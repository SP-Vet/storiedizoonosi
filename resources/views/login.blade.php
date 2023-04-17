@extends('layout.loginuser')
@section('additionalcss')
<!--<style>
    @media (min-width: 992px) {
      .gradient-form{
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
</style>-->
@endsection
@section('content')
<section class="gradient-form h-100" style="background-color: #eee;">
<div class="container py-5 h-100">

    <?php if(isset($settings) && isset($settings['testo_debug_top']) && $settings['testo_debug_top']->valueconfig!=''){?><?=html_entity_decode($settings['testo_debug_top']->valueconfig,ENT_QUOTES,'utf-8');?><?php } ?>
      @if (session('messageinfo'))
          <div class="container-fluid pb-2 pt-2">
              <div class="alert alert-success">
                  {!!session('messageinfo')!!}
              </div>
          </div>
      @endif
      @if (session('messagedanger'))
          <div class="container-fluid pb-2 pt-2">
              <div class="alert alert-danger">
                  {!!session('messagedanger')!!}
              </div>
          </div>
      @endif

      <div class="row d-flex justify-content-center align-items-center mt-5">
          <div class="col-xl-10">
            <div class="card rounded-3 text-black">
              <div class="row g-0">
                <div class="col-lg-6">
                  <div class="card-body p-md-5 mx-md-4">
                    <div class="text-center">
                      <h2 class="mt-1 mb-5 pb-1">Login</h4>
                    </div>
                    <form action="{{route('postcheckLogin')}}" id="login-form" method="POST" class="needs-validation" novalidate >
                        {{ csrf_field() }}
                      <p>Effettua il login al tuo account</p>
                      <div class="form-outline mb-4">
                        <input type="email" id="email" class="form-control" placeholder="" required="required"  name="email" />
                        <label class="form-label" for="email">Email</label>
                      </div>
                      <div class="form-outline mb-4">
                          <input type="password" id="password" class="form-control" required="required" name="password" />
                        <label class="form-label" for="password">Password</label>
                      </div>
                      <div class="form-outline mb-4">
                          <!-- MTCaptcha Anchor DOM, copy start -->
                          <div class="mtcaptcha"></div>
                          <!-- MTCaptcha Anchor DOM, copy end -->
                      </div>
                      <div class="pt-1 mb-5 pb-1 position-relative">
                        <input type="submit" class="btn border-header btn-block fa-lg gradient-custom-2 mb-3 position-absolute top-0 start-0 text-bold" value="Accedi al portale" >
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary fa-lg mb-3 position-absolute top-0 end-0">Annulla</a>
                        <!--<a class="text-muted" href="#!">Forgot password?</a>-->
                      </div>
                      <hr>
                      <div class="row pb-4 pt-4">
                          <div class="col-12 col-sm-6 text-center text-sm-end"><p class="mb-0 me-2"><strong>Non hai un account?</strong></p></div>
                          <div class="col-12 col-sm-6 text-center text-sm-start"><a href="{{route('getRegistration')}}" class="btn btn-outline-danger"><strong>Richiedi un account</strong></a></div>
                      </div>
                    </form>
                  </div>
                </div>
                <div class="col-lg-6 d-flex align-items-center gradient-custom-2">
                    <?php if(isset($settings) && isset($settings['text_login_column_two']) && $settings['text_login_column_two']->valueconfig!=''){?><?=html_entity_decode($settings['text_login_column_two']->valueconfig,ENT_QUOTES,'utf-8');?><?php } ?>
                </div>
              </div>
            </div>
          </div>
      </div>
    </div>
</section>
@endsection
@section('footerlogin')
<footer class="bg-header text-center text-lg-start">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">

    
    <?php if(isset($settings) && isset($settings['logo_footer_bottom']) && $settings['logo_footer_bottom']->valueconfig!=''){?>
                <div class="col-md-12 mx-auto mt-3 text-center">
                    <div class="d-flex justify-content-center">
                        <a class="text-white" target="_blank" href="<?php if(isset($settings) && isset($settings['link_logo_footer_bottom'])){?><?=html_entity_decode($settings['link_logo_footer_bottom']->valueconfig,ENT_QUOTES,'utf-8');?><?php }else echo '#'; ?>" title="<?php if(isset($settings) && isset($settings['link_logo_footer_bottom'])){?><?=html_entity_decode($settings['link_logo_footer_bottom']->valueconfig,ENT_QUOTES,'utf-8');?><?php }else echo ''; ?>" style="white-space: nowrap;">
                            <img class="d-inline-block" src="/images/<?=html_entity_decode($settings['logo_footer_bottom']->valueconfig,ENT_QUOTES,'utf-8');?>" width="250" title="<?php if(isset($settings) && isset($settings['link_logo_footer_bottom'])){?><?=html_entity_decode($settings['link_logo_footer_bottom']->valueconfig,ENT_QUOTES,'utf-8');?><?php }else echo ''; ?>" alt="<?php if(isset($settings) && isset($settings['link_logo_footer_bottom'])){?><?=html_entity_decode($settings['link_logo_footer_bottom']->valueconfig,ENT_QUOTES,'utf-8');?><?php }else echo ''; ?>" />
                        </a>
                    </div>         
                </div>
            <?php } ?>
    </div>
</footer>
          
@endsection


