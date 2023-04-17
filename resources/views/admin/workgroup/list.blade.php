@extends('admin.layout.base')

@section('additionaljs')
    @parent
    <script type="text/javascript" src="/js/workgroup.js"></script>
@endsection

@section('content')
<div class="d-flex justify-content-between mt-2">
    <div class="d-inline-flex"><h5 class="pt-2">{{$title_page}}</h5></div>
    <div class="d-inline-flex"><a href="{{route('adminAddUser')}}" class="btn btn-success"><i class="fa fa-plus-circle"></i><strong>Aggiungi Amministratore</strong></a></div>
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
                    <th scope="col">Email Reale</th>
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
                    <td><?=$utente->email_real;?></td>
                    <td><?=$utente->role;?></td>
                    <td><?=($utente->created_at)?Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $utente->created_at)->format('d/m/Y'):'';?></td>
                    <td>
                        <?php if($utente->id!=$admin->id){ ?>
                            <a href="#" class="font-dark conferma-resetpassword" idadmin="<?=$utente->id;?>" email="<?=$utente->email;?>" email_real="<?=$utente->email_real;?>" title="Reset Password"><i class="fa fa-key fa-lg"></i></a>

                            <!--<a href="{{route('adminModifyUser')}}/{{$utente->id}}" class="font-dark" title="Modifica"><i class="fa fa-pencil fa-lg"></i></a>-->
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