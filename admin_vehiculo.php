<?php
include ("_core.php");
include('facturacion_funcion_imprimir.php');
// Page setup
function initial()
{
	include_once "_headers.php";
	$title='Administrar Vehiculos';
	$_PAGE ['title'] = $title;
	include_once "header.php";
	include_once "main_menu.php";
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);
	$vehiculos = getDataVehiculos();
?>
<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row" id="row1">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<?php
				if ($links!='NOT' || $admin=='1' ){
					echo "<div class='ibox-title'>";
					$filename='agregar_vehiculo.php';
					$link=permission_usr($id_user,$filename);
					if ($link!='NOT' || $admin=='1' )
					echo "<a href='agregar_vehiculo.php' class='btn btn-primary' role='button'><i class='fa fa-plus icon-large'></i> Agregar vehiculo</a>";
					echo	"</div>";
					?>
					<div class="ibox-content">
						<!--load datables estructure html-->
						<header>
							<h4><?php echo $title; ?></h4>
						</header>
						<section>
							<table class="table table-striped table-bordered table-hover" id="editable2">
								<thead>
									<tr>
										<th class="col-lg-1">Id</th>
										<th class="col-lg-1">Marca</th>
										<th class="col-lg-3">Modelo</th>
										<th class="col-lg-2">Placa</th>
										<th class="col-lg-2">VIN</th>
										<th class="col-lg-1">Año</th>
										<th class="col-lg-1">Combustible</th>
										<th class="col-lg-1">Acci&oacute;n</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$count= _num_rows($vehiculos);
									/*
									v.id, v.placa, m.marca, mo.modelo,v.vin, v.anio, v.numero_unidad,
								  tv.descripcion, v.llantas, v.ejes, v.mes_vence_tarjeta,
								  v.capacidad,p.descripcion as combustible
									*/
					 					if ($count>0){
											for($i=0;$i<$count;$i++){
												$row=_fetch_array($vehiculos);

												echo "<tr>";
												echo "<td>". $row["id"] ."</td>
													    <td>". $row["marca"] ."</td>
															<td>". $row["modelo"] ."</td>
															<td>". $row["placa"] ."</td>
															<td>". $row["vin"] ."</td>
															<td>". $row["anio"] ."</td>
															<td>". $row["combustible"] ."</td>
															";
												echo headDropDown();
												$filename='editar_marca.php';
												$link=permission_usr($id_user,$filename);
												if ($link!='NOT' || $admin=='1' )
													echo "<li><a data-toggle='modal' href='editar_vehiculo.php?id=".$row["id"]."' ><i class='fa fa-pencil'></i> Editar</a></li>";
												$filename='borrar_marca.php';
												$link=permission_usr($id_user,$filename);
												if ($link!='NOT' || $admin=='1' )
													echo "<li><a data-toggle='modal' href='borrar_vehiculo.php?id=".$row["id"]."' data-target='#deleteModal' data-refresh='true'><i class='fa fa-eraser'></i> Eliminar</a></li>";
												echo footDropDown();
											}
										}
									?>
								</tbody>
							</table>
							<input type="hidden" name="autosave" id="autosave" value="false-0">
						</section>
						<!--Show Modal Popups View & Delete -->
						<div class='modal fade' id='viewModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
							<div class='modal-dialog'>
								<div class='modal-content'></div><!-- /.modal-content -->
							</div><!-- /.modal-dialog -->
						</div><!-- /.modal -->
						<div class='modal fade' id='deleteModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
							<div class='modal-dialog modal-sm'>
								<div class='modal-content modal-sm'></div><!-- /.modal-content -->
							</div><!-- /.modal-dialog -->
						</div><!-- /.modal -->
					</div><!--div class='ibox-content'-->
				</div><!--<div class='ibox float-e-margins' -->
				</div> <!--div class='col-lg-12'-->
			</div> <!--div class='row'-->
		</div><!--div class='wrapper wrapper-content  animated fadeInRight'-->
		<?php
		include("footer.php");
		echo" <script type='text/javascript' src='js/funciones/vehiculo.js'></script>";
		?>
		<script type="text/javascript">
		$(document).on('hidden.bs.modal', function(e) {
			var target = $(e.target);
			target.removeData('bs.modal').find(".modal-content").html('');
		});
		</script>
		<?php


	}
	else {
		echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div></div></div></div></div>";
		include ("footer.php");
	}
}

function estado_vehiculo() {
	$id_vehiculo = $_POST ['id_vehiculo'];
	$estado = $_POST["estado"];
	if($estado == 1)
	{
		$n = 0;
	}
	else
	{
		$n = 1;
	}
	$table = 'vehiculo';
	$id_sucursal = $_SESSION["id_sucursal"];
	$form_data = array(
		'estado' => $n,
	);
	$where_clause = "id_vehiculo='".$id_vehiculo."'";
	$delete = _update ( $table, $form_data, $where_clause );
	if ($delete)
	{
		$xdatos ['typeinfo'] = 'Success';
		$xdatos ['msg'] = 'Registro actualizado con exito!';
	}
	else
	{
		$xdatos ['typeinfo'] = 'Error';
		$xdatos ['msg'] = 'Registro no pudo ser actualizado!';
	}
	echo json_encode ( $xdatos );
}
function printBcode()
{
  $qty 				 = $_POST['qty'];
	$tipo_etiq	 = $_POST['tipo_etiq'];
  $id_vehiculo = $_POST['id_vehiculo'];
  $id_sucursal=$_SESSION['id_sucursal'];
  //Valido el sistema operativo y lo devuelvo para saber a que puerto redireccionar
  $info = $_SERVER['HTTP_USER_AGENT'];
  if (strpos($info, 'Windows') == true) {
    $so_cliente='win';
  } else {
    $so_cliente='lin';
  }
  //directorio de script impresion cliente
  $sql_dir_print="SELECT *  FROM config_dir WHERE id_sucursal='$id_sucursal'";
  $result_dir_print=_query($sql_dir_print);
  $row_dir_print=_fetch_array($result_dir_print);
  $dir_print=$row_dir_print['dir_print_script'];
  $shared_print_barcode=$row_dir_print['shared_print_barcode'];
  $nreg_encode['shared_print_barcode'] =$shared_print_barcode;
  $nreg_encode['dir_print'] =$dir_print;
  $nreg_encode['sist_ope'] =$so_cliente;
	$nreg_encode['datos'] =print_bcode($id_vehiculo, $qty,$tipo_etiq);
  echo json_encode($nreg_encode);
}
function setPrintBcode()
{
	$id_sucursal = $_SESSION["id_sucursal"];
	$tipo_etiq	 = $_POST['tipo_etiq'];
  //Valido el sistema operativo y lo devuelvo para saber a que puerto redireccionar
  $info = $_SERVER['HTTP_USER_AGENT'];
  if (strpos($info, 'Windows') == true) {
    $so_cliente='win';
  } else {
    $so_cliente='lin';
  }
	$table = 'config_dir';

	$form_data = array(
		'media_type' => $tipo_etiq,
	);
	$where_clause = "id_sucursal='".$id_sucursal."'";
	$upd = _update ( $table, $form_data, $where_clause );

  //directorio de script impresion cliente
  $sql_dir_print="SELECT *  FROM config_dir WHERE id_sucursal='$id_sucursal'";
  $result_dir_print=_query($sql_dir_print);
  $row_dir_print=_fetch_array($result_dir_print);
  $dir_print=$row_dir_print['dir_print_script'];
  $shared_print_barcode=$row_dir_print['shared_print_barcode'];
  $nreg_encode['shared_print_barcode'] =$shared_print_barcode;
  $nreg_encode['dir_print'] =$dir_print;
  $nreg_encode['sist_ope'] =$so_cliente;
	$nreg_encode['datos'] =print_bcodeSet($tipo_etiq);
  echo json_encode($nreg_encode);
}
function setMarginBcode()
{
	$id_sucursal = $_SESSION["id_sucursal"];
	$leftmargin	 = $_POST['leftmargin'];
	$table = 'config_dir';
	$form_data = array(
		'leftmarginlabel' => $leftmargin,
	);
	$where_clause = "id_sucursal='".$id_sucursal."'";
	$upd = _update ( $table, $form_data, $where_clause );
  if($upd){
		$xdatos ['typeinfo'] = 'Success';
		$xdatos ['msg'] = 'Registro actualizado con exito!';

	}else{
		$xdatos ['typeinfo'] = 'Error';
		$xdatos ['msg'] = 'Registro no pudo ser actualizado !';
	}
  echo json_encode($xdatos);
}
if (!isset($_POST['process'])) {
    initial();
} else {
    if (isset($_POST['process'])) {
        switch ($_POST['process']) {
            case 'insert':
            insertar();
            break;
            case 'lista':
            lista();
            break;
            case 'insert_img':
        		insert_img();
        		break;
						case 'estado':
        		estado_vehiculo();
        		break;
						case 'printBcode':
						printBcode();
            break;
						case 'setPrintBcode':
						setPrintBcode();
            break;
						case 'setMarginBcode':
						setMarginBcode();
						break;
        }
    }
}
?>
