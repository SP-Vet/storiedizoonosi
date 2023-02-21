/*
 * Italian Ministry of Health Research Project: MEOH/2021-2022 - IZS UM 04/20 RC
 * Created on 2023
 * @author Eros Rivosecchi <e.rivosecchi@izsum.it>
 * @author IZSUM Sistema Informatico <sistemainformatico@izsum.it>
 * 
 * @license 
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at

 * http://www.apache.org/licenses/LICENSE-2.0

 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 * 
 * @version 1.0
 */

$(document).ready(function(){
    $('textarea.risposta').each(function(){
        let id=$(this).attr('id');
        CKEDITOR.replace( id, {
            customConfig: '/js/ckeditor_configs/config_simple.js',
            filebrowserUploadUrl: rottaupload,
            filebrowserUploadMethod: 'form'
        });
    });
    
    initDeletePart();
    //adding parts
    $('.button-add-part').click(function(e){
        e.preventDefault();
        let numparti=$('.contenitore-parte').length;
        let numnuovaparte=numparti+1;
        let casualstring=makeidletter(15);
        let parte='';
        parte+='<div class="contenitore-parte border rounded overflow-hidden flex-md-row m-3 p-3">';
            
            parte+='<div class="col-md-12">';
                parte+='<input type="hidden" name="dbid[]" value="'+casualstring+'" />';
                parte+='<div class="titolo-parte"><div class="fRight"><span class="deletePart bg-danger font-white p-2 rounded pointer"><i class="fa fa-close"></i> Elimina Dato</span></div>',
                parte+='<div class="testo-numero-parte h5">Dato&nbsp;<span class="numero-parte">'+numnuovaparte+'</span></div>';
                parte+='<div class="valore-numero-parte">';
                parte+='<div class="mb-3 col-12">';
                parte+='<label for="domanda-'+casualstring+'" class="form-label">Titolo<span class="text-required"> * </span></label>';
                parte+='<div class="input-group has-validation">';
                parte+='<input type="text" name="domanda[]" value="" class="form-control input-domanda" id="domanda-'+casualstring+'" aria-describedby="domanda-'+casualstring+'" required />';
                parte+='<div class="invalid-feedback">Campo obbligatorio.</div>';
                parte+='</div>';
                parte+='</div>';                                        
                parte+='</div>';
                parte+='</div>';
                parte+='<div class="descrizione-parte">';
                parte+='<div class="mb-3 col-12">';
                parte+='<label for="risposta-'+casualstring+'" class="form-label">Descrizione</label>';
                parte+='<textarea class="form-select risposta" rows="3" id="risposta-'+casualstring+'" name="risposta[]" placeholder=""></textarea>';
                parte+='</div>';
                parte+='</div>';
            parte+='</div>';

        parte+='</div>';

        $('.container-parti').append(parte);
        initDeletePart($('.contenitore-parte').last());
        
        //add init textarea part
        let idtextarea= 'risposta-'+casualstring;
        CKEDITOR.replace(idtextarea, {
            customConfig: '/js/ckeditor_configs/config_simple.js',
            filebrowserUploadUrl: rottaupload,
            filebrowserUploadMethod: 'form'
        });
    }) 
})

function checkValidityCKEDITORContextdata(){
    let flagerr=0;
    //context data descriptions
    if($('.contenitore-parte').length==='undefined' || $('.contenitore-parte').length==0){
        flagerr=1;
        aggiungiErroreMSGError('<p><strong>Inserire almeno una descrizione</strong></p>');
    }else{
        $('.contenitore-parte').each(function(){
            let iddesc=$(this).find('textarea.risposta').attr('id');
            let textbox_descrizioneparte=CKEDITOR.instances[iddesc].getData();
            if (textbox_descrizioneparte===''){
                flagerr=1;
                aggiungiErroreMSGError('<p><strong>La descrizione del Dato '+$(this).find('span.numero-parte').html()+' di contesto della storia è obbligatoria</strong></p>');
            }
        })
    }
    if(flagerr==1)return false;
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
        $(this).find('.msgContainerError').prepend('<p><strong>ATTENZIONE! Ci sono degli errori all\'interno dei dati di contesto.</strong></p>')
        if($(this).hasClass('d-none'))$(this).removeClass('d-none');
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
        text: "Sei sicuro di voler eliminare questo contenuto?",
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