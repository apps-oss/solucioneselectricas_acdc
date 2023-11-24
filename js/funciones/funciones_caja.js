$(document).ready(function()
{
  $('#formulario').validate({
  	    rules: {
            name_caja: {
            required: true,
             },
            serie: {
            required: true,
             },
            desde: {
            required: true,
             },
            hasta: {
            required: true,
             },

         },
        messages: {
						name_caja: "Por favor ingrese el nombre de caja",
						serie: "Por favor ingrese la serie",
						desde: "Por favor ingrese el valor inicial",
						hasta: "Por favor ingrese el valor final",

					},

        submitHandler: function (form) {
            senddata();
        }
      });

      $('.selectt').select2();
      $(".numeric").numeric({
        negative:false,
          decimalPlaces: 0
      });

      $("#fecha").datepicker({
        format: 'dd-mm-yyyy',
        language:'es',
      });

  $(".decimal").numeric({
    negative: false,
    decimalPlaces: 4
  });
});

function senddata()
{
  let nombre_caja = $("#name_caja").val();
  let serie = $("#serie").val();
  let desde = $("#desde").val();
  let resolucion = $("#resolucion").val();
  let fecha = $("#fecha").val();
  let hasta = $("#hasta").val();
  let process = $("#process").val();
  let id_sucursal = $("#id_sucursal").val();
  let correlativo_dispo = $("#correlativo_dispo").val();
  let sucursal = $("#sucursal").val();
  let id_caja = -1;

  let datos = "";
  let url = "agregar_caja.php";
  if(process == 'agregar')
  {

    datos += "process="+process+"&nombre_caja="+nombre_caja+"&serie="+serie
    datos +="&desde="+desde+"&hasta="+hasta+"&resolucion="+resolucion
    datos +="&fecha="+fecha+"&id_sucursal="+sucursal
    datos +="&correlativo_dispo="+correlativo_dispo

  }
  if(process == 'editar')
  {
    url     = "editar_caja.php";
    id_caja = $("#id_caja").val()
    datos = "process="+process+"&nombre_caja="+nombre_caja+"&serie="+serie
    datos +="&desde="+desde+"&hasta="+hasta+"&resolucion="+resolucion
    datos +="&fecha="+fecha+"&id_caja="+id_caja+"&id_sucursal="+sucursal;
    datos +="&correlativo_dispo="+correlativo_dispo
  }
  if(id_sucursal != "" && id_sucursal != 0)
  {
    $.ajax({
      type:'POST',
      url:url,
      data: datos,
      dataType: 'json',
      success: function(datax){
        display_notify(datax.typeinfo,datax.msg);
        if(datax.typeinfo == 'Success')
        {
          setInterval("reload1();", 1000);
        }
      }
    });
  }
  else
  {
    display_notify("Error","Debe de seleccionar la sucursal");
  }

}

$(document).on("click","#estado", function()
{
  var id_caja = $(this).parents("tr").find("#id_caja").val();
  var estado = $(this).parents("tr").find("#estado1").val();
  if(estado == 1)
  {
    var text = "Desactivar";
  }
  else
  {
      var text = "Activar";
  }
  swal({
    title: text+" esta caja?",
    text: "",
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: "btn-danger",
    confirmButtonText: "Si, "+text+" esta caja!",
    cancelButtonText: "No, cancelar!",
    closeOnConfirm: true,
    closeOnCancel: false
  },
  function(isConfirm) {
    if (isConfirm) {
      estado_pro(id_caja, estado);
      //swal("Exito", "Turno iniciado con exito", "error");
    } else {
      swal("Cancelado", "Operación cancelada", "error");
    }
  });
})
function estado_pro(id_caja, estado) {
  //var id_proveedor = $('#id_proveedor').val();
  var dataString = 'process=estado' + '&id_caja=' + id_caja+ '&estado=' + estado;
  $.ajax({
    type: "POST",
    url: "admin_caja.php",
    data: dataString,
    dataType: 'json',
    success: function(datax) {
      display_notify(datax.typeinfo, datax.msg);
      if (datax.typeinfo == "Success") {
        setInterval("reload1();", 1000);
        //$('#deleteModal').hide();
      }
    }
  });
}

function reload1() {
  location.href = 'admin_caja.php';
}
