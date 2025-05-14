var CACHE={};


function dealer_photo_upload(e,name)
{
		var formData = new FormData($('#form_validation')[0]);
		if(name=='upload_governer_photo')
		{
			formData.append('upload_profile_photo', $('input[type=file]')[3].files[0]);
		}
		if(name=='upload_vehicle_photo')
		{
			formData.append('upload_profile_photo', $('input[type=file]')[4].files[0]);
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
							$('#veh_speed_governer_photo').val(data.path);
						}
						if(name=='upload_vehicle_photo')
						{
							$('#veh_photo').val(data.path);
						}
						
					}
				},
				error: function() {
					
				}
			});
			return true;
}

function vehicle_owner_id_proof(e,name)
{
		var formData = new FormData($('#form_validation')[0]);		
		if(name=='vehicle_owner_id_proof'){
			formData.append('vehicle_owner_id_proof', $('input[type=file]')[0].files[0]);
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
			formData.append('vehicle_owners', $('input[type=file]')[1].files[0]);
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
			formData.append('rc_book', $('input[type=file]')[2].files[0]);
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

	
     

});
$(document).ready(function(){
	
 $('.datetimepicker').bootstrapMaterialDatePicker({
        format: 'YYYY-MM-DD',
        clearButton: true,
        weekStart: 1,
        time: false
 });
 
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
			
			
			
			swal({
							title: "<bold>Success</bold>",						
							type: "success",	
							html: true,
							text: "New Records has been created successfully.",						
						}, function (isConfirm) {
							if(isConfirm)
							{
								
								form.submit();
								
								
							}
								
						});
			
			return false;
		}

  });		
	
});




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


	$('#veh_owner_phone').on('keydown',function(){
		var value=$(this).val();
		
		if(value.length <= 3)
		{
			return true;
		}

		$.post(SITEURL+"admin/fetch_customer_by_phone",{'phone':value},function(data){
			data=JSON.parse(data);

			if(data.customer){
				$("#veh_owner_id").val(data.customer.c_customer_id);
				$("#veh_owner_name").val(data.customer.c_customer_name);
				$("#veh_address").val(data.customer.c_address);
				$("#veh_owner_email").val(data.customer.c_email);
			}else{
				$("#veh_owner_id").val('');
				$("#veh_owner_name").val('');
				$("#veh_address").val('');
			}

			$("#veh_owner_id,#veh_owner_name,#veh_address").trigger("change");
			
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


function resetall()
{
	window.localStorage.removeItem('createentry');
	CACHE={};
	window.location.href=window.location.href;

}

function saveCache()
{
	window.localStorage.setItem('createentry',JSON.stringify(CACHE));	
}

function verifyCache()
{
	var cacheVariable=window.localStorage.getItem('createentry');	
	if(cacheVariable && cacheVariable !=undefined &&  cacheVariable !=null)
	{
		var parse=JSON.parse(cacheVariable);
		if(typeof(parse)==='object')
		{
			$.each(parse,function(key,value){
				$('[name='+key+']').val(value)
				CACHE[key]=value;
			});
		}
		saveCache();
	}
}

$(document).ready(function(){

	$('input,textarea').on('blur change keyup',function(){
		var id=$(this).attr('name');
		var value=$(this).val();
		if(value && ''+value.length>0)
		{
			if(id !='upload_governer_photo' && id !='upload_vehicle_photo' &&
				id !='veh_make_no' && id !='veh_model_no' &&
				 id !='veh_serial_no' && 
					 id !='veh_tac' 
				)
			{
				CACHE[id]=$(this).val();
			}
		}
		saveCache();
	});
	$('select').on('blur change keyup',function(){
		var id=$(this).attr('name');
		var value=$(this).val();
		if(value && ''+value.length>0)
		{
			if(''+id !='veh_company_id' && id !='upload_governer_photo' && id !='upload_vehicle_photo' &&
				id !='veh_make_no' && id !='veh_model_no' &&
				 id !='veh_serial_no' && 
					 id !='veh_tac' )
			{
				CACHE[id]=$(this).val();
			}
		}
		saveCache();
	});

	verifyCache();
});