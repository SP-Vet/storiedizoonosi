@extends('admin.layout.base')
@section('content')
    <div class="d-flex justify-content-between mt-2">
        <div class="d-inline-flex"><h5 class="pt-2">{{$title_page}}</h5></div>
    </div>
    <hr>
    <?php if(!isset($risposte) || (is_array($risposte) && count($risposte)==0)){ ?>
        <h3>NON SONO STATE ANCORA CARICATE RISPOSTE ALL&apos;INTERNO DEL SISTEMA</h3>
    <?php }else{ ?>
        <div class="row">
            <div class="col-12">
            <?php foreach($domande AS $domanda=>$valuequestion){ ?>
                <table class="table table-bordered">
                    <thead class="table-warning">
                        <th class="text-end">risposte</th>
                        <?php switch($valuequestion->typeanswer){
                            case 1:?>
                                <th><b>SI</b></th>
                                <th><b>NO</b></th>
                                <?php break;
                            case 2: ?>
                                <th><b>1</b></th>
                                <th><b>2</b></th>
                                <th><b>3</b></th>
                                <th><b>4</b></th>
                                <th><b>5</b></th>
                                <th><b>6</b></th>
                                <th><b>7</b></th>
                                <th><b>8</b></th>
                                <th><b>9</b></th>
                                <th><b>10</b></th>
                                <?php break;
                            case 3:?>
                                <th><b>SI</b></th>
                                <th><b>NO</b></th>
                                <th><b>NON SO</b></th>
                                <?php break;
                        }?>
                    </thead>

                    <tbody>
                        <tr>
                            <td><?=(array_key_exists($valuequestion->question,$risposte))?$valuequestion->question:'';?></td>
                            <?php switch($valuequestion->typeanswer){
                                case 1:?>
                                    <td><?=(isset($risposte[$valuequestion->question]['SI']))?$risposte[$valuequestion->question]['SI']:'-';?></td>
                                    <td><?=(isset($risposte[$valuequestion->question]['NO']))?$risposte[$valuequestion->question]['NO']:'-';?></td>
                            <?php break;
                                case 2: ?>
                                    <td><?=(isset($risposte[$valuequestion->question][1]))?$risposte[$valuequestion->question][1]:'-';?></td>
                                    <td><?=(isset($risposte[$valuequestion->question][2]))?$risposte[$valuequestion->question][2]:'-';?></td>
                                    <td><?=(isset($risposte[$valuequestion->question][3]))?$risposte[$valuequestion->question][3]:'-';?></td>
                                    <td><?=(isset($risposte[$valuequestion->question][4]))?$risposte[$valuequestion->question][4]:'-';?></td>
                                    <td><?=(isset($risposte[$valuequestion->question][5]))?$risposte[$valuequestion->question][5]:'-';?></td>
                                    <td><?=(isset($risposte[$valuequestion->question][6]))?$risposte[$valuequestion->question][6]:'-';?></td>
                                    <td><?=(isset($risposte[$valuequestion->question][7]))?$risposte[$valuequestion->question][7]:'-';?></td>
                                    <td><?=(isset($risposte[$valuequestion->question][8]))?$risposte[$valuequestion->question][8]:'-';?></td>
                                    <td><?=(isset($risposte[$valuequestion->question][9]))?$risposte[$valuequestion->question][9]:'-';?></td>
                                    <td><?=(isset($risposte[$valuequestion->question][10]))?$risposte[$valuequestion->question][10]:'-';?></td>
                            <?php break;
                                case 3:?>
                                    <td><?=(isset($risposte[$valuequestion->question]['SI']))?$risposte[$valuequestion->question]['SI']:'-';?></td>
                                    <td><?=(isset($risposte[$valuequestion->question]['NO']))?$risposte[$valuequestion->question]['NO']:'-';?></td>
                                    <td><?=(isset($risposte[$valuequestion->question]['NON SO']))?$risposte[$valuequestion->question]['NON SO']:'-';?></td>
                               <?php break;
                            }?>
                        </tr>
                    </tbody>
                    </table>

            <?php } ?>
            </div>
        </div>
    <?php } ?>
    

@endsection