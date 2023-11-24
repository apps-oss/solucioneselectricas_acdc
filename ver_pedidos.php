<?php
include_once "_core.php";
include('num2letras.php');
//include ('facturacion_funcion_imprimir.php');
//include("escpos-php/Escpos.php");
function initial()
{
    $id_factura=$_REQUEST["id_factura"];
    $id_sucursal=$_REQUEST['id_sucursal'];
    $numero_docx=$_REQUEST['numero_doc'];
    //permiso del script
    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];
    $uri = $_SERVER['SCRIPT_NAME'];
    $filename=get_name_script($uri);
    $links=permission_usr($id_user, $filename);

    //$id_sucursal=$_SESSION['id_sucursal'];

    echo "<style type='text/css'>
    #inventable{
    	font-family: 'Open Sans';
    	 font-style: normal;
    	 font-size: small;
		font-weight: 400;
		src: local('Open Sans'), local('OpenSans'), url(fonts/apache/opensans/OpenSans-Regular.ttf) format('truetype'), url(fonts/apache/opensans/OpenSans.woff) format('woff');
    }
    .table thead tr > th.success{
		background-color: #428bca !important;
		color: white !important;
	}
	.table > tfoot > tr > .thick-line {
		border-top: 2px solid;
	}
	</style>";


    $sql="SELECT * FROM producto";
    $result=_query($sql);
    $count=_num_rows($result);

    $id_usuario=$_SESSION["id_usuario"];
    $id_sucursal=$_SESSION['id_sucursal'];
    //permiso del script
    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];
    $uri = $_SERVER['SCRIPT_NAME'];
    $filename=get_name_script($uri);
    $links=permission_usr($id_user, $filename);

    $sql_fact="SELECT p.*, cliente.nombre FROM pedidos AS p JOIN cliente
	ON p.id_cliente=cliente.id_cliente
	WHERE p.id_factura='$id_factura'
	AND p.id_sucursal='$id_sucursal'
	";
    $result_fact = _query($sql_fact);
    $count_fact = _num_rows($result_fact); ?>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Ver factura</h4>
</div>

<div class="modal-body">
		<div class="row" id="row1">
				<?php

                        if ($links!='NOT' || $admin=='1') {
                            ?>

						<?php
                        if ($count_fact > 0) {
                            for ($i = 0; $i < $count_fact; $i ++) {
                                $row = _fetch_array($result_fact, $i);
                                $cliente=$row['nombre'];
                                $factnum=$row['numero_doc'];
                                $fecha=$row['fecha'];
                            }
                        } ?>

						<!--div class="ibox "-->
						<div>
							<!--load datables estructure html-->
							<header><h4 class="text-danger">Factura No: &nbsp;<?php echo $factnum; ?></h4>
							<h4  class='text-navy'>Fecha:<?php echo $fecha; ?>&nbsp;
							Cliente:<?php echo $cliente; ?></h4>
							</header>
							<section>
								<div class="table-responsive m-t">
									<table class="table table-condensed table-striped" id="inventable">
									<thead class="thead-inverse">
										<tr>
										<th class='success'>Id</th>
										<th class='success'>Descripci&oacute;n</th>
										<th class='success'>Precio Vta.</th>
										<th class='success'>Cantidad</th>
										<!--th class='success'>Bonificación</th-->
										<th class='success'>Presentación</th>
										<th class='success' >Subtotal</th>
										</tr>
									</thead>
									<tbody>
										<?php
                                        $sql_fact_det="SELECT p.id_factura,pr.descripcion,
										presentacion_producto.descripcion AS preprodesc, presentacion_producto.unidad,
										presentacion.descripcion AS predesc,
										p.id_cliente, p.fecha,
										p.numero_doc, p.total,
										p.id_usuario, p.anulada,
										p.id_usuario, p.finalizada,
										p.id_sucursal,p.retencion,
										pd.id_factura_detalle,
										pd.id_prod_serv,pd.cantidad,
										pd.precio_venta, pd.subtotal,
										pd.tipo_prod_serv, p.tipo_documento,
										pd.bonificacion,
										p.sumas,p.iva,
										p.subt_bonifica
										FROM pedidos AS p
										JOIN pedidos_detalle AS pd  ON p.id_factura=pd.id_factura
										JOIN producto  AS pr ON pd.id_prod_serv=pr.id_producto
										JOIN presentacion_producto ON pd.id_presentacion=presentacion_producto.id_pp
										JOIN presentacion ON presentacion_producto.id_presentacion=presentacion.id_presentacion
										WHERE
										p.id_factura='$id_factura'
										AND p.id_sucursal='$id_sucursal'";

                            $result_fact_det=_query($sql_fact_det);
                            $count_fact_det=_num_rows($result_fact_det);
                            for ($i=0;$i<$count_fact_det;$i++) {
                                $row=_fetch_array($result_fact_det);
                                $id_factura=$row['id_factura'];
                                $id_producto=$row['id_prod_serv'];
                                $cantidad=$row['cantidad'];
                                $precio_venta=$row['precio_venta'];
                                $subtotal=$row['subtotal'];
                                $total=sprintf("%.2f", $row['total']);
                                $descprod=$row['descripcion'];
                                $preprodesc=$row['preprodesc'];
                                $predesc=$row['predesc'];
                                $unidad=$row['unidad'];
                                $tipo=$row['tipo_documento'];
                                $sumas=$row['sumas'];
                                $iva=$row['iva'];
                                $retencion=$row['retencion'];
                                echo "<tr>";
                                echo "<td>".$id_producto."</td>";
                                echo "<td>".$descprod."</td>";
                                echo "<td id='pv' class='text-right'>".$precio_venta."</td>";
                                echo "<td id='cant1' class='text-right'>".$cantidad/$unidad."</td>";
                                echo "<td id='cant1' class='text-right'>".$predesc." ".$preprodesc."</td>";
                                echo "<td id='subtot' class='text-right'>".number_format($subtotal, 2)."</td>";
                                echo "</tr>";
                            } ?>
									</tbody>
									<tfoot>
										<tr>
											<td class="thick-line"></td>
											<td class="thick-line"></td>
											<td class="thick-line"></td>
											<td class="thick-line"></td>
											<td class="thick-line text-right"><strong>TOTAL $:</strong></td>
											<td  class="thick-line text-right" id='total_dinero' ><strong><?php echo sprintf("%.2f", $total-$retencion); ?></strong></td>
										</tr>
									</tfoot>
								</table>
								<?php
                                $totalTexto =	getTotalTexto($total-$retencion);
                            echo "<input type='hidden' nombre='id_factura_ver' id='id_factura_ver' value='$id_factura'>";
                            echo "<div class='well m-t'  id='totaltexto'>".$totalTexto." </div>"; ?>
					</section>
				</div>
				<div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btnPrint">Imprimir</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Salir</button>
				</div>
		</div>
	</div>


<?php
//include_once ("footer.php");
//echo "<script src='js/funciones/genera_venta.js'></script>";
                        } //permiso del script
else {
    echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
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
    case 'consultar_stock':
        consultar_stock();
        break;
    }

    //}
}
?>
