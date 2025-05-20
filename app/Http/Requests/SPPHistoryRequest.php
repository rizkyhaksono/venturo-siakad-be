<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SPPHistoryRequest extends FormRequest
{
  public $validator;

  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return auth()->check();
  }

  /**
   * Get the validation rules that apply to the request.
   */
  public function rules(): array
  {
    return [
      'user_id' => ['required', 'uuid', 'exists:m_users,id'],
      'spp_id' => ['required', 'uuid', 'exists:t_spp,id'],
      'amount_paid' => ['required', 'numeric'],
      'payment_date' => ['required', 'date'],
      'payment_status' => ['required', 'string', 'in:paid,unpaid'],
      'payment_method' => ['required', 'string'],
    ];
  }

  /**
   * Get custom attributes for validator errors.
   */
  public function messages(): array
  {
    return [
      'user_id.required' => 'The user ID is required.',
      'user_id.uuid' => 'The user ID must be a valid UUID.',
      'user_id.exists' => 'The selected user ID is invalid.',
      'spp_id.required' => 'The SPP ID is required.',
      'spp_id.uuid' => 'The SPP ID must be a valid UUID.',
      'spp_id.exists' => 'The selected SPP ID is invalid.',
      'amount_paid.required' => 'The amount paid is required.',
      'amount_paid.numeric' => 'The amount paid must be a number.',
      'payment_date.required' => 'The payment date is required.',
      'payment_date.date' => 'The payment date must be a valid date.',
      'payment_status.required' => 'The payment status is required.',
      'payment_status.string' => 'The payment status must be a string.',
      'payment_status.in' => 'The selected payment status is invalid.',
      'payment_method.required' => 'The payment method is required.',
      'payment_method.string' => 'The payment method must be a string.',
    ];
  }
}
