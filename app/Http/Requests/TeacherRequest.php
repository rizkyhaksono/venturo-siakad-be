<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRequest extends FormRequest
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
      'user_id' => ['required', 'uuid', 'exists:m_users,id'],
      'name' => ['required', 'string', 'max:255'],
      'employee_number' => ['required', 'string', 'max:255', 'unique:m_teachers,employee_number'],
      'subject' => ['required', 'string', 'max:255'],
    ];
  }
}
