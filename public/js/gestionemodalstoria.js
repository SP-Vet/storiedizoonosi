$(document).ready(function(){
    //evento click snippet
    $('.snippet-link').click(function(){
        let snid=$(this).attr("snippet");
        getsnippet(snid);
    })
})
$.idstoria=$('#storiaid').val();
$.idzoonosi=$('#zoonosiid').val();

/*estrazione dati di contesto*/
function getdaticontesto(){
    $.ajax({
        type:'POST',
        url:"/ajx-getdaticontesto",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
        data:{sid:$.idstoria},
        success:function(data){
            if(data.error){
                alert(data.message);
            }else{
                //preparazione dati estratti + apertura modal
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

/*estrazione reviews*/
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
                //preparazione dati estratti + apertura modal
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

/*estrazione snippets*/
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
                //preparazione dati estratti + apertura modal
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
