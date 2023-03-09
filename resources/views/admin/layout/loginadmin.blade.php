<!DOCTYPE html>
<html lang="en">
    <head>
        <title> @yield('title','Storie di Zoonosi') - Login utente</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-">
        <meta name="csrf_token" content="{{ csrf_token() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="HTML, CSS, jQuery, PHP, Ajax">
        <meta name="description" content="ZooSpot">
        <meta name="author" content="Eros Rivosecchi">
        <meta name="robots" content="index, follow">
        <meta http-equiv="cache-control" content="no-cache">
        <meta http-equiv="expires" content="0">
        <meta http-equiv="pragma" content="no-cache">
        <link as="style" href="/css/Lustria/Lustria-Regular.ttf" rel="preload"/>
        <link href="/css/Lustria/Lustria-Regular.ttf" rel="stylesheet" />
        <link rel="stylesheet" href="/css/bootstrap/bootstrap.min.css" type="text/css" />
        @section('additionalcss')
        @show
        <link rel="stylesheet" href="/css/style.css" type="text/css" />
        <link rel="stylesheet" href="/css/login.css" type="text/css" />
        <script src="/js/jquery/jquery-3.6.0.min.js"></script> 
        <link rel="icon" type="image/x-icon" href="/images/biohazard32x32.ico">
        <!-- MTCaptcha javascript configuration and import, copy start -->
        <script>
            var mtcaptchaConfig = {
                "sitekey": "<?=config('app.MTCAPTCHApublic');?>",
                "widgetSize": "mini",
                "theme": "neowhite",
                "lang": "it"
            };
            (function(){var mt_service = document.createElement('script');mt_service.async = true;mt_service.src = 'https://service.mtcaptcha.com/mtcv1/client/mtcaptcha.min.js';(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(mt_service);
            var mt_service2 = document.createElement('script');mt_service2.async = true;mt_service2.src = 'https://service2.mtcaptcha.com/mtcv1/client/mtcaptcha2.min.js';(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(mt_service2);}) ();
        </script>
        <!-- MTCaptcha javascript configuration and import, copy end -->
    </head>
  <body>
        @yield('content')
            
        @yield('footerlogin')  
      
        @section('additionaljs')
        <!-- Bootstrap core JS-->
        <script src="/js/bootstrap/bootstrap.bundle.min.js"></script>
        <script type="text/javascript" src="/libraries/bootstrap-datepicker-1.9.0-dist/js/bootstrap-datepicker.js"></script>
        <script type="text/javascript" src="/libraries/bootstrap-datepicker-1.9.0-dist/locales/bootstrap-datepicker.it.min.js" charset="UTF-8"></script>
        <script type="text/javascript" src="/js/custom.js" /></script>
        <!-- Core theme JS-->
        @show
        
    </body>
</html>
