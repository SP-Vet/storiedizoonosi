<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Carbon\Carbon;
use App\Models\Privacy;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\LogPersonal;

class PrivacyController extends Controller
{
    public $mod_privacy;
    private $request;
    
    public function __construct(Request $request)
    {
        $this->request=$request;
        $this->mod_privacy = new Privacy();
        $this->mod_log=new LogPersonal($request);
    }
    
    public function view(){
        Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] view', $this->mod_log->getParamFrontoffice());
        $title_page='Privacy Policy';
        $data=$this->mod_privacy->getCurrentPrivacy();
        return view('privacy')->with('data',$data)->with('title_page',$title_page);       
    }
  
}
