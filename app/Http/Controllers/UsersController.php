<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Redirector;
use App\Models\User;
use App\Models\Conferma;
use App\Models\Privacy;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\LogPersonal;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use DB;

class UsersController extends Controller
{
    public $mod_user;
    public $erroriFormRegistrazione='';
    private $request;
    public $tmpmail='';
    
    public function __construct(Request $request)
    {
        $this->request=$request;
        $this->mod_user = new User;
        $this->mod_conferma = new Conferma;
        $this->mod_privacy = new Privacy;
        $this->mod_log = new LogPersonal($request);
    }
    
    /**
    *
    * Pagina di login utente
    *   
    * @return view
    *
    */
    public function login(){
        Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] login', $this->mod_log->getParamFrontoffice());
        return view('login');
    }
    
    public function logout(){
        Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] logout', $this->mod_log->getParamFrontoffice());
        $this->request->session()->flush();
        Auth::logout();
        return redirect('/');
    }
    
    public function checklogin(Request $request){
        Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] checklogin', $this->mod_log->getParamFrontoffice());
        $responseMTCaptcha = Http::get('https://service.mtcaptcha.com/mtcv1/api/checktoken?privatekey='.env('MTCAPTCHA_PRIVATE').'&token='.$request->input('mtcaptcha-verifiedtoken'));
        $dataRresponse=$responseMTCaptcha->json();
        
        if($dataRresponse['success']){
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            if (Auth::attempt($credentials)) {
                if (Auth::user()->email_verified_at != '') {
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
    * Form di registrazione nuovo utente
    * @return view
    *
    */
    public function registrazione(){
        Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] registrazione', $this->mod_log->getParamFrontoffice());
        $title_page='Registrazione nuovo utente';
        $datireg=[];
        //$link_conferma=$this->mod_conferma->getLinkConfermaEmail(34,'e.rivosecchi@izsum.it');
        if($this->request->isMethod('post')){
            Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] registrazione', $this->mod_log->getParamFrontoffice('invio del post'));
            $datireg=$this->request->all();      
            $responseMTCaptcha = Http::get('https://service.mtcaptcha.com/mtcv1/api/checktoken?privatekey='.env('MTCAPTCHA_PRIVATE').'&token='.$request->input('mtcaptcha-verifiedtoken'));
            $dataRresponse=$responseMTCaptcha->json();
            if($dataRresponse['success']){
                //echo '<pre>';print_r($datireg);exit;
                if($this->checkRegistrationform()){
                    Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] registrazione', $this->mod_log->getParamFrontoffice('check post valido'));
                    $request_post=$this->request->all();
                    //controlli esistenza email
                    if(!$this->checkExistMail($request_post['email'])){
                        Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] registrazione', $this->mod_log->getParamFrontoffice('email non esistente'));
                        //controlli esistenza codice fiscale (se inserito)
                        $codfis_esistente=0;
                        if($request_post['codfis']!='' ){
                            if($this->checkExistCodfis($request_post['codfis']))
                                $codfis_esistente=1;
                        }
                        if($codfis_esistente==0){
                            DB::beginTransaction();
                            try {
                                Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->critical('[IN TRY] registrazione', $this->mod_log->getParamFrontoffice());
                                //memorizzazione utente NON verificato
                                $user = new User;
                                $user->name = $request_post['nome'].' '.$request_post['cognome'];
                                $user->email =$this->tmpmail= $request_post['email'];
                                //$user->password=bcrypt($request_post['password']);
                                $user->password=Hash::make($request_post['password']);
                                $user->role='member';
                                $user->save();

                                //set codice fiscale se esistente
                                if($request_post['codfis']!='')
                                    $this->mod_user->setCodiceFiscaleUtente($user->id,$request_post['codfis']);

                                //set privacy policy presa visione
                                $this->mod_privacy->setAccettazione($user->id,0);

                                //link di conferma
                                $link_conferma=$this->mod_conferma->getLinkConfermaEmail($user->id,$user->email);
                                $link_conferma_clean= str_replace('//', 'http://', $this->mod_conferma->getLinkConfermaEmail($user->id,$user->email));

                                //invio email con link di conferma
                                $datimail=array('link_conferma' => $link_conferma,'link_conferma_clean'=>$link_conferma_clean,'email'=>$user->email,'nome_sito'=>env('NOME_SITO'));
                                Mail::send('emails.verifyemail', $datimail, function($message){
                                    $message->subject('Conferma la tua registrazione');
                                    $message->to($this->tmpmail);
                                });
                                //set record temporaneo del tempo di richiesta di attivazione email
                                $this->mod_user->setTMPVerifiedDate($user->id);
                                DB::commit();
                                Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->critical('[OUT TRY] registrazione', $this->mod_log->getParamFrontoffice());
                                return view('emailregistrazioneinviata');
                            } catch (Throwable $e) {
                                DB::rollBack();
                                Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->error('[OUT TRY] registrazione', $this->mod_log->getParamFrontoffice($e->getMessage()));
                                echo $e->getMessage();
                                exit;
                            }
                        }else{
                            Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->error('[OUT] registrazione', $this->mod_log->getParamFrontoffice('codice fiscale già esistente'));
                            //codice fiscale già presente
                            $this->request->session()->flash('formerrato', '<h2>Codice Fiscale gi&agrave; presente nel sistema. Per ulteriori informazioni contattaci via email.</h2>'); 
                        }
                    }else{
                        Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->error('[OUT] registrazione', $this->mod_log->getParamFrontoffice('email già presente nel sistema'));
                        $this->request->session()->flash('formerrato', '<h2>Email gi&agrave; presente nel sistema. Se non ricordi la password prova a recuperarla tramite l&apos;apposito servizio.</h2>'); 
                    }
                }else{
                    //echo '<pre>'.$this->erroriFormRegistrazione;exit;
                    Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->error('[OUT] registrazione', $this->mod_log->getParamFrontoffice('dati non corretti'));
                    $this->request->session()->flash('formerrato', '<h2>Dati non corretti</h2>'."<br />".$this->erroriFormRegistrazione);
                }
            }else{
                Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->error('[OUT] registrazione', $this->mod_log->getParamFrontoffice('captcha non validato'));
                $this->request->session()->flash('formerrato', '<h2>Captcha non validato correttamente.</h2>'); 
            }            
        }
        $privacy_policy=$this->mod_privacy->getPrivacyAttuale();
        //echo '<pre>';print_r($privacy_policy);exit;
        return view('registrazione')->with('datapost',$datireg)->with('privacy_policy',$privacy_policy)->with('title_page',$title_page)
                ->with('og_url',$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])
                ->with('og_title','Registrazione Utente')
                ->with('art_title','Registrazione Utente')
                ->with('og_description','Registrazione di un nuovo utente per il portale delle storie di zoonosi')
                ->with('art_description','Registrazione di un nuovo utente per il portale delle storie di zoonosi');
    }
    
    /**
    *
    * Metodo di controllo validità dei dati del form di registrazione
    * @return boolean
    *
    */
    private function checkRegistrationform(){
        Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] checkRegistrationform', $this->mod_log->getParamFrontoffice());
        $request_post=$this->request->all();
        //controllo dati required mancanti
        $datimancanti=[];
        if(!$request_post['nome'])$datimancanti[]='Nome mancante';
        if(!$request_post['cognome'])$datimancanti[]='Cognome mancante';
        if(!$request_post['email'])$datimancanti[]='Email mancante';
        if(!$request_post['ripetiemail'])$datimancanti[]='Ripeti Email mancante';
        if(!$request_post['password'])$datimancanti[]='Password mancante';
        if(!$request_post['ripetipassword'])$datimancanti[]='Ripeti Password mancante';
        if((isset($request_post['privacypolicy']) && $request_post['privacypolicy']!=1) || !isset($request_post['privacypolicy']))$datimancanti[]='Accetta le condizioni sulla Privacy';
        if(count($datimancanti)>0){
            $this->setVisualErrori($datimancanti);
            return false;
        }
        
        //controllo integrità dei dati
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
            $this->setVisualErrori($datiintegri);
            return false;
        }
        
        $daticodfis=[];
        if($request_post['codfis']!=''){
            if(!preg_match('/[A-Z0-9]+/', trim(strtoupper($request_post['codfis']))))$daticodfis[]='Codice fiscale non valido';
        }
        if(count($daticodfis)>0){
            $this->setVisualErrori($daticodfis);
            Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->error('[OUT] checkRegistrationform', $this->mod_log->getParamFrontoffice('paramentri non corretti'));
            return false;
        }
        return true;
    }

    private function setVisualErrori($arrayErr){
        foreach ($arrayErr AS $key=>$textErrore){
            $this->erroriFormRegistrazione.='<b>'.$textErrore.'</b><br />';
        }
        unset($arrayErr);
        return;
    }
    
    private function checkExistMail($check_email){
        $email = filter_var($check_email, FILTER_SANITIZE_EMAIL);
        $user=[];
        $user=$this->mod_user->getUserFromEmail($email)->toArray();
        if(count($user)==0)
            return false;
        return true;
    }
    
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
    * Metodo di controllo e verifica email di registrazione
    * @return view
    *
    */
    public function checkemailconferma(){
        Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] checkemailconferma', $this->mod_log->getParamFrontoffice());
        if($this->mod_conferma->checkEmailConferma($this->request->first,$this->request->second,$this->request->third)){
            // check url http://127.0.0.1:8000/confermamail/d889d75e8e50459912041e6d028369de465eb28027207606bba97b6574290b3253746c32/34/MLgu9anq2rX9Rpam
            $user = User::where('id', $this->request->second)->first();
            $user->markEmailAsVerified();
            $this->request->session()->flash('messageinfo', '<h2>Email verificata con successo. Benvenuto.</h2>');   
            
            //cancellazione record richiesta conferma email
            $this->mod_user->deleteTMPDateverified($user->id);
            Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[OUT] checkemailconferma', $this->mod_log->getParamFrontoffice('email confermata'));
        }else{
            Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->error('[OUT] checkemailconferma', $this->mod_log->getParamFrontoffice('errore durante la conferma dell\'indirizzo email'));
        }
        return redirect('/');
    }
}
