<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Transaction;

class WebMoneyController extends Controller
{
    public function pay(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'LMI_PAYMENT_NO' => 'required|string',
            'LMI_PAYMENT_DESC' => 'required|string',
        ]);

        // $boy =config('services.webmoney.merchant_purse');
        // echo $boy;
        // die;

        // Store transaction locally before redirecting
        Transaction::create([
            'user_id' => Auth::id(),
            'payment_id' => $request->LMI_PAYMENT_NO,
            'amount' => $request->amount,
            'currency' => 'RUB', // Assuming RUB, adjust if necessary
            'payment_method' => 'WebMoney',
            'status' => 'pending',
            'description' => $request->LMI_PAYMENT_DESC,
        ]);

        // Prepare form data for WebMoney
        $formData = [
            'LMI_PAYMENT_AMOUNT' => $request->amount,
            'LMI_PAYMENT_NO' => $request->LMI_PAYMENT_NO,
            'LMI_PAYMENT_DESC' => $request->LMI_PAYMENT_DESC,
            'LMI_PAYEE_PURSE' => config('services.webmoney.merchant_purse'),
            'LMI_SIM_MODE' => config('services.webmoney.sim_mode'), // 0 for real payments, 1 for test payments
            // Add other required fields by WebMoney if any
        ];

        // The action URL for WebMoney Merchant service
        $webMoneyUrl = 'https://merchant.webmoney.ru/lmi/payment.asp';

        // Return a view with a self-submitting form
        return view('payment.webmoney_redirect', compact('webMoneyUrl', 'formData'));
    }

    public function success(Request $request)
    {
        // Handle successful payment callback from WebMoney
        // Verify the payment signature and other parameters as per WebMoney documentation
        // This is a simplified example. You MUST implement proper verification.

        $payment_no = $request->input('LMI_PAYMENT_NO');
        $transaction = Transaction::where('payment_id', $payment_no)->where('status', 'pending')->first();

        if ($transaction) {
            // Check LMI_HASH for security if configured
            // For example: 
            // $secretKey = 'YOUR_SECRET_KEY'; // Keep this secret!
            // $stringToHash = $request->input('LMI_PAYEE_PURSE') . 
            //                 $request->input('LMI_PAYMENT_AMOUNT') . 
            //                 $request->input('LMI_PAYMENT_NO') . 
            //                 $request->input('LMI_MODE') . 
            //                 $request->input('LMI_SYS_INVS_NO') . 
            //                 $request->input('LMI_SYS_TRANS_NO') . 
            //                 $request->input('LMI_SYS_TRANS_DATE') . 
            //                 $secretKey . // This was an example, actual usage is below for result URL
            //                 $request->input('LMI_PAYER_PURSE') . 
            //                 $request->input('LMI_PAYER_WM');
            // $generatedHash = strtoupper(md5($stringToHash)); // or hash('sha256', $stringToHash)
            // if ($generatedHash !== $request->input('LMI_HASH')) {
            //     // Hash mismatch, potential fraud
            //     return redirect()->route('payment.failed')->with('error', 'Payment verification failed.');
            // }

            $user = User::find($transaction->user_id);
            if ($user) {
                $user->balance += $transaction->amount;
                $user->save();

                $transaction->status = 'completed';
                $transaction->save();

                return redirect()->route('user.transaction.index')->with('success', 'Баланс успешно пополнен!');
            }
        }

        return redirect()->route('payment.failed')->with('error', 'Ошибка обработки платежа.');
    }

    public function fail(Request $request)
    {
        // Handle failed payment callback from WebMoney
        $payment_no = $request->input('LMI_PAYMENT_NO');
        if ($payment_no) {
            // Find the transaction by payment_id, ensuring it's not already completed.
            $transaction = Transaction::where('payment_id', $payment_no)
                                      ->where('status', '!=', 'completed')
                                      ->first();
            
            if ($transaction) {
                // Update status to 'failed' as WebMoney directed to the fail URL.
                $transaction->status = 'failed';
                $transaction->save();
            } else {
                // Log if transaction not found or already completed, for debugging
                // \Illuminate\Support\Facades\Log::warning('WebMoney Fail: Transaction not found or already completed for LMI_PAYMENT_NO: ' . $payment_no);
            }
        }
        return redirect()->route('user.transaction.index')->with('error', 'Платеж не удался или был отменен.');
    }

    public function result(Request $request)
    {
        // Handle WebMoney result/notification URL (server-to-server)
        // This is where WebMoney sends notifications about payment status changes.
        // You MUST implement robust verification here as per WebMoney documentation.
        // This includes checking LMI_HASH, LMI_PREREQUEST, etc.

        // Example: Prerequest handling
        if ($request->has('LMI_PREREQUEST') && $request->input('LMI_PREREQUEST') == '1') {
            // This is a pre-request. Check if payment is possible.
            // For example, check if LMI_PAYMENT_NO is unique, if the amount is valid, etc.
            $payment_no = $request->input('LMI_PAYMENT_NO');
            $transactionExists = Transaction::where('payment_id', $payment_no)->exists();

            if ($transactionExists) {
                // Payment_no already exists, might be a duplicate or an issue.
                // Log this event for investigation.
                // According to WebMoney docs, if you cannot process, you should not output YES.
                // However, for simplicity here, we assume it's okay if it's pending.
                $transaction = Transaction::where('payment_id', $payment_no)->first();
                if ($transaction && $transaction->status === 'pending') {
                     // If order exists and is pending, it's okay to proceed with payment
                     // Log::info('WebMoney Prerequest: Payment_no ' . $payment_no . ' exists and is pending.');
                     // echo 'YES'; // Respond YES if payment can be accepted
                     // exit;
                } else {
                    // Log::warning('WebMoney Prerequest: Payment_no ' . $payment_no . ' already processed or invalid.');
                    // echo 'Error: Payment already processed or invalid.';
                    // exit;
                }
            }
            
            // Further checks: amount, currency, payee purse etc.
            // if ( $request->input('LMI_PAYEE_PURSE') !== 'YOUR_WEBMONEY_PURSE') { 
            //     // Log::error('WebMoney Prerequest: Incorrect payee purse.');
            //     // echo 'Error: Incorrect payee purse.';
            //     // exit;
            // }

            // If all checks pass for prerequest:
            // Log::info('WebMoney Prerequest: Payment_no ' . $payment_no . ' is valid.');
            echo 'YES';
            exit;
        }

        // This is a payment notification (not a pre-request)
        // Verify LMI_HASH
        $secretKey = config('services.webmoney.secret_key');
        if (empty($secretKey)) {
            // Log::error('WebMoney Result: Secret key is not configured.');
            // Consider how to handle this - perhaps respond with an error or do not process.
            // For security, if secret key is missing, you should not process the payment.
            exit; // Or echo some error message that WebMoney expects
        }

        $stringToHash = $request->input('LMI_PAYEE_PURSE') . 
                        $request->input('LMI_PAYMENT_AMOUNT') . 
                        $request->input('LMI_PAYMENT_NO') . 
                        $request->input('LMI_MODE') . 
                        $request->input('LMI_SYS_INVS_NO') . 
                        $request->input('LMI_SYS_TRANS_NO') . 
                        $request->input('LMI_SYS_TRANS_DATE') . 
                        $secretKey . 
                        $request->input('LMI_PAYER_PURSE') . 
                        $request->input('LMI_PAYER_WM');
        
        $generatedHash = strtoupper(md5($stringToHash));

        if ($generatedHash !== $request->input('LMI_HASH')) {
            // Log::error('WebMoney Result: Hash mismatch. Payment_no: ' . $request->input('LMI_PAYMENT_NO'));
            // Hash mismatch, potential fraud. Do not process the payment.
            // WebMoney expects no output or a specific error message if the hash is incorrect.
            // Consult WebMoney documentation for the exact expected response in case of hash mismatch.
            exit;
        }

        // Hash is correct, proceed to update transaction and user balance
        $payment_no = $request->input('LMI_PAYMENT_NO');
        $transaction = Transaction::where('payment_id', $payment_no)->where('status', 'pending')->first();

        if ($transaction) {
            $user = User::find($transaction->user_id);
            if ($user) {
                $user->balance += $transaction->amount;
                $user->save();

                $transaction->status = 'completed';
                $transaction->payment_system_trans_id = $request->input('LMI_SYS_TRANS_NO'); // Store WebMoney transaction ID
                $transaction->paid_at = now(); // Record payment time
                $transaction->save();

                // Log::info('WebMoney Result: Payment successful. Payment_no: ' . $payment_no . ', User ID: ' . $user->id . ', Amount: ' . $transaction->amount);
                // WebMoney expects no output on successful processing of the notification.
                exit;
            } else {
                // Log::error('WebMoney Result: User not found for transaction. Payment_no: ' . $payment_no);
            }
        } else {
            // Log::warning('WebMoney Result: Transaction not found or not pending. Payment_no: ' . $payment_no);
            // This could happen if the notification is received multiple times or if the transaction was already processed/failed.
        }
        // If something went wrong or transaction was already processed, WebMoney expects no output or a specific error.
        // Consult WebMoney documentation.
        exit;
    }
}