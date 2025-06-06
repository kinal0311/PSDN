﻿ <?php $this->load->view('common/admin_login_header'); ?>
 <!-- Bootstrap Material Datetime Picker Css -->
 <link href="<?php echo base_url() ?>public/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />

 <!-- Wait Me Css -->
 <link href="<?php echo base_url() ?>public/plugins/waitme/waitMe.css" rel="stylesheet" />

 <!-- Bootstrap Select Css -->
 <link href="<?php echo base_url() ?>public/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

<body class="<?php echo THEME_BODY_HEADER_COLOR; ?>">
    <?php $this->load->view('common/top_search_bar'); ?>  
	 <?php $this->load->view('common/dashboard_top_bar'); ?> 
  <?php $this->load->view('common/left_side_bar'); ?>


    <section class="content">
        <div class="container-fluid">
            <div class="block-header" style="display:none;">
                <h2>
                    Update Rto Number
                </h2>
            </div>
            <!-- Basic Validation -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>Update RTO</h2>                           
                        </div>
                        <div class="body">
                            <form id="form_validation" method="POST" enctype="multipart/form-data">
                                 <div class="form-group form-float">
                                     <div class="form-line">
                                         <select class="form-control show-tick" name="state_id" id="state_id"
                                             data-live-search="true" required>
                                             <option value="">--Select State--</option>
                                             <?php 
											foreach($state_list as $key=>$value)
											{ 
												$selected='';
                                                if(isset($RToInfo['state_id']) && (string)$RToInfo['state_id']===(string)$value['id'])
												{
													$selected='selected="selected"';
												}
											?>

                                             <option <?php echo $selected; ?> value="<?php echo $value['id']; ?>">
                                                 <?php echo $value['s_name']; ?>
                                             </option>
                                             <?php 
											}
											?>
                                         </select>
                                     </div>
                                 </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" tabindex="1" autofocus id="rto_number" name="rto_number" value="<?php echo isset($RToInfo['rto_number'])?$RToInfo['rto_number']:""; ?>" required>
                                        <label class="form-label">RTO Number</label>
                                    </div>
                                </div>  
                         <input type="hidden" name="rto_no" id="rto_no" value="<?php echo $RToInfo['rto_no']; ?>" />

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" tabindex="2" autofocus id="rto_place" name="rto_place" value="<?php echo isset($RToInfo['rto_place'])?$RToInfo['rto_place']:""; ?>"  required>
                                        <label class="form-label">RTO Place</label>
                                    </div>
                                </div>  
                                                              
    							<div class="form-group form-float">
                                    <button class="btn btn-primary waves-effect" type="submit">SUBMIT</button>
    							</div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Validation -->
        
        </div>
    </section>
	
	<!--- Model Dialog --->
	<!-- Default Size -->
            <div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="defaultModalLabel">Modal title</h4>
                        </div>
                        <div class="modal-body" id="defaultModalBody">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin sodales orci ante, sed ornare eros vestibulum ut. Ut accumsan
                            vitae eros sit amet tristique. Nullam scelerisque nunc enim, non dignissim nibh faucibus ullamcorper.
                            Fusce pulvinar libero vel ligula iaculis ullamcorper. Integer dapibus, mi ac tempor varius, purus
                            nibh mattis erat, vitae porta nunc nisi non tellus. Vivamus mollis ante non massa egestas fringilla.
                            Vestibulum egestas consectetur nunc at ultricies. Morbi quis consectetur nunc.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">OK</button>
                        </div>
                    </div>
                </div>
            </div>
	<!--- Model Dialog ---->
	
	 <script src="<?php echo base_url() ?>public/js/pages/function/update_rto.js?t=<?php echo time(); ?>"></script>
	<?php $this->load->view('common/admin_login_css_js'); ?> 		
</body>
</html>