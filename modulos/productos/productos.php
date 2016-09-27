<?php 
include('../../libreria/engine.php');

?>
<h2>Productos</h2>
<hr>

<button class="btn btn-success" onClick="MM('modulos/productos/addProductos.php?id=0')"> Nuevo Producto</button>
<br>
<br>
<table class="table">
	
    <tr>
    	<th>Producto</th>
        <th>Precio</th>
        <th>Detalle</th>
        <th>Existenca</th>
        <th>--</th>
    </tr>

	<?php 
		$sql = "select * from productos";
		$rs = mysqli_query($con,$sql);
		while($fila = mysqli_fetch_assoc($rs)){
			
			$color = ($fila['estado']==0)?"pink":"";
			
			echo "
			<tr>
				<td>{$fila['nombre']}</td>
				<td>".number_format($fila['precio'],2)."</td>
				<td>{$fila['detalle']}</td>
				<td>{$fila['existencia']}</td>
				<td>
	<button class='btn btn-primary' onClick=\"  MM('modulos/productos/addProductos.php?id={$fila['id']}')  \"  >Edit</button>
				</td>
			</tr>
			";
	
		}
	
	
	?>


</table>