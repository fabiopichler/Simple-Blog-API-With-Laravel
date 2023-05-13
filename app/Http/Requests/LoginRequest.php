<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Dto\UserCredentialsDto;

class LoginRequest extends FormRequest
{
    private UserCredentialsDto $userCredentialsDto;

    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    public function data(): UserCredentialsDto
    {
        if (empty($this->userCredentialsDto)) {
            $validated = $this->validated();

            $this->userCredentialsDto = new UserCredentialsDto(
                $validated['username'],
                $validated['password']
            );
        }

        return $this->userCredentialsDto;
    }
}
