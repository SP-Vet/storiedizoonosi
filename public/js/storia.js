
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
    $('#titolo').blur(function(){
        let nome=$(this).val();
        $('#slug').val(convertToSlug(nome));
        checkSlug($('#sid').val());
    })
    $('#titolo').keyup(function(){
        let nome=$(this).val();
        $('#slug').val(convertToSlug(nome));
        checkSlug($('#sid').val());
    });
    $('textarea.descrizione-parte').each(function(){
        let id=$(this).attr('id');
        CKEDITOR.replace( id, {
            customConfig: '/js/ckeditor_configs/config_simple.js',
            filebrowserUploadUrl: rottaupload,
            filebrowserUploadMethod: 'form'
        });
    });
    
    /*$('textarea.testosnippet').each(function(){
        let id=$(this).attr('id');
        CKEDITOR.replace( id, {
            customConfig: '/js/ckeditor_configs/config_simple_100.js'
        });
    })*/
    
    $('textarea.testosnippet').each(function(){
        let idtextarea=$(this).attr("id");
        CKEDITOR.replace(idtextarea, {
            customConfig: '/js/ckeditor_configs/config_simple.js',
            filebrowserUploadUrl: rottaupload,
            filebrowserUploadMethod: 'form'
        });
    }) 
})


function checkSlug(sid){
    let id=0;
    if(typeof sid!=='undefined' && $.isNumeric(sid))
        id=sid;
    
    //ajax call to check existence of slugzoonosis excluding the zoonosis itself
    if($('#slug').val()!=='' && typeof $('#slug').val()!== 'undefined'){
        $.ajax({
            type:'POST',
            url:"/admin/ajx-checkslug",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
            data:{sid:id,slug:$('#slug').val()},
            success:function(data){
                if(data.error){
                    $('.messaggioslugerrore').html(data.message);
                    $('.messaggioslugerrore').removeClass('d-none');
                    $('.messaggioslugerrore').addClass('d-block');                    
                }
            },
            error: function(error) {console.log(error);},
            beforeSend: function() {
                //clear div message error slug
                if(!$('.messaggioslugerrore').hasClass('d-none')){
                    $('.messaggioslugerrore').html('');
                    $('.messaggioslugerrore').removeClass('d-block');
                    $('.messaggioslugerrore').addClass('d-none');
                }
            },
        });
    }  
}

function getphaseintegrations(sfid){
    $.ajax({
        type:'POST',
        url:"/admin/ajx-getphaseintegrations",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
        data:{sfid:sfid},
        success:function(data){
            if(data.error){
                alert(data.message);
            }else{
                //extracted data preparation + modal opening
                let corpohtml='';
                if(data.integrazioni.length>0){
                    for(let i in data.integrazioni){
                        corpohtml+='<div class="approfondimento-commento">';
                            corpohtml+='ID integrazione: <i>'+data.integrazioni[i].said+'</i><br />';
                            if(data.integrazioni[i].said_genitore!=null)
                                corpohtml+='ID integrazione padre: <i>'+data.integrazioni[i].said_genitore+'</i><br />';
                            if(data.integrazioni[i].testoselezionato!=null)
                                corpohtml+='Testo selezionato: <span class="bg-marker"><i>'+data.integrazioni[i].testoselezionato+'</i></span><br />';
                            corpohtml+='<img src="/images/avatar.png" class="me-2 mt-1" width="40" height="40">';
                            corpohtml+='<strong class="d-inline-flex text-gray-dark d-block align-self-end">'+data.integrazioni[i].nomeutente+'<span class="">&nbsp;('+data.integrazioni[i].data_pubblicazione_format+')</span></strong>';
                                corpohtml+='<p class="pb-1 mb-0 small lh-sm">'+data.integrazioni[i].testoapprofondimento+'</p>';
                            corpohtml+='</div>';
                            corpohtml+='<hr>';
                            
                        corpohtml+='</div>';
                    }
                }else{
                    corpohtml+='<h3>Non ci sono dati biografici disponibili per questa storia.</h3>';
                }
                $('div#elenco-integrazioni').html(corpohtml);   
                $('div#elenco-integrazioni').find('.collapse').collapse();
            }
        },
        error: function(error) {
            console.log(error);
        },
        beforeSend(){
            $('div#elenco-integrazioni').html('');
        }
    });
} 


