<?php
    include("_core.php");
    // Page setup
    $_PAGE = array();
    $_PAGE ['title'] = 'Administrar Sucursal';
    $_PAGE ['links'] = null;
    $_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/chosen/chosen.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/jqGrid/ui.jqgrid.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';
    include_once "header.php";
    include_once "main_menu.php";

    $sql="SELECT * FROM sucursal";
    $result=_query($sql);
    $count=_num_rows($result);

        //permiso del script
        $id_user=$_SESSION["id_usuario"];
        $admin=$_SESSION["admin"];
        $uri = $_SERVER['SCRIPT_NAME'];
        $filename=get_name_script($uri);
        $links=permission_usr($id_user, $filename);
    //permiso del script
    if ($links!='NOT' || $admin=='1') {
        ?>

<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row" id="row1">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<?php
                echo "<div class='ibox-title'>";
        $filename='agregar_sucursal.php';
        $link=permission_usr($id_user, $filename);
        if ($link!='NOT' || $admin=='1') {
            echo "<a href='agregar_sucursal.php' class='btn btn-primary' role='button'><i class='fa fa-plus icon-large'></i> Agregar Sucursal</a>";
        }
        echo "	</div>"; ?>
				<div class="ibox-content">
					<!--load datables estructure html-->
					<header>
						<h4>Administrar Sucursal</h4>
					</header>
					<section>
						<table class="table table-striped table-bordered table-hover"id="editable">
							<thead>
								<tr>
									<th>Id Sucursal</th>
									<th>Nombre</th>
									<th>Dirección</th>
									<th>Acción</th>
								</tr>
							</thead>
							<tbody> 
				<?php
                    if ($count>0) {
                        for ($i=0;$i<$count;$i++) {
                            $row=_fetch_array($result);
                            echo "<tr>";
                            echo"<td>".$row['id_sucursal']."</td>
								<td>".$row['descripcion']."</td>
								<td>".$row['direccion']."</td>
								";
                            echo"<td><div class=\"btn-group\">
								<a href=\"#\" data-toggle=\"dropdown\" class=\"btn btn-primary dropdown-toggle\"><i class=\"fa fa-user icon-white\"></i> Menu<span class=\"caret\"></span></a>
								<ul class=\"dropdown-menu dropdown-primary\">";
                            $filename='editar_sucursal.php';
                            $link=permission_usr($id_user, $filename);
                            if ($link!='NOT' || $admin=='1') {
                                echo "<li><a href=\"editar_sucursal.php?id_sucursal=".$row['id_sucursal']."\"><i class=\"fa fa-pencil\"></i> Editar</a></li>";
                            }
                            $filename='borrar_sucursal.php';
                            $link=permission_usr($id_user, $filename);
                            if ($link!='NOT' || $admin=='1') {
                                echo "<li><a data-toggle='modal' href='borrar_sucursal.php?id_sucursal=" .  $row ['id_sucursal']."&process=formDelete"."' data-target='#deleteModal' data-refresh='true'><i class=\"fa fa-eraser\"></i> Eliminar</a></li>";
                            }

                            echo "	</ul>
										</div>
										</td>
										</tr>";
                        }
                    } ?>			
							</tbody>		
						</table>
						 <input type="hidden" name="autosave" id="autosave" value="false-0">	
					</section>   
					<!--Show Modal Popups View & Delete -->
					<div class='modal fade' id='viewModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
						<div class='modal-dialog'>
							<div class='modal-content modal-md'></div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->
					<div class='modal fade' id='deleteModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
						<div class='modal-dialog'>
							<div class='modal-content modal-sm'></div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->	
               	</div><!--div class='ibox-content'-->
       		</div><!--<div class='ibox float-e-margins' -->
		</div> <!--div class='col-lg-12'-->
	</div> <!--div class='row'-->  
</div><!--div class='wrapper wrapper-content  animated fadeInRight'-->			
<?php
    include("footer.php");
        echo '<script src="js/plugins/axios/axios.min.js"></script>';

        $uniqueId=uniqidReal();

        echo "<script type='text/javascript' src='js/funciones/funciones_sucursal.js?v=$uniqueId'></script>";
    } //permiso del script
else {
    echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
}
?>
