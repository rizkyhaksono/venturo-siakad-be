<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubjectRequest extends FormRequest
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
      'study_year_id' => ['required', 'uuid', 'exists:m_study_years,id'],
      'class_id' => ['required', 'uuid', 'exists:m_classes,id'],
      'teacher_id' => ['required', 'uuid', 'exists:m_teachers,id'],
    ];
  }
}
