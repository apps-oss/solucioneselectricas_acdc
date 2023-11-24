$(function () {
  $('#inventable').enableCellNavigation();
});
//para lecturas diarias
$(document).ready(function() {
  let duration = 1000;
  $({to:0}).animate({to:1}, duration, function() {
    $(".qty").numeric({
      negative: false,
      decimalPlaces: 2
    });
  });

  let fecha = $("#fecha").val();
   //setLecturaFecha(fecha)
  $("#inventable #tr_0").find(".combustt").focus()
});
$(document).on('change', '.existencias', function(event) {
  //verificar que cantidad final no sea menor que inicial

  //iterar para actualizar todas las filas y realizar calculos
  $('#inventable tr').each(function(index) {
    let tr = $(this);
    actualizaFila(tr);
    //totalColumns();
  });
});
//calculuar totales
let totalColumns=()=>{
  let diferencia   = 0
  let total_sist   = 0
  let total_conteo = 0
  let total_dif    = 0
  $("#inventable tr").each(function() {
    let id_producto    = $(this).find("td:eq(1)").text()
    let exist_ante     = $(this).find("td:eq(3)").text();
    let existencias     = $(this).find("td:eq(4)").find(".existencias").val();
    existencias        = isNaN(existencias ) ? 0:  existencias  ==""? 0: parseFloat(existencias );
    diferencia =     parseFloat(exist_ante) - parseFloat(existencias)
    gal_diesel  = $(this).find("td:eq(4)").find("#qty_diesel").val()
    gal_regular = $(this).find("td:eq(4)").find("#qty_regular").val()
    gal_super   = $(this).find("td:eq(4)").find("#qty_super").val()
    dinero_diesel  = $(this).find("td:eq(7)").find(".diesel").val()
    dinero_regular = $(this).find("td:eq(7)").find(".regular").val()
    dinero_super   =  $(this).find("td:eq(7)").find(".super").val()
    gal_diesel     = isNaN(gal_diesel) ? 0: gal_diesel==""? 0: parseFloat(gal_diesel);
    gal_regular    = isNaN(gal_regular) ? 0: gal_regular==""? 0: parseFloat(gal_regular);
    gal_super      = isNaN(gal_super) ? 0: gal_super==""? 0: parseFloat(gal_super);
    dinero_diesel  = isNaN(dinero_diesel) ? 0: dinero_diesel==""? 0: parseFloat(dinero_diesel);
    dinero_regular = isNaN(dinero_regular) ? 0: dinero_regular==""? 0: parseFloat(dinero_regular);
    dinero_super   = isNaN(dinero_super) ? 0: dinero_super==""? 0: parseFloat(dinero_super);
    tot_gdiesel  += parseFloat(gal_diesel);
    tot_gregular += parseFloat(gal_regular);
    tot_gsuper   += parseFloat(gal_super);
    tot_ddiesel  += parseFloat(dinero_diesel);
    tot_dregular += parseFloat(dinero_regular);
    tot_dsuper   += parseFloat(dinero_super);
    totalgalones += parseFloat(gal_diesel+gal_regular+gal_super);
    totalventa   += parseFloat(dinero_diesel+dinero_regular+dinero_super);
    filas        += 1;
  });
  tot_gdiesel   = roundNumberV1(tot_gdiesel, 4)
  tot_ddiesel   = roundNumberV1(tot_ddiesel, 4)
  tot_gregular  = roundNumberV1(tot_gregular, 4)
  tot_dregular  = roundNumberV1(tot_dregular, 4)
  tot_gsuper    = roundNumberV1(tot_gsuper, 4)
  tot_dsuper    = roundNumberV1(tot_dsuper, 4)
  totalgalones  = roundNumberV1(totalgalones, 4)
  totalventa    = roundNumberV1(totalventa, 4)


}


$(document).on("click", "#submit1", function() {
  $('#submit1').attr('disabled', true);
  if($("#inventable tr").length>0)
  {
    senddata();
  }
  else {
    display_notify("Error", "Debe agregar productos a la lista");
    $('#submit1').attr('disabled', false);
  }
});
let senddata=()=> {
  //totalColumns()
  let i = 0;
  let msg = "";
  let error           = false;
  let array_error     = [];
  let fecha           = $("#fecha").val();
  let id_apertura     = $("#id_apertura").val();
  if (fecha == '' || fecha == undefined) {
    let typeinfo = 'Warning';
    msg = 'Seleccione una Fecha!';
    error=true;
    array_error.push(msg);
  }
  let tableData    = storeTblValue();
  let urlprocess = "lectura_lub_dia.php";
  let dataString = 'process=insertar'  + '&fecha=' + fecha
  dataString += "&id_apertura="+id_apertura
  dataString += tableData;
  Swal.fire({
    title: 'Seguro que desea guardar las Lecturas?',
    text: "Revise que la información sea correcta!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    cancelButtonText: 'Cancelar!',
    confirmButtonText:'<i class="fa fa-gas-pump"></i> Guardar!',
  }).then((result) => {
    if (result.isConfirmed) {

      if(error==false){
        $.ajax({
          type: 'POST',
          url: urlprocess,
          data: dataString,
          dataType: 'json',
          success: function(datax) {
            if (datax.typeinfo == "Success") {
              $(".usage").attr("disabled", true);
              display_notify(datax.typeinfo, datax.msg);
              //activa_modalPago(tipo_impresion,datax);
            } else {
              display_notify(datax.typeinfo, datax.msg);
            }
            $('#submit1').attr('disabled', false);
          }
        });
      } else {
        display_notify("Error", "En formulario: "+ array_error.join(",<br>"));
        $("#submit1").removeAttr('disabled');
      }

      Swal.fire(
        'Procesado!',
        'Información guardada con éxito.',
        'success'
      )
      $('#submit1').attr('disabled', false);
    }else{
      $('#submit1').attr('disabled', false);
    }
  })
}
let storeTblValue=()=>{
    let i=0;
    let obj ={}
    let array_json=[];
    $("#inventable tr").each(function(index) {

      let id_producto    = $(this).find("td:eq(1)").text()
      let exist_ante     = $(this).find("td:eq(3)").text();
      let existencias     = $(this).find("td:eq(4)").find(".existencias").val();
      existencias        = isNaN(existencias ) ? 0:  existencias  ==""? 0: parseFloat(existencias );
      if ( existencias>=0) {
          let obj = {
            id_producto : id_producto,
            existencias : existencias ,
            exist_ante  : exist_ante ,
          }
          array_json.push(obj)
          i = i + 1;
        } else {
            error = true
        }
    });
	 let valjson = JSON.stringify(array_json);
   let stringDatos="&json_arr="+valjson
       stringDatos+="&cuantos="+i
   return stringDatos;
}
//revisar que galones sea igual a lect fin -lect ini , e igual con dinero para reporte de corte

let actualizaFila=(tr)=> {
  let diferencia = 0
  let exist_ante  = tr.find("td:eq(3)").text();
  let existencias = tr.find("td:eq(4)").find(".existencias").val();
  existencias     = isNaN(existencias ) ? 0:  existencias  ==""? 0: parseFloat(existencias );
  diferencia      = parseFloat(exist_ante) - parseFloat(existencias)
  tr.find("td:eq(5)").text(""+diferencia);

}
