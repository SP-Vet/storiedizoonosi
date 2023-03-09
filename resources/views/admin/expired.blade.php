@extends('admin.layout.changepassword')

@section('content')
<section class="h-100 gradient-form" >
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-xl-12">
                <div class="card rounded-3 text-black shadow-lg">
                    <div class="row g-0">
                        <h3 class="text-center p-3">Modifica la password di accesso</h3>
                        <div class="justify-content-center p-2">
                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if (session('message'))
                                <div class="alert alert-info">
                                    {{ session('message') }}
                                </div>
                            @endif
                        </div>
                        <form action="{{route('postPasswordExpiration')}}" id="cambiapassword" method="POST" class="needs-validation" novalidate>
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-12">
                                    <div class="card-body p-3 mx-md-4">
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="passwordcorrente">Password corrente<span class="required">*</span></label>
                                            <input name="passwordcorrente" type="password" id="passwordcorrente" class="form-control" value="" required="required" />
                                        </div>
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="password">Nuova Password <span class="required">*</span></label>
                                            <input name="password" type="password" id="password" class="form-control" value="" required="required" />
                                            <span id="passwordHelpInline" class="form-text">Almeno 8 caratteri, numeri, maiuscole, minuscole e caratteri speciali (!@#$%-*_£())</span>
                                        </div>
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="ripetipassword">Ripeti Nuova Password <span class="required">*</span></label>
                                            <input name="ripetipassword" type="password" id="ripetipassword" class="form-control" value="" required="required" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 p-5 text-end">
                                    <button class="btn btn-form-submit" type="submit">Conferma</button>
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