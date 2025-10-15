<?php

namespace Application\Payment\Requests;

use Illuminate\Validation\Rule;
use Domain\Payment\Enums\Gateway;
use Illuminate\Foundation\Http\FormRequest;

class CreatePaymentRequest extends FormRequest
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
            'amount' => ['required', 'numeric', 'min:1'],
            'gateway' => ['required', 'string', Rule::enum(Gateway::class)],
        ];
    }
}
