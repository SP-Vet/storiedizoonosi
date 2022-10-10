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