<?php 
include("../../libreria/engine.php");
?>
<!-- css y js de dataTable buscador -->
<link rel="stylesheet" href="modulos/factura/dataTables.css">
<script src="modulos/factura/dataTables.js"></script>
<div class="row">
    	<div class="col-md-12">
        	<div class="panel panel-body">
            <?php 
			 $sc="";
			 $sql = "select * from productos";
			  $rs = mysqli_query($con,$sql);
			  $td="";
			  while($fila = mysqli_fetch_assoc($rs)){
			
						$itbis = $fila['precio']*0.18;
						$precio = $fila['precio']+$itbis;
						$btn = ($fila['existencia'] < 1) ?"No Disponible":"<button type='button' class='btn btn-success btn-xs' onclick=\"agregar({$fila['id']},this);\">Agregar</button>";
						
						$td .="
							<tr id='producto{$fila['id']}'>
								<td id='nombre{$fila['id']}'><input type='hidden' id='total{$fila['id']}' value='{$fila['precio']}'>{$fila['nombre']}</td>
								<td><input id='cantidad{$fila['id']}' class='form-control' onkeyup='carculando({$fila['id']})'  style='width:50px' value='0'></td>
								
								<td><input id='existencia{$fila['id']}' data='{$fila['existencia']}' value='{$fila['existencia']}' class='form-control' readonly style='width:50px' value='1'></td>
								
								<td><input id='subtotal{$fila['id']}' class='form-control'style='width:60px' value='{$fila['precio']}' readonly></td>
								<td>
										<table>
										<tr>
											<td>
					<input type='checkbox' checked id='itbis{$fila['id']}' value='1'  onclick='carculando({$fila['id']})' >&nbsp;
											</td>
											<td>
						<input class='form-control'style='width:60px' id='impuesto{$fila['id']}' value=''  readonly></td> 
											</td>
										</tr>
										</table>	
								<td id='totalAll{$fila['id']}'>".number_format($fila['precio'],0)."</td>
								<td>{$btn}</td>
							</tr>
							
						
							 ";
				   			$sc .="carculando({$fila['id']});";
				      }
				$td .= " <script>
               	   $(document).ready(function() {
                    $('#produc').dataTable({
							'paging':   false,
							'info':     false
						});
             	   });
				   </script>
					";
			
			?>
        	<table class="table" id="produc">
            	<thead>	
                    <tr>
                        <td>Producto</td>
                        <td>Cantidad</td>
                        <td>Existencia</td>
                        <td>Subtotal</td>
                        <td>Impuesto</td>
                        <td>Total</td>
                        <td>--</td> 
                    </tr>
                </thead>
                <tbody>
                	<?php echo $td ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
 </div>
 
 <input type="hidden" id="mAbierto" value="1">
 <script>
	
	//calculo de los productos 
 	function carculando(id){ 
			existencia  = parseInt($("#existencia"+id).attr('data'))+0;
			cantidad  = (parseInt($("#cantidad"+id).val()) >0)?parseInt( $("#cantidad"+id).val())+0:0;
			
			if( cantidad > existencia || cantidad ==0 ){
				$("#cantidad"+id).css("border","1px solid red");
				$("#cantidad"+id).focus();
				$("#cantidad"+id).val('');
				//carculando(id)
				return false;
				}
			$("#cantidad"+id).css("border","1px solid #ccc");
			$("#existencia"+id).val(existencia - cantidad);
			
			total     = $("#total"+id).val();
			subtotal  = $("#subtotal"+id).val(); 
			
			$("#impuesto"+id).val(0)
			totalAll = ( total * cantidad )  ;
			tibis    = ( totalAll * 0.18 )  ;
			
			$("#subtotal"+id).val(totalAll);
			
			if($("#itbis"+id).prop( "checked")){
				 $("#impuesto"+id).val(tibis)
				totalAll = totalAll+tibis;
				}
			
			$("#totalAll"+id).html(totalAll); 			
			
		
		}
 		<?php 
			echo $sc;
		?>
 </script>
 