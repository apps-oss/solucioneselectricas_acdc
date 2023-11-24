<?php
include_once "_core.php";
include ('num2letras.php');
include ('facturacion_funcion_imprimir.php');
function initial() {
	$title= 'Corte de Caja Diario';
	include_once "_headers.php";
  $_PAGE ['title'] = $title;
	include_once "header.php";
	include_once "main_menu.php";
	date_default_timezone_set('America/El_Salvador');
	$fecha_actual=date("Y-m-d");
	$id_sucursal=$_SESSION['id_sucursal'];
	$sql_sucursal=_query("SELECT * FROM sucursal WHERE id_sucursal='$id_sucursal'");
	$array_sucursal=_fetch_array($sql_sucursal);
	$nombre_sucursal=$array_sucursal['descripcion'];
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$aper_id = $_REQUEST["aper_id"];
	//$sql_apertura = _query("SELECT * FROM apertura_caja WHERE id_apertura = '$aper_id' AND vigente = 1 AND id_sucursal = '$id_sucursal'");
	$sql_apertura = _query("SELECT * FROM apertura_caja WHERE id_apertura = '$aper_id'  AND id_sucursal = '$id_sucursal'");
	$cuenta = _num_rows($sql_apertura);
	$row_apertura = _fetch_array($sql_apertura);
	$id_apertura = $row_apertura["id_apertura"];
	$tike_inicia = $row_apertura["tiket_inicia"];
	$factura_inicia = $row_apertura["factura_inicia"];
	$credito_inicia = $row_apertura["credito_fiscal_inicia"];
	$empleado = $row_apertura["id_empleado"];
	$dev_inicia = $row_apertura["dev_inicia"];
	$turno = $row_apertura["turno"];
	$fecha_apertura = $row_apertura["fecha"];
	$hora_apertura = $row_apertura["hora"];
	$monto_apertura = $row_apertura["monto_apertura"];
	$monto_ch = $row_apertura["monto_ch"];
	$caja = $row_apertura["caja"];
	$hora_actual = date('H:i:s');
	//datos caja //
	$rc = getDatosCaja($caja);
	$tipo_caja = $rc["tipo_caja"];
	$nombre_caja = $rc["nombre"];
	/////////////////////////////////////////Correlativo//////////////////////////////////////////////////////////
	$n_tiket = 0;
	$n_factura = 0;
	$n_credito_fiscal = 0;
	$n_dev = 0;
	$sql_monto_dev=_fetch_array(_query("SELECT SUM(factura.total) AS total_devoluciones FROM factura
	JOIN factura AS f ON f.id_factura=factura.afecta
	WHERE factura.tipo_documento ='DEV' AND factura.id_apertura_pagada=$aper_id"));
	$monto_dev=$sql_monto_dev['total_devoluciones'];
	$sql_monto_dev=_fetch_array(_query("SELECT SUM(factura.total) AS total_devoluciones FROM factura
	JOIN factura AS f ON f.id_factura=factura.afecta
	WHERE factura.tipo_documento ='NC' AND factura.id_apertura_pagada=$aper_id"));
	$monto_nc=$sql_monto_dev['total_devoluciones'];
	$sql_monto_dev=_fetch_array(_query("SELECT SUM(factura.retencion) AS total_retencion FROM factura
	WHERE id_apertura_pagada=$aper_id AND credito=0"));
	$monto_retencion=$sql_monto_dev['total_retencion'];
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$sql_caja = _query("SELECT * FROM mov_caja WHERE fecha = '$fecha_apertura' AND id_apertura = '$id_apertura'
		AND hora BETWEEN '$hora_apertura' AND '$hora_actual' AND id_sucursal = '$id_sucursal'");
	$cuenta_caja = _num_rows($sql_caja);
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$total_tike_npago = 0;
	$total_factura_npago = 0;
	$total_credito_fiscal_npago = 0;
	$sql_pendiente = _query("SELECT * FROM factura WHERE fecha = '$fecha_actual'
		AND id_sucursal = '$id_sucursal' AND anulada = 0 AND credito != '0' AND finalizada = 0 AND credito=0");
	$cuenta1 = _num_rows($sql_pendiente);
	if($cuenta1 > 0)
	{
		while ($row_pendiente = _fetch_array($sql_pendiente))
		{
			$id_factura = $row_pendiente["id_factura"];
			$anulada = $row_pendiente["anulada"];
			$subtotal = $row_pendiente["subtotal"];
			$suma = $row_pendiente["sumas"];
			$iva = $row_pendiente["iva"];
			$total = $row_pendiente["total"];
			$numero_doc = $row_pendiente["numero_doc"];
			$tipo_pago = $row_pendiente["tipo_pago"];
			$pagada = $row_pendiente["pagada"];
			$tipo_documento = $row_pendiente["tipo_documento"];
			if($tipo_documento == "TIK")
			{
				$total_tike_npago += $total;
			}
			else if($tipo_documento == "COF")
			{
				$total_factura_npago += $total;
			}
			else if($tipo_documento == "CCF")
			{
				$total_credito_fiscal_npago += $total;
			}
		}
	}
	/////////////////////////////////////////////////////////////////////////////
	$q= "SELECT MIN(numero_doc) as minimo, MAX(numero_doc) as maximo FROM factura
	WHERE fecha = '$fecha_apertura' AND id_apertura_pagada = '$id_apertura'
	AND credito=0 AND hora BETWEEN '$hora_apertura' AND '$hora_actual'
	AND numero_doc LIKE '%TIK%' AND id_sucursal = '$id_sucursal' AND anulada = 0
	UNION ALL SELECT MIN(CONVERT(num_fact_impresa,UNSIGNED INTEGER)) as minimo,
	MAX(CONVERT(num_fact_impresa,UNSIGNED INTEGER)) as maximo
	FROM factura WHERE fecha = '$fecha_apertura'  AND credito=0
	AND id_apertura_pagada = '$id_apertura'
	AND hora BETWEEN '$hora_apertura' AND '$hora_actual'
	AND numero_doc LIKE '%COF%' AND id_sucursal = '$id_sucursal' AND anulada = 0
	UNION ALL SELECT MIN(CONVERT(num_fact_impresa,UNSIGNED INTEGER))
	AS minimo, MAX(CONVERT(num_fact_impresa,UNSIGNED INTEGER))
	AS maximo FROM factura WHERE fecha = '$fecha_apertura' AND credito=0
	AND id_apertura_pagada = '$id_apertura'
	AND hora BETWEEN '$hora_apertura' AND '$hora_actual'
	AND numero_doc LIKE '%CCF%' AND id_sucursal = '$id_sucursal' AND anulada = 0";
	$sql_min_max = _query($q);
	$cuenta_min_max = _num_rows($sql_min_max);

	$tike_min = 0;
	$tike_max = 0;
	$factura_min = 0;
	$factura_max = 0;
	$credito_fiscal_min = 0;
	$credito_fiscal_max = 0;
	$dev_min = 0;
	$dev_max = 0;
	$res_min = 0;
	$res_max = 0;

	if($cuenta_min_max)
	{
		$i = 1;
		while ($row_min_max = _fetch_array($sql_min_max))
		{
			if($i == 1)
			{
				$tike_min = $row_min_max["minimo"];
				$tike_max = $row_min_max["maximo"];
				if($tike_min != "" && $tike_max != "")
				{
					list($minimo_num,$ads) = explode("_", $tike_min);
					list($maximo_num,$ads) = explode("_", $tike_max);
				}
				if($tike_min > 0)
				{
					$tike_min = $minimo_num;
				}
				else
				{
					$tike_min = 0;
				}

				if($tike_max > 0)
				{
					$tike_max = $maximo_num;
				}
				else
				{
					$tike_max = 0;
				}
			}
			if($i == 2)
			{
				$factura_min = $row_min_max["minimo"];
				$factura_max = $row_min_max["maximo"];
				if($factura_max != "" && $factura_min != "")
				{
					$minimo_num = $factura_min;
					$maximo_num= $factura_max;
				}
				if($factura_min != "")
				{
					$factura_min = $minimo_num;
				}
				else
				{
					$factura_min = 0;
				}

				if($factura_max != "")
				{
					$factura_max = $maximo_num;
				}
				else
				{
					$factura_max = 0;
				}
			}
			if($i == 3)
			{
				$credito_fiscal_min = $row_min_max["minimo"];
				$credito_fiscal_max = $row_min_max["maximo"];
				if($credito_fiscal_min != "" && $credito_fiscal_max != 0)
				{
					$minimo_num = $credito_fiscal_min;
					$maximo_num = $credito_fiscal_max;
				}
				if($credito_fiscal_min != "")
				{
					$credito_fiscal_min = $minimo_num;
				}
				else
				{
					$credito_fiscal_min = 0;
				}

				if($credito_fiscal_max != "")
				{
					$credito_fiscal_max = $maximo_num;
				}
				else
				{
					$credito_fiscal_max = 0;
				}
			}
			$i += 1;
		}
	}
	$total_entrada_caja = 0;
	$total_salida_caja = 0;
	if($cuenta_caja > 0)
	{
		while ($row_caja = _fetch_array($sql_caja))
		{
			$monto = $row_caja["valor"];
			$entrada = $row_caja["entrada"];
			$salida = $row_caja["salida"];
			$numero_doc = $row_caja['numero_doc'];
			$idtransace = $row_caja['idtransace'];
			if($numero_doc == "")
			{
				if($entrada == 1 && $salida == 0)
				{
					$total_entrada_caja += $monto;
				}
				else if($salida == 1 && $entrada == 0)
				{
					$total_salida_caja += $monto;
				}
			}

		}
	}
	$total_tike_2 = 0;
	$total_factura_2 = 0;
	$total_credito_fiscal_2 = 0;
	$total_contado_2 = 0;
	$total_transferencia_2 = 0;
	$total_cheque_2 = 0;
	$total_tarjeta_2 = 0;
	$total_vale_2 = 0;
	$total_credito_2 = 0;
	$t_tike_2 = 0;
	$t_factuta_2 = 0;
	$t_credito_2 = 0;
	$total_consumo_interno_2 = 0;

	$q="SELECT * FROM factura WHERE fecha = '$fecha_apertura'
	AND id_sucursal = '$id_sucursal' AND anulada = 0
	AND finalizada = 1 AND id_apertura_pagada ='$id_apertura' ";
	$sql_corte_caja = _query($q);
	$cuenta_caja = _num_rows($sql_corte_caja);
	if($cuenta_caja > 0)
	{
		while ($row_corte = _fetch_array($sql_corte_caja))
		{
			$id_factura = $row_corte["id_factura"];
			$anulada = $row_corte["anulada"];
			$subtotal = $row_corte["subtotal"];
			$suma = $row_corte["sumas"];
			$iva = $row_corte["iva"];
			$total = $row_corte["total"];
			$numero_doc = $row_corte["numero_doc"];
			$tipo_pago = $row_corte["tipo_pago"];
			$pagada = $row_corte["finalizada"];
			$tipo_documento = $row_corte["tipo_documento"];

			//verificar el tipo de pago
			$q2="SELECT  id_factura, alias_tipopago, subtotal, total_facturado, datos_extra
			FROM factura_pago WHERE id_factura='$id_factura'" ;
			$res = _query($q2);
			$subtotal_fp = 0;
			while ($row_pago = _fetch_array($res))
			{
			$tipo_pago = $row_pago['alias_tipopago'];
			$subtotal_fp  = $row_pago['subtotal'];

			if($tipo_documento == 'TIK')
			{
				$total_tike_2 += $subtotal_fp;
				if($tipo_pago == "CON")
				{
					$total_contado_2 += $subtotal_fp;
				}
				if($tipo_pago == "COI")
				{
					$total_consumo_interno_2 += $subtotal_fp;
				}
				if($tipo_pago == "CRE")
			  {
				  $total_credito_2 += $subtotal_fp;
			  }
				 if($tipo_pago == "TAR")
				{
					$total_tarjeta_2  += $subtotal_fp;
				}
				 if($tipo_pago == "CHE")
				{
					$total_cheque_2 += $subtotal_fp;
				}
				if($tipo_pago == "VAL")
				{
					$total_vale_2 += $subtotal_fp;
				}
				$t_tike_2 += 1;
			}
			 if($tipo_documento == 'COF')
			{
				$total_factura_2 += $subtotal_fp;
				if($tipo_pago == "CON")
				{
					$total_contado_2 += $subtotal_fp;
				}
				if($tipo_pago == "COI")
				{
					$total_consumo_interno_2 += $subtotal_fp;
				}
				if($tipo_pago == "CRE")
				{
					$total_credito_2 += $subtotal_fp;
				}
				if($tipo_pago == "TAR")
				{
					$total_tarjeta_2  += $subtotal_fp;
				}
				if($tipo_pago == "CHE")
				{
					$total_cheque_2 += $subtotal_fp;
				}
				 if($tipo_pago == "VAL")
				{
					$total_vale_2 += $subtotal_fp;
				}
				$t_factuta_2 += 1;
			}
			 if($tipo_documento == 'CCF')
			{
				$total_credito_fiscal_2 += $subtotal_fp;
				if($tipo_pago == "CON")
				{
					$total_contado_2 += $subtotal_fp;
				}
				else if($tipo_pago == "COI")
				{
					$total_consumo_interno_2 += $subtotal_fp;
				}
				else if($tipo_pago == "CRE")
				{
					$total_credito_2 += $subtotal_fp;
				}
				else if($tipo_pago == "TAR")
				{
					$total_tarjeta_2  += $subtotal_fp;
				}
				else if($tipo_pago == "CHE")
				{
					$total_cheque_2 += $subtotal_fp;
				}
				else if($tipo_pago == "VAL")
				{
					$total_vale_2 += $subtotal_fp;
				}
				$t_credito_2 += 1;
			}
		}
	 }
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	//$total_devolucion = $total_dev_g + $total_dev_e;

	$total_nopagado = $total_tike_npago + $total_factura_npago + $total_credito_fiscal_npago;
	$total_corte_2 = $total_tike_2 + $total_factura_2 + $total_credito_fiscal_2 + $monto_apertura + $total_entrada_caja  + $monto_ch;
	$total_corte_2=round($total_corte_2,2);
	$total_caja_chica = $monto_ch + $total_entrada_caja - $total_salida_caja;
	$total_caja_chica=round($total_caja_chica,2);
	$total_forma_pago= $total_contado_2+$total_tarjeta_2+$total_cheque_2+ $total_credito_2+$total_vale_2 +$total_consumo_interno_2;

	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);
	//traer cajero
	$cajero = getCajero($id_user);
	//permiso del script
	?>
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox ">
					<?php
					//permiso del script
					if ($links!='NOT' || $admin=='1' ){
						$row_aper= getDatosApertura($aper_id,$id_sucursal);
						$id_empleado_apertura=$row_aper['id_empleado'];
						if ($id_empleado_apertura==$id_user || $admin=='1' ){
						?>
						<div class="ibox-title">
							<h5>Registrar Corte de Caja por Turno <?php echo $nombre_sucursal;?>&nbsp;<br>
							  Cajero: <?php echo $cajero;?> Caja <label class=' text -success'> <?=$nombre_caja;?></label></h5>
						</div>
						<div class="ibox-content">
							<form name="formulario" id="formulario">
								<div class="row">
									<div class="col-md-3">
										<input type="hidden" id='tipo_caja' name='tipo_caja' value ='<?= $tipo_caja;?>' >
										<div class="form-group has-info single-line">
											<label>Tipo de corte</label>
											<select id="tipo_corte" name="tipo_corte" class="form-control select2 ">
												<!--option value="C">Corte de caja</option-->
												<option value="X" selected>Corte X</option>
												<!--option value="Z">Corte Z</option-->
											</select>
										</div>
									</div>
									<?php
									$fecha_actual=date("Y-m-d");

									$nrows_tot_sist=0;
									$total_diario =0;
									echo "<div class='col-md-3'>";
									echo "<div class='form-group has-info single-line'><label>Fecha:</label>
									<input type='text' class='form-control datepicker' id='fecha' name='fecha' value='$fecha_actual' ></div>";
									echo "</div>";
									$url_lect="lectura_dia.php?id_apertura=$aper_id&fecha=$fecha_actual";
									?>
								</div>

								<div class="row">
									<div class="col-lg-8">
								<div class="row">
								<div class="col-lg-6">
									<div class='alert alert-success text-center' style='font-weight: bold;'>
										<label style='font-size: 15px;'>Total Documentos</label>
									</div>

									<table class="table table-border">
									<thead>
										<tr>
											<th>Tipo Documento</th>
											<th>N° Inicio</th>
											<th>N° Final</th>
											<th>Total Documentos</th>
											<th>Total Venta</th>
										</tr>
									</thead>
									<tbody id='tabla_doc'>
										<tr>
											<td>TIQUETE</td>
											<td><?php echo $tike_min;?></td>
											<td><?php echo $tike_max;?></td>
											<td><?php echo $t_tike_2;?></td>
											<td><?php echo number_format($total_tike_2,2,".",",");?></td>
										</tr>
										<tr>
											<td>FACTURA</td>
											<td><?php echo $factura_min;?></td>
											<td><?php echo $factura_max;?></td>
											<td><?php echo $t_factuta_2;?></td>
											<td><?php echo number_format($total_factura_2,2,".",",");?></td>
										</tr>
										<tr>
											<td>CREDITO FISCAL</td>
											<td><?php echo $credito_fiscal_min;?></td>
											<td><?php echo $credito_fiscal_max;?></td>
											<td><?php echo $t_credito_2;?></td>
											<td><?php echo number_format($total_credito_fiscal_2,2,".",",");?></td>
										</tr>
										<tr>
											<td colspan="4">MONTO APERTURA</td>
											<td><label id="id_total1"><?php echo number_format($monto_apertura,2,".",",");?></label></td>
										</tr>
										<tr>
											<td colspan="4">MONTO CAJA CHICA</td>
											<td><label id="id_total12"><?php echo number_format($monto_ch,2,".",",");?></label></td>
										</tr>
										<tr>
											<td colspan="4">(-RETENCION)</td>
											<td><label id="id_totalre"><?php echo number_format($monto_retencion,2,".",",");?></label></td>
										</tr>
										<tr>
											<td colspan="4">TOTAL</td>
											<td><label id="id_total"><?php echo number_format($total_corte_2,2,".",","); ?></label></td>
										</tr>
									</tbody>
								</table>
								</div>

								<div class="col-lg-6" id="tipo_pagos">
									<div class='alert alert-success text-center' style='font-weight: bold;'>
										<label style='font-size: 15px;'>Total por Tipo de Pago</label>
									</div>
									<table class="table table-border" id="table_pagos">
									<thead>
										<tr>
											<th class="col-md-11">Tipo de Pago</th>
											<th class="col-md-1">Total</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>TOTAL EFECTIVO</td>
											<td><?php echo sprintf("%.2f", $total_contado_2);?></td>
										</tr>
										<tr>
											<td>TOTAL CREDITO</td>
											<td><?php echo sprintf("%.2f",$total_credito_2);?></td>
										</tr>
										<tr>
											<td>TOTAL TARJETA DEBITO/CREDITO</td>
											<td><?php echo sprintf("%.2f",$total_tarjeta_2);?></td>
										</tr>
										<tr>
											<td>TOTAL CHEQUE</td>
											<td><?php echo sprintf("%.2f",$total_cheque_2);?></td>
										</tr>
										<tr hidden>
											<td>TOTAL CUPONES</td>
											<td><?php echo sprintf("%.2f",$total_vale_2);?></td>
										</tr>

											<tr>
											<td>TOTAL TODOS LOS TIPOS DE PAGO </td>
											<td><?php echo sprintf("%.2f",$total_forma_pago);?></td>
										</tr>

									</tbody>
								</table>
								</div>
								</div>
								<div class='row'>
								<div hidden class="col-lg-6" id="caja_mov">
									<div class='alert alert-success text-center' style='font-weight: bold;'>
										<label style='font-size: 15px;'>Total Movimientos de Caja</label>
									</div>

								<table class="table table-border" id="table_mov">
									<thead>
										<tr>
											<th class="col-md-11">Tipo Movimiento</th>
											<th class="col-md-1">Total</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>ENTRADAS</td>
											<td><?php echo $total_entrada_caja;?></td>
										</tr>
										<tr>
											<td>SALIDAS</td>
											<td><?php echo $total_salida_caja;?></td>
										</tr>
									</tbody>
								</table>
								</div>


								<!--//////////////////////////////////////////////////////////////////////////////////////////-->
								<div class="col-lg-6" id="caja_cobro">
									<div class='alert alert-success text-center' style='font-weight: bold;'>
										<label style='font-size: 15px;'>Total Cobros de credito</label>
									</div>

								<table class="table table-border" id="table_mov">
									<thead>
										<tr>
											<th class="col-md-11">CLIENTE</th>
											<th class="col-md-1">MONTO</th>
										</tr>
									</thead>
									<tbody>
										<?php
										  	$sql_cuenta = _query("SELECT mc.*, f.numero_doc, f.id_cliente, f.nombre AS nombre_f, c.nombre AS nombre_c
													FROM mov_caja AS mc
													JOIN factura AS f ON mc.numero_doc = f.numero_doc
													JOIN cliente AS c ON f.id_cliente = c.id_cliente
													WHERE mc.fecha = '$fecha_apertura' AND mc.id_apertura = '$id_apertura' AND mc.hora BETWEEN '$hora_apertura' AND '$hora_actual' AND mc.id_sucursal = '$id_sucursal' AND mc.numero_doc != ''");
												$cuenta_cuenta = _num_rows($sql_cuenta);
												$total_cobros = 0;
												if($cuenta_cuenta > 0)
												{
													while ($row_cuenta = _fetch_array($sql_cuenta))
													{
														$monto = $row_cuenta["valor"];
														$entrada = $row_cuenta["entrada"];
														$salida = $row_cuenta["salida"];
														$numero_doc = $row_cuenta['numero_doc'];
														$idtransace = $row_cuenta['idtransace'];
														$nombre_c = $row_cuenta['nombre_c'];
														$nombre_f = $row_cuenta['nombre_f'];

														if($nombre_c == "")
														{
															$nombre_text = $nombre_f;
														}
														else
														{
																$nombre_text = $nombre_c;
														}
														echo "<tr>
																		<td>".$nombre_text."</td>
																		<td>$".number_format($monto, 2,'.',',')."</td>
																	</tr>";
														$total_cobros += $monto;
													}
													echo "<tr>
																	<td>TOTAL</td>
																	<td>$".number_format($total_cobros, 2,'.',',')."</td>
																</tr>";
												}
										?>
									</tbody>
								</table>
								</div>
								</div>
									<div class="row" id="caja_dev">
									<div class="col-lg-6">
										<div class='alert alert-success text-center' style='font-weight: bold;'>
											<label style='font-size: 15px;'>Total Devoluciones</label>
										</div>

									<table class="table table-border" id="table_dev" >
										<thead>
											<tr>
												<th>N°</th>
												<th>N° Documento</th>
												<th>Documento Afecta</th>
												<th>N° Afecta</th>
												<th>Total</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$sql_devoluciones=_query("SELECT factura.numero_doc,factura.total,
												f.tipo_documento,f.numero_doc as doc
												FROM factura JOIN factura AS f ON f.id_factura=factura.afecta
												WHERE factura.tipo_documento ='DEV' AND factura.id_apertura_pagada=$aper_id");
											$i=1;
											while ($row_de=_fetch_array($sql_devoluciones)) {
												# code...
												list($doca,$sa)=explode("_",$row_de['numero_doc']);

												list($docb,$sb)=explode("_",$row_de['doc']);

												echo "
												<tr>
													<td>$i</td>
													<td>$doca</td>
													<td>".$row_de['tipo_documento']."</td>
													<td>$docb</td>
													<td class='text-right'>".number_format($row_de['total'],2,".","")."</td>
												</tr>
												";
												$i++;
											}
											?>
											<tr>
												<td colspan="4">TOTAL</td>
												<td class="text-right"><label id="id_total_dev"><?php echo number_format($monto_dev,2,".","");?></label></td>
											</tr>
										</tbody>
									</table>
									</div>
									<div class="col-lg-6" id="caja_nc" >
										<div class='alert alert-success text-center' style='font-weight: bold;'>
											<label style='font-size: 15px;'>Total Notas de Credito</label>
										</div>

									<table class="table table-border" id="table_nc" >
										<thead>
											<tr>
												<th>N°</th>
												<th>N° Documento</th>
												<th>Documento Afecta</th>
												<th>N° Afecta</th>
												<th>Total</th>
											</tr>
										</thead>
										<tbody>

											<?php
											$sql_devoluciones=_query("SELECT factura.numero_doc,factura.total,
												f.tipo_documento,f.num_fact_impresa as doc
												 FROM factura JOIN factura AS f ON f.id_factura=factura.afecta
												 WHERE factura.tipo_documento ='NC' AND factura.id_apertura_pagada=$aper_id");
											$i=1;
											while ($row_de=_fetch_array($sql_devoluciones)) {
												list($doca,$sa)=explode("_",$row_de['numero_doc']);
												$docb=$row_de['doc'];
												echo "
												<tr>
													<td>$i</td>
													<td>$doca</td>
													<td>".$row_de['tipo_documento']."</td>
													<td>$docb</td>
													<td class='text-right'>".number_format($row_de['total'],2,".","")."</td>
												</tr>
												";
												$i++;
											}
											?>
											<tr>
												<td colspan="4">TOTAL</td>
												<td class="text-right"><label id="id_total_nc"><?php echo number_format($monto_nc,2,".","");?></label></td>
											</tr>
										</tbody>
									</table>
									</div>
								</div>
							</div>
							<div class='col-lg-4' style='marginleft:10px'>
							<div class='row'>
								<div class='col-lg-3'></div>
								<div class='col-lg-6' id='botonera' style='marginleft:10px'>
								<?php
								$url2="corte_caja_arqueo_pdf.php?id_apertura=$id_apertura" ;
								$url1="reporte_lecturas_pdf.php?id_apertura=$id_apertura";
								$url_lub="lectura_lub_dia.php?id_apertura=$id_apertura";
								$url_lub2="reporte_conteo_acelub_dia_pdf.php?id_apertura=$id_apertura";

								?>
								<div class='row' style='margin-bottom:3px'>
								<div class='row' style='margin-bottom:3px'>
									<button type="button" id="btnArqueo" name="btnArqueo"
									class="btn btn-md btn-primary pull-right btn-block">
									<i class="fa-solid fa-cash-register"></i> Arqueo</button>
									</div>
								<div class='row' style='margin-bottom:3px'>
									<a class="btn btn-md btn-success pull-right btn-block"
										 href='<?= $url2;?>' 		id='btnPrintArq' target="_blank">
									<i class="fa-solid fa-print"></i> Imprimir Arqueo</a>
								</div>

								<div id='lecturass' hidden>

								<?php if 	($tipo_caja ==2){ ?>
								<div class='row' style='margin-bottom:3px'>
								<a class="btn btn-md btn-primary pull-right btn-block"
									 href='<?= $url_lect;?>' 		id='lectura' >
									<i class="fa-solid fa-gas-pump"> </i>&nbsp; Lectura Bombas</a>
								</div>

								<div class='row' style='margin-bottom:3px'>
									<a class="btn btn-md btn-success pull-right btn-block"
									 href='<?= $url1;?>' 		id='btnPrintLect' target="_blank">
									 <i class="fa-solid fa-print"></i> Imprimir Lectura</a>
								</div>
								<div class='row' style='margin-bottom:3px'>
								<a class="btn btn-md btn-primary pull-right btn-block"
									 href='<?= $url_lub;?>' 		id='lubricants' >
									<i class="fa-solid fa-gas-pump"> </i>&nbsp; Lubricantes</a>
								</div>
								<div class='row' style='margin-bottom:3px'>
									<a class="btn btn-md btn-success pull-right btn-block"
									 href='<?= $url_lub2;?>' 		id='btnPrintLub' target="_blank">
									 <i class="fa-solid fa-print"></i> Reporte Lubricantes</a>
								</div>

							<?php }?>
							</div>
								<div class='row' style='margin-bottom:3px'>

										<button type="submit"  id="submit1" name="submit1"
										class="btn btn-md btn-primary pull-right btn-block">
									<i class="fa-solid fa-floppy-disk"></i> Guardar</button>
								</div>
								</div>
							</div>
							<div class='col-lg-3'></div>
							</div>
							<div class="row" id='totales_corte'  style='margin-top:10px' >
								<div class='alert alert-warning text-center' style='font-weight: bold;'>
									<label style='font-size: 15px;'>Totales </label>
								</div>
								<table class="table table-border tabla_corte" id='table_t'>
									<thead>
										<tr>
											<th class="col-lg-10">Descripción</th>
											<th class="col-lg-2">Total</th>
										</tr>
									</thead>
										<tbody id="table_data">
										<tr>
											<td>Total Arqueo </td>
											<td style="text-align: right"><input type="text" id="total_efectivo" name="total_efectivo" value="0.00"  class="decimal input_clear"></td>
										</tr>
										<tr>
											<td>Total Facturado + Apertura(Sistema)</td>
											<td style="text-align: right">
											<input type="text"  id="id_total_general" value='<?php echo number_format(($total_corte_2 - $total_salida_caja - $monto_dev - $monto_nc + $total_cobros),2,".","");?>' class="decimal input_clear">
											</td>
										</tr>
										<tr>
											<td>Diferencia</td>
											<td style="text-align:right">
													<input type="text" id="id_diferencia" value='<?php echo "-".number_format(($total_corte_2 - $total_salida_caja - $monto_dev - $monto_nc + $total_cobros),2,".","");?>' class="decimal input_clear">
											</td>
												</tr>
											<?php if 	($tipo_caja ==2){ ?>
											<tr>
													<td>Total $ Lectura de Bombas	</td>
													<td style="text-align: right">
														<input type="text" id="total_dinero_lectura" name="total_dinero_lectura" value="0.00"  class="decimal input_clear"></td>
											</tr>
											<tr>
											<td>Total Galones Lectura de Bombas	</td>
											<td style="text-align:right"><input type="text" id="total_galones" name="total_galones" value="0.00"  class="decimal input_clear"></td>
											</tr>
										<?php } ?>

									</tbody>
								</table>
								</div>
						</div>
		</div>
									<div class="row">
										<div class="col-md-8">
											<div class="form-group">
												<label>Observaciones </label><input type="text" id="observaciones" name="observaciones" placeholder="observaciones" value=""  class="form-control ">
											</div>
										</div>
									</div>
									<?php if 	($tipo_caja ==2){ ?>
										<div class="row">
										<div class="col-md-8">
											<div class="table">
												<center><h5>DEVOLUCIONES DE COMBUSTIBLE</h5></center>
											<table class="table" id='loadtable'>
											<thead class='thead1'>
												<tr>
													<th>Tanque</th>
													<th>Descripción</th>
													<th>Devolución</th>
												</tr>
											</thead>
										  <tbody  id="tank">
										<?php
										 $rt=getTanques($id_sucursal);
										 $numrt=_num_rows($rt);
										  if ($numrt>0){
											 for ($i=0;$i<$numrt;$i++){
												$rowt = _fetch_array($rt);  ?>
													<tr>
													<td><?php echo $rowt['tipo_combustible'] ?></td>
													<td><?php echo $rowt['descripcion'] ?></td>
													<td><input type='text' id='dev_dia'  class='form-control decimal'
														 name='dev_dia' value='' ></td>
												</tr>
												<?php
											 }
										 } ?>

									</tbody>
								</table>
									</div>
									</div>
									</div>
								<?php	} ?>
									<div>
										<input type="hidden" name="process" id="process" value="insert"><br>
										<!--
										<input type="hidden" name="lista_tike" id="lista_tike" value="<?php print_r($lista_tike);?>">
										<input type="hidden" name="lista_factura" id="lista_factura" value="<?php print_r($lista_factura);?>">
										<input type="hidden" name="lista_credito_fiscal" id="lista_credito_fiscal" value="<?php print_r($lista_credito_fiscal);?>">-->
										<input type="hidden" name="lista_dev" id="lista_dev" value="<?php print_r($lista_dev);?>">
										<input type="hidden" name="lista_nc" id="lista_nc" value="<?php print_r($lista_nc);?>">
										<input type="hidden" name="retencion" id="retencion" value="<?php echo $monto_retencion;?>">


										<input type="hidden" name="t_tike" id="t_tike" value="<?php echo $t_tike_2;?>">
										<input type="hidden" name="t_factuta" id="t_factuta" value="<?php echo $t_factuta_2;?>">
										<input type="hidden" name="t_credito" id="t_credito" value="<?php echo $t_credito_2;?>">
										<input type="hidden" name="t_dev" id="t_dev" value="<?php echo $t_dev;?>">
										<input type="hidden" name="t_nc" id="t_nc" value="<?php echo $t_nc;?>">
										<input type="hidden" name="t_res" id="t_res" value="<?php echo $t_res;?>">

										<input type="hidden" name="total_tike" id="total_tike" value="<?php echo $total_tike_2;?>">
										<input type="hidden" name="total_factura" id="total_factura" value="<?php echo $total_factura_2;?>">
										<input type="hidden" name="total_credito" id="total_credito" value="<?php echo $total_credito_fiscal_2;?>">
										<input type="hidden" name="total_dev" id="total_dev" value="<?php echo $total_dev;?>">
										<input type="hidden" name="total_nc" id="total_nc" value="<?php echo $total_nc;?>">

										<input type="hidden" name="fecha_actual" id="fecha_actual" value="<?php echo $fecha_actual;?>">
										<input type="hidden" name="hora_actual" id="hora_actual" value="<?php echo $hora_actual;?>">
										<input type="hidden" name="id_sucursal" id="id_sucursal" value="<?php echo $id_sucursal;?>">
										<input type="hidden" name="id_empleado" id="id_empleado" value="<?php echo $empleado;?>">
										<input type="hidden" name="turno" id="turno" value="<?php echo $turno;?>">
										<input type="hidden" name="id_apertura" id="id_apertura" value="<?php echo $id_apertura;?>">
										<input type="hidden" name="caja_apertura" id="caja_apertura" value="<?php echo $caja;?>">

										<input type="hidden" name="tike_min" id="tike_min" value="<?php echo $tike_min;?>">
										<input type="hidden" name="tike_max" id="tike_max" value="<?php echo $tike_max;?>">
										<input type="hidden" name="factura_min" id="factura_min" value="<?php echo $factura_min;?>">
										<input type="hidden" name="factura_max" id="factura_max" value="<?php echo $factura_max;?>">
										<input type="hidden" name="credito_fiscal_min" id="credito_fiscal_min" value="<?php echo $credito_fiscal_min;?>">
										<input type="hidden" name="credito_fiscal_max" id="credito_fiscal_max" value="<?php echo $credito_fiscal_max;?>">
										<input type="hidden" name="dev_min" id="dev_min" value="<?php echo $dev_min;?>">
										<input type="hidden" name="dev_max" id="dev_max" value="<?php echo $dev_max;?>">
										<input type="hidden" name="res_min" id="res_min" value="<?php echo $res_min;?>">
										<input type="hidden" name="res_max" id="res_max" value="<?php echo $res_max;?>">


										<input type="hidden" name="total_entrada" id="total_entrada" value="0">
										<input type="hidden" name="total_salida" id="total_salida" value="0">
										<input type="hidden" name="total_cobros" id="total_cobros" value="<?php echo $total_cobros;?>">

										<input type="hidden" name="monto_apertura" id="monto_apertura" value="<?php echo $monto_apertura;?>">
										<input type="hidden" name="aper_id" id="aper_id" value="<?php echo $aper_id;?>">
										<input type="hidden" id="total_corte" name="total_corte"
										value="<?php echo ($total_corte_2 - $total_salida_caja - $monto_dev - $monto_nc);?>" >
										<input type="hidden" id="diferencia" name="diferencia" value=""  >
										<input type='hidden' id='total_contado' name='total_contado' value='<?php echo $total_contado_2;?>'>
										<input type='hidden' id='total_tarjeta' name='total_tarjeta' value='<?php echo $total_tarjeta_2;?>'>
									</div>
								</form>
							</div>
							<div class='modales'>
							  <?php modalArqueo();?>
							</div>
						</div>
					</div>
				</div>
			</div>

			<?php
			include_once ("footer.php");
			echo '<script src="js/plugins/axios/axios.min.js"></script>';
			  echo "<script src='js/plugins/cellNavigate.js'></script>";
			echo "<script src='js/funciones/funciones_corte.js'></script>";
		}
		else {
			echo "<br><br><div class='alert alert-warning'>No tiene permiso para ver este corte de otro usuario.</div><div></div></div></div></div></div>";
			include_once ("footer.php");
		}
		} //permiso del script
		else {
			echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div><div></div></div></div></div></div>";
			include_once ("footer.php");
		}
	}

	function corte()
	{
		$fecha_corte = $_POST["fecha"];
		$total_efectivo = $_POST["total_efectivo"];
		$total_corte = $_POST["total_corte"];
		$diferencia = $_POST["diferencia"];
		$t_tike = $_POST["t_tike"];
		$t_factuta = $_POST["t_factuta"];
		$t_credito = $_POST["t_credito"];
		$t_dev = $_POST["t_dev"];
		$t_nc = $_POST["t_nc"];
		$t_res = $_POST["t_res"];
		$fecha_actual = $_POST["fecha_actual"];
		$hora_actual = $_POST["hora_actual"];
		$id_sucursal = $_POST["id_sucursal"];
		$id_empleado = $_POST["id_empleado"];
		$turno = $_POST["turno"];
		$id_apertura = $_POST["id_apertura"];
		$tike_min = $_POST["tike_min"];
		$tike_max = $_POST["tike_max"];
		$factura_min = $_POST["factura_min"];
		$factura_max = $_POST["factura_max"];
		$credito_fiscal_min = $_POST["credito_fiscal_min"];
		$credito_fiscal_max = $_POST["credito_fiscal_max"];
		$dev_min = $_POST["dev_min"];
		$dev_max = $_POST["dev_max"];
		$res_min = $_POST["res_min"];
		$res_max = $_POST["res_max"];
		$monto_apertura = $_POST["monto_apertura"];
		$tipo_corte = $_POST["tipo_corte"];

		$total_entrada = 0;
		$total_salida = 0;
		$monto_ch = 0;
		$lista_dev = $_POST["lista_dev"];
		$lista_nc = $_POST["lista_nc"];
		$total_contado = $_POST["total_contado"];
		$total_tarjeta = $_POST["total_tarjeta"];

		$caja = $_POST["caja_apertura"];
		$retencion=$_POST['retencion'];
		$sql_cajax = _query("SELECT correlativo_dispo,tipo_caja,nombre FROM caja WHERE id_caja = '$caja'");
		$rc = _fetch_array($sql_cajax);
		$correlativo_dispo = $rc["correlativo_dispo"];
		$tipo_caja = $rc["tipo_caja"];
		$nombre_caja = $rc["nombre"];
		$nn_tik = $correlativo_dispo + 1;
		//$tike = $total_tike_e + $total_tike_g;
		//$factura = $total_factura_e + $total_factura_g;
		//$credito = $total_credito_fiscal_e + $total_credito_fiscal_g;
		//$reserva = $total_reserva_g + $total_reserva_e;
		//$dev = $total_dev_e + $total_dev_g;
		$total_tike= $_POST["total_tike"];
		$total_factura = $_POST["total_factura"];
		$total_credito_fiscal = $_POST["total_credito"];

		$tabla = "controlcaja";
		$form_data = array(
			'fecha_corte' => $fecha_actual,
			'hora_corte' => $hora_actual,
			'id_empleado' => $id_empleado,
			'id_sucursal' => $id_sucursal,
			'id_apertura' => $id_apertura,
			'totalt' => $total_tike,
			'totalf' => $total_factura,
			'totalcf' => $total_credito_fiscal,
			'diferencia' => $diferencia,
			'totalgral' => $total_corte,
			'cashfinal' => $total_efectivo,
			'totalnot' => $t_tike,
			'totalnof' => $t_factuta,
			'totalnocf' => $t_credito,
			'turno' => $turno,
			'tinicio' => $tike_min,
			'tfinal' => $tike_max,
			'finicio' => $factura_min,
			'ffinal' => $factura_max,
			'cfinicio' => $credito_fiscal_min,
			'cffinal' => $credito_fiscal_max,
			'cashinicial' => $monto_apertura,
			'tipo_corte' => $tipo_corte,
			'vtaefectivo' => $total_contado,
			'tarjetas' => $total_tarjeta,
			'vales' => $total_salida,
			'ingresos' => $total_entrada,
			'totalnodev' => $t_dev,
			'rinicio' => $res_min,
			'rfinal' => $res_max,
			'totalnor' => $t_res,
			'monto_ch' => $monto_ch,
			'caja' => $caja,
			'retencion' => $retencion,
		);
		$id_cortex="";
		$sql_ = _query("SELECT * FROM controlcaja WHERE id_apertura = '$id_apertura' AND tipo_corte = 'Z'");
		$cuentax = _num_rows($sql_);
		if($cuentax == 0)
		{
			if($tipo_corte == "C")
			{
				$insertar = _insert($tabla, $form_data);
				$id_cortex= _insert_id();

				$sql_prods_corte = _query("SELECT DISTINCT id_producto FROM movimiento_producto_detalle WHERE id_producto IN (SELECT id_producto FROM producto WHERE producto.id_categoria IN (SELECT to_corte.id_categoria FROM to_corte )) AND movimiento_producto_detalle.fecha = '$fecha_corte'");

				if(_num_rows($sql_prods_corte)>0)
				{
					while ($row_pd=_fetch_array($sql_prods_corte)) {
						$tabl="to_corte_producto";
						$form_d = array(
							'id_corte' => $id_cortex,
							'id_producto' => $row_pd['id_producto'],
						);

						$head = _insert($tabl,$form_d);
						if ($head) {
							$id = _insert_id();

							$detail = _query("SELECT movimiento_producto_detalle.* FROM movimiento_producto_detalle WHERE movimiento_producto_detalle.fecha ='$fecha_corte' AND movimiento_producto_detalle.id_producto=$row_pd[id_producto]");
							while ($row_pde=_fetch_array($detail)) {

								$tab = "to_corte_producto_detalle";
								$form_da = array(
									'id_ref' => $id,
									'cantidad' => $row_pde['cantidad'],
									'stock_anterior' => $row_pde['stock_anterior'],
									'stock_actual' => $row_pde['stock_actual']
								);
								$head = _insert($tab,$form_da);
							}

						}

					}
				}

				$sql_devoluciones=_query("SELECT factura.numero_doc,factura.total,f.tipo_documento,f.numero_doc as doc FROM factura JOIN factura AS f ON f.id_factura=factura.afecta WHERE factura.tipo_documento ='DEV' AND factura.id_apertura_pagada=$id_apertura");
				$i=1;
				while ($row_de=_fetch_array($sql_devoluciones)) {
					# code...
					list($doca,$sa)=explode("_",$row_de['numero_doc']);

					list($docb,$sb)=explode("_",$row_de['doc']);

					$table_dev = "devoluciones_corte";
					$form_dev = array(
						'id_corte' => $id_cortex,
						'n_devolucion' => $doca,
						't_devolucion' => $row_de['total'],
						'afecta' => $docb,
						'tipo' => $row_de['tipo_documento'],
					);
					$inser_dev = _insert($table_dev, $form_dev);
					$i++;
				}
				$sql_devoluciones=_query("SELECT factura.numero_doc,factura.total,f.tipo_documento,f.num_fact_impresa as doc FROM factura JOIN factura AS f ON f.id_factura=factura.afecta WHERE factura.tipo_documento ='NC' AND factura.id_apertura_pagada=$id_apertura");
				$i=1;
				while ($row_de=_fetch_array($sql_devoluciones)) {
					# code...
					list($doca,$sa)=explode("_",$row_de['numero_doc']);
					$docb=$row_de['doc'];

					$table_dev = "devoluciones_corte";
					$form_dev = array(
						'id_corte' => $id_cortex,
						'n_devolucion' => $doca,
						't_devolucion' => $row_de['total'],
						'afecta' => $docb,
						'tipo' => $row_de['tipo_documento'],
					);
					$inser_dev = _insert($table_dev, $form_dev);
					$i++;
				}
				$table_apertura='apertura_caja';
				$form_up = array(
					'cortado'=> 1,
						'id_corte' => $id_cortex,
				);
				$where_apertura = "id_apertura='".$id_apertura."'";
				$up_apertura = _update($table_apertura, $form_up, $where_apertura);
			}
			else if($tipo_corte == "X")
			{
				/*$extra = array('tiket' => $nn_tik ,);
				$resultx = array_merge($form_data, $extra);*/
				$resultx = $form_data;
				$insertar = _insert($tabla, $resultx);
				$id_cortex = _insert_id();
				//$id_cortex= _insert_id();
				if($insertar)
				{
					/*$t = "caja";
					$ff = array('correlativo_dispo' => $nn_tik,);
					$wp = "id_caja='".$caja."'";
					$upd = _update($t,$ff,$wp);*/


					$sql_devoluciones=_query("SELECT factura.numero_doc,factura.total,f.tipo_documento,f.numero_doc as doc FROM factura JOIN factura AS f ON f.id_factura=factura.afecta WHERE factura.tipo_documento ='DEV' AND factura.id_apertura_pagada=$id_apertura");
					$i=1;
					while ($row_de=_fetch_array($sql_devoluciones)) {
						# code...
						list($doca,$sa)=explode("_",$row_de['numero_doc']);

						list($docb,$sb)=explode("_",$row_de['doc']);

						$table_dev = "devoluciones_corte";
						$form_dev = array(
							'id_corte' => $id_cortex,
							'n_devolucion' => $doca,
							't_devolucion' => $row_de['total'],
							'afecta' => $docb,
							'tipo' => $row_de['tipo_documento'],
						);
						$inser_dev = _insert($table_dev, $form_dev);
						$i++;
					}
					$sql_devoluciones=_query("SELECT factura.numero_doc,factura.total,f.tipo_documento,f.num_fact_impresa as doc FROM factura JOIN factura AS f ON f.id_factura=factura.afecta WHERE factura.tipo_documento ='NC' AND factura.id_apertura_pagada=$id_apertura");
					$i=1;
					while ($row_de=_fetch_array($sql_devoluciones)) {
						# code...
						list($doca,$sa)=explode("_",$row_de['numero_doc']);
						$docb=$row_de['doc'];

						$table_dev = "devoluciones_corte";
						$form_dev = array(
							'id_corte' => $id_cortex,
							'n_devolucion' => $doca,
							't_devolucion' => $row_de['total'],
							'afecta' => $docb,
							'tipo' => $row_de['tipo_documento'],
						);
						$inser_dev = _insert($table_dev, $form_dev);
						$i++;
					}
				}
				$table_apertura='apertura_caja';
				$form_up = array(
						'id_corte' => $id_cortex,
					'cortado'=> 1,
				);
				$where_apertura = "id_apertura='".$id_apertura."'";
				$up_apertura = _update($table_apertura, $form_up, $where_apertura);
			}
			else if($tipo_corte == "Z")
			{

				$resultx = $form_data;
				$table_apertura = "apertura_caja";
				$form_up = array(
					'vigente' => 0,
					'monto_vendido' => $total_efectivo,
					'cortado'=> 1,
						'id_corte' => $id_cortex,
				);
				$where_apertura = "id_apertura='".$id_apertura."'";
				$up_apertura = _update($table_apertura, $form_up, $where_apertura);
				if($up_apertura)
				{
					$tab = "detalle_apertura";
					$form_d = array(
						'vigente' => 0 , );
						$ww = "id_apertura='".$id_apertura."' AND turno='".$turno."'";
						$up_turno = _update($tab,$form_d, $ww);

						$insertar = _insert($tabla, $resultx);
						$id_cortex = _insert_id();
						if($insertar)
						{

							$q="SELECT factura.numero_doc,factura.total,f.tipo_documento,f.numero_doc as doc
							FROM factura JOIN factura AS f ON f.id_factura=factura.afecta
							WHERE factura.tipo_documento ='DEV' AND factura.id_apertura_pagada=$id_apertura";
							$sql_devoluciones=_query($q);
							$i=1;
							while ($row_de=_fetch_array($sql_devoluciones)) {
								# code...
								list($doca,$sa)=explode("_",$row_de['numero_doc']);

								list($docb,$sb)=explode("_",$row_de['doc']);

								$table_dev = "devoluciones_corte";
								$form_dev = array(
									'id_corte' => $id_cortex,
									'n_devolucion' => $doca,
									't_devolucion' => $row_de['total'],
									'afecta' => $docb,
									'tipo' => $row_de['tipo_documento'],
								);
								$inser_dev = _insert($table_dev, $form_dev);
								$i++;
							}

							$q2="SELECT factura.numero_doc,factura.total,f.tipo_documento,f.num_fact_impresa as doc
							FROM factura JOIN factura AS f ON f.id_factura=factura.afecta
							 WHERE factura.tipo_documento ='NC' AND factura.id_apertura_pagada=$id_apertura";
							$sql_devoluciones=_query($q2);
							$i=1;
							while ($row_de=_fetch_array($sql_devoluciones)) {
								# code...
								list($doca,$sa)=explode("_",$row_de['numero_doc']);
								$docb=$row_de['doc'];

								$table_dev = "devoluciones_corte";
								$form_dev = array(
									'id_corte' => $id_cortex,
									'n_devolucion' => $doca,
									't_devolucion' => $row_de['total'],
									'afecta' => $docb,
									'tipo' => $row_de['tipo_documento'],
								);
								$inser_dev = _insert($table_dev, $form_dev);
								$i++;
							}
						}
					}
				}

				if($insertar)
				{
					$xdatos['typeinfo']='Success';
					$xdatos['msg']='Corte guardado correctamente !'.$correlativo_dispo;
					$xdatos['process']='insert';
					$xdatos['id_corte']=$id_cortex;
				}
				else
				{
					$xdatos['typeinfo']='Error';
					$xdatos['msg']='Error al guardar el corte !'._error();
				}
			}
			else
			{
				$xdatos['typeinfo']='Error';
				$xdatos['msg']='Ya existe un corte con esta apertura!';
			}
			echo json_encode($xdatos);
		}

		function  imprimir(){
			$id_corte = $_POST["id_corte"];
			$id_sucursal=$_SESSION['id_sucursal'];
			//directorio de script impresion cliente
			$sql_dir_print="SELECT *  FROM config_dir WHERE id_sucursal='$id_sucursal'";
			//$sql_dir_print="SELECT * FROM config_dir WHERE id_sucursal=1 ";
			$result_dir_print=_query($sql_dir_print);
			$row0=_fetch_array($result_dir_print);
			$dir_print=$row0['dir_print_script'];
			$shared_printer_win=$row0['shared_printer_matrix'];
			$shared_printer_pos=$row0['shared_printer_pos'];

			$info_mov=print_corte($id_corte);
			$nreg_encode =$info_mov;
			//Valido el sistema operativo y lo devuelvo para saber a que puerto redireccionar
			$info = $_SERVER['HTTP_USER_AGENT'];
			if(strpos($info, 'Windows') == TRUE)
			$so_cliente='win';
			else
			$so_cliente='lin';
			$nreg_encode['shared_printer_win'] =$shared_printer_win;
			$nreg_encode['shared_printer_pos'] =$shared_printer_pos;
			$nreg_encode['dir_print'] =$dir_print;
			//$nreg_encode['movimiento'] =$info_mov;
			$nreg_encode['sist_ope'] =$so_cliente;
			echo json_encode($nreg_encode);
		}
		function cambio()
		{
			$tipo_corte = $_POST["tipo_corte"];
			$aper_id = $_POST["aper_id"];

			$sql_monto_dev=_fetch_array(_query("SELECT SUM(factura.total) AS total_devoluciones
			FROM factura JOIN factura AS f ON f.id_factura=factura.afecta
			WHERE factura.tipo_documento ='DEV' AND factura.id_apertura_pagada=$aper_id"));
			$monto_dev=$sql_monto_dev['total_devoluciones'];

			$sql_monto_dev=_fetch_array(_query("SELECT SUM(factura.total) AS total_devoluciones
			FROM factura JOIN factura AS f ON f.id_factura=factura.afecta
			WHERE factura.tipo_documento ='NC' AND factura.id_apertura_pagada=$aper_id"));
			$monto_nc=$sql_monto_dev['total_devoluciones'];

			$sql_monto_dev=_fetch_array(_query("SELECT SUM(factura.retencion) AS total_retencion
			FROM factura WHERE id_apertura_pagada=$aper_id AND credito=0"));
			$monto_retencion=$sql_monto_dev['total_retencion'];
			$monto_retencion=round($monto_retencion,2);

			date_default_timezone_set('America/El_Salvador');
			$fecha_actual=date("Y-m-d");
			$hora_actual = date('H:i:s');
			$id_sucursal=$_SESSION['id_sucursal'];
			$sql_apertura1 = _query("SELECT * FROM apertura_caja WHERE id_apertura = '$aper_id'
				AND vigente = 1 AND id_sucursal = '$id_sucursal'");
			$cuenta1 = _num_rows($sql_apertura1);
			$row_apertura1 = _fetch_array($sql_apertura1);
			$id_apertura = $row_apertura1["id_apertura"];
			$tike_inicia = $row_apertura1["tiket_inicia"];
			$factura_inicia = $row_apertura1["factura_inicia"];
			$credito_inicia = $row_apertura1["credito_fiscal_inicia"];
			$empleado = $row_apertura1["id_empleado"];
			$dev_inicia = $row_apertura1["dev_inicia"];
			$turno = $row_apertura1["turno"];
			$fecha_apertura = $row_apertura1["fecha"];
			$hora_apertura = $row_apertura1["hora"];
			$monto_apertura = $row_apertura1["monto_apertura"];
			$monto_ch = $row_apertura1["monto_ch"];
			$caja     = $row_apertura1["caja"];
			$tike_min = 0;
			$tike_max = 0;
			$factura_min = 0;
			$factura_max = 0;
			$credito_fiscal_min = 0;
			$credito_fiscal_max = 0;

			$t_tike = 0;
			$t_factuta = 0;
			$t_credito = 0;
			$t_dev = 0;
			$t_nc = 0;

			$total_tike = 0;
			$total_factura = 0;
			$total_credito_fiscal = 0;

			$total_contado = 0;
			$total_transferencia = 0;
			$total_cheque = 0;
			$total_tarjeta = 0;
			$total_vale = 0;
			$total_consumo_interno = 0 ;
			$total_credito =0;
			if($tipo_corte == "Z" || $tipo_corte == "X")
			{
				$sql_min_max = _query("SELECT MIN(numero_doc) as minimo, MAX(numero_doc) as maximo FROM factura WHERE fecha = '$fecha_apertura' AND id_apertura = '$id_apertura' AND hora BETWEEN '$hora_apertura' AND '$hora_actual' AND numero_doc LIKE '%TIK%' AND id_sucursal = '$id_sucursal' AND anulada = 0
				UNION ALL SELECT MIN(CONVERT(CONVERT(num_fact_impresa,UNSIGNED INTEGER),UNSIGNED INTEGER)) as minimo, MAX(CONVERT(CONVERT(num_fact_impresa,UNSIGNED INTEGER),UNSIGNED INTEGER)) as maximo FROM factura WHERE fecha = '$fecha_apertura' AND id_apertura = '$id_apertura' AND hora BETWEEN '$hora_apertura' AND '$hora_actual' AND numero_doc LIKE '%COF%' AND id_sucursal = '$id_sucursal' AND anulada = 0
				UNION ALL SELECT MIN(CONVERT(num_fact_impresa,UNSIGNED INTEGER)) as minimo, MAX(CONVERT(num_fact_impresa,UNSIGNED INTEGER)) as maximo FROM factura WHERE fecha = '$fecha_apertura' AND id_apertura = '$id_apertura' AND hora BETWEEN '$hora_apertura' AND '$hora_actual' AND numero_doc LIKE '%CCF%' AND id_sucursal = '$id_sucursal' AND anulada = 0" );
				$cuenta_min_max = _num_rows($sql_min_max);
				if($cuenta_min_max)
				{
					$i = 1;
					while ($row_min_max = _fetch_array($sql_min_max))
					{
						if($i == 1)
						{
							$tike_min = $row_min_max["minimo"];
							$tike_max = $row_min_max["maximo"];
							if($tike_min != "" && $tike_max != "")
							{
								list($minimo_num,$ads) = explode("_", $tike_min);
								list($maximo_num,$ads) = explode("_", $tike_max);
							}
							if($tike_min > 0)
							{
								$tike_min = $minimo_num;
							}
							else
							{
								$tike_min = 0;
							}

							if($tike_max > 0)
							{
								$tike_max = $maximo_num;
							}
							else
							{
								$tike_max = 0;
							}
						}
						if($i == 2)
						{
							$factura_min = $row_min_max["minimo"];
							$factura_max = $row_min_max["maximo"];
							if($factura_max != "" && $factura_min != "")
							{
								$minimo_num= $factura_min;
								$maximo_num = $factura_max;
							}
							if($factura_min != "")
							{
								$factura_min = $minimo_num;
							}
							else
							{
								$factura_min = 0;
							}

							if($factura_max != "")
							{
								$factura_max = $maximo_num;
							}
							else
							{
								$factura_max = 0;
							}
						}
						if($i == 3)
						{
							$credito_fiscal_min = $row_min_max["minimo"];
							$credito_fiscal_max = $row_min_max["maximo"];
							if($credito_fiscal_min != "" && $credito_fiscal_max != 0)
							{
								$minimo_num= $credito_fiscal_min;
								$maximo_num= $credito_fiscal_max;
							}
							if($credito_fiscal_min != "")
							{
								$credito_fiscal_min = $minimo_num;
							}
							else
							{
								$credito_fiscal_min = 0;
							}

							if($credito_fiscal_max != "")
							{
								$credito_fiscal_max = $maximo_num;
							}
							else
							{
								$credito_fiscal_max = 0;
							}
						}
						$i += 1;
					}
				}


				$sql_corte = _query("SELECT * FROM factura
					WHERE fecha = '$fecha_apertura'
					AND id_apertura = '$id_apertura'
					AND hora BETWEEN '$hora_apertura' AND '$hora_actual'
					AND id_sucursal = '$id_sucursal' AND anulada = 0");
				$cuenta = _num_rows($sql_corte);


				if($cuenta > 0)
				{
					while ($row_corte = _fetch_array($sql_corte))
					{
						$id_factura = $row_corte["id_factura"];
						$anulada = $row_corte["anulada"];
						$subtotal = $row_corte["subtotal"];
						$suma = $row_corte["sumas"];
						$iva = $row_corte["iva"];
						$total = $row_corte["total"];
						$numero_doc = $row_corte["numero_doc"];
						$tipo_pago = $row_corte["credito"];
						$pagada = $row_corte["finalizada"];
						$alias_tipodoc = $row_corte["tipo_documento"];
						$q="SELECT  id_factura, alias_tipopago, subtotal, total_facturado, datos_extra
						FROM factura_pago WHERE id_factura='$id_factura'" ;
						$res = _query($q);
						$subtotal_fp = 0;
						while ($row_pago = _fetch_array($res))
						{
							$tipo_pago = $row_pago['alias_tipopago'];
							$subtotal_fp  = $row_pago['subtotal'];

							if($alias_tipodoc == 'TIK')
							{
								$total_tike += $subtotal_fp;
								if($tipo_pago == "CON")
								{
									$total_contado += $subtotal_fp;
								}
								else if($tipo_pago == "COI")
								{
									$total_consumo_interno += $subtotal_fp;
								}
								else if($tipo_pago == "CRE")
								{
									$total_credito += $subtotal_fp;
								}
								else if($tipo_pago == "TAR")
								{
									$total_tarjeta += $subtotal_fp;
								}
								else if($tipo_pago == "CHE")
								{
									$total_cheque += $total;
								}
								else if($tipo_pago == "VAL")
								{
									$total_vale += $subtotal_fp;
								}
								$t_tike += 1;
							}
							else if($alias_tipodoc == 'COF')
							{
								$total_factura += $total;
								if($tipo_pago == "CON")
								{
									$total_contado += $total;
								}
								else if($tipo_pago == "COI")
								{
									$total_consumo_interno += $subtotal_fp;
								}
								else if($tipo_pago == "CRE")
								{
									$total_credito += $subtotal_fp;
								}
								else if($tipo_pago == "TAR")
								{
									$total_tarjeta += $subtotal_fp;
								}
								else if($tipo_pago == "CHE")
								{
									$total_cheque += $total;
								}
								else if($tipo_pago == "VAL")
								{
									$total_vale += $subtotal_fp;
								}
								$t_factuta += 1;
							}
							else if($alias_tipodoc == 'CCF')
							{
								$total_credito_fiscal += $total;
								if($tipo_pago == "CON")
								{
									$total_contado += $total;
								}
								else if($tipo_pago == "COI")
								{
									$total_consumo_interno += $subtotal_fp;
								}
								else if($tipo_pago == "CRE")
								{
									$total_credito += $subtotal_fp;
								}
								else if($tipo_pago == "TAR")
								{
									$total_tarjeta += $subtotal_fp;
								}
								else if($tipo_pago == "CHE")
								{
									$total_cheque += $total;
								}
								else if($tipo_pago == "VAL")
								{
									$total_vale += $subtotal_fp;
								}
								$t_credito += 1;
							}
						}
					}
				}
			}
			else
			{
				$sql_min_max = _query("SELECT MIN(numero_doc) as minimo, MAX(numero_doc) as maximo FROM factura WHERE fecha = '$fecha_apertura' AND credito=0 AND id_apertura_pagada = '$id_apertura' AND hora BETWEEN '$hora_apertura' AND '$hora_actual' AND numero_doc LIKE '%TIK%' AND id_sucursal = '$id_sucursal' AND anulada = 0
				UNION ALL SELECT MIN(CONVERT(num_fact_impresa,UNSIGNED INTEGER)) as minimo, MAX(CONVERT(num_fact_impresa,UNSIGNED INTEGER)) as maximo FROM factura WHERE fecha = '$fecha_apertura' AND credito=0 AND id_apertura_pagada = '$id_apertura' AND hora BETWEEN '$hora_apertura' AND '$hora_actual' AND numero_doc LIKE '%COF%' AND id_sucursal = '$id_sucursal' AND anulada = 0
				UNION ALL SELECT MIN(CONVERT(num_fact_impresa,UNSIGNED INTEGER)) as minimo, MAX(CONVERT(num_fact_impresa,UNSIGNED INTEGER)) as maximo FROM factura WHERE fecha = '$fecha_apertura' AND credito=0 AND id_apertura_pagada = '$id_apertura' AND hora BETWEEN '$hora_apertura' AND '$hora_actual' AND numero_doc LIKE '%CCF%' AND id_sucursal = '$id_sucursal' AND anulada = 0");
				$cuenta_min_max = _num_rows($sql_min_max);

				if($cuenta_min_max)
				{
					$i = 1;
					while ($row_min_max = _fetch_array($sql_min_max))
					{
						if($i == 1)
						{
							$tike_min = $row_min_max["minimo"];
							$tike_max = $row_min_max["maximo"];
							if($tike_min != "" && $tike_max != "")
							{
								list($minimo_num,$ads) = explode("_", $tike_min);
								list($maximo_num,$ads) = explode("_", $tike_max);
							}
							if($tike_min > 0)
							{
								$tike_min = $minimo_num;
							}
							else
							{
								$tike_min = 0;
							}

							if($tike_max > 0)
							{
								$tike_max = $maximo_num;
							}
							else
							{
								$tike_max = 0;
							}
						}
						if($i == 2)
						{
							$factura_min = $row_min_max["minimo"];
							$factura_max = $row_min_max["maximo"];
							if($factura_max != "" && $factura_min != "")
							{
								$minimo_num = $factura_min;
								$maximo_num=$factura_max;
							}
							if($factura_min != "")
							{
								$factura_min = $minimo_num;
							}
							else
							{
								$factura_min = 0;
							}

							if($factura_max != "")
							{
								$factura_max = $maximo_num;
							}
							else
							{
								$factura_max = 0;
							}
						}
						if($i == 3)
						{
							$credito_fiscal_min = $row_min_max["minimo"];
							$credito_fiscal_max = $row_min_max["maximo"];
							if($credito_fiscal_min != "" && $credito_fiscal_max != 0)
							{
								$minimo_num= $credito_fiscal_min;
								$maximo_num= $credito_fiscal_max;
							}
							if($credito_fiscal_min != "")
							{
								$credito_fiscal_min = $minimo_num;
							}
							else
							{
								$credito_fiscal_min = 0;
							}

							if($credito_fiscal_max != "")
							{
								$credito_fiscal_max = $maximo_num;
							}
							else
							{
								$credito_fiscal_max = 0;
							}
						}
						$i += 1;
					}
				}
				$sql_corte_caja = _query("SELECT * FROM factura
					WHERE fecha = '$fecha_apertura' AND id_sucursal = '$id_sucursal'
					AND anulada = 0 AND finalizada = 1 AND credito=0
					AND id_apertura_pagada = '$id_apertura'");
				$cuenta_caja = _num_rows($sql_corte_caja);
				if($cuenta_caja > 0)
				{
					while ($row_corte = _fetch_array($sql_corte_caja))
					{
						$id_factura = $row_corte["id_factura"];
						$anulada = $row_corte["anulada"];
						$subtotal = $row_corte["subtotal"];
						$suma = $row_corte["sumas"];
						$iva = $row_corte["iva"];
						$total = $row_corte["total"];
						$numero_doc = $row_corte["numero_doc"];
						$tipo_pago = $row_corte["credito"];
						$pagada = $row_corte["finalizada"];
						$tipo_documento = $row_corte["tipo_documento"];

						$q="SELECT  id_factura, alias_tipopago, subtotal, total_facturado, datos_extra
						FROM factura_pago WHERE id_factura='$id_factura'" ;
						$res = _query($q);
						$subtotal_fp = 0;
						while ($row_pago = _fetch_array($res))
						{
							$subtotal_fp  = $row_pago['subtotal'];
							$tipo_pago = $row_pago['alias_tipopago'];
							if($tipo_documento == 'TIK')
							{
								$total_tike += 	$subtotal_fp;
								if($tipo_pago == "CON")
								{
									$total_contado += 	$subtotal_fp;
								}
								else if($tipo_pago == "COI")
								{
									$total_consumo_interno += $subtotal_fp;
								}
								else if($tipo_pago == "TAR")
								{
									$total_tarjeta += 	$subtotal_fp;
								}
								else if($tipo_pago == "CHE")
								{
									$total_cheque += 	$subtotal_fp;
								}
								else if($tipo_pago == "VAL")
								{
									$total_vale += $subtotal_fp;
								}
								$t_tike += 1;
							}
							else if($tipo_documento == 'COF')
							{
								$total_factura += 	$subtotal_fp;
								if($tipo_pago == "CON")
								{
									$total_contado +=	$subtotal_fp;
								}
								else if($tipo_pago == "COI")
								{
									$total_consumo_interno += $subtotal_fp;
								}
								else if($tipo_pago == "TAR")
								{
									$total_tarjeta += 	$subtotal_fp;
								}
								else if($tipo_pago == "CHE")
								{
									$total_cheque += 	$subtotal_fp;
								}
								else if($tipo_pago == "VAL")
								{
									$total_vale += $subtotal_fp;
								}
								$t_factuta += 1;
							}
							else if($tipo_documento == 'CCF')
							{
								$total_credito_fiscal += 	$subtotal_fp;
								if($tipo_pago == "CON")
								{
									$total_contado += 	$subtotal_fp;
								}
								else if($tipo_pago == "COI")
								{
									$total_consumo_interno += $subtotal_fp;
								}
								else if($tipo_pago == "TAR")
								{
									$total_tarjeta += 	$subtotal_fp;
								}
								else if($tipo_pago == "CHE")
								{
									$total_cheque += 	$subtotal_fp;
								}
								else if($tipo_pago == "VAL")
								{
									$total_vale += $subtotal_fp;
								}
								$t_credito += 1;
							}
						}
					}
				}
			}

			$total_corte = $total_tike + $total_factura + $total_credito_fiscal + $monto_apertura;

			/////////////////////////////////
			$xdatos['t_tike']=round($t_tike,2);
			$xdatos['t_factuta']=round($t_factuta,2);
			$xdatos['t_credito']=round($t_credito,2);
			/////////////////////////////////
			$xdatos['total_tike']=round($total_tike,2);
			$xdatos['total_factura']=round($total_factura,2);
			$xdatos['total_credito_fiscal']=$total_credito_fiscal;
			//////////////////////////////////
			$xdatos['total_contado'] = round($total_contado,2);
			$xdatos['total_transferencia'] = round($total_transferencia,2);
			$xdatos['total_tarjeta'] = round($total_tarjeta,2);
			$xdatos['total_cheque'] = $total_cheque;
			$xdatos['total_vale'] = round($total_vale,2);
			///////////////////////////////////
			$xdatos['tike_min'] = $tike_min;
			$xdatos['tike_max'] = $tike_max;
			$xdatos['factura_min'] = $factura_min;
			$xdatos['factura_max'] = $factura_max;
			$xdatos['credito_fiscal_min'] = $credito_fiscal_min;
			$xdatos['credito_fiscal_max'] = $credito_fiscal_max;
			////////////////////////////////////
			$xdatos['monto_apertura'] = round($monto_apertura,2);
			$xdatos['monto_ch'] = round($monto_ch,2);
			$xdatos['monto_retencion'] = round($monto_retencion,2);
			if ($tipo_corte == "C")
			{
				 $tot_corte = round(($total_corte+$monto_ch),2);
				$xdatos['total_corte']=  sprintf("%.2f", $tot_corte);

			}
			else {
				$tot_corte = round($total_corte,2);
				$xdatos['total_corte']= sprintf("%.2f", $tot_corte);

			}
			//datos de arqueo
			$q="SELECT total FROM arqueo_corte
 		  WHERE id_apertura='$aper_id' LIMIT 1";
 		 $res=_query($q);
		 $total_arqueo = 0.00 ;
		 if (_num_rows($res)>0){
			 $row= _fetch_row($res);
			 $total_arqueo = sprintf("%.2f",$row[0]);
		 }
		 //datos de lecturas
		 $sql = "SELECT total_dinero, total_gal, total_impuestos
		 FROM lectura_bomba
		 WHERE id_apertura='$aper_id'
		 ";
		 $res2=_query($sql);
		 $total_galones = 0 ;
		 $total_dinero_lectura  = 0 ;
		 $total_impuestos       = 0;
		 $total_fin_dinero_lect = 0;

		 if (_num_rows($res2)>0){
			 $row2= _fetch_row($res2);
			$total_dinero_lectura  = $row2[0];
			$total_galones         = $row2[1];
			$total_impuestos       = $row2[2];
		 }
		// $total_fin_dinero_lect  = $total_dinero_lectura ; //+ $total_impuestos ;
		$total_fin_dinero_lect  = sprintf("%.2f", $total_dinero_lectura);
		$rc = getDatosCaja($caja);
		$tipo_caja = $rc["tipo_caja"];
		$xdatos['total_arqueo']= $total_arqueo;
		$xdatos['total_dinero_lectura']= $total_fin_dinero_lect;
		$xdatos['total_galones']= $total_galones;
		$xdatos['tipo_caja']= $tipo_caja;
		echo json_encode($xdatos);
	}

//funcion para traer datos seguntipo de pago
function getValorTipoPago($id_factura){
	$q="SELECT  id_factura, alias_tipopago, subtotal,
	total_facturado, datos_extra
	FROM factura_pago
	WHERE id_factura='$id_factura'" ;
	$res = _query($q);
	return $res;
}
/* modal para arqueo de caja */
function modalArqueo(){
	?>
  <div class="modal fade" id="modalArqueo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content modal-md">
            <div class="modal-header">
              <h5 class="modal-title text-center text-success textModalArq" id="modalArqLabel">Arqueo de Caja  &nbsp;
                <span class="fa-solid  fa-money-bill-1-wave text-success"></span></h5>
								<div class='row'>
									<div class='col-md-6 text-center'>
								<h5 class='text-warning'>Facturado</h5>
							</div><div class='col-md-4 text-center'>
								<input type="text" id="facturado" name="facturado" value=0
								class="form-control col-md-6  input-sm decimal text-primary" readonly >
								</div>
									<div class='col-md-2 text-center'></div>
            </div>
						</div>
            <div class="modal-body">
              <table	class="table table-condensed" id="tableArqueo">
                <tbody id='conceptos'>
									<?php
									$conceptos =getConceptosArqueo();
									while ($row = _fetch_array($conceptos)){
									//}
									 //foreach($conceptos as $key=> $val ) {
									    $st_1	=	"style='width:80%;'";
											$st_2	=	"style='width:130%;'";
											$id						= $row['id'];
											$descrip			= $row['descripcion'];
											$multiplicador= $row['multiplicador'];
											$id_input = "<input type='hidden' name='id$id' id='id$id' ";
											$id_input.="class='identifier identifier$id' value='$id'>";
											$multi_input = "<input type='hidden' name='multiplic' id='multiplic' ";
											$multi_input.="class='multiplicador' value='$multiplicador'>";
											$subt_input ="<input type='text' name='subt$id' id='subt$id'";
											$subt_input.="class='form-control input-sm subtotal identifier$id'  value='' readonly>";
											$data_arq= "<tr class='conceptos_all'>";
											$data_arq.="<td class='text-primary' colspan=2>".$id_input.$multi_input."<h5 $st_2>".$descrip."</h5></td>";
											$data_arq.="<td $st_1><input type='text' name='qty$id' id='qty$id' ";
											$data_arq.="class='form-control input-sm numeric cant cant$id' value=''></td>";
											$data_arq.="<td $st_1>".$subt_input."</td>";
											$data_arq.="</tr>";
											echo $data_arq;
										}
										$tot_input ="<input type='text' name='total_arq' id='total_arq' ";
										$tot_input.="class='total total_arqueo' value='' readonly>";
										?>
										<tr class='totals'>
											<td colspan=3>Totales: $</td>
											<td><?=$tot_input;?></td>
									</tr>
                </tbody>
                <tfoot >
									<tr class='foot'>
                <td colspan=2><button type="button" class="btn btn-primary" id="btnSaveArq" name="btnSaveArq">
									<i class="fa fa-save"></i> Guardar</button> </td>
								<td></td>
                <td><div class="pull-right"><button type="button"  class="btn btn-danger" id="btnEsc">
									<i class="fa fa-stop"></i> &nbsp;&nbsp;&nbsp;Salir &nbsp;&nbsp;&nbsp;</button></div>
								</td>
							</tr>
                </tfoot>
              </table>
              <input type="hidden" name="id_cuenta" id="id_cuenta" value=-1>
            </div>

        </div>
      </div>
 <?php
}
function getApertura(){
	$id_sucursal=$_SESSION['id_sucursal'];
	$id_apertura = $_REQUEST["id_apertura"];
	$row=getDatosApertura($id_apertura,$id_sucursal);
	$id_caja=$row['caja'];
	$row2=getCaja($id_caja);
 	$total_venta_apertura =getTotalFactPago($id_apertura);
	$row['total_venta_apertura']= $total_venta_apertura;

	$res = array_merge($row, $row2);

	echo json_encode($res);
}
function arqueo(){
	$cuantos = $_REQUEST["cuantos"];
	$total_arqueo =  $_REQUEST["total_arqueo"];
	$id_apertura =  $_POST["id_apertura"];
	$array_json=$_POST['json_arr'];
	$insertar = false;
 if ($cuantos>0) {
	 $array = json_decode($array_json, true);
	  $table= 'arqueo_corte';
	 foreach ($array as $fila) {
		 $id   = $fila['id'];
		 $qty  = $fila['qty'];
		 $subt = $fila['subt'];
		 $q="SELECT id_arqueo, id_apertura, id_concepto, alias_tipopago,
		  cantidad, subtotal,total FROM arqueo_corte
		  WHERE id_apertura='$id_apertura' and id_concepto='$id'";
		 $res=_query($q);
		 //hay que hacer el loop por cada concepto y verificar para insertar o actualizar
		 $data = array(
			 'id_apertura' => $id_apertura,
			 'id_concepto' => $id,
			 'cantidad' 	=> $qty,
			 'subtotal' 	=> $subt,
			 'total'			=> $total_arqueo,
		 );
		 if(_num_rows($res)==0){
				 $insertar = _insert($table, $data);
		  }else{
				$wc ="id_apertura='$id_apertura' and id_concepto='$id'";
				$insertar = _update($table, $data, $wc);
			}
	  }
	 }
	 if ($insertar) {
		 //_commit();
		 $xdatos['typeinfo']="Success";
		 $xdatos['msg']="Registro actualizado correctamente";
	 } else {
		 //_rollback();
		 $xdatos['typeinfo']="Error";
		 $xdatos['msg']="Error al insertar o actualizar el registro";
	 }
	   echo json_encode($xdatos);
}
function getTanques($id_sucursal){
	$qt="SELECT t.numero, t.descripcion, t.tipo_combustible
	FROM tanque AS t WHERE activa=1 and id_sucursal='$id_sucursal'";
	$rt = _query($qt);
	return $rt;
}

function finalizar_turno(){
	//
	$id_app = $_REQUEST["id_apertura"];
	$turno = $_REQUEST["turno"];
	$id_apertura = $_REQUEST["id_apertura"];
	$turno_detalle = $_REQUEST["turno"];
	$tipo_corte = $_POST["tipo_corte"];
	$diferencia     =$_POST["diferencia"];
  $total_efectivo =$_POST["total_efectivo"];
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$fecha_corte = date('Y-m-d');
	$fecha_actual=date("Y-m-d");
	$id_sucursal=$_SESSION['id_sucursal'];

		$sql_sucursal=_query("SELECT * FROM sucursal WHERE id_sucursal='$id_sucursal'");
		$array_sucursal=_fetch_array($sql_sucursal);
		$nombre_sucursal=$array_sucursal['descripcion'];



		$sql_apertura = _query("SELECT * FROM apertura_caja WHERE id_apertura = '$id_app'");
		$cuenta = _num_rows($sql_apertura);
		$row_apertura = _fetch_array($sql_apertura);
		$id_apertura = $row_apertura["id_apertura"];
		$tike_inicia = $row_apertura["tiket_inicia"];
		$factura_inicia = $row_apertura["factura_inicia"];
		$credito_inicia = $row_apertura["credito_fiscal_inicia"];
		$empleado = $row_apertura["id_empleado"];
		$dev_inicia = $row_apertura["dev_inicia"];
		$turno = $row_apertura["turno"];
		$fecha_apertura = $row_apertura["fecha"];
		$hora_apertura = $row_apertura["hora"];
		$monto_apertura = $row_apertura["monto_apertura"];
		$monto_apertura = $monto_apertura +$row_apertura["monto_ch"];

		$hora_actual = date('H:i:s');
		/////////////////////////////////////////Correlativo//////////////////////////////////////////////////////////
		$n_tiket = 0;
	    $n_factura = 0;
	    $n_credito_fiscal = 0;
	    $n_dev = 0;


	    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $sql_caja = _query("SELECT * FROM mov_caja WHERE fecha = '$fecha_apertura' AND id_apertura = '$id_app' AND hora BETWEEN '$hora_apertura' AND '$hora_actual' AND id_sucursal = '$id_sucursal'");
	    $cuenta_caja = _num_rows($sql_caja);

		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$sql_corte = _query("SELECT * FROM factura WHERE fecha = '$fecha_apertura' AND id_apertura = '$id_app' AND hora BETWEEN '$hora_apertura' AND '$hora_actual' AND id_sucursal = '$id_sucursal' AND finalizada = 1 AND anulada = 0 AND credito = 0");
		$cuenta = _num_rows($sql_corte);
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$sql_min_max = _query("SELECT MIN(numero_doc) as minimo, MAX(numero_doc) as maximo FROM factura WHERE fecha = '$fecha_apertura' AND id_apertura = '$id_apertura' AND hora BETWEEN '$hora_apertura' AND '$hora_actual' AND numero_doc LIKE '%TIK%' AND id_sucursal = '$id_sucursal' AND anulada = 0 AND finalizada = 1 UNION ALL SELECT MIN(numero_doc) as minimo, MAX(numero_doc) as maximo FROM factura WHERE fecha = '$fecha_apertura' AND id_apertura = '$id_apertura' AND hora BETWEEN '$hora_apertura' AND '$hora_actual' AND numero_doc LIKE '%COF%' AND id_sucursal = '$id_sucursal' AND anulada = 0 AND finalizada = 1 UNION ALL SELECT MIN(numero_doc) as minimo, MAX(numero_doc) as maximo FROM factura WHERE fecha = '$fecha_apertura' AND id_apertura = '$id_apertura' AND hora BETWEEN '$hora_apertura' AND '$hora_actual' AND numero_doc LIKE '%CCF%' AND id_sucursal = '$id_sucursal' AND anulada = 0 AND finalizada = 1" );
		$cuenta_min_max = _num_rows($sql_min_max);

		$total_tike_e = 0;
		$total_factura_e = 0;
		$total_credito_fiscal_e = 0;
		$total_reserva_e = 0;
		$total_dev_e = 0;
		$total_tike_g = 0;
		$total_factura_g = 0;
		$total_credito_fiscal_g = 0;
		$total_reserva_g = 0;
		$total_dev_g = 0;
		$tike_min = 0;
		$tike_max = 0;
		$factura_min = 0;
		$factura_max = 0;
		$credito_fiscal_min = 0;
		$credito_fiscal_max = 0;
		$dev_min = 0;
		$dev_max = 0;
		$res_min = 0;
		$res_max = 0;
		$t_tike = 0;
		$t_factuta = 0;
		$t_credito = 0;
		$t_dev = 0;
		$t_res = 0;
		$t_recerva = 0;
		$total_contado = 0;
		$total_tarjeta = 0;
		$total_tike = 0;
		$total_factura = 0;
		$total_credito_fiscal = 0;
		$lista_dev = "";
		if($cuenta > 0)
		{
			while ($row_corte = _fetch_array($sql_corte))
			{
				$id_factura = $row_corte["id_factura"];
	      $anulada = $row_corte["anulada"];
	      $subtotal = $row_corte["subtotal"];
	      $suma = $row_corte["sumas"];
	      $iva = $row_corte["iva"];
	      $total = $row_corte["total"];
	      $numero_doc = $row_corte["numero_doc"];

	      $ax = explode("_", $numero_doc);
	      $numero_co = $ax[0];
	      $alias_tipodoc = $ax[1];


	      if($alias_tipodoc == 'TIK')
	      {
	          $total_tike += $total;
	      }
	      else if($alias_tipodoc == 'COF')
	      {
	          $total_factura += $total;
	      }
	      else if($alias_tipodoc == 'CCF')
	      {
	          $total_credito_fiscal += $total;
	      }



			}
		}
		if($cuenta_min_max)
	        {
	            $i = 1;
	            while ($row_min_max = _fetch_array($sql_min_max))
	            {
	                if($i == 1)
	                {
	                    $tike_min = $row_min_max["minimo"];
	                    $tike_max = $row_min_max["maximo"];
	                    if($tike_min != "" && $tike_max != "")
	                    {
	                    list($minimo_num,$ads) = explode("_", $tike_min);
	                    list($maximo_num,$ads) = explode("_", $tike_max);
	                	}
	                    if($tike_min > 0)
	                    {
	                        $tike_min = $minimo_num;
	                    }
	                    else
	                    {
	                        $tike_min = 0;
	                    }

	                    if($tike_max > 0)
	                    {
	                        $tike_max = $maximo_num;
	                    }
	                    else
	                    {
	                        $tike_max = 0;
	                    }
	                }
	                if($i == 2)
	                {
	                    $factura_min = $row_min_max["minimo"];
	                    $factura_max = $row_min_max["maximo"];
	                    if($factura_max != "" && $factura_min != "")
	                    {
	                    list($minimo_num,$ads) = explode("_", $factura_min);
	                    list($maximo_num,$ads) = explode("_", $factura_max);
	                	}
	                    if($factura_min != "")
	                    {
	                        $factura_min = $minimo_num;
	                    }
	                    else
	                    {
	                        $factura_min = 0;
	                    }

	                    if($factura_max != "")
	                    {
	                        $factura_max = $maximo_num;
	                    }
	                    else
	                    {
	                        $factura_max = 0;
	                    }
	                }
	                if($i == 3)
	                {
	                    $credito_fiscal_min = $row_min_max["minimo"];
	                    $credito_fiscal_max = $row_min_max["maximo"];
	                    if($credito_fiscal_min != "" && $credito_fiscal_max != 0)
	                    {
	                    list($minimo_num,$ads) = explode("_", $credito_fiscal_min);
	                    list($maximo_num,$ads) = explode("_", $credito_fiscal_max);
	                	}
	                    if($credito_fiscal_min != "")
	                    {
	                        $credito_fiscal_min = $minimo_num;
	                    }
	                    else
	                    {
	                        $credito_fiscal_min = 0;
	                    }

	                    if($credito_fiscal_max != "")
	                    {
	                        $credito_fiscal_max = $maximo_num;
	                    }
	                    else
	                    {
	                        $credito_fiscal_max = 0;
	                    }
	                }
	                $i += 1;
	            }
	        }
		$total_entrada_caja = 0;
		$total_salida_caja = 0;
		if($cuenta_caja > 0)
		{
			while ($row_caja = _fetch_array($sql_caja))
			{
				$monto = $row_caja["valor"];
				$entrada = $row_caja["entrada"];
				$salida = $row_caja["salida"];

				if($entrada == 1 && $salida == 0)
				{
					$total_entrada_caja += $monto;
				}
				else if($salida == 1 && $entrada == 0)
				{
					$total_salida_caja += $monto;
				}
			}
		}

		$sql_monto_dev=_fetch_array(_query("SELECT SUM(factura.total) AS total_devoluciones FROM factura JOIN factura AS f ON f.id_factura=factura.afecta WHERE factura.tipo_documento ='DEV' AND factura.id_apertura_pagada=$id_apertura"));
		$monto_dev=$sql_monto_dev['total_devoluciones'];

		$sql_monto_dev=_fetch_array(_query("SELECT SUM(factura.total) AS total_devoluciones FROM factura JOIN factura AS f ON f.id_factura=factura.afecta WHERE factura.tipo_documento ='NC' AND factura.id_apertura_pagada=$id_apertura"));
		$monto_nc=$sql_monto_dev['total_devoluciones'];

		//$total_devolucion = $total_dev_g + $total_dev_e;
		$total_corte = $total_tike + $total_factura- $monto_dev - $monto_nc  + $total_credito_fiscal + $monto_apertura  + $total_entrada_caja - $total_salida_caja;

		$total_exx = $total_tike_e+$total_factura_e+$total_credito_fiscal_e+$total_reserva_e;
		$total_graa = $total_tike_g+$total_factura_g+$total_credito_fiscal_g+$total_reserva_g;

	$hora_actual = date("H:i:s");
	$tike = $total_tike_e + $total_tike_g;
	$factura = $total_factura_e + $total_factura_g;
	$credito = $total_credito_fiscal_e + $total_credito_fiscal_g;
	$reserva = $total_reserva_g + $total_reserva_e;
	//$dev = $total_dev_e + $total_dev_g;

	//$total_tike= $_POST["total_tike"];
	//$total_factura = $_POST["total_factura"];
	//$total_credito_fiscal = $_POST["total_credito"];

	$tabla = "controlcaja";
	$form_data = array(
		'fecha_corte' => $fecha_actual,
		'hora_corte' => $hora_actual,
		'id_empleado' => $id_user,
		'id_sucursal' => $id_sucursal,
		'id_apertura' => $id_apertura,
		'texento' => $total_tike_e,
		'tgravado' => $total_tike,
		'totalt' => $total_tike,
		'fexento' => $total_factura_e,
		'fgravado' => $total_factura,
		'totalf' => $total_factura,
		'cfexento' => $total_credito_fiscal_e,
		'cfgravado' => $total_credito_fiscal,
		'totalcf' => $total_credito_fiscal,
		'diferencia' => $diferencia,
		'totalgral' => $total_corte,
		'cashfinal' => $total_efectivo,
		'totalnot' => $t_tike,
		'totalnof' => $t_factuta,
		'totalnocf' => $t_credito,
		'turno' => $turno,
		'tinicio' => $tike_min,
		'tfinal' => $tike_max,
		'finicio' => $factura_min,
		'ffinal' => $factura_max,
		'cfinicio' => $credito_fiscal_min,
		'cffinal' => $credito_fiscal_max,
		'cashinicial' => $monto_apertura,
		'tipo_corte' => $tipo_corte,
		'vtaefectivo' => $total_contado,
		'tarjetas' => $total_tarjeta,
		'vales' => $total_salida_caja,
		'ingresos' => $total_entrada_caja,
		'totalnodev' => $t_dev,
		'rinicio' => $res_min,
		'rfinal' => $res_max,
		'totalnor' => $t_res,
		'rexento' => $total_reserva_e,
		'rgravado' => $total_reserva_g,
		'totalr' => $reserva,
	);
 /*
	$sql_ = _query("SELECT * FROM controlcaja WHERE id_apertura = '$id_apertura' AND tipo_corte = 'Z'");
	$cuentax = _num_rows($sql_);
	if($cuentax == 0)
	{*/
		if($tipo_corte == "X")
		{
			/*$insertar = _insert($tabla, $form_data);
			$id_cortex= _insert_id();*/
			$table_apertura = "apertura_caja";
			$form_up = array(
				'monto_vendido' => $total_efectivo,
				'vigente'=> 0,
			);
			/*
			if($insertar)
			{*/
				$sql_turno = _query("SELECT * FROM detalle_apertura WHERE id_apertura = '$id_apertura' ORDER BY turno DESC LIMIT 1");
			    $row_turno = _fetch_array($sql_turno);
			    $tuno = $row_turno["turno"];
			    $id_usuario = $row_turno["id_usuario"];

			    $sql_turno = _query("SELECT * FROM detalle_apertura WHERE id_apertura = '$id_apertura' AND vigente = 1 ");
			    $row_turno = _fetch_array($sql_turno);
			    $id_detalle = $row_turno["id_detalle"];
			    $n_tuno = $tuno + 1;
			    $tabla2 = "detalle_apertura";
			    $form_data2 = array(
			        'vigente' => 0
			        );
			    $where_up = "id_detalle='".$id_detalle."'";
			    $update = _update($tabla2, $form_data2, $where_up);
			    if($update)
			    {
			        $tabla1 = "detalle_apertura";
			        $form_data1 = array(
			            'id_apertura' => $id_apertura,
			            'turno' => $n_tuno,
			            'fecha' => $fecha_actual,
			            'hora' => $hora_actual,
			            'vigente' => 1
			            );
			        $insert = _insert($tabla1, $form_data1);
			        if($insert)
			        {
			            $tabla3 = "apertura_caja";
			            $form_data3 = array(
			                'turno' => $n_tuno,
			                'turno_vigente' => 1,
			                );
			            $where_up3 = "id_apertura='".$id_apertura."'";
			            $update1 = _update($tabla3, $form_data3, $where_up3);
			        }
			    }
			    $where_apertura = "id_apertura='".$id_apertura."'";
				$up_apertura = _update($table_apertura, $form_up, $where_apertura);
				//total_ticket_gravado

				$sql_devoluciones=_query("SELECT factura.numero_doc,factura.total,f.tipo_documento,f.numero_doc as doc
					FROM factura JOIN factura AS f ON f.id_factura=factura.afecta
					WHERE factura.tipo_documento ='DEV' AND factura.id_apertura_pagada=$id_apertura");
				$i=1;
				while ($row_de=_fetch_array($sql_devoluciones)) {
					# code...
					list($doca,$sa)=explode("_",$row_de['numero_doc']);

					list($docb,$sb)=explode("_",$row_de['doc']);

					$table_dev = "devoluciones_corte";
					$form_dev = array(
						'id_corte' => $id_cortex,
						'n_devolucion' => $doca,
						't_devolucion' => $row_de['total'],
						'afecta' => $docb,
						'tipo' => $row_de['tipo_documento'],
					);
					$inser_dev = _insert($table_dev, $form_dev);
					$i++;
				}
				$sql_devoluciones=_query("SELECT factura.numero_doc,factura.total,f.tipo_documento,f.num_fact_impresa as doc
					FROM factura JOIN factura AS f ON f.id_factura=factura.afecta WHERE factura.tipo_documento ='NC'
					AND factura.id_apertura_pagada=$id_apertura");
				$i=1;
				while ($row_de=_fetch_array($sql_devoluciones)) {
					# code...
					list($doca,$sa)=explode("_",$row_de['numero_doc']);
					$docb=$row_de['doc'];

					$table_dev = "devoluciones_corte";
					$form_dev = array(
						'id_corte' => $id_cortex,
						'n_devolucion' => $doca,
						't_devolucion' => $row_de['total'],
						'afecta' => $docb,
						'tipo' => $row_de['tipo_documento'],
					);
					$inser_dev = _insert($table_dev, $form_dev);
					$i++;
				}
			//}


		}
  /*
		if($tipo_corte == "X")
		{
			$insertar = _insert($tabla, $form_data);
			$id_cortex= _insert_id();
			$sql_turno = _query("SELECT * FROM detalle_apertura WHERE id_apertura = '$id_apertura' AND vigente = 1 ");
			$row_turno = _fetch_array($sql_turno);
			$id_detalle = $row_turno["id_detalle"];
			$n_tuno = $tuno + 1;
			$table_apertura = "apertura_caja";
			$form_up = array(
				'monto_vendido' => $total_efectivo,
				'vigente'=> 0,
			);

			$tabla_ap = "detalle_apertura";
			$form_data_ap = array(
					'vigente' => 0
			);
			$where_up = "id_detalle='".$id_detalle."'";
			$update_ap = _update($tabla_ap, $form_data_ap, $where_up);
			var_dump($update_ap);
			if($update_ap)
			{
					$tabla1 = "detalle_apertura";
					$form_data1 = array(
							'id_apertura' => $id_apertura,
							'turno' => $n_tuno,
							'fecha' => $fecha_actual,
							'hora' => $hora_actual,
							'vigente' => 1
							);
					$insert = _insert($tabla1, $form_data1);
					if($insert)
					{
							$tabla1 = "apertura_caja";
							$form_data1 = array(
									'turno' => $n_tuno,
									'turno_vigente' => 1,
									);
							$where_up = "id_apertura='".$id_apertura."'";
							$update1 = _update($tabla1, $form_data1, $where_up);
					}
			}
			$where_apertura = "id_apertura='".$id_apertura."'";
		  $up_apertura = _update($table_apertura, $form_up, $where_apertura);


			$sql_devoluciones=_query("SELECT factura.numero_doc,factura.total,f.tipo_documento,f.numero_doc as doc
				 FROM factura JOIN factura AS f ON f.id_factura=factura.afecta
				 WHERE factura.tipo_documento ='DEV' AND factura.id_apertura_pagada=$id_apertura");
			$i=1;
			while ($row_de=_fetch_array($sql_devoluciones)) {
				# code...
				list($doca,$sa)=explode("_",$row_de['numero_doc']);

				list($docb,$sb)=explode("_",$row_de['doc']);

				$table_dev = "devoluciones_corte";
				$form_dev = array(
					'id_corte' => $id_cortex,
					'n_devolucion' => $doca,
					't_devolucion' => $row_de['total'],
					'afecta' => $docb,
					'tipo' => $row_de['tipo_documento'],
				);
				$inser_dev = _insert($table_dev, $form_dev);
				$i++;
			}
			$sql_devoluciones=_query("SELECT factura.numero_doc,factura.total,f.tipo_documento,f.num_fact_impresa as doc
				FROM factura JOIN factura AS f ON f.id_factura=factura.afecta
				WHERE factura.tipo_documento ='NC' AND factura.id_apertura_pagada=$id_apertura");
			$i=1;
			while ($row_de=_fetch_array($sql_devoluciones)) {
				# code...
				list($doca,$sa)=explode("_",$row_de['numero_doc']);
				$docb=$row_de['doc'];

				$table_dev = "devoluciones_corte";
				$form_dev = array(
					'id_corte' => $id_cortex,
					'n_devolucion' => $doca,
					't_devolucion' => $row_de['total'],
					'afecta' => $docb,
					'tipo' => $row_de['tipo_documento'],
				);
				$inser_dev = _insert($table_dev, $form_dev);
				$i++;
			}

		}
	if($tipo_corte == "Z")
		{

			$table_apertura = "apertura_caja";
			$form_up = array(
				'vigente' => 0,
				'monto_vendido' => $total_efectivo,
			);
			$where_apertura = "id_apertura='".$id_apertura."'";
			$up_apertura = _update($table_apertura, $form_up, $where_apertura);
			if($up_apertura)
			{
				$insertar = _insert($tabla, $form_data);
				if($insertar)
				{
					$id_cortex = _insert_id();

					$sql_devoluciones=_query("SELECT factura.numero_doc,factura.total,f.tipo_documento,f.numero_doc as doc
						FROM factura JOIN factura AS f ON f.id_factura=factura.afecta
						WHERE factura.tipo_documento ='DEV' AND factura.id_apertura_pagada=$id_apertura");
					$i=1;
					while ($row_de=_fetch_array($sql_devoluciones)) {
						# code...
						list($doca,$sa)=explode("_",$row_de['numero_doc']);

						list($docb,$sb)=explode("_",$row_de['doc']);

						$table_dev = "devoluciones_corte";
						$form_dev = array(
							'id_corte' => $id_cortex,
							'n_devolucion' => $doca,
							't_devolucion' => $row_de['total'],
							'afecta' => $docb,
							'tipo' => $row_de['tipo_documento'],
						);
						$inser_dev = _insert($table_dev, $form_dev);
						$i++;
					}
					$sql_devoluciones=_query("SELECT factura.numero_doc,factura.total,f.tipo_documento,f.num_fact_impresa as doc
						FROM factura JOIN factura AS f ON f.id_factura=factura.afecta
						WHERE factura.tipo_documento ='NC' AND factura.id_apertura_pagada=$id_apertura");
					$i=1;
					while ($row_de=_fetch_array($sql_devoluciones)) {
						# code...
						list($doca,$sa)=explode("_",$row_de['numero_doc']);
						$docb=$row_de['doc'];

						$table_dev = "devoluciones_corte";
						$form_dev = array(
							'id_corte' => $id_cortex,
							'n_devolucion' => $doca,
							't_devolucion' => $row_de['total'],
							'afecta' => $docb,
							'tipo' => $row_de['tipo_documento'],
						);
						$inser_dev = _insert($table_dev, $form_dev);
						$i++;
					}
				}
			}
		}
			*/
		if($insertar)
		{
			$xdatos['typeinfo']='Success';
			$xdatos['msg']='Corte guardado correctamente !';
			$xdatos['process']='insert';
			$xdatos['id_corte']=$id_cortex;
		}
		else
		{
			$xdatos['typeinfo']='Error';
		 	$xdatos['msg']='Error al guardar el corte !'._error();
		}
/*	}
	else
	{
		$xdatos['typeinfo']='Error';
		$xdatos['msg']='Ya existe un corte con esta apertura!';
	}*/
	echo json_encode($xdatos);
}


		if(!isset($_REQUEST['process'])){
			initial();
		}
		else
		{
			if(isset($_REQUEST['process'])){
				switch ($_REQUEST['process']) {
					case 'insert':
					corte();
					break;
					case 'total_sistema':
					//total_sistema();
					break;
					case 'imprimir':
					imprimir();
					break;
					case 'cambio':
					cambio();
					break;
					case 'datos_apertura':
					getApertura();
					break;
					case 'arqueo':
					arqueo();
					break;
					case 'finalizar_turno':
					finalizar_turno();
				  break;
				}
			}
		}
		?>
