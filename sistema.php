<?php 

include ("libreria/engine.php");
paFuera();
date_default_timezone_set('America/La_PAZ');
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Sistema de Facturacion</title>
    
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    
</head>

<body>

    <div class="row">
        
        <div class="row hidden-xs hidden-sm" id="header">
            <div class="col-md-4" style="padding-left: 75px; font-size: 34px; font-family: avenir-medium;">
                mercadito
            </div>
            
            <div class="col-md-7" style="text-align:right; font-size:20px; margin-top:7px;">
            <?php echo date("D, M Y - h:i A")?>
            </div>
            <div>
            
            <div class="col-md-1">
                <button class="btn btn-danger" onClick="location.href='logout.php'" id="salir"><i class="fa fa-sign-out" aria-hidden="true"></i></button>
                </div>
            </div>
        
        </div>
        
        <!-- inicio del menu -->
        <div class="col-md-3">
         
            <div class="menu">
                <li class="enlacesMenus hidden-lg hidden-md text-center" onclick="menu()"><i class="fa fa-bars" style="float:left; font-size: 28px; margin-top:8px;" aria-hidden="true"></i> mercadito </li>
                <li class="enlacesMenu hidden-xs hidden-sm" onClick="window.location='./sistema.php'"><i class="fa fa-home" aria-hidden="true"></i> Inicio</li>
                <li class="enlacesMenu hidden-xs hidden-sm"  onClick="irA('modulos/productos/productos.php')"><i class="fa fa-cube" aria-hidden="true"></i> Productos</li>
                <li class="enlacesMenu hidden-xs hidden-sm"  onClick="irA('modulos/usuarios/Usuarios.php')"><i class="fa fa-user" aria-hidden="true"></i> Usuarios</li>
                <li class="enlacesMenu hidden-xs hidden-sm"  onClick="irA('modulos/factura/factura.php')"><i class="fa fa-archive" aria-hidden="true"></i> Facturas</li>
                
            </div>
            <div class="completamenu hidden-xs hidden-sm"> </div>
            
            
        </div>
        <!-- final del menu -->
        
        <!-- inicio del contenedor -->
        <div class="col-md-9">
            <div id="contenedor">
            <h2 style="margin-top:10px;">Inicio</h2>
            <hr style="margin-top:2px;">
            </div>
            
        </div>
        <!-- final del contenedor -->
        
    </div>

    <!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Introducir Datos al Sistema</h4>
      </div>
      
        <div class="modal-body" id="contenidoModal">
        
      
        </div>
  
    </div>
  </div>
</div>
    
</body>


</html>

<script>
    
  	function MM(ruta){
        
        $("#myModal").modal("show")
        cargarEn("contenidoModal",ruta);
    }
	function menu(){
		
		$(".enlacesMenu").toggleClass("hidden-xs");
		$(".enlacesMenu").toggleClass("hidden-sm");
		
		}
	
	//funcion de amadis
	function cargarEn(elemento,pagina){
			
			$("#"+elemento).html("<center> <img src='images/loading.gif' height='200'> </center>");
			
			$.get(pagina,function(data){
					$("#"+elemento).html(data);
				});
		
		}
	
	function irA(ruta){
		
			cargarEn("contenedor",ruta);
		
		}
	
	
	
</script>
