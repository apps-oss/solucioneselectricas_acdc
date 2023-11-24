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

});

function senddata(){
	let marca=$('#marca').val();
		let id_marca=0;
    var process=$('#process').val();

	if(process=='insert')
	{

		var urlprocess='agregar_marca.php';
	}
	if(process=='edited'){
		 id_marca=$('#id_marca').val();
		var urlprocess='editar_marca.php';
	}
	var dataString='process='+process+'&id_marca='+id_marca+'&marca='+marca;


			$.ajax({
				type:'POST',
				url:urlprocess,
				data: dataString,
				dataType: 'json',
				success: function(datax)
				{
					process=datax.process;
					display_notify(datax.typeinfo,datax.msg);
					if(datax.typeinfo != "Error")
					{
						setInterval("reload1();", 1000);
					}
				}
			});
}

function reload1()
{
     location.href = 'admin_marca.php';
}
function deleted() {
	var id_marca = $('#id_marca').val();
	var dataString = 'process=deleted' + '&id_marca=' + id_marca;
	$.ajax({
		type : "POST",
		url : "borrar_marca.php",
		data : dataString,
		dataType : 'json',
		success : function(datax)
		{
			display_notify(datax.typeinfo, datax.msg);
			if(datax.typeinfo != "Error")
			{
				setInterval("location.reload();", 1000);
				$('#deleteModal').hide();
			}
		}
	});
}
