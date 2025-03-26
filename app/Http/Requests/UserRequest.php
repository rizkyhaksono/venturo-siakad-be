<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use ProtoneMedia\LaravelMixins\Request\ConvertsBase64ToFiles;

class UserRequest extends FormRequest
{
    use ConvertsBase64ToFiles; // Library untuk convert base64 menjadi File

    public $validator;

    /**
     * Setting custom attribute pesan error yang ditampilkan
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'password' => 'Kolom Password',
        ];
    }

    /**
     * Tampilkan pesan error ketika validasi gagal
     *
     * @return void
     */
    public function failedValidation(Validator $validator)
    {
        $this->validator = $validator;
    }

    public function rules(): array
    {
        return $this->isMethod('post') ? $this->createRules() : $this->updateRules();
    }

    /**
     * Validation rules for creating a new user.
     */
    private function createRules(): array
    {
        return [
            'username' => ['required', 'string', 'max:255', 'unique:m_users,username'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'email' => ['required', 'email', 'max:255', 'unique:m_users,email'],
            'role' => ['required', 'string', 'exists:m_user_roles,id'],
            'photo' => ['nullable', 'file', 'image', 'max:2048'], // 2MB max
            'phone_number' => ['nullable', 'numeric', 'digits_between:8,15'],
        ];
    }

    /**
     * Validation rules for updating an existing user.
     */
    private function updateRules(): array
    {
        $userId = $this->route('user')?->id ?? $this->user;

        return [
            'username' => ['sometimes', 'string', 'max:255', "unique:m_users,username,{$userId}"],
            'password' => ['sometimes', 'string', 'min:6', 'confirmed'],
            'email' => ['sometimes', 'email', 'max:255', "unique:m_users,email,{$userId}"],
            'role' => ['sometimes', 'string', 'exists:m_user_roles,id'],
            'photo' => ['nullable', 'file', 'image', 'max:2048'], // 2MB max
            'phone_number' => ['nullable', 'numeric', 'digits_between:8,15'],
        ];
    }

    /**
     * inisialisasi key "photo" dengan value base64 sebagai "FILE"
     */
    protected function base64FileKeys(): array
    {
        return [
            'photo' => 'foto-user.jpg',
        ];
    }
}
