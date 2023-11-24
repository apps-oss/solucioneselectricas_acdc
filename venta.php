<?php
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: Wed, 1 Jan 2020 00:00:00 GMT");

 ?>
<?php
include_once "_core.php";
include('num2letras.php');
include('facturacion_funcion_imprimir.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function initial()
{
    $title="Venta";
    include_once "_headers.php";
    $_PAGE ['title'] = $title;
    $_PAGE ['links'] .= '<link rel="stylesheet" type="text/css" href="css/util.css">';
    $_PAGE ['links'] .= '<link rel="stylesheet" type="text/css" href="css/main.css">';
    $_PAGE ['links'] .= '<link rel="stylesheet" type="text/css" href="css/venta_varios.css">';
    $_PAGE ['links'] .= '<link href="css/plugins/autocomplete/autocomplete.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/style_scroll.css" rel="stylesheet">';
    include_once "header.php"; ?>
    <style type="text/css">
        thead tr th{
          position: sticky;
          top: 0;
          z-index: 10;
          background-color: #e3f2fd;
          color: #263238;
        }

        .table-responsive {
          height:400px;
          overflow:scroll;
        }

    </style>
<?php
    //include_once "main_menu.php";
    date_default_timezone_set('America/El_Salvador');
    $fecha_actual = date('Y-m-d');

    $id_sucursal=$_SESSION['id_sucursal'];
    //permiso del script
    $id_user=$_SESSION["id_usuario"];
    $sql_apertura = _query("SELECT * FROM apertura_caja WHERE vigente = 1
    AND id_sucursal = '$id_sucursal' AND fecha='$fecha_actual' AND id_empleado = '$id_user'");
    $cuenta = _num_rows($sql_apertura);

    $turno_vigente=0;
    $nombrecaja = "";
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
    //impuestos
    $sql_iva="SELECT iva,monto_retencion1,monto_retencion10,monto_percepcion FROM empresa";
    $result_IVA=_query($sql_iva);
    $row_IVA=_fetch_array($result_IVA);
    $iva=$row_IVA['iva']/100;
    $monto_retencion1=$row_IVA['monto_retencion1'];
    $monto_retencion10=$row_IVA['monto_retencion10'];
    $monto_percepcion=$row_IVA['monto_percepcion'];
    /////////////////////////////////////////////////////
    $admin=$_SESSION["admin"];
    $uri = $_SERVER['SCRIPT_NAME'];
    $filename=get_name_script($uri);
    $links=permission_usr($id_user, $filename);
    $id_usuario=$id_user;

    $fecha_actual=date("Y-m-d");
    if (isset($_REQUEST['id_pedido'])) {
        $id_pedido = $_REQUEST["id_pedido"];
        $sql_pedido = _query("SELECT co.*, cl.nombre FROM pedido as co, cliente as cl
      WHERE co.id_cliente = cl.id_cliente AND co.id_pedido = '$id_pedido'");
        $cuenta = _num_rows($sql_pedido);
        if ($cuenta != 0) {
            $row_coti = _fetch_array($sql_pedido);
            $nombre = $row_coti["nombre"];
            $id_cliente_bd = $row_coti["id_cliente"];
            $fecha_actual = $row_coti["fecha"];
            $pedido = $id_pedido;
        }
    } else {
        $nombre = "";
        $id_cliente_bd = "";
        $fecha_actual = date("Y-m-d");
        $pedi = "";
    } ?>
<div class="gray-bg">
  <div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
      <div class="col-lg-12">
        <div class="ibox ">
          <?php
          //permiso del script
          if ($links!='NOT' || $admin=='1') {
              if ($turno_vigente=='1') {
                  ?>
              <div class="ibox-content">
                 <div class='row'><!--divisionsup -->
                      <div class='col-md-9'><!--col-md-9 -->
                          <div class="row focuss">
                              <input type="hidden" id="fecha" value="<?php echo $fecha_actual; ?>">
                              <br>
                              <div id="b"  class="col-md-6">
                                  <div class="search-element">
                                            <label>Buscar Producto (Por Descripción ó Código)</label>
                                            <div class="auto-search-wrapper max-height loupe">
                                              <input type="text" placeholder="Buscar" class="form-control" id="producto_buscar" name="producto_buscar" value="">
                                          </div>
                                  </div>
                              </div>
                              <div  class="form-group col-md-2">
                                  <div class="form-group has-info">
                                    <label>Referencia</label>
                                    <input type="text" class="form-control" id="num_ref" name="num_ref" value="">
                                  </div>
                              </div>
                              <div class="form-group col-md-2">
                                    <label>Referencia</label>
                                    <select class="form-control select_r" name="n_ref" id="n_ref">
                                      <option value="0">Seleccione</option>
                                      <?php
                                      $fecha_actual = date("Y-m-d");
                  $sql="SELECT cliente.nombre, factura.total,numero_ref
                                                          FROM factura JOIN cliente ON cliente.id_cliente=factura.id_cliente
                                                          WHERE numero_ref!=0 AND fecha='".date("Y-m-d")."'
                                                          AND finalizada!=1 AND factura.id_sucursal=$id_sucursal";

                  $result=_query($sql);
                  $cuenta = _num_rows($result);
                  echo _error();
                  if ($cuenta > 0) {
                      while ($row = _fetch_array($result)) {
                          echo "<option value='".$row['numero_ref']."'>";
                          echo "".$row['numero_ref']." | ";
                          echo "".utf8_decode(Mayu(utf8_decode($row['nombre'])))." | ";
                          echo "$".number_format($row['total'], 2)."";
                          echo "</option>";
                      }
                  } ?>
                                  </select>
                            </div>
                              <div class='col-md-2' hidden>
                                <label id='buscar_habilitadox'>Descripción adicional Cliente</label>
                                <input  type="text" class="form-control" id="extra_nombre" name="extra_nombre" value="">
                              </div>
                              <div class='col-md-3' hidden>
                                <h5 id='txtcaja' class=' text-success'>Caja: <?php echo $nombrecaja ?> </h5>
                              </div>

                        </div>
                        <div class="row">
                            <div id='form_datos_cliente' class="form-group col-md-3">
                              <div class="form-group has-info">
                                    <label>Cliente&nbsp;</label>
                                    <select class="form-control select usage" name="id_cliente" id="id_cliente">
                                      <option value=''>seleccione</option>
                                      <?php
                                                        $sqlcli=_query("SELECT * FROM cliente  ORDER BY nombre");
                  while ($row_cli = _fetch_array($sqlcli)) {
                      echo "<option value='".$row_cli["id_cliente"]."'";
                      if ($id_cliente_bd != "") {
                          if ($row_cli["id_cliente"] == $id_cliente_bd) {
                              echo " selected ";
                          } else {
                              if ($row_cli["id_cliente"] == -1) {
                                  echo " selected ";
                              }
                          }
                      } else {
                          if ($row_cli["id_cliente"] == -1) {
                              echo " selected ";
                          }
                      }

                      echo ">".$row_cli["nombre"]."</option>";
                  } ?>
                                    </select>
                              </div>
                            </div>
                            <div  hidden  class="col-md-3 form-group">
                              <label>Vendedor</label>
                              <div> <h3 id='nombreVendedor'  class="text-info"><h3></div>
                              <input type="hidden" name="id_vendor" id="id_vendor" value="">
                            </div>
                          <div  class="form-group col-md-3">
                            <div class="form-group has-info">
                              <label>Seleccione Vendedor</label><br>
                              <select name='vendedor' id='vendedor' class=' select2 select form-control'>
                                <?php
                                $q_emp="SELECT empleado.id_empleado,
                                concat(empleado.nombre,' ',empleado.apellido) AS nombre
                                FROM empleado WHERE id_tipo_empleado=2 ";
                  $sql=_query($q_emp);
                  while ($row=_fetch_array($sql)) {
                      ?>
                                              <option value="<?php echo $row['id_empleado'] ?>"><?php echo $row['nombre'] ?></option>
                                              <?php
                  } ?>
                              </select>
                            </div>
                          </div>
                          <div  class="form-group col-md-2">
                              <div class="form-group has-info">
                                <label>Tipo Impresi&oacuten</label>
                                    <?php
                                      $array1=getTipoDocCliente();
                  $select=crear_select("tipo_impresion", $array1, 'TIK', "width:100%;");
                  echo $select; ?>
                              </div>
                          </div>
                        <div  hidden class="col-md-2">
                          <div class="form-group has-info">
                            <label>Tipo de pago</label><br>
                            <?php
                              $array2=getTipoPago();
                  $select2=crear_select("con_pago", $array2, 'CON', "width:100%;");
                  echo $select2; ?>
                          </div>
                        </div>
                        <div  class="form-group col-md-2">
                          <div class="form-group has-info">
                            <label>Numero doc. Factura</label>
                            <input maxlength="8" type="text" class="form-control" id="numdoc" name="numdoc" value="">
                          </div>
                        </div>

                        </div>
                      </div><!--col-md-9 -->
                      <div class='col-md-3'>
                             <div class='row'>
                                  <div class="col-md-12">
                                            <br>
                                            <a class="btn btn-sm btn-danger pull-right" style="margin-left:1%;" href="dashboard.php" id='salir'><i class="fa fa-mail-reply"></i> F4 Salir</a>
                                            <button type="button" id="borrar_preven" style="margin-left:1%;" name="borrar_preven" class="btn btn-sm btn-success pull-right usage"><i class="fa fa-trash"></i> F6 Borrar </button>

                                            <?php
                                            if (isset($_REQUEST['id_pedido'])) {
                                                echo '<button type="button" id="btn_pedido" name="btn_pedido" class="btn btn-sm btn-primary pull-right usage"><i class="fa fa-check"></i> Pagar</button>';
                                            } else {
                                                echo '<button type="button" id="submit1" name="submit1" class="btn btn-sm btn-primary pull-right usage"><i class="fa fa-print"></i> F2 Imprimir</button>';
                                            } ?>
                                    </div>
                                    <div class="col-md-12">  <br>
                                                          <button type="button" id="plus_servicio" style="margin-right :1%;" name="plus_servicio" class="btn btn-sm btn-success pull-right"><i class="fa fa-plus"></i> Servicio</button>
                                                      <?php
                                                      $filename='agregar_ingreso_caja.php';
                  $link=permission_usr($id_user, $filename);
                  if ($link!='NOT' || $admin=='1') {
                      echo "<a id='xa' data-toggle='modal' href='agregar_ingreso_caja_v.php'  style='margin-right:1%;'  data-target='#viewModal2' data-refresh='true' class='btn btn-sm btn-warning pull-right'><i class='fa fa-plus icon-large'></i> F10 Ingreso</a>";
                  }
                  $filename='agregar_salida_caja.php';
                  $link=permission_usr($id_user, $filename);
                  if ($link!='NOT' || $admin=='1') {
                      echo "<a id='xb' data-toggle='modal' href='agregar_salida_caja_v.php' style='margin-right:1%;'
                      data-target='#salidaModal' data-refresh='true' class='btn btn-sm btn-danger pull-right'>
                      <i class='fa fa-minus icon-large'></i> F9 Vale</a>";
                  } ?>
                                    </div>
                                    <div class="col-md-12">  <br>  <!--label class='pull-right'>Datos Crédito cliente</!--label> <br-->
                                    <button type="button" id="btnModalDescuento"   name="btnModalDescuento" style="margin-right :1%;" class="btn btn-sm btn-primary   btn-outline  pull-right"><i class="fa fa-money"></i> F4 Desc</button>
                                      <button type="button" id="btnModalDataCliente"   name="btnModalDataCliente" style="margin-right :1%;" class="btn btn-sm btn-success pull-right"><i class="fa fa-eye"></i>  Cliente</button>
                                    </div>
                              </div>
                      </div><!--col-md-3 -->
                </div><!--divisionsup -->

            </div>


          <!--load datables estructure html-->
          <header>
            <section>
              <input type='hidden' name='porc_iva' id='porc_iva' value='<?php echo $iva; ?>'>
              <input type='hidden' name='monto_retencion1' id='monto_retencion1' value='<?php echo $monto_retencion1 ?>'>
              <input type='hidden' name='monto_retencion10' id='monto_retencion10' value='<?php echo $monto_retencion10 ?>'>
              <input type='hidden' name='monto_percepcion' id='monto_percepcion' value='100'>
              <input type='hidden' name='porc_retencion1' id='porc_retencion1' value=0>
              <input type='hidden' name='porc_retencion10' id='porc_retencion10' value=0>
              <input type='hidden' name='porc_percepcion' id='porc_percepcion' value=0>
              <input type='hidden' name='porcentaje_descuento' id='porcentaje_descuento' value=0>
              <input type="hidden" name="precio_aut" id="precio_aut" value="0">
              <input type="hidden" name="cliente_retiene" id="retiene" value="0">
              <input type="hidden" name="clave" id="clave" value="">

              <div class="">
                <div class="row">
                  <div class="col-md-9">
                    <div class="wrap-table1001">
                      <div class="table100 ver1 m-b-10">
                      <div class="table100-head">
                          <table id="inventable1">
                            <thead>
                              <tr class="row100 head">
                                <th hidden class="success cell100 column10">Id</th>
                                <th class='success  cell100 column30'>Descripci&oacute;n</th>
                                <th class='success  cell100 column10'>Stock</th>
                                <th class='success  cell100 column10'>Cantidad</th>
                                <th class='success  cell100 column10'>Presentación</th>
                                <th hidden class='success  cell100 column10'>Descripción</th>
                                <th class='success cell100 column10'>Precios</th>
                                <th  class='success cell100 column10'>$</span> </th>
                                <th class='success  cell100 column10'>Subtotal</th>
                                <th class='success  cell100 column10'>Acci&oacute;n</th>
                              </tr>
                            </thead>
                          </table>
                        </div>
                        <div class="table100-body js-pscroll">
                          <table>
                            <tbody id="inventable">
                              <?php
                              if (isset($_REQUEST['id_pedido'])) {
                                  $id_pedido = $_REQUEST["id_pedido"];
                                  // echo $id_pedido;
                                  $sql_detalle = _query("SELECT * FROM pedido_detalle WHERE id_pedido = '$id_pedido'");

                                  $cuenta_pe = _num_rows($sql_detalle);
                                  // echo "SELECT * FROM pedido_detalle WHERE id_pedido = '$id_pedido'";
                                  if ($cuenta_pe > 0) {
                                      $tr_add = "";
                                      while ($row_p = _fetch_array($sql_detalle)) {
                                          $id_producto = $row_p["id_prod_serv"];
                                          $cantidad = $row_p["cantidad"];
                                          $precio_venta = $row_p["precio_venta"];
                                          $id_presentacion = $row_p["id_presentacion"];
                                          $id_sucursal_bd = $row_p["id_sucursal"];
                                          $unidad = $row_p["unidad"];
                                          //  AND u.bodega=0
                                          $sql1 = "SELECT p.id_producto,p.id_categoria, p.barcode, p.descripcion, p.estado, p.perecedero, p.exento, p.id_categoria, p.id_sucursal,SUM(su.cantidad) as stock
                                    FROM producto AS p
                                    JOIN stock_ubicacion as su ON su.id_producto=p.id_producto
                                    JOIN ubicacion as u ON u.id_ubicacion=su.id_ubicacion
                                    WHERE p.id_producto = '$id_producto'

                                    AND su.id_sucursal=$id_sucursal_bd";
                                          $stock1=_query($sql1);
                                          $row1=_fetch_array($stock1);
                                          $nrow1=_num_rows($stock1);
                                          if ($nrow1>0) {
                                              if ($row1["descripcion"] != "" && $row1["descripcion"] != null) {
                                                  $id_productov = $row1['id_producto'];
                                                  $id_producto = $row1['id_producto'];
                                                  $sql_exis = _query("SELECT stock FROM stock WHERE id_producto = '$id_productov'");
                                                  $datos_exis = _fetch_array($sql_exis);
                                                  $stockv = $datos_exis["stock"];
                                                  if (intval($stockv) > 0) {
                                                      $hoy=date("Y-m-d");
                                                      $perecedero=$row1['perecedero'];
                                                      $barcode = $row1["barcode"];
                                                      $descripcion = $row1["descripcion"];
                                                      $estado = $row1["estado"];
                                                      $perecedero = $row1["perecedero"];
                                                      $exento = $row1["exento"];
                                                      $categoria=$row1['id_categoria'];
                                                      $i=0;
                                                      $unidadp=0;
                                                      $preciop=0;
                                                      $descripcionp=0;
                                                      $select_rank="<select class='sel_r form-control'>";
                                                      $anda = "";
                                                      if ($id_presentacion > 0) {
                                                          $anda = "AND presentacion_producto.id_presentacion = '$id_presentacion'";
                                                      }
                                                      $sql_p=_query("SELECT presentacion.nombre, presentacion_producto.descripcion,presentacion_producto.id_pp as id_presentacion,presentacion_producto.unidad,presentacion_producto.precio
                                          FROM presentacion_producto
                                          JOIN presentacion ON presentacion.id_presentacion=presentacion_producto.id_presentacion
                                          WHERE presentacion_producto.id_producto='$id_producto'
                                          AND presentacion_producto.activo=1
                                          $anda
                                          ORDER BY presentacion_producto.unidad ASC");
                                                      $select="<select class='sel form-control'>";
                                                      while ($row=_fetch_array($sql_p)) {
                                                          if ($i==0) {
                                                              $id_press=$row["id_presentacion"];
                                                              $unidadp=$row['unidad'];
                                                              $preciop=$row['precio'];
                                                              $descripcionp=$row['descripcion'];

                                                              $preciosArray = _getPrecios($id_press, $precio_venta);
                                                              $xc=0;
                                                              foreach ($preciosArray as $key => $value) {
                                                                  // code...
                                                                  if ($value>0) {
                                                                      $select_rank.="<option value='$value'";
                                                                      if ($xc==0) {
                                                                          $select_rank.=" selected ";
                                                                          $preciop=$value;
                                                                          $xc = 1;
                                                                      }
                                                                      $select_rank.=">$value</option>";
                                                                  }
                                                              }
                                                              if ($xc==0) {
                                                                  $select_rank.="<option value='0.0'>0.0</option>";
                                                              }
                                                              //$select_rank.="<option value='0.0'>0.0</option>";
                                                              $select_rank.="</select>";
                                                          }
                                                          $select.="<option value='".$row["id_presentacion"]."'";
                                                          if ($id_presentacion == $row["id_presentacion"]) {
                                                              $select.=" selected ";
                                                          }
                                                          $select.=">$row[nombre]</option>";
                                                          $i=$i+1;
                                                      }
                                                      $select.="</select>";
                                                      $sql_e=_fetch_array(_query("SELECT exento FROM producto WHERE id_producto=$id_producto"));
                                                      $exento=$sql_e['exento'];
                                                      if ($exento==1) {
                                                          # code...

                                                          $xdatos['preciop_s_iva']=$preciop;
                                                      } else {
                                                          # code...
                                                          $sqkl=_fetch_array(_query("SELECT iva FROM sucursal WHERE id_sucursal=$id_sucursal"));
                                                          $iva=$sqkl['iva']/100;
                                                          $iva=1+$iva;
                                                          $preciop_s_iva= round(($preciop/$iva), 8, PHP_ROUND_HALF_DOWN);
                                                      }
                                                      $btnView= "<a data-toggle='modal' href='ver_imagen.php?id_producto='".$id_producto."'  data-target='#viewProd' data-refresh='true' class='btn btn-primary btnViw fa'><i class='fa fa-eye'></i></a>";
                                                      $tr_add .="<tr  class='row100 head' id=''>";
                                                      $tr_add .="<td hidden class='cell100 column10 text-success id_pps'><input type='hidden' id='unidades' name='unidades' value='".$unidad."'>".$id_producto."</td>";
                                                      $tr_add .="<td class='cell100 column20 text-success'>" .$descripcion." ".$exento.'</td>';
                                                      $tr_add .="<td class='cell100 column10 text-success' id='cant_stock'>".$stockv."</td>";
                                                      $tr_add .="<td class='cell100 column10 text-success'><div class='col-xs-2'><input type='text'  class='txt_box decimal2 " .$categoria. " cant' id='cant' name='cant' value='' style='width:60px;'></div></td>";
                                                      //$tr_add .="<td class='cell100 column10 text-success'><div class='col-xs-2'><input type='text'  class='txt_box decimal2 " .$categoria. " bonificacion' id='bonificacion' name='bonificacion' value='' style='width:60px;'></div></td>";
                                                      $tr_add .="<td class='cell100 column10 text-success preccs'>".$select."</td>";
                                                      $tr_add .="<td hidden class='cell100 column10 text-success descp'><input type'text' id='dsd' class='form-control' value='".$descripcionp."' class='txt_box' readonly></td>";
                                                      $tr_add .="<td class='cell100 column10 text-success rank_s'>".$select_rank."</td>";
                                                      $tr_add .="<td class='cell100 column10 text-success'><input type='hidden'  id='precio_venta_inicial' name='precio_venta_inicial' value='".$preciop."'><input type='hidden'  id='precio_sin_iva' name='precio_sin_iva' value='".$preciop_s_iva."'><input type='text'  class='form-control decimal' readOnly id='precio_venta' name='precio_venta' value='".$preciop."'></td>";
                                                      $tr_add .="<td class='ccell100 column10'><input type='hidden'  id='subtotal_fin' name='subtotal_fin' value='0.00'><input type='text'  class='decimal txt_box form-control' id='subtotal_mostrar' name='subtotal_mostrar'  value='0.00' readOnly></td>";
                                                      $tr_add .="<td hidden class='cell100 column10 text-success id_pps'><input type='hidden' id='subt_bonifica' name='subt_bonifica' value='0'></td>";
                                                      $tr_add .='<td class="cell100 column10 Delete text-center"><input id="delprod" type="button" class="btn btn-danger fa"  value="&#xf1f8;">&nbsp;'.$btnView.' </td>';
                                                      $tr_add .='</tr>';
                                                  }
                                              }
                                          }
                                      }
                                      echo $tr_add;
                                  }
                              } ?>
                            </tbody>
                          </table>
                        </div>
                        <div class="table101-body">
                          <table>
                            <tbody>
                              <tbody id="totals">
                                <?php $id_imp  ="<input type='hidden' id='id_imp' name='id_imp' value='-1' />"?>
                                <tr id='ng'>
                                <td class="cell100 column70  leftt text-bluegrey "><?php echo $id_imp?> SUMAS $:</td>
                                <td class='cell100 column30 text-right text-green' id='total_gravado'>0.00</td>
                                </tr>
                                <tr>
                                  <td class="cell100 column70 leftt  text-bluegrey "><?php echo $id_imp?>IVA  $</td>
                                  <td class="cell100 column30 text-right text-green " id='total_iva'>0.00</td>
                                </tr>
                                <tr>
                                <td class="cell100 column70  leftt text-bluegrey "><?php echo $id_imp?>SUBTOTAL $</td>
                                <td class="cell100 column30 text-right  text-green" id='total_gravado_iva'>0.00</td>
                              </tr>
                              <tr>
                                <td class="cell100 column25 leftt text-bluegrey "><?php echo $id_imp?>PERCEPCION $:</td>
                                <td class="cell100 column25 text-right  text-green"  id='total_percepcion'>0.00</td>
                                <td class="cell100 column25 leftt  text-bluegrey "><?php echo $id_imp?>NO SUJETA $</td>
                                <td class="cell100 column25  text-right text-green" id='total_nosujeta'>0.00</td>
                              </tr>
                              <tr>
                                <td class="cell100 column25  leftt  text-bluegrey "><?php echo $id_imp?>RETENCION $:</td>
                                <td class="cell100 column25 text-right text-green" id='total_retencion'>0.00</td>
                                <td class="cell100 column25 leftt  text-bluegrey "><?php echo $id_imp?>V. EXENTA $</td>
                                <td class="cell100 column25  text-right text-green" id='total_exenta'>0.00</td>
                              </tr>
                              <tr>
                                <td hidden></td>
                                <td hidden  id='total_bonifica'>0.00</td>
                                <td hidden class="cell100 column25 leftt text-bluegrey "><?php echo $id_imp?>GRAVADO $</td>
                                <td hidden class="cell100 column25 text-right text-green" id='total_gravado_sin_iva'>0.00</td>
                              </tr>
                              <tr>
                                <td hidden class="cell100 column15 leftt text-bluegrey "><?php echo $id_imp?>DESCUENTO $:</td>
                                <td hidden class="cell100 column10  text-right text-green"  id='total_final'>0.00</td>
                                <td class="cell100 column70 leftt  text-bluegrey"><?php echo $id_imp?>TOTAL $:</td>
                                <td class="cell100 column30  text-right text-green"  id='monto_pago'>0.00</td>
                              </tr>
                              <tr>
                                <td class='cell100 column25 leftt  text-bluegrey '><?php echo $id_imp?>CANT. PROD:</td>
                                <td class='cell100 column25 text-right text-danger' id='totcant'>0.00</td>
                                <td class='cell100 column50 text-bluegrey'  id='totaltexto'>&nbsp;</td>
                              </tr>
                              <!--tr>
                                <td class='cell100 column50 text-bluegrey'  id='totaltexto'>&nbsp;</td>
                                <td class='cell100 column15 leftt  text-bluegrey '>CANT. PROD:</td>
                                <td class='cell100 column10 text-right text-danger' id='totcant'>0.00</td>
                                <td class="cell100 column10  leftt text-bluegrey ">SUMAS $:</td>
                                <td class='cell100 column15 text-right text-green' id='total_gravado'>0.00</td>
                              </tr>
                              <tr>
                                <td class="cell100 column11  leftt text-bluegrey">SUBT. BONI $</td>
                                <td class='cell100 column9 text-right text-green' id='total_bonifica'>0.00</td>
                                <td class="cell100 column10 leftt text-bluegrey ">GRAVADO $</td>
                                <td class="cell100 column10 text-right text-green" id='total_gravado_sin_iva'>0.00</td>
                                <td class="cell100 column6 leftt  text-bluegrey ">IVA  $</td>
                                <td class="cell100 column10 text-right text-green " id='total_iva'>0.00</td>
                                <td class="cell100 column11  leftt text-bluegrey ">SUBTOTAL $</td>
                                <td class="cell100 column10 text-right  text-green" id='total_gravado_iva'>0.00</td>
                                <td class="cell100 column13 leftt  text-bluegrey ">V. EXENTA $</td>
                                <td class="cell100 column10  text-right text-green" id='total_exenta'>0.00</td>
                              </tr>
                              <tr>
                                <td class="cell100 column15 leftt text-bluegrey ">PERCEPCION $:</td>
                                <td class="cell100 column10 text-right  text-green"  id='total_percepcion'>0.00</td>
                                <td class="cell100 column15  leftt  text-bluegrey ">RETENCION $:</td>
                                <td class="cell100 column10 text-right text-green" id='total_retencion'>0.00</td>
                                <td class="cell100 column15 leftt text-bluegrey ">DESCUENTO $:</td>
                                <td class="cell100 column10  text-right text-green"  id='total_final'>0.00</td>
                                <td class="cell100 column15 leftt  text-bluegrey">A PAGAR $:</td>
                                <td class="cell100 column10  text-right text-green"  id='monto_pago'>0.00</td>
                              </tr-->
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="wrap-table1001">
                      <div class="table100 ver1 m-b-10">
                        <div class="table100-head">
                          <table id="inventable1">
                            <thead>
                              <tr class="row100 head">
                                <th class="success cell100 column100 text-center">DATOS FACTURA</th>
                              </tr>
                            </thead>
                          </table>
                        </div>
                        <div class="table101-body">
                          <table>
                            <tbody>
                              <tr>
                                <td class='cell100 column50  text-success'>FECHA:</td>
                                <td class='cell100 column50 '><input type="text" id="fecha_fact" class="txt_box2"  value="<?php echo $fecha_actual; ?>"></td>
                              </tr>

                              <tr>
                                <td class='cell100 column50  text-success'>TOTAL: $</td>
                                <td class='cell100 column50 '><input type="text" id="tot_fdo" class="txt_box2"   value="" readOnly></td>
                              </tr>

                              <tr>
                                <td class='cell100 column50  text-success'>CLIENTE: </td>
                                <td class='cell100 column50 '><input type="text" id="nomcli" class="txt_box2"  value="" readOnly></td>
                              </tr>
                              <tr>
                                <td class='cell100 column50  text-success'>DIRECCION: </td>
                                <td class='cell100 column50 '><input type="text" id="dircli" class="txt_box2"  value="" readOnly></td>
                              </tr>
                              <tr>
                                <td class='cell100 column50  text-success'>NIT: </td>
                                <td class='cell100 column50 '><input type="text" id="nitcli" class="txt_box2"    value="" readOnly></td>
                              </tr>
                              <tr>
                                <td class='cell100 column50  text-success'>NRC: </td>
                                <td class='cell100 column50 '><input type="text" id="nrccli" class="txt_box2"   value="" readOnly></td>
                              </tr>
                              <tr>
                                  <td class="cell100 column50   text-success ">SUBTOTAL VTA. $:</td>
                                  <td class='cell100 column50  text-right text-green'><input type="text" id="total_gravado2" class="txt_box2"   value="" readOnly></td>
                              </tr>
                              <tr hidden>
                                <td class="cell100 column50    text-success">SUBT. BONI $</td>
                                <td class='cell100 column50  text-right text-green' ><input type="text" id='total_bonifica2' class="txt_box2"   value="" readOnly> </td>
                              </tr>
                              <tr hidden>
                                <td class="cell100 column50    text-success">VENTA - BONI $</td>
                                <td class='cell100 column50  text-right text-green' ><input type="text" id='subtotal_menos_boni' class="txt_box2"   value="" readOnly> </td>
                              </tr>
                              <tr>
                                <td class="cell100 column50    text-success"> IVA $</td>
                                <td class='cell100 column50  text-right text-green' ><input type="text" id='total_iva2' class="txt_box2"   value="" readOnly> </td>
                              </tr>
                              <tr>
                                <td class="cell100 column50    text-success"> DESCUENTO $</td>
                                <td class='cell100 column50  text-right text-green' >
                                  <input type="text" id='total_descuento' name='total_descuento' class="txt_box2"  value="" readOnly>
                                </td>
                              </tr>
                              <tr>
                                  <td class="cell100 column50    text-success ">TOTAL FIN $:</td>
                                  <td class='cell100 column50  text-right text-green'>
                                    <input type="text"  id='total_monto_final' class="txt_box2"   value="" readOnly>
                                  </td>
                              </tr>
                             <input type="hidden" id="efectivov"   value="0">
                             <input type="hidden" id="cambiov"   value="0" readOnly>

                            </tbody>
                          </table>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
                <?php
                  echo "<input type='hidden' name='id_empleado' id='id_empleado' >";
                  echo "<input type='hidden' name='numero_doc' id='numero_doc' >";
                  echo "<input type='hidden' name='id_factura' id='id_factura' >";
                  echo "<input type='hidden' name='urlprocess' id='urlprocess' value='$filename'>"; ?>
                <input type='hidden' name='totalfactura' id='totalfactura' value='0'>
                <input type="hidden" id="imprimiendo" name="imprimiendo" value="0">

                <input type='hidden' name='id_apertura' id='id_apertura' value='<?php echo $id_apertura; ?>'>
                <input type='hidden' name='turno' id='turno' value='<?php echo $turno; ?>'>
                <input type='hidden' name='filas' id='filas' value='0'>
                <input type='hidden' name='caja' id='caja' value='<?php echo $caja; ?>'>
              </div>
              <!--div class="table-responsive m-t"-->
            </section>

          </div>
          <!--div class='ibox-content'-->
          <!-- Modal -->
          <div class='modal fade' id='viewProd' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
            <div class='modal-dialog'>
              <div class='modal-content'></div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->
          <div class='modal fade' id='salidaModal' style="overflow:hidden;" role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
            <div class='modal-dialog modal-sm'>
              <div class='modal-content modal-sm'></div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->
          <div class='modal fade' id='viewModal2' style="overflow:hidden;" role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
            <div class='modal-dialog modal-sm'>
              <div class='modal-content modal-sm'></div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->
          <div class='modal fade' id='busqueda' style="overflow:hidden;" role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
            <div class='modal-dialog modal-sm'>
              <div class='modal-content modal-sm'></div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->
          <div class="modal-container">
            <div class="modal fade" id="clienteModal" tabindex="-2" role="dialog" aria-labelledby="myModalCliente" aria-hidden="true">
              <div class="modal-dialog model-sm">
                <div class="modal-content"> </div>
              </div>
            </div>
          </div>
          <?php modalPago();
                  modalDataCliente();
                  modalDescuento(); ?>

        </div>
        <!--<div class='ibox float-e-margins' -->
      </div>
      <!--div class='col-lg-12'-->
      <!--div class='row'-->
      <!--div class='wrapper wrapper-content  animated fadeInRight'-->

      <?php
              }   //apertura de caja
              else {
                  echo "<br><br><div class='alert alert-warning'><h3 class='text-danger'> No Hay Apertura de Caja vigente para este turno!!! aperture <a href='admin_corte.php'>aquí</a>  </h3></div></div></div></div></div>";
                  include_once("footer.php");
              }  //apertura de caja
              include_once("footer.php");
              $uniqueId=uniqidReal();
              echo '<script src="js/plugins/axios/axios.min.js"></script>';
              echo "<script src='js/plugins/cellNavigate.js'></script>";
              echo "<script src='js/plugins/arrowtable/arrow-table.js'></script>";
              echo "<script src='js/plugins/bootstrap-checkbox/bootstrap-checkbox.js'></script>";
              echo '<script src="js/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
              <script src="js/funciones/main.js"></script>';
              echo "<script src='js/plugins/sweetalert/sweetalert2.all.min.js'></script>";
              echo '<script src="js/plugins/autocomplete/autocomplete.min.js"></script>';
              echo "<script src='js/funciones/venta.js?v=$uniqueId'></script>";
          //echo "<script src='js/funciones/util.js'></script>";
          } //permiso del script
  else {
      echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div></div></div></div></div>";
      include_once("footer.php");
  }
}

function cargar_data()
{
    $id_sucursal = $_SESSION["id_sucursal"];
    $n_ref = $_POST["n_ref"];
    $fecha = date("Y-m-d");
    /////////////////////// FACTURA
    $sql_fact="SELECT factura.id_factura, factura.id_cliente,factura.id_empleado,
  factura.fecha,  factura.numero_doc, factura.tipo_documento, factura.precio_aut,factura.clave
  FROM factura WHERE numero_ref = $n_ref AND fecha = '$fecha' AND finalizada != 1";
    //echo $sql_fact;
    $result_fact=_query($sql_fact);
    $count_fact=_num_rows($result_fact);

    if ($count_fact > 0) {
        $row_fact=_fetch_array($result_fact);
        $fecha=$row_fact['fecha'];
        $id_factura = $row_fact["id_factura"];
        $numero_doc=$row_fact['numero_doc'];
        $alias_tipodoc = $row_fact["tipo_documento"];
        $tipo_documento=$row_fact['tipo_documento'];
        $id_empleado=$row_fact['id_empleado'];

        $precio_aut = $row_fact['precio_aut'];
        $clave = $row_fact['clave'];

        $id_usuario = $_SESSION['id_usuario'];

        $r_precios=_fetch_array(_query("SELECT precios FROM usuario WHERE id_usuario=$id_usuario"));
        $precios=$r_precios['precios'];

        if ($precio_aut>$precios) {
            // code...
            $precios=$precio_aut;
        }

        /////////////////////////CLIENTE
        $sql_cliente1="SELECT cliente.id_cliente,cliente.retiene,cliente.retiene10,factura.id_factura,
        factura.fecha,factura.tipo_documento,cliente.nombre
        FROM cliente,factura
        where id_factura='$id_factura'
        and factura.id_cliente=cliente.id_cliente
        ORDER BY cliente.nombre";
        //echo $sql_cliente1;
        $id_cliente=0;
        $nombre_cliente = "";
        $retencion1=0;
        $retencion10=0;

        $qcliente=_query($sql_cliente1);
        while ($row_cliente=_fetch_array($qcliente)) {
            $id_cliente=$row_cliente['id_cliente'];
            $tipo_documento=$row_cliente['tipo_documento'];
            $nombre_cliente=$row_cliente['nombre'];
            if ($row_cliente['retiene']==1) {
                # code...
                $retencion1=0.01;
            }
            if ($row_cliente['retiene10']==1) {
                # code...
                $retencion1=0.1;
            }
        }
        //////////////DETALLE FACTURA
        $sql_fact_det="SELECT factura.id_factura, factura.id_cliente,factura.id_empleado,
    factura.fecha, factura.numero_doc, factura.total,factura.id_usuario,
    factura.anulada, factura.id_usuario, factura.finalizada, factura.id_sucursal,
    factura_detalle.id_factura_detalle, factura_detalle.id_prod_serv,factura_detalle.cantidad,
    factura_detalle.precio_venta, factura_detalle.subtotal, factura_detalle.tipo_prod_serv,
    factura_detalle.bonificacion,factura_detalle.subt_bonifica,factura_detalle.id_prod_serv,
    producto.descripcion, producto.id_producto,producto.id_categoria,producto.exento,
    factura_detalle.id_presentacion, producto.decimals
    FROM factura
    JOIN factura_detalle  ON factura.id_factura=factura_detalle.id_factura
    JOIN producto  ON producto.id_producto=factura_detalle.id_prod_serv
    WHERE factura.id_factura='$id_factura'
    AND factura.id_sucursal='$id_sucursal'
    AND factura_detalle.tipo_prod_serv='PRODUCTO'
    UNION ALL
    SELECT factura.id_factura, factura.id_cliente,factura.id_empleado,
    factura.fecha, factura.numero_doc, factura.total,factura.id_usuario,
    factura.anulada, factura.id_usuario, factura.finalizada, factura.id_sucursal,
    factura_detalle.id_factura_detalle, factura_detalle.id_prod_serv,factura_detalle.cantidad,
    factura_detalle.precio_venta, factura_detalle.subtotal, factura_detalle.tipo_prod_serv,
    factura_detalle.bonificacion,factura_detalle.subt_bonifica,factura_detalle.id_prod_serv,
    servicios.descripcion, servicios.id_servicio AS id_producto ,servicios.id_categoria, 0 AS exento,
    factura_detalle.id_presentacion, 1 AS decimals
    FROM factura
    JOIN factura_detalle  ON factura.id_factura=factura_detalle.id_factura
    JOIN servicios  ON servicios.id_servicio=factura_detalle.id_prod_serv
    WHERE factura.id_factura='$id_factura'
    AND factura.id_sucursal='$id_sucursal'
    AND factura_detalle.tipo_prod_serv='SERVICIO'
    ";

        $result_fact_det=_query($sql_fact_det);
        $count_fact_det=_num_rows($result_fact_det);
        //echo $sql_fact_det;
        $total=0;
        $lista = "";
        for ($i=0;$i<$count_fact_det;$i++) {
            $row=_fetch_array($result_fact_det);
            $numero_doc=$row['numero_doc'];
            $id_factura=$row['id_factura'];
            $exento= $row['exento'];
            $id_producto=$row['id_prod_serv'];
            $tipo_prod=$row['tipo_prod_serv'];
            $anulada=$row['anulada'];
            $descripcion=$row['descripcion'];
            $cantidad=$row['cantidad'];
            $precio_venta=$row['precio_venta'];
            $subtotal=$row['subtotal'];
            $categoria=$row['id_categoria'];
            $bonificacion=$row['bonificacion'];
            $subt_bonifica=$row['subt_bonifica'];
            $tipo_prod_serv=$row['tipo_prod_serv'];
            $tipo_bien = "<input type='hidden' id='tipo_bien' name='tipo_bien' value='".$tipo_prod_serv."'>";
            if ($row['decimals']==1) {
                $categoria=86;
            }

            $total=$row['total'];
            $id_usuario=$row['id_usuario'];
            $id_empleado=$row['id_empleado'];
            $id_producto=$row['id_producto'];
            $id_pre = $row["id_presentacion"];
            $total=sprintf("%.2f", $total);
            $exento=$row['exento'];

            if ($tipo_prod_serv=='PRODUCTO') {
                $q="SELECT presentacion.nombre, presentacion_producto.descripcion,
                presentacion_producto.id_pp as id_presentacion,presentacion_producto.unidad,
                presentacion_producto.precio FROM presentacion_producto
                JOIN presentacion ON presentacion.id_presentacion=presentacion_producto.id_presentacion
                WHERE presentacion_producto.id_producto='$id_producto' AND presentacion_producto.activo=1";
                $sql_ss=_query($q);
                $y = 0;
                $unidadp = 0;
                $preciop = 0;
                $select_rank="<select class='sel_r form-control'>";
                $select="<select class='sel form-control'>";

                while ($rowx=_fetch_array($sql_ss)) {
                    if ($y==0) {
                        $unidadp=$rowx['unidad'];
                        $preciop=$rowx['precio'];
                        $descripcionp=$rowx['descripcion'];
                        $preciosArray = _getPrecios($id_pre, $precios);
                        $xc=0;
                        foreach ($preciosArray as $key => $value) {
                            if ($value>0) {
                                $select_rank.="<option value='$value'";
                                if ($xc==0 || $precio_venta==$value) {
                                    $select_rank.=" selected ";
                                    $preciop=$value;
                                    $xc = 1;
                                }
                                $select_rank.=">$value</option>";
                            }
                        }
                        if ($xc==0) {
                            $select_rank.="<option value='0.0'>0.0</option>";
                        }
                        $select_rank.="</select>";
                    }
                    $select.="<option value='$rowx[id_presentacion]'";
                    if ($id_pre == $rowx["id_presentacion"]) {
                        $select.="selected";
                    }
                    $select.=">$rowx[nombre]</option>";
                    $y=$y+1;
                }
                $select.="</select>";
                // AND u.bodega=0
                $sql_cc = _query("SELECT * FROM presentacion_producto WHERE id_pp = '$id_pre'");
                $roq = _fetch_array($sql_cc);
                $unidadq=$roq['unidad'];
                $descripcionq=$roq['descripcion'];
                $cc = $cantidad / $unidadq;
                $descripcion=$row['descripcion'];
                $sql_s = _fetch_array(_query("SELECT p.id_sucursal,SUM(su.cantidad) as stock
                FROM producto AS p JOIN stock_ubicacion as su ON su.id_producto=p.id_producto
                JOIN ubicacion as u ON u.id_ubicacion=su.id_ubicacion
                WHERE  p.id_producto ='$id_producto' AND su.id_sucursal=$id_sucursal"));

                $stock_r=$sql_s['stock'];

                $hoy=date("Y-m-d");
                $sql_res_pre=_fetch_array(_query("SELECT SUM(factura_detalle.cantidad+factura_detalle.bonificacion) as reserva
                FROM factura JOIN factura_detalle ON factura_detalle.id_factura=factura.id_factura
                WHERE factura_detalle.id_prod_serv=$id_producto AND factura.id_sucursal=$id_sucursal
                AND factura.fecha = '$hoy' AND factura.finalizada=0 "));
                $reserva=$sql_res_pre['reserva'];

                $sql_res_esto=_fetch_array(_query("SELECT SUM(factura_detalle.cantidad+factura_detalle.bonificacion) as reservado
                FROM factura JOIN factura_detalle ON factura_detalle.id_factura=factura.id_factura
                WHERE factura_detalle.id_prod_serv=$id_producto AND factura.id_factura=$id_factura"));
                $reservado=$sql_res_esto['reservado'];
                $existencias=$stock_r+$reservado-$reserva;
                $descprod=$descripcion;
                $ubicacion="";
                if ($existencias<=$cantidad) {
                    $existencias=$cantidad;
                }
                $descripcion.=$ubicacion;
            } else {
                $descripcionp=" ";
                $unidadq=1;
                $existencias=1;
                $cc=$cantidad;
                $select="<select class='sel form-control'>";
                $select.="<option value='1'>UNIDAD</option>";
                $select.="</select>";
                $select_rank="<select class='sel_r form-control'>";
                $select_rank.="<option value='".$precio_venta."'>".$precio_venta."</option>";
                $select_rank.="</select>";
            }
            // var_dump($cc);
            $sqkl=_fetch_array(_query("SELECT iva FROM sucursal WHERE id_sucursal=$id_sucursal"));
            $iva=$sqkl['iva']/100;
            $iva=1+$iva;
            //$cc= intval($cc);

            $btnView= "<a data-toggle='modal' href='ver_imagen.php?id_producto='".$id_producto."'  data-target='#viewProd' data-refresh='true' class='btn btn-primary btnViw fa'><i class='fa fa-eye'></i></a>";

            $es_exento = "<input type='hidden' id='exento' name='exento' value='" .$exento. "'>";
            $cantidades = "<td class='cell100 column10 text-success'><input type='text'  " ;
            $cantidades .= $categoria . " cant' id='cant' name='cant' value='".$cc."' style='width:60px;'></td>";
            $lista .= "<tr  class='row100 head' id='" . $i . "'>";
            $lista .= "<td hidden class='cell100 column10 text-success id_pps'>";
            $lista .="<input type='hidden' id='unidades' name='unidades' value='1'>" . $id_producto. $es_exento. "</td>";
            $lista .= "<td class='cell100 column30 success'>" . $descripcion  .$tipo_bien .'</td>';
            $lista .= "<td class='cell100 column10 text-success' id='cant_stock'>" . $existencias . "</td>";
            $lista .= $cantidades;
            $lista .= "<td class='cell100 column10 text-success preccs'>" . $select . "</td>";
            $lista .= "<td hidden class='cell100 column10 text-success descp'><input type'text' id='dsd' class='form-control' value='" . $descripcionp . "' class='txt_box' readonly></td>";
            $lista .= "<td class='cell100 column10 text-success rank_s'>" . $select_rank . "</td>";
            $lista .= "<td class='cell100 column10 text-success'><input type='hidden'  id='precio_venta_inicial' name='precio_venta_inicial' value='" . $precio_venta . "'>";
            $lista .= "<input type='hidden'  id='precio_sin_iva' name='precio_sin_iva' value='" . $precio_venta. "'>";
            $lista .= "<input type='text'  class='form-control decimal' id='precio_venta' name='precio_venta' value='" .$precio_venta . "'></td>";
            if ($tipo_documento == "CCF") {
                $lista .= "<td class='ccell100 column10'>" . "<input type='hidden'  id='subtotal_fin' name='subtotal_fin' value='" .$subtotal . "'>" .
            "<input type='text'  class='decimal txt_box form-control' id='subtotal_mostrar' name='subtotal_mostrar'  value='" . $subtotal . "'readOnly></td>";
            } else {
                $lista .= "<td class='ccell100 column10'>" . "<input type='hidden'  id='subtotal_fin' name='subtotal_fin' value='" .$subtotal . "'>"
            . "<input type='text'  class='decimal txt_box form-control' id='subtotal_mostrar' name='subtotal_mostrar'  value='" . $subtotal . "'readOnly></td>";
            }
            $lista .= "<td hidden class='cell100 column10 text-success id_pps'><input type='hidden' id='subt_bonifica' name='subt_bonifica' value='0'></td>";
            $lista .= '<td class="cell100 column10 Delete text-center"><input id="delprod" type="button" class="btn btn-danger fa"  value="&#xf1f8;">&nbsp;'. $btnView.' </td>';
            $lista .= '</tr>';
        }
        $select_vendedor="";
        $sqlemp=_query("SELECT id_empleado, nombre FROM empleado WHERE id_sucursal='$id_sucursal' AND id_tipo_empleado=2");
        while ($row_emp = _fetch_array($sqlemp)) {
            if ($row_emp["id_empleado"]==$id_empleado) {
                $select_vendedor .= "<option value='".$row_emp["id_empleado"]."' selected>".$row_emp["nombre"]."</option>";
            } else {
                $select_vendedor .= "<option value='".$row_emp["id_empleado"]."'>".$row_emp["nombre"]."</option>";
            }
        }
        $select_cliente="";
        $select_cliente="<option value=''>Seleccione</option>";
        $sqlcli=_query("SELECT * FROM cliente ORDER BY nombre");
        while ($row_cli = _fetch_array($sqlcli)) {
            if ($row_cli["id_cliente"]==$id_cliente) {
                # code...
                $select_cliente.= "<option value='".$row_cli["id_cliente"]."' selected>".$row_cli["nombre"]."</option>";
            } else {
                $select_cliente.= "<option value='".$row_cli["id_cliente"]."'>".$row_cli["nombre"]."</option>";
            }
        }

        $select_tipo_impresion="";

        if ("TIK"==$alias_tipodoc) {
            $select_tipo_impresion.="<option value='TIK'>TICKET</option>";
        } else {
            $select_tipo_impresion.="<option value='TIK'>TICKET</option>";
        }

        if ("COF"==$alias_tipodoc) {
            # code...
            $select_tipo_impresion.="<option value='COF' selected>FACTURA CONSUMIDOR FINAL</option>";
        } else {
            # code...
            $select_tipo_impresion.="<option value='COF'>FACTURA CONSUMIDOR FINAL</option>";
        }

        if ("CCF"==$alias_tipodoc) {
            # code...
            $select_tipo_impresion.="<option value='CCF' selected  >CREDITO FISCAL</option>";
        } else {
            # code...
            $select_tipo_impresion.="<option value='CCF'>CREDITO FISCAL</option>";
        }

        $xdatos['typeinfo'] = "Success";
        $xdatos['msg'] = "";
        $xdatos['id_cliente'] = $id_cliente;
        $xdatos['select_cliente'] = $select_cliente;
        $xdatos['select_tipo_impresion'] = $select_tipo_impresion;
        $xdatos['select_vendedor'] = $select_vendedor;
        $xdatos['nombre_cliente'] = $nombre_cliente;
        $xdatos['alias_tipodoc'] = $alias_tipodoc;
        $xdatos['lista'] = $lista;
        $xdatos['id_empleado'] = $id_empleado;
        $xdatos['numero_doc'] = $numero_doc;
        $xdatos['id_factura'] = $id_factura;
        $xdatos['retencion1']= $retencion1;
        $xdatos['retencion10']= $retencion10;
        $xdatos['precio_aut'] = $precio_aut;
        $xdatos['clave'] = $clave;
    } else {
        $xdatos['typeinfo'] = "Error";
        $xdatos['msg'] = "No se encontro documento";
        $xdatos['id_cliente'] = "";
        $xdatos['nombre_cliente'] = "";
        $xdatos['alias_tipodoc'] = "";
        $xdatos['lista'] = "";
        $xdatos['id_empleado'] = "";
        $xdatos['numero_doc'] = "";
        $xdatos['id_factura'] = "";
        $xdatos['retencion1']= 0;
        $xdatos['retencion10']= 0;
    }
    echo json_encode($xdatos);
}
function consultar_stock()
{
    $precio_aut = $_REQUEST['precio_aut'];
    $tipo = $_POST['tipo'];
    $id_producto = $_REQUEST['id_producto'];
    $id_usuario=$_SESSION["id_usuario"];
    $r_precios=_fetch_array(_query("SELECT precios FROM usuario WHERE id_usuario=$id_usuario"));
    $precios=$r_precios['precios'];
    $limit="LIMIT ".$precios;
    if ($precio_aut>$precios) {
        // code...
        $precios=$precio_aut;
    }
    $id_sucursal=$_SESSION['id_sucursal'];
    $id_factura=$_REQUEST['id_factura'];
    $precio=0;
    $id_presentacione = 0;
    $categoria="";
    if ($tipo == "D") {
        $clause = "p.id_producto = '$id_producto'";
    } else {
        $sql_aux= _query("SELECT id_producto FROM producto WHERE barcode='$id_producto'");
        if (_num_rows($sql_aux)>0) {
            $dats_aux = _fetch_array($sql_aux);
            $id_producto = $dats_aux["id_producto"];
            $clause = "p.id_producto = '$id_producto'";
        } else {
            $id_producto = intval($id_producto);
            $sql_aux = _query("SELECT id_pp as id_presentacion, id_producto
      FROM presentacion_producto WHERE id_pp='$id_producto'
      AND activo='1'  OR  barcode='$id_producto' AND activo='1' ");
            if (_num_rows($sql_aux)>0) {
                $dats_aux = _fetch_array($sql_aux);
                $id_producto = $dats_aux["id_producto"];
                $id_presentacione = $dats_aux["id_presentacion"];
                $clause = "p.id_producto = '$id_producto'";
            } else {
                $clause = "p.barcode = '$id_producto'";
            }
        }
    }
    //  AND u.bodega=0
    $sql1 = "SELECT p.id_producto,p.id_categoria, p.barcode, p.decimals,
  p.descripcion, p.estado, p.perecedero, p.exento, p.id_categoria, p.id_sucursal,SUM(su.cantidad) as stock
  FROM producto AS p
  JOIN stock_ubicacion as su ON su.id_producto=p.id_producto
  JOIN ubicacion as u ON u.id_ubicacion=su.id_ubicacion
  WHERE $clause

  AND su.id_sucursal=$id_sucursal";
    $stock1=_query($sql1);
    $row1=_fetch_array($stock1);
    $nrow1=_num_rows($stock1);
    if ($nrow1>0) {
        if ($row1["descripcion"] != "" && $row1["descripcion"] != null) {
            $id_productov = $row1['id_producto'];
            $id_producto = $row1['id_producto'];
            $sql_exis = _query("SELECT stock FROM stock WHERE id_producto = '$id_productov'");
            $datos_exis = _fetch_array($sql_exis);
            $stockv = $datos_exis["stock"];
            if (intval($stockv) > 0) {
                $hoy=date("Y-m-d");
                $perecedero=$row1['perecedero'];
                $barcode = $row1["barcode"];
                $descripcion = $row1["descripcion"];
                $estado = $row1["estado"];
                $perecedero = $row1["perecedero"];
                $exento = $row1["exento"];
                $categoria=$row1['id_categoria'];
                $sql_res_pre=_fetch_array(_query("SELECT  SUM(factura_detalle.cantidad+factura_detalle.bonificacion) as reserva
        FROM factura JOIN factura_detalle ON factura_detalle.id_factura=factura.id_factura
        WHERE factura_detalle.id_prod_serv=$id_producto AND factura.id_sucursal=$id_sucursal
        AND factura.fecha = '$hoy' AND factura.finalizada=0 "));
                $reserva=$sql_res_pre['reserva'];

                $sql_res_esto=_fetch_array(_query("SELECT  SUM(factura_detalle.cantidad+factura_detalle.bonificacion) as reservado
        FROM factura JOIN factura_detalle ON factura_detalle.id_factura=factura.id_factura
        WHERE factura_detalle.id_prod_serv=$id_producto AND factura.id_factura=$id_factura"));
                $reservado=$sql_res_esto['reservado'];


                $stock= $row1["stock"]-$reserva+$reservado;
                if ($stock<0) {
                    $stock=0;
                }

                $i=0;
                $unidadp=0;
                $preciop=0;
                $descripcionp=0;
                $select_rank="<select class='sel_r form-control'>";
                $anda = "";
                if ($id_presentacione > 0) {
                    $anda = "AND presentacion_producto.id_pp = '$id_presentacione'";
                }
                $sql_p=_query("SELECT presentacion.nombre, presentacion_producto.descripcion,
        presentacion_producto.id_pp as id_presentacion,presentacion_producto.unidad,presentacion_producto.precio
        FROM presentacion_producto
        JOIN presentacion ON presentacion.id_presentacion=presentacion_producto.id_presentacion
        WHERE presentacion_producto.id_producto='$id_producto'
        AND presentacion_producto.activo=1
        $anda
        ORDER BY presentacion_producto.unidad ASC");
                $select="<select class='sel form-control'>";
                while ($row=_fetch_array($sql_p)) {
                    if ($i==0) {
                        $id_press=$row["id_presentacion"];
                        $unidadp=$row['unidad'];
                        $preciop=$row['precio'];
                        $descripcionp=$row['descripcion'];

                        $preciosArray = _getPrecios($id_press, $precios);
                        $xc=0;
                        foreach ($preciosArray as $key => $value) {
                            // code...
                            if ($value>0) {
                                $select_rank.="<option value='$value'";
                                if ($xc==0) {
                                    $select_rank.=" selected ";
                                    $preciop=$value;
                                    $xc = 1;
                                }
                                $select_rank.=">$value</option>";
                            }
                        }
                        if ($xc==0) {
                            $select_rank.="<option value='0.0'>0.0</option>";
                        }
                        //$select_rank.="<option value='0.0'>0.0</option>";
                        $select_rank.="</select>";
                    }
                    $select.="<option value='".$row["id_presentacion"]."'";
                    if ($id_presentacione == $row["id_presentacion"]) {
                        $select.=" selected ";
                    }
                    $select.=">$row[nombre]</option>";
                    $i=$i+1;
                }
                $align=new AlignMarginText();
                $descripc="";
                $descripts = $align->wordwrap1($descripcion, 60);
                foreach ($descripts as $descrip) {
                    $descripc.=$descrip."<br>";
                }
                $select.="</select>";
                $xdatos['perecedero']=$perecedero;
                $xdatos['decimals']= $row1['decimals'];
                $xdatos['descripcion']= $descripc;
                $xdatos['id_producto']= $id_productov;
                $xdatos['select']= $select;
                $xdatos['select_rank']= $select_rank;
                $xdatos['stock']= $stock;
                $xdatos['preciop']= $preciop;

                $sql_e=_fetch_array(_query("SELECT exento FROM producto WHERE id_producto=$id_producto"));
                $exento=$sql_e['exento'];
                if ($exento==1) {
                    # code...
                    $xdatos['preciop_s_iva']=$preciop;
                } else {
                    # code...
                    $sqkl=_fetch_array(_query("SELECT iva FROM sucursal WHERE id_sucursal=$id_sucursal"));
                    $iva=$sqkl['iva']/100;
                    $iva=1+$iva;
                    $xdatos['preciop_s_iva']= round(($preciop/$iva), 8, PHP_ROUND_HALF_DOWN);
                }
                $xdatos['unidadp']= $unidadp;
                $xdatos['descripcionp']= $descripcionp;
                $xdatos['exento']=$exento;
                $xdatos['categoria']=$categoria;
                $xdatos['typeinfo']="Success";

                echo json_encode($xdatos); //Return the JSON Array
            } else {
                $xdatos['typeinfo']="Error";
                $xdatos['msg']="El producto seleccionado no posee existencias";
                echo json_encode($xdatos); //Return the JSON Array
            }
        } else {
            $xdatos['typeinfo']="Error";
            $xdatos['msg']="El codigo ingresado no pertenece a nungun producto";
            echo json_encode($xdatos); //Return the JSON Array
        }
    } else {
    }
}

function total_texto()
{
    $total=$_REQUEST['total'];
    list($entero, $decimal)=explode('.', $total);
    $enteros_txt=num2letras($entero);
    $decimales_txt=num2letras($decimal);

    if ($entero>1) {
        $dolar=" dolares";
    } else {
        $dolar=" dolar";
    }
    $cadena_salida= "Son: <strong>".$enteros_txt.$dolar." con ".$decimal."/100 ctvs.</strong>";
    echo $cadena_salida;
}

function numero_tiquete($ult_doc, $tipo)
{
    $ult_doc=trim($ult_doc);
    $len_ult_valor=strlen($ult_doc);
    $long_num_fact=10;
    $long_increment=$long_num_fact-$len_ult_valor;
    $valor_txt="";
    if ($len_ult_valor<$long_num_fact) {
        for ($j=0;$j<$long_increment;$j++) {
            $valor_txt.="0";
        }
    } else {
        $valor_txt="";
    }
    $valor_txt=$valor_txt.$ult_doc."_".$tipo;
    return $valor_txt;
}

function insertar()
{
    $xdatos = [];
    _begin();

    try {
        $precio_aut = 0;
        $clave="";
        if (isset($_REQUEST['precio_aut'])) {
            $precio_aut=$_REQUEST['precio_aut'];
            $clave=$_REQUEST['clave'];
        }
        $fecha_movimiento =$_POST['fecha_movimiento'];
        $id_cliente=$_POST['id_cliente'];
        $id_factura=$_POST['id_factura'];
        $id_vendedor=$_POST['id_vendedor'];
        $cuantos = $_POST['cuantos'];
        $array_json=$_POST['json_arr'];
        $fecha=date("Y-m-d");
        //  IMPUESTOS
        $total_percepcion= $_POST['total_percepcion'];

        $subtotal=$_POST['subtotal'];
        $total_bonifica=0;
        $sumas=$_POST['sumas'];
        $suma_gravada=$_POST['suma_gravada'];
        $iva= $_POST['iva'];
        $retencion= $_POST['retencion'];
        $venta_exenta= $_POST['venta_exenta'];
        $total_menos_retencion=$_POST['total'];
        $total = $retencion+$_POST['total'];

        $id_empleado=$_SESSION["id_usuario"];
        if ($id_vendedor == "") {
            $id_vendedor = $id_empleado;
        }
        $id_sucursal=$_SESSION["id_sucursal"];
        $fecha_actual = date('Y-m-d');
        $tipoprodserv = "PRODUCTO";
        $credito=$_POST['credito'];
        $id_apertura=$_POST['id_apertura'];
        $turno=$_POST['turno'];
        $caja=$_POST['caja'];
        $tipo_documento=$_POST['tipo_impresion'];
        $tipo_impresion=$tipo_documento;
        $tipo_pago= $_POST['tipo_pago'];
        $insertar_fact      =  false;
        $insertar_fact_dett =  true;
        $insertar_numdoc    =  false;
        $numdoc             = $_POST['numdoc'];
        $nitcli             = $_POST['nitcli'];
        $extra_nombre       = $_POST['extra_nombre'];
        $hora=date("H:i:s");
        $xdatos['typeinfo']='';
        $xdatos['msg']='';
        $xdatos['process']='';

        //resolucion, autorizacion de facturas
        $q_res="SELECT id_resolucion FROM resolucion
        WHERE  alias='$tipo_documento' AND vigente=1
        ";
        //$validoRes=true;
        $id_resolucion=0;
        $ress = _query($q_res);
        $numrowss= _num_rows($ress);
        if ($numrowss>0) {
            $row_res=_fetch_row($ress);
            $id_resolucion=$row_res[0];
        }
        //descripcion tipo de pago
        $descrip_pago = getTipoPago($tipo_pago);
        //datos de cliente
        if ($id_cliente!=-1) {
            $sql_cte="select * from cliente WHERE id_cliente=$id_cliente";
            $result_cte= _query($sql_cte);
            $nrows_cte=_num_rows($result_cte);
            $rows_cte=_fetch_array($result_cte);
            $dias_credito=$rows_cte['dias_credito'];
        }

        $a=1;
        $b=1;
        $c=1;
        $z=1;
        $j = 1 ;
        $k = 1 ;
        $l = 1 ;
        $tipo_entrada_salida='';

        $sql="select * from correlativo WHERE id_sucursal=$id_sucursal";
        $result= _query($sql);
        $rows=_fetch_array($result);
        $nrows=_num_rows($result);
        $ult_ccf=$rows['ccf']+1;
        $ult_cof=$rows['cof']+1;

        $numero_doc="";
        $num_fact_impresa='';

        $table_numdoc="correlativo";
        $data_numdoc="";

        if ($tipo_impresion =='COF') {
            $tipo_entrada_salida='FACTURA CONSUMIDOR';
            $data_numdoc = array(
        'cof' => $ult_cof
        );
            $numero_doc=numero_tiquete($ult_cof, $tipo_impresion);
        }
        if ($tipo_impresion =='TIK') {
            $sql_corre = _query("SELECT * FROM caja WHERE id_caja = '$caja'");
            $row_corre = _fetch_array($sql_corre);
            $correlativo_dispo = $row_corre["correlativo_dispo"];
            $tipo_entrada_salida='TICKET';
            $data_numdoc = array(
        'correlativo_dispo' => $correlativo_dispo+1,
        );
            $num_fact_impresa=$correlativo_dispo;
            $numero_doc=numero_tiquete($correlativo_dispo, $tipo_impresion);
        }
        if ($tipo_impresion =='CCF') {
            $tipo_entrada_salida='CREDITO FISCAL';
            $data_numdoc = array(
        'ccf' => $ult_ccf
        );
            $numero_doc=numero_tiquete($ult_ccf, $tipo_impresion);
        }

        if ($tipo_impresion != "TIK") {
            $where_clause_n=" WHERE id_sucursal='$id_sucursal'";
            $insertar_numdoc = _update($table_numdoc, $data_numdoc, $where_clause_n);
        } else {
            $tab = 'caja';
            $where_clause_c=" WHERE id_caja='$caja'";
            $insertar_numdoc = _update($tab, $data_numdoc, $where_clause_c);
        }

        $abono=0;
        $saldo=0;

        $serie="";
        $ultimo=0;

        if ($tipo_impresion == "TIK") {
            $sql_corre = _query("SELECT * FROM caja WHERE id_caja = '$caja'");
            $row_corre = _fetch_array($sql_corre);
            $serie = $row_corre["serie"];
        } elseif ($tipo_impresion == "COF") {
            # code...
            $swl =_fetch_array(_query("SELECT * FROM sucursal where id_sucursal=$id_sucursal "));
            $serie=$swl['serie_cof'];
            $sql_ult=_query("SELECT MAX(CONVERT(num_fact_impresa,UNSIGNED INTEGER)) as ultimo FROM factura WHERE id_sucursal=$id_sucursal AND tipo_documento='COF' ");

            $num_rows_ul=_num_rows($sql_ult);
            if ($num_rows_ul>0) {
                # code...
                $ul=_fetch_array($sql_ult);
                $ultimo=$ul['ultimo'];
            }
        } else {
            # code...
            $swl =_fetch_array(_query("SELECT * FROM sucursal where id_sucursal=$id_sucursal "));
            $serie=$swl['serie_ccf'];

            $sql_ult=_query("SELECT MAX(CONVERT(num_fact_impresa,UNSIGNED INTEGER)) as ultimo FROM factura WHERE id_sucursal=$id_sucursal AND tipo_documento='CCF' ");

            $num_rows_ul=_num_rows($sql_ult);
            if ($num_rows_ul>0) {
                # code...
                $ul=_fetch_array($sql_ult);
                $ultimo=$ul['ultimo'];
            }
        }
        //actualizar nit cliente
        if (isset($nitcli) && $nitcli!="") {
            $tc= 'cliente';
            $fd = array(
        'nit' => $nitcli,
        );
            $wc="id_cliente='".$id_cliente."'";
            $updCliente = _update($tc, $fd, $wc);
        }
        //fin actualizar nit cliente
        $total_credito = 0 ;
        if ($credito==1) {
            $saldo         = $total;
            $total_credito = $total;
        }
        $id_fact="";
        if ($id_factura=="") {
            # code...
            $table_fact= 'factura';
            $form_data_fact = array(
        'id_cliente' => $id_cliente,
        'fecha' => $fecha_movimiento,
        'numero_doc' => $numero_doc,
        'subtotal' => $subtotal,
        'subt_bonifica'=>$total_bonifica,
        'sumas'=>$sumas,
        'suma_gravado'=>$suma_gravada,
        'iva' =>$iva,
        'retencion'=>$retencion,
        'venta_exenta'=>$venta_exenta,
        'total_menos_retencion'=>$total_menos_retencion,
        'total' => $total,
        'id_usuario'=>$id_empleado,
        'id_empleado' => $id_vendedor,
        'id_sucursal' => $id_sucursal,
        'tipo' => $tipo_entrada_salida,
        'serie' => $serie,
        'num_fact_impresa' => $numdoc,
        'hora' => $hora,
        'finalizada' => '1',
        'abono'=>$abono,
        'saldo' => $saldo,
        'tipo_documento' => $tipo_documento,
        'id_apertura' => $id_apertura,
        'id_apertura_pagada' => $id_apertura,
        'caja' => $caja,
        'credito' => $credito,
        'turno' => $turno,
        'precio_aut' => $precio_aut,
        'clave' => $clave,
        'extra_nombre' => $extra_nombre,
        'pagar' => $total,
        'tipo_pago' =>$tipo_pago,
        'total_credito' =>$total_credito,
        'id_resolucion' =>$id_resolucion,
        );
            $insertar_fact = _insert($table_fact, $form_data_fact);
            $id_fact= _insert_id();

            if (!$insertar_fact) {
                # code...
                $b=0;
            }
        } else {
            $table_fact= 'factura';
            $form_data_fact = array(
        'id_cliente' => $id_cliente,
        'fecha' => $fecha_movimiento,
        'numero_doc' => $numero_doc,
        'subtotal' => $subtotal,
        'subt_bonifica'=>$total_bonifica,
        'sumas'=>$sumas,
        'suma_gravado'=>$suma_gravada,
        'iva' =>$iva,
        'retencion'=>$retencion,
        'venta_exenta'=>$venta_exenta,
        'total_menos_retencion'=>$total_menos_retencion,
        'total' => $total,
        'id_empleado' => $id_vendedor,
        'id_sucursal' => $id_sucursal,
        'tipo' => $tipo_entrada_salida,
        'serie' => $serie,
        'num_fact_impresa' => $numdoc,
        'hora' => $hora,
        'finalizada' => '1',
        'abono'=>$abono,
        'saldo' => $saldo,
        'tipo_documento' => $tipo_documento,
        'id_apertura' => $id_apertura,
        'id_apertura_pagada' => $id_apertura,
        'caja' => $caja,
        'credito' => $credito,
        'turno' => $turno,
        'precio_aut' => $precio_aut,
        'clave' => $clave,
        'extra_nombre' => $extra_nombre,
        'pagar' => $total,
        'tipo_pago' =>$tipo_pago,
        'total_credito' =>$total_credito,
        'id_resolucion' =>$id_resolucion,
        );
            //  $whereclause="id_factura='".$id_factura."'";
            //  $insertar_fact = _update($table_fact, $form_data_fact, $whereclause);
            $whereclause = "id_factura = '" . $id_factura . "'";
            /* Consulta que verifica si ya se inserto un documento, TIK,COF, CCF y que no tenga estado finalizado, de lo contrario genera error y no actualiza*/
            $existe_factura=existeFactura($id_factura);
            if ($existe_factura==0) {
                $insertar_fact = _update('factura', $form_data_fact, $whereclause);
                $id_fact= $id_factura;
                if (!$insertar_fact) {
                    $b=0;
                }
            }
            $table="factura_detalle";
            $where_clause="id_factura='".$id_fact."'";
            $delete=_delete($table, $whereclause);
            if (!$delete) {
                $b=0;
            }
        }

        $cre=1;
        if ($credito==1) {
            $table="credito";
            $total=$total-$retencion;
            $form_data = array(
            'id_cliente'  => $id_cliente,
            'fecha'       => $fecha_movimiento,
            'tipo_doc'    => $tipo_documento,
            'numero_doc'  => $numero_doc,
            'id_factura'  => $id_fact,
            'dias'        =>  $dias_credito,
            'total'       => $total,
            'abono'       => 0,
            'saldo'       => $total,
            'finalizada'  => 0,
            'id_sucursal' => $id_sucursal,
        );
            $insert=_insert($table, $form_data);
            if ($insert) {
            } else {
                $cre=0;
            }
        }

        $table='movimiento_producto';
        $form_data = array(
        'id_sucursal' => $id_sucursal,
        'correlativo' => $numero_doc,
        'concepto' => "VENTA",
        'total' => $total,
        'tipo' => 'SALIDA',
        'proceso' => $tipo_documento,
        'referencia' => $numero_doc,
        'id_empleado' => $id_empleado,
        'fecha' => $fecha,
        'hora' => $hora,
        'id_suc_origen' => $id_sucursal,
        'id_suc_destino' => $id_sucursal,
        'id_proveedor' => 0,
        'id_factura' => $id_fact,
    );
        $insert_mov =_insert($table, $form_data);
        $x=1;
        if ($insert_mov) {
            # code...
        } else {
            # code...
            $x=0;
        }

        $id_movimiento=_insert_id();
        $nuevo_stock =0;
        if ($cuantos>0) {
            $array = json_decode($array_json, true);
            foreach ($array as $fila) {
                $id_producto=$fila['id'];
                $unidades=$fila['unidades'];
                $subtotal=$fila['subtotal'];
                $subt_bonifica= 0;
                $bonificacion=0;
                $bandera=$fila['bandera'];
                $cantidad=$fila['cantidad'];
                $id_presentacion=$fila['id_presentacion'];
                $cantidad_real = $cantidad*$unidades;
                $cantidad = $cantidad_real;
                $bonificacion_r= $bonificacion*$unidades;
                $exento=$fila['exento'];
                $precio_venta=$fila['precio'];
                $tipoprodserv =$bandera;

                if ($bandera == "SERVICIO") {
                    if ($id_producto == "nuevo_servicio") {
                        $id_presentacion=1;
                        $subtotal = round($precio_venta * $cantidad, 2);
                        $descripcion = $fila["descripcion_ser"];
                        $tab_ser = "servicios";
                        $form_dataser = array(
                    'descripcion' => $descripcion,
                    'estado' => 1,
                    'id_categoria' => 1,
                    'costo' => $precio_venta,
                    'precio' => $precio_venta,
                    'tipo_prod_servicio' => "SERVICIO",
                    );
                        $inser_ser = _insert($tab_ser, $form_dataser);
                        $id_producto = _insert_id();
                    }
                }
                $sql_costo=_fetch_array(_query("SELECT costo FROM presentacion_producto WHERE id_pp  = $id_presentacion"));
                $precio_compra=$sql_costo['costo'];
                $table_fact_det= 'factura_detalle';
                $data_fact_det = array(
                'id_factura' => $id_fact,
                'id_prod_serv' => $id_producto,
                'cantidad' => $cantidad_real,
                'bonificacion'=> $bonificacion_r,
                'precio_venta' => $precio_venta,
                'subtotal' => $subtotal,
                'subt_bonifica' => $subt_bonifica,
                'tipo_prod_serv' => $bandera,
                'id_empleado' => $id_empleado,
                'id_sucursal' => $id_sucursal,
                'fecha' => $fecha_movimiento,
                'id_presentacion'=> $id_presentacion,
                'exento' => $exento,
                'combustible' =>0,
                'impuesto'    => 0,
                'total' => $subtotal,
            );


                $insertar_fact_det = _insert($table_fact_det, $data_fact_det);
                if (!$insertar_fact_det) {
                    $c=0;
                }
                if ($bandera == "PRODUCTO") {
                    $a_transferir=$cantidad_real;
                    $q_ori = _query("SELECT ubicacion.id_ubicacion FROM ubicacion WHERE ubicacion.id_sucursal='$id_sucursal'");
                    $nrows_ori = _num_rows($q_ori);
                    if ($nrows_ori>0) {
                        for ($n=0;$n<$nrows_ori;$n++) {
                            $orig=_fetch_array($q_ori);
                            $origen=$orig['id_ubicacion'];
                            $sql=_query("SELECT * FROM stock_ubicacion WHERE stock_ubicacion.id_producto='$id_producto'
                    AND stock_ubicacion.id_ubicacion='$origen' AND stock_ubicacion.cantidad!=0
                    ORDER BY id_posicion DESC ,id_estante DESC ");

                            while ($rowsu=_fetch_array($sql)) {
                                $id_su1=$rowsu['id_su'];
                                $stock_anterior=$rowsu['cantidad'];
                                if ($a_transferir!=0) {
                                    $transfiriendo=0;
                                    $nuevo_stock=$stock_anterior-$a_transferir;
                                    if ($nuevo_stock<0) {
                                        $transfiriendo=$stock_anterior;
                                        $a_transferir=$a_transferir-$stock_anterior;
                                        $nuevo_stock=0;
                                    } else {
                                        if ($nuevo_stock>0) {
                                            $transfiriendo=$a_transferir;
                                            $a_transferir=0;
                                            $nuevo_stock=$stock_anterior-$transfiriendo;
                                        } else {
                                            $transfiriendo=$stock_anterior;
                                            $a_transferir=0;
                                            $nuevo_stock=0;
                                        }
                                    }
                                    $table="stock_ubicacion";
                                    $form_data = array(
                        'cantidad' => $nuevo_stock,
                        );
                                    $where_clause="id_su='".$id_su1."'";
                                    $update=_update($table, $form_data, $where_clause);
                                    if ($update) {
                                        $up = 1;
                                    } else {
                                        $up=0;
                                    }
                                    $table="movimiento_stock_ubicacion";
                                    $form_data = array(
                        'id_producto' => $id_producto,
                        'id_origen' => $id_su1,
                        'id_destino'=> 0,
                        'cantidad' => $transfiriendo,
                        'fecha' => $fecha,
                        'hora' => $hora,
                        'anulada' => 0,
                        'afecta' => 0,
                        'id_sucursal' => $id_sucursal,
                        'id_presentacion'=> $id_presentacion,
                        'id_mov_prod' => $id_movimiento,
                        );

                                    $insert_mss =_insert($table, $form_data);

                                    if (!$insert_mss) {
                                        $z=0;
                                    }
                                }
                            }
                        }
                    }
                    $sql2="SELECT stock FROM stock WHERE id_producto='$id_producto' AND id_sucursal='$id_sucursal'";
                    $stock2=_query($sql2);
                    $nrow2=_num_rows($stock2);
                    if ($nrow2>0) {
                        $row2=_fetch_array($stock2);
                        $existencias=$row2['stock'];
                    } else {
                        $existencias=0;
                    }

                    /*significa que no hay suficientes unidades en el stock_ubicacion para realizar el descargo*/
                    if ($a_transferir>0) {
                        /*verificamos si se desconto algo de stock_ubicacion*/

                        if ($a_transferir!=$cantidad) {/*si entra aca significa que se descontaron algunas unidades de stock_ubicacion y hay que descontarlas de stock y lote*/
                            /*se insertara la diferencia entre el stock_ubicacion y la cantidad a descontar en la tabla de movimientos pendientes*/
                            $table1= 'movimiento_producto_detalle';
                            $cant_total=$existencias-$a_transferir;
                            $form_data1 = array(
                    'id_movimiento'=>$id_movimiento,
                    'id_producto' => $id_producto,
                    'cantidad' => ($cantidad-$a_transferir),
                    'costo' => $precio_compra,
                    'precio' => $precio_venta,
                    'stock_anterior'=>$existencias,
                    'stock_actual'=>$cant_total,
                    'lote' => 0,
                    'id_presentacion' => $id_presentacion,
                    'fecha' =>  $fecha,
                    'hora' => $hora
                );
                            $insert_mov_det = _insert($table1, $form_data1);
                            if (!$insert_mov_det) {
                                $j = 0;
                            }
                            $table2= 'stock';
                            if ($nrow2==0) {
                                $form_data2 = array(
                    'id_producto' => $id_producto,
                    'stock' => 0,
                    'costo_unitario'=>round(($precio_compra/$unidades), 2),
                    'precio_unitario'=>round(($precio_venta/$unidades), 2),
                    'create_date'=>$fecha_movimiento,
                    'update_date'=>$fecha_movimiento,
                    'id_sucursal' => $id_sucursal
                    );
                                $insert_stock = _insert($table2, $form_data2);
                            } else {
                                $form_data2 = array(
                    'id_producto' => $id_producto,
                    'stock' => $cant_total,
                    'costo_unitario'=>round(($precio_compra/$unidades), 2),
                    'precio_unitario'=>round(($precio_venta/$unidades), 2),
                    'update_date'=>$fecha_movimiento,
                    'id_sucursal' => $id_sucursal
                    );
                                $where_clause="WHERE id_producto='$id_producto' and id_sucursal='$id_sucursal'";
                                $insert_stock = _update($table2, $form_data2, $where_clause);
                            }
                            if (!$insert_stock) {
                                $k = 0;
                            }

                            /*arreglando problema con lotes de nuevo*/
                            $cantidad_a_descontar=($cantidad-$a_transferir);
                            $sql=_query("SELECT id_lote, id_producto, fecha_entrada, vencimiento, cantidad FROM lote
                    WHERE id_producto='$id_producto'
                    AND id_sucursal='$id_sucursal'
                    AND cantidad>0
                    AND estado='VIGENTE'
                    ORDER BY vencimiento");


                            $contar=_num_rows($sql);
                            $insert=1;
                            if ($contar>0) {
                                # code...
                                while ($row=_fetch_array($sql)) {
                                    # code...
                                    $entrada_lote=$row['cantidad'];
                                    if ($cantidad_a_descontar>0) {
                                        # code...
                                        if ($entrada_lote==0) {
                                            $table='lote';
                                            $form_dat_lote=$arrayName = array(
                            'estado' => 'FINALIZADO',
                        );
                                            $where = " WHERE id_lote='$row[id_lote]'";
                                            $insert=_update($table, $form_dat_lote, $where);
                                        } else {
                                            if (($entrada_lote-$cantidad_a_descontar)>0) {
                                                # code...
                                                $table='lote';
                                                $form_dat_lote=$arrayName = array(
                            'cantidad'=>($entrada_lote-$cantidad_a_descontar),
                            'estado' => 'VIGENTE',
                            );
                                                $cantidad_a_descontar=0;

                                                $where = " WHERE id_lote='$row[id_lote]'";
                                                $insert=_update($table, $form_dat_lote, $where);
                                            } else {
                                                # code...
                                                if (($entrada_lote-$cantidad_a_descontar)==0) {
                                                    # code...
                                                    $table='lote';
                                                    $form_dat_lote=$arrayName = array(
                                'cantidad'=>($entrada_lote-$cantidad_a_descontar),
                                'estado' => 'FINALIZADO',
                            );
                                                    $cantidad_a_descontar=0;

                                                    $where = " WHERE id_lote='$row[id_lote]'";
                                                    $insert=_update($table, $form_dat_lote, $where);
                                                } else {
                                                    $table='lote';
                                                    $form_dat_lote=$arrayName = array(
                                'cantidad'=>0,
                                'estado' => 'FINALIZADO',
                            );
                                                    $cantidad_a_descontar=$cantidad_a_descontar-$entrada_lote;
                                                    $where = " WHERE id_lote='$row[id_lote]'";
                                                    $insert=_update($table, $form_dat_lote, $where);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            /*fin arreglar problema con lotes*/
                            if (!$insert) {
                                $l = 0;
                            }

                            $table1= 'movimiento_producto_pendiente';
                            $cant_total=$existencias-$cantidad;
                            $form_data1 = array(
                    'id_movimiento'=>$id_movimiento,
                    'id_producto' => $id_producto,
                    'id_presentacion' => $id_presentacion,
                    'cantidad' => $a_transferir,
                    'costo' => $precio_compra,
                    'precio' => $precio_venta,
                    'fecha' =>  $fecha,
                    'hora' => $hora,
                    'id_sucursal' => $id_sucursal
                );
                            $insert_mov_det = _insert($table1, $form_data1);
                            if (!$insert_mov_det) {
                                $j = 0;
                            }
                        } else {/*significa que no hay nada en stock_ubicacion y no se puede descontar de stock_ubicacion ni de stock*/
                            /*se insertara todo en la tabla de movimientos pendientes*/

                            $table1= 'movimiento_producto_pendiente';
                            $cant_total=$existencias-$cantidad;
                            $form_data1 = array(
                    'id_movimiento'=>$id_movimiento,
                    'id_producto' => $id_producto,
                    'id_presentacion' => $id_presentacion,
                    'cantidad' => $cantidad,
                    'costo' => $precio_compra,
                    'precio' => $precio_venta,
                    'fecha' =>  $fecha,
                    'hora' => $hora,
                    'id_sucursal' => $id_sucursal
                );
                            $insert_mov_det = _insert($table1, $form_data1);
                            if (!$insert_mov_det) {
                                $j = 0;
                            }
                        }
                    }
                    /*Hay suficientes unidades en  el stock_ubicacion para realizar el descargo y se procede normalmente*/
                    else {
                        $table1= 'movimiento_producto_detalle';
                        $cant_total=$existencias-$cantidad;
                        $form_data1 = array(
                'id_movimiento'=>$id_movimiento,
                'id_producto' => $id_producto,
                'cantidad' => $cantidad,
                'costo' => $precio_compra,
                'precio' => $precio_venta,
                'stock_anterior'=>$existencias,
                'stock_actual'=>$cant_total,
                'lote' => 0,
                'id_presentacion' => $id_presentacion,
                'fecha' =>  $fecha,
                'hora' => $hora
                );
                        $insert_mov_det = _insert($table1, $form_data1);
                        if (!$insert_mov_det) {
                            $j = 0;
                        }
                        $table2= 'stock';
                        if ($nrow2==0) {
                            $cant_total=$cantidad;
                            $form_data2 = array(
                    'id_producto' => $id_producto,
                    'stock' => $cant_total,
                    'costo_unitario'=>round(($precio_compra/$unidades), 2),
                    'precio_unitario'=>round(($precio_venta/$unidades), 2),
                    'create_date'=>$fecha_movimiento,
                    'update_date'=>$fecha_movimiento,
                    'id_sucursal' => $id_sucursal
                );
                            $insert_stock = _insert($table2, $form_data2);
                        } else {
                            $cant_total=$existencias-$cantidad;
                            $form_data2 = array(
                    'id_producto' => $id_producto,
                    'stock' => $cant_total,
                    'costo_unitario'=>round(($precio_compra/$unidades), 2),
                    'precio_unitario'=>round(($precio_venta/$unidades), 2),
                    'update_date'=>$fecha_movimiento,
                    'id_sucursal' => $id_sucursal
                );
                            $where_clause="WHERE id_producto='$id_producto' and id_sucursal='$id_sucursal'";
                            $insert_stock = _update($table2, $form_data2, $where_clause);
                        }
                        if (!$insert_stock) {
                            $k = 0;
                        }
                        /*arreglando problema con lotes de nuevo*/
                        $cantidad_a_descontar=$cantidad;
                        $sql=_query("SELECT id_lote, id_producto, fecha_entrada, vencimiento, cantidad
                FROM lote
                WHERE id_producto='$id_producto'
                AND id_sucursal='$id_sucursal'
                AND cantidad>0
                AND estado='VIGENTE'
                ORDER BY vencimiento");

                        $contar=_num_rows($sql);
                        $insert=1;
                        if ($contar>0) {
                            # code...
                            while ($row=_fetch_array($sql)) {
                                # code...
                                $entrada_lote=$row['cantidad'];
                                if ($cantidad_a_descontar>0) {
                                    # code...
                                    if ($entrada_lote==0) {
                                        $table='lote';
                                        $form_dat_lote=$arrayName = array(
                        'estado' => 'FINALIZADO',
                        );
                                        $where = " WHERE id_lote='$row[id_lote]'";
                                        $insert=_update($table, $form_dat_lote, $where);
                                    } else {
                                        if (($entrada_lote-$cantidad_a_descontar)>0) {
                                            # code...
                                            $table='lote';
                                            $form_dat_lote=$arrayName = array(
                            'cantidad'=>($entrada_lote-$cantidad_a_descontar),
                            'estado' => 'VIGENTE',
                        );
                                            $cantidad_a_descontar=0;

                                            $where = " WHERE id_lote='$row[id_lote]'";
                                            $insert=_update($table, $form_dat_lote, $where);
                                        } else {
                                            # code...
                                            if (($entrada_lote-$cantidad_a_descontar)==0) {
                                                # code...
                                                $table='lote';
                                                $form_dat_lote=$arrayName = array(
                            'cantidad'=>($entrada_lote-$cantidad_a_descontar),
                            'estado' => 'FINALIZADO',
                            );
                                                $cantidad_a_descontar=0;

                                                $where = " WHERE id_lote='$row[id_lote]'";
                                                $insert=_update($table, $form_dat_lote, $where);
                                            } else {
                                                $table='lote';
                                                $form_dat_lote=$arrayName = array(
                            'cantidad'=>0,
                            'estado' => 'FINALIZADO',
                            );
                                                $cantidad_a_descontar=$cantidad_a_descontar-$entrada_lote;
                                                $where = " WHERE id_lote='$row[id_lote]'";
                                                $insert=_update($table, $form_dat_lote, $where);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        /*fin arreglar problema con lotes*/
                        if (!$insert) {
                            $l = 0;
                        }
                    }
                }//if ($bandera == "PRODUCTO") {
            } //foreach ($array as $fila){
            $dats_caja = getCaja($caja);
            $nombrecaja =$dats_caja['nombre'];
            if ($a&&$b&&$c&&$x&&$z&&$k&&$j&&$l&&$cre) {
                _commit(); // transaction is committed
                $xdatos['typeinfo']='Success';
                $xdatos['msg']='Registro  Numero: <strong>'.$numero_doc.'</strong>  Guardado con Exito !';
                $xdatos['numdoc']=$numero_doc;
                $xdatos['id_factura']=$id_fact;
                $xdatos['total']=$total;
                $xdatos['ultimo']=$ultimo+1;
                $xdatos['nombrecaja']=$nombrecaja;
                $xdatos['descrip_pago']=$descrip_pago;
                $xdatos['nuevo_stock']=$nuevo_stock;
            } else {
                _rollback(); // transaction rolls back
                $xdatos['typeinfo']='Error';
                $xdatos['msg']='Registro no pudo ser ingresado!'.$a."-".$b."-".$c."-".$x."-".$z."-".$k."-".$j."-".$l;
            }
        }
    } catch (\Throwable $e) {
        $xdatos = [
            'typeinfo' => 'Error',
            'msg' => 'E: ' . $e->getMessage() . ' _E: ' . _error() . ' L: ' . $e->getLine()
        ];

        _rollback();
    }

    echo json_encode($xdatos);
}

function imprimir_fact()
{
    $numero_doc = $_POST['numero_doc'];
    $tipo_impresion= $_POST['tipo_impresion'];
    $id_factura= $_POST['num_doc_fact'];
    $id_sucursal=$_SESSION['id_sucursal'];
    $numero_factura_consumidor = $_POST['numero_factura_consumidor'];
    $direccion=" ";
    $nombreape=" ";
    $fecha_fact=$_POST['fecha_fact'];
    //tipo de pago
    $tipo_pago    = $_POST['tipo_pago'];
    $transaccion  = $_POST['transaccion'];
    $con_pago     = $_POST['tipo_pago'];
    $cambio_fin   = $_POST['cambio'];
    $efectivo_fin = $_POST['efectivo'];
    $tarjeta_fin  = $_POST['tarjeta_fin'];
    //array json de pagos  que vienen del modal puedens er combinado
    $array_json   = $_POST['json_arr'];
    $cuantos      = $_POST['cuantos'];
    //Valido el sistema operativo y lo devuelvo para saber a que puerto redireccionar
    $info = $_SERVER['HTTP_USER_AGENT'];
    if (strpos($info, 'Windows') == true) {
        $so_cliente='win';
    } else {
        $so_cliente='lin';
    }
    $valess="";
    if (isset($_POST['valess'])) {
        $valess=$_POST['valess'];
    }
    $sql_fact="SELECT * FROM factura WHERE id_factura='$id_factura'";
    $result_fact=_query($sql_fact);
    $nrows_fact=_num_rows($result_fact);
    if ($nrows_fact>0) {
        $credito       = 0;
        $total_credito = 0;
        $dias_credito  = 0;
        $alias_pago    = "";
        $dats_ft = _fetch_array($result_fact);
        $fecha_ant = $dats_ft["fecha"];
        $id_cliente= $dats_ft["id_cliente"];
        $total=$dats_ft["total"];
        $correlativo_tik = explode('_', $dats_ft['numero_doc']);
        if ($fecha_fact == "") {
            $fecha_fact = $fecha_ant;
        }
        $table='factura_pago';
        if ($cuantos>0) {
            $array = json_decode($array_json, true);
            foreach ($array as $fila) {
                $alias_pago      = $fila['alias_pago'];
                $subtotal        = $fila['subtotal'];
                $total_facturado = $fila['total_facturado'];
                $datos_extra     = $fila['datos_extra'];
                //$table_fact_det= 'factura_detalle';
                $fdata = array(
                'id_factura'      => $id_factura,
                'alias_tipopago'  => $alias_pago,
                'subtotal'        => $subtotal,
                'total_facturado' => $total_facturado,
                'datos_extra'     => $datos_extra,
                );
                $sql="SELECT  id_factura, alias_tipopago, subtotal
                FROM factura_pago
                WHERE id_factura='$id_factura'
                AND alias_tipopago ='$alias_pago'
                AND  subtotal ='$subtotal'";
                $res=_query($sql);
                if (_num_rows($res)==0) {
                    $insert_pagos = _insert($table, $fdata);
                }
                if ($alias_pago=='CRE') {
                    $table_cr="credito";

                    $form_cr = array(
                'id_cliente'  => $id_cliente,
                'fecha'       => $fecha_fact,
                'tipo_doc'    => $tipo_impresion,
                'numero_doc'  => $numero_doc,
                'id_factura'  => $id_factura,
                'dias'        => $dias_credito,
                'total'       => $total_facturado,
                'abono'       => 0,
                'saldo'       => $total_facturado,
                'finalizada'  => 0,
                'id_sucursal' => $id_sucursal,
                );
                    $insert_cre=_insert($table_cr, $form_cr);
                    if ($insert_cre) {
                        $credito=1;
                        $total_credito =$subtotal;
                        $con_pago=$alias_pago;
                    }
                }
            }
        }
        if ($tipo_impresion=='TIK') {
            $numero_factura_consumidor = (int) $correlativo_tik[0];
        }
        $table_fact= 'factura';
        $form_data_fact = array(
        'impresa' => '1',
        'fecha' => $fecha_fact,
        'num_fact_impresa'=> $numero_factura_consumidor,
        'nombre'          => $nombreape,
        'direccion'       => $direccion,
        'num_transac'     => $transaccion,
        'tipo_pago'       => $con_pago,
        'datos_extra'     => $valess,
        'total_efectivo'  => $efectivo_fin,
        'total_tarjeta'   => $tarjeta_fin,
        'credito'         => $credito,
        'total_credito'   => $total_credito,
      );
        $where_clause="id_factura='$id_factura'";
        $actualizar = _update($table_fact, $form_data_fact, $where_clause);
    }
    switch ($tipo_impresion) {
    case 'TIK':
      $nreg_encode =print_ticket($id_factura);
      break;
    case 'COF':
      $nit= $_POST['nit'];
      $nrc= $_POST['nrc'];
      $info_facturas=print_fact($id_factura, $nit, $nombreape="");
      $nreg_encode['facturar'] =$info_facturas;
      break;
    case 'CCF':
      $nit= $_POST['nit'];
      $nrc= $_POST['nrc'];
      $info_facturas=print_ccf($id_factura, $tipo_impresion, $nit, $nrc, $nombreape);
      $nreg_encode['facturar'] =$info_facturas;
      break;
    case 'ENV':
      $info_facturas=print_envio($id_factura, $tipo_impresion);
      $nreg_encode['facturar'] =$info_facturas;
      break;
  }
    //directorio de script impresion cliente
    $sql_dir_print="SELECT *  FROM config_dir WHERE id_sucursal='$id_sucursal'";
    $result_dir_print=_query($sql_dir_print);
    $row_dir_print=_fetch_array($result_dir_print);
    $dir_print=$row_dir_print['dir_print_script'];
    $shared_printer_win=$row_dir_print['shared_printer_matrix'];
    $shared_printer_pos=$row_dir_print['shared_printer_pos'];
    $nreg_encode['shared_printer_win'] =$shared_printer_win;
    $nreg_encode['shared_printer_pos'] =$shared_printer_pos;
    $nreg_encode['dir_print'] =$dir_print;
    $nreg_encode['sist_ope'] =$so_cliente;
    $nreg_encode['tarjeta_fin'] =$tarjeta_fin;
    echo json_encode($nreg_encode);
}
function agregar_cliente()
{
    //$id_cliente=$_POST["id_cliente"];
    $nombre=$_POST["nombress"];
    $dui=$_POST["dui"];
    $tel1=$_POST["tel1"];
    $tel2=$_POST["tel2"];


    $var1=preg_match('/\x{27}/u', $nombre);
    $var2=preg_match('/\x{22}/u', $nombre);
    if ($var1==true || $var2==true) {
        $nombre =stripslashes($nombre);
    }
    $sql_result=_query("SELECT * FROM cliente WHERE nombre='$nombre'");
    $numrows=_num_rows($sql_result);
    $row_update=_fetch_array($sql_result);
    $id_cliente=$row_update["id_cliente"];
    $name_cliente=$row_update["nombre"];


    //'id_cliente' => $id_cliente,
    $table = 'cliente';
    $form_data = array(
    'nombre' => $nombre,
    'dui' => $dui,
    'telefono1' => $tel1,
    'telefono2' => $tel2,
  );

    if ($numrows == 0 && trim($nombre)!='') {
        $insertar = _insert($table, $form_data);
        $id_cliente=_insert_id();
        if ($insertar) {
            $xdatos['typeinfo']='Success';
            $xdatos['msg']='Registro insertado con exito!';
            $xdatos['process']='insert';
            $xdatos['id_client']=  $id_cliente;
        } else {
            $xdatos['typeinfo']='Error';
            $xdatos['msg']='Registro no insertado !';
        }
    } else {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Registro no insertado !';
    }
    echo json_encode($xdatos);
}
function mostrar_datos_cliente()
{
    $id_cliente=$_POST['id_client'];

    $sql="SELECT * FROM cliente
  WHERE
  id_cliente='$id_cliente'";
    $result=_query($sql);
    $count=_num_rows($result);
    if ($count > 0) {
        for ($i = 0; $i < $count; $i ++) {
            $row = _fetch_array($result);
            $id_cliente=$row["id_cliente"];
            $nombre=$row["nombre"];
            $apellido="";
            $nit=$row["nit"];
            $dui=$row["dui"];
            $direccion=$row["direccion"];
            $telefono1=$row["telefono1"];
            $giro=$row["giro"];
            $registro=$row["nrc"];
        }
    }
    $xdatos['nit']= $nit;
    $xdatos['registro']= $registro;
    $xdatos['nombreape']=   $nombre." ".$apellido;
    echo json_encode($xdatos); //Return the JSON Array
}
function cons_rank()
{
    $id_sucursal = $_SESSION["id_sucursal"];
    $id_producto=$_POST['id_producto'];
    $id_presentacion=$_POST['id_presentacion'];
    $cantidad=$_POST['cantidad'];

    $id_usuario=$_SESSION["id_usuario"];
    $r_precios=_fetch_array(_query("SELECT precios FROM usuario WHERE id_usuario=$id_usuario"));
    $precios=$r_precios['precios'];
    $limit="LIMIT ".$precios;
    $select_rank="<select class='sel_r precio_r form-control'>";
    $sql_rank=_query("SELECT id_prepd,desde,hasta,precio
  FROM presentacion_producto_precio
  WHERE id_presentacion=$id_presentacion
  AND id_sucursal=$id_sucursal
  AND precio!=0
  ORDER BY desde ASC
  $limit");
    $xc = 0;
    $preciop = 0;
    if (_num_rows($sql_rank)>0) {
        while ($rowr=_fetch_array($sql_rank)) {
            $select_rank.="<option value='$rowr[precio]'";
            if (!$xc) {
                $select_rank.=" selected ";
                $preciop=$rowr['precio'];
                $xc = 1;
            }
            $select_rank.=">$rowr[precio]</option>";
        }
    } else {
        $sqlq = _query("SELECT precio FROM presentacion_producto WHERE id_presentacion='$id_presentacion'");
        $datsq = _fetch_array($sqlq);
        $preciop=$datsq['precio'];
        $select_rank.="<option value='$datsq[precio]' selected>$datsq[precio]</option>";
    }
    $select_rank.="</select>";
    $xdatos["precio"] = $preciop;
    $xdatos["precios"] = $select_rank;
    echo json_encode($xdatos); //Return the JSON Array
}
function getpresentacion()
{
    $precio_aut=0;
    if (isset($_REQUEST['precio_aut'])) {
        $precio_aut=$_REQUEST['precio_aut'];
    }
    $id_sucursal=$_SESSION['id_sucursal'];
    $id_presentacion =$_REQUEST['id_presentacion'];
    $cant =$_REQUEST['cant'];
    $sql=_fetch_array(_query("SELECT * FROM presentacion_producto WHERE id_pp=$id_presentacion"));
    $precio=$sql['precio'];
    $unidad=$sql['unidad'];
    $descripcion=$sql['descripcion'];
    $id_producto=$sql['id_producto'];
    $sql_e=_fetch_array(_query("SELECT exento FROM producto WHERE id_producto=$id_producto"));
    $exento=$sql_e['exento'];

    $select_rank="<select class='sel_r precio_r form-control'>";
    $id_sucursal = $_SESSION['id_sucursal'];

    $id_usuario=$_SESSION["id_usuario"];
    $r_precios=_fetch_array(_query("SELECT precios FROM usuario WHERE id_usuario=$id_usuario"));
    $precios=$r_precios['precios'];
    if ($precio_aut>$precios) {
        // code...
        $precios=$precio_aut;
    }

    $preciosArray = _getPrecios($id_presentacion, $precios);
    $xc=0;
    foreach ($preciosArray as $key => $value) {
        // code...
        if ($value>0) {
            $select_rank.="<option value='$value'";
            if ($xc==0) {
                $select_rank.=" selected ";
                $preciop=$value;
                $xc = 1;
            }
            $select_rank.=">$value</option>";
        }
    }
    if ($xc==0) {
        $select_rank.="<option value='0.0'>0.0</option>";
    }
    //$select_rank.="<option value='0.0'>0.0</option>";
    $select_rank.="</select>";

    $des = "<input type='text' id='ss' class='txt_box form-control' value='".$descripcion."' readonly>";
    $xdatos['precio']=$precio;

    if ($exento==1) {
        # code...
        $xdatos['preciop_s_iva']=$precio;
    } else {
        # code...
        $sqkl=_fetch_array(_query("SELECT iva FROM sucursal WHERE id_sucursal=$id_sucursal"));
        $iva=$sqkl['iva']/100;
        $iva=1+$iva;
        $xdatos['preciop_s_iva']= round(($precio/$iva), 8, PHP_ROUND_HALF_DOWN);
    }
    $xdatos['unidad']=$unidad;
    $xdatos['descripcion']=$des;
    $xdatos['descripcion']=$des;
    $xdatos['select_rank']=$select_rank;
    echo json_encode($xdatos);
}
function insertar_preventa()
{
    //date_default_timezone_set('America/El_Salvador');
    $id_factura=$_POST['id_factura'];
    $fecha_movimiento= $_POST['fecha_movimiento'];
    $id_cliente=$_POST['id_cliente'];

    $precio_aut = 0;
    $clave="";

    if (isset($_REQUEST['precio_aut'])) {
        $precio_aut=$_REQUEST['precio_aut'];
        $clave=$_REQUEST['clave'];
    }

    $id_vendedor=$_SESSION['id_usuario'];
    $cuantos = $_POST['cuantos'];
    $array_json=$_POST['json_arr'];
    //  IMPUESTOS
    $total_percepcion= $_POST['total_percepcion'];

    $subtotal=$_POST['subtotal'];
    $subt_bonifica=0;
    if (isset($_POST['subt_bonifica'])) {
        $subt_bonifica=$_POST['subt_bonifica'];
    }

    $sumas=$_POST['sumas'];
    $suma_gravada=$_POST['suma_gravada'];
    $iva= $_POST['iva'];
    $retencion= $_POST['retencion'];
    $venta_exenta= $_POST['venta_exenta'];
    $total_menos_retencion=$_POST['total'];
    $total = $retencion+$_POST['total'];

    $id_empleado=$_SESSION["id_usuario"];
    $id_sucursal=$_SESSION["id_sucursal"];
    $fecha_actual = date('Y-m-d');
    $tipoprodserv =$_POST['subtotal'];

    $insertar_fact=false;
    $insertar_fact_dett=true;
    $insertar_numdoc =false;

    $hora=date("H:i:s");
    $xdatos['typeinfo']='';
    $xdatos['msg']='';
    $xdatos['process']='';

    _begin();

    $a=1;
    $b=1;
    $c=1;

    if ($id_factura==0) {
        $hoy = date("Y-m-d");
        $sql="SELECT MAX(numero_ref) as ref FROM factura WHERE id_sucursal='$id_sucursal' AND fecha='$hoy'";
        $result= _query($sql);
        $rows=_fetch_array($result);
        $ult=$rows['ref']+1;
        $numero_doc = str_pad($ult, 7, "0", STR_PAD_LEFT)."_REF";
    } else {
        $sql_num=_query("SELECT * FROM factura where id_factura=$id_factura AND finalizada = 0");

        if (_num_rows($sql_num) > 0) {
            // code...
            $rw = _fetch_array($sql_num);
            $numero_doc=$rw['numero_doc'];
            $ult=$rw['numero_ref'];
        } else {
            // code...
            $id_factura=0;
            $hoy = date("Y-m-d");
            $sql="SELECT MAX(numero_ref) as ref FROM factura WHERE id_sucursal='$id_sucursal' AND fecha='$hoy'";
            $result= _query($sql);
            $rows=_fetch_array($result);
            $ult=$rows['ref']+1;
            $numero_doc = str_pad($ult, 7, "0", STR_PAD_LEFT)."_REF";
        }
    }

    $abono=0;
    $saldo=0;
    $tipo_documento=$_POST['tipo_impresion'];
    $tipo_entrada_salida='NUM. REFERENCIA INTERNA';

    if ($id_factura=="0") {
        # code...
        $table_fact= 'factura';
        $form_data_fact = array(
      'id_cliente' => $id_cliente,
      'fecha' => $fecha_movimiento,
      'numero_doc' => $numero_doc,
      'referencia' => $numero_doc,
      'numero_ref' => $ult,
      'subtotal' => $subtotal,
      'subt_bonifica' => $subt_bonifica,
      'sumas'=>$sumas,
      'suma_gravado'=>$suma_gravada,
      'iva' =>$iva,
      'retencion'=>$retencion,
      'venta_exenta'=>$venta_exenta,
      'total_menos_retencion'=>$total_menos_retencion,
      'total' => $total,
      'id_usuario'=>$id_empleado,
      'id_empleado' => $id_vendedor,
      'id_sucursal' => $id_sucursal,
      'tipo' => $tipo_entrada_salida,
      'hora' => $hora,
      'finalizada' => '0',
      'abono'=>$abono,
      'saldo' => $saldo,
      'tipo_documento' => $tipo_documento,
      'precio_aut' => $precio_aut,
      'clave' => $clave,
    );
        $insertar_fact = _insert($table_fact, $form_data_fact);
        $id_fact= _insert_id();

        if (!$insertar_fact) {
            # code...
            $b=0;
        }
    } else {
        # code...
        $table_fact= 'factura';
        $form_data_fact = array(
      'id_cliente' => $id_cliente,
      'fecha' => $fecha_movimiento,
      'numero_doc' => $numero_doc,
      'referencia' => $numero_doc,
      'numero_ref' => $ult,
      'subtotal' => $subtotal,
      'subt_bonifica' => $subt_bonifica,
      'sumas'=>$sumas,
      'suma_gravado'=>$suma_gravada,
      'iva' =>$iva,
      'retencion'=>$retencion,
      'venta_exenta'=>$venta_exenta,
      'total_menos_retencion'=>$total_menos_retencion,
      'total' => $total,
      'id_usuario'=>$id_empleado,
      'id_empleado' => $id_vendedor,
      'id_sucursal' => $id_sucursal,
      'tipo' => $tipo_entrada_salida,
      'hora' => $hora,
      'finalizada' => '0',
      'abono'=>$abono,
      'saldo' => $saldo,
      'tipo_documento' => $tipo_documento,
      'precio_aut' => $precio_aut,
      'clave' => $clave,
    );
        $whereclause="id_factura='".$id_factura."'";
        $insertar_fact = _update($table_fact, $form_data_fact, $whereclause);
        $id_fact= $id_factura;

        if (!$insertar_fact) {
            # code...
            $b=0;
        }
        $table="factura_detalle";
        $where_clause="id_factura='".$id_fact."'";
        $delete=_delete($table, $where_clause);
        if (!$delete) {
            $b=0;
        }
    }

    if ($cuantos>0) {
        $array = json_decode($array_json, true);
        foreach ($array as $fila) {
            $id_producto=$fila['id'];
            $unidades=$fila['unidades'];
            $subtotal=$fila['subtotal'];
            $cantidad=$fila['cantidad'];
            $bonificacion=$fila['bonificacion'];
            $id_presentacion=$fila['id_presentacion'];
            $bandera=$fila['tipo_prod_serv'];
            $cantidad_real=$cantidad*$unidades;
            $bonifica_real = $bonificacion * $unidades;
            $exento=$fila['exento'];
            $precio_venta=$fila['precio'];
            $subt_bonifica=round($precio_venta * $bonifica_real, 4);
            $table_fact_det= 'factura_detalle';
            $tipoprodserv =$bandera;
            $data_fact_det = array(
        'id_factura' => $id_fact,
        'id_prod_serv' => $id_producto,
        'cantidad' => $cantidad_real,
        'bonificacion' => $bonifica_real,
        'subt_bonifica' => $subt_bonifica,
        'precio_venta' => $precio_venta,
        'subtotal' => $subtotal,
        'tipo_prod_serv' => $fila['tipo_prod_serv'],
        'id_empleado' => $id_empleado,
        'id_sucursal' => $id_sucursal,
        'fecha' => $fecha_movimiento,
        'id_presentacion'=> $id_presentacion,
        'exento' => $exento,
      );
            $insertar_fact_det = _insert($table_fact_det, $data_fact_det);
            if (!$insertar_fact_det) {
                # code...
                $c=0;
            }
        } //foreach ($array as $fila){
        if ($a&&$b&&$c) {
            _commit(); // transaction is committed
            $xdatos['typeinfo']='Success';
            $xdatos['msg']='Referenca Numero: <strong>'.$numero_doc.'</strong>  Guardado con Exito !';
            $xdatos['referencia']=$ult;
            $xdatos['tot']=number_format($total, 2);
        } else {
            _rollback(); // transaction rolls back
            $xdatos['typeinfo']='Error';
            $xdatos['msg']='Registro no pudo ser ingresado!'.$a."-".$b."-".$c;
        }
    }
    echo json_encode($xdatos);
}
function borrar_preventa()
{
    _begin();
    $id_factura=$_REQUEST['id_factura'];
    $table='factura';
    $where_clause="id_factura='".$id_factura."'";
    $delete=_delete($table, $where_clause);
    if ($delete) {
        $table="factura_detalle";
        $where_clause="id_factura='".$id_factura."'";
        $delete=_delete($table, $where_clause);
        if ($delete) {
            _commit();
            $xdatos['typeinfo']="Success";
            $xdatos['msg']="Registro eliminado correctamente";
        } else {
            _rollback();
            $xdatos['typeinfo']="Error";
            $xdatos['msg']="Error al insertar el registro";
        }
    } else {
        _rollback();
        $xdatos['typeinfo']="Error";
        $xdatos['msg']="Error al insertar el registro";
    }
    echo json_encode($xdatos);
}

function getcode()
{
    $clave = $_REQUEST['clave'];
    $id_usuario=$_SESSION['id_usuario'];

    $sql = _query("SELECT * FROM precio_aut where clave='$clave' and aplicado=0");

    if (_num_rows($sql)>0) {
        $row = _fetch_array($sql);

        $table='precio_aut';
        $form_data = array(
      'aplicado' => 1,
      'fecha_aplicado' => date("Y-m-d"),
      'id_usuario' => $id_usuario,
    );
        $where_clause = "id = $row[id]";
        $update = _update($table, $form_data, $where_clause);

        if ($update) {
            $xdatos['typeinfo']="Success";
            $xdatos['msg']="Codigo valido";
            $xdatos['precio']=$row['precio'];
            $xdatos['clave']=$clave;
        }
    } else {
        $xdatos['typeinfo']="Error";
        $xdatos['msg']="Error, codigo inexistente";
    }
    echo json_encode($xdatos);
}

function tabla()
{
    $precio_aut=$_REQUEST['precio_aut'];
    $id_sucursal=$_SESSION['id_sucursal'];
    $id_presentacion =$_REQUEST['id_presentacion'];
    $cant =$_REQUEST['cant'];
    $sql=_fetch_array(_query("SELECT * FROM presentacion_producto WHERE id_pp=$id_presentacion"));
    $precio=$sql['precio'];
    $unidad=$sql['unidad'];
    $descripcion=$sql['descripcion'];
    $id_producto=$sql['id_producto'];
    $sql_e=_fetch_array(_query("SELECT exento FROM producto WHERE id_producto=$id_producto"));
    $exento=$sql_e['exento'];

    $select_rank="<select class='sel_r precio_r form-control'>";
    $id_sucursal = $_SESSION['id_sucursal'];

    $id_usuario=$_SESSION["id_usuario"];
    $r_precios=_fetch_array(_query("SELECT precios FROM usuario WHERE id_usuario=$id_usuario"));
    $precios=$r_precios['precios'];
    if ($precio_aut>$precios) {
        // code...
        $precios=$precio_aut;
    }

    $preciosArray = _getPrecios($id_presentacion, $precios);
    $xc=0;

    $precios_mc = array();
    foreach ($preciosArray as $key => $value) {
        // code...
        if ($value>0) {
            $precios_mc[$xc]=$value;
            $xc++;
        }
    }

    $xd=1;
    foreach ($precios_mc as $key => $value) {
        // code...
        $select_rank.="<option value='$value'";
        if ($xd==$xc) {
            $select_rank.=" selected ";
            $preciop=$value;
        }
        $select_rank.=">$value</option>";
        $xd++;
    }

    if ($xc==0) {
        $select_rank.="<option value='0.0'>0.0</option>";
    }
    //$select_rank.="<option value='0.0'>0.0</option>";
    $select_rank.="</select>";

    $des = "<input type='text' id='ss' class='txt_box form-control' value='".$descripcion."' readonly>";
    $xdatos['precio']=$preciop;

    if ($exento==1) {
        # code...
        $xdatos['preciop_s_iva']=$precio;
    } else {
        # code...
        $sqkl=_fetch_array(_query("SELECT iva FROM sucursal WHERE id_sucursal=$id_sucursal"));
        $iva=$sqkl['iva']/100;
        $iva=1+$iva;
        $xdatos['preciop_s_iva']= round(($precio/$iva), 8, PHP_ROUND_HALF_DOWN);
    }
    $xdatos['unidad']=$unidad;
    $xdatos['descripcion']=$des;
    $xdatos['descripcion']=$des;
    $xdatos['select_rank']=$select_rank;
    echo json_encode($xdatos);
}
function mostrar_cliente()
{
    $id_cliente = $_REQUEST['id_cliente'];
    $sql="SELECT  ct.nombre, ct.direccion, ct.nit, ct.nrc, ct.dui,
    ct.retiene, ct.retiene10, ct.depto, ct.municipio,ct.id_vendedor,ct.categoria,
    coalesce(ct.limite_credito,0) AS limite_credito,
    COALESCE(SUM(COALESCE(cr.saldo,0)),0) AS saldo_pendiente,
    (COALESCE(ct.limite_credito,0)- COALESCE(SUM(COALESCE(cr.saldo,0)),0)) AS saldo_disponible,
    COALESCE(COUNT(cr.id_credito),0) as numcredits
    FROM cliente as ct
    JOIN credito AS cr ON ct.id_cliente= cr.id_cliente
    WHERE ct.id_cliente='$id_cliente'
    AND cr.finalizada=0
    ";
    $result = _query($sql);
    $count = _num_rows($result);
    $row = _fetch_array($result);
    $emp="Vendedor No Asignado";
    if ($count>0) {
        if ($row['id_vendedor']!=0) {
            $emp=getEmpleado($row['id_vendedor']);
        }
        $row['nombreVendedor']=$emp;
        //categoria de cliente para facturacion
        $sqlcat="SELECT nombre FROM categoria_proveedor WHERE id_categoria='".$row['categoria']."'";
        $rcat = _query($sqlcat);
        $countcat = _num_rows($rcat);
        if ($countcat>0) {
            $rowcat = _fetch_array($rcat);
            $catcontrib=$rowcat['nombre'];
            if (strtoupper($catcontrib)=='CONSUMIDOR') {
                $row['tipo_impresion']='COF';
            } else {
                $row['tipo_impresion']='CCF';
            }
            if ($id_cliente<=0) {
                $row['tipo_impresion']='TIK';
            }
        } else {
            if ($id_cliente>0) {
                $row['tipo_impresion']='COF';
            } else {
                $row['tipo_impresion']='TIK';
            }
        }
    }
    echo json_encode($row);
}
function limit_cedit($id_cliente)
{
    $sql="SELECT  ct.limite_credito, SUM(cr.saldo) AS saldo,
    (ct.limite_credito- SUM(cr.saldo)) as disponible,
     count(id_factura) as numcredits
    FROM cliente as ct
    JOIN  credito AS cr ON ct.id_cliente= cr.id_cliente
    WHERE ct.id_cliente='$id_cliente'
    AND cr.finalizada=0 ";
}
function modalPago()
{
    ?>
  <div class="modal fade" id="modalPago" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content  modal-md">
            <div class="modal-header">
              <h3 class="modal-title text-center text-success textmodalPrint"
                 id="myModalLabel">Impresión  &nbsp;
                <span class="fa-2x fa-solid fa-store text-success"></span></h3>
                <div class="row">
              <div class="col-md-3">
                <h5 class="text-primary text-center">Valor Facturado: $</h5>
              </div>
              <div class="col-md-3"><strong>
                <input type="text" id="facturado" name="facturado" value=""
                class="input_clear text-green" readonly ></strong>
              </div>
              <div class="col-md-6 text-center" >
                  <h5 class="text-primary text-center">&nbsp;Documento :
                  <span class="  text-success"id='fact_num'></span></h5>
              </div>
            </div>
          </div>
            <div class="modal-body">
              <div class="row text-center">
                <input type='hidden' id='docImpresion' name=='docImpresion' value=''/>
                <div class="row">
                  <div class="col-md-5   metodos_pago "></div>
                  <div class="col-md-5 ">

                  <center><h5>INGRESAR INFORMACION SEGUN TIPO PAGO SELECCIONADO</h5></center>
                  <table	class="clean " id="tableview">
                  <tbody id='efecttiv' >
                    <tr class='effectivo'>
                      <td><h5 class='text-danger '>Efectivo $ </h5></td>
                      <td><input type="text" id="efectivo" name="efectivo" value=""  class=" decimal form-control montoMetodoPago"> </td>
                    </tr>
                    <tr  class='effectivo'>
                      <td><h5 class='text-success '>Cambio $</h5> </td>
                      <td> <input type="text" id="cambio" name="cambio" value=0 placeholder="cambio"
                         class="input_clear decimal" readonly ></td>
                    </tr>
                  </tbody>
                  <tbody id='creditt' hidden >
                    <tr class='creditoss'>
                      <td><h5 class='text-danger'>Monto $ </h5></td>
                      <td><input type="text" id="valcredit" name="valcredit" value=""  class=" decimal form-control montoMetodoPago"> </td>
                    </tr>
                    <tr  class='creditoss'>
                      <td><h5 class='text-success'>Dias </h5> </td>
                      <td> <input type="text" id="diascredit" name="diascredit" value=""  class=" decimal form-control"  ></td>
                    </tr>
                  </tbody>
                  <tbody id='tarjet' hidden>
                    <tr class='tarjjeta'>
                      <td><h5 class='text-danger '>Monto $ </h5></td>
                      <td><input type="text" id="tarj" name="tarj" value=""  class=" decimal form-control montoMetodoPago"> </td>
                    </tr>
                    <tr class='tarjjeta'>
                      <td><h5 class='text-success '>Transaccion</h5> </td>
                      <td> <input type="text" id="transac" name="transac" class="form-control" ></td>
                    </tr>
                  </tbody>
                  <tbody id='cheques' hidden>
                    <tr class='chequess'>
                      <td><h5 class='text-danger '>Monto $ </h5></td>
                      <td><input type="text" id="valorcheque" name="valorcheque" value=""  class=" decimal form-control montoMetodoPago"> </td>
                    </tr>
                    <tr class='chequess'>
                      <td><h5 class='text-success '># de Cheque</h5> </td>
                      <td><input type="text" id="numcheque" name="numcheque" class="form-control" ></td>
                    </tr>
                    <tr class='chequess'>
                      <td><h5 class='text-success '>Banco</h5> </td>
                      <td><input type="text" id="banco" name="banco" class="form-control" ></td>
                    </tr>
                  </tbody>
                  <tbody id='transf' hidden>
                    <tr class='transferencia'>
                      <td><h5 class='text-danger '>Monto $ </h5></td>
                      <td><input type="text" id="valortransferencia" name="valortransferencia" value=""  class=" decimal form-control montoMetodoPago"> </td>
                    </tr>
                    <tr class='transferencia'>
                      <td><h5 class='text-success '># de Transferencia</h5> </td>
                      <td><input type="text" id="numtransferencia" name="numtransferencia" class="form-control" ></td>
                    </tr>
                    <tr class='transferencia'>
                      <td><h5 class='text-success '>Banco</h5> </td>
                      <td><input type="text" id="banco" name="banco" class="form-control" ></td>
                    </tr>
                  </tbody>
                  <tbody id='valess' hidden>
                    <tr class='val'>
                      <td><h5 class='text-danger '>Monto $ </h5></td>
                      <td><input type="text" id="montovale" name="montovale" value=""  class=" decimal form-control montoMetodoPago"> </td>
                    </tr>
                    <tr class='val'>
                      <td><h5 class='text-success'>Del No. </h5></td>
                      <td><input type="text" id="del" name="del" value="" placeholder="Del No."  class="form-control numeric"> </td>
                    </tr>
                    <tr  class='val'>
                      <td><h5 class='text-success'>Al No.</h5> </td>
                      <td> <input type="text" id="al" name="al" value="" placeholder="Al No." class="form-control numeric" ></td>
                    </tr>
                    <tr  class='val'>
                      <td><h5 class='text-success'>Placa No.</h5> </td>
                      <td> <input type="text" id="placa" name="placa" value="" placeholder="Placa  No." class="form-control " ></td>
                    </tr>
                    <tr  class='val'>
                      <td><h5 class='text-success'>KM</h5> </td>
                      <td> <input type="text" id="km" name="km" value="" placeholder="kms" class="form-control decimal" ></td>
                    </tr>
                    <tr  class='val'>
                      <td><h5 class='text-success'>NIT</h5> </td>
                      <td> <input type="text" id="nitt" name="nitt" value="" placeholder="NIT" class="form-control" ></td>
                    </tr>
                    <tr  class='val'>
                      <td><h5 class='text-success'>Observación</h5> </td>
                      <td> <input type="text" id="observ" name="observ" value="" placeholder="observaciones"
                        class="form-control" ></td>
                    </tr>
                  </tbody>

                  <tbody id='totales'>
                    <tr  class='tot'>
                      <td><h5 class='text-success'>Diferencia</h5> </td>
                      <td> <input type="text" id="diferencia_" name="diferencia_" value="" class="input_clear" readonly></td>
                    </tr>
                  </tbody>
                </table>
              </div>

              </div>
              <div class="row ">
              <div class="col-md-12 tablaPagos" hidden>
                <div class="table-responsive">
                  <table class='table table-bordered' id='pagoss'>
                    <thead>
                      <th class="col-lg-3">PAGO</th>
                      <th class="col-lg-5">INFO</th>
                      <th class="col-lg-3 text-right">VALOR $</th>
                      <th class="col-lg-1">ACCI&Oacute;N</th>
                    </thead>
                    <tbody id='pagos'></tbody>
                    <tfoot id='totales_fin'>
                      <tr  class='tot'>

                        <th class="col-lg-8" colspan=2>
                          <h5 class='text-success'>TOTALES $</h5></th>
                          <th class="col-lg-3 text-right ">
                          <input type="text" id="tot_fin" name="tot_fin" value="0"
                          class="input_clear text-right" readonly>
                        </th>
                        <th class="col-lg-1"></th>
                      </tr>
                    </tfoot>
                  </table>
                </div>
                </div>
              </div>

              <div class="row">
                  <div class="multi-button">
                  <button  class="btn buttons invoic" id="btnAddPayment">
                    <span>     + Pago </span></button>
                  <button   class="btn buttons prn" id="btnPrintFact" disabled>
                    <span>     Imprimir </span></button>
                  <button    class="btn buttons clos" id="btnEsc" disabled>
                    <span>     Salir </span></button>
                </div>
              </div>




              <input type="hidden" name="id_cuenta" id="id_cuenta" value=-1>
              <input type="hidden" name="id_factt" id="id_factt" value="">
              <input type="hidden" name="data_extra" id="data_extra" value="">
            </div>
          </div>
        </div>
      </div>
 <?php
}
function modalDataCliente()
{
    ?>
    <div class="modal fade" id="modalDataCliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content  modal-md">
            <div class="modal-header">
              <h3 class="modal-title text-center text-success "
                 id="myModalLabel">Datos de Crédito de Cliente  &nbsp;
                <span class="fa-2x fa-solid fa-store text-success"></span></h3>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>
            <div class="modal-body">
            <div class='row'>
                                <div  class="form-group col-md-12">
                                  <div class="form-group has-info">
                                    <label>Limite de Cr&eacute;dito</label>
                                    <input maxlength="8" type="text" class="form-control" id="limite_credito" name="limite_credito" value="" readonly>
                                  </div>
                                </div>
                                <div  class="form-group col-md-12">
                                  <div class="form-group has-info">
                                    <label>Total  Cr&eacute;dito no Cancelado</label>
                                    <input maxlength="8" type="text" class="form-control" id="saldo_pendiente" name="saldo_pendiente" value="" readonly>
                                  </div>
                                </div>
                                <div  class="form-group col-md-12">
                                  <div class="form-group has-info">
                                    <label>Saldo disponible</label>
                                    <input maxlength="8" type="text" class="form-control text-success" id="saldo_disponible" name="saldo_disponible" value="" readonly>
                                  </div>
                                </div>
                                <!--div  class="form-group col-md-12">
                                  <div class="form-group has-info">
                                    <label>Total Dispone Esta Factura</label>
                                    <input maxlength="8" type="text" class="form-control text-warning" id="disponible_fac" name="disponible_fac" value="" readonly>
                                  </div>
                                </div-->
                        </div>

            </div>
            <div class="modal-footer"></div>
        </div>
      </div>
    </div>
  <?php
}

function validarNumdoc()
{
    //SELECT * FROM resolucion WHERE alias='COF' AND vigente=1
    $numero = trim($_REQUEST['numeroDocImpreso']);
    $alias = $_REQUEST['tipo_impresion'];
    $q="SELECT * FROM resolucion
    WHERE '$numero' BETWEEN desde AND hasta
    AND alias='$alias' AND vigente=1
    ";
    $valido=true;

    $res = _query($q);
    $numrows= _num_rows($res);
    if ($numrows > 0) {
        $row=_fetch_row($res);
        $id_resolucion= $row[0];
        //CONVERT(num_fact_impresa,UNSIGNED INTEGER)=
        $q2="SELECT id_factura FROM factura
      WHERE id_resolucion='$id_resolucion'
      AND CONVERT(num_fact_impresa,UNSIGNED INTEGER)='$numero'
       ";
        $res2 = _query($q2);
        $numrows2= _num_rows($res2);
        if ($numrows2 > 0) {
            $valido=false;
            $xdatos['valido'] = $valido ;
            $xdatos['typeinfo']='Error';
            $xdatos['msg']='Número de documento ya esta registrado previamente';
        }
    } else {
        $valido=false;
        $xdatos['valido'] = $valido ;
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Número de documento no existe en el rango autorizado actual!';
    }

    if ($valido==true) {
        $xdatos['typeinfo']='Success';
        $xdatos['msg']='Numero de documento válido!';
        $xdatos['valido'] = $valido ;
    }

    echo json_encode($xdatos);
}
//verificar metdos de pago por cliente, en especial creditos
function clienteMetodoPago()
{
    $id_cliente = $_POST['id_cliente'];
    $facturado  = $_POST['facturado'];
    $id_factura = $_POST['id_factura'];
    $saldo_disponible =0;

    $sql="SELECT  ct.nombre, ct.direccion, ct.nit, ct.nrc, ct.dui,
    ct.retiene, ct.retiene10, ct.depto, ct.municipio,ct.id_vendedor,ct.categoria,
    COALESCE(ct.limite_credito,0) AS limite_credito,
    COALESCE(SUM(COALESCE(cr.saldo,0)),0) AS saldo_pendiente,
   (COALESCE(ct.limite_credito,0)- COALESCE(SUM(COALESCE(cr.saldo,0)),0)) AS saldo_disponible,
    COALESCE(COUNT(cr.id_credito),0) as numcredits
    FROM cliente as ct
    JOIN credito AS cr ON ct.id_cliente= cr.id_cliente
    WHERE ct.id_cliente='$id_cliente'
    AND cr.finalizada=0
    ";
    $result = _query($sql);
    $count = _num_rows($result);
    $mostrar="";
    if ($count>0) {
        $row = _fetch_array($result);
        $saldo_disponible = sprintf("%.2f", $row['saldo_disponible']);
        $saldo_disponible = $saldo_disponible > 0 ? $saldo_disponible : '0.0';
        $limite_credito = $row['limite_credito'];
        $cliente = $row['nombre'];
        if ($limite_credito>0 && $saldo_disponible> $facturado) {
            $mostrar="<div class='col-md-12'><label class='text-success  '>Cliente: ".$cliente." </label>&nbsp;";
            $mostrar.="<label class='text-success'> AUTORIZADO, Cr&eacute;dito disponible:$".$saldo_disponible." </label></div>";
            $mostrar.="<div class='col-md-12'><label class='text-primary text-center'>M&eacute;todo de Pago";
            $array2= getTipoPago();
            $mostrar.= create_select_list("metodo_pago", $array2, 'CON', "width:100%;", "5");
            $mostrar.="</label></div>";
        } else {
            $mostrar="<div class='col-md-12 '><label class='text-success  '>Cliente: ".$cliente." </label>&nbsp;";
            $mostrar.="<label class='text-danger '>  Cr&eacute;dito NO AUTORIZADO, saldo disponible:$".$saldo_disponible." </label></div>";
            $mostrar.="<div class='col-md-12'><label class='text-primary text-center'>M&eacute;todo de Pago";
            $array2= getMetodosPagoNoCred();
            $mostrar.= create_select_list("metodo_pago", $array2, 'CON', "width:100%;", "5");
            $mostrar.=" </label></div> ";
        }
    } else {
        $mostrar="<div class='col-md-12 '><label class='text-success  '>Cliente: ".$cliente." </label>&nbsp;";
        $mostrar.="<label class='text-danger '>  Cr&eacute;dito NO AUTORIZADO, saldo disponible:$".$saldo_disponible." </label></div>";
        $mostrar.="<div class='col-md-12'><label class='text-primary text-center'>M&eacute;todo de Pago";
        $array2= getMetodosPagoNoCred();
        $mostrar.= create_select_list("metodo_pago", $array2, 'CON', "width:100%;", "5");
        $mostrar.=" </label></div> ";
    }
    //$mostrar.=" <div class='row'> </div>";
    $xdatos['datos']=$mostrar;
    echo json_encode($xdatos);
}

function consultar_servicio()
{
    $tipo_doc = $_POST["tipo_doc"];
    $id_servicio = $_POST["id_servicio"];
    $id_usuario = $_SESSION["id_usuario"];
    $id_sucursal=$_SESSION['id_sucursal'];
    $precio=0;
    $categoria="";
    $sql_servicio = _query("SELECT * FROM servicios WHERE id_servicio = '$id_servicio'");
    $num_rows = _num_rows($sql_servicio);
    if ($num_rows > 0) {
        $row1 = _fetch_array($sql_servicio);
        $descripcion = $row1["descripcion"];
        $categoria=$row1['id_categoria'];
        $precio = $row1["precio_iva"];
        if ($tipo_doc == "CCF") {
            $precio = $row1["precio"];
        }
    }
    $select="<select class='sel2 form-control' readonly>";
    $select.="<option value='1'>UNIDAD</option>";
    $select.="</select>";
    $select_rank="<select class='sel_rr form-control' readonly>";
    $select_rank.="<option value='0.0'>0.0</option>";
    $select_rank.="</select>";
    $align=new AlignMarginText();
    $hoy=date("Y-m-d");
    $descripc="-";
    $descripts = $align->wordwrap1($descripcion, 60);
    foreach ($descripts as $descrip) {
        $descripc.=$descrip."<br>";
    }
    $xdatos['perecedero'] = 0;
    $xdatos['decimals']   = 0;
    $xdatos['descripcion']= $descripc;
    $xdatos['id_producto']= $id_servicio;
    $xdatos['stock']= 10;
    $xdatos['select']= $select;
    $xdatos['select_rank']= $select_rank;
    $xdatos['categoria']=$categoria;
    $xdatos['preciop'] = $precio;
    echo json_encode($xdatos);
}

function existeFactura($id_factura)
{
    //funcion que verifica si ya fue finalizada una factura
    $q="SELECT * FROM factura WHERE id_factura='$id_factura' AND finalizada=1";
    $r=_query($q);
    $existe=0;
    if (_num_rows($r)>0) {
        $existe=1;
    }
    return  $existe;
}
function getLimiteCredito()
{
    $id_cliente = $_POST["id_cliente"];
    $row= limiteCreditoId($id_cliente);
    $xdatos['datos']=$row;
    echo json_encode($xdatos);
}
//modal descuentos
function modalDescuento()
{
    //ir a traer valor de tabla SELECT id, valor, limite, inactivo FROM descuentos_descuento?>
    <div class="modal fade" id="modalDescuento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content  modal-md">
            <div class="modal-header">
              <h3 class="modal-title text-center text-success "
                 id="myModalLabel">Descuento  </h3>
             </div>
            <div class="modal-body">
                <div class='row'>
                <div  class="form-group col-md-12">
                        <div class="form-group has-info">
                           <label>Cambiar Tipo de Descuento  (F6 <-> Porcentual % ó Valor $)
                           <button type="button" class="btn btn-success"  id='btnSwitch' name='btnSwitch'>
                           <i class="fa fa-arrows-h"></i> F6&nbsp;</button></label>
                        </div>
                      </div>
                     <input type="hidden" id='autorizado' name='autorizado' value=0 />
                     <!--tipo descuento puede tomar 0 y 1 donde 0(cero) sera descuento por valor y 1 por porcentaje-->
                     <input type="hidden" id='tipo_descuento' name='tipo_descuento' value=0 />
                     <div  class="form-group col-md-12">
                     <center><h3 class='text-danger'>Digite usuario y password y dar Enter en casilla password para verificar</h3></center>
                     </div>
                     <div  class="form-group col-md-6">
                        <div class="form-group has-info">

                          <label class='txt'>Usuario</label>
                           <input type="text" class="form-control pass" id="usuario" name="usuario" value=""   >
                        </div>
                      </div>

                      <div  class="form-group col-md-6">
                        <div class="form-group has-info">
                          <label class='txt'>Password</label>
                           <input  type="password" class="form-control pass" id="pasword" name="pasword" value=""   >
                        </div>
                      </div>
                      <div  class="datos_descuento" hidden>
                      <div  class="form-group col-md-6 porcentual">
                        <div class="form-group has-info">
                          <label class='txtLabelDesc'><h5 class='text-success'>Descuento por Valor $</h5></label>
                           <input maxlength="8" type="text" class="form-control decimal" id="valor" name="valor" value=""   >
                        </div>
                      </div>

                      <div  class="form-group col-md-6 mensajes">
                      </div>
                      </div>
                      <div  class="form-group col-md-6">
                        <div class="form-group has-info">
                          <label>Total Facturado $</label>
                          <input maxlength="8" type="text" class="form-control text-success decimal" id="total_venta" name="total_venta" value="" readonly>
                        </div>
                      </div>
                      <div  class="form-group col-md-6">
                        <div class="form-group has-info">
                          <label>Total Descontar $</label>
                          <input maxlength="8" type="text" class="form-control text-success decimal" id="total_descontar"
                           name="total_descontar" value="0" readonly>
                        </div>
                      </div>
                </div>
            </div>
            <div class="modal-footer">
	<button type="button" class="btn btn-success" id='btnSaveDescuentos'><i class="fa fa-save"></i>&nbsp;Aceptar</button>
    <button type="button" class="btn btn-danger" id='btnCloseView2'>Cerrar</button>
</div>
        </div>
      </div>
    </div>
  <?php
}
//functions to load
if (!isset($_REQUEST['process'])) {
    initial();
}
//else {
if (isset($_REQUEST['process'])) {
    switch ($_REQUEST['process']) {
    case 'formEdit':
      initial();
      break;
      case 'insert':
      insertar();
      break;
      case 'mostrar_datos_cliente':
      mostrar_datos_cliente();
      break;
      case 'consultar_stock':
      consultar_stock();
      break;
      case 'cargar_empleados':
      cargar_empleados();
      break;
      case 'cargar_precios':
      cargar_precios();
      break;
      case 'total_texto':
      total_texto();
      break;
      case 'imprimir_fact':
      imprimir_fact();
      break;
      case 'print2':
      print2(); //Generacion de los datos de factura que se retornan para otro script que imprime!!!
      break;
      case 'mostrar_numfact':
      mostrar_numfact();
      break;
      case 'reimprimir':
      reimprimir();
      break;
      case 'agregar_cliente':
      agregar_cliente();
      break;
      case 'cargar_data':
      cargar_data();
      break;
      case 'cons_rank':
      cons_rank();
      break;
      case 'getpresentacion':
      getpresentacion();
      break;
      case 'insert_preventa':
      insertar_preventa();
      break;
      case 'borrar_preventa':
      borrar_preventa();
      break;
      case 'getcode':
      getcode();
      break;
      case 'tabla':
      tabla();
      break;
      case 'mostrar_cliente':
      mostrar_cliente();
      break;
      case 'validarNumdoc':
      validarNumdoc();
      break;
      case 'clienteMetodoPago':
      clienteMetodoPago();
      break;
      case 'consultar_servicio':
        consultar_servicio();
        break;
      case 'getLimiteCredito':
        getLimiteCredito();
        break;
    }
}
  ?>
