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

 function changeStatus (user_id,user_status) {

 	var text='';
 	if(''+user_status==='1')
 	{
 		text='Dow u want to De-Active selected users.';
 	}else{
 		text='Dow u want to Active selected users.';
 	}

	swal({
		title: "Are you sure?",
		text: text,
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Yes, do it!",
		closeOnConfirm: false
	}, function (isConfirm) {
		if(isConfirm)
		{

			var res={};
			res.user_id=user_id;
			res.user_status=0;
			if(''+user_status==='0')
			{
				res.user_status=1;
			}
			
			$.post(SITEURL+"admin/changeUserStatus",res,function(data){
				data=JSON.parse(data);
				if(data.error)
				{					
					showWithTitleMessage(data.error,'');
				}	
				if(data.success)
				{					
					window.location.href=window.location.href;
				}
		    });		    
	    }
	});
}
	

