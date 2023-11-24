<?php
include("_core.php");
require_once '_helper_dte.php';
function initial()
{
    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];

    $uri = $_SERVER['SCRIPT_NAME'];
    $filename=get_name_script($uri);
    $links=permission_usr($id_user, $filename);
    //credenciales MH
    $dat=   getCRedential();
    //Verificar si hay un token de esta fecha, sino existe reemplazar, sino insertar
    $row_tk=token_dia();

    $fecha=date('Y-m-d');
    $mensaje="No autenticado en fecha:". $fecha;
    $token="";
    $auth=0;
    if ($row_tk!=null) {
        $mensaje="AUTENTICADO  en fecha:". $fecha;
        $token=$row_tk['token'];
        $auth=1;
    } ?>


	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
		<h4 class="modal-title">Establecer Conexión</h4>
	</div>
	<div class="modal-body">
		<div class="wrapper wrapper-content  animated fadeInRight">
			<div class="row">
				<div class="col-lg-12">
                <input type="hidden" id="urrl" value="<?php echo $dat['url']; ?>">
                <input type="hidden" id="ussr" value="<?php echo $dat['user']; ?>">
                <input type="hidden" id="pwwd" value="<?php echo $dat['pwd']; ?>">
                <div id="mensaje"><?php echo $mensaje; ?></div>
                <textarea id= "tok" name= "tok" class='form-control' style= "border-width: medium;"><?php echo $token; ?></textarea>
				</div>
      </div>
     
			<input type="hidden" name="process" id="process" value="initial">
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-primary" id="btnCnx">Conectar</button>
		<button type="button" class="btn btn-default" id="closeM" data-dismiss="modal">Cerrar</button>
	</div>
    <?php

echo '<script src="js/plugins/axios/axios.min.js"></script>';
    // echo '<script src="js/funciones/getAuth.js"></script>';?> 
  <script type="text/javascript">
   
        $(document).on("click", "#viewModal .modal-footer #btnCnx", function(e){
            conex()
        });
        let conex=()=>{
            const url= document.getElementById('urrl').value;
            let ussr= document.getElementById('ussr').value;
            let pwd = document.getElementById('pwwd').value;
           
            let dataString =  `user=${ussr}&pwd=${pwd}`
            axios.post(url,dataString)
            .then(function (response) {
            let resp = response.data
            respuesta(resp);
            })
            .catch(function (error) {
            console.log(error);
            });
        }
        let respuesta=(resp)=>{
            let status=resp.status
            let mensaje = document.getElementById('mensaje')
            let tok = document.getElementById('tok')
            if(status='OK'){
                let token=resp.body.token;
                mensaje.innerHTML =  `<p>status=${status}</p>`;
                tok.textContent= token;
                console.log(resp);
                guardaToken(token)
            }
            if(status='ERROR'){
                console.log(resp);
            }
        }
        let guardaToken=(token)=>{
            let url2='cnx_mh.php'
            let dataString =  `process=save_token&token=${token}`
            alert(dataString)
            axios.post(url2,dataString)
            .then(function (response) {
            let resp = response.data
            display_notify(resp.typeinfo, resp.msg);
            })
            .catch(function (error) {
            console.log(error);
            });

        }
  </script>
<?php
}
function save_token()
{
    $ins=false;
    $token=$_REQUEST['token'];
    $id_sucursal=$_SESSION['id_sucursal'];
    $fecha=date('Y-m-d');
    $wc=" id_sucursal='$id_sucursal'
           AND fecha='$fecha'";
    $hora=date("H:i:s");

    $q="SELECT id, fecha, hora, token, id_sucursal 
    FROM token_auth_dia
    WHERE $wc ";
    $res = _query($q);
    $n   = _num_rows($res);
    $fd= array(
        'fecha'=>$fecha,
        'hora'=>$hora,
        'token'=>$token,
        'id_sucursal'=>$id_sucursal,
    );
    if ($n==0) {
        $ins=_insert('token_auth_dia', $fd);
        $id= _insert_id();
    } else {
        $ins=_update('token_auth_dia', $fd, $wc);
    }
    if ($ins) {
        $xdatos['typeinfo']='Success';
        $xdatos['msg']='Token actualizado con exito!, fecha:'.$fecha;
    } else {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Token no pudo ser actualizado, fecha:'.$fecha;
    }
    echo json_encode($xdatos);
}
if (! isset($_REQUEST ['process'])) {
    initial();
} else {
    if (isset($_REQUEST ['process'])) {
        switch ($_REQUEST ['process']) {
            case 'initial':
                initial();
                break;
            case 'save_token':
                save_token();
            break;
            }
    }
}
?>
