@extends('admin.layout.loginadmin')
@section('content')
<section class="vh-100" style="background-color: #508bfc;">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card shadow-2-strong" style="border-radius: 1rem;">
          <div class="card-body p-5 text-center">
            <h3 class="mb-5">Login Amministrazione<br><i>Storie di Zoonosi</i></h3>
            <form action="{{ route('adminLoginPost') }}" method="post">
                {!! csrf_field() !!}
                @if(\Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ \Session::get('success') }}
                    </div>
                @endif
                {{ \Session::forget('success') }}
                @if(\Session::get('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ \Session::get('error') }}
                    </div>
                @endif  
                @if (session('messageinfo'))
                <div class="alert alert-success">
                    {!!session('messageinfo')!!}
                </div>
                @endif
                @if (session('messagedanger'))
                    <div class="alert alert-danger">
                        {!!session('messagedanger')!!}
                    </div>
                @endif
                <div class="form-outline mb-4">
                    <input type="email" name="email" id="email" class="form-control form-control-lg" required />
                    <label class="form-label" for="email">Email</label>
                    @if ($errors->has('email'))
                        <span class="help-block font-red-mint">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-outline mb-4">
                    <input type="password" id="password" name="password" class="form-control form-control-lg" required />
                    <label class="form-label" for="password">Password</label>
                    @if ($errors->has('password'))
                        <span class="help-block font-red-mint">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-outline mb-4 text-center">
                    <label class="form-label" for="mtcaptcha">Inserisci il codice sottostante</label>
                    <!-- MTCaptcha Anchor DOM, copy start -->
                    <div class="mtcaptcha"></div>
                    <!-- MTCaptcha Anchor DOM, copy end -->
                </div>
                <button class="btn btn-primary btn-lg btn-block" type="submit">Login</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection