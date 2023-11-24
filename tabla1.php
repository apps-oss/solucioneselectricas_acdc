<?php
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: Wed, 1 Jan 2020 00:00:00 GMT");
include_once "_core.php";
include('num2letras.php');


//$id_factura=$_REQUEST["id_factura"];
$title="Venta";

include_once "_headers.php";
$_PAGE ['title'] = $title;
$_PAGE ['links'] .= '<link rel="stylesheet" type="text/css" href="css/util.css">';
$_PAGE ['links'] .= '<link rel="stylesheet" type="text/css" href="css/main.css">';
$_PAGE ['links'] .= '<link rel="stylesheet" type="text/css" href="css/venta_varios.css">';
include_once "header.php";
?>
<style>
.cell-focus {
                background-color: green;
                color: white;
            }
            td, th{
                white-space: nowrap;
            }
</style>
<div class="container">
    <div class="row">
        <div class="col-12">
            <table id="example" class="table table-striped table-sm table-responsive" style="width:100%">
                <thead>
                <tr>
                    <th>N°</th>
                    <th>Desarrollo</th>
                    <th>Enero</th>
                    <th>Febrero</th>
                    <th>Marzo</th>
                    <th>Abril</th>
                    <th>Mayo</th>
                    <th>Junio</th>
                    <th>Julio</th>
                    <th>Agosto</th>
                    <th>Septiembre</th>
                    <th>Octubre</th>
                    <th>Noviembre</th>
                    <th>Diciembre</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td id="start">1</td>
                    <td>Ejemplo de desarrollo 1</td>
                    <td> $46,762.07 </td>
                    <td> $37,697.94 </td>
                    <td> $97,052.56 </td>
                    <td> $58,454.34 </td>
                    <td> $11,393.24 </td>
                    <td> $64,789.92 </td>
                    <td> $36,711.51 </td>
                    <td> $84,038.15 </td>
                    <td> $53,776.74 </td>
                    <td> $99,424.76 </td>
                    <td> $53,932.62 </td>
                    <td> $45,433.82 </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Ejemplo de desarrollo 2</td>
                    <td>  $10,213.46  </td>
                    <td>  $53,932.62  </td>
                    <td>  $45,433.82  </td>
                    <td>  $67,714.60  </td>
                    <td>  $19,085.43  </td>
                    <td>  $59,297.38  </td>
                    <td>  $22,056.71  </td>
                    <td>  $58,732.53  </td>
                    <td>  $56,752.68  </td>
                    <td>  $33,157.53  </td>
                    <td>  $33,857.04  </td>
                    <td>  $38,155.85  </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Ejemplo de desarrollo 3</td>
                    <td>   $52,617.91    </td>
                    <td>   $18,100.89   </td>
                    <td>   $82,234.38   </td>
                    <td>   $61,742.69   </td>
                    <td>   $48,326.86   </td>
                    <td>   $95,219.42   </td>
                    <td>   $67,941.98   </td>
                    <td>   $62,101.14   </td>
                    <td>   $65,173.30   </td>
                    <td>   $80,842.48   </td>
                    <td>   $60,727.48   </td>
                    <td>   $39,458.24   </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
include_once("footer.php");
echo "<script src='js/plugins/sweetalert/sweetalert2.all.min.js'></script>";
echo "<script src='js/plugins/bootstrap-checkbox/bootstrap-checkbox.js'></script>";
echo '<script src="js/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="js/funciones/main.js"></script>';
echo "<script src='js/funciones/util.js'></script>";
 ?>
<script>
let start;
let isEditing = false;
let kc = 0; //Key Code
let tableDT;

$(function() {
    $("body input").prop("autocomplete", false);
    $("table").focus();
    //tableDT = $('#example').DataTable();

    start = $('#start');
    start.addClass('cell-focus');

    document.onkeydown = checkKey;
    document.onclick = deleteInput;

    //Bloque para mover las celdas activas
    function checkKey(e) {
        if (isEditing) return;
        e = e || window.event;
        kc = e.keyCode;
        if (kc === 38) {
            // up arrow
            doTheNeedful($(start.parent().prev().find('td')[start.index()]));
        } else if (kc === 40) {
            // down arrow
            doTheNeedful($(start.parent().next().find('td')[start.index()]));
        } else if (kc === 37) {
            // left arrow
            doTheNeedful(start.prev());
        } else if (kc === 39) {
            // right arrow
            doTheNeedful(start.next());
        }else if (kc === 13) {
            // Enter
            replacedByAnInputText(e);
        }else if (kc === 9) {
            //Tab
            if (e.shiftKey){
                if (start.prev().length === 0){
                    doTheNeedful($(start.parent().prev().children().last()));
                }else{
                    doTheNeedful(start.prev());
                }
            } else{
                if (start.next().length === 0){
                    doTheNeedful($(start.parent().next().children()[0]));
                }else{
                    doTheNeedful(start.next());
                }
            }
            e.stopPropagation();
            e.preventDefault();
        }
    }

    function deleteInput(e) {
        console.log(start)
    }

    $("#example tr").on('dblclick',function(e) {
        replacedByAnInputText(e)
    }).on('click', function(e) {
        if ($(e.target).closest('td')) {
            start.removeClass('cell-focus');
            if (start.find('input').length>0){
                start.html($(start.find('input')[0]).val()).removeClass("p-0");
                isEditing = false;
            }
            start = $(e.target);
            start.addClass('cell-focus');
            e.stopPropagation();
        }
    }).on('keydown', "#editing", function (e) {
        e = e || window.event;
        kc = e.keyCode;
        if (kc === 13 || kc === 27){
            if (start.find('input').length>0){
                start.html($(start.find('input')[0]).val()).removeClass("p-0");
                doTheNeedful(start);
                isEditing = false;
                //tableDT.ajax.reload();
                e.stopPropagation();
                e.preventDefault();
            }
        }else if (kc == 9){
            start.html($(start.find('input')[0]).val()).removeClass("p-0");
            //doTheNeedful(start);
            isEditing = false;
            //tableDT.ajax.reload();
            if (e.shiftKey){
                if (start.prev().length === 0){
                    doTheNeedful($(start.parent().prev().children().last()));
                }else{
                    doTheNeedful(start.prev());
                }
            } else{
                if (start.next().length === 0){
                    doTheNeedful($(start.parent().next().children()[0]));
                }else{
                    doTheNeedful(start.next());
                }
            }
            e.stopPropagation();
            e.preventDefault();
        }
    });
} );

function replacedByAnInputText(e) {
    start.removeClass('cell-focus').addClass("p-0");
    let input = $('<input class="form-control rounded-0 form-control-sm" type="text" id="editing" value="' + start.html() + '">');
    start.html(input);
    $(start.find('input')[0]).select().focus();
    e.preventDefault();
    e.stopPropagation();
    isEditing = true;
}

function doTheNeedful(sibling) {
    if (sibling.length === 1) {
        start.removeClass('cell-focus');
        sibling.addClass('cell-focus');
        start = sibling;
    }
}
</script>
