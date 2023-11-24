<?php
include_once "_core.php";
function initial()
{
    $title = 'Agregar Cliente';
    $_PAGE = array();
    $_PAGE ['title'] = $title;
    $_PAGE ['links'] = null;
    $_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/chosen/chosen.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/jqGrid/ui.jqgrid.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';

    include_once "header.php";
    include_once "main_menu.php";
    //permiso del script
    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];
    $uri = $_SERVER['SCRIPT_NAME'];
    $filename=get_name_script($uri);
    $links=permission_usr($id_user, $filename); ?>
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-2">
    </div>
  </div>
  <div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
      <div class="col-lg-12">
        <div class="ibox">
          <?php
          //permiso del script
          if ($links!='NOT' || $admin=='1') {
              ?>
            <div class="ibox-title">
              <h5><?php echo $title; ?></h5>
            </div>
            <div class="ibox-content">
              <form name="formulario" id="formulario">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group has-info single-line">
                      <label>Nombre  <span style="color:red;">*</span></label>
                      <input type="text" placeholder="Nombre del Cliente" class="form-control" id="nombre" name="nombre">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group has-info single-line">
                      <label>Nombre (Empresa o Negocio) <span style="color:red;">*</span></label>
                      <input type="text" placeholder="Nombre del Negocio" class="form-control" id="negocio" name="negocio">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group has-info single-line">
                      <label>Dirección</label>
                      <textarea placeholder="Dirección" class="form-control" id="direccion" name="direccion" rows="3" cols="80"></textarea>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group has-info single-line">
                      <label>Departamento <span style="color:red;">*</span></label>
                    
                                    <?php
                                $array0 =getDepartamentos();
              $select0=crear_select("departamento", $array0, $departamento, "width:100%;");
              echo $select0; ?>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group has-info single-line">
                      <label>Municipio <span style="color:red;">*</span></label>
                      <select class="col-md-12 select" id="municipio" name="municipio">
                        <option value="">Primero seleccione un departamento</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group has-info single-line">
                      <label>Categoria del Cliente <span style="color:red;">*</span></label>
                      <select class="col-md-12 select" id="categoria" name="categoria">
                        <?php
                        $sqld = "SELECT * FROM categoria_proveedor";
              $resultd=_query($sqld);
              while ($depto = _fetch_array($resultd)) {
                  echo "<option value='".$depto["id_categoria"]."'";

                  echo">".$depto["nombre"]."</option>";
              } ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group has-info single-line">
                      <label>DUI</label>
                      <input type="text" placeholder="00000000-0" class="form-control" id="dui" name="dui">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group has-info single-line">
                      <label>NIT  <span style="color:red;">*</span></label>
                      <input type="text" placeholder="0000-000000-000-0" class="form-control" id="nit" name="nit">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group has-info single-line">
                      <label>NRC  <span style="color:red;">*</span></label>
                      <input type="text" placeholder="Registro" class="form-control" id="nrc" name="nrc">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group has-info single-line">
                      <label>Giro  <span style="color:red;">*</span></label>
                      <select class="col-md-12 select" id="sel_giro" name="sel_giro">
                                        <option value=''></option>
                                    </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group has-info single-line">
                      <div class='checkbox i-checks'><label><input id='retiene' name='retiene' type='checkbox'> <span class="label-text"><b>Retiene</b></span></label></div>
                      <input type="hidden" name="hi_retiene" id="hi_retiene" value="0">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-3" hidden="true" id="retiene_select">
                    <div class="form-group has-info single-line">
                      <label>Porcentaje de Retención <span style="color:red;">*</span></label>
                      <select class="col-md-12 select" id="porcentaje" name="porcentaje">
                        <option value="0">Sin Retención</option>
                        <option value="1">1%</option>
                        <option value="10">10%</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group has-info single-line">
                      <label>Teléfono 1 <span style="color:red;">*</span></label>
                      <input type="text" placeholder="0000-0000" class="form-control tel" id="telefono1" name="telefono1">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group has-info single-line">
                      <label>Teléfono 2</label>
                      <input type="text" placeholder="0000-0000" class="form-control tel" id="telefono2" name="telefono2">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group has-info single-line">
                      <label>Codigo Cliente</label>
                      <input type="text" placeholder="" class="form-control" id="codigocliente" name="codigocliente">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group has-info single-line">
                      <label>Correo</label>
                      <input type="text" placeholder="mail@server.com" class="form-control" id="correo" name="correo">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-3 form-group">
                    <label>Vendedor</label>
                  <select class="form-control select" name="id_vendor" id="id_vendor">
                    <!--option value="0">Seleccione</option-->
                    <?php

                    $rowV = getVendedor();
              $nr=_num_rows($rowV);
              for ($i=0;$i<$nr;$i++) {
                  $row = _fetch_array($rowV);
                  echo "<option value='".$row['id_empleado']."'>";
                  echo "".utf8_decode(Mayu(utf8_decode($row['nombre'])));
                  echo "</option>";
              } ?>
                  </select>
                </div>
                <div class='col-lg-3'>
                  <div class='form-group has-info'>
                    <label>Fecha Nacimiento:</label>
                    <input type='text' class='datepick form-control' value='' id='fecha1' name='fecha1'>
                  </div>
                </div>
                <div class='col-lg-3'>
                  <div class='form-group has-info'>
                    <label>Días Crédito:</label>
                    <input type='text' class='form-control numeric'  id='dias_credito' name='dias_credito'  value="0">
                  </div>
                </div>
                <div class='col-lg-3'>
                  <div class='form-group has-info'>
                    <label>Limite Crédito:</label>
                    <input type='text' class='form-control decimal'  id='limite_credito' name='limite_credito'  value="0">
                  </div>
                </div>
                </div>
                <input type="hidden" name="process" id="process" value="insert"><br>
                <!--?php getDif() ?-->
                <div>
                  <input type="submit" id="submit1" name="submit1" value="Guardar" class="btn btn-primary m-t-n-xs" />
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
    include_once("footer.php");
              $uniqueId=uniqidReal();
              echo "<script src='js/funciones/funciones_cliente.js?v=$uniqueId'></script>";
          } //permiso del script
    else {
        echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
    }
}

function insertar()
{
    $nombre=$_POST["nombre"];
    $negocio=$_POST["negocio"];
    $direccion=$_POST["direccion"];
    $departamento=$_POST["departamento"];
    $municipio=$_POST["municipio"];
    $dui=$_POST["dui"];
    $nit=$_POST["nit"];
    $nrc=$_POST["nrc"];
    $giro=$_POST["giro"];
    $categoria=$_POST["categoria"];
    $porcentaje=$_POST["porcentaje"];
    if ($porcentaje == 1) {
        $retiene = 1;
        $retiene10 = 0;
    } elseif ($porcentaje == 0) {
        $retiene = 0;
        $retiene10 = 0;
    } else {
        $retiene = 0;
        $retiene10 = 1;
    }
    if (isset($_POST['percibe'])) {
        $percibe = 1;
    } else {
        $percibe = 0;
    }
    $telefono1=$_POST["telefono1"];
    $telefono2=$_POST["telefono2"];
    $codigocliente=$_POST["codigocliente"];
    $correo=$_POST["correo"];
    $id_vendedor=$_POST["id_vendor"];
    if (isset($_POST['fecha1'])) {
        $fecha_nac = $_POST['fecha1'];
    }
    $dias_credito=$_POST["dias_credito"];
    $limite_credito = $_POST["limite_credito"];
    $id_sucursal = $_SESSION["id_sucursal"];
    $sql_exis=_query("SELECT id_cliente FROM cliente WHERE nombre ='$nombre' AND id_sucursal='$id_sucursal' AND nit='$nit'");
    $num_exis = _num_rows($sql_exis);
    $array = $_POST["valjson"];
    $data = json_decode($array, true);
    if ($num_exis > 0) {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Ya se registro un cliente con estos datos!';
    } else {
        $table = 'cliente';
        $form_data = array(
      'categoria' => $categoria,
      'nombre' => $nombre,
      'negocio' => $negocio,
      'direccion' => $direccion,
      'municipio' => $municipio,
      'depto' => $departamento,
      'nrc' => $nrc,
      'nit' => $nit,
      'dui' => $dui,
      'giro' => $giro,
      'telefono1' => $telefono1,
      'telefono2' => $telefono2,
      'codcliente' => $codigocliente,
      'email' => $correo,
      'retiene' => $retiene,
      'percibe' => $percibe,
      'retiene10' => $retiene10,
      'id_sucursal' => $id_sucursal,
      'fecha_nac'=> $fecha_nac,
      'id_vendedor' => $id_vendedor,
      'dias_credito'=>$dias_credito,
      'limite_credito' => $limite_credito,
      );
        $insertar   = _insert($table, $form_data);
        $id_cliente = _insert_id();
        //dif
        $table2='cliente_dif';
        foreach ($data as $fila) {
            $num_dif = $fila['num_dif'];
            $embarc  = $fila['embarc'];
            $fi  		 = $fila['fi'];
            $ff 		 = $fila['ff'];
            $limgal	 = $fila['limgal'];

            $sql0="SELECT *
                  FROM $table2
                  WHERE numero_dif='$num_dif'
                  ";
            $sql=_query($sql0);
            $row=_fetch_array($sql);
            $nrow=_num_rows($sql);

            if ($nrow==0) {
                $form_data2 = array(
                'id_cliente'   => $id_cliente,
                'numero_dif'   => $num_dif,
                'embarcacion'   => $embarc,
                'fecha_inicio' => $fi,
                'fecha_fin'    => $ff,
                'limite_galon' => $limgal,
                'activo'       => 1,
                );
                $update2 = _insert($table2, $form_data2);
            }
        }
        if ($insertar) {
            $xdatos['typeinfo']='Success';
            $xdatos['msg']='Registro guardado con exito!';
            $xdatos['process']='insert';
        } else {
            $xdatos['typeinfo']='Error';
            $xdatos['msg']='Registro no pudo ser guardado !'._error();
        }
    }
    echo json_encode($xdatos);
}
function municipio()
{
    $id_departamento = $_POST["id_departamento"];
    $option = "";
    $sql_mun = _query("SELECT * FROM municipio WHERE id_departamento_municipio='$id_departamento'");
    while ($mun_dt=_fetch_array($sql_mun)) {
        $option .= "<option value='".$mun_dt["id_municipio"]."'>".$mun_dt["nombre_municipio"]."</option>";
    }
    echo $option;
}

function getDif()
{
    ?>
  <div class="row">
    <div class="col-lg-12"><br>
      <div class=" alert-warning text-center"  style="font-weight: bold; border: 2px solid #8a6d3b; border-radius: 25px; margin-bottom:20px;">
        <h3>Registro Embarcaciones</h3>
        <h3>Documento de Identificación para la exoneración de la Contribución para la Conservación Vial (DIF)</h3>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-2">
      <div class="form-group has-info single-line">
        <!--
        SELECT id_dif, id_cliente, numero_dif, embarcacion, fecha_inicio, fecha_fin, limite_galon, activo FROM cliente_dif WHERE 1
        -->
        <label>Número DIF</label>
        <input type="text" name="numero_dif" id="numero_dif" class="form-control clear">
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group has-info single-line">
        <label>Nombre Embarcación</label>
        <input type="text" name="embarcacion" id="embarcacion" class="form-control clear">
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group has-info single-line">
        <label>Fecha Ext.</label>
        <input type="text" name="fecha_inicio" id="fecha_inicio" class="form-control clear  datepick">
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group has-info single-line">
        <label>Fecha Fin</label>
        <input type="text" name="fecha_fin" id="fecha_fin" class="form-control clear datepick">
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group has-info single-line">
        <label>Límite Galón</label>
        <input type="text" name="lmite_galon" id="limite_galon" class="form-control clear decimal">
      </div>
    </div>
    <!--div  class="col-md-1">
      <div class="form-group has-info single-line">
        <label>Activo</label>
        <div class='checkbox i-checks'>
          <label>
            <input type='checkbox'  id='activo' name='activo' value='1'><i></i>
          </label>
        </div>
      </div>
    </div-->


    <div class="col-md-1">
      <div class="form-group has-info">
        <br>
        <br>
        <a  class="btn btn-primary" id="add_pre"><i class="fa fa-plus"></i> Agregar</a>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <table class="table table-hover table-striped table-bordered">
        <thead>
          <tr>
            <th class="col-md-1">DIF</th>
            <th class="col-md-1">Embarcación</th>
            <th class="col-md-1">Fecha Ext.</th>
            <th class="col-md-1">Fecha Fin</th>
            <th class="col-md-1">Limite Gal.</th>
            <th class="col-md-1">Estado</th>
            <th class="col-md-1">Acción</th>
          </tr>
        </thead>
        <tbody id="presentacion_table">

        </tbody>
      </table>
    </div>
  </div>
<?php
}

if (!isset($_POST['process'])) {
    initial();
} else {
    if (isset($_POST['process'])) {
        switch ($_POST['process']) {
      case 'insert':
      insertar();
      break;
      case 'municipio':
      municipio();
      break;

    }
    }
}
?>
