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
          <th colspan="25" class="bordernone" style="border: none;text-align: center;">Dealer Sale Report</th>
      </tr>
    </table>
    <table class="table">
        <tr>
            <th  class="bordernone">Total Sales : <?php echo count($reportData); ?></th>
            <th  class="bordernone" >Start Date  : <?php echo date('Y-m-d',strtotime($params['start_date'])); ?></th>
        </tr>
        <tr>
            <?php
            $distributer='';
            if(isset($reportData[0]['user_type']) && (string)$reportData[0]['user_type']==='1')
            {
              $distributer=$reportData[0]['distributer'];
            }
            if(!isset($reportData[0]['distributer']) && (string)$reportData[0]['distributer']==='' && (string)$reportData[0]['user_type']==='1')
            {
               $distributer='Admin';
            }
             if(isset($_GET['user_id']) && (string)$_GET['user_id']==='-1')
            {
              $distributer="All";
            }
            ?>
            <th  class="bordernone">Distributor Name : <?php echo $distributer; ?></th>
            <th  class="bordernone" >End Date  : <?php echo date('Y-m-d',strtotime($params['end_date'])); ?></th>    
        </tr>
        <?php
         $dealerName='All';
        if(isset($_GET['user_id']) && (int)$_GET['user_id']>0)
        {
          $dealerName=isset($reportData[0]['dealer'])?$reportData[0]['dealer']:"";
        }
        ?>
        <tr>
            <th  class="bordernone" colspan="2">Dealer Name : <?php echo $dealerName; ?></th>
        </tr>
    </table>

    <table class="table">
      <thead>
        <tr>
          <th>Entry ID</th>
          <th>SLD Serial No.</th></th>
          <th>Vehicle No.</th>
          <th>Set Speed</th>
          <th>Owner Name</th>
        </tr>
      </thead>
      <tbody>
      <?php   
      if(count($reportData)>0)
      {
        $loopDate="";    
        foreach ($reportData as $key => $value) {        
        ?>

        <?php
          $currentDate=$value['veh_create_date'];
          if((string)$currentDate != (string)$loopDate)
          {
        ?>
           <tr class="datetr" >
              <td colspan="5">
                <?php
                  echo date('l, M d, Y',strtotime($value['veh_create_date']));
                  $loopDate=$value['veh_create_date'];
                ?>              
              </td>
            </tr>
        <?php
        }           
      ?>      
          <tr>
            <td><?php echo $value['veh_id']; ?></td>
            <td><?php echo $value['s_serial_number']; ?></td>
            <td><?php echo $value['veh_rc_no']; ?></td>
            <td><?php echo $value['veh_speed']; ?></td>
            <td><?php echo $value['veh_owner_name']; ?></td>
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
                     <button name="Print" class="btn btn-info" onclick='window.history.go("-1");return false;'>Go Back</button>
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
            printWindow.document.write('<html><head><title>Dealer Copy</title>'+style);
            printWindow.document.write('</head><style>.datetr{background: grey;color: white;}</style><body style="padding:0;margin-top:20;">');
             printWindow.document.write('<link href="../public/css/pdf/report.css" rel="stylesheet" type="text/css" />');
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