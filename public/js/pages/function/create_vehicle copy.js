var CACHE = {};

var fileInput = document.getElementById('upload_governer_photo');
fileInput.addEventListener('change', function (e) {
	dealer_photo_upload(e, 'upload_governer_photo')
}, false);
var fileInput1 = document.getElementById('upload_vehicle_photo');
fileInput1.addEventListener('change', function (e) {
	dealer_photo_upload(e, 'upload_vehicle_photo')
}, false);
//  var fileInput1= document.getElementById('vehicle_owner_id_proof');
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


function dealer_photo_upload(e, name) {
	var formData = new FormData($('#form_validation')[0]);
	if (name == 'upload_governer_photo') {
		// formData.append('upload_profile_photo', $('input[type=file]')[3].files[0]);
		formData.append('upload_profile_photo', $('input[type=file]')[0].files[0]);
	}
	if (name == 'upload_vehicle_photo') {
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
		beforeSend: function () {


		},
		success: function (msg) {
			// console.log(msg)
			data = msg.replace(/^\s+|\s+$/g, "");
			data = JSON.parse(data);
			if (data.fail) {
				swal({
					title: "<bold>Upload Failed</bold>",
					text: data.error,
					type: "error",
					html: true
				}, function (isConfirm) {

				});
			} else { data.success }
			{
				if (name == 'upload_governer_photo') {
					$('#veh_speed_governer_photo').val(data.path);
				}
				if (name == 'upload_vehicle_photo') {
					$('#veh_photo').val(data.path);
				}

			}
		},
		error: function () {

		}
	});
	return true;
}

function vehicle_owner_id_proof(e, name) {
	var formData = new FormData($('#form_validation')[0]);
	if (name == 'vehicle_owner_id_proof') {
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
		beforeSend: function () {


		},
		success: function (msg) {
			data = msg.replace(/^\s+|\s+$/g, "");
			data = JSON.parse(data);
			if (data.fail) {
				swal({
					title: "<bold>Upload Failed</bold>",
					text: data.error,
					type: "error",
					html: true
				}, function (isConfirm) {

				});
			} else { data.success }
			{
				$('#vehicle_owner_id_proof_photo').val(data.path);
			}
		},
		error: function () {

		}
	});
	return true;
}

function vehicle_owners_photo_upload(e, name) {
	var formData = new FormData($('#form_validation')[0]);

	if (name == 'vehicle_owners') {
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
		beforeSend: function () {


		},
		success: function (msg) {
			data = msg.replace(/^\s+|\s+$/g, "");
			data = JSON.parse(data);
			if (data.fail) {
				swal({
					title: "<bold>Upload Failed</bold>",
					text: data.error,
					type: "error",
					html: true
				}, function (isConfirm) {

				});
			} else { data.success }
			{
				$('#vehicle_owners_photo').val(data.path);
			}
		},
		error: function () {

		}
	});
	return true;
}

function rc_book_photo_upload(e, name) {
	var formData = new FormData($('#form_validation')[0]);

	if (name == 'rc_book') {
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
		beforeSend: function () {


		},
		success: function (msg) {
			data = msg.replace(/^\s+|\s+$/g, "");
			data = JSON.parse(data);
			if (data.fail) {
				swal({
					title: "<bold>Upload Failed</bold>",
					text: data.error,
					type: "error",
					html: true
				}, function (isConfirm) {

				});
			} else { data.success }
			{
				$('#rc_book_photo').val(data.path);
			}
		},
		error: function () {

		}
	});
	return true;
}




$(function () {

	//     $.post(SITEURL + "admin/getRTOByStateById", { 'id': "36" }, function (data) {
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
	// 	});

	$.post(SITEURL + "admin/getRTOByStateById", { 'id': "36" }, function (data) {
		data = data.replace(/^\s+|\s+$/g, "");
		data = JSON.parse(data);
		if (data.rto_list && data.rto_list.length === 0) {
			showWithTitleMessage('No Records Found', "Selected State Doesn't have any Rto records.");
		}
		var html = '';
		html = '<option value="" selected="selected">--Select RTO--</option>';
		if (data.rto_list && data.rto_list.length) {
			$.each(data.rto_list, function (resKey, resValue) {
				html += '<option value="' + resValue.rto_no + '">' + resValue.rto_place + '_RTO_' + resValue.rto_number + '</option>';
			});
		}
		$('#veh_rto_no').html(html);
		$('#veh_rto_no').selectpicker('refresh');
	
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
		submitHandler: function (form) {
			$('[type=submit]').attr('disabled', 'disabled');
			$.post(SITEURL + "admin/create_new_vehicle_records", $('#form_validation').serializeArray(), function (data) {
				$('[type=submit]').removeAttr('disabled');
				data = data.replace(/^\s+|\s+$/g, "");
				data = JSON.parse(data);
				if (data.error) {
					showWithTitleMessage(data.error, '');
				}
				if (data.mobileError) {
					swal({
						title: "<bold>Error Found</bold>",
						text: 'Mobile Number Not Valid',
						type: "error",
						html: true
					}, function (isConfirm) {

					});
				}
				if (data.validation && Object.keys(data.validation).length > 0) {
					var words = "";
					for (var i = 0; i < Object.keys(data.validation).length; i++) {
						var Obj = Object.keys(data.validation)[i];
						words += data.validation[Obj] + "<br />";
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
				if (data.success) {
					if (data.redirect) {
						window.localStorage.removeItem('createentry');
						CACHE = {};
						swal({
							title: "<bold>Success</bold>",
							type: "success",
							html: true,
							text: "New Records has been created successfully.",
						}, function (isConfirm) {
							if (isConfirm) {
								window.location.href = SITEURL + data.redirect;
							}
						});
					}
				}

			});


			return false;
		}
	});

});
$(document).ready(function () {

	$('.datetimepicker').bootstrapMaterialDatePicker({
		format: 'YYYY-MM-DD',
		clearButton: true,
		weekStart: 1,
		time: false
	});

})

function rcCheckFunction() {
	var checkBox = document.getElementById("scales");
	if (checkBox.checked == true) {
		document.getElementById("veh_rc_no").value = "NEW REGISTRATION";
	} else {
		document.getElementById("veh_rc_no").value = "";
	}
}

$(document).ready(function () {


	$('[name=veh_make_no]').on('change', function () {
		var value = $(this).val();
		if (value === '') {
			return true;
		}
		$.post(SITEURL + "admin/fetch_model_list_by_make", { 'veh_make_no': value }, function (data) {
			data = data.replace(/^\s+|\s+$/g, "");
			data = JSON.parse(data);
			if (data.model_list && data.model_list.length === 0) {
				showWithTitleMessage('No Records Found', "Selected Make Doesn't have any model records.");
			}
			var html = '';
			html = '<option value="" selected="selected">--Select Vehicle Model--</option>';
			if (data.model_list && data.model_list.length) {
				$.each(data.model_list, function (resKey, resValue) {
					html += '<option value="' + resValue.ve_model_id + '">' + resValue.ve_model_name + '</option>';
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
			data = data.replace(/^\s+|\s+$/g, "");
			data = JSON.parse(data);
			if (data.rto_list && data.rto_list.length === 0) {
				showWithTitleMessage('No Records Found', "Selected State Doesn't have any Rto records.");
			}
			var html = '';
			html = '<option value="" selected="selected">--Select RTO--</option>';
			if (data.rto_list && data.rto_list.length) {
				$.each(data.rto_list, function (resKey, resValue) {
					html += '<option value="' + resValue.rto_no + '">' + resValue.rto_place + '_RTO_' + resValue.rto_number + '</option>';
				});
			}
			$('#veh_rto_no').html(html);
			$('#veh_rto_no').selectpicker('refresh');
		});
		
		// console.log("data", value);
		var value = $("#veh_company_id").val();
		var state_id = $(this).val();
		if (value === '' || state_id == '') {
			return true;
		}
		$.post(SITEURL + "admin/fetch_serial_list_by_company_and_state", { 'veh_company_id': value, 'state_id':state_id }, function (data) {
			data = data.replace(/^\s+|\s+$/g, "");
			data = JSON.parse(data);
			// Serial Number
			if (data.Serial_List && data.Serial_List.length === 0) {
				showWithTitleMessage('No Records Found', "Serial Numbers are not allocated under you.",);
			}
			var html = '';
			html = '<option value="" selected="selected">--Select Serial Number / IMEI / ICCID--</option>';
			if (data.Serial_List && data.Serial_List.length) {
				$.each(data.Serial_List, function (resKey, resValue) {
					html += '<option value="' + resValue.s_serial_id + '">' + resValue.s_serial_number + ' / ' + resValue.s_imei + ' / ' + resValue.s_iccid + '</option>';
				});
			}
			$('#veh_serial_no').html(html);
			$('#veh_serial_no').selectpicker('refresh');
			// Tac Number
			if (data.Serial_List && data.Serial_List[0]) {
				var splitTac = data.Serial_List[0]['c_tac_no'].split(',');
				splitTac = splitTac.filter(Boolean)
				var html = '';
				html = '<option value="" selected="selected">--Select Tac Number--</option>';
				if (splitTac.length === 0) {
					showWithTitleMessage('Error Found', "No Tac number available for selected Company",);
				} else {
					$.each(splitTac, function (resKey, resValue) {
						html += '<option value="' + resValue + '">' + resValue + '</option>';
					});
				}
				$('#veh_tac').html(html);
				$('#veh_tac').selectpicker('refresh');
	
			}
	
		});
		// $.post(SITEURL + "admin/fetch_serial_list_by_company_and_state", { 'veh_company_id': value, 'state_id':state_id }, function (data) {
		// 	data = data.replace(/^\s+|\s+$/g, "");
		// 	data = JSON.parse(data);
		// 	if (data.rto_list && data.rto_list.length === 0) {
		// 		showWithTitleMessage('No Records Found', "Selected State Doesn't have any Rto records.");
		// 	}
		// 	var html = '';
		// 	html = '<option value="" selected="selected">--Select RTO--</option>';
		// 	if (data.rto_list && data.rto_list.length) {
		// 		$.each(data.rto_list, function (resKey, resValue) {
		// 			html += '<option value="' + resValue.rto_no + '">' + resValue.rto_place + '_RTO_' + resValue.rto_number + '</option>';
		// 		});
		// 	}
		// 	$('#veh_rto_no').html(html);
		// 	$('#veh_rto_no').selectpicker('refresh');
		// });
	});

	// 	$('[name=state]').on('change', function () {
	// 		var value = $(this).val();
	// 		if (value === '') {
	// 			return true;
	// 		}
	// 		// console.log("data", value);
	// 		$.post(SITEURL + "admin/getRTOByStateById", { 'id': value }, function (data) {
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

	$('#scales').on('change', function () {
		var newRegistrationCheckbox = document.getElementById('scales');
		var dropdown = $('#validity_validation');
		if (newRegistrationCheckbox.checked) {
			dropdown.val("2").selectpicker('refresh');
			console.log(dropdown.value)

			var today = new Date();
			today.setFullYear(today.getFullYear() + 2);
			var validityTime = today.toISOString().slice(0, 10);
			document.getElementById('validity_to').value = validityTime;

		}
		else {
			dropdown.val("").selectpicker('refresh');
			document.getElementById('validity_to').value = '';
		}
	});


	$('#validity_validation').on('change', function () {
		var value = $(this).val();
		console.log(value)
		//handle validity to period
		var validity = this.value;
		var today = new Date();
		console.log("validity", validity)
		console.log("today", today)
		// Calculate the validity time based on the selected value
		if (validity == "1") {
			today.setFullYear(today.getFullYear() + 1);
			var validityTime = today.toISOString().slice(0, 10);
		} else if (validity == "2") {
			today.setFullYear(today.getFullYear() + 2);
			var validityTime = today.toISOString().slice(0, 10);
		}
		else if (validity == "") {
			var validityTime = ""
		}
		document.getElementById('validity_to').value = validityTime; // Set the calculated validity time in the input field
	});

	$('#veh_owner_phone').on('keyup', function () {
		var value = $(this).val();
		if (value.length != 10) {
			$("#veh_owner_id").val('');
			$("#veh_owner_name").val('');
			$("#veh_address").val('');
			$("#veh_owner_email").val('');
			$("#veh_owner_id,#veh_owner_name,#veh_address").trigger("change");
			return true;
		} else {
			$.post(SITEURL + "admin/fetch_customer_by_phone", { 'phone': value }, function (data) {
				data = data.replace(/^\s+|\s+$/g, "");
				data = JSON.parse(data);
				if (data.customer) {
					$("#veh_owner_id").val(data.customer.c_customer_id);
					$("#veh_owner_name").val(data.customer.c_customer_name);
					$("#veh_address").val(data.customer.c_address);
					$("#veh_owner_email").val(data.customer.c_email);
				} else {
					$("#veh_owner_id").val('');
					$("#veh_owner_name").val('');
					$("#veh_address").val('');
				}

				$("#veh_owner_id,#veh_owner_name,#veh_address").trigger("change");

			});
		}
	});


	/*$('[name=veh_company_id]').on('change',function(){*/
	var value = $("#veh_company_id").val();
	var state_id = $("#state").val();
	if (value === '' || state_id == '') {
		return true;
	}
	$.post(SITEURL + "admin/fetch_serial_list_by_company_and_state", { 'veh_company_id': value, 'state_id':state_id }, function (data) {
		data = data.replace(/^\s+|\s+$/g, "");
		data = JSON.parse(data);
		// Serial Number
		if (data.Serial_List && data.Serial_List.length === 0) {
			showWithTitleMessage('No Records Found', "Serial Numbers are not allocated under you.",);
		}
		var html = '';
		html = '<option value="" selected="selected">--Select Serial Number / IMEI / ICCID--</option>';
		if (data.Serial_List && data.Serial_List.length) {
			$.each(data.Serial_List, function (resKey, resValue) {
				html += '<option value="' + resValue.s_serial_id + '">' + resValue.s_serial_number + ' / ' + resValue.s_imei + ' / ' + resValue.s_iccid + '</option>';
			});
		}
		$('#veh_serial_no').html(html);
		$('#veh_serial_no').selectpicker('refresh');
		// Tac Number
		if (data.Serial_List && data.Serial_List[0]) {
			var splitTac = data.Serial_List[0]['c_tac_no'].split(',');
			splitTac = splitTac.filter(Boolean)
			var html = '';
			html = '<option value="" selected="selected">--Select Tac Number--</option>';
			if (splitTac.length === 0) {
				showWithTitleMessage('Error Found', "No Tac number available for selected Company",);
			} else {
				$.each(splitTac, function (resKey, resValue) {
					html += '<option value="' + resValue + '">' + resValue + '</option>';
				});
			}
			$('#veh_tac').html(html);
			$('#veh_tac').selectpicker('refresh');

		}

	});

});
/*});*/


function resetall() {
	window.localStorage.removeItem('createentry');
	CACHE = {};
	window.location.href = window.location.href;

}

function saveCache() {
	window.localStorage.setItem('createentry', JSON.stringify(CACHE));
}

function verifyCache() {
	var cacheVariable = window.localStorage.getItem('createentry');
	if (cacheVariable && cacheVariable != undefined && cacheVariable != null) {
		var parse = JSON.parse(cacheVariable);
		if (typeof (parse) === 'object') {
			$.each(parse, function (key, value) {
				if ((key == "vehicle_owner_id_proof") || (key == "vehicle_owners") || (key == "rc_book")) {
					$('[name=' + key + '_photo]').val(value)
				} else /* if(key == "upload_governer_photo") {
					$('[name='+key+'_photo]').val(value)
				} else */ if (key == "upload_governer_photo") {
						$('[name="veh_speed_governer_photo"]').val(value)
					} else if (key == "upload_vehicle_photo") {
						$('[name="veh_photo"]').val(value)
					} else {
						$('[name=' + key + ']').val(value)
					}

				CACHE[key] = value;
			});
		}
		saveCache();
	}
}

$(document).ready(function () {

	$('input,textarea').on('blur change keyup', function () {
		var id = $(this).attr('name');
		var value = $(this).val();
		if (value && '' + value.length > 0) {
			if (id != 'upload_governer_photo' && id != 'upload_vehicle_photo' &&
				id != 'veh_make_no' && id != 'veh_model_no' &&
				id != 'veh_serial_no' &&
				id != 'veh_tac'
			) {
				CACHE[id] = $(this).val();
			}
		}
		saveCache();
	});
	$('select').on('blur change keyup', function () {
		var id = $(this).attr('name');
		var value = $(this).val();
		if (value && '' + value.length > 0) {
			if ('' + id != 'veh_company_id' && id != 'upload_governer_photo' && id != 'upload_vehicle_photo' &&
				id != 'veh_make_no' && id != 'veh_model_no' && id != 'state' && id != 'veh_rto_no' &&
				id != 'veh_serial_no' &&
				id != 'veh_tac') {
				CACHE[id] = $(this).val();
			}
		}
		saveCache();
	});

	verifyCache();

});