@extends('admin.layout.base')

@section('additionaljs')
    @parent
    <script type="text/javascript" src="/js/form-control.js"></script>
    <script type="text/javascript">
        var rottaupload="<?=route('ckeditor.uploadpublicimage', ['_token' => csrf_token() ]);?>";
        CKEDITOR.replace( 'testoprivacy', {
            customConfig: '/js/ckeditor_configs/config_simple_heightvariable.js',
            filebrowserUploadUrl: rottaupload,
            filebrowserUploadMethod: 'form',
            height: '400'
        });
    </script>
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

<?php //echo '<pre>';print_r($form);exit;?>

<form action="{{route($form)}}" id="gestioneprivacy" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate>
    {{ csrf_field() }}
    <div class="row p-2">
        <div class="col-md-2">
            <div class="mt-3"><strong>Data Inserimento:</strong><br /><?=(isset($privacy->data_inserimento))?$privacy->data_inserimento:'';?></div>
            <div class="mt-3"><strong>Data Pubblicazione:</strong><br /><?=(isset($privacy->data_pubblicazione))?$privacy->data_pubblicazione:'';?></div>
        </div>
        <div class="col-md-10">
            <input type="hidden" class="form-control" name="data_inserimento" value="<?=(isset($privacy->data_inserimento))?$privacy->data_inserimento:'';?>" />
            <input type="hidden" class="form-control" name="data_pubblicazione" value="<?=(isset($privacy->data_pubblicazione))?$privacy->data_pubblicazione:'';?>" />
            <div class="btn-group" role="group" aria-label="Seleziona una risposta">
                <h4 class="me-2">Attuale</h4>
                <input type="radio" value="1" class="btn-check" name="attuale" id="btnradio1" autocomplete="off" <?php if(isset($privacy->attuale) && $privacy->attuale==1)echo 'checked="checked"';?> />
                <label class="btn btn-outline-primary" for="btnradio1">SI</label>

                <input type="radio" value="0" class="btn-check" name="attuale" id="btnradio2" autocomplete="off" <?php if(isset($privacy->attuale) && $privacy->attuale==0 || !isset($privacy->attuale))echo 'checked="checked"';?> />
                <label class="btn btn-outline-primary" for="btnradio2">NO</label>
            </div>
            <div class="btn-group ms-md-5" role="group" aria-label="Seleziona una risposta">
                <h4 class="me-2">Riconferma al login</h4>
                <input type="radio" value="1" class="btn-check" name="reflag" id="btnradio3" autocomplete="off" <?php if(isset($privacy->reflag) && $privacy->reflag==1)echo 'checked="checked"';?> />
                <label class="btn btn-outline-primary" for="btnradio3">SI</label>

                <input type="radio" value="0" class="btn-check" name="reflag" id="btnradio4" autocomplete="off" <?php if(isset($privacy->reflag) && $privacy->reflag==0 || !isset($privacy->reflag))echo 'checked="checked"';?> />
                <label class="btn btn-outline-primary" for="btnradio4">NO</label>
            </div>
            <hr>
            <h4>Testo</h4>
            <textarea class="form-control" rows="20" id="testoprivacy" name="testoprivacy"><?=(isset($privacy->testoprivacy))?$privacy->testoprivacy:'';?></textarea>   
        </div>
    </div>
    <hr>
    <input type="hidden" name="ppid" id="ppid" value="<?=(isset($ppid))?$ppid:'';?>" />
    <button class="btn btn-form-submit" type="submit">Salva</button>
    <a href="{{ url()->previous() }}" class="btn btn-secondary"><i class="fa fa-fast-backward"></i><strong>&nbsp;Indietro</strong></a>
</form>
@endsection