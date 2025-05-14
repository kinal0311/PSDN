 var fileInput= document.getElementById('upload_profile_photo');
 fileInput.addEventListener('change', dealer_photo_upload);


var fileInput= document.getElementById('upload_gst_certificate');
fileInput.addEventListener('change', upload_gst_certificate);

var fileInput= document.getElementById('upload_id_proof');
fileInput.addEventListener('change', upload_id_proof);

var fileInput= document.getElementById('upload_photo_personal');
fileInput.addEventListener('change', upload_photo_personal);

var fileInput= document.getElementById('upload_pan_card');
fileInput.addEventListener('change', upload_pan_card);

var fileInput= document.getElementById('upload_cancelled_cheque_leaf');
fileInput.addEventListener('change', upload_cancelled_cheque_leaf);

function phoneNumberValidation(e, th) {
	var fileInputnew = document.getElementById('phone');
	var finalNumberResult = fileInputnew.value;
	var phoneno = /^\d{10}$/;
	if (!finalNumberResult.match(phoneno)) {
		swal({
			title: "<bold>Mobile Number Not Valid</bold>",
			text: "Mobile Number Not Valid",
			type: "error",
			html: true
		}, function (isConfirm) {

		});
	}
}

function dealer_photo_upload(e,th) {
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

function upload_gst_certificate(e,th){
		var formData = new FormData($('#form_validation')[0]);
		formData.append('upload_gst_certificate', $('input[type=file]')[0].files[0]);
		$.ajax({
				type: "POST",
				url: SITEURL + "upload/upload_gst_certificate",
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
						$('#gst_certificate').val(data.path);
					}
				},
				error: function() {
					
				}
			});
			return true;
}

function upload_id_proof(e,th){
	var formData = new FormData($('#form_validation')[0]);
	formData.append('upload_id_proof', $('input[type=file]')[1].files[0]);
	$.ajax({
			type: "POST",
			url: SITEURL + "upload/upload_id_proof",
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
					$('#id_proof').val(data.path);
				}
			},
			error: function() {
				
			}
		});
		return true;
}

function upload_photo_personal(e,th){
		var formData = new FormData($('#form_validation')[0]);
		formData.append('upload_photo_personal', $('input[type=file]')[2].files[0]);
		$.ajax({
				type: "POST",
				url: SITEURL + "upload/upload_photo_personal",
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
						$('#photo_personal').val(data.path);
					}
				},
				error: function() {
					
				}
			});
			return true;
}

function upload_pan_card(e,th){
		var formData = new FormData($('#form_validation')[0]);
		formData.append('upload_pan_card', $('input[type=file]')[3].files[0]);
		$.ajax({
				type: "POST",
				url: SITEURL + "upload/upload_pan_card",
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
						$('#pan_card').val(data.path);
					}
				},
				error: function() {
					
				}
			});
			return true;
}

function upload_cancelled_cheque_leaf(e,th){
		var formData = new FormData($('#form_validation')[0]);
		formData.append('upload_cancelled_cheque_leaf', $('input[type=file]')[4].files[0]);
		$.ajax({
				type: "POST",
				url: SITEURL + "upload/upload_cancelled_cheque_leaf",
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
						$('#cancelled_cheque_leaf').val(data.path);
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
				
				if(data.mobileError)
				{					
					swal({
						title: "<bold>Error Found</bold>",
						text: 'Mobile Number Not Valid',
						type: "error",
						html: true
					}, function (isConfirm) {
						
					});				
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
	if ($('[name=user_type]').val() == 1) {
		$('#fitment_access_div').show();
	} else {
		$('#fitment_access_div').hide();
		$('#fitment_access_no').prop('checked', true);
	}

	$('[name=user_type]').on('change',function(){
		if ($('[name=user_type]').val() == 1) {
			$('#fitment_access_div').show();
		} else {
			$('#fitment_access_div').hide();
			$('#fitment_access_no').prop('checked', true);
		}
	});


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
