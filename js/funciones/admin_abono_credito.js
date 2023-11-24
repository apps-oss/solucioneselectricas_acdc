let dataTable = "";
let urlprocess='admin_abono_credito.php'
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
  let seleccionado = document.getElementById('cliente_seleccionado').value
  console.log("nombre cliente selected:"+ seleccionado);
  let element = document.querySelector('.client_select');
  let textPrint=  `<label class="text-success">Cliente : ${seleccionado}</label>`
  // Set HTML content
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
 $(document).on('change', '.abonar', function(event) {
   //verificar que cantidad final no sea menor que inicial
   let tr  =  $(this).parents('tr');
   let abonar =  $(this).val()
   let saldo_act = tr.find("td:eq(5)").text();
   //let abono     = tr.find("td:eq(5)").find(".qty").val();
   abonar        = isNaN(abonar) ? 0: abonar==""? 0: parseFloat(abonar);
   saldo_act     = isNaN(saldo_act) ? 0: saldo_act==""? 0: parseFloat(saldo_act);
   if(abonar>saldo_act ){
     $(this).val(""+saldo_act)
   }
   let duration = 1000;
     totalColumns();
 });


 let totalColumns=()=>{
   console.log("detalle_creditos")
   let total        = 0
   let filas        = 0
   let total_abonar = 0.0
   let       abonar = 0
   let            n = 0
   let total_saldo  = 0
   let       saldo = 0
   // fin impuestos al combustible
   $("#detalle_creditos tr").each(function() {
     abonar = parseFloat($(this).find(".abonar").val());
     abonar = isNaN(abonar) ? 0: abonar==""? 0: parseFloat(abonar);
     saldo  = $(this).find("td:eq(5)").text()
     saldo  = isNaN(saldo) ? 0: saldo==""? 0: parseFloat(saldo);
     total_abonar +=abonar
     total_saldo  +=saldo
     n++
   });
   total_abonar=round(total_abonar,2)
   $("#total_abonar").val(""+total_abonar)
   $("#total_saldo").val(""+total_saldo)
   let tt=` $ ${total_abonar.toFixed(2)}`
   $(".monto_abonar").val(tt)
   $("#monto").val(tt)
 }
 $(document).on("click", "#btnSave", function(e) {
  e.preventDefault();
  e.stopImmediatePropagation();
   let abonar= $("#total_abonar").val()
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
   let id_abono_historial = $("#id_abono_historial").val();
   let id_cliente   = $("#id_cliente").val();
   let id_apertura  = $("#id_apertura").val();
   let fecha        = $("#fecha").val();
   let total_abonar = $("#total_abonar").val()
   let total_saldo  =$('#detalle_creditos tr:last').find("td:eq(5)").text()
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
 let storeTblValue=()=>{
     let i=0;
     let obj ={}
     let array_json=[];
     $("#detalle_creditos tr").each(function(index) {
       let id     = $(this).find("td:eq(0)").text();
       let abonar = $(this).find("td:eq(6)").find(".abonar").val();
       abonar        = isNaN(abonar) ? 0: abonar==""? 0: parseFloat(abonar);
       total_abonar +=abonar
       if ( abonar>0) {
           let obj = {
             id_credito    : id,
             abonar     :abonar,
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
}
$(document).on("click", "#btnSalir", function(event) {
  reload1();
});

$(document).on("click", "#btnSaveCred", function(event) {
  let id_cliente   = $("#id_cliente2").val();
  let fecha        = $("#fecha_credito").val();
  let montoCredito = $("#montoCredito").val()
  if(id_cliente!="" && fecha!="" && montoCredito!=""){
      insertCredito();
  }else {
    display_notify("Warning","Falta información de cŕedito!");
      $("#modalCredito .modal-body #txtBuscarCte2").focus()
  }
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


$(document).on("click", "#btnHistoCred", function(event) {
  let id_cliente   = $("#id_cliente").val();
  if(id_cliente!=""){
    $('#modalHistorial').modal({backdrop: 'static',keyboard: false});
    let duration = 100;
    $({to:0}).animate({to:1}, duration, function() {
      let seleccionado = document.getElementById('cliente_seleccionado').value
      $('#modalHistorial .modal-header .clienteHistorial').html("<h5 class='text-center text-info'>Cliente: "+seleccionado+"</h5>");
      getHistoCred();
    })
  }else {
    display_notify("Warning","Falta seleccionar cliente!");
      $("  #txtBuscarCte").focus()
  }
});




let getHistoCred=()=>{
  let id_cliente   = $("#id_cliente").val();
let dataString ='process=getHistoCred&id_cliente='+id_cliente
  axios.post(urlprocess,dataString)
    .then(function (response) {
    $('#history').html(response.data.detalle);
    })
    .catch(function (error) {
      console.log(error);
    });


}
