@extends('layout.base')
@section('content')
    <h1 class="text-center mb-5 mt-5">Privacy Policy</h2>
    <div class="mb-5"><?=html_entity_decode($data->testoprivacy,ENT_QUOTES,'utf-8');?></div>
    <div class="d-flex justify-content-end mb-5"><?php if(isset($settings) && isset($settings['city_privacy']) && $settings['city_privacy']->valueconfig!=''){?><?=html_entity_decode($settings['city_privacy']->valueconfig,ENT_QUOTES,'utf-8');?><?php } ?>, <?=Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->data_pubblicazione)->format('d/m/Y');?></div>
@endsection