@extends('admin.layout.base')

@section('additionaljs')
    @parent
    <script type="text/javascript" src="/js/form-control.js"></script>
    <?php if($configuration->typeconf==1){ //ckeditor only with textarea type content?>
        <script type="text/javascript">
            CKEDITOR.replace( 'valueconfig', {
                customConfig: '/js/ckeditor_configs/config_text.js',
            });
        </script>
    <?php } ?>
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

<?php //echo '<pre>';print_r($configuration);echo '</pre>';?>
<form action="{{route($form)}}/{{$configuration->confid}}" id="gestioneconfigurazione" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">
    {{ csrf_field() }}
    
    <div class="row">
        <div class="col-12 col-md-4">
            <div class="row p-2">
                <div class="mt-3"><strong>Nome:</strong><br /><?=$configuration->nameconfig;?></div>
                <div class="mt-3"><strong>Descrizione:</strong><br /><?=$configuration->descbase;?></div>
                <div class="mt-3"><strong>Desc. Approfondita:</strong><br /><?=$configuration->desctooltip;?></div>
                <div class="mt-3"><strong>Sezione:</strong><br />
                <?php switch($configuration->groupsection){ //0-generale, 1-storie, 2-zoonosi, 3-integrazioni, 4-utenti
                        case 0:
                            echo 'GENERALE';
                            break;
                        case 1:
                            echo 'STORIE';
                            break;
                        case 2:
                            echo 'ZOONOSI';
                            break;
                        case 3:
                            echo 'INTEGRAZIONI';
                            break;
                        case 4:
                            echo 'UTENTI';
                            break;
                        default:
                            echo 'ND';
                            break;    
                    }?>
                </div>
                <div class="mt-3"><strong>Ultima modifica:</strong><br /><?=Carbon\Carbon::createFromFormat('Y-m-d', $configuration->datamodified)->format('d/m/Y');?></div>
            </div>
        </div>
        <div class="col-12 col-md-6 border-start">
            <div class="row p-2">
                <div class="mt-3"><strong>VALORE:</strong><br /><br />
                <input type="hidden" class="form-control" name="nameconfig" value="<?=$configuration->nameconfig;?>" />
                <input type="hidden" class="form-control" name="typeconf" value="<?=$configuration->typeconf;?>" />
                <?php switch($configuration->typeconf){   //0-INPUT TEXT, 1-TEXTAREA, 2-CHECKBOX, 3-RADIOBOX, 4-FILE
                            case 0: ?>
                                <input type="text" class="form-control" id="valueconfig" name="valueconfig" value="<?=$configuration->valueconfig;?>" />
                            <?php break; ?>
                            <?php case 1: ?>
                                <textarea class="form-control" id="valueconfig" name="valueconfig"><?=$configuration->valueconfig;?></textarea>
                            <?php break;?>
                            <?php case 2: ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="checktype2"  <?php if($configuration->valueconfig==1)echo 'checked="checked"';?>>
                                    <label class="form-check-label" for="checktype2">
                                        Selezionare la checkbox per "<b>SI</b>"
                                    </label>
                                </div>
                            <?php break;?>
                            <?php case 3: ?>
                                <div class="btn-group" role="group" aria-label="Seleziona una risposta">
                                    <input type="radio" class="btn-check" name="valueconfig" id="btnradio1" autocomplete="off" <?php if($configuration->valueconfig==1)echo 'checked="checked"';?> />
                                    <label class="btn btn-outline-primary" for="btnradio1">SI</label>

                                    <input type="radio" class="btn-check" name="valueconfig" id="btnradio2" autocomplete="off" <?php if($configuration->valueconfig==0)echo 'checked="checked"';?> />
                                    <label class="btn btn-outline-primary" for="btnradio2">NO</label>
                                </div>
                            <?php break;?>
                            <?php case 4: ?>
                                <p><b>Scegli una nuova immagine:</b></p>
                                <input type="file" class="form-control" id="valueconfig" name="valueconfig" value="" />
                                <hr>
                                <p><b>Attuale:</b></p>
                                <input type="hidden" class="" id="oldvalueconfig" name="oldvalueconfig" value="<?=$configuration->valueconfig;?>" />
                                <?php if($configuration->valueconfig!=''){ ?>
                                <div>
                                    <img src="/images/png-icon.png" width="50" /><span class="ms-2"><?=html_entity_decode($configuration->valueconfig,ENT_QUOTES,'utf-8')?></span>
                                    <span class="btn btn-danger btn-sm removeimg">Cancella contenuto</span>
                                </div>
                                <?php } ?>
                                <?php break;?>
                            <?php default: ?>
                                <?php break;?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <input type="hidden" name="confid" id="confid" value="<?=$confid;?>" />
    <button class="btn btn-form-submit" type="submit">Salva</button>
    <a href="{{ url()->previous() }}" class="btn btn-secondary"><i class="fa fa-fast-backward"></i><strong>&nbsp;Indietro</strong></a>
    </div>
</form>
@endsection