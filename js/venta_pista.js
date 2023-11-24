let urlprocess = 'venta_pista.php';
let sending = 0;
let array_Impuestos=[]

$(document).ready(function() {
  array_Impuestos= getImpComb();
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
  })
  $('html,body').animate({
    scrollTop: $(".focuss").offset().top
  }, 1000);
  let duration = 1500;
  $({to:0}).animate({to:1}, duration, function() {
      $("#producto_buscar").focus()
      //Activar modal al inicio
      $("#btnAddComb").click()
      //stopPropagation();

  });
  $(".select").select2({
    placeholder: {
      id: '',
      text: 'Seleccione',
    },
    allowClear: true,
  });
  $(".selectt").select2()
  $("#scrollable-dropdown-menu #producto_buscar").typeahead({
    highlight: true,
  }, {
    limit: 100,
    name: 'productos',
    display: 'producto',
    source: function show(q, cb, cba) {
      let url = 'autocomp_prod_pista.php' + "?query=" + q;
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
  $(".decimal").numeric({
    negative: false,
    decimalPlaces: 4
  });
  $(document).keydown(function(e) {
    if (e.which == 117) { //F6 Guardar Cobro
      $("#submit1").click();
      e.preventDefault()
    }
    if (e.which == 113) { //F2 activa  venta combustible
      $("#btnAddComb").click()
      e.stopPropagation();
    }
    if (e.which == 114) { //F3 activa  venta combustible al costo !
      $("#btnAddCombCosto").click()
      e.preventDefault();

    }
    if (e.which == 27) { //Tecla ESc para salir
      location.replace("dashboard.php");
      e.stopPropagation();
      e.preventDefault();
    }
    if (e.which == 115) { //F4  Modal Cliente
      e.stopPropagation();
      e.preventDefault();
      $('#btnAddClient').click();
    }
    if (e.which == 46) /*suprimir*/ {
      $("#inventable tr:first-child").remove();
      setTotals();
      let filas = $("#filas").val();
      filas --;
      $("#filas").val(filas);
      e.preventDefault();
    }
  });
  $('#form_fact_consumidor').hide();
  $('#form_fact_ccfiscal').hide();
  //Boton de imprimir deshabilitado hasta que se guarde la factura
  $('#print1').prop('disabled', true);
  $('#submit1').prop('disabled', false);
  getImpGas();



});

$(document).on("click", "#btnCloseView", function(event) {
  $('#viewProd').modal('hide');
});
$(document).on('hidden.bs.modal', function(e) {
  let target = $(e.target);
  target.removeData('bs.modal').find("#modalPago .modal-content").html('');
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
});

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

  ////(a_cant);
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
    getImpGas();
    setTotals();
  }, 300);

});

$(document).on("keyup", "#precio_venta", function() {
  tr = $(this).closest('tr');
  precio = parseFloat($(this).val());

  if (isNaN(precio)) {
    precio=0;
  }
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
  let iva             = parseFloat($('#porc_iva').val());
  let precio_sin_iva   = parseFloat(tr.find('#precio_sin_iva').val());
  let precio_venta_ini = parseFloat(tr.find('#precio_venta_inicial').val());
  let existencias = tr.find('#cant_stock').text();
  let tipo_impresion = $('#tipo_impresion').val();
  let  cantidad = parseFloat(tr.find('#cant').val());
  let aplica_dif     =  $("#aplica_dif").val();
  let producto_dif   = tr.find('#producto_dif').val();
  let precio_prod_dif=tr.find('#precio_prod_dif').val();
  let seldif         =  $("#seldif option:selected").val();
  let disponible_dif = $("#disponible_dif").val();
  disponible_dif = isNaN(disponible_dif) ? 0:disponible_dif ==""? 0:round(disponible_dif,4);
  let precio_final   = precio_venta_ini

  let descontar_impuesto = 0
  let subt_impuesto      = 0
  let impuesto           = 0
  let impuesto_dif       = 0
  $.each(array_Impuestos, function(i, item) {
    if(item.activo==1 ){
      impuesto+= roundNumberV1(1 * item.valor,4);
    }
    if(item.activo==1 && item.dif==1 ){
     impuesto_dif+= roundNumberV1(1 * item.valor,4);
    }
  })
  if (aplica_dif ==1 &&  seldif!=-1 && disponible_dif>=cantidad && producto_dif==1){
      precio_final   = precio_prod_dif
  }

  if (tipo_impresion == "CCF") {
    precio_final   =roundNumberV1(   ( precio_venta_ini - impuesto)  /(1+iva),4)
  }
  tr.find('#precio_venta').val(""+precio_final)
  let subtotal = roundNumberV1(cantidad*precio_final,4);
   subtotal        = isNaN( subtotal ) ? 0:  subtotal  ==""? 0: parseFloat( subtotal );
  let subt_mostrar = subtotal.toFixed(4);

  tr.find("#subtotal_fin").val(subt_mostrar);
  tr.find("#subtotal_mostrar").val(subt_mostrar);
  setTotals();

  let subt_bonifica = 0.0 ;
  tr.find("#subt_bonifica").val(subt_bonifica);
}
// actualize table data to server
$(document).on("click", "#submit1", function() {
  $('#submit1').attr('disabled', true);
  if($("#inventable tr").length>0)
  {
    senddata();
  }
  else {
    display_notify("Error", "Debe agregar productos a la lista");
    $('#submit1').attr('disabled', false);
  }
});


$(document).on("click", "#btnEsc", function(event) {
  reload1();
});

$(document).on("click", ".print1", function() {
  let totalfinal = parseFloat($('#totalfactura').val());
  let facturado = totalfinal.toFixed(2);
  $(".modal-header #facturado").val(facturado);
});

$(document).on("click", "#print2", function() {
  imprime2();
});




$(document).on("keyup","#efectivov",function(evt){
	if(evt.keyCode !=13)
	{
		total_efectivov();
	}
	else
	{
		if ($("#corr_in").val()!="") {
			if(parseFloat($("#cambiov").val()) >=0)
			{
				imprimev();
			}
			else {
				display_notify("Warning", "Ingrese un valor mayor o igual al total facturado");
			}
		}
		else
		{
			display_notify("Warning", "Debe finalizar una factura primero");
		}
	}
});

$(document).on("keyup", "#numdoc", function(evt) {
  if (evt.keyCode == 13) {
    if ($(this).val() != "") {
       $("#numdoc2").val($(this).val());
      	imprimev();
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


function total_efectivov() {
  let efectivo = parseFloat($('#efectivov').val());
  let totalfinal = parseFloat($('#tot_fdo').val());
  let facturado = totalfinal.toFixed(2);

  if (isNaN(parseFloat(efectivo))) {
    efectivo = 0;
  }
  if (isNaN(parseFloat(totalfinal))) {
    totalfinal = 0;
  }
  let cambio = efectivo - totalfinal;
  cambio = round(cambio, 2);
  let cambio_mostrar = cambio.toFixed(2);
  $('#cambiov').val(cambio_mostrar);
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
          })
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
function reload1() {
  location.href = 'venta_pista.php';
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
  precio_aut = $("#precio_aut").val();
  urlprocess = "venta_pista.php";
  let dataString = 'process=consultar_stock'+'&id_producto='+id_proda+'&id_factura='+id_factura+'&tipo='+tip+'&precio_aut='+precio_aut ;
  $.ajax({
    type: "POST",
    url: urlprocess,
    data: dataString,
    dataType: 'json',
    success: function(data){
			if(data.typeinfo == "Success"){
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
        let es_combustible = data.es_combustible;
        let decimals=data.decimals;
        let aplicar_dif = data.aplicar_dif
        if (decimals==1) {
          categoria=86;
        }
	      let preciop_s_iva = parseFloat(data.preciop_s_iva);
	      let tipo_impresion = $('#tipo_impresion').val();
	      let filas = parseInt($("#filas").val());
        filas++;
	      let exentus = "<input type='hidden' id='exento' name='exento' value='" + exento + "'>";
	      let subtotal = subt(data.preciop, 1);
        let img_prod= '<a data-toggle="modal" href="ver_imagen.php?id_producto='+id_prod+'"  data-target="#viewProd" data-refresh="true" class="btn btn-primary btnViw fa"><i class="fa fa-eye"></i></a>';
	      subt_mostrar = subtotal.toFixed(2);
	      let cantidades = "<td class='cell100 column10 text-success'><input type='text'  class='form-control decimal2 " + categoria + " cant' id='cant' name='cant' value='' style='width:60px;'></td>";
        let  bonificaciones = "<td class='cell100 column10 text-success'><input type='text'  class='form-control decimal2 " + categoria + " bonificacion' id='bonificacion' name='bonificacion' value='' style='width:60px;'></td>";
        let  combustible="<input type='hidden' id='combustible' name ='combustible' value='"+es_combustible +"'>";
        let  producto_dif="<input type='hidden' id='producto_dif' name ='producto_dif' value='"+aplicar_dif +"'>";
        let btnView = '<a data-toggle="modal" href="ver_imagen.php?id_producto='+id_prod+'"  data-target="#viewProd" data-refresh="true" class="btn btn-primary btnViw fa"><i class="fa fa-eye"></i></a>';
	      tr_add = '';
	      tr_add += "<tr  class='row100 head' id='" + filas + "'>";
	      tr_add += "<td hidden class='cell100 column10 text-success id_pps'><input type='hidden' id='unidades' name='unidades' value='" + data.unidadp + "'>" + id_prod + "</td>";
	      tr_add += "<td class='cell100 column30 text-success'>" + descrip_only + exentus + combustible + producto_dif +'</td>';
	      tr_add += "<td class='cell100 column10 text-success' id='cant_stock'>" + existencias + "</td>";
	      tr_add += cantidades;
        //tr_add += bonificaciones;
	      tr_add += "<td class='cell100 column10 text-success preccs'>" + data.select + "</td>";
	      tr_add += "<td hidden class='cell100 column10 text-success descp'><input type'text' id='dsd' class='form-control' value='" + data.descripcionp + "' class='txt_box' readonly></td>";
	      tr_add += "<td class='cell100 column10 text-success rank_s'>" + data.select_rank + "</td>";
	      tr_add += "<td class='cell100 column10 text-success'><input type='hidden'  id='precio_venta_inicial' name='precio_venta_inicial' value='" + data.preciop + "'><input type='hidden'  id='precio_sin_iva' name='precio_sin_iva' value='" + preciop_s_iva + "'><input type='text'  class='form-control decimal' id='precio_venta' name='precio_venta' value='" + data.preciop + "'></td>";
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
	        decimalPlaces: 4,
	      });
	      $(".86").numeric({
	        negative: false,
	        decimalPlaces: 4
	      });
	      $('#filas').val(filas);
	      $('#items').val(filas);
	      $(".sel").select2();
	      $(".sel_r").select2();
	      $('#inventable #' +filas).find("#cant").focus();
	      setTotals();
	      scrolltable();
        getImpGas()
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
  ////(a_cant);
  ////(id_producto);
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
  //("existencia:"+existencia);
  //("asignar:"+a_asignar);

  if (a_asignar > existencia) {
    val = existencia - (a_asignar - a_cant);
    val = val / unidad;
    val = Math.trunc(val);
    val = parseInt(val);
    fila.find('#cant').val(val);
  }

  actualiza_subtotal(tr);
  getImpGas()
});
$(document).on('select2:close', '.sel_r', function() {
  let tr = $(this).parents("tr");
  //(tr);
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
  ////(id_presentacion);
  $.ajax({
    url: 'venta_pista.php',
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
      ////(a_cant);
      ////(id_producto);
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
      ////(existencia);
      ////(a_asignar);

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
    //("Ya se estan enviando datos");
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
      url: 'venta_pista.php',
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
      ////(id_presentacion);
      $.ajax({
        url: 'venta_pista.php',
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
          ////(a_cant);
          ////(id_producto);
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
  start_getBoats(id_cliente);
});

let msgPago=(msg_con_pago)=>{
  $("#limite_credito").val(msg_con_pago);
  $("#saldo_disponible").val(msg_con_pago);
  $("#saldo_pendiente").val(msg_con_pago);
  $("#disponible_fac").val(msg_con_pago);
}

function mostrar_cliente(id_cliente){
  $.ajax({
    url: 'venta_pista.php',
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
      //$("#vendedor").val(d.id_vendedor).trigger('change');
      $("#id_vendedor").val(d.id_vendedor);
      $("#nombreVendedor").html(d.nombreVendedor);
      //validar que tenga valores en creditos y disponiblidad de  credito
      let con_pago = $("#con_pago").val();
      let msg_con_pago=" ";

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
        //$("#tipo_impresion").val(d.tipo_impresion);
        //$("#tipo_impresion").val(d.tipo_impresion).trigger('change');
    // }
      setTotals();
    }
  });
}
function reload_location(){
  setTimeout(function() {
    location.href = 'venta_pista.php';
  }, 1000);
}

async function getImpGas(){
    let 	ur='venta_pista.php?';
    let params ='process=getImpGas'
    let url=`${ur}${params}`

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
 let valor=await getvals().then((response) => response.detalle);
  calcImpGas(valor);
}
function calcImpGas(valor){
  let n=0
  let total_impuestos_comb =0
  let total_impuesto = 0
  let seldif       =  $("#seldif").val();
  let disponible_dif =  $("#disponible_dif").val();
  if(isNaN(disponible_dif) && disponible_dif=="")
     disponible_dif_tot = 0
  else
      disponible_dif_tot =parseFloat( disponible_dif)

  $.each(valor, function(i, item) {
    let totalcantidad      = 0
    let subt_impuesto      = 0
    let total_impuesto     = 0
    let descontar_impuesto = 0
    let id_imp =   $("#totals").find("#"+n).find("#id_imp").val()
    //aplica DIF
    let aplica_dif   =  $("#aplica_dif").val();
    $("#inventable tr").each(function() {
        let cant  = $(this).find("#cant").val();
        let stock = parseFloat($(this).find("#cant_stock").text());
        let producto_dif = $(this).find("#producto_dif").val();
        if(disponible_dif_tot<cant && seldif!=-1){
           cant = disponible_dif_tot
           if(cant>stock){
              cant = stock
           }
           $(this).find("#cant").val(cant);
        }
        cant =  isNaN(cant) ?  0:parseFloat(cant);
        let  es_combustible = $(this).find("#combustible").val();
        let aplica_dif = parseInt(item.dif)
        let name_imp = item.nombre
        //('total_impuestos de '+item.nombre+" aplica_dif:"+item.dif+" seldif:"+ seldif)
        if (es_combustible==1 && item.activo==1 && id_imp == item.id ){
            totalcantidad +=cant;
            subt_impuesto= cant * item.valor
            if (aplica_dif ==1 &&  seldif!=-1 && disponible_dif_tot>=cant && producto_dif==1){
              subt_impuesto  = 0
              descontar_impuesto = cant * item.valor;
              descontar_impuesto = round(descontar_impuesto,4);
              $("#descontar_impuesto").val(descontar_impuesto.toFixed(4));
            }
            subt_impuesto =  isNaN(subt_impuesto) ?  0:round(subt_impuesto,4);
            total_impuesto += parseFloat(subt_impuesto);
            total_impuesto =  isNaN(total_impuesto) ?  0:round(total_impuesto,4);
            $("#totals").find("#"+n).find("#total_impgas").text(total_impuesto);
            $("#totals").find("#"+n).find("#val_imp_gas").val(total_impuesto);
        }
    });
    total_impuestos_comb += total_impuesto
    n++
  });
  //('total_impuestos_comb:'+total_impuestos_comb);
   $("#tot_imp_gas").val(total_impuestos_comb);
   $("#total_impuestos_gas").val(total_impuestos_comb);
   setTotals()
}
//  mostrar embarcaciones con DIF Activo y con fecha vigente
let showBoats= async(id_cliente)=>{
  try{
        let 	ur='venta_pista.php?';
        let params ='process=getBoats&idcliente='+id_cliente
        let url=`${ur}${params}`
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
           await getvals().then((response) => {

              $.each(response.detalle, function(i, item) {
                  //("embarcaciones:"+item.embarcacion)
                  let descdif = `${item.embarcacion} / DIF: ${item.numero_dif}`
                  let newOption = new Option(descdif, item.id_dif, false, false);
                  $('#seldif').append(newOption);

              })
          });
  } catch(err) {
      display_notify("Error", "no se pueden mostrar Embarcaciones");
  }
}
let start_getBoats= async(id_cliente)=>{
  await showBoats(id_cliente);
}
//cargar valores mensuales DIF POR EMBARCACION Seleccionada
$(document).on('change', '#seldif', function(event) {
  //let id _dif= $("select#seldif option:selected").val(); //get the value
  let id_dif = $(this).val();
  let id_cliente = $("#id_cliente").val();
  if(id_dif!=-1 || id_dif!=null &&  id_cliente!=""){
      start_limitDIF(id_dif,id_cliente);
  }
  getImpGas();
  setTotals()
});
let start_limitDIF= async(id_dif,id_cliente)=>{
    await showLimitDIF(id_dif, id_cliente);
}
let showLimitDIF = async(id_dif,id_cliente)=>{
  if(id_dif!=-1 || id_dif!=null){
    try{
          let 	ur='venta_pista.php?';
          let params='process=getLimitDif&id_dif='+id_dif+'&id_cliente='+id_cliente
          let url=`${ur}${params}`
          function getVals(){
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
          await getVals().then((response) => {
              $('#disponible_dif').val(response.disponible);
          });
    } catch(err) {
        display_notify("Error", "no se pueden mostrar Galones consumidos");
    }
  }
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
  let aplica_impuesto = 1
  let aplica_dif     =  "";
  let seldif         =  $("#seldif option:selected").val();
  let tipo_impresion     =  $("#tipo_impresion option:selected").val();
  let iva = parseFloat($('#porc_iva').val());
  let num_combustibles=0
  let impuestos_aplicables = 0
  let subtotal = 0
  let precio_final   = 0
  let es_combustible  = 0
  let alcosto=0
  //impuestos al combustible
  let impuesto = 0
  let n = 0
  // array impuestos
  $.each(array_Impuestos, function(i, item) {
    if(item.activo == 1 ){
      impuestos_aplicables+= roundNumberV1(1 * item.valor,4);
    }
  })
  //fin array impuestos
  let total_impuesto = 0
  // fin impuestos al combustible
  $("#inventable tr").each(function() {
    cant = parseFloat($(this).find("#cant").val());
    if (isNaN(cant) || cant == "") {
      cant = 0;
    }
    es_combustible  = parseInt($(this).find('#combustible').val());
    alcosto  = parseInt($(this).find('#precio_costo').val());

    if(es_combustible==0){
      impuesto = 0.0
      aplica_impuesto = 0
    }else {
       impuesto  =  parseFloat($(this).find('#impuesto').val());
       num_combustibles++
    }
    //alert(impuesto)
    let aplica_dif     = $(this).find("#aplica_dif").val();
    let precio_venta_ini = parseFloat($(this).find('#precio_venta_inicial').val());
    let precio_sin_iva  = parseFloat($(this).find('#precio_sin_iva').val());
    let precio_prod_dif=$(this).find('#precio_prod_dif').val();
    let impuesto_dif  =  parseFloat($(this).find('#impuesto_dif').val());
    let monto_original= parseFloat($(this).find('#monto_original').val());
    let producto_dif   = $(this).find('#producto_dif').val()
    ex = parseInt($(this).find('#exento').val());
    precio_venta_ini  = isNaN( precio_venta_ini ) ? 0:  precio_venta_ini  ==""? 0: parseFloat( precio_venta_ini );
    precio_final   = precio_venta_ini
    if(es_combustible==0 && tipo_impresion != 'CCF'){
        precio_final   = precio_venta_ini
         impuesto = 0.0
         total_impuesto +=impuesto
         console.log("condicion:1"+"tipo_impresion:"+tipo_impresion + "es_combustible:"+es_combustible+" aplica_impuesto:"+aplica_impuesto+" precio_final producto:"+precio_final)
    }
    if(es_combustible==0 && tipo_impresion == 'CCF'){
        precio_final   =  precio_sin_iva
        impuesto = 0.0
        total_impuesto +=impuesto
        console.log("condicion:2"+"tipo_impresion:"+tipo_impresion + "es_combustible:"+es_combustible+" aplica_impuesto:"+aplica_impuesto+" precio_final producto:"+precio_final)
    }
    if( tipo_impresion == 'CCF' && seldif==-1 && es_combustible==1){
      precio_final   = (precio_venta_ini - impuesto)/ (1.0 + iva)
      aplica_impuesto = 1
      total_impuesto +=  cant * impuestos_aplicables
      console.log("condicion:3"+"tipo_impresion:"+tipo_impresion + "es_combustible:"+es_combustible+" aplica_impuesto:"+aplica_impuesto+" precio_final producto:"+precio_final)
    }
    if( tipo_impresion != 'CCF' && seldif==-1  && es_combustible==1){
      precio_final   = precio_venta_ini - impuesto
      aplica_impuesto = 1
      total_impuesto += cant * impuestos_aplicables
        console.log("condicion:4"+" precio_venta_ini:"+ precio_venta_ini+" impuesto:"+impuesto+" tipo_impresion:"+tipo_impresion + "es_combustible:"+es_combustible+" aplica_impuesto:"+aplica_impuesto+" precio_final producto:"+precio_final)
    }
    if (seldif!=-1 && producto_dif==1 && tipo_impresion != 'CCF'  && es_combustible==1 ){
        //precio_final    = precio_venta_ini - impuesto_dif
        precio_final    = precio_venta_ini - impuesto
        aplica_impuesto = 0
        console.log("condicion:5 "+"tipo_impresion:"+tipo_impresion + "es_combustible:"+es_combustible+" aplica_impuesto:"+aplica_impuesto+" precio_final producto:"+precio_final)
    }
    if (seldif!=-1 && producto_dif==1 && tipo_impresion == 'CCF'){
        //precio_final   = (precio_venta_ini - impuesto_dif) / (1.0 + iva)
        precio_final   = (precio_venta_ini - impuesto) / (1.0 + iva)
        aplica_impuesto = 0
          console.log("condicion:6"+"tipo_impresion:"+tipo_impresion + "es_combustible:"+es_combustible+" aplica_impuesto:"+aplica_impuesto+" precio_final producto:"+precio_final)
    }
    if (seldif!=-1 && producto_dif!=1 && es_combustible==1 ){
        precio_final   = precio_venta_ini - impuesto
        total_impuesto  =  cant * impuestos_aplicables
        aplica_impuesto = 1
          console.log("condicion:7"+"tipo_impresion:"+tipo_impresion + "es_combustible:"+es_combustible+" aplica_impuesto:"+aplica_impuesto+" precio_final producto:"+precio_final)
    }
    if(alcosto==1 && tipo_impresion=='CCF'){
        precio_final   = precio_venta_ini
        total_impuesto  =  cant * impuestos_aplicables
        aplica_impuesto = 1
          console.log("condicion:8"+"tipo_impresion:"+tipo_impresion + "es_combustible:"+es_combustible+" aplica_impuesto:"+aplica_impuesto+" precio_final producto:"+precio_final)
    }
    if(alcosto==1 && tipo_impresion!='CCF'){
        precio_final   = precio_venta_ini*( 1 +iva)
        total_impuesto  =  cant * impuestos_aplicables
        aplica_impuesto = 1
          console.log("condicion:9"+"tipo_impresion:"+tipo_impresion + "es_combustible:"+es_combustible+" aplica_impuesto:"+aplica_impuesto+" precio_final producto:"+precio_final)
    }

    //}

    $(this).find('#precio_venta').val(""+precio_final)

    subtotal = roundNumberV1(cant*precio_final,4);

    subtotal  = isNaN( subtotal ) ? 0:  subtotal  ==""? 0: parseFloat( subtotal );
    let subt_mostrar = subtotal.toFixed(4);
    $(this).find("#subtotal_fin").val(subt_mostrar);
    $(this).find("#subtotal_mostrar").val(subt_mostrar);
    totalcant     += parseFloat(cant);
    totalcant = isNaN(totalcant) ? 0 : parseFloat(totalcant)
    subt_exento =0
    total_gravado += parseFloat(subtotal);
    total_exento  = 0.0;
    total         += subt_exento + subtotal;
    filas         += 1;

    let nombre_imp=$("#totals").find("#nombre_imp").val();
    if (nombre_imp=="FOVIAL"){
      $("#totals").find("#"+n).find("#val_imp_gas").val(total_impuesto);
      $("#totals").find("#"+n).find("#total_impgas").text(total_impuesto);
    }
    n++

  });
  if (num_combustibles>1){
    aplica_impuesto = 1
  }
  console.log("total_impuesto:"+total_impuesto) //verificar cada uno de los impuestos y el total de c/u
  console.log("total_final(ge):"+total)
  $("#aplica_impuesto").val(aplica_impuesto)
  let duration =800;

  //$({to:0}).animate({to:1}, duration, function() {
    let totaless ={
        totalcant     : totalcant,
        total_gravado : total_gravado,
        total_exento  : total_exento,
        total         : total,
        filas         : filas,
     }

       return totaless ;
//});


}
//Recalculo de totales optimizado
let setTotals=async()=>{
    //valores base calculos
    let iva = $('#porc_iva').val();
    let tipo_impresion =  $("#tipo_impresion option:selected").val();
    //aplica DIF
    let aplica_dif   =  $("#aplica_dif").val();
    let seldif       =  $("#seldif").val();
    let tot_imp_gas  = $("#tot_imp_gas").val();
    tot_imp_gas =isNaN(tot_imp_gas) ? 0 : parseFloat(tot_imp_gas)

    let urlprocess   = "venta_pista.php";
    let total_iva = 0
    let total_percepcion  = 0.0
    let total_retencion1  = 0.0
    let total_retencion10 = 0.0
    let total_retencion   = 0.0
    let total_final       = 0.0
    //traer totales de tabla
    let duration =1500;
    let totaless=  totalColumns();
    let total_gravado = parseFloat(totaless.total_gravado)
    let totalcantidad = parseFloat(totaless.totalcant)
    let total_exento  = parseFloat(totaless.total_exento)
    let total_ge      = parseFloat(totaless.total) //sumas gravado y exento
    let filas         = parseInt(totaless.filas)
    //  getTotalTexto(total_final)
    if (tipo_impresion == "CCF") {
      total_iva = round((total_gravado * iva),4);
    }

    let total_gravado_iva  = total_gravado + total_iva;
    let descontar_impuesto = $("#descontar_impuesto").val();

    //console.log("total_percepcion :"+total_percepcion +" total_iva:"+total_iva +" total_retencion1:"+total_retencion1+" total_retencion10:"+total_retencion10+" tot_imp_gas:"+tot_imp_gas)
    total_final =  total_ge  + total_iva
    total_final += tot_imp_gas

   //console.log("total_ge:"+total_ge+" total_final:"+total_final)
    total_final=round(total_final,4)
    let totcant_m = totalcantidad.toFixed(2)
    let total_gravado_m = total_gravado.toFixed(4);
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
    $('#totaltexto').load("venta_pista.php", {
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
    $('#disponible_fac').val(disponible_fac.toFixed(2));
//  })
}

//iterar tabla para crear json y enviar a guardar factura
//Funcion para recorrer la tabla completa y pasar los valores de los elementos a un array
let storeTblValue=()=>{
    let i=0;
    let obj ={}
    let array_json=[];
    let total_galones = 0;
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
      let subt_bonifica = 0
      let combustible   = $(this).find("#combustible").val();
      if(combustible==1){
        total_galones += cantidad;
      }

      if (cantidad > 0 && precio_venta) {
          let obj = {
            id_detalle      : id_detalle,
            id              : id,
            precio          : precio_venta,
            cantidad        : cantidad,
            bonificacion    : bonificacion,
            unidades        : unidades,
            subtotal        : subtotal,
            subt_bonifica   : subt_bonifica,
            id_presentacion : id_presentacion,
            exento          : exento,
            combustible     : combustible,
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
      stringDatos+="&total_galones="+total_galones
   return stringDatos;
}
//recorrer tabla de impuestos para retornar a senddata()
let totalImpuestoGas=()=>{
  let total_imp_arr=[];
  $("#totals tr").each(function() {
    const id_imp       = $(this).find("#id_imp").val();
    const imp_nombre   = $(this).find("#imp_nombre").val();
    const val_imp_gas = $(this).find("#val_imp_gas").val();

    if(id_imp!="" && id_imp!=null && id_imp!=undefined && id_imp!=-1 ){
      let tot_imp ={
          id_imp        : id_imp,
          imp_nombre    : imp_nombre,
          val_imp_gas  : val_imp_gas,
       }
       total_imp_arr.push(tot_imp)
     }
  });
  let valjson = JSON.stringify(total_imp_arr);
  let stringDatos="&json_imp_arr="+valjson
  return stringDatos;
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
  let total_bonifica = $('#total_bonifica2').val();
  let tipo_impresion = $('#tipo_impresion').val();
  let fecha_movimiento = $("#fecha").val();
  let extra_nombre =$("#extra_nombre").val();
  let disponible_fac = $('#disponible_fac').val();
  let totcant = $('#totcant').text();
  let seldif       =  $("#seldif").val(); //para validar si es exento de fovial con dif
  let disponible_dif =  $("#disponible_dif").val();
  let tot_imp_gas  = $("#tot_imp_gas").val();
  disponible_dif = '' ?  0:round(parseFloat(disponible_dif),4);

  if (fecha_movimiento == '' || fecha_movimiento == undefined) {
    let typeinfo = 'Warning';
    msg = 'Seleccione una Fecha!';
    //display_notify(typeinfo, msg);
  }
  let tableData    = storeTblValue();
  let json_imp_arr = totalImpuestoGas();
  if (procces == "insert") {
    let id_cotizacion = "";
  }
  let aplica_impuesto= $("#aplica_impuesto").val()
  let urlprocess = "venta_pista.php";
  let dataString = 'process=insert'  + '&fecha_movimiento=' + fecha_movimiento;
  dataString += '&id_cliente=' + id_cliente + '&total=' + total;
  dataString += '&id_vendedor=' + id_vendedor // + '&json_arr=' + json_arr;
  dataString += '&retencion=' + retencion;
  dataString += '&total_percepcion=' + total_percepcion;
  dataString += '&numdoc=' + numdoc;
  dataString += '&iva=' + iva;
  dataString += '&items=' + items;
  dataString += '&subtotal=' + subtotal;
  dataString += '&total_bonifica=' + total_bonifica;
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
  dataString += '&extra_nombre=' + extra_nombre+'&totcant='+totcant;
  dataString += '&seldif=' + seldif+'&disponible_dif='+disponible_dif;
  dataString += '&tipo_pago='+tipo_pago +"&tot_imp_gas="+tot_imp_gas;
  dataString += tableData;
  dataString +=  json_imp_arr;
  dataString += '&aplica_impuesto=' + aplica_impuesto;
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

  if(credito==1){
    if (disponible_fac == "" || disponible_fac<=0 || disponible_fac==null
     || disponible_fac==undefined) {
      msg = 'Revise limte de crédito del cliente!';
      error=true;
      array_error.push(msg);
    }
  }
  if(error==false){
    $.ajax({
      type: 'POST',
      url: urlprocess,
      data: dataString,
      dataType: 'json',
      success: function(datax) {
        if (datax.typeinfo == "Success") {
          activa_modalPago(tipo_impresion,datax);
        } else {
          display_notify(datax.typeinfo, datax.msg);
        }
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        console.log("Status: " + textStatus);
        console.log("Error: " + errorThrown);
      }
    });
  } else {
    display_notify("Error", "En formulario: "+ array_error.join(",<br>"));
    $("#submit1").removeAttr('disabled');
  }
}

$(document).on('shown.bs.modal', function(e) {
});
//modal para pago , cambio e impresion de recibo
let activa_modalPago=async(tipo_impresion,datax)=>{
    let numdoc=datax.numdoc
    let totalfinal=parseFloat(datax.total);
    $('#id_factura').val(datax.id_factura);
    $('#modalPago').modal({backdrop: 'static',keyboard: false});
    let facturado= totalfinal.toFixed(2);
    let textPrint= `Impresión : ${datax.nombrecaja}  `
    textPrint+=  `<span class="fa-2x  fa-solid fa-gas-pump  text-success"></span>`
    $("#modalPago .modal-header .textmodalPrint").html(textPrint);
    $("#modalPago .modal-header  #facturado").val(facturado);
    $("#modalPago .modal-header #fact_num").html(numdoc);
    $("#modalPago .modal-header #descrip_pago").html(datax.descrip_pago);
    $('#modalPago .modal-body #id_factt').val(datax.id_factura);
    $('#modalPago .modal-body #docImpresion').val(tipo_impresion);
    let nombreCliente=$("#name_client_sel").val();
    $('#modalPago .modal-body #nombreCliente').val(nombreCliente);
    let duration =500;
    $({to:0}).animate({to:1}, duration, function() {
      $("#modalPago .modal-body #metodo_pago").focus()
      $('#modalPago #tableview').enableCellNavigation();
    });
    switch (tipo_impresion) {
      case 'TIK':
        $('#modalPago .modal-body .fac').hide();
        //$('#modalPago .modal-body .fiscal').hide();
        break;
      case 'COF':
        $('#modalPago .modal-body .fac').show();
        //$('#modalPago .modal-body .fiscal').hide();
        break;
      case 'CCF':
        $('#modalPago .modal-body .fac').show();
        //$('#modalPago .modal-body .fiscal').show();
        break;
    }

}

$(document).on("keydown","#modalPago .modal-body #efectivo",function(e){
	if(e.keyCode !=13 || e.keyCode !=27 )
	{
	  total_efectivo();
	}
  if(e.keyCode ==13 ){
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
let total_efectivo=()=>{
  let tipo_pago  = $('#modalPago .modal-body #metodo_pago').val();
  let totalfinal = parseFloat($(".modal-header #facturado").val());
	let facturado  = totalfinal.toFixed(2);
  let totall     =  $("#modalPago .modal-body #tot_fin").val()
  let diferencia = parseFloat($("#modalPago .modal-body #diferencia_").val())
  totall         = isNaN(totall) ? 0: totall ==""? 0: parseFloat(totall);
  let efectivo="";
  if  (tipo_pago =='CON'){
    efectivo=parseFloat($('#modalPago .modal-body #efectivo').val());
    if (isNaN(parseFloat(efectivo))){
      efectivo=0;
    }
    if (isNaN(parseFloat(totalfinal))){
      totalfinal=0;
    }
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

$(document).on("click", "#modalPago .modal-body #btnPrintFact", function (event) {
  let tipo_impresion = $("#tipo_impresion").val();
  if(tipo_impresion!='TIK')
    validarNumdoc()
  else
    imprimev()

});

$(document).on("click", "#btnEsc", function (event) {
	reload1();
});

let imprimev=(id_fact=-1)=>{
  let error       = false;
  let array_error = [];
  let   msg       = "";
  let imprimiendo = parseInt($('#imprimiendo').val());
  $('#imprimiendo').val(1);
  let numero_doc = $("#numdoc").val();
  let print = 'imprimir_fact';
  let tipo_impresion =  $("#tipo_impresion option:selected").val();
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
  let nombre_cliente = $("#nombreCliente").val()
  let data_extra   = $("#modalPago .modal-body #data_extra").val()
  if (tipo_impresion!== "TIK"){
     num_fact_cons = $("#modalPago .modal-body #numeroDocImpreso").val();
  }else{
     num_fact_cons = 0;
  }
  if  ($("#modalPago .modal-body #numeroDocImpreso").val() =="" && tipo_impresion!== "TIK" ){
    msg = 'Falta el Número de Documento !';
    error=true;
    array_error.push(msg);
    $("#modalPago .modal-body #numeroDocImpreso").focus();
  }
  let tipo_pago=$("#modalPago .modal-body #metodo_pago :selected").val();
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
       nit : nitt,
       del : del,
       al  : al,
       placa : placa,
       km : km,
       observaciones : observ,
    }
    let valess = JSON.stringify(vales);
    dataString  += '&valess='+valess
  }
  dataString +=  '&numero_doc=' + numero_doc
  dataString += '&tipo_impresion='+tipo_impresion+'&num_doc_fact='+id_factura
  dataString += '&numero_factura_consumidor=' + num_fact_cons+'&fecha_fact='+fecha_fact;
  dataString += '&cambio='+cambio_fin+'&efectivo='+efectivo_fin
  dataString += '&transaccion='+transaccion
  dataString += '&tarjeta_fin='+tarjeta_fin
  dataString += '&nombre_cliente='+nombre_cliente
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
      url: "venta_pista.php",
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
              cambio: cambio_fin
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
    let duration = 1000;
    $({to:0}).animate({to:1}, duration, function() {
      reload1()
        });
  }
  else {
   display_notify("Error", "En formulario: "+ array_error.join(",<br>"));
  }
}

//Agregar metodos de pago en modal
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
    //$("#modalPago .modal-body #efectivo").focus();
  }
  if  (tipo_pago =='CRE'){
    $(".modal-body #creditt").show();
    $(".modal-body #efecttiv").hide();
    $(".modal-body #tarjet").hide();
    $(".modal-body #cheques").hide();
    $(".modal-body #valess").hide();
  }
  if  (tipo_pago =='TAR'){
    $(".modal-body #efecttiv").hide();
    $(".modal-body #creditt").hide();
    $(".modal-body #valess").hide();
    $(".modal-body #cheques").hide();
    $(".modal-body #tarjet").show();
  }
  if  (tipo_pago =='VAL'){
    $(".modal-body #creditt").hide();
    $(".modal-body #efecttiv").hide();
    $(".modal-body #tarjet").hide();
    $(".modal-body #cheques").hide();
    $(".modal-body #valess").show();
    let nitcli = $('#nitcli').val();
    $("#modalPago .modal-body #nitt").val(nitcli)
  }
  if  (tipo_pago =='CHE'){
    $(".modal-body #creditt").hide();
    $(".modal-body #efecttiv").hide();
    $(".modal-body #tarjet").hide();
    $(".modal-body #valess").hide();
    $(".modal-body #cheques").show();
    let nitcli = $('#nitcli').val();
  }
}
//metodo de pago seleccionado
$('#modalPago .modal-body #metodo_pago').on('select2:select', function (e) {
    let data = e.params.data;
});
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
    let facturado = parseFloat( $("#modalPago .modal-header #facturado").val())
    let metodopago= $('#modalPago .modal-body  #metodo_pago option:selected').text();
    let data_extra= $("#modalPago .modal-body #data_extra").val()
    // $('#btnAddPayment').prop('disabled', true)
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
      //diferencia = facturado - valor
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
    actualizaTablaPagos()
}
//function to add tr to table tbody
let add_tr=(valor,diferencia, datos_extra)=>{
  let tipo_pago = $('#modalPago .modal-body  #metodo_pago option:selected').val();
  let btnDel  = '<input id="btnDelPay" type="button" '
      btnDel += 'class="btn btn-danger fa"  value="&#xf1f8;">';
  let totall=  $("#modalPago .modal-body #tot_fin").val()
  totall     = isNaN(totall) ? 0: totall ==""? 0: parseFloat(totall);
  let facturado = parseFloat( $("#modalPago .modal-header #facturado").val())
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
  let facturado = parseFloat( $("#modalPago .modal-header #facturado").val())
  $("#modalPago .modal-body #pagos tr").each(function() {
    let subt= $(this).find("td:eq(2)").text();
    totall     += parseFloat(subt);
  });
  $("#modalPago .modal-body #tot_fin").val(totall)
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
  let total_facturado=$(".modal-header #facturado").val();
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
//validar el monto recibido , si no es efectivo poenrlo igual a facturado
$(document).on("keydown", "#valcredit, #tarj, #valorcheque, #montovale", function() {
  let valor = $(this).val()
  let totalfinal = parseFloat($(".modal-header #facturado").val());
	let facturado  = totalfinal.toFixed(2);
  if (parseFloat(valor)>totalfinal){
    $(this).val(facturado)
  }
});
//traer los impuestos activos de combustible
async function getImpComb(){
    let 	ur='venta_pista.php?';
    let params ='process=getImpGas'
    let url=`${ur}${params}`
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
//para activar modal de combustibles
$(document).on("click", "#btnAddComb", function (event) {
	activaModal_Comb()
});
//para activar modal de combustibles
$(document).on("click", "#btnAddCombCosto", function (event) {
	activaModal_CombCosto()
});
let activaModal_Comb=()=>{
  $('#modalCombustibles').modal({
    backdrop: 'static',
    keyboard: false,
  });
  let duration = 500;
  $({to:0}).animate({to:1}, duration, function() {
    $("#modalCombustibles .modal-body #galones").focus();
    $("#modalCombustibles").find('#montoDinero').val("");
    $("#modalCombustibles").find('#galones').val("");
    $("#modalCombustibles").find('#txtBuscarProd').val(" ");
    $("#modalCombustibles").find('#qtyProd').val(" ");
   })
}
$(document).on("click", "#btnSalir", function(event) {
  $('#modalCombustibles').modal('hide');
  $('#modalCombCosto').modal('hide');
});
//activa modal combustible al costo
let activaModal_CombCosto=()=>{
  $('#modalCombCosto').modal({
    backdrop: 'static',
    keyboard: false,
  });
  let duration = 500;
  $({to:0}).animate({to:1}, duration, function() {
    $("#modalCombCosto .modal-body #galones").focus();
    $("#modalCombCosto").find('#montoDinero').val("");
    $("#modalCombCosto").find('#galones').val("");
    $("#modalCombCosto").find('#txtBuscarProd').val(" ");
   })
    $('#modalCombCosto #t_combustible').enableCellNavigation();
}
$(document).on('keydown', '.modal-body #montoDinero, #galones', function(e) {
    let val =$(this).val()
    if (e.which === 13||e.keyCode === 13 && val!="") { //arrow up
      let duration = 500;
      //$({to:0}).animate({to:1}, duration, function() {
        $(".modal-body  #t_combustible #tr_1 #descripc").focus()
      //})
    }
  })
  $(document).on("focus", '.modal-body #t_combustible>tr ', function(){
         $(this).addClass("cell-focus")
    });
  $(document).on("focusout", '.modal-body #t_combustible>tr ', function(){
         $(this).removeClass("cell-focus")
    });
 //modal combustible
 $(document).on('keydown', '#modalCombustibles .modal-body .btnSelComb', function(e) {
   let tr = $(this).parents("tr");
   let id_producto  =  tr.find("td:eq(0)").find("#id_product").val()
   let precio       =  tr.find("td:eq(3)").text()
   let monto        =  $(".modal-body").find("#montoDinero").val()
   let galones      =  $(".modal-body").find("#galones").val()
   if (e.which === 13) { //ENTER
     if (monto!=""){
        galones=  roundNumberV1(monto/precio,4)
        $(".modal-body #galones").val(""+galones);
     }
     if (galones!="" && monto==""){
        monto = roundNumberV1(galones * precio,4)
        $(".modal-body #montoDinero").val(""+monto);
     }
     if (galones!=""){
       addCombToList(id_producto, galones,monto)
       let duration = 800;
         $({to:0}).animate({to:1}, duration, function() {
           $(".modal-body").find("#montoDinero").val("")
            $(".modal-body").find("#galones").val("")
         })

     }
     $(".modal-body  #txtBuscarProd").focus()
   }
   if (e.which === 39) {
      $(".modal-body  #tabla_pagos #tr_1 #descripcImp").focus()
   }
 });

 // active plugin de navegacion de tecjlas cursoras !
 $(function () {
   $('#t_combustible').enableCellNavigation();
 });

 //modal combustible al costo
 $(document).on('keydown', '#modalCombCosto .modal-body .btnSelComb', function(e) {
   let tr = $(this).parents("tr");
   let id_producto  =  tr.find("td:eq(0)").find("#id_product").val()
   let precio  =  tr.find("#costo").val()
   let monto   =   $("#modalCombCosto .modal-body").find("#montoDinero").val()
   let id_cliente   =  $("#modalCombCosto .modal-body").find("#idCteConsumo").val()
   let galones =  $("#modalCombCosto .modal-body").find("#galones").val()
   if (e.which === 13) { //ENTER
     if (monto!=""){
        galones=  roundNumberV1(monto/precio,4)
        $(".modal-body #galones").val(""+galones);
     }
     if (galones!="" && monto==""){
        monto = roundNumberV1(galones * precio,4)
        $(".modal-body #montoDinero").val(""+monto);
     }

     let precio_costo=1;
     addCombToList(id_producto, galones,monto,precio_costo)
       $('#tipo_impresion').val('COF').change();
       $("#id_cliente").val(id_cliente).change();
      // $("#modalCombCosto .modal-body  #numeroDocImpreso").focus()
   }
   if (e.which === 39) {
      $(".modal-body  #tabla_pagos2 #tr_1 .selTrDocImp").focus()
   }


 });
 $(function () {
     $('#t_combustible2').enableCellNavigation();

   $('#tabla_pagos2').enableCellNavigation();
 });
 function addCombToList(id_proda, cant,monto_original,precio_costo=0)
 {
   $(".select2-dropdown").hide();
   $('#inventable').find('tr#filainicial').remove();
   array_prod = []
     $("#inventable tr").each(function(index) {
       let id_productt = $(this).find("td:eq(0)").text();
       array_prod.push(id_productt)
     });

   //impuestos al combustible
   let impuesto_dif = 0
   let impuesto = 0
   $.each(array_Impuestos, function(i, item) {
     if(item.activo==1 ){
       impuesto+= roundNumberV1(1 * item.valor,4);
     }
     if(item.activo==1 && item.dif==1 ){
      impuesto_dif+= roundNumberV1(1 * item.valor,4);
     }
   })
   id_proda = $.trim(id_proda);
   id_factura = parseInt($('#id_factura').val());
   if (isNaN(id_factura))
 	{
     id_factura = 0;
   }
   precio_aut = $("#precio_aut").val();
   urlprocess = "venta_pista.php";
   let dataString = 'process=consultar_stock'+'&id_producto='+id_proda+'&id_factura='+id_factura+'&tipo=D'+'&precio_aut='+precio_aut ;
    dataString += '&precio_costo='+precio_costo
   $.ajax({
     type: "POST",
     url: urlprocess,
     data: dataString,
     dataType: 'json',
     success: function(data){
 			if(data.typeinfo == "Success"){
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
        let es_combustible = data.es_combustible;
        let decimals=data.decimals;
        let aplicar_dif = data.aplicar_dif
        let precio_dif =  data.preciop
        let preciop =  data.preciop
        if (precio_costo==1){
           let iva             = parseFloat($('#porc_iva').val());
           preciop =   parseFloat(data.costo)
         }
         if (aplicar_dif == 1||aplicar_dif == '1' ){
             precio_dif   =   parseFloat(data.preciop) - parseFloat(impuesto_dif)
         }
         if(es_combustible==0){
           impuesto = 0.0
         }
 	      let preciop_s_iva = parseFloat(data.preciop_s_iva);
 	      let tipo_impresion = $('#tipo_impresion').val();

 	      let filas = parseInt($("#filas").val());
         filas++;
 	      let exentus = "<input type='hidden' id='exento' name='exento' value='" + exento + "'>";
 	      let subtotal = subt(preciop, cant);
        let img_prod= '<a data-toggle="modal" href="ver_imagen.php?id_producto='+id_prod+'"  data-target="#viewProd" data-refresh="true" class="btn btn-primary btnViw fa"><i class="fa fa-eye"></i></a>';
 	      subt_mostrar = subtotal.toFixed(2);
 	      let cantidades = "<td class='cell100 column10 text-success'><input type='text'  class='form-control decimal2 " + categoria + " cant' id='cant' name='cant' value='"+cant+"' style='width:60px;' readonly></td>";
         let  bonificaciones = "<td class='cell100 column10 text-success'><input type='text'  class='form-control decimal2 " + categoria + " bonificacion' id='bonificacion' name='bonificacion' value='' style='width:60px;'></td>";
         let  combustible="<input type='hidden' id='combustible' name ='combustible' value='"+es_combustible +"'>";
         let  producto_dif="<input type='hidden' id='producto_dif' name ='producto_dif' value='"+aplicar_dif +"'>";
         let  imp_dif="<input type='hidden' id='impuesto_dif' name ='impuesto_dif' value='"+impuesto_dif +"'>";
         let  monto_origin="<input type='hidden' id='monto_original' name ='monto_original' value='"+monto_original +"'>";
         let  precio_prod_dif="<input type='hidden' id='precio_prod_dif' name ='precio_prod_dif' value='"+precio_dif +"'>";
         let  imp="<input type='hidden' id='impuesto' name ='impuesto' value='"+impuesto +"'>";
         let  alcosto="<input type='hidden' id='precio_costo' name ='precio_costo' value='"+precio_costo +"'>";
         let btnView = '<a data-toggle="modal" href="ver_imagen.php?id_producto='+id_prod+'"  data-target="#viewProd" data-refresh="true" class="btn btn-primary btnViw fa"><i class="fa fa-eye"></i></a>';
 	      tr_add = '';
 	      tr_add += "<tr  class='row100 head' id='" + filas + "'>";
 	      tr_add += "<td hidden class='cell100 column10 text-success id_pps'><input type='hidden' id='unidades' name='unidades' value='" + data.unidadp + "'>" + id_prod + "</td>";
 	      tr_add += "<td class='cell100 column30 text-success'>" + descrip_only + exentus + combustible + producto_dif +precio_prod_dif+imp_dif+imp+monto_origin+alcosto+'</td>';
 	      tr_add += "<td class='cell100 column10 text-success' id='cant_stock'>" + existencias + "</td>";
 	      tr_add += cantidades;

 	      tr_add += "<td class='cell100 column10 text-success preccs'>" + data.select + "</td>";
 	      tr_add += "<td hidden class='cell100 column10 text-success descp'><input type'text' id='dsd' class='form-control' value='" + data.descripcionp + "' class='txt_box' readonly></td>";
 	      tr_add += "<td class='cell100 column10 text-success rank_s'>" + data.select_rank + "</td>";

 	      if (tipo_impresion == "CCF") {
          tr_add += "<td class='cell100 column10 text-success'><input type='hidden'  id='precio_venta_inicial' name='precio_venta_inicial' value='" + preciop_s_iva + "'>"
          tr_add += "<input type='hidden'  id='precio_sin_iva' name='precio_sin_iva' value='" + preciop_s_iva + "'>"
          tr_add += "<input type='text'  class='form-control decimal' id='precio_venta' name='precio_venta' value='" +  preciop_s_iva + "' readonly></td>";

 	        tr_add += "<td class='ccell100 column10'>" + "<input type='hidden'  id='subtotal_fin' name='subtotal_fin' value='" +  subt_mostrar + "'>" +
          "<input type='text'  class='decimal txt_box form-control' id='subtotal_mostrar' name='subtotal_mostrar'  value='"+ subt_mostrar + "'readOnly></td>";

 	      } else {
          tr_add += "<td class='cell100 column10 text-success'><input type='hidden'  id='precio_venta_inicial' name='precio_venta_inicial' value='" + preciop + "'>"
          tr_add += "<input type='hidden'  id='precio_sin_iva' name='precio_sin_iva' value='" + preciop_s_iva + "'>"
          tr_add += "<input type='text'  class='form-control decimal' id='precio_venta' name='precio_venta' value='" + preciop + "' readonly></td>";

 	        tr_add += "<td class='ccell100 column10'>" + "<input type='hidden'  id='subtotal_fin' name='subtotal_fin' value='" +  subt_mostrar + "'>" +
          "<input type='text'  class='decimal txt_box form-control' id='subtotal_mostrar' name='subtotal_mostrar'  value='" + subt_mostrar + "'readOnly></td>";
 	      }
        tr_add += "<td hidden class='cell100 column10 text-success id_pps'><input type='hidden' id='subt_bonifica' name='subt_bonifica' value='0'></td>";
 	     // tr_add += '<td class="cell100 column10 Delete text-center"><input id="delprod" type="button" class="btn btn-danger fa"  value="&#xf1f8;">&nbsp;'+ btnView+' </td>';
 	      tr_add += '</tr>';
 	      //numero de filas
        let existeProducto = false
        array_prod.forEach(function(idProd) {
            if (idProd == id_prod) {
               existeProducto = true
            }
        })
        if(existeProducto==false){
            $("#inventable").append(tr_add);
        }

 	      $(".decimal2").numeric({
 	        negative: false,
 	        decimalPlaces: 4,
 	      });
 	      $(".86").numeric({
 	        negative: false,
 	        decimalPlaces: 4
 	      });
 	      $('#filas').val(filas);
 	      $('#items').val(filas);
 	     // $(".sel").select2();
 	     // $(".sel_r").select2();

 	      setTotals();
 	      scrolltable();
         getImpGas()
     	}
 			else
 			{
 				display_notify("Error", data.msg);
 			}
 		}
   });
   let duration = 500;
     $({to:0}).animate({to:1}, duration, function() {
       setTotals();
     })
 }
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
// active plugin de navegacion de tecjlas cursoras !
$(function () {
  $('#tabla_pagos').enableCellNavigation();
});
$(document).on("focus", '.modal-body #tabla_pagos>tr ', function(){
       $(this).addClass("cell-focus")
  });
$(document).on("focusout", '.modal-body #tabla_pagos>tr ', function(){
       $(this).removeClass("cell-focus")
});
//seleccion del metodo de pago
$(document).on('keydown', '#modalCombustibles .modal-body .selTrDocImp', function(e) {
  let tr          = $(this).parents("tr");
  let alias_imp = tr.find("td:eq(0)").find("#alias_imp").val()
  //let descrip_imp  = tr.find("td:eq(1)").find("#descripcImp").val()
  if (e.which === 13) { //arrow up
    switch (alias_imp) {
    case 'TIK':
      $("#modalCombustibles").modal("hide")
      $("#tipo_impresion").val(alias_imp).change();
      $("#submit1").click();
      e.stopPropagation();
      break;
    case 'COF':
      $("#modalCombustibles").modal("hide")
      $("#tipo_impresion").val(alias_imp).change();
      $("#btnAddClient").click();
      e.stopPropagation();
      break;
    case 'CCF':
      $("#modalCombustibles").modal("hide")
      $("#tipo_impresion").val(alias_imp).change();
      $("#btnAddClient").click();
      e.stopPropagation();
      break;
    }
  }
  if (e.which === 37) {
        $(".modal-body  #t_combustible #tr_1 #descripc").focus()
  }
});

//activar modal de cliente
$(document).on("click", "#btnAddClient", function (event) {
	activaModal_Cliente()
});
let activaModal_Cliente=()=>{
  $('#modalCliente').modal({
    backdrop: 'static',
    keyboard: false,
  });
  let duration = 500;
  $({to:0}).animate({to:1}, duration, function() {
      $("#modalCliente .modal-body #buscador").focus();
      $("#modalCliente .modal-body #buscador").val("");
      $("#modalCliente .modal-body #resultCliente").html("")
      $("#modalCliente .modal-body #resultClienteDif").html("")
      $('#modalCliente .modal-body  #agregaCliente').hide()
  })
}

$(document).on('keydown', '#modalCliente .modal-body #buscador', function(e) {
  if (e.which === 13) {
    let url='buscador_cliente.php'
    let query=$(this).val()
    let limit = 4
    if (query.length > 2) {
      $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: {
          query: query,
          limit:limit,
        },
        success: function(datos) {
           addCteTable(datos)
        }
      })
    }
    e.stopPropagation();
    e.preventDefault();
  }
  if(e.keyCode ==27 || e.which==27)
  {
    $("#modalCliente .modal-body #btnEscape").click();
    e.stopPropagation();
    e.preventDefault();
  }
    $("#modalCliente .modal-body #agregaCliente").hide();
});
let addCteTable=(datos)=>{
  let filas=1;
  let  count = Object.keys(datos).length;
  $("#resultCliente").html("");
  $('#resultCliente').enableCellNavigation();
  if(count>0){
    $.each(datos, function(i, item) {
      let inputhide  ='<input type="text" id="tableEventShifter" '
      inputhide += 'value="'+item.id_cliente +'"'
      inputhide += 'style="position:absolute;z-index: -10;" />'
      tr_add = '';
      tr_add += "<tr  id='" + filas + "' tabindex='"+i+"'>";
      tr_add += "<td>" + filas +inputhide +"</td>";
      tr_add += "<td>" + item.nombre + "</td>";
      tr_add += "<td>" + item.nrc + "</td>";
      tr_add += "</tr>"
      $("#resultCliente").append(tr_add);
      $('#resultCliente').enableCellNavigation();
      filas++;
    })
    $("#resultCliente tr[tabindex=0] #tableEventShifter").focus();
  }
  if(count==0){
      let inputhide  ='<input type="text" id="tableEventShifter" '
      inputhide += 'value="-1"'
      inputhide += 'style="position:absolute;z-index: -10;" />'
      tr_add = '';
      tr_add += "<tr  id='0' tabindex='0'>";
      tr_add += "<td>" + "-"+inputhide + "</td>";
      tr_add += "<td colspan=2>" + "SIN RESULTADOS, ENTER AGREGAR" + "</td>";
      tr_add += "</tr>";
      $("#resultCliente").append(tr_add);
      $("#resultCliente tr[tabindex=0] #tableEventShifter").focus();
  }
}

$(document).on('keydown', '.modal-body  #resultCliente tr', function(e) {
  if(e.which == 13){ //up
    let tr = $(this).parents("tr");
    let nombress  = $(e.target).closest("tr").find("td:eq(1)").text()
    let id = $(e.target).closest("tr").find("#tableEventShifter").val()
    if(id!=-1){
      $("#id_cliente").val(id).change();
      $("#id_client_sel").val(id)
      $("#name_client_sel").val(nombress)
      $('.modal-body  #agregaCliente').hide()
      getClienteDif(id)
    }else{
      $('.modal-body  #agregaCliente').show()
      $('#addShowClient').enableCellNavigation();
      $("#addShowClient tr[tabindex=0] #nombres").focus()
    }
  }
})
  $(document).on("focus", '.modal-body #resultCliente tr ', function(e){
      $(this).addClass("cell-focus")
  });
  $(document).on("focusout", '.modal-body #resultCliente tr ', function(e){
      $(this).removeClass("cell-focus")
  });
  $(document).on("click", "#modalCliente .modal-body #btnModalClient", function(e) {
      agregarCte();
      e.stopPropagation();
  });
let agregarCte=()=> {
  let error=false;
  let array_error=[];
  let nombre=$('.modal-body #nombres').val();
  let nrc=$('.modal-body #nrc').val();
  let dui=$('#modalCliente .modal-body #dui').val();
  let esDUI=true
  if(dui!=""){
    esDUI=isDUI(dui);
  }
  if(!esDUI || esDUI==false){
    error=true;
    array_error.push('Verificar DUI');
  }
  let direccion=$('.modal-body #direccion').val();
  let telefono1=$('.modal-body #tel1').val();
  if (nombre=="" ){
    error=true;
    array_error.push('Verificar Nombre y Apellido ');
  }
  let dataString = 'process=agregar_cliente'
  dataString+='&nombre='+nombre+'&nrc='+nrc;
  dataString+='&dui='+dui+'&direccion='+direccion+'&telefono1='+telefono1;
  let duration =1000;
  if(error==false){
    axios.post(urlprocess,dataString)
    .then(function (response) {
      //display_notify(response.data.typeinfo, response.data.msg);
      let id_client = response.data.id_client;
      let nombress = response.data.nombre;
      if(id_client!=-1){
        $('#id_cliente').append($('<option>', {
            value: id_client,
            text: nombress
        }));
        $("#id_cliente").val(id_client).change();
        $("#id_client_sel").val(id_client)
        $("#name_client_sel").val(nombress)
        $('#modalCliente').modal('hide');
        $("#submit1").click()
      }
    })
    .catch(function (error) {
      console.log(error);
    });
  }else{
    display_notify("Error", "En formulario:<br>"+ array_error.join(",<br>"));
  }
}
let getClienteDif=(id_cliente)=>{
   let dataString ='process=getBoats&idcliente='+id_cliente
   axios.post(urlprocess,dataString)
   .then(function (response) {
   $("#resultClienteDif").html("");
   addCteDifTable(response.data.detalle)
   })
   .catch(function (error) {
     console.log(error);
   });
}

let addCteDifTable=(datos)=>{
  let filas=1;
  let  count = Object.keys(datos).length;
  if(count>0){
    $('#resultClienteDif').enableCellNavigation();
    let inputhide  ='<input type="text" id="eventShifterDif" '
        inputhide += 'value="-1"'
        inputhide += 'style="position:absolute;z-index: -10;" />'
    tr_add = '';
    tr_add += "<tr  id='0' tabindex='0'>";
    tr_add += "<td>" + "-"+inputhide + "</td>";
    tr_add += "<td colspan=2>" + "NO SELECCION" + "</td>";
    tr_add += "</tr>";
    $("#resultClienteDif").append(tr_add);
    $.each(datos, function(i, item) {
      let inputhide  ='<input type="text" id="eventShifterDif" '
          inputhide += 'value="'+item.id_dif +'"'
          inputhide += 'style="position:absolute;z-index: -10;" />'
        tr_add = '';
        tr_add += "<tr  id='" + filas + "' tabindex='"+filas+"'>";
        tr_add += "<td>" + filas +inputhide +"</td>";
        tr_add += "<td>" + item.numero_dif+ "</td>";
        tr_add += "<td>" + item.embarcacion+ "</td>";
        tr_add += "</tr>"
        $("#resultClienteDif").append(tr_add);
          $('#resultClienteDif').enableCellNavigation();
        filas++;

    })
    $("#resultClienteDif tr[tabindex=0] #eventShifterDif").focus();
  }
  if(count==0){
    let inputhide  ='<input type="text" id="eventShifterDif" '
        inputhide += 'value="-1"'
        inputhide += 'style="position:absolute;z-index: -10;" />'
    tr_add = '';
    tr_add += "<tr  id='0' tabindex='1'>";
    tr_add += "<td>" + "-"+inputhide + "</td>";
    tr_add += "<td colspan=2>" + "SIN RESULTADOS" + "</td>";
    tr_add += "</tr>";
    $("#resultClienteDif").append(tr_add);
    $("#resultClienteDif tr[tabindex=1] #eventShifterDif").focus();
  }
}
$(document).on("focus", '.modal-body #resultClienteDif tr ', function(e){
    $(this).addClass("cell-focus")
});
$(document).on("focusout", '.modal-body #resultClienteDif tr ', function(e){
    $(this).removeClass("cell-focus")
});
$(document).on("keydown","#modalCliente .modal-body ",function(e){
  if(e.keyCode ==27 || e.which==27){
    $("#modalCliente .modal-body #btnEscape").click();
    e.stopPropagation();
    e.preventDefault();
  }
  if(e.keyCode ==115 || e.which==115){ //tecla f4
    $("#modalCliente .modal-body #btnReturnCte").click();
    e.stopPropagation();
    e.preventDefault();
  }
  if (e.ctrlKey && e.which == 71) { // Ctrl + G Guardar
    $("#modalCliente .modal-body #btnModalClient").click();
    e.preventDefault();
    e.stopPropagation();
  }
});
$(document).on("click", "#modalCliente .modal-body #btnEscape", function(e){
  $('#modalCliente').modal('hide');
});
$(document).on("click", "#modalCliente .modal-body #btnReturnCte", function(e) {
  e.stopPropagation();
  e.preventDefault();
  $("#modalCliente #buscador").focus();
});
$(document).on('keydown', '.modal-body  #resultClienteDif tr', function(e) {
  if(e.which == 13){
    let id = $(e.target).closest("tr").find("#eventShifterDif").val()
    //$("#modalCliente #buscador").focus();
    //e.stopPropagation();
    e.preventDefault();
    $("#seldif").val(id).change();
    let duration = 1000;
      $({to:0}).animate({to:1}, duration, function() {
        setTotals()
        $("#modalCliente").modal("hide")
        //$("#submit1").click();
        senddata();
      })

  }
});
//BOTON DE IMPRESION CONSUMO INTERNO
$(document).on("click", "#btnPrintConsumo", function (event) {
  guardarConsumo()
});
let guardarConsumo=()=> {
  //revisar que si no se ha ingresado numero de factura y le da click guarda 2 veces !!!!!!!!!!!!!!!!!!!
  //Obtener los valores a guardar de cada item facturado
  let procces = $("#process").val();
  let i = 0;
  let id = '1';
  let id_empleado = 0;
  let id_cliente = $("#id_clienteCosto option:selected").val();
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
  let total_bonifica = $('#total_bonifica2').val();
  let tipo_impresion = $('#tipo_impresion option:selected').val();
  let fecha_movimiento = $("#fecha").val();
  let extra_nombre =$("#extra_nombre").val();
  let disponible_fac = $('#disponible_fac').val();
  let totcant = $('#totcant').text();
  let seldif       =  $("#seldif").val(); //para validar si es exento de fovial con dif
  let disponible_dif =  $("#disponible_dif").val();
  let tot_imp_gas  = $("#tot_imp_gas").val();
  disponible_dif = '' ?  0:round(parseFloat(disponible_dif),4);
  if (fecha_movimiento == '' || fecha_movimiento == undefined) {
    let typeinfo = 'Warning';
    msg = 'Seleccione una Fecha!';
    //display_notify(typeinfo, msg);
  }
  let tableData    = storeTblValue();
  let json_imp_arr = totalImpuestoGas();
  if (procces == "insert") {
    let id_cotizacion = "";
  }
  let aplica_impuesto= $("#aplica_impuesto").val()
  let urlprocess = "venta_pista.php";
  let dataString = 'process=insert'  + '&fecha_movimiento=' + fecha_movimiento;
  dataString += '&id_cliente=' + id_cliente + '&total=' + total;
  dataString += '&id_vendedor=' + id_vendedor // + '&json_arr=' + json_arr;
  dataString += '&retencion=' + retencion;
  dataString += '&total_percepcion=' + total_percepcion;
  dataString += '&numdoc=' + numdoc;
  dataString += '&iva=' + iva;
  dataString += '&items=' + items;
  dataString += '&subtotal=' + subtotal;
  dataString += '&total_bonifica=' + total_bonifica;
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
  dataString += '&extra_nombre=' + extra_nombre+'&totcant='+totcant;
  dataString += '&seldif=' + seldif+'&disponible_dif='+disponible_dif;
  dataString += '&tipo_pago='+tipo_pago +"&tot_imp_gas="+tot_imp_gas;
  dataString += tableData;
  dataString +=  json_imp_arr;
  dataString += '&aplica_impuesto=' + aplica_impuesto;
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
  if(credito==1){
    if (disponible_fac == "" || disponible_fac<=0 || disponible_fac==null
     || disponible_fac==undefined) {
      msg = 'Revise limte de crédito del cliente!';
      error=true;
      array_error.push(msg);
    }
  }
  if  ($("#modalCombCosto #numeroDocImpreso").val() =="" && tipo_impresion!= "TIK" ){
    msg = 'Falta el Número de Documento !';
    error=true;
    array_error.push(msg);
    $("#modalCombCosto .modal-body #numeroDocImpreso").focus();
  }
  if(error==false){
    $.ajax({
      type: 'POST',
      url: urlprocess,
      data: dataString,
      dataType: 'json',
      success: function(datax) {
        if (datax.typeinfo == "Success") {
          $(".usage").attr("disabled", true);
           imprimeConsumo(datax);
        } else {
          display_notify(datax.typeinfo, datax.msg);
        }
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        console.log("Status: " + textStatus);
        console.log("Error: " + errorThrown);
      }
    });
  } else {
    display_notify("Error", "En formulario: "+ array_error.join(",<br>"));
    $("#submit1").removeAttr('disabled');
  }
}
let imprimeConsumo=(datax)=>{
  $("#submit1").removeAttr('disabled');
  $("#btnAddClient").removeAttr('disabled');
  let error       = false;
  let array_error = [];
  let   msg       = "";
  let numero_doc = $("#numdoc").val();
  //let tipo_pago='COI';
  let print = 'imprimir_fact';
  let fecha_fact = $("#fecha_fact").val();
  let direccion = $("#dircli").val();
  let id_factura = datax.id_factura;
  let  tipo_impresion = $('#tipo_impresion option:selected').val();
  let cambio_fin   = 0
  let efectivo_fin = 0
  let tarjeta_fin  = 0
  let diferencia   = 0
  let num_fact_cons = '';
  let transaccion =" "
  let nombre_cliente = $("#modalCombCosto #nombreCliente").val()
  let tipo_pago =  $('#alias_pago option:selected').val();
  if (tipo_impresion== "TIK"){
     num_fact_cons = 0;
  }
  if  ($("#modalCombCosto #numeroDocImpreso").val() =="" && tipo_impresion!== "TIK" ){
    msg = 'Falta el Número de Documento !';
    error=true;
    array_error.push(msg);
    $("#modalCombCosto .modal-body #numeroDocImpreso").focus();
  }else{
     num_fact_cons = $("#modalCombCosto #numeroDocImpreso").val();
  }
  let dataString  = 'process=' + print
  dataString +=  '&numero_doc=' + numero_doc
  dataString += '&tipo_impresion='+tipo_impresion+'&num_doc_fact='+id_factura
  dataString += '&numero_factura_consumidor=' + num_fact_cons+'&fecha_fact='+fecha_fact;
  dataString += '&cambio='+cambio_fin+'&efectivo='+efectivo_fin
  dataString += '&transaccion='+transaccion
  dataString += '&tarjeta_fin='+tarjeta_fin
  dataString += '&nombre_cliente='+nombre_cliente
  nombreape = $("#nomcli").val();
  if (tipo_impresion == "CCF" ||tipo_impresion == "COF" ) {
    let nit = $("#nitcli").val();
    let nrc = $("#nrccli").val();
    dataString += '&nit=' + nit + '&nrc=' + nrc;
  }
  dataString += "&direccion=" + direccion + '&nombreape=' + nombreape;
    let datos_extr="";
    if(tipo_pago=='CON'){
      datos_extr={
         efectivo : datax.total,
         cambio :'0.0',
      }
    }
  if(tipo_pago=='COI'){
    datos_extr={
       mensaje:'consumo interno',
       efectivo : 0,
       cambio :0,
    }
  }
  if(tipo_pago=='CRE'){
    datos_extr={
       dias_credito : 0,
    }
  }

  let datos_extra = JSON.stringify(datos_extr);
  let obj = {
    id_factura      : id_factura,
    alias_pago      : tipo_pago,
    subtotal        : datax.total,
    total_facturado : datax.total,
    datos_extra    : datos_extra,
  }
  let array_json=[];
  let array_pagos=[];
  array_json.push(obj)
  array_pagos.push(tipo_pago)
  let valjson = JSON.stringify(array_json);
  let stringDatos="&json_arr="+valjson
  stringDatos+="&tipo_pago="+array_pagos
  stringDatos+="&cuantos=1"
  dataString += stringDatos;
  if ( error == false) {
      let duration =500;
    $.ajax({
      type: 'POST',
      url: "venta_pista.php",
      data: dataString,
      dataType: 'json',
      success: function(datos) {
        let sist_ope = datos.sist_ope;
        let dir_print = datos.dir_print;
        let shared_printer_win = datos.shared_printer_win;
        let shared_printer_pos = datos.shared_printer_pos;
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
      },
      error : function() {
        alert('Disculpe, existió un problema en impresión');
      },
    });
    $("#inventable tr").remove();
    $('#modalCombCosto').modal('hide');
  }
  else {
   display_notify("Error", "En formulario: "+ array_error.join(",<br>"));
  }
}
//falta activar combinaciones de tecla btn salir y tecla esc, ctrl +p  para imprimir en modalCombCosto !!!
$(document).on('keydown', '#modalCombCosto', function(e) {
  if (e.keyCode ==27 || e.which == 27) { //Esc salir
    $('#modalCombCosto .modal-body #btnSalir').click()
    e.stopPropagation();
    e.preventDefault();
  }
  if (e.ctrlKey && e.which == 80) { // Ctrl + P Imprimir
      $("#modalCombCosto .modal-body  #btnPrintConsumo").click();
      e.preventDefault();
      e.stopPropagation();
  }
})
$(document).on('keydown', '#modalCombCosto #numeroDocImpreso', function(e) {
  let val = $(this).val()
  if (val!="" && (e.keyCode ==13 || e.which == 13) ) { //Enter => Imprimir
    $("#modalCombCosto .modal-body  #btnPrintConsumo").click();
    e.preventDefault();
    e.stopPropagation();
  }
})
/* autocomplete en modal */
const autoCompleteConfig = [{
    name: 'Datos',
    debounceMS: 250,
    minLength: 2,
    maxResults: 10,
    inputSource: document.getElementById('txtBuscarProd'),
    targetID: document.getElementById('idProdSelected'),
    fetchURL: 'autocomp_producto_pista.php?term={term}',
    fetchMap: {id: "id",
               name: "descripcion"}
  }
];
// Initiate Autocomplete to Create Listeners
autocompleteBS(autoCompleteConfig);
function resultHandlerBS(inputName, selectedData) {
  console.log("descripcion:"+selectedData.descripcion);
  document.getElementById('idProdSelected').value=selectedData.id_producto;
  $(" .modal-body #qtyProd").focus()
}
$(document).on('keydown', '.modal-body #txtBuscarProd', function(e) {
  if ( e.keycode === 13 || e.which === 13) {
    $(" .modal-body #qtyProd").focus()
  }
})

  $(document).on('keydown', '.modal-body #qtyProd', function(e) {
      let val =$(this).val()
      val.trim()
      if($(this).val()==""){
        val=1
      }
      console.log("cantidad:"+val)
      if ( e.keycode === 13 || e.which === 13) {
        if ( $(this).val()!=""  &&  val!=0) {
          let id_producto=$("#modalCombustibles .modal-body #idProdSelected").val();
          addCombToList(id_producto,val ,0)
          let duration = 500;
            $({to:0}).animate({to:1}, duration, function() {
              $("#modalCombustibles .modal-body #txtBuscarProd").val("")
              $("#modalCombustibles .modal-body #qtyProd").val("")
            })
        }
          $("#modalCombustibles .modal-body #galones").focus()
      }
    })
    //num_fact_cons = $("#modalPago .modal-body #numeroDocImpreso").val();
    $(document).on('keydown', '#modalPago .modal-body #numeroDocImpreso', function(e) {
        if (e.which === 13||e.keyCode === 13 ) {
          let val=$(this).val()
          if ( val!="") {
            validarNumdoc()
          }
        }
      })
function clearTable(){
$("#inventable").html("");
  setTotals();
}

$(document).on("click", "#btnClearTable", function(event) {
  clearTable()
});
  $(document).on('keydown', '#modalCombustibles', function(e) {
    if (e.which == 27) { //Esc salir
      $('#modalCombustibles #btnSalir').click()
      e.stopPropagation();
      e.preventDefault();
    }

    if ( e.which == 46) { //Supr Imprimir
        $('#modalCombustibles #btnClearTable').click()
        e.preventDefault();
      e.stopPropagation();
    }
  })
//validar que el numero de documento este en el rango de la resolucion actual y que no este repetido

let validarNumdoc=()=>{
  let numeroDocImpreso = $("#modalPago .modal-body #numeroDocImpreso").val();
  let tipo_impresion = $("#tipo_impresion").val();
  let dataString = 'process=validarNumdoc'
  dataString+='&numeroDocImpreso='+numeroDocImpreso
  dataString+='&tipo_impresion='+tipo_impresion
  axios.post(urlprocess,dataString)
    .then(function (response) {
    let docValido = response.data.valido;
    if(docValido==true){
        display_notify(response.data.typeinfo, response.data.msg);
      imprimev()
    }else{
        display_notify(response.data.typeinfo, response.data.msg);
      $("#modalPago .modal-body #numeroDocImpreso").focus()
    }
  })
  .catch(function (error) {
    console.log(error);
  });
}


//autocomplete para costo en modal
const autoCompleteConfig2 = [{
    name: 'Datos',
    debounceMS: 250,
    minLength: 2,
    maxResults: 10,
    inputSource: document.getElementById('txtBuscarProd2'),
    targetID: document.getElementById('idProdSelected2'),
    fetchURL: 'autocomp_producto_pista.php?term={term}',
    fetchMap: {id: "id",
               name: "descripcion"}
  }
];
// Initiate Autocomplete to Create Listeners
autocompleteBS(autoCompleteConfig2);
function resultHandlerBS(inputName, selectedData) {
  console.log("descripcion:"+selectedData.descripcion);
  document.getElementById('idProdSelected2').value=selectedData.id_producto;
  $(" .modal-body #qtyProd2").focus()
}
$(document).on('keydown', '.modal-body #qtyProd2', function(e) {
    let val =$(this).val()
    val.trim()
    if($(this).val()==""){
      val=1
    }
    console.log("cantidad:"+val)
    let precio_costo=1;

    if ( e.keycode === 13 || e.which === 13) {
      if ( $(this).val()!=""  &&  val!=0) {
        let id_producto=$(" .modal-body #idProdSelected2").val();
        addCombToList(id_producto,val ,0,precio_costo)
      }
        $("#modalCombCosto .modal-body #montoDinero").focus()
    }
  })


//seleccionar pago al costo
$(document).on('keydown', '#modalCombCosto .modal-body .selTrDocImp', function(e) {
  let tr          = $(this).parents("tr");
  let alias_imp = tr.find("td:eq(0)").find("#alias_imp").val()
  //let descrip_imp  = tr.find("td:eq(1)").find("#descripcImp").val()
  if (e.which === 13) { //arrow up
    switch (alias_imp) {

    case 'COF':
      //$("#modalCombCosto").modal("hide")
      $("#tipo_impresion").val(alias_imp).change();
     $("#numeroDocImpreso").focus()
      e.stopPropagation();
      break;
    case 'CCF':
    //  $("#modalCombCosto").modal("hide")
      $("#tipo_impresion").val(alias_imp).change();
      $("#numeroDocImpreso").focus()
      e.stopPropagation();
      break;
    }
  }
  if (e.which === 37) {
      $(".modal-body  #t_combustible #tr_1 #descripc").focus()
  }
});
$(document).on('keydown', '#modalCombCosto .modal-body #montoDinero, #galones', function(e) {
    let val =$(this).val()
    if (e.which === 13||e.keyCode === 13 && val!="") { //arrow up
      let duration = 500;
      //$({to:0}).animate({to:1}, duration, function() {
        $("#modalCombCosto .modal-body  #t_combustible2 #tr_1 #descripc").focus()
      //})
    }
  })
  $(document).on("focus", '.modal-body #t_combustible2>tr ', function(){
         $(this).addClass("cell-focus")
    });
  $(document).on("focusout", '.modal-body #t_combustible2>tr ', function(){
         $(this).removeClass("cell-focus")
    });
    $(document).on("focus", '.modal-body #tabla_pagos2>tr ', function(){
           $(this).addClass("cell-focus")
      });
    $(document).on("focusout", '.modal-body #tabla_pagos2>tr ', function(){
           $(this).removeClass("cell-focus")
    });
