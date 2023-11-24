$(document).ready(function()
{

  $('#formulario').validate({
    rules: {
      nombre: {
        required: true,
      },
      negocio: {
        required: true,
      },
      departamento: {
        required: true,
      },
      municipio: {
        required: true,
      },
    },
    messages: {
      nombre: "Por favor ingrese el Nombre del cliente",
      nombre: "Por favor ingrese el Nombre del Negocio",
      departamento: "Por favor seleccione un Departamento",
      municipio: "Por favor seleccione un Municipio",
    },
    highlight: function(element) {
      $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
    },
    success: function(element) {
      $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
    },
    submitHandler: function(form) {
      senddata();
    }
  });
  $("#departamento").change(function() {
    $("#municipio *").remove();
    $("#select2-municipio-container").text("");
    var ajaxdata = {
      "process": "getMunicipio",
      "id_departamento": $("#departamento").val()
    };
    $.ajax({
      url: "_helpers.php",
      type: "POST",
      data: ajaxdata,
      success: function(opciones) {
        $("#select2-municipio-container").text("Seleccione");
        $("#municipio").html(opciones);
        $("#municipio").val("");
      }
    })
  });
  $('.tel').on('keydown', function(event) {
    if (event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 13 || event.keyCode == 37 || event.keyCode == 39) {

    } else {
      if ((event.keyCode > 47 && event.keyCode < 60) || (event.keyCode > 95 && event.keyCode < 106)) {
        inputval = $(this).val();
        var string = inputval.replace(/[^0-9]/g, "");
        var bloc1 = string.substring(0, 4);
        var bloc2 = string.substring(4, 7);
        var string = bloc1 + "-" + bloc2;
        $(this).val(string);
      } else {
        event.preventDefault();
      }

    }
  });
  $('.numeric').numeric({
    negative: false,
    decimal: false
  });
  $(".decimal").numeric({
    negative: false,
    decimalPlaces: 4
  });
  $('#dui').on('keydown', function(event) {
    if (event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 13 || event.keyCode == 37 || event.keyCode == 39) {

    } else {
      if ((event.keyCode > 47 && event.keyCode < 60) || (event.keyCode > 95 && event.keyCode < 106)) {
        inputval = $(this).val();
        var string = inputval.replace(/[^0-9]/g, "");
        var bloc1 = string.substring(0, 8);
        var bloc2 = string.substring(8, 8);
        var string = bloc1 + "-" + bloc2;
        $(this).val(string);
      } else {
        event.preventDefault();
      }

    }
  });
  $('#nrc').on('keydown', function(event) {
    if (event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 13 || event.keyCode == 37 || event.keyCode == 39) {

    } else {
      if ((event.keyCode > 47 && event.keyCode < 60) || (event.keyCode > 95 && event.keyCode < 106)) {
        inputval = $(this).val();
        var string = inputval.replace(/[^0-9]/g, "");
        var bloc1 = string.substring(0, 8);
        var bloc2 = string.substring(8, 8);
        var string = bloc1 + "-" + bloc2;
        $(this).val(string);
      } else {
        event.preventDefault();
      }

    }
  });
  $('#nit').on('keydown', function(event) {
    if (event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 13 || event.keyCode == 37 || event.keyCode == 39) {

    } else {
      if ((event.keyCode > 47 && event.keyCode < 60) || (event.keyCode > 95 && event.keyCode < 106)) {
        inputval = $(this).val();
        var string = inputval.replace(/[^0-9]/g, "");
        var bloc1 = string.substring(0, 4);
        var bloc2 = string.substring(4, 10);
        var bloc3 = string.substring(10, 13);
        var bloc4 = string.substring(13, 13);
        var string = bloc1 + "-" + bloc2 + "-" + bloc3 + "-" + bloc4;
        $(this).val(string);
      } else {
        event.preventDefault();
      }
    }
  });
  $('.select').select2();
  $('.selectt').select2();
  $(".datepick").datepicker();
  $("#retiene").on("ifChecked", function() {
    $("#retiene_select").show();
    $('.select').select2();
    $("#hi_retiene").val(1);
  });
  $("#retiene").on("ifUnchecked", function() {
    $("#retiene_select").hide();
    $("#hi_retiene").val(0);
  });
	$("#sel_giro").select2({
		ajax: {
			url: "getGiro.php",
			type: "post",
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					searchTerm: params.term // search term
				};
			},
			processResults: function (response) {
				return {
					results: response
				};
			},
			cache: true
		}
	});
});
$(function() {
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
function senddata() {
  let tableData=storeTblValue();
  let direccion =$.trim($("#direccion").val()) 
  let msg = "";
  let error = false;
  let array_error = [];
  let dataString = $("#formulario").serialize();
  let aEliminar=$("#dif_eliminados").val();
  dataString+=tableData;
  dataString+='&elimina='+aEliminar;
  console.log(dataString)
  let process = $('#process').val();
  let urlprocess = '';
  
  if (direccion=="" || direccion.length<5) {
    msg = 'Registrar Dirección, al menos 5 caractere!';
    error=true;
    array_error.push(msg);
  }
  if (process == 'insert') {
     urlprocess = 'agregar_cliente.php';
  }
  if (process == 'edited') {
    urlprocess = 'editar_cliente.php';
  }
  if(error==false){
    $.ajax({
      type: 'POST',
      url: urlprocess,
      data: dataString,
      dataType: 'json',
      success: function(datax) {
        display_notify(datax.typeinfo, datax.msg);
        if (datax.typeinfo == "Success") {
          setInterval("reload1();", 1000);
        }
      }
    });
  } else {
    display_notify("Error", "En formulario: "+ array_error.join(",<br>"));
   
  }
}
let storeTblValue=()=>{
    let i=0;
    let obj ={}
    let array_json=[];
	    $("#presentacion_table tr").each(function (index) {
		    if (index>=0){
           let id_dif  = $(this).find('#id_dif').val();
           let nuevo   = $(this).find('#nuevo').val();
           let num_dif = $(this).find('#ndif').val();
           let embarc  = $(this).find('#emb').val();
           let fi      = $(this).find('#fi').val();
           let ff      = $(this).find('#ff').val();
           let limgal  = $(this).find('#limgal').val();
           let estado  = $(this).find('#estado').val();
           obj={
             id_dif : id_dif,
             num_dif : num_dif,
             embarc  : embarc,
             fi      : fi,
             ff      : ff,
             limgal  : limgal,
             nuevo   : nuevo,
             estado  :estado,
           }
           	i=i+1
          }
          array_json.push(obj)
    });
	 let valjson = JSON.stringify(array_json);
   let stringDatos="&valjson="+valjson
       stringDatos+="&cuantos="+i
   return stringDatos;
}

function reload1() {
  location.href = 'admin_cliente.php';
}
function deleted() {
  var id_cliente = $('#id_cliente').val();
  var dataString = 'process=deleted' + '&id_cliente=' + id_cliente;
  $.ajax({
    type: "POST",
    url: "borrar_cliente.php",
    data: dataString,
    dataType: 'json',
    success: function(datax) {
      display_notify(datax.typeinfo, datax.msg);
      if (datax.typeinfo == "Success") {
        setInterval("location.reload();", 1000);
        $('#deleteModal').hide();
      }
    }
  });
}
$(document).on("click", "#add_pre", function() {
  $(this).attr('disabled', 'disabled');
  setTimeout("",1500);
  btn = $(this);
  let id_cliente   = $("#id_cliente").val();
  let id_dif   = -1;
  let numero_dif   = $("#numero_dif").val();
  let embarcacion  = $("#embarcacion").val();
  let fecha_inicio = $("#fecha_inicio").val();
  let fecha_fin    = $("#fecha_fin").val();
  let limite_galon = $("#limite_galon").val();
  let proceso=$("#process").val();
  embarcacion = String(embarcacion).replace(/[^a-zA-Z0-9 *]/g, "");
  if (numero_dif != "" && embarcacion != "" &&  fecha_inicio != ""
      && fecha_fin != "" && limite_galon!= "" && limite_galon>0 ) {
    let exis = false;
    $("#presentacion_table tr").each(function() {
      let num_dif = $(this).find(".num_dif").val();
      if (num_dif == numero_dif) {
          exis = true;
      }

    });
    if (exis)
		{
      display_notify("Warning", "Ya agrego ese numero de DIF");
      btn.removeAttr('disabled');
    } else {
        let dataString = 'process=cargar_tr_dif'+'&id_dif='+id_dif
        dataString += '&numero_dif='+numero_dif
        dataString += '&embarcacion='+embarcacion+'&fecha_inicio='+fecha_inicio
        dataString += '&fecha_fin='+fecha_fin+'&limite_galon='+limite_galon
        $.ajax({
          url: 'editar_cliente.php',
          type: 'POST',
          dataType: 'json',
          data: dataString,
          success: function(datax)
          {
            btn.removeAttr('disabled');
            $("#presentacion_table").append(datax.datos);
              $(".clear").val("");
                $(".datepick").datepicker();
                $('.select2').select2();
                $(".decimal").numeric({
                  negative: false,
                  decimalPlaces: 4
                });
          }
        });
      //}
    }
  } else {
    display_notify("Error", "Por favor complete todos los campos");
    btn.removeAttr('disabled');
  }
});
$(document).on("click", ".elmdif", function() {
  let array=[];
  let tr= $(this).parents("tr");
  let nuevo =tr.find("#nuevo").val();
  if(nuevo==1){
    tr.remove();
  }else{
    let id_dif =tr.find("#id_dif").val();
    if($("#dif_eliminados").val()!=""){
        array.push($("#dif_eliminados").val());
    }
    array.push(id_dif)
    $("#dif_eliminados").val(array);
    console.log("array:"+array)
    tr.remove();
  }
});
