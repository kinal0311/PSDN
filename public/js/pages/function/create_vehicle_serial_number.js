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
		submitHandler: function(form) {	
			
			$.post(SITEURL + "admin/assign_new_serial_numbers_records", $('#form_validation').serializeArray(), function (data) {
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
						title: "<bold></bold>",
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

	$('#s_company_id').on('change',function(){
		var value=$(this).val();

		if(value==='')
		{
			return true;
		}
		$.post(SITEURL + "admin/fetch_list_of_products", { 'p_company_id': value }, function (data) {
			data = data.replace(/^\s+|\s+$/g, "");
			data=JSON.parse(data);
			if(data.list && data.list.length===0)
			{
				showWithTitleMessage('No Product Records Found.','');
			}
			var html='';
			if(data.list && data.list.length)
			{
				$.each(data.list,function(resKey,resValue){
					html+='<option value="'+resValue.p_product_id+'">'+resValue.p_product_name+'</option>';
				});
			}
			$('#s_product_id').html(html);
			$('#s_product_id').selectpicker('refresh');
			$('#s_product_id').trigger('change')

		});

	});

	$('#s_product_id').on('change',function(){
		var company_id=$('#s_company_id').val();
		var product_id=$(this).val();
		var serial_ids = $('#hid_serial_ids').val().split(',');

		if(company_id=='' || product_id == '')
		{
			return true;
		}
		$.post(SITEURL + "admin/fetch_list_of_unassigned_serial_numbers", { 's_company_id': company_id, 's_product_id': product_id }, function (data) {
			data = data.replace(/^\s+|\s+$/g, "");
			data=JSON.parse(data);
			if(data.list && data.list.length===0)
			{
				showWithTitleMessage('No Unassigned Serial Numbers Found.','');
			}
			var html='';
			var selected = '';
			if(data.list && data.list.length)
			{
				$.each(data.list,function(resKey,resValue){
					selected = ($.inArray(resValue.s_serial_id, serial_ids) > -1) ? 'selected' : '';
					html+='<option value="'+resValue.s_serial_id+'" '+selected+'>'+resValue.s_serial_number+'-'+resValue.s_imei+'-'+((resValue.s_mobile)?resValue.s_mobile:'(No Mobile)')+'</option>';
				});
			}
			$('#s_serial_id').html(html);		
			$('#s_serial_id').selectpicker('refresh');
			$('#distributor_price').prop('min', data.min_admin_price);
			$('#dealer_price').prop('min', data.min_distributor_price);

			$('#h_admin_price').val(data.min_admin_price);
			$('#h_distributor_price').val(data.min_distributor_price);
			$('#h_dealer_price').val(data.min_dealer_price);

		});

	});


	$('[name=s_user_type]').on('change',function(){
		var value=$(this).val();

		if(value==='')
		{
			return true;
		}
		var company=$('#s_company_id').val();
		if(company==='')
		{
					swal({
						title: "<bold></bold>",
						text: 'Please select company',
						type: "error",
						html: true
					}, function (isConfirm) {
						
					});
					
					return false;
		}
		$.post(SITEURL + "admin/fetch_list_of_users", { 'user_type': value, 'user_company_id': company }, function (data) {
			data = data.replace(/^\s+|\s+$/g, "");
			data=JSON.parse(data);
			if(data.list && data.list.length===0)
			{
				showWithTitleMessage('No Users Records Found.','');
			}
				var html='';
				if(''+value==='2')
				{
					html='<option value="" selected="selected">--Select Distributor--</option>';
					$("#dealer_price_div").hide();
					$("#distributor_price_div").show();
				}else if(''+value==='1')
				{
					html='<option value="">--Select Dealer--</option>';
					$("#distributor_price_div").hide();
					$("#dealer_price_div").show();
				}
			if(data.list && data.list.length)
			{
				$.each(data.list,function(resKey,resValue){
					html+='<option value="'+resValue.user_id+'">'+resValue.user_name+'</option>';
				});
			}
			$('#s_user_id').html(html);		
			$('#s_user_id').selectpicker('refresh');

		});



	});


	$('#s_serial_number').keypress(function (e) {
	    var regex = new RegExp("^[a-zA-Z0-9, ]+$");
	    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
	    if (regex.test(str)) {
	        return true;
	    }

	    e.preventDefault();
	    return false;
	});
});


