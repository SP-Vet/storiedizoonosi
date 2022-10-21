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
use App\Models\Stories;
use App\Models\Contextdata;
use DateTime;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\LogPersonal;

class AdminContextdataController extends Controller
{
    public $mod_stories;
    public $mod_contextdata;
    private $request;
    public $menuactive='storie';
    public $errorsFormSubmission='';
    
    public function __construct(Request $request)
    {
        $this->request=$request;
        $this->mod_stories = new Stories();
        $this->mod_contextdata=new Contextdata();
        $this->mod_log=new LogPersonal($request);
    }
    
    /**
    *
    * data management context of a story
    *   
    * @return view
    *
    */
    public function contextdatastory(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] contextdatastory', $this->mod_log->getParamFrontoffice());
        if(auth()->guard('admin')->user()->role!=='admin'){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->warning('[OUT] contextdatastory', $this->mod_log->getParamFrontoffice('ruolo non ammesso'));
            return redirect('/admin');
        }
        $title_page='Dati contesto storia';
        if($this->request->isMethod('post')){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] contextdatastory', $this->mod_log->getParamFrontoffice('invio post dati di contesto')); 
            if($this->checkform()){
                $request_post=$this->request->all();                
                DB::beginTransaction();
                try {
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[IN TRY] contextdatastory', $this->mod_log->getParamFrontoffice());
                    //memo base data
                    $ordine=1;
                    $elencodbid=[];
                    $elencoPOSTdbid=[];//contains numeric and non-numeric keys before and after storing the context data
                    foreach ($request_post['dbid'] AS $ks=>$dbid){
                        $datidbid=[];
                        if(!is_numeric($dbid)){
                            //insert storiafase
                            $daticontesto=new Contextdata();
                            $daticontesto->sid=$this->request->sid;
                            $daticontesto->ordine=$ordine;
                            $daticontesto->save();
                            $iddbid=$daticontesto->dbid;
                        }else{
                            $daticontesto = Contextdata::find($dbid);
                            $daticontesto->ordine = $ordine;
                            $daticontesto->save();
                            $iddbid=$dbid;
                        }

                        //update-insert context data questions / answers
                        $elencodbid[]=$elencoPOSTdbid[$dbid]=$iddbid;
                        $datidbid['domanda']=$this->dataready($request_post['domanda'][$ks]);
                        $datidbid['risposta']=$this->dataready($request_post['risposta'][$ks]);
                        $this->mod_contextdata->setContextdatalanguageAss($iddbid,$datidbid);
                        $ordine++;
                    }
                    //delete all dbid not in insert and update
                    Contextdata::whereNotIn('dbid',$elencodbid)->where('sid',$this->request->sid)->delete();
                    unset($elencobdid);
                    unset($ordine);
                    unset($dbid);
                    unset($iddbid);
                    
                    DB::commit();
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[OUT TRY] contextdatastory', $this->mod_log->getParamFrontoffice());
                    $this->request->session()->flash('messageinfo', '<h2>Dati di contesto aggiornati con successo!</h2>');   
                    return redirect('/admin/elencostorie');
                }catch (Throwable $e) {
                    DB::rollBack();
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT TRY] contextdatastory', $this->mod_log->getParamFrontoffice($e->getMessage()));
                    echo $e->getMessage();
                    exit;
                }
            }else{
                Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT] contextdatastory', $this->mod_log->getParamFrontoffice('parametri post non validi'));
                $this->request->session()->flash('formerrato', '<h5>Dati non corretti - RICONTROLLARE TUTTI I CAMPI INSERITI</h5>'."".$this->errorsFormSubmission);
            }
        }
        
        $daticontesto=new \Illuminate\Support\Collection();
        $daticontesto=$this->mod_contextdata->getContextdataFromStory($this->request->sid);
        return view('admin.contextdata.addmod')->with('daticontesto',$daticontesto)->with('form','adminSaveContextDataStory')
                ->with('sid',$this->request->sid)
            ->with([
                'title_page'=>$title_page,
                'admin'=>auth()->guard('admin')->user(),
                'menuactive'=>$this->menuactive,
            ]);
        
    }
    
    /**
    *
    * Method for checking validation data of the insert/modify form
    * @return boolean
    *
    */
    private function checkform(){
        $request_post=$this->request->all();
        //check for missing required data
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
            $this->setVisualErrors($datimancanti);
            return false;
        }
        return true;
    }

    private function setVisualErrors($arrayErr){
        foreach ($arrayErr AS $key=>$textErrore){
            $this->errorsFormSubmission.='<b>'.$textErrore.'</b><br />';
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
