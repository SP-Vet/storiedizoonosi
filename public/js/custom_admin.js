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

