<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Admin extends CI_Controller {

	

	// Load Constructur

	public function __construct() {

        parent::__construct();

		$this->load->model('adminmodel');

		$this->load->model('commonmodel');

		$this->load->library("pagination");

		$this->load->library('session');

		

    }

	

	public function email()

	{

		require APPPATH.'\libraries\PHPMailer\PHPMailerAutoload.php';

		$mail = new PHPMailer(true);

		$mail->IsSMTP();

		$mail->Host = SMTP_HOST; 

		$mail->SMTPDebug = 2; 

		$mail->SMTPSecure = 'ssl'; //<----------------- You missed this 

		$mail->SMTPAuth = true; 

		$mail->Host = SMTP_HOST; 

		$mail->Port = SMTP_PORT; // 

		$mail->Username = SMTP_USERNAME;

		$mail->Password = SMTP_PASSWORD;

		$mail->AddAddress('senthily88@gmail.com', 'John Doe');

		$mail->SetFrom('senthily88@gmail.com', 'First Last');

		$mail->Subject = 'This is a TEST message';

		$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';

		$body = "To view the message, please use an HTML compatible email viewer!"; // automatically

		$mail->MsgHTML($body);

		$mail->Send();

		echo "Message Sent OK</p>\n";

	}

	

	public function pdf1()

	{

		

		$this->commonmodel->saveQrCode('Qrcode test');

	}



	public function reset_password()

	{

		$params=$this->input->post();

		$userinfo=$this->commonmodel->getUserInfo($params);	

		$response=array();

		if(empty($userinfo))

		{

			$response['error']='Invalid mail address, Please try again.';

		}else{

			$randomPwd=random_string('alnum', 6);

		 	require APPPATH.'\libraries\PHPMailer\PHPMailerAutoload.php';

			$mail = new PHPMailer(true);

			$mail->IsSMTP();

			$mail->Host = SMTP_HOST; 

			//$mail->SMTPDebug = 2; 

			$mail->SMTPSecure = 'ssl'; //<----------------- You missed this 

			$mail->SMTPAuth = true; 

			$mail->Host = SMTP_HOST; 

			$mail->Port = SMTP_PORT; // 

			$mail->Username = SMTP_USERNAME;

			$mail->Password = SMTP_PASSWORD;

			$mail->AddAddress($params['email'], $userinfo['user_name']);

			$mail->SetFrom(SMTP_MAIL_FROM,SMTP_MAIL_FROM_NAME);

			$mail->Subject = 'Reset Password';			

			$body = "Password has been reseted successsfully, <br/> New Password is : ".$randomPwd; // automatically

			$mail->MsgHTML($body);

			$mail->Send();

			$response['success']=true;

			$response['message']='Reset Password has been send your mail address, Kindly check it.';

			$updateInfo=array();

			$updateInfo['user_password']=md5($randomPwd);

			$updateInfo['user_id']=$userinfo['user_id'];

			$userinfo=$this->commonmodel->updateUserInfo($updateInfo);	

		}

		echo json_encode($response);

	}

	

	public function pdf11()

	{

		$data['title']=true;

		$html=$this->load->view('tcpdf1',$data,true);



		

		echo $html;exit();

	

	}



	public function pdf()

	{

		$data['title']=true;

		$html=file_get_contents('http://localhost/speed/admin/pdf11');//$this->load->view('tcpdf1',$data,true);



		

		//echo $html;exit();

		$filename='abcd';

		

		

		require_once(APPPATH . '/libraries/pdf/config/lang/eng.php');

        require_once(APPPATH . '/libraries/pdf/tcpdf.php');

		$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        

        

        // set document information

        $pdf->SetCreator('TCPDF');

        // set header and footer fonts

        $pdf->setHeaderFont(Array(

            'helvetica',

            '',

            10

        ));

        $pdf->setFooterFont(Array(

          'helvetica',

            '',

            8

        ));

        // set default monospaced font

        $pdf->SetDefaultMonospacedFont('courier');

        //set margins

        //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        //$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);

        //$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        //set auto page breaks

        $pdf->SetAutoPageBreak(TRUE, 25);

        //set image scale factor

        $pdf->setImageScale(1.25);

        //set some language-dependent strings

        $pdf->setLanguageArray($l);

        // ---------------------------------------------------------

        // set font

        //$pdf->SetFont('helvetica', '', 10);        

        $pdf->SetPrintHeader(false);

        $pdf->SetPrintFooter(false);

        $pdf->AddPage();        

        //$pdf->SetFont('helvetica', '', 8);

        

        # To support arabic fonts

        $pdf->SetFont('ufontscom_aealarabiya', '', 10);

        

        $pdf->writeHTML($html, true, false, false, false, '');

        // add a page

        // output the HTML content

        // reset pointer to the last page

        //Close and output PDF document

        ob_end_clean();

        $pdf->Output($filename . '.pdf', 'D');



	}

	

	public function pdf_qr_code()

	{
	
	//*******
	
	if(isset($_GET['id'])){

		$id=base64_decode(base64_decode(base64_decode($_GET['id'])));
		
		$get_info = $this->commonmodel->getPdfVehicleInfo($id);
		$invoice_date = date('d-m-Y',strtotime($userinfo['veh_create_date']));
	}  
	//******* ", Chassis No:".$get_info['veh_chassis_no'].", Engine No:".$get_info['veh_engine_no'].", Vehicle Make:".$get_info['v_make_name'].", Vehicle Model:".$get_info['ve_model_name'].SLD No:".$get_info['s_serial_number'].", TAC No:".$get_info['veh_tac'].", Set Speed".$get_info['veh_speed'].", Invoice Date:".$invoice_date."
		
		$pdf_qr_code="Owner Name:".$get_info['veh_owner_name'].", Reg No:".$get_info['veh_rc_no'].", Chassis No:".$get_info['veh_chassis_no'].", Download URL: ".base_url()."admin/downloadwebpdf?id=".$_GET['id'];

		$this->commonmodel->qrcode($pdf_qr_code);

	}

    
	
	public function downloadRenewalwebpdf(){		

		$params=$this->input->get();

		if(!isset($params['id'])) {

			 redirect(base_url().'admin/dashboard', 'refresh');
			 exit();
		}

		$id=base64_decode(base64_decode(base64_decode($params['id'])));
		$encodeID=base64_encode(base64_encode(base64_encode($id)));

		if(!is_numeric($id))
		{
			redirect(base_url().'admin/dashboard', 'refresh');
			 exit();	
		}

		$data['userinfo']=$this->commonmodel->getPdfRenewalVehicleInfo($id);			
		$data['userinfo']['qrcodeimg']=base_url()."admin/renewal_pdf_qr_code/?id=".$encodeID;

		$this->load->view("RenewalwebPDF",$data);		
	    }
    
	public function renewal_pdf_qr_code(){

		$pdf_qr_code=base_url()."admin/downloadRenewalwebpdf?id=".$_GET['id'];
		$this->commonmodel->qrcode($pdf_qr_code);
	}
	
	
	

	public function downloadwebpdf()

	{		

		$params=$this->input->get();

		if(!isset($params['id']))

		{

			 redirect(base_url().'admin/dashboard', 'refresh');

			 exit();

		}



		$id=base64_decode(base64_decode(base64_decode($params['id'])));

		//echo $id;exit();

		$encodeID=base64_encode(base64_encode(base64_encode($id)));

		if(!is_numeric($id))

		{

			redirect(base_url().'admin/dashboard', 'refresh');

			 exit();	

		}

		$data['userinfo']=$this->commonmodel->getPdfVehicleInfo($id);			

		$data['userinfo']['qrcodeimg']=base_url()."admin/pdf_qr_code/?id=".$encodeID;

		

		$this->load->view("webPDF",$data);		

	}







	public function downloadpdf($id)

	{		

		$data['userinfo']=$this->commonmodel->getPdfVehicleInfo($id);			

		$data['userinfo']['qrcodeimg']=base_url()."admin/pdf_qr_code/".$id;

		

		$html=$this->load->view("vehiclePDF",$data,true);

		//echo $html;exit();

		$filename=date('Y_m_d_H_i_s');

		

		require_once(APPPATH . '/libraries/pdf/config/lang/eng.php');

        require_once(APPPATH . '/libraries/pdf/tcpdf.php');

		$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        

        // set document information

        $pdf->SetCreator('TCPDF');

        // set header and footer fonts

        $pdf->setHeaderFont(Array(

            'helvetica',

            '',

            10

        ));

        $pdf->setFooterFont(Array(

          'helvetica',

            '',

            8

        ));

        // set default monospaced font

        $pdf->SetDefaultMonospacedFont('courier');

        //set margins

       // $pdf->SetMargins(1, 1, 1,1);

        $pdf->SetHeaderMargin(1);

        //$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        //set auto page breaks

        $pdf->SetAutoPageBreak(TRUE, 25);

        //set image scale factor

        $pdf->setImageScale(1.25);

        //set some language-dependent strings

        $pdf->setLanguageArray($l);

        // ---------------------------------------------------------

        // set font

        //$pdf->SetFont('helvetica', '', 10);        

        $pdf->SetPrintHeader(false);

		$pdf->SetPrintFooter(false);     

        $pdf->AddPage();   

      

        //$pdf->SetFont('helvetica', '', 8);

        

        # To support arabic fonts

        $pdf->SetFont('ufontscom_aealarabiya', '', 10);

        

        $pdf->writeHTML($html, true, false, false, false, '');

        // add a page

        // output the HTML content

        // reset pointer to the last page

        //Close and output PDF document

        ob_end_clean();

        $pdf->Output($filename . '.pdf', 'D');

        ob_end_flush();

	}









	public function sendAttachementEmail($id)

	{		

		$params=$this->input->post();

		$data['userinfo']=$this->commonmodel->getPdfVehicleInfo($id);			

		$data['userinfo']['qrcodeimg']=base_url()."admin/pdf_qr_code/".$id;

		$html=$this->load->view("vehiclePDF",$data,true);

	

		$filename="public/temp_upload/".date('Y_m_d_H_i_s').".pdf";

		

		require_once(APPPATH . '/libraries/pdf/config/lang/eng.php');

        require_once(APPPATH . '/libraries/pdf/tcpdf.php');

		$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        

        // set document information

        $pdf->SetCreator('TCPDF');

        // set header and footer fonts

        $pdf->setHeaderFont(Array(

            'helvetica',

            '',

            10

        ));

        $pdf->setFooterFont(Array(

          'helvetica',

            '',

            8

        ));

        // set default monospaced font

        $pdf->SetDefaultMonospacedFont('courier');

        //set margins

       // $pdf->SetMargins(1, 1, 1,1);

        $pdf->SetHeaderMargin(1);

        //$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        //set auto page breaks

        $pdf->SetAutoPageBreak(TRUE, 25);

        //set image scale factor

        $pdf->setImageScale(1.25);

        //set some language-dependent strings

        $pdf->setLanguageArray($l);

        // ---------------------------------------------------------

        // set font

        //$pdf->SetFont('helvetica', '', 10);        

        $pdf->SetPrintHeader(false);

		$pdf->SetPrintFooter(false);     

        $pdf->AddPage();   

      

        //$pdf->SetFont('helvetica', '', 8);

        

        # To support arabic fonts

        $pdf->SetFont('ufontscom_aealarabiya', '', 10);

        

        $pdf->writeHTML($html, true, false, false, false, '');

        // add a page

        // output the HTML content

        // reset pointer to the last page

        //Close and output PDF document

        ob_start();

        ob_end_clean();

        $pdf->Output($filename, 'F');

        ob_end_flush();



        require APPPATH.'/libraries/PHPMailer/PHPMailerAutoload.php';

		$mail = new PHPMailer(true);

		$mail->IsSMTP();

		$mail->Host = "smtp.gmail.com"; 

		//$mail->SMTPDebug = 2; 

		$mail->SMTPSecure = 'ssl'; //<----------------- You missed this 

		$mail->SMTPAuth = true; 

		$mail->Host = SMTP_HOST; 

		$mail->Port = SMTP_PORT; // 

		$mail->Username = SMTP_USERNAME;

		$mail->Password = SMTP_PASSWORD;

		$mail->AddAddress($params['email'], 'Dealer');

		$mail->SetFrom(SMTP_MAIL_FROM, SMTP_MAIL_FROM_NAME);

		$mail->addAttachment($filename); 

		$mail->Subject = 'Universal Tele Services';

		$mail->AltBody = 'Download the Universal Tele Services Cerificate';

		$body = "Download the  Universal Tele Services Cerificate"; // automatically

		$mail->MsgHTML($body);

		$mail->Send();

		echo 1;

		unlink($filename);



	}







	// Initialize Function

	public function index()

	{			

		$user_type=$this->session->userdata('user_type');

		if(isset($user_type) && (string)$user_type==='0')

		{

			 redirect(base_url().'admin/dashboard', 'refresh');

			 exit();

		}

		

		$this->load->view('admin/login');

	}

	

	public function logout()

	{

		$this->session->sess_destroy();

		redirect(base_url(), 'refresh');

		exit();

	}

		

	

	public function edit_profile()

	{

		$user_type=$this->session->userdata('user_type');

		if(!isset($user_type) && (string)$user_type==='')

		{

			 redirect(base_url(), 'refresh');

			 exit();

		}

		$dealerID=$this->session->userdata('user_id');		

		$data['userinfo']=$this->commonmodel->getDealerInfo($dealerID);		

		if(empty($data['userinfo']))

		{

			  redirect(base_url().'admin/dashboard', 'refresh');

		}

		$data['userinfo']['user_id']=base64_encode($dealerID);		

		$data['pageTitle']='Edit Profile';

		// Load Content

		$this->load->view('masters/edit_profile',$data);

	}

	

	public function forgot_password()

	{		

		$this->load->view('admin/forgot_password');

	}

	// Verify user Records

	public function verifyuser() {

		

		$params=$this->input->post();

		

		$returnResponse=array();	

		$returnResponse['validation']=array();

		$returnResponse['error']="";

		$returnResponse['success']="";

		// Validation

		$this->form_validation->set_rules('phone_number', 'Phone Number', 'required');

		$this->form_validation->set_rules('password_value', 'Password', 'required');

		// Validation verify

		if ($this->form_validation->run() == FALSE)

        {

			 $returnResponse['validation']=$this->form_validation->error_array();             

			 echo json_encode($returnResponse);exit();

        }

		//Pass params to Model

		$response=$this->adminmodel->verifyuser($params);

        if(empty($response))

		{

			$returnResponse['error']="Please Enter valid Credentials.";

			echo json_encode($returnResponse);exit();

		}			 

		if(isset($response['user_password']))

		{

			unset($response['user_password']);

		}

		// Set Session

		$this->session->set_userdata($response);		

		$returnResponse['success']=true;



		$returnResponse['redirect']='admin/dashboard';

		echo json_encode($returnResponse);exit();

	}



	// Verify user Records

	public function verifyrto() {

		

		$params=$this->input->post();

		

		$returnResponse=array();	

		$returnResponse['validation']=array();

		$returnResponse['error']="";

		$returnResponse['success']="";

		// Validation

		$this->form_validation->set_rules('rto_number', 'Rto Number', 'required');

		$this->form_validation->set_rules('rto_pwd', 'rto_pwd', 'required');

		// Validation verify

		if ($this->form_validation->run() == FALSE)

        {

			 $returnResponse['validation']=$this->form_validation->error_array();             

			 echo json_encode($returnResponse);exit();

        }

		//Pass params to Model

		$response=$this->adminmodel->verifyrto($params);

        if(empty($response))

		{

			$returnResponse['error']="Please Enter valid Credentials.";

			echo json_encode($returnResponse);exit();

		}		

		//print_r($response);exit();	 		

		// Set Session

		$this->session->set_userdata($response);		

		$returnResponse['success']=true;



		$returnResponse['redirect']='rto/rto_dashboard';

		echo json_encode($returnResponse);exit();

	}

	

	// Initialize Function

	public function dashboard()

	{	

		$user_type=$this->session->userdata('user_type');

		if(!isset($user_type) && (string)$user_type==='')

		{

			 redirect(base_url(), 'refresh');

			 exit();

		}		

		$user_type=$this->session->userdata('user_type');

		if(isset($user_type) && (string)$user_type==='1')

		{

			 redirect(base_url().'dealer/create_new_entry', 'refresh');

			 exit();

		}

		if(isset($user_type) && (string)$user_type==='2')

		{

			 redirect(base_url().'dealer/create_new_entry', 'refresh');

			 exit();

		}

		$data['countList']=$this->commonmodel->getNoOfCount();

		$_SESSION['currentActivePage']='Home';			

		// Load Content

		$this->load->view('admin/dashboard',$data);			

	}



	// Initialize Function

	public function rto_dashboard()

	{	

		$rtoNo=$this->session->userdata('rto_no');

		if(!isset($rtoNo) || strlen($rtoNo)===0)

		{

			 redirect(base_url(), 'refresh');

			 exit();

		}	

		$selectedReportDate=isset($_GET['start_date'])?$_GET['start_date']:date('Y-m-d');

		$data['totalNoOfVehicles']=$this->commonmodel->totalNoOflistofTodayEntrys($selectedReportDate,$rtoNo);	

		$data['listofvehicles']=$this->commonmodel->listofTodayEntrys($selectedReportDate,$rtoNo);	

		$data['selectedReportDate']=$selectedReportDate;		

		$this->load->view('rto/rto_dashboard',$data);			

	}

	

	

	// Agents

	public function create_new_users()

	{	

		$user_type=$this->session->userdata('user_type');

		if(!isset($user_type) && (string)$user_type==='')

		{

			 redirect(base_url(), 'refresh');

			 exit();

		}

		$data['rto_list']=$this->commonmodel->allRtoNumbers();		

		$data['company_list']=$this->commonmodel->allCompanyList();		

		$this->session->set_userdata('currentActivePage','Create_Users');

		// Load Content

		$this->load->view('masters/create_dealer',$data);		

	}

		public function removeWhiteSpace()

	{

		$this->commonmodel->removeWhiteSpace();

	}





	// Admin Create New Company

	public function create_company()

	{	

		$user_type=$this->session->userdata('user_type');

		if((!isset($user_type) && (string)$user_type==='') ||  (string)$user_type !='0')

		{

			 redirect(base_url(), 'refresh');

			 exit();

		}

		$this->session->set_userdata('currentActivePage','Create_Company');

		// Load Content

		$this->load->view('masters/create_company',$data);		

	}

	

	// Admin Create New Company

	public function create_vehicle_make()

	{	

		$user_type=$this->session->userdata('user_type');

		if((!isset($user_type) && (string)$user_type==='') ||  (string)$user_type !='0')

		{

			 redirect(base_url(), 'refresh');

			 exit();

		}

		$this->session->set_userdata('currentActivePage','Create_Vehicle_Make');

		// Load Content

		$this->load->view('masters/create_vehicle_make',$data);		

	}





	// Admin Create New Company

	public function create_rto()

	{	

		$user_type=$this->session->userdata('user_type');

		if((!isset($user_type) && (string)$user_type==='') ||  (string)$user_type !='0')

		{

			 redirect(base_url(), 'refresh');

			 exit();

		}

		$this->session->set_userdata('currentActivePage','Create_RTO_Number');

		// Load Content

		$this->load->view('masters/create_rto_number',$data);		

	}



	// Create New Vehicle Make Records

	public function create_rto_records()

	{	

		$params=$this->input->post();				

		$returnResponse=array();	

		$returnResponse['validation']=array();

		$returnResponse['error']="";

		$returnResponse['success']="";

		// Validation

		$this->form_validation->set_rules('rto_number', 'RTO Number', 'trim|required');

		$this->form_validation->set_rules('rto_place', 'RTO Place', 'trim|required');

				

		$this->form_validation->set_rules(

				'rto_number', 'RTO Number',

				array(

						'required',

						array(

								'already_exits',

								function($str)

								{

										return $this->commonmodel->verify_exits_rto_number($str);

								}

						)

				)

		);		

				

		

		// Validation verify

		if ($this->form_validation->run() == FALSE)

        {        	

			 $returnResponse['validation']=$this->form_validation->error_array();             

			 echo json_encode($returnResponse);exit();

        }

		//Pass params to Model

		//$params['v_created_by']=$this->session->userdata('user_id');	

		$response=$this->adminmodel->create_new_rto_records($params);

        if(empty($response))

		{

			$returnResponse['error']="Please Enter valid Details.";

			echo json_encode($returnResponse);exit();

		}			 

	

		// Set Session	

		$returnResponse['success']=true;

		$returnResponse['redirect']='admin/rto_list';

		$returnResponse['message']='Rto Number created successfully.';

		echo json_encode($returnResponse);exit();	

	}

	





	public function rto_list()

	{	

		$user_type=$this->session->userdata('user_type');

		if((!isset($user_type) && (string)$user_type==='') ||  (string)$user_type !='0')

		{

			 redirect(base_url(), 'refresh');

			 exit();

		}

		$_SESSION['currentActivePage']='RTO_List';



		$limit=LIST_PAGE_LIMIT;					

		$offset=isset($_GET['offset'])?$_GET['offset']:0;

		if($offset !=0)

		{

			$offset=((int)$limit * (int)$offset)-(int)$limit;

		}

		$search=isset($_GET['search'])?$_GET['search']:'';		

		$data['totalNoOfRTO']=$this->commonmodel->totalNoOfRTO();				

		$data['listofRTONumbers']=$this->commonmodel->listofRtoList($limit,$offset,$search);				

		//print_r($data['listofRTONumbers']);exit();

		$this->load->view('masters/rto_list',$data);	

	}





	public function edit_rto_number($rtoNo)

	{

		$user_type=$this->session->userdata('user_type');

		if((!isset($user_type) && (string)$user_type==='') || (string)$user_type !='0')

		{

			 redirect(base_url(), 'refresh');

			 exit();

		}

		if(!isset($rtoNo))

		{

			  redirect(base_url().'admin/dashboard', 'refresh');

		}

		$data['RToInfo']=$this->commonmodel->getRtoInfo($rtoNo);		

		if(empty($data['RToInfo']))

		{

			 redirect(base_url().'admin/dashboard', 'refresh');

		}

		$data['RToInfo']['rto_no']=base64_encode($rtoNo);

		$data['pageTitle']='Edit RTO Number';		

		$_SESSION['currentActivePage']='RTO_List';		

		// Load Content		

		$this->load->view('masters/edit_rto_number',$data);		

	}

	

	public function update_rto_records()

	{

		$params=$this->input->post();	

		$params['rto_no']=base64_decode($params['rto_no']);

		//print_r($params);exit();

		$returnResponse=array();	

		$returnResponse['validation']=array();

		$returnResponse['error']="";

		$returnResponse['success']="";

		// Validation

		$this->form_validation->set_rules('rto_number', 'RTO Number', 'trim|required');

		$this->form_validation->set_rules('rto_place', 'RTO Place', 'trim|required');

		$this->form_validation->set_rules('rto_no', 'RTO Number', 'trim|required');

				

		$this->form_validation->set_rules(

				'rto_number', 'RTO Number',

				array(

						'required',

						array(

								'already_exits',

								function($str)

								{

										$arg=$this->input->post();	

										$arg['rto_no']=base64_decode($arg['rto_no']);

										return $this->commonmodel->verify_exits_rto_number($str,$arg['rto_no']);

								}

						)

				)

		);		

			

		

		// Validation verify

		if ($this->form_validation->run() == FALSE)

        {

			 $returnResponse['validation']=$this->form_validation->error_array();             

			 echo json_encode($returnResponse);exit();

        }	

		//print_r($params);exit();

		//Pass params to Model

		$response=$this->adminmodel->modify_rto_records($params);

        if(empty($response))

		{

			$returnResponse['error']="Please Enter valid Credentials.";

			echo json_encode($returnResponse);exit();

		}			 		

		// Set Session

		$this->session->set_userdata($response);		

		$returnResponse['success']=true;

		$returnResponse['redirect']='admin/rto_list';

		$returnResponse['message']='RTO Records has been updated successfully.';

		echo json_encode($returnResponse);exit();

	}

	

	





	// Agents

	public function create_new_dealer_records()

	{	

		$params=$this->input->post();					

		if(!isset($params['user_distributor_id']))

		{

		$params['user_distributor_id']=0;	

		}

		$returnResponse=array();	

		$returnResponse['validation']=array();

		$returnResponse['error']="";

		$returnResponse['success']="";

		// Validation

		$this->form_validation->set_rules('name', 'Name', 'trim|required');

		$this->form_validation->set_rules('phone', 'Phone', 'trim|required');

		$this->form_validation->set_rules('user_company_id', 'Company ID', 'trim|required');

		$this->form_validation->set_rules('gender', 'Gender', 'trim|required');

		$this->form_validation->set_rules('password', 'Password', 'trim|required');

		$this->form_validation->set_rules('user_rto', 'Rto Number', 'trim|required');

		$this->form_validation->set_rules('user_type', 'User Type', 'trim|required');

		$this->form_validation->set_rules('description', 'Address', 'trim|required');

		

		$this->form_validation->set_rules(

				'phone', 'Phone',

				array(

						'required',

						array(

								'phone_no_already_exits',

								function($str)

								{

										return $this->commonmodel->verify_exits_dealer_phone_number($str);

								}

						)

				)

		);

		if(isset($params['email']) && strlen($params['email'])>0)

		{

			$this->form_validation->set_rules(

					'email', 'Email',

					array(

							'required',

							array(

									'email_no_already_exits',

									function($str)

									{

											return $this->commonmodel->verify_exits_dealer_email($str);

									}

							)

					)

			);		

		}

		// Validation verify

		if ($this->form_validation->run() == FALSE)

        {

			 $returnResponse['validation']=$this->form_validation->error_array();             

			 echo json_encode($returnResponse);exit();

        }

		// Rename Profile Photo

		if(isset($params['profile_photo']) && strlen($params['profile_photo'])>0)

		{

			if (strpos($params['profile_photo'], 'temp_upload') !== false) {

				$profile_photo = str_replace('public/temp_upload/', 'public/upload/users/', $params['profile_photo']);

				rename($params['profile_photo'], $profile_photo);

				$params['profile_photo']=$profile_photo;

			}

		}

		//Pass params to Model

		$response=$this->adminmodel->create_new_dealer_records($params);

        if(empty($response))

		{

			$returnResponse['error']="Please Enter valid Credentials.";

			echo json_encode($returnResponse);exit();

		}			 

		if(isset($response['user_password']))

		{

			unset($response['user_password']);

		}

		// Set Session	

		$returnResponse['success']=true;

		$returnResponse['redirect']='admin/users_list';

		$returnResponse['message']='Users has been created successfully.';

		echo json_encode($returnResponse);exit();	

	}





	// Agents

	public function create_new_vehicle_model_records()

	{	

		$params=$this->input->post();				

		$returnResponse=array();	

		$returnResponse['validation']=array();

		$returnResponse['error']="";

		$returnResponse['success']="";

		// Validation

		$this->form_validation->set_rules('ve_make_id', 'Vehicle Make', 'trim|required');

		$this->form_validation->set_rules('ve_model_name', 'Vehicle Model', 'trim|required');

		

		$this->form_validation->set_rules(

				've_model_name', 'Vehicle Model',

				array(

						'required',

						array(

								'already_exits',

								function($str)

								{

										$arg=$this->input->post();	

										return $this->commonmodel->verify_exits_model_make_records($str,$arg['ve_make_id']);

								}

						)

				)

		);

		

		// Validation verify

		if ($this->form_validation->run() == FALSE)

        {

			 $returnResponse['validation']=$this->form_validation->error_array();             

			 echo json_encode($returnResponse);exit();

        }

		$params['ve_created_by']=$this->session->userdata('user_id');	

		$response=$this->adminmodel->create_new_vehicle_model_records($params);

        if(empty($response))

		{

			$returnResponse['error']="Please Enter valid Credentials.";

			echo json_encode($returnResponse);exit();

		}			 

		

		// Set Session	

		$returnResponse['success']=true;

		$returnResponse['redirect']='admin/vehicle_model_list';

		$returnResponse['message']='Model has been created successfully.';

		echo json_encode($returnResponse);exit();	

	}







	// Create New Company Records

	public function create_new_company_records()

	{	

		$params=$this->input->post();				

		$returnResponse=array();	

		$returnResponse['validation']=array();

		$returnResponse['error']="";

		$returnResponse['success']="";

		// Validation

		$this->form_validation->set_rules('c_company_name', 'Company Name', 'trim|required');

		$this->form_validation->set_rules('c_cop_validity', 'Cop Validity', 'trim|required');

		

		

		$this->form_validation->set_rules(

				'c_company_name', 'Company Name',

				array(

						'required',

						array(

								'already_exits',

								function($str)

								{

										return $this->commonmodel->verify_exits_company_name($str);

								}

						)

				)

		);		

		$this->form_validation->set_rules(

					'c_tac_no[0]', 'Tac Number',

					array(

							'required',

							array(

									'already_exits',

									function($str)

									{

										   $c_tac_no=$this->input->post('c_tac_no');		

										   $resultArray=$this->commonmodel->verify_exits_company_tac_number($c_tac_no);		

										   if(empty($resultArray))

										   {

										   	return true;

										   }

										   return false;

									}

							)

					)

		);				

		

		// Validation verify

		if ($this->form_validation->run() == FALSE)

        {        	

			 $returnResponse['validation']=$this->form_validation->error_array();             

			 echo json_encode($returnResponse);exit();

        }

		//Pass params to Model

		$params['c_created_by']=$this->session->userdata('user_id');	

		$response=$this->adminmodel->create_new_company_records($params);

        if(empty($response))

		{

			$returnResponse['error']="Please Enter valid Details.";

			echo json_encode($returnResponse);exit();

		}			 

	

		// Set Session	

		$returnResponse['success']=true;

		$returnResponse['redirect']='admin/company_list';

		$returnResponse['message']='New Company Entry has been created successfully.';

		echo json_encode($returnResponse);exit();	

	}



	// Create New Vehicle Make Records

	public function create_new_vehicle_make_records()

	{	

		$params=$this->input->post();				

		$returnResponse=array();	

		$returnResponse['validation']=array();

		$returnResponse['error']="";

		$returnResponse['success']="";

		// Validation

		$this->form_validation->set_rules('v_vehicle_make', 'Make Name', 'trim|required');

				

		$this->form_validation->set_rules(

				'v_vehicle_make', 'Make Name',

				array(

						'required',

						array(

								'already_exits',

								function($str)

								{

										return $this->commonmodel->verify_exits_make_name($str);

								}

						)

				)

		);		

				

		

		// Validation verify

		if ($this->form_validation->run() == FALSE)

        {        	

			 $returnResponse['validation']=$this->form_validation->error_array();             

			 echo json_encode($returnResponse);exit();

        }

		//Pass params to Model

		$params['v_created_by']=$this->session->userdata('user_id');	

		$response=$this->adminmodel->create_new_vehicle_make_records($params);

        if(empty($response))

		{

			$returnResponse['error']="Please Enter valid Details.";

			echo json_encode($returnResponse);exit();

		}			 

	

		// Set Session	

		$returnResponse['success']=true;

		$returnResponse['redirect']='admin/vehicle_make_list';

		$returnResponse['message']='New Vehicle Make has been created successfully.';

		echo json_encode($returnResponse);exit();	

	}



	public function company_list()

	{	

		$user_type=$this->session->userdata('user_type');

		if((!isset($user_type) && (string)$user_type==='') ||  (string)$user_type !='0')

		{

			 redirect(base_url(), 'refresh');

			 exit();

		}

		$_SESSION['currentActivePage']='Company_List';



		$limit=LIST_PAGE_LIMIT;					

		$offset=isset($_GET['offset'])?$_GET['offset']:0;

		if($offset !=0)

		{

			$offset=((int)$limit * (int)$offset)-(int)$limit;

		}

		$search=isset($_GET['search'])?$_GET['search']:'';		

		$data['totalNoOfCompanys']=$this->commonmodel->totalNoOfDealers();				

		$data['listofCommpanys']=$this->commonmodel->listofcompanys($limit,$offset,$search);				

		//print_r($data['listofCommpanys']);exit();

		$this->load->view('masters/company_list',$data);	

	}

//------------ Addon Starts -------------- // 

public function certificate_list(){	

		$user_type=$this->session->userdata('user_type');

		if((!isset($user_type) && (string)$user_type==='') ||  (string)$user_type !='0')
		{
		 redirect(base_url(), 'refresh');
		 exit();
		}

		$_SESSION['currentActivePage']='Certificate_List';

		$limit=LIST_PAGE_LIMIT;

		$offset=isset($_GET['offset'])?$_GET['offset']:0;

		if($offset !=0)

		{

			$offset=((int)$limit * (int)$offset)-(int)$limit;

		}

		$search=isset($_GET['search'])?$_GET['search']:'';		

		$data['totalNoOfCompanys']=$this->commonmodel->totalNoOfDealers();				

		$data['listofCommpanys']=$this->commonmodel->listofcertificates($limit,$offset,$search);				
		//print_r("<pre>");
		//print_r($data['listofCommpanys']);
		//print_r("</pre>");
		//exit();

		$this->load->view('masters/certificate_list',$data);	

	}


// ---------------------------------------// 

	public function vehicle_make_list()

	{	

		$user_type=$this->session->userdata('user_type');

		if((!isset($user_type) && (string)$user_type==='') ||  (string)$user_type !='0')

		{

			 redirect(base_url(), 'refresh');

			 exit();

		}

		$_SESSION['currentActivePage']='Vehicle_Make_List';



		$limit=LIST_PAGE_LIMIT;					

		$offset=isset($_GET['offset'])?$_GET['offset']:0;

		if($offset !=0)

		{

			$offset=((int)$limit * (int)$offset)-(int)$limit;

		}

		$search=isset($_GET['search'])?$_GET['search']:'';		

		$data['totalNoOfMakeList']=$this->commonmodel->totalNoOfvehicleMake();				

		$data['listofVehicleMakes']=$this->commonmodel->listofVehicleMakes($limit,$offset,$search);				

		//print_r($data['listofVehicleMakes']);exit();

		$this->load->view('masters/vehicle_make_list',$data);	

	}



		// Admin Create New Vehicle Model

	public function assign_serial_number()

	{	

		$user_type=$this->session->userdata('user_type');

		if((!isset($user_type) && (string)$user_type==='') ||  (string)$user_type !='0')

		{

			 redirect(base_url(), 'refresh');

			 exit();

		}

		$data['company_list']=$this->commonmodel->allCompanyList();	

		//print_r($data['company_list']);exit();

		$this->session->set_userdata('currentActivePage','Assign_Serial_Number');		

		// Load Content

		$this->load->view('masters/create_vehicle_serial_numbers',$data);		

	}



// --- Addons Started ----&&&&&&&&&&&&&&&&&&&&&



	public function create_certificate()

	{ 

	$user_type=$this->session->userdata('user_type');

		if(!isset($user_type) && (string)$user_type==='')
		{

			 redirect(base_url(), 'refresh');

			 exit();

		}

		

	$data['company_list']=$this->commonmodel->allCompanyList();		

	//Views

	$_SESSION['currentActivePage']='Create_Entry';

	$this->load->view('masters/create_vehicle_certificate',$data);

			

	}


public function create_No_of_Certificates()

	{	

		$params=$this->input->post();				

		$returnResponse=array();	

		$returnResponse['validation']=array();

		$returnResponse['error']="";

		$returnResponse['success']="";

		// Validation

		$this->form_validation->set_rules('s_company_id', 'Company Name', 'trim|required');

		$this->form_validation->set_rules('No_of_Certificates', 'No of Certificates', 'trim|required');

		$this->form_validation->set_rules('s_user_type', 'User Type', 'trim|required');

		$this->form_validation->set_rules('s_user_id', 'User Name', 'trim|required');

		//$exits_records=$this->commonmodel->verify_exits_serial_number($params['s_serial_number']);

		//Pass params to Model

		$params['s_created_by']=$this->session->userdata('user_id');	

		$response=$this->adminmodel->create_No_of_Certificates_records($params);
		
		
            //$returnResponse['error']= "STOP";
			//echo json_encode($returnResponse);
			//exit();

        if(empty($response))
		{
			$returnResponse['error']="Please Enter valid Details.";
			echo json_encode($returnResponse);
			exit();

		}			 

	

		// Set Session	

		$returnResponse['success']=true;
		$returnResponse['redirect']='admin/renewal_list';
		$returnResponse['message']='Certificates assigned successfully.';

		echo json_encode($returnResponse);
		exit();	

	}



// --- Addons Ends ---&&&&&&&&&&&&&&&



	// Create New Vehicle Make Records

	public function create_new_serial_numbers_records()

	{	

		$params=$this->input->post();				

		$returnResponse=array();	

		$returnResponse['validation']=array();

		$returnResponse['error']="";

		$returnResponse['success']="";

		// Validation

		$this->form_validation->set_rules('s_company_id', 'Company Name', 'trim|required');

		$this->form_validation->set_rules('s_serial_number', 'Serial Number', 'trim|required');

		$this->form_validation->set_rules('s_user_type', 'User Type', 'trim|required');

		$this->form_validation->set_rules('s_user_id', 'User Name', 'trim|required');

		

		$exits_records=$this->commonmodel->verify_exits_serial_number($params['s_serial_number']);
//print_r($exits_records);
//print_r("Get In");
//exit();
		$this->form_validation->set_rules(

				's_serial_number', 'Serial Number',

				array(

						'required',

						array(

								'already_exits',

								function($str)

								{

										$resultSet=$this->commonmodel->verify_exits_serial_number($str);

										if(empty($resultSet))

										{

											return true;

										}else{

											return false;

										}

								}

						)

				)

		);		

				

		

		// Validation verify

		if ($this->form_validation->run() == FALSE)
        {        	

			 $returnResponse['validation']=$this->form_validation->error_array();             

			 if(isset($returnResponse['validation']['s_serial_number']) && count($exits_records)>0)
			 {
			 	$exSerials=array();
			 	foreach ($exits_records as $key => $value) {

			 		if(isset($value['s_serial_number']))
			 		{
			 			$exSerials[]=$value['s_serial_number'];
			 		}
			 	}
			 	$exSerials=array_unique($exSerials);
			 	$returnResponse['validation']['s_serial_number']='The following serial number exits from our records.'."<br />".implode(',', $exSerials);
			 }
			 echo json_encode($returnResponse);exit();
        }

		//Pass params to Model

		$params['s_created_by']=$this->session->userdata('user_id');	

		$response=$this->adminmodel->create_new_serial_numbers_records($params);

        if(empty($response))

		{

			$returnResponse['error']="Please Enter valid Details.";

			echo json_encode($returnResponse);exit();

		}			 

	

		// Set Session	

		$returnResponse['success']=true;

		$returnResponse['redirect']='admin/serial_number_list';

		$returnResponse['message']='Serial Number assigned successfully.';

		echo json_encode($returnResponse);exit();	

	}



	public function update_serial_numbers_records()

	{

		$params=$this->input->post();	

		$params['s_serial_id']=base64_decode($params['s_serial_id']);

		//print_r($params);exit();

		$returnResponse=array();	

		$returnResponse['validation']=array();

		$returnResponse['error']="";

		$returnResponse['success']="";

		// Validation

		$this->form_validation->set_rules('s_company_id', 'Company Name', 'trim|required');

		$this->form_validation->set_rules('s_serial_number', 'Serial Number', 'trim|required');

		$this->form_validation->set_rules('s_user_type', 'User Type', 'trim|required');

		$this->form_validation->set_rules('s_user_id', 'User Name', 'trim|required');

		$this->form_validation->set_rules('s_serial_number', 'Serial Number', 'trim|required');

	

		

		$exits_records=$this->commonmodel->verify_exits_serial_number($params['s_serial_number'],$params['s_serial_id']);

		$this->form_validation->set_rules(

				's_serial_number', 'Serial Number',

				array(

						'required',

						array(

								'already_exits',

								function($str)

								{

										$arg=$this->input->post();	

										$arg['s_serial_id']=base64_decode($arg['s_serial_id']);

										$resultSet=$this->commonmodel->verify_exits_serial_number($str,$arg['s_serial_id']);

										if(empty($resultSet))

										{

											return true;

										}else{

											return false;

										}

								}

						)

				)

		);		

					

				

		

		

		// Validation verify

		if ($this->form_validation->run() == FALSE)

        {

			 $returnResponse['validation']=$this->form_validation->error_array();             

			 if(isset($returnResponse['validation']['s_serial_number']) && count($exits_records)>0)

			 {

			 	$exSerials=array();

			 	foreach ($exits_records as $key => $value) {

			 		if(isset($value['s_serial_number']))

			 		{

			 			$exSerials[]=$value['s_serial_number'];

			 		}

			 	}

			 	$exSerials=array_unique($exSerials);

			 	$returnResponse['validation']['s_serial_number']='The following serial number exits from our records.'."<br />".implode(',', $exSerials);

			 }

			 echo json_encode($returnResponse);exit();

        }	

		//Pass params to Model

		$response=$this->adminmodel->modify_new_serial_numbers_records($params);

        if(empty($response))

		{

			$returnResponse['error']="Please Enter valid Credentials.";

			echo json_encode($returnResponse);exit();

		}			 		

		// Set Session

		$this->session->set_userdata($response);		

		$returnResponse['success']=true;

		$returnResponse['redirect']='admin/serial_number_list';

		$returnResponse['message']='Serial number Updated successfully.';

		echo json_encode($returnResponse);exit();

	}





	public function serial_number_list()

	{	

		$user_type=$this->session->userdata('user_type');

		if((!isset($user_type) && (string)$user_type==='') ||  (string)$user_type !='0')

		{

			 redirect(base_url(), 'refresh');

			 exit();

		}

		$_SESSION['currentActivePage']='Serial_Number_List';



		$limit=LIST_PAGE_LIMIT;					

		$offset=isset($_GET['offset'])?$_GET['offset']:0;

		if($offset !=0)

		{

			$offset=((int)$limit * (int)$offset)-(int)$limit;

		}

		$search=isset($_GET['search'])?$_GET['search']:'';		

		$company_id=isset($_GET['company_id'])?$_GET['company_id']:'';		

		$data['totalNoOfSerialNumbers']=$this->commonmodel->totalNoOfSerialNumbers();				

		$data['listofSerialNumbers']=$this->commonmodel->listofSerialNumbers($limit,$offset,$search,$company_id);				$data['company_list']=$this->commonmodel->allCompanyList();				



		$this->load->view('masters/serial_number_list',$data);	

	}



	public function edit_serial_number($serial_number)

	{

		$user_type=$this->session->userdata('user_type');

		if((!isset($user_type) && (string)$user_type==='') || (string)$user_type !='0')

		{

			 redirect(base_url(), 'refresh');

			 exit();

		}

		if(!isset($serial_number))

		{

			  redirect(base_url().'admin/dashboard', 'refresh');

		}

		$data['SerialInfo']=$this->commonmodel->getSerialNumberInfo($serial_number);		

		if(empty($data['SerialInfo']))

		{

			 redirect(base_url().'admin/dashboard', 'refresh');

		}

		$data['SerialInfo']['s_serial_id']=base64_encode($serial_number);		

		$data['pageTitle']='Edit Serial Numbers';		

		$_SESSION['currentActivePage']='Serial_Number_List';		

		$data['company_list']=$this->commonmodel->allCompanyList();	



		$this->load->view('masters/edit_vehicle_serial_numbers',$data);		

	}





	public function fetch_list_of_users($needAdmin=0)

	{

		$params=$this->input->post();

		$currentUserID=0;

		if(isset($params['currentUserID']))		

		{

			$currentUserID=base64_decode($params['currentUserID']);	

		}

		

		$list=$this->commonmodel->fetch_list_of_users($params,$needAdmin,$currentUserID);	

		//echo $this->db->last_query();exit();

		$response=array();

		$response['list']=$list;

		echo json_encode($response);exit();

	}



	// Admin Create New Vehicle Model

	public function create_vehicle_model()

	{	

		$user_type=$this->session->userdata('user_type');

		if((!isset($user_type) && (string)$user_type===''))

		{

			 redirect(base_url(), 'refresh');

			 exit();

		}

		 

		$data['make_list']=$this->commonmodel->allMakeList();	

		$this->session->set_userdata('currentActivePage','Create_Vehicle_Model');		

		// Load Content

		$this->load->view('masters/create_vehicle_model',$data);		

	}





	public function vehicle_model_list()

	{	

		$user_type=$this->session->userdata('user_type');

		if((!isset($user_type) && (string)$user_type===''))

		{

			 redirect(base_url(), 'refresh');

			 exit();

		}

		$_SESSION['currentActivePage']='Vehicle_Model_List';



		$limit=LIST_PAGE_LIMIT;					

		$offset=isset($_GET['offset'])?$_GET['offset']:0;

		if($offset !=0)

		{

			$offset=((int)$limit * (int)$offset)-(int)$limit;

		}



		$search=isset($_GET['search'])?$_GET['search']:'';		

		$data['totalNoOfModelList']=$this->commonmodel->totalNoOfvehicleModel();				

		$data['listofVehicleModels']=$this->commonmodel->listofVehicleModels($limit,$offset,$search);			

		$data['make_list']=$this->commonmodel->allMakeList();			

		$MakeList=array();

		foreach ($data['listofVehicleModels'] as $key => $value) {

			if(isset($value['ve_make_id']) && !isset($MakeList[$value['ve_make_id']]))

			{

				$MakeList[$value['ve_make_id']]=array();

				$MakeList[$value['ve_make_id']]['name']=$value['v_make_name'];

				$MakeList[$value['ve_make_id']]['list']=array();				

			}

			$MakeList[$value['ve_make_id']]['list'][]=$value;

		}

		//print_r($MakeList);exit();

		$data['MakeList']=$MakeList;

		

		$this->load->view('masters/vehicle_model_list',$data);	

	}



	public function edit_vehicle_model($modelID)

	{

		$user_type=$this->session->userdata('user_type');

		if((!isset($user_type) && (string)$user_type==='') || (string)$user_type !='0')

		{

			 redirect(base_url(), 'refresh');

			 exit();

		}

		if(!isset($modelID))

		{

			  redirect(base_url().'admin/dashboard', 'refresh');

		}

		$data['ModelInfo']=$this->commonmodel->getModelInfo($modelID);		

		if(empty($data['ModelInfo']))

		{

			 redirect(base_url().'admin/dashboard', 'refresh');

		}

		$data['ModelInfo']['ve_model_id']=base64_encode($modelID);

		$data['pageTitle']='Edit Vehicle Model';		

		$_SESSION['currentActivePage']='Vehicle_Model_List';		

		$data['make_list']=$this->commonmodel->allMakeList();	

		//print_r($data['ModelInfo']);exit();

		// Load Content

		$this->load->view('masters/edit_vehicle_model',$data);		

	}

	



	public function update_vehicle_model_records()

	{

		$params=$this->input->post();	

		$params['ve_model_id']=base64_decode($params['ve_model_id']);

		//print_r($params);exit();

		$returnResponse=array();	

		$returnResponse['validation']=array();

		$returnResponse['error']="";

		$returnResponse['success']="";

		// Validation

		$this->form_validation->set_rules('ve_make_id', 'Make ID', 'trim|required');

		$this->form_validation->set_rules('ve_model_name', 'Model Name', 'trim|required');

		$this->form_validation->set_rules('ve_model_id', 'Make ID', 'trim|required');

			

		

		$this->form_validation->set_rules(

				've_model_name', 'Vehicle Model',

				array(

						'required',

						array(

								'already_exits',

								function($str)

								{

										$arg=$this->input->post();	

										$arg['ve_model_id']=base64_decode($arg['ve_model_id']);

										return $this->commonmodel->verify_exits_model_make_records($str,$arg['ve_make_id'],$arg['ve_model_id']);

								}

						)

				)

		);

		

		

		// Validation verify

		if ($this->form_validation->run() == FALSE)

        {

			 $returnResponse['validation']=$this->form_validation->error_array();             

			 echo json_encode($returnResponse);exit();

        }	



		//Pass params to Model

		$response=$this->adminmodel->modify_model_records($params);

        if(empty($response))

		{

			$returnResponse['error']="Please Enter valid Credentials.";

			echo json_encode($returnResponse);exit();

		}			 		

		// Set Session

		$this->session->set_userdata($response);		

		$returnResponse['success']=true;

		$returnResponse['redirect']='admin/vehicle_model_list';

		$returnResponse['message']='Vehicle Model Details updated successfully.';

		echo json_encode($returnResponse);exit();

	}

	

	





	public function users_list()

	{	

		$user_type=$this->session->userdata('user_type');

		if(!isset($user_type) && (string)$user_type==='')

		{

			 redirect(base_url(), 'refresh');

			 exit();

		}

		$_SESSION['currentActivePage']='Users_List';

		//print_r($this->session->all_userdata());exit();

		$limit=LIST_PAGE_LIMIT;					

		$offset=isset($_GET['offset'])?$_GET['offset']:0;

		if($offset !=0)

		{

			$offset=((int)$limit * (int)$offset)-(int)$limit;

		}

		$search=isset($_GET['search'])?$_GET['search']:'';		

		$data['totalNoOfDealers']=$this->commonmodel->totalNoOfDealers();				

		$data['listofdealers']=$this->commonmodel->listofdealers($limit,$offset,$search);				

		//echo $data['totalNoOfDealers'];exit();

		$this->load->view('masters/dealers_list',$data);	

	}



	public function edit_company($companyID)

	{

		$user_type=$this->session->userdata('user_type');

		if((!isset($user_type) && (string)$user_type==='') || (string)$user_type !='0')

		{

			 redirect(base_url(), 'refresh');

			 exit();

		}

		if(!isset($companyID))

		{

			  redirect(base_url().'admin/dashboard', 'refresh');

		}

		$data['companyInfo']=$this->commonmodel->getCompanyInfo($companyID);		

		if(empty($data['companyInfo']))

		{

			 redirect(base_url().'admin/dashboard', 'refresh');

		}

		$data['companyInfo']['c_company_id']=base64_encode($companyID);

		$data['pageTitle']='Edit Company Profile';		

		$_SESSION['currentActivePage']='Company_List';

		// Load Content

		$this->load->view('masters/edit_company',$data);		

	}



	public function edit_vehicle_make($makeID)

	{

		$user_type=$this->session->userdata('user_type');

		if((!isset($user_type) && (string)$user_type==='') || (string)$user_type !='0')

		{

			 redirect(base_url(), 'refresh');

			 exit();

		}

		if(!isset($makeID))

		{

			  redirect(base_url().'admin/dashboard', 'refresh');

		}

		$data['MakeInfo']=$this->commonmodel->getMakeInfo($makeID);		

		if(empty($data['MakeInfo']))

		{

			 redirect(base_url().'admin/dashboard', 'refresh');

		}

		$data['MakeInfo']['v_make_id']=base64_encode($makeID);

		$data['pageTitle']='Edit Vehicle Make';		

		$_SESSION['currentActivePage']='Vehicle_Make_List';		

		// Load Content

		$this->load->view('masters/edit_vehicle_make',$data);		

	}

	

	

	public function edit_users($dealerID)

	{

		$user_type=$this->session->userdata('user_type');

		if(!isset($user_type) && (string)$user_type==='')

		{

			 redirect(base_url(), 'refresh');

			 exit();

		}

		if(!isset($dealerID) || (string)$dealerID==='0')

		{

			  redirect(base_url().'admin/dashboard', 'refresh');

		}

		$data['rto_list']=$this->commonmodel->allRtoNumbers();		

		$data['userinfo']=$this->commonmodel->getDealerInfo($dealerID);		

		if(empty($data['userinfo']))

		{

			  redirect(base_url().'admin/dashboard', 'refresh');

		}

		$data['userinfo']['user_id']=base64_encode($dealerID);

		$data['pageTitle']='Edit Profile';		

		$_SESSION['currentActivePage']='Users_List';

		$data['company_list']=$this->commonmodel->allCompanyList();	

		// Load Content

		$this->load->view('masters/edit_dealer',$data);		

	}

	

	public function update_dealer_records()

	{

		$params=$this->input->post();	



		$params['user_id']=base64_decode($params['user_id']);

		//print_r($params);exit();

		if(!isset($params['user_distributor_id']))

		{

			$params['user_distributor_id']=0;	

			//$params['user_distributor_id']=0;

		}

		$returnResponse=array();	

		$returnResponse['validation']=array();

		$returnResponse['error']="";

		$returnResponse['success']="";

		// Validation

		$this->form_validation->set_rules('user_id', 'User ID', 'trim|required');

		$this->form_validation->set_rules('user_company_id', 'Company ID', 'trim|required');

		

		$this->form_validation->set_rules('name', 'Name', 'trim|required');

		$this->form_validation->set_rules('phone', 'Phone', 'trim|required');

		$this->form_validation->set_rules('email', 'Email', 'trim|required');

		$this->form_validation->set_rules('gender', 'Gender', 'trim|required');

		$this->form_validation->set_rules('password', 'Password', 'trim|required');

		$this->form_validation->set_rules('user_rto', 'Rto Number', 'trim|required');

			

		$this->form_validation->set_rules(

				'phone', 'Phone',

				array(

						'required',

						array(

								'phone_no_already_exits',

								function($str)

								{		

										$userID=base64_decode($this->input->post('user_id'));

										return $this->commonmodel->verify_exits_dealer_phone_number($str,$userID);

								}

						)

				)

		);

		$this->form_validation->set_rules(

				'email', 'Email',

				array(

						'required',

						array(

								'email_no_already_exits',

								function($str)

								{

										$userID=base64_decode($this->input->post('user_id'));

										return $this->commonmodel->verify_exits_dealer_email($str,$userID);

								}

						)

				)

		);		

		// Validation verify

		if ($this->form_validation->run() == FALSE)

        {

			 $returnResponse['validation']=$this->form_validation->error_array();             

			 echo json_encode($returnResponse);exit();

        }		

		// Rename Profile Photo

		if(isset($params['profile_photo']) && strlen($params['profile_photo'])>0)

		{

			if (strpos($params['profile_photo'], 'temp_upload') !== false) {

				$profile_photo = str_replace('public/temp_upload/', 'public/upload/users/', $params['profile_photo']);

				rename($params['profile_photo'], $profile_photo);

				$params['profile_photo']=$profile_photo;

			}

		}

		//Pass params to Model

		$response=$this->adminmodel->modify_dealer_records($params,$params['user_id']);

        if(empty($response))

		{

			$returnResponse['error']="Please Enter valid Credentials.";

			echo json_encode($returnResponse);exit();

		}			 		

		// Set Session

		$this->session->set_userdata($response);		

		$returnResponse['success']=true;

		$returnResponse['redirect']='admin/users_list';

		$returnResponse['message']='Users Details updated successfully.';

		echo json_encode($returnResponse);exit();

	}







	public function update_company_records()

	{

		$params=$this->input->post();	

		$params['c_company_id']=base64_decode($params['c_company_id']);

		//print_r($params);exit();

		$returnResponse=array();	

		$returnResponse['validation']=array();

		$returnResponse['error']="";

		$returnResponse['success']="";

		// Validation

		$this->form_validation->set_rules('c_company_id', 'Company ID', 'trim|required');

		$this->form_validation->set_rules('c_company_name', 'Company Name', 'trim|required');

		$this->form_validation->set_rules('c_cop_validity', 'Cop Validity', 'trim|required');		

		$this->form_validation->set_rules('c_tac_no[0]', 'Tac Number', 'trim|required');

			

		

		$this->form_validation->set_rules(

				'c_company_name', 'Company Name',

				array(

						'required',

						array(

								'already_exits',

								function($str)

								{

										$c_company_id=base64_decode($this->input->post('c_company_id'));

										return $this->commonmodel->verify_exits_company_name($str,$c_company_id);

								}

						)

				)

		);		

		$this->form_validation->set_rules(

					'c_tac_no[0]', 'Tac Number',

					array(

							'required',

							array(

									'already_exits',

									function($str)

									{

										   $c_tac_no=$this->input->post('c_tac_no');	

										   $c_company_id=base64_decode($this->input->post('c_company_id'));	

										   $resultArray=$this->commonmodel->verify_exits_company_tac_number($c_tac_no,$c_company_id);		

										   if(empty($resultArray))

										   {

										   	return true;

										   }

										   return false;

									}

							)

					)

		);				

			

		// Validation verify

		if ($this->form_validation->run() == FALSE)

        {

			 $returnResponse['validation']=$this->form_validation->error_array();             

			 echo json_encode($returnResponse);exit();

        }	

		

		//Pass params to Model

		$response=$this->adminmodel->modify_company_records($params);

        if(empty($response))

		{

			$returnResponse['error']="Please Enter valid Credentials.";

			echo json_encode($returnResponse);exit();

		}			 		

		// Set Session

		$this->session->set_userdata($response);		

		$returnResponse['success']=true;

		$returnResponse['redirect']='admin/company_list';

		$returnResponse['message']='Company Details updated successfully.';

		echo json_encode($returnResponse);exit();

	}







	public function update_vehicle_make_records()

	{

		$params=$this->input->post();	

		$params['v_make_id']=base64_decode($params['v_make_id']);

		//print_r($params);exit();

		$returnResponse=array();	

		$returnResponse['validation']=array();

		$returnResponse['error']="";

		$returnResponse['success']="";

		// Validation

		$this->form_validation->set_rules('v_make_id', 'Make ID', 'trim|required');

		$this->form_validation->set_rules('v_vehicle_make', 'Make Name', 'trim|required');

			

		

		$this->form_validation->set_rules(

				'v_vehicle_make', 'Make Name',

				array(

						'required',

						array(

								'already_exits',

								function($str)

								{

										$v_make_id=base64_decode($this->input->post('v_make_id'));

										return $this->commonmodel->verify_exits_make_name($str,$v_make_id);

								}

						)

				)

		);		

		

		// Validation verify

		if ($this->form_validation->run() == FALSE)

        {

			 $returnResponse['validation']=$this->form_validation->error_array();             

			 echo json_encode($returnResponse);exit();

        }	

		//print_r($params);exit();

		//Pass params to Model

		$response=$this->adminmodel->modify_make_records($params);

        if(empty($response))

		{

			$returnResponse['error']="Please Enter valid Credentials.";

			echo json_encode($returnResponse);exit();

		}			 		

		// Set Session

		$this->session->set_userdata($response);		

		$returnResponse['success']=true;

		$returnResponse['redirect']='admin/vehicle_make_list';

		$returnResponse['message']='Vehicle Make Details updated successfully.';

		echo json_encode($returnResponse);exit();

	}

	

	

	

	public function update_profile_records()

	{

		$user_type=$this->session->userdata('user_type');



		$params=$this->input->post();	

		$params['user_id']=base64_decode($params['user_id']);

		//print_r($params);exit();

		$returnResponse=array();	

		$returnResponse['validation']=array();

		$returnResponse['error']="";

		$returnResponse['success']="";

		if((string)$user_type !='0')

		{

			$returnResponse['error']="Admin have only permission to change profile records.";

			echo json_encode($returnResponse);exit();

		}	

		// Validation

		$this->form_validation->set_rules('user_id', 'User ID', 'trim|required');

		$this->form_validation->set_rules('name', 'Name', 'trim|required');

		$this->form_validation->set_rules('phone', 'Phone', 'trim|required');

		$this->form_validation->set_rules('email', 'Email', 'trim|required');

		$this->form_validation->set_rules('gender', 'Gender', 'trim|required');

		$this->form_validation->set_rules('password', 'Password', 'trim|required');

			

		$this->form_validation->set_rules(

				'phone', 'Phone',

				array(

						'required',

						array(

								'phone_no_already_exits',

								function($str)

								{		

										$userID=base64_decode($this->input->post('user_id'));

										return $this->commonmodel->verify_exits_dealer_phone_number($str,$userID);

								}

						)

				)

		);

		$this->form_validation->set_rules(

				'email', 'Email',

				array(

						'required',

						array(

								'email_no_already_exits',

								function($str)

								{

										$userID=base64_decode($this->input->post('user_id'));

										return $this->commonmodel->verify_exits_dealer_email($str,$userID);

								}

						)

				)

		);		

		// Validation verify

		if ($this->form_validation->run() == FALSE)

        {

			 $returnResponse['validation']=$this->form_validation->error_array();             

			 echo json_encode($returnResponse);exit();

        }		

		// Rename Profile Photo

		if(isset($params['profile_photo']) && strlen($params['profile_photo'])>0)

		{

			if (strpos($params['profile_photo'], 'temp_upload') !== false) {

				$profile_photo = str_replace('public/temp_upload/', 'public/upload/users/', $params['profile_photo']);

				rename($params['profile_photo'], $profile_photo);

				$params['profile_photo']=$profile_photo;

			}

		}

		//Pass params to Model

		$response=$this->adminmodel->update_profile_records($params,$params['user_id']);

        if(empty($response))

		{

			$returnResponse['error']="Please Enter valid Credentials.";

			echo json_encode($returnResponse);exit();

		}			 		

		// Set Session

		$this->session->set_userdata($response);		

		$returnResponse['success']=true;

		$returnResponse['redirect']='admin/logout';

		echo json_encode($returnResponse);exit();

	}

	

	public function create_new_entry()

	{

		$user_type=$this->session->userdata('user_type');

		if(!isset($user_type) && (string)$user_type==='')

		{

			 redirect(base_url(), 'refresh');

			 exit();

		}

		$user_id=$this->session->userdata('user_id');		

		$data['rto_list']=$this->commonmodel->allRtoNumbers();

		$data['make_list']=$this->commonmodel->allMakeList();	

		$data['serialList']=$this->commonmodel->allSerialList($user_id);					

		$data['company_list']=$this->commonmodel->allCompanyList($user_id);		

		//print_r($data['company_list']);exit();			

		// Load Content

		$_SESSION['currentActivePage']='Create_Entry';

		$this->load->view('masters/create_new_vehicle',$data);		

	}

	



	public function fetch_model_list_by_make()

	{

		$params=$this->input->post();	

		$data['model_list']=$this->commonmodel->allModelList($params['veh_make_no']);		

		echo json_encode($data);exit();

	}



	

	//Delete 

	public function changeUserStatus()

	{

		$params=$this->input->post();	

		$user_type=$this->session->userdata('user_type');		



		$returnResponse=array();	

		$returnResponse['validation']=array();

		$returnResponse['error']="";

		$returnResponse['success']="";

		if((string)$user_type !='0')

		{

			$returnResponse['error']="Admin Can have permission only.";

			echo json_encode($returnResponse);exit();

		}

		$result=$this->adminmodel->changeUserStatus($params);		

		$returnResponse['success']="true";

		echo json_encode($returnResponse);exit();

	}

	//Delete 

	public function delete_entry_list()

	{

		$params=$this->input->post();	

		$user_type=$this->session->userdata('user_type');		



		$returnResponse=array();	

		$returnResponse['validation']=array();

		$returnResponse['error']="";

		$returnResponse['success']="";

		if((string)$user_type !='0')

		{

			$returnResponse['error']="Admin Can have permission only.";

			echo json_encode($returnResponse);exit();

		}

		$result=$this->adminmodel->delete_entry_list($params);		

		$returnResponse['success']="true";

		echo json_encode($returnResponse);exit();

	}

//---  Add on Starts ----- 
public function fetch_company_info()

	{

		$params=$this->input->post();	

		$user_id=$this->session->userdata('user_id');		

		$data['Serial_List']=$this->commonmodel->selectCompanyInfo($params['veh_company_id'],$user_id);		

		echo json_encode($data);exit();

	}
// ------Add on Ends ----	
	public function fetch_serial_list_by_company()

	{

		$params=$this->input->post();	

		$user_id=$this->session->userdata('user_id');		

		$data['Serial_List']=$this->commonmodel->allSerialNumberByCompany($params['veh_company_id'],$user_id);		

		echo json_encode($data);exit();

	}

	// Agents

	public function create_new_vehicle_records()

	{	

		$params=$this->input->post();	

		//print_r($params);exit();

	

		$returnResponse=array();	

		$returnResponse['validation']=array();

		$returnResponse['error']="";

		$returnResponse['success']="";

		// Validation

		$this->form_validation->set_rules('veh_create_date', 'Vehicle Created Date', 'trim|required');

		$this->form_validation->set_rules('validity_to', 'Vehicle Validity Date', 'trim|required');

		$this->form_validation->set_rules('veh_rc_no', 'Vehicle Rc Number', 'trim|required');

		$this->form_validation->set_rules('veh_chassis_no', 'Vehicle Chassis Number', 'trim|required');

		$this->form_validation->set_rules('veh_company_id', 'Vehicle Company Name', 'trim|required');

		$this->form_validation->set_rules('veh_engine_no', 'Vehicle Engine Number', 'trim|required');

		$this->form_validation->set_rules('veh_make_no', 'Vehicle Make Number', 'trim|required');

		$this->form_validation->set_rules('veh_model_no', 'Vehicle Model Number', 'trim|required');

		$this->form_validation->set_rules('veh_owner_name', 'Vehicle Owner Name', 'trim|required');

		$this->form_validation->set_rules('veh_address', 'Vehicle Address', 'trim|required');

		$this->form_validation->set_rules('veh_owner_phone', 'Vehicle Owner Number', 'trim|required');

		$this->form_validation->set_rules('veh_serial_no', 'Vehicle Serial Number', 'trim|required');

		$this->form_validation->set_rules('veh_rto_no', 'Vehicle RTO Number', 'trim|required');

		$this->form_validation->set_rules('veh_speed', 'Vehicle Speed', 'trim|required');

		$this->form_validation->set_rules('veh_tac', 'Vehicle Tac Number', 'trim|required');

		$this->form_validation->set_rules('veh_invoice_no', 'Vehicle Invoice Number', 'trim|required');

		$this->form_validation->set_rules('veh_speed_governer_photo', 'Vehicle Governer Photo', 'trim|required');

		$this->form_validation->set_rules('veh_photo', 'Vehicle Photo', 'trim|required');

		

		$this->form_validation->set_rules(

				'veh_rc_no', 'Vehicle Rc Number',

				array(

						'required',

						array(

								'already_exits',

								function($str)

								{		

									

									return $this->commonmodel->verify_exits_vehicle_records($str,'veh_rc_no');

								}

						)

				)

		);

		

		$this->form_validation->set_rules(

				'veh_chassis_no', 'Vehicle Chassis Number',

				array(

						'required',

						array(

								'already_exits',

								function($str)

								{		

									return $this->commonmodel->verify_exits_vehicle_records($str,'veh_chassis_no');

								}

						)

				)

		);

		

		$this->form_validation->set_rules(

				'veh_engine_no', 'Vehicle Engine Number',

				array(

						'required',

						array(

								'already_exits',

								function($str)

								{		

									return $this->commonmodel->verify_exits_vehicle_records($str,'veh_engine_no');

								}

						)

				)

		);

		if(isset($params['veh_rc_no']) && strlen($params['veh_rc_no'])>0)

		{

			$params['veh_rc_no']=preg_replace('/\s+/', '', $params['veh_rc_no']);

		}

		// $this->form_validation->set_rules(

		// 	'veh_invoice_no', 'Vehicle Invoice Number',

		// 		array(

		// 				'required',

		// 				array(

		// 						'already_exits',

		// 						function($str)

		// 						{		

		// 							return $this->commonmodel->verify_exits_vehicle_records($str,'veh_invoice_no');

		// 						}

		// 				)

		// 		)

		// );

		$fetchCompanyInfo=$this->commonmodel->fetch(

				'c_company_id',

				$params['veh_company_id'],

				'c_company_name,c_cop_validity',

				$this->db->table_company);

		$params['veh_cop_validity']=isset($fetchCompanyInfo['c_cop_validity'])?$fetchCompanyInfo['c_cop_validity']:date('Y-m-d H:i:s');

		$params['veh_sld_make']=isset($fetchCompanyInfo['c_company_name'])?$fetchCompanyInfo['c_company_name']:"";

		$params['validity_from']=date('Y-m-d H:i:s');

		$params['validity_to']=date('Y-m-d H:i:s', strtotime("+".EXPIRE_DATE_VALUE." days"));

		$params['veh_create_date']=date('Y-m-d');

		//print_r($params);exit();

		// Validation verify

		if ($this->form_validation->run() == FALSE)

        {

			 $returnResponse['validation']=$this->form_validation->error_array();             

			 echo json_encode($returnResponse);exit();

        }

		// Rename // Rename Profile Photo

		if(isset($params['veh_speed_governer_photo']) && strlen($params['veh_speed_governer_photo'])>0)

		{

			if (strpos($params['veh_speed_governer_photo'], 'temp_upload') !== false) {

				$profile_photo = str_replace('public/temp_upload/', 'public/upload/vehicle/', $params['veh_speed_governer_photo']);

				rename($params['veh_speed_governer_photo'], $profile_photo);

				$params['veh_speed_governer_photo']=$profile_photo;

			}

		}

		// Rename // Rename Profile Photo

		if(isset($params['veh_photo']) && strlen($params['veh_photo'])>0)

		{

			if (strpos($params['veh_photo'], 'temp_upload') !== false) {

				$profile_photo = str_replace('public/temp_upload/', 'public/upload/vehicle/', $params['veh_photo']);

				rename($params['veh_photo'], $profile_photo);

				$params['veh_photo']=$profile_photo;

			}

		}

		

		$params['veh_created_user_id']=$this->session->userdata('user_id');		

		//Pass params to Model

		$response=$this->adminmodel->create_new_vehicle_records($params);

        if(empty($response))

		{

			$returnResponse['error']="Please Enter valid Details.";

			echo json_encode($returnResponse);exit();

		}			 	

		

		$returnResponse['success']=true;

		$user_type=$this->session->userdata('user_type');

		if((string)$user_type==='0' || (string)$user_type==='2')

		{

				$returnResponse['redirect']='admin/entry_list';

		}elseif((string)$user_type==='1')

		{

				$returnResponse['redirect']='dealer/entry_list';

		}

		

		echo json_encode($returnResponse);exit();	

	}

// Add on - addition Starts &&&&&&&&&&& 

	public function create_renewal_vehicle_records()

	{	

	
		
		$params=$this->input->post();	

		//print_r($params);exit();

		$returnResponse=array();	

		$returnResponse['validation']=array();

		$returnResponse['error']="";

		$returnResponse['success']="";

		// Validation

		$this->form_validation->set_rules('veh_create_date', 'Vehicle Created Date', 'trim|required');

		$this->form_validation->set_rules('validity_to', 'Vehicle Validity Date', 'trim|required');

		$this->form_validation->set_rules('veh_rc_no', 'Vehicle Rc Number', 'trim|required');

		$this->form_validation->set_rules('veh_chassis_no', 'Vehicle Chassis Number', 'trim|required');

		$this->form_validation->set_rules('veh_company_id', 'Vehicle Company Name', 'trim|required');

		$this->form_validation->set_rules('veh_engine_no', 'Vehicle Engine Number', 'trim|required');

		$this->form_validation->set_rules('veh_make_no', 'Vehicle Make Number', 'trim|required');

		$this->form_validation->set_rules('veh_model_no', 'Vehicle Model Number', 'trim|required');

		$this->form_validation->set_rules('veh_owner_name', 'Vehicle Owner Name', 'trim|required');

		$this->form_validation->set_rules('veh_address', 'Vehicle Address', 'trim|required');

		$this->form_validation->set_rules('veh_owner_phone', 'Vehicle Owner Number', 'trim|required');

		$this->form_validation->set_rules('veh_serial_no', 'Vehicle Serial Number', 'trim|required');

		$this->form_validation->set_rules('veh_rto_no', 'Vehicle RTO Number', 'trim|required');

		$this->form_validation->set_rules('veh_speed', 'Vehicle Speed', 'trim|required');

		$this->form_validation->set_rules('veh_tac', 'Vehicle Tac Number', 'trim|required');

		$this->form_validation->set_rules('veh_invoice_no', 'Vehicle Invoice Number', 'trim|required');

		$this->form_validation->set_rules('veh_speed_governer_photo', 'Vehicle Governer Photo', 'trim|required');

		$this->form_validation->set_rules('veh_photo', 'Vehicle Photo', 'trim|required');


		
		//------ Check Already Exists ------
		  
		    $response_check['userinfo'] = $this->commonmodel->Check_Certificate_Validity($userID = $this->session->userdata('user_id'));
									
									
		   if(empty($response_check)){

			$returnResponse['error']="You donot have the certificate create limit, Please Contact Administrator.";

			echo json_encode($returnResponse);exit();

		    }else{
			
			$available = $response_check['userinfo']['allotted'] - $response_check['userinfo']['used']; 
			
			if($available <= 0){
			
				$returnResponse['error']="Certificate Create Limit Reached, Please Contact Administrator..";
				echo json_encode($returnResponse);exit();
			}
			
			}	

                               
									// Blocked : return $this->commonmodel->verify_exits_vehicle_records($str,'veh_rc_no');

		
		//------ Check Already Exists -----		
		
		// ----- Check Serial Number Exists - Starts -----
		
		$My_response_check = $this->commonmodel->verify_renewal_serial_number_exists($params["veh_serial_no"],$params["veh_rc_no"]);
		
			
		 if(  empty( $My_response_check ) ) { 
		 
		// $returnResponse['error']="Serial Number is Not Exists";
			//echo json_encode($returnResponse);
			//exit();
		 
		 
		   }else{

			$returnResponse['error']="Serial Number is already Exists";
			echo json_encode($returnResponse);
			exit();
		    } 
			
		
		
		/*$this->form_validation->set_rules(

				'veh_serial_no', 'Serial Number',

				array( 'required', array(
											'already_exits', function($str){

										//$resultSet=$this->commonmodel->verify_exits_serial_number($str);
										$resultSet=$this->commonmodel->verify_renewal_serial_number_exists($str);

										if(empty($resultSet)){

											return true;

										}else{

											return false;

										}
									}	
								)
							)
						);		*/

		// ----- Check Serial Number Exists - Ends -----		
		
		
/*----- BLOCK RC Validation ------
		$this->form_validation->set_rules(
				'veh_rc_no', 'Vehicle Rc Number',
				array( 'required', array( 'already_exits', function($str){									

							return $this->commonmodel->verify_exits_vehicle_records($str,'veh_rc_no');

							}
						)
				    )
		        );
				
/*----- BLOCK CHASIS Validation ------

		$this->form_validation->set_rules(
				'veh_chassis_no', 'Vehicle Chassis Number',

				array( 'required', array( 'already_exits', 

								function($str){		

									return $this->commonmodel->verify_exits_vehicle_records($str,'veh_chassis_no');

							}
						)
					)
				);
--------- BLOCK Vehicle Engine Number Validation -------

		$this->form_validation->set_rules( 'veh_engine_no', 'Vehicle Engine Number', 
		
					array( 'required', array( 'already_exits',

								function($str){		
									return $this->commonmodel->verify_exits_vehicle_records($str,'veh_engine_no');
								}
							)
						)
                    );
					
-----------------------------------------------------------*/

		if(isset($params['veh_rc_no']) && strlen($params['veh_rc_no'])>0)

		{

			$params['veh_rc_no']=preg_replace('/\s+/', '', $params['veh_rc_no']);

		}

		// $this->form_validation->set_rules(

		// 	'veh_invoice_no', 'Vehicle Invoice Number',

		// 		array(

		// 				'required',

		// 				array(

		// 						'already_exits',

		// 						function($str)

		// 						{		

		// 							return $this->commonmodel->verify_exits_vehicle_records($str,'veh_invoice_no');

		// 						}

		// 				)

		// 		)

		// );

		$fetchCompanyInfo=$this->commonmodel->fetch(

				'c_company_id',

				$params['veh_company_id'],

				'c_company_name,c_cop_validity',

				$this->db->table_company);

		$params['veh_cop_validity']=isset($fetchCompanyInfo['c_cop_validity'])?$fetchCompanyInfo['c_cop_validity']:date('Y-m-d H:i:s');

		$params['veh_sld_make']=isset($fetchCompanyInfo['c_company_name'])?$fetchCompanyInfo['c_company_name']:"";

		$params['validity_from']=date('Y-m-d H:i:s');

		$params['validity_to']=date('Y-m-d H:i:s', strtotime("+".EXPIRE_DATE_VALUE." days"));

		$params['veh_create_date']=date('Y-m-d');

		//print_r($params);exit();

		// Validation verify

		if ($this->form_validation->run() == FALSE)

        {

			 $returnResponse['validation']=$this->form_validation->error_array();             

			 echo json_encode($returnResponse);exit();

        }

		// Rename // Rename Profile Photo

		if(isset($params['veh_speed_governer_photo']) && strlen($params['veh_speed_governer_photo'])>0)

		{

			if (strpos($params['veh_speed_governer_photo'], 'temp_upload') !== false) {

				$profile_photo = str_replace('public/temp_upload_renewal/', 'public/upload/vehicle_renewal/', $params['veh_speed_governer_photo']);

				rename($params['veh_speed_governer_photo'], $profile_photo);

				$params['veh_speed_governer_photo']=$profile_photo;

			}

		}

		// Rename // Rename Profile Photo

		if(isset($params['veh_photo']) && strlen($params['veh_photo'])>0)

		{

			if (strpos($params['veh_photo'], 'temp_upload') !== false) {

				$profile_photo = str_replace('public/temp_upload_renewal/', 'public/upload/vehicle_renewal/', $params['veh_photo']);

				rename($params['veh_photo'], $profile_photo);

				$params['veh_photo']=$profile_photo;

			}

		}

		

		$params['veh_created_user_id']=$this->session->userdata('user_id');		

		//Pass params to Model

		$response=$this->adminmodel->create_renewal_vehicle_records($params);

        if(empty($response))

		{

			$returnResponse['error']="Please Enter valid Details.";

			echo json_encode($returnResponse);exit();

		}			 	

		$reduce=$this->commonmodel->Reduce_Certificate($userID=$this->session->userdata('user_id'));

		

		$returnResponse['success']=true;

		$user_type=$this->session->userdata('user_type');

		if((string)$user_type==='0' || (string)$user_type==='2')

		{

				$returnResponse['redirect']='admin/renewal_list';

		}elseif((string)$user_type==='1')

		{

				$returnResponse['redirect']='dealer/renewal_list';

		}

		

		echo json_encode($returnResponse);exit();	

	}

	public function create_renewal_entry(){

		$user_type=$this->session->userdata('user_type');

		if(!isset($user_type) && (string)$user_type===''){

			 redirect(base_url(), 'refresh');
			 exit();
		}

		$user_id=$this->session->userdata('user_id');		

		$data['rto_list']=$this->commonmodel->allRtoNumbers();

		$data['make_list']=$this->commonmodel->allMakeList();	

		$data['serialList']=$this->commonmodel->allSerialList($user_id);					

		$data['company_list']=$this->commonmodel->allCompanyList($user_id);		

        
		//----------
		
		 $data['userinfo'] = $this->commonmodel->Check_Certificate_Validity($userID = $this->session->userdata('user_id'));									
									
		  if(empty($data['userinfo'])){
		  
		        

				$returnResponse['error']="You donot have the certificate create limit, Please Contact Administrator.";
			    echo json_encode($returnResponse);exit();

		    }else{
			
//			print_r($data['userinfo']);

			     $allotted = $data['userinfo']['allotted'];
				 $used =  $data['userinfo']['used']; 
				$available = $data['userinfo']['allotted'] - $data['userinfo']['used']; 
				
			}
		//----------	
		

		//print_r($data['company_list']);exit();			

		// Load Content

		$_SESSION['currentActivePage']='Create_Entry';

		$this->load->view('masters/create_renewal_vehicle',$data);		

	}



	public function renewal_list()

	{	

		$user_type=$this->session->userdata('user_type');

		if(!isset($user_type) && (string)$user_type==='')

		{

			 redirect(base_url(), 'refresh');

			 exit();

		}

		$user_id=$this->session->userdata('user_id');

		$limit=LIST_PAGE_LIMIT;					

		$offset=isset($_GET['offset'])?$_GET['offset']:0;

		if($offset !=0)

		{

			$offset=((int)$limit * (int)$offset)-(int)$limit;

		}

		if(!isset($_GET['start_date']) || strlen($_GET['start_date'])===0)

		{

			$_GET['start_date']=0;

		}

		if(!isset($_GET['end_date']) || strlen($_GET['end_date'])===0)

		{

			$_GET['end_date']=0;

		}		

		$search=isset($_GET['search'])?$_GET['search']:'';

		$data['totalNoOfVehicles']=$this->commonmodel->totalNoOfVehicleRenewals($user_id);	

		$data['listofvehicles']=$this->commonmodel->listofvehicleRenewals($limit,$offset,$search,$user_id);								
//print_r("Reached-100"); exit();
		//print_r($data['listofvehicles']);exit();

		$_SESSION['currentActivePage']='Entry_List';
                                   
		$this->load->view('masters/vehicle_renewal_list',$data);	

	}


// Add on - addition Ends &&&&&&&&&&&	

	public function entry_list()

	{	

		$user_type=$this->session->userdata('user_type');

		if(!isset($user_type) && (string)$user_type==='')

		{

			 redirect(base_url(), 'refresh');

			 exit();

		}

		$user_id=$this->session->userdata('user_id');

		$limit=LIST_PAGE_LIMIT;					

		$offset=isset($_GET['offset'])?$_GET['offset']:0;

		if($offset !=0)

		{

			$offset=((int)$limit * (int)$offset)-(int)$limit;

		}

		if(!isset($_GET['start_date']) || strlen($_GET['start_date'])===0)

		{

			$_GET['start_date']=0;

		}

		if(!isset($_GET['end_date']) || strlen($_GET['end_date'])===0)

		{

			$_GET['end_date']=0;

		}		

		$search=isset($_GET['search'])?$_GET['search']:'';

		$data['totalNoOfVehicles']=$this->commonmodel->totalNoOfVehicle($user_id);	

		$data['listofvehicles']=$this->commonmodel->listofvehicle($limit,$offset,$search,$user_id);								

		//print_r($data['listofvehicles']);exit();

		$_SESSION['currentActivePage']='Entry_List';

		$this->load->view('masters/vehicle_list',$data);	

	}

	

	public function edit_entry($VehicleID)

	{

		$user_type=$this->session->userdata('user_type');

		if(!isset($user_type) && (string)$user_type==='')

		{

			 redirect(base_url(), 'refresh');

			 exit();

		}

		if(!isset($VehicleID) || (string)$VehicleID==='0')

		{

			

			  redirect(base_url().'admin/dashboard', 'refresh');

		}

		$data['rto_list']=$this->commonmodel->allRtoNumbers();	

		$data['userinfo']=$this->commonmodel->getVehicleInfo($VehicleID);	

		//print_r($data['userinfo']);exit();			

		if(empty($data['userinfo']))

		{

			  redirect(base_url().'admin/dashboard', 'refresh');

		}

		$data['userinfo']['veh_id']=base64_encode($VehicleID);

		$data['pageTitle']='Edit Vehicle';		



		$user_id=$this->session->userdata('user_id');		

		$data['make_list']=$this->commonmodel->allMakeList();	

		$data['serialList']=$this->commonmodel->allSerialList($user_id);					

		$data['company_list']=$this->commonmodel->allCompanyList($user_id);	

		// Load Content

		$this->load->view('masters/edit_vehicle ',$data);		

	}

	

	public function update_vehicle_records()

	{

		$params=$this->input->post();	

		$params['veh_id']=base64_decode($params['veh_id']);

		

		$returnResponse=array();	

		$returnResponse['validation']=array();

		$returnResponse['error']="";

		$returnResponse['success']="";

		// Validation

		$this->form_validation->set_rules('veh_create_date', 'Vehicle Created Date', 'trim|required');

		$this->form_validation->set_rules('veh_rc_no', 'Vehicle Rc Number', 'trim|required');

		$this->form_validation->set_rules('veh_chassis_no', 'Vehicle Chassis Number', 'trim|required');

		$this->form_validation->set_rules('veh_company_id', 'Vehicle Company Name', 'trim|required');

		$this->form_validation->set_rules('veh_engine_no', 'Vehicle Engine Number', 'trim|required');

		$this->form_validation->set_rules('veh_make_no', 'Vehicle Make Number', 'trim|required');

		$this->form_validation->set_rules('veh_model_no', 'Vehicle Model Number', 'trim|required');

		$this->form_validation->set_rules('veh_owner_name', 'Vehicle Owner Name', 'trim|required');

		$this->form_validation->set_rules('veh_address', 'Vehicle Address', 'trim|required');

		$this->form_validation->set_rules('veh_owner_phone', 'Vehicle Owner Number', 'trim|required');

		$this->form_validation->set_rules('veh_serial_no', 'Vehicle Serial Number', 'trim|required');

		$this->form_validation->set_rules('veh_rto_no', 'Vehicle RTO Number', 'trim|required');

		$this->form_validation->set_rules('veh_speed', 'Vehicle Speed', 'trim|required');

		$this->form_validation->set_rules('veh_tac', 'Vehicle Tac Number', 'trim|required');

		$this->form_validation->set_rules('veh_invoice_no', 'Vehicle Invoice Number', 'trim|required');

		$this->form_validation->set_rules('veh_speed_governer_photo', 'Vehicle Governer Photo', 'trim|required');

		$this->form_validation->set_rules('veh_photo', 'Vehicle Photo', 'trim|required');

		

		$this->form_validation->set_rules(

				'veh_rc_no', 'Vehicle Rc Number',

				array(

						'required',

						array(

								'already_exits',

								function($str)

								{		

									$veh_id=$this->input->post('veh_id');

									$veh_id=base64_decode($veh_id);

									return $this->commonmodel->verify_exits_vehicle_records($str,'veh_rc_no',$veh_id);

								}

						)

				)

		);

		

		$this->form_validation->set_rules(

				'veh_chassis_no', 'Vehicle Chassis Number',

				array(

						'required',

						array(

								'already_exits',

								function($str)

								{		

									$veh_id=$this->input->post('veh_id');

									$veh_id=base64_decode($veh_id);

									return $this->commonmodel->verify_exits_vehicle_records($str,'veh_chassis_no',$veh_id);

								}

						)

				)

		);

		

		$this->form_validation->set_rules(

				'veh_engine_no', 'Vehicle Engine Number',

				array(

						'required',

						array(

								'already_exits',

								function($str)

								{		

									$veh_id=$this->input->post('veh_id');

									$veh_id=base64_decode($veh_id);

									return $this->commonmodel->verify_exits_vehicle_records($str,'veh_engine_no',$veh_id);

								}

						)

				)

		);

		

		// $this->form_validation->set_rules(

		// 		'veh_invoice_no', 'Vehicle Invoice Number',

		// 		array(

		// 				'required',

		// 				array(

		// 						'already_exits',

		// 						function($str)

		// 						{		

		// 							$veh_id=$this->input->post('veh_id');

		// 							$veh_id=base64_decode($veh_id);

		// 							return $this->commonmodel->verify_exits_vehicle_records($str,'veh_invoice_no',$veh_id);

		// 						}

		// 				)

		// 		)

		// );



		if(isset($params['veh_rc_no']) && strlen($params['veh_rc_no'])>0)

		{

			$params['veh_rc_no']=preg_replace('/\s+/', '', $params['veh_rc_no']);

		}



		//print_r($params);exit();



		// Validation verify

		if ($this->form_validation->run() == FALSE)

        {

			

			 $returnResponse['validation']=$this->form_validation->error_array();             

			 echo json_encode($returnResponse);exit();

        }		

		

		$fetchCompanyInfo=$this->commonmodel->fetch(

				'c_company_id',

				$params['veh_company_id'],

				'c_company_name,c_cop_validity',

				$this->db->table_company);

		$params['veh_cop_validity']=isset($fetchCompanyInfo['c_cop_validity'])?$fetchCompanyInfo['c_cop_validity']:date('Y-m-d H:i:s');

		$params['veh_sld_make']=isset($fetchCompanyInfo['c_company_name'])?$fetchCompanyInfo['c_company_name']:"";

		$params['validity_from']=date('Y-m-d H:i:s',strtotime($params['veh_create_date']));

		//$params['validity_to']=date('Y-m-d H:i:s',strtotime("+".EXPIRE_DATE_VALUE." days", strtotime($params['validity_from'])));

		// Rename Profile Photo

		// Rename // Rename Profile Photo

		if(isset($params['veh_speed_governer_photo']) && strlen($params['veh_speed_governer_photo'])>0)

		{

			if (strpos($params['veh_speed_governer_photo'], 'temp_upload') !== false) {

				$profile_photo = str_replace('public/temp_upload/', 'public/upload/vehicle/', $params['veh_speed_governer_photo']);

				rename($params['veh_speed_governer_photo'], $profile_photo);

				$params['veh_speed_governer_photo']=$profile_photo;

			}

		}

		// Rename // Rename Profile Photo

		if(isset($params['veh_photo']) && strlen($params['veh_photo'])>0)

		{

			if (strpos($params['veh_photo'], 'temp_upload') !== false) {

				$profile_photo = str_replace('public/temp_upload/', 'public/upload/vehicle/', $params['veh_photo']);

				rename($params['veh_photo'], $profile_photo);

				$params['veh_photo']=$profile_photo;

			}

		}

		//Pass params to Model

		$response=$this->adminmodel->modify_vehicle_records($params,$params['veh_id']);

        if(empty($response))

		{

			$returnResponse['error']="Please Enter valid Details.";

			echo json_encode($returnResponse);exit();

		}			 		

		// Set Session

		$this->session->set_userdata($response);		

		$returnResponse['success']=true;

		$user_type=$this->session->userdata('user_type');

		if((string)$user_type==='0')

		{

				$returnResponse['redirect']='admin/entry_list';

		}else

		{

				$returnResponse['redirect']='dealer/entry_list';

		}

		echo json_encode($returnResponse);exit();

	}

	



	public function dealersalesreport()

	{

		$data['make_list']=$this->commonmodel->allMakeList();	

		$data['rto_list']=$this->commonmodel->allRtoNumbers();		

		$data['company_list']=$this->commonmodel->allCompanyList();				

		$this->load->view('report/dealersalesreport',$data);		

	}	

	



	public function view_dealersalesreport()

	{

		$params=$this->input->get();	

	

		if(isset($params['start_date']))

		{

			$params['start_date']=$params['start_date'].' 00:00:00';	

		}



		if(isset($params['end_date']))

		{

			$params['end_date']=$params['end_date'].' 23:59:59';	

		}

		if(strtotime($params['end_date']) < strtotime($params['start_date']) )

		{

			$start=$params['start_date'];

			$end=$params['end_date'];

			$params['start_date']=date('Y-m-d',strtotime($end)).' 00:00:00';

			$params['end_date']=date('Y-m-d',strtotime($start)).' 23:59:59';

		}

		$data['reportData']=$this->adminmodel->view_dealersalesreport($params);					

		$data['params']=$params;		

		//print_r($data);exit();			

		$this->load->view('report/view_dealersalesreport',$data);		

	}





	public function inventoryreport()

	{

		$data['make_list']=$this->commonmodel->allMakeList();	

		$data['rto_list']=$this->commonmodel->allRtoNumbers();		

		$data['company_list']=$this->commonmodel->allCompanyList();				

		$this->load->view('report/inventoryreport',$data);		

	}	



	public function view_inventoryreport()

	{

		$params=$this->input->get();	

	

		if(isset($params['start_date']))

		{

			$params['start_date']=$params['start_date'].' 00:00:00';	

		}



		if(isset($params['end_date']))

		{

			$params['end_date']=$params['end_date'].' 23:59:59';	

		}

		if(strtotime($params['end_date']) < strtotime($params['start_date']) )

		{

			$start=$params['start_date'];

			$end=$params['end_date'];

			$params['start_date']=date('Y-m-d',strtotime($end)).' 00:00:00';

			$params['end_date']=date('Y-m-d',strtotime($start)).' 23:59:59';

		}

		$data['reportData']=$this->adminmodel->view_inventoryreport($params);					

		$data['params']=$params;		

		//print_r($data);exit();			

		$this->load->view('report/view_inventoryreport',$data);		

	}



	public function salesreport()

	{

		$user_type=$this->session->userdata('user_type');

		if(isset($user_type) && (string)$user_type!='0')

		{

			 redirect(base_url().'admin/dashboard', 'refresh');

			 exit();

		}

		$data['make_list']=$this->commonmodel->allMakeList();	

		$data['rto_list']=$this->commonmodel->allRtoNumbers();		

		$data['company_list']=$this->commonmodel->allCompanyList();				

		$this->load->view('report/salesreport',$data);		

	}	



	public function view_salesreport()

	{

		$params=$this->input->get();	

	

		if(isset($params['start_date']))

		{

			$params['start_date']=$params['start_date'].' 00:00:00';	

		}



		if(isset($params['end_date']))

		{

			$params['end_date']=$params['end_date'].' 23:59:59';	

		}

		if(strtotime($params['end_date']) < strtotime($params['start_date']) )

		{

			$start=$params['start_date'];

			$end=$params['end_date'];

			$params['start_date']=date('Y-m-d',strtotime($end)).' 00:00:00';

			$params['end_date']=date('Y-m-d',strtotime($start)).' 23:59:59';

		}

		$data['reportData']=$this->adminmodel->view_salesreport($params);					

		$data['params']=$params;		

		//print_r($data);exit();			

		$this->load->view('report/view_salesreport',$data);		

	}

}

