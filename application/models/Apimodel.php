<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Apimodel extends CI_Model {

    public function deleteGpsLivetrackingData($serialIMEI)
    {
        // gps_livetracking delete
        // date_default_timezone_set('Asia/Kolkata'); //Asia: India

        // $time =  date("Y-m-d"." "."H:i:s");
        
        $otherdb = $this->load->database('tracking', TRUE);
        $otherdb->select('*');
        $otherdb->where('imei', $serialIMEI);
        // $otherdb->where('lastupdatedTime <=', $time);
        $otherdb->from($otherdb->table_trackings);
        $result = $otherdb->get();
        $result1 = $result->result_array();
        // end
        // echo "<pre>";print_r(count($result1));exit;
        if(count($result1)!=0){
            $sql = "DELETE FROM `gps_livetracking_data` WHERE imei='$serialIMEI'";
            $tracking = $this->load->database('tracking', TRUE);
            $tracking->query($sql);
            return true;
        }else{
            return false;
        }
    }

    public function create_device_log($vehicleInfo)
    {
        date_default_timezone_set('Asia/Kolkata'); //Asia: India
        $time =  date("Y-m-d"." "."H:i:s");
        $insertRecords=array();
       // $insertRecords=array();
        // $insertRecords['vehicle_id']=isset($vehicleInfo['veh_id'])?$vehicleInfo['veh_id']:"";
        // $insertRecords['serial_id']=isset($vehicleInfo['veh_serial_no'])?$vehicleInfo['veh_serial_no']:"";
        // $insertRecords['customer_id']=isset($vehicleInfo['veh_owner_id'])?$vehicleInfo['veh_owner_id']:"";
        // $insertRecords['event_date']= $time;
        // $insertRecords['changed_by']= $this->session->userdata('user_id');
        // $insertRecords['event_id']=4; // 4 => Back to stock
        // $insertRecords['reason'] = null;
        // $this->db->insert($this->db->table_device_logs, $insertRecords);
        $objOldSerialRec=array();
        $objOldSerialRec['vehicle_id']=isset($vehicleInfo['veh_id'])?$vehicleInfo['veh_id']:"";
        $objOldSerialRec['serial_id']= isset($vehicleInfo['veh_serial_no'])?$vehicleInfo['veh_serial_no']:"";
        $objOldSerialRec['customer_id']= isset($vehicleInfo['veh_owner_id'])?$vehicleInfo['veh_owner_id']:"";
        $objOldSerialRec['distributor_id']= isset($vehicleInfo['s_distributor_id'])?$vehicleInfo['s_distributor_id']:"";
        $objOldSerialRec['dealer_id']= isset($vehicleInfo['s_dealer_id'])?$vehicleInfo['s_dealer_id']:"";
        $objOldSerialRec['event_date']= $time;
        $objOldSerialRec['changed_by']=	$this->session->userdata('user_id');
        $objOldSerialRec['reason']=NULL;
        $objOldSerialRec['event_id']=8;	// Refurbished (Back to stock)
        $this->db->insert($this->db->table_device_logs, $objOldSerialRec);
        return true;
    }

    public function delete_invoices_customer($params)
    {
        $this->db->select('*');
        $this->db->where('i_serial_ids', $params['veh_serial_no']);
        $this->db->from($this->db->table_invoices_customer);
        $result = $this->db->get();
        $result1 = $result->result_array();
        if(count($result1)!=0){
            // delete invoice_customer
            $this->db->where('i_serial_ids', $params['veh_serial_no']);
            $this->db->delete($this->db->table_invoices_customer);
        }
        return true;
    }

    public function update_serial_number($vehicleInfo)
    {
        $this->db->select('*');
        $this->db->where('s_serial_id', $vehicleInfo['veh_serial_no']);
        $this->db->from($this->db->table_serial_no);
        $result = $this->db->get();
        $result1 = $result->result_array();
        if(count($result1)!=0){
            // update serial number
            $updateRecords=array();
            $updateRecords['s_used']=0;	
            $updateRecords['customer_id'] = 0;
            $updateRecords['assign_to_customer_on'] = NULL;
            $updateRecords['fitment'] = 0;
            $this->db->where('s_serial_id', $vehicleInfo['veh_serial_no']);
            $this->db->update($this->db->table_serial_no,$updateRecords);
        }
        return true;
    }

    public function deleteVehicleInfo($vehicleInfo)
    {
        $this->db->select('*');
        $this->db->where('veh_id', $vehicleInfo['veh_id']);
        $this->db->from($this->db->table_vehicle);
        $result = $this->db->get();
        $result1 = $result->result_array();
        if(count($result1)!=0){
            // vehicle data delete
            if($vehicleInfo['veh_speed_governer_photo']!=""){
                unlink($vehicleInfo['veh_speed_governer_photo']);
            }
            if($vehicleInfo['vehicle_owner_photo']!=""){
                unlink($vehicleInfo['veh_speed_governer_photo']);
            }
            if($vehicleInfo['rc_book_photo']!=""){
                unlink($vehicleInfo['rc_book_photo']);
            }
            if($vehicleInfo['vehicle_owner_id_proof']!=""){
                unlink($vehicleInfo['vehicle_owner_id_proof']);
            }
            if($vehicleInfo['veh_photo']!=""){
                unlink($vehicleInfo['veh_photo']);
            }
            $this->db->where('veh_id', $vehicleInfo['veh_id']);
            $this->db->delete($this->db->table_vehicle);
        }
        return true;
    }

    public function deleteFitment($vehicleInfo)
    {
        $this->db->select('*');
        $this->db->where('fitment_imei', $vehicleInfo['s_imei']);
        $this->db->from($this->db->table_device_fitment);
        $result = $this->db->get();
        $result1 = $result->result_array();
        if(count($result1)!=0){
            // delete fitment detail
            $this->db->where('fitment_imei', $vehicleInfo['s_imei']);
            $this->db->delete($this->db->table_device_fitment);
        }
        return true;
    }
    
    public function getVehicleInfo($id, $user = 0)
    {
        // $this->db->select('veh.*,cus.c_email as veh_owner_email,mod.ve_model_name,ser.s_serial_number');
        $this->db->select('veh.veh_rc_no,veh.veh_owner_name,veh.veh_owner_phone,veh.veh_owner_id,cus.c_email as veh_owner_email,mod.ve_model_name,ser.s_serial_number,ser.s_iccid,ser.s_imei,ser.s_serial_id,rto.rto_number, user.user_name as dealer_name, user.user_phone as dealer_phone, user.user_id as dealer_id');
        $this->db->where('veh_id', $id);
        $this->db->from($this->db->table_vehicle . ' as veh');
        $this->db->join($this->db->table_model . ' as mod', 'veh.veh_model_no = mod.ve_model_id', 'left');
        $this->db->join($this->db->table_rto . ' as rto', 'veh.veh_rto_no = rto.rto_no', 'left');
        $this->db->join($this->db->table_serial_no . ' as ser', 'ser.s_serial_id = veh.veh_serial_no', 'left');
        $this->db->join($this->db->table_users . ' as user', 'user.user_id = ser.s_dealer_id', 'left');
        $this->db->join($this->db->table_customers . ' as cus', 'cus.c_phone = veh.veh_owner_phone', 'left');
        $result = $this->db->get();
        $result = $result->row_array();
        return $result;
    }

    public function verify_exits_customer_phone($params)
    {
        $this->db->select('count(*)');
        $this->db->where('c_phone', $params['new_phone']);
        $this->db->from($this->db->table_customers);
        $result = $this->db->count_all_results();
        return $result;
    }
    
    public function allSerialList($byUser = 0)
    {
        $user_type = $this->session->userdata('user_type');
        $user_company_id = $this->session->userdata('user_company_id');
        $this->db->select('s_serial_id,s_serial_number,s_imei,s_iccid,s_company_id');
        if ((string)$user_type != '0') {
            $this->db->where('s_company_id', $user_company_id);
        }
        if ((string)$byUser != '1') {
            $this->db->where('s_dealer_id ', $byUser);
        }else{
            $this->db->where('s_dealer_id  !=', 0);
        }
        $this->db->from($this->db->table_serial_no);
        $result = $this->db->get();
        $result = $result->result_array();
        return $result;
    }

    public function getCustomerInfo($phone)
    {
        if ($phone) {
            $this->db->select('c_customer_id,c_email,c_customer_name,c_address');
            $this->db->from($this->db->table_customers);
            $this->db->like('c_phone', $phone, 'both');
            $result = $this->db->get();
            $result = $result->row();
        }
        //echo $this->db->last_query();exit();
        return $result;
    }

    public function create_new_customer_records($params)
    {
        $user_type = $this->session->userdata('user_type');
        $user_id=$this->session->userdata('user_id');

        if($params['new_owner_id']==''){
            $insert=array(
                'c_customer_name' => isset($params['new_name'])?$params['new_name']:"",
                'c_address' => isset($params['new_address'])?$params['new_address']:"",
                'c_phone' => isset($params['new_phone'])?$params['new_phone']:"",
                'c_user_status'=>1, "c_created_by" => $user_id
            );
            if(isset($params['new_email']) && strlen($params['new_email'])>0)
            {
                $insert['c_email']=isset($params['new_email'])?$params['new_email']:"";
            }
            $this->db->insert($this->db->table_customers,$insert);

            $new_owner_id = $this->db->insert_id();
            // echo "<pre>";print_r($params);exit;
            return $new_owner_id;
        }
    }

    public function create_device_log_owner_change($params)
    {
        date_default_timezone_set('Asia/Kolkata'); //Asia: India
        // old owner record
        $time =  date("Y-m-d"." "."H:i:s");
        $insertRecords=array();
        $insertRecords['vehicle_id']=isset($params['veh_id'])?$params['veh_id']:"";
        $insertRecords['serial_id']=isset($params['s_serial_id'])?$params['s_serial_id']:"";
        $insertRecords['distributor_id']= isset($params['s_distributor_id'])?$params['s_distributor_id']:"";
		$insertRecords['dealer_id']= isset($params['s_dealer_id'])?$params['s_dealer_id']:"";
        $insertRecords['customer_id']=isset($params['new_owner_id'])?$params['new_owner_id']:"";
        $insertRecords['event_date']= $time;
        $insertRecords['changed_by']= $this->session->userdata('user_id');
        $insertRecords['event_id']=6; // 6 => Owner Change
        $insertRecords['reason'] = isset($params['reason'])?$params['reason']:"";
        $this->db->insert($this->db->table_device_logs, $insertRecords);
        
        

        // // new owner record
        // $newOwnerRecords=array();
        // $newOwnerRecords['vehicle_id']=isset($params['veh_id'])?$params['veh_id']:"";
        // $newOwnerRecords['serial_id']=isset($params['s_serial_id'])?$params['s_serial_id']:"";
        // $newOwnerRecords['customer_id']=isset($params['new_owner_id'])?$params['new_owner_id']:"";
        // $newOwnerRecords['event_date']= $time;
        // $newOwnerRecords['changed_by']= $this->session->userdata('user_id');
        // $newOwnerRecords['event_id']=1; // 6 => Owner Change
        // $newOwnerRecords['reason'] = NULL;
        // $this->db->insert($this->db->table_device_logs, $newOwnerRecords);
        return true;
    }

    public function update_new_owner_vehicle($params)
    {

        $this->db->select('count(*)');
        $this->db->where('veh_owner_id', $params['veh_owner_id']);
        $this->db->from($this->db->table_vehicle);
        $result = $this->db->count_all_results();
        if($result!=0){
            $updateRecords=array();
            $updateRecords['veh_owner_id'] = $params['new_owner_id'];
            $updateRecords['veh_owner_name'] = $params['new_name'];
            $updateRecords['veh_address'] = $params['new_address'];
            $updateRecords['veh_owner_phone'] = $params['new_phone'];
            $this->db->where('veh_id', $params['veh_id']);
            $this->db->where('veh_owner_id', $params['veh_owner_id']);
            $this->db->update($this->db->table_vehicle,$updateRecords);
        }
        return true;
    }

    public function update_new_owner_serial($params)
    {
        $this->db->select('count(*)');
        $this->db->where('s_serial_id', $params['s_serial_id']);
        $this->db->where('customer_id', $params['veh_owner_id']);
        $this->db->from($this->db->table_serial_no);
        $result = $this->db->count_all_results();
        if($result!=0){
            $updateRecords=array();
            $updateRecords['customer_id'] = $params['new_owner_id'];
            $this->db->where('s_serial_id', $params['s_serial_id']);
            $this->db->where('customer_id', $params['veh_owner_id']);
            $this->db->update($this->db->table_serial_no, $updateRecords);
        }
        return true;
    }

    public function update_new_owner_invoice($params)
    {
        $this->db->select('count(*)');
        $this->db->where('i_serial_ids', $params['s_serial_id']);
        $this->db->where('i_to_customer_id', $params['veh_owner_id']);
        $this->db->from($this->db->table_invoices_customer);
        $result = $this->db->count_all_results();
        if($result!=0){
            $updateRecords=array();
            $updateRecords['i_to_customer_id'] = $params['new_owner_id'];
            $this->db->where('i_serial_ids', $params['s_serial_id']);
            $this->db->where('i_to_customer_id', $params['veh_owner_id']);
            $this->db->update($this->db->table_invoices_customer,$updateRecords);
        }
        return true;
    }

    public function update_new_owner_gps_tracking($params)
    {
        $otherdb = $this->load->database('tracking', TRUE);
        $otherdb->select('count(*)');
        $otherdb->where('vehicleId', $params['veh_id']);
        $otherdb->where('customerID', $params['veh_owner_id']);
        $otherdb->from($otherdb->table_trackings);
        $result = $this->db->count_all_results();
        
        if($result!=0){
            $updateRecords=array();
            $updateRecords['customerID'] = $params['new_owner_id'];
            $otherdb->where('vehicleId', $params['veh_id']);
            $otherdb->where('customerID', $params['veh_owner_id']);
            $otherdb->update($otherdb->table_trackings,$updateRecords);
        }
        return true;
    }
    
     public function update_owner_number_vehicle($params)
    {

            $updateRecords=array();
            $updateRecords['veh_owner_phone'] = $params['new_phone'];
            $this->db->where('veh_owner_id', $params['veh_owner_id']);
            $this->db->update($this->db->table_vehicle,$updateRecords);

        return true;
    }
    
    public function update_owner_number_customer($params)
    {
            $updateRecord=array();
            $updateRecord['c_phone'] = $params['new_phone'];
            $this->db->where('c_customer_id', $params['veh_owner_id']);
            $this->db->update($this->db->table_customers,$updateRecord);

        return true;

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
}