<?php
include ("_core.php");
// Page setup

/*
$_PAGE = array ();
$_PAGE ['title'] = $title;
$_PAGE ['links'] = null;
$_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
$_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
$_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
$_PAGE ['links'] .= '<link href="css/plugins/chosen/chosen.css" rel="stylesheet">';
$_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
$_PAGE ['links'] .= '<link href="css/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">';
$_PAGE ['links'] .= '<link href="css/plugins/jqGrid/ui.jqgrid.css" rel="stylesheet">';
$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
$_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
$_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';
include_once "header.php";
*/
$title = " Lectura de Bombas de Combustible";
include_once "_headers.php";
$_PAGE ['title'] = $title;
include_once "header.php";
include_once "main_menu.php";

$sql="SELECT * FROM bomba ORDER BY numero ASC";
$result=_query($sql);

//permiso del script
$id_user=$_SESSION["id_usuario"];
$admin=$_SESSION["admin"];

$uri = $_SERVER['SCRIPT_NAME'];
$filename=get_name_script($uri);
$links=permission_usr($id_user, $filename);
$fechahoy=date("Y-m-d");
$fechaanterior=restar_dias($fechahoy,30);
//permiso del script
	?>
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row" id="row1">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					<?php
					if ($links!='NOT' || $admin=='1' ){
					echo"<div class='ibox-title'>";
					$filename='agregar_bomba.php';
					$link=permission_usr($id_user,$filename);
					if ($link!='NOT' || $admin=='1' )
					$button = "<a  data-toggle='modal' href='agregar_lecturas.php'
					class='btn btn-primary' role='button' data-target='#viewModal' data-refresh='true'>
					<i class='fa fa-plus icon-large'></i> Agregar Lecturas</a>";
					//echo $button;
					echo "</div>";
					?>
					<div class="ibox-content">
						<!--load datables estructure html-->
						<header>
							<h4><?php echo $title; ?></h4>
						</header>
						<div class="row">
							<div class="input-group">
								<div class="col-md-4">
									<div class="form-group">
										<label>Fecha Inicio</label>
										<input type="text" placeholder="Fecha Inicio" class="datepick form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo  $fechahoy;?>">
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label>Fecha Fin</label>
										<input type="text" placeholder="Fecha Fin" class="datepick form-control" id="fecha_fin" name="fecha_fin" value="<?php echo $fechahoy;?>">
									</div>
								</div>
								<div class="col-md-4">

									<div class="form-group">
										<div><label>Buscar Lecturas</label> </div>
										<button type="button" id="btnMostrar" name="btnMostrar" class="btn btn-primary"><i class="fa fa-eye"></i> Mostrar </button>
									</div>
								</div>
							</div>
						</div>
						<section>
							<table class="table table-striped table-bordered table-hover" id="editable">
								<thead>
									<tr>
										<th class="col-lg-1">Id</th>
										<th class="col-lg-1">Gal. Diesel</th>
										<th class="col-lg-1">Gal. Regular</th>
										<th class="col-lg-1">Gal. Super</th>
										<th class="col-lg-1">Total Galones</th>
										<th class="col-lg-1">Efect. Diesel $</th>
										<th class="col-lg-1">Efect. Regular $</th>
										<th class="col-lg-1">Efect. Super $</th>
										<th class="col-lg-1">Total Efectivo $</th>
										<th class="col-lg-1">Fecha </th>
										<th class="col-lg-1">Acción</th>
									</tr>
								</thead>
								<tbody>
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
							<div class='modal-dialog'>
								<div class='modal-content modal-sm'></div><!-- /.modal-content -->
							</div><!-- /.modal-dialog -->
						</div><!-- /.modal -->
					</div><!--div class='ibox-content'-->
				</div><!--<div class='ibox float-e-margins' -->
				</div> <!--div class='col-lg-12'-->
			</div> <!--div class='row'-->
		</div><!--div class='wrapper wrapper-content  animated fadeInRight'-->
		<?php
		include ("footer.php");
		echo" <script type='text/javascript' src='js/funciones/lecturas.js'></script>";
	} //permiso del script
	else {
		echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div></div></div></div></div>";
		include ("footer.php");
	}
	?>
