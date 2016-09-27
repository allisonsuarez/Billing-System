<?php 
include("../../libreria/engine.php");


?>

<h2 style="margin-top:10px;">Usuarios</h2>
<hr style="margin-top:2px;">
<button class="btn bt-success" onclick="MM('modulos/usuarios/addUsuario.php?id=0')">Nuevo Usuario</button>
<br>
<br>
<table class="table">
    
    <tr>
        
        <td>Nombre</td>
        <td>Email</td>
        <td>Usuario</td>
        <td>--</td>
        
    </tr>
    <?php
    
    $sql = "select * from usuarios";
    
    $rs =  mysqli_query($con,$sql);
    
    while($fila = mysqli_fetch_assoc($rs)){
        
        $color = ($fila['estado']==0)?"pink":"";
            
        echo "
        
    <tr style = 'background:{$color}'>
        
        <td>{$fila['nombre']}</td>
        <td>{$fila['email']}</td>
        <td>{$fila['usuario']}</td>


        <td>
        <button class='btn btn-primary btn-xs' onClick = \" MM('modulos/usuarios/addUsuario.php?id={$fila['id']}') \">
            Edit
        </button>
        
        </td>
        
    </tr>
        
        ";
    }
        
    ?>
    
</table>