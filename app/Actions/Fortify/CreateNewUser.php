<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
//            'phone' => 'required|regex:/(0)[0-9]{1}[0-1]{1}[0-9]{8}/',
            'phone' => 'required',
            'password' => $this->passwordRules(),
        ])->validate();

        return User::create([
            'firstname' => $input['firstname'],
            'lastname' => $input['lastname'],
            'email' => $input['email'],
            'phone' => $input['phone'],
            'plan' => session('plan'),
            'password' => Hash::make($input['password']),
        ]);
    }
}
