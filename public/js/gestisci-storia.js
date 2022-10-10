$(document).ready(function(){
    initDeletePart();
    initDeleteCollaboratore();
    initChooseVideo();
    initSelectSnippetfase();    
    initNuovoSnippet();
    initEliminaSnippet();

    //gestione pulsanti snippets
    /*$('input.accetta-snippet').click(function(){
        //check values snip
        if(checkValidSnip($(this).closest('.blocco-snippet'))){
            chiudiSnippets($(this).closest('.blocco-snippet'),1);
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Attenzione...',
                text: 'Compilare tutti i campi dello snippet prima di accettarlo'
            })
        }
    })*/
    
    initChiudiSnippet();

    //aggiunta parti
    $('.button-add-part').click(function(e){
        e.preventDefault();
        let numparti=$('.contenitore-parte').length;
        let numnuovaparte=numparti+1;
        
        let casualstring=makeidletter(15);

        let parte='';
        parte+='<div class="contenitore-parte border rounded overflow-hidden flex-md-row m-3 p-3">';
            parte+='<div class="row">';
                parte+='<div class="col-md-7">';
                    parte+='<input type="hidden" name="sfid[]" value="'+casualstring+'" />';
                    parte+='<div class="titolo-parte"><div class="fRight"><span class="deletePart bg-danger font-white p-2 rounded pointer"><i class="fa fa-close"></i> Elimina Parte</span></div>',
                    parte+='<div class="testo-numero-parte h5">Parte&nbsp;<span class="numero-parte">'+numnuovaparte+'</span></div>';
                    parte+='<div class="valore-numero-parte">';
                    parte+='<div class="mb-3 col-12">';
                    parte+='<label for="titolo-'+casualstring+'" class="form-label">Titolo Parte<span class="text-required"> * </span></label>';
                    parte+='<div class="input-group has-validation">';
                    parte+='<input type="text" name="titolofase[]" value="" class="form-control input-titoloparte" id="titolo-'+casualstring+'" aria-describedby="titolo-'+casualstring+'" required />';
                    parte+='<div class="invalid-feedback">Campo obbligatorio.</div>';
                    parte+='</div>';
                    parte+='</div>';                                        
                    parte+='</div>';
                    parte+='</div>';
                    parte+='<div class="descrizione-parte">';
                    parte+='<div class="mb-3 col-12">';
                    parte+='<label for="descrizione-parte-'+casualstring+'" class="form-label">Descrizione parte</label>';
                    parte+='<textarea class="form-select descrizione-parte" rows="3" id="descrizione-parte-'+casualstring+'" name="testofase[]" placeholder=""></textarea>';
                    parte+='</div>';
                    parte+='</div>';
                parte+='</div>';
                //add snippet
                parte+=creaSelectSnippetfase();
                
            parte+='</div>';
        parte+='</div>';

        $('.container-parti').append(parte);
        initDeletePart($('.contenitore-parte').last());
        
        //select snippet
        initSelectSnippetfase($('.contenitore-parte').last());
        initChiudiSnippet($('.contenitore-parte').last());
        initNuovoSnippet($('.contenitore-parte').last());
        initEliminaSnippet($('.contenitore-parte').last());
        //add init textarea parte
        let idtextarea= 'descrizione-parte-'+casualstring;
        CKEDITOR.replace(idtextarea, {
            customConfig: '/js/ckeditor_configs/config_simple.js',
            filebrowserUploadUrl: rottaupload,
            filebrowserUploadMethod: 'form'
        });
    })
    
    $('button.addcollaboratore').click(function(){
        let collid=$('#sel_collaboratori').val();
        switch(collid){
            case 'undefined':
                break;
            case '0':
                //nuovo collaboratore da aggiungere
                let txtcoll='';
                txtcoll='<div class="collaboratore d-flex mb-2">';
                txtcoll+='<input type="hidden" name="collid[]" value="" />';
                txtcoll+='<div class="d-inline-flex me-3"><input class="form-control" type="text" name="nomecollaboratore[]" value="" placeholder="Nome" required /></div>';
                txtcoll+='<div class="d-inline-flex me-3" ><input class="form-control" type="text" name="cognomecollaboratore[]" value="" placeholder="Cognome" required /></div>';
                txtcoll+='<div class="d-inline-flex me-3">';
                txtcoll+='<select class="select form-control w-100 sel_ruolo" name="sel_ruolo[]" required>';
                txtcoll+=$('select#tmp_sel_ruolo').html();
                txtcoll+='</select>';
                txtcoll+='</div>';
                txtcoll+='<div class="d-inline-flex"><span class="deleteCollaboratore bg-danger font-white p-2 rounded pointer"><i class="fa fa-close"></i></span></div>';
                txtcoll+='</div>';

                $('.elenco-collaboratori').append(txtcoll);
                $('.sel_ruolo').last().val('');
                $('.sel_ruolo').last().change();
                initDeleteCollaboratore($('.collaboratore').last());

                break;
            default:
                //get dati collaboratore da chiamata ajax
                if($.isNumeric(collid)){
                    $.ajax({
                        type:'POST',
                        url:"/admin/ajx-getcollaboratore",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                        data:{collid:collid},
                        success:function(data){
                            if(!data.error){
                                let txtcoll='';
                                txtcoll='<div class="collaboratore d-flex mb-2">';
                                txtcoll+='<input type="hidden" name="collid[]" value="'+data.collaboratore.collid+'" />';
                                txtcoll+='<div class="d-inline-flex me-3"><input class="form-control" type="text" name="nomecollaboratore[]" value="'+data.collaboratore.nome+'" placeholder="Nome" required readonly /></div>';
                                txtcoll+='<div class="d-inline-flex me-3" ><input class="form-control" type="text" name="cognomecollaboratore[]" value="'+data.collaboratore.cognome+'" placeholder="Cognome" required readonly /></div>';
                                txtcoll+='<div class="d-inline-flex me-3">';
                                txtcoll+='<select class="select form-control w-100 sel_ruolo" name="sel_ruolo[]" required>';
                                 txtcoll+=$('select#tmp_sel_ruolo').html();
                                txtcoll+='</select>';
                                txtcoll+='</div>';
                                txtcoll+='<div class="d-inline-flex"><span class="deleteCollaboratore bg-danger font-white p-2 rounded pointer"><i class="fa fa-close"></i></span></div>';
                                txtcoll+='</div>';
                                
                                $('.elenco-collaboratori').append(txtcoll);
                                $('.sel_ruolo').last().val('');
                                $('.sel_ruolo').last().change();
                                initDeleteCollaboratore($('.collaboratore').last());
                            }else{
                                alert(data.message);
                            }
                        },
                        error: function(error) {console.log(error);},
                        beforeSend: function() {},
                    });
                }
                /*<div class="collaboratore d-flex mb-2">
                    <input type="hidden" name="collid[]" value="<?=$collabstoria->collid;?>" />
                    <div class="d-inline-flex me-3"><input class="form-control" type="text" name="nomecollaboratore[]" value="<?=$collabstoria->nome;?>" placeholder="Nome" /></div>
                    <div class="d-inline-flex me-3" ><input class="form-control" type="text" name="cognomecollaboratore[]" value="<?=$collabstoria->cognome;?>" placeholder="Cognome" /></div>
                    <div class="d-inline-flex me-3">
                        <select class="select form-control w-100 sel_ruolo" name="sel_ruolo[]" required>
                            <option value="">Ruolo del collaboratore nella storia</option>
                            <?php foreach ($ruoli AS $ruolo){ ?>
                            <option value="<?=$ruolo->rid;?>" <?php if($collabstoria->rid==$ruolo->rid)echo 'selected="selected"';?>><?=$ruolo->nomeruolo;?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="d-inline-flex"><span class="deleteCollaboratore bg-danger font-white p-2 rounded pointer"><i class="fa fa-close"></i></span></div>
                </div>*/
                break;
        }
        
        //$('.elenco-collaboratori').append();
    })
    

    /*$('button.salvastoria').click(function(e){
        e.preventDefault();
        let title='';
        let text='';
        if($('#gestionestoria').find('button[type="submit"].btn-showloader')){
            title=$('button[type="submit"]').attr("title-loader");
            text=$('button[type="submit"]').attr("text-loader");
        };
        showloader(title,text);
        svuotaContainerMSGError();
        nascondiContainerMSGError();
        //visualizzaContainerMSGError();
        var form = $('#gestionestoria');
        if (!form.checkValidity() || !checkValidityCKEDITORGestionestoria($('#gestionestoria'))) {
            //e.stopPropagation();
            hideloader();
            //creazione blocco errori
            
            
        }else{
            $('#gestionestoria').submit();
        }   
    })*/
                
    $('span.removepodcastfile').click(function(){$("#podcast").val('');})
    $('span.removevideofile').click(function(){$("#video").val('');})
    $('span.removevideoAJAX').click(function(){
        $('#filevideo').val('');
        $(this).parent().remove();
    })
    $('span.removepodcastAJAX').click(function(){
        $('#filepodcast').val('');
        $(this).parent().remove();
    })
    $('span.removepdfstoriaAJAX').click(function(){
        $('#filepdf').val('');
        $(this).parent().remove();
    })
    
})

function checkValidityCKEDITORGestionestoria(){
    let flagerr=0;
    
    let textbox_abstract = CKEDITOR.instances.abstract.getData();
    if (textbox_abstract===''){
        flagerr=1;
        aggiungiErroreMSGError('<p><strong>L&apos;Abstract è obbligatorio</strong></p>');
    }
    
    let textbox_copyright = CKEDITOR.instances.copyright.getData();
    if (textbox_copyright===''){
        flagerr=1;
        aggiungiErroreMSGError('<p><strong>Il Copyright è obbligatorio</strong></p>');
    }
    
    //descrizioni parti storia
    if($('.contenitore-parte').length==='undefined' || $('.contenitore-parte').length==0){
        flagerr=1;
        aggiungiErroreMSGError('<p><strong>Inserire almeno una parte della storia</strong></p>');
    }else{
        $('.contenitore-parte').each(function(){
            let iddesc=$(this).find('textarea.descrizione-parte').attr('id');
            let textbox_descrizioneparte=CKEDITOR.instances[iddesc].getData();
            if (textbox_descrizioneparte===''){
                flagerr=1;
                aggiungiErroreMSGError('<p><strong>La descrizione della Parte '+$(this).find('span.numero-parte').html()+' della storia è obbligatoria</strong></p>');
            }
        })
    }
    
    //testo snippet
    if($('.blocco-snippet').length!=='undefined' && $('.blocco-snippet').length>0){
        $('.blocco-snippet').each(function(){
            let iddescsnip=$(this).find('textarea.testosnippet').attr('id');
            let textbox_descrizionesnip=CKEDITOR.instances[iddescsnip].getData();
            if (textbox_descrizionesnip===''){
                flagerr=1;
                aggiungiErroreMSGError('<p><strong>Lo snippet "'+$(this).closest('.contenitoresnippetsfase').find('.titolosnippet').val()+'" della Parte '+$(this).closest('.contenitore-parte').find('span.numero-parte').html()+' della storia è obbligatorio</strong></p>');
            }
        })
    }
    
    

    if(flagerr==1)return false;
    
    return true;
    
}


function checkValidityCollaboratori(){
    if(!$('div.collaboratore').length){
        aggiungiErroreMSGError('<p>Aggiungere almeno un collaboratore (autore) alla storia</p>');
        return false;
    }
    
    return true;
}


function aggiungiErroreMSGError(msg){
    $('.msgContainerError').append(msg);
}

function svuotaContainerMSGError(){
    $('.msgContainerError').html('');
}
function nascondiContainerMSGError(){
    $('.checkerroripreinvio').each(function(){
        if(!$(this).hasClass('d-none'))$(this).addClass('d-none');
    })
}
function visualizzaContainerMSGError(){
    $('.checkerroripreinvio').each(function(){
        $(this).find('.msgContainerError').prepend('<p><strong>ATTENZIONE! Ci sono degli errori all\'interno della storia.</strong></p>')
        if($(this).hasClass('d-none'))$(this).removeClass('d-none');
    })
}

function initNuovoSnippet(obj){
    if(obj){
        $(obj).find('.nuovo-snippet').click(function(){
            let sfid=$(this).closest('.contenitore-parte').find('input[name="sfid[]"]').val();
            //alert(sfid);
            creaBloccoSnippet(sfid,$(this).closest('.contenitore-parte'));
        })
    }else{
        $('.nuovo-snippet').click(function(){
            let sfid=$(this).closest('.contenitore-parte').find('input[name="sfid[]"]').val();
            //alert(sfid);
            creaBloccoSnippet(sfid,$(this).closest('.contenitore-parte'));
        })
    }
}

function initEliminaSnippet(obj){
    if(obj){
        $(obj).find('.elimina-snippet').click(function(){
            let snid=$(this).closest('.contenitore-parte').find('select.snippetsfase').val();
            if($.isArray(snid))snid=snid[0];
            if(snid!=='undefined' && snid!=''){
                //rimuovi opzione select
                $(this).closest('.contenitore-parte').find('select.snippetsfase option[value="'+snid+'"]').remove();
                //deselezionare la select
                $(this).closest('.contenitore-parte').find('select.snippetsfase option:selected').prop("selected", false);
                //nascondere tutti blocchi snippets
                chiudiTuttiSnippetFase($(this).closest('.contenitore-parte'));
                //cancellare blocco snippets
                $('.blocco-snippet[snid="'+snid+'"]').remove();
            }
        })
    }else{
        $('.elimina-snippet').click(function(){
            let snid=$(this).closest('.contenitore-parte').find('select.snippetsfase').val();
            if($.isArray(snid))snid=snid[0];
            if(snid!=='undefined' && snid!=''){
                //rimuovi opzione select
                $(this).closest('.contenitore-parte').find('select.snippetsfase option[value="'+snid+'"]').remove();
                //deselezionare la select
                $(this).closest('.contenitore-parte').find('select.snippetsfase option:selected').prop("selected", false);
                //nascondere tutti blocchi snippets
                chiudiTuttiSnippetFase($(this).closest('.contenitore-parte'));
                //cancellare blocco snippets
                $('.blocco-snippet[snid="'+snid+'"]').remove();
            }
        })
    }
}

function creaSelectSnippetfase(){
    let txtbloccosnip='';
    txtbloccosnip+='<div class="col-md-5">';
        txtbloccosnip+='<div class="mb-3 mt-4 col-12 contenitoresnippetsfase">';
            txtbloccosnip+='<div class="d-flex justify-content-between mb-2">';
                txtbloccosnip+='<div class="d-inline-flex"><h5 class="mb-0">Snippets</h5></div>';
                txtbloccosnip+='<div class="d-inline-flex">';
                    txtbloccosnip+='<input type="button" class="btn btn-sm btn-outline-success me-2 nuovo-snippet" value="Nuovo Snippet" />';
                    txtbloccosnip+='<input type="button" class="btn btn-sm btn-outline-danger elimina-snippet" value="Elimina Snippet" />';
                txtbloccosnip+='</div>';
            txtbloccosnip+='</div>';

            txtbloccosnip+='<select class="form-control snippetsfase" multiple></select>';

            txtbloccosnip+='<hr />';
        txtbloccosnip+='</div>';  
    txtbloccosnip+='</div>';
    return txtbloccosnip;
}

function initSelectSnippetfase(obj){
    if(obj){
        //gestione visualizzazione snippet fase
        $(obj).find('select.snippetsfase').change(function(){
            let snid=$(this).val();
            if($.isArray(snid))snid=snid[0];
            if(/*$.isNumeric(snid) && */snid!=='undefined' && snid!=''){
                $(this).closest('.contenitoresnippetsfase').find('.blocco-snippet').each(function(){
                    if(!$(this).hasClass('d-none'))
                        $(this).addClass('d-none');
                })
                $('.blocco-snippet[snid="'+snid+'"]').removeClass('d-none');
            }else{
                //alert("no selection");
            }
        })
    }else{
        //gestione visualizzazione snippet fase
        $('select.snippetsfase').change(function(){
            let snid=$(this).val();
            if($.isArray(snid))snid=snid[0];
            if(/*$.isNumeric(snid) && */snid!=='undefined' && snid!=''){
                $(this).closest('.contenitoresnippetsfase').find('.blocco-snippet').each(function(){
                    if(!$(this).hasClass('d-none'))
                        $(this).addClass('d-none');
                })
                $('.blocco-snippet[snid="'+snid+'"]').removeClass('d-none');
            }else{
                //alert("no selection");
            }
        })
    }
}


function initChiudiSnippet(obj){
    if(obj){
        $(obj).find('input.chiudi-snippet').click(function(){
            //check values snip
            if(checkValidSnip($(this).closest('.blocco-snippet'))){
                chiudiSnippets($(this).closest('.blocco-snippet'),1);
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Attenzione...',
                    text: 'Compilare tutti i campi dello snippet prima di accettarlo'
                })
            }
        })
    }else{
        $('input.chiudi-snippet').click(function(){
            //chiudiSnippets($(this).closest('.blocco-snippet'),0);
            //check values snip
            if(checkValidSnip($(this).closest('.blocco-snippet'))){
                chiudiSnippets($(this).closest('.blocco-snippet'),1);
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Attenzione...',
                    text: 'Compilare tutti i campi dello snippet prima di accettarlo'
                })
            }
        })
    }
}

function chiudiTuttiSnippetFase(bloccosnippets){
    $(bloccosnippets).find('.blocco-snippet').each(function(){
        if(!$(this).hasClass('d-none'))$(this).addClass('d-none');
    })
}

function makeid(length) {
    var result= '';
    var characters= '0123456789';
    var charactersLength = characters.length;
    for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
   }
   return result;
}

function makeidletter(length) {
    var result= '';
    var characters= 'abcdefghijklmnopqrstuvwxyz';
    var charactersLength = characters.length;
    for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
   }
   return result;
}

function creaBloccoSnippet(sfid,contenitoreparte,){
    let bloccosnip='';
    let casualstring=makeidletter(15);
    
    //chiusura di tutti gli snippet aperti per la parte di storia
    chiudiSnippets($(contenitoreparte).find('.blocco-snippet'),0);
    
    bloccosnip+='<div class="blocco-snippet row" snid="'+casualstring+'">';
        bloccosnip+='<input type="hidden" name="snid['+sfid+'][]" value="" />';
        bloccosnip+='<div class="mb-3 col-md-6">';
            bloccosnip+='<label for="titolosnippet-'+casualstring+'" class="form-label">Titolo Snippet<span class="text-required"> * </span></label>';
            bloccosnip+='<div class="input-group has-validation">';
                bloccosnip+='<input type="text" name="titolosnippet['+sfid+'][]" value="" class="form-control titolosnippet" id="titolosnippet-'+casualstring+'" aria-describedby="titolosnippet-'+casualstring+'" required />';
                bloccosnip+='<div class="invalid-feedback">Campo obbligatorio.</div>';
            bloccosnip+='</div>';
        bloccosnip+='</div>';
        bloccosnip+='<div class="mb-3 col-md-6">';
            bloccosnip+='<label for="chiavesnippet-'+casualstring+'" class="form-label">Chiave Snippet<span class="text-required"> * </span></label>';
            bloccosnip+='<div class="input-group has-validation">';
                bloccosnip+='<input type="text" name="chiavesnippet['+sfid+'][]" value="" class="form-control chiavesnippet" id="chiavesnippet-'+casualstring+'" aria-describedby="chiavesnippet-'+casualstring+'" required />';
                bloccosnip+='<div class="invalid-feedback">Campo obbligatorio.</div>';
            bloccosnip+='</div>';
        bloccosnip+='</div>';  
        bloccosnip+='<div class="mb-3 col-12">';
            bloccosnip+='<label for="testosnippet-'+casualstring+'" class="form-label">Testo Snippet<span class="text-required"> * </span></label>';
            bloccosnip+='<div class="input-group has-validation">';
                bloccosnip+='<textarea class="form-select testosnippet" rows="3" id="testosnippet-'+casualstring+'" name="testosnippet['+sfid+'][]" placeholder="" required></textarea>';
                bloccosnip+='<div class="invalid-feedback">Campo obbligatorio.</div>';
            bloccosnip+='</div>';
        bloccosnip+='</div>';
        bloccosnip+='<div class="mb-3 col-12">';
            bloccosnip+='<input type="button" class="btn btn-sm btn-warning chiudi-snippet" value="Chiudi Snippet" />';
        bloccosnip+='</div>';
    bloccosnip+='</div>';
    
    //console.log($('input[name="sfid[]"][value="'+sfid+'"]').closest('.contenitore-parte').find('.contenitoresnippetfase'));
    //console.log(casualstring);
    $('input[name="sfid[]"][value="'+sfid+'"]').closest('.contenitore-parte').find('.contenitoresnippetsfase').append(bloccosnip);
    
    let idtextarea= 'testosnippet-'+casualstring;
    CKEDITOR.replace(idtextarea, {
        customConfig: '/js/ckeditor_configs/config_simple.js',
        filebrowserUploadUrl: rottaupload,
        filebrowserUploadMethod: 'form'
    });
    
    creaNuovaOpzioneSnippet($(contenitoreparte).find('select.snippetsfase'),casualstring);
    initChiudiSnippet($('input[name="sfid[]"][value="'+sfid+'"]').closest('.contenitore-parte').find('.contenitoresnippetsfase').last('.blocco-snippet'));
}

function creaNuovaOpzioneSnippet(selsnip,id){
        $(selsnip).append($('<option>', {
            value: id,
            text: 'NUOVO SNIPPET'
        }));
    }

function checkValidSnip(snip){    
    if($(snip).find('.titolosnippet').val()==='' 
        || $(snip).find('.chiavesnippet').val()==='' 
        ||  CKEDITOR.instances[$(snip).find('.testosnippet').attr('id')].getData()==='')
    return false;

    return true;
    
}

function chiudiSnippets(snip,salva){
    $(snip).closest('.contenitoresnippetsfase').find('.snippetsfase').prop("selected", false);
    $(snip).closest('.contenitoresnippetsfase').find('.snippetsfase').val('').change();
    $(snip).addClass('d-none');
    
    /*if(!$.isNumeric($('#sid').val()) && salva==0){
        //svuotacampi
        $(snip).find('.titolosnippet').val('');
        $(snip).find('.chiavesnippet').val('');
        CKEDITOR.instances[$(snip).find('.testosnippet').attr('id')].setData('');
    }*/
}

function initChooseVideo(){
    $('input.tipovideo').change(function(){
        switch($(this).val()){
            case '1':
                //URL/HTML
                $('div.containerurlvideo').removeClass("d-none");
                //$('div.containerurlvideo textarea.linkurlhtml').val('');
                if(!$('div.containeruploadvideo').hasClass("d-none"))
                    $('div.containeruploadvideo').addClass("d-none");
                
                break;
            case '2':
                //UPLOAD VIDEO
                $('div.containeruploadvideo').removeClass("d-none");
                if(!$('div.containerurlvideo').hasClass("d-none"))
                    $('div.containerurlvideo').addClass("d-none");
                break;
        }
        
    })
}

function initDeletePart(obj){
    if(typeof obj !== 'undefined'){
        $(obj).find('.deletePart').click(function(){
            confermaEliminazioneParte($(this));
        })
    }else{
        $('.deletePart').click(function(){
            confermaEliminazioneParte($(this));
        })
    }
}
function confermaEliminazioneParte(parte){
    Swal.fire({
        title: "Conferma eliminazione",
        text: "Sei sicuro di voler eliminare questo contenuto?"+"\n"+"Eventuali approfondimenti collegati alla parte verranno eliminati.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        confirmButtonText: 'Elimina',
        cancelButtonText: 'Annulla',
        showLoaderOnConfirm: true,
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            $(parte).closest('.contenitore-parte').remove();
            ricalcolaNumerazioneParti();
        }
    }) 
}

function initDeleteCollaboratore(obj){
    if(typeof obj !== 'undefined'){
        $(obj).find('.deleteCollaboratore').click(function(){
            confermaEliminazioneCollaboratore($(this));
        })
    }else{
        $('.deleteCollaboratore').click(function(){
            confermaEliminazioneCollaboratore($(this));
        })
    }
}
function confermaEliminazioneCollaboratore(collaboratore){
    Swal.fire({
        title: "Conferma eliminazione",
        text: "Sei sicuro di voler eliminare questo collaboratore?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        confirmButtonText: 'Elimina',
        cancelButtonText: 'Annulla',
        showLoaderOnConfirm: true,
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            $(collaboratore).closest('.collaboratore').remove();
        }
    }) 
}


function ricalcolaNumerazioneParti(){
    let numparti=1;
    $('.contenitore-parte').each(function(){
        $(this).find('.titolo-parte .testo-numero-parte .numero-parte').html(numparti)
        $(this).find('.valore-numero-parte label.form-label').attr("for",'titolo-'+numparti);
        //$(this).find('.valore-numero-parte input.input-titoloparte').attr("name",'titolo-'+numparti);
        $(this).find('.valore-numero-parte input.input-titoloparte').attr("id",'titolo-'+numparti);
        $(this).find('.valore-numero-parte input.input-titoloparte').attr("aria-describedby",'titolo-'+numparti);
        $(this).find('.descrizione-parte label.form-label').attr("for",'descrizione-parte-'+numparti);
        //$(this).find('.descrizione-parte textarea').attr("name",'descrizione-parte-'+numparti);
        $(this).find('.descrizione-parte textarea').attr("id",'descrizione-parte-'+numparti);
        numparti+=1;

    })

}