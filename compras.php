<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: Wed, 1 Jan 2020 00:00:00 GMT"); // Anytime in the past
 ?>
<?php
include_once "_core.php";

function initial()
{
    $title = "Compra de producto";
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
    $_PAGE ['links'] .= '<link href="css/plugins/sweetalert/sweetalert.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/bootstrap-checkbox/bootstrap-checkbox.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link rel="stylesheet" type="text/css" href="css/main_co.css">';
    $_PAGE ['links'] .= '<link rel="stylesheet" type="text/css" href="css/plugins/perfect-scrollbar/perfect-scrollbar.css">';
    $_PAGE ['links'] .= '<link rel="stylesheet" type="text/css" href="css/util_co.css">';

    include_once "header.php";
    $sql="SELECT * FROM producto";
    $result=_query($sql);
    $count=_num_rows($result);
    //permiso del script
    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];
    $id_sucursal=$_SESSION["id_sucursal"];

    $uri = $_SERVER['SCRIPT_NAME'];
    $filename=get_name_script($uri);
    $links=permission_usr($id_user, $filename);
    $fecha_actual=date("Y-m-d");

    $iva=0;
    $sql_iva="select iva,monto_retencion1,monto_retencion10,monto_percepcion FROM sucursal WHERE  id_sucursal=$_SESSION[id_sucursal]";
    $result_IVA=_query($sql_iva);
    $row_IVA=_fetch_array($result_IVA);
    $iva=$row_IVA['iva']/100;
    $monto_percepcion=$row_IVA['monto_percepcion'];

    $id_compra = 0;

    if (isset($_REQUEST['id_compra'])) {
        $id_compra=$_REQUEST['id_compra'];
    }

    $id_proveedor=-1;
    $tipo_documento = "CCF";
    $numero_doc="";
    $id_ubicacion="";
    $fecha="";
    $dias_credito="";
    $percepcion=0;
    $sql_c = _query("SELECT * FROM compra2 where id_compra=$id_compra");

    if (_num_rows($sql_c)>0) {
        $d=_fetch_array($sql_c);
        $id_proveedor = $d['id_proveedor'];
        $tipo_documento = $d['alias_tipodoc'];
        $numero_doc= $d['numero_doc'];
        $fecha = $d['fecha'];
        $id_ubicacion= $d['id_ubicacion'];
        $dias_credito = $d['dias_credito'];
    } else {
        if ($id_compra!=0) {
            ?>
    <script type="text/javascript">
      location.href = "compras.php";
    </script>
    <?php
        }
    }
    //array de tipos Documento
    $sql3='SELECT idtipodoc, nombredoc, provee,  alias FROM tipodoc
     WHERE provee=1 AND interno=0';
    $result3=_query($sql3);
    $count3=_num_rows($result3);
    $array3= array(-1=>"Seleccione");
    for ($z=0;$z<$count3;$z++) {
        $row3=_fetch_array($result3);
        $id3=$row3['alias'];
        $description3=$row3['nombredoc'];
        $array3[$id3] = $description3;
    } ?>
  <div class="gray-bg">
    <div class="wrapper wrapper-content  animated fadeInRight">
      <div class="row">
        <div class="col-lg-12">
          <div class="ibox">
            <?php if ($links!='NOT' || $admin=='1') { ?>
              <div class="ibox-content">

                <div class='row'>
                  <div hidden class="col-lg-4">
                    <div class="form-group has-info">
                      <label>Concepto</label>
                      <input type='text' class='form-control' value='COMPRA DE PRODUCTO' id='concepto' name='concepto'>
                    </div>
                  </div>

                  <div class='col-lg-3'>
                    <div class='form-group has-info'>
                      <label>Proveedor</label>

                      <select class="form-control select " id="id_proveedor" name="id_proveedor">
                        <option value="">Seleccione</option>
                        <?php
                        $sql_proveedor=_query("SELECT proveedor.id_proveedor, proveedor.nombre,proveedor.percibe FROM proveedor ORDER BY nombre");
                        while ($row=_fetch_array($sql_proveedor)) {
                            # code...?>
                          <option <?php if ($row['id_proveedor']==$id_proveedor) {
                                echo " selected ";
                                $percibe=$row['percibe'];
                                if ($percibe==1) {
                                    $percepcion=round(1/100, 2);
                                } else {
                                    $percepcion=0;
                                }
                            } ?>  value="<?php echo $row['id_proveedor'] ?>"><?php echo $row['nombre'] ?></option>
                          <?php
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class='col-lg-3'>
                    <div class='form-group has-info'>
                      <label>Tipo  Documento</label>
                     
                                <?php
                                $nombre_select0="tipo_doc";
                            $idd0=-1;
                            //$style='width:0px';
                            $select0=crear_select($nombre_select0, $array3, $idd0, "");
                            echo $select0; ?>
                     
                      
                    </div>
                  </div>
                  <div class='col-lg-3'>
                    <div class='form-group has-info'>
                      <label>Numero de Documento</label>
                      <input type="text" class="form-control" id="numero_doc" name="numero_doc" value="<?=$numero_doc ?>">
                    </div>
                  </div>
                  <div   class="col-lg-1">
                    <label>Dias Credito</label>

                    <input class="form-control" id="numero_dias" name="numero_dias" value='<?=$dias_credito ?>'>
                  </div>
                  <div class='col-lg-2'>
                    <div class='form-group has-info'>
                      <label>Fecha</label>
                      <input type='text' class='datepick form-control' value='<?php if ($fecha!="") {
                                echo $fecha;
                            } else {
                                echo $fecha_actual;
                            } ?>' id='fecha1' name='fecha1'>
                    </div>
                  </div>
                </div>
                <div class="row focuss" id="buscador">
                  <div class="form-group col-md-4">
                    <div id="a">
                      <label>Buscar Producto (Código)</label>
                      <input type="text" id="codigo" name="codigo" style="width:100% !important" class="form-control usage" placeholder="Ingrese Código de producto" style="border-radius:0px">
                    </div>
                    <div hidden id="b">
                      <label id='buscar_habilitado'>Buscar Producto (Descripción)</label>
                      <div id="scrollable-dropdown-menu">
                        <input type="text" id="producto_buscar" name="producto_buscar" style="width:100% !important" class=" form-control usage typeahead" placeholder="Ingrese la Descripción de producto" data-provide="typeahead"
                        style="border-radius:0px">
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group has-info">
                      <label>Destino</label>
                      <select class="form-control select" id="destino" name="destino">
                        <?php
                        $sql = _query("SELECT * FROM ubicacion WHERE id_sucursal='$id_sucursal' ORDER BY descripcion ASC");
                        while ($row = _fetch_array($sql)) {
                            $selected="";
                            if ($id_ubicacion==$row['id_ubicacion']) {
                                $selected=" selected ";
                            }
                            echo "<option $selected  value='".$row["id_ubicacion"]."'>".$row["descripcion"]."</option>";
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-5">
                    <br>
                    <a class="btn btn-danger pull-right" style="margin-left:3%;" href="dashboard.php" id='salir'><i class="fa fa-mail-reply"></i> F4 Salir</a>
                    <button type="button" id="submit1" style='margin-left:3%;' name="submit1" class="btn btn-primary pull-right usage"><i class="fa fa-check"></i> F2 Finalizar y dar ingreso</button>
                    <button type="button" id="submit2" name="submit2" class="btn btn-info pull-right usage"><i class="fa fa-save"></i> Solo guardar</button> 
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <header>
                      <h4 class="text-navy">Lista de Productos </h4>
                    </header>
                    <input type='hidden' name='porc_iva' id='porc_iva' value='<?php echo $iva; ?>'>
                    <input type='hidden' name='monto_percepcion' id='monto_percepcion' value='<?php echo $monto_percepcion; ?>'>
                    <input type="hidden" id="percepcion" name="percepcion" value="<?=$percepcion?>">
                    <div class='widget-content' id="content">
                      <div class="wrap-table1001">
                        <div class="table100 ver1 m-b-10">
                          <div class="table100-head">
                            <table id="inventable1">
                              <thead>
                                <tr class="row100 head">
                                  <th class="success col-lg-1">Id</th>
                                  <th class='success col-lg-3'>Nombre</th>
                                  <th class='success col-lg-1'>Presentación</th>
                                  <th class='success col-lg-1'>Descripción</th>
                                  <th class='success col-lg-1'>Cantidad</th>
                                  <th class='success col-lg-1'>Costo</th>
                                  <th class='success col-lg-1'>Precio</th>
                                  <th class='success col-lg-1'>Subtotal</th>
                                  <th class='success col-lg-1'>Vence</th>
                                  <th class='success col-lg-1'>Acci&oacute;n</th>
                                </tr>
                              </thead>
                            </table>
                          </div>
                          <div class="table100-body js-pscroll">
                            <table>
                              <tbody id="inventable">
                                <?php
                                $sql_cd= _query("SELECT detalle_compra2.subtotal, detalle_compra2.cantidad,presentacion_producto.precio,
                                   presentacion_producto.descripcion as descrip,presentacion_producto.unidad,presentacion_producto.id_pp,
                                   producto.perecedero,producto.descripcion,producto.id_producto,producto.exento,producto.id_categoria,
                                   detalle_compra2.fecha_vence,detalle_compra2.ultcosto,detalle_compra2.id_presentacion
                                   FROM detalle_compra2 JOIN producto ON producto.id_producto=detalle_compra2.id_producto
                                   JOIN presentacion_producto ON presentacion_producto.id_pp=detalle_compra2.id_presentacion WHERE detalle_compra2.id_compra=$id_compra");
                                $tr_add = "";
                                if (_num_rows($sql_cd)>0) {
                                    $i=0;
                                    while ($row = _fetch_array($sql_cd)) {
                                        // code...
                                        $perecedero = $row['perecedero'];
                                        $fv=$row['fecha_vence'];
                                        $id_producto= $row['id_producto'];
                                        $descrip = $row['descripcion'];
                                        $descripcionp = $row['descrip'];
                                        $categoria = $row['id_categoria'];
                                        $es_combustible = es_combustible($categoria);
                                        $preciop = $row['precio'];
                                        $cp=$row['ultcosto'];
                                        $id_presentacion= $row['id_pp'];

                                        $cantidad = round($row['cantidad']/$row['unidad'], 4);

                                        if ($fv=="0000-00-00") {
                                            $fv="";
                                        }
                                        if ($perecedero == 1) {
                                            $caduca = "<div class='form-group'><input type='text' class='datepicker form-control vence' value='$fv'></div>";
                                        } else {
                                            $caduca = "<input type='hidden' class='vence' value='NULL'>";
                                        }
                                        $exento = "<input type='hidden' class='exento' value='$row[exento]'>";

                                        $unit = "<input type='hidden' class='unidad' value='$row[unidad]'>";
                                        $combustible="<input type='hidden' id='combustible' name ='combustible' value='".$es_combustible ."'>";
                                        $sql_p=_query("SELECT presentacion.nombre, prp.descripcion,
                                      prp.id_pp as id_presentacion,prp.unidad,prp.costo,prp.precio,prp.barcode
                                      FROM presentacion_producto AS prp
                                      JOIN presentacion ON presentacion.id_presentacion=prp.id_presentacion
                                      WHERE prp.id_producto='$id_producto'
                                      AND prp.activo=1
                                       ORDER BY prp.unidad DESC");
                                        $select="<select class='sel form-control'>";
                                        while ($row2=_fetch_array($sql_p)) {
                                            $selected="";
                                            if ($id_presentacion==$row2['id_presentacion']) {
                                                $selected = " selected ";
                                            }
                                            $select.="<option $selected value='".$row2["id_presentacion"]."'>".$row2["nombre"]." (".$row2["unidad"].")</option>";
                                        }
                                        $select.="</select>";

                                        $tr_add .= '<tr id="'.$i.'" class="row100">';
                                        $tr_add .= '<td class="id_p col-lg-1">' . $id_producto . '</td>';
                                        $tr_add .= '<td class="col-lg-3">' . $descrip . $exento  . '</td>';
                                        $tr_add .= '<td class="col-lg-1">' . $select . '</td>';
                                        $tr_add .= '<td class="descp col-lg-1">' . $descripcionp.$combustible . '</td>';
                                        $tr_add .= "<td class='col-lg-1'><div><input type='text' value='$cantidad'  class='form-control cant ".$categoria." ' style='width:80px;'></div></td>";
                                        $tr_add .= "<td class='col-lg-1'><div>" . $unit . "<input type='text'  class='form-control precio_compra' value='" .$cp . "' style='width:80px;'></div></td>";
                                        $tr_add .= "<td class='col-lg-1'><div><input type='text'  class='form-control precio_venta' value='" . $preciop . "' style='width:80px;'></div></td>";
                                        $tr_add .= "<td class='col-lg-1'><div><input type='text'  class='form-control subtotal1' value='$row[subtotal]' style='width:80px;'></div></td>";
                                        $tr_add .= "<td class='col-lg-1'>" . $caduca . '</td>';
                                        $tr_add .= "<td class='Delete text-center col-lg-1'><a><i class='fa fa-trash'></i></a></td>";
                                        $tr_add .= '</tr>';
                                    }
                                    echo $tr_add;
                                }
                                 ?>
                              </tbody>
                            </table>
                          </div>
                          <div class="table101-body">
                            <table>
                              <thead>
                                <tbody id="totals">
                                  <?php $id_imp  ="<input type='hidden' id='id_imp' name='id_imp' value='-1' />"?>
                                  <tr>
                                    <td class="cell100 column100 ">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td class='cell100 column50 text-bluegrey tr_bb' id='totaltexto'>&nbsp;</td>
                                    <td class='cell100 column15 leftt  text-bluegrey  tr_bb'>CANT. PROD:</td>
                                    <td class='cell100 column10 text-right text-danger  tr_bb' id='totcant'>0.00</td>
                                    <td class="cell100 column15 leftt text-bluegrey">SUMAS (SIN IVA) $:</td>
                                    <td class="cell100 column10 text-right text-green" id='sumas_sin_iva'>0.00</td>
                                  </tr>
                                  <tr>
                                    <td class="cell100 column75">&nbsp;</td>
                                    <td class="cell100 column15  leftt  text-bluegrey ">IVA $:</td>
                                    <td class="cell100 column10 text-right text-green " id='iva'>0.00</td>
                                  </tr>
                                  <tr>
                                    <td class="cell100 column75">&nbsp;</td>
                                    <td class="cell100 column15  leftt text-bluegrey ">SUBTOTAL $:</td>
                                    <td class="cell100 column10 text-right  text-green" id='subtotal'>0.00</td>
                                  </tr>
                                  <tr>
                                    <td class="cell100 column75">&nbsp;</td>
                                    <td class="cell100 column15 leftt  text-bluegrey ">VENTA EXENTA $:</td>
                                    <td class="cell100 column10  text-right text-green" id='venta_exenta'>0.00</td>
                                  </tr>
                                  <tr>
                                    <td class="cell100 column75">&nbsp;</td>
                                    <td class="cell100 column15  leftt  text-bluegrey ">PERCEPCIÓN $:</td>
                                    <td class="cell100 column10 text-right text-green" id='total_percepcion'>0.00</td>
                                  </tr>
                                </tr>
                                  <?php $imp_gas=getImpuestoGas();
                      $count = _num_rows($imp_gas);
                      if ($imp_gas != null) {
                          for ($s = 0; $s < $count; $s++) {
                              $row = _fetch_array($imp_gas);
                              $id         = $row["id"];
                              $nombre     = $row["nombre"];
                              $valor      = $row["valor"];
                              $activo     = $row["activo"];
                              $dif        = $row["dif"];
                              $id_imp     ="<input type='hidden' id='id_imp' name='id_imp' value='$id' />";
                              $aplica_dif ="<input type='hidden' id='aplica_dif' name='aplica_dif' value='$dif' />";
                              $tot_imp_gas="<input type='hidden' id='tot_imp_gas' name='tot_imp_gas' value='0' />";
                              $val_imp_gas="<input type='hidden' id='val_imp_gas' name='val_imp_gas' value='0' />";
                              $iname     ="<input type='hidden' id='imp_nombre' name='imp_nombre'' value='$nombre' />";
                              echo "<tr id='$s'>
                                    <td class='cell100 column75'>&nbsp;</td>
                                    <td class='cell100 column15 desc_imp text-bluegrey'  id='descrip_impuesto'>";
                              echo  $id_imp.$iname.$aplica_dif.$val_imp_gas." IMPUESTO POR GALON: $nombre ($valor )</td>
                                    <td class='cell100 column10 val_imp text-right text-green'  id='total_impgas'>0.0 </td>
                                    </tr>";
                          }
                          echo "<tr hidden >
                                  <td class='cell100 column70 desc_imp text-bluegrey'  id='descrip_tot_imp'>
                                  <input type='hidden' id='id_imp' name='id_imp' value='-1' />TOTALES IMPUESTOS COMBUSTIBLES"."</td>
                                  <td class='cell100 column30 val_imp text-right text-green'  id='tot_imp_gass'>0.0 </td>
                                  </tr>";
                      }
                                $id_imp     ="<input type='hidden' id='id_imp' name='id_imp' value='-1' />"; ?>

                                  <tr>
                                  <tr>
                                    <td class="cell100 column75">&nbsp;</td>
                                    <td class="cell100 column15 leftt text-bluegrey ">TOTAL $:</td>
                                    <td class="cell100 column10 text-right  text-green" id='total_dinero'>0.00</td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <input type="hidden" id='total_impuestos_gas' name='total_impuestos_gas' value="" readOnly>
                          <input type="hidden" name="filas" id="filas" value="0">
                          <input type="hidden" name="process" id="process" value="insert"><br>
                          <input type="hidden" name="id_compra_g" id="id_compra_g" value="<?=$id_compra ?>">
                          <input type='hidden' name='urlprocess' id='urlprocess' value="<?php echo $filename ?> ">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--div class='ibox-content'-->
              </div>
            </div>

            <?php
            include_once("footer.php");
            $a=rand(1, 99999);
            echo "<script src='js/funciones/funciones_compras.js?t$a=$a'></script>";

            echo "<script src='js/plugins/arrowtable/arrow-table.js'></script>";
            echo "<script src='js/plugins/bootstrap-checkbox/bootstrap-checkbox.js'></script>";
            echo '<script src="js/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
            <script src="js/funciones/main.js"></script>';

          } //permiso del script
    else {
        echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div></div></div></div></div>";
        include_once("footer.php");
    }
}

        function save()
        {
            $id_compra = $_REQUEST['id_compra'];
            $cuantos = $_POST['cuantos'];
            $datos = $_POST['datos'];
            $destino = $_POST['destino'];
            $fecha = $_POST['fecha'];
            $total_compras = $_POST['total'];
            $concepto=$_POST['concepto'];
            $hora=date("H:i:s");
            $fecha_movimiento = date("Y-m-d");
            $id_empleado=$_SESSION["id_usuario"];
            //$arr_abono_creditos=json_encode($arr2);
            $json_imp_arr       = null;

            $id_proveedor=$_POST["proveedor"];
            $alias_tipodoc=$_POST['tipo_doc'];
            $numero_documen=$_POST['numero_doc'];

            $sumas_sin_iva=$_POST['sumas_sin_iva'];
            $subtotal=$_POST['subtotal'];
            $iva=$_POST['iva'];
            $venta_exenta=$_POST['venta_exenta'];
            $total_percepcion=$_POST['total_percepcion'];
            $dias_credito=$_POST['dias_credito'];

            $id_sucursal = $_SESSION["id_sucursal"];
            $sql_num = _query("SELECT ii FROM correlativo WHERE id_sucursal='$id_sucursal'");
            $datos_num = _fetch_array($sql_num);
            $ult = $datos_num["ii"]+1;
            $numero_doc=str_pad($ult, 7, "0", STR_PAD_LEFT).'_II';

            _begin();
            $z=1;

            /*actualizar los correlativos de II*/
            $corr=1;

            if ($concepto=='') {
                $concepto='COMPRA DE PRODUCTO';
            }
            $a = 1 ;

            if ($id_compra!="0") {
                $id_fact = $id_compra;

                /*insertar la compra*/
                $table_fc= 'compra2';
                $form_data_fc = array(
              'id_proveedor' => $id_proveedor,
              'alias_tipodoc'=>$alias_tipodoc,
              'fecha' => $fecha,
              'fecha_ingreso' => $fecha_movimiento,
              'numero_doc' => $numero_documen,
              'total' => $total_compras,
              'total_percepcion'=>$total_percepcion,
              'id_empleado' => $id_empleado,
              'id_sucursal' => $id_sucursal,
              'iva' => $iva,
              'hora' => $hora,
              'dias_credito' => $dias_credito,
              'finalizada' =>1,
              'imp_comb' =>$json_imp_arr,
            );
                //falta en compras vencimiento a 30, 60, 90 dias y vence iva
                $insertar_fc = _update($table_fc, $form_data_fc, "id_compra=$id_fact");
                if ($insertar_fc) {
                    # code...
                } else {
                    # code...
                    $a=0;
                }
            } else {
                /*insertar la compra*/
                $table_fc= 'compra2';
                $form_data_fc = array(
              'id_proveedor' => $id_proveedor,
              'alias_tipodoc'=>$alias_tipodoc,
              'fecha' => $fecha,
              'fecha_ingreso' => $fecha_movimiento,
              'numero_doc' => $numero_documen,
              'total' => $total_compras,
              'total_percepcion'=>$total_percepcion,
              'id_empleado' => $id_empleado,
              'id_sucursal' => $id_sucursal,
              'iva' => $iva,
              'hora' => $hora,
              'dias_credito' => $dias_credito,
              'finalizada' =>1,
              'imp_comb' =>$json_imp_arr,
            );
                //falta en compras vencimiento a 30, 60, 90 dias y vence iva
                $insertar_fc = _insert($table_fc, $form_data_fc);
                if ($insertar_fc) {
                    # code...
                } else {
                    # code...
                    $a=0;
                }
                $id_fact= _insert_id();
            }

            $j = 1 ;
            $k = 1 ;
            $l = 1 ;
            $d = 1 ;
            $m = 1 ;
            $lll = 1 ;

            _delete("detalle_compra2", "id_compra=$id_fact");
            $array_json=$_POST['json_arr'];
            $array = json_decode($array_json, true);
            foreach ($array as $fila) {
                $id_producto=$fila['id_producto'];
                $precio_compra=$fila['compra'];
                $precio_venta=$fila['venta'];
                $cantidad=$fila['cant'];
                $unidades=$fila['unidad'];
                $fecha_caduca=$fila['vence'];
                $id_presentacion=$fila['id_presentacion'];
                $exento=$fila['exento'];

                /*cantidad de una presentacion por la unidades que tiene*/
                $cantidad=$cantidad*$unidades;


                //detalle de compras
                $table_dc= 'detalle_compra2';
                $form_data_dc = array(
              'id_compra' => $id_fact,
              'id_producto' => $id_producto,
              'cantidad' => $cantidad,
              'ultcosto' => $precio_compra,
              'subtotal' => round(($cantidad/$unidades)*$precio_compra, 2),
              'exento' => $exento,
              'id_presentacion' => $id_presentacion,
              'fecha_vence' => $fecha_caduca,
            );
                $insertar_dc = _insert($table_dc, $form_data_dc);
                if (!$insertar_dc) {
                    $b=0;
                }
            }
            if ($insertar_fc && $insertar_dc &&$corr &&$z && $j && $k && $l && $m && $d && $lll) {
                _commit();
                $xdatos['typeinfo']='Success';
                $xdatos['msg']='Registro ingresado con exito!';
                $xdatos['id_compra']= $id_fact;
            } else {
                _rollback();
                $xdatos['typeinfo']='Error';
                $xdatos['msg']='Registro de no pudo ser ingresado!';
                $xdatos['id_compra']= $id_fact;
            }
            echo json_encode($xdatos);
        }
        function insertar()
        {
            $cuantos = $_POST['cuantos'];
            $datos = $_POST['datos'];
            $destino = $_POST['destino'];
            $fecha = $_POST['fecha'];
            $total_compras = $_POST['total'];
            $concepto=$_POST['concepto'];
            $hora=date("H:i:s");
            $fecha_movimiento = date("Y-m-d");
            $id_empleado=$_SESSION["id_usuario"];

            $id_compra =$_REQUEST['id_compra'];
            _delete("compra2", "id_compra=$id_compra");
            _delete("detalle_compra2", "id_compra=$id_compra");

            $id_proveedor=$_POST["proveedor"];
            $alias_tipodoc=$_POST['tipo_doc'];
            $numero_documen=$_POST['numero_doc'];

            $sumas_sin_iva=$_POST['sumas_sin_iva'];
            $subtotal=$_POST['subtotal'];
            $iva=$_POST['iva'];
            $venta_exenta=$_POST['venta_exenta'];
            $total_percepcion=$_POST['total_percepcion'];
            $dias_credito=$_POST['dias_credito'];
            $json_imp_arr       = null;
            $id_sucursal = $_SESSION["id_sucursal"];
            $sql_num = _query("SELECT ii FROM correlativo WHERE id_sucursal='$id_sucursal'");
            $datos_num = _fetch_array($sql_num);
            $ult = $datos_num["ii"]+1;
            $numero_doc=str_pad($ult, 7, "0", STR_PAD_LEFT).'_II';

            _begin();
            $z=1;

            /*actualizar los correlativos de II*/
            $corr=1;
            $table="correlativo";
            $form_data = array(
            'ii' =>$ult
          );
            $where_clause_c="id_sucursal='".$id_sucursal."'";
            $up_corr=_update($table, $form_data, $where_clause_c);
            if ($up_corr) {
                # code...
            } else {
                $corr=0;
            }
            if ($concepto=='') {
                $concepto='COMPRA DE PRODUCTO';
            }
            $a = 1 ;

            /*insertar la compra*/
            $table_fc= 'compra';
            $form_data_fc = array(
            'id_proveedor' => $id_proveedor,
            'alias_tipodoc'=>$alias_tipodoc,
            'fecha' => $fecha,
            'fecha_ingreso' => $fecha_movimiento,
            'numero_doc' => $numero_documen,
            'total' => $total_compras,
            'total_percepcion'=>$total_percepcion,
            'id_empleado' => $id_empleado,
            'id_sucursal' => $id_sucursal,
            'iva' => $iva,
            'hora' => $hora,
            'dias_credito' => $dias_credito,
            'finalizada' =>1,
            'imp_comb' =>$json_imp_arr,
          );
            //falta en compras vencimiento a 30, 60, 90 dias y vence iva
            $insertar_fc = _insert($table_fc, $form_data_fc);
            if ($insertar_fc) {
                # code...
            } else {
                # code...
                $a=0;
            }
            $id_fact= _insert_id();


            /*cuentas por pagar*/
            if ($dias_credito!=0) {
                # code...
                $table_cxp= 'cuenta_pagar';
                $fecha_vencimiento=sumar_dias_Ymd($fecha, $dias_credito);
                $form_data_cxp = array(
              'id_proveedor' => $id_proveedor,
              'alias_tipodoc'=>$alias_tipodoc,
              'fecha' => $fecha_movimiento,
              'fecha_vence' => $fecha_vencimiento,
              'numero_doc' => $numero_documen,
              'monto' => $total_compras,
              'saldo_pend'=> $total_compras,
              'id_empleado' => $id_empleado,
              'id_sucursal' => $id_sucursal,
              'hora' => $hora,
              'dias_credito' => $dias_credito,
              'id_compra' => $id_fact,
            );
                $insertar_cxp = _insert($table_cxp, $form_data_cxp);
            }



            $table='movimiento_producto';
            $form_data = array(
            'id_sucursal' => $id_sucursal,
            'correlativo' => $numero_doc,
            'concepto' => $concepto,
            'total' => $total_compras,
            'tipo' => 'ENTRADA',
            'proceso' => 'II',
            'referencia' => $numero_doc,
            'id_empleado' => $id_empleado,
            'fecha' => $fecha,
            'hora' => $hora,
            'id_suc_origen' => $id_sucursal,
            'id_suc_destino' => $id_sucursal,
            'id_proveedor' => $id_proveedor,
            'id_compra' => $id_fact,
          );
            $insert_mov =_insert($table, $form_data);
            $id_movimiento=_insert_id();
            $lista=explode('#', $datos);
            $j = 1 ;
            $k = 1 ;
            $l = 1 ;
            $d = 1 ;
            $m = 1 ;
            $lll = 1 ;

            $array_json=$_POST['json_arr'];
            $array = json_decode($array_json, true);
            foreach ($array as $fila) {
                $id_producto=$fila['id_producto'];
                $precio_compra=$fila['compra'];
                $precio_venta=$fila['venta'];
                $cantidad=$fila['cant'];
                $unidades=$fila['unidad'];
                $fecha_caduca=$fila['vence'];
                $id_presentacion=$fila['id_presentacion'];
                $exento=$fila['exento'];

                $sql_su="SELECT id_su, cantidad FROM stock_ubicacion WHERE id_producto='$id_producto' AND id_sucursal='$id_sucursal' AND id_ubicacion='$destino' AND id_estante=0 AND id_posicion=0";
                $stock_su=_query($sql_su);
                $nrow_su=_num_rows($stock_su);
                $id_su="";
                /*cantidad de una presentacion por la unidades que tiene*/
                $cantidad=$cantidad*$unidades;
                if ($nrow_su >0) {
                    $row_su=_fetch_array($stock_su);
                    $cant_exis = $row_su["cantidad"];
                    $id_su = $row_su["id_su"];
                    $cant_new = $cant_exis + $cantidad;
                    $form_data_su = array(
                'cantidad' => $cant_new,
              );
                    $table_su = "stock_ubicacion";
                    $where_su = "id_su='".$id_su."'";
                    $insert_su = _update($table_su, $form_data_su, $where_su);
                } else {
                    $form_data_su = array(
                'id_producto' => $id_producto,
                'id_sucursal' => $id_sucursal,
                'cantidad' => $cantidad,
                'id_ubicacion' => $destino,
              );
                    $table_su = "stock_ubicacion";
                    $insert_su = _insert($table_su, $form_data_su);
                    $id_su=_insert_id();
                }
                if (!$insert_su) {
                    $m=0;
                }
                $sql2="SELECT stock FROM stock WHERE id_producto='$id_producto' AND id_sucursal='$id_sucursal'";
                $stock2=_query($sql2);
                $row2=_fetch_array($stock2);
                $nrow2=_num_rows($stock2);
                if ($nrow2>0) {
                    $existencias=$row2['stock'];
                } else {
                    $existencias=0;
                }
                $sql_lot = _query("SELECT MAX(numero) AS ultimo FROM lote WHERE id_producto='$id_producto'");
                $datos_lot = _fetch_array($sql_lot);
                $lote = $datos_lot["ultimo"]+1;
                $table1= 'movimiento_producto_detalle';
                $cant_total=$cantidad+$existencias;
                $form_data1 = array(
              'id_movimiento'=>$id_movimiento,
              'id_producto' => $id_producto,
              'cantidad' => $cantidad,
              'costo' => $precio_compra,
              'precio' => $precio_venta,
              'stock_anterior'=>$existencias,
              'stock_actual'=>$cant_total,
              'lote' => $lote,
              'id_presentacion' => $id_presentacion,
              'fecha' => $fecha_movimiento,
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
                'costo_unitario'=>$precio_compra,
                'precio_unitario'=>$precio_venta,
                'create_date'=>$fecha_movimiento,
                'update_date'=>$fecha_movimiento,
                'id_sucursal' => $id_sucursal
              );
                    $insert_stock = _insert($table2, $form_data2);
                } else {
                    $cant_total=$cantidad+$existencias;
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
                if ($fecha_caduca!="0000-00-00" && $fecha_caduca!="") {
                    $sql_caduca="SELECT * FROM lote WHERE id_producto='$id_producto' and fecha_entrada='$fecha_movimiento' and vencimiento='$fecha_caduca' ";
                    $result_caduca=_query($sql_caduca);
                    $row_caduca=_fetch_array($result_caduca);
                    $nrow_caduca=_num_rows($result_caduca);
                    /*if($nrow_caduca==0){*/
                    $table_perece= 'lote';

                    if ($fecha_movimiento>=$fecha_caduca) {
                        $estado='VIGENTE';
                    } else {
                        $estado='VIGENTE';
                    }
                    $form_data_perece = array(
                'id_producto' => $id_producto,
                'referencia' => $numero_doc,
                'numero' => $lote,
                'fecha_entrada' => $fecha_movimiento,
                'vencimiento'=>$fecha_caduca,
                'precio' => $precio_compra,
                'cantidad' => $cantidad,
                'estado'=>$estado,
                'id_sucursal' => $id_sucursal,
                'id_presentacion' => $id_presentacion,
              );
                    $insert_lote = _insert($table_perece, $form_data_perece);
                } else {
                    $sql_caduca="SELECT * FROM lote WHERE id_producto='$id_producto' AND fecha_entrada='$fecha_movimiento'";
                    $result_caduca=_query($sql_caduca);
                    $row_caduca=_fetch_array($result_caduca);
                    $nrow_caduca=_num_rows($result_caduca);
                    $table_perece= 'lote';
                    $estado='VIGENTE';

                    $form_data_perece = array(
                'id_producto' => $id_producto,
                'referencia' => $numero_doc,
                'numero' => $lote,
                'fecha_entrada' => $fecha_movimiento,
                'vencimiento'=>$fecha_caduca,
                'precio' => $precio_compra,
                'cantidad' => $cantidad,
                'estado'=>$estado,
                'id_sucursal' => $id_sucursal,
                'id_presentacion' => $id_presentacion,
              );
                    $insert_lote = _insert($table_perece, $form_data_perece);
                }
                if (!$insert_lote) {
                    $l = 0;
                }

                $table="movimiento_stock_ubicacion";
                $form_data = array(
              'id_producto' => $id_producto,
              'id_origen' => 0,
              'id_destino'=> $id_su,
              'cantidad' => $cantidad,
              'fecha' => $fecha_movimiento,
              'hora' => $hora,
              'anulada' => 0,
              'afecta' => 0,
              'id_sucursal' => $id_sucursal,
              'id_presentacion'=> $id_presentacion,
              'id_mov_prod' => $id_movimiento,
            );

                $insert_mss =_insert($table, $form_data);

                if ($insert_mss) {
                    # code...
                } else {
                    # code...
                    $z=0;
                }

                //detalle de compras
                $table_dc= 'detalle_compra';
                $form_data_dc = array(
              'id_compra' => $id_fact,
              'id_producto' => $id_producto,
              'cantidad' => $cantidad,
              'ultcosto' => $precio_compra,
              'subtotal' => round(($cantidad/$unidades)*$precio_compra, 2),
              'exento' => $exento,
              'id_presentacion' => $id_presentacion,
            );
                $insertar_dc = _insert($table_dc, $form_data_dc);
                if (!$insertar_dc) {
                    $b=0;
                }
                //aptualizar el precio en producto prsentacion
                $table_prese_pro="presentacion_producto";
                $form_data_p_p = array(
              'precio'=>$precio_venta,
              'costo'=>$precio_compra,
            );
                $where_clause_p_p="WHERE id_producto='$id_producto' AND id_pp='$id_presentacion'";
                $update_p_p = _update($table_prese_pro, $form_data_p_p, $where_clause_p_p);
                if (!$update_p_p) {
                    $d=0;
                }

                $costo_u = $precio_compra / $unidades;
                $sql_presen_sel2 = _query("SELECT * FROM presentacion_producto where id_producto = $id_producto ");
                while ($row3 = _fetch_array($sql_presen_sel2)) {
                    _update_s("presentacion_producto", array('costo' => ($costo_u * $row3['unidad'])), "id_pp = $row3[id_pp]");
                }

                /*actualizando el stock del local de venta*/
                $num=_query("SELECT ubicacion.id_ubicacion FROM ubicacion WHERE id_sucursal=$id_sucursal AND bodega=0");

                if (_num_rows($num)>0) {
                    // code...
                    $sql1a=_fetch_array(_query("SELECT ubicacion.id_ubicacion FROM ubicacion WHERE id_sucursal=$id_sucursal AND bodega=0"));
                    $id_ubicaciona=$sql1a['id_ubicacion'];
                    $sql2a=_fetch_array(_query("SELECT SUM(stock_ubicacion.cantidad) as stock FROM stock_ubicacion WHERE id_producto=$id_producto AND stock_ubicacion.id_ubicacion=$id_ubicaciona"));
                    $table='stock';
                    $form_data = array(
                'stock_local' => $sql2a['stock'],
              );
                    $where_clause="id_producto='".$id_producto."' AND id_sucursal=$id_sucursal";
                    $updatea=_update($table, $form_data, $where_clause);
                    /*finalizando we*/
                }
            }
            if ($insert_mov &&$insertar_fc && $insertar_dc &&$corr &&$z && $j && $k && $l && $m && $d && $lll) {
                _commit();
                $xdatos['typeinfo']='Success';
                $xdatos['msg']='Registro ingresado con exito!';
            } else {
                _rollback();
                $xdatos['typeinfo']='Error';
                $xdatos['msg']='Registro de no pudo ser ingresado!';
            }
            echo json_encode($xdatos);
        }
        function consultar_stock()
        {
            $id_producto = $_REQUEST['id_producto'];
            $tipo = $_REQUEST['tipo'];
            $id_sucursal=$_SESSION['id_sucursal'];
            $id_usuario=$_SESSION['id_usuario'];
            $id_presentacione=0;
            $r_precios=_fetch_array(_query("SELECT precios FROM usuario WHERE id_usuario=$id_usuario"));
            $precios=$r_precios['precios'];
            $limit="LIMIT ".$precios;
            if ($tipo == "D") {
                $clause = "p.id_producto = '$id_producto'";
            } else {
                $sql_aux= _query("SELECT id_producto FROM producto WHERE barcode='$id_producto'");
                echo _error();
                if (_num_rows($sql_aux)>0) {
                    $dats_aux = _fetch_array($sql_aux);
                    $id_producto = $dats_aux["id_producto"];
                    $clause = "p.id_producto = '$id_producto'";
                } else {
                    $id_producto = intval($id_producto);
                    $sql_aux = _query("SELECT id_pp as id_presentacion, id_producto
                FROM presentacion_producto WHERE id_pp='$id_producto'
                AND activo='1' OR  barcode='$id_producto'
                ");
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
            $sql1 = "SELECT p.id_producto, p.descripcion,p.exento,p.id_categoria
          FROM producto AS p
          WHERE $clause";

            $stock1=_query($sql1);
            if (_num_rows($stock1)>0) {
                $row1=_fetch_array($stock1);
                $descipcion = $row1["descripcion"];
                $id_producto = $row1["id_producto"];
                $exento = $row1["exento"];
                $categoria   = $row1['id_categoria'];
                $i=0;
                $unidadp=0;
                $preciop=0;
                $costop=0;
                $descripcionp=0;
                $pbarcode="";
                $anda = "";
                $es_combustible=0;

                if ($id_presentacione > 0) {
                    $anda = " AND prp.id_pp = '$id_presentacione'";
                }
                $sql_p=_query("SELECT presentacion.nombre, prp.descripcion,
              prp.id_pp as id_presentacion,prp.unidad,prp.costo,prp.precio,prp.barcode
              FROM presentacion_producto AS prp
              JOIN presentacion ON presentacion.id_presentacion=prp.id_presentacion
              WHERE prp.id_producto='$id_producto'
              AND prp.activo=1
              $anda ORDER BY prp.unidad DESC");
                $select="<select class='sel form-control'>";
                while ($row=_fetch_array($sql_p)) {
                    if ($i==0) {
                        $unidadp=$row['unidad'];
                        $costop=$row['costo'];
                        $preciop=$row['precio'];
                        $descripcionp=$row['descripcion'];
                        $pbarcode=$row['barcode'];
                        $xc=0;
                    }
                    $select.="<option value='".$row["id_presentacion"]."'>".$row["nombre"]." (".$row["unidad"].")</option>";
                    $i=$i+1;
                }

                $select.="</select>";
                $xdatos['select']= $select;
                $xdatos['descrip']= $descipcion;
                $xdatos['id_p']= $id_producto;
                $xdatos['costop']= $costop;
                $xdatos['preciop']= $preciop;
                $xdatos['unidadp']= $unidadp;
                $xdatos['descripcionp']= $descripcionp;
                $xdatos['pbarcode']= $pbarcode;
                $xdatos['exento'] = $exento;
                $xdatos['es_combustible'] = $es_combustible;
                $xdatos['i']=$i;

                $sql_perece="SELECT * FROM producto WHERE id_producto='$id_producto'";
                $result_perece=_query($sql_perece);
                $row_perece=_fetch_array($result_perece);
                $perecedero=$row_perece['perecedero'];
                $xdatos['perecedero'] = $perecedero;
                $xdatos['categoria']=$row_perece['id_categoria'];
                $xdatos['decimals']=$row_perece['decimals'];
                $xdatos['typeinfo']="Success";
            } else {
                $xdatos['typeinfo']="Error";
                $xdatos['msg']="El codigo ingresado no pertenece a ningun producto";
            }

            echo json_encode($xdatos);
        }
        function getpresentacion()
        {
            echo json_encode(getPre());
        }

        function datos_proveedores()
        {
            $id_proveedor = $_POST['id_proveedor'];
            $sql0="SELECT percibe  FROM proveedor  WHERE id_proveedor='$id_proveedor'";
            $result = _query($sql0);
            $numrows= _num_rows($result);
            $row = _fetch_array($result);

            $percibe=$row['percibe'];
            if ($percibe==1) {
                $percepcion=round(1/100, 2);
            } else {
                $percepcion=0;
            }
            $xdatos['percepcion'] = $percepcion;
            echo json_encode($xdatos); //Return the JSON Array
        }
        function es_combustible($id_categoria)
        {
            $es_combustible=0;
            $sql_comb="SELECT * FROM categoria WHERE  id_categoria='$id_categoria' and combustible=1 ";
            $res_comb =_query($sql_comb);
            if (_num_rows($res_comb)>0) {
                $es_combustible=1;
            }
            return $es_combustible;
        }
        if (!isset($_REQUEST['process'])) {
            initial();
        }
        if (isset($_REQUEST['process'])) {
            switch ($_REQUEST['process']) {
            case 'insert':
            insertar();
            break;
            case 'save':
            save();
            break;
            case 'consultar_stock':
            consultar_stock();
            break;
            case 'getpresentacion':
            getpresentacion();
            break;
            case 'datos_proveedores':
            datos_proveedores();
            break;
          }
        }
        ?>
