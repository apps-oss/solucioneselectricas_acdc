function round(value, decimals)
{
    return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
}
$(document).ready(function() {

$('#formulario').validate({
  rules: {
     	total_efectivo: {
        required: true,
      },
  },
  messages: {
    total_efectivo: "Por favor ingrese el monto en efectivo",
  },
  submitHandler: function (form) {
    corte();
  }
});

$(".datepicker").datepicker({
  format: 'yyyy-mm-dd',
  language:'es',
});
  $(".decimal").numeric();
    mostrarDatos("X");

  $("#tipo_corte").select2()
});


$(function (){
	//binding event click for button in modal form
	$(document).on("click", "#btnDelete", function(event) {
		deleted();
	});
	$(document).on("click", "#btnReimprimir", function(event) {
		reimprimir();
	});
});

$("#tipo_corte").change(function()
{
    let tipo = $(this).val()
  mostrarDatos(tipo)
})

function cambio(tipo)
{
  let  aper_id = $("#aper_id").val();

  $.ajax({
    type:'POST',
    url:"corte_caja_pendiente.php",
    data: "process=cambio&tipo_corte="+tipo+"&aper_id="+aper_id,
    dataType: 'json',
    success: function(datax){

          let total_corte = datax.total_corte;
          ////////////////////////////////////
          let t_tike = datax.t_tike;
          let t_factuta = datax.t_factuta;
          let t_credito = datax.t_credito;
          ////////////////////////////////////
          let total_contado = datax.total_contado;
          let total_transferencia = datax.total_transferencia;
          let total_cheque = datax.total_cheque;
          let total_tarjeta = datax.total_tarjeta;
          let total_vale = datax.total_vale;
          ////////////////////////////////////
          let total_tike = datax.total_tike;
          let total_factura = datax.total_factura;
          let total_credito_fiscal = datax.total_credito_fiscal;
          ////////////////////////////////////
          let tike_max = datax.tike_max;
          let tike_min = datax.tike_min;
          let factura_max = datax.factura_max;
          let factura_min = datax.factura_min;
          let credito_fiscal_max = datax.credito_fiscal_max;
          let credito_fiscal_min = datax.credito_fiscal_min;
          ///////////////////////////////////
          let monto_apertura = datax.monto_apertura;
          let monto_ch = datax.monto_ch;
          let monto_retencion = datax.monto_retencion;
          let total_arqueo = datax.total_arqueo;
          let total_dinero_lectura = datax.total_dinero_lectura;
          let total_galones    = datax.total_galones;
          let tipo_caja        = datax.tipo_caja

          let fila=""
          let fila1=""
          total_corte      = isNaN(total_corte) ? 0: total_corte==""? 0: parseFloat(total_corte);
          let total_corte_fin = 0.00
          let diferencia = round(total_arqueo -total_corte,2)
          let diferencia_v= diferencia.toFixed(2)

          if(parseFloat(total_arqueo)>0 && tipo_caja==2){
              $("#lecturass").show()
          }
          if(tipo == 'Z' || tipo == 'X')
          {

            $("#total_corte").val(total_corte);
            fila = "<tr><td>TIQUETE</td><td>"+tike_min+"</td><td>"+tike_max+"</td><td>"+t_tike+"</td><td>"+total_tike+"</td></tr>";
            fila += "<tr><td>FACTURA</td><td>"+factura_min+"</td><td>"+factura_max+"</td><td>"+t_factuta+"</td><td>"+total_factura+"</td></tr>";
            fila += "<tr><td>CREDITO FISCAL</td><td>"+credito_fiscal_min+"</td><td>"+credito_fiscal_max+"</td><td>"+t_credito+"</td><td>"+total_credito_fiscal+"</td></tr><tr>";
            fila += "<td colspan='4'>MONTO APERTURA</td><td><label id='id_total1'>"+monto_apertura+"</label></td></tr>";
            fila += "<tr><td colspan='4'>TOTAL</td><td><label id='id_total'>"+total_corte+"</label></td></tr>";

            fila1 = "<tr><td><strong><input type='text' id='total_efectivo' name='total_efectivo' value='"+total_arqueo+"'  class='input_clear decimal' readonly></td>";
            fila1 += "<td style='text-align: center'><label id='id_total_general'>"+total_corte+"</label></td>";
            fila1 += "<td style='text-align: center'><label id='id_diferencia'>0.0</label></td></tr>";
          }
          else
          {

            let total_cobro = parseFloat($("#total_cobros").val());
            let devs =  parseFloat($("#id_total_dev").text());
            let salidas =  parseFloat($("#total_salida").val());
            let entrada = parseFloat($("#total_entrada").val());
            console.log(salidas);
            console.log(entrada);
            let total_corte1 = total_corte + entrada - (salidas + devs);
            total_corte_fin = round(total_corte1+total_cobro,2);
            $("#total_corte").val(total_corte1);
            fila = "<tr><td>TIQUETE</td><td>"+tike_min+"</td><td>"+tike_max+"</td><td>"+t_tike+"</td><td>"+total_tike+"</td></tr>";
            fila += "<tr><td>FACTURA</td><td>"+factura_min+"</td><td>"+factura_max+"</td><td>"+t_factuta+"</td><td>"+total_factura+"</td></tr>";
            fila += "<tr><td>CREDITO FISCAL</td><td>"+credito_fiscal_min+"</td><td>"+credito_fiscal_max+"</td><td>"+t_credito+"</td><td>"+total_credito_fiscal+"</td></tr><tr>";
            fila += "<td colspan='4'>MONTO APERTURA</td><td><label id='id_total1'>"+monto_apertura+"</label></td></tr>";
            fila += "<td colspan='4'>MONTO CAJA CHICA</td><td><label id='id_total12'>"+monto_ch+"</label></td></tr>";
            fila += "<td colspan='4'>(-RETENCION)</td><td><label id='id_totalre'>"+monto_retencion+"</label></td></tr>";
            fila += "<tr><td colspan='4'>TOTAL</td><td><label id='id_total'>"+total_corte+"</label></td></tr>";

            fila1 = "<tr><td><input type='text' id='total_efectivo' name='total_efectivo' value=''  class='form-control decimal decimal'></td>";
            fila1 += "<td style='text-align: center'><label id='id_total_general'>"+round(total_corte1+total_cobro,2)+"</label></td>";
            fila1 += "<td style='text-align: center'><label id='id_diferencia'>"+round(total_corte1+total_cobro,2)+"</label></td></tr>";
          }

          $("#tabla_doc").html(fila);
          //$("#table_data").html(fila1);
          $("#total_efectivo").val(total_arqueo);
          $("#id_total_general").text(total_corte);
          $("#id_diferencia").val(diferencia_v);

          ////////////////////////////////////
          $("#t_tike").val(t_tike);
          $("#t_factuta").val(t_factuta);
          $("#t_credito").val(t_credito);
          ////////////////////////////////////
          $("#total_tike").val(total_tike);
          $("#total_factura").val(total_factura);
          $("#total_credito").val(total_credito_fiscal);
          ////////////////////////////////////
          $("#tike_max").val(tike_max);
          $("#tike_min").val(tike_min);
          $("#factura_max").val(factura_max);
          $("#factura_min").val(factura_min);
          $("#credito_fiscal_max").val(credito_fiscal_max);
          $("#credito_fiscal_min").val(credito_fiscal_min);
          $("#total_efectivo").val(total_arqueo);
          $("#total_dinero_lectura").val(total_dinero_lectura);
          $("#total_galones").val(total_galones);
      }
      ,
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        console.log("Status: " + textStatus);
        console.log("Error: " + errorThrown);

      }
  });
}

$(document).on("keyup, focusout, blur","#fecha",function(){
	let fecha=$('#fecha').val();
	dataString='process=total_sistema&fecha='+fecha;
	$.ajax({
				type:'POST',
				url:"corte_caja_pendiente.php",
				data: dataString,
				dataType: 'json',
				success: function(datax){
					let total=datax.total;
					$('#total_sistema').val(total);
					totales();
				}
			});

	totales();
});
//function to round 2 decimal places
function round(value, decimals) {
    return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
    //round "original" to two decimals
	//let result=Math.round(original*100)/100  //returns value like 28.45
}
//Eventos que pueden enviar a calular totales corte de caja
$(document).on("keyup","#efectivo, #tarjeta, #cheque",function(){
  totales();
});
function totales(){
	let total_sistema=parseFloat($('#total_sistema').val());
	let efectivo=parseFloat($('#efectivo').val());
	let tarjeta=parseFloat($('#tarjeta').val());
	let cheque=parseFloat($('#cheque').val());
	let observ="";

	if (isNaN(parseFloat(efectivo))){
		efectivo=0;
	}
	if (isNaN(parseFloat(tarjeta))){
		tarjeta=0;
	}
	if (isNaN(parseFloat(cheque))){
		cheque=0;
	}
	let total_corte=efectivo+tarjeta+cheque;
	let diferencia=total_corte-total_sistema;

	let total_cortado=round(total_corte, 2);
	let	total_corte_mostrar=total_cortado.toFixed(2);

	let dif=round(diferencia, 2);
	let	dif_mostrar=dif.toFixed(2);
	if(diferencia>0){
		observ="Hay una diferencia positiva de "+dif_mostrar +" dolares";
	}
	if(diferencia<0){
		observ="Hay una diferencia negativa de "+dif_mostrar +" dolares";
	}
	$('#total_corte').val(total_corte_mostrar);
	$('#diferencia').val(dif_mostrar);
	$('#observaciones').val(observ);
}

function senddata(){
	let fecha=$('#fecha').val();
	let efectivo=$('#efectivo').val();
	let tarjeta=$('#tarjeta').val();
	let cheque=$('#cheque').val();
	let observaciones=$('#observaciones').val();
	let total_corte=$('#total_corte').val();
	let total_sistema=$('#total_sistema').val();
	let diferencia=$('#diferencia').val();
	let numero_remesa=$('#numero_remesa').val();
    //Get the value from form if edit or insert
	let process=$('#process').val();

	if(process=='insert'){
		let id_caja_chica=0;
		let urlprocess='corte_caja_pendiente.php';
	}

	let dataString='process='+process+'&fecha='+fecha+'&efectivo='+efectivo+'&tarjeta='+tarjeta+'&cheque='+cheque;
	dataString+='&total_corte='+total_corte+'&total_sistema='+total_sistema+'&diferencia='+diferencia+'&numero_remesa='+numero_remesa+'&observaciones='+observaciones;


	$.ajax({
		type:'POST',
		url:urlprocess,
		data: dataString,
		dataType: 'json',
		success: function(datax){
				let id_corte=datax.id_corte;
        		display_notify(datax.typeinfo,datax.msg);
				if(datax.typeinfo == "Success")
				{
					imprimir_corte(id_corte)

					setInterval("reload1();", 1000);
				}

			}
	});
}

function reload1(){
	location.href = 'admin_corte.php';
}
function deleted() {
	let id_producto = $('#id_producto').val();
	let dataString = 'process=deleted' + '&id_producto=' + id_producto;
	$.ajax({
		type : "POST",
		url : "borrar_producto.php",
		data : dataString,
		dataType : 'json',
		success : function(datax) {
			display_notify(datax.typeinfo, datax.msg);
			setInterval("location.reload();", 3000);
			$('#deleteModal').hide();
		}
	});
}

$(document).on("keyup","#total_efectivo", function()
{
	let total_corte = round(parseFloat($("#total_corte").val()),2);
	let total_efectivo = round(parseFloat($(this).val()),2);
	let total_cobros = round(parseFloat($("#total_cobros").val()),2);

		let valor = parseFloat(total_efectivo - (total_corte + total_cobros));
      $("#diferencia").val(round(valor, 2));
  		$("#id_diferencia").text(round(valor, 2));


})


function corte()
{
	  let form = $("#formulario");
    let msg = "";
    let error = false;
    let array_error = [];
    let formdata = false;
    if(window.FormData)
    {
        formdata = new FormData(form[0]);
    }
    let formAction = form.attr('action');
    let total_arqueo = $("#total_efectivo").val();
    total_arqueo     = isNaN(total_arqueo) ? 0: total_arqueo==""? 0: parseFloat(total_arqueo);
    if (total_arqueo == 0) {
      msg = "No ha realizado el arqueo de caja";
      error=true;
      array_error.push(msg);
    }

    let tipo_caja = $("#tipo_caja").val();
    if (tipo_caja == 2) { // caja de pista es valor 2, para validar si hay lectura de bombas
      let total_dinero_lectura = $("#total_dinero_lectura").val();
      total_dinero_lectura     = isNaN(total_dinero_lectura) ? 0: total_dinero_lectura==""? 0: parseFloat(total_dinero_lectura);
      let total_galones = $("#total_galones").val();
       total_galones     = isNaN(total_galones) ? 0: total_galones==""? 0: parseFloat(total_galones);
      //if (total_galones == 0 || total_dinero_lectura  == 0 ) {
      if (total_galones == 0   ) {
        msg = "No ha realizado lectura de bombas";
        error=true;
        array_error.push(msg);
      }
    }
    if(error==false){
      $.ajax({
          type        : 'POST',
          url         : 'corte_caja_pendiente.php',
          cache       : false,
          data        : formdata ? formdata : form.serialize(),
          contentType : false,
          processData : false,
          dataType : 'json',
          success: function(datax)
          {
  		    display_notify(datax.typeinfo, datax.msg)
  		    if(datax.typeinfo == "Success")
  		    {
  	          	let id_corte=datax.id_corte;
                cierre_turno()
                let duration=1000
                $({to:0}).animate({to:1}, duration, function() {
                  imprimir_pdf_consolidado()
                  reload1();
                });
  		    }
  	    }
      });
    }else{
      display_notify("Error", "Revisar: "+ array_error.join(",<br>"));
    }
}

function total()
{
	let tipo_corte = $("#tipo_corte").val();
	let t_t = parseFloat($("#total_tike").val());
	let t_f = parseFloat($("#total_factura").val());
	let t_c = parseFloat($("#total_credito").val());
	let t_e_c = parseFloat($("#total_entrada").val());
	let t_s_c = parseFloat($("#total_salida").val());
	let t_dev = parseFloat($("#total_dev").val());
	let t_nc = parseFloat($("#total_nc").val());
  //let apertura = parseFloat($("#total1").text());
  //apertura   = isNaN(apertura) ? 0: apertura==""? 0: parseFloat(apertura);
	//console.log(t_dev);
	let m_p = parseFloat($("#monto_apertura").val());
	//let d_t = d_g + d_e;
	console.log(t_f);
	let total_all = 0;
	if(tipo_corte == "C")
	{
		let total_c = t_t + t_f + t_c + m_p + t_e_c - t_s_c ;
		total_all = round(total_c, 2);
	}
	else if(tipo_corte == "X")
	{
		//let total_x = t_t + t_f + t_c  + m_p;
    let total_x = t_t + t_f + t_c + m_p + t_e_c - t_s_c ;
		total_all = round(total_x, 2);
	}
	else if(tipo_corte == "Z")
	{
		let total_z = t_t + t_f + t_c  + m_p;
		total_all = round(total_z, 2);
	}
//  total_all   +=  apertura
  total_all   = isNaN(total_all) ? 0: total_all==""? 0: parseFloat(total_all);
  console.log(total_all);
  let signo ='';
  if(total_all<0){
     signo =" - "
  }
  total_ver = total_all.toFixed(2)
	$("#total_corte").val(total_ver);
	$("#id_total_general").text(total_ver);
	$("#id_diferencia").text(signo+total_ver);
	$("#id_total").text(total_ver);
}

function imprimir_corte(id_corte){
	let datoss = "process=imprimir"+"&id_corte="+id_corte;
	$.ajax({
		type : "POST",
		url :"corte_caja_pendiente.php",
		data : datoss,
		dataType : 'json',
		success : function(datos) {
			let sist_ope = datos.sist_ope;
			let dir_print=datos.dir_print;
			let shared_printer_win=datos.shared_printer_win;
			let shared_printer_pos=datos.shared_printer_pos;

				if (sist_ope == 'win') {
					$.post("http://"+dir_print+"printcortewin1.php", {
						datosvale: datos.movimiento,
						shared_printer_win:shared_printer_win,
						shared_printer_pos:shared_printer_pos,
					})
				} else {
					$.post("http://"+dir_print+"printcorte1.php", {
						datosvale: datos.movimiento
					});
				}

		}
	});
}
function reimprimir(){
	let id_corte = $("#id_corte").val();
	imprimir_corte(id_corte);
	$('#viewModal').hide();
	setInterval("location.reload();", 500);
}
//datos del sistema segun tipo corte
let mostrarDatos=(tipo="X")=>{
  if(tipo == "C"){
    $("#table_mov").attr("hidden", false);
    $("#caja_mov").attr("hidden", false);
    $("#caja_cobro").attr("hidden", false);
    cambio(tipo);
    total();
  }
  else if(tipo == "X"){
    $("#table_mov").attr("hidden", false);
    $("#table_dev").attr("hidden", false);
    $("#caja_dev").attr("hidden", false);
    $("#caja_mov").attr("hidden", true);
    $("#table_nc").attr("hidden", false);
    $("#caja_nc").attr("hidden", false);
    $("#caja_cobro").attr("hidden", true);
    $("#tabla_no_pago").attr("hidden", true);
    $("#caja_no_pago").attr("hidden", true);
    cambio(tipo);
    total();
  }
  else if(tipo == "Z")
  {
    $("#table_mov").attr("hidden", true);
    $("#table_dev").attr("hidden", false);
    $("#caja_dev").attr("hidden", false);
    $("#caja_mov").attr("hidden", true);
    $("#table_nc").attr("hidden", false);
    $("#caja_nc").attr("hidden", false);
    $("#caja_cobro").attr("hidden", true);
    $("#tabla_no_pago").attr("hidden", true);
    $("#caja_no_pago").attr("hidden", true);
    cambio(tipo);
    total();
  }
}

//traer info de  apertura para actilet modal arqueo
$(document).on("click", "#btnArqueo", function (event) {
  let id_apertura = $("#id_apertura").val()
  let dataString = "process=datos_apertura"+"&id_apertura="+id_apertura;
  $.ajax({
    type : "POST",
    url :"corte_caja_pendiente.php",
    data : dataString,
    dataType : 'json',
    success : function(datos) {

      actModalArqueo(datos)
    }
  });
})

let actModalArqueo=(datos)=>{
  let id_apertura = $('#id_apertura').val();
  $('#modalArqueo').modal({backdrop: 'static',keyboard: false});
  $('#modalArqueo .modal-body .numeric').numeric(
  { decimalPlaces: 4,
    negative: false
  });
  let title =`<h3>Arqueo de Caja  ${datos.nombre}
    <span class='fa-2x fa-solid  fa-money-bill-1-wave text-success'>
    </span></h3>`
  $("#modalArqLabel").html(title)
  $("#modalArqueo .modal-header  #facturado").val(datos.total_venta_apertura);
  $('#modalArqueo #tableArqueo').enableCellNavigation();

  let duration = 500;
  $({to:0}).animate({to:1}, duration, function() {
  $('#modalArqueo .modal-body #tableArqueo #conceptos #qty1').focus()
  })
}
$(document).on("click", ".modal-body #btnEsc", function (event) {
   $('#modalArqueo').modal('toggle');
});

//totales de arqueo
let totalColumns=()=>{
  let totales        = 0
  let subtotal       = 0
  let cantidad       = 0
  let multiplicador  = 0
  let filas          = 0
  $("#conceptos tr").each(function() {
    multiplicador = $(this).find("td:eq(0)").find(".multiplicador").val();
    cantidad      = $(this).find("td:eq(1)").find(".cant").val();
    multiplicador = isNaN(multiplicador) ? 0: multiplicador==""? 0: parseFloat(multiplicador);
    cantidad      = isNaN(cantidad) ? 0: cantidad==""? 0: parseFloat(cantidad);
    subtotal      = cantidad  * multiplicador
    subtotal      = isNaN(subtotal) ? 0: subtotal==""? 0: parseFloat(subtotal);
    totales      += parseFloat(subtotal);
    filas        += 1;
    $(this).find("td:eq(2)").find(".subtotal").val(subtotal);
  });
  totales =  roundNumberV1(totales, 2)
  totales_ver = totales.toFixed(2)
  $(".modal-body .total_arqueo").val(totales_ver);
  $("#total_efectivo").val(totales_ver);
  let facturado = $("#modalArqueo .modal-header  #facturado").val()
  let monto_apertura = parseFloat($("#monto_apertura").val());
  monto_apertura = isNaN(monto_apertura) ? 0: monto_apertura==""? 0: parseFloat(monto_apertura);
  let diferencia = parseFloat(facturado) + monto_apertura - totales
  diferencia =  roundNumberV1(diferencia, 2)
  diferencia_ver = diferencia.toFixed(2)
  $("#id_diferencia").text(diferencia_ver);
}

$(document).on('change, keyup', '.cant', function(event) {
  //iterar para actualizar todas las filas y realizar calculos
  $('#conceptos tr').each(function(index) {
    let tr = $(this);
    totalColumns();
  });
});

$(document).on("click", ".modal-body #btnSaveArq", function (event) {
  saveArqueo();
});

let saveArqueo=()=>{
  let id_apertura = $('#id_apertura').val();
  let url = 'corte_caja_pendiente.php'
  let stringData=storeTblValue()
  stringData +="&process=arqueo&id_apertura="+id_apertura;
  axios.post(url,stringData)
  .then(function (response) {
    display_notify(response.data.typeinfo, response.data.msg);
    $("#lecturass").show()
  })
  .catch(function (error) {
    console.log(error);
    $("#lecturass").hide()
  });
}
let storeTblValue=()=>{
  let i=1;
  let obj ={}
  let array_json=[];
  $("#conceptos tr").each(function(index) {
    let id    = $(this).find("#id"+i).val();
    let qty    = $(this).find("#qty"+i).val();
    let subt    = $(this).find("#subt"+i).val();
    qty = isNaN(qty) ? 0: qty==""? 0: parseFloat(qty);
    subt = isNaN(subt) ? 0: subt==""? 0: parseFloat(subt);
    if(id){
      let obj = {
        id    : id,
        qty  :qty,
        subt:subt,
      }
      i++;
      array_json.push(obj)
    }
  });
  let total_arqueo = $("#modalArqueo .modal-body #total_arq").val();
  let valjson = JSON.stringify(array_json);
  let stringDatos="&json_arr="+valjson
    stringDatos+="&total_arqueo="+total_arqueo
    stringDatos+="&cuantos="+i
  return stringDatos;
}

let imprimir_pdf_consolidado=()=>{

  let id_apertura = $('#id_apertura').val();
  let tipo_caja = $("#tipo_caja").val();
    let url = 'reporte_corte_tienda_pdf.php'
  if (tipo_caja == 2) { // caja de pista es valor 2, para validar si hay lectura de bombas
     url = 'reporte_corte_consolidado_pdf.php'
  }
	var fini = $("#fini").val();
	var ffin = $("#ffin").val();
	var ticket = $("#ticket").val();
	let cadena = url+"?id_apertura="+id_apertura;
	window.open(cadena, '', '');


}

let cierre_turno=()=>{
  let id_apertura    = $('#id_apertura').val();
  let diferencia     = $('#id_diferencia').val();
  let total_efectivo = $("#total_efectivo").val();
  let fecha_apertura = $("#fecha").val();
  let turno = $('#turno').val();
  let tipo_corte = $('#tipo_corte option:selected').val();

  let dataString = "process=finalizar_turno"+"&id_apertura="+id_apertura;
  dataString += "&turno="+turno+"&tipo_corte="+tipo_corte;
  dataString += "&diferencia="+diferencia+"&total_efectivo="+total_efectivo;
    dataString += "&fecha_apertura="+fecha_apertura;
  $.ajax({
    type : "POST",
    url :"corte_caja_pendiente.php",
    data : dataString,
    dataType : 'json',
    success : function(datos) {
      console.log("realizado!")
    }
  });

}
