 <?php $this->load->view('common/admin_login_header'); ?>
 <!-- Bootstrap Material Datetime Picker Css -->
 <link href="<?php echo base_url() ?>public/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />

 <!-- Wait Me Css -->
 <link href="<?php echo base_url() ?>public/plugins/waitme/waitMe.css" rel="stylesheet" />

 <!-- Bootstrap Select Css -->
 <link href="<?php echo base_url() ?>public/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

 <style type="text/css">
    .multi-button {
      max-width: 700px;
      padding: 10px 16px;
      border-radius: 50px;
      background: #ddd;
      border: 2px solid #ddd;
    }

    .updatecls {
        margin-right:12px;
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
                    Create New Company
                </h2>
            </div>
            <!-- Basic Validation -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>Reassign Serial Numbers</h2>                           
                        </div>
                        <div class="body">
                            <div class="multi-button">
                                <button class="btn btn-primary waves-effect updatecls" style="border-radius: 20px;" value="CMT TAG">CMT TAG</button>
                                <button class="btn btn-primary waves-effect updatecls" style="border-radius: 20px;" value="EPB ON">EPB ON</button>
                                <button class="btn btn-primary waves-effect updatecls" style="border-radius: 20px;" value="EPB OFF">EPB OFF</button>
                                <button class="btn btn-primary waves-effect updatecls" style="border-radius: 20px;" value="RESET">RESET</button>
                                <button class="btn btn-primary waves-effect updatecls" style="border-radius: 20px;" value="CLR Altitude">CLR Altitude</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Validation -->
        
        </div>
    </section>
    <script src="<?php echo base_url() ?>public/js/pages/function/edit_vehicle_serial_number.js?t=<?php echo time(); ?>"></script>
    <?php $this->load->view('common/admin_login_css_js'); ?>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.updatecls').click(function() {
                appendStr = "selectedVal=" + $(this).val() + "&imei=<?php echo $imei; ?>"; 
                $.ajax({
                    type: "POST",
                    url: SITEURL + "admin/imei_ota_save?" + appendStr,
                    contentType: "application/json; charset=utf-8",
                    data: JSON.stringify({
                        
                    }),
                    // dataType: "json",
                    // contentType: false,
                    // processData: false,
                    success: function (data) {

                        data = JSON.parse(data.trim());
                        if (data.error) {
                            showWithTitleMessage(data.error, '');
                        }
                        if (data.validation && Object.keys(data.validation).length > 0) {
                            var words = "";
                            for (var i = 0; i < Object.keys(data.validation).length; i++) {
                                var Obj = Object.keys(data.validation)[i];
                                words += data.validation[Obj] + "<br />";
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
                        if (data.success) {
                            if (data.redirect) {
                                swal({
                                    title: "<bold>Success</bold>",
                                    type: "success",
                                    html: true,
                                    text: data.message,
                                }, function (isConfirm) {
                                    if (isConfirm) {
                                        window.location.href = SITEURL + data.redirect;
                                    }
                                });
                            }
                        }

                    }
                });
            });
        });
    </script>    
</body>
</html>