 <?php $this->load->view('common/admin_login_header'); ?>
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
                     Update Vehicle
                 </h2>
             </div>
             <!-- Basic Validation -->
             <div class="row clearfix">
                 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                     <div class="card">
                         <div class="header">
                             <div class="row">
                                 <div class="col-md-6">
                                     <h2>Device QRCode</h2>
                                 </div>
                                 <div class="col-md-4">
                                 </div>
                                 <div class="col-md-2">
                                     <button onclick="history.back()">Go Back</button>
                                 </div>
                             </div>
                         </div>
                         <div class="body">

                             <form id="form_validation" method="POST" enctype="multipart/form-data">
                                 <!-- <h1><?php //echo $imeiInfo['s_serial_id'];?></h1> -->
                                 <table id="mytable" class="table table-bordred table-striped"
                                     style="width: 100% !important;">
                                     <tbody>
                                         <td>
                                             <table>
                                                 <tr>
                                                     <td>IMEI:
                                                         <?php echo isset($imeiInfo['s_imei'])&&$imeiInfo['s_imei']!=""? $imeiInfo['s_imei']: "Not Available"; ?>
                                                     </td>
                                                     <td rowspan="4">
                                                         <?php
                                                        if(isset($imeiInfo['s_imei']) && $imeiInfo['s_imei']!="" && isset($imeiInfo['s_iccid']) && $imeiInfo['s_iccid'] != "" && isset($imeiInfo['s_serial_number']) && $imeiInfo['s_serial_number']!=""){
                                                          $CI =& get_instance();
                                                          $CI->load->library('ciqrcode');
  
                                                          $params['data'] = $imeiInfo['s_imei'] . ';' . $imeiInfo['s_iccid'] . ';' . $imeiInfo['s_serial_number'];
                                                          $params['level'] = 'H';
                                                          $params['size'] = 4;
                                                          $params['savename'] = FCPATH . 'qrcodes/' . $imeiInfo['s_imei'] . '.png';
                                                          $CI->ciqrcode->generate($params);
                                                          echo '<img src="'.base_url() . 'qrcodes/' . $imeiInfo['s_imei'] . '.png" />'; 
                                                        ?><?php }
                                                        else
                                                        {
                                                          echo '<img src="'.base_url() . 'public/images/no_image.png" />'; 
                                                        } ?></td>

                                                 </tr>
                                                 <tr>
                                                     <td>ICCID:
                                                         <?php echo isset($imeiInfo['s_iccid'])&&$imeiInfo['s_iccid']!="" ? $imeiInfo['s_iccid']:" Not Available"; ?>
                                                     </td>
                                                 </tr>
                                                 <tr>
                                                     <td>S/N:
                                                         <?php echo isset($imeiInfo['s_serial_number'])&&$imeiInfo['s_serial_number']!="" ? $imeiInfo['s_serial_number']:"Not Available"; ?>
                                                     </td>
                                                 </tr>
                                                 <tr>
                                                     <td>MADE IN INDIA</td>
                                                 </tr>
                                                 <tr>
                                                     <td>www.psdn.live</td>
                                                 </tr>
                                                 <tr>

                                                 </tr>
                                             </table>
                                         </td>

                                     </tbody>

                                 </table>
                             </form>
                         </div>
                     </div>
                 </div>
             </div>
             <!-- #END# Basic Validation -->

         </div>
     </section>

     <!--- Model Dialog ---->


     <script src="<?php echo base_url() ?>public/js/pages/function/edit_vehicle.js?t=<?php echo time(); ?>"></script>
     <?php $this->load->view('common/admin_login_css_js'); ?>
 </body>

 </html>