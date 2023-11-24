$(document).ready(function()
{
	$( ".datepick" ).datepicker();
	$("#submit1").click(function()
	{
			cargar();
	})
	$("#xls").click(function()
	{
			cargar_excel();
	})
});

$(document).on("ifChecked", "#tiket", function()
{
	$("#ticket").val("1");
	console.log("1");
});
$(document).on("ifUnchecked", "#tiket", function()
{
	$("#ticket").val("0");
	console.log("0");
});

function cargar()
{
	let fini = $("#fini").val();
	let ffin = $("#ffin").val();
  let id_apertura = $('#id_apertura').val();
	let cadena = "reporte_dia_acelub_pdf.php?fini="+fini+'&id_apertura=' + id_apertura;;
	window.open(cadena, '', '');
}
function cargar_excel()
{
	let fini = $("#fini").val();
	let ffin = $("#ffin").val();

	let cadena = "libro_ventas_consumidores_excel.php?fini="+fini+"&ffin="+ffin;
	window.open(cadena, '', '');
}
