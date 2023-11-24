<?php
include("_core.php");
include('num2letras.php');
include('facturacion_funcion_imprimir.php');
// Page setup
function initial()
{
    $_PAGE = array();
    $title='Administrar Cuotas de Créditos por Cliente';
    include_once "_headers.php";
    $_PAGE ['title'] = $title;

    $_PAGE ['links'] .= '<link href="css/plugins/switchery/switch.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/autocompleteBS/autocompleteBS.css" rel="stylesheet">';
    include_once "header.php";
    include_once "main_menu.php";
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    //permiso del script
    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];
    $uri = $_SERVER['SCRIPT_NAME'];
    $filename=get_name_script($uri);
    $links=permission_usr($id_user, $filename);
    $fechahoy=date("Y-m-d");
    $fechaanterior=restar_dias($fechahoy, 30);
    $id_sucursal=$_SESSION['id_sucursal'];
    $fecha = date('Y-m-d');
    $fecha_actual=date("Y-m-d");
    $sql_apertura = _query("SELECT * FROM apertura_caja WHERE vigente = 1
  AND id_sucursal = '$id_sucursal' AND fecha='$fecha' AND id_empleado = '$id_user'");
    $cuenta = _num_rows($sql_apertura);
    $id_apertura = 0;
    $turno_vigente = 0;
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
        $msj_apertura="APERTURA ACTUAL ";
    } ?>
  <style media="screen">
  span.select2-container {
    z-index:10050;
  }
  </style>
  <div class="wrapper wrapper-content  animated fadeInRight">
  <div class="row" id="row1">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <?php if ($links!='NOT' || $admin=='1') {
        if ($turno_vigente=='1') {
            echo"
            <div class='ibox-title'>
            <h5>$title</h5>
            </div>"; ?>
            <div class="ibox-content">
              <!--load datables estructure html-->
              <div class="row">
                <!--div class="input-group"-->
                  <div class="col-md-6">
                      <label>Buscar Cliente</label>
                    <input type="text" placeholder="Buscar" class="form-control" id="txtBuscarCte" name="txtBuscarCte" value="">
                      <input type="hidden" name="id_cliente" id="id_cliente"  />
                  </div>
                  <div class="col-md-2">
                      <label>Monto Abonar $</label>
                    <input type="text" placeholder="Monto" class="form-control" id="monto" name="monto" value='' readonly>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Fecha </label>
                      <input type="text" placeholder="Fecha Inicio" class="datepick form-control" id="fecha" name="fecha" value="<?php echo  $fecha; ?>">
                    </div>
                  </div>
                  <div class="col-md-2">
                    <br>
                    <div class="btn-group">

                      <button type="button" id="btnSave" name="btnSave"
                      class="btn btn-md btn-primary pull-right" style='margin-left:2px;'>
                      <i class="fa-solid fa-save"></i> Guardar</button>&nbsp;
                      </div>
                  </div>
                    <div class="row">
                      <input type hidden id='cliente_seleccionado' name='cliente_seleccionado' value='' />
                      <div class="col-md-12 client_select">
                      </div>
                  </div>
              </div>
              <hr>
                <div class="row">
              <section>
                <div class="table">
                <table class="table table-striped table-bordered table-responsive" id="cred">
                  <thead style='color:#263238'>
                    <tr>
                      <th class="col-md-1">Id</th>
                      <th class="col-md-2">No. cuota</th>
                      <th class="col-md-1">Fecha vencimiento</th>
                      <th class="col-md-1">Valor Cuota $</th>
                      <th class="col-md-1">Estado</th>
                      <th class="col-md-1">Monto Abonar $</th>
                      <th class="col-md-1">saldo Pendiente $</th>
                      <!--th class="col-md-1">Acción</th-->
                    </tr>
                  </thead>
                    <tbody class='table' id="detalle_creditos">
                </table>
                </div>
                <!--input type="hidden" name="autosave" id="autosave" value="false-0"-->
                <input type='hidden' name='id_apertura' id='id_apertura' value='<?php echo $id_apertura; ?>'>
                <input type="hidden" name="total_abonar" id="total_abonar" value="0">
                <input type="hidden" name="total_saldo" id="total_saldo" value="0">
                <input type="hidden" name="id_abono_historial" id="id_abono_historial" value="-1" />
              </section>

              <!--Show Modal Popups View & Delete -->
              <div class='modal fade' id='viewModal' tabindex='-1' data-backdrop="static" data-keyboard="false" role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
                <div class='modal-dialog modal-md'>
                  <div class='modal-content modal-md'></div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>
              <!-- /.modal -->
              <div class='modal fade' id='deleteModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
                <div class='modal-dialog modal-md'>
                  <div class='modal-content modal-md'></div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>
              <!-- /.modal -->
              <!--Show Modal Popups View & Delete -->
              <div class='modal fade' id='viewModalFact' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
                <div class='modal-dialog modal-md'>
                  <div class='modal-content modal-md'></div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>
              <!-- /.modal -->
                  <!--Show Modal Popups  -->
                <div class='modal fade' id='modalPrint' tabindex='-1' role='dialog' aria-labelledby='myModalPrint' aria-hidden='true'>
                  <div class='modal-dialog modal-sm'>
                    <div class='modal-content modal-sm'>
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Imprimir </h4>
                    </div>
                    <div class="modal-body">
                      <div class="row"> 
                        <div class="col-md-12">
                          <center><h5>¿Desea imprimir el ticket?</h5></center>
                        </div>
                        <div class="col-md-12">
                            <button type="button"  class="btn btn-danger pull-right" style='margin-left:2px;' id="btnSalir2" >
                            <i class="fa fa-stop"></i> &nbsp;&nbsp; Salir &nbsp;&nbsp</button>
                            <button type="button" id="btnPrintAbono" name="btnPrintAbono"
                            class="btn btn-md btn-primary pull-right" style='margin-left:2px;'>
                            <i class="fa-solid fa-print"></i> Imprimir</button>&nbsp;
                            <input type="hidden" name="id_abono_print" id="id_abono_print" value="-1" />
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>
              <!--Show Modal Popups View & Delete -->
              <?php  modalCredito(); ?>
              <!-- /.modal -->
              </div>
            </div>
            <!--div class='ibox-content'-->
          </div>
          <!--<div class='ibox float-e-margins' -->
        </div>
        <!--div class='col-lg-12'-->
      </div>
      <!--div class='row'-->
    </div>
    <!--div class='wrapper wrapper-content  animated fadeInRight'-->
    <?php
        } else {
            echo "<div class='ibox-content'><div class='row'><div class='col-lg-12'>";
            echo "<div class='alert alert-warning'><h3 class='text-danger'>
        No Hay Apertura de Caja vigente para este turno!!! aperture <a href='admin_corte.php'>aquí</a>  </h3>
        </div></div></div></div>
        </div></div></div></div></div>
        ";
        }  //apertura de caja
    } //permiso del script
    else {
        $mensaje = mensaje_permiso();
        echo "<br><br>$mensaje</div></div></div></div>";
        include "footer.php";
    }

    include("footer.php");
    echo "<script src='js/plugins/sweetalert/sweetalert2.all.min.js'></script>";
    echo "<script src='js/plugins/cellNavigate.js'></script>";
    echo '<script src="js/plugins/switchery/switchery.js"></script>';
    echo '<script src="js/plugins/autocompleteBS/autocompleteBS.js"></script>';
    echo" <script type='text/javascript' src='js/funciones/admin_abono_cuota.js'></script>";
}

function getCreditos($id_cliente)
{
    $q="SELECT vc.*,c.nombre FROM venta_cuota AS vc
  LEFT JOIN cliente AS c ON c.id_cliente=vc.id_cliente
  WHERE vc.id_cliente='$id_cliente'
  AND vc.saldo>0
  AND vc.finalizada=0
  ORDER BY vc.fecha_inicio";
    $res=_query($q);
    return $res;
}
function getCuotas($id_cliente)
{
    $q="SELECT * FROM venta_cuota as vc
  JOIN cuota AS c on c.id_venta_cuota=vc.id_venta_cuota
  WHERE vc.id_cliente='$id_cliente' AND vc.saldo>0";
    $res=_query($q);
    return $res;
}
function getVentasCuota($id_cliente)
{
    $q="SELECT * FROM venta_cuota as vc
  WHERE vc.id_cliente='$id_cliente' AND vc.saldo>0";
    $res=_query($q);
    return $res;
}
function credito_cliente()
{
    $id_cliente = $_POST['id_cliente'];
    /*
    $r0= getVentasCuota($id_cliente);
    $count0= _num_rows($r0);
    for ($a=0;$a<$count0;$a++) {
      $row0=_fetch_array($r0);
      $id_venta_cuota=  $row0['id_venta_cuota'];
      
    } */
    $r= getCuotas($id_cliente);
    $count= _num_rows($r);
    $total_creditos = 0;
    $total_abonado = 0;
    $total_saldo = 0;
    $saldo_pendiente =0;
    $el_add="";
    if ($count>0) {
        
        for ($j=0;$j<$count;$j++) {
            $row=_fetch_array($r);
            $id_credito=  $row['id_venta_cuota'];
           
            $id_cuota =  $row['id_cuota'];
            $cuota=round($row['valorcuota'], 2);
            $abono = round($row['abono'], 2);
            $saldo = round($row['saldo'], 2);
            $ncuota =$row['cuotanumero'];
            $detalleVenta="";
            if($ncuota==0){
              $detalleVenta=" DE ARTICULOS: <BR>".getDetVenta($id_credito);
            }
            $pagada =$row['pagada'];
            $abonocuota =round($row['abonocuota'], 2);
            $saldocuota = round($row['saldocuota'], 2);
            $numcuota = $ncuota ==0 ? " PRIMA " : $ncuota;
            $estado = $pagada ==1 ? " CANCELADA ".$row['fecha_pago'] : "NO PAGADA";
            if ($pagada ==0) {
                if (!is_null($row['fecha_ult_abono']) && $row['fecha_ult_abono']!="0000-00-00") {
                    $estado = " ULT. ABONO ".$row['fecha_ult_abono'] ;
                }
            }


            $total =sprintf("%.2f", $cuota);
            $abono =sprintf("%.2f", $abono);
            $saldo =sprintf("%.2f", $saldo);
            $abonocuota =sprintf("%.2f", $abonocuota);
            $saldocuota =sprintf("%.2f", $saldocuota);
            $input_id_cuota="<input type='hidden'  class='form-control decimal id_quote'
          id='id_cuota' name='id_cuota' value='$id_cuota'>";
            $hidden_saldo="<input type='hidden'  class='decimal saldoxcuota'
          id='saldocuota' name=' saldocuota' value='$saldocuota' />";
            $hidden_abono="<input type='hidden'  class='decimal abonoxcuota'
          id='abonocuota' name=' abonocuota' value='$abonocuota' />";
            $input_efect="<input type='text'  class='form-control decimal abonar text-success'
          id='abono_$j' name='abono_$j' value='' style='width:auto;font-weight: bold;'>";

            $switch   = $pagada ==1 ? " " : $input_efect;
            $el_add.="<tr>";
            $el_add.="<td>".$input_id_cuota.  $id_credito."</td>";
            $el_add.="<td>".$numcuota."&nbsp; ".$detalleVenta."</td>";
            $el_add.="<td>".$row['fecha_vence']."</td>";
            $el_add.="<td class='cuota'>".$cuota."</td>";
            $el_add.="<td>".$estado."</td>";

            $el_add.= '<td class="text-center">'.$switch.$hidden_saldo.$hidden_abono.'</td>';
            $el_add.="<td class='saldo_pend_cuota'>".$saldocuota."</td>";
            $el_add.="</tr>";
            $total_creditos += $total;
            $total_abonado += $abono;
            if ($pagada ==0) {
                $total_saldo += $cuota;
                $saldo_pendiente+= $saldocuota;
            }
        }
        $total_creditos =sprintf("%.2f", $total_creditos);
        $total_abonado =sprintf("%.2f", $total_abonado);
        $total_saldo =sprintf("%.2f", round($total_saldo, 2));
        $msj1 ="SALDO PENDIENTE: ";
        $msj2 ="TOTAL ABONAR: ";
        $input_abono="<input type='text'  class='form-control text-danger decimal monto_abonar'
            id='abonar_cr' name='abonar_cr' value='0' style='width:auto;  font-weight: bold;' readonly>";

        $el_add.="<tr>";
        $el_add.="<td>"." "."</td>";
        $el_add.="<td>"." "."</td>";
        $el_add.="<td  style='font-weight: bold;'>".$msj1."</td>";
        $el_add.="<td  style='font-weight: bold;' class='saldo_ante'>".$total_saldo."</td>";
        $el_add.="<td  style='font-weight: bold;'>".$msj2."</td>";
        $el_add.="<td>".$input_abono."</td>";
        $el_add.="<td class='total_pend_cuotas'>".$saldo_pendiente."</td>";
        $el_add.="</tr>";
    } else {
        $mensaje = "El Cliente no posee creditos con saldo pendiente en estos momentos " ;
        $el_add.="<tr>";
        $el_add.="<td colspan=7>".  $mensaje."</td>";

        $el_add.="</tr>";
    }
    echo $el_add;
}

function insertar()
{
    $cuantos       = $_POST['cuantos'];
    $array_json    = $_POST['json_arr'];
    $fecha         = $_POST['fecha'];
    $id_cliente    = $_POST['id_cliente'];
    $total_abonar  = $_POST['total_abonar'];
    $saldo_ante   = $_POST['total_saldo'];
    $id_apertura   = $_POST['id_apertura'];
    $hora          = date("H:i:s");
    $id_usuario = $_SESSION['id_usuario'];
    $id_sucursal = $_SESSION['id_sucursal'];

    $ins1 = false;
    $ins2 = false;
    $upd1 = false;
    $nuevosaldo=0;
    _begin();
    //$xdatos ="";
    if ($cuantos>0) {
        $table1 = 'cuota';
        $table2 = 'venta_cuota';
        $table3 = 'abono_historial';
        $array = json_decode($array_json, true);
        $arr_abono_creditos=[];
        $arr1=[];
        $arrfin=[];
        $n=0;
        foreach ($array as $fila) {
            /*
             let obj = {
             id_credito : id_credito,
             id_cuota   : id_cuota,
             ncuota     : ncuota ,
             valorcuota : valorcuota,
             abonar     : abonar,
             saldocuota : saldocuota,
             abonocuota : abonocuota,
     }
            */
            $n++;
            $pagada=0;
            $id_credito = $fila['id_credito'];
            $id_cuota = $fila['id_cuota'];
            $ncuota     = $fila['ncuota'];
            $valorcuota     = $fila['valorcuota'];
            $abonar    = $fila['abonar'];
            $saldocuota =$fila['saldo_pend_cuota'];
            $abonocuota= $fila['abonocuota']+$abonar;
            if ($saldocuota<=0) {
                $pagada=1;
            }
            $fd1 = array(
        'pagada' => $pagada,
        'fecha_pago' => $fecha,
        'saldocuota' => $fila['saldo_pend_cuota'],
        'abonocuota' => $abonocuota,
        'fecha_ult_abono' => $fecha,
      );
            $wc1 = "id_cuota='" . $id_cuota . "'";
            $upd1 = _update($table1, $fd1, $wc1);
            array_push($arrfin, $array);
            $q="SELECT *
        FROM venta_cuota AS vc WHERE vc.id_venta_cuota=$id_credito";
            $res=_query($q);

            if (_num_rows($res)>0) {
                $row=_fetch_assoc($res);
                $abono_previo=$row['abono'];
                $saldo_previo=$row['saldo'];
                $cuotaspagadas=$row['cuotaspagadas'];

                $nuevo_saldo=round(($saldo_previo-$abonar), 2);
                $nuevo_abono=round(($abono_previo+$abonar), 2);
                $nuevo_cuotaspagadas=$cuotaspagadas+1;

                $fd2 = array(
          'abono' => $nuevo_abono,
          'saldo' => $nuevo_saldo,
          'cuotaspagadas'=>$nuevo_cuotaspagadas,
        );
                $wc2= "id_venta_cuota='" . $id_credito . "'";
                $upd2 = _update($table2, $fd2, $wc2);
            }
        }

        if ($upd2) {
            $arr_abono_creditos=json_encode($arrfin);
            $table3 = 'abono_historial';
            $saldo_ultimo = $saldo_ante - $total_abonar;
            $fd3 = array(
      'id_cliente' =>$id_cliente,
      'arr_abono_creditos' => $array_json,
      'abono' => $total_abonar,
      'saldo_ante' =>$saldo_ante,
      'saldo_ultimo'=> $saldo_ultimo,
      'fecha' => $fecha,
      'hora' => $hora,
      'id_apertura' => $id_apertura,
      'id_sucursal'=> $id_sucursal,
      'cuotas' => 1,
      );
            $ins1 = _insert($table3, $fd3);
            $id_abono_historial = _insert_id();
        }
    }
    if ($upd1 && $upd2 && $ins1) {
        _commit();
        $xdatos['typeinfo']='Success';
        $xdatos['msg']='Abono realizado con exito!';
        $xdatos["fecha"] = ED($fecha);
        $xdatos["hora"] = hora($hora);
        $xdatos["id_abono_historial"] = $id_abono_historial;
        $xdatos["abono"] = sprintf("%.2f", $total_abonar);
        $xdatos["saldo_ante"] = sprintf("%.2f", $saldo_ante);
        $xdatos["saldo_ultimo"] = sprintf("%.2f", $saldo_ultimo);
        $xdatos["arr_abono_creditos"] = $arr_abono_creditos;
    //de una vez enviare a imprimir
    } else {
        _rollback();
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Registro no pudo ser guardado !'._error();
    }
    echo json_encode($xdatos);
}
function print_abono()
{
    $id_abono_historial= $_POST['id_abono_historial'];
    $id_usuario = $_SESSION['id_usuario'];
    $id_sucursal = $_SESSION['id_sucursal'];
    //Valido el sistema operativo y lo devuelvo para saber a que puerto redireccionar
    $info = $_SERVER['HTTP_USER_AGENT'];
    if (strpos($info, 'Windows') == true) {
        $so_cliente='win';
    } else {
        $so_cliente='lin';
    }
    $xdatos =print_abonos($id_abono_historial);
    //directorio de script impresion cliente
    $sql_dir_print="SELECT *  FROM config_dir WHERE id_sucursal='$id_sucursal'";
    $result_dir_print=_query($sql_dir_print);
    $row_dir_print=_fetch_array($result_dir_print);
    $dir_print=$row_dir_print['dir_print_script'];
    $shared_printer_win=$row_dir_print['shared_printer_matrix'];
    $shared_printer_pos=$row_dir_print['shared_printer_pos'];

    $xdatos['shared_printer_win'] =$shared_printer_win;
    $xdatos['shared_printer_pos'] =$shared_printer_pos;
    $xdatos['dir_print'] =$dir_print;
    $xdatos['sist_ope'] =$so_cliente;
    echo json_encode($xdatos);
}
function modalCredito()
{
    $id_sucursal=$_SESSION['id_sucursal']; ?>
    <div class="modal fade" id="modalCredito" tabindex="-1" role="dialog" aria-labelledby="myModalX" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content modal-md">
          <div class="modal-header">
            <div class="row">
            <div class="col-md-6">
            <h3 class="modal-title text-center text-success textmodalPrint"
               id="myModalX">Agregar Credito Antiguo  &nbsp;
              <span class="fa-2x fa-solid fa-invoice text-success"></span></h3>  </div>
              <div class="col-md-3">

            </div>
        </div>
          <div class="modal-body">
              <input type="hidden" name="id_cliente2" id="id_cliente2"  />
            <div class="row">
              <div class="col-md-12">
                <table	class="table table-condensed table-striped" id="tableviewX">
                  <tr class='cmb'>
                    <td><h5 class='text-success '>Cliente</h5> </td>
                    <td>
                      <input type="text" placeholder="Buscar" class="form-control" id="txtBuscarCte2" name="txtBuscarCte2" value="">
                    </td>
                  </tr>
                </table>
              </div>
            <div class="col-md-12 client_select2"></div>
        </div>

            <div class="row">
      					<table class='table' id='addShowClient'>
                  <tr tabindex=0>
                    <td><h5 class='text-danger '>Monto $ </h5></td>
                    <td><input type="text" placeholder="monto" class="form-control decimal
                      input_header_panel" id="montoCredito" name="montoCredito" /></td>
                  </tr>
                  <tr>
                    <td><h5 class='text-danger '>Fecha Credito </h5></td>
                    <td><input type="text" placeholder="fecha credito" class="form-control datepick"
                      id="fecha_credito" name="fecha_credito" /></td>
                 </tr>
                 <tr>
                    <td>
                      <button type="button"  class="btn btn-danger pull-right" id="btnSalir" >
                      <i class="fa fa-stop"></i> &nbsp;&nbsp; Salir &nbsp;&nbsp</button>
                    </td>
                      <td><button type="button" class="btn btn-success" name='btnSaveCred'
                        id='btnSaveCred'><i class="fa fa-save"></i> &nbsp;  Guardar</button></td>
                  </tr>
      				  </table>
            </div>
            <div class="row">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php
}
function insertCredito()
{
    // insertar credito antiguo sin mayor detalle
    $fecha         = $_POST['fecha'];
    $id_cliente    = $_POST['id_cliente'];
    $montoCredito  = $_POST['montoCredito'];
    $id_usuario = $_SESSION['id_usuario'];
    $id_sucursal = $_SESSION['id_sucursal'];

    $table="credito";
    $form_data = array(
  'id_cliente'  => $id_cliente,
  'fecha'       => $fecha,
  'tipo_doc'    => 'CCA',
  'numero_doc'  => '1',
  'id_factura'  => '-1',
  'dias'        =>  0,
  'total'       =>  $montoCredito,
  'abono'       => 0,
  'saldo'       =>   $montoCredito,
  'finalizada'  => 0,
  'id_sucursal' => $id_sucursal,
  );
    $insert=_insert($table, $form_data);
    $id_credito = "-1";
    if ($insert) {
        $id_credito = _insert_id();
        $xdatos['typeinfo']='Success';
        $xdatos['msg']='credito agregado con exito!';
        $xdatos["id_credito"] = $id_credito;
    } else {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Registro no pudo ser guardado !'._error();
    }
    echo json_encode($xdatos);
}
function getDetVenta($id_venta_cuota){
  $detalle_venta ="<strong>";
  $align=new AlignMarginText();
  
    $q="SELECT id_factura,saldo,numerocuotas FROM venta_cuota 
    WHERE  id_venta_cuota='$id_venta_cuota'";
    $r=_query($q);
    $row=_fetch_row($r);
    $id_factura=$row[0];
    $saldo=$row[1];
    $numerocuotas=$row[2];
    //var_dump($id_factura);
    $sql_fact_det="SELECT  producto.id_producto, producto.descripcion, producto.exento,
    producto.codigo,
    presentacion.nombre as descp,
    presentacion.descripcion AS descripcion_pr,
    presentacion_producto.descripcion AS descpre,
    presentacion_producto.unidad,
    presentacion_producto.id_pp as id_presentacion,
    factura_detalle.*
    FROM factura_detalle
    JOIN producto ON factura_detalle.id_prod_serv=producto.id_producto
    JOIN presentacion_producto ON factura_detalle.id_presentacion=presentacion_producto.id_pp
    JOIN presentacion ON presentacion.id_presentacion=presentacion_producto.id_presentacion
    WHERE  factura_detalle.id_factura='$id_factura'
    ";
    $result_fact_det=_query($sql_fact_det);
  //	return $result_fact_det;
  //$result_fact_det = getDetalleVenta($id_venta_cuota);
  $wdesc=60;
  $total_final=0;
  $espacio=" ";
  $nrows_fact_det=_num_rows($result_fact_det);

  for($s=0;$s<$nrows_fact_det;$s++){
    $row_fact_det=_fetch_array($result_fact_det);
    $id_producto =$row_fact_det['id_producto'];
    $descripcion =$row_fact_det['descripcion'];
    //descripcion presentacion
   
    $nombre_pre =$row_fact_det['descp'];
   
    $cantidad =$row_fact_det['cantidad'];

    $subt=$row_fact_det['subtotal'];
    $unidad=$row_fact_det['unidad'];
   
    $cantidad=$cantidad/$unidad;
    $descripcion1 = $descripcion." ".$nombre_pre;
   
    $descripts = $align->wordwrap1($descripcion1,$wdesc);
    $ln=count($descripts);
  
    $subtotal=sprintf("%.4f",$subt);
    $total_final=$total_final+$subtotal;
    $cant = $align->rightaligner($cantidad, $espacio,6);
    $detalle_venta .= $cantidad  ." - ".$descripts[0]. "<br>";
   
    for($p=1;$p<$ln;$p++){
      $detalle_venta .= " ".$descripts[$p]. "<br>";
    }
  }
  $detalle_venta .="</strong>";
 return  $detalle_venta ;
}
if (!isset($_REQUEST['process'])) {
    initial();
}
if (isset($_REQUEST['process'])) {
    switch ($_REQUEST['process']) {
    case 'credito_cliente':
    credito_cliente();
    break;
    case 'insertar':
    insertar();
    break;
    case 'print_abono':
    print_abono();
    break;
    case 'insertCredito':
    insertCredito();
    break;
  }
}
?>
