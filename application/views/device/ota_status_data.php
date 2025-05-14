<?php  
 $this->load->view('common/admin_login_header'); ?>
 <?php
 $user_type=$this->session->userdata('user_type');

 ?>
 <!-- Bootstrap Material Datetime Picker Css -->
<link href="<?php echo base_url() ?>public/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />

 <!-- Wait Me Css -->
 <link href="<?php echo base_url() ?>public/plugins/waitme/waitMe.css" rel="stylesheet" />

 <!-- Bootstrap Select Css -->
 <link href="<?php echo base_url() ?>public/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
 <!-- Include SweetAlert CSS and JS -->
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<style>
.glyphicon { 
  line-height: 2 !important;  
}
.pagination>li>a, .pagination>li>span { border-radius: 50% !important;margin: 0 5px;}

    /* Define styles for the custom Swal popup */
    .custom-swal-popup {
        font-size: 12px; /* Increase text size */
    }
    
    /* Define styles for the title (User Information) */
    .custom-swal-popup .swal-title {
        font-size: 14px; /* Increase title size */
    }
    
    /* Define styles for the buttons (OK button) */
    .custom-swal-popup .swal2-confirm {
        font-size: 12px; /* Increase button text size */
    }
    
    /* Define styles for the content (table) */
    .custom-swal-popup .swal2-content {
        padding: 14px; /* Increase padding to increase popup size */
    }

</style>
<body class="<?php echo THEME_BODY_HEADER_COLOR; ?>">
    <?php $this->load->view('common/top_search_bar'); ?>  
	 <?php $this->load->view('common/dashboard_top_bar'); ?> 
  <?php $this->load->view('common/left_side_bar'); ?>


    <section class="content">
        <div class="container-fluid">
            <div class="block-header" style="display:none;">
                <h2>
                    OTA Status
                </h2>
            </div>
            <!-- Basic Validation -->
<div class="row clearfix">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="card">
        <div class="header">
            <h2>
               OTA Status
            </h2>   
            <ul class="header-dropdown m-r--5">
                <li class="dropdown">
                  <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"
                 role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="material-icons">more_vert</i>
                    </a>
                    <ul class="dropdown-menu pull-right">
                        <li><a href="<?php echo base_url().'admin/create_rto'; ?>">Create</a></li>    
                    </ul> 
                </li>
            </ul>                         
        </div>
        <div class="body">
		
		
             <div class="table-responsive">
	<!--- Search---->
				<form action="<?php echo base_url() ?>device/ota_status"
				 name="searchfilter"  id="searchfilter" method="get" />				
						<input type="hidden" name="offset"   id="offset"
						value="<?php echo isset($_GET['offset'])?$_GET['offset']:0; ?>">				
						<div class="row clearfix">
							<div class="col-sm-4">
								<div class="form-group">
									<div class="form-line">
										<input type="text" class="form-control" placeholder="Search by IMEI" id="search" 
										value="<?php echo isset($_GET['search'])?$_GET['search']:""; ?>" name="search">
									</div>
								</div>
							</div>									
							
							<div class="col-sm-4">
								<div class="form-group">
									<div class="">
										<button class="btn btn-primary waves-effect" type="submit" id="searchfiltersubmit" 
										name="searchfiltersubmit">Search</button>
									</div>
								</div>
							</div>
							
						</div>
			</form>

			<!---- Search ---->

              <table id="mytable" class="table table-bordred table-striped" 
              style="table-layout: fixed; width: 100% !important;">
                   <colgroup>
                        <col style="max-width: 5%; min-width: 40px;">
                        <col style="max-width: 15%; min-width: 100px;">
                        <col style="max-width: 15%; min-width: 100px;">
                        <col style="max-width: 10%; min-width: 80px;">
                        <col style="max-width: 10%; min-width: 80px;">
                        <col style="max-width: 15%; min-width: 220px;">
                        <col style="max-width: 10%; min-width: 220px;">
                        <col style="max-width: 10%; min-width: 80px;">
                        <col style="max-width: 15%; min-width: 220px;"> 
                        <col style="max-width: 10%; min-width: 80px;">
                   </colgroup>
                   <thead>
						<th style="width:5%">#</th>
                        <th style="width:15%">IMEI</th>
                        <th>User Info</th>
                        <th>Type</th>
                        <th>Sent to Device</th>
                        <th style="width:15%">Sent Time</th>
                        <th>Response Sent</th>
                        <th>Ack Status</th>
                        <th style="width:15%">Ack Received</th>
                        <th style="width:10%">Received Time</th>
                   </thead>
				<tbody>  
				<?php if(count($listOfOtaStatusDatas)>0)
					{
						$sno=1;
						if(isset($_GET['offset']) && (int)$_GET['offset']>0)
						{
							$sno=(((int)$_GET['offset']-1)* 10)+1;
						}
						foreach($listOfOtaStatusDatas as $key => $value)
						{ ?>
							<tr>
								<td><?php echo $sno; ?> </td>
								<td><?php echo $value['IMEI']; ?></td>
								<!--<td><?php echo $value['LastUpdatedBy']; ?></td>-->
								<!--<td class="user-id"><a href="#" class="user-link"><?php echo $value['LastUpdatedBy']; ?></a></td>-->
                                <td class="user-id">
                                    <input type="hidden" id ="user-id-value" class="user-id-value" value="<?php echo $value['LastUpdatedBy']; ?>">
                                    <a  href="#"  id="user-link-" class="btn btn-primary btn-xs" >
                                        <span class="glyphicon glyphicon-info-sign"></span>
                                    </a>
                                </td>
                                
                                <td class="user-id text-center">
                                    <input type="hidden" id="user-id-value" class="user-id-value" value="<?php echo $value['LastUpdatedBy']; ?>">
                                    <a href="#" class="user-link">
                                        <span class="glyphicon glyphicon-info-sign"></span>
                                    </a>
                                </td>
								
								<!--Type-->
								<?php if($value['Type']!= null){ ?>
								    <td><?php echo $value['Type']; ?></td>
								<?php }else{ ?>  
								    <td><?php echo NA ?></td>
								<?php } ?>
								
								<!--IsSent-->
								<?php if($value['IsSent']== 1){ ?>
								    <td><?php echo YES ?></td>
								<?php }else{ ?>  
								    <td><?php echo NO ?></td>
								<?php } ?>
								
								<!--SentTime-->
								<?php if($value['SentTime'] != null){ ?>
								    <td><?php echo $value['SentTime'];?></td>
								<?php }else{ ?>  
								    <td><?php echo NA ?></td>
								<?php } ?>
								
								<!--RespHandling-->
								<?php if($value['RespHandling'] != null){ ?>
								    <td><?php echo $value['RespHandling'];?></td>
								<?php }else { ?>  
								    <td><?php echo NA ?></td>
								<?php } ?>
								
								<!--IsAck-->
								<?php if($value['IsAck'] == 1){ ?>
								    <td><?php echo YES;?></td>
								<?php }else { ?>  
								    <td><?php echo No ?></td>
								<?php } ?>
								
								<!--ACKData-->
								<?php if($value['ACKData'] != null){ ?>
								    <td style="width:20%"><?php echo $value['ACKData'];?></td>
								<?php }else { ?>  
								    <td><?php echo NA ?></td>
								<?php } ?>
								
								<!--CreatedTime-->
								<?php if($value['CreatedTime'] != null){ ?>
								    <td ><?php echo $value['CreatedTime'];?></td>
								<?php }else { ?>  
								    <td><?php echo NA ?></td>
								<?php } ?>
								

							</tr>
							<?php $sno++;
						} 
					}else{ ?>
						<tr style=" text-align: center;">
							<td colspan="4">No Records Found</td>
						</tr>
					<?php } ?>
						</tbody>
        
					</table>
					
					<div style="float:right;" id="pageformat"></div>
                   </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Validation -->
        
        </div>
    </section>
	
	<!--- Model Dialog ---->
	
	
	<?php $this->load->view('common/admin_login_css_js'); ?> 			
	<script>	
		$(function() {
			$('#pageformat').pagination({
				items: '<?php echo $totalNoOfotaStatusDatas; ?>',
				itemsOnPage: '<?php echo 10; ?>',
				cssStyle: 'light-theme',
				onPageClick:function(no){
					var offsetValue=$('#offset').val();
					if(offsetValue==no)
					{
						
					}
					else
					{
						$('#offset').val(no);							
						$('#searchfiltersubmit').trigger('click');
					}
				}
			});
			<?php
			if(isset($_GET['offset']) && (int)$_GET['offset']>0)
			{
			?>
			$('#pageformat').pagination('selectPage', '<?php echo $_GET['offset']; ?>');
			<?php
			}
			?>
		});
	</script>
	 <script>
        function showUserInfo(userId) {
            

        var data = new FormData;
        data.append("id", userId),
       
            $.ajax({
                url:SITEURL+ "/device/getUserInfo", // Replace with the actual URL
                type: "POST",
                data:  { 'userId': userId },
                success: function (data) {
                    data = data.replace(/^\s+|\s+$/g, "");
                    var responseData = JSON.parse(data);    
                    console.log(responseData.user_id)
                    var role = "";
                    if(responseData.user_type == 0){
                        role = 'Super Admin'; 
                    }else if(responseData.user_type == 1){
                        role = 'Dealer'; 
                    }else if(responseData.user_type == 2){
                        role = 'Distributor'; 
                    }else if(responseData.user_type == 3){
                        role = 'Rto'; 
                    }else if(responseData.user_type == 4){
                        role = 'Sub Admin'; 
                    }else if(responseData.user_type == 5){
                        role = 'Device'; 
                    }else if(responseData.user_type == 6){
                        role = 'Technician'; 
                    }else{
                         role = 'Undefined'; 
                    } 
                    var popupContent = `
                        <table style="width: 150px; text-align: center; margin: 0 auto; position: relative;">

                            <tr>
                                <th style="text-align: left;">Name:</th>
                                <td style="text-align: left;">${responseData.user_name}</td>
                            </tr>
                            <tr>
                                <th style="text-align: left;">Phone:</th>
                                <td style="text-align: left;">${responseData.user_phone}</td>
                            </tr>
                            <tr>
                                <th style="text-align: left;">Role:</th>
                                <td style="text-align: left;">${role}</td>
                            </tr>
                        </table>
                    `;
                    
                    Swal.fire({
                    title: 'User Information',
                    html: popupContent,
                    // icon: 'info',
                    // iconSize: '5px',
                    confirmButtonText: 'OK',
                    width: '400px', // Adjust the width as needed
                    customClass: {
                        content: 'text-center', // Center-align the content
                        popup: 'custom-swal-popup', // Custom class for styling
                    }
                });
                     
                },
                error: function () {
                    alert("Failed to fetch user information.");
                }
            });
        }
    
        // Add a click event handler to the anchor tag with class "user-link"
        // $(document).on("click", ".user-link", function (e) {
        $(document).on("click", "[id^='user-link-']", function (e) {
            e.preventDefault(); // Prevent the default behavior of the anchor tag
            // var userId = $(this).text();
            var userIdValue = document.getElementById("user-id-value").value;

            showUserInfo(userIdValue);
        });
    </script>
</body>
</html>