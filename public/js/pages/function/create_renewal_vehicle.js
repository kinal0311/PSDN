var CACHE={};

 var fileInput= document.getElementById('upload_governer_photo');
  fileInput.addEventListener('change', function(e){
	 dealer_photo_upload(e,'upload_governer_photo')
 },false);
 var fileInput1= document.getElementById('upload_vehicle_photo');
 fileInput1.addEventListener('change', function(e){
	 dealer_photo_upload(e,'upload_vehicle_photo')
 },false);

function dealer_photo_upload(e,name)
{
		var formData = new FormData($('#form_validation')[0]);
		if(name=='upload_governer_photo')
		{
			formData.append('upload_profile_photo', $('input[type=file]')[0].files[0]);
		}
		if(name=='upload_vehicle_photo')
		{
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
			
			$.post(SITEURL+"admin/create_renewal_vehicle_records",$('#form_validation').serializeArray(),function(data){
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
						window.localStorage.removeItem('createentry');
						CACHE={};
						swal({
							title: "<bold>Success</bold>",						
							type: "success",	
							html: true,
							text: "New Records has been created successfully.",						
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


	$('[name=veh_company_id]').on('change',function(){
		var value=$(this).val();
		if(value==='')
		{
			return true;
		}
		$.post(SITEURL+"admin/fetch_company_info",{'veh_company_id':value},function(data){
			data=JSON.parse(data);
			
			// Serial Number
			
			/* STOP VALIDATION - Starts
			
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
			
			 STOP VALIDATION - Ends */
			
			// Tac Number // if( data.Serial_List && data.Serial_List[0]) 
			//showWithTitleMessage('Error Found',data.Serial_List[0]);
			if(data.Serial_List[0])
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