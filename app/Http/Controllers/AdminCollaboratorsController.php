<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Carbon\Carbon;
use App\Models\Collaborators;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\LogPersonal;

class AdminCollaboratorsController extends Controller
{
    private $request;
    
    public function __construct(Request $request)
    {
        $this->request=$request;
        $this->mod_collaborators = new Collaborators();
        $this->mod_log=new LogPersonal($request);
    }
    
    public function getcollaborator(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] getcollaborator', $this->mod_log->getParamFrontoffice());
        if($this->request->collid!='' && !preg_match('/^[1-9][0-9]*$/',$this->request->collid)){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT] getcollaborator', $this->mod_log->getParamFrontoffice('parametri non validi'));
            return response()->json(['error'=>true,'message'=>'Si è verificato un problema con il collaboratore selezionato.']);
        }
        $collaboratore=$this->mod_collaborators->find($this->request->collid);
        return response()->json(['error'=>false,'message'=>'','collaboratore'=>$collaboratore]);
    }
  
}
