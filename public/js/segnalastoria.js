(function () {
    'use strict'
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')
  
    // Loop over them and prevent submission
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
            }

            form.classList.add('was-validated')
        }, false)
    })
})()

function apricondizioni() {
    $("#modalTerminicondizioni").modal('show');
}
var flagletto=0;
$(document).ready(function(){
    $('#presovisionetermini').click(function(){
        flagletto=1;
    });

    $('#terminiecondizioni').click(function(e){
        if(flagletto!==1){
            e.preventDefault();
            $("#modalTerminicondizioni").modal('show');
        }
    })
    $('#terminiecondizioni_en').click(function(e){
        if(flagletto!==1){
            e.preventDefault();
            $("#modalTerminicondizioni").modal('show');
        }
    })

})