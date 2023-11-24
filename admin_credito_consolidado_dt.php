<?php

include("_core.php");

$requestData= $_REQUEST;
$fechai= $_REQUEST['fechai'];
$fechaf= $_REQUEST['fechaf'];
$id_sucursal=$_SESSION['id_sucursal'];
$id_user=$_SESSION['id_usuario'];

$sql_apertura = _query("SELECT * FROM apertura_caja WHERE vigente = 1 AND id_sucursal = '$id_sucursal'  AND id_empleado='$id_user'");

$cuenta = _num_rows($sql_apertura);

$turno_vigente=0;
if ($cuenta>0) {
    $row_apertura = _fetch_array($sql_apertura);
    $id_apertura = $row_apertura["id_apertura"];
    $turno = $row_apertura["turno"];
    $fecha_apertura = $row_apertura["fecha"];
    $hora_apertura = $row_apertura["hora"];
    $turno_vigente = $row_apertura["turno_vigente"];
}
require('ssp.customized.class.php');
// DB table to use
$table = 'credito';
// Table's primary key
$primaryKey = 'id_credito';

// MySQL server connection information
$sql_details = array(
  'user' => $username,
  'pass' => $password,
  'db'   => $dbname,
  'host' => $hostname
);
//permiso del script
$id_user=$_SESSION["id_usuario"];
$admin=$_SESSION["admin"];
$uri = $_SERVER['SCRIPT_NAME'];
$filename=get_name_script($uri);
$links=permission_usr($id_user, $filename);
$id_sucursal=$_SESSION['id_sucursal'];
$joinQuery =" FROM credito AS cr
              LEFT JOIN cliente AS c ON c.id_cliente=cr.id_cliente
              ";
$extraWhere = " cr.saldo>0";
$groupBy =" c.id_cliente";
$columns = array(
  array( 'db' => '`cr`.`id_cliente`',              'dt' => 0, 'field' => 'id_cliente' ),
  array( 'db' => '`c`.`nombre`',                   'dt' => 1, 'field' => 'nombre'),
  array( 'db' => 'FORMAT(COALESCE(SUM(`cr`.`saldo`),0),2)',    'dt' => 2, 'field' => 'saldo' , 'as' => 'saldo' ),
  array( 'db' => 'COALESCE(COUNT(`cr`.`id_factura`),0)','dt' => 3, 'field' => 'n_facturas', 'as' => 'n_facturas' ),
  array( 'db' => '`cr`.`id_cliente`', 'dt' => 4, 'formatter' => function ($id_cliente, $row) {
      $menudrop="<div class='btn-group'>
    <a href='#' data-toggle='dropdown' class='btn btn-primary dropdown-toggle'><i class='fa fa-user icon-white'></i> Menu<span class='caret'></span></a>
    <ul class='dropdown-menu dropdown-primary'>";
      $admin=$_SESSION["admin"];
      $filename='abonar_credito.php';
      $link=permission_usr($id_user, $filename);
      if ($link!='NOT' || $admin=='1') {
          $menudrop.= "<li><a data-toggle='modal' href='$filename?id_cliente=$id_cliente&id_sucursal=$id_sucursal' data-target='#viewModal' data-refresh='true' ><i class='fa fa-money'></i> Abonar</a></li>";
      }
      $menudrop.="</ul>
      </div>";
      return $menudrop;
  },
      'field' => 'id_cliente' ),
    );
  echo json_encode(
      SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy)
  );
