@extends('admin.layout.base')
@section('content')
<div class="d-flex justify-content-between mt-2">
    <div class="d-inline-flex"><h5 class="pt-2">{{$title_page}}</h5></div>
    <!--<div class="d-inline-flex"><a href="{{route('adminAddStory')}}" class="btn btn-success"><i class="fa fa-plus-circle"></i><strong>Aggiungi STORIA</strong></a></div>-->
</div>
<hr>
<div class="row mt-2">
    <div class="col-md-12">
        <table class="table table-striped">
            <thead class="table-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Desc</th>
                    <th scope="col">Valore</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Gruppo</th>
                    
                    <th scope="col">Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($configurations AS $conf){ ?>
                <tr>
                    <td><?=$conf->confid;?></td>
                    <td><?=$conf->nameconfig;?></td>
                    <td title="<?=$conf->desctooltip;?>"><?=$conf->descbase;?></td>
                    <td>
                        <?php 
                        switch($conf->typeconf){
                            case 2:
                            case 3:
                                if($conf->valueconfig==1)echo 'SI';else echo 'NO';
                                break;
                            default:
                                echo html_entity_decode($conf->valueconfig,ENT_QUOTES,'utf-8');
                            break;
                        }    
                        ?>
                    </td>
                    <td>
                    <?php switch($conf->typeconf){
                        case 0:
                            echo 'INPUT TEXT';
                            break;
                        case 1:
                            echo 'TEXTAREA';
                            break;
                        case 2:
                            echo 'CHECKBOX';
                            break;
                        case 3:
                            echo 'RADIOBOX';
                            break;
                        case 4:
                            echo 'FILE';
                            break;
                        default:
                            echo 'ND';
                            break;    
                    } ?>
                    </td>
                    <td>
                    <?php switch($conf->groupsection){ //0-generale, 1-storie, 2-zoonosi, 3-integrazioni, 4-utenti
                        case 0:
                            echo 'GENERALE';
                            break;
                        case 1:
                            echo 'STORIE';
                            break;
                        case 2:
                            echo 'ZOONOSI';
                            break;
                        case 3:
                            echo 'INTEGRAZIONI';
                            break;
                        case 4:
                            echo 'UTENTI';
                            break;
                        default:
                            echo 'ND';
                            break;    
                    }?>
                    </td>
                    <td>
                        <a href="{{route('adminModifySetting')}}/{{$conf->confid}}" class="font-dark" title="Modifica"><i class="fa fa-pencil fa-lg"></i></a>
                        <?php 
                            /*
                        <!--<a href="#" class="font-red conferma-elimina" idvalore="{{$storia->sid}}" sezione="storie"  title="Elimina"><i class="fa fa-trash fa-lg"></i></a>-->
                        <!--<a href="{{route('adminContextDataStory')}}/{{$storia->sid}}" class="font-dark" title="Dati di contesto"><i class="fa fa-clipboard fa-lg"></i></a>-->
                        */ ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
@endsection