

function validateSignInForm()
{
    $('#sign_in').validate({
		
        highlight: function (input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.input-group').append(error);
        }, 
		submitHandler: function(form) {			
			
			window.localStorage.setItem('phone_number',"");
			window.localStorage.setItem('password_value',"");
			// Get Value
			var phone_number=$('#phone_number').val();
			var password_value=$('#password_value').val();
			if($('#rememberme').prop('checked'))
			{
				window.localStorage.setItem('phone_number',phone_number);
				window.localStorage.setItem('password_value',password_value);
			}
			var params={};
			params.phone_number=phone_number;
			params.password_value=password_value;
			$('#erroralert').hide();
			$.post(SITEURL+"admin/verifyuser",params,function(data){
				data = data.replace(/^\s+|\s+$/g, "");
				data = JSON.parse(data);
				console.log(data)
				if(data.error)
				{
					$('#erroralert').show();
					$('#erroralert').find('strong').text(data.error);
				}				
				//Success Response
				if(data.success)
				{
					if(data.redirect)
					{
						window.location.href=SITEURL+data.redirect;
					}
				}
				if(data.expiry){
					swal({
						title: "<bold>Alert</bold>",
						type: "warning",
						html: true,
						text: "There are " + data.data + " certificates expiring in the next 45 days. Kindly intimate customers about renewal.",
						showCancelButton: true,
						cancelButtonText: "SKIP", // Set the cancel button text
						confirmButtonText: "VIEW",  // Set the OK button text
					  }, function (isConfirm) {
						if (isConfirm) {
						  window.location.href = SITEURL + 'admin/expiring_list';
						} else {
						  window.location.href = SITEURL + 'admin/dashboard';
						}
					  });
					  
				}
				
			});

		}
    });
};

$(document).ready(function(){
	
	$('#phone_number').val(window.localStorage.getItem('phone_number'));
	$('#password_value').val(window.localStorage.getItem('password_value'));			
	validateSignInForm();
})

