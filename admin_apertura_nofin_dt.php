<?php
	include ("_core.php");

	$requestData= $_REQUEST;
	$fechai= $_REQUEST['fechai'];
	$fechaf= $_REQUEST['fechaf'];

	require('ssp.customized.class.php' );
	// DB table to use
	$table = 'factura';
	// Table's primary key
	$primaryKey = 'id_apertura';

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

	$joinQuery = " FROM  apertura_caja AS ac
	JOIN usuario AS usr ON (ac.id_empleado=usr.id_usuario)
	 ";
	 $extraWhere = " ac.id_sucursal='$id_sucursal'
	 AND ac.fecha BETWEEN '$fechai' AND '$fechaf' AND ac.vigente=0 AND cortado=0 and id_corte=0";

	$columns = array(
	array( 'db' => '`ac`.`id_apertura`', 'dt' => 0, 'field' => 'id_apertura' ),
	array( 'db' => '`ac`.`caja`', 'dt' => 1, 'field' => 'caja' ),
	array( 'db' => '`usr`.`nombre`', 'dt' => 2, 'field' => 'nombreuser' , 'as' => 'nombreuser' ),
	array( 'db' => "DATE_FORMAT(`ac`.`fecha`,'%d-%m-%Y')", 'dt' =>3, 'field' => 'fecha', 'as' => 'fecha' ),
	array( 'db' => '`ac`.`hora`', 'dt' => 4, 'field' => 'hora' , 'as' => 'hora'),

	array( 'db' => '`ac`.`id_apertura`', 'dt' =>5, 'formatter' => function( $id_apertura, $row ){
		$menudrop="<div class='btn-group'>
		<a href='#' data-toggle='dropdown' class='btn btn-primary dropdown-toggle'><i class='fa fa-user icon-white'></i> Menu<span class='caret'></span></a>
		<ul class='dropdown-menu dropdown-primary'>";
		$id_user=$_SESSION["id_usuario"];
		$id_sucursal=$_SESSION['id_sucursal'];
		$admin=$_SESSION["admin"];
		$filename='corte_caja_pendiente.php';
		$dat_ap=getDatosApNoVigente($id_apertura);
		$caja= $dat_ap['caja'];
		$dats_caja = getCaja($caja);
		$tipo_caja =$dats_caja['tipo_caja'];

		$link=permission_usr($id_user,$filename);
		if ($link!='NOT' || $admin=='1' ){
			$menudrop.="<li><a  href='$filename?aper_id=".$id_apertura."'><i class='fa fa-check'></i> Realizar Corte</a></li>";
		}
		if($tipo_caja==2){
			$filename='venta_pista.php';
			$link=permission_usr($id_user,$filename);
			if ($link!='NOT' || $admin=='1' ){
				$menudrop.="<li><a  href='$filename?id_apertura=".$id_apertura."'><i class='fa fa-money'></i> Venta Corte Pendiente</a></li>";
			}
		}

		$menudrop.="</ul>
		</div>";
		return $menudrop;},
		'field' => 'id_apertura' ),
	);

	echo json_encode(
		SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
	);

?>
