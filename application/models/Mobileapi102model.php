<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//require APPPATH . '\libraries\PHPMailer\PHPMailerAutoload.php';

class Mobileapi102model extends CI_Model {

        public function __construct()
        {
                parent::__construct();             
        }

        public function send_sms($phone,$otp,$message='')
        {
			//echo "function called"; exit;
        	$OtpMsg='OTP '.$otp.' to reset your Password, PSDN Tech.';
        	//$OtpMsg='Please enter OTP '.$otp.' from PSDN';
        	if(strlen($message)>0)
        	{
        	$OtpMsg=$message;    
        	}
			$curl = curl_init();
            //echo "http://api.msg91.com/api/sendhttp.php?route=4&sender=TESTIN&mobiles=".$phone."&authkey=273355AY7MXrX08qN5cbc5435&encrypt=&message=".$OtpMsg."&flash=&unicode=&schtime=&afterminutes=&response=&campaign=&country=91";			

			curl_setopt_array($curl, array(
			  CURLOPT_URL => "http://api.msg91.com/api/sendhttp.php?route=4&sender=PSDNIN&mobiles=".$phone."&authkey=273355AY7MXrX08qN5cbc5435&encrypt=&message=".$OtpMsg."&flash=&unicode=&afterminutes=&response=&campaign=&country=91",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_SSL_VERIFYHOST => 0,
			  CURLOPT_SSL_VERIFYPEER => 0,
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);
           // echo json_encode($err);
			curl_close($curl);

			if ($err) {
				//echo "if"; exit;
			  return  "cURL Error #:" . $err;
			} else {
				//echo "else"; exit;
			  return $response;
			}
		}
		
		public function certificate_expirydate_sms($phone,$message='')
        {
        	if(strlen($message)>0)
        	{
        	$OtpMsg=$message;    
        	}
			$curl = curl_init();
            //echo "http://api.msg91.com/api/sendhttp.php?route=4&sender=TESTIN&mobiles=".$phone."&authkey=273355AY7MXrX08qN5cbc5435&encrypt=&message=".$OtpMsg."&flash=&unicode=&schtime=&afterminutes=&response=&campaign=&country=91";			

			curl_setopt_array($curl, array(
			  CURLOPT_URL => "http://api.msg91.com/api/sendhttp.php?route=4&sender=PSDNIN&mobiles=".$phone."&authkey=273355AY7MXrX08qN5cbc5435&encrypt=&message=".$OtpMsg."&flash=&unicode=&afterminutes=&response=&campaign=&country=91",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_SSL_VERIFYHOST => 0,
			  CURLOPT_SSL_VERIFYPEER => 0,
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);
           // echo json_encode($err);
			curl_close($curl);

			if ($err) {
			  return  "cURL Error #:" . $err;
			} else {
			  return $response;
			}
		}
		
		public function certificate_expirydate_mail($to_mail)
        {
			$mail = new PHPMailer(true);
			$mail->SMTPDebug = 0;  
			$mail->isSMTP();
			// $mail->Host = "smtp.sendgrid.net"; 
			// $mail->SMTPAuth = true;                               
			// $mail->Username = 'psdnprabu';                 
			// $mail->Password = 'psdn@1234';                           
			// $mail->SMTPSecure = 'none';                            
			// $mail->Port = 25;
			$mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = 'mjkrsrinivasan@gmail.com';
            $mail->Password = '9655397467';
            $mail->SMTPSecure = 'TLS';
            $mail->Port = 587;
			//$to	= "kathiresan.softengg@gmail.com"; 
			$to = $to_mail;
			$mailbody = "Dear Sir/Madam,<br>Your device certificate is going to expire within 15 days.<br><br><b>Regards,</b><BR>PSDN Technology Pvt Ltd.";
			$mailsubject = 'Certificate expiry date info';
			$mail->setFrom('sales@psdn.live', 'PSDN');
			$mail->addAddress($to,$to);
			$mail->isHTML(true);
			$messageparam  = $mailbody;
			$mail->Subject = $mailsubject;
			$mail->Body    = $mailbody;
			///print_r($mail); exit;
			$mail->send(); 
		}

         public function get_imei_number($params)
        {
            $this->db->select('imei,uniqueId');	
        	$this->db->where_in('imei', $params);		
			$this->db->from($this->db->table_vechicle_tracking.' as veh');
            $result = $this->db->get();
			$result = $result->result_array();
			return $result;
        }

        public function view_tracking($params)
        {
			$otherdb = $this->load->database('tracking', TRUE);
            $otherdb->select('latitude,longitude,distance,signalStrength,lastupdatedTime,imei');
            $otherdb->where('vtrackingId', $params['vehId']);
            $otherdb->from($otherdb->table_trackings);
            $result2 = $otherdb->get();
			$result2 = $result2->result_array();
			//return $result2;

		   //   $DB2 = $this->load->database('tracking', TRUE); 
		   // // print_r();exit;
		 //   $result 	=  $DB2->query(" select * from gps_livetracking_data where customerID = '".$customerID."' ");
		//   $result = $result->result_array();
		//   return $result;
		//   exit();

            $this->db->select('veh.veh_rc_no as vehicleRegNumber,com.c_company_name as vendorName,veh.veh_speed as speed,veh.veh_device_brand as productName, veh.veh_cat as vehCat, ser.s_imei as imei,ser.s_mobile as simNumber');	
        	$this->db->where('veh.veh_id',$params['vehId']);		
			$this->db->from($this->db->table_vehicle.' as veh');
			$this->db->join($this->db->table_serial_no.' as ser', 'veh.veh_serial_no = ser.s_serial_id','left');
			$this->db->join($this->db->table_company.' as com', 'veh.veh_company_id = com.c_company_id','left');

			$this->db->join($this->db->table_customers.' as cus', 'cus.c_customer_id = veh.veh_owner_id','left');
            $result = $this->db->get();
           // echo $this->db->last_query();exit();
			$result = $result->result_array();
		    $a=array_merge($result,$result2);
		 	return $a;
        }
        
        public function cerificate_list($params)
        {
        $this->db->select('ser.s_mobile,ser.s_imei,veh.veh_id,veh.validity_to as veh_cop_validity,veh.veh_rc_no,cus.c_customer_id,ser.s_serial_id,cus.c_phone,ser.s_serial_number');	
        	$this->db->where('cus.c_phone',$params['c_phone']);		
			$this->db->from($this->db->table_customers.' as cus');
			$this->db->join($this->db->table_vehicle.' as veh', 'cus.c_customer_id = veh.veh_owner_id','right');
			$this->db->join($this->db->table_serial_no.' as ser', 'ser.s_serial_id = veh.veh_serial_no','right');		
			if(strlen(trim($params['search'])))
			{
				$this->db->where("(cus.c_phone like '%".$params['search']."%' OR  ser.s_mobile like '%".$params['search']."%' OR ser.s_imei like '%".$params['search']."%' OR veh.veh_id like '%".$params['search']."%' OR veh.veh_rc_no like '%".$params['search']."%' OR ser.s_serial_number like '%".$params['search']."%' )");
			}
	$this->db->limit($params['limit'], $params['start']);
            $result = $this->db->get();

			$result = $result->result_array();
		//	echo $this->db->last_query();exit();
			return $result;
        }
		
		public function vehiclestatus_list($params)
        {

		 //------------- New Code ---------	
		   $DB2 = $this->load->database('trackingdb', TRUE); 

		     $result 	=  $this->db->query("select c_customer_id from ci_customers where c_phone = '".$params['c_phone']."' ");
		     $customerID = $result->result_array()[0]['c_customer_id'];
		 // print_r();exit;
		   $result 	=  $DB2->query(" select * from gps_livetracking_data where customerID = '".$customerID."' ");
		   $result = $result->result_array();
		   return $result;
		   exit();

			//$result = $result->row_array();

			
			
			
			// the TRUE paramater tells CI that you'd like to return the database object.
		 
		    //$otherdb->db->select('imei');	
		 //------------- New code Ends -----	
			
        /*$this->db->select('ser.s_mobile,ser.s_imei,veh.veh_id,veh.veh_cop_validity,veh.veh_rc_no,cus.c_customer_id,ser.s_serial_id,cus.c_phone,ser.s_serial_number');	
        	$otherdb->db->where('cus.c_phone',$params['c_phone']);	*/	
			//$otherdb->db->from($otherdb->db->table_gps_livetracking_data);
			
			
				
		/*	if(strlen(trim($params['search'])))
			{
				$this->db->where("(cus.c_phone like '%".$params['search']."%' OR  ser.s_mobile like '%".$params['search']."%' OR ser.s_imei like '%".$params['search']."%' OR veh.veh_id like '%".$params['search']."%' OR veh.veh_rc_no like '%".$params['search']."%' OR ser.s_serial_number like '%".$params['search']."%' )");
			}
	$this->db->limit($params['limit'], $params['start']); */
	
	
            //$result = $otherdb->db->get();

			$result = $result->result_array();
		//	echo $this->db->last_query();exit();
			return $result;
        }

          public function verifyUsersData($params)
		{
			$this->db->select('*');
			if(isset($params['c_otp_reference']) && strlen($params['c_otp_reference'])>0)
			{
			$this->db->where('c_otp_reference',$params['c_otp_reference']);
			}else if(isset($params['c_user_remember']) && strlen($params['c_user_remember'])>0)
			{
			$this->db->where('c_user_remember',$params['c_user_remember']);
			}else{
				$this->db->where('c_customer_id',-1);
			}
			$this->db->from($this->db->table_customer);
            $result = $this->db->get();
			$result = $result->row_array();
			return $result;
		}



        public function updateInfo($params)
        {
        	$insertRecords=array();
        	if(isset($params['imageUrl']) && strlen($params['imageUrl'])>0)
        	{
					if (strpos($params['imageUrl'], 'temp_upload') !== false) {

						$profile_photo = str_replace('public/temp_upload/', 'public/upload/customer/', $params['imageUrl']);

						rename($params['imageUrl'], $profile_photo);

						$insertRecords['c_photo']=$profile_photo;
					}
			}
			if(isset($params['fname']) && strlen($params['fname'])>0)
			{
			 $insertRecords['c_customer_name']=$params['fname'];
			}
			if(isset($params['email']) && strlen($params['email'])>0)
			{
			 $insertRecords['c_email']=$params['email'];
			}
			if(isset($params['address']) && strlen($params['address'])>0)
			{
			 $insertRecords['c_address']=$params['address'];
			}
			if(isset($params['c_otp_reference']) && strlen($params['c_otp_reference'])>0)
			{
			 $insertRecords['c_otp_reference']=$params['c_otp_reference'];
			}
			if(isset($params['c_user_remember']) && strlen($params['c_user_remember'])>0)
			{
			 $insertRecords['c_user_remember']=$params['c_user_remember'];
			}
			if(isset($params['c_password']) && strlen($params['c_password'])>0)
			{
			  $insertRecords['c_password']=$params['c_password'];
			  //$insertRecords['c_user_remember']="";
			  //$insertRecords['c_otp_reference']="";
			}
			if(isset($params['user_logout']) && strlen($params['user_logout'])>0)
			{
			 $insertRecords['c_device_id']="";
			}
			$insertRecords['c_updated_date']=date('Y-m-d H:i:s');
			$this->db->where('c_phone', $params['c_phone']);
			$this->db->update($this->db->table_customer,$insertRecords);
            return true;
        }

        public function uploadCustomerPhotos() {	

       $path = "public/upload/";
		if (!is_dir($path)) {
			mkdir($path, 0777, TRUE);
		}
		$path = "public/upload/customer";
		if (!is_dir($path)) {
			mkdir($path, 0777, TRUE);
		}
		
		$path = "public/temp_upload/";
		if (!is_dir($path)) {
			mkdir($path, 0777, TRUE);
		}	
		$returnResponse=array();
		$valid_formats = array("jpg", "png", "gif", "bmp","jpeg");
	try {
        if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
            if($_FILES) {
                $name = $_FILES['imageData']['name'];
                $size = $_FILES['imageData']['size'];
                if(strlen($name)) {
                    list($txt, $ext) = explode(".", $name);
                    if(in_array(strtolower($ext),$valid_formats)) {
                        if($size<(5242880)) {
                            $actual_image_name = time().".".$ext;
                            $tmp = $_FILES['imageData']['tmp_name'];
                            if(move_uploaded_file($tmp, $path.$actual_image_name))
                            {
                               $returnResponse['success']=true; 
							   $returnResponse['path']=$path.$actual_image_name; 


							     $config['image_library'] = 'gd2';  
			                     $config['source_image'] =  $path.$actual_image_name;  
			                     $config['create_thumb'] = FALSE;  
			                     $config['maintain_ratio'] = TRUE;  
			                     $config['quality'] = '60%';  
			                     $config['width'] = 200;  
			                     $config['height'] = 200;  
			                     $config['new_image'] =  $path.$actual_image_name;  
			                     $this->load->library('image_lib', $config);  
			                     $this->image_lib->resize();  




                            } else {
							   $returnResponse['fail']=true; 
							   $returnResponse['error']='fiald to upload image';                                
                            }
                        } else {
							$returnResponse['fail']=true; 
							$returnResponse['error']='Image file size max 5 MB';                            
                        }
                    } else {                        
						$returnResponse['fail']=true; 
						$returnResponse['error']='Invalid file format..';
                    }
                } else {                 
				 $returnResponse['fail']=true; 
				 $returnResponse['error']='Please2 select image..!';
                }
            } else {
				$returnResponse['fail']=true; 
				$returnResponse['error']='Please1 select image..!';             
            }
        } 
    } catch(Exception $e) {
		$returnResponse['fail']=true; 
	    $returnResponse['error']=$e->getMessage();       
    }	 
return $returnResponse;
	}
	
	
		public function remove_sos($params)
		{
			$this->db->where('sos_cus_phone', $params['c_phone']);
			$this->db->where('sos_id', $params['sos_id']);
   			$this->db->delete($this->db->table_sos); 
   			return true;
		}

		public function add_sos($params)
		{
			$data=array();
			$data['sos_cus_phone']=$params['c_phone'];
			$data['sos_name']=$params['name'];
			$data['sos_number']=$params['phone'];
			$data['sos_status']=1;			
			$this->db->insert($this->db->table_sos,$data);
			return $data;
		}

		public function check_exists_sos($params)
		{
			$this->db->select('*');
			$this->db->where('sos.sos_cus_phone',$params['c_phone']);
			$this->db->where('sos.sos_number',$params['phone']);
			$this->db->from($this->db->table_sos,' as sos');
            $result = $this->db->get();
			$result = $result->num_rows();
			return $result;
		}

	   public function sos_list($params)
		{
			$this->db->select('sos.sos_id,sos.sos_name,sos.sos_number');
			$this->db->where('sos.sos_cus_phone',$params['c_phone']);
			$this->db->from($this->db->table_sos,' as sos');
            $result = $this->db->get();
			$result = $result->result_array();
			return $result;
		}


         public function verifyuser($params)
		{
			$this->db->select('c_customer_name as name');
			$this->db->where('c_phone',$params['c_phone']);
			$this->db->from($this->db->table_customers);
            $result = $this->db->get();
			$result = $result->row_array();
			// echo "<pre>HAI=====>";
            // print_r($result);
            // exit;
			return $result;
		}

		public function getCustomerProfileInfo($params){
			
		$this->db->select('c_customer_name as name,c_customer_name as fname,c_email as email, c_photo as imageUrl, c_phone as mobile, c_address as address');
			$this->db->where('c_phone',$params['c_phone']);
			$this->db->where('c_user_status',1);		
			$this->db->from($this->db->table_customer);
            $result = $this->db->get();
			$result = $result->row_array();
			return $result;
		}


        public function signin($params)
		{
			$this->db->select('*');
			$this->db->where('c_phone',$params['phone']);
			$this->db->where('c_password',$params['password']);		
			$this->db->where('c_user_status',1);		
			
			$this->db->from($this->db->table_customer);
            $result = $this->db->get();
			$result = $result->row_array();
			return $result;
		}

		 public function updateRememberKey($params)
        {
        	$insertRecords=array();
        	if(isset($params['remember']))
        	{
			$insertRecords['c_remember']=$params['remember'];
			}
			$insertRecords['c_device_id']=$params['device_id'];
			$insertRecords['c_updated_date']=$params['c_updated_date'];
			$this->db->where('c_customer_id', $params['c_customer_id']);
			$this->db->update($this->db->table_customer,$insertRecords);
            return true;
        }

         public function quick_signin($params)
		{
			$this->db->select('*');
			$this->db->where('c_remember',$params['remember']);			
			$this->db->from($this->db->table_customer);
            $result = $this->db->get();
			$result = $result->row_array();
			return $result;
		}

}
