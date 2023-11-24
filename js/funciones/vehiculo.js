$(document).ready(function()
{

  $('#formulario').validate({
    rules: {
      id_marca: {
        required: true,
      },
      id_modelo: {
        required: true,
      },
      placa: {
        required: true,
      },
      unidad: {
        required: true,
      },
      year: {
        required: true,
      },
    },
    messages: {
      id_marca: "Por seleccione Marca",
      id_modelo: "Por seleccione Modelo",
      placa: "Por favor ingrese Placa",
      unidad: "Por favor ingrese numero de unidad",
      year: "Por favor  seleccione año",
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


  $('.numeric').numeric({
    negative: false,
    decimal: false
  });
  $(".decimal").numeric({
    negative: false,
    decimalPlaces: 4
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
});
$(function() {
  //binding event click for button in modal form
  $(document).on("click", "#btnDelete", function(event) {
    deleted();
  });
  // Clean the modal form
  /*
  $(document).on('hidden.bs.modal', function(e) {
    var target = $(e.target);
    target.removeData('bs.modal').find(".modal-content").html('');
  });
*/
});
function senddata() {

  let dataString = $("#formulario").serialize();
  console.log(dataString)
  let error = false;
  let array_error = [];
  let   msg = "";
  let process = $('#process').val();
  let urlprocess = '';
  if (process == 'insert') {
     urlprocess = 'agregar_vehiculo.php';
  }
  if (process == 'edited') {
    urlprocess = 'editar_vehiculo.php';
  }
  if($("#marca").val()==-1){
    msg = 'Seleccione marca !';
    error=true;
    array_error.push(msg);
  }
  if ( error == false) {
    $.ajax({
      type: 'POST',
      url: urlprocess,
      data: dataString,
      dataType: 'json',
      success: function(datax) {
        display_notify(datax.typeinfo, datax.msg);
        if (datax.typeinfo == "Success") {

          if(process == 'edited')
          {
            console.log("OK,  id:"+datax.id);
            // $("#id_id_p").val(datax.id_producto);
            editar_img();
          }
          if (process == 'insert')
          {
            //display_notify(datax.typeinfo, datax.msg);
            $("#id_id_p").val(datax.id);
            img();
          }
          //setInterval("reload1();", 1000);
        }
      }
    });
  }else {
     display_notify("Error", "En formulario: "+ array_error.join(",<br>"));
    }
}


function reload1() {
  location.href = 'admin_vehiculo.php';
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


$("#marca").change(function() {
  $("#modelo *").remove();
  $("#select2-modelo-container").text("");
  let ajaxdata = {
    "process": "modelo",
    "id_marca": $("#marca").val()
  };
  $.ajax({
    url: "agregar_vehiculo.php",
    type: "POST",
    data: ajaxdata,
    delay: 250,
    success: function(opciones) {
      $("#select2-modelo-container").text("Seleccione");
      $("#modelo").html(opciones);
      $("#modelo").val("");
    }
  })
});


$(document).on("click", "#btn_img", function()
{
	$('#viewVehiculo').modal({backdrop: 'static',keyboard: false});
  $("#logo").fileinput({
    'showUpload': false
})
});

function img()
{
  let form = $("#formulario_pro");
  let formdata = false;
  if(window.FormData)
  {
      formdata = new FormData(form[0]);
  }
let formAction = form.attr('action');
let serialize=form.serialize();
console.log (serialize)
  $.ajax({
      type        : 'POST',
      url         : "agregar_vehiculo.php",
      cache       : false,
      data        : formdata ? formdata : serialize,
      contentType : false,
      processData : false,
      dataType : 'json',
      success: function(datax)
      {
        display_notify(datax.typeinfo, datax.msg);
        if (datax.typeinfo == "Success")
        {
          //setInterval("reload1();", 1000);
        }
      }
  });
}
$("#btnGimg").click(function()
{
  let process = $('#viewVehiculo .modal-body #process').val();
  if (process=='editar_img'){
    editar_img();
    $("#cerrar_ven").click();
  }else{
      $("#cerrar_ven").click();
  }
});
$(document).on('shown.bs.modal', function(e) {
  $("#logo").fileinput({
    'showUpload': false
  })

  $(".fileinput-upload").hide();
  $.fn.fileinput.defaults = {
    language: 'en',
    showCaption: true,
    showPreview: true,
    showRemove: true,
    showUpload: false, // <------ just set this from true to false
    showCancel: true,
    showUploadedThumbs: true,
    // many more below
  };
});

function editar_img()
{
	var form = $("#formulario_pro");
  var formdata = false;
  if(window.FormData)
  {
      formdata = new FormData(form[0]);
  }
  var formAction = form.attr('action');
  $.ajax({
      type        : 'POST',
      url         : "editar_vehiculo.php",
      cache       : false,
      data        : formdata ? formdata : form.serialize(),
      contentType : false,
      processData : false,
      dataType : 'json',
      success: function(datax)
      {
        display_notify(datax.typeinfo, datax.msg);
        if (datax.typeinfo == "Success")
        {
        //  setInterval("location.reload();", 1000);
        }
      }
  });
}
