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

    
<style type="text/css">

table tr td {
         
      padding-top: 3px;
      padding-bottom: 3px;
      font-size:14px;
      color:black;
      padding-left:10px;
}
.invoice-info{ 
	margin-top:20px;	
	}
.inv-sub-title{ font-size:18px; padding-top:5px; padding-bottom:10px; display:block;
	
	}	
</style>
    <link href="../public/pdf/bootstrap-theme.css" rel="stylesheet" />
    <link href="../public/pdf/bootstrap-theme.min.css" rel="stylesheet" />
    <link href="../public/pdf/bootstrap.min.css" rel="stylesheet" />
    <script src="../public/pdf/jquery-3.1.1.min.js"></script>
    <script src="../public/pdf/jquery-ui.min.js"></script>
    <script src="../public/pdf/bootstrap.js"></script>
    <script src="../public/pdf/bootstrap.min.js"></script>

<title></title>
</head>

<body>

<div id="Certificate" class="container-fluid" style="margin-top:30px;">
<div class="container-fluid" style=" margin:auto; padding:0; ">
<div class="row" style="margin-top:10px; ">
<div class="col-sm-offset-2 col-sm-8" style=" border:1px solid #999" >

<?php 

function convert_number($number) {

    if (($number < 0) || ($number > 999999999)) {
      throw new Exception("Number is out of range");
    }

    $Gn = floor($number / 1000000);

    /* Millions (giga) */

    $number -= $Gn * 1000000;
    $kn = floor($number / 1000);

    /* Thousands (kilo) */

    $number -= $kn * 1000;
    $Hn = floor($number / 100);

    /* Hundreds (hecto) */

    $number -= $Hn * 100;
    $Dn = floor($number / 10);

    /* Tens (deca) */

    $n = $number % 10;

    /* Ones */

    $res = "";

    if ($Gn) {

      $res .= convert_number($Gn) .  "Million";
    }

    if ($kn) {

      $res .= (empty($res) ? "" : " ") .convert_number($kn) . " Thousand";

    }

    if ($Hn) {

      $res .= (empty($res) ? "" : " ") .convert_number($Hn) . " Hundred";

    }

    $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");

    $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", "Seventy", "Eigthy", "Ninety");

    if ($Dn || $n) {

      if (!empty($res)) {

        $res .= " and ";

      }

      if ($Dn < 2) {

        $res .= $ones[$Dn * 10 + $n];

      } else {

        $res .= $tens[$Dn];

        if ($n) {

          $res .= "-" . $ones[$n];

        }

      }

    }

    if (empty($res)) {

      $res = "zero";

    }

    return $res;

  }


?>

<section class="invoice">

  <!-- title row -->

      <div class="row">
        <div class="col-xs-6" >
          
           <img src="<?php echo base_url().$userinfo['companylogo'];?>" >
            <small class="pull-right"></small>
         
        </div>
        <div class="col-xs-6" style="text-align:right;">
          <h2 >Invoice</h2> <h4>#<?php echo $userinfo['invoice_number']; ?></h4><br>          
            <small class="pull-right">
            
			<b><?php echo date('d-M-Y', strtotime($userinfo['i_created_date'])); ?></b>
            </small><br>
           
        </div>
        <!-- /.col -->
      </div>

<!-- info row 1 starts-->

      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
        <b class="inv-sub-title"><?php echo $userinfo['FromCompany']; ?></b>
        </div>

        <!-- /.col -->

        <div class="col-sm-4 invoice-col">
       

        </div>

        <!-- /.col -->

        <div class="col-sm-4 invoice-col">

         

          

        </div>

        <!-- /.col -->

      </div>

      <!-- /.row -->
      
      <!-- info row 2 starts-->

      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">

          <b class="inv-sub-title">FROM</b>

          <address>

            <strong><?php echo $userinfo['FromCompany']; ?></strong><br>
			<?php /*echo $userinfo['user_name'];*/ ?>
            <?php echo nl2br($userinfo['user_info']); ?><br>

            GSTIN: <?php echo $userinfo['gstin']; ?><br>
            Email: <?php echo $userinfo['user_email']; ?>

          </address>

        </div>

        <!-- /.col -->

        <div class="col-sm-4 invoice-col"><b class="inv-sub-title">INVOICE TO</b><address>

            <strong><?php echo $userinfo['ToCompany']; ?></strong><br>
			<?php echo $userinfo['user_name2']; ?><br>
            <?php echo nl2br($userinfo['user_info2']); ?><br>

            GSTIN: <?php echo $userinfo['gstin2']; ?><br>
            Email: <?php echo $userinfo['user_email2']; ?>

          </address>

        </div>

        <!-- /.col -->

        <div class="col-sm-4 invoice-col">

         <b class="inv-sub-title">INVOICE DETAILS</b>
        <address>
		  <b>Brand:</b> <?php echo $userinfo['c_company_name']; ?><br>
          <b>Product:</b> <?php echo $userinfo['p_product_name']; ?><br>
          <b>Invoice Date:</b> <?php echo date('d-M-Y', strtotime($userinfo['i_created_date'])); ?><br>
          <b>Due Date:</b> 
		  <?php echo date('d-M-Y', strtotime($userinfo['i_created_date']) + (INVOICE_DUE_PERIOD * 86400)); ?>
          
          <br>

			<?php 
            /*print_r('<pre>');
            print_r($userinfo);
            print_r('</pre>');
            echo base_url(); */
             ?>
            </address>

         
        </div>

        <!-- /.col -->

      </div>

      <!-- /.row -->



      <!-- Table row -->

      <div class="row">

        <div class="col-xs-12 table-responsive">

          <table width="100%" class="table table-striped" cellpadding="0" cellspacing="0">

            <thead>

            <tr>

              <th>#</th>

              <th>Serial Number</th>

              <th>IMEI</th>

              <th>Mobile</th>
              
              <th>Qty</th>

              <th>Subtotal</th>

            </tr>

            </thead>

            <tbody>

            <?php 

              $sub_total = 0;
			  $count = 0;

              foreach($serialsinfo as $serialinfo) { $count++; ?>

            <tr>

              <td><?php echo $count; ?></td>

              <td><?php echo $serialinfo['s_serial_number'] ?></td>

              <td><?php echo $serialinfo['s_imei'] ?></td>

              <td><?php echo $serialinfo['s_mobile'] ?></td>
              
              <td>1</td>

              <td ><?php 

                if($userinfo['i_user_type'] == 2){

                  $unit_price = $serialinfo['distributor_price'];

                }else if($userinfo['i_user_type'] == 1){

                  $unit_price = $serialinfo['dealer_price'];

                }else{

                  $unit_price = $serialinfo['admin_price'];

                }

                

                $sub_total += $unit_price;



                echo number_format($unit_price, 2);



              ?></td>

            </tr>

            <?php } ?>            

            </tbody>

          </table>

        </div>

        <!-- /.col -->

      </div>

      <!-- /.row -->



      <div class="row">

        <!-- accepted payments column -->

        <div class="col-xs-6">

           <p class="lead">Payment Methods:</p>
          <img src="../public/images/bank-transfer.png" alt="NEFT / IMPS / RTGS "> NEFT / IMPS / RTGS <br>
          <b>Acc Number:</b> 10038978186,<br> <b>Acc Name:</b> PSDN TECHNOLOGY PRIVATE LIMITED,<br> <b>IFSC:</b> IDFB0080106 <br>
          <b>Branch</b>: ANNA NAGAR BRANCH, CHENNAI- 600-040.
          
          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">

            <b>Terms & Conditions</b><br>
            1. Payment to be released in favor of "PSDN TECHNOLOGY PRIVATE LIMITED." by A/C Payee Cheque/Demand Draft/NEFT-RTGS. <br>
            2. Interest @ 18% charged if the invoices are not cleared within due dates as per payment terms mentioned in the invoice. <br>
            3. CSIL shall have every lien on the goods until invoices will remain outstanding fully/Partly. <br>
            4. All disputes shall be subjected to Chennai, Tamil Nadu Jurisdiction only. <br>
            5. Any claim regarding material rejection should be intimated to us within 5 days from the date of invoice otherwise no claims shall be entertained later.<br>
            6. Our liability shall be limited to the cost of bare Products and cost of Product production on boards supplied by " PSDN TECHNOLOGY PRIVATE LIMITED " only.<br>
            7. We take utmost care in selecting a mode of transport, but in case of any damage or loss of material or delay in transit, we will not be able to accept any
            claims.<br>
            8. All Terms & Conditions mentioned in the Quotation & Proforma invoice are applicable.<br>


          </p> 

        </div>

        <!-- /.col -->
		<div class="col-xs-1">&nbsp;</div>
        <!-- /.col -->
        <div class="col-xs-4">
          <!-- <p class="lead">Amount Due 2/22/2014</p> -->
          <div class="table-responsive" style="text-align:right">

            <table border="0" class="table" style="width:100%"  cellpadding="0" cellspacing="0" >

              <tr>

                <th style="width:50%">Subtotal:</th>

                <td><?php 

                    echo number_format($sub_total, 2); 

                    $cgst = $sub_total * CGST_PERCENT / 100;

                    $sgst = $sub_total * SGST_PERCENT / 100;

                    $total = $sub_total + $cgst + $sgst;                    

                ?></td>

              </tr>
              
              <tr>

                <th>SGST (<?php echo SGST_PERCENT; ?>%)</th>

                <td><?php echo number_format($sgst, 2); ?></td>

              </tr>

              <tr>

                <th>CGST (<?php echo CGST_PERCENT; ?>%)</th>

                <td><?php echo number_format($cgst, 2); ?></td>

              </tr>

              

              <tr>

                <th>Total:</th>

                <td><?php echo number_format($total, 2); ?></td>

              </tr>
           

            </table>

          </div>          
		
        </div>
        
        <div class="col-xs-1">&nbsp;</div>
        <!-- /.col -->

      </div>

      <!-- /.row -->



      <!-- this row will not appear when printing -->

      <div class="row no-print" style="padding-bottom:25px">
		<div class="col-xs-6">
        <div style="display:block; float:left; margin-top:0px; margin-left:0px; border:1px solid #CCC; width:100%; min-height:100px; padding:5px 0 0 5px;" >
        <?php echo $userinfo['i_comments']; ?>
        </div>
        </div>
        <div class="col-xs-6">
 		<b>Amount in words:</b> <?php echo convert_number($total) . ' only' ; ?>
        <br>
          <br>
          <a class="btn btn-default" onclick="PrintDiv()"><i class="fa fa-print">          
          </i> Print Invoice </a>

          <!-- <button type="button" class="btn btn-success pull-right">
               <i class="fa fa-credit-card"></i> Submit Payment</button>
          		<button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
            	<i class="fa fa-download"></i> Generate PDF
          		</button> -->

        </div>
      </div>

    </section>

</div>
</div>
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

            printWindow.document.write('<html><head> <style>tr td { font-size:14px;color:black;padding-left:10px;  }</style><title>Dealer Copy</title><link href="../public/pdf/bootstrap.min.css" rel="stylesheet" />');

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

