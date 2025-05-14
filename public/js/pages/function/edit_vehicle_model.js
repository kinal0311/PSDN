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
			
            $.post(SITEURL + "admin/update_vehicle_model_records", $('#form_validation').serializeArray(), function (data) {
                data = data.replace(/^\s+|\s+$/g, "");
				data=JSON.parse(data);
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

function deleteModelList(model_id) {
    console.log(model_id)
    $.ajax({
        url: SITEURL + "admin/check_model",
        method: "POST",
        data:  { 'model_id': model_id } ,
        success: function (data) {
            data = data.replace(/^\s+|\s+$/g, "");
            data = JSON.parse(data);
            if(data != 0)
                {
                    swal({
                            title: "<bold>ERROR!</bold>",						
                            type: "error",	
                            html: true,
                            text: 'Cannot delete this Model, Certificate already created with this Model',						
                        }, function (isConfirm) {
                            if(isConfirm)
                            {
                                // location.reload();
                            }
                        });						
                }
                else{
                    // if(confirm('Are you sure you want to delete it?')) 
                    
                    Swal.fire({
                    title: 'Enter your password',
                    input: 'password',
                    inputPlaceholder: 'Please Enter The Delete Password',
                    showCancelButton: true,
                    inputAttributes: {
                    autocapitalize: 'off',
                    autocomplete: 'new-password',
                    autocorrect: 'off',
                    customClass: {
                    container: 'swal2-title',
                    title: 'swal2-title',
                    input: 'swal2-title',
                    confirmButton: 'swal2-title'
                  }
                },
            preConfirm: (password) => {
            console.log(password);
            return new Promise((resolve) => {
                // You can perform additional validation or checks here
                resolve(password);
              });
            }
            }).then((result) => {
                if (result.isConfirmed) {
                        const password = result.value;
                        // Use the password as needed
                        if (password == null) {
                            Swal.fire("Error!","Please Enter The Password!")
                            return false;
                        }

                        if (password === "") {
                            Swal.fire("Error!","Please Enter The Password!");
                        return false
                        }
                        if (password != "" && password != null) {
                        // return true;
                        $.post(SITEURL + "admin/delete_model", { 'model_id': model_id ,"password":password}, function (data) {
                            data = data.replace(/^\s+|\s+$/g, "");
                            data = JSON.parse(data);
                            if (data.error) {
                                // showWithTitleMessage(data.error, '');
                                Swal.fire("Error!",data.message)
                            }
                            if (data.success) {
                                swal({
                                    title: "<bold>SUCCESS</bold>",						
                                    type: "success",	
                                    html: true,
                                    text: 'Model deleted successfully',						
                                }, function (isConfirm) {
                                if(isConfirm)
                                {
                                    window.location.href = SITEURL+'admin/vehicle_model_list';
                                }
                                });	
                            }
                            })
                            }
                    }
                })
                    }
    },
    error: function() {
        alert('Error saving data!');
    }
    });
}

