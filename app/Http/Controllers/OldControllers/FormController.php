<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;//ストレージ保存用
use Illuminate\Http\Request;
use App\Http\Requests\FilesRequest;
use App\Models\Form;// 投稿一覧用

class FormController extends Controller 
{
    public function Upload(FilesRequest $request){
        // var_dump($request->main);
        // return;
        $post = new Form();
        $post->title = $request->title;
        $post->main  = $request->main;
        $post->created_at   = date('Y-m-d H:i:s');
        $post->updated_at    = date('Y-m-d H:i:s');
        $post->id = $request->id;
        $post->save();// データーベースに保存

        // 画像以外のファイルは保存しない
        if($request->post_img){
            if($request->post_img->extension() == 'gif' 
            || $request->post_img->extension() == 'jpeg' 
            || $request->post_img->extension() == 'jpg' 
            || $request->post_img->extension() == 'png')
            // ||　もしくは
            {
            // ディレクトリ名
            $dir = 'post_img';
            // アップロードされたファイル名を取得
            $file_name = $request->file('post_img')->getClientOriginalName();
            // 取得したファイル名で保存
            $request->file('post_img')->storeAs('public/' . $dir, $file_name);
            }
        }
        return redirect ('/postlist');
    }


    // WYSIWYG エディタ
    public function Wysiwyg (Request $request){
        return view ('public/wysiwyg');
    }

    //   投稿一覧
    public function Postlist (Request $request){
        // $data = Form::all(); これだと並べ替えできない
        // $datas = Form::orderBy('created_at', 'desc') ->limit(5)->get();
        $datas = Form::orderBy('created_at', 'desc') ->get();// 全投稿を新着順に取り出す
        return view('public/post')->with(['datas' => $datas]);
    }

    //   投稿詳細
    public function Show ($id){    
        $data = Form::where('id', $id)->first();// idが一致する投稿を1つだけ取り出す
        return view('public/show')->with(['data' => $data]);
    }
 
}
    //   echo $data; 取り出した中身を見る
