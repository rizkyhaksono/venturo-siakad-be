<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudyYearRequest extends FormRequest
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
      'year' => ['required', 'string', 'max:255'],
      'semester' => ['required', 'string', 'max:255'],
    ];
  }
}
