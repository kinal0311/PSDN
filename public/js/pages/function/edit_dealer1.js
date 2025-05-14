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
			
			$.post(SITEURL+"admin/update_dealer_records",$('#form_validation').serializeArray(),function(data){
				data=JSON.parse(data.trim());
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





$(document).ready(function(){


	$('[name=user_types]').on('change',function(){
		var value=$(this).val();
		var user_company_id=$('#user_company_id').val();
		if(user_company_id==='')
		{
			showWithTitleMessage('Please Select Company Name.','');
			$('[name=user_type]').prop('checked',false)
			return false;
		}
		if(value==='')
		{
			return true;
		}
		if(''+value==='2' || ''+value==='0')
		{
			$('#under_by_user').hide();
			$('#user_distributor_id').val(0);
			$('#user_distributor_id').removeAttr('required');
			return true;
		}
		$.post(SITEURL+"admin/fetch_list_of_users",{'user_type':2,'user_company_id':user_company_id,'currentUserID':$('#user_id').val()},function(data){
			data=JSON.parse(data);
			if(data.list && data.list.length===0)
			{
				showWithTitleMessage('No Users Records Found.','');
			}
				var html='';
				if(''+value==='2')
				{
					html='<option value="" selected="selected">--Select Distributor--</option>';
				}else if(''+value==='1')
				{
					html='<option value="">--Select Dealer--</option>';
				}
			if(data.list && data.list.length)
			{
				$.each(data.list,function(resKey,resValue){
					html+='<option value="'+resValue.user_id+'">'+resValue.user_name+'</option>';
				});
			}
			
				$('#under_by_user').show();
				$('#user_distributor_id').attr('required','required');
				$('#user_distributor_id').html(html);			﻿
				$('#user_distributor_id').selectpicker('refresh');	
			
			

		});

	});


});
