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
use App\Models\Settings;
use App\Models\User;
use DB;

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
    public $mod_settings;
    public $mod_user;
    public $request;
    public $errors_checkform=[];
    
    public function __construct(Request $request)
    {
        $this->request=$request;
        $this->mod_privacy = new Privacy();
        $this->mod_settings = new Settings();
        $this->mod_user = new User();
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

        $settings=[];
        $settings=array_column($this->mod_settings->getAll([],[0,5])->toArray(),NULL,'nameconfig');

        return view('privacy')->with('data',$data)->with('title_page',$title_page)->with('settings',$settings);       
    }

    /**
    *
    * Manage the page for the privacy acceptance
    *
    * @param Integer $uid id of the user
    * @return \Illuminate\Http\Response
    *
    */
    public function privacyacceptance($uid){
        Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] privacyacceptance', $this->mod_log->getParamFrontoffice());
        $title_page='Privacy Policy Accettazione';
        if($this->request->isMethod('post')){
            Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] privacyacceptance', $this->mod_log->getParamFrontoffice('inviato post di accettazione'));
            if($this->checkform()){
                $request_post=$this->request->all();
                DB::beginTransaction();
                try {
                    Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->critical('[IN TRY] privacyacceptance', $this->mod_log->getParamFrontoffice());
                    
                    //set privacy policy acknowledgment
                    $this->mod_privacy->setAccept($request_post['idutente'],2);

                    DB::commit();
                    Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->critical('[OUT TRY] privacyacceptance', $this->mod_log->getParamFrontoffice());
                    Auth::loginUsingId($request_post['idutente']);
                    return redirect('/');
                } catch (Throwable $e) {
                    DB::rollBack();
                    Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->error('[OUT TRY] privacyacceptance', $this->mod_log->getParamFrontoffice($e->getMessage()));
                    echo $e->getMessage();
                    exit;
                }
            }else{

                Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->error('[OUT] checkform', $this->mod_log->getParamFrontoffice('parametri di accettazione non validi')."\r\n".implode(', ',$this->errors_checkform));
                return redirect('/login');
            }
        }

        //checkdatauser
        if(!isset($uid))return redirect ('/login');
        //get privacy accepted by the user
        $current_privacy=$this->mod_privacy->getCurrentPrivacy();
        $privacy_accepted_user=$this->mod_privacy->getLastAcceptedPrivacyFromUser($uid);

        if(!isset($privacy_accepted_user->ppid) || 
        (isset($privacy_accepted_user->ppid) && $privacy_accepted_user->ppid!=$current_privacy->ppid) ||
        (isset($privacy_accepted_user->ppid) && $current_privacy->reflag==1 && (Carbon::createFromFormat('Y-m-d H:i:s', $current_privacy->data_pubblicazione) > Carbon::createFromFormat('Y-m-d H:i:s', $privacy_accepted_user->data_accettazione_visione))))
        {
            $settings=[];
            $settings=array_column($this->mod_settings->getAll([],[0,5])->toArray(),NULL,'nameconfig');
            return view('privacyacceptance')->with('current_privacy',$current_privacy)->with('form','savePrivacy')
                ->with('privacy_accepted_user',$privacy_accepted_user)->with('uid',$uid)
                ->with('title_page',$title_page)->with('settings',$settings);  
        }else{
            return redirect('/login');
        }
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

        if(!$request_post['ppid'])$datimancanti[]='id privacy non esistente';
        if(!$request_post['idutente'])$datimancanti[]='id utente non esistente';
        if(!preg_match('/^[1-9][0-9]*$/',$request_post['ppid']))$datimancanti[]='id privacy non valido';
        if(!preg_match('/^[1-9][0-9]*$/',$request_post['idutente']))$datimancanti[]='id utente non valido';

        $current_privacy=$this->mod_privacy->getCurrentPrivacy();
        if(isset($request_post['idutente']) && preg_match('/^[1-9][0-9]*$/',$request_post['idutente'])){
            $privacy_accepted_user=$this->mod_privacy->getLastAcceptedPrivacyFromUser($request_post['idutente']);
            $user=$this->mod_user->getUserFromID($request_post['idutente']);
            if(isset($user))
                $user=$user[0];
           
            if(!isset($user->id) || (isset($user->id) && $user->id!=$request_post['idutente']))$datimancanti[]='id utente non esistente o non combacianti';
            if(isset($privacy_accepted_user->ppid)){
                if($privacy_accepted_user->ppid==$current_privacy->ppid && (Carbon::createFromFormat('Y-m-d H:i:s', $current_privacy->data_pubblicazione) < Carbon::createFromFormat('Y-m-d H:i:s', $privacy_accepted_user->data_accettazione_visione)))
                    $datimancanti[]='privacy già accettata dall&#39;utente';   
            }
        }
        if($request_post['ppid']!=$current_privacy->ppid)$datimancanti[]='privacy attuali non corrispondenti';   

        if(count($datimancanti)>0){
            $this->errors_checkform=$datimancanti;
            return false;
        }
        return true;
    }
  
}
