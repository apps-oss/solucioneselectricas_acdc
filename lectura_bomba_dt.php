<?php
	include ("_core.php");

	$requestData= $_REQUEST;
	$fechai= $_REQUEST['fechai'];
	$fechaf= $_REQUEST['fechaf'];

	require('ssp.customized.class.php' );
	// DB table to use
	$table = 'lectura_detalle_bomba';
	// Table's primary key
	$primaryKey = 'id';

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
	$links=permission_usr($id_user,$filename);

	$id_sucursal=$_SESSION['id_sucursal'];

	$joinQuery = "
	FROM  $table AS l
	JOIN bomba AS b ON (b.id=l.id_bomba)
	";
	$extraWhere = "l.id_sucursal='$id_sucursal'
	AND l.fecha BETWEEN '$fechai' AND '$fechaf'";
	$columns = array(
	array( 'db' => '`l`.`id`', 'dt' => 0, 'field' => 'id' ),
	array( 'db' => '`b`.`numero`', 'dt' => 1, 'field' => 'numero' ),
	array( 'db' => '`b`.`descripcion`', 'dt' => 2, 'field' => 'descripcion' ),
	array( 'db' => '`l`.`combustible`', 'dt' => 3, 'field' => 'combustible' ),
	array( 'db' => "DATE_FORMAT(`l`.`fecha`,'%d-%m-%Y')", 'dt' =>4, 'field' => 'fecha', 'as' => 'fecha' ),
	array( 'db' => '`l`.`inicio_combustible`', 'dt' => 5, 'field' => 'inicio_combustible' , 'as' => 'inicio_combustible' ),
	array( 'db' => '`l`.`fin_combustible`', 'dt' => 6, 'field' => 'fin_combustible' , 'as' => 'fin_combustible' ),
	array( 'db' => '`l`.`galones`', 'dt' => 7, 'field' => 'galones' , 'as' => 'galones' ),
	array( 'db' => '`l`.`id`', 'dt' =>8, 'formatter' => function( $id, $row ){
		$menudrop="<div class='btn-group'>
			<a href='#' data-toggle='dropdown' class='btn btn-primary dropdown-toggle'><i class='fa fa-user icon-white'></i> Menu<span class='caret'></span></a>
			<ul class='dropdown-menu dropdown-primary'>";
			include ("_core.php");
			$id_user=$_SESSION["id_usuario"];
			$id_sucursal=$_SESSION['id_sucursal'];

				$filename='ver_bomba.php';
				$link=permission_usr($id_user,$filename);
				if ($link!='NOT' || $admin=='1' )
				{
					$menudrop.="<li><a data-toggle='modal' href='$filename?id_bomba=$id_bomba&numero_doc=$numero_doc&id_sucursal=$id_sucursal'  data-target='#viewModallt' data-refresh='true'><i class=\"fa fa-check\"></i> Ver bomba</a></li>";
				}

			$menudrop.="</ul>
		</div>";
		return $menudrop;},
		'field' => 'id_bomba' ),

	);
	//echo json_encode(
	//SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having )
	echo json_encode(
		SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
	);

?>
