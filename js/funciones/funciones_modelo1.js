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
/*
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
*/
function senddata(){
	var modelo=$('#modelo').val();
	var marca=$('#marca').val();
    var process=$('#process').val();
	
	if(process=='insert')
	{
		var id_modelo=0;  
		var urlprocess='agregar_modelo.php';
	}	 
	if(process=='edited'){
		var id_modelo=$('#id_modelo').val();
		var urlprocess='editar_modelo.php';  
	}
	var dataString='process='+process+'&id_modelo='+id_modelo+'&modelo='+modelo+'&marca='+marca;


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
     location.href = 'admin_modelo.php';	
}
function deleted() {
	var id_modelo = $('#id_modelo').val();
	var dataString = 'process=deleted' + '&id_modelo=' + id_modelo;
	$.ajax({
		type : "POST",
		url : "borrar_modelo.php",
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
