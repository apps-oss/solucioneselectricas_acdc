<?php
include_once "_core.php";

function initial() 
{
?>
 	<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Agregar Marca</h4>
	</div>
	<form name="formulario" id="formulario">
	<div class="modal-body">
		<div class="wrapper wrapper-content  animated fadeInRight">
			<div class="row" id="row1">
				<div class="col-lg-12">
					<div class="form-group has-info single-line">
                  		<label class="control-label" for="Nombre">Marca</label>
                  		<input type="text" placeholder="Marca" class="form-control" id="marca" name="marca">
                  	</div>   
                  		<input type="hidden" name="process" id="process" value="insert">
  				</div>
			</div>
				<?php 
					//echo "<input type='hidden' nombre='id_garantia' id='id_garantia' value='$id_garantia'>";
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

function insert()
{
	$marca = $_POST["marca"];
    $sql_result= _query("SELECT * FROM marca WHERE marca='$marca'");
    $row_update=_fetch_array($sql_result);
    $numrows=_num_rows($sql_result);
    
    $table = 'marca';
    $form_data = array (
    	'marca' => $marca
    );   	
      
    if($numrows == 0 && trim($marca)!='')
    { 

    	$insertar = _insert($table,$form_data);
	    if($insertar)
	    {
	       $xdatos['typeinfo']='Success';
	       $xdatos['msg']='Datos ingresados correctamente!';
	       $xdatos['process']='insert';
	    }
	    else
	    {
	       $xdatos['typeinfo']='Error';
	       $xdatos['msg']='Datos no pudieron ser ingresados!';
	       $xdatos['process']='none';
		}    
    }
   	else
   	{
   		$xdatos['typeinfo']='Error';
       	$xdatos['msg']='Esta marca ya fue ingresada!';
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
			case 'insert':
				insert();
				break;		
		} 
	}			
}
?>
