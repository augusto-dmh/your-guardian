<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class TransactionDeleteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->can('delete', $this->route('transaction'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
                //
            ];
    }
}
