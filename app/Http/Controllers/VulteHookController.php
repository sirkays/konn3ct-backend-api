<?php

namespace App\Http\Controllers;

use App\Models\DonationPayment;
use App\Models\PreRegUserModel;
use App\Models\User;
use App\Services\Odoo\OdooPayloadFactory;
use App\Services\Odoo\OdooSignalDispatcher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VulteHookController extends Controller
{
    public function index(Request $request){
        $input = $request->all();

        Log::info("VulteHookController:".json_encode($input));

        if($input['service'] != "wallet"){
            return "ok";
        }

        if($input['data']['status'] != "successful"){
            return "ok";
        }

        if(isset($input['data']['metadata']['pay_type'])  && $input['data']['metadata']['pay_type'] == "donation"){
            $dp=DonationPayment::where([["id",$input['data']['metadata']['payment_id']], ["status",0]])->first();

            if($dp){
                $dp->status=1;
                $dp->paid_at=Carbon::now();
                $dp->notification_response=json_encode($input);
                $dp->save();

                return "Payment Successful";
            }
        }

        if(isset($input['data']['metadata']['pay_type'])  && $input['data']['metadata']['pay_type'] == "event"){
            $dp=PreRegUserModel::where([["id",$input['data']['metadata']['payment_id']], ["paid",0]])->first();

            if($dp){
                $dp->amount=$input['data']['amount'];
                $dp->paid=1;
                $dp->paid_at=Carbon::now();
                $dp->save();

                // --- Odoo API-028: PAID_EVENT_PURCHASE ---
                // Emit only when paid transitions from 0 to 1 (already guarded above).
                // Resolve Konn3ct user_id by email — prereg_users has no FK to users.
                try {
                    $factory    = app(OdooPayloadFactory::class);
                    $dispatcher = app(OdooSignalDispatcher::class);

                    $konn3ctUser = User::where('email', $dp->email)->first();
                    $eventRef    = $dp->payment_reference ?? ('VULTE-' . $dp->id);
                    $idempKey    = 'PAID_EVENT_PURCHASE:' . $dp->prereg_id . ':' . $eventRef;
                    $ticketPrice = (float) ($dp->amount / 100); // Vulte uses minor units

                    if ($konn3ctUser) {
                        $payload = $factory->paidEventPurchase(
                            $konn3ctUser->id,
                            $dp->prereg_id,
                            $ticketPrice,
                            'paid'
                        );
                        $dispatcher->dispatch(
                            'PAID_EVENT_PURCHASE',
                            'paid_event_purchase',
                            $idempKey,
                            $payload
                        );
                    } else {
                        // User not found in users table — record as waiting_for_identity.
                        // Do NOT use prereg_users.id as user_id.
                        Log::info('Odoo PAID_EVENT_PURCHASE: no Konn3ct user found for email — waiting_for_identity', [
                            'prereg_id' => $dp->prereg_id,
                        ]);
                        $partialPayload = [
                            'user_id'      => null,
                            'event_id'     => $dp->prereg_id,
                            'ticket_price' => $ticketPrice,
                            'payment_status'=> 'paid',
                            '_identity_gap' => 'email_not_in_users_table',
                        ];
                        $dispatcher->dispatchWaitingForIdentity(
                            'PAID_EVENT_PURCHASE',
                            'paid_event_purchase',
                            $idempKey,
                            $partialPayload
                        );
                    }
                } catch (\Exception $e) {
                    Log::error('Odoo PAID_EVENT_PURCHASE dispatch failed in VulteHookController', [
                        'error' => substr($e->getMessage(), 0, 300),
                    ]);
                }

                return "Event Payment Successful";
            }
        }

        return "Noted";
    }

    public function bankTransfer(Request $request){
        $input = $request->all();

        Log::info("Bank Transfer VulteHookController:".json_encode($input));

        if(!isset($input['details'])){
            return "Not allowed";
        }

        if($input['details']['status'] != "Successful"){
            return "ok";
        }

        if(isset($input['details']['meta']['pay_type'])  && $input['details']['meta']['pay_type'] == "donation"){
            $dp=DonationPayment::where([["id",$input['details']['meta']['payment_id']], ["status",0]])->first();

            if($dp){
                $dp->status=1;
                $dp->amount=$input['details']['amount']/100;
                $dp->paid_at=Carbon::now();
                $dp->notification_response=json_encode($input);
                $dp->save();

                return "Payment Successful";
            }
        }

        return "Noted";
    }
}
