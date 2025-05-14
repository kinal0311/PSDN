<?php $this->load->view('common/admin_login_header'); ?>
<?php
$user_type = $this->session->userdata('user_type');
?>
<script type="text/javascript">
    var user_type = '<?php echo $user_type; ?>';
</script>


<!-- Bootstrap Material Datetime Picker Css -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<link href="<?php echo base_url() ?>public/css/check_status_table.css" rel="stylesheet">
<link href="<?php echo base_url() ?>public/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css"
      rel="stylesheet"/>


<!-- Wait Me Css -->

<link href="<?php echo base_url() ?>public/plugins/waitme/waitMe.css" rel="stylesheet"/>


<!-- Bootstrap Select Css -->

<link href="<?php echo base_url() ?>public/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet"/>

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

                        <h2>Device - Data Check</h2>


                        <?php if(check_permission($user_type,'menu_product_create')){ ?>
                            <!--<ul class="header-dropdown m-r--5">-->
                            <!--    <li class="dropdown">-->
                            <!--        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">-->
                            <!--            <i class="material-icons">more_vert</i>-->
                            <!--        </a>-->
                            <!--        <ul class="dropdown-menu pull-right">-->
                            <!--           <li><a id="saveBtn">Save</a></li>-->
                            <!--            <li><a href="<?php echo base_url().'admin/view_imei_saved_history'; ?>">View Saved History</a></li>-->

                            <!--        </ul>-->
                            <!--    </li>-->
                            <!--</ul>-->
                        <?php } ?>
                    </div>

                    <div class="body">


                        <form id="search_form_validation" method="POST" enctype="multipart/form-data">
                            <div class="row clearfix">
                                <div class="col-sm-4" style="<?php echo $dealerNone; ?>">
                                    <div class="form-group ">
                                        <div class="form-line">

                                            <input type="text" class="form-control" name="imei_no" id="imei_no"
                                                   minlength="8"
                                                   value="<?php echo $imei; ?>" required>
                                            <label class="form-label">Enter IMEI Number</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <div class="form-line">

                                            <input type="text" class="datetimepicker form-control"
                                                   value="<?php echo $date; ?>" name="start_date" id="start_date"
                                                   placeholder="Please choose Date..">
                                        </div>
                                    </div>


                                </div>


                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <div class="form-line">

                                            <input type="text" class="datetimepicker form-control"
                                                   value="<?php echo $startTime; ?>" name="start_time" id="start_time"
                                                   placeholder="Please choose Date..">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php
                                            $dateValue = date('h:i');
                                            ?>
                                            <input type="text" class="datetimepicker form-control"
                                                   value="<?php echo $endTime; ?>" name="end_time" id="end_time"
                                                   placeholder="Please choose Date..">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-float">
                                        <button class="btn btn-primary waves-effect" id="check_imei_data_btn" type="button">
                                            SUBMIT
                                        </button>
                                    </div>
                                </div>
                                <div class="col-sm-2" style="margin-left: -200px;"> <!-- Adjust the margin-left as needed -->
                                    <div class="form-group form-float" id="console_button_container" style="display: none;">
                                        <button class="btn btn-warning waves-effect" id="console_button" type="button" onclick="redirectToConsole()">
                                            Go to Console
                                        </button>
                                    </div>
                                </div>


                            </div>


                        </form>
                        <!--style="display: none;"-->
                        <div class="loader" id="resultOfVechStatusloader"></div>
                        <div id="resultOfVechStatus" style="display: none;">
                           
                            <!--  <div id="healthD">
                                  <h3>Health Data</h3>
                                  <div id="data">
                                      <table class="table">
                                          <thead class="thead-dark">
                                          <tr>
                                              <th scope="col">Vendor</th>
                                              <th scope="col">Frimware V</th>
                                              <th scope="col">IMEI</th>
                                              <th scope="col">Server Reched</th>
                                              <th scope="col">Battery%</th>
                                              <th scope="col">Batt thershold</th>
                                              <th scope="col">Mem %</th>
                                              <th scope="col">Data Interval</th>
                                              <th scope="col">Input Val</th>
                                              <th scope="col">Output Val</th>
                                              <th scope="col">ADC 1 Val</th>
                                              <th scope="col">ADC 2 Val</th>
                                          </tr>
                                          </tr>
                                          </thead>
                                          <tbody id="healthDataBody">
                                          <tr>

                                          </tr>
                                          </tbody>
                                      </table>
                                  </div>

                                  <div>
                                      <div class="d-flex align-items-center mb-3">
                                          <h3 class="p-0 m-0 mr-2">Check</h3>
                                          <input type="email" class="form-control" id="num" placeholder="xxxxxx">
                                          <h3 class="p-o m-0 mx-2">Device Datas</h3>
                                          <button class="btn btn-primary btn-sm">FIND</button>
                                      </div>

                                      <div id="data" class="overflow-auto">
                                          <table class="table overflow-auto">
                                              <thead class="thead-dark">
                                              <tr>
                                                  <th scope="col">S.no</th>
                                                  <th scope="col">Vendor_id</th>
                                                  <th scope="col">firmware_v</th>
                                                  <th scope="col">packet_type</th>
                                                  <th scope="col">packet_sta</th>
                                                  <th scope="col">IMEI</th>
                                                  <th scope="col">vehicle_reg_no</th>
                                                  <th scope="col">latitude</th>
                                                  <th scope="col">longitude</th>
                                                  <th scope="col">gps_sent</th>
                                                  <th scope="col">server_reached</th>
                                                  <th scope="col">ignition</th>
                                                  <th scope="col">battery_status</th>
                                                  <th scope="col">emergency_status</th>

                                              </tr>
                                              </tr>
                                              </thead>
                                              <tbody id="historyDataBody">

                                              </tbody>
                                          </table>
                                      </div>
                                  </div>
                                  <div>
                                      <h3 class="p-o m-0 mt-2">Verify Plots on Map</h3>
                                  </div>




                              </div>-->
                            <div id="healthD">
                                <div id="healthDdata">
                                    <h3>Health Data</h3>
                                    <div>
                                        <table class="table">
                                            <thead class="thead-dark">
                                            <tr>
                                                <th scope="col">Vendor</th>
                                                <th scope="col">Frimware V</th>
                                                <th scope="col">IMEI</th>
                                                <th scope="col">Server Reched</th>
                                                <th scope="col">Battery%</th>
                                                <th scope="col">Batt thershold</th>
                                                <th scope="col">Mem %</th>
                                                <th scope="col">Data Interval</th>
                                                <th scope="col">Input Val</th>
                                                <th scope="col">Output Val</th>
                                                <th scope="col">ADC 1 Val</th>
                                                <th scope="col">ADC 2 Val</th>

                                            </tr>
                                            </tr>
                                            </thead>
                                            <tbody id="healthDataBody">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div id="healthDataEmpty"></div>
                                </br>
                                <div>
                                    <div class="d-flex align-items-center mb-3">
                                        <!--  <h3 class="p-0 m-0 mr-2">Check</h3>
                                          <input type="email" class="form-control" id="num" placeholder="xxxxxx">
                                          <h3 class="p-o m-0 mx-2">Device Datas</h3>
                                          <button class="btn btn-primary btn-sm" id="check_imei_his_data_btn">FIND</button>-->

                                        <form id="search_form_validatioan" style="display: none" method="POST"
                                              enctype="multipart/form-data">
                                            <div class="form-group form-float">
                                                <!--      <div class="form-line">

                                                          <input type="text" class="form-control" name="his_imei_no" id="imei_no" minlength="8" value="869247040617091" required>
                                                          <label class="form-label">Enter IMEI Number / Serial Number</label>
                                                      </div>-->

                                                <div class="form-line">

                                                    <input type="text" class="form-control" name="imei_count"
                                                           id="imei_count" minlength="8" value="100" required>
                                                    <label class="form-label">Enter number of record to fetch</label>
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                                <button class="btn btn-primary waves-effect"
                                                        id="check_imei_his_data_btn" type="button">SUBMIT
                                                </button>
                                            </div>

                                        </form>

                                    </div>
                                    <div id="resultOfVechHisStatus" style="display: none;">
                                        <div class="overflow-auto table-wrap"
                                        >
                                            <table class="table">
                                                <thead class="thead-dark">
                                                <tr>
                                                    <th scope="col">S.no</th>
                                                    <th scope="col">Packet Type</th>
                                                    <th scope="col">Packet Status</th>
                                                    <th scope="col">GPS Sent</th>
                                                    <th scope="col">Ignition</th>
                                                    <th scope="col">Speed</th>
                                                    <th scope="col">Signal strength</th>
                                                    <th scope="col">Alert ID</th>
                                                    <th scope="col">Latitude </th>
                                                    <th scope="col">Lat direction</th>
                                                    <th scope="col">Longitude</th>
                                                    <th scope="col">Long direction</th>
                                                    <th scope="col">Altitude</th>
                                                    <th scope="col">Main Power</th>
                                                    <th scope="col">V Mode</th>
                                                    <!--<th scope="col">server_reached</th>-->
                                                    <!--<th scope="col">ignition</th>-->
                                                    <!--<th scope="col">battery_status</th>-->
                                                    <!--<th scope="col">emergency_status</th>-->

                                                </tr>
                                                </tr>
                                                </thead>
                                                <tbody id="healthHistoryDataBody">

                                                </tbody>
                                            </table>
                                        </div>

                                        <div>
                                            <h3 class="p-o m-0 mt-2">Verify Plots on Map</h3>
                                        </div>
                                        <div id="map" style="height: 300px; width: 100%"></div>
                                    </div>

                                </div>
                                <div id="NoVechHisStatus" style="display: none;"></div>


                            </div>
                        </div>

                        <div id="resultOfVechStatusEmpty" style="display: none;">
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <!-- #END# Basic Validation -->


    </div>

</section>


<!--- Model Dialog --->

<!-- Default Size -->

<div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h4 class="modal-title" id="defaultModalLabel">Modal title</h4>

            </div>

            <div class="modal-body" id="defaultModalBody">


            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">OK</button>

            </div>

        </div>

    </div>

</div>

<!--- Model Dialog ---->


<?php $this->load->view('common/admin_login_css_js'); ?>

<script src="<?php echo base_url() ?>public/js/pages/function/vehiclehistory.js"></script>

<script src="<?php echo base_url() ?>public/js/pages/function/add_vehicle_serial_number.js?t=<?php echo time(); ?>"></script>

<!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_F76FCq1xJtvasEC9OxRguRKHxfVJFXc&callback=initMap"-->
<!--        async defer></script>-->

    <script>
        document.getElementById("check_imei_data_btn").addEventListener("click", function () {
            // Show the "Go to Console" button
            document.getElementById("console_button_container").style.display = "block";
        });
    </script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAx3BvK2E1sHk6jTJGF8ty7Brkh-nP4gd4&callback=initMap"
        async defer></script>
</body>
</html>



