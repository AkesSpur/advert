<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Transaction;
use App\Models\GeneralSetting; // Import GeneralSetting
use Illuminate\Support\Facades\Log;

class WebMoneyController extends Controller
{
    public function pay(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1', // This is the amount in RUB the user wants to pay
            'LMI_PAYMENT_NO' => 'required|string',
            'LMI_PAYMENT_DESC' => 'required|string',
        ]);

        // Store transaction locally before redirecting
        Transaction::create([
            'user_id' => Auth::id(),
            'payment_id' => $request->LMI_PAYMENT_NO,
            'amount' => $request->amount, // This is the intended RUB amount
            'currency' => 'RUB',
            'payment_method' => 'WebMoney',
            'status' => 'pending',
            'description' => $request->LMI_PAYMENT_DESC,
        ]);

        $formData = [
            'LMI_PAYMENT_AMOUNT' => $request->amount, // Sending RUB amount
            'LMI_PAYMENT_NO' => $request->LMI_PAYMENT_NO,
            'LMI_PAYMENT_DESC' => $request->LMI_PAYMENT_DESC,
            'LMI_PAYEE_PURSE' => config('services.webmoney.merchant_purse'),
            'LMI_SIM_MODE' => config('services.webmoney.sim_mode'), 
        ];

        $webMoneyUrl = 'https://merchant.webmoney.ru/lmi/payment.asp';

        return view('payment.webmoney_redirect', compact('webMoneyUrl', 'formData'));
    }

    public function success(Request $request)
    {
        $payment_no = $request->input('LMI_PAYMENT_NO');
        $transaction = Transaction::where('payment_id', $payment_no)->first(); // Check status later

        if (!$transaction) {
            Log::warning('WebMoney Success: Transaction not found for LMI_PAYMENT_NO: ' . $payment_no);
            return redirect()->route('user.transaction.index')->with('error', 'Транзакция не найдена.');
        }

        // The success URL is for client-side. Primary logic is in 'result'.
        // Here, we mostly confirm based on what 'result' should have done.
        if ($transaction->status == 'completed') {
            Log::info('WebMoney Success: Payment already completed based on result notification. Payment_no: ' . $payment_no);
            return redirect()->route('user.transaction.index')->with('success', 'Баланс успешно пополнен (подтверждено).');
        } elseif ($transaction->status == 'pending') {
            // This case is less ideal. It means 'result' might not have been called or processed yet.
            // Or, if 'result' failed, status might be 'failed'.
            Log::warning('WebMoney Success: Transaction still pending for LMI_PAYMENT_NO: ' . $payment_no . '. User redirected to success page. Waiting for server notification.');
            return redirect()->route('user.transaction.index')->with('info', 'Платеж обрабатывается. Статус будет обновлен в ближайшее время.');
        } elseif ($transaction->status == 'failed') {
            Log::info('WebMoney Success: User redirected to success URL, but transaction was marked as failed. Payment_no: ' . $payment_no);
            return redirect()->route('user.transaction.index')->with('error', 'Во время обработки платежа произошла ошибка. Пожалуйста, свяжитесь со службой поддержки.');
        }

        return redirect()->route('user.transaction.index')->with('info', 'Статус вашего платежа: ' . $transaction->status);
    }

    public function fail(Request $request)
    {
        $payment_no = $request->input('LMI_PAYMENT_NO');
        if ($payment_no) {
            $transaction = Transaction::where('payment_id', $payment_no)
                                      ->where('status', '!=', 'completed') // Avoid overwriting a completed transaction
                                      ->first();
            
            if ($transaction && $transaction->status != 'failed') { // Only update if not already marked failed by 'result'
                $transaction->status = 'failed';
                $transaction->description = ($transaction->description ? $transaction->description . ' | ' : '') . 'Payment failed or cancelled by user at WebMoney.';
                $transaction->save();
                Log::info('WebMoney Fail: Transaction LMI_PAYMENT_NO: ' . $payment_no . ' marked as failed by fail URL.');
            } elseif($transaction && $transaction->status == 'failed') {
                Log::info('WebMoney Fail: Transaction LMI_PAYMENT_NO: ' . $payment_no . ' already marked as failed.');
            } else {
                Log::warning('WebMoney Fail: Transaction not found or already completed for LMI_PAYMENT_NO: ' . $payment_no);
            }
        }
        return redirect()->route('user.transaction.index')->with('error', 'Платеж не удался или был отменен. Если вы считаете, что это ошибка, пожалуйста, свяжитесь со службой поддержки.');
    }

    public function result(Request $request)
    {
        Log::info('WebMoney Result: Received notification.', $request->all());

        if ($request->has('LMI_PREREQUEST') && $request->input('LMI_PREREQUEST') == '1') {
            $payment_no = $request->input('LMI_PAYMENT_NO');
            $payee_purse = $request->input('LMI_PAYEE_PURSE');
            $payment_amount = $request->input('LMI_PAYMENT_AMOUNT');

            if ($payee_purse != config('services.webmoney.merchant_purse')) {
                Log::error('WebMoney Prerequest: Incorrect payee purse. Expected: ' . config('services.webmoney.merchant_purse') . ', Got: ' . $payee_purse . ' for LMI_PAYMENT_NO: ' . $payment_no);
                exit('ERR: INCORRECT_PAYEE_PURSE');
            }

            $transaction = Transaction::where('payment_id', $payment_no)->first();

            if (!$transaction) {
                 Log::error('WebMoney Prerequest: Transaction LMI_PAYMENT_NO: ' . $payment_no . ' not found. It should have been created in pay().');
                 exit('ERR: TRANSACTION_NOT_FOUND');
            }
            
            if ($transaction->status != 'pending') {
                Log::warning('WebMoney Prerequest: Transaction LMI_PAYMENT_NO: ' . $payment_no . ' already exists and status is not pending (Status: ' . $transaction->status . ').');
                exit('ERR: TRANSACTION_NOT_PENDING');
            }
            if ((float)$transaction->amount != (float)$payment_amount) {
                Log::error('WebMoney Prerequest: Amount mismatch for LMI_PAYMENT_NO: ' . $payment_no . '. Expected RUB: ' . $transaction->amount . ', Got in prerequest: ' . $payment_amount);
                exit('ERR: AMOUNT_MISMATCH');
            }

            Log::info('WebMoney Prerequest: Valid for LMI_PAYMENT_NO: ' . $payment_no);
            echo 'YES';
            exit;
        }

        $secretKey = config('services.webmoney.secret_key');
        if (empty($secretKey)) {
            Log::critical('WebMoney Result: Secret key is not configured. Cannot verify LMI_HASH.');
            exit('ERR: SECRET_KEY_NOT_CONFIGURED'); 
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

        if ($generatedHash != $request->input('LMI_HASH')) {
            Log::error('WebMoney Result: LMI_HASH mismatch for LMI_PAYMENT_NO: ' . $request->input('LMI_PAYMENT_NO') . '. Generated: ' . $generatedHash . ', Received: ' . $request->input('LMI_HASH'));
            exit('ERR: HASH_MISMATCH');
        }

        $payment_no = $request->input('LMI_PAYMENT_NO');
        $transaction = Transaction::where('payment_id', $payment_no)->where('status', 'pending')->first();

        if (!$transaction) {
            Log::warning('WebMoney Result: Transaction not found or not pending for LMI_PAYMENT_NO: ' . $payment_no . '. It might have been already processed or is an unknown transaction.');
            // If already processed (e.g. completed), WebMoney might send notifications multiple times. 
            // Check if it exists with a completed status.
            $existingTransaction = Transaction::where('payment_id', $payment_no)->first();
            if ($existingTransaction && $existingTransaction->status == 'completed') {
                Log::info('WebMoney Result: Received duplicate notification for already completed LMI_PAYMENT_NO: ' . $payment_no);
                exit; // Or 'YES' if WebMoney expects it for duplicates
            }
            exit('ERR: TRANSACTION_NOT_FOUND_OR_NOT_PENDING');
        }

        $user = User::find($transaction->user_id);
        if (!$user) {
            Log::error('WebMoney Result: User not found for transaction LMI_PAYMENT_NO: ' . $payment_no . '. User ID: ' . $transaction->user_id);
            // Mark transaction as failed if user is missing, as balance cannot be updated.
            $transaction->status = 'failed';
            $transaction->description = ($transaction->description ? $transaction->description . ' | ' : '') . 'Payment failed: User account not found.';
            $transaction->save();
            exit('ERR: USER_NOT_FOUND');
        }

        $settings = GeneralSetting::first();
        $usdToRubRate = $settings ? $settings->webmoney_usd_to_rub_rate : null;

        if (!$usdToRubRate || $usdToRubRate <= 0) {
            Log::error('WebMoney Result: USD to RUB exchange rate is not set or invalid for LMI_PAYMENT_NO: ' . $payment_no . '. Transaction ID: ' . $transaction->id);
            $transaction->status = 'failed';
            $failure_reason = 'Платеж не прошел: Неверная или отсутствующая конфигурация обменного курса. Пожалуйста, обратитесь в службу поддержки.';
            $transaction->description = $transaction->description ? $transaction->description . ' | ' . $failure_reason : $failure_reason;
            $transaction->save();
            exit('ERR: WM_EXCHANGE_RATE_INVALID');
        }

        $webmoneyPaymentAmountUSD = (float)$request->input('LMI_PAYMENT_AMOUNT');
        // It's crucial to confirm that LMI_PAYMENT_AMOUNT in the *notification* is indeed USD.
        // WebMoney might have a field like LMI_CURRENCY or similar in the notification.
        // Assuming LMI_PAYMENT_AMOUNT is USD as per the problem description.

        $creditedRubAmount = round($webmoneyPaymentAmountUSD * $usdToRubRate, 2); // Round to 2 decimal places for currency

        $user->balance += $creditedRubAmount;
        $user->save();

        $transaction->status = 'completed';
        $transaction->payment_system_trans_id = $request->input('LMI_SYS_TRANS_NO');
        $transaction->paid_at = now();
        
        $transaction->original_payment_amount = $webmoneyPaymentAmountUSD;
        $transaction->original_payment_currency = 'USD'; // Assuming WebMoney sent USD
        $transaction->amount = $creditedRubAmount; // The main 'amount' is now the credited RUB value
        // $transaction->currency remains 'RUB'

        $transaction->save();

        Log::info('WebMoney Result: Payment successful. LMI_PAYMENT_NO: ' . $payment_no . '. User ID: ' . $user->id . '. Original: ' . $webmoneyPaymentAmountUSD . ' USD. Credited: ' . $creditedRubAmount . ' RUB. Rate: ' . $usdToRubRate);
        // WebMoney usually expects an exit or no output on success. Some systems expect 'YES'. Check WM docs.
        exit; // Or echo 'YES'; if required by WebMoney for successful processing acknowledgement.
    }
}