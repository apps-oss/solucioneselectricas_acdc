<?php
include_once "_core.php";

function initial() 
{
?>
 	<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Agregar Modelo</h4>
	</div>
	<form name="formulario" id="formulario">
	<div class="modal-body">
		<div class="wrapper wrapper-content  animated fadeInRight">
			<div class="row" id="row1">
				<div class="col-lg-12">
					<div class="form-group has-info single-line">
                  		<label class="control-label" for="modelo">Modelo</label>
                  		<input type="text" placeholder="Modelo" class="form-control" id="modelo" name="modelo">
                  	</div>
                  	<div class="form-group has-info single-line">
                  		<label class="control-label" for="marca">Marca</label>
                  		<select name="marca" id="marca" class="select form-control" style="width: 100%;">
                  			<option value="">Seleccione</option>
                  			<?php
                  				$sql = _query("SELECT * FROM marca ORDER BY marca ASC");
                  				while($row = _fetch_array($sql))
                  				{
                  					echo "<option value='".$row["id_marca"]."'>".$row["marca"]."</option>";
                  				}
                  			?>
                  		</select>
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
echo "<script src='js/funciones/funciones_agregar_modelo.js'></script>";
	
}

function insert()
{
	$modelo = $_POST["modelo"];
	$marca = $_POST["marca"];
    $sql_result= _query("SELECT * FROM modelo WHERE modelo='$modelo' AND id_marca='$marca'");
    $row_update=_fetch_array($sql_result);
    $numrows=_num_rows($sql_result);
    
    $table = 'modelo';
    $form_data = array (
    	'id_marca' => $marca,
    	'modelo' => $modelo
    );   	
      
    if($numrows == 0 && trim($modelo)!='' && $marca !="")
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
       	$xdatos['msg']='Este modelo ya fue ingresada!';
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
