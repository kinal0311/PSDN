<?php
            //echo $value['veh_id'];
            // echo "<pre>"; print_r($imei);
            // echo "<pre>"; print_r($listofvehicles); exit;
            //echo hi(); exit;
            //$enc_veh_Id = urlencode($this->encrypt->encode($listofvehicles[0]['veh_id']));

            //echo "id".$enc_veh_Id; exit;

            $this->load->view('common/admin_login_header'); ?>
<?php
$user_type = $this->session->userdata('user_type');



?>
<!-- Bootstrap Material Datetime Picker Css -->
<!-- <link
     href="<?php echo base_url() ?>public/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css"
     rel="stylesheet" /> -->

<!-- Wait Me Css -->
<link href="<?php echo base_url() ?>public/plugins/waitme/waitMe.css" rel="stylesheet" />

<!-- Bootstrap Select Css -->
<link href="<?php echo base_url() ?>public/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

<style>
    #searchfiltersubmit {
        display: none;
    }
</style>
<style>
    .glyphicon {
        line-height: 2 !important;
    }

    .pagination>li>a,
    .pagination>li>span {
        border-radius: 50% !important;
        margin: 0 5px;
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
                    OTA list
                </h2>
            </div>
            <!-- Basic Validation -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                    OTA Param List
                            </h2>
                            <?php
                            $user_type = $this->session->userdata('user_type');
                            $redirectionBase = 'admin';
                            if ((string)$user_type === '1') {
                                $redirectionBase = 'admin';
                            }
                            $hideHomeFromDealer = '';
                            if ((string)$user_type === '1') {
                                $hideHomeFromDealer = 'display:none;';
                            }
                            ?>
                        </div>

                        <div class="body">

                       <div class="table-responsive">
                        <form action="<?php echo base_url() . $redirectionBase . '/ota_param'; ?>" name="searchfilter" id="searchfilter" method="get">
                            <input type="hidden" name="imei" value="<?php echo $imei; ?>">
                            <input type="hidden" name="offset" id="offset" value="<?php echo isset($_GET['offset']) ? $_GET['offset'] : 0; ?>">
                            <div class="form-group">
                                <div>
                                    <button class="btn btn-primary waves-effect" type="submit" id="searchfiltersubmit" name="searchfiltersubmit">Search</button>
                                </div>
                            </div>
                        </form>


                            
                                <table id="mytable" class="table table-bordred table-striped">

                                    <thead>
                                        <th>#</th>
                                        <th >Alert type </th>
                                        <th >Status</th>
                                        <th >Time</th>
                                        
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (count($listofvehicles) > 0) {

                                            $sno = 1;
                                            if (isset($_GET['offset']) && (int)$_GET['offset'] > 0) {
                                                $sno = (((int)$_GET['offset'] - 1) * 25) + 1;
                                            }
                                            foreach ($listofvehicles as $key => $value) {
                                        ?>
                                                <tr>
                                                    <td><?php echo $sno; ?></td>
                                                    <td><?php echo $value['alert_type']; ?></td>
                                                    <td><?php echo $value['device_status']; ?></td>
                                                    <td><?php echo $value['start_time']; ?></td>

                                                </tr>
                                            <?php
                                                $sno++;
                                            }
                                        } else {
                                            ?>
                                            <tr style=" text-align: center;">
                                                <td colspan="10">No Records Found</td>
                                            </tr>
                                        <?php
                                        }
                                        ?>

                                    </tbody>

                                </table>
                                        <div style="float:right;" id="pageformat"></div>
                                        <div class="col-sm-1">


                                        </div>
                                    </div>

    </section>

    <script src="<?php echo base_url() ?>public/js/pages/function/expiry_list.js"></script>
    <?php $this->load->view('common/admin_login_css_js'); ?>

    <script>

        $(function() {
            $('#pageformat').pagination({
                items: '<?php echo $totalNoOfVehicles; ?>',
                itemsOnPage: '<?php echo 25; ?>',
                cssStyle: 'light-theme',
                onPageClick: function(no) {
                    var offsetValue = $('#offset').val();
                    if (offsetValue == no) {

                    } else {
                        $('#offset').val(no);
                        $('#searchfiltersubmit').trigger('click');
                    }
                }
            });
            <?php
            if (isset($_GET['offset']) && (int)$_GET['offset'] > 0) {
            ?>
                $('#pageformat').pagination('selectPage', '<?php echo $_GET['offset']; ?>');
                if ('<?php echo intval($totalNoOfVehicles); ?>' < 25) {
                    console.log("<25");
                    $('#pageformat').pagination('selectPage', '1');
                } else {
                    console.log(">25");
                    // $('#offset').val(no);
                    $('#pageformat').pagination('selectPage', '<?php echo $_GET['offset']; ?>');
                }
            <?php
            }
            ?>
        });
    </script>
</body>

</html>