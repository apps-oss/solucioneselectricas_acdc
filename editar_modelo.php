<?php
include_once "_core.php";

function initial()
{
	$id_modelo = $_REQUEST["id_modelo"];
	$sql = _query("SELECT * FROM modelo WHERE id_modelo ='$id_modelo'");
	$row = _fetch_array($sql);
	$modelo = $row["modelo"];
	$id_marca = $row["id_marca"];
?>
 	<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Editar Modelo</h4>
	</div>
	<form name="formulario" id="formulario">
	<div class="modal-body">
		<div class="wrapper wrapper-content  animated fadeInRight">
			<div class="row" id="row1">
				<div class="col-lg-12">
					<div class="form-group has-info single-line">
                  		<label class="control-label" for="modelo">Modelo</label>
                  		<input type="text" placeholder="modelo" class="form-control" id="modelo" name="modelo" value="<?php echo $modelo; ?>">
                  	</div>
                  	<div class="form-group has-info single-line">
                  		<label class="control-label" for="marca">Marca</label>
                  		<select name="marca" id="marca" class="select form-control" style="width: 100%;">
                  			<option value="">Seleccione</option>
                  			<?php
                  				$sql = _query("SELECT * FROM marca ORDER BY marca ASC");
                  				while($row = _fetch_array($sql))
                  				{
                  					echo "<option value='".$row["id_marca"]."'";
                  					if($row["id_marca"] == $id_marca)
                  					{
                  						echo " selected ";
                  					}
                  					echo ">".$row["marca"]."</option>";
                  				}
                  			?>
                  		</select>
                  	</div>
                  	<input type="hidden" name="process" id="process" value="edited">
  				</div>
			</div>
				<?php
					echo "<input type='hidden' nombre='id_modelo' id='id_modelo' value='$id_modelo'>";
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

function edit()
{
	$id_modelo = $_POST["id_modelo"];
	$modelo = $_POST["modelo"];
	$marca = $_POST["marca"];

    $table = 'modelo';
    $form_data = array (
    	'id_marca' => $marca,
    	'modelo' => $modelo
    );
    $where = "id_modelo='".$id_modelo."'";
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
