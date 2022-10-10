@extends('admin.layout.base')

@section('content')
<section class="h-100 gradient-form" >
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-xl-12">
                <div class="card rounded-3 text-black shadow-lg">
                    <div class="row g-0">
                        <h3 class="text-center p-3">Modifica la password di accesso</h3>
                        @if (session('formerrato'))
                        <div class="w-100">
                            <div class="row ps-5 pe-5">
                                <div class="col-12 alert alert-danger">
                                    {!!session('formerrato')!!}
                                </div> 
                            </div>
                        </div>
                        @endif
                        <form action="{{route('admincheckCambiapassword')}}/{{$admin->id}}" id="cambiapassword" method="POST" class="needs-validation" novalidate>
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-12">
                                    <div class="card-body p-3 mx-md-4">
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="password">Password <span class="required">*</span></label>
                                            <input name="password" type="password" id="password" class="form-control" value="" required="required" />
                                            <span id="passwordHelpInline" class="form-text">Almeno 8 caratteri, numeri, maiuscole, minuscole e caratteri speciali</span>
                                        </div>
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="ripetipassword">Ripeti Password <span class="required">*</span></label>
                                            <input name="ripetipassword" type="password" id="ripetipassword" class="form-control" value="" required="required" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 p-5 text-end">
                                    <button class="btn btn-form-submit" type="submit">Conferma</button>
                                    <a class="btn btn-form-back" href="{{ url()->previous() }}" />Indietro</a>
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