@extends('layout.base')
@section('header_pt1')
    @include('layout.header_pt1')
@endsection

@section('additionalcss')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<link href="https://unpkg.com/video.js@7/dist/video-js.min.css" rel="stylesheet" />
@endsection

@section('additionaljs')
    @parent
    <script src="https://vjs.zencdn.net/7.17.0/video.min.js"></script>
    <script type="text/javascript" src="/js/gestionemodalstoria.js" ></script>
    
    <script>
    /*l'indice di arrCom risulta essere l'id del commento*/ 
    $.arrCom=[];
    <?php if(is_array($approfondimenti) && count($approfondimenti)>0){ ?>
        <?php foreach ($approfondimenti AS $ka=>$approfondimento){ ?>
             $.arrCom[<?=$approfondimento->said;?>]="<?=$approfondimento->testoselezionato;?>";
        <?php }unset($ka);unset($approfondimento); ?>
    <?php } ?>
    /*l'indice di testoBlo risulta essere l'id del blocco*/ 
    $.testoBlo=[];
    <?php if(is_array($fasi) && count($fasi)>0){ ?>
        <?php foreach ($fasi AS $kf=>$fase){ ?>
             $.testoBlo[<?=$fase->sfid;?>]="<?=$fase->testofase;?>";
        <?php }unset($kf);unset($fase); ?>
    <?php } ?>
    
    </script>
    
@endsection
<?php use Illuminate\Support\Facades\Storage;?>

@section('modal_1')
<div class="modal fade" tabindex="-1" id="modalQuesiti" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg modal-fullscreen-sm-down modal-animation-special">
        <div class="modal-content">
            <div class="modal-header bg-wheat">
                <h2 class="modal-title font-dark">Dati di contesto</h2>
                <button type="button" class="btn-close btn-close-white closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="questions" class="clearfix">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-header font-white closeModal" data-bs-dismiss="modal">Chiudi</button>
            </div>
        </div>
    </div>
</div>
@endsection


@section('modal_2')
<div class="modal fade" tabindex="-1" id="modalReview" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg modal-fullscreen-sm-down modal-animation-special">
        <div class="modal-content">
            <div class="modal-header bg-wheat">
                <h2 class="modal-title font-dark">Review <?=$storia->nomezoonosi;?></h2>
                <button type="button" class="btn-close btn-close-white closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-header font-white closeModal" data-bs-dismiss="modal">Chiudi</button>
            </div>
        </div>
    </div>
</div>
@endsection


@section('modal_3')
<div class="modal fade" tabindex="-1" id="modalReview" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg modal-fullscreen-sm-down modal-animation-special">
        <div class="modal-content">
            <div class="modal-header bg-wheat">
                <h2 class="modal-title font-dark">Review <?=$storia->nomezoonosi;?></h2>
                <button type="button" class="btn-close btn-close-white closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-header font-white closeModal" data-bs-dismiss="modal">Chiudi</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" id="modalProbResp" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg modal-fullscreen-sm-down modal-animation-special">
        <div class="modal-content">
            <div class="modal-header bg-wheat">
                <h4 class="modal-title font-dark">Snippets</h4>
                <button type="button" class="btn-close btn-close-white closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-header font-white closeModal" data-bs-dismiss="modal">Chiudi</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<input type="hidden" id="storiaid" value="<?=$storia->sid;?>" />
<input type="hidden" id="zoonosiid" value="<?=$storia->zid;?>" />
<div class="d-flex justify-content-start mt-5"><div><i class="fa fa-bookmark me-2"></i></div><div><h2 class="title-zoonosi"><?=$storia->titolo;?></h2></div></div>
<div class="d-flex justify-content-start"><div><i class="fa fa-quote-right me-2"></i></div><div><h5 class="int-tecnica-zoonosi"><?=$storia->descrizione;?></h5></div></div>
<div class="d-flex justify-content-start">
    <div><i class="fa fa-users me-2"></i></div>
    <div>
        <h5 class="attori-zoonosi">
            <?php if(is_array($collaboratori) && count($collaboratori)>0){$totcollaboratori=count($collaboratori);
                foreach ($collaboratori AS $kc=>$collaboratore){ ?>
                    <?=$collaboratore->grado.' '.$collaboratore->nome.' '.$collaboratore->cognome;?><?php if($kc+1<=$totcollaboratori-1)echo ', '; ?>
                <?php } ?>
            <?php } ?>
        </h5>
    </div>
</div>
<div class="d-lg-flex d-xl-flex d-xxl-flex">
    <div class="col-12 col-sm-7 col-md-12 col-lg-12 col-xl-12 col-xxl-12 d-lg-flex d-xl-flex d-xxl-flex">
        <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 col-xxl-4 d-inline-flex ">
            <div><i class="fa fa-copyright me-2"></i></div>
            <div><h6 class="copyright-zoonosi" style="font-size: 0.8rem;"><?=($storia->copyright!='')?$storia->copyright:'';?></h6></div>
        </div>

        <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 col-xxl-4 d-inline-flex ">
            <div><i class="fa fa-calendar me-2"></i></div>
            <div><h6 class="data-zoonosi"><?=Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $storia->data_pubblicazione)->format('d/m/Y');?></h6></div>
        </div>

        <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 col-xxl-4 d-inline-flex">
            <div><i class="fa fa-newspaper-o me-2"></i></div>
            <div><h6 class="editore-zoonosi"><?=($storia->editore!='')?$storia->editore:'';?></h6></div>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
</div>

<?php 
//$url=asset(Storage::url('storieallegatimultimediali/'.$storia->sid.'/FILEVIDEO_20220401134025223356.mp4'));
?>

<div class="row p-3">
    <div class="col-12 col-sm-4 border p-2">
        <?php if(count($podcast->all())>0){ 
            foreach ($podcast AS $singolopodcast){
                if(!$singolopodcast->linkurlhtml){
                    //video interno
                    $urldownloadpod=url('storageallegatistorie/'.$datapost['sid'].'/'.$singolopodcast->nome_file_memorizzato.'/'.$singolopodcast->nome_file_originale);
                    //$pathpod = storage_path('app/allegatimultimedialistorie/'.$datapost['sid'].'/'. $singolopodcast->nome_file_memorizzato); ?>
                    <div class="col-12 mt-2">
                        <!--<input class="form-control mb-3" type="file" id="video" name="video" />-->
                        <a href="{{$urldownload}}"><img src="/images/mp3-icon.png" width="50" /><span class="ms-2"><?=html_entity_decode($singolopodcast->nome_file_originale,ENT_QUOTES,'utf-8')?></span></a>
                    </div>
                <?php } ?>
            <?php }
        } ?>
    </div>
    <div class="col-12 col-sm-4 border p-2 text-center">
        <?php 
        if(count($video->all())>0){ 
            foreach ($video AS $singolovideo){
                if(!$singolovideo->linkurlhtml){
                    $linkstoria=asset(Storage::url('storieallegatimultimediali/'.$storia->sid.'/'.$singolovideo->nome_file_memorizzato));?>
                    <video-js id="my-player" class="video-js vjs-layout-medium" data-setup='{"controls": true, "autoplay": false, "preload": "auto", "responsive": true, "fluid": true}'>
                        <source src="<?=$linkstoria;?>" type="video/mp4"></source>
                        <p class="vjs-no-js">
                            To view this video please enable JavaScript, and consider upgrading to a
                            web browser that
                            <a href="https://videojs.com/html5-video-support/" target="_blank">
                              supports HTML5 video
                            </a>
                        </p>
                    </video-js>
                <?php }else{ echo $singolovideo->linkurlhtml; }
            }
        }?>
        <?php /* 
            if($storia->sid==4 || $storia->sid==3 || $storia->sid==16 || $storia->sid==7){
                $linkstoria='';
                if($storia->sid==4)$linkstoria='/videos/Monica_Cagiola_Tubercolosi_2022ultimo.mp4';
                if($storia->sid==3)$linkstoria='/videos/Monica_Cagiola_Rogna _sarcoptica_M_Epidemic_2022Ultimo.mp4';
                if($storia->sid==16)$linkstoria=$url;
                if($storia->sid==7)$linkstoria='/videos/VET_Marco_Servili.mp4';
        ?>
        <video-js id="my-player" class="video-js vjs-layout-medium" data-setup='{"controls": true, "autoplay": false, "preload": "auto", "responsive": true, "fluid": true}'>
         <source src="<?=$linkstoria;?>" type="video/mp4"></source>
         <p class="vjs-no-js">
           To view this video please enable JavaScript, and consider upgrading to a
           web browser that
           <a href="https://videojs.com/html5-video-support/" target="_blank">
             supports HTML5 video
           </a>
         </p>
        </video-js>
        <?php } */?>
    </div>
    <div class="col-12 col-sm-4 border text-center p-3">
        <?php if($storia->linktelegram!=''){ ?>
        <a href="<?=$storia->linktelegram;?>" title="Unisciti al gruppo Telegram">
            <button type="button" class="btn bg-telegram text-white blob blue"><strong>Telegram {{$storia->nomezoonosi}}&nbsp;<i class="fa fa-telegram fa-2x" style="vertical-align: middle;"></i></strong></button>
        </a>
        <?php } ?>
    </div>
</div>
<?php /*if($storia->linktelegram!=''){ ?>
<div class="row justify-content-end">
    <div class="col-12 col-sm-5 col-md-5 col-lg-5 col-xl-4 col-xxl-4 text-end mb-3 mt-3">
        <a href="<?=$storia->linktelegram;?>" title="Unisciti al gruppo Telegram">
            <button type="button" class="btn bg-telegram text-white blob blue"><strong>Telegram {{$storia->nomezoonosi}}&nbsp;<i class="fa fa-telegram fa-2x" style="vertical-align: middle;"></i></strong></button>
        </a>
    </div>
</div>
<?php }*/ ?>


<div class="row pl-2 pr-2 justify-content-center">  
    <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-3 col-xxl-3  pl-2 pr-2 mt-1 mb-1 text-end">
        <button type="button" class="btn bg-success font-white fw-bold" onclick="getdaticontesto();" data-bs-toggle="modal" data-bs-target="#modalQuesiti">
            DATI DI CONTESTO
        </button>    
    </div>
    <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-3 col-xxl-3 pl-2 pr-2 mt-1 mb-1 text-start">
        <button type="button" class="btn bg-orange font-white fw-bold" onclick="getreview();" data-bs-toggle="modal" data-bs-target="#modalReview">
            REVIEW <?= strtoupper($storia->nomezoonosi);?>
        </button>
    </div>
    <!--<div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-3 col-xxl-2 pl-2 pr-2 mt-1 mb-1 text-center">
      <button type="button" class="btn bg-dark-orange font-white fw-bold" onclick="getrisorsaformativa();">
          RISORSE FORMATIVE
      </button>
    </div>-->
</div>

<?php if(is_array($fasi) && count($fasi)>0){ ?>
<div class="accordion mt-3" id="accordionExample">
    <?php foreach ($fasi AS $kf=>$fase){ ?>
    <div class="accordion-item">
        <h2 class="accordion-header" id="heading<?=$fase->sfid;?>">
            <button class="accordion-button fw-bold" type="button"  data-bs-target="#collapse<?=$fase->sfid;?>" aria-expanded="false" aria-controls="collapse<?=$fase->sfid;?>">
                <?=$fase->titolofase;?>
            </button>
        </h2>
      <!-- aggiungere la classe "show" per mantenere il blocco aperto-->
      <div id="collapse<?=$fase->sfid;?>" class="collapse multi-collapse show" >
        <div class="accordion-body ">
            <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-7 col-xxl-7 fLeft p-3 border-start border-dark">
                <div class="testo-blocco" idblocco="<?=$fase->sfid;?>">
                    <?=$fase->testofase;?>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-5 col-xxl-5 fLeft approfondimenti-blocco">
                <div class="my-3 p-3 rounded shadow-lg border-wheat-2 bg-grey-transp">
                    <div data-bs-toggle="collapse" data-bs-target="#collapseApp<?=$fase->sfid;?>" aria-expanded="false" aria-controls="collapseApp<?=$fase->sfid;?>"><h6 class="border-bottom-grey-blue pb-2 mb-0 pointer" title="Apri/Chiudi approfondimenti">INTEGRAZIONI (<span class="tot-approfondimenti"><?=(array_key_exists($fase->sfid, $numero_approfondimenti_fasi))?$numero_approfondimenti_fasi[$fase->sfid]:'0';?></span>) <i class="fa fa-caret-square-o-down"></i></h6></div>
                  <div id="collapseApp<?=$fase->sfid;?>" class="collapse-approfondimenti show">
                    <div class="blocco-interno-approfondimenti">
                      <div class="noreverse-flex"> <!-- questo div contenitore permette di non far invertire l&apos;ordine in cui vengono presentati gli approfondimenti -->
                            <?php 
                            if(array_key_exists($fase->sfid,$approfondimenti_genitori)){
                                foreach ($approfondimenti_genitori[$fase->sfid] AS $ag=>$commento_genitore){ ?>
                                    <div class="containergenerale-approfondimento mb-2">
                                        <div class="d-flex approfondimento-commento pointer" idcom="<?=$commento_genitore->said;?>">
                                            <input type="hidden" class="riferimento-testo" value="<?=str_replace('\'','&apos;',$commento_genitore->testoselezionato);?>" />
                                            <img src="/images/avatar.png" width="40" height="40" class="me-2 mt-1" />
                                            <div class="align-self-stretch pb-1 pe-2 border-bottom-grey-blue" title="Clicca per selezionare l'approfondimento">
                                                <strong class="d-block text-gray-dark d-block align-self-end"><?=$commento_genitore->nome_cognome_utente;?> <span class="font-06">(<?=Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $commento_genitore->data_inserimento)->format('d/m/Y H:i');?>)</span></strong>
                                                <p class="pb-1 mb-0 small lh-sm"><?=$commento_genitore->testoapprofondimento;?></p>
                                            </div>
                                        </div>
                                        <small class="col-12 text-end ms-5">
                                            <a class="text-decoration-none text-muted fw-bold rispondi-approfondimento" href="#!" title="Rispondi" idcom="<?=$commento_genitore->said;?>">Rispondi</a>
                                        </small>
                                    </div>
                                    
                                    <?php if(array_key_exists($commento_genitore->said, $approfondimenti_figli)){
                                        foreach ($approfondimenti_figli[$commento_genitore->said] AS $af=>$commento_figlio){?>
                                            <div class="containergenerale-approfondimento mb-2 ms-5">
                                                <div class="d-flex approfondimento-commento pointer" idcom="<?=$commento_figlio->said;?>"  idcomp="<?=$commento_figlio->said_genitore;?>">
                                                    <input type="hidden" class="riferimento-testo" value="<?=str_replace('\'','&apos;',$commento_figlio->testoselezionato);?>" />
                                                    <img src="/images/avatar.png" width="40" height="40" class="me-2 mt-1" />
                                                    <div class="align-self-stretch pb-1 pe-2 border-bottom-grey-blue" title="Clicca per selezionare l&apos;approfondimento">
                                                        <strong class="d-block text-gray-dark d-block align-self-end"><?=$commento_figlio->nome_cognome_utente;?> <span class="font-06">(<?=Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $commento_figlio->data_inserimento)->format('d/m/Y H:i');?>)</span></strong>
                                                        <p class="pb-1 mb-0 small lh-sm"><?=$commento_figlio->testoapprofondimento;?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>                                        
                                    <?php } ?>
                                <?php }
                            }
                            ?>
                      </div>
                    </div>
                    <div class="mt-4 inserisci-approfondimento border-top-grey-blue">
                      <div class="p-2 m-2 container-testo-approfondimento bg-wheat-transp">
                        <figure class="text-end">
                          <blockquote class="blockquote"><p class="h6">Stai scrivendo un&apos;integrazione per il seguente testo:</p></blockquote>
                          <figcaption class="blockquote-footer testo-approfondimento"></figcaption>
                        </figure>
                        <div class="col-12">
                          <div class="fRight"> <button class="btn btn-warning btn-sm elimina-suggerimento">Elimina selezione</button> </div>
                        </div>
                        <div class="clearfix"></div>
                      </div>
                      <div class="p-2 m-2 container-testo-risposta bg-wheat-transp">
                        <figure class="text-end">
                          <blockquote class="blockquote"><p class="h6">Stai rispondendo ad un&apos;integrazione:</p></blockquote>
                          <figcaption class="blockquote-footer testo-risposta" idcomrisp=""></figcaption>
                        </figure>
                        <div class="col-12">
                          <div class="fRight"> <button class="btn btn-warning btn-sm elimina-risposta">Elimina risposta</button> </div>
                        </div>
                        <div class="clearfix"></div>
                      </div>    
                      <div class="p-2">
                          <div class="comment-box p-0">
                              <div class="comment-area"> <textarea class="form-control messaggio-approfondimento" placeholder="Scrivi un&apos;integrazione. Puoi anche selezionare una parte specifica del testo." rows="3"></textarea> </div>
                              <div class="comment-btns mt-2">
                                  <div class="row">
                                      <div class="col-12">
                                          <div class="pull-left">
                                                <?php if(Auth::check()){ ?>
                                                    <button class="btn btn-success send invia-approfondimento btn-sm">Invia integrazione <i class="fa fa-check ml-1"></i><!--<i class="fa fa-long-arrow-right ml-1"></i>--></button>
                                                <?php }else{ ?>
                                                    <p>Effettua il <a href="{{route('loginUser')}}"><b>LOGIN</b></a> per lasciare un approfondimento.</p>
                                                <?php } ?>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
            <div class="clearfix"></div>
          </div>
        </div>
    </div>
    <?php } ?>
</div>

<?php if($storia->linkzoodiac!=''){?>
    <div class="row mb-3 mt-3">
        <div class="col-12 col-sm-5 text-start">
            <a href="<?=$storia->linkzoodiac;?>">
                <div class="border rounded p-2"><img src="/images/logo_zoodiac.png" style="max-width: 100px; height: auto;"><span class="align-middle"><strong>Documentazione di indirizzo per la diagnosi di zoonosi nell&apos;uomo</strong></span></div>
            </a>
        </div>
    </div>
<?php } ?>

<?php } ?>
@endsection


