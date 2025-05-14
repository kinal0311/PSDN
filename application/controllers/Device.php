<?php

defined('BASEPATH') OR exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, OPTIONS");


class Device extends CI_Controller
{
	// Load Constructur
    public function __construct()
    {
        parent::__construct();
        $this->load->model('adminmodel');
        $this->load->model('commonmodel');
        $this->load->model('devicemodel');
        $this->load->library("pagination");
        $this->load->library('session');
    }

    // Initialize Function
    public function index()
    {
        $user_type = $this->session->userdata('user_type');
       
        if (isset($user_type)) {
            if ((string)$user_type !== '0') {
                redirect(base_url(), 'refresh');
                exit();
            } else {
                $this->scan();
            }            
        }

        $this->load->view('device/login');
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

        if ($response['user_type'] !== '5') {
            $returnResponse['error'] = "You are not allowed to access.";
            echo json_encode($returnResponse);
            exit();
        }

        if (isset($response['user_password'])) {
            unset($response['user_password']);
        }

        // Set Session
        session_start();
        $this->session->set_userdata($response);
        $returnResponse['success'] = true;
        $returnResponse['redirect'] = 'device/serial_numbers';
        echo json_encode($returnResponse);
        exit();
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect(base_url() . 'device/', 'refresh');
        exit();
    }

    public function scan()
    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if ($user_type != '5') {
            redirect(base_url(), 'refresh');
            exit();
        }

        $this->load->view('device/scan');
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
       
        $response = $this->devicemodel->checkCodeExists($code_arr);
        // echo "<pre>";print_r($response);exit;
        if (!empty($response)) {
            $returnResponse['error'] = "IMEI number is already exist. Please try with new string.";
            echo json_encode($returnResponse);
            exit();
        } else {
           
            $currentSerialNumber   = $this->devicemodel->getCurrentSerialNumber();
            $newSerialNumber       = $currentSerialNumber->serial_number + 1;
            $formattedSerialNumber = sprintf('%07d', $newSerialNumber);
            $serialNumber          = $currentSerialNumber->static_code.$formattedSerialNumber;
            // echo "<pre>";print_r($serialNumber);exit;
            $inserted = $this->devicemodel->saveSerialNumber($code_arr,$serialNumber);
            if ($inserted) {
                
                $this->devicemodel->updateSerialNumber($newSerialNumber);

                $returnResponse['success'] = true;
                $returnResponse['redirect'] = 'device/scan';
                echo json_encode($returnResponse);
                exit();
            } else {
                $returnResponse['error'] = "Unable to save records.";
                echo json_encode($returnResponse);
                exit();
            }
        }
    }


    public function serial_numbers()
    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if ($user_type != '5') {
            redirect(base_url(), 'refresh');
            exit();
        }

        $_SESSION['currentActivePage'] = 'Serial_No_List';
        $usertype=$_SESSION['user_type'];
        $userid=$_SESSION['user_id'];
        $limit = LIST_PAGE_LIMIT;
        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;

        if ($offset != 0) {
            $offset = ((int)$limit * (int)$offset) - (int)$limit;
        }

        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $country = isset($_GET['s_country_id']) ? $_GET['s_country_id'] : 0;
        $state = isset($_GET['s_state_id']) ? $_GET['s_state_id'] : 0;

        $data['totalNoOfSerialNos'] = $this->devicemodel->totalNoOfSerialNos();
        $data['usertype']=$usertype;
        $data['listofSerialNos'] = $this->devicemodel->listofSerialNos($limit, $offset, $search);
        $data['stateList'] = $this->commonmodel->activeStateList();
        // $data['countryList'] = $this->commonmodel->allCountryList();
        // if (isset($_GET['s_country_id']) != "") {
        //     $data['stateList'] = $this->commonmodel->getStateInfoByCountryId($country);
        // }
        // else{
        //     $data['stateList'] = '';
        // }
        $this->load->view('device/serial_no_list', $data);
    }
    
    

    public function tracklivedata()
    {
         $this->load->view('device/trackingdata');

    }
    
     public function search() {
         
        $imei = $this->input->post('imei');
       
        // Perform the search based on IMEI
        $results = $this->devicemodel->searchByIMEI($imei);
//  echo "<pre>";print_r($results);exit;
        // Load the view with search results
        $data['results'] = $results;
        $this->load->view('device/trackingdata', $data);
    }
    
    public function unregistered_data()
    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if ($user_type != '5') {
            redirect(base_url(), 'refresh');
            exit();
        }

        $_SESSION['currentActivePage'] = 'Unregistered_Data_List';
        $usertype=$_SESSION['user_type'];
        $userid=$_SESSION['user_id'];
        $limit = LIST_PAGE_LIMIT;
        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;

        if ($offset != 0) {
            $offset = ((int)$limit * (int)$offset) - (int)$limit;
        }

        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $country = isset($_GET['s_country_id']) ? $_GET['s_country_id'] : 0;
        $state = isset($_GET['s_state_id']) ? $_GET['s_state_id'] : 0;
        $data['stateList'] = $this->commonmodel->activeStateList();
        // $data['countryList'] = $this->commonmodel->allCountryList();
        // if (isset($_GET['s_country_id']) != "") {
        //     $data['stateList'] = $this->commonmodel->getLaunchStateInfoByCountryId($country);
        // }
        // else{
        //     $data['stateList'] = '';
        // }

        if($search != ''){
            // $data['totalNoOfUnregisteredDatas'] = $this->devicemodel->totalNoOfUnregisteredDatas($search, $state);
            
            $data['usertype']=$usertype;
            $data['search']  =$search;

            $data['listOfUnregisteredDatas'] = $this->devicemodel->listOfUnregisteredDatas($limit, $offset, $search, $state);
        // echo "<pre>";print_r($data);exit;

        }
        else{
            $data['totalNoOfUnregisteredDatas'] = 0;
            // echo "<pre>";print_r($data);exit;
    
            $data['usertype']=$usertype;
            $data['search']  =$search;
            $data['listOfUnregisteredDatas'] = [];
        }
        $this->load->view('device/unregistered_device_data', $data);
    }

    public function registered_data()
    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if ($user_type != '5') {
            redirect(base_url(), 'refresh');
            exit();
        }
        

        $_SESSION['currentActivePage'] = 'registered_Data_List';
        $usertype=$_SESSION['user_type'];
        $userid=$_SESSION['user_id'];
        $limit = 10;
        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;
        if ($offset != 0) {
            $offset = ((int)$limit * (int)$offset) - (int)$limit;
        }

        $search = isset($_GET['search']) ? $_GET['search'] : '';
        
        if($search != ''){
            $data['totalNoOfregisteredDatas'] = $this->devicemodel->totalNoOfregisteredDatas($search);
            // echo "<pre>";print_r($data);exit;
    
            $data['usertype']=$usertype;
            $data['search']  =$search;
            $data['listOfregisteredDatas'] = $this->devicemodel->listOfregisteredDatas($limit, $offset, $search);
        }
        else{
            $data['totalNoOfregisteredDatas'] = 0;
            // echo "<pre>";print_r($data);exit;
    
            $data['usertype']=$usertype;
            $data['search']  =$search;
            $data['listOfregisteredDatas'] = [];
        }
        
        // echo "<pre>";print_r($data);exit;
        $this->load->view('device/registered_device_data', $data);
    }
    
     public function ota_status()
    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if ($user_type != '5') {
            redirect(base_url(), 'refresh');
            exit();
        }

        $_SESSION['currentActivePage'] = 'ota_status_data';
        $usertype=$_SESSION['user_type'];
        $userid=$_SESSION['user_id'];
        $limit = 10;
        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;

        if ($offset != 0) {
            $offset = ((int)$limit * (int)$offset) - (int)$limit;
        }

        $search = isset($_GET['search']) ? $_GET['search'] : '';

        $data['totalNoOfotaStatusDatas'] = $this->devicemodel->totalNoOfOtaStatusDatas($search);
        $data['usertype']=$usertype;
        $data['listOfOtaStatusDatas'] = $this->devicemodel->listOfOtaStatusDatas($limit, $offset, $search);
        // echo "<pre>";print_r($data);exit;
        $this->load->view('device/ota_status_data', $data);
    }
    
    public function ota_outbox()
    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if ($user_type != '5') {
            redirect(base_url(), 'refresh');
            exit();
        }

        $_SESSION['currentActivePage'] = 'ota_status_data';
        $usertype=$_SESSION['user_type'];
        $userid=$_SESSION['user_id'];
        $limit = 10;
        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;

        if ($offset != 0) {
            $offset = ((int)$limit * (int)$offset) - (int)$limit;
        }

        $search = isset($_GET['search']) ? $_GET['search'] : '';

        $data['totalNoOfotaStatusDatas'] = $this->devicemodel->totalNoOfOtaOutboxDatas($search);
        $data['usertype']=$usertype;
        $data['listOfOtaStatusDatas'] = $this->devicemodel->listOfOtaOutboxDatas($limit, $offset, $search);
        
        // $uniqueIds = array_unique(array_column($data['listOfOtaStatusDatas'], 'LastUpdatedBy'));

        // $names = $this->devicemodel->getNamesByIds($uniqueIds);
           

        // $idToNameMapping = [];
        // foreach ($names as $nameRow) {
        //     $idToNameMapping[$nameRow['user_id']] = $nameRow['user_name']; 
        // }
        
        //   foreach ($data['listOfOtaStatusDatas'] as &$item) {
        //         $lastUpdatedById = $item['LastUpdatedBy'];
        //         echo "<pre>";print_r($idToNameMapping[".'$lastUpdatedById'."]);exit;
        //         if (isset($idToNameMapping[".'$lastUpdatedById'."])) {
        //             // echo "<pre>";print_r( $item['LastUpdatedBy'] );exit;
        //             $item['LastUpdatedBy'] = $idToNameMapping[$lastUpdatedById];
        //         }
        //     }
            
        // echo "<pre>";print_r($data['listOfOtaStatusDatas']);exit;
        $this->load->view('device/ota_outbox', $data);
    }
    
    public function getUserInfo(){
        $userId    = $this->input->post('userId');         
        $user_info = $this->devicemodel->getUserInfo($userId);
        // echo "<pre>";print_r($user_info);exit;
        echo json_encode($user_info);
        exit();
        
    }
    
    public function print_old()
    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if ($user_type != '5') {
            redirect(base_url(), 'refresh');
            exit();
        }

        $_SESSION['currentActivePage'] = 'Print';
        $usertype=$_SESSION['user_type'];
        $limit = LIST_PAGE_LIMIT;
        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;

        if ($offset != 0) {
            $offset = ((int)$limit * (int)$offset) - (int)$limit;
        }

        $data['totalNoOfSerialNos'] = $this->devicemodel->totalNoOfSerialNos();
        $data['usertype']=$usertype;
        $data['listofSerialNos'] = $this->devicemodel->listofSerialNos($limit, $offset);

        $allSerialNos = $this->devicemodel->listofSerialNos();
        $printHtml = '';

        if(count($allSerialNos) > 0)
        {
            $printHtml = '<table id="mytable" class="table table-bordred table-striped" style="width: 100% !important;"><tbody>';
            $sno=1;
            if(isset($_GET['offset']) && (int)$_GET['offset']>0)
            {
                $sno=(((int)$_GET['offset']-1)*LIST_PAGE_LIMIT)+1;
            }
            foreach($allSerialNos as $key => $value)
            {
                $printHtml .= ($sno % 2 == 0) ? '' : '<tr>';
                $CI =& get_instance();
                $CI->load->library('ciqrcode');

                $params['data'] = $value['s_imei'] . ';' . $value['s_iccid'] . ';' . $value['s_serial_number'];
                $params['level'] = 'H';
                $params['size'] = 4;
                $params['savename'] = FCPATH . 'qrcodes/' . $value['s_imei'] . '.png';
                $CI->ciqrcode->generate($params);
                    
                $printHtml .= '<td>
                <table><tr><td>IMEI: ' . $value['s_imei'] . '</td><td rowspan="4"><img src="'.base_url() . 'qrcodes/' . $value['s_imei'] . '.png" /></td></tr><tr><td>ICCID: ' . $value['s_iccid'] . '</td></tr><tr><td>S/N: ' . $value['s_serial_number'] . '</td></tr><tr><td>MADE IN INDIA</td></tr><tr><td>www.psdn.tech</td></tr>
                  </table>
               </td>';
                $printHtml .= ($sno % 2 == 0 || count($allSerialNos) == $sno) ? '</tr>' : ''; 
                $sno++;
            }
            $printHtml .= '</table>';
        }

        $data['printHtml'] = $printHtml;

        $this->load->view('device/print', $data);
    }

    public function print()
    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if ($user_type != '5') {
            redirect(base_url(), 'refresh');
            exit();
        }

        $_SESSION['currentActivePage'] = 'Print';
        $usertype = $_SESSION['user_type'];
        $limit = LIST_PAGE_LIMIT;
        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;

        if ($offset != 0) {
            $offset = ((int)$limit * (int)$offset) - (int)$limit;
        }
        $country = isset($_GET['s_country_id']) ? $_GET['s_country_id'] : 0;
        $state = isset($_GET['s_state_id']) ? $_GET['s_state_id'] : 0;
        $data['totalNoOfSerialNos'] = $this->devicemodel->totalNoOfSerialNos();
        $data['usertype'] = $usertype;
        $data['listofSerialNos'] = $this->devicemodel->listofSerialNos($limit, $offset);
        $allSerialNos = $data['listofSerialNos'];
        // $allSerialNos = $this->devicemodel->listofSerialNos($limit, $offset);
        // $data['countryList'] = $this->commonmodel->allCountryList();
        // if (isset($_GET['s_country_id']) != "") {
        //     $data['stateList'] = $this->commonmodel->getStateInfoByCountryId($country);
        // }
        // else{
        //     $data['stateList'] = '';
        // }
        $data['stateList'] = $this->commonmodel->activeStateList();
        // $allSerialNos = $this->devicemodel->listofSerialNos();
        
        
        $printHtml = '';
        // echo "<pre>";print_r(count($allSerialNos));exit;
        if(count($allSerialNos) > 0)
        {
            $printHtml = '<table id="mytable" class="table table-bordred table-striped" style="width: 100% !important;"><tbody>';
            $sno=1;
            if(isset($_GET['offset']) && (int)$_GET['offset']>0)
            {
                $sno=(((int)$_GET['offset']-1)*LIST_PAGE_LIMIT)+1;
            }
            // echo "<pre>";print_r($allSerialNos);exit;
            foreach($allSerialNos as $key => $value)
            {    
                $printHtml .= ($sno % 1 == 0) ? '' : '<tr>';
                $CI =& get_instance();
                $CI->load->library('ciqrcode');
                // echo "<pre>";print_r($value['s_imei']);exit;
                $params['data'] = $value['s_imei'] . ';' . $value['s_iccid'] . ';' . $value['s_serial_number'];
                /* $params['level'] = 'H';
                $params['size'] = 4; */
                $params['savename'] = FCPATH . 'public/qrcodes/' . $value['s_imei'] . '.png';

                // "imagePath =>public/temp_upload/1697088606.png imageName =>1697088606.png path =>public/upload/vehicle"
                $CI->ciqrcode->generate($params);
                $imagePath = 'public/qrcodes/' . $value['s_imei'] . '.png';
                $imageName = $value['s_imei'] . '.png';
                $path = "public/qrcodes";
                
                $awsImageUpload = $this->commonmodel->awsImageUpload($imagePath, $imageName, $path);
                
                if (isset($imagePath) && strlen($imagePath) > 0) {
                    unlink($imagePath);
                }
                $value['s_iccid'] = $value['s_iccid']?$value['s_iccid']:"-";
                $value['s_serial_number'] = $value['s_serial_number']?$value['s_serial_number']:"-";
                $value['s_imei'] = $value['s_imei']?$value['s_imei']:"-";
                
                
                $printHtml .= '<td>
                <table style="font-family:Segoe UI,sans-serif"><tr style="height: 2px;"><td><p style="font-size: 13px !important; margin: 0px !important;"><b>IMEI: ' . $value['s_imei'] . '</b></p><p style="font-size: 12.5px !important; margin: 0px !important;"><b>ICCID: ' . $value['s_iccid'] . '</b></p><p style="font-size: 13px !important; margin: 0px !important;"><b>S/N: ' . $value['s_serial_number'] . '</b></p><p style="font-size: 13px !important; margin: 0px !important;"><b>MADE IN INDIA</b></p><p style="font-size: 15px !important; margin: 0px !important;"><b>www.psdn.live</b></p></td><td rowspan="4"><img width="100" src="'. AWS_S3_BUCKET_URL . 'public/qrcodes/' . $value['s_imei'] . '.png" /></td></tr>
                </table>
               </td>
               <td>
               <table style="font-family:Segoe UI,sans-serif"><tr style="height: 2px;"><td><p style="font-size: 13px !important; margin: 0px !important;"><b>IMEI: ' . $value['s_imei'] . '</b></p><p style="font-size: 12.5px !important; margin: 0px !important;"><b>ICCID: ' . $value['s_iccid'] . '</b></p><p style="font-size: 13px !important; margin: 0px !important;"><b>S/N: ' . $value['s_serial_number'] . '</b></p><p style="font-size: 13px !important; margin: 0px !important;"><b>MADE IN INDIA</b></p><p style="font-size: 15px !important; margin: 0px !important;"><b>www.psdn.live</b></p></td><td rowspan="4"><img width="100" src="'. AWS_S3_BUCKET_URL . 'public/qrcodes/' . $value['s_imei'] . '.png" /></td></tr>
               </table>
               </td>';
               /*  $printHtml .= '<td>
                <table><tr style="height: 2px;"><td><p style="font-size: 12px !important; margin: 0px !important;">IMEI: ' . $value['s_imei'] . '</p></td><td rowspan="4"><img width="100" src="'.base_url() . 'qrcodes/' . $value['s_imei'] . '.png" /></td></tr><tr style="height: 2px;"><td><p style="font-size: 12px !important; margin: 0px !important;">ICCID: ' . $value['s_iccid'] . '</p></td></tr><tr style="height: 2px;"><td><p style="font-size: 12px !important; margin: 0px !important;">S/N: ' . $value['s_serial_number'] . '</p></td></tr><tr><td><p style="font-size: 12px !important; margin: 0px !important;">MADE IN INDIA</p></td></tr><tr><td><p style="font-size: 12px !important; margin: 0px !important;">www.psdn.tech</p></td></tr>
                  </table>
               </td>'; */
                /* $printHtml .= '<td>
                <table><tr style="height: 2px;"><td><p style="font-size: 12px !important; margin: 0px !important;">IMEI: ' . $value['s_imei'] . '</p></td><td rowspan="4"><img width="100" src="'.base_url() . 'qrcodes/' . $value['s_imei'] . '.png" /></td></tr><tr style="height: 2px;"><td><p style="font-size: 12px !important; margin: 0px !important;">ICCID: ' . $value['s_iccid'] . '</p></td></tr><tr style="height: 2px;"><td><p style="font-size: 12px !important; margin: 0px !important;">S/N: ' . $value['s_serial_number'] . '</p></td></tr><tr><td><p style="font-size: 12px !important; margin: 0px !important;">MADE IN INDIA</p></td></tr><tr><td><p style="font-size: 12px !important; margin: 0px !important;">www.psdn.tech</p></td></tr>
                  </table>
               </td>'; */
                  
                $printHtml .= ($sno % 1 == 0 || count($allSerialNos) == $sno) ? '</tr>' : ''; 
                // echo "<pre>";print_r($value);
                $sno++;
            }
            // echo "<pre>";print_r($printHtml);exit;
            $printHtml .= '</table>';
        }
        // echo "<pre>";print_r($printHtml);
        $data['printHtml'] = $printHtml;

        $this->load->view('device/print', $data);
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

    public function import()
    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if ($user_type != '5') {
            redirect(base_url(), 'refresh');
            exit();
        }

        $_SESSION['currentActivePage'] = 'Import';
        $this->load->view('device/import', $data);
    }
    
    public function stock_list()
    {
         //Permission
        $user_type = $this->session->userdata('user_type');
        if ($user_type != '5') {
            redirect(base_url(), 'refresh');
            exit();
        }

        $_SESSION['currentActivePage'] = 'Serial_No_List';
        $usertype=$_SESSION['user_type'];
        $userid=$_SESSION['user_id'];
        $limit = LIST_PAGE_LIMIT;
        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;

        if ($offset != 0) {
            $offset = ((int)$limit * (int)$offset) - (int)$limit;
        }

        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $country = isset($_GET['s_country_id']) ? $_GET['s_country_id'] : 0;
        $state = isset($_GET['s_state_id']) ? $_GET['s_state_id'] : 0;

        $data['totalNoOfSerialNos'] = $this->devicemodel->totalNoOfSerialNos();
        $data['usertype']=$usertype;
        // $data['listofSerialNos'] = $this->devicemodel->listofSerialNos($limit, $offset, $search);
        // $data['countryList'] = $this->commonmodel->allCountryList();
        $data['listofSerialNos'] = $this->devicemodel->listofCompleteSerial($limit, $offset, $search);
        $data['stateList'] = $this->commonmodel->activeStateList();
        // if (isset($_GET['s_country_id']) != "") {
        //     $data['stateList'] = $this->commonmodel->getStateInfoByCountryId($country);
        // }
        // else{
        //     $data['stateList'] = '';
        // }

        $this->load->view('device/stock_list', $data);
    }

    
    public function stock_list_search()
    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if ($user_type != '5') {
            redirect(base_url(), 'refresh');
            exit();
        }
        
        $_SESSION['currentActivePage'] = 'Serial_No_List';
        
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

        $data['totalNoOfSerialNumbers'] = $this->commonmodel->totalNoOfUnassignedSerialNumbers();
        $data['listofSerialNumbers'] = $this->commonmodel->listofUnassignedSerialNumbers($limit, $offset, $search, $company_id);
        $data['company_list'] = $this->commonmodel->allCompanyList();

        if ($company_id) {
            $data['product_list'] = $this->commonmodel->companyProductList($company_id);
        }
        
        $this->load->view('device/stock_list', $data);
    }

    public function ajax_splitfiles()
    {
        $records = [];
        if (($handle = fopen($_FILES['import_file']['tmp_name'], "r")) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $records[] = $row;
            }
            fclose($handle);
        }

        if (!empty($records)) {
            // echo "<pre>";print_r($records);exit;
            echo json_encode(['status' => 1, 'msg' => 'Success', 'data' => $records]);
        } else {
            // echo "<pre>";print_r('2');exit;
            echo json_encode(['status' => 0, 'msg' => 'Please enter valid file.']);
        }
        exit;
    }

    public function ajax_verifydata()
    {
        $iccid = $this->input->get('iccid');
        $mobile1 = $this->input->get('mobile1');
        $mobile2 = $this->input->get('mobile2');
   
        // echo "<pre>";print_r($mobile1);exit;
     
        $bgclass = 'bg-success'; //bg-success, bg-warning, bg-danger
        $message = '';
        $error = false;

        if($iccid == '') {
            $message .= 'ICCID is empty. ';
            $bgclass = 'bg-danger';
            $error = true;
        }
        if($mobile1 == '') {
            $message .= 'Mobile 1 is empty. ';
            $bgclass = 'bg-danger';
            $error = true;
        }
        if($mobile2 == '') {
            $message .= 'Mobile 2 is empty. ';
            $bgclass = 'bg-danger';
            $error = true;
        }

        if (!$error) {
            if ($this->devicemodel->checkIccidExists($iccid)) {
                $status = $this->devicemodel->addMobileNumbersToIccid($iccid, $mobile1, $mobile2);
                // echo "<pre>";print_r($status);exit;
                if ($status) {
                    $bgclass = 'bg-success';
                    $message  = 'Succesfully Saved.';
                } else {
                    $bgclass = 'bg-danger';
                    $message  = 'Unable to save data.';
                }
            } else {
                $bgclass = 'bg-danger';
                $message = 'ICCID # ' . $iccid . ' is not available in the database.';
            }
        }

        echo json_encode([
            'msg' => '<span class="' . $bgclass . '">' . $message . '</span>'
        ]); exit;
    }
}