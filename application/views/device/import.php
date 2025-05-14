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
                    Import
                </h2>
            </div>

            <!-- Basic Validation -->

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>Upload SIM Numbers</h2>                           
                        </div>
                        <div class="body">
                            <form id="import_form_validation" method="POST" enctype="multipart/form-data">
                                <div class="form-group form-float">
                                    <div class="form-lines">
                                        <div class="form-label"><label>Upload Sim Numbers: </label></div>
                                        <input type="file" name="import_file" id="import_file" accept=".csv" required/>
                                     </div>
                                </div>
                                <div class="form-group form-float">
                                    <button class="btn btn-primary waves-effect" type="submit">SUBMIT</button>
                                </div>
                            </form>
                            <div id="import_notes">
                                <p><b>Important Note: </b></p>
                                <p>System will accept <b style="color: red;">.CSV File</b> only.</p> 
                                <p>Maximum file size - <b style="color: red;">2 MB.</b></p>
                                <p>Maximum Row count - <b style="color: red;">1000 Nos</b></p>
                                <p><a href="<?php echo base_url().'public/Stock-SIM-upload.csv'; ?>">Download Sample File</a></p>
                            </div>
                            <div id="file_read_status" style="display: none;">
                                <label for="file">Importing progress:</label>
                                <progress id="record_read_status" value="0" max="100">0%</progress>
                                <div id="record_read_response" style="width: 1000px; border: 5px solid #C0C0C0; padding: 10px; margin: 10px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Validation -->
        </div>

	

 <script>
    $(function () {
        $('#import_form_validation').validate({
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
                // alert('asdasd')
                var formData = new FormData($('#import_form_validation')[0]);

                $.ajax({
                    type: "POST",
                    url: SITEURL + "device/ajax_splitfiles",
                    data: formData,
                    //use contentType, processData for sure.
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                    },
                    success: function (res) {
                        data = res.replace(/^\s+|\s+$/g, "");
                        data = JSON.parse(data);
                        console.log(data)
                        if(data.status) {
                            records = data.data;
                            console.log(records);
                            if (records.length > 1001) {
                                alert('System should allow only 1000 records');
                            } else if (records.length > 0) {
                                $('#import_notes').hide();
                                $('#file_read_status').show();
                                $.each(records, function(index, val) {
                                    if (index == 0) {
                                        return true;
                                    }
                                    $.ajax({
                                        type: "POST",
                                        url: SITEURL + "device/ajax_verifydata?iccid=" + val[0] + '&mobile1=' + val[1] + '&mobile2=' + val[2],
                                        // data: passData,
                                        //use contentType, processData for sure.
                                        contentType: false,
                                        processData: false,
                                        beforeSend: function () {
                                        },
                                        success: function (response) {
                                            op = JSON.parse(response)
                                            var pct = ((index + 1) / records.length) * 100;
                                            $('#record_read_status').attr('value',pct);
                                            $('#record_read_status').val(pct);
                                            $('#record_read_response').append("<br /><span class=" + op.bgclass + ">Row #" + (index + 1) + " - " + op.msg + "</span>");
                                        }
                                    });
                                });
                                 
                            }
                        } else {

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