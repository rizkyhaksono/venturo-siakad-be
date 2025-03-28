<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubjectSchedulesRequest extends FormRequest
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
      'class_id' => ['required', 'uuid', 'exists:m_classes,id'],
      'subject_id' => ['required', 'uuid', 'exists:m_subjects,id'],
      'teacher_id' => ['required', 'uuid', 'exists:m_teachers,id'],
      'subject_hour_id' => ['required', 'uuid', 'exists:m_subject_hours,id'],
      'day' => ['required', 'string', 'in:Monday,Tuesday,Wednesday,Thursday,Friday'],
    ];
  }
}
