<?php

namespace Application\Order\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Auth\Access\Gate;
use Domain\Payment\Enums\GatewayEnum;
use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
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
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
            'totalAmount' => ['required', 'numeric', 'min:0'],
            'shippingAddress' => ['nullable', 'string', 'max:255'],
            'gateway' => ['required', 'string', Rule::in([GatewayEnum::STRIPE, GatewayEnum::COD])]
        ];
    }
}
