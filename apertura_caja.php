<?php
include_once "_core.php";
function initial()
{
    $title = 'Apertura de caja v.08062022';
	$_PAGE = array ();
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
    date_default_timezone_set('America/El_Salvador');
  $caja=0;
  // verificar que la caja sea de pista para agregar el galonaje
  // inicial del tanque e imprimir en el corte
  // luego en el corte comparar con la suma de cada tipo de combustible
  //  crear tabla de tanques de combustible: super, regular y diesel
  // y llevar el galonaje diario  por tanque
  $fecha_actual = date('Y-m-d');
  $qf="SELECT max(fecha) as fecha_anterior FROM tanque_diario
                  WHERE fecha<'$fecha_actual'";
  $rf = _query($qf);

  /*
  if( _num_rows($rf)>0){
    $resp=_fetch_row($rf);
    $fecha_ante=$resp[0];
    $qt="SELECT max(td.id_stock),td.id_tanque, t.descripcion, td.galones_dia, td.fecha
    FROM tanque AS t
    JOIN tanque_diario  AS td
    ON t.numero=td.id_tanque
    WHERE fecha='$fecha_ante'
    group by td.id_tanque";
    $rt = _query($qt);
    $numrt =  _num_rows($rt);
    if($numrt==0){
      $qt="SELECT t.numero AS id_tanque, t.descripcion, 0
      FROM tanque AS t
      ";
      $rt = _query($qt);
      $numrt =  _num_rows($rt);
    }
  }*/
  $id_sucursal = $_SESSION["id_sucursal"];
  $id_user=$_SESSION["id_usuario"];

  $admin=$_SESSION["admin"];

  $qt = "SELECT t.numero AS id_tanque, t.descripcion, 0
         FROM tanque AS t WHERE id_sucursal='$id_sucursal'  ";
  $rt = _query($qt);
  $numrt =  _num_rows($rt);

  if (isset($_REQUEST["id_caja"])) {
    $caja = $_REQUEST["id_caja"];
  }

  $uri = $_SERVER['SCRIPT_NAME'];
  $filename=get_name_script($uri);
  $links=permission_usr($id_user,$filename);

  $q = "SELECT * FROM apertura_caja WHERE vigente = 1
  AND id_sucursal = '$id_sucursal'
  AND id_empleado = '$id_user' AND fecha = '$fecha_actual'";
  $sql_apertura = _query($q);
  $cuenta_apertura = _num_rows($sql_apertura);
  if($cuenta_apertura == 0)
  {
?>
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
        			if ($links!='NOT' || $admin=='1' ){
    			?>
                <div class="ibox-title">
                    <h5><?php echo $title; ?></h5>
                </div>
                <div class="ibox-content">
                    <form name="formulario" id="formulario">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group has-info single-line">
                                    <label>Fecha <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="fecha" name="fecha" value="<?php echo date('Y-m-d');?>" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">

                                <div hidden><!--antiguamente incluso el admin puede usar caja -->
                                  <div class="form-group single-line">
                                   <label for="nombre">Nombre</label>
                                      <!--input type="text" name="nombre" id="nombre" class="form-control mayu"  value="<?php echo $cajero;?>" readonly-->
                                  </div>
                                  <?php
                                    if($admin != 1)
                                      {
                                        echo "<input type='hidden' class='form-control' id='empleado' name='empleado' value='".$id_user."'>";
                                    }?>
                                </div>

                                <div class="form-group has-info single-line">
                                    <label>Empleado <span style="color:red;">*</span></label>
                                        <?php
                                          if($admin != 1)
                                            {
                                              /*
                                              $sql_empleado = _query("SELECT empleado.* FROM empleado,usuario WHERE usuario.id_empleado = empleado.id_empleado AND  usuario.id_usuario='$id_user'");
                                              $cuen = _num_rows($sql_empleado);
                                              $row_empleado = _fetch_array($sql_empleado);

                                              $id_usuario = $id_user;
                                              $nombre = $row_empleado["nombre"];
                                              */
                                              $nombre = getCajero($id_user);
                                              echo "<input type='text' class='form-control' id='nombre' name='nombre' value='".$nombre."' readOnly>";

                                                echo "<input type='hidden' class='form-control' id='empleado' name='empleado' value='".$id_user."'>";
                                            }
                                            else
                                            {
                                              $sql_empleado = _query("SELECT * FROM empleado,usuario WHERE usuario.id_empleado = empleado.id_empleado AND empleado.id_tipo_empleado = 3");
                                              $cuen = _num_rows($sql_empleado);

                                              echo "<select class='form-control select' id='empleado' name='empleado'>";
                                              echo "<option value='".$id_user."'>".$_SESSION["nombre"]."</option>";
                                              while ($row_empleado = _fetch_array($sql_empleado))
                                              {
                                                $id_usuario = $row_empleado["id_usuario"];
                                                $nombre = $row_empleado["nombre"];
                                                echo "<option value='".$id_usuario."'>".$nombre."</option>";
                                              }

                                              echo "</select>";
                                            }
                                        ?>
                                </div>

                          </div>


                        </div>
                        <div class="row">
                            <div  class="col-md-6">
                                <div  class="form-group has-info single-line">
                                    <label>Caja <span style="color:red;">*</span></label>
                                    <select class="form-control select" id="caja" name="caja">
                                      <option value='-1'>Seleccione</option>
                                         <?php
                                          $no_disp=1;
                                          $q = "SELECT * FROM caja WHERE id_sucursal = '$id_sucursal' AND activa = 1 ORDER BY id_caja ASC ";
                                         $qsucursal=_query($q);

                                         while($row_caja =_fetch_array($qsucursal))
                                         {
                                             $id_caja      = $row_caja["id_caja"];
                                             $tipo_caja    = $row_caja["tipo_caja"];
                                             $sql_consulta = _query("SELECT * FROM apertura_caja WHERE caja = '$id_caja' AND vigente = 1");
                                             $cuenta = _num_rows($sql_consulta);
                                             $nombre_caja=$row_caja["nombre"];
                                             if($cuenta == 0)
                                             {
                                               echo "
                                               <option value='".$id_caja."'";
                                               if($caja == $id_caja)
                                               {
                                                 echo "selected";
                                               }
                                               echo ">".$nombre_caja."</option>
                                               ";
                                               $no_disp=0;
                                             }

                                         }
                                         if($no_disp==1){
                                           echo " <option value='-2' selected>No cajas disponibles</option>";
                                         }
                                         ?>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-info single-line">
                                    <label>Monto Apertura <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control numeric" id="monto_apertura" name="monto_apertura">
                                </div>
                            </div>
                        </div>
                        <div hidden class="row">
                          <div class="col-md-6">
                              <div class="form-group has-info single-line">
                                  <label>Monto Caja Chica <span style="color:red;">*</span></label>
                                  <input type="text" class="form-control numeric" id="monto_ch" name="monto_ch">
                              </div>
                          </div>
                        </div>
                        <?php
                            $fecha_i = date('Y-m-d');
                            $sql_turno = _query("SELECT * FROM apertura_caja WHERE fecha = '$fecha_i' AND id_sucursal='$id_sucursal' ORDER BY id_apertura DESC LIMIT 1");
                            $cuenta_turno = _num_rows($sql_turno);
                            if($cuenta_turno > 0)
                            {
                                $row_ap = _fetch_array($sql_turno);
                                $turno_ap = $row_ap['turno'];
                                $sigue_turno = $turno_ap + 1;
                                echo "<input type='hidden' class='form-control' id='turno' name='turno' value='".$sigue_turno."'>";
                            }
                            else
                            {
                                echo "<input type='hidden' class='form-control' id='turno' name='turno' value='1'>";
                            }
                        ?>
                        <input type="hidden" name="process" id="process" value="insert">
                        <input type="hidden" name="tipo_caja" id="tipo_caja" value="1">
                        <input type="hidden" name="galonaje" id="galonaje" value="">
                        <input type="hidden" name="admin " id="admin"  value="<?php echo $admin;?>">
                        <input type="hidden" name="id_sucursal" id="id_sucursal" value="<?php echo $id_sucursal;?>"><br>

                        <div class='row' id ='tanques' hidden>

                          <div class="col-md-12">
                            <div class="table-responsive">
                            <table class="table" id='loadtable'>
                              <thead class='thead1'>
                                <tr>
                                  <th >Tanque</th>
                                  <th >Descripción</th>
                                  <th >Gal.  Anterior</th>
                                  <th >Gal.  Actual</th>
                                </tr>
                              </thead>
                              <tbody class='tbody1 table' id="tank">
                                <?php if ($numrt>0){
                                  for ($i=0;$i<$numrt;$i++){
                                   $rowt = _fetch_array($rt);
                                    $id_tank = $rowt['id_tanque']; ?>
                                     <tr>
                                     <td><?php echo $id_tank ?></td>
                                     <td><?php echo $rowt['descripcion'] ?></td>

                                    <?php
                                    $q= "SELECT  td.galones_dia,td.fecha
                                        FROM tanque_diario  AS td
                                        WHERE fecha<='$fecha_actual'
                                        AND id_sucursal='$id_sucursal'
                                        AND td.id_tanque='$id_tank'
                                        ORDER BY fecha DESC  LIMIT 1  ";
                                      $r = _query($q) ;
                                      $nrows=_num_rows($r);
                                      if($nrows>0){
                                        $rowgal=_fetch_array($r);
                                        $galdia = $rowgal['galones_dia'];
                                        $fecha  = $rowgal['fecha'];
                                        echo "<td>".$galdia."</td>";
                                        if ($fecha==$fecha_actual){
                                          echo "<td><input type='text' id='gal_dia'  class='form-control decimal'
                                             name='gal_dia' value='".$galdia."' readonly ></td> ";
                                        }else {
                                          echo "<td><input type='text' id='gal_dia'  class='form-control decimal'
                                               name='gal_dia' value=''  ></td> ";
                                        }
                                      }
                                      else{
                                         echo "<td>&nbsp;</td>";
                                         echo "<td><input type='text' id='gal_dia'  class='form-control decimal'
                                              name='gal_dia' value=''  ></td> ";
                                      }
                                     ?>
                                   </tr>
                                   <?php
                                  }
                                }?>
                              </tbody>
                            </table>
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
    </div>
</div>
<?php
}

	} //permiso del script
    else
    {
    		echo "<div></div><br><br><div class='alert alert-warning'>Ya hay una apertura de caja vigente. Por favor realize el corte de caja</div>";
    }
    include_once ("footer.php");
    echo '<script src="js/plugins/axios/axios.min.js"></script>';
    echo "<script src='js/funciones/funciones_apertura.js'></script>";
}
function apertura()
{
    date_default_timezone_set('America/El_Salvador');
    $fecha = $_POST["fecha"];
    $empleado = $_POST["empleado"];
    $turno = $_POST["turno"];
    $monto_apertura = $_POST["monto_apertura"];
    $id_sucursal = $_POST["id_sucursal"];
    $hora_actual = date('H:i:s');
    $caja = $_POST["caja"];
    $monto_ch = $_POST["monto_ch"];
    $tabla = "apertura_caja";
    $id_sucursal = $_SESSION["id_sucursal"];
    $turno_real = 1;
    $fecha_i = date('Y-m-d');
    if($caja>=0)
    {
      //datos caja
    $rowCaja   =  getDatosCaja($caja);
    $tipo_caja = $rowCaja['tipo_caja'];


    $sql_turno = _query("SELECT * FROM apertura_caja WHERE fecha = '$fecha_i'
       AND id_sucursal='$id_sucursal' AND caja = '$caja' ORDER BY id_apertura DESC LIMIT 1");
    $cuenta_turno = _num_rows($sql_turno);

    if($cuenta_turno > 0)
    {
        $row_ap = _fetch_array($sql_turno);
        $turno_ap = $row_ap['turno'];
        $turno_real = $turno_ap + 1;

    }
    else
    {
        $turno_real = 1;
    }
    $form_data = array(
        'fecha' => $fecha,
        'id_empleado' => $empleado,
        'turno' => $turno_real,
        'monto_apertura' => $monto_apertura,
        'vigente' => 1,
        'id_sucursal' => $id_sucursal,
        'hora' => $hora_actual,
        'turno_vigente' => 1,
        'caja' => $caja,
        'monto_ch' => $monto_ch,
        'monto_ch_actual' => $monto_ch,
        );
    $sql_caja = _query("SELECT * FROM apertura_caja WHERE vigente = 1 AND id_sucursal = '$id_sucursal' AND caja = '$caja'");
    $cuenta1 = _num_rows($sql_caja);
    if($cuenta1 == 0)
    {
        $insertar = _insert($tabla, $form_data);
        if($insertar)
        {
            $id_apertura = _insert_id();
            $tabla1 = "detalle_apertura";
            $form_data1 = array(
                'id_apertura' => $id_apertura,
                  'id_sucursal' => $id_sucursal,
                'turno' => $turno_real,
                'id_usuario' => $empleado,
                'fecha' => $fecha,
                'hora' => $hora_actual,
                'vigente' => 1,
                'caja' => $caja,
                );
            $insert_de = _insert($tabla1,$form_data1);
            if($insert_de)
            {
                $xdatos['typeinfo']  = 'Success';
                $xdatos['msg']       = 'Apertura de caja realizada correctamente !';
                $xdatos['tipo_caja'] = $tipo_caja;
                $xdatos['process']   = 'insert';
            }
            else
            {
                $xdatos['typeinfo']='Error';
                $xdatos['msg']='Fallo agregar el turno!'._error();
                $xdatos['tipo_caja'] = -1;
            }

            //si es caja de pista, agregar galones de tanque
            if(  $tipo_caja ==2 && isset($_POST["galonaje"])) {
              $galonaje = $_POST["galonaje"];
              $array = json_decode($galonaje, true);

              $td = "tanque_diario";
              foreach ($array as $fila) {
                $id_tank=$fila['id_tank'];
                $galones=$fila['galones'];

                $q= "SELECT  td.galones_dia,td.fecha
                    FROM tanque_diario  AS td
                    WHERE fecha='$fecha'
                    AND td.id_tanque='$id_tank'
                    AND td.id_sucursal='$id_sucursal'
                    ORDER BY fecha DESC  LIMIT 1  ";
                  $r = _query($q) ;
                  $nrows=_num_rows($r);
                  if($nrows==0){
                    $f_td = array(
                        'id_server' => $id_tank,
                        'id_tanque' => $id_tank,
                        'galones_dia' => $galones,
                        'fecha' => $fecha,
                        'id_sucursal' => $id_sucursal,
                        );
                    $insert_td = _insert($td,$f_td);
                 }
              }
            }


        }
        else
        {
            $xdatos['typeinfo']='Error';
            $xdatos['msg']='La apertura no se pudo realizar!'._error();
            $xdatos['tipo_caja'] = -1;
        }
    }
    else
    {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Ya existe una apertura de caja vigente en esta caja!';
        $xdatos['tipo_caja'] = -1;
    }
  }
  else
  {
      $xdatos['typeinfo']='Error';
      $xdatos['msg']='No se puede insertar una caja que no existe!';
      $xdatos['tipo_caja'] = -1;
  }
    echo json_encode($xdatos);
}
function apertura_turno()
{
    date_default_timezone_set('America/El_Salvador');
    $fecha = date("Y-m-d");
    $hora_actual = date('H:i:s');
    $id_apertura = $_POST["id_apertura"];
    $id_detalle = $_POST["id_detalle"];
    $emp = $_SESSION["id_usuario"];

    $sql_com = _query("SELECT * FROM detalle_apertura WHERE id_detalle = '$id_detalle'");
    $cuenta =_num_rows($sql_com);
    if($cuenta == 1)
    {
        $tabla = "detalle_apertura";
        $form_data = array(
            'id_usuario' => $emp,
          );
        $where_d = "id_detalle='".$id_detalle."'";
        $update_d = _update($tabla, $form_data, $where_d);
        $tabla = "apertura_caja";
        $form_data = array(
            'id_empleado' => $emp,
            );
        $where_d = "id_apertura='".$id_apertura."'";
        $update_d = _update($tabla, $form_data, $where_d);
        if($update_d)
        {
            $xdatos['typeinfo']='Success';
            $xdatos['msg']='Turno agregado correctamente!';
            $xdatos['process']='insert';
        }
        else
        {
            $xdatos['typeinfo']='Error';
            $xdatos['msg']='Fallo al agregar el turno!'._error();
        }
    }
    else
    {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='No existe un turno para asignar!'._error();
    }
    echo json_encode($xdatos);
}

function cerrar_turno()
{
    date_default_timezone_set('America/El_Salvador');
    $fecha = date("Y-m-d");
    $hora_actual = date('H:i:s');
    $id_apertura = $_POST["id_apertura"];
    $sql_turno = _query("SELECT * FROM detalle_apertura WHERE id_apertura = '$id_apertura' ORDER BY turno DESC LIMIT 1");
    $row_turno = _fetch_array($sql_turno);
    $tuno = $row_turno["turno"];
    $id_usuario = $row_turno["id_usuario"];

    $sql_turno = _query("SELECT * FROM detalle_apertura WHERE id_apertura = '$id_apertura' AND vigente = 1 ");
    $row_turno = _fetch_array($sql_turno);
    $id_detalle = $row_turno["id_detalle"];
    //echo "ok";
    $n_tuno = $tuno + 1;
    $tabla = "detalle_apertura";
    $form_data = array(
        'vigente' => 0
        );
    $where_up = "id_detalle='".$id_detalle."'";
    $update = _update($tabla, $form_data, $where_up);
    if($update)
    {
        $tabla1 = "detalle_apertura";
        $form_data1 = array(
            'id_apertura' => $id_apertura,
            'turno' => $n_tuno,
            'fecha' => $fecha,
            'hora' => $hora_actual,
            'vigente' => 1
            );
        $insert = _insert($tabla1, $form_data1);
        if($insert)
        {
            $tabla1 = "apertura_caja";
            $form_data1 = array(
                'turno' => $n_tuno,
                'turno_vigente' => 1,
                );
            $where_up = "id_apertura='".$id_apertura."'";
            $update1 = _update($tabla1, $form_data1, $where_up);
            if($update1)
            {
                $xdatos['typeinfo']='Success';
                $xdatos['msg']='Turno agregado correctamente!';
                $xdatos['process']='insert';
            }
        }
    }
    else
    {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Fallo al agregar el turno!'._error();
    }

  echo json_encode($xdatos);
}
//caja por id
function getDatoCaja(){
  $caja = $_POST["caja"];
  $sql_caja = _query("SELECT * FROM caja WHERE id_caja='$caja'");
  $dats_caja = _fetch_array($sql_caja);
  $xdatos['datos']=$dats_caja;
  echo json_encode($dats_caja);
}
function getTank(){
   if ($numrt>0){
     for ($i=0;$i<$numrt;$i++){
      $rowt = _fetch_array($rt);  ?>
        <tr>
        <td><?php echo $rowt['id_tanque'] ?></td>
        <td><?php echo $rowt['galones_dia'] ?></td>
        <td><input type='text' id='galones_hoy'  class='form-control decimal'
           name='total_galon' value='0' ></td>
      </tr>
      <?php
     }
   }
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
                apertura();
                break;
            case 'apertura_turno':
                apertura_turno();
                break;
            case 'cerrar_turno':
                cerrar_turno();
                break;
            case 'getCaja':
                getDatoCaja();
                break;
        }
    }
}
?>
