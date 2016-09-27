<?php 
include("../../libreria/engine.php");
  
  
		  	$td="";
		  	$sql = "select * from clientes";
			$rs = mysqli_query($con,$sql);
			  $td="";
			while($fila = mysqli_fetch_assoc($rs)){
		 	
			
			$td.="
				   <tr onClick=\"  cliente({$fila['id']},'{$fila['nombre']}','{$fila['cedula']}','{$fila['telefono']}','{$fila['direccion']}','{$fila['rnc']}') \" >
                        <td> {$fila['nombre']} </td>
						<td> {$fila['cedula']} </td>
                        <td> {$fila['telefono']} </td>
						<td> {$fila['rnc']} </td>
                    </tr>";
		 
		  }
		  
			$td .= " <script>
               	   $(document).ready(function() {
                    $('#clie').dataTable({
							'paging':   false,
							'info':     false
						});
             	   });
				   </script>
					";
 ?>
 			
 <!-- css y js de dataTable buscador -->
<link rel="stylesheet" href="modulos/factura/dataTables.css">
<script src="modulos/factura/dataTables.js"></script>

    <div class="row ">
    <div class="col-lg-12"> 
    <button class="btn btn-success" onClick="newCliente()">Nuevo Cliente</button>
        <br><br>
    <div class="table-responsive">
    <table class="table " id="clie">
        <thead>
            <tr>
            <td>Nombre</td>
            <td>Cedula</td>
            <td>Telefono</td>
            <td>RNC</td>
            </tr>
        </thead>
        <tbody>
        <?php echo $td ?>
        </tbody>
    </table>
    </div>
    </div>
    </div>
 		
    <script>
        //para agregar los cliente
            function cliente(id,nombre,cedula,telefono,direccion,rnc){
                           $("#id_cliente").val(id);
                           $("#nombre").val(nombre);
                           $("#cedula").val(cedula);
                           $("#telefono").val(telefono);
                           $("#direccion").val(direccion);
                           $("#rnc").val(rnc);

                           $('#nombre').removeAttr('readonly');
                           $('#nombre').removeAttr('onclick');
                           $('#myModal').modal('hide');
                }


        function puntos(){
                if(localStorage.getItem("puntos")== "null"){
                    localStorage.setItem("puntos",100)
                }else{
                     x= parseInt(localStorage.getItem("puntos"));
                     x= x+100;
                     localStorage.setItem("puntos",x);
                }
                console.log(localStorage.getItem("login"))
            }


function newCliente(){
        $('#nombre').removeAttr('onclick');
        $('#nombre').removeAttr('readonly');
        $('#myModal').modal('hide');
        cliente(0,'','','','','');
        $('#nombre').focus();
    }


    </script>
