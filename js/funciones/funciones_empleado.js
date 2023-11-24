$(document).ready(function() {
  $('#formulario').validate({
    rules: {
      nombre: {
        required: true,
      },
      apellido: {
        required: true,
      },
      tipo_empleado: {
        required: true,
      },
    },
    messages: {
      nombre: "Por favor ingrese un nombre",
      apellido: "Por favor ingrese un apellido",
      apellido: "Por favor seleccione el tipo de empleado",
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
  $("#salariobase").numeric({negative:false});
  $('#tipo_empleado').select2();
  $('.selectt').select2();
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

function senddata()
{
  let nombre = $('#nombre').val();
  let apellido = $('#apellido').val();
  let nit = $('#nit').val();
  let dui = $('#dui').val();
  let direccion = $('#direccion').val();
  let telefono1 = $('#telefono1').val();
  let telefono2 = $('#telefono2').val();
  let email = $('#email').val();
  let salariobase = $('#salariobase').val();
  let id_tipo_empleado = $('select#tipo_empleado option:selected').val();
  let sucursal=$('#sucursal').val();
  let id_empleado = 0;
  let urlprocess = 'agregar_empleado.php';
  //Get the value from form if edit or insert
  let process = $('#process').val();
  /*
  if (process == 'insert')
	{
    let urlprocess = 'agregar_empleado.php';
  }*/
  if (process == 'edited')
	{
    id_empleado = $('#id_empleado').val();
    urlprocess = 'editar_empleado.php';
  }
  let dataString = 'process=' + process + '&id_empleado=' + id_empleado
  dataString += '&nombre=' + nombre + '&apellido=' + apellido + '&nit=' + nit
  dataString += '&dui=' + dui+'&direccion=' + direccion
  dataString +=  '&telefono1=' + telefono1 + '&telefono2=' + telefono2
  dataString += '&email=' + email + '&salariobase=' + salariobase
  dataString +=  '&id_tipo_empleado=' + id_tipo_empleado;
  dataString += '&sucursal='+sucursal
  $.ajax({
    type: 'POST',
    url: urlprocess,
    data: dataString,
    dataType: 'json',
    success: function(datax){
      process = datax.process;
      display_notify(datax.typeinfo, datax.msg);
			if(datax.typeinfo == "Success")
			{
      	setInterval("reload1();", 1000);
			}
    }
  });
}

function reload1()
{
  location.href = 'admin_empleado.php';
}

function deleted()
{
  var id_empleado = $('#id_empleado').val();
  var dataString = 'process=deleted'+'&id_empleado='+id_empleado;
  $.ajax({
    type: "POST",
    url: "borrar_empleado.php",
    data: dataString,
    dataType: 'json',
    success: function(datax){
      display_notify(datax.typeinfo, datax.msg);
      if(datax.typeinfo == "Success")
			{
      	setInterval("reload1();", 1000);
			}
      $('#deleteModal').hide();
    }
  });
}
