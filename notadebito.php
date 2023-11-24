<?php
include_once "_core.php";

include('num2letras.php');
include('facturacion_funcion_imprimir.php');

function initial()
{
    $_PAGE = array();
    $_PAGE ['title'] = 'Nota de Débito';
    $_PAGE ['links'] = null;
    $_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
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
    // fin permiso del script

    $id_factura = $_REQUEST['id_factura'];
    $fecha_actual=date("Y-m-d");
    $sql=_fetch_array(_query("SELECT * FROM factura WHERE factura.id_factura=$id_factura"));
    $numero_doc      = $sql['numero_doc'];
    $numero_doc      = $sql['numero_doc'];
    $total_facturado = $sql['total'];
    list($numero, $tipo)=explode('_', $numero_doc);
    $numero = round($numero, 0);
    $id_cliente=$sql['id_cliente'];
    $sqlc=_fetch_array(_query("SELECT  nombre 
	FROM cliente WHERE id_cliente=$id_cliente"));
    $nombre_cliente=$sqlc['nombre'];

    $id_user=$_SESSION["id_usuario"];
    $sql_apertura = _query("SELECT * FROM apertura_caja WHERE vigente = 1 AND id_sucursal = '$id_sucursal' AND fecha='$fecha_actual' AND id_empleado = '$id_user'");
    $cuenta = _num_rows($sql_apertura);

    $turno_vigente=0;
    if ($cuenta>0) {
        $row_apertura = _fetch_array($sql_apertura);
        $id_apertura = $row_apertura["id_apertura"];
        $turno = $row_apertura["turno"];
        $caja = $row_apertura["caja"];
        $fecha_apertura = $row_apertura["fecha"];
        $hora_apertura = $row_apertura["hora"];
        $turno_vigente = $row_apertura["vigente"];
    } ?>

	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-2">
		</div>
	</div>
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox ">
					<?php
                    if ($links!='NOT' || $admin=='1') {
                        //if ($turno_vigente=='1' ){?>
					<div class="ibox-title">
						<h5>Nota de Crédito: <label class="text-success"><?php echo $numero_doc ?></label></h5>
						<input type="hidden" id="numero_doc" name="numero_doc" value=" <?php echo $numero_doc ?>">
						<input type="hidden" name="id_factura" id="id_factura" value="<?php echo $id_factura ?>">
						<input type="hidden" id="id_apertura" name="id_apertura" value="<?php echo $id_apertura ?>">
						<input type="hidden" id="caja" name="caja" value="<?php echo $caja ?>">
						<input type="hidden" id="turno" name="turno" value="<?php echo $turno ?>">
						<input type="hidden" id="total_facturado" name="total_facturado" value="<?php echo $total_facturado ?>">
					</div>
					<div class="ibox-content">
						<div class='row'>
								<div class="col-lg-12">
										<h5>Seleccione el tipo de nota</h5>
								</div>
								<label for="notacred1">Ajuste de precios</label>
								<div class="radio radio-inline i-checks ">
									<div class="iradio checked"><input type="radio" name="notacred" id="notacred1" checked></div>
								</div>
							<label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
								<label for="notacred2"> Agregar Producto </label>&nbsp;&nbsp;
								<div class="radio radio-inline i-checks">
										<div class="iradio checked "><input type="radio" name="notacred" id="notacred2"></div>
								</div>
						</div>
					</div>
					<hr>
					<div class="ibox-content">
						<div class="row" id='datos_encabezado'>
							<div class="col-lg-12">
								<label>Tipo de documento: <?php echo $tipo ?></label>  Total Facturado $ <?php echo$total_facturado ?>
								<br>
								<input type="hidden" id="tipo_doc" name="tipo_doc" value="<?php echo $tipo ?>">
								<label>Numero: <?php echo $numero ?></label>
								<br>
								<label>Cliente: <?php echo $nombre_cliente ?></label>
								<input type="hidden" id="id_cliente" name="id_cliente" value="<?php echo $id_cliente ?>">
							</div>
						</div>
						<div class="row" id='ajuste'>
							<?php formAjuste(); ?>
						</div>
						<div class="row" id='devolucion'>
						<?php formDevolucion($id_factura); ?>
						</div>

					</div>

					<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content modal-md">
								<div class="modal-header">
									<h4 class="modal-title" id="myModalLabel">Impresion</h4>
								</div>
								<div class="modal-body">
									<div class="wrapper wrapper-content  animated fadeInRight">
										<div class="row">
											<input type='hidden' name='id_factura_n' id='id_factura_n' value=''>
											<div class="col-md-6">
												<div class="form-group">
													<label><h5 class='text-navy'>Numero control Interno:</h5></label>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group" id='fact_num'></div>
											</div>
										</div>

										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label><h5 class='text-navy'>Facturado $:</h5></label>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<input type="text" id="facturado" name="facturado" value=0 class="form-control decimal" readonly>
												</div>
											</div>
										</div>

											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label><h5 class='text-navy'>Numero nota de credito:</h5></label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<input class="form_control" type="text" id="numero" name="numero" value="">
													</div>
												</div>
											</div>


									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-primary" id="btnPrintFact">Imprimir</button>
									<button type="button" class="btn btn-warning" id="btnEsc">Salir</button>
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
                        echo "<script src='js/funciones/notadebito.js'></script>";
                    } //permiso del script
    else {
        echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div><div></div>";
        include_once("footer.php");
    }
}

function insert_sucursal()
{
    //$id_sucursal=$_POST["id_sucursal"];
    $descripcion=$_POST["nombre"];
    $direccion=$_POST["direccion"];
    $casa=$_POST["casa"];
    $sql_result= _query("SELECT * FROM sucursal WHERE descripcion='$descripcion'");
    $numrows=_num_rows($sql_result);
    $table = 'sucursal';
    $form_data = array(
        'descripcion' => $descripcion,
        'direccion' => $direccion,
        'casa_matriz' => $casa
    );

    if ($numrows == 0) {
        $insertar = _insert($table, $form_data);

        if ($insertar) {
            $xdatos['typeinfo']='Success';
            $xdatos['msg']='Registro insertado con éxito !';
            $xdatos['process']='insert';
        } else {
            $xdatos['typeinfo']='Error';
            $xdatos['msg']='Error al insertar!';
            $xdatos['process']='none';
        }
    }

    echo json_encode($xdatos);
}
function formAjuste()
{
    ?>
	<div class="col-lg-12">
		<table id="tabla_ajuste" class=" table table-hover table-striped">
			<tr>
				<td class='text-success col-lg-1'>ID (No. línea)</td>
				<td class='text-success col-lg-2'>Concepto (máximo 59 caracteres por línea)</td>
				<td class='text-success col-lg-2'>Valor</td>
			</tr>
			<tr>
				<td class='text-success col-lg-1'>1</td>
				<td class='text-success col-lg-2'>
					<input type="text" placeholder="Concepto" class="form-control" id="concepto" name="concepto" value='Ajuste de Precios'>
				</td>
				<td class='text-success col-lg-2'>
					<input type='text'  class='form-control decimal' id='valor_ajuste' name='valor_ajuste' value='0.0' ></td>
				</td>
			</tr>
			<tr>
				<td class='text-success col-lg-1'>2</td>
				<td class='text-success col-lg-2'>
					<input type="text" placeholder="Concepto" class="form-control" id="concepto2" name="concepto2" value=''>
				</td>
				<td class='text-success col-lg-2'></td>
			</tr>
			<tr>
				<td class='text-success col-lg-1'>3</td>
				<td class='text-success col-lg-2'>
					<input type="text" placeholder="Concepto" class="form-control" id="concepto3" name="concepto3" value=''>
				</td>
				<td class='text-success col-lg-2'></td>
			</tr>
			<tr>
				<td class='text-success col-lg-1'>4</td>
				<td class='text-success col-lg-2'>
					<input type="text" placeholder="Concepto" class="form-control" id="concepto4" name="concepto4" value=''>
				</td>
				<td class='text-success col-lg-2'></td>
			</tr>
			<tr>
				<td class='text-success col-lg-1'>5</td>
				<td class='text-success col-lg-2'>
					<input type="text" placeholder="Concepto" class="form-control" id="concepto5" name="concepto5" value=''>
				</td>
				<td class='text-success col-lg-2'></td>
			</tr>
		</table>
	</div>
	<div class="col-lg-12">
		<button class="btn btn-primary" type="button" id="btnGuardarAjuste" name="btnGuardarAjuste">Guardar</button>
	</div>
<?php
}
function formDevolucion($id_factura)
{
    ?>
	<div class="col-lg-12">
		<table id="tabla" class=" table table-hover table-striped">
			<thead>
			<tr>
				<td class='text-success col-lg-1'>ID</td>
				<td class='text-success col-lg-2'>Producto</td>
				<td class='text-success col-lg-1'>Presentación</td>
				<td class='text-success col-lg-1'>Descripción</td>
				<td class='text-success col-lg-1'>Precio de venta</td>
				<td class='text-success col-lg-1'>Cant. vendida </td>
				<!--td class='text-success col-lg-1'> Cant Boni. </td-->
				<td class='text-success col-lg-1'>Dev. Anteriores</td>
				<td class='text-success col-lg-1'>Cant. a devolver</td>
				<!--td class='text-success col-lg-1'>Cant. Boni devol.</td-->
				<td class='text-success col-lg-1'>Subtotal a devolver</td>
			</tr>
			</thead>
			<tbody class='table' id="detalle">
			<?php
            //$q=getItemFact($id_factura);
            $q=det_dte($id_factura);
    while ($row=_fetch_array($q)) {
        $sqldev_a= _fetch_array(_query("SELECT SUM(notadeb_det.cant) as cant
						FROM notadeb_det WHERE notadeb_det.id_factura=$id_factura
						AND notadeb_det.id_producto=$row[id_prod_serv]
						AND notadeb_det.id_factura_detalle=$row[id_factura_detalle]"));
        $dev_ant=$sqldev_a['cant']; ?>
				<tr>
					<td class='text-success col-lg-1'><?php echo $row['id_prod_serv'] ?></td>
					<td class='text-success col-lg-2'><?php echo $row['descripcion'] ?>
						<input type="hidden" id="tipo_prod_serv" name="tipo_prod_serv" value="<?php echo $row['tipo_prod_serv'] ?>">
						<input type="hidden" id="unidades" name="unidades" value="<?php echo $row['unidad'] ?>">
						<input type="hidden" id="id_presentacion" name="id_presentacion" value="<?php echo $row['id_presentacion'] ?>">
						<input type="hidden" id="id_factura_detalle" name="id_factura_detalle" value="<?php echo $row['id_factura_detalle'] ?>">
					</td>
					<td class='text-success col-lg-1'><?php echo $row['descripcion_pr'] ?> </td>
					<td class='text-success col-lg-1'><?php echo $row['descripcion_p'] ?> </td>
					<td class='text-success col-lg-1'><?php echo $row['precio_venta'] ?></td>
					<td class='text-success col-lg-1'><?php echo $row['cantidad'] ?></td>
					<!--td class='text-success col-lg-1'></td-->
					<td class='text-success col-lg-1'><?php echo $dev_ant ?></td>
					<td class='text-success col-lg-1'> <input class="form-control int" type="text" id="cant" name="cant" value=""> </td>
					<!--td class='text-success col-lg-1'> <input class="form-control int" type="text" id="boni" name="boni" value=""> </td-->
					<td class='text-success col-lg-1'> <input readOnly class="form-control" type="text" id="subtotal" name="subtotal" value=""> </td>
				</tr>
				<?php
    } ?>
			</tbody>
		</table>
	</div>
	<div class="col-lg-12">
		<table class="table">
			<tr>
				<td class='text-success col-lg-10' colspan="4">Total</td>
				<td id="totcant" class='text-success col-lg-1'>0</td>
				<!--td id="totboni" class='text-success col-lg-1'>0</td-->
				<td id="montodev" class='text-success col-lg-1'>0</td>
			</tr>
		</table>
	</div>
	<div class="col-lg-12">
		<button class="btn btn-primary" type="button" id="btnGuardar" name="btnGuardar">Guardar</button>
	</div>
<?php
}


function act()
{
    $numero = $_POST['numero'];
    $id_factura = $_POST['id_factura'];
    $table='factura';
    $form_data = array(
        'num_fact_impresa' => $numero,
    );
    $where_clause="id_factura = '".$id_factura."'";
    $update = _update($table, $form_data, $where_clause);
    if ($update) {
        # code...
        $xdatos['ok']='ok';
    }

    echo json_encode($xdatos);
}
function imprimir_fact_x()
{
    $id_factura = $_REQUEST['id_factura'];
    $sql_fact="SELECT * FROM factura WHERE id_factura='$id_factura'";
    $result_fact=_query($sql_fact);
    $row_fact=_fetch_array($result_fact);
    $nrows_fact=_num_rows($result_fact);
    $id_sucursal=$_SESSION['id_sucursal'];
    //Valido el sistema operativo y lo devuelvo para saber a que puerto redireccionar
    $info = $_SERVER['HTTP_USER_AGENT'];
    if (strpos($info, 'Windows') == true) {
        $so_cliente='win';
    } else {
        $so_cliente='lin';
    }

    if ($nrows_fact>0) {
        $id_cliente=$row_fact['id_cliente'];
        $fecha=$row_fact['fecha'];
        $numero_doc=trim($row_fact['numero_doc']);
        $total=$row_fact['total'];
        $tipo_impresion=$row_fact['tipo_documento'];

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
                $direccion=$row["direccion"];
                $nit=$row["nit"];
                $dui=$row["dui"];
                $nrc=$row["nrc"];
                $nombreape=$nombre;
            }
        }

        if ($tipo_impresion=='COF') {
            $info_facturas=print_fact($id_factura, $nit, $nombreape, $direccion);
        }
        if ($tipo_impresion=='CCF') {
            $info_facturas=print_ccf($id_factura, $tipo_impresion, $nit, $nrc, $nombreape, $direccion);
        }
        if ($tipo_impresion=='ENV') {
            $info_facturas=print_ccf($id_factura, $tipo_impresion, $nit, $nrc, $nombreape, "");
        }
        /*
        if ($tipo_impresion=='NC'){
            $tipo_impresion='DEV';
            $info_facturas=print_ncr($id_factura,$tipo_impresion,$nombreape,$direccion);
        }*/

        if ($tipo_impresion=='ND') {
            $info_facturas=print_ncr2($id_factura);
        }

        //directorio de script impresion cliente
        $headers="";
        $footers="";
        if ($tipo_impresion=='TIK') {
            $info_facturas=print_ticket($id_factura, $tipo_impresion);
            $sql_pos="SELECT *  FROM sucursal  WHERE id_sucursal='$id_sucursal' ";
            $result_pos=_query($sql_pos);
            $row1=_fetch_array($result_pos);
            //$headers=$row1['descripcion']."|".Mayu($row1['direccion'])."|".$row1['giro']."|";
            $headers=""."|".""."|".""."|";
            $footers="GRACIAS POR SU COMPRA, VUELVA PRONTO......"."|";
        }

        $sql_dir_print="SELECT *  FROM config_dir WHERE id_sucursal='$id_sucursal'";
        $result_dir_print=_query($sql_dir_print);
        $row_dir_print=_fetch_array($result_dir_print);
        $dir_print=$row_dir_print['dir_print_script'];
        $shared_printer_win=$row_dir_print['shared_printer_matrix'];
        $shared_printer_pos=$row_dir_print['shared_printer_pos'];
        $nreg_encode['tipo_impresion'] =$tipo_impresion;
        $nreg_encode['shared_printer_win'] =$shared_printer_win;
        $nreg_encode['shared_printer_pos'] =$shared_printer_pos;
        $nreg_encode['dir_print'] =$dir_print;
        $nreg_encode['facturar'] =$info_facturas;
        $nreg_encode['sist_ope'] =$so_cliente;
        $nreg_encode['headers'] =$headers;
        $nreg_encode['footers'] =$footers;

        echo json_encode($nreg_encode);
    }
}
function imprimir_fact()
{
    $numero_doc = $_POST['numero_doc'];
    $id_factura= $_POST['num_doc_fact'];
    $id_sucursal=$_SESSION['id_sucursal'];
    $numero_factura_imprimir = $_POST['numero_factura_imprimir'];

    $tipo_impresion='DEV';
    //Valido el sistema operativo y lo devuelvo para saber a que puerto redireccionar
    $info = $_SERVER['HTTP_USER_AGENT'];
    if (strpos($info, 'Windows') == true) {
        $so_cliente='win';
    } else {
        $so_cliente='lin';
    }

    $sql_fact="SELECT * FROM factura WHERE id_factura='$id_factura'";
    $result_fact=_query($sql_fact);
    $row_fact=_fetch_array($result_fact);
    $nrows_fact=_num_rows($result_fact);
    if ($nrows_fact>0) {
        $fecha_movimiento=$row_fact['fecha'];
        $id_cliente=$row_fact['id_cliente'];
        $table_fact= 'factura';

        $form_data_fact = array(
            'finalizada' => '1',
            'impresa' => '1',
            'num_fact_impresa'=>$numero_factura_imprimir,
        );

        $where_clause="WHERE id_factura='$id_factura'";
        $actualizar = _update($table_fact, $form_data_fact, $where_clause);
    }

    $sql="SELECT * FROM cliente
	WHERE
	id_cliente=$id_cliente";

    $result=_query($sql);
    $count=_num_rows($result);
    if ($count > 0) {
        for ($i = 0; $i < $count; $i ++) {
            $row = _fetch_array($result);
            $id_cliente=$row["id_cliente"];
            $nombre=$row["nombre"];
            $direccion=$row["direccion"];
            $nit=$row["nit"];
            $dui=$row["dui"];
            $nrc=$row["nrc"];
            $nombreape=$nombre;
        }
    }

    $info_facturas=print_ncr2($id_factura);


    //directorio de script impresion cliente
    $headers="";
    $footers="";
    $sql_dir_print="SELECT *  FROM config_dir WHERE id_sucursal='$id_sucursal'";
    $result_dir_print=_query($sql_dir_print);
    $row_dir_print=_fetch_array($result_dir_print);
    $dir_print=$row_dir_print['dir_print_script'];
    $shared_printer_win=$row_dir_print['shared_printer_matrix'];
    $shared_printer_pos=$row_dir_print['shared_printer_pos'];
    $nreg_encode['tipo_impresion'] ='DEV';
    $nreg_encode['shared_printer_win'] =$shared_printer_win;
    $nreg_encode['shared_printer_pos'] =$shared_printer_pos;
    $nreg_encode['dir_print'] =$dir_print;
    $nreg_encode['facturar'] =$info_facturas;
    $nreg_encode['sist_ope'] =$so_cliente;
    $nreg_encode['headers'] =$headers;
    $nreg_encode['footers'] =$footers;

    echo json_encode($nreg_encode);
}
// nota de credito ajuste
function ajuste()
{
    $id_factura				= $_POST['id_factura'];
    $id_cliente				= $_POST['id_cliente'];
    $tipo					= $_POST['tipo'];
    $valor_ajuste			= $_POST['valor_ajuste'];
    $total_facturado	    = $_POST['total_facturado'];
    $concepto 				= $_POST['concepto']."\n";
    $concepto 				.= $_POST['concepto2']."\n";
    $concepto 				.= $_POST['concepto3']."\n";
    $concepto 				.= $_POST['concepto4']."\n";
    $concepto 				.= $_POST['concepto5'];

    $id_sucursal=$_SESSION['id_sucursal'];
    $id_usuario=$_SESSION['id_usuario'];
    //calcuclo de IVA
    $calc_iva = 0;
    if ($tipo=="CCF") {
        $calc_iva = calc_iva($valor_ajuste);
    }
    $total_s_iva =  $valor_ajuste - $calc_iva;
    $ver1=0;
    $ver2=0;
    $ver3=0;
    $ver4=0;
    $ver5=0;
    $ver6=0;

    $id_nd=0;

    _begin();

    $fecha=date("Y-m-d");
    $hora=date("H:i:s");

    /*insertar la devolucion*/
    $table="nota_debito";

    $form_data = array(
        'id_factura' => $id_factura,
        'cant' => 1,
        'monto'=> $valor_ajuste,
        'concepto' => $concepto,
        'fecha'=> $fecha,
        'hora'=>$hora,
        'tipo'=>0, //0 ajuste, 1=devolucion
        'total_iva'=>$calc_iva,
        'total_s_iva'=>$total_s_iva,
    );
    $insertar=_insert($table, $form_data);
    if ($insertar) {
        # code...
        $id_nd=_insert_id();
    } else {
        $ver1=1;
    }

    $correlative=0;
    $ult_dev=0;
    $tipo_documento="";

    if ($tipo=="CCF" || $tipo=="COF" || $tipo=="FAC") {
        # code...
        /*obtener correlativo y actualizarlo*/
        $sql="select * from correlativo where id_sucursal='$id_sucursal'";
        $result= _query($sql);
        $rows=_fetch_array($result);
        $ult_dev=$rows['nc'];
        $ult_dev=$ult_dev+1;

        $correlative=str_pad($ult_dev, 7, '0', STR_PAD_LEFT);
        $correlative.='_ND';
        $table='correlativo';
        $form_data = array(
            'nc' => $ult_dev,
        );
        $where_clause="id_sucursal='".$id_sucursal."'";
        $update=_update($table, $form_data, $where_clause);
        if (!$update) {
            $ver3=1;
        }
        $tipo_documento="NC";
    }

    $numero_doc=$correlative;

    $id_user=$_SESSION['id_usuario'];
    /*agregar la factura*/

    $id_factura_nueva=0;
    $table='factura';
    $form_data = array(
        'id_cliente' => $id_cliente,
        'fecha' => $fecha,
        'numero_doc' => $numero_doc,
        'subtotal' => $valor_ajuste -$calc_iva,
        'sumas'=>$valor_ajuste -$calc_iva,
        'iva' =>$calc_iva,
        'total_retencion'=>0,
        'total' => $valor_ajuste,
        'id_usuario'=>$id_user,
        'id_empleado' => $id_user,
        'id_sucursal' => $id_sucursal,
        'hora' => $hora,
        'finalizada' => '1',
        'abono'=>0,
        'saldo' => 0,
        'tipo_documento' => $tipo_documento,
        'tipo'=> 'NOTA DE CREDITO',
        'id_apertura' => 0,
        'id_apertura_pagada' => 0,
        'caja' =>0,
        'credito' => 0,
        'afecta' => $id_factura,
        'id_nd' =>	$id_nd,
    );

    $insertar=_insert($table, $form_data);
    if ($insertar) {
        $id_factura_nueva=_insert_id();
    } else {
        $ver4=1;
    }
    /*
    SELECT `id_factura_detalle`, `id_factura`, `id_prod_serv`, `cantidad`, `precio_venta`, `subtotal`, `descuento`,
    `id_empleado`, `tipo_prod_serv`, `id_sucursal`, `id_factura_dia`, `fecha`, `impresa_lote`, `hora`, `id_presentacion`, `tipo`, `concepto`
    */
    /*factura detalle*/
    $table='factura_detalle';
    $form_data = array(
            'id_factura' => $id_factura_nueva,
            'id_prod_serv' => -1,
            'cantidad' => 1,
            'precio_venta' => $valor_ajuste,
            'subtotal' => $valor_ajuste,
            'tipo_prod_serv' => "SERVICIO",
            'id_empleado' => $_SESSION['id_usuario'],
            'id_sucursal' => $id_sucursal,
            'fecha' => $fecha,
            'hora' => $hora,
            'id_presentacion'=> -1,
            'tipo' => 1,
        );
    $insertar=_insert($table, $form_data);
    if (!$insertar) {
        $ver5=1;
    } else {
        $id_fd = _insert_id();
    }

    $table='notadeb_det';
    $form_data = array(
            'id_nd' => $id_nd,
            'id_factura' => $id_factura,
            'id_producto' => -1,
            'cant' => 1,
            'monto' => $valor_ajuste,
            'id_factura_detalle'=>$id_fd,
        );
    $insertar=_insert($table, $form_data);
    if (!$insertar) {
        $ver2=1;
    }

    //actualizar abonos y creditos si existen
    $monto_a_devolver = $valor_ajuste;
    $sql_cr = _query("SELECT id_credito,total,abono,saldo FROM credito
			WHERE id_factura=$id_factura");
    $cr=0;
    $ver6=0;//si se ejecuto el abono al credito
    if (_num_rows($sql_cr)>0) {
        $total_descontar = $monto_a_devolver + $calc_iva ;
        $datos_cr = _fetch_array($sql_cr);
        $id_credito = $datos_cr["id_credito"];
        $total = $datos_cr["total"];
        $abono = $datos_cr["abono"];
        $saldo = $datos_cr["saldo"];
        if ($total_descontar<=$saldo) {
            $t1="abono_credito";
            $t2="credito";
            $wc=" id_credito='".$id_credito."'";
            $data_cr = array(
                     'id_credito' => $id_credito,
                     'abono' => $total_descontar,
                     'fecha' => $fecha,
                     'hora' => $hora,
                     'tipo_doc' => 'Otro(Nota de Crédito)',
                     'num_doc' => $id_nd,
                     'id_sucursal' => $id_sucursal,
                 );
            $insert_cr = _insert($t1, $data_cr);
            if ($insert_cr) {
                $id_abono_credito = _insert_id();
                $nuevosaldo=round(($saldo - $total_descontar), 2);
                $nuevo_val_abono=round(($abono + $total_descontar), 2);

                $data_ab = array(
                         'abono' => $nuevo_val_abono,
                         'saldo' => $nuevosaldo,
                     );
                $where_clause = "id_credito='" . $id_credito . "'";
                $upd_cr = _update($t2, $data_ab, $where_clause);
                if (!$upd_cr) {
                    $ver6=1;
                }
            } else {
                $cr=1;//credito
            }
        }
    }

    if ($ver1==0 && $ver2==0 && $ver3==0 && $ver4==0 && $ver5==0 && $ver6==0 &&$cr==0) {
        _commit();
        $xdatos['typeinfo']='Success';
        $xdatos['msg']='Registro insertado con éxito !';
        $xdatos['process']='insert';
        $xdatos['id_factura']=$id_factura_nueva;
        $xdatos['numdoc']=$correlative;
    } else {
        _rollback();
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Error al insertar:'.$ver1.$ver2.$ver3.$ver4.$ver5.$ver6.$cr;
        $xdatos['process']='none';
    }

    echo json_encode($xdatos);
}
function devolucion()
{
    $totcant=$_POST['totcant'];
    $monto_a_devolver=$_POST['montodev'];
    $id_factura=$_POST['id_factura'];
    $id_cliente=$_POST['id_cliente'];
    $array_json=$_POST['json_arr'];
    $tipo=$_POST['tipo'];
    $id_sucursal=$_SESSION['id_sucursal'];
    $id_usuario=$_SESSION['id_usuario'];
    //calcuclo de IVA

    //calcuclo de IVA
    $calc_iva = 0;
    if ($tipo=="CCF") {
        $calc_iva = calc_iva($monto_a_devolver);
    }
    $total_s_iva =  $monto_a_devolver- $calc_iva;
    $ver1=0;
    $ver2=0;
    $ver3=0;
    $ver4=0;
    $ver5=0;
    $ver6=0;

    $j = 1 ;
    $k = 1 ;
    $l = 1 ;
    $m = 1 ;
    $z = 1 ;

    $id_nd=0;

    _begin();

    $fecha=date("Y-m-d");
    $hora=date("H:i:s");


    $array = json_decode($array_json, true);

    /*insertar la devolucion*/
    $table="nota_debito";
    $form_data = array(
        'id_factura' => $id_factura,
        'cant' => 1,
        'monto'=> $monto_a_devolver,
        'concepto' => 'DEVOLUCION',
        'fecha'=> $fecha,
        'hora'=>$hora,
        'tipo'=>1, //0 ajuste, 1=devolucion
        'total_iva'=>$calc_iva,
        'total_s_iva'=>$total_s_iva,
    );
    $insertar=_insert($table, $form_data);
    if ($insertar) {
        $id_nd=_insert_id();
    } else {
        $ver1=1;
    }

    $correlative=0;
    $ult_dev=0;
    $tipo_documento="";

    if ($tipo=="CCF" || $tipo=="COF" || $tipo=="FAC") {
        /*obtener correlativo y actualizarlo*/
        $sql="select * from correlativo where id_sucursal='$id_sucursal'";
        $result= _query($sql);
        $rows=_fetch_array($result);
        $ult_dev=$rows['nc'];
        $ult_dev=$ult_dev+1;

        $correlative=str_pad($ult_dev, 7, '0', STR_PAD_LEFT);
        $correlative.='_ND';
        $table='correlativo';
        $form_data = array(
            'nc' => $ult_dev,
        );
        $where_clause="id_sucursal='".$id_sucursal."'";
        $update=_update($table, $form_data, $where_clause);
        if (!$update) {
            $ver3=1;
        }
        $tipo_documento="NCR";
    }

    $numero_doc=$correlative;
    $id_user=$_SESSION['id_usuario'];
    /*agregar la factura*/
    $id_factura_nueva=0;
    $table='factura';
    $form_data = array(
        'id_cliente' => $id_cliente,
        'fecha' => $fecha,
        'numero_doc' => $numero_doc,
        'subtotal' => $monto_a_devolver -$calc_iva,
        'sumas'=> $monto_a_devolver-$calc_iva,
        'iva' =>$calc_iva,
        'total_retencion'=>0,
        'total' =>  $monto_a_devolver,
        'id_usuario'=>$id_user,
        'id_empleado' => $id_user,
        'id_sucursal' => $id_sucursal,
        'hora' => $hora,
        'finalizada' => '1',
        'abono'=>0,
        'saldo' => 0,
        'tipo_documento' => $tipo_documento,
        'tipo'=> 'NOTA DE CREDITO',
        'id_apertura' => 0,
        'id_apertura_pagada' => 0,
        'caja' =>0,
        'credito' => 0,
        'afecta' => $id_factura,
        'id_nd' =>	$id_nd,
    );

    $insertar=_insert($table, $form_data);
    if ($insertar) {
        # code...
        $id_factura_nueva=_insert_id();
    } else {
        $ver4=1;
    }

    $table='movimiento_producto';
    $form_data = array(
    'id_sucursal' => $id_sucursal,
    'correlativo' => $numero_doc,
    'concepto' => "DEVOLUCION",
    'total' => $monto_a_devolver,
    'tipo' => 'ENTRADA',
    'proceso' => 'DEV',
    'referencia' => $numero_doc,
    'id_empleado' => $_SESSION['id_usuario'],
    'fecha' => $fecha,
    'hora' => $hora,
    'id_suc_origen' => $id_sucursal,
    'id_suc_destino' => $id_sucursal,
    'id_proveedor' => 0,
        'id_factura' => $id_factura_nueva,
  );
    $insertarM = _insert($table, $form_data);

    $id_movimiento=-1;
    if ($insertarM) {
        # code...
        $id_movimiento=_insert_id();
    } else {
        $ver6=1;
    }

    /*fcactura detalle*/
    foreach ($array as $fila) {
        $id_producto=$fila['id_producto'];
        $cant=$fila['cant'];
        //$boni=$fila['boni'];
        $monto=$fila['monto'];
        $unidades=$fila['unidades'];
        $id_fatura_detalle=$fila['id_factura_detalle'];
        $id_presentacion=$fila['id_presentacion'];
        $precio_venta=$fila['precio_venta'];
        $cant=$cant*$unidades;
        $tipo_prod_serv = $fila['tipo_prod_serv'];
        $tipo=0;
        if ($tipo_prod_serv=='SERVICIO') {
            $tipo=1;
        }
        $table='factura_detalle';
        $form_data = array(
            'id_factura' => $id_factura_nueva,
            'id_prod_serv' => $id_producto,
            'cantidad' => $cant,
            'precio_venta' => $precio_venta,
            'subtotal' => $monto,
            'tipo_prod_serv' => $tipo_prod_serv,
            'id_empleado' => $_SESSION['id_usuario'],
            'id_sucursal' => $id_sucursal,
            'fecha' => $fecha,
            'hora' => $hora,
            'id_presentacion'=>-1,
            'tipo' => $tipo,
        );
        $insertar=_insert($table, $form_data);
        if (!$insertar) {
            $ver5=1;
        } else {
            $id_fd = _insert_id();
        }
        $table='notadeb_det';
        $form_data = array(
                'id_nd' => $id_nd,
                'id_factura' => $id_factura,
                'id_producto' => $id_producto,
                'cant' => 1,
                'monto' => $monto,
                'id_factura_detalle'=>$id_fd,
            );
        $insertar=_insert($table, $form_data);
        if (!$insertar) {
            $ver2=1;
        }
        $precio_costo = 0;
        if ($tipo_prod_serv=='PRODUCTO') {
            $sql_costo = _query("SELECT costo FROM precio_producto
				WHERE id_producto=$id_producto");
            $datos_costo = _fetch_array($sql_costo);
            $precio_costo = $datos_costo["costo"];

            $table='notadeb_det';
            $form_data = array(
                'id_nd' => $id_nd,
                'id_factura' => $id_factura,
                'id_producto' => $id_producto,
                'cant' => $cant,
                'monto' => $monto,
                'id_factura_detalle'=>$id_fatura_detalle,
            );
            $insertar=_insert($table, $form_data);
            if (!$insertar) {
                $ver2=1;
            }
            /*meterlo al local sin asignarlo*/
            $orig=_fetch_array(_query("SELECT ubicacion.id_ubicacion FROM ubicacion
				WHERE ubicacion.bodega=0 AND ubicacion.id_sucursal=$id_sucursal"));
            $destino=$orig['id_ubicacion'];

            $sql_su="SELECT id_su, cantidad FROM stock_ubicacion
			WHERE id_producto='$id_producto' AND id_sucursal='$id_sucursal'
			AND id_ubicacion='$destino' AND id_estante=0 AND id_posicion=0";
            $stock_su=_query($sql_su);
            $nrow_su=_num_rows($stock_su);
            $id_su="";
            /*cantidad de una presentacion por la unidades que tiene*/
            $cantidad=$cant*$unidades;
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
                    'id_estante' => 0,
                    'id_posicion' => 0,
                );
                $table_su = "stock_ubicacion";
                $insert_su = _insert($table_su, $form_data_su);
                $id_su = _insert_id();
            }
            if (!$insert_su) {
                $m=0;
            }

            $sql2="SELECT stock FROM stock WHERE id_producto='$id_producto'
			AND id_sucursal='$id_sucursal'";
            $stock2=_query($sql2);
            $row2=_fetch_array($stock2);
            $nrow2=_num_rows($stock2);
            if ($nrow2>0) {
                $existencias=$row2['stock'];
            }

            $sql_lot = _query("SELECT MAX(numero) AS ultimo FROM lote
			WHERE id_producto='$id_producto'");
            $datos_lot = _fetch_array($sql_lot);
            $lote = $datos_lot["ultimo"]+1;
            $table1= 'movimiento_producto_detalle';
            $cant_total=$cantidad+$existencias;
            $form_data1 = array(
                'id_movimiento'=>$id_movimiento,
                'id_producto' => $id_producto,
                'cantidad' => $cantidad,
                'costo' => $precio_costo,
                'precio' => $precio_venta,
                'stock_anterior'=>$existencias,
                'stock_actual'=>$cant_total,
                'lote' => $lote,
                'id_presentacion' => $id_presentacion,
                'fecha' => $fecha,
                'hora' => $hora,
            );
            $insert_mov_det = _insert($table1, $form_data1);
            if (!$insert_mov_det) {
                $j = 0;
            }

            $table2= 'stock';
            $cant_total=$cantidad+$existencias;
            $form_data2 = array(
                'id_producto' => $id_producto,
                'stock' => $cant_total,
                'costo_unitario'=>round(($precio_costo/$unidades), 2),
                'precio_unitario'=>round(($precio_venta/$unidades), 2),
                'update_date'=>$fecha,
                'id_sucursal' => $id_sucursal
            );
            $where_clause="WHERE id_producto='$id_producto' and id_sucursal='$id_sucursal'";
            $insert_stock = _update($table2, $form_data2, $where_clause);
            if (!$insert_stock) {
                $k = 0;
            }
            $estado='VIGENTE';
            $sql_lote = _query("SELECT MAX(lote.vencimiento) as vence
			FROM lote WHERE lote.id_producto='$id_producto'");
            $datos_lote = _fetch_array($sql_lote);
            $fecha_caduca = $datos_lote["vence"];
            $table_perece='lote';
            $form_data_perece = array(
            'id_producto' => $id_producto,
            'referencia' => $id_movimiento,
            'numero' => $lote,
            'fecha_entrada' => date("Y-m-d"),
            'vencimiento'=>$fecha_caduca,
            'precio' => $precio_costo,
            'cantidad' => $cantidad,
            'estado'=>$estado,
            'id_sucursal' => $_SESSION['id_sucursal'],
            'id_presentacion' => $id_presentacion,
            );
            $insert_lote = _insert($table_perece, $form_data_perece);
            if (!$insert_lote) {
                $l = 0;
            }
            $table="movimiento_stock_ubicacion";
            $form_data = array(
            'id_producto' => $id_producto,
            'id_origen' => 0,
            'id_destino'=> $id_su,
            'cantidad' => $cantidad,
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

    //actualizar abonos y creditos si existen
    $sql_cr = _query("SELECT id_credito,total,abono,saldo FROM credito
		WHERE id_factura=$id_factura");
    $cr=0;
    $ab_cr=0;//si se ejecuto el abono al credito
    if (_num_rows($sql_cr)>0) {
        //$total_descontar = $monto_a_devolver + $calc_iva ;
        //no se calcula iva para devolucion
        $total_descontar = $monto_a_devolver;
        $datos_cr = _fetch_array($sql_cr);
        $id_credito = $datos_cr["id_credito"];
        $total = $datos_cr["total"];
        $abono = $datos_cr["abono"];
        $saldo = $datos_cr["saldo"];
        if ($total_descontar <= $saldo) {
            $t1="abono_credito";
            $t2="credito";
            $wc=" id_credito='".$id_credito."'";
            $data_cr = array(
                 'id_credito' 	=> $id_credito,
                 'abono' 				=> $total_descontar,
                 'fecha' 				=> $fecha,
                 'hora' 				=> $hora,
                 'tipo_doc' 		=> 'Otro(Nota de Crédito)',
                 'num_doc' 			=> $id_nd,
                 'id_sucursal' 	=> $id_sucursal,
             );
            $insert_cr = _insert($t1, $data_cr);
            if ($insert_cr) {
                $id_abono_credito = _insert_id();
                $nuevosaldo = round(($saldo - $total_descontar), 2);
                $nuevo_val_abono = round(($abono + $total_descontar), 2);
                $data_ab = array(
                     'abono' => $nuevo_val_abono,
                     'saldo' => $nuevosaldo,
                 );
                $where_clause = "id_credito='" . $id_credito . "'";
                $upd_cr = _update($t2, $data_ab, $where_clause);
                if (!$upd_cr) {
                    $ab_cr=1;
                }
            } else {
                $cr=1;//credito
            }
        }
    }
    if ($ver1==0&&$ver2==0&&$ver3==0&&$ver4==0&&$ver5==0&&$ver6==0&&$j&&$k&&$l&&$m&&$z && $cr==0 && $ab_cr==0) {
        _commit();
        $xdatos['typeinfo']='Success';
        $xdatos['msg']='Registro insertado con éxito !';
        $xdatos['process']='insert';
        $xdatos['id_factura']=$id_factura_nueva;
        $xdatos['numdoc']=$correlative;
    } else {
        _rollback();
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Error al insertar :'.$ver1.$ver2.$ver3.$ver4.$ver6.$j.$k.$l.$m.$z;
        $xdatos['process']='none';
    }
    echo json_encode($xdatos);
}
function getItemFact($id_factura)
{
    $q="SELECT  p.id_producto, p.descripcion,p.cod_umedidaMH AS unidad_medida, fd.*,
    c.combustible
    FROM factura_detalle AS fd
    JOIN producto AS p ON fd.id_prod_serv=p.id_producto
    JOIN categoria AS c ON  c.id_categoria=p.id_categoria
    WHERE  fd.id_factura='$id_factura'
    AND fd.tipo_prod_serv='PRODUCTO'
    UNION ALL
    SELECT s.id_servicio AS id_producto, s.descripcion,
    '59' AS unidad_medida, fd.*, c.combustible
     FROM factura_detalle AS fd
    JOIN servicios AS s ON fd.id_prod_serv=s.id_servicio
    JOIN categoria AS c ON  c.id_categoria=s.id_categoria
    WHERE fd.id_factura='$id_factura'
    AND fd.tipo_prod_serv='SERVICIO'";
    $r=_query($q);
    return $r;
}
if (!isset($_POST['process'])) {
    initial();
} else {
    if (isset($_POST['process'])) {
        switch ($_POST['process']) {
    case 'insert':
        insert_sucursal();
        break;
    case 'devolver':
        devolucion();
        break;
    case 'act':
        act();
        break;
    case 'imprimir_fact':
        imprimir_fact();
        break;
    case 'ajuste':
        ajuste();
        break;
    }
    }
}
?>
