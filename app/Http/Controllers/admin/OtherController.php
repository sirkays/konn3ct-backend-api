<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;

class OtherController extends Controller
{
    public function faqs()
    {
        $datas['faqs'] = Faq::where("status", 1)->get();
        $datas['i'] = 1;

        return view('admin.faqs', $datas);
    }
}
