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
                            <!-- <div class="form-group form-float">
                                <div class="form-line">
                                    <select class="form-control show-tick" name="s_country_id" id="s_country_id" data-live-search="true">
                                            <?php 
											foreach($countryList as $key=>$value)
											{ ?>
                                             <option value="<?php echo $value['c_id']; ?>">
                                                 <?php echo $value['c_name']; ?>
                                             </option>
                                             <?php 
											}
											?>
                                         </select>
                                     </div>
                                 </div> -->
                                 <input type="hidden" name="s_country_id" id="s_country_id" value=1>

                                 <div class="form-group form-float">
                                     <div class="form-line">
                                         <select class="form-control show-tick" name="s_state_id" id="s_state_id"
                                             data-live-search="true" required>
                                             <option value="">--Select State-- *</option>
                                         </select>
                                     </div>
                                 </div>
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
                    url: SITEURL + "admin/verifyscanner",
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
<script>
    
    $(document).ready(function () {
        $('[name=s_country_id]').on('change', function () {
            var value = $(this).val();
            if (value === '') {
                return true;
            }
            console.log("data", value);
            $.post(SITEURL + "admin/getStateByCountryById", { 'id': value }, function (data) {
                data = data.replace(/^\s+|\s+$/g, "");
                data = JSON.parse(data);
                if (data.state_list && data.state_list.length === 0) {
                    showWithTitleMessage('No Records Found', "Selected Country Doesn't have any State records.");
                }
                var html = '';
                html = '<option value="" selected="selected">--Select State--</option>';
                if (data.state_list && data.state_list.length) {
                    $.each(data.state_list, function (resKey, resValue) {
                        html += '<option value="' + resValue.id + '">' + resValue.s_name + '</option>';
                    });
                }
                $('#s_state_id').html(html);
                $('#s_state_id').selectpicker('refresh');
            });
        });
    });

    $(function () {
        $.post(SITEURL + "admin/getStateByCountryById", { 'id': "1" }, function (data) {
            data = data.replace(/^\s+|\s+$/g, "");
            data = JSON.parse(data);
            if (data.state_list && data.state_list.length === 0) {
                showWithTitleMessage('No Records Found', "Selected Country Doesn't have any State records.");
            }
            var html = '';
            html = '<option value="" selected="selected">--Select State--</option>';
            if (data.state_list && data.state_list.length) {
                $.each(data.state_list, function (resKey, resValue) {
                    html += '<option value="' + resValue.id + '">' + resValue.s_name + '</option>';
                });
            }
            $('#s_state_id').html(html);
            $('#s_state_id').selectpicker('refresh');
        });
    });
</script>
<?php $this->load->view('common/admin_login_css_js'); ?> 		
</body>

</html>