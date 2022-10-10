<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Encore\Admin\Auth\Permission;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DB;
use Session;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\LogPersonal;

use App\Models\Storie;
use App\Models\Storiesubmit;
use App\Models\Approfondimenti;

class AdminController extends Controller
{    
    
    public $erroriFormSubmission='';
    private $request;

    public function __construct(Request $request)
    {
        $this->request=$request;
        $this->mod_log=new LogPersonal($request);         
    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] dashboard', $this->mod_log->getParamFrontoffice());
        $title_page='';
        
        $mod_storiesubmit= new Storiesubmit();
        $storie_sottomesse=[];
        $storie_sottomesse=$mod_storiesubmit->getStorieSubmitNONgestite();        
        $mod_storie= new Storie();
        $storie_bozze=[];
        $filterstorie=[['s.stato',1]]; //in lavorazione (bozza)
        $storie_bozze=$mod_storie->getStorie($filterstorie);        
        $mod_approfondimenti= new Approfondimenti();
        $approf_inseriti=[];
        $filterapprof=[['sa.stato',0]]; //inseriti non ancora approvati
        $approf_inseriti=$mod_approfondimenti->getAll($filterapprof);
        
        return view('admin.dashboard')->with([
                    'title_page'=>$title_page,
                    'admin'=>auth()->guard('admin')->user(),
                ])->with('storie_sottomesse',$storie_sottomesse)->with('storie_bozze',$storie_bozze)->with('approf_inseriti',$approf_inseriti);
    }
    
    
    public function cambiapassword(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] cambiapassword', $this->mod_log->getParamFrontoffice());
        if(auth()->guard('admin')->user()->id!=$this->request->id){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->warning('[OUT] cambiapassword', $this->mod_log->getParamFrontoffice('tentata mdifica altro utente'));
            return redirect(route('dashboard'));
        }
        $title_page='Cambia password';
        if($this->request->isMethod('post')){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] cambiapassword', $this->mod_log->getParamFrontoffice('richiesta post del cambio'));
            if($this->checkControlPassword($this->request->password)){
                DB::beginTransaction();
                try {
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[IN TRY] cambiapasswrod', $this->mod_log->getParamFrontoffice());
                    Admin::find(auth()->guard('admin')->user()->id)->update(['password'=> Hash::make($this->request->password)]);
                    DB::commit();
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[OUT TRY] cambiapasswrod', $this->mod_log->getParamFrontoffice());
                    $this->request->session()->flash('messageinfo', 'Password modificata correttamente');
                } catch (Throwable $e) {
                    DB::rollBack();
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT TRY] cambiapasswrod', $this->mod_log->getParamFrontoffice($e->getMessage()));
                    $this->request->session()->flash('messagedanger', 'Errore interno al sistema, passworn NON modificata');
                }
                return redirect(route('dashboard'));    
            }else{
                Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT] cambiapasswrod', $this->mod_log->getParamFrontoffice('form errato'));
                $this->request->session()->flash('formerrato', '<h5>Dati non corretti</h5>'."".$this->erroriFormSubmission);
            }
        }
        
        return view('admin.cambiapassword')->with([
                    'title_page'=>$title_page,
                    'admin'=>auth()->guard('admin')->user(),
                ]);
    }
    
    private function checkControlPassword(){
        $request_post=$this->request->all();
        //controllo dati required mancanti
        $datiintegri=[];
        if(!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%-*_£()])[0-9A-Za-z!@#$%-*_£()]{8,}$/', trim($request_post['password'])))$datiintegri[]='La password non rispetta gli standard di sicurezza';
        if(trim($request_post['password'])!==trim($request_post['ripetipassword']))$datiintegri[]='Le password fornite non coincidono';
        if(count($datiintegri)>0){
            $this->setVisualErrori($datiintegri);
            return false;
        }
        return true;
    }
    
    private function setVisualErrori($arrayErr){
        foreach ($arrayErr AS $key=>$textErrore){
            $this->erroriFormSubmission.='<b>'.$textErrore.'</b><br />';
        }
        unset($arrayErr);
        return;
    }

}