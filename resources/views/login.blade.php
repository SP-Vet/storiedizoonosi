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
        <div class="text-center w-98 p-2 border-blue-dark border-1">DEBUG VERSIONE 1.0 - PUBBLICAZIONE PROVVISORIA IN FASE DI TEST. La versione funzionante sarà disponibile il 31 Ottobre 2022.<br />ALLEGATO ALL'E-JOURNAL SPVET.IT [ISSN 1592-1581] -  redazione-spvet@izsum.it  Tel. 075-343207.</div>
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
                          <form action="/checklogin" id="login-form" method="POST" class="needs-validation" novalidate >
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
                                <div class="col-12 col-sm-6 text-center text-sm-start"><a href="/registrazione" class="btn btn-outline-danger"><strong>Richiedi un account</strong></a></div>
                            </div>
                          </form>
                        </div>
                      </div>
                      <div class="col-lg-6 d-flex align-items-center gradient-custom-2">
                        <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                          <h4 class="mb-4"><strong></strong>Micro Epidemic One Health (ME.OH)</strong></h4>
                          <p class="small mb-0">Sanit&agrave; animale - Approccio One Health per lo studio delle zoonosi emergenti.</p>
                          <p class="small mb-0">Sviluppiamo una elevata cultura di <strong>Sanit&agrave; Pubblica</strong></p>
                        </div>
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
        <strong>2022</strong> - <strong>Storie di Zoonosi</strong> - <a href="https://izsum.it/IZSUM/" class="text-dark">Istituto Zooprofilattico Sperimentale dell&apos;Umbria e delle Marche "Togo Rosati"</a>
    </div>
</footer>
          
@endsection


