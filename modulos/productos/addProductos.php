<?php 
include("../../libreria/engine.php");
	
	
	if($_POST){

				if($_POST['id'] < 1){
					// crear un producto nuevo
						$sql="INSERT INTO productos 
							 (nombre, tipo, precio, detalle, capital, estado, existencia)
							 VALUES
							 (
							 '{$_POST['nombre']}',
							 '{$_POST['tipo']}',
							 '{$_POST['precio']}',
							 '{$_POST['detalles']}',
							 '{$_POST['capital']}',
							 '{$_POST['estado']}',
							 '{$_POST['existencia']}'
							 )";
						  mysqli_query($con,$sql);
					
					
					
					}else{
					// modificar el producto 	
					
					$sql="
						UPDATE productos set
						nombre  = '{$_POST['nombre']}',
						precio  = '{$_POST['precio']}',
						detalle = '{$_POST['detalles']}',
						capital = '{$_POST['capital']}',
						existencia = '{$_POST['existencia']}',
						estado  = '{$_POST['estado']}',
						tipo    = '{$_POST['tipo']}'
						
						where id = '{$_POST['id']}'";
                    
                    
						mysqli_query($con,$sql);		
				}

		
			echo "<script>
                parent.quitarModal();
            </script>
				 ";
		
			//print_r($_POST);
		exit();
		}
	
	
	
		$sql = "select * from productos where id = {$_GET['id']}";
        $rs  = mysqli_query($con,$sql);
        $prod = mysqli_fetch_assoc($rs);
	

?>



			<iframe name="ifm" style="display:none" id="ifm" width="100%" height="200"></iframe>


		<div class="row">

			<form target="ifm" action="modulos/productos/addProductos.php" method="post">
            
            	<div class="col-md-12">
                
                	<input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
                
                	Nombre
            		<input type="text" required title="Digite el nombre" placeholder="Nombre" name="nombre" class="form-control" value="<?php echo  $prod['nombre'] ?>">
                </div>
                
                <div class="col-md-6">
                	Precio
                	<input type="number" required name="precio" class="form-control" value="<?php echo  $prod['precio'] ?>">
                </div>
                
                <div class="col-md-6">
                	Capital
                	<input type="number" name="capital" class="form-control" value="<?php echo  $prod['capital'] ?>">
            	</div>
                
                <div class="col-md-6">
                    Existencia
                    <input type="number" required class="form-control" name="existencia" value="<?php echo  $prod['existencia'] ?>">
                </div>
                
                
                <div class="col-md-6">
                	Tipo
                    <select name="tipo" class="form-control">
                        <option value="1">Producto</option>
                        <option value="2" <?php echo ($prod['tipo'] ==2 )? "selected":"" ?> >Servicio</option>
                    </select>
                </div>
                <div class="col-md-12">
                	Detalles
                	<textarea name="detalles" class="form-control"><?php echo $prod['detalle'] ?></textarea>
                </div>
                
                <div class="col-md-12">
                    Estado
                	<select name="estado" class="form-control">
                    	<option value="1">Activo</option>
                        <option value="2" <?php  echo ($prod['tipo'] ==2 )? "selected":"" ?> >Inactivo</option>
                    </select>
                </div>
                
                <div class="col-md-12">
                	<br>
                 <input type="submit" value="Guardar" class="btn btn-primary btn-block">
                </div>        
                
                
            </form>
            
            
            </div>
            
            
            <script>
				function quitarModal(){
						
						$('#myModal').modal('hide');
						irA('modulos/productos/productos.php');
						
					
					}
			</script>
            