<?php
include("_core.php");
// Page setup
function initial()
{
$title='Administrar Ajuste';
$_PAGE = array();
$_PAGE ['title'] = $title;
$_PAGE ['links'] = null;
$_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
$_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
$_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
$_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
$_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
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

?>
<style media="screen">
span.select2-container {
  z-index:10050;
}
</style>
<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row" id="row1">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<?php
				if ($links!='NOT' || $admin=='1') {
					echo "<div class='ibox-title'>";
					$filename='ajuste_inventario.php';
					$link=permission_usr($id_user, $filename);
					if ($link!='NOT' || $admin=='1') {
            ?>
            <a href="ajuste_inventario.php">
  					<button type="button" class="btn btn-primary" name="button"> <i class="fa fa-plus">Realizar Ajuste</i> </button>
            </a>
            <?php
					}
					echo	"</div>";
					?>
					<div class="ibox-content">
						<!--load datables estructure html-->
						<header>
							<h4>Administrar Ajustes</h4>
						</header>
            <div class="row">
              <div class="col-lg-3">
                <label>Origen</label>
                <select class=" select" style="width:100%" id="origen" name="origen">
                  <?php
                  $sql = _query("SELECT * FROM ubicacion WHERE id_sucursal='$id_sucursal' ORDER BY descripcion ASC");

                  if (isset($_REQUEST['id_origen'])) {
                    # code...
                    while($row = _fetch_array($sql))
                    {
                      if ($row['id_ubicacion']==$_REQUEST['id_origen']) {
                        # code...
                        echo "<option selected value='".$row["id_ubicacion"]."'>".$row["descripcion"]."</option>";
                      }
                      else {
                        # code...
                        echo "<option value='".$row["id_ubicacion"]."'>".$row["descripcion"]."</option>";
                      }

                    }
                  }
                  else {
                    while($row = _fetch_array($sql))
                    {
                        echo "<option value='".$row["id_ubicacion"]."'>".$row["descripcion"]."</option>";
                    }
                  }

                  ?>
                </select>
              </div>
              <div class="col-lg-3">


              </div>
            </div>
            <br>
						<section>
							<table class="table table-striped table-bordered table-hover" id="editable2">
								<thead>
									<tr>
										<th class="col-lg-1">Id</th>
										<th class="col-lg-1">Fecha</th>
                    <th class="col-lg-1">Hora</th>
										<th class="col-lg-3">Concepto</th>
										<th class="col-lg-2">Usuario</th>
                    <th class="col-lg-2">Correlativo</th>
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
        include("footer.php");
                    echo" <script type='text/javascript' src='js/funciones/funciones_admin_ajuste.js'></script>";
                } else {
        echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div></div></div></div></div>";
        include("footer.php");
    }
  }

  if (!isset($_REQUEST['process']))
  {
    initial();
  }
  if (isset($_REQUEST['process']))
  {
    switch ($_REQUEST['process'])
    {
      case 'insert':
      break;
    }
  }
    ?>
