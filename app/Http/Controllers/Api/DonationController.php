<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\RoomModel;
use Illuminate\Http\Request;
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
            'type' => 'required',
            'amount' => 'required',
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Check your inputs and try again', 'error' => $validator->errors()]);
        }

        $i = RoomModel::find($input['id']);

        if (!$i) {
            return response()->json(['success' => false, 'message' => 'Invalid Room!']);
        }

        Donation::create([
            'room_id' => $i->id,
            'user_id' => $i->user_id,
            'name' => $input['name'],
            'type' => $input['type'],
            'amount' => $input['amount']
        ]);

        return response()->json(['success' => true, 'message' => 'Donation created successfully']);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Donation  $donation
     * @return \Illuminate\Http\Response
     */
    public function show($room_id)
    {
        $donation=Donation::where([['room_id',$room_id], ['status',1]])->latest()->get();
        return response()->json(['success' => true, 'message' => 'Donation fetched successfully', 'data'=>$donation]);
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
}
