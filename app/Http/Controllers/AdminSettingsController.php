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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Auth\SessionGuard;
use App\Models\Admin;
use App\Models\Settings;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\LogPersonal;

use DateTime;
use DB;
use Carbon\Carbon;

/**
 * Manages all configuration variables
 * 
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version Release: 1.0
 * @since   Class available since Release 1.0
 * 
 */
class AdminSettingsController extends Controller
{
    public $mod_conf;
    private $request;
    public $menuactive='settings';
    public $errorsFormSubmission='';
    
    public function __construct(Request $request)
    {
        $this->request=$request;
        $this->mod_conf = new Settings();
        $this->mod_log=new LogPersonal($request);
    }
    
    /**
    *
    * List all configurations
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
        $title_page='Elenco configurazioni';
        $where=$whereand=$whereor=$wherenot=$wheresame=[];
        $conf=$this->mod_conf->getAll();
        return view('admin.settings.list')->with('configurations',$conf)
                ->with([
                    'title_page'=>$title_page,
                    'admin'=>auth()->guard('admin')->user(),
                    'menuactive'=>$this->menuactive,
                ]);
    }


    /**
    *
    * Manage the editing of a setting
    * @return \Illuminate\Http\Response
    *
    */
    public function modify(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] modify', $this->mod_log->getParamFrontoffice());
        $title_page='Modifica Impostazione';
        if(auth()->guard('admin')->user()->role!=='admin')return redirect('/admin');
       
        if($this->request->isMethod('post')){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] modify', $this->mod_log->getParamFrontoffice('inviato il post della configurazione'));
            $daticonf=$this->request->all();
            if($this->checkform()){
                Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] modify', $this->mod_log->getParamFrontoffice('post corretto'));
                DB::beginTransaction();
                try {
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[IN TRY] modify', $this->mod_log->getParamFrontoffice());
                    //memo setting      
                    $setting = Settings::find($daticonf['confid']);
                    $oldsetting=$this->mod_conf->getConfFromID($daticonf['confid'])->toArray();
                    $oldsetting=$oldsetting[0];
                    if($daticonf['typeconf']==4){
                         /*START CHECK IMAGES*/
                        if(isset($_FILES['valueconfig']) && $_FILES['valueconfig']['name']!=''){
                            $destinationPath = 'images';
                            $myimage = $this->request->file('valueconfig')->getClientOriginalName();
                            $this->request->file('valueconfig')->move(public_path($destinationPath), $myimage);
                            $setting->valueconfig = $myimage;
                        }else
                            $setting->valueconfig =  $this->dataready($daticonf['oldvalueconfig']);
                        /*END CHECK IMAGES*/
                    }else
                        $setting->valueconfig =  $this->dataready($daticonf['valueconfig']);
                    
                    $setting->datamodified = 'NOW()';
                    $setting->save();
                    
                    DB::commit();
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[OUT TRY] modify', $this->mod_log->getParamFrontoffice());
                    $this->request->session()->flash('messageinfo', '<h2>Impostazione aggiornata con successo!</h2>');   
                    return redirect('/admin/elencoimpostazioni');
                } catch (Throwable $e) {
                    DB::rollBack();
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT TRY] modify', $this->mod_log->getParamFrontoffice($e->getMessage()));
                    $this->request->session()->flash('formerrato', '<h5>Dati non corretti - RICONTROLLARE TUTTI I CAMPI INSERITI</h5>'."".$this->errorsFormSubmission);                    
                }
            }else{
                Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT] modify', $this->mod_log->getParamFrontoffice('form errato'));
                $this->request->session()->flash('formerrato', '<h5>Dati non corretti - RICONTROLLARE TUTTI I CAMPI INSERITI</h5>'."".$this->errorsFormSubmission);
            }            
        }

       
        $configuration=new \Illuminate\Support\Collection();
        $confid=$this->request->confid;  
        $configuration=$this->mod_conf->getConfFromID($confid)[0];        
        return view('admin.settings.mod')->with('configuration',$configuration)->with('confid',$confid)->with('form','adminSaveManagementSetting')
                ->with([
                    'title_page'=>$title_page,
                    'admin'=>auth()->guard('admin')->user(),
                    'menuactive'=>$this->menuactive,
                ]);
    }

    /**
    *
    * Method for checking validation data of the modify form
    * @return BOOL
    *
    */
    private function checkform(){
        $request_post=$this->request->all();
        $allowed_image = array('jpg','png', 'gif', 'svg');
   
        //check for missing required data
        $datimancanti=[];
        if(!$request_post['nameconfig'])$datimancanti[]='Nome della configurazione mancante';
        if(!preg_match('/[0-9]+/', $request_post['typeconf']))$datimancanti[]='Tipo configurazione non valido';
        if(!$request_post['confid'])$datimancanti[]='ID configurazione mancante';
        if(!preg_match('/[1-9]+/', $request_post['confid']))$datimancanti[]='ID configurazione non valido';
   
        //checks on img files 
        if(isset($_FILES['valueconfig'])){
            if($_FILES['valueconfig']['name']!=''){
                if($_FILES['valueconfig']['error']!=0)$datimancanti[]='Errore di carimento per il fil <<'.$_FILES['valueconfig']['name'].'>>';
                $ext_img = pathinfo($_FILES['valueconfig']['name'], PATHINFO_EXTENSION);
                if (!in_array($ext_img, $allowed_image)) {$datimancanti[]='Formato del file immagine non valido';}
                if($_FILES['valueconfig']['size']>4718592)$datimancanti[]='File immagine troppo grande <<'.$_FILES['valueconfig']['name'].'>>';
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
    * Prepare the text to be published for errors
    * 
    * @param Array $arrayErr array with the list of form errors
    * @return BOOL
    *
    */
    private function setVisualErrors($arrayErr){
        foreach ($arrayErr AS $key=>$textErrore)
            $this->errorsFormSubmission.='<b>'.$textErrore.'</b><br />';
        unset($arrayErr);
        return true;
    }
    
    /**
    *
    * Prepare the data for storage
    * 
    * @param $data string value to set
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
