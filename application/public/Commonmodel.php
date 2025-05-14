<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Commonmodel extends CI_Model {

        public function __construct()
        {
                parent::__construct();             
        }
		
		public function getNoOfCount()
		{
			$response=array();
			// Dealers
			$this->db->select('*');	
			$this->db->where('user_type',1);
			$this->db->from($this->db->table_users);			
            $result = $this->db->count_all_results();			
			$response['dealer']=$result;
			// DISTRIBUTOR 
			$this->db->select('*');	
			$this->db->where('user_type',2);
			$this->db->from($this->db->table_users);			
            $result = $this->db->count_all_results();			
			$response['distributor']=$result;
			// RTO 
			$this->db->select('*');	
			$this->db->where('user_type',3);
			$this->db->from($this->db->table_users);			
            $result = $this->db->count_all_results();			
			$response['rto']=$result;
			// Vehicle 
			$this->db->select('*');	
			$this->db->from($this->db->table_vehicle);			
            $result = $this->db->count_all_results();			
			$response['vehicle']=$result;
			return $response;
		}
		
		public function qrcode($data)
		{
			$this->load->library('ciqrcode');
			header("Content-Type: image/png");
			$params['data'] = $data;
			return $this->ciqrcode->generate($params);
		}
		
		public function saveQrCode($qrparams)
		{
			$this->load->library('ciqrcode');
			$params['data'] = $qrparams;
			$params['level'] = 'H';
			$params['size'] = 10;
			$params['savename'] = FCPATH.'public/qrcode/'.time().'.png';
			$this->ciqrcode->generate($params);
			return $params['savename'];
		}
		
		
		public function allRtoNumbers()
		{
			$this->db->select('*');	
			$this->db->from($this->db->table_rto);
            $result = $this->db->get();
			$result = $result->result_array();
			return $result;
		}

		public function fetch($whereFrom,$whereTo,$fields,$table,$single=true)
		{
			$this->db->select($fields);	
			$this->db->where($whereFrom,$whereTo);
			$this->db->from($table);
            $result = $this->db->get();
            if($single)
            {
            	$result = $result->row_array();	
            }else{
            	$result = $result->result_array();	
            }
			
			return $result;
		}

		

		public function allMakeList()
		{
			$this->db->select('v_make_id,v_make_name');	
			$this->db->from($this->db->table_make);
            $result = $this->db->get();
			$result = $result->result_array();
			return $result;
		}
 

		public function allModelList($makeID=0)
		{
			$this->db->select('ve_model_id,ve_model_name');	
			if((string)$makeID!='0')
			{
				$this->db->where('ve_make_id ',$makeID);
			}
			$this->db->from($this->db->table_model);
            $result = $this->db->get();
			$result = $result->result_array();
			return $result;
		}

		public function allSerialList($byUser=0)
		{
			$this->db->select('s_serial_id,s_serial_number,s_company_id');	
			if((string)$byUser!='1')
			{
				$this->db->where('s_user_id ',$byUser);
			}
			$this->db->from($this->db->table_serial_no);
            $result = $this->db->get();
			$result = $result->result_array();
			return $result;
		}

		public function fetch_list_of_users($params,$needAdmin,$avoid=0)
		{
			$this->db->select('user_id,CONCAT(user_name, " - ", user_phone) AS user_name');	
			if(isset($avoid) && (int)$avoid>0)
			{
				$this->db->where('user_id  !=',$avoid);
			}	
			$this->db->where('user_type ',$params['user_type']);
			if((string)$needAdmin==='1')
			{
				$this->db->or_where('user_id ',1);
			}
			
			$this->db->from($this->db->table_users);
            $result = $this->db->get();
			$result = $result->result_array();
		//echo $this->db->last_query();exit();
			return $result;
		}


		public function allSerialNumberByCompany($companyID=0,$userID=0)
		{
			$this->db->select('ser.s_serial_id,ser.s_serial_number,com.c_tac_no');	
			$this->db->from($this->db->table_serial_no.' as ser');
			$this->db->join($this->db->table_company.' as com', 'ser.s_company_id = com.c_company_id');	
			if((string)$companyID !='0')
			{
				$this->db->where('ser.s_company_id', $companyID);   	
			}
			if((string)$userID !='0' && (string)$userID !='1')
			{
				$this->db->where('ser.s_user_id', $userID);   	
			}
			$this->db->where('ser.s_used', 0);   
            $result = $this->db->get();
			$result = $result->result_array();
			//echo $this->db->last_query();exit();
			return $result;
		}



		public function allCompanyList($userID=0)
		{
			$this->db->select('com.c_company_name,com.c_company_id,com.c_tac_no');	
			$this->db->from($this->db->table_company.' as com');
			if((string)$userID!='0' && (string)$userID !='1')
			{
				$this->db->join($this->db->table_users.' as usr', 'usr.user_company_id = com.c_company_id');		
				$this->db->where('usr.user_id', $userID);   	
			}
			
            $result = $this->db->get();
			$result = $result->result_array();
			//echo $this->db->last_query();exit();
			return $result;
		}

		public function listofTodayEntrys($selectedReportDate,$rtoNo)
		{
			$this->db->select('veh.*,ser.s_serial_number');	
			
			$this->db->where('veh_create_date', $selectedReportDate);   
			$this->db->where('veh_rto_no', $rtoNo);   
			$this->db->order_by("veh_id", "asc");		

			$this->db->from($this->db->table_vehicle.' veh');					
			$this->db->join($this->db->table_serial_no.' as ser', 'ser.s_serial_id = veh.veh_serial_no', 'left');	

            $result = $this->db->get();
			$result = $result->result_array();
			//echo $this->db->last_query();exit();
			return $result;
		}
		public function totalNoOflistofTodayEntrys($selectedReportDate,$rtoNo)
		{
			$this->db->select('*');				
			$this->db->where('veh_create_date', $selectedReportDate);   
			$this->db->where('veh_rto_no', $rtoNo);   
			$this->db->order_by("veh_id", "asc");							
			$this->db->from($this->db->table_vehicle);			
            $result = $this->db->count_all_results();	
			return $result;
		}
		


		public function listofVehicleMakes($limit,$offset,$search='')
		{
			$this->db->select('v_make_id,v_make_name');	
			
			if(strlen($search)>0)
			{
				$this->db->or_like('v_make_name',$search, 'both');   
			}			
			$this->db->from($this->db->table_make);
			$this->db->limit($limit, $offset);
			$this->db->order_by("v_make_name", "asc");
            $result = $this->db->get();
			$result = $result->result_array();
			return $result;
		}
		public function listofcompanys($limit,$offset,$search='')
		{
			$this->db->select('c_company_name,c_tac_no,c_cop_validity,c_company_id');	
			
			if(strlen($search)>0)
			{
				$this->db->or_like('c_company_name',$search, 'both');   
				$this->db->or_where("FIND_IN_SET('$search',c_tac_no) !=", 0);
			}
			
			$this->db->from($this->db->table_company);
			$this->db->limit($limit, $offset);
			$this->db->order_by("c_company_name", "asc");
            $result = $this->db->get();
			$result = $result->result_array();
			return $result;
		}
		
		public function listofdealers($limit,$offset,$search='')
		{
			$this->db->select('user_id,user_name,user_phone,user_type,user_status');	
			
			if(strlen($search)>0)
			{
				$this->db->or_like('user_name',$search, 'both');   
				$this->db->or_like('user_phone',$search, 'both');   			
			}
			if(isset($_GET['user_type']) && (int)$_GET['user_type']>0)
			{
				$this->db->where('user_type', $_GET['user_type']);   			
			}
			$this->db->where('user_type != ',0);
			$this->db->from($this->db->table_users);
			$this->db->limit($limit, $offset);
			$this->db->order_by("user_name", "asc");
            $result = $this->db->get();
			$result = $result->result_array();
			return $result;
		}
		public function totalNoOfDealers()
		{
			$this->db->select('*');	
			if(isset($_GET['search']) && strlen($_GET['search'])>0)
			{
				$this->db->or_like('user_name',$_GET['search'], 'both');   
				$this->db->or_like('user_email',$_GET['search'], 'both');   			
			}
			if(isset($_GET['user_type']) && (int)$_GET['user_type']>0)
			{
				$this->db->where('user_type', $_GET['user_type']);   			
			}
			$this->db->where('user_type != ',0);
			$this->db->from($this->db->table_users);
			
            $result = $this->db->count_all_results();	
			return $result;
		}

		public function listofSerialNumbers($limit,$offset,$search='')
		{
			$this->db->select('ser.s_serial_id,ser.s_serial_number,com.c_company_name,us.user_name,ser.s_user_type,ser.s_used');	
			
			if(strlen($search)>0)
			{
				$this->db->or_like('ser.s_serial_number',$search, 'both');   
			}	
			if(isset($_GET['used_status']) && strlen($_GET['used_status'])>0)
			{
				$this->db->where('s_used',$_GET['used_status']);  
			}
			$this->db->from($this->db->table_serial_no.' as ser');	
			$this->db->join($this->db->table_company.' as com', 'com.c_company_id = ser.s_company_id', 'left');				
			$this->db->join($this->db->table_users.' as us', 'us.user_id = ser.s_user_id', 'left');				
			$this->db->limit($limit, $offset);
			$this->db->order_by("ser.s_serial_number", "asc");
            $result = $this->db->get();
			$result = $result->result_array();
			return $result;
		}

		public function listofVehicleModels($limit,$offset,$search='')
		{
			$this->db->select('mo.ve_model_id,mo.ve_make_id,mo.ve_model_name,mk.v_make_name');	
			if(isset($_GET['make_id']) && strlen($_GET['make_id'])>0)
			{
				$this->db->where('ve_make_id',$_GET['make_id'], 'both');  
			}
			if(strlen($search)>0)
			{
				$this->db->or_like('ve_model_name',$search, 'both');   
			}	
				
			$this->db->from($this->db->table_model.' as mo');	
			$this->db->join($this->db->table_make.' as mk', 'mk.v_make_id = mo.ve_make_id', 'left');				
			$this->db->limit($limit, $offset);
			$this->db->order_by("ve_model_name", "asc");
            $result = $this->db->get();
			$result = $result->result_array();
			//echo $this->db->last_query();exit();
			return $result;
		}

		public function totalNoOfvehicleModel()
		{
			$this->db->select('*');	
			if(isset($_GET['make_id']) && strlen($_GET['make_id'])>0)
			{
				$this->db->where('ve_make_id',$_GET['make_id'], 'both');  
			}
			if(isset($_GET['search']) && strlen($_GET['search'])>0)
			{
				$this->db->or_like('ve_model_name',$_GET['search'], 'both');  
			}	
					
			$this->db->from($this->db->table_model);			
            $result = $this->db->count_all_results();	
			return $result;
		}

		public function getModelInfo($modelID)
		{
			$this->db->select('*');	
			$this->db->where('ve_model_id = ',$modelID);
			$this->db->from($this->db->table_model);	
			$result = $this->db->get();		
            $result = $result->row_array();			
			return $result;
		}

			public function getRtoInfo($RTONo)
		{
			$this->db->select('*');	
			$this->db->where('rto_no = ',$RTONo);
			$this->db->from($this->db->table_rto);	
			$result = $this->db->get();		
            $result = $result->row_array();			
			return $result;
		}

		public function getSerialNumberInfo($serial_number)
		{
			$this->db->select('ser.*,ue.user_name');	
			$this->db->where('ser.s_serial_id = ',$serial_number);
			$this->db->from($this->db->table_serial_no.' as ser');	
			$this->db->join($this->db->table_users.' as ue', 'ser.s_user_id = ue.user_id', 'left');	
			$result = $this->db->get();		
            $result = $result->row_array();			
			return $result;
		}



		public function totalNoOfvehicleMake()
		{
			$this->db->select('*');	
			if(isset($_GET['search']) && strlen($_GET['search'])>0)
			{
				$this->db->or_like('v_make_name',$_GET['search'], 'both');  
			}		
			$this->db->from($this->db->table_make);			
            $result = $this->db->count_all_results();	
			return $result;
		}

		public function totalNoOfRTO()
		{
			$this->db->select('*');	
			if(isset($_GET['search']) && strlen($_GET['search'])>0)
			{
				$this->db->or_like('rto_place',$_GET['search'], 'both');  
				$this->db->or_like('rto_number',$_GET['search'], 'both');  
			}		
			$this->db->from($this->db->table_rto);			
            $result = $this->db->count_all_results();	
			return $result;
		}



		public function listofRtoList($limit,$offset,$search='')
		{
			$this->db->select('*');	
			if(isset($_GET['search']) && strlen($_GET['search'])>0)
			{
				$this->db->or_like('rto_place',$_GET['search'], 'both');  
				$this->db->or_like('rto_number',$_GET['search'], 'both');  
			}	
			
			$this->db->from($this->db->table_rto);	
			$this->db->limit($limit, $offset);
			$this->db->order_by("rto_number", "asc");
            $result = $this->db->get();
			$result = $result->result_array();
			//echo $this->db->last_query();exit();
			return $result;
		}

		public function totalNoOfCompanys()
		{
			$this->db->select('*');	
			if(isset($_GET['search']) && strlen($_GET['search'])>0)
			{
				$this->db->or_like('c_company_name',$_GET['search'], 'both');  
				$this->db->or_where("FIND_IN_SET('$search',c_tac_no) !=", 0); 
			}		
			$this->db->from($this->db->table_company);			
            $result = $this->db->count_all_results();	
			return $result;
		}

		public function totalNoOfSerialNumbers()
		{
			$this->db->select('*');	

			
			if(isset($_GET['search']) && strlen($_GET['search'])>0)
			{
				$this->db->or_like('s_serial_number',$_GET['search'], 'both');  
			}	
			if(isset($_GET['used_status']) && strlen($_GET['used_status'])>0)
			{
				$this->db->where('s_used',$_GET['used_status']);  
			}	
			$this->db->from($this->db->table_serial_no);			
            $result = $this->db->count_all_results();
            //echo $this->db->last_query();exit();	
			return $result;
		}


		public function updateUserInfo($params)
		{
			$insertRecords=array();			
			$insertRecords['user_password']=isset($params['user_password'])?$params['user_password']:"";			
			$this->db->where('user_id', $params['user_id']);
			$this->db->update($this->db->table_users,$insertRecords);
			return 1;
		}

		public function getUserInfo($params)
		{
			$this->db->select('*');	
			$this->db->where('user_email = ',$params['email']);
			$this->db->from($this->db->table_users);	
			$result = $this->db->get();		
            $result = $result->row_array();			
			return $result;
		}

			public function getUserInfobyid($id)
		{
			$this->db->select('*');	
			$this->db->where('user_id = ',$id);
			$this->db->from($this->db->table_users);	
			$result = $this->db->get();		
            $result = $result->row_array();			
			return $result;
		}


			public function verify_exits_rto_number($rto_number,$id)
		{
			$this->db->select('*');	
			$this->db->where('rto_number',$rto_number);
			if(strlen($id)>0)
			{
				$this->db->where('rto_no !=',$id);
			}
			$this->db->from($this->db->table_rto);
            $result = $this->db->get();
			$result = $result->result_array();		
			if(empty($result))
			{
				return true;
			}
			return false;
		}


		// Verify Exits Company Name
		public function verify_exits_model_make_records($model_name,$make_id,$id)
		{
			$this->db->select('*');	
			$this->db->where('ve_model_name',$model_name);
			$this->db->where('ve_make_id',$make_id);
			if(strlen($id)>0)
			{
				$this->db->where('ve_model_id !=',$id);
			}
			$this->db->from($this->db->table_model);
            $result = $this->db->get();
			$result = $result->result_array();		
			if(empty($result))
			{
				return true;
			}
			return false;
		}

		// Verify Exits Company Name
		public function verify_exits_company_name($c_company_name,$id="")
		{
			$this->db->select('*');	
			$this->db->where('c_company_name',$c_company_name);
			if(strlen($id)>0)
			{
				$this->db->where('c_company_id !=',$id);
			}
			$this->db->from($this->db->table_company);
            $result = $this->db->get();
			$result = $result->result_array();			
			if(empty($result))
			{
				return true;
			}
			return false;
		}
			// Verify Exits Make Name
		public function verify_exits_make_name($make_name,$id="")
		{
			$this->db->select('*');	
			$this->db->where('v_make_name',$make_name);
			if(strlen($id)>0)
			{
				$this->db->where('v_make_id !=',$id);
			}
			$this->db->from($this->db->table_make);
            $result = $this->db->get();
			$result = $result->result_array();			
			if(empty($result))
			{
				return true;
			}
			return false;
		}

		// Verify Exits Company Name
		public function verify_exits_serial_number($serial_numbers,$id="")
		{

			$params=array();
			$serial_numbers=explode(',', $serial_numbers);
			$params['serial_numbers']=array_values(array_filter(array_unique($serial_numbers)));
			
			$this->db->select('s_serial_number');	
			foreach ($params['serial_numbers'] as $key => $value) {
				$this->db->where('s_serial_number',$value);
				if(strlen($id)>0)
				{
					$this->db->where('s_serial_id !=',$id);
				}
			}
			
			$this->db->from($this->db->table_serial_no);
            $result = $this->db->get();
			$result = $result->result_array();
			//echo $this->db->last_query();exit();
			return $result;
		}

		// Verify Exits Company Name
		public function verify_exits_company_tac_number($tac_number_list,$id="")
		{
			$params['c_tac_no']=array_values(array_filter(array_unique($tac_number_list)));
			
			$this->db->select('c_tac_no');	
			foreach ($params['c_tac_no'] as $key => $value) {
				$this->db->or_where("FIND_IN_SET('$value',c_tac_no) !=", 0);
				if(strlen($id)>0)
				{
					$this->db->where('c_company_id !=',$id);
				}
			}
			
			$this->db->from($this->db->table_company);
            $result = $this->db->get();
			$result = $result->result_array();
			return $result;
		}
		
		public function verify_exits_dealer_phone_number($phone_number,$id="")
		{
			$this->db->select('*');	
			$this->db->where('user_phone',$phone_number);
			if(strlen($id)>0)
			{
				$this->db->where('user_id !=',$id);
			}
			$this->db->from($this->db->table_users);
            $result = $this->db->get();
			$result = $result->result_array();
			if(empty($result))
			{
				return true;
			}
			return false;
		}
		
		public function verify_exits_dealer_email($user_email,$id="")
		{			
			$this->db->select('*');	
			$this->db->where('user_email',$user_email);	
			if(strlen($id)>0)
			{
				$this->db->where('user_id !=',$id);
			}
			$this->db->from($this->db->table_users);
            $result = $this->db->get();
			$result = $result->result_array();
			if(empty($result))
			{
				return true;
			}
			return false;
		}

		public function getCompanyInfo($id)
		{
			$this->db->select('*');	
			$this->db->where('c_company_id',$id);	
			$this->db->from($this->db->table_company);
            $result = $this->db->get();
			$result = $result->row_array();
			return $result;
		}

		public function getMakeInfo($id)
		{
			$this->db->select('*');	
			$this->db->where('v_make_id',$id);	
			$this->db->from($this->db->table_make);
            $result = $this->db->get();
			$result = $result->row_array();
			return $result;
		}
		
		public function getDealerInfo($id)
		{

		
			$this->db->select('one.*,two.user_id as dis_id,two.user_name as dis_name');	
			$this->db->where('one.user_id',$id);	
			$this->db->from($this->db->table_users.' as one');
			$this->db->join($this->db->table_users.' as two', 'one.user_distributor_id = two.user_id', 'left');	
            $result = $this->db->get();
			$result = $result->row_array();
			return $result;
		}
		
		public function verify_exits_vehicle_records($value,$field,$id=0)
		{			
			$this->db->select('*');	
			$this->db->where($field,$value);
			if((int)$id > 0)
			{
				$this->db->where('veh_id !=',$id);
			}
			$this->db->from($this->db->table_vehicle);
            $result = $this->db->get();			
			$result = $result->result_array();						
			if(empty($result))
			{
				return true;
			}
			return false;
		}
		
		public function listofvehicle($limit,$offset,$search='',$user_id=0)
		{
			$this->db->select('veh.*,ser.s_serial_number');	
			
			if(strlen($search)>0)
			{
				$this->db->or_like('veh.veh_rc_no',$search, 'both');   
				$this->db->or_like('veh.veh_chassis_no',$search, 'both');   				
				$this->db->or_like('veh.veh_serial_no',$search, 'both');   
				$this->db->or_like('veh.veh_invoice_no',$search, 'both');   
			}
			if((int)$user_id !=1)
			{
				$this->db->where('veh.veh_created_user_id',$user_id);
			}
			if($_GET['start_date'] !=0 && $_GET['end_date'] !=0)
			{
				$from=$_GET['start_date'];
				$to=$_GET['end_date'];
				if(strtotime($_GET['end_date']) < strtotime($_GET['start_date']))
				{
					$from=$_GET['end_date'];
					$to=$_GET['start_date'];
				}
				$this->db->where('veh.veh_create_date >=', $from);
				$this->db->where('veh.veh_create_date <=', $to);
			}elseif($_GET['start_date'] !=0){
				$this->db->where('veh.veh_create_date >=', $_GET['start_date']);
				$this->db->where('veh.veh_create_date <=', date('Y-m-d'));
			}elseif($_GET['end_date'] !=0){
				$this->db->where('veh.veh_create_date >=', date('Y-m-d'));
				$this->db->where('veh.veh_create_date <=', $_GET['end_date']);
			}
			$this->db->from($this->db->table_vehicle.' as veh');
			$this->db->join($this->db->table_serial_no.' as ser', 'ser.s_serial_id = veh.veh_serial_no', 'left');	

			$this->db->limit($limit, $offset);
			$this->db->order_by("veh.veh_invoice_no", "asc");
            $result = $this->db->get();
			$result = $result->result_array();		
			//echo $this->db->last_query();exit();
			return $result;
		}
		
		public function totalNoOfVehicle($user_id=0)
		{
			$this->db->select('*');	
			if((int)$user_id !=1)
			{
				$this->db->where('veh_created_user_id',$user_id);
			}
			if($_GET['start_date'] !=0 && $_GET['end_date'] !=0)
			{
				$from=$_GET['start_date'];
				$to=$_GET['end_date'];
				if(strtotime($_GET['end_date']) < strtotime($_GET['start_date']))
				{
					$from=$_GET['end_date'];
					$to=$_GET['start_date'];
				}
				$this->db->where('veh_create_date >=', $from);
				$this->db->where('veh_create_date <=', $to);
			}elseif($_GET['start_date'] !=0){
				$this->db->where('veh_create_date >=', $_GET['start_date']);
				$this->db->where('veh_create_date <=', date('Y-m-d'));
			}elseif($_GET['end_date'] !=0){
				$this->db->where('veh_create_date >=', date('Y-m-d'));
				$this->db->where('veh_create_date <=', $_GET['end_date']);
			}
			$this->db->from($this->db->table_vehicle);			
            $result = $this->db->count_all_results();			
			return $result;
		}
		
		public function getVehicleInfo($id,$user=0)
		{
			$this->db->select('veh.*,mod.ve_model_name,ser.s_serial_number');	
			$this->db->where('veh_id',$id);	
			$this->db->from($this->db->table_vehicle.' as veh');
			$this->db->join($this->db->table_model.' as mod', 'veh.veh_model_no = mod.ve_model_id', 'left');	
			$this->db->join($this->db->table_serial_no.' as ser', 'ser.s_serial_id = veh.veh_serial_no', 'left');	
            $result = $this->db->get();
			$result = $result->row_array();
			
			return $result;
		}

		public function getPdfVehicleInfo($id)
		{
			$this->db->select('veh.*,ue.user_info,rto.rto_number,rto.rto_place,com.c_company_name,mke.v_make_name,ser.s_serial_number,model.ve_model_name');	
			$this->db->where('veh.veh_id',$id);	
			$this->db->from($this->db->table_vehicle.' veh');
			$this->db->join($this->db->table_users.' ue', 'veh.veh_created_user_id = ue.user_id', 'left');		
			$this->db->join($this->db->table_rto.' rto', 'rto.rto_no = veh.veh_rto_no', 'left');		
			$this->db->join($this->db->table_company.' com', 'com.c_company_id = veh.veh_company_id', 'left');		
			$this->db->join($this->db->table_make.' mke', 'mke.v_make_id = veh.veh_make_no', 'left');		
			$this->db->join($this->db->table_serial_no.' ser', 'ser.s_serial_id = veh.veh_serial_no', 'left');		
			$this->db->join($this->db->table_model.' model', 'model.ve_model_id = veh.veh_model_no', 'left');		
            $result = $this->db->get();
          //  echo $this->db->last_query();exit();
			$result = $result->row_array();
			
			return $result;
		}
}