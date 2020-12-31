<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentsController extends Controller
{

    public function list(){
        $datas['payments']=PaymentModel::join('plans','plans.id','=','payment.plan')->join('users', 'users.id', '=', 'payment.user_id')->select('payment.*', 'plans.name as plan', 'users.firstname as firstname', 'users.lastname as lastname', 'users.subscription as subscription')->OrderBy('id', 'desc')->get();
        $datas['sp']=PaymentModel::sum('amount');
        $datas['tp']=PaymentModel::count();
        $datas['pp']=PaymentModel::distinct('plan')->count();
        $datas['i']=1;

        return view('admin.payments', $datas);
    }


    public function receipt($id){
        $datas['payment']=PaymentModel::join('users', 'users.id', '=','payment.user_id')->where('user_id', $id)->orderBy('payment.id', 'desc')->first();

        if(!$datas['payment']){
            $datas['payments']=PaymentModel::where('user_id', $id)->get();
            $datas['sp']=PaymentModel::where('user_id', $id)->sum('amount');
            $datas['tp']=PaymentModel::where('user_id', $id)->count();
            return view('admin.payments', $datas);
        }

        return view('admin.receipt', $datas);

    }
}
