<?php 
include("libreria/engine.php");
	$mensaje ="";
	if($_POST){
	
		$user = $_POST['user'];
		$clave = md5($_POST['clave']);
		
		$sql ="select * from usuarios where usuario = '{$user}'  and clave = '{$clave}' ";
		$rs = mysqli_query($con,$sql);
		$fila = mysqli_fetch_assoc($rs);
		
		if(isset($fila['id']) and $fila['id'] > 0 ){
			//todo bien 
			
			$_SESSION['user'] = $fila;
			
				echo "<script> location.href='./sistema.php'; </script>";
			
			header("Location: ./sistema.php");
			
			
			exit();
			}
		
		$mensaje ="Usuario o Clave Invalidos !!!";
		
		}
	


?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Login</title>
    
<link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
</head>

<body>
				<form method="post">	
                       <div class="col-md-4"></div>
                   
                       <div class="col-md-4" style="padding-top:200px;">
                           <img src="factura/logo.png">
                           <br>
                            <input type="text" 
                      value="<?php echo ( isset($_POST['user']) )?$_POST['user']:"";     ?>" name="user" placeholder="Usuario" class="form-control">
                            <br>
                            <input name="clave" type="password" placeholder="Clave" class="form-control">
                            <br>
                            <button class="btn btn-primary btn-block ">Entrar </button>
                                
                                <br><br>
                                 <center>
                                 <font color="red">
                                <?php  
                                    echo $mensaje;
                                ?>
                                </font>
                                </center>
                       </div>
				</form>
                
</body>
</html>