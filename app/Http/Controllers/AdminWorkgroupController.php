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
    * @return view
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
    
    public function adding(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] adding', $this->mod_log->getParamFrontoffice());
        if(auth()->guard('admin')->user()->role!=='admin'){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->warning('[OUT] adding', $this->mod_log->getParamFrontoffice('ruolo non ammesso'));
            return redirect('/admin');
        }
        
        return view('admin.workgroup.adding')->with('gruppo',$utenti)
                ->with([
                    'title_page'=>$title_page,
                    'admin'=>auth()->guard('admin')->user(),
                    'menuactive'=>$this->menuactive,
                ]);
    }
  
    private function setVisualErrors($arrayErr){
        foreach ($arrayErr AS $key=>$textErrore){
            $this->errorsFormSubmission.='<b>'.$textErrore.'</b><br />';
        }
        unset($arrayErr);
        return;
    }
}
