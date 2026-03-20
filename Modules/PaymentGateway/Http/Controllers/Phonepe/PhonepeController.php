<?php

namespace Modules\PaymentGateway\Http\Controllers\Phonepe;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Backend\Payments\PaymentsController;

class PhonepeController extends Controller
{
    /**
     * Init payment
     */
    public function initPayment()
    {
        Log::info('PhonePe initPayment called. Session order_code: ' . session('order_code') . ', Session amount: ' . session('amount'));
        $amount = session('amount');
        if (!$amount && session('order_code')) {
            $orderGroup = \App\Models\OrderGroup::where('order_code', session('order_code'))->first();
            if ($orderGroup) {
                $amount = $orderGroup->grand_total_amount;
                Log::info('PhonePe amount retrieved from OrderGroup: ' . $amount);
            } else {
                Log::error('PhonePe: OrderGroup not found for code: ' . session('order_code'));
            }
        }
        
        if (!$amount) {
            Log::error('PhonePe: Amount is empty, failing payment.');
            return (new PaymentsController)->payment_failed();
        }
        
        // PhonePe only supports INR
        if (Session::has('currency_code')) {
            if (strtoupper(Session::get('currency_code')) == 'INR') {
                $amount = formatPrice($amount, false, false, false, false);
            } else {
                $amount = priceToUsd($amount);
            }
        }

        $amount = (float) str_replace(',', '', (string) $amount);

        $gateway = \Modules\PaymentGateway\Entities\PaymentGateway::where('gateway', 'phonepe')->first();
        if (!$gateway) {
            Log::error('PhonePe: Gateway not found in database');
            return (new PaymentsController)->payment_failed();
        }

        $gatewayDetails = \Modules\PaymentGateway\Entities\PaymentGatewayDetail::where('payment_gateway_id', $gateway->id)
            ->pluck('value', 'key');

        $merchantId = trim((string) $gatewayDetails->get('PHONEPE_MERCHANT_ID', ''));
        $saltKey = trim((string) $gatewayDetails->get('PHONEPE_SALT_KEY', ''));
        $saltIndex = trim((string) $gatewayDetails->get('PHONEPE_SALT_INDEX', ''));

        Log::info('PhonePe Settings Read (Direct DB): MerchantID='.$merchantId.', SaltKey=' . (empty($saltKey) ? 'EMPTY' : 'PRESENT') . ', SaltIndex='.$saltIndex);

        $clientId = trim((string) $gatewayDetails->get('PHONEPE_CLIENT_ID', ''));
        $clientSecret = trim((string) $gatewayDetails->get('PHONEPE_CLIENT_SECRET', ''));
        $clientVersion = trim((string) $gatewayDetails->get('PHONEPE_CLIENT_VERSION', '')) ?: '1';

        $isSandbox = $gateway->sandbox;

        // Prioritize V1 API if Merchant ID and Salt Key are provided, as it's more stable for Hosted Checkout
        if ($merchantId && $saltKey && $saltIndex) {
            Log::info('PhonePe: Using V1 API Flow (Merchant ID + Salt Key)');
            
            $transactionId = 'TXN' . time();
            $callbackUrl = route('phonepe.callback');
            
            $data = array(
                'merchantId' => $merchantId,
                'merchantTransactionId' => $transactionId,
                'merchantUserId' => 'MUID' . auth()->id(),
                'amount' => (int) round($amount * 100), // Amount in paise
                'redirectUrl' => $callbackUrl,
                'redirectMode' => 'POST',
                'callbackUrl' => $callbackUrl,
                'paymentInstrument' => array(
                    'type' => 'PAY_PAGE'
                )
            );

            $encode = base64_encode(json_encode($data));
            $string = $encode . '/pg/v1/pay' . $saltKey;
            $sha256 = hash('sha256', $string);
            $finalHeader = $sha256 . '###' . $saltIndex;

            if ($isSandbox) {
                $url = "https://api-preprod.phonepe.com/apis/hermes/pg/v1/pay";
            } else {
                $url = "https://api.phonepe.com/apis/hermes/pg/v1/pay";
            }

            Log::info('PhonePe V1 Initiation URL: ' . $url);
            Log::info('PhonePe V1 Payload: ' . json_encode($data));

            $response = curl_init();
            curl_setopt_array($response, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => false,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode(['request' => $encode]),
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'X-VERIFY: ' . $finalHeader
                ),
            ));

            $result = curl_exec($response);
            $curlError = curl_error($response);
            $httpCode = curl_getinfo($response, CURLINFO_HTTP_CODE);
            curl_close($response);

        } elseif ($clientId && $clientSecret) {
            // Use V2 Standard Checkout if client credentials are available.
            Log::info('PhonePe: Using V2 API Flow (Client ID + Client Secret)');
            $tokenData = $this->getAccessToken($clientId, $clientSecret, $clientVersion, $isSandbox);
            if (empty($tokenData['access_token'])) {
                Log::error('PhonePe: Failed to get Access Token');
                return (new PaymentsController)->payment_failed();
            }

            $merchantOrderId = $this->buildMerchantOrderId();
            // Pass merchantOrderId in the URL to ensure it's available in the callback even if session is lost
            $redirectUrl = route('phonepe.callback', ['merchantOrderId' => $merchantOrderId]);
            
            $data = array(
                'amount' => (int) round($amount * 100), // Amount in paise
                'merchantOrderId' => $merchantOrderId,
                'paymentFlow' => array(
                    'type' => 'PG_CHECKOUT',
                    'merchantUrls' => array(
                        'redirectUrl' => $redirectUrl,
                    ),
                )
            );

            if ($isSandbox) {
                $url = "https://api-preprod.phonepe.com/apis/pg-sandbox/checkout/v2/pay";
            } else {
                $url = "https://api.phonepe.com/apis/pg/checkout/v2/pay";
            }

            $authorizationType = $tokenData['token_type'] ?? 'O-Bearer';
            $headers = array(
                'Content-Type: application/json',
                'Authorization: ' . $authorizationType . ' ' . $tokenData['access_token']
            );

            Log::info('PhonePe V2 Initiation URL: ' . $url);
            Log::info('PhonePe V2 Payload (Direct JSON): ' . json_encode($data));
            Log::info('PhonePe V2 Headers: ' . json_encode($headers));

            $response = curl_init();
            curl_setopt_array($response, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => false,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => $headers,
            ));

            $result = curl_exec($response);
            $curlError = curl_error($response);
            $httpCode = curl_getinfo($response, CURLINFO_HTTP_CODE);
            curl_close($response);

        } else {
            Log::error('PhonePe: No valid credentials found (neither V1 nor V2).');
            return (new PaymentsController)->payment_failed();
        }

        if (!empty($curlError)) {
            Log::error('PhonePe cURL Error: ' . $curlError);
        }

        Log::info('PhonePe HTTP Status: ' . ($httpCode ?? 'N/A'));
        Log::info('PhonePe Response: ' . $result);
        $res = json_decode($result);

        if (isset($res->redirectUrl) && !empty($res->redirectUrl)) {
            session([
                'phonepe_merchant_order_id' => $merchantOrderId ?? null,
                'phonepe_pg_order_id' => $res->orderId ?? null,
            ]);

            return redirect()->away($res->redirectUrl);
        } elseif (isset($res->success) && $res->success == true) {
            return redirect()->to($res->data->instrumentResponse->redirectInfo->url);
        } else {
            Log::error('PhonePe API Error: ' . ($res->message ?? 'Unknown error'));
            return (new PaymentsController)->payment_failed();
        }
    }

    /**
     * Get OAuth token for V2 API
     */
    private function getAccessToken($clientId, $clientSecret, $clientVersion, $isSandbox)
    {
        if ($isSandbox) {
            $url = "https://api-preprod.phonepe.com/apis/pg-sandbox/v1/oauth/token";
        } else {
            $url = "https://api.phonepe.com/apis/identity-manager/v1/oauth/token";
        }

        $fields = [
            'grant_type' => 'client_credentials',
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'client_version' => $clientVersion
        ];

        $response = curl_init();
        curl_setopt_array($response, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => http_build_query($fields),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $result = curl_exec($response);
        $curlError = curl_error($response);
        curl_close($response);

        if (!empty($curlError)) {
            Log::error('PhonePe Token cURL Error: ' . $curlError);
        }

        Log::info('PhonePe Token Response: ' . $result);
        $res = json_decode($result);

        if (!isset($res->access_token)) {
            return null;
        }

        return [
            'access_token' => $res->access_token,
            'token_type' => $res->token_type ?? 'O-Bearer',
        ];
    }

    /**
     * Callback from PhonePe
     */
    public function callback(Request $request)
    {
        Log::info('PhonePe Callback URL: ' . $request->fullUrl());
        Log::info('PhonePe Callback Method: ' . $request->method());
        Log::info('PhonePe Callback Params: ' . json_encode($request->all()));
        
        // Also log raw body in case PhonePe sends JSON without correct Content-Type
        $rawBody = file_get_contents('php://input');
        if ($rawBody) {
            Log::info('PhonePe Callback Raw Body: ' . $rawBody);
        }

        $gateway = \Modules\PaymentGateway\Entities\PaymentGateway::where('gateway', 'phonepe')->first();
        $gatewayDetails = $gateway
            ? \Modules\PaymentGateway\Entities\PaymentGatewayDetail::where('payment_gateway_id', $gateway->id)->pluck('value', 'key')
            : collect();

        $clientId = trim((string) $gatewayDetails->get('PHONEPE_CLIENT_ID', ''));
        $clientSecret = trim((string) $gatewayDetails->get('PHONEPE_CLIENT_SECRET', ''));
        $clientVersion = trim((string) $gatewayDetails->get('PHONEPE_CLIENT_VERSION', '')) ?: '1';
        $merchantOrderId = $request->input('merchantOrderId') ?: session('phonepe_merchant_order_id');

        if ($merchantOrderId && $clientId && $clientSecret && $gateway) {
            $statusResponse = $this->getOrderStatus($merchantOrderId, $clientId, $clientSecret, $clientVersion, (bool) $gateway->sandbox);
            $statusState = strtoupper((string) data_get($statusResponse, 'state'));

            Log::info('PhonePe Order Status Response: ' . json_encode($statusResponse));

            if ($statusState === 'COMPLETED') {
                session()->forget(['phonepe_merchant_order_id', 'phonepe_pg_order_id']);

                $payment_details = json_encode([
                    'callback' => $request->all(),
                    'status' => $statusResponse,
                ]);
                return (new PaymentsController)->payment_success($payment_details);
            }

            Log::error('PhonePe Callback status not completed. MerchantOrderId=' . $merchantOrderId . ', State=' . ($statusState ?: 'UNKNOWN'));
        }

        if ($request->code == 'PAYMENT_SUCCESS') {
            session()->forget(['phonepe_merchant_order_id', 'phonepe_pg_order_id']);
            $payment_details = json_encode($request->all());
            return (new PaymentsController)->payment_success($payment_details);
        }

        Log::error('PhonePe Callback failed with code: ' . $request->code);
        session()->forget(['phonepe_merchant_order_id', 'phonepe_pg_order_id']);
        return (new PaymentsController)->payment_failed();
    }

    private function getOrderStatus($merchantOrderId, $clientId, $clientSecret, $clientVersion, $isSandbox)
    {
        $tokenData = $this->getAccessToken($clientId, $clientSecret, $clientVersion, $isSandbox);
        if (empty($tokenData['access_token'])) {
            Log::error('PhonePe: Failed to get access token for order status.');
            return null;
        }

        $baseUrl = $isSandbox
            ? 'https://api-preprod.phonepe.com/apis/pg-sandbox'
            : 'https://api.phonepe.com/apis/pg';

        $url = $baseUrl . '/checkout/v2/order/' . urlencode($merchantOrderId) . '/status?details=false';
        $headers = [
            'Content-Type: application/json',
            'Authorization: ' . ($tokenData['token_type'] ?? 'O-Bearer') . ' ' . $tokenData['access_token'],
        ];

        $response = curl_init();
        curl_setopt_array($response, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTPHEADER => $headers,
        ]);

        $result = curl_exec($response);
        $curlError = curl_error($response);
        $httpCode = curl_getinfo($response, CURLINFO_HTTP_CODE);
        curl_close($response);

        if (!empty($curlError)) {
            Log::error('PhonePe Order Status cURL Error: ' . $curlError);
        }

        Log::info('PhonePe Order Status HTTP Status: ' . $httpCode);

        return json_decode($result, true);
    }

    private function buildMerchantOrderId()
    {
        $base = session('order_code') ?: ('ORDER' . time());
        $base = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $base);

        return strtoupper($base . '_' . time());
    }
}
