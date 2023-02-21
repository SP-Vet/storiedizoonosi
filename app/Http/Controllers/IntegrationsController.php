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
use App\Models\Integrations;
use App\Models\Stories;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\LogPersonal;

/**
 * Manage all function of the user dashboard 
 * 
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version Release: 1.0
 * @since   Class available since Release 1.0
 * 
 */
class IntegrationsController extends Controller
{
    public $mod_integrations;
    private $request;
    
    public function __construct(Request $request)
    {
        $this->request=$request;
        $this->mod_integrations = new Integrations();
        $this->mod_stories = new Stories();
        $this->mod_log=new LogPersonal($request);
    }
        
     /**
    *
    * Manages storage and notification after sending a new integration
    *
    * @return JSON
    *
    */
    public function setnewintegration(){
        Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] setnewintegration', $this->mod_log->getParamFrontoffice());
        if($this->request->idcomrisp!='' && !preg_match('/^[1-9][0-9]*$/',$this->request->idcomrisp))
            return response()->json(['error'=>true,'message'=>'Si è verificato un problema con il commento di risposta selezionato.']);
        if(($this->request->sfid!='' && !preg_match('/^[1-9][0-9]*$/',$this->request->sfid)) || !$this->request->sfid)
            return response()->json(['error'=>true,'message'=>'Si è verificato un problema con il blocco storia selezionato.']);
        if($this->request->approfondimento=='')
            return response()->json(['error'=>true,'message'=>'Nessun testo inserito come approfondimento.']);
        if(!Auth::check()){
            Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->alert('[IN] setnewintegration', $this->mod_log->getParamFrontoffice());
            return response()->json(['error'=>true,'message'=>'Utente non loggato o non autorizzato.']);
        }
        
        try{
            Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->critical('[IN TRY] setnewintegration', $this->mod_log->getParamFrontoffice());
            $user = Auth::user();
            //memorization of the integration sent
            $this->mod_integrations->setNewIntegration($user->id,$this->request->sfid,$this->request->approfondimento,$this->request->testoapprofondimento,$this->request->idcomrisp);
            
            //send TEMP EMAIL for in-depth notice entered
            $storia=$this->mod_stories->getStoryFromPhaseID($this->request->sfid);
            $datimail=array('nomeutente' => $user->name,'titolostoria'=>$storia->titolo,'titolofase'=>$storia->titolofase,'sfid'=>$this->request->sfid);
            Mail::send('emails.newintegration', $datimail, function($message){
                $message->subject('Nuovo approfondimento inserito');
                $message->to('e.rivosecchi@izsum.it');
                $message->cc('r.ciappelloni@izsum.it');
                $message->cc('m.roccetti@izsum.it');
            });
            Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[OUT TRY] setnewintegration', $this->mod_log->getParamFrontoffice());
            return response()->json(['error'=>false,'message'=>'Approfondimento inviato. Sarà reso pubblico al termine della revisione.']);
        } catch (Throwable $e) {
            Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->error('[OUT TRY] setnewintegration', $this->mod_log->getParamFrontoffice($e->getMessage()));
            return response()->json(['error'=>true,'message'=>$e->getMessage()]);
        }
    }
}
