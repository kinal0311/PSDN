 <?php $this->load->view('common/admin_login_header'); ?>
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
                    Create New Company
                </h2>
            </div>
            <!-- Basic Validation -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>Assign Serial Numbers</h2>     
                        </div>
                        <div class="body">
                            <form id="form_validation" method="POST" enctype="multipart/form-data">
                                <div class="form-group form-float">
                                    <div class="form-line1">
                                        <label class="form-label">Company Name</label>
                                        <input type="hidden" name="s_company_id" value="<?php echo (isset($_POST['hid_company_id']))?$_POST['hid_company_id']:''; ?>">
                                        <select class="form-control show-tick" name="s_company_id" disabled id="s_company_id" data-live-search="true" required>
                                            <option value="">--Select Company Name--</option>
                                            <?php
                                            foreach ($company_list as $key => $value) {
                                                $selected="";
                                                if(isset($_POST['hid_company_id']) && (string)$_POST['hid_company_id']===(string)$value['c_company_id'])
                                                {
                                                    $selected="selected='selected'";
                                                }
                                            ?>
                                            <option <?php echo $selected; ?> value="<?php echo $value['c_company_id'] ?>"><?php echo $value['c_company_name'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>                                       
                                     </div>
                                </div>

                                <div class="form-group form-float">
                                    <div class="form-line1">
                                        <label class="form-label">Product Name</label>
                                        <input type="hidden" name="s_product_id" value="<?php echo (isset($_POST['hid_product_id']))?$_POST['hid_product_id']:''; ?>">
                                        <select class="form-control show-tick" name="s_product_id" disabled id="s_product_id" data-live-search="true" required>
                                            <option value="">--Select Product Name--</option>
                                            <?php
                                            foreach ($product_list as $key => $value) {
                                                $selected="";
                                                if(isset($_POST['hid_product_id']) && (string)$_POST['hid_product_id']===(string)$value['p_product_id'])
                                                {
                                                    $selected="selected='selected'";
                                                }
                                            ?>
                                            <option <?php echo $selected; ?>value="<?php echo $value['p_product_id'] ?>"><?php echo $value['p_product_name'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>                                       
                                     </div>
                                </div>
                                
                                 <div class="form-group form-float">
                                    <div class="form-line1">
                                        <input type="hidden" id="hid_serial_ids" name="hid_serial_ids" value="<?php echo (isset($_POST['serial_ids']))?implode(',', $_POST['serial_ids']):''; ?>">
                                        <label class="form-label">Selected Serial Numbers</label>
                                        <!-- <select class="form-control show-tick" name="s_serial_id[]" id="s_serial_id" disabled data-live-search="true" multiple required >
                                            <option value="">--Serial Numbers--</option>
                                            <?php 
                                                foreach($serial_list as $serial){
                                                    echo '<option value="'.$serial['s_serial_id'].'">'.$serial['s_serial_number'].'-'.$serial['s_imei'].'-'.(($serial['s_mobile'])?$serial['s_mobile']:'(No Mobile)').'</option>';
                                                }
                                            ?>
                                        </select>-->
                                        <?php
                                            foreach($serial_list as $serial){
                                            echo '<br>'.$serial['s_serial_number'].'-'.$serial['s_imei'].'-'.(($serial['s_mobile'])?$serial['s_mobile']:'(No Mobile)');
                                            }
                                        ?>
                                        <input type="hidden" name="s_serial_id" value="<?php echo (isset($_POST['serial_ids']))?implode(',', $_POST['serial_ids']):''; ?>">
                                    </div>
                                </div>

                               <!--  <div class="form-group">
                                   <label class="form-label">User Type</label>
                                   <input type="radio" name="s_user_type" id="s_user_type2" value="2" class="radio-col-deep-purple" required>
                                    <label for="s_user_type2" class="m-l-20">Distributor</label>

                                    <input type="radio" name="s_user_type" id="s_user_type1" value="1" class="radio-col-deep-purple" required>
                                    <label for="s_user_type1">Dealer</label>                                   
                                </div>     -->

                                 <?php 
                                    $user_type=$this->session->userdata('user_type');

                                    if($_POST['hid_mode'] == 'unassigned'){
                                 ?>
                                 <div class="form-group form-float">
                                    <div class="form-line">
                                        <select class="form-control show-tick" name="s_distributor_id"  id="s_distributor_id" data-live-search="true" required >
                                            <option value="">--Select Distributor --</option>  
                                            <?php 
                                                foreach($distributor_list as $user){
                                                    echo '<option value="'.$user['user_id'].'">'.$user['user_name'].'</option>';
                                                }
                                            ?>                                          
                                        </select>                                       
                                     </div>
                                 </div>   
                                <!--<div class="form-group form-float" id="distributor_price_div">-->
                                <!--    <div class="form-line">-->
                                <!--        <input type="number" class="form-control" name="distributor_price" id="distributor_price" min="0" required>-->
                                <!--        <label class="form-label">Distributor Price</label>-->
                                <!--    </div>-->
                                <!--</div>-->

                                    <?php } else { ?>
                                <div class="form-group form-float custom-dropdown">
                                    <div class="form-line">
                                        <select class="form-control show-tick" name="s_dealer_id"  id="s_dealer_id" data-live-search="true" style="padding: 70px;" required>
                                            <option value="">--Select Dealer --</option>    
                                            <?php 
                                                foreach($dealer_list as $user){
                                                    echo '<option value="'.$user['user_id'].'">'.$user['user_name'].'</option>';
                                                }
                                            ?>                                                                                  
                                        </select>                                       
                                     </div>
                                 </div>   
                                <!--<div class="form-group form-float" id="dealer_price_div">-->
                                <!--    <div class="form-line">-->
                                <!--        <input type="number" class="form-control" name="dealer_price" id="dealer_price" min="0" required>-->
                                <!--        <label class="form-label">Dealer Price</label>-->
                                <!--    </div>-->
                                <!--</div>-->
                                    <?php } ?>

                                <input type="hidden" name="hid_mode" id="hid_mode" value="<?php echo $_POST['hid_mode']; ?>">
                                <input type="hidden" name="h_admin_price" id="h_admin_price" >
                                <input type="hidden" name="h_distributor_price" id="h_distributor_price" >
                                <input type="hidden" name="h_dealer_price" id="h_dealer_price" >


                                <!--<div class="form-group form-float" id="dealer_price_div">-->
                                <!--    <div class="form-line">-->
                                <!--        <textarea class="form-control" name="comments" id="comments" required></textarea>-->
                                <!--        <label class="form-label">Comments (for Invoice)</label>-->
                                <!--    </div>-->
                                <!--</div>-->

    							<div class="form-group form-float">
                                    <a class="btn btn-default " href="history.back(0)">BACK</a>
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
                           
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">OK</button>
                        </div>
                    </div>
                </div>
            </div>
	<!--- Model Dialog ---->
	
	 <script src="<?php echo base_url() ?>public/js/pages/function/create_vehicle_serial_number.js?t=<?php echo time(); ?>"></script>
	<?php $this->load->view('common/admin_login_css_js'); ?> 		
    <?php if(isset($_POST['hid_product_id'])){
        echo '<script>
            $(function() {
                $("#s_product_id").trigger("change");
            });
        </script>
        ';
    }
    ?>
</body>
</html>