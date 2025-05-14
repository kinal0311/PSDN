<html>
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <style type="text/css">
      </style>
      <style type="text/css">
      </style>
   </head>
   <body>
      <table cellpadding="0" border="0" align="center" cellspacing="0" style=" border-collapse: collapse;">
         <tbody>
            <tr>
               <td colspan="6" style="height:1px;text-align: center;padding-top: 0em;display: block;">
                  <h1>SPEED LIMITING DEVICE</h1>
               </td>
            </tr>
            <tr>
               <td colspan="6" style="height:1px;text-align: center;padding-top: 0em;display: block;">
                  <h3>ONLINE FITMENT CERTIFICATE</h3>
               </td>
            </tr>
            <tr>
               <td colspan="6" style="height:1px;text-align: center;padding-top: 0em;display: block;">
                  <h3>(COMPLIANCE TO AIS 018,AIS 037 STANDARD)</h3>
               </td>
            </tr>
            <tr>
               <td colspan="6" style="height:1px;text-align: center;padding-top: 0em;display: block;">
                  <h5>AIS004 (PART-3)</h5>
               </td>
            </tr>
         </tbody>
      </table>
      <table border="0" cellpadding="5" cellspacing="0" style="padding:15px 0 15px 0;border-bottom: 0px solid white;border-top: 1px solid #000;
         border-left: 1px solid #000;border-right: 1px solid #000;">
         <tbody>
            <tr>
               <td colspan="4" style="height:5px;"></td>
            </tr>
            <tr>
               <td  style="font:15px arial;color:#666;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;">RTO:</strong>
               </td>
               <td  style="font:15px arial;color:#000;"><?php echo isset($userinfo['rto_number'])?$userinfo['rto_number']:""; ?>
               </td>
               <td style="font:15px arial;color:#666;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;">Dealer ID:</strong>
               </td>
               <td  style="font:15px arial;color:#000;"><?php echo isset($userinfo['veh_created_user_id'])?$userinfo['veh_created_user_id']:""; ?>
               </td>
            </tr>
            <tr>
               <td style="font:15px arial;color:#666;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;">Vehicle No:</strong>
               </td>
               <td  style="font:15px arial;color:#000;"><?php echo isset($userinfo['veh_rc_no'])?$userinfo['veh_rc_no']:""; ?>
               </td>
               <td style="font:15px arial;color:#666;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;">Date:</strong>
               </td>
               <td  style="font:15px arial;color:#000;"><?php echo isset($userinfo['veh_create_date'])?$userinfo['veh_create_date']:""; ?>
               </td>
            </tr>
            <tr>
               <td style="font:15px arial;color:#666;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;">Chassis No:</strong>
               </td>
               <td  style="font:15px arial;color:#000;"><?php echo isset($userinfo['veh_chassis_no'])?$userinfo['veh_chassis_no']:""; ?>
               <td style="font:15px arial;color:#666;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;">Validity:</strong>
               </td>
               <td  style="font:15px arial;color:#000;"><?php echo isset($userinfo['Validity'])?$userinfo['Validity']:""; ?> </td>
            </tr>
            <tr>
               <td style="font:15px arial;color:#666;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;">Engine No:</strong>
               </td>
               <td  style="font:15px arial;color:#000;"><?php echo isset($userinfo['veh_engine_no'])?$userinfo['veh_engine_no']:""; ?> </td>
               <td style="font:15px arial;color:#666;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;">SLD Make:</strong>
               </td>
               <td  style="font:15px arial;color:#000;"><?php echo isset($userinfo['veh_make_no'])?$userinfo['veh_make_no']:""; ?> </td>
            </tr>
            <tr>
               <td style="font:15px arial;color:#666;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;">Vehicle Make:</strong>
               </td>
               <td  style="font:15px arial;color:#000;"><?php echo isset($userinfo['veh_make_no'])?$userinfo['veh_make_no']:""; ?> </td>
               <td style="font:15px arial;color:#666;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;">SLD SERIAL NO:</strong>
               </td>
               <td  style="font:15px arial;color:#000;"><?php echo isset($userinfo['veh_serial_no'])?$userinfo['veh_serial_no']:""; ?> </td>
            </tr>          
            <tr>
               <td style="font:15px arial;color:#666;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;">Vehicle Model:</strong>
               </td>
               <td  style="font:15px arial;color:#000;"><?php echo isset($userinfo['veh_model_no'])?$userinfo['veh_model_no']:""; ?> </td>
               <td style="font:15px arial;color:#666;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;">TAC No:</strong>
               </td>
               <td  style="font:15px arial;color:#000;"><?php echo isset($userinfo['veh_tac'])?$userinfo['veh_tac']:""; ?> </td>
            </tr>
            <tr>
               <td style="font:15px arial;color:#666;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;">Owner Name:</strong>
               </td>
               <td  style="font:15px arial;color:#000;"><?php echo isset($userinfo['veh_owner_name'])?$userinfo['veh_owner_name']:""; ?> </td>
               <td style="font:15px arial;color:#666;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;">Set Speed:</strong>
               </td>
               <td  style="font:15px arial;color:#000;"><?php echo isset($userinfo['veh_speed'])?$userinfo['veh_speed']:""; ?> </td>
            </tr>
            <tr>
               <td style="font:15px arial;color:#666;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;">Phone:</strong>
               </td>
               <td  style="font:15px arial;color:#000;"><?php echo isset($userinfo['veh_owner_phone'])?$userinfo['veh_owner_phone']:""; ?> </td>
               <td style="font:15px arial;color:#666;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;">COP Validity:</strong>
               </td>
               <td  style="font:15px arial;color:#000;"><?php echo isset($userinfo['Validity'])?$userinfo['Validity']:""; ?> </td>
            </tr>
            <tr>
               <td style="font:15px arial;color:#666;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;">Invoice No:</strong>
               </td>
               <td  style="font:15px arial;color:#000;"><?php echo isset($userinfo['veh_invoice_no'])?$userinfo['veh_invoice_no']:""; ?> </td>
               <td style="font:15px arial;color:#666;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;"></strong>
               </td>
               <td style="font:15px arial;color:#000;"></td>
            </tr>
            <tr>
               <td style="font:15px arial;color:#666;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;">Owner Address:</strong>
               </td>
               <td style="font:15px arial;color:#000;">
                  <address style="border: 1px solid grey;">
                     <?php echo isset($userinfo['veh_address'])?$userinfo['veh_address']:""; ?>
                  </address>
               </td>
               <td style="font:15px arial;color:#666;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;">Dealer Address:</strong>
               </td>
               <td style="font:15px arial;color:#000;">
                  <address style="border: 1px solid grey;">
                     <?php echo isset($userinfo['user_info'])?$userinfo['user_info']:""; ?>
                  </address>
               </td>
            </tr>
         </tbody>
      </table>
      <table border="0" cellpadding="5" cellspacing="0" style="padding:15px 0 15px 0;border-top: 0px solid white;border-bottom: 3px solid #000;
         border-left: 1px solid #000;border-right: 1px solid #000;">
         <tbody>
            <tr>
               <td colspan="2" style="font:15px arial;color:#666;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;"></strong>
               </td>
               <td  colspan="2"  style="font:15px arial;color:#666;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;"></strong>
               </td>
               <td  colspan="2"  style="font:15px arial;color:#666;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;"></strong>
               </td>
            </tr>
            <tr>
               <td colspan="2" style="font:15px arial;color:#666;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;"></strong>
               </td>
               <td  colspan="2"  style="font:15px arial;color:#666;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;"></strong>
               </td>
               <td  colspan="2"  style="font:15px arial;color:#666;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;"></strong>
               </td>
            </tr>
            <tr>
               <td colspan="2" style="font:15px arial;color:#666;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;">Certificate QR Code:</strong>
               </td>
               <td  colspan="2"  style="font:15px arial;color:#666;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;">Vehicle Photo:</strong>
               </td>
               <td  colspan="2"  style="font:15px arial;color:#666;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;">Speed Governor Photo:</strong>
               </td>
            </tr>
            <tr>
               <td  colspan="2" style="font:15px arial;color:#000;">
                  <img width="132px" height="132px" src="<?php echo $userinfo['qrcodeimg']; ?>" />
               </td>
               <td  colspan="2" style="font:15px arial;color:#000;">
                  <img width="132px" height="132px" src="<?php echo base_url().$userinfo['veh_photo']; ?>" />
               </td>
               <td  colspan="2" style="font:15px arial;color:#000;">
                  <img width="132px" height="132px" src="<?php echo base_url().$userinfo['veh_speed_governer_photo']; ?>" />
               </td>
            </tr>
            <tr>
               <td colspan="2" style="font:15px arial;color:#666;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;"></strong>
               </td>
               <td  colspan="2"  style="font:15px arial;color:#666;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;"></strong>
               </td>
               <td  colspan="2"  style="font:15px arial;color:#666;text-align: right;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;text-align: right;">Authorised:</strong>
               </td>
            </tr>
            <tr>
               <td colspan="4" style="font:15px arial;color:#666;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;">This is to certify that following vehicle has been fitted with approved <b>HOVEL</b> Electronic speed Governor, Which is set to a maximum per set speed 65Kmph(+/- 2%) and shall now exceed this speed in any circumstances.unless the device is tempered or the seal is broken by unauthorised technicians or indiviidual.</strong>
               </td>
               <td  colspan="2">
               </td>
            </tr>
            <tr>
               <td colspan="4" style="font:15px arial;color:#666;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;">We have sealed this device at critical points and the cerificate is being issued only after testing.</strong>
               </td>
               <td  colspan="2"  style="font:15px arial;color:#666;text-align: right;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;text-align: right;">Dealer Signature:</strong>
               </td>
            </tr>
         </tbody>
      </table>
     
   </body>
    <table border="0" cellpadding="5" cellspacing="0" style="padding:15px 10px 15px 0;margin-top:10px;border:0px solid white;">
         <tbody>
            <tr>
               <td colspan="4" style="font:15px arial;color:#666;">
                  <strong style="padding-top:5px;font:15px arial;color:#666;">For repairs and breakdown please contact the respective Ditrict Dealers. if not attended within 2 working days please inform us in writing E-mail:info@tediiindia.com Toll Free No 1800 121 5800</strong>
               </td>
            </tr>
         </tbody>
      </table>
</html>