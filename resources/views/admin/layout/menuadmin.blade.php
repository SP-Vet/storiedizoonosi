<div class="list-group list-group-flush">
    <!-- <span class="badge bg-danger fRight"></span> -->
    <a class="list-group-item list-group-item-action list-group-item-light p-3 <?=(isset($menuactive) && $menuactive=='zoonosi')?'active':'';?>" href="{{route('adminListZoonosi')}}"><i class="fa fa-bug"></i>&nbsp;Zoonosi<span class="badge bg-danger fRight"></span></a>
    <a class="list-group-item list-group-item-action list-group-item-light p-3 <?=(isset($menuactive) && $menuactive=='storie')?'active':'';?>" href="{{route('adminListStorie')}}"><i class="fa fa-archive"></i>&nbsp;Storie<span class="badge bg-danger fRight menu-notificastorie"></span></a>
    <a class="list-group-item list-group-item-action list-group-item-light p-3 <?=(isset($menuactive) && $menuactive=='approfondimenti')?'active':'';?>" href="{{route('adminListApprofondimenti')}}"><i class="fa fa-comment-o"></i>&nbsp;Integrazioni<span class="badge bg-danger fRight menu-notificaapprofondimenti"></span></a>
    <a class="list-group-item list-group-item-action list-group-item-light p-3 <?=(isset($menuactive) && $menuactive=='gruppodilavoro')?'active':'';?>" href="{{route('adminListGruppodilavoro')}}"><i class="fa fa-user-o"></i>&nbsp;Gruppo di lavoro</a>
    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!"><i class="fa fa-wrench"></i>&nbsp;Impostazioni</a>
    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="/admin/logs"><i class="fa fa-cog"></i>&nbsp;Visualizza Log</a>
</div>