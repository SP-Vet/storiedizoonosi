@extends('admin.layout.base')

@section('additionaljs')
    @parent
    <script type="text/javascript" src="/js/form-control-gestiscistoria.js"></script>
    <script type="text/javascript" src="/js/storia.js"></script>
    <script type="text/javascript">
        var rottaupload="<?=route('ckeditor.upload', ['_token' => csrf_token() ]);?>";
        CKEDITOR.replace( 'copyright', {
            customConfig: '/js/ckeditor_configs/config_simple.js',
            filebrowserUploadUrl: rottaupload,
            filebrowserUploadMethod: 'form'
        });
        CKEDITOR.replace( 'abstract', {
            customConfig: '/js/ckeditor_configs/config_simple.js',
            filebrowserUploadUrl: rottaupload,
            filebrowserUploadMethod: 'form'
        });
        CKEDITOR.replace( 'descrizione', {
            customConfig: '/js/ckeditor_configs/config_simple.js',
            filebrowserUploadUrl: rottaupload,
            filebrowserUploadMethod: 'form'
        });
    </script>
    <script type="text/javascript" src="/js/gestisci-storia.js"></script>
@endsection

@section('modal_1')
<div class="modal fade" tabindex="-1" id="modalIntegrazioni" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg modal-fullscreen-sm-down modal-animation-special">
        <div class="modal-content">
            <div class="modal-header bg-wheat">
                <h2 class="modal-title font-dark">Integrazioni</h2>
                <button type="button" class="btn-close btn-close-white closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="elenco-integrazioni" class="clearfix"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-header font-white closeModal" data-bs-dismiss="modal">Chiudi</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="d-flex justify-content-between mt-2">
    <div class="d-inline-flex"><h5 class="pt-2">{{$title_page}}</h5></div>
    <div class="d-inline-flex"><a href="{{ url()->previous() }}" class="btn btn-secondary"><i class="fa fa-fast-backward"></i><strong>&nbsp;Torna indietro</strong></a></div>
</div>
<hr>
@if (session('formerrato'))
    <div class="w-100">
        <div class="row ps-3 pe-3">
            <div class="col-12 alert alert-danger">
                {!!session('formerrato')!!}
            </div> 
        </div>
    </div>
@endif
<div class="w-100 checkerroripreinvio d-none">
    <div class="row ps-3 pe-3">
        <div class="col-12 alert alert-danger msgContainerError font-12"></div>
    </div>     
</div> 

<?php //echo '<pre>';print_r($storiasubmit);echo '</pre>';?>
<?php if(isset($storiasubmit->titolo_inserito)){ ?>
<div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 container-list-story position-relative">
    <h5 class="ps-4 pt-4 pe-4 mb-0">Dati forniti dall&apos;utente</h5>  
    <div class="col-12 col-md-4 p-4">
        <div class="mb-1 col-12">
            <label for="utente" class="form-label"><strong>Utente: </strong></label><span class=""><?=(isset($storiasubmit->titolo_inserito))?html_entity_decode($storiasubmit->titolo_inserito,ENT_QUOTES,'utf-8'):'';?></span>
        </div>
        <div class="mb-1 col-12">
            <label for="data_inserimento" class="form-label"><strong>Data inserimento: </strong></label><span class=""><?=(isset($datapost['data_inserimento']))?$datapost['data_inserimento']:'';?></span>
        </div>
        <div class="mb-1 col-12">
            <label for="titolo_inserito" class="form-label"><strong>Titolo inserito: </strong></label><span class=""><?=(isset($storiasubmit->titolo_inserito))?html_entity_decode($storiasubmit->titolo_inserito,ENT_QUOTES,'utf-8'):'';?></span>
        </div>
        <div class="mb-1 col-12">
            <label for="tipozoonosi_inserito" class="form-label"><strong>Zoonosi inserita: </strong></label><span class=""><?=(isset($storiasubmit->tipozoonosi_inserito))?html_entity_decode($storiasubmit->tipozoonosi_inserito,ENT_QUOTES,'utf-8'):'';?></span>
        </div>
        <div class="mb-1 col-12">
            <label for="annoambientazione_inserito" class="form-label"><strong>Anno ambientazione inserito: </strong></label><span class=""><?=(isset($storiasubmit->annoambientazione_inserito))?html_entity_decode($storiasubmit->annoambientazione_inserito,ENT_QUOTES,'utf-8'):'';?></span>
        </div>
        <div class="mb-1 col-12">
            <label for="brevedescrizione_inserita" class="form-label"><strong>Descrizione inserita: </strong></label><span class=""><?=(isset($storiasubmit->brevedescrizione_inserita))?html_entity_decode($storiasubmit->brevedescrizione_inserita,ENT_QUOTES,'utf-8'):'';?></span>
        </div>
        <div class="mb-1 col-12">
            <label for="ruolo_inserito" class="form-label"><strong>Ruolo inserito: </strong></label><span class=""><?=(isset($storiasubmit->ruolo_inserito))?html_entity_decode($storiasubmit->ruolo_inserito,ENT_QUOTES,'utf-8'):'';?></span>
        </div>
        <div class="mb-1 col-12">
            <label for="noteaggiuntive_inserite" class="form-label"><strong>Note inserite: </strong></label><span class=""><?=(isset($storiasubmit->noteaggiuntive_inserite))?html_entity_decode($storiasubmit->noteaggiuntive_inserite,ENT_QUOTES,'utf-8'):'';?></span>
        </div>
    </div>
    <div class="col-12 col-md-8 p-4">
        <div class="col-12"><p><strong>Documentazione allegata</strong></p></div>
        <?php  if(count($storiasubmitfile->all())>0){?>
        <div class="row">
            <?php foreach ($storiasubmitfile AS $file){
                $urldownload=url('storagestoriesubmit/'.$storiasubmit->ssid.'/'.$file->nome_file_memorizzato.'/'.$file->nome_file_originale);
                $path = storage_path('app/storiesubmit/'.$storiasubmit->ssid.'/'. $file->nome_file_memorizzato);
                switch($file->mimetype){
                    case 'video/mp4': 
                    case 'video/x-msvideo':
                        $tipoicona='mp4';
                        break;
                    case 'application/pdf': 
                        $tipoicona='pdf';
                        break;
                    case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document': 
                    case 'application/msword':
                    case 'application/rtf':
                    case 'text/plain':
                        $tipoicona='doc';
                        break;
                    case 'image/jpeg':
                        $tipoicona='jpeg';
                        break;
                    case 'image/png':
                        $tipoicona='png';
                        break;
                    case 'audio/mpeg':
                        $tipoicona='mp3';
                        break;
                    case 'audio/ogg':
                        $tipoicona='ogg';
                        break;
                    default: 
                        $tipoicona='';
                        break;
                }?>
                <div class="col-6 mb-1 p-2">
                    <a href="{{$urldownload}}"><img src="/images/<?=$tipoicona;?>-icon.png" width="50" /><span class="ms-2"><?=html_entity_decode($file->nome_file_originale,ENT_QUOTES,'utf-8')?></span></a>
                </div>
            <?php } ?>
        </div>
        <?php } ?>
    </div>
</div>
<?php } ?>
<form action="{{route($form)}}" id="gestionestoria" method="POST" class="needs-validation formgestiscistoria" enctype="multipart/form-data" novalidate>
    {{ csrf_field() }}
<div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 container-list-story position-relative">
    <h5 class="ps-4 pt-4 pe-4 mb-0">Dati Storia</h5>  
    <div class="clearfix"></div>
    <div class="mb-3 col-md-2 pt-1 pb-1 ps-4 pe-4">
        <label for="zoonosi" class="form-label">Zoonosi<span class="text-required"> * </span></label>
        <div class="input-group has-validation">
            <select class="select form-control" name="zid" id="zid">
                    <option value="">Seleziona una zoonosi</option>
                    @foreach($zoonosi as $z)    
                        <option value="{{$z->zid}}" <?php if(isset($datapost['zid']) && $z->zid==$datapost['zid'])echo 'selected="selected"';?>>{{$z->nome}}</option>
                    @endforeach
                </select>
            <div class="invalid-feedback">
              Campo obbligatorio.
            </div>
        </div>
    </div>
    <div class="mb-3 col-md-2 pt-1 pb-1 ps-4 pe-4">
        <label for="anno_ambientazione" class="form-label">Anno ambientazione<span class="text-required"> * </span></label>
        <div class="input-group has-validation">
            <input type="text" name="anno_ambientazione" value="<?=(isset($datapost['anno_ambientazione']))?$datapost['anno_ambientazione']:'';?>" class="form-control" id="anno" aria-describedby="anno_ambientazione" placeholder="Anno di ambientazone" required>
            <div class="invalid-feedback">
                Campo obbligatorio.
            </div>
        </div>
    </div>
    <div class="mb-3 col-md-2 pt-1 pb-1 ps-4 pe-4 bg-wheat rounded">
        <label for="stato" class="form-label">Stato<span class="text-required"> * </span></label>
        <div class="input-group has-validation">
            <!--0-in attesa approvazione,1-in lavorazione,2-pubblicata,3-nascosta-->
            <select class="select form-control" name="stato" id="stato" required="required">
                <option value="0" <?php if(isset($datapost['stato']) && $datapost['stato']==0)echo 'selected="selected"';?>>Attesa di approvazione</option>
                <option value="1" <?php if(isset($datapost['stato']) &&$datapost['stato']==1)echo 'selected="selected"';?>>Il lavorazione</option>
                <option value="2" <?php if(isset($datapost['stato']) &&$datapost['stato']==2)echo 'selected="selected"';?>>Pubblicata</option>
                <option value="3" <?php if(isset($datapost['stato']) &&$datapost['stato']==3)echo 'selected="selected"';?>>Nascosta</option>
            </select>
            <div class="invalid-feedback">
                Campo obbligatorio.
            </div>
        </div>
    </div>
    <div class="mb-3 col-md-3 pt-1 pb-1 ps-4 pe-4">
        <label for="editore" class="form-label">Editore<span class="text-required"> * </span></label>
        <div class="input-group has-validation">
            <input type="text" name="editore" value="<?=(isset($datapost['editore']))?$datapost['editore']:'';?>" class="form-control" id="editore" aria-describedby="editore" placeholder="Editore" required>
            <div class="invalid-feedback">
                Campo obbligatorio.
            </div>
        </div>
    </div>
    <div class="mb-3 col-6 pt-1 pb-1 ps-4 pe-4">
        <label for="titolo" class="form-label">Titolo<span class="text-required"> * </span></label>
        <div class="input-group has-validation">
            <input type="text" name="titolo" value="<?=(isset($datapost['titolo']))?$datapost['titolo']:'';?>" class="form-control" id="titolo" aria-describedby="titolo" placeholder="Titolo della storia" required>
            <div class="invalid-feedback">
                Campo obbligatorio.
            </div>
        </div>
    </div>
    <div class="mb-3 col-6 pt-1 pb-1 ps-4 pe-4">
        <label for="slug" class="form-label">Slug<span class="text-required"> * </span></label>
        <div class="input-group has-validation">
            <input type="text" class="form-control" id="slug" name="slug" placeholder="Slug-autogenerato" value="<?=(isset($datapost['slug']))?$datapost['slug']:'';?>" required="required" readonly="readonly" />
        </div>
        <div><span class="alert alert-danger mt-3 d-none messaggioslugerrore"></span></div>
    </div>
    <div class="row">
        <div class="col-md-6 ps-4 pe-4">
            <div class="mb-3 col-12 pt-1 pb-1">
                <label for="abstract" class="form-label">Abstract<span class="text-required"> * </span></label>
                <div class="input-group has-validation">
                    <textarea name="abstract" id="abstract" rows="2" required="required"><?=(isset($datapost['abstract']))?$datapost['abstract']:'';?></textarea>
                    <div class="invalid-feedback">
                        Campo obbligatorio.
                    </div>
                </div>
            </div>
            <div class="mb-3 col-12 pt-1 pb-1">
                <label for="copyright" class="form-label">Copyright<span class="text-required"> * </span></label>
                <div class="input-group has-validation">
                    <textarea name="copyright" id="copyright" rows="2" required="required"><?=(isset($datapost['copyright']))?$datapost['copyright']:'';?></textarea>
                    <div class="invalid-feedback">
                        Campo obbligatorio.
                    </div>
                </div>
            </div>
            <div class="mb-3 col-12 pt-1 pb-1">
                <label for="descrizione" class="form-label">Descrizione</label>
                <div class="input-group has-validation">
                    <textarea name="descrizione" id="descrizione" rows="2"><?=(isset($datapost['descrizione']))?$datapost['descrizione']:'';?></textarea>
                    <div class="invalid-feedback">
                        Campo obbligatorio.
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 ps-4">
            <div class="mb-3 col-12 pt-1 pb-1 ps-3 pe-3">
                <label for="linkzoodiac" class="form-label">Link Zoodiac</label>
                <div class="input-group has-validation">
                    <input type="text" name="linkzoodiac" value="<?=(isset($datapost['linkzoodiac']))?$datapost['linkzoodiac']:'';?>" class="form-control" id="linkzoodiac" aria-describedby="linkzoodiac" placeholder="Link portale Zoodiac" />
                </div>
            </div>
            <div class="mb-3 col-12 pt-1 pb-1 ps-3 pe-3">
                <label for="linkspvet" class="form-label">Link SPVet.it</label>
                <div class="input-group has-validation">
                    <input type="text" name="linkspvet" value="<?=(isset($datapost['linkspvet']))?$datapost['linkspvet']:'';?>" class="form-control" id="linkspvet" aria-describedby="linkspvet" placeholder="Link SPVet.it" />
                </div>
            </div>
            <!-- collaboratori -->
            <div class="row m-3 ps-1 pe-1 pt-3 border rounded overflow-hidden flex-md-row mb-4 container-list-story position-relative">
                <h5>Collaboratori</h5>
                <div class="col-md-8 mb-3">
                    <div class="input-group">
                        <select class="select form-control" name="sel_collaboratori" id="sel_collaboratori">
                            <option value="">Scegli un collaboratore ed aggiungilo</option>
                            <option value="0">NUOVO COLLABORATORE</option>
                            <?php foreach ($collaboratori AS $collaboratore){?>
                            <option value="<?=$collaboratore->collid;?>"><?=$collaboratore->cognome.' '.$collaboratore->nome;?></option>
                            <?php } ?>
                        </select>
                        <div class="input-group-prepend">
                            <button class="btn btn-outline-secondary addcollaboratore" type="button">Aggiungi</button>
                        </div>
                    </div>
                </div>
                <div class="p-3 border-top"></div>
                <div class="tmp_sel_ruolo d-none">
                    <select class="select form-control w-100" id="tmp_sel_ruolo" name="tmp_sel_ruol">
                        <option value="">Ruolo del collaboratore nella storia</option>
                        <?php foreach ($ruoli AS $ruolo){ ?>
                        <option value="<?=$ruolo->rid;?>"><?=$ruolo->nomeruolo;?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-12 elenco-collaboratori">
                    <?php //echo '<pre>';print_r($collaboratoristoria);echo '</pre>';?>
                    <?php if(count($collaboratoristoria->all())>0){ ?>
                        <?php foreach ($collaboratoristoria AS $collabstoria){ ?>
                            <div class="collaboratore d-flex mb-2">
                                <input type="hidden" name="collid[]" value="<?=$collabstoria->collid;?>" />
                                <div class="d-inline-flex me-3"><input class="form-control" type="text" name="nomecollaboratore[]" value="<?=$collabstoria->nome;?>" placeholder="Nome" required readonly /></div>
                                <div class="d-inline-flex me-3"><input class="form-control" type="text" name="cognomecollaboratore[]" value="<?=$collabstoria->cognome;?>" placeholder="Cognome" required readonly /></div>
                                <div class="d-inline-flex me-3">
                                    <select class="select form-control w-100 sel_ruolo" name="sel_ruolo[]" required>
                                        <option value="">Ruolo del collaboratore nella storia</option>
                                        <?php foreach ($ruoli AS $ruolo){ ?>
                                        <option value="<?=$ruolo->rid;?>" <?php if($collabstoria->rid==$ruolo->rid)echo 'selected="selected"';?>><?=$ruolo->nomeruolo;?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="d-inline-flex"><span class="deleteCollaboratore bg-danger font-white p-2 rounded pointer"><i class="fa fa-close"></i></span></div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
            <!-- allegati multimediali -->
            <div class="row m-3 ps-1 pe-1 pt-3 border rounded overflow-hidden flex-md-row mb-4 container-list-story position-relative">
                <h5>Podcast</h5>
                <div class="input-group input-group-sm ">
                    <input type="file" class="form-control" id="podcast" name="podcast" />
                    <span class="input-group-text btn-danger removepodcastfile">elimina selezione</span>
                </div>
                <hr class="mt-3 mb-0"/>
                <?php if(count($podcast->all())>0){ 
                        foreach ($podcast AS $singolopodcast){
                            if(!$singolopodcast->linkurlhtml){
                                //video interno
                                $urldownloadpod=url('storageallegatistorie/'.$datapost['sid'].'/'.$singolopodcast->nome_file_memorizzato.'/'.$singolopodcast->nome_file_originale);
                                //$pathpod = storage_path('app/allegatimultimedialistorie/'.$datapost['sid'].'/'. $singolopodcast->nome_file_memorizzato); ?>
                                <div class="col-12 mt-2">
                                    <!--<input class="form-control mb-3" type="file" id="video" name="video" />-->
                                    <a href="{{$urldownloadpod}}"><img src="/images/mp3-icon.png" width="50" /><span class="ms-2"><?=html_entity_decode($singolopodcast->nome_file_originale,ENT_QUOTES,'utf-8')?></span></a>
                                    <span class="btn btn-danger btn-sm removepodcastAJAX fRight">cancella podcast</span>
                                </div>
                            <?php } ?>
                        <?php }
                    } ?>
            </div>
            <div class="row m-3 ps-1 pe-1 pt-3 border rounded overflow-hidden flex-md-row mb-4 container-list-story position-relative">
                <h5>Video</h5>
                <div class="mb-3">
                    <?php $showlink='d-none';$checkradiourl='';$showimage='d-none';$checkimage='';$checkimagepredef='checked';
                    if(count($video->all())>0){
                            foreach ($video AS $singolovideo){
                                if($singolovideo->linkurlhtml!=''){
                                    $showlink='';
                                    $checkradiourl='checked';
                                }
                                if($singolovideo->imgpredef!=''){
                                    $showimage='';
                                    $checkimage='checked';
                                    $checkimagepredef='';
                                }    
                            }
                        }
                    ?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input tipovideo" type="radio" name="tipovideo" id="tipovideo1" value="1" {{$checkradiourl}} />
                        <label class="form-check-label" for="tipovideo1">URL/HTML</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input tipovideo" type="radio" name="tipovideo" id="tipovideo2" value="2" />
                        <label class="form-check-label" for="tipovideo2">Carica Video</label>
                    </div>
                    <hr class="mt-0">
                    <div class="containerurlvideo {{$showlink}}">
                        <p>Inserisci l'url</p>
                        <textarea class="form-control linkurlhtml" name="linkurlhtml" placeholder="Inserisci l'url"><?=(isset($singolovideo))?$singolovideo->linkurlhtml:'';?> </textarea>
                        
                        
                        <?php ?>
                        
                        <div class="container-imgpredef mt-2">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input imgpredef" type="radio" name="imgpredef" id="imgpredef1" value="1" {{$checkimagepredef}}   />
                                <label class="form-check-label" for="imgpredef1">Usa IMG predefinita</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input imgpredef" type="radio" name="imgpredef" id="imgpredef2" value="2"  {{$checkimage}} />
                                <label class="form-check-label" for="imgpredef2">Carica immagine</label>
                            </div>
                            
                            
                            <div class="containeruploadimgpredef {{$showimage}}">
                                <div class="input-group input-group-sm ">
                                    <input type="file" class="form-control" id="imgpredef" name="imgpredef" />
                                    <span class="input-group-text btn-danger removeimgpredeffile">elimina selezione</span>
                                </div>
                                
                                 <?php if(count($video->all())>0){ 
                                foreach ($video AS $singolovideo){
                                    if($singolovideo->imgpredef!=''){?>
                                        <div class="col-12 mt-2">
                                            <!--<input class="form-control mb-3" type="file" id="video" name="video" />-->
                                            <img src="/images/png-icon.png" width="50" /><span class="ms-2"><?=html_entity_decode($singolovideo->imgpredef,ENT_QUOTES,'utf-8')?></span></a>
                                            <span class="btn btn-danger btn-sm removeimgpredefAJAX fRight">cancella immagine</span>
                                        </div>
                                    <?php }?>
                                <?php }
                            } ?>
                            </div>

                    </div>

                        
                    </div>
                    <div class="containeruploadvideo d-none">
                        <div class="input-group input-group-sm ">
                            <input type="file" class="form-control" id="video" name="linkvideo" />
                            <span class="input-group-text btn-danger removevideofile">elimina selezione</span>
                        </div>
                    </div>
                    <hr class="mt-3 mb-0"/>
                    <?php if(count($video->all())>0){ 
                        foreach ($video AS $singolovideo){
                            if(!$singolovideo->linkurlhtml){
                                //video interno
                                $urldownload=url('storageallegatistorie/'.$datapost['sid'].'/'.$singolovideo->nome_file_memorizzato.'/'.$singolovideo->nome_file_originale);
                                //$path = storage_path('app/allegatimultimedialistorie/'.$datapost['sid'].'/'. $singolovideo->nome_file_memorizzato); ?>
                                <div class="col-12 mt-2">
                                    <!--<input class="form-control mb-3" type="file" id="video" name="video" />-->
                                    <a href="{{$urldownload}}"><img src="/images/mp4-icon.png" width="50" /><span class="ms-2"><?=html_entity_decode($singolovideo->nome_file_originale,ENT_QUOTES,'utf-8')?></span></a>
                                    <span class="btn btn-danger btn-sm removeimgpredefAJAX fRight">cancella video</span>
                                </div>
                            <?php }?>
                        <?php }
                    } ?>
                </div>
            </div>
            <div class="row m-3 ps-1 pe-1 pt-3 border rounded overflow-hidden flex-md-row mb-4 container-list-story position-relative">
                <h5>PDF storia</h5>
                <div class="input-group input-group-sm ">
                    <input type="file" class="form-control" id="pdfstoria" name="pdfstoria" />
                    <span class="input-group-text btn-danger removepdfstoriafile">elimina selezione</span>
                </div>
                <hr class="mt-3 mb-0"/>
                <?php if(count($pdfstoria->all())>0){ 
                        foreach ($pdfstoria AS $singolopdf){
                            if(!$singolopdf->linkurlhtml){
                                //pdf interno
                                $urldownloadpdf=url('storageallegatistorie/'.$datapost['sid'].'/'.$singolopdf->nome_file_memorizzato.'/'.$singolopdf->nome_file_originale);?>
                                <div class="col-12 mt-2">
                                    <!--<input class="form-control mb-3" type="file" id="video" name="video" />-->
                                    <a href="{{$urldownloadpdf}}" target="_blank" ><img src="/images/pdf-icon.png" width="50" /><span class="ms-2"><?=html_entity_decode($singolopdf->nome_file_originale,ENT_QUOTES,'utf-8')?></span></a>
                                    <span class="btn btn-danger btn-sm removepdfstoriaAJAX fRight">cancella pdf</span>
                                </div>
                            <?php } ?>
                        <?php }
                    } ?>
            </div>
            <input type="hidden" id="filepodcast" name="filepodcast" value="<?=(isset($singolopodcast->nome_file_memorizzato))?$singolopodcast->nome_file_memorizzato:'';?>" />
            <input type="hidden" id="filevideo" name="filevideo" value="<?=(isset($singolovideo->nome_file_memorizzato))?$singolovideo->nome_file_memorizzato:'';?>" />
            <input type="hidden" id="filepdf" name="filepdf" value="<?=(isset($singolopdf->nome_file_memorizzato))?$singolopdf->nome_file_memorizzato:'';?>" />
            <input type="hidden" id="fileimgpredef" name="fileimgpredef" value="<?=(isset($singolovideo->imgpredef))?$singolovideo->imgpredef:'';?>" />

        </div>
    </div>
    <hr>
    <div class="row bg-wheat-transp g-0 border rounded overflow-hidden flex-md-row mb-4 container-list-story position-relative">
            <div class="">
              <div class="fLeft"><h5 class="ps-4 pt-4 pe-4 mb-0">Parti della storia</h5></div>
              <div class="fRight ps-4 pt-4 pe-4 mb-0"><a class="btn btn-primary button-add-part"><i class="fa fa-plus"></i>Aggiungi parte</a></div>
            </div>
        <div class="container-parti">
    <?php if(isset($fasistoria) && count($fasistoria->all())>0){
            $i=1;?>
            <?php foreach ($fasistoria AS $fase){ ?>
                <div class="contenitore-parte border rounded overflow-hidden flex-md-row m-3 p-3">
                    <div class="row">
                        <div class="col-md-7">
                            <input type="hidden" name="sfid[]" value="<?=$fase->sfid;?>" />
                            <div class="testo-numero-parte h5">Parte&nbsp;<span class="numero-parte"><?=$i;?></span>
                                <?php if(array_key_exists($fase->sfid,$approfondimentifasi)){ ?>
                                    <span class="d-inline-flex ms-3"><button type="button" class="btn bg-info fw-bold w-100 hover-color-white btn-sm" onclick="getphaseintegrations(<?=$fase->sfid;?>);" data-bs-toggle="modal" data-bs-target="#modalIntegrazioni">
                                       <?='Leggi '.$approfondimentifasi[$fase->sfid].' integrazione/i pubblicate';?>
                                       </button>
                                    </span>
                                <?php } ?>
                                <div class="fRight"><span class="deletePart bg-danger font-white p-2 rounded pointer btn-sm"><i class="fa fa-close"></i> Elimina Parte</span></div>
                            </div>
                            <div class="titolo-parte">                                
                                <div class="valore-numero-parte">
                                    <div class="mb-3 col-12">
                                        <label for="titolo-<?=$i;?>" class="form-label">Titolo Parte<span class="text-required"> * </span></label>
                                        <div class="input-group has-validation">
                                            <input type="text" name="titolofase[]" value="<?=html_entity_decode($fase->titolofase,ENT_QUOTES,'utf-8');?>" class="form-control input-titoloparte" id="titolo-<?=$i;?>" aria-describedby="titolo-<?=$i;?>" required />
                                            <div class="invalid-feedback">
                                                Campo obbligatorio.
                                            </div>
                                        </div>
                                    </div>                                         
                                </div>
                            </div>
                            <div class="descrizione-parte">
                                <div class="mb-3 col-12">
                                    <label for="descrizione-parte-<?=$i;?>" class="form-label">Descrizione parte<span class="text-required"> * </span></label>
                                    <div class="input-group has-validation">
                                        <textarea class="form-select descrizione-parte" rows="3" id="descrizione-parte-<?=$i;?>" name="testofase[]" placeholder="" required><?=html_entity_decode($fase->testofase,ENT_QUOTES,'utf-8');?></textarea>
                                        <div class="invalid-feedback">
                                            Campo obbligatorio.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="mb-3 mt-4 col-12 contenitoresnippetsfase">
                                <div class="d-flex justify-content-between mb-2">
                                    <div class="d-inline-flex">
                                    <h5 class="mb-0">Snippets</h5>
                                    </div>
                                    <div class="d-inline-flex">
                                        <input type="button" class="btn btn-sm btn-outline-success me-2 nuovo-snippet" value="Nuovo Snippet" />
                                        <input type="button" class="btn btn-sm btn-outline-danger elimina-snippet" value="Elimina Snippet" />
                                    </div>
                                </div>
                                <select class="form-control snippetsfase" multiple>
                                    <?php if(isset($snippetfase[$fase->sfid])){
                                        foreach ($snippetfase[$fase->sfid] AS $snip){?>
                                            <option value="<?=$snip->snid;?>"><?=$snip->titolo;?></option>
                                        <?php }
                                    }?>
                                </select>
                                <hr />
                                <?php if(isset($snippetfase[$fase->sfid])){
                                    foreach ($snippetfase[$fase->sfid] AS $snipval){?>
                                        <div class="blocco-snippet row d-none" snid="<?=$snipval->snid;?>">
                                            <input type="hidden" name="snid[<?=$fase->sfid;?>][]" value="<?=$snipval->snid;?>" />
                                            <div class="mb-3 col-md-6">
                                                <label for="titolosnippet-<?=$snipval->snid;?>" class="form-label">Titolo Snippet<span class="text-required"> * </span></label>
                                                <div class="input-group has-validation">
                                                    <input type="text" name="titolosnippet[<?=$fase->sfid;?>][]" value="<?=html_entity_decode($snipval->titolo,ENT_QUOTES,'utf-8');?>" class="form-control titolosnippet" id="titolosnippet-<?=$snipval->snid;?>" aria-describedby="titolosnippet-<?=$snipval->snid;?>" required />
                                                    <div class="invalid-feedback">
                                                        Campo obbligatorio.
                                                    </div>
                                                </div>
                                            </div>   
                                            <div class="mb-3 col-md-6">
                                                <label for="chiavesnippet-<?=$snipval->snid;?>" class="form-label">Chiave Snippet<span class="text-required"> * </span></label>
                                                <div class="input-group has-validation">
                                                    <input type="text" name="chiavesnippet[<?=$fase->sfid;?>][]" value="<?=html_entity_decode($snipval->chiave,ENT_QUOTES,'utf-8');?>" class="form-control chiavesnippet" id="chiavesnippet-<?=$snipval->snid;?>" aria-describedby="chiavesnippet-<?=$snipval->snid;?>" required />
                                                    <div class="invalid-feedback">
                                                        Campo obbligatorio.
                                                    </div>
                                                </div>
                                            </div>   
                                            <div class="mb-3 col-12">
                                                <label for="testosnippet-<?=$snipval->snid;?>" class="form-label">Testo Snippet<span class="text-required"> * </span></label>
                                                <div class="input-group has-validation">
                                                    <textarea class="form-select testosnippet" rows="3" id="testosnippet-<?=$snipval->snid;?>" name="testosnippet[<?=$fase->sfid;?>][]" placeholder="" required><?=html_entity_decode($snipval->testo,ENT_QUOTES,'utf-8');?></textarea>
                                                    <div class="invalid-feedback">
                                                        Campo obbligatorio.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3 col-12">
                                                <!--<input type="button" class="btn btn-sm btn-info accetta-snippet" value="Accetta Snippet" />-->
                                                <input type="button" class="btn btn-sm btn-warning chiudi-snippet" value="Chiudi Snippet" />
                                            </div>
                                        </div>
                                    <?php }
                                }?>
                            </div>  
                        </div>
                    </div>
                </div>
        <?php $i++;} ?>
    <?php }?>
    </div> 
        <div class="mb-3">
            <div class="fRight ps-4 pt-4 pe-4 mb-0"><a class="btn btn-primary button-add-part"><i class="fa fa-plus"></i>Aggiungi parte</a></div>
        </div> 
    </div>
    <div class="w-100 checkerroripreinvio d-none">
        <div class="row ps-3 pe-3">
            <div class="col-12 alert alert-danger msgContainerError font-12"></div>
        </div>     
    </div> 
    <div class="mb-3">
        <div class="ps-4 pe-4 mb-0">
            <button type="submit" class="btn btn-success salvastoria btn-showloader" title-loader="ATTENDERE..." text-loader="Il sistema sta memorizzando le informazioni<br />Non chiudere la finestra del browser...<br />(potrebbe volerci qualche minuto)"><strong>Salva la storia</strong></button>
            <a href="{{ url()->previous() }}" class="btn btn-secondary"><i class="fa fa-fast-backward"></i><strong>&nbsp;Torna indietro</strong></a>
        </div>
    </div>  
</div>               
    <input type="hidden" id="sid" name="sid" value="<?=(isset($datapost['sid']))?$datapost['sid']:((isset($storiasubmit->sid))?$storiasubmit->sid:'');?>" />
    <input type="hidden" id="uid" name="uid" value="<?=(isset($datapost['uid']))?$datapost['uid']:'';?>" />
</form>
@endsection