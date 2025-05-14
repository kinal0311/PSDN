$(function () {
	
    $('#form_validation').validate({
        rules: {
           
           
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
			
			$.post(SITEURL + "admin/create_new_vehicle_make_records", $('#form_validation').serializeArray(), function (data) {
				data = data.replace(/^\s+|\s+$/g, "");
				data = JSON.parse(data);
				if(data.error)
				{					
					showWithTitleMessage(data.error,'');
				}	
				if(data.validation && Object.keys(data.validation).length>0)
				{
					var words="";
					for(var i=0;i<Object.keys(data.validation).length;i++)
					{
						var Obj=Object.keys(data.validation)[i];
						words+=data.validation[Obj]+"<br />";
					}
					swal({
						title: "<bold>Error Found</bold>",
						text: words,
						type: "error",
						html: true
					}, function (isConfirm) {
						
					});
				}	
								
				//Success Response
				if(data.success)
				{
					if(data.redirect)
					{
						swal({
							title: "<bold>Success</bold>",						
							type: "success",	
							html: true,
							text: data.message,						
						}, function (isConfirm) {
							if(isConfirm)
							{
								window.location.href=SITEURL+data.redirect;
							}
						});						
					}
				}
				
			});
			
			
			return false;
		}
    });  

});

