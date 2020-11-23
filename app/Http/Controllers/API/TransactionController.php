<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Product;
use Validator;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\TransactionResource;
use Illuminate\Support\Str;

class TransactionController extends BaseController
{
    /**
     * List my transaction
     * @param  Request $request request object
     * @return Collection
     */
    public function index(Request $request)
    {
        // Get current user
        $user = $request->user();

        // Prepare transaction filters
        $query = Transaction::query();
        $query->where('user_id', $user['id'])
            ->orWhere('from_user_id', $user['id'])
            ->orderBy('created_at','desc');

        return $this->sendResponse(TransactionResource::collection($query->paginate(20)), 'User transactions');
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $data = $request->all();
        $data['from_user_id'] = $user['id'];
        $data['profile_id'] = $user['profile_id'];

        // Basic validation rules
        $validator = Validator::make($data, [
            'user_id' => 'required|integer|different:from_user_id,exists:users,id',
            'amount' => 'required|regex:/^\d*(\.\d{1,2})?$/',
        ]);

        // Execute simple verifications before executing external request
        if ($validator->fails())
            return $this->sendError('Validation Error.', $validator->errors());

        try {
            // External validation
            $response = Http::post('https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6', [
                'email' =>  ['user', 'pass'],
                'amount' => $request->input('amount')
                ])
                ->json();

            if ($response['message'] != 'Autorizado')
                return $this->sendError('Operação não autorizada', ['Operação não autorizada pela operadora.'], 406);
        } catch(\Exception $e) {
            return $this->sendError('Serviço indisponível.', $e->getMessage(), 403);
        }

        try {
            $data['transaction_id'] = Str::uuid();

            $transaction = new Transaction;
            $transaction->fill($data)
                ->save();

            return $this->sendResponse(new TransactionResource($transaction), 'Transaction has been created successfully.');
        }
        catch (\Exception $e) {
            return $this->sendError('Invalid Data.', $e->getMessage(), 406);
        }
    }
}
