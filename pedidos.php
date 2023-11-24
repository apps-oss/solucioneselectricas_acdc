<?php
include("_core.php");
include('num2letras.php');
include('facturacion_funcion_imprimir.php');
// Page setup
function initial()
{
    $_PAGE = array();
    $title='Pedido por Cliente';
    include_once "_headers.php";
    $_PAGE ['title'] = $title;
    $_PAGE ['links'] .= '<link href="css/plugins/autocomplete/autocomplete.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/style_scroll.css" rel="stylesheet">';
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
    } else {
        $caja  = 1 ;
        $turno = 1 ;
    }
    $datos_caja=getCajaBySucursal($id_sucursal); ?>

  <div class="wrapper wrapper-content  animated fadeInRight">
  <div class="row" id="row1">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <?php if ($links!='NOT' || $admin=='1') {
        //if ($turno_vigente=='1') {
        echo"
            <div class='ibox-title'>
            <h5>$title</h5>
            </div>"; ?>
            <div class="ibox-content">
              <!--load datables estructure html-->
              <div class="row">
                <!--div class="input-group"-->
                  <div class="col-md-6">
                    <input type="hidden" name="id_cliente" id="id_cliente"  />
                    <input type hidden id='caja' name='caja' value="<?php echo  $datos_caja['id_caja']; ?>" />   
                    <input type hidden id='turno' name='turno' value="<?php echo  $turno; ?>" />   
                    <input type hidden id='id_apertura' name='id_apertura' value="<?php echo  $id_apertura; ?>" /> 
                    <input type hidden id='id_factura' name='id_factura' value="-1" />     
                  <div class="search-element">
                    <label>Buscar Cliente</label>
                      <div class="auto-search-wrapper max-height loupe">
                        <input type hidden id='cliente_seleccionado' name='cliente_seleccionado' value='' />                       
                          <input type="text" placeholder="Buscar" class="form-control" id="txtBuscarCte" name="txtBuscarCte" value="">
                      </div>
                  </div>
                  </div>
                  
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Fecha Inicio</label>
                     
                      <input type="text" placeholder="Fecha Inicio" class="datepick form-control" id="fecha_movimiento" name="fecha_movimiento" value="<?php echo  $fecha_actual; ?>">
                     
                    </div>
                  </div>
                  <div class="col-md-2" hidden>
                    <div class="form-group">
                      <label>Fecha Fin</label>
                      <?php $fechaini =restar_dias($fecha_actual, 1); ?>                      
                      <input type="text" placeholder="Fecha Inicio" class="datepick form-control" id="fechafin" name="fechafin" value="<?php echo  $fecha_actual; ?>">
                    </div>
                  </div>
                  <div class="col-md-2">
                  
                  <div class="btn-group"><br>
                    <button type="button" id="btnSave" name="btnSave"
                    class="btn btn-md btn-primary pull-right" style='margin-left:2px;'>
                    <i class="fa-solid fa-save"></i> Guardar</button>&nbsp;
                                
                  </div>
                 </div>
                <hr>
              </div>                                     
              <div class="row">
                <div class="col-md-12 clientSel" id= "client_select"></div>                
             </div>
             <div class="row">
                <div class="col-md-6">
                    <input type="hidden" name="id_ruta" id="id_ruta" value='-1'  />
                    <div class="search-element">
                          <label>Buscar Producto (Por Descripción ó Código)</label>
                          <div class="auto-search-wrapper max-height loupe">
                              <input type hidden id='ruta_seleccionada' name='ruta_seleccionada' value='' />
                              <input type="text" placeholder="Buscar" class="form-control" id="txtBuscarProd" name="txtBuscarProd" value="">
                          </div>
                     </div>
                </div>
                <div class="col-md-2">                  
                    <label>Items</label>                                       
                    <input type="text" class="form-control text-primary" id="items" name="items" value="" readonly>                 
                </div>
                <div class="col-md-2">                  
                    <label>Cant. Prod.</label>                                       
                    <input type="text" class="form-control text-primary" id="totcant" name="totcant" value="" readonly>                 
                </div>
                <div class="col-md-2">                  
                    <label>Total $</label>                                       
                    <input type="text" class="form-control totalpagar text-success" id="totaldinero" name="totaldinero" value="" readonly>                 
                </div>
              </div>
            </div>           
            <div class="row">
              <div class="col-md-12">
                <div class='ibox'>   
                  <div class='ibox-content'>   
                    <section>
                        <div class="table">
                            <!--table class="table table-striped tableFixHead" id="agend"-->
                            <table class="table table-striped" id="inv">
                                <thead class='headd'>
                                  <tr class='tra'>
                                     <th class='col-md-3'><?=ins_nbsp('4'); ?>Descripcion</th>
                                    <th class='col-md-1'><?=ins_nbsp('2'); ?>Stock</th>
                                    <th class='col-md-1'><?=ins_nbsp('3'); ?>Cantidad&nbsp;</th>
                                    <th class='col-md-1'><?=ins_nbsp('7'); ?>Presentación&nbsp;</th>
                                    <th class='col-md-1'><?=ins_nbsp('3'); ?>Precios</th>
                                    <th class='col-md-1'><?=ins_nbsp('3'); ?>$.</th>
                                    <th class='col-md-1'><?=ins_nbsp('3'); ?>Tot. Fact.</th>
                                    <th class='col-md-1'><?=ins_nbsp('10'); ?>Acción.</th>
                                    <!--th class="c30 tdh ">Desc</th>
                                    <th class="c10 tdh ">Stock</th>
                                    <th class="c10 tdh ">Cantidad&nbsp;</th>
                                    <th class="c10 tdh ">Presentación&nbsp;</th>
                                    <th class="c10 tdh "><?=ins_nbsp('3'); ?>Precios</th>
                                    <th class="c10 tdh "><?=ins_nbsp('3'); ?>$.</th>
                                    <th class="c10 tdh "><?=ins_nbsp('1'); ?>Tot. Fact.</th>
                                    <th class="c10 tdh "><?=ins_nbsp('1'); ?>Acción.</th-->
                                  </tr>
                                </thead>
                                <tbody class='scrolled' id="inventable"  ></tbody>
                            </table>
                        </div>                    
                        <input type='hidden' name='id_apertura' id='id_apertura' value='<?php echo $id_apertura; ?>'>
                        <input type="hidden" name="total_transporte" id="total_transporte" value="0">
                        <input type="hidden" name="total_facturado" id="total_facturado" value="0">
                        <input type='hidden' name='totalfactura' id='totalfactura' value='0'>
                        <input type='hidden' name='filas' id='filas' value='0'>
                        <div class="row well m-t ">   
                            <div class="col-md-8">                  
                              <div class="totaLetras text-info" id='totaltexto'></div>
                            </div>  
                            <div class="col-md-4">  
                                <div class="conceptos_factura">
                                <div class='text-success' id='total_pedido'></div>
                                    <!--table class='table invoice-total'>
                                        <tbody>
                                          <tr><td><strong>TOTAL :</strong></td>
                                              <td  class='text-success' id='total_pedido'></td></tr>
                                          </tbody>
                                    </table-->
                                </div>
                             </div>
                        </div> 
                    </section>
                  </div>
                </div>
              </div>             
              <div class="col-md-4" hidden>
              <div class='ibox'> 
              <div class='ibox-content'> 
                <div class="table">
                    <table class="table table-striped" id="data_client">
                        <thead class='headd'>
                              <tr>
                                <th class="c100">DATOS CLIENTE PEDIDO</th>
                              
                              </tr>
                            </thead>
                            <tbody class='scrolled'>
                              <tr class='tra' hidden>
                                <td class=' c50 text-success'>FECHA FACTURA:</td>
                                <td class=' c50'><input type="text" id="fecha_fact" class="txt_box2 datepick c90"  value="<?php echo date("Y-m-d"); ?>"></td>
                              </tr>
                              <tr>
                                <td class=' c50 text-success'>DIRECCION: </td>
                                <td class=' c50'><input type="text" id="dircli" class="txt_box2 c90"  value="" readOnly></td>
                              </tr>
                              <tr>
                                <td class=' c50 text-success'>NIT: </td>
                                <td class=' c50'><input type="text" id="nitcli" class="txt_box2 c90"    value="" readOnly></td>
                              </tr>
                              <tr>
                                <td class=' c50 text-success'>NRC: </td>
                                <td class=' c50'><input type="text" id="nrccli" class="txt_box2 c90"   value="" readOnly></td>
                              </tr>                            
                              <tr>
                                <td class=" c50  text-success ">SUMAS $:</td>
                                <td class=' c50  text-green'><input type="text" id="total_gravado2" class="txt_box2  c90"   value="" readOnly></td>
                              </tr>                            
                              <tr>
                            <td class=" c50   text-success"> IVA $</td>
                            <td class=' c50  text-green' ><input type="text" id='total_iva2' class="txt_box2  c90"   value="" readOnly> </td>
                              </tr>
                              <tr>
                              <td class=" c50  text-success ">SUBTOTAL $:</td>
                              <td class=' c50  text-green'><input type="text" id="subtotal2" class="txt_box2  c90"   value="" readOnly></td>
                              </tr> 
                              <tr hidden>
                            <td class=" c50   text-success"> PERCEPCION $</td>
                            <td class=' c50  text-green' ><input type="text" id='total_percibe' class="txt_box2  c90"   value="" readOnly> </td>
                              </tr>
                              <tr>
                            <td class=" c50   text-success"> RETENCION $</td>
                            <td class=' c50  text-green' ><input type="text" id='total_retencion' class="txt_box2  c90"   value="" readOnly> </td>
                              </tr>
                              <tr>
                              <td class=" c50   text-success ">TOTAL FIN $:</td>
                              <td class=' c50  text-green'><input type="text"  id='total_monto_final' class="txt_box2  c90"   value="" readOnly> </td>
                              </tr>
                             <input type="hidden" id="efectivov" class="txt_box2"   value="0">
                             <input type="hidden" id="cambiov" class="txt_box2"   value="0" readOnly>

                            </tbody>
                          </table>
                  </div> 
            </div>
          </div>
            </div>
              
                <!-- Modal -->
          <div class='modal fade' id='viewProd' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
            <div class='modal-dialog'>
              <div class='modal-content'></div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->
              <?php modalPago(); ?>
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
              <!-- /.modal -->
              </div>
           
          </div>
          <!--<div class='ibox float-e-margins' -->
        </div>
        <!--div class='col-lg-12'-->
      </div>
      <!--div class='row'-->
    </div>
    <!--div class='wrapper wrapper-content  animated fadeInRight'-->
    <?php
    /*
        } else {
            echo "<div class='ibox-content'><div class='row'><div class='col-lg-12'>";
            echo "<div class='alert alert-warning'><h3 class='text-danger'>
        No Hay Apertura de Caja vigente para este turno!!! aperture <a href='admin_corte.php'>aquí</a>  </h3>
        </div></div></div></div>
        </div></div></div></div></div>
        ";
        }  //apertura de caja
        */
    } //permiso del script
    else {
        $mensaje = mensaje_permiso();
        echo "<br><br>$mensaje</div></div></div></div>";
        include "footer.php";
    }

    include("footer.php");
    echo "<script src='js/plugins/sweetalert/sweetalert2.all.min.js'></script>";
    echo "<script src='js/plugins/cellNavigate.js'></script>";
    echo '<script src="js/plugins/axios/axios.min.js"></script>';
    echo '<script src="js/plugins/autocomplete/autocomplete.min.js"></script>';
    echo" <script type='text/javascript' src='js/funciones/pedidos.js'></script>";
}

function ins_nbsp($n)
{
    $val="";
    for ($i=0;$i<$n;$i++) {
        $val.='&nbsp;';
    }
    return $val;
}
function insertar1()
{
    $caja   = $_POST['caja'];
    $turno = $_POST['turno'];
    $id_apertura = $_POST['id_apertura'];
    $array_json   = $_POST['json_arr'];
    $arrayData = json_decode($array_json, true);
    $arrAgenda = json_decode($_POST['json_agenda'], true);
    $fecha_actual= date('Y-m-d');
    $hora = date('H:i:s');
    $tipodoc='PED';
    //echo $array['id_cliente'];
    $id_empleado=$_SESSION["id_usuario"];
    $id_sucursal=$_SESSION["id_sucursal"];
    $fecha_actual = date('Y-m-d');
    $id_resolucion=getResolucion($tipodoc);
    $numero_doc= getCorrelativo($tipodoc, $caja);
    $rowDoc=getTipoDoc($tipodoc);
    _begin();
    //booleans a verificar que se cumplan para completar la transaccion!
    $a=1;
    $b=1;
    $c=1;
    $d=1;
    $serie="";
    $t_fact= 'pedidos';
    $fd_fact = array(
      'id_cliente' =>  $arrayData['id_cliente'],
      'fecha' => $fecha_actual,
      'numero_doc' => $numero_doc,
      'subtotal' => $arrayData['subtotal'],
      'subt_bonifica'=>0, // no utilizado
      'sumas'=>$arrayData['facturado'],
      'suma_gravado'=> $arrayData['facturado'],
      'iva' =>$arrayData['calc_iva'],
      'retencion'=>0, //no aplicada
      'venta_exenta'=> 0,//no aplicada,
      'total_menos_retencion'=>$arrayData['subtotal'],
      'total' => $arrayData['total_fin'],
      'id_usuario'=>$id_empleado,
      'id_empleado' => $id_empleado,
      'id_sucursal' => $id_sucursal,
      'tipo' => $rowDoc['nombredoc'],
      'serie' => $serie,
      'num_fact_impresa' => 0, //actualizar al imprimir
      'hora' => $hora,
      'finalizada' => '1',
      'abono'=>0,
      'saldo' => 0,
      'tipo_documento' => $tipodoc,
      'id_apertura' => $id_apertura,
      'id_apertura_pagada' => $id_apertura,
      'caja' => $caja,
      'credito' => 1,
      'turno' => $turno,
      'precio_aut' => 0,
      'clave' => '0',
      'nombre' => $arrayData['concepto'],
      'datos_extra' => $arrayData['concepto'],
      'extra_nombre' => 'SERVICIO',
      'pagar' => $arrayData['total_fin'],
      'tipo_pago' =>'CRE',
      'total_credito' =>0,
      'id_resolucion' =>$id_resolucion,
      'percepcion' => 0,
      'retencion' => $arrayData['retencion'],
      'fact_transp' => 1,

    );
    $insertar_fact = _insert($t_fact, $fd_fact);
    $id_fact= _insert_id();

    if (!$insertar_fact) {
        $a=0;
    }
    $t_fact_det= 'pedidos_detalle';
    $data_fact_det = array(
    'id_factura' => $id_fact,
    'id_prod_serv' => '-1',
    'cantidad' =>'1',
    'bonificacion'=> 0,
    'precio_venta' => $arrayData['facturado'],
    'subtotal' => $arrayData['subtotal'],
    'subt_bonifica' => 0,
    'tipo_prod_serv' => 'SERVICIO',
    'id_empleado' => $id_empleado,
    'id_sucursal' => $id_sucursal,
    'fecha' => $fecha_actual,
    'id_presentacion'=> '-1',
    'exento' => 0,
    'combustible' => 0,
    'impuesto'    => 0,
    'subtotal_iva' => $arrayData['calc_iva'],
    'total' => $arrayData['total_fin'],
    );
    $insertar_fact_det = _insert($t_fact_det, $data_fact_det);
    if (!$insertar_fact_det) {
        $b=0;
    }
    foreach ($arrAgenda as $serv) {
        $id      = $serv['id'];
        $id_agend      = $serv['id_agenda'];

        $table3 = 'servicios_realizados';
        $fd3 = array(
          'fecha_factura' =>$fecha_actual,
          'facturado' =>1,
          'id_factura' => $id_fact,
          );
        $wc3="id='$id'  AND id_agenda='$id_agend'";
        $upd1 = _update($table3, $fd3, $wc3);
        if (!$upd1) {
            $c=0;
        }
        $fd4 = array(
          'fecha_factura' =>$fecha_actual,
          'facturado' =>1,
          );
        $wc4="id='$id_agend'";
        $table4 = 'agenda_dia';
        $upd2 = _update($table4, $fd4, $wc4);
        if (!$upd2) {
            $d=0;
        }
    }
    if ($a && $b && $c &&$d) {
        _commit(); // transaction is committed
        $xdatos['typeinfo']='Success';
        $xdatos['msg']='Registro  Numero: <strong>'.$numero_doc.'</strong>  Guardado con Exito !';
        $xdatos['numdoc']=$numero_doc;
        $xdatos['id_factura']=$id_fact;
        $xdatos['total']=$arrayData['total_fin'];
        $xdatos['caja']=$caja;
        $xdatos['descrip_pago']= $arrayData['concepto'];
    } else {
        _rollback(); // transaction rolls back
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Registro no pudo ser ingresado!'.$a."-".$b."-".$c;
    }

    echo json_encode($xdatos);
}
function getCorrel($id_sucursal)
{
    $tc="correlativo";
    $wc="WHERE id_sucursal='$id_sucursal'";
    $sql="select * from $tc $wc";
    $result= _query($sql);
    $rows=_fetch_array($result);
    $nrows=_num_rows($result);
    $ult_ped=$rows['ped']+1;
    $fd = array(
      'ped' => $ult_ped,
    );

    $upd = _update($tc, $fd, $wc);
    if ($upd) {
        return $ult_ped;
    } else {
        return  0;
    }
}
function getResolucion($tipodoc)
{
    //resolucion, autorizacion de facturas por SUCURSAL
    $id_sucursal=$_SESSION["id_sucursal"];
    $q_res="SELECT id_resolucion FROM resolucion
  WHERE  alias='$tipodoc' AND vigente=1
  AND id_sucursal='$id_sucursal'
  ";
    $id_resolucion=0;
    $ress = _query($q_res);
    $numrowss= _num_rows($ress);
    if ($numrowss>0) {
        $row_res=_fetch_row($ress);
        $id_resolucion=$row_res[0];
    }
    return $id_resolucion;
}
function getCorrelativo($tipo_impresion, $caja)
{
    $id_sucursal=$_SESSION["id_sucursal"];
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
        $numero_doc= ultimoCorrelativo($correlativo_dispo, $tipo_impresion);
    }
    if ($tipo_impresion =='CCF') {
        $tipo_entrada_salida='CREDITO FISCAL';
        $data_numdoc = array(
          'ccf' => $ult_ccf,
        );
        $numero_doc= ultimoCorrelativo($ult_ccf, $tipo_impresion);
    }
    if ($tipo_impresion != "TIK") {
        $where_clause_n=" WHERE id_sucursal='$id_sucursal'";
        $insertar_numdoc = _update($table_numdoc, $data_numdoc, $where_clause_n);
    } else {
        $tab = 'caja';
        $where_clause_c=" WHERE id_caja='$caja'";
        $insertar_numdoc = _update($tab, $data_numdoc, $where_clause_c);
    }
    return $numero_doc;
}
//documentos activos para facturacion
function getTipoDoc($alias="")
{
    $q= "SELECT idtipodoc, nombredoc, alias ";
    $q.="  FROM tipodoc WHERE cliente=1  AND activo=1 ";
    $q.=" AND alias='$alias'";
    $res=_query($q);
    $row=_fetch_array($res);
    return $row;
}
function ultimoCorrelativo($ult_doc, $tipo)
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

function getMotorista()
{
    $q_emp="SELECT empleado.id_empleado,
  concat(empleado.nombre,' ',empleado.apellido) AS nombre
  FROM empleado WHERE id_tipo_empleado=5 ";
    $sql=_query($q_emp);
    $options="";
    while ($row=_fetch_array($sql)) {
        $options.="  <option value='".$row['id_empleado']."'>". $row['nombre']."</option>";
    }
    return $options;
}
function getEquipo()
{
    $q_emp="SELECT  id, numero_unidad,placa FROM vehiculo ";
    $sql=_query($q_emp);
    $options="";
    while ($row=_fetch_array($sql)) {
        $options.="<option value='".$row['id']."'>". $row['numero_unidad']." ( ".$row['placa'].")</option>";
    }
    return $options;
}





function getImpuestos()
{
    //impuestos
    $sql_iva="SELECT iva,monto_retencion1,monto_retencion10,monto_percepcion FROM empresa";
    $result_IVA=_query($sql_iva);
    $row_IVA=_fetch_array($result_IVA);
    return $row_IVA;
}
function getPercibeRetiene($id_cliente)
{
    /////////////////////////CLIENTE
    $sql_cliente1="SELECT id_cliente,retiene,retiene10,percibe,
  nombre,nit,nrc,dui,direccion
  FROM cliente
  where id_cliente='$id_cliente'";
    $retiene=0;
    $percibe=0;

    $qcliente=_query($sql_cliente1);
    while ($row=_fetch_array($qcliente)) {
        $id_cliente=$row['id_cliente'];
        if ($row['retiene']==1) {
            $retiene=0.01;
        }
        if ($row['retiene10']==1) {
            $retiene=0.1;
        }
        if ($row['percibe']==1) {
            $percibe=0.01;
        }
        $nrc="_";
        if ($row['nrc']!="") {
            $nrc=$row['nrc'];
        }
        $nit="_";
        if ($row['nit']!="") {
            $nit=$row['nit'];
        }
        $dui="_";
        if ($row['dui']!="") {
            $dui=$row['dui'];
        }
        $direccion="_";
        if ($row['direccion']!="") {
            $direccion=$row['direccion'];
        }
    }

    $datos=
    array('percibe'=> $percibe,
    'retiene'=>$retiene,
    'nrc'=>$nrc,
    'nit'=>$nit,
    'dui'=>$dui,
    'direccion'=>$direccion,
  );
    return $datos;
}

function modalPago()
{
    ?>
  <div class="modal fade" id="modalPago" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content  modal-custom">
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
                <div class="col-lg-12" hidden>
                  <div class="form-group">
                    <label class="col-lg-4 control-label">Número Doc. Impreso</label>
                    <div class="col-lg-4">
                      <input type='text' id='numeroDocImpreso' name='numeroDocImpreso' class='form-control' value='' /> 
                    </div>
                  </div>
                </div>
     
                  <div class="col-md-6   metodos_pago "></div>
                  <div class="col-md-6 ">

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
function modalServices()
{
    ?>
 <div class="modal fade" id="modalService" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
 <div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
			<h3 class="modal-title" id="lineModalLabel">Registro de Servicio por Cliente</h3>
		</div>
		<div class="modal-body">
		
    <form class="form-horizontal">
      <div class="row">
      <input type='hidden'  class='n_agenda ' id='num_agenda' name='num_agenda' value='' />
      <input type='hidden'  class='tottransp ' id='totaltransportado' name='totaltransportado' value='' />
      <input type='hidden'  class='id_equipoo' id='id_equipo' name='id_equipo' value='' />
      <input type='hidden'  class='ruta ' id='id_rutaa' name='id_rutaa' value='' />
      <input type='hidden'  class='cliente ' id='id_clientee' name='id_clientee' value='' />
      <input type='hidden'  class='motorista ' id='id_motorr' name='id_motorr' value='' />
      <div class="col-lg-6">
        <div class="form-group">
          <label class="col-lg-3 control-label">Cliente:</label>
          <label class="col-lg-9 control-label nomClie" id="nombreClie"></label>  
        </div>
        <div class="form-group">
          <label class="col-lg-3 control-label">Motorista:</label>
          <label class="col-lg-9 control-label nombMot" id="nombreMot"></label>  
        </div>
        <div class="form-group">
          <label class="col-lg-3 control-label">Ruta:</label>
          <label class="col-lg-9 control-label nombRut" id="nombreRuta"></label>  
        </div>
        <hr>
          <div class="row">
          <div class="col-lg-12"  id="totalTransportado"></div>                   
          </div>
        <!-- valores de combustible transportado-->
        <div class="form-group">
          <label class="col-lg-6 control-label">Diesel</label>
            <div class="col-lg-6">
            <input type='text'  class='form-control dec2 qty2' id='diesell' name='diesell' value='' style='width:80%;'>
          </div>
          </div>
          <div class="form-group">
          <label class="col-lg-6 control-label">Regular</label>
            <div class="col-lg-6">
            <input type='text'  class='form-control dec2 qty2' id='regularr' name='regularr' value='' style='width:80%;'>
          </div>
          </div>
        
          <div class="form-group">
          <label class="col-lg-6 control-label">Super</label>
            <div class="col-lg-6">
              <input type='text'  class='form-control dec2 qty2' id='superr' name='superr' value='' style='width:80%;'>
            </div>
          </div>
          <div class="form-group">
          <label class="col-lg-6 control-label">ION Diesel</label>
            <div class="col-lg-6">
            <input type='text'  class='form-control dec2 qty2' id='ionn' name='ionn' value='' style='width:80%;'>
          </div>
          </div>
          <div class="form-group">
          <label class="col-lg-6 control-label">Total Transportado </label>
            <div class="col-lg-6">
            <input type='text'  class='form-control dec2 totaltranspfin text-right text-success strong' id='total_transpp' name='total_transpp' value='' style='width:80%;' readonly>
          </div>
          </div>

      </div>
      <div class="col-lg-6">
      <div class="form-group">
          <label class="col-lg-6 control-label">Fecha Servicio</label>
            <div class="col-lg-6">
            <input type="text" placeholder="Fecha Servicio" class="datepick form-control" id="fechaserv" name="fechaserv" value="<?= date('Y-m-d'); ?>" style='width:80%;'>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-6 control-label">Nota de Remisión</label>
            <div class="col-lg-6">        
            <input type='text'  class='form-control rem' id='remision' name='remision' value='' style='width:80%;'>
          </div>
        </div>
       
       
        <div class="form-group">
          <label class="col-lg-6 control-label">Costo Galón Ruta</label>
            <div class="col-lg-6">
            <input type='text'  class='form-control dec2 costogal' id='costogalon' name='costogalon' value='' style='width:80%;'>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-6 control-label">Total Facturar $</label>
            <div class="col-lg-6">
            <input type='text'  class='form-control totfac' id='totalfact' name='totalfact' value='' style='width:80%;' readonly>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-6 control-label">Diesel Asignado</label>
            <div class="col-lg-6">
            <input type='text'  class='form-control decimal  die_mot qty' id='diesel_mot' name='diesel_mot' value='' style='width:80%;'>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-6 control-label">Precio %</label>
            <div class="col-lg-6">
            <input type='text'  class='form-control decimal  preporc' id='precioporc' name='precioporc' value='' style='width:80%;'>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-6 control-label">Total Pago Motorista $</label>
            <div class="col-lg-6">
            <input type='text'  class='form-control decimal totpagomot' id='totalpagomot' name='totalpagomot' value='' style='width:80%;' readonly>
          </div>
        </div>
        </div>
        </div> 
      <div class='row text-right'>
     
      <button type="button" id='btnSaveService' class="btn btn-success"><i class="fa-solid fa-save"></i>Guardar</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">Salir</button>
      </div>
    </form>
		</div>
    <div class="modal-footer">
      
    </div>
	</div>
  </div>
</div> 
<?php
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
            //$mostrar="<div class='col-md-12'><label class='text-success  '>Cliente: ".$cliente." </label>&nbsp;";
            $mostrar.="<label class='text-success'> AUTORIZADO, Cr&eacute;dito disponible:$".$saldo_disponible." </label></div>";
            $mostrar.="<div class='col-md-12'><label class='text-primary text-center'>M&eacute;todo de Pago";
            $array2= getTipoPago();
            $mostrar.= create_select_list("metodo_pago", $array2, 'CRE', "width:100%;", "5");
            $mostrar.="</label></div>";
        } else {
            //$mostrar="<div class='col-md-12 '><label class='text-success  '>Cliente: ".$cliente." </label>&nbsp;";
            $mostrar.="<label class='text-danger '>  Cr&eacute;dito NO AUTORIZADO, saldo disponible:$".$saldo_disponible." </label></div>";
            $mostrar.="<div class='col-md-12'><label class='text-primary text-center'>M&eacute;todo de Pago";
            $array2= getMetodosPagoNoCred();
            $mostrar.= create_select_list("metodo_pago", $array2, 'CRE', "width:100%;", "5");
            $mostrar.=" </label></div> ";
        }
    } else {
        //$mostrar="<div class='col-md-12 '><label class='text-success  '>Cliente "." </label>&nbsp;";
        $mostrar.="<label class='text-danger '>  Cr&eacute;dito NO AUTORIZADO, saldo disponible:$".$saldo_disponible." </label></div>";
        $mostrar.="<div class='col-md-12'><label class='text-primary text-center'>M&eacute;todo de Pago";
        $array2= getMetodosPagoNoCred();
        $mostrar.= create_select_list("metodo_pago", $array2, 'CRE', "width:100%;", "5");
        $mostrar.=" </label></div> ";
    }
    //$mostrar.=" <div class='row'> </div>";
    $xdatos['datos']=$mostrar;
    echo json_encode($xdatos);
}
function validarNumdoc()
{
    $id_sucursal=$_SESSION['id_sucursal'];
    //SELECT * FROM resolucion WHERE alias='COF' AND vigente=1
    $numero = trim($_REQUEST['numeroDocImpreso']);
    $alias = $_REQUEST['tipo_impresion'];
    $q="SELECT * FROM resolucion
    WHERE '$numero' BETWEEN desde AND hasta
    AND alias='$alias' AND vigente=1
    AND id_sucursal='$id_sucursal'
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
        AND id_sucursal='$id_sucursal'
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

function imprimir_fact()
{
    $numero_doc = $_POST['numero_doc'];
    $tipo_impresion= $_POST['tipo_impresion'];
    $id_factura= $_POST['num_doc_fact'];
    $id_sucursal=$_SESSION['id_sucursal'];
    $numero_factura_consumidor = $_POST['numero_factura_consumidor'];
    $fecha_fact=$_POST['fecha_fact'];
    //tipo de pago
    $con_pago     = $_POST['tipo_pago'];
    //array json de pagos  que vienen del modal puedens er combinado
    $array_json   = $_POST['json_arr'];
    $cuantos      = $_POST['cuantos'];
    $credito=0;
    $total_credito =0;
    $sql_fact="SELECT * FROM pedidos WHERE id_factura='$id_factura'";
    $result_fact=_query($sql_fact);
    $nrows_fact=_num_rows($result_fact);
    if ($nrows_fact>0) {
        $dias_credito  = 0;
        $alias_pago    = "";
        $dats_ft = _fetch_array($result_fact);
        $id_cliente= $dats_ft["id_cliente"];
        if ($fecha_fact == "" || $fecha_fact =='0000-00-00' || $fecha_fact =='00-00-0000') {
            $fecha_fact = date('Y-m-d');
        }
        $table='pedidos_pago';

        if ($cuantos>0) {
            $array = json_decode($array_json, true);
            foreach ($array as $fila) {
                $alias_pago      = $fila['alias_pago'];
                $subtotal        = $fila['subtotal'];
                $total_facturado = $fila['total_facturado'];
                $datos_extra     = $fila['datos_extra'];
                //$table_fact_det= 'pedidos_detalle';
                $fdata = array(
                  'id_factura'      => $id_factura,
                  'alias_tipopago'  => $alias_pago,
                  'subtotal'        => $subtotal,
                  'total_facturado' => $total_facturado,
                  'datos_extra'     => $datos_extra,
                );
                $sql="SELECT  id_factura, alias_tipopago, subtotal
                FROM pedidos_pago
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
                    'pedido'      => 1,
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
        $table_fact= 'pedidos';
        $form_data_fact = array(
        'impresa'        => '1',
        'fecha'          => $fecha_fact,
        'tipo_pago'      => $con_pago,
        'credito'        => $credito,
        'total_credito'  => $total_credito,
        'num_fact_impresa'=>$id_factura,
        );
        $where_clause="id_factura='$id_factura'";
        $actualizar = _update($table_fact, $form_data_fact, $where_clause);
    }

    $nreg_encode['id_pedido'] =$id_factura;

    //var_dump($nreg_encode);
    echo json_encode($nreg_encode);
}
function getCajaBySucursal($id_sucursal)
{
    // caja de transporte  tipo_caja=3
    $q="SELECT * FROM caja 
    WHERE  id_sucursal='$id_sucursal'";
    $res=_query($q);
    $row=_fetch_assoc($res);
    return $row;
}

function nitCliente($nitcli, $id_cliente)
{
    if (isset($nitcli) && $nitcli!="") {
        $tc= 'cliente';
        $fd = array(
    'nit' => $nitcli,
    );
        $wc="id_cliente='".$id_cliente."'";
        $upd = _update($tc, $fd, $wc);
    }
}
function updPedido()
{
}
function insertar()
{
    $precio_aut = 0;
    $clave="";
    $id_sucursal=$_SESSION["id_sucursal"];

    $fecha_movimiento =$_POST['fecha_movimiento'];
    $id_cliente=$_POST['id_cliente'];
    $id_factura=$_POST['id_factura'];
    $id_vendedor=$_POST['id_vendedor'];
    $cuantos = $_POST['cuantos'];
    $array_json=$_POST['json_arr'];
    $fecha=date("Y-m-d");
    $subtotal=$_POST['subtotal'];
    $sumas=$_POST['sumas'];
    $suma_gravada=$_POST['suma_gravada'];
    $iva= $_POST['iva'];
    $venta_exenta= $_POST['venta_exenta'];
    $total_menos_retencion=$_POST['total'];
    $total = $_POST['total'];
    $id_empleado=$_SESSION["id_usuario"];
    if ($id_vendedor == "") {
        $id_vendedor = $id_empleado;
    }
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
    $id_resolucion=0;
    //descripcion tipo de pago
    $descrip_pago = getTipoPago($tipo_pago);
    //datos de cliente
    $sql_cte="select * from cliente WHERE id_cliente=$id_cliente";
    $result_cte= _query($sql_cte);
    $nrows_cte=_num_rows($result_cte);
    $rows_cte=_fetch_array($result_cte);
    $dias_credito=$rows_cte['dias_credito'];
    _begin();
    $a=1;
    $b=1;
    $c=1;
    $z=1;
    $j = 1 ;
    $k = 1 ;
    $l = 1 ;
    $tipo_entrada_salida='';
    //correlativo documento
    $ult_ped=getCorrel($id_sucursal);
    if ($ult_ped>0) {
        $numero_doc=charfill("0", $ult_ped, 10)."_PED";
    }
    //actualizar nit cliente
    nitCliente($nitcli, $id_cliente);
    //fin actualizar nit cliente
    $total_credito = 0 ;
    if ($credito==1) {
        $saldo         = $total;
        $total_credito = $total;
    }
    $table_fact= 'pedidos';
    $form_data_fact = array(
      'id_cliente' => $id_cliente,
      'fecha' => $fecha_movimiento,
      'numero_doc' => $numero_doc,
      'subtotal' => $subtotal,
      'subt_bonifica'=>0,
      'sumas'=>$sumas,
      'suma_gravado'=>$suma_gravada,
      'iva' =>$iva,
      'retencion'=>0,
      'venta_exenta'=>$venta_exenta,
      'total_menos_retencion'=>$total_menos_retencion,
      'total' => $total,
      'id_usuario'=>$id_empleado,
      'id_empleado' => $id_vendedor,
      'id_sucursal' => $id_sucursal,
      'tipo' => $tipo_entrada_salida,
      'serie' => 1,
      'num_fact_impresa' => $numdoc,
      'hora' => $hora,
      'finalizada' => '1',
      'abono'=>0,
      'saldo' => 0,
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
        $b=0;
    }
    $cre=1;
    if ($credito==1) {
        $table="credito";
        $total=$total;
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
    if (!$insert_mov) {
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

            $cantidad=$fila['cantidad'];
            $id_presentacion=$fila['id_presentacion'];
            $cantidad_real = $cantidad*$unidades;
            $bonificacion_r= $bonificacion*$unidades;
            $exento=$fila['exento'];
            $precio_venta=$fila['precio'];

            $sql_costo=_fetch_array(_query("SELECT costo FROM presentacion_producto WHERE id_pp  = $id_presentacion"));
            $precio_compra=$sql_costo['costo'];
            $table_fact_det= 'pedidos_detalle';
            $data_fact_det = array(
            'id_factura' => $id_fact,
            'id_prod_serv' => $id_producto,
            'cantidad' => $cantidad_real,
            'bonificacion'=> $bonificacion_r,
            'precio_venta' => $precio_venta,
            'subtotal' => $subtotal,
            'subt_bonifica' => $subt_bonifica,
            'tipo_prod_serv' => $tipoprodserv,
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
            //$cantidad= $cantidad * $unidades ;
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
            $xdatos['ultimo']=$ult_ped;
            $xdatos['nombrecaja']=$nombrecaja;
            $xdatos['descrip_pago']=$descrip_pago;
            $xdatos['nuevo_stock']=$nuevo_stock;
        } else {
            _rollback(); // transaction rolls back
            $xdatos['typeinfo']='Error';
            $xdatos['msg']='Registro no pudo ser ingresado!'.$a."-".$b."-".$c."-".$x."-".$z."-".$k."-".$j."-".$l;
        }
    }
    echo json_encode($xdatos);
}


if (!isset($_REQUEST['process'])) {
    initial();
}
if (isset($_REQUEST['process'])) {
    switch ($_REQUEST['process']) {
    case 'insert': //usada
      insertar();
      break;
    case 'print_abono':
      print_abono();
      break;

    case 'validarNumdoc'://usada
        validarNumdoc();
        break;
    case 'clienteMetodoPago'://usada
        clienteMetodoPago();
        break;
    case 'imprimir_fact'://usada
      imprimir_fact();
      break;
  }
}

?>
