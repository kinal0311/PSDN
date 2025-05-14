<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <?php
    $title='Universal Tele Services';
  ?>
    <title><?php echo $title ?></title>
    <link rel="shortcut icon" href="<?php echo base_url(); ?>public/favicon.ico?v=1">
    <link href="<?php echo base_url() ?>public/font/font.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>public/font/icon.css" rel="stylesheet" type="text/css">
     <link href="<?php echo base_url() ?>public/css/pdf/report.css" rel="stylesheet" type="text/css">
    <!-- Bootstrap Core Css -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/plugins/bootstrap/css/bootstrap.css"  />
</head>
  <body >
    
<div class="well divresize" id="Certificate" >
<style type="text/css">
  @media print {
    .bordernone{
      border: none !important;
    }
    .divres2ize{
      margin-left:10%;
      width: :80%;
    }
    .datetr{background: grey;color: white;}
  }
  .datetr{background: grey !important;color: white  !important;}
</style>
    <table class="table">
      <tr>
          <th colspan="25" class="bordernone" style="border: none;text-align: center;">Inventory Report</th>
      </tr>
    </table>
    <table class="table">
        <tr>
            <th  class="bordernone"  colspan="3">Total Sales : <?php echo count($reportData); ?></th>
            <th  class="bordernone" style="    float: right;display: none;">Start Date  : <?php echo date('Y-m-d',strtotime($params['start_date'])); ?></th>
        </tr>

        <?php
        $dealerName='All';
        if(isset($_GET['user_id']) && (int)$_GET['user_id']>0)
        {
          $dealerName=isset($reportData[0]['user_name'])?$reportData[0]['user_name']:"";
        }
        ?>
        <tr> 
            <th  class="bordernone" colspan="3">Dealer Name : <?php echo $dealerName; ?></th>
            <th  class="bordernone" style="    float: right;display: none;">End Date  : <?php echo date('Y-m-d',strtotime($params['end_date'])); ?></th>    
        </tr>
        
    </table>

    <table class="table">
      <thead>
        <tr>
          
          <th>SLD Serial No.</th></th>
          <th>Sale Date</th>
          <th>Fixed</th>
          <th>Fixed Date</th>
          <th>Vehicle No.</th>
        </tr>
      </thead>
      <tbody>
      <?php   
      if(count($reportData)>0)
      {
        $Available_SLD=0;
        $Solded_SLD=0;
        foreach ($reportData as $key => $value) {      
         $currentDate=$value['s_created_date'];   
         $currentDate=date('Y-m-d',strtotime($currentDate));   
         $yes='';
          if(isset($value['veh_create_date']) && !empty($value['veh_create_date']))
          {
          $yes='Yes';     
          }
        ?>      
          <tr>
            <td><?php echo $value['s_serial_number']; ?></td>
            <td><?php echo $currentDate; ?></td>
            <?php
              $fix_date='';
              $veh_no='';
              if(isset($value['veh_create_date']) && !empty($value['veh_create_date']))
              {
                $fix_date=date('Y-m-d',strtotime($value['veh_create_date']));  
              }
               if(isset($value['veh_rc_no']) && !empty($value['veh_rc_no']))
              {
                $veh_no=$value['veh_rc_no'];
              }
            ?>
            <td><?php echo $yes; ?></td>
            <td><?php echo $fix_date; ?></td>
            <td><?php echo $veh_no; ?></td>
          </tr>
        <?php         
        }
      }else{
           ?>
             <tr style="text-align: center;"><td colspan="5">No Records Found</td></tr>
           <?php
      }
      ?>
      </tbody>
    </table>
</div>
<div style="margin:auto">
         <table style="margin-bottom:10px;margin-left:auto;margin-right:auto;margin-top:10px;padding:10px;">
            <tbody>
               <tr>                 
                  <td style="padding:10px;">
                     <button name="Print" class="btn btn-info" onclick="CustomerCopy(3)">PRINT</button>
                  </td>
                  <td style="padding:10px;">
                     <button name="Print" class="btn btn-info"  onclick='window.history.go("-1");return false;'>Go Back</button>
                  </td>
               </tr>
            </tbody>
         </table>
</div>
 <script type="text/javascript">
        function CustomerCopy() {
    var style='<link href="<?php echo base_url(); ?>public/plugins/bootstrap/css/bootstrappdf.css" rel="stylesheet" />';style='';
            var divContents = document.getElementById("Certificate").innerHTML;
            var printWindow = window.open('', '', 'height=1024,width=1024');
            printWindow.document.write('<html><head><title>Dealer Inventory Report</title>'+style);
            printWindow.document.write('</head><style>.datetr{background: grey;color: white;}</style><body style="padding:0;margin-top:20;">');
             printWindow.document.write('<link href="<?php echo base_url(); ?>public/css/pdf/report.css" rel="stylesheet" type="text/css" />');
            printWindow.document.write(divContents);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            function show() {
                if (printWindow.document.readyState === "complete") {
                    printWindow.focus();
                    printWindow.print();
                    printWindow.close();
                } else {
                    setTimeout(show, 5000);
                }
            };
             setTimeout(show, 2000);
        };
       // setTimeout(CustomerCopy,5000)
    </script>

  </body>
</html>