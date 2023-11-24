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
  fact_apertura();
});
$(document).on('change', '.qty, .efect', function(event) {
  //verificar que cantidad final no sea menor que inicial
  let uni_tr =  $(this).parents('tr');
  let lect_init        = uni_tr.find("td:eq(2)").find(".qty").val();
  let lect_fini        = uni_tr.find("td:eq(3)").find(".qty").val();
  let efect_init   = uni_tr.find("td:eq(5)").find(".qty").val();
  let efect_fini   = uni_tr.find("td:eq(6)").find(".qty").val();
  //let venta       = uni_tr.find("td:eq(7)").find(".qty").val();
  lect_init            = isNaN(lect_init) ? 0: lect_init==""? 0: parseFloat(lect_init);
  lect_fini            = isNaN(lect_fini) ? 0: lect_init==""? 0: parseFloat(lect_fini);

  efect_init            = isNaN(efect_init) ? 0: efect_init==""? 0: parseFloat(efect_init);
  efect_fini            = isNaN(efect_fini) ? 0: efect_fini==""? 0: parseFloat(efect_fini);
  if(lect_fini<lect_init ){
    uni_tr.find("td:eq(3)").find(".qty").val(lect_init)
    uni_tr.find("td:eq(4)").find(".qty").val(0)
  }
  if(efect_fini<efect_init ){
    uni_tr.find("td:eq(6)").find(".qty").val(efect_init)
    uni_tr.find("td:eq(7)").find(".qty").val(0)
  }
  //iterar para actualizar todas las filas y realizar calculos
  $('#inventable tr').each(function(index) {
    let tr = $(this);
    actualizaFila(tr);
    //totalColumns();
  });
});
//calculuar totales
let totalColumns=()=>{
  let galones        = 0
  let gal_diesel     = 0
  let tot_gdiesel    = 0
  let gal_regular    = 0
  let tot_gregular   = 0
  let gal_super      = 0
  let tot_gsuper     = 0
  let venta          = 0
  let dinero_diesel  = 0
  let tot_ddiesel    = 0
  let dinero_regular = 0
  let tot_dregular   = 0
  let dinero_super   = 0
  let tot_dsuper     = 0
  let totalgalones   = 0
  let totalventa     = 0
  let filas          = 0
  $("#inventable tr").each(function() {

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
  //traer los impuestos a los combustibles
    getImpGas(totalgalones,totalventa);
    $("#gal_diesel").val(tot_gdiesel);
    $("#gal_regular").val(tot_gregular);
    $("#gal_super").val(tot_gsuper);
    $("#dinero_diesel").val(tot_ddiesel);
    $("#dinero_regular").val(tot_dregular);
    $("#dinero_super").val(tot_dsuper);
    $("#total_galon").val(totalgalones);
    $("#total_efectivo").val(totalventa);

}
async function getImpGas(totalgalones,totalventa){
    let 	ur='venta_pista.php?';
    let params ='process=getImpGas'
    let url=`${ur}${params}`

    function getvals(){
    return fetch(url,
    {
      method: "POST",
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
    })
    .then((response) => {
     return response.json();
    })
    .then((responseJSON) => {
      return responseJSON;
    })
    .catch(error => console.warn(error));
  }
 let valor=await getvals().then((response) => response.detalle);
  calcImpGas(valor,totalgalones,totalventa);
}
function calcImpGas(valor,totalgalones,totalventa){
    let n=0
    let total_impuestos_comb =0
    let total_impuesto = 0
    let total_dinero_final = 0
    totalgalones = parseFloat(totalgalones)
    $.each(valor, function(i, item) {
      let totalcantidad  = 0
      let subt_impuesto  = 0
      total_impuesto = 0
      let id_imp =   $("#tabla_impuestos").find("#"+n).find("#id_imp").val()

      if ( item.activo==1 && id_imp == item.id ){
          subt_impuesto= totalgalones * item.valor
          subt_impuesto =  isNaN(subt_impuesto) ?  0:round(subt_impuesto,4);
          total_impuesto += parseFloat(subt_impuesto);
          total_impuesto =  isNaN(total_impuesto) ?  0:round(total_impuesto,4);
            //console.log('total_impuestos de '+item.nombre+':'+total_impuesto);
          $("#tabla_impuestos").find("#"+n).find("#total_impgas").text(total_impuesto);
          $("#tabla_impuestos").find("#"+n).find("#val_imp_gas").val(total_impuesto);
      }
      total_impuestos_comb += total_impuesto;
      n++
      //console.log('total_impuestos_comb:'+total_impuestos_comb);
     $("#tot_imp_gass").text(total_impuestos_comb);
     $("#tot_imp_combust").val(total_impuestos_comb);
     //setTotals()
  });
  total_impuestos_comb=roundNumberV1(total_impuestos_comb, 4)

  total_dinero_final =   parseFloat(totalventa);
  isNaN(total_dinero_final) ?  0:round(total_dinero_final,4);
    console.log('total_dinero_final: '+total_dinero_final);
    $("#total_dinero_final").val(total_dinero_final);
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
  totalColumns()
  let i = 0;
  let msg = "";
  let error           = false;
  let array_error     = [];
  let fecha           = $("#fecha").val();
  let gal_diesel      = $("#gal_diesel").val();
  let gal_regular     = $("#gal_regular").val();
  let gal_super       = $("#gal_super").val();
  let dinero_diesel   = $("#dinero_diesel").val();
  let dinero_regular  = $("#dinero_regular").val();
  let dinero_super    = $("#dinero_super").val();
  let total_galon     = $("#total_galon").val();
  let total_venta     = $("#total_efectivo").val();
  let id_apertura     = $("#id_apertura").val();
  let total_impuestos = $("#tot_imp_combust").val()
  if (fecha == '' || fecha == undefined) {
    let typeinfo = 'Warning';
    msg = 'Seleccione una Fecha!';
    error=true;
    array_error.push(msg);
  }
  let tableData    = storeTblValue();
  let urlprocess = "lectura_dia.php";
  let dataString = 'process=insertar'  + '&fecha=' + fecha
  dataString += "&gal_diesel=" + gal_diesel + "&gal_regular=" + gal_regular
  dataString += "&gal_super="+ gal_super + "&dinero_diesel="+ dinero_diesel
  dataString += "&dinero_regular="+ dinero_regular + "&dinero_super="+ dinero_super
  dataString += "&total_galon="+total_galon+"&total_venta="+total_venta
  dataString += "&id_apertura="+id_apertura+"&total_impuestos="+total_impuestos
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
              fact_apertura();
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
      let id_bomba    = $(this).find("#id_bomba").val();
      let id_manguera = $(this).find("#id_manguera").val();
      let id_comb     = $(this).find("#id_comb").val();
      let lect_ini    = $(this).find("td:eq(2)").find(".qty").val();
      let lect_fin    = $(this).find("td:eq(3)").find(".qty").val();
      let galones     = $(this).find("td:eq(4)").find(".qty").val();
      let efect_ini   = $(this).find("td:eq(5)").find(".qty").val();
      let efect_fin   = $(this).find("td:eq(6)").find(".qty").val();
      let venta       = $(this).find("td:eq(7)").find(".qty").val();
      let devol = 0
      let efect_devol = 0
      let combustible = $(this).find(".combustible").text();
      if ( lect_fin>0) {
          let obj = {
            id_bomba    : id_bomba,
            id_comb     : id_comb,
            id_manguera : id_manguera,
            lect_ini    : lect_ini,
            lect_fin    : lect_fin,
            devol       : devol,
            galones     : galones,
            efect_ini   : efect_ini,
            efect_fin   : efect_fin,
            efect_devol : efect_devol,
            venta       : venta,
            combustible : combustible,
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
  let monto_efectivo = 0
  let precio         = 0
  let efect_ini      = 0;
  let efect_fin      = 0;
  let dif_efect      = 0
  let lect_ini        = tr.find("td:eq(2)").find(".qty").val();
  let lect_fin        = tr.find("td:eq(3)").find(".qty").val();
  let galones   = parseFloat(lect_fin - lect_ini  )
  console.log("galones:"+galones)
  galones             = isNaN(galones) ? 0: galones==""? 0: parseFloat(galones);

  if (galones<0){
    galones = 0 ;
  }
  tr.find("td:eq(4)").find(".qty").val(galones);

  efect_ini =  tr.find("td:eq(5)").find(".qty").val();
  efect_ini     = isNaN(efect_ini ) ? 0: efect_ini ==""? 0: parseFloat(efect_ini );
  efect_ini = roundNumberV1(efect_ini, 4)
  efect_fin =  tr.find("td:eq(6)").find(".qty").val();
  efect_fin = roundNumberV1(efect_fin, 4)
  monto_efectivo =efect_fin - efect_ini
  monto_efectivo    = isNaN(monto_efectivo) ? 0: monto_efectivo==""? 0: parseFloat(monto_efectivo);
  tr.find("td:eq(7)").find(".qty").val(monto_efectivo );
  totalColumns()
}

let fact_apertura=()=>{
    let id_apertura     = $("#id_apertura").val();
    let urlprocess = "lectura_dia.php";
    let dataString = 'process=fact_apertura'
    dataString += "&id_apertura="+id_apertura
    $.ajax({
      type: 'POST',
      url: urlprocess,
      data: dataString,
      //dataType: 'json',
      success: function(html) {
      
          console.log("aca")
            $('#combustibles').html(html);

          //display_notify(datax.typeinfo, datax.msg);


      }
    });
}
