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
			
			$.post(SITEURL+"admin/create_No_of_Certificates",$('#form_validation').serializeArray(),function(data){
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
		$.post(SITEURL+"admin/fetch_list_of_users",{'user_type':value,'user_company_id':company},function(data){
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


