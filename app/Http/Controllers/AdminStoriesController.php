<?php
/*
 * Italian Ministry of Health Research Project: MEOH/2021-2022 - IZS UM 04/20 RC
 * Created on 2023
 * @author Eros Rivosecchi <e.rivosecchi@izsum.it>
 * @author IZSUM Sistema Informatico <sistemainformatico@izsum.it>
 * 
 * @license 
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at

 * http://www.apache.org/licenses/LICENSE-2.0

 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 * 
 * @version 1.0
 */

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
use App\Models\Stories;
use App\Models\Zoonoses;
use App\Models\Storiessubmit;
use App\Models\Storiessubmitfile;
use App\Models\Collaborators;
use App\Models\Ruoli;
Use App\Models\Multimediaelements;
Use App\Models\Storiesphases;
Use App\Models\Snippets;
use App\Models\Integrations;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\LogPersonal;

use DateTime;
use DB;
use Carbon\Carbon;

/**
 * Manages all the functions that an administrator 
 * can use regarding the stories that are uploaded to the system
 * 
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version Release: 1.0
 * @since   Class available since Release 1.0
 * 
 */
class AdminStoriesController extends Controller
{
    public $mod_stories;
    public $mod_storiessubmit;
    private $request;
    public $menuactive='storie';
    public $errorsFormSubmission='';
    
    public function __construct(Request $request)
    {
        $this->request=$request;
        $this->mod_stories = new Stories();
        $this->mod_storiessubmit = new Storiessubmit();
        $this->mod_storiessubmitfile = new Storiessubmitfile();
        $this->mod_zoonosi = new Zoonoses();    
        $this->mod_collaborators = new Collaborators();    
        $this->mod_ruoli = new Ruoli();    
        $this->mod_multimediaelements= new Multimediaelements();
        $this->mod_collaborators=new Collaborators();
        $this->mod_storiesphases=new Storiesphases();
        $this->mod_snippets=new Snippets();
        $this->mod_integrations=new Integrations();
        $this->mod_log=new LogPersonal($request);
    }
    
    /**
    *
    * List all the stories in the system
    *   
    * @return \Illuminate\Http\Response
    *
    */
    public function list(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] list', $this->mod_log->getParamFrontoffice());
        if(auth()->guard('admin')->user()->role!=='admin'){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->warning('[IN] list', $this->mod_log->getParamFrontoffice('ruolo non ammesso'));
            return redirect('/admin');
        }
        $title_page='Elenco storie';
        $where=$whereand=$whereor=$wherenot=$wheresame=[];
        $order=[];
        $order['s.data_inserimento']='DESC';
        $storie=$this->mod_stories->getAll([],$order);
        return view('admin.stories.list')->with('storie',$storie)
                ->with([
                    'title_page'=>$title_page,
                    'admin'=>auth()->guard('admin')->user(),
                    'menuactive'=>$this->menuactive,
                ]);
    }
    
    /**
    *
    * Manage the editing and posting page of stories
    * @return \Illuminate\Http\Response
    *
    */
    public function modify(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] modify', $this->mod_log->getParamFrontoffice());
        $title_page='Aggiungi/Modifica Storia';
        $datistoria=[];
        $snippetfase=[];
        $collaboratori=new \Illuminate\Support\Collection();
        $collaboratoristoria=new \Illuminate\Support\Collection();
        if($this->request->isMethod('post')){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] modify', $this->mod_log->getParamFrontoffice('inviato il post della storia'));
            $datistoria=$this->request->all();
            if($this->checkform()){
                Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] modify', $this->mod_log->getParamFrontoffice('post corretto'));
                $request_post=$this->request->all();
                $file_testo = $this->request->file('pdfstoria');
                $file_audio = $this->request->file('podcast');
                $file_video = $this->request->file('linkvideo');
                $file_imgpredef = $this->request->file('imgpredef');
                DB::beginTransaction();
                try {
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[IN TRY] modify', $this->mod_log->getParamFrontoffice());
                    //memo story      
                    if(!$request_post['sid']){   
                        $storia = new Stories();
                        $storia->data_pubblicazione = NULL;
                        if($request_post['stato']==2)
                            $storia->data_pubblicazione = 'NOW()';
                    }else{
                        $storia = Stories::find($request_post['sid']);
                        $oldstoria=$this->mod_stories->getStory($request_post['sid'])->toArray();
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
                    
                    //memo storyes language
                    $arr_storialingua=[];
                    $arr_storialingua['titolo']=$this->dataready($request_post['titolo']);
                    $arr_storialingua['slug']=$this->dataready($request_post['slug']);
                    $arr_storialingua['abstract']=$this->dataready($request_post['abstract']);
                    $arr_storialingua['descrizione']=$this->dataready($request_post['descrizione']);
                    $this->mod_stories->setStorylanguageAss($storia->sid,$arr_storialingua);
                    
                    /*START memo collaborators*/
                    // 1.empty meoh_storiecollaboratori
                    $this->mod_collaborators->deleteStoriesCollaboratorsAss($storia->sid);
                    // 2.add any collaborators to meoh_stories_collaborators
                    $arr_collaboratori=[];
                    $arr_collaboratori=$request_post['collid'];
                    if(is_array($request_post['collid']) && count($request_post['collid'])>0){
                        foreach ($request_post['collid'] AS $tco=>$collaboratore){
                            if(!is_numeric($collaboratore)){
                                $collab = new Collaborators();
                                $collab->nome = $this->dataready($request_post['nomecollaboratore'][$tco]);
                                $collab->cognome = $this->dataready($request_post['cognomecollaboratore'][$tco]);
                                $collab->save();
                                
                                $arr_collaboratori[$tco]=$collab->collid;
                            }
                        }unset($tco);unset($collaboratore);
                    }
                    // 3.fill meoh_storiecollaboratori
                    $this->mod_collaborators->addCollaboratorsStory($storia->sid,$arr_collaboratori,$request_post['sel_ruolo']);
                    /*END memo collaborators*/
                    
                    //memo multimedia elements
                    $pathst = storage_path('app/public/storieallegatimultimediali/'.$storia->sid);
                    if(!File::isDirectory($pathst)){
                        File::makeDirectory($pathst, 0777, true, true);
                    }

                    //podcast
                    $whereallegati1=[];
                    $oldpodcast=new \Illuminate\Support\Collection();
                    $whereallegati1[]=['ams.tipologia',1]; //AUDIO/PODCAST
                    $oldpodcast=$this->mod_multimediaelements->getMultimediaElementsFromStory($storia->sid,$whereallegati1);
                    $deleteOldPodcast=0;
                    if(isset($_FILES['podcast']['name']) && $_FILES['podcast']['name']!=''){
                        $deleteOldPodcast=1;
                        $originPodcastName = $this->request->file('podcast')->getClientOriginalName();
                        $filePodcastName = pathinfo($originPodcastName, PATHINFO_FILENAME);
                        $extensionPodcast = $this->request->file('podcast')->getClientOriginalExtension();
                        
                        $newPodcastName = 'FILEPODCAST_'.time().'.'.$extensionPodcast;
                        $this->request->file('podcast')->move($pathst, $newPodcastName);
                        $urlPodcast=asset(Storage::url('storieallegatimultimediali/'.$storia->sid.'/'.$newPodcastName));
                 
                        //memo DB
                        $allegatopodcast = new Multimediaelements();
                        $allegatopodcast->nome_file_originale=$originPodcastName;
                        $allegatopodcast->nome_file_memorizzato=$newPodcastName;
                        $allegatopodcast->tipologia=1;
                        $allegatopodcast->sid=$storia->sid;
                        $allegatopodcast->save();
                    }
                    //if new podcast inserted and an old one present || deleted the old podcast
                    if(($deleteOldPodcast==1 && count($oldpodcast->all())>0) ||  ($deleteOldPodcast==0 && count($oldpodcast->all())>0 && !$request_post['filepodcast']) ){
                        foreach ($oldpodcast AS $singlepodcast){
                            //physical removal
                            $pathpod = storage_path('app/public/storieallegatimultimediali/'.$storia->sid.'/'. $singlepodcast->nome_file_memorizzato);
                            Storage::delete($pathpod);
                            
                            //delete record DB
                            Multimediaelements::destroy($singlepodcast->amsid);                            
                        }
                    }
                 
                    //video
                    $oldvideo=new \Illuminate\Support\Collection();
                    $whereallegati2=[2,5]; //VIDEO
                    $oldvideo=$this->mod_multimediaelements->getMultimediaElementsFromStory($storia->sid,array(),$whereallegati2);
                    $deleteOldVideo=0;
                    if(isset($_FILES['linkvideo']['name']) && $_FILES['linkvideo']['name']!=''){
                        $deleteOldVideo=1;
                        $originVideoName = $this->request->file('linkvideo')->getClientOriginalName();
                        $fileVideoName = pathinfo($originVideoName, PATHINFO_FILENAME);
                        $extensionVideo = $this->request->file('linkvideo')->getClientOriginalExtension();
                        
                        $newVideoName = 'FILEVIDEO_'.time().'.'.$extensionVideo;
                        $this->request->file('linkvideo')->move($pathst, $newVideoName);
                        $urlVideo=asset(Storage::url('storieallegatimultimediali/'.$storia->sid.'/'.$newVideoName));
                 
                        //memo DB
                        $allegatovideo = new Multimediaelements();
                        $allegatovideo->nome_file_originale=$this->dataready($originVideoName);
                        $allegatovideo->nome_file_memorizzato=$newVideoName;
                        $allegatovideo->tipologia=2;
                        $allegatovideo->sid=$storia->sid;
                        $allegatovideo->save();
                    }
                    //if a new podcast is inserted and an old one is present || deleted the old video || old video replaced by linkurl / html
                    if(($deleteOldVideo==1 && count($oldvideo->all())>0) ||  ($deleteOldVideo==0 && count($oldvideo->all())>0 && !$request_post['filevideo']) || ($deleteOldVideo==0 && count($oldvideo->all())>0 && $request_post['linkurlhtml']!='')){
                        foreach ($oldvideo AS $singlevideo){
                            //physical removal
                            $pathvid = storage_path('app/public/storieallegatimultimediali/'.$storia->sid.'/'. $singlevideo->nome_file_memorizzato);
                            Storage::delete($pathvid);
                            
                            //erased record DB
                            Multimediaelements::destroy($singlevideo->amsid);                            
                        }
                    }
                    if($request_post['linkurlhtml']!=''){
                        //memo DB
                        $allegatovideo = new Multimediaelements();
                        $allegatovideo->tipologia=5;
                        $allegatovideo->linkurlhtml=$this->dataready($request_post['linkurlhtml']);
                        $allegatovideo->sid=$storia->sid;
                        
                        if(isset($_FILES['imgpredef']['name']) && $_FILES['imgpredef']['name']!=''){
                            $originImgpredefName = $this->request->file('imgpredef')->getClientOriginalName();
                            $fileImgpredefName = pathinfo($originImgpredefName, PATHINFO_FILENAME);
                            $extensionImgpredef = $this->request->file('imgpredef')->getClientOriginalExtension();

                            $newImgpredefName = 'FILEIMGPREDEF_'.time().'.'.$extensionImgpredef;
                            $this->request->file('imgpredef')->move($pathst, $newImgpredefName);
                            $urlImgpredef=asset(Storage::url('storieallegatimultimediali/'.$storia->sid.'/'.$newImgpredefName));
                            
                            $allegatovideo->imgpredef=$newImgpredefName;
                        }elseif($request_post['fileimgpredef']){
                            $allegatovideo->imgpredef=$request_post['fileimgpredef'];
                        }else{
                            $allegatovideo->imgpredef='';
                        }
                        $allegatovideo->save();
                    }
                    
                   
                    
                    //pdf
                    $whereallegati3=[];
                    $oldpdf=new \Illuminate\Support\Collection();
                    $whereallegati3[]=['ams.tipologia',6]; //PDF STORIA
                    $oldpdf=$this->mod_multimediaelements->getMultimediaElementsFromStory($storia->sid,$whereallegati3);
                    $deleteOldPdf=0;
                    if(isset($_FILES['pdfstoria']['name']) && $_FILES['pdfstoria']['name']!=''){
                        $deleteOldPdf=1;
                        $originPdfName = $this->request->file('pdfstoria')->getClientOriginalName();
                        $filePdfName = pathinfo($originPdfName, PATHINFO_FILENAME);
                        $extensionPdf = $this->request->file('pdfstoria')->getClientOriginalExtension();
                        
                        $newPdfName = 'FILEPDF_'.time().'.'.$extensionPdf;
                        $this->request->file('pdfstoria')->move($pathst, $newPdfName);
                        $urlPdf=asset(Storage::url('storieallegatimultimediali/'.$storia->sid.'/'.$newPdfName));
                 
                        //memo DB
                        $allegatopdf = new Multimediaelements();
                        $allegatopdf->nome_file_originale=$originPdfName;
                        $allegatopdf->nome_file_memorizzato=$newPdfName;
                        $allegatopdf->tipologia=6;
                        $allegatopdf->sid=$storia->sid;
                        $allegatopdf->save();
                    }
                    //if a new pdf is inserted and an old one is present || deleted the old pdf
                    if(($deleteOldPdf==1 && count($oldpdf->all())>0) ||  ($deleteOldPdf==0 && count($oldpdf->all())>0 && !$request_post['filepdf']) ){
                        foreach ($oldpdf AS $singlepdf){
                            //physical removal
                            $pathpdf = storage_path('app/public/storieallegatimultimediali/'.$storia->sid.'/'. $singlepdf->nome_file_memorizzato);
                            Storage::delete($pathpdf);
                            
                            //erased record DB
                            Multimediaelements::destroy($singlepdf->amsid);                            
                        }
                    }
                    
                    //memo PHASES and INSIGHTS PHASES
                    $ordine=1;
                    $elencosfid=[];
                    $elencoPOSTsfid=[];//it contains the numeric and non-numeric keys before and after storing the phases
                    foreach ($request_post['sfid'] AS $ks=>$sfid){
                        $datisfid=[];
                        if(!is_numeric($sfid)){
                            //insert phase history
                            $storiafase=new Storiesphases();
                            $storiafase->sid=$storia->sid;
                            $storiafase->ordine=$ordine;
                            $storiafase->save();
                            $idsfid=$storiafase->sfid;
                        }else{
                            $storiafase = Storiesphases::find($sfid);
                            $storiafase->ordine = $ordine;
                            $storiafase->save();
                            $idsfid=$sfid;
                        }
                        
                        //update-insert storiafase
                        $elencosfid[]=$elencoPOSTsfid[$sfid]=$idsfid;
                        
                        $datisfid['titolofase']=$this->dataready($request_post['titolofase'][$ks]);
                        $datisfid['testofase']=$this->dataready($request_post['testofase'][$ks]);
                        $this->mod_storiesphases->setStoriafaselinguaAss($idsfid,$datisfid);
                        $ordine++;
                    }
                    //delete all sfid not in insert and update
                    Storiesphases::whereNotIn('sfid',$elencosfid)->where('sid',$storia->sid)->delete();
                    //unset($elencosfid);
                    unset($ordine);
                    unset($sfid);
                    unset($idsfid);
                 
                    //memo snippets phases
                    /*if(isset($request_post['snid']) && is_array($request_post['snid']) && count($request_post['snid'])>0){
                        foreach ($request_post['snid'] AS $sfid=>$snippets){
                            $idsfid=$sfid;
                            if(!is_numeric($sfid))
                                $idsfid=$elencoPOSTsfid[$sfid];
                            
                            $nuovisnip=[];
                            //all new snippets
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
                                $this->mod_snippets->setSnippetslanguageAss($idsnip,$datisnip);
                                $nuovisnip[]=$idsnip;
                            }
                            //delete snippets non più presenti per ogni determinata parte
                            Snippets::whereNotIn('snid',$nuovisnip)->where('sfid',$idsfid)->delete();
                        }
                    }*/
                    //memo snippets phases
                    if(isset($request_post['snid']) && is_array($request_post['snid']) && count($request_post['snid'])>0){
                        $sfid_snidesistenti=[];
                        foreach ($request_post['snid'] AS $sfid=>$snippets){
                            $idsfid=$sfid;
                            if(!is_numeric($sfid))
                                $idsfid=$elencoPOSTsfid[$sfid];

                            $nuovisnip=[];
                            //all new snippets
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
                                $this->mod_snippets->setSnippetslanguageAss($idsnip,$datisnip);
                                $nuovisnip[]=$idsnip;
                            }
                            //delete snippets non più presenti per ogni determinata parte
                            Snippets::whereNotIn('snid',$nuovisnip)->where('sfid',$idsfid)->delete();
                            $sfid_snidesistenti[]=$idsfid;
                        }
                        /*DB::enableQueryLog();
                        Snippets::where('sfid',array_diff($elencosfid,$sfid_snidesistenti))->delete();
                        $query = DB::getQueryLog();
                        dd(end($query));exit;*/                        
                        //delete all snippets in $elencosfid and not in $sfid_snidesistenti
                        if(isset($sfid_snidesistenti) && is_array($sfid_snidesistenti) && count($sfid_snidesistenti)>0)
                            Snippets::where('sfid',array_diff($elencosfid,$sfid_snidesistenti))->delete();
                    }else{
                        //delete all snippets from all phases of the story
                        Snippets::where('sfid',$elencosfid)->delete();
                    }                    
                    
                    DB::commit();
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[OUT TRY] modify', $this->mod_log->getParamFrontoffice());
                    $this->request->session()->flash('messageinfo', '<h2>Storia aggiornata con successo!</h2>');   
                    return redirect('/admin/elencostorie');
                } catch (Throwable $e) {
                    DB::rollBack();
                    Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT TRY] modify', $this->mod_log->getParamFrontoffice($e->getMessage()));
                    echo $e->getMessage();
                    exit;
                }
            }else{
                Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->error('[OUT] modify', $this->mod_log->getParamFrontoffice('form errato'));
                $this->request->session()->flash('formerrato', '<h5>Dati non corretti - RICONTROLLARE TUTTI I CAMPI INSERITI</h5>'."".$this->errorsFormSubmission);
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
        $collaboratori=$this->mod_collaborators->getAll(1);
        $approfondimenti=$approfondimentifasi=[];
        if($idsid){
            $fasistoria=$this->mod_stories->getStoryPhases($idsid);
            $datistoria=$this->mod_stories->getStory($idsid);
            $datistoria=get_object_vars($datistoria->toArray()[0]);
            $collaboratoristoria=$this->mod_stories->getStoryCollaborators($idsid);
            $snippets=$this->mod_stories->getSnippetsFromStory($idsid)->toArray();
            $snippetfase=[];
            if(count($snippets)>0){
                foreach ($snippets AS $snippet){
                    $snippetfase[$snippet->sfid][]=$snippet;
                }
            }
            
            $storiasubmit=$this->mod_storiessubmit->getStoriaSubmitFromSID($idsid);
            if(count($storiasubmit->all())>0){
                $storiasubmit=$storiasubmit[0];
                $storiasubmitfile=$this->mod_storiessubmitfile->getFilesFromSSID($storiasubmit->ssid);
            }
            
            $approfondimenti=$this->mod_integrations->getNumberIntegrationsStory($idsid)->toArray();
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
            $podcast=$this->mod_multimediaelements->getMultimediaElementsFromStory($idsid,$whereallegati1);
            $whereallegati2=[2,5]; //VIDEO
            $video=$this->mod_multimediaelements->getMultimediaElementsFromStory($idsid,array(),$whereallegati2);
            $whereallegati3[]=['ams.tipologia',6]; //PDF
            $pdfstoria=$this->mod_multimediaelements->getMultimediaElementsFromStory($idsid,$whereallegati3);
        }
        $ruoli=$this->mod_ruoli->getAll(1);        
        $order=[];
        $order['zl.nome']='ASC';
        $zoonosi=$this->mod_zoonosi->getAll('',$order);
        
        return view('admin.stories.addmod')->with('datapost',$datistoria)->with('fasistoria',$fasistoria)->with('form','adminSaveModifyStory')
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
    * Method for checking validation data of the insert/modify form
    * @return BOOL
    *
    */
    private function checkform(){
        $request_post=$this->request->all();
        //check for missing required data
        $datimancanti=[];
        if(!$request_post['anno_ambientazione'])$datimancanti[]='Anno di ambientazione mancante';
        if(!$request_post['editore'])$datimancanti[]='Editore mancante';
        if(!$request_post['titolo'])$datimancanti[]='Titolo mancante';
        if(!$request_post['slug'])$datimancanti[]='Slug mancante';
        if(!$request_post['abstract'])$datimancanti[]='Abstract mancante';
        if(!$request_post['copyright'])$datimancanti[]='Copyright mancante';
        
        //check collaborators
        if(!isset($request_post['collid']) || !is_array($request_post['collid']) || (is_array($request_post['collid']) && count($request_post['collid'])==0))$datimancanti[]='Inserire almeno un collaboratore';
        if(isset($request_post['collid']) && count($request_post['collid'])>0){
            foreach ($request_post['collid'] AS $tc=>$collaboratore){
                if(!isset($request_post['nomecollaboratore'][$tc]) || $request_post['nomecollaboratore'][$tc]=='')$datimancanti[]='Inserire il nome del collaboratore';
                if(!isset($request_post['cognomecollaboratore'][$tc]) || $request_post['cognomecollaboratore'][$tc]=='')$datimancanti[]='Inserire il cognome del collaboratore';
                if(!isset($request_post['sel_ruolo'][$tc]) || $request_post['sel_ruolo'][$tc]=='' || $request_post['sel_ruolo'][$tc]==0)$datimancanti[]='Inserire il ruolo del collaboratore';
            }
        }
       
        //check data storie fasi
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
        
        //check snippets
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
        
        //CHECK VALIDITY OF ANY UPLOAD OF VIDEO / PODCAST / PDF
        $allowed_text = array('pdf');
        $allowed_video = array('mp4','mov','avi');
        $allowed_audio = array('pcm', 'wav', 'mp3', 'ogg', 'flac','m4a');
        //controls on pdf files
        if(isset($_FILES['pdfstoria']['name']) && $_FILES['pdfstoria']['name']!=''){
            $ext_text = pathinfo($_FILES['pdfstoria']['name'], PATHINFO_EXTENSION);
            if (!in_array($ext_text, $allowed_text)) {$datimancanti[]='Il file caricato come testo della storia deve essere del formato .PDF';}
            if($_FILES['pdfstoria']['size']>10485760){$datimancanti[]='Il file PDF caricato è troppo grande';}
        }
        //controls on podcast
        if(isset($_FILES['podcast']['name']) && $_FILES['podcast']['name']!=''){
            $ext_audio = pathinfo($_FILES['podcast']['name'], PATHINFO_EXTENSION);
            if (!in_array($ext_audio, $allowed_audio)) {$datimancanti[]='Il file caricato come podcast della storia deve essere di un formato tra: .pcm, .wav, .mp3, .ogg, .flac, .m4a';}
            if($_FILES['podcast']['size']>20971520){$datimancanti[]='Il file audio del Podcast caricato è troppo grande';}
        }
        //controls on video
        if(isset($_FILES['linkvideo']['name']) && $_FILES['linkvideo']['name']!=''){
            $ext_video = pathinfo($_FILES['linkvideo']['name'], PATHINFO_EXTENSION);
            if (!in_array($ext_video, $allowed_video)) {$datimancanti[]='Il file caricato come video della storia deve essere di un formato tra: .mp4, .mov, .avi';}
            if($_FILES['linkvideo']['size']>419430400){$datimancanti[]='Il file video caricato è troppo grande';}   
        }
        
        if(count($datimancanti)>0){
            $this->setVisualErrors($datimancanti);
            return false;
        }
        
        return true;
     
    }
    
    /**
    *
    * Check the uniqueness of the url for story
    * @return JSON
    *
    */
    public function checkslug(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] checkslug', $this->mod_log->getParamFrontoffice());
        $sid=0;
        if(preg_match('/^[1-9][0-9]*$/',$this->request->sid))$sid=$this->request->sid;
        $storia=$this->mod_stories->checkExistSlug($this->request->slug,$sid);
        if(count($storia->toArray())>0){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[OUT] checkslug', $this->mod_log->getParamFrontoffice('slug già presente'));
            return response()->json(['error'=>true,'message'=>'Slug già presente nel sistema, modificare il nome della storia']);
        }
        return response()->json(['error'=>false,'message'=>'']);
    }
    
    /**
    *
    * Publish the story
    * @return JSON
    *
    */
    public function publishstory(){
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[IN] pubblicastoria', $this->mod_log->getParamFrontoffice());
        if(!preg_match('/^[1-9][0-9]*$/',$this->request->sid)){
            Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->critical('[OUT] pubblicastoria', $this->mod_log->getParamFrontoffice('id storia non valido'));
            return response()->json(['error'=>true,'message'=>'Storia selezionata non valida']);   
        }
        $this->mod_stories->publishStory($this->request->sid,['stato'=>2,'data_pubblicazione'=>'NOW()']);
        Log::build(['driver' => 'single','path' => storage_path('logs/back.log')])->info('[OUT] pubblicastoria', $this->mod_log->getParamFrontoffice());
        return response()->json(['error'=>false,'message'=>'']);
    }
    
    /**
    *
    * Prepare the text to be published for errors
    * 
    * @param Array $arrayErr array with the list of form errors
    * @return BOOL
    *
    */
    private function setVisualErrors($arrayErr){
        foreach ($arrayErr AS $key=>$textErrore)
            $this->errorsFormSubmission.='<b>'.$textErrore.'</b><br />';
        unset($arrayErr);
        return true;
    }
    
    /**
    *
    * Prepare the data for storage
    * 
    * @param $data string value to set
    * @return STRING
    *
    */
    private function dataready($data) {
        if(!$data)return '';
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    } 
}
