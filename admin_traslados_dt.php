<?php
include("_core.php");
$requestData= $_REQUEST;
require('ssp.customized.class.php');
// DB table to use
$table = 'traslado';
$origen = $_REQUEST['origen'];
$pro    = $_REQUEST['pro'];
$estado = $_REQUEST['estado'];
// Table's primary key
$primaryKey = 'id_traslado';
// MySQL server connection information
$sql_details = array(
  'user' => $username,
  'pass' => $password,
  'db'   => $dbname,
  'host' => $hostname
);
$joinQuery="";
$joinQuery="
FROM traslado
LEFT JOIN usuario ON usuario.id_usuario=traslado.id_empleado_envia
LEFT JOIN usuario as us ON us.id_usuario=traslado.id_empleado_recibe
";
$est="";
switch ($estado) {
  case 'fi':
    $est=" AND traslado.anulada=0 AND traslado.finalizada=1 ";
    break;
  case 'an':
    $est=" AND traslado.anulada=1 AND traslado.finalizada=0 ";
    break;
  case 'pe':
    $est=" AND traslado.anulada=0 AND traslado.finalizada=0 ";
    break;
  case 'gu':
    $est=" AND traslado.anulada=0 AND traslado.finalizada=0 ";
    $joinQuery="
    FROM traslado_g as traslado
    LEFT JOIN usuario ON usuario.id_usuario=traslado.id_empleado_envia
    LEFT JOIN usuario as us ON us.id_usuario=traslado.id_empleado_recibe
    ";
    break;
  default:
    break;
}
$ubi="";
$extraWhere="";
if ($pro=="gen") {
  if ($origen=="gen") {
    if ($estado=="pe") {
      $extraWhere="  traslado.id_sucursal_destino=$_SESSION[id_sucursal]  $ubi  $est ";
    }
    else
    {
      $extraWhere="  traslado.id_sucursal_destino=$_SESSION[id_sucursal]  $ubi  $est ";
    }
  }
  else {
    $ubi="AND traslado.id_origen=$origen";
    $extraWhere="  traslado.id_sucursal_origen=$_SESSION[id_sucursal]  $ubi  $est ";
  }
}

if ($pro=="env") {
  if ($origen!="gen") {
    $ubi="AND traslado.id_origen=$origen";
  }
  $extraWhere="  traslado.id_sucursal_origen=$_SESSION[id_sucursal] $ubi  $est ";
}
if ($pro=="rec") {
  if ($origen!="gen") {
    $ubi="AND traslado.id_ubicacion_destino=$origen";
  }
  $extraWhere="  traslado.id_sucursal_destino=$_SESSION[id_sucursal]  $ubi  $est  ";
}

$columns = array(
  array( 'db' => 'traslado.id_traslado',  'dt' => 0, 'field' => 'id_traslado'),
  array( 'db' => 'traslado.fecha', 'dt' => 1, 'field' => 'fecha'),
  array( 'db' => 'traslado.hora', 'dt' => 2,
  'formatter' => function ($hora, $row) {

    $hora=hora($hora);
    return $hora;
    },
  'field' => 'hora'),
  array( 'db' => 'traslado.id_traslado',   'dt' => 3,
  'formatter' =>
  function ($id_traslado, $row) {

    $d="";
    if (get_es()!="gu") {
      $d = "traslado";
    }
    else {
      $d = "traslado_g as traslado";
    }
    $res=_query("SELECT CONCAT('sucursal ',' ',sucursal.direccion) as origen
    FROM $d JOIN sucursal ON traslado.id_sucursal_origen=sucursal.id_sucursal WHERE traslado.id_traslado='$id_traslado'");
    $sql_suc=_fetch_array($res);
    $a=utf8_decode(Mayu($sql_suc['origen']));
    return $a;

    },
  'field' => 'id_traslado'),
  array( 'db' => 'traslado.id_traslado',   'dt' => 4,
  'formatter' =>
  function ($id_traslado, $row) {

    $d="";
    if (get_es()!="gu") {
      $d = "traslado";
    }
    else {
      $d = "traslado_g as traslado";
    }
    $q = "SELECT CONCAT('sucursal ',' ',sucursal.direccion) as destino
    FROM $d JOIN sucursal ON traslado.id_sucursal_destino=sucursal.id_sucursal
    WHERE traslado.id_traslado=$id_traslado";
    $res=_query($q);
    $sql_suc=_fetch_array($res);
    $a=utf8_decode(Mayu($sql_suc['destino']));
    return $a;

    //  return "-";
    },
  'field' => 'id_traslado'),
  array( 'db' => 'traslado.empleado_envia',   'dt' => 5, 'field' => "empleado_envia"),
  array( 'db' => 'traslado.empleado_recibe',   'dt' => 6, 'field' => "empleado_recibe"),
  array( 'db' => 'traslado.id_traslado',   'dt' => 7,
  'formatter' => function ($id_traslado, $row) {


    $sql_tra=_fetch_array(_query("SELECT * FROM traslado WHERE id_traslado=$id_traslado"));
    $finalizada=$sql_tra['finalizada'];
    $anulada=$sql_tra['anulada'];
    $val="";
    if (get_es()!="gu") {
      // code...
      if ($anulada==0&&$finalizada==0) {

        $val="<strong class='text-info'>PENDIENTE</strong>";
      }
      if ($anulada==1&&$finalizada==0) {
        # code...
        $val="<strong class='text-danger'>NULA</strong>";
      }
      if ($anulada==0&&$finalizada==1) {
        # code...
        $val="<strong class='text-primary'>FINALIZADA</strong>";
      }
    }
    else {
      // code...
      $val="<strong class='text-danger'>GUARDADO</strong>";
    }


    return $val;
    },
   'field' => 'id_traslado'),
  array( 'db' => 'traslado.id_traslado','dt' => 8,
  'formatter' => function ($id_traslado, $row) {

      if (get_es()!='gu') {
        $sql_tra=_fetch_array(_query("SELECT * FROM traslado WHERE id_traslado=$id_traslado"));
        $finalizada=$sql_tra['finalizada'];
          $anulada=$sql_tra['anulada'];
        $id_sucursal_envia=$sql_tra['id_sucursal_origen'];
        $id_sucursal_recibe=$sql_tra['id_sucursal_destino'];

        $menudrop="<div class='btn-group'>
        <a href='#' data-toggle='dropdown' class='btn btn-primary dropdown-toggle'><i class='fa fa-user icon-white'></i> Menu<span class='caret'></span></a>
        <ul class='dropdown-menu dropdown-primary'>";
        $id_user=$_SESSION["id_usuario"];
        $admin=$_SESSION["admin"];
        $id_sucursal=$_SESSION['id_sucursal'];
        if ($id_sucursal_recibe==$id_sucursal&&$finalizada==0&&$anulada==0) {
          $filename='recibir_traslado.php';
          $link=permission_usr($id_user, $filename);
          if ($link!='NOT' || $admin=='1') {
              $menudrop.= "<li><a href='recibir_traslado.php?id_movimiento=".$id_traslado."' ><i class=\"fa fa-plus\"></i> Recibir Traslado</a></li>";
          }
        }
        $filename='ver_traslado.php';
        $link=permission_usr($id_user, $filename);
        if ($link!='NOT' || $admin=='1') {
            $menudrop.= "<li><a data-toggle='modal'  href='ver_traslado.php?id_traslado=".$id_traslado."' data-target='#viewModal' data-refresh='true'><i class=\"fa fa-search\"></i> Detalle Traslado</a></li>";
        }
        $filename='reporte_traslado.php';
        $link=permission_usr($id_user, $filename);
        if ($link!='NOT' || $admin=='1') {
            $menudrop.= "<li><a href='reporte_traslado.php?id_traslado=".$id_traslado."' target='_blank'><i class=\"fa fa-print\"></i> Reporte Traslado</a></li>";
        }
        if ($id_sucursal_recibe==$id_sucursal&&$finalizada==1) {
          $filename='reporte_traslado_recibido.php';
          $link=permission_usr($id_user, $filename);
          if ($link!='NOT' || $admin=='1') {
              $menudrop.= "<li><a href='reporte_traslado_recibido.php?id_traslado=".$id_traslado."' target='_blank'><i class=\"fa fa-print\"></i> Reporte Traslado Recibido</a></li>";
          }
        }
        $menudrop.="</ul>
            </div>";
      }
      else {
        $menudrop="<div class='btn-group'>
        <a href='#' data-toggle='dropdown' class='btn btn-primary dropdown-toggle'><i class='fa fa-user icon-white'></i> Menu<span class='caret'></span></a>
        <ul class='dropdown-menu dropdown-primary'>";
        $menudrop.= "<li><a  href='traslado_producto.php?id_traslado=".$id_traslado."' ><i class=\"fa fa-pencil\"></i> Ver Traslado</a></li>";

        $menudrop.="</ul>
            </div>";
      }
      return $menudrop;
      }, 'field' => 'id_traslado')
        );


        echo json_encode(
          SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
        );

    function get_es()
    {
      return $stado=$_REQUEST['estado'];
    }
