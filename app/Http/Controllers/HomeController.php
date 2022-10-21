<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Home;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\LogPersonal;

class HomeController extends Controller
{
    public $mod_home;
    private $request;
    
    public function __construct(Request $request)
    {
        $this->request=$request;
        $this->mod_home = new Home();
        $this->mod_log=new LogPersonal($request);
    }
    
    /**
    *
    * List all zoonoses in the system
    *
    * @return view
    *
    */
    public function index(){
        Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] homepage', $this->mod_log->getParamFrontoffice());
        $title_page='Homepage';
        $order=[];
        $order['zu.nome']='ASC';
        $zoonosi=$this->mod_home->getZoonosi('',$order);
        return view('home')->with('zoonosi',$zoonosi)->with('title_page',$title_page)
                ->with('og_description','Repository di esperienze in forma di racconto, con riferimenti alla letteratura scientifica riguardanti «Casi di Zoonosi»')
                ->with('art_description','Repository di esperienze in forma di racconto, con riferimenti alla letteratura scientifica riguardanti «Casi di Zoonosi»')
                ->with('art_abstract','Repository di esperienze in forma di racconto, con riferimenti alla letteratura scientifica riguardanti «Casi di Zoonosi»');
    }
    
    /**
    *
    * Static project description page
    *
    * @return view
    *
    */
    public function project(){
        Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] project', $this->mod_log->getParamFrontoffice());
        return view('project');
    }
    
  
}
