<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class BankTrasnsactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'transaction_date' => 'required|date',
            'bank_from' => 'required|string|max:255',
            'bank_to' => 'required|string|max:255',
            'sender' => 'required|string|max:255',
            'receiver' => 'required|string|max:255',
            'transaction_method' => 'required|string|max:255',
            'transaction_amount' => 'required|numeric|min:0',
            'remarks' => 'nullable|string|max:500',
            'sender_bank_branch' => 'required|string|max:255',
            'receiver_bank_branch' => 'required|string|max:255',
            'sender_contact' => 'required|string|max:15', // Adjust max length as needed
            'receiver_contact' => 'required|string|max:15', // Adjust max length as needed
            'created_by' => 'required|exists:users,id', // Assuming created_by references users table
        ];
    }

    public function attributes()
    {
        return [
            'transaction_date' => 'transaction date',
            'bank_from' => 'bank from',
            'bank_to' => 'bank to',
            'sender' => 'sender',
            'receiver' => 'receiver',
            'transaction_method' => 'transaction method',
            'transaction_amount' => 'transaction amount',
            'remarks' => 'remarks',
            'sender_bank_branch' => 'sender bank branch',
            'receiver_bank_branch' => 'receiver bank branch',
            'sender_contact' => 'sender contact',
            'receiver_contact' => 'receiver contact',
            'created_by' => 'created by',
        ];
    }
}
