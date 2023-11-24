<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    include("_core.php");
    // Page setup
    $_PAGE = array();
    $title='Administrar Factura (Buscar por Fechas)';
    $_PAGE ['title'] = $title;
    $_PAGE ['links'] = null;
    $_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/chosen/chosen.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';
    include_once "header.php";
    include_once "main_menu.php";

    //permiso del script
    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];
    $uri = $_SERVER['SCRIPT_NAME'];
    $filename=get_name_script($uri);
    $links=permission_usr($id_user, $filename);
    $fechahoy=date("Y-m-d");
    $fechaanterior=restar_dias($fechahoy, 15);
?>
<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row" id="row1">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<?php
                $filename='venta.php';
                $link=permission_usr($id_user, $filename);
                echo "<div class='ibox-title'>";
                if ($link!='NOT' || $admin=='1') {
                    echo "<a href='$filename' class='btn btn-primary' role='button'><i class='fa fa-plus icon-large'></i> Agregar factura</a>";
                }
                echo "</div>";
                //permiso del script
                        if ($links!='NOT' || $admin=='1') {
                            echo"
                        <div class='ibox-title'>
                            <h5>$title</h5>
                        </div>"; ?>
				<div class="ibox-content">
					<!--load datables estructure html-->
					<div class="row">
						  <div class="input-group">
								 <div class="col-md-3">
									<div class="form-group">
										<label>Fecha Inicio</label>
										<input type="text" placeholder="Fecha Inicio" class="datepick form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo  $fechaanterior; ?>">
									</div>
                                </div>

                            <div class="col-md-3">
									<div class="form-group">
										<label>Fecha Fin</label>
										<input type="text" placeholder="Fecha Fin" class="datepick form-control" id="fecha_fin" name="fecha_fin" value="<?php echo $fechahoy; ?>">
									</div>
                                </div>
								<div class="col-md-3">
								<?php
								$filename='admin_factura_rangos.php';
								$link=permission_usr($id_user, $filename);
								if ($link!='NOT' || $admin=='1') 
								{
								?>
									<label>Cajas</label>
									<div class="form-group">
										<select class="form-control select" id='caja'>
										<?php 
											/**
											 * validando que solo muestre la opsion de consolidados
											 * si el usuario en sesecion tiene permisos o es admin
											 */
											// $filename='Ver Factura Por Caja';
											$link=permission_usr($id_user, $filename);
											echo ($link!='NOT' || $admin=='1')? "<option value=''>CONSOLIDADO</option>":"";
										?>
										
										<?php
										/**
										 * Funcion creada en _helpers.php la cual evalua si es user
										 * en sesicon en uno de los cajeros autorizados
										 */
										$id_caja = getDatosUser($id_user);
										/**
										 * condicion para mostrar los registros de una solo caja o de 
										 * todas las cajas
										 */
										//$condicion = ($id_caja > 0 && $admin!=1)? " AND `id_caja`='$id_caja'":"";
										$sql = _query("SELECT * FROM `caja` WHERE id_sucursal='$id_sucursal' ORDER BY id_caja ASC ");
										while($row = _fetch_array($sql))
										{
											echo "<option value='".$row["id_caja"]."'>".$row["nombre"]."</option>";
										}
										?>
										</select>
									</div>	
								<?php
								}
								?>
                            </div>
								<div class="col-md-3">
								<div class="form-group">
										<div><label>Buscar Facturas</label> </div>
										<button type="button" id="btnMostrar" name="btnMostrar" class="btn btn-primary"><i class="fa fa-check"></i> Mostrar Facturas</button>

									</div>
                                </div>
                              </div>

					</div>
					<section>
						<table class="table table-striped table-bordered table-hover" id="editable2">
							<thead>
								<tr>
									<th>Id factura</th>
									<th>Numero Doc</th>
									<th>Correlativo</th>
									<th>Tipo Doc</th>
									<th>Cliente</th>
									<th>Empleado</th>
									<th>Fecha</th>
									<th>Total</th>
									<th>Estado</th>									
									<th>Acci&oacute;n</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
						 <input type="hidden" name="autosave" id="autosave" value="false-0">
					</section>
					<!--Show Modal Popups View & Delete -->
					<div class='modal fade' id='viewModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
						<div class='modal-dialog'>
							<div class='modal-content modal-md'></div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->
					<div class='modal fade' id='deleteModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
						<div class='modal-dialog'>
							<div class='modal-content modal-sm'></div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->
					<!--Show Modal Popups View & Delete -->
					<div class='modal fade' id='viewModalFact' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
						<div class='modal-dialog'>
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
                            //echo" <script type='text/javascript' src='js/funciones/util.js'></script>";
                            echo" <script type='text/javascript' src='js/funciones/funciones_fact_rangos.js'></script>";
                            echo '<script src="js/plugins/axios/axios.min.js"></script>';
                        } //permiso del script
else {
    echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
}
?>
