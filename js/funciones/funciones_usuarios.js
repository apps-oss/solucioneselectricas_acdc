$(document).ready(function() {
	if($("#process").val()!="permissions")
	{
		$('#formulario').validate({
			rules: {
				nombre:
				{
					required: true,
				},
				usuario:
				{
					required: true,
				},
				clave1:
				{
					required: true,
				},
				clave2: {
					equalTo: "#clave1",
				},
			},
			messages:
			{
				nombre: "Por favor ingrese el nombre del usuario",
				usuario: "Por favor ingrese el usuario",
				clave1: "Por favor ingrese la clave",
				clave2: "Por favor ingrese la clave, igual al campo Contraseña",
			},
			submitHandler: function (form)
			{
				senddata();
			}
		});
	}
	else
	{
		$("#formulario").submit(function(event){
			senddata();
			event.preventDefault();
		});
	}
	$('.selectt').select2();
	$('#precio').select2();
	$('#usuario').on('keyup', function(evt)
	{
		if(evt.keyCode == 32)
		{
			$(this).val($(this).val().replace(" ",""));
		}
		else
		{
			$(this).val($(this).val().toLowerCase());
		}
	});

	$("#adminc").on("ifChecked", function(event) {
      $(".i-checks").iCheck("check");
      $("#admin").val("1");
    });
    $("#adminc").on("ifUnchecked", function(event) {
      $(".i-checks").iCheck("uncheck");
      $("#admin").val("0");
    });

	$('#admi').on('ifChecked', function(event)
	{
		if($("#process").val() =="permissions")
		{
			$('.i-checks').iCheck('check');
			$('#admin').val("1");
		}
	});
	$('#admi').on('ifUnchecked', function(event)
	{
		if($("#process").val() =="permissions")
		{
			$('.i-checks').iCheck('uncheck');
			$('#admin').val("0");
		}
	});

	$('#activ').on('ifChecked', function(event)
	{
		$('#activo').val("1");
	});
	$('#activ').on('ifUnchecked', function(event)
	{
		$('#activo').val("0");
	});

	$('#preci').on('ifChecked', function(event)
	{
		$('#precio').val("1");
	});
	$('#preci').on('ifUnchecked', function(event)
	{
		$('#precio').val("0");
	});
});



function autosave(val){
	var name=$('#name').val();
	if (name==''|| name.length == 0){
		var	typeinfo="Info";
		var msg="The field name is required";
		display_notify(typeinfo,msg);
		$('#name').focus();
	}
	else{
		senddata();
	}
}

function senddata()
{
	//Get the value from form if edit or insert
	let process=$('#process').val();
	//alert("process"+process);
	let nombre=$('#nombre').val();
	let usuario=$('#usuario').val();
	let clave=$('#clave1').val();
	let clave2=$('#clave2').val();
	let admin=$('#admin').val();
	let id_empleado=$('#id_empleado').val();
	let urlprocess='agregar_usuario.php';
	let id_usuario=0;
	let	myCheckboxes='0';
	let dataString="";
	if(process=='insert'){
		urlprocess='agregar_usuario.php';
		let sucursal=$('#sucursal').val();
		dataString='process='+process+'&nombre='+nombre+'&usuario='+usuario
		dataString+='&clave='+clave+'&admin='+admin+'&id_empleado='+id_empleado;
		dataString+= '&sucursal='+sucursal
	}
	if(process=='edited')
	{
		id_usuario=$('#id_usuario').val();
		let sucursal=$('#sucursal').val();
		urlprocess='editar_usuario.php';
		dataString='process='+process+'&nombre='+nombre+'&usuario='+usuario
		dataString+='&clave='+clave+'&admin='+admin+'&id_usuario='+id_usuario
		dataString+='&id_empleado='+id_empleado+'&sucursal='+sucursal;
		dataString+='&clave2='+clave2
	}
	if(process=='permissions')
	{
		id_usuario=$('#id_usuario').val();
		urlprocess='permiso_usuario.php';
		let myCheckboxes = new Array();
		let cuantos=0;
		let precio = $("#precio").val();
		$("input[name='myCheckboxes']:checked").each(function(index)
		{
			myCheckboxes.push($(this).val());
			cuantos=cuantos+1;
		});
		if (cuantos==0){
			myCheckboxes='0';
		}
		dataString='process='+process+'&admin='+admin+'&precio='+precio
		dataString+='&id_usuario='+id_usuario+'&myCheckboxes='+myCheckboxes+'&qty='+cuantos;

	}
$.ajax({
	type:'POST',
	url:urlprocess,
	data: dataString,
	dataType: 'json',
	success: function(datax)
	{
		process=datax.process;
		display_notify(datax.typeinfo,datax.msg);
		if(datax.typeinfo == "Success")
		{
			setInterval("reload1();", 1500);
		}
	}
});
}
function reload1()
{
	location.href = 'admin_usuarios.php';
}
function deleted()
{
	let id_usuario = $('#id_usuario').val();
	let dataString = 'process=deleted' + '&id_usuario=' + id_usuario;
	$.ajax({
		type : "POST",
		url : "borrar_usuario.php",
		data : dataString,
		dataType : 'json',
		success : function(datax) {
			display_notify(datax.typeinfo, datax.msg);
			if(datax.typeinfo == "Success")
			{
				setInterval("reload1();", 1500);
				$('#deleteModal').hide();
			}
		}
	});
}
//binding event click for button in modal form
$(document).on("click", "#btnDelete", function(event) {
	deleted();
});
// Clean the modal form
$(document).on('hidden.bs.modal', function(e) {
	let target = $(e.target);
	target.removeData('bs.modal').find(".modal-content").html('');
});
