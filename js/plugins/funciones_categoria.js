$(document).ready(function() {

//validar los campos del form
$('#formulario').validate({		
	    rules: {
                    nombre: {  
                    required: true,           
                     }, 
                 },
                 highlight: function(element) {
					$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
				},
				success: function(element) {
					element
					.text('OK!').addClass('has-success')
					.closest('.form-group').removeClass('has-error').addClass('has-success');
				},
        submitHandler: function (form) { 
            senddata();
        }
    });
                    


});
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
	var name=$('#nombre').val();  
    var description=$('#descripcion').val();
  
    //Get the value from form if edit or insert
	var process=$('#process').val();
	
    if(process=='insert'){	
		var id_categoria=0;
		var urlprocess='agregar_categoria.php';
	 }
	 
	if(process=='edited'){	
		var id_categoria=$('#id_categoria').val(); ;
		var urlprocess='editar_categoria.php';  
	}

	var dataString='process='+process+'&id_categoria='+id_categoria+'&nombre='+name+'&descripcion='+description;
	//alert(dataString);
			$.ajax({
				type:'POST',
				url:urlprocess,
				data: dataString,			
				dataType: 'json',
				success: function(datax){	
					process=datax.process;
					
						//var maxid=datax.max_id;
						display_notify(datax.typeinfo,datax.msg);	
										
						//$("#submit1").click(function () {
							location.href = 'admin_categoria.php';
						//});	
					   	       
				}
			});          
}

function deleted() {
	var id_categoria = $('#id_categoria').val();
	var dataString = 'process=deleted' + '&id_categoria=' + id_categoria;
	$.ajax({
		type : "POST",
		url : "borrar_categoria.php",
		data : dataString,
		dataType : 'json',
		success : function(datax) {
			display_notify(datax.typeinfo, datax.msg);
			setInterval("location.reload();", 3000);
			$('#deleteModal').hide(); 
		}
	});
}
