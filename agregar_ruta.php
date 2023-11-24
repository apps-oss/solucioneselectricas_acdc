<?php
include("_core.php");
function formulario()
{
	$title='Administrar ruta';
include_once "_headers.php";
$_PAGE ['title'] = $title;

include_once "header.php";
include_once "main_menu.php";

	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);

	if($links !="NOT" || $admin=="1")
	{
		echo "<style type='text/css'>
			#inventable{
				font-family: 'Open Sans';
				 font-style: normal;
				 font-size: small;
			font-weight: 400;
			src: local('Open Sans'), local('OpenSans'), url(fonts/apache/opensans/OpenSans-Regular.ttf) format('truetype'), url(fonts/apache/opensans/OpenSans.woff) format('woff');
			}
			.table thead tr > th.success{
			background-color: #428bca !important;
			color: white !important;
		}
		.table > tfoot > tr > .thick-line {
			border-top: 2px solid;
		}
		</style>";
		?>
		<br>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class='text-success'><i class="fa fa-user"></i> Registro ruta</h4>
					</div>
					<div class="panel-body">
						<div class="col-lg-12">
							<form class="form-horizontal" id="frm_usuario" autocomplete="off">
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group has-info single-line">
											<label class="control-label">Nombre</label>
											<input type="text" class="form-control" name="nombre" id="nombre">
										</div>
									</div>

								</div>
								<div class="row">
									<div class="col-lg-12"><br>
										<div class="alert alert-warning text-center"><h3>Clientes</h3></div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">

								<div class="col-md-8">
									<div class="form-group has-info">
										<label for="">Cliente</label>
										<div id="scrollable-dropdown-menu">
											<input type="text" name="cliente" id="cliente" class="pull-right form-control  autocomplete typeahead">
										</div>
									</div>
								</div>
							</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<table class="table table-hover table-striped table-bordered" id="inventable">
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
		$cliente=$row["nombre"];

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
			break;
		}
	}
}


?>
