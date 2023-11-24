$(document).ready(function() {

	$('#formulario').validate({
	    rules: {
                   	modelo: {
                    required: true,
                     },
                    marca: {
                    required: true,
                     },
                 },
                messages: {
				modelo: "Por favor ingrese el modelo",
				marca: "Por seleccione la marca",
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
  $(".selectt").select2();

});

$(document).on('shown.bs.modal', function(e) {
		$("#marca").select2({
		 		dropdownParent: $("#viewModal")
	 	});
});
