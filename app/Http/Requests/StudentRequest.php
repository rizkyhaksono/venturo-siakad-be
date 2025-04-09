<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
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
      'student_number' => ['required', 'string', 'unique:m_student,student_number'],
      'birth_date' => ['nullable', 'date'],
      'address' => ['nullable', 'string', 'max:255'],
      'gender' => ['nullable', 'string', 'max:10'],
    ];

    if ($this->method() === 'PUT' || $this->method() === 'PATCH') {
      $rules['name'] = ['sometimes', 'string', 'max:255'];
      $rules['student_number'] = ['sometimes', 'string', 'unique:m_student,student_number,' . $this->route('student')];
      $rules['birth_date'] = ['sometimes', 'date'];
      $rules['address'] = ['sometimes', 'string', 'max:255'];
      $rules['gender'] = ['sometimes', 'string', 'max:10'];
    }

    return $rules;
  }
}
