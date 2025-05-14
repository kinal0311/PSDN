<?php $this->load->view('common/admin_login_header'); ?>
  <?php
 $user_type=$this->session->userdata('user_type');
 ?>
 <script type="text/javascript">
     var user_type='<?php echo $user_type; ?>';
 </script>
 <!-- Bootstrap Material Datetime Picker Css -->
 <link href="<?php echo base_url() ?>public/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />

 <!-- Wait Me Css -->
 <link href="<?php echo base_url() ?>public/plugins/waitme/waitMe.css" rel="stylesheet" />

 <!-- Bootstrap Select Css -->
 <link href="<?php echo base_url() ?>public/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

<body class="<?php echo THEME_BODY_HEADER_COLOR; ?>">
    <?php $this->load->view('common/top_search_bar'); ?>  
     <?php //$this->load->view('common/dashboard_top_bar'); ?> 
  <?php //$this->load->view('common/left_side_bar'); 
  
  ?>
    <!-- <section class="content"> -->
    <section >
        <div class="container">
            <div class="block-header" style="display:none;">
               <!--  <h2>
                    Customer Registration
                </h2> -->
            </div>
            <!-- Basic Validation -->
            <div class="row clearfix">
                <div class="col-md-8 col-sm-12 col-xs-12 col-md-offset-2">
                    <div class="card">
                        <!-- <div class="header">
                            <h2>Customer Registration</h2>                           
                        </div> -->
                        <div class="body">
                            <form id="form_validation" method="POST" enctype="multipart/form-data">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="name" required>
                                        <label class="form-label">Name</label>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="number" class="form-control" name="phone" required>
                                        <label class="form-label">Phone</label>
                                    </div>
                                </div>                              
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="email" class="form-control" name="email" autocomplete="off" required >
                                        <label class="form-label">Email</label>
                                    </div>
                                </div>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <textarea name="address" cols="30" rows="5" class="form-control no-resize" required></textarea>
                                        <label class="form-label">Address</label>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="password" class="form-control" id="password" name="password" required>
                                        <label class="form-label">Password</label>
                                    </div>
                                </div>
                                
                              
                                <br>
                            <br>
                            <div class="form-group form-float text-center">
                                <button class="btn btn-success waves-effect" type="submit">SUBMIT</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Validation -->
        
        </div>
    </section>
    
  
    
     <script src="<?php echo base_url() ?>public/js/pages/function/create_customer_front.js?t=<?php echo time(); ?>"></script>
    <?php $this->load->view('common/admin_login_css_js'); ?>      
</body>
</html>