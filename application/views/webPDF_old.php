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

    
body{ font:Arial, Helvetica, sans-serif;
	-webkit-print-color-adjust: exact;
}
table tr td {            
            padding-top: 3px;
            padding-bottom: 3px;
            font-size:14px;
            color:black;
            padding-left:10px;
        }
.c_sub_table{ 
	margin-left:auto;
	margin-right:auto;
	border-collapse:collapse;
	border:1px solid #999;
	}	
p{
	font-family:Times New Roman;
	font-weight:bold; 
	align-content:center;
	font-size:12px;
	margin:0;
	padding:0;
}	

h3{	font-family:Georgia, &#39;Times New Roman&#39;, Times, serif;
	font-weight:bold; 
	padding-top:0px;
	padding-bottom:0px;	
}	
	
.tab_header{
	style="margin-left:auto;
	margin-right:auto;
	border-collapse:collapse;
	border:1px solid #999;"
	}



@media print { .btnHide { display:none} }	
</style>
<style media="print">
h1 {color:#000000;}
p {color:#000000;}
body {background-color:#000;}
.btnHide { display:none}
</style>
<title></title>
</head>
<body>

<?php
/*print_r('<pre>');
print_r($userinfo);
print_r('</pre>');*/
?>
    
    
<div id="Certificate" class="container-fluid" style="margin-top:30px;">
<div class="container" style=" margin:auto; padding:0;  ">

<div  class="row" style="margin:auto;padding:0;color:black;"> <!-- Row starts -->
       
<!-- Header Starts-->
<div class="col-sm-12 m-auto" >

<table class="tab_header" width="100%" cellpadding="0" cellspacing="0" >
 <tr>
 	<td><img style="max-width: 70%; height: auto;" src="<?php echo base_url();?>public/images/ARAIpic.jpg" ></td>
    <td>
    <center>
<h3>PSDN TECHNOLOGY Pvt.Ltd </h3>
                     <p style="font-family:Georgia, &#39;Times New Roman&#39;, Times, serif;font-weight:bold;font-size:14px;margin:0;padding:0"> ONLINE VEHICLE TRACKING <br>DEVICE INSTALLATION CERTIFICATE</p>
                     <p style="font-family:Times New Roman;font-weight:bold; align-content:center;font-size:12px;margin:0;padding:0;">(COMPLIANCE TO AIS 140 STANDARD)<br></p>
                     <p style="font-family:Times New Roman;font-weight:bold; align-content:center;font-size:12px;margin:0;padding:0;">ISO 9001:2015 certified<br></p>
</center>
    </td>
    <td><img style="max-width: 60%; height: auto; float: right; margin-right:20px" src="<?php echo base_url();?>public/images/psdn_logo.jpg" ></td>
 </tr>   
  
</table>    
</div>     
<!-- Header Ends-->

</div><!-- Row Ends-->

<!-- Row starts -->
    
<div class="row" style="margin-top:10px;">
     
     <div class="col-sm-12 m-auto" >

     <div class="col-sm-12">
     <p> <b style="font-size:16px">To:</b> The Regional Transport Office<br>
	 <?php echo $userinfo['rto_number'].'-'.$userinfo['rto_place']; ?>
     </p>
     </div>

     <div class="col-sm-8">
     <table align="left" border="1" cellpadding="1" cellspacing="1" class="tab_vehicle" style="margin-left:auto; margin-right:auto; 	border-collapse:collapse; border:1px solid #999;">
            <tr>
                <th colspan="3">
                <span style="font-size:14px;">
                Vehicle Details
                </span>
                </th>
            </tr>
            <tr>
                <td><span style="font-family:Times New Roman;">Registration No : </span> 
                <span id="vhlnolbl" style="font-family:Times New Roman;font-weight:bold;"><?php echo isset($userinfo['veh_rc_no'])?$userinfo['veh_rc_no']:""; ?></span></td>
                <td><span style="font-family:Times New Roman;">Registration Date & Year : </span>
                <span id="datelbl" style="font-family:Times New Roman;"><?php echo date('d-m-Y',strtotime($userinfo['veh_create_date'])); ?></span></td>

                <td rowspan="3"><img  src="<?php echo $userinfo['qrcodeimg']; ?>" class="img-responsive"> </td>
            </tr>
            <tr>
                <td><span style="font-family:Times New Roman;">Chassis No : </span> 
                <span id="chissnolbl" style="font-family:Times New Roman;"><?php echo isset($userinfo['veh_chassis_no'])?$userinfo['veh_chassis_no']:""; ?></span></td>
                <td><span style="font-family:Times New Roman;">Engine NO : </span> 
                <span id="englbl" style="font-family:Times New Roman;"><?php echo isset($userinfo['veh_engine_no'])?$userinfo['veh_engine_no']:""; ?></span></td>
                
            </tr>
            <tr>
                <td><span style="font-family:Times New Roman;">Vehicle Make: </span> 
                <span id="vhlmakelbl" style="font-family:Times New Roman;"><?php echo isset($userinfo['v_make_name'])?$userinfo['v_make_name']:""; ?></span></td>
                <td><span style="font-family:Times New Roman;">Vehicle Model : </span>  
                <span id="modellbl" style="font-family:Times New Roman;"><?php echo isset($userinfo['ve_model_name'])?$userinfo['ve_model_name']:""; ?></span></td>
                
            </tr>
        </table>
        </div>

            <div class="col-sm-4">
                                   
            </div>

            <div class="col-sm-12">

            <table width="100%" align="left" border="1" cellpadding="1" cellspacing="1" style="margin-top:15px; margin-left:auto; margin-right:auto; border-collapse:collapse; border:1px solid #999;">
            <tr>
                <th colspan="2"><span style="font-family:Times New Roman;">Vehicle Owner Details</span></th>
             </tr>
             <tr>
                  <td><span style="font-family:Times New Roman;">Owner Name : </span>  
                    <span id="ownerlbl" style="font-family:Times New Roman;"><?php echo isset($userinfo['veh_owner_name'])?$userinfo['veh_owner_name']:""; ?></span></td>
                    <td><span style="font-family:Times New Roman;">Phone : </span>  
                    <span id="phonelbl" style="font-family:Times New Roman;"><?php echo isset($userinfo['veh_owner_phone'])?$userinfo['veh_owner_phone']:""; ?></span></td>

                </tr>
                <tr>
                    <td>Owner Address : 
                        <p style="padding:0px;margin:0">
                        <?php echo nl2br($userinfo['veh_address']); ?>                        
                        </p></td>
                    <td>
                    <span style="font-family:Times New Roman;">Email : </span>  
                    <span id="phonelbl" style="font-family:Times New Roman;"><?php echo isset($userinfo['c_email'])?$userinfo['c_email']:"No"; ?></span>
                    </td>
                </tr>


                </table>
            </div>
			
            <div class="col-sm-12">

                <table   width="100%" align="left" border="1" cellpadding="1" cellspacing="1" style="margin-top:15px; margin-left:auto; margin-right:auto; border-collapse:collapse; border:1px solid #999;" >
                <tr>
                    <th colspan="2"><span style="font-family:Times New Roman;">VLTD Details</span></th>
                </tr>
                <tr>
                    <td><span style="font-family:Times New Roman;">VLTD Serial No : </span>
                    <span id="snolbl" style="font-family:Times New Roman;font-weight:bold;"><?php echo isset($userinfo['s_serial_number'])?$userinfo['s_serial_number']:""; ?></span></td>
                    <td>Software Version: 1.0.1 (Device)/ B 1.0.1(System)</td>
                </tr>

                <tr>
                    <td><span style="font-family:Times New Roman;">VLTD Make : </span>
                    <span id="speedmklbl" style="font-family:Times New Roman;"><?php echo isset($userinfo['c_company_name'])?$userinfo['c_company_name']:""; ?></span></td>
                    <td><span style="font-family:Times New Roman;">Invoice Date & No. : </span>
                    <span id="invoicelbl" style="font-family:Times New Roman;"><?php echo date('d-m-Y',strtotime($userinfo['veh_create_date'])); ?> / #<?php echo isset($userinfo['invoice_number'])?$userinfo['invoice_number']:""; ?></span></td>

                </tr>
                 
                <tr>
                    <td><span style="font-family:Times New Roman;">TAC NO : </span>
                    <span id="TAClbl" style="font-family:Times New Roman;"><?php echo isset($userinfo['veh_tac'])?$userinfo['veh_tac']:""; ?></span></td>
                    <td>Certificate Issued date: 
                    <span id="invoicelbl" style="font-family:Times New Roman;"><?php echo date('d-m-Y',strtotime($userinfo['veh_create_date'])); ?></span>
                    </td>
                </tr>

                <tr>
                    <td><span style="font-family:Times New Roman;">COP Validity: </span>
                    <span id="expdate" style="font-family:Times New Roman;"><?php echo date('d-m-Y',strtotime($userinfo['validity_from'])); ?></span></td>

                    <td><span style="font-family:Times New Roman;">Valid Upto: </span>
                    <span id="expdate" style="font-family:Times New Roman;"><?php echo date('d-m-Y',strtotime($userinfo['validity_to'])); ?></span></td>
                    
                </tr>
                
                
                
                 <tr>
                    
                    <td><span style="font-family:Times New Roman;">IMEI Number : </span>
                    <span id="coplabel" style="font-family:Times New Roman;"><?php echo isset($userinfo['s_imei'])?$userinfo['s_imei']:""; ?></span></td>
                     <td> Certified By: Automotive Research Association of India (ARAI) </td>
                     <!-- <td><span style="font-family:Times New Roman;">Set Speed : </span>
                    <span id="speedlbl" style="font-family:Times New Roman;"><?php echo isset($userinfo['veh_speed'])?$userinfo['veh_speed']:""; ?></span></td> -->
                </tr>

                  </table>
            </div>

            <div class="col-sm-12">

                <table class="table table-bordered c_sub_table" >
                <!-- <tr>
                    
                    
                     <td><span style="font-family:Times New Roman;">COP Validity : </span>
                    <span id="coplabel" style="font-family:Times New Roman;"><?php echo date('d-m-Y',strtotime($userinfo['veh_cop_validity'])); ?></span></td>
                   
                </tr> -->
                <tr>
                    <td><span style="font-family:Times New Roman;font-weight:bold;">Installed By : </span>
                    <p style="padding:0px;margin:0">
                          <?php echo isset($userinfo['user_info'])?$userinfo['user_info']:""; ?>                                    
                    </p>
                    </td>
                    <td><span style="font-family:Times New Roman;font-weight:bold;">Certificate Issued By : </span>
                    <p style="padding:0px;margin:0">
                          <?php echo isset($userinfo['user_info'])?$userinfo['user_info']:""; ?>                                    
                    </p>
                    </td>
                </tr>
                </table>
            </div>

            <div class="col-sm-12">

                <table class="table table-bordered c_sub_table" >
                <tr>
                    <td width="70%">
                        <p style="font-family:Times New Roman;text-align:justify;padding:2px;font-size:14px;"><?php /*This is to certify that following vehicle has been fitted with approved <b> <?php echo isset($userinfo['c_company_name'])?$userinfo['c_company_name']:""; ?></b> Electronic Speed Governor, which is set to a maximum pre set speed of <b><?php echo isset($userinfo['veh_speed'])?$userinfo['veh_speed']:""; ?> </b> Kmph(+/- 2%) and shall not exceed this speed in any circumstances, unless the device is tampered or the seal is broken by unauthorised technicians or individual. */ ?>
						This is to certify that following vehicle has been fitted and activated with approved PSDN100 vehicle location tracking device as per AIS 140 standard which is activated for live tracking and more features unless the device tampered or the seal is broken by unauthorized techicians or individual.</p>
                    </td>
                    <td>
                        <span style="font-family:Times New Roman;font-weight:bold;">RTA/RFO/STA Signature : </span>
                    </td>
                </tr>

                </table>
            </div>

            <div class="col-sm-12">

                <table class="table table-bordered c_sub_table" >
                <tr>
                    <td width="70%">
                        <p style="font-family:Times New Roman;text-align:justify;padding:2px;font-size:14px;"><?php /*  This is to certify that following vehicle has been fitted with approved <b> <?php echo isset($userinfo['c_company_name'])?$userinfo['c_company_name']:""; ?></b> Electronic Speed Governor, which is set to a maximum pre set speed of <b><?php echo isset($userinfo['veh_speed'])?$userinfo['veh_speed']:""; ?> </b> Kmph(+/- 2%) and shall not exceed this speed in any circumstances, unless the device is tampered or the seal is broken by unauthorised technicians or individual. */?>This to acknowledge and confirm that we have got our vehicle fittedwith tha above vehicle location tracking unit. we have checked the performance the vehicle of the fittment and we confirm VLTD is concerning as per norms lied outin AIS 140 & AIS 004 (part 3), we are satisfied with the performance of the device in all respects we undertake not to raise any disput and any legal claims against m/s <b> <?php echo isset($userinfo['c_company_name'])?$userinfo['c_company_name']:""; ?>, in the event that the above maintained function are founded broken/torn/tampered/after expiry and warranty date issue.</p>
                    </td>
                    <td>
                        <span style="font-family:Times New Roman;font-weight:bold;"> </span>
                    </td>
                </tr>
 
                </table>
            </div>

             <div class="col-sm-12">

                <table class="table table-bordered c_sub_table" >
                <tr>
                    <td width="50%">
                        <span style="font-family:Times New Roman;font-weight:bold;"> Customer Signature</span>
                    </td>
                    <td width="50%">
                        <span style="font-family:Times New Roman;font-weight:bold;"> Dealer Signature</span>
						<br>
						<br>
						<span style="font-family:Times New Roman;font-weight:bold; "> Dealer Seal</span>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
						<span style="font-family:Times New Roman;font-weight:bold; ">Dealer Date:</span>
                    </td>
                </tr>
                </table>
            </div>
			<div class="col-sm-12"><p> <?php /* For Repairs and breakdowns, Please contact the respective Distric Dealers. If not attend within 48 Hours, Kindly inform us in writing email support@psdn.in.<br>
            Phone:090802-01070. */ ?> For repairs and breakdown please contact respective district dealers. if, not attend within 2 working days please inform us in writing Email: support@psdn.in<br>Phone : 086809-96666</p> </div>

            <!-- <div class="col-sm-12">

            <table border="1" style="margin-right:10px;border-color:lightgrey;">
                <tr>
                    <td style="text-align:center"><b>Certificate QR Code</b></td>
                    <td style="text-align:center"><b>Vechicle Photo</b></td>
                    <td style="text-align:center"><b>Speed Governor Photo</b></td>
                </tr>
                
                <tr style="padding-top:5px;padding-bottom:0px;">

                   <td style="width:250px;height:200px;padding:0;margin:0;"></td>
                   <img src="<?php echo base_url().$userinfo['veh_photo']; ?>" style="height:200px;width:230px;"/></div>
                    <td style="align-content:center;padding:10px;width:230px;height:200px;"><div style="margin:auto"><img src="<?php echo base_url().$userinfo['veh_speed_governer_photo']; ?>" style="height:200px;width:250px;" /></div></td>
                    
                </tr>
                <tr style="padding:2px;">

                    <td colspan="3" style="padding:0;">
                        <div style="float:right">
                           <p style="font-family:Times New Roman;text-align:justify;padding:2px;font-size:14px;margin-right:10px;font-weight:bold;">Authorised</p>
                        </div>
                    </td>
                </tr>
                <tr style="padding:2px;">
                    <td colspan="2" >
                        
                    </td>
                    
                    <td>
                        <div style="float:right">
                           <img src="../public/pdf/blank.jpg" style="height:80px;width:200px;" />
                        </div>
                    </td>
                </tr>
               <tr style="padding:2px;">
                                       <td colspan="2" style="padding:0;">
                                          <p style="font-family:Times New Roman;text-align:justify;padding:2px;font-size:14px;"> We have sealed this device at critical points and the certificate is being issued only after testing.</p>
                                       </td>
                                       <td style="text-align:right;padding:0;    border-top-style: hidden;">
                                          <p style="font-family:Times New Roman;text-align:justify;padding:2px;font-size:14px;font-weight:bold;float:right;margin-right:10px;">Dealer Signature</p>
                                       </td>
                </tr>
            </table>
                        </td>
                    </tr>
                        </table>
         <p style="font-family:Times New Roman;text-align:justify;padding:2px;margin:auto;font-size:12px;">For repairs and breakdowns please contact the respective District Dealers, if not attended within 2 working days please inform us in writing E-Mail: universalteleservices@gmail.com Toll Free No. 1800 121 5800</p>
            </div>-->
            
            
        	</div>
        </div>
   </div> 
      
    
    <div style="margin:auto" id = "btnHide" class="btnHide">
    <table style="margin-bottom:10px;margin-left:auto;margin-right:auto;margin-top:10px;padding:10px;">
            <tr>
                    <td style="padding:10px;">
                        <button name="Print" media="print and (color:#FFF)" class="btn btn-info" onclick="PrintDiv()">PRINT Dealer Copy</button>
                    </td>
                <td style="padding:10px;">
                        <button name="Print" class="btn btn-info" onclick="PrintDiv()">PRINT Department Copy</button>
                    </td>
                <td style="padding:10px;">
                        <button name="Print" class="btn btn-info" onclick="PrintDiv()">PRINT Customer Copy</button>
                    </td>
             

        </table>
    </div>
    
    
</div>
</body>
     <script type="text/javascript">
        function PrintDiv2() {
            var divContents = document.getElementById("Certificate").innerHTML;
            var printWindow = window.open('', '', 'height=1000,width=800');
            printWindow.document.write('<html><head> <style>table tr td { font-size:14px;color:black;padding-left:10px; }</style><title>Customer Copy</title><link href="../public/pdf/bootstrap.min.css" rel="stylesheet" />');
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
        printWindow.document.write('<html><head> <style>tr td { font-size:14px;color:black;padding-left:10px;  }</style><title>Department Copy</title><link href="../Content/bootstrap.min.css" rel="stylesheet" />');
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
            var divContents = document.getElementById("Certificate").innerHTML + "<script>" +
                        "window.onload = function() {" + 

                        " window.print();" + 
                        "};" +
                        "<" + "/script>";

            var printWindow = window.open('', '', 'height=1000,width=800');
            printWindow.document.write('<html><head> <title>Dealer Copy</title> <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">');
            printWindow.document.write('</head><body style="padding:0;margin-top:20;">');

            printWindow.document.write(divContents);
            printWindow.document.write('</body></html>');
            printWindow.document.close();

             document.getElementById("btnHide").style.display = "block";
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
