<?php

namespace App\Http\Requests\Transaction;

use App\Models\Transaction;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class TransactionUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->route('transaction'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'nullable|exists:users,id',
            'bill_id' => 'nullable|exists:bills,id',
            'transaction_category_id' => [
                'required',
                Rule::exists('transaction_categories', 'id')->where(
                    'transaction_type',
                    $this->type
                ),
            ],
            'amount' => 'nullable|numeric',
            'type' => 'nullable|string|in:income,expense',
            'description' => 'nullable|string|max:65535',
            'title' => 'nullable|nullable|string|max:255',
            'description' => 'nullable|nullable|string|max:65535',
        ];
    }
}
