$(function () {
	
	$.validator.addMethod('equalValue', function (value, element,param) {
		console.log(param)
		console.log(value)
         return this.optional(element) || value === param; 
    }, "You must enter {0}");
	
    $('#form_validation').validate({
        rules: {
            'checkbox': {
                required: true
            },
            'gender': {
                required: true
            },
			'user_rto': {
                required: true
            }
        },
        highlight: function (input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.form-group').append(error);
        },
		submitHandler: function(form) {	
			console.log(1111111);
			return false;
		}
    });  

});