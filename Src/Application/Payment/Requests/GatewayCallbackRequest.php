<?php

namespace Application\Payment\Requests;

use Illuminate\Validation\Rule;
use Domain\Payment\Enums\Gateway;
use Illuminate\Foundation\Http\FormRequest;

class GatewayCallbackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'gateway' => ['required', 'string', Rule::enum(Gateway::class)],
            'payload' => ['required', 'array'],
            'payload.session_id' => ['required', 'string'],
            'payload.payment_status' => ['required', 'string'],
        ];
    }
}
