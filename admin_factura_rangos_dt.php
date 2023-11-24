<?php

    include("_core.php");
    $requestData= $_REQUEST;
    $fechai= $_REQUEST['fechai'];
    $fechaf= $_REQUEST['fechaf'];
    $caja = $_REQUEST['caja'];

    require('ssp.customized.class.php');
    // DB table to use
    $table = 'factura';
    // Table's primary key
    $primaryKey = 'id_factura';

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

    $joinQuery = "
	FROM  factura  AS fac
	LEFT JOIN cliente AS cte ON (fac.id_cliente = cte.id_cliente)
    LEFT JOIN empleado AS usr ON (fac.id_empleado=usr.id_empleado)
	";
    $extraWhere = "fac.id_sucursal='$id_sucursal'
	AND fac.fecha BETWEEN '$fechai' AND '$fechaf'
    AND fac.finalizada=1 AND caja!=0";
	if($caja != "")
	{
		$extraWhere = "fac.id_sucursal='$id_sucursal'
		AND fac.fecha BETWEEN '$fechai' 
		AND '$fechaf' 
		AND fac.finalizada=1 AND caja=$caja";
	}
    $columns = array(
    array( 'db' => '`fac`.`id_factura`', 'dt' => 0, 'field' => 'id_factura' ),
    array( 'db' => '`fac`.`numero_doc`', 'dt' => 1, 'field' => 'numero_doc' ),
    array( 'db' => '`fac`.`num_fact_impresa`', 'dt' => 2, 'field' => 'num_fact_impresa' ),
    array( 'db' => '`fac`.`tipo`', 'dt' => 3, 'field' => 'tipo' ),
    array( 'db' => '`cte`.`nombre`', 'dt' => 4, 'field' => 'nombrecliente' , 'as' => 'nombrecliente'),
    array( 'db' => '`usr`.`nombre`', 'dt' => 5, 'field' => 'nombreuser' , 'as' => 'nombreuser' ),
    array( 'db' => '`fac`.`fecha`', 'dt' =>6, 'field' => 'fecha' ),
    array( 'db' => '`fac`.`total`', 'dt' =>7, 'field' => 'total' ),
    array( 'db' => '`fac`.`id_factura`', 'dt' => 8, 'formatter' => function ($id_factura, $row) {
        $txt_estado=estado($id_factura);
        return $txt_estado;
    }, 'field' => 'id_factura'),
    array( 'db' => '`fac`.`id_factura`', 'dt' => 9, 'formatter' => function ($id_factura, $row) {
        $menudrop="<div class='btn-group'>
						<a href='#' data-toggle='dropdown' class='btn btn-primary dropdown-toggle'><i class='fa fa-user icon-white'></i> Menu<span class='caret'></span></a>
						<ul class='dropdown-menu dropdown-primary'>";

        include("_core.php");
        $id_user=$_SESSION["id_usuario"];
        $id_sucursal=$_SESSION['id_sucursal'];
        $admin=$_SESSION["admin"];
        $sql="SELECT numero_doc,finalizada,anulada,tipo_pago,tipo_documento FROM factura WHERE id_factura='$id_factura'";
        $result=_query($sql);
        $count=_num_rows($result);
        $row=_fetch_array($result);
        $anulada=$row['anulada'];
        $finalizada=$row['finalizada'];
        $numero_doc=$row['numero_doc'];
        $alias_tipodoc=$row['tipo_documento'];

        $filename='anular_factura.php';
        $link=permission_usr($id_user, $filename);
        if ($anulada==0) {
            if ($link!='NOT' || $admin=='1') {
                if ($alias_tipodoc!='DEV') {
                    $menudrop.="<li><a data-toggle='modal' href='$filename?id_factura=".$id_factura."&process=formDelete"."' data-target='#deleteModal' data-refresh='true'><i class='fa fa-eraser'></i> Anular</a></li>";
                }
            }
        }
        $filename='editar_ventas.php';
        $link=permission_usr($id_user, $filename);
        if ($link!='NOT' || $admin=='1') {
            if ($finalizada==0 && $anulada==0  && $alias_tipodoc!='DEV') {
                $menudrop.="<li><a  href='$filename?id_factura=$id_factura&numero_doc=$numero_doc&id_sucursal=$id_sucursal&process=formEdit' ><i class='fa fa-pencil'></i> Editar</a></li>";
            }
        }
        if ($alias_tipodoc!='DEV' && $alias_tipodoc!='TIK') {
            $filename='notacredito.php';
            $link=permission_usr($id_user, $filename);
            if ($link!='NOT' || $admin=='1') {
                if ($anulada==0  && $alias_tipodoc!='ND' && $alias_tipodoc!='NC' && $alias_tipodoc!='DEV' && $alias_tipodoc!='COF') {
                    $menudrop.="<li><a  href='$filename?id_factura=$id_factura' ><i class='fa fa-minus'></i> Nota de Crédito</a></li>";
                }
            }
            $filename='notadebito.php';
            $link=permission_usr($id_user, $filename);
            if ($link!='NOT' || $admin=='1') {
                if ($anulada==0  && $alias_tipodoc!='ND' && $alias_tipodoc!='NC' && $alias_tipodoc!='DEV' && $alias_tipodoc!='COF') {
                    $menudrop.="<li><a  href='$filename?id_factura=$id_factura' ><i class='fa fa-plus'></i> Nota de Débito</a></li>";
                }
            }
        }
        $filename='ver_ventas.php';
        $link=permission_usr($id_user, $filename);
        if ($link!='NOT' || $admin=='1') {
            $menudrop.="<li><a data-toggle='modal' href='$filename?id_factura=$id_factura&numero_doc=$numero_doc&id_sucursal=$id_sucursal'  data-target='#viewModalFact' data-refresh='true'><i class='fa fa-check'></i> Ver Factura</a></li>";
        }
        /*
        if ($alias_tipodoc == "TIK") {
            $filename='recibo.php';
            $link=permission_usr($id_user, $filename);
            if ($link!='NOT' || $admin=='1') {
                $menudrop.="<li><a href='$filename?id_factura=$id_factura&numero_doc=$numero_doc&id_sucursal=$id_sucursal' target='_blank'><i class='fa fa-print'></i> Imprimir recibo</a></li>";
            }
        }*/
        $menudrop.="</ul>
					</div>";
        return $menudrop;
    },
        'field' => 'id_factura' ),
    );
    echo json_encode(
        SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
    );
    function estado($id_pedido)
    {
        $sql="select finalizada,anulada from factura where id_factura='$id_pedido'";
        $result=_query($sql);
        $count=_num_rows($result);
        $row=_fetch_array($result);
        $anulada=$row['anulada'];
        $finalizada=$row['finalizada'];
        $txt_estado="";
        if ($finalizada==1 && $anulada==0) {
            $txt_estado="<h5 class='text-mutted'>".'FINALIZADA'."</h5>";
        }

        if ($finalizada==0 && $anulada==1) {
            $txt_estado="<h5 class='text-warning'>".'NULA'."</h5>";
        }

        if ($finalizada==1 && $anulada==1) {
            $txt_estado="<h5 class='text-warning'>".'NULA'."</h5>";
        }

        if ($finalizada==0 && $anulada==0) {
            $txt_estado="<h5 class='text-danger'>".'PENDIENTE'."</h5>";
        }

        return $txt_estado;
    }
