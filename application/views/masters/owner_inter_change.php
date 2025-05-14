<?php $this->load->view('common/admin_login_header'); ?>
<!-- Bootstrap Material Datetime Picker Css -->
<link href="<?php echo base_url() ?>public/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />

<!-- Wait Me Css -->
<link href="<?php echo base_url() ?>public/plugins/waitme/waitMe.css" rel="stylesheet" />

<!-- Bootstrap Select Css -->
<link href="<?php echo base_url() ?>public/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<style>
    #success {
        background: green;
    }

    #error {
        background: red;
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
                    OwnerShip Inter Change
                </h2>
            </div>
            <!-- Basic Validation -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2> OwnerShip Inter Change</h2>
                        </div>
                        <div class="body">

                            <?php /* echo "<pre>"; print_r($userinfo);echo "</pre>"; */ ?>

                            <form id="form_validation" method="POST" enctype="multipart/form-data">

                                <input type="hidden" name="veh_id" id="veh_id" value="<?php echo $vehicleInfo['veh_id']; ?>" />
                                <input type="hidden" name="veh_owner_id" id="veh_owner_id" value="<?php echo $vehicleInfo['veh_owner_id']; ?>" />
                                <input type="hidden" name="new_owner_id" id="new_owner_id" ?>
                                <input type="hidden" name="s_serial_id" id="s_serial_id" value="<?php echo $vehicleInfo['s_serial_id']; ?>" />


                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="veh_rc_no" disabled value="<?php echo isset($vehicleInfo['veh_rc_no']) ? $vehicleInfo['veh_rc_no'] : ""; ?>" id="veh_rc_no">
                                        <label class="form-label">Vehicle RC No</label>
                                    </div>
                                </div>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="s_serial_number" disabled value="<?php echo isset($vehicleInfo['s_serial_number']) ? $vehicleInfo['s_serial_number'] : ""; ?> / <?php echo isset($vehicleInfo['s_imei']) ? $vehicleInfo['s_imei'] : ""; ?> / <?php echo isset($vehicleInfo['s_iccid']) ? $vehicleInfo['s_iccid'] : ""; ?>" id="s_serial_number">
                                        <label class="form-label">Serial Number ( Serial No / Imei / Iccid )</label>
                                    </div>
                                </div>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="veh_owner_name" disabled value="<?php echo isset($vehicleInfo['veh_owner_name']) ? $vehicleInfo['veh_owner_name'] : ""; ?>" id="veh_owner_name" disabled required>
                                        <label class="form-label">Existing Owner Name</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="number" class="form-control" name="veh_owner_phone"  disabled value="<?php echo isset($vehicleInfo['veh_owner_phone']) ? $vehicleInfo['veh_owner_phone'] : ""; ?>" id="veh_owner_phone" required>
                                        <label class="form-label">Existing Phone No</label>
                                    </div>
                                </div>

                                <!-- <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="veh_owner_email" disabled
                                            value="<?php echo isset($vehicleInfo['veh_owner_email']) ? $vehicleInfo['veh_owner_email'] : ""; ?>"
                                            id="veh_owner_email" required>
                                        <label class="form-label">Existing Owner Email</label>
                                    </div>
                                </div> -->

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <select  onchange="handleReasonChange()" class="form-control show-tick" name="reason_inter_change" id="reason_inter_change" data-live-search="true" required>
                                            <option value="0">--Reason for Interchange--</option>
                                            <option value="1">I have changed my phone number</option>
                                            <option value="2">I want to change owner</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- <div class="form-group form-float">
                                    <div class="form-line" id="new_phone_field">
                                        <input type="number" class="form-control" tabindex="1" name="new_phone" id="new_phone"  maxlength="10" pattern="[0-9]{10}"  required>
                                        <label class="form-label">New Owner Phone*</label>
                                    </div>
                                </div> -->
                                <div class="form-group form-float">
                                    <div class="form-line" id="new_phone_field">
                                        <input type="tel" class="form-control" tabindex="1" name="new_phone" id="new_phone" maxlength="10" pattern="[0-9]{10}" inputmode="numeric" required>
                                        <label class="form-label">New Owner Phone*</label>
                                    </div>
                                </div>

                                <div class="form-group form-float">
                                    <div class="form-line" id="new_name_field">
                                        <input type="text" class="form-control" tabindex="1" name="new_name" id="new_name" required>
                                        <label class="form-label">New Owner Name*</label>
                                    </div>
                                </div>



                                <div class="form-group form-float">
                                    <div class="form-line" id="new_email_field">
                                        <input type="text" class="form-control" tabindex="1" name="new_email" id="new_email" required>
                                        <label class="form-label">New Owner Email*</label>
                                    </div>
                                </div>

                                <div class="form-group form-float">
                                    <div class="form-line" id="new_address_field">
                                        <textarea class="form-control" tabindex="1" id="new_address" name="new_address" required></textarea>
                                        <label class="form-label">New Owner Address*</label>
                                    </div>
                                </div>

                                <div class="clearfix"></div>
                                <br>
                                <div class="form-group form-float">
                                    <button class="btn btn-primary waves-effect" type="submit">OWNER INTER CHANGE</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Validation -->

        </div>
    </section>

    <!--- Model Dialog ---->

    <script>
            var newPhoneField   = document.getElementById("new_phone_field");
            var newNameField    = document.getElementById("new_name_field");
            var newAddressField = document.getElementById("new_address_field");
            var newEmailField   = document.getElementById("new_email_field");

            newNameField.style.display    = "none";
            newEmailField.style.display   = "none";
            newPhoneField.style.display   = "none";
            newAddressField.style.display = "none";

        function handleReasonChange() {
            var reason          = document.getElementById("reason_inter_change").value;
            console.log(reason)

            if (reason == "1" ) {
                // Show only new phone field
                newPhoneField.style.display   = "block";
                newNameField.style.display    = "none";
                newAddressField.style.display = "none";
                newEmailField.style.display   = "none";
            } 
            if(reason == "2") {
                // Show all fields
                newPhoneField.style.display   = "block";
                newNameField.style.display    = "block";
                newAddressField.style.display = "block";
                newEmailField.style.display   = "block";
            }
            if(reason == "0") {
                // hide all fields
                newNameField.style.display    = "none";
                newEmailField.style.display   = "none";
                newPhoneField.style.display   = "none";
                newAddressField.style.display = "none";
            }
        }
    </script>
    <script src="<?php echo base_url() ?>public/js/pages/function/owner_inter_change.js?t=<?php echo time(); ?>">
    </script>
    <?php $this->load->view('common/admin_login_css_js'); ?>
</body>

</html>