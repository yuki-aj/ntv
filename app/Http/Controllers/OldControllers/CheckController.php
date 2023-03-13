<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SampleController extends Controller
{
    public function inputConfirm(Request $request) {
        // お名前
        $name = $request->name;
        
        // 電話番号
        $tel = $request->tel;
        
        // 確認画面に表示する値を格納
        $input_data = [
            'title' => $title,
            'main' => $main,
        ];
        
        return view('confirm', $input_data);
    }
}