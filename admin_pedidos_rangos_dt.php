<?php

    include("_core.php");

    $requestData= $_REQUEST;
    $fechai= $_REQUEST['fechai'];
    $fechaf= $_REQUEST['fechaf'];

    require('ssp.customized.class.php');
    // DB table to use
    $table = 'pedidos';
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
	FROM  pedidos  AS fac
	JOIN cliente AS cte ON (fac.id_cliente = cte.id_cliente)
	JOIN usuario AS usr ON (fac.id_usuario=usr.id_usuario)
	JOIN caja AS caj ON (caj.id_caja=fac.caja)
	JOIN tipodoc AS td ON (td.alias=fac.tipo_documento)
	";
    $extraWhere = "fac.id_sucursal='$id_sucursal'
	AND fac.fecha BETWEEN '$fechai' AND '$fechaf' AND fac.finalizada=1 AND caja!=0";
    $columns = array(
    array( 'db' => '`fac`.`id_factura`', 'dt' => 0, 'field' => 'id_factura' ),
    array( 'db' => '`fac`.`numero_doc`', 'dt' => 1, 'field' => 'numero_doc' ),
    array( 'db' => '`cte`.`nombre`', 'dt' => 2, 'field' => 'nombrecliente' , 'as' => 'nombrecliente'),
    array( 'db' => '`usr`.`nombre`', 'dt' => 3, 'field' => 'nombreuser' , 'as' => 'nombreuser' ),
    array( 'db' => "DATE_FORMAT(`fac`.`fecha`,'%d-%m-%Y')", 'dt' =>4, 'field' => 'fecha', 'as' => 'fecha' ),
    array( 'db' => 'FORMAT(ROUND(`fac`.`total`,2),2)', 'dt' =>5, 'field' => 'total', 'as' => 'total' ),
    array( 'db' => '`fac`.`id_factura`', 'dt' =>6, 'formatter' => function ($id_factura, $row) {
        $menudrop="<div class='btn-group'>
			<a href='#' data-toggle='dropdown' class='btn btn-primary dropdown-toggle'><i class='fa fa-user icon-white'></i> Menu<span class='caret'></span></a>
			<ul class='dropdown-menu dropdown-primary'>";
        include("_core.php");
        $id_user=$_SESSION["id_usuario"];
        $id_sucursal=$_SESSION['id_sucursal'];
        $admin=$_SESSION["admin"];
        $row=getPedidos($id_factura);
        $anulada=$row['anulada'];
        $finalizada=$row['finalizada'];
        $numero_doc=$row['numero_doc'];
        $id_apertura=$row['id_apertura'];
        $id_sucursal=$_SESSION['id_sucursal'];
        //permiso del script
        $id_user=$_SESSION["id_usuario"];
        $sql_apertura = _query("SELECT * FROM apertura_caja WHERE id_apertura=$id_apertura");
        $cuenta = _num_rows($sql_apertura);
        $turno_vigente=0;
        if ($cuenta>0) {
            $row_apertura = _fetch_array($sql_apertura);
            $turno_vigente = $row_apertura["vigente"];
        }


        $filename='ver_pedidos.php';
        $link=permission_usr($id_user, $filename);
        if ($link!='NOT' || $admin=='1') {
            $menudrop.="<li><a data-toggle='modal' href='$filename?id_factura=$id_factura&numero_doc=$numero_doc&id_sucursal=$id_sucursal'  data-target='#viewModalFact' data-refresh='true'><i class=\"fa fa-check\"></i> Ver Pedido</a></li>";
        }
        /*$filename='reimprimir_pedidos.php';
        $link=permission_usr($id_user, $filename);
        if ($link!='NOT' || $admin=='1') {
            if ($finalizada==1) {
                $menudrop.="<li><a data-toggle='modal' href='$filename.php?id_factura=".$id_factura."' data-target='#viewModal' data-refresh='true'><i class=\"fa fa-print\"></i> Reimprimir</a></li>";
            }
        }*/
        $filename='anular_pedidos.php';
        $link=permission_usr($id_user, $filename);
        if ($link!='NOT' || $admin=='1') {
            if ($anulada==0&&$finalizada==1) {
                if ($turno_vigente==1 || $admin=='1') {
                    $menudrop.="<li><a data-toggle='modal' href='$filename?id_factura=".$id_factura."' data-target='#viewModal' data-refresh='true'><i class=\"fa fa-close\"></i> Anular</a></li>";
                }
            }
        }
        $menudrop.="</ul>
		</div>";
        return $menudrop;
    },
        'field' => 'id_factura' ),

    );
    //echo json_encode(
    //SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having )
    echo json_encode(
        SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
    );
    function tipo_doc($id_factura)
    {
        $sql="select tipo_documento from factura where id_factura='$id_factura'";
        $result=_query($sql);
        $count=_num_rows($result);
        $row=_fetch_array($result);
        $tipo_doc=$row['tipo_documento'];

        $txt_doc="";
        if ($tipo_doc=='CCF') {
            $txt_doc="<h5 class='text-success'>".'CREDITO FISCAL'."</h5>";
        }

        if ($tipo_doc=='COF') {
            $txt_doc="<h5 class='text-warning'>".'CONSUMIDOR FINAL'."</h5>";
        }
        if ($tipo_doc=='NC' || $tipo_doc=='DEV') {
            $txt_doc="<h5 class='text-danger'>".'NOTA DE CREDITO'."</h5>";
        }

        return $txt_doc;
    }
    function estado($id_factura)
    {
        $sql="select finalizada,anulada from factura where id_factura='$id_factura'";
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
    function totalRound($id_factura)
    {
        $sql="select total,retencion from factura where id_factura='$id_factura'";
        $result=_query($sql);
        $count = _num_rows($result);
        $row = _fetch_row($result);
        $val = 	sprintf("%.2f", ($row[0]-$row[1]));
        return $val;
    }
function getPedidos($id_factura)
{
    $sql="SELECT numero_doc,finalizada,anulada,tipo,id_apertura FROM pedidos WHERE id_factura='$id_factura'";
    $result=_query($sql);
    $count=_num_rows($result);
    $row=null;
    if ($count>0) {
        $row=_fetch_array($result);
    }

    return $row;
}
