<?php
include("_core.php");
include('facturacion_funcion_imprimir.php');
// Page setup
function initial()
{
    $title='Administrar Productos';
    $_PAGE = array();
    $_PAGE ['title'] = $title;
    $_PAGE ['links'] = null;
    $_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/sweetalert/sweetalert.css" rel="stylesheet">';
    include_once "header.php";
    include_once "main_menu.php";

    //permiso del script
    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];

    $uri = $_SERVER['SCRIPT_NAME'];
    $filename=get_name_script($uri);
    $links=permission_usr($id_user, $filename); ?>
<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row" id="row1">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<?php
                if ($links!='NOT' || $admin=='1') {
                    echo "<div class='ibox-title'>";
                    $filename='agregar_producto.php';
                    $link=permission_usr($id_user, $filename);
                    if ($link!='NOT' || $admin=='1') {
                        echo "<a href='agregar_producto.php' class='btn btn-primary' role='button'><i class='fa fa-plus icon-large'></i> Agregar Producto</a>";
                    }


                    echo	"</div>"; ?>
					<div class="ibox-content">
						<!--load datables estructure html-->
						<header>
							<h4><?php echo $title; ?></h4>
						</header>
						<section>
							<table class="table table-striped table-bordered table-hover" id="editable2">
								<thead>
									<tr>
										<th class="col-lg-1">Id</th>
										<!--th class="col-lg-1">CodArt</th-->
										<th class="col-lg-1">Barcode</th>
										<th class="col-lg-3">Descripcion</th>
										<th class="col-lg-2">Categoria</th>
										<th class="col-lg-2">Proveedor</th>
										<th class="col-lg-1">Exento</th>
										<th class="col-lg-1">Estado</th>
										<th class="col-lg-1">Acci&oacute;n</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
							<input type="hidden" name="autosave" id="autosave" value="false-0">
						</section>
						<!--Show Modal Popups View & Delete -->
						<div class='modal fade' id='viewModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
							<div class='modal-dialog'>
								<div class='modal-content'></div><!-- /.modal-content -->
							</div><!-- /.modal-dialog -->
						</div><!-- /.modal -->
						<div class='modal fade' id='deleteModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
							<div class='modal-dialog modal-sm'>
								<div class='modal-content modal-sm'></div><!-- /.modal-content -->
							</div><!-- /.modal-dialog -->
						</div><!-- /.modal -->
					</div><!--div class='ibox-content'-->
				</div><!--<div class='ibox float-e-margins' -->
				</div> <!--div class='col-lg-12'-->
			</div> <!--div class='row'-->
		</div><!--div class='wrapper wrapper-content  animated fadeInRight'-->
		<?php
        include("footer.php");
                    echo" <script type='text/javascript' src='js/funciones/funciones_producto.js'></script>"; ?>
		<script type="text/javascript">
		$(document).on('hidden.bs.modal', function(e) {
			var target = $(e.target);
			target.removeData('bs.modal').find(".modal-content").html('');
		});
		</script>
		<?php
                } else {
                    echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div></div></div></div></div>";
                    include("footer.php");
                }
}

function estado_producto()
{
    $id_producto = $_POST ['id_producto'];
    $estado = $_POST["estado"];
    if ($estado == 1) {
        $n = 0;
    } else {
        $n = 1;
    }
    $table = 'producto';
    $id_sucursal = $_SESSION["id_sucursal"];
    $form_data = array(
        'estado' => $n,
    );
    $where_clause = "id_producto='".$id_producto."'";
    $delete = _update($table, $form_data, $where_clause);
    if ($delete) {
        $xdatos ['typeinfo'] = 'Success';
        $xdatos ['msg'] = 'Registro actualizado con exito!';
    } else {
        $xdatos ['typeinfo'] = 'Error';
        $xdatos ['msg'] = 'Registro no pudo ser actualizado!';
    }
    echo json_encode($xdatos);
}
function printBcode_old()
{
    $qty 		   = $_POST['qty'];
    $tipo_etiq   = $_POST['tipo_etiq'];
    $id_producto = $_POST['id_producto'];
    $id_sucursal = $_SESSION['id_sucursal'];
    //Valido el sistema operativo y lo devuelvo para saber a que puerto redireccionar
    $info = $_SERVER['HTTP_USER_AGENT'];
    if (strpos($info, 'Windows') == true) {
        $so_cliente='win';
    } else {
        $so_cliente='lin';
    }
    //directorio de script impresion cliente
    $sql_dir_print="SELECT *  FROM config_dir WHERE id_sucursal='$id_sucursal'";
    $result_dir_print=_query($sql_dir_print);
    $row_dir_print=_fetch_array($result_dir_print);
    $dir_print=$row_dir_print['dir_print_script'];
    $shared_print_barcode=$row_dir_print['shared_print_barcode'];
    $nreg_encode['shared_print_barcode'] =$shared_print_barcode;
    $nreg_encode['dir_print'] =$dir_print;
    $nreg_encode['sist_ope'] =$so_cliente;
    $nreg_encode['datos'] =print_bcode($id_producto, $qty, $tipo_etiq);
    echo json_encode($nreg_encode);
}
function printBcode()
{
    $qty 				 = $_POST['qty'];
    $tipo_etiq	 = $_POST['tipo_etiq'];
    $id_producto = $_POST['id_producto'];
    $precio_sel  = $_POST['precio_sel'];
    $presentacion=$_REQUEST['presentacion'];
    $id_presentacion=$_REQUEST['id_presentacion'];
    list($idp, $nombpresenta)=explode("-", $presentacion);
    $id_sucursal=$_SESSION['id_sucursal'];
    //Valido el sistema operativo y lo devuelvo para saber a que puerto redireccionar
    $info = $_SERVER['HTTP_USER_AGENT'];
    if (strpos($info, 'Windows') == true) {
        $so_cliente='win';
    } else {
        $so_cliente='lin';
    }
    //directorio de script impresion cliente
    $sql_dir_print="SELECT *  FROM config_dir WHERE id_sucursal='$id_sucursal'";
    $result_dir_print=_query($sql_dir_print);
    $row_dir_print=_fetch_array($result_dir_print);
    $dir_print=$row_dir_print['dir_print_script'];
    $shared_print_barcode=$row_dir_print['shared_print_barcode'];
    $nreg_encode['shared_print_barcode'] =$shared_print_barcode;
    $nreg_encode['dir_print'] =$dir_print;
    $nreg_encode['sist_ope'] =$so_cliente;
    $nreg_encode['datos'] =print_bcode($id_producto, $qty, $tipo_etiq, $precio_sel, $nombpresenta, $id_presentacion);
    echo json_encode($nreg_encode);
}
function setPrintBcode()
{
    $id_sucursal = $_SESSION["id_sucursal"];
    $tipo_etiq	 = $_POST['tipo_etiq'];
    //Valido el sistema operativo y lo devuelvo para saber a que puerto redireccionar
    $info = $_SERVER['HTTP_USER_AGENT'];
    if (strpos($info, 'Windows') == true) {
        $so_cliente='win';
    } else {
        $so_cliente='lin';
    }
    $table = 'config_dir';

    $form_data = array(
        'media_type' => $tipo_etiq,
    );
    $where_clause = "id_sucursal='".$id_sucursal."'";
    $upd = _update($table, $form_data, $where_clause);

    //directorio de script impresion cliente
    $sql_dir_print="SELECT *  FROM config_dir WHERE id_sucursal='$id_sucursal'";
    $result_dir_print=_query($sql_dir_print);
    $row_dir_print=_fetch_array($result_dir_print);
    $dir_print=$row_dir_print['dir_print_script'];
    $shared_print_barcode=$row_dir_print['shared_print_barcode'];
    $nreg_encode['shared_print_barcode'] =$shared_print_barcode;
    $nreg_encode['dir_print'] =$dir_print;
    $nreg_encode['sist_ope'] =$so_cliente;
    $nreg_encode['datos'] =print_bcodeSet($tipo_etiq);
    echo json_encode($nreg_encode);
}
function setMarginBcode()
{
    $id_sucursal = $_SESSION["id_sucursal"];
    $leftmargin	 = $_POST['leftmargin'];
    $table = 'config_dir';
    $form_data = array(
        'leftmarginlabel' => $leftmargin,
    );
    $where_clause = "id_sucursal='".$id_sucursal."'";
    $upd = _update($table, $form_data, $where_clause);
    if ($upd) {
        $xdatos ['typeinfo'] = 'Success';
        $xdatos ['msg'] = 'Registro actualizado con exito!';
    } else {
        $xdatos ['typeinfo'] = 'Error';
        $xdatos ['msg'] = 'Registro no pudo ser actualizado !';
    }
    echo json_encode($xdatos);
}
function cargarPrecPres()
{
    $id_presentacion=$_REQUEST['presentacion'];
    $id_producto=$_REQUEST['id_producto'];
    $id_user=$_REQUEST["id_user"];
    $id_user=$_SESSION["id_usuario"];
    $q=_query("SELECT precios FROM usuario WHERE id_usuario='$id_user'");
    $r_precios=_fetch_array($q);
    $precios=$r_precios['precios'];
    $sql0="SELECT pp.id_pp
	FROM  presentacion_producto AS pp
	WHERE pp.id_producto = '$id_producto'
	AND  pp.id_presentacion = '$id_presentacion'
	";
    $res=_query($sql0);
    $id_pp0=_fetch_array($res);
    $id_pp=$id_pp0['id_pp'];

    $xc=0;
    $n_p=0;
    $select_rank="";
    $preciosArray = _getPrecios($id_pp, $precios);
    $xc=0;
    $precio_venta=0;
    foreach ($preciosArray as $key => $value) {
        // code...
        if ($value>0.0) {
            $select_rank.="<option value='$value'";
            if ($xc==0 || $precio_venta==$value) {
                $select_rank.=" selected ";
                $preciop=$value;
                $xc = 1;
            }
            $select_rank.=">$value</option>";
        }
    }

    if ($xc==0) {
        $select_rank.="<option value='0.0'>0.0</option>";
    }

    $xdatos['select_rank'] = $select_rank;

    echo json_encode($xdatos);
}
if (!isset($_POST['process'])) {
    initial();
} else {
    if (isset($_POST['process'])) {
        switch ($_POST['process']) {
            case 'insert':
            insertar();
            break;
            case 'lista':
            lista();
            break;
            case 'insert_img':
                insert_img();
                break;
                        case 'estado':
                estado_producto();
                break;
            case 'printBcode':
            printBcode();
            break;
            case 'setPrintBcode':
            setPrintBcode();
            break;
            case 'setMarginBcode':
            setMarginBcode();
            break;
            case 'cargarPrecPres':
                cargarPrecPres();
                  break;
        }
    }
}
?>
