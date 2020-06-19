<?php
$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
if($action == 'ajax'){
	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	
	if (isset($_REQUEST['id'])){
		$id=intval($_REQUEST['id']);
		$delete=mysqli_query($con,"delete from tmp where id='$id'");
	}
	
	if (isset($_POST['descripcion'])){
		
		$descripcion=mysqli_real_escape_string($con,(strip_tags($_POST["descripcion"],ENT_QUOTES)));
		$cantidad=intval($_POST['cantidad']);
		$precio=floatval($_POST['precio']);
		$color=mysqli_real_escape_string($con,(strip_tags($_POST["color"],ENT_QUOTES)));
		$talla=mysqli_real_escape_string($con,(strip_tags($_POST["talla"],ENT_QUOTES)));
		$sql="INSERT INTO `tmp` (`id`, `descripcion`, `cantidad`, `precio`,color,talla) VALUES (NULL, '$descripcion', '$cantidad', '$precio','$color','$talla');";
		
		$insert=mysqli_query($con,$sql);
	}
	$query_perfil=mysqli_query($con,"select * from perfil where id=1");
	$rw=mysqli_fetch_assoc($query_perfil);
	$impuestos=$rw['impuestos'];
	
	$query=mysqli_query($con,"select * from tmp order by id");
	$items=1;
	$suma=0;
	while($row=mysqli_fetch_array($query)){
			$total=$row['cantidad']*$row['precio'];
			$total=number_format($total,2,'.','');
		?>
	<tr>
		<td><?php echo $row['descripcion'];?></td>

		<td class='text-center'><?php echo $row['color'];?></td>
		<td class='text-center'><?php echo $row['talla'];?></td>
		<td class='text-center'><?php echo $row['cantidad'];?></td>
		
		
		<td class='text-right'><?php echo $row['precio'];?></td>
		<td class='text-right'><?php echo $total;?></td>
		<td class='text-right'><a href="#" onclick="eliminar_item('<?php echo $row['id']; ?>')" ><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAAoLQ9TAAAAeFBMVEUAAADnTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDx+VWpeAAAAJ3RSTlMAAQIFCAkPERQYGi40TVRVVlhZaHR8g4WPl5qdtb7Hys7R19rr7e97kMnEAAAAaklEQVQYV7XOSQKCMBQE0UpQwfkrSJwCKmDf/4YuVOIF7F29VQOA897xs50k1aknmnmfPRfvWptdBjOz29Vs46B6aFx/cEBIEAEIamhWc3EcIRKXhQj/hX47nGvt7x8o07ETANP2210OvABwcxH233o1TgAAAABJRU5ErkJggg=="></a></td>
	</tr>	
		<?php
		$items++;
		$suma+=$total;
	}
	$neto=$suma;
	$total_impuestos=($neto*$impuestos) / 100;
	$total=$neto+$total_impuestos;
	?>
	<tr>
		<td colspan='7'>
		
			<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-plus"></span> Agregar producto</button>
		</td>
	</tr>
	<tr>
		<td colspan='5' class='text-right'>
			<h4>Sub Total :</h4>
		</td>
		<th class='text-right'>
			<h4><?php echo number_format($neto,2);?></h4>
		</th>
		<td></td>
	</tr>
	<tr>
		<td colspan='5' class='text-right'>
			<h4>Impuestos <small><?=$impuestos?>%</small> :</h4>
		</td>
		<th class='text-right'>
			<h4><?php echo number_format($total_impuestos,2);?></h4>
		</th>
		<td></td>
	</tr>
	<tr>
		<td colspan='5' class='text-right'>
			<h4>Total :</h4>
		</td>
		<th class='text-right'>
			<h4><?php echo number_format($total,2);?></h4>
		</th>
		<td></td>
	</tr>
<?php

}