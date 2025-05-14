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
                            <h2>Edit Company Profile</h2>   
                                                    
                        </div>
                        <div class="body">
                            <form id="form_validation" method="POST" enctype="multipart/form-data">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" tabindex="1"  autofocus id="c_company_name" name="c_company_name" value="<?php echo isset($companyInfo['c_company_name'])?$companyInfo['c_company_name']:""; ?>" required>
                                        <label class="form-label">Company / Brand Name</label>
                                    </div>
                               <input type="hidden" name="c_company_id" id="c_company_id" value="<?php echo $companyInfo['c_company_id']; ?>" />
                                </div>  
                                <?php
                                // $tacNumber=array();
                                // if(isset($companyInfo['c_tac_no']))
                                // {
                                //     $tacNumber=explode(',', $companyInfo['c_tac_no']);
                                // } 
                                ?>
                                 <!-- <div class="row clearfix">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="text" tabindex="2" class="form-control" id="c_tac_no[]" name="c_tac_no[]" value="<?php echo isset($tacNumber[0])?$tacNumber[0]:""; ?>" required>
                                                        <label class="form-label">Tac Number 1</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="text" tabindex="3" class="form-control" id="c_tac_no[]" name="c_tac_no[]" value="<?php echo isset($tacNumber[1])?$tacNumber[1]:""; ?>">
                                                        <label class="form-label">Tac Number 2</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="text" tabindex="4" class="form-control" id="c_tac_no[]" name="c_tac_no[]" value="<?php echo isset($tacNumber[2])?$tacNumber[2]:""; ?>">
                                                        <label class="form-label">Tac Number 3</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="text" tabindex="5" class="form-control" id="c_tac_no[]" name="c_tac_no[]" value="<?php echo isset($tacNumber[3])?$tacNumber[3]:""; ?>">
                                                        <label class="form-label">Tac Number 4</label>
                                                    </div>
                                                </div>
                                            </div>
                                          
                                  </div> -->

                            <div class="row clearfix">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" tabindex="6"  class="datetimepicker form-control" name="c_cop_validity"  id="c_cop_validity" placeholder="Please choose date.."
                                            value="<?php echo isset($companyInfo['c_cop_validity'])?date('Y-m-d',strtotime($companyInfo['c_cop_validity'])):""; ?>" >
                                                        <label class="form-label"
                                             required>
                                        </div>
                                    </div>
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
	
	 <script src="<?php echo base_url() ?>public/js/pages/function/edit_company.js?t=<?php echo time(); ?>"></script>
	<?php $this->load->view('common/admin_login_css_js'); ?> 		
</body>
</html>