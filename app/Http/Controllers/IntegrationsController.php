<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Carbon\Carbon;
use App\Models\Integrations;
use App\Models\Stories;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\LogPersonal;

class IntegrationsController extends Controller
{
    public $mod_integrations;
    private $request;
    
    public function __construct(Request $request)
    {
        $this->request=$request;
        $this->mod_integrations = new Integrations();
        $this->mod_stories = new Stories();
        $this->mod_log=new LogPersonal($request);
        
    }
        
    
    public function setnewintegration(){
        Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] setnewintegration', $this->mod_log->getParamFrontoffice());
        if($this->request->idcomrisp!='' && !preg_match('/^[1-9][0-9]*$/',$this->request->idcomrisp))
            return response()->json(['error'=>true,'message'=>'Si è verificato un problema con il commento di risposta selezionato.']);
        if(($this->request->sfid!='' && !preg_match('/^[1-9][0-9]*$/',$this->request->sfid)) || !$this->request->sfid)
            return response()->json(['error'=>true,'message'=>'Si è verificato un problema con il blocco storia selezionato.']);
        if($this->request->approfondimento=='')
            return response()->json(['error'=>true,'message'=>'Nessun testo inserito come approfondimento.']);
        if(!Auth::check()){
            Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->alert('[IN] setnewintegration', $this->mod_log->getParamFrontoffice());
            return response()->json(['error'=>true,'message'=>'Utente non loggato o non autorizzato.']);
        }
        
        try{
            Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->critical('[IN TRY] setnewintegration', $this->mod_log->getParamFrontoffice());
            $user = Auth::user();
            //memorization of the integration sent
            $this->mod_integrations->setNewIntegration($user->id,$this->request->sfid,$this->request->approfondimento,$this->request->testoapprofondimento,$this->request->idcomrisp);
            
            //send TEMP EMAIL for in-depth notice entered
            $storia=$this->mod_stories->getStoryFromPhaseID($this->request->sfid);
            $datimail=array('nomeutente' => $user->name,'titolostoria'=>$storia->titolo,'titolofase'=>$storia->titolofase,'sfid'=>$this->request->sfid);
            Mail::send('emails.newintegration', $datimail, function($message){
                $message->subject('Nuovo approfondimento inserito');
                $message->to('e.rivosecchi@izsum.it');
                $message->cc('r.ciappelloni@izsum.it');
            });
            
            Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[OUT TRY] setnewintegration', $this->mod_log->getParamFrontoffice());
            return response()->json(['error'=>false,'message'=>'Approfondimento inviato. Sarà reso pubblico al termine della revisione.']);
            
        } catch (Throwable $e) {
            Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->error('[OUT TRY] setnewintegration', $this->mod_log->getParamFrontoffice($e->getMessage()));
            return response()->json(['error'=>true,'message'=>$e->getMessage()]);
        }
               
    }
  
}
