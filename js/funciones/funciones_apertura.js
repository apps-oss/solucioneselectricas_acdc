$(document).ready(function(){
	$('#formulario').validate({
	    rules: {
            fecha:
            {
            	required: true,
            },
            empleado:
            {
                required: true,
            },
            turno:
            {
            	required: true,
            },
            monto_apertura:
            {
            	required: true,
            },
         },
        messages: {
		fecha: "Por favor ingrese la fecha de apertura",
		empleado: "Por favor seleccione el empleado",
		turno: "Por favor seleccione el turno",
		monto_apertura: "Ingrese el monto de apertura",

		},
        submitHandler: function (form) {
            apertura();
        }
    });
		$( ".datepick" ).datepicker();
		$(".select").select2();
		$('.selectt').select2();
		$(".numeric").numeric({
			negative: false,
		});
		$(".decimal").numeric({
	    negative: false,
	    decimalPlaces: 4
	  });
		let duration = 1000

		$({to:0}).animate({to:1}, duration, function() {
			let caja = $("select#caja option:selected").val(); //get the value
			getCajaTipo(caja);
		});

});



function apertura()
{
	let url='apertura_caja.php';
	let form = $("#formulario");
	let tipo_caja = $("#tipo_caja").val()
	if (tipo_caja =='2'){
		let tableData = storeTblValue();
		$("#galonaje").val(tableData)
	}
    let formdata = false;
    if(window.FormData)
    {
        formdata = new FormData(form[0]);
    }
    let formAction = form.attr('action');
		let  caja = $("#caja option:selected").val();
		if(caja != "" && caja >= 0 )
		{
			$.ajax({
	        type        : 'POST',
	        url         : url,
	        cache       : false,
	        data        : formdata ? formdata : form.serialize(),
	        contentType : false,
	        processData : false,
	        dataType : 'json',
	        success: function(data)
	        {
			    display_notify(data.typeinfo,data.msg,data.process);
	            if(data.typeinfo == "Success")
	            {
								let admin= $("#admin").val()

								if(admin==0){
									reload1(data.tipo_caja)
								}else{
									reload2()
								}

	            }
		    }
	    });
		}
		else
		{
				display_notify("Error", "Debe de seleccionar una caja");
		}
}

function reload1(tipo)
{
	let duration = 1000
	if(tipo =='1')
		location.href = 'venta.php';
	if(tipo =='2')
		location.href = 'venta_pista.php';
	$({to:0}).animate({to:1}, duration, function() {
    location.href
	});
}
function reload2()
{
	let duration = 1000
		location.href = 'dashboard.php';
	$({to:0}).animate({to:1}, duration, function() {
    location.href
	});
}

//consultar el tipo de caja y si es pista mostrar los text para llenado de tanque!!!!!
//$(document).on("change", "#tipo_entrada", function() {
$(document).on('change', '#caja', function(event) {
let caja = $(this).find(':selected').val(); //get the value
	getCajaTipo(caja);
});
function getCajaTipo(caja){
		$("#tanques").hide()
 if (caja!=-1){
	let url='apertura_caja.php';
	 let dataString ='process=getCaja&caja='+caja
	 axios.post(url,dataString)
	 .then(function (response) {
		 console.log(response.data);
		 if (response.data.tipo_caja=='2'){
				$("#tanques").show()
				 console.log("mostrar tanques...");
		 }
		 else{
				$("#tanques").hide()
		 }

			$("#tipo_caja").val(response.data.tipo_caja)
	 })
	 .catch(function (error) {
		 console.log(error);
	 });
 }
}
//recorrer tabla y guardar valores
let storeTblValue=()=>{
    let i=0;
    let obj ={}
    let array_json=[];
    $("#tank tr").each(function(index) {
			let id_tank = $(this).find("td:eq(0)").text();
      let galones     = $(this).find("td:eq(3)").find("#gal_dia").val();
			 galones        = isNaN(galones ) ? 0:  galones  ==""? 0: parseFloat(galones );
      if (galones >= 0 ) {
          let obj = {
            id_tank    : id_tank,
            galones    : galones,
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
   return valjson;
}
