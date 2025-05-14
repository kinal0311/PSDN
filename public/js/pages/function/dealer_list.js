function filterRecordsByPage(page)
{
	$('#searchfilter').find('#offset').val(page);
	$('#searchfilter').find('#search').val($('#search').val());
	$('#searchfiltersubmit').trigger('click')
	return true;
}

$(function(){
	 $('#start_date,#end_date').bootstrapMaterialDatePicker({
			format: 'YYYY-MM-DD',
			clearButton: true,
			weekStart: 1,
			time: false
	 });
})


$(document).ready(function(){
	$('[name=distributor_id]').on('change',function(){
		var value=$(this).val();
		if(value==='')
		{
			return true;
		}
		// console.log("haiii"+value)
		$.post(SITEURL+"admin/fetch_dealer_list_by_distributor",{'distributor_id':value},function(data){
            data = data.replace(/^\s+|\s+$/g,"");
            data=JSON.parse(data);
            // console.log(data)
            if(data.dealer_list && data.dealer_list.length===0)
			{
				showWithTitleMessage('No Records Found',"Selected Distributor Doesn't have any Dealer.");
			}
				var html='';				
					html='<option value="" selected="selected">--Select dealer--</option>';				
			if(data.dealer_list && data.dealer_list.length)
			{
				$.each(data.dealer_list,function(resKey,resValue){
					html+='<option value="'+resValue.user_id+'">'+resValue.user_name+'</option>';
				});
			}
			$('#dealer_id').html(html);			
			$('#dealer_id').selectpicker('refresh');

		});
	});
	
	
// 	$('[name=state_id]').on('change', function () {
// 		var value = $(this).val();
// 		if (value === '') {
// 			return true;
// 		}
// 		// console.log("data", value);
// 		$.post(SITEURL + "admin/getRTOByStateById", { 'id': value }, function (data) {
// 			data = data.replace(/^\s+|\s+$/g, "");
// 			data = JSON.parse(data);
// 			if (data.rto_list && data.rto_list.length === 0) {
// 				showWithTitleMessage('No Records Found', "Selected State Doesn't have any Rto records.");
// 			}
// 			var html = '';
// 			html = '<option value="" selected="selected">--Select RTO--</option>';
// 			if (data.rto_list && data.rto_list.length) {
// 				$.each(data.rto_list, function (resKey, resValue) {
// 					html += '<option value="' + resValue.rto_no + '">' + resValue.rto_number + ' - ' + resValue.rto_place + '</option>';
// 				});
// 			}
// 			$('#veh_rto_no').html(html);
// 			$('#veh_rto_no').selectpicker('refresh');
// 		});
	
// 	});

    $('[name=state_id]').on('change', function () {
    		var value = $(this).val();
    		if (value === '') {
    			return true;
    		}
    		// console.log("data", value);
    		$.post(SITEURL + "admin/getRTOByStateById", { 'id': value }, function (data) {
    			data = data.replace(/^\s+|\s+$/g, "");
    			data = JSON.parse(data);
    			if (data.rto_list && data.rto_list.length === 0) {
    				showWithTitleMessage('No Records Found', "Selected State Doesn't have any Rto records.");
    			}
    			var html = '';
    			html = '<option value="" selected="selected">--Select RTO--</option>';
    			if (data.rto_list && data.rto_list.length) {
    				$.each(data.rto_list, function (resKey, resValue) {
    					html += '<option value="' + resValue.rto_no + '">' + resValue.rto_place + '_RTO_' + resValue.rto_number  + '</option>';
    				});
    			}
    			$('#veh_rto_no').html(html);
    			$('#veh_rto_no').selectpicker('refresh');
    		});
    	
    	});
	
});

function downloadbutton_old() {
	var url_string = window.location.href.toString(); 
	var url = new URL(url_string);
	var startDate = url.searchParams.get("start_date");
	var endDate = url.searchParams.get("end_date");
	var checkDate = url.searchParams.get("scales");
	var search = url.searchParams.get("search");
	var dealerId = url.searchParams.get("dealer_id");

	//search
	var searchValue = 0;
	if(search==""){
		searchValue = 0;
	}else{
		 searchValue = search;
	}

	//dealer id
	var dealerValue = 0;
	if(dealerId==""){
		dealerValue = 0;
	}else{
		dealerValue = dealerId;
	}

	// console.log(searchValue,"searchValue")
	// console.log(dealerValue,"dealerValue")

	if(checkDate=="ON" || checkDate=="on"){
		// console.log(startDate,"start_date");
		// console.log(endDate,"endDate");
	
    txtfromdate_arr = startDate.split("-");
    txtfromdate = txtfromdate_arr[0] + "-" + txtfromdate_arr[1] + "-" + txtfromdate_arr[2];
    txttodate_arr = endDate.split("-");
    txttodate = txttodate_arr[0] + "-" + txttodate_arr[1] + "-" + txttodate_arr[2];
    var fd = new FormData();
    fd.append("type", "certificatelist");
    fd.append("start_date", txtfromdate);
    fd.append("end_date", txttodate);
    fd.append("dealer", dealerValue);
    fd.append("search", searchValue);

    $.ajax({
        url: SITEURL + "admin/downloadexcelassign",
        type: "POST",
        cache: !1,
        data: fd,
        processData: !1,
        contentType: !1,
        success: function(datasresult) {
            window.location.href = SITEURL + "admin/downloadexcel"
        }
    })
	}
	else{
		showWithTitleMessage('Please select Date filter for download Excel');
	}
}

function downloadbutton() {
	
	var url_string = window.location.href.toString(); 
	var url = new URL(url_string);
	var startDate = url.searchParams.get("start_date");
	var endDate = url.searchParams.get("end_date");
	var checkDate = url.searchParams.get("scales");
	var search = url.searchParams.get("search");
	if (userType =="1") {
		var dealerId = userId;
	}else{
		var dealerId = url.searchParams.get("dealer_id");
	}
	if (userType =="2") {
		var distributorId = userId;
	}else{
		var distributorId = url.searchParams.get("distributor_id");
		// var dealerId = url.searchParams.get("dealer_id");
	}
	var stateId = url.searchParams.get("state_id");
    var rtoId =  url.searchParams.get("veh_rto_no");
	//search
	var searchValue = 0;
	if(search==""){
		searchValue = 0;
	}else{
		 searchValue = search;
	}

	//dealer id
	var dealerValue = 0;
	if(dealerId==""){
		dealerValue = 0;
	}else{
		dealerValue = dealerId;
	}

	var distributorValue = 0;
	if(distributorId==""){
		distributorValue = 0;
	}else{
		distributorValue = distributorId;
	}

	var stateValue = 0;
	if(stateId==""){
		stateValue = 0;
	}else{
		stateValue = stateId;
	}

    var fd = new FormData();
// 	if(checkDate=="ON" || checkDate=="on"){
//         txtfromdate_arr = startDate.split("-");
//         txtfromdate = txtfromdate_arr[0] + "-" + txtfromdate_arr[1] + "-" + txtfromdate_arr[2];
//         txttodate_arr = endDate.split("-");
//         txttodate = txttodate_arr[0] + "-" + txttodate_arr[1] + "-" + txttodate_arr[2];
//         fd.append("type", "certificatelist");
//         fd.append("start_date", txtfromdate);
//         fd.append("end_date", txttodate);
//         fd.append("type", "certificatelist");
//         fd.append("dealer", dealerValue);
//         fd.append("search", searchValue);
//         fd.append("distributorId", distributorValue);
//         fd.append("stateId", stateValue);
//         window.open("http://www.psdn.live/reports/excelDownload?type=certificatelist&start_date=" + txtfromdate + "&end_date=" + txttodate + "&dealer=" + dealerValue + "&search=" + searchValue + "&distributorId=" + distributorValue + "&stateId=" + stateValue+"&header=UFNETkV4Y2Vs", '_blank')
// 	}
// 	else{
	    fd.append("type", "certificatelist");
        fd.append("dealer", dealerValue);
        fd.append("search", searchValue);
        fd.append("distributorId", distributorValue);
        fd.append("stateId", stateValue);
        fd.append("rtoId", rtoId);
        // console.log("http://www.psdn.live/reports/excelDownload?type=certificatelist&start_date=&end_date=&dealer=" + dealerValue + "&search=" + searchValue + "&distributorId=" + distributorValue + "&stateId=" + stateValue+"&header=UFNETkV4Y2Vs", '_blank');
       window.open("http://www.psdn.live/reports/excelDownload?type=certificatelist&start_date=&end_date=&dealer=" + dealerValue + "&search=" + searchValue + "&rtoId=" + rtoId +"&distributorId=" + distributorValue + "&stateId=" + stateValue+"&header=UFNETkV4Y2Vs", '_blank')
// 	}
        
   
    //location.href = "http://www.psdn.live/reports/excelDownload?type=certificatelist&start_date=" + txtfromdate + "&end_date=" + txttodate + "&dealer=" + dealerValue + "&search=" + searchValue + "&distributorId=" + distributorValue + "&stateId=" + stateValue+"&header=UFNETkV4Y2Vs";
    //     $.ajax({
    //         url: "http://www.psdn.live/reports/downloadexcelassign",
    //         type: "POST",
    //         cache: !1,
    //         data: fd,
    //         processData: !1,
    //         contentType: !1,
    //         success: function(datasresult) {
    // 			location.href = "http://www.psdn.live/reports/downloadexcelTech"
    //         }
    //     })
}



// function showConfirmmesage(veh_id, userType) {
// 	// console.log("userType",userType);
// 	// console.log("veh_id",veh_id);
// 	swal({
// 		title: "! Alert",
// 		text: "Please Enter The Delete Password",
// 		type: "input",
// 		showCancelButton: true,
// 		closeOnConfirm: false,
// 		animation: "slide-from-top",
// 		inputPlaceholder: "Password"
// 	},
// 		function (inputValue) {
// 			if (inputValue === null) return false;

// 			if (inputValue === "") {
// 				swal.showInputError("Please Enter The Password!");
// 				return false
// 			}
// 			if (inputValue === "Shivan@2123") {
// 				// return true;
// 				$.post(SITEURL + "apicontroller/delete_entry_list", { 'veh_id': veh_id }, function (data) {
// 					data = data.replace(/^\s+|\s+$/g, "");
// 					data = JSON.parse(data);
// 					if (data.error) {
// 						// showWithTitleMessage(data.error, '');
// 						swal.showInputError(data.message);
// 					}
// 					if (data.success) {
// 						$(document).ready(function () {
// 							toastr.options = {
// 								'closeButton': true,
// 								'debug': false,
// 								'newestOnTop': false,
// 								'progressBar': false,
// 								'positionClass': 'toast-top-right',
// 								'preventDuplicates': false,
// 							}
// 						});

// 						if (data.responseData.result1 == true) {
// 							toastr.success("GPS Data's Deleted Successfully");
// 						} else {
// 							toastr.error("GPS Data's Not Deleted");
// 						}
// 						if (data.responseData.result3 == true) {
// 							toastr.success("Invoice Data's Deleted Successfully");
// 						} else {
// 							toastr.error("Invoice Data's Not Deleted");
// 						}
// 						if (data.responseData.result4 == true) {
// 							toastr.success("Serial Data's Updated Successfully");
// 						} else {
// 							toastr.error("Serial Data's Not Updated");
// 						}
// 						if (data.responseData.result5 == true) {
// 							toastr.success("Vehicle Data's Deleted Successfully");
// 							toastr.success("Upload Documents Deleted Successfully");
// 						} else {
// 							toastr.error("Vehicle Data's Not Deleted");
// 							toastr.error("Upload Documents Not Deleted");
// 						}
// 						// if (data.responseData.result6 == true) {
// 						// 	toastr.success("Upload Documents Deleted Successfully");
// 						// } else {
// 						// 	toastr.error("Upload Documents Not Deleted Successfully");
// 						// }
// 						swal("Deleted!", "Certificate Deleted", "success");
// 						setTimeout(function () {
// 							window.location.href = window.location.href;
// 						}, 4000);
// 					}
// 				});
// 			} else {
// 				swal.showInputError("Password Incorrect!");
// 			}
// 		});
// }

function showConfirmmesage(veh_id) {
	// console.log("userType",userType);
	// console.log("veh_id",veh_id);
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
							$.post(SITEURL + "apicontroller/delete_entry_list", { 'veh_id': veh_id ,"password":password}, function (data) {
								data = data.replace(/^\s+|\s+$/g, "");
								data = JSON.parse(data);
								if (data.error) {
									// showWithTitleMessage(data.error, '');
									Swal.fire("Error!",data.message)
								}
								if (data.success) {
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

									if (data.responseData.result1 == true) {
										toastr.success("GPS Data's Deleted Successfully");
									} else {
										toastr.error("GPS Data's Not Deleted Successfully");
									}
									if (data.responseData.result2 == true) {
										toastr.success("Invoice Data's Deleted Successfully");
									} else {
										toastr.error("Invoice Data's Not Deleted Successfully");
									}
									if (data.responseData.result3 == true) {
										toastr.success("Serial Data's Updated Successfully");
									} else {
										toastr.error("Serial Data's Not Updated Successfully");
									}
									if (data.responseData.result4 == true) {
										toastr.success("Vehicle Data's Deleted Successfully");
									} else {
										toastr.error("Vehicle Data's Not Deleted Successfully");
									}
									if (data.responseData.result5 == true) {
										toastr.success("Upload Documents Deleted Successfully");
									} else {
										toastr.error("Upload Documents Not Deleted Successfully");
									}

									Swal.fire(
										'Deleted!',
										'Your file has been deleted.',
										'success'
									  )
									// swal("Deleted!", "Certificate Deleted", "success");
									setTimeout(function () {
										window.location.href = window.location.href;
									}, 4000);
								}
								console.log('Entered password:', password);
								})
								}
                        }
            })
}
	

function changeStatus(status)
{

}