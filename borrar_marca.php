<?php
include ("_core.php");
function initial()
{
	$id_marca = $_REQUEST ['id_marca'];
	$sql="SELECT * FROM marca WHERE id_marca='$id_marca'";
	$result = _query($sql);
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Borrar Marca</h4>
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
							echo "<tr><td>Id</th><td>$id_marca</td></tr>";
							echo "<tr><td>Marca</td><td>".$row['marca']."</td>";
							echo "</tr>";
						?>
					</tbody>
				</table>
			</div>
		</div>
			<?php 
				echo "<input type='hidden' nombre='id_marca' id='id_marca' value='$id_marca'>";
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
	$id_marca = $_POST ['id_marca'];
	$table = 'marca';
	$where_clause = "id_marca='" . $id_marca . "'";
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
