<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EazypayController extends Controller
{
    public function initiatePayment(Request $request)
    {
        $merchantId = env('EAZYPAY_MERCHANT_ID');
        $subMerchantId = env('EAZYPAY_SUB_MERCHANT_ID');
        $encryptionKey = env('EAZYPAY_ENCRYPTION_KEY');
        $returnUrl = env('EAZYPAY_RETURN_URL');
        $baseUrl = env('EAZYPAY_BASE_URL'); // UAT Base URL

        $amount = $request->input('amount');
        $referenceNo = uniqid('ORD_'); // Generate unique order reference

        // Prepare Data for Hash (Check latest ICICI Documentation for required parameters)
        $dataString = "{$merchantId}|{$subMerchantId}|{$referenceNo}|{$amount}|INR|{$returnUrl}";

        // Generate Hash (SHA256 Encryption)
        $hash = hash('sha256', $dataString . '|' . $encryptionKey);

        // Construct Payment URL (UAT Environment)
        $paymentUrl = "{$baseUrl}/pay?merchantid={$merchantId}&submerchantid={$subMerchantId}&orderid={$referenceNo}&amount={$amount}&currency=INR&returnurl={$returnUrl}&hash={$hash}";

        // Debugging: Log the Payment URL for testing
        \Log::info("Generated Payment URL: " . $paymentUrl);

        // Redirect User to Payment Page
        return redirect()->away($paymentUrl);
    }

    public function handleResponse(Request $request)
    {
        \Log::info("Eazypay Response: ", $request->all());

        if ($request->input('status') === 'success') {
            return view('eazypay.success', ['response' => $request->all()]);
        } else {
            return view('eazypay.failed', ['response' => $request->all()]);
        }
    }
}
