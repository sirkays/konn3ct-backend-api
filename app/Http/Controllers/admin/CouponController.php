<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CouponCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    public function fetch()
    {
        $datas['coupons'] = CouponCode::latest()->paginate(25);
        $datas['i'] = 1;
        return view('admin.coupon_codes', $datas);
    }

    public function disable($id)
    {
        $cc = CouponCode::find($id);
        if (!$cc) {
            return back()->with('error', 'Coupon-code not found');
        }

        if ($cc->status == 0) {
            return back()->with('error', 'Coupon-code has been disabled earlier');
        }


        $cc->status = 0;
        $cc->save();

        return back()->with('success', 'Coupon-code has been disabled successfully.');
    }

    public function enable($id)
    {
        $cc = CouponCode::find($id);
        if (!$cc) {
            return back()->with('error', 'Coupon-code not found');
        }

        if ($cc->status == 1) {
            return back()->with('error', 'Coupon-code has been enabled earlier');
        }

        $cc->status = 1;
        $cc->save();

        return back()->with('success', 'Coupon-code has been disabled successfully.');
    }

    public function create(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'code' => 'nullable|unique:coupon_codes',
            'type' => 'required',
            'discount' => 'required|integer',
            'reoccuring' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        if (!isset($input['code'])) {
            $input['code'] = uniqid("K-");
        }

        CouponCode::create($input);

        return back()->with('success', 'Coupon-code has been created successfully and is ready for use.');

    }

    public function apply(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'code' => 'required'
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $cc = CouponCode::where('code', $input['code'])->first();
        if (!$cc) {
            return back()->with('error', 'Coupon-code is invalid');
        }

        if ($cc->status != 1) {
            return back()->with('error', 'Coupon-code has expired');
        }

        if ($cc->reoccuring == 0 && $cc->used_by != null) {
            return back()->with('error', 'Coupon-code has been used.');
        }

        session(['discount' => $cc->discount, 'discount_type' => $cc->type, 'discount_id' => $cc->id]);

        return back()->with('success', 'Coupon-code has been applied successfully.');

    }


    public function markCouponCode()
    {
        $did = session('discount_id', 0);

        if ($did != 0) {
            $n = Auth::user()->email . "; ";

            $cc = CouponCode::find($did);
            $cc->used_by .= $n;
            $cc->save();
        }
    }


}
