var dataTable ="";
$(document).ready(function() {
	// Clean the modal form
	$(document).on('hidden.bs.modal', function(e) {
		var target = $(e.target);
		target.removeData('bs.modal').find(".modal-content").html('');
	});
	generar();
});
//esta funcion generar se puede hacer mas generica pasandole parametros y url y otrso datos
function generar(){
	let fechai=$("#fecha_inicio").val();
	let fechaf		=$("#fecha_fin").val();
	const url 			="admin_pedidos_rangos_dt.php?fechai="+fechai+"&fechaf="+fechaf
	const obj_order	=  [[0, 'desc']]
	generateDT('#editable2',url,obj_order )
}
$(function (){
	//binding event click for button in modal form
	$(document).on("click", "#btnDelete", function(event) {
		event.stopPropagation()
		deleted();
		event.preventDefault();
	});
	//Reimprimir factura
	$(document).on("click", "#btnPrint", function(event) {
        let id_factura=$('#id_factura_ver').val();
        generaPDF(id_factura)
	});
	// Clean the modal form
	$(document).on('hidden.bs.modal', function(e) {
		var target = $(e.target);
		target.removeData('bs.modal').find(".modal-content").html('');
	});
	//Recargar facturas
	$(document).on("click", "#btnReload", function(event) {
		reload2();
	});
	//Recargar facturas
	$(document).on("click", "#btnReload3", function(event) {
		reload3();
	});
	//Finalizar factura
	$(document).on("click", "#btnFinFact", function(event) {
		finalizar2();
	})
});
$(document).on("click", "#btnMostrar", function(event) {
	generar();
});

function reload1(){
	location.href = 'admin_factura_rangos.php';
}
function reload2(){
	location.href = 'admin_venta_nofin.php';
}
function reload3(){
	location.href = 'admin_facturas_vendedor.php';
}
function deleted() {
	$('#btnDelete').prop('disabled', true);
	var id_factura = $('#id_factura').val();
	var dataString = 'process=deleted' + '&id_factura=' + id_factura;
	$.ajax({
		type : "POST",
		url : "anular_pedidos.php",
		data : dataString,
		dataType : 'json',
		success : function(datax) {
			display_notify(datax.typeinfo, datax.msg);
			$('#btnDelete').prop('disabled', false);
			setInterval("location.reload();", 3000);
			$('#deleteModal').hide();
		}
	});
}
function print1() {
	var id_factura = $('#id_factura').val();
	var dataString = 'process=imprimir_fact' + '&id_factura=' + id_factura;
	$.ajax({
		type : "POST",
		url : "reimprimir_factura.php",
		data : dataString,
		dataType : 'json',
		success : function(datos) {
			let sist_ope = datos.sist_ope;
			let dir_print=datos.dir_print;
			let tipo_impresion= datos.tipo_impresion;
			let shared_printer_win=datos.shared_printer_win;
			let shared_printer_pos=datos.shared_printer_pos;
			let headers="";
			let footers="";
			let	efectivo_fin=0;
			let	 cambio_fin=0;
			if (tipo_impresion == 'COF') {
				if (sist_ope == 'win') {
					$.post("http://"+dir_print+"printfactwin1.php", {
						datosventa: datos.facturar,
						efectivo: efectivo_fin,
						cambio: cambio_fin,
						shared_printer_win:shared_printer_win
					});
				} else {
					$.post("http://"+dir_print+"printfact1.php", {
						datosventa: datos.facturar,
						efectivo: efectivo_fin,
						cambio: cambio_fin
					}, function(data, status) {
						if (status != 'success') {
							console.log("No Se envio la impresión ");
						}
					});
				}
			}
			if (tipo_impresion == 'TIK') {
				if (sist_ope == 'win') {
					$.post("http://"+dir_print+"printposwin1.php", {
						datosventa: datos.facturar,
						efectivo: efectivo_fin,
						cambio: cambio_fin,
						shared_printer_pos:shared_printer_pos,
						headers:headers,
						footers:footers,
					})
				} else {
					$.post("http://" + dir_print + "printik_pista.php", {
						efectivo: 0,
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
			if (tipo_impresion == 'DEV') {
				if (sist_ope == 'win') {
					$.post("http://"+dir_print+"printncrwin1.php", {
						datosventa: datos.facturar,
						efectivo: efectivo_fin,
						cambio: cambio_fin,
						shared_printer_win:shared_printer_win
					})
				} else {
					$.post("http://"+dir_print+"printncr1.php", {
						datosventa: datos.facturar,
						efectivo: efectivo_fin,
						cambio: cambio_fin
					});
				}
			}
			if (tipo_impresion == 'CCF') {
				if (sist_ope == 'win') {
					$.post("http://"+dir_print+"printcfwin1.php", {
						datosventa: datos.facturar,
						efectivo: efectivo_fin,
						cambio: cambio_fin,
						shared_printer_win:shared_printer_win
					})
				} else {
					$.post("http://"+dir_print+"printcf1.php", {
						datosventa: datos.facturar,
						efectivo: efectivo_fin,
						cambio: cambio_fin
					}, function(data, status) {
						if (status != 'success') {
							console.log("No Se envio la impresión");
						}
					});
				}
			}
			if (tipo_impresion == 'ENV') {
				if (sist_ope == 'win') {
					$.post("http://"+dir_print+"printenvwin1.php", {
						datosventa: datos.facturar,
						efectivo: efectivo_fin,
						cambio: cambio_fin,
						shared_printer_win:shared_printer_win
					})
				} else {
					$.post("http://"+dir_print+"printenv1.php", {
						datosventa: datos.facturar,
						efectivo: efectivo_fin,
						cambio: cambio_fin
					});
				}
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log("Status: " + textStatus);
			console.log("Error: " + errorThrown);
		}
	});
}
$(document).on("keyup","#efectivo",function(){
  total_efectivo();
});
function total_efectivo(){
	var efectivo=parseFloat($('#efectivo').val());
	var totalfinal=parseFloat($('#facturado').text());
	var facturado= totalfinal.toFixed(2);
	if (isNaN(parseFloat(efectivo))){
		efectivo=0;
	}
	if (isNaN(parseFloat(totalfinal))){
		totalfinal=0;
	}
	var cambio=efectivo-totalfinal;
	var cambio=round(cambio, 2);
	var	cambio_mostrar=cambio.toFixed(2);
	if($('#efectivo').val()!='' && efectivo>=totalfinal)
		$('#cambio').html("<h5 class='text-success'>"+cambio_mostrar+"</h5>");
	else
		$('#cambio').text('');
	if(efectivo<totalfinal){
		$('#cambio').html("<h5 class='text-danger'>"+"Falta dinero !!!"+"</h5>");
	}
}
//function to round 2 decimal places
function round(value, decimals) {
    return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
}
function finalizar2() {
	var id_factura = $('#id_factura').val();
	var dataString = 'process=finalizar_fact' + '&id_factura=' + id_factura;
	$.ajax({
		type : "POST",
		url : "finalizar_factura.php",
		data : dataString,
		dataType : 'json',
		success: function(datax){
			process=datax.process;
			factura=datax.factura;
			display_notify(datax.typeinfo,datax.msg);
			setInterval("reload2();", 3000);
		}
	});
}
let generaPDF=(id_factura)=>{
    let cadena = "pedido_pdf.php?id_factura="+id_factura;    
    window.open(cadena, '', '');
}