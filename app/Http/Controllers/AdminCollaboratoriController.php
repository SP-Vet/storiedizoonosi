<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Carbon\Carbon;
use App\Models\Collaboratori;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\LogPersonal;

class AdminCollaboratoriController extends Controller
{
    private $request;
    
    public function __construct(Request $request)
    {
        $this->request=$request;
        $this->mod_collaboratori = new Collaboratori;
        $this->mod_log=new LogPersonal($request);
    }
    
    public function getcollaboratore(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] getcollaboratore', $this->mod_log->getParamFrontoffice());
        if($this->request->collid!='' && !preg_match('/^[1-9][0-9]*$/',$this->request->collid)){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT] getcollaboratore', $this->mod_log->getParamFrontoffice('parametri non validi'));
            return response()->json(['error'=>true,'message'=>'Si è verificato un problema con il collaboratore selezionato.']);
        }
        $collaboratore=$this->mod_collaboratori->find($this->request->collid);
        return response()->json(['error'=>false,'message'=>'','collaboratore'=>$collaboratore]);
    }
  
}
