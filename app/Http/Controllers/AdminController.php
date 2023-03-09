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
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Encore\Admin\Auth\Permission;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DB;
use Session;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\LogPersonal;
use App\Models\Stories;
use App\Models\Storiessubmit;
use App\Models\Integrations;
use Carbon\Carbon;

/**
 * Manages the platform's admin data
 * 
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version Release: 1.0
 * @since   Class available since Release 1.0
 * 
 */
class AdminController extends Controller
{    
    
    public $errorsFormSubmission='';
    private $request;

    public function __construct(Request $request)
    {
        $this->request=$request;
        $this->mod_log=new LogPersonal($request);         
    }
    
    /**
     * Show the application admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] dashboard', $this->mod_log->getParamFrontoffice());
        $title_page='';
        
        $mod_storiessubmit= new Storiessubmit();
        $storie_sottomesse=[];
        $storie_sottomesse=$mod_storiessubmit->getStorieSubmitNONgestite();        
        $mod_stories= new Stories();
        $storie_bozze=[];
        $filterstorie=[['s.stato',1]]; //in lavorazione (bozza)
        $storie_bozze=$mod_stories->getStories($filterstorie);        
        $mod_integrations= new Integrations();
        $approf_inseriti=[];
        $filterapprof=[['sa.stato',0]]; //inseriti non ancora approvati
        $approf_inseriti=$mod_integrations->getAll($filterapprof);
        
        return view('admin.dashboard')->with([
                    'title_page'=>$title_page,
                    'admin'=>auth()->guard('admin')->user(),
                ])->with('storie_sottomesse',$storie_sottomesse)->with('storie_bozze',$storie_bozze)->with('approf_inseriti',$approf_inseriti);
    }
    
    /**
     * Change the admin log in password
     *
     * @return \Illuminate\Http\Response
     */
    public function cambiapassword(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] cambiapassword', $this->mod_log->getParamFrontoffice());
        if(auth()->guard('admin')->user()->id!=$this->request->id){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->warning('[OUT] cambiapassword', $this->mod_log->getParamFrontoffice('tentata mdifica altro utente'));
            return redirect(route('dashboard'));
        }
        $title_page='Cambia password';
        if($this->request->isMethod('post')){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] cambiapassword', $this->mod_log->getParamFrontoffice('richiesta post del cambio'));
            if($this->checkControlPassword($this->request->password)){
                DB::beginTransaction();
                try {
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[IN TRY] cambiapasswrod', $this->mod_log->getParamFrontoffice());
                    Admin::find(auth()->guard('admin')->user()->id)->update(['password'=> Hash::make($this->request->password),'password_changed_at'=>Carbon::now()]);
                    DB::commit();
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[OUT TRY] cambiapasswrod', $this->mod_log->getParamFrontoffice());
                    $this->request->session()->flash('messageinfo', 'Password modificata correttamente');
                } catch (Throwable $e) {
                    DB::rollBack();
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT TRY] cambiapasswrod', $this->mod_log->getParamFrontoffice($e->getMessage()));
                    $this->request->session()->flash('messagedanger', 'Errore interno al sistema, passworn NON modificata');
                }
                return redirect(route('dashboard'));    
            }else{
                Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT] cambiapasswrod', $this->mod_log->getParamFrontoffice('form errato'));
                $this->request->session()->flash('formerrato', '<h5>Dati non corretti</h5>'."".$this->errorsFormSubmission);
            }
        }
        
        return view('admin.cambiapassword')->with([
                    'title_page'=>$title_page,
                    'admin'=>auth()->guard('admin')->user(),
                ]);
    }
    
    /**
     * Check validation of the input password submitted
     *
     * @return BOOL
     */
    private function checkControlPassword(){
        $request_post=$this->request->all();
        //check for missing required data
        $datiintegri=[];
        if(!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%-*_£()])[0-9A-Za-z!@#$%-*_£()]{8,}$/', trim($request_post['password'])))$datiintegri[]='La password non rispetta gli standard di sicurezza';
        if(trim($request_post['password'])!==trim($request_post['ripetipassword']))$datiintegri[]='Le password fornite non coincidono';
        if(count($datiintegri)>0){
            $this->setVisualErrors($datiintegri);
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

}