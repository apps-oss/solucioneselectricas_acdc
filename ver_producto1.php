<?php
include ("_core.php");
$id_producto = $_REQUEST['id_producto'];
$sql="SELECT p.descripcion, p.barcode, p.estado, p.imagen,p.codart, pv.nombre, c.nombre_cat FROM producto AS p, proveedor as pv, categoria as c WHERE p.id_proveedor=pv.id_proveedor AND p.id_categoria=c.id_categoria AND  p.id_producto='$id_producto'";
$result = _query( $sql);
$count = _num_rows( $result );

$id_user=$_SESSION["id_usuario"];
$id_sucursal=$_SESSION["id_sucursal"];
$admin=$_SESSION["admin"];

$uri = $_SERVER['SCRIPT_NAME'];
$filename=get_name_script($uri);
$links=permission_usr($id_user,$filename);

//directorio de script impresion cliente
$sql_dir_print="SELECT *  FROM config_dir WHERE id_sucursal='$id_sucursal'";
$result_dir_print=_query($sql_dir_print);
$row_dir_print=_fetch_array($result_dir_print);
$dir_print=$row_dir_print['dir_print_script'];
$media_type=$row_dir_print['media_type'];

?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  <h4 class="modal-title">Datos de Producto</h4>
</div>
<div class="modal-body">
  <div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row" id="row1">
      <div class="col-lg-12">
        <?php	if ($links!='NOT' || $admin=='1' ){ ?>
          <table	class="table table-bordered table-striped" id="tableview">
            <thead>
              <tr>
                <th>Campo</th>
                <th colspan=3>Detalle</th>
              </tr>
            </thead>
            <tbody>
              <?php
                  $row = _fetch_array ($result);
                  $descripcion=$row['descripcion'];
                  $barcode=$row['barcode'];
                  $estado=$row['estado'];
                  $nombre_proveedor=$row['nombre'];
                  $nombre=$row['nombre_cat'];
                  $imagen=$row['imagen'];
                  if ($estado==1)
                  $estadoactivo='Activo';
                  else
                  $estadoactivo='Inactivo';

                  echo"<tr><td>Descripcion:</td><td colspan=3>".$descripcion."</td></tr>";
                  echo"<tr><td>Barcode:</td><td>".$barcode."</td><td>Categoria:</td><td>".$nombre."</td></tr>";
                  echo"<tr><td>Proveedor:</td><td colspan=3>".$nombre_proveedor."</td></tr>";
                  echo"<tr><td>Estado:</td><td>".$estadoactivo."</td><td>CodArt:</td><td>$row[codart]</td></tr>";
                  echo"<tr><td colspan='4' class='font-bold text-center'><h4>Presentaciones</h4></td></tr>";
                  $sql_p = _query("SELECT pp.precio, pp.costo, pp.descripcion,pp.id_presentacion, p.nombre
                    FROM presentacion_producto as pp, presentacion as p
                    WHERE pp.id_presentacion=p.id_presentacion AND pp.id_producto = '$id_producto'");

                  echo"<tr><td>Presentación</td><td>Descripción</td><td>Costo</td><td>Precio</td></tr>";
                  while ($roq = _fetch_array($sql_p))
                  {
                    $precio=$roq["precio"];
                    echo"<tr><td>".$roq["nombre"]."</td>
                    <td>".$roq["descripcion"]."</td>
                    <td>".$roq["costo"]."</td>
                    <td>".$precio."</td>
                    </tr>";
                  }

                  echo"<tr><td colspan='4' class='font-bold text-center'><h4>Imprimir Barcode</h4></td></tr>";
                  if ($media_type=='TD'){
                    $check_td=' checked ';
                    $check_tt='';
                  }else{
                    $check_tt=' checked ';
                    $check_td='';
                  }
                    ?>
                <tr><td colspan=4><h5 class=' text-center'>Seleccione el tipo de Etiqueta</h5></td></tr>
                <tr><td colspan=1 style="width:40%"><strong>Termica Directa</strong> <label class=" center-block">
                  <input type="radio" name="tipo_etiq" id="tipo_etiq1" value='TD' <?=$check_td;?>/></label></td>
                <td colspan=2 style="width:40%"> <strong>Transferencia Termica</strong><label class=" center-block">
                  <input type="radio" name="tipo_etiq" id="tipo_etiq2" value='TT' <?=$check_tt;?> ></div></td></label>
                <td colspan=1 style="width:20%">
                  <button type='button' class='btn btn-primary'  id='btnSetMT' name='btnSetMT'><i class='fa fa-check'></i> Cambiar</button> </td>
              </tr>
              <tr>
                <td colspan=4><h5 class=' text-center'>Cantidad de Etiquetas a Imprimir</h5></td>

              </tr>

                <tr><td colspan=1><strong>Cantidad:</strong></td>
                  <td colspan=2><input type='text'  class='form-control numeric' id='qty' name='qty' value='1' ></td>
                    <td colspan=1><button type='button' class='btn btn-primary'  id='btnPrintBcode' name='btnPrintBcode'><i class='fa fa-print'></i> Imprimir</button></td>
                  </tr>

            </tbody>
          </table>
            <input type='hidden'  class='form-control' id='id_prodd' name='id_prodd' value='<?=$id_producto;?>' ></td>
        </div>
        <?php if ($imagen!="") { ?>
          <!--Widgwt imagen-->
          <div class="col-lg-12 center-block">
            <div class="widget style1 gray-bg text-center">
              <div class="m-b-md" id='imagen'>
                <img alt="image" class="img-rounded" src=<?php echo $imagen; ?> width="250px" height="150px" border='1'>
              </div>
            </div>
            <div class="span12 text-center"><strong><?php echo $descripcion; ?></strong></div>
          </div>
          <!--Fin Widgwt imagen-->
        <?php }
        else{
          $descripcion=$descripcion. " , No tiene imagen asignada";
          echo "<div class='span12 text-center'><strong>$descripcion</strong></div>";
        }
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
    echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div></div></div></div></div>";
  }
  ?>
