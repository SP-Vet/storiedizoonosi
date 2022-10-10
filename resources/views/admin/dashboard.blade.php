@extends('admin.layout.base')
@section('content')
<?php if(count($storie_sottomesse->all())>0){ ?>
<div class="clearfix"></div>
<div class="row border rounded m-2 mb-4 container-list-story p-2">
    <h3 class="mb-2 p-2 bg-warning bg-gradient">Hai <?=count($storie_sottomesse->all());?> nuove storie sottomesse da gestire</h3>
    <?php foreach ($storie_sottomesse AS $storiasubmit){ ?>
        <div class="bloccostoria p-2 col-12 col-md-4">
            <div class="border border-primary border-2 rounded p-2 pb-4">
                <div>Data sottomissione: <strong><?=Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $storiasubmit->data_inserimento)->format('d/m/Y');?></strong></div>
                <div>Utente: <strong><?=$storiasubmit->nome_cognome_utente;?></strong></div>
                <div>Email: <strong><?=$storiasubmit->email;?></strong></div>
                <div>Titolo: <strong><?=$storiasubmit->titolo_inserito;?></strong></div>
                <div>Zoonosi: <strong><?=$storiasubmit->tipozoonosi_inserito;?></strong></div>
                <a href="/admin/modificastoria/<?=$storiasubmit->sid;?>" class="btn btn-primary position-relative stretched-link" style="top: 10px;"><i class="fa fa-pencil"></i>&nbsp;Gestisci</a>
                <a href="mailto:<?=$storiasubmit->email;?>?subject=Richiesta informazioni storia - <?=$storiasubmit->titolo_inserito;?>" class="btn btn-secondary position-relative stretched-link" style="top: 10px;"><i class="fa fa-envelope"></i>&nbsp;Contatta l&apos;autore</a>
            </div>
        </div>
    <?php } ?>
</div>
<?php } ?>
<?php if(count($storie_bozze->all())>0){?>
<div class="clearfix"></div>
<div class="row border rounded m-2 mb-4 container-list-story p-2">
    <h3 class="mb-2 p-2 bg-primary bg-gradient font-white">Hai <?=count($storie_bozze->all());?> bozze di storia da pubblicare</h3>
    <?php foreach ($storie_bozze AS $storiabozza){ ?>
        <div class="bloccostoria p-2 col-12 col-md-4">
            <div class="border border-primary border-2 rounded p-2 pb-4">
                <?php if(isset($storiabozza->data_lavorazione) && $storiabozza->data_lavorazione!=''){ ?><div>Data ultima revisione: <strong><?=Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $storiabozza->data_lavorazione)->format('d/m/Y');?></strong></div><?php } ?>
                <!--<div>Revisione a carico di: <strong>[[name]]</strong></div>-->
                <hr>
                <?php if(isset($storiabozza->data_inserimento) && $storiabozza->data_inserimento!=''){ ?><div>Data sottomissione: <strong><?=Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $storiabozza->data_inserimento)->format('d/m/Y');?></strong></div><?php } ?>
                <?php if(isset($storiabozza->autore) && $storiabozza->autore!=''){?><div>Utente: <strong><?=$storiabozza->autore;?></strong></div><?php } ?>
                <?php if(isset($storiabozza->email) && $storiabozza->email!=''){?><div>Email: <strong><?=$storiabozza->email;?></strong></div><?php } ?>
                <div>Titolo: <strong><?=$storiabozza->titolo;?></strong></div>
                <div>Zoonosi: <strong><?=$storiabozza->nome_zoonosi;?></strong></div>
                <a href="/admin/modificastoria/<?=$storiabozza->sid;?>" class="btn btn-primary position-relative stretched-link" style="top: 10px;"><i class="fa fa-pencil"></i>&nbsp;Gestisci</a>
                <?php if(isset($storiabozza->email) && $storiabozza->email!=''){?><a href="mailto:<?=$storiabozza->email;?>?subject=Richiesta informazioni storia - <?=$storiabozza->titolo;?>" class="btn btn-secondary position-relative stretched-link" style="top: 10px;"><i class="fa fa-envelope"></i>&nbsp;Contatta l&apos;autore</a><?php } ?>
                <a href="#!" class="btn btn-success position-relative stretched-link pubblica-storia" idstoria="<?=$storiabozza->sid;?>" style="top: 10px;"><i class="fa fa-check"></i>&nbsp;Pubblica</a>
            </div>
        </div>
    <?php } ?>
</div>
<?php } ?>

<?php if(count($approf_inseriti->all())>0){ ?>
<div class="clearfix"></div>
<div class="row border rounded m-2 mb-4 container-list-story p-2">
    <h3 class="mb-2 p-2 bg-info bg-gradient">Hai <?=count($approf_inseriti->all());?> nuove INTEGRAZIONI da controllare</h3>
    <?php foreach ($approf_inseriti AS $approfondimento){ ?>
        <div class="bloccostoria p-2 col-12 col-md-4">
            <div class="border border-primary border-2 rounded p-2 pb-4">
                <div>Data sottomissione: <strong><?=Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $approfondimento->data_inserimento)->format('d/m/Y');?></strong></div>
                <div>Utente: <strong><?=$approfondimento->nomeutente;?></strong></div>
                <div>Storia: <strong><a href="/admin/modificastoria/<?=$approfondimento->sid;?>"><?=$approfondimento->titolo;?></a></strong></div>
                <a href="/admin/gestisciapprofondimenti/<?=$approfondimento->said;?>" class="btn btn-primary position-relative stretched-link" style="top: 10px;"><i class="fa fa-pencil"></i>&nbsp;Gestisci</a>
                <a href="mailto:<?=$approfondimento->email;?>?subject=Richiesta informazioni storia - <?=$approfondimento->titolo;?>" class="btn btn-secondary position-relative stretched-link" style="top: 10px;"><i class="fa fa-envelope"></i>&nbsp;Contatta l&apos;autore</a>
                <a href="#!" class="btn btn-success position-relative stretched-link pubblica-approfondimento" idapprofondimento="<?=$approfondimento->said;?>" style="top: 10px;"><i class="fa fa-check"></i>&nbsp;Pubblica</a>
            </div>
        </div>
    <?php } ?>
</div>
<?php } ?>
@endsection