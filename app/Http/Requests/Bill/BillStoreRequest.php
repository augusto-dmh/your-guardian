<?php

namespace App\Http\Requests\Bill;

use Illuminate\Foundation\Http\FormRequest;

class BillStoreRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:65535',
            'amount' => 'numeric',
            'status' => 'in:pending,paid,overdue',
            'due_date' => 'required|date',
        ];
    }
}
