<?php
include_once "_core.php";

function initial() {

	
	$_PAGE = array ();
	$_PAGE ['title'] = 'Editar Codigo Contable';
	$_PAGE ['links'] = null;
	$_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/chosen/chosen.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jqGrid/ui.jqgrid.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';
	
	include_once "header.php";
	include_once "main_menu.php";

	 $id_contable=$_REQUEST["id_contable"];
	 $sql  = _query("SELECT * FROM contables_generales WHERE id_contable = '$id_contable'");
	 $row = _fetch_array($sql);
   $descripcion=utf8_encode($row["descripcion"]); 
   $codigo=$row["codigo"];  
	 $codigo_es=$row["especifico"];	
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$uri=$_SERVER['REQUEST_URI'];
	list($uri1,$uri2,$filename)=explode("/",$uri);
	$poschar = strpos($filename, '?');
	if ($poschar !== false) {
		list($namelink1,$namelink1)=explode("?",$filename);
		$links=permission_usr($id_user,$namelink1);
	}
	else{
		 $links=permission_usr($id_user,$filename);
	}
?>
 
            <div class="row wrapper border-bottom white-bg page-heading">
                
                <div class="col-lg-2">

                </div>
            </div>
        <div class="wrapper wrapper-content  animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox ">
						<?php 
						//permiso del script
						if ($links!='NOT' || $admin=='1' ){
					?>
                        <div class="ibox-title">
                            <h5>Editar Codigo Contable</h5>
                        </div>
                        <div class="ibox-content">
						
				           <form name="formulario" id="formulario">
                              <div class="col-lg-6">
                              <div class="form-group has-info single-line">
                                <label>Codigo Contable</label>
                                 <input type="text" class="form-control" id="codigo" name="codigo" value="<?php echo $codigo;?>">
                              </div>
                              </div>
                              <div class="col-lg-6">
                              <div class="form-group has-info single-line">
                                <label>Codigo Espec&iacute;fico</label>
                                 <input type="text" class="form-control" id="espe" name="espe" value="<?php echo $codigo_es;?>">
                              </div>
                              </div>
                              <div class="col-lg-12">
                              <div class="form-group has-info single-line">
                                <label>Descripci&oacute;n</label>
                                 <input type="text" placeholder="Descripcion" class="form-control" id="descripcion" name="descripcion" value="<?php echo $descripcion;?>">
                              </div>
                             </div>
                            
                              <input type="hidden" name="process" id="process" value="edited"><br>
                              <input type="hidden" name="id_contable" id="id_contable" value="<?php echo $id_contable;?>"><br>
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
echo "<script src='js/funciones/funciones_contable.js'></script>";

} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}	
}

function editar(){
    $id_contable= $_POST["id_contable"];
    $descripcion=$_POST["descripcion"];
    $codigo=$_POST["codigo"];
    $especifico=$_POST["espe"];
  
    //'id_cliente' => $id_cliente,
    $table = 'contables_generales';
    $form_data = array( 
    'codigo' => $codigo,
    'descripcion' => $descripcion,
    'especifico' => $especifico
    );       
    $where_clause = "id_contable='" .$id_contable. "'";
    $updates = _update( $table , $form_data , $where_clause );
    if($updates){
       $xdatos['typeinfo']='Success';
       $xdatos['msg']='Registro modificado con exito!';
       $xdatos['process']='insert';
    }
    else{
       $xdatos['typeinfo']='Error';
       $xdatos['msg']='Registro no pudo ser modificado !';
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
  case 'edited':
    editar();
    break;    
  
  } 
}     
}
?>
