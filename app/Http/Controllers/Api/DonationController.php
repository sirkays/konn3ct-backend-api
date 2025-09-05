<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\DonationPayment;
use App\Models\RoomModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DonationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|in:0,1',
            'amount' => 'required',
            'id' => 'required',
            'enableFlashNotification' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Check your inputs and try again', 'error' => $validator->errors()]);
        }

        $i = RoomModel::find($input['id']);

        if (!$i) {
            return response()->json(['success' => false, 'message' => 'Invalid Room!']);
        }

        $donation=Donation::create([
            'room_id' => $i->id,
            'user_id' => $i->user_id,
            'name' => $input['name'],
            'type' => $input['type'],
            'amount' => $input['amount'],
            'enableFlashNotification' => $input['enableFlashNotification']
        ]);

        return response()->json(['success' => true, 'message' => 'Donation created successfully', 'data'=>$donation->id]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Donation  $donation
     * @return \Illuminate\Http\Response
     */
    public function show($room_id)
    {
        $donation=Donation::where([['room_id',$room_id], ['status',1]])->latest()->get()->makeHidden(['room_id','user_id', 'updated_at']);
        if(count($donation) > 0){
            $donated_amount=DonationPayment::where([['donation_id',$donation[0]['id']], ['status',1]])->sum('amount');
        }

        return response()->json(['success' => true, 'message' => 'Donation fetched successfully', 'data'=>$donation, 'donated_amount'=>$donated_amount ?? 0]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Donation  $donation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Donation $donation)
    {
        $donation->update([
            'status'=>$request->status
        ]);

        return response()->json(['success' => true, 'message' => 'Donation updated successfully', 'data'=>$donation]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Donation  $donation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Donation $donation)
    {
        //
    }


    /**
     * Make Payment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Donation  $donation
     * @return \Illuminate\Http\Response
     */
    public function pay(Request $request, Donation $donation)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:200',
            'email' => 'required|string|max:200',
            'amount' => 'required',
            'id' => 'required|string|max:200',
            'meetid' => 'required|string|max:200',
            'description' => 'nullable|string|max:200',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Check your inputs and try again', 'error' => $validator->errors()]);
        }

        if($donation->type == 1){
            $amount=$donation->amount;
        }else{
            $amount=$input['amount'];
        }

        $dp=DonationPayment::create([
            'donation_id' => $donation->id,
            'meeting_id' => $input['meetid'],
            'amount' => $amount,
            'payee_id' => $input['id'],
            'payee_email' => $input['email'],
            'payee_name' => $input['name'],
            'description' => $input['description'],
            'provider' => "vulte",
        ]);

        $payload='{
    "amount": '.$amount.',
    "walletId": "master",
    "currency": "'.$donation->currency.'",
    "metadata": {
        "pay_type": "donation",
        "payment_id": "'.$dp->id.'",
        "payee_id": "'.$input['id'].'",
        "payee_name": "'.$input['name'].'",
        "payee_email":"'.$input['email'].'"
    }
}';

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('VULTE_BASEURL').'/v1/checkout/initialize',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_POSTFIELDS =>$payload,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: '.env('VULTE_KEY')
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        Log::info("Donation Payment: Payload $payload; Response $response");

        $rep=json_decode($response, true);

        $dp->provider_response = $response;

        if($rep['success']){
            $dp->reference = $rep['data']['reference'];
            $dp->save();
            return response()->json(['success' => true, 'message' => 'Proceed to Payment', 'data'=>$rep['data']['authorization_url'], 'reference'=>$dp->reference]);
        }else{
            $dp->save();
            return response()->json(['success' => false, 'message' => 'Unable to make payment at this time']);
        }
    }


    /**
     * Make Payment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $ref
     * @return \Illuminate\Http\Response
     */
    public function paymentCheck(Request $request, $ref)
    {
        $dp=DonationPayment::where("reference",$ref)->first();

        if(!$dp){
            return response()->json(['success' => false, 'message' => 'Invalid Payment Reference']);
        }

        if($dp->status==1){
            return response()->json(['success' => true, 'message' => 'Payment Successful', 'data'=>$dp->status, 'amount'=>$dp->amount]);
        }else{
            return response()->json(['success' => true, 'message' => 'Payment Pending', 'data'=>$dp->status, 'amount'=>$dp->amount]);
        }
    }


}
