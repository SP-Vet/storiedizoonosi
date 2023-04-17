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
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Redirector;
use App\Models\User;
use App\Models\Confirm;
use App\Models\Privacy;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\LogPersonal;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\Settings;
use DB;

/**
 * Manage all functions of a user's personal data  
 * 
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version Release: 1.0
 * @since   Class available since Release 1.0
 * 
 */
class UsersController extends Controller
{
    public $mod_user;
    public $mod_settings;
    public $formRegistrationError='';
    private $request;
    public $tmpmail='';
    
    public function __construct(Request $request)
    {
        $this->request=$request;
        $this->mod_user = new User();
        $this->mod_confirm = new Confirm();
        $this->mod_privacy = new Privacy();
        $this->mod_settings = new Settings();
        $this->mod_log = new LogPersonal($request);
    }
    
    /**
    *
    * Login user page
    *   
    * @return \Illuminate\Http\Response
    *
    */
    public function login(){
        Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] login', $this->mod_log->getParamFrontoffice());
        
        $settings=[];
        $settings=array_column($this->mod_settings->getAll([['c.groupsection','0']])->toArray(),NULL,'nameconfig');
        return view('login')->with('settings',$settings);
    }
    
    /**
    *
    * Logout method
    *   
    * @return \Illuminate\Http\Response
    *
    */
    public function logout(){
        Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] logout', $this->mod_log->getParamFrontoffice());
        $this->request->session()->flush();
        Auth::logout();
        return redirect('/');
    }
    
    /**
    *
    * Check credential's login validity of a user 
    *   
    * @return \Illuminate\Http\Response
    *
    */
    public function checklogin(Request $request){
        Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] checklogin', $this->mod_log->getParamFrontoffice());
        $responseMTCaptcha = Http::get('https://service.mtcaptcha.com/mtcv1/api/checktoken?privatekey='.config('app.MTCAPTCHAprivate').'&token='.$request->input('mtcaptcha-verifiedtoken'));
        $dataRresponse=$responseMTCaptcha->json();
        
        if($dataRresponse['success']){
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            if (Auth::attempt($credentials)) {
                if (Auth::user()->email_verified_at != '') {
                    $uid=Auth::user()->id;
                    Auth::logout();
                    /*
                    * Check if privacy policy has benn accepted by the user or needs a new acceptance
                    *
                    * User will be redirected to new privacy policy acceptance if:
                    * 1 - there's no privacy accepted record
                    * 2 - privacy accepted != actual privacy
                    * 3 - actual privacy have reflag && data_publication privacy > user's data_acceptance privacy
                    */
                    //get privacy accepted by the user
                    $current_privacy=$this->mod_privacy->getCurrentPrivacy();
                    $privacy_accepted_user=$this->mod_privacy->getLastAcceptedPrivacyFromUser($uid);
                    
                    //testare funzionamento
                    if(!isset($privacy_accepted_user->ppid) || 
                        (isset($privacy_accepted_user->ppid) && $privacy_accepted_user->ppid!=$current_privacy->ppid) ||
                        (isset($privacy_accepted_user->ppid) && $current_privacy->reflag==1 && (Carbon::createFromFormat('Y-m-d H:i:s', $current_privacy->data_pubblicazione) > Carbon::createFromFormat('Y-m-d H:i:s', $privacy_accepted_user->data_accettazione_visione))))
                    {
                        //redirect to privacy acceptance
                        return redirect()->route('showPrivacy', ['uid' => $uid]);
                       
                    }

                    Auth::attempt($credentials);
                    $request->session()->regenerate();
                    Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[OUT] checklogin', $this->mod_log->getParamFrontoffice());
                    return redirect('/');
                }else{
                    Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->warning('[OUT] checklogin', $this->mod_log->getParamFrontoffice('email non verificata'));
                    $this->request->session()->flash('messagedanger', '<h2>Email non ancora confermata. Controlla la tua casella email.</h2>');
                }
            }else{
                Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->warning('[OUT] checklogin', $this->mod_log->getParamFrontoffice('credenziali non valide'));
                $this->request->session()->flash('messagedanger', '<h2>Email o password errati. Riprovare.</h2>');
            }
        }else{
            Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->error('[OUT] checklogin', $this->mod_log->getParamFrontoffice('captcha non validato'));
            return back()->with('error','Captcha non validato correttamente');
        }
        return back();
    }
    
    /**
    *
    * New user registration form and store procedure
    * @return \Illuminate\Http\Response
    *
    */
    public function registration(){
        Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] registration', $this->mod_log->getParamFrontoffice());
        $title_page='Registrazione nuovo utente';
        $datireg=[];
        if($this->request->isMethod('post')){
            Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] registration', $this->mod_log->getParamFrontoffice('invio del post'));
            $datireg=$this->request->all();      
            $responseMTCaptcha = Http::get('https://service.mtcaptcha.com/mtcv1/api/checktoken?privatekey='.config('app.MTCAPTCHAprivate').'&token='.$this->request->input('mtcaptcha-verifiedtoken'));
            $dataRresponse=$responseMTCaptcha->json();
            if($dataRresponse['success']){
                if($this->checkRegistrationform()){
                    Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] registration', $this->mod_log->getParamFrontoffice('check post valido'));
                    $request_post=$this->request->all();
                    //check the existence of emails
                    if(!$this->checkExistMail($request_post['email'])){
                        Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] registration', $this->mod_log->getParamFrontoffice('email non esistente'));
                        //check the existence of the tax code (if entered)
                        $codfis_esistente=0;
                        if($request_post['codfis']!='' ){
                            if($this->checkExistCodfis($request_post['codfis']))
                                $codfis_esistente=1;
                        }
                        if($codfis_esistente==0){
                            DB::beginTransaction();
                            try {
                                Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->critical('[IN TRY] registration', $this->mod_log->getParamFrontoffice());
                                //user storage NOT verified
                                $user = new User;
                                $user->name = $request_post['nome'].' '.$request_post['cognome'];
                                $user->email =$this->tmpmail= $request_post['email'];
                                //$user->password=bcrypt($request_post['password']);
                                $user->password=Hash::make($request_post['password']);
                                //$user->role='member';
                                $user->save();

                                //tax code set if existing
                                if($request_post['codfis']!='')
                                    $this->mod_user->setUserTaxCode($user->id,$request_post['codfis']);

                                //set privacy policy acknowledged
                                $this->mod_privacy->setAccept($user->id,0);

                                //confirmation link
                                $link_conferma=$this->mod_confirm->getEmailConfirmationLink($user->id,$user->email);
                                //$link_conferma_clean= str_replace('//', 'https://', $this->mod_confirm->getEmailConfirmationLink($user->id,$user->email));
				$link_conferma_clean=$link_conferma;

                                //sending email with confirmation link
                                $datimail=array('link_conferma' => $link_conferma,'link_conferma_clean'=>$link_conferma_clean,'email'=>$user->email,'nome_sito'=>env('NOME_SITO'));
                                Mail::send('emails.verifyemail', $datimail, function($message){
                                    $message->subject('Conferma la tua registrazione');
                                    $message->to($this->tmpmail);
                                });
                                //Temporary record set of email activation request time
                                $this->mod_user->setTMPVerifiedDate($user->id);
                                DB::commit();
                                Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->critical('[OUT TRY] registration', $this->mod_log->getParamFrontoffice());
                                return view('registrationemailsent');
                            } catch (Throwable $e) {
                                DB::rollBack();
                                Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->error('[OUT TRY] registration', $this->mod_log->getParamFrontoffice($e->getMessage()));
                                echo $e->getMessage();
                                exit;
                            }
                        }else{
                            Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->error('[OUT] registration', $this->mod_log->getParamFrontoffice('codice fiscale già esistente'));
                            //tax code already present
                            $this->request->session()->flash('formerrato', '<h2>Codice Fiscale gi&agrave; presente nel sistema. Per ulteriori informazioni contattaci via email.</h2>'); 
                        }
                    }else{
                        Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->error('[OUT] registration', $this->mod_log->getParamFrontoffice('email già presente nel sistema'));
                        $this->request->session()->flash('formerrato', '<h2>Email gi&agrave; presente nel sistema. Se non ricordi la password prova a recuperarla tramite l&apos;apposito servizio.</h2>'); 
                    }
                }else{
                    Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->error('[OUT] registration', $this->mod_log->getParamFrontoffice('dati non corretti'));
                    $this->request->session()->flash('formerrato', '<h2>Dati non corretti</h2>'."<br />".$this->formRegistrationError);
                }
            }else{
                Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->error('[OUT] registrazione', $this->mod_log->getParamFrontoffice('captcha non validato'));
                $this->request->session()->flash('formerrato', '<h2>Captcha non validato correttamente.</h2>'); 
            }            
        }
        $privacy_policy=$this->mod_privacy->getCurrentPrivacy();
        
        $settings=[];
        $settings=array_column($this->mod_settings->getAll([],[0,5])->toArray(),NULL,'nameconfig');
        return view('registration')->with('datapost',$datireg)->with('privacy_policy',$privacy_policy)->with('title_page',$title_page)->with('settings',$settings)
                ->with('og_url',$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])
                ->with('og_title','Registrazione Utente')
                ->with('art_title','Registrazione Utente')
                ->with('og_description','Registrazione di un nuovo utente per il portale delle storie di zoonosi')
                ->with('art_description','Registrazione di un nuovo utente per il portale delle storie di zoonosi');
    }
    
    /**
    *
    * Method of checking the validity of the registration form data
    * @return BOOL
    *
    */
    private function checkRegistrationform(){
        Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] checkRegistrationform', $this->mod_log->getParamFrontoffice());
        $request_post=$this->request->all();
        //check for missing required data
        $datimancanti=[];
        if(!$request_post['nome'])$datimancanti[]='Nome mancante';
        if(!$request_post['cognome'])$datimancanti[]='Cognome mancante';
        if(!$request_post['email'])$datimancanti[]='Email mancante';
        if(!$request_post['ripetiemail'])$datimancanti[]='Ripeti Email mancante';
        if(!$request_post['password'])$datimancanti[]='Password mancante';
        if(!$request_post['ripetipassword'])$datimancanti[]='Ripeti Password mancante';
        if((isset($request_post['privacypolicy']) && $request_post['privacypolicy']!=1) || !isset($request_post['privacypolicy']))$datimancanti[]='Accetta le condizioni sulla Privacy';
        if(count($datimancanti)>0){
            $this->setVisualErrors($datimancanti);
            return false;
        }
        
        //data integrity check
        $datiintegri=[];
        $email = filter_var($request_post['email'], FILTER_SANITIZE_EMAIL);
        $ripetiemail = filter_var($request_post['ripetiemail'], FILTER_SANITIZE_EMAIL);
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))$datiintegri[]='Email non valida';
        if(!filter_var($ripetiemail, FILTER_VALIDATE_EMAIL))$datiintegri[]='Ripeti Email non valida';
        if($email!==$ripetiemail)$datiintegri[]='Le email inserite non coincidono';
        //if(!preg_match("/^[a-zA-Z@.-_0-9]*$/",$this->clearData($request_post['email'])))$datimancanti[]='Email non corretta';
        if(!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%-*_£()])[0-9A-Za-z!@#$%-*_£()]{8,}$/', trim($request_post['password'])))$datiintegri[]='La password non rispetta gli standard di sicurezza';
        if(trim($request_post['password'])!==trim($request_post['ripetipassword']))$datiintegri[]='Le password fornite non coincidono';
        if(count($datiintegri)>0){
            $this->setVisualErrors($datiintegri);
            return false;
        }
        
        $daticodfis=[];
        if($request_post['codfis']!=''){
            if(!preg_match('/[A-Z0-9]+/', trim(strtoupper($request_post['codfis']))))$daticodfis[]='Codice fiscale non valido';
        }
        if(count($daticodfis)>0){
            $this->setVisualErrors($daticodfis);
            Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->error('[OUT] checkRegistrationform', $this->mod_log->getParamFrontoffice('paramentri non corretti'));
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
            $this->formRegistrationError.='<b>'.$textErrore.'</b><br />';
        }
        unset($arrayErr);
        return true;
    }
    
    /**
    *
    * Check the exists of an email address
    * 
    * @param String $check_email email address
    * @return BOOL
    *
    */
    private function checkExistMail($check_email){
        $email = filter_var($check_email, FILTER_SANITIZE_EMAIL);
        $user=[];
        $user=$this->mod_user->getUserFromEmail($email)->toArray();
        if(count($user)==0)
            return false;
        return true;
    }
    
    /**
    *
    * Check the exists of an Tax ID code
    * 
    * @param String $check_codfis Tax ID code
    * @return BOOL
    *
    */
    private function checkExistCodfis($check_codfis){
        $codfis = trim(stripslashes(htmlspecialchars(strtoupper($check_codfis))));
        $user=[];
        $user=$this->mod_user->getUserFromCodfis($codfis)->toArray();
        if(count($user)==0)
            return false;
        return true;
    }
    
    /**
    *
    * Method for checking and verifying registration emails
    * @return \Illuminate\Http\Response
    *
    */
    public function checkconfirmationemail(){
        Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] checkconfirmationemail', $this->mod_log->getParamFrontoffice());
        if($this->mod_confirm->checkConfirmationEmail($this->request->first,$this->request->second,$this->request->third)){
            // check url http://127.0.0.1:8000/confermamail/d889d75e8e50459912041e6d028369de465eb28027207606bba97b6574290b3253746c32/34/MLgu9anq2rX9Rpam
            $user = User::where('id', $this->request->second)->first();
            $user->markEmailAsVerified();
            $this->request->session()->flash('messageinfo', '<h2>Email verificata con successo. Benvenuto.</h2>');   
            
            //record cancellation request email confirmation
            $this->mod_user->deleteTMPDateverified($user->id);
            Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[OUT] checkconfirmationemail', $this->mod_log->getParamFrontoffice('email confermata'));
        }else{
            Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->error('[OUT] checkconfirmationemail', $this->mod_log->getParamFrontoffice('errore durante la conferma dell\'indirizzo email'));
        }
        return redirect('/');
    }
}
