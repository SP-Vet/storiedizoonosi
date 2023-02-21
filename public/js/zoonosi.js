
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
    $('#nomezoonosi').blur(function(){
        let nome=$(this).val();
        $('#slugzoonosi').val(convertToSlug(nome));
        checkSlugZoonosi($('#zid').val());
    })
    $('#nomezoonosi').keyup(function(){
        let nome=$(this).val();
        $('#slugzoonosi').val(convertToSlug(nome));
        checkSlugZoonosi($('#zid').val());
    });
    
    /*ClassicEditor
    .create( document.querySelector( '#descrizione' ), {
        toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote','|','sourceEditing' ],
        heading: {
            options: [
                { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' }
            ]
        }
    } )
    .catch( error => {
        console.log( error );
    } );*/
   
    $('.upload-review').submit(function(e) {
        e.preventDefault();
        let file = $(this).find('input[name="review"]')[0].files[0];
        let zid = $(this).find('input[name="review"]').attr("zoonosi");
        let formData = new FormData(this);
        formData.append('review', file);
        formData.append('zid', zid);
        if(typeof(file)!=='undefined'){
            $.ajax({
                type:'POST',
                url:"/admin/ajx-uploadreview",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                data: formData,
                processData: false,
                contentType: false,
                cache:false,
                dataType: 'json',
                success:function(data){
                    if(!data.error){
                        Swal.fire({
                            icon: 'success',
                            title: 'GRAZIE',
                            text: data.message,
                            confirmButtonText: 'Chiudi',
                            confirmButtonColor: "#198754"
                        }).then((result) => {
                           location.reload();
                        })
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Errore...',
                            text: data.message,
                            confirmButtonText: 'Chiudi',
                            confirmButtonColor: "#FF5733"
                        })
                    }
                },
                error: function(error) {console.log(error);},
                beforeSend: function() {},
            });
        }
    });
})

function removeReview(srid){
    if($.isNumeric(srid)){
        Swal.fire({
            title: "Conferma l'eliminazione",
            text: "Sei sicuro di voler eliminare questa review? Il processo non sarà reversibile...",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            confirmButtonText: 'Elimina Review',
            cancelButtonText: 'Annulla',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return  $.ajax({
                    type:'POST',
                    url:"/admin/ajx-removereview",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                    data:{srid:srid},
                    success:function(data){return data;},
                    error: function(error) {console.log(error);},
                    beforeSend: function() {},
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                if(!result.value.error){  
                    Swal.fire({
                        icon: 'success',
                        title: 'GRAZIE',
                        text: result.value.message,
                        confirmButtonText: 'Chiudi',
                        confirmButtonColor: "#198754"
                    }).then((result) => {
                       location.reload();
                    })
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Errore...',
                        text: result.value.message,
                        confirmButtonText: 'Chiudi',
                        confirmButtonColor: "#FF5733"
                    })
                }
            }
        })
    }
}

function checkSlugZoonosi(zid){
    let id=0;
    if(typeof zid!=='undefined' && $.isNumeric(zid))
        id=zid;
    //ajax call to check existence of slugzoonosis excluding the zoonosis itself
    if($('#slugzoonosi').val()!=='' && typeof $('#slugzoonosi').val()!== 'undefined'){
        $.ajax({
            type:'POST',
            url:"/admin/ajx-checkslugzoonosi",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
            data:{zid:id,slug:$('#slugzoonosi').val()},
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
