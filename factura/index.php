<?PHP 
include("../libreria/engine.php");

function consulta($tabla_db,$id,$con){ 
        $sql = "select * from {$tabla_db} where id = {$id}";
        $rss  = mysqli_query($con,$sql);
        $consulta = mysqli_fetch_assoc($rss);
    return $consulta;
}

$factura = consulta("factura", $_GET['id'],$con);
$cliente = consulta("clientes", $factura['id_cliente'],$con);


?>
<!doctype HTMl>
<html>
  <head>
    <title></title>
  </head>  
    
    <style>
    
    
     
     .table-factura{
         width:800px;
         margin:0px;
         font-size:12px;
         border:4px solid #dfdcdc;
		 text-align:center;
      }    
      
      td{
        padding :5px;
        }
    
    
      #factura{
        height:1000px;
        width:800px;
       
        }
      .header{
        height:20px;
        text-align:center;
        color:#f4343f;
        padding:5px;
		vertical-align:middle;
        }
      .info-empresa{
        width:400px;
		padding-top:20px;
		padding-left:10px;
		padding-right:10px;
  
        float:left;
        font-family:arial;
        font-size:12px;
        line-height:15px;
        }
      .info-factura{
          width:360px;
          float:left;
          padding:10px;
          text-align:right;
          font-family:avenir;
          font-size:12px;
          line-height:15px;
        }  
      .linea{
        border:1px solid #ccc;
        display:block; 
        }
      .cliente{
          padding:10px;    
          }
        body{
          font-family:avenir;
          font-size:12px;
          } 
   
    </style>
    <body>
        <div id="factura">
          <div class="header"> <br> FACTURA <?php echo $factura['id'] ?> </div>
          <div class="info-empresa">
               <img height="65" src="logo.png" style=" float:left; padding-right:20px ">
              <br>
               RNC : 03030303030
               <br>
               Direccion: Carmen Mendoza # 2, Sto. Dgo.
               <br>
              Tel.:849-000-1001  
          </div>
          
          <div class="info-factura">
          <br>
           <br>
          
          
              <b> No. de Factura: </b>  <?php echo $factura['id'] ?>

              <br>
              <b>NCF:</b> <?php echo $factura['ncf'] ?>
              <br>
              <b>Fecha:</b> <?php echo $factura['fecha_creacion'] ?>
              
            
             <!-- <?php echo date("d-m-Y H:i A"); ?> -->
          </div>
           <br> <br> <br> <br> <br> <br> <br>
          <div class="linea"></div>
            <div class="cliente">
                <b>Cliente :</b> <?php echo $cliente['nombre'] ?>
                <br>
                <b>Cedula :</b> <?php echo $cliente['cedula'] ?>
                <br>
                <b>Telefono :</b> <?php echo $cliente['telefono'] ?>
                <br>
                <b>Direccion :</b> <?php echo $cliente['direccion'] ?>
            </div>
            
            <div class="linea"></div>
             
             <div class="cuerpo-de-factura" style="height:700px"> 
             
             <table class="table-factura" rules="all">
                <tr style="background:#dfdcdc">
                  <td>Cantidad</td>
                  <td>Medida</td>
                  <td>Descripcion</td>
                  <td>Precio</td>
                  
                  <td>Sub-Total</td>
                  <td>Impuesto</td>
                  <td>Total</td>
                </tr>
            
<?php 

$sub_total = 0;
$impuesto = 0;
    $sql = "select p.nombre,df.impuesto,p.existencia,df.id_producto as id,df.cantidad,df.precio as sub_total,df.pago  from detalles_factura df
    left join productos p
    on(df.id_producto = p.id)
    where df.id_factura = '{$factura['id']}' 
    ";
$rs = mysqli_query($con,$sql);
while($fila = mysqli_fetch_assoc($rs)){

    $sub_total += $fila['sub_total'] * $fila['cantidad'];
									$impuesto  += $fila['impuesto'];
								
									echo "
									  <tr  valign='top'>
										  <td>{$fila['cantidad']}</td>
										  <td>unidad</td>
										  <td>{$fila['nombre']}</td>
										  <td>".number_format($fila['sub_total']/$fila['cantidad'],2)."</td>
										  
						 <td>".number_format($fila['sub_total'] *  $fila['cantidad'],2)."</td>
										  <td>".number_format($fila['impuesto'],2)."</td>
									 <td>".number_format($fila['sub_total'] + $fila['impuesto'],2)."</td>
										</tr>
									
									
										";

}
                
                
                 ?>
                 
                 
                <tr>
                  <td colspan='4'></td>
                  <td> <?php echo number_format($sub_total,2) ?></td>
                  <td><?php echo number_format($impuesto,2) ?></td>
                  
                  <td><b><i><?php echo number_format($impuesto+$sub_total,2) ?></b></i></td>
                </tr>
    
    
    

                 
                </table>
                 <br> <br> <br> <br> <br> <br> <br>
                <center>
                ____________________________________________            
                <br>
                Firma del Cliente
                </center>
                
                
              </div>
              
              
            <div class="linea"></div>
            
            <center>
              Intel |  Direccion : Calle Rosa Duarte # 15, Sto. Dgo. RD. |Tel.:809-220-1111  
            </center>
            
          
        </div>
  
    </body>
</html>