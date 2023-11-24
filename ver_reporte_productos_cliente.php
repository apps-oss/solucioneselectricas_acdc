<?php
include_once "_core.php";
include('num2letras.php');
include('facturacion_funcion_imprimir.php');

function initial()
{
    // Page setup
    $id_user=$_SESSION["id_usuario"];

    $_PAGE = array();
    $_PAGE ['title'] = 'Reporte ventas de producto por cliente y marca';
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
    $_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';

    include_once "header.php";
    include_once "main_menu.php";
    //permiso del script
    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];

    $uri = $_SERVER['SCRIPT_NAME'];
    $filename=get_name_script($uri);
    $links=permission_usr($id_user, $filename);


    //permiso del script
    if ($links!='NOT' || $admin=='1') {
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
          <h5>Reporte productos por cliente y marca</h5>
        </div>
        <div class="ibox-content">
          <input type="hidden" name="process" id="process" value="reporte">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <select class="select2" name="id_cliente" id="id_cliente">
                  <option value="-1">Reporte General (Todos los clientes)</option>
                  <?php
                    $sql_proveedor="SELECT c.id_cliente, c.nombre FROM cliente AS c";
                    $query_proveedor=_query($sql_proveedor);
                    while ($row_proveedor=_fetch_array($query_proveedor)) {
                        echo "<option value=".$row_proveedor['id_cliente'].">".$row_proveedor['nombre']."</option>";
                    } ?>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <select class="select2" name="marca" id="marca">
                  <option value="">Todas las marcas</option>
                  <?php
                    $sql_marca="SELECT DISTINCT marca FROM producto";
                    $query_marca=_query($sql_marca);
                    while ($row_marca=_fetch_array($query_marca)) {
                        echo "<option value=".$row_marca['marca'].">".$row_marca['marca']."</option>";
                    } ?>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="mes_inicial">Fecha inicial</label>
                <input type="date" class="form-control" name="mes_inicial" id="mes_inicial" value="<?= date('Y-m-d') ?>">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="mes_final">Fecha final</label>
                <input type="date" class="form-control" name="mes_final" id="mes_final" value="<?= date('Y-m-d') ?>">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <input type="hidden" name="id_sucursal" id="id_sucursal">
                <input type="submit" id="submit" name="submit" value="PDF" class="btn btn-primary m-t-n-xs" />
              
                <input type="submit" id="submit2" name="submit2" value="XLS" class="btn btn-primary m-t-n-xs" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
include_once("footer.php");
        echo "<script src='js/funciones/reporte_producto_cliente.js'></script>";
    } //permiso del script
    else {
        echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
    }
}


if (!isset($_POST['process'])) {
    initial();
} else {
    if (isset($_POST['process'])) {
        switch ($_POST['process']) {
    case 'edit':
        //insertar_empresa();
    editar();
        break;
    }
    }
}
?>
