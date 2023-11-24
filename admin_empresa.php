<?php
include_once "_core.php";

function initial() {


	$_PAGE = array ();
	$title='Configuración Empresa';
	$_PAGE ['title'] = $title;
	$_PAGE ['links'] = null;
	$_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/fileinput/fileinput.css" media="all" rel="stylesheet" type="text/css"/>';

	include_once "header.php";
	include_once "main_menu.php";
	$id_sucursal= $_SESSION['id_sucursal'];
	$text = "";

	$sql="SELECT * FROM empresa";
	$result=_query($sql);
	$count=_num_rows($result);
	$row = _fetch_array($result);
	$nombre = $row["descripcion"];
	$logo = $row["logo"];
	$razon=$row["razonsocial"];
	$direccion=$row["direccion"];
	$n_sucursal=$row["n_sucursal"];
	$telefono1=$row["telefono1"];
	$telefono2=$row["telefono2"];
	$nit=$row["nit"];
	$nrc=$row["nrc"];
	$iva=$row["iva"];
	$giro = $row["giro"];
	$monto_retencion1 = $row["monto_retencion1"];
	$monto_retencion10 = $row["monto_retencion10"];
	$monto_percepcion = $row["monto_percepcion"];

	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];

	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);
	//permiso del script

	if ($links!='NOT' || $admin=='1' ){

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
							<h5><?= $title;?></h5>
						</div>
						<div class="ibox-content">
							<form name="formulario" id="formulario">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group has-info single-line">
											<label class="control-label" for="Nombre">Descripción</label>
											<input type="text" placeholder="Digite Nombre" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre;?>">
										</div>
									</div>
									<div class="col-md-6" hidden>
										<div class="form-group has-info single-line">
											<label>Razón Social</label>
											<input type="text" placeholder="Razón Social" class="form-control dis" id="razon" name="razon" value="<?php echo $razon;?>">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12" hidden>
										<div class="form-group has-info single-line">
											<label class="control-label" for="Dirección">Dirección</label>
											<input type="text" placeholder="Dirección" class="form-control" id="direccion" name="direccion" value="<?php echo $direccion;?>">
										</div>
									</div>



								</div>
								<div class="row">
									<div class="col-md-6" hidden>
										<div class="form-group has-info single-line">
											<label>Teléfono 1</label>
											<input type="text" placeholder="Teléfono 1" class="form-control dis tel" id="telefono1" name="telefono1" value="<?php echo $telefono1;?>">
										</div>
									</div>
									<div class="col-md-6" hidden>
										<div class="form-group has-info single-line">
											<label>Teléfono 2</label>
											<input type="text" placeholder="Teléfono 2" class="form-control dis tel" id="telefono2" name="telefono2" value="<?php echo $telefono2;?>">
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6" hidden>
										<div class="form-group has-info single-line">
											<label>NIT</label>
											<input type="text" placeholder="NIT" class="form-control dis nit" id="nit" name="nit" value="<?php echo $nit;?>">
										</div>
									</div>
									<div class="col-md-6" hidden>
										<div class="form-group has-info single-line">
											<label>NRC</label>
											<input type="text" placeholder="NRC" class="form-control dis nrc" id="nrc" name="nrc" value="<?php echo $nrc;?>">
										</div>
									</div>
								</div>
								<div class="row">
									
									<div class="col-md-6" hidden>
										<div class="form-group has-info single-line">
											<label>Giro</label>
											<input type="text" placeholder="Giro" class="form-control dis" id="giro" name="giro" value="<?php echo $giro;?>">
										</div>
									</div>
								</div>
								<div class="row">
								<div class="col-md-6">
										<div class="form-group has-info single-line">
											<label>IVA</label>
											<input type="text" placeholder="IVA" class="form-control dis" id="iva" name="iva" value="<?php echo $iva;?>">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group has-info single-line">
											<label>Monto inicial de retención 1%</label>
											<input type="text" placeholder="Monto inicial de retencion 1%" class="form-control dis" id="monto_retencion1" name="monto_retencion1" value="<?php echo $monto_retencion1;?>">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group has-info single-line">
											<label>Monto inicial de percepción</label>
											<input type="text" placeholder="Monto inicial de percepción" class="form-control dis" id="monto_percepcion" name="monto_percepcion" value="<?php echo $monto_percepcion;?>">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group has-info single-line">
											<label>Monto inicial de retención 10%</label>
											<input type="text" placeholder="Monto inicial de retencion 10%" class="form-control dis" id="monto_retencion10" name="monto_retencion10" value="<?php echo $monto_retencion10;?>">
										</div>
									</div>
								</div>
								
								<div class="row">
									<div class="col-md-6">
										<div class="form-group has-info single-line">
											<label>Logo</label>
											<input type="file" name="logo" id="logo" class="file" data-preview-file-type="image">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group has-info">
											<img id="logo_view" name="logo_view" src="<?php echo $logo;?>" style='width: 200px; height: 100px;'>
											<input type="hidden" name="logo_v" value="<?php echo $logo;?>">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<input type="hidden" name="process" id="process" value="editar">
										<input type="hidden" name="id_sucursale" id="id_sucursale" value="<?php echo $id_sucursal;?>">
											<input type="hidden"  id="n_sucursal" name="n_sucursal" value="1">
										<input type="submit" id="submit1" name="submit1" value="Guardar" class="btn btn-primary m-t-n-xs pull-right" />
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>

			<?php
			include_once ("footer.php");
			$uniqueId=uniqidReal();
			echo "<script src='js/funciones/funciones_empresa.js?v=$uniqueId'></script>";
		} //permiso del script
		else {
			echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
		}

	}

	function editar()
	{
		require_once 'class.upload.php';
		$id_sucursale = $_POST["id_sucursale"];
		$nombre=$_POST["nombre"];
		$razon=$_POST["razon"];
		$direccion=$_POST["direccion"];
		$telefono1=$_POST["telefono1"];
		$telefono2=$_POST["telefono2"];
		$nit=$_POST["nit"];
		$nrc=$_POST["nrc"];
		$iva=$_POST["iva"];
		$giro = $_POST["giro"];
		$monto_retencion1 = $_POST["monto_retencion1"];
		$monto_retencion10 = $_POST["monto_retencion10"];
		$monto_percepcion = $_POST["monto_percepcion"];
		$n_sucursal = $_POST["n_sucursal"];
		$url = "";

		if ($_FILES['logo']['name'] !="")
		{
			$foo = new Upload($_FILES['logo'],'es_ES');
			if ($foo->uploaded) {
				$pref = uniqid()."_";
				$foo->file_force_extension = false;
				$foo->no_script = false;
				$foo->file_name_body_pre = $pref;
				// save uploaded image with no changes
				$foo->Process('img/');
				if ($foo->processed)
				{
					$query = _query("SELECT logo FROM empresa");
					$result = _fetch_array($query);
					$urlb=$result["logo"];
					if($urlb!="")
					{
						unlink($urlb);
					}
					$cuerpo=quitar_tildes($foo->file_src_name_body);
					$cuerpo=trim($cuerpo);
					$url = 'img/'.$pref.$cuerpo.".".$foo->file_src_name_ext;
				}
				else
				{
					$xdatos['typeinfo']='Error';
					$xdatos['msg']='Error al guardar la imagen!';
				}
			}
			else
			{
				$xdatos['typeinfo']='Error';
				$xdatos['msg']='Error al subir la imagen!';
			}
		}
			$table = 'empresa';
			$form_data = array (
				'descripcion' => $nombre,
				'direccion' => $direccion,
				'telefono1' => $telefono1,
				'telefono2' => $telefono2,
				'razonsocial' => $razon,
				'nit' => $nit,
				'nrc' => $nrc,
				'iva' => $iva,
				'giro' => $giro,
				'monto_retencion1' => $monto_retencion1,
				'monto_retencion10' => $monto_retencion10,
				'monto_percepcion' => $monto_percepcion,
				'logo'=>$url,
			);
	      $q = _query("SELECT idempresa FROM empresa");
			  $r_e = _fetch_row($q);
				$id_empresa= $r_e[0];
				$where_clause = " idempresa='$id_empresa' ";
				$editar =_update($table, $form_data, $where_clause);
				if($editar)
				{
					$xdatos['typeinfo']='Success';
					$xdatos['msg']='Datos de sucursal editados correctamente!';
					$xdatos['process']='edit';
				}
				else
				{
					$xdatos['typeinfo']='Error';
					$xdatos['msg']='Datos de sucursal no pudieron ser editados sin foto!'._error();
				}

		echo json_encode($xdatos);
}


	if(!isset($_REQUEST['process'])){
		initial();
	}
	else
	{
		if(isset($_REQUEST['process'])){
			switch ($_REQUEST['process']) {
				case 'editar':
				editar();
				break;
				case 'formEdit' :
					initial();
					break;
				}
			}
		}
		?>
