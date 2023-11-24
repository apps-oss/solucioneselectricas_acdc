<?php
include_once "_core.php";
function initial() {
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$id = $_REQUEST['id'];
	$sql="SELECT * FROM impuestos_gasolina WHERE id='$id'";
	$result = _query($sql);
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);

	$row=_fetch_array($result);
	$descripcion=$row['descripcion'];
	$nombre = $row['nombre'];
	$valor = $row['valor'];
	$activo = $row['activo'];

	?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title">Editar Impuesto</h4>
	</div>
	<div class="modal-body">
		<?php if($links != 'NOT' || $admin == '1'){ ?>
		<div class="row" id="row1">
			<div class="col-lg-12">
				<form name="formulario" id="formulario" autocomplete="off">
					<div class="form-group has-info single-line">
						<label>Nombre</label>
						<input type="text" placeholder="Nombre" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre;?>">
					</div>
					<div class="form-group has-info single-line">
						<label>Descripción</label>
						<input type="text" placeholder="Descripcion" class="form-control" id="descripcion" name="descripcion" value="<?php echo $descripcion;?>" />
					</div>

					<div  class="col-md-6">
						<div class="form-group has-info single-line">
							<label>valor impuesto</label>
							<input type="text"   name="valor" id="valor" class="form-control clear decimal" value="<?php echo $valor;?>" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group has-info single-line">
							<label class="control-label">Activo</label>
							<div class='checkbox i-checks'>
								<label>
									<?php
									if($activo==1)
									{
										echo "<input type='checkbox'   id='activo' name='activo' value='1' checked> <i></i>";
									}
									else
									{
										echo "<input type='checkbox'  id='activo' name='activo' value='1'> <i></i>";
									}
									?>
								</label>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<input type="hidden" name="process" id="process" value="edited">
						<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
						<input type="submit" id="submit1" name="submit1" value="Guardar" class="btn btn-primary m-t-n-xs" />
					</div>
				</form>
			</div>
		</div>
	</div>
	<script>
	$(document).ready(function(){
		$('#formulario').validate({
			rules: {
				nombre: {
					required: true,
					//valor:true,
				},
			},
			messages: {
				nombre: "Por favor ingrese un nombre",
				//	valor:"Por favor ingrese un valor mayor que cero!",
			},
			highlight: function(element) {
				$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
			},
			success: function(element) {
				$(element).closest('.form-group').removeClass('has-error').addClass('has-success');
			},
			submitHandler: function (form) {

				senddata();
			}
		});
	});
	</script>
<?php
	}
	else
	{
		echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div></div>";
	}
}
function edited()
{
	$nombre=$_POST["nombre"];
	$id=$_POST["id"];
	$descripcion=$_POST["descripcion"];
	$valor=$_POST["valor"];
	$activo=$_POST["activo"];
	$table = 'impuestos_gasolina';
	$sql_result= _query("SELECT * FROM $table WHERE nombre='$nombre'");
	$numrows=_num_rows($sql_result);
	if ($tienda == 0 && $pista == 0){
		$tienda =1;
	}
	//SELECT id, nombre, descripcion, valor, activo FROM impuestos_gasolina

	$form_data = array (
		'nombre' => $nombre,
		'descripcion' => $descripcion,
		'valor' =>$valor,
		'activo'=>$activo,
	);

	$where_clause = "id='" . $id . "'";
	$insertar = _update ( $table, $form_data, $where_clause );
		if($insertar)
		{
			$field='id_categoria';
			$xdatos['typeinfo']='Success';
			$xdatos['msg']='Registro ingresado con exito!';
		}
		else
		{
			$xdatos['typeinfo']='Error';
			$xdatos['msg']='Registro no pudo ser ingresado!';
		}
	echo json_encode($xdatos);
}

if(!isset($_REQUEST['process'])){
	initial();
}
else
{
	if(isset($_REQUEST['process']))
	{
		switch ($_REQUEST['process'])
		{
			case 'edited':
				edited();
				break;
			case 'formEdit' :
				initial();
				break;
		}
	}
}
	?>
