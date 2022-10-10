/*l'indice di arrCom risulta essere l'id del commento*/ 
$.arrCom=[];
$.arrCom[1]="Non aveva mai avuto problemi in passato";

/*l'indice di testoBlo risulta essere l'id del blocco*/ 
$.testoBlo=[];
$.testoBlo[1]="<p>Sono stato contattato dalla proprietaria per la prima volta venerdì 12 giugno, verso l'ora di cena. La signora al telefono mi ha raccontato di essere tornata a casa da pochi minuti, quando ha notato che la sua gatta respirava male. Riferisce che l'animale era rimasto dentro casa per tutto il giorno e, a tale riguardo, percepisco la sua contrarietà del fatto che nessuno si fosse accorto di nulla fino a quel momento. Comunque, visto che un improvviso problema respiratorio nel gatto non è mai da sottovalutare, le ho dato appuntamento per quella sera stessa, verso le 21. Oltretutto in quel momento ero particolarmente sensibile alle tematiche sulle patologie respiratorie del gatto, dato che avevo perso da poco uno dei miei gatti a causa di un tumore ai polmoni, perciò quando ho ricevuto la telefonata della signora, quella sera, non ho perso tempo e sono corso in ambulatorio.<table class=\"table\" width=\"100%\"><thead><tr><th scope=\"col\">Colonna 1</th><th scope=\"col\">Colonna 2</th><th scope=\"col\">Colonna 3</th></tr></thead><tbody><tr><td>1</td><td>2</td><td>3</td></tr><tr><td>4</td><td>5</td><td>6</td></tr></tbody></table> Dall'anamnesi è emerso che la gatta aveva circa 2 anni, era femmina, regolarmente vaccinata, sverminata e sterilizzata. Non aveva mai avuto problemi in passato, era sempre stata in ottime condizioni fisiche e in buona salute. Viveva in casa e aveva accesso al giardino esterno, quindi faceva una vita sia indoor che outdoor. Una cosa che ho scoperto in seguito era che i proprietari avevano una <span class=\"snippet-link\" data-bs-toggle=\"modal\" data-bs-target=\"#modalProbResp\">Bat Box in giardino</span>, ovvero una di quelle cassette di accoglienza per pipistrelli, ma inizialmente non ne ero al corrente.</p>";

$(document).ready(function(){
    $('.video-play-1').click(function(){
        var iframe = $('.vimeo-iframe').get(0);
        var player = new Vimeo.Player(iframe);
        player.play();
        //$(this).closest('.accordion-item').find('.vp-controls button').click();
    })
})
