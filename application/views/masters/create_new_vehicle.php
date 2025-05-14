 <?php $this->load->view('common/admin_login_header'); ?>

 <?php
 $user_type=$this->session->userdata('user_type');
 ?>
 <script type="text/javascript">
var user_type = '<?php echo $user_type; ?>';
 </script>
 <!-- Bootstrap Material Datetime Picker Css -->
 <link
     href="<?php echo base_url() ?>public/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css"
     rel="stylesheet" />

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
                     Create New Entry
                 </h2>
             </div>
             <!-- Basic Validation -->
             <div class="row clearfix">
                 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                     <div class="card">
                         <div class="header">
                             <h2>CREATE NEW ENTRY</h2>
                         </div>
                         <div class="body">

                             <form id="form_validation" method="POST" enctype="multipart/form-data">
                                 <div class="row clearfix">
                                     <div class="col-sm-4">
                                         <div class="form-group">
                                             <div class="form-line">
                                                 <input type="text" style="color:red" readonly class="form-control"
                                                     name="veh_create_date" id="veh_create_date"
                                                     placeholder="Please choose date.."
                                                     value="<?php echo date('Y-m-d'); ?>" required>
                                             </div>
                                         </div>
                                     </div>
                                 </div>

                                 <div class="form-group form-float">
                                     <div class="form-line">
                                         <!--<input type="text" class="form-control" name="veh_owner_phone"  id="veh_owner_phone" required autocomplete="off" readonly="readonly" onfocus="javascript:this.removeAttribute('readonly')">-->
                                         <!--<label class="form-label">Owner Phone No *</label>-->
                                         <input type="text" class="form-control" name="veh_owner_phone"
                                             id="veh_owner_phone" maxlength="10" pattern="[0-9]{10}" required>
                                         <label class="form-label">Owner Phone No *</label>
                                     </div>
                                 </div>

                                 <input type="hidden" name="veh_owner_id" id="veh_owner_id">

                                 <div class="form-group form-float">
                                     <div class="form-line">
                                         <input type="text" class="form-control" name="veh_owner_name"
                                             id="veh_owner_name" autocomplete="off" readonly="readonly"
                                             onfocus="javascript:this.removeAttribute('readonly')">
                                         <label class="form-label">Owner Name</label>
                                     </div>
                                 </div>
                                 <div class="form-group form-float">
                                     <div class="form-line">
                                         <input type="email" class="form-control" name="veh_owner_email"
                                             id="veh_owner_email" autocomplete="off" readonly="readonly"
                                             onfocus="javascript:this.removeAttribute('readonly')">
                                         <label class="form-label">Owner Email</label>
                                     </div>
                                 </div>
                                 <div class="form-group form-float">
                                     <div class="form-line">
                                         <textarea cols="30" rows="5" name="veh_address" id="veh_address"
                                             class="form-control no-resize"></textarea>
                                         <label class="form-label">Address</label>
                                     </div>
                                 </div>

                                 <div>
                                     <input type="checkbox" id="scales" name="scales" onclick="rcCheckFunction()">
                                     <label for="scales">Brand New Vehicle No Rc Book</label>
                                 </div>

                                 <div class="form-group form-float">
                                     <div class="form-line">
                                         <input type="text" class="form-control" name="veh_rc_no" id="veh_rc_no">
                                         <label class="form-label">Vehicle RC No *</label>
                                     </div>
                                 </div>

                                 <div class="form-group form-float">
                                     <div class="form-line">
                                         <input type="text" class="form-control" name="veh_chassis_no"
                                             id="veh_chassis_no">
                                         <label class="form-label">Chassis No</label>
                                     </div>
                                 </div>
                                 <div class="form-group form-float">
                                     <div class="form-line">
                                         <input type="text" class="form-control" name="veh_engine_no"
                                             id="veh_engine_no">
                                         <label class="form-label">Engine No</label>
                                     </div>
                                 </div>


                                 <div class="form-group form-float">
                                     <div class="form-line">
                                         <select class="form-control show-tick" name="veh_make_no" id="veh_make_no"
                                             data-live-search="true">
                                             <option value="">--Select Vehicle Make--</option>
                                             <?php 
                                            foreach($make_list as $key=>$value)
                                            { ?>
                                             <option value="<?php echo $value['v_make_id']; ?>">
                                                 <?php echo $value['v_make_name']; ?></option>
                                             <?php 
                                            }
                                            ?>
                                         </select>
                                     </div>
                                 </div>


                                 <div class="form-group form-float">
                                     <div class="form-line">
                                         <select class="form-control show-tick" name="veh_model_no" id="veh_model_no"
                                             data-live-search="true">
                                             <option value="">--Select Vehicle Model--</option>

                                         </select>
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
                                             <?php
                                         if(isset($userinfo['veh_serial_no']) && (string)$userinfo['s_serial_number'])
                                        {
                                        ?>
                                             <option value='<?php echo $userinfo["veh_serial_no"] ?>'>
                                                 <?php echo $userinfo["s_serial_number"] ?></option>
                                             <?php
                                        }else{
                                        ?>
                                             <option value="">--Select Serial Number-- *</option>
                                             <?php
                                        }
                                        ?>

                                         </select>
                                     </div>
                                 </div>

                                 <!--<div class="form-group form-float">-->
                                 <!--    <div class="form-line">-->
                                 <!--        <select class="form-control show-tick" name="veh_serial_no" id="veh_serial_no"-->
                                 <!--            data-live-search="true" required>-->
                                 <!--            <option value="">--Select Serial Number / IMEI / ICCID--</option>-->


                                 <!--        </select>-->
                                 <!--    </div>-->
                                 <!--</div>-->

                                 <div class="form-group form-float">
                                     <div class="form-line">
                                         <select class="form-control show-tick" name="technician_id" id="technician_id"
                                             data-live-search="true">
                                             <option value="">--Select Technician--</option>
                                             <?php 
                                            foreach($technician_list as $key=>$value)
                                            { ?>
                                             <option value="<?php echo $value['user_id']; ?>">
                                                 <?php echo $value['user_name']; ?></option>
                                             <?php 
                                            }
                                            ?>
                                         </select>

                                         <!-- <select class="form-control show-tick" name="technician_id" id="technician_id"
                                             data-live-search="true" required>
                                             <option value="">--Select Technician--</option>
                                             <?php
                                            foreach ($technician_list as $key => $value) {
                                                $selected = "";
                                                if (isset($_GET['technician_id']) && (string)$_GET['technician_id'] === (string)$value['user_id']) {
                                                        $selected = 'selected="selected"';
                                                }
                                                ?>
                                            <option <?php echo $selected; ?>
                                                value="<?php echo $value['user_id']; ?>">
                                                <?php echo $value['user_name']; ?></option>
                                                <?php
                                                }
                                            ?>
                                                            
                                         </select> -->
                                     </div>
                                 </div>


                                 <div class="form-group form-float">
                                     <div class="form-line">
                                         <select class="form-control show-tick" name="state" id="state"
                                             data-live-search="true">
                                             <!--<option value="">--Select State--</option>-->
                                             <?php 
											foreach($stateList as $key=>$value)
											{ ?>
                                             <option value="<?php echo $value['id']; ?>">
                                                 <?php echo $value['s_name']; ?>
                                             </option>
                                             <?php 
											}
											?>
                                         </select>
                                     </div>
                                 </div>

                                 <div class="form-group form-float">
                                     <div class="form-line">
                                         <select class="form-control show-tick" name="veh_rto_no" id="veh_rto_no"
                                             data-live-search="true" required>
                                             <option value="">--Select RTO-- *</option>
                                         </select>
                                     </div>
                                 </div>

                                 <!-- <div class="form-group form-float">
                                     <div class="form-line">
                                         <select class="form-control show-tick" name="veh_rto_no" id="veh_rto_no"
                                             data-live-search="true" required>
                                             <option value="">--Select RTO--</option>
                                             <?php 
											//foreach($rto_list as $key=>$value)
											//{ ?>

                                             <option value="<?php //echo $value['rto_no']; ?>">
                                                 <?php //echo $value['rto_number']." - ".$value['rto_place'].""; ?>
                                             </option>
                                             <?php 
											//}
											?>
                                         </select>
                                     </div>
                                 </div> -->
                                 <?php /*
							<div class="form-group form-float">
                                    <div class="form-line">
										<select class="form-control show-tick" name="veh_speed"  id="veh_speed" data-live-search="true" required>
											<option value="">--Set Speed--</option>
											<?php 
											$speed=unserialize(VEHICLE_SPEED);
											foreach($speed as $key=>$value)
											{ ?>
                                 <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                 <?php 
											}
											?>
                                 </select>
                         </div>
                     </div> */ ?>


                     <div class="form-group form-float">
                         <div class="form-line">
                             <select class="form-control show-tick" name="veh_tac" id="veh_tac" data-live-search="true"
                                 required>
                                 <option value="">--Select Tac No--</option>
                             </select>
                         </div>
                     </div>
                     <div class="form-group form-float">
                         <div class="form-line">
                             <select class="form-control show-tick" name="veh_cat" id="veh_cat" data-live-search="true">
                                 <option value="">--Select Vehicle Category--</option>
                                 <option value="1"> TRUCK </option>
                                 <option value="2"> LORRY </option>
                                 <option value="3"> OFF ROAD </option>
                                 <option value="4"> BUS </option>
                                 <option value="5"> VAN </option>
                                 <option value="6"> CAR </option>
                                 <option value="7"> BIKES </option>
                             </select>
                         </div>
                     </div>

                     <!--div class="form-group form-float">
                                    <div class="form-line" -->
                     <input type="hidden" class="form-control" name="veh_speed" id="veh_speed" value="50">
                     <input type="hidden" class="form-control" name="veh_invoice_no" id="veh_invoice_no" value="INV">
                     <!--label class="form-label">Invoice No</label>
                                    </div>
                            </div -->
                     <?php

                                $date = date('Y-m-d');
                                $validity_to=date('Y-m-d', strtotime($date. ' + 1 year'));
                            ?>
                     <!--<div class="row clearfix">-->
                     <!--<div class="col-sm-4">-->
                     <!--<label class="form-label">Selling Price</label><br />-->
                     <!--    <div class="form-group">-->
                     <!--        <div class="form-line">-->
                     <!--            <input type="number" class="form-control" name="selling_price" step="1"  id="selling_price" placeholder="Selling Price" value="" required>-->
                     <!--        </div>-->
                     <!--    </div>-->
                     <!--</div>-->
                     <!--<div class="col-sm-4">-->
                     <!--                        <label class="form-label">Validity To</label><br />-->
                     <!--                            <div class="form-group">-->
                     <!--                                <div class="form-line">-->
                     <!--                                    <input type="text" class="datetimepicker form-control" name="validity_to"  id="validity_to" placeholder="Please choose date.." value="<?php echo $validity_to; ?>" required>-->
                     <!--                                </div>-->
                     <!--                            </div>-->
                     <!--                        </div>-->
                     <!--                    </div>-->

                     <div class="row clearfix">
                         <div class="col-sm-4">
                             <label class="form-label">No Of Panic Button</label><br />
                             <div class="form-group">
                                 <div class="form-line">
                                     <input type="number" class="form-control" name="panic_button" id="panic_button"
                                         placeholder="Please Enter Panic Button Count">
                                 </div>
                             </div>
                         </div>
                         <div class="col-sm-4">
                             <label class="form-label">Validity To</label><br />
                             <div class="form-group">
                                 <div class="form-line">
                                     <input type="text" class="datetimepicker form-control" name="validity_to"
                                         id="validity_to" placeholder="Please choose date.."
                                         value="<?php echo $validity_to; ?>" required>
                                 </div>
                             </div>
                         </div>
                         <div class="col-sm-4">
                             <label class="form-label">Validity Validation</label><br />

                             <div class="form-group form-float">
                                 <div class="form-line">
                                     <select class="form-control show-tick" name="validity_validation"
                                         id="validity_validation" required>
                                         <option value="">--Select Validity Validation--</option>
                                         <option value="1">OLD: 1Year</option>
                                         <option value="2">NEW: 2Year</option>
                                     </select>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="row clearfix">
                         <div class="col-sm-4">
                             <label class="form-label">Registration Date</label><br />
                             <div class="form-group">
                                 <div class="form-line">
                                     <input type="text" class="datetimepicker form-control" name="registration_date"
                                         id="registration_date" placeholder="Please choose date..">
                                 </div>
                             </div>
                         </div>
                     </div>


                     <div class="form-group form-float">
                         <div class="form-line">
                             <textarea cols="30" rows="5" name="remarks" id="remarks"
                                 class="form-control no-resize"></textarea>
                             <label class="form-label">Remarks</label>
                         </div>
                     </div>
                     <!--<div class="form-group form-float">-->
                     <!--  <div class="form-line">-->
                     <!--	<label class="form-label">Vehicle Owner ID Proof </label><br />-->
                     <!--		<div class="body">-->
                     <!--				<div class="fallback">-->
                     <!--					<input type="file" id="vehicle_owner_id_proof" name="vehicle_owner_id_proof"  accept="image/*" required/>-->
                     <!--				</div>									-->
                     <!--		</div>	-->
                     <!--                             <div class="help-info" style="color:#1F91F3">Allowed image formats : <b>jpg,jpeg,gif,png,bmp</b> (Max 5 MB)</div>	-->
                     <!--	</div>-->
                     <!--                       <input type="hidden" id="vehicle_owner_id_proof_photo" name="vehicle_owner_id_proof_photo" value=""  />								-->
                     <!--</div>-->
                     <!--<div class="form-group form-float">-->
                     <!--  <div class="form-line">-->
                     <!--	<label class="form-label">Vehicle Owner Photo </label><br />-->
                     <!--		<div class="body">-->
                     <!--				<div class="fallback">-->
                     <!--					<input type="file" id="vehicle_owners" name="vehicle_owners"  accept="image/*" required/>-->
                     <!--				</div>									-->
                     <!--		</div>	-->
                     <!--                             <div class="help-info" style="color:#1F91F3">Allowed image formats : <b>jpg,jpeg,gif,png,bmp</b> (Max 5 MB)</div>	-->
                     <!--	</div>-->
                     <!--                       <input type="hidden" id="vehicle_owners_photo" name="vehicle_owners_photo" value=""  />								-->
                     <!--</div>-->
                     <!--<div class="form-group form-float">-->
                     <!--  <div class="form-line">-->
                     <!--	<label class="form-label">RC Book Photo </label><br />-->
                     <!--		<div class="body">-->
                     <!--				<div class="fallback">-->
                     <!--					<input type="file" id="rc_book" name="rc_book"  accept="image/*"/>-->
                     <!--				</div>									-->
                     <!--		</div>	-->
                     <!--                             <div class="help-info" style="color:#1F91F3">Allowed image formats : <b>jpg,jpeg,gif,png,bmp</b> (Max 5 MB)</div>	-->
                     <!--	</div>-->
                     <!--                       <input type="hidden" id="rc_book_photo" name="rc_book_photo" value=""  />								-->
                     <!--</div>						-->
                     <div class="form-group form-float">
                         <div class="form-line">
                             <label class="form-label">Device Photo</label><br />
                             <div class="body">
                                 <div class="fallback">
                                     <input type="file" id="upload_governer_photo" name="upload_governer_photo"
                                         accept="image/*" />
                                 </div>
                             </div>
                             <div class="help-info" style="color:#1F91F3">Allowed image formats :
                                 <b>jpg,jpeg,jfif,gif,png,bmp</b> (Max 5 MB)</div>
                         </div>
                         <input type="hidden" id="veh_speed_governer_photo" name="veh_speed_governer_photo" value="" />
                     </div>

                     <div class="form-group form-float">
                         <div class="form-line">
                             <label class="form-label">Vehicle Photo</label><br />
                             <div class="body">
                                 <div class="fallback">
                                     <input type="file" id="upload_vehicle_photo" name="upload_vehicle_photo"
                                         accept="image/*" />
                                 </div>
                             </div>
                             <div class="help-info" style="color:#1F91F3">Allowed image formats :
                                 <b>jpg,jpeg,jfif,gif,png,bmp</b> (Max 5 MB)</div>
                         </div>
                         <input type="hidden" id="veh_photo" name="veh_photo" value="" />
                     </div>
                     <div class="form-group">
                     <div id= "fitment">
                                    <label class="form-label">Fitment is not done, Do you want to Skip the Fitment Entry?</label><br />
                                     <input type="radio" name="fitment" id="male" value="Y" class="with-gap" checked>
                                     <label for="male">Yes</label>
            
                                     <input type="radio" name="fitment" id="female" value="N" class="with-gap">
                                    <label for="female" class="m-l-20">No</label>
                                     
                                </div>
                     </div>
                     <div class="form-group form-float">
                         <button class="btn btn-primary waves-effect" type="submit">SUBMIT</button>
                         <button class="btn bg-blue-grey waves-effect" type="button"
                             onClick="return resetall();">RESET</button>
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


     <script src="<?php echo base_url() ?>public/js/pages/function/create_vehicle.js?t=<?php echo 2; ?>"></script>
     <?php $this->load->view('common/admin_login_css_js'); ?>
     <script type="text/javascript">
     $(document).ready(function() {
         /* if($('#veh_company_id option').length===2 && ''+user_type!='0')
         {
             $('#veh_company_id option:eq(1)').prop('selected','selected');
             setTimeout(function(){
             $('#veh_company_id').trigger('change');
             },1000)
         } */

         setTimeout(function() {
             if ($('#veh_tac option').length === 2) {
                 $('#veh_tac option[value=AK9123]').prop('selected', 'selected');
                 $('#veh_tac').selectpicker('refresh');
             }
         }, 1000)

         document.getElementById('validity_to').value = "";

         document.getElementById('validity_validation').value = "";

     });


     document.getElementById("veh_owner_phone").addEventListener("input", function() {
         var mobileField = document.getElementById("veh_owner_phone");
         var mobileNumber = mobileField.value;
         mobileNumber = mobileNumber.replace(/\D/g, "");
         mobileField.value = mobileNumber;
     });
     </script>
 </body>

 </html>