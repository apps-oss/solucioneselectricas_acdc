$(document).ready(function() {

$('#formulario').validate({		
	    rules: {
                   	marca: {  
                    required: true,           
                     }, 
                 },
                messages: {
				marca: "Por favor ingrese la marca",
				},
				highlight: function(element) {
					$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
				},
				success: function(element) {
					$(element).closest('.form-group').removeClass('has-error').addClass('has-success');
				},
        submitHandler: function (form) { 
            senddata();
        }
    });
                    
});