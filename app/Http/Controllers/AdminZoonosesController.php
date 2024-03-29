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
use App\Models\Zoonoses;
Use App\Models\Reviews;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\LogPersonal;

/**
 * Manages all zoonoses entered into the system 
 * 
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version Release: 1.0
 * @since   Class available since Release 1.0
 * 
 */
class AdminZoonosesController extends Controller
{
    public $mod_zoonoses;
    private $request;
    public $errorsFormSubmission='';
    public $menuactive='zoonosi';
    
    public function __construct(Request $request)
    {
        $this->request=$request;
        $this->mod_zoonoses = new Zoonoses;
        $this->mod_review= new Reviews();
        $this->mod_log=new LogPersonal($request);
    }
    
    /**
    *
    * List all zoonoses in the system
    *   
    * @return \Illuminate\Http\Response
    *
    */
    public function list(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] elenco', $this->mod_log->getParamFrontoffice());
        if(auth()->guard('admin')->user()->role!=='admin'){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->warning('[OUT] elenco', $this->mod_log->getParamFrontoffice('ruolo non ammesso'));
            return redirect('/admin');
        }
        $title_page='Elenco zoonosi';        
        $where=$whereand=$whereor=$wherenot=$wheresame=[];
        $order=[];
        $order['zl.nome']='ASC';
        $zoonosi=$this->mod_zoonoses->getAll([],$order);
        $review=$revfiles=[];
        $review=$this->mod_review->getAllReview()->toArray();
        if(count($review)>0){
            foreach ($review AS $document)
                $revfiles[$document->zid]=$document;
        }
        $zoonosi=$this->mod_zoonoses->getAll([],$order);
        return view('admin.zoonoses.list')->with('zoonosi',$zoonosi)->with('revfiles',$revfiles)
                ->with([
                    'title_page'=>$title_page,
                    'admin'=>auth()->guard('admin')->user(),
                    'menuactive'=>$this->menuactive
                ]);;
    }
    
    /**
    *
    * Add a zoonoses to the system
    *   
    * @return \Illuminate\Http\Response
    *
    */
    public function adding(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] add', $this->mod_log->getParamFrontoffice());
        if(auth()->guard('admin')->user()->role!=='admin'){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[OUT] add', $this->mod_log->getParamFrontoffice('ruolo non ammesso'));
            return redirect('/admin');
        }
        $title_page='Aggiungi zoonosi';
        $datizoo=[];
        
        if($this->request->isMethod('post')){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] add', $this->mod_log->getParamFrontoffice('invio del post'));
            $datizoo=$this->request->all();
            if($this->checkform()){
                DB::beginTransaction();
                try {
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[IN TRY] add', $this->mod_log->getParamFrontoffice());
                    //memorization zoonoses
                    $zoonosi = new Zoonoses;
                    $zoonosi->linktelegram = $datizoo['linktelegram'];
                    $zoonosi->linkraccoltereview = $datizoo['linkraccoltereview'];
                    $zoonosi->save();

                    //memorization parameters
                    $this->mod_zoonoses->setZoonosiLang($datizoo,$zoonosi->zid);
                    
                    DB::commit();
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[OUT TRY] add', $this->mod_log->getParamFrontoffice());
                    $this->request->session()->flash('messageinfo', 'Zoonosi inserita correttamente');
                    return redirect(route('adminListZoonoses'));
                    
                } catch (Throwable $e) {
                    DB::rollBack();
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT] add', $this->mod_log->getParamFrontoffice($e->getMessage()));
                    echo $e->getMessage();
                    exit;
                }
            }else{
                Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT] add', $this->mod_log->getParamFrontoffice('form errato'));
                $this->request->session()->flash('formerrato', '<h5>Dati non corretti</h5>'."".$this->errorsFormSubmission);
            }
        }
   
       
        return view('admin.zoonoses.addmod')->with('datapost',$datizoo)->with('form','adminSaveNewZoonoses')
                ->with([
                    'title_page'=>$title_page,
                    'admin'=>auth()->guard('admin')->user(),
                    'menuactive'=>$this->menuactive,
                ]);;
    }
    
    /**
    *
    * Modify a zoonoses into the system
    *   
    * @return \Illuminate\Http\Response
    *
    */
    public function modify(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] modify', $this->mod_log->getParamFrontoffice());
        if(auth()->guard('admin')->user()->role!=='admin'){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->warning('[OUT] modify', $this->mod_log->getParamFrontoffice('ruolo non ammesso'));
            return redirect('/admin');
        }
        $title_page='Modifica zoonosi';
        $datizoo=[];
        
        if($this->request->isMethod('post')){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] modifica', $this->mod_log->getParamFrontoffice('invio del post'));
            $datizoo=$this->request->all();
            if($this->checkform()){
                //UPDATE ZOONOSI
                DB::beginTransaction();
                try {
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[IN TRY] modify', $this->mod_log->getParamFrontoffice());
                    $zoonosi = Zoonoses::find($datizoo['zid']);
                    $zoonosi->linktelegram = $datizoo['linktelegram'];
                    $zoonosi->linkraccoltereview = $datizoo['linkraccoltereview'];
                    $zoonosi->save();

                    //update parameters
                    $this->mod_zoonoses->setZoonosiLang($datizoo,$zoonosi->zid,1);
                    
                    DB::commit();
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[OUT TRY] modify', $this->mod_log->getParamFrontoffice());
                    $this->request->session()->flash('messageinfo', 'Zoonosi aggiornata correttamente');
                    return redirect(route('adminListZoonoses'));
                } catch (Throwable $e) {
                    DB::rollBack();
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT TRY] modify', $this->mod_log->getParamFrontoffice($e->getMessage()));
                    echo $e->getMessage();
                    exit;
                }
            }else{
                Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT] modify', $this->mod_log->getParamFrontoffice('form errato'));
                $this->request->session()->flash('formerrato', '<h5>Dati non corretti</h5>'."".$this->errorsFormSubmission);
            }
        }else{
            $datizoo=$this->mod_zoonoses->getZoonosi($this->request->zid);
            $datizoo=get_object_vars($datizoo->toArray()[0]);
        }
     
        return view('admin.zoonoses.addmod')->with('datapost',$datizoo)->with('form','adminSaveModifyZoonoses')
                ->with([
                    'title_page'=>$title_page,
                    'admin'=>auth()->guard('admin')->user(),
                    'menuactive'=>$this->menuactive,
                ]);
    }
  
    /**
    *
    * Delete a zoonoses from the system
    *   
    * @return \Illuminate\Http\Response
    *
    */
    public function erase(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] erase', $this->mod_log->getParamFrontoffice());
        if(auth()->guard('admin')->user()->role!=='admin'){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->warning('[OUT] erase', $this->mod_log->getParamFrontoffice('ruolo non ammesso'));
            return redirect('/admin');
        }
        $zoonosi = Zoonoses::find($this->request->zid);
        $zoonosi->delete();
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[OUT] erase', $this->mod_log->getParamFrontoffice());
        $this->request->session()->flash('messageinfo', 'Zoonosi eliminata correttamente');
        return redirect(route('adminListZoonoses'));
    }
    
    /**
    *
    * Check if there is almost a slug for a zoonoses
    *   
    * @return JSON
    *
    */
    public function checkslugzoonosi(){
        $zid=0;
        if(preg_match('/^[1-9][0-9]*$/',$this->request->zid))$zid=$this->request->zid;
        $zoonosi=$this->mod_zoonoses->checkExistSlugzoonosi($this->request->slug,$zid);
        if(count($zoonosi->toArray())>0){
            return response()->json(['error'=>true,'message'=>'Slug già presente nel sistema, modificare il nome della zoonosi']);
        }
        return response()->json(['error'=>false,'message'=>'']);
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
        if(!$request_post['nome'])$datimancanti[]='Nome zoonosi mancante';
        if(!$request_post['descrizione'])$datimancanti[]='Descrizione zoonosi mancante';
        if(!$request_post['slugzoonosi'])$datimancanti[]='Slugzoonosi mancante';
        if(!$request_post['img_url'])$datimancanti[]='Url immagine zoonosi mancante';
        if(!$request_post['img_desc'])$datimancanti[]='Descrizione immagine zoonosi mancante';
      
        $zid=0;
        if(preg_match('/^[1-9][0-9]*$/',$request_post['zid']))$zid=$request_post['zid'];
        $zoonosi=$this->mod_zoonoses->checkExistSlugzoonosi($request_post['slugzoonosi'],$zid);
        if(count($zoonosi->toArray())>0)$datimancanti[]='Slug zoonosi già presente, cambiare il nome della zoonosi';
        $zoonosinome=$this->mod_zoonoses->checkExistNamezoonosi($request_post['nome'],$zid);
        if(count($zoonosinome->toArray())>0)$datimancanti[]='Nome zoonosi già presente, cambiare il nome della zoonosi';
        if(count($datimancanti)>0){
            $this->setVisualErrors($datimancanti);
            return false;
        }
        return true;
    }
    
    /**
    *
    * Prepare the text to be published for errors
    * 
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
