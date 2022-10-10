@extends('admin.layout.base')

@section('additionaljs')
    @parent
    <script type="text/javascript" src="/js/form-control-daticontesto.js"></script>
    <script type="text/javascript">var rottaupload="<?=route('ckeditor.upload', ['_token' => csrf_token() ]);?>";</script>
    <script type="text/javascript" src="/js/gestisci-daticontesto.js"></script>
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


<form action="{{route($form)}}" id="gestionedaticontesto" method="POST" class="needs-validation formgestiscidaticontesto" enctype="multipart/form-data" novalidate>
    {{ csrf_field() }}
    <div class="row bg-wheat-transp g-0 border rounded overflow-hidden flex-md-row mb-4 container-list-story position-relative">
        <div class="">
            <div class="fLeft"><h5 class="ps-4 pt-4 pe-4 mb-0">Dati di contesto</h5></div>
            <div class="fRight ps-4 pt-4 pe-4 mb-0"><a class="btn btn-primary button-add-part"><i class="fa fa-plus"></i>Aggiungi dato</a></div>
        </div>
        <div class="container-parti">
            <?php if(isset($daticontesto) && count($daticontesto->all())>0){$i=1;?>
                <?php foreach ($daticontesto AS $dato){ ?>
                    <div class="contenitore-parte border rounded overflow-hidden flex-md-row m-3 p-3">
                        <div class="col-md-12">
                            <input type="hidden" name="dbid[]" value="<?=$dato->dbid;?>" />
                            <div class="titolo-parte">
                                <div class="fRight"><span class="deletePart bg-danger font-white p-2 rounded pointer"><i class="fa fa-close"></i> Elimina Dato</span></div>
                                <div class="testo-numero-parte h5">Dato&nbsp;<span class="numero-parte"><?=$i;?></span></div>
                                <div class="valore-numero-parte">
                                    <div class="mb-3 col-12">
                                        <label for="domanda-<?=$i;?>" class="form-label">Titolo<span class="text-required"> * </span></label>
                                        <div class="input-group has-validation">
                                            <input type="text" name="domanda[]" value="<?=html_entity_decode($dato->domanda,ENT_QUOTES,'utf-8');?>" class="form-control input-domanda" id="domanda-<?=$i;?>" aria-describedby="domanda-<?=$i;?>" required />
                                            <div class="invalid-feedback">
                                                Campo obbligatorio.
                                            </div>
                                        </div>
                                    </div>                                         
                                </div>
                            </div>
                            <div class="descrizione-parte">
                                <div class="mb-3 col-12">
                                    <label for="risposta-<?=$i;?>" class="form-label">Descrizione<span class="text-required"> * </span></label>
                                    <div class="input-group has-validation">
                                        <textarea class="form-select risposta" rows="3" id="risposta-<?=$i;?>" name="risposta[]" placeholder="" required><?=html_entity_decode($dato->risposta,ENT_QUOTES,'utf-8');?></textarea>
                                        <div class="invalid-feedback">
                                            Campo obbligatorio.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php $i++;} ?>
            <?php }?>
        </div> 
        <div class="mb-3">
            <div class="fRight ps-4 pt-4 pe-4 mb-0"><a class="btn btn-primary button-add-part"><i class="fa fa-plus"></i>Aggiungi dato</a></div>
        </div> 
    </div>
    <div class="w-100 checkerroripreinvio d-none">
        <div class="row ps-3 pe-3">
            <div class="col-12 alert alert-danger msgContainerError font-12"></div>
        </div>     
    </div> 
    <div class="mb-3">
        <div class="ps-4 pe-4 mb-0">
            <button type="submit" class="btn btn-success salvadaticontesto btn-showloader" title-loader="ATTENDERE..." text-loader="Il sistema sta memorizzando le informazioni<br />Non chiudere la finestra del browser...<br />(potrebbe volerci qualche minuto)"><strong>Salva dati di contesto</strong></button>
            <a href="{{ url()->previous() }}" class="btn btn-secondary"><i class="fa fa-fast-backward"></i><strong>&nbsp;Torna indietro</strong></a>
        </div>
    </div>  
    <input type="hidden" id="sid" name="sid" value="{{$sid}}" />
</form>
@endsection