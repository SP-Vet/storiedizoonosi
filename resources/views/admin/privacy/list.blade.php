@extends('admin.layout.base')

@section('content')
<div class="d-flex justify-content-between mt-2">
    <div class="d-inline-flex"><h5 class="pt-2">{{$title_page}}</h5></div>
    <div class="d-inline-flex"><a href="{{route('adminAddPrivacy')}}" class="btn btn-success"><i class="fa fa-plus-circle"></i><strong>Aggiungi Privacy</strong></a></div>
</div>
<hr>
<div class="row mt-2">
    <div class="col-md-12">
        <table class="table table-striped">
            <thead class="table-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Data Inserimento</th>
                    <th scope="col">Data Pubblicazione</th>
                    <th scope="col">Attuale</th>
                    <th scope="col">Riconferma al login</th>
                    <th scope="col">Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($privacys AS $privacy){ ?>
                <tr>
                    <td><?=$privacy->ppid;?></td>
                    <td><?=($privacy->data_inserimento)?Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $privacy->data_inserimento)->format('d/m/Y'):'';?></td>
                    <td><?=($privacy->data_pubblicazione)?Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $privacy->data_pubblicazione)->format('d/m/Y'):'';?></td>
                    <td><b><?=($privacy->attuale==1)?'SI':'NO';?></b></td>
                    <td><b><?=($privacy->reflag==1)?'SI':'NO';?></b></td>
                    <td>
                        <a href="{{route('adminModifyPrivacy')}}/{{$privacy->ppid}}" class="font-dark" title="gestisci"><i class="fa fa-pencil fa-lg"></i></a>
                        <!--<a href="#" class="font-red conferma-elimina" idvalore="" sezione=""  title="Nascondi"><i class="fa fa-trash fa-lg"></i></a>-->
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

    </div>

    
</div>
@endsection