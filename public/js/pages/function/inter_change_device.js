var CACHE={};

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
			$('[type=submit]').attr('disabled','disabled');
			$.post(SITEURL+"admin/update_inter_change_device",$('#form_validation').serializeArray(),function(data){
				$('[type=submit]').removeAttr('disabled');
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
					$(document).ready(function () {
						toastr.options = {
							'closeButton': true,
							'debug': false,
							'newestOnTop': false,
							'progressBar': false,
							'positionClass': 'toast-top-right',
							'preventDuplicates': false,
						}
					});

					if (data.responseData.result1 == true && data.responseData.result2 == true) {
						toastr.success("Serial Number Data's Updated Successfully");
					} else {
						toastr.error("Serial Number Data's Not Updated Successfully");
					}
					if (data.responseData.result3 == true) {
						toastr.success("Vehicle Data's Successfully");
					} else {
						toastr.error("Vehicle Data's Not Deleted Successfully");
					}
					if (data.responseData.result4 == true) {
						toastr.success("Invoice Data's Updated Successfully");
					} else {
						toastr.error("Invoice Data's Not Updated Successfully");
					}
					if (data.responseData.result5 == true && data.responseData.result6 == true) {
						toastr.success("Log Created Successfully");
					} else {
						toastr.error("Log Not Created Successfully");
					}
					if (data.responseData.result7 == true && data.responseData.result8 == true) {
						toastr.success("Fitment Created Successfully");
					} else {
						toastr.error("Fitment Not Created Successfully");
					}
					setTimeout(function () {
						if(data.redirect)
						{
							window.localStorage.removeItem('createentry');
							CACHE={};
							swal({
								title: "<bold>Success</bold>",						
								type: "success",	
								html: true,
								text: "Device Inter Change successfully.",
							}, function (isConfirm) {
								if(isConfirm)
								{
									window.location.href=SITEURL+data.redirect;
								}
							});						
						}
					}, 3000);
					
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

	/*$('[name=veh_company_id]').on('change',function(){*/
	var value = $("#veh_company_id").val();
	var dealerId =  $("#dealer_id").val();
	var serialId =  $("#s_serial_id").val();
	var stateId =  $("#s_state_id").val();
	
	if(value==='')
	{
		return true;
	}
	$.post(SITEURL + "admin/fetch_serial_list_by_companyId_dealerId", { 'veh_company_id': value, 'dealer_id': dealerId, 'serial_id': serialId, 'stateId': stateId }, function (data) {
		data = data.replace(/^\s+|\s+$/g, "");
		data=JSON.parse(data);
		// Serial Number
		if(data.Serial_List && data.Serial_List.length===0)
		{
			showWithTitleMessage('No Records Found',"Serial Numbers are not allocated under you.",);
		}
			var html='';				
				html='<option value="" selected="selected">-- Select Serial Number / IMEI / ICCID --</option>';
		if(data.Serial_List && data.Serial_List.length)
		{
			$.each(data.Serial_List,function(resKey,resValue){
				html+='<option value="'+resValue.s_serial_id+'">'+resValue.s_serial_number +' / '+ resValue.s_imei+' / '+resValue.s_iccid+'</option>';
			});
		}
		$('#veh_serial_no').html(html);			
		$('#veh_serial_no').selectpicker('refresh');
		// Tac Number
		// if(data.Serial_List && data.Serial_List[0])
		// {
		// 	var splitTac=data.Serial_List[0]['c_tac_no'].split(',');
		// 	splitTac = splitTac.filter(Boolean)
		// 	var html='';
		// 		html='<option value="" selected="selected">--Select Tac Number--</option>';	
		// 	if(splitTac.length===0)
		// 	{
		// 		showWithTitleMessage('Error Found',"No Tac number available for selected Company",);	
		// 	}else{
		// 		$.each(splitTac,function(resKey,resValue){
		// 			html+='<option value="'+resValue+'">'+resValue+'</option>';
		// 		});
		// 	}
		// 	$('#veh_tac').html(html);			
		// 	$('#veh_tac').selectpicker('refresh');
			
		// }

	});
});


$(document).ready(function () {
	$('[name=veh_serial_no]').on('change', function () {
		var value = $(this).val();
		if (value === '') {
			return true;
		}
		console.log("data", value);
		$.post(SITEURL + "admin/getSerialDetailById", { 'id': value }, function (data) {
			data = data.replace(/^\s+|\s+$/g, "");
			data = JSON.parse(data);
			if (data.fitment == "0") {
				var html = '';
				html = '<label class="form-label">Fitment is not done, Do you want to Skip the Fitment Entry?</label><br/><input type="radio" name="fitment" id="male" value="Y" class="with-gap" checked><label for="male">Yes</label><input type="radio" name="fitment" id="female" value="N" class="with-gap"><label for="female" class="m-l-20">No</label>';
				// if (data.rto_list && data.rto_list.length) {
				// 	html += '<option value="' + resValue.rto_no + '">' + resValue.rto_number + ' - ' + resValue.rto_place + '</option>';
				// }
				$('#fitment').html(html);	
			}
		});
	});
});

/*});*/


// function resetall()
// {
// 	window.localStorage.removeItem('createentry');
// 	CACHE={};
// 	window.location.href=window.location.href;

// }

// function saveCache()
// {
// 	window.localStorage.setItem('createentry',JSON.stringify(CACHE));	
// }