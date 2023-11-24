<?php
include_once "_core.php";
function initial()
{
	$title = "Editar Usuario";
	$_PAGE = array ();
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
	$_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';

	include_once "header.php";
	include_once "main_menu.php";
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
  $id_usuario=$_REQUEST["id_usuario"];
  $id_sucursal=$_SESSION["id_sucursal"];
  $sql=_fetch_array(_query("SELECT * FROM usuario WHERE id_usuario='$id_usuario'"));
	$pass= $sql['password'];
  $id_empleado = $sql['id_empleado'];
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user, $filename);
	$sucursales=getSucursales();
	$empleados=getEmpleados();
	//permiso del script
	?>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-2">
		</div>
	</div>
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox">
					<?php if ($links!='NOT' || $admin=='1' ){ ?>
						<div class="ibox-title">
							<h5><?php echo $title; ?></h5>
						</div>
						<div class="ibox-content">
							<form name="formulario" id="formulario" autocomplete="off">
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group has-info single-line">
											<label>Nombre</label>
											<input type="text" placeholder="Nombre" class="form-control" id="nombre" name="nombre" value="<?php echo $sql['nombre'];?>">
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group has-info single-line">
											<label>Usuario</label>
											<input type="text" placeholder="Apellido" class="form-control" id="usuario" name="usuario" value="<?php echo $sql['usuario'];?>">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group has-info single-line">
											<label>Contraseña</label>
											<input type="password" placeholder="Contraseña" class="form-control" id="clave1" name="clave1" value="<?php echo $pass;?>">
											<input type="hidden" id="clave2" name="clave2" value="<?php echo $pass;?>">
										</div>
									</div>
									<div class="col-lg-2">
										<div class="form-group has-info single-line">
												<label class="control-label">Admin</label>
												<div class='checkbox i-checks'>
													<label id='frentex'>Tipo Usuario<br>
														<?php
														if($sql['admin'] == 1 ){
															echo "<input type='checkbox' checked id='adminc' name='adminc' value=''><strong> Admin</strong>";
														}else {
															echo "<input type='checkbox'  id='adminc' name='adminc' value='0'><strong> Admin</strong>";
														}
														echo "<input type='hidden' checked id='admin' name='admin' value='".$sql['admin']."'>";
														?>

													</label>
												</div>
												<input type='hidden' id='admin' name='admin' value="1">
										</div>
									</div>
									</div>
									<div class="row">
										<div class="col-md-4">
											<div class="form-group has-info single-line">
												<label>Empleado <span style="color:red;">*</span></label>
													<?php
													$select2=crear_select("id_empleado", $empleados,$id_empleado , "width:100%;");
													echo $select2;
													?>

											</div>
										</div>
										<div class="col-md-4">
												<div class="form-group single-line">
														<label for="sucursal">Asignar Sucursal<span class="text-danger">*</span></label>
															<?php if (isset($sucursales)):
																$id_sucursal=$_SESSION["id_sucursal"];
															$select=crear_select("sucursal", $sucursales, $id_sucursal, "width:100%;");
															echo $select;
															endif;
															?>
												</div>
										</div>
									</div>

									<div class="row">
									</div>
									<div>
										<input type="hidden" name="process" id="process" value="edited"><br>
											<input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $id_usuario;?>"><br>
										<input type="submit" id="submit1" name="submit1" value="Guardar" class="btn btn-primary m-t-n-xs" />
									</div>
								</form>
								</div>
					</div>
				</div>
			</div>
		</div>
<?php
		include_once ("footer.php");
		echo "<script src='js/funciones/funciones_usuarios.js'></script>";
	} //permiso del script
	else {
		echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div></div></div></div></div>";
		include_once ("footer.php");
	}
}
function editar()
{
  $id_usuario=$_POST["id_usuario"];
	$id_empleado=$_POST["id_empleado"];
	$nombre=$_POST["nombre"];
	$usuario=$_POST["usuario"];
	$clave=$_POST["clave"];
	$clave2=$_POST["clave2"];
	if($clave!=$clave2){
		$clave=MD5($_POST["clave"]);
	}
	$sucursal = $_POST["sucursal"];
	$admin=$_POST["admin"];
	$id_sucursal = $_SESSION["id_sucursal"];
    $existe_usuario =_query("SELECT id_usuario FROM usuario WHERE usuario='$usuario' AND id_usuario!='$id_usuario'");
    $numrowss=_num_rows($existe_usuario);
    if($numrowss==0)
    {
			$table = 'usuario';
			$form_data = array(
				'nombre' => $nombre,
				'usuario' => $usuario,
				'password' => $clave,
				'admin'=>$admin,
				'id_empleado'=>$id_empleado,
				'id_sucursal'=>$sucursal,
			);
			$where = "id_usuario='".$id_usuario."'";
      $update = _update($table,$form_data, $where);
      if($update)
      {
        $xdatos['typeinfo']='Success';
        $xdatos['msg']='Registro ingresado con exito!';
        $xdatos['process']='editar';
      }
      else
      {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Registro no pudo ser editato!';
      }
    }else{
      $xdatos['typeinfo']='Error';
      $xdatos['msg']='El usuario ya existe!';
    }
	echo json_encode($xdatos);
}

if(!isset($_POST['process']))
{
	initial();
}
else
{
	if(isset($_POST['process']))
	{
		switch ($_POST['process'])
		{
			case 'edited':
				editar();
				break;
		}
	}
}
?>
