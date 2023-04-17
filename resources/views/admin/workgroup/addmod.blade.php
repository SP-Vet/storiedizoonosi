@extends('admin.layout.base')

@section('additionaljs')
    @parent
    <script type="text/javascript" src="/js/form-control.js"></script>
    <script type="text/javascript" src="/js/admin_wg.js"></script>
@endsection

@section('content')
<div class="d-flex justify-content-between mt-2">
    <div class="d-inline-flex"><h5 class="pt-2">{{$title_page}}</h5></div>
    <div class="d-inline-flex"><a href="{{ url()->previous() }}" class="btn btn-secondary"><i class="fa fa-fast-backward"></i><strong>&nbsp;Torna indietro</strong></a></div>
</div>
<hr>
@if (session('formerrato'))
    <div class="w-100">
        <div class="row ps-3 pe-3">
            <div class="col-12 alert alert-danger">
                {!!session('formerrato')!!}
            </div> 
        </div>
    </div>
@endif
<form action="{{route($form)}}" id="gestionenuovoutente" method="POST" class="needs-validation" novalidate>
    {{ csrf_field() }}
        <div class="row mb-3">
            <label for="name" class="col-sm-2 col-form-label">Nome <span class="required">*</span></label>
            <div class="col-12 col-md-4">
                <input type="text" class="form-control" id="name" name="name" value="" placeholder="Nome Amministratore" required />
            </div>
        </div>
        <div class="row mb-3">
            <label for="email" class="col-sm-2 col-form-label">Email di accesso <span class="required">*</span></label>
            <div class="col-12 col-sm-4">
                <div class="input-group">
                    <input type="text" id="email" name="email" class="form-control" placeholder="Email di accesso" aria-label="Email di accesso" aria-describedby="basic-addon2" value="" required />
                    <span class="input-group-text" id="basic-addon2">@sdz.it</span>
                </div>
                <div><span class="alert alert-danger mt-3 d-none messaggioemailadminerrore"></span></div>
            </div>
        </div>
        <div class="row mb-3">
            <label for="email_real" class="col-sm-2 col-form-label">Email di notifica <span class="required">*</span></label>
            <div class="col-12 col-md-4">
                <div class="input-group">
                    <input type="text" class="form-control" id="email_real" name="email_real" placeholder="Email di notifica" value="" required />
                </div>
                <div><span class="alert alert-danger mt-3 d-none messaggioemailadminrealeerrore"></span></div>
            </div>
            
        </div>
        <div class="row mb-3">
            <label for="ruolo" class="col-sm-2 col-form-label">Ruolo <span class="required">*</span></label>
            <div class="col-12 col-md-4">
                <select class="form-control" id="ruolo" name="ruolo" required>
                    <option value="admin" selected>Amministratore</option>
                </select>
            </div>
        </div>
        <hr>
        <button class="btn btn-form-submit" type="submit">Salva</button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary"><i class="fa fa-fast-backward"></i><strong>&nbsp;Indietro</strong></a>
    </div>
</form>
@endsection