<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminmodel extends CI_Model {

        public function __construct()
        {
                parent::__construct();             
        }

        public function delete_entry_list($params)
        {
        	 $this->db->where('veh_id', $params['veh_id']);
             $this->db->delete($this->db->table_vehicle);
             return true;
        }

         public function changeUserStatus($params)
        {
        	$insertRecords=array();
			$insertRecords['user_status']=$params['user_status'];
			$this->db->where('user_id', $params['user_id']);
			$this->db->update($this->db->table_users,$insertRecords);
            return true;
        }

        public function verifyrto($params)
		{
			$this->db->select('*');
			$this->db->where('rto_no',$params['rto_number']);
			$this->db->where('rto_pwd',$params['rto_pwd']);		
			
			$this->db->from($this->db->table_rto);
            $result = $this->db->get();
			$result = $result->row_array();
			return $result;
		}
		
		public function verifyuser($params)
		{
			$this->db->select('*');
			$this->db->where('user_phone',$params['phone_number']);
			$this->db->where('user_password',md5($params['password_value']));		
			$this->db->where('user_status',1);		
			if(isset($params['user_type']) && (string)$params['user_type']==='dealer')
			{
				$this->db->where('user_type',1);				
			}
			if(isset($params['user_type']) && (string)$params['user_type']==='distributor')
			{
				$this->db->where('user_type',2);				
			}
			if(isset($params['user_type']) && (string)$params['user_type']==='subadmin')
			{
				$this->db->where('user_type',4);				
			}
			$this->db->from($this->db->table_users);
            $result = $this->db->get();
			$result = $result->row_array();
		//	echo $this->db->last_query();exit();
			return $result;
		}

		public function create_new_vehicle_make_records($params)
		{			
			$insertRecords=array();		
			$insertRecords['v_make_name']=isset($params['v_vehicle_make'])?$params['v_vehicle_make']:"";
			$insertRecords['v_created_by']=isset($params['c_created_by'])?$params['c_created_by']:"";
			$this->db->insert($this->db->table_make,$insertRecords);
			return $this->db->affected_rows() > 0;
		}

		public function create_new_company_records($params)
		{
			$params['c_tac_no']=array_values(array_filter(array_unique($params['c_tac_no'])));
			$params['c_tac_no']=implode(',', $params['c_tac_no']);
			
			$insertRecords=array();		
			$insertRecords['c_company_name']=isset($params['c_company_name'])?$params['c_company_name']:"";
			$insertRecords['c_tac_no']=isset($params['c_tac_no'])?$params['c_tac_no']:"";			
			$insertRecords['c_cop_validity']=isset($params['c_cop_validity'])?$params['c_cop_validity']:"";
			$insertRecords['c_created_by']=isset($params['c_created_by'])?$params['c_created_by']:"";
			$this->db->insert($this->db->table_company,$insertRecords);
			return $this->db->affected_rows() > 0;
		}

		public function create_new_product_records($params)
		{
	
			$insertRecords=array();
			$insertRecords['p_company_id']=isset($params['p_company_id'])?$params['p_company_id']:"";
			$insertRecords['p_product_name']=isset($params['p_product_name'])?$params['p_product_name']:"";			
			$insertRecords['p_unit_price']=isset($params['p_unit_price'])?$params['p_unit_price']:"";
			$insertRecords['p_product_description']=isset($params['p_product_description'])?$params['p_product_description']:"";
			$insertRecords['p_created_by']=isset($params['p_created_by'])?$params['p_created_by']:"";
			$this->db->insert($this->db->table_products,$insertRecords);
			$insert_id = $this->db->insert_id();
			
			$insertLogRecords["product_id"] = $insert_id;
			$insertLogRecords["price"] = isset($params['p_unit_price'])?$params['p_unit_price']:0;
			$insertLogRecords["price_created_by"] = isset($params['p_created_by'])?$params['p_created_by']:"";
			//$insertLogRecords[""] = ;
			$this->db->insert($this->db->table_product_pricelog,$insertLogRecords);
			
			
			return $this->db->affected_rows() > 0;
		}

		public function create_new_vehicle_model_records($params)
		{
		
			$insertRecords=array();		
			$insertRecords['ve_make_id']=isset($params['ve_make_id'])?$params['ve_make_id']:"";
			$insertRecords['ve_model_name']=isset($params['ve_model_name'])?$params['ve_model_name']:"";			
			$insertRecords['ve_created_by']=isset($params['ve_created_by'])?$params['ve_created_by']:"";
			$this->db->insert($this->db->table_model,$insertRecords);
			return $this->db->affected_rows() > 0;
		}

		public function create_new_rto_records($params)
		{		
			$insertRecords=array();		
			$insertRecords['rto_number']=isset($params['rto_number'])?$params['rto_number']:"";			
			$insertRecords['rto_place']=isset($params['rto_place'])?$params['rto_place']:"";
			$insertRecords['rto_pwd']=RTO_PASSWORD;
			$this->db->insert($this->db->table_rto,$insertRecords);
			return $this->db->affected_rows() > 0;
		}
		
		public function create_new_dealer_records($params)
		{
			// echo "<pre>";
			// print_r($params);
			// echo "<pre>";
			// exit;
			$insertRecords=array();
			$insertRecords['user_name']=isset($params['name'])?$params['name']:"";
			$insertRecords['user_phone']=isset($params['phone'])?$params['phone']:"";
			$insertRecords['user_email']=isset($params['email'])?$params['email']:"";			
			$insertRecords['gstin']=isset($params['gstin'])?$params['gstin']:"";
			$insertRecords['invoice_prefix']=isset($params['invoice_prefix'])?$params['invoice_prefix']:"";			
			$insertRecords['user_company_id']=isset($params['user_company_id'])?$params['user_company_id']:"";			
			$insertRecords['user_own_company']=isset($params['user_own_company'])?$params['user_own_company']:"";			
			$insertRecords['user_password']=isset($params['password'])?md5($params['password']):"";
			$insertRecords['user_gender']=isset($params['gender'])?$params['gender']:"";
			$insertRecords['user_info']=isset($params['description'])?$params['description']:"";
			$insertRecords['user_photo']=isset($params['profile_photo'])?$params['profile_photo']:"";
			$insertRecords['user_type']=isset($params['user_type'])?$params['user_type']:"";
			$insertRecords['user_distributor_id']=isset($params['user_distributor_id'])?$params['user_distributor_id']:0;
			$insertRecords['users_rtono']=isset($params['user_rto'])?$params['user_rto']:"";
			$insertRecords['user_status']=1;
			$insertRecords['created_by']= $this->session->userdata('user_id');
			
			/* Account Details */
			
			$insertRecords['acc_no']= isset($params['user_acc_number'])?$params['user_acc_number']:"";
			$insertRecords['acc_name']= isset($params['user_acc_name'])?$params['user_acc_name']:"";
			$insertRecords['acc_ifsc_code']= isset($params['user_acc_ifsc_code'])?$params['user_acc_ifsc_code']:"";
			$insertRecords['acc_branch']= isset($params['user_acc_branch'])?$params['user_acc_branch']:"";
			if($this->session->userdata('user_type') == 2) {
				$insertRecords['state_id']= $this->session->userdata('state_id');
			} else if($this->session->userdata('user_type') == 4) {
				$insertRecords['state_id']= isset($params['user_states'])?$params['user_states']:"0";
			} else {
				$insertRecords['state_id']= isset($params['user_states'])?$params['user_states']:"0";
			}
			/* Dealer Supporting Documents */
			$insertRecords['user_photo']=isset($params['profile_photo'])?$params['profile_photo']:"";
			$insertRecords['gst_certificate']=isset($params['gst_certificate'])?$params['gst_certificate']:"";
			$insertRecords['id_proof']=isset($params['id_proof'])?$params['id_proof']:"";
			$insertRecords['photo_personal']=isset($params['photo_personal'])?$params['photo_personal']:"";
			$insertRecords['pan_card']=isset($params['pan_card'])?$params['pan_card']:"";
			$insertRecords['cancelled_cheque_leaf']=isset($params['cancelled_cheque_leaf'])?$params['cancelled_cheque_leaf']:"";
			$this->db->insert($this->db->table_users,$insertRecords);
			return $this->db->affected_rows() > 0;
		}

		// Addons - Starts &&&&&&&&&&&&
		
		public function create_No_of_Certificates_records($params)
		{

			$value = $params['No_of_Certificates'];
			
			// Check Records Exists
			$this->db->select('sl');	
			$this->db->where('created_to',$params['s_user_id']);	
			$this->db->from($this->db->table_certificate);
            $result = $this->db->get();
			$results = $result->row_array();
			
			
			if(empty($results)){
				// Newly insert, if it is not exists.				
				$insertRecords=array();
				$insertRecords['creator_type']=isset($params['s_user_type'])?$params['s_user_type']:"";
				$insertRecords['created_by']=isset($params['s_created_by'])?$params['s_created_by']:"";			
				$insertRecords['created_to']=isset($params['s_user_id'])?$params['s_user_id']:"";							
				$insertRecords['allotted']=isset($value)?$value:0;										
				$this->db->insert($this->db->table_certificate,$insertRecords);			
				return 1;
				
				// Insert Tracking Records, at first time.				
			
				$insertRecords=array();
				$insertRecords['creator_type']=isset($params['s_user_type'])?$params['s_user_type']:"";
				$insertRecords['created_by']=isset($params['s_created_by'])?$params['s_created_by']:"";			
				$insertRecords['created_to']=isset($params['s_user_id'])?$params['s_user_id']:"";							
				$insertRecords['allotted']=isset($value)?$value:0;										
				$this->db->insert($this->db->table_certificate_tracking,$insertRecords); 
				
			}else{
			
			   // Insert Tracking Records, if it is exists.				
			
				$insertRecords=array();
				$insertRecords['creator_type']=isset($params['s_user_type'])?$params['s_user_type']:"";
				$insertRecords['created_by']=isset($params['s_created_by'])?$params['s_created_by']:"";			
				$insertRecords['created_to']=isset($params['s_user_id'])?$params['s_user_id']:"";							
				$insertRecords['allotted']=isset($value)?$value:0;										
				$this->db->insert($this->db->table_certificate_tracking,$insertRecords);
				
				// Update Records in Main Certificate Table				
				$allotedVal = isset($value)?$value:0;
				$this->db->set('allotted', 'allotted+'.$allotedVal, FALSE);
				$this->db->where('sl', $results['sl']); // Check this Value
				$this->db->update($this->db->table_certificate);
				return 1;
				
			}  
				
		}
		
		// Addons  - Ends &&&&&&&&&&&&&
		
		
		public function create_new_serial_numbers_records($params)
		{

			$params['s_serial_number']=explode(',', $params['s_serial_number']);
			$params['s_serial_number']=array_values(array_filter(array_unique($params['s_serial_number'])));

			$this->db->select('user_id, invoice_prefix, invoice_sequence');	
			$this->db->from($this->db->table_users);
			$this->db->where('user_id', $params['s_user_id']); 
            $result = $this->db->get();
			$user = $result->row();
			$user->invoice_sequence = $user->invoice_sequence + 1;

			$this->db->set('invoice_sequence', $user->invoice_sequence, FALSE);
			$this->db->where('user_id', $user->user_id); // Check this Value
			$this->db->update($this->db->table_users);

			$serial_ids = array();
			foreach ($params['s_serial_number'] as $key => $value) {
				list($serial_number, $imei, $mobile) = explode('-', $value);
//var_dump($serial_number, $imei, $mobile);exit;
				$insertRecords=array();
				$insertRecords['s_company_id']=isset($params['s_company_id'])?$params['s_company_id']:"";
				$insertRecords['s_product_id']=isset($params['s_product_id'])?$params['s_product_id']:"";
				$insertRecords['s_serial_number']=isset($serial_number)?$serial_number:"";
				$insertRecords['s_imei']=isset($imei)?$imei:"";
				$insertRecords['s_mobile']=isset($mobile)?$mobile:"";
				$insertRecords['admin_price']=isset($params['admin_price'])?$params['admin_price']:0;
				//$insertRecords['s_user_type']=0;//isset($params['s_user_type'])?$params['s_user_type']:"";			
				//$insertRecords['s_user_id']=0;//isset($params['s_user_id'])?$params['s_user_id']:"";			
				$insertRecords['s_created_by']=isset($params['s_created_by'])?$params['s_created_by']:"";	
				$this->db->insert($this->db->table_serial_no,$insertRecords);
				$serial_ids[] = $this->db->insert_id();
			}					

			$this->db->insert($this->db->table_invoices,array(
				'invoice_number' => $user->invoice_prefix . $user->invoice_sequence,
				'i_serial_ids' => implode(',',$serial_ids),
				'i_user_type' => 0,
				'i_user_id' => 0,				
				'i_comments' => isset($params['comments'])?$params['comments']:0,
				'i_product_id' => isset($params['s_product_id'])?$params['s_product_id']:0,				
				'i_created_by' => isset($params['s_created_by'])?$params['s_created_by']:0,
			));

			return 1;
		}

		public function modify_new_serial_numbers_records($params)
		{
				$insertRecords=array();
				$insertRecords['s_company_id']=isset($params['s_company_id'])?$params['s_company_id']:"";
				$insertRecords['s_product_id']=isset($params['s_product_id'])?$params['s_product_id']:"";
				$insertRecords['s_serial_number']=isset($params['s_serial_number'])?$params['s_serial_number']:"";
				$insertRecords['s_imei']=isset($params['s_imei'])?$params['s_imei']:"";
				$insertRecords['s_mobile']=isset($params['s_mobile'])?$params['s_mobile']:"";
				$insertRecords['admin_price']=isset($params['admin_price'])?$params['admin_price']:0;
				//$insertRecords['s_user_type']=isset($params['s_user_type'])?$params['s_user_type']:"";
				//$insertRecords['s_user_id']=isset($params['s_user_id'])?$params['s_user_id']:"";			
				$this->db->where('s_serial_id', $params['s_serial_id']);
				$this->db->update($this->db->table_serial_no,$insertRecords);
					
			return 1;
		}

		public function assign_serial_numbers_records($params)
		{
			$params['s_serial_id']=explode(',', $params['s_serial_id']);
			//$params['s_serial_number']=array_values(array_filter(array_unique($params['s_serial_number'])));

			$this->db->select('user_id, invoice_prefix, invoice_sequence');	
			$this->db->from($this->db->table_users);
			$this->db->where('user_id', $this->session->userdata('user_id')); 
            $result = $this->db->get();
			$user = $result->row();

			//var_dump($user);exit;

			$user_type=$this->session->userdata('user_type');

			$user->invoice_sequence = $user->invoice_sequence + 1;

			$this->db->set('invoice_sequence', $user->invoice_sequence, FALSE);
			$this->db->where('user_id', $user->user_id); // Check this Value
			$this->db->update($this->db->table_users);

			$this->db->insert($this->db->table_invoices,array(
				'invoice_number' => $user->invoice_prefix . $user->invoice_sequence,
				'i_user_type' => ($params['hid_mode'] == 'unassigned')?2:1,
				'i_user_id' => ($params['hid_mode'] == 'unassigned')?$params['s_distributor_id']:$params['s_dealer_id'],
				'i_comments' => isset($params['comments'])?$params['comments']:0,
				'i_product_id' => isset($params['s_product_id'])?$params['s_product_id']:0,
				'i_serial_ids' => implode(',',$params['s_serial_id']),
				'i_created_by' => isset($params['s_created_by'])?$params['s_created_by']:0,
			));
//echo 'sdfsdf';exit;
			foreach ($params['s_serial_id'] as $key => $value) {
				$insertRecords=array();
				$insertRecords['s_company_id']=isset($params['s_company_id'])?$params['s_company_id']:"";
				$insertRecords['s_product_id']=isset($params['s_product_id'])?$params['s_product_id']:"";
				//$insertRecords['s_serial_id']=$value;
				if($params['hid_mode'] == 'unassigned'){
					$insertRecords['s_distributor_id']=isset($params['s_distributor_id'])?$params['s_distributor_id']:0;
					$insertRecords['distributor_price']=isset($params['distributor_price'])?$params['distributor_price']:0;
					$insertRecords['s_user_type']=2;
					$insertRecords['s_user_id']=isset($params['s_distributor_id'])?$params['s_distributor_id']:"";
				}else{
					$insertRecords['s_dealer_id']=isset($params['s_dealer_id'])?$params['s_dealer_id']:0;
					$insertRecords['dealer_price']=isset($params['dealer_price'])?$params['dealer_price']:0;
					$insertRecords['s_user_type']=1;
					$insertRecords['s_user_id']=isset($params['s_dealer_id'])?$params['s_dealer_id']:"";
				}
				//$insertRecords['s_created_by']=isset($params['s_created_by'])?$params['s_created_by']:"";	
				//$this->db->insert($this->db->table_serial_no,$insertRecords);

				$this->db->where('s_serial_id', $value);
				$this->db->update($this->db->table_serial_no, $insertRecords);
			}					
			return 1;
		}

		public function reassign_serial_numbers_records($params)
		{
				$user_type = $params['s_user_type'];

				$insertRecords=array();
				$insertRecords['s_company_id']=isset($params['s_company_id'])?$params['s_company_id']:"";
				$insertRecords['s_product_id']=isset($params['s_product_id'])?$params['s_product_id']:"";
				$insertRecords['s_serial_number']=isset($params['s_serial_number'])?$params['s_serial_number']:"";
				$insertRecords['s_user_type']=$user_type;

				if($user_type == '2'){
					$insertRecords['s_distributor_id']=isset($params['s_distributor_id'])?$params['s_distributor_id']:$params['s_user_id'];
					$insertRecords['distributor_price']=isset($params['distributor_price'])?$params['distributor_price']:0;
					$insertRecords['s_user_type']=2;
					$insertRecords['s_user_id']=isset($params['s_distributor_id'])?$params['s_distributor_id']:$params['s_user_id'];
				}else{
					$insertRecords['s_dealer_id']=isset($params['s_dealer_id'])?$params['s_dealer_id']:$params['s_user_id'];
					$insertRecords['dealer_price']=isset($params['dealer_price'])?$params['dealer_price']:0;
					$insertRecords['s_user_type']=1;
					$insertRecords['s_user_id']=isset($params['s_dealer_id'])?$params['s_dealer_id']:$params['s_user_id'];
				}
				// if($params['s_user_type'] == 2){
				// 	$insertRecords['distributor_price']=isset($params['distributor_price'])?$params['distributor_price']:0;
				// }else{
				// 	$insertRecords['dealer_price']=isset($params['dealer_price'])?$params['dealer_price']:0;
				// }
				$insertRecords['s_user_id']=isset($params['s_user_id'])?$params['s_user_id']:"";			
				$this->db->where('s_serial_id', $params['s_serial_id']);
				$this->db->update($this->db->table_serial_no,$insertRecords);
					
			return 1;
		}

		public function modify_company_records($params)
		{
			$params['c_tac_no']=array_values(array_filter(array_unique($params['c_tac_no'])));
			$params['c_tac_no']=implode(',', $params['c_tac_no']);

			$insertRecords=array();
			$insertRecords['c_company_name']=isset($params['c_company_name'])?$params['c_company_name']:"";
			$insertRecords['c_tac_no']=isset($params['c_tac_no'])?$params['c_tac_no']:"";			
			$insertRecords['c_cop_validity']=isset($params['c_cop_validity'])?$params['c_cop_validity']:"";
			
			$this->db->where('c_company_id', $params['c_company_id']);
			$this->db->update($this->db->table_company,$insertRecords);
			return 1;
		}

		public function modify_product_records($params)
		{
			//print_r($params); exit;
			$insertRecords=array();
			$insertRecords=array();
			$insertRecords['p_company_id']=isset($params['p_company_id'])?$params['p_company_id']:"";
			$insertRecords['p_product_name']=isset($params['p_product_name'])?$params['p_product_name']:"";			
			$insertRecords['p_unit_price']=isset($params['p_unit_price'])?$params['p_unit_price']:"";
			$insertRecords['p_product_description']=isset($params['p_product_description'])?$params['p_product_description']:"";
			
			$this->db->where('p_product_id', $params['p_product_id']);
			$this->db->update($this->db->table_products,$insertRecords);
			
			if((isset($params['p_unit_price'])) && ($params['p_unit_price'] ) != $params["p_unit_price_hidden"]){
				$insertLogRecords["product_id"] = isset($params['p_product_id'])?$params['p_product_id']:"";
				$insertLogRecords["price"] = isset($params['p_unit_price'])?$params['p_unit_price']:0;
				$insertLogRecords["price_created_by"] = isset($params['s_created_by'])?$params['s_created_by']:"";
				//$insertLogRecords[""] = ;
				$this->db->insert($this->db->table_product_pricelog,$insertLogRecords);
			}
			return 1;
		}

		public function modify_rto_records($params)
		{
			
			$insertRecords=array();
			$insertRecords['rto_number']=isset($params['rto_number'])?$params['rto_number']:"";			
			$insertRecords['rto_place']=isset($params['rto_place'])?$params['rto_place']:"";			

			$this->db->where('rto_no', $params['rto_no']);
			$this->db->update($this->db->table_rto,$insertRecords);
			return 1;
		}


		public function modify_make_records($params)
		{
			
			$insertRecords=array();
			$insertRecords['v_make_name']=isset($params['v_vehicle_make'])?$params['v_vehicle_make']:"";			

			$this->db->where('v_make_id', $params['v_make_id']);
			$this->db->update($this->db->table_make,$insertRecords);
			return 1;
		}

		public function modify_model_records($params)
		{
			
			$insertRecords=array();
			$insertRecords['ve_model_name']=isset($params['ve_model_name'])?$params['ve_model_name']:"";			
			$insertRecords['ve_make_id']=isset($params['ve_make_id'])?$params['ve_make_id']:"";			

			$this->db->where('ve_model_id', $params['ve_model_id']);
			$this->db->update($this->db->table_model,$insertRecords);
			return 1;
		}
		
		public function modify_dealer_records($params)
		{
			$insertRecords=array();
			$insertRecords['user_name']=isset($params['name'])?$params['name']:"";
			$insertRecords['user_phone']=isset($params['phone'])?$params['phone']:"";
			$insertRecords['user_email']=isset($params['email'])?$params['email']:"";
			$insertRecords['gstin']=isset($params['gstin'])?$params['gstin']:"";
			$insertRecords['invoice_prefix']=isset($params['invoice_prefix'])?$params['invoice_prefix']:"";
			if(isset($params['password']) && isset($params['old_password']) && (string)$params['password'] !=(string)$params['old_password'])
			{
				$insertRecords['user_password']=isset($params['password'])?md5($params['password']):"";
			}
			$insertRecords['user_gender']=isset($params['gender'])?$params['gender']:"";
			$insertRecords['user_company_id']=isset($params['user_company_id'])?$params['user_company_id']:"";
			$insertRecords['user_info']=isset($params['description'])?$params['description']:"";
			$insertRecords['user_photo']=isset($params['profile_photo'])?$params['profile_photo']:"";
			$insertRecords['user_distributor_id']=isset($params['user_distributor_id'])?$params['user_distributor_id']:"";
			//$insertRecords['user_type']=isset($params['user_type'])?$params['user_type']:"";
			$insertRecords['users_rtono']=isset($params['user_rto'])?$params['user_rto']:"";
			$insertRecords['user_status']=1;
			$insertRecords['user_own_company']=isset($params['user_own_company'])?$params['user_own_company']:"";
			
			/* Account Details */
			
			$insertRecords['acc_no']= isset($params['user_acc_number'])?$params['user_acc_number']:"";
			$insertRecords['acc_name']= isset($params['user_acc_name'])?$params['user_acc_name']:"";
			$insertRecords['acc_ifsc_code']= isset($params['user_acc_ifsc_code'])?$params['user_acc_ifsc_code']:"";
			$insertRecords['acc_branch']= isset($params['user_acc_branch'])?$params['user_acc_branch']:"";
			
			/* Dealer Supporting Documents */
			$insertRecords['user_photo']=isset($params['profile_photo'])?$params['profile_photo']:"";
			$insertRecords['gst_certificate']=isset($params['gst_certificate'])?$params['gst_certificate']:"";
			$insertRecords['id_proof']=isset($params['id_proof'])?$params['id_proof']:"";
			$insertRecords['photo_personal']=isset($params['photo_personal'])?$params['photo_personal']:"";
			$insertRecords['pan_card']=isset($params['pan_card'])?$params['pan_card']:"";
			$insertRecords['cancelled_cheque_leaf']=isset($params['cancelled_cheque_leaf'])?$params['cancelled_cheque_leaf']:"";
			
			$this->db->where('user_id', $params['user_id']);
			$this->db->update($this->db->table_users,$insertRecords);
			return 1;
		}
		
		public function update_profile_records($params)
		{
			$insertRecords=array();
			$insertRecords['user_name']=isset($params['name'])?$params['name']:"";
			$insertRecords['user_phone']=isset($params['phone'])?$params['phone']:"";
			$insertRecords['user_email']=isset($params['email'])?$params['email']:"";
			$insertRecords['gstin']=isset($params['gstin'])?$params['gstin']:"";
			$insertRecords['invoice_prefix']=isset($params['invoice_prefix'])?$params['invoice_prefix']:"";
			if(isset($params['password']) && isset($params['old_password']) && (string)$params['password'] !=(string)$params['old_password'])
			{
				$insertRecords['user_password']=isset($params['password'])?md5($params['password']):"";
			}
			$insertRecords['user_gender']=isset($params['gender'])?$params['gender']:"";
			$insertRecords['user_info']=isset($params['description'])?$params['description']:"";
			$insertRecords['user_photo']=isset($params['profile_photo'])?$params['profile_photo']:"";
			$this->db->where('user_id', $params['user_id']);
			$this->db->update($this->db->table_users,$insertRecords);
			return 1;
		}
		
		public function create_new_vehicle_records($params)
		{
			// echo "<pre>";
			// print_r($params);
			// exit;
			if(!$params['veh_owner_id'] && $params['veh_owner_phone']){
				$insert=array(
					'c_customer_name' => isset($params['veh_owner_name'])?$params['veh_owner_name']:"",
					'c_address' => isset($params['veh_address'])?$params['veh_address']:"",
					'c_phone' => isset($params['veh_owner_phone'])?$params['veh_owner_phone']:"",
					'c_user_status'=>1
				);
				if(isset($params['veh_owner_email']) && strlen($params['veh_owner_email'])>0)
				{
					$insert['c_email']=isset($params['veh_owner_email'])?$params['veh_owner_email']:"";
				}
				$this->db->insert($this->db->table_customers,$insert);

				$params['veh_owner_id'] = $this->db->insert_id();
			}

			$insertRecords=array();
			$insertRecords['veh_create_date']=isset($params['veh_create_date'])?$params['veh_create_date']:"";
			$insertRecords['veh_rc_no']=isset($params['veh_rc_no'])?$params['veh_rc_no']:"";
			$insertRecords['veh_chassis_no']=isset($params['veh_chassis_no'])?$params['veh_chassis_no']:"";			
			$insertRecords['veh_engine_no']=isset($params['veh_engine_no'])?$params['veh_engine_no']:"";
			$insertRecords['veh_make_no']=isset($params['veh_make_no'])?$params['veh_make_no']:"";
			$insertRecords['veh_model_no']=isset($params['veh_model_no'])?$params['veh_model_no']:"";
			$insertRecords['veh_owner_id']=isset($params['veh_owner_id'])?$params['veh_owner_id']:"";
			$insertRecords['veh_owner_name']=isset($params['veh_owner_name'])?$params['veh_owner_name']:"";
			$insertRecords['veh_address']=isset($params['veh_address'])?$params['veh_address']:"";
			$insertRecords['veh_owner_phone']=isset($params['veh_owner_phone'])?$params['veh_owner_phone']:"";
			$insertRecords['veh_serial_no']=isset($params['veh_serial_no'])?$params['veh_serial_no']:"";
			$insertRecords['veh_rto_no']=isset($params['veh_rto_no'])?$params['veh_rto_no']:"";
			$insertRecords['veh_speed']=isset($params['veh_speed'])?$params['veh_speed']:"";
			$insertRecords['veh_tac']=isset($params['veh_tac'])?$params['veh_tac']:"";
			$insertRecords['veh_cat']=isset($params['veh_cat'])?$params['veh_cat']:"";

			$insertRecords['veh_company_id']=isset($params['veh_company_id'])?$params['veh_company_id']:"";
			$insertRecords['veh_cop_validity']=isset($params['veh_cop_validity'])?$params['veh_cop_validity']:"";
			$insertRecords['veh_sld_make']=isset($params['veh_sld_make'])?$params['veh_sld_make']:"";
			$insertRecords['validity_from']=isset($params['validity_from'])?$params['validity_from']:"";
			$insertRecords['validity_to']=isset($params['validity_to'])?$params['validity_to']:"";
			$insertRecords['selling_price']=isset($params['selling_price'])?$params['selling_price']:"";

			$insertRecords['veh_invoice_no']=isset($params['veh_invoice_no'])?$params['veh_invoice_no']:"";
			$insertRecords['veh_speed_governer_photo']=isset($params['veh_speed_governer_photo'])?$params['veh_speed_governer_photo']:"";
			$insertRecords['veh_photo']=isset($params['veh_photo'])?$params['veh_photo']:"";
			$insertRecords['vehicle_owner_id_proof']=isset($params['vehicle_owner_id_proof_photo'])?$params['vehicle_owner_id_proof_photo']:"";			
			$insertRecords['vehicle_owner_photo']=isset($params['vehicle_owners_photo'])?$params['vehicle_owners_photo']:"";			
			$insertRecords['rc_book_photo']=isset($params['rc_book_photo'])?$params['rc_book_photo']:"";			
			$insertRecords['veh_created_user_id']=isset($params['veh_created_user_id'])?$params['veh_created_user_id']:"";			
			$insertRecords['veh_status']=1;
			$this->db->insert($this->db->table_vehicle,$insertRecords);
			$insert_id=$this->db->insert_id();
			// Used Flag on Serial number
			$updateRecords=array();
			$updateRecords['s_used']=1;			
			$this->db->where('s_serial_id', $insertRecords['veh_serial_no']);
			$this->db->update($this->db->table_serial_no,$updateRecords);
			
			//Update User_Sttaus
			$updateRecords=array();
			$updateRecords['c_user_status']=1;			
			$this->db->where('c_customer_id',$params['veh_owner_id']);
			$this->db->update($this->db->table_customers,$updateRecords);
			
			$info=array();
			$info['insert_id']=$insert_id;
			$info['veh_owner_id']=$params['veh_owner_id'];
			return $info;
		}
		
		public function customer_status($params){
			// print_r($params);
			// exit;
			$updateRecords=array();
			$updateRecords['c_status']= $params[0];
			for($Arry = 1; $Arry <= count($params);$Arry++){
				$this->db->where('c_customer_id',$params[$Arry]);
				$this->db->update($this->db->table_customers,$updateRecords);
			}
		}
		public function create_renewal_vehicle_records($params)
		{
			$insertRecords=array();
			$insertRecords['veh_create_date']=isset($params['veh_create_date'])?$params['veh_create_date']:"";
			$insertRecords['veh_rc_no']=isset($params['veh_rc_no'])?$params['veh_rc_no']:"";
			$insertRecords['veh_chassis_no']=isset($params['veh_chassis_no'])?$params['veh_chassis_no']:"";			
			$insertRecords['veh_engine_no']=isset($params['veh_engine_no'])?$params['veh_engine_no']:"";
			$insertRecords['veh_make_no']=isset($params['veh_make_no'])?$params['veh_make_no']:"";
			$insertRecords['veh_model_no']=isset($params['veh_model_no'])?$params['veh_model_no']:"";
			$insertRecords['veh_owner_name']=isset($params['veh_owner_name'])?$params['veh_owner_name']:"";
			$insertRecords['veh_address']=isset($params['veh_address'])?$params['veh_address']:"";
			$insertRecords['veh_owner_phone']=isset($params['veh_owner_phone'])?$params['veh_owner_phone']:"";
			$insertRecords['veh_serial_no']=isset($params['veh_serial_no'])?$params['veh_serial_no']:"";
			$insertRecords['veh_rto_no']=isset($params['veh_rto_no'])?$params['veh_rto_no']:"";
			$insertRecords['veh_speed']=isset($params['veh_speed'])?$params['veh_speed']:"";
			$insertRecords['veh_tac']=isset($params['veh_tac'])?$params['veh_tac']:"";

			$insertRecords['veh_company_id']=isset($params['veh_company_id'])?$params['veh_company_id']:"";
			$insertRecords['veh_cop_validity']=isset($params['veh_cop_validity'])?$params['veh_cop_validity']:"";
			$insertRecords['veh_sld_make']=isset($params['veh_sld_make'])?$params['veh_sld_make']:"";
			$insertRecords['validity_from']=isset($params['validity_from'])?$params['validity_from']:"";
			$insertRecords['validity_to']=isset($params['validity_to'])?$params['validity_to']:"";

			$insertRecords['veh_invoice_no']=isset($params['veh_invoice_no'])?$params['veh_invoice_no']:"";
			$insertRecords['veh_speed_governer_photo']=isset($params['veh_speed_governer_photo'])?$params['veh_speed_governer_photo']:"";
			$insertRecords['veh_photo']=isset($params['veh_photo'])?$params['veh_photo']:"";			
			$insertRecords['veh_created_user_id']=isset($params['veh_created_user_id'])?$params['veh_created_user_id']:"";			
			$insertRecords['veh_status']=1;
			$this->db->insert($this->db->table_renewal,$insertRecords);
			
			// Used Flag on Serial number
			//$updateRecords=array();
			//$updateRecords['s_used']=1;			
			//$this->db->where('s_serial_id', $insertRecords['veh_serial_no']);
			//$this->db->update($this->db->table_serial_no,$updateRecords);
			return $this->db->affected_rows() > 0;
		}
	
	
		public function modify_vehicle_records($params,$veh_id)
		{
			$insertRecords=array();
			$insertRecords['veh_create_date']=isset($params['veh_create_date'])?$params['veh_create_date']:"";
			$insertRecords['veh_rc_no']=isset($params['veh_rc_no'])?$params['veh_rc_no']:"";
			$insertRecords['veh_chassis_no']=isset($params['veh_chassis_no'])?$params['veh_chassis_no']:"";			
			$insertRecords['veh_engine_no']=isset($params['veh_engine_no'])?$params['veh_engine_no']:"";
			$insertRecords['veh_make_no']=isset($params['veh_make_no'])?$params['veh_make_no']:"";
			$insertRecords['veh_model_no']=isset($params['veh_model_no'])?$params['veh_model_no']:"";
			$insertRecords['veh_owner_name']=isset($params['veh_owner_name'])?$params['veh_owner_name']:"";
			$insertRecords['veh_address']=isset($params['veh_address'])?$params['veh_address']:"";
			$insertRecords['veh_owner_phone']=isset($params['veh_owner_phone'])?$params['veh_owner_phone']:"";
			$insertRecords['veh_serial_no']=isset($params['veh_serial_no'])?$params['veh_serial_no']:"";
			$insertRecords['veh_rto_no']=isset($params['veh_rto_no'])?$params['veh_rto_no']:"";
			$insertRecords['veh_speed']=isset($params['veh_speed'])?$params['veh_speed']:"";
			$insertRecords['veh_tac']=isset($params['veh_tac'])?$params['veh_tac']:"";
            $insertRecords['veh_cat']=isset($params['veh_cat'])?$params['veh_cat']:"";

			$insertRecords['veh_company_id']=isset($params['veh_company_id'])?$params['veh_company_id']:"";
			$insertRecords['veh_cop_validity']=isset($params['veh_cop_validity'])?$params['veh_cop_validity']:"";
			$insertRecords['veh_sld_make']=isset($params['veh_sld_make'])?$params['veh_sld_make']:"";
			$insertRecords['validity_from']=isset($params['validity_from'])?$params['validity_from']:"";
			$insertRecords['validity_to']=isset($params['validity_to'])?$params['validity_to']:"";
			$insertRecords['selling_price']=isset($params['selling_price'])?$params['selling_price']:"";

			$insertRecords['veh_invoice_no']=isset($params['veh_invoice_no'])?$params['veh_invoice_no']:"";
			$insertRecords['veh_speed_governer_photo']=isset($params['veh_speed_governer_photo'])?$params['veh_speed_governer_photo']:"";
			$insertRecords['veh_photo']=isset($params['veh_photo'])?$params['veh_photo']:"";
			$insertRecords['vehicle_owner_id_proof']=isset($params['vehicle_owner_id_proof_photo'])?$params['vehicle_owner_id_proof_photo']:"";			
			$insertRecords['vehicle_owner_photo']=isset($params['vehicle_owners_photo'])?$params['vehicle_owners_photo']:"";			
			$insertRecords['rc_book_photo']=isset($params['rc_book_photo'])?$params['rc_book_photo']:"";			
			$this->db->where('veh_id', $veh_id);
			$this->db->update($this->db->table_vehicle,$insertRecords);
			return 1;
		}

		public function view_dealersalesreport($params)
		{
			$this->db->select('veh.*,ser.s_serial_number,usr.user_type,usr.user_name as dealer,usr1.user_name as distributer');
			if(isset($params['serial_no']) && strlen($params['serial_no'])>0)
			{
				$this->db->or_like('ser.s_serial_id',$params['serial_no'], 'both');   
			}
			if(isset($params['user_type']) && (int)$params['user_type']>0)
			{
				$this->db->where('usr.user_type', $params['user_type']);
			}
			if(isset($params['user_id']) && (int)$params['user_id']>0)
			{
				$this->db->where('veh.veh_created_user_id', $params['user_id']);
			}
			if(isset($params['company_id']) && (int)$params['company_id']>0)
			{
				$this->db->where('usr.user_company_id', $params['company_id']);
			}
			if(isset($params['make_no']) && (int)$params['make_no']>0)
			{
				$this->db->where('veh.veh_make_no', $params['make_no']);
			}
			if(isset($params['model_id']) && (int)$params['model_id']>0)
			{
				$this->db->where('veh.veh_model_no', $params['model_id']);
			}
			if(isset($params['rto_no']) && (int)$params['rto_no']>0)
			{
				$this->db->where('veh.veh_rto_no', $params['rto_no']);
			}
			if(isset($params['rto_no']) && (int)$params['rto_no']>0)
			{
				$this->db->where('veh.veh_rto_no', $params['rto_no']);
			}
			if(isset($params['start_date']) && (int)$params['start_date']>0)
			{
				//$this->db->where('veh.veh_create_date >=', $params['start_date']);
			}
			if(isset($params['end_date']) && (int)$params['end_date']>0)
			{
				//$this->db->where('veh.veh_create_date <=', $params['end_date']);
			}
			
			$this->db->from($this->db->table_vehicle.' as veh');
			$this->db->join($this->db->table_serial_no.' as ser', 'ser.s_serial_id = veh.veh_serial_no','left');	
			$this->db->join($this->db->table_users.' as usr', 'usr.user_id = veh.veh_created_user_id');	
			$this->db->join($this->db->table_users.' as usr1', 'usr.user_distributor_id = usr1.user_id','left');	
			$this->db->order_by("veh.veh_create_date", "asc");	
            $result = $this->db->get();
			$result = $result->result_array();
			//echo $this->db->last_query();exit();
			return $result;
		}

		public function view_inventoryreport($params)
		{
			$this->db->select('ser.*,veh.veh_create_date,veh.veh_rc_no,usr.user_name');
			if(isset($params['serial_no']) && strlen($params['serial_no'])>0)
			{
				$this->db->or_like('ser.s_serial_id',$params['serial_no'], 'both');   
			}
			if(isset($params['user_type']) && (int)$params['user_type']>0)
			{
				$this->db->where('usr.user_type', $params['user_type']);
			}
			if(isset($params['user_id']) && (int)$params['user_id']>0)
			{
				$this->db->where('veh.veh_created_user_id', $params['user_id']);
			}
			if(isset($params['company_id']) && (int)$params['company_id']>0)
			{
				$this->db->where('usr.user_company_id', $params['company_id']);
			}
			if(isset($params['make_no']) && (int)$params['make_no']>0)
			{
				$this->db->where('veh.veh_make_no', $params['make_no']);
			}
			if(isset($params['model_id']) && (int)$params['model_id']>0)
			{
				$this->db->where('veh.veh_model_no', $params['model_id']);
			}
			if(isset($params['rto_no']) && (int)$params['rto_no']>0)
			{
				$this->db->where('veh.veh_rto_no', $params['rto_no']);
			}
			if(isset($params['start_date']) && (int)$params['start_date']>0)
			{
				//$this->db->where('ser.s_created_date >=', $params['start_date']);
			}
			if(isset($params['end_date']) && (int)$params['end_date']>0)
			{
				//$this->db->where('ser.s_created_date <=', $params['end_date']);
			}
			
			$this->db->from($this->db->table_serial_no.' as ser');
			$this->db->join($this->db->table_vehicle.' as veh', 'veh.veh_serial_no = ser.s_serial_id','left');		
			$this->db->join($this->db->table_users.' as usr', 'usr.user_id = ser.s_user_id','left');	
			$this->db->order_by("ser.s_used", "asc");	
            $result = $this->db->get();
			$result = $result->result_array();
			//echo $this->db->last_query();exit();
			return $result;
		}

		public function view_salesreport($params)
		{
			$this->db->select('ser.*,veh.veh_create_date,veh.veh_rc_no,usr.user_name');
			if(isset($params['serial_no']) && strlen($params['serial_no'])>0)
			{
				$this->db->or_like('ser.s_serial_id',$params['serial_no'], 'both');   
			}
			if(isset($params['user_type']) && (int)$params['user_type']>0)
			{
				$this->db->where('usr.user_type', $params['user_type']);
			}
			if(isset($params['user_id']) && (int)$params['user_id']>0)
			{
				$this->db->where('veh.veh_created_user_id', $params['user_id']);
			}
			if(isset($params['company_id']) && (int)$params['company_id']>0)
			{
				$this->db->where('usr.user_company_id', $params['company_id']);
			}
			if(isset($params['make_no']) && (int)$params['make_no']>0)
			{
				$this->db->where('veh.veh_make_no', $params['make_no']);
			}
			if(isset($params['model_id']) && (int)$params['model_id']>0)
			{
				$this->db->where('veh.veh_model_no', $params['model_id']);
			}
			if(isset($params['rto_no']) && (int)$params['rto_no']>0)
			{
				$this->db->where('veh.veh_rto_no', $params['rto_no']);
			}
			if(isset($params['start_date']) && (int)$params['start_date']>0)
			{
				$this->db->where('ser.s_created_date >=', $params['start_date']);
			}
			if(isset($params['end_date']) && (int)$params['end_date']>0)
			{
				$this->db->where('ser.s_created_date <=', $params['end_date']);
			}
			
			$this->db->from($this->db->table_serial_no.' as ser');
			$this->db->join($this->db->table_vehicle.' as veh', 'veh.veh_serial_no = ser.s_serial_id','left');		
			$this->db->join($this->db->table_users.' as usr', 'usr.user_id = ser.s_user_id','left');	
			$this->db->order_by("ser.s_used", "asc");	
            $result = $this->db->get();
			$result = $result->result_array();
			//echo $this->db->last_query();exit();
			return $result;
		}
		
		public function add_tracking_entry($params,$ownerID=0)
		{
		    try{
			$this->db->select('ser.s_imei,veh.veh_rc_no');
			$this->db->where('veh.veh_id', $params);
			$this->db->from($this->db->table_serial_no.' as ser');
			$this->db->join($this->db->table_vehicle.' as veh', 'veh.veh_serial_no = ser.s_serial_id','left');	
            $result = $this->db->get();
			$result = $result->row_array();
			
			if(isset($result['s_imei']))
			{
			     $sql = "INSERT INTO gps_livetracking_data(customerID,imei,vehicleRegnumber) VALUES ('".$ownerID."','".$result['s_imei']."','".$result['veh_rc_no']."'); ";
				 $tracking = $this->load->database('tracking', TRUE); 
				 $tracking->query($sql);
				 //$tracking->insert($this->db->table_tracking,array('imei'=>$result['s_imei']));
			}
		    }
		    catch(Exception $e) {
		        
		    }
			return true;
		}
}