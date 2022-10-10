$(document).ready(function(){
    $('.switch-lang-label-registrazione').click(function(){
        let ln=$(this).attr("data-language");
        let lan=$(this).attr("data-actual-language");
        cambialinguaattuale(ln);
        cambiatitoloform(getitlelinguaform(ln));
        cambiapulsantelingua(lan);
        $('.label-nome').html(getlabelnome(ln)+' <span class="required">*</span>');
        $('.label-cognome').html(getlabelcognome(ln)+' <span class="required">*</span>');
        $('.label-codfis').html(getlabelcodfis(ln));
        $('#codfisHelpInline').html(getlabelcodfishelp(ln));
        $('.label-ripetiemail').html(getlabelripetiemail(ln)+' <span class="required">*</span>');
        $('.label-ripetipassword').html(getlabelripetipassword(ln)+' <span class="required">*</span>');
        $('.label-terminiecondizioni').html(getlabelterminiecondizioni(ln));
        $('.label-inviadati').html(getlabelinviadati(ln));
        $('.label-indietro').html(getlabelindietro(ln));         
        $('#passwordHelpInline').html(getlabelpasswordhelp(ln));
    });
});
        
function getlabelnome(codlingua){
    switch(codlingua){
        case 'EN':
            return 'Name';
            break;
        case 'IT':
            return 'Nome';
            break;
    }
}
function getlabelcognome(codlingua){
    switch(codlingua){
        case 'EN':
            return 'Surname';
            break;
        case 'IT':
            return 'Cognome';
            break;
    }
}
function getlabelcodfis(codlingua){
    switch(codlingua){
        case 'EN':
            return 'Economic Operator Registration and Identification';
            break;
        case 'IT':
            return 'Codice Fiscale';
            break;
    }
}
function getlabelcodfishelp(codlingua){
    switch(codlingua){
        case 'EN':
            return 'Useful in the case of future authentication via SPID';
            break;
        case 'IT':
            return 'Utile nel caso di autenticazione futura tramite SPID';
            break;
    }
}
function getlabelripetiemail(codlingua){
    switch(codlingua){
        case 'EN':
            return 'Repeat Email';
            break;
        case 'IT':
            return 'Ripeti Email';
            break;
    }
}
function getlabelripetipassword(codlingua){
    switch(codlingua){
        case 'EN':
            return 'Repeat Password';
            break;
        case 'IT':
            return 'Ripeti Password';
            break;
    }
}
function getlabelpasswordhelp(codlingua){
    switch(codlingua){
        case 'EN':
            return 'At least 8 characters, numbers, uppercase, lowercase and special characters';
            break;
        case 'IT':
            return 'Almeno 8 caratteri, numeri, maiuscole, minuscole e caratteri speciali';
            break;
    }
}
function getlabelterminiecondizioni(codlingua){
     switch(codlingua){
        case 'EN':
            return 'Accept <a href="#" onclick="apricondizioni();">the privacy policy</a>';
            break;
        case 'IT':
            return 'Accetta <a href="#" onclick="apricondizioni();">la privacy policy</a>';
            break;
    }

}
function getlabelinviadati(codlingua){
     switch(codlingua){
        case 'EN':
            return 'Send data';
            break;
        case 'IT':
            return 'Invia dati';
            break;
    }

}
function getlabelindietro(codlingua){
     switch(codlingua){
        case 'EN':
            return 'Back';
            break;
        case 'IT':
            return 'Indietro';
            break;
    }

}
function cambiatitoloform(testotitolo){
    $('.titoloform').html(testotitolo);
}
function cambiapulsantelingua(codlingua){
    $('.switch-lang-label-registrazione').attr('data-language',codlingua);
    $('.switch-lang-label-registrazione').attr('title',getitlelingua(codlingua));
    $('.switch-lang-label-registrazione img').attr('src','/images/bandiera_'+codlingua.toLowerCase()+'.png');
    $('.switch-lang-label-registrazione img').attr('title',getitlelingua(codlingua));
}
function cambialinguaattuale(codlingua){
    $('.switch-lang-label-registrazione').attr('data-actual-language',codlingua);
}
function getitlelinguaform(codlingua){
    switch(codlingua){
        case 'EN':
            return 'Request a new account by filling out the form below';
            break;
        case 'IT':
            return 'Richiedi un nuovo account compilando il form sottostante';
            break;
    }
}        
function getitlelingua(codlingua){
    switch(codlingua){
        case 'EN':
            return 'Switch language to English';
            break;
        case 'IT':
            return 'Cambia lingua in Italiano';
            break;
    }
}