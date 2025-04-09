<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubjectHourRequest extends FormRequest
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
      'start_hour' => ['required', 'integer', 'min:1'],
      'start_time' => ['required', 'date_format:H:i'],
      'end_time' => [
        'required',
        'date_format:H:i',
        'after:start_time'
      ],
    ];
  }

  /**
   * Get custom messages for validator errors.
   */
  public function messages(): array
  {
    return [
      'start_hour.required' => 'Start hour is required',
      'start_hour.integer' => 'Start hour must be an integer',
      'start_hour.min' => 'Start hour must be at least 1',
      'start_time.required' => 'Start time is required',
      'start_time.date_format' => 'Start time must be in HH:mm format',
      'end_time.required' => 'End time is required',
      'end_time.date_format' => 'End time must be in HH:mm format',
      'end_time.after' => 'End time must be after start time'
    ];
  }
}
