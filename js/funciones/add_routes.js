$(document).ready(function() {
  $("#cliente").typeahead({
  	//  contentType: "application/json; charset=utf-8",
  	source: function(query, process) {
  		$.ajax({
                     url: 'autocomplete_cliente.php',
                      type: 'POST',
                      data: 'query=' + query ,
                      dataType: 'JSON',
                      async: true,

                      success: function(data) {
  						//$("#xcontribuyente").val('');
                          process(data);
  						}
                  });
              },

             updater: function(selection){
            var data0=selection;
            var data= data0.split("|");
            var id_data = data[0];
            var descrip1 =data[1];
          alert(id_data)
  				}


  });
/*
  $("#scrollable-dropdown-menu #producto_buscar").typeahead({
    highlight: true,
  },
  {
  	limit:100,
    name: 'productos',
    display: 'producto',
  	source: function show(q, cb, cba) {
          console.log(q);
          var url = 'autocomplete_cliente.php' + "?query=" + q;
          $.ajax({ url: url })
              .done(function(res) {
                  cba(JSON.parse(res));
              })
              .fail(function(err) {
                  alert(err);
              });
      }
  }).on('typeahead:selected', onAutocompleted);

  function onAutocompleted($e, datum) {
     alert("aca")
  		$('.typeahead').typeahead('val', '');
  		var prod0=datum;
  		 var prod= prod0.split("|");
  		 var id_prod = prod[0];
  		 var descrip = prod[1];
       alert(id_prod)
  }*/
/*
  let url = 'autocomplete_cliente.php'
  $("#scrollable-dropdown-menu  #producto_buscar").typeahead({
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
	  var prod0=data0.producto;
  	var id = prod0.split("|");
  	var nombre = id[1];
  	id = parseInt(id[0]);
    alert(id)

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
  				//agregar_lista(id_cliente,id_mun,nom_mun,id_dep,nom_dep,cliente)
  			}else {
  				display_notify("Warning",datax.msg)
  			}



  			}
  		});
  }
  */
});
