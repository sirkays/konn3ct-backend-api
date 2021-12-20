<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OtherController extends Controller
{
    public function addReferral(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'referralCode' => 'required|min:4|max:255'
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $u = User::where('referral', $input['referralCode'])->first();
        if (!$u) {
            return back()->with('error', 'Referral code does not exist. Kindly use a valid one.');
        }

        if ($u->reffal_code != null) {
            return back()->with('error', 'Invalid Code. Kindly use a valid one.');
        }

        $u->referral = $input['referralCode'];
        $u->save();

        return back()->with('success', 'Referral added successfully');

    }

}
