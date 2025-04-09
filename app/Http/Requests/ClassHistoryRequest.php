<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassHistoryRequest extends FormRequest
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
      'student_id' => ['required', 'string', 'max:255'],
      'class_id' => ['required', 'string', 'max:255'],
      'study_year_id' => ['required', 'string', 'exists:m_study_year,id'],
      'previous_status' => ['required', 'string', 'max:255', 'in:entered,promoted,transferred'],
      'new_status' => ['required', 'string', 'max:255', 'in:entered,promoted,transferred'],
      'entry_date' => ['required', 'date'],
    ];

    if ($this->method() === 'PUT' || $this->method() === 'PATCH') {
      foreach ($rules as $field => $validations) {
        $rules[$field] = array_merge(['sometimes'], $validations);
      }
    }

    return $rules;
  }

  /**
   * Get custom messages for validation errors.
   */
  public function messages(): array
  {
    return [
      'student_id.required' => 'The student is required.',
      'student_id.string' => 'The student must be a string.',
      'student_id.max' => 'The student may not be greater than 255 characters.',
      'class_id.required' => 'The class is required.',
      'class_id.string' => 'The class must be a string.',
      'class_id.max' => 'The class may not be greater than 255 characters.',
      'study_year_id.required' => 'The study year is required.',
      'study_year_id.exists' => 'The selected study year is invalid.',
      'previous_status.required' => 'The previous status is required.',
      'previous_status.string' => 'The previous status must be a string.',
      'previous_status.max' => 'The previous status may not be greater than 255 characters.',
      'new_status.required' => 'The new status is required.',
      'new_status.string' => 'The new status must be a string.',
      'new_status.max' => 'The new status may not be greater than 255 characters.',
      'entry_date.required' => 'The entry date is required.',
      'entry_date.date' => 'The entry date must be a date.',
    ];
  }

  public function prepareForValidation()
  {
    if ($this->has('entry_date')) {
      $this->merge([
        'entry_date' => date('Y-m-d', strtotime($this->entry_date))
      ]);
    }
  }
}
