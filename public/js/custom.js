/* START toggle menu */
/*window.addEventListener('DOMContentLoaded', event => {
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // commentate le 3 righe sottostanti per non mantenere la scelta sul toggle del menu in caso di refresh della pagina
        if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
             document.body.classList.toggle('sb-sidenav-toggled');
        }


        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }
});*/
/* END toggle menu */

var isMobile = false; //initiate as false
// device detection
if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
    || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) { 
    isMobile = true;
   
}

$(document).ready(function(){
   
    /*https://bootstrap-datepicker.readthedocs.io/en/latest/*/
    $('.date-picker').datepicker({
        rtl: true,
        autoclose: true,
        clearBtn: true,
        language: 'it',
        weekStart: 2,
        /*format: "dd/mm/yyyy",*/
        format: "dd/mm/yyyy",
        showWeekDays: false
    });
  

    
    $('#catzoonosi').change(function(){
        let idzon=$(this).val();
        if($.isNumeric(idzon)){
            $('div#zoo'+idzon).show();
            $('div.containerzoodash').not('#zoo'+idzon).hide();
        }else{
            $('div.containerzoodash').show();
        }
    });

    $('.switch-lang').click(function(){
        let ln=$(this).attr("data-language");
        $(this).closest('.container-form-sub').hide();
        $('.container-form-sub-'+ln).show();
    });


    //rimozione della classe show per far comprimere il blocco degli approfondimenti
    if($(window).width() < 700){
        $('.collapse-approfondimenti').removeClass("show");
        $('.collapse-approfondimenti').addClass("collapse");
    }
    initRispondiApprofondimento();
    initEvidenziatesto();

    if(!isMobile){
        $('.testo-blocco').mouseup(function() {
            let blocco=$(this).closest('.accordion-item');
            cancellaSelezione();
            let txt_selected='';
            //txt_selected=getSelectedText();
            txt_selected=$.trim(getSelectionHtml());
            if(txt_selected!==''){
                //alert(txt_selected);
                $(blocco).find('.testo-approfondimento').html(txt_selected);
                apriSuggerimentoSelezione(blocco);
            }else{
                chiudiSuggerimentoSelezione();
            }
        });
    }else{
        $('.btn-use-selection').removeClass('d-none');
        $('.btn-use-selection').click(function(){
            let blocco=$(this).closest('.accordion-item');
            cancellaSelezione();
            let txt_selected='';
            txt_selected=getSelectedText();
            if(txt_selected!==''){
                //alert(txt_selected);
                $(blocco).find('.testo-approfondimento').html(txt_selected);
                
                $('.collapse-approfondimenti').removeClass("collapse");
                $('.collapse-approfondimenti').addClass("show");
                apriSuggerimentoSelezione(blocco);
            }else{
                chiudiSuggerimentoSelezione();
            }
        })
        /*START DA TESTARE FUNZIONAMENTO SU MOBILE */
        /*$('.testo-blocco').on("select", function() {
            let txt_selected='';
            txt_selected=getSelectedText();
            if(txt_selected!==''){
               alert(txt_selected); 
            }

        })*/
        /*noContext = document.getElementsByClassName('testo-blocco');
        for (var i = 0 ; i < noContext.length; i++) {

            $(noContext[i]).mouseup(function(){
                alert("AS");
            })
        }*/
        /*$('.testo-blocco').on("touchend", function(e) {
            e.preventDefault();
            let blocco=$(this).closest('.accordion-item');
            cancellaSelezione();
            let txt_selected='';
            txt_selected=getSelectedText();
            console.log(txt_selected);
            if(txt_selected!==''){
                //alert(txt_selected);
                $(blocco).find('.testo-approfondimento').html(txt_selected);
                apriSuggerimentoSelezione(blocco);
            }else{
                chiudiSuggerimentoSelezione();
            }
        });*/
        /*END DA TESTARE FUNZIONAMENTO SU MOBILE */
    }

    


    $('.elimina-suggerimento').click(function(){
        let blocco=$(this).closest('.accordion-item');
        cancellaSelezione();
        chiudiSuggerimentoSelezione();
    })

    $('.elimina-risposta').click(function(){
        let blocco=$(this).closest('.accordion-item');
        cancellaRisposta(blocco);
        chiudiSuggerimentoRisposta();
    })



    $('.invia-approfondimento').click(function(){
        //alert("La funzione non è ancora disponibile.");return;
        let blocco=$(this).closest('.accordion-item');
        let approfondimento=$(blocco).find('textarea.messaggio-approfondimento').val();
      
        if($.trim(approfondimento)!==''){
            Swal.fire({
                title: "Conferma l'annotazione",
                text: "Il tuo approfondimento verrà controllato prima di essere reso pubblico.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                confirmButtonText: 'Invia',
                cancelButtonText: 'Annulla',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    let idcomrisp=$(blocco).find('.testo-risposta').attr("idcomrisp");
                    let testoapprofondimento=$(blocco).find('.testo-approfondimento').html();
                    let sfid=$(blocco).find('.testo-blocco').attr('idblocco');
                    //invio commento tramite ajax
                    return  $.ajax({
                        type:'POST',
                        url:"/ajx-putintegrationmessage",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                        data:{idcomrisp:idcomrisp,testoapprofondimento:testoapprofondimento,sfid:sfid,approfondimento:approfondimento},
                        success:function(data){return data;},
                        error: function(error) {console.log(error);},
                        beforeSend: function() {},
                    });
                    
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    if(!result.value.error){
                        cancellaSelezione();
                        cancellaRisposta($(blocco));
                        chiudiSuggerimentoSelezione();
                        chiudiSuggerimentoRisposta();
                        pulisciTestoApprofondimento(blocco);
                        Swal.fire({
                            icon: 'success',
                            title: 'GRAZIE',
                            text: result.value.message,
                            confirmButtonText: 'Chiudi',
                            confirmButtonColor: "#198754"
                        })
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
          
        }
    })

    $('.toggle h3').on('click', function(e){
        var answer = $(this).next('.toggle-info');
        
        if(!$(answer).is(":visible")) {
            $(this).parent().addClass('open');
        } else {
            $(this).parent().removeClass('open');
        }
        $(answer).slideToggle(300);
    });
    
    $('.conferma-elimina').click(function(e){
        e.preventDefault();
        let sezione=$(this).attr("sezione");
        let idval=$(this).attr("idvalore");
        Swal.fire({
                title: "Conferma eliminazione",
                text: "Sei sicuro di voler eliminare questo contenuto?. L'eliminazione non sarà reversibile...",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                confirmButtonText: 'Elimina',
                cancelButtonText: 'Annulla',
                showLoaderOnConfirm: true,
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    //redirect a cancellazione
                     window.location = '/admin/cancella'+sezione+'/'+idval;
                }
            })
    })

})
function showloader(title,text){
    if(title!='')$('.custom-loader-title').html(title);
    if(text!='')$('.custom-loader-text').html(text);
    $('div.custom-loader-mask').show();
}
function hideloader(){
    $('div.custom-loader-mask').hide();
    $('.custom-loader-title').html('Attendere');
    $('.custom-loader-text').html('');
}


function ricalcolaNumApprBlocchi(){
    let numappr=0;
    $('.approfondimenti-blocco').each(function(){
        numappr=0;
        numappr=$(this).find('.containergenerale-approfondimento').length;
        $(this).find('span.tot-approfondimenti').html(numappr);
    })
}

function initRispondiApprofondimento(bloccco=""){
    if(typeof blocco !== 'undefined' && blocco !== null && blocco!=""){
        $(blocco).find('.rispondi-approfondimento').click(function(e){
            e.preventDefault();
            preparaRispondi($(this));
        })
    }else{
        $('.rispondi-approfondimento').click(function(e){
            e.preventDefault();
            preparaRispondi($(this));
        })
    }
}

function preparaRispondi(obj){
    let idcom=$(obj).attr("idcom");
    let idappro_container=$(obj).closest('.containergenerale-approfondimento').find(".approfondimento-commento").attr("idcom");
    if(idcom!==idappro_container || idcom==0 || typeof idcom == 'undefined' || idcom=="")return false;

    let blocco=$(obj).closest('.accordion-item');       
    apriSuggerimentoRisposta(blocco);
    setIdRisposta(blocco,idcom);

}

function setIdRisposta(blocco,idcom){
    $(blocco).find(".testo-risposta").attr("idcomrisp",idcom);
}

function initEvidenziatesto(blocco,bl){
    if(typeof blocco !== 'undefined' && blocco !== null){
        $(blocco).find('.approfondimento-commento').unbind( "click" );
        $(blocco).find('.approfondimento-commento').click(function(){
            evidenziaEventoJQ(this,bl);    
            selezionaCommento($(this));
        })
    }else{
        $(document).find('.approfondimento-commento').click(function(){
            let el='';
            el=$(this).closest('.accordion-item');
            evidenziaEventoJQ(this,el);
            selezionaCommento($(this));
        })
    }
}

function selezionaCommento(commento){
    $(commento).closest('.approfondimenti-blocco').find('.commento-select').removeClass('commento-select');
    $(commento).addClass('commento-select');
}
function replaceAllSpecialChars(str){
    let stringreplaced='';
    stringreplaced=str.replace("'",'&#39;');
    
    stringreplaced==stringreplaced.replace("à",'&agrave;');
    stringreplaced==stringreplaced.replace("À",'&Agrave;');
    stringreplaced==stringreplaced.replace("è",'&egrave;');
    stringreplaced==stringreplaced.replace("É",'&Egrave;');
    stringreplaced==stringreplaced.replace("ì",'&igrave;');
    stringreplaced==stringreplaced.replace("Ì",'&Igrave;');
    stringreplaced==stringreplaced.replace("ò",'&ograve;');
    stringreplaced==stringreplaced.replace("Ò",'&Ograve;');
    stringreplaced==stringreplaced.replace("ù",'&ugrave;');
    stringreplaced==stringreplaced.replace("Ù",'&Ugrave;');

    return stringreplaced;
}

function evidenziaEventoJQ(elemento,bl) {
    let textsearch=$(elemento).find('.riferimento-testo').val();
    let t=textsearch.replace(/££/g,'"'); 
    let replacedt=replaceAllSpecialChars(t);
    //console.log('t=>'+t);
    //console.log('rt=>'+replacedt);
    let numcom=$(elemento).attr('idcom');
    cancellaSelezioneBlocco(bl);
    if($.trim(t)!==''){
        let testob=$(bl).find('.testo-blocco');
        let idblo=$(bl).find('.testo-blocco').attr("idblocco");
        //console.log(textsearch.replace("\"","\""));
        if ($(bl).find('.testo-blocco:contains(\"'+stripHtml(t)+'\")').length > 0) {
            //evidenziazione testo selezionato
            let oldtesto=stripHtml(replaceAllSpecialChars($.testoBlo[idblo]));
            //let oldtesto=$.testoBlo[idblo];
            //console.log(stripHtml(replaceAllSpecialChars($.testoBlo[idblo])));
            if(typeof oldtesto!=='undefined' && oldtesto!==''){
                let txtmod=oldtesto.replace(replacedt, "<span class=\"bg-marker\">"+replacedt+"</span>"); 
                //console.log(txtmod);
                //let txtmod=oldtesto.replace(textsearch, "<span class=\"bg-marker\">"+textsearch+"</span>"); 
                $(testob).html(txtmod);
            }
        }    
    }
    
}

function stripHtml(html)
{
   let tmp = document.createElement("DIV");
   tmp.innerHTML = html;
   return tmp.textContent || tmp.innerText || "";
}

function cancellaSelezioneBlocco(bl){
    let idblo=$(bl).find('.testo-blocco').attr("idblocco");
    //console.log($.testoBlo[idblo]);
    if(typeof $.testoBlo[idblo]!== 'undefined' && $.testoBlo[idblo]!==''){
        //let oldtesto=$.testoBlo[idblo];
        let oldtesto=stripHtml(replaceAllSpecialChars($.testoBlo[idblo]));
        $(bl).find('.testo-blocco').html(oldtesto);
    }
}

function getSelectedText() {
    if (window.getSelection) {
        //console.log(window.getSelection().toString());
        return window.getSelection().toString();
    } else if (document.selection) {
        return document.selection.createRange().text;
    }
    return '';
}

function getSelectionHtml() {
    var html = "";
    if (typeof window.getSelection != "undefined") {
        var sel = window.getSelection();
        if (sel.rangeCount) {
            var container = document.createElement("div");
            for (var i = 0, len = sel.rangeCount; i < len; ++i) {
                container.appendChild(sel.getRangeAt(i).cloneContents());
            }
            html = container.innerHTML;
        }
    } else if (typeof document.selection != "undefined") {
        if (document.selection.type == "Text") {
            html = document.selection.createRange().htmlText;
        }
    }
    return html;
}

function cancellaSelezione(){$('.testo-approfondimento').html('');}
function cancellaRisposta(blocco){
    $(blocco).find('.testo-risposta').html('');
    $(blocco).find('.testo-risposta').attr("idcomrisp","");
}

function chiudiSuggerimentoSelezione(){$('.container-testo-approfondimento').hide('slow');}
function chiudiSuggerimentoRisposta(){$('.container-testo-risposta').hide('slow');}

function apriSuggerimentoSelezione(blocco){
    $(blocco).find('.container-testo-approfondimento').show('slow',function(){
            $(blocco).find('.messaggio-approfondimento').focus();
            //scroll al riepilogo della selezione e proposta di approfondimento
            $(window).scrollTop($(blocco).find('.inserisci-approfondimento').offset().top);
    });
}

function apriSuggerimentoRisposta(blocco){
    //
    let commento=$(blocco).find('.containergenerale-approfondimento p').html();
    $(blocco).find('.container-testo-risposta figcaption').html(commento);

	$(blocco).find('.container-testo-risposta').show('slow',function(){
		$(blocco).find('.messaggio-approfondimento').focus();
		//scroll al riepilogo della selezione e proposta di approfondimento
		$(window).scrollTop($(blocco).find('.inserisci-approfondimento').offset().top);
	});
}

function pulisciTestoApprofondimento(blocco){$(blocco).find('.messaggio-approfondimento').val('');}

function convertToSlug(Text) {
  return Text.toLowerCase()
             .replace(/[^\w ]+/g, '')
             .replace(/ +/g, '-');
}