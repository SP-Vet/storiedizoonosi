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
use Illuminate\Support\Facades\Http;
use App\Models\Settings;
use App\Models\Serviceevaluation;
use DB;

/**
 * Manage the service evaluation of the systemn 
 * 
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version Release: 1.0
 * @since   Class available since Release 1.0
 * 
 */
class AdminServiceevaluationController extends Controller
{
    public $mod_service;
    public $mod_privacy;
    public $mod_settings;
    public $request;
    public $menuactive='evaluation';
    public $errors_checkform=[];
    
    public function __construct(Request $request)
    {
        $this->request=$request;
        $this->mod_service = new Serviceevaluation();
        $this->mod_settings = new Settings();
        $this->mod_privacy = new Privacy();
        $this->mod_log=new LogPersonal($request);
    }
    
    /**
    *
    * View the actual questions and answers of the service evaluation
    *
    * @return \Illuminate\Http\Response
    *
    */
    public function list(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] list', $this->mod_log->getParamFrontoffice());
        if(auth()->guard('admin')->user()->role!=='admin'){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->warning('[IN] list', $this->mod_log->getParamFrontoffice('ruolo non ammesso'));
            return redirect('/admin');
        }
        $title_page='Report valutazioni servizio';
        $domande=array_column($this->mod_service->getAllQuestionsAndAnswers()->toArray(),NULL,'question');
        $risposteutente=$this->mod_service->getAllUsersAnswers();
        $datirisposte=[];
        if(isset($risposteutente) && $risposteutente->count()>0){
            foreach ($risposteutente as $risposta) {
                if(!isset($datirisposte[$risposta->question][$risposta->valueanswer]))
                    $datirisposte[$risposta->question][$risposta->valueanswer]=1;
                else
                    $datirisposte[$risposta->question][$risposta->valueanswer]++;
            }
        }
        return view('admin.evaluation.list')->with('domande',$domande)->with('risposte',$datirisposte)//->with('a',$storie)
        ->with([
            'title_page'=>$title_page,
            'admin'=>auth()->guard('admin')->user(),
            'menuactive'=>$this->menuactive,
        ]);     
    }
  
}
