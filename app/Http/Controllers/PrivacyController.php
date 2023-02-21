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
use Illuminate\Routing\Redirector;
use Carbon\Carbon;
use App\Models\Privacy;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\LogPersonal;

/**
 * Manage the privacy policy of the systemn 
 * 
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version Release: 1.0
 * @since   Class available since Release 1.0
 * 
 */
class PrivacyController extends Controller
{
    public $mod_privacy;
    private $request;
    
    public function __construct(Request $request)
    {
        $this->request=$request;
        $this->mod_privacy = new Privacy();
        $this->mod_log=new LogPersonal($request);
    }
    
    /**
    *
    * View the last privacy policy loaded into the page
    *
    * @return \Illuminate\Http\Response
    *
    */
    public function view(){
        Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] view', $this->mod_log->getParamFrontoffice());
        $title_page='Privacy Policy';
        $data=$this->mod_privacy->getCurrentPrivacy();
        return view('privacy')->with('data',$data)->with('title_page',$title_page);       
    }
  
}
