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
        if ($this->isMethod('post')) {
            return $this->createRules();
        }

        return $this->updateRules();
    }

    private function createRules(): array
    {
        return [
            'name' => 'required|max:100',
            'photo' => 'nullable|file|image|mimes:jpg,jpeg,png|max:1048',
            'email' => 'required|email|unique:m_user',
            'wali' => 'required|max:100',
            'pekerjaan' => 'required|max:100',
            'birth_date' => 'required|date',
            'address' => 'required|max:255',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'password' => 'required|min:6',
            'phone_number' => 'numeric',
            'm_user_roles_id' => 'nullable|string|exists:m_user_roles,id',
        ];
    }

    private function updateRules(): array
    {
        return [
            'name' => 'sometimes|required|max:100',
            'photo' => 'nullable|file|image|mimes:jpg,jpeg,png|max:2048',
            'email' => 'sometimes|required|email|unique:m_user,email,' . $this->route('user'),
            'wali' => 'sometimes|required|max:100',
            'pekerjaan' => 'sometimes|required|max:100',
            'birth_date' => 'sometimes|required|date',
            'address' => 'sometimes|required|max:255',
            'gender' => 'sometimes|required|in:Laki-laki,Perempuan',
            'password' => 'nullable|min:6',
            'phone_number' => 'nullable|numeric',
            'm_user_roles_id' => 'nullable|string|exists:m_user_roles,id',
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
