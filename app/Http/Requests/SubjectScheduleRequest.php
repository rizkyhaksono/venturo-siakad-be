<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubjectScheduleRequest extends FormRequest
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
      'class_id' => ['required', 'uuid', 'exists:m_class,id'],
      'subject_id' => ['required', 'uuid', 'exists:m_subject,id'],
      'teacher_id' => ['required', 'uuid', 'exists:m_teacher,id'],
      'subject_hour_id' => ['required', 'uuid', 'exists:m_subject_hour,id'],
      'day' => ['required', 'string', 'in:Monday,Tuesday,Wednesday,Thursday,Friday'],
      'rombel_id' => ['nullable', 'uuid', 'exists:m_rombel,id'],
    ];
  }
}
