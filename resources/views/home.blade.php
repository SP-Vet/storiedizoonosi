@extends('layout.base')

@section('header_pt2')
<div class="mt-4">
    <div class="d-flex justify-content-between">
        <h2><strong>STORIE DI ZOONOSI</strong></h2>
    </div>
    <?php if(isset($settings) && isset($settings['subtitle_text']) && $settings['subtitle_text']->valueconfig!=''){?><?=html_entity_decode($settings['subtitle_text']->valueconfig,ENT_QUOTES,'utf-8');?><?php } ?>
</div>
@endsection
@section('content')
<div class="d-flex justify-content-end">
    <div class="border border-warning p-2 rounded">
        <div class="d-inline-flex">Scegli zoonosi:&nbsp;</div>
        <div class="d-inline-flex">
            <select class="select form-control" name="filter_categoria" id="catzoonosi">
                <option value="" selected="selected">Tutte</option>
                @foreach($zoonosi as $z)    
                    <option value="{{$z->zid}}">{{$z->nome}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
@foreach ($zoonosi AS $key=>$zoo)
<div class="row mb-2 mt-3">
    <div class="col-12 containerzoodash" id="zoo{{$zoo->zid}}">
        <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 container-zoonosi position-relative">
            <div class="col-12 col-sm-6 ps-3 pt-3 pe-3">
                <h3 class="mb-2"><?=$zoo->nome;?></h3>
                <p style="font-size: 0.6rem;"><a href="/elencostorie/{{$zoo->slugzoonosi}}"><img style="width: 100%;overflow: hidden;max-height: 200px;object-fit: cover;" src="{{$zoo->img_url}}" alt="IMG_Zoonosi"></a><?=html_entity_decode($zoo->img_desc,ENT_QUOTES,'utf-8');?></p>
            </div>
            <div class="col-12 col-sm-6 ps-3 pe-3 pt-2 d-flex flex-column position-static">
                <?php if($zoo->linktelegram!=''){ ?>
                    <div class="row">
                        <div class="col-12 mb-1 mt-1 text-end">
                            <a href="<?=$zoo->linktelegram;?>" title="Unisciti al gruppo Telegram">
                                <button type="button" class="btn bg-telegram text-white blob blue" style="font-size: 0.8rem;"><strong>Telegram&nbsp; {{$zoo->nome}}<i class="fa fa-telegram fa-2x ps-2" style="vertical-align: middle;"></i></strong></button>
                            </a>
                        </div>
                    </div>
                <?php } ?>
                <div class="bloccostoria pb-2 <?=($zoo->linktelegram!='')?'pt-2':'pt-5';?>">
                    <p class="card-text mb-auto"><?= html_entity_decode($zoo->descrizione,ENT_QUOTES,'utf-8');?></p>
                </div>
            </div>
            <a class="link-black" href="/elencostorie/{{$zoo->slugzoonosi}}">
                <div class="ps-3 pe-3 text-end pb-3">
                    <div class="btn btn-success">Vai alle storie di zoonosi</div>
                </div>
            </a>
        </div>
    </div>
</div>
@endforeach
@endsection


