<?php
include_once "_core.php";

function initial() 
{
	$id_marca = $_REQUEST["id_marca"];
	$sql = _query("SELECT * FROM marca WHERE id_marca ='$id_marca'");
	$row = _fetch_array($sql);
	$marca = $row["marca"];
?>
 	<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Editar Marca</h4>
	</div>
	<form name="formulario" id="formulario">
	<div class="modal-body">
		<div class="wrapper wrapper-content  animated fadeInRight">
			<div class="row" id="row1">
				<div class="col-lg-12">
					<div class="form-group has-info single-line">
                  		<label class="control-label" for="Nombre">Marca</label>
                  		<input type="text" placeholder="Marca" class="form-control" id="marca" name="marca" value="<?php echo $marca; ?>">
                  	</div>   
                  		<input type="hidden" name="process" id="process" value="edited">
  				</div>
			</div>
				<?php 
					echo "<input type='hidden' nombre='id_marca' id='id_marca' value='$id_marca'>";
				?>
			</div>

	</div>
	<div class="modal-footer">
		<input type="submit" class="btn btn-primary"  value="Guardar">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>

	</div>
	</form>
<?php
echo "<script src='js/funciones/funciones_agregar_marca.js'></script>";
	
}

function edit()
{
	$id_marca = $_POST["id_marca"];
	$marca = $_POST["marca"];

    $table = 'marca';
    $form_data = array (
    	'marca' => $marca
    );   	
    $where = "id_marca='".$id_marca."'";
	$update = _update($table,$form_data, $where);
    if($update)
    {
       $xdatos['typeinfo']='Success';
       $xdatos['msg']='Datos modificados correctamente!';
       $xdatos['process']='insert';
    }
    else
    {
       $xdatos['typeinfo']='Error';
       $xdatos['msg']='Datos no pudieron ser modificados!';
       $xdatos['process']='none';
	}    
    echo json_encode($xdatos);
}

if(!isset($_POST['process']))
{
	initial(); 
}
else
{
	if(isset($_POST['process']))
	{	
		switch ($_POST['process'])
		{
			case 'edited':
				edit();
				break;		
		} 
	}			
}
?>
