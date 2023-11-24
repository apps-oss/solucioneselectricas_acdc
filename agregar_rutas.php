<?php
include("_core.php");
function formulario()
{
	$title='Agregar Ruta por Municipio';
	$_PAGE = array();
  $_PAGE ['title'] = $title;
  $_PAGE ['links'] = null;
	  $_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/typeahead.css" rel="stylesheet">';

  $_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/select2/select2-bootstrap.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/bootstrap-checkbox/bootstrap-checkbox.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link rel="stylesheet" type="text/css" href="css/plugins/perfect-scrollbar/perfect-scrollbar.css">';
  $_PAGE ['links'] .= '<link rel="stylesheet" type="text/css" href="css/util_co.css">';
  $_PAGE ['links'] .= '<link rel="stylesheet" type="text/css" href="css/main_co.css">';

	include_once "header.php";
	include_once "main_menu.php";

	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);
	?>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-2">
		</div>
	</div>
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox">


	<?php if($links !="NOT" || $admin=="1")
	{?>
		<div class="ibox-title">
			<h5><?php echo $title; ?></h5>
		</div>
		<div class="ibox-content">
		<div class="row">

						<div class="col-lg-12">
							<form id="frm_usuario" >
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group has-info single-line">
											<label class="control-label">Nombre Ruta</label>
											<input type="text" class="form-control" name="nombre" id="nombre">
										</div>
									</div>

								</div>
								<div class="row">
									<div class="col-lg-12"><br>
										<div class="alert alert-warning text-center"><h3>Clientes </h3></div>
									</div>
								</div>
								<div class="row">


								<div class="col-lg-6">

										<label for="">Buscar Cliente Individual</label>
										<div id="scrollable-dropdown-menu">
											<input type="text" id="cliente" name="cliente" class="form-control  typeahead" placeholder="Ingrese Cliente" data-provide="typeahead" style="border-radius:0px;width:100% !important">
										</div>


									</div>
							</div>
								<div class="row"></div>
							<div class="row">
								<div class="table col-lg-12">
									<table class="table table-hover table-striped" id="inventable">
										<thead>
											<tr>
												<th class="col-sm-1">ORDEN</th>
												<th class="col-sm-4">NOMBRE</th>
												<th class="col-sm-3">DEPARTAMENTO</th>
												<th class="col-sm-3">MUNICIPIO</th>
												<th class="col-md-1">Acción</th>
											</tr>
										</thead>
										<tbody id="cliente_table">

										</tbody>
									</table>
								</div>
							</div>
								<div class="form-actions">
									<input type="hidden" name="process" id="process" value="insert">
									<input type="button" value="Guardar" class="btn btn-success pull-right" id="btn_ruta">
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
		<?php
	}
	else
	{
		echo "<br><div class='col-lg-12'>
		<div class='row'>
		<div class='col-lg-12'>
		<div class='alert alert-warning'>Usted no tiene permiso para acceder a este modulo</div>
		</div>
		</div>
		</div>";
	}
	include("footer.php");
	echo '<script src="js/funciones/main.js"></script>';
	echo "<script src='js/funciones/util.js'></script>";
	echo "<script src='js/plugins/arrowtable/arrow-table.js'></script>";
	echo "<script src='js/plugins/bootstrap-checkbox/bootstrap-checkbox.js'></script>";
	echo '<script src="js/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>';
	echo "<script type='text/javascript' src='js/funciones/funciones_ruta.js'></script>";
}

function ingresar_datos()
{
	$nombre = $_POST["nombre"];
	//$id_sucursal = $_POST["id_sucursal"];
	$datos= json_decode($_POST["datos"], true);
	$tabla = 'ruta';
	$data_usuario = array(
		'descripcion' => $nombre,
	);
	$sql = _query("SELECT * FROM ruta WHERE descripcion = '$nombre'");
	$dato_existente = _num_rows($sql);
	if($dato_existente>0)
	{
		$xdatos['typeinfo']='Error';
		$xdatos['msg']='La ruta ya se encuentra registrada!';
	}
	else
	{
		$insertar_usuario = _insert($tabla, $data_usuario);
		if($insertar_usuario)
		{
			$id_ruta=_insert_id();
			$table_fav = "ruta_detalle";
			foreach ($datos as $datas)
			{
				$id_cliente = $datas["id_cliente"];
				$orden = $datas["orden"];
				$form_data_fa = array(
					'id_cliente' => $id_cliente,
					'id_ruta' => $id_ruta,
					'orden' => $orden,
				);
				$insert_fa=_insert($table_fav,$form_data_fa);
			}
			$xdatos['typeinfo']='Success';
			$xdatos['msg']='Ruta ingresada correctamente!';
			$xdatos['process']='insert';
		}
		else
		{
			$xdatos['typeinfo']='Error';
			$xdatos['msg']='Ruta no pudo ser ingresada !'._error();
		}
	}
	echo json_encode($xdatos);
}
function traer_cliente()
{
	$id_cliente = $_POST["id_cliente"];
	$sql = _query("SELECT cli.nombre,cli.municipio,cli.depto,m.nombre_municipio,d.nombre_departamento
									FROM cliente as cli
									JOIN departamento as d ON cli.depto=d.id_departamento
									JOIN municipio as m on cli.municipio=m.id_municipio
									WHERE id_cliente='$id_cliente'");
	$dato_existente = _num_rows($sql);

	$tr = "";
	$cliente="";
	$id_departamento="";
	$municipio="";
	$departamento="";
	$id_municipio="";
	if($dato_existente>0)
	{
		$row=_fetch_array($sql);
		$cliente=$row["nombre"];
		$id_municipio=$row["municipio"];
		$id_departamento=$row["depto"];
		$nombre_mun=$row["nombre_municipio"];
		$nombre_dep=$row["nombre_departamento"];


		$xdatos['id_cliente']=$id_cliente;
		$xdatos['cliente']=$cliente;
		$xdatos['id_departamento']=$id_departamento;
		$xdatos['municipio']=$nombre_mun;
		$xdatos['departamento']=$nombre_dep;
		$xdatos['id_municipio']=$id_municipio;
		$xdatos['typeinfo']='Success';
	}
	else
	{
		$xdatos['typeinfo']='Error';
		$xdatos['msg']='Faltan datos(Departamento o Municipio)!';

	}
	echo json_encode($xdatos);
}
function  obtener_clientes()
{
	$id_departamento = $_POST["id_departamento"];
	$sql = _query("SELECT cli.id_cliente,cli.nombre,cli.municipio,cli.depto,m.nombre_municipio,d.nombre_departamento
									FROM cliente as cli
									JOIN departamento as d ON cli.depto=d.id_departamento
									JOIN municipio as m on cli.municipio=m.id_municipio
									WHERE  cli.depto='$id_departamento'"
								);
	$dato_existente = _num_rows($sql);

	$tr = "";
	$cliente="";
	$id_departamento="";
	$municipio="";
	$departamento="";
	$id_municipio="";
	$arr_data= array();
	if($dato_existente>0)
	{
		while (	$row=_fetch_array($sql)) {
						array_push($arr_data,$row);
	    }
	}
	else{
		$xdatos['typeinfo']='Error';
		$xdatos['msg']='Faltan datos(Departamento o Municipio)!';
		array_push($arr_data,"null");
	}
	echo json_encode($arr_data);
}
if(!isset($_POST['process'])){
	formulario();
}
else
{
	if(isset($_POST['process'])){
		switch ($_POST['process']) {
			case 'insert':
			ingresar_datos();
			break;
			case 'traer_cliente':
			traer_cliente();
			case 'obtener_clientes':
			obtener_clientes();
			break;
		}
	}
}


?>
