var CACHE={};

//  var fileInput= document.getElementById('upload_governer_photo');
//   fileInput.addEventListener('change', function(e){
// 	 dealer_photo_upload(e,'upload_governer_photo')
//  },false);
//  var fileInput1= document.getElementById('upload_vehicle_photo');
//  fileInput1.addEventListener('change', function(e){
// 	 dealer_photo_upload(e,'upload_vehicle_photo')
//  },false);

function showConfirmmesage(id) {
    swal({
        title: "<bold>ALERT!</bold>",						
        type: "warning",	
        html: true,
        showCancelButton: true,
        text: 'Are you sure you want to delete it',						
    }, function (isConfirm) {
    if(isConfirm)
    {
        $.ajax({
            type: 'POST',
            url: SITEURL + "admin/delete_service_mail",
            data: {'id': id }, 
            success: function(data) {
                    data = data.replace(/^\s+|\s+$/g, "");
                    data = JSON.parse(data);
                    swal({
                        title: "<bold>SUCCESS</bold>",						
                        type: "success",	
                        html: true,
                        text: 'Email address deleted successfully',						
                    }, function (isConfirm) {
                    if(isConfirm)
                    {
                        window.location.href=SITEURL+'admin/service_mail';
                    }
            });	
            }
          });
    }
    });
}

$(function () {

    $('#form_validation').validate({
      
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
			$.post(SITEURL+"admin/add_service_email",$('#form_validation').serializeArray(),function(data){
				console.log("data",data);
				$('[type=submit]').removeAttr('disabled');
				data = data.replace(/^\s+|\s+$/g, "");
				data = JSON.parse(data);
				if(data.error)
				{					
					showWithTitleMessage(data.error,data.message);
				}				
				//Success Response
				if(data.success)
				{
					if(data.redirect)
					{
						window.localStorage.removeItem('createentry');
						CACHE={};
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

