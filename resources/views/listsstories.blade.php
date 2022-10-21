@extends('layout.base')
@section('content')

<?php if(is_array($storie) && count($storie)>0){ ?>
<div class="col-12 text-end">Sono stati trovati <b><?=count($storie);?></b> risultati</div>
<?php $zidattuale=''; foreach ($storie AS $key=>$storia){ ?>
        <?php if($storia->linktelegram!='' && $zidattuale!=$storia->zid){ ?>
            <div class="row">
                   <div class="col-12">
                       <a href="<?=$storia->linktelegram;?>" title="Unisciti al gruppo Telegram">
                           <button type="button" class="btn bg-telegram text-white blob blue" style="font-size: 0.8rem;"><strong>Telegram&nbsp; {{$storia->nome_zoonosi}}<i class="fa fa-telegram fa-2x ps-2" style="vertical-align: middle;"></i></strong></button>
                       </a>
                   </div>
               </div>
       <?php }$zidattuale=$storia->zid; ?>

    <div class="row mb-2 mt-3">
        <div class="col-md-12">
            <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 container-list-story position-relative">
                <div class="col-12 p-4">
                    <div class="d-flex justify-content-between">
                        <div><h4 class="mb-2"><?=$storia->nome_zoonosi;?></h3></div>
                    </div>
                    <div><div class="fs-6 mb-1 text-muted d-inline-block">Published online <?=Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $storia->data_pubblicazione)->format('d/m/Y');?></div> - <strong class="fs-6 d-inline-block mb-2 text-primary ">Italia</strong></div>
                    <div class="bloccostoria pt-2 pb-4 border-top border-1 col-12">
                        <h5><strong><?=$storia->titolo;?></strong></h5>
                        <h6><?= html_entity_decode($storia->descrizione,ENT_QUOTES,'utf-8');?></h6>
                        <figcaption class="blockquote-footer mt-1">
                            <?=($storia->grado)?$storia->grado.' ':'';?><?=($storia->autore);?>; Redazione <a target="_blank" href="http://spvet.it/">SPVet.it</a>; <a target="_blank" href="http://spvet.it/microepidemic/gl.html">Comitato Scientifico MEOH</a>
                        </figcaption>
                        <p class="card-text mb-auto"><strong>Abstract - </strong><?= html_entity_decode($storia->abstract,ENT_QUOTES,'utf-8');?></p>
                        
                        <a class="btn btn-arrow-enter mt-3" href="/storia/<?=$storia->slug;?>">Leggi la storia<img class="ps-2" src="/images/arrow_right_black_slim.png" width="100" /></a>
                    </div>
                    <div class="mt-4">
                        <?= html_entity_decode($storia->copyright,ENT_QUOTES,'utf-8');?>
                    </div>
                </div>
            </div>
         </div>
    </div>
<?php } ?>

<?php }else{ ?>
<div class="mb-5 mt-5" style="min-height: 500px;">
    <h2>LA RICERCA NON HA FORNITO ALCUN RISULTATO</h2>
    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-5 mb-5"><i class="fa fa-fast-backward"></i><strong>&nbsp;Torna indietro</strong></a>
</div>

<?php } ?>
@endsection


