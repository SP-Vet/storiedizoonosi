@extends('layout.base')
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
    <?php //echo '<pre>';print_r($domande);echo '</pre>';?>
    
    <div class="container p-0 mt-3 mb-4">
        <h1 class="h3 mb-3 text-center">Pagina di valutazione del servizio</h1>
        <div class="row">
            <div class="col-12">
                <form action="{{route('postserviceEvaluation')}}" method="post" class="need-validation">
                    {{ csrf_field() }}
                    <?php foreach($domande AS $domanda){ ?>
                        <input type="hidden" name="seaid[]" value="<?=$domanda->seaid;?>" />
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title"><?=$domanda->question;?></h5>
                            <h6 class="card-subtitle text-muted">
                                <?php switch($domanda->typeanswer){ 
                                    case 1:
                                    case 3:
                                        echo 'Scegli una risposta';
                                        break;
                                    case 2:
                                        echo 'Dai un punteggio da 1 a 10';
                                        break;
                                    default:
                                        echo '&nbsp;';
                                        break;
                                }?>
                            </h6>
                        </div>
                        <div class="card-body">
                            <?php switch($domanda->typeanswer){ 
                                case 1: ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="valueanswer<?=$domanda->seaid;?>" id="inlineRadio<?=$domanda->seaid;?>-1" value="SI" required>
                                        <label class="form-check-label" for="inlineRadio1">SI</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="valueanswer<?=$domanda->seaid;?>" id="inlineRadio<?=$domanda->seaid;?>-2" value="NO" required>
                                        <label class="form-check-label" for="inlineRadio2">NO</label>
                                    </div>
                                    <?php break;
                                case 2: ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="valueanswer<?=$domanda->seaid;?>" id="inlineRadio<?=$domanda->seaid;?>-1" value="1" required>
                                        <label class="form-check-label" for="inlineRadio<?=$domanda->seaid;?>-1">1</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="valueanswer<?=$domanda->seaid;?>" id="inlineRadio<?=$domanda->seaid;?>-2" value="2" required>
                                        <label class="form-check-label" for="inlineRadio2<?=$domanda->seaid;?>-">2</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="valueanswer<?=$domanda->seaid;?>" id="inlineRadio<?=$domanda->seaid;?>-3" value="3" required>
                                        <label class="form-check-label" for="inlineRadio<?=$domanda->seaid;?>-3">3</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="valueanswer<?=$domanda->seaid;?>" id="inlineRadio<?=$domanda->seaid;?>-4" value="4" required>
                                        <label class="form-check-label" for="inlineRadio<?=$domanda->seaid;?>-4">4</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="valueanswer<?=$domanda->seaid;?>" id="inlineRadio<?=$domanda->seaid;?>-5" value="5" required>
                                        <label class="form-check-label" for="inlineRadio<?=$domanda->seaid;?>-5">5</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="valueanswer<?=$domanda->seaid;?>" id="inlineRadio<?=$domanda->seaid;?>-6" value="6" required>
                                        <label class="form-check-label" for="inlineRadio<?=$domanda->seaid;?>-6">6</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="valueanswer<?=$domanda->seaid;?>" id="inlineRadio<?=$domanda->seaid;?>-7" value="7" required>
                                        <label class="form-check-label" for="inlineRadio<?=$domanda->seaid;?>-7">7</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="valueanswer<?=$domanda->seaid;?>" id="inlineRadio<?=$domanda->seaid;?>-8" value="8" required>
                                        <label class="form-check-label" for="inlineRadio<?=$domanda->seaid;?>-8">8</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="valueanswer<?=$domanda->seaid;?>" id="inlineRadio<?=$domanda->seaid;?>-9" value="9" required>
                                        <label class="form-check-label" for="inlineRadio<?=$domanda->seaid;?>-9">9</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="valueanswer<?=$domanda->seaid;?>" id="inlineRadio<?=$domanda->seaid;?>-10" value="10" required>
                                        <label class="form-check-label" for="inlineRadio<?=$domanda->seaid;?>-10">10</label>
                                    </div>
                                    <?php break;
                                case 3: ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="valueanswer<?=$domanda->seaid;?>" id="inlineRadio<?=$domanda->seaid;?>-1" value="SI" required>
                                        <label class="form-check-label" for="inlineRadio<?=$domanda->seaid;?>-1">SI</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="valueanswer<?=$domanda->seaid;?>" id="inlineRadio<?=$domanda->seaid;?>-2" value="NO" required>
                                        <label class="form-check-label" for="inlineRadio<?=$domanda->seaid;?>-2">NO</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="valueanswer<?=$domanda->seaid;?>" id="inlineRadio<?=$domanda->seaid;?>-3" value="NON SAPREI" required>
                                        <label class="form-check-label" for="inlineRadio<?=$domanda->seaid;?>-3">NON SAPREI</label>
                                    </div>
                                    <?php break;    
                                default:
                                    echo '&nbsp;';
                                    break;
                            }?>

                        </div>
                    </div>
                    <?php } ?>
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title">Inserisci il codice visualizzato</h5>
                            <h6 class="card-subtitle text-muted">&nbsp;</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-outline">
                                <!-- MTCaptcha Anchor DOM, copy start -->
                                <div class="mtcaptcha"></div>
                                <!-- MTCaptcha Anchor DOM, copy end -->
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-form-submit">Invia la scheda di valutazione</button>
                </form>
            </div>
        </div>
    </div>
@endsection