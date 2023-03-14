$(document).ready(function(){
    $('.conferma-resetpassword').click(function(e){
        e.preventDefault();
        let email=$(this).attr("email");
        let email_real=$(this).attr("email_real");
        let idadmin=$(this).attr("idadmin");
        Swal.fire({
            title: "Conferma reset passwrod",
            text: "Sei sicuro di voler resettare la password di questo amministratore?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            confirmButtonText: 'Reset password',
            cancelButtonText: 'Annulla',
            showLoaderOnConfirm: true,
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                //redirect a reset passwrod
                    window.location = '/admin/resetpasswordadmin/'+idadmin+'/'+email+'/'+email_real;
            }
        })
    })
})
