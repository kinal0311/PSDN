<?php

defined('BASEPATH') OR exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, OPTIONS");


class ApiController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('apimodel');
        
        $this->load->model('adminmodel');

        $this->load->model('commonmodel');

        $this->load->library("pagination");

        $this->load->library('session');
    }


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

            $user_id = $this->session->userdata('user_id');
            $password = $this->adminmodel->getPassword($user_id);
            $responseData = ($password['user_password']);
            $enterPass = md5($params['password']);
            if ($enterPass != $responseData) {
                // Password is incorrect
                $returnResponse['error'] = 1;
                $returnResponse['message'] = "Password incorrect!";
                echo json_encode($returnResponse);
                exit();
            } 

        // echo "<pre>";print_r($enterPass);exit;
        
        $vehicleInfo = $this->commonmodel->getVehicleInfo($params['veh_id']);
        // $serialInfo = $this->commonmodel->getSerialNumberInfo($vehicleInfo['veh_serial_no']);
        
        $result1 = $result2 = $result3 = $result4 = $result5 = false;
        if($vehicleInfo['s_imei']==""){
            $returnResponse['error'] = 1;
            $returnResponse['message'] = "Device information not exist.";
            echo json_encode($returnResponse);
            exit();
        }
        
        $result1 = $this->apimodel->deleteGpsLivetrackingData($vehicleInfo['s_imei']);
        if($result1==true){
            // device logs insert
            $serialInfo = $this->commonmodel->getSerialNumberInfo($vehicleInfo['veh_serial_no']);
            $vehicleInfo['s_distributor_id'] = $serialInfo['s_distributor_id'];
            $vehicleInfo['s_dealer_id'] = $serialInfo['s_dealer_id'];
            
            $result2 = $this->apimodel->create_device_log($vehicleInfo);
            // invoice customer delete
            $result3 = $this->apimodel->delete_invoices_customer($vehicleInfo);
            // serial number update
            $result4 = $this->apimodel->update_serial_number($vehicleInfo);
            // vehicle data delete
            $result5 = $this->apimodel->deleteVehicleInfo($vehicleInfo);
            // fitment entry delete
            $result6 = $this->apimodel->deleteFitment($vehicleInfo);

            $responseData=array();
            $responseData['result1']=$result1;
            $responseData['result2']=$result2;
            $responseData['result3']=$result3;
            $responseData['result4']=$result4;
            $responseData['result5']=$result5;
            $responseData['result6']=$result6;
            $returnResponse['success'] = "true";
            $returnResponse['responseData'] = $responseData;
    
            echo json_encode($returnResponse);
            exit();
        }else{
            $returnResponse['error'] = "failure";
            $returnResponse['message'] = "GPS Live Tracking Data's not found";
    
            echo json_encode($returnResponse);
            exit();
        }

    }
    
    

        // public function delete_entry_list()
        // {
        //     $params = $this->input->post();
            
        //     $user_type = $this->session->userdata('user_type');
    
        //     $returnResponse = array();
    
        //     $returnResponse['validation'] = array();
    
        //     $returnResponse['error'] = "";
    
        //     $returnResponse['success'] = "";
    
        //     if ((string)$user_type != '0') {
        //         $returnResponse['error'] = "Admin Can have permission only.";
        //         echo json_encode($returnResponse);
        //         exit();
        //     }
            
        //     // $user_id = $this->session->userdata('user_id');
        //     // $password = $this->adminmodel->getPassword($user_id);
        //     // $responseData = ($password['user_password']);
        //     // $enterPass = md5($params['password']);
        //     // if ($enterPass != $responseData) {
        //     //     // Password is incorrect
        //     //     $returnResponse['error'] = 1;
        //     //     $returnResponse['message'] = "Password incorrect!";
        //     //     echo json_encode($returnResponse);
        //     //     exit();
    
        //     // } 
            
        //     $vehicleInfo = $this->commonmodel->getVehicleInfo($params['veh_id']);
        //     // $serialInfo = $this->commonmodel->getSerialNumberInfo($vehicleInfo['veh_serial_no']);
            
        //     $result1 = $result2 = $result3 = $result4 = $result5 = false;
        //     if($vehicleInfo['s_imei']==""){
        //         $returnResponse['error'] = 1;
        //         $returnResponse['message'] = "Device information not exist.";
        //         echo json_encode($returnResponse);
        //         exit();
        //     }
            
        //     $result1 = $this->apimodel->deleteGpsLivetrackingData($vehicleInfo['s_imei']);
        //     if($result1==true){
        //         // device logs insert
        //         $serialInfo = $this->commonmodel->getSerialNumberInfo($vehicleInfo['veh_serial_no']);
        //         $vehicleInfo['s_distributor_id'] = $serialInfo['s_distributor_id'];
        //         $vehicleInfo['s_dealer_id'] = $serialInfo['s_dealer_id'];
                
        //         $result2 = $this->apimodel->create_device_log($vehicleInfo);
        //         // invoice customer delete
        //         $result3 = $this->apimodel->delete_invoices_customer($vehicleInfo);
        //         // serial number update
        //         $result4 = $this->apimodel->update_serial_number($vehicleInfo);
        //         // vehicle data delete
        //         $result5 = $this->apimodel->deleteVehicleInfo($vehicleInfo);
        //         // fitment entry delete
        //         $result6 = $this->apimodel->deleteFitment($vehicleInfo);
    
        //         $responseData=array();
        //         $responseData['result1']=$result1;
        //         $responseData['result2']=$result2;
        //         $responseData['result3']=$result3;
        //         $responseData['result4']=$result4;
        //         $responseData['result5']=$result5;
        //         $responseData['result6']=$result6;
        //         $returnResponse['success'] = "true";
        //         $returnResponse['responseData'] = $responseData;
        
        //         echo json_encode($returnResponse);
        //         exit();
        //     }else{
        //         $returnResponse['error'] = "failure";
        //         $returnResponse['message'] = "GPS Live Tracking Data's not found";
        
        //         echo json_encode($returnResponse);
        //         exit();
        //     }
    
        // }
    
    
    // subash changes
    public function owner_inter_change()
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

        $data['vehicleInfo'] = $this->apimodel->getVehicleInfo($VehicleID);

        if (empty($data['vehicleInfo'])) {
            redirect(base_url() . 'admin/dashboard', 'refresh');
        }

        $data['vehicleInfo']['veh_id'] = base64_encode($VehicleID);

        $data['pageTitle'] = 'Ownership Inter Change';
        // echo "<pre>"; print_r($VehicleID);exit;

        $user_id = $this->session->userdata('user_id');
        // echo "<pre>"; print_r($data['vehicleInfo']);exit;
        $data['serialList'] = $this->apimodel->allSerialList($data['vehicleInfo']['dealer_id']);
        // Load Content
        // echo "<pre>"; print_r($data);exit;
        $this->load->view('masters/owner_inter_change', $data);
    }

    public function fetch_customer_by_phone()
    {

        $params = $this->input->post();

        $data['customer'] = $this->apimodel->getCustomerInfo($params['phone']);

        echo json_encode($data);
        exit();

    }

    // public function ownership_change()
    // {
    //     $params = $this->input->post();
        
    //     $returnResponse = array();

    //     $returnResponse['validation'] = array();

    //     $returnResponse['error'] = "";

    //     $returnResponse['success'] = "";
    //     // echo "<pre>"; print_r($params);exit; 
    //     // Validation

    //     $this->form_validation->set_rules('new_phone', 'Please choose phone number', 'trim|required');
        
    //     $this->form_validation->set_rules('new_name', 'Please choose name', 'trim|required');

    //     $this->form_validation->set_rules('new_email', 'Please choose email', 'trim|required');

    //     $this->form_validation->set_rules('reason_inter_change', 'Please choose reason', 'trim|required');

    //     if($params['reason_inter_change']==""){
    //         $obj['reason_inter_change'] = "Please choose reason.";
    //         $returnResponse['validation'] = $obj;
    //         echo json_encode($returnResponse);
    //         exit();
    //     }

    //     if ($this->form_validation->run() == FALSE){
    //         $returnResponse['validation'] = $this->form_validation->error_array();
    //         echo json_encode($returnResponse);
    //         exit();
    //     }
        
    //     if($params['reason_inter_change']=="1"){
    //         $params['reason'] = "I Change My Phone Number";
    //     }else if($params['reason_inter_change']=="2"){
    //         $params['reason'] = "My Number is Missing";
    //     }else if($params['reason_inter_change']=="3"){
    //         $params['reason'] = "Owner Change";
    //     }

    //     //Pass params to Model
    //     $params['c_created_by'] = $this->session->userdata('user_id');
        
    //     $result1 = $result2 = $result3 = $result4 = $result5 = false;
        
    //     if(!$params['new_owner_id'] && $params['new_phone']){
    //         //create customer
    //         $ownerId = $this->apimodel->create_new_customer_records($params);
    //         $params['new_owner_id'] = $ownerId;
    //     }
    //     $params['veh_id']=base64_decode($params['veh_id']);
        
    //     $serialInfo = $this->commonmodel->getSerialNumberInfo($params['s_serial_id']);
    //     $params['s_imei'] = $serialInfo['s_imei'];
    //     $params['s_distributor_id'] = $serialInfo['s_distributor_id'];
    //     $params['s_dealer_id'] = $serialInfo['s_dealer_id'];
    //     // echo "<pre>";print_r($params);exit;
    //     $result1 = $this->apimodel->create_device_log_owner_change($params);
    //     $result2 = $this->apimodel->update_new_owner_vehicle($params);
    //     $result3 = $this->apimodel->update_new_owner_serial($params);
    //     $result4 = $this->apimodel->update_new_owner_invoice($params);
    //     $result5 = $this->apimodel->update_new_owner_gps_tracking($params);
    
    //     // Set Session

    //     $responseData=array();
    //     $responseData['result1']=$result1;
    //     $responseData['result2']=$result2;
    //     $responseData['result3']=$result3;
    //     $responseData['result4']=$result4;
    //     $responseData['result5']=$result5;
    //     $responseData['result6']=$result6;
    //     $returnResponse['success'] = true;
    //     $returnResponse['redirect'] = 'admin/entry_list';
    //     $returnResponse['message'] = 'Owner interchange has been changed successfully.';
    //     $returnResponse['responseData'] = $responseData;

    //     echo json_encode($returnResponse);
    //     exit();

    //     // $returnResponse['success'] = true;

    //     // $returnResponse['redirect'] = 'admin/entry_list';

    //     // $returnResponse['message'] = 'Owner interchange has been changed successfully.';

    //     // echo json_encode($returnResponse);
    //     // exit();

    // }
    
    public function ownership_change()
    {
        $params = $this->input->post();
        
        $returnResponse = array();

        $returnResponse['validation'] = array();

        $returnResponse['error'] = "";

        $returnResponse['success'] = "";
        // echo "<pre>"; print_r($params);exit; 
        // Validation

        if($params['reason_inter_change'] != 1){
        $this->form_validation->set_rules('new_phone', 'Please choose phone number', 'trim|required');
        
        $this->form_validation->set_rules('new_name', 'Please choose name', 'trim|required');

        $this->form_validation->set_rules('new_email', 'Please choose email', 'trim|required');

        $this->form_validation->set_rules('reason_inter_change', 'Please choose reason', 'trim|required');
        }

        $count = $this->apimodel->verify_exits_customer_phone($params);
        if($params['reason_inter_change'] == 1){

        if($count != 0){
                $returnResponse['error'] = 1;
                $returnResponse['message'] = "Mobile number already exist";
                echo json_encode($returnResponse);
                exit();
        }

        $this->form_validation->set_rules('new_phone', 'Please choose phone number', 'trim|required');

        $this->form_validation->set_rules('reason_inter_change', 'Please choose reason', 'trim|required');
        }

        if($params['reason_inter_change']==""){
            $obj['reason_inter_change'] = "Please choose reason.";
            $returnResponse['validation'] = $obj;
            echo json_encode($returnResponse);
            exit();
        }

        if ($this->form_validation->run() == FALSE){
            $returnResponse['validation'] = $this->form_validation->error_array();
            echo json_encode($returnResponse);
            exit();
        }
        
        if($params['reason_inter_change']=="1"){
            $params['reason'] = "I Change My Phone Number";
        $result1 = $result2 = $result3 = $result4 = $result5 = false;

        }else if($params['reason_inter_change']=="2"){
        $result1 = $result2 = false;

            $params['reason'] = "Owner Change";
        }

        // else if($params['reason_inter_change']=="3"){
        //     $params['reason'] = "Owner Change";
        // }


        //Pass params to Model
        $params['c_created_by'] = $this->session->userdata('user_id');
        // echo "<pre>";print_r($count);exit;
        
        
        
        
        $serialInfo = $this->commonmodel->getSerialNumberInfo($params['s_serial_id']);
        $params['s_imei'] = $serialInfo['s_imei'];
        $params['s_distributor_id'] = $serialInfo['s_distributor_id'];
        $params['s_dealer_id'] = $serialInfo['s_dealer_id'];

        $responseData=array();

        if($params['reason_inter_change']=="2"){
        // echo "<pre>";print_r($params);exit;

            if(!$params['new_owner_id'] && $params['new_phone']){
                //create customer
                $ownerId = $this->apimodel->create_new_customer_records($params);
                $params['new_owner_id'] = $ownerId;
            }
            $params['veh_id']=base64_decode($params['veh_id']);
            
            $result1 = $this->apimodel->create_device_log_owner_change($params);
            $result2 = $this->apimodel->update_new_owner_vehicle($params);
            $result3 = $this->apimodel->update_new_owner_serial($params);
            $result4 = $this->apimodel->update_new_owner_invoice($params);
            $result5 = $this->apimodel->update_new_owner_gps_tracking($params);
        
            // Set Session

            $responseData['result1']=$result1;
            $responseData['result2']=$result2;
            $responseData['result3']=$result3;
            $responseData['result4']=$result4;
            $responseData['result5']=$result5;
            $responseData['result6']=$result6;
            $returnResponse['success'] = true;
            $returnResponse['redirect'] = 'admin/entry_list';
            $returnResponse['message'] = 'Owner interchanged successfully.';
            $returnResponse['responseData'] = $responseData;

        }

        if($params['reason_inter_change']=="1"){

        $result1 = $this->apimodel->update_owner_number_vehicle($params);
        $result2 = $this->apimodel->update_owner_number_customer($params);

            $returnResponse['success'] = true;

        $returnResponse['redirect'] = 'admin/entry_list';

        $returnResponse['message'] =  'Mobile number has been changed successfully.';

        $returnResponse['responseData'] = $responseData;

        // echo json_encode($returnResponse);
        // exit();

    }
    echo json_encode($returnResponse);
    exit();
    
    // subash changes

 }
    
    // subash changes

}