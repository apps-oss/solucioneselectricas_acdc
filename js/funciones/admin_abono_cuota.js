let dataTable = "";
let urlprocess='admin_abono_cuota.php'
$(document).ready(function() {
    $(" #txtBuscarCte").focus()
})
/* autocomplete Bs */
const autoCompleteConfig = [{
    name: 'Datos',
    debounceMS: 250,
    minLength: 2,
    maxResults: 10,
    inputSource: document.getElementById('txtBuscarCte'),
    targetID: document.getElementById('id_cliente'),
    fetchURL: 'autocomp_cliente.php?term={term}',
    fetchMap: {id: "id_cliente",
               name: "nombre"}
  }
];
// Initiate Autocomplete to Create Listeners
autocompleteBS(autoCompleteConfig);
function resultHandlerBS(inputName, selectedData) {
  document.getElementById('id_cliente').value=selectedData.id_cliente;
  document.getElementById('cliente_seleccionado').value=selectedData.nombre
  let element = document.querySelector('.client_select');
    let seleccionado = document.getElementById('cliente_seleccionado').value
  console.log("nombre cliente selected:"+ seleccionado);
  let textPrint=  `<label class="text-success">Cliente : ${selectedData.nombre}</label>`
  element.innerHTML = textPrint;
  $("#txtBuscarCte").val("")
  buscarCredito()
}
$(document).on('keydown', '#txtBuscarCte', function(e) {
  let seleccionado = $('#cliente_seleccionado').val()
  let element = document.querySelector('.client_select');
   let textPrint=  `<label class="text-success">Cliente : ${seleccionado}</label>`
   // Set HTML content
   element.innerHTML = textPrint;
  if ( e.keycode === 13 || e.which === 13) {

     $("#txtBuscarCte").val("")

    buscarCredito()
  }
})
let buscarCredito=()=>{
   let id_cliente = document.getElementById('id_cliente').value;
   let dataString = 'process=credito_cliente'
   dataString+='&id_cliente='+id_cliente;
   let duration =1000;
   $.ajax({
     type: 'POST',
     url: urlprocess,
     data: dataString,
     success: function(html) {
         console.log("aca")
           $('#detalle_creditos').html(html);
           let duration = 1000;
           $({to:0}).animate({to:1}, duration, function() {
             if($("#abono_0").length>0)
             {
              $('#detalle_creditos').enableCellNavigation();
              $("#abono_0").focus()
              $(".abonar").numeric({
                negative: false,
                decimalPlaces: 2
              });
            }else {
                $(" #txtBuscarCte").focus()
            }
           });
     }
   });
 }
 $(document).on('keyup', '.abonar', function(event) {
   //verificar que cantidad final no sea menor que inicial
   let tr  =  $(this).parents('tr');
   let abonar =  $(this).val()
   let saldo_act = tr.find("#saldocuota").val();
   abonar        = isNaN(abonar) ? 0: abonar==""? 0: parseFloat(abonar);
   saldo_act     = isNaN(saldo_act) ? 0: saldo_act==""? 0: parseFloat(saldo_act);
   let saldofinal = saldo_act - abonar
   saldofinal    = saldofinal<0 ? 0.0: saldofinal==""? 0.0: parseFloat(saldofinal);
   saldofinal    = round(saldofinal,2)
   if(abonar>saldo_act ){
     $(this).val(""+saldo_act)
   }

   tr.find(".saldo_pend_cuota").text(""+saldofinal.toFixed(2));
     totalColumns();
 });



 $(document).on("click", "#btnSave", function() {
   let abonar= $(".monto_abonar").val()
    abonar        = isNaN(abonar) ? 0: abonar==""? 0: parseFloat(abonar);
   if(abonar>0)
   {
     senddata();
   }
   else {
     display_notify("Error", "Debe agregar abonos ");
     $('#submit1').attr('disabled', false);
   }
 });
//guardar abonos de creditos
 let senddata=()=> {
   totalColumns()
   let i = 0;
   let msg = "";
   let error        = false;
   let array_error  = [];
   let id_cliente   = $("#id_cliente").val();
   let id_apertura  = $("#id_apertura").val();
   let fecha        = $("#fecha").val();
   let total_abonar = $(".monto_abonar").val()
   //let total_saldo  = $("#total_saldo").val()
   let total_saldo  =$('#detalle_creditos tr:last').find(".saldo_ante").text()


   total_abonar       = isNaN(total_abonar) ? 0: total_abonar==""? 0: parseFloat(total_abonar);
   if (fecha == '' || fecha == undefined) {
     let typeinfo = 'Warning';
     msg = 'Seleccione una Fecha!';
     error=true;
     array_error.push(msg);
   }
   let tableData    = storeTblValue();

   let dataString = 'process=insertar'  + '&fecha=' + fecha
   dataString += "&id_cliente=" + id_cliente
   dataString += "&id_apertura=" + id_apertura
   dataString += "&total_abonar=" +total_abonar
   dataString += "&total_saldo=" +total_saldo
   dataString += tableData;
   //alert(dataString)
   Swal.fire({
     title: 'Seguro que desea guardar ?',
     text: "Revise que la información sea correcta!",
     icon: 'warning',
     showCancelButton: true,
     confirmButtonColor: '#3085d6',
     cancelButtonColor: '#d33',
     cancelButtonText: 'Cancelar!',
     confirmButtonText:'<i class="fa fa-save"></i> Guardar!',
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
              $('#modalPrint').modal({
                backdrop: 'static',
                keyboard: false,
              });
              $("#modalPrint .modal-body #id_abono_print").val(datax.id_abono_historial)
             } else {
               display_notify(datax.typeinfo, datax.msg);
             }
             $('#btnSave').attr('disabled', false);
           }
         });
       } else {
         display_notify("Error", "En formulario: "+ array_error.join(",<br>"));
         $("#btnSave").removeAttr('disabled');
       }


       $('#btnSave').attr('disabled', false);
     }else{
       $('#btnSave').attr('disabled', false);
     }
   })
 }
 $(document).on("click", "#btnPrintAbono", function(e) {
  e.preventDefault();
  e.stopImmediatePropagation();
  let id_abono_historial=$("#modalPrint .modal-body #id_abono_print").val()
  printTicket(id_abono_historial)
});
$(document).on("click", "#btnSalir2", function(event) {
  reload1();
});

 let printTicket=(id_abono_historial)=> {
   //para imprimir el ticket de abono
   //let id_abono_historial = datax.id_abono_historial
   let error       = false;
   let array_error = [];
   let   msg       = "";
   let dataString = 'process=print_abono'
      dataString += '&id_abono_historial=' + id_abono_historial
     $.ajax({
       type: 'POST',
       url: urlprocess,
       data: dataString,
       dataType: 'json',
       success: function(datos) {
         let sist_ope = datos.sist_ope;
         let dir_print = datos.dir_print;
         let shared_printer_win = datos.shared_printer_win;
         let shared_printer_pos = datos.shared_printer_pos;
           if (sist_ope == 'win') {
             $.post("http://" + dir_print + "printposwin1.php", {
               shared_printer_pos: shared_printer_pos,
               efectivo: 0,
               cambio: 0,
               totales: datos.totales,
               total_letras: datos.total_letras,
               encabezado: datos.encabezado,
               cuerpo: datos.cuerpo,
               pie: datos.pie,
               img:datos.img,
             })
           } else {
             $.post("http://" + dir_print + "printik_pista.php", {
               efectivo:0,
               cambio: 0,
               totales: datos.totales,
               total_letras: datos.total_letras,
               encabezado: datos.encabezado,
               cuerpo: datos.cuerpo,
               pie: datos.pie,
               img:datos.img,
             });
           }
           /*
         let duration = 1000;
         $({to:0}).animate({to:1}, duration, function() {
            reload1()
         });*/
       }
     });
 }

//////////////////////// fin uilizadas en creditos /////
function reload1() {
  console.log(urlprocess)
  location.href = urlprocess;
}
$(document).on("click", "#btnAddCred", function (event) {
	activaModalCredito()
});

let activaModalCredito=()=>{
  $('#modalCredito').modal({
    backdrop: 'static',
    keyboard: false,
  });
  let duration = 500;
  $({to:0}).animate({to:1}, duration, function() {
      $("#modalCredito .modal-body #txtBuscarCte2").focus()
      auto2();
      $('#addShowClient').enableCellNavigation();
  })
}
let auto2=()=>{

/* autocomplete Bs para cliente de agregar credito */
const autoCompleteConfig2 = [{
    name: 'Datos',
    debounceMS: 250,
    minLength: 2,
    maxResults: 10,
    inputSource: document.getElementById('txtBuscarCte2'),
    targetID: document.getElementById('id_cliente2'),
    fetchURL: 'autocomp_cliente.php?term={term}',
    fetchMap: {id: "id_cliente",
               name: "nombre"}
  }
];
autocompleteBS(autoCompleteConfig2);
// Initiate Autocomplete to Create Listeners

function resultHandlerBS(inputName, selectedData) {
  console.log("nombre Cliente:"+selectedData.nombre);
  document.getElementById('id_cliente2').value=selectedData.id_cliente;
  let elem = document.querySelector('.client_select2');
  let textPrint=  `<label class=" text-success">Cliente : ${selectedData.nombre}</label>`
  // Set HTML content
  elem.innerHTML = textPrint;
  $("#montoCredito").focus()
  $("txtBuscarCte2").val("")
}
$(document).on("click", "#btnSalir", function(event) {
  reload1();
});
}
$(document).on("click", "#btnSaveCred", function(e) {
  e.preventDefault();
  e.stopImmediatePropagation();
  insertCredito();
});
let insertCredito=()=>{

  let id_cliente   = $("#id_cliente2").val();
  let fecha        = $("#fecha_credito").val();
  let montoCredito = $("#montoCredito").val()

  let dataString = 'process=insertCredito'  + '&fecha=' + fecha
  dataString += "&id_cliente=" + id_cliente
  dataString += "&montoCredito=" +montoCredito
  $.ajax({
    type: 'POST',
    url: urlprocess,
    data: dataString,
    dataType: 'json',
    success: function(datax) {
      if (datax.typeinfo == "Success") {
        display_notify(datax.typeinfo, datax.msg);
      } else {
        display_notify(datax.typeinfo, datax.msg);
      }
      let duration = 500;
      $({to:0}).animate({to:1}, duration, function() {
          reload1()
      })
    }
  });
}

$(document).on("click", ".inclu", function(event) {
  totalColumns()
});

let totalColumns=()=>{
  let saldo = "";
  let abonar=0;
  let toto_sin = 0;
  let total_pendiente = 0
  let saldo_pend_cuota  = 0
  $("#detalle_creditos tr").each(function() {
    let tr=$(this)
    let cuota = parseFloat(tr.find(".cuota").text());
    let abonar = parseFloat(tr.find(".abonar").val());
    abonar     = isNaN( abonar) ? 0:  abonar==""? 0: parseFloat(abonar);
    saldo_pend_cuota  = parseFloat(tr.find(".saldo_pend_cuota").text());
  
    saldo_pend_cuota= isNaN( saldo_pend_cuota) ? 0:  saldo_pend_cuota==""? 0: parseFloat(saldo_pend_cuota);
   
    let saldo_act = tr.find("#saldocuota").val();
    saldo_act     = isNaN( saldo_act) ? 0:  saldo_act==""? 0: parseFloat(saldo_act);
    if (abonar>saldo_act){
      abonar=saldo_act
    }
      
    toto_sin +=  abonar;
    total_pendiente += saldo_pend_cuota

  });

  $("#monto").val(toto_sin.toFixed(2));
  $(".monto_abonar").val(toto_sin.toFixed(2));
  $(".total_pend_cuotas").text(total_pendiente.toFixed(2));
}
let storeTblValue=()=>{
  let i=0;
  let obj ={}
  let array_json=[];
  $("#detalle_creditos tr").each(function(index) {
     let tr     = $(this)
     let id_credito     = tr.find("td:eq(0)").text();
     let id_cuota = tr.find("#id_cuota").val();
     let ncuota = tr.find("td:eq(1)").text();
     let abonar = parseFloat(tr.find(".abonar").val());
     let saldocuota = tr.find("#saldocuota").val();
     let abonocuota = tr.find("#abonocuota").val();
     let valorcuota  = parseFloat(tr.find(".cuota").text());
     let saldo_pend_cuota  = parseFloat(tr.find(".saldo_pend_cuota").text());
      if(abonar>0){
      let obj = {
        id_credito : id_credito,
        id_cuota   : id_cuota,
        ncuota     : ncuota ,
        valorcuota : valorcuota,
        abonar     : abonar,
        saldocuota : saldocuota,
        abonocuota : abonocuota,
        saldo_pend_cuota : saldo_pend_cuota,
      }
      array_json.push(obj)
      i = i + 1;
    }
     
  });

 let valjson = JSON.stringify(array_json);
 let stringDatos="&json_arr="+valjson
   stringDatos+="&cuantos="+i
 //alert(stringDatos)
 return stringDatos;
}