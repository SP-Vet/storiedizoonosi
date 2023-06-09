@extends('layout.base')
@section('header_pt1')
    @include('layout.header_pt1')
@endsection
@section('content')
<?php if(isset($settings) && isset($settings['project_page_img_1']) && $settings['project_page_img_1']->valueconfig!=''){?>
        <img class="d-inline-block" src="/images/<?=$settings['project_page_img_1']->valueconfig;?>" title="Immagine 1 pagina progetto" alt="Immagine 1 pagina progetto" />
<?php } ?>
<?php if(isset($settings) && isset($settings['project_page_text'])){?><?=html_entity_decode($settings['project_page_text']->valueconfig,ENT_QUOTES,'utf-8');?><?php } ?>
@endsection


