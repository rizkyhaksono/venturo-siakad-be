<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentAssesmentRequest extends FormRequest
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
      'student_id' => ['required', 'uuid', 'exists:m_student,id'],
      'subject_schedule_id' => ['required', 'uuid', 'exists:m_subject_schedule,id'],
      'uts_score' => ['nullable', 'numeric'],
      'uas_score' => ['nullable', 'numeric'],
      'tugas_score' => ['nullable', 'numeric'],
      'activity_score' => ['nullable', 'numeric'],
      'total_score' => ['nullable', 'numeric'],
      'notes' => ['nullable', 'string'],
      'study_year_id' => ['required', 'uuid', 'exists:m_study_year,id'],
    ];
  }

  /**
   * Get custom attributes for validator errors.
   */
  public function messages(): array
  {
    return [
      'student_id.required' => 'The student ID is required.',
      'student_id.uuid' => 'The student ID must be a valid UUID.',
      'student_id.exists' => 'The selected student ID is invalid.',
      'subject_schedule_id.required' => 'The subject schedule ID is required.',
      'subject_schedule_id.uuid' => 'The subject schedule ID must be a valid UUID.',
      'subject_schedule_id.exists' => 'The selected subject schedule ID is invalid.',
      'uts_score.numeric' => 'The UTS score must be a number.',
      'uas_score.numeric' => 'The UAS score must be a number.',
      'tugas_score.numeric' => 'The Tugas score must be a number.',
      'activity_score.numeric' => 'The Activity score must be a number.',
      'total_score.numeric' => 'The Total score must be a number.',
      'notes.string' => 'The notes must be a string.',
      'study_year_id.required' => 'The study year ID is required.',
      'study_year_id.uuid' => 'The study year ID must be a valid UUID.',
      'study_year_id.exists' => 'The selected study year ID is invalid.',
    ];
  }
}
