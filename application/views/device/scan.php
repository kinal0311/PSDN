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
    <?php $this->load->view('common/dashboard_top_bar'); ?>
    <?php $this->load->view('common/left_side_bar'); ?>

    <section class="content">
        <div class="container-fluid">
            <div class="block-header" style="display:none;">
                <h2>
                    Scan the code
                </h2>

            </div>

            <!-- Basic Validation -->

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>Manual Stock Entry - Scan ( with Device String )</h2>                           
                        </div>
                        <div class="body">
                            <form id="scan_form_validation" method="POST" enctype="multipart/form-data">
                                <div class="form-group form-float">
                                    <div class="form-lines">
										<div class="form-label"><label>Enter Device String: </label></div>
                                        <textarea value="2" autofocus type="hidden" class="form-control show-tick" name="serial_number"  id="serial_number" data-live-search="true" placeholder="" required style="border-style: solid;"></textarea>
                                        <span style="font-weight: bold; opacity: .5; color: #0000ff">Ex. String format should be  <b>IMEI;ICCID ( 865006043444299;89917210914421977643 )</b></span>
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

	

 <script>
    $(function () {
        $('#scan_form_validation').validate({
            rules: {},
            highlight: function (input) {
                $(input).parents('.form-line').addClass('error');
            },
            unhighlight: function (input) {
                $(input).parents('.form-line').removeClass('error');
            },
            errorPlacement: function (error, element) {
                $(element).parents('.form-group').append(error);
            },
            submitHandler: function (form) {

                var formData = new FormData($('#scan_form_validation')[0]);

                $.ajax({
                    type: "POST",
                    url: SITEURL + "device/verifyscanner",
                    data: formData,
                    //use contentType, processData for sure.
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                    },
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

                return false;
            }
        });
    });
</script>
<?php $this->load->view('common/admin_login_css_js'); ?> 		
</body>

</html>