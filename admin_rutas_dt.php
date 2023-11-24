<?php
 include ("_core.php");
 $requestData= $_REQUEST;
 require('ssp.customized.class.php' );
// DB table to use
$table = 'ruta';
/*
$hostname = "localhost";
$username = "libreria";
$password = "L1br3r1@18";
$dbname
*/
// Table's primary key
$primaryKey = 'id_ruta';
 // MySQL server connection information
 $sql_details = array(
 'user' => $username,
 'pass' => $password,
 'db'   => $dbname,
 'host' => $hostname
 );
	$joinQuery=" FROM ruta as r ";
	$extraWhere="";

$columns = array(
    array( 'db' => '`r`.`id_ruta`', 'dt' => 0, 'field' => 'id_ruta'),
    array( 'db' => '`r`.`descripcion`',  'dt' => 1, 'field' => 'descripcion'),
    array( 'db' => 'r.id_ruta','dt' => 2,
        'formatter' => function( $id_ruta, $row ) {
       	$menudrop="<div class='btn-group'>
			<a href='#' data-toggle='dropdown' class='btn btn-primary dropdown-toggle'><i class='fa fa-user icon-white'></i> Menu<span class='caret'></span></a>
			<ul class='dropdown-menu dropdown-primary'>";
			$id_user=$_SESSION["id_usuario"];
			$admin=$_SESSION["admin"];
			$filename='anular_factura.php';
			$link=permission_usr($id_user,$filename);
			$filename='editar_ruta.php';
			$link=permission_usr($id_user,$filename);
			if ($link!='NOT' || $admin=='1' ){
			$menudrop.="<li><a href=\"editar_ruta.php?id_ruta=".$row['id_ruta']."\"><i class=\"fa fa-pencil\"></i> Editar</a></li>";
								    }

								    $link=permission_usr($id_user,$filename);
								    if ($link!='NOT' || $admin=='1' ){
		  $menudrop.="<li><a class='btn borr' id='$id_ruta' ><i class='fa fa-trash'></i>  Eliminar</a></li>";			    }
								     $filename='ver_ruta.php';
								     $link=permission_usr($id_user,$filename);
								    if ($link!='NOT' || $admin=='1' ){
			$menudrop.= "<li><a data-toggle='modal' href='ver_ruta.php?id_ruta=".$row['id_ruta']."' data-target='#viewModal' data-refresh='true'><i class=\"fa fa-search\"></i> Ver Detalle</a></li>";
								    }

			$menudrop.="</ul>
						</div>";
		return $menudrop;}, 'field' => 'id_ruta')
		);
	echo json_encode(
		SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
	);
?>
