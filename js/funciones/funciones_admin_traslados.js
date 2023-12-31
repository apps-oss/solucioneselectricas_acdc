$(document).ready(function() {
  $(".datepick").datepicker();
  $('.select').select2();
  generar();

});
$(function() {
  //binding event click for button in modal form
  $(document).on("click", "#btnDelete", function(event) {
  });
  // Clean the modal form
  $(document).on('hidden.bs.modal', function(e) {
    var target = $(e.target);
    target.removeData('bs.modal').find(".modal-content").html('');
  });
  $(document).on("click", "#anular", function(event) {
    anular();
  });
});
function generar(){
  let origen    = $('#origen').val();
  let pro       = $('#pro').val();
  let estado    = $('#estado').val();

	const url 			="admin_traslados_dt.php?origen="+origen+"&pro="+pro+"&estado="+estado
	const obj_order	=  [[0, 'desc']]
	generateDT('#editable2',url,obj_order )
}
function generar1() {
  let origen    = $('#origen').val();
  let pro       = $('#pro').val();
  let estado    = $('#estado').val();
  //alert("origen:"+origen+" proceso:"+pro+" estado:"+estado)
  let dataTable = $('#editable2').DataTable().destroy()
  dataTable = $('#editable2').DataTable({
    language:
    {
      "sProcessing":     "Procesando...",
      "sLengthMenu":     "Mostrar _MENU_ registros",
      "sZeroRecords":    "No se encontraron resultados",
      "sEmptyTable":     "Ningún dato disponible en esta tabla",
      "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
      "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
      "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
      "sInfoPostFix":    "",
      "sSearch":         "Buscar:",
      "sUrl":            "",
      "sInfoThousands":  ",",
      "sLoadingRecords": "Cargando...",
      "oPaginate": {
          "sFirst":    "Primero",
          "sLast":     "Último",
          "sNext":     "Siguiente",
          "sPrevious": "Anterior"
      },
      "oAria": {
          "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
          "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      }
    },
    "pageLength": 50,
    "order": [
      [0, 'desc'],
    ],
    "processing": true,
    "serverSide": true,
    "ajax": {
      url: "admin_traslados_dt.php",
      "data": {
        "origen": origen,
        "pro" : pro,
        "estado" : estado,
      },
      error: function() { // error handling
        //$(".editable2-error").html("");
        $("#editable2").append('<tbody class="editable2_grid-error"><tr><th colspan="6">No se encontró información segun busqueda </th></tr></tbody>');
        $("#editable2_processing").css("display", "none");
        $(".editable2-error").remove();
      }
    },
    /*"aoColumnDefs": [
      { "bSearchable": false,"bSortable":false, "aTargets": [ 3] }
    ],*/
    "columnDefs": [{
      "targets": 1, //index of column starting from 0
      "render": function(data, type, full, meta) {
        if (data != null)
          return '<p class="text-success"><strong>' + data + '</strong></p>';
        else
          return '';
      }
    }]
  });
  dataTable.ajax.reload()
}

function anular() {
  var id_movimiento = $('#id_movimiento').val();
  var dataString = 'process=anular' + '&id_movimiento=' + id_movimiento;
  $.ajax({
    type: "POST",
    url: "anular_traslado.php",
    data: dataString,
    dataType: 'json',
    success: function(datax) {
      display_notify(datax.typeinfo, datax.msg);
      if (datax.typeinfo != "Error") {
        setInterval("location.reload();", 1000);
        $('#viewModal').hide();
      }
    }
  });
}

$(document).on('change', '#origen', function(event) {
  generar();

});
$(document).on('change', '#pro', function(event) {

  if ($(this).val()!="env") {
    $('#estado').select2("destroy");
    $('#estado').val("pe");
    $('#estado option[value=gu]').attr('disabled', true);
    $('#estado').select2();
  }
  else
  {
    $('#estado').select2("destroy");
    $('#estado option[value=gu]').removeAttr('disabled', true);
    $('#estado').select2();
  }

  generar();

});
$(document).on('change', '#estado', function(event) {
  generar();

});
