<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Invoice extends CI_Controller {

	

	// Load Constructur

	public function __construct() {

        parent::__construct();

		$this->load->model('adminmodel');

		$this->load->model('commonmodel');

		$this->load->library("pagination");

		$this->load->library('session');
		
		$this->load->model('invoicemodel');

		

    }
    
    public function create_proforma_invoice(){
		
		
		
		$_SESSION['currentActivePage'] = 'Create_Cerificate';
		
		$user_company_id = $this->session->userdata('user_company_id');
		$user_type 		 = $this->session->userdata('user_type');
		$user_id		 = $this->session->userdata('user_id');
		
		$data['message'] = '';
		
		if($this->input->post('hidsubmit')!=""){
		
			$txtcreateto		= $this->input->post('txtcreateto');
			$txtpicompany		= $this->input->post('txtpicompany');
			$txtdropdowndisdeal	= $this->input->post('txtdropdowndisdeal');
			$txtcompanyname		= $this->input->post('txtcompanyname');
			$txtaddress			= $this->input->post('txtaddress');
			$txtgsttin			= $this->input->post('txtgsttin');
			$txtmobile			= $this->input->post('txtmobile');
			$txtemail			= $this->input->post('txtemail');			
			$txtduedate			= $this->input->post('txtduedate');
			$txtdeliverynote	= $this->input->post('txtdeliverynote');
			$txttermsofdelivery	= $this->input->post('txttermsofdelivery');
			
			
			
			$datains_arr		= array("createto" => $txtcreateto, "company_id" => $txtpicompany, "dealer_distributer_customer" => $txtdropdowndisdeal, "company_name" => $txtcompanyname, "company_address" => $txtaddress, "gstin" => $txtgsttin, "mobile" => $txtmobile, "email" => $txtemail, "duedate" => $txtduedate, "deliverynote" => $txtdeliverynote, "termsofdelivery" => $txttermsofdelivery, "user_id" => $user_id, "user_type" => $user_type);
			
			$this->db->insert("ci_proformainvoice", $datains_arr);
			$insertid = $this->db->insert_id();

			// echo "<pre>";
			// echo "testing==>";
			// print_r($insertid);
			// exit;
			
			
			
			
			
			$get_competitors_ids_str	= "";
			
			foreach ($_POST as $key => $value) {
			
				$get_competitors_ids = $this->invoicemodel->get_competitors_ids($key);
				
				if($get_competitors_ids!=""){
					$get_competitors_ids_str .= $get_competitors_ids.",";
				}
				
			
			}	
			
			$get_competitors_ids_str_arr = array();
			
			if($get_competitors_ids_str!=""){
			
				$get_competitors_ids_str = substr($get_competitors_ids_str,0,strlen($get_competitors_ids_str)-1);
				
				$get_competitors_ids_str_arr = explode(",",$get_competitors_ids_str);
			
			}
			
			for($i=0;$i<sizeof($get_competitors_ids_str_arr);$i++){
			
				$txtaddproduct			= $this->input->post('txtaddproduct_'.$get_competitors_ids_str_arr[$i]);
				$txtaddqty				= $this->input->post('txtaddqty_'.$get_competitors_ids_str_arr[$i]);
				$txtaddunitprice		= $this->input->post('txtaddunitprice_'.$get_competitors_ids_str_arr[$i]);
				$txtaddofferprice		= $this->input->post('txtaddofferprice_'.$get_competitors_ids_str_arr[$i]);
				$txtaddrate				= $this->input->post('txtaddrate_'.$get_competitors_ids_str_arr[$i]);
				
				$datainsarray 			= array("ref_invoiceid" => $insertid, "product_id" => $txtaddproduct, "quantity" => $txtaddqty, "unit_price" => $txtaddunitprice, "offer_price" => $txtaddofferprice, "rate" => $txtaddrate );
				
				
				
				$this->db->insert("ci_proformainvoice_products", $datainsarray);
				
				
				
				
			}

			$data['message'] = 'insert';
			
			$txtsendmail		= $this->input->post('txtsendmail');
			
			
				
			require APPPATH.'/libraries/PHPMailer/PHPMailerAutoload.php';
		
			include APPPATH.'libraries/pdfs/mpdf.php';
		
		
		$datas_invoice = $this->invoicemodel->fetch_proformainvoicedatas($insertid);
		
		$html = '<html><body style="font-family:arial;font-size:12px;"><table width="100%"><tr><td width="70%">&nbsp;</td><td width="30%" align="right"><b><span style="font-size:20px;">Proforma Invoice</span></b><br>#'.$datas_invoice->id.'<br>'.$datas_invoice->invoice_generate_date.'</td></tr><tr><td colspan="2"><font style="font-size:17px;"><b>PSDN Technology Pvt Ltd</b></font></td></tr><tr><td colspan="2"><b>PSDN Technology Pvt Ltd</td></tr><tr><td colspan="2">ADMIN No. 1/14, Raja Naicker Street,</td></tr><tr><td colspan="2">Sivabootha, Vanagaram, Chennai - 600095.</td></tr><tr><td colspan="2">GSTIN: 33AAKCP4319G1ZI</td></tr><tr><td colspan="2">Email: admin@psdn.in</td></tr></table><br><table width="100%" cellpadding="5" cellspacing="0" border="1"><tr><td width="33.33%"><b>FROM</b></td><td width="33.33%"><b>INVOICE TO</b></td><td width="33.33%"><b>INVOICE DETAILS</b></td></tr><tr><td valign="top"><b>'.$datas_invoice->user_own_company.'</b><br>'.$datas_invoice->user_info.'<br>GSTIN: 33AAKCP4319G1ZI <br>Email: '.$datas_invoice->user_email.'</td><td valign="top"><b>'.$datas_invoice->company_name.'</b><br>'.$datas_invoice->company_address.'<br>GSTIN: '.$datas_invoice->gstin.'<br>Email: '.$datas_invoice->email.'</td><td valign="top"><b>Invoice Date: </b>'.date('d-m-Y',strtotime($datas_invoice->invoice_generate_date)).'<br><b>Due Date: </b>'.date('d-m-Y',strtotime($datas_invoice->duedate)).'</td></tr></table><br><table width="100%" cellpadding="5" cellspacing="0" border="1"><tr><td width="1%"><b>Sl.No</b></td><td width="49%"><b>Description of Goods</b></td><td width="10%"><b>Quantity</b></td><td width="10%"><b>Rate</b></td><td width="10%"><b>Offer</b></td><td width="10%"><b>Amount</b></td></tr>';
		
		$datas_invoice_products = $this->invoicemodel->fetch_proformainvoice_products($insertid);
		
		$slno 				= 1;
		$total_qty 			= 0;
		$total_amt			= 0;
		
		foreach($datas_invoice_products as $row) {
			
			$p_product_name		= $row->p_product_name;
			$p_unit_price		= $row->p_unit_price;
			$quantity			= $row->quantity;
			$offer_price		= $row->offer_price;
			
			$amount				= ($p_unit_price * $quantity) - $offer_price;
			
			$total_qty 			= $total_qty + $quantity;
			$total_amt  		= $total_amt + $amount;
			
			$html .= '<tr><td width="1%">'.$slno.'.</td><td width="49%">'.$p_product_name.'</td><td width="10%">'.$quantity.'</td><td width="10%">'.number_format($p_unit_price,2).'</td><td width="10%">'.number_format($offer_price,2).'</td><td width="10%">'.number_format($amount,2).'</td></tr>'; 
			
			$slno++;
			
		}
		
		$subtotal 		= 0;
		$subtotal 		= $total_amt;
		
		$sgst_amt		= $subtotal * (9/100);
		$cgst_amt		= $subtotal * (9/100);
		
		$total 			= $subtotal + $sgst_amt + $cgst_amt;
			
		$html .= '<tr><td width="1%">&nbsp;</td><td width="49%" align="right">&nbsp;</td><td width="10%"><b>'.$total_qty.'</b></td><td width="10%">&nbsp;</td><td width="10%">&nbsp;</td><td width="10%"><b>'.number_format($total_amt,2).'</b></td></tr>';	
		
		$html .= '</table><br>';
		
		$html 		.= '<table width="100%" cellpadding="5" cellspacing="0" border="0">';
		
		$html		.= '<tr><td width="80%" valign="top"><b>Amount in Words:</b> '.$this->invoicemodel->convert_number($total).' </td><td width="10%"><b>Sub Total</b></td><td width="10%"><b>'.number_format($subtotal,2).'</b></td></tr>';
		
		$html		.= '<tr><td width="80%" valign="top">&nbsp;</td><td width="10%"><b>SGST (9%) </b></td><td width="10%"><b>'.number_format($sgst_amt,2).'</b></td></tr>';
		
		$html		.= '<tr><td width="76%" valign="top">&nbsp;</td><td width="12%"><b>CGST (9%) </b></td><td width="10%"><b>'.number_format($cgst_amt,2).'</b></td></tr>';
		
		$html		.= '<tr><td width="80%" valign="top">&nbsp;</td><td width="12%"><b>Total</b></td><td width="10%"><b>'.number_format($total,2).'</b></td></tr>';
		
		$html		.= '</table><br>';
		
		$html		.= '<b>Terms and Delivery:</b><br>';
		
		$html		.= $datas_invoice->termsofdelivery;
		
		$html		.= '<br>';
		
		$html		.= '<b>Terms and Conditions:</b><br>';
		
		$html		.= '1. Payment to be released in favor of "PSDN TECHNOLOGY PRIVATE LIMITED." by A/C Payee Cheque/Demand Draft/NEFT-RTGS.<BR>';

		$html		.= '2. Interest @ 18% charged if the invoices are not cleared within due dates as per payment terms mentioned in the invoice.<BR>';

		$html		.= '3. CSIL shall have every lien on the goods until invoices will remain outstanding fully/Partly.<BR>';

		$html		.= '4. All disputes shall be subjected to Chennai, Tamil Nadu Jurisdiction only.<BR>';

		$html		.= '5. Any claim regarding material rejection should be intimated to us within 5 days from the date of invoice otherwise no claims shall be entertained later.<BR>';

		$html		.= '6. Our liability shall be limited to the cost of bare Products and cost of Product production on boards supplied by " PSDN TECHNOLOGY PRIVATE LIMITED " only.<BR>';

		$html		.= '7. We take utmost care in selecting a mode of transport, but in case of any damage or loss of material or delay in transit, we will not be able to accept any claims.<BR>';

		$html		.= '8. All Terms & Conditions mentioned in the Quotation & Proforma invoice are applicable.<BR>';
		
		$html		.= '<br>';
		
		$html		.= '<p align="right"><b>For PSDN Technology Pvt Ltd</b></p><br><br>';
		$html		.= '<p align="right"><b>Authorized Signatory</b></p>';
		$html .= '</body></html>';
		
		$mpdf=new mPDF('c'); 

		$mpdf->WriteHTML($html);
		
		$filnamepdf = uniqid().'.pdf';
		
		$mpdf->Output('invoice/'.$filnamepdf,'F');
		//$mpdf->Output();
		
		if($txtsendmail!=""){
		
			$mail = new PHPMailer(true);

			$mail->SMTPDebug = 0;  
			$mail->isSMTP();
			
			$mail->Host = "smtp.sendgrid.net"; 

			$mail->SMTPAuth = true;                               
			$mail->Username = 'psdnprabu';                 
			$mail->Password = 'psdn@1234';                           
			$mail->SMTPSecure = 'none';                            
			$mail->Port = 25;
			
			//$to	= "kathiresan.softengg@gmail.com";
			$to = $txtemail;
			
			$mailbody = "Dear Sir/Madam,<br>we have attached proforma invoice. kindly review it.<br><br><b>Regards,</b><BR>PSDN Technology Pvt Ltd.";
			$mailsubject = 'Proforma Invoice';
			$mail->setFrom('sales@psdn.live', 'PSDN');
			$mail->addAddress($to,$to);
		  
			$mail->isHTML(true);
			$messageparam  = $mailbody;
			$mail->Subject = $mailsubject;
			$mail->Body    = $mailbody;
			
			$mail->AddAttachment('invoice/'.$filnamepdf);
			
			$mail->send(); 
			
				
			}

			$this->db->set('invoice_file', $filnamepdf); 
			$this->db->where('id', $insertid); 
			$this->db->update('ci_proformainvoice');
			
			redirect('/invoice/proforma_invoice_list', 'refresh');
		}	
		
		
		
		$companydropdowndatas = $this->invoicemodel->getcompanyalldetails_fromusertype($user_type);
		$data['companydropdowndatas'] = $companydropdowndatas;
		
		if($user_type=="0"){
			$data['companyname'] 	= '';
			$data['companyaddress'] = '';
		}
		else{
			$datascompany = $this->invoicemodel->fetch_companydropdowndatas($user_company_id);
			$data['companyname'] 		= $datascompany->c_company_name;
			$data['companyaddress']		= $datascompany->c_address;	
		}	
		
		$this->load->view('masters/create_proforma_invoice',$data);		
		
	
	}
    
	public function fetch_customerdropdowndatas(){
		
		$txtdropdowndisdeal	= $this->input->post('txtdropdowndisdeal');
		
		$getallentries_inner 	= $this->invoicemodel->fetch_customerdropdowndatas($txtdropdowndisdeal);
		
		
		
		$final_arr			= array("dropdowndatas" => $getallentries_inner);
		
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($final_arr));
		
		
		
	}
	
	public function fetch_companydropdowndatas(){
		
		$txtdropdowndisdeal	= $this->input->post('txtdropdowndisdeal');
		
		$getallentries_inner 	= $this->invoicemodel->fetch_companydropdowndatas($txtdropdowndisdeal);
		
		
		
		$final_arr			= array("dropdowndatas" => $getallentries_inner);
		
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($final_arr));
		
		
		
	}

	public function fetch_txtdropdowndisdealdatas(){
		
		$txtpicompany	= $this->input->post('txtpicompany');
		$user_type 		 		= $this->session->userdata('user_type');
		$getallentries_inner 	= $this->invoicemodel->fetch_txtdropdowndisdealdatas($txtpicompany,$user_type);
		
		
		
		$getallentries_inner_str = '';
		
		if($user_type=="1"){
			$getallentries_inner_str .= '<option value="">--Choose Customers--</option>';
			
			
			
			foreach($getallentries_inner as $row) {
				$getallentries_inner_str .= '<option value="'.$row->c_customer_id.'">'.$row->c_customer_name.'</option>';
			}
		}
		else if($user_type=="0" or $user_type=="4"){
			$getallentries_inner_str .= '<option value="">--Choose Distributors--</option>';
			
			foreach($getallentries_inner as $row) {
				$getallentries_inner_str .= '<option value="'.$row->user_id.'">'.$row->user_name.'</option>';
			}
			
		}
		else if($user_type=="2"){
			$getallentries_inner_str .= '<option value="">--Choose Dealers--</option>';
			
			foreach($getallentries_inner as $row) {
				$getallentries_inner_str .= '<option value="'.$row->user_id.'">'.$row->user_name.'</option>';
			}
		}
		
		
		
		$final_arr			= array("dropdowndatas" => $getallentries_inner_str);
		
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($final_arr));
		
	}
	
	public function fetch_productsaddmoredatas(){
	
		$hideditId		 = $this->input->post('hideditId');
		$type			 = $this->input->post('type');
		$rowcount		 = $this->input->post('rowcount');
		$txtpicompany	 = $this->input->post('txtpicompany');
		
		$getdatas_rawdata = array();
		
		$getallentries_products 	= $this->invoicemodel->getall_entries_products($txtpicompany);
		
		$getallentries_products_str = '<option value="">--Products--</option>';
		
		foreach($getallentries_products as $row) {
			$getallentries_products_str .= '<option value="'.$row->p_product_id.'">'.$row->p_product_name.'</option>';
		}
		
		switch($type){
			
			case "addmore":
			
					$hidcountrows	= $rowcount;
					
					$addmoredatas	= '<tr rowid="'.$hidcountrows.'"><td><select class="form-control show-tick" name="txtaddproduct_'.$hidcountrows.'" id="txtaddproduct_'.$hidcountrows.'" data-live-search="true" onchange="javascript:onchproductprice('.$hidcountrows.');">'.$getallentries_products_str.'</select><span class="help-block errortext" id="txtaddproducterror_'.$hidcountrows.'"></span></td><td><input type="text" class="form-control" name="txtaddqty_'.$hidcountrows.'" id="txtaddqty_'.$hidcountrows.'" size="1" onkeypress="return isNumberKey(event)" onblur="javascript:calculationprice('.$hidcountrows.');"><span class="help-block errortext" id="txtaddqtyerror_'.$hidcountrows.'"></span></td><td><input type="text" class="form-control" name="txtaddunitprice_'.$hidcountrows.'" id="txtaddunitprice_'.$hidcountrows.'" size="3" onkeypress="return isNumberKey(event)" readonly onblur="javascript:calculationprice('.$hidcountrows.');"><span class="help-block errortext" id="txtaddunitpriceerror_'.$hidcountrows.'"></span></td><td><input type="text" class="form-control" name="txtaddofferprice_'.$hidcountrows.'" id="txtaddofferprice_'.$hidcountrows.'" size="3" onkeypress="return isNumberKey(event)" onblur="javascript:calculationprice('.$hidcountrows.');"><span class="help-block errortext" id="txtaddofferpriceerror_'.$hidcountrows.'"></span></td><td><input type="text" class="form-control" name="txtaddrate_'.$hidcountrows.'" id="txtaddrate_'.$hidcountrows.'" size="3" onkeypress="return isNumberKey(event)" readonly><span class="help-block errortext" id="txtaddrateerror_'.$hidcountrows.'"></span></td><td><a href="javascript:void();" onclick="delete_addmore(this,0)"><span class="glyphicon glyphicon-minus-sign" style="color:red;"></span></a></td></tr>';
			
			break;
			
			case "fetch":
			
				if($hideditId==""){
					
					$hidcountrows	= 1;
					
					$addmoredatas	= '<tr rowid="'.$hidcountrows.'"><td><select class="form-control show-tick" name="txtaddproduct_'.$hidcountrows.'" id="txtaddproduct_'.$hidcountrows.'" data-live-search="true" onchange="javascript:onchproductprice('.$hidcountrows.');">'.$getallentries_products_str.'</select><span class="help-block errortext" id="txtaddproducterror_'.$hidcountrows.'"></span></td><td><input type="text" class="form-control" name="txtaddqty_'.$hidcountrows.'" id="txtaddqty_'.$hidcountrows.'" size="1" onkeypress="return isNumberKey(event)" onblur="javascript:calculationprice('.$hidcountrows.');"><span class="help-block errortext" id="txtaddqtyerror_'.$hidcountrows.'"></span></td><td><input type="text" class="form-control" name="txtaddunitprice_'.$hidcountrows.'" id="txtaddunitprice_'.$hidcountrows.'" size="3" onkeypress="return isNumberKey(event)" readonly onblur="javascript:calculationprice('.$hidcountrows.');"><span class="help-block errortext" id="txtaddunitpriceerror_'.$hidcountrows.'"></span></td><td><input type="text" class="form-control" name="txtaddofferprice_'.$hidcountrows.'" id="txtaddofferprice_'.$hidcountrows.'" size="3" onkeypress="return isNumberKey(event)" onblur="javascript:calculationprice('.$hidcountrows.');"><span class="help-block errortext" id="txtaddofferpriceerror_'.$hidcountrows.'"></span></td><td><input type="text" class="form-control" name="txtaddrate_'.$hidcountrows.'" id="txtaddrate_'.$hidcountrows.'" size="3" onkeypress="return isNumberKey(event)" readonly><span class="help-block errortext" id="txtaddrateerror_'.$hidcountrows.'"></span></td><td><a href="javascript:void();" onclick="delete_addmore(this,0)"><span class="glyphicon glyphicon-minus-sign" style="color:red;"></span></a></td></tr>';
					
						
				}
				
			break;		
			
		}

		$final_arr			= array("hidcountrows" => $hidcountrows, "addmoredatas" => $addmoredatas, "getdatas_rawdata" => $getdatas_rawdata );
		
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($final_arr));	
	
	}	
	
	public function proforma_invoice_list(){
		
		
		
		
		
		$_SESSION['currentActivePage'] = 'Create_Cerificate';
		
		$user_company_id = $this->session->userdata('user_company_id');
		$user_type 		 = $this->session->userdata('user_type');
		$user_id		 = $this->session->userdata('user_id');
		
		$data['message'] = '';	
		
		$getallentries_datas 	= $this->invoicemodel->getall_entries_proforma_invoice($user_company_id,$user_type,$user_id);
		
		$data['getallentries_datas'] = $getallentries_datas;
		
		$this->load->view('masters/proforma_invoice_list',$data);		
		
	}

	public function fetch_productdatas(){
	
		$p_product_id			= $this->input->post('p_product_id');
		
		$getallentries_datas 	= $this->invoicemodel->fetch_productdatas($p_product_id);
		
		$final_arr			= array("getdatas" => $getallentries_datas);
		
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($final_arr));
		
	
	}
		
	public function sendmail_proformainvoice(){
		
		$txtmodalemail			= $this->input->post('txtmodalemail');
		$modalhidid				= $this->input->post('modalhidid');
		
		//$getallentries_datas 	= $this->adminmodel->fetch_proformainvoicedatas($modalhidid);
		
			require APPPATH.'libraries/PHPMailer/PHPMailerAutoload.php';
			
			$datas_invoice = $this->invoicemodel->fetch_proformainvoicedatas($modalhidid);
			
			$filnamepdf = $datas_invoice->invoice_file;		
		
		
			$mail = new PHPMailer(true);

			$mail->SMTPDebug = 0;  
			$mail->isSMTP();
			
			$mail->Host = "smtp.sendgrid.net"; 

			$mail->SMTPAuth = true;                               
			$mail->Username = 'psdnprabu';                 
			$mail->Password = 'psdn@1234';                           
			$mail->SMTPSecure = 'none';                            
			$mail->Port = 25;
			
			//$to	= "kathiresan.softengg@gmail.com";
			$to = $txtmodalemail;
			
			$mailbody = "Dear Sir/Madam,<br>we have attached proforma invoice. kindly review it.<br><br><b>Regards,</b><BR>PSDN Technology Pvt Ltd.";
			$mailsubject = 'Proforma Invoice';
			$mail->setFrom('sales@psdn.live', 'PSDN');
			$mail->addAddress($txtmodalemail,$txtmodalemail);
		  
			$mail->isHTML(true);
			$messageparam  = $mailbody;
			$mail->Subject = $mailsubject;
			$mail->Body    = $mailbody;
			
			$mail->AddAttachment('invoice/'.$filnamepdf);
			
			$mail->send();
			
			
		
	}	
	
}

