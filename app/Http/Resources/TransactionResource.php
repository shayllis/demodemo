<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->transaction_id,
            'amount' => $this->amount,
            'date' => $this->created_at->format('Y-m-d H:i:s'),
            'from' => [
                'id' => $this->fromUser->id,
                'name' => $this->fromUser->name
            ],
            'to' => [
                'id' => $this->user->id,
                'name' => $this->user->name
            ],
        ];
    }
}
