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

        $merchantId = paymentGatewayValue('phonepe', 'PHONEPE_MERCHANT_ID');
        $saltKey = paymentGatewayValue('phonepe', 'PHONEPE_SALT_KEY');
        $saltIndex = paymentGatewayValue('phonepe', 'PHONEPE_SALT_INDEX');
        
        $clientId = paymentGatewayValue('phonepe', 'PHONEPE_CLIENT_ID');
        $clientSecret = paymentGatewayValue('phonepe', 'PHONEPE_CLIENT_SECRET');
        $clientVersion = paymentGatewayValue('phonepe', 'PHONEPE_CLIENT_VERSION') ?? '1';

        $isSandbox = paymentGateway('phonepe')->sandbox;

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
            curl_close($response);

        } elseif ($clientId && $clientSecret) {
            // Use V2 API if ONLY Client ID and Client Secret are provided
            Log::info('PhonePe: Using V2 API Flow (Client ID + Client Secret)');
            $accessToken = $this->getAccessToken($clientId, $clientSecret, $clientVersion, $isSandbox);
            if (!$accessToken) {
                Log::error('PhonePe: Failed to get Access Token');
                return (new PaymentsController)->payment_failed();
            }

            // Derive merchantId from clientId if PHONEPE_MERCHANT_ID is empty
            $v2MerchantId = $merchantId ?: explode('_', $clientId)[0];

            $transactionId = 'TXN' . time();
            $callbackUrl = route('phonepe.callback');
            
            $data = array(
                'merchantId' => $v2MerchantId, 
                'merchantTransactionId' => $transactionId,
                'merchantUserId' => 'MUID' . auth()->id(),
                'amount' => (int) round($amount * 100), // Amount in paise
                'redirectUrl' => $callbackUrl,
                'redirectMode' => 'REDIRECT',
                'callbackUrl' => $callbackUrl,
                'paymentInstrument' => array(
                    'type' => 'PAY_PAGE'
                )
            );

            $encode = base64_encode(json_encode($data));
            
            // For V2 Sandbox, X-VERIFY is often still required even with the token.
            // We use the Client Secret as the Salt Key if a separate one is not provided.
            $v2SaltKey = paymentGatewayValue('phonepe', 'PHONEPE_SALT_KEY') ?: $clientSecret;
            $v2SaltIndex = paymentGatewayValue('phonepe', 'PHONEPE_SALT_INDEX') ?: $clientVersion;

            if ($isSandbox) {
                $url = "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay";
            } else {
                $url = "https://api.phonepe.com/apis/hermes/pg/v1/pay";
            }

            $headers = array(
                'Content-Type: application/json',
                'Authorization: O-Bearer ' . $accessToken,
                'X-CLIENT-ID: ' . $v2MerchantId,
                'X-CLIENT-VERSION: ' . $clientVersion
            );

            if ($v2SaltKey && $v2SaltIndex) {
                $string = $encode . '/pg/v1/pay' . $v2SaltKey;
                $sha256 = hash('sha256', $string);
                $finalHeader = $sha256 . '###' . $v2SaltIndex;
                $headers[] = 'X-VERIFY: ' . $finalHeader;
                Log::info('PhonePe: Added X-VERIFY to V2 Flow using Client Secret as salt');
            }

            Log::info('PhonePe V2 Initiation URL: ' . $url);
            Log::info('PhonePe V2 Payload: ' . json_encode($data));
            Log::info('PhonePe V2 Headers: ' . json_encode($headers));

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
                CURLOPT_POSTFIELDS => json_encode(['request' => $encode]), // Wrapped in request
                CURLOPT_HTTPHEADER => $headers,
            ));

            $result = curl_exec($response);
            curl_close($response);

        } else {
            Log::error('PhonePe: No valid credentials found (neither V1 nor V2).');
            return (new PaymentsController)->payment_failed();
        }

        Log::info('PhonePe Response: ' . $result);
        $res = json_decode($result);

        if (isset($res->success) && $res->success == true) {
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
        curl_close($response);

        Log::info('PhonePe Token Response: ' . $result);
        $res = json_decode($result);

        return $res->access_token ?? null;
    }

    /**
     * Callback from PhonePe
     */
    public function callback(Request $request)
    {
        Log::info('PhonePe Callback received: ' . json_encode($request->all()));
        if ($request->code == 'PAYMENT_SUCCESS') {
            $payment_details = json_encode($request->all());
            return (new PaymentsController)->payment_success($payment_details);
        } else {
            Log::error('PhonePe Callback failed with code: ' . $request->code);
            return (new PaymentsController)->payment_failed();
        }
    }
}
