<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BankTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'transaction_date' => $this->transaction_date,
            'bank_from' => $this->bank_from,
            'bank_to' => $this->bank_to,
            'sender' => $this->sender,
            'receiver' => $this->receiver,
            'transaction_method' => $this->transaction_method,
            'transaction_amount' => $this->transaction_amount,
            'mode' => $this->mode,
            'remarks' => $this->remarks,
            'sender_bank_branch' => $this->sender_bank_branch,
            'receiver_bank_branch' => $this->receiver_bank_branch,
            'sender_contact' => $this->sender_contact,
            'receiver_contact' => $this->receiver_contact,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
