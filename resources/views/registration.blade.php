@extends('layout.base')
@section('header_pt1')
    @include('layout.header_pt1')
@endsection
@section('modal_1')
<div class="modal fade" tabindex="-1" id="modalTerminicondizioni" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg modal-fullscreen-sm-down modal-animation-special">
            <div class="modal-content">
                <div class="modal-header bg-wheat">
                    <h2 class="modal-title font-dark">Privacy Policy</h2>
                    <button type="button" class="btn-close btn-close-white closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?=html_entity_decode($privacy_policy->testoprivacy,ENT_QUOTES,'utf-8');?>
                    <div class="d-flex justify-content-end mb-5"><?php if(isset($settings) && isset($settings['city_privacy']) && $settings['city_privacy']->valueconfig!=''){?><?=html_entity_decode($settings['city_privacy']->valueconfig,ENT_QUOTES,'utf-8');?><?php } ?>, <?=Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $privacy_policy->data_pubblicazione)->format('d/m/Y');?></div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="presovisionetermini" class="btn bg-success font-white closeModal" data-bs-dismiss="modal">Ho preso visione</button>
                    <button type="button" class="btn bg-header font-white closeModal" data-bs-dismiss="modal">Chiudi</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('additionalcss')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
@endsection
@section('additionaljs')
    @parent
    <script type="text/javascript" src="/js/form-control.js"></script>
    <script type="text/javascript" src="/js/terminiecondizioni.js"></script>    
    <script type="text/javascript" src="/js/registrazione-utente.js"></script>
@endsection
@section('additionalcaptcha')
<script>
    var mtcaptchaConfig = {
        "sitekey": "<?=config('app.MTCAPTCHApublic');?>",
        "widgetSize": "mini",
        "theme": "neowhite",
        "lang": "it"
    };
    (function(){var mt_service = document.createElement('script');mt_service.async = true;mt_service.src = 'https://service.mtcaptcha.com/mtcv1/client/mtcaptcha.min.js';(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(mt_service);
    var mt_service2 = document.createElement('script');mt_service2.async = true;mt_service2.src = 'https://service2.mtcaptcha.com/mtcv1/client/mtcaptcha2.min.js';(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(mt_service2);}) ();
</script>
@endsection
@section('content')
@if (session('formerrato'))
    <div class="w-100">
        <div class="row ps-5 pe-5">
            <div class="col-12 alert alert-danger">
                {!!session('formerrato')!!}
            </div> 
        </div>
    </div>
@endif
<section class="h-100 gradient-form" >
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12">
                <div class="card rounded-3 text-black shadow-lg container-form-sub container-form-IT">
                    <div class="row g-0">
                        <div class="p-2 col-12 text-end">
                            <a href="#!" class="switch-lang-label-registrazione" data-actual-language="IT" data-language="EN" title="Switch language to English">
                              <img src="/images/bandiera_en.png" title="Switch language to English" width="40">
                            </a>
                        </div>
                        <h3 class="text-center p-3 titoloform">Richiedi un nuovo account compilando il form sottostante</h3>                        
                        <form action="{{route('postRegistration')}}" id="registrazione" method="POST" class="needs-validation" novalidate>
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="card-body p-3 mx-md-4">
                                        <div class="form-outline mb-4">
                                            <label class="form-label label-nome" for="nome">Nome <span class="required">*</span></label>
                                            <input name="nome" type="text" id="nome" class="form-control" value="<?=(isset($datapost['nome']))?$datapost['nome']:'';?>" required="required" />
                                        </div>
                                        <div class="form-outline mb-4">
                                            <label class="form-label label-cognome" for="cognome">Cognome <span class="required">*</span></label>
                                            <input name="cognome" type="text" id="cognome" class="form-control" value="<?=(isset($datapost['cognome']))?$datapost['cognome']:'';?>" required="required" />
                                        </div>
                                        <div class="form-outline mb-4">
                                            <label class="form-label label-codfis" for="codfis">Codice Fiscale</label>
                                            <input name="codfis" name="codfis" type="text" id="codfis" class="form-control" value="<?=(isset($datapost['codfis']))?$datapost['codfis']:'';?>" />
                                            <span id="codfisHelpInline" class="form-text">Utile nel caso di autenticazione futura tramite SPID</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card-body p-3 mx-md-4">
                                        <div class="form-outline mb-4">
                                            <label class="form-label label-email" for="email">Email <span class="required">*</span></label>
                                            <input name="email" type="email" id="email" class="form-control" value="<?=(isset($datapost['email']))?$datapost['email']:'';?>" required="required" />
                                        </div>
                                        <div class="form-outline mb-4">
                                            <label class="form-label label-ripetiemail" for="ripetiemail">Ripeti Email <span class="required">*</span></label>
                                            <input name="ripetiemail" type="email" id="ripetiemail" class="form-control" value="<?=(isset($datapost['ripetiemail']))?$datapost['ripetiemail']:'';?>" required="required" />
                                        </div>
                                        <div class="form-outline mb-4">
                                            <label class="form-label label-password" for="password">Password <span class="required">*</span></label>
                                            <input name="password" type="password" id="password" class="form-control" value="" required="required" />
                                            <span id="passwordHelpInline" class="form-text">Almeno 8 caratteri, numeri, maiuscole, minuscole e caratteri speciali</span>
                                        </div>
                                        <div class="form-outline mb-4">
                                            <label class="form-label label-ripetipassword" for="ripetipassword">Ripeti Password <span class="required">*</span></label>
                                            <input name="ripetipassword" type="password" id="ripetipassword" class="form-control" value="" required="required" />
                                        </div>
                                        <div class="form-outline mb-4">
                                            <div class="form-check">
                                                <input name="privacypolicy" class="form-check-input" type="checkbox" value="1" id="terminiecondizioni" required <?php if(isset($datapost['privacypolicy']) && $datapost['privacypolicy']==1)echo 'checked="checked"';?>>
                                                <label class="form-check-label fw-bold label-terminiecondizioni" for="terminiecondizioni">
                                                    Accetta <a href="#" onclick="apricondizioni();">la privacy policy</a>
                                                </label>
                                                <div class="invalid-feedback label-mustaccept">
                                                    Devi accettare per proseguire.
                                                </div>
                                            </div>  
                                        </div>
                                        <div class="form-outline mb-4">
                                            <!-- MTCaptcha Anchor DOM, copy start -->
                                            <div class="mtcaptcha"></div>
                                            <!-- MTCaptcha Anchor DOM, copy end -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 p-5 text-end">
                                    <button class="btn btn-form-submit label-inviadati" type="submit">Invia dati</button>
                                    <a class="btn btn-form-back label-indietro" href="{{ url()->previous() }}" />Indietro</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


