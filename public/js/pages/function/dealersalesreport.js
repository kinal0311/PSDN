$(function(){
	 $('#start_date,#end_date').bootstrapMaterialDatePicker({
			format: 'YYYY-MM-DD',
			clearButton: true,
			weekStart: 1,
			time: false
	 });
	 validationProcess();

	 $('#user_type').val('');
	 $('#user_id').val('');
	 $('#company_id').val('');
	 $('#make_no').val('');
	 $('#model_id').val('');
	 $('#rto_no').val('');
	 
});

function validationProcess()
{
    $('#form_validation').validate({
        rules: {
           
            'user_type': {
                required: true
            },
			'user_id': {
                required: true
            }
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
		    
		    var X=$('#form_validation').serializeArray();
			var URL=SITEURL+'admin/view_dealersalesreport?iframe=true&';
			X.forEach(function(val,key){				
				URL+=val.name+"="+val.value+"&";
			});			
			
				$('#iframe').show().attr('src',URL);
				$('#print').show();
				return false;
			return true;
		}
    });  
}


function printReport()
{
	var X=$('#form_validation').serializeArray();
			var URL=SITEURL+'admin/view_dealersalesreport?iframe=true&';
			X.forEach(function(val,key){				
				URL+=val.name+"="+val.value+"&";
			});
	 var win = window.open(URL, '_blank');
  win.focus();
}


// Select User Types
function select_user_type(e,th)
{
		var value=$(th).val();
		if(value==='')
		{
			return true;
		}
		$.post(SITEURL+"admin/fetch_list_of_users",{'user_type':value},function(data){
			data=JSON.parse(data);
				if(data.list && data.list.length===0)
				{
					showWithTitleMessage('No Users Records Found.','');
					return false;
				}
				var html='';
				if(''+value==='2')
				{
					html='<option value="" selected="selected">--Select Distributor--</option>';
				}else if(''+value==='1')
				{
					html='<option value="">--Select Dealer--</option>';
				}
								html+='<option value="-1">ALL</option>';

				if(data.list && data.list.length)
				{
					$.each(data.list,function(resKey,resValue){
						html+='<option value="'+resValue.user_id+'">'+resValue.user_name+'</option>';
					});
				}
			$('#user_id').html(html);		
			$('#user_id').selectpicker('refresh');

		});	
}

// Select Model list
function select_model_list(e,th)
{
		var value=$(th).val();
		if(value==='')
		{
			return true;
		}
		$.post(SITEURL+"admin/fetch_model_list_by_make",{'veh_make_no':value},function(data){
			data=JSON.parse(data);
			if(data.model_list && data.model_list.length===0)
			{
				showWithTitleMessage('No Records Found',"Selected Make Doesn't have any model records.",);
				return false;
			}
				var html='';				
					html='<option value="" selected="selected">--Select Model--</option>';				
			if(data.model_list && data.model_list.length)
			{
				$.each(data.model_list,function(resKey,resValue){
					html+='<option value="'+resValue.ve_model_id+'">'+resValue.ve_model_name+'</option>';
				});
			}
			$('#model_id').html(html);			
			$('#model_id').selectpicker('refresh');

		});
}


function PrintElem(elem)
{
    var mywindow = window.open('', 'PRINT', 'height=400,width=600');

    mywindow.document.write('<html><head><title>' + document.title  + '</title>');
    mywindow.document.write('</head><body >');
    mywindow.document.write('<h1>' + document.title  + '</h1>');
    mywindow.document.write(document.getElementById(elem).innerHTML);
    mywindow.document.write('</body></html>');

    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/

    mywindow.print();
    mywindow.close();
    console.log(mywindow);
    return true;
}