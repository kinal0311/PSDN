 var fileInput= document.getElementById('upload_profile_photo');
 fileInput.addEventListener('change', dealer_photo_upload);

function dealer_photo_upload(e,th)
	{
		var formData = new FormData($('#form_validation')[0]);
		formData.append('upload_profile_photo', $('input[type=file]')[0].files[0]);
		$.ajax({
				type: "POST",
				url: SITEURL + "upload/dealer_profile_photo",
				data: formData,
				//use contentType, processData for sure.
				contentType: false,
				processData: false,
				beforeSend: function() {
					
	
				},
				success: function(msg) {
					data=JSON.parse(msg);
					if(data.fail)
					{						
						swal({
							title: "<bold>Upload Failed</bold>",
							text: data.error,
							type: "error",
							html: true
						}, function (isConfirm) {
							
						});
					}else{data.success}
					{
						$('#profile_photo').val(data.path);
					}
				},
				error: function() {
					
				}
			});
			return true;
	}


$(function () {
	
    $('#form_validation').validate({
        rules: {
           
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
			
			$.post(SITEURL+"admin/update_profile_records",$('#form_validation').serializeArray(),function(data){
				data=JSON.parse(data);
				if(data.error)
				{					
					showWithTitleMessage(data.error,'Error Found');
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
							text: "Details has been Modified Successfully.",						
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