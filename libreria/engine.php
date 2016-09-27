<?php 
session_start();

	
	function paFuera(){
		   if( !isset($_SESSION['user']) ){
			    //con javascript
			  	echo "
					<script>
						location.href='login.php';
					</script>
					";
				 // con php	
				 header("Location: login.php");
			   }
			
		}
	
	


//conexion a la BD
 $con = mysqli_connect("localhost","root","","facturacion");
 if(mysqli_connect_errno()){
	   echo "La Base de datos Fallo ". mysqli_connect_error();
	 }
	 
	 