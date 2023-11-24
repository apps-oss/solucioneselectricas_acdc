<?php
include_once "_core.php";
function initial() {
	$id_categoria = $_REQUEST['id_categoria'];
	$sql="SELECT * FROM categoria WHERE id_categoria='$id_categoria'";
	$result = _query($sql);

	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];

	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);
	?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title">Editar Categoría</h4>
	</div>
	<div class="modal-body">
		<div class="row" id="row1">
			<div class="col-lg-12">
				<?php if ($links!='NOT' || $admin=='1' ){ ?>
				<form name="formulario" id="formulario" autocomplete="off">
					<?php
					$row=_fetch_array($result);
					$descripcion=$row['descripcion'];
					$nombre = $row['nombre_cat'];
					$pista = $row['pista'];
					$tienda = $row['tienda'];
					?>
					<div class="form-group has-info single-line">
						<label>Nombre</label>
						<input type="text" placeholder="Nombre" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre;?>">
					</div>
					<div class="form-group has-info single-line">
						<label>Descripción</label>
						<input type="text" placeholder="Descripcion" class="form-control" id="descripcion" name="descripcion" value="<?php echo $descripcion;?>">
					</div>
					<div class="col-md-6">
						<div class="form-group has-info single-line">
							<label class="control-label">Tienda</label>
							<div class='checkbox i-checks'>
								<label>
									<?php
									if($tienda==1)
									{
										echo "<input type='checkbox'  id='tienda' name='tienda' value='1' checked> <i></i>";
									}
									else
									{
										echo "<input type='checkbox'  id='tienda' name='tienda' value='1'> <i></i>";
									}
									?>
								</label>
							</div>
						</div>
					</div>
					<div class="col-md-4" hidden>
						<div class="form-group has-info single-line">
							<label class="control-label">Pista</label>
							<div class='checkbox i-checks'>
								<label>
									<?php
									if($pista==1)
									{
										echo "<input type='checkbox'  id='pista' name='pista' value='1' checked> <i></i>";
									}
									else
									{
										echo "<input type='checkbox'   id='pista' name='pista' value='1'> <i></i>";
									}
									?>
								</label>
							</div>
						</div>
					</div>
					<input type="hidden" name="process" id="process" value="edited">
					<input type="hidden" name="id_categoria" id="id_categoria" value="<?php echo $id_categoria; ?>">
					<div>
						<input type="submit" id="submit1" name="submit1" value="Guardar" class="btn btn-primary m-t-n-xs"/>
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
				},
			},
			messages: {
				nombre: "Por favor ingrese un nombre",
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
	} //permiso del script
	else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}
function edited()
{
	$id_categoria=$_POST["id_categoria"];
	$nombre=$_POST["nombre"];
	$descripcion=$_POST["descripcion"];
	$tienda=$_POST["tienda"];
	$pista=$_POST["pista"];
	$table = 'categoria';
	if ($tienda == 0 && $pista == 0){
		$tienda = 1;
	}
	$form_data = array (
		'nombre_cat' => $nombre,
		'descripcion' => $descripcion,
		'tienda'=>$tienda,
		'pista'=>$pista,
	);
	$where_clause = "id_categoria='" . $id_categoria . "'";
	$updates = _update ( $table, $form_data, $where_clause );
	if($updates)
	{
		$xdatos['typeinfo']='Success';
		$xdatos['msg']='Registro editado con exito!';
	}
	else
	{
		$xdatos['typeinfo']='Error';
		$xdatos['msg']='Registro no pudo ser editado!';
	}
	echo json_encode($xdatos);
}
if(!isset($_REQUEST['process']))
{
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
