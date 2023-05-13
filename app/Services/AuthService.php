<?php

/*-------------------------------------------------------------------------------

Copyright (c) 2023 Fábio Pichler

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

-------------------------------------------------------------------------------*/

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

use App\Models\User;
use App\Dto\AuthUserDto;
use App\Dto\UserCreationDto;
use App\Dto\UserCredentialsDto;

class AuthService
{
    public function login(UserCredentialsDto $credentialsDto): array
    {
        $username = $credentialsDto->username;

        $credentials = [
            'password' => $credentialsDto->password,
        ];

        if (filter_var($username, FILTER_VALIDATE_EMAIL))
            $credentials['email'] = $username;
        else
            $credentials['username'] = $username;

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function signup(UserCreationDto $userDto): AuthUserDto
    {
        $user = User::create([
            'name' => $userDto->name,
            'username' => $userDto->username,
            'email' => $userDto->email,
            'password' => Hash::make($userDto->password),
        ]);

        event(new Registered($user));

        return new AuthUserDto($user);
    }

    public function user(): AuthUserDto
    {
        return new AuthUserDto(auth()->user());
    }

    public function logout(): array
    {
        auth()->logout();

        return ['message' => 'Successfully logged out'];
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    private function respondWithToken($token): array
    {
        return [
            'accessToken' => $token,
            'refreshToken' => '',
            'user' => new AuthUserDto(auth()->user()),
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }
}
