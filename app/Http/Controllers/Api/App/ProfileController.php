<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Models\AddonModel;
use App\Models\PaymentModel;
use App\Models\PlanModel;
use App\Models\PlanPricing;
use App\Models\RoomModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function show()
    {
        $datas['rooms_count'] = RoomModel::where('user_id', Auth::id())->count();
        $datas['payments_count'] = PaymentModel::where('user_id', Auth::id())->count();
        $datas['user_plan'] = PlanModel::find(Auth::user()->plan);
        $datas['user'] = Auth::user();

        return response()->json(['success' => true, 'message' => 'Fetched successfully', 'data' => $datas]);
    }

    public function updateProfile(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'firstname' => 'nullable|max:255',
            'lastname' => 'nullable|max:255',
            'phone' => 'nullable|min:9',
            'image' => 'nullable|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode(",", $validator->errors()->all()), 'error' => $validator->errors()->all()]);
        }

        $user=User::find(Auth::id());

        if ($request->hasFile('image')) {
            $upload = Storage::put('chat_images', $request->image);
            $imglink = Storage::url($upload);
            $user->profile_photo_path=$imglink;
        }

        if ($request->has('firstname')) {
            $user->firstname=$request->firstname;
        }

        if ($request->has('lastname')) {
            $user->lastname=$request->lastname;
        }

        if ($request->has('phone')) {
            $user->phone=$request->phone;
        }

        $user->save();

        return response()->json(['success' => true, 'message' => 'Updated successfully', 'data' => $user]);
    }

    public function updatePassword(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'current_password' => 'nullable|max:255',
            'new_password' => 'nullable|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode(",", $validator->errors()->all()), 'error' => $validator->errors()->all()]);
        }

        $user=User::find(Auth::id());

        if (!Hash::check($input['current_password'], $user->password)) {
            return response()->json(['status' => false, 'message' => 'Current Password is not correct']);
        }

        $user->password=Hash::make($input['new_password']);

        $user->save();

        return response()->json(['success' => true, 'message' => 'Password Updated successfully']);
    }

    public function viewSessions(Request $request)
    {
        $user=User::where('id',Auth::id())->first();
        // Get only id and name of associated tokens as an array of arrays
        $tokenData = $user->tokens->map(function ($token) {
            return [
                'id' => $token->id,
                'name' => $token->name,
                'last_used_at'=>$token->last_used_at
            ];
        })->toArray();

        // Get the bearer token from the request headers
        $bearerToken = $request->bearerToken();

        $bt=str_contains($bearerToken,"|") ?explode("|",$bearerToken)[1] :  $bearerToken;


        // Find the matching token in the user's associated tokens
        $currentToken = $user->tokens->where('token', hash('sha256', $bt))->first();

        $ct=[
            'id' => $currentToken->id,
            'name' => $currentToken->name,
            'last_used_at'=>$currentToken->last_used_at
        ];

        return response()->json(['success' => true, 'message' => 'Fetched successfully', 'data'=>$tokenData, 'currentToken'=>$ct]);
    }

    public function deleteOthersUserSession(Request $request)
    {

        $user=User::where('id',Auth::id())->first();

        // Get the bearer token from the request headers
        $bearerToken = $request->bearerToken();

        $bt=str_contains($bearerToken,"|") ?explode("|",$bearerToken)[1] :  $bearerToken;

        // Find the matching token in the user's associated tokens
        $currentToken = $user->tokens->where('token', hash('sha256', $bt))->first();


        // Revoke all other tokens for the user
        $user->tokens()->where('id', '!=', $currentToken->id)->delete();

        return response()->json(['success' => true, 'message' => 'Other sessions revoked successfully']);
    }

    public function payments(Request $request)
    {
        $datas['payments'] = PaymentModel::where('user_id', Auth::id())->with('planDetails')->OrderBy('id', 'desc')->simplePaginate(10);
        $datas['sumed_payments'] = PaymentModel::where('user_id', Auth::id())->sum('amount');
        $datas['count_payments'] = PaymentModel::where('user_id', Auth::id())->count();

        return response()->json(['success' => true, 'message' => 'Fetched successfully', 'data' => $datas]);
    }

    public function plans(Request $request)
    {
        $input = $request->all();
        $vs = $input['vs'];

        if ($vs['countryCode'] == null) {
            $country = "NG";
        } else {
            $country = $vs['countryCode'];
        }

        if ($country == "IN") {
            $lite_monthly1 = PlanPricing::where([["type", "monthly"], ["currency", "INR"], ["plan_id", 2]])->first();

            $lite_yearly1 = PlanPricing::where([["type", "yearly"], ["currency", "INR"], ["plan_id", 2]])->first();

            $pro_monthly1 = PlanPricing::where([["type", "monthly"], ["currency", "INR"], ["plan_id", 3]])->first();

            $pro_yearly1 = PlanPricing::where([["type", "yearly"], ["currency", "INR"], ["plan_id", 3]])->first();


            $datas['lite_monthly'] = "INR $lite_monthly1->price";
            $datas['lite_yearly'] = "INR $lite_yearly1->price";
            $datas['pro_monthly'] = "INR $pro_monthly1->price";
            $datas['pro_yearly'] = "INR $pro_yearly1->price";

        } else {

            $lite_monthly1 = PlanPricing::where([["type", "monthly"], ["currency", "NGN"], ["plan_id", 2]])->first();
            $lite_monthly2 = PlanPricing::where([["type", "monthly"], ["currency", "USD"], ["plan_id", 2]])->first();

            $lite_yearly1 = PlanPricing::where([["type", "yearly"], ["currency", "NGN"], ["plan_id", 2]])->first();
            $lite_yearly2 = PlanPricing::where([["type", "yearly"], ["currency", "USD"], ["plan_id", 2]])->first();

            $pro_monthly1 = PlanPricing::where([["type", "monthly"], ["currency", "NGN"], ["plan_id", 3]])->first();
            $pro_monthly2 = PlanPricing::where([["type", "monthly"], ["currency", "USD"], ["plan_id", 3]])->first();

            $pro_yearly1 = PlanPricing::where([["type", "yearly"], ["currency", "NGN"], ["plan_id", 3]])->first();
            $pro_yearly2 = PlanPricing::where([["type", "yearly"], ["currency", "USD"], ["plan_id", 3]])->first();

            $datas['lite_monthly'] = "$$lite_monthly2->price / #$lite_monthly1->price";
            $datas['lite_yearly'] = "$$lite_yearly2->price / #$lite_yearly1->price";
            $datas['pro_monthly'] = "$$pro_monthly2->price / #$pro_monthly1->price";
            $datas['pro_yearly'] = "$$pro_yearly2->price / #$pro_yearly1->price";
        }

        $datas['plan']=PlanModel::get();

        return response()->json(['success' => true, 'message' => 'Fetched successfully', 'data' => $datas]);
    }

    public function addons(Request $request)
    {
        $data=AddonModel::get();
        $datas['whatsapp_invite']=\Illuminate\Support\Facades\Auth::user()->whatsapp_invite=="0" ? "Not yet activated" : (\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse(\Illuminate\Support\Facades\Auth::user()->whatsapp_invite), false) > 0 ? "Expires in " .\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse(Auth::user()->whatsapp_invite), false). " days" : "Expired");
        $datas['streaming_service']=\Illuminate\Support\Facades\Auth::user()->streaming_service=="0" ? "Not yet activated" : (\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse(\Illuminate\Support\Facades\Auth::user()->streaming_service), false) > 0 ? "Expires in " .\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse(Auth::user()->streaming_service), false). " days" : "Expired");

        return response()->json(['success' => true, 'message' => 'Fetched successfully', 'data' => $data, 'current_status' => $datas]);
    }

    public function referee()
    {
        if(Auth::user()->referral_code == NULL){
            return response()->json(['success' => true, 'message' => 'Fetched successfully', 'data' => []]);
        }

        $datas = User::where('referral', Auth::user()->referral_code)->select('firstname','lastname','email', 'plan','created_at')->with('plan')->simplePaginate(10);

        return response()->json(['success' => true, 'message' => 'Fetched successfully', 'data' => $datas]);
    }


}
