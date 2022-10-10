<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use DateTime;
use DB;
use App\Models\Storie;
use App\Models\Storiesubmit;
use App\Models\Storiesubmitfile;
use App\Models\Home;
use App\Models\Privacy;
use App\Http\Controllers\LogPersonal;
Use App\Models\Allegatimultimediali;

class StorieController extends Controller
{
    public $mod_storie;
    private $request;
    private $og_url='';
    private $og_type='article';
    private $og_title='';
    private $og_description='';
    private $art_title='';
    private $art_author='';
    private $art_description='';
    private $art_abstract='';
    
    public $erroriFormSubmission='';

    public function __construct(Request $request)
    {
        $this->request=$request;
        $this->mod_storie = new Storie;
        $this->mod_home = new Home;
        $this->mod_privacy = new Privacy;
        $this->mod_allegatimultimediali= new Allegatimultimediali();
        $this->mod_log=new LogPersonal($request);
    }
    
    /**
    *
    * Elenca tutte le storie presenti nel sistema a seconda dei parametri
    *   @param $zid id della zoonosi
    * @return view
    *
    */
    public function elencostorie($slugzoonosi=''){
        //Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] elencostorie', $this->mod_log->getParamFrontoffice());
        $title_page='Elenco storie';
        $where=$whereand=$whereor=$wherenot=$wheresame=[];
        $order=[];
        //se il tipo di chiamata è un POST significa che la richiesta proviene dal form di ricerca
        if($this->request->isMethod('post')){
            Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] elencostorie', $this->mod_log->getParamFrontoffice('inviato post di ricerca'));
            if($this->checkSearchform()){
                //$this->request->session()->flash('messageinfo', '<b>DATI CORRETTI</b>');
                $request_post=$this->request->all();
                $this->setParametriRicerca($where,$whereand,$whereor,$wherenot,$wheresame);
            }else{
                //campi inseriti nel form di ricerca non validi
                Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->error('[OUT] checkSearchform', $this->mod_log->getParamFrontoffice('parametri di ricerca non validi'));
                $this->request->session()->flash('messagedanger', '<b>ATTENZIONE</b>! I parametri inseriti non sono corretti, riprovare!');
                return redirect('/ricerca');
            }
        }elseif($this->request->isMethod('get')){
            Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] elencostorie', $this->mod_log->getParamFrontoffice('visualizzazione storie da zoonosi'));
            //se è fatta richiesta di un esplicito elenco di storie a partire da una zoonosi
            if(preg_match('/^[a-z0-9]+(-?[a-z0-9]+)*$/', $slugzoonosi)){
                //$where[]=['s.zid',$zid];
                $where[]=['zl.slugzoonosi',$slugzoonosi];
            }else{
                //elenco di tutte le storie presenti nel sistema
            }
        }
        
        //solo storie pubblicate
        $where[]=['s.stato',2];
        $order['s.data_pubblicazione']='DESC';
        $storie=$this->mod_storie->getStorie($where,$whereand,$whereor,$wherenot,$wheresame,$order)->toArray();
        if(count($storie)>0){
            //ordinamento storie per zoonosi
            usort($storie, function($a, $b) {
                return $a->zid <=> $b->zid;
            });
        }
        
        if($this->request->isMethod('post') && count($storie)==0){
            Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->warning('[OUT] elencostorie', $this->mod_log->getParamFrontoffice('ricerca senza risultati'));
            $this->request->session()->flash('messagedanger', '<h2>LA RICERCA NON HA FORNITO ALCUN RISULTATO</h2>');
            return redirect('/ricerca');
        }
        $arr_st=[];
        foreach ($storie AS $story)
            $arr_st[$story->nome_zoonosi]=$story->nome_zoonosi;
        $listastorie=implode(', ',$arr_st);

        return view('elencostorie')->with('storie',$storie)
                ->with('title_page',$title_page)
                ->with('og_url',$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])
                ->with('og_title','Elenco storie')
                ->with('art_title','Elenco storie')
                ->with('og_description','Risultati ricerca storie '.$listastorie)
                ->with('art_description','Risultati ricerca storie '.$listastorie);
    }
    
    private function checkSearchform(){
        $request_post=$this->request->all();
        Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] checkSearchform', $this->mod_log->getParamFrontoffice());
        if($request_post['valorericerca1']!=''){
            switch($request_post['tiporicerca1']){
                case 'Autore':
                case 'Titolo':
                case 'Abstract':
                case 'Testo':
                    break;
                default:
                    return false;
                    break;
            }
            switch($request_post['valoreoperazione1']){
                case 'AND':
                case 'OR':
                case 'AND_NOT':
                case 'EXACT':
                    break;
                default:
                    return false;
                    break;
            }
        }
        if($request_post['valorericerca2']!=''){
            switch($request_post['tiporicerca2']){
                case 'Autore':
                case 'Titolo':
                case 'Abstract':
                case 'Testo':
                    break;
                default:
                    return false;
                    break;
            }
            switch($request_post['valoreoperazione2']){
                case 'AND':
                case 'OR':
                case 'AND_NOT':
                case 'EXACT':
                    break;
                default:
                    return false;
                    break;
            }
        }
        if($request_post['valorericerca3']!=''){
            switch($request_post['tiporicerca3']){
                case 'Autore':
                case 'Titolo':
                case 'Abstract':
                case 'Testo':
                    break;
                default:
                    return false;
                    break;
            }
            switch($request_post['valoreoperazione3']){
                case 'AND':
                case 'OR':
                case 'AND_NOT':
                case 'EXACT':
                    break;
                default:
                    return false;
                    break;
            }
        }
        if($request_post['valorericerca4']!=''){
            switch($request_post['tiporicerca4']){
                case 'Autore':
                case 'Titolo':
                case 'Abstract':
                case 'Testo':
                    break;
                default:
                    return false;
                    break;
            }
            switch($request_post['valoreoperazione4']){
                case 'AND':
                case 'OR':
                case 'AND_NOT':
                case 'EXACT':
                    break;
                default:
                    return false;
                    break;
            }
        }
        if($request_post['zoonosi']!='' && !preg_match('/^[1-9][0-9]*$/',$request_post['zoonosi']))
            return false;
        
        //"PAESE" non è stato ancora inserito in query
        if($request_post['paese']!='IT')return false;
        
        if($request_post['data_dal']!=''){
            try {
                Carbon::parse(str_replace('/', '-', $request_post['data_dal']));
            } catch (\Carbon\Exceptions\InvalidFormatException $e) {
                echo $e->getMessage();exit;
                return false;
            }
        }
        if($request_post['data_al']!=''){
            try {
                Carbon::parse(str_replace('/', '-', $request_post['data_al']));
            } catch (\Carbon\Exceptions\InvalidFormatException $e) {
                echo $e->getMessage();exit;
                return false;
            }
        }
        return true;
    }
    
    private function setParametriRicerca(&$where,&$whereand,&$whereor,&$wherenot,&$wheresame){
        $request_post=$this->request->all();
        //$where[]=['s.stato',2];
        if($request_post['data_dal']!='')$where[]=['s.anno_ambientazione','>=',Carbon::createFromFormat('d/m/Y', $request_post['data_dal'])->format('Y')];
        if($request_post['data_al']!='')$where[]=['s.anno_ambientazione','<=',Carbon::createFromFormat('d/m/Y', $request_post['data_al'])->format('Y')];
        if($request_post['zoonosi']!='')$where[]=['s.zid',(int)$request_post['zoonosi']];

        for($i=1;$i<=4;$i++){
            $valori=[];
            unset($tk);
            if($request_post['valorericerca'.$i]!=''){
                if($request_post['valoreoperazione'.$i]=='EXACT'){
                    $parola=trim(strtolower(htmlentities($request_post['valorericerca'.$i],ENT_QUOTES,'UTF-8')));
                    $wheresame[$i][]=[$request_post['tiporicerca'.$i],'LIKE',$parola];
                }else{
                    $valori=explode(' ',trim(strtolower(htmlentities($request_post['valorericerca'.$i],ENT_QUOTES,'UTF-8'))));
                    //echo '<pre>';print_r($valori);exit;
                    foreach ($valori AS $tk=>$parola){
                        switch($request_post['valoreoperazione'.$i]){
                            case 'AND':
                                $whereand[$i][]=[$request_post['tiporicerca'.$i],'LIKE','%'.$parola.'%'];
                                break;
                            case 'OR':
                                $whereor[$i][]=[$request_post['tiporicerca'.$i],'LIKE','%'.$parola.'%'];
                                break;
                            case 'AND_NOT':
                                $wherenot[$i][]=[$request_post['tiporicerca'.$i],'NOT LIKE','%'.$parola.'%'];
                                break;
                            case 'EXACT':
                                $wheresame[$i][]=[$request_post['tiporicerca'.$i],'LIKE',$parola];
                                break;
                            default:
                                return false;
                                break;
                        }
                    } 
                }
                
            } 
        }
    }
    /**
    *
    * Pagina di atterragio con form di ricerca
    * @return view
    *
    */  
    public function ricerca(){
        Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] ricerca', $this->mod_log->getParamFrontoffice());

        $title_page='Ricerca storie';
        $order=[];
        $order['zu.nome']='ASC';
        $zoonosi=$this->mod_home->getZoonosi('',$order);
        
        return view('ricercastorie')->with('title_page',$title_page)->with('zoonosi',$zoonosi)
                ->with('og_url',$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])
                ->with('og_title','Motore di ricerca')
                ->with('art_title','Motore di ricerca')
                ->with('og_description','Motore di ricerca storie di zoonosi')
                ->with('art_description','Motore di ricerca storie di zoonosi');
    }
    
    /**
    *
    * Elenca tutte le storie presenti nel sistema a seconda dei parametri
    *   @param $sid id della storia 
    * @return view
    *
    */
    public function dettagliostoria($slug=''){
        Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] dettagliostoria', $this->mod_log->getParamFrontoffice());
        //echo '<pre>';print_r($_SERVER);exit;
        $storia=[];
        //$storia=$this->mod_storie->getStoria($sid)->toArray();
        $storia=$this->mod_storie->getStoriaFromSlug($slug)->toArray();
        if(count($storia)==0){
            return back()->withInput()->with('messageinfo', 'Storia non trovata.');
        }
        
        $collaboratori=[];
        $collaboratori=$this->mod_storie->getCollaboratoriStoria($storia[0]->sid,1)->toArray();
        
        $fasi=[];
        $fasi=$this->mod_storie->getFasiStoria($storia[0]->sid)->toArray();
        $approfondimenti=[];
        $approfondimenti_genitori_tmp=[];
        $approfondimenti_genitori=[];
        $approfondimenti_figli_tmp=[];
        $approfondimenti_figli=[];
        $numero_approfondimenti_fasi=[];
        if(is_array($fasi) && count($fasi)>0){
            $elencofasi=[];
            //estrazione approfondimenti
            foreach ($fasi AS $kf=>$fase)
                $elencofasi[]=$fase->sfid;
         
            $approfondimenti=$this->mod_storie->getApprofondimentiFasi($elencofasi,[1])->toArray();
            //echo '<pre>';print_r($approfondimenti);exit;
            if(is_array($approfondimenti) && count($approfondimenti)>0){
                
                //creo arrary soli genitori o solo figli
                foreach ($approfondimenti AS $ka=>$approfondimento){
                    if(array_key_exists($approfondimento->sfid, $numero_approfondimenti_fasi))
                        $numero_approfondimenti_fasi[$approfondimento->sfid]+=1;
                    else
                        $numero_approfondimenti_fasi[$approfondimento->sfid]=1;
                    if(is_numeric($approfondimento->said_genitore))
                        $approfondimenti_figli_tmp[]=$approfondimento;
                    else
                        $approfondimenti_genitori_tmp[]=$approfondimento;
                }
                
                //preparo l'array dei figli
                if(is_array($approfondimenti_figli_tmp) && count($approfondimenti_figli_tmp)>0)
                    foreach ($approfondimenti_figli_tmp AS $afk=>$figlio)
                        $approfondimenti_figli[$figlio->said_genitore][]=$figlio;
                //preparo l'array dei genitori 
                if(is_array($approfondimenti_genitori_tmp) && count($approfondimenti_genitori_tmp)>0)
                    foreach ($approfondimenti_genitori_tmp AS $afg=>$genitore)
                        $approfondimenti_genitori[$genitore->sfid][]=$genitore;
            }
        }
       
        //metadati per articolo
        $this->setMetadatiStoria($storia[0],$collaboratori);
        
        $whereallegati1=$whereallegati2=[];
        $podcast=new \Illuminate\Support\Collection();
        $video=new \Illuminate\Support\Collection();
        $pdfstoria=new \Illuminate\Support\Collection();
        $whereallegati1[]=['ams.tipologia',1]; //AUDIO/PODCAST
        $podcast=$this->mod_allegatimultimediali->getAllegatiMultimedialiFromStoria($storia[0]->sid,$whereallegati1);
        $whereallegati2=[2,5]; //VIDEO
        $video=$this->mod_allegatimultimediali->getAllegatiMultimedialiFromStoria($storia[0]->sid,array(),$whereallegati2);
        $whereallegati3[]=['ams.tipologia',6]; //PDF
        $pdfstoria=$this->mod_allegatimultimediali->getAllegatiMultimedialiFromStoria($storia[0]->sid,$whereallegati3);
                
        $snippets=new \Illuminate\Support\Collection();
        $snippets=$this->mod_storie->getSnippetsFromStoria($storia[0]->sid)->toArray();
        $snippetfase=[];
        if(count($snippets)>0){
            foreach ($snippets AS $snippet){
                $snippetfase[$snippet->sfid][]=$snippet;
            }
        }
        
        return view('storia')->with('title_page',$storia[0]->titolo)->with('storia',$storia[0])->with('collaboratori',$collaboratori)->with('fasi',$fasi)
                ->with('approfondimenti',$approfondimenti)
                ->with('approfondimenti_figli',$approfondimenti_figli)
                ->with('approfondimenti_genitori',$approfondimenti_genitori)
                ->with('numero_approfondimenti_fasi',$numero_approfondimenti_fasi)
                ->with('snippetfase',$snippetfase)
                ->with('video',$video)->with('podcast',$podcast)->with('pdfstoria',$pdfstoria)
                ->with('og_url',$this->og_url)->with('og_title',$this->og_title)->with('og_description',$this->og_description)->with('og_type',$this->og_type)
                ->with('art_type_dc',$this->art_type_dc)
                ->with('art_author',$this->art_author)->with('art_title',$this->art_title)->with('art_description',$this->art_description)->with('art_abstract',$this->art_abstract)
                ->with('art_datapublic',$this->art_datapublic);
    }
    
    private function setMetadatiStoria($storia,$collaboratori=''){
        $this->og_url=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $this->og_title=$storia->titolo;
        $this->og_description=$storia->descrizione;
        
        $this->art_title=$storia->titolo;
        $this->art_description=$storia->descrizione;
        $this->art_abstract=$storia->abstract;
        $this->art_datapublic=$storia->data_pubblicazione;
        $this->art_publisher=$storia->editore;
        $this->art_type_dc='Text';
        if(isset($collaboratori) && count($collaboratori)>0)
            $this->art_author=$collaboratori[0]->nome.' '.$collaboratori[0]->cognome;
        return;
    }
    
    public function getdaticontestostoria(){
        Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] getdaticontestostoria', $this->mod_log->getParamFrontoffice());
        if(preg_match('/^[1-9][0-9]*$/',$this->request->sid)){
            $quesiti=[];
            $quesiti=$this->mod_storie->getDaticontestoFromStoria($this->request->sid);
            
            //concludere parte
            $quesiti=$quesiti->toArray();
            if(is_array($quesiti) && count($quesiti)>0){
                foreach ($quesiti AS $quesito){
                    $quesito->risposta=html_entity_decode($quesito->risposta,ENT_QUOTES,'utf-8');
                }
            }
            Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[OUT] getdaticontestostoria', $this->mod_log->getParamFrontoffice());
            return response()->json(['error'=>false,'message'=>'','quesiti'=>$quesiti]);
        }else{
            Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->error('[OUT] getdaticontestostoria', $this->mod_log->getParamFrontoffice('errore richiesta dati di contesto storia'));
            return response()->json(['error'=>true,'message'=>'Errore. Impossibile estrarre i dati di contesto. Riprovare']);
        }
    }
    
    public function getreviewzoonosi(){
        Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] getreviewzoonosi', $this->mod_log->getParamFrontoffice());
        if(preg_match('/^[1-9][0-9]*$/',$this->request->zid)){
            $reviews=[];
            $urldownloadpdf='';
            $reviews=$this->mod_storie->getReviewsFromZoonosi($this->request->zid)->toArray();
            //preparare URL download PDF
            if(count($reviews)>0)
                $urldownloadpdf=url('storagereview/'.$reviews[0]->zid.'/'.$reviews[0]->file_memorizzato.'/'.$reviews[0]->titolo_visualizzato);              
            Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[OUT] getreviewzoonosi', $this->mod_log->getParamFrontoffice());
            return response()->json(['error'=>false,'message'=>'','reviews'=>$reviews,'urldown'=>$urldownloadpdf]);
        }else{
            Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->error('[OUT] getreviewzoonosi', $this->mod_log->getParamFrontoffice('errore richiesta review'));
            return response()->json(['error'=>true,'message'=>'Errore. Impossibile estrarre le reviews della storia. Riprovare']);
        }
    }
    
    public function getsnippet(){
        Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] getsnippet', $this->mod_log->getParamFrontoffice());
        if(preg_match('/^[1-9][0-9]*$/',$this->request->snid)){
            $snippet=array();
            $snippet=$this->mod_storie->getSnippet($this->request->snid);
            $snippet=$snippet->toArray();
            if(is_array($snippet) && count($snippet)>0){
                $snippet=$snippet[0];
                $snippet->testo= html_entity_decode($snippet->testo,ENT_QUOTES,'utf-8');
            }
            Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[OUT] getsnippet', $this->mod_log->getParamFrontoffice());
            return response()->json(['error'=>false,'message'=>'','snippet'=>$snippet]);
        }else{
            Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->error('[OUT] getsnippet', $this->mod_log->getParamFrontoffice('errore richiesta snippet'));
            return response()->json(['error'=>true,'message'=>'Errore. Impossibile estrarre i dati dello snippet. Riprovare']);
        }
    }
    
    public function segnalastoria(){
        Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] segnalastoria', $this->mod_log->getParamFrontoffice());
        $title_page='Crowdsourcing storie';
        $datisubmit=[];
        //se post, controllo dati provenienti dal form
        if($this->request->isMethod('post')){
            Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] segnalastoria', $this->mod_log->getParamFrontoffice('invio post della storia'));
            $datisubmit=$this->request->all();
            if($this->checkSubmissionform() && Auth::check()){
                Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->info('[IN] segnalastoria', $this->mod_log->getParamFrontoffice('form valido ed utente autenticato'));
                //memorizzazione storia + file
                $files_video=$files_immagini=[];
                $request_post=$this->request->all();
                $lang=($request_post['language']=='EN')?'_en':'';
                $file_testo = $this->request->file('filetesto'.$lang);
                $file_audio = $this->request->file('fileaudio'.$lang);
                $files_video = $this->request->file('filevideo'.$lang);
                $files_immagini = $this->request->file('fileimmagini'.$lang);
              
                DB::beginTransaction();
                try {
                    Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->critical('[IN TRY] segnalastoria', $this->mod_log->getParamFrontoffice());
                    //memo storia        
                    $storia = new Storie();
                    $storia->data_inserimento = 'NOW()';
                    $storia->data_lavorazione = NULL;
                    $storia->data_pubblicazione = NULL;
                    $storia->anno_ambientazione = trim(htmlentities($request_post['annoambientazione'.$lang],ENT_QUOTES,'utf-8'));
                    $storia->stato = 0;
                    $storia->copyright = '';
                    $storia->editore = '';
                    $storia->linktelegram = '';
                    $storia->zid = NULL;
                    $storia->uid=Auth::id();
                    $storia->linkzoodiac = '';
                    $storia->linkspvet = '';
                    $storia->save();
                    
                    //creazione record per dati inseriti dall'utente per la storia
                    $storiasubmit = new Storiesubmit();
                    $storiasubmit->titolo_inserito = trim(htmlentities($request_post['titolo'.$lang],ENT_QUOTES,'utf-8'));
                    $storiasubmit->tipozoonosi_inserito = trim(htmlentities($request_post['zoonosi'.$lang],ENT_QUOTES,'utf-8'));
                    $storiasubmit->annoambientazione_inserito = trim(htmlentities($request_post['annoambientazione'.$lang],ENT_QUOTES,'utf-8'));
                    $storiasubmit->brevedescrizione_inserita = trim(htmlentities($request_post['descrizionebreve'.$lang],ENT_QUOTES,'utf-8'));
                    $storiasubmit->ruolo_inserito = trim(htmlentities($request_post['ruolo'.$lang],ENT_QUOTES,'utf-8'));
                    $storiasubmit->noteaggiuntive_inserite = trim(htmlentities($request_post['noteaggiuntive'.$lang],ENT_QUOTES,'utf-8'));
                    $storiasubmit->lingua_inserita = $request_post['language'];
                    $storiasubmit->uid = Auth::id();
                    $storiasubmit->sid = $storia->sid;
                    $storiasubmit->save();
                    
                    //set privacy policy presa visione
                    $this->mod_privacy->setAccettazione(Auth::id(),1);
                    
                    //creazione cartella per files
                    $path = storage_path('app/storiesubmit/'.$storiasubmit->ssid);
                    File::ensureDirectoryExists($path);
                    
                    $paths=[];
                    //memorizzazione files inseriti alla tabella della storia submit
                    if($_FILES['filetesto'.$lang]['name']!=''){
                        $now = new DateTime();
                        for($i=0;$i<20;$i++){
                            $file_testo_nomecreato='FILETESTO_'.$now->format("YmdHisu").'.'.$file_testo->getClientOriginalExtension();
                            if(!file_exists($path.'/'.$file_testo_nomecreato)){
                                $paths[]=$file_testo->storeAs('/storiesubmit/'.$storiasubmit->ssid,$file_testo_nomecreato ,'local');
                               //memorizzazione record nel db collegandolo alla storia submitted
                               $storiafile = new Storiesubmitfile();
                               $storiafile->ssid=$storiasubmit->ssid;
                               $storiafile->nome_file_memorizzato=$file_testo_nomecreato;
                               $storiafile->nome_file_originale=$file_testo->getClientOriginalName();
                               $storiafile->dataora_inserimento='NOW()';
                               $storiafile->mimetype=$file_testo->getMimeType();
                               $storiafile->save();
                               break;
                            }else{
                                usleep(250000); //un quarto di secondo di attesa
                                $now = new DateTime();
                            }
                        }
                    }
                    if($_FILES['fileaudio'.$lang]['name']!=''){
                        $now = new DateTime();
                        for($i=0;$i<20;$i++){
                            $file_audio_nomecreato='FILEAUDIO_'.$now->format("YmdHisu").'.'.$file_audio->getClientOriginalExtension();
                            if(!file_exists($path.'/'.$file_audio_nomecreato)){
                               $paths[]=$file_audio->storeAs('/storiesubmit/'.$storiasubmit->ssid, $file_audio_nomecreato ,'local');
                               
                               //memorizzazione record nel db collegandolo alla storia submitted
                               $storiafile = new Storiesubmitfile();
                               $storiafile->ssid=$storiasubmit->ssid;
                               $storiafile->nome_file_memorizzato=$file_audio_nomecreato;
                               $storiafile->nome_file_originale=$file_audio->getClientOriginalName();
                               $storiafile->dataora_inserimento='NOW()';
                               $storiafile->mimetype=$file_audio->getMimeType();
                               $storiafile->save();
                               break;
                            }else{
                                usleep(250000); //un quarto di secondo di attesa
                                $now = new DateTime();
                            }
                        }
                    }
                    if($this->request->hasFile('filevideo'.$lang)){
                        foreach ($files_video AS $file_video) {
                            $now = new DateTime();
                            for($i=0;$i<20;$i++){
                                $file_video_nomecreato='FILEVIDEO_'.$now->format("YmdHisu").'.'.$file_video->getClientOriginalExtension();
                                if(!file_exists($path.'/'.$file_video_nomecreato)){
                                   $paths[]=$file_video->storeAs('/storiesubmit/'.$storiasubmit->ssid, $file_video_nomecreato ,'local');

                                   //memorizzazione record nel db collegandolo alla storia submitted
                                   $storiafile = new Storiesubmitfile();
                                   $storiafile->ssid=$storiasubmit->ssid;
                                   $storiafile->nome_file_memorizzato=$file_video_nomecreato;
                                   $storiafile->nome_file_originale=$file_video->getClientOriginalName();
                                   $storiafile->dataora_inserimento='NOW()';
                                   $storiafile->mimetype=$file_video->getMimeType();
                                   $storiafile->save();
                                   break;
                                }else{
                                    usleep(250000); //un quarto di secondo di attesa
                                    $now = new DateTime();
                                }
                            }
                        }
                    }
                    if($this->request->hasFile('fileimmagini'.$lang)){
                        foreach ($files_immagini AS $file_immagini) {
                            $now = new DateTime();
                            for($i=0;$i<20;$i++){
                                $file_immagini_nomecreato='FILEIMMAGINI_'.$now->format("YmdHisu").'.'.$file_immagini->getClientOriginalExtension();
                                if(!file_exists($path.'/'.$file_immagini_nomecreato)){
                                   $paths[]=$file_immagini->storeAs('/storiesubmit/'.$storiasubmit->ssid, $file_immagini_nomecreato ,'local');

                                   //memorizzazione record nel db collegandolo alla storia submitted
                                   $storiafile = new Storiesubmitfile();
                                   $storiafile->ssid=$storiasubmit->ssid;
                                   $storiafile->nome_file_memorizzato=$file_immagini_nomecreato;
                                   $storiafile->nome_file_originale=$file_immagini->getClientOriginalName();
                                   $storiafile->dataora_inserimento='NOW()';
                                   $storiafile->mimetype=$file_immagini->getMimeType();
                                   $storiafile->save();
                                   break;
                                }else{
                                    usleep(250000); //un quarto di secondo di attesa
                                    $now = new DateTime();
                                }
                            }
                        }
                    }
                    
                    //invio email verso amministratore
                    $datimail=array('titolostoria'=>$storiasubmit->titolo_inserito,'nomeutente'=>Auth::user()->name,'emailutente'=>Auth::user()->email,'nome_sito'=>env('NOME_SITO'));
                    Mail::send('emails.newstoria_amminsitratore', $datimail, function($message){
                        $message->subject('Nuova storia inserita');
                        $message->to('e.rivosecchi@izsum.it');
                        $message->cc('r.ciappelloni@izsum.it');
                    });
                            
                    //inserire email verso utente
                    $datimail=array('nome_sito'=>env('NOME_SITO'));
                    Mail::send('emails.newstoria', $datimail, function($message){
                        $message->subject('Nuova storia inserita');
                        $message->to(Auth::user()->email);
                    });
                    
                    DB::commit();
                    //testare file video ancora più grande per timeout
                    Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->critical('[OUT TRY] segnalastoria', $this->mod_log->getParamFrontoffice());
                    //redirect a pagina con messaggio di compiuto invio
                    $this->request->session()->flash('messageinfo', '<h2>Storia inoltrata con successo!</h2><h3>Riceverai ulteriori email sullo stato di avanzamento della storia inviata.</h3>');   
                    return redirect('/');
                } catch (Throwable $e) {
                    DB::rollBack();
                    Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->error('[OUT] segnalastoria', $this->mod_log->getParamFrontoffice($e->getMessage()));
                    echo $e->getMessage();
                    exit;
                }
            }else{
                Log::build(['driver' => 'single','path' => storage_path('logs/front.log')])->error('[OUT] segnalastoria', $this->mod_log->getParamFrontoffice($e->getMessage('dati del form non corretti')));
                $this->request->session()->flash('formerrato', '<h2>Dati non corretti</h2>'."<br />".$this->erroriFormSubmission);
            }
        }
        
        $privacy_policy=$this->mod_privacy->getPrivacyAttuale();
        return view('segnalastoria')->with('privacy_policy',$privacy_policy)->with('title_page',$title_page)->with('datapost',$datisubmit)
                ->with('og_url',$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])
                ->with('og_title','Sottomissione nuova storia')
                ->with('art_title','Sottomissione nuova storia')
                ->with('og_description','Compilare il form per sottomettere a revisione una nuova storia di zoonosi')
                ->with('art_description','Compilare il form per sottomettere a revisione una nuova storia di zoonosi');
    }
    
    private function checkSubmissionform(){
        $request_post=$this->request->all();
        $lang=($request_post['language']=='EN')?'_en':'';
        $datimancanti=[];
        $allowed_text = array('doc','docx','rtf','txt','pdf');
        $allowed_video = array('mp4','mov','avi');
        $allowed_image = array('jpg','png', 'gif', 'svg');
        $allowed_audio = array('pcm', 'wav', 'mp3', 'ogg', 'flac');
        
        $file_testo = $this->request->file('filetesto'.$lang);
        $file_audio = $this->request->file('fileaudio'.$lang);
        $files_video = $this->request->file('filevideo'.$lang);
        $files_immagini = $this->request->file('fileimmagini'.$lang);
        
        switch($lang){
            case '_en':
                if(!$request_post['titolo'.$lang])$datimancanti[]='Missing title';
                if(!$request_post['zoonosi'.$lang])$datimancanti[]='Missing zoonosis';
                if(!$request_post['annoambientazione'.$lang])$datimancanti[]='Missing year';
                if(!$request_post['descrizionebreve'.$lang])$datimancanti[]='Missing short description';
                if(!preg_match('/[0-9]+/', $request_post['annoambientazione'].$lang))$datimancanti[]='Invalid year selected';
                
                //controlli sui file testo
                if($_FILES['filetesto'.$lang]['name']!=''){
                    if(isset($_FILES['filetesto'.$lang]) && $_FILES['filetesto'.$lang]['error']!=0)$datimancanti[]='Error loading the text file <<'.$_FILES['filetesto'.$lang]['name'].'>>';
                    $ext_text = pathinfo($_FILES['filetesto'.$lang]['name'], PATHINFO_EXTENSION);
                    if (!in_array($ext_text, $allowed_text)) {$datimancanti[]='Invalid text file format';}
                    if($_FILES['filetesto'.$lang]['size']>10485760){$datimancanti[]='Text file too large';}
                }
                //controlli sui file audio
                if($_FILES['fileaudio'.$lang]['name']!=''){
                    if($_FILES['fileaudio'.$lang]['error']!=0)$datimancanti[]='Error loading the audio file <<'.$_FILES['fileaudio'.$lang]['name'].'>>';
                    $ext_audio = pathinfo($_FILES['fileaudio'.$lang]['name'], PATHINFO_EXTENSION);
                    if (!in_array($ext_audio, $allowed_audio)) {$datimancanti[]='Invalid audio file format';}
                    if($_FILES['fileaudio'.$lang]['size']>20971520){$datimancanti[]='Audio file too large';}
                }
                //controlli sui file video
                if(count($_FILES['filevideo'.$lang]['name'])>2){
                    $datimancanti[]='Exceeded the maximum allowed number of video files';
                }elseif($_FILES['filevideo'.$lang]['name'][0]!=''){
                    foreach ($_FILES['filevideo'.$lang]['error'] AS $kve=>$errv)
                        if($errv!=0)$datimancanti[]='Error loading the video file <<'.$_FILES['filevideo'.$lang]['name'][$kve].'>>';
                    /*foreach ($_FILES['filevideo'.$lang]['name'] AS $kvex=>$namev){
                        $ext_video = pathinfo($namev, PATHINFO_EXTENSION);
                        if (!in_array($ext_video, $allowed_video)) {$datimancanti[]='Invalid video file format for <<'.$_FILES['filevideo'.$lang]['name'][$kvex].'>>';}
                    }*/
                    if($this->request->hasFile('filevideo'.$lang)){
                        foreach ($files_video AS $file_video) {
                            if(!in_array(strtolower($file_video->getClientOriginalExtension()),$allowed_video))
                                    $datimancanti[]='Invalid video file format for <<'.$file_video->getClientOriginalName().'>>';
                        }
                    }    
                    foreach ($_FILES['filevideo'.$lang]['size'] AS $kvs=>$szv)
                        if($szv>419430400)$datimancanti[]='Video file too large <<'.$_FILES['filevideo'.$lang]['name'][$kvs].'>>';
                }
                //controlli sui file immagini
                if(count($_FILES['fileimmagini'.$lang]['name'])>10){
                    $datimancanti[]='Exceeded the maximum allowed number of images files';
                }elseif($_FILES['fileimmagini'.$lang]['name'][0]!=''){
                    foreach ($_FILES['fileimmagini'.$lang]['error'] AS $kie=>$erri)
                        if($erri!=0)$datimancanti[]='Error loading the image file <<'.$_FILES['fileimmagini'.$lang]['name'][$kie].'>>';
                    
                    if($this->request->hasFile('fileimmagini'.$lang)){
                        foreach ($files_immagini AS $file_immagini) {
                            if(!in_array(strtolower($file_immagini->getClientOriginalExtension()),$allowed_image))
                                    $datimancanti[]='Invalid image file format for <<'.$file_immagini->getClientOriginalName().'>>';
                        }
                    }     
                    /*foreach ($_FILES['fileimmagini'.$lang]['name'] AS $kiex=>$namei){
                        $ext_image = pathinfo($namei, PATHINFO_EXTENSION);
                        if (!in_array($ext_image, $allowed_image)) {$datimancanti[]='Invalid image file format for <<'.$_FILES['fileimmagini'.$lang]['name'][$kiex].'>>';}
                    }*/
                    foreach ($_FILES['fileimmagini'.$lang]['size'] AS $kis=>$szi)
                        if($szi>4718592)$datimancanti[]='Image file too large <<'.$_FILES['fileimmagini'.$lang]['name'][$kis].'>>';
                }
                break;
            default:
                if(!$request_post['titolo'])$datimancanti[]='Titolo mancante';
                if(!$request_post['zoonosi'])$datimancanti[]='Zoonosi mancante';
                if(!$request_post['annoambientazione'])$datimancanti[]='Anno ambientazione mancante';
                if(!$request_post['descrizionebreve'])$datimancanti[]='Breve descrizione mancante';
                if(!preg_match('/[0-9]+/', $request_post['annoambientazione']))$datimancanti[]='Anno ambientazione non valido';
                
                //controlli sui file testo
                if($_FILES['filetesto']['name']!=''){
                    if($_FILES['filetesto']['error']!=0)$datimancanti[]='Errore di carimento per il file di testo <<'.$_FILES['filetesto']['name'].'>>';
                    $ext_text = pathinfo($_FILES['filetesto']['name'], PATHINFO_EXTENSION);
                    if (!in_array($ext_text, $allowed_text)) {$datimancanti[]='Formato del file di testo non valido';}
                    if($_FILES['filetesto']['size']>10485760){$datimancanti[]='File di testo troppo grande';}
                }
                //controlli sui file audio
                if($_FILES['fileaudio']['name']!=''){
                    if($_FILES['fileaudio']['error']!=0)$datimancanti[]='Errore di carimento per il file audio <<'.$_FILES['fileaudio']['name'].'>>';
                    $ext_audio = pathinfo($_FILES['fileaudio']['name'], PATHINFO_EXTENSION);
                    if (!in_array($ext_audio, $allowed_audio)) {$datimancanti[]='Formato del file audio non valido';}
                    if($_FILES['fileaudio']['size']>20971520){$datimancanti[]='File audio troppo grande';}
                }
                //controlli sui file video
                if(count($_FILES['filevideo']['name'])>2){
                    $datimancanti[]='Superato il massimo numero consentito di file video';
                }elseif($_FILES['filevideo']['name'][0]!=''){
                    foreach ($_FILES['filevideo']['error'] AS $kve=>$errv)
                        if($errv!=0)$datimancanti[]='Errore di carimento per il file video <<'.$_FILES['filevideo']['name'][$kve].'>>';
                    if($this->request->hasFile('filevideo'.$lang)){
                        foreach ($files_video AS $file_video) {
                            if(!in_array(strtolower($file_video->getClientOriginalExtension()),$allowed_video))
                                    $datimancanti[]='Formato non valido per il  il file video <<'.$file_video->getClientOriginalName().'>>';
                        }
                    }    
                    foreach ($_FILES['filevideo']['size'] AS $kvs=>$szv)
                        if($szv>419430400)$datimancanti[]='File video troppo grande <<'.$_FILES['filevideo']['name'][$kvs].'>>';
                }
                //controlli sui file immagini
                if(count($_FILES['fileimmagini']['name'])>10){
                    $datimancanti[]='Superato il massimo numero consentito di file immagine';
                }elseif($_FILES['fileimmagini']['name'][0]!=''){
                    foreach ($_FILES['fileimmagini']['error'] AS $kie=>$erri)
                        if($erri!=0)$datimancanti[]='Errore di carimento per il file immagine <<'.$_FILES['fileimmagini']['name'][$kie].'>>';
                        
                    if($this->request->hasFile('fileimmagini'.$lang)){
                        foreach ($files_immagini AS $file_immagini) {
                            if(!in_array(strtolower($file_immagini->getClientOriginalExtension()),$allowed_image))
                                    $datimancanti[]='Formato non valido per il file immagini <<'.$file_immagini->getClientOriginalName().'>>';
                        }
                    }    
                    foreach ($_FILES['fileimmagini']['size'] AS $kis=>$szi)
                        if($szi>4718592)$datimancanti[]='Immagine troppo grande <<'.$_FILES['fileimmagini']['name'][$kis].'>>';
                }
                break;
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
  
}
