$(document).ready(function(){

	const url = window.location.search;
    const urlParams = new URLSearchParams(url);
    const id_cot = urlParams.get('id');
    const tipo = urlParams.get('tipo');

    CallPHP(id_cot, tipo);
});

function CallPHP(id_cot, tipo){
    
    var ruta = "files/insert-items.php";

    $.ajax({type:'POST',        // call php 
        url: ruta,
        data: ({
            id :  id_cot, 
            tipo : tipo
        }),
        cache: false,
        dataType: "json",
        crossDomain: true,
        success: function(insertar){
            $('.bar').css('display', 'none');
            if (insertar === "404") {
                $('.content').load('plantillas/error-page.php');
                $('.error_msg').append(insertar);
            }else{
                $('.content').load('plantillas/success-page.php');
                $('.error_msg').append(insertar);
            }
            
        },
    });
}