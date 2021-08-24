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

    function encryptdata($plain)
    {
        return bin2hex(openssl_encrypt($plain, "aes-128-cbc", env("ENCRYPT_AESKEY"), OPENSSL_RAW_DATA, env("ENCRYPT_IVKEY")));
    }

    function decryptdata($encriptedData)
    {
        $ciphertext = hex2bin($encriptedData);
        return openssl_decrypt($ciphertext, "aes-128-cbc", env("ENCRYPT_AESKEY"), OPENSSL_RAW_DATA, env("ENCRYPT_IVKEY"));
    }

}
