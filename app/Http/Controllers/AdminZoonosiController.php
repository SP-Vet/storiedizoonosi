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
use App\Models\Zoonosi;
Use App\Models\Reviews;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\LogPersonal;

class AdminZoonosiController extends Controller
{
    public $mod_zoonosi;
    private $request;
    public $erroriFormSubmission='';
    public $menuactive='zoonosi';
    
    public function __construct(Request $request)
    {
        $this->request=$request;
        $this->mod_zoonosi = new Zoonosi;
        $this->mod_review= new Reviews();
        $this->mod_log=new LogPersonal($request);
    }
    
    /**
    *
    * Elenca tutte le zoonosi presenti nel sistema
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
        $title_page='Elenco zoonosi';        
        $where=$whereand=$whereor=$wherenot=$wheresame=[];
        $order=[];
        $order['zl.nome']='ASC';
        $zoonosi=$this->mod_zoonosi->getAll([],$order);
        $review=$revfiles=[];
        $review=$this->mod_review->getAllReview()->toArray();
        if(count($review)>0){
            foreach ($review AS $documento)
                $revfiles[$documento->zid]=$documento;
        }
        $zoonosi=$this->mod_zoonosi->getAll([],$order);
        return view('admin.zoonosi.elenco')->with('zoonosi',$zoonosi)->with('revfiles',$revfiles)
                ->with([
                    'title_page'=>$title_page,
                    'admin'=>auth()->guard('admin')->user(),
                    'menuactive'=>$this->menuactive
                ]);;
    }
    
     public function aggiungi(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] aggiungi', $this->mod_log->getParamFrontoffice());
        if(auth()->guard('admin')->user()->role!=='admin'){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[OUT] aggiungi', $this->mod_log->getParamFrontoffice('ruolo non ammesso'));
            return redirect('/admin');
        }
        $title_page='Aggiungi zoonosi';
        $datizoo=[];
        
        if($this->request->isMethod('post')){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] aggiungi', $this->mod_log->getParamFrontoffice('invio del post'));
            $datizoo=$this->request->all();
            if($this->checkform()){
                DB::beginTransaction();
                try {
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[IN TRY] aggiungi', $this->mod_log->getParamFrontoffice());
                    //memorizzazione zoonosi
                    $zoonosi = new Zoonosi;
                    $zoonosi->linktelegram = $datizoo['linktelegram'];
                    $zoonosi->linkraccoltereview = $datizoo['linkraccoltereview'];
                    $zoonosi->save();

                    //memorizzazione parametri
                    $this->mod_zoonosi->setZoonosiLang($datizoo,$zoonosi->zid);
                    
                    DB::commit();
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[OUT TRY] aggiungi', $this->mod_log->getParamFrontoffice());
                    $this->request->session()->flash('messageinfo', 'Zoonosi inserita correttamente');
                    return redirect(route('adminListZoonosi'));
                    
                } catch (Throwable $e) {
                    DB::rollBack();
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT] aggiungi', $this->mod_log->getParamFrontoffice($e->getMessage()));
                    echo $e->getMessage();
                    exit;
                }
            }else{
                Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT] aggiungi', $this->mod_log->getParamFrontoffice('form errato'));
                $this->request->session()->flash('formerrato', '<h5>Dati non corretti</h5>'."".$this->erroriFormSubmission);
            }
        }
   
        return view('admin.zoonosi.aggiungimodifica')->with('datapost',$datizoo)->with('form','adminSalvaNewZoonosi')
                ->with([
                    'title_page'=>$title_page,
                    'admin'=>auth()->guard('admin')->user(),
                    'menuactive'=>$this->menuactive,
                ]);;
    }
    
    public function modifica(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] modifica', $this->mod_log->getParamFrontoffice());
        if(auth()->guard('admin')->user()->role!=='admin'){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->warning('[OUT] modifica', $this->mod_log->getParamFrontoffice('ruolo non ammesso'));
            return redirect('/admin');
        }
        $title_page='Modifica zoonosi';
        $datizoo=[];
        
        if($this->request->isMethod('post')){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] modifica', $this->mod_log->getParamFrontoffice('invio del post'));
            $datizoo=$this->request->all();
            if($this->checkform()){
                //UPDATE ZOONOSI
                DB::beginTransaction();
                try {
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[IN TRY] modifica', $this->mod_log->getParamFrontoffice());
                    $zoonosi = Zoonosi::find($datizoo['zid']);
                    $zoonosi->linktelegram = $datizoo['linktelegram'];
                    $zoonosi->linkraccoltereview = $datizoo['linkraccoltereview'];
                    $zoonosi->save();

                    //update parametri
                    $this->mod_zoonosi->setZoonosiLang($datizoo,$zoonosi->zid,1);
                    
                    DB::commit();
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[OUT TRY] modifica', $this->mod_log->getParamFrontoffice());
                    $this->request->session()->flash('messageinfo', 'Zoonosi aggiornata correttamente');
                    return redirect(route('adminListZoonosi'));
                } catch (Throwable $e) {
                    DB::rollBack();
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT TRY] modifica', $this->mod_log->getParamFrontoffice($e->getMessage()));
                    echo $e->getMessage();
                    exit;
                }
            }else{
                Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT] modifica', $this->mod_log->getParamFrontoffice('form errato'));
                $this->request->session()->flash('formerrato', '<h5>Dati non corretti</h5>'."".$this->erroriFormSubmission);
            }
        }else{
            $datizoo=$this->mod_zoonosi->getZoonosi($this->request->zid);
            $datizoo=get_object_vars($datizoo->toArray()[0]);
        }
     
        return view('admin.zoonosi.aggiungimodifica')->with('datapost',$datizoo)->with('form','adminSalvaModificaZoonosi')
                ->with([
                    'title_page'=>$title_page,
                    'admin'=>auth()->guard('admin')->user(),
                    'menuactive'=>$this->menuactive,
                ]);
    }
  
    public function cancella(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] cancella', $this->mod_log->getParamFrontoffice());
        if(auth()->guard('admin')->user()->role!=='admin'){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->warning('[OUT] cancella', $this->mod_log->getParamFrontoffice('ruolo non ammesso'));
            return redirect('/admin');
        }
        $zoonosi = Zoonosi::find($this->request->zid);
        $zoonosi->delete();
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[OUT] cancella', $this->mod_log->getParamFrontoffice());
        $this->request->session()->flash('messageinfo', 'Zoonosi eliminata correttamente');
        return redirect(route('adminListZoonosi'));
    }
    
    public function checkslugzoonosi(){
        $zid=0;
        if(preg_match('/^[1-9][0-9]*$/',$this->request->zid))$zid=$this->request->zid;
        $zoonosi=$this->mod_zoonosi->checkExistSlugzoonosi($this->request->slug,$zid);
        if(count($zoonosi->toArray())>0){
            return response()->json(['error'=>true,'message'=>'Slug già presente nel sistema, modificare il nome della zoonosi']);
        }
        return response()->json(['error'=>false,'message'=>'']);
    }
    
    /**
    *
    * Metodo di controllo validità dei dati del form di inserimento/modifica
    * @return boolean
    *
    */
    private function checkform(){
        $request_post=$this->request->all();
        //controllo dati required mancanti
        $datimancanti=[];
        if(!$request_post['nome'])$datimancanti[]='Nome zoonosi mancante';
        if(!$request_post['descrizione'])$datimancanti[]='Descrizione zoonosi mancante';
        if(!$request_post['slugzoonosi'])$datimancanti[]='Slugzoonosi mancante';
        if(!$request_post['img_url'])$datimancanti[]='Url immagine zoonosi mancante';
        if(!$request_post['img_desc'])$datimancanti[]='Descrizione immagine zoonosi mancante';
      
        $zid=0;
        if(preg_match('/^[1-9][0-9]*$/',$request_post['zid']))$zid=$request_post['zid'];
        $zoonosi=$this->mod_zoonosi->checkExistSlugzoonosi($request_post['slugzoonosi'],$zid);
        if(count($zoonosi->toArray())>0)$datimancanti[]='Slug zoonosi già presente, cambiare il nome della zoonosi';
        $zoonosinome=$this->mod_zoonosi->checkExistNomezoonosi($request_post['nome'],$zid);
        if(count($zoonosinome->toArray())>0)$datimancanti[]='Nome zoonosi già presente, cambiare il nome della zoonosi';
        if(count($datimancanti)>0){
            $this->setVisualErrori($datimancanti);
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
