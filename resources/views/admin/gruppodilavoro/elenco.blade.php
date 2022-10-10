@extends('admin.layout.base')

@section('content')
<div class="d-flex justify-content-between mt-2">
    <div class="d-inline-flex"><h5 class="pt-2">{{$title_page}}</h5></div>
</div>
<hr>
<div class="row mt-2">
    <div class="col-md-12">
        <table class="table table-striped">
            <thead class="table-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Email</th>
                    <th scope="col">Ruolo</th>
                    <th scope="col">Data inserimento</th>
                    <th scope="col">Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($gruppo AS $utente){ ?>
                <tr>
                    <td><?=$utente->id;?></td>
                    <td><?=$utente->name;?></td>
                    <td><?=$utente->email;?></td>
                    <td><?=$utente->role;?></td>
                    <td><?=($utente->created_at)?Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $utente->created_at)->format('d/m/Y'):'';?></td>
                    <td>
                        <?php if($utente->id==$admin->id){ ?>
                            <!--<a href="{{route('adminModificaUtente')}}/{{$utente->id}}" class="font-dark" title="Modifica"><i class="fa fa-pencil fa-lg"></i></a>-->
                        <?php } ?>
                        <!--<a href="#" class="font-red conferma-elimina" idvalore="{{$utente->id}}" sezione="storie"  title="Elimina"><i class="fa fa-trash fa-lg"></i></a>-->
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

    </div>

    
</div>
@endsection