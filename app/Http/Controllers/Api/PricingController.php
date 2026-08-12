<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PlanPricing;
use App\Models\Reseller;
use App\Models\User;
use App\Models\VisitLog;
use App\Services\Odoo\OdooPayloadFactory;
use App\Services\Odoo\OdooSignalDispatcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PricingController extends Controller
{
    function getPlans($currency)
    {
        $price = PlanPricing::where("currency", $currency)->with('plan')->get();
        return response()->json(['success' => true, 'message' => 'Pricing fetched successfully', 'data' => $price]);
    }

    //Registration
    public function register(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|numeric|min:9',
            'password' => 'required|string|min:8',
            'reseller' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Check your inputs and try again', 'errors' => $validator->errors()]);
        }

        $reseller = Reseller::find($input['reseller']);

        if (!$reseller) {
            return response()->json(['success' => false, 'message' => 'Kindly provide a valid ID']);
        }

        if (isset($input['email'])) {
            $em = User::where("email", $input['email'])->first();

            if ($em) {
                return response()->json(['success' => false, 'message' => 'Email already exist']);
            }
        }

        if (isset($input['phone'])) {
            $ph = User::where("phone", $input['phone'])->first();

            if ($ph) {
                return response()->json(['success' => false, 'message' => 'Phone number already exist']);
            }
        }

        User::create([
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'password'  => Hash::make($request->password),
            'referral_code' => trim(substr(date('iym') . rand(), 0, 6)),
            'plan'         => 1,
            'reseller_id'  => $request->reseller,
        ]);

        // --- Odoo API-026: USER_REGISTERED (reseller) ---
        try {
            $u2 = User::where('email', $request->email)->first();
            if ($u2) {
                $factory    = app(OdooPayloadFactory::class);
                $dispatcher = app(OdooSignalDispatcher::class);
                $fullName   = trim(($u2->firstname ?? '') . ' ' . ($u2->lastname ?? ''));
                $payload    = $factory->userRegistered(
                    $u2->id, $fullName, $u2->email ?? '',
                    null, $u2->referral ?? null, 'reseller', $request->ip()
                );
                $dispatcher->dispatch('USER_REGISTERED', 'user_registered',
                    'USER_REGISTERED:' . $u2->id, $payload);
            }
        } catch (\Exception $e) {
            Log::error('Odoo USER_REGISTERED dispatch failed in PricingController::register', [
                'error' => substr($e->getMessage(), 0, 300),
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Your Registration is Successful']);
    }

    public function business(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|numeric|min:9',
            'password' => 'required|string|min:8',
            'reseller' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Check your inputs and try again', 'errors' => $validator->errors()]);
        }

        $reseller = Reseller::find($input['reseller']);

        if (!$reseller) {
            return response()->json(['success' => false, 'message' => 'Kindly provide a valid ID']);
        }

        if (isset($input['email'])) {
            $em = User::where("email", $input['email'])->first();

            if ($em) {
                return response()->json(['success' => false, 'message' => 'Email already exist']);
            }
        }

        if (isset($input['phone'])) {
            $ph = User::where("phone", $input['phone'])->first();

            if ($ph) {
                return response()->json(['success' => false, 'message' => 'Phone number already exist']);
            }
        }

        User::create([
            'firstname'    => $request->name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'password'     => Hash::make($request->password),
            'referral_code'=> trim(substr(date('iym') . rand(), 0, 6)),
            'plan'         => 1,
            'reseller_id'  => $request->reseller,
        ]);

        // --- Odoo API-026: USER_REGISTERED (reseller/business) ---
        try {
            $u2 = User::where('email', $request->email)->first();
            if ($u2) {
                $factory    = app(OdooPayloadFactory::class);
                $dispatcher = app(OdooSignalDispatcher::class);
                $payload    = $factory->userRegistered(
                    $u2->id, $u2->firstname ?? '', $u2->email ?? '',
                    null, $u2->referral ?? null, 'reseller', $request->ip()
                );
                $dispatcher->dispatch('USER_REGISTERED', 'user_registered',
                    'USER_REGISTERED:' . $u2->id, $payload);
            }
        } catch (\Exception $e) {
            Log::error('Odoo USER_REGISTERED dispatch failed in PricingController::business', [
                'error' => substr($e->getMessage(), 0, 300),
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Your Registration is Successful']);
    }

    function getUsers($id)
    {
        $users = User::where("reseller_id", 1)->with('plan')->get();
        return response()->json(['success' => true, 'message' => 'Users fetched successfully', 'data' => $users]);
    }

    function getActivity($countrycode)
    {
        $data = VisitLog::where("countryCode", $countrycode)->paginate();
        return response()->json(['success' => true, 'message' => 'Activities fetched successfully', 'data' => $data]);
    }

}
