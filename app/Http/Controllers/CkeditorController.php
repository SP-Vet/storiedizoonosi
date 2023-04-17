<?php
/*
 * Italian Ministry of Health Research Project: MEOH/2021-2022 - IZS UM 04/20 RC
 * Created on 2023
 * @author Eros Rivosecchi <e.rivosecchi@izsum.it>
 * @author IZSUM Sistema Informatico <sistemainformatico@izsum.it>
 * 
 * @license 
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at

 * http://www.apache.org/licenses/LICENSE-2.0

 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 * 
 * @version 1.0
 */


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use File;
use Illuminate\Support\Facades\Storage;

/**
 * Manage function of CKeditor library 
 * 
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version Release: 1.0
 * @since   Class available since Release 1.0
 * 
 */
class CkeditorController extends Controller
{

    /**
     * Success response method.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ckeditor');
    }

    /**
     * Manage the upload of a new content.
     * @param Request $request all parameter insert to the request
     * @return \Illuminate\Http\Response
     */

    public function upload(Request $request)
    {
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

    /**
     * Manage the upload of a new image contextdata content.
     * @param Request $request all parameter insert to the request
     * @return \Illuminate\Http\Response
     */

     public function uploadcontextdataimage(Request $request)
     {
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
             $url=asset('storagetextareaviewimage/'.$fileName);
             $msg = 'Upload immagine completato'; 
             $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
             //@header('X-FRAME-OPTION : SAMEORIGIN');
             @header('Content-type: text/html; charset=utf-8'); 
             echo $response;
         }
     }

    /**
     * Manage the upload of a new public image content.
     * @param Request $request all parameter insert to the request
     * @return \Illuminate\Http\Response
     */

     public function uploadpublicimage(Request $request)
     {
         if($request->hasFile('upload')) {
             $pathst = storage_path('/public/images');
             $originName = $request->file('upload')->getClientOriginalName();
             $fileName = pathinfo($originName, PATHINFO_FILENAME);
             $extension = $request->file('upload')->getClientOriginalExtension();
             //$fileName =  random_str(8, 'abcdefghijklmnopqrstuvwxyz').'_'.time().'.'.$extension;            
             //$fileName = strtolower(str_replace(' ', '_', $fileName)).'_'.time().'.'.$extension;
             //$request->file('upload')->move(public_path('images'), $fileName);
             $request->file('upload')->move($pathst, $fileName);
             $CKEditorFuncNum = $request->input('CKEditorFuncNum');
             $url = asset('images/'.$fileName); 
             $msg = 'Upload file completato'; 
             $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
             //@header('X-FRAME-OPTION : SAMEORIGIN');
             @header('Content-type: text/html; charset=utf-8'); 
             echo $response;
         }
     }
}