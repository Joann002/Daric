<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreTransferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'from_account_id' => ['required', 'exists:accounts,id', 'different:to_account_id'],
            'to_account_id' => ['required', 'exists:accounts,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'from_account_id.required' => 'Le compte source est obligatoire',
            'from_account_id.different' => 'Les comptes source et destination doivent être différents',
            'to_account_id.required' => 'Le compte destination est obligatoire',
            'amount.required' => 'Le montant est obligatoire',
            'amount.min' => 'Le montant doit être supérieur à 0',
            'date.required' => 'La date est obligatoire',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $fromAccount = \App\Models\Account::find($this->from_account_id);
            $toAccount = \App\Models\Account::find($this->to_account_id);
            
            if ($fromAccount && $fromAccount->user_id !== auth()->id()) {
                $validator->errors()->add('from_account_id', 'Ce compte ne vous appartient pas');
            }
            
            if ($toAccount && $toAccount->user_id !== auth()->id()) {
                $validator->errors()->add('to_account_id', 'Ce compte ne vous appartient pas');
            }
        });
    }
}
