var CACHE = {};

var fileInput1 = document.getElementById('vehicle_owners');
fileInput1.addEventListener('change', function (e) {
	vehicle_owners_photo_upload(e, 'vehicle_owners')
}, false);

function vehicle_owners_photo_upload(e, name) {
	var formData = new FormData($('#form_validation')[0]);

	if (name == 'vehicle_owners') {
		formData.append('vehicle_owners', $('input[type=file]')[0].files[0]);
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

$(function () {

	$('#form_validation').validate({
		rules: {


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
		submitHandler: function (form) {

			$.post(SITEURL + "admin/create_new_customer_records", $('#form_validation').serializeArray(), function (data) {
				data = data.replace(/^\s+|\s+$/g, "");
				data = JSON.parse(data);
				if (data.error) {
					showWithTitleMessage(data.error, '');
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
						swal({
							title: "<bold>Success</bold>",
							type: "success",
							html: true,
							text: data.message,
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

verifyCache();