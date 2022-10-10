<?php

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
use App\Models\Approfondimenti;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\LogPersonal;


class AdminApprofondimentiController extends Controller
{
    public $mod_approfondimenti;
    public $emailutente;
    private $request;
    public $menuactive='approfondimenti';
    public $erroriFormSubmission='';
    
    public function __construct(Request $request)
    {
        $this->request=$request;
        $this->mod_approfondimenti = new Approfondimenti;
        $this->mod_log=new LogPersonal($request);
    }
    
    /**
    *
    * Elenca tutte le storie presenti nel sistema
    *   
    * @return view
    *
    */
    public function elenco(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] elenco', $this->mod_log->getParamFrontoffice());
        if(auth()->guard('admin')->user()->role!=='admin')return redirect('/admin');
        $title_page='Elenco approfondimenti';
        $where=$whereand=$whereor=$wherenot=$wheresame=[];
        $order=[];
        $order['sa.data_inserimento']='DESC';
        $approfondimenti=$this->mod_approfondimenti->getAll([],$order);
        
        return view('admin.approfondimenti.elenco')->with('approfondimenti',$approfondimenti)
                ->with([
                    'title_page'=>$title_page,
                    'admin'=>auth()->guard('admin')->user(),
                    'menuactive'=>$this->menuactive,
                        
                ]);
    }
    
    public function gestisci(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] gestisci', $this->mod_log->getParamFrontoffice());
        if(auth()->guard('admin')->user()->role!=='admin')return redirect('/admin');
        $title_page='Modifica approfondimento';
        $datiapp=[];
        
        if($this->request->isMethod('post')){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] gestisci', $this->mod_log->getParamFrontoffice('richiesta modifica'));
            $datiapp=$this->request->all();
            if($this->checkform()){
                Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] gestisci', $this->mod_log->getParamFrontoffice('form corretto'));
                //UPDATE testo approfondimento
                DB::beginTransaction();
                try {
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[IN TRY] gestisci', $this->mod_log->getParamFrontoffice());
                    $approfondimento = Approfondimenti::find($datiapp['said']);
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
                    
                    //invio email approvazione all'utente che l'ha inserito
                    if($inviamail==1){
                        $user=User::find($approfondimento->uid);
                        $this->emailutente=$user->email;
                        $datimail=array('testoapprofondimento' => html_entity_decode($approfondimento->testoapprofondimento,ENT_QUOTES,'utf-8'));
                        Mail::send('emails.approfondimentoapprovato', $datimail, function($message){
                            $message->subject('Nuovo approfondimento inserito');
                            $message->to($this->emailutente);
                        });
                        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[OUT SEND MAIL] approfondimenti', $this->mod_log->getParamFrontoffice());
                    }
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[OUT TRY] gestisci', $this->mod_log->getParamFrontoffice());
                    $this->request->session()->flash('messageinfo', 'Approfondimento aggiornato correttamente');
                    return redirect(route('adminListApprofondimenti'));
                } catch (Throwable $e) {
                    DB::rollBack();
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT] gestisci', $this->mod_log->getParamFrontoffice($e->getMessage()));
                    echo $e->getMessage();
                    exit;
                }
            }else{
                Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT] gestisci', $this->mod_log->getParamFrontoffice('form non corretto'));
                $this->request->session()->flash('formerrato', '<h5>Dati non corretti</h5>'."".$this->erroriFormSubmission);
            }
        }else{
            $datiapp=$this->mod_approfondimenti->getApprofondimento($this->request->said);
            $datiapp=get_object_vars($datiapp->toArray()[0]);
        }
        
        return view('admin.approfondimenti.gestisci')->with('datapost',$datiapp)->with('form','adminSalvaGestioneApprofondimento')
                ->with([
                    'title_page'=>$title_page,
                    'admin'=>auth()->guard('admin')->user(),
                    'menuactive'=>$this->menuactive,
                    
                ]);
    }
    
    
  /**
    *
    * Metodo di controllo validità dei dati del form di gestione
    * @return boolean
    *
    */
    private function checkform(){
        $request_post=$this->request->all();
        //controllo dati required mancanti
        $datimancanti=[];
        if(!$request_post['testoapprofondimento'])$datimancanti[]='Testo approfondimento mancante';
        if(!preg_match('/^[1-9][0-9]*$/',$request_post['said']))$datimancanti[]='Approfondimento mancante';
        if(count($datimancanti)>0){
            $this->setVisualErrori($datimancanti);
            return false;
        }
        return true;
     
    }
    
    public function pubblicaapprofondimento(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] pubblicaapprofondimento', $this->mod_log->getParamFrontoffice());
        if(!preg_match('/^[1-9][0-9]*$/',$this->request->said)){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[OUT] pubblicaapprofondimento', $this->mod_log->getParamFrontoffice('id approfondimento non valido'));
            return response()->json(['error'=>true,'message'=>'Approfondimento selezionato non valido']);   
        }
        $this->mod_approfondimenti->pubblicaApprofondimento($this->request->said,['stato'=>1,'data_pubblicazione'=>'NOW()']);
        $approfondimento = Approfondimenti::find($this->request->said);
        $user=User::find($approfondimento->uid);
        
        //invio email di conferma
        $this->emailutente=$user->email;
        $datimail=array('testoapprofondimento' => html_entity_decode($approfondimento->testoapprofondimento,ENT_QUOTES,'utf-8'));
        Mail::send('emails.approfondimentoapprovato', $datimail, function($message){
            $message->subject('Nuova integrazione inserita');
            $message->to($this->emailutente);
        });
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[END SEND MAIL] approfondimenti', $this->mod_log->getParamFrontoffice());        
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[OUT] pubblicaapprofondimento', $this->mod_log->getParamFrontoffice());
        return response()->json(['error'=>false,'message'=>'']);
    }
    
    public function getintegrazionifase(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] getintegrazionifase', $this->mod_log->getParamFrontoffice());
        if($this->request->sfid!='' && !preg_match('/^[1-9][0-9]*$/',$this->request->sfid)){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT] getintegrazionifase', $this->mod_log->getParamFrontoffice('parametri non validi'));
            return response()->json(['error'=>true,'message'=>'Si è verificato un problema con la le integrazioni scelte.']);
        }
        $integrazioni=$this->mod_approfondimenti->getAll(['sa.sfid'=>$this->request->sfid]);
        return response()->json(['error'=>false,'message'=>'','integrazioni'=>$integrazioni]);
    }
    
    private function setVisualErrori($arrayErr){
        foreach ($arrayErr AS $key=>$textErrore){
            $this->erroriFormSubmission.='<b>'.$textErrore.'</b><br />';
        }
        unset($arrayErr);
        return;
    }
}
