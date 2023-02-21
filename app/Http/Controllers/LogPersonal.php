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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

/**
 * Manage all functions to view and storage the logs 
 * 
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version Release: 1.0
 * @since   Class available since Release 1.0
 * 
 */
class LogPersonal extends Controller
{
    private $request;
    private $ip;
    private $useragent;
    private $session;
    private $route;
    private $post;
    private $file;
    private $fullurl;    
    public $errorsFormSubmission='';
    
    public function __construct(Request $request)
    {
        $this->request=$request;
        $this->ip=$request->ip();
        $this->useragent=$request->userAgent();
        $this->session= $request->getSession();
        $this->route=$request->route();
        $this->post=$request->post();
        $this->fullurl=$request->fullUrl();
        $this->file=$request->file();
    }
    
    /**
    *
    * List the log dashboard page
    *
    * @return \Illuminate\Http\Response
    *
    */
    public function elenco(){
        return view('admin.log.elenco');
    }
    
    /**
    *
    * List all zoonoses in the system
    * @param String $message an optional message to insert into the log
    * @return Array
    *
    */
    public function getParamFrontoffice($message=''){
        return ['ip'=>$this->ip,'fullurl'=>$this->fullurl,'message'=>$message,'useragent'=>$this->useragent,'route'=>$this->route,'post'=>$this->post,'session'=>$this->session,'file'=>$this->file,'user'=>(Auth::user())?Auth::user()->id:null,'admin'=>(auth()->guard('admin')->user())?auth()->guard('admin')->user()->id:''];
    }
}
