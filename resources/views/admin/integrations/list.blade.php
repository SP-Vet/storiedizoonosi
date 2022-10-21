@extends('admin.layout.base')

@section('content')
<div class="d-flex justify-content-between mt-2">
    <div class="d-inline-flex"><h5 class="pt-2">{{$title_page}}</h5></div>
    <!--<div class="d-inline-flex"><a href="#" class="btn btn-success"><i class="fa fa-plus-circle"></i><strong>Aggiungi Approfondimento</strong></a></div>-->
</div>
<hr>
<div class="row mt-2">
    <div class="col-md-12">
        <table class="table table-striped">
            <thead class="table-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Utente</th>
                    <th scope="col">Testo</th>
                    <th scope="col">Data inserimento</th>
                    <th scope="col">Data pubblicazione</th>
                    <th scope="col w-10pc">Stato</th>
                    <th scope="col">Storia</th>
                    <th scope="col">Zoonosi</th>
                    <th scope="col text-center"># risposta</th>
                    <th scope="col">Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($approfondimenti AS $approfondimento){ ?>
                <tr>
                    <td><?=$approfondimento->said;?></td>
                    <td><?=$approfondimento->nomeutente;?></td>
                    <td><?=($approfondimento->testoapprofondimento)?substr($approfondimento->testoapprofondimento,0,100).'...(continua)':'';?></td>
                    <td><?=($approfondimento->data_inserimento)?Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $approfondimento->data_inserimento)->format('d/m/Y'):'';?></td>
                    <td><?=($approfondimento->data_pubblicazione)?Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $approfondimento->data_pubblicazione)->format('d/m/Y'):'';?></td>
                    <!--0-in attesa di approvazione, 1-pubblicato, 2-nascosto-->
                    <?php 
                    $bg_stato='';
                    $stato='';
                    switch($approfondimento->stato){
                        case 0:
                            $font_color='font-white';
                            $bg_stato='bg-danger';
                            $stato='ATTESA APPROVAZIONE';
                            break;
                        case 1:
                            $font_color='font-white';
                            $bg_stato='bg-success';
                            $stato='APPROVATO';
                            break;
                        case 2:
                            $font_color='font-white';
                            $bg_stato='bg-info';
                            $stato='NASCOSTO';
                            break;
                        default:
                            $font_color='font-dark';
                            $bg_stato='bg-light';
                            $stato='---------';
                            break;
                    }
                    ?>
                    <td class="w-15pc"><span class="pt-1 pb-1 ps-2 pe-2 {{$bg_stato}} {{$font_color}}">{{$stato}}</span></td>
                    <td><?=($approfondimento->titolo)?$approfondimento->titolo:'---';?></td>
                    <td><?=($approfondimento->nome_zoonosi)?$approfondimento->nome_zoonosi:'---';?></td>
                    <td class="text-center"><?=($approfondimento->said_genitore)?$approfondimento->said_genitore:'';?></td>
                    <td>
                        <a href="{{route('adminManageIntegration')}}/{{$approfondimento->said}}" class="font-dark" title="gestisci"><i class="fa fa-pencil fa-lg"></i></a>
                        <!--<a href="#" class="font-red conferma-elimina" idvalore="" sezione=""  title="Nascondi"><i class="fa fa-trash fa-lg"></i></a>-->
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

    </div>

    
</div>
@endsection