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
   
    $('a.pubblica-storia').click(function(e){
        e.preventDefault();
        let idstoria=$(this).attr('idstoria');      
        
        Swal.fire({
            title: "Conferma la pubblicazione",
            text: "Sei sicuro di voler pubblicare la storia? Questo sarà poi visualizzabile sul portale...",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            confirmButtonText: 'Conferma',
            cancelButtonText: 'Annulla',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                //invio commento tramite ajax
                return  $.ajax({
                    type:'POST',
                    url:"/admin/ajx-publishstory",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                    data:{sid:idstoria},
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
                    })
                    location.reload();
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Errore...',
                        text: result.value.message,
                        confirmButtonText: 'Chiudi',
                        confirmButtonColor: "#FF5733"
                        /*footer: '<a href="">Why do I have this issue?</a>'*/
                    })
                }
            }
        })
    })

   $('a.pubblica-approfondimento').click(function(e){
        e.preventDefault();
        let idapprofondimento=$(this).attr('idapprofondimento');      
        
        Swal.fire({
            title: "Conferma la pubblicazione",
            text: "Sei sicuro di voler pubblicare l'approfondimento? Questo sarà poi visualizzabile sul portale...",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            confirmButtonText: 'Conferma',
            cancelButtonText: 'Annulla',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                //invio commento tramite ajax
                return  $.ajax({
                    type:'POST',
                    url:"/admin/ajx-publishintegrations",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                    data:{said:idapprofondimento},
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
                    })
                    location.reload();
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Errore...',
                        text: result.value.message,
                        confirmButtonText: 'Chiudi',
                        confirmButtonColor: "#FF5733"
                        /*footer: '<a href="">Why do I have this issue?</a>'*/
                    })
                }
            }
        })
    })
})

