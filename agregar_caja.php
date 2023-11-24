<?php
include_once "_core.php";
function initial() {


	$_PAGE = array ();
	$_PAGE ['title'] = 'Agregar Caja';
	$_PAGE ['links'] = null;
	$_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';

	include_once "header.php";
	include_once "main_menu.php";
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$uri=$_SERVER['REQUEST_URI'];
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);
	$fecha_actual = date("d-m-Y");
	//permiso del script
	$sucursales=getSucursales();
	if ($links!='NOT' || $admin=='1' ){
?>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-2">

        </div>
    </div>

				<div class="wrapper wrapper-content">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox" id="main_view">
        <div class="ibox-title">
          <h3 class="text-success"><b><i class="mdi mdi-plus"></i> Agregar Caja</b></h3>
        </div>
        <div class="ibox-content">
          <!--form id="form_add" novalidate-->
						  <form name="formulario" id="formulario">
            <div class="row">
            <div class="col-lg-4">
              <div class="form-group single-line">
               <label for="nombre">Nombre</label>
                  <input type="text" name="name_caja" id="name_caja" class="form-control mayu"
                  placeholder="Ingrese un nombre de Caja"  >
              </div>
            </div>
            <div class="col-lg-4">
              <div class="form-group single-line">
                <label for="serie">Serie</label>
                <input type="text" name="serie" id="serie" class="form-control mayu"
                placeholder="Ingrese la serie de Caja"
              >
            </div>
            </div>
            <div class="col-lg-4">
              <div class="form-group  single-line">
                <label for="fecha">Fecha de Resoluci&oacute;n</label>
                <input type="text" name="fecha" id="fecha" class="form-control datepicker"
                placeholder="Seleccione una fecha" value="<?php echo $fecha_actual;?>"
                >
              </div>
            </div>
          </div>
          <!--	//id_caja, nombre, serie, desde, hasta, correlativo_dispo, resolucion, fecha, id_sucursal, activa-->
          <div class="row">
          <div class="col-lg-4">
            <div class="form-group single-line">
             <label for="desde">Desde</label>
                <input type="text" name="desde" id="desde" class="form-control numeric"
                placeholder="Ingrese nuerro de inicio"
              >
            </div>
          </div>
          <div class="col-lg-4">
            <div class="form-group single-line">
              <label for="hasta">Hasta</label>
              <input type="text" name="hasta" id="hasta" class="form-control numeric"
              placeholder="Ingrese numero de finalizacion"
              >
            </div>
          </div>
          <div class="col-lg-4">
              <div class="form-group single-line">
                  <label for="sucursal">Sucursal para Caja<span class="text-danger">*</span></label>
                    <?php if (isset($sucursales)):
                    $select=crear_select2("sucursal", $sucursales, $id_sucursal, "width:100%;");
										echo $select;
                    endif;
										?>
              </div>
          </div>

        </div>
        <!--	// correlativo_dispo, resolucion, fecha, id_sucursal, activa-->
        <div class="row">
        <div class="col-lg-6">
          <div class="form-group single-line">
           <label for="correlativo_dispo">correlativo disponible</label>
              <input type="text" name="correlativo_dispo" id="correlativo_dispo" class="form-control mayu"
              placeholder="Ingrese nuerro de inicio"
             data-parsley-trigger="change">
          </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group single-line">
            <label for="resolucion"> Resolucion</label>
            <input type="text" name="resolucion" id="resolucion" class="form-control mayu"
            placeholder="Ingrese numero de finalizacion"
          >
        </div>
        </div>
      </div>

              <div class="row">
            <div class="form-actions col-lg-12">
							<input type="hidden" name="id_sucursal" id='id_sucursal' value="<?php echo $id_sucursal; ?>">
							  <input type="hidden" name="process" id="process" value="agregar"><br>
              <button type="submit" id="btn_add" name="btn_add_caja"
              class="btn btn-success m-t-n-xs float-right"><i
              class="mdi mdi-content-save"></i>
              Guardar Registro
            </button>
          </div>
          </div>

      </form>
    </div>

  </div>
  <div class="ibox" style="display: none;" id="divh">
    <div class="ibox-content text-center">
      <div class="row">
        <div class="col-lg-12">
          <h2 class="text-danger blink_me">Espere un momento, procesando su solicitud!</h2>
          <section class="sect">
            <div id="loader">
            </div>
          </section>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>


<?php
include_once ("footer.php");
echo "<script src='js/funciones/funciones_caja.js'></script>";
} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}
function insertar()
{
  $nombre_caja  = $_POST["nombre_caja"];
  $serie 				= $_POST["serie"];
  $desde 				= $_POST["desde"];
	$resolucion 	= $_POST["resolucion"];
  $hasta				= $_POST["hasta"];
  $id_sucursal  = $_POST["id_sucursal"];
	$fecha				= md($_POST["fecha"]);
	$correlativo_dispo = $_POST["correlativo_dispo"];

  $sql_caja = _query("SELECT * FROM caja WHERE nombre = '$nombre_caja'");
  $cuenta = _num_rows($sql_caja);
  if($cuenta == 0)
  {
    $table = 'caja';
    $form_caja = array(
      'nombre' => $nombre_caja,
			'resolucion'=>$resolucion,
      'serie'  => $serie,
      'desde'  => $desde,
      'hasta'  => $hasta,
      'correlativo_dispo' => $desde,
      'id_sucursal' => $id_sucursal,
			'activa' => 1,
    );
    $insertar = _insert($table, $form_caja);
    if($insertar)
    {
      $xdatos['typeinfo']='Success';
      $xdatos['msg']='Registro guardado con exito!';
      $xdatos['process']='insert';
    }
    else
    {
      $xdatos['typeinfo']='Error';
      $xdatos['msg']='Error al insertar el registro !'._error();
    }
  }
  else
  {
    $xdatos['typeinfo']='Error';
    $xdatos['msg']='Ya existe un registro con ese mismo nombre !';
  }
  echo json_encode($xdatos);
}

if(!isset($_POST['process'])){
	initial();
}
else
{
if(isset($_POST['process'])){
switch ($_POST['process']) {
	case 'agregar':
		insertar();
		break;

	}
}
}
?>
