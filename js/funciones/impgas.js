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
	$(document).on('shown.bs.modal', function(e) {
		$('#valor').numeric({
	    negative: false,
	    decimalPlaces: 2
	  });
	});
});

function senddata(){
	let name=$('#nombre').val();
	let description= $('#descripcion').val();
	let valor = $('#valor').val();

	let error=false;
	  let array_error=[];
	if (valor.length==0 || valor==''|| valor==0){
		let msg='Falta Asignar valor\n'
    msg+='de  impuestos!';
    error=true;
    array_error.push(msg);
	}
	let activo = 0;
	if ($('#activo').prop('checked')) {
  	activo  = 1;
	}


	//Get the value from form if edit or insert
	let process=$('#process').val();
	 let id=0;
	 let urlprocess=''
	if(process=='insert')
	{
		 urlprocess= 'agregar_impgas.php';
		 id=0;
	}

	if(process=='edited')
	{
	 id=$('#id').val(); ;
	 urlprocess='editar_impgas.php';
	}

	let dataString='process='+process+'&id='+id+'&nombre='+name+'&descripcion='+description;
	dataString += '&valor='+valor+'&activo='+activo;
	if(error==false){
	$.ajax({
		type:'POST',
		url:urlprocess,
		data: dataString,
		dataType: 'json',
		success: function(datax){
			process=datax.process;
			display_notify(datax.typeinfo,datax.msg);
			if(datax.typeinfo == "Success")
			{
				setInterval("reload1();", 1000);
			}
		}
	});
}else{
			 display_notify("Error", "En formulario: "+ array_error.join(",<br>"));
	 }
}

function reload1()
{
	location.href = 'admin_impgas.php';
}
function deleted()
{
	var id = $('#id_imp').val();
	var dataString = 'process=deleted' + '&id=' + id;
	$.ajax({
		type : "POST",
		url : "borrar_impgas.php",
		data : dataString,
		dataType : 'json',
		success : function(datax) {
			display_notify(datax.typeinfo, datax.msg);
			if(datax.typeinfo == "Success")
			{
				setInterval("reload1();", 1000);
			}
			$('#deleteModal').hide();
		}
	});
}

$(document).on("keyup",".modal-body #nombre, #descripcion",function(e){
  if(e.keyCode !=13)
	{
	  $(this).val($(this).val().toUpperCase());
	}
});
