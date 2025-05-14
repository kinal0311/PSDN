

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
			
			window.localStorage.setItem('rto_number',"");
			window.localStorage.setItem('rto_pwd',"");
			// Get Value
			var rto_number=$('#rto_number').val();
			var rto_pwd=$('#rto_pwd').val();
			if($('#rememberme').prop('checked'))
			{
				window.localStorage.setItem('rto_number',rto_number);
				window.localStorage.setItem('rto_pwd',rto_pwd);
			}
			var params={};
			params.rto_number=rto_number;
			params.user_type='rto';
			params.rto_pwd=rto_pwd;
			$('#erroralert').hide();
			$.post(SITEURL+"admin/verifyrto",params,function(data){
				data=JSON.parse(data);
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
				
			});

		}
    });
};

$(document).ready(function(){
	
	$('#rto_number').val(window.localStorage.getItem('rto_number'));
	$('#rto_pwd').val(window.localStorage.getItem('rto_pwd'));			
	validateSignInForm();
})

