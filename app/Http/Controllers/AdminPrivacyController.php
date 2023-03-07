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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\Admin;
use App\Models\Stories;
use App\Models\Privacy;
use DateTime;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\LogPersonal;

/**
 * Manages the platform's privacy policy
 * 
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version Release: 1.0
 * @since   Class available since Release 1.0
 * 
 */
class AdminPrivacyController extends Controller
{
    public $mod_privacy;
    private $request;
    public $menuactive='privacy';
    public $errorsFormSubmission='';
    
    public function __construct(Request $request)
    {
        $this->request=$request;
        $this->mod_privacy = new Privacy();
        $this->mod_log=new LogPersonal($request);
    }
    

    /**
    *
    * Lists all privacy policy of the system
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
        $title_page='Elenco Privacy Policy';
        $where=[];
        $order=[];
        $order['p.attuale']='DESC';
        $order['p.data_pubblicazione']='DESC';
        $privacy=$this->mod_privacy->getAll([],$order);
        return view('admin.privacy.list')->with('privacys',$privacy)
                ->with([
                    'title_page'=>$title_page,
                    'admin'=>auth()->guard('admin')->user(),
                    'menuactive'=>$this->menuactive,
                ]);
    }
 

    /**
    *
    * Manage data of a privacy policy
    * @return \Illuminate\Http\Response
    *
    */
    public function modify(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] modify', $this->mod_log->getParamFrontoffice());
        $title_page='Aggiungi/Modifica Privacy';
        if($this->request->isMethod('post')){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] modify', $this->mod_log->getParamFrontoffice('inviato il post della privacy'));
            $datiprivacy=$this->request->all();
            //echo '<pre>';print_r($datiprivacy);exit;
            if($this->checkform()){
                Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] modify', $this->mod_log->getParamFrontoffice('post corretto'));
                DB::beginTransaction();
                try {
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[IN TRY] modify', $this->mod_log->getParamFrontoffice());
                    //memo privacy      
                    $deactivate_old_actual=false;
                    if(!$datiprivacy['ppid']){   
                        $privacy = new Privacy();
                        $privacy->data_inserimento = 'NOW()';
                        if($datiprivacy['attuale']==1){
                            $privacy->data_pubblicazione = 'NOW()';
                            $deactivate_old_actual=true;
                        }
                    }else{
                        $privacy = Privacy::find($datiprivacy['ppid']);
                        $oldprivacy=$this->mod_privacy->getPrivacyFromID($datiprivacy['ppid'])->toArray();
                        $oldprivacy=$oldprivacy[0];
                        if($oldprivacy->attuale==0  && $datiprivacy['attuale']==1){
                            $privacy->data_pubblicazione = 'NOW()';
                            $deactivate_old_actual=true;
                        }
                    }
                    $privacy->attuale=$datiprivacy['attuale'];
                    $privacy->reflag=$datiprivacy['reflag'];
                    $privacy->testoprivacy=$this->dataready($datiprivacy['testoprivacy']);
                    $privacy->save();
                    if($deactivate_old_actual)
                        $this->mod_privacy->deactivateOldPrivacy($privacy->ppid);
                                   
                    DB::commit();
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[OUT TRY] modify', $this->mod_log->getParamFrontoffice());
                    $this->request->session()->flash('messageinfo', '<h2>Privacy aggiornata/inserita con successo!</h2>');   
                    return redirect(route('adminListPrivacy'));
                } catch (Throwable $e) {
                    DB::rollBack();
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT TRY] modify', $this->mod_log->getParamFrontoffice($e->getMessage()));
                    $this->request->session()->flash('messagedanger', '<h2>!!ERRORE!! Privacy NON aggiornata/inserita! Contattare l&apos;amministratore del sistema.</h2>');   
                    return redirect(route('adminListPrivacy'));
                }
            }else{
                Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT] modify', $this->mod_log->getParamFrontoffice('form errato'));
                $this->request->session()->flash('formerrato', '<h5>Dati non corretti - RICONTROLLARE TUTTI I CAMPI INSERITI</h5>'."".$this->errorsFormSubmission);
            }            
        }

        $ppid=(isset($this->request->ppid))?$this->request->ppid:'';
        $privacy=new \Illuminate\Support\Collection();
        if($ppid)
            $privacy=$this->mod_privacy->getAll([['p.ppid',$ppid]])[0];

        return view('admin.privacy.addmod')->with('privacy',$privacy)->with('ppid',$ppid)
                ->with('form','adminSaveModifyPrivacy')
                ->with([
                    'title_page'=>$title_page,
                    'admin'=>auth()->guard('admin')->user(),
                    'menuactive'=>$this->menuactive,
                ]);
    }
    
    /**
    *
    * Method for checking validation data of the insert/modify form
    * @return BOOL
    *
    */
    private function checkform(){
        $request_post=$this->request->all();
        //check for missing required data
        $datimancanti=[];
        if($request_post['ppid']!='' && !preg_match('/[0-9]+/', $request_post['ppid']))$datimancanti[]='Privacy non valida';
        //check privacy data values
        if(count($datimancanti)>0){
            $this->setVisualErrors($datimancanti);
            return false;
        }
        return true;
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
    
    /**
    *
    * Method to prepare data for storage
    * @param STRING $data string value to set
    * @return STRING
    *
    */
    private function dataready($data) {
        if(!$data)return '';
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    } 
}
