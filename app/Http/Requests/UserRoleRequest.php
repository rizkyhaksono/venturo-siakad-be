<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UserRoleRequest extends FormRequest
{
  public $validator;

  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    // Adjust authorization logic based on your needs
    return auth()->check(); // Example: only authenticated users
  }

  /**
   * Get the validation rules that apply to the request.
   */
  public function rules(): array
  {
    $rules = [
      'name' => ['required', 'string', 'max:255'],
      'access' => ['required', 'string', 'max:255'],
    ];

    // Adjust rules based on whether it's a create or update request
    if ($this->method() === 'PUT' || $this->method() === 'PATCH') {
      $rules['name'] = ['sometimes', 'string', 'max:255'];
      $rules['access'] = ['sometimes', 'string', 'max:255'];
    }

    return $rules;
  }
}
