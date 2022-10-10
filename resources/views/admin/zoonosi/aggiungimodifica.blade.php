@extends('admin.layout.base')

@section('additionaljs')
    @parent
    <script type="text/javascript" src="/js/form-control.js"></script>
    <script type="text/javascript" src="/js/zoonosi.js"></script>
    <script type="text/javascript" src="/js/ckeditor_configs/config_simple.js"></script>
    <script type="text/javascript">
        CKEDITOR.replace( 'descrizione', {
            customConfig: '/js/ckeditor_configs/config_simple.js',
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
<form action="{{route($form)}}" id="gestionezoonosi" method="POST" class="needs-validation" novalidate>
    {{ csrf_field() }}
    <div class="row">
        <div class="col-7">
            <div class="row mt-2">
                <div class="col-md-4">
                    <label class="labels">Nome Zoonosi <span class="required">*</span></label><input type="text" class="form-control" id="nomezoonosi" name="nome" placeholder="Nome zoonosi" value="<?=(isset($datapost['nome']))?$datapost['nome']:'';?>" required="required" />
                    <label class="labels">Slug<span class="required">*</span></label><input type="text" class="form-control" id="slugzoonosi" name="slugzoonosi" placeholder="Slug-autogenerato" value="<?=(isset($datapost['slugzoonosi']))?$datapost['slugzoonosi']:'';?>" required="required" readonly="readonly" />
                    <span class="alert alert-danger mt-3 d-none messaggioslugerrore"></span>
                </div>
                <div class="col-md-8"><label class="labels">Descrizione <span class="required">*</span></label><textarea class="form-select" rows="3" id="descrizione" name="descrizione" placeholder="Inserisci la descrizione della zoonosi" required="required"><?=(isset($datapost['descrizione']))?html_entity_decode($datapost['descrizione']):'';?></textarea></div>
            </div>
            <div class="row mt-2">
            </div>
            <div class="row mt-3">
                <div class="col-md-6"></div>
            </div>
        </div>
        <div class="col-5 border-start">
            <div class="row mt-2">
                <div class="col-md-12"><label class="labels">Link canale Telegram</label><input type="text" class="form-control" id="linktelegram" name="linktelegram" placeholder="Link canale Telegram" value="<?=(isset($datapost['linktelegram']))?$datapost['linktelegram']:'';?>" /></div>
            </div>
            <div class="row mt-3">
                <div class="col-md-8"><label class="labels">URL immagine <span class="required">*</span></label><textarea class="form-select" rows="3" id="img_url" name="img_url" placeholder="Inserisci l'URL dell&apos;immagine della zoonosi" required="required"><?=(isset($datapost['img_url']))?html_entity_decode($datapost['img_url']):'';?></textarea></div>
            </div>
            <div class="row mt-3">
                <div class="col-md-8"><label class="labels">Descrizione immagine<span class="required">*</span></label><textarea class="form-select" rows="3" id="img_desc" name="img_desc" placeholder="Inserisci la descrizione dell'immagine" required="required"><?=(isset($datapost['img_desc']))?html_entity_decode($datapost['img_desc']):'';?></textarea></div>
            </div>
            <div class="row mt-2">
                <div class="col-md-12"><label class="labels">Link raccolte Review</label><input type="text" class="form-control" id="linkraccoltereview" name="linkraccoltereview" placeholder="Link Raccolte Review" value="<?=(isset($datapost['linkraccoltereview']))?$datapost['linkraccoltereview']:'';?>" /></div>
            </div>
        </div>
    </div>
    <hr>
    <input type="hidden" name="zid" id="zid" value="<?=(isset($datapost['zid']))?$datapost['zid']:'';?>" />
    <button class="btn btn-form-submit" type="submit">Salva</button>
    <a href="{{ url()->previous() }}" class="btn btn-secondary"><i class="fa fa-fast-backward"></i><strong>&nbsp;Indietro</strong></a>
    </div>
</form>
@endsection