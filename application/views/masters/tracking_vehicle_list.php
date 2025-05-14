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


  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Vehicle Tracking
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"> Track Your Vehicle</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
     <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <div class="box-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                  <div class="input-group-btn">
                    <button type="button" onclick="return showMore(event,this);" class="btn btn-default"><i class="fa fa-search"></i></button>
                    <button type="button" onclick="return showMore(event,this,1);" class="btn btn-default"><i class="fa fa-times"></i></button>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered">
                <thead>
                  <tr>
                  <th>S.No</th>
                  <th>Vehicle No</th>
                  <th>IMEI Number</th>
                  <th>Serial No</th>
                  <th>Mobile No</th>
                  <th>ValidUpto</th>
                  <th>SOS</th>
                  <th>Track</th>
                </tr>
                </thead>
                <tbody id="tbody">

              </tbody></table>
            </div>
            <!-- /.box-body -->
           
          </div>
          <!-- /.box -->

        
          <!-- /.box -->
        </div>
        <!-- /.col -->
      
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <!-- Main row -->
   
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
  </div>
       <!--- Model Dialog ---->
	
	 
	<?php $this->load->view('common/admin_login_css_js'); ?> 	
  <script src="<?php echo base_url(); ?>public/js/frontend.js?id=<?php echo rand(); ?>"></script>
    <script>
        var base_url='<?php echo base_url(); ?>';
        trackiniginfo();
    </script>	
</body>
</html>