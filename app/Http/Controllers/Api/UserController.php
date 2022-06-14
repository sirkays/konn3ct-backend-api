<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function createUser(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|max:255',
            'name' => 'required|string|min:3',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Check your inputs and try again', 'error' => $validator->errors()]);
        }

        User::create([
            'firstname' => $input['name'],
            'email' => $input['email'],
            'phone' => 0,
            'plan' => 1,
            'referral_code' => trim(substr(date('iym') . rand(), 0, 6)),
            'password' => Hash::make($input['password']),
        ]);
        return response()->json(['success' => true, 'message' => 'Account created successfully']);
    }

    public function createToken(Request $request)
    {
        $input = $request->all();

        $rules = array(
            'email' => 'required|email',
            'password' => 'required'
        );

        $validator = Validator::make($input, $rules);

        if (!$validator->passes()) {
            return response()->json(['success' => false, 'message' => 'Required field(s) is missing', 'error' => $validator->errors()]);
        }

        if (Auth::attempt(['email' => $input['email'], 'password' => $input['password']])) {
            // Authentication passed...

            $token = $request->user()->createToken($input['email']);

            return response()->json(['success' => true, 'message' => 'Token created successfully', 'token' => $token->plainTextToken]);
        }

        return response()->json(['success' => false, 'message' => 'These credentials do not match our records!']);
    }

}
