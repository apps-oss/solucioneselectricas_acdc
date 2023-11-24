<?php
include_once "_core.php";
function initial()
{
    $title = 'Editar Cliente';
    $_PAGE = array();
    $_PAGE ['title'] = $title;
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
    $_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
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

    $id_cliente = $_REQUEST["id_cliente"];
    $sql = _query("SELECT * FROM cliente WHERE id_cliente='$id_cliente'");
    $datos = _fetch_array($sql);

    $nombre = $datos["nombre"];
    $negocio = $datos["negocio"];
    $direccion = $datos["direccion"];
    $departamento = $datos["depto"];
    $municipio = $datos["municipio"];
    $dui = $datos["dui"];
    $nit = $datos["nit"];
    $nrc = $datos["nrc"];
    $giro = $datos["giro"];
    $categoria = $datos["categoria"];
    $retiene = $datos["retiene"];
    $retiene10 = $datos["retiene10"];
    $percibe = $datos["percibe"];
    $telefono1 = $datos["telefono1"];
    $telefono2 = $datos["telefono2"];
    $codigocliente = $datos["codcliente"];
    $email = $datos["email"];
    $fecha_nac = $datos["fecha_nac"];
    $id_vendedor = $datos["id_vendedor"];
    $dias_credito = $datos["dias_credito"];
    $limite_credito= $datos["limite_credito"];
    $no_retiene = 0;
    $retie = 0;
    if ($percibe == 0 && $retiene == 0 && $retiene10 == 0) {
        $no_retiene = 1;
    }
    if ($retiene == 1 || $retiene10 == 1) {
        $retie = 1;
    }
    $cod_act_eco=$datos["cod_act_eco"];
    $q_act_eco="SELECT descripcion from giroMH WHERE codigo='$cod_act_eco'";
    $r_act_eco= _query($q_act_eco);
    $row_act_eco=_fetch_row($r_act_eco);
    $descrip_act_eco= $row_act_eco[0]; ?>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-2">
		</div>
	</div>
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox">
					<?php
                    //permiso del script
                    if ($links!='NOT' || $admin=='1') {
                        ?>
						<div class="ibox-title">
							<h5><?php echo $title; ?></h5>
						</div>
						<div class="ibox-content">
							<form name="formulario" id="formulario">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group has-info single-line">
											<label>Nombre  <span style="color:red;">*</span></label>
											<input type="text" placeholder="Nombre del cliente" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre; ?>">
										</div>
									</div>
									<div class="col-md-12">
                    <div class="form-group has-info single-line">
                      <label>Nombre (Empresa o Negocio) <span style="color:red;">*</span></label>
                      <input type="text" placeholder="Nombre del Negocio" class="form-control" id="negocio" name="negocio" value="<?php echo $negocio; ?>">
                    </div>
                  </div>
									<div class="col-lg-12">
										<div class="form-group has-info single-line">
											<label>Dirección</label>
                      <textarea placeholder="Dirección" class="form-control" id="direccion" name="direccion" rows="3" cols="80"><?php echo $direccion; ?></textarea>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3">
									<div class="form-group has-info single-line">
                                    <label>Departamento <span style="color:red;">*</span></label>
                                    <?php
                                $array0 =getDepartamentos();
                        $select0=crear_select("departamento", $array0, $departamento, "width:100%;");
                        echo $select0; ?>
                                    
                                </div>
									</div>
									<div class="col-md-3">
									<div class="form-group has-info single-line">
                                    <label>Municipio <span style="color:red;">*</span></label>
                                    <?php
                                $array1 =getMunicipios($departamento);
                        $select1=crear_select("municipio", $array1, $municipio, "width:100%;");
                        echo $select1; ?> 
                                  
                                </div>
									</div>
									<div class="col-md-3">
										<div class="form-group has-info single-line">
											<label>Categoria del Cliente <span style="color:red;">*</span></label>
											<select class="col-md-12 select" id="categoria" name="categoria">
												<?php
                                                $sqld = "SELECT * FROM categoria_proveedor";
                        $resultd=_query($sqld);
                        while ($depto = _fetch_array($resultd)) {
                            echo "<option value='".$depto["id_categoria"]."'";
                            if ($categoria == $depto["id_categoria"]) {
                                echo " selected ";
                            }
                            echo">".$depto["nombre"]."</option>";
                        } ?>
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group has-info single-line">
											<label>DUI</label>
											<input type="text" placeholder="00000000-0" class="form-control" id="dui" name="dui" value="<?php echo $dui; ?>">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3">
										<div class="form-group has-info single-line">
											<label>NIT  <span style="color:red;">*</span></label>
											<input type="text" placeholder="0000-000000-000-0" class="form-control" id="nit" name="nit" value="<?php echo $nit; ?>">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group has-info single-line">
											<label>NRC  <span style="color:red;">*</span></label>
											<input type="text" placeholder="Registro" class="form-control" id="nrc" name="nrc" value="<?php echo $nrc; ?>">
										</div>
									</div>
									<div class="col-md-3">
									<div class="form-group has-info single-line">
                                    <label>Giro  <span style="color:red;">*</span></label>
                                    <select class="col-md-12 select" id="sel_giro" name="sel_giro">
                                        <option value='<?php echo $cod_act_eco; ?>'><?php echo $descrip_act_eco; ?></option>
                                    </select>
                                </div>
									</div>
									<div class="col-md-3">
										<div class="form-group has-info single-line">
											<div class='checkbox i-checks'><label><input id='retiene' name='retiene' type='checkbox' <?php if ($retiene || $retiene10) {
                            echo " checked ";
                        } ?>> <span class="label-text"><b>Retiene</b></span></label></div>
											<input type="hidden" name="hi_retiene" id="hi_retiene" value="<?php echo $retie; ?>">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3" id="retiene_select" <?php if (!$retiene && !$retiene10) {
                            echo " hidden ";
                        } ?>>
										<div class="form-group has-info single-line">
											<label>Porcentaje de Retención <span style="color:red;">*</span></label>
											<select class="col-md-12 select" id="porcentaje" name="porcentaje">
												<option value="0">Sin Retención</option>
												<option value="1" <?php if ($retiene) {
                            echo " selected ";
                        } ?>>1%</option>
												<option value="10" <?php if ($retiene10) {
                            echo " selected ";
                        } ?>>10%</option>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3">
										<div class="form-group has-info single-line">
											<label>Teléfono 1 <span style="color:red;">*</span></label>
											<input type="text" placeholder="0000-0000" class="form-control tel" id="telefono1" name="telefono1" value="<?php echo $telefono1; ?>">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group has-info single-line">
											<label>Teléfono 2</label>
											<input type="text" placeholder="0000-0000" class="form-control tel" id="telefono2" name="telefono2" value="<?php echo $telefono2; ?>">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group has-info single-line">
											<label>codigo cliente</label>
											<input type="text" placeholder="" class="form-control" id="codigocliente" name="codigocliente" value="<?php echo $codigocliente; ?>">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group has-info single-line">
											<label>Correo</label>
											<input type="text" placeholder="mail@server.com" class="form-control" id="correo" name="correo" value="<?php echo $email; ?>">
										</div>
									</div>
								</div>
								<div class="row">
	                  <div class="col-md-3 form-group">
	                    <label>Vendedor</label>
	                  <select class="form-control select" name="id_vendor" id="id_vendor">
	                    <!--option value="0">Seleccione</option-->
	                    <?php
                                            /*
                                            echo "<option value='".$depto["id_municipio"]."'";
                                            if($municipio == $depto["id_municipio"])
                                            {
                                                echo " selected ";
                                            }
                                            echo">".$depto["nombre_municipio"]."</option>";*/
                        $rowV = getVendedor();
                        $nr=_num_rows($rowV);
                        for ($i=0;$i<$nr;$i++) {
                            $row = _fetch_array($rowV);
                            echo "<option value='".$row['id_empleado']."'";
                            if ($id_vendedor == $row["id_empleado"]) {
                                echo " selected ";
                            }
                            echo ">".utf8_decode(Mayu(utf8_decode($row['nombre'])));
                            echo "</option>";
                        } ?>
	                  </select>
	                </div>
	                <div class='col-lg-3'>
	                  <div class='form-group has-info'>
	                    <label>Fecha Nacimiento:</label>
	                    <input type='text' class='datepick form-control'  id='fecha1' name='fecha1'  value="<?php echo $fecha_nac; ?>">
	                  </div>
	                </div>
									<div class='col-lg-3'>
										<div class='form-group has-info'>
											<label>Días Crédito:</label>
											<input type='text' class='form-control numeric'  id='dias_credito' name='dias_credito'  value="<?php echo $dias_credito; ?>">
										</div>
									</div>
									<div class='col-lg-3'>
										<div class='form-group has-info'>
											<label>Limite Crédito:</label>
											<input type='text' class='form-control decimal'  id='limite_credito' name='limite_credito'  value="<?php echo $limite_credito; ?>">
										</div>
									</div>
                </div>

								<!--div class="panel-body">
                      <div class="panel-group" id="accordion">
                          <div class="panel panel-default">
                              <div class="panel-heading">
																<h5  class="panel-title text-center text-success" ><i class="fa fa-plus"></i>
																				<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
																				class="collapsed" aria-expanded="false"
																				style="font-weight: 700; font-size: 14px; ">
																					Registro de Embarcaciones</a>
																</h5>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse">
                                  <div class="panel-body">
                                    	<!--?php getDif($id_cliente) ?-->
                                  <!--/div>
                              </div>
                          </div>
                      </div>
                  </div-->

								<input type="hidden" name="id_cliente" id="id_cliente" value="<?php echo $id_cliente; ?>">
								<input type="hidden" name="dif_eliminados" id="dif_eliminados" value="">
								<input type="hidden" name="process" id="process" value="edit"><br>
								<div>
									<input type="submit" id="submit1" name="submit1" value="Guardar" class="btn btn-primary m-t-n-xs" />
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
        include_once("footer.php");
                        $uniqueId=uniqidReal();
                        echo "<script src='js/funciones/funciones_cliente.js?v=$uniqueId'></script>";
                    } //permiso del script
    else {
        echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
    }
}

function insertar()
{
    $id_cliente=$_POST["id_cliente"];
    $nombre=$_POST["nombre"];
    $negocio=$_POST["negocio"];
    $direccion=$_POST["direccion"];
    $departamento=$_POST["departamento"];
    $municipio=$_POST["municipio"];
    $dui=$_POST["dui"];
    $nit=$_POST["nit"];
    $nrc=$_POST["nrc"];
    $giro="";
    $categoria=$_POST["categoria"];
    $porcentaje=$_POST["porcentaje"];

    if ($porcentaje == 1) {
        $retiene = 1;
        $retiene10 = 0;
    } elseif ($porcentaje == 0) {
        $retiene = 0;
        $retiene10 = 0;
    } else {
        $retiene = 0;
        $retiene10 = 1;
    }
    if (isset($_POST['percibe'])) {
        $percibe = 1;
    } else {
        $percibe = 0;
    }

    $telefono1=$_POST["telefono1"];
    $telefono2=$_POST["telefono2"];
    $codigocliente=$_POST["codigocliente"];
    $correo=$_POST["correo"];
    $id_vendedor=$_POST["id_vendor"];
    if (isset($_POST['fecha1'])) {
        $fecha_nac = $_POST['fecha1'];
    }
    $dias_credito=$_POST["dias_credito"];
    $limite_credito = $_POST["limite_credito"];
    $sql_exis=_query("SELECT id_cliente FROM cliente WHERE nombre='$nombre' AND nit ='$nit' AND id_cliente != '$id_cliente'");
    $num_exis = _num_rows($sql_exis);

    $array = $_POST["valjson"];
    $data = json_decode($array, true);
    $eliminar = $_POST["elimina"]; //pendiente hacer eliminacion !!!!
    $cod_act_eco=$_POST["sel_giro"];
    if ($cod_act_eco!="") {
        $giro= getDescripGiro($cod_act_eco);
    }

    if ($num_exis > 0) {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Ya se registro un cliente con estos datos!';
    } else {
        $table = 'cliente';
        $form_data = array(
            'categoria' => $categoria,
            'nombre' => $nombre,
            'negocio' => $negocio,
            'direccion' => $direccion,
            'municipio' => $municipio,
            'depto' => $departamento,
            'nrc' => $nrc,
            'nit' => $nit,
            'dui' => $dui,
            'giro' => $giro,
            'telefono1' => $telefono1,
            'telefono2' => $telefono2,
            'codcliente' => $codigocliente,
            'email' => $correo,
            'percibe' => $percibe,
            'retiene' => $retiene,
            'retiene10' => $retiene10,
            'fecha_nac'=> $fecha_nac,
            'id_vendedor' => $id_vendedor,
            'dias_credito'=>$dias_credito,
            'limite_credito' => $limite_credito,
            'cod_act_eco' => $cod_act_eco,
        );
        $where = "id_cliente='".$id_cliente."'";
        $upadte = _update($table, $form_data, $where);
        //dif
        $table2='cliente_dif';
        foreach ($data as $fila) {
            $num_dif = $fila['num_dif'];
            $id_dif = $fila['id_dif'];
            $embarc  = $fila['embarc'];
            $fi  		 = $fila['fi'];
            $ff 		 = $fila['ff'];
            $limgal	 = $fila['limgal'];
            $nuevo	 = $fila['nuevo'];
            $activo	 = $fila['estado'];
            if ($nuevo==1) {
                $activo =1;
            }
            $form_data2 = array(
            'id_cliente'   => $id_cliente,
            'numero_dif'   => $num_dif,
            'embarcacion'   => $embarc,
            'fecha_inicio' => $fi,
            'fecha_fin'    => $ff,
            'limite_galon' => $limgal,
            'activo'       => $activo,
            );
            $sql0="SELECT *
			FROM $table2
			WHERE id_dif='$id_dif'
			";
            $sql=_query($sql0);
            $row=_fetch_array($sql);
            $nrow=_num_rows($sql);

            if ($nrow==0 && $nuevo==1) {
                $update2 = _insert($table2, $form_data2);
            }
            if ($nrow>0 && $id_dif !=-1) {
                $wc ="id_dif = '$id_dif'";
                $update2 = _update($table2, $form_data2, $wc);
            }
        }
        //eliminar marcados
        if ($eliminar!="") {
            $ids=explode(",", $eliminar);
            foreach ($ids as $iddif) {
                $wcdel ="id_dif = '$iddif'";
                $delete = _delete($table2, $wcdel);
            }
        }
        if ($upadte) {
            $xdatos['typeinfo']='Success';
            $xdatos['msg']='Registro modificado con exito!'.	$msg;
            $xdatos['process']='insert';
        } else {
            $xdatos['typeinfo']='Error';
            $xdatos['msg']='Registro no pudo ser modificado !';
        }
    }
    echo json_encode($xdatos);
}
function getDif($id_cliente)
{
    $sql = "SELECT id_dif, id_cliente, numero_dif, embarcacion, fecha_inicio,
	fecha_fin, limite_galon, activo FROM cliente_dif
	WHERE id_cliente='$id_cliente'";
    $res = _query($sql);
    $nrows=_num_rows($res); ?>
  <div class="row">
    <div class="col-lg-12"><br>
      <div class=" alert-warning text-center"  style="font-weight: bold; border: 2px solid #8a6d3b; border-radius: 25px; margin-bottom:20px;">
        <h3>Registro Embarcaciones</h3>
        <h3>Documento de Identificación para la exoneración de la Contribución para la Conservación Vial (DIF)</h3>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-2">
      <div class="form-group has-info single-line">
        <!--
        SELECT id_dif, id_cliente, numero_dif, embarcacion, fecha_inicio, fecha_fin, limite_galon, activo FROM cliente_dif WHERE 1
        -->
        <label>Número DIF</label>
        <input type="text" name="numero_dif" id="numero_dif" class="form-control clear" value="">
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group has-info single-line">
        <label>Nombre Embarcación</label>
        <input type="text" name="embarcacion" id="embarcacion" class="form-control clear">
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group has-info single-line">
        <label>Fecha Ext.</label>
        <input type="text" name="fecha_inicio" id="fecha_inicio" class="form-control clear  datepick">
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group has-info single-line">
        <label>Fecha Fin</label>
        <input type="text" name="fecha_fin" id="fecha_fin" class="form-control clear datepick">
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group has-info single-line">
        <label>Límite Galón</label>
        <input type="text" name="lmite_galon" id="limite_galon" class="form-control clear decimal">
      </div>
    </div>
    <!--div  class="col-md-1">
      <div class="form-group has-info single-line">
        <label>Activo</label>
        <div class='checkbox i-checks'>
          <label>
            <input type='checkbox'  id='activo' name='activo' value='1'><i></i>
          </label>
        </div>
      </div>
    </div-->
    <div class="col-md-1">
      <div class="form-group has-info">
        <br>
        <br>
        <a  class="btn btn-success" id="add_pre"><i class="fa fa-plus"></i> Agregar</a>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <table class="table table-hover table-striped table-bordered">
        <thead>
          <tr>
            <th class="col-md-1">DIF</th>
            <th class="col-md-1">Embarcación</th>
            <th class="col-md-1">Fecha Ext.</th>
            <th class="col-md-1">Fecha Fin</th>
            <th class="col-md-1">Limite Gal.</th>
            <th class="col-md-1">Estado</th>
            <th class="col-md-1">Acción</th>
          </tr>
        </thead>
        <tbody id="presentacion_table">
				<?php	if ($nrows>0) {
        $arrayEst =array(0=>"Inactivo",1 =>'Activo');
        for ($i=0;$i<$nrows;$i++) {
            $row=_fetch_array($res);
            $est='Inactivo';
            if ($row['activo']==1) {
                $est='Activo';
            }
            $select=crear_select2("estado", $arrayEst, $row['activo'], "width:100%;");
            $nuevo  = '<input type="hidden" id="nuevo" name="nuevo" value="0">';
            $id_dif = '<input type="hidden" id="id_dif" name="id_dif" value="'.$row['id_dif'].'">';
            $numero_dif = '<input type="hidden" id="numero_dif" name="numero_dif" value="'.$row['numero_dif'].'">';
            $ndif = '<input class="form-control in" type="text" id="ndif" name="ndif" value="'.$row['numero_dif'].'">';
            $emb  = '<input class="form-control in" type="text" id="emb" name="emb" value="'.$row['embarcacion'].'">';
            $fi   = '<input class="form-control in datepick" type="text" id="fi" name="fi" value="'.$row['fecha_inicio'].'">';
            $ff   = '<input class="form-control in datepick" type="text" id="ff" name="ff" value="'.$row['fecha_fin'].'">';
            $limgal   = '<input class="form-control in" type="text" id="limgal" name="limgal" value="'.$row['limite_galon'].'">';
            echo "<tr class='exis' style='background: #BDECB6;'>";
            echo "<td class='ndiff'>".$id_dif.$numero_dif.$ndif.$nuevo."</td>";
            echo "<td class='emb'>".$emb."</td>";
            echo "<td class='fi'>".$fi."</td>";
            echo "<td class='ff'>".$ff."</td>";
            echo "<td class='limg'>".$limgal."</td>";
            echo "<td class='selct'>".$select."</td>";
            echo "<td class='text-center'><a class='elmdif' id='".$row['numero_dif']."'";
            echo " title='Eliminar'><i class='fa fa-times iconsa'></i></a> </td>";
            echo "</tr>";
        }
    } ?>
        </tbody>
      </table>
    </div>
  </div>
<?php
}
function cargar_tr_dif()
{
    $numero_dif  = $_REQUEST['numero_dif'];
    $embarcacion = $_REQUEST['embarcacion'];
    $fecha_inicio= $_REQUEST['fecha_inicio'];
    $fecha_fin   = $_REQUEST['fecha_fin'];
    $limite_galon= $_REQUEST['limite_galon'];
    $tr="";
    $arrayEst =array(0=>"Inactivo",1 =>'Activo');

    $select=crear_select2("estado", $arrayEst, 1, "width:100%;");
    $nuevo  = '<input  type="hidden" id="nuevo" name="nuevo" value="1">';
    $id_dif = '<input type="hidden" id="id_dif" name="id_dif" value="-1">';
    $numer_dif = '<input type="hidden" id="numero_dif" name="numero_dif" value="'.$numero_dif.'">';
    $ndif   = '<input class="form-control in" type="text" id="ndif" name="ndif" value="'.$numero_dif.'">';
    $emb    = '<input class="form-control in" type="text" id="emb" name="emb" value="'.$embarcacion.'">';
    $fi     = '<input class="form-control in   datepick" type="text" id="fi" name="fi" value="'.$fecha_inicio.'">';
    $ff     = '<input class="form-control in  datepick" type="text" id="ff" name="ff" value="'.$fecha_fin.'">';
    $limgal = '<input class="form-control in  decimal" type="text" id="limgal" name="limgal" value="'.$limite_galon.'">';

    $tr  = "<tr class='exis' style='background: #BDECB6;'>";
    $tr .= "<td class='ndiff'>".$id_dif.$numer_dif.$ndif.$nuevo."</td>";
    $tr .=  "<td class='emb'>".$emb."</td>";
    $tr .=  "<td class='fi'>".$fi."</td>";
    $tr .=  "<td class='ff'>".$ff."</td>";
    $tr .=  "<td class='limg'>".$limgal."</td>";
    $tr .=  "<td class='selct'>".$select."</td>";
    $tr .=  "<td class='text-center'><a class='elmdif' id='".$numero_dif."'";
    $tr .=  " title='Eliminar'><i class='fa fa-times iconsa'></i></a> </td>";
    $tr .=  "</tr>";
    $xdatos['datos']=$tr;
    echo json_encode($xdatos);
}
if (!isset($_POST['process'])) {
    initial();
} else {
    if (isset($_POST['process'])) {
        switch ($_POST['process']) {
            case 'edit':
            insertar();
            break;
            case 'cargar_tr_dif':
            cargar_tr_dif();
            break;
        }
    }
}
?>
