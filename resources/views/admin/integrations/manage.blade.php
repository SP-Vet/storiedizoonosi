@extends('admin.layout.base')

@section('additionaljs')
    @parent
    <script type="text/javascript" src="/js/form-control.js"></script>
    <script type="text/javascript">
        CKEDITOR.replace( 'testoapprofondimento', {
            customConfig: '/js/ckeditor_configs/config_simple.js'
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
<form action="{{route($form)}}/{{$datapost['said']}}" id="gestioneapprofondimento" method="POST" class="needs-validation" novalidate>
    {{ csrf_field() }}
    <div class="row">
        <div class="col-3">
            <div class="row p-2">
                <div class="mt-3"><strong>Utente:</strong><br /><?=(isset($datapost['nomeutente']))?$datapost['nomeutente']:'';?></div>
                 <div class="mt-3"><strong>Email:</strong><br /><?=(isset($datapost['email']))?$datapost['email']:'';?></div>
                <div class="mt-3"><strong>Data inserimento:</strong><br /><?=($datapost['data_inserimento'])?Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $datapost['data_inserimento'])->format('d/m/Y H:i'):'';?></div>
                <div class="mt-3"><strong>Titolo storia:</strong><br /><?=$datapost['titolo'];?></div>
                <div class="mt-3"><strong>Zoonosi:</strong><br /><?=$datapost['nome_zoonosi'];?></div>
            </div>
        </div>
        <div class="col-9">
            <!--0-in attesa di approvazione, 1-pubblicato, 2-nascosto-->
            <label class="labels">Stato<span class="required">*</span></label>
            <select class="form-control" name="stato" id="stato">
                <option value="0" <?php if(!$datapost['stato'] || $datapost['stato']==0)echo 'selected="selected"';?>>Attesa di approvazione</option>
                <option value="1" <?php if($datapost['stato']==1)echo 'selected="selected"';?>>Pubblicato</option>
                <option value="2" <?php if($datapost['stato']==2)echo 'selected="selected"';?>>Nascosto</option>
            </select>
            <label class="labels mt-3">Testo Approfondimento<span class="required">*</span></label>
            <textarea class="form-select" rows="3" id="testoapprofondimento" name="testoapprofondimento" required="required"><?=(isset($datapost['testoapprofondimento']))?html_entity_decode($datapost['testoapprofondimento']):'';?></textarea>
            <hr>
            <?php if($datapost['data_pubblicazione']!=''){ ?>
                <div class="mt-3"><strong>Data pubblicazione:</strong><br /><?=($datapost['data_pubblicazione'])?Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $datapost['data_pubblicazione'])->format('d/m/Y H:i'):'';?></div>
            <?php } ?>
            <?php $testoreplace='';if($datapost['testoselezionato']!=''){$testoreplace=$datapost['testoselezionato']; ?>
                <div class="mt-3"><strong>Testo selezionato:</strong><br /><?=(isset($datapost['testoselezionato']))?html_entity_decode($datapost['testoselezionato'],ENT_QUOTES,'utf-8'):'';?></div>
            <?php } ?>
            <?php if($datapost['testofase']!=''){?>
                <div class="mt-3"><strong>Testo storia:</strong><br /><span style="font-size: 0.7rem;"><?=(isset($datapost['testofase']))? str_replace($testoreplace, '<span style="background-color: #ffbc40;">'.$testoreplace.'</span>', html_entity_decode($datapost['testofase'],ENT_QUOTES,'utf-8')):'';?></span></div>
            <?php } ?>
        </div>
    </div>
    <hr>
    <input type="hidden" name="said" id="said" value="<?=(isset($datapost['said']))?$datapost['said']:'';?>" />
    <button class="btn btn-form-submit" type="submit">Salva</button>
    <a href="{{ url()->previous() }}" class="btn btn-secondary"><i class="fa fa-fast-backward"></i><strong>&nbsp;Indietro</strong></a>
    </div>
</form>
@endsection