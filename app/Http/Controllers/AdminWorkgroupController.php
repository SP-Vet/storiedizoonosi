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
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use DateTime;
use DB;
use Session;
use App\Models\Admin;
use App\Models\ConfirmAdmin;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\LogPersonal;


/**
 * Manages all workgroup user that can be access the admin panel
 * 
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version Release: 1.0
 * @since   Class available since Release 1.0
 * 
 */
class AdminWorkgroupController extends Controller
{
    public $mod_admin;
    private $request;
    public $menuactive='gruppodilavoro';
    public $errorsFormSubmission='';
    
    public function __construct(Request $request)
    {
        $this->request=$request;
        $this->mod_admin = new Admin();
        $this->mod_log=new LogPersonal($request);
    }
    
    /**
    *
    * Lists all users on the system as a workgroup
    *   
    * @return \Illuminate\Http\Response
    *
    */
    public function list(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] list', $this->mod_log->getParamFrontoffice());
        if(auth()->guard('admin')->user()->role!=='admin'){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->warning('[OUT] list', $this->mod_log->getParamFrontoffice('ruolo non ammesso'));
            return redirect('/admin');
        }
        $title_page='Elenco utenti del gruppo di lavoro';
        $where=$whereand=$whereor=$wherenot=$wheresame=[];
        $order=[];
        $order['a.name']='ASC';
        $utenti=$this->mod_admin->getAll([],$order);
        return view('admin.workgroup.list')->with('gruppo',$utenti)
                ->with([
                    'title_page'=>$title_page,
                    'admin'=>auth()->guard('admin')->user(),
                    'menuactive'=>$this->menuactive,
                ]);
    }
    
    /**
    *
    * Add a user in the system as a workgroup
    *   
    * @return \Illuminate\Http\Response
    *
    */
    public function adding(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] adding', $this->mod_log->getParamFrontoffice());
        if(auth()->guard('admin')->user()->role!=='admin'){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->warning('[OUT] adding', $this->mod_log->getParamFrontoffice('ruolo non ammesso'));
            return redirect('/admin');
        }
        $title_page='Aggiungi nuovo amministratore';
        
        if($this->request->isMethod('post')){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] adding', $this->mod_log->getParamFrontoffice('inviato il post del nuovo utente'));
            $datiutente=$this->request->all();
            if($this->checkform()){
                Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] adding', $this->mod_log->getParamFrontoffice('post corretto'));
                //DB::beginTransaction();

                DB::beginTransaction();
                try {
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[IN TRY] adding', $this->mod_log->getParamFrontoffice());
                    //memo admin
                    $admin=new Admin();
                    $admin->name = trim(htmlentities($datiutente['name'],ENT_QUOTES,'utf-8'));
                    $admin->email = trim(strtolower(htmlentities($datiutente['email'],ENT_QUOTES,'utf-8')));
                    $admin->email_real = trim(strtolower(htmlentities($datiutente['email_real'],ENT_QUOTES,'utf-8')));
                    $admin->role=trim(strtolower(htmlentities($datiutente['ruolo'],ENT_QUOTES,'utf-8')));
                    $admin->reset_password=1;
                    $admin->password=Hash::make(Str::random(12));
                    $admin->password_changed_at=Carbon::now();
                    $admin->save();
                    
                    $confirm=new ConfirmAdmin();
                    $linkreset=$confirm->getEmailResetLink($admin->id,$admin->email);
                    $linkreset_clean= str_replace('//', 'https://', $confirm->getEmailResetLink($admin->id,$admin->email));
                    //sending email with reset password link
                    $datimail=array('linkreset' => $linkreset,'linkreset_clean'=>$linkreset_clean,'email'=>$admin->email,'email_real'=>$admin->email_real,'nome_sito'=>config('app.name'));
                    $this->email_admin=$admin->email_real;
                    Mail::send('emails.newadmin', $datimail, function($message){
                        $message->subject('Nuovo account amministratore');
                        $message->to($this->email_admin);
                    });
                    unset($this->email_admin);

                    DB::commit();
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[OUT TRY] adding', $this->mod_log->getParamFrontoffice());
                    $this->request->session()->flash('messageinfo', '<h2>Amministratore aggiunto con successo!</h2>');   
                    return redirect(route('adminListWorkgroup'));
                } catch (Throwable $e) {
                    DB::rollBack();
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT TRY] adding', $this->mod_log->getParamFrontoffice($e->getMessage()));
                    return back()->with('messagedanger','Errore durante l\'inserimento del nuovo amministratore, riprovare!');
                }
            }else{
                Log::build(['driver' => 'single','path' => storage_path('logs/frobacknt.log')])->error('[OUT] adding', $this->mod_log->getParamFrontoffice('form errato'));
                $this->request->session()->flash('messagedanger', '<h2>Dati non corretti</h2>'."<br />".$this->errorsFormSubmission);
                return back();
            }
        }

        return view('admin.workgroup.addmod')->with('form','adminSaveNewUser')
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
        if(!$request_post['name'])$datimancanti[]='Nome amministratore mancante';
        if(!$request_post['email'])$datimancanti[]='Email di accesso mancante';
        if(!$request_post['email_real'])$datimancanti[]='Email di notifica mancante';
        if(!$request_post['ruolo'])$datimancanti[]='Ruolo mancante';
        if($request_post['email']!=''){
            $email=$this->mod_admin->checkExistAdminEmailAddress($request_post['email'].'@sdz.it');
            if(count($email->toArray())>0)$datimancanti[]='Email di accesso già esistente';
        }
        if($request_post['email_real']!=''){
            $email_real=$this->mod_admin->checkExistAdminRealEmailAddress($request_post['email_real']);
            if(count($email_real->toArray())>0)$datimancanti[]='Email di notifica già esistente';
        }

        if(count($datimancanti)>0){
            $this->setVisualErrors($datimancanti);
            return false;
        }
        
        return true;
    }



    /**
    *
    * Reset password of an admin
    * @param Integer $a id of dthe admin
    * @param String $b email of the admin
    * @param String $c real email of the admin  
    * 
    * @return \Illuminate\Http\Response
    *
    */
    public function resetpassword($a,$b,$c){
        $email_reale=$c;
        $email=$b;
        $idadmin=$a;
        $admin=new \Illuminate\Support\Collection();
        $admin=$this->mod_admin->getAll(['a.id'=>$idadmin,'a.email'=>$email,'a.email_real'=>$email_reale])->first();
        if(!isset($admin))redirect('/admin');

        $adm = Admin::find($admin->id);
        $adm->reset_password=1;
        $adm->save();

        $confirm=new ConfirmAdmin();
        $linkreset=$confirm->getEmailResetLink($admin->id,$admin->email);
        $linkreset_clean= str_replace('//', 'https://', $confirm->getEmailResetLink($admin->id,$admin->email));

        //sending email with reset passwrod link
        $datimail=array('linkreset' => $linkreset,'linkreset_clean'=>$linkreset_clean,'email'=>$admin->email,'nome_sito'=>config('app.name'));
        $this->email_admin=$admin->email_real;
        Mail::send('emails.resetpasswordadmin', $datimail, function($message){
            $message->subject('Reimposta password');
            $message->to($this->email_admin);
        });
        unset($this->email_admin);
        $this->request->session()->flash('messageinfo', '<h2>Link reset password inviato con successo!</h2><h3>L\'amministratore riceverà un mail con il link per reimpostare la password.</h3>');   
        return redirect(route('adminListWorkgroup'));
    }

    /**
    *
    * Insert new password of an admin
    *   
    * @return \Illuminate\Http\Response
    *
    */
    public function checkResetPassword($first,$second,$third){
        $mod_confirm=new ConfirmAdmin();
        if($this->request->isMethod('post')){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[IN POST] checkResetPassword', $this->mod_log->getParamFrontoffice());
            if(!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%-*_£()])[0-9A-Za-z!@#$%-*_£()]{8,}$/', trim($this->request->get('password')))){
                //Not valid password
                return redirect()->back()->with("error","La nuova password non rispetta il formato minimo di sicurezza. Inserire almeno 8 caratteri, numeri, maiuscole, minuscole e caratteri speciali ( !@#$%-*_£() ).");
            }
            if(strcmp(trim($this->request->get('password')), trim($this->request->get('ripetipassword'))) != 0){
                //The passwords entered do not match
                return redirect()->back()->with("error","Le nuove password inserite non coincidono. Riprovare.");
            }
            if(Session::get('second')!=$second)return redirect('/admin/login');

            $admin=Admin::find($second);
            if($admin->reset_password==0)return redirect(route('adminLogin'));
            DB::beginTransaction();
            try {
                Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[IN TRY] checkResetPassword', $this->mod_log->getParamFrontoffice());
                //$admin=Admin::find($second);
                $admin->reset_password=0;
                $admin->password=Hash::make($this->request->password);
                $admin->password_changed_at=Carbon::now();
                $admin->save();
                DB::commit();
                Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[OUT TRY] checkResetPassword', $this->mod_log->getParamFrontoffice());
                \Session::flush();
                $this->request->session()->flash('messageinfo', 'Password modificata correttamente. Effettua il login');
                return redirect(route('adminLogin'));
            } catch (Throwable $e) {
                DB::rollBack();
                Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT TRY] checkResetPassword', $this->mod_log->getParamFrontoffice($e->getMessage()));
                $this->request->session()->flash('messagedanger', 'Errore interno al sistema, password NON modificata');
            }
        }else{
            if(!$mod_confirm->checkResetPassword($first,$second,$third))redirect('/admin/login');
            else{
                Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[IN] checkResetPassword', $this->mod_log->getParamFrontoffice());
                $admin=Admin::find($second);
                Session::put('first', $first);
                Session::put('second', $second);
                Session::put('third', $third);
            }
        }

        return view('admin.resetpassword')->with('admin',$admin)->with('first',$first)->with('second',$second)->with('third',$third)
            ->with([
                //'title_page'=>$title_page,
                'admin'=>auth()->guard('admin')->user(),
                'menuactive'=>$this->menuactive,
            ]);
    }

    /**
    *
    * Check the uniqueness of the admin log in email address
    * @return JSON
    *
    */
    public function checkemailadminexists(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] checkemailadminexists', $this->mod_log->getParamFrontoffice());
        $email=$this->mod_admin->checkExistAdminEmailAddress($this->request->email.'@sdz.it');
        if(count($email->toArray())>0){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[OUT] checkemailadminexists', $this->mod_log->getParamFrontoffice('email di accesso già presente'));
            return response()->json(['error'=>true,'message'=>'Indirizzo email di accesso già presente nel sistema, modificare l\'indirizzo']);
        }
        return response()->json(['error'=>false,'message'=>'']);
    }

    /**
    *
    * Check the uniqueness of the admin real email address
    * @return JSON
    *
    */
    public function checkemailrealadminexists(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] checkemailrealadminexists', $this->mod_log->getParamFrontoffice());
        $email=$this->mod_admin->checkExistAdminRealEmailAddress($this->request->email);
        if(count($email->toArray())>0){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[OUT] checkemailrealadminexists', $this->mod_log->getParamFrontoffice('email di accesso già presente'));
            return response()->json(['error'=>true,'message'=>'Indirizzo email di accesso già presente nel sistema, modificare l\'indirizzo']);
        }
        return response()->json(['error'=>false,'message'=>'']);
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
