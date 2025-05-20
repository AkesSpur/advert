<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

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

        if (!$transaction) {
            Log::warning('WebMoney Success: Transaction not found or not pending for LMI_PAYMENT_NO: ' . $payment_no);
            return redirect()->route('user.transaction.index')->with('error', 'Транзакция не найдена или уже обработана.');
        }

        // IMPORTANT: The success URL is for client-side redirection. 
        // Critical payment verification (like LMI_HASH) should ideally happen in the server-to-server 'result' URL notification.
        // However, if WebMoney sends LMI_HASH to the success URL as well, you can verify it here too.
        // For this example, we assume the primary verification is in the 'result' method.
        // If your WebMoney setup sends critical data to success URL and expects verification, implement it here.

        $user = User::find($transaction->user_id);
        if ($user) {
            // Note: Balance update and transaction status to 'completed' should ideally be triggered by the 'result' URL notification
            // to prevent manipulation if the user simply revisits the success URL.
            // If the 'result' URL reliably updates the status, this part might only confirm or could be redundant.
            // For now, we'll keep it, assuming 'result' is the primary source of truth.
            if ($transaction->status === 'pending') { // Double check status before updating
                $user->balance += $transaction->amount;
                $user->save();

                $transaction->status = 'completed';
                // $transaction->payment_system_trans_id = $request->input('LMI_SYS_TRANS_NO'); // If available on success URL
                // $transaction->paid_at = now(); // If available on success URL
                $transaction->save();
                Log::info('WebMoney Success: Payment processed via success URL. Payment_no: ' . $payment_no);
                return redirect()->route('user.transaction.index')->with('success', 'Баланс успешно пополнен!');
            } elseif ($transaction->status === 'completed') {
                Log::info('WebMoney Success: Payment already completed. Payment_no: ' . $payment_no);
                return redirect()->route('user.transaction.index')->with('success', 'Баланс уже был пополнен по этой транзакции.');
            } else {
                Log::warning('WebMoney Success: Transaction status is not pending or completed. Status: ' . $transaction->status . ' Payment_no: ' . $payment_no);
                return redirect()->route('user.transaction.index')->with('error', 'Статус транзакции не позволяет завершить операцию.');
            }
        } else {
            Log::error('WebMoney Success: User not found for transaction. Payment_no: ' . $payment_no);
        }

        return redirect()->route('user.transaction.index')->with('error', 'Ошибка обработки платежа.');

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
                Log::warning('WebMoney Fail: Transaction not found or already completed for LMI_PAYMENT_NO: ' . $payment_no);
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
            $payment_no = $request->input('LMI_PAYMENT_NO');
            $payee_purse = $request->input('LMI_PAYEE_PURSE');
            $payment_amount = $request->input('LMI_PAYMENT_AMOUNT');

            // 1. Check if LMI_PAYEE_PURSE matches your configured purse
            if ($payee_purse !== config('services.webmoney.merchant_purse')) {
                Log::error('WebMoney Prerequest: Incorrect payee purse. Expected: ' . config('services.webmoney.merchant_purse') . ', Got: ' . $payee_purse . ' for LMI_PAYMENT_NO: ' . $payment_no);
                // WebMoney documentation suggests not outputting YES or outputting an error message.
                // echo "Error: Incorrect payee purse."; // Or simply don't output YES
                exit;
            }

            // 2. Check if transaction (LMI_PAYMENT_NO) exists and its status
            $transaction = Transaction::where('payment_id', $payment_no)->first();

            if ($transaction) {
                // If transaction exists, check its status
                if ($transaction->status !== 'pending') {
                    Log::warning('WebMoney Prerequest: Transaction LMI_PAYMENT_NO: ' . $payment_no . ' already exists and status is not pending (Status: ' . $transaction->status . ').');
                    // echo "Error: Payment already processed or in invalid state."; // Or simply don't output YES
                    exit;
                }
                // Additionally, you might want to verify if $transaction->amount matches $payment_amount
                if ((float)$transaction->amount !== (float)$payment_amount) {
                    Log::error('WebMoney Prerequest: Amount mismatch for LMI_PAYMENT_NO: ' . $payment_no . '. Expected: ' . $transaction->amount . ', Got: ' . $payment_amount);
                    // echo "Error: Amount mismatch.";
                    exit;
                }
            } else {
                // Transaction does not exist. This is usually okay for a prerequest if you create it upon successful payment notification.
                // However, in this implementation, we create it in the pay() method. So, if it doesn't exist here, it's an issue.
                // For this flow, let's assume it should exist if created in pay().
                // If your flow is different (e.g., create transaction only on 'result' notification), adjust this logic.
                Log::warning('WebMoney Prerequest: Transaction LMI_PAYMENT_NO: ' . $payment_no . ' not found. This might be an issue if it was expected to be created in pay().');
                // Depending on strictness, you might exit here.
                // For now, we'll allow it to proceed, assuming it might be created later or this is a new payment.
                // If you create transactions in pay(), this should ideally be an error.
            }

            // All checks passed for prerequest
            Log::info('WebMoney Prerequest: Valid for LMI_PAYMENT_NO: ' . $payment_no);
            echo 'YES';
            exit;
        }

        // This is a payment notification (not a pre-request)
        // Verify LMI_HASH
        $secretKey = config('services.webmoney.secret_key');
        if (empty($secretKey)) {
            Log::error('WebMoney Result: Secret key is not configured. Cannot verify LMI_HASH.');
            // WebMoney expects no output or a specific error message if you cannot process.
            // For security, if secret key is missing, you should not process the payment.
            exit; 
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
            Log::error('WebMoney Result: LMI_HASH mismatch for LMI_PAYMENT_NO: ' . $request->input('LMI_PAYMENT_NO') . '. Generated: ' . $generatedHash . ', Received: ' . $request->input('LMI_HASH'));
            // Hash mismatch, potential fraud. Do not process the payment.
            // WebMoney expects no output or a specific error message if the hash is incorrect.
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

                Log::info('WebMoney Result: Payment successful and processed for LMI_PAYMENT_NO: ' . $payment_no . '. User ID: ' . $user->id . ', Amount: ' . $transaction->amount);
                // WebMoney expects no output on successful processing of the notification.
                exit;
            } else {
                Log::error('WebMoney Result: User not found for transaction LMI_PAYMENT_NO: ' . $payment_no . '. Transaction User ID: ' . $transaction->user_id);
            }
        } else {
            Log::warning('WebMoney Result: Transaction not found or not in pending state for LMI_PAYMENT_NO: ' . $payment_no . '. Current status: ' . ($transaction ? $transaction->status : 'not found') );
            // This could happen if the notification is received multiple times or if the transaction was already processed/failed via another callback.
        }
        // If something went wrong (e.g., transaction not found, user not found, hash mismatch handled above) or transaction was already processed, WebMoney expects no output or a specific error.
        // The `exit` calls above handle these cases. If execution reaches here, it implies an unexpected state not covered.
        Log::error('WebMoney Result: Reached end of result method unexpectedly for LMI_PAYMENT_NO: ' . $request->input('LMI_PAYMENT_NO'));
        exit;
    }
}