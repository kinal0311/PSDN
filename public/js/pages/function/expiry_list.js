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

});