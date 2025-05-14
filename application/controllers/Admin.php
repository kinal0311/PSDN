<?php

defined('BASEPATH') OR exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, OPTIONS");
require_once FCPATH . 'vendor/autoload.php';
use Aws\S3\S3Client;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
error_reporting(E_ALL);
// ini_set('display_errors', 1);
class Admin extends CI_Controller
{


    // Load Constructur

    public function __construct()
    {

        parent::__construct();

        $this->load->model('adminmodel');

        $this->load->model('commonmodel');

        $this->load->library("pagination");

        $this->load->library('session');
    }

    public function import_customers() {
        // File validation
        if (!$_FILES['excel_file']) {
            $message = "Error: No file uploaded or there was an error uploading the file.";
            $this->session->set_flashdata('error', $message);
            redirect(base_url() . 'admin/customers_list', 'refresh');
        }
        
        $file = $_FILES['excel_file'];
        $allowedMimeTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'];
        $maxFileSize = 10 * 1024 * 1024; // 10MB max file size
    
        // Check if file is uploaded
        if (!$file || $file['error'] != UPLOAD_ERR_OK) {
            $message = "Error: No file uploaded or there was an error uploading the file.";
            $this->session->set_flashdata('error', $message);
            redirect(base_url() . 'admin/customers_list', 'refresh');
        }
    
        // Check file type
        if (!in_array($file['type'], $allowedMimeTypes)) {
            $message = "Error: Invalid file type. Please upload an Excel file (.xlsx or .xls).";
            $this->session->set_flashdata('error', $message);
            redirect(base_url() . 'admin/customers_list', 'refresh');
        }
    
        // Check file size
        if ($file['size'] > $maxFileSize) {
            $message = "Error: File size exceeds the maximum limit of 10MB.";
            $this->session->set_flashdata('error', $message);
            redirect(base_url() . 'admin/customers_list', 'refresh');
        }
    
        // Load the file using PhpSpreadsheet
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file['tmp_name']);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
    
        // Validate sheet structure (skip header row)
        $headerRow = $sheetData[1]; // First row (header)
        
        // Check if required columns are present
        $requiredColumns = ['A', 'B', 'C', 'D'];
        foreach ($requiredColumns as $col) {
            if (!isset($headerRow[$col])) {
                if($col =="A"){
                    $col_name = 'customer_name';
                }else if($col =="B"){
                    $col_name = 'phone';
                }else if($col == "C"){
                    $col_name = 'address';
                }else{
                    $col_name = 'email';
                }
                $message = "Error: Missing required column ''$col_name'' in the Excel file.";
                $this->session->set_flashdata('error', $message);
                redirect(base_url() . 'admin/customers_list', 'refresh');
            }
        }
    
        // Prepare for data insertion
        $insertRecords = [];
        $time = date("Y-m-d H:i:s");
        $existingPhones = []; // Array to hold existing phone numbers for quick check
        $errors = []; // Array to hold errors for failed rows
    
        // Fetch existing phone numbers from the database
        $this->db->select('c_phone');
        $existingPhonesQuery = $this->db->get($this->db->table_customers)->result_array();
        foreach ($existingPhonesQuery as $row) {
            $existingPhones[] = $row['c_phone'];
        }
    
        // Process each row in the Excel sheet, starting from row 2 to skip the header
        for ($rowNumber = 2; $rowNumber <= count($sheetData); $rowNumber++) {
            $row = $sheetData[$rowNumber];
    
            $c_phone = isset($row['B']) ? $row['B'] : "";
    
            // Validate fields - add error message if validation fails
            if (empty($c_phone)) {
                $errors[] = "Row $rowNumber: Phone number is missing.";
                continue;
            }
            if (in_array($c_phone, $existingPhones)) {
                $errors[] = "Row $rowNumber: Phone number $c_phone already exists.";
                continue;
            }
    
            // Build a single insert record
            $insertRecords[] = [
                'c_customer_name' => isset($row['A']) ? $row['A'] : "",
                'c_phone' => $c_phone,
                'c_address' => isset($row['C']) ? $row['C'] : "",
                'c_email' => isset($row['D']) ? $row['D'] : NULL,
            ];
    
            // Add phone to existingPhones to prevent duplicates within the current import
            $existingPhones[] = $c_phone;
        }
    
        // Insert valid records in batch
        if (!empty($insertRecords)) {
            $this->db->insert_batch($this->db->table_customers, $insertRecords);
        }
    
        // Return or log the number of records added and any error messages
        $message1 = count($insertRecords) . " records were successfully added.\n";
    
        if (!empty($errors)) {
            $message .= "Some records failed to insert due to the following errors:\n";
            $message .= implode("\n", $errors); // Join errors by line
            $this->session->set_flashdata('error', $message);
            redirect(base_url() . 'admin/customers_list', 'refresh');
        } else {
            $this->session->set_flashdata('success', $message1);
            redirect(base_url() . 'admin/customers_list', 'refresh');
        }
    }
    
    public function import_stock_list() {
        if (!$_FILES['excel_file']) {
            $message = "Error: No file uploaded or there was an error uploading the file.";
            $this->session->set_flashdata('error', $message);
            redirect(base_url() . 'admin/scan_bulk_upload', 'refresh');
        }
        $file = $_FILES['excel_file'];
        $allowedMimeTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'];
        $maxFileSize = 10 * 1024 * 1024; // 10MB max file size
    
        if (!$file || $file['error'] != UPLOAD_ERR_OK) {
            $message = "Error: No file uploaded or there was an error uploading the file.";
            $this->session->set_flashdata('error', $message);
            redirect(base_url() . 'admin/scan_bulk_upload', 'refresh');
        }
        if (!in_array($file['type'], $allowedMimeTypes)) {
            $message = "Error: Invalid file type. Please upload an Excel file (.xlsx or .xls).";
            $this->session->set_flashdata('error', $message);
            redirect(base_url() . 'admin/scan_bulk_upload', 'refresh');
        }
    
        if ($file['size'] > $maxFileSize) {
            $message = "Error: File size exceeds the maximum limit of 10MB.";
            $this->session->set_flashdata('error', $message);
            redirect(base_url() . 'admin/scan_bulk_upload', 'refresh');
        }
         
        try {
           
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file['tmp_name']);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
     // echo "ddsfsd";exit;
        // Validate sheet structure (skip header row)
        $headerRow = $sheetData[1];
        $requiredColumns = ['A', 'B', 'C', 'D', 'E'];
        $fieldMap = [
            'A' => 'serial_number',
            'B' => 'imei',
            'C' => 'iccid',
            'D' => 'sim_number_1',
            'E' => 'sim_number_2',
        ];
    
        foreach ($requiredColumns as $col) {
            if (!isset($headerRow[$col])) {
                $colName = $fieldMap[$col];
                $message = "Error: Missing required column '$colName' in the Excel file.";
                $this->session->set_flashdata('error', $message);
                redirect(base_url() . 'admin/scan_bulk_upload', 'refresh');
            }
        }
    
        // Fetch existing IMEIs and ICCIDs from the database
        $this->db->select('s_imei, s_iccid');
        $existingRecords = $this->db->get($this->db->table_serial_no)->result_array();
        $existingIMEIs = array_column($existingRecords, 's_imei');
        $existingICCID = array_column($existingRecords, 's_iccid');
    
        $insertRecords = [];
        $errors = [];
        $time = date("Y-m-d H:i:s");
    
        // Process rows, starting from row 2
        for ($rowNumber = 2; $rowNumber <= count($sheetData); $rowNumber++) {
            $row = $sheetData[$rowNumber];
            $serialNumber = isset($row['A']) ? trim($row['A']) : "";
            $imei = isset($row['B']) ? trim($row['B']) : "";
            $iccid = isset($row['C']) ? trim($row['C']) : "";
            $simNumber1 = isset($row['D']) ? trim($row['D']) : "";
            $simNumber2 = isset($row['E']) ? trim($row['E']) : "";
    
            // Validation checks
            if (empty($serialNumber) || empty($imei) || empty($iccid)) {
                $errors[] = "Row $rowNumber: Required fields (serial_number, imei, or iccid) are missing.";
                continue;
            }
    
            if (strlen($imei) != 15) {
                $errors[] = "Row $rowNumber: IMEI must be 15 characters.";
                continue;
            }
    
            if (strlen($iccid) != 20) {
                $errors[] = "Row $rowNumber: ICCID must be 20 characters.";
                continue;
            }
    
            if (in_array($imei, $existingIMEIs)) {
                $errors[] = "Row $rowNumber: IMEI $imei already exists.";
                continue;
            }
    
            if (in_array($iccid, $existingICCID)) {
                $errors[] = "Row $rowNumber: ICCID $iccid already exists.";
                continue;
            }
    
            // Build a single insert record
            $insertRecords[] = [
                's_serial_number' => $serialNumber,
                's_imei' => $imei,
                's_iccid' => $iccid,
                's_mobile' => $simNumber1,
                's_mobile_2' => $simNumber2,
                's_company_id' => 2,
                's_product_id' => 1,
                'admin_price'       => 4500,
                'distributor_price' => 0,
                'dealer_price'      => 0,
                's_user_type' => $this->session->userdata('user_type'),
                's_user_id' => $this->session->userdata('user_id'),
                's_created_date' => $time,
                'inScan'            => '0',
                's_created_by'      => 1,
                's_state_id' => $this->input->post('s_state_id'),
                's_country_id' => $this->input->post('s_country_id'),
            ];
    
            // Add to existing lists to prevent duplicates in the current batch
            $existingIMEIs[] = $imei;
            $existingICCID[] = $iccid;
        }
    
        // Insert valid records in batch
        if (!empty($insertRecords)) {
            $this->db->insert_batch('ci_serial_numbers', $insertRecords);
            // $this->db->insert_batch($this->db->table_serial_no, $insertRecords);
        }
    
        // Return response
        if (!empty($errors)) {
            $message = count($insertRecords) . " records were successfully added.\n";
            $message .= "Some records failed to insert due to the following errors:\n";
            $message .= implode("\n", $errors);
            $this->session->set_flashdata('error', $message);
        } else {
            $message = count($insertRecords) . " records were successfully added.";
            $this->session->set_flashdata('success', $message);
        }
            
        } catch (Exception $e) {
            print_r($e->getMessage());
        }

        
    
        redirect(base_url() . 'admin/scan_bulk_upload', 'refresh');
    }
    
    public function send_sos_alert()
    {
        $imei = $_REQUEST['imei'];
        $sql = "select vehicleRegnumber,customerID,longitude,latitude,lastupdatedTime from gps_livetracking_data where imei=" . $imei;
        $tracking = $this->load->database('tracking', TRUE);
        $resultSet = $tracking->query($sql);
        $trackingInfo = $resultSet->row_array();
        $customerID = isset($trackingInfo['customerID']) ? $trackingInfo['customerID'] : 0;
        if ((string)$customerID != '0') {
            //Customer Info
            $this->db->select('cus.c_customer_name,cus.c_phone,sos.*');
            $this->db->where('cus.c_customer_id', $customerID);
            $this->db->from($this->db->table_customers . ' as cus');
            $this->db->join($this->db->table_sos . ' as sos', 'sos.sos_cus_phone = cus.c_phone', 'right');
            $result = $this->db->get();
            $customerInfo = $result->result_array();
            //Admin info
            $this->db->select('usr.user_phone');
            $this->db->where('usr.user_id', 1);
            $this->db->from($this->db->table_users . ' as usr');
            $result = $this->db->get();
            $adminInfo = $result->row_array();
            $tinyurl = $this->get_tiny_url('https://maps.google.com?q=' . $trackingInfo['latitude'] . ',' . $trackingInfo['longitude']);
            $sos_alert_msg = $trackingInfo['vehicleRegnumber'] . ' is in Emergency! location:' . $tinyurl;
            if (!empty($customerInfo)) {
                foreach ($customerInfo as $key => $value) {
                    if ((int)$key === 0) {
                        $c_phone = $value['c_phone'];
                        $this->commonmodel->send_sms($c_phone, $sos_alert_msg);
                    }
                    $sos_number = $value['sos_number'];
                    $this->commonmodel->send_sms($sos_number, $sos_alert_msg);
                }
            }

            if (!empty($adminInfo)) {
                $sos_number = $adminInfo['user_phone'];
                $this->commonmodel->send_sms($sos_number, $sos_alert_msg);
            }
            echo 1;
        }
    }

    public function reset()
    {
        unset($_SESSION['permission']);
        echo '1';
        exit();
    }
     public function metrics()
    {
        // echo "<pre>";print_r("data");exit;  
        $refresh = $this->commonmodel->updateRefreshTime();
        $data['currentDate']  = date('Y-m-d H:i:s') ;
        $this->load->view('admin/metrics', $data);

    }
    
     public function ota_param()
    {
        $params = $this->input->post();
        
        $data['listofvehicles'] = $this->commonmodel->ota_param($params['imei_no']);
        // echo "<pre>"; print_r($data); exit;

        $data['imei']           = $params;
        $resultData             = $this->commonmodel->check_ota($params['imei_no']);
        $count                  = $resultData->count;
        $data['count']          = $count;
        
        // echo "<pre>";print_r($data);exit;  
        echo json_encode($data);
        exit();

    }
    
    public function check_ota(){
        $params = $this->input->post();
        // echo "<pre>";print_r($params);exit;  
        $data  = $this->commonmodel->check_ota($params['imei_no']);
        $count = $data->count;
        // echo "<pre>";print_r($count);exit;  
        if($count == 0){
            $insert  = $this->commonmodel->insert_ota($params['imei_no']);
        } 
        $returnResponse['count'] = $count;

        echo json_encode($returnResponse);
        exit();

    }
    
    public function checkApi()
    {
        // echo "<pre>";print_r("params");exit;
        $data='success';
        echo json_encode(true);
		exit();   
    } 
    
    public function total_device_list()
    {
        $user_type  = $this->session->userdata('user_type');
        $user_id    = $this->session->userdata('user_id');

        $limit      = LIST_PAGE_LIMIT;
        $offset     = isset($_GET['offset']) ? $_GET['offset'] : 0;
        if($offset != 0) {
            $offset = ((int)$limit * (int)$offset) - (int)$limit;
        }
        $search     = isset($_GET['search']) ? $_GET['search'] : '';       
        $data['totalNoOfVehicles'] = $this->commonmodel->totalDeviceCount($user_type, $user_id, $search);        
        $data['device']            = $this->commonmodel->getTotalDevice($user_type, $user_id, $search, $limit, $offset);
        // echo "<pre>";print_r($data);exit;
        $this->load->view('admin/total_device', $data);

    }
    
    
    public function used_device_list()
    {
        $user_type  = $this->session->userdata('user_type');
        $user_id    = $this->session->userdata('user_id');

        $limit      = LIST_PAGE_LIMIT;
        $offset     = isset($_GET['offset']) ? $_GET['offset'] : 0;
        if($offset != 0) {
            $offset = ((int)$limit * (int)$offset) - (int)$limit;
        }
        $search     = isset($_GET['search']) ? $_GET['search'] : '';       
        $data['totalNoOfVehicles'] = $this->commonmodel->usedDeviceCount($user_type, $user_id, $search);        
        $data['device']            = $this->commonmodel->getUsedDevice($user_type, $user_id, $search, $limit, $offset);
        // echo "<pre>";print_r($data);exit;
        $this->load->view('admin/used_device', $data);

    }
    
     public function unUsed_device()
    {
        $user_type  = $this->session->userdata('user_type');
        $user_id    = $this->session->userdata('user_id');

        $limit      = LIST_PAGE_LIMIT;
        $offset     = isset($_GET['offset']) ? $_GET['offset'] : 0;
        if($offset != 0) {
            $offset = ((int)$limit * (int)$offset) - (int)$limit;
        }
        $search     = isset($_GET['search']) ? $_GET['search'] : '';   
        // echo "<pre>";print_r($limit);exit;

        $data['totalNoOfVehicles'] = $this->commonmodel->unUsedDeviceCount($user_type, $user_id, $search);        
        $data['device']            = $this->commonmodel->getUnUsedDevice($user_type, $user_id, $search, $limit, $offset);
        // echo "<pre>";print_r($data);exit;   
        $this->load->view('admin/unUsed_device', $data);
    }
    
    public function offline_device()
    {
        $user_type  = $this->session->userdata('user_type');
        $user_id    = $this->session->userdata('user_id');

        $limit      = LIST_PAGE_LIMIT;
        $offset     = isset($_GET['offset']) ? $_GET['offset'] : 0;
        if($offset != 0) {
            $offset = ((int)$limit * (int)$offset) - (int)$limit;
        }
        $search     = isset($_GET['search']) ? $_GET['search'] : '';   
        $hour       = isset($_GET['hour']) && $_GET['hour'] !== ''  ? $_GET['hour'] : 0; 
        $currentDate = date('Y-m-d H:i:s');

        // echo "<pre>";print_r($hour);exit;
        if($hour == 0){
            $hour  = '5 MINUTE';
        }
        else{
            if($hour == 5){
                $hour  = '5 MINUTE';
            }  
            else{
                $hour  = $hour.' HOUR';
            }
        }

        if($hour == '5 MINUTE'){
            $hour2 = '6 HOUR';
        }
        elseif($hour == '6 HOUR'){
            $hour2 = '12 HOUR';
        }
        elseif($hour == '12 HOUR'){
            $hour2 = '24 HOUR';
        }
        elseif($hour == '24 HOUR'){
            $hour2 = '48 HOUR';
        }
        elseif($hour == '48 HOUR'){
            $hour2 = '72 HOUR';
        }
        elseif($hour == '72 HOUR'){
            $hour2 = '';
        }
        
        // echo "<pre>";print_r($hour2);exit;

        $data['totalNoOfVehicles'] = $this->commonmodel->offlineDeviceCount($user_type, $user_id, $search, $hour, $hour2);        
        $data['device']            = $this->commonmodel->getofflineDevice($user_type, $user_id, $search, $limit, $offset ,$hour,$hour2);
        // echo "<pre>";print_r($data);exit;
        $this->load->view('admin/offline_device', $data);
    }
    
    public function live_device()
    {
        $user_type  = $this->session->userdata('user_type');
        $user_id    = $this->session->userdata('user_id');

        $limit      = LIST_PAGE_LIMIT;
        $offset     = isset($_GET['offset']) ? $_GET['offset'] : 0;
        if($offset != 0) {
            $offset = ((int)$limit * (int)$offset) - (int)$limit;
        }
        $search     = isset($_GET['search']) ? $_GET['search'] : '';   
        // echo "<pre>";print_r($search);exit;

        $data['totalNoOfVehicles'] = $this->commonmodel->liveDeviceCount($user_type, $user_id, $search);        
        $data['device']            = $this->commonmodel->getliveDevice($user_type, $user_id, $search, $limit, $offset);
        // echo "<pre>";print_r($data);exit;
        $this->load->view('admin/live_device', $data);
    }
    
      public function inscan_device()
    {
        $user_type  = $this->session->userdata('user_type');
        $user_id    = $this->session->userdata('user_id');

        $limit      = LIST_PAGE_LIMIT;
        $offset     = isset($_GET['offset']) ? $_GET['offset'] : 0;
        if($offset != 0) {
            $offset = ((int)$limit * (int)$offset) - (int)$limit;
        }
        $search     = isset($_GET['search']) ? $_GET['search'] : '';   
        // echo "<pre>";print_r($search);exit;

        $data['totalNoOfVehicles'] = $this->commonmodel->inscanDeviceCount($user_type, $user_id, $search);        
        $data['device']            = $this->commonmodel->getInscanDevice($user_type, $user_id, $search, $limit, $offset);
        // echo "<pre>";print_r($data);exit;   
        $this->load->view('admin/inscan_device', $data);
    }
    
     public function getofflineDevice($user_type, $user_id, $search, $limit, $offset)
    {
        // echo "<pre>";print_r($search);exit();
        if (strlen($search) > 0) {  

            $this->db->select('count(*)');   
            if($user_type == 1)
            {
                $this->db->where('ser.s_dealer_id', $user_id);
            }
            if($user_type == 2)
            {
                $this->db->where('ser.s_distributor_id', $user_id);
            }
            $this->db->from($this->db->table_vehicle . ' as veh');
            $this->db->join($this->db->table_serial_no . ' as ser', 'ser.s_serial_id = veh.veh_serial_no', 'right');
            $this->db->group_start(); 
            $this->db->like('ser.s_imei', $search);
            $this->db->or_like('veh.veh_rc_no', $search);
            $this->db->group_end();
            // $this->db->where('s_dealer_id', $user_id);
            $results1 = $this->db->count_all_results();
            // echo "<pre>";print_r($this->db->last_query());exit;

            if($results1 > 0){
                $subquery101    = "WHERE (imei like '%$search%' OR vehicleRegnumber like '%$search%') and lastUpdatedtime < DATE_SUB(NOW(), INTERVAL 5 MINUTE) ";             
                // echo "<pre>";print_r("select count(*) as count from gps_livetracking_data ".$subquery101."");exit;
                $otherdb = $this->load->database('tracking', TRUE);
                $datas 	= $otherdb->query("select * from gps_livetracking_data ".$subquery101."")->result_array();
                // echo "<pre>";print_r($datas);exit;
            }else{
                $datas = [];
            }

        }
        else{
            $this->db->select('s_imei');   
            $this->db->from($this->db->table_serial_no);
            if($user_type == 1)
            {
                $this->db->where('s_dealer_id', $user_id);
            }
            if($user_type == 2)
            {
                $this->db->where('s_distributor_id', $user_id);
            }
            $results = $this->db->get();
            $results1 = $results->result();
            // echo "<pre>";print_r($results1);exit;
    
            foreach($results1 as $row) {
                $vehiclenos .= " '".$row->s_imei."',";
            }
            if($vehiclenos!=""){
                $vehiclenos = substr($vehiclenos,0,strlen($vehiclenos)-1);
            }	
    
            if($vehiclenos!="" ){
                $subquery101    = " where imei in (".$vehiclenos.") and lastUpdatedtime < DATE_SUB(NOW(), INTERVAL 5 MINUTE) order by lastUpdatedtime DESC LIMIT " . $limit . " OFFSET " . $offset;
            }
            
            // echo "<pre>";print_r("select count(*) as count from gps_livetracking_data ".$subquery101."");exit;
            $otherdb = $this->load->database('tracking', TRUE);
            $datas 	= $otherdb->query("select * from gps_livetracking_data ".$subquery101."")->result_array();
            // echo "<pre>";print_r($this->db->last_query());exit();
        }
            return $datas;
        
    }
    

    public function faulty_device()
    {
        $user_type  = $this->session->userdata('user_type');
        $user_id       = $this->session->userdata('user_id');

        $limit      = LIST_PAGE_LIMIT;
        $offset     = isset($_GET['offset']) ? $_GET['offset'] : 0;
        if($offset != 0) {
            $offset = ((int)$limit * (int)$offset) - (int)$limit;
        }
        
        $search     = isset($_GET['search']) ? $_GET['search'] : '';
        $data['totalNoOfVehicles'] = $this->commonmodel->totalfaultyDevice($user_type, $user_id, $search);
        $data['faulty']            = $this->commonmodel->getfaultyDevice($user_type, $user_id, $search, $limit, $offset);
        
        // echo "<pre>";print_r($data);exit;
        $this->load->view('admin/faulty_device', $data);
    }
    
    
    public function email()

    {

        require APPPATH . '\libraries\PHPMailer\PHPMailerAutoload.php';

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

        $params = $this->input->post();

        $userinfo = $this->commonmodel->getUserInfo($params);

        $response = array();

        if (empty($userinfo)) {

            $response['error'] = 'Invalid mail address, Please try again.';

        } else {

            $randomPwd = random_string('alnum', 6);

            require APPPATH . '\libraries\PHPMailer\PHPMailerAutoload.php';

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

            $mail->SetFrom(SMTP_MAIL_FROM, SMTP_MAIL_FROM_NAME);

            $mail->Subject = 'Reset Password';

            $body = "Password has been reseted successsfully, <br/> New Password is : " . $randomPwd; // automatically

            $mail->MsgHTML($body);

            $mail->Send();

            $response['success'] = true;

            $response['message'] = 'Reset Password has been send your mail address, Kindly check it.';

            $updateInfo = array();

            $updateInfo['user_password'] = md5($randomPwd);

            $updateInfo['user_id'] = $userinfo['user_id'];

            $userinfo = $this->commonmodel->updateUserInfo($updateInfo);

        }

        echo json_encode($response);

    }


    public function pdf11()

    {

        $data['title'] = true;

        $html = $this->load->view('tcpdf1', $data, true);


        echo $html;
        exit();


    }


    public function pdf()

    {

        $data['title'] = true;

        $html = file_get_contents('http://localhost/speed/admin/pdf11');//$this->load->view('tcpdf1',$data,true);


        //echo $html;exit();

        $filename = 'abcd';


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
        $pdf_qr_code = base_url() . "admin/downloadwebpdf?id=" . $_GET['id'];

        $this->commonmodel->qrcode($pdf_qr_code, $_GET['id']);

    }

    public function inv_qr_code()
    {

        $pdf_qr_code = base_url() . "admin/downloadinvoice?id=" . $_GET['id'];

        $this->commonmodel->qrcode($pdf_qr_code);

    }


    public function downloadRenewalwebpdf()
    {

        $params = $this->input->get();

        if (!isset($params['id'])) {

            redirect(base_url() . 'admin/dashboard', 'refresh');
            exit();
        }

        $id = base64_decode(base64_decode(base64_decode($params['id'])));
        $encodeID = base64_encode(base64_encode(base64_encode($id)));

        if (!is_numeric($id)) {
            redirect(base_url() . 'admin/dashboard', 'refresh');
            exit();
        }

        $data['userinfo'] = $this->commonmodel->getPdfRenewalVehicleInfo($id);
        $data['userinfo']['qrcodeimg'] = base_url() . "admin/renewal_pdf_qr_code/?id=" . $encodeID;

        $this->load->view("RenewalwebPDF", $data);
    }

    public function renewal_pdf_qr_code()
    {

        $pdf_qr_code = base_url() . "admin/downloadRenewalwebpdf?id=" . $_GET['id'];
        $this->commonmodel->qrcode($pdf_qr_code);
    }


    public function downloadwebpdf()

    {

        $params = $this->input->get();

        if (!isset($params['id'])) {

            redirect(base_url() . 'admin/dashboard', 'refresh');

            exit();

        }


        $id = base64_decode(base64_decode(base64_decode($params['id'])));

        //echo $id;exit();

        $encodeID = base64_encode(base64_encode(base64_encode($id)));

        if (!is_numeric($id)) {

            redirect(base_url() . 'admin/dashboard', 'refresh');

            exit();

        }

        $data['userinfo']   = $this->commonmodel->getPdfVehicleInfo($id);
        $technician         = $this->commonmodel->getTechnicianName($data['userinfo']['veh_technician_id']);

        $dealerName         = $this->commonmodel->getdealerName($data['userinfo']['s_dealer_id']);
        
        $data['dealerName'] = $dealerName['user_name'];
        $data['technician'] =  (!empty($technician))? $technician['user_name']: "";
        $data['userinfo']['certificateIssuedBy'] = "39 Tarachand Dutt Street, 2nd Floor Kolkata West Bengal- 700073";
        // $data['userinfo']['certificateIssuedBy'] = "PSDN Technology Pvt. Ltd. No 7, 8 Appaswamy Towers, Block A, 2nd Floor, Sir Thyagaraya Road, T Nagar, Chennai, Tamil Nadu 600017.";

        $rto = substr($data['userinfo']['rto_number'], 0, 2);
        
        if($rto=="TN"){
            $data['userinfo']['transportLogo'] = "public/images/tnstc-logo.png";
        }elseif($rto=="WB"){
            $data['userinfo']['transportLogo'] = "public/images/Biswa_bangla.png";
        }elseif($rto=="GJ"){
            $data['userinfo']['transportLogo'] = "public/images/GSRTC.png.png";
        }elseif($rto=="AP"){
            $data['userinfo']['transportLogo'] = "public/images/apstc.png";
        }elseif($rto=="TS"){
            $data['userinfo']['transportLogo'] = "public/images/TSRTC_LOGO.png";
        }elseif($rto=="KL"){
            $data['userinfo']['transportLogo'] = "public/images/ksrtc.png";
        }elseif($rto=="KA"){
            $data['userinfo']['transportLogo'] = "public/images/KSRTC_Logo.png";
        }elseif($rto=="MH"){
            $data['userinfo']['transportLogo'] = "public/images/msrtc.png";
        }else{
            $data['userinfo']['transportLogo'] = "public/images/Biswa_bangla.png";
        }
        // $data['userinfo']['qrcodeimg'] = base_url() . "admin/pdf_qr_code/?id=" . $encodeID;
        $this->commonmodel->qrcode(base_url() . "admin/pdf_qr_code/?id=" . $encodeID,$encodeID);
        $data['userinfo']['qrcodeimg']  = AWS_S3_BUCKET_URL. "public/qrcodes/". $encodeID .".png";
        if($data['userinfo']['veh_rc_no']=="" && $data['userinfo']['rc_book_photo']==""){
            if(strtotime($data['userinfo']['veh_create_date']) < strtotime('-30 days')) {
                $this->load->view("rcAlert");
            }else{
                if($data['userinfo']['rc_book_photo']==null){
                    $data['userinfo']['rc_book_photo'] ="public/images/white.png";
                    // $data['userinfo']['veh_rc_no']= "No Rc Number";
                }
                if($data['userinfo']['vehicle_owner_id_proof']==null){
                    $data['userinfo']['vehicle_owner_id_proof'] ="public/images/white.png";
                }
                if($data['userinfo']['vehicle_owner_photo']==null){
                    $data['userinfo']['vehicle_owner_photo'] ="public/images/white.png";
                }
                if($data['userinfo']['veh_photo']==null){
                    $data['userinfo']['veh_photo'] ="public/images/white.png";
                }
                if($data['userinfo']['veh_speed_governer_photo']==null){
                    $data['userinfo']['veh_speed_governer_photo'] ="public/images/white.png";
                }
                $this->commonmodel->qrcode(base_url() . "admin/pdf_qr_code/?id=" . $encodeID,$encodeID);
                // $data['userinfo']['qrcodeimg'] = base_url() . "admin/pdf_qr_code/?id=" . $encodeID;
                $data['userinfo']['qrcodeimg']  = AWS_S3_BUCKET_URL. "public/qrcodes/". $encodeID .".png";
                $this->load->view("webPDF", $data);    
            }
        }else{
            if($data['userinfo']['rc_book_photo']==null){
                $data['userinfo']['rc_book_photo'] ="public/images/white.png";
                // $data['userinfo']['veh_rc_no']= "No Rc Number";
            }
            if($data['userinfo']['vehicle_owner_id_proof']==null){
                $data['userinfo']['vehicle_owner_id_proof'] ="public/images/white.png";
            }
            if($data['userinfo']['vehicle_owner_photo']==null){
                $data['userinfo']['vehicle_owner_photo'] ="public/images/white.png";
            }
            if($data['userinfo']['veh_photo']==null){
                $data['userinfo']['veh_photo'] ="public/images/white.png";
            }
            if($data['userinfo']['veh_speed_governer_photo']==null){
                $data['userinfo']['veh_speed_governer_photo'] ="public/images/white.png";
            }
            $this->commonmodel->qrcode(base_url() . "admin/pdf_qr_code/?id=" . $encodeID,$encodeID);
            // $data['userinfo']['qrcodeimg'] = base_url() . "admin/pdf_qr_code/?id=" . $encodeID;
            $data['userinfo']['qrcodeimg'] = AWS_S3_BUCKET_URL. "public/qrcodes/". $encodeID .".png";
            $this->load->view("webPDF", $data);
        }
        // $this->load->view("webPDF", $data);

    }

    public function downloadinvoice()

    {
       // echo "hai"; exit;

        $params = $this->input->get();

        if (!isset($params['id'])) {

            redirect(base_url() . 'admin/dashboard', 'refresh');

            exit();

        }


        $id = base64_decode(base64_decode(base64_decode($params['id'])));

        //echo $id;exit();

        $encodeID = base64_encode(base64_encode(base64_encode($id)));

        if (!is_numeric($id)) {

            redirect(base_url() . 'admin/dashboard', 'refresh');

            exit();

        }

        $data['userinfo'] = $this->commonmodel->getPdfInvoiceInfo($id);

        $data['serialsinfo'] = $this->commonmodel->getPdfInvoiceSerialsInfo(explode(',', $data['userinfo']['i_serial_ids']));

        $data['userinfo']['qrcodeimg'] = base_url() . "admin/inv_qr_code/?id=" . $encodeID;

        $this->load->view("invoicePDF", $data);

    }


    public function downloadpdf($id)

    {

        $data['userinfo'] = $this->commonmodel->getPdfVehicleInfo($id);

        $data['userinfo']['qrcodeimg'] = base_url() . "admin/pdf_qr_code/" . $id;


        $html = $this->load->view("vehiclePDF", $data, true);

        //echo $html;exit();

        $filename = date('Y_m_d_H_i_s');


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

        $params = $this->input->post();

        $data['userinfo'] = $this->commonmodel->getPdfVehicleInfo($id);

        $data['userinfo']['qrcodeimg'] = base_url() . "admin/pdf_qr_code/" . $id;

        $html = $this->load->view("vehiclePDF", $data, true);


        $filename = "public/temp_upload/" . date('Y_m_d_H_i_s') . ".pdf";


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


        require APPPATH . '/libraries/PHPMailer/PHPMailerAutoload.php';

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

        $user_type = $this->session->userdata('user_type');

        if (isset($user_type) && (string)$user_type === '0') {

            redirect(base_url() . 'admin/dashboard', 'refresh');

            exit();

        }


        $this->load->view('admin/login');

    }


    // public function logout()

    // {

    //     $this->session->sess_destroy();

    //     redirect(base_url() . 'admin/', 'refresh');

    //     exit();

    // }
    public function logout()

    {

        $user_type = $this->session->userdata('user_type');
        // echo "<pre>";print_r($user_type);exit;

        if ( $user_type == 4 || $user_type == 0) {
            $this->session->sess_destroy();
            redirect(base_url() . 'admin/', 'refresh');
        }
        if($user_type == 1){
            $this->session->sess_destroy();
            redirect(base_url() . 'dealer/', 'refresh');
        }
        if($user_type == 2){
            $this->session->sess_destroy();
            redirect(base_url() . 'distributor/', 'refresh');
        }

        exit();

    }


    public function edit_profile()

    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'profile_edit')) {
            redirect(base_url(), 'refresh');
            exit();
        }
        //Permission


        $dealerID = $this->session->userdata('user_id');

        $data['userinfo'] = $this->commonmodel->getDealerInfo($dealerID);

        if (empty($data['userinfo'])) {

            redirect(base_url() . 'admin/dashboard', 'refresh');

        }

        $data['userinfo']['user_id'] = base64_encode($dealerID);

        $data['pageTitle'] = 'Edit Profile';

        // Load Content

        $this->load->view('masters/edit_profile', $data);

    }


    public function forgot_password()

    {

        $this->load->view('admin/forgot_password');

    }

    // Verify user Records

    public function verifyuser()
    {
        $params = $this->input->post();
        $returnResponse = array();
        $returnResponse['validation'] = array();
        $returnResponse['error'] = "";
        $returnResponse['success'] = "";
        // Validation
        $this->form_validation->set_rules('phone_number', 'Phone Number', 'required');
        $this->form_validation->set_rules('password_value', 'Password', 'required');

        // Validation verify

        if ($this->form_validation->run() == FALSE) {

            $returnResponse['validation'] = $this->form_validation->error_array();

            echo json_encode($returnResponse);
            exit();

        }

        //Pass params to Model
        $response = $this->adminmodel->verifyuser($params);

        if (empty($response)) {

            $returnResponse['error'] = "Please Enter valid Credentials.";

            echo json_encode($returnResponse);
            exit();

        }
        if (isset($response['user_password'])) {

            unset($response['user_password']);

        }
        // Set Session
        session_start();
        $this->session->set_userdata($response);
        // echo "<pre>";
        // print_r($_SESSION);
        // exit;
        if($response['user_type'] == 1){
            $count = $this->adminmodel->countExpiry($response['user_id']);
            if($count != 0){
                $returnResponse['expiry'] = true;
                $returnResponse['data'] = $count;
                // $returnResponse['success'] = true;
                $returnResponse['redirect'] = 'admin/expiring_list';
                echo json_encode($returnResponse);
                exit();
            }
            else{
                $returnResponse['success'] = true;
                $returnResponse['redirect'] = 'admin/dashboard';
                echo json_encode($returnResponse);
                exit();
            }
        }
        
        $returnResponse['success'] = true;
        $returnResponse['redirect'] = 'admin/dashboard';
        echo json_encode($returnResponse);
        exit();
    }


    // Verify user Records

    public function verifyrto()
    {


        $params = $this->input->post();


        $returnResponse = array();

        $returnResponse['validation'] = array();

        $returnResponse['error'] = "";

        $returnResponse['success'] = "";

        // Validation

        $this->form_validation->set_rules('rto_number', 'Rto Number', 'required');

        $this->form_validation->set_rules('rto_pwd', 'rto_pwd', 'required');

        // Validation verify

        if ($this->form_validation->run() == FALSE) {

            $returnResponse['validation'] = $this->form_validation->error_array();

            echo json_encode($returnResponse);
            exit();

        }

        //Pass params to Model

        $response = $this->adminmodel->verifyrto($params);

        if (empty($response)) {

            $returnResponse['error'] = "Please Enter valid Credentials.";

            echo json_encode($returnResponse);
            exit();

        }

        //print_r($response);exit();

        // Set Session

        $this->session->set_userdata($response);

        $returnResponse['success'] = true;


        $returnResponse['redirect'] = 'rto/rto_dashboard';

        echo json_encode($returnResponse);
        exit();

    }
    // Initialize Function

     public function count_vehicle_records()
    {
        $params = $this->input->post();
        $vehicle_user_name = $params['veh_owner_name'];
        $vehicle_user_id = $params['veh_owner_id'];
        $dataName 	= $this->adminmodel->getCustomerName($vehicle_user_id);
        $name = $dataName[0]['c_customer_name'];

        $data['count']= 0;
        if($name == $vehicle_user_name){
            $data['count'] = 0;
        }
        else{
        $data['count'] = 1;
        }
		echo json_encode($data);
		exit();

    }
    
    public function testCount(){
        $data['countList'] = $this->commonmodel->getCount();
    }
    
    public function dashboard()
    {
        //Check Permission
        $user_type = $this->session->userdata('user_type');
        
        // echo "session valueeeee===>:".$user_type;
        // echo "<pre>";
        // print_r($_SESSION);
        // exit;

        if (!check_permission($user_type, 'menu_dashboard')) {
            redirect(base_url(), 'refresh');
            exit();
        }
        
        $data['countList'] = $this->commonmodel->getNoOfCount();

        
        $this->commonmodel->userTracking();
        $_SESSION['currentActivePage'] = 'Home';
        $user_type = $this->session->userdata('user_type');
        $data['user_type'] = $this->session->userdata('user_type');

// Block Redirection
        /*
		if(isset($user_type) && (string)$user_type==='1'){

			if ( $this->session->userdata('defaultPage')=='1' ){

			}else{
			   // $this->session->set_userdata('defaultPage', '1' );
			    redirect(base_url().'admin/assigned_serial_number_list', 'refresh');
		      	exit();
			}
			 exit();
		}

		if(isset($user_type) && (string)$user_type==='2'){

			 redirect(base_url().'admin/assigned_serial_number_list', 'refresh');
			// redirect(base_url().'distributor/create_new_entry', 'refresh');
			 exit();
		}
*/

        // Load Content
        $this->load->view('admin/dashboard', $data);
    }


    // Initialize Function

    public function rto_dashboard()

    {

        $rtoNo = $this->session->userdata('rto_no');

        if (!isset($rtoNo) || strlen($rtoNo) === 0) {

            redirect(base_url(), 'refresh');

            exit();

        }

        $selectedReportDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d');

        $data['totalNoOfVehicles'] = $this->commonmodel->totalNoOflistofTodayEntrys($selectedReportDate, $rtoNo);

        $data['listofvehicles'] = $this->commonmodel->listofTodayEntrys($selectedReportDate, $rtoNo);

        $data['selectedReportDate'] = $selectedReportDate;

        $this->load->view('rto/rto_dashboard', $data);

    }


    // Agents

    public function create_new_users()

    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'menu_user_create')) {
            redirect(base_url(), 'refresh');
            exit();
        }


        $data['rto_list'] = $this->commonmodel->allRtoNumbers();

        $data['company_list'] = $this->commonmodel->allCompanyList();
        if ($user_type == 4) {
            $data['states_list'] = $this->commonmodel->allStatesList();
        } else {
            $data['states_list'] = "";
        }
        $this->session->set_userdata('currentActivePage', 'Create_Users');

        // Load Content

        $this->load->view('masters/create_dealer', $data);

    }

    public function removeWhiteSpace()

    {

        $this->commonmodel->removeWhiteSpace();

    }


    // Admin Create New Company

    public function create_company()

    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'menu_inventry_create')) {
            redirect(base_url(), 'refresh');
            exit();
        }


        $this->session->set_userdata('currentActivePage', 'Create_Company');

        // Load Content

        $this->load->view('masters/create_company', $data);

    }

    public function create_product()

    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'menu_product_create')) {
            redirect(base_url(), 'refresh');
            exit();
        }


        $this->session->set_userdata('currentActivePage', 'Create_Product');

        // Load Content

        $data['company_list'] = $this->commonmodel->allCompanyList();

        $this->load->view('masters/create_product', $data);

    }


    // Admin Create New Company

    public function create_vehicle_make()

    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'menu_vehicle_make_create')) {
            redirect(base_url(), 'refresh');
            exit();
        }


        $this->session->set_userdata('currentActivePage', 'Create_Vehicle_Make');

        // Load Content

        $this->load->view('masters/create_vehicle_make', $data);

    }


    // Admin Create New Company

    public function create_rto()
    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'menu_rto_create')) {
            redirect(base_url(), 'refresh');
            exit();
        }

        $data['state_list'] = $this->commonmodel->allStateList();
        $this->session->set_userdata('currentActivePage', 'Create_RTO_Number');

        // Load Content

        $this->load->view('masters/create_rto_number', $data);

    }


    // Create New Vehicle Make Records

    public function create_rto_records()
    {

        $params = $this->input->post();

        $returnResponse = array();

        $returnResponse['validation'] = array();

        $returnResponse['error'] = "";

        $returnResponse['success'] = "";

        // Validation
        
        $this->form_validation->set_rules('rto_number', 'RTO Number', 'trim|required');

        $this->form_validation->set_rules('rto_place', 'RTO Place', 'trim|required');
        
        $this->form_validation->set_rules(

            'rto_number', 'RTO Number',

            array(

                'required',

                array(

                    'already_exits',

                    function ($str) {
                        // echo "<pre>";print_r($str);exit;
                        return $this->commonmodel->verify_exits_rto_number($str);

                    }

                )

            )

        );


        // Validation verify
        if ($this->form_validation->run() == FALSE) {
            
            $returnResponse['validation'] = $this->form_validation->error_array();
            echo json_encode($returnResponse);
            exit();

        }
        
        //Pass params to Model

        //$params['v_created_by']=$this->session->userdata('user_id');
        $stateInfo = $this->commonmodel->getStateInfo($params['state_id']);
        $params['stateName'] = $stateInfo['s_name'];

        $response = $this->adminmodel->create_new_rto_records($params);

        if (empty($response)) {

            $returnResponse['error'] = "Please Enter valid Details.";

            echo json_encode($returnResponse);
            exit();

        }


        // Set Session

        $returnResponse['success'] = true;

        $returnResponse['redirect'] = 'admin/rto_list';

        $returnResponse['message'] = 'Rto Number created successfully.';

        echo json_encode($returnResponse);
        exit();

    }


    public function rto_list_copy()
    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'menu_rto_list')) {
            redirect(base_url(), 'refresh');
            exit();
        }


        $_SESSION['currentActivePage'] = 'RTO_List';


        $limit = LIST_PAGE_LIMIT;

        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;

        if ($offset != 0) {

            $offset = ((int)$limit * (int)$offset) - (int)$limit;

        }

        $search = isset($_GET['search']) ? $_GET['search'] : '';

        $data['totalNoOfRTO'] = $this->commonmodel->totalNoOfRTO();

        $data['listofRTONumbers'] = $this->commonmodel->listofRtoList($limit, $offset, $search);

        //print_r($data['listofRTONumbers']);exit();

        $this->load->view('masters/rto_list', $data);

    }

    public function rto_list()
    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'menu_rto_list')) {
            redirect(base_url(), 'refresh');
            exit();
        }

        $_SESSION['currentActivePage'] = 'RTO_List';

        $limit = LIST_PAGE_LIMIT;

        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;

        if ($offset != 0) {

            $offset = ((int)$limit * (int)$offset) - (int)$limit;

        }

        $state = isset($_GET['state_id']) ? $_GET['state_id'] : '';
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        
        $data['totalNoOfRTO'] = $this->commonmodel->totalNoOfRTO($search, $state);
        $data['stateList'] = $this->commonmodel->allStateList();
        $data['listofRTONumbers'] = $this->commonmodel->listofRtoList($limit, $offset, $search, $state);

        $this->load->view('masters/rto_list', $data);

    }
    
    
    public function create_customer()
    {
        //Permission
        $user_type = $this->session->userdata('user_type');

        $this->session->set_userdata('currentActivePage', 'Create_Customer');

        // Load Content

        $data['company_list'] = $this->commonmodel->allCompanyList();
        // print_r($data);exit;
        $this->load->view('masters/create_customer', $data);
        
    }
    
    
    public function customers_list_old()
    {
        
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'menu_customer')) {
            redirect(base_url(), 'refresh');
            exit();
        }

        $_SESSION['currentActivePage'] = 'Customers_List';

        $usertype=$_SESSION['user_type'];
        $userid=$_SESSION['user_id'];

        // echo  $usertype." ".$userid; exit;

        $limit = LIST_PAGE_LIMIT;

        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;

        if ($offset != 0) {

            $offset = ((int)$limit * (int)$offset) - (int)$limit;

        }

        $search = isset($_GET['search']) ? $_GET['search'] : '';
        // echo "<pre>";print_r($search);exit;
      if($usertype==0||$usertype==4)//SUPER ADMIN
      {
        $data['totalNoOfCustomers'] = $this->commonmodel->totalNoOfCustomers();
        $data['usertype']        = $usertype;
        $data['listofCustomers'] = $this->commonmodel->listofCustomersList($limit, $offset, $search);
      }  
      if($usertype==1) //DEALER
      {
        $data['totalNoOfCustomers'] = $this->commonmodel->totalNoOfCustomersDealer($userid);
        $data['usertype']        = $usertype;
        $data['listofCustomers'] = $this->commonmodel->listofCustomersListDealer($limit, $offset, $search,$userid);
      }
      if($usertype==2)//DISTRIBUTOR
      {
        $data['deler_id']        = $this->commonmodel->getTotalDealerID($userid);
        // echo "<pre>"; print_r($data['deler_id']); exit;
        $data['totalNoOfCustomers'] = $this->commonmodel->totalNoOfCustomersDistributor($userid,$search,$data['deler_id']);
        $data['usertype']        = $usertype;
        
        //$data['getDealersName']= $this->commonmodel->listOfDistributorDealers($userid);
        $data['listofCustomers'] = $this->commonmodel->listofCustomersListDistributor($limit, $offset, $search,$userid,$data['deler_id']);
      }

        // echo "<pre>";print_r($data);exit();

        $this->load->view('masters/customers_list', $data);

    }

    public function customers_list()
    {
        
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'menu_customer')) {
            redirect(base_url(), 'refresh');
            exit();
        }

        $_SESSION['currentActivePage'] = 'Customers_List';

        $usertype=$_SESSION['user_type'];
        $userid=$_SESSION['user_id'];

        // echo  $usertype." ".$userid; exit;

        $limit = LIST_PAGE_LIMIT;

        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;

        if ($offset != 0) {

            $offset = ((int)$limit * (int)$offset) - (int)$limit;

        }

        $search = isset($_GET['search']) ? $_GET['search'] : '';
        // echo "<pre>";print_r($search);exit;
      if($usertype==0||$usertype==4)//SUPER ADMIN
      {
        $data['totalNoOfCustomers'] = $this->commonmodel->totalNoOfCustomers();
        $data['usertype']        = $usertype;
        $data['listofCustomers'] = $this->commonmodel->listofCustomersList($limit, $offset, $search);
      }  
      if($usertype==1) //DEALER
      {
        $data['totalNoOfCustomers'] = $this->commonmodel->totalNoOfCustomersDealer($userid);
        $data['usertype']        = $usertype;
        $data['listofCustomers'] = $this->commonmodel->listofCustomersListDealer($limit, $offset, $search,$userid);
      }
      if($usertype==2)//DISTRIBUTOR
      {
        $data['deler_id']        = $this->commonmodel->getTotalDealerID($userid);
        // echo "<pre>"; print_r($data['deler_id']); exit;
        $data['totalNoOfCustomers'] = $this->commonmodel->totalNoOfCustomersDistributor($userid,$search,$data['deler_id']);
        $data['usertype']        = $usertype;
        
        //$data['getDealersName']= $this->commonmodel->listOfDistributorDealers($userid);
        $data['listofCustomers'] = $this->commonmodel->listofCustomersListDistributor($limit, $offset, $search,$userid,$data['deler_id']);
      }

        // echo "<pre>";print_r($data);exit();

        $this->load->view('masters/customers_list', $data);

    }

    public function update_customer_status()
    {
        // $updateRecords=array();
        // $updateRecords['c_status']="INACTIVE";
        // for($Arry = 0; $Array < count();$Array++){
        // $this->db->where('c_customer_id',$_REQUEST["valJson"][$Array]);
        // $this->db->update($this->db->table_customers,$updateRecords);
        // }
        $this->adminmodel->customer_status($_REQUEST["valJson"]);

        $user_type = $this->session->userdata('user_type');
        $returnResponse['fail'] = false;
        $returnResponse['success'] = "Customer " . $_REQUEST["valJson"][0] . " Successfully";
        echo json_encode($returnResponse);
    }

    public function edit_rto_number()

    {
        $rtoNo=base64_decode($_GET['q']);
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'rto_edit')) {
            redirect(base_url(), 'refresh');
            exit();
        }
        //Permission

        if (!isset($rtoNo)) {

            redirect(base_url() . 'admin/dashboard', 'refresh');

        }

        $data['RToInfo'] = $this->commonmodel->getRtoInfo($rtoNo);
        $data['state_list'] = $this->commonmodel->allStateList();

        if (empty($data['RToInfo'])) {

            redirect(base_url() . 'admin/dashboard', 'refresh');

        }

        $data['RToInfo']['rto_no'] = base64_encode($rtoNo);

        $data['pageTitle'] = 'Edit RTO Number';

        $_SESSION['currentActivePage'] = 'RTO_List';

        // Load Content

        $this->load->view('masters/edit_rto_number', $data);

    }


    public function edit_customer(){

        $customerid=  $_GET['id'];
        $data['customerInfo'] = $this->commonmodel->getCustomerDetails($customerid);
        // echo "<pre>";print_r($data);exit();
        $this->load->view('masters/edit_customer', $data);
    }
    
    public function update_customer_records()
    {
        
        $params = $this->input->post();
        
        $returnResponse = array();

        $returnResponse['validation'] = array();

        $returnResponse['error'] = "";

        $returnResponse['success'] = "";

        // Validation
        $this->form_validation->set_rules('c_customer_name', 'Customer Name', 'trim|required');

        $this->form_validation->set_rules('c_phone', 'Phone Number', 'trim|required');
        // Validation verify
        
        if ($this->form_validation->run() == FALSE) {

            $returnResponse['validation'] = $this->form_validation->error_array();

            echo json_encode($returnResponse);
            exit();

        }
        
        
        // Rename // Rename Profile Photo
        if (isset($params['vehicle_owners_photo']) && strlen($params['vehicle_owners_photo']) > 0) {
            /* if (strpos($params['vehicle_owners_photo'], 'temp_upload') !== false) {
                $profile_photo = str_replace('public/temp_upload/', 'public/upload/vehicle_owners_photos/', $params['vehicle_owners_photo']);

                rename($params['vehicle_owners_photo'], $profile_photo);

                $params['vehicle_owners_photo'] = $profile_photo;
            } */
            if (strpos($params['vehicle_owners_photo'], 'temp_upload') !== false) {
                $imagePath = $params['vehicle_owners_photo'];
                $imageData = explode('/', $imagePath);
                $imageName = $imageData[2];
                $path = "public/upload/vehicle_owners_photos";
                $deviceImage = $this->awsImageUpload($imagePath, $imageName, $path);
                $dats = explode('/', $deviceImage);
                $params['vehicle_owners_photo'] = $path.'/'.$dats[6];
                if (isset($params['vehicle_owners_photo']) && strlen($params['vehicle_owners_photo']) > 0) {
                    unlink($imagePath);
                }
            }
        }else{
            $params['vehicle_owners_photo'] = null;
        }
        
        //Pass params to Model

        // $params['c_created_by'] = $this->session->userdata('user_id');
        $response = $this->adminmodel->update_customer_records($params);

        if (empty($response)) {

            $returnResponse['error'] = "Please Enter valid Details.";

            echo json_encode($returnResponse);
            exit();

        }


        // Set Session

        $returnResponse['success'] = true;

        $returnResponse['redirect'] = 'admin/customers_list';

        $returnResponse['message'] = 'Customer details updated successfully.';

        echo json_encode($returnResponse);
        exit();

    }
    
    public function update_rto_records()

    {

        $params = $this->input->post();

        $params['rto_no'] = base64_decode($params['rto_no']);

        //print_r($params);exit();

        $returnResponse = array();

        $returnResponse['validation'] = array();

        $returnResponse['error'] = "";

        $returnResponse['success'] = "";

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

                    function ($str) {

                        $arg = $this->input->post();

                        $arg['rto_no'] = base64_decode($arg['rto_no']);

                        return $this->commonmodel->verify_exits_rto_number($str, $arg['rto_no']);

                    }

                )

            )

        );


        // Validation verify

        if ($this->form_validation->run() == FALSE) {

            $returnResponse['validation'] = $this->form_validation->error_array();

            echo json_encode($returnResponse);
            exit();

        }

        //print_r($params);exit();

        //Pass params to Model
        $stateInfo = $this->commonmodel->getStateInfo($params['state_id']);
        $params['stateName'] = $stateInfo['s_name'];

        $response = $this->adminmodel->modify_rto_records($params);

        if (empty($response)) {

            $returnResponse['error'] = "Please Enter valid Credentials.";

            echo json_encode($returnResponse);
            exit();

        }

        // Set Session

        $this->session->set_userdata($response);

        $returnResponse['success'] = true;

        $returnResponse['redirect'] = 'admin/rto_list';

        $returnResponse['message'] = 'RTO Records has been updated successfully.';

        echo json_encode($returnResponse);
        exit();

    }


    // Agents

    public function create_new_dealer_records()
    {

        $params = $this->input->post();
        $length = strlen($params['phone']);
        // exit;
        if (!isset($params['user_distributor_id'])) {

            $params['user_distributor_id'] = 0;

        }

        $returnResponse = array();

        $returnResponse['validation'] = array();

        $returnResponse['error'] = "";

        $returnResponse['success'] = "";

        $returnResponse['mobileError'] = "";

        // Validation
        if($length < 10){
            $returnResponse['mobileError'] = True;
            echo json_encode($returnResponse);
            exit();
        }
        
        // Validation

        $this->form_validation->set_rules('name', 'Name', 'trim|required');

        $this->form_validation->set_rules('phone', 'Phone', 'trim|required');

        $this->form_validation->set_rules('user_company_id', 'Company ID', 'trim|required');

        if ($params["user_type"] != 6) {
            $this->form_validation->set_rules('email', 'Email', 'trim|required');
        }
        
        $this->form_validation->set_rules('gender', 'Gender', 'trim|required');

        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($params["user_type"] == 1) {
            $this->form_validation->set_rules('user_rto', 'Rto Number', 'trim|required');
        } else {
            $this->form_validation->set_rules('user_rto', 'Rto Number', 'trim');
        }
        if ($params["user_type"] == 2) {
            $this->form_validation->set_rules('user_states', 'Select States', 'required');
        }
        $this->form_validation->set_rules('user_own_company', 'Company Name', 'trim');

        $this->form_validation->set_rules('user_type', 'User Type', 'trim|required');

        $this->form_validation->set_rules('description', 'Address', 'trim|required');

        $this->form_validation->set_rules('acc_number', 'Account Number', 'trim');
        $this->form_validation->set_rules('acc_name', 'Account Name', 'trim');
        $this->form_validation->set_rules('acc_ifsc', 'IFSC Code', 'trim');
        $this->form_validation->set_rules('acc_branch', 'Account Branch', 'trim');


        $this->form_validation->set_rules(

            'phone', 'Phone',

            array(

                'required',

                array(

                    'phone_no_already_exits',

                    function ($str) {

                        return $this->commonmodel->verify_exits_dealer_phone_number($str);

                    }

                )

            )

        );

        if (isset($params['email']) && strlen($params['email']) > 0) {

            $this->form_validation->set_rules(

                'email', 'Email',

                array(

                    'required',

                    array(

                        'email_no_already_exits',

                        function ($str) {

                            return $this->commonmodel->verify_exits_dealer_email($str);

                        }

                    )

                )

            );

        }

        // Validation verify

        if ($this->form_validation->run() == FALSE) {

            $returnResponse['validation'] = $this->form_validation->error_array();

            echo json_encode($returnResponse);
            exit();

        }

        // Rename Profile Photo

        if (isset($params['profile_photo']) && strlen($params['profile_photo']) > 0) {

            if (strpos($params['profile_photo'], 'temp_upload') !== false) {

                $profile_photo = str_replace('public/temp_upload/', 'public/upload/users/', $params['profile_photo']);

                rename($params['profile_photo'], $profile_photo);

                $params['profile_photo'] = $profile_photo;

            }

        }

        //Pass params to Model

        $response = $this->adminmodel->create_new_dealer_records($params);

        if (empty($response)) {

            $returnResponse['error'] = "Please Enter valid Credentials.";

            echo json_encode($returnResponse);
            exit();

        }

        if (isset($response['user_password'])) {

            unset($response['user_password']);

        }

        // Set Session

        $returnResponse['success'] = true;

        $returnResponse['redirect'] = 'admin/users_list';

        $returnResponse['message'] = 'Users has been created successfully.';

        echo json_encode($returnResponse);
        exit();

    }

    public function create_new_customer_records()
    {
        
        $params = $this->input->post();

        $params['c_user_status']=1;
        // echo "<pre>";print_r($params);exit;
        
        $params['c_password'] = md5($params['c_password']);
        // $params['c_password'] = md5("123456");
        // echo "<pre>";print_r($params);exit;
        $returnResponse = array();

        $returnResponse['validation'] = array();

        $returnResponse['error'] = "";

        $returnResponse['success'] = "";

       

        $count = $this->adminmodel->count_customer_records($params['c_phone']);
        // echo "<pre>";print_r($count);exit;
        if($count != 0 ){
                $returnResponse['error'] = "Mobile number already used.";
                 echo json_encode($returnResponse);
                 exit();
        }

        // Validation
        $this->form_validation->set_rules('c_customer_name', 'Customer Name', 'trim|required');

        $this->form_validation->set_rules('c_phone', 'Phone Number', 'trim|required');

        // $this->form_validation->set_rules('c_address', 'Address', 'trim|required');

        $this->form_validation->set_rules('c_password', 'Password', 'trim|required');

        // Validation verify
        
        if ($this->form_validation->run() == FALSE) {

            $returnResponse['validation'] = $this->form_validation->error_array();

            echo json_encode($returnResponse);
            exit();

        }
        
        // echo "<pre>";print_r($params);exit;
        // Rename // Rename Profile Photo
        if (isset($params['vehicle_owners_photo']) && strlen($params['vehicle_owners_photo']) > 0) {
            // if (strpos($params['vehicle_owners_photo'], 'temp_upload') !== false) {
            //     $profile_photo = str_replace('public/temp_upload/', 'public/upload/vehicle_owners_photos/', $params['vehicle_owners_photo']);

            //     rename($params['vehicle_owners_photo'], $profile_photo);

            //     $params['vehicle_owners_photo'] = $profile_photo;
            // }
            if (strpos($params['vehicle_owners_photo'], 'temp_upload') !== false) {
                $imagePath = $params['vehicle_owners_photo'];
                $imageData = explode('/', $imagePath);
                $imageName = $imageData[2];
                $path = "public/upload/vehicle_owners_photos";
                $deviceImage = $this->awsImageUpload($imagePath, $imageName, $path);
                $dats = explode('/', $deviceImage);
                $params['vehicle_owners_photo'] = $path.'/'.$dats[6];
                if (isset($params['vehicle_owners_photo']) && strlen($params['vehicle_owners_photo']) > 0) {
                    unlink($imagePath);
                }
            }
        }else{
            $params['vehicle_owners_photo'] = null;
        }
        
        //Pass params to Model

        $params['c_created_by'] = $this->session->userdata('user_id');
        // echo "<pre>";print_r($params);
        $response = $this->adminmodel->create_new_customer_records($params);
        //  echo "<pre>";print_r($response);exit;

        if (empty($response)) {

            $returnResponse['error'] = "Please Enter valid Details.";

            echo json_encode($returnResponse);
            exit();

        }


        // Set Session

        $returnResponse['success'] = true;

        $returnResponse['redirect'] = 'admin/customers_list';

        $returnResponse['message'] = 'New Customer Entry has been created successfully.';

        echo json_encode($returnResponse);
        exit();

    }


    // Agents

    public function create_new_vehicle_model_records()

    {


        $params = $this->input->post();

        $returnResponse = array();

        $returnResponse['validation'] = array();

        $returnResponse['error'] = "";

        $returnResponse['success'] = "";

        // Validation

        $this->form_validation->set_rules('ve_make_id', 'Vehicle Make', 'trim|required');

        $this->form_validation->set_rules('ve_model_name', 'Vehicle Model', 'trim|required');


        $this->form_validation->set_rules(

            've_model_name', 'Vehicle Model',

            array(

                'required',

                array(

                    'already_exits',

                    function ($str) {

                        $arg = $this->input->post();

                        return $this->commonmodel->verify_exits_model_make($str, $arg['ve_make_id']);

                    }

                )

            )

        );


        // Validation verify

        if ($this->form_validation->run() == FALSE) {

            $returnResponse['validation'] = $this->form_validation->error_array();

            echo json_encode($returnResponse);
            exit();

        }

        $params['ve_created_by'] = $this->session->userdata('user_id');

        $response = $this->adminmodel->create_new_vehicle_model_records($params);

        if (empty($response)) {

            $returnResponse['error'] = "Please Enter valid Credentials.";

            echo json_encode($returnResponse);
            exit();

        }


        // Set Session

        $returnResponse['success'] = true;

        $returnResponse['redirect'] = 'admin/vehicle_model_list';

        $returnResponse['message'] = 'Model has been created successfully.';

        echo json_encode($returnResponse);
        exit();

    }


    // Create New Company Records

    public function create_new_company_records()

    {

        $params = $this->input->post();

        $returnResponse = array();

        $returnResponse['validation'] = array();

        $returnResponse['error'] = "";

        $returnResponse['success'] = "";

        // Validation

        $this->form_validation->set_rules('c_company_name', 'Company Name', 'trim|required');

        $this->form_validation->set_rules('c_cop_validity', 'Cop Validity', 'trim|required');


        $this->form_validation->set_rules(

            'c_company_name', 'Company Name',

            array(

                'required',

                array(

                    'already_exits',

                    function ($str) {

                        return $this->commonmodel->verify_exits_company_name($str);

                    }

                )

            )

        );

        // $this->form_validation->set_rules(

        //     'c_tac_no[0]', 'Tac Number',

        //     array(

        //         'required',

        //         array(

        //             'already_exits',

        //             function ($str) {

        //                 $c_tac_no = $this->input->post('c_tac_no');

        //                 $resultArray = $this->commonmodel->verify_exits_company_tac_number($c_tac_no);

        //                 if (empty($resultArray)) {

        //                     return true;

        //                 }

        //                 return false;

        //             }

        //         )

        //     )

        // );


        // Validation verify

        if ($this->form_validation->run() == FALSE) {

            $returnResponse['validation'] = $this->form_validation->error_array();

            echo json_encode($returnResponse);
            exit();

        }

        //Pass params to Model

        $params['c_created_by'] = $this->session->userdata('user_id');

        $response = $this->adminmodel->create_new_company_records($params);

        if (empty($response)) {

            $returnResponse['error'] = "Please Enter valid Details.";

            echo json_encode($returnResponse);
            exit();

        }


        // Set Session

        $returnResponse['success'] = true;

        $returnResponse['redirect'] = 'admin/company_list';

        $returnResponse['message'] = 'New Manufacturer has been created successfully.';

     // echo "<pre>"; print_r($returnResponse); exit;

        echo json_encode($returnResponse);
        exit();

    }

    public function create_new_product_records()

    {

        $params = $this->input->post();

        //var_dump($params);exit;

        $returnResponse = array();

        $returnResponse['validation'] = array();

        $returnResponse['error'] = "";

        $returnResponse['success'] = "";

        // Validation

        $this->form_validation->set_rules('p_company_id', 'Company Name', 'trim|required');

        $this->form_validation->set_rules('p_product_name', 'Product Name', 'trim|required');

        $this->form_validation->set_rules('p_unit_price', 'Unit Price', 'trim|required');

        $this->form_validation->set_rules('p_product_description', 'Description', 'trim|required');

        
        $this->form_validation->set_rules(

            'p_tac_no[0]', 'Tac Number',

            array(

                'required',

                array(

                    'already_exits',

                    function ($str) {

                        $p_tac_no = $this->input->post('p_tac_no');

                        $resultArray = $this->commonmodel->verify_exits_product_tac_number($p_tac_no);

                        if (empty($resultArray)) {

                            return true;

                        }

                        return false;

                    }

                )

            )

        );
        


        $this->form_validation->set_rules(

            'p_product_name', 'Product Name',

            array(

                'required',

                array(

                    'already_exits',

                    function ($str) {

                        return $this->commonmodel->verify_exits_product_name($str);

                    }

                )

            )

        );


        // Validation verify

        if ($this->form_validation->run() == FALSE) {

            $returnResponse['validation'] = $this->form_validation->error_array();

            echo json_encode($returnResponse);
            exit();

        }

        //Pass params to Model

        $params['p_created_by'] = $this->session->userdata('user_id');

        $response = $this->adminmodel->create_new_product_records($params);

       // echo "<pre>"; print_r($response); exit;

        if (empty($response)) {

            $returnResponse['error'] = "Please Enter valid Details.";

            echo json_encode($returnResponse);
            exit();

        }


        // Set Session

        $returnResponse['success'] = true;

        $returnResponse['redirect'] = 'admin/product_list';

        $returnResponse['message'] = 'New Product Entry has been created successfully.';

        echo json_encode($returnResponse);
        exit();

    }


    // Create New Vehicle Make Records

    public function create_new_vehicle_make_records()

    {

        $params = $this->input->post();

        $returnResponse = array();

        $returnResponse['validation'] = array();

        $returnResponse['error'] = "";

        $returnResponse['success'] = "";

        // Validation

        $this->form_validation->set_rules('v_vehicle_make', 'Make Name', 'trim|required');


        $this->form_validation->set_rules(

            'v_vehicle_make', 'Make Name',

            array(

                'required',

                array(

                    'already_exits',

                    function ($str) {

                        return $this->commonmodel->verify_exits_make_name($str);

                    }

                )

            )

        );


        // Validation verify

        if ($this->form_validation->run() == FALSE) {

            $returnResponse['validation'] = $this->form_validation->error_array();

            echo json_encode($returnResponse);
            exit();

        }

        //Pass params to Model

        $params['v_created_by'] = $this->session->userdata('user_id');

        $response = $this->adminmodel->create_new_vehicle_make_records($params);

        if (empty($response)) {

            $returnResponse['error'] = "Please Enter valid Details.";

            echo json_encode($returnResponse);
            exit();

        }


        // Set Session

        $returnResponse['success'] = true;

        $returnResponse['redirect'] = 'admin/vehicle_make_list';

        $returnResponse['message'] = 'New Vehicle Make has been created successfully.';

        echo json_encode($returnResponse);
        exit();

    }


    public function company_list()

    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'menu_inventry_list')) {
            redirect(base_url(), 'refresh');
            exit();
        }


        $_SESSION['currentActivePage'] = 'Company_List';


        $limit = LIST_PAGE_LIMIT;

        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;

        if ($offset != 0) {

            $offset = ((int)$limit * (int)$offset) - (int)$limit;

        }

        $search = isset($_GET['search']) ? $_GET['search'] : '';

        $data['totalNoOfCompanys'] = $this->commonmodel->totalNoOfDealers();

        $data['listofCommpanys'] = $this->commonmodel->listofcompanys($limit, $offset, $search);

        //print_r($data['listofCommpanys']);exit();

        $this->load->view('masters/company_list', $data);

    }

    //24-1-2022 manoj starts
    public function device_configuration_list()
    {
        $user_type = $this->session->userdata('user_type');
        // if (!check_permission($user_type, 'menu_inventry_list')) {
        //     redirect(base_url(), 'refresh');
        //     exit();
        // }
        $data = array();
        $this->load->view('masters/device_configuration_list', $data);
    }
    //24-1-2022 manoj ends

    public function product_list()

    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'menu_product_list')) {
            redirect(base_url(), 'refresh');
            exit();
        }


        $_SESSION['currentActivePage'] = 'Product_List';


        $limit = LIST_PAGE_LIMIT;

        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;

        if ($offset != 0) {

            $offset = ((int)$limit * (int)$offset) - (int)$limit;

        }

        $search = isset($_GET['search']) ? $_GET['search'] : '';

        $data['totalNoOfProducts'] = $this->commonmodel->totalNoOfProducts();

        $data['listofProducts'] = $this->commonmodel->listofProducts($limit, $offset, $search);

        $data['company_list'] = $this->commonmodel->allCompanyList();

        //print_r($data['listofCommpanys']);exit();

        $this->load->view('masters/product_list', $data);

    }

//------------ Addon Starts -------------- //

    public function certificate_list()
    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'menu_stocks_cerificate_allocation')) {
            redirect(base_url(), 'refresh');
            exit();
        }


        $_SESSION['currentActivePage'] = 'Certificate_List';

        $limit = LIST_PAGE_LIMIT;

        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;

        if ($offset != 0) {

            $offset = ((int)$limit * (int)$offset) - (int)$limit;

        }

        $search = isset($_GET['search']) ? $_GET['search'] : '';

        $data['totalNoOfCompanys'] = $this->commonmodel->totalNoOfDealers();

        $data['listofCommpanys'] = $this->commonmodel->listofcertificates($limit, $offset, $search);
        //print_r("<pre>");
        //print_r($data['listofCommpanys']);
        //print_r("</pre>");
        //exit();

        $this->load->view('masters/certificate_list', $data);

    }


// ---------------------------------------//

    public function vehicle_make_list()

    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'menu_vehicle_make_list')) {
            redirect(base_url(), 'refresh');
            exit();
        }
        //Permission

        $_SESSION['currentActivePage'] = 'Vehicle_Make_List';


        $limit = LIST_PAGE_LIMIT;

        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;

        if ($offset != 0) {

            $offset = ((int)$limit * (int)$offset) - (int)$limit;

        }

        $search = isset($_GET['search']) ? $_GET['search'] : '';

        $data['totalNoOfMakeList'] = $this->commonmodel->totalNoOfvehicleMake();

        $data['listofVehicleMakes'] = $this->commonmodel->listofVehicleMakes($limit, $offset, $search);

        //print_r($data['listofVehicleMakes']);exit();

        $this->load->view('masters/vehicle_make_list', $data);

    }


    // Admin Create New Vehicle Model

    public function add_serial_number()
    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'menu_stocks_inward')) {
            redirect(base_url(), 'refresh');
            exit();
        }


        $data['company_list'] = $this->commonmodel->allCompanyList();

        $data['countryList'] = $this->commonmodel->allCountryList();

        $this->session->set_userdata('currentActivePage', 'STOCK_INWARD');

        // Load Content
        // echo "<pre>";print_r($data);exit;
        $this->load->view('masters/add_serial_number', $data);

    }


    // Admin Create New Vehicle Model

    public function assign_serial_number()

    {

        $user_type = $this->session->userdata('user_type');

        if (!isset($user_type) && ((string)$user_type === '' || (string)$user_type == '2')) {

            redirect(base_url(), 'refresh');

            exit();

        }

        $data['company_list'] = $this->commonmodel->allCompanyList();
        if ($_POST['hid_company_id']) {
            $data['product_list'] = $this->commonmodel->companyProductList($_POST['hid_company_id']);
        }
        //var_dump($_POST['serial_ids']);exit;
        // $data['serial_list'] = $this->commonmodel->fetch_list_of_selected_serial_numbers(['serial_ids' => $_POST['serial_ids']]);

        // $data['distributor_list'] = $this->commonmodel->fetch_list_of_users(['user_type' => 2], 0);
        // $data['dealer_list'] = $this->commonmodel->fetch_list_of_dealers(['user_type' => 1], 0);

        $data['serial_list'] = $this->commonmodel->fetch_list_of_selected_serial_numbers(['serial_ids' => $_POST['serial_ids']]);

        $data['distributor_list'] = $this->commonmodel->fetch_list_of_users(['user_type' => 2], 0);
        // $data['dealer_list'] = $this->commonmodel->fetch_list_of_dealers(['user_type' => 1], 0);

        $data['dealer_list'] = $this->commonmodel->fetch_list_of_dealers_by_distributorId($data['serial_list'][0]['s_distributor_id']);

        // echo "<pre>";print_r($data['dealer_list']);exit();

        $this->session->set_userdata('currentActivePage', 'Assign_Serial_Number');

        // Load Content

        $this->load->view('masters/create_vehicle_serial_numbers', $data);

    }


// --- Addons Started ----&&&&&&&&&&&&&&&&&&&&&


    public function create_certificate()

    {

        $user_type = $this->session->userdata('user_type');

        if (!isset($user_type) && (string)$user_type === '') {

            redirect(base_url(), 'refresh');

            exit();

        }
        // echo "<pre>";print_r('hi');exit;

        $data['company_list'] = $this->commonmodel->allCompanyList();

        //Views

        $_SESSION['currentActivePage'] = 'Create_Entry';

        $this->load->view('masters/create_vehicle_certificate', $data);


    }


    public function create_No_of_Certificates()

    {

        $params = $this->input->post();

        $returnResponse = array();

        $returnResponse['validation'] = array();

        $returnResponse['error'] = "";

        $returnResponse['success'] = "";

        // Validation

        $this->form_validation->set_rules('s_company_id', 'Company Name', 'trim|required');

        $this->form_validation->set_rules('No_of_Certificates', 'No of Certificates', 'trim|required');

        $this->form_validation->set_rules('s_user_type', 'User Type', 'trim|required');

        $this->form_validation->set_rules('s_user_id', 'User Name', 'trim|required');

        //$exits_records=$this->commonmodel->verify_exits_serial_number($params['s_serial_number']);

        //Pass params to Model

        $params['s_created_by'] = $this->session->userdata('user_id');

        $response = $this->adminmodel->create_No_of_Certificates_records($params);


        //$returnResponse['error']= "STOP";
        //echo json_encode($returnResponse);
        //exit();

        if (empty($response)) {
            $returnResponse['error'] = "Please Enter valid Details.";
            echo json_encode($returnResponse);
            exit();

        }


        // Set Session

        $returnResponse['success'] = true;
        $returnResponse['redirect'] = 'admin/renewal_list';
        $returnResponse['message'] = 'Certificates assigned successfully.';

        echo json_encode($returnResponse);
        exit();

    }



// --- Addons Ends ---&&&&&&&&&&&&&&&


    // Create New Vehicle Make Records

    public function create_new_serial_numbers_records()

    {
        
        $params = $this->input->post();
        
        $returnResponse = array();

        $returnResponse['validation'] = array();

        $returnResponse['error'] = "";

        $returnResponse['success'] = "";

        // Validation

        $this->form_validation->set_rules('s_company_id', 'Company Name', 'trim|required');

        $this->form_validation->set_rules('s_product_id', 'Product', 'trim|required');

        $this->form_validation->set_rules('mode', 'Mode', 'trim|required');

        if ($params['mode'] == 'list') {
            $this->form_validation->set_rules('s_serial_number', 'Serial Number', 'trim|required');
            $tmp = explode(',', $_POST['s_serial_number']);
        } else {
            //$this->form_validation->set_rules('file', 'CSV file', 'required');


            $tmp = [];
            if (($handle = fopen($_FILES['file']['tmp_name'], "r")) !== FALSE) {
                while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $tmp[] = $row[0] . '-' . $row[1] . '-' . $row[2];
                }
                fclose($handle);
            }

            $params['s_serial_number'] = implode(',', $tmp);
            $_POST['s_serial_number'] = $params['s_serial_number'];
        }
        $serial_valid = true;
        $invalid_serials = [];

        $validSerialNumberList = [];
        $inValidSerialNumberList = [];
        $validImeiNumberList = [];
        $inValidImeiNumberList = [];
        
        foreach ($tmp as $tmp1) {
            $tmp2 = explode('-', $tmp1);
            if (empty($tmp2[0]) || empty($tmp2[1])) {
                $invalid_serials[] = $tmp1;
                $serial_valid = false;
            }
            if (!empty($tmp2[0]) && in_array(trim($tmp2[0]), $validSerialNumberList)) {
                $inValidSerialNumberList[] = str_replace($tmp2[0], '<b style="color:red;">' . $tmp2[0] . '</b>', $tmp1);
            } else {
                $validSerialNumberList[] = $tmp2[0];
            }
            if (!empty($tmp2[1]) && in_array(trim($tmp2[1]), $validImeiNumberList)) {
                $inValidImeiNumberList[] = str_replace($tmp2[1], '<b style="color:red;">' . $tmp2[1] . '</b>', $tmp1);
            } else {
                $validImeiNumberList[] = $tmp2[1];
            }
        }

        $_POST['serial_valid'] = $serial_valid;

        $this->form_validation->set_rules('serial_valid', 'Serial Numbers', 'required');

        if ($serial_valid) {
            $exits_records = $this->commonmodel->verify_exits_serial_number($params['s_serial_number']);

            if (count($exits_records) > 0) {
                foreach ($exits_records as $key => $value) {
                    if (isset($value['s_serial_number']) && in_array($value['s_serial_number'], $validSerialNumberList)) {
                        $inValidSerialNumberList[] = '<b style="color:red;">' . $value['s_serial_number'] . '</b>';
                    }
                    if (isset($value['s_imei']) && in_array($value['s_imei'], $validImeiNumberList)) {
                        $inValidImeiNumberList[] = '<b style="color:red;">' . $value['s_imei'] . '</b>';
                    }
                }
            }

            $this->form_validation->set_rules(

                's_serial_number', 'Serial Number',

                array(

                    'required',

                    array(

                        'already_exits',

                        function ($str) {


                            $resultSet = $this->commonmodel->verify_exits_serial_number($str);

                            if (empty($resultSet)) {

                                return true;

                            } else {

                                return false;

                            }

                        }

                    )

                )

            );
        }


        // Validation verify

        if ($this->form_validation->run() == FALSE) {

            $returnResponse['validation'] = $this->form_validation->error_array();
            if (count($inValidSerialNumberList) > 0) {
                $returnResponse['validation']['serial_valid'] = 'The following serial numbers are invalid (either Serial Number or IMEI missing).' . "<br />" . implode('<br>', $inValidSerialNumberList);
            } else if (count($inValidImeiNumberList) > 0) {
                $returnResponse['validation']['serial_valid'] = 'The following serial numbers are invalid (either Serial Number or IMEI missing).' . "<br />" . implode('<br>', $inValidImeiNumberList);
            } else if (isset($returnResponse['validation']['serial_valid']) && count($invalid_serials) > 0) {
                $returnResponse['validation']['serial_valid'] = 'The following serial numbers are invalid (either Serial Number or IMEI missing).' . "<br />" . implode('<br>', $invalid_serials);
            } else if (isset($returnResponse['validation']['s_serial_number']) && count($exits_records) > 0) {
                $exSerials = array();
                //var_dump($exits_records);exit;
                foreach ($exits_records as $key => $value) {

                    if (isset($value['s_serial_number'])) {
                        $exSerials[] = $value['s_serial_number'] . '-' . $value['s_imei'] . '-' . $value['s_mobile'];
                    }
                }
                $exSerials = array_unique($exSerials);
                $returnResponse['validation']['s_serial_number'] = 'The following serial number exits from our records.' . "<br />" . implode('<br>', $exSerials);
            }
            echo json_encode($returnResponse);
            exit();
        }

        //Pass params to Model

        $params['s_created_by'] = $this->session->userdata('user_id');
        // echo "<pre>";print_r($params);exit;
        $response = $this->adminmodel->create_new_serial_numbers_records($params);
        
        if (empty($response)) {

            $returnResponse['error'] = "Please Enter valid Details.";

            echo json_encode($returnResponse);
            exit();

        }


        // Set Session

        $returnResponse['success'] = true;

        $returnResponse['redirect'] = 'admin/unassigned_serial_number_list';

        $returnResponse['message'] = 'Serial Numbers added successfully.';

        echo json_encode($returnResponse);
        exit();

    }

    public function assign_new_serial_numbers_records()

    {

        $params = $this->input->post();

        $returnResponse = array();

        $returnResponse['validation'] = array();

        $returnResponse['error'] = "";

        $returnResponse['success'] = "";

        // Validation

        $this->form_validation->set_rules('s_company_id', 'Company Name', 'trim|required');

        $this->form_validation->set_rules('s_product_id', 'Product', 'required');

        $this->form_validation->set_rules('s_serial_id[]', 'Serial Number', 'required');

        $user_type = $this->session->userdata('user_type');

        //var_dump($params);exit;
        if ($params['hid_mode'] == 'unassigned') {
            $this->form_validation->set_rules('s_distributor_id', 'Distributor Name', 'trim|required');
            // $this->form_validation->set_rules('distributor_price', 'Distributor Price', "trim|required|greater_than[{$params['h_admin_price']}]");
        } else {
            $this->form_validation->set_rules('s_dealer_id', 'Dealer Name', 'trim|required');
            // $this->form_validation->set_rules('dealer_price', 'Dealer Price', "trim|required|greater_than[{$params['h_admin_price']}]|greater_than[{$params['h_distributor_price']}]");
        }

        $exits_records = 0;
        /*
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
*/


        // Validation verify

        if ($this->form_validation->run() == FALSE) {

            $returnResponse['validation'] = $this->form_validation->error_array();

            /*
			 if(isset($returnResponse['validation']['s_serial_id']) && count($exits_records)>0)
			 {
			 	$exSerials=array();
			 	foreach ($exits_records as $key => $value) {

			 		if(isset($value['s_serial_id']))
			 		{
			 			$exSerials[]=$value['s_serial_id'];
			 		}
			 	}
			 	$exSerials=array_unique($exSerials);
			 	$returnResponse['validation']['s_serial_id']='The following serial number exits from our records.'."<br />".implode(',', $exSerials);
			 }*/
            echo json_encode($returnResponse);
            exit();
        }

        //Pass params to Model

        $params['s_created_by'] = $this->session->userdata('user_id');

        $response = $this->adminmodel->assign_serial_numbers_records($params);

        if (empty($response)) {

            $returnResponse['error'] = "Please Enter valid Details.";

            echo json_encode($returnResponse);
            
            exit();

        }


        // Set Session

        $returnResponse['success'] = true;

        if ($params['hid_mode'] == 'unassigned') {
            $returnResponse['redirect'] = 'admin/unassigned_serial_number_list';
        } else {
            $returnResponse['redirect'] = 'admin/serial_number_list';
        }

        $returnResponse['message'] = 'Serial Number assigned successfully.';

        echo json_encode($returnResponse);
        exit();

    }


    public function update_unassigned_serial_numbers_records()
    {
        // print_r($params);exit();
        $params = $this->input->post();

        $params['s_serial_id'] = base64_decode($params['s_serial_id']);

        $returnResponse = array();

        $returnResponse['validation'] = array();

        $returnResponse['error'] = "";

        $returnResponse['success'] = "";

        // Validation

        $this->form_validation->set_rules('s_company_id', 'Company Name', 'trim|required');

        $this->form_validation->set_rules('s_product_id', 'Product', 'trim|required');

        $this->form_validation->set_rules('s_serial_number', 'Serial Number', 'trim|required');

        $this->form_validation->set_rules('s_state_id', 'State', 'trim|required');

        //$this->form_validation->set_rules('s_user_type', 'User Type', 'trim|required');

        //$this->form_validation->set_rules('s_user_id', 'User Name', 'trim|required');

        //$this->form_validation->set_rules('s_serial_number', 'Serial Number', 'trim|required');


        // Hide & Direct to New Function:

        //$exits_records=$this->commonmodel->verify_exits_serial_number($params['s_serial_number'],$params['s_serial_id'],$params['s_imei']);

// Direct to New Function:
        $exits_records = $this->commonmodel->verify_exits_IMEI_numbers($params['s_serial_number'], $params['s_serial_id'], $params['s_imei'], $params['s_mobile'], $params['s_mobile_2'], $params['s_iccid'], $params['s_state_id']);

        $this->form_validation->set_rules(

            's_serial_number', 'Serial Number',

            array('required',

                array(

                    'already_exits',

                    function ($str) {

                        $arg = $this->input->post();

                        $arg['s_serial_id'] = base64_decode($arg['s_serial_id']);

                        //	$resultSet=$this->commonmodel->verify_exits_serial_number($str,$arg['s_serial_id'],$arg['s_imei']);
                        // $resultSet = $this->commonmodel->verify_exits_IMEI_numbers($arg['s_serial_number'], $arg['s_serial_id'], $arg['s_imei'], $arg['s_mobile']);
                        // $resultSet = $this->commonmodel->verify_exits_IMEI_numbers($arg['s_serial_number'], $arg['s_serial_id'], $arg['s_imei'], $arg['s_mobile'],$arg['s_mobile_2'],$arg['s_iccid']);
                        $resultSet = $this->commonmodel->verify_exits_IMEI_number($arg['s_serial_number'], $arg['s_serial_id'], $arg['s_imei'], $arg['s_iccid']);

                        if (empty($resultSet)) {

                            return true;

                        } else {

                            return false;
                        }
                    }
                )
            )
        );
        
        if($params['s_mobile']==$params['s_mobile_2']){   
            $returnResponse['validation'] = $this->form_validation->error_array();
            $returnResponse['validation']['s_mobile'] = 'Sim 1 and Sim 2 value is same, Please Check .';
            echo json_encode($returnResponse);
            exit();
        }

        // Validation verify

        if ($this->form_validation->run() == FALSE) {
            $returnResponse['validation'] = $this->form_validation->error_array();


            if (isset($returnResponse['validation']['s_serial_number']) && count($exits_records) > 0) {

                $exSerials = array();


                foreach ($exits_records as $key => $value) {

                    if (isset($value['s_serial_number'])) {
                        // $exSerials[] = $value['s_serial_number'] . "-" . $value['s_imei'] . "-" . $value['s_mobile'];
                        // $exSerials[] = $value['s_serial_number'] . "-" . $value['s_imei'] . "-" . $value['s_mobile'] . "-" . $value['s_mobile_2'] . "-" . $value['s_iccid'];
                        $exSerials[] = $value['s_serial_number'] . "-" . $value['s_imei'] . "-" . $value['s_iccid'];
                    }
                }


                $exSerials = array_unique($exSerials);
                
                $returnResponse['validation']['s_serial_number'] = 'Entered Values are exists with our records, Please Check .' . "<br />" . implode(',', $exSerials) . "Ex.(UIN-IMEI-Mobile)";
            }
            // echo "<pre>";print_r($returnResponse);exit;
            echo json_encode($returnResponse);

            exit();

        }


        //Pass params to Model

        $response = $this->adminmodel->modify_new_serial_numbers_records($params);

        if (empty($response)) {

            $returnResponse['error'] = "Please Enter valid Credentials.";

            echo json_encode($returnResponse);
            exit();

        }

        // Set Session

        $this->session->set_userdata($response);

        $returnResponse['success'] = true;

        $returnResponse['redirect'] = 'admin/unassigned_serial_number_list';

        $returnResponse['message'] = 'Serial number Updated successfully.';

        echo json_encode($returnResponse);
        exit();

    }

    public function update_serial_numbers_records()
    {

        $params = $this->input->post();

        $params['s_serial_id'] = base64_decode($params['s_serial_id']);

        //print_r($params);exit();

        $returnResponse = array();

        $returnResponse['validation'] = array();

        $returnResponse['error'] = "";

        $returnResponse['success'] = "";

        // Validation

        $this->form_validation->set_rules('s_company_id', 'Company Name', 'trim|required');

        $this->form_validation->set_rules('s_product_id', 'Product', 'trim|required');

        $this->form_validation->set_rules('s_serial_number', 'Serial Number', 'trim|required');

        //$this->form_validation->set_rules('s_user_type', 'User Type', 'trim|required');

        //$this->form_validation->set_rules('s_user_id', 'User Name', 'trim|required');

        //$this->form_validation->set_rules('s_serial_number', 'Serial Number', 'trim|required');


        $exits_records = $this->commonmodel->verify_exits_serial_number($params['s_serial_number'], $params['s_serial_id']);

        $this->form_validation->set_rules(

            's_serial_number', 'Serial Number',

            array(

                'required',

                array(

                    'already_exits',

                    function ($str) {

                        $arg = $this->input->post();

                        $arg['s_serial_id'] = base64_decode($arg['s_serial_id']);

                        $resultSet = $this->commonmodel->verify_exits_serial_number($str, $arg['s_serial_id']);

                        if (empty($resultSet)) {

                            return true;

                        } else {

                            return false;

                        }

                    }

                )

            )

        );


        // Validation verify

        if ($this->form_validation->run() == FALSE) {

            $returnResponse['validation'] = $this->form_validation->error_array();

            if (isset($returnResponse['validation']['s_serial_number']) && count($exits_records) > 0) {

                $exSerials = array();

                foreach ($exits_records as $key => $value) {

                    if (isset($value['s_serial_number'])) {

                        $exSerials[] = $value['s_serial_number'];

                    }

                }

                $exSerials = array_unique($exSerials);

                $returnResponse['validation']['s_serial_number'] = 'The following serial number exits from our records.' . "<br />" . implode(',', $exSerials);

            }

            echo json_encode($returnResponse);
            exit();

        }

        //Pass params to Model

        $response = $this->adminmodel->modify_new_serial_numbers_records($params);

        if (empty($response)) {

            $returnResponse['error'] = "Please Enter valid Credentials.";

            echo json_encode($returnResponse);
            exit();

        }

        // Set Session

        $this->session->set_userdata($response);

        $returnResponse['success'] = true;

        $returnResponse['redirect'] = 'admin/serial_number_list';

        $returnResponse['message'] = 'Serial number Updated successfully.';

        echo json_encode($returnResponse);
        exit();

    }


    public function unassigned_serial_number_list()
    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'menu_stocks_assign_to_distributer')) {
            redirect(base_url(), 'refresh');
            exit();
        }


        $_SESSION['currentActivePage'] = 'Unassigned_Serial_Number_List';

        $limit = LIST_PAGE_LIMIT;

        $data['num_recs'] = [10, 25, 50, 75, 100];

        if (isset($_GET['recs'])) {
            $limit = $_GET['recs'];
        } else {
            $_GET['recs'] = $limit;
        }


        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;

        if ($offset != 0) {

            $offset = ((int)$limit * (int)$offset) - (int)$limit;

        }

        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $company_id = isset($_GET['company_id']) ? $_GET['company_id'] : '';
        $country = isset($_GET['s_country_id']) ? $_GET['s_country_id'] : 0;
        $state = isset($_GET['s_state_id']) ? $_GET['s_state_id'] : 0;

        $data['totalNoOfSerialNumbers'] = $this->commonmodel->totalNoOfUnassignedSerialNumbers();
        $data['listofSerialNumbers'] = $this->commonmodel->listofUnassignedSerialNumbersData($limit, $offset, $search, $company_id);
        $data['company_list'] = $this->commonmodel->allCompanyList();
        // $data['countryList'] = $this->commonmodel->allCountryList();
        $data['stateList'] = $this->commonmodel->activeStateList();
        // if (isset($_GET['s_country_id']) != "") {
        //     $data['stateList'] = $this->commonmodel->getStateInfoByCountryId($country);
        // }
        // else{
        //     $data['stateList'] = '';
        // }

        if ($company_id) {
            $data['product_list'] = $this->commonmodel->companyProductList($company_id);
        }
        // echo "<pre>";print_r($data);exit;
        $this->load->view('masters/unassigned_serial_number_list', $data);
    }

    public function serial_number_list()
    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'menu_stocks_assign_to_dealer')) {
            redirect(base_url(), 'refresh');
            exit();
        }
        //Permission
        $user_type = $this->session->userdata('user_type');

        // if(!isset($user_type) && ((string)$user_type==='' ||  (string)$user_type =='2'))

        // {

        // 	 redirect(base_url(), 'refresh');

        // 	 exit();

        // }

        $_SESSION['currentActivePage'] = 'Serial_Number_List';


        $limit = LIST_PAGE_LIMIT;

        $data['num_recs'] = [10, 25, 50, 75, 100];

        if (isset($_GET['recs'])) {
            $limit = $_GET['recs'];
        } else {
            $_GET['recs'] = $limit;
        }

        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;

        if ($offset != 0) {

            $offset = ((int)$limit * (int)$offset) - (int)$limit;

        }

        $search = isset($_GET['search']) ? $_GET['search'] : '';

        $company_id = isset($_GET['company_id']) ? $_GET['company_id'] : '';
        $country = isset($_GET['s_country_id']) ? $_GET['s_country_id'] : 0;
        $state = isset($_GET['s_state_id']) ? $_GET['s_state_id'] : 0;

        $data['limit']=$limit;
        $data['totalNoOfSerialNumbers'] = $this->commonmodel->totalNoOfSerialNumbers();
        $data['listofSerialNumbers'] = $this->commonmodel->listofSerialNumbers($limit, $offset, $search, $company_id);
        $data['company_list'] = $this->commonmodel->allCompanyList();
        // $data['countryList'] = $this->commonmodel->allCountryList();
        $data['stateList'] = $this->commonmodel->activeStateList();
        // if (isset($_GET['s_country_id']) != "") {
        //     $data['stateList'] = $this->commonmodel->getStateInfoByCountryId($country);
        // }
        // else{
        //     $data['stateList'] = '';
        // }

        if ($company_id) {
            $data['product_list'] = $this->commonmodel->companyProductList($company_id);
        }


        $this->load->view('masters/serial_number_list', $data);

    }

    public function assigned_serial_number_list()
    {
//Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'menu_stocks_assign_devices')) {
            redirect(base_url(), 'refresh');
            exit();
        }
        //Permission
        $user_type = $this->session->userdata('user_type');

        // if(!isset($user_type) && ((string)$user_type==='' ||  (string)$user_type =='2'))

        // {

        // 	 redirect(base_url(), 'refresh');

        // 	 exit();

        // }

        $_SESSION['currentActivePage'] = 'Serial_Number_List';


        $limit = LIST_PAGE_LIMIT;

        $data['num_recs'] = [10, 25, 50, 75, 100];

        if (isset($_GET['recs'])) {
            $limit = $_GET['recs'];
        } else {
            $_GET['recs'] = $limit;
        }

        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;
        
        if ($offset != 0) {

            $offset = ((int)$limit * (int)$offset) - (int)$limit;

        }

        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $country = isset($_GET['s_country_id']) ? $_GET['s_country_id'] : 0;
        $state = isset($_GET['s_state_id']) ? $_GET['s_state_id'] : 0;
        
        // echo "<pre>";print_r($used_status);exit;
        $company_id = isset($_GET['company_id']) ? $_GET['company_id'] : '';
        $data['limit']=$limit;
        $data['totalNoOfSerialNumbers'] = $this->commonmodel->totalNoOfAssignedSerialNumbers();
        // $data['countryList'] = $this->commonmodel->allCountryList();
        $data['stateList'] = $this->commonmodel->activeStateList();
        $data['listofSerialNumbers'] = $this->commonmodel->listofAssignedSerialNumbers($limit, $offset, $search, $company_id);
        $data['company_list'] = $this->commonmodel->allCompanyList();
        if ($company_id) {
            $data['product_list'] = $this->commonmodel->companyProductList($company_id);
        }
        // if (isset($_GET['s_country_id']) != "") {
        //     $data['stateList'] = $this->commonmodel->getStateInfoByCountryId($country);
        // }
        // else{
        //     $data['stateList'] = '';
        // }


        $this->load->view('masters/assigned_serial_number_list', $data);

    }

    public function count_customer_records()
    {
        $params = $this->input->post();
        
        // echo "<pre>";print_r($params);
        $vehicle_user_name = $params['c_customer_name'];
        $vehicle_user_phone= $params['c_phone'];
        $vehicle_user_id   = $params['c_customer_id'];

        $dataName 	= $this->adminmodel->getCustomerNameCustomer($vehicle_user_id);
        $name       = $dataName[0]['c_customer_name'];
        $mobile     = $dataName[0]['c_phone'];

        $data['count']= 0;
        if($mobile != $vehicle_user_phone ){
            $data['count'] = 1;
        }
        else{
        $data['count'] = 0;
        }
        // echo "<pre>";print_r($data);exit;
		echo json_encode($data);
		exit();

    }
    public function device_qrcode_old()
    {
        $imei=base64_decode($_GET['imei']);
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'cerificate_edit')) {
            redirect(base_url(), 'refresh');
            exit();
        }
        //Permission

        if (!isset($imei) || (string)$imei === '0') {

            redirect(base_url() . 'admin/dashboard', 'refresh');

        }

        $data['imeiInfo'] = $this->commonmodel->getImeiInfo($imei);


        if (empty($data['imeiInfo'])) {
            redirect(base_url() . 'admin/dashboard', 'refresh');
        }

        $data['pageTitle'] = 'Device QRcode';

        $user_id = $this->session->userdata('user_id');

        $this->load->view('masters/device_qrcode', $data);

    }


    public function device_qrcode()
    {
        if(isset($_GET['serialNumber'])){
            $serialNumber=base64_decode($_GET['serialNumber']);
            
            $user_type = $this->session->userdata('user_type');
            if (!check_permission($user_type, 'cerificate_download')) {
                redirect(base_url(), 'refresh');
                exit();
            }
            
            //Permission
            if (!isset($serialNumber) || (string)$serialNumber === '0') {
                redirect(base_url() . 'admin/dashboard', 'refresh');
            }
             
            $data['imeiInfo'] = $this->commonmodel->getSerialNumber($serialNumber);
            
            if (empty($data['imeiInfo'])) {
                redirect(base_url() . 'admin/dashboard', 'refresh');
            }

            $data['pageTitle'] = 'Device QRcode';

            $user_id = $this->session->userdata('user_id');

            $this->load->view('masters/device_qrcode', $data);
        }else {
            $imei=base64_decode($_GET['imei']);
            
            $user_type = $this->session->userdata('user_type');
            if (!check_permission($user_type, 'cerificate_download')) {
                redirect(base_url(), 'refresh');
                exit();
            }
            //Permission
//  echo "<pre>";print_r($imei);exit;
            if (!isset($imei) || (string)$imei === '0') {

                redirect(base_url() . 'admin/dashboard', 'refresh');

            }

            $data['imeiInfo'] = $this->commonmodel->getImeiInfo($imei);


            if (empty($data['imeiInfo'])) {
                redirect(base_url() . 'admin/dashboard', 'refresh');
            }

            $data['pageTitle'] = 'Device QRcode';

            $user_id = $this->session->userdata('user_id');

            $this->load->view('masters/device_qrcode', $data);
        }
    }


    public function edit_unassigned_serial_number()
    {
        $serial_number=base64_decode($_GET['q']);
        // Check Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'assign_to_distributer_edit')) {
            redirect(base_url(), 'refresh');
            exit();
        }
        //Permission

        if (!isset($serial_number)) {

            redirect(base_url() . 'admin/dashboard', 'refresh');
        }

        $data['SerialInfo'] = $this->commonmodel->getSerialNumberInfo($serial_number);

        if (empty($data['SerialInfo'])) {

            redirect(base_url() . 'admin/dashboard', 'refresh');

        }
        $data['stateList'] = $this->commonmodel->activeStateList();
        $data['SerialInfo']['s_serial_id'] = base64_encode($serial_number);

        $data['pageTitle'] = 'Edit Unassigned Serial Numbers';

        $_SESSION['currentActivePage'] = 'Unassigned_Serial_Number_List';

        $data['company_list'] = $this->commonmodel->allCompanyList();

        $data['product_list'] = $this->commonmodel->companyProductList($data['SerialInfo']['s_company_id']);


        $this->load->view('masters/edit_serial_number', $data);

    }

    public function edit_serial_number($serial_number)

    {

        $user_type = $this->session->userdata('user_type');

        if ((!isset($user_type) && (string)$user_type === '') || (string)$user_type != '0') {

            redirect(base_url(), 'refresh');

            exit();

        }

        if (!isset($serial_number)) {

            redirect(base_url() . 'admin/dashboard', 'refresh');

        }

        $data['SerialInfo'] = $this->commonmodel->getSerialNumberInfo($serial_number);

        if (empty($data['SerialInfo'])) {

            redirect(base_url() . 'admin/dashboard', 'refresh');

        }

        $data['SerialInfo']['s_serial_id'] = base64_encode($serial_number);

        $data['pageTitle'] = 'Edit Serial Numbers';

        $_SESSION['currentActivePage'] = 'Serial_Number_List';

        $data['company_list'] = $this->commonmodel->allCompanyList();

        $data['product_list'] = $this->commonmodel->companyProductList($data['SerialInfo']['s_company_id']);

        $this->load->view('masters/edit_vehicle_serial_numbers', $data);

    }


    public function fetch_list_of_users($needAdmin = 0)

    {

        $params = $this->input->post();

        $currentUserID = 0;

        if (isset($params['currentUserID'])) {

            $currentUserID = base64_decode($params['currentUserID']);

        }


        $list = $this->commonmodel->fetch_list_of_users($params, $needAdmin, $currentUserID);

        //echo $this->db->last_query();exit();

        $response = array();

        $response['list'] = $list;

        echo json_encode($response);
        exit();

    }


    public function fetch_saved_history()

    {




        $list = $this->commonmodel->fetchSavedHistory();

        //echo $this->db->last_query();exit();

        $response = array();

        $response['list'] = $list;

        echo json_encode($response);
        exit();

    }




    public function fetch_list_of_products()

    {

        $params = $this->input->post();

        $list = $this->commonmodel->fetch_list_of_products($params);

        //echo $this->db->last_query();exit();

        $response = array();

        $response['list'] = $list;

        echo json_encode($response);
        exit();

    }

    public function fetch_list_of_unassigned_serial_numbers()

    {

        $params = $this->input->post();

        $list = $this->commonmodel->fetch_list_of_unassigned_serial_numbers($params);

        //echo $this->db->last_query();exit();

        $response = array();

        $response['list'] = $list;
        // $response['min_admin_price'] = min(array_column($list, 'admin_price'));
        // $response['min_distributor_price'] = min(array_column($list, 'distributor_price'));
        // $response['min_dealer_price'] = min(array_column($list, 'dealer_price'));

        $response['min_admin_price'] = min(array_column($list, 'p_unit_price'));
        $response['min_distributor_price'] = min(array_column($list, 'p_unit_price'));
        $response['min_dealer_price'] = min(array_column($list, 'p_unit_price'));

        echo json_encode($response);
        exit();

    }


    // Admin Create New Vehicle Model

    public function create_vehicle_model()

    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'menu_vehicle_model_create')) {
            redirect(base_url(), 'refresh');
            exit();
        }


        $data['make_list'] = $this->commonmodel->allMakeList();

        $this->session->set_userdata('currentActivePage', 'Create_Vehicle_Model');

        // Load Content

        $this->load->view('masters/create_vehicle_model', $data);

    }


    public function vehicle_model_list()

    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'menu_vehicle_model_list')) {
            redirect(base_url(), 'refresh');
            exit();
        }


        $_SESSION['currentActivePage'] = 'Vehicle_Model_List';


        $limit = LIST_PAGE_LIMIT;

        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;

        if ($offset != 0) {

            $offset = ((int)$limit * (int)$offset) - (int)$limit;

        }


        $search = isset($_GET['search']) ? $_GET['search'] : '';

        $data['totalNoOfModelList'] = $this->commonmodel->totalNoOfvehicleModel();

        $data['listofVehicleModels'] = $this->commonmodel->listofVehicleModels($limit, $offset, $search);

        $data['make_list'] = $this->commonmodel->allMakeList();

        $MakeList = array();

        foreach ($data['listofVehicleModels'] as $key => $value) {

            if (isset($value['ve_make_id']) && !isset($MakeList[$value['ve_make_id']])) {

                $MakeList[$value['ve_make_id']] = array();

                $MakeList[$value['ve_make_id']]['name'] = $value['v_make_name'];

                $MakeList[$value['ve_make_id']]['list'] = array();

            }

            $MakeList[$value['ve_make_id']]['list'][] = $value;

        }

        //print_r($MakeList);exit();

        $data['MakeList'] = $MakeList;


        $this->load->view('masters/vehicle_model_list', $data);

    }


    public function edit_vehicle_model()

    {
        $modelID=base64_decode($_GET['q']);
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'vehicle_model_edit')) {
            redirect(base_url(), 'refresh');
            exit();
        }
        //Permission

        if (!isset($modelID)) {

            redirect(base_url() . 'admin/dashboard', 'refresh');

        }

        $data['ModelInfo'] = $this->commonmodel->getModelInfo($modelID);

        if (empty($data['ModelInfo'])) {

            redirect(base_url() . 'admin/dashboard', 'refresh');

        }

        $data['ModelInfo']['ve_model_id'] = base64_encode($modelID);

        $data['pageTitle'] = 'Edit Vehicle Model';

        $_SESSION['currentActivePage'] = 'Vehicle_Model_List';

        $data['make_list'] = $this->commonmodel->allMakeList();

        //print_r($data['ModelInfo']);exit();

        // Load Content

        $this->load->view('masters/edit_vehicle_model', $data);

    }


    public function update_vehicle_model_records()

    {

        $params = $this->input->post();

        $params['ve_model_id'] = base64_decode($params['ve_model_id']);

        //print_r($params);exit();

        $returnResponse = array();

        $returnResponse['validation'] = array();

        $returnResponse['error'] = "";

        $returnResponse['success'] = "";

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

                    function ($str) {

                        $arg = $this->input->post();

                        $arg['ve_model_id'] = base64_decode($arg['ve_model_id']);

                        return $this->commonmodel->verify_exits_model_make_records($str, $arg['ve_make_id'], $arg['ve_model_id']);

                    }

                )

            )

        );


        // Validation verify

        if ($this->form_validation->run() == FALSE) {

            $returnResponse['validation'] = $this->form_validation->error_array();

            echo json_encode($returnResponse);
            exit();

        }


        //Pass params to Model

        $response = $this->adminmodel->modify_model_records($params);

        if (empty($response)) {

            $returnResponse['error'] = "Please Enter valid Credentials.";

            echo json_encode($returnResponse);
            exit();

        }

        // Set Session

        $this->session->set_userdata($response);

        $returnResponse['success'] = true;

        $returnResponse['redirect'] = 'admin/vehicle_model_list';

        $returnResponse['message'] = 'Vehicle Model Details updated successfully.';

        echo json_encode($returnResponse);
        exit();

    }


   public function users_list()

    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'menu_user_list')) {
            redirect(base_url(), 'refresh');
            exit();
        }


        $_SESSION['currentActivePage'] = 'Users_List';

        //print_r($this->session->all_userdata());exit();

        $limit = LIST_PAGE_LIMIT;

        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;

        if ($offset != 0) {

            $offset = ((int)$limit * (int)$offset) - (int)$limit;

        }

        $search = isset($_GET['search']) ? $_GET['search'] : '';

        $data['totalNoOfDealers'] = $this->commonmodel->totalNoOfDealers();

        $data['listofdealers'] = $this->commonmodel->listofdealers($limit, $offset, $search);

        // echo "<pre>";print_r($data);exit();

        $this->load->view('masters/dealers_list', $data);

    }


    public function edit_company()

    {
        $companyID=base64_decode($_GET['q']);
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'inventry_edit')) {
            redirect(base_url(), 'refresh');
            exit();
        }

        if (!isset($companyID)) {

            redirect(base_url() . 'admin/dashboard', 'refresh');

        }

        $data['companyInfo'] = $this->commonmodel->getCompanyInfo($companyID);

        if (empty($data['companyInfo'])) {

            redirect(base_url() . 'admin/dashboard', 'refresh');

        }

        $data['companyInfo']['c_company_id'] = base64_encode($companyID);

        $data['pageTitle'] = 'Edit Company Profile';

        $_SESSION['currentActivePage'] = 'Company_List';

        // Load Content

        $this->load->view('masters/edit_company', $data);

    }

    public function edit_product()

    {
        $product_id=base64_decode($_GET['q']);
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'product_edit')) {
            redirect(base_url(), 'refresh');
            exit();
        }
        //Permission

        if (!isset($product_id)) {

            redirect(base_url() . 'admin/dashboard', 'refresh');

        }

        $data['productInfo'] = $this->commonmodel->getProductInfo($product_id);

        //echo "<pre>"; print_r($data); exit;

        if (empty($data['productInfo'])) {

            redirect(base_url() . 'admin/dashboard', 'refresh');

        }

        $data['productInfo']['p_product_id'] = base64_encode($product_id);

        $data['pageTitle'] = 'Edit Product';

        $_SESSION['currentActivePage'] = 'Product_List';

        $data['company_list'] = $this->commonmodel->allCompanyList();

        // Load Content

        $this->load->view('masters/edit_product', $data);

    }


    public function edit_vehicle_make()

    {

        $makeID=base64_decode($_GET['q']);
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'vehicle_make_edit')) {
            redirect(base_url(), 'refresh');
            exit();
        }
        //Permission

        if (!isset($makeID)) {

            redirect(base_url() . 'admin/dashboard', 'refresh');

        }

        $data['MakeInfo'] = $this->commonmodel->getMakeInfo($makeID);

        if (empty($data['MakeInfo'])) {

            redirect(base_url() . 'admin/dashboard', 'refresh');

        }

        $data['MakeInfo']['v_make_id'] = base64_encode($makeID);

        $data['pageTitle'] = 'Edit Vehicle Make';

        $_SESSION['currentActivePage'] = 'Vehicle_Make_List';

        // Load Content

        $this->load->view('masters/edit_vehicle_make', $data);

    }


    public function edit_users()

    {
        $dealerID=base64_decode($_GET['q']);
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'user_edit')) {
            redirect(base_url(), 'refresh');
            exit();
        }
        //Permission

        if (!isset($dealerID) || (string)$dealerID === '0') {

            redirect(base_url() . 'admin/dashboard', 'refresh');

        }

        $data['rto_list'] = $this->commonmodel->allRtoNumbers();
        if ($user_type == 4) {
            $data['states_list'] = $this->commonmodel->allStatesList();
        } else {
            $data['states_list'] = "";
        }
        $data['userinfo'] = $this->commonmodel->getDealerInfo($dealerID);

        if (empty($data['userinfo'])) {

            redirect(base_url() . 'admin/dashboard', 'refresh');

        }

        $data['userinfo']['user_id'] = base64_encode($dealerID);

        $data['pageTitle'] = 'Edit Profile';

        $_SESSION['currentActivePage'] = 'Users_List';

        $data['company_list'] = $this->commonmodel->allCompanyList();

        // Load Content

        $this->load->view('masters/edit_dealer', $data);

    }


    public function update_dealer_records()

    {

        $params = $this->input->post();
        echo "";print_r();
        $length = strlen($params['phone']);
        $params['user_id'] = base64_decode($params['user_id']);

        //print_r($params);exit();

        if (!isset($params['user_distributor_id'])) {

            $params['user_distributor_id'] = 0;

            //$params['user_distributor_id']=0;

        }

        $returnResponse = array();

        $returnResponse['validation'] = array();

        $returnResponse['error'] = "";

        $returnResponse['success'] = "";
        
        $returnResponse['mobileError'] = "";

        // Validation

        if($length < 10){
            $returnResponse['mobileError'] = True;
            echo json_encode($returnResponse);
            exit();
        }

        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');

        $this->form_validation->set_rules('user_company_id', 'Company ID', 'trim|required');


        $this->form_validation->set_rules('name', 'Name', 'trim|required');

        $this->form_validation->set_rules('phone', 'Phone', 'trim|required');

        // $this->form_validation->set_rules('email', 'Email', 'trim|required');
        if ($params["user_type"] != 6) {
            $this->form_validation->set_rules('email', 'Email', 'trim|required');
        }

        $this->form_validation->set_rules('gender', 'Gender', 'trim|required');

        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        $this->form_validation->set_rules('user_own_company', 'Company Name', 'trim');

        // if ($params["user_type"] == 2) {
        //     $this->form_validation->set_rules('user_rto', 'Rto Number', 'trim|required');
        // } else {

            $this->form_validation->set_rules('user_rto', 'Rto Number', 'trim');
        // }


        $this->form_validation->set_rules(

            'phone', 'Phone',

            array(

                'required',

                array(

                    'phone_no_already_exits',

                    function ($str) {

                        $userID = base64_decode($this->input->post('user_id'));

                        return $this->commonmodel->verify_exits_dealer_phone_number($str, $userID);

                    }

                )

            )

        );

        if ($params["user_type"] != 6) {
            $this->form_validation->set_rules(

            'email', 'Email',

            array(

                'required',

                array(

                    'email_no_already_exits',

                    function ($str) {

                        $userID = base64_decode($this->input->post('user_id'));

                        return $this->commonmodel->verify_exits_dealer_email($str, $userID);

                    }

                )

            )

        );
        }
        // Validation verify

        if ($this->form_validation->run() == FALSE) {

            $returnResponse['validation'] = $this->form_validation->error_array();

            echo json_encode($returnResponse);
            exit();

        }

        // Rename Profile Photo

        if (isset($params['profile_photo']) && strlen($params['profile_photo']) > 0) {

            if (strpos($params['profile_photo'], 'temp_upload') !== false) {

                $profile_photo = str_replace('public/temp_upload/', 'public/upload/users/', $params['profile_photo']);

                rename($params['profile_photo'], $profile_photo);

                $params['profile_photo'] = $profile_photo;

            }

        }

        //Pass params to Model

        $response = $this->adminmodel->modify_dealer_records($params, $params['user_id']);

        if (empty($response)) {

            $returnResponse['error'] = "Please Enter valid Credentials.";

            echo json_encode($returnResponse);
            exit();

        }

        // Set Session

        $this->session->set_userdata($response);

        $returnResponse['success'] = true;

        $returnResponse['redirect'] = 'admin/users_list';

        $returnResponse['message'] = 'Users Details updated successfully.';

        echo json_encode($returnResponse);
        exit();

    }


    public function update_company_records()

    {

        $params = $this->input->post();

        $params['c_company_id'] = base64_decode($params['c_company_id']);

        //print_r($params);exit();

        $returnResponse = array();

        $returnResponse['validation'] = array();

        $returnResponse['error'] = "";

        $returnResponse['success'] = "";

        // Validation

        $this->form_validation->set_rules('c_company_id', 'Company ID', 'trim|required');

        $this->form_validation->set_rules('c_company_name', 'Company Name', 'trim|required');

        $this->form_validation->set_rules('c_cop_validity', 'Cop Validity', 'trim|required');

        //$this->form_validation->set_rules('c_tac_no[0]', 'Tac Number', 'trim|required');


        $this->form_validation->set_rules(

            'c_company_name', 'Company Name',

            array(

                'required',

                array(

                    'already_exits',

                    function ($str) {

                        $c_company_id = base64_decode($this->input->post('c_company_id'));

                        return $this->commonmodel->verify_exits_company_name($str, $c_company_id);

                    }

                )

            )

        );

        // $this->form_validation->set_rules(

        //     'c_tac_no[0]', 'Tac Number',

        //     array(

        //         'required',

        //         array(

        //             'already_exits',

        //             function ($str) {

        //                 $c_tac_no = $this->input->post('c_tac_no');

        //                 $c_company_id = base64_decode($this->input->post('c_company_id'));

        //                 $resultArray = $this->commonmodel->verify_exits_company_tac_number($c_tac_no, $c_company_id);

        //                 if (empty($resultArray)) {

        //                     return true;

        //                 }

        //                 return false;

        //             }

        //         )

        //     )

        // );


        // Validation verify

        if ($this->form_validation->run() == FALSE) {

            $returnResponse['validation'] = $this->form_validation->error_array();

            echo json_encode($returnResponse);
            exit();

        }


        //Pass params to Model

        $response = $this->adminmodel->modify_company_records($params);

        if (empty($response)) {

            $returnResponse['error'] = "Please Enter valid Credentials.";

            echo json_encode($returnResponse);
            exit();

        }

        // Set Session

        $this->session->set_userdata($response);

        $returnResponse['success'] = true;

        $returnResponse['redirect'] = 'admin/company_list';

        $returnResponse['message'] = 'Manufacturer Details updated successfully.';

        echo json_encode($returnResponse);
        exit();

    }

    public function update_product_records()

    {

        $params = $this->input->post();

        $params['p_product_id'] = base64_decode($params['p_product_id']);

        $params['s_created_by'] = $this->session->userdata('user_id');

        // $params['hidden_price'] = $this->session->userdata('user_id');

        //print_r($params);exit();

        $returnResponse = array();

        $returnResponse['validation'] = array();

        $returnResponse['error'] = "";

        $returnResponse['success'] = "";

        // Validation

        $this->form_validation->set_rules('p_product_id', 'Product ID', 'trim|required');

        $this->form_validation->set_rules('p_company_id', 'Company Name', 'trim|required');

        $this->form_validation->set_rules('p_product_name', 'Product Name', 'trim|required');

        $this->form_validation->set_rules('p_unit_price', 'Unit Price', 'trim|required');

        $this->form_validation->set_rules('p_product_description', 'Description', 'trim|required');

        $this->form_validation->set_rules('p_tac_no[0]', 'Tac Number', 'trim|required');

        $this->form_validation->set_rules(

            'p_product_name', 'Product Name',

            array(

                'required',

                array(

                    'already_exits',

                    function ($str) {

                        $p_product_id = base64_decode($this->input->post('p_product_id'));

                        return $this->commonmodel->verify_exits_product_name($str, $p_product_id);

                    }

                )

            )

        );

        $this->form_validation->set_rules(

                'p_tac_no[0]', 'Tac Number',
    
                array(
    
                    'required',
    
                    array(
    
                        'already_exits',
    
                        function ($str) {
    
                            $p_tac_no = $this->input->post('p_tac_no');
    
                            $p_product_id = base64_decode($this->input->post('p_product_id'));
    
                            $resultArray = $this->commonmodel->verify_exits_product_tac_number($p_tac_no, $p_product_id);
    
                            if (empty($resultArray)) {
    
                                return true;
    
                            }
    
                            return false;
    
                        }
    
                    )
    
                )
    
            );
    
    


        // Validation verify

        if ($this->form_validation->run() == FALSE) {

            $returnResponse['validation'] = $this->form_validation->error_array();

            echo json_encode($returnResponse);
            exit();

        }


        //Pass params to Model

        $response = $this->adminmodel->modify_product_records($params);

        if (empty($response)) {

            $returnResponse['error'] = "Please Enter valid Credentials.";

            echo json_encode($returnResponse);
            exit();

        }

        // Set Session

        $this->session->set_userdata($response);

        $returnResponse['success'] = true;

        $returnResponse['redirect'] = 'admin/product_list';

        $returnResponse['message'] = 'Product Details updated successfully.';

        echo json_encode($returnResponse);
        exit();

    }


    public function update_vehicle_make_records()

    {

        $params = $this->input->post();

        $params['v_make_id'] = base64_decode($params['v_make_id']);

        //print_r($params);exit();

        $returnResponse = array();

        $returnResponse['validation'] = array();

        $returnResponse['error'] = "";

        $returnResponse['success'] = "";

        // Validation

        $this->form_validation->set_rules('v_make_id', 'Make ID', 'trim|required');

        $this->form_validation->set_rules('v_vehicle_make', 'Make Name', 'trim|required');


        $this->form_validation->set_rules(

            'v_vehicle_make', 'Make Name',

            array(

                'required',

                array(

                    'already_exits',

                    function ($str) {

                        $v_make_id = base64_decode($this->input->post('v_make_id'));

                        return $this->commonmodel->verify_exits_make_name($str, $v_make_id);

                    }

                )

            )

        );


        // Validation verify

        if ($this->form_validation->run() == FALSE) {

            $returnResponse['validation'] = $this->form_validation->error_array();

            echo json_encode($returnResponse);
            exit();

        }

        //print_r($params);exit();

        //Pass params to Model

        $response = $this->adminmodel->modify_make_records($params);

        if (empty($response)) {

            $returnResponse['error'] = "Please Enter valid Credentials.";

            echo json_encode($returnResponse);
            exit();

        }

        // Set Session

        $this->session->set_userdata($response);

        $returnResponse['success'] = true;

        $returnResponse['redirect'] = 'admin/vehicle_make_list';

        $returnResponse['message'] = 'Vehicle Make Details updated successfully.';

        echo json_encode($returnResponse);
        exit();

    }


    public function update_profile_records()

    {

        $user_type = $this->session->userdata('user_type');


        $params = $this->input->post();

        $params['user_id'] = base64_decode($params['user_id']);

        //print_r($params);exit();

        $returnResponse = array();

        $returnResponse['validation'] = array();

        $returnResponse['error'] = "";

        $returnResponse['success'] = "";

        if ((string)$user_type != '0') {

            $returnResponse['error'] = "Admin have only permission to change profile records.";

            echo json_encode($returnResponse);
            exit();

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

                    function ($str) {

                        $userID = base64_decode($this->input->post('user_id'));

                        return $this->commonmodel->verify_exits_dealer_phone_number($str, $userID);

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

                    function ($str) {

                        $userID = base64_decode($this->input->post('user_id'));

                        return $this->commonmodel->verify_exits_dealer_email($str, $userID);

                    }

                )

            )

        );

        // Validation verify

        if ($this->form_validation->run() == FALSE) {

            $returnResponse['validation'] = $this->form_validation->error_array();

            echo json_encode($returnResponse);
            exit();

        }

        // Rename Profile Photo

        if (isset($params['profile_photo']) && strlen($params['profile_photo']) > 0) {

            if (strpos($params['profile_photo'], 'temp_upload') !== false) {

                $profile_photo = str_replace('public/temp_upload/', 'public/upload/users/', $params['profile_photo']);

                rename($params['profile_photo'], $profile_photo);

                $params['profile_photo'] = $profile_photo;

            }

        }

        //Pass params to Model

        $response = $this->adminmodel->update_profile_records($params, $params['user_id']);

        if (empty($response)) {

            $returnResponse['error'] = "Please Enter valid Credentials.";

            echo json_encode($returnResponse);
            exit();

        }

        // Set Session

        $this->session->set_userdata($response);

        $returnResponse['success'] = true;

        $returnResponse['redirect'] = 'admin/logout';

        echo json_encode($returnResponse);
        exit();

    }


    public function create_new_entry()
    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'menu_cerificate_create')) {
            redirect(base_url(), 'refresh');
            exit();
        }

        $user_id = $this->session->userdata('user_id');

        $data['rto_list'] = $this->commonmodel->allRtoNumbers();
        
        $data['stateList'] = $this->commonmodel->allStateList();
        
        $data['make_list'] = $this->commonmodel->allMakeList();
        // echo "<pre>";print_r($data);exit();
        // $data['serialList'] = $this->commonmodel->allSerialList($user_id);
        
        $data['company_list'] = $this->commonmodel->allCompanyList($user_id);
        
        if($user_type == 1 || $user_type == 0){
            $data['technician_list'] = $this->commonmodel->getTechnicianListForDealer($user_id);
        }
        
        // Load Content

        $_SESSION['currentActivePage'] = 'Create_Cerificate';
        
        $this->load->view('masters/create_new_vehicle', $data);

    }


    public function fetch_model_list_by_make()

    {

        $params = $this->input->post();

        $data['model_list'] = $this->commonmodel->allModelList($params['veh_make_no']);

        echo json_encode($data);
        exit();

    }


    public function fetch_customer_by_phone()

    {

        $params = $this->input->post();
        
        $data['customer'] = $this->commonmodel->getCustomerInfo($params['phone']);

        echo json_encode($data);
        exit();

    }


    //Delete

    public function changeUserStatus()

    {

        $params = $this->input->post();

        $user_type = $this->session->userdata('user_type');


        $returnResponse = array();

        $returnResponse['validation'] = array();

        $returnResponse['error'] = "";

        $returnResponse['success'] = "";

        if ((string)$user_type != '0') {

            $returnResponse['error'] = "Admin Can have permission only.";

            echo json_encode($returnResponse);
            exit();

        }

        $result = $this->adminmodel->changeUserStatus($params);

        $returnResponse['success'] = "true";

        echo json_encode($returnResponse);
        exit();

    }

    //Delete

    public function delete_entry_list()

    {

        $params = $this->input->post();

        $user_type = $this->session->userdata('user_type');


        $returnResponse = array();

        $returnResponse['validation'] = array();

        $returnResponse['error'] = "";

        $returnResponse['success'] = "";

        if ((string)$user_type != '0') {

            $returnResponse['error'] = "Admin Can have permission only.";

            echo json_encode($returnResponse);
            exit();

        }

        $result = $this->adminmodel->delete_entry_list($params);

        $returnResponse['success'] = "true";

        echo json_encode($returnResponse);
        exit();

    }

//---  Add on Starts -----
    public function fetch_company_info()

    {

        $params = $this->input->post();

        $user_id = $this->session->userdata('user_id');

        $data['Serial_List'] = $this->commonmodel->selectCompanyInfo($params['veh_company_id'], $user_id);

        echo json_encode($data);
        exit();

    }

// ------Add on Ends ----
    public function fetch_serial_list_by_company()

    {

        $params = $this->input->post();

        $user_id = $this->session->userdata('user_id');

        $data['Serial_List'] = $this->commonmodel->allSerialNumberByCompany($params['veh_company_id'], $user_id);

        echo json_encode($data);
        exit();

    }
    
    public function fetch_serial_list_by_company_and_state()

    {

        $params = $this->input->post();

        $user_id = $this->session->userdata('user_id');

        $data['Serial_List'] = $this->commonmodel->allSerialNumberByCompanyAndState($params['veh_company_id'], $params['state_id'], $user_id);

        echo json_encode($data);
        exit();

    }

    // Agents

    public function create_new_vehicle_records()
    {

        $params = $this->input->post();

        $length = strlen($params['veh_owner_phone']);

        $returnResponse = array();

        $returnResponse['validation'] = array();

        $returnResponse['error'] = "";

        $returnResponse['success'] = "";

        $returnResponse['mobileError'] = "";
        // Validation

        if($length < 10){
            $returnResponse['mobileError'] = True;
            echo json_encode($returnResponse);
            exit();
        } 
        
        $this->form_validation->set_rules('veh_create_date', 'Vehicle Created Date', 'trim|required');

        $this->form_validation->set_rules('validity_to', 'Vehicle Validity Date', 'trim|required');

        // $this->form_validation->set_rules('veh_rc_no', 'Vehicle Rc Number', 'trim|required');
        if($params['scales']!="on"){
            $this->form_validation->set_rules('veh_rc_no', 'Vehicle Rc Number', 'trim|required');
            // $this->form_validation->set_rules('rc_book_photo', 'Rc Photo', 'trim|required');
        }
        
        if($params['fitment']=="N"){   // gender(fitment)
            // $this->form_validation->set_rules('veh_rc_no', 'Vehicle Rc Number', 'trim|required');
            $obj['fitment'] = "Please complete your fitment entry and try it.";
            $returnResponse['validation'] = $obj;
            echo json_encode($returnResponse);
            exit();
        }

        // $this->form_validation->set_rules('veh_chassis_no', 'Vehicle Chassis Number', 'trim|required');

        // $this->form_validation->set_rules('veh_company_id', 'Vehicle Company Name', 'trim|required');

        // $this->form_validation->set_rules('veh_engine_no', 'Vehicle Engine Number', 'trim|required');

        // $this->form_validation->set_rules('veh_make_no', 'Vehicle Make Number', 'trim|required');

        // $this->form_validation->set_rules('veh_model_no', 'Vehicle Model Number', 'trim|required');

        // $this->form_validation->set_rules('veh_owner_name', 'Vehicle Owner Name', 'trim|required');

        // $this->form_validation->set_rules('veh_address', 'Vehicle Address', 'trim|required');

        $this->form_validation->set_rules('veh_owner_phone', 'Vehicle Owner Number', 'trim|required');

        $this->form_validation->set_rules('veh_serial_no', 'Vehicle Serial Number', 'trim|required');

        $this->form_validation->set_rules('veh_rto_no', 'Vehicle RTO Number', 'trim|required');
        
        // $this->form_validation->set_rules('state', 'State', 'trim|required');

        // $this->form_validation->set_rules('veh_speed', 'Vehicle Speed', 'trim|required');

        // $this->form_validation->set_rules('veh_tac', 'Vehicle Tac Number', 'trim|required');

        // $this->form_validation->set_rules('veh_cat', 'Vehicle Category', 'trim|required');

        // $this->form_validation->set_rules('veh_invoice_no', 'Vehicle Invoice Number', 'trim|required');

        // $this->form_validation->set_rules('veh_speed_governer_photo', 'Device Photo', 'trim|required');

        // $this->form_validation->set_rules('selling_price', 'Selling Price', 'trim|required');

        // $this->form_validation->set_rules('veh_photo', 'Vehicle Photo', 'trim|required');
        
        if (isset($params['veh_owner_email']) && strlen($params['veh_owner_email']) > 0) {
            $this->form_validation->set_rules('veh_owner_email', 'Email', 'required|valid_email');
        }
        
         if($params['veh_rc_no'] != "NEW REGISTRATION"){
            $this->form_validation->set_rules(
                'veh_rc_no', 'Vehicle Rc Number',
                array(
                    'required',
                    array(
                        'already_exits',
                        function ($str) {
                            return $this->commonmodel->verify_exits_vehicle_records($str, 'veh_rc_no');
                        }
                    )
                )
            );
        }

        
        if($params['veh_engine_no']!=""){
            $this->form_validation->set_rules(
                'veh_engine_no', 'Vehicle Engine Number',
                array(
                    'required',
                    array(
                        'already_exits',
                        function ($str) {
                            return $this->commonmodel->verify_exits_vehicle_records($str, 'veh_engine_no');
                        }    
                    )
                )
            );
        }

        if($params['veh_chassis_no']!=""){
            $this->form_validation->set_rules(
                'veh_chassis_no', 'Vehicle Chassis Number',
                array(
                    'required',
                    array(
                        'already_exits',
                        function ($str) {
                            return $this->commonmodel->verify_exits_vehicle_records($str, 'veh_chassis_no');
                        }
                    )
                )
            );
        }
        // $this->form_validation->set_rules(

        //     'veh_rc_no', 'Vehicle Rc Number',

        //     array(

        //         'required',

        //         array(

        //             'already_exits',

        //             function ($str) {


        //                 return $this->commonmodel->verify_exits_vehicle_records($str, 'veh_rc_no');

        //             }

        //         )

        //     )

        // );


        // $this->form_validation->set_rules(

        //     'veh_chassis_no', 'Vehicle Chassis Number',

        //     array(

        //         'required',

        //         array(

        //             'already_exits',

        //             function ($str) {

        //                 return $this->commonmodel->verify_exits_vehicle_records($str, 'veh_chassis_no');

        //             }

        //         )

        //     )

        // );


        // $this->form_validation->set_rules(

        //     'veh_engine_no', 'Vehicle Engine Number',

        //     array(

        //         'required',

        //         array(

        //             'already_exits',

        //             function ($str) {

        //                 return $this->commonmodel->verify_exits_vehicle_records($str, 'veh_engine_no');

        //             }

        //         )

        //     )

        // );

        // if (isset($params['veh_rc_no']) && strlen($params['veh_rc_no']) > 0) {

        //     $params['veh_rc_no'] = preg_replace('/\s+/', '', $params['veh_rc_no']);

        // }

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

        $fetchCompanyInfo = $this->commonmodel->fetch( 'c_company_id', $params['veh_company_id'], 'c_company_name,c_cop_validity', $this->db->table_company);

        $params['veh_cop_validity'] = isset($fetchCompanyInfo['c_cop_validity']) ? $fetchCompanyInfo['c_cop_validity'] : date('Y-m-d H:i:s');

        $params['veh_sld_make'] = isset($fetchCompanyInfo['c_company_name']) ? $fetchCompanyInfo['c_company_name'] : "";

        $params['validity_from'] = date('Y-m-d H:i:s');

        // $params['validity_to'] = date('Y-m-d H:i:s', strtotime("+" . EXPIRE_DATE_VALUE . " days"));

        $params['veh_create_date'] = date('Y-m-d');

        //print_r($params);exit();

        // Validation verify

        if ($this->form_validation->run() == FALSE) {

            $returnResponse['validation'] = $this->form_validation->error_array();

            echo json_encode($returnResponse);
            exit();

        }

        // Rename // Rename Profile Photo

        if (isset($params['vehicle_owner_id_proof_photo']) && strlen($params['vehicle_owner_id_proof_photo']) > 0) {

            if (strpos($params['vehicle_owner_id_proof_photo'], 'temp_upload') !== false) {

                $profile_photo = str_replace('public/temp_upload/', 'public/upload/vehicle_owner_id_proof/', $params['vehicle_owner_id_proof_photo']);

                rename($params['vehicle_owner_id_proof_photo'], $profile_photo);

                $params['vehicle_owner_id_proof_photo'] = $profile_photo;

            }

        }
        // Rename // Rename Profile Photo

        if (isset($params['vehicle_owners_photo']) && strlen($params['vehicle_owners_photo']) > 0) {

            if (strpos($params['vehicle_owners_photo'], 'temp_upload') !== false) {

                $profile_photo = str_replace('public/temp_upload/', 'public/upload/vehicle_owners_photos/', $params['vehicle_owners_photo']);

                rename($params['vehicle_owners_photo'], $profile_photo);

                $params['vehicle_owners_photo'] = $profile_photo;

            }

        }
        // Rename // Rename Profile Photo

        if (isset($params['rc_book_photo']) && strlen($params['rc_book_photo']) > 0) {

            if (strpos($params['rc_book_photo'], 'temp_upload') !== false) {

                $profile_photo = str_replace('public/temp_upload/', 'public/upload/rc_book_photos/', $params['rc_book_photo']);

                rename($params['rc_book_photo'], $profile_photo);

                $params['rc_book_photo'] = $profile_photo;

            }

        }
// Rename // Rename Profile 
            
        
        if (isset($params['veh_speed_governer_photo']) && strlen($params['veh_speed_governer_photo']) > 0) {
            /* if (strpos($params['veh_speed_governer_photo'], 'temp_upload') !== false) {

                $profile_photo = str_replace('public/temp_upload/', 'public/upload/vehicle/', $params['veh_speed_governer_photo']);

                rename($params['veh_speed_governer_photo'], $profile_photo);

                $params['veh_speed_governer_photo'] = $profile_photo;

            } */
            
            if (strpos($params['veh_speed_governer_photo'], 'temp_upload') !== false) {
                $imagePath = $params['veh_speed_governer_photo'];
                $imageData = explode('/', $imagePath);
                $imageName = $imageData[2];
                $path = "public/upload/vehicle";
                $deviceImage = $this->awsImageUpload($imagePath, $imageName, $path);
                // echo "<pre>";print_r($deviceImage);
                $dats = explode('/', $deviceImage);
                $params['veh_speed_governer_photo'] = $path.'/'.$dats[6];
                if (isset($params['veh_speed_governer_photo']) && strlen($params['veh_speed_governer_photo']) > 0) {
                    unlink($imagePath);
                }
            }
        }
        
        // Rename // Rename Profile Photo

        if (isset($params['veh_photo']) && strlen($params['veh_photo']) > 0) {
            if (strpos($params['veh_photo'], 'temp_upload') !== false) {
                $vehImagePath = $params['veh_photo'];
                $vehImageData = explode('/', $vehImagePath);
                $imageName1 = $vehImageData[2];
                $path = "public/upload/vehicle";
                $vehicleImage = $this->awsImageUpload($vehImagePath, $imageName1, $path);
                // echo "<pre>";print_r($vehicleImage);
                $dats = explode('/', $vehicleImage);
                $params['veh_photo']  = $path.'/'.$dats[6];
                // echo "<pre>";print_r($params['veh_photo']);exit;
                if (isset($params['veh_photo']) && strlen($params['veh_photo']) > 0) {
                    unlink($vehImagePath);
                }
                // $profile_photo = str_replace('public/temp_upload/', 'public/upload/vehicle/', $params['veh_photo']);
                // rename($params['veh_photo'], $profile_photo);
                // $params['veh_photo'] = $profile_photo;
            }

        }
        
        if (isset($params['veh_speed_governer_photo']) && strlen($params['veh_speed_governer_photo']) == 0) {
             $params['veh_speed_governer_photo'] = "public/images/psdn_logo.jpg";
         }
 

        if (isset($params['veh_photo']) && strlen($params['veh_photo']) == 0) {
            $params['veh_photo'] = "public/images/psdn_logo.jpg";
        }

        // echo "<pre>";print_r($params);exit;
        // echo "<pre>";print_r($params);exit;
        $params['veh_created_user_id'] = $this->session->userdata('user_id');
        
        if($params['scales']=="on"){
            $params['scales']= 1;
        }else{
            $params['scales']= 0;
        }
        
        $serialInfo = $this->commonmodel->getSerialNumberInfo($params['veh_serial_no']);
        $params['product_id'] = $serialInfo['s_product_id'];
        $params['s_imei'] = $serialInfo['s_imei'];
        $params['s_distributor_id'] = $serialInfo['s_distributor_id'];
        $params['s_dealer_id'] = $serialInfo['s_dealer_id'];
        
        $info = $this->adminmodel->create_new_vehicle_records($params);
        
        $response = $info['insert_id'];
        
        if (empty($response)) {

            $returnResponse['error'] = "Please Enter valid Details.";

            echo json_encode($returnResponse);
            exit();

        }
        $veh_owner_id = $info['veh_owner_id'];
        $test = $this->adminmodel->add_tracking_entry($response, $veh_owner_id, $params['veh_created_user_id'], $info['insert_id'], $params['s_dealer_id'], $params['s_distributor_id']);

        $returnResponse['success'] = true;

        $user_type = $this->session->userdata('user_type');

        if ((string)$user_type === '0' || (string)$user_type === '2' || (string)$user_type === '4') {

            $returnResponse['redirect'] = 'admin/entry_list';

        } elseif ((string)$user_type === '1') {

            $returnResponse['redirect'] = 'dealer/entry_list';

        }

        $encodeID = base64_encode(base64_encode(base64_encode($response)));
        $tinyurl = $this->get_tiny_url(base_url() . 'admin/downloadwebpdf?id=' . $encodeID);
        $SMS = 'Cerificate Created successfully,ref url :' . $tinyurl;
        log_message('error', $SMS);
        $this->commonmodel->send_sms_wp($params['veh_owner_phone'], $tinyurl);
        echo json_encode($returnResponse);
        exit();
    }

//     public function create_new_vehicle_records()

//     {

//         $params = $this->input->post();
//         $length = strlen($params['veh_owner_phone']);
//     //   echo "<pre>";print_r($params);exit;


//         $returnResponse = array();

//         $returnResponse['validation'] = array();

//         $returnResponse['error'] = "";

//         $returnResponse['success'] = "";

//         $returnResponse['mobileError'] = "";
//         // Validation

//         if($length < 10){
//             $returnResponse['mobileError'] = True;
//             echo json_encode($returnResponse);
//             exit();
//         } 
        
//         $this->form_validation->set_rules('veh_create_date', 'Vehicle Created Date', 'trim|required');

//         $this->form_validation->set_rules('validity_to', 'Vehicle Validity Date', 'trim|required');

//         // $this->form_validation->set_rules('veh_rc_no', 'Vehicle Rc Number', 'trim|required');
//         if($params['scales']!="on"){
//             $this->form_validation->set_rules('veh_rc_no', 'Vehicle Rc Number', 'trim|required');
//             // $this->form_validation->set_rules('rc_book_photo', 'Rc Photo', 'trim|required');
//         }
        
//         if($params['fitment']=="N"){   // gender(fitment)
//             // $this->form_validation->set_rules('veh_rc_no', 'Vehicle Rc Number', 'trim|required');
//             $obj['fitment'] = "Please complete your fitment entry and try it.";
//             $returnResponse['validation'] = $obj;
//             echo json_encode($returnResponse);
//             exit();
//         }

//         // $this->form_validation->set_rules('veh_chassis_no', 'Vehicle Chassis Number', 'trim|required');

//         // $this->form_validation->set_rules('veh_company_id', 'Vehicle Company Name', 'trim|required');

//         // $this->form_validation->set_rules('veh_engine_no', 'Vehicle Engine Number', 'trim|required');

//         // $this->form_validation->set_rules('veh_make_no', 'Vehicle Make Number', 'trim|required');

//         // $this->form_validation->set_rules('veh_model_no', 'Vehicle Model Number', 'trim|required');

//         // $this->form_validation->set_rules('veh_owner_name', 'Vehicle Owner Name', 'trim|required');

//         // $this->form_validation->set_rules('veh_address', 'Vehicle Address', 'trim|required');

//         $this->form_validation->set_rules('veh_owner_phone', 'Vehicle Owner Number', 'trim|required');

//         $this->form_validation->set_rules('veh_serial_no', 'Vehicle Serial Number', 'trim|required');

//         $this->form_validation->set_rules('veh_rto_no', 'Vehicle RTO Number', 'trim|required');
        
//         // $this->form_validation->set_rules('state', 'State', 'trim|required');

//         // $this->form_validation->set_rules('veh_speed', 'Vehicle Speed', 'trim|required');

//         // $this->form_validation->set_rules('veh_tac', 'Vehicle Tac Number', 'trim|required');

//         // $this->form_validation->set_rules('veh_cat', 'Vehicle Category', 'trim|required');

//         // $this->form_validation->set_rules('veh_invoice_no', 'Vehicle Invoice Number', 'trim|required');

//         // $this->form_validation->set_rules('veh_speed_governer_photo', 'Device Photo', 'trim|required');

//         // $this->form_validation->set_rules('selling_price', 'Selling Price', 'trim|required');

//         // $this->form_validation->set_rules('veh_photo', 'Vehicle Photo', 'trim|required');
        
//         if (isset($params['veh_owner_email']) && strlen($params['veh_owner_email']) > 0) {
//             $this->form_validation->set_rules('veh_owner_email', 'Email', 'required|valid_email');
//         }

//          if($params['veh_rc_no'] != "NEW REGISTRATION"){
//             $this->form_validation->set_rules(
//                 'veh_rc_no', 'Vehicle Rc Number',
//                 array(
//                     'required',
//                     array(
//                         'already_exits',
//                         function ($str) {
//                             return $this->commonmodel->verify_exits_vehicle_records($str, 'veh_rc_no');
//                         }
//                     )
//                 )
//             );
//         }

        
//         if($params['veh_engine_no']!=""){
//             $this->form_validation->set_rules(
//                 'veh_engine_no', 'Vehicle Engine Number',
//                 array(
//                     'required',
//                     array(
//                         'already_exits',
//                         function ($str) {
//                             return $this->commonmodel->verify_exits_vehicle_records($str, 'veh_engine_no');
//                         }    
//                     )
//                 )
//             );
//         }

//         if($params['veh_chassis_no']!=""){
//             $this->form_validation->set_rules(
//                 'veh_chassis_no', 'Vehicle Chassis Number',
//                 array(
//                     'required',
//                     array(
//                         'already_exits',
//                         function ($str) {
//                             return $this->commonmodel->verify_exits_vehicle_records($str, 'veh_chassis_no');
//                         }
//                     )
//                 )
//             );
//         }
//         // $this->form_validation->set_rules(

//         //     'veh_rc_no', 'Vehicle Rc Number',

//         //     array(

//         //         'required',

//         //         array(

//         //             'already_exits',

//         //             function ($str) {


//         //                 return $this->commonmodel->verify_exits_vehicle_records($str, 'veh_rc_no');

//         //             }

//         //         )

//         //     )

//         // );


//         // $this->form_validation->set_rules(

//         //     'veh_chassis_no', 'Vehicle Chassis Number',

//         //     array(

//         //         'required',

//         //         array(

//         //             'already_exits',

//         //             function ($str) {

//         //                 return $this->commonmodel->verify_exits_vehicle_records($str, 'veh_chassis_no');

//         //             }

//         //         )

//         //     )

//         // );


//         // $this->form_validation->set_rules(

//         //     'veh_engine_no', 'Vehicle Engine Number',

//         //     array(

//         //         'required',

//         //         array(

//         //             'already_exits',

//         //             function ($str) {

//         //                 return $this->commonmodel->verify_exits_vehicle_records($str, 'veh_engine_no');

//         //             }

//         //         )

//         //     )

//         // );

//         // if (isset($params['veh_rc_no']) && strlen($params['veh_rc_no']) > 0) {

//         //     $params['veh_rc_no'] = preg_replace('/\s+/', '', $params['veh_rc_no']);

//         // }

//         // $this->form_validation->set_rules(

//         // 	'veh_invoice_no', 'Vehicle Invoice Number',

//         // 		array(

//         // 				'required',

//         // 				array(

//         // 						'already_exits',

//         // 						function($str)

//         // 						{

//         // 							return $this->commonmodel->verify_exits_vehicle_records($str,'veh_invoice_no');

//         // 						}

//         // 				)

//         // 		)

//         // );

//         $fetchCompanyInfo = $this->commonmodel->fetch(

//             'c_company_id',

//             $params['veh_company_id'],

//             'c_company_name,c_cop_validity',

//             $this->db->table_company);

//         $params['veh_cop_validity'] = isset($fetchCompanyInfo['c_cop_validity']) ? $fetchCompanyInfo['c_cop_validity'] : date('Y-m-d H:i:s');

//         $params['veh_sld_make'] = isset($fetchCompanyInfo['c_company_name']) ? $fetchCompanyInfo['c_company_name'] : "";

//         $params['validity_from'] = date('Y-m-d H:i:s');

//         // $params['validity_to'] = date('Y-m-d H:i:s', strtotime("+" . EXPIRE_DATE_VALUE . " days"));

//         $params['veh_create_date'] = date('Y-m-d');

//         //print_r($params);exit();

//         // Validation verify

//         if ($this->form_validation->run() == FALSE) {

//             $returnResponse['validation'] = $this->form_validation->error_array();

//             echo json_encode($returnResponse);
//             exit();

//         }

//         // Rename // Rename Profile Photo

//         if (isset($params['vehicle_owner_id_proof_photo']) && strlen($params['vehicle_owner_id_proof_photo']) > 0) {

//             if (strpos($params['vehicle_owner_id_proof_photo'], 'temp_upload') !== false) {

//                 $profile_photo = str_replace('public/temp_upload/', 'public/upload/vehicle_owner_id_proof/', $params['vehicle_owner_id_proof_photo']);

//                 rename($params['vehicle_owner_id_proof_photo'], $profile_photo);

//                 $params['vehicle_owner_id_proof_photo'] = $profile_photo;

//             }

//         }
//         // Rename // Rename Profile Photo

//         if (isset($params['vehicle_owners_photo']) && strlen($params['vehicle_owners_photo']) > 0) {

//             if (strpos($params['vehicle_owners_photo'], 'temp_upload') !== false) {

//                 $profile_photo = str_replace('public/temp_upload/', 'public/upload/vehicle_owners_photos/', $params['vehicle_owners_photo']);

//                 rename($params['vehicle_owners_photo'], $profile_photo);

//                 $params['vehicle_owners_photo'] = $profile_photo;

//             }

//         }
// // Rename // Rename Profile Photo

//         if (isset($params['rc_book_photo']) && strlen($params['rc_book_photo']) > 0) {

//             if (strpos($params['rc_book_photo'], 'temp_upload') !== false) {

//                 $profile_photo = str_replace('public/temp_upload/', 'public/upload/rc_book_photos/', $params['rc_book_photo']);

//                 rename($params['rc_book_photo'], $profile_photo);

//                 $params['rc_book_photo'] = $profile_photo;

//             }

//         }
// // Rename // Rename Profile Photo

//         if (isset($params['veh_speed_governer_photo']) && strlen($params['veh_speed_governer_photo']) > 0) {

//             if (strpos($params['veh_speed_governer_photo'], 'temp_upload') !== false) {

//                 $profile_photo = str_replace('public/temp_upload/', 'public/upload/vehicle/', $params['veh_speed_governer_photo']);

//                 rename($params['veh_speed_governer_photo'], $profile_photo);

//                 $params['veh_speed_governer_photo'] = $profile_photo;

//             }

//         }

//         // Rename // Rename Profile Photo

//         if (isset($params['veh_photo']) && strlen($params['veh_photo']) > 0) {

//             if (strpos($params['veh_photo'], 'temp_upload') !== false) {

//                 $profile_photo = str_replace('public/temp_upload/', 'public/upload/vehicle/', $params['veh_photo']);

//                 rename($params['veh_photo'], $profile_photo);

//                 $params['veh_photo'] = $profile_photo;

//             }

//         }
        
//         if (isset($params['veh_speed_governer_photo']) && strlen($params['veh_speed_governer_photo']) == 0) {
//              $params['veh_speed_governer_photo'] = "public/images/psdn_logo.jpg";
//          }
 

//         if (isset($params['veh_photo']) && strlen($params['veh_photo']) == 0) {
//             $params['veh_photo'] = "public/images/psdn_logo.jpg";
//         }



//         $params['veh_created_user_id'] = $this->session->userdata('user_id');
        
//         if($params['scales']=="on"){
//             $params['scales']= 1;
//         }else{
//             $params['scales']= 0;
//         }

//         $serialInfo = $this->commonmodel->getSerialNumberInfo($params['veh_serial_no']);
//         $params['product_id'] = $serialInfo['s_product_id'];
//         $params['s_imei'] = $serialInfo['s_imei'];
//         $params['s_distributor_id'] = $serialInfo['s_distributor_id'];
//         $params['s_dealer_id'] = $serialInfo['s_dealer_id'];
        
//         $info = $this->adminmodel->create_new_vehicle_records($params);
//         $response = $info['insert_id'];

//         if (empty($response)) {

//             $returnResponse['error'] = "Please Enter valid Details.";

//             echo json_encode($returnResponse);
//             exit();

//         }
//         $veh_owner_id = $info['veh_owner_id'];
//         $this->adminmodel->add_tracking_entry($response, $veh_owner_id, $params['veh_created_user_id'], $info['insert_id'], $params['s_dealer_id'], $params['s_distributor_id']);


//         $returnResponse['success'] = true;

//         $user_type = $this->session->userdata('user_type');

//         if ((string)$user_type === '0' || (string)$user_type === '2') {

//             $returnResponse['redirect'] = 'admin/entry_list';

//         } elseif ((string)$user_type === '1') {

//             $returnResponse['redirect'] = 'dealer/entry_list';

//         }

//         $encodeID = base64_encode(base64_encode(base64_encode($response)));
//         $tinyurl = $this->get_tiny_url(base_url() . 'admin/downloadwebpdf?id=' . $encodeID);
//         $SMS = 'Cerificate Created successfully,ref url :' . $tinyurl;
//         log_message('error', $SMS);
//         $this->commonmodel->send_sms($params['veh_owner_phone'], $SMS);
//         echo json_encode($returnResponse);
//         exit();

//     }


    public function awsImageUpload($imagePath, $imageName, $path){
        try{
            // echo "<pre>";print_r("imagePath =>".$imagePath." imageName =>".$imageName." path =>".$path);exit;
            // "imagePath =>public/temp_upload/1697088606.png imageName =>1697088606.png path =>public/upload/vehicle"
            // s3 Bucket connect
            $credentials = [
                // 'key'    => 'AKIASHD435HUA2JU4WHE',
                'key'    => 'AKIASHD435HUONSXGNLH',
                'secret' => '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',
            ];
            $s3Client = new S3Client([
                'version'     => 'latest',
                'region'      => 'ap-south-1',
                'credentials' => $credentials
            ]);

            /* $bucket          = "psdn-v1"; */
            $bucket          = "techpsdn";
            $folderName      = $path;
            $folderExists    = false;
            list($txt, $ext) = explode(".", $imageName);
            $contentType     = strtolower($ext);

            //check folder
            try {
                $findFolder = $s3Client->listObjects([
                    'Bucket' => $bucket,
                    'Prefix' => $folderName . '/',
                    'MaxKeys'=> 1,
                ]);
                if ($findFolder['Contents']) {
                    $folderExists = true;
                }
                // echo "Folder exists!";
            } catch (S3Exception $e) {
                if ($e->getStatusCode() !== 404) {
                    echo "An error occurred: " . $e->getMessage();
                    exit;
                }
            }

            // Create the folder if it doesn't exist
            if (!$folderExists) {
                $result = $s3Client->putObject([
                    'Bucket'      => $bucket,
                    'Key'         => $folderName."/".$imageName,
                    'SourceFile'  => $imagePath,	               // public/temp_upload/1686202313.jpg
                    'ContentType' => $contentType,		           // jpg or png (File Type)
                    'StorageClass' => 'REDUCED_REDUNDANCY'       
                ]);
                    // echo "<pre>";print_r($result['ObjectURL']);exit;

                $url = $result['ObjectURL'];
                return $url;
            }

            // Upload the image to the folder  
            try {
                // echo "<pre>";print_r($folderName."/".$imageName);
                // echo "<pre>";print_r("impa".$imagePath);
                // echo "<pre>";print_r("ct".$contentType);exit;
                $result1 = $s3Client->putObject([
                    'Bucket'      => $bucket,
                    'Key'         => $folderName."/".$imageName,   // sample/45614565.jpg
                    'SourceFile'  => $imagePath,	               // public/temp_upload/1686202313.jpg
                    'ContentType' => $contentType,		           // jpg or png (File Type)
                    'StorageClass' => 'REDUCED_REDUNDANCY'
                ]);

                $urlOutput = $result1['ObjectURL'];
                return $urlOutput;

            }catch (S3Exception $e) {
                die('Error:' . $e->getMessage());
            } catch (Exception $e) {
                die('Error:' . $e->getMessage());
            } 
        }catch (S3Exception $e) {
            echo "An error occurred: " . $e->getMessage();
            exit;
        }
    }

    public function get_tiny_url($url)
    {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, 'http://tinyurl.com/api-create.php?url=' . $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }


// Add on - addition Starts &&&&&&&&&&&

    public function create_renewal_vehicle_records()

    {


        $params = $this->input->post();

        //print_r($params);exit();

        $returnResponse = array();

        $returnResponse['validation'] = array();

        $returnResponse['error'] = "";

        $returnResponse['success'] = "";

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

        $this->form_validation->set_rules('veh_speed_governer_photo', 'Device Photo', 'trim|required');

        $this->form_validation->set_rules('veh_photo', 'Vehicle Photo', 'trim|required');


        //------ Check Already Exists ------

        $response_check['userinfo'] = $this->commonmodel->Check_Certificate_Validity($userID = $this->session->userdata('user_id'));


        if (empty($response_check)) {

            $returnResponse['error'] = "You donot have the certificate create limit, Please Contact Administrator.";

            echo json_encode($returnResponse);
            exit();

        } else {

            $available = $response_check['userinfo']['allotted'] - $response_check['userinfo']['used'];

            if ($available <= 0) {

                $returnResponse['error'] = "Certificate Create Limit Reached, Please Contact Administrator..";
                echo json_encode($returnResponse);
                exit();
            }

        }


        // Blocked : return $this->commonmodel->verify_exits_vehicle_records($str,'veh_rc_no');


        //------ Check Already Exists -----

        // ----- Check Serial Number Exists - Starts -----

        $My_response_check = $this->commonmodel->verify_renewal_serial_number_exists($params["veh_serial_no"], $params["veh_rc_no"]);


        if (empty($My_response_check)) {

            // $returnResponse['error']="Serial Number is Not Exists";
            //echo json_encode($returnResponse);
            //exit();


        } else {

            $returnResponse['error'] = "Serial Number is already Exists";
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

        if (isset($params['veh_rc_no']) && strlen($params['veh_rc_no']) > 0) {

            $params['veh_rc_no'] = preg_replace('/\s+/', '', $params['veh_rc_no']);

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

        $fetchCompanyInfo = $this->commonmodel->fetch(

            'c_company_id',

            $params['veh_company_id'],

            'c_company_name,c_cop_validity',

            $this->db->table_company);

        $params['veh_cop_validity'] = isset($fetchCompanyInfo['c_cop_validity']) ? $fetchCompanyInfo['c_cop_validity'] : date('Y-m-d H:i:s');

        $params['veh_sld_make'] = isset($fetchCompanyInfo['c_company_name']) ? $fetchCompanyInfo['c_company_name'] : "";

        $params['validity_from'] = date('Y-m-d H:i:s');

        $params['validity_to'] = date('Y-m-d H:i:s', strtotime("+" . EXPIRE_DATE_VALUE . " days"));

        $params['veh_create_date'] = date('Y-m-d');

        //print_r($params);exit();

        // Validation verify

        if ($this->form_validation->run() == FALSE) {

            $returnResponse['validation'] = $this->form_validation->error_array();

            echo json_encode($returnResponse);
            exit();

        }

        // Rename // Rename Profile Photo

        if (isset($params['veh_speed_governer_photo']) && strlen($params['veh_speed_governer_photo']) > 0) {

            if (strpos($params['veh_speed_governer_photo'], 'temp_upload') !== false) {

                $profile_photo = str_replace('public/temp_upload_renewal/', 'public/upload/vehicle_renewal/', $params['veh_speed_governer_photo']);

                rename($params['veh_speed_governer_photo'], $profile_photo);

                $params['veh_speed_governer_photo'] = $profile_photo;

            }

        }

        // Rename // Rename Profile Photo

        if (isset($params['veh_photo']) && strlen($params['veh_photo']) > 0) {

            if (strpos($params['veh_photo'], 'temp_upload') !== false) {

                $profile_photo = str_replace('public/temp_upload_renewal/', 'public/upload/vehicle_renewal/', $params['veh_photo']);

                rename($params['veh_photo'], $profile_photo);

                $params['veh_photo'] = $profile_photo;

            }

        }


        $params['veh_created_user_id'] = $this->session->userdata('user_id');

        //Pass params to Model

        $response = $this->adminmodel->create_renewal_vehicle_records($params);

        if (empty($response)) {

            $returnResponse['error'] = "Please Enter valid Details.";

            echo json_encode($returnResponse);
            exit();

        }

        $reduce = $this->commonmodel->Reduce_Certificate($userID = $this->session->userdata('user_id'));


        $returnResponse['success'] = true;

        $user_type = $this->session->userdata('user_type');

        if ((string)$user_type === '0' || (string)$user_type === '2') {

            $returnResponse['redirect'] = 'admin/renewal_list';

        } elseif ((string)$user_type === '1') {

            $returnResponse['redirect'] = 'dealer/renewal_list';

        }


        echo json_encode($returnResponse);
        exit();

    }

    public function create_renewal_entry()
    {

        $user_type = $this->session->userdata('user_type');

        if (!isset($user_type) && (string)$user_type === '') {

            redirect(base_url(), 'refresh');
            exit();
        }

        $user_id = $this->session->userdata('user_id');

        $data['rto_list'] = $this->commonmodel->allRtoNumbers();

        $data['make_list'] = $this->commonmodel->allMakeList();

        $data['serialList'] = $this->commonmodel->allSerialList($user_id);

        $data['company_list'] = $this->commonmodel->allCompanyList($user_id);


        //----------

        $data['userinfo'] = $this->commonmodel->Check_Certificate_Validity($userID = $this->session->userdata('user_id'));

        if (empty($data['userinfo'])) {


            $returnResponse['error'] = "You donot have the certificate create limit, Please Contact Administrator.";
            echo json_encode($returnResponse);
            exit();

        } else {

//			print_r($data['userinfo']);

            $allotted = $data['userinfo']['allotted'];
            $used = $data['userinfo']['used'];
            $available = $data['userinfo']['allotted'] - $data['userinfo']['used'];

        }
        //----------


        //print_r($data['company_list']);exit();

        // Load Content

        $_SESSION['currentActivePage'] = 'Create_Entry';

        $this->load->view('masters/create_renewal_vehicle', $data);

    }


    public function renewal_list()

    {

        $user_type = $this->session->userdata('user_type');

        if (!isset($user_type) && (string)$user_type === '') {

            redirect(base_url(), 'refresh');

            exit();

        }

        $user_id = $this->session->userdata('user_id');

        $limit = LIST_PAGE_LIMIT;

        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;

        if ($offset != 0) {

            $offset = ((int)$limit * (int)$offset) - (int)$limit;

        }

        if (!isset($_GET['start_date']) || strlen($_GET['start_date']) === 0) {

            $_GET['start_date'] = 0;

        }

        if (!isset($_GET['end_date']) || strlen($_GET['end_date']) === 0) {

            $_GET['end_date'] = 0;

        }

        $search = isset($_GET['search']) ? $_GET['search'] : '';

        $data['totalNoOfVehicles'] = $this->commonmodel->totalNoOfVehicleRenewals($user_id);

        $data['listofvehicles'] = $this->commonmodel->listofvehicleRenewals($limit, $offset, $search, $user_id);
//print_r("Reached-100"); exit();
        //print_r($data['listofvehicles']);exit();

        $_SESSION['currentActivePage'] = 'Entry_List';

        $this->load->view('masters/vehicle_renewal_list', $data);

    }


// Add on - addition Ends &&&&&&&&&&&

    public function entry_list_old()

    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'menu_cerificate_list')) {
            redirect(base_url(), 'refresh');
            exit();
        }

        $user_id = $this->session->userdata('user_id');

        $limit = LIST_PAGE_LIMIT;

        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;

        if ($offset != 0) {

            $offset = ((int)$limit * (int)$offset) - (int)$limit;

        }

        if (!isset($_GET['start_date']) || strlen($_GET['start_date']) === 0) {

            $_GET['start_date'] = 0;

        }

        if (!isset($_GET['end_date']) || strlen($_GET['end_date']) === 0) {

            $_GET['end_date'] = 0;

        }

        $search = isset($_GET['search']) ? $_GET['search'] : '';
        
        // 19-04-2023
        $checkBox = isset($_GET['scales']) ? $_GET['scales'] :'OFF';
        $data['startDate'] =date('Y-m-01');
        $data['endDate'] =date('Y-m-d');

        $data['totalNoOfVehicles'] = $this->commonmodel->totalNoOfVehicle($user_id);

        $data['listofvehicles'] = $this->commonmodel->listofvehicle($limit, $offset, $search, $user_id);
        
        $data['dealer_list'] = $this->commonmodel->fetch_list_of_active_dealers(['user_type' => 1], 0);

        //$data['customer_list']=$this->commonmodel->allCustomerList();

        $_SESSION['currentActivePage'] = 'Cerificate_LIST';

        $this->load->view('masters/vehicle_list', $data);

    }
    
    
    public function entry_list()
    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'menu_cerificate_list')) {
            redirect(base_url(), 'refresh');
            exit();
        }
        
        $user_id = $this->session->userdata('user_id');

        $limit = LIST_PAGE_LIMIT;

        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;

        if ($offset != 0) 
        {
            $offset = ((int)$limit * (int)$offset) - (int)$limit;
        }

        if (!isset($_GET['start_date']) || strlen($_GET['start_date']) === 0)
        {
            $_GET['start_date'] = 0;
            $startDate = ($_GET['start_date']);
        } else {
            $startDate = ($_GET['start_date']);
        }

        if (!isset($_GET['end_date']) || strlen($_GET['end_date']) === 0) {

            $_GET['end_date'] = 0;
            $endDate = ($_GET['end_date']);
        } else {
            $endDate = ($_GET['end_date']);
        }

        $search = isset($_GET['search']) ? $_GET['search'] : '';
        
        // echo "<pre>";print_r($startDate);
        // echo "<pre>";print_r($endDate);exit;

        $params = $this->input->post();

        $data['startDate'] = date('Y-m-01');
        $data['endDate'] = date('Y-m-d');

        //dealer login
        if($user_type == 1){
            $dealer_id = $this->session->userdata('user_id');
        }
        else{
            $dealer_id = isset($_GET['dealer_id']) ? $_GET['dealer_id'] : 0;
        }

        //distributor login
        if($user_type == 2){
            $distributor_id = $this->session->userdata('user_id');
            // echo "<pre>";print_r($distributor_id);exit;
        }
        else{
            $distributor_id = isset($_GET['distributor_id']) ? $_GET['distributor_id'] : 0;
        }
        $state = isset($_GET['state_id']) ? $_GET['state_id'] : 0;
        $veh_rto_no = isset($_GET['veh_rto_no']) ? $_GET['veh_rto_no'] : 0;
        // echo "<pre>";print_r($endDate);exit;

        $data['totalNoOfVehicles'] = $this->commonmodel->totalNoOfVehicle($search, $user_id, $dealer_id, $distributor_id , $startDate, $endDate, $state,$veh_rto_no);
        $data['listofvehicles'] = $this->commonmodel->listofvehicle($limit, $offset, $search, $user_id, $dealer_id, $distributor_id, $startDate, $endDate, $state, $veh_rto_no);
        $data['stateList'] = $this->commonmodel->allStateList();

        if (isset($_GET['state_id']) != "") {
            $data['rto_list'] = $this->commonmodel->getRtoInfoByStateId($state);
        }
        else{
            $data['rto_list'] = '';
        }

        if ($user_type == 0 || $user_type == 4) {
            if (isset($_GET['distributor_id']) != "") {
                $data['dealer_list'] = $this->commonmodel->fetch_dealers(['user_type' => 1], $distributor_id);
            }
            $data['distributor_list'] = $this->commonmodel->fetch_list_of_distributors(['user_type' => 2], 0);
        } else {
            $data['dealer_list'] = "";
            $data['distributor_list'] = "";
        }

        //distributor login
        if ($user_type == 2) {
                $data['dealer_list'] = $this->commonmodel->fetch_dealers(['user_type' => 1], $distributor_id);
        }
        // echo "<pre>";print_r($data);exit();

        $_SESSION['currentActivePage'] = 'Cerificate_LIST';

        $this->load->view('masters/vehicle_list', $data);
        
        // //Permission
        // $user_type = $this->session->userdata('user_type');
        // if (!check_permission($user_type, 'menu_cerificate_list')) {
        //     redirect(base_url(), 'refresh');
        //     exit();
        // }

        // $user_id = $this->session->userdata('user_id');

        // $limit = LIST_PAGE_LIMIT;

        // $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;

        // if ($offset != 0) {

        //     $offset = ((int)$limit * (int)$offset) - (int)$limit;
        // }

        // if (!isset($_GET['start_date']) || strlen($_GET['start_date']) === 0) {

        //     $_GET['start_date'] = 0;
        //     $startDate = ($_GET['start_date']);
        // } else {
        //     $startDate = ($_GET['start_date']);
        // }

        // if (!isset($_GET['end_date']) || strlen($_GET['end_date']) === 0) {

        //     $_GET['end_date'] = 0;
        //     $endDate = ($_GET['end_date']);
        // } else {
        //     $endDate = ($_GET['end_date']);
        // }

        // $search = isset($_GET['search']) ? $_GET['search'] : '';
        // $checkBox = isset($_GET['scales']) ? $_GET['scales'] : 'off';
        
        // // echo "<pre>";print_r($startDate);exit;

        // $params = $this->input->post();

        // $data['startDate'] = date('Y-m-01');
        // $data['endDate'] = date('Y-m-d');

        // //dealer login
        // if($user_type == 1){
        //     $dealer_id = $this->session->userdata('user_id');
        // }
        // else{
        //     $dealer_id = isset($_GET['dealer_id']) ? $_GET['dealer_id'] : 0;
        // }

        // //distributor login
        // if($user_type == 2){
        //     $distributor_id = $this->session->userdata('user_id');
        //     // echo "<pre>";print_r($distributor_id);exit;
        // }
        // else{
        //     $distributor_id = isset($_GET['distributor_id']) ? $_GET['distributor_id'] : 0;
        // }
        // $state = isset($_GET['state_id']) ? $_GET['state_id'] : 0;


        // $data['totalNoOfVehicles'] = $this->commonmodel->totalNoOfVehicle($search, $user_id, $dealer_id, $distributor_id, $checkBox , $state);
        // $data['listofvehicles'] = $this->commonmodel->listofvehicle($limit, $offset, $search, $user_id, $dealer_id, $distributor_id, $startDate, $endDate, $checkBox,$state);

        // $data['stateList'] = $this->commonmodel->allStateList();
        // // $data['dealer_list'] = $this->commonmodel->fetch_list_of_dealers(['user_type' => 1], 0);

        // if ($user_type == 0) {
        //     if (isset($_GET['distributor_id']) != "") {
        //         $data['dealer_list'] = $this->commonmodel->fetch_dealers(['user_type' => 1], $distributor_id);
        //     }
        //     $data['distributor_list'] = $this->commonmodel->fetch_list_of_distributors(['user_type' => 2], 0);
        // } else {
        //     $data['dealer_list'] = "";
        //     $data['distributor_list'] = "";
        // }

        // //distributor login
        // if ($user_type == 2) {
        //         $data['dealer_list'] = $this->commonmodel->fetch_dealers(['user_type' => 1], $distributor_id);
        // }
        // // echo "<pre>";print_r($data);exit();

        // $_SESSION['currentActivePage'] = 'Cerificate_LIST';

        // $this->load->view('masters/vehicle_list', $data);
    }

    public function invoices_list()

    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'menu_invoice')) {
            redirect(base_url(), 'refresh');
            exit();
        }


        $user_id = $this->session->userdata('user_id');

        $limit = LIST_PAGE_LIMIT;

        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;

        if ($offset != 0) {

            $offset = ((int)$limit * (int)$offset) - (int)$limit;

        }

        if (!isset($_GET['start_date']) || strlen($_GET['start_date']) === 0) {

            $_GET['start_date'] = 0;

        }

        if (!isset($_GET['end_date']) || strlen($_GET['end_date']) === 0) {

            $_GET['end_date'] = 0;

        }

        $search = isset($_GET['search']) ? $_GET['search'] : '';

        $data['totalNoOfInvoices'] = $this->commonmodel->totalNoOfInvoices($user_id);
        
        $data['listofInvoices'] = $this->commonmodel->listofInvoices($limit, $offset, $search, $user_id);
        
        //print_r($data['listofvehicles']);exit();
        //$data['customer_list']=$this->commonmodel->allCustomerList();

        $_SESSION['currentActivePage'] = 'Entry_List';
        // echo "<pre>";print_r($data);exit();
        $this->load->view('masters/invoices_list', $data);

    }

    public function edit_entry()
    {
        $VehicleID=base64_decode($_GET['q']);
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'cerificate_edit')) {
            redirect(base_url(), 'refresh');
            exit();
        }
        //Permission

        if (!isset($VehicleID) || (string)$VehicleID === '0') {


            redirect(base_url() . 'admin/dashboard', 'refresh');

        }

        $data['rto_list'] = $this->commonmodel->allRtoNumbers();
        
        $data['stateList'] = $this->commonmodel->allStateList();

        $data['userinfo'] = $this->commonmodel->getVehicleInfo($VehicleID);

        if (empty($data['userinfo'])) {

            redirect(base_url() . 'admin/dashboard', 'refresh');

        }

        $data['userinfo']['veh_id'] = base64_encode($VehicleID);

        $data['pageTitle'] = 'Edit Vehicle';


        $user_id = $this->session->userdata('user_id');

        $data['make_list'] = $this->commonmodel->allMakeList();
        
        $data['serialList'] = $this->commonmodel->allSerialList($user_id, $data['userinfo']['s_state_id']);
        // echo "<pre>";print_r($data);exit();

        $data['company_list'] = $this->commonmodel->allCompanyList($user_id);
        
        $data['technician_list'] = $this->commonmodel->alltechnicianList($user_id);
        
        // Load Content
        $this->load->view('masters/edit_vehicle ', $data);
    }


    public function update_vehicle_records()
    {
        $params = $this->input->post();

        $params['veh_id'] = base64_decode($params['veh_id']);


        $returnResponse = array();

        $returnResponse['validation'] = array();

        $returnResponse['error'] = "";

        $returnResponse['success'] = "";

        // Validation

        $this->form_validation->set_rules('veh_create_date', 'Vehicle Created Date', 'trim|required');

        // $this->form_validation->set_rules('veh_rc_no', 'Vehicle Rc Number', 'trim|required');
        if($params['scales']!="on"){
            $this->form_validation->set_rules('veh_rc_no', 'Vehicle Rc Number', 'trim|required');
            // $this->form_validation->set_rules('rc_book_photo', 'Rc Photo', 'trim|required');
        }

        // $this->form_validation->set_rules('veh_chassis_no', 'Vehicle Chassis Number', 'trim|required');

        // $this->form_validation->set_rules('veh_company_id', 'Vehicle Company Name', 'trim|required');

        // $this->form_validation->set_rules('veh_engine_no', 'Vehicle Engine Number', 'trim|required');

        // $this->form_validation->set_rules('veh_make_no', 'Vehicle Make Number', 'trim|required');

        // $this->form_validation->set_rules('veh_model_no', 'Vehicle Model Number', 'trim|required');

        // $this->form_validation->set_rules('veh_owner_name', 'Vehicle Owner Name', 'trim|required');

        // $this->form_validation->set_rules('veh_address', 'Vehicle Address', 'trim|required');

        $this->form_validation->set_rules('veh_owner_phone', 'Vehicle Owner Number', 'trim|required');

        $this->form_validation->set_rules('veh_serial_no', 'Vehicle Serial Number', 'trim|required');

        $this->form_validation->set_rules('veh_rto_no', 'Vehicle RTO Number', 'trim|required');
        
        // $this->form_validation->set_rules('state', 'State', 'trim|required');

        // $this->form_validation->set_rules('veh_speed', 'Vehicle Speed', 'trim|required');

        // $this->form_validation->set_rules('veh_tac', 'Vehicle Tac Number', 'trim|required');

        // $this->form_validation->set_rules('veh_cat', 'Vehicle Category', 'trim|required');

        // $this->form_validation->set_rules('veh_invoice_no', 'Vehicle Invoice Number', 'trim|required');

        $this->form_validation->set_rules('veh_speed_governer_photo', 'Device Photo', 'trim|required');

        $this->form_validation->set_rules('veh_photo', 'Vehicle Photo', 'trim|required');

        // $this->form_validation->set_rules('selling_price', 'Selling Price', 'trim|required');
        
        if($params['veh_rc_no'] != "NEW REGISTRATION"){
            // $this->form_validation->set_rules(
            //     'veh_rc_no', 'Vehicle Rc Number',
            //     array(
            //         'required',
            //         array(
            //             'already_exits',
            //             function ($str) {
            //                 return $this->commonmodel->verify_exits_vehicle_records($str, 'veh_rc_no');
            //             }
            //         )
            //     )
            // );
            $this->form_validation->set_rules(
                'veh_rc_no', 'Vehicle Rc Number',
                array(
                'required',
                array(
                    'already_exits',
                    function ($str) {
                        $veh_id = $this->input->post('veh_id');
                        $veh_id = base64_decode($veh_id);
                        return $this->commonmodel->verify_exits_vehicle_records($str, 'veh_rc_no', $veh_id);
                    }
                )
            )
        );
        }

        
        if($params['veh_engine_no']!=""){
            // $this->form_validation->set_rules(
            //     'veh_engine_no', 'Vehicle Engine Number',
            //     array(
            //         'required',
            //         array(
            //             'already_exits',
            //             function ($str) {
            //                 return $this->commonmodel->verify_exits_vehicle_records($str, 'veh_engine_no');
            //             }    
            //         )
            //     )
            // );
             $this->form_validation->set_rules(
                'veh_engine_no', 'Vehicle Engine Number',
                array(
                'required',
                array(
                    'already_exits',
                    function ($str) {
                        $veh_id = $this->input->post('veh_id');
                        $veh_id = base64_decode($veh_id);
                        return $this->commonmodel->verify_exits_vehicle_records($str, 'veh_engine_no', $veh_id);
                    }
                )
                )
            );
        }

        if($params['veh_chassis_no']!=""){
            /* $this->form_validation->set_rules(
                'veh_chassis_no', 'Vehicle Chassis Number',
                array(
                    'required',
                    array(
                        'already_exits',
                        function ($str) {
                            return $this->commonmodel->verify_exits_vehicle_records($str, 'veh_chassis_no');
                        }
                    )
                )
            ); */
            $this->form_validation->set_rules(
                'veh_chassis_no', 'Vehicle Chassis Number',
                array(
                'required',
                array(
                    'already_exits',
                    function ($str) {
                        $veh_id = $this->input->post('veh_id');
                        $veh_id = base64_decode($veh_id);
                        return $this->commonmodel->verify_exits_vehicle_records($str, 'veh_chassis_no', $veh_id);
                    }
                )
                )
            );
        }

        // $this->form_validation->set_rules(

        //     'veh_rc_no', 'Vehicle Rc Number',

        //     array(

        //         'required',

        //         array(

        //             'already_exits',

        //             function ($str) {

        //                 $veh_id = $this->input->post('veh_id');

        //                 $veh_id = base64_decode($veh_id);

        //                 return $this->commonmodel->verify_exits_vehicle_records($str, 'veh_rc_no', $veh_id);

        //             }

        //         )

        //     )

        // );


        // $this->form_validation->set_rules(

        //     'veh_chassis_no', 'Vehicle Chassis Number',

        //     array(

        //         'required',

        //         array(

        //             'already_exits',

        //             function ($str) {

        //                 $veh_id = $this->input->post('veh_id');

        //                 $veh_id = base64_decode($veh_id);

        //                 return $this->commonmodel->verify_exits_vehicle_records($str, 'veh_chassis_no', $veh_id);

        //             }

        //         )

        //     )

        // );


        // $this->form_validation->set_rules(

        //     'veh_engine_no', 'Vehicle Engine Number',

        //     array(

        //         'required',

        //         array(

        //             'already_exits',

        //             function ($str) {

        //                 $veh_id = $this->input->post('veh_id');

        //                 $veh_id = base64_decode($veh_id);

        //                 return $this->commonmodel->verify_exits_vehicle_records($str, 'veh_engine_no', $veh_id);

        //             }

        //         )

        //     )

        // );


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


        if (isset($params['veh_rc_no']) && strlen($params['veh_rc_no']) > 0) {

            $params['veh_rc_no'] = preg_replace('/\s+/', '', $params['veh_rc_no']);

        }


        //print_r($params);exit();


        // Validation verify

        if ($this->form_validation->run() == FALSE) {


            $returnResponse['validation'] = $this->form_validation->error_array();

            echo json_encode($returnResponse);
            exit();

        }

        
        $fetchCompanyInfo = $this->commonmodel->fetch(

            'c_company_id',

            $params['veh_company_id'],

            'c_company_name,c_cop_validity',

            $this->db->table_company);

        $params['veh_cop_validity'] = isset($fetchCompanyInfo['c_cop_validity']) ? $fetchCompanyInfo['c_cop_validity'] : date('Y-m-d H:i:s');

        $params['veh_sld_make'] = isset($fetchCompanyInfo['c_company_name']) ? $fetchCompanyInfo['c_company_name'] : "";

        $params['validity_from'] = date('Y-m-d H:i:s', strtotime($params['veh_create_date']));

        if (isset($params['veh_register_date']) && strlen($params['veh_register_date']) > 0) {
            $params['veh_register_date'] = date('Y-m-d', strtotime($params['veh_register_date']));
        }

        //$params['validity_to']=date('Y-m-d H:i:s',strtotime("+".EXPIRE_DATE_VALUE." days", strtotime($params['validity_from'])));

        // Rename Profile Photo

        if (isset($params['vehicle_owner_id_proof_photo']) && strlen($params['vehicle_owner_id_proof_photo']) > 0) {

            if (strpos($params['vehicle_owner_id_proof_photo'], 'temp_upload') !== false) {

                $profile_photo = str_replace('public/temp_upload/', 'public/upload/vehicle_owner_id_proof/', $params['vehicle_owner_id_proof_photo']);

                rename($params['vehicle_owner_id_proof_photo'], $profile_photo);

                $params['vehicle_owner_id_proof_photo'] = $profile_photo;

            }

        }
        // Rename // Rename Profile Photo

        if (isset($params['vehicle_owners_photo']) && strlen($params['vehicle_owners_photo']) > 0) {

            if (strpos($params['vehicle_owners_photo'], 'temp_upload') !== false) {

                $profile_photo = str_replace('public/temp_upload/', 'public/upload/vehicle_owners_photos/', $params['vehicle_owners_photo']);

                rename($params['vehicle_owners_photo'], $profile_photo);

                $params['vehicle_owners_photo'] = $profile_photo;

            }

        }
// Rename // Rename Profile Photo

        if (isset($params['rc_book_photo']) && strlen($params['rc_book_photo']) > 0) {

            if (strpos($params['rc_book_photo'], 'temp_upload') !== false) {

                $profile_photo = str_replace('public/temp_upload/', 'public/upload/rc_book_photos/', $params['rc_book_photo']);

                rename($params['rc_book_photo'], $profile_photo);

                $params['rc_book_photo'] = $profile_photo;

            }

        }
        // Rename // Rename Profile Photo
        
        if (isset($params['veh_speed_governer_photo']) && strlen($params['veh_speed_governer_photo']) > 0) {

            /* if (strpos($params['veh_speed_governer_photo'], 'temp_upload') !== false) {

                $profile_photo = str_replace('public/temp_upload/', 'public/upload/vehicle/', $params['veh_speed_governer_photo']);

                rename($params['veh_speed_governer_photo'], $profile_photo);

                $params['veh_speed_governer_photo'] = $profile_photo;

            } */
            if (strpos($params['veh_speed_governer_photo'], 'temp_upload') !== false) {
                $imagePath = $params['veh_speed_governer_photo'];
                $imageData = explode('/', $imagePath);
                $imageName = $imageData[2];
                $path = "public/upload/vehicle";
                $deviceImage = $this->awsImageUpload($imagePath, $imageName, $path);
                $dats = explode('/', $deviceImage);
                $params['veh_speed_governer_photo'] = $path.'/'.$dats[6];
                if (isset($params['veh_speed_governer_photo']) && strlen($params['veh_speed_governer_photo']) > 0) {
                    unlink($imagePath);
                }
            }

        }

        // Rename // Rename Profile Photo

        if (isset($params['veh_photo']) && strlen($params['veh_photo']) > 0) {

            if (strpos($params['veh_photo'], 'temp_upload') !== false) {
                $vehImagePath = $params['veh_photo'];
                $vehImageData = explode('/', $vehImagePath);
                $imageName1 = $vehImageData[2];
                $path = "public/upload/vehicle";
                $vehicleImage = $this->awsImageUpload($vehImagePath, $imageName1, $path);
                $dats = explode('/', $vehicleImage);
                $params['veh_photo']  = $path.'/'.$dats[6];
                if (isset($params['veh_photo']) && strlen($params['veh_photo']) > 0) {
                    unlink($vehImagePath);
                }
            }
            /* if (strpos($params['veh_photo'], 'temp_upload') !== false) {

                $profile_photo = str_replace('public/temp_upload/', 'public/upload/vehicle/', $params['veh_photo']);

                rename($params['veh_photo'], $profile_photo);

                $params['veh_photo'] = $profile_photo;

            } */

        }
        
         if($params['scales']=="on"){
            $params['scales']= 1;
        }
        else{
            $params['scales']= 0;
        }
        
        //Pass params to Model
        // echo "<pre>";print_r($params);
        // echo "<pre>";print_r("wwewe");exit;
        $response = $this->adminmodel->modify_vehicle_records($params, $params['veh_id']);
        
        if (empty($response)) {

            $returnResponse['error'] = "Please Enter valid Details.";

            echo json_encode($returnResponse);
            exit();

        }

        // Set Session

        $this->session->set_userdata($response);

        $returnResponse['success'] = true;

        $user_type = $this->session->userdata('user_type');

        if ((string)$user_type === '0') {

            $returnResponse['redirect'] = 'admin/entry_list';

        } else {

            $returnResponse['redirect'] = 'dealer/entry_list';

        }

        echo json_encode($returnResponse);
        exit();

    }


    public function dealersalesreport()

    {

        $data['make_list'] = $this->commonmodel->allMakeList();

        $data['rto_list'] = $this->commonmodel->allRtoNumbers();

        $data['company_list'] = $this->commonmodel->allCompanyList();

        $this->load->view('report/dealersalesreport', $data);

    }


    public function view_dealersalesreport()

    {

        $params = $this->input->get();


        if (isset($params['start_date'])) {

            $params['start_date'] = $params['start_date'] . ' 00:00:00';

        }


        if (isset($params['end_date'])) {

            $params['end_date'] = $params['end_date'] . ' 23:59:59';

        }

        if (strtotime($params['end_date']) < strtotime($params['start_date'])) {

            $start = $params['start_date'];

            $end = $params['end_date'];

            $params['start_date'] = date('Y-m-d', strtotime($end)) . ' 00:00:00';

            $params['end_date'] = date('Y-m-d', strtotime($start)) . ' 23:59:59';

        }

        $data['reportData'] = $this->adminmodel->view_dealersalesreport($params);

        $data['params'] = $params;

        //print_r($data);exit();

        $this->load->view('report/view_dealersalesreport', $data);

    }


    public function inventoryreport()

    {

        $data['make_list'] = $this->commonmodel->allMakeList();

        $data['rto_list'] = $this->commonmodel->allRtoNumbers();

        $data['company_list'] = $this->commonmodel->allCompanyList();

        $this->load->view('report/inventoryreport', $data);

    }


    public function view_inventoryreport()

    {

        $params = $this->input->get();


        if (isset($params['start_date'])) {

            $params['start_date'] = $params['start_date'] . ' 00:00:00';

        }


        if (isset($params['end_date'])) {

            $params['end_date'] = $params['end_date'] . ' 23:59:59';

        }

        if (strtotime($params['end_date']) < strtotime($params['start_date'])) {

            $start = $params['start_date'];

            $end = $params['end_date'];

            $params['start_date'] = date('Y-m-d', strtotime($end)) . ' 00:00:00';

            $params['end_date'] = date('Y-m-d', strtotime($start)) . ' 23:59:59';

        }

        $data['reportData'] = $this->adminmodel->view_inventoryreport($params);

        $data['params'] = $params;

        //print_r($data);exit();

        $this->load->view('report/view_inventoryreport', $data);

    }


    public function salesreport()

    {

        $user_type = $this->session->userdata('user_type');

        if (isset($user_type) && (string)$user_type != '0'&& $user_type != '4') {

            redirect(base_url() . 'admin/dashboard', 'refresh');

            exit();

        }

        $data['make_list'] = $this->commonmodel->allMakeList();

        $data['rto_list'] = $this->commonmodel->allRtoNumbers();

        $data['company_list'] = $this->commonmodel->allCompanyList();

        $this->load->view('report/salesreport', $data);

    }

    public function daily_reports_old(){

        $user_type = $this->session->userdata('user_type');

        if (isset($user_type) && (string)$user_type != '0'&& $user_type != '4') {

            redirect(base_url() . 'admin/dashboard', 'refresh');

            exit();

        }

        $user_id = $this->session->userdata('user_id');

        $limit = LIST_PAGE_LIMIT;

        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;

        if ($offset != 0) 
        {
            $offset = ((int)$limit * (int)$offset) - (int)$limit;
        }

        if (!isset($_GET['start_date']) || strlen($_GET['start_date']) === 0)
        {
            $_GET['start_date'] = 0;
            $startDate = ($_GET['start_date']);
        } else {
            $startDate = ($_GET['start_date']);
        }

        if (!isset($_GET['end_date']) || strlen($_GET['end_date']) === 0) {

            $_GET['end_date'] = 0;
            $endDate = ($_GET['end_date']);
        } else {
            $endDate = ($_GET['end_date']);
        }

        $search = isset($_GET['search']) ? $_GET['search'] : '';

        $params = $this->input->post();

        $data['startDate'] = date('Y-m-01');
        $data['endDate'] = date('Y-m-d');

        //dealer login
        if($user_type == 1){
            $dealer_id = $this->session->userdata('user_id');
        }
        else{
            $dealer_id = isset($_GET['dealer_id']) ? $_GET['dealer_id'] : 0;
        }

        //distributor login
        if($user_type == 2){
            $distributor_id = $this->session->userdata('user_id');
            // echo "<pre>";print_r($distributor_id);exit;
        }
        else{
            $distributor_id = isset($_GET['distributor_id']) ? $_GET['distributor_id'] : 0;
        }
        $state = isset($_GET['state_id']) ? $_GET['state_id'] : 0;
        $veh_rto_no = isset($_GET['veh_rto_no']) ? $_GET['veh_rto_no'] : 0;
        // echo "<pre>";print_r($endDate);exit;

        $data['totalNoOfVehicles'] = $this->commonmodel->totalNoOfVehicle($search, $user_id, $dealer_id, $distributor_id , $startDate, $endDate, $state,$veh_rto_no);
        $data['listofvehicles'] = $this->commonmodel->listofvehicle($limit, $offset, $search, $user_id, $dealer_id, $distributor_id, $startDate, $endDate, $state, $veh_rto_no);
        $data['stateList'] = $this->commonmodel->allStateList();

        if (isset($_GET['state_id']) != "") {
            $data['rto_list'] = $this->commonmodel->getRtoInfoByStateId($state);
        }
        else{
            $data['rto_list'] = '';
        }

        if ($user_type == 0 || $user_type == 4) {
            if (isset($_GET['distributor_id']) != "") {
                $data['dealer_list'] = $this->commonmodel->fetch_dealers(['user_type' => 1], $distributor_id);
            }
            $data['distributor_list'] = $this->commonmodel->fetch_list_of_distributors(['user_type' => 2], 0);
        } else {
            $data['dealer_list'] = "";
            $data['distributor_list'] = "";
        }

        //distributor login
        if ($user_type == 2) {
                $data['dealer_list'] = $this->commonmodel->fetch_dealers(['user_type' => 1], $distributor_id);
        }
        // echo "<pre>";print_r($data);exit();

        $_SESSION['currentActivePage'] = 'Cerificate_LIST';


        $this->load->view('report/dailyreport', $data);

    }

    public function daily_reports()
    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'menu_cerificate_list')) {
            redirect(base_url(), 'refresh');
            exit();
        }
        
        $user_id = $this->session->userdata('user_id');

        $limit = LIST_PAGE_LIMIT;

        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;

        if ($offset != 0) 
        {
            $offset = ((int)$limit * (int)$offset) - (int)$limit;
        }

        if (!isset($_GET['start_date']) || strlen($_GET['start_date']) === 0)
        {
            $_GET['start_date'] = 0;
            $startDate = ($_GET['start_date']);
        } else {
            $startDate = ($_GET['start_date']);
        }

        if (!isset($_GET['end_date']) || strlen($_GET['end_date']) === 0) {

            $_GET['end_date'] = 0;
            $endDate = ($_GET['end_date']);
        } else {
            $endDate = ($_GET['end_date']);
        }

        $search = isset($_GET['search']) ? $_GET['search'] : 'tt';
        
        // echo "<pre>";print_r($startDate);
        // echo "<pre>";print_r($search);exit;

        $params = $this->input->post();

        $data['startDate'] = date('Y-m-01');
        $data['endDate'] = date('Y-m-d');

        //dealer login
        if($user_type == 1){
            $dealer_id = $this->session->userdata('user_id');
        }
        else{
            $dealer_id = isset($_GET['dealer_id']) ? $_GET['dealer_id'] : 0;
        }

        //distributor login
        if($user_type == 2){
            $distributor_id = $this->session->userdata('user_id');
            // echo "<pre>";print_r($distributor_id);exit;
        }
        else{
            $distributor_id = isset($_GET['distributor_id']) ? $_GET['distributor_id'] : 0;
        }
        $state = isset($_GET['state_id']) ? $_GET['state_id'] : 0;
        $veh_rto_no = isset($_GET['veh_rto_no']) ? $_GET['veh_rto_no'] : 0;

        $data['totalNoOfVehicles'] = $this->commonmodel->totalNoOfVehicle($search, $user_id, $dealer_id, $distributor_id , $startDate, $endDate, $state,$veh_rto_no);
        // echo "<pre>";print_r($data['totalNoOfVehicles']);exit;

        $data['listofvehicles'] = $this->commonmodel->listofvehicledailyrun($limit, $offset, $search, $user_id, $dealer_id, $distributor_id, $startDate, $endDate, $state, $veh_rto_no);
        $data['stateList'] = $this->commonmodel->allStateList();

        if (isset($_GET['state_id']) != "") {
            $data['rto_list'] = $this->commonmodel->getRtoInfoByStateId($state);
        }
        else{
            $data['rto_list'] = '';
        }

        if ($user_type == 0 || $user_type == 4) {
            if (isset($_GET['distributor_id']) != "") {
                $data['dealer_list'] = $this->commonmodel->fetch_dealers(['user_type' => 1], $distributor_id);
            }
            $data['distributor_list'] = $this->commonmodel->fetch_list_of_distributors(['user_type' => 2], 0);
        } else {
            $data['dealer_list'] = "";
            $data['distributor_list'] = "";
        }

        //distributor login
        if ($user_type == 2) {
                $data['dealer_list'] = $this->commonmodel->fetch_dealers(['user_type' => 1], $distributor_id);
        }
        // echo "<pre>";print_r($data);exit();

        $_SESSION['currentActivePage'] = 'Cerificate_LIST';

        $this->load->view('report/dailyreport', $data);
    
    }


    public function view_salesreport()

    {

        $params = $this->input->get();


        if (isset($params['start_date'])) {

            $params['start_date'] = $params['start_date'] . ' 00:00:00';

        }


        if (isset($params['end_date'])) {

            $params['end_date'] = $params['end_date'] . ' 23:59:59';

        }

        if (strtotime($params['end_date']) < strtotime($params['start_date'])) {

            $start = $params['start_date'];

            $end = $params['end_date'];

            $params['start_date'] = date('Y-m-d', strtotime($end)) . ' 00:00:00';

            $params['end_date'] = date('Y-m-d', strtotime($start)) . ' 23:59:59';

        }

        $data['reportData'] = $this->adminmodel->view_salesreport($params);

        $data['params'] = $params;

        //print_r($data);exit();

        $this->load->view('report/view_salesreport', $data);

    }

    public function view_dailyreport()

    {

        $params = $this->input->get();


        if (isset($params['start_date'])) {

            $params['start_date'] = $params['start_date'] . ' 00:00:00';

        }


        if (isset($params['end_date'])) {

            $params['end_date'] = $params['end_date'] . ' 23:59:59';

        }

        if (strtotime($params['end_date']) < strtotime($params['start_date'])) {

            $start = $params['start_date'];

            $end = $params['end_date'];

            $params['start_date'] = date('Y-m-d', strtotime($end)) . ' 00:00:00';

            $params['end_date'] = date('Y-m-d', strtotime($start)) . ' 23:59:59';

        }

        $data['reportData'] = $this->adminmodel->view_salesreport($params);

        $data['params'] = $params;

        //print_r($data);exit();

        $this->load->view('report/view_dailyreport', $data);

    }


    public function check_device_data($date,$startTime,$endTime,$imei)
    {
        $user_type = $this->session->userdata('user_type');
        if (!isset($user_type) && ((string)$user_type === '' || (string)$user_type == '2')) {
            redirect(base_url(), 'refresh');
            exit();
        }

        $this->session->set_userdata('currentActivePage', 'check_device_data');

        $data['date'] = $date;

        $data['startTime'] = $startTime;

        $data['endTime'] = $endTime;
        if($imei != 0)
        $data['imei'] = $imei;
        else
            $data['imei'] = "";
        // Load Content
        $this->load->view('masters/check_device_data',$data);
    }
    
//     public function check_validlatlang()
//     {
//     //   echo "<pre>";print_r('check_validlatlang');exit;
//         $data = $this->commonmodel->veh_lat_long_check();
// echo "<pre>";print_r($data);exit;
       
//     }



    public function delete_saved_history($id)
    {

        $user_type = $this->session->userdata('user_type');
        if (!isset($user_type) && ((string)$user_type === '' || (string)$user_type == '2')) {
            redirect(base_url(), 'refresh');
            exit();
        }
        $this->db->where('id', $id);
        $this->db->delete($this->db->table_imei_history);
        //    $this->session->set_userdata('currentActivePage', 'check_device_data');
        // Load Content
        $this->load->view('masters/view_saved_history_data');
    }
    public function view_imei_saved_history()
    {

        $user_type = $this->session->userdata('user_type');
        if (!isset($user_type) && ((string)$user_type === '' || (string)$user_type == '2')) {
            redirect(base_url(), 'refresh');
            exit();
        }

    //    $this->session->set_userdata('currentActivePage', 'check_device_data');
        // Load Content
        $this->load->view('masters/view_saved_history_data');
    }

    // Check Serial number

    public function check_device_status_old()
    {

        $user_type = $this->session->userdata('user_type');
        if (!isset($user_type) && ((string)$user_type === '' || (string)$user_type == '2')) {
            redirect(base_url(), 'refresh');
            exit();
        }
        // $data['company_list']=$this->commonmodel->allCompanyList();
        // if($_POST['hid_company_id']){
        // $data['product_list']=$this->commonmodel->companyProductList($_POST['hid_company_id']);
        // }
        //var_dump($_POST['serial_ids']);exit;
        //$data['serial_list']=$this->commonmodel->fetch_list_of_selected_serial_numbers();
        // $data['distributor_list']=$this->commonmodel->fetch_list_of_users(['user_type'=>2], 0);
        // $data['dealer_list']=$this->commonmodel->fetch_list_of_users(['user_type'=>1], 0);
        //print_r($data['company_list']);exit();
        $this->session->set_userdata('currentActivePage', 'Check_Device_Status');
        // Load Content
        $this->load->view('masters/check_device_status');
    }
    
     public function check_device_status()
    {

        $user_type = $this->session->userdata('user_type');
        if (!isset($user_type) && ((string)$user_type === '' || (string)$user_type == '2')) {
            redirect(base_url(), 'refresh');
            exit();
        }
        // $data['company_list']=$this->commonmodel->allCompanyList();
        // if($_POST['hid_company_id']){
        // $data['product_list']=$this->commonmodel->companyProductList($_POST['hid_company_id']);
        // }
        //var_dump($_POST['serial_ids']);exit;
        //$data['serial_list']=$this->commonmodel->fetch_list_of_selected_serial_numbers();
        // $data['distributor_list']=$this->commonmodel->fetch_list_of_users(['user_type'=>2], 0);
        // $data['dealer_list']=$this->commonmodel->fetch_list_of_users(['user_type'=>1], 0);
        //print_r($data['company_list']);exit();
        $data['stateList'] = $this->commonmodel->allStateList();

        $this->session->set_userdata('currentActivePage', 'Check_Device_Status');
        // Load Content
        $this->load->view('masters/check_device_status',$data);
    }

    /*	public function search_device_status() {

		$params=$this->input->post();
		// print_r($params);
		// exit;
		$checkArray['model_list'] = $this->commonmodel->fetch_imei_numbers($params['imei_no']);
		if($checkArray['model_list']["status"] == "Y") {
			//print_r($checkArray['model_list']['data']); exit;
			foreach($checkArray['model_list']['data'] as $key => $value) {
				$result .= "<tr>
								<td>".$value["vehicleRegnumber"]."</td>
								<td>".$value["customerID"]."</td>
								<td>".$value["vendorName"]."</td>
								<td>".$value["imei"]."</td>
								<td>".$value["simNumber"]."</td>
								<td>".$value["ignition"]."</td>
							</tr>";
			}
			$data["model_list"] = $result;
		} else {
			$data["model_list"] = $checkArray['model_list'];
		}
		echo json_encode($data);exit();
	}
	*/
    public function search_device_data()
    {
        $params = $this->input->post();
        $user_type = $this->session->userdata('user_type');
        $user_id   = $this->session->userdata('user_id');
        
        $data['error'] = 0;
        $checkimei     = 1;
        if($user_type != 1 || $user_type!= 4){
            $checkimei = $this->commonmodel->check_imei_data($params['imei_no'],$user_id,$user_type);
        }
       
        if($checkimei != 0){
           $checkArray['model_list'] = $this->commonmodel->fetch_imei_data($params['imei_no'], $params['start_date'], $params['start_time'], $params['end_time']);
        }
        else{
            $data['error'] = 1;
            echo json_encode($data);
            exit();
        }
        
        $data["model_list"] = $checkArray['model_list'];
        
        echo json_encode($data);
        exit();
    }

    public function save_his_data()
    {
        $params = $this->input->post();
        $checkArray['model_list'] = $this->commonmodel->saveHisData($params['imei_no'], $params['start_date'], $params['start_time'], $params['end_time']);

        $data["model_list"] = $checkArray['model_list'];

        echo json_encode($data);
        exit();
    }


    public function search_device_his_data()
    {
        $params = $this->input->post();
        $checkArray['model_list'] = $this->commonmodel->fetch_imei_history($params['imei_no'], $params['imei_count'], $params['start_date'], $params['start_time'], $params['end_time']);

        $data["model_list"] = $checkArray['model_list'];

        echo json_encode($data);
        exit();
    }

    public function search_device_status()
    {
        $params = $this->input->post();
        // echo "<pre>";print_r($params);exit;
        $user_type    = $this->session->userdata('user_type');
        $user_id      = $this->session->userdata('user_id');
        // $data['imei'] = 1;
        // if($user_type != 0 && $user_type != 4){
             $data['imei'] = -1;
             $validCount   = $this->commonmodel->getValidUser($user_type,$params['imei_no'],$user_id);
             // echo "<pre>";print_r($validCount);exit();
             if($validCount != 0){
                $data['imei'] = 1;
             }
        // }
       
        
        $checkArray['model_list'] = $this->commonmodel->fetch_imei_numbers($params['imei_no']);
        // echo "<pre>";print_r($checkArray);exit;
     
        $serialNo = isset($checkArray['model_list']['data']['s_serial_id']) ? $checkArray['model_list']['data']['s_serial_id'] : '';
        $data['device_logs'] = array();
        $data["model_list"]  = $checkArray['model_list'];
        $data["model_list"]['data']['user_type'] = $user_type;
        $data['veh_state'] = $this->commonmodel->getStateInfo($checkArray['model_list']['data']['veh_state_id']);
        if(!empty($data['model_list']) && $data['model_list']['data']['s_state_id'] == ""){
            $data['status'] =1;
            // $data['imei_number'] =$params['imei_no'];
            // $data['message'] = "State is not assigned to this IMEI number";
            // echo json_encode($data);
            // return false;
            // $data['ser_state'] = array();
        }
        $data['ser_state'] = $this->commonmodel->getStateInfo($checkArray['model_list']['data']['s_state_id']);
        // echo "<pre>";print_r($data['ser_state']);exit;


        if($serialNo != ''){
            $data['device_logs'] = $this->commonmodel->get_device_logs($serialNo);
        }
        // echo "<pre>";print_r($data['device_logs']);exit;
        $current_time  = $this->session->userdata('console_time');

        if($data['model_list']['data'] && !isset($data['model_list']['data']['lastupdatedTime']) && $data['model_list']['data']['s_state_id'] != ""){
            // lastupdatedTime
            $state_data = $this->commonmodel->getRegisteredFirstData($params['imei_no']);
            // echo "str";
            // print_r(isset($data['model_list']['data']['lastupdatedTime']));

            if(!empty($state_data)){
                $data['model_list']['data']['lastupdatedTime'] =  date('Y-m-d H:i:s', strtotime($state_data[0]['created_time']));
            }
        }

        if($data['model_list']['data'] && isset($data['model_list']['data']['lastupdatedTime']) && ($data['model_list']['data']['lastupdatedTime'] =="") && $data['model_list']['data']['s_state_id'] != ""){
            $state_data = $this->commonmodel->getRegisteredFirstData($params['imei_no']);
            // print_r($state_data);exit;

            if(!empty($state_data)){
                $data['model_list']['data']['lastupdatedTime'] =  date('Y-m-d H:i:s', strtotime($state_data[0]['created_time']));
            }

        }

        // getRegisteredData
        // echo "<pre>";print_r($data['state']);
        // echo "<pre>";print_r($data);exit;
        // echo "<pre>";print_r($data["model_list"]['data']['s_state_id']);exit;
        echo json_encode($data);
        exit();
    }

    public function assign_state(){
        $params = $this->input->post();
        // echo "<pre>";print_r($params);exit;
        $user_type    = $this->session->userdata('user_type');
        $user_id      = $this->session->userdata('user_id');

        $data = $this->commonmodel->assign_state($params);
        echo json_encode($data);
        exit();
    }

    public function create_certificate_entry()

    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'menu_cerificate_create')) {
            redirect(base_url(), 'refresh');
            exit();
        }

        $user_id = $this->session->userdata('user_id');

        $data['rto_list'] = $this->commonmodel->allRtoNumbers();

        $data['make_list'] = $this->commonmodel->allMakeList();

        $data['serialList'] = $this->commonmodel->allSerialList($user_id);

        $data['company_list'] = $this->commonmodel->allCompanyList($user_id);

        //print_r($data['company_list']);exit();
        // Load Content

        if ($this->input->post("hidsubmit") != "") {

            $veh_create_date = $this->input->post('veh_create_date');
            $veh_owner_phone = $this->input->post('veh_owner_phone');
            $veh_owner_id = $this->input->post('veh_owner_id');
            $veh_owner_name = $this->input->post('veh_owner_name');
            $veh_owner_email = $this->input->post('veh_owner_email');
            $veh_address = $this->input->post('veh_address');
            $veh_rc_no = $this->input->post('veh_rc_no');
            $veh_chassis_no = $this->input->post('veh_chassis_no');
            $veh_engine_no = $this->input->post('veh_engine_no');
            $veh_make_no = $this->input->post('veh_make_no');
            $veh_model_no = $this->input->post('veh_model_no');
            $veh_company_id = $this->input->post('veh_company_id');
            $veh_serial_no = $this->input->post('veh_serial_no');
            $veh_rto_no = $this->input->post('veh_rto_no');
            $veh_tac = $this->input->post('veh_tac');
            $veh_speed = $this->input->post('veh_speed');
            $veh_invoice_no = $this->input->post('veh_invoice_no');
            $selling_price = $this->input->post('selling_price');
            $validity_to = $this->input->post('validity_to');

            $this->db->select('user_id, invoice_prefix, invoice_sequence');
            $this->db->from($this->db->table_users);
            $this->db->where('user_id', $this->session->userdata('user_id'));
            $result = $this->db->get();
            $user = $result->row();

            $user->invoice_sequence = $user->invoice_sequence + 1;

            if ($veh_owner_phone != "") {

                $query_cust = $this->db->query("SELECT * FROM ci_customers where c_phone = '" . $veh_owner_phone . "' ");

                if ($query_cust->num_rows() == "0") {

                    $cust_datains = array("c_customer_name" => $veh_owner_name, "c_address" => $veh_address, "c_phone" => $veh_owner_phone, "c_email" => $veh_owner_email, "c_created_by" => $user_id);
                    $this->db->insert("ci_customers", $cust_datains);

                    $c_customer_id = $this->db->insert_id();

                } else {

                    $query_custrow = $query_cust->row();
                    $c_customer_id = $query_custrow->c_customer_id;

                }
            }

            if ($veh_serial_no != "") {
                $query_serialno = $this->db->query("SELECT * FROM ci_serial_numbers where s_serial_id = '" . $veh_serial_no . "' ");

                if ($query_serialno->num_rows() == "0") {
                    $i_product_id = "0";
                } else {
                    $query_prodrow = $query_serialno->row();
                    $i_product_id = $query_prodrow->s_product_id;
                }

            } else {
                $i_product_id = "0";
            }


            $this->db->set('invoice_sequence', $user->invoice_sequence, FALSE);
            $this->db->where('user_id', $user->user_id);
            $this->db->update($this->db->table_users);


            $datains = array("invoice_number" => $veh_invoice_no . $user->invoice_sequence, "i_user_type" => $user_type, "i_user_id" => $user_id, "i_to_customer_id" => $c_customer_id, "i_product_id" => $i_product_id, "i_serial_ids" => $veh_serial_no, "i_created_by" => $veh_create_date);


            $this->db->insert("ci_invoices_customer", $datains);


            redirect('admin/invoices_list_customers');


        }

        $_SESSION['currentActivePage'] = 'Create_Cerificate';

        $this->load->view('masters/create_certificate_entry', $data);

    }
    
    public function check_rto(){
        $rto_id = $this->input->post('rto_id');
        $count  = $this->commonmodel->check_rto($rto_id);
        // echo "<pre>";print_r($count);exit;

        echo json_encode($count);
		exit();	
    }

    public function delete_rto(){

        $passwordEntered= $this->input->post('password');
        $user_type      = $this->session->userdata('user_type');
        $returnResponse = array();

        $returnResponse['validation'] = array();

        $returnResponse['error'] = "";

        $returnResponse['success'] = "";

            $user_id = $this->session->userdata('user_id');
            $password = $this->adminmodel->getPassword($user_id);
            $responseData = ($password['user_password']);
            $enterPass = md5($passwordEntered);
            // echo "<pre>";print_r($user_id);
            // echo "<pre>";print_r(md5(9836754040));
            // echo "<pre>";print_r($enterPass);exit;
            if ($enterPass != $responseData) {
                // Password is incorrect
                $returnResponse['error'] = 1;
                $returnResponse['message'] = "Password incorrect!";
                echo json_encode($returnResponse);
                exit();

            } 

        $rto_id                     = $this->input->post('rto_id');
        $returnResponse['result']   = $this->commonmodel->delete_rto($rto_id);
        $returnResponse['success']  = true;
        // echo "<pre>";print_r($data);exit;  

        echo json_encode($returnResponse);
        exit();			
    }
    
    public function check_make(){
        $make_id = $this->input->post('make_id');
        $count  = $this->commonmodel->check_make($make_id);
        // echo "<pre>";print_r($count);exit;

        echo json_encode($count);
		exit();	
    }

    public function delete_make(){

        $passwordEntered= $this->input->post('password');
        $user_type      = $this->session->userdata('user_type');
        $returnResponse = array();
        $returnResponse['validation'] = array();
        $returnResponse['error']      = "";
        $returnResponse['success']    = "";

            $user_id = $this->session->userdata('user_id');
            $password = $this->adminmodel->getPassword($user_id);
            $responseData = ($password['user_password']);
            $enterPass = md5($passwordEntered);
            // echo "<pre>";print_r($user_id);
            // echo "<pre>";print_r(md5(9836754040));
            // echo "<pre>";print_r($enterPass);exit;
            if ($enterPass != $responseData) {
                // Password is incorrect
                $returnResponse['error'] = 1;
                $returnResponse['message'] = "Password incorrect!";
                echo json_encode($returnResponse);
                exit();

            } 

        $make_id                     = $this->input->post('make_id');
        $returnResponse['result']   = $this->commonmodel->delete_make($make_id);
        $returnResponse['success']  = true;
        // echo "<pre>";print_r($data);exit;  

        echo json_encode($returnResponse);
        exit();			
    }
    
    public function check_model(){
        $model_id = $this->input->post('model_id');
        $model = base64_decode($model_id);
        // echo "<pre>";print_r($model);exit;
        
        $count  = $this->commonmodel->check_model($model);
        // echo "<pre>";print_r($count);exit;

        echo json_encode($count);
		exit();	
    }

    public function delete_model(){

        $passwordEntered= $this->input->post('password');
        $user_type      = $this->session->userdata('user_type');
        $returnResponse = array();
        $returnResponse['validation'] = array();
        $returnResponse['error']      = "";
        $returnResponse['success']    = "";

            $user_id = $this->session->userdata('user_id');
            $password = $this->adminmodel->getPassword($user_id);
            $responseData = ($password['user_password']);
            $enterPass = md5($passwordEntered);
            // echo "<pre>";print_r($user_id);
            // echo "<pre>";print_r(md5(9836754040));
            // echo "<pre>";print_r($enterPass);exit;
            if ($enterPass != $responseData) {
                // Password is incorrect
                $returnResponse['error'] = 1;
                $returnResponse['message'] = "Password incorrect!";
                echo json_encode($returnResponse);
                exit();

            } 

        $model_id                   = $this->input->post('model_id');
        $model = base64_decode($model_id);
        // echo "<pre>";print_r($model);exit;

        $returnResponse['result']   = $this->commonmodel->delete_model($model);
        $returnResponse['success']  = true;
        // echo "<pre>";print_r($data);exit;  

        echo json_encode($returnResponse);
        exit();			
    }

    public function invoices_list_customers()

    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'menu_invoice')) {
            redirect(base_url(), 'refresh');
            exit();
        }


        $user_id = $this->session->userdata('user_id');

        $limit = LIST_PAGE_LIMIT;

        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;

        if ($offset != 0) {

            $offset = ((int)$limit * (int)$offset) - (int)$limit;

        }

        if (!isset($_GET['start_date']) || strlen($_GET['start_date']) === 0) {

            $_GET['start_date'] = 0;

        }

        if (!isset($_GET['end_date']) || strlen($_GET['end_date']) === 0) {

            $_GET['end_date'] = 0;

        }

        $search = isset($_GET['search']) ? $_GET['search'] : '';

        $data['totalNoOfInvoices'] = $this->commonmodel->totalNoOfInvoices_customers($user_id);
        
        $data['listofInvoices'] = $this->commonmodel->listofInvoices_customers($limit, $offset, $search, $user_id);
        
        
        //$data['customer_list']=$this->commonmodel->allCustomerList();

        $_SESSION['currentActivePage'] = 'Entry_List';
        // echo "<pre>";print_r($data);exit;
        $this->load->view('masters/invoices_list_customers', $data);

    }

    
    public function downloadinvoice_customers()

    {

        $params = $this->input->get();

        if (!isset($params['id'])) {

            redirect(base_url() . 'admin/dashboard', 'refresh');

            exit();

        }

        
        $id = base64_decode(base64_decode(base64_decode($params['id'])));
        // echo "<pre>";print_r($id);exit;
        //echo $id;exit();

        $encodeID = base64_encode(base64_encode(base64_encode($id)));

        if (!is_numeric($id)) {

            redirect(base_url() . 'admin/dashboard', 'refresh');

            exit();

        }

        // $data['userinfo'] = $this->commonmodel->getPdfInvoiceInfo_customers($id);
        // $data['userinfo'] = $this->commonmodel->getPdfInvoiceInfo($id);
        $user_type = $this->session->userdata('user_type');
        if($user_type==1){
            $data['userinfo'] = $this->commonmodel->getPdfInvoiceInfo_customers($id);
        }else{
            $data['userinfo'] = $this->commonmodel->getPdfInvoiceInfo($id);
        }

        $data['serialsinfo'] = $this->commonmodel->getPdfInvoiceSerialsInfo(explode(',', $data['userinfo']['i_serial_ids']));

        $data['userinfo']['qrcodeimg'] = base_url() . "admin/inv_qr_code/?id=" . $encodeID;

        $this->load->view("invoicePDF_customers", $data);

    }

    public function tracking_vehicle()
    {
        if (!check_permission($user_type, 'menu_track_vehicle')) 
        {
            redirect(base_url(), 'refresh');
            exit();
        }
        $data['page_title']='PSDN | Vehicle List';
        $this->session->set_userdata('currentActivePage', 'tracking_vehicle_list');
        $this->load->view('masters/tracking_vehicle_list',$data);
    }
    public function all_customer_vehicles()
    {
        // echo "route"; exit;
        // $this->session->set_userdata('currentActivePage', 'all_customer_vehicles');
        // $datanew = $this->commonmodel->veh_lat_long();
        $data['veh_lat_long'] = $this->commonmodel->veh_lat_long();
        // $data['new'] = json_encode($datanew);
        // echo "<pre>";print_r(($data['veh_lat_long']));exit;
        $this->load->view('masters/all_customer_vehicles',$data);
    }
    
    public function getValitLatLngByImie()
    {
        $imei = $this->input->post('imei');
        $table_name_check = "tbl_trackinghistory_" . $month.$year;

        // Get the current month and year
        $current_month = date('m');
        $current_year = date('Y');
        // $current_date = date('d');
        $todayDate  = date('Y-m-d');
        $dayOfMonth = date('d', strtotime($todayDate));

        if ($dayOfMonth <= 5) {
            $previousMonth = date('m', strtotime('-1 month', strtotime($todayDate)));
            $previousYear  = date('Y', strtotime('-1 month', strtotime($todayDate)));            
            $table_name    = "tbl_trackinghistory_" . $previousMonth.$previousYear;
        } else {
            $table_name    = "tbl_trackingalldatas";
        }
//  echo "<pre>";print_r($table_name);exit;
        $data = $this->commonmodel->getValitLatLngByImie($imei,$table_name);
        // $data = [];
        // echo "<pre>";print_r($data);exit;
        
        echo json_encode($data); 
       }
       
    public function tracking()
    {
        // echo "hai"; exit;
        $data['page_title']='PSDN | Tracking';
        //$this->session->set_userdata('currentActivePage', 'tracking');
        $this->load->view('masters/tracking',$data);       
    }
    // public function search_vehicle()
    // {
    //     $params = $this->input->post();
    //     $imei_no=$params['search_imei'];
    //     $data['lat_lng']=$this->commonmodel->search_vehicle($imei_no);
    //     if(count($data['lat_lng']))
    //     {
    //         // echo "<pre>";
    //         // print_r($data);
    //         // exit;
    //       $data['veh_lat_long'] = $this->commonmodel->veh_lat_long();
    //       $this->load->view('masters/all_customer_vehicles',$data);
    //     }
    //     else
    //     {
    //       $data['lat_lng']=array();
    //       redirect(base_url() . 'portal/all_customer_vehicles', 'refresh');
    //     }
    // }
    public function distributor_customer()
    {
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'menu_distributor_track_vehicle')) {
            redirect(base_url(), 'refresh');
            exit();
        }
        $_SESSION['currentActivePage'] = 'Distributor_customer';
        $usertype=$_SESSION[user_type];
        $userid=$_SESSION[user_id];
        
        $data['deler_id']           = $this->commonmodel->getTotalDealerID($userid);
        $data['totalNoOfCustomers'] = $this->commonmodel->totalNoOfCustomersForDistributor($data['deler_id']);
        $data['listofCustomers']    = $this->commonmodel->distributor_vehicle_list($userid);
        //  echo "<pre>";
        //  print_r($data);
        //  exit;
        $this->load->view('masters/distributor_customer_vehicles',$data);
    }
    public function dealer_customer()
    {
        $user_type = $this->session->userdata('user_type');
       
        if (!check_permission($user_type, 'menu_dealer_track_vehicle'))
        {
            redirect(base_url(), 'refresh');
            exit();
        }
        
        $_SESSION['currentActivePage'] = 'Dealer_customer';
        $usertype=$_SESSION['user_type'];
        $userid=$_SESSION['user_id'];
        
        $data['totalNoOfCustomers'] = $this->commonmodel->totalNoOfCustomersDealer($userid);
        $data['listofCustomers'] = $this->commonmodel->dealer_vehicle_list($userid);
        // echo "<pre>";
        // print_r($data);
        // exit;
        $this->load->view('masters/dealer_customer_vehicles',$data);
    }
    
    public function check_console(){
        $params       = $this->input->get('imei');
        $data['imei'] = base64_decode($params);
        // echo "<pre>";print_r($data);exit();
        $user_type    = $this->session->userdata('user_type');
        $this->load->view('masters/check_console',$data);
    }
    
    
    public function check_valid_imei(){
        $imei         = $this->input->get('imei');
        $user_type    = $this->session->userdata('user_type');
        $user_id      = $this->session->userdata('user_id');
        $data         = array();
        $data['imei'] = -1;
        $data['valid']= -1;
        $imeiCount    = $this->commonmodel->getValidImei($imei);
        $validCount   = $this->commonmodel->getValidUser($user_type,$imei,$user_id);
        // echo "<pre>";print_r($validCount);exit();
        if($imeiCount != 0){
            $data['imei'] = 1;
        }
        
        if($validCount != 0){
            $data['valid'] = 1;
        }
        // echo "<pre>";print_r($data);exit();
       
        echo json_encode($data);exit;
    }

    // add_time_to_session ----------
    public function add_time_to_session() {
        $current_time = date('Y-m-d H:i:s'); 
        $imei         = $this->input->post('imei');
        $this->session->set_userdata('console_time', $current_time);
        $this->session->set_userdata('console_imei', $imei);
        $this->session->set_userdata('console_count', 1);
        // echo "<pre>";print_r($this->session->userdata('console_time'));exit;
        return true;
    }
    
    
    public function getRegisteredData(){
        //-----get data from session 
        $current_time  = $this->session->userdata('console_time');
        $imei          = $this->session->userdata('console_imei');
        $console_count = $this->session->userdata('console_count');
        
        $responseData = array();
        // echo "<pre>";print_r($result1);exit;
        $result1      = $this->commonmodel->getRegisteredData($current_time,$imei);
        // echo "<pre>";print_r($result1);exit;

        if($console_count == 1 && count($result1) == 0){
            $result2      = $this->commonmodel->getRegisteredFirstData($imei);
            $responseData = $result2;
        }
        else{
            $responseData = $result1;
        }
        // echo "<pre>";print_r($responseData);exit;
        //-----set data into session
        if(count($responseData)!= 0 ){
            $updated_time =  $responseData[0]['created_time'];
            $this->session->set_userdata('console_time', $updated_time);
        }
          $this->session->set_userdata('console_count', 0);
        
        
        echo json_encode($responseData);exit;  
    }
    

    public function imei_ota_update($imei)
    {
        $user_type = $this->session->userdata('user_type');

        /*if ((string)$user_type == '2' || (string)$user_type == '3') {
            redirect(base_url() . 'admin/assigned_serial_number_list', 'refresh');
            exit();
        }*/

        if (!isset($imei)) {
            redirect(base_url() . 'admin/assigned_serial_number_list', 'refresh');
        }

        $SerialInfo = $this->commonmodel->getIMEISerialInfo($imei);

        if (empty($SerialInfo)) {
            redirect(base_url() . 'admin/assigned_serial_number_list', 'refresh');
        }

        $data['imei'] = $imei;

        $this->load->view('masters/update_imei_ota', $data);
    }

    public function imei_ota_save()
    {
        $params = [
            'selectedVal' => isset($_GET['selectedVal']) ? $_GET['selectedVal'] : '',
            'imei' => isset($_GET['imei']) ? $_GET['imei'] : ''
        ];

        $returnResponse['validation'] = array();
        $returnResponse['error'] = "";
        $returnResponse['success'] = "";

        if (empty($params['imei']) || empty($params['selectedVal'])) {
            $returnResponse['error'] = "Required details missing. Please try again.";
        }

        $inserted = $this->commonmodel->updateOTAForIMEI($params);
        if ($inserted) {
            $returnResponse['success'] = true;
            $returnResponse['redirect'] = 'admin/assigned_serial_number_list';
            echo json_encode($returnResponse);
            exit();
        } else {
            $returnResponse['error'] = "Unable to save records.";
            echo json_encode($returnResponse);
            exit();
        }
    }
    
    public function add_service_email()
    {
        $returnResponse = array();
        $returnResponse['error'] = "";
        $returnResponse['success'] = "";

        $params  = $this->input->post();
        $mail    = $params['mail'];
        $addMail = $this->commonmodel->addMail($mail);
        if($addMail){
        $returnResponse['success'] = true;

        $returnResponse['redirect'] = 'admin/service_mail';

        $returnResponse['message'] = 'New Email address added successfully.';
        }
        else{
        $returnResponse['error'] = true;
        $returnResponse['message'] = 'Error saving  Email address.';
        }
        // echo "<pre>";print_r($returnResponse);exit;
        echo json_encode($returnResponse);
        exit();
    }
    
     public function delete_service_mail()
    {
        $mail_id = $this->input->post('id');
        // echo "<pre>";print_r($mail_id);exit;  
        $data    = $this->commonmodel->delete_service_mail($mail_id);
        echo json_encode($data);
        exit();		 
    } 


    public function getRTOByStateById()
    {

        $params = $this->input->post();

        $data['rto_list'] = $this->commonmodel->getRtoInfoByStateId((int)$params['id']);
        echo json_encode($data);
        exit();

    }
    

    public function getStateByCountryById()
    {

        $params = $this->input->post();

        $data['state_list'] = $this->commonmodel->getStateInfoByCountryId((int)$params['id']);
        echo json_encode($data);
        exit();

    }
    
    public function getLaunchStateByCountryById()
    {

        $params = $this->input->post();

        $data['state_list'] = $this->commonmodel->getLaunchStateInfoByCountryId((int)$params['id']);
        echo json_encode($data);
        exit();

    }
    
     public function service_mail()
    {
        //  echo "<pre>";print_r("data");exit;
        $limit = LIST_PAGE_LIMIT;
        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;
        if ($offset != 0) {
            $offset = ((int)$limit * (int)$offset) - (int)$limit;
        }

        $data['mail_list_count']  = $this->commonmodel->getNoOfServiceMailList();        
        $data['mail_list']  = $this->commonmodel->getserviceMailList($limit, $offset);
        // echo "<pre>";print_r($data);exit;
        
        $this->load->view('admin/service_mail', $data);
    }
    
    public function clearRecord()
    {
        $params = $this->input->post();
        $getUnregisteredRecord = $this->commonmodel->getUnregisteredDeviceData($params);
        $getRegisteredRecord = $this->commonmodel->getRegisteredDeviceData($params);
        $returnResponse['success'] = true;
        echo json_encode($returnResponse);
        exit();
    }
    
    public function getSerialDetailById()
    {
        $params = $this->input->post();
        $data = $this->commonmodel->getSerialNumberInfo($params['id']);
        echo json_encode($data);
        exit();
    }

    public function inter_change_device()
    {
        $VehicleID=base64_decode($_GET['q']);
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'cerificate_edit')) {
            redirect(base_url(), 'refresh');
            exit();
        }
        //Permission

        if (!isset($VehicleID) || (string)$VehicleID === '0') {
            redirect(base_url() . 'admin/dashboard', 'refresh');
        }

        $data['vehicleInfo'] = $this->commonmodel->getVehicleInfoData($VehicleID);
        // echo "<pre>"; print_r($data['vehicleInfo']);exit;
        if (empty($data['vehicleInfo'])) {
            redirect(base_url() . 'admin/dashboard', 'refresh');
        }

        $data['vehicleInfo']['veh_id'] = base64_encode($VehicleID);

        $data['pageTitle'] = 'Inter Change Device';
        // echo "<pre>"; print_r($data);exit;

        $user_id = $this->session->userdata('user_id');
        // echo "<pre>"; print_r($data['vehicleInfo']);exit;
        $data['serialList'] = $this->commonmodel->allSerialList($data['vehicleInfo']['dealer_id'], $data['vehicleInfo']['s_state_id']);
        // echo "<pre>"; print_r($data['serialList']);exit;
        // Load Content
        // echo "<pre>"; print_r($data['serialList'] );exit;
        $this->load->view('masters/device_inter_change', $data);
    }
    
    public function fetch_serial_list_by_companyId_dealerId()
    {
        $params = $this->input->post();

        $user_id = $params['dealer_id'];
        $stateId = $params['stateId'];
        $data['Serial_List'] = $this->commonmodel->allSerialNumberByCompany($params['veh_company_id'], $user_id, $stateId);

        echo json_encode($data);
        exit();
    }
    
    
    public function update_inter_change_device()
    {
        $params = $this->input->post();

        $returnResponse = array();

        $returnResponse['validation'] = array();

        $returnResponse['error'] = "";

        $returnResponse['success'] = "";

        // Validation

        $this->form_validation->set_rules('veh_serial_no', 'Please Select Serial Number', 'trim|required');
        
        // $this->form_validation->set_rules('fitment', 'Please complete your fitment entry and try it.', 'trim|required');

        // $this->form_validation->set_rules('reason_inter_change', 'Please choose reason', 'trim|required');

        if($params['fitment']=="N"){
            $obj['reason_inter_change'] = "Please complete your fitment entry and try it.";
            $returnResponse['validation'] = $obj;
            echo json_encode($returnResponse);
            exit();
        }
        if($params['reason_inter_change']==""){
            $obj['reason_inter_change'] = "Please choose reason.";
            $returnResponse['validation'] = $obj;
            echo json_encode($returnResponse);
            exit();
        }

        if ($this->form_validation->run() == FALSE) {
            $returnResponse['validation'] = $this->form_validation->error_array();
            echo json_encode($returnResponse);
            exit();
        }
        $existingSerialInfo = $this->commonmodel->getSerialNumberInfo($params['s_serial_id']);
        $params['old_imei_number'] = $existingSerialInfo['s_imei'];
        $params['old_customer_id'] = $existingSerialInfo['customer_id'];
        $params['old_distributor_id'] = $existingSerialInfo['s_distributor_id'];
        $params['old_dealer_id'] = $existingSerialInfo['s_dealer_id'];
        $serialInfo = $this->commonmodel->getSerialNumberInfo($params['veh_serial_no']);
        $params['new_customer_id'] = $params['old_customer_id'];
        $params['new_distributor_id'] = $serialInfo['s_distributor_id'];
        $params['new_dealer_id'] = $serialInfo['s_dealer_id'];
        $params['new_serial_number'] = $serialInfo['s_serial_number'];
        $params['new_imei_number'] = $serialInfo['s_imei'];
        $params['device_changed_date'] = date('Y-m-d H:i:s');
        $params['device_changed_by'] = $this->session->userdata('user_id');
        $params['veh_id']=base64_decode($params['veh_id']);
        
        if($params['reason_inter_change']=="1"){
            $params['reason'] = "Accidentally Added";
            $params['status'] = 0; 
            $params['log_status'] = 7; 
        }else if($params['reason_inter_change']=="2"){
            $params['reason'] = "Faulty Device";
            $params['status'] = 1; 
            $params['log_status'] = 9;
        }

        // $info = $this->adminmodel->update_inter_change_device($params);
        // echo "<pre>";print_r($params);exit;
        // $response = $info['insert_id'];
        $oldSerialNumberResult = $this->adminmodel->updateOldSerial($params);
        $newSerialNumberResult = $this->adminmodel->updateNewSerial($params);
        $vehicleInfoResult = $this->adminmodel->updateVehicleInfo($params);
        $invoicesCustomerResult = $this->adminmodel->updateInvoicesCustomer($params);
        $oldDeviceLogResult = $this->adminmodel->insertOldDeviceLogs($params);
        $newDeviceLogResult = $this->adminmodel->insertNewDeviceLogs($params);
        $newDeviceFitmentResult = $this->adminmodel->createNewFitment($params);
        $oldDeviceFitmentResult = $this->adminmodel->deleteOldFitment($params);
        
        /* if (empty($newDeviceLogResult)) {

            $returnResponse['error'] = "Please Enter valid Details.";

            echo json_encode($returnResponse);
            exit();

        } */
        /* $veh_owner_id = $info['veh_owner_id']; */
        
        $this->adminmodel->update_tracking_entry($params['veh_id'], $params['veh_owner_id'], $params);

        $responseData=array();
        $responseData['result1']=$oldSerialNumberResult;
        $responseData['result2']=$newSerialNumberResult;
        $responseData['result3']=$vehicleInfoResult;
        $responseData['result4']=$invoicesCustomerResult;
        $responseData['result5']=$oldDeviceLogResult;
        $responseData['result6']=$newDeviceLogResult;
        $responseData['result7']=$newDeviceFitmentResult;
        $responseData['result8']=$oldDeviceFitmentResult;
        $returnResponse['success'] = "true";
        $returnResponse['responseData'] = $responseData;

        // echo json_encode($returnResponse);
        // exit();
        // $returnResponse['success'] = true;

        $user_type = $this->session->userdata('user_type');

        if ((string)$user_type === '0' || (string)$user_type === '2') {

            $returnResponse['redirect'] = 'admin/entry_list';

        } elseif ((string)$user_type === '1') {

            $returnResponse['redirect'] = 'dealer/entry_list';

        }
        echo json_encode($returnResponse);
        exit();
    }
    
    
    
    public function expiring_list()
    {
        
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'menu_cerificate_list')) {
            redirect(base_url(), 'refresh');
            exit();
        }
    
        $user_id = $this->session->userdata('user_id');
    
        $limit = LIST_PAGE_LIMIT;
        // echo "<pre>";print_r("called..".$user_id);exit();
        $dealer_id = isset($_GET['dealer_id']) ? $_GET['dealer_id'] : "";
        $distributor_id = isset($_GET['distributor_id']) ? $_GET['distributor_id'] : "";
    
        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;
        if ($offset != 0) {
    
            $offset = ((int)$limit * (int)$offset) - (int)$limit;
    
        }
    
    
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $country = isset($_GET['s_country_id']) ? $_GET['s_country_id'] : 0;
        $state = isset($_GET['s_state_id']) ? $_GET['s_state_id'] : 0;
        // $days = isset($_GET['days'] && $_GET['days'] !=="") ? $_GET['days'] : 0;
        if (isset($_GET['days']) && $_GET['days'] !=="") {
            # code...
            $days = $_GET['days'];
        } else {
            $days = 0;
            # code...
        }
        
        // echo "<pre>";print_r("called..".$days);exit();
        $params = $this->input->post();
    
        if ($user_type == 0 || $user_type == 4 )  {
            $data['totalNoOfVehicles'] = $this->commonmodel->totalExpiresForAdmin($user_id,$days);
            $data['listofvehicles'] = $this->commonmodel->listofvehicleExpiresForAdmin($limit, $offset, $search, $dealer_id,$distributor_id,$days);
            
            if(isset($_GET['distributor_id'])!="" ){
                $data['dealer_list'] = $this->commonmodel->fetch_dealers(['user_type' => 1], $distributor_id);
            }
            $data['distributor_list'] = $this->commonmodel->fetch_list_of_distributors(['user_type' => 2], 0);
        }
        else{
            $data['totalNoOfVehicles'] = $this->commonmodel->totalExpires($user_id );
            $data['listofvehicles'] = $this->commonmodel->listofExpires($limit, $offset, $search, $user_id);
            // $data['dealer_list'] = $this->commonmodel->fetch_dealers(['user_type' => 1], $distributor_id);
            // $data['distributor_list'] = $this->commonmodel->fetch_list_of_distributors(['user_type' => 2], 0);
            // echo "<pre>";print_r($data);exit();
        }
        // echo "<pre>";print_r($data);exit;
        //$data['customer_list']=$this->commonmodel->allCustomerList(); $this->db->where('user_type', 2);
        // echo "<pre>";print_r($data);exit();
        // $data['countryList'] = $this->commonmodel->allCountryList();
        $data['stateList'] = $this->commonmodel->activeStateList();
        // if (isset($_GET['s_country_id']) != "") {
        //     $data['stateList'] = $this->commonmodel->getStateInfoByCountryId($country);
        // }
        // else{
        //     $data['stateList'] = '';
        // }

        $_SESSION['currentActivePage'] = 'Cerificate_LIST';
    
        $this->load->view('masters/expiring_list', $data);
    
    }
    
    public function expired_list()
    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'menu_cerificate_list')) {
            redirect(base_url(), 'refresh');
            exit();
        }
    
        $user_id = $this->session->userdata('user_id');
    
        $limit = LIST_PAGE_LIMIT;
        // echo "<pre>";print_r("called..".$user_id);exit();
        $dealer_id = isset($_GET['dealer_id']) ? $_GET['dealer_id'] : "";
        $distributor_id = isset($_GET['distributor_id']) ? $_GET['distributor_id'] : "";
    
        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;
        if ($offset != 0) {
    
            $offset = ((int)$limit * (int)$offset) - (int)$limit;
    
        }
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $country = isset($_GET['s_country_id']) ? $_GET['s_country_id'] : 0;
        $state = isset($_GET['s_state_id']) ? $_GET['s_state_id'] : 0;
        // echo "<pre>";print_r("called..".$search);exit();
        
        if ($user_type == '0' || $user_type == '4') {
            $data['totalNoOfVehicles'] = $this->commonmodel->totalExpiredForAdmin($user_id );
            $data['listofvehicles'] = $this->commonmodel->listofvehicleExpiredForAdmin($limit, $offset, $search, $dealer_id,$distributor_id);
          if(isset($_GET['distributor_id'])!="" ){
            $data['dealer_list'] = $this->commonmodel->fetch_dealers(['user_type' => 1], $distributor_id);
          }
            $data['distributor_list'] = $this->commonmodel->fetch_list_of_distributors(['user_type' => 2], 0);
        }
    
        else{
            $data['totalNoOfVehicles'] = $this->commonmodel->totalExpiredVehicle($user_id );
            $data['listofvehicles'] = $this->commonmodel->listofExpiredVehicle($limit, $offset, $search, $user_id);
            $data['dealer_list'] = "";
            $data['distributor_list'] = "";
        }
        // $data['countryList'] = $this->commonmodel->allCountryList();
        $data['stateList'] = $this->commonmodel->activeStateList();
        // if (isset($_GET['s_country_id']) != "") {
        //     $data['stateList'] = $this->commonmodel->getStateInfoByCountryId($country);
        // }
        // else{
        //     $data['stateList'] = '';
        // }
        //$data['customer_list']=$this->commonmodel->allCustomerList(); $this->db->where('user_type', 2);
        // echo "<pre>";print_r($data);exit();
    
        $_SESSION['currentActivePage'] = 'Cerificate_LIST';
    
        $this->load->view('masters/expired_list', $data);
    
    }
    
    public function fetch_dealer_list_by_distributor()
    {
        $params = $this->input->post();
        $data['dealer_list'] = $this->commonmodel->alldealers($params['distributor_id']);
        // echo "<pre>";print_r($data);exit();

        echo json_encode($data);
        exit();
    }
    
    public function stockReturnToAdmin()
    {
        $params = $this->input->post();
        // echo "<pre>";print_r($params);exit;
        $returnResponse = array();

        $returnResponse['validation'] = array();

        $returnResponse['error'] = "";

        $returnResponse['success'] = "";

        // Validation

        $this->form_validation->set_rules('imei', 'Please Select Imei Number', 'trim|required');
        
        if ($this->form_validation->run() == FALSE) {
            $returnResponse['validation'] = $this->form_validation->error_array();
            echo json_encode($returnResponse);
            exit();
        }
        $params['s_created_by'] = $this->session->userdata('user_id');
        $result = $this->adminmodel->updateStockReturnToAdmin($params);
        
        if($result==1){
            $returnResponse['success'] = "true";
        }
        
        $user_type = $this->session->userdata('user_type');
        echo json_encode($returnResponse);
        exit();
    }
    
    public function stockReturnToDistributor()
    {
        $params = $this->input->post();
        // echo "<pre>";print_r($params);exit;
        $returnResponse = array();

        $returnResponse['validation'] = array();

        $returnResponse['error'] = "";

        $returnResponse['success'] = "";

        // Validation

        $this->form_validation->set_rules('imei', 'Please Select Imei Number', 'trim|required');
        

        if ($this->form_validation->run() == FALSE) {
            $returnResponse['validation'] = $this->form_validation->error_array();
            echo json_encode($returnResponse);
            exit();
        }
        
        $params['s_created_by'] = $this->session->userdata('user_id');
        $result = $this->adminmodel->updateStockReturnToDistributor($params);
        
        if($result==1){
            $returnResponse['success'] = "true";
        }
        

        $user_type = $this->session->userdata('user_type');

        echo json_encode($returnResponse);
        exit();
    }
    
    
    public function passwordUpdateData()
    {
        $result = $this->adminmodel->userListAll();
        foreach ($result as $value) {
            $result = $this->adminmodel->userPasswordUpdated($value->user_id, md5($value->user_phone));
        }
        echo "<pre>";print_r("sdd");exit;
    }
    
     public function updateGpsLiveTrackingData(){
        //  echo "<pre>";print_r("completted");exit;
        $limit = 1000 ;
        $offset = 0 * $limit;
        
        $this->db->select('s_imei, s_dealer_id, s_distributor_id');
        $this->db->order_by('assign_to_customer_on', 'desc');
        $this->db->limit($limit, $offset);
        $this->db->from($this->db->table_serial_no);
        $result = $this->db->get();
        $table1_data = $result->result_array();
        // echo "<pre>";print_r($this->db->last_query());exit;
        // echo "<pre>";print_r($table1_data);exit;
        foreach ($table1_data as $row) {
			$otherdb = $this->load->database('tracking', TRUE); 
            $data = array(
                'dealer_id' => $row['s_dealer_id'],
                'distributor_id' => $row['s_distributor_id']
            );
			$otherdb->where('imei', $row['s_imei']);
			$otherdb->where('dealer_id', 0);
			$otherdb->where('distributor_id', 0);
			$otherdb->update($otherdb->table_trackings,$data);
        // echo "<pre>";print_r($otherdb->last_query());exit;
        }
        // echo "<pre>";print_r("completted");exit;
    }

    public function apiNew()
    {
        $gpsTrackingData = $this->commonmodel->getGPSLiveTrackingData();
        $returnResponse['success'] = true;
        echo json_encode($returnResponse);
        exit();
    }

    public function scan()
    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        
        if ((string)$user_type != '0') {
            redirect(base_url(), 'refresh');
            exit();
        }
        $data['countryList'] = $this->commonmodel->allCountryList();
        // print_r($data); exit;
        $this->load->view('masters/scan', $data);
    }

    public function scan_bulk_upload()
    {
        //Permission
        $user_type = $this->session->userdata('user_type');
            // print
        if ((string)$user_type != '0' && (string)$user_type != '4') {
            // print_r($user_type);exit;
            redirect(base_url(), 'refresh');
            exit();
        }
        $data['countryList'] = $this->commonmodel->allCountryList();
        // print_r($data); exit;
        $this->load->view('masters/scan_bulk_upload', $data);
    }

    public function verifyscanner()
    {
        $params = $this->input->post();
        $returnResponse = array();
        $returnResponse['validation'] = array();
        $returnResponse['error'] = "";
        $returnResponse['success'] = "";
        // Validation
        $this->form_validation->set_rules('serial_number', 'Serial Number', 'required');

        // Validation verify
        if ($this->form_validation->run() == FALSE) {
            $returnResponse['validation'] = $this->form_validation->error_array();
            echo json_encode($returnResponse);
            exit();
        }

        $code_arr = explode(";", $_POST['serial_number']);
        $err_msg = '';
        // echo "<pre>";print_r(count($code_arr));exit;
        if (count($code_arr) != 2) {
            $returnResponse['error'] = "Please enter valid code.";
            echo json_encode($returnResponse);
            exit();
        }
        if (strlen($code_arr[0]) != 15) {
            $err_msg = 'IMEI should be 15 characters. ';
        }

        if (strlen($code_arr[1]) != 20) {
            $err_msg = ($err_msg == '') ? 'ICCID should be 20 characters. ' : $err_msg . ' ICCID should be 20 characters.';
        }

        // if (strlen($code_arr[2]) != 19) {
        //     $err_msg = ($err_msg == '') ? 'Serial Number should have 19 characters. ' : $err_msg . ' Serial Number should be 19 characters.';
        // }

        if ($err_msg != '') {
            $returnResponse['error'] = trim($err_msg);
            echo json_encode($returnResponse);
            exit();
        }

        //Pass params to Model
       
        $response = $this->adminmodel->checkCodeExists($code_arr);
        // echo "<pre>";print_r($response);exit;
        if (!empty($response)) {
            $returnResponse['error'] = "IMEI number is already exist. Please try with new string.";
            echo json_encode($returnResponse);
            exit();
        } else {
           
            $currentSerialNumber   = $this->adminmodel->getCurrentSerialNumber();
            $newSerialNumber       = $currentSerialNumber->serial_number + 1;
            $formattedSerialNumber = sprintf('%07d', $newSerialNumber);
            $serialNumber          = $currentSerialNumber->static_code.$formattedSerialNumber;
            // echo "<pre>";print_r($serialNumber);exit;
            $inserted = $this->adminmodel->saveSerialNumber($code_arr,$serialNumber,$params);
            if ($inserted) {
                
                $this->adminmodel->updateSerialNumber($newSerialNumber);

                $returnResponse['success'] = true;
                $returnResponse['redirect'] = 'admin/scan';
                echo json_encode($returnResponse);
                exit();
            } else {
                $returnResponse['error'] = "Unable to save records.";
                echo json_encode($returnResponse);
                exit();
            }
        }
    }
}