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
    //click snippet event
    $('.snippet-link').click(function(){
        let snid=$(this).attr("snippet");
        getsnippet(snid);
    })
})
$.idstoria=$('#storiaid').val();
$.idzoonosi=$('#zoonosiid').val();

/*context data extraction*/
function getdaticontesto(){
    $.ajax({
        type:'POST',
        url:"/ajx-getcontextdata",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
        data:{sid:$.idstoria},
        success:function(data){
            if(data.error){
                alert(data.message);
            }else{
                //extracted data preparation + modal opening
                let corpohtml='';
                if(data.quesiti.length>0){
                    for(let i in data.quesiti){
                        corpohtml+='<div class="p-1">';
                        corpohtml+='<h3 class="bg-success">\n\
                                    <a class="btn text-center text-white w-100" data-bs-toggle="collapse" href="#collapseExample'+i+'" role="button" aria-expanded="false" aria-controls="collapseExample'+i+'">\n\
                                    '+data.quesiti[i].domanda+'</a></h3>';
                        corpohtml+='<div class="collapse" id="collapseExample'+i+'">';
                        corpohtml+=data.quesiti[i].risposta;
                        corpohtml+='</div>';
                        corpohtml+='</div>';
                    }
                }else{
                    corpohtml+='<h3>Non ci sono dati di contesto disponibili per questa storia.</h3>';
                }
                $('div#questions').html(corpohtml);   
                $('div#questions').find('.collapse').collapse();
            }
        },
        error: function(error) {
            console.log(error);
        },
        beforeSend(){
            $('div#quesiti').html('');
        }
    });
} 

/*extraction reviews*/
function getreview(){
     $.ajax({
        type:'POST',
        url:"/ajx-getreview",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
        data:{zid:$.idzoonosi},
        success:function(data){
            if(data.error){
                alert(data.message);
            }else{
                //extracted data preparation + modal opening
                let corpohtml='';
                if(data.reviews.length>0){
                    corpohtml+='<ul>';
                    for(let i in data.reviews){
                        corpohtml+='<li>\n\
                                    <a href="'+data.urldown+'"  target="_blank" class="text-decoration-none" title="'+data.reviews[i].titolo_visualizzato+'">\n\
                                    <img src="/images/pdf-icon.png" width="35" />'+data.reviews[i].titolo_visualizzato+'<strong></strong></a>\n\
                            </li>';
                    }
                     corpohtml+='</ul>';
                }else{
                    corpohtml+='<h3>Non ci sono reviews disponibili per questa zoonosi.</h3>';
                }
                $('div#modalReview .modal-body').html(corpohtml);   
            }
        },
        error: function(error) {
            console.log(error);
        },
        beforeSend(){
            $('div#modalReview .modal-body').html('');
        }
    });
}

/*snippet extraction*/
function getsnippet(snid){
    $.ajax({
        type:'POST',
        url:"/ajx-getsnippet",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
        data:{snid:snid},
        success:function(data){
            if(data.error){
                alert(data.message);
            }else{
                //extracted data preparation + modal opening
                let corpo='';
                let titolo='Snippets';
                if(data.snippet){
                    titolo=data.snippet.titolo;
                    corpo+=data.snippet.testo;
                }else{
                    corpo+='<h3>Non ci sono dati di snippet disponibili per la voce selezionata.</h3>';
                }
                $('div#modalProbResp div.modal-body').html(corpo); 
                $('div#modalProbResp div.modal-header h4').html(titolo); 
            }
        },
        error: function(error) {
            console.log(error);
        },
        beforeSend(){
            $('div#modalProbResp div.modal-header h4').html('Snippets'); 
            $('div#modalProbResp div.modal-body').html(''); 
        }
    });
}
