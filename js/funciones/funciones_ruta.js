$(document).ready(function() {


let url = 'autocomplete_cliente.php'
$("#scrollable-dropdown-menu #cliente").typeahead({
	highlight: true,
}, {
	limit: 100,
	name: 'productos',
	display: 'producto',
	source: function show(q, cb, cba) {
		console.log(q);
			type: 'GET',
		//let url = 'autocomplete_cliente.php' + "?query=" + q;
		$.ajax({
				url: url+ "?query=" + q ,
			})
			.done(function(res) {
				cba(JSON.parse(res));
			})
			.fail(function(err) {
				alert(err);
			});
	}
}).on('typeahead:selected', onAutocompleted);

function onAutocompleted($e, data0) {

	var id = data0.split("|");
	var nombre = id[1];
	id = parseInt(id[0]);
	$.ajax(
		{
			url: 'agregar_ruta.php',
			type: 'POST',
			data: 'process=traer_cliente&id_cliente=' + id ,
			dataType: 'JSON',
			async: true,
			success: function(datax)
			{
				if (datax.typeinfo=="Success") {

				var id_cliente =datax.id_cliente
				var id_mun =datax.id_municipio
				var nom_mun =datax.municipio
				var id_dep =datax.id_departamento
				var nom_dep =datax.departamento
				var cliente =datax.cliente
				agregar_lista(id_cliente,id_mun,nom_mun,id_dep,nom_dep,cliente)
			}else {
				display_notify("Warning",datax.msg)
			}
				//$("#cliente_table").prepend(datax.tr);


			}
		});
}
  $('.selectt').select2();
		// Clean the modal form
		$(document).on('hidden.bs.modal', function(e) {
			var target = $(e.target);
			target.removeData('bs.modal').find(".modal-content").html('');

		});
		$('#viewModal').on('hidden.bs.modal', function () {
				$(this).removeData('bs.modal');
	});

generar();
$(".vineta").numeric({
	negative: false,
	decimal: false
});
}); //end $(document).ready(function() {
	$(function (){
		//binding event click for button in modal form
		$(document).on("click", "#btnDelete", function(event) {
			deleted();
		});
		// Clean the modal form
		$(document).on('hidden.bs.modal', function(e) {
			var target = $(e.target);
			target.removeData('bs.modal').find(".modal-content").html('');
		});

	});
	$(document).on("click", "#btn_ruta", function() {
		senddata();
	});
	$(document).on('click', '.elim', function() {
	  var tr = $(this).parents("tr");
	  tr.remove();

	});
//Departamento del cliente
$("#departamento").change(function() {

	var ajaxdata2 = {
		"process": "obtener_clientes",
		"id_departamento": $("#departamento").val()
	};
	$.ajax({
		url: "agregar_rutas.php",
		type: "POST",
		data: ajaxdata2,
		success: function(datos) {

			var result = JSON.parse(datos);
		if (result!="null"){
				$.each(result, function(i, item){
		 	  agregar_lista(result[i].id_cliente,result[i].municipio,result[i].nombre_municipio,result[i].depto,result[i].nombre_departamento,result[i].nombre);
		 	});
		}else{
			display_notify("Warning","No Hay Clientes Asignados a Este Departamento!");
		}
		}
	})
});

	function agregar_lista(id_cliente,id_mun,nom_mun,id_dep,nom_dep,cliente){
		id_previo=0; filas=1;
		var id_previo=new Array();
		tr_add="";
		campo0=1;
		$("#inventable tr").each(function (index) {
				if (index>0){
	            $(this).children("td").each(function (index2) {
	           	  switch (index2){
	                    case 0:
							campo0 = $(this).text();
							if (campo0!=undefined || campo0!=''){
								id_previo.push(campo0);
							}
							break;
						}
					});
					filas=filas+1;
					} //if index>0
			});

			tr_add += "<tr id='"+filas+"'>";
			tr_add += '<td class="orden">'+filas+'</td>';
			tr_add += '<td class="cliente"><input type="hidden" class="id_departamento" value="'+id_dep+'">';
			tr_add += '<input type="hidden" class="id_municipio" value="'+id_mun+'">';
			tr_add += '<input type="hidden" class="id_cliente" value="'+id_cliente+'">';
			tr_add += cliente+'</td>';
			tr_add += '<td class="departamento">'+nom_dep+'</td>';
			tr_add += '<td class="municipio">'+nom_mun+'</td>';
			tr_add += "<td class='elim text-center'><i class='btn btn-danger fa fa-trash'></i></td></tr>";

				var existe=false;
				$.each(id_previo, function(i,id_cliente_ant){
					if(id_cliente==id_cliente_ant ){
						existe=true;
					}
				});
				if (existe==false ){
					$("#inventable").append(tr_add);
				//Aca se instancia la tabla o el body de la tabla que se reordena  !!!
				/*
					$("#inventable").tableDnD({
						onDrop: function(table, row) {
						actualiza_fila();
					}
	    });*/
				}

	}

	function actualiza_fila(){
	 	filas=0;
	 	$("#inventable tr").each(function (index) {
			if (index>0){
				filas=filas+1;
	            $(this).find("td:eq(0)").text(filas)
	        }
		});
		//$("#inventable").find("tr:eq("+filas+")").find("td:eq(0)").text(" ")
	}
function generar(){
	dataTable = $('#editable2').DataTable().destroy()
	dataTable = $('#editable2').DataTable( {
			"pageLength": 50,
			"order":[[ 0, 'asc' ], [ 1, 'asc' ]],
			"processing": true,
			"serverSide": true,
			"ajax":{
					url :"admin_rutas_dt.php",

					error: function(){  // error handling
						//$(".editable2-error").html("");
						$("#editable2").append('<tbody class="editable2_grid-error"><tr><th colspan="3">No se encontró información segun busqueda </th></tr></tbody>');
						$("#editable2_processing").css("display","none");
						$( ".editable2-error" ).remove();
						}
					},
					"columnDefs": [ {
		    "targets": 1,//index of column starting from 0
		    "render": function ( data, type, full, meta ) {
					if(data!=null)
		      return '<p class="text-success"><strong>'+data+'</strong></p>';
					else
					 return '';
		    }
		  } ]
				} );

		dataTable.ajax.reload()
}

//function to round 2 decimal places
function round(value, decimals) {
    return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
}

function senddata() {
  var nombre = $('#nombre').val();
  var datat = new Array();
  var datast = "";
  var i = 0;
	var errors = false;
var error_array = [];
var array_json = [];
  $("#cliente_table tr").each(function()
	{
    var id_cliente = $(this).find(".id_cliente").val();
    var orden = $(this).find(".orden").text();
    var obj = new Object();
    obj.id_cliente = id_cliente;
    obj.orden = orden;
    text = JSON.stringify(obj);
    datat.push(text);
    i += 1;
  });
	if(i==0){
		errors = true;
		error_array.push('Agregar Clientes a la Ruta');
	}
  datast = '[' + datat + ']';
  //Get the value from form if edit or insert
	if (nombre=="") {
		errors = true;
		error_array.push('Agregar Nombre a la Ruta');
	}
  var process = $('#process').val();
	var dataString = 'process=' + process + '&nombre=' + nombre + '&datos=' + datast;
  if (process == 'insert')
	{
    var id_cliente = 0;
    var urlprocess = 'agregar_ruta.php';
  }
  if (process == 'edit')
	{
    var id_ruta = $('#id_ruta').val();
    dataString += '&id_ruta='+id_ruta;
    var urlprocess = 'editar_ruta.php';
  }
	  if (errors==false) {
		  $.ajax({
		    type: 'POST',
		    url: urlprocess,
		    data: dataString,
		    dataType: 'json',
		    success: function(datax) {
		      process = datax.process;
		      display_notify(datax.typeinfo, datax.msg);
		      if (datax.typeinfo == "Success") {
		        setInterval("reload1();", 1500);
		      }
		    }
		  });
	}  else {
  display_notify("Error","Error en formulario: "+error_array.join(",<br>"));
	}

}

function reload1() {
  location.href = 'admin_rutas.php';
}

$(document).on("click", ".borr", function() {
	var id = $(this).attr("id");

	delete_ruta(id);
});

function delete_ruta(id) {
	swal({
			title: "Esta seguro que desea eliminar esta Ruta???",
			text: "Usted no podra deshacer este cambio",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Si, Eliminar",
			cancelButtonText: "No, Cerrar",
			closeOnConfirm: true
		},
		function() {

			$.ajax({
				type: "POST",
				url: "admin_rutas.php",
				data: "proccess=eliminar&id="+id,
				dataType: "JSON",
				success: function(datax) {
					display_notify(datax.typeinfo, datax.msg);
					if (datax.typeinfo == "success" || datax.typeinfo == "Success") {
						setInterval("reload1();", 1000);
						console.log("hola");
					}
				}
			});
		});
}

function deleted() {
	var id_ruta = $('#id_ruta').val();
	var dataString = 'process=deleted' + '&id_ruta=' + id_ruta;
	$.ajax({
		type : "POST",
		url : "borrar_ruta.php",
		data : dataString,
		dataType : 'json',
		success : function(datax) {
			display_notify(datax.typeinfo, datax.msg);
			setInterval("location.reload();", 500);
			$('#deleteModal').hide();
		}
	});
}
