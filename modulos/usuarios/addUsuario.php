<?php 
include("../../libreria/engine.php");

	
	if($_POST){
		
		//si el user es nuevo 
			if($_POST['Id'] < 1){
				//Creamos nuevo user 
					$sql = "INSERT INTO `usuarios` 
					(`id`, `usuario`, `nombre`, `email`, `clave`, `estado`)
					VALUES 
					( NULL,
					 '{$_POST['Usuario']}',
					 '{$_POST['Nombre']}',
					 '{$_POST['Email']}',
					 '".md5($_POST['Clave'])."',
					 '{$_POST['Estado']}'
					 );";	
					mysqli_query($con,$sql);	
			
				}else{
				// Actulizamos los datos del user 
				
					$clave = (strlen($_POST['Clave']) > 2 )?",  clave = '".md5($_POST['Clave'])."' ": "";
				
				$sql ="UPDATE `usuarios` SET 
                `usuario` = '{$_POST['Usuario']}',
                `nombre` = '{$_POST['Nombre']}',
                `email`  = '{$_POST['Email']}',
                estado  = {$_POST['Estado']} 
					  
                      {$clave}
					  
					  WHERE `usuarios`.`id` = {$_POST['Id']};";	
                        
                    mysqli_query($con,$sql);
					
	
			 }
					
			
	exit();
		}



		$sql = "select * from usuarios where id = {$_GET['id']}";
		      $rs  = mysqli_query($con,$sql);
			 $user = mysqli_fetch_assoc($rs);

?>


			
			Nombre *
			<input type="text" id="nombre" value="<?php echo $user['nombre'] ?>" class="form-control">
            Usuario *
            <input type="text" id="usuario" value="<?php echo $user['usuario'] ?>" class="form-control">
            Email 
            <input type="email" id="email"  value="<?php echo $user['email'] ?>" class="form-control">
            Clave 
            <input type="password" id="clave" class="form-control">
            Estado
            <select id="estado" class="form-control">
            	<option value="1">Activo</option>
   <option value="0" <?php echo ($_GET['id'] > 0 and $user['estado']==0)?"selected":"" ?> >Inactivo</option>
            </select>
            <br>
       <input type="button" onClick="addUsuario()" class="btn btn-primary btn-block " value="Guardar">
            
            <script>
				function addUsuario(){
					
					nombre  =  $("#nombre").val();
					usuario =  $("#usuario").val();
					email   =  $("#email").val();
					clave   =  $("#clave").val();
					estado  =  $("#estado").val(); 
					 //validamos el nombre 
					 	if(nombre.length < 4){
						 	$("#nombre").css("border","red 1px solid");
						 	return false;
						 }
						//colocamos el color del border nombre 
						$("#nombre").css("border","#ccc 1px solid");
						
						//validamos el usuario
						if(usuario.length < 4){
							$("#usuario").css("border","red 1px solid");
							return false
							}
							
							
						//colocamos el color del border al usuario
						$("#usuario").css("border","1px solid #ccc");
						
						
								$.post( "modulos/usuarios/addUsuario.php",{
									Nombre  : nombre,
									Usuario : usuario,
									Email   : email,
									Clave   : clave,
									Estado  : estado,
									Id      : <?php echo $_GET['id']+0 ?> 
									
									}) .done(function(data){  
									   //alert(data);  
										$("#myModal").modal('hide');
										irA('modulos/usuarios/usuarios.php');
									
									});
									
						
					
					}
				
			</script>