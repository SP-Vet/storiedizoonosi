/*CKEDITOR.editorConfig = function( config ){
    config.toolbar = [
        ['Source','-','Cut','Copy','Paste','-','Undo','Redo','-',],
        ['Bold','Italic','Strike','NumberedList'],
    ];
    config.extraPlugins = 'markdown';*/
    
    /*config.toolbar_toolbarLight =
    [
         ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Scayt'],
         ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
         ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar', 'Link','Unlink','Anchor', 'Maximize'] ,
         '/',
         ['Styles','Format','Font','FontSize', 'Bold','Italic','Strike','NumberedList','BulletedList','Outdent','Indent','Blockquote', 'TextColor','BGColor'],
         extraPlugins = 'markdown'

    ];

    config.toolbar_Fullx =
    [
      ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
      ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
      ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
      '/',
      ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
      ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
      ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
      ['Link','Unlink','Anchor'],
      ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar'],
      '/',
      ['Styles','Format','Font','FontSize'],
      ['TextColor','BGColor'],
      ['Maximize', 'ShowBlocks'],
      extraPlugins = 'markdown'
   ];*/
   
   /*config.extraPlugins = 'markdown';
   

};*/

CKEDITOR.editorConfig = function( config ) {
	config.toolbarGroups = [
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'forms', groups: [ 'forms' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'about', groups: [ 'about' ] }
	];
        config.height = 100;
        config.width = '100%';
        config.removeButtons = 'Save,NewPage,ExportPdf,Preview,Print,Templates,PasteFromWord,Find,Replace,Scayt,Form,TextField,Textarea,Select,Button,ImageButton,HiddenField,CopyFormatting,CreateDiv,Language,Anchor,Smiley,PageBreak,Font,Format,BGColor,ShowBlocks,About,Checkbox,Radio';
        config.extraPlugins= 'markdown';
        config.markdown = {
            lineWrapping: true
        }
        //config.extraAllowedContent =  'textarea[required]';
        config.filebrowserUploadMethod= 'form';
        //config.filebrowserUploadUrl = "/{{route('ckeditor.upload', ['_token' => csrf_token() ])}}";
        /*config.filebrowserBrowseUrl = "/{{route('ckeditor.upload', ['_token' => csrf_token() ])}}";
        config.filebrowserUploadUrl = '/admin/ckeditor/upload';*/
};