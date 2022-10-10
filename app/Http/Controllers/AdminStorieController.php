<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Auth\SessionGuard;
use App\Models\Admin;
use App\Models\Storie;
use App\Models\Zoonosi;
use App\Models\Storiesubmit;
use App\Models\Storiesubmitfile;
use App\Models\Collaboratori;
use App\Models\Ruoli;
Use App\Models\Allegatimultimediali;
Use App\Models\Storiefasi;
Use App\Models\Snippets;
Use App\Models\Approfondimenti;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\LogPersonal;

use DateTime;
use DB;
use Carbon\Carbon;

class AdminStorieController extends Controller
{
    public $mod_storie;
    public $mod_storiesubmit;
    private $request;
    public $menuactive='storie';
    public $erroriFormSubmission='';
    
    public function __construct(Request $request)
    {
        $this->request=$request;
        $this->mod_storie = new Storie;
        $this->mod_storiesubmit = new Storiesubmit();
        $this->mod_storiesubmitfile = new Storiesubmitfile();
        $this->mod_zoonosi = new Zoonosi();    
        $this->mod_collaboratori = new Collaboratori();    
        $this->mod_ruoli = new Ruoli();    
        $this->mod_allegatimultimediali= new Allegatimultimediali();
        $this->mod_collaboratori=new Collaboratori();
        $this->mod_storiefasi=new Storiefasi();
        $this->mod_snippets=new Snippets();
        $this->mod_approfondimenti=new Approfondimenti();
        $this->mod_log=new LogPersonal($request);
    }
    
    /**
    *
    * Elenca tutte le storie presenti nel sistema
    *   
    * @return view
    *
    */
    public function elenco(){
        //echo '<pre>';print_r();exit;
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] elenco', $this->mod_log->getParamFrontoffice());
        if(auth()->guard('admin')->user()->role!=='admin'){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->warning('[IN] elenco', $this->mod_log->getParamFrontoffice('ruolo non ammesso'));
            return redirect('/admin');
        }
        $title_page='Elenco storie';
        $where=$whereand=$whereor=$wherenot=$wheresame=[];
        $order=[];
        $order['s.data_inserimento']='DESC';
        $storie=$this->mod_storie->getAll([],$order);
        return view('admin.storie.elenco')->with('storie',$storie)
                ->with([
                    'title_page'=>$title_page,
                    'admin'=>auth()->guard('admin')->user(),
                    'menuactive'=>$this->menuactive,
                ]);
    }
    
    public function modifica(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] modifica', $this->mod_log->getParamFrontoffice());
        $title_page='Aggiungi/Modifica Storia';
        $datistoria=[];
        $snippetfase=[];
        $collaboratori=new \Illuminate\Support\Collection();
        $collaboratoristoria=new \Illuminate\Support\Collection();
        if($this->request->isMethod('post')){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] modifica', $this->mod_log->getParamFrontoffice('inviato il post della storia'));
            $datistoria=$this->request->all();
            //echo '<pre>';print_r($datistoria);print_r($_FILES);echo '</pre>';
            if($this->checkform()){
                Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] modifica', $this->mod_log->getParamFrontoffice('post corretto'));
                $request_post=$this->request->all();
                $file_testo = $this->request->file('pdfstoria');
                $file_audio = $this->request->file('podcast');
                $file_video = $this->request->file('linkvideo');
                DB::beginTransaction();
                try {
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[IN TRY] modifica', $this->mod_log->getParamFrontoffice());
                    //memo storia      
                    if(!$request_post['sid']){   
                        $storia = new Storie();
                        $storia->data_pubblicazione = NULL;
                        if($request_post['stato']==2)
                            $storia->data_pubblicazione = 'NOW()';
                    }else{
                        $storia = Storie::find($request_post['sid']);
                        $oldstoria=$this->mod_storie->getStoria($request_post['sid'])->toArray();
                        $oldstoria=$oldstoria[0];
                        
                        if($oldstoria->stato!=2  && $request_post['stato']==2)
                            $storia->data_pubblicazione = 'NOW()';
                    }

                    $storia->data_lavorazione = 'NOW()';
                    $storia->anno_ambientazione = trim(htmlentities($request_post['anno_ambientazione'],ENT_QUOTES,'utf-8'));
                    $storia->copyright = $this->dataready($request_post['copyright']);
                    $storia->editore = $this->dataready($request_post['editore']);
                    $storia->zid = $request_post['zid'];
                    $storia->uid=$request_post['uid'];
                    $storia->linkzoodiac = $request_post['linkzoodiac'];
                    $storia->linkspvet = $request_post['linkspvet'];
                    $storia->stato = $request_post['stato'];
                    $storia->save();
                    
                    //memo storie lingue
                    $arr_storialingua=[];
                    $arr_storialingua['titolo']=$this->dataready($request_post['titolo']);
                    $arr_storialingua['slug']=$this->dataready($request_post['slug']);
                    $arr_storialingua['abstract']=$this->dataready($request_post['abstract']);
                    $arr_storialingua['descrizione']=$this->dataready($request_post['descrizione']);
                    $this->mod_storie->setStorialinguaAss($storia->sid,$arr_storialingua);
                    
                    /*START memo collaboratori*/
                    // 1.svuotare meoh_storiecollaboratori
                    $this->mod_collaboratori->deleteStorieCollaboratoriAss($storia->sid);
                    // 2.aggiungere eventuali collaboratori a meoh_storie_collaboratori
                    $arr_collaboratori=[];
                    $arr_collaboratori=$request_post['collid'];
                    if(is_array($request_post['collid']) && count($request_post['collid'])>0){
                        foreach ($request_post['collid'] AS $tco=>$collaboratore){
                            if(!is_numeric($collaboratore)){
                                $collab = new Collaboratori();
                                $collab->nome = $this->dataready($request_post['nomecollaboratore'][$tco]);
                                $collab->cognome = $this->dataready($request_post['cognomecollaboratore'][$tco]);
                                $collab->save();
                                
                                $arr_collaboratori[$tco]=$collab->collid;
                            }
                        }unset($tco);unset($collaboratore);
                    }
                    // 3.riempire meoh_storiecollaboratori
                    $this->mod_collaboratori->addCollaboratoriStoria($storia->sid,$arr_collaboratori,$request_post['sel_ruolo']);
                    /*END memo collaboratori*/
                    
                    //memo elementi multimediali
                    $pathst = storage_path('app/public/storieallegatimultimediali/'.$storia->sid);
                    if(!File::isDirectory($pathst)){
                        File::makeDirectory($pathst, 0777, true, true);
                    }

                    //podcast
                    $whereallegati1=[];
                    $oldpodcast=new \Illuminate\Support\Collection();
                    $whereallegati1[]=['ams.tipologia',1]; //AUDIO/PODCAST
                    $oldpodcast=$this->mod_allegatimultimediali->getAllegatiMultimedialiFromStoria($storia->sid,$whereallegati1);
                    $deleteOldPodcast=0;
                    if(isset($_FILES['podcast']['name']) && $_FILES['podcast']['name']!=''){
                        $deleteOldPodcast=1;
                        $originPodcastName = $this->request->file('podcast')->getClientOriginalName();
                        $filePodcastName = pathinfo($originPodcastName, PATHINFO_FILENAME);
                        $extensionPodcast = $this->request->file('podcast')->getClientOriginalExtension();
                        
                        $newPodcastName = 'FILEPODCAST_'.time().'.'.$extensionPodcast;
                        $this->request->file('podcast')->move($pathst, $newPodcastName);
                        $urlPodcast=asset(Storage::url('storieallegatimultimediali/'.$storia->sid.'/'.$newPodcastName));
                 
                        //memorizzazione DB
                        $allegatopodcast = new Allegatimultimediali();
                        $allegatopodcast->nome_file_originale=$originPodcastName;
                        $allegatopodcast->nome_file_memorizzato=$newPodcastName;
                        $allegatopodcast->tipologia=1;
                        $allegatopodcast->sid=$storia->sid;
                        $allegatopodcast->save();
                    }
                    //se nuovo podcast inserito e presente uno vecchio || eliminato il vecchio podcast
                    if(($deleteOldPodcast==1 && count($oldpodcast->all())>0) ||  ($deleteOldPodcast==0 && count($oldpodcast->all())>0 && !$request_post['filepodcast']) ){
                        foreach ($oldpodcast AS $singlepodcast){
                            //eliminazione fisica
                            $pathpod = storage_path('app/public/storieallegatimultimediali/'.$storia->sid.'/'. $singlepodcast->nome_file_memorizzato);
                            Storage::delete($pathpod);
                            
                            //eliminazione record DB
                            Allegatimultimediali::destroy($singlepodcast->amsid);                            
                        }
                    }
                 
                    //video
                    $oldvideo=new \Illuminate\Support\Collection();
                    $whereallegati2=[2,5]; //VIDEO
                    $oldvideo=$this->mod_allegatimultimediali->getAllegatiMultimedialiFromStoria($storia->sid,array(),$whereallegati2);
                    $deleteOldVideo=0;
                    if(isset($_FILES['linkvideo']['name']) && $_FILES['linkvideo']['name']!=''){
                        $deleteOldVideo=1;
                        $originVideoName = $this->request->file('linkvideo')->getClientOriginalName();
                        $fileVideoName = pathinfo($originVideoName, PATHINFO_FILENAME);
                        $extensionVideo = $this->request->file('linkvideo')->getClientOriginalExtension();
                        
                        $newVideoName = 'FILEVIDEO_'.time().'.'.$extensionVideo;
                        $this->request->file('linkvideo')->move($pathst, $newVideoName);
                        $urlVideo=asset(Storage::url('storieallegatimultimediali/'.$storia->sid.'/'.$newVideoName));
                 
                        //memorizzazione DB
                        $allegatovideo = new Allegatimultimediali();
                        $allegatovideo->nome_file_originale=$this->dataready($originVideoName);
                        $allegatovideo->nome_file_memorizzato=$newVideoName;
                        $allegatovideo->tipologia=2;
                        $allegatovideo->sid=$storia->sid;
                        $allegatovideo->save();
                    }
                    //se nuovo podcast inserito e presente uno vecchio || eliminato il vecchio video || vecchio video sostituito da linkurl/html
                    if(($deleteOldVideo==1 && count($oldvideo->all())>0) ||  ($deleteOldVideo==0 && count($oldvideo->all())>0 && !$request_post['filevideo']) || ($deleteOldVideo==0 && count($oldvideo->all())>0 && $request_post['linkurlhtml']!='')){
                        foreach ($oldvideo AS $singlevideo){
                            //eliminazione fisica
                            $pathvid = storage_path('app/public/storieallegatimultimediali/'.$storia->sid.'/'. $singlevideo->nome_file_memorizzato);
                            Storage::delete($pathvid);
                            
                            //eliminazione record DB
                            Allegatimultimediali::destroy($singlevideo->amsid);                            
                        }
                    }
                    if($request_post['linkurlhtml']!=''){
                        //memorizzazione DB
                        $allegatovideo = new Allegatimultimediali();
                        $allegatovideo->tipologia=5;
                        $allegatovideo->linkurlhtml=$this->dataready($request_post['linkurlhtml']);
                        $allegatovideo->sid=$storia->sid;
                        $allegatovideo->save();
                    }
                    
                    //pdf
                    $whereallegati3=[];
                    $oldpdf=new \Illuminate\Support\Collection();
                    $whereallegati3[]=['ams.tipologia',6]; //PDF STORIA
                    $oldpdf=$this->mod_allegatimultimediali->getAllegatiMultimedialiFromStoria($storia->sid,$whereallegati3);
                    $deleteOldPdf=0;
                    if(isset($_FILES['pdfstoria']['name']) && $_FILES['pdfstoria']['name']!=''){
                        $deleteOldPdf=1;
                        $originPdfName = $this->request->file('pdfstoria')->getClientOriginalName();
                        $filePdfName = pathinfo($originPdfName, PATHINFO_FILENAME);
                        $extensionPdf = $this->request->file('pdfstoria')->getClientOriginalExtension();
                        
                        $newPdfName = 'FILEPDF_'.time().'.'.$extensionPdf;
                        $this->request->file('pdfstoria')->move($pathst, $newPdfName);
                        $urlPdf=asset(Storage::url('storieallegatimultimediali/'.$storia->sid.'/'.$newPdfName));
                 
                        //memorizzazione DB
                        $allegatopdf = new Allegatimultimediali();
                        $allegatopdf->nome_file_originale=$originPdfName;
                        $allegatopdf->nome_file_memorizzato=$newPdfName;
                        $allegatopdf->tipologia=6;
                        $allegatopdf->sid=$storia->sid;
                        $allegatopdf->save();
                    }
                    //se nuovo pdf inserito e presente uno vecchio || eliminato il vecchio pdf
                    if(($deleteOldPdf==1 && count($oldpdf->all())>0) ||  ($deleteOldPdf==0 && count($oldpdf->all())>0 && !$request_post['filepdf']) ){
                        foreach ($oldpdf AS $singlepdf){
                            //eliminazione fisica
                            $pathpdf = storage_path('app/public/storieallegatimultimediali/'.$storia->sid.'/'. $singlepdf->nome_file_memorizzato);
                            Storage::delete($pathpdf);
                            
                            //eliminazione record DB
                            Allegatimultimediali::destroy($singlepdf->amsid);                            
                        }
                    }
                    
                    //memorizzazione FASI e APPROFONDIMENTI
                    $ordine=1;
                    $elencosfid=[];
                    $elencoPOSTsfid=[];//contiene le chiavi numeriche e non numeriche prima e dopo la memorizzazione delle fasi
                    foreach ($request_post['sfid'] AS $ks=>$sfid){
                        $datisfid=[];
                        if(!is_numeric($sfid)){
                            //insert storiafase
                            $storiafase=new Storiefasi();
                            $storiafase->sid=$storia->sid;
                            $storiafase->ordine=$ordine;
                            $storiafase->save();
                            $idsfid=$storiafase->sfid;
                        }else{
                            $storiafase = Storiefasi::find($sfid);
                            $storiafase->ordine = $ordine;
                            $storiafase->save();
                            $idsfid=$sfid;
                        }
                        
                        //update-insert storiafase
                        $elencosfid[]=$elencoPOSTsfid[$sfid]=$idsfid;
                        
                        $datisfid['titolofase']=$this->dataready($request_post['titolofase'][$ks]);
                        $datisfid['testofase']=$this->dataready($request_post['testofase'][$ks]);
                        $this->mod_storiefasi->setStoriafaselinguaAss($idsfid,$datisfid);
                        $ordine++;
                    }
                    //delete tutti sfid non presenti tra insert ed update
                    Storiefasi::whereNotIn('sfid',$elencosfid)->where('sid',$storia->sid)->delete();
                    unset($elencosfid);unset($ordine);
                    unset($sfid);
                    unset($idsfid);
                 
                    //memorizzazione snippets fasi
                    if(isset($request_post['snid']) && is_array($request_post['snid']) && count($request_post['snid'])>0){
                        foreach ($request_post['snid'] AS $sfid=>$snippets){
                            $idsfid=$sfid;
                            if(!is_numeric($sfid))
                                $idsfid=$elencoPOSTsfid[$sfid];
                            
                            $nuovisnip=[];
                            //tutti gli snippets nuovi
                            foreach ($snippets AS $ksn=>$snid){
                                $datisnip=[];
                                $idsnip=$snid;
                                if(!is_numeric($snid)){
                                    $nuovosnip=new Snippets();
                                    $nuovosnip->sfid=$idsfid;
                                    $nuovosnip->save();
                                    $idsnip=$nuovosnip->snid;
                                }
                                $datisnip['chiave']=$this->dataready($request_post['chiavesnippet'][$sfid][$ksn]);
                                $datisnip['titolo']=$this->dataready($request_post['titolosnippet'][$sfid][$ksn]);
                                $datisnip['testo']=$this->dataready($request_post['testosnippet'][$sfid][$ksn]);
                                $this->mod_snippets->setSnippetslinguaAss($idsnip,$datisnip);
                                $nuovisnip[]=$idsnip;
                            }
                            //delete snippets non più presenti per ogni determinata parte
                            Snippets::whereNotIn('snid',$nuovisnip)->where('sfid',$idsfid)->delete();
                        }
                    }
                    
                    DB::commit();
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[OUT TRY] modifica', $this->mod_log->getParamFrontoffice());
                    $this->request->session()->flash('messageinfo', '<h2>Storia aggiornata con successo!</h2>');   
                    return redirect('/admin/elencostorie');
                } catch (Throwable $e) {
                    DB::rollBack();
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT TRY] modifica', $this->mod_log->getParamFrontoffice($e->getMessage()));
                    echo $e->getMessage();
                    exit;
                }
               
            }else{
                Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT] modifica', $this->mod_log->getParamFrontoffice('form errato'));
                $this->request->session()->flash('formerrato', '<h5>Dati non corretti - RICONTROLLARE TUTTI I CAMPI INSERITI</h5>'."".$this->erroriFormSubmission);
            }            
        }

        $idsid=(isset($this->request->sid))?$this->request->sid:'';
        $fasistoria=new \Illuminate\Support\Collection();
        $datistoria=new \Illuminate\Support\Collection();
        $collaboratori=new \Illuminate\Support\Collection();
        $collaboratoristoria=new \Illuminate\Support\Collection();
        $snippets=new \Illuminate\Support\Collection();
        $storiasubmitfile=new \Illuminate\Support\Collection();
        $storiasubmit=new \Illuminate\Support\Collection();
        $collaboratori=$this->mod_collaboratori->getAll(1);
        $approfondimenti=$approfondimentifasi=[];
        if($idsid){
            $fasistoria=$this->mod_storie->getFasiStoria($idsid);
            $datistoria=$this->mod_storie->getStoria($idsid);
            $datistoria=get_object_vars($datistoria->toArray()[0]);
            $collaboratoristoria=$this->mod_storie->getCollaboratoriStoria($idsid);
            $snippets=$this->mod_storie->getSnippetsFromStoria($idsid)->toArray();
            $snippetfase=[];
            if(count($snippets)>0){
                foreach ($snippets AS $snippet){
                    $snippetfase[$snippet->sfid][]=$snippet;
                }
            }
            
            $storiasubmit=$this->mod_storiesubmit->getStoriaSubmitFromSID($idsid);
            if(count($storiasubmit->all())>0){
                $storiasubmit=$storiasubmit[0];
                $storiasubmitfile=$this->mod_storiesubmitfile->getFilesFromSSID($storiasubmit->ssid);
            }
            
            $approfondimenti=$this->mod_approfondimenti->getNumeroApprofondimentiStoria($idsid)->toArray();
            if(count($approfondimenti)>0){
                foreach ($approfondimenti AS $totalegruppo){
                    $approfondimentifasi[$totalegruppo->sfid]=$totalegruppo->totalefase;
                }
            }
        }
        
        $whereallegati1=$whereallegati2=[];
        $podcast=new \Illuminate\Support\Collection();
        $video=new \Illuminate\Support\Collection();
        $pdfstoria=new \Illuminate\Support\Collection();

        if($idsid){
            $whereallegati1[]=['ams.tipologia',1]; //AUDIO/PODCAST
            $podcast=$this->mod_allegatimultimediali->getAllegatiMultimedialiFromStoria($idsid,$whereallegati1);
            $whereallegati2=[2,5]; //VIDEO
            $video=$this->mod_allegatimultimediali->getAllegatiMultimedialiFromStoria($idsid,array(),$whereallegati2);
            $whereallegati3[]=['ams.tipologia',6]; //PDF
            $pdfstoria=$this->mod_allegatimultimediali->getAllegatiMultimedialiFromStoria($idsid,$whereallegati3);
        }
        $ruoli=$this->mod_ruoli->getAll(1);        
        $order=[];
        $order['zl.nome']='ASC';
        $zoonosi=$this->mod_zoonosi->getAll('',$order);
        
        return view('admin.storie.aggiungimodifica')->with('datapost',$datistoria)->with('fasistoria',$fasistoria)->with('form','adminSalvaModificaStoria')
                ->with('storiasubmit',$storiasubmit)->with('storiasubmitfile',$storiasubmitfile)->with('zoonosi',$zoonosi)
                ->with('collaboratori',$collaboratori)->with('collaboratoristoria',$collaboratoristoria)
                ->with('video',$video)->with('podcast',$podcast)->with('pdfstoria',$pdfstoria)
                ->with('ruoli',$ruoli)->with('snippetfase',$snippetfase)->with('approfondimentifasi',$approfondimentifasi)
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
        if(!$request_post['anno_ambientazione'])$datimancanti[]='Anno di ambientazione mancante';
        if(!$request_post['editore'])$datimancanti[]='Editore mancante';
        if(!$request_post['titolo'])$datimancanti[]='Titolo mancante';
        if(!$request_post['slug'])$datimancanti[]='Slug mancante';
        if(!$request_post['abstract'])$datimancanti[]='Abstract mancante';
        if(!$request_post['copyright'])$datimancanti[]='Copyright mancante';
        
        //check collaboratori
        if(!isset($request_post['collid']) || !is_array($request_post['collid']) || (is_array($request_post['collid']) && count($request_post['collid'])==0))$datimancanti[]='Inserire almeno un collaboratore';
        if(isset($request_post['collid']) && count($request_post['collid'])>0){
            foreach ($request_post['collid'] AS $tc=>$collaboratore){
                if(!isset($request_post['nomecollaboratore'][$tc]) || $request_post['nomecollaboratore'][$tc]=='')$datimancanti[]='Inserire il nome del collaboratore';
                if(!isset($request_post['cognomecollaboratore'][$tc]) || $request_post['cognomecollaboratore'][$tc]=='')$datimancanti[]='Inserire il cognome del collaboratore';
                if(!isset($request_post['sel_ruolo'][$tc]) || $request_post['sel_ruolo'][$tc]=='' || $request_post['sel_ruolo'][$tc]==0)$datimancanti[]='Inserire il ruolo del collaboratore';
            }
        }
       
        //check dati storie fasi
        if(!is_array($request_post['sfid']) || count($request_post['sfid'])==0)$datimancanti[]='Inserire almeno una parte della storia';
        if(!is_array($request_post['titolofase']) || count($request_post['titolofase'])==0)$datimancanti[]='Inserire i titoli delle parti della storia';
        if(!is_array($request_post['testofase']) || count($request_post['testofase'])==0)$datimancanti[]='Inserire le parti della storia';
        else{
            foreach ($request_post['titolofase'] AS $tf=>$titolo){
                if(!$titolo)$datimancanti[]='Titolo mancante nella Parte '.($tf+1).' della storia';
            }
            foreach ($request_post['testofase'] AS $ttf=>$testofase){
                if(!$testofase)$datimancanti[]='Testo mancante nella Parte '.($ttf+1).' della storia';
            }
        }
        
        //check dati snippets
        if(isset($request_post['snid'])){
            if(is_array($request_post['snid']) && count($request_post['snid'])>0){
                foreach($request_post['snid'] AS $sfid=>$arrsnid){
                    foreach($arrsnid AS $ks=>$snid){
                        if($request_post['titolosnippet'][$sfid][$ks]=='')$datimancanti[]='Inserire il titolo dello snippet '.($ks+1).' della Parte '.(array_search ($sfid,$request_post['sfid'])+1).' della storia';
                        if($request_post['chiavesnippet'][$sfid][$ks]=='')$datimancanti[]='Inserire la chiave dello snippet '.($ks+1).' della Parte '.(array_search ($sfid,$request_post['sfid'])+1).' della storia';
                        if($request_post['testosnippet'][$sfid][$ks]=='')$datimancanti[]='Inserire il testo dello snippet '.($ks+1).' della Parte '.(array_search ($sfid,$request_post['sfid'])+1).' della storia';
                    }
                }
            }
        }
        
        //CHECK VALIDITA EVENTUALI UPLOAD DI VIDEO/PODCAST/PDF
        $allowed_text = array('pdf');
        $allowed_video = array('mp4','mov','avi');
        $allowed_audio = array('pcm', 'wav', 'mp3', 'ogg', 'flac');
        //controlli sui file pdf
        if(isset($_FILES['pdfstoria']['name']) && $_FILES['pdfstoria']['name']!=''){
            $ext_text = pathinfo($_FILES['pdfstoria']['name'], PATHINFO_EXTENSION);
            if (!in_array($ext_text, $allowed_text)) {$datimancanti[]='Il file caricato come testo della storia deve essere del formato .PDF';}
            if($_FILES['pdfstoria']['size']>10485760){$datimancanti[]='Il file PDF caricato è troppo grande';}
        }
        //controlli sul podcast
        if(isset($_FILES['podcast']['name']) && $_FILES['podcast']['name']!=''){
            $ext_audio = pathinfo($_FILES['podcast']['name'], PATHINFO_EXTENSION);
            if (!in_array($ext_audio, $allowed_audio)) {$datimancanti[]='Il file caricato come podcast della storia deve essere di un formato tra: .pcm, .wav, .mp3, .ogg, .flac';}
            if($_FILES['podcast']['size']>20971520){$datimancanti[]='Il file audio del Podcast caricato è troppo grande';}
        }
        //controlli sul video
        if(isset($_FILES['linkvideo']['name']) && $_FILES['linkvideo']['name']!=''){
            $ext_video = pathinfo($_FILES['linkvideo']['name'], PATHINFO_EXTENSION);
            if (!in_array($ext_video, $allowed_video)) {$datimancanti[]='Il file caricato come video della storia deve essere di un formato tra: .mp4, .mov, .avi';}
            if($_FILES['linkvideo']['size']>419430400){$datimancanti[]='Il file video caricato è troppo grande';}   
        }
        
        if(count($datimancanti)>0){
            $this->setVisualErrori($datimancanti);
            return false;
        }
        
        return true;
     
    }
    
     public function checkslug(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] checkslug', $this->mod_log->getParamFrontoffice());
        $sid=0;
        if(preg_match('/^[1-9][0-9]*$/',$this->request->sid))$sid=$this->request->sid;
        $storia=$this->mod_storie->checkExistSlug($this->request->slug,$sid);
        if(count($storia->toArray())>0){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[OUT] checkslug', $this->mod_log->getParamFrontoffice('slug già presente'));
            return response()->json(['error'=>true,'message'=>'Slug già presente nel sistema, modificare il nome della storia']);
        }
        return response()->json(['error'=>false,'message'=>'']);
    }
    
    public function pubblicastoria(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] pubblicastoria', $this->mod_log->getParamFrontoffice());
        if(!preg_match('/^[1-9][0-9]*$/',$this->request->sid)){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[OUT] pubblicastoria', $this->mod_log->getParamFrontoffice('id storia non valido'));
            return response()->json(['error'=>true,'message'=>'Storia selezionata non valida']);   
        }
        $this->mod_storie->pubblicaStoria($this->request->sid,['stato'=>2,'data_pubblicazione'=>'NOW()']);
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[OUT] pubblicastoria', $this->mod_log->getParamFrontoffice());
        return response()->json(['error'=>false,'message'=>'']);
    }
    
    private function setVisualErrori($arrayErr){
        foreach ($arrayErr AS $key=>$textErrore)
            $this->erroriFormSubmission.='<b>'.$textErrore.'</b><br />';
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
