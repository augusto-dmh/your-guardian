<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class TransactionUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'exists:users,id',
            'bill_id' => 'exists:bills,id',
            'transaction_category_id' =>
                'nullable|exists:transaction_categories,id',
            'amount' => 'numeric',
            'type' => 'string|in:income,expense',
            'description' => 'string|max:65535',
        ];
    }
}
