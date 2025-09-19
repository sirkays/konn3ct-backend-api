<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Models\DonationPayment;
use App\Models\Kyc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DonationController extends Controller
{
    function stats(Request $request)
    {
        $query=DonationPayment::join('donations','donation_payments.donation_id','donations.id')->where([['donations.user_id',Auth::id()], ['donation_payments.status',1]])->select('donation_payments.*','donations.id','donations.user_id','donations.id','donations.currency');
        $datas['donation_sum']=$query->sum('donation_payments.amount');
        $datas['donation_count']=$query->count();

        $datas['settlement_sum']=$query->whereNotNull('settled_at')->sum('donation_payments.amount');
        $datas['settlement_count']=$query->whereNotNull('settled_at')->count();

        $query2=DonationPayment::join('donations','donation_payments.donation_id','donations.id')->where([['donations.user_id',Auth::id()], ['donation_payments.status',1]])->select('donation_payments.*','donations.id','donations.user_id','donations.id','donations.currency');
        $datas['pending_sum']=$query2->whereNull('settled_at')->sum('donation_payments.amount');
        $datas['pending_count']=$query2->whereNull('settled_at')->count();

        return response()->json(['success' => true, 'message' => 'Fetched Donation Stats',  'data' =>$datas]);
    }

    function donationsIn(Request $request)
    {
        $query=DonationPayment::join('donations','donation_payments.donation_id','donations.id')->where('donations.user_id',Auth::id())->select('donation_payments.*','donations.id','donations.user_id','donations.id','donations.currency')->orderBy('donation_payments.id','desc')->paginate();

        return response()->json(['success' => true, 'message' => 'Donations Fetched', 'data' =>$query]);
    }

    function banks(Request $request)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.paystack.co/bank',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . env("PAYSTACK_SECRET_KEY"),
                "Cache-Control: no-cache",
            ),
        ));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($curl);

        curl_close($curl);

        $rep = json_decode($response, true);

        return response()->json(['success' => true, 'message' => 'Bank list fetched', 'data' => $rep['data']]);
    }

    function verifyBank(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'account_number' => 'required|string',
            'bank_code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => implode(",", $validator->errors()->all()), 'errors' => $validator->errors()]);
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.paystack.co/bank/resolve?account_number=' . $request->account_number . '&bank_code=' . $request->bank_code,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . env("PAYSTACK_SECRET_KEY"),
                "Cache-Control: no-cache",
            ),
        ));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($curl);

        curl_close($curl);

        $rep = json_decode($response, true);

        if ($rep['status']) {
            return response()->json([
                'success' => true,
                'data' => [
                    'account_number' => $request->account_number,
                    'account_name' => $rep['data']['account_name'],
                    'bank_code' => $request->bank_code,
                ]
            ]);

        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unable to resolve account details'
            ]);
        }
    }

    function individualKyc(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'type' => 'required',
            'bvn' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'bank_name' => 'required',
            'bank_code' => 'required',
            'bank_account_number' => 'required',
            'bank_account_name' => 'required',
            'id_type' => 'required',
            'id_document' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => implode(",", $validator->errors()->all()), 'errors' => $validator->errors()]);
        }

        $fk=Kyc::where('bvn',$input['bvn'])->first();

        if($fk){
            if($fk->user_id != Auth::id()){
                return response()->json(['success' => false, 'message' => 'BVN has been used by another customer']);
            }

            return response()->json(['success' => false, 'message' => 'You have submitted your KYC already']);
        }

        $k=Kyc::create([
            'user_id' => Auth::id(),
            'type' => $input['type'],
            'address' => $input['address'],
            'bvn' => $input['bvn'],
            'bank_name' => $input['bank_name'],
            'bank_code' => $input['bank_code'],
            'bank_account_number' => $input['bank_account_number'],
            'bank_account_name' => $input['bank_account_name'],
            'id_type' => $input['id_type'],
            'id_document' => '',
        ]);


        return response()->json(['success' => true, 'message' => 'KYC submitted successfully', 'data' => $k]);
    }

    function corporateKyc(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'type' => 'required',
            'company_name' => 'required',
            'company_email' => 'required',
            'company_phone' => 'required',
            'company_address' => 'required',
            'company_taxid' => 'required',
            'bvn' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'bank_name' => 'required',
            'bank_code' => 'required',
            'bank_account_number' => 'required',
            'bank_account_name' => 'required',
            'id_type' => 'required',
            'id_document' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => implode(",", $validator->errors()->all()), 'errors' => $validator->errors()]);
        }

        $fk=Kyc::where('bvn',$input['bvn'])->first();

        if($fk){
            if($fk->user_id != Auth::id()){
                return response()->json(['success' => false, 'message' => 'BVN has been used by another customer']);
            }
            return response()->json(['success' => false, 'message' => 'You have submitted your KYC already']);
        }

        $k=Kyc::create([
            'user_id' => Auth::id(),
            'type' => $input['type'],
            'company_name' => $input['company_name'],
            'company_email' => $input['company_email'],
            'company_phone' => $input['company_phone'],
            'company_address' => $input['company_address'],
            'company_taxid' => $input['company_taxid'],
            'bvn' => $input['bvn'],
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'email' => $input['email'],
            'phone' => $input['phone'],
            'bank_name' => $input['bank_name'],
            'bank_code' => $input['bank_code'],
            'bank_account_number' => $input['bank_account_number'],
            'bank_account_name' => $input['bank_account_name'],
            'id_type' => $input['id_type'],
            'id_document' => '',
        ]);

        return response()->json(['success' => true, 'message' => 'KYC submitted successfully', 'data' => $k]);
    }

}
