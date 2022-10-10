$(document).ready(function(){
   (function () {
    'use strict'
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                let title='';
                let text='';
                if($(form).find('button[type="submit"].btn-showloader')){
                    title=$('button[type="submit"]').attr("title-loader");
                    text=$('button[type="submit"]').attr("text-loader");
                };
                showloader(title,text);
                svuotaContainerMSGError();
                nascondiContainerMSGError();
                
                if (!form.checkValidity() || !checkValidityCKEDITORDaticontesto()){
                    event.preventDefault();
                    event.stopPropagation();
                    hideloader();
                    visualizzaContainerMSGError();
                    window.scrollTo(0, 0);
                }

                form.classList.add('was-validated')   
            }, false)
        })
    })()
})

