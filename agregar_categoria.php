<?php
include_once "_core.php";
function initial() {
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];

	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);
	?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title">Agregar Categoría</h4>
	</div>
	<div class="modal-body">
		<?php if($links != 'NOT' || $admin == '1'){ ?>
		<div class="row" id="row1">
			<div class="col-lg-12">
				<form name="formulario" id="formulario" autocomplete="off">
					<div class="form-group has-info single-line">
						<label>Nombre</label>
						<input type="text" placeholder="Nombre" class="form-control" id="nombre" name="nombre" value="">
					</div>
					<div class="form-group has-info single-line">
						<label>Descripción</label>
						<input type="text" placeholder="Descripcion" class="form-control" id="descripcion" name="descripcion" value="">
					</div>
					<input type="hidden" name="process" id="process" value="insert">

					<div class="col-md-6">
						<div class="form-group has-info single-line">
							<label class="control-label">Tienda</label>
							<div class='checkbox i-checks'>
								<label>
									<input type='checkbox'  id='tienda' name='tienda' value='1' checked><i></i>
								</label>
							</div>
						</div>
					</div>
					<div class="col-md-4" hidden>
						<div class="form-group has-info single-line">
							<label class="control-label">Pista</label>
							<div class='checkbox i-checks'>
								<label>
									<input type='checkbox'  id='pista' name='pista' value='1'><i></i>
								</label>
							</div>
						</div>
					</div>
					<div>
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
	}
	else
	{
		echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div></div>";
	}
}
function insert()
{
	$nombre=$_POST["nombre"];
	$descripcion=$_POST["descripcion"];
	$tienda=$_POST["tienda"];
	$pista=$_POST["pista"];
	$sql_result= _query("SELECT * FROM categoria WHERE nombre_cat='$nombre'");
	$numrows=_num_rows($sql_result);
	if ($tienda == 0 && $pista == 0){
		$tienda =1;
	}
	$table = 'categoria';
	$form_data = array (
		'nombre_cat' => $nombre,
		'descripcion' => $descripcion,
		'pista' =>$pista,
		'tienda'=>$tienda,
	);

	if($numrows == 0)
	{
		$insertar = _insert($table,$form_data);
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
	}
	else
	{
		$xdatos['typeinfo']='Error';
		$xdatos['msg']='Ya se registro una categoria con estos datos!';
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
			case 'insert':
				insert();
				break;
			case 'formEdit' :
				initial();
				break;
		}
	}
}
	?>
