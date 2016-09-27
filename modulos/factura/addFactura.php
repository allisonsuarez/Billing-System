<?php 
include("../../libreria/engine.php");
//function para hacer consultas dinamicas 
function consulta($tabla_db,$id,$con){ 
$sql = "select * from {$tabla_db} where id = {$id}";
$rss  = mysqli_query($con,$sql);
$consulta = mysqli_fetch_assoc($rss);
return $consulta;
}

if($_POST){
//datos del cliente 

if($_POST['id_cliente'] < 1){
//Creamos nuevo clientes 

    $sql = "INSERT INTO `clientes` 
     ( `nombre`, `cedula`, `rnc`, `telefono`, `direccion`, `estado`)
      VALUES 
      (
     '{$_POST['nombre']}',
     '{$_POST['cedula']}',
     '{$_POST['rnc']}',
     '{$_POST['telefono']}',
     '{$_POST['direccion']}',
     '1'
     );";	
     //guardamos los datos del cliente
     mysqli_query($con,$sql);	
     //cargamos el id del cliente 
     $cliente = mysqli_insert_id($con);
}else{
// Actulizamos los datos del clientes 

$sql ="UPDATE `clientes` SET 
     `nombre`     = '{$_POST['nombre']}',
     `rnc`        = '{$_POST['rnc']}',
     `cedula`        = '{$_POST['cedula']}',
     `telefono`   = '{$_POST['telefono']}',
     `direccion`  = '{$_POST['direccion']}', 
     `telefono`   = '{$_POST['telefono']}'
      WHERE `id`  =  '{$_POST['id_cliente']}';";	
    mysqli_query($con,$sql);
    //cargamos el id del cliente 
    $cliente = $_POST['id_cliente']+0;

}

//guardarmos los datos de la factura			
if($_POST['id'] < 1){
//nueva facturara  
//sql para agregar factura 
$sql="
insert into factura 
(
id_cliente,
id_usuario,
fecha,
pago,
sub_total,
impuesto,
total,
ncf,
codigo,
detalle,
fecha_creacion,
estado
)

VALUES
(
'{$cliente}',
'{$_SESSION['user']['id']}',
'{$_POST['fecha']}',
'{$_POST['sub_t']}',
'{$_POST['pago']}',
'{$_POST['itbs']}',
'{$_POST['total']}',
'{$_POST['ncf']}',
'{$_POST['tipo']}',
'{$_POST['detalle']}',
'".date("Y-m-d h:i:s")."',
'{$_POST['estado']}'

)
";
//guardamos la factura
mysqli_query($con,$sql);	

//id de la factura agregada  	
$id_factura = mysqli_insert_id($con);

}else{
//modificamos la fatura

$sql ="UPDATE `factura` SET 
     `id_cliente`      = '{$cliente}',
     `id_usuario`      = '{$_SESSION['user']['id']}',
     `fecha`           = '{$_POST['fecha']}',
     `sub_total`       = '{$_POST['sub_t']}',
     `pago`            = '{$_POST['pago']}',
     `total`      	   = '{$_POST['total']}',
     `ncf`             = '{$_POST['ncf']}',
     `impuesto`        = '{$_POST['itbs']}',
     `codigo`          = '{$_POST['tipo']}',
     `detalle`         = '{$_POST['detalle']}',
     `estado`          = '{$_POST['estado']}'
      WHERE `id`       = '{$_POST['id']}';";	
    mysqli_query($con,$sql);

//id de la factura actualizada 		
$id_factura= $_POST['id']+0;

}


//para inventario devolvemos los producto comprados  
$sql = "select df.id_producto as id,df.cantidad,p.existencia from detalles_factura df
        left join productos p
        on(df.id_producto = p.id)
        where df.id_factura = '{$id_factura}'";
$rs = mysqli_query($con,$sql);
while($fila = mysqli_fetch_assoc($rs)){
    $cantidad = $fila['cantidad']+$fila['existencia'];

    $sqls ="UPDATE `productos` SET 
           `existencia` = '{$cantidad}'
           where id = '{$fila['id']}'";	
           mysqli_query($con,$sqls);	   

}



//borramos todos los productos relacionado con esta factura 
$sql = "DELETE FROM `detalles_factura` WHERE `id_factura` = '{$id_factura}'";
mysqli_query($con,$sql);


//guardarmos el detalle de la factura
if(isset($_POST['id_producto'])){

$prod = $_POST['id_producto'];
//contamos la cantidad de productos 
$cant = count($_POST['id_producto']);
//recorremos el array de producto en post 
for($k=0; $k < $cant; $k++){
    //sql para agregar el detalle 
    $sql = "INSERT INTO `detalles_factura` 
     ( `id_producto`, `id_factura`,`cantidad`,`precio`, `impuesto`,pago)
      VALUES 
      (
     '{$prod[$k]}',
     '{$id_factura}',
     '{$_POST['cantidad'][$k]}',
     '{$_POST['precio'][$k]}',
     '{$_POST['impuesto'][$k]}',
     '{$_POST['sub_total'][$k]}'
     );";
     mysqli_query($con,$sql);

     $sqls ="UPDATE `productos` SET  `existencia` = (existencia - {$_POST['cantidad'][$k]})
           where id = '{$prod[$k]}'";	
           mysqli_query($con,$sqls);	

}
}





echo "<script>
parent.$('#MM').modal('hide');
parent.irA('modulos/factura/factura.php?id={$id_factura}');
</script>";


exit();
}




$sql = "select * from factura where id = {$_GET['id']}";
$rs  = mysqli_query($con,$sql);
$fact = mysqli_fetch_assoc($rs);






$clit = consulta('clientes',$fact['id_cliente']+0,$con);



$fecha = ($fact['fecha'])?$fact['fecha']:date('Y-m-d');


?>
<link rel="stylesheet" href="addfactstyle.css">
<div class="panel panel-body">

<iframe name="ifrmF" id="ifrmF" style="width:100%; display:none; height:300px;">
</iframe>

<h1>No.#<?php echo $fact['id']+0 ?></h1>
<form action="modulos/factura/addFactura.php" onSubmit=" return loaderGuardar()" method="post" target="ifrmF">
<div class="row">

<div class="col-md-4">

<div style="" class="form-group input-group">
<span class="input-group-addon"> 

<span class="fa fa-university"></span>
</span>
<input type="text"  name="nombre" readonly id="nombre"  onclick="MM('modulos/factura/clientes.php')" placeholder="Name" class="form-control" value="<?php echo $clit['nombre'] ?>" />
<span class="input-group-addon" onclick="MM('modulos/factura/clientes.php')"> 
<span class="fa fa-plus-circle"></span>
</span>
</div>
<div  class="form-group input-group ">
<span class="input-group-addon">Cedula</span>
<input type="text" name="cedula" id="cedula" value="<?php echo $clit['cedula'] ?>" placeholder="" class="form-control" /> 
<input name="cliente" id="cliente" type="hidden" value="<?php echo $clit['id'] ?>" />				</div>
<div  class="form-group input-group ">
<span class="input-group-addon">RNC</span>
<input type="text" name="rnc" id="rnc" value="<?php echo $clit['rnc'] ?>" placeholder="" class="form-control" /> 
<input name="id_cliente" id="id_cliente" type="hidden" value="<?php echo $clit['id']+0 ?>" /> 
</div>

<div style="" class="form-group input-group">
<span class="input-group-addon"> 
<span class="fa fa-phone"></span>
</span>
<input type="telefono" id="telefono" value="<?php echo $clit['telefono'] ?>" name="telefono" placeholder="Phone" class="form-control" />
</div>



<div class="form-group input-group " >
<span class="input-group-addon"> 
<span class="fa fa-road"></span>
</span>
<input type="text" id="direccion" value="<?php echo $clit['direccion'] ?>" name="direccion" placeholder="address" class="form-control" />
</div>

</div>
<div class="col-md-4">
<div style="" class="form-group input-group">
<span class="input-group-addon"> 
<span class="fa fa-file-o"></span>
</span>
<select class="form-control" name="tipo" id="tipo">
<option value="1">FACTURA</option>
<option value="2"  <?php echo ($fact['codigo'] ==2)?"selected":""; ?> >COTIZACION</option>
</select>  
<input type="hidden" name="id" value="<?php echo $fact['id']+0 ?>"/>
</div>


<div style="" class="form-group input-group">
<span class="input-group-addon"> 
<span class="fa fa-calendar" ></span>
</span> 
<input type="date" id="fecha_producto" value="<?php echo $fecha  ?>" name="fecha" placeholder="Fecha" class="form-control" /> 	
</div>

<div style="" class="form-group input-group"> 
<span class="input-group-addon"> NCF</span> 
<input type="text" id="ncf" value="<?php echo $fact['ncf'] ?>" name="ncf" placeholder="ncf" class="form-control" /> 	

</div>
<div style="" class="form-group input-group"> 
<span class="input-group-addon"> 
Detalles:
</span> 

<textarea class="form-control" name="detalle"  style="height:60px;"><?php echo $fact['detalle'] ?></textarea>

</div>
<div style="" class="form-group input-group"> 
<span class="input-group-addon"> 
Estado:
</span> 
<select class="form-control" name="estado">
<option value="1">Activo</option>
<option value="0" <?php echo ($fact['estado'] ==0 and $fact['id'] > 0 )?"selected":""; ?>>Inactivo</option>
</select>

</div>
</div>

<div class="col-md-4"> 
<div  class="form-group input-group">
<span class="input-group-addon"> 
<span >SUBTOTAL</span>
</span>       
 <input type="number" value="<?php echo number_format($fact['sub_total'],0) ?>" id="Tsubtotal" name="sub_t" class="form-control" readonly />
</div>

<div style=" margin-top:-8px" class="form-group input-group">
<span class="input-group-addon"> 
<span >ITBS</span>
</span>       
 <input type="number" id="Timpuesto" value="<?php echo number_format($fact['impuesto'],0) ?>" name="itbs" class="form-control"  readonly="readonly" />
</div>

<div style="margin-top:-8px" class="form-group input-group">
<span class="input-group-addon"> 
<span >TOTAL</span>
</span>       
 <input type="number" style="font-size:15px; font-weight:bold; color:red" id="Totalx" name="total" class="form-control" readonly />
</div>


<div  class="form-group input-group">
<span class="input-group-addon"> 
<span >PAGO</span>
</span>       
 <input type="number" required title="pago" id="pago" name="pago"  class="form-control" value="<?php echo (isset($fact['pago']))? number_format($fact['pago'],0):""; ?>"/>
</div>
<input type="submit" class="btn btn-primary btn-block" value="Proceder al Pago">
</div>
</div>
<div class="row">
<button class="btn btn-info "  type='button' onClick="AddP()">Agregar Producto</button>
<div class="panel panel body" style="min-height:200px;">

<table class="table" >
<thead>	
<tr>
    <td>Codigo</td>
    <td>Producto</td>
    <td>Cantidad</td>
     <td>Existencia</td>
    <td>Precio</td>
    <td  style='width:100px;'>Subtotal</td>
    <td>Impuesto</td>
    <td>Total</td>
    <td>--</td> 
</tr>
</thead>
<tbody id='productos'>
<?php 
$sc='';
$dtss = "";
$sql = "select p.nombre,df.impuesto,p.existencia,df.id_producto as id,df.cantidad,df.precio as sub_total,df.pago  from detalles_factura df
        left join productos p
        on(df.id_producto = p.id)
        where df.id_factura = '{$fact['id']}' 
        ";
$rs = mysqli_query($con,$sql);
while($fila = mysqli_fetch_assoc($rs)){

          $total = $fila['impuesto']+$fila['sub_total'];
          $impuesto = ($fila['impuesto'] > 0)?"checked":"";
          $existencia = $fila['existencia']+$fila['cantidad'];

            $dtss .="

                        <tr id='produ{$fila['id']}'>
                        <td id='codigo{$fila['id']}' ><input type='hidden' name='id_producto[]' value='{$fila['id']}'> {$fila['id']}</td>
                        <td id='nombre{$fila['id']}' class='nombre'>{$fila['nombre']}</td><td><input name='cantidad[]' id='cantidad{$fila['id']}' class='form-control' onkeyup='carculando({$fila['id']}); actualizar()'  style='width:50px' value='{$fila['cantidad']}' ></td>
                        <td><input id='existencia{$fila['id']}' value='{$fila['existencia']}' data='{$existencia}' class='form-control' readonly style='width:50px' value='1'></td>

                        <td><input id='total{$fila['id']}' name='precio[]' class='form-control' style='width:50px' onkeyup='carculando({$fila['id']}); actualizar()'  value='{$fila['sub_total']}'></td>

                        <td  style='width:100px;'><input style='width:100px;' id='subtotal{$fila['id']}' class='form-control Tsubtotal'style='width:60px' name='sub_total[]' value='{$fila['sub_total']}' readonly></td><td><table><tr>
                        <td><input type='checkbox' {$impuesto}  id='itbis{$fila['id']}' value='1'  onclick='carculando({$fila['id']});actualizar()' >&nbsp;</td>
                        <td><input class='form-control Timpuesto'style='width:60px' id='impuesto{$fila['id']}' name='impuesto[]' value='{$fila['impuesto']}'  readonly></td></td></tr></table><td id='totalAll{$fila['id']}'>{$total}</td><td><button class='btn btn-danger btn-xs' type='button' onclick='eliminarItem(this)'>-</button></td></tr>



                    ";	

                $sc .="carculando({$fila['id']});";		
          }
echo $dtss;

?>
</tbody>



</table>
</div>
</div>

</form>
</div>
<script>


function agregar(id,ths){

cantidad  =	parseInt( $("#cantidad"+id).val());

if(cantidad < 1){
alert('Agregue la cantidad');
return false;
}
$(ths).parent().parent().attr('bgcolor','pink')

itbis = (document.getElementById('itbis'+id).checked)?"checked":"";

if(! document.getElementById('produ'+id)){
$("#productos").append("<tr id='produ"+id+"'><td id='codigo"+id+"' ><input type='hidden' name='id_producto[]' value='"+id+"'> "+id+"</td><td id='nombre"+id+"'>"+$("#nombre"+id).html()+"</td><td><input name='cantidad[]' autocomplete='off' id='cantidad"+id+"' class='form-control' onkeyup='carculando("+id+"); actualizar()'  style='width:50px' value='"+$("#cantidad"+id).val()+"' ></td><td><input autocomplete='off' id='existencia"+id+"' class='form-control' onkeyup='carculando("+id+"); actualizar()'  style='width:50px' value='"+$("#existencia"+id).val()+"' data='"+$("#existencia"+id).attr('data')+"' readonly></td><td><input id='total"+id+"' name='precio[]' class='form-control' style='width:50px' onkeyup='carculando("+id+"); actualizar()'  value='"+$("#total"+id).val()+"'></td><td><input id='subtotal"+id+"' class='form-control Tsubtotal'style='width:100px' name='sub_total[]' value='"+$("#subtotal"+id).val()+"' readonly></td><td><table><tr><td><input type='checkbox' id='itbis"+id+"' value='"+$("#itbis"+id).val()+"'  onclick='carculando("+id+"); actualizar()' "+itbis+" >&nbsp;</td><td><input class='form-control Timpuesto'style='width:60px' id='impuesto"+id+"' name='impuesto[]' value='"+$("#impuesto"+id).val()+"'  readonly></td></td></tr></table><td id='totalAll"+id+"'>"+$("#totalAll"+id).html()+"</td><td><button class='btn btn-danger btn-xs' type='button' onclick='eliminarItem(this)'>-</button></td></tr>");
actualizar();
}else{
$("#produ"+id).remove();
$("#productos").append("<tr id='produ"+id+"'><td id='codigo"+id+"' ><input type='hidden' name='id_producto[]' value='"+id+"'> "+id+"</td><td id='nombre"+id+"'>"+$("#nombre"+id).html()+"</td><td><input name='cantidad[]' autocomplete='off' id='cantidad"+id+"' class='form-control' onkeyup='carculando("+id+"); actualizar()'  style='width:50px' value='"+$("#cantidad"+id).val()+"' ></td><td><input autocomplete='off' id='existencia"+id+"' class='form-control' onkeyup='carculando("+id+"); actualizar()'  style='width:50px' value='"+$("#existencia"+id).val()+"' data='"+$("#existencia"+id).attr('data')+"' readonly></td><td><input id='total"+id+"' name='precio[]' class='form-control' style='width:50px' onkeyup='carculando("+id+"); actualizar()'  value='"+$("#total"+id).val()+"'></td><td><input id='subtotal"+id+"' class='form-control Tsubtotal'style='width:100px' name='sub_total[]' value='"+$("#subtotal"+id).val()+"' readonly></td><td><table><tr><td><input type='checkbox' id='itbis"+id+"' value='"+$("#itbis"+id).val()+"'  onclick='carculando("+id+"); actualizar()' "+itbis+" >&nbsp;</td><td><input class='form-control Timpuesto'style='width:60px' id='impuesto"+id+"' name='impuesto[]' value='"+$("#impuesto"+id).val()+"'  readonly></td></td></tr></table><td id='totalAll"+id+"'>"+$("#totalAll"+id).html()+"</td><td><button class='btn btn-danger btn-xs' type='button' onclick='eliminarItem(this)'>-</button></td></tr>");
actualizar();
}

//$("#productos").append("<tr class='tr"+id+"'><td><input type='hidden' value='"+$("#subtotal"+id).val()+"' class='Tsubtotal'><input type='hidden' value='"+$("#impuesto"+id).val()+"' class='Timpuesto'>"+$("#codigo"+id).html()+"</td><td>"+$("#nombre"+id).html()+"</td><td>"+$("#cantidad"+id).val()+"</td>  <td>"+numberWithCommas($("#subtotal"+id).val())+"</td><td>"+$("#impuesto"+id).val()+"</td><td>"+$("#totalAll"+id).html()+"</td><td><button class='btn btn-danger btn-xs' type='button' onclick='eliminarItem(this)'>-</button></td></tr>");

actualizar();			
// $(document).scrollTop(0);
}

function actualizar(){


$("#Timpuesto").val(0);
$("#Tsubtotal").val(0);

var subtotal=0;
var impuesto=0;



$('.Timpuesto').each(function() {
var num = parseFloat(this.value, 10);
if (!isNaN(num)) {
    impuesto += num;
}
});


$('.Tsubtotal').each(function() {
var num = parseFloat(this.value, 10);
if (!isNaN(num)) {
    subtotal += num;
}
});




$("#Tsubtotal").val(subtotal);
$("#Timpuesto").val(impuesto);



$("#Totalx").val( subtotal + impuesto); 


}


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

cantidad  = $("#cantidad"+id).val();


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

actualizar();	
}



//para eliminar las filas
function eliminarItem(id){
$(id).parent().parent().attr('bgcolor','pink');
if(confirm("Desea eliminar este producto?")) 
{
$(id).parent().parent().remove()
actualizar()
//document.getElementById('tbOPCIONE').removeChild(item);
}else{
$(id).parent().parent().attr('bgcolor','');	
}
}


function AddP(){
if($("#mAbierto").val()==0){
MM('modulos/factura/listaProducto.php')
}else{
$("#Mymodal").modal('show');
}
}
<?php echo $sc ?>
actualizar();

</script>
<input id="mAbierto" type="hidden" value="0">
