let urlprocess='pedidos.php'
let modalServ     = "#modalService .modal-body " //para moda de registro de valores
let modalPagoHead   = "#modalPago .modal-header " 
let modalPagoBody   = "#modalPago .modal-body "
let valores={}
let jsonSend={}
let objFactura={}
let agendas={}
let id_cliente=""
let nombreCliente=""
$(document).ready(function() {
    $(" #txtBuscarCte").focus()
})


//autocomplete clientes
const api_url = "autocomp_cliente.php";
new Autocomplete("txtBuscarCte", {
    onSearch: ({ currentValue }) => {
      //const api = `${api_url}?term=${encodeURI(currentValue)}`;
      const api = `${api_url}?term=${encodeURI(currentValue)}`;
      console.log("api:"+api)
      return new Promise((resolve) => {
        fetch(api)
          .then((response) => response.json())
          .then((data) => {
            resolve(data);
          })
          .catch((error) => {
            console.error(error);
          });
      });
    },
    onResults: ({ matches }) =>
      matches.map((el) =>
      `<li>
      ${el.id_cliente} - ${el.nombre}
      </li>`).join(""),
    onSelectedItem: ({ index, element, object }) => {
      console.log("onSelectedItem:", index, element.value, object);
    },
    onSubmit: ({ index, element, object, results }) =>{    
        id_cliente=object.id_cliente
        nombreCliente=object.nombre;
        document.getElementById('id_cliente').value=object.id_cliente;
        document.getElementById('cliente_seleccionado').value=object.nombre;
        let clientSel= document.getElementById('client_select')       
        clientSel.innerHTML= `<h5 class='test-primary tex-center'>Cliente Seleccionado : ${object.nombre}  </h5>`
        $(" #txtBuscarProd").focus()
    },
  });
  //autocomplete rutas
  const api_url2 = "autocomp_prod.php";
  console.log(api_url2)
  new Autocomplete("txtBuscarProd", {
      onSearch: ({ currentValue }) => {
        const api2 = `${api_url2}?term2=${encodeURI(currentValue)}&pedido=1`;
        console.log("api:"+api2)
        return new Promise((resolve) => {
          fetch(api2)
            .then((response) => response.json())
            .then((data) => {
              resolve(data);
            })
            .catch((error) => {
              console.error(error);
            });
        });
      },
      onResults: ({ matches }) =>
        matches.map((el) =>
        `<li>
        ${el.id} - ${el.descripcion}
        </li>`).join(""),
      onSelectedItem: ({ index, element, object }) => {
        console.log("onSelectedItem:", index, element.value, object);
      },
      onSubmit: ({ index, element, object, results }) => {
        console.log("click on item: ", index, element, object, results);
        addProductList(object.id)
      },
    });

    $(document).on("click", "#btnSave", function(e) {
      e.preventDefault();
      e.stopImmediatePropagation();
      senddata();
    });
  
    $(document).on("click", ".servicce", function(e) {
      tr = $(this).closest('tr');
      $("#modalService .modal-body .decimal").numeric({
        negative: false,
        decimalPlaces: 2
      });
      $("#modalService .modal-body .dec2").numeric({
        negative: false,
        decimalPlaces: 4
      });
      let id_agenda    =  $(e.target).data('id_agenda')
      let id_ruta      = $(e.target).data('id_ruta')
      let id_motorista = $(e.target).data('id_motorista')
      let id_cliente   =  $(e.target).data('id_cliente')
      let costogalon   = $(e.target).data('costogal')
      let tot_transp   = $(e.target).data('total_transportado')
      let diesel       = $(e.target).data('diesel')
      let regular      = $(e.target).data('reg')
      let superr       = $(e.target).data('superr')
      let ion          = $(e.target).data('ion') 
      let cliente      = $(e.target).data('cliente')
      let motorista = $(e.target).data('motorista')
      let ruta = $(e.target).data('ruta')
      let id_equipo = $(e.target).data('id_equipo')
      let equipo = $(e.target).data('equipo')

      costogalon       = isNaN( costogalon ) ? 0: costogalon ==""? 0: parseFloat( costogalon );      
      tot_transp        = isNaN( tot_transp ) ? 0: tot_transp ==""? 0: parseFloat( tot_transp );
      let totalfact=  round(costogalon * tot_transp,2)
      $(modalServ+"#id_rutaa").val(id_ruta);
      $(modalServ+"#id_motorr").val(id_motorista);
      $(modalServ+"#id_clientee").val(id_cliente);
      $(modalServ+"#num_agenda").val(""+id_agenda)
      $(modalServ+"#id_equipo").val(""+id_equipo)
      $(modalServ+"#totaltransportado").val(""+tot_transp)
      $(modalServ+"#costogalon").val(costogalon)
      $(modalServ+"#totalfact").val(""+totalfact)

       let client = `<h5 class='test-primary tex-center'> ${cliente} </h5>`
       let nombreClie= document.getElementById('nombreClie')
       nombreClie.innerHTML=client
       let motor = `<h5 class='test-primary tex-center'> ${motorista} </h5>`
       let nombmot= document.getElementById('nombreMot')
       nombmot.innerHTML=motor
       let route = `<h5 class='test-primary tex-center'> ${ruta} </h5>`
       let nombrerut= document.getElementById('nombreRuta')
       nombrerut.innerHTML=route
       
       $(modalServ+"#diesell").val(""+diesel)
       $(modalServ+"#regularr").val(""+regular)
       $(modalServ+"#superr").val(""+superr)
       $(modalServ+"#ionn").val(""+ion)
       $(modalServ+"#total_transpp").val(""+tot_transp)   
    });

    $(document).on("keyup", "#precioporc", function(e) {    
      $("#modalService .modal-body .decimal").numeric({
        negative: false,
        decimalPlaces: 2
      });
      let percent    = $(this).val()
      percent        = isNaN(  percent ) ? 0:  percent ==""? 0: parseFloat(  percent );
      let tot_f_transp =$(modalServ+" #totalfact").val()
      tot_f_transp    = isNaN(  tot_f_transp) ? 0:  tot_f_transp==""? 0: parseFloat( tot_f_transp);
   
      let pagomot = 0.0
      if (percent>0){
        percent = round(percent/100,2)
        pagomot = round(percent *  tot_f_transp,2) 
      }
      $(modalServ+"#totalpagomot").val(""+pagomot)      
    });    
$(document).on("click", "#modalService .modal-body #btnSaveService", function(e) {
  e.preventDefault();
  e.stopImmediatePropagation();
  senddata()
})
function reload1() {
  let duration = 800;
   $({to:0}).animate({to:1}, duration, function() {
    location.href = urlprocess;
   })
}
 //guardar en base
 let senddata=()=> {
  //Obtener los valores a guardar de cada item facturado
  let procces = $("#process").val();
  let i = 0;
  let id = '1';
  let id_empleado = 0;
  let id_cliente = $("#id_cliente").val();
  let items = $("#items").val();
  let msg = "";
  let error = false;
  let array_error = [];
  let nitcli = $('#nitcli').val();
  let numdoc = $('#numdoc').val();
  let total_percepcion = $('#total_percepcion').text();
  let id_factura = $('#id_factura').val();
  let subtotal = $('#total_gravado_iva').text(); /*total gravado mas iva subtotal*/
  let suma_gravada = $('#total_gravado_sin_iva').text(); /*total sumas sin iva*/
  let sumas = $('#total_gravado').text(); /*total sumas sin iva + exentos*/
  let iva = $('#total_iva').text(); /*porcentaje de iva de la factura*/
  let retencion = $('#total_retencion').text(); /*total retencion cuando un cliente retiene 1 o 10 %*/
  let venta_exenta = $('#total_exenta').text(); /*total venta exenta*/
  let total = $('#totalfactura').val();
  let tipo_pago = 0;
  let id_vendedor = $("#vendedor").val();
  let id_apertura = $('#id_apertura').val();
  let turno = $('#turno').val();
  let caja = $('#caja').val();
  let credito = 0;
  let tipo_impresion = 'PED';
  let fecha_movimiento = $("#fecha_movimiento").val();
  let extra_nombre =$("#extra_nombre").val();
  let disponible_fac = $('#disponible_fac').val();
  let totcant = $('#totcant').text();

  if (fecha_movimiento == '' || fecha_movimiento == undefined) {
    let typeinfo = 'Warning';
    msg = 'Seleccione una Fecha!';
    //display_notify(typeinfo, msg);
  }
  let tableData    = storeTblValue();
  if (procces == "insert") {
    let id_cotizacion = "";
  }
  let dataString = 'process=insert'  + '&fecha_movimiento=' + fecha_movimiento;
  dataString += '&id_cliente=' + id_cliente + '&total=' + total;
  dataString += '&id_vendedor=' + id_vendedor // + '&json_arr=' + json_arr;
  dataString += '&retencion=' + retencion;
  dataString += '&total_percepcion=' + total_percepcion;
  dataString += '&numdoc=' + numdoc;
  dataString += '&iva=' + iva;
  dataString += '&items=' + items;
  dataString += '&subtotal=' + subtotal;
  dataString += '&sumas=' + sumas;
  dataString += '&venta_exenta=' + venta_exenta;
  dataString += '&suma_gravada=' + suma_gravada;
  dataString += '&tipo_impresion=' + tipo_impresion;
  dataString += '&id_factura=' + id_factura;
  dataString += '&id_apertura=' + id_apertura;
  dataString += '&turno=' + turno;
  dataString += '&caja=' + caja;
  dataString += '&credito=' + credito;
  dataString += '&nitcli=' + nitcli;
  dataString += '&extra_nombre=' + extra_nombre+'&totcant='+totcant;
  dataString += '&tipo_pago='+tipo_pago
  dataString += tableData;
  if (id_cliente == "") {
    msg = 'No hay un Cliente!';
    error=true;
    array_error.push(msg);
  }
  if (tipo_impresion == "") {
    msg = 'No hay un tipo de impresion seleccionada!';
    error=true;
    array_error.push(msg);
  }
  if (total.length==0 || total==''|| total==0){
    msg = 'Seleccione al menos un producto !';
    error=true;
    array_error.push(msg);
  }
  if (numdoc == "" && tipo_impresion !='TIK') {
    msg = 'Numero de documento a imprimir!';
    error=true;
    array_error.push(msg);
  }
  if(credito==1){
    if (disponible_fac == "" || disponible_fac<=0 || disponible_fac==null || disponible_fac==undefined) {
      msg = 'Revise limte de crédito del cliente!';
      error=true;
      array_error.push(msg);
    }
  }
  if(error==false){
    $.ajax({
      type: 'POST',
      url: 'pedidos.php',
      data: dataString,
      dataType: 'json',
      success: function(datax) {
        if (datax.typeinfo == "Success") {
          $(".usage").attr("disabled", true);
          activa_modalPago(tipo_impresion,datax);
        } else {
          display_notify(datax.typeinfo, datax.msg);
        }
      }
    });
  } else {
    display_notify("Error", "En formulario: "+ array_error.join(",<br>"));
    $("#submit1").removeAttr('disabled');
  }
}
//Funcion para recorrer la tabla completa y pasar los valores de los elementos a un array
let storeTblValue=()=>{
    let i=0;
    let obj ={}
    let array_json=[];
    $("#inventable tr").each(function(index) {
      let id_detalle = $(this).attr("id_detalle");
      if (id_detalle == undefined) {
        id_detalle = "";
      }
      let id = $(this).find("td:eq(0)").text();
      let id_presentacion = $(this).find('.sel').val();
      let precio_venta = $(this).find("#precio_venta").val();
      let cantidad = parseFloat($(this).find("#cant").val());
      if (isNaN(cantidad)) {
        cantidad = 0;
      }
      let bonificacion = 0;
      let unidades = $(this).find("#unidades").val();
      let exento = $(this).find("#exento").val();
      let subtotal = $(this).find("#subtotal_fin").val();

      if (cantidad > 0 && precio_venta) {
          let obj = {
            id_detalle      : id_detalle,
            id              : id,
            precio          : precio_venta,
            cantidad        : cantidad,
            unidades        : unidades,
            subtotal        : subtotal,
            id_presentacion : id_presentacion,
            exento          : exento,
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


//  clienteMetodoPago
let  clienteMetodoPago= async(id_cliente,facturado,id_factura)=>{
  $.ajax({
    url: 'pedidos.php',
    type: 'POST',
    dataType: 'json',
    data: {
      process: "clienteMetodoPago",
      id_cliente: id_cliente,
      facturado:facturado,
      id_factura:id_factura,
    },
    success: function(d) {
      $(modalPagoBody+'.metodos_pago').html(d.datos);
    }
  });
}

$(document).on('change', modalPagoBody+' #metodo_pago', function(event) {
  let tipo_pago = $(modalPagoBody+ '#metodo_pago option:selected').val();
  seleccionarPago(tipo_pago);
});
let seleccionarPago=(tipo_pago)=>{
  if  (tipo_pago =='CON'){
    $(".modal-body #efecttiv").show();
    $(".modal-body #creditt").hide();
    $(".modal-body #tarjet").hide();
    $(".modal-body #valess").hide();
    $(".modal-body #cheques").hide();
    $(".modal-body #transf").hide();
    //$(modalPagoBody+" #efectivo").focus();
  }
  if  (tipo_pago =='CRE'){
    $(".modal-body #creditt").show();
    $(".modal-body #efecttiv").hide();
    $(".modal-body #tarjet").hide();
    $(".modal-body #cheques").hide();
    $(".modal-body #transf").hide();
    $(".modal-body #valess").hide();
  }
  if  (tipo_pago =='TAR'){
    $(".modal-body #efecttiv").hide();
    $(".modal-body #creditt").hide();
    $(".modal-body #valess").hide();
    $(".modal-body #cheques").hide();
      $(".modal-body #transf").hide();
    $(".modal-body #tarjet").show();
  }
  if  (tipo_pago =='VAL'){
    $(".modal-body #creditt").hide();
    $(".modal-body #efecttiv").hide();
    $(".modal-body #tarjet").hide();
    $(".modal-body #cheques").hide();
      $(".modal-body #transf").hide();
    $(".modal-body #valess").show();
    let nitcli = $('#nitcli').val();
    $(modalPagoBody+" #nitt").val(nitcli)
  }
  if  (tipo_pago =='CHE'){
    $(".modal-body #creditt").hide();
    $(".modal-body #efecttiv").hide();
    $(".modal-body #tarjet").hide();
    $(".modal-body #valess").hide();
    $(".modal-body #cheques").show();
    $(".modal-body #transf").hide();
    let nitcli = $('#nitcli').val();
  }
  if  (tipo_pago =='TRA'){
    $(".modal-body #creditt").hide();
    $(".modal-body #efecttiv").hide();
    $(".modal-body #tarjet").hide();
    $(".modal-body #valess").hide();
    $(".modal-body #cheques").hide();
    $(".modal-body #transf").show();
    let nitcli = $('#nitcli').val();
  }
}

//Agregar metodos de pago en modal
$(document).on("click", "#btnAddPayment", function (event) {
    $(modalPagoBody+' .tablaPagos').show()
	agregarPago();
});
$(document).on("click", "#btnDelPay", function(event) {
  $(this).parents("tr").remove();
  actualizaTablaPagos()
});
let agregarPago=()=>{
  let tipo_pago = $('#modalPago .modal-body  #metodo_pago option:selected').val();
  let btnDel  = '<input id="btnDelPay" type="button" '
      btnDel += 'class="btn btn-danger fa"  value="&#xf1f8;">';
  let totall =  $("#modalPago .modal-body #tot_fin").val()
  totall     = isNaN(totall) ? 0: totall ==""? 0: parseFloat(totall);
  let facturado = parseFloat( $("#modalPago .modal-header #facturado").val())
  let metodopago= $('#modalPago .modal-body  #metodo_pago option:selected').text();
  let data_extra= $("#modalPago .modal-body #data_extra").val()
  // $('#btnAddPayment').prop('disabled', true)
  if(totall>=facturado  ){
      $('#btnPrintFact').prop('disabled', false)
      $('#btnEsc').prop('disabled', false)
  }
  let diferencia = 0
  let cambio     = 0
  let sumas      = 0
  txt =""
  tr_add = '';
  //let totall= 0
  if  (tipo_pago =='CON'){
    let valor = parseFloat( $(".modal-body #efectivo").val())
    valor    = isNaN(valor) ? 0:valor ==""? 0: parseFloat(valor);
    let cambio =parseFloat( $(".modal-body #cambio").val())
    let pendiente  = facturado - totall
    //diferencia = facturado - valor
    diferencia = pendiente - valor
    diferencia = roundNumberV1(diferencia, 4)
    if(cambio<0){
      cambio = 0
    }
    if(diferencia<0){
      diferencia = 0
    }
    let datos_extr={
       efectivo : valor,
       cambio :cambio,
    }
    if(valor>facturado){
      valor=facturado
    }
    let datos_extra = JSON.stringify(datos_extr);
    add_tr(valor, diferencia, datos_extra)
    $('#modalPago .modal-body #efectivo').val("");
  }
  if  (tipo_pago =='CRE'){
    let valor = parseFloat( $(".modal-body #valcredit").val())
      valor    = isNaN(valor) ? 0:valor ==""? 0: parseFloat(valor);
    let diascredit = parseFloat( $(".modal-body #diascredit").val())
    diascredit     = isNaN(diascredit) ? 0:diascredit ==""? 0: parseFloat(diascredit);
    diferencia = facturado - valor
    diferencia = roundNumberV1(diferencia, 4)
    if(diferencia<0){
      diferencia = 0
    }
    let datos_extr={
       dias_credito : diascredit,
    }
    let datos_extra = JSON.stringify(datos_extr);
    add_tr(valor, diferencia, datos_extra)
  }
  if  (tipo_pago =='TAR'){
    let valor = parseFloat( $(".modal-body #tarj").val())
      valor    = isNaN(valor) ? 0:valor ==""? 0: parseFloat(valor);
    let transac = $(".modal-body #transac").val()
    diferencia = facturado - valor
    diferencia = roundNumberV1(diferencia, 4)
    if(diferencia<0){
      diferencia = 0
    }
    let datos_extr={
       transaccion : transac,
    }
    let datos_extra = JSON.stringify(datos_extr);
    add_tr(valor, diferencia, datos_extra)

  }
  if  (tipo_pago =='VAL'){
    let del   = $("#modalPago .modal-body #del").val()
    let al    = $("#modalPago .modal-body #al").val()
    let placa = $("#modalPago .modal-body #placa").val()
    let km    = $("#modalPago .modal-body #km").val()
    let nitt  = $("#modalPago .modal-body #nitt").val()
    let observ = $("#modalPago .modal-body #observ").val()
    let valor = parseFloat( $(".modal-body #montovale").val())
      valor    = isNaN(valor) ? 0:valor ==""? 0: parseFloat(valor);
    diferencia = facturado - valor
    diferencia = roundNumberV1(diferencia, 4)
    if(diferencia<0){
      diferencia = 0
    }
    let datos_extr={
       nit : nitt,
       del : del,
       al  : al,
       placa : placa,
       km : km,
       observaciones : observ,
    }
    let datos_extra = JSON.stringify(datos_extr);
    add_tr(valor,diferencia, datos_extra)
  }
  //CHEQUES
  if  (tipo_pago =='CHE'){
    let valor = parseFloat( $(".modal-body #valorcheque").val())
      valor    = isNaN(valor) ? 0:valor ==""? 0: parseFloat(valor);
    let numcheque = $(".modal-body #numcheque").val()
    let banco = $(".modal-body #banco").val()
    diferencia = facturado - valor
    diferencia = roundNumberV1(diferencia, 4)
    if(diferencia<0){
      diferencia = 0
    }
    let datos_extr={
       cheque : numcheque,
       banco  : banco,
    }
    let datos_extra = JSON.stringify(datos_extr);
    add_tr(valor,diferencia, datos_extra)
  }
  //TRANSFERENCIA
  if  (tipo_pago =='TRA'){
    let valor = parseFloat( $(".modal-body #valortransferencia").val())
      valor    = isNaN(valor) ? 0:valor ==""? 0: parseFloat(valor);
    let numtransferencia = $(".modal-body #numtransferencia").val()
    let banco = $(".modal-body #banco").val()
    diferencia = facturado - valor
    diferencia = roundNumberV1(diferencia, 4)
    if(diferencia<0){
      diferencia = 0
    }
    let datos_extr={
       transferencia : numtransferencia,
       banco  : banco,
    }
    let datos_extra = JSON.stringify(datos_extr);
    add_tr(valor,diferencia, datos_extra)
  }
  actualizaTablaPagos()
}
//function to add tr to table tbody
let add_tr=(valor,diferencia, datos_extra)=>{

$('#modalPago .modal-body  .tablaPagos').show()
let tipo_pago = $('#modalPago .modal-body  #metodo_pago option:selected').val();
let btnDel  = '<input id="btnDelPay" type="button" '
    btnDel += 'class="btn btn-danger fa"  value="&#xf1f8;">';
let totall=  $("#modalPago .modal-body #tot_fin").val()
totall     = isNaN(totall) ? 0: totall ==""? 0: parseFloat(totall);
let facturado = parseFloat( $("#modalPago .modal-header #facturado").val())
let metodopago= $('#modalPago .modal-body  #metodo_pago option:selected').text();
let txt =""
const res = JSON.parse(datos_extra);
Object.entries(res).forEach((entry) => {
  const [key, value] = entry;
  txt += `${key}: ${value}<br>`
});

let input_pago = "<input type='hidden' name='alias_pago' id='alias_pago'"
input_pago    += "value='"+tipo_pago+"'>"
let input_extra = "<input type='hidden' name='datos_extra' id='datos_extra'"
input_extra    += "value='"+datos_extra+"'>"
tr_add = "";
tr_add += "<tr  class='pagado'>";
tr_add += "<td class='descripcion col-lg-3'>" + metodopago + "</td>";
tr_add += "<td class='informacion col-lg-5'>" +input_pago+input_extra+ txt+ "</td>";
tr_add += "<td class='valores col-lg-3 text-right'>" +valor+ "</td>";
tr_add += "<td class='DeletePay text-center col-lg-1'>" +btnDel+ "</td>";
tr_add += "</tr>";
$("#modalPago .modal-body #diferencia_").val(diferencia)
  if(totall<facturado && valor>0 ){
    $("#modalPago .modal-body #pagoss #pagos").prepend(tr_add);
    actualizaTablaPagos()
}
if(totall>=facturado  ){
    $('#btnAddPayment').prop('disabled', true)
    $('#btnPrintFact').prop('disabled', false)
    $('#btnEsc').prop('disabled', false)
}
}
//Actualizar total a Pagar
let actualizaTablaPagos=()=>{
let subt = 0
totall   = 0
let facturado = parseFloat( $("#modalPago .modal-header #facturado").val())
$("#modalPago .modal-body #pagos tr").each(function() {
  let subt= $(this).find("td:eq(2)").text();
  totall     += parseFloat(subt);
});
$("#modalPago .modal-body #tot_fin").val(totall)
if(totall>=facturado  ){
    $('#btnAddPayment').prop('disabled', true)
    $('#btnPrintFact').prop('disabled', false)
    $('#btnEsc').prop('disabled', false)
}else{
  $('#btnAddPayment').prop('disabled', false)
  $('#btnPrintFact').prop('disabled', true)
  $('#btnEsc').prop('disabled', true)
}
}
let storeTablePagos=()=>{
  let i=0;
  let obj ={}
  let array_json=[];
  let array_pagos=[];
  let id_factura=$('#modalPago modal-body #id_factt').val();
  let id_fact =$('#id_factura').val();
  let total_facturado=$(".modal-header #facturado").val();
  $("#modalPago .modal-body #pagos tr").each(function() {
    let alias_pago= $(this).find("td:eq(1)").find("#alias_pago").val();
    let datos_extra = $(this).find("td:eq(1)").find("#datos_extra").val();
    let subtotal= $(this).find("td:eq(2)").text();
    let obj = {
      id_factura      : id_fact,
      alias_pago      : alias_pago,
      subtotal        : subtotal,
      total_facturado : total_facturado,
      datos_extra    : datos_extra,
    }
    array_json.push(obj)
    array_pagos.push(alias_pago)
    i = i + 1;
  });
  let valjson = JSON.stringify(array_json);
  let stringDatos="&json_arr="+valjson
      stringDatos+="&tipo_pago="+array_pagos
      stringDatos+="&cuantos="+i
  return stringDatos;
}
$(document).on("keyup",".modal-body input[type='text']",function(e){

  if(e.keyCode !=13)
	{
    alphabetic=containsAnyLetter($(this).val())
    if(alphabetic){
       $(this).val($(this).val().toUpperCase());
    }
	}
});
//validate if only alphabetic characters
function containsAnyLetter(str) {
  return /[a-zA-Z]/.test(str);
}
$(document).on("keyup", "#valcredit, #tarj, #valorcheque, #montovale", function() {
  let valor = $(this).val()
  let totalfinal = parseFloat($(".modal-header #facturado").val());
	let facturado  = totalfinal.toFixed(2);
  if (parseFloat(valor)>totalfinal){
    $(this).val(facturado)
  }
});

$(document).on("keyup",modalPagoBody+" #efectivo",function(e){
	if(e.keyCode !=13 || e.keyCode !=27 )
	{
	  total_efectivo();
	}
  if(e.keyCode ==13 && $(this).val()!="" ){
    $(modalPagoBody+" #btnAddPayment").focus();
    e.stopPropagation();
    e.preventDefault();
  }
    if(e.keyCode ==27)
  {
    $(modalPagoBody+" #btnEsc").click();
    e.stopPropagation();
    e.preventDefault();
  }
});
//cambiar el foco segun metodo de pago en #modalPago
$(document).on('keydown', modalPagoBody+' #metodo_pago', function(e) {
  let id_mp = $(this).val()
  let tipo_pago = $(modalPagoBody+' #metodo_pago option:selected').val();
  if (e.which === 13 || e.keyCode==13) { //a
    $(modalPagoBody+" .montoMetodoPago").focus()
  }
});
$(document).on('keydown', '#modalPago', function(e) {
  if (e.keyCode ==27 || e.which == 27) { //Esc salir
    $(modalPagoBody+' #btnEsc').click()
    e.stopPropagation();
    e.preventDefault();
  }
  if (e.ctrlKey && e.which == 80) { // Ctrl + P Imprimir
    $("#modalPago  #btnPrintFact").click();
      e.preventDefault();
    e.stopPropagation();
  }
  if (e.ctrlKey && e.which == 65) { // Ctrl + A Imprimir
    $("#modalPago  #btnAddPayment").click()
      e.preventDefault();
    e.stopPropagation();
  }
})

//modal de pagos, pago en efectivo
let total_efectivo=()=>{
  let tipo_pago  = $(modalPagoBody+' #metodo_pago').val();
  let totalfinal = parseFloat($(".modal-header #facturado").val());
	let facturado  = totalfinal.toFixed(2);
  let totall     =  $(modalPagoBody+" #tot_fin").val()
  let diferencia = parseFloat($(modalPagoBody+" #diferencia_").val())
  totall         = isNaN(totall) ? 0: totall ==""? 0: parseFloat(totall);
  let efectivo="";
  if  (tipo_pago =='CON'){
    efectivo=parseFloat($(modalPagoBody+' #efectivo').val());
    if (isNaN(parseFloat(efectivo))){
      efectivo=0;
    }
    if (isNaN(parseFloat(totalfinal))){
      totalfinal=0;
    }
    let pendiente  = totalfinal - totall
     pendiente  =roundNumberV1(pendiente, 4)
    //diferencia = pendiente - valor
    if(diferencia>0){
      valor= pendiente
      $(modalPagoBody+'#efectivo').val(valor);
    }

    let cambio=efectivo - pendiente;
    cambio=round(cambio, 2);

    let	cambio_mostrar=cambio.toFixed(2);
    $(modalPagoBody+' #cambio').val(cambio_mostrar);

  }
}
$(document).on("click", modalPagoBody+" #btnPrintFact", function (event) {
  let id_factura=$('#id_factura').val();
  imprimev(id_factura)
});
$(document).on('keydown', modalPagoBody+' #numeroDocImpreso', function(e) {
  let val = $(this).val()
  if (val!="" && (e.keyCode ==13 || e.which == 13) ) { //Enter
    e.preventDefault();
    e.stopPropagation();
    validarNumdoc()
  }
})


$(document).on("click", modalPagoBody+" #btnEsc", function(event) {
  reload_url("pedidos.php")
});

//activvar modal de pago
let activa_modalPago=async(tipo_impresion,datax)=>{
  let numdoc=datax.numdoc
  let totalfinal=parseFloat(datax.total);
  let id_cliente = $("#id_cliente").val();
  let id_factura = datax.id_factura
  $('#id_factura').val(datax.id_factura);
  $('#modalPago').modal({backdrop: 'static',keyboard: false});
  let facturado= totalfinal.toFixed(2);
  let textPrint= `Impresión : ${datax.nombrecaja}  `
  textPrint+=  `<span class="fa-2x  fa-solid fa-gas-pump  text-success"></span>`
  $("#modalPago .modal-header .textmodalPrint").html(textPrint);
  $("#modalPago .modal-header  #facturado").val(facturado);
  clienteMetodoPago(id_cliente,facturado,id_factura);
  $("#modalPago .modal-header #fact_num").html(numdoc);
  $("#modalPago .modal-header #descrip_pago").html(datax.descrip_pago);
  $('#modalPago .modal-body #id_factt').val(datax.id_factura);
  $('#modalPago .modal-body #docImpresion').val(tipo_impresion);
  let nombreCliente=$("#name_client_sel").val();
  $('#modalPago .modal-body #nombreCliente').val(nombreCliente);
  let duration =500;
  $({to:0}).animate({to:1}, duration, function() {
    $("#modalPago .modal-body #metodo_pago").focus()
    $('#modalPago #tableview').enableCellNavigation();
  });
  switch (tipo_impresion) {
    case 'TIK':
      $('#modalPago .modal-body .fac').hide();
      break;
    case 'COF':
      $('#modalPago .modal-body .fac').show();
      break;
    case 'CCF':
      $('#modalPago .modal-body .fac').show();
      break;
  }
}
let	 addProductList=(id_proda, tip='')=>
{
  $(".select2-dropdown").hide();
  $('#inventable').find('tr#filainicial').remove();
  id_proda = $.trim(id_proda);
  id_factura = parseInt($('#id_factura').val());
  if (isNaN(id_factura))
	{
    id_factura = 0;
  }
  urlprocess='venta_directa.php';
  precio_aut = $("#precio_aut").val();
  let dataString = 'process=consultar_stock'+'&id_producto='+id_proda+'&id_factura='+id_factura+'&tipo='+tip+'&precio_aut='+precio_aut ;
  $.ajax({
    type: "POST",
    url: urlprocess,
    data: dataString,
    dataType: 'json',
    success: function(data)
		{
			if(data.typeinfo == "Success")
			{
	      let id_prod = data.id_producto;
	      let precio_venta = data.precio_venta;
	      let unidades = data.unidades;
	      let existencias = data.stock;
	      let perecedero = data.perecedero;
	      let descrip_only = data.descripcion;
	      let fecha_fin_oferta = data.fecha_fin_oferta;
	      let exento = data.exento;
	      let categoria = data.categoria;
	      let select_rank = data.select_rank;
        let decimals=data.decimals;        
	      let preciop_s_iva = parseFloat(data.preciop_s_iva);
	      let tipo_impresion = $('#tipo_impresion').val();
	      let filas = parseInt($("#filas").val());
        filas++;
	      let es_exento = "<input type='hidden' id='exento' name='exento' value='" + exento + "'>";
        let btnView = '<a data-toggle="modal" href="ver_imagen.php?id_producto='+id_prod+'"  data-target="#viewProd" data-refresh="true" class="btn btn-primary btnViw fa"><i class="fa fa-eye"></i></a>';
	      let subtotal = subt(data.preciop, 1);
	      subt_mostrar = subtotal.toFixed(2);
	      let cantidades = "<td class='col-md-1 text-success'><input type='text'  class='form-control decimal2 " + categoria + " cant' id='cant' name='cant' value='' style='width:60px;'></td>";
        let tr_add = '';
	      tr_add += "<tr  class='row100 head' id='" + filas + "'>";
	      tr_add += "<td hidden class=' id_pps'><input type='hidden' id='unidades' name='unidades' value='" + data.unidadp + "'>" + id_prod + "</td>";
	      tr_add += "<td class='col-md-3 text-success'>" + descrip_only + es_exento + '</td>';
	      tr_add += "<td class='col-md-1 text-success' id='cant_stock'>" + existencias + "</td>";
	      tr_add += cantidades;
	      tr_add += "<td class='col-md-1 text-success preccs'>" + data.select + "</td>";
	      //tr_add += "<td hidden class='col-md-1 text-success descp'><input type'hidden' id='dsd' class='form-control' value='" + data.descripcionp + "' class='txt_box' readonly></td>";
	      tr_add += "<td class='col-md-1 text-success rank_s'>" + data.select_rank + "</td>";
	      tr_add += "<td class='col-md-1 text-success'><input type='hidden'  id='precio_venta_inicial' name='precio_venta_inicial' value='" + data.preciop + "'><input type='hidden'  id='precio_sin_iva' name='precio_sin_iva' value='" + preciop_s_iva +
         "'><input type='text'  class='form-control decimal' id='precio_venta' name='precio_venta' value='" + data.preciop + "' readonly></td>";
	      tr_add += "<td class='col-md-1'>" + "<input type='hidden'  id='subtotal_fin' name='subtotal_fin' value='" + "0.00" + "'>" + "<input type='text'  class='decimal txt_box form-control' id='subtotal_mostrar' name='subtotal_mostrar'  value='" + "0.00" + "'readOnly></td>";
        tr_add += "<td hidden class='col-md-1  text-success id_pps'><input type='hidden' id='subt_bonifica' name='subt_bonifica' value='0'></td>";
	      tr_add += '<td class="col-md-1  Delete text-center"><input id="delprod" type="button" class="btn btn-danger fa"  value="&#xf1f8;">&nbsp;'+ btnView+' </td>';
	      tr_add += '</tr>';
	      //numero de filas
	      $("#inventable").prepend(tr_add);
	      $(".decimal2").numeric({
	        negative: false,
	        decimal: false
	      });
	      $(".86").numeric({
	        negative: false,
	        decimalPlaces: 4
	      });
        $(".decimal").numeric({
         negative: false,
         decimalPlaces: 2
       });
	      $('#filas').val(filas);
	      $('#items').val(filas);
	      $(".sel").select2();
	      $(".sel_r").select2();
	      $('#inventable #' +filas).find("#cant").focus();
	      setTotals();
	      scrolltable();
    	}
			else
			{
				display_notify("Error", data.msg);
			}
		}
  });
  setTotals();
}

let setTotals=async()=>{
  //valores base calculos
  let iva = $('#porc_iva').val();
  let cliente_retiene = $("#cliente_retiene").val();
  let porc_percepcion = $("#porc_percepcion").val();
  let porc_reten1 =$("#porc_retencion1").val();
  porc_reten1 =isNaN(porc_reten1) ? 0 : parseFloat(porc_reten1)/100;
  let porc_reten10 =$("#porc_retencion10").val();
  porc_reten10 =isNaN(porc_reten10) ? 0 : parseFloat(porc_reten10)/100;
  let monto_retencion1 = parseFloat($('#monto_retencion1').val());
  monto_retencion1 =isNaN( monto_retencion1) ? 0 : parseFloat( monto_retencion1)/100;
  let monto_retencion10 = parseFloat($('#monto_retencion10').val());
   monto_retencion10 =isNaN( monto_retencion10) ? 0 : parseFloat( monto_retencion10)/100;
  let monto_percepcion = $('#monto_percepcion').val();
  let tipo_impresion = $('#tipo_impresion').val();
  //aplica DIF
  let aplica_dif   =  $("#aplica_dif").val();
  let seldif       =  $("#seldif").val();
  let total_iva = 0
  let total_percepcion  = 0.0
  let total_retencion1  = 0.0
  let total_retencion10 = 0.0
  let total_retencion   = 0.0
  //traer totales de tabla
  let totaless=totalColumns();
  let total_gravado = parseFloat(totaless.total_gravado)
  let totalcantidad = parseFloat(totaless.totalcant)
  let total_exento  = parseFloat(totaless.total_exento)
  let total_ge      = parseFloat(totaless.total) //sumas gravado y exento
  let filas         = parseInt(totaless.filas)

 
  if(cliente_retiene==1){
    if (total_gravado >= monto_retencion1)
      total_retencion1 = total_gravado * porc_reten1;
  }
  if(cliente_retiene==10){
    if (total_gravado >= monto_retencion10)
      total_retencion10 = total_gravado * porc_reten10;
  }
  let total_gravado_iva = total_gravado + total_iva;
  let total_final = total_ge + total_percepcion + total_iva
      total_final += - (total_retencion1 + total_retencion10)
  total_final=round(total_final,4)
  //letiables para mostrar valores y asignacion a elementos html de valores
  let totcant_m = totalcantidad.toFixed(2)
  let total_gravado_m = total_gravado.toFixed(2);
  let total_gravado_iva_m = total_gravado_iva.toFixed(2);
  let total_retencion1_m  = total_retencion1.toFixed(2);
  let total_retencion10_m = total_retencion10.toFixed(2);
  let total_final_m = total_final.toFixed(2);
  $('#items').val(""+filas);
  $('#totcant').val(totcant_m);
  $('#totaldinero').val(total_final_m);
  $('#total_gravado_sin_iva').html(total_gravado_m);
  $('#total_gravado').html(total_gravado_m);
  $('#total_exenta').html(total_exento.toFixed(2));
  $('#total_gravado_iva').html(total_gravado_iva_m); //total gravado con iva
  $('#total_iva').text(total_iva.toFixed(2));
  $('#total_iva2').val(total_iva.toFixed(2));
  $('#total_percepcion').html(total_percepcion.toFixed(2));
  if (parseFloat(total_retencion1) > 0.0)
    $('#total_retencion').html(total_retencion1_m);
  if (parseFloat(total_retencion10) > 0.0)
    $('#total_retencion').html(total_retencion10_m);
  $('#items').val(filas);
  $('#total_gravado2').val(total_gravado_m);
  $('#tot_fdo').val(total_gravado_m);
  $('#totaltexto').load("venta_directa.php", {
    'process': 'total_texto',
    'total': total_final_m
  });
  //total final
  $('#total_final').html(total_final_m);
  $('#total_pedido').html(total_final_m);
  $('#totalfactura').val(total_final_m);
  $('#monto_pago').html(total_final_m);
  $("#total_monto_final").val(total_final_m);
  //si es contado no valido el limite
  let con_pago = $("#con_pago").val();
  let msg_con_pago="";
  let saldo_disponible = $('#saldo_disponible').val();
  if(parseFloat(saldo_disponible)<0){
      saldo_disponible = 0;
  }
  if (isNaN(saldo_disponible) || saldo_disponible == "") {
  saldo_disponible = 0;
  }
  let disponible_fac= round(parseFloat(saldo_disponible)- total_final,2);
  if(disponible_fac<0){
    disponible_fac=0
  }
  $('#disponible_fac').val(disponible_fac.toFixed(2));

}

let totalColumns=()=>{
  let cant          = 0
  let ex            = 0
  let totalcant     = 0
  let total_gravado = 0
  let total_exento  = 0
  let total         = 0
  let subt_gravado  = 0
  let subt_exento   = 0
  let filas         = 0;
  $("#inventable tr").each(function() {
    cant = $(this).find("#cant").val();
    if (isNaN(cant) || cant == "") {
      cant = 0;
    }
    ex = parseInt($(this).find('#exento').val());
    if (ex == 0) {
      subt_gravado = $(this).find("#subtotal_fin").val();
    } else {
      subt_exento = $(this).find("#subtotal_fin").val();
    }
    totalcant     += parseFloat(cant);
    //totalcant = isNaN(totalcant) ? 0 : parseFloat(totalcant)
    total_gravado += parseFloat(subt_gravado);
    total_exento  += parseFloat(subt_exento);
    total         += parseFloat(subt_exento) + parseFloat(subt_gravado);
    filas         += 1;
  });
  totaless ={
      totalcant     : totalcant,
      total_gravado : total_gravado,
      total_exento  : total_exento,
      total         : total,
      filas         : filas,
   }
   return totaless ;
}

$(document).on('keyup', '.cant', function(evt) {
  let tr = $(this).parents("tr");

  if (evt.keyCode == 13) {
    num = parseFloat($(this).val());
    if (isNaN(num)) {
      num = 0;
    }
    if ($(this).val() != "" && num > 0) {
      $(" #txtBuscarProd").focus()
      $(" #txtBuscarProd").val("")
    }
  }

  fila = $(this).closest('tr');
  id_producto = fila.find('.id_pps').text();
  existencia = parseFloat(fila.find('#cant_stock').text());
  existencia = round(existencia, 4);
  a_cant = parseFloat(fila.find('#cant').val());
  unidad = parseInt(fila.find('#unidades').val());
  a_cant = parseFloat(a_cant * unidad);
  a_cant = round(a_cant, 4);
  a_asignar = 0;

  $('#inventable tr').each(function(index) {
    if ($(this).find('.id_pps').text() == id_producto) {
      t_cant = parseFloat($(this).find('#cant').val());
      t_cant = round(t_cant, 4);
      if (isNaN(t_cant)) {
        t_cant = 0;
      }
      t_unidad = parseInt($(this).find('#unidades').val());
      if (isNaN(t_unidad)) {
        t_unidad = 0;
      }
      t_cant = parseFloat((t_cant * t_unidad));
      a_asignar = a_asignar + t_cant;
      a_asignar = round(a_asignar, 4);
    }
  });
  console.log(existencia);
  console.log(a_asignar);
  if (a_asignar > existencia) {
    val = existencia - (a_asignar - a_cant);
    val = val / unidad;
    val = Math.trunc(val);
    val = parseInt(val);
    fila.find('#cant').val(val);
  }
  actualiza_subtotal(tr);
});

function actualiza_subtotal(tr) {
    let iva = parseFloat($('#porc_iva').val());
    let precio_sin_iva = parseFloat(tr.find('#precio_sin_iva').val());
    let existencias = tr.find('#cant_stock').text();
    let tipo_impresion = $('#tipo_impresion').val();
    //bonificacion
    let cantidad=0;
    let cantfinal=0;
    let  bonificacion = 0;   
    cantidad = parseInt(tr.find('#cant').val());
    if (isNaN(cantidad) || cantidad == "") {
      cantidad = 0;
    }
    let precio = tr.find('#precio_venta').val();
    let precio_oculto = tr.find('#precio_venta').val();
    if (isNaN(precio) || precio == "") {
      precio = 0;
    }
    cantfinal=cantidad+bonificacion;
    let subtotal = subt(cantfinal, precio);
    let subt_mostrar = round(subtotal, 2);
    let subt_bonifica = subt(bonificacion, precio);
    subt_bonifica = round(subt_bonifica, 4);
    tr.find("#subtotal_fin").val(subt_mostrar);
    tr.find("#subt_bonifica").val(subt_bonifica);
    tr.find("#subtotal_mostrar").val(subt_mostrar);
    setTotals();
}
$(document).on('keyup', '#precio_venta', function(event) {
  if (event.keyCode==13) {
    if ($('#b').attr('hidden')) {
      $('#codigo').focus();
    } else {
      $('#producto_buscar').focus();
    }
  }
});
$(document).on("click", "#btnCloseView", function(event) {
  $('#viewProd').modal('hide');
});
// Evento que selecciona la fila y la elimina de la tabla
$(document).on("click", "#delprod", function() {
  $(this).parents("tr").remove();
  setTotals();
});

let generaPDF=(id_factura)=>{
  let cadena = "pedido_pdf.php?id_factura="+id_factura;
  window.open(cadena, '', '');
}
//LIMPIAR datos DOM
function clearTable(){
  $("#inventable").html("");
  setTotals();
}

let imprimev=(id_fact=-1)=>{
  let error = false;
  let array_error = [];
  let   msg = "";

  let imprimiendo = parseInt($('#imprimiendo').val());
  let total =   $(".modal-body #facturado").val();
  $('#imprimiendo').val(1);
  let numero_doc = $("#numdoc").val();
  let print = 'imprimir_fact';
  //let tipo_impresion = $("#tipo_impresion").val();
  let tipo_impresion ='PED';

  let fecha_fact = $("#fecha_fact").val();
  let id_factura = $("#id_factura").val();
  let cambio_fin   = 0
  let efectivo_fin = 0
  let tarjeta_fin  = 0
  let diferencia = 0
  let num_fact_cons = '';
  let transaccion =" "
  
  let tipo_pago=$("#con_pago").val();
  if  (tipo_pago =='CON'){
      cambio_fin = $(".modal-body #cambio").val();
      efectivo_fin = $(".modal-body #efectivo").val()
      if(efectivo_fin<0 && efectivo_fin<parseFloat(total)){
        msg = 'Falta dinero para cancelar !';
        error=true;
        array_error.push(msg);
      }
  }
  if  (tipo_pago =='TAR'){
    transaccion = $(".modal-body #efectivo").val()
  }
  if  (tipo_pago =='COM'){
    efectivo_fin = $('#modalPago .modal-body #efectivo_comb').val();
    tarjeta_fin  = $('#modalPago .modal-body #tarjeta_comb').val();
    transaccion  = $('#modalPago .modal-body #transaccion_comb').val();
    diferencia  = $('#modalPago .modal-body #diferencia_comb').val();
     if(transaccion==""){
       msg = 'Digite número de Transaccion!';
       error=true;
       array_error.push(msg);
     }
     if(parseFloat(diferencia)<0 || diferencia=="" ){
       msg = 'Falta dinero para cancelar !';
       error=true;
       array_error.push(msg);
     }
  }
  let dataString  = 'process=' + print
  if ($('#modalPago .modal-body #valess').is(':visible') &&  tipo_pago =='VAL') {
    let del   = $("#modalPago .modal-body #del").val()
    let al    = $("#modalPago .modal-body #al").val()
    let placa = $("#modalPago .modal-body #placa").val()
    let km    = $("#modalPago .modal-body #km").val()
    let nitt  = $("#modalPago .modal-body #nitt").val()
    let observ = $("#modalPago .modal-body #observ").val()
    let vales={
       del : del,
       al  : al,
       placa : placa,
       km : km,
       nit : nitt,
       observaciones : observ,
    }
    let valess = JSON.stringify(vales);
    console.log("vales:"+valess)
    dataString  += '&valess='+valess
  }

  dataString +=  '&numero_doc=0'
  dataString  += '&tipo_impresion='+tipo_impresion+'&num_doc_fact='+id_factura
  dataString += '&numero_factura_consumidor=0'+'&fecha_fact='+fecha_fact;
  dataString += '&cambio='+cambio_fin+'&efectivo='+efectivo_fin
  dataString += '&transaccion='+transaccion
  dataString += '&tarjeta_fin='+tarjeta_fin
  nombreape = $("#nomcli").val();

  let tableData    = storeTablePagos();
  dataString += tableData;
  if ( error == false) {
    $.ajax({
      type: 'POST',
      url: 'pedidos.php',
      data: dataString,
      dataType: 'json',
      success: function(datos) {
          generaPDF(id_factura)
       

      }
    });
    $("#inventable tr").remove();
  }
  else {
   display_notify("Error", "En formulario: "+ array_error.join(",<br>"));
  }
}