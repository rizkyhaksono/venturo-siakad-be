<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SPPRequest extends FormRequest
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
      'name' => ['required', 'string', 'max:255'],
      'jenis_biaya' => ['required', 'string', 'in:Reguler,Mandiri'],
      'study_year_id' => ['required', 'uuid', 'exists:m_study_year,id'],
      'total' => ['required', 'numeric'],
    ];
  }

  /**
   * Get custom attributes for validator errors.
   */
  public function messages(): array
  {
    return [
      'name.required' => 'The name is required.',
      'name.string' => 'The name must be a string.',
      'name.max' => 'The name may not be greater than 255 characters.',
      'jenis_biaya.required' => 'The jenis biaya is required.',
      'jenis_biaya.string' => 'The jenis biaya must be a string.',
      'jenis_biaya.in' => 'The selected jenis biaya is invalid.',
      'study_year_id.required' => 'The study year ID is required.',
      'study_year_id.uuid' => 'The study year ID must be a valid UUID.',
      'study_year_id.exists' => 'The selected study year ID is invalid.',
      'total.required' => 'The total is required.',
      'total.numeric' => 'The total must be a number.',
    ];
  }
}
