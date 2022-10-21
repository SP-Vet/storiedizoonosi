@extends('admin.layout.base')

@section('additionaljs')
    @parent
    <script type="text/javascript" src="/js/zoonosi.js"></script>
@endsection

@section('content')
<div class="d-flex justify-content-between mt-2">
    <div class="d-inline-flex"><h5 class="pt-2">{{$title_page}}</h5></div>
    <div class="d-inline-flex"><a href="{{route('adminAddZoonoses')}}" class="btn btn-success"><i class="fa fa-plus-circle"></i><strong>Aggiungi ZOONOSI</strong></a></div>
</div>
<hr>
<div class="row mt-2">
    <div class="col-md-12">
        <table class="table table-striped">
            <thead class="table-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Descrizione</th>
                    <th scope="col">Slug</th>
                    <th scope="col">Review</th>
                    <th scope="col" class="text-center">Link Telegram</th>
                    <th scope="col">Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($zoonosi AS $zoo){ ?>
                <tr>
                    <td><?=$zoo->zid;?></td>
                    <td><strong><?=$zoo->nome;?></strong></td>
                    <td><?=html_entity_decode(substr($zoo->descrizione, 0, 100).'...',ENT_QUOTES,'utf-8');?></td>
                    <td><?=$zoo->slugzoonosi;?></td>
                    <td>
                        <?php $urldownloadpdf='';
                            if(array_key_exists($zoo->zid, $revfiles)){ 
                                $urldownloadpdf=url('storagereview/'.$zoo->zid.'/'.$revfiles[$zoo->zid]->file_memorizzato.'/'.$revfiles[$zoo->zid]->titolo_visualizzato);?>
                                <div class="col-12 mt-2">
                                    <a href="{{$urldownloadpdf}}" target="_blank"><img src="/images/pdf-icon.png" width="20" /><span class="ms-2"><?=html_entity_decode($revfiles[$zoo->zid]->titolo_visualizzato,ENT_QUOTES,'utf-8')?></span></a>
                                    <br /><span class="btn btn-danger btn-sm" onclick="removeReview({{$revfiles[$zoo->zid]->srid}});">elimina</span>
                                </div>
                        <?php }else{ ?>
                            <form method="post" enctype="multipart/form-data" class="upload-review">
                                {{ csrf_field() }}
                                <input type="file" name="review" zoonosi="{{$zoo->zid}}" />
                                <button type="submit" class="btn btn-info">Carica Review</button>
                            </form>    
                            <p class="help-info">Seleziona il file tramite il tasto "Sfoglia" e poi clicca su "Carica"</p>
                        <?php } ?>
                    </td>
                    <td class="text-center"><?=($zoo->linktelegram)?'<a href="'.$zoo->linktelegram.'" class="font-primary"><i class="fa fa-telegram fa-lg"></i></a>':'NO';?></td>
                    <td>
                        <a href="{{route('adminModifyZoonoses')}}/{{$zoo->zid}}" class="font-dark" title="Modifica"><i class="fa fa-pencil fa-lg"></i></a>
                        <a href="#" class="font-red conferma-elimina" idvalore="{{$zoo->zid}}" sezione="zoonosi"  title="Elimina"><i class="fa fa-trash fa-lg"></i></a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
@endsection