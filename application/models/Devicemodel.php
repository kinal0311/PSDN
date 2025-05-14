<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DeviceModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function checkCodeExists($params)
	{
        $this->db->select('*');
        // $this->db->select('s_serial_id,s_serial_number,s_imei,s_mobile,s_user_type,admin_price,distributor_price,s_created_date,assign_to_distributer_on,p_product_id,p_company_id,p_product_name,p_unit_price,p_product_description,p_created_date');
		$this->db->where('s_imei', $params[0]);
		// $this->db->where('s_iccid', $params[1]);
		// $this->db->where('s_serial_number', $params[2]);


		$this->db->from($this->db->table_serial_no);
        $result = $this->db->get();
		$result = $result->row_array();
// 		echo $this->db->last_query();exit();
		return $result;
	}

	public function saveSerialNumber($params,$serialNumber)
	{
		$insert_data = [
			's_company_id'      => 2,
			's_product_id'      => 1,
			's_serial_number'   => $serialNumber,
			's_imei'            => trim($params[0]),
			's_iccid'           => trim($params[1]),
			's_user_type'       => $this->session->userdata('user_type'),
			's_user_id'         => $this->session->userdata('user_id'),
			'admin_price'       => 4500,
			'distributor_price' => 0,
			'dealer_price'      => 0,
			's_created_date'    => date('Y-m-d H:i:s'),
			's_created_by'      => 1,
			'inScan'            => '1',
		];

		$this->db->insert($this->db->table_serial_no, $insert_data);
// 		echo $this->db->last_query();exit();
		return $this->db->affected_rows() > 0;
	}

    public function updateSerialNumber($newSerialNumber) {
        // Assuming your table name is "your_table" and serial_number is the column name
        $this->db->update('ci_serial_numbers_autoincrement', ['serial_number' => $newSerialNumber]);
    }

    public function getCurrentSerialNumber() {
        $query = $this->db->get('ci_serial_numbers_autoincrement');
        $row = $query->row();
        return $row;
    }
    

    public function totalNoOfSerialNos()
    {
        //$this->db->select('*');
        $this->db->select('count(*)');
        if (isset($_GET['search']) && strlen($_GET['search']) > 0) {
            $this->db->or_like('s_imei', $_GET['search'], 'both');
            $this->db->or_like('s_iccid', $_GET['search'], 'both');
            $this->db->or_like('s_serial_number', $_GET['search'], 'both');
        }
        if (isset($_GET['s_country_id']) && strlen($_GET['s_country_id']) > 0) {
            $this->db->where('s_country_id', $_GET['s_country_id'], 'both');
        }
        if (isset($_GET['s_state_id']) && strlen($_GET['s_state_id']) > 0) {
            $this->db->where('s_state_id', $_GET['s_state_id'], 'both');
        }
        $this->db->where('inScan', '1');
        $this->db->from($this->db->table_serial_no);
        $result = $this->db->count_all_results();
        //echo "<pre>"; print_r($result); exit;
        return $result;
    }

    public function listofSerialNos($limit = '', $offset = '', $search = '')
    {
        // $this->db->select('*');
        $this->db->select('s_serial_id, s_imei, s_iccid, s_serial_number, s_mobile, s_mobile_2, s_created_date');
        if (isset($_GET['search']) && strlen($_GET['search']) > 0) {
            $limit = 25;
            $offset = 0;
            $this->db->or_like('s_imei', $_GET['search'], 'both');
            $this->db->or_like('s_iccid', $_GET['search'], 'both');
            $this->db->or_like('s_serial_number', $_GET['search'], 'both');
        }
        $this->db->where('inScan', '1');
        if (isset($_GET['s_country_id']) && strlen($_GET['s_country_id']) > 0) {
            $this->db->where('s_country_id', $_GET['s_country_id'], 'both');
        }
        if (isset($_GET['s_state_id']) && strlen($_GET['s_state_id']) > 0) {
            $this->db->where('s_state_id', $_GET['s_state_id'], 'both');
        }
		$this->db->select('s_serial_id, s_imei, s_iccid, s_serial_number, s_mobile, s_mobile_2, s_created_date');
		$this->db->from($this->db->table_serial_no);
		$this->db->order_by('s_serial_id','desc');
		// $limit = 10;
		$this->db->limit($limit, $offset);
		$result = $this->db->get();
		$result = $result->result_array();
        // echo "<pre>";print_r($this->db->last_query());exit();
		// echo "<pre>"; print_r($result); exit;
        return $result;
    }
    
    public function listofCompleteSerial($limit = '', $offset = '', $search = '')
    {
        // $this->db->select('*');
        $this->db->select('s_serial_id, s_imei, s_iccid, s_serial_number, s_mobile, s_mobile_2, s_created_date');
        if (isset($_GET['search']) && strlen($_GET['search']) > 0) {
            $limit = 25;
            $offset = 0;
            $this->db->or_like('s_imei', $_GET['search'], 'both');
            $this->db->or_like('s_iccid', $_GET['search'], 'both');
            $this->db->or_like('s_serial_number', $_GET['search'], 'both');
        }
        if (isset($_GET['s_country_id']) && strlen($_GET['s_country_id']) > 0) {
            $this->db->where('s_country_id', $_GET['s_country_id'], 'both');
        }
        if (isset($_GET['s_state_id']) && strlen($_GET['s_state_id']) > 0) {
            $this->db->where('s_state_id', $_GET['s_state_id'], 'both');
        }
        $this->db->where('s_distributor_id', '0');
        $this->db->where('s_used', '0');
        $this->db->where('inScan', '0');

		$this->db->select('s_serial_id, s_imei, s_iccid, s_serial_number, s_mobile, s_mobile_2, s_created_date');
		$this->db->from($this->db->table_serial_no);
		$this->db->order_by('s_serial_id','desc');
		// $limit = 10;
		$this->db->limit($limit, $offset);
		$result = $this->db->get();
		$result = $result->result_array();
		//echo "<pre>"; print_r($result); //exit;
        return $result;
    }
    
//     public function listofSerialNos($limit = '', $offset = '', $search = '')
//     {
//         $this->db->select('*');
//         if (isset($_GET['search']) && strlen($_GET['search']) > 0) {
//             $this->db->or_like('s_imei', $_GET['search'], 'both');
//             $this->db->or_like('s_iccid', $_GET['search'], 'both');
//             $this->db->or_like('s_serial_number', $_GET['search'], 'both');
//         }
//         $this->db->where('inScan', '1');

// 		$this->db->select('s_serial_id, s_imei, s_iccid, s_serial_number, s_mobile, s_mobile_2');
// 		$this->db->from($this->db->table_serial_no);
// 		$this->db->order_by('s_serial_id','desc');
// 		$this->db->limit($limit, $offset);
// 		$result = $this->db->get();
// 		$result = $result->result_array();
// 		//echo "<pre>"; print_r($result); exit;
//         return $result;
//     }

    public function getUserInfo($user_id){
        // echo "<pre>";print_r($user_id);exit;
        $this->db->select('user_id,user_name,user_phone,user_type');
        $this->db->where('user_id',$user_id);
        $this->db->from($this->db->table_users);
        $result = $this->db->get();
        $result = $result->row_array();
        // echo "<pre>";print_r($result);exit;
        return $result;
        
        
    }

    public function totalNoOfUnregisteredDatas($search, $stateId)
    {
        // $DB2 = $this->load->database('postgre_db', TRUE);
        $mainDB = $this->load->database('default', TRUE);
        $result = $mainDB
        ->select('*') // Add columns from the state table as needed
        ->from($mainDB->table_state)
        ->where('id', $stateId)
        ->get()
        ->row_array();
        if(!empty($result) && !empty($result['s_key'])) {

        $databaseName = 'PSDN_' . $result['s_key'];
        $trimmedStr = trim($databaseName);
        $DB2 = $this->load->database($trimmedStr, TRUE);

        $where = '';
        if ($search != '') {
        	$where = "where imei = '" . $search . "'";
        }


        //$query = "select * from public.tbl_unregistered_device_data";
        $query = "select id,imei,data,created_time from public.tbl_unregistered_device_data " . $where  . " ";
        // echo $query; exit;  
        return $DB2->query($query)->num_rows();
    }else{
        $row = array();
        return $row;
    }

    }

    public function listOfUnregisteredDatas($limit, $offset, $search = '', $stateId)
    {
        // $DB2 = $this->load->database('postgre_db', TRUE);
        $mainDB = $this->load->database('default', TRUE);
        $result = $mainDB
        ->select('*') // Add columns from the state table as needed
        ->from($mainDB->table_state)
        ->where('id', $stateId)
        ->get()
        ->row_array();

        if(!empty($result) && !empty($result['s_key'])) {
            $databaseName = 'PSDN_' . $result['s_key'];
            $trimmedStr = trim($databaseName);
            $DB2 = $this->load->database($trimmedStr, TRUE);
    
            $where = '';
            if ($search != '') {
                $where = "where imei = '" . $search . "'";
            }
    
            //$query = "select * from public.tbl_unregistered_device_data " . $where  . " ORDER BY id DESC OFFSET " . $offset . " LIMIT " . $limit;
            // $query = "select id,imei,data,created_time from public.tbl_unregistered_device_data " . $where  . " ORDER BY id DESC OFFSET " . $offset . " LIMIT " . $limit;
            $query = "select id,imei,data,created_time from public.tbl_unregistered_device_data " . $where  . " ORDER BY id DESC  LIMIT " . $limit ." OFFSET " . $offset;
            //  echo "<pre>";print_r($query);exit();
            $data = $DB2->query($query)->result_array();
            // echo "<pre>"; print_r($data); exit;
            return $data;
            // return $DB2->query($query)->result_array();
        }else{
            $row = array();
            return $row;
        }

    }
    
    public function totalNoOfOtaStatusDatas($search){
        $DB2 = $this->load->database('postgre_db', TRUE);

        $where = '';
        if ($search != '') {
            $where = ' where "IMEI" = \'' . $search . '\'';
        }
        
         $query = "select count(*) AS total_count  from public.tbl_ota_device_ack"  . $where  . " ";
        //   echo "<pre>";print_r($query);exit();
         $result = $DB2->query($query)->row();
         return $result->total_count;
    }
    
     public function listOfOtaStatusDatas($limit, $offset, $search = '')
    {
        $DB2 = $this->load->database('postgre_db', TRUE);

        $where = '';
        if ($search != '') {
            $where = ' where "IMEI" = \'' . $search . '\'';
        }

        //$query = "select * from public.tbl_unregistered_device_data " . $where  . " ORDER BY id DESC OFFSET " . $offset . " LIMIT " . $limit;
        $query = "select * from public.tbl_ota_device_ack " . $where  . " ORDER BY id DESC OFFSET " . $offset . " LIMIT " . $limit;
        // $query = "select id,imei,data,created_time from public.tbl_unregistered_device_data " . $where  . " ORDER BY id DESC  LIMIT " . $limit ." OFFSET " . $offset;

    //  	echo "<pre>";print_r($DB2->query($query)->result_array());exit();
        return $DB2->query($query)->result_array();
    }
    
     public function totalNoOfOtaOutboxDatas($search){
        $DB2 = $this->load->database('postgre_db', TRUE);

        $where = '';
        if ($search != '') {
            $where = ' where "IMEI" = \'' . $search . '\'';
        }
        
         $query = "select count(*) AS total_count  from public.tbl_ota_param"  . $where  . " ";
        //   echo "<pre>";print_r($query);exit();
         $result = $DB2->query($query)->row();
         return $result->total_count;
    }
    
     public function listOfOtaOutboxDatas($limit, $offset, $search = '')
    {
        $DB2 = $this->load->database('postgre_db', TRUE);

        $where = '';
        if ($search != '') {
            $where = ' where "IMEI" = \'' . $search . '\'';
        }

        //$query = "select * from public.tbl_unregistered_device_data " . $where  . " ORDER BY id DESC OFFSET " . $offset . " LIMIT " . $limit;
        $query = "select * from public.tbl_ota_param " . $where  . " ORDER BY id DESC OFFSET " . $offset . " LIMIT " . $limit;
        // $query = "select id,imei,data,created_time from public.tbl_unregistered_device_data " . $where  . " ORDER BY id DESC  LIMIT " . $limit ." OFFSET " . $offset;

    //  	echo "<pre>";print_r($DB2->query($query)->result_array());exit();
        return $DB2->query($query)->result_array();
    }
    
    public function getNamesByIds($ids) {
        $this->db->select('user_id, user_name');
        $this->db->where_in('user_id', $ids);
        $this->db->from($this->db->table_users);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function totalNoOfregisteredDatas($search)
    {
        // $DB2 = $this->load->database('postgre_db', TRUE);
        $mainDB = $this->load->database('default', TRUE);
        $result = $mainDB
        ->select('s_imei, s_state_id, s_country_id, state.s_key') // Add columns from the state table as needed
        ->from($mainDB->table_serial_no)
        ->where('s_imei', $search)
        ->join($mainDB->table_state, 'state.id = ' . $mainDB->table_serial_no . '.s_state_id', 'left') // Adjust the join condition accordingly
        ->get()
        ->row_array();
        $databaseName = 'PSDN_' . $result['s_key'];
        $trimmedStr = trim($databaseName);
        $DB2 = $this->load->database($trimmedStr, TRUE);

        $where = '';
            if ($search != '') {
            	$where = "where imei = '" . $search . "'";
        }
        
        $query = "SELECT COUNT(*) AS total_count FROM public.tbl_registered_device_data  " . $where  . " ";
        $result = $DB2->query($query)->row();
		// echo "<pre>"; print_r($result); exit;

        return $result->total_count;
    }
    
    public function listOfregisteredDatas($limit, $offset, $search = '')
    {
        // $DB2 = $this->load->database('postgre_db', TRUE);
        $mainDB = $this->load->database('default', TRUE);
        $result = $mainDB
        ->select('s_imei, s_state_id, s_country_id, state.s_key') // Add columns from the state table as needed
        ->from($mainDB->table_serial_no)
        ->where('s_imei', $search)
        ->join($mainDB->table_state, 'state.id = ' . $mainDB->table_serial_no . '.s_state_id', 'left') // Adjust the join condition accordingly
        ->get()
        ->row_array();
        $databaseName = 'PSDN_' . $result['s_key'];
        
        $trimmedStr = trim($databaseName);
        $DB2 = $this->load->database($trimmedStr, TRUE);

        $where = '';
        if ($search != '') {
        	$where = "where imei = '" . $search . "'";
        }

        //$query = "select * from public.tbl_unregistered_device_data " . $where  . " ORDER BY id DESC OFFSET " . $offset . " LIMIT " . $limit;
        $query = "select id,imei,data,created_time from public.tbl_registered_device_data " . $where  . " ORDER BY id DESC OFFSET " . $offset . " LIMIT " . $limit;
        // $query = "select id,imei,data,created_time from public.tbl_unregistered_device_data " . $where  . " ORDER BY id DESC  LIMIT " . $limit ." OFFSET " . $offset;
        $result = $DB2->query($query)->result_array();
     	// echo "<pre>";print_r($result);exit();
        return $result;
    }

    public function checkIccidExists($iccid)
    {
        // 	$this->db->select('*');
    	$this->db->select('count(*)');
        $this->db->where('s_iccid', $iccid);
        $this->db->from($this->db->table_serial_no);
        return $this->db->count_all_results();
    }

    public function addMobileNumbersToIccid($iccid, $mobile1, $mobile2)
    {
    	$updataData = [
    		's_mobile'   => $mobile1,
    		's_mobile_2' => $mobile2,
    		'inScan'     => 0,
    	];

        $this->db->where('s_iccid', $iccid);
        $this->db->update($this->db->table_serial_no, $updataData);

        return $this->db->affected_rows();
    }
    
     public function searchByIMEI($imei) {
        // Replace 'your_table_name' with your actual table name
        $DB2    = $this->load->database('postgre_db', TRUE);
        $query  = "select id,imei,data,created_time from public.tbl_registered_device_data where created_time >= NOW();";
        $result = $DB2->query($query)->result_array();
        return $result;
    }
    
}