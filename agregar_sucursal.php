<?php
include_once "_core.php";

function initial() {
	$_PAGE = array ();
	$_PAGE ['title'] = 'Agregar Sucursal';
	$_PAGE ['links'] = null;
	$_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
	/*
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
	*/
	$_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';

	include_once "header.php";
	include_once "main_menu.php";
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	/*
	$uri=$_SERVER['REQUEST_URI'];
	list($uri1,$uri2,$filename)=explode("/",$uri);
	$poschar = strpos($filename, '?');
	if ($poschar !== false) {
		list($namelink1,$namelink1)=explode("?",$filename);
		$links=permission_usr($id_user,$namelink1);
	}
	else{
		 $links=permission_usr($id_user,$filename);
	}*/
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);
	//permiso del script
	if ($links!='NOT' || $admin=='1' ){

?>

            <div class="row wrapper border-bottom white-bg page-heading">

                <div class="col-lg-2">

                </div>
            </div>
        <div class="wrapper wrapper-content  animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5>Agregar Sucursal</h5>
                        </div>
                        <div class="ibox-content">


                          <form name="formulario" id="formulario">
                              <div class="form-group has-info single-line"><label class="control-label" for="Nombre">Descripción</label> <input type="text" placeholder="Digite Nombre" class="form-control" id="nombre" name="nombre"></div>
                              <div class="form-group has-info single-line"><label class="control-label" for="Dirección">Dirección</label> <input type="text" placeholder="Dirección" class="form-control" id="direccion" name="direccion"></div>

                                <div class="form-group has-info single-line">
                                    <div class="form-group"><label class="col-sm-2 control-label">Casa Matriz</label>
                                    	<div class="col-sm-10">
                                        	<div class="checkbox i-checks"><label> <input type="checkbox"  id="casa" name="casa" value="1"> <i></i>  </label></div>
                                    	</div>
                                  </div>
                                     <input type="hidden" name="process" id="process" value="insert"><br>
                                </div>

                                    <div>

                                       <input type="submit" id="submit1" name="submit1" value="Guardar" class="btn btn-primary m-t-n-xs" />

                                    </div>
                                </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

<?php
include_once ("footer.php");
echo "<script src='js/funciones/funciones_sucursal.js'></script>";
		} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}

function insert_sucursal(){
	  $descripcion=$_POST["nombre"];
    $direccion=$_POST["direccion"];
    $casa=$_POST["casa"];

    $sql_result= _query("SELECT * FROM sucursal WHERE descripcion='$descripcion'");
    $numrows=_num_rows($sql_result);

    $table = 'sucursal';
    $form_data = array (
    	'descripcion' => $descripcion,
    	'direccion' => $direccion,
    	'casa_matriz' => $casa
    );

    if($numrows == 0){
		    $insertar = _insert($table,$form_data);
				$id_sucursal=_insert_id();
		    //insertar correlativos
				$tabl = 'correlativo';
				$data = array (
					'id_sucursal' => $id_sucursal,
					'tik'=>0,
					'cof'=>0,
					'ccf'=>0,
					'ref'=>0,
					'ii'=>0,
					'di'=>0,
					'ai'=>0,
					'ti'=>0,
					'voc'=>0,
					'aj'=>0,
					'cot'=>0,
					'tre'=>0,
					'trr'=>0,
					'dev'=>0,
					'nc'=>0,
					'pd'=>0,
					'pdp'=>0,
					'cof_e'=>0,
					'ccf_e'=>0,
					'nc_e'=>0,
					'dev_e'=>0,
					'con'=>0,
				);
				$ins = _insert($tabl,$data);
				//insertar directorios para impresion

				$table2 = 'config_dir';
			  $q   = "SELECT * FROM config_dir WHERE id_config_dir!='' limit 1";
			  $res = _query($q);
			  if (_num_rows($res)>0){
			 	 $row = _fetch_array($res);
				 $dir_print_script  		= $row['dir_print_script'];
				 $shared_printer_matrix = $row['shared_printer_matrix'];
				 $shared_printer_pos    = $row['shared_printer_pos'];
				 $shared_print_barcode  = $row['shared_print_barcode'];
			 }else{
				$dir_print_script  		 = "localhost/impresion/";
				$shared_printer_matrix = "//localhost/facturacion";
				$shared_printer_pos    = "//localhost/ticket";
				$shared_print_barcode  = "//localhost/barcode";
			 }
				$data2 = array (
				'id_sucursal'           => $id_sucursal,
				'dir_print_script'      => $dir_print_script,
				'shared_printer_matrix' => $shared_printer_matrix,
				'shared_printer_pos'    => $shared_printer_pos,
				'shared_print_barcode'  => $shared_print_barcode,
				'rollo_etiqueta'  =>1,
			 );
			 $ins2 = _insert($table2,$data2);
			  $table3 = 'config_pos';
				$data3 = array (
					'id_sucursal' => $id_sucursal,
					'header1' 		=> $descripcion,
					'footer1' 		=> "GRACIAS POR SU COMPRA, VUELVA PRONTO",
				);
				$ins3 = _insert($table3,$data3);
		    if($insertar && $ins && $ins2 && $ins3 ){
		       $xdatos['typeinfo']='Success';
		       $xdatos['msg']='Registro insertado con éxito !';
		       $xdatos['process']='insert';
		    }
		    else{
		       $xdatos['typeinfo']='Error';
		       $xdatos['msg']='Error al insertar!';
		        $xdatos['process']='none';
				}
    }

	echo json_encode($xdatos);
}

if(!isset($_POST['process'])){
	initial();
}
else
{
if(isset($_POST['process'])){
switch ($_POST['process']) {
	case 'insert':
		insert_sucursal();
		break;

	}
}
}
?>
