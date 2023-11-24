<?php
	include ("_core.php");

	$requestData= $_REQUEST;
	$fechai= $_REQUEST['fechai'];
	$fechaf= $_REQUEST['fechaf'];

	require('ssp.customized.class.php' );
	// DB table to use
	$table = 'lectura_bomba';
	// Table's primary key
	$primaryKey = 'id_lectura';

	// MySQL server connection information
	$sql_details = array(
  'user' => $username,
  'pass' => $password,
  'db'   => $dbname,
  'host' => $hostname
  );

	//permiso del script
	/*
SELECT `id_lectura`, `gal_diesel`, `gal_regular`, `gal_super`, `total_gal`,
`dinero_diesel`, `dinero_regular`, `dinero_super`, `total_dinero`, `fecha`,
`hora_corte`, `id_sucursal`, `id_usuario` FROM `lectura_bomba`
	*/
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];

	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);

	$id_sucursal=$_SESSION['id_sucursal'];

	$joinQuery = "FROM  $table AS l ";
	$extraWhere = "l.id_sucursal='$id_sucursal'
	AND l.fecha BETWEEN '$fechai' AND '$fechaf'";
	$columns = array(
	array( 'db' => '`l`.`id_lectura`', 'dt'    => 0, 'field' => 'id_lectura' ),
	array( 'db' => '`l`.`gal_diesel`', 'dt'    => 1, 'field' => 'gal_diesel' ),
	array( 'db' => '`l`.`gal_regular`', 'dt'   => 2, 'field' => 'gal_regular' ),
	array( 'db' => '`l`.`gal_super`', 'dt'     => 3, 'field' => 'gal_super' ),
	array( 'db' => '`l`.`total_gal`', 'dt'     => 4, 'field' => 'total_gal' , 'as' => 'total_gal' ),
	array( 'db' => '`l`.`dinero_diesel`', 'dt' => 5, 'field' => 'dinero_diesel' , 'as' => 'dinero_diesel' ),
	array( 'db' => '`l`.`dinero_regular`', 'dt'=> 6, 'field' => 'dinero_regular' , 'as' => 'dinero_regular' ),
	array( 'db' => '`l`.`dinero_super`', 'dt'  => 7, 'field' => 'dinero_super' , 'as' => 'dinero_super' ),
	array( 'db' => '`l`.`total_dinero`', 'dt'  => 8, 'field' => 'total_dinero' , 'as' => 'total_dinero' ),
	array( 'db' => "DATE_FORMAT(`l`.`fecha`,'%d-%m-%Y')", 'dt' =>9, 'field' => 'fecha', 'as' => 'fecha' ),
	array( 'db' => '`l`.`id_lectura`', 'dt' =>10, 'formatter' => function( $id, $row ){
				$menudrop=headDropDown();
				include("_core.php");
				$id_user=$_SESSION["id_usuario"];
				$id_sucursal=$_SESSION['id_sucursal'];
				$admin=$_SESSION["admin"];
				$filename='reporte_lecturas_pdf.php';
				$link=permission_usr($id_user,$filename);
				if ($link!='NOT' || $admin=='1' ){
					$menudrop.= "<li><a  href='$filename?id_lectura=".$id."' target='_blank'><i class='fa fa-print'></i> Lecturas</a></li>";
				}
				$menudrop.=footDropDown();
				return $menudrop;},
				'field' => 'id_lectura' ),

	);
	//echo json_encode(
	//SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having )
	echo json_encode(
		SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
	);

?>
