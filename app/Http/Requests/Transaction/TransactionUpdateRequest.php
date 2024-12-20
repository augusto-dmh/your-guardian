<?php

namespace App\Http\Requests\Transaction;

use App\Models\Transaction;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

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
            'bill_id' => 'nullable|exists:bills,id',
            'transaction_category_id' => [
                'nullable',
                Rule::exists('transaction_categories', 'id')->where(
                    'transaction_type',
                    $this->type
                ),
            ],
            'amount' => 'required|numeric',
            'type' => 'nullable|string|in:income,expense',
            'title' => 'required|nullable|string|max:255',
            'description' => 'nullable|nullable|string|max:65535',
        ];
    }
}
