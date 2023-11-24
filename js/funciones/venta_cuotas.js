let urlprocess =  "venta_cuotas.php";
let sending = 0;
let array_Impuestos=[]
let array_Cotiza = {}
$(window).keydown(function(event) {
    if (event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
});

$(document).on('keydown', '.confirm', function(event) {
  if (event.keyCode == 13)
  {
    setTimeout(function() {
      location.reload();
    },500);
  }
});

$(document).ready(function() {
    array_Impuestos= getParamCuotas();
  $(".select_r").select2({
    placeholder: {
      id: '0',
      text: 'Seleccione',
    },
    allowClear: true,
  });

  $('#num_doc_fact').numeric({
    negative: false,
    decimal: false
  });
  $('html,body').animate({
    scrollTop: $(".focuss").offset().top
  }, 1500);
  //$('#codigo').focus();
  let duration = 500;
  $({to:0}).animate({to:1}, duration, function() {
      $("#producto_buscar").focus()
  });
  $(".select").select2({
    placeholder: {
      id: '',
      text: 'Seleccione',
    },
    allowClear: true,
  });
  $(".selectt").select2()
  $("#codigo").keyup(function(evt)
	{
		let code = $(this).val();
    if (evt.keyCode == 13)
		{
			if($(this).val()!="")
			{
      	addProductList(code, "C");
			}
			$(this).val("");
    }
  });

  $("#scrollable-dropdown-menu #producto_buscar").typeahead({
    highlight: true,
  }, {
    limit: 100,
    name: 'productos',
    display: 'producto',
    source: function show(q, cb, cba) {
      console.log(q);
      let url = 'autocomp_producto.php' + "?query=" + q;
      $.ajax({
          url: url
        })
        .done(function(res) {
          cba(JSON.parse(res));
        })
        .fail(function(err) {
          alert(err);
        });
    }
  }).on('typeahead:selected', onAutocompleted);

  function onAutocompleted($e, datum) {
    $('.typeahead').typeahead('val', '');
    let prod0 = datum.producto;
    let prod = prod0.split("|");
    let id_prod = prod[0];
    let descrip = prod[1];
    addProductList(id_prod, "D");
  }


  // Clean the modal form
  $(document).on('change', '#n_ref', function(event) {
    cargar_ref();
    /* Act on the event */
  });

  $(".decimal").numeric({
    negative: false,
    decimalPlaces: 4
  });

  document.addEventListener('keydown', event => {
    if (event.ctrlKey && event.keyCode == 13) {
      event.preventDefault();
      event.stopPropagation();
      if ($('#b').attr('hidden')) {
        $('#codigo').focus();
      } else {
        $('#producto_buscar').focus();
      }
    }
  }, false);

  document.addEventListener('keydown', event => {

    if (event.ctrlKey && event.keyCode == 82) {
      event.preventDefault();
      event.stopPropagation();
      console.log(event.keyCode);

      $('#n_ref').select2("open");

    }
  }, false);

  $(document).on('keydown', '#num_ref', function(event) {
    if (event.keyCode == 13) {
      $('#aut_code').removeAttr('disabled');
      $('#aut_code').val("");
      $('#clave').val('');
      $('#precio_aut').val('');

      if ($(this).val() != "") {
        a = $('#vendedor');
        b = $('#id_cliente');
        c = $('#tipo_impresion');


        let n_ref = $("#num_ref").val();
        let fecha = $("#fecha").val();
        $("#num_ref").val("")
        $.ajax({
          type: 'POST',
          url: urlprocess,
          data: "process=cargar_data&n_ref=" + n_ref + "&fecha=" + fecha,
          dataType: 'json',
          success: function(datax) {
            let id_cliente = datax.id_cliente;
            let nombre_cliente = datax.nombre_cliente;
            let alias_tipodoc = datax.alias_tipodoc;
            let lista = datax.lista;
            if (datax.typeinfo == "Success") {
              b.empty().trigger('change');
              c.empty().trigger('change');

              $("#id_cliente").html(datax.select_cliente);
              b.trigger('change');
              $("#tipo_impresion").html(datax.select_tipo_impresion);
              c.trigger('change');
              $("#inventable").html(lista);

              $("#id_empleado").val(datax.id_empleado);
              $("#vendedor").val(datax.id_empleado);
              $("#id_factura").val(datax.id_factura);
              $("#numero_doc").val(datax.numero_doc);

              $('#aut_code').removeAttr('disabled');
              $('#clave').val(datax.clave);
              $('#precio_aut').val(datax.precio_aut);

              if (datax.clave!='') {
                $('#aut_code').val(datax.clave);
                $('#aut_code').attr('disabled', 'disabled');
              }

              $(".cant").numeric({
                negative: false,
                decimal: false
              });
              $(".86").numeric({
                negative: false,
                decimalPlaces: 4
              });

              $(".sel").select2();
              $(".sel_r").select2();

              porc_retencion1 = datax.retencion1;
              porc_retencion10 = datax.retencion10;
              $("#porc_retencion1").val(porc_retencion1);
              $("#porc_retencion10").val(porc_retencion10);
              if ($('#b').attr('hidden')) {
                $('#codigo').focus();
              } else {
                $('#producto_buscar').focus();
              }

              setTotals();
            } else {

              display_notify(datax.typeinfo, datax.msg);


              $("#id_cliente").val("1");
              $("#id_cliente").trigger('change');

              $("#text_cliente").val("");
              $("#tipo_impresion").val("TIK");
              $("#tipo_impresion").trigger('change');

              $("#vendedor").val("");
              $("#caja_detalles").html("");
              $("#id_empleado").val("");
              $("#id_factura").val("");
              $("#numero_doc").val("");
              $("#inventable").html("");
              setTotals();
            }
          }
        });

      }
    }
  });

  document.addEventListener('keydown', event => {
    if (event.ctrlKey && event.keyCode == 16) {
      event.stopPropagation();

    }
  }, false);

  $(document).keydown(function(e) {
    if (e.which == 113) { //F2 Guardar
      $("#submit1").click();
      e.stopPropagation();
    }
     if (e.which == 27) { //F2 Guardar
      $("#abrir").click();
      e.stopPropagation();
    }
    if (e.which == 114) { //F3 salir
      e.stopPropagation();
      e.preventDefault();

			if ($('#a').attr('hidden')) {
				$('#a').removeAttr('hidden');
				$('#b').attr('hidden', 'hidden');
				$('#codigo').focus();
				$('#change').html("<i class='fa fa-exchange'></i> F3 Descripción");
			} else {
				$('#b').removeAttr('hidden');
				$('#a').attr('hidden', 'hidden');
				$('#producto_buscar').focus();
				$('#change').html("<i class='fa fa-exchange'></i> F3 Código");
			}
    }
    if (e.which == 115) { //F4 salir
      location.replace("dashboard.php");
      e.stopPropagation();
      e.preventDefault();
    }
    if (e.which == 119) { //F8 guardar como preventa
      e.stopPropagation();
      guardar_preventa();
    }
    if (e.which == 120) { //F9  vale
      e.stopPropagation();
      e.preventDefault();
      $('#xa').click();

    }
    if (e.which == 121) { //F10 ingreso
      e.stopPropagation();
      e.preventDefault();
      $('#xb').click();
    }
    if (e.which == 117) {
      e.stopPropagation();
      borrar_preventa();
      e.preventDefault();
    }
    if (e.which == 46) /*suprimir*/ {
      $("#inventable tr:first-child").remove();
      setTotals();
      let filas = $("#filas").val();
      filas --;
      $("#filas").val(filas);
      e.preventDefault();
			if ($('#b').attr('hidden')) {
				$('#codigo').focus();
			} else {
				$('#producto_buscar').focus();
			}
    }
  });
  $('#form_fact_consumidor').hide();
  $('#form_fact_ccfiscal').hide();

  //Boton de imprimir deshabilitado hasta que se guarde la factura
  $('#print1').prop('disabled', true);
  $('#submit1').prop('disabled', false);
  //$('#print1').prop('disabled', false);
});
$(document).on('hidden.bs.modal', function(e) {
  let target = $(e.target);
  target.removeData('bs.modal').find("#modalPago .modal-content").html('');
});

$(document).on('click','#change', function() {
  if ($('#a').attr('hidden')) {
	$('#a').removeAttr('hidden');
	$('#b').attr('hidden', 'hidden');
	$('#codigo').focus();
	$('#change').html("<i class='fa fa-exchange'></i> F3 Descripción");
} else {
	$('#b').removeAttr('hidden');
	$('#a').attr('hidden', 'hidden');
	$('#producto_buscar').focus();
	$('#change').html("<i class='fa fa-exchange'></i> F3 Código");
}
});

//function to round 2 decimal places
function round(value, decimals) {
  return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
}
$(function() {
  //binding event click for button in modal form
  $(document).on("click", "#btnDelete", function(event) {
    deleted();
  });
    $(document).on("click", "#abrir", function(event) {
    abrir_caja();
  });
  // Clean the modal form
  /*
  $(document).on('hidden.bs.modal', function(e) {
    let target = $(e.target);
    target.removeData('bs.modal').find(".modal-content").html('');
    location.reload();
  });*/

});



function cargar_ref() {
  a = $('#vendedor');
  b = $('#id_cliente');
  c = $('#tipo_impresion');


  let n_ref = $("#n_ref").val();
  let fecha = $("#fecha").val();
  $.ajax({
    type: 'POST',
    url: urlprocess,
    data: "process=cargar_data&n_ref=" + n_ref + "&fecha=" + fecha,
    dataType: 'json',
    success: function(datax) {
      let id_cliente = datax.id_cliente;
      let nombre_cliente = datax.nombre_cliente;
      let alias_tipodoc = datax.alias_tipodoc;
      let lista = datax.lista;
      if (datax.typeinfo == "Success") {
        b.empty().trigger('change');
        c.empty().trigger('change');

        $("#id_cliente").html(datax.select_cliente);
        b.trigger('change');

        $("#tipo_impresion").html(datax.select_tipo_impresion);
        c.trigger('change');

        $("#inventable").html(lista);
        //console.log(lista);
        $("#id_empleado").val(datax.id_empleado);
        $("#vendedor").val(datax.id_empleado);
        $("#id_factura").val(datax.id_factura);
        $("#numero_doc").val(datax.numero_doc);

        $(".decimal").numeric({
          negative: false,
          decimalPlaces: 4
        });
        $(".86").numeric({
          negative: false,
          decimalPlaces: 4
        });

        $(".decimal2").numeric({
          negative: false,
          decimal: false
        });
        $(".86").numeric({
          negative: false,
          decimalPlaces: 4
        });

        $(".sel").select2();
        $(".sel_r").select2();

        porc_retencion1 = datax.retencion1;
        porc_retencion10 = datax.retencion10;
        $("#porc_retencion1").val(porc_retencion1);
        $("#porc_retencion10").val(porc_retencion10);
        if ($('#b').attr('hidden')) {
          $('#codigo').focus();
        } else {
          $('#producto_buscar').focus();
        }

        setTotals();
      } else {
        /*
        display_notify(datax.typeinfo,datax.msg);
        */

        $("#id_cliente").val("");
        $("#id_cliente").trigger('change');

        $("#text_cliente").val("");
        $("#tipo_impresion").val("");
        $("#tipo_impresion").trigger('change');

        $("#vendedor").val("");
        $("#caja_detalles").html("");
        $("#id_empleado").val("");
        $("#id_factura").val("");
        $("#numero_doc").val("");
        $("#inventable").html("");
        setTotals();
      }
    }
  });
}
// Evento para seleccionar una opcion y mostrar datos en un div
$(document).on("change", "#tipo_entrada", function() {
  $(".datepick2").datepicker();
  $('#id_proveedor').select2();

  let id = $("select#tipo_entrada option:selected").val(); //get the value
  if (id != '0') {
    $('#buscador').show();
  } else
    $('#buscador').hide();

  if (id == '1')
    $('#form_fact_consumidor').show();
  else
    $('#form_fact_consumidor').hide();


  if (id == '2')
    $('#form_fact_ccfiscal').show();
  else
    $('#form_fact_ccfiscal').hide();

});
// Seleccionar el tipo de factura
$(document).on("change", "#tipo_entrada", function() {
  let id = $("select#tipo_entrada option:selected").val(); //get the value
  $('#mostrar_numero_doc').load('editar_factura.php?' + 'process=mostrar_numfact' + '&id=' + id);
});

// Agregar productos a la lista del inventario
function cargar_empleados() {
  $('#inventable>tbody>tr').find("#select_empleado").each(function() {
    $(this).load('editar_factura.php?' + 'process=cargar_empleados');
    setTotals();
  });
}

// Evento que selecciona la fila y la elimina de la tabla
$(document).on("click", "#delprod", function() {
  $(this).parents("tr").remove();
  setTotals();
});

//Evento que se activa al perder el foco en precio de venta y cantidad:

$(document).on('change', '.sel_prec', function() {
  let tr = $(this).parents("tr");
  let precio = $(this).find(':selected').val();
  tr.find("#precio_venta").val(precio);
  actualiza_subtotal(tr);
});
$(document).on('keyup', '#cant', function() {
  fila = $(this).parents('tr');
  id_producto = fila.find('.id_pps').text();
  existencia = parseFloat(fila.find('#cant_stock').text());
  existencia = round(existencia, 4);
  a_cant = $(this).val();
  unidad = parseInt(fila.find('#unidades').val());
  a_cant = parseFloat(a_cant * unidad);
  a_cant = round(a_cant, 4);

  //console.log(a_cant);
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

  if (a_asignar > existencia) {
    val = existencia - (a_asignar - a_cant);
    val = val / unidad;
    val = Math.trunc(val);
    val = parseInt(val);
    $(this).val(val);
    setTimeout(function() {
      setTotals();
    }, 1000);
  } else {
    setTotals();
  }
  let tr = $(this).parents("tr");

  id_presentacion_p = fila.find('.sel').val();

  setTimeout(function() {
    actualiza_subtotal(tr);
  }, 300);
});

$(document).on("keyup", "#precio_venta", function() {
  tr = $(this).closest('tr');
  precio = parseFloat($(this).val());

  if (isNaN(precio)) {
    precio=0;
  }
 /*
  precio_rank = parseFloat(tr.find('.sel_r').val());
  precio_rank_f = truncateDecimals(precio_rank,2);
  if (precio!=0 && precio<precio_rank_f) {
    tr.find("#precio_venta").val(precio_rank);
    precio = precio_rank;
  }
  */
  precio_rank = parseFloat(tr.find('.sel_r').val());
  precio_rank_f = truncateDecimals(precio_rank,2);
  if (precio<=0) {
    tr.find("#precio_venta").val(precio_rank);
    precio = precio_rank;
  }
  tr.find("#precio_sin_iva").val(precio / 1.13);
  actualiza_subtotal(tr);
});

function truncateDecimals (num, digits) {
    let numS = num.toString(),
        decPos = numS.indexOf('.'),
        substrLength = decPos == -1 ? numS.length : 1 + decPos + digits,
        trimmedResult = numS.substr(0, substrLength),
        finalResult = isNaN(trimmedResult) ? 0 : trimmedResult;

    return parseFloat(finalResult);
}

function actualiza_subtotal(tr) {
  let iva = parseFloat($('#porc_iva').val());
  let precio_sin_iva = parseFloat(tr.find('#precio_sin_iva').val());
  let existencias    = tr.find('#cant_stock').text();
  let tipo_impresion = $('#tipo_impresion').val();
  let cantidad = 0;
  let cantfinal = 0;
  let precio =  0;
  if (tipo_impresion != 'CCF') {
    precio = tr.find('#precio_venta').val();
  }
  else {
    precio = tr.find('#precio_sin_iva').val();
  }
    cantidad = parseInt(tr.find('#cant').val());
   if (isNaN(cantidad) || cantidad == "") {
     cantidad = 0;
   }
    if (isNaN(precio) || precio == "") {
      precio = 0;
    }
    let subtotal = subt(cantidad, precio);
    subtotal = round(subtotal, 2);
    let subt_mostrar = subtotal.toFixed(4);
    tr.find("#subtotal_fin").val(subt_mostrar);
    tr.find("#subtotal_mostrar").val(subt_mostrar);
    setTotals();
}


// actualize table data to server
$(document).on("click", "#submit1", function() {
  let vacio=1
  let tipo_impresion = $("#tipo_impresion").val();
  let numdoc =   $("#numdoc").val()
  if( Object.keys(array_Cotiza).length === 0 ){
      display_notify('Warning', 'No tiene cotización de productos!');
  }else {
    let jsonCotiza = JSON.stringify(array_Cotiza);
    console.log(jsonCotiza);
    if (numdoc != "" && tipo_impresion !='TIK') {
     start_docValida();
     }else{
       senddata();
     }
  }
});
$(document).on("click", "#preventa", function() {
  guardar_preventa();
});
$(document).on("click", "#borrar_preven", function() {
  borrar_preventa();
});
$(document).on("click", "#btnEsc", function(event) {
  reload1();
});
$(document).on("click", "#btnCloseView", function(event) {
  $('#viewProd').modal('hide');
});
$(document).on("click", ".print1", function() {
  let totalfinal = parseFloat($('#totalfactura').val());
  let facturado = totalfinal.toFixed(2);
  $(".modal-body #facturado").val(facturado);
});

$(document).on("click", "#print2", function() {
  imprime2();
});

function borrar_preventa() {
  let id_factura = parseInt($("#id_factura").val());
  let corr_in = $("#corr_in").val();
  if (isNaN(id_factura)) {
    id_factura = 0;
  }
  if (id_factura != 0 && corr_in == "") {

    swal({
      title: "¿Esta seguro?",
      text: "Esto eliminara la preventa de manera permanente",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: '',
      confirmButtonText: 'Borrar',
      closeOnConfirm: false,
      closeOnCancel: true
    }, function(isConfirm) {
      if (isConfirm) {
        $.ajax({
          url: urlprocess,
          type: 'POST',
          dataType: 'json',
          data: {
            process: "borrar_preventa",
            id_factura: id_factura
          },
          success: function(datax) {
            if (datax.typeinfo == "Success") {
              display_notify(datax.typeinfo, datax.msg);
              setInterval("reload1();", 1000);
            }
          }
        });
      } else {}

    });
  }
}



$(document).on("keyup", "#efectivo", function() {
  total_efectivo();
});

$(document).on("keyup", "#numdoc", function(evt) {
  if (evt.keyCode == 13) {
    if ($(this).val() != "") {
       $("#numdoc2").val($(this).val());
      	//imprimev();
        let valida= validarNumdoc();
    } else {
      display_notify('Warning', 'Ingrese el numero del documento a imprimir');
    }
  }
});
$(document).on("keyup", "#nomcli", function(evt) {
  if (evt.keyCode == 13) {
    if ($(this).val() != "") {
      $("#dircli").focus();
    } else {
      display_notify('Warning', 'Ingrese el nombre del cliente');
    }
  }
});
$(document).on("keyup", "#dircli", function(evt) {
  if (evt.keyCode == 13) {
    if ($(this).val() != "") {
      if ($("#tipo_impresion").val() == 'CCF') {
        $("#nitcli").focus();
      } else {
        $("#efectivov").focus();
      }
    } else {
      display_notify('Warning', 'Ingrese la direccion del cliente');
    }
  }

});
$(document).on("keyup", "#nitcli", function(evt) {
  if (evt.keyCode == 13) {
    if ($(this).val() != "") {
      $("#nrccli").focus();
    } else {
      display_notify('Warning', 'Ingrese el numero de NIT del cliente');
    }
  }
});
$(document).on("keyup", "#nrccli", function(evt) {
  if (evt.keyCode == 13) {
    if ($(this).val() != "") {
      $("#efectivov").focus();
    } else {
      display_notify('Warning', 'Ingrese el numero de registro del cliente');
    }
  }
});

function activa_modal(numfact, numdoc, id_cliente) {
  //urlprocess = urlprocess;
  $('#num_doc_fact').numeric({
    negative: false,
    decimal: false
  });
  $('#viewModal').modal({
    backdrop: 'static',
    keyboard: false
  });
  let totalfinal = parseFloat($('#total_dinero').text());
  let tipo_impresion = $('#tipo_impresion').val();
  if (tipo_impresion == "TIK") {
    $('#fact_cf').hide();
  } else {
    $('#fact_cf').show();
  }
  if (tipo_impresion == "CCF") {
    $('#ccf').show();

    //para traer datos de cliente si existe
    let id_client = $('#id_cliente').val();
    let dataString = 'process=mostrar_datos_cliente' + '&id_client=' + id_client;
    $.ajax({
      type: 'POST',
      url: urlprocess,
      data: dataString,
      dataType: 'json',
      success: function(data) {
        nit = data.nit;
        registro = data.registro;
        nombreape = data.nombreape;
        $('#nit').val(nit);
        $('#nrc').val(registro);
        $('#nombreape').val(nombreape);
      }
    });

  } else {
    $('#ccf').hide();
  }
  let facturado = parseFloat($('#totalfactura').val()).toFixed(2);
  $(".modal-body #facturado").val(facturado);

  $(".modal-body #fact_num").html(numdoc);
  $(".modal-body #efectivo").focus();
}




function imprime1() {
  let numero_doc = $(".modal-body #fact_num").html();
  let print = 'imprimir_fact';
  let tipo_impresion = $("#tipo_impresion").val();
  let fecha_fact = $("#fecha_fact").val();

  let id_factura = $("#id_factura").val();
  if (tipo_impresion == "TIK") {
    let num_doc_fact = '';
    numero_factura_consumidor = '';
  } else {
    let numero_factura_consumidor = $(".modal-body #num_doc_fact").val();
    let num_doc_fact = $(".modal-body #num_doc_fact").val();
  }
  let dataString = 'process=' + print + '&numero_doc=' + numero_doc + '&tipo_impresion=' + tipo_impresion + '&num_doc_fact=' + id_factura + '&numero_factura_consumidor=' + numero_factura_consumidor + '&fecha_fact=' + fecha_fact;

  if (tipo_impresion == "CCF") {
    nit = $('.modal-body #nit').val();
    nrc = $('.modal-body #nrc').val();
    nombreape = $('.modal-body #nombreape').val();
    dataString += '&nit=' + nit + '&nrc=' + nrc + '&nombreape=' + nombreape;
  }
  $.ajax({
    type: 'POST',
    url: urlprocess,
    data: dataString,
    dataType: 'json',
    success: function(datos) {
      let sist_ope = datos.sist_ope;
      let dir_print = datos.dir_print;
      let shared_printer_win = datos.shared_printer_win;
      let shared_printer_pos = datos.shared_printer_pos;
      let headers = datos.headers;
      let footers = datos.footers;
      let efectivo_fin = parseFloat($('#efectivo').val());
      let cambio_fin = parseFloat($('#cambio').val());

      //esta opcion es para generar recibo en  printer local y validar si es win o linux
      if (tipo_impresion == 'COF') {
        if (sist_ope == 'win') {
          $.post("http://" + dir_print + "printfactwin1.php", {
            datosventa: datos.facturar,
            efectivo: efectivo_fin,
            cambio: cambio_fin,
            shared_printer_win: shared_printer_win
          })
        } else {
          $.post("http://" + dir_print + "printfact1.php", {
            datosventa: datos.facturar,
            efectivo: efectivo_fin,
            cambio: cambio_fin
          }, function(data, status) {

            if (status != 'success') {

            }

          });
        }
      }
      if (tipo_impresion == 'ENV') {
        if (sist_ope == 'win') {
          $.post("http://" + dir_print + "printenvwin1.php", {
            datosventa: datos.facturar,
            efectivo: efectivo_fin,
            cambio: cambio_fin,
            shared_printer_win: shared_printer_win
          })
        } else {
          $.post("http://" + dir_print + "printenv1.php", {
            datosventa: datos.facturar,
            efectivo: efectivo_fin,
            cambio: cambio_fin
          }, function(data, status) {

            if (status != 'success') {

            }

          });
        }
      }
      if (tipo_impresion == 'TIK') {
        if (sist_ope == 'win') {
          $.post("http://" + dir_print + "printposwin1.php", {
            datosventa: datos.facturar,
            efectivo: efectivo_fin,
            cambio: cambio_fin,
            shared_printer_pos: shared_printer_pos,
            headers: headers,
            footers: footers,
          })
        } else {
          $.post("http://" + dir_print + "printpos1.php", {
            datosventa: datos.facturar,
            efectivo: efectivo_fin,
            cambio: cambio_fin,
            headers: headers,
            footers: footers,
          }, function(data, status) {

            if (status != 'success') {

            }

          });
        }
      }
      if (tipo_impresion == 'CCF') {
        if (sist_ope == 'win') {
          $.post("http://" + dir_print + "printcfwin1.php", {
            datosventa: datos.facturar,
            efectivo: efectivo_fin,
            cambio: cambio_fin,
            shared_printer_win: shared_printer_win
          })
        } else {
          $.post("http://" + dir_print + "printcf1.php", {
            datosventa: datos.facturar,
            efectivo: efectivo_fin,
            cambio: cambio_fin
          }, function(data, status) {

            if (status != 'success') {

            }

          });
        }
      }
      //  setInterval("reload1();", 500);
    }
  });
}

function imprimev1() {
  let imprimiendo = parseInt($('#imprimiendo').val());
  $('#imprimiendo').val(1);
  let numero_doc = $("#numdoc").val();
  let print = 'imprimir_fact';
  let tipo_impresion = $("#tipo_impresion").val();
  let tipo_impresiona = $("#tipo_impresion option:selected").text();
  console.log(tipo_impresiona);
  let fecha_fact = $("#fecha_fact").val();
  let direccion = $("#dircli").val();

  let id_factura = $("#id_factura").val();
  if (tipo_impresion == "TIK") {
    numero_factura_consumidor = '';
  } else {
    let numero_factura_consumidor = $("#numdoc").val();
  }
  let dataString = 'process=' + print + '&numero_doc=' + numero_doc + '&tipo_impresion=' + tipo_impresion + '&num_doc_fact=' + id_factura + '&numero_factura_consumidor=' + numero_factura_consumidor + '&fecha_fact=' + fecha_fact;
  nombreape = $("#nomcli").val();
  if (tipo_impresion == "CCF" ||tipo_impresion == "COF" ) {
    nit = $("#nitcli").val();
    nrc = $("#nrccli").val();
    //$('.modal-body #nombreape').val();
    dataString += '&nit=' + nit + '&nrc=' + nrc;
  }
  dataString += "&direccion=" + direccion + '&nombreape=' + nombreape;

  if (imprimiendo == 0) {
    $.ajax({
      type: 'POST',
      url: urlprocess,
      data: dataString,
      dataType: 'json',
      success: function(datos) {
        let sist_ope = datos.sist_ope;
        let dir_print = datos.dir_print;
        let shared_printer_win = datos.shared_printer_win;
        let shared_printer_pos = datos.shared_printer_pos;
        let headers = datos.headers;
        let footers = datos.footers;
        let efectivo_fin = parseFloat($('#efectivov').val());
        let cambio_fin = parseFloat($('#cambiov').val());
        //esta opcion es para generar recibo en  printer local y validar si es win o linux
        if (tipo_impresion == 'COF') {
          if (sist_ope == 'win') {
            $.post("http://" + dir_print + "printfactwin1.php", {
              datosventa: datos.facturar,
              efectivo: efectivo_fin,
              cambio: cambio_fin,
              shared_printer_win: shared_printer_win
            })
          } else {
            $.post("http://" + dir_print + "printfact1.php", {
              datosventa: datos.facturar,
              efectivo: efectivo_fin,
              cambio: cambio_fin
            }, function(data, status) {

              if (status != 'success') {

              }
            });
          }
        }
        if (tipo_impresion == 'ENV') {
          if (sist_ope == 'win') {
            $.post("http://" + dir_print + "printenvwin1.php", {
              datosventa: datos.facturar,
              efectivo: efectivo_fin,
              cambio: cambio_fin,
              shared_printer_win: shared_printer_win
            })
          } else {
            $.post("http://" + dir_print + "printenv1.php", {
              datosventa: datos.facturar,
              efectivo: efectivo_fin,
              cambio: cambio_fin
            }, function(data, status) {

              if (status != 'success') {

              }

            });
          }
        }
        if (tipo_impresiona == 'TICKET') {
          if (sist_ope == 'win') {
            $.post("http://" + dir_print + "printposwin1.php", {
              datosventa: datos.facturar,
              efectivo: efectivo_fin,
              cambio: cambio_fin,
              shared_printer_pos: shared_printer_pos,
              headers: headers,
              footers: footers,
            })
          } else {
            $.post("http://" + dir_print + "printpos1.php", {
              datosventa: datos.facturar,
              efectivo: efectivo_fin,
              cambio: cambio_fin,
              headers: headers,
              footers: footers,
            }, function(data, status) {

              if (status != 'success') {

              }

            });
          }
        }
        if (tipo_impresion == 'CCF') {
          if (sist_ope == 'win') {
            $.post("http://" + dir_print + "printcfwin1.php", {
              datosventa: datos.facturar,
              efectivo: efectivo_fin,
              cambio: cambio_fin,
              shared_printer_win: shared_printer_win
            })
          } else {
            $.post("http://" + dir_print + "printcf1.php", {
              datosventa: datos.facturar,
              efectivo: efectivo_fin,
              cambio: cambio_fin
            }, function(data, status) {

              if (status != 'success') {

              }

            });
          }
        }

      }
    });
  }
}

function reload1() {
  location.href = urlprocess;
}


$(document).on("click", "#btnAddClient", function(event) {
  agregarcliente();
});

function agregarcliente() {
  let nombress = $(".modal-body #nombress").val();
  let duii = $(".modal-body #duii").val();
  let tel1 = $(".modal-body #tel1").val();
  let tel2 = $(".modal-body #tel2").val();
  let dataString = 'process=agregar_cliente' + '&nombress=' + nombress;
  dataString += '&dui=' + duii + '&tel1=' + tel1 + '&tel2=' + tel2;
  $.ajax({
    type: "POST",
    url: urlprocess,
    data: dataString,
    dataType: 'json',
    success: function(datax) {
      let process = datax.process;
      let id_client = datax.id_client;
      // Agragar datos a select2
      //let nombreape = nombress + " " + apellidoss;
      $("#id_cliente").append("<option value='" + id_client + "' selected>" + nombress + "</option>");
      $("#id_cliente").trigger('change');

      //Cerrar Modal
      $('#clienteModal').modal('hide');
      //Agregar NRC y NIT al form de Credito Fiscal
      display_notify(datax.typeinfo, datax.msg);

    }
  });
}
$(document).on("click", "#btnEsc2", function(event) {
  $('#clienteModal').modal('hide');
  //reload1();
});
$(document).on('change', '#tipo_impresion', function(event) {
  $('#inventable tr').each(function(index) {
    let tr = $(this);
    actualiza_subtotal(tr);
  });
});


function addProductList(id_proda, tip)
{
  $(".select2-dropdown").hide();
  $('#inventable').find('tr#filainicial').remove();
  id_proda = $.trim(id_proda);
  id_factura = parseInt($('#id_factura').val());
  if (isNaN(id_factura))
	{
    id_factura = 0;
  }
  let precio_aut = $("#precio_aut").val();
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
        if (decimals==1) {
          categoria=86;
        }
	      let preciop_s_iva = parseFloat(data.preciop_s_iva);
	      let tipo_impresion = $('#tipo_impresion').val();
	      let filas = parseInt($("#filas").val());
        filas++;
	      let es_exento = "<input type='hidden' id='exento' name='exento' value='" + exento + "'>";
        let btnView = '<a data-toggle="modal" href="ver_imagen.php?id_producto='+id_prod+'"  data-target="#viewProd" data-refresh="true" class="btn btn-primary btnViw fa"><i class="fa fa-eye"></i></a>';
	      let subtotal = subt(data.preciop, 1);
	      subt_mostrar = subtotal.toFixed(2);
	      let cantidades = "<td class='cell100 column10 text-success'><input type='text'  class='form-control decimal2 " + categoria + " cant' id='cant' name='cant' value='' style='width:60px;'></td>";
        tr_add = '';
	      tr_add += "<tr  class='row100 head' id='" + filas + "'>";
	      tr_add += "<td hidden class='cell100 column10 text-success id_pps'><input type='hidden' id='unidades' name='unidades' value='" + data.unidadp + "'>" + id_prod + "</td>";
	      tr_add += "<td class='cell100 column30 text-success'>" + descrip_only + es_exento + '</td>';
	      tr_add += "<td class='cell100 column10 text-success' id='cant_stock'>" + existencias + "</td>";
	      tr_add += cantidades;
	      tr_add += "<td class='cell100 column10 text-success preccs'>" + data.select + "</td>";
	      tr_add += "<td hidden class='cell100 column10 text-success descp'><input type'text' id='dsd' class='form-control' value='" + data.descripcionp + "' class='txt_box' readonly></td>";
	      tr_add += "<td class='cell100 column10 text-success rank_s'>" + data.select_rank + "</td>";
	      tr_add += "<td class='cell100 column10 text-success'><input type='hidden'  id='precio_venta_inicial' name='precio_venta_inicial' value='" + data.preciop + "'><input type='hidden'  id='precio_sin_iva' name='precio_sin_iva' value='" + preciop_s_iva +
         "'><input type='text'  class='form-control decimal' id='precio_venta' name='precio_venta' value='" + data.preciop + "'></td>";
	      if (tipo_impresion == "CCF") {
	        tr_add += "<td class='ccell100 column10'>" + "<input type='hidden'  id='subtotal_fin' name='subtotal_fin' value='" + "0.00" + "'>" + "<input type='text'  class='decimal txt_box form-control' id='subtotal_mostrar' name='subtotal_mostrar'  value='" + "0.00" + "'readOnly></td>";
	      } else {
	        tr_add += "<td class='ccell100 column10'>" + "<input type='hidden'  id='subtotal_fin' name='subtotal_fin' value='" + "0.00" + "'>" + "<input type='text'  class='decimal txt_box form-control' id='subtotal_mostrar' name='subtotal_mostrar'  value='" + "0.00" + "'readOnly></td>";
	      }
        tr_add += "<td hidden class='cell100 column10 text-success id_pps'><input type='hidden' id='subt_bonifica' name='subt_bonifica' value='0'></td>";
	      tr_add += '<td class="cell100 column10 Delete text-center"><input id="delprod" type="button" class="btn btn-danger fa"  value="&#xf1f8;">&nbsp;'+ btnView+' </td>';
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
$(document).on('keyup', '.cant', function(evt) {
  let tr = $(this).parents("tr");

  if (evt.keyCode == 13) {
    num = parseFloat($(this).val());
    if (isNaN(num)) {
      num = 0;
    }
    if ($(this).val() != "" && num > 0) {
      tr.find('.sel').select2("open");
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
  //console.log(a_cant);
  //console.log(id_producto);
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
$(document).on('select2:close', '.sel_r', function() {
  let tr = $(this).parents("tr");
  console.log(tr);
  tr.find("#precio_venta").focus();
});

$(document).on('keyup', '#precio_venta', function(event) {

  if (event.keyCode==13) {
    if ($('#b').attr('hidden')) {
      $('#codigo').focus();
    } else {
      $('#producto_buscar').focus();
    }
  }
});




$(document).on('select2:close', '.sel', function(event) {
  let tr = $(this).parents("tr");
  let cantid = tr.find("#cant").val();
  let id_presentacion = $(this).val();
  let a = $(this);
  precio_aut = $("#precio_aut").val();
  //console.log(id_presentacion);
  $.ajax({
    url: urlprocess,
    type: 'POST',
    dataType: 'json',
    data: 'process=getpresentacion' + "&id_presentacion=" + id_presentacion + "&cant=" + cantid + "&precio_aut=" + precio_aut,
    success: function(data) {
      a.closest('tr').find('.descp').html(data.descripcion);
      a.closest('tr').find('#precio_venta').val(data.precio);
      a.closest('tr').find('#unidades').val(data.unidad);
      a.closest('tr').find('#precio_sin_iva').val(data.preciop_s_iva);
      a.closest('tr').find(".rank_s").html(data.select_rank);
      fila = a.closest('tr');
      id_producto = fila.find('.id_pps').text();
      existencia = parseFloat(fila.find('#cant_stock').text());
      existencia = round(existencia, 4);
      a_cant = parseFloat(fila.find('#cant').val());
      unidad = parseInt(fila.find('#unidades').val());
      a_cant = parseFloat(a_cant * data.unidad);
      a_cant = round(a_cant, 4);
      $(".sel_r").select2();
      a.closest('tr').find('.sel_r').select2("open");
      //console.log(a_cant);
      //console.log(id_producto);
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
      //console.log(existencia);
      //console.log(a_asignar);

      if (a_asignar > existencia) {
        val = existencia - (a_asignar - a_cant);
        val = val / unidad;
        val = Math.trunc(val);
        val = parseInt(val);
        fila.find('#cant').val(val);
      }

      let tr = a.closest('tr');
      actualiza_subtotal(tr);
    }
  });
  setTimeout(function() {
    setTotals();
  }, 200);


});

$(document).on('change', '.sel_r', function(event) {
  let a = $(this).closest('tr');
  precio = parseFloat($(this).val());
  a.find('#precio_venta').val(precio);
  a.find("#precio_sin_iva").val(precio / 1.13);
  actualiza_subtotal(a);
});

$(function() {
  //binding event click for button in modal form
  $(document).on("click", "#btnIngreso", function(event) {
    agregar_ingreso();
  });
  $(document).on("click", "#btnSalida", function(event) {
    agregar_salida();
  });
});


function agregar_ingreso() {
  let id_empleado = $("#id_empleado2").val();
  let id_apertura = $("#id_apertura2").val();
  let id_tipo = $("#tipo2").val();
  let turno = $("#turno2").val();
  let monto = $("#monto2").val();
  let concepto = $("#concepto2").val();

  let datos = "process=ingreso" + "&id_apertura=" + id_apertura + "&id_empleado=" + id_empleado + "&turno=" + turno + "&monto=" + monto + "&concepto=" + concepto + "&id_tipo=" + id_tipo;

  if (sending==0) {
    sending=1;
    $.ajax({
      type: "POST",
      url: "agregar_ingreso_caja.php",
      data: datos,
      dataType: 'json',
      success: function(datax) {
        display_notify(datax.typeinfo, datax.msg);
        if (datax.typeinfo == "Success") {
          imprimir_vale(datax.id_mov);
          setInterval("location.reload();", 1000);
          $('#viewModal').hide();
        }
      }
    });
  }
  else {
    console.log("Ya se estan enviando datos");
  }
}

function agregar_salida() {
  let id_empleado = $("#id_empleado2").val();
  let id_apertura = $("#id_apertura2").val();
  let turno = $("#turno2").val();
  let monto = $("#monto2").val();
  let concepto = $("#concepto2").val();
  let tipo_doc = $("#tipo_doc2").val();
  let n_doc = $("#n_doc2").val();
  let recibe = $("#recibe2").val();
  let proveedor = $("#proveedor2").val();
  let id_tipo = $("#tipo2").val();
  let datos = "process=salida" + "&id_apertura=" + id_apertura + "&id_empleado=" + id_empleado + "&turno=" + turno + "&monto=" + monto + "&concepto=" + concepto + "&proveedor=" + proveedor + "&tipo_doc=" + tipo_doc + "&n_doc=" + n_doc + "&recibe=" + recibe + "&id_tipo=" + id_tipo;

  if (sending==0) {
    sending=1;
    $.ajax({
      type: "POST",
      url: "agregar_salida_caja.php",
      data: datos,
      dataType: 'json',
      success: function(datax) {
        display_notify(datax.typeinfo, datax.msg);
        if (datax.typeinfo == "Success") {
          imprimir_vale(datax.id_mov);
          setInterval("location.reload();", 1000);
          $('#salidaModal').hide();
        }
      }
    });
  }
}

function imprimir_vale(id_movimiento) {
  let datoss = "process=imprimir" + "&id_movimiento=" + id_movimiento;
  $.ajax({
    type: "POST",
    url: "agregar_ingreso_caja.php",
    data: datoss,
    dataType: 'json',
    success: function(datos) {
      let sist_ope = datos.sist_ope;
      let dir_print = datos.dir_print;
      let shared_printer_win = datos.shared_printer_win;
      let shared_printer_pos = datos.shared_printer_pos;

      if (sist_ope == 'win') {
        $.post("http://" + dir_print + "printvalewin1.php", {
          datosvale: datos.movimiento,
          shared_printer_win: shared_printer_win,
          shared_printer_pos: shared_printer_pos,
        })
      } else {
        $.post("http://" + dir_print + "printvale1.php", {
          datosvale: datos.movimiento
        });
      }

    }
  });
}

$(document).on('keyup', '.external', function(event) {

  let q = $(this).val();

  if (q.length > 2) {
    $.ajax({
      url: 'http://farmacialafe.ddns.net/sistema/consulta.php',
      type: 'POST',
      dataType: 'json',
      data: {
        hash: 'd681824931f81f6578e63fd7e35095af',
        q: q
      },
      success: function(datax) {
        $('.extern').html(datax.data);
      }
    })
  }


});
function abrir_caja()
{
	$.post("http://localhost/impresion/abrir.php", {datosventa: ""});
}


$(document).on('keydown', '#aut_code', function(event) {
  if (event.keyCode == 13) {
    a= $(this);
    $(this).attr('disabled', 'disabled');
    $.ajax({
      url: urlprocess,
      type: 'POST',
      dataType: 'json',
      data: {
        process: "getcode",
        clave: $(this).val(),
      },
      success: function (xdatos) {

        if (xdatos.typeinfo=='Success') {
          display_notify(xdatos.typeinfo, xdatos.msg);
          $('#precio_aut').val(xdatos.precio);
          $('#clave').val(xdatos.clave);
          aplicar();
        }
        else {
          display_notify(xdatos.typeinfo, xdatos.msg);
          a.removeAttr('disabled');
        }

      }
    })

  }
});


function aplicar() {

  $('#inventable tr').each(function(index, el) {

      let tr = $(this);
      let cantid = tr.find("#cant").val();
      let id_presentacion = $(this).find(".sel").val();
      let a = $(this).find(".sel");
      precio_aut = $("#precio_aut").val();
      //console.log(id_presentacion);
      $.ajax({
        url: urlprocess,
        type: 'POST',
        dataType: 'json',
        data: 'process=tabla' + "&id_presentacion=" + id_presentacion + "&cant=" + cantid + "&precio_aut=" + precio_aut,
        success: function(data) {
          a.closest('tr').find('.descp').html(data.descripcion);
          a.closest('tr').find('#precio_venta').val(data.precio);
          a.closest('tr').find('#unidades').val(data.unidad);
          a.closest('tr').find('#precio_sin_iva').val(data.preciop_s_iva);
          a.closest('tr').find(".rank_s").html(data.select_rank);
          fila = a.closest('tr');
          id_producto = fila.find('.id_pps').text();
          existencia = parseFloat(fila.find('#cant_stock').text());
          existencia = round(existencia, 4);
          a_cant = parseFloat(fila.find('#cant').val());
          unidad = parseInt(fila.find('#unidades').val());
          a_cant = parseFloat(a_cant * data.unidad);
          a_cant = round(a_cant, 4);
          $(".sel_r").select2();
          //console.log(a_cant);
          //console.log(id_producto);
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
          //console.log(existencia);
          //console.log(a_asignar);

          if (a_asignar > existencia) {
            val = existencia - (a_asignar - a_cant);
            val = val / unidad;
            val = Math.trunc(val);
            val = parseInt(val);
            fila.find('#cant').val(val);
          }
          let tr = a.closest('tr');
          actualiza_subtotal(tr);
        }
      });
      setTimeout(function() {
        setTotals();
      }, 200);
  });
};
//bonificaciones
$(document).on('keyup', '#bonificacion', function() {
  fila = $(this).parents('tr');
  id_producto = fila.find('.id_pps').text();
  existencia = parseFloat(fila.find('#cant_stock').text());
  existencia = round(existencia, 4);
  a_cant = $(this).val();
  unidad = parseInt(fila.find('#unidades').val());
  a_cant = parseFloat(a_cant * unidad);
  a_cant = round(a_cant, 4);

  a_asignar = 0;

  $('#inventable tr').each(function(index) {

    if ($(this).find('.id_pps').text() == id_producto) {
      t_cant = parseFloat($(this).find('#bonificacion').val());
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

  if (a_asignar > existencia) {
    val = existencia - (a_asignar - a_cant);
    val = val / unidad;
    val = Math.trunc(val);
    val = parseInt(val);
    $(this).val(val);
    setTimeout(function() {
      setTotals();
    }, 1000);
  } else {
    setTotals();
  }
  let tr = $(this).parents("tr");

  id_presentacion_p = fila.find('.sel').val();

  setTimeout(function() {
    actualiza_subtotal(tr);
  }, 300);
});

$(document).on('change', '#id_cliente', function() {
  let id_cliente = $(this).val();
  mostrar_cliente(id_cliente);
});
$(document).on('change', '#con_pago', function() {
  let con_pago = $(this).val();
  let msg_con_pago="  ";
  let id_cliente = $("#id_cliente").val();
  if(con_pago=='CON' )
  {
    msg_con_pago+=" CONTADO ";
    msgPago(msg_con_pago)
  }
  if( con_pago=='TAR')
  {
    msg_con_pago+=" TARJETA ";
    msgPago(msg_con_pago)
  }
  if(con_pago=='CRE')
  {
     mostrar_cliente(id_cliente);
  }

});
let msgPago=(msg_con_pago)=>{
  $("#limite_credito").val(msg_con_pago);
  $("#saldo_disponible").val(msg_con_pago);
  $("#saldo_pendiente").val(msg_con_pago);
  $("#disponible_fac").val(msg_con_pago);
}

function mostrar_cliente(id_cliente){
  $.ajax({
    url: urlprocess,
    type: 'POST',
    dataType: 'json',
    data: {
      process: "mostrar_cliente",
      id_cliente: id_cliente
    },
    success: function(d) {
      $("#nomcli").val(d.nombre);
      $("#dircli").val(d.direccion);
      $("#nitcli").val(d.nit);
      $("#nrccli").val(d.nrc);
      $("#nrccli").attr('readOnly', false);
      $("#nitcli").attr('readOnly', false);
      $("#porc_retencion1").val(d.retiene);
      $("#porc_retencion10").val(d.retiene10);
      $("#id_vendor").val(d.id_vendedor);
      $("#vendedor").val(d.id_vendedor).trigger('change');
      $("#id_vendedor").val(d.id_vendedor);
      $("#nombreVendedor").html(d.nombreVendedor);
      //validar que tenga valores en creditos y disponiblidad de  credito
      //let con_pago = $("#con_pago").val();
      let msg_con_pago=" ";
      /*
      if(con_pago!='CRE' )
      {
        $("#limite_credito").val(msg_con_pago);
        $("#saldo_disponible").val(msg_con_pago);
        $("#saldo_pendiente").val(msg_con_pago);
      }
      else{*/
        limite_credito =d.limite_credito;
        if (isNaN(limite_credito) || limite_credito == "") {
          limite_credito =  0;
        }
        else {
          limite_credito =round(limite_credito,2);
        }
          saldo_disponible = d.saldo_disponible;
        if (isNaN(d.saldo_disponible) || d.saldo_disponible == "") {
          saldo_disponible =0;
        }
        else {
          saldo_disponible =round(parseFloat(saldo_disponible),2);
        }
        saldo_pendiente = d.saldo_pendiente;
        if (isNaN(d.saldo_pendiente) || d.saldo_pendiente == "") {
            saldo_pendiente = 0;
        }
        else {
          saldo_pendiente =round(saldo_pendiente,2);
        }
        $("#limite_credito").val(limite_credito);
        $("#saldo_disponible").val(saldo_disponible);
        $("#saldo_pendiente").val(saldo_pendiente);
        //Asignar tipo de documento para impresion CCF o COF
        $("#tipo_impresion").val(d.tipo_impresion);
        $("#tipo_impresion").val(d.tipo_impresion).trigger('change');
     //}
      setTotals();
    }
  });
}
function reload_location(){
  setTimeout(function() {
    location.href = urlprocess;
  }, 1000);
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
//Recalculo de totales optimizado
/*let setTotals=async()=>{
    //valores base calculos
    let iva = $('#porc_iva').val();
    let porc_percepcion = $("#porc_percepcion").val();
    let porc_reten1 =$("#porc_retencion1").val();
    porc_reten1 =isNaN(porc_reten1) ? 0 : parseFloat(porc_reten1)/100;
    let porc_reten10 =$("#porc_retencion10").val();
    porc_reten10 =isNaN(porc_reten10) ? 0 : parseFloat(porc_reten10)/100;

    let monto_retencion1 = parseFloat($('#monto_retencion1').val());
    let monto_retencion10 = parseFloat($('#monto_retencion10').val());
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

    //  getTotalTexto(total_final)
    if (tipo_impresion == "CCF") {
      total_iva = round((total_gravado * iva),4);
      console.log("IVA="+total_iva)
    }
    if (total_gravado >= monto_retencion1)
      total_retencion1 = total_gravado * porc_reten1;
        console.log("retencion1="+total_retencion1)
    if (total_gravado >= monto_retencion10)
      total_retencion10 = total_gravado * porc_reten10;
        console.log("retencion10="+total_retencion10)
    let total_gravado_iva = total_gravado + total_iva;
    let total_final = total_ge + total_percepcion + total_iva
        total_final += - (total_retencion1 + total_retencion10)
    total_final=round(total_final,4)
    //letiables para mostrar valores y asignacion a elementos html de valores
    let totcant_m = totalcantidad.toFixed(2)
    console.log("total cantidad="+totcant_m)

    let total_gravado_m = total_gravado.toFixed(2);
    let total_gravado_iva_m = total_gravado_iva.toFixed(2);
    let total_retencion1_m  = total_retencion1.toFixed(2);
    let total_retencion10_m = total_retencion10.toFixed(2);
    let total_final_m = total_final.toFixed(2);

    $('#totcant').text(totcant_m);
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
    $('#totaltexto').load(urlprocess, {
     'process': 'total_texto',
     'total': total_final_m
    });
    //total final
    $('#total_final').html(total_final_m);
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
        disponible_fac = round(parseFloat(saldo_disponible)- total_final,2);
        disponible_fac = isNaN(disponible_fac) ? 0: disponible_fac ==""? 0: parseFloat(disponible_fac);
        $('#disponible_fac').val(disponible_fac.toFixed(2));
    //}
}*/

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

    //  getTotalTexto(total_final)
    if (tipo_impresion == "CCF") {
      total_iva = round((total_gravado * iva),4);
      console.log("IVA="+total_iva)
    }
    if(cliente_retiene==1){
      if (total_gravado >= monto_retencion1)
        total_retencion1 = total_gravado * porc_reten1;
      console.log("retencion1="+total_retencion1)
    }
    if(cliente_retiene==10){
      if (total_gravado >= monto_retencion10)
        total_retencion10 = total_gravado * porc_reten10;
      console.log("retencion10="+total_retencion10)
    }

    let total_gravado_iva = total_gravado + total_iva;
    let total_final = total_ge + total_percepcion + total_iva
        total_final += - (total_retencion1 + total_retencion10)

    total_final=round(total_final,4)
    //letiables para mostrar valores y asignacion a elementos html de valores
    let totcant_m = totalcantidad.toFixed(2)
    console.log("total cantidad="+totcant_m)

    let total_gravado_m = total_gravado.toFixed(2);
    let total_gravado_iva_m = total_gravado_iva.toFixed(2);
    let total_retencion1_m  = total_retencion1.toFixed(2);
    let total_retencion10_m = total_retencion10.toFixed(2);
    let total_final_m = total_final.toFixed(2);

    $('#totcant').text(totcant_m);
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
    $('#totaltexto').load(urlprocess, {
     'process': 'total_texto',
     'total': total_final_m
    });
    //total final
    $('#total_final').html(total_final_m);
    $('#totalfactura').val(total_final_m);
    $('#monto_pago').html(total_final_m);
    $("#total_monto_final").val(total_final_m);
    //si es contado no valido el limite
    let con_pago = $("#con_pago").val();
    let msg_con_pago="";
    /*if(con_pago!='CRE'){
        $('#disponible_fac').val(msg_con_pago);
    }
    else{*/
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
    //}
}
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
  let tipo_impresion = $('#tipo_impresion').val();
  let fecha_movimiento = $("#fecha").val();
  let extra_nombre =$("#extra_nombre").val();
  let disponible_fac = $('#disponible_fac').val();
  let totcant = $('#totcant').text();

  if (fecha_movimiento == '' || fecha_movimiento == undefined) {
    let typeinfo = 'Warning';
    msg = 'Seleccione una Fecha!';
  }
  let tableData    = storeTblValue();
  //let json_imp_arr = totalImpuestoGas();
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
  dataString +=  '&totcant='+totcant;
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
  //array_Cotiza

  let jsonCotiza = JSON.stringify(array_Cotiza);
  dataString +="&jsonCotiza="+jsonCotiza
  if(error==false){

    $.ajax({
      type: 'POST',
      url: urlprocess,
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
//actilet el focus...al levantar el modal y mostrar segun tipo pago
$(document).on('shown.bs.modal', function(e) {
});
//modal para pago , cambio e impresion de recibo
let activa_modalPago=async(tipo_impresion,datax)=>{
   let numdoc=datax.numdoc
   let id_cliente = $("#id_cliente option:selected").val();
   let id_factura = datax.id_factura
   let totalfinal=parseFloat(datax.total);
    $('#id_factura').val(datax.id_factura);
    $('#modalPago').modal({backdrop: 'static',keyboard: false});
    let facturado= totalfinal.toFixed(2);
    clienteMetodoPago(id_cliente,facturado,id_factura);
    addCredit()
    let textPrint= `Impresión : ${datax.nombrecaja}  `
    textPrint+=  `<span class="fa-2x  fa-solid fa-shop  text-success"></span>`
    $("#modalPago .modal-header .textmodalPrint").html(textPrint);
    $("#modalPago .modal-body  #facturado").val(facturado);
    $("#modalPago .modal-body  #nombrecliente").html( `<strong>${datax.cliente}</strong>`);
    $("#modalPago .modal-body  #primavalor").val(""+round(array_Cotiza.prima,2));
    $("#modalPago .modal-body  #saldovalor").val(""+round(array_Cotiza.saldo,2));
    $("#modalPago .modal-body #fact_num").html(numdoc);
    $("#modalPago .modal-body #descrip_pago").html(datax.descrip_pago);
    $('#modalPago modal-body #id_factt').val(datax.id_factura);
    let duration=800
    $({to:0}).animate({to:1}, duration, function() {
      $("#modalPago .modal-body #metodo_pago").focus()
      $('#modalPago #tableview').enableCellNavigation();

    });
}
$(document).on("keyup","#efectivo",function(e){
    if(e.keyCode !=13 || e.keyCode !=27 )
    {
      total_efectivo();
    }
    if(e.keyCode ==13 ){
      $(".modal-body #btnPrintFact").click();
      e.stopPropagation();
      e.preventDefault();
    }
    if(e.keyCode ==27)
    {
    $("#modalPago .modal-body #btnEsc").click();
    e.stopPropagation();
    e.preventDefault();
    }
});

$(document).on("click", "#modalPago .modal-body #btnPrintFact", function (event) {
	imprimev();

});


$(document).on("click", "#btnEsc", function (event) {
	reload1();
});

let imprimev=(id_fact=-1)=>{
  let error = false;
  let array_error = [];
  let   msg = "";

  let imprimiendo = parseInt($('#imprimiendo').val());
  let total =   $(".modal-body #facturado").val();
  $('#imprimiendo').val(1);
  let numero_doc = $("#numdoc").val();
  let print = 'imprimir_fact';
  let tipo_impresion = $("#tipo_impresion").val();
  let tipo_impresiona = $("#tipo_impresion option:selected").text();
  let fecha_fact = $("#fecha_fact").val();
  let direccion = $("#dircli").val();
  let id_factura = $("#id_factura").val();
  let cambio_fin   = 0
  let efectivo_fin = 0
  let tarjeta_fin  = 0
  let diferencia = 0
  let num_fact_cons = '';
  let transaccion =" "
  if (tipo_impresion!== "TIK") {
     num_fact_cons = $("#numdoc").val();
  }
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

  dataString +=  '&numero_doc=' + numero_doc
  dataString  += '&tipo_impresion='+tipo_impresion+'&num_doc_fact='+id_factura
  dataString += '&numero_factura_consumidor=' + num_fact_cons+'&fecha_fact='+fecha_fact;
  dataString += '&cambio='+cambio_fin+'&efectivo='+efectivo_fin
  dataString += '&transaccion='+transaccion
  dataString += '&tarjeta_fin='+tarjeta_fin
  nombreape = $("#nomcli").val();
  if (tipo_impresion == "CCF" ||tipo_impresion == "COF" ) {
    nit = $("#nitcli").val();
    nrc = $("#nrccli").val();
    dataString += '&nit=' + nit + '&nrc=' + nrc;
  }
  dataString += "&direccion=" + direccion + '&nombreape=' + nombreape;
  let tableData    = storeTablePagos();
  dataString += tableData;
  if ( error == false) {
    $.ajax({
      type: 'POST',
      url: urlprocess,
      data: dataString,
      dataType: 'json',
      success: function(datos) {
        let sist_ope = datos.sist_ope;
        let dir_print = datos.dir_print;
        let shared_printer_win = datos.shared_printer_win;
        let shared_printer_pos = datos.shared_printer_pos;
        //esta opcion es para generar recibo en  printer local y validar si es win o linux
        if (tipo_impresion == 'COF') {
          if (sist_ope == 'win') {
            $.post("http://" + dir_print + "printfactwin1.php", {
              datosventa: datos.facturar,
              efectivo: efectivo_fin,
              cambio: cambio_fin,
              shared_printer_win: shared_printer_win,
            })
          } else {
            $.post("http://" + dir_print + "printfact1.php", {
              datosventa: datos.facturar,
              efectivo: efectivo_fin,
              cambio: cambio_fin,
            });
          }
        }
        if (tipo_impresion == 'ENV') {
          if (sist_ope == 'win') {
            $.post("http://" + dir_print + "printenvwin1.php", {
              datosventa: datos.facturar,
              efectivo: efectivo_fin,
              cambio: cambio_fin,
              shared_printer_win: shared_printer_win,
            })
          } else {
            $.post("http://" + dir_print + "printenv1.php", {
              datosventa: datos.facturar,
              efectivo: efectivo_fin,
              cambio: cambio_fin,
            });
          }
        }
        if (tipo_impresion == 'TIK') {
          if (sist_ope == 'win') {
            $.post("http://" + dir_print + "printposwin1.php", {
              shared_printer_pos: shared_printer_pos,
              efectivo: efectivo_fin,
              cambio: cambio_fin,
              totales: datos.totales,
              total_letras: datos.total_letras,
              encabezado: datos.encabezado,
              cuerpo: datos.cuerpo,
              pie: datos.pie,
              img:datos.img,
            })
          } else {
            $.post("http://" + dir_print + "printik_pista.php", {
              efectivo: efectivo_fin,
              cambio: cambio_fin,
              totales: datos.totales,
              total_letras: datos.total_letras,
              encabezado: datos.encabezado,
              cuerpo: datos.cuerpo,
              pie: datos.pie,
              img:datos.img,
            });
          }
        }
        if (tipo_impresion == 'CCF') {
          if (sist_ope == 'win') {
            $.post("http://" + dir_print + "printcfwin1.php", {
              datosventa: datos.facturar,
              efectivo: efectivo_fin,
              cambio: cambio_fin,
              shared_printer_win: shared_printer_win
            })
          } else {
            $.post("http://" + dir_print + "printcf1.php", {
              datosventa: datos.facturar,
              efectivo: efectivo_fin,
              cambio: cambio_fin
            });
          }
        }
      }
    });
    $("#inventable tr").remove();
  }
  else {
   display_notify("Error", "En formulario: "+ array_error.join(",<br>"));
  }
}
//modal de pagos, pago en efectivo
let total_efectivo=()=>{
  let tipo_pago  = $('#modalPago .modal-body #metodo_pago').val();
  let totalfinal = parseFloat(array_Cotiza.totalfinal);
  totalfinal        = isNaN(totalfinal) ? 0: totalfinal ==""? 0: parseFloat(totalfinal);
	let facturado  = totalfinal.toFixed(2);
  let totall     =  $("#modalPago .modal-body #tot_fin").val()
  let diferencia = parseFloat($("#modalPago .modal-body #diferencia_").val())
  totall         = isNaN(totall) ? 0: totall ==""? 0: parseFloat(totall);
  let efectivo="";
  if  (tipo_pago =='CON'){
    efectivo=$('#modalPago .modal-body #efectivo').val()
    efectivo  = isNaN(efectivo) ? 0: efectivo ==""? 0: parseFloat(efectivo);

    let pendiente  = totalfinal - totall
     pendiente  =roundNumberV1(pendiente, 4)
    //diferencia = pendiente - valor
    if(diferencia>0){
      valor= pendiente
      $('#modalPago .modal-body #efectivo').val(valor);
    }
    let cambio=efectivo - pendiente;
    cambio=round(cambio, 2);

    let	cambio_mostrar=cambio.toFixed(2);
    $('#modalPago .modal-body #cambio').val(cambio_mostrar);

  }
}
//Agregar metodos d epago en modal
$(document).on("click", "#btnAddPay", function (event) {
	seleccionarPago();
});
$(document).on('change', '#modalPago .modal-body  #metodo_pago', function(event) {
  let tipo_pago = $('#modalPago .modal-body  #metodo_pago option:selected').val();
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
    $(".modal-body #valess").show();
    $(".modal-body #transf").hide();
    let nitcli = $('#nitcli').val();
    $("#modalPago .modal-body #nitt").val(nitcli)
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
    let facturado = parseFloat(array_Cotiza.totalfinal)
    let metodopago= $('#modalPago .modal-body  #metodo_pago option:selected').text();
    let data_extra= $("#modalPago .modal-body #data_extra").val()

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
  let facturado = parseFloat( array_Cotiza.totalfinal)
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
  let facturado = parseFloat(array_Cotiza.totalfinal)
  $("#modalPago .modal-body #pagos tr").each(function() {
    let subt= $(this).find("td:eq(2)").text();
    totall     += parseFloat(subt);
  });
  totall=round(totall,2)
  $("#modalPago .modal-body #tot_fin").val(""+totall)
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
  let total_facturado=$(".modal-body #facturado").val();
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

$(document).on("keyup","#modalPago .modal-body #efectivo",function(e){
	if(e.keyCode !=13 || e.keyCode !=27 )
	{
	  total_efectivo();
	}
  if(e.keyCode ==13 && $(this).val()!="" ){
    $("#modalPago .modal-body #btnAddPayment").focus();
    e.stopPropagation();
    e.preventDefault();
  }
    if(e.keyCode ==27)
  {
    $("#modalPago .modal-body #btnEsc").click();
    e.stopPropagation();
    e.preventDefault();
  }
});
//cambiar el foco segun metodo de pago en #modalPago
$(document).on('keydown', '#modalPago .modal-body #metodo_pago', function(e) {
  let id_mp = $(this).val()
  let tipo_pago = $('#modalPago .modal-body  #metodo_pago option:selected').val();
  if (e.which === 13 || e.keyCode==13) { //a
    $("#modalPago .modal-body .montoMetodoPago").focus()
  }
});
$(document).on('keydown', '#modalPago', function(e) {
  if (e.keyCode ==27 || e.which == 27) { //Esc salir
    $('#modalPago .modal-body #btnEsc').click()
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

let  validarNumdoc=async()=>{
  let docValido = true
  let numeroDocImpreso = $("#numdoc").val();
  let tipo_impresion = $("#tipo_impresion").val();
  if (tipo_impresion!='TIK'){
    let dataString = 'process=validarNumdoc'
    dataString+='&numeroDocImpreso='+numeroDocImpreso
    dataString+='&tipo_impresion='+tipo_impresion
    try {
        const response =await axios.post(urlprocess,dataString);
        console.log(response.data);

        docValido = response.data.valido;
        if(docValido ==false){
            display_notify(response.data.typeinfo, response.data.msg);
        }

    } catch (err) {
        console.error(err);
    }
}
 console.log("docValido:"+docValido)
 return docValido;

}

let start_docValida= async(tipo_impresion)=>{
    let valida = await validarNumdoc()
   let   vacio=1
    if (valida==false){
      $("#numdoc").focus()
      $('#submit1').prop( "disabled", false );
    }
    if($("#inventable tr").length==0){
      vacio=0
      msg = 'Debe agregar productos a la lista';
      display_notify("Error", msg);
        $('#submit1').prop( "disabled", false );
    }
    if(vacio==1 && valida==true){
      senddata();
      $('#submit1').prop( "disabled", true );
    }
}

//  clienteMetodoPago(id_cliente,facturado,id_factura)
let  clienteMetodoPago= async(id_cliente,facturado,id_factura)=>{
  $.ajax({
    url: urlprocess,
    type: 'POST',
    dataType: 'json',
    data: {
      process: "clienteMetodoPago",
      id_cliente: id_cliente,
      facturado:facturado,
      id_factura:id_factura,
    },
    success: function(d) {
      $('#modalPago .modal-body .metodos_pago').html(d.datos);
    }
  });
}
//Agregar metodos d epago en modal
$(document).on("click", "#btnAddPayment", function (event) {
	agregarPago();
  let tipo_impresion=  $('#modalPago .modal-body #docImpresion').val();
  let duration = 500;
  $({to:0}).animate({to:1}, duration, function() {
    if(tipo_impresion!='TIK'){
      $("#modalPago .modal-body #numeroDocImpreso").focus();
    }else{
      console.log("foco a  #btnPrintFact")
      $("#modalPago").find("#btnPrintFact").focus();
    }
  })
});
//cotizacion

$(document).on("click", "#btnCotiza", function() {
   activaModalCotiza()
});
//parametros de cuotas
async function getParamCuotas(){
    let params ='process=getParamCuotas'
    let url=`${urlprocess}?${params}`
    function getvals(){
    return fetch(url,
    {
      method: "POST",
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
    })
    .then((response) => {
     return response.json();
    })
    .then((responseJSON) => {
      return responseJSON;
    })
    .catch(error => console.warn(error));
  }
  array_Impuestos=await getvals().then((response) => response.detalle);
  return array_Impuestos
}

let activaModalCotiza=async()=>{
    let totalfinal =  $('#totalfactura').val();
    $('#modalCotiza').modal({backdrop: 'static',keyboard: false});
    $(".decimal").numeric({
     negative: false,
     decimalPlaces: 2
   });
    $("#modalCotiza .modal-body  #valorcredito").val(totalfinal);
    calCuota()
}
$(document).on(' keydown', '#modalCotiza .modal-body #efectivoprima', function(e) {
  if (e.keyCode == 13) {
    num = parseFloat($(this).val());
  num = isNaN(num ) ? 0: num  ==""? 0:round(num ,2);
    if ($(this).val() != "" && num > 0) {
     calCuota()
    }
  }
})
$(document).on(' keydown', '#modalCotiza .modal-body #porc_minimo', function(e) {
  if (e.keyCode == 13) {
    num = parseFloat($(this).val());
  num = isNaN(num ) ? 0: num  ==""? 0:round(num ,2);
    if ($(this).val() != "" && num > 0) {
     calCuota()
    }
  }
})
$(document).on('keydown', '#modalCotiza .modal-body #valorcredito', function(e) {
  if (e.keyCode == 13) {
    num = parseFloat($(this).val());
    num = isNaN(num ) ? 0: num  ==""? 0:round(num ,2);
    if ($(this).val() != "" && num > 0) {
     calCuota()
    }
  }
})
$(document).on('change', '#modalCotiza .modal-body #plazocuotas', function(e) {
     calCuota()
})

let calCuota=async(valorCotiza=0)=>{
  let fecha_credito = $("#modalCotiza .modal-body  #fecha_credito").val();
  let valor_contado = $("#modalCotiza .modal-body  #valorcredito").val();
   valor_contado = isNaN( valor_contado ) ? 0:  valor_contado ==""? 0:round( valor_contado ,2);
  let porc_min_prima  = 0
  let porc_min_int_mes = 0
  let efectivoprima =0
  $.each(array_Impuestos, function(i, item) {
      porc_min_prima  = round(item.porc_min_prima/100,4);
      porc_min_int_mes = round(item.porc_min_int_mes/100,4);

  })
  let porc_digitado = $("#modalCotiza .modal-body  #porc_minimo").val();
  porc_digitado = isNaN(porc_digitado ) ? 0: porc_digitado  =="" ? 0:round(porc_digitado/100 ,4)
  if ( porc_digitado > porc_min_int_mes){
    porc_min_int_mes =  porc_digitado
  }
  let numcuotas = $('#plazocuotas  option:selected').val(); //get the value
  efectivoprima = $("#modalCotiza .modal-body  #efectivoprima").val();
  efectivoprima = isNaN(efectivoprima ) ? 0:efectivoprima  ==""? 0:round(efectivoprima ,2);
  let prima = valor_contado *   porc_min_prima
  if(  efectivoprima<prima){
     efectivoprima =prima
  }
  let resta = round(valor_contado  - efectivoprima,2)
  let porc_total= round(porc_min_int_mes * numcuotas, 2)
  let interes =round( resta * porc_total, 2)
  let saldo = round(resta + interes,2)
  let monto_cuota = round((saldo / numcuotas),2)
  let porc_mes_fin = round(porc_min_int_mes*100,2)
  let totalfinal=round(parseFloat(efectivoprima)+parseFloat(saldo),2)
  totalfinal= isNaN(totalfinal) ? 0: totalfinal ==""? 0:round(totalfinal,2);
  $("#modalCotiza .modal-body  #efectivoprima").val(""+efectivoprima);
  $("#modalCotiza .modal-body  #saldo").val(""+saldo);
  $("#modalCotiza .modal-body  #cuota").val(""+monto_cuota);
  $("#modalCotiza .modal-body  #porc_minimo").val("" + porc_mes_fin);

  let id_vendedor = $('#modalCotiza .modal-body #id_vendedor2  option:selected').val(); //get the value
  let comision_venta =  $("#modalCotiza .modal-body  #comision").val();
  //array_Cotiza.total_credito=total_credito2
  array_Cotiza = {
      fecha_credito   : fecha_credito,
      numcuotas       : numcuotas,
      prima           : efectivoprima,
      saldo           : saldo,
      monto_cuota     : monto_cuota,
      porc_mes_fin    : porc_mes_fin,
      porc_total      : porc_total,
      valor_contado   : valor_contado,
      totalfinal      : totalfinal,
      id_vendedor     : id_vendedor,
      comision_venta  : comision_venta,
  }
}
$(document).on("click", "#modalCotiza .modal-body #btnCalCotiza", function() {
  calCuota()
  mostrarCotiza()
  $('#modalCotiza').modal('hide');
});

let mostrarCotiza=async()=>{
  let fecha_credito2=array_Cotiza.fecha_credito
  let prima2= array_Cotiza.prima
  let saldo2 = array_Cotiza.saldo
  let total_credito2=round(parseFloat(array_Cotiza.totalfinal),2)
  let cuotames2 = array_Cotiza.monto_cuota
  let nmeses2 = array_Cotiza.numcuotas
  let fecha_sep = fecha_credito2.split("-");
  let dia =fecha_sep[2]
  let date = new Date(fecha_credito2);
  let ult_mes = date.getMonth()+nmeses2;
  let ff= moment(fecha_credito2, "YYYY-MM-DD").add(nmeses2, 'months').calendar();
  let fini =moment(fecha_credito2).format('DD-MM-YYYY')
  let ffin =moment(ff).format('DD-MM-YYYY')
  console.log(ff)
  //let diavence2
  $("#fecha_credito2").val(""+fecha_credito2);
  $("#prima2").val(""+prima2);
  $("#saldo2").val(""+saldo2);
  $("#total_credito2").val(""+total_credito2);
  $("#cuotames2").val(""+cuotames2);
  $("#nmeses2").val(""+ nmeses2);
  $("#diavence2").val(""+dia+" DE CADA MES ");
  $(".msjfecha").html("DESDE:"+fini+" HASTA: "+ffin);
}
//Agregar monto que va al credito a la tabla de pagos
let addCredit=()=>{
    let valor = array_Cotiza.saldo
    let diferencia=0
    valor    = isNaN(valor) ? 0:valor ==""? 0: parseFloat(valor);
    let datos_extr={
       cuotas : array_Cotiza.numcuotas,
       valor_cuotas : array_Cotiza.monto_cuota,
    }
    let datos_extra = JSON.stringify(datos_extr);

    let btnDel  = ' '

    let totall=   array_Cotiza.saldo
    totall     = isNaN(totall) ? 0: totall ==""? 0: parseFloat(totall);
    let facturado = parseFloat(array_Cotiza.totalfinal)
    let metodopago= "CREDITO";
    let txt =`número cuotas: ${array_Cotiza.numcuotas}<br>
          valor cuotas $: ${array_Cotiza.monto_cuota}`


    let input_pago = "<input type='hidden' name='alias_pago' id='alias_pago'"
    input_pago    += "value='CRE'>"
    let input_extra = "<input type='hidden' name='datos_extra' id='datos_extra'"
    input_extra    += "value='"+datos_extra+"'>"
    tr_add = "";
    tr_add += "<tr  class='pagado'>";
    tr_add += "<td class='descripcion col-lg-3'>" + metodopago + "</td>";
    tr_add += "<td class='informacion col-lg-5'>" +input_pago+input_extra+ txt+ "</td>";
    tr_add += "<td class='valores col-lg-3 text-right'>" +valor+ "</td>";
    tr_add += "<td class='DeletePay text-center col-lg-1'>" +btnDel+ "</td>";
    tr_add += "</tr>";
    $("#modalPago .modal-body #diferencia_").val("0")
     $("#modalPago .modal-body #tot_fin").val(array_Cotiza.totalfinal)
    //  if(totall<facturado && valor>0 ){
        $("#modalPago .modal-body #pagoss #pagos").prepend(tr_add);
        actualizaTablaPagos()

    //}

}