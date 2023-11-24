<?php
include("_core.php");
function initial()
{
    $id_cliente = $_REQUEST ['id_cliente'];
    $id_sucursal = $_SESSION["id_sucursal"];
    $sql="SELECT *FROM cliente WHERE id_cliente='$id_cliente' AND id_sucursal='$id_sucursal'";
    $result = _query($sql);
    //permiso del script
    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];

    $uri = $_SERVER['SCRIPT_NAME'];
    $filename=get_name_script($uri);
    $links=permission_usr($id_user, $filename);
    //permiso del script
    ?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
		<h4 class="modal-title">Borrar cliente</h4>
	</div>
	<div class="modal-body">
		<div class="wrapper wrapper-content  animated fadeInRight">
			<div class="row" id="row1">
				<div class="col-lg-12">
					<?php
                    //permiso del script
                    if ($links!='NOT' || $admin=='1') {
                        ?>
						<table class="table table-bordered table-striped" id="tableview">
							<thead>
								<tr>
									<th>Campo</th>
									<th>Descripcion</th>
								</tr>
							</thead>
							<tbody>
								<?php
                                        $row = _fetch_array($result);
                        echo "<tr><td>Id</th><td>$id_cliente</td></tr>";
                        echo "<tr><td>Nombre</td><td>".$row ['nombre']."</td>";
                        echo "<tr><td>Dirección</td><td>".$row ['direccion']."</td>";
                        echo "</tr>"; ?>
							</tbody>
						</table>
					</div>
				</div>
				<?php
                echo "<input type='hidden' nombre='id_cliente' id='id_cliente' value='$id_cliente'>"; ?>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-primary" id="btnDelete">Borrar</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
		</div>
		<!--/modal-footer -->
<?php
                    } //permiso del script
    else {
        echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div></div></div></div></div>";
    }
}
function deleted()
{
    $id_cliente = $_POST ['id_cliente'];

    $table = 'cliente';
    $saldo_pendiente=tieneCredito($id_cliente);
    $saldo=round($saldo_pendiente, 2);
    $saldo=sprintf("%.2f", $saldo);

    if ($saldo_pendiente>0) {
        $xdatos ['typeinfo'] = 'Error';
        $xdatos ['msg'] = 'Cliente no puede ser eliminado, tiene un saldo pendiente de: $'.$saldo;
    } else {
        $where_clause = "id_cliente='".$id_cliente."'";
        $delete = _delete($table, $where_clause);
        if ($delete) {
            $xdatos ['typeinfo'] = 'Success';
            $xdatos ['msg'] = 'Registro eliminado con exito!';
        } else {
            $xdatos ['typeinfo'] = 'Error';
            $xdatos ['msg'] = 'Registro no pudo ser eliminado!';
        }
    }
    echo json_encode($xdatos);
}
function tieneCredito($id_cliente)
{
    $q="SELECT COALESCE(SUM(COALESCE(cr.saldo,0)),0) AS saldo_pendiente
	FROM credito AS cr
	WHERE cr.id_cliente='$id_cliente'
    UNION ALL 
    SELECT  COALESCE(SUM(COALESCE(vc.saldo,0)),0) AS saldo_pendiente
	FROM venta_cuota AS vc
	 WHERE vc.id_cliente='$id_cliente'";

    $result = _query($q);
    $numrows= _num_rows($result);
    $total_saldo  =0;
    if ($numrows>0) {
        while ($row=_fetch_array($result)) {
            $total_saldo += $row['saldo_pendiente'];
        }
    }
    return  $total_saldo;
}
if (! isset($_REQUEST ['process'])) {
    initial();
} else {
    if (isset($_REQUEST ['process'])) {
        switch ($_REQUEST ['process']) {
            case 'formDelete':
                initial();
                break;
                case 'deleted':
                deleted();
                break;
            }
    }
}

    ?>
