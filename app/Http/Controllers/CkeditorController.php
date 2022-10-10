<?php

  

namespace App\Http\Controllers;

  

use Illuminate\Http\Request;
use File;
use Illuminate\Support\Facades\Storage;

class CkeditorController extends Controller

{

    /**

     * success response method.

     *

     * @return \Illuminate\Http\Response

     */

    public function index()

    {

        return view('ckeditor');

    }

  

    /**

     * success response method.

     *

     * @return \Illuminate\Http\Response

     */

    public function upload(Request $request)

    {

        //echo "dasdasdsadasda";exit;
        if($request->hasFile('upload')) {
            $pathst = storage_path('app/public/storietextarea');
            if(!File::isDirectory($pathst)){
                File::makeDirectory($pathst, 0777, true, true);
            }
            
            $originName = $request->file('upload')->getClientOriginalName();

            $fileName = pathinfo($originName, PATHINFO_FILENAME);

            $extension = $request->file('upload')->getClientOriginalExtension();
            //$fileName =  random_str(8, 'abcdefghijklmnopqrstuvwxyz').'_'.time().'.'.$extension;            
            $fileName = strtolower(str_replace(' ', '_', $fileName)).'_'.time().'.'.$extension;
            //$request->file('upload')->move(public_path('images'), $fileName);
            $request->file('upload')->move($pathst, $fileName);
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            //$url = asset('images/'.$fileName); 
         
            $url=asset(Storage::url('storietextarea/'.$fileName));
            $msg = 'Upload immagine completato'; 
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
            //@header('X-FRAME-OPTION : SAMEORIGIN');
            @header('Content-type: text/html; charset=utf-8'); 
            echo $response;

        }

    }

}