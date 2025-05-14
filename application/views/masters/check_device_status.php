 <?php $this->load->view('common/admin_login_header'); ?>
 <?php
    $user_type = $this->session->userdata('user_type');
    ?>
 <script type="text/javascript">
     var user_type = '<?php echo $user_type; ?>';
 </script>

 <style>
     .narrow-table {
         width: 50%;
         /* Adjust the width as needed */
     }

     #clear-button {
         cursor: pointer;
         /* Change the cursor to a hand pointer */
     }
 </style>

 <!-- Bootstrap Material Datetime Picker Css -->

 <link href="<?php echo base_url() ?>public/css/check_status_table.css" rel="stylesheet">
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

         <div class="container-fluid" style="padding: 0px">

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

                             <h2>Where is My Device?</h2>

                         </div>

                         <div class="body">

                             <form id="search_form_validation" method="POST" enctype="multipart/form-data">
                                 <!--                     <div class="form-group form-float">-->
                                 <!--                         <div class="form-line">-->
                                 <!--                             <input type="text" class="form-control" name="imei_no" id="imei_no" minlength="8" value="" required>-->
                                 <!--                             <label class="form-label">Enter IMEI Number</label>-->
                                 <!--                         </div>-->
                                 <!--                     </div>-->
                                 <!--<div class="form-group form-float">-->
                                 <!--                         <button class="btn btn-primary waves-effect" id="check_imei_btn" type="button" >SUBMIT</button>-->
                                 <!--</div>-->

                                 <div class="row clearfix">
                                     <div class="col-sm-4" style="<?php echo $dealerNone; ?>">
                                         <div class="form-group form-float">
                                             <div class="form-line">
                                                 <input type="text" class="form-control" name="imei_no" id="imei_no" minlength="8" value="" required>
                                                 <label class="form-label">Enter IMEI Number</label>
                                             </div>
                                         </div>
                                     </div>

                                     <div class="col-sm-4">
                                         <div class="form-group form-float">
                                             <button class="btn btn-primary waves-effect" id="check_imei_btn" type="button">SUBMIT</button>
                                         </div>
                                     </div>
                                     <div class="col-sm-2" style="margin-left: -200px;"> <!-- Adjust the margin-left as needed -->
                                         <div class="form-group form-float" id="console_button_container" style="display: none;">
                                             <button class="btn btn-warning waves-effect" id="console_button" type="button" onclick="redirectToConsole()">
                                                 Go to Console
                                             </button>
                                         </div>
                                         <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Open Modal</button> -->

                                     </div>


                                 </div>

                             </form>

                             <div id="resultOfVechStatus" style="display: none;">
                                 <div id="info">
                                     <!--                     <div class="form-group form-float">-->
                                     <!--                         <button class="btn btn-warning waves-effect" id="console_button" type="button" onclick="redirectToConsole()">Go to Console</button>-->
                                     <!--</div>-->

                                     <div id="storage" class="p-2">
                                         <h3>Device Stage</h3>
                                         <table class="table">
                                             <thead class="thead-dark">
                                                 <tr>
                                                     <th scope="col">Stock Inward</th>
                                                     <th scope="col">Distributor</th>
                                                     <th scope="col">Dealer</th>
                                                     <th scope="col">Certificate</th>
                                                     <th scope="col">LIVE Status</th>
                                                 </tr>
                                                 </tr>
                                             </thead>
                                             <tbody>
                                                 <tr>
                                                     <td id="stockBy">-</td>
                                                     <td id="distributorName">-</td>
                                                     <td id="dealerName">-</td>
                                                     <td id="state">-</td>
                                                     <td id="liveStatus"><span class="badge badge-danger">Inactive</span></td>
                                                 </tr>
                                                 <tr>

                                                     <td id="stockOn">-</td>
                                                     <td id="assignToDistributorOn">-</td>
                                                     <td id="assingToDealerOn">-</td>
                                                     <td id="certificate">Not sale</td>
                                                     <td id=""></td>
                                                 </tr>
                                                 <tr>
                                                     <td></td>
                                                     <?php if (check_permission($user_type, 'stock_to_admin')) { ?>
                                                         <td id="returnToAdmin"></td>
                                                     <?php } else { ?>
                                                         <td></td>
                                                     <?php } ?>
                                                     <?php if (check_permission($user_type, 'stock_to_distributor')) { ?>
                                                         <td id="returnToDistributor"></td>
                                                     <?php } else { ?>
                                                         <td></td>
                                                     <?php } ?>
                                                     <td></td>
                                                     <td></td>
                                                 </tr>
                                             </tbody>
                                         </table>
                                     </div>

                                     <div id="information" class="p-2">
                                         <h3>Device Information</h3>
                                         <table class="table">
                                             <thead class="thead-dark">
                                                 <tr>
                                                     <th scope="col">IMEI</th>
                                                     <th scope="col">Serial No </th>
                                                     <th scope="col">SIM 1 </th>
                                                     <th scope="col">SIM 2 </th>
                                                     <th scope="col">ICCID</th>
                                                     <th scope="col">Reg No</th>
                                                 </tr>
                                             </thead>
                                             <tbody>
                                                 <tr>
                                                     <td id="imei">-</td>
                                                     <td id="serialNo">-</td>
                                                     <td id="sim1Num"><span class="text-danger">NIL</span></td>
                                                     <td id="sim2Num"><span class="text-danger">NIL</span></td>
                                                     <td id="iccidNo">-</td>
                                                     <td id="regVehicle">-</td>
                                                 </tr>
                                             </tbody>
                                         </table>
                                     </div>

                                     <div id="ota_information" class="p-2">
                                         <h3>Alerts</h3>
                                         <table class="table narrow-table">
                                             <thead class="thead-dark">
                                                 <tr>
                                                     <th scope="col">Alert type</th>
                                                     <th scope="col">Time</th>
                                                 </tr>
                                             </thead>
                                             <tbody>
                                                 <tr>
                                                     <td id="alert_type">-</td>
                                                     <td id="alert_time">-</td>
                                                 </tr>
                                                 <!--<tr>-->
                                                 <!--   <td id="clear-button"><span class="badge badge-danger" style="padding: 10px 15px;" onclick="clear_ota()">Clear</span></td>-->
                                                 <!--</tr>-->
                                             </tbody>
                                         </table>
                                     </div>

                                     <div id="info" class="p-2">
                                         <h3>Customer Info</h3>
                                         <table class="table">
                                             <thead class="thead-dark">
                                                 <tr>
                                                     <th scope="col">Customer Name</th>
                                                     <th scope="col">Phone</th>
                                                     <th scope="col">Email</th>
                                                     <th scope="col">EXP Date</th>
                                                     <th scope="col">Certificate status</th>
                                                     <th scope="col">Track Now</th>
                                                 </tr>
                                                 </tr>
                                             </thead>
                                             <tbody>
                                                 <tr>
                                                     <td id="custName">-</td>
                                                     <td id="custPhone">-</td>
                                                     <td id="custEmail">-</td>
                                                     <td id="expDate">-</td>
                                                     <td id="certStatus"><span class="text-info">InActive</span></td>
                                                     <td id="trackNow"><span class="text-info"><a href="#">-</a></span></td>
                                                 </tr>
                                             </tbody>
                                         </table>
                                     </div>
                                     <div id="Device_info" class="p-2">
                                         <h3>Device Log</h3>
                                         <table class="table">
                                             <thead class="thead-dark">
                                                 <tr>
                                                     <th scope="col">Date & Time</th>
                                                     <th scope="col">Serial Number</th>
                                                     <th scope="col">Event Type</th>
                                                     <th scope="col">Comment</th>
                                                     <th scope="col">By</th>
                                                 </tr>
                                             </thead>
                                             <tbody id="deviceLog">
                                             </tbody>
                                         </table>
                                     </div>
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

     <!-- The Modal -->
     <div class="modal" id="myModal">
         <div class="modal-dialog">
             <div class="modal-content">
                 <!-- Modal Header -->
                 <div class="modal-header">
                     <h4 class="modal-title">Assign State</h4>
                     <button type="button" class="close" data-dismiss="modal">
                         &times;
                     </button>
                 </div>

                 <!-- Modal body -->
                 <div class="modal-body">
                     <form  id="assign_state_validation" action="#" method="post">
                         <div class="form-group form-float">
                             <div class="form-line">
                                <label>IMEI Number</label>
                             <input type="text" id="hide_imei_number" readonly class="form-control" name="hide_imei_number" placeholder="1234567890" />
                             </div>
                         </div>
                         <div class="form-group form-float">
                             <div class="form-line">
                                 <select required class="form-control show-tick" name="state_id" id="state_id" data-live-search="true">
                                     <option value="">--Select State--</option>
                                     <?php
                                        foreach ($stateList as $key => $value) {
                                            $selected = "";
                                            if (isset($_GET['state_id']) && (string)$_GET['state_id'] === (string)$value['id']) {
                                                $selected = 'selected="selected"';
                                            }
                                        ?>
                                         <option <?php echo $selected; ?> value="<?php echo $value['id']; ?>">
                                             <?php echo $value['s_name']; ?></option>
                                     <?php
                                        }
                                        ?>
                                 </select>
                             </div>
                         </div>

                            <button type="button" id="assign_state" name="assign_state" class="btn btn-primary">Assign State</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">
                                Close
                            </button>
                     </form>
                 </div>

                 <!-- Modal footer -->
                 <div class="modal-footer">
                     <!-- <button type="button" class="btn btn-danger" data-dismiss="modal">
                         Close
                     </button> -->
                 </div>
             </div>
         </div>
     </div>

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


     <script>
         document.getElementById("check_imei_btn").addEventListener("click", function() {
             // Show the "Go to Console" button
             document.getElementById("console_button_container").style.display = "block";
         });
     </script>
     <script src="<?php echo base_url() ?>public/js/pages/function/dateFunction.js?t=<?php echo time(); ?>"></script>

     <script src="<?php echo base_url() ?>public/js/pages/function/add_vehicle_serial_number.js?t=<?php echo time(); ?>"></script>

     <?php $this->load->view('common/admin_login_css_js'); ?>

     <script>
         // 	 function clear_ota(){
         //         console.log('hii')
         //     }
     </script>


 </body>

 </html>