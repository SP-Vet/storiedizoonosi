<?php

namespace App\Http\Controllers;
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
use App\Models\Admin;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\LogPersonal;


class AdminGruppodilavoroController extends Controller
{
    public $mod_admin;
    private $request;
    public $menuactive='gruppodilavoro';
    public $erroriFormSubmission='';
    
    public function __construct(Request $request)
    {
        $this->request=$request;
        $this->mod_admin = new Admin;
        $this->mod_log=new LogPersonal($request);
    }
    
    /**
    *
    * Elenca tutti gli utenti presenti nel sistema come gruppo di lavoro
    *   
    * @return view
    *
    */
    public function elenco(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] elenco', $this->mod_log->getParamFrontoffice());
        if(auth()->guard('admin')->user()->role!=='admin'){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->warning('[OUT] elenco', $this->mod_log->getParamFrontoffice('ruolo non ammesso'));
            return redirect('/admin');
        }
        $title_page='Elenco utenti del gruppo di lavoro';
        $where=$whereand=$whereor=$wherenot=$wheresame=[];
        $order=[];
        $order['a.name']='ASC';
        $utenti=$this->mod_admin->getAll([],$order);
        return view('admin.gruppodilavoro.elenco')->with('gruppo',$utenti)
                ->with([
                    'title_page'=>$title_page,
                    'admin'=>auth()->guard('admin')->user(),
                    'menuactive'=>$this->menuactive,
                ]);
    }
    
    public function aggiungi(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] aggiungi', $this->mod_log->getParamFrontoffice());
        if(auth()->guard('admin')->user()->role!=='admin'){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->warning('[OUT] aggiungi', $this->mod_log->getParamFrontoffice('ruolo non ammesso'));
            return redirect('/admin');
        }
        
        return view('admin.gruppodilavoro.aggiungi')->with('gruppo',$utenti)
                ->with([
                    'title_page'=>$title_page,
                    'admin'=>auth()->guard('admin')->user(),
                    'menuactive'=>$this->menuactive,
                ]);
    }
  
    private function setVisualErrori($arrayErr){
        foreach ($arrayErr AS $key=>$textErrore){
            $this->erroriFormSubmission.='<b>'.$textErrore.'</b><br />';
        }
        unset($arrayErr);
        return;
    }
}
