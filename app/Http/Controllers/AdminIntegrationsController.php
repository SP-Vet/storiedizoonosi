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
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use DateTime;
use DB;
use App\Models\Integrations;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\LogPersonal;

/**
 * Manages the integrations of the platform
 * 
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version Release: 1.0
 * @since   Class available since Release 1.0
 * 
 */
class AdminIntegrationsController extends Controller
{
    public $mod_integrations;
    public $emailutente;
    private $request;
    public $menuactive='approfondimenti';
    public $errorsFormSubmission='';
    
    public function __construct(Request $request)
    {
        $this->request=$request;
        $this->mod_integrations = new Integrations();
        $this->mod_log=new LogPersonal($request);
    }
    
    /**
    *
    * List of all integrations in the system
    *   
    * @return \Illuminate\Http\Response
    *
    */
    public function list(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] list', $this->mod_log->getParamFrontoffice());
        if(auth()->guard('admin')->user()->role!=='admin')return redirect('/admin');
        $title_page='Elenco approfondimenti';
        $where=$whereand=$whereor=$wherenot=$wheresame=[];
        $order=[];
        $order['sa.data_inserimento']='DESC';
        $approfondimenti=$this->mod_integrations->getAll([],$order);
        
        return view('admin.integrations.list')->with('approfondimenti',$approfondimenti)
                ->with([
                    'title_page'=>$title_page,
                    'admin'=>auth()->guard('admin')->user(),
                    'menuactive'=>$this->menuactive,
                        
                ]);
    }
    
    /**
     * Manage all parameters of an integration.
     *
     * @return \Illuminate\Http\Response
     */
    public function manage(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] manage', $this->mod_log->getParamFrontoffice());
        if(auth()->guard('admin')->user()->role!=='admin')return redirect('/admin');
        $title_page='Modifica approfondimento';
        $datiapp=[];
        
        if($this->request->isMethod('post')){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] manage', $this->mod_log->getParamFrontoffice('richiesta modifica'));
            $datiapp=$this->request->all();
            if($this->checkform()){
                Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] manage', $this->mod_log->getParamFrontoffice('form corretto'));
                //UPDATE integration text
                DB::beginTransaction();
                try {
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[IN TRY] manage', $this->mod_log->getParamFrontoffice());
                    $approfondimento = Integrations::find($datiapp['said']);
                    $oldstato=$approfondimento->stato;
                    $inviamail=0;
                    if((!$oldstato || $oldstato==0) && ($datiapp['stato']==1)){
                        $inviamail=1;
                        $approfondimento->data_pubblicazione='NOW()';
                    }                        
                    $approfondimento->stato=$datiapp['stato'];
                    $approfondimento->testoapprofondimento = $datiapp['testoapprofondimento'];
                    $approfondimento->save();
                    DB::commit();
                    
                    //send approvation email to the users
                    if($inviamail==1){
                        $user=User::find($approfondimento->uid);
                        $this->emailutente=$user->email;
                        $datimail=array('testoapprofondimento' => html_entity_decode($approfondimento->testoapprofondimento,ENT_QUOTES,'utf-8'));
                        Mail::send('emails.integrationapproved', $datimail, function($message){
                            $message->subject('Nuovo approfondimento inserito');
                            $message->to($this->emailutente);
                        });
                        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[OUT SEND MAIL] integrations', $this->mod_log->getParamFrontoffice());
                    }
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[OUT TRY] manage', $this->mod_log->getParamFrontoffice());
                    $this->request->session()->flash('messageinfo', 'Approfondimento aggiornato correttamente');
                    return redirect(route('adminListIntegrations'));
                } catch (Throwable $e) {
                    DB::rollBack();
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT] manage', $this->mod_log->getParamFrontoffice($e->getMessage()));
                    echo $e->getMessage();
                    exit;
                }
            }else{
                Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT] manage', $this->mod_log->getParamFrontoffice('form non corretto'));
                $this->request->session()->flash('formerrato', '<h5>Dati non corretti</h5>'."".$this->errorsFormSubmission);
            }
        }else{
            $datiapp=$this->mod_integrations->getIntegration($this->request->said);
            $datiapp=get_object_vars($datiapp->toArray()[0]);
        }
        
        return view('admin.integrations.manage')->with('datapost',$datiapp)->with('form','adminSaveManagementIntegration')
                ->with([
                    'title_page'=>$title_page,
                    'admin'=>auth()->guard('admin')->user(),
                    'menuactive'=>$this->menuactive,
                    
                ]);
    }
    
    
  /**
    *
    * Method for checking the validity of the management form data
    * @return BOOL
    *
    */
    private function checkform(){
        $request_post=$this->request->all();
        //check for missing required data
        $datimancanti=[];
        if(!$request_post['testoapprofondimento'])$datimancanti[]='Testo approfondimento mancante';
        if(!preg_match('/^[1-9][0-9]*$/',$request_post['said']))$datimancanti[]='Approfondimento mancante';
        if(count($datimancanti)>0){
            $this->setVisualErrors($datimancanti);
            return false;
        }
        return true;
     
    }
    
    /**
    *
    * Method for quickly publishing an integration
    * @return JSON
    *
    */
    public function publishintegrations(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] publishintegrations', $this->mod_log->getParamFrontoffice());
        if(!preg_match('/^[1-9][0-9]*$/',$this->request->said)){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[OUT] publishintegrations', $this->mod_log->getParamFrontoffice('id approfondimento non valido'));
            return response()->json(['error'=>true,'message'=>'Approfondimento selezionato non valido']);   
        }
        $this->mod_integrations->publishIntegration($this->request->said,['stato'=>1,'data_pubblicazione'=>'NOW()']);
        $approfondimento = Integrations::find($this->request->said);
        $user=User::find($approfondimento->uid);
        
        //send confirmation email
        $this->emailutente=$user->email;
        $datimail=array('testoapprofondimento' => html_entity_decode($approfondimento->testoapprofondimento,ENT_QUOTES,'utf-8'));
        Mail::send('emails.integrationapproved', $datimail, function($message){
            $message->subject('Nuova integrazione inserita');
            $message->to($this->emailutente);
        });
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[END SEND MAIL] integrations', $this->mod_log->getParamFrontoffice());        
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[OUT] publishintegrations', $this->mod_log->getParamFrontoffice());
        return response()->json(['error'=>false,'message'=>'']);
    }
    
    /**
    *
    * Method for get all integrations of a phase
    * @return JSON
    *
    */
    public function getphaseintegrations(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] getphaseintegrations', $this->mod_log->getParamFrontoffice());
        if($this->request->sfid!='' && !preg_match('/^[1-9][0-9]*$/',$this->request->sfid)){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT] getphaseintegrations', $this->mod_log->getParamFrontoffice('parametri non validi'));
            return response()->json(['error'=>true,'message'=>'Si è verificato un problema con la le integrazioni scelte.']);
        }
       
        $integrazioni=$this->mod_integrations->getAll(['sa.sfid'=>$this->request->sfid]);
        if(isset($integrazioni) && count($integrazioni)>0){
            foreach($integrazioni AS $integrazione){
                $integrazione->testoselezionato=html_entity_decode($integrazione->testoselezionato,ENT_QUOTES,'utf-8');
            }
        }
        return response()->json(['error'=>false,'message'=>'','integrazioni'=>$integrazioni]);
    }

    /**
    *
    * Method for set up all form errors
    * @param Array $arrayErr Array with error strings
    * @return BOOL
    *
    */
    private function setVisualErrors($arrayErr){
        foreach ($arrayErr AS $key=>$textErrore){
            $this->errorsFormSubmission.='<b>'.$textErrore.'</b><br />';
        }
        unset($arrayErr);
        return true;
    }
}
