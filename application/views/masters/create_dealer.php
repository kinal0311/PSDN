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
  <?php $this->load->view('common/left_side_bar'); 
  

  ?>


    <section class="content">
        <div class="container-fluid">
            <div class="block-header" style="display:none;">
                <h2>
                    Create New User
                </h2>
            </div>
            <!-- Basic Validation -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>Create New User</h2>                           
                        </div>
                        <div class="body">
                            <form id="form_validation" method="POST" enctype="multipart/form-data">

                                  <div class="form-group form-float">
                                    <div class="form-line">
                                        <select class="form-control show-tick" name="user_company_id" id="user_company_id" data-live-search="true" required>
                                            <option value="">-- Company Name --</option>
                                            <?php
                                                foreach($company_list as $key=>$value)
                                                {                                                 
                                            ?>
                                            <option  value="<?php echo $value['c_company_id']; ?>"><?php echo $value['c_company_name']; ?></option>
                                            <?php                                                           
                                            }                                                           
                                            ?>  
                                            
                                        </select>
                                     </div>
                                </div>

                                <div class="form-group">
                                   <label class="form-label">User Type</label>

                                   <?php
                                   $user_type=$this->session->userdata('user_type');  
                                    if($user_type == 0){ ?>                                  
                                    <input type="radio" name="user_type" id="s_user_type0" value="4" class="radio-col-deep-purple" required>
                                    <label for="s_user_type0" class="m-l-20">Sub Admin</label>
                                    <input type="radio" name="user_type" id="s_user_type4" value="6" class="radio-col-deep-purple" required>
                                    <label for="s_user_type4" class="m-l-20">Technician</label>
                                    <?php }else  if($user_type == 4){ ?>
                                    <input type="radio" name="user_type" id="s_user_type2" value="2" class="radio-col-deep-purple" required>
                                    <label for="s_user_type2" >Distributor</label>
                                    <input type="radio" name="user_type" id="s_user_type4" value="6" class="radio-col-deep-purple" required>
                                    <label for="s_user_type4" class="m-l-20">Technician</label>
                                    <?php }else  if($user_type == 2){ ?>                                   
                                    <input type="radio" name="user_type" id="s_user_type1" value="1" class="radio-col-deep-purple"  checked="checked" required>
                                    <label for="s_user_type1">Dealer</label>                                   
                                    <?php }else  if($user_type == 1){ ?>                                   
                                    <input type="radio" name="user_type" id="s_user_type4" value="6" class="radio-col-deep-purple"  checked="checked" required>
                                    <label for="s_user_type4">Technician</label>                                   
                                    <?php } ?>                                  

                                </div>

                                <div class="form-group" id="fitment_access_div">
                                    <label for="male">Fitment Access</label>
                                    <input type="radio" name="fitment_access" id="fitment_access_yes" value="1" class="with-gap radio-col-deep-purple">
                                    <label for="fitment_access_yes">Yes</label>

                                    <input type="radio" name="fitment_access" id="fitment_access_no" value="0" class="with-gap radio-col-deep-purple" checked="checked">
                                    <label for="fitment_access_no" class="m-l-20 ">No</label>
                                </div>
                                

                                <div class="form-group form-float" style="display: none;" id="under_by_user">
                                    <div class="form-line">
                                        <select class="form-control show-tick" name="user_distributor_id"  id="user_distributor_id" data-live-search="true" required >
                                            <option value="">--Select User--</option>                                            
                                        </select>                                       
                                     </div>
                                 </div>   

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="name" required>
                                        <label class="form-label">Name</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="radio" name="gender" id="male" value="M" class="with-gap">
                                    <label for="male">Male</label>

                                    <input type="radio" name="gender" id="female" value="F" class="with-gap">
                                    <label for="female" class="m-l-20">Female</label>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="phone" name="phone" maxlength="10" pattern="[0-9]{10}" required>
                                        <label class="form-label">Phone</label>
                                    </div>
                                </div>                              
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="email" class="form-control" id="email" name="email" autocomplete="off" >
                                        <label class="form-label">Email</label>
                                    </div>
                                </div>


                                <!--<div class="form-group form-float">-->
                                <!--    <div class="form-line">-->
                                <!--        <input type="text" class="form-control" name="user_own_company" >-->
                                <!--        <label class="form-label">Company Name</label>-->
                                <!--    </div>-->
                                <!--</div>-->
                                <!--<div class="form-group form-float">-->
                                <!--    <div class="form-line">-->
                                <!--        <input type="text" class="form-control" name="gstin" >-->
                                <!--        <label class="form-label">GSTIN Number</label>-->
                                <!--    </div>-->
                                <!--</div>-->
                                <!--<div class="form-group form-float">-->
                                <!--    <div class="form-line">-->
                                <!--        <input type="text" class="form-control" name="invoice_prefix" >-->
                                <!--        <label class="form-label">Invoice Prefix</label>-->
                                <!--    </div>-->
                                <!--</div>-->

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <textarea name="description" cols="30" rows="5" class="form-control no-resize" required></textarea>
                                        <label class="form-label">Address</label>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="password" name="password" required>
                                        <label class="form-label">Password</label>
                                    </div>
                                </div>
                                <div class="form-group form-float" style="display: <?php if($user_type == 4){ echo "block"; } else { echo "none"; } ?>">
                                    <div class="form-line">
                                        <select class="form-control show-tick" name="user_states" data-live-search="true" required>
                                            <option value="">-- Select States --</option>
                                            <?php  
                                            foreach($states_list as $key=>$value)
                                            { ?>
                                            <option value="<?php echo $value['id']; ?>"><?php echo $value['s_name']; ?></option>  
                                            <?php 
                                            }
                                            ?>
                                        </select>
                                     </div>
                                </div>
                                <div class="form-group form-float" style="display: <?php if($user_type == 2){ echo "block"; } else { echo "none"; } ?>">
                                    <div class="form-line">
                                        <select class="form-control show-tick" name="user_rto" data-live-search="true" required>
                                            <option value="">-- Select Rto --</option>
                                            <?php  
                                            foreach($rto_list as $key=>$value)
                                            { ?>
                                            <option value="<?php echo $value['rto_no']; ?>"><?php echo $value['rto_number']." - ".$value['rto_place'].""; ?></option>   
                                            <?php 
                                            }
                                            ?>
                                        </select>
                                     </div>
                                </div>
                                <br>
                            <!--    <div class="form-group form-float">-->
                            <!--        <label class="form-label">Account Details</label>-->
                            <!--    </div>-->
                            <!--    <br>-->
                            <!--    <div class="form-group form-float">-->
                            <!--        <div class="form-line">-->
                            <!--            <input type="number" class="form-control" name="user_acc_number" >-->
                            <!--            <label class="form-label">Account Number</label>-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--    <div class="form-group form-float">-->
                            <!--        <div class="form-line">-->
                            <!--            <input type="text" class="form-control" name="user_acc_name" >-->
                            <!--            <label class="form-label">Account Name</label>-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--    <div class="form-group form-float">-->
                            <!--        <div class="form-line">-->
                            <!--            <input type="text" class="form-control" name="user_acc_ifsc_code" >-->
                            <!--            <label class="form-label">IFSC Code</label>-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--    <div class="form-group form-float">-->
                            <!--        <div class="form-line">-->
                            <!--            <input type="text" class="form-control" name="user_acc_branch" >-->
                            <!--            <label class="form-label">Branch</label>-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--<br>-->
                            <!--<div class="form-group form-float">-->
                            <!--    <label class="form-label">Documents Uploads</label>-->
                            <!--</div>-->
                            <!--<br>-->

                            <!--<div class="form-group form-float">-->
                            <!--    <div class="form-line">-->
                            <!--        <label class="form-label" for="upload_gst_certificate">GST certificate</label><br />                                  -->
                            <!--        <div class="body">-->
                            <!--            <div class="fallback">-->
                            <!--                <input name="file" type="file" id="upload_gst_certificate" name="upload_gst_certificate"  accept="image/*" />-->
                            <!--            </div>-->
                            <!--        </div>-->
                            <!--        <div class="help-info" style="color:#1F91F3" >Allowed image formats : <b>jpg,jpeg,gif,png,bmp</b> (Max 5 MB)</div>                              -->
                            <!--    </div>      -->
                            <!--    <input type="hidden" id="gst_certificate" name="gst_certificate" value="" />                                -->
                            <!--</div>-->
                            <!--<div class="form-group form-float">-->
                            <!--    <div class="form-line">-->
                            <!--        <label class="form-label" for="upload_id_proof">ID Proof</label><br />                                  -->
                            <!--        <div class="body">-->
                            <!--            <div class="fallback">-->
                            <!--                <input name="file" type="file" id="upload_id_proof" name="upload_id_proof"  accept="image/*" />-->
                            <!--            </div>-->
                            <!--        </div>-->
                            <!--        <div class="help-info" style="color:#1F91F3" >Allowed image formats : <b>jpg,jpeg,gif,png,bmp</b> (Max 5 MB)</div>-->
                            <!--    </div>      -->
                            <!--    <input type="hidden" id="id_proof" name="id_proof" value="" />                              -->
                            <!--</div>-->
                            <!--<div class="form-group form-float">-->
                            <!--    <div class="form-line">-->
                            <!--        <label class="form-label" for="upload_photo_personal">Dealer / Distributer Photo (Personal)</label><br />                                   -->
                            <!--        <div class="body">-->
                            <!--            <div class="fallback">-->
                            <!--                <input name="file" type="file" id="upload_photo_personal" name="upload_photo_personal"  accept="image/*" />-->
                            <!--            </div>-->
                            <!--        </div>-->
                            <!--        <div class="help-info" style="color:#1F91F3" >Allowed image formats : <b>jpg,jpeg,gif,png,bmp</b> (Max 5 MB)</div>-->
                            <!--    </div>      -->
                            <!--    <input type="hidden" id="photo_personal" name="photo_personal" value="" />                              -->
                            <!--</div>-->
                            <!--<div class="form-group form-float">-->
                            <!--    <div class="form-line">-->
                            <!--    <label class="form-label" for="upload_pan_card">PAN card</label><br />                                   -->
                            <!--        <div class="body">-->
                            <!--            <div class="fallback">-->
                            <!--                <input name="file" type="file" id="upload_pan_card" name="upload_pan_card"  accept="image/*" />-->
                            <!--            </div>-->
                            <!--        </div>-->
                            <!--        <div class="help-info" style="color:#1F91F3" >Allowed image formats : <b>jpg,jpeg,gif,png,bmp</b> (Max 5 MB)</div>-->
                            <!--    </div>      -->
                            <!--    <input type="hidden" id="pan_card" name="pan_card" value="" />                              -->
                            <!--</div>-->
                            <!--<div class="form-group form-float">-->
                            <!--    <div class="form-line">-->
                            <!--        <label class="form-label" for="upload_cancelled_cheque_leaf">Cancelled Cheque Leaf</label><br />                                  -->
                            <!--        <div class="body">-->
                            <!--            <div class="fallback">-->
                            <!--                <input name="file" type="file" id="upload_cancelled_cheque_leaf" name="upload_cancelled_cheque_leaf"  accept="image/*" />-->
                            <!--            </div>-->
                            <!--        </div>-->
                            <!--        <div class="help-info" style="color:#1F91F3" >Allowed image formats : <b>jpg,jpeg,gif,png,bmp</b> (Max 5 MB)</div>-->
                            <!--    </div>      -->
                            <!--    <input type="hidden" id="cancelled_cheque_leaf" name="cancelled_cheque_leaf" value="" />                                -->
                            <!--</div>-->
                            <!--<div class="form-group form-float">-->
                            <!--    <div class="form-line">-->
                            <!--        <label class="form-label" for="upload_profile_photo">Logo</label><br />                                   -->
                            <!--        <div class="body">-->
                            <!--            <div class="fallback">-->
                            <!--                <input name="file" type="file" id="upload_profile_photo" name="upload_profile_photo"  accept="image/*" />-->
                            <!--            </div>-->
                            <!--        </div>-->
                            <!--        <div class="help-info" style="color:#1F91F3" >Allowed image formats : <b>jpg,jpeg,gif,png,bmp</b> (Max 5 MB)</div>-->
                            <!--    </div>      -->
                            <!--    <input type="hidden" id="profile_photo" name="profile_photo" value="" />                                -->
                            <!--</div>-->
                            
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
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin sodales orci ante, sed ornare eros vestibulum ut. Ut accumsan
                            vitae eros sit amet tristique. Nullam scelerisque nunc enim, non dignissim nibh faucibus ullamcorper.
                            Fusce pulvinar libero vel ligula iaculis ullamcorper. Integer dapibus, mi ac tempor varius, purus
                            nibh mattis erat, vitae porta nunc nisi non tellus. Vivamus mollis ante non massa egestas fringilla.
                            Vestibulum egestas consectetur nunc at ultricies. Morbi quis consectetur nunc.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">OK</button>
                        </div>
                    </div>
                </div>
            </div>
    <!--- Model Dialog ---->
    
     <script src="<?php echo base_url() ?>public/js/pages/function/create_dealer.js?t=<?php echo time(); ?>"></script>
    <?php $this->load->view('common/admin_login_css_js'); ?> 
    <script type="text/javascript">
        $(document).ready(function(){
            if($('#user_company_id option').length===2 && ''+user_type!='0')
            {
                $('#user_company_id option:eq(1)').prop('selected','selected');
                setTimeout(function(){
                $('#user_company_id').trigger('change');
                },1000)
            }
        })
    </script>       
</body>
</html>