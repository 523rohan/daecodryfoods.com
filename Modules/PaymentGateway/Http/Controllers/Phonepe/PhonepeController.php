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
        $amount = session('amount');
        if (!$amount && session('order_code')) {
            $orderGroup = \App\Models\OrderGroup::where('order_code', session('order_code'))->first();
            if ($orderGroup) {
                $amount = $orderGroup->grand_total_amount;
            }
        }
        
        if (!$amount) {
            return (new PaymentsController)->payment_failed();
        }
        
        // PhonePe only supports INR
        if (Session::has('currency_code')) {
            if (strtoupper(Session::get('currency_code')) == 'INR') {
                $amount = formatPrice($amount, false, false, false, false);
            } else {
                // If not INR, we should convert to INR. 
                // Since INR is default (rate 1), priceToUsd actually returns the amount in default currency (INR)
                $amount = priceToUsd($amount);
            }
        }

        $merchantId = paymentGatewayValue('phonepe', 'PHONEPE_MERCHANT_ID');
        $saltKey = paymentGatewayValue('phonepe', 'PHONEPE_SALT_KEY');
        $saltIndex = paymentGatewayValue('phonepe', 'PHONEPE_SALT_INDEX');
        
        $transactionId = 'TXN' . time();
        $callbackUrl = route('phonepe.callback');
        
        $data = array(
            'merchantId' => $merchantId,
            'merchantTransactionId' => $transactionId,
            'merchantUserId' => 'MUID' . auth()->id(),
            'amount' => $amount * 100, // Amount in paise
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

        $isSandbox = paymentGateway('phonepe')->sandbox;
        if ($isSandbox) {
            $url = "https://api-preprod.phonepe.com/apis/hermes/pg/v1/pay";
        } else {
            $url = "https://api.phonepe.com/apis/hermes/pg/v1/pay";
        }

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

        Log::info('PhonePe Response: ' . $result);
        $res = json_decode($result);

        if (isset($res->success) && $res->success == true) {
            return redirect()->to($res->data->instrumentResponse->redirectInfo->url);
        } else {
            return (new PaymentsController)->payment_failed();
        }
    }

    /**
     * Callback from PhonePe
     */
    public function callback(Request $request)
    {
        if ($request->code == 'PAYMENT_SUCCESS') {
            $payment_details = json_encode($request->all());
            return (new PaymentsController)->payment_success($payment_details);
        } else {
            return (new PaymentsController)->payment_failed();
        }
    }
}
