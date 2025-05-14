<?php $this->load->view('common/admin_login_header'); ?>
<!-- Bootstrap Material Datetime Picker Css -->
<link
    href="<?php echo base_url() ?>public/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css"
    rel="stylesheet" />

<!-- Wait Me Css -->
<link href="<?php echo base_url() ?>public/plugins/waitme/waitMe.css" rel="stylesheet" />

<!-- Bootstrap Select Css -->
<link href="<?php echo base_url() ?>public/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css"
     href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
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
                    Device Inter Change
                </h2>
            </div>
            <!-- Basic Validation -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>Inter Change Device</h2>
                        </div>
                        <div class="body">

                            <?php /* echo "<pre>"; print_r($userinfo);echo "</pre>"; */ ?>

                            <form id="form_validation" method="POST" enctype="multipart/form-data">

                                <input type="hidden" name="veh_id" id="veh_id"
                                    value="<?php echo $vehicleInfo['veh_id']; ?>" />
                                <input type="hidden" name="veh_owner_id" id="veh_owner_id"
                                    value="<?php echo $vehicleInfo['veh_owner_id']; ?>" />


                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="veh_rc_no" disabled
                                            value="<?php echo isset($vehicleInfo['veh_rc_no'])?$vehicleInfo['veh_rc_no']:""; ?>"
                                            id="veh_rc_no">
                                        <label class="form-label">Vehicle RC No</label>
                                    </div>
                                </div>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <?php if($vehicleInfo['s_iccid']==""){
                                            $iccid = "-";
                                        }else{
                                            $iccid = $vehicleInfo['s_iccid'];
                                        }?>
                                        
                                        <input type="text" class="form-control" name="s_serial_number" disabled value="<?php echo isset($vehicleInfo['s_serial_number'])?$vehicleInfo['s_serial_number']:"-"; ?> / <?php echo isset($vehicleInfo['s_imei'])?$vehicleInfo['s_imei']:"-"; ?> / <?php echo $iccid ?>"
                                            id="s_serial_number">
                                        <label class="form-label">Existing Serial Number ( Serial No / Imei / Iccid )</label>
                                    </div>
                                </div>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="veh_owner_name"
                                            value="<?php echo isset($vehicleInfo['veh_owner_name'])?$vehicleInfo['veh_owner_name']:""; ?>"
                                            id="veh_owner_name" disabled required>
                                        <label class="form-label">Owner Name</label>
                                    </div>
                                </div>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="veh_owner_name"
                                            value="<?php echo isset($vehicleInfo['veh_owner_name'])?$vehicleInfo['veh_owner_name']:""; ?>"
                                            id="veh_owner_name" disabled required>
                                        <label class="form-label">Owner Name</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="number" class="form-control" name="veh_owner_phone" disabled
                                            value="<?php echo isset($vehicleInfo['veh_owner_phone'])?$vehicleInfo['veh_owner_phone']:""; ?>"
                                            id="veh_owner_phone" required>
                                        <label class="form-label">Phone No</label>
                                        <!-- <p><?php //echo json_encode($serialList) ?></p> -->
                                    </div>
                                </div>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="dealer_name"
                                            value="<?php echo isset($vehicleInfo['dealer_name'])?$vehicleInfo['dealer_name']:""; ?>"
                                            id="dealer_name" disabled required>
                                        <label class="form-label">Dealer Name</label>
                                    </div>
                                </div>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="number" class="form-control" name="dealer_phone"
                                            value="<?php echo isset($vehicleInfo['dealer_phone'])?$vehicleInfo['dealer_phone']:""; ?>"
                                            id="dealer_phone" readonly required>
                                        <label class="form-label">Dealer Phone No</label>
                                    </div>
                                </div>

                                <div class="form-group form-float">
                                    <div class="form-lines">
                                        <input type="hidden" class="form-control" name="s_serial_id"
                                            value="<?php echo isset($vehicleInfo['s_serial_id'])?$vehicleInfo['s_serial_id']:""; ?>"
                                            id="s_serial_id" readonly required>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-lines">
                                        <input type="hidden" class="form-control" name="dealer_id"
                                            value="<?php echo isset($vehicleInfo['dealer_id'])?$vehicleInfo['dealer_id']:""; ?>"
                                            id="dealer_id" readonly required>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-lines">
                                        <input type="hidden" class="form-control" name="dealer_id"
                                            value="<?php echo isset($vehicleInfo['s_state_id'])?$vehicleInfo['s_state_id']:""; ?>"
                                            id="s_state_id" readonly required>
                                    </div>
                                </div>

                                <div class="form-group form-float">
                                    <div class="form-lines">
                                        <input value="2" type="hidden" class="form-control show-tick"
                                            name="veh_company_id" id="veh_company_id" data-live-search="true" required>
                                        <?php /* <select class="form-control show-tick" name="veh_company_id"  id="veh_company_id" data-live-search="true" required>
                                            <option value="">--Select Company/ Brand Name--</option>
                                            <?php 
                                            foreach($company_list as $key=>$value)
                                            { ?>
                                        <option value="<?php echo $value['c_company_id']; ?>">
                                            <?php echo $value['c_company_name']; ?></option>
                                        <?php 
                                            }
                                            ?>
                                        </select> */ ?>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        
                                        <select class="form-control show-tick" name="veh_serial_no" id="veh_serial_no"
                                            data-live-search="true" required>
                                            <option value="">--Select Serial Number--</option>
                                            <?php 
                                            foreach($serialList as $key=>$value)
                                            { ?>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <select class="form-control show-tick" name="reason_inter_change" id="reason_inter_change"
                                            data-live-search="true" required>
                                            <option value="">--Reason for Interchange--</option>
                                            <option value="1">Accidentally Added</option>
                                            <option value="2">Faulty Device</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div id="fitment">
                                        <!-- <label class="form-label">Fitment is not done, Do you want to Skip the Fitment Entry ?</label><br />
                                        <input type="radio" name="fitment" id="male" value="Y" class="with-gap" checked>
                                        <label for="male">Yes</label>

                                        <input type="radio" name="fitment" id="female" value="N" class="with-gap">
                                        <label for="female" class="m-l-20">No</label> -->
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <br>
                                <div class="form-group form-float">
                                    <button class="btn btn-primary waves-effect" type="submit">INTER CHANGE</button>
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


<script src="<?php echo base_url() ?>public/js/pages/function/inter_change_device.js?t=<?php echo time(); ?>">
    </script>
    <?php $this->load->view('common/admin_login_css_js'); ?>
</body>

</html>