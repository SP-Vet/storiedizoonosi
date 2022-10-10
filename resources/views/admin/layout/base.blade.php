<!DOCTYPE html>
<html lang="it">
    <head>
        <title> @yield('title','Dashboard - Storie di zoonosi')<?php if(isset($title_page) && $title_page!='')echo ' - '.$title_page;else echo '';?></title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <meta name="csrf_token" content="{{ csrf_token() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="HTML, CSS, jQuery, PHP, Ajax">
        <meta name="robots" content="index, follow">
        <meta http-equiv="cache-control" content="no-cache">
        <meta http-equiv="expires" content="0">
        <meta http-equiv="pragma" content="no-cache">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lustria&family=Cormorant+Garamond&display=swap" rel="stylesheet"> 
        <link rel="stylesheet" href="/css/bootstrap/bootstrap.min.css" type="text/css" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        <link rel="stylesheet" href="/libraries/bootstrap-datepicker-1.9.0-dist/css/bootstrap-datepicker.css" type="text/css" />
        @section('additionalcss')
        @show
        <link rel="stylesheet" href="/css/style.css" type="text/css" />
        <script src="/js/jquery/jquery-3.6.0.min.js"></script> 
        <link rel="icon" type="image/x-icon" href="/images/biohazard32x32.ico">
        
    </head>
    <body>
        <div class="custom-loader-mask">
          <div class="custom-loader-textbox">
              <div class="custom-loader-title">Attendere...</div>
              <div class="custom-loader-text"></div>
          </div>
          <div class="custom-loader"></div>
        </div>
        <!-- START modal -->
        @yield('modal_1')
        @yield('modal_2')
        @yield('modal_3')
        @yield('modal_4')
        
        <div class="d-flex" id="wrapper">
            <div class="border-end bg-white" id="sidebar-wrapper">
                <div class="sidebar-heading border-bottom bg-header"><a href="{{route('dashboard')}}" class="font-white text-decoration-none"><i class="fa fa-home"></i>Storie di zoonosi</a></div>
                  @include('admin.layout.menu'.$admin->role)
                </div>
            <!-- Page content wrapper-->
            <div id="page-content-wrapper">
                <!-- Top navigation-->
                <nav class="navbar navbar-expand-lg navbar-light bg-header border-bottom" id="includedmenu" style="padding-bottom: .6rem;">
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

                        <div class="collapse navbar-collapse d-flex" id="navbarSupportedContent">
                            <div><strong>Benvenuto {{$admin->name}}, sei loggato come {{strtoupper($admin->role)}}</strong></div>
                            <ul class="navbar-nav  mt-2 mt-lg-0 ms-auto">
                                <li class="nav-item me-2"><a class="nav-link btn-registrati" href="{{route('adminCambiapassword')}}/{{$admin->id}}" title="Cambia password"><i class="fa fa-key"></i>Cambia password</a></li>
                                <li class="nav-item "><a class="nav-link btn-registrati" href="{{route('adminLogout')}}" title="Disconnetti"><i class="fa fa-sign-out"></i>Disconnetti</a></li>
                            </ul>
                            <!--<div class="justify-content-end"><p class="mt-2 mb-1">Benvenuto, <strong>U. Prova</strong></p></li>--> 
                        </div>
                    </div>
                </nav>
                <!-- Page content-->
                <div class="container-fluid pb-2 mt-3">
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
        @include('admin.layout.footer')
        @section('additionaljs')
        <!-- Bootstrap core JS-->
        <script src="/js/bootstrap/bootstrap.bundle.min.js"></script>
        <script type="text/javascript" src="/libraries/bootstrap-datepicker-1.9.0-dist/js/bootstrap-datepicker.js"></script>
        <script type="text/javascript" src="/libraries/bootstrap-datepicker-1.9.0-dist/locales/bootstrap-datepicker.it.min.js" charset="UTF-8"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="/js/ckeditor/ckeditor.js"></script>
        <!--<script src="/node_modules/@ckeditor/ckeditor5-build-classic/build/ckeditor.js"></script>-->
        <script type="text/javascript" src="/js/custom.js" /></script>
        <script type="text/javascript" src="/js/custom_admin.js" /></script>
        <!-- Core theme JS-->
        @show
    </body>
</html>
