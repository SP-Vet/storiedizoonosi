@extends('layout.base')
@section('header_pt1')
    @include('layout.header_pt1')
@endsection

@section('additionalcss')
<style>
    @media (min-width: 992px) {
      .wrapper{
        min-height:100%;
        position:relative;
      }
      footer {
          position: absolute;
          bottom:0;
          left:0;
          width:100%;
      }
    }
</style>
@endsection

@section('additionaljs')
    @parent
@endsection

@section('content')
<section class="h-100 gradient-form" >
    <div class="container py-5 h-100">
        <div class="row ps-5 pe-5">
            <div class="col-12 alert alert-success text-center">
                <h2>Controlla la tua casella email</h2>
                <h3>Abbiamo inviato un&apos;email con un link di conferma all&apos;indirizzo di posta elettronica fornito.</h3>
                <h3>Segui le istruzioni ricevute per confermare il tuo account.</h3>
            </div> 
        </div>
    </div>
</section>
@endsection


