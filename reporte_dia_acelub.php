<?php
include_once "_core.php";

function initial() {
	// Page setup
	$id_user=$_SESSION["id_usuario"];

	//$_PAGE = array ();
	  $title = ' INFORME DIARIO ACEITES / LUBRICANTES';
	/*
	$_PAGE ['links'] = null;
	$_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/chosen/chosen.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jqGrid/ui.jqgrid.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/fileinput/fileinput.css" media="all" rel="stylesheet" type="text/css"/>';
	$_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';*/
	include_once "_headers.php";
  $_PAGE ['title'] = $title;

	include_once "header.php";
	include_once "main_menu.php";
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];

	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user, $filename);

	$sql_apertura = _query("SELECT * FROM apertura_caja WHERE vigente = 1
    AND id_sucursal = '$id_sucursal' AND fecha='$fecha_actual' AND id_empleado = '$id_user'");
  $cuenta = _num_rows($sql_apertura);
	if ($cuenta>0) {
    $row_apertura = _fetch_array($sql_apertura);
    $id_apertura = $row_apertura["id_apertura"];
    $turno = $row_apertura["turno"];
    $caja = $row_apertura["caja"];
    $fecha_apertura = $row_apertura["fecha"];
    $hora_apertura = $row_apertura["hora"];
    $turno_vigente = $row_apertura["vigente"];
    $dats_caja = getCaja($caja);
    $nombrecaja =$dats_caja['nombre'];
  }

	//permiso del script
	if ($links!='NOT' || $admin=='1' ){
?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-2">
                </div>
            </div>
        <div class="wrapper wrapper-content  animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5>Generar libro Ventas a Consumidores</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group has-info single-line">
                                  <label>Fecha Inicio</label>
                                  <input type="text" class="form-control datepick" id="fini" name="fini" value="<?php echo date('Y-m-d');?>">
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group has-info single-line">
																	  <label>Imprimir Reporte</label><br>
																	  <button type="button" id="submit1" name="submit1"  class="btn btn-primary m-t-n-xs " >Imprimir</button>
                                  <!--label>Fecha Fin</label>
                                  <input type="text" class="form-control datepick" id="ffin" name="ffin" value="<!--?php echo date('Y-m-d');?>"-->
                                </div>
                              </div>
                            </div>
                  				  <input type="hidden" name="process" id="process" value="edit">
                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <input type="hidden" name="id_sucursal" id="id_sucursal">
																	  <input type='hidden' name='id_apertura' id='id_apertura' value='<?php echo $id_apertura; ?>'>
																	<!--input type="submit" id="xls" name="xls" value="EXCEL" class="btn btn-primary m-t-n-xs pull-right" /-->
																	<span class="pull-right">&nbsp</span>

                                </div>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

<?php
include_once ("footer.php");
echo "<script src='js/funciones/reporte_dia_acelub.js'></script>";
		} //permiso del script
else {
		echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div><div></div><div></div>";
		include_once ("footer.php");
	}
}



if(!isset($_POST['process'])){
	initial();
}
else
{
if(isset($_POST['process'])){
switch ($_POST['process']) {
	case 'edit':
		//insertar_empresa();
    editar();
		break;

	}
}
}
?>
