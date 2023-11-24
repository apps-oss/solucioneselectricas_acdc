<?php
include ("_core.php");

include_once "_headers.php";
$title='Aperturas No Finalizadas de Fechas Anteriores ';
$_PAGE ['title'] = $title;
include_once "header.php";
include_once "main_menu.php";

//permiso del script
$id_user=$_SESSION["id_usuario"];
$admin=$_SESSION["admin"];
$uri = $_SERVER['SCRIPT_NAME'];
$filename=get_name_script($uri);
$links=permission_usr($id_user,$filename);

$fechahoy=date("Y-m-d");
$fechaante0=restar_dias($fechahoy,10);
$fechaante1=restar_dias($fechahoy,1);
?>

<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row" id="row1">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<?php
				$filename='venta.php';
				$link=permission_usr($id_user,$filename);
				echo "<div class='ibox-title'>";
				if ($link!='NOT' || $admin=='1' )
				//echo "<a href='venta.php' class='btn btn-primary' role='button'><i class='fa fa-plus icon-large'></i> Agregar venta</a>";
				echo "</div>";
				//permiso del script
				if ($links!='NOT' || $admin=='1' ){
					echo"
					<div class='ibox-title'>
					<h5>$title</h5>
					</div>";
					?>
					<div class="ibox-content">

						<div class="row">
							<div class="input-group">
								<div class="col-md-4">
									<div class="form-group">
										<label>Fecha Inicio</label>
										<input type="text" placeholder="Fecha Inicio" class="datepick form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo  $fechaante0;?>">
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label>Fecha Fin</label>
										<input type="text" placeholder="Fecha Fin" class="datepick form-control" id="fecha_fin" name="fecha_fin" value="<?php echo $fechaante1;?>">
									</div>
								</div>
								<div class="col-md-4">

									<div class="form-group">
										<div><label>Buscar Aperturas</label> </div>
										<button type="button" id="btnMostrar" name="btnMostrar" class="btn btn-primary"><i class="fa fa-check"></i> Aperturas</button>
									</div>
								</div>
							</div>
						</div>
						<section>
							<table class="table table-striped table-bordered table-hover" id="editable2">
								<thead>
									<tr>
										<th class="col-lg-1">Id Apertura</th>
										<th class="col-lg-1">Caja</th>
										<th class="col-lg-2">Empleado</th>
										<th class="col-lg-1">Fecha</th>
										<th class="col-lg-1">Hora</th>
										<th class="col-lg-1">Acci&oacute;n</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
							<input type="hidden" name="autosave" id="autosave" value="false-0">
						</section>
						<!--Show Modal Popups View & Delete -->
						<div class='modal fade' id='viewModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
							<div class='modal-dialog modal-sm'>
								<div class='modal-content modal-sm'></div><!-- /.modal-content -->
							</div><!-- /.modal-dialog -->
						</div><!-- /.modal -->
						<div class='modal fade' id='deleteModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
							<div class='modal-dialog modal-sm'>
								<div class='modal-content modal-sm'></div><!-- /.modal-content -->
							</div><!-- /.modal-dialog -->
						</div><!-- /.modal -->
						<!--Show Modal Popups View & Delete -->
						<div class='modal fade' id='viewModalFact' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
							<div class='modal-dialog modal-md'>
								<div class='modal-content modal-md'></div><!-- /.modal-content -->
							</div><!-- /.modal-dialog -->
						</div><!-- /.modal -->

					</div><!--div class='ibox-content'-->
				</div><!--<div class='ibox float-e-margins' -->
				</div> <!--div class='col-lg-12'-->
			</div> <!--div class='row'-->
		</div><!--div class='wrapper wrapper-content  animated fadeInRight'-->
		<?php
		include("footer.php");
		echo" <script type='text/javascript' src='js/funciones/admin_aperturas.js'></script>";
	} //permiso del script
	else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}

	?>
