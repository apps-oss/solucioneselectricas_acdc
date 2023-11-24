<?php
require_once "_conexion.php";
/*
CONSTANTES PARA USO DE IMPRESION MATRICIAL
*/
define("LINEINCH6", "". chr(27).chr(50));//6 LINES inch  matrix
define("LINEINCH8", "". chr(27).chr(48));//8 LINES inch  matrix
define("LINEINCH9", "". chr(27).chr(51)."2");
define("CMODE", "".chr(15)); //condensed matrix
define("NCMODE", "".chr(18)); //condensed matrix
define("ELITE12", "".chr(27).chr(77)); //Select elite width (12 cpi)  matrix
define("SANSERIF", "".chr(27).chr(107).chr(49)); //san serif  matrix
define("SPANISH", "".chr(27).chr(54)); //spanish latin print chars  matrix
define("DOUBLEFONT", "". chr(27).chr(33).chr(41)); // font double elite matrix
/*
CONSTANTES PARA USO DE IMPRESION DE TICKET
*/
define("LEFT_P", "".chr(27).chr(97).chr(0));   //Left pos
define("CENTER_P", "".chr(27).chr(97).chr(1));   //Center pos
define("RIGHT_P", "".chr(27).chr(97).chr(2));   //right pos
define("FONT_A", "".chr(27) . chr(33) . chr(0)); //font A MEDIUM pos
define("FONT_B", "".chr(27) . chr(33) . chr(1)); //font B SMALL pos
define("DOUBLEFONT_P", "".chr(27) . chr(33) . chr(16)); //font A DOUBLE SIZE pos
define("DESCUENTO", "DESCUENTO, UD. AHORRA ");   //Left pos
function getSucursales()
{
    $array = array();
    $q="SELECT id_sucursal, descripcion FROM sucursal ORDER BY descripcion";
    $res=_query($q);
    $array["-1"] = "Seleccione";
    while ($row=_fetch_array($res)) {
        $id=$row['id_sucursal'];
        $description=$row['descripcion'];
        $array[$id] = $description;
    }
    return $array;
}
function getCajaSucursal($id_sucursal)
{
    $array = array();
    $q= "SELECT id_caja, nombre
  FROM caja
  WHERE  id_sucursal='$id_sucursal'
  AND activa=1
  ORDER BY nombre ";
    $res=_query($q);
    $array["-1"] = "Seleccione";
    while ($row=_fetch_array($res)) {
        $id=$row['id_caja'];
        $description=$row['nombre'];
        $array[$id] = $description;
    }
    return $array;
}
function getCajero($id_usuario)
{
    $sql ="SELECT  CONCAT_WS(' ', e.nombre, e.apellido) AS nombre
        FROM usuario AS u JOIN empleado AS e ON u.id_empleado = e.id_empleado
        WHERE u.id_usuario = '$id_usuario'";
    $res = _query($sql);
    if (_num_rows($res)>0) {
        $row = _fetch_array($res);
    } else {
        $sql ="SELECT * FROM usuario WHERE id_usuario = '$id_usuario'";
        $res= _query($sql);
        $row = _fetch_array($res);
    }
    $nombre = $row["nombre"];
    return $nombre;
}

//array empleados
function getEmpleados($id_sucursal=-1)
{
    $array = array();
    $q="SELECT * FROM empleado";
    if ($id_sucursal!=-1) {
        $q.=" WHERE id_sucursal='$id_sucursal'";
    }
    $res=_query($q);
    $array["-1"] = "Seleccione";
    while ($row=_fetch_array($res)) {
        $id=$row['id_empleado'];
        $description=$row['nombre']." ".$row['apellido'];
        $array[$id] = $description;
    }
    return $array;
}
//tipo empleado
function getTipoEmpleados()
{
    $array = array();
    $q="SELECT * FROM tipo_empleado";
    $q.= " ORDER BY descripcion";
    $res=_query($q);
    $array["-1"] = "Seleccione";
    while ($row=_fetch_array($res)) {
        $id=$row['id_tipo_empleado'];
        $description=$row['descripcion'];
        $array[$id] = $description;
    }
    return $array;
}

function getLogo()
{
    $qString="SELECT logo  FROM empresa";
    $sql=_query($qString);
    $array=_fetch_row($sql);
    $logo = $array[0];
    return $logo;
}
function getLogoSuc($id_sucursal)
{
    $qString="SELECT logo  FROM sucursal WHERE id_sucursal='$id_sucursal'";
    $sql=_query($qString);
    $array=_fetch_row($sql);
    $logo = $array[0];
    return $logo;
}
function getUrl()
{
    $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
    $host= $_SERVER["HTTP_HOST"];
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        $url = "https://";
    } else {
        $url = "http://";
    }
    // Append the host(domain name, ip) to the URL.
    $url.= $_SERVER['HTTP_HOST'];
    // Append the requested resource location to the URL
    $url.= $_SERVER['REQUEST_URI'];
    //$script= end((explode('/', $url)));
    $file = explode('/', $url);
    $file = end($file);
    $lastdir = str_replace($file, "", $url);
    return $lastdir;
}
/*  Devuelve array DUI  o NCR de un contribuyente */
function getDuiNrc($id_buscar)
{
    $busqueda=_query("SELECT dui,nrc FROM cliente WHERE id_cliente ='$id_buscar'");
    $resultado=_fetch_row($busqueda);
    return $resultado;
}

function getUser($id_user, $admin=0)
{
    $sql_user="SELECT us.id_usuario, us.nombre, us.admin
  FROM usuario AS us
  WHERE  us.id_usuario='$id_user'";

    $sql_user .= " GROUP BY us.id_usuario  ";
    $r_user=_query($sql_user);
    return $r_user;
}
//crear select
function crear_select($nombre, $array, $id_valor, $style)
{
    $txt='';
    //style='width:200px' <select id="select2-single-input-sm" class="form-control input-sm select2-single">
    $txt.= "<select class='select2 form-control input-sm select2-single selectt select' name='$nombre' id='$nombre' style='$style'>";

    foreach ($array as $clave=>$valor) {
        if ($id_valor==$clave) {
            $txt .= "<option value='$clave' selected>". $valor . "</option>";
        } else {
            $txt .= "<option value='$clave'>". $valor . "</option>";
        }
    }
    $txt .= "</select>";
    return $txt;
}
//crear select
function create_select($nombre, $array, $id_valor="", $style="")
{
    $txt='';
    //style='width:200px' <select id="select2-single-input-sm" class="form-control input-sm select2-single">
    $txt.= "<select class='form-control selcls ' name='$nombre' id='$nombre' style='$style'>";

    foreach ($array as $clave=>$valor) {
        if ($id_valor==$clave) {
            $txt .= "<option value='$clave' selected>". $valor . "</option>";
        } else {
            $txt .= "<option value='$clave'>". $valor . "</option>";
        }
    }
    $txt .= "</select>";
    return $txt;
}
//documentos activos para facturacion
function getTipoDocCliente($id=-1)
{
    $array = array();
    $q=" SELECT idtipodoc, nombredoc, alias,  activo";
    $q.="  FROM tipodoc WHERE cliente=1  AND activo=1 ";
    if ($id!=-1) {
        $q.=" AND idtipodoc='$id'";
    }
    $res=_query($q);
    //$array["-1"] = "Seleccione";
    while ($row=_fetch_array($res)) {
        $id=$row['alias'];
        $description=$row['nombredoc'];
        $array[$id] = $description;
    }
    return $array;
}
function getTipoPago($alias="")
{
    $array = array();
    $q=" SELECT  id_tipopago, alias_tipopago, descripcion FROM tipo_pago";
    $q.="  WHERE activo=1 ORDER BY id_tipopago ";
    if ($alias!="") {
        $q.=" AND alias_tipopago='$alias'";
    }
    $res=_query($q);
    //$array["-1"] = "Seleccione";
    while ($row=_fetch_array($res)) {
        $id=$row['alias_tipopago'];
        $description=$row['descripcion'];
        $array[$id] = $description;
    }
    return $array;
}
function getTotalTexto($total)
{
    $dolar = " ";
    $enteros_txt ="";
    //$total=number_format($total,2,".",",");
    $total =sprintf("%.2f", $total);
    list($entero, $decimal)=explode('.', $total);
    switch ($entero) {
        case 0:
            $dolar = " CERO DOLARES CON ";
            break;
        case 1:
            $dolar= " UN DOLAR CON ";
            break;
    case 100:
            $dolar= " CIEN DOLARES CON ";
            break;
        default:
            $enteros_txt = mb_strtoupper(num2letras($entero));
            $dolar = " DOLARES CON ";
            break;
    }

    $cadena_salida= $enteros_txt.$dolar.$decimal."/100 CTVS.";
    return $cadena_salida;
}
//rango de fechas para icluir en  encabezados o reportes
function getRangoFechaTexto($inicio, $fin)
{
    list($a, $m, $d) = explode("-", $inicio);
    list($a1, $m1, $d1) = explode("-", $fin);
    $rango="";
    if ($a ==$a1) {
        if ($m==$m1) {
            if ($d==$d1) {
                $rango="DEL $d  DE ".meses($m)." DE $a";
            } else {
                $rango="DEL $d AL $d1 DE ".meses($m)." DE $a";
            }
        } else {
            $rango="DEL $d DE ".meses($m)." AL $d1 DE ".meses($m1)." DE $a";
        }
    } else {
        $rango="DEL $d DE ".meses($m)." DEL $a AL $d1 DE ".meses($m1)." DE $a1";
    }
    return $rango;
}
//caja por id
function getCaja($caja=-1)
{
    $sql_caja = _query("SELECT * FROM caja WHERE id_caja='$caja'");
    $dats_caja = _fetch_array($sql_caja);
    return $dats_caja;
}
function getDropDown()
{
    ?>
    <td><div class='btn-group'>
    <a data-toggle='dropdown' class='btn btn-primary dropdown-toggle' href='#' >
    <i class='fa fa-user icon-white'></i> Menu
    </a>
<?php
}
function getProducto($id_producto)
{
    $sql="SELECT p.id_producto, p.barcode, p.descripcion,
	pp.precio,pp.id_presentacion,
	pp.descripcion as descpre, pr.nombre
	FROM producto AS p, presentacion_producto AS pp, presentacion AS pr
	WHERE  p.id_producto=pp.id_producto
	AND pp.id_presentacion=pr.id_presentacion
	AND p.id_producto='$id_producto'
	";
    $res=_query($sql);
    $n=_num_rows($res);
    $result="";
    if ($n>0) {
        $result= _fetch_array($res);
    }
    return $result;
}
function datos_empresa()
{
    //EMPRESA
    $sql_empresa = "SELECT * FROM sucursal WHERE id_sucursal='".$_SESSION["id_sucursal"]."'";
    $result_empresa=_query($sql_empresa);
    $row_empresa=_fetch_array($result_empresa);
    $empresa=$row_empresa['descripcion'];
    $razonsocial=$row_empresa['razon_social'];
    $giro=$row_empresa['giro'];
    $nit=$row_empresa['nit'];
    $nrc=$row_empresa['nrc'];
    $data = array('telefono2' => $row_empresa['telefono2'],'telefono1' => $row_empresa['telefono1'],
  'direccion' => $row_empresa['direccion'], 'empresa' => $empresa, 'razonsocial' => $razonsocial,
   'giro' => $giro,'nit' => $nit,'nrc' => $nrc);
    $datos= json_encode($data);
    return $datos;
}
function empresa()
{
    $sql_empresa = "SELECT * FROM sucursal where id_sucursal=$_SESSION[id_sucursal]";
    $result_empresa=_query($sql_empresa);
    $row_empresa=_fetch_array($result_empresa);
    $empresa=$row_empresa['descripcion'];
    return $empresa;
}
/**
 * Giro de empresa desde la tabla giroMH
 */
function get_giro($codigo)
{
    $sql_giro =_query("SELECT descripcion from giroMH WHERE codigo='$codigo'");
    $result_giro = _fetch_row($sql_giro);
    $giro = $result_giro[0];
    return $giro;
}
function datos_sucursal($id_sucursal)
{
    //Sucursal
    $sql_sucursal=_query("SELECT * FROM sucursal WHERE id_sucursal='$id_sucursal'");
    $array_sucursal=_fetch_array($sql_sucursal);
    return  $array_sucursal;
}
function datos_impuesto()
{
    //impuestos
    $sql_iva="SELECT iva,monto_retencion1,monto_retencion10,monto_percepcion
  FROM empresa";
    $result_IVA=_query($sql_iva);
    return $result_IVA;
}
function datos_factura($id_factura)
{
    //Obtener informacion de tabla Factura
    $sql_fact="SELECT * FROM factura WHERE id_factura='$id_factura'";
    $result_fact=_query($sql_fact);
    return $result_fact;
}
function datos_fact_det($id_factura)
{
    $sql_fact_det="SELECT  producto.id_producto, producto.descripcion, producto.exento,
    producto.codigo,
    presentacion.nombre as descp,
    presentacion.descripcion AS descripcion_pr,
    presentacion_producto.descripcion AS descpre,
    presentacion_producto.unidad,
    presentacion_producto.id_pp as id_presentacion,
    factura_detalle.*
    FROM factura_detalle
    JOIN producto ON factura_detalle.id_prod_serv=producto.id_producto
    JOIN presentacion_producto ON factura_detalle.id_presentacion=presentacion_producto.id_pp
    JOIN presentacion ON presentacion.id_presentacion=presentacion_producto.id_presentacion
    WHERE  factura_detalle.id_factura='$id_factura'
    GROUP BY factura_detalle.id_factura_detalle
    UNION ALL
    SELECT s.id_servicio AS id_producto, s.descripcion, 0 AS exento, 0 AS codigo,
    'SERVICIO' AS descp,
    ' ' AS descripcion_pr,
    ' ' AS descpre,
    1 AS unidad,
    0 AS id_presentacion,
    fd.*
    FROM factura_detalle AS fd
    JOIN servicios AS s ON fd.id_prod_serv=s.id_servicio
    JOIN categoria AS c ON  c.id_categoria=s.id_categoria
    WHERE fd.id_factura='$id_factura'
    AND fd.tipo_prod_serv='SERVICIO' 
    GROUP BY fd.id_factura_detalle
   ";
    $result_fact_det=_query($sql_fact_det);
    return $result_fact_det;
}
function datos_clientes($id_cliente)
{
    //Obtener informacion de tabla Cliente
    $sql="select * from cliente where id_cliente='$id_cliente'";
    $result= _query($sql);
    return $result;
}
function datos_empleado($id_empleado, $id_vendedor)
{
    //Obtener informacion de tabla usuario
    $sql="select * from usuario where id_usuario='$id_empleado'";
    $result= _query($sql);
    $row=_fetch_array($result);
    $empleado=$row['nombre'];
    $sql2="select empleado.* from empleado join usuario
  ON usuario.id_empleado=empleado.id_empleado
  where id_usuario='$id_vendedor'";
    $result2= _query($sql2);
    $vendedor="";
    if (_num_rows($result2)>0) {
        $row2=_fetch_array($result2);
        $vendedor=$row2['nombre'];
    } else {
        $vendedor=$empleado;
    }

    $empleado_vendedor=	$empleado."|".$vendedor;
    return $empleado_vendedor;
}
function vendedor($id_vendedor)
{
    $sql2="SELECT   CONCAT_WS(' ', nombre, apellido) AS nombre
  FROM empleado  WHERE id_empleado='$id_vendedor'";
    $result2= _query($sql2);
    $vendedor="";
    if (_num_rows($result2)>0) {
        $row2=_fetch_array($result2);
        $vendedor=$row2['nombre'];
    }
    return $vendedor;
}
function getDepartamento($id_d, $id_m)
{
    $sql="SELECT d.nombre_departamento as ndepto,
  m.nombre_municipio as nmuni
  FROM departamento AS d, municipio AS m
  WHERE m.id_departamento_municipio=d.id_departamento
  AND d.id_departamento=$id_d
  AND m.id_municipio=$id_m";
    return _query($sql);
}
// si una factura tiene impuestos a combustibles
function getImpGass($id_factura)
{
    $sql= "SELECT id_impuesto,imp_nombre, total_imp,id_dif,aplica_impuesto
    FROM fact_imp_combust
    WHERE id_factura='$id_factura'";
    $res = _query($sql);
    return $res;
}
// si una factura tiene impuestos a combustibles
function getIdDif($id_factura)
{
    $sql= "SELECT cd.numero_dif
    FROM cliente_dif AS cd
    JOIN fact_imp_combust AS fic
    ON cd.id_dif=fic.id_dif
    WHERE fic.id_factura='$id_factura'
    AND fic.galones_dif>0
    AND fic.id_dif!=-1
    ";
    $res = _query($sql);
    if (_num_rows($res)>0) {
        $resultado=_fetch_row($res);
        return $resultado[0];
    } else {
        return " ";
    }
}
//  numero de impuestos a combustibles
function getCantImpGas()
{
    $sql= "SELECT COALESCE(count(id),0) as n_imp FROM impuestos_gasolina ";
    $busqueda= _query($sql);
    if (_num_rows($busqueda)>0) {
        $resultado=_fetch_row($busqueda);
        return $resultado[0];
    } else {
        return 0;
    }
}
function getConfigDir($id_sucursal)
{
    $sql_dir_print="SELECT *  FROM config_dir WHERE id_sucursal='$id_sucursal'";
    $result_dir_print=_query($sql_dir_print);
    return   $result_dir_print;
}
//utiles para modulo transporte
function getMarcaModelo($id_marca, $id_modelo)
{
    $tiene="";
    $sql="SELECT ma.marca,mo.modelo
   FROM marca ma
    JOIN modelo mo ON ma.id_marca=mo.id_marca
    WHERE ma.id_marca='$id_marca'
    AND mo.id_modelo='$id_modelo'
   ";

    $query= _query($sql);
    if (_num_rows($query)>0) {
        $result = _fetch_array($query);
        $row = $result['marca']." - ".$result['modelo'];
    } else {
        $row=" ";
    }
    return $row;
}
 //crear array marcas
function getMarcas()
{
    $sql="SELECT * FROM marca";
    $result=_query($sql);
    $count=_num_rows($result);
    $array = array("Seleccione",'-1');
    for ($x=0;$x<$count;$x++) {
        $row=_fetch_array($result);
        $id           = $row['id_marca'];
        $description  = $row['marca'];
        $array[$id]   = $description;
    }
    return $array;
}
//TIPOS VEHICULO
function getTipoVehiculo()
{
    $array = array();
    $q="SELECT * FROM tipo_vehiculo";
    $res=_query($q);
    $array["-1"] = "Seleccione";
    while ($row=_fetch_array($res)) {
        $id=$row['id'];
        $description=$row['descripcion'];
        $array[$id] = $description;
    }
    return $array;
}
//TIPOS VEHICULO
function getCombustibles()
{
    $array = array();
    $q="SELECT p.id_producto AS id,p.descripcion
FROM producto as p
JOIN categoria AS c ON p.id_categoria=c.id_categoria
WHERE c.combustible=1";
    $res=_query($q);
    $array["-1"] = "Seleccione";
    while ($row=_fetch_array($res)) {
        $id=$row['id'];
        $description=$row['descripcion'];
        $array[$id] = $description;
    }
    return $array;
}
function days_in_month($month, $year)
{
    // calculate number of days in a month
    return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
}

function diasXMes($month)
{
    $year=date('Y');
    $dias_mes = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $res="";
    for ($i=1; $i<=$dias_mes ; $i++) {
        if ($i == date('j')) {
            $res.= '<option value="'.$i.'" selected>'.$i.'</option>';
        } else {
            $res.= '<option value="'.$i.'">'.$i.'</option>';
        }
    }
    return $res;
}
function getDataVehiculos()
{
    $sql="SELECT v.id, v.placa, m.marca, mo.modelo,v.vin, v.anio, v.numero_unidad,
  tv.descripcion, v.llantas, v.ejes, v.mes_vence_tarjeta,
  v.capacidad,p.descripcion as combustible
  FROM vehiculo AS v
  JOIN tipo_vehiculo AS tv ON v.tipo_vehiculo=tv.id
  JOIN producto AS p ON p.id_producto=v.tipo_combustible
  JOIN categoria AS c ON p.id_categoria=c.id_categoria
  JOIN marca as m on m.id_marca=v.id_marca
  JOIN modelo as mo on mo.id_modelo=v.id_modelo
  WHERE c.combustible=1
  ";
    $result=_query($sql);
    return $result;
}

function headDropDown()
{
    $drop="<td class='text-center'>
  <div class='btn-group'>
  <a href='#' data-toggle='dropdown' class='btn btn-primary dropdown-toggle'>
  <i class='fa fa-user icon-white'></i> Menu<span class='caret'></span></a>
  <ul class='dropdown-menu dropdown-primary'>";
    return $drop;
}
function footDropDown()
{
    $drop="	</ul></div></td></tr>";
    return $drop;
}
function getPrecioBaseCombustibles()
{
    $array = array();
    $q="SELECT p.id_producto AS id,p.descripcion,pp.precio
  FROM producto as p
  JOIN categoria AS c ON p.id_categoria=c.id_categoria
  JOIN presentacion_producto AS pp
  ON pp.id_producto=p.id_producto
  WHERE c.combustible=1";
    $res=_query($q);
    return $res;
}

function getConceptosArqueo($alias="")
{
    $array = array();
    $q="SELECT id, descripcion,multiplicador,alias_tipopago FROM arqueo_conceptos";
    $q.="  WHERE activo=1 ORDER BY id ";
    if ($alias!="") {
        $q.=" AND alias_tipopago='$alias'";
    }
    $res=_query($q);
    return $res;
}
function getDatosApertura($aper_id, $id_sucursal="")
{
    $q="SELECT * FROM apertura_caja WHERE id_apertura = '$aper_id'
  AND vigente = 1 ";
    if ($id_sucursal!="") {
        $q .= "  AND id_sucursal = '$id_sucursal'";
    }

    $res=_query($q);
    $row=_fetch_array($res);
    return $row;
}
function getTotalFactPago($id_apertura, $tipo_pago="")
{
    $q = "SELECT COALESCE(SUM(fp.subtotal),0) AS total FROM factura AS f
      JOIN factura_pago AS fp ON f.id_factura=fp.id_factura
      WHERE f.id_apertura= '$id_apertura'
      AND f.anulada=0";
    if ($tipo_pago!="") {
        $q .= " AND fp.alias_tipopago='$tipo_pago'";
    }
    $res=_query($q);
    $total_venta = 0 ;
    if (_num_rows($res)>0) {
        $row=_fetch_row($res);
        $total_venta =  $row[0];
    }
    return $total_venta;
}


function getDatosCaja($caja)
{
    $q= "SELECT * FROM caja WHERE id_caja = '$caja'";
    $res=_query($q);
    $row=_fetch_array($res);
    return $row;
}
function getArqueoCorte($id_apertura)
{
    $sql = "SELECT ar.*,ac.descripcion FROM arqueo_corte AS ar
  JOIN arqueo_conceptos AS ac ON ar.id_concepto=ac.id
  WHERE id_apertura='$id_apertura'";
    $res=_query($sql);
    return $res;
}
function getDatosApNoVigente($aper_id, $id_sucursal="")
{
    $q="SELECT * FROM apertura_caja WHERE id_apertura = '$aper_id'
   ";
    if ($id_sucursal!="") {
        $q .= "  AND id_sucursal = '$id_sucursal'";
    }

    $res=_query($q);
    $row=_fetch_array($res);
    return $row;
}
function getImpuestoGas()
{
    $sql="SELECT * FROM impuestos_gasolina ";
    $res= _query($sql);
    $count = _num_rows($res);
    if ($count>0) {
        return $res;
    } else {
        return null;
    }
}
//formas de pago por factura y/o alias de pago
function getPagoXFactura($id_factura, $alias_pago="")
{
    $sql="SELECT  *
  FROM factura_pago
  WHERE id_factura='$id_factura'";
    if ($alias_pago!="") {
        $sql.=" AND alias_tipopago ='$alias_pago' ";
    }
    $res=_query($sql);
    return $res;
}
//cliente
function getCliente($id=-1)
{
    $array = array();
    $q=" SELECT id_cliente,nombre";
    $q.="  FROM cliente   ";
    if ($id!=-1) {
        $q.=" WHERE  id_cliente='$id'";
    }
    $q.="  ORDER BY nombre ";
    $res=_query($q);
    //$array["-1"] = "Seleccione";
    while ($row=_fetch_array($res)) {
        $id=$row['id_cliente'];
        $description=$row['nombre'];
        $array[$id] = $description;
    }
    return $array;
}
function getClienteInterno()
{
    $array = array();
    $q = " SELECT id_cliente, nombre ";
    $q.= " FROM cliente   ";
    $q.= " WHERE  consumo_interno=1
         ORDER BY id_cliente ";

    $res = _query($q);
    if (_num_rows($res)>0) {
        while ($row=_fetch_array($res)) {
            $id=$row['id_cliente'];
            $description=$row['nombre'];
            $array[$id] = $description;
        }
        return $array;
    }
}
function getCorteAp($id_apertura)
{
    $q   ="SELECT cc.*,  c.nombre AS nombre_caja
    FROM controlcaja as cc
    LEFT JOIN caja as c ON cc.caja = c.id_caja
    WHERE cc.id_apertura = '$id_apertura'";
    $res=  _query($q);
    $count = _num_rows($res);
    if ($count>0) {
        $row = _fetch_array($res);
        return $row;
    } else {
        return null;
    }
}
function getByCorteAp($id_corte)
{
    $q   ="SELECT cc.*,  c.nombre AS nombre_caja
    FROM controlcaja as cc
    LEFT JOIN caja as c ON cc.caja = c.id_caja
    WHERE cc.id_corte = '$id_corte'";
    $res=  _query($q);
    $count = _num_rows($res);
    if ($count>0) {
        $row = _fetch_array($res);
        return $row;
    } else {
        return null;
    }
}
function getDatosAperturaNoVigente($aper_id, $id_sucursal="")
{
    $q="SELECT * FROM apertura_caja WHERE id_apertura = '$aper_id'
    ";
    if ($id_sucursal!="") {
        $q .= "  AND id_sucursal = '$id_sucursal'";
    }

    $res=_query($q);
    $row=_fetch_array($res);
    return $row;
}
function getDatosHistoAbono($id_abono_historial)
{
    //Obtener informacion de tabla Factura
    $q="SELECT  id_sucursal, id_abono_historial, id_cliente, abono, saldo_ante,
  saldo_ultimo, arr_abono_creditos, fecha, hora, id_apertura,cuotas
   FROM abono_historial
   WHERE id_abono_historial='$id_abono_historial'";
    $res=_query($q);
    return $res ;
}
//metodos de pago, sin credito autorizado
function getMetodosPagoNoCred()
{
    $array = array();
    $q=" SELECT  id_tipopago, alias_tipopago, descripcion FROM tipo_pago";
    $q.="  WHERE activo=1
  AND alias_tipopago!='CRE'
  ORDER BY id_tipopago ";

    $res=_query($q);
    //$array["-1"] = "Seleccione";
    while ($row=_fetch_array($res)) {
        $id=$row['alias_tipopago'];
        $description=$row['descripcion'];
        $array[$id] = $description;
    }
    return $array;
}
//crear select
function create_select_list($nombre, $array, $id_valor="", $style="", $size="")
{
    $txt='';
    if ($size=="") {
        $txt.= "<select class='form-control selcls ' name='$nombre' id='$nombre' style='$style'>";
    } else {
        $txt.= "<select class='form-control selcls ' name='$nombre' id='$nombre' style='$style'  size='$size'>";
    }


    foreach ($array as $clave=>$valor) {
        if ($id_valor==$clave) {
            $txt .= "<option value='$clave' selected>". $valor . "</option>";
        } else {
            $txt .= "<option value='$clave'>". $valor . "</option>";
        }
    }
    $txt .= "</select>";
    return $txt;
}
//cliente por factura
function cliente_factura($id_factura)
{
    $select = "SELECT c.nrc,c.nit,c.dui,c.cod_act_eco,c.nombre,
    c.direccion,d.nombre as nombre_departamento ,c.depto,c.municipio,c.email
  from cliente as c join factura f on c.id_cliente=f.id_cliente
  join departamentoMH as d on c.depto=d.id_departamento
   where f.id_factura='$id_factura'";
    $result =  _query($select) ;
    if (_num_rows($result)==0) {
        $select = "SELECT c.nrc,c.nit,c.dui,c.cod_act_eco,c.nombre,
        c.direccion,d.nombre as nombre_departamento,c.depto,c.municipio,c.email
    from cliente as c
    join factura f on c.id_cliente=f.id_cliente
    join departamentoMH as d on c.depto=d.id_departamento
     where f.id_factura=" . $id_factura;
        $result =  _query($select) ;
    }
    return $result;
}
//vendedor
function getVendor()
{
    $array = array();
    $q= "SELECT empleado.id_empleado,
  concat(empleado.nombre,' ',empleado.apellido) AS nombre
  FROM empleado WHERE id_tipo_empleado=2  ";
    $res=_query($q);
    $array["-1"] = "Seleccione";
    while ($row=_fetch_array($res)) {
        $id=$row['id_empleado'];
        $description=$row['nombre'];
        $array[$id] = $description;
    }
    return $array;
}
function getVentaCuotas($id_factura)
{
    //Obtener informacion de tabla
    $sql ="SELECT * FROM venta_cuota WHERE id_factura='$id_factura'";
    $result = _query($sql);
    $row    = _fetch_assoc($result);
    return $row;
}
function getCuotaMinMax($id_venta_cuota)
{
    //Obtener informacion de tabla
    $sql ="SELECT MIN(fecha_vence) AS inifecha,
  MAX(fecha_vence) AS finfecha
  FROM cuota WHERE id_venta_cuota='$id_venta_cuota'
  AND cuotanumero>0";
    $result = _query($sql);
    $row    = _fetch_assoc($result);
    return $row;
}
function datosCliente($id_cliente)
{
    //Obtener informacion de tabla Cliente
    $sql="select * from cliente where id_cliente='$id_cliente'";
    $result= _query($sql);
    $row= _fetch_assoc($result);
    return $row;
}

function charfill($char, $value, $nd)
{ //retorna un string de n digitos dado el numero y la cantidad del string
    $length = strlen((string)$value);
    for ($i = $length;$i<$nd;$i++) {
        $value = $char.$value;
    }
    return $value; ///////  result
}
//pedidos
function datos_pedido($id_factura)
{
    //Obtener informacion de tabla Factura
    $sql_fact="SELECT * FROM pedidos WHERE id_factura='$id_factura'";
    $result_fact=_query($sql_fact);
    return $result_fact;
}
function datos_pedido_det($id_factura)
{
    $sql_fact_det="SELECT  producto.id_producto, producto.descripcion, producto.exento,
  producto.codigo,
  presentacion.nombre as descp,
  presentacion.descripcion AS descripcion_pr,
  presentacion_producto.descripcion AS descpre,
  presentacion_producto.unidad,
  presentacion_producto.id_pp as id_presentacion,
   pedidos_detalle.*
   FROM pedidos_detalle
   JOIN producto ON pedidos_detalle.id_prod_serv=producto.id_producto
   JOIN presentacion_producto ON pedidos_detalle.id_presentacion=presentacion_producto.id_pp
   JOIN presentacion ON presentacion.id_presentacion=presentacion_producto.id_presentacion
   WHERE  pedidos_detalle.id_factura='$id_factura'
   ";
    $result_fact_det=_query($sql_fact_det);
    return $result_fact_det;
}

//cliente por factura
function cliente_pedido($id_factura)
{
    $select = "select c.nrc,c.nit,c.nombre,c.direccion,d.nombre_departamento
  from cliente as c join pedidos f on c.id_cliente=f.id_cliente
  join departamento as d on c.depto=d.id_departamento
   where f.id_factura=" . $id_factura;
    $result =  _query($select) ;
    if (_num_rows($result)==0) {
        $select = "select c.nrc,c.nit,c.nombre,c.direccion,'LA UNON' as nombre_departamento
    from cliente as c
    join factura f on c.id_cliente=f.id_cliente
     where f.id_factura=" . $id_factura;
        $result =  _query($select) ;
    }
    return $result;
}
function getEmpresa()
{
    $sql_empresa = "SELECT * FROM empresa";
    $result_empresa=_query($sql_empresa);
    $row_empresa=_fetch_array($result_empresa);
    return $row_empresa;
}
//funciones catalogos MH
function getNombreDepartamentoId($id)
{
    $q="SELECT nombre FROM departamentoMH
    WHERE id_departamento='$id'";
    $res = _query($q);
    $n   = _num_rows($res);
    $nombre = "";
    if ($n>0) {
        $row=_fetch_row($res);
        $nombre = $row[0];
    }
    return $nombre;
}

function getDescripGiro($id)
{
    $q_act_eco="SELECT descripcion from giroMH WHERE codigo='$id'";
    $r_act_eco= _query($q_act_eco);
    $row_act_eco=_fetch_row($r_act_eco);
    $giro= $row_act_eco[0];
    return  $giro;
}
//funciones IVA
function getIVA()
{
    $q = "SELECT iva FROM sucursal ";
    $r = _query($q);
    $row=_fetch_row($r);
    $iva= round($row[0]/100, 2);
    return  $iva;
}
function calc_iva($valor)
{
    //impuestos
    $sql_iva="SELECT ivaFROM sucursal where iva>0";
    $result_IVA=_query($sql_iva);
    $row_IVA=_fetch_array($result_IVA);
    $iva=$row_IVA['iva']/100;

    $calc_iva = round($iva * $valor, 4) ;
    return $calc_iva;
}
function getMunicipio()
{
    $id_departamento = $_POST["id_departamento"];
    $option = "";
    $sql_mun = _query("SELECT * FROM municipioMH WHERE id_departamento='$id_departamento'");
    while ($mun_dt=_fetch_array($sql_mun)) {
        $option .= "<option value='".$mun_dt["codigo"]."'>".$mun_dt["nombre"]."</option>";
    }
    echo $option;
}
//unique id
function uniqidReal($lenght = 13)
{
    // uniqid gives 13 chars, but you could adjust it to your needs.
    if (function_exists("random_bytes")) {
        $bytes = random_bytes(ceil($lenght / 2));
    } elseif (function_exists("openssl_random_pseudo_bytes")) {
        $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
    } else {
        throw new Exception("no cryptographically secure random function available");
    }
    return substr(bin2hex($bytes), 0, $lenght);
}

function getDepartamentos()
{
    $array = array();
    $q="SELECT * FROM departamentoMH";
    $res=_query($q);
    $array["-1"] = "Seleccione";
    while ($row=_fetch_array($res)) {
        $id=$row['id_departamento'];
        $description=$row['nombre'];
        $array[$id] = $description;
    }
    return $array;
}
function getMunicipios($id_departamento)
{
    $array = array();
    $q="SELECT * FROM municipioMH  WHERE id_departamento='$id_departamento'";
    $res=_query($q);
    $array["-1"] = "Seleccione";
    while ($row=_fetch_array($res)) {
        $id=$row['codigo'];
        $description=$row['nombre'];
        $array[$id] = $description;
    }
    return $array;
}

function getUMedidas()
{
    $array = array();
    $q="SELECT `id`, `codigo`, `nombre` FROM `unidad_medidaMH` ";
    $res=_query($q);
    $array["-1"] = "Seleccione";
    while ($row=_fetch_array($res)) {
        $id=$row['codigo'];
        $description=$row['nombre'];
        $array[$id] = $description;
    }
    return $array;
}

function getDte($id_factura)
{
    $q="SELECT * FROM jsonDTE
     WHERE id_factura='$id_factura'";
    $res = _query($q);
    $n   = _num_rows($res);
    $row=null;
    if ($n>0) {
        $row=_fetch_array($res);
    }
    return $row;
}
function getGiroId($id)
{
    $q="SELECT * FROM giroMH 
    WHERE codigo='$id'";
    $res =_query($q);
    $row=_fetch_array($res);
    return $row;
}
function getGiro($id)
{
    $q="SELECT * FROM giroMH 
    WHERE codigo='$id'";
    $res =_query($q);
    $row=_fetch_array($res);
    return $row;
}
function getNombreMunicipioCod($depto, $cod)
{
    $q="SELECT nombre FROM municipioMH
    WHERE codigo='$cod'
    AND id_departamento='$depto'";
    $res = _query($q);
    $n   = _num_rows($res);
    $nombre = "";
    if ($n>0) {
        $row=_fetch_row($res);
        $nombre = $row[0];
    }
    return $nombre;
}
function getNombreDte($tipoDoc)
{
    $filename='N';
    switch ($tipoDoc) {
        case 'CCF':
            $filename='COMPROBANTE DE CRÉDITO FISCAL';
            break;
        case 'COF':
            $filename='FACTURA';
            break;
        case 'FAC':
            $filename='FACTURA';
            break;
        case 'FSE':
            $filename='FACTURA DE SUJETO EXCLUIDO';
            break;
        case 'NC': // NOTA DE CREDITO
            $filename='NOTA DE CRÉDITO';
            break;
        case 'ND': // NOTA DE DEBITO
            $filename='NOTA DE DÉBITO';
            break;
        case 'REM': // NOTA DE REMISION
            $filename='NOTA DE REMISIÓN';
            break;
        default:
            $filename='N';
        break;
    }
    return $filename;
}
function convert_to($source, $target_encoding)
{
    // detect the character encoding of the incoming file
    $encoding = mb_detect_encoding($source, "auto");

    // escape all of the question marks so we can remove artifacts from
    // the unicode conversion process
    $target = str_replace("?", "[question_mark]", $source);

    // convert the string to the target encoding
    $target = mb_convert_encoding($target, $target_encoding, $encoding);

    // remove any question marks that have been introduced because of illegal characters
    $target = str_replace("?", "", $target);

    // replace the token string "[question_mark]" with the symbol "?"
    $target = str_replace("[question_mark]", "?", $target);

    return $target;
}
function verificarDtePdf($id_factura, $tipoDoc)
{
    switch ($tipoDoc) {
        case 'CCF':
            $filename='dte_ccf_pdf.php';
            break;
        case 'COF':
            $filename='dte_fac_pdf.php';
            break;
        case 'FAC':
            $filename='dte_fac_pdf.php';
            break;
        case 'FSE':
            $filename='dte_fse_pdf.php';
            break;
        case 'NC': // NOTA DE CREDITO
            $filename='dte_notas_pdf.php';
            break;
        case 'ND': // NOTA DE DEBITO
            $filename='dte_notas_pdf.php';
            break;
        case 'REM': // NOTA DE DEBITO
            $filename='dte_notas_pdf.php';
            break;
        default:
            $filename='N';
        break;
    }

    return $filename;
}
function det_dte($id_factura)
{
    $q="SELECT  p.id_producto, p.descripcion, pr.cod_umedidaMH AS unidad_medida,fd.*,c.combustible
    FROM factura_detalle AS fd
    JOIN producto AS p ON fd.id_prod_serv=p.id_producto
    JOIN presentacion_producto AS pp ON pp.id_producto=p.id_producto
    JOIN presentacion AS pr ON pr.id_presentacion=pp.id_presentacion
    JOIN categoria AS c ON  c.id_categoria=p.id_categoria
    WHERE  fd.id_factura='$id_factura'
    AND fd.tipo_prod_serv='PRODUCTO'
    GROUP BY fd.id_factura_detalle
   UNION ALL
    SELECT s.id_servicio AS id_producto, s.descripcion,
    '59' AS unidad_medida, fd.*, c.combustible
    FROM factura_detalle AS fd
    JOIN servicios AS s ON fd.id_prod_serv=s.id_servicio
    JOIN categoria AS c ON  c.id_categoria=s.id_categoria
    WHERE fd.id_factura='$id_factura'
    AND fd.tipo_prod_serv='SERVICIO' 
    GROUP BY fd.id_factura_detalle";
    $r=_query($q);
    return $r;
}
function getDataSucursalId($id_sucursal)
{
    $sql=_query("SELECT * FROM sucursal WHERE id_sucursal='$id_sucursal'");
    $row=_fetch_array($sql);
    return $row;
}
function getVendedores()
{
    $array =[];
    $sql2="SELECT e.* FROM empleado AS e
    JOIN usuario AS u ON u.id_empleado=e.id_empleado
    JOIN tipo_empleado  AS te ON te.id_tipo_empleado=e.id_tipo_empleado    
    WHERE descripcion lIKE '%Vendedor%'   
    ";
    $res= _query($sql2);
    if (_num_rows($res)>0) {
        while ($row=_fetch_array($res)) {
            $id=$row['id_empleado'];
            $description=$row['nombre'];
            $array[$id] = $description;
        }
        return $array;
    }
}
/**
 * Devuelve el id de caja que le debe pertener al user
 * esta funcion tendra el resultado esperado siempre
 * que los usuarios se mantengan de la siguiente forma:
 * _____________________
 * | usuario | id_caja |
 * | caja1   |     2   |
 * | caja2   |     3   |
 * | caja3   |     4   |
 * |_________|_________|
 *
 * Lo que se busca es que al cajero1 solo se muestren la
 * facturas y ticket pertenecientes a su caja.
 */
function getDatosUser($id_user)
{
    $sql =_query("SELECT * FROM `usuario` WHERE `id_usuario`=$id_user");
    $sql_result = _fetch_array($sql);
    $user_name = $sql_result['usuario'];
    $id_caja = 0;
    if ($user_name=="caja1") {
        $id_caja=2;
    }
    if ($user_name=="caja2") {
        $id_caja=3;
    }
    if ($user_name=="caja3") {
        $id_caja=4;
    }

    return $id_caja;
}
/**
 * funcion que retorna las unidades de la presentacion vendida
 */
function getPresentationFactura($id_presentacion)
{
    $sql_presentacion = _query("SELECT * FROM presentacion_producto WHERE id_pp=$id_presentacion");
    $row_presentacion = _fetch_array($sql_presentacion);
    $unida_presentacion = $row_presentacion['unidad'];
    return $unida_presentacion;
}

//array para presentaciones por producto
function getPresentation($id_producto)
{
    $sql="SELECT pr.id_presentacion,pr.nombre,pp.id_pp,
    pp.descripcion as descpre
    FROM presentacion AS pr
    JOIN presentacion_producto AS pp
    ON pp.id_presentacion=pr.id_presentacion
    WHERE pp.id_producto='$id_producto'";
    $res=_query($sql);
    $array =array();
    $array["-1"] = "Seleccione";
    while ($row=_fetch_array($res)) {
        $id=$row['id_presentacion'];
        $description=$row['id_pp']."-".$row['nombre'];
        $array[$id] = $description;
    }
    return $array;
}
//descuentos
function getDetalleDescuento($id_factura, $id_sucursal)
{
    $q="SELECT factura.id_factura,
    factura_detalle.id_factura_detalle,
    factura_detalle.id_prod_serv,factura_detalle.cantidad,
    factura_detalle.precio_venta, factura_detalle.subtotal,
    factura_detalle.tipo_prod_serv, factura.tipo_documento,
    factura.sumas,factura.iva
    FROM factura
    JOIN factura_detalle  ON factura.id_factura=factura_detalle.id_factura
    WHERE factura_detalle.tipo_prod_serv='DESCUENTO'
    AND factura.id_factura='$id_factura'
    AND factura.id_sucursal='$id_sucursal'";
    $r= _query($q);
    $row=null;
    if (_num_rows($r)>0) {
        $row=_fetch_array($r);
    }
    return $row;
}
function validateUser()
{
    //SELECT * FROM resolucion WHERE alias='COF' AND vigente=1
    $pasword = md5($_REQUEST['pasword']);
    $user = $_REQUEST['user'];
    $q="SELECT * FROM  usuario
    WHERE  usuario='$user'
    AND password='$pasword' 
    AND admin=1
    ";
    $valido=false;

    $res = _query($q);
    $numrows= _num_rows($res);
    if ($numrows > 0) {
        $valido=true;
    }

    if ($valido==true) {
        $xdatos['typeinfo']='Success';
        $xdatos['msg']='Usuario válido!';
        $xdatos['valido'] = $valido ;
    } else {
        $xdatos['valido'] = $valido ;
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Usuario no es válido!';
    }
    echo json_encode($xdatos);
}

if (isset($_POST['process'])) {
    switch ($_POST['process']) {
        case 'getMunicipio':
            getMunicipio();
            break;
        case 'validateUser':
            validateUser();
            break;

    }
}
?>
