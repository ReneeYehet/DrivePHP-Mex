<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	</head>

	<body>
		<div class="content">
			<div class="bar">
			  <div class="circle"></div>
			  <p id="load">Agregando<br>datos</p>
			</div>
		</div>
		
		<script type="text/javascript">
			$(document).ready(function(){
				const url = window.location.search;
				const urlParams = new URLSearchParams(url);
				const id_cot = urlParams.get('id');
				var ruta = "http://mexdrive.gearhostpreview.com/php/insert-items.php";
				const tipo = urlParams.get('tipo');

			    $.ajax({type:'POST',        // call php 
			        url: ruta,
			        data: ({
			        	id :  id_cot, 
			        	tipo : tipo
			        }),
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
			    console.log(id_cot);
			    console.log(tipo);
			});
		</script>

		
		<div class="error_msg" style="width: 100%; text-align: center;"> </div>
		<div class="footer">
			<img src="img/logo_ECN.png" style="width: 300;">
		</div>
	</body>
</html>
