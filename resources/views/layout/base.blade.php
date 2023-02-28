<!DOCTYPE html>
<html lang="it">
    <head>
        <title> @yield('title','Storie di zoonosi')<?php if(isset($title_page) && $title_page!='')echo ' - '.$title_page;else echo '';?></title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <meta name="csrf_token" content="{{ csrf_token() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="Zoonosi, one health, malattie, animale, uomo, storie, racconti, medicina, medicina veterinaria, sanità pubblica ,sanità animale, ambiente, istituto zooprofilattico">
        <meta name="title" content="<?php if(isset($art_title) && $art_title!='')echo $art_title;else echo 'Storie di Zoonosi';?>">
        <meta name="description" content="<?php if(isset($art_description) && $art_description!='')echo $art_description;else echo 'Open Access Repository';?>">
        <meta name="author" content="<?php if(isset($art_author) && $art_author!='')echo $art_author;else echo 'Eros Rivosecchi';?>">
        <meta name="robots" content="index, follow">
        <meta http-equiv="cache-control" content="no-cache">
        <meta http-equiv="expires" content="0">
        <meta http-equiv="pragma" content="no-cache">
        <link rel="schema.DC" href="" /> 
        <meta name="DC.Type" content="<?php if(isset($art_type_dc) && $art_type_dc!='')echo $art_type_dc;else echo 'Interactive Resource';?>" /> 
        <meta name="DC.Title" content="<?php if(isset($art_title) && $art_title!='')echo $art_title;else echo 'Storie di Zoonosi';?>" /> 
        <meta name="DC.Creator" content="<?php if(isset($art_author) && $art_author!='')echo $art_author;else echo 'Eros Rivosecchi';?>" /> 
        <meta name="DC.Subject" content="Storie di Zoonosi" /> 
        <meta name="DC.Description" content="<?php if(isset($art_abstract) && $art_abstract!='')echo $art_abstract;else echo 'Open Access Repository';?>" /> 
        <meta name="DC.Abstract" content="<?php if(isset($art_abstract) && $art_abstract!='')echo $art_abstract;else echo 'Open Access Repository';?>" /> 
        <meta name="DC.Publisher" content="<?php if(isset($art_publisher) && $art_publisher!='')echo $art_publisher;else echo 'https://spvet.it';?>" /> 
        <meta name="DC.Date" content="<?php if(isset($art_datapublic) && $art_datapublic!='')echo Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $art_datapublic)->format('d/m/Y');else echo date('Y-m-d');?>" /> 
        <meta name="DC.Language" content="it_IT" /> 
        <meta property="og:url" content="<?php if(isset($og_url) && $og_url!='')echo $og_url;else echo '';?>">
        <meta property="og:image" content="<?php if(isset($og_image) && $og_image!='')echo $og_image;else { ?>{{ URL::to('/') }}<?php echo '/images/logo_zoonosi.png';}?>">
        <meta property="og:site_name" content="Storie di Zoonosi">
        <meta property="og:type" content="<?php if(isset($og_type) && $og_type!='')echo $og_type;else echo 'website';?>">
        <meta property="og:title" content="<?php if(isset($og_title) && $og_title!='')echo $og_title;else echo 'Storie di Zoonosi';?>">
        <meta property="og:description" content="<?php if(isset($og_description) && $og_description!='')echo $og_description;else echo 'Open Access Repository';?>">
        <meta property="og:locale" content="it_IT">
        <link as="style" href="/css/Lustria/Lustria-Regular.ttf" rel="preload"/>
        <link href="/css/Lustria/Lustria-Regular.ttf" rel="stylesheet" />
        <link rel="stylesheet" href="/css/bootstrap/bootstrap.min.css" type="text/css" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        <link rel="stylesheet" href="/libraries/bootstrap-datepicker-1.9.0-dist/css/bootstrap-datepicker.css" type="text/css" />
        @section('additionalcss')
        @show
        <link rel="stylesheet" href="/css/style.css" type="text/css" />
        <script src="/js/jquery/jquery-3.6.0.min.js"></script> 
        <link rel="icon" type="image/x-icon" href="/images/biohazard32x32.ico">
        @section('additionalcaptcha')
        @show
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
        <!-- END modal -->
        <?php if(isset($settings) && isset($settings['testo_debug_top']) && $settings['testo_debug_top']->valueconfig!=''){?><?=$settings['testo_debug_top']->valueconfig;?><?php } ?>
        <div class="d-flex" id="wrapper">
          <!-- Page content wrapper-->
          <div id="page-content-wrapper">
              <!-- Top navigation-->
            <nav class="navbar navbar-expand-lg navbar-light  border-bottom" id="includedmenu">
                <?php if(Auth::check()){ ?>
                    @include('layout.menulogghed')
                <?php }else{ ?>
                    @include('layout.menuospite')
               <?php } ?>
            </nav>
            <!-- Page content-->
            <div class="container container-fluid pb-2">
                <div class="col-12" id="includeheader2">
                    <?php if(isset($title_page) && $title_page=='Homepage'){?>
                        @include('layout.header_pt1_home')
                    <?php }else{ ?>
                        @include('layout.header_pt1')
                    <?php } ?>
                </div>
                @yield('header_pt2')
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
        <script type="text/javascript" src="/js/custom.js"></script>
        <!-- Core theme JS-->
        @show
    </body>
</html>
