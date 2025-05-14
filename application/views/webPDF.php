<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <link href="../public/pdf/bootstrap-theme.css" rel="stylesheet" />
    <link href="../public/pdf/bootstrap-theme.min.css" rel="stylesheet" />
    <link href="../public/pdf/bootstrap.min.css" rel="stylesheet" />

    <script src="../public/pdf/jquery-3.1.1.min.js"></script>
    <script src="../public/pdf/jquery-ui.min.js"></script>
    <script src="../public/pdf/bootstrap.js"></script>
    <script src="../public/pdf/bootstrap.min.js"></script>

    <!--<link href="../public/pdf/bootstrap-theme.css" rel="stylesheet" />
<link href="../public/pdf/bootstrap-theme.min.css" rel="stylesheet" />
<link href="../public/pdf/bootstrap.min.css" rel="stylesheet" />

<script src="../public/pdf/jquery-3.1.1.min.js"></script>
<script src="../public/pdf/jquery-ui.min.js"></script>
<script src="../public/pdf/bootstrap.js"></script>
<script src="../public/pdf/bootstrap.min.js"></script> -->

    <style type="text/css">
    body {
        font: Arial, Helvetica, sans-serif;
        -webkit-print-color-adjust: exact;
    }

    table tr td {
        padding-top: 3px;
        padding-bottom: 3px;
        font-size: 14px;
        color: black;
        padding-left: 10px;
    }

    .c_sub_table {
        margin-left: auto;
        margin-right: auto;
        border-collapse: collapse;
        border: 1px solid #999;
    }

    p {
        font-family: Times New Roman;
        font-weight: bold;
        align-content: center;
        font-size: 12px;
        margin: 0;
        padding: 0;
    }

    h3 {
        font-family: Georgia, &#39;
        Times New Roman&#39;
        ,
        Times,
        serif;
        font-weight: bold;
        padding-top: 0px;
        padding-bottom: 0px;
    }

    .tab_header {
        style="margin-left:auto;
margin-right: auto;
        border-collapse: collapse;
        border: 1px solid #999;
        "

    }

    /* img {
        max-width: 260px;
        max-height: 260px;
    } */
    </style>
    <style media="print">
    h1 {
        color: #000000;
    }

    p {
        color: #000000;
    }

    body {
        background-color: #000;
    }

    .btnHide {
        display: none
    }
    </style>

    <title></title>
</head>

<body>

    <?php

$style1 = 'width: 100px; height: 120px; opacity: 0.2;';
$style2 = 'width: 300px; border: 2px dashed blue;';
?>


    <div id="Certificate" class="container-fluid" style="margin-top:30px;">
        <div class="container" style=" margin:auto; padding:0;  ">
            <div row="class">
                <div class="col-sm-12 col-md-12">
                    <div style="position: absolute; top: 830px; left: 23%; z-index: 1;">
                        <img src="<?php echo AWS_S3_BUCKET_URL?>public/images/PSDN_LOGO_3.png"
                            style="width: 100%; height: 420px; opacity: 0.2;" alt="Photo">
                    </div>
                </div>
            </div>
            <div class="row" style="margin:auto;padding:0;color:black;">
                <!-- Row starts -->

                <!-- Header Starts-->
                <div class="col-sm-12 m-auto">

                    <table class="tab_header" width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td><img style="max-width: 70%; height: auto;"
                                    src="<?php echo AWS_S3_BUCKET_URL?>public/images/ARAIpic.jpg"></td>
                            <td>
                                <center>
                                    <h3>PSDN TECHNOLOGY Pvt.Ltd </h3>
                                    <p
                                        style="font-family:Georgia, &#39;Times New Roman&#39;, Times, serif;font-weight:bold;font-size:14px;margin:0;padding:0">
                                        ONLINE VEHICLE TRACKING <br>DEVICE INSTALLATION CERTIFICATE</p>
                                    <p
                                        style="font-family:Times New Roman;font-weight:bold; align-content:center;font-size:12px;margin:0;padding:0;">
                                        (COMPLIANCE TO AIS 140 STANDARD)<br></p>
                                    <p
                                        style="font-family:Times New Roman;font-weight:bold; align-content:center;font-size:12px;margin:0;padding:0;">
                                        ISO 9001:2015 certified<br></p>
                                </center>
                            </td>
                            <td><img style="max-width: 60%; height: auto; float: right; margin-right:20px"
                                    src="<?php echo AWS_S3_BUCKET_URL?>public/images/psdn_logo.jpg"></td>
                        </tr>

                    </table>
                </div>
                <!-- Header Ends-->

            </div><!-- Row Ends-->

            <!-- Row starts -->

            <div class="row" style="margin-top:10px;">

                <div class="col-sm-12 m-auto">

                    <div class="row">
                        <div class="col-sm-12" style="display: flex;">
                            <div class="col-sm-6 col-md-4">
                                <p> <b style="font-size:16px">To:</b> The Regional Transport Office<br>
                                    <?php echo $userinfo['rto_number'].'-'.$userinfo['rto_place']; ?>
                                </p>
                            </div>
                            <div class="col-sm-6 col-md-8">
                                <p> <b style="font-size:14px">Certificate Date:
                                    </b><?php echo $userinfo['veh_insert_date']?> <br>
                                    <b style="font-size:14px">Certificate Number:
                                    </b>PSDN<?php echo substr($userinfo['s_imei'], -7);?> <br>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!--   <div class="col-sm-12">-->
                    <!--   <p> <b style="font-size:16px">To:</b> The Regional Transport Office<br>-->
                    <!--<?php //echo $userinfo['rto_number'].'-'.$userinfo['rto_place']; ?>-->
                    <!--   </p>-->
                    <!--   </div>-->
                    <div class="row" style="margin-top:10px;margin-right:0px;margin-left:0px;">
                        <div class="col-sm-8 col-md-9">
                            <table align="left" border="1" cellpadding="1" cellspacing="1" class="tab_vehicle"
                                style="margin-left:auto; margin-right:auto; 	border-collapse:collapse; border:1px solid #999;">
                                <tr>
                                    <th colspan="3">
                                        <span style="font-size:14px;">
                                            Vehicle Details
                                        </span>
                                    </th>
                                </tr>
                                <tr>
                                    <td><span style="font-family:Times New Roman;">Registration No : </span>
                                        <span id="vhlnolbl"
                                            style="font-family:Times New Roman;font-weight:bold;"><?php echo isset($userinfo['veh_rc_no'])?$userinfo['veh_rc_no']:""; ?></span>
                                    </td>
                                    <td><span style="font-family:Times New Roman;">Registration Date & Year : </span>
                                        <span id="datelbl"
                                            style="font-family:Times New Roman;"><?php echo isset($userinfo['veh_register_date'])?$userinfo['veh_register_date']:""; ?></span>
                                    </td>

                                    <td rowspan="3"><img src="<?php echo $userinfo['qrcodeimg']; ?>"
                                            class="img-responsive"> </td>
                                </tr>
                                <tr>
                                    <td><span style="font-family:Times New Roman;">Chassis No : </span>
                                        <span id="chissnolbl"
                                            style="font-family:Times New Roman;"><?php echo isset($userinfo['veh_chassis_no'])?$userinfo['veh_chassis_no']:""; ?></span>
                                    </td>
                                    <td><span style="font-family:Times New Roman;">Engine NO : </span>
                                        <span id="englbl"
                                            style="font-family:Times New Roman;"><?php echo isset($userinfo['veh_engine_no'])?$userinfo['veh_engine_no']:""; ?></span>
                                    </td>

                                </tr>
                                <tr>
                                    <td><span style="font-family:Times New Roman;">Vehicle Make: </span>
                                        <span id="vhlmakelbl"
                                            style="font-family:Times New Roman;"><?php echo isset($userinfo['v_make_name'])?$userinfo['v_make_name']:""; ?></span>
                                    </td>
                                    <td><span style="font-family:Times New Roman;">Vehicle Model : </span>
                                        <span id="modellbl"
                                            style="font-family:Times New Roman;"><?php echo isset($userinfo['ve_model_name'])?$userinfo['ve_model_name']:""; ?></span>
                                    </td>

                                </tr>
                            </table>
                        </div>

                        <!-- <div class="col-sm-4 col-md-3" style="position:absolute;right:0px;">
                            <table>
                                <tr>
                                    <td><img style="max-width: 198px; height: auto; float: right; margin-right:20px"
                                            src="<?php echo AWS_S3_BUCKET_URL?>public/images/cdac.png"></td>
                                </tr>
                            </table>
                        </div> -->
                    </div>
                    <div class="col-sm-12">

                        <table width="100%" align="left" border="1" cellpadding="1" cellspacing="1"
                            style="margin-top:15px; margin-left:auto; margin-right:auto; border-collapse:collapse; border:1px solid #999;">
                            <tr>
                                <th colspan="3"><span style="font-family:Times New Roman;">Vehicle Owner Details</span>
                                </th>
                            </tr>
                            <tr>
                                <td style="width: 33.33%;"><span style="font-family:Times New Roman;">Owner Name :
                                    </span>
                                    <span id="ownerlbl"
                                        style="font-family:Times New Roman;"><?php echo isset($userinfo['veh_owner_name'])?$userinfo['veh_owner_name']:""; ?></span>
                                </td>
                                <td style="width: 33.33%;"><span style="font-family:Times New Roman;">Phone : </span>
                                    <span id="phonelbl"
                                        style="font-family:Times New Roman;"><?php echo isset($userinfo['veh_owner_phone'])?$userinfo['veh_owner_phone']:""; ?></span>
                                </td>
                                <td style="width: 33.33%;">
                                    <span style="font-family:Times New Roman;">Email : </span>
                                    <span id="phonelbl"
                                        style="font-family:Times New Roman;"><?php echo isset($userinfo['c_email'])?$userinfo['c_email']:"No"; ?></span>
                                </td>

                            </tr>
                            <tr>
                                <!-- <td colspan="3">Owner Address : -->
                                <?php 
                                $text          = $userinfo['veh_address'];
                                // $text = "Line 1,\nLine 2\nLine 3,\nLine 4";
                                $convertedText = nl2brWithComma($text);

                                function nl2brWithComma($string) {
                                    $lines = explode("\n", $string);
                                    $result = '';
                                
                                    foreach ($lines as $line) {
                                        $trimmedLine = rtrim($line);
                                
                                        if (substr($trimmedLine, -1) === ',') {
                                            $result .= $trimmedLine . " ";
                                        } else {
                                            $result .= $trimmedLine . ", ";
                                        }
                                    }
                                    return rtrim($result, ", ");
                                }
                                
                                $string = $userinfo['veh_address'];
                                $letterCount = strlen(preg_replace('/[^a-zA-Z]/', '', $string));
                                    ?>
                                <td colspan="3"><span style="font-family:Times New Roman;">Owner Address : </span>
                                    <span id="ownerlbl"
                                        style="padding:0px;margin:0px;font-size:12px"><?php echo $convertedText; ?></span>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-sm-12 m-auto">
                        <table class="tab_header" width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td> <img src="<?php echo AWS_S3_BUCKET_URL.$userinfo['veh_photo']; ?>" 
                                         width="220px" height="250px" class="img-responsive"> </td>
                                <td> <img src="<?php echo AWS_S3_BUCKET_URL.$userinfo['veh_speed_governer_photo']; ?>"
                                         width="220px" height="250px"  class="img-responsive"> </td>
                                <td> <img src="<?php echo AWS_S3_BUCKET_URL.$userinfo['vehicle_owner_id_proof']; ?>"
                                         width="220px" height="250px"  class="img-responsive"> </td>
                                <td> <img src="<?php echo AWS_S3_BUCKET_URL.$userinfo['vehicle_owner_photo']; ?>" 
                                         width="220px" height="250px"  class="img-responsive"> </td>
                                <td> <img src="<?php echo AWS_S3_BUCKET_URL.$userinfo['rc_book_photo']; ?>" 
                                        width="100%" width="220px" height="250px"  class="img-responsive"> </td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-sm-12">

                        <table width="100%" align="left" border="1" cellpadding="1" cellspacing="1"
                            style="margin-top:15px; margin-left:auto; margin-right:auto; border-collapse:collapse; border:1px solid #999;">
                            <tr>
                                <th colspan="3"><span style="font-family:Times New Roman;">VLTD Details</span></th>
                            </tr>
                            <tr>
                                <td><span style="font-family:Times New Roman;">VLTD Serial No : </span>
                                    <span id="snolbl"
                                        style="font-family:Times New Roman;font-weight:bold;"><?php echo isset($userinfo['s_serial_number'])?$userinfo['s_serial_number']:""; ?></span>
                                </td>
                                <td>Software Version : 1.0.1 (Device)/ B 1.0.1(System)</td>
                            </tr>


                            <tr colspan="3">
                                <td><span style="font-family:Times New Roman;">VLTD Make : </span>
                                    <span id="speedmklbl"
                                        style="font-family:Times New Roman;"><?php echo isset($userinfo['c_company_name'])?$userinfo['c_company_name']:""; ?></span>
                                </td>
                                <td> Certified By : Automotive Research Association of India (ARAI) </td>
                                <!-- <td><span style="font-family:Times New Roman;">Invoice Date & No. : </span>
                                    <span id="invoicelbl"
                                        style="font-family:Times New Roman;"><?php echo date('d-m-Y',strtotime($userinfo['veh_create_date'])); ?>
                                        /
                                        #<?php echo isset($userinfo['invoice_number'])?$userinfo['invoice_number']:""; ?></span>
                                </td> -->

                            </tr>

                            <tr>
                                <td><span style="font-family:Times New Roman;">IMEI Number : </span>
                                    <span id="coplabel"
                                        style="font-family:Times New Roman;"><?php echo isset($userinfo['s_imei'])?$userinfo['s_imei']:""; ?></span>
                                </td>
                                <td><span style="font-family:Times New Roman;">ICCID : </span>
                                    <span id="expdate"
                                        style="font-family:Times New Roman;"><?php echo isset($userinfo['s_iccid'])?$userinfo['s_iccid']:""; ?></span>
                                </td>
                                <!--<td>Certificate Issued date: -->
                                <!--<span id="invoicelbl" style="font-family:Times New Roman;"><?php echo date('d-m-Y',strtotime($userinfo['veh_create_date'])); ?></span>-->
                                <!--</td>-->
                            </tr>

                            <!--<tr>-->
                            <!--    <td><span style="font-family:Times New Roman;">COP Validity: </span>-->
                            <!--    <span id="expdate" style="font-family:Times New Roman;"><?php echo date('d-m-Y',strtotime($userinfo['validity_from'])); ?></span></td>-->

                            <!--    <td><span style="font-family:Times New Roman;">Valid Upto: </span>-->
                            <!--    <span id="expdate" style="font-family:Times New Roman;"><?php echo date('d-m-Y',strtotime($userinfo['validity_to'])); ?></span></td>-->

                            <!--</tr>-->
                            <tr>
                                <td><span style="font-family:Times New Roman;">TAC NO : </span>
                                    <span id="TAClbl"
                                        style="font-family:Times New Roman;"><?php echo isset($userinfo['veh_tac'])?$userinfo['veh_tac']:""; ?></span>
                                </td>
                                <td> VLDT MODEL : PSDN100 </td>
                                <!--                                 
                                <td><span style="font-family:Times New Roman;">VLDT MODEL : </span>
                                    <span id="expdate"
                                        style="font-family:Times New Roman;"><?php echo date('d-m-Y',strtotime($userinfo['validity_to'])); ?></span>
                                </td> -->
                            </tr>
                            <tr>
                                <td><span style="font-family:Times New Roman;">VLT SIM1 : </span>
                                    <span id="TAClbl"
                                        style="font-family:Times New Roman;"><?php echo isset($userinfo['s_mobile'])?$userinfo['s_mobile']:""; ?></span>
                                </td>

                                <td><span style="font-family:Times New Roman;">VLT SIM2 : </span>
                                    <span id="expdate"
                                        style="font-family:Times New Roman;"><?php echo isset($userinfo['s_mobile_2'])?$userinfo['s_mobile_2']:""; ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td><span style="font-family:Times New Roman;">No.Of panic Button : </span>
                                    <span id="TAClbl"
                                        style="font-family:Times New Roman;"><?php echo isset($userinfo['veh_panic_button'])?$userinfo['veh_panic_button']:""; ?></span>
                                </td>

                                <td><span style="font-family:Times New Roman;">Valid Upto : </span>
                                    <span id="expdate"
                                        style="font-family:Times New Roman;"><?php echo date('d-m-Y',strtotime($userinfo['validity_to'])); ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td><span style="font-family:Times New Roman;">Installation Date : </span>
                                    <span id="TAClbl"
                                        style="font-family:Times New Roman;"><?php echo isset($userinfo['veh_create_date'])?$userinfo['veh_create_date']:""; ?></span>
                                </td>

                                <td><span style="font-family:Times New Roman;">Vehicle Validation : </span>
                                    <span id="expdate" style="font-family:Times New Roman;"><?php if($userinfo['veh_validity_validation'] == '1'){
                                                echo '1 Year/Old';
                                        } if($userinfo['veh_validity_validation'] == '2'){
                                                echo '2 Year/New';
                                        }?></span>
                                </td>
                            </tr>

                        </table>
                    </div>
                    <br>
                    <!-- <div class="col-sm-12">
                    <table width="100%" align="left" border="1" cellpadding="1" cellspacing="1"
                            style="margin-top:15px;margin-bottom:15px; margin-left:auto; margin-right:auto; border-collapse:collapse; border:1px solid #999;">
                        <tr>
                           <td ><span style="font-family:Times New Roman;font-weight:bold;">Fitted By : </span>
                                       <span id="ownerlbl"
                                    style="padding:0px;margin:0px;font-family:Times New Roman;"><?php echo $technician; ?></span>
                            </td>
                            <td ><span style="font-family:Times New Roman;font-weight:bold;">Remarks : </span>
                                       <span id="ownerlbl"
                                    style="padding:0px;margin:0px;font-family:Times New Roman;"><?php echo $userinfo['veh_remarks']; ?></span>
                            </td>
                        </tr>
                    </table>
                    </div> -->

                    <div class="col-sm-12">

                        <table class="table table-bordered c_sub_table" style="margin-top:15px;">
                            <tr>
                                <td><span style="font-family:Times New Roman;font-weight:bold;">Fitted By : </span>
                                    <span id="ownerlbl"
                                        style="margin-top:15px;padding:0px;margin:0px;font-family:Times New Roman;"><?php echo $technician; ?></span>
                                </td>
                                <td><span style="font-family:Times New Roman;font-weight:bold;">Remarks : </span>
                                    <span id="ownerlbl"
                                        style="padding:0px;margin:0px;font-family:Times New Roman;"><?php echo $userinfo['veh_remarks']; ?></span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-sm-12">

                        <table class="table table-bordered c_sub_table">
                            <tr>
                                <td><span style="font-family:Times New Roman;font-weight:bold;">Installed By : </span>
                                    <?php
                                $string = $userinfo['user_info'];
                                $letterCount = strlen(preg_replace('/[^a-zA-Z]/', '', $string));
                                if($letterCount > 20){
                                    ?>
                                    <p style="padding:0px;margin:0px;font-size: 13px;">
                                        <?php echo isset($userinfo['user_info'])?$userinfo['user_info']:""; ?>
                                    </p>
                                    <?php
                                }
                                else{
                                    ?>
                                    <p style="padding:0px;margin:0px;font-size: 14px;">
                                        <?php echo isset($userinfo['user_info'])?$userinfo['user_info']:""; ?>
                                    </p>
                                    <?php
                                }
                                ?>
                                    <!-- <p style="padding:0px;margin:0;font-size: 12px;">
                                        <?php echo isset($userinfo['user_info'])?$userinfo['user_info']:""; ?>
                                    </p>
                                </td> -->
                                <td><span style="font-family:Times New Roman;font-weight:bold;">Certificate Issued By :
                                    </span>
                                    <p style="padding:0px;margin:0;font-size: 14px;">
                                        <?php echo isset($userinfo['certificateIssuedBy'])?$userinfo['certificateIssuedBy']:""; ?>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-sm-12">

                        <table class="table table-bordered c_sub_table">
                            <tr>
                                <td width="70%">
                                    <p
                                        style="font-family:Times New Roman;text-align:justify;padding:2px;font-size:14px;">
                                        This is to certify that following vehicle has been fitted and activated with
                                        approved PSDN100 vehicle location tracking device as per AIS 140 standard which
                                        is activated for live tracking and more features unless the device tampered or
                                        the seal is broken by unauthorized techicians or individual.</p>
                                </td>
                                <td>
                                    <span style="font-family:Times New Roman;font-weight:bold;">RTA/RFO/STA Signature :
                                    </span>
                                </td>
                            </tr>

                        </table>
                    </div>

                    <div class="col-sm-12">

                        <table class="table table-bordered c_sub_table">
                            <tr>
                                <td width="50%">
                                    <span style="font-family:Times New Roman;font-weight:bold;"> Customer
                                        Signature</span>
                                </td>
                                <td width="50%">
                                    <span style="font-family:Times New Roman;font-weight:bold;"> Dealer Signature</span>
                                    <br>
                                    <span style="font-family:Times New Roman;font-weight:bold; "> Dealer Seal</span>
                                    <br>
                                    <br>
                                    <!--<br>-->
                                    <!--<br>-->
                                    <!--<br>-->
                                    <span style="font-family:Times New Roman;font-weight:bold; ">Dealer Date:</span>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="page-break"></div>

                    <div class="row" style="margin:auto;padding:0;color:black;">
                        <!-- Row starts -->

                        <!-- Header Starts-->
                        <div class="col-sm-12 m-auto">

                            <table class="tab_header" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td><img style="max-width: 70%; height: auto;"
                                            src="<?php echo AWS_S3_BUCKET_URL?>public/images/psdn_logo.jpg"></td>
                                    <td>
                                        <center>
                                            <h3>PSDN TECHNOLOGY Pvt.Ltd </h3>
                                            <p
                                                style="font-family:Georgia, &#39;Times New Roman&#39;, Times, serif;font-weight:bold;font-size:14px;margin:0;padding:0">
                                                An ARAI & ISO certified company</p>
                                            <p
                                                style="font-family:Times New Roman;font-weight:bold; align-content:center;font-size:12px;margin:0;padding:0;">
                                                39,TARACHAND DUTTA, 2nd FLOOR<br></p>
                                            <p
                                                style="font-family:Times New Roman;font-weight:bold; align-content:center;font-size:12px;margin:0;padding:0;">
                                                KOLKATA-700073 WEST BENGAL,INDIA<br></p>
                                        </center>
                                    </td>
                                    <td>
                                        <!-- <p
                                            style="font-family: Times New Roman; font-weight: bold; font-size: 20px; margin: 0; padding: 0; background-color: black; color: white; text-align: center; margin-right: 40px;">
                                            WARRANTY CARD<br>
                                        </p> -->
                                        <p
                                            style="font-family: Times New Roman; font-weight: bold; font-size: 20px; text-align: center; margin-right: 40px;">
                                            WARRANTY CARD<br>
                                        </p>
                                    </td>

                                </tr>

                            </table>
                            <br>
                        </div>

                        <div class="row">
                            <div class="col-sm-12" style="display: flex;">
                                <div class="col-sm-6 col-md-12 justify-content-end text-right">
                                    <b style="font-size:14px">Warranty card No:
                                    </b>PSDN<?php echo substr($userinfo['s_imei'], -7);?> <br>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12" style="margin-bottom:20px;">

                            <table width="100%" align="left" border="1" cellpadding="1" cellspacing="1"
                                style="margin-top:15px; margin-left:auto; margin-right:auto; border-collapse:collapse; border:1px solid #999;">
                                <!-- <tr>
        <th colspan="3"><span style="font-family:Times New Roman;">VLTD Details</span></th>
    </tr> -->
                                <tr>
                                    <td><span style="font-family:Times New Roman;">Customer Name : </span>
                                        <span id="cusName"
                                            style="font-family:Times New Roman;"><?php echo isset($userinfo['veh_owner_name'])?$userinfo['veh_owner_name']:""; ?></span>
                                    </td>

                                    <td><span style='font-family:Times New Roman;'>Address : </span>
                                        <span id='cusAdd'
                                            style='font-family:Times New Roman;'><?php echo $convertedText; ?></span>
                                    </td>

                                </tr>


                                <tr>
                                    <td><span style="font-family:Times New Roman;">Mobile : </span>
                                        <span id="cusMob"
                                            style="font-family:Times New Roman;"><?php echo isset($userinfo['veh_owner_phone'])?$userinfo['veh_owner_phone']:""; ?></span>
                                    </td>
                                    <td><span style='font-family:Times New Roman'>Email : </span>
                                        <span id="cusEmail"
                                            style='font-family:Times New Roman;'><?php echo isset($userinfo['c_email'])?$userinfo['c_email']:"";?></span>

                                    </td>

                                </tr>

                                <tr>
                                    <td><span style="font-family:Times New Roman;">RC No : </span>
                                        <span id="coplabel"
                                            style="font-family:Times New Roman;"><?php echo isset($userinfo['veh_rc_no'])?$userinfo['veh_rc_no']:""; ?></span>
                                    </td>
                                    <td><span style="font-family:Times New Roman;">Dealer Name : </span>
                                        <span id="expdate"
                                            style="font-family:Times New Roman;"><?php echo isset($dealerName)?$dealerName:""; ?></span>
                                    </td>
                                </tr>


                            </table>
                        </div>
                        <!-- <div class="row" style="margin-top:10px;margin-right:0px;margin-left:0px;">
                            <div class="col-sm-8 col-md-9">
                                <div style="display: flex;">
                                    <table align="left" border="1" cellpadding="1" cellspacing="1" class="tab_vehicle"
                                        style="margin-left: auto; margin-right: auto; border-collapse: collapse; border: 1px solid #999; width: 600px; height: 100px;">
                                        <tr>
                                            <td><span style="font-family: Times New Roman;">Date of purchase : </span>
                                                <span id="vhlnolbl"
                                                    style="font-family: Times New Roman; font-weight: bold;"><?php echo isset($userinfo['veh_rc_no']) ? $userinfo['veh_rc_no'] : ""; ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span style="font-family: Times New Roman;">Model No : </span>
                                                <span id="chissnolbl"
                                                    style="font-family: Times New Roman;"><?php echo isset($userinfo['veh_chassis_no']) ? $userinfo['veh_chassis_no'] : ""; ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span style="font-family: Times New Roman;">Serial No : </span>
                                                <span id="vhlmakelbl"
                                                    style="font-family: Times New Roman;"><?php echo isset($userinfo['v_make_name']) ? $userinfo['v_make_name'] : ""; ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span style="font-family: Times New Roman;">IMEI No : </span>
                                                <span id="imeiNo"
                                                    style="font-family: Times New Roman;"><?php echo isset($userinfo['imei_no']) ? $userinfo['imei_no'] : ""; ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span style="font-family: Times New Roman;">ICCID No : </span>
                                                <span id="iccidNo"
                                                    style="font-family: Times New Roman;"><?php echo isset($userinfo['iccid_no']) ? $userinfo['iccid_no'] : ""; ?></span>
                                            </td>
                                        </tr>
                                    </table>
                                    <div style="width: 200px; border: 1px solid #999; margin-left: 10px; padding: 10px;">
                                    </div>
                                </div>
                            </div>
                        </div> -->

                        <div class="row" style="margin-top: 10px; margin-right: 0px; margin-left: 0px;">
                            <div class="col-sm-8 col-md-9">
                            <div style="display: flex; justify-content: space-between;">
                                    <div style="width: 400px;">
                                       <p><span style="font-family: Times New Roman;">Invoice No : </span>
                                            <span id="vhlnolbl"
                                                style="font-family: Times New Roman;"><?php echo isset($userinfo['veh_invoice_no']) ? $userinfo['veh_invoice_no'] : ""; ?></span>
                                        </p>
                                        <p><span style="font-family: Times New Roman;">Date of purchase : </span>
                                            <span id="vhlnolbl"
                                                style="font-family: Times New Roman;"><?php echo isset($userinfo['veh_create_date']) ? $userinfo['veh_create_date'] : ""; ?></span>
                                        </p>
                                        <p><span style="font-family: Times New Roman;">Model No : PSDN100 </span>
                                        </p>
                                        <p><span style="font-family: Times New Roman;">Serial No : </span>
                                            <span id="vhlnolbl"
                                                style="font-family: Times New Roman;"><?php echo isset($userinfo['s_serial_number']) ? $userinfo['s_serial_number'] : ""; ?></span>
                                        </p>
                                        <p><span style="font-family: Times New Roman;">IMEI No : </span>
                                            <span id="vhlnolbl"
                                                style="font-family: Times New Roman;"><?php echo isset($userinfo['s_imei']) ? $userinfo['s_imei'] : ""; ?></span>
                                        </p>
                                        <p><span style="font-family: Times New Roman;">ICCID No : </span>
                                            <span id="vhlnolbl"
                                                style="font-family: Times New Roman;"><?php echo isset($userinfo['s_iccid']) ? $userinfo['s_iccid'] : ""; ?></span>
                                        </p>
                                        <!-- Add more value sections as needed -->
                                    </div>
                                    <!-- <div
                                        style="width: 300px; height: 200px;border: 1px solid #999; padding: 10px;margin: left 20px;">
                                        <div style="text-align: center; position: absolute; top: 0; left: 0; right: 0;">
                                            <p style="font-family: Times New Roman; font-weight: bold;">Dealer Stamp
                                                Text</p>
                                        </div>
                                    </div> -->
                                    <div id="signatureBox" style="width: 450px; height: 200px; border: 1px solid #999; padding: 10px; margin-left: 300px; position: relative;">
                                        <div style="text-align: center;">
                                            <p style="font-family: Times New Roman; font-weight: bold;">Dealer's Stamp</p>
                                        </div>
                                        <div style="position: absolute; top: -23px; left: 0; right: 0; text-align: center;">
                                            <p style="font-family: Times New Roman; font-weight: bold;">CUSTOMER'S COPY</p>
                                        </div>
                                    </div>



                                </div>
                            </div>
                        </div>


                        <!-- Header Ends-->

                    </div><!-- Row Ends-->

                    <div class="page-break"></div>

                    <div id="SecondPageContent" class="container-fluid" style="margin-top:30px;">
                        <h2>TERMS & CONDITIONS WITH LIMITED WARRANTY</h2>
                        <p>➤ Warranty shall be extended only against manufacturing defects and/ or failure of its
                            mechanical and / or electrical components for a period of 1 year from the date of purchase
                            wherein the warranty card is dully filled and stamped.</p>
                        <p>➤ PSDN also disclaims any claim and responsibility on the low or no connectivity by the
                            telecom service provider. User understands and agrees that low or no connectivity might
                            result in stoppage of VTS service as well collection of any data.</p>
                        <p>➤ The warranty does not extend to any product that has been damages and / or rendered
                            defective.</p>
                        <p>➤ As a result of accidents and / or misuse and / or abuse;</p>
                        <p>➤ By operation outside the usage parameters stated in the product’s user’s manual;</p>
                        <p>➤ By the use of parts not manufactured and / or sold by the authorized manufactures;</p>
                        <p>➤ As a result of service other than PSDN</p>
                        <p>➤ This warranty card shall be rendered invalid if found to be altered and / or defaced</p>
                        <p>➤ This warranty shall be subject to the following limitations and / or exclusions and shall
                            not apply to:</p>
                        <p>➤ Malfunctions and / or damages and / or defects as a result of exposure to fire and water
                            and / or using wrong electric supply </p>
                        <p>➤ Dealer’s display and / or showroom sets</p>
                        <p>➤ Dismantling of the device for repairs and / or after repairs by unauthorized person and /
                            or entity.</p>
                        <p>➤ Parts subjected to normal and inevitable wear and tear of fuses, seals, diaphragm knobs,
                            rubber parts etc.</p>
                        <p>➤ Demonstration on use of appliance and / or product.</p>
                        <p>➤ This warranty extends only to the original purchaser and is non- transferable.</p>
                        <p>➤ Non- conformance to the warranty to company’s satisfaction, based on examination.</p>
                        <p>➤ This warranty is applicable within India only.</p>
                        <p>➤ EXCEPT AS EXPRESSLY SET FORTH IN THIS WARRANTY, PSDN TECHNOLOGY PRIVATE LIMITED MAKES NO
                            OTHER WARRANTIES, EXPRESSED AND / OR IMPLIED, INCLUDING ANY IMPLIED WARRANTIES AND / OR
                            MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE. PSDN EXPRESSLY DISCLAIMS ALL
                            WARRANTIES NOT STATED IN THIS LIMITED WARRANTY. ANY IMPLIED WARRANTIES THAT MAY BE IMPOSED
                            BY LAW ARE LIMITED TO THE TERMS OF THIS EXPRESS LIMITED WARRANTY.</p>
                        <p>➤ Any warranty provided by PSDN, will not be entertained without a proof of purchase.</p>
                        <p>➤ PSDN fully disclaims any responsibility for any damage, accident, theft of the vehicle in
                            any Manner. PSDN's role is limited to sale and service of the VTS device. No claims of these
                            natures will be accepted by PSDN.</p>
                        <p>➤ Device warranty period of 2year is applicable as per our standard warranty terms. Battery
                            will come with 6 months warranty. Tampering with device is not covered under standard
                            warranty terms & engineer’s visit & damaged parts shall be charged extra.</p>
                        <br>


                        <!-- Add more content here -->
                    </div>

                    <!-- Include the CSS for page breaks and other styles -->
                    <style type="text/css">
                    .page-break {
                        page-break-before: always;
                    }

                    /* Add any additional styling for the second page content here */
                    </style>


                    <div class="col-sm-12">
                        <p> For repairs and breakdown please contact respective district dealers. if, not
                            attend within 2 working days please inform us in writing Email: support@psdn.in<br>Phone :
                            +91-90736-51925, +91-90736-51926</p>
                    </div>



                </div>
            </div>
        </div>


        <div style="margin:auto" id="btnHide" class="btnHide">
            <table style="margin-bottom:10px;margin-left:auto;margin-right:auto;margin-top:10px;padding:10px;">
                <tr>
                    <!-- <td style="padding:10px;">
                        <button name="Print" media="print and (color:#FFF)" class="btn btn-info"
                            onclick="PrintDiv()">PRINT Dealer Copy</button>
                    </td> -->
                    <td style="padding:10px;">
                        <button name="Print" class="btn btn-info" onclick="PrintDiv()">PRINT</button>
                    </td>
                    <!-- <td style="padding:10px;">
                        <button name="Print" class="btn btn-info" onclick="PrintDiv()">PRINT Customer Copy</button>
                    </td> -->


            </table>
        </div>




    </div>
</body>
<script type="text/javascript">
function PrintDiv2() {
    var divContents = document.getElementById("Certificate").innerHTML;
    var printWindow = window.open('', '', 'height=1000,width=800');
    printWindow.document.write(
        '<html><head> <style>table tr td { font-size:14px;color:black;padding-left:10px; }</style><title>Customer Copy</title><link href="../public/pdf/bootstrap.min.css" rel="stylesheet" />'
    );
    printWindow.document.write('</head><body style="padding:0;margin-top:20;">');
    printWindow.document.write(divContents);
    printWindow.document.write('</body></html>');
    printWindow.document.close();

    function show() {
        if (printWindow.document.readyState === "complete") {
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        } else {
            setTimeout(show, 3000);
        }
    };
    show();
};
</script>
<script type="text/javascript">
function PrintDiv1() {
    var divContents = document.getElementById("Certificate").innerHTML;
    var printWindow = window.open('', 'print', 'height=1000,width=800');
    printWindow.document.write(
        '<html><head> <style>tr td { font-size:14px;color:black;padding-left:10px;  }</style><title>Department Copy</title><link href="../Content/bootstrap.min.css" rel="stylesheet" />'
    );
    printWindow.document.write('</head><body style="padding:0;margin-top:20;>');
    printWindow.document.write(divContents);
    printWindow.document.write('</body></html>');
    printWindow.document.close();

    function show() {
        if (printWindow.document.readyState === "complete") {
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        } else {
            setTimeout(show, 3000);
        }
    };
    show();
};
</script>
<script type="text/javascript">
function PrintDiv() {
    // var content =  document;
    document.getElementById("btnHide").style.display = "none";
    console.log(document.getElementById("Certificate").innerHTML)
    var divContents = document.getElementById("Certificate").innerHTML + "<script>" +
        "window.onload = function() {" +

        " window.print();" +
        "};" +
        "<" + "/script>";

    var printWindow = window.open('', '', 'height=1000,width=800');
    printWindow.document.write(
        '<html><head> <title>Certificate</title> <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">'
    );
    printWindow.document.write('</head><body style="padding:0;margin-top:20;">');

    // Save the PDF


    printWindow.document.write(divContents);
    printWindow.document.write('</body></html>');
    printWindow.document.close();

    var images = certificate.getElementsByTagName('veh_photo');
    var y = 20; // Set the initial vertical position of the content

    for (var i = 0; i < images.length; i++) {
        var image = images[i];
        var imageUrl = image.src;

        // Add the image to the PDF
        pdf.addImage(imageUrl, 'JPEG', 20, y, imageWidth, imageHeight);

        // Increment the vertical position for the next content
        y += imageHeight + 10; // Adjust the spacing between images

        // Add any additional vehicle details, owner details, etc. here using pdf.text() or pdf.cell()
    }

    document.getElementById("btnHide").style.display = "block";
    // pdf.save('vehicle_details.pdf');
    // function show() {
    //     if (printWindow.document.readyState === "complete") {
    //         printWindow.focus();
    //         printWindow.print();
    //         printWindow.close();
    //     } else {
    //         setTimeout(show, 3000);
    //     }
    // };
    // show();
};







/**   function PrintDiv() {
       var divContents = document.getElementById("Certificate").innerHTML;
       var printWindow = window.open('', '', 'height=1000,width=800');
       printWindow.document.write('<html><head> <style>tr td { font-size:14px;color:black;padding-left:10px;  }</style><title>Dealer Copy</title> <link href="../public/pdf/bootstrap-theme.css" rel="stylesheet" /> <link href="../public/pdf/bootstrap-theme.min.css" rel="stylesheet" /> <link href="../public/pdf/bootstrap.min.css" rel="stylesheet" /> <link href="../Content/bootstrap.min.css" rel="stylesheet" />');
       printWindow.document.write('</head><body style="padding:0;margin-top:20;">');
       printWindow.document.write(divContents);
       printWindow.document.write('</body></html>');
       printWindow.document.close();
       function show() {
           if (printWindow.document.readyState === "complete") {
               printWindow.focus();
               printWindow.print();
               printWindow.close();
           } else {
               setTimeout(show, 3000);
           }
       };
       show();
   }; **/
</script>

</html>