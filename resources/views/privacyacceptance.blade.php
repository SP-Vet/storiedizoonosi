@extends('layout.base')

@section('additionaljs')
    @parent
@endsection

@if (session('formerrato'))
    <div class="w-100">
        <div class="row ps-3 pe-3">
            <div class="col-12 alert alert-danger">
                {!!session('formerrato')!!}
            </div> 
        </div>
    </div>
@endif
@section('content')

    <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 position-relative">
        <?php if(isset($privacy_accepted_user->ppid)){ ?>
        <h5 class="ps-4 pt-4 pe-4 mb-0">Privacy accettata dall&apos;utente</h5>  
        <div class="col-12 p-4">
            <div class="mb-1 col-12">
                <label for="data_accettazione_visione" class="form-label"><strong>Data accettazione: </strong><?=Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $privacy_accepted_user->data_accettazione_visione)->format('d/m/Y H:i');?></label>
            </div>
            <div style="height: 400px;">
                <div class="container-fluid pb-3 flex-grow-1 d-flex flex-column flex-sm-row overflow-auto" style="height: 400px;">
                    <div class="row flex-grow-sm-1 flex-grow-0">
                        <div class="col overflow-auto h-100" style="display: block;">
                            <div class="bg-grey-transp border-wheat-2 rounded-3 p-3">
                                <?=html_entity_decode($privacy_accepted_user->testoprivacy,ENT_QUOTES,'utf-8');?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr />
        </div>
        <div class="clearfix"></div>
        <?php } ?>
        <h5 class="ps-4 pt-4 pe-4 mb-0">Privacy attuale (da accettare)</h5>  
        <div class="col-12 p-4">
            <div style="height: 800px;">
                <div class="container-fluid pb-3 flex-grow-1 d-flex flex-column flex-sm-row overflow-auto" style="height: 800px;">
                    <div class="row flex-grow-sm-1 flex-grow-0">
                        <div class="col overflow-auto h-100" style="display: block;">
                            <div class="bg-grey-transp border-wheat-2 rounded-3 p-3">
                                <?=html_entity_decode($current_privacy->testoprivacy,ENT_QUOTES,'utf-8');?>
                                <div class="d-flex justify-content-end mb-5"><?php if(isset($settings) && isset($settings['city_privacy']) && $settings['city_privacy']->valueconfig!=''){?><?=html_entity_decode($settings['city_privacy']->valueconfig,ENT_QUOTES,'utf-8');?><?php } ?>, <?=Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $current_privacy->data_pubblicazione)->format('d/m/Y');?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>  
    </div>



<form action="{{route($form)}}/{{$uid}}" id="privacyacceptance" method="POST" class="needs-validation" novalidate>
    {{ csrf_field() }}
    <div class="col-12 text-center mb-5"><button class="btn btn-form-submit" type="submit">Accetta condizioni Privacy</button></div>
    <input type="hidden" name="ppid" value="<?=$current_privacy->ppid;?>" />
    <input type="hidden" name="idutente" value="<?=$uid;?>" />
</form>


@endsection