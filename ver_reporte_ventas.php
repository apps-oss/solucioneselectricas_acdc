<?php
include ("_core.php");
// Page setup
function initial()
{
  $title = "Reporte de ventas";
  $_PAGE = array ();
  $_PAGE ['title'] = $title;
  $_PAGE ['links'] = null;
  $_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/typeahead.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/chosen/chosen.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
  include_once "header.php";
  include_once "main_menu.php";
  $id_sucursal=$_SESSION['id_sucursal'];
  $id_user = $_SESSION["id_usuario"];
  date_default_timezone_set('America/El_Salvador');
  $fin = date("d-m-Y");
  $fini ="01-".date("m").'-'.date("Y");
  $hora_actual = date("H:i:s");
  $id_user=$_SESSION["id_usuario"];
  $admin=$_SESSION["admin"];

  $uri = $_SERVER['SCRIPT_NAME'];
  $filename=get_name_script($uri);
  $links=permission_usr($id_user,$filename);
  //permiso del script
  if ($links!='NOT' || $admin=='1' ){
    ?>

    <div class="wrapper wrapper-content  animated fadeInRight">
      <div class="row" id="row1">
        <div class="col-lg-12">
          <div class="ibox float-e-margins">
            <div class="ibox-title">
              <h4><?php echo $title; ?></h4>
            </div>
            <div class="ibox-content">
                <div class="row">
                  <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                    <div class="form-group has-info single-line">
                        <label for="">Tipo de Reporte</label><label for="" id="msg_reporte"></label>
                        <select class='select' name="tipo_reporte" id="tipo_reporte">
                          <option value="">Seleccione el tipo de reporte</option>
                          <option value="creditos_por_vendedor">Reporte de creditos por vendedor</option>
                          <option value="cuentas_cobrar_gen">Reporte de creditos general</option>
                          <option value="reporte_venta_diario">Reporde de venta diaria por vendedor</option>
                          <option value="reporte_venta_gen">Reporte de ventas General</option>
                          <option value="marca_vendedor_gen">Reporte de ventas vendedor por marca</option>
                          <option value="por_mes_total">Reporte general de facturas por mes</option>
                          <option value="por_cliente_vendedor">Reporte de facturas 1 vendedor 1 cliente</option>
                          <option value="cuentas_por_cobrar">Reporte de creditos 1 vendedor 1 cliente</option>
                        </select>
                      </div>
                  </div>

                  <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group has-info single-line" id="conte_vendedor">
                          <label>Buscar Vendedor</label>
                            <label for="" id="msg_vendedor"></label>
                            <input type="text" id="txt_vendedor" name="txt_vendedor" style="width:100% !important" class="form-control usage" placeholder="Nombre vendedor" style="border-radius:0px" disabled>
                          <input id="vendedor" type="hidden" name="vendedor" value="">
                        </div>
                     </div>
                     <div class="col-lg-6">
                        <div class="form-group has-info single-line" id="conte_cliente">
                            <label>Buscar Cliente</label><label for="" id="msg_cliente"></label>
                            <input type="text" id="txt_cliente" name="txt_cliente" style="width:100% !important" class="form-control usage" placeholder="Nombre vendedor" style="border-radius:0px" disabled>
                            <input id="cliente" type="hidden" name="cliente" value="">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group has-info single-line" id="conte_inicio">
                          <label>Fecha Inicio</label><label for="" id="msg_fini"></label>
                          <input type="text"  class="form-control fecha" id="fini" value="<?php echo $fini;?>" disabled>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group has-info single-line" id='conte_fin'>
                        <label>Fecha Fin</label><label for="" id="msg_fin"></label>
                        <input type="text"  class="form-control fecha" id="fin" value="<?php echo $fin;?>" disabled>
                      </div>
                    </div>
                  </div>
                
                </div>
                </div>
                <div class="row">
                  <div class="col-lg-12 form-group">
                    <a class="btn btn-primary pull-right" id="submit" name="submit"><i class="fa fa-print"></i> Imprimir</a>
                    <input type="hidden" id="id_producto">
                  </div>
                </div>
            </div><!--div class='ibox-content'-->
          </div><!--<div class='ibox float-e-margins' -->
          </div> <!--div class='col-lg-12'-->
        </div> <!--div class='row'-->
      </div><!--div class='wrapper wrapper-content  animated fadeInRight'-->
      <?php
      include ("footer.php");
      echo "<script src='js/funciones/funciones_reportes_ventas.js'></script>";
    } //permiso del script
    else
    {
      echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div></div></div></div></div>";
      include ("footer.php");
    }
    ?>
    <script src=""></script>
    <?php
  }
  function consultar_prod()
  {
    $code = intval($_REQUEST['code']);
    $id_sucursal=$_SESSION['id_sucursal'];
      $sql_aux = _query("SELECT id_pp as id_presentacion, id_producto FROM presentacion_producto WHERE id_pp='$code' AND activo='1'");
      if(_num_rows($sql_aux)>0)
      {
        $dats_aux = _fetch_array($sql_aux);
        $id_producto = $dats_aux["id_producto"];
        $id_presentacione = $dats_aux["id_presentacion"];
        $clause = "id_producto = '$code'";
      }
      else
      {
        $clause = "barcode = '$code'";
      }
    $sql1 = "SELECT id_producto, descripcion
             FROM producto
             WHERE $clause";
    $prods=_query($sql1);
    if (_num_rows($prods)>0)
    {
      $row_prod = _fetch_array($prods);
      $xdata["id"] = $row_prod["id_producto"];
      $xdata["descrip"] = $row_prod["descripcion"];
      $xdata["typeinfo"] = "Success";
    }
    else
    {
      $xdata["typeinfo"] = "Error";
      $xdata["msg"] = "El codigo ingresado no pertenece a ningun producto";
    }
    echo json_encode($xdata);
  }
  if(!isset($_POST['process'])){
    initial();
  }
  else
  {
      switch ($_POST['process'])
      {
        case 'cons':
        consultar_prod();
          break;
      }
  }
  ?>
