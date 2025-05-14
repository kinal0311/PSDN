


<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">

<head><link href="../public/pdf/bootstrap-theme.css" rel="stylesheet" /><link href="../public/pdf/bootstrap-theme.min.css" rel="stylesheet" /><link href="../public/pdf/bootstrap.min.css" rel="stylesheet" />
    <script src="../public/pdf/jquery-3.1.1.min.js"></script>
    <script src="../public/pdf/jquery-ui.min.js"></script>
    <script src="../public/pdf/bootstrap.js"></script>
    <script src="../public/pdf/bootstrap.min.js"></script>
    
    <style>
      table tr td {
            
            padding-top: 3px;
            padding-bottom: 3px;
            font-size:14px;
            color:black;
            padding-left:10px;
            
        }
    </style>
<title>

</title></head>
<body>
    
    
   <div id="Certificate" class="container-fluid" style="margin-top:30px;">
       <link href="../public/pdf/bootstrap-theme.css" rel="stylesheet" />
    <link href="../public/pdf/bootstrap-theme.min.css" rel="stylesheet" />
    <link href="../public/pdf/bootstrap.min.css" rel="stylesheet" />
    <script src="../public/pdf/jquery-3.1.1.min.js"></script>
    <script src="../public/pdf/jquery-ui.min.js"></script>
    <script src="../public/pdf/bootstrap.js"></script>
    <script src="../public/pdf/bootstrap.min.js"></script>
       
           <div class="container-fluid" style=" margin:auto; padding:0;">
   <div  class="row" style="margin:auto;padding:0;color:black;">
       
       <div class="container-fluid" style="margin:auto; padding:0;">
           <center><h3 style="font-family:Georgia, &#39;Times New Roman&#39;, Times, serif;font-weight:bold; padding-top:0px;padding-bottom:0px;">SPEED LIMITING DEVICE </h3>
                     <p style="font-family:Georgia, &#39;Times New Roman&#39;, Times, serif;font-weight:bold;font-size:14px;margin:0;padding:0"> ONLINE FITMENT CERTIFICATE</p>
                     <p style="font-family:Times New Roman;font-weight:bold; align-content:center;font-size:12px;margin:0;padding:0;">(COMPLIANCE TO AIS 018,AIS 037 STANDARD)<br></p>
                     <p style="font-family:Times New Roman;font-weight:bold; align-content:center;font-size:12px;margin:0;padding:0;">AIS004 (PART-3)<br></p></center>
       </div>
      

   </div>
    
      <div class="row" style="margin-top:10px;">
         <div class="col-sm-offset-2 col-sm-8" >
            <table class="col-sm-12 table table-bordered" style="margin-left:auto;margin-right:auto;border-collapse:collapse;border:solid 2px black;">
                <tr>
                    <td><span style="font-family:Times New Roman;">RTO : </span> 
                    <span id="rtolbl" style="font-family:Times New Roman;"><?php echo $userinfo['rto_number'].' '.$userinfo['rto_place']; ?></span></td>
                    <td><span style="font-family:Times New Roman;">Dealer ID : </span>
                    <span id="dealeridlbl" style="font-family:Times New Roman;"><?php echo $userinfo['veh_created_user_id']; ?></span></td>
                </tr>
                 <tr>
                    <td><span style="font-family:Times New Roman;">Vechicle No : </span> 
                    <span id="vhlnolbl" style="font-family:Times New Roman;font-weight:bold;"><?php echo isset($userinfo['veh_rc_no'])?$userinfo['veh_rc_no']:""; ?></span></td>
                    <td><span style="font-family:Times New Roman;">Date : </span>
                    <span id="datelbl" style="font-family:Times New Roman;"><?php echo date('d-m-Y',strtotime($userinfo['veh_create_date'])); ?></span></td>
                </tr>
                <tr>
                    <td><span style="font-family:Times New Roman;">Chassis No : </span> 
                    <span id="chissnolbl" style="font-family:Times New Roman;"><?php echo isset($userinfo['veh_chassis_no'])?$userinfo['veh_chassis_no']:""; ?></span></td>
                    <td><span style="font-family:Times New Roman;">Validity: </span>
                    <span id="expdate" style="font-family:Times New Roman;">From <?php echo date('d-m-Y',strtotime($userinfo['validity_from'])); ?> TO <?php echo date('d-m-Y',strtotime($userinfo['validity_to'])); ?></span></td>
                </tr>
                <tr>
                    <td><span style="font-family:Times New Roman;">Engine NO : </span> 
                    <span id="englbl" style="font-family:Times New Roman;"><?php echo isset($userinfo['veh_engine_no'])?$userinfo['veh_engine_no']:""; ?></span></td>
                    <td><span style="font-family:Times New Roman;">SLD Make : </span>
                    <span id="speedmklbl" style="font-family:Times New Roman;"><?php echo isset($userinfo['c_company_name'])?$userinfo['c_company_name']:""; ?></span></td>
                </tr>
                <tr>
                    <td><span style="font-family:Times New Roman;">Vehicle Make: </span> 
                    <span id="vhlmakelbl" style="font-family:Times New Roman;"><?php echo isset($userinfo['v_make_name'])?$userinfo['v_make_name']:""; ?></span></td>
                    <td><span style="font-family:Times New Roman;">SLD Serial No : </span>
                    <span id="snolbl" style="font-family:Times New Roman;font-weight:bold;"><?php echo isset($userinfo['s_serial_number'])?$userinfo['s_serial_number']:""; ?></span></td>
                </tr>
                <tr>
                    <td><span style="font-family:Times New Roman;">Vehicle Model : </span>  
                    <span id="modellbl" style="font-family:Times New Roman;"><?php echo isset($userinfo['ve_model_name'])?$userinfo['ve_model_name']:""; ?></span></td>
                    <td><span style="font-family:Times New Roman;">IMEI : </span>
                    <span id="coplabel" style="font-family:Times New Roman;"><?php echo isset($userinfo['s_imei'])?$userinfo['s_imei']:""; ?></span></td>
                </tr>
                <tr>
                    <td><span style="font-family:Times New Roman;">Owner Name : </span>  
                    <span id="ownerlbl" style="font-family:Times New Roman;"><?php echo isset($userinfo['veh_owner_name'])?$userinfo['veh_owner_name']:""; ?></span></td>
                    <td><span style="font-family:Times New Roman;">TAC NO : </span>
                    <span id="TAClbl" style="font-family:Times New Roman;"><?php echo isset($userinfo['veh_tac'])?$userinfo['veh_tac']:""; ?></span></td>
                </tr>
                 <tr>
                    
                     <td><span style="font-family:Times New Roman;">Phone : </span>  
                    <span id="phonelbl" style="font-family:Times New Roman;"><?php echo isset($userinfo['veh_owner_phone'])?$userinfo['veh_owner_phone']:""; ?></span></td>
                     <td><span style="font-family:Times New Roman;">Set Speed : </span>
                    <span id="speedlbl" style="font-family:Times New Roman;"><?php echo isset($userinfo['veh_speed'])?$userinfo['veh_speed']:""; ?></span></td>
                </tr>
                <tr>
                    
                    <td><span style="font-family:Times New Roman;">Invoice No. : </span>
                    <span id="invoicelbl" style="font-family:Times New Roman;"><?php echo isset($userinfo['veh_invoice_no'])?$userinfo['veh_invoice_no']:""; ?></span></td>
                     <td><span style="font-family:Times New Roman;">COP Validity : </span>
                    <span id="coplabel" style="font-family:Times New Roman;"><?php echo date('d-m-Y',strtotime($userinfo['veh_cop_validity'])); ?></span></td>
                   
                </tr>
                <tr>
                    <td><span style="font-family:Times New Roman;font-weight:bold;">Owner Address : </span> 
                        <div style="border:2px solid black;min-height:70px; max-height:150px;width:350px;padding-left:5px;padding-right:5px;padding-top:0px;padding-bottom:0px;">
                            
                            <p style="padding:0px;margin:0">
                     <?php echo isset($userinfo['veh_address'])?$userinfo['veh_address']:""; ?>                        
                            </p></div></td>
                    
                    <td><span style="font-family:Times New Roman;font-weight:bold;">Dealer Address : </span>
                        <div style="border:2px solid black;min-height:70px;max-height:150px;width:350px;padding:5px;">
                    
                             <p style="padding:0px;margin:0">
                          <?php echo isset($userinfo['user_info'])?$userinfo['user_info']:""; ?>
                                    
                            </p></div></td>
                </tr>
                <tr>
                    <td colspan="2"style="margin:0;">
            <table border="1" style="margin-right:10px;border-color:lightgrey;">
                <tr>
                    <td style="text-align:center"><b>Certificate QR Code</b></td>
                    <td style="text-align:center"><b>Vechicle Photo</b></td>
                    <td style="text-align:center"><b>Speed Governor Photo</b></td>
                </tr>
                
                <tr style="padding-top:5px;padding-bottom:0px;">

                   <td style="width:250px;height:200px;padding:0;margin:0;"><div style="width:250px;height:200px;padding:0;margin:0;"><div style="margin:auto;padding:0;height:100px;width:125px;"><img  src="<?php echo $userinfo['qrcodeimg']; ?>" style="height:100px;width:125px;margin-top:45px;" /></div></div></td>
                    <td style="align-content:center;padding:10px;"><div style="margin:auto"><img src="<?php echo base_url().$userinfo['veh_photo']; ?>" style="height:200px;width:230px;"/></div></td>
                   
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
                        <p style="font-family:Times New Roman;text-align:justify;padding:2px;font-size:14px;">This is to certify that following vehicle has been fitted with approved <b> <?php echo isset($userinfo['c_company_name'])?$userinfo['c_company_name']:""; ?></b> Electronic Speed Governor, which is set to a maximum pre set speed of <b><?php echo isset($userinfo['veh_speed'])?$userinfo['veh_speed']:""; ?> </b> Kmph(+/- 2%) and shall not exceed this speed in any circumstances, unless the device is tampered or the seal is broken by unauthorised technicians or individual.</p>
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
            </div>
        </div>
        </div>
        
    
   </div>
      
    
    <div style="margin:auto">
    <table style="margin-bottom:10px;margin-left:auto;margin-right:auto;margin-top:10px;padding:10px;">
            <tr>
                    <td style="padding:10px;">
                        <button name="Print" class="btn btn-info" onclick="PrintDiv()">PRINT Dealer Copy</button>
                    </td>
                <td style="padding:10px;">
                        <button name="Print" class="btn btn-info" onclick="PrintDiv1()">PRINT Department Copy</button>
                    </td>
                <td style="padding:10px;">
                        <button name="Print" class="btn btn-info" onclick="PrintDiv2()">PRINT Customer Copy</button>
                    </td>
             

        </table>
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
                    setTimeout(show, 100);
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
                setTimeout(show, 100);
            }
        };
        show();
    };
        
    </script>
    <script type="text/javascript">
        function PrintDiv() {
            var divContents = document.getElementById("Certificate").innerHTML;
            var printWindow = window.open('', '', 'height=1000,width=800');
            printWindow.document.write('<html><head> <style>tr td { font-size:14px;color:black;padding-left:10px;  }</style><title>Dealer Copy</title><link href="../Content/bootstrap.min.css" rel="stylesheet" />');
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
                    setTimeout(show, 100);
                }
            };
            show();
        };
    </script>
</html>
