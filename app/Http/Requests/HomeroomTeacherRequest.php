<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HomeroomTeacherRequest extends FormRequest
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
      'teacher_id' => ['required', 'string', 'exists:m_teacher,id'],
      'class_id' => ['required', 'string', 'exists:m_class,id'],
      'study_year_id' => ['required', 'string', 'exists:m_study_year,id'],
      'semester' => ['required', 'string', 'in:1,2'],
    ];

    if ($this->method() === 'PUT' || $this->method() === 'PATCH') {
      $rules['teacher_id'] = ['sometimes', 'string', 'exists:m_teacher,id'];
      $rules['class_id'] = ['sometimes', 'string', 'exists:m_class,id'];
      $rules['study_year_id'] = ['sometimes', 'string', 'exists:m_study_year,id'];
      $rules['semester'] = ['sometimes', 'string', 'in:1,2'];
    }

    return $rules;
  }
}
