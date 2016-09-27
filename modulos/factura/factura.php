<h2 style="margin-top:10px;">Facturas</h2>
<hr style="margin-top:2px;">  

<?php 
include("../../libreria/engine.php");
date_default_timezone_set('America/La_Paz');

?>
 <div class="row">
    	
    	<div class="col-md-12">
        <br>
        	<button onClick="irA('modulos/factura/addFactura.php?id=0')" class="btn btn-success">Nueva Factura</button>
        	<div class="panel panel-body">
            <?php 
			$td="";
		$sql = "select f.fecha,c.nombre,f.total,f.pago,f.id,c.rnc,c.cedula,f.codigo,f.estado from factura f 
					left join clientes c
					on(f.id_cliente = c.id)
					";
			$rs = mysqli_query($con,$sql);
			while($fila = mysqli_fetch_assoc($rs)){
			
						$tipo= ($fila['codigo']==1)?"FACTURA":"COTIZACION";
						$balance = $fila['total']-$fila['pago'];
						$estado = ($balance > 1 )?"#f7e05d":"";
						
						
						
						if($balance > 1){
							$stad = "<strong>ABONADO</strong>";
							}
							
						if($balance < 1){
							$stad = "<strong>PAGADO</strong>";
							$balance="0.00";
							}
						
						 $estado= ($fila['estado']==0)?"pink":$estado;
						
						
						$td .="
							<tr style='background:{$estado}'>
								<td>{$fila['id']}</td>
								<td>{$fila['nombre']}</td>
								<td>{$fila['rnc']}</td>
								<td>".date("m-d-Y",strtotime($fila['fecha']))."</td>
								<td>{$tipo}</td>
								<td>".number_format($fila['total']+0,2)."</td>
								<td>".number_format($fila['pago']+0,2)."</td>
								<td>".number_format($balance +0,2)."</td>
								<td>{$stad}</td>
								<td><button type='button' class='btn btn-xs btn-primary fa fa-pencil' onclick=\"irA('modulos/factura/addFactura.php?id={$fila['id']}&ifm')\"></button>
								<button class='btn btn-xs btn-info fa fa-print' onClick=\" printPage('factura/index.php?id={$fila['id']}') \"></button>
								
								</td> 
							</tr>
							
						
							 ";
				   
				      }
			$td .= " <script>
               	   $(document).ready(function() {
                    $('#factu').dataTable({
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

        	<table class="table" id="factu">
            	<thead>	
                    <tr>
                    	<td>Codigo</td>
                        <td>Cliente</td>
                        <td>Cedula</td>
                        <td>Fecha</td>
                        <td>Tipo</td>
                        <td>Total</td>
                        <td>Pago</td>
                        <td>Balance</td>
                        <td>Estado</td>
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
 <script>
 	//imprimir factuara
	
	<?php 
	echo (isset($_GET['id']))?"printPage('factura/index.php?id={$_GET['id']}')":"";
	?>
	
	
	function setPrint () {
	  this.contentWindow.__container__ = this;
	  this.contentWindow.onbeforeunload = closePrint;
	  this.contentWindow.onafterprint = closePrint;
	  this.contentWindow.focus(); // Required for IE
	  this.contentWindow.print();
	}
	function printPage (sURL) {
	  var oHiddFrame = document.createElement("iframe");
	  oHiddFrame.onload = setPrint;
	  oHiddFrame.style.visibility = "hidden";
	  oHiddFrame.style.position = "fixed";
	  oHiddFrame.style.right = "0";
	  oHiddFrame.style.bottom = "0";
	  oHiddFrame.src = sURL;
	  document.body.appendChild(oHiddFrame);
	}
	function closePrint () {
  	  document.body.removeChild(this.__container__);
	}
 </script>