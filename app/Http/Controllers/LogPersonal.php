<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class LogPersonal extends Controller
{
    private $request;
    private $ip;
    private $useragent;
    private $session;
    private $route;
    private $post;
    private $file;
    private $fullurl;    
    public $erroriFormSubmission='';
    
    public function __construct(Request $request)
    {
        $this->request=$request;
        $this->ip=$request->ip();
        $this->useragent=$request->userAgent();
        $this->session= $request->getSession();
        $this->route=$request->route();
        $this->post=$request->post();
        $this->fullurl=$request->fullUrl();
        $this->file=$request->file();
    }
    
    public function elenco(){
        return view('admin.log.elenco');
    }
    
    public function getParamFrontoffice($message=''){
        return ['ip'=>$this->ip,'fullurl'=>$this->fullurl,'message'=>$message,'useragent'=>$this->useragent,'route'=>$this->route,'post'=>$this->post,'session'=>$this->session,'file'=>$this->file,'user'=>(Auth::user())?Auth::user()->id:null,'admin'=>(auth()->guard('admin')->user())?auth()->guard('admin')->user()->id:''];
    }
    
   
  
}
