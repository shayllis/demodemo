<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class SendNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get not notified transactions
        $transactions = Transaction::query()
            ->select('transactions.id', 'users.email', 'transactions.status_id')
            ->join('users', 'users.id', '=', 'transactions.user_id')
            ->where('status_id', 1)
            ->orderBy('transactions.created_at')
            ->limit(30);

        $transactions = $transactions->get();//(array) TransactionResource::collection($transactions->paginate(20));

        $errors = false;
        foreach ($transactions as $transaction) {
            try {
                $response = Http::post('https://run.mocky.io/v3/b19f7b9f-9cbf-4fc6-ad22-dc30601aec04', [
                    'to' => $transaction['email'],
                    'subject' => 'Transaction received',
                    'message' => "You have just received a new transaction, please check your app."
                ])
                ->json();

                $transaction->status_id = 2;
                $transaction->save();
            }
            catch(\Exception $e) {
                $errors = true;
            }
        }

        if ($errors)
            Log::emergency('Mensagery is down');
    }
}
