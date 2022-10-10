@extends('layout.base')
@section('content')
    <h1 class="text-center mb-5 mt-5">Privacy Policy</h2>
    <div class="mb-5">{!!$data->testoprivacy!!}</div>
    <div class="d-flex justify-content-end mb-5">Perugia, <?=Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->data_pubblicazione)->format('d/m/Y');?></div>
@endsection


