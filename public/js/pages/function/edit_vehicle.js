 var fileInput= document.getElementById('upload_governer_photo');
  fileInput.addEventListener('change', function(e){
	 dealer_photo_upload(e,'upload_governer_photo')
 },false);
 var fileInput1= document.getElementById('upload_vehicle_photo');
 fileInput1.addEventListener('change', function(e){
	 dealer_photo_upload(e,'upload_vehicle_photo')
 },false);
// var fileInput1= document.getElementById('vehicle_owner_id_proof');
//  fileInput1.addEventListener('change', function(e){
// 	 vehicle_owner_id_proof(e,'vehicle_owner_id_proof')
//  },false);
//  var fileInput1= document.getElementById('vehicle_owners');
//  fileInput1.addEventListener('change', function(e){
// 	 vehicle_owners_photo_upload(e,'vehicle_owners')
//  },false);
//  var fileInput1= document.getElementById('rc_book');
//  fileInput1.addEventListener('change', function(e){
// 	 rc_book_photo_upload(e,'rc_book')
//  },false);
 
function dealer_photo_upload(e,name)
{
		var formData = new FormData($('#form_validation')[0]);
		if(name=='upload_governer_photo')
		{
			// formData.append('upload_profile_photo', $('input[type=file]')[3].files[0]);
			formData.append('upload_profile_photo', $('input[type=file]')[0].files[0]);
		}
		if(name=='upload_vehicle_photo')
		{
			// formData.append('upload_profile_photo', $('input[type=file]')[4].files[0]);
			formData.append('upload_profile_photo', $('input[type=file]')[1].files[0]);
		}
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
				    console.log("msg", msg);
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
						if(name=='upload_governer_photo')
						{
						    console.log("data.path",data.path);
							$('#veh_speed_governer_photo').val(data.path);
						}
						if(name=='upload_vehicle_photo')
						{
							$('#veh_photo').val(data.path);
						}
						
					}
				},
				error: function(err) {
					console.log("err",err);
				}
			});
			return true;
}

function vehicle_owner_id_proof(e,name)
{
		var formData = new FormData($('#form_validation')[0]);		
		if(name=='vehicle_owner_id_proof'){
			// formData.append('vehicle_owner_id_proof', $('input[type=file]')[0].files[0]);
			formData.append('vehicle_owner_id_proof', $('input[type=file]')[2].files[0]);
		}

	
		$.ajax({
				type: "POST",
				url: SITEURL + "upload/vehicle_owner_id_proof",
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
						$('#vehicle_owner_id_proof_photo').val(data.path);
					}
				},
				error: function() {
					
				}
			});
			return true;
}

function vehicle_owners_photo_upload(e,name)
{
		var formData = new FormData($('#form_validation')[0]);
		
		if(name=='vehicle_owners')
		{
			// formData.append('vehicle_owners', $('input[type=file]')[1].files[0]);
			formData.append('vehicle_owners', $('input[type=file]')[3].files[0]);
		}
		
	
		$.ajax({
				type: "POST",
				url: SITEURL + "upload/vehicle_owners_photo_upload",
				data: formData,
				//use contentType, processData for sure.
				contentType: false,
				processData: false,
				beforeSend: function() {
					
	
				},
			success: function (data) {
				data = data.replace(/^\s+|\s+$/g, "");
					data=JSON.parse(data);
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
						$('#vehicle_owners_photo').val(data.path);
					}
				},
				error: function() {
					
				}
			});
			return true;
}

function rc_book_photo_upload(e,name)
{
		var formData = new FormData($('#form_validation')[0]);
		
		if(name=='rc_book')
		{
			// formData.append('rc_book', $('input[type=file]')[2].files[0]);
			formData.append('rc_book', $('input[type=file]')[4].files[0]);
		}
	
		$.ajax({
				type: "POST",
				url: SITEURL + "upload/rc_book_photo_upload",
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
						$('#rc_book_photo').val(data.path);
					}
				},
				error: function() {
					
				}
			});
			return true;
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
			$.post(SITEURL+"admin/count_vehicle_records",$('#form_validation').serializeArray(),function(data){
             data = data.replace(/^\s+|\s+$/g, "");
             data = JSON.parse(data);
             if(data.count != 0)
                 {
					swal({
						title: "<bold>ALERT!</bold>",						
						type: "warning",
						showCancelButton: true,
                        confirmButtonText: 'OK',	
						html: true,
						text: 'The change will be reflected to all your certificates',						
					}, function (isConfirm) {
					if(isConfirm)
					{
						$.post(SITEURL+"admin/update_vehicle_records",$('#form_validation').serializeArray(),function(data){
							// data=JSON.parse(data.trim());
							data = data.trim();
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
										text: "Entry Records has been modified successfully.",						
									}, function (isConfirm) {
										if(isConfirm)
										{
											window.location.href=SITEURL+data.redirect;
										}
									});						
								}
							}
							
						});
			        }					
		        })
	        }
			else{
				$.post(SITEURL+"admin/update_vehicle_records",$('#form_validation').serializeArray(),function(data){
					// data=JSON.parse(data.trim());
					data = data.trim();
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
								text: "Entry Records has been modified successfully.",						
							}, function (isConfirm) {
								if(isConfirm)
								{
									window.location.href=SITEURL+data.redirect;
								}
							});						
						}
					}
					
				});

			}
		})
	


			
			
			
			return false;
		}
    });  

});

//     $('#form_validation').validate({
      
//         highlight: function (input) {
//             $(input).parents('.form-line').addClass('error');
//         },
//         unhighlight: function (input) {
//             $(input).parents('.form-line').removeClass('error');
//         },
//         errorPlacement: function (error, element) {
//             $(element).parents('.form-group').append(error);
//         },
// 		submitHandler: function(form) {	
			
// 			$.post(SITEURL+"admin/update_vehicle_records",$('#form_validation').serializeArray(),function(data){
// 				// data=JSON.parse(data.trim());
// 				data = data.trim();
// 				data = data.replace(/^\s+|\s+$/g, "");
// 				data = JSON.parse(data);
// 				if(data.error)
// 				{					
// 					showWithTitleMessage(data.error,'');
// 				}	
// 				if(data.validation && Object.keys(data.validation).length>0)
// 				{
// 					var words="";
// 					for(var i=0;i<Object.keys(data.validation).length;i++)
// 					{
// 						var Obj=Object.keys(data.validation)[i];
// 						words+=data.validation[Obj]+"<br />";
// 					}
// 					swal({
// 						title: "<bold>Error Found</bold>",
// 						text: words,
// 						type: "error",
// 						html: true
// 					}, function (isConfirm) {
						
// 					});
// 				}	
								
// 				//Success Response
// 				if(data.success)
// 				{
// 					if(data.redirect)
// 					{
// 						swal({
// 							title: "<bold>Success</bold>",						
// 							type: "success",	
// 							html: true,
// 							text: "Entry Records has been modified successfully.",						
// 						}, function (isConfirm) {
// 							if(isConfirm)
// 							{
// 								window.location.href=SITEURL+data.redirect;
// 							}
// 						});						
// 					}
// 				}
				
// 			});
			
			
// 			return false;
// 		}
//     });  

// });

$('#validity_validation').on('change',function(){
	var value=$(this).val();
	console.log(value)
		var validity = this.value; 
		var today    = new Date(); 
	    console.log("validity",validity)
	    console.log("today",today)
		// Calculate the validity time based on the selected value
		if (validity == "1") {
			today.setFullYear(today.getFullYear() + 1);
			var validityTime = today.toISOString().slice(0, 10);
		}
		else if (validity == "2") {
			today.setFullYear(today.getFullYear() + 2);
			var validityTime = today.toISOString().slice(0, 10);
		}
		else if (validity == "") {
		    var validityTime = ""
		}
		document.getElementById('validity_to').value = validityTime; // Set the calculated validity time in the input field
	});
	
$(document).ready(function(){
	
 $('.datetimepicker').bootstrapMaterialDatePicker({
        format: 'YYYY-MM-DD',
        clearButton: true,
        weekStart: 1,
        time: false
 });
	
})


$(document).ready(function(){


	$('[name=veh_make_no]').on('change',function(){
		var value=$(this).val();
		if(value==='')
		{
			return true;
		}
		$.post(SITEURL+"admin/fetch_model_list_by_make",{'veh_make_no':value},function(data){
			data=JSON.parse(data);
			if(data.model_list && data.model_list.length===0)
			{
				showWithTitleMessage('No Records Found',"Selected Make Doesn't have any model records.");
			}
				var html='';				
					html='<option value="" selected="selected">--Select Model--</option>';				
			if(data.model_list && data.model_list.length)
			{
				$.each(data.model_list,function(resKey,resValue){
					html+='<option value="'+resValue.ve_model_id+'">'+resValue.ve_model_name+'</option>';
				});
			}
			$('#veh_model_no').html(html);			
			$('#veh_model_no').selectpicker('refresh');

		});

	});

    $('[name=state]').on('change', function () {
		var value = $(this).val();
		if (value === '') {
			return true;
		}
		// console.log("data", value);
		$.post(SITEURL + "admin/getRTOByStateById", { 'id': value }, function (data) {
			data = JSON.parse(data);
			if (data.rto_list && data.rto_list.length === 0) {
				showWithTitleMessage('No Records Found', "Selected State Doesn't have any Rto records.");
			}
			var html = '';
			html = '<option value="" selected="selected">--Select RTO--</option>';
			if (data.rto_list && data.rto_list.length) {
				$.each(data.rto_list, function (resKey, resValue) {
					html += '<option value="' + resValue.rto_no + '">' + resValue.rto_number + ' - ' + resValue.rto_place + '</option>';
				});
			}
			$('#veh_rto_no').html(html);
			$('#veh_rto_no').selectpicker('refresh');
		});

	});

	$('[name=veh_company_id]').on('change',function(){
		var value=$(this).val();
		if(value==='')
		{
			return true;
		}
		$.post(SITEURL+"admin/fetch_serial_list_by_company",{'veh_company_id':value},function(data){
			data=JSON.parse(data);
			// Serial Number
			if(data.Serial_List && data.Serial_List.length===0)
			{
				showWithTitleMessage('No Records Found',"Serial Numbers are not allocated under you.",);
			}
				var html='';				
					html='<option value="" selected="selected">--Select Serial Number--</option>';				
			if(data.Serial_List && data.Serial_List.length)
			{
				$.each(data.Serial_List,function(resKey,resValue){
					html+='<option value="'+resValue.s_serial_id+'">'+resValue.s_serial_number+'</option>';
				});
			}
			$('#veh_serial_no').html(html);			
			$('#veh_serial_no').selectpicker('refresh');
			// Tac Number
			if(data.Serial_List && data.Serial_List[0])
			{
				var splitTac=data.Serial_List[0]['c_tac_no'].split(',');
				splitTac = splitTac.filter(Boolean)
				var html='';
					html='<option value="" selected="selected">--Select Tac Number--</option>';	
				if(splitTac.length===0)
				{
					showWithTitleMessage('Error Found',"No Tac number available for selected Company",);	
				}else{
					$.each(splitTac,function(resKey,resValue){
						html+='<option value="'+resValue+'">'+resValue+'</option>';
					});
				}
				$('#veh_tac').html(html);			
				$('#veh_tac').selectpicker('refresh');
				
			}

		});

	});
});
