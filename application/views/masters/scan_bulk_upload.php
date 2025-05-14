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
                            <h2>Bulk Stock Entry</h2>
                        </div>
                        
                        <div class="body">
                        <?php if ($this->session->flashdata('success')): ?>
							<div class="alert alert-success">
								<?= $this->session->flashdata('success'); ?>
							</div>
                        <br/>
						<?php endif; ?>

						<?php if ($this->session->flashdata('error')): ?>
							<div class="alert alert-danger">
								<?= nl2br($this->session->flashdata('error')); // Display errors with new lines ?>
							</div>
                        <br/>
						<?php endif; ?>
                            <!-- <div class="col-lg-6"> -->
                                <div class="form-group ">
                                    <div class="" style="float:right;">
                                        <a href="<?php echo base_url() ?>public/stock_bulk_import.xlsx" class="btn btn-primary waves-effect">Download Sample File</a>
                                    </div>
                                </div>
                                <br/>
                                <form id="bulk_scan_form_validation" action="<?= base_url('admin/import_stock_list') ?>" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="s_country_id" id="s_country_id" value=1>

                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <select class="form-control show-tick" name="s_state_id" id="s_state_id"
                                                data-live-search="true" required>
                                                <option value="">--Select State-- *</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label>Import Excel File:</label>
                                            <input type="file" class="form-control" name="excel_file" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="">
                                            <button type="submit" class="btn btn-primary waves-effect">Upload</button>
                                        </div>
                                    </div>
                                </form>
                            <!-- </div> -->
                            
                            <!-- <form id="scan_form_validation" method="POST" enctype="multipart/form-data">
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
                            </form> -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Validation -->
        </div>

	

 <script>
    $(function () {
        $('#bulk_scan_form_validation').validate({
            rules: {},
            highlight: function (input) {
                $(input).parents('.form-line').addClass('error');
            },
            unhighlight: function (input) {
                $(input).parents('.form-line').removeClass('error');
            },
            errorPlacement: function (error, element) {
                $(element).parents('.form-group').append(error);
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