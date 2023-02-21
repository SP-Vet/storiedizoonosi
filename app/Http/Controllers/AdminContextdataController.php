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
use App\Models\Contextdata;
use DateTime;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\LogPersonal;

/**
 * Manages the platform's context data
 * 
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version Release: 1.0
 * @since   Class available since Release 1.0
 * 
 */
class AdminContextdataController extends Controller
{
    public $mod_stories;
    public $mod_contextdata;
    private $request;
    public $menuactive='storie';
    public $errorsFormSubmission='';
    
    public function __construct(Request $request)
    {
        $this->request=$request;
        $this->mod_stories = new Stories();
        $this->mod_contextdata=new Contextdata();
        $this->mod_log=new LogPersonal($request);
    }
    
    /**
    * Context data management of a story
    * @return \Illuminate\Http\Response
    *
    */
    public function contextdatastory(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] contextdatastory', $this->mod_log->getParamFrontoffice());
        if(auth()->guard('admin')->user()->role!=='admin'){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->warning('[OUT] contextdatastory', $this->mod_log->getParamFrontoffice('ruolo non ammesso'));
            return redirect('/admin');
        }
        $title_page='Dati contesto storia';
        //if POST insert/update/delete data
        if($this->request->isMethod('post')){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] contextdatastory', $this->mod_log->getParamFrontoffice('invio post dati di contesto')); 
            if($this->checkform()){
                $request_post=$this->request->all();                
                DB::beginTransaction();
                try {
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[IN TRY] contextdatastory', $this->mod_log->getParamFrontoffice());
                    //memo base data
                    $ordine=1;
                    $elencodbid=[];
                    $elencoPOSTdbid=[];//contains numeric and non-numeric keys before and after storing the context data
                    foreach ($request_post['dbid'] AS $ks=>$dbid){
                        $datidbid=[];
                        if(!is_numeric($dbid)){
                            //insert storiafase
                            $daticontesto=new Contextdata();
                            $daticontesto->sid=$this->request->sid;
                            $daticontesto->ordine=$ordine;
                            $daticontesto->save();
                            $iddbid=$daticontesto->dbid;
                        }else{
                            $daticontesto = Contextdata::find($dbid);
                            $daticontesto->ordine = $ordine;
                            $daticontesto->save();
                            $iddbid=$dbid;
                        }

                        //update-insert context data questions / answers
                        $elencodbid[]=$elencoPOSTdbid[$dbid]=$iddbid;
                        $datidbid['domanda']=$this->dataready($request_post['domanda'][$ks]);
                        $datidbid['risposta']=$this->dataready($request_post['risposta'][$ks]);
                        $this->mod_contextdata->setContextdatalanguageAss($iddbid,$datidbid);
                        $ordine++;
                    }
                    //delete all dbid not in insert and update
                    Contextdata::whereNotIn('dbid',$elencodbid)->where('sid',$this->request->sid)->delete();
                    unset($elencobdid);
                    unset($ordine);
                    unset($dbid);
                    unset($iddbid);
                    
                    DB::commit();
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[OUT TRY] contextdatastory', $this->mod_log->getParamFrontoffice());
                    $this->request->session()->flash('messageinfo', '<h2>Dati di contesto aggiornati con successo!</h2>');   
                    return redirect('/admin/elencostorie');
                }catch (Throwable $e) {
                    DB::rollBack();
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT TRY] contextdatastory', $this->mod_log->getParamFrontoffice($e->getMessage()));
                    echo $e->getMessage();
                    exit;
                }
            }else{
                Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT] contextdatastory', $this->mod_log->getParamFrontoffice('parametri post non validi'));
                $this->request->session()->flash('formerrato', '<h5>Dati non corretti - RICONTROLLARE TUTTI I CAMPI INSERITI</h5>'."".$this->errorsFormSubmission);
            }
        }
        
        $daticontesto=new \Illuminate\Support\Collection();
        $daticontesto=$this->mod_contextdata->getContextdataFromStory($this->request->sid);
        return view('admin.contextdata.addmod')->with('daticontesto',$daticontesto)->with('form','adminSaveContextDataStory')
                ->with('sid',$this->request->sid)
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
        if(!$request_post['sid'])$datimancanti[]='Storia non selezionata';
        //check context data values
        if(!is_array($request_post['dbid']) || count($request_post['dbid'])==0)$datimancanti[]='Inserire almeno un dato di contesto per storia';
        if(!is_array($request_post['domanda']) || count($request_post['domanda'])==0)$datimancanti[]='Inserire i titoli delle dei dati di contesto della storia';
        if(!is_array($request_post['risposta']) || count($request_post['risposta'])==0)$datimancanti[]='Inserire le descrizion dei dati di contesto della storia';
        else{
            foreach ($request_post['domanda'] AS $tf=>$domanda){
                if(!$domanda)$datimancanti[]='Titolo mancante nel Dato '.($tf+1).' di contesto della storia';
            }
            foreach ($request_post['risposta'] AS $ttf=>$risposta){
                if(!$risposta)$datimancanti[]='Descrizione mancante nel Dato '.($ttf+1).' di contesto della storia';
            }
        }
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
