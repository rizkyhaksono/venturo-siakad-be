<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class KKMRequest extends FormRequest
{
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
      'subject_id' => ['required', 'uuid', 'exists:m_subject,id'],
      'min_score' => ['required', 'integer', 'min:0'],
      'description' => ['nullable', 'string'],
    ];
  }

  /**
   * Get custom messages for validation errors
   */
  public function messages(): array
  {
    return [
      'subject_id.required' => 'The subject ID is required.',
      'subject_id.string' => 'The subject ID must be a string.',
      'subject_id.exists' => 'The selected subject ID is invalid.',
      'min_score.required' => 'The minimum score is required.',
      'min_score.integer' => 'The minimum score must be an integer.',
      'min_score.min' => 'The minimum score must be at least 0.',
      'description.string' => 'The description must be a string.',
    ];
  }
}
