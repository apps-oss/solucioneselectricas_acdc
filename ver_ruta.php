<?php
include ("_core.php");
include ('num2letras.php');
//include ('facturacion_funcion_imprimir.php');
function initial()
{
$id_ruta = $_REQUEST['id_ruta'];
$sql="SELECT * FROM ruta WHERE id_ruta='$id_ruta'";
$result = _query( $sql);
$count = _num_rows( $result );
$raw=_fetch_array($result);
$descripcion=$raw["descripcion"];

//permiso del script
//permiso del script
 	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];

	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);
  $id_sucursal = 1;

?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title">Detalle de Ruta</h4>
</div>
<div class="modal-body">
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row" id="row1">
			<div class="col-lg-12">
				<?php
						//permiso del script
						if ($links!='NOT' || $admin=='1' ){
					?>
          <div class="row">
            <div class="form-group has-info single-line">
              <label class="control-label">Descripcion</label>
              <input type="text" class="form-control" name="nombre" id="nombre" value="<?php echo $descripcion; ?>" readonly>
            </div>
          </div>
					<table	class="table table-bordered table-striped" id="tableview">
						<thead>
							<tr>
								<th>Campo</th>
								<th >Detalle</th>
								<th >Detalle</th>
							</tr>
						</thead>
						<tbody>
							<?php

								if ($count > 0) {
                  $sql = _query("SELECT cli.nombre,cli.municipio,cli.depto,m.nombre_municipio,d.nombre_departamento
                                  FROM ruta as r
                                  JOIN ruta_detalle as rd ON r.id_ruta=rd.id_ruta
                                  JOIN cliente as cli ON rd.id_cliente=cli.id_cliente
                                  JOIN departamento as d ON cli.depto=d.id_departamento
                                  JOIN municipio as m on cli.municipio=m.id_municipio
                                  WHERE r.id_ruta='$id_ruta'");

									for($i = 0; $i < _num_rows($sql); $i ++) {
										$row = _fetch_array ( $sql, $i );
                    $cliente=$row["nombre"];
                    $id_municipio=$row["municipio"];
                    $id_departamento=$row["depto"];
                    $nombre_mun=$row["nombre_municipio"];
                    $nombre_dep=$row["nombre_departamento"];
                    ?>
                    <tr id="<?php echo $id_cliente; ?>">
                    <td class='cliente'>
                    <input type='hidden' class='id_departamento' value='<?php echo $id_departamento; ?>'>
                    <input type='hidden' class='id_municipio' value='<?php echo $id_municipio; ?>'>
                    <input type='hidden' class='id_cliente' value='<?php echo $id_cliente; ?>'>
                    <?php echo $cliente; ?>
                  </td>
                    <td class='departamento'> <?php echo $nombre_dep; ?></td>
                    <td class='municipio'> <?php echo $nombre_mun; ?></td></tr>
                    <?php
                      }
                    }
                     ?>
						</tbody>

					</table>
				</div>
					<!--Fin Widgwt imagen-->
          <?php

				?>

			</div>
		</div>
	</div>
<div class="modal-footer">

<?php
	echo "<button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>
	</div><!--/modal-footer -->";
}
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}

if(!isset($_REQUEST['process'])){
  initial();
}
//else {
if (isset($_REQUEST['process'])) {
  switch ($_REQUEST['process']) {
    case 'buscarprodcant' :
    buscarprodcant();
    break;
  }

  //}
}
?>
