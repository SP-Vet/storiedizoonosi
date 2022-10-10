@extends('admin.layout.base')
@section('content')
<div class="d-flex justify-content-between mt-2">
    <div class="d-inline-flex"><h5 class="pt-2">{{$title_page}}</h5></div>
    <div class="d-inline-flex"><a href="{{route('adminAggiungiStoria')}}" class="btn btn-success"><i class="fa fa-plus-circle"></i><strong>Aggiungi STORIA</strong></a></div>
</div>
<hr>
<div class="row mt-2">
    <div class="col-md-12">
        <table class="table table-striped">
            <thead class="table-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Zoonosi</th>
                    <th scope="col">Titolo</th>
                    <th scope="col">Data inserimento</th>
                    <th scope="col">Data pubblicazione</th>
                    <th scope="col w-10pc">Stato</th>
                    <th scope="col">Utente</th>
                    
                    <th scope="col">Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($storie AS $storia){ ?>
                <tr>
                    <td><?=$storia->sid;?></td>
                    <td><?=($storia->zid)?$storia->nome_zoonosi:$storia->tipozoonosi_inserito;?></td>
                    <td><strong><?=($storia->titolo)?$storia->titolo:$storia->titolo_inserito;?></strong></td>
                    <td><?=($storia->data_inserimento)?Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $storia->data_inserimento)->format('d/m/Y'):'';?></td>
                    <td><?=($storia->data_pubblicazione)?Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $storia->data_pubblicazione)->format('d/m/Y'):'';?></td>
                    <!--0-in attesa approvazione,1-in lavorazione,2-pubblicata,3-nascosta-->
                    <?php 
                    $bg_stato='';
                    $stato='';
                    switch($storia->stato){
                        case 0:
                            $font_color='font-white';
                            $bg_stato='bg-danger';
                            $stato='ATTESA APPROVAZIONE';
                            break;
                        case 1:
                            $font_color='font-dark';
                            $bg_stato='bg-warning';
                            $stato='IN LAVORAZIONE';
                            break;
                        case 2:
                            $font_color='font-white';
                            $bg_stato='bg-success';
                            $stato='PUBBLICATA';
                            break;
                        case 3:
                            $font_color='font-white';
                            $bg_stato='bg-info';
                            $stato='NASCOSTA';
                            break;
                        default:
                            $font_color='font-dark';
                            $bg_stato='bg-light';
                            $stato='---------';
                            break;
                    }
                    ?>
                    <td class="w-15pc"><span class="pt-1 pb-1 ps-2 pe-2 {{$bg_stato}} {{$font_color}}">{{$stato}}</span></td>
                    <td><?=($storia->uid)?$storia->nomeutente:'AMMINISTRATORE';?></td>
                    <td>
                        <a href="{{route('adminModificaStoria')}}/{{$storia->sid}}" class="font-dark" title="Modifica"><i class="fa fa-pencil fa-lg"></i></a>
                        <!--<a href="#" class="font-red conferma-elimina" idvalore="{{$storia->sid}}" sezione="storie"  title="Elimina"><i class="fa fa-trash fa-lg"></i></a>-->
                        <a href="{{route('adminDatiContestoStoria')}}/{{$storia->sid}}" class="font-dark" title="Dati di contesto"><i class="fa fa-clipboard fa-lg"></i></a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
@endsection