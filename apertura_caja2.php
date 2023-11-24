<?php
include_once "_core.php";
function initial()
{
    $title = 'Apertura de caja v 2.0';
	$_PAGE = array ();
	$_PAGE ['title'] = $title;
	$_PAGE ['links'] = null;
	$_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';

	$_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';

	include_once "header.php";
	include_once "main_menu.php";
	//permiso del script
    date_default_timezone_set('America/El_Salvador');
  $caja=0;
  if (isset($_REQUEST["id_caja"])) {
    // code...
    $caja = $_REQUEST["id_caja"];
  }

  $id_sucursal = $_SESSION["id_sucursal"];

	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);
  $cajasSucursal=getCajaSucursal($id_sucursal);
  $cajero = getCajero($id_user);
  $fecha_actual = date('Y-m-d');
  $sql_apertura = _query("SELECT * FROM apertura_caja WHERE vigente = 1 AND id_sucursal = '$id_sucursal' AND id_empleado = '$id_user' AND fecha = '$fecha_actual'");
  $cuenta_apertura = _num_rows($sql_apertura);
  //if($cuenta_apertura == 0 )
  if($cuenta_apertura >= 0 )
    {
?>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-2">
    </div>
</div>
<div class="wrapper wrapper-content">
    <div class="row">
      <div class="col-lg-12">
        <?php
            //permiso del script
            if ($links!='NOT' || $admin=='1' ){
        ?>
        <div class="ibox" id="main_view">
          <div class="ibox-title">
            <h3 class="text-success"><b><i class="mdi mdi-plus"></i> apertura Caja</b></h3>
          </div>
          <div class="ibox-content">
            <form id="formulario" >
                <?php if (isset($caja)):?>

                <div class="row">
                  <input type="hidden" name="id_usuario" id="id_usuario" value="<?=$id_user?>">
                  <div class="col-lg-6 ">
                    <div class="form-group single-line">
                      <label for="serie">Fecha</label>
                      <input type="text" name="fecha" id="fecha" class="form-control datepicker"
                      placeholder="Seleccione una fecha" value="<?=date("d-m-Y")?>"
                      required data-parsley-trigger="change">
                    </div>
                  </div>
                  <div class="col-lg-6">
                  <div class="form-group single-line">
                   <label for="nombre">Nombre</label>
                      <input type="text" name="nombre" id="nombre" class="form-control mayu"
                      placeholder="Ingrese un nombre de Usuario"  value="<?=$cajero?>" readonly
                    required data-parsley-trigger="change">
                  </div>
                </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group single-line">
                            <label for="caja">Caja Apertura<span class="text-danger">*</span></label>

                              <?php if (isset($cajasSucursal)):
                                 $select=crear_select("caja", $cajasSucursal, $id_sucursal, "width:100%;");
                                 echo $select;

                              endif; ?>

                        </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group has-info single-line">
                          <label>Monto Apertura <span style="color:red;">*</span></label>
                          <input type="text" class="form-control numeric" id="monto_apertura" name="monto_apertura"
                            required data-parsley-trigger="change">
                      </div>
                  </div>
                </div>
                  <div class="row">
                      <div class="form-actions col-lg-12">

                        <button type="submit" id="btn_add_apert" name="btn_add_apert"
                        class="btn btn-success m-t-n-xs float-right"><i
                        class="mdi mdi-content-save"></i>
                        Guardar Registro
                        </button>
                      </div>
                    </div>

              <?php else:?>
                <div class="row">

                  <div class='alert alert-warning text-center' style='font-weight: bold;'>
                    <label style='font-size: 15px;'>No hay cajas disponibles para apertura!!</label>
                    <br>
                    <label style='font-size: 15px;'>Debe de realizar un corte para poder iniciar una nueva apertura de caja.</label>

                  </div>
              </div>
              <?php endif;?>
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
    <?php
  }?>
  </div>
  </div>
</div>
<?php


	} //permiso del script
    else
    {
    		echo "<div></div><br><br><div class='alert alert-warning'>Ya hay una apertura de caja vigente. Por favor realize el corte de caja</div>";
    }
    include_once ("footer.php");
    echo "<script src='js/funciones/funciones_apertura.js'></script>";
}
function apertura()
{
    date_default_timezone_set('America/El_Salvador');
    $fecha = $_POST["fecha"];
    $empleado = $_POST["empleado"];
    $turno = $_POST["turno"];
    $monto_apertura = $_POST["monto_apertura"];
    $id_sucursal = $_POST["id_sucursal"];
    $hora_actual = date('H:i:s');
    $caja = $_POST["caja"];
    $monto_ch = $_POST["monto_ch"];
    $tabla = "apertura_caja";

    $turno_real = 1;
    $fecha_i = date('Y-m-d');
    $sql_turno = _query("SELECT * FROM apertura_caja
      WHERE fecha = '$fecha_i' AND id_sucursal='$id_sucursal' AND caja = '$caja' ORDER BY id_apertura DESC LIMIT 1");
    $cuenta_turno = _num_rows($sql_turno);
    if($cuenta_turno > 0)
    {
        $row_ap = _fetch_array($sql_turno);
        $turno_ap = $row_ap['turno'];
        $turno_real = $turno_ap + 1;

    }
    else
    {
        $turno_real = 1;
    }
    $form_data = array(
        'fecha' => $fecha,
        'id_empleado' => $empleado,
        'turno' => $turno_real,
        'monto_apertura' => $monto_apertura,
        'vigente' => 1,
        'id_sucursal' => $id_sucursal,
        'hora' => $hora_actual,
        'turno_vigente' => 1,
        'caja' => $caja,
        'monto_ch' => $monto_ch,
        'monto_ch_actual' => $monto_ch,
        );
    $sql_caja = _query("SELECT * FROM apertura_caja WHERE vigente = 1 AND id_sucursal = '$id_sucursal' AND caja = '$caja'");
    $cuenta1 = _num_rows($sql_caja);
    if($cuenta1 == 0)
    {
        $insertar = _insert($tabla, $form_data);
        if($insertar)
        {
            $id_apertura = _insert_id();
            $tabla1 = "detalle_apertura";
            $form_data1 = array(
                'id_apertura' => $id_apertura,
                'turno' => $turno_real,
                'id_usuario' => $empleado,
                'fecha' => $fecha,
                'hora' => $hora_actual,
                'vigente' => 1,
                'caja' => $caja,

                );
            $insert_de = _insert($tabla1,$form_data1);
            if($insert_de)
            {
                $xdatos['typeinfo']='Success';
                $xdatos['msg']='Apertura de caja realizada correctamente !';
                $xdatos['process']='insert';
            }
            else
            {
                $xdatos['typeinfo']='Error';
                $xdatos['msg']='Fallo agregar el turno!'._error();
            }
        }
        else
        {
            $xdatos['typeinfo']='Error';
            $xdatos['msg']='La a pertura no se pudo realizar!'._error();
        }
    }
    else
    {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Ya existe una apertura de caja vigente en esta caja!';
    }
    echo json_encode($xdatos);
}
function apertura_turno()
{
    date_default_timezone_set('America/El_Salvador');
    $fecha = date("Y-m-d");
    $hora_actual = date('H:i:s');
    $id_apertura = $_POST["id_apertura"];
    $id_detalle = $_POST["id_detalle"];
    $emp = $_SESSION["id_usuario"];

    $sql_com = _query("SELECT * FROM detalle_apertura WHERE id_detalle = '$id_detalle'");
    $cuenta =_num_rows($sql_com);
    if($cuenta == 1)
    {
        $tabla = "detalle_apertura";
        $form_data = array(
            'id_usuario' => $emp,
            );

        $where_d = "id_detalle='".$id_detalle."'";
        $update_d = _update($tabla, $form_data, $where_d);

        $tabla = "apertura_caja";
        $form_data = array(
            'id_empleado' => $emp,
            );

        $where_d = "id_apertura='".$id_apertura."'";
        $update_d = _update($tabla, $form_data, $where_d);

        if($update_d)
        {
            $xdatos['typeinfo']='Success';
            $xdatos['msg']='Turno agregado correctamente!';
            $xdatos['process']='insert';
        }
        else
        {
            $xdatos['typeinfo']='Error';
            $xdatos['msg']='Fallo al agregar el turno!'._error();
        }
    }
    else
    {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='No existe un turno para asignar!'._error();
    }
    echo json_encode($xdatos);
}

function cerrar_turno()
{
    date_default_timezone_set('America/El_Salvador');
    $fecha = date("Y-m-d");
    $hora_actual = date('H:i:s');
    $id_apertura = $_POST["id_apertura"];
    $sql_turno = _query("SELECT * FROM detalle_apertura WHERE id_apertura = '$id_apertura' ORDER BY turno DESC LIMIT 1");
    $row_turno = _fetch_array($sql_turno);
    $tuno = $row_turno["turno"];
    $id_usuario = $row_turno["id_usuario"];

    $sql_turno = _query("SELECT * FROM detalle_apertura WHERE id_apertura = '$id_apertura' AND vigente = 1 ");
    $row_turno = _fetch_array($sql_turno);
    $id_detalle = $row_turno["id_detalle"];
    //echo "ok";
    $n_tuno = $tuno + 1;
    $tabla = "detalle_apertura";
    $form_data = array(
        'vigente' => 0
        );
    $where_up = "id_detalle='".$id_detalle."'";
    $update = _update($tabla, $form_data, $where_up);
    if($update)
    {
        $tabla1 = "detalle_apertura";
        $form_data1 = array(
            'id_apertura' => $id_apertura,
            'turno' => $n_tuno,
            'fecha' => $fecha,
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
            if($update1)
            {
                $xdatos['typeinfo']='Success';
                $xdatos['msg']='Turno agregado correctamente!';
                $xdatos['process']='insert';
            }
        }
    }
    else
    {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Fallo al agregar el turno!'._error();
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
        	case 'insert':
                apertura();
                break;
            case 'apertura_turno':
                apertura_turno();
                break;
            case 'cerrar_turno':
                cerrar_turno();
                break;
        }
    }
}
?>
