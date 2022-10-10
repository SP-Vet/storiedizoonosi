<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\Admin;
use App\Models\Storie;
use App\Models\Daticontesto;
use DateTime;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\LogPersonal;

class AdminDaticontestoController extends Controller
{
    public $mod_storie;
    public $mod_daticontesto;
    private $request;
    public $menuactive='storie';
    public $erroriFormSubmission='';
    
    public function __construct(Request $request)
    {
        $this->request=$request;
        $this->mod_storie = new Storie;
        $this->mod_daticontesto=new Daticontesto();
        $this->mod_log=new LogPersonal($request);
    }
    
    /**
    *
    * gestione dati contesto di una storia
    *   
    * @return view
    *
    */
    public function daticontestostoria(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] daticontestostoria', $this->mod_log->getParamFrontoffice());
        if(auth()->guard('admin')->user()->role!=='admin'){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->warning('[OUT] daticontestostoria', $this->mod_log->getParamFrontoffice('ruolo non ammesso'));
            return redirect('/admin');
        }
        $title_page='Dati contesto storia';
        if($this->request->isMethod('post')){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] daticontestostoria', $this->mod_log->getParamFrontoffice('invio post dati di contesto')); 
            if($this->checkform()){
                $request_post=$this->request->all();                
                DB::beginTransaction();
                try {
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[IN TRY] daticontestostoria', $this->mod_log->getParamFrontoffice());
                    //memorizzazione dati di base
                    $ordine=1;
                    $elencodbid=[];
                    $elencoPOSTdbid=[];//contiene le chiavi numeriche e non numeriche prima e dopo la memorizzazione dei dati di contesto
                    foreach ($request_post['dbid'] AS $ks=>$dbid){
                        $datidbid=[];
                        if(!is_numeric($dbid)){
                            //insert storiafase
                            $daticontesto=new Daticontesto();
                            $daticontesto->sid=$this->request->sid;
                            $daticontesto->ordine=$ordine;
                            $daticontesto->save();
                            $iddbid=$daticontesto->dbid;
                        }else{
                            $daticontesto = Daticontesto::find($dbid);
                            $daticontesto->ordine = $ordine;
                            $daticontesto->save();
                            $iddbid=$dbid;
                        }

                        //update-insert domande/risposte dati di contesto
                        $elencodbid[]=$elencoPOSTdbid[$dbid]=$iddbid;
                        $datidbid['domanda']=$this->dataready($request_post['domanda'][$ks]);
                        $datidbid['risposta']=$this->dataready($request_post['risposta'][$ks]);
                        $this->mod_daticontesto->setDaticontestolinguaAss($iddbid,$datidbid);
                        $ordine++;
                    }
                    //delete tutti dbid non presenti tra insert ed update
                    Daticontesto::whereNotIn('dbid',$elencodbid)->where('sid',$this->request->sid)->delete();
                    unset($elencobdid);
                    unset($ordine);
                    unset($dbid);
                    unset($iddbid);
                    
                    DB::commit();
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[OUT TRY] daticontestostoria', $this->mod_log->getParamFrontoffice());
                    $this->request->session()->flash('messageinfo', '<h2>Dati di contesto aggiornati con successo!</h2>');   
                    return redirect('/admin/elencostorie');
                }catch (Throwable $e) {
                    DB::rollBack();
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT TRY] daticontestostoria', $this->mod_log->getParamFrontoffice($e->getMessage()));
                    echo $e->getMessage();
                    exit;
                }
            }else{
                Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT] daticontestostoria', $this->mod_log->getParamFrontoffice('parametri post non validi'));
                $this->request->session()->flash('formerrato', '<h5>Dati non corretti - RICONTROLLARE TUTTI I CAMPI INSERITI</h5>'."".$this->erroriFormSubmission);
            }
        }
        
        $daticontesto=new \Illuminate\Support\Collection();
        $daticontesto=$this->mod_daticontesto->getDaticontestoFromStoria($this->request->sid);
        return view('admin.daticontesto.aggiungimodifica')->with('daticontesto',$daticontesto)->with('form','adminSalvaDatiContestoStoria')
                ->with('sid',$this->request->sid)
            ->with([
                'title_page'=>$title_page,
                'admin'=>auth()->guard('admin')->user(),
                'menuactive'=>$this->menuactive,
            ]);
        
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
        if(!$request_post['sid'])$datimancanti[]='Storia non selezionata';
        //check dati storie fasi
        if(!is_array($request_post['dbid']) || count($request_post['dbid'])==0)$datimancanti[]='Inserire almeno un dato di contesto per storia';
        if(!is_array($request_post['domanda']) || count($request_post['domanda'])==0)$datimancanti[]='Inserire i titoli delle dei dati di contesto della storia';
        if(!is_array($request_post['risposta']) || count($request_post['risposta'])==0)$datimancanti[]='Inserire le descrizion dei dati di contesto della storia';
        else{
            foreach ($request_post['domanda'] AS $tf=>$domanda){
                if(!$domanda)$datimancanti[]='Titolo mancante nel Dato '.($tf+1).' di contesto della storia';
            }
            foreach ($request_post['risposta'] AS $ttf=>$risposta){
                if(!$risposta)$datimancanti[]='Descrizione mancante nel Dato '.($ttf+1).' di contesto della storia';
            }
        }
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
    
    private function dataready($data) {
        if(!$data)return '';
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    } 
}
