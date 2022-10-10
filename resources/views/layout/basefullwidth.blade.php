<!DOCTYPE html>
<html lang="it">
    <head>
        <title> @yield('title','Zoospot')<?php if(isset($title_page) && $title_page!='')echo ' - '.$title_page;else echo '';?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="csrf_token" content="{{ csrf_token() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="HTML, CSS, jQuery, PHP, Ajax">
        <meta name="description" content="ZooSpot">
        <meta name="author" content="Eros Rivosecchi">
        <meta name="robots" content="index, follow">
        <meta http-equiv="cache-control" content="no-cache">
        <meta http-equiv="expires" content="0">
        <meta http-equiv="pragma" content="no-cache">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lustria&display=swap" rel="stylesheet"> 
        <link rel="stylesheet" href="/css/bootstrap/bootstrap.min.css" type="text/css" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        <link rel="stylesheet" href="/libraries/bootstrap-datepicker-1.9.0-dist/css/bootstrap-datepicker.css" type="text/css" />
        @section('additionalcss')
        @show
        <link rel="stylesheet" href="/css/style.css" type="text/css" />
        <script src="/js/jquery/jquery-3.6.0.min.js"></script> 
        <link rel="icon" type="image/x-icon" href="/images/zooico.ico">
    </head>
<body>
    <!-- START modal -->
    @yield('modal_1')
    @yield('modal_2')
    @yield('modal_3')
    @yield('modal_4')
    <!-- END modal -->
        <div class="text-center w-98 p-2 m-2 border-blue-dark border-1">DEBUG VERSIONE 1.0 - PUBBLICAZIONE PROVVISORIA IN FASE DI TEST. La versione funzionante sarà disponibile il 28 Febbraio 2022. ALLEGATO ALL'E-JOURNAL SPVET.IT [ISSN 1592-1581] -  redazione-spvet@izsum.it  Tel. 075-343207.</div>
    <div class="d-flex" id="wrapper">
      <!-- Page content wrapper-->
    <div id="page-content-wrapper">
          <!-- Top navigation-->
        <nav class="navbar navbar-expand-lg navbar-light border-bottom" id="includedmenu">
           <?php if(Auth::check()){ ?>
                @include('layout.menulogghed')
            <?php }else{ ?>
                @include('layout.menuospite')
            <?php } ?>
        </nav>
        <!-- Page content-->
        <div class="container-fluid pb-2">
            @yield('header_pt1')
            @if (session('messageinfo'))
                    <div class="alert alert-success">
                        {!!session('messageinfo')!!}
                    </div>
                @endif
                @if (session('messagedanger'))
                    <div class="alert alert-danger">
                        {!!session('messagedanger')!!}
                    </div>
                @endif
            @yield('content')
        </div>
      </div>
    </div>
        @include('layout.footer')  
        @section('additionaljs')
        <!-- Bootstrap core JS-->
        <script src="/js/bootstrap/bootstrap.bundle.min.js"></script>
        <script type="text/javascript" src="/libraries/bootstrap-datepicker-1.9.0-dist/js/bootstrap-datepicker.js"></script>
        <script type="text/javascript" src="/libraries/bootstrap-datepicker-1.9.0-dist/locales/bootstrap-datepicker.it.min.js" charset="UTF-8"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script type="text/javascript" src="/js/custom.js" /></script>
        <!-- Core theme JS-->    
        @show  
    </body>
</html>
