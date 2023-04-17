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
class ServiceevaluationController extends Controller
{
    public $mod_service;
    public $mod_privacy;
    public $mod_settings;
    public $request;
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
        Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] list', $this->mod_log->getParamFrontoffice());
        $title_page='Valutazione del Servizio';
        $data=$this->mod_privacy->getCurrentPrivacy();

        $settings=[];
        $settings=array_column($this->mod_settings->getAll([],[0,5])->toArray(),NULL,'nameconfig');

        $domande=$this->mod_service->getAllQuestionsAndAnswers(['sea.actualanswer'=>1]);
        //echo '<pre>';print_r($domande);exit;
        return view('evaluationservice')->with('domande',$domande)->with('title_page',$title_page)->with('settings',$settings);       
    }


    
    /**
    *
    * Check service evaluation validity 
    *   
    * @return \Illuminate\Http\Response
    *
    */
    public function add(){
        Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] add', $this->mod_log->getParamFrontoffice());
        $responseMTCaptcha = Http::get('https://service.mtcaptcha.com/mtcv1/api/checktoken?privatekey='.config('app.MTCAPTCHAprivate').'&token='.$this->request->input('mtcaptcha-verifiedtoken'));
        $dataRresponse=$responseMTCaptcha->json();
        if($dataRresponse['success']){
            if ($this->checkform()) {
                $request_post=$this->request->all();
                //store data evaluation form
                DB::beginTransaction();
                try {
                    Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->critical('[IN TRY] add', $this->mod_log->getParamFrontoffice());
                    //insert
                    foreach($request_post['seaid'] AS $t=>$seaid){
                        $key='valueanswer'.$seaid;
                        $dati=[];
                        $dati['seaid']=$seaid;
                        $dati['valueanswer']=$request_post[$key];
                        $this->mod_service->setEvaluationAnswer($dati);
                    }
                    DB::commit();
                    Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[OUT] add', $this->mod_log->getParamFrontoffice());
                    $this->request->session()->flash('messageinfo', '<h2>Grazie per aver inviato il tuo feedback.</h2>');
                    return redirect(route('serviceEvaluation'));
                } catch (Throwable $e) {
                    DB::rollBack();
                    Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->error('[OUT TRY] add', $this->mod_log->getParamFrontoffice($e->getMessage()));
                    return redirect(route('serviceEvaluation'));
                }
            }else{
                Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->error('[OUT] add', $this->mod_log->getParamFrontoffice('form errato'));
                $this->request->session()->flash('messagedanger', '<h2>Dati non corretti</h2>'."<br />".$this->errorsFormSubmission);
            }
        }else{
            Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->error('[OUT] add', $this->mod_log->getParamFrontoffice('captcha non validato'));
            return back()->with('messagedanger','Captcha non validato correttamente');
        }
        return back();
    }




    /**
    *
    * Method for checking validation data of the form
    * @return BOOL
    *
    */
    private function checkform(){
        $request_post=$this->request->all();
        //check for missing required data
        $datimancanti=[];
        if(!is_array($request_post['seaid']) || (is_array($request_post['seaid']) && count($request_post['seaid'])==0))$datimancanti[]='Domande non valide';
        if(is_array($request_post['seaid'])){
            foreach($request_post['seaid'] AS $k=>$seaid){
                if(!preg_match('/^[1-9][0-9]*$/',$seaid)){
                    $datimancanti[]='Domanda non valida';
                    break;
                }
            }
        }

        $risposte=array_column($this->mod_service->getAllQuestionsAndAnswers(['sea.actualanswer'=>1])->toArray(),NULL,'seaid');
        if(is_array($request_post['seaid'])){
            $seaid_risposte_utente=array_flip($request_post['seaid']);
            foreach($risposte AS $risposta){
                if(!array_key_exists($risposta->seaid,$seaid_risposte_utente)){
                    $datimancanti[]='Domanda non disponibile';
                    break;
                }  
            }
        }
        
        foreach($risposte AS $risposta){
            $key='valueanswer'.$risposta->seaid;
            if(!isset($request_post[$key]))$datimancanti[]='Risposta mancante';
            switch($risposta->typeanswer){
                case 1:
                    if($request_post[$key]!=='SI' && $request_post[$key]!=='NO')$datimancanti[]='Risposta non valida';
                    break;
                case 2:
                    if(!preg_match('/^[1-9][0-9]*$/',$request_post[$key]))$datimancanti[]='Risposta non valida';
                    break;
                case 3:
                    if($request_post[$key]!=='SI' && $request_post[$key]!=='NO' && $request_post[$key]!=='NON SAPREI')$datimancanti[]='Risposta non valida';
                    break;
                default:
                    break;
            }
        }

        if(count($datimancanti)>0){
            $this->errors_checkform=$datimancanti;
            return false;
        }
        return true;
    }
  
}
