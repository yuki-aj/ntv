<?php
namespace App\Http\Controllers;
use Session,App,Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;//ストレージ存在チェック
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CommonController;
use App\Models\User;
use App\Models\Store;
use App\Models\Option;
use App\Models\Product;
use App\Models\Prefecture;
use App\Models\Type;
use App\Models\Unit;
use App\Models\Grade;
use App\Models\Time;
use App\Models\Category;
use App\Models\Summertest;
use Illuminate\Pagination\LengthAwarePaginator;
class SummernoteController extends Controller{
    public function create(){
        return view('public/summernote');
    }
    public function image (Request $request){
        $result=$request->file('file')->isValid();//画像のデータがあるか判定
        if($result){//あれば
            $filename = $request->file->getClientOriginalName();//画像の名前を取得
            $file_name = '/img_files/summernote/'.$filename;
            // Storage::disk(env('STORAGE_ENV'))->putFileAs('public', $file_name);
            // $file_name = '/img_files/summernote/'.$filename;
            // if($request->file){
            //     $file = $request->file('file');
            //     //画像をリサイズ&トリミング&jpg変換
            //     Image::make($file)->fit(800, 450)->encode('jpg')->save();
            //     $file_name = $filename;
            //     $summernote   = Storage::disk(env('STORAGE_ENV'))->putFileAs('public/img_files/summernote', $file, $file_name);
            // // $user->save();
			// }
            $file=$request->file('file')->move('temp', $filename);
            echo asset('/temp/'.$filename);
        }
    }
        public function store(Request $request){
            // dd($request->file('files'));
            $summertest=new Summertest();
            $summertest->summernote=$request->summernote;
            $summertest->save();
            // $request->file('files')->store('public/img_files/temp');//画像をstorageに保存
            // return redirect()->route('summernote.create');
            return redirect('/summernote/create');
            // return back();
            // return response()->file(storage_path('app/public/img_files/store/store1.jpg'));
        }
}/* EOF */