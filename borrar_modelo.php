<?php
include ("_core.php");
function initial()
{
	$id_modelo = $_REQUEST ['id_modelo'];
	$sql="SELECT m.id_modelo, m.modelo, ma.marca FROM modelo as m, marca as ma WHERE m.id_marca=ma.id_marca AND m.id_modelo='$id_modelo'";
	$result = _query($sql);
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Borrar Modelo</h4>
</div>
<div class="modal-body">
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<table class="table table-bordered table-striped" id="tableview">
					<thead>
						<tr>
							<th>Campo</th>
							<th>Descripcion</th>
						</tr>
					</thead>
					<tbody>	
						<?php
							$row = _fetch_array ($result);
							echo "<tr><td>Id</th><td>$id_modelo</td></tr>";
							echo "<tr><td>Marca</td><td>".$row['marca']."</td>";
							echo "<tr><td>Modelo</td><td>".$row['modelo']."</td>";
							echo "</tr>";
						?>
					</tbody>
				</table>
			</div>
		</div>
			<?php 
				echo "<input type='hidden' nombre='id_modelo' id='id_modelo' value='$id_modelo'>";
			?>
		</div>

</div>
<div class="modal-footer">
	<button type="button" class="btn btn-primary" id="btnDelete">Borrar</button>
	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>

</div>
<!--/modal-footer -->

<?php
/*
}
	else {
		echo "<div></div><br><br><div class='alert alert-warning'>You don't have permission to use this module.</div>";
	}
	*/
}
function deleted()
{
	$id_modelo = $_POST ['id_modelo'];
	$table = 'modelo';
	$where_clause = "id_modelo='" . $id_modelo . "'";
	$delete = _delete ( $table, $where_clause );
	if ($delete)
	{
		$xdatos ['typeinfo'] = 'Success';
		$xdatos ['msg'] = 'Datos eliminados correctamente!';
	} 
	else 
	{
		$xdatos ['typeinfo'] = 'Error';
		$xdatos ['msg'] = 'Datos no pudieron ser eliminados!';
	}
	echo json_encode ( $xdatos );
}
if (! isset ( $_REQUEST ['process'] ))
{
	initial();
}
else 
{
	if (isset ( $_REQUEST ['process'] ))
	{
		switch ($_REQUEST ['process'])
		{
			case 'formDelete' :
				initial();
				break;
			case 'deleted' :
				deleted();
				break;
		}
	}
}
?>
