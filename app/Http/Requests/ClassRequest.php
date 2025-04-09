<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ClassRequest extends FormRequest
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
    $rules = [
      'name' => ['required', 'string', 'max:255'],
      'study_year_id' => ['required', 'string', 'exists:m_study_year,id'],
    ];

    // Adjust rules based on whether it's a create or update request
    if ($this->method() === 'PUT' || $this->method() === 'PATCH') {
      $rules['name'] = ['sometimes', 'string', 'max:255'];
      $rules['study_year_id'] = ['sometimes', 'string', 'exists:m_study_year,id'];
    }

    return $rules;
  }

  /**
   * Get custom messages for validation errors.
   */
  public function messages(): array
  {
    return [
      'name.required' => 'The class name is required.',
      'name.string' => 'The class name must be a string.',
      'name.max' => 'The class name may not be greater than 255 characters.',
      'study_year_id.required' => 'The study year is required.',
      'study_year_id.exists' => 'The selected study year is invalid.',
    ];
  }

  /**
   * Handle a failed validation attempt.
   */
  protected function failedValidation(Validator $validator)
  {
    $this->validator = $validator;

    throw new HttpResponseException(response()->json([
      'status' => 'error',
      'message' => 'Validation errors occurred',
      'errors' => $validator->errors(),
    ], 422));
  }

  /**
   * Prepare the data for validation.
   */
  protected function prepareForValidation(): void
  {
    $this->merge([
      'created_by' => auth()->id(), // Automatically set created_by
      'updated_by' => auth()->id(), // Automatically set updated_by
    ]);
  }
}
