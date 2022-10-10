<!DOCTYPE html>

<html>

<head>

    <title>Laravel 6 Ckeditor Image Upload Example - ItSolutionStuff.com</title>

    <script src="/js/ckeditor/ckeditor.js"></script>

</head>

<body>

  

<h1>Laravel 6 Ckeditor Image Upload Example - ItSolutionStuff.com</h1>

<textarea name="editor1"></textarea>

   

<script type="text/javascript">

    CKEDITOR.replace('editor1', {

        filebrowserUploadUrl: "<?php route('ckeditor.upload', ['_token' => csrf_token() ]);?>",

        filebrowserUploadMethod: 'form'

    });

</script>

   

</body>

</html>