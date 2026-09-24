<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->route('user');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user?->id)],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => [Rule::in(array_keys(\App\Models\User::ROLES))],
            'nip' => ['nullable', 'string', 'max:30'],
            'mata_pelajaran' => ['nullable', 'string', 'max:255', 'required_if:roles.*,guru'],
            'password' => $user
                ? ['nullable', 'confirmed']
                : ['required', 'confirmed', Password::defaults()],
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'mata_pelajaran.required_if' => 'Mata pelajaran wajib diisi untuk role Guru.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'roles.required' => 'Pilih minimal satu peran (role).',
            'roles.min' => 'Pilih minimal satu peran (role).',
        ];
    }
}