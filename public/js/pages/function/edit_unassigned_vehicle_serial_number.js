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
			
			$.post(SITEURL+"admin/update_unassigned_serial_numbers_records",$('#form_validation').serializeArray(),function(data){

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
				            // 	title: "<bold></bold>",
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


	$('#s_company_id').on('change',function(){
		var value=$(this).val();

		if(value==='')
		{
			return true;
		}
		$.post(SITEURL+"admin/fetch_list_of_products",{'p_company_id':value},function(data){
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

		});

	});

	$('#list,#upload').on('change', function (){
		var value=$(this).val();

		if(value == 'upload'){
			$('#mode_list').hide();
			$('#mode_upload').show();
		}else{
			$('#mode_list').show();
			$('#mode_upload').hide();
		}
		
	});


	$('#s_serial_number').keypress(function (e) {
	    var regex = new RegExp("^[a-zA-Z0-9\-, ]+$");
	    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
	    if (regex.test(str)) {
	        return true;
	    }

	    e.preventDefault();
	    return false;
	});
});


