<?php
defined('BASEPATH') or exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, OPTIONS");
require_once FCPATH . 'vendor/autoload.php';

use Aws\S3\S3Client;

class Commonmodel extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function send_sms($phone, $otpMsg)
    {
        // echo "sms service"; exit;
        log_message('error', $phone);
        log_message('error', $otpMsg);
        $msgContent = "http://api.msg91.com/api/sendhttp.php?route=4&sender=PSDNIN&mobiles=" . $phone . "&authkey=273355AY7MXrX08qN5cbc5435&encrypt=&message=" . urlencode($otpMsg) . "&unicode=1&country=91";
        log_message('error', $msgContent);
         $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $msgContent,
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

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }

    public function send_sms_wp($phone, $otpMsg)
    {
        // echo "sms service"; exit;
        log_message('error', $phone);
        log_message('error', $otpMsg);
        // $msgContent = "http://api.msg91.com/api/sendhttp.php?route=4&sender=PSDNIN&mobiles=" . $phone . "&authkey=273355AY7MXrX08qN5cbc5435&encrypt=&message=" . urlencode($otpMsg) . "&unicode=1&country=91";
        log_message('error', "TEST WHATSAPP MESSAGE:::::::::");

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://partnersv1.pinbot.ai/v3/417900404747640/messages',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "messaging_product": "whatsapp",
            "recipient_type": "individual",
            "to": "'.$phone.'",
            "type": "template",
            "template": {
                "name": "certificate_links",
                "language": {
                    "code": "en"
                },
                "components": [
                    {
                        "type": "button",
                        "sub_type": "url",
                        "index": "0",
                        "parameters": [
                            {
                                "type": "text",
                                "text": "'.$otpMsg.'"
                            }
                        ]
                    },
                    {
                        "type": "button",
                        "sub_type": "VOICE_CALL",
                        "index": "1",
                        "parameters": [
                            {
                                "type": "text",
                                "text": "SUPPORT"
                            }
                        ]
                    }
                ]
            }
        }',
        CURLOPT_HTTPHEADER => array(
            'apikey: c49a6712-92fb-11ef-bb5a-02c8a5e042bd',
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }


    public function getNoOfCount_copy()
    {
        $user_type = $this->session->userdata('user_type');
        $user_company_id = $this->session->userdata('user_company_id');
        $response = array();
        $user_id = $this->session->userdata('user_id');
        // Dealers
        // $this->db->select('*');
        $this->db->select('count(*)');
        $this->db->where('user_type', 1);

        if ((string)$user_type != '0') {
            $this->db->where('user_company_id', $user_company_id);
            $this->db->where('created_by', $user_id);
        }

        $this->db->from($this->db->table_users);
        $result = $this->db->count_all_results();
        $response['dealer'] = $result;


        // DISTRIBUTOR
        // $this->db->select('*');
        $this->db->select('count(*)');
        $this->db->where('user_type', 2);


        if ((string)$user_type != '0') {
            $this->db->where('user_company_id', $user_company_id);
            $this->db->where('created_by', $user_id);
            $this->db->where('created_by', $user_id);
        }
        $this->db->from($this->db->table_users);
        $result = $this->db->count_all_results();
        $response['distributor'] = $result;

        // RTO
        // $this->db->select('*');
        $this->db->select('count(*)');
        //$this->db->where('user_type',3);
        $this->db->from($this->db->table_rto);
        $result = $this->db->count_all_results();
        $response['rto'] = $result;

        // Vehicle
        $this->db->select('count(*)');
        // echo "<pre>";print_r("user");exit;
        if ($user_type == 1) {
            $this->db->where('ser.s_company_id', $user_company_id);
            $this->db->where('ser.s_dealer_id', $user_id);
        }
        if ($user_type == 2) {
            $this->db->where('ser.s_company_id', $user_company_id);
            $this->db->where('ser.s_distributor_id', $user_id);
        }
        $this->db->where('ser.s_used', 1);
        $this->db->from($this->db->table_vehicle . ' as veh');
        $this->db->join($this->db->table_serial_no . ' as ser', 'ser.s_serial_id = veh.veh_serial_no', 'left');
        $result = $this->db->count_all_results();
        //  echo $this->db->last_query();exit();
        $response['vehicle'] = $result;

        //TotalDevices 
        if ($user_type == 0) {
            //assigned
            $this->db->select('count(*)');
            $this->db->from($this->db->table_serial_no);
            $this->db->where('s_distributor_id >', '0');
            $result = $this->db->count_all_results();

            //Unassigned
            $this->db->select('count(*)');
            $this->db->where('s_distributor_id', '0');
            $this->db->where('s_used', '0');
            $this->db->where('inScan', '0');
            $this->db->from($this->db->table_serial_no);
            $result1 = $this->db->count_all_results();

            //fault
            $this->db->select('count(*)');
            $this->db->from($this->db->table_serial_no);
            $this->db->where('s_distributor_id >', '0');
            $this->db->where('s_status', 1);
            $result2 = $this->db->count_all_results();

            $response['totalDevices'] = $result + $result1 + $result2;
        }
        if ($user_type == 2) {
            //assigned
            $this->db->select('count(*)');
            $this->db->from($this->db->table_serial_no);
            $this->db->where('s_distributor_id', $user_id);
            $result = $this->db->count_all_results();

            //Unassigned
            $this->db->select('count(*)');
            $this->db->where('s_company_id', $user_company_id);
            $this->db->where('s_distributor_id', $user_id);
            $this->db->where('s_dealer_id', '0');
            $this->db->where('s_used', '0');
            $this->db->from($this->db->table_serial_no);
            $result1 = $this->db->count_all_results();

            //fault
            $this->db->select('count(*)');
            $this->db->from($this->db->table_serial_no);
            $this->db->where('s_distributor_id', $user_id);
            $this->db->where('s_status', 1);
            $result2 = $this->db->count_all_results();
            // echo "<pre>";print_r($result1);exit();

            $response['totalDevices'] = $result + $result1 + $result2;
        }
        if ($user_type == 1) {
            //assigned
            $this->db->select('count(*)');
            $this->db->from($this->db->table_serial_no);
            $this->db->where('s_dealer_id', $user_id);
            $result = $this->db->count_all_results();

            //fault
            $this->db->select('count(*)');
            $this->db->from($this->db->table_serial_no);
            $this->db->where('s_dealer_id', $user_id);
            $this->db->where('s_status', 1);
            $result1 = $this->db->count_all_results();

            $response['totalDevices'] = $result + $result1;
        }

        //used Devices
        if ($user_type == '0' || $user_type == '2') {
            $this->db->select('count(*)');
            $user_company_id = $this->session->userdata('user_company_id');
            if ((string)$user_type != '0') {
                $this->db->where('s_company_id	', $user_company_id);
            }
            if ($user_type == '0') {
                $this->db->where('s_distributor_id >', '0');
            } else if ($user_type == '2') {
                $this->db->where('s_distributor_id', $user_id);
            }
            $this->db->from($this->db->table_serial_no);
            $result = $this->db->count_all_results();
            $response['usedDevices'] = $result;
        }
        if ($user_type == '1') {
            $this->db->select('count(*)');
            $this->db->where('s_dealer_id', $user_id);
            $this->db->where('s_used', '1');
            $this->db->from($this->db->table_serial_no);
            $result = $this->db->count_all_results();
            // echo $this->db->last_query();exit();
            $response['usedDevices'] = $result;
        }

        //unused devices
        if ($user_type == '0') {
            $this->db->select('count(*)');
            $this->db->where('s_distributor_id', '0');
            $this->db->where('s_used', '0');
            $this->db->where('inScan', '0');
            $this->db->from($this->db->table_serial_no);
            $result1 = $this->db->count_all_results();
            // echo "<pre>";print_r($result1);exit();

            $response['unUsedDevices'] = $result1;
        } else {
            $this->db->select('count(*)');
            $this->db->where('s_company_id', $user_company_id);
            $this->db->where('s_distributor_id', $user_id);
            $this->db->where('s_dealer_id', '0');
            $this->db->where('s_used', '0');
            $this->db->from($this->db->table_serial_no);
            $result1 = $this->db->count_all_results();

            $this->db->select('count(*)');
            $user_company_id = $this->session->userdata('user_company_id');
            if ((string)$user_type != '0') {
                $this->db->where('s_company_id	', $user_company_id);
            }
            if ($user_type == '2') {
                $this->db->where('s_distributor_id', $user_id);
                $this->db->where('s_dealer_id', '0');
            }
            if ($user_type == '1') {
                $this->db->where('s_dealer_id', $user_id);
            }
            $this->db->where('s_used', '0');
            $this->db->from($this->db->table_serial_no);
            $result = $this->db->count_all_results();
            // echo $this->db->last_query();exit();
            $response['unUsedDevices'] = $result;
        }

        //Offline 
        $currentDatetime = date('Y-m-d H:i:s');
        $oneHourAgo = date('Y-m-d H:i:s', strtotime('-1 hour'));
        // echo "<pre>";print_r($oneHourAgo);
        // echo "<pre>";print_r($currentDatetime);exit;

        $this->db->select('s_imei');
        $this->db->from($this->db->table_serial_no);
        if ($user_type == 1) {
            $this->db->where('s_dealer_id', $user_id);
        }
        if ($user_type == 2) {
            $this->db->where('s_distributor_id', $user_id);
        }
        $results = $this->db->get();
        $results1 = $results->result();
        // echo "<pre>";print_r($results1);exit;

        foreach ($results1 as $row) {
            $vehiclenos .= " '" . $row->s_imei . "',";
        }
        if ($vehiclenos != "") {
            $vehiclenos = substr($vehiclenos, 0, strlen($vehiclenos) - 1);
        }

        if ($vehiclenos != "") {
            $subquery101    = " where imei in (" . $vehiclenos . ") and lastupdatedTime < '" . $oneHourAgo . "' and lastupdatedTime!='' ";
        }
        // echo "<pre>";print_r($subquery101);exit;

        // echo "<pre>";print_r("select count(*) as count from gps_livetracking_data ".$subquery101."");exit;
        $otherdb = $this->load->database('tracking', TRUE);
        $datas     = $otherdb->query("select count(*) as count from gps_livetracking_data " . $subquery101 . "")->result();
        $result2 = $datas[0]->count;
        $response['offline'] = $result2;


        //live
        $this->db->select('s_imei');
        $this->db->from($this->db->table_serial_no);
        if ($user_type == 1) {
            $this->db->where('s_dealer_id', $user_id);
        }
        if ($user_type == 2) {
            $this->db->where('s_distributor_id', $user_id);
        }
        $this->db->where('s_used', 1);
        $results = $this->db->get();
        $results1 = $results->result();
        // echo "<pre>";print_r($results1);exit;

        foreach ($results1 as $row) {
            $vehiclenos .= " '" . $row->s_imei . "',";
        }
        if ($vehiclenos != "") {
            $vehiclenos = substr($vehiclenos, 0, strlen($vehiclenos) - 1);
        }

        if ($vehiclenos != "") {
            $subquery101    = " where imei in (" . $vehiclenos . ") and ignition = 1 ";
        }

        // echo "<pre>";print_r("select count(*) as count from gps_livetracking_data ".$subquery101."");exit;
        $otherdb = $this->load->database('tracking', TRUE);
        $datas     = $otherdb->query("select count(*) as count from gps_livetracking_data " . $subquery101 . "")->result();
        $result1 = $datas[0]->count;
        $response['live'] = $result1;


        //fault
        $this->db->select('count(*)');
        $this->db->from($this->db->table_serial_no);
        if ($user_type == 1) {
            $this->db->where('s_dealer_id', $user_id);
        }
        if ($user_type == 2) {
            $this->db->where('s_distributor_id', $user_id);
        }
        $this->db->where('s_status', 1);
        $result = $this->db->count_all_results();
        $response['faulty'] = $result;

        // $this->db->select('*');
        // $this->db->select('count(*)');
        // if ((string)$user_type != '0') {
        //     $this->db->where('veh_company_id', $user_company_id);
        //     $this->db->where('veh_created_user_id', $user_id);
        // }
        // $this->db->from($this->db->table_vehicle);
        // $result = $this->db->count_all_results();
        // $response['vehicle'] = $result;

        //model
        $this->db->select('*');
        if (isset($_GET['make_id']) && strlen($_GET['make_id']) > 0) {
            $this->db->where('ve_make_id', $_GET['make_id'], 'both');
        }
        if (isset($_GET['search']) && strlen($_GET['search']) > 0) {
            $this->db->or_like('ve_model_name', $_GET['search'], 'both');
        }

        $this->db->from($this->db->table_model);
        $result = $this->db->count_all_results();
        $response['model'] = $result;


        //make
        $this->db->select('v_make_id,v_make_name');
        if (isset($_GET['search']) && strlen($_GET['search']) > 0) {
            $this->db->or_like('v_make_name', $_GET['search'], 'both');
        }
        $this->db->from($this->db->table_make);
        $result = $this->db->count_all_results();
        $response['make'] = $result;

        // //today
        // // $this->db->select('*');
        // $this->db->select('count(*)');
        // $from = date('Y-m-d') . ' 00:00:00';
        // $to = date('Y-m-d') . ' 23:59:59';
        // $this->db->where('veh_create_date >=', $from);
        // $this->db->where('veh_create_date <=', $to);
        // if ((string)$user_type != '0') {
        //     $this->db->where('veh_company_id', $user_company_id);
        //     $this->db->where('veh_created_user_id', $user_id);
        // }
        // $this->db->from($this->db->table_vehicle);
        // $result = $this->db->count_all_results();
        // $response['today'] = $result;

        //today
        // $this->db->select('*');
        $user_type = $this->session->userdata('user_type');
        $user_company_id = $this->session->userdata('user_company_id');
        $this->db->select('*');
        if ((string)$user_type != '0') {
            $this->db->where('ser.s_company_id', $user_company_id);
        }
        $from = date('Y-m-d') . ' 00:00:00';
        $to = date('Y-m-d') . ' 23:59:59';
        $this->db->where('veh_create_date >=', $from);
        $this->db->where('veh_create_date <=', $to);

        $dealer_id = 0;
        $distributor_id = 0;
        if ($user_type == 1) {
            $dealer_id = $this->session->userdata('user_id');
        }

        if ($user_type == 2) {
            $distributor_id = $this->session->userdata('user_id');
        }
        // echo "<pre>";print_r($dealer_id);exit();
        if ((int)$dealer_id != 0) {
            $this->db->where('ser.s_dealer_id', $dealer_id);
        }
        if ((int)$distributor_id != 0) {
            $this->db->where('ser.s_distributor_id', $distributor_id);
        }

        $this->db->where('ser.s_used', 1);
        $this->db->from($this->db->table_vehicle . ' as veh');
        $this->db->join($this->db->table_serial_no . ' as ser', 'ser.s_serial_id = veh.veh_serial_no', 'left');
        $result = $this->db->count_all_results();
        // echo "<pre>";print_r($result);exit();
        // echo "<pre>";print_r($this->db->last_query());exit();
        $response['today'] = $result;
        // $this->db->select('*');
        $this->db->select('count(*)');

        $this->db->from($this->db->table_customers);
        $result = $this->db->count_all_results();
        $response['customer'] = $result;
        return $response;
    }


    public function getNoOfCount()
    {
        $user_type = $this->session->userdata('user_type');
        $user_company_id = $this->session->userdata('user_company_id');
        $response = array();
        $user_id = $this->session->userdata('user_id');
        // Dealers
        // $this->db->select('*');
        $this->db->select('count(*)');
        $this->db->where('user_type', 1);
        $this->db->where('user_status', 1);
        // echo "<pre>";print_r($user_type);exit;
        if ((string)$user_type != '0' && (string)$user_type != '4') {
            $this->db->where('user_company_id', $user_company_id);
            $this->db->where('created_by', $user_id);
        }

        $this->db->from($this->db->table_users);
        $result = $this->db->count_all_results();
        // echo "<pre>";print_r($this->db->last_query());exit();
        $response['dealer'] = $result;


        // DISTRIBUTOR
        // $this->db->select('*');
        $this->db->select('count(*)');
        $this->db->where('user_type', 2);
        $this->db->where('user_status', 1);

        if ((string)$user_type != '0' && (string)$user_type != '4') {
            $this->db->where('user_company_id', $user_company_id);
            $this->db->where('created_by', $user_id);
            $this->db->where('created_by', $user_id);
        }
        $this->db->from($this->db->table_users);
        $result = $this->db->count_all_results();
        $response['distributor'] = $result;

        // RTO
        // $this->db->select('*');
        $this->db->select('count(*)');
        //$this->db->where('user_type',3);
        $this->db->from($this->db->table_rto);
        $result = $this->db->count_all_results();
        $response['rto'] = $result;

        // Vehicle
        $this->db->select('count(*)');
        // echo "<pre>";print_r("user");exit;
        if ($user_type == 1) {
            $this->db->where('ser.s_company_id', $user_company_id);
            $this->db->where('ser.s_dealer_id', $user_id);
        }
        if ($user_type == 2) {
            $this->db->where('ser.s_company_id', $user_company_id);
            $this->db->where('ser.s_distributor_id', $user_id);
        }
        $this->db->where('ser.s_used', 1);
        $this->db->where('ser.s_status', 0);
        $this->db->from($this->db->table_serial_no . ' as ser');
        $this->db->join($this->db->table_vehicle . ' as veh', 'ser.s_serial_id = veh.veh_serial_no', 'left');
        $result = $this->db->count_all_results();
        //  echo $this->db->last_query();exit();
        $response['vehicle'] = $result;
        // echo "<pre>";print_r($response['vehicle']);exit;

        //-------------- old code
        //         //TotalDevices 
        //         if($user_type == 0 || $user_type == 4 )
        // 		{
        //             //assigned
        //             $this->db->select('count(*)');   
        //             $this->db->from($this->db->table_serial_no); 
        //             $this->db->where('s_distributor_id >', '0');
        //             $this->db->where('s_status', 0);
        //             $result = $this->db->count_all_results();
        //             // echo $this->db->last_query();exit();
        //             //Unassigned
        //             $this->db->select('count(*)');
        //             $this->db->where('s_distributor_id', '0');
        //             $this->db->where('s_used', '0');
        //             // $this->db->where('inScan', '0');
        //             $this->db->from($this->db->table_serial_no);
        //             $result1 = $this->db->count_all_results();
        //             // echo "<pre>";print_r($result1);exit;
        //             //fault
        //             $this->db->select('count(*)');   
        //             $this->db->from($this->db->table_serial_no); 
        //             $this->db->where('s_distributor_id >', '0');
        //             $this->db->where('s_status', 1);
        //             $result2 = $this->db->count_all_results();

        //             $response['totalDevices'] = $result+$result1+$result2;

        //         }
        //         if($user_type == 2)
        // 		{
        //             //assigned
        //             $this->db->select('count(*)');   
        //             $this->db->from($this->db->table_serial_no); 
        //             $this->db->where('s_distributor_id', $user_id);
        //             $result = $this->db->count_all_results();

        //             //Unassigned
        //             $this->db->select('count(*)');
        //             $this->db->where('s_company_id', $user_company_id);
        //             $this->db->where('s_distributor_id', $user_id);
        //             $this->db->where('s_dealer_id', '0');
        //             $this->db->where('s_used', '0');
        //             $this->db->from($this->db->table_serial_no);
        //             $result1 = $this->db->count_all_results();

        //             //fault
        //             $this->db->select('count(*)');   
        //             $this->db->from($this->db->table_serial_no); 
        //             $this->db->where('s_distributor_id', $user_id);
        //             $this->db->where('s_status', 1);
        //             $result2 = $this->db->count_all_results();
        //             // echo "<pre>";print_r($result1);exit();

        //             $response['totalDevices'] = $result+$result1+$result2;
        //         }
        //         if($user_type == 1)
        // 		{
        //             //assigned
        //             $this->db->select('count(*)');   
        //             $this->db->from($this->db->table_serial_no); 
        //             $this->db->where('s_dealer_id', $user_id);
        //             $result = $this->db->count_all_results();

        //             //fault
        //             $this->db->select('count(*)');   
        //             $this->db->from($this->db->table_serial_no); 
        //             $this->db->where('s_dealer_id', $user_id);
        //             $this->db->where('s_status', 1);
        //             $result1 = $this->db->count_all_results();

        //             $response['totalDevices'] = $result+$result1;
        //         }


        //             //used Devices
        //             if($user_type == '0' || $user_type == '2' || $user_type == '4' )
        //             {
        //                 $this->db->select('count(*)');
        //                 $user_company_id = $this->session->userdata('user_company_id');
        //                 if ((string)$user_type != '0') {
        //                     $this->db->where('s_company_id	', $user_company_id);
        //                 }
        //                 if ($user_type == '0' || $user_type == '4') {
        //                     $this->db->where('s_distributor_id >', '0');
        //                     $this->db->where('s_status', 0);
        //                 } else if ($user_type == '2') {
        //                     $this->db->where('s_distributor_id', $user_id);
        //                     $this->db->where('s_status', 0);
        //                 }         
        //                 $this->db->from($this->db->table_serial_no);
        //                 $result = $this->db->count_all_results();
        //                 // echo $this->db->last_query();exit();
        //                 $response['usedDevices'] = $result;
        //             }
        //             if($user_type == '1'){
        //                 $this->db->select('count(*)');
        //                 $this->db->where('s_dealer_id', $user_id);
        //                 $this->db->where('s_used', '1');    
        //                 $this->db->from($this->db->table_serial_no);
        //                 $result = $this->db->count_all_results();
        //                 // echo $this->db->last_query();exit();
        //                 $response['usedDevices'] = $result; 
        //             }

        //           //unused devices
        //             if ($user_type == '0' || $user_type == '4') {
        //                 $this->db->select('count(*)');
        //                 $this->db->where('s_distributor_id', '0');
        //                 $this->db->where('s_used', '0');
        //                 $this->db->where('inScan', '0');
        //                 $this->db->from($this->db->table_serial_no);
        //                 $result1 = $this->db->count_all_results();
        //                 // echo "<pre>";print_r($result1);exit();

        //                 $response['unUsedDevices'] = $result1;
        //           }
        //           else{
        //             $this->db->select('count(*)');
        //             $this->db->where('s_company_id', $user_company_id);
        //             $this->db->where('s_distributor_id', $user_id);
        //             $this->db->where('s_dealer_id', '0');
        //             $this->db->where('s_used', '0');
        //             $this->db->from($this->db->table_serial_no);
        //             $result1 = $this->db->count_all_results();

        //             $this->db->select('count(*)');
        //             $user_company_id = $this->session->userdata('user_company_id');
        //             if ((string)$user_type != '0' && (string)$user_type == '4') {
        //                 $this->db->where('s_company_id	', $user_company_id);
        //             }
        //             if ($user_type == '2') {
        //                 $this->db->where('s_distributor_id', $user_id);
        //                 $this->db->where('s_dealer_id', '0');
        //             }
        //             if ($user_type == '1') {
        //                 $this->db->where('s_dealer_id', $user_id);
        //             }     

        //             $this->db->where('s_used', '0');     
        //             $this->db->from($this->db->table_serial_no);
        //             $result = $this->db->count_all_results();
        //             // echo $this->db->last_query();exit();
        //             $response['unUsedDevices'] = $result;
        //         }

        //live
        //         $this->db->select('s_imei');   
        //         $this->db->from($this->db->table_serial_no);
        //         if($user_type == 1)
        // 		{
        //             $this->db->where('s_dealer_id', $user_id);
        //         }
        //         if($user_type == 2)
        // 		{
        //             $this->db->where('s_distributor_id', $user_id);
        //         }
        //         $this->db->where('s_used', 1);
        //         $results = $this->db->get();
        //         $results1 = $results->result();
        //         // echo "<pre>";print_r($results1);exit;

        //         // foreach($results1 as $row){
        //         //     $vehiclenos .= " '".$row->s_imei."',";
        //         // }
        //         // if($vehiclenos!=""){
        //         //     $vehiclenos = substr($vehiclenos,0,strlen($vehiclenos)-1);
        //         // }	
        //         // if($vehiclenos!="" ){
        //         //     $subquery101    = " where imei in (".$vehiclenos.") and lastupdatedTime >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) ";
        //         // }

        //         $otherdb = $this->load->database('tracking', TRUE);
        //         // $datas 	= $otherdb->query("select count(*) as count from gps_livetracking_data ".$subquery101."")->result();
        //         $datas   = $otherdb->query("select imei as s_imei from gps_livetracking_data where lastupdatedTime >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) ")->result();

        //         $imeiArray1 = array_map(function($item) {
        //             return $item->s_imei;
        //         }, $results1);

        //         $imeiArray2 = array_map(function($item) {
        //             return $item->s_imei;
        //         }, $datas);
        //         $matchingCount = count(array_intersect($imeiArray1, $imeiArray2));
        //         $response['live'] = $matchingCount;
        //         // $result1 = $datas[0]->count;

        //         //Offline 
        //         $currentDatetime = date('Y-m-d H:i:s');
        //         $oneHourAgo = date('Y-m-d H:i:s', strtotime('-1 hour'));
        //         // echo "<pre>";print_r($oneHourAgo);
        //         // echo "<pre>";print_r($currentDatetime);exit;

        //         $this->db->select('s_imei');   
        //         $this->db->from($this->db->table_serial_no);
        //         if($user_type == 1)
        // 		{
        //             $this->db->where('s_dealer_id', $user_id);
        //         }
        //         if($user_type == 2)
        // 		{
        //             $this->db->where('s_distributor_id', $user_id);
        //         }
        //         $this->db->where('s_used', 1);
        //         $results = $this->db->get();
        //         $results1 = $results->result();
        //         // echo "<pre>";print_r($this->db->last_query());exit;

        //         // foreach($results1 as $row) {
        //         //     $vehiclenos .= " '".$row->s_imei."',";
        //         // }
        //         // // echo "<pre>";print_r($vehiclenos);exit;
        //         // if($vehiclenos!=""){
        //         //     $vehiclenos = substr($vehiclenos,0,strlen($vehiclenos)-1);
        //         // }	

        //         // if($vehiclenos!="" ){
        //         //     $subquery101    = " where imei in (".$vehiclenos.") and lastUpdatedtime < DATE_SUB(NOW(), INTERVAL 5 MINUTE) ";
        //         // }
        //         // echo "<pre>";print_r($subquery101);exit;

        //         $otherdb = $this->load->database('tracking', TRUE);

        //         $datas 	= $otherdb->query("select  imei as s_imei from gps_livetracking_data where lastUpdatedtime < DATE_SUB(NOW(), INTERVAL 5 MINUTE) ")->result();
        //         // $datas 	= $otherdb->query("select count(*) as count from gps_livetracking_data")->result();

        //         $imeiArray1 = array_map(function($item) {
        //             return $item->s_imei;
        //         }, $results1);

        //         $imeiArray2 = array_map(function($item) {
        //             return $item->s_imei;
        //         }, $datas);
        //         $matchingCount = count(array_intersect($imeiArray1, $imeiArray2));

        //         // $result2 = $datas[0]->count;

        //         //proper flow
        //         // $response['offline'] = $result2;
        //         // echo "<pre>";print_r($response['offline']);exit;
        //         // alertnative flow
        //         $response['offline'] = $matchingCount;


        //new
        //TotalDevices 
        if ($user_type == 0 || $user_type == 4) {
            //used
            $this->db->select('count(*)');
            $this->db->from($this->db->table_serial_no);
            $this->db->where('s_distributor_id >', '0');
            $this->db->where('s_status', 0);   // not fault
            $this->db->where('inScan', 0);
            $result = $this->db->count_all_results();
            // echo $this->db->last_query();exit();

            //Unused
            $this->db->select('count(*)');
            $this->db->where('s_distributor_id', '0');
            $this->db->where('s_used', 0);   // not used by customer
            $this->db->where('s_status', 0); // not fault
            $this->db->where('inScan', 0);
            $this->db->from($this->db->table_serial_no);
            $result1 = $this->db->count_all_results();
            // echo $this->db->last_query();exit();

            //fault
            $this->db->select('count(*)');
            $this->db->from($this->db->table_serial_no);
            $this->db->where('s_distributor_id >', '0');
            $this->db->where('s_status', 1);
            $result2 = $this->db->count_all_results();

            //inScan
            $this->db->select('count(*)');
            $this->db->from($this->db->table_serial_no);
            $this->db->where('s_status', 0); // not fault
            $this->db->where('inScan', 1);   // not verified by device team
            $result3 = $this->db->count_all_results();

            $response['totalDevices'] = $result + $result1 + $result2 + $result3;
        }
        if ($user_type == 2) {
            //assigned
            $this->db->select('count(*)');
            $this->db->from($this->db->table_serial_no);
            $this->db->where('s_distributor_id', $user_id);
            $this->db->where('s_dealer_id != 0');
            $this->db->where('s_status', 0);   // not fault
            $this->db->where('inScan', 0);     // verified by device team
            $result = $this->db->count_all_results();

            //Unassigned
            $this->db->select('count(*)');
            $this->db->where('s_company_id', $user_company_id);
            $this->db->where('s_distributor_id', $user_id);
            $this->db->where('s_dealer_id', '0');
            $this->db->where('s_used', 0);   // not used by customer
            $this->db->where('s_status', 0); // not fault
            $this->db->where('inScan', 0);   // verified by device team
            $this->db->from($this->db->table_serial_no);
            $result1 = $this->db->count_all_results();

            //fault
            $this->db->select('count(*)');
            $this->db->from($this->db->table_serial_no);
            $this->db->where('s_distributor_id', $user_id);
            $this->db->where('s_status', 1);
            $result2 = $this->db->count_all_results();
            // echo "<pre>";print_r($result2);exit();

            $response['totalDevices'] = $result + $result1 + $result2;
        }
        if ($user_type == 1) {
            //assigned
            $this->db->select('count(*)');
            $this->db->from($this->db->table_serial_no);
            $this->db->where('s_dealer_id', $user_id);
            $this->db->where('s_used', 1);
            $this->db->where('s_status', 0);   // not fault
            $this->db->where('inScan', 0);     // verified by device team
            $result = $this->db->count_all_results();

            //Unassigned
            $this->db->select('count(*)');
            $this->db->where('s_company_id', $user_company_id);
            $this->db->where('s_dealer_id', $user_id);
            $this->db->where('s_used', 0);   // not used by customer
            $this->db->where('s_status', 0); // not fault
            $this->db->where('inScan', 0);   // verified by device team
            $this->db->from($this->db->table_serial_no);
            $result1 = $this->db->count_all_results();

            //fault
            $this->db->select('count(*)');
            $this->db->from($this->db->table_serial_no);
            $this->db->where('s_dealer_id', $user_id);
            $this->db->where('s_status', 1);
            $result2 = $this->db->count_all_results();
            // echo "<pre>";print_r($result);exit();

            $response['totalDevices'] = $result + $result1 + $result2;
        }

        //used Devices
        if ($user_type == '0' || $user_type == '2' || $user_type == '4') {
            $this->db->select('count(*)');
            $user_company_id = $this->session->userdata('user_company_id');
            if ((string)$user_type != '0' || $user_type == '4') {
                $this->db->where('s_company_id	', $user_company_id);
            }
            if ($user_type == '0' || $user_type == '4') {
                $this->db->where('s_distributor_id >', '0');
            } else if ($user_type == '2') {
                $this->db->where('s_distributor_id', $user_id);
                $this->db->where('s_dealer_id != 0');
            }
            $this->db->where('s_status', 0);
            $this->db->where('inScan', 0);
            $this->db->from($this->db->table_serial_no);
            $result = $this->db->count_all_results();
            // echo $this->db->last_query();exit();
            $response['usedDevices'] = $result;
        }
        if ($user_type == '1') {
            $this->db->select('count(*)');
            $this->db->where('s_dealer_id', $user_id);
            $this->db->where('s_used', '1');
            $this->db->where('inScan ', 0);
            $this->db->where('s_status ', 0);
            $this->db->from($this->db->table_serial_no);
            $result = $this->db->count_all_results();
            // echo $this->db->last_query();exit();
            $response['usedDevices'] = $result;
        }

        //unused devices
        if ($user_type == '0' || $user_type == '4') {
            $this->db->select('count(*)');
            $this->db->where('s_distributor_id', '0');
            $this->db->where('s_used', 0);
            $this->db->where('s_status', 0); // not fault
            $this->db->where('inScan', 0);
            $this->db->from($this->db->table_serial_no);
            $result1 = $this->db->count_all_results();
            // echo "<pre>";print_r($this->db->last_query());exit();

            $response['unUsedDevices'] = $result1;
        } else {

            $this->db->select('count(*)');
            $user_company_id = $this->session->userdata('user_company_id');
            if ((string)$user_type != '0' && (string)$user_type != '4') {
                $this->db->where('s_company_id	', $user_company_id);
            }
            if ($user_type == '2') {
                $this->db->where('s_distributor_id', $user_id);
                $this->db->where('s_dealer_id', '0');
            }
            if ($user_type == '1') {
                $this->db->where('s_dealer_id', $user_id);
            }

            $this->db->where('s_used', 0);
            $this->db->where('s_status', 0);
            $this->db->where('inScan', 0);
            $this->db->from($this->db->table_serial_no);
            $result = $this->db->count_all_results();
            // echo $this->db->last_query();exit();
            $response['unUsedDevices'] = $result;
        }

        //live
        if ($user_type == 1) {
            $subquery101 = " where dealer_id = " . $user_id . " and lastupdatedTime >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) ";
        }
        if ($user_type == 2) {
            $subquery101 = " where distributor_id = " . $user_id . " and lastupdatedTime >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) ";
        }
        if ($user_type == 4 || $user_type == 0) {
            $subquery101 = " where lastupdatedTime >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) ";
        }

        $otherdb = $this->load->database('tracking', true);
        // echo "<pre>";print_r("select count(*) as count from gps_livetracking_data ".$subquery101."");exit;
        $datas   = $otherdb->query("select count(*) as count from gps_livetracking_data " . $subquery101 . "")->result();
        $result1 = $datas[0]->count;
        $response['live'] = $result1;

        //offline
        if ($user_type == 1) {
            $subquery101 = " where dealer_id = " . $user_id . " and lastUpdatedtime < DATE_SUB(NOW(), INTERVAL 5 MINUTE)  ";
        }
        if ($user_type == 2) {
            $subquery101 = " where distributor_id = " . $user_id . " and lastUpdatedtime < DATE_SUB(NOW(), INTERVAL 5 MINUTE)  ";
        }
        if ($user_type == 4 || $user_type == 0) {
            $subquery101 = " where lastUpdatedtime < DATE_SUB(NOW(), INTERVAL 5 MINUTE)  ";
        }

        $otherdb = $this->load->database('tracking', true);
        // echo "<pre>";print_r("select count(*) as count from gps_livetracking_data ".$subquery101."");exit;
        $datas   = $otherdb->query("select count(*) as count from gps_livetracking_data " . $subquery101 . "")->result();
        $result1 = $datas[0]->count;
        $response['offline'] = $result1;

        //fault
        $this->db->select('count(*)');
        $this->db->from($this->db->table_serial_no);
        if ($user_type == 1) {
            $this->db->where('s_dealer_id', $user_id);
        }
        if ($user_type == 2) {
            $this->db->where('s_distributor_id', $user_id);
        }
        $this->db->where('s_status', 1);
        $result = $this->db->count_all_results();
        $response['faulty'] = $result;

        // $this->db->select('*');
        // $this->db->select('count(*)');
        // if ((string)$user_type != '0') {
        //     $this->db->where('veh_company_id', $user_company_id);
        //     $this->db->where('veh_created_user_id', $user_id);
        // }
        // $this->db->from($this->db->table_vehicle);
        // $result = $this->db->count_all_results();
        // $response['vehicle'] = $result;

        //model
        $this->db->select('*');
        if (isset($_GET['make_id']) && strlen($_GET['make_id']) > 0) {
            $this->db->where('ve_make_id', $_GET['make_id'], 'both');
        }
        if (isset($_GET['search']) && strlen($_GET['search']) > 0) {
            $this->db->or_like('ve_model_name', $_GET['search'], 'both');
        }

        $this->db->from($this->db->table_model);
        $result = $this->db->count_all_results();
        $response['model'] = $result;


        //make
        $this->db->select('v_make_id,v_make_name');
        if (isset($_GET['search']) && strlen($_GET['search']) > 0) {
            $this->db->or_like('v_make_name', $_GET['search'], 'both');
        }
        $this->db->from($this->db->table_make);
        $result = $this->db->count_all_results();
        $response['make'] = $result;

        // //today
        // // $this->db->select('*');
        // $this->db->select('count(*)');
        // $from = date('Y-m-d') . ' 00:00:00';
        // $to = date('Y-m-d') . ' 23:59:59';
        // $this->db->where('veh_create_date >=', $from);
        // $this->db->where('veh_create_date <=', $to);
        // if ((string)$user_type != '0') {
        //     $this->db->where('veh_company_id', $user_company_id);
        //     $this->db->where('veh_created_user_id', $user_id);
        // }
        // $this->db->from($this->db->table_vehicle);
        // $result = $this->db->count_all_results();
        // $response['today'] = $result;

        //today
        // $this->db->select('*');

        $user_type = $this->session->userdata('user_type');
        $user_company_id = $this->session->userdata('user_company_id');
        $this->db->select('*');
        if ((string)$user_type != '0') {
            $this->db->where('ser.s_company_id', $user_company_id);
        }
        $from = date('Y-m-d') . ' 00:00:00';
        $to = date('Y-m-d') . ' 23:59:59';
        $this->db->where('veh_create_date >=', $from);
        $this->db->where('veh_create_date <=', $to);

        $dealer_id = 0;
        $distributor_id = 0;
        if ($user_type == 1) {
            $dealer_id = $this->session->userdata('user_id');
        }

        if ($user_type == 2) {
            $distributor_id = $this->session->userdata('user_id');
        }
        // echo "<pre>";print_r($dealer_id);exit();
        if ((int)$dealer_id != 0) {
            $this->db->where('ser.s_dealer_id', $dealer_id);
        }
        if ((int)$distributor_id != 0) {
            $this->db->where('ser.s_distributor_id', $distributor_id);
        }

        $this->db->where('ser.s_used', 1);
        $this->db->from($this->db->table_vehicle . ' as veh');
        $this->db->join($this->db->table_serial_no . ' as ser', 'ser.s_serial_id = veh.veh_serial_no', 'left');
        $result = $this->db->count_all_results();

        // echo "<pre>";print_r($result);exit();
        // echo "<pre>";print_r($this->db->last_query());exit();
        $response['today'] = $result;
        // $this->db->select('*');

        //customer
        // $this->db->select('count(*)');
        // $this->db->from($this->db->table_customers);
        // $result = $this->db->count_all_results();
        // // echo "<pre>";print_r($this->db->last_query());exit();
        // $response['customer'] = $result;

        //customer
        if ($user_type == 0 || $user_type == 4) //SUPER ADMIN
        {
            $this->db->select('count(*)');
            $this->db->from($this->db->table_customers);
            $result = $this->db->count_all_results();
            $response['customer'] = $result;
        }
        if ($user_type == 1) //DEALER
        {
            $this->db->select('count(*)');
            $this->db->where('c_created_by', $dealer_id);
            $this->db->from($this->db->table_customers);
            $result = $this->db->count_all_results();
            $response['customer'] = $result;
        }
        if ($user_type == 2) //$user_type
        {
            $this->db->select('count(*)');
            $this->db->where('one.created_by', $distributor_id);
            $this->db->where('one.user_status', 1);
            $this->db->where('cus.c_customer_name is NOT NULL', NULL, FALSE);
            $this->db->where('cus.c_status ', 'ACTIVE');
            $this->db->from($this->db->table_users . ' as one');
            $this->db->limit($limit, $offset);
            $this->db->join($this->db->table_users . ' as two', 'one.user_id = two.created_by', 'left');
            $this->db->join($this->db->table_customers . ' as cus', 'one.user_id = cus.c_created_by', 'left');
            $result = $this->db->count_all_results();
            // echo "<pre>"; print_r($result); exit;
            // echo "<pre>";print_r($this->db->last_query());exit;
            $response['customer'] = $result;
        }


        //inScan
        $this->db->select('count(*)');
        $this->db->from($this->db->table_serial_no);
        $this->db->where('inScan', 1);
        $result = $this->db->count_all_results();
        $response['inScan'] = $result;

        //expiring certificate
        $user_type = $this->session->userdata('user_type');
        $user_company_id = $this->session->userdata('user_company_id');
        $this->db->select('veh.*,ser.s_serial_number,ser.s_distributor_id,dealuser.user_name as dealer_name ,disuser.user_name as distributor_name');
        $this->db->where('ser.s_used ', 1);
        $this->db->from($this->db->table_vehicle . ' as veh');
        $this->db->join($this->db->table_serial_no . ' as ser', 'ser.s_serial_id = veh.veh_serial_no', 'left');
        $this->db->join($this->db->table_users . ' as disuser', 'ser.s_distributor_id = disuser.user_id', 'left');
        $this->db->join($this->db->table_users . ' as dealuser', 'ser.s_dealer_id = dealuser.user_id', 'left');
        $currentDate  = date('Y-m-d H:i:s');
        $this->db->where('veh.validity_to >=', $currentDate);

        if ((string)$user_type != '0' && (string)$user_type != '4') {
            $this->db->where('ser.s_company_id', $user_company_id);
        }

        if ($dealer_id == "" && $distributor_id != "") {
            $this->db->where('ser.s_distributor_id ', $distributor_id);
        }
        if ($dealer_id != "" && $distributor_id != "") {
            $this->db->where('ser.s_distributor_id ', $distributor_id);
            $this->db->where('ser.s_dealer_id ', $dealer_id);
        }
        if (isset($_GET['s_country_id']) && strlen($_GET['s_country_id']) > 0) {
            $this->db->where('ser.s_country_id', $_GET['s_country_id'], 'both');
        }
        if (isset($_GET['s_state_id']) && strlen($_GET['s_state_id']) > 0) {
            $this->db->where('ser.s_state_id', $_GET['s_state_id'], 'both');
        }
        // $result = $this->db->get();
        $response['expiring_certi_counts'] = $this->db->count_all_results();
        // echo $this->db->last_query();exit;

        //Expired Certificate
        $this->db->select('count(*)');
        $this->db->from($this->db->table_vehicle . ' as veh');
        $this->db->join($this->db->table_serial_no . ' as ser', 'ser.s_serial_id = veh.veh_serial_no', 'left');
        // $this->db->select('veh.*,ser.s_serial_number,ser.s_distributor_id ,dealuser.user_name as dealer_name ,disuser.user_name as distributor_name');
        $this->db->join($this->db->table_users . ' as disuser', 'ser.s_distributor_id = disuser.user_id', 'left');
        $this->db->join($this->db->table_users . ' as dealuser', 'ser.s_dealer_id = dealuser.user_id', 'left');
        $this->db->where('ser.s_used ', 1);
        $currentDate  = date('Y-m-d H:i:s');
        $this->db->where('veh.validity_to <=', $currentDate);
        // echo "<pre>";print_r( "hai".($dealer_id));exit;

        if (strlen($_GET['search']) > 0) {
            $this->db->group_start();
            $this->db->like('ser.s_serial_number', $_GET['search'], 'both');
            $this->db->or_like('veh.veh_owner_phone', $_GET['search'], 'both');
            $this->db->group_end();
        }
        if ($_GET['dealer_id'] == "" && $_GET['distributor_id'] != "") {
            // $this->db->select('veh.*,ser.s_serial_number,ser.s_distributor_id ,disuser.user_name as distributor_name');
            // $this->db->join($this->db->table_users . ' as disuser', 'ser.s_distributor_id = disuser.user_id', 'left');
            $this->db->where('ser.s_distributor_id ', $_GET['distributor_id']);
        }
        if ($_GET['dealer_id'] != "" && $_GET['distributor_id'] != "") {
          $this->db->where('ser.s_distributor_id ', $_GET['distributor_id']);
            $this->db->where('ser.s_dealer_id ', $_GET['dealer_id']);
        }
        if (isset($_GET['s_country_id']) && strlen($_GET['s_country_id']) > 0) {
            $this->db->where('ser.s_country_id', $_GET['s_country_id'], 'both');
        }
        if (isset($_GET['s_state_id']) && strlen($_GET['s_state_id']) > 0) {
            $this->db->where('ser.s_state_id', $_GET['s_state_id'], 'both');
        }
        $response['expired_certi_counts'] = $this->db->count_all_results();

        // $result = $result->result_array();
        // echo "<pre>"; print_r($response);exit;
        return $response;
    }

    //     public function getCount(){

    //         $this->db->select('s_imei');   
    //         $this->db->from($this->db->table_serial_no);
    //         if($user_type == 1)
    // 		{
    //             $this->db->where('s_dealer_id', $user_id);
    //         }
    //         if($user_type == 2)
    // 		{
    //             $this->db->where('s_distributor_id', $user_id);
    //         }
    //         $this->db->where('s_used', 1);
    //         $results = $this->db->get();
    //         $results1 = $results->result();

    //         $otherdb = $this->load->database('tracking', TRUE);

    //         $datas 	= $otherdb->query("select imei as s_imei  from gps_livetracking_data where lastUpdatedtime < DATE_SUB(NOW(), INTERVAL 5 MINUTE)")->result();

    //         $imeiArray1 = array_map(function($item) {
    //             return $item->s_imei;
    //         }, $results1);

    //         $imeiArray2 = array_map(function($item) {
    //             return $item->s_imei;
    //         }, $datas);

    //         // Count the matching s_imei values
    //         $matchingCount = count(array_intersect($imeiArray1, $imeiArray2));
    //         echo "<pre>";print_r($matchingCount);exit;

    //     }

    public function qrcode($data, $id)
    {
        // echo "<pre>";print_r($data);exit;
        // $this->load->library('ciqrcode');
        // // echo "<pre>";print_r("gtest");exit;
        // header("Content-Type: image/png");
        // $params['data'] = $data;
        // return $this->ciqrcode->generate($params);
        // $this->load->library('ciqrcode');

        // header("Content-Type: image/png");
        // $params['data'] = 'This is a text to encode become QR Code';
        // return $this->ciqrcode->generate($params);
        $data = base_url() . "admin/downloadwebpdf?id=" . $id;
        $CI = &get_instance();
        $CI->load->library('ciqrcode');
        $params['data'] = $data;
        $params['savename'] = FCPATH . 'public/qrcodes/' . $id . '.png';
        $CI->ciqrcode->generate($params);

        $imagePath = 'public/qrcodes/' . $id . '.png';
        $imageName = $id . '.png';
        $path = "public/qrcodes";
        $deviceImage = $this->awsImageUpload($imagePath, $imageName, $path);
        return $deviceImage;
    }

    public function saveQrCode($qrparams)
    {
        $this->load->library('ciqrcode');
        $params['data'] = $qrparams;
        $params['level'] = 'H';
        $params['size'] = 10;
        $params['savename'] = FCPATH . 'public/qrcode/' . time() . '.png';
        $this->ciqrcode->generate($params);
        return $params['savename'];
    }

    public function totalDeviceCount($user_type, $user_id, $search)
    {
        if (strlen($search) > 0) {
            $this->db->select('count(*)');
            if ($user_type == 1) {
                $this->db->where('s_dealer_id', $user_id);
            }
            if ($user_type == 2) {
                $this->db->where('s_distributor_id', $user_id);
            }
            $this->db->group_start();
            $this->db->like('s_serial_number', $search, 'both');
            $this->db->or_like('s_imei', $search, 'both');
            $this->db->group_end();
            $this->db->from($this->db->table_serial_no);
            $response = $this->db->count_all_results();
        } else {
            $this->db->select('count(*)');
            $this->db->from($this->db->table_serial_no);
            if ($user_type == 2) {
                $this->db->where('s_distributor_id', $user_id);
            }
            if ($user_type == 1) {
                $this->db->where('s_dealer_id', $user_id);
            }

            $response = $this->db->count_all_results();
        }
        // echo "<pre>";print_r($this->db->last_query());exit();
        return $response;
    }


    public function getTotalDevice($user_type, $user_id, $search, $limit, $offset)
    {
        // echo "<pre>";print_r($offset);exit();
        if (strlen($search) > 0) {
            $this->db->select('ser.*,di.user_name as distributor_name,de.user_name as dealer_name,ser.s_distributor_id,ser.s_dealer_id,ser.s_used');
            if ($user_type == 1) {
                $this->db->where('ser.s_dealer_id', $user_id);
            }
            if ($user_type == 2) {
                $this->db->where('ser.s_distributor_id', $user_id);
            }
            $this->db->group_start();
            $this->db->like('ser.s_serial_number', $search, 'both');
            $this->db->or_like('ser.s_imei', $search, 'both');
            $this->db->group_end();
            $this->db->limit($limit, $offset);
            $this->db->from($this->db->table_serial_no . ' as ser');
            $this->db->join($this->db->table_users . ' as di', 'di.user_id = ser.s_distributor_id', 'left');
            $this->db->join($this->db->table_users . ' as de', 'de.user_id = ser.s_dealer_id', 'left');
            $result = $this->db->get();
            $result = $result->result_array();
        } else {
            $this->db->select('ser.*,di.user_name as distributor_name,de.user_name as dealer_name,ser.s_distributor_id,ser.s_dealer_id,ser.s_used');
            if ($user_type == 1) {
                $this->db->where('ser.s_dealer_id', $user_id);
            }
            if ($user_type == 2) {
                $this->db->where('ser.s_distributor_id', $user_id);
            }
            $this->db->limit($limit, $offset);
            $this->db->from($this->db->table_serial_no . ' as ser');
            $this->db->join($this->db->table_users . ' as di', 'di.user_id = ser.s_distributor_id', 'left');
            $this->db->join($this->db->table_users . ' as de', 'de.user_id = ser.s_dealer_id', 'left');
            $result = $this->db->get();
            $result = $result->result_array();
        }
        return $result;
    }

    public function getTechnicianListForDealer($id)
    {
        if ($id) {
            $this->db->select('user_id, user_name');
            $this->db->from($this->db->table_users);
            $this->db->where('created_by', $id);
            $this->db->where('user_type', 6);
            $result = $this->db->get();
            $result = $result->result_array();
        }
        // echo $this->db->last_query();exit();
        return $result;
    }

    public function getTechnicianName($techId)
    {
        $this->db->select('user_name');
        $this->db->where('user_id', $techId);
        $this->db->from($this->db->table_users);
        $result = $this->db->get();
        $result = $result->row_array();
        // echo "<pre> dscdss";print_r($result);exit();

        return $result;
    }


    public function updateRefreshTime()
    {
        $user_id = $this->session->userdata('user_id');
        $currentDate  = date('Y-m-d H:i:s');
        $insertRecords = array();
        $insertRecords['user_dashboard_refresh'] = $currentDate;
        $this->db->where('user_id', $user_id);
        $this->db->update($this->db->table_users, $insertRecords);
        return true;
    }



    // old
    // public function usedDeviceCount($user_type, $user_id, $search)
    // {

    //     if (strlen($search) > 0) {
    //         $this->db->select('count(*)');   
    //         $this->db->from($this->db->table_serial_no); 
    //         $this->db->group_start();
    //         $this->db->like('s_serial_number', $search, 'both');
    //         $this->db->or_like('s_imei', $search, 'both');
    //         $this->db->group_end();
    //         if($user_type == 2)
    //         {
    //             $this->db->where('s_distributor_id', $user_id);
    //         }
    //         if($user_type == 1)
    //         {
    //             $this->db->where('s_used', '1');    
    //             $this->db->where('s_dealer_id', $user_id);
    //         }
    //         if($user_type == 0 || $user_type == 4)
    //         {
    //             $this->db->where('s_distributor_id >',' 0');
    //         }
    //         $this->db->where('inScan !=', 1);
    //         $this->db->where('s_status !=', 1);
    //         $response = $this->db->count_all_results();

    //         // echo "<pre>";print_r($this->db->last_query());exit;

    //     }
    //     else{
    //         $this->db->select('count(*)');   
    //         $this->db->from($this->db->table_serial_no); 
    //         if($user_type == 2)
    //         {
    //             $this->db->where('s_distributor_id', $user_id);
    //         }
    //         if($user_type == 1)
    //         {
    //             $this->db->where('s_used', '1');    
    //             $this->db->where('s_dealer_id', $user_id);
    //         }
    //         if($user_type == 0 || $user_type == 4)
    //         {
    //             $this->db->where('s_distributor_id >',' 0');
    //         }
    //         $this->db->where('inScan !=', 1);
    //         $this->db->where('s_status !=', 1);
    //         $response = $this->db->count_all_results();

    //         // echo "<pre>";print_r($this->db->last_query());exit;

    //     }
    //     return $response;
    // }

    public function usedDeviceCount($user_type, $user_id, $search)
    {

        if (strlen($search) > 0) {
            $this->db->select('count(*)');
            $this->db->from($this->db->table_serial_no);
            $this->db->group_start();
            $this->db->like('s_serial_number', $search, 'both');
            $this->db->or_like('s_imei', $search, 'both');
            $this->db->group_end();
            if ($user_type == 2) {
                $this->db->where('s_dealer_id != 0');
                $this->db->where('s_distributor_id', $user_id);
            }
            if ($user_type == 1) {
                $this->db->where('s_used', '1');
                $this->db->where('s_dealer_id', $user_id);
            }
            if ($user_type == 0 || $user_type == 4) {
                $this->db->where('s_distributor_id >', ' 0');
            }
            $this->db->where('inScan ', 0);
            $this->db->where('s_status ', 0);
            $response = $this->db->count_all_results();

            // echo "<pre>";print_r($this->db->last_query());exit;

        } else {
            $this->db->select('count(*)');
            $this->db->from($this->db->table_serial_no);
            if ($user_type == 2) {
                $this->db->where('s_dealer_id != 0');
                $this->db->where('s_distributor_id', $user_id);
            }
            if ($user_type == 1) {
                $this->db->where('s_used', '1');
                $this->db->where('s_dealer_id', $user_id);
            }
            if ($user_type == 0 || $user_type == 4) {
                $this->db->where('s_distributor_id >', ' 0');
            }
            $this->db->where('inScan ', 0);
            $this->db->where('s_status ', 0);
            $response = $this->db->count_all_results();

            // echo "<pre>";print_r($this->db->last_query());exit;

        }
        return $response;
    }

    public function getUsedDevice($user_type, $user_id, $search, $limit, $offset)
    {
        // echo "<pre>";print_r($offset);exit();
        if (strlen($search) > 0) {
            $this->db->select('*');
            if ($user_type == 2) {
                $this->db->where('s_dealer_id != 0');
                $this->db->where('s_distributor_id', $user_id);
            }
            if ($user_type == 1) {
                $this->db->where('s_used', '1');
                $this->db->where('s_dealer_id', $user_id);
            }
            if ($user_type == 0 || $user_type == 4) {
                $this->db->where('s_distributor_id >', ' 0');
            }
            $this->db->where('inScan !=', 1);
            $this->db->where('s_status !=', 1);
            $this->db->group_start();
            $this->db->like('s_serial_number', $search, 'both');
            $this->db->or_like('s_imei', $search, 'both');
            $this->db->group_end();
            $this->db->from($this->db->table_serial_no);
            $result = $this->db->get();
            $result = $result->result_array();
            // echo "<pre>";print_r($this->db->last_query());exit;

            // echo "<pre>";print_r($result);exit();
        } else {
            $this->db->select('*');
            $this->db->from($this->db->table_serial_no);
            if ($user_type == 2) {
                $this->db->where('s_dealer_id != 0');
                $this->db->where('s_distributor_id', $user_id);
            }
            if ($user_type == 1) {
                $this->db->where('s_used', '1');
                $this->db->where('s_dealer_id', $user_id);
            }
            if ($user_type == 0 || $user_type == 4) {
                $this->db->where('s_distributor_id >', ' 0');
            }
            $this->db->where('inScan !=', 1);
            $this->db->where('s_status !=', 1);
            $this->db->limit($limit, $offset);
            $result = $this->db->get();
            $result = $result->result_array();
            // echo "<pre>";print_r($this->db->last_query());exit();
        }
        return $result;
    }

    // old
    // public function getUsedDevice($user_type, $user_id, $search, $limit, $offset)
    // {
    //     // echo "<pre>";print_r($offset);exit();
    //     if (strlen($search) > 0) {
    //         $this->db->select('*');   
    //         if($user_type == 2)
    //         {
    //             $this->db->where('s_distributor_id', $user_id);
    //         }
    //         if($user_type == 1)
    //         {
    //             $this->db->where('s_used', '1');    
    //             $this->db->where('s_dealer_id', $user_id);
    //         }
    //         if($user_type == 0 || $user_type == 4)
    //         {
    //             $this->db->where('s_distributor_id >',' 0');
    //         }
    //         $this->db->where('inScan !=', 1);
    //         $this->db->where('s_status !=', 1);
    //         $this->db->group_start();
    //         $this->db->like('s_serial_number', $search, 'both');
    //         $this->db->or_like('s_imei', $search, 'both');
    //         $this->db->group_end();
    //         $this->db->from($this->db->table_serial_no); 
    //         $result = $this->db->get();
    //         $result = $result->result_array();
    //         // echo "<pre>";print_r($this->db->last_query());exit;

    //         // echo "<pre>";print_r($result);exit();
    //     }
    //     else{
    //         $this->db->select('*');   
    //         $this->db->from($this->db->table_serial_no); 
    //         if($user_type == 2)
    //         {
    //             $this->db->where('s_distributor_id', $user_id);
    //         }
    //         if($user_type == 1)
    //         {
    //             $this->db->where('s_used', '1');    
    //             $this->db->where('s_dealer_id', $user_id);
    //         }
    //         if($user_type == 0 || $user_type == 4)
    //         {
    //             $this->db->where('s_distributor_id >',' 0');
    //         }
    //         $this->db->where('inScan !=', 1);
    //         $this->db->where('s_status !=', 1);
    //         $this->db->limit($limit, $offset);
    //         $result = $this->db->get();
    //         $result = $result->result_array();
    //         // echo "<pre>";print_r($this->db->last_query());exit();
    //     }
    //         return $result;

    // }

    // old
    //  public function unUsedDeviceCount($user_type, $user_id, $search)
    // {
    //     $user_company_id = $this->session->userdata('user_company_id');
    //     if ($user_type == '0' || $user_type == '4') {
    //             $this->db->select('count(*)');
    //             $this->db->where('s_distributor_id', '0');
    //             if ($user_type != '0') {
    //                 $this->db->where('s_company_id', $user_company_id);
    //             }
    //             if (strlen($search) > 0) {
    //                 $this->db->group_start();
    //                 $this->db->like('s_serial_number', $search, 'both');
    //                 $this->db->or_like('s_imei', $search, 'both');
    //                 $this->db->group_end();
    //             }
    //             $this->db->where('s_used', '0');
    //             $this->db->where('inScan', '0');
    //             $this->db->from($this->db->table_serial_no);
    //             $result = $this->db->count_all_results();
    //             // echo "<pre>";print_r($result1);exit();
    //     }
    //     else{

    //         $this->db->select('count(*)');
    //         $user_company_id = $this->session->userdata('user_company_id');
    //         if ((string)$user_type != '0') {
    //             $this->db->where('s_company_id	', $user_company_id);
    //         }
    //         if ($user_type == '2') {
    //             $this->db->where('s_distributor_id', $user_id);
    //             $this->db->where('s_dealer_id', '0');
    //         }
    //         if ($user_type == '1') {
    //             $this->db->where('s_dealer_id', $user_id);
    //         }     
    //         if (strlen($search) > 0) {
    //             $this->db->group_start();
    //             $this->db->like('s_serial_number', $search, 'both');
    //             $this->db->or_like('s_imei', $search, 'both');
    //             $this->db->group_end();
    //         }

    //             $this->db->where('inScan', '0');
    //             $this->db->where('s_used', '0');     
    //         $this->db->from($this->db->table_serial_no);
    //         $result = $this->db->count_all_results();
    //         // echo "<pre>";print_r($this->db->last_query());exit();
    //     }

    //     return $result;
    // }

    public function unUsedDeviceCount($user_type, $user_id, $search)
    {
        $user_company_id = $this->session->userdata('user_company_id');
        if ($user_type == '0' || $user_type == '4') {
            $this->db->select('count(*)');
            $this->db->where('s_distributor_id', '0');
            if ($user_type != '0' || $user_type == '4') {
                $this->db->where('s_company_id', $user_company_id);
            }
            if (strlen($search) > 0) {
                $this->db->group_start();
                $this->db->like('s_serial_number', $search, 'both');
                $this->db->or_like('s_imei', $search, 'both');
                $this->db->group_end();
            }
            $this->db->where('s_status', '0');
            $this->db->where('s_used', '0');
            $this->db->where('inScan', '0');
            $this->db->from($this->db->table_serial_no);
            $result = $this->db->count_all_results();
            // echo "<pre>";print_r($result1);exit();
        } else {

            $this->db->select('count(*)');
            $user_company_id = $this->session->userdata('user_company_id');
            if ((string)$user_type != '0' || (string)$user_type == '4') {
                $this->db->where('s_company_id	', $user_company_id);
            }
            if ($user_type == '2') {
                $this->db->where('s_distributor_id', $user_id);
                $this->db->where('s_dealer_id', '0');
            }
            if ($user_type == '1') {
                $this->db->where('s_dealer_id', $user_id);
            }
            if (strlen($search) > 0) {
                $this->db->group_start();
                $this->db->like('s_serial_number', $search, 'both');
                $this->db->or_like('s_imei', $search, 'both');
                $this->db->group_end();
            }
            $this->db->where('s_status', '0');
            $this->db->where('inScan', '0');
            $this->db->where('s_used', '0');
            $this->db->from($this->db->table_serial_no);
            $result = $this->db->count_all_results();
            // echo "<pre>";print_r($this->db->last_query());exit();
        }

        return $result;
    }

    // old
    // public function getUnUsedDevice($user_type, $user_id, $search, $limit, $offset)
    // {
    //         // echo "<pre>";print_r($offset);exit();
    //     $user_company_id = $this->session->userdata('user_company_id');
    //     if ($user_type == '0' || $user_type == '4') {
    //         $this->db->select('*');
    //         $this->db->where('s_distributor_id', '0');
    //         if ($user_type != '0') {
    //             $this->db->where('s_company_id', $user_company_id);
    //         }
    //         if (strlen($search) > 0) {
    //             $this->db->group_start();
    //             $this->db->like('s_serial_number', $search, 'both');
    //             $this->db->or_like('s_imei', $search, 'both');
    //             $this->db->group_end();
    //         }
    //         $this->db->where('s_used', '0');
    //         $this->db->where('inScan', '0');
    //         $this->db->limit($limit, $offset);
    //         $this->db->from($this->db->table_serial_no);
    //         $result = $this->db->get();
    //         $result = $result->result_array(); 
    //         // echo "<pre>";print_r($this->db->last_query());exit;           
    //         // echo "<pre>";print_r($result1);exit();
    // }
    // else{

    //     $this->db->select('*');
    //     $user_company_id = $this->session->userdata('user_company_id');
    //     if ((string)$user_type != '0') {
    //         $this->db->where('s_company_id	', $user_company_id);
    //     }
    //     if ($user_type == '2') {
    //         $this->db->where('s_distributor_id', $user_id);
    //         $this->db->where('s_dealer_id', '0');
    //     }
    //     if ($user_type == '1') {
    //         $this->db->where('s_dealer_id', $user_id);
    //     }     
    //     if (strlen($search) > 0) {
    //         $this->db->group_start();
    //         $this->db->like('s_serial_number', $search, 'both');
    //         $this->db->or_like('s_imei', $search, 'both');
    //         $this->db->group_end();
    //     }

    //     $this->db->where('s_used', '0');   
    //     $this->db->where('inScan', '0');
    //     $this->db->limit($limit, $offset);
    //     $this->db->from($this->db->table_serial_no);
    //     $result = $this->db->get();
    //     $result = $result->result_array();                    
    //     // echo "<pre>";print_r($this->db->last_query());exit();
    // }
    //         return $result;
    // }

    public function getUnUsedDevice($user_type, $user_id, $search, $limit, $offset)
    {
        // echo "<pre>";print_r($offset);exit();
        $user_company_id = $this->session->userdata('user_company_id');
        if ($user_type == '0' || $user_type == '4') {
            $this->db->select('*');
            $this->db->where('s_distributor_id', '0');
            if ($user_type != '0') {
                $this->db->where('s_company_id', $user_company_id);
            }
            if (strlen($search) > 0) {
                $this->db->group_start();
                $this->db->like('s_serial_number', $search, 'both');
                $this->db->or_like('s_imei', $search, 'both');
                $this->db->group_end();
            }
            $this->db->where('s_status', '0');
            $this->db->where('s_used', '0');
            $this->db->where('inScan', '0');
            $this->db->limit($limit, $offset);
            $this->db->from($this->db->table_serial_no);
            $result = $this->db->get();
            $result = $result->result_array();
            // echo "<pre>";print_r($this->db->last_query());exit;           
            // echo "<pre>";print_r($result1);exit();
        } else {

            $this->db->select('*');
            $user_company_id = $this->session->userdata('user_company_id');
            if ((string)$user_type != '0') {
                $this->db->where('s_company_id	', $user_company_id);
            }
            if ($user_type == '2') {
                $this->db->where('s_distributor_id', $user_id);
                $this->db->where('s_dealer_id', '0');
            }
            if ($user_type == '1') {
                $this->db->where('s_dealer_id', $user_id);
            }
            if (strlen($search) > 0) {
                $this->db->group_start();
                $this->db->like('s_serial_number', $search, 'both');
                $this->db->or_like('s_imei', $search, 'both');
                $this->db->group_end();
            }
            $this->db->where('s_status', '0');
            $this->db->where('s_used', '0');
            $this->db->where('inScan', '0');
            $this->db->limit($limit, $offset);
            $this->db->from($this->db->table_serial_no);
            $result = $this->db->get();
            $result = $result->result_array();
            // echo "<pre>";print_r($this->db->last_query());exit();
        }
        return $result;
    }

    public function offlineDeviceCount($user_type, $user_id, $search, $hour, $hour2)
    {

        if (strlen($search) > 0) {
            // echo "<pre>";print_r($hour);exit();

            $this->db->select('count(*)');
            if ($user_type == 1) {
                $this->db->where('ser.s_dealer_id', $user_id);
            }
            if ($user_type == 2) {
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
            // echo "<pre>";print_r($results1);exit();

            if ($results1 > 0) {
                if ($user_type == 1) {
                    if ($hour2 == '') {
                        $subquery101 = " where  (imei like '%$search%' OR vehicleRegnumber like '%$search%') AND dealer_id = " . $user_id . " and lastupdatedTime < DATE_SUB(NOW(), INTERVAL " . $hour . ")";
                    } else {
                        $subquery101 = " where  (imei like '%$search%' OR vehicleRegnumber like '%$search%') AND dealer_id = " . $user_id . " and lastupdatedTime < DATE_SUB(NOW(), INTERVAL " . $hour . ")  AND lastupdatedTime > DATE_SUB(NOW(), INTERVAL " . $hour2 . ") ";
                    }
                }
                if ($user_type == 2) {
                    if ($hour2 == '') {
                        $subquery101 = " where (imei like '%$search%' OR vehicleRegnumber like '%$search%') AND distributor_id = " . $user_id . " and lastupdatedTime < DATE_SUB(NOW(), INTERVAL " . $hour . ")";
                    } else {
                        $subquery101 = " where (imei like '%$search%' OR vehicleRegnumber like '%$search%') AND distributor_id = " . $user_id . " and lastupdatedTime < DATE_SUB(NOW(), INTERVAL " . $hour . ")  AND lastupdatedTime > DATE_SUB(NOW(), INTERVAL " . $hour2 . ")";
                    }
                }
                if ($user_type == 4 || $user_type == 0) {
                    if ($hour2 == '') {
                        $subquery101 = " where (imei like '%$search%' OR vehicleRegnumber like '%$search%') AND lastupdatedTime < DATE_SUB(NOW(), INTERVAL " . $hour . ")";
                    } else {
                        $subquery101 = " where (imei like '%$search%' OR vehicleRegnumber like '%$search%') AND lastupdatedTime < DATE_SUB(NOW(), INTERVAL " . $hour . ")  AND lastupdatedTime > DATE_SUB(NOW(), INTERVAL " . $hour2 . ") ";
                    }
                }

                $otherdb = $this->load->database('tracking', true);
                // echo "<pre>";print_r("select count(*) as count from gps_livetracking_data ".$subquery101."");exit;
                $datas   = $otherdb->query("select count(*) as count from gps_livetracking_data " . $subquery101 . "")->result();
                $response = $datas[0]->count;
            } else {
                $response = 0;
            }
        } else {
            if ($user_type == 1) {
                if ($hour2 == '') {
                    $subquery101 = " where  (imei like '%$search%' OR vehicleRegnumber like '%$search%') AND dealer_id = " . $user_id . " and lastupdatedTime < DATE_SUB(NOW(), INTERVAL " . $hour . ")";
                } else {
                    $subquery101 = " where  (imei like '%$search%' OR vehicleRegnumber like '%$search%') AND dealer_id = " . $user_id . " and lastupdatedTime < DATE_SUB(NOW(), INTERVAL " . $hour . ")  AND lastupdatedTime > DATE_SUB(NOW(), INTERVAL " . $hour2 . ") ";
                }
            }
            if ($user_type == 2) {
                if ($hour2 == '') {
                    $subquery101 = " where (imei like '%$search%' OR vehicleRegnumber like '%$search%') AND distributor_id = " . $user_id . " and lastupdatedTime < DATE_SUB(NOW(), INTERVAL " . $hour . ")";
                } else {
                    $subquery101 = " where (imei like '%$search%' OR vehicleRegnumber like '%$search%') AND distributor_id = " . $user_id . " and lastupdatedTime < DATE_SUB(NOW(), INTERVAL " . $hour . ")  AND lastupdatedTime > DATE_SUB(NOW(), INTERVAL " . $hour2 . ")";
                }
            }
            if ($user_type == 4 || $user_type == 0) {
                if ($hour2 == '') {
                    $subquery101 = " where (imei like '%$search%' OR vehicleRegnumber like '%$search%') AND lastupdatedTime < DATE_SUB(NOW(), INTERVAL " . $hour . ")";
                } else {
                    $subquery101 = " where (imei like '%$search%' OR vehicleRegnumber like '%$search%') AND lastupdatedTime < DATE_SUB(NOW(), INTERVAL " . $hour . ")  AND lastupdatedTime > DATE_SUB(NOW(), INTERVAL " . $hour2 . ") ";
                }
            }

            $otherdb = $this->load->database('tracking', true);
            // echo "<pre>";print_r("select count(*) as count from gps_livetracking_data ".$subquery101."");exit;
            $datas   = $otherdb->query("select count(*) as count from gps_livetracking_data " . $subquery101 . "")->result();
            $response = $datas[0]->count;
        }

        return $response;
    }

    public function getofflineDevice($user_type, $user_id, $search, $limit, $offset, $hour, $hour2)
    {
        // echo "<pre>";print_r($user_id);exit();
        if (strlen($search) > 0) {

            $this->db->select('count(*)');
            if ($user_type == 1) {
                $this->db->where('ser.s_dealer_id', $user_id);
            }
            if ($user_type == 2) {
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

            if ($results1 > 0) {
                if ($user_type == 1) {
                    if ($hour2 == '') {
                        $subquery101 = " where  (imei like '%$search%' OR vehicleRegnumber like '%$search%') AND dealer_id = " . $user_id . " and lastupdatedTime < DATE_SUB(NOW(), INTERVAL " . $hour . ")";
                    } else {
                        $subquery101 = " where  (imei like '%$search%' OR vehicleRegnumber like '%$search%') AND dealer_id = " . $user_id . " and lastupdatedTime < DATE_SUB(NOW(), INTERVAL " . $hour . ")  AND lastupdatedTime > DATE_SUB(NOW(), INTERVAL " . $hour2 . ") ";
                    }
                }
                if ($user_type == 2) {
                    if ($hour2 == '') {
                        $subquery101 = " where (imei like '%$search%' OR vehicleRegnumber like '%$search%') AND distributor_id = " . $user_id . " and lastupdatedTime < DATE_SUB(NOW(), INTERVAL " . $hour . ")";
                    } else {
                        $subquery101 = " where (imei like '%$search%' OR vehicleRegnumber like '%$search%') AND distributor_id = " . $user_id . " and lastupdatedTime < DATE_SUB(NOW(), INTERVAL " . $hour . ")  AND lastupdatedTime > DATE_SUB(NOW(), INTERVAL " . $hour2 . ")";
                    }
                }
                if ($user_type == 4 || $user_type == 0) {
                    if ($hour2 == '') {
                        $subquery101 = " where (imei like '%$search%' OR vehicleRegnumber like '%$search%') AND lastupdatedTime < DATE_SUB(NOW(), INTERVAL " . $hour . ")";
                    } else {
                        $subquery101 = " where (imei like '%$search%' OR vehicleRegnumber like '%$search%') AND lastupdatedTime < DATE_SUB(NOW(), INTERVAL " . $hour . ")  AND lastupdatedTime > DATE_SUB(NOW(), INTERVAL " . $hour2 . ") ";
                    }
                }

                $otherdb = $this->load->database('tracking', true);
                // echo "<pre>";print_r("select *  from gps_livetracking_data ".$subquery101."");exit;
                $datas     = $otherdb->query("select * from gps_livetracking_data " . $subquery101 . " order by lastupdatedTime desc LIMIT " . $limit . " OFFSET " . $offset . " ")->result_array();

                // echo "<pre>";print_r($datas);exit;
            } else {
                $datas = [];
            }
        } else {

            if ($user_type == 1) {
                if ($hour2 == '') {
                    $subquery101 = " where  (imei like '%$search%' OR vehicleRegnumber like '%$search%') AND dealer_id = " . $user_id . " and lastupdatedTime < DATE_SUB(NOW(), INTERVAL " . $hour . ")";
                } else {
                    $subquery101 = " where  (imei like '%$search%' OR vehicleRegnumber like '%$search%') AND dealer_id = " . $user_id . " and lastupdatedTime < DATE_SUB(NOW(), INTERVAL " . $hour . ")  AND lastupdatedTime > DATE_SUB(NOW(), INTERVAL " . $hour2 . ") ";
                }
            }
            if ($user_type == 2) {
                if ($hour2 == '') {
                    $subquery101 = " where (imei like '%$search%' OR vehicleRegnumber like '%$search%') AND distributor_id = " . $user_id . " and lastupdatedTime < DATE_SUB(NOW(), INTERVAL " . $hour . ")";
                } else {
                    $subquery101 = " where (imei like '%$search%' OR vehicleRegnumber like '%$search%') AND distributor_id = " . $user_id . " and lastupdatedTime < DATE_SUB(NOW(), INTERVAL " . $hour . ")  AND lastupdatedTime > DATE_SUB(NOW(), INTERVAL " . $hour2 . ")";
                }
            }
            if ($user_type == 4 || $user_type == 0) {
                if ($hour2 == '') {
                    $subquery101 = " where (imei like '%$search%' OR vehicleRegnumber like '%$search%') AND lastupdatedTime < DATE_SUB(NOW(), INTERVAL " . $hour . ")";
                } else {
                    $subquery101 = " where (imei like '%$search%' OR vehicleRegnumber like '%$search%') AND lastupdatedTime < DATE_SUB(NOW(), INTERVAL " . $hour . ")  AND lastupdatedTime > DATE_SUB(NOW(), INTERVAL " . $hour2 . ") ";
                }
            }

            // echo "<pre>";print_r("select *  from gps_livetracking_data ".$subquery101."");exit;
            $otherdb = $this->load->database('tracking', TRUE);
            $datas     = $otherdb->query("select * from gps_livetracking_data " . $subquery101 . " order by lastupdatedTime desc LIMIT " . $limit . " OFFSET " . $offset . " ")->result_array();
            // echo "<pre>";print_r($otherdb->last_query());exit();
        }
        return $datas;
    }


    public function liveDeviceCount($user_type, $user_id, $search)
    {
        if (strlen($search) > 0) {
            $this->db->select('count(*)');
            $this->db->from($this->db->table_serial_no);
            if ($user_type == 1) {
                $this->db->where('s_dealer_id', $user_id);
            }
            if ($user_type == 2) {
                $this->db->where('s_distributor_id', $user_id);
            }
            $this->db->where('s_imei', $search);
            $results1 = $this->db->count_all_results();

            if ($results1 > 0) {
                if ($user_type == 1) {
                    $subquery101 = " where  (imei like '%$search%' OR vehicleRegnumber like '%$search%') AND dealer_id = " . $user_id . " and lastupdatedTime >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) ";
                }
                if ($user_type == 2) {
                    $subquery101 = " where (imei like '%$search%' OR vehicleRegnumber like '%$search%') AND distributor_id = " . $user_id . " and lastupdatedTime >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) ";
                }
                if ($user_type == 4 || $user_type == 0) {
                    $subquery101 = " where (imei like '%$search%' OR vehicleRegnumber like '%$search%') AND lastupdatedTime >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) ";
                }

                $otherdb = $this->load->database('tracking', true);
                // echo "<pre>";print_r("select count(*) as count from gps_livetracking_data ".$subquery101."");exit;
                $datas   = $otherdb->query("select count(*) as count from gps_livetracking_data " . $subquery101 . "")->result();
                $response = $datas[0]->count;
            } else {
                $response = 0;
            }
        } else {
            if ($user_type == 1) {
                $subquery101 = " where dealer_id = " . $user_id . " and lastupdatedTime >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) ";
            }
            if ($user_type == 2) {
                $subquery101 = " where distributor_id = " . $user_id . " and lastupdatedTime >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) ";
            }
            if ($user_type == 4 || $user_type == 0) {
                $subquery101 = " where lastupdatedTime >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) ";
            }

            $otherdb = $this->load->database('tracking', true);
            // echo "<pre>";print_r("select count(*) as count from gps_livetracking_data ".$subquery101."");exit;
            $datas   = $otherdb->query("select count(*) as count from gps_livetracking_data " . $subquery101 . "")->result();
            $response = $datas[0]->count;
        }
        // echo "<pre>";print_r($response);exit; 

        return $response;
    }

    public function getliveDevice($user_type, $user_id, $search, $limit, $offset)
    {
        if (strlen($search) > 0) {

            $this->db->select('count(*)');
            if ($user_type == 1) {
                $this->db->where('ser.s_dealer_id', $user_id);
            }
            if ($user_type == 2) {
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

            if ($results1 > 0) {
                if ($user_type == 1) {
                    $subquery101 = " where (imei like '%$search%' OR vehicleRegnumber like '%$search%') AND dealer_id = " . $user_id . " and lastupdatedTime >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) ";
                }
                if ($user_type == 2) {
                    $subquery101 = " where (imei like '%$search%' OR vehicleRegnumber like '%$search%') AND distributor_id = " . $user_id . " and lastupdatedTime >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) ";
                }
                if ($user_type == 4 || $user_type == 0) {
                    $subquery101 = " where (imei like '%$search%' OR vehicleRegnumber like '%$search%') AND lastupdatedTime >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) ";
                }

                $otherdb = $this->load->database('tracking', true);
                // echo "<pre>";print_r("select count(*) as count from gps_livetracking_data ".$subquery101."");exit;
                $datas   = $otherdb->query("select * from gps_livetracking_data " . $subquery101 . "")->result_array();

                // echo "<pre>";print_r($datas);exit;
            } else {
                $datas = [];
            }
        } else {

            if ($user_type == 1) {
                $subquery101 = " where dealer_id = " . $user_id . " and lastupdatedTime >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) order by lastUpdatedtime DESC LIMIT " . $limit . " OFFSET " . $offset;
            }
            if ($user_type == 2) {
                $subquery101 = " where distributor_id = " . $user_id . " and lastupdatedTime >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) order by lastUpdatedtime DESC LIMIT " . $limit . " OFFSET " . $offset;
            }
            if ($user_type == 4 || $user_type == 0) {
                $subquery101 = " where lastupdatedTime >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) order by lastUpdatedtime DESC LIMIT " . $limit . " OFFSET " . $offset;
            }

            // echo "<pre>";print_r("select count(*) as count from gps_livetracking_data ".$subquery101."");exit;
            $otherdb = $this->load->database('tracking', TRUE);
            $datas     = $otherdb->query("select * from gps_livetracking_data " . $subquery101 . "")->result_array();
            // echo "<pre>";print_r($otherdb->last_query());exit();
        }
        return $datas;
    }

    public function inscanDeviceCount($user_type, $user_id, $search)
    {
        if (strlen($search) > 0) {
            $this->db->select('count(*)');
            $this->db->from($this->db->table_serial_no);
            $this->db->group_start();
            $this->db->like('s_serial_number', $search, 'both');
            $this->db->or_like('s_imei', $search, 'both');
            $this->db->group_end();
            $this->db->where('inScan', 1);
            $result = $this->db->count_all_results();
            // echo "<pre>";print_r($this->db->last_query());exit;

        } else {
            $this->db->select('count(*)');
            $this->db->from($this->db->table_serial_no);
            $this->db->where('inScan', 1);
            $result = $this->db->count_all_results();
        }
        return $result;
    }

    public function getInscanDevice($user_type, $user_id, $search, $limit, $offset)
    {
        // echo "<pre>";print_r($offset);exit();
        if (strlen($search) > 0) {
            $this->db->select('s_serial_number,s_imei,s_iccid');
            $this->db->from($this->db->table_serial_no);
            $this->db->group_start();
            $this->db->like('s_serial_number', $search, 'both');
            $this->db->or_like('s_imei', $search, 'both');
            $this->db->group_end();
            $this->db->where('inScan', 1);
            $this->db->limit($limit, $offset);
            $results = $this->db->get();
            $results1 = $results->result_array();
            // echo "<pre>";print_r($this->db->last_query());exit;

        } else {
            $this->db->select('s_serial_number,s_imei,s_iccid');
            $this->db->from($this->db->table_serial_no);
            $this->db->where('inScan', 1);
            $this->db->limit($limit, $offset);
            $results = $this->db->get();
            $results1 = $results->result_array();
        }
        return $results1;
    }


    public function getCustomerDetails($customer_id)
    {
        if ($customer_id) {
            $this->db->select('*');
            $this->db->from($this->db->table_customers);
            // $this->db->like('c_phone', $phone, 'both');
            $this->db->where('c_customer_id', $customer_id);
            $result = $this->db->get();
            $result = $result->row_array();
        }
        // echo $this->db->last_query();exit();
        return $result;
    }


    public function alltechnicianList($user_id)
    {
        $user_type = $this->session->userdata('user_type');
        // echo "<pre>";print_r($user_type);exit();

        $this->db->select('user_id, user_name');
        $this->db->from($this->db->table_users);

        if ($user_type == 0 || $user_type == 1) {
            $this->db->where("created_by", $user_id);
        }
        $this->db->where('user_type', 6);
        $this->db->where('user_status', 1);
        $result = $this->db->get();
        $result = $result->result_array();
        // echo $this->db->last_query();exit();
        // echo "<pre>";print_r($result1);exit();

        return $result;
    }

    public function fetch_list_of_dealers_by_distributorId($distributor_id)
    {
        $this->db->select('user_id,user_name');
        $this->db->where('user_status', 1);
        $this->db->where('user_type ', 1);
        $this->db->where('created_by', $distributor_id);

        $this->db->from($this->db->table_users);
        $result = $this->db->get();
        $result = $result->result_array();
        // echo $this->db->last_query();exit();
        return $result;
    }

    public function totalfaultyDevice($user_type, $user_id, $search)
    {
        // echo "<pre>";print_r($user_id);exit();
        $this->db->select('count(*)');
        $this->db->from($this->db->table_serial_no);
        if ($user_type == 1) {
            $this->db->where('s_dealer_id', $user_id);
        }
        if ($user_type == 2) {
            $this->db->where('s_distributor_id', $user_id);
        }
        if (strlen($search) > 0) {
            $this->db->group_start();
            $this->db->like('s_serial_number', $search, 'both');
            $this->db->or_like('s_imei', $search, 'both');
            $this->db->group_end();
        }
        $this->db->where('s_status', 1);
        $result = $this->db->count_all_results();
        // echo "<pre>";print_r($this->db->last_query());exit();
        return $result;
    }

    public function getfaultyDevice($user_type, $user, $search, $limit, $offset)
    {
        // echo "<pre>";print_r($user);exit();

        $this->db->select('ser.*,di.user_name as distributor_name,de.user_name as dealer_name,ser.s_distributor_id,ser.s_dealer_id,ser.s_used');
        if ($user_type == 1) {
            $this->db->where('s_dealer_id', $user);
        }
        if ($user_type == 2) {
            $this->db->where('s_distributor_id', $user);
        }
        if (strlen($search) > 0) {
            $this->db->group_start();
            $this->db->like('ser.s_serial_number', $search, 'both');
            $this->db->or_like('ser.s_imei', $search, 'both');
            $this->db->group_end();
        }
        $this->db->where('s_status', 1);
        $this->db->limit($limit, $offset);
        $this->db->from($this->db->table_serial_no . ' as ser');
        $this->db->join($this->db->table_users . ' as di', 'di.user_id = ser.s_distributor_id', 'left');
        $this->db->join($this->db->table_users . ' as de', 'de.user_id = ser.s_dealer_id', 'left');
        $result = $this->db->get();
        $result = $result->result_array();
        // echo "<pre>";print_r($this->db->last_query());exit();
        // echo "<pre>";print_r($result);exit();
        return $result;
    }

    public function allRtoNumbers()
    {
        // $this->db->select('*');
        $this->db->select('rto_no, rto_place, rto_pwd, rto_number, state_name, state_id');
        $this->db->from($this->db->table_rto);
        $result = $this->db->get();
        $result = $result->result_array();
        return $result;
    }


    public function allStateList()
    {
        //$this->db->select('*');
        $this->db->select('id, s_name');
        $this->db->from($this->db->table_state);
        $this->db->order_by("s_name", "DESC");
        $result = $this->db->get();
        $result = $result->result_array();
        return $result;
    }

    public function activeStateList()
    {
        //$this->db->select('*');
        $this->db->select('id, s_name');
        $this->db->from($this->db->table_state);
        $this->db->where('launch_state', 1);
        $this->db->order_by("s_name", "DESC");
        $result = $this->db->get();
        $result = $result->result_array();
        return $result;
    }

    public function allCountryList()
    {
        //$this->db->select('*');
        $this->db->select('c_id, c_name');
        $this->db->from($this->db->table_country);
        $this->db->order_by("c_name", "DESC");
        $result = $this->db->get();
        $result = $result->result_array();
        return $result;
    }

    public function allStatesList_copy()
    {
        // $this->db->select('*');
        $this->db->select('state_id,state_name,state_code,country_id');
        $this->db->from($this->db->table_states);
        $result = $this->db->get();
        $result = $result->result_array();
        return $result;
    }

    public function allStatesList()
    {
        // $this->db->select('*');
        $this->db->select('id,s_name,s_key,country_id');
        $this->db->from($this->db->table_state);
        $this->db->order_by("s_name", "DESC");
        $result = $this->db->get();
        $result = $result->result_array();
        return $result;
    }

    public function removeWhiteSpace()
    {
        $this->db->select('veh_id,veh_rc_no');
        $this->db->from($this->db->table_vehicle);
        $result = $this->db->get();
        $result = $result->result_array();
        foreach ($result as $key => $value) {
            $val = preg_replace('/\s+/', '', $value['veh_rc_no']);
            $insertRecords = array();
            $insertRecords['veh_rc_no'] = $val;
            $this->db->where('veh_id', $value['veh_id']);
            $this->db->update($this->db->table_vehicle, $insertRecords);
        }
    }


    public function fetch($whereFrom, $whereTo, $fields, $table, $single = true)
    {
        $this->db->select($fields);
        $this->db->where($whereFrom, $whereTo);
        $this->db->from($table);
        $result = $this->db->get();
        if ($single) {
            $result = $result->row_array();
        } else {
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


    public function allModelList($makeID = 0)
    {
        $this->db->select('ve_model_id,ve_model_name');
        if ((string)$makeID != '0') {
            $this->db->where('ve_make_id ', $makeID);
        }
        $this->db->from($this->db->table_model);
        $result = $this->db->get();
        $result = $result->result_array();
        return $result;
    }

    public function allSerialList($byUser = 0, $stateID)
    {
        $user_type = $this->session->userdata('user_type');
        $user_company_id = $this->session->userdata('user_company_id');
        $this->db->select('s_serial_id,s_serial_number,s_imei,s_iccid,s_company_id');
        if ((string)$user_type != '0' && (string)$user_type != '4') {
            $this->db->where('s_company_id', $user_company_id);
        }
        if ((string)$user_type != '0' && (string)$user_type != '4') {
            $this->db->where('s_user_id ', $byUser);
        }
        if (isset($stateID) && strlen($stateID) > 0) {
            $this->db->where('s_state_id', $stateID);
        }
        if ((string)$user_type != '0' && (string)$user_type != '4') {
            $this->db->where('s_dealer_id ', $byUser);
        } else {
            $this->db->where('s_dealer_id  !=', 0);
        }
        $this->db->where('s_status ', '0');  //workable
        $this->db->where('s_used ', '0');
        $this->db->where("(s_used IS NULL OR s_used = 0)");
        $this->db->from($this->db->table_serial_no);
        $result = $this->db->get();
        $result = $result->result_array();
        // echo "<pre>";print_r($this->db->last_query());exit;
        return $result;
    }


    public function fetchSavedHistory()
    {
        // echo "haii";exit;
        // $this->db->select('*');
        $this->db->select('id,imei,date,start_time,end_time');
        $this->db->from($this->db->table_imei_history);
        $result = $this->db->get();
        $result = $result->result_array();

        return $result;
    }

    public function fetch_list_of_users($params, $needAdmin, $avoid = 0)
    {
        $this->db->select('user_id,CONCAT(user_name, " - ", user_phone) AS user_name');
        $this->db->where('user_status', 1);
        if (isset($avoid) && (int)$avoid > 0) {
            $this->db->where('user_id  !=', $avoid);
        }
        if (isset($params['user_company_id']) && strlen($params['user_company_id']) > 0) {
            $this->db->where('user_company_id ', $params['user_company_id']);
        }
        if (isset($params['user_type']) && (int)$params['user_type'] > 0) {
            $this->db->where('user_type ', $params['user_type']);
        }

        $user_type = $this->session->userdata('user_type');
        $user_id = $this->session->userdata('user_id');
        if ($user_type == 2) {
            $this->db->where('user_id', $user_id);
        } else if ((string)$needAdmin === '1') {
            $this->db->or_where('user_id ', 1);
        }

        $this->db->from($this->db->table_users);
        $result = $this->db->get();
        $result = $result->result_array();
        //echo $this->db->last_query();exit();
        return $result;
    }

    public function fetch_list_of_dealers($params, $needAdmin, $avoid = 0)
    {
        $this->db->select('user_id,CONCAT(user_name, " - ", user_phone) AS user_name');
        $this->db->where('user_status', 1);
        if (isset($avoid) && (int)$avoid > 0) {
            $this->db->where('user_id  !=', $avoid);
        }
        if (isset($params['user_company_id']) && strlen($params['user_company_id']) > 0) {
            $this->db->where('user_company_id ', $params['user_company_id']);
        }
        if (isset($params['user_type']) && (int)$params['user_type'] > 0) {
            $this->db->where('user_type ', $params['user_type']);
        }

        $user_type = $this->session->userdata('user_type');
        $user_id = $this->session->userdata('user_id');
        if ($user_type == 2) {
            $this->db->where('created_by', $user_id);
        } else if ((string)$needAdmin === '1') {
            $this->db->or_where('user_id ', 1);
        }

        $this->db->from($this->db->table_users);
        $result = $this->db->get();
        $result = $result->result_array();
        // echo $this->db->last_query();exit();
        return $result;
    }


    public function fetch_list_of_active_dealers($params, $needAdmin, $avoid = 0)
    {
        $this->db->select('user_id,user_name');
        $this->db->where('user_status', 1);
        if (isset($avoid) && (int)$avoid > 0) {
            $this->db->where('user_id  !=', $avoid);
        }
        if (isset($params['user_company_id']) && strlen($params['user_company_id']) > 0) {
            $this->db->where('user_company_id ', $params['user_company_id']);
        }
        if (isset($params['user_type']) && (int)$params['user_type'] > 0) {
            $this->db->where('user_type ', $params['user_type']);
        }

        $user_type = $this->session->userdata('user_type');
        $user_id = $this->session->userdata('user_id');
        if ($user_type == 2) {
            $this->db->where('created_by', $user_id);
        } else if ((string)$needAdmin === '1') {
            $this->db->or_where('user_id ', 1);
        }

        $this->db->from($this->db->table_users);
        $result = $this->db->get();
        $result = $result->result_array();
        //echo $this->db->last_query();exit();
        return $result;
    }


    public function fetch_list_of_products($params)
    {
        $this->db->select('p_product_id,p_product_name');

        if (isset($params['p_company_id']) && strlen($params['p_company_id']) > 0) {
            $this->db->where('p_company_id ', $params['p_company_id']);
        }
        $this->db->from($this->db->table_products);
        $result = $this->db->get();
        $result = $result->result_array();
        //echo $this->db->last_query();exit();
        return $result;
    }

    public function fetch_list_of_unassigned_serial_numbers($params)
    {
        $this->db->select('s_serial_id,s_serial_number,s_imei,s_mobile,admin_price,distributor_price,dealer_price,p_unit_price');

        if (isset($params['s_company_id']) && strlen($params['s_company_id']) > 0) {
            $this->db->where('s_company_id ', $params['s_company_id']);
        }
        if (isset($params['s_product_id']) && strlen($params['s_product_id']) > 0) {
            $this->db->where('s_product_id ', $params['s_product_id']);
        }
        //$this->db->where('s_user_type', 0);
        //$this->db->or_where('s_user_type', 2);
        $this->db->from($this->db->table_serial_no);
        $this->db->join($this->db->table_products, 'p_product_id = s_product_id', 'left');
        $result = $this->db->get();
        $result = $result->result_array();
        //echo $this->db->last_query();exit();
        return $result;
    }

    public function fetch_list_of_selected_serial_numbers($params)
    {
        $this->db->select('s_serial_id,s_serial_number,s_imei,s_mobile,admin_price,distributor_price,dealer_price,s_distributor_id');

        // if(isset($params['s_company_id']) && strlen($params['s_company_id'])>0)
        // {
        // 	$this->db->where('s_company_id ',$params['s_company_id']);
        // }
        // if(isset($params['s_product_id']) && strlen($params['s_product_id'])>0)
        // {
        // 	$this->db->where('s_product_id ',$params['s_product_id']);
        // }
        //var_dump($params['serial_ids']);exit;
        $this->db->where_in('s_serial_id', $params['serial_ids']);
        //$this->db->or_where('s_user_type', 2);
        $this->db->from($this->db->table_serial_no);
        $result = $this->db->get();
        $result = $result->result_array();
        //echo $this->db->last_query();exit();
        return $result;
    }

    //		public function fetch_imei_numbers($params)
    //		{
    //			$this->db->select('*');
    //			$this->db->where('s_imei', $params);
    //			$this->db->or_where('s_serial_number', $params);
    //			$this->db->from($this->db->table_serial_no);
    //            $result1 = $this->db->get();
    //			$result1 = $result1->result_array();
    //			//echo"<pre>";print_r($result1);exit();
    //
    //
    //			if(count($result1) < 1) {
    //				$result["status"] = "N";
    //				$result["data"] = "Cannot find this device-IMEI in the System";
    //			} else {
    //				// $params = $result0[0]['s_imei'];
    //				// $this->db->select('*');
    //				// $this->db->where('veh_serial_no',$result0[0]["s_serial_id"]);
    //
    //				// $this->db->from($this->db->table_vehicle);
    //				// $result1 = $this->db->get();
    //				// $result1 = $result1->result_array();
    //				// $res  =array();
    //				// print_r($result1);exit();
    //				// if(count($result1) < 1) {
    //				// 	$result["status"] = "N";
    //				// 	$result["data"] = "The Certificate is not issued for this IMEI";
    //				// }
    //				// else
    //				// {
    //
    //
    //					// $this->db->select('*');
    //					// $this->db->from($this->db->table_serial_no);
    //					// //$this->db->where('s_used','=', 1);
    //					// $this->db->where('s_imei',$params);
    //					// $this->db->or_where('s_serial_number', $params);
    //		   			// $result1 = $this->db->get();
    //					// $result1 = $result1->result_array();
    //
    //					//echo "<pre>";print_r($result1);exit();
    //
    //					//if($result1[0]['s_used'] == 1)
    //
    //
    //					if($result1[0]['s_distributor_id'] != 0)
    //					{
    //
    //						$this->db->select('user_name as distributerName');
    //						$this->db->from($this->db->table_users);
    //						$this->db->where('user_id', $result1[0]['s_distributor_id']);
    //			            $distributerName = $this->db->get();
    //						$distributerName = $distributerName->result_array();
    //						//print_r($distributerName);exit();
    //					}
    //					if($result1[0]['s_distributor_id'] != 0 and $result1[0]['s_dealer_id'] != 0)
    //					{
    //
    //
    //						$this->db->select('user_name as dealerName');
    //						$this->db->from($this->db->table_users);
    //						$this->db->where('user_id', $result1[0]['s_dealer_id']);
    //			            $dealerName = $this->db->get();
    //						$dealerName = $dealerName->result_array();
    //						//print_r($dealerName);exit();
    //					}
    //
    //						/*$this->db->select('user_name as customerName');
    //						$this->db->from($this->db->table_users);
    //						$this->db->where('user_id', $result1[0]['customer_id']);
    //			            $customerName = $this->db->get();
    //						$customerName = $customerName->result_array();
    //						print_r($customerName);exit();*/
    //
    //						$otherdb = $this->load->database('tracking', TRUE);
    //						$otherdb->select('*');
    //						$otherdb->where('imei',$params);
    //						$otherdb->from($otherdb->table_tracking);
    //						$result2 = $otherdb->get();
    //						$result2 = $result2->result_array();
    //					   // echo "<pre>";print_r($result2);exit();
    //
    //					if($result1[0]['s_distributor_id'] != 0 and $result1[0]['s_dealer_id'] != 0 and  $result2[0]['customerID'] != 0)
    //					{
    //					    $this->db->select('c_customer_name as customerName');
    //						$this->db->from($this->db->table_customers);
    //						$this->db->where('c_customer_id', $result2[0]['customerID']);
    //			            $customerName2 = $this->db->get();
    //						$customerName2 = $customerName2->result_array();
    //						//print_r($customerName2);exit();
    //					}
    //
    //						$result1 = !empty($result1[0])?$result1[0]:array();
    //						$result2 = !empty($result2[0])?$result2[0]:array();
    //						$distributerName = !empty($distributerName[0])?$distributerName[0]:array();
    //						$dealerName = !empty($dealerName[0])?$dealerName[0]:array();
    //						//$customerName = !empty($customerName[0])?$customerName[0]:array();
    //						$customerName2 = !empty($customerName2[0])?$customerName2[0]:array();
    //
    //						$result3=array_merge($result2,$result1);
    //						$result4=array_merge($distributerName,$dealerName);
    //						$result5=array_merge($result4,$customerName2);
    //						$result6=array_merge($result3,$result5);
    //						//print_r($result2);exit();
    //						if(count($result2) < 1 && count($result1) < 1) {
    //							$result["status"] = "N";
    //							$result["data"] = "The Device not connected to server";
    //						} else {
    //
    //							$result["status"] = "Y";
    //							$res[] =$result6;
    //
    //							$result["data"] = $res;
    //							//print_r($res);exit();
    //						}
    //
    //
    //
    //					/*$otherdb = $this->load->database('tracking', TRUE);
    //					$otherdb->select('*');
    //					$otherdb->where('imei',$params);
    //					$otherdb->from($otherdb->table_tracking);
    //					$result2 = $otherdb->get();
    //					$result2 = $result2->result_array();
    //					echo "<pre>";print_r($result2);exit();
    //					if(count($result2) < 1) {
    //						$result["status"] = "N";
    //						$result["data"] = "The Device not connected to server";
    //					} else {
    //						$result["status"] = "Y";
    //						$result["data"] = $result2;
    //					}
    //					*/
    //
    //				// }
    //
    //			}
    //			//echo $this->db->last_query();exit();
    //			return $result;
    //		}


    public function ota_param($imei)
    {
        // error_reporting(E_ALL);
        // ini_set('display_errors', '1');

        // $DB2   = $this->load->database('PSDN_TN', TRUE);
        $mainDB = $this->load->database('default', TRUE);
        $result = $mainDB
            ->select('s_imei, s_state_id, s_country_id, state.s_key') // Add columns from the state table as needed
            ->from($mainDB->table_serial_no)
            ->where('s_imei', $imei)
            ->join($mainDB->table_state, 'state.id = ' . $mainDB->table_serial_no . '.s_state_id', 'left') // Adjust the join condition accordingly
            ->get()
            ->row_array();
        // echo "<pre>";var_dump($result['s_key']);exit;
        $data = array();

        if (!empty($result) && $result['s_key']!="" && ($result['s_key']) != null) {
            //echo "<pre>";print_r($result['s_imei']);exit;
            $databaseName = 'PSDN_' . $result['s_key'];
            $trimmedStr = trim($databaseName);
            $DB2 = $this->load->database($trimmedStr, TRUE);
            $query = "select * from public.tbl_alert_data where imei = '" . $imei . "' AND isclosed = false ORDER BY start_time DESC ";
            // echo "<pre>";print_r($query);exit;
            return $data = $DB2->query($query)->result_array();
        }
    }

    public function check_ota($imei)
    {
        // error_reporting(E_ALL);
        $data = array();
        // $DB2   = $this->load->database('PSDN_TN', TRUE);
        $mainDB = $this->load->database('default', TRUE);
        $result = $mainDB
            ->select('s_imei, s_state_id, s_country_id, state.s_key') // Add columns from the state table as needed
            ->from($mainDB->table_serial_no)
            ->where('s_imei', $imei)
            ->join($mainDB->table_state, 'state.id = ' . $mainDB->table_serial_no . '.s_state_id', 'left') // Adjust the join condition accordingly
            ->get()
            ->row_array();
        if (!empty($result)) {

            $databaseName = 'PSDN_' . $result['s_key'];
            $trimmedStr = trim($databaseName);

            $DB2 = $this->load->database($trimmedStr, TRUE);

            // $query = "select count(*) as count from public.tbl_ota_param where imei = '" . $imei . "' AND resphandling = 'CMD TAG' ";
            $query = "select count(*) as count from public.tbl_ota_param where 'IMEI' = '" . $imei . "' AND 'RespHandling' = 'CMD TAG' ";

            // echo $query;exit;
            $data = $DB2->query($query)->row();
        }
        //    print_r($data);exit;

        return $data;
    }

    public function insert_ota($imei)
    {
        // error_reporting(E_ALL);
        // print_r($imei); exit;
        $mainDB = $this->load->database('default', TRUE);
        $result = $mainDB->select('s_imei, s_state_id, s_country_id, state.s_key') // Add columns from the state table as needed
            ->from($mainDB->table_serial_no)
            ->where('s_imei', $imei)
            ->join($mainDB->table_state, 'state.id = ' . $mainDB->table_serial_no . '.s_state_id', 'left') // Adjust the join condition accordingly
            ->get()
            ->row_array();
        $databaseName = 'PSDN_' . $result['s_key'];
        $trimmedStr = trim($databaseName);

        $DB2 = $this->load->database($trimmedStr, TRUE);
        //  $DB2   = $this->load->database('postgre_db', TRUE);

        // print_r($DB2); exit;

        $datetimeObject    = new DateTime();
        // $datetimeObject    = new DateTime($datetimeString);
        $formattedDatetime = $datetimeObject->format('Y-m-d H:i:s');

        $query = "INSERT INTO public.tbl_ota_param (\"imei\",\"createdTime\",\"isSent\",\"type\",\"lastUpdatedBy\",\"resphandling\") VALUES ('" . $imei . "','" . $formattedDatetime . "',0,'CLEAR','','CMD TAG')";
        // echo $query;exit;

        $data = $DB2->query($query);
        return true;
    }

    public function count_ota_param($imei)
    {
        $mainDB = $this->load->database('default', TRUE);
        $result = $mainDB->select('s_imei, s_state_id, s_country_id, state.s_key') // Add columns from the state table as needed
            ->from($mainDB->table_serial_no)
            ->where('s_imei', $imei)
            ->join($mainDB->table_state, 'state.id = ' . $mainDB->table_serial_no . '.s_state_id', 'left') // Adjust the join condition accordingly
            ->get()
            ->row_array();
        $databaseName = 'PSDN_' . $result['s_key'];
        $trimmedStr = trim($databaseName);

        $DB2 = $this->load->database($trimmedStr, TRUE);
        // $DB2   = $this->load->database('postgre_db', TRUE);
        $query = "select count(*) as count from public.tbl_alert_data where imei = '" . $imei . "' AND isclosed = false ";
        // echo $query;exit;
        $data  = $DB2->query($query)->result();
        $count = $data[0]->count;
        return $count;
    }

    public function saveHisData($params, $start_date, $start_time, $end_time)
    {
        //        $DB2 = $this->load->database('postgre_db', TRUE);
        //
        //        $from = $start_date.' '.$start_time;
        //        $to = $start_date.' '.$end_time;
        //       $query  = 'INSERT INTO `ci_saved_history`(`id`, `imei`, `date`, `start_time`, `end_time`, `created_at`) VALUES (1,\'32423423\',\'2010-09-23\',\'00:00\',\'23:00\',\'2010-09-23 00:00\')';




        $cust_datains = array("imei" => $params, "date" => $start_date, "start_time" => $start_time, "end_time" => $end_time);
        $data  = $this->db->insert("ci_saved_history", $cust_datains);



        if (count($data) == 0) {
            $resultData["status"] = "N";
            $resultData["data"] = "Can't save history";
        } else {

            $resultData["status"] = "Y";
            // $res[] =$serialTableArray;

            $resultData["data"] = "Inserted successfully";
            //
            //                exit();
        }

        return $resultData;
    }


    public function fetch_imei_data($params, $start_date, $start_time, $end_time)
    {
        // $DB2 = $this->load->database('postgre_db', TRUE);
        $mainDB = $this->load->database('default', TRUE);
        $result = $mainDB
            ->select('s_imei, s_state_id, s_country_id, state.s_key') // Add columns from the state table as needed
            ->from($mainDB->table_serial_no)
            ->where('s_imei', $params)
            ->join($mainDB->table_state, 'state.id = ' . $mainDB->table_serial_no . '.s_state_id', 'left') // Adjust the join condition accordingly
            ->get()
            ->row_array();
        $databaseName = 'PSDN_' . $result['s_key'];
        $trimmedStr = trim($databaseName);
        $DB2 = $this->load->database($trimmedStr, TRUE);

        $from = $start_date . ' ' . $start_time;
        $to = $start_date . ' ' . $end_time;
        // echo "<pre>";print_r($databaseName);exit;
        $query = "select * from public.tbl_health_data where imei = '" . $params . "' AND server_reached between '" . $from . "' AND '" . $to . "'  ORDER BY id DESC LIMIT 5";
        //       echo $query;exit;
        $data = $DB2->query($query)->result();
        //

        $rowData = "";
        for ($i = 0; $i < count($data); $i++) {
            $rowData = $rowData . "<tr>";

            $rowData = $rowData . '<td>' . $data[$i]->vendor_name . '</td>';
            $rowData = $rowData . '<td>' . $data[$i]->firmware_v . '</td>';
            $rowData = $rowData . '<td>' . $data[$i]->imei . '</td>';
            $rowData = $rowData . '<td>' . $data[$i]->server_reached . '</td>';
            $rowData = $rowData . '<td>' . $data[$i]->battery_percentage . '</td>';
            $rowData = $rowData . '<td>' . $data[$i]->battery_threshold . '</td>';
            $rowData = $rowData . '<td>' . $data[$i]->memory_percentage . '</td>';
            $rowData = $rowData . '<td>' . $data[$i]->data_interval . '</td>';
            $rowData = $rowData . '<td>' . $data[$i]->input_value . '</td>';
            $rowData = $rowData . '<td>' . $data[$i]->output_value . '</td>';
            $rowData = $rowData . '<td>' . $data[$i]->adc_one . '</td>';
            $rowData = $rowData . '<td>' . $data[$i]->adc_two . '</td>';


            $rowData = $rowData . "</tr>";
        }


        if (count($data) == 0) {
            $resultData["status"] = "N";
            $resultData["data"] = "Health data not found on server";
        } else {

            $resultData["status"] = "Y";
            // $res[] =$serialTableArray;

            $resultData["data"] = $rowData;
            //
            //                exit();
        }

        return $resultData;
    }


    public function fetch_imei_history($params, $imei_count, $start_date, $start_time, $end_time)
    {
        $from = $start_date . ' ' . $start_time;
        $to = $start_date . ' ' . $end_time;

        $parts = explode("-", $start_date);
        $year  = $parts[0];
        $month = $parts[1];
        $date  = $parts[2];

        $table_name_check = "tbl_trackinghistory_" . $month . $year;

        // Get the current month and year
        $current_month = date('m');
        $current_year = date('Y');

        //new date format
        $reqDate = $year . '-' . $month . '-' . $date;

        // Check if the table exists
        // $DB2 = $this->load->database('postgre_db', TRUE);
        $mainDB = $this->load->database('default', TRUE);
        $result = $mainDB
            ->select('s_imei, s_state_id, s_country_id, state.s_key') // Add columns from the state table as needed
            ->from($mainDB->table_serial_no)
            ->where('s_imei', $params)
            ->join($mainDB->table_state, 'state.id = ' . $mainDB->table_serial_no . '.s_state_id', 'left') // Adjust the join condition accordingly
            ->get()
            ->row_array();
        $databaseName = 'PSDN_' . $result['s_key'];
        $trimmedStr = trim($databaseName);
        $DB2 = $this->load->database($trimmedStr, TRUE);
        $table_exists = $DB2->table_exists($table_name_check);


        if ($table_exists) {
            $table_name = "tbl_trackinghistory_" . $month . $year;
        } else if ($month == $current_month && $year == $current_year) {
            $table_name = "tbl_trackingalldatas";
        } else {
            $table_name = "";
            $resultData["status"] = "N";
            $resultData["data"] = "History not found for given duration";
            return $resultData;
        }

        $query = "select packet_type,packet_status,CONCAT (gps_date,' ', gps_time) as gps_sent,ignition,vehicle_speed,gsm_signal_strength,alert_id,latitude,lat_direction,longitude,long_direction,altitude,main_power_status,vehicle_mode from public." . $table_name . " where imei = '" . $params . "' AND server_reached between '" . $from . "' AND '" . $to . "' ORDER BY gps_sent desc";
        // $query = "select * from public.tbl_trackingalldatas where imei = '" . $params . "' AND server_reached between '" . $from . "' AND '" . $to. "' ORDER BY gps_sent desc";
        $data = $DB2->query($query)->result();
        // echo "<pre>";print_r($data);exit();

        $rowData = "";
        $coordinates = array();
        for ($i = 0; $i < count($data); $i++) {
            $rowData = $rowData . "<tr>";

            $rowData = $rowData . '<td>' . ($i + 1) . '</td>';
            $rowData = $rowData . '<td>' . $data[$i]->packet_type . '</td>';
            $rowData = $rowData . '<td>' . $data[$i]->packet_status . '</td>';
            $rowData = $rowData . '<td>' . $data[$i]->gps_sent . '</td>';
            $rowData = $rowData . '<td>' . $data[$i]->ignition . '</td>';
            $rowData = $rowData . '<td>' . $data[$i]->vehicle_speed . '</td>';
            $rowData = $rowData . '<td>' . $data[$i]->gsm_signal_strength . '</td>';
            $rowData = $rowData . '<td>' . $data[$i]->alert_id . '</td>';
            $rowData = $rowData . '<td>' . $data[$i]->latitude . '</td>';
            $rowData = $rowData . '<td>' . $data[$i]->lat_direction . '</td>';
            $rowData = $rowData . '<td>' . $data[$i]->longitude . '</td>';
            $rowData = $rowData . '<td>' . $data[$i]->long_direction . '</td>';
            $rowData = $rowData . '<td>' . $data[$i]->altitude . '</td>';
            $rowData = $rowData . '<td>' . $data[$i]->main_power_status . '</td>';
            $rowData = $rowData . '<td>' . $data[$i]->vehicle_mode . '</td>';
            // $rowData = $rowData . '<td>' . $data[$i]->server_reached . '</td>';
            // $rowData = $rowData . '<td>' . $data[$i]->ignition . '</td>';
            // $rowData = $rowData . '<td>' . $data[$i]->battery_status . '</td>';
            // $rowData = $rowData . '<td>' . $data[$i]->emergency_status . '</td>';

            $rowData = $rowData . "</tr>";
            $latlng = new \stdClass();
            $latlng->lat = (float)$data[$i]->latitude;
            $latlng->lng = (float)$data[$i]->longitude;

            array_push($coordinates, $latlng);
        }

        if (count($data) == 0) {
            $resultData["status"] = "N";
            $resultData["data"] = "History not found for given duration";
        } else {

            $resultData["status"] = "Y";
            // $res[] =$serialTableArray;
            $resultData["latlng"] = $coordinates;
            $resultData["data"] = $rowData;
            //
            //                exit();
        }

        return $resultData;
    }

    public function assign_state($params)
    {
        $data = '';
        if (isset($params['imei_no']) && $params['state_id']) {
            $insertRecords['s_state_id'] = isset($params['state_id']) ? $params['state_id'] : null;
            $insertRecords['s_country_id'] = 1;
            $this->db->where('s_imei', $params['imei_no']);
            $data = $this->db->update($this->db->table_serial_no, $insertRecords);
        }
        return $data;
    }

    public function fetch_imei_numbers($params)
    {
        $this->db->select('*');
        $this->db->where('s_imei', $params);
        // $this->db->or_where('s_serial_number', $params);
        $this->db->from($this->db->table_serial_no);
        $result1 = $this->db->get();
        $result1 = $result1->result_array();
        if (count($result1) < 1) {
            $result["status"] = "N";
            $result["data"] = "Cannot find this History in the System";
        } else {
            // echo "<pre>"; print_r($result1); exit;

            $serialTableArray = $result1[0];
            $serialTableArray["stockBy"] = "-";
            $serialTableArray["distributerName"] = "-";
            $serialTableArray["dealerName"] = "-";
            $this->db->select('user_name as stockBy');
            $this->db->from($this->db->table_users);
            $this->db->where('user_id', $result1[0]['s_created_by']);
            $stockBy = $this->db->get();
            $stockBy = $stockBy->result_array();
            $serialTableArray["stockBy"] = $stockBy[0]["stockBy"];
            $serialTableArray['certificateLink'] = "-";
            if ($result1[0]['s_distributor_id'] != 0) {
                $this->db->select('user_name as distributerName');
                $this->db->from($this->db->table_users);
                $this->db->where('user_id', $result1[0]['s_distributor_id']);
                $distributerName = $this->db->get();
                $distributerName = $distributerName->result_array();
                $serialTableArray["distributerName"] = $distributerName[0]["distributerName"];
            }
            if ($result1[0]['s_distributor_id'] != 0 and $result1[0]['s_dealer_id'] != 0) {
                $this->db->select('user_name as dealerName');
                $this->db->from($this->db->table_users);
                $this->db->where('user_id', $result1[0]['s_dealer_id']);
                $dealerName = $this->db->get();
                $dealerName = $dealerName->result_array();
                $serialTableArray["dealerName"] = $dealerName[0]["dealerName"];
            }

            $otherdb = $this->load->database('tracking', TRUE);
            $otherdb->select('*');
            $otherdb->where('imei', $params);
            $otherdb->from($otherdb->table_trackings);
            $result2 = $otherdb->get();
            $result2 = $result2->result_array();
            // echo "<pre>"; print_r($result2); exit;
            if ($result2) {

                if ($result1[0]['s_distributor_id'] != 0 and $result1[0]['s_dealer_id'] != 0 and $result2[0]['customerID'] != 0) {

                    $this->db->select('*');
                    $this->db->from($this->db->table_vehicle);
                    // $this->db->where('veh_rc_no', $serialTableArray['vehicleRegnumber']);
                    $this->db->where('veh_serial_no', $serialTableArray['s_serial_id']);
                    $customerName2 = $this->db->get();
                    $customerName2 = $customerName2->result_array();

                    if (count($customerName2) > 1) {
                        $result["status"] = "N";
                        $result["data"] = "Vehicle Number Duplicate with vehicle " . $customerName2[0]['veh_id'] . ',' . $customerName2[1]['veh_id'];
                        return $result;
                    }
                    $pdfEncode = base64_encode(base64_encode(base64_encode($customerName2[0]['veh_id'])));
                    $href = base_url() . "admin/downloadwebpdf?id=" . $pdfEncode;
                    $serialTableArray['certificateLink'] = $href;
                    if (!empty($customerName2[0])) {
                        $serialTableArray = array_merge($serialTableArray, $customerName2[0]);
                    }
                    // echo "<pre>"; print_r(($customerName2)); exit;

                    $this->db->select('c_email');
                    $this->db->from($this->db->table_customers);
                    $this->db->where('c_customer_id', $result2[0]['customerID']);
                    $customerName = $this->db->get();
                    $customerEmail = $customerName->result_array();
                    $serialTableArray["customerEmail"] = $customerEmail[0]["c_email"];
                    if(!empty($result2[0])){
                        // array_push($serialTableArray, $result2[0]);
                        $serialTableArray = array_merge($result2[0], $serialTableArray);

                    }

                    // echo "<pre>"; print_r($serialTableArray); exit;

                }
            } else {
                $this->db->select('*');
                $this->db->from($this->db->table_vehicle);
                // $this->db->where('veh_rc_no', $serialTableArray['vehicleRegnumber']);
                $this->db->where('veh_serial_no', $serialTableArray['s_serial_id']);
                $customerName2 = $this->db->get();
                $customerName2 = $customerName2->result_array();
                if (!empty($customerName2)) {
                    $pdfEncode = base64_encode(base64_encode(base64_encode($customerName2[0]['veh_id'])));
                    $href = base_url() . "admin/downloadwebpdf?id=" . $pdfEncode;
                    $serialTableArray['certificateLink'] = $href;
                    $serialTableArray = array_merge($customerName2[0], $serialTableArray);
                }
                // echo "<pre>"; print_r($customerName2); exit;
            }
            // echo "<pre>"; print_r($serialTableArray); exit;

            if (count($result2) < 1 && count($result1) < 1) {
                $result["status"] = "N";
                $result["data"] = "The Device not connected to server";
            } else {
                $result["status"] = "Y";
                $result["data"] = $serialTableArray;
            }
        }

        return $result;
    }


    public function fetch_serial_numbers($params)
    {
        // print_r($params);
        // exit();
        $this->db->select('s_serial_id,s_serial_number,s_imei,s_mobile,admin_price,distributor_price,dealer_price,s_used');
        $this->db->where('s_serial_number', $params);
        //$this->db->or_where('s_user_type', 2);
        $this->db->from($this->db->table_serial_no);
        $result0 = $this->db->get();
        $result0 = $result0->result_array();
        //echo"<pre>";print_r($result0);exit();

        if (count($result0) < 1) {
            $result["status"] = "N";
            $result["data"] = "Cannot find this device-IMEI in the System";
        } else {
            $this->db->select('*');
            $this->db->where('veh_serial_no', $result0[0]["s_serial_id"]);

            $this->db->from($this->db->table_vehicle);
            $result1 = $this->db->get();
            $result1 = $result1->result_array();
            $res = array();
            //print_r($result1);exit();
            if (count($result1) < 1) {
                $result["status"] = "N";
                $result["data"] = "The Certificate is not issued for this IMEI";
            } else {


                $this->db->select('*');
                $this->db->from($this->db->table_serial_no);
                //$this->db->where('s_used','=', 1);
                $this->db->where('s_serial_number', $params);
                $result1 = $this->db->get();
                $result1 = $result1->result_array();

                //echo "<pre>";print_r($result1[0]['s_used']);exit();

                if ($result1[0]['s_used'] == 1) {
                    $this->db->select('user_name as distributerName');
                    $this->db->from($this->db->table_users);
                    $this->db->where('user_id', $result1[0]['s_distributor_id']);
                    $distributerName = $this->db->get();
                    $distributerName = $distributerName->result_array();

                    $this->db->select('user_name as dealerName');
                    $this->db->from($this->db->table_users);
                    $this->db->where('user_id', $result1[0]['s_dealer_id']);
                    $dealerName = $this->db->get();
                    $dealerName = $dealerName->result_array();

                    $this->db->select('user_name as customerName');
                    $this->db->from($this->db->table_users);
                    $this->db->where('user_id', $result1[0]['customer_id']);
                    $customerName = $this->db->get();
                    $customerName = $customerName->result_array();

                    $otherdb = $this->load->database('tracking', TRUE);
                    $otherdb->select('*');
                    $otherdb->where('imei', $params);
                    $otherdb->from($otherdb->table_tracking);
                    $result2 = $otherdb->get();
                    $result2 = $result2->result_array();
                    //echo "<pre>";print_r($result2);exit();
                    $result1 = !empty($result1[0]) ? $result1[0] : array();
                    $result2 = !empty($result2[0]) ? $result2[0] : array();
                    $distributerName = !empty($distributerName[0]) ? $distributerName[0] : array();
                    $dealerName = !empty($dealerName[0]) ? $dealerName[0] : array();
                    $customerName = !empty($customerName[0]) ? $customerName[0] : array();

                    $result3 = array_merge($result2, $result1);
                    $result4 = array_merge($distributerName, $dealerName);
                    $result5 = array_merge($result4, $customerName);
                    $result6 = array_merge($result3, $result5);


                    if (count($result2) < 1) {
                        $result["status"] = "N";
                        $result["data"] = "The Device not connected to server";
                    } else {

                        $result["status"] = "Y";
                        $res[] = $result6;

                        $result["data"] = $res;
                        //print_r($res);exit();
                    }
                } else {
                    print_r("www");
                    exit();
                }

                /*$otherdb = $this->load->database('tracking', TRUE);
					$otherdb->select('*');
					$otherdb->where('imei',$params);
					$otherdb->from($otherdb->table_tracking);
					$result2 = $otherdb->get();
					$result2 = $result2->result_array();
					echo "<pre>";print_r($result2);exit();
					if(count($result2) < 1) {
						$result["status"] = "N";
						$result["data"] = "The Device not connected to server";
					} else {
						$result["status"] = "Y";
						$result["data"] = $result2;
					}
					*/
            }
        }
        //echo $this->db->last_query();exit();
        return $result;
    }


    public function selectCompanyInfo($companyID = 0, $userID = 0)
    {
        $this->db->select('c_tac_no');
        $this->db->from($this->db->table_company);
        $this->db->where('c_company_id', $companyID);
        $result = $this->db->get();
        $result = $result->result_array();
        //echo $this->db->last_query();exit();
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
        // echo "<pre>";print_r($result);exit;
        //echo $this->db->last_query();exit();
        return $result;
    }


    public function allSerialNumberByCompany($companyID = 0, $userID = 0)
    {
        $user_type = $this->session->userdata('user_type');
        // $this->db->select('ser.s_serial_id,ser.s_serial_number,com.c_tac_no');
        $this->db->select('ser.s_serial_id,ser.s_serial_number,ser.s_iccid,ser.s_imei,com.c_tac_no');
        $this->db->from($this->db->table_serial_no . ' as ser');
        $this->db->join($this->db->table_company . ' as com', 'ser.s_company_id = com.c_company_id');
        if ((string)$companyID != '0') {
            $this->db->where('ser.s_company_id', $companyID);
        }
        if ((string)$userID != '0' && (string)$userID != '1' && (string)$user_type != '0' && (string)$user_type != '4') {
            //$this->db->where('ser.s_user_id', $userID);
            $this->db->where('ser.s_dealer_id', $userID);
        }
        $this->db->where('ser.s_status', 0);
        $this->db->where("(s_used IS NULL OR s_used = 0)");
        $this->db->where('ser.s_dealer_id !=', 0);
        $result = $this->db->get();
        $result = $result->result_array();
        // echo $this->db->last_query();exit();
        return $result;
    }

    public function allSerialNumberByCompanyAndState($companyID = 0, $userID = 0)
    {
        $user_type = $this->session->userdata('user_type');
        // $this->db->select('ser.s_serial_id,ser.s_serial_number,com.c_tac_no');
        $this->db->select('ser.s_serial_id,ser.s_serial_number,ser.s_iccid,ser.s_imei,com.c_tac_no');
        $this->db->from($this->db->table_serial_no . ' as ser');
        $this->db->join($this->db->table_company . ' as com', 'ser.s_company_id = com.c_company_id');
        if ((string)$companyID != '0') {
            $this->db->where('ser.s_company_id', $companyID);
        }
        // if ((string)$stateID != '0') {
        //     $this->db->where('ser.s_state_id', $stateID);
        // }
        if ((string)$userID != '0' && (string)$userID != '1' && (string)$user_type != '0' && (string)$user_type != '4') {
            //$this->db->where('ser.s_user_id', $userID);
            $this->db->where('ser.s_dealer_id', $userID);
        }
        $this->db->where('ser.s_status', 0);
        $this->db->where("(s_used IS NULL OR s_used = 0)");
        $this->db->where('ser.s_dealer_id !=', 0);
        $result = $this->db->get();
        $result = $result->result_array();
        // echo $this->db->last_query();exit();
        return $result;
    }



    public function companyProductList($companyID = 0)
    {
        $user_type = $this->session->userdata('user_type');
        $user_company_id = $this->session->userdata('user_company_id');
        $this->db->select('p_product_id,p_product_name');

        if ((string)$user_type != '0') {
            $this->db->where('p_company_id', $user_company_id);
        }
        $this->db->from($this->db->table_products);

        $result = $this->db->get();
        $result = $result->result_array();
        //echo $this->db->last_query();exit();
        return $result;
    }

    public function allCompanyList($userID = 0)
    {
        $user_type = $this->session->userdata('user_type');
        $user_company_id = $this->session->userdata('user_company_id');
        $this->db->select('com.c_company_name,com.c_company_id,com.c_tac_no');
        if ((string)$user_type != '0') {
            $this->db->where('c_company_id', $user_company_id);
        }
        $this->db->from($this->db->table_company . ' as com');
        if ((string)$userID != '0' && (string)$userID != '1') {
            $this->db->join($this->db->table_users . ' as usr', 'usr.user_company_id = com.c_company_id');
            $this->db->where('usr.user_id', $userID);
        }
        $this->db->where('com.c_status', 1);

        $result = $this->db->get();
        $result = $result->result_array();
        //echo $this->db->last_query();exit();
        return $result;
    }

    public function allCustomerList($userID = 0)
    {
        $user_type = $this->session->userdata('user_type');
        $user_company_id = $this->session->userdata('user_company_id');

        $this->db->select('c_customer_name,c_customer_id,c_phone');

        $this->db->from($this->db->table_customers);
        // if((string)$userID!='0' && (string)$userID !='1')
        // {
        // 	$this->db->join($this->db->table_users.' as usr', 'usr.user_company_id = com.c_company_id');
        // 	$this->db->where('usr.user_id', $userID);
        // }
        $result = $this->db->get();
        $result = $result->result_array();
        //echo $this->db->last_query();exit();
        return $result;
    }

    public function listofTodayEntrys($selectedReportDate, $rtoNo)
    {
        $this->db->select('veh.*,ser.s_serial_number');

        $this->db->where('veh_create_date', $selectedReportDate);
        $this->db->where('veh_rto_no', $rtoNo);
        $this->db->order_by("veh_id", "asc");

        $this->db->from($this->db->table_vehicle . ' veh');
        $this->db->join($this->db->table_serial_no . ' as ser', 'ser.s_serial_id = veh.veh_serial_no', 'left');

        $result = $this->db->get();
        $result = $result->result_array();
        //echo $this->db->last_query();exit();
        return $result;
    }

    public function totalNoOflistofTodayEntrys($selectedReportDate, $rtoNo)
    {
        // $this->db->select('*');
        $this->db->select('count(*)');
        $this->db->where('veh_create_date', $selectedReportDate);
        $this->db->where('veh_rto_no', $rtoNo);
        $this->db->order_by("veh_id", "asc");
        $this->db->from($this->db->table_vehicle);
        $result = $this->db->count_all_results();
        return $result;
    }


    public function listofVehicleMakes($limit, $offset, $search = '')
    {
        $this->db->select('v_make_id,v_make_name');

        if (strlen($search) > 0) {
            $this->db->or_like('v_make_name', $search, 'both');
        }
        $this->db->from($this->db->table_make);
        $this->db->limit($limit, $offset);
        $this->db->order_by("v_make_id", "desc");
        $result = $this->db->get();
        $result = $result->result_array();
        return $result;
    }

    //-------- Add on starts ---------

    public function listofcertificates($limit, $offset, $search = '')
    {
        $user_type = $this->session->userdata('user_type');
        $user_company_id = $this->session->userdata('user_company_id');
        //---------------
        $this->db->select('ser.allotted,ser.used,us.user_name,us.user_type');
        if ((string)$user_type != '0') {
            $this->db->where('us.user_company_id', $user_company_id);
        }
        if (strlen($search) > 0) {
            $this->db->or_like('us.user_name', $search, 'both');
        }

        //if(strlen($company_id)>0 && (int)$company_id>0)
        //{
        //$this->db->or_like('ser.s_company_id',$company_id, 'both');
        //}
        //if(isset($_GET['used_status']) && strlen($_GET['used_status'])>0)
        //{
        //$this->db->where('s_used',$_GET['used_status']);
        //}

        $this->db->from($this->db->table_certificate . ' as ser');
        //$this->db->join($this->db->table_users.' as com', 'com.user_id = ser.created_by', 'left');
        $this->db->join($this->db->table_users . ' as us', 'us.user_id = ser.created_to', 'left');
        $this->db->limit($limit, $offset);
        $this->db->order_by("ser.sl", "desc");
        $result = $this->db->get();
        $result = $result->result_array();
        return $result;


        // ---------------
        /*
			$this->db->select('c_company_name,c_tac_no,c_cop_validity,c_company_id');

			if(strlen($search)>0)
			{
				$this->db->or_like('c_company_name',$search, 'both');
				$this->db->or_where("FIND_IN_SET('$search',c_tac_no) !=", 0);
			}

			$this->db->from($this->db->table_company);
			$this->db->limit($limit, $offset);
			$this->db->order_by("c_company_id", "desc");
            $result = $this->db->get();
			$result = $result->result_array();
			return $result;
*/
    }

    //-------- Addon Ends --------

    public function listofcompanys($limit, $offset, $search = '')
    {
        $user_type = $this->session->userdata('user_type');
        $user_company_id = $this->session->userdata('user_company_id');
        $this->db->select('c_company_name,c_tac_no,c_cop_validity,c_company_id');
        if ((string)$user_type != '0') {
            $this->db->where('c_company_id', $user_company_id);
        }
        if (strlen($search) > 0) {
            $this->db->or_like('c_company_name', $search, 'both');
            $this->db->or_where("FIND_IN_SET('$search',c_tac_no) !=", 0);
        }

        $this->db->from($this->db->table_company);
        $this->db->limit($limit, $offset);
        $this->db->order_by("c_company_id", "desc");
        $result = $this->db->get();
        $result = $result->result_array();
        return $result;
    }

    public function listofProducts($limit, $offset, $search = '')
    {

        $user_type = $this->session->userdata('user_type');
        $user_company_id = $this->session->userdata('user_company_id');
        $this->db->select('p_product_id,p_product_name,p_product_description,p_tac_no,p_unit_price,c_company_name');
        if ((string)$user_type != '0') {
            $this->db->where('c_company_id', $user_company_id);
        }
        if (strlen($search) > 0) {
            $this->db->like('p_product_name', $search, 'both');
        }

        if (isset($_GET['company_id']) && (int)$_GET['company_id'] > 0) {
            $this->db->where('p_company_id', $_GET['company_id']);
        }

        $this->db->from($this->db->table_products);
        $this->db->join($this->db->table_company, 'p_company_id = c_company_id', 'left');
        $this->db->limit($limit, $offset);
        $this->db->order_by("p_product_id", "desc");
        $result = $this->db->get();
        // echo $this->db->last_query();exit();
        $result = $result->result_array();
        return $result;
    }

    public function totalNoOfProducts()
    {
        $user_type = $this->session->userdata('user_type');
        $user_company_id = $this->session->userdata('user_company_id');
        // $this->db->select('*');
        $this->db->select('count(*)');
        if ((string)$user_type != '0') {
            $this->db->where('p_company_id', $user_company_id);
        }
        if (strlen($search) > 0) {
            $this->db->like('p_product_name', $search, 'both');
        }

        if (isset($_GET['company_id']) && (int)$_GET['company_id'] > 0) {
            $this->db->where('p_company_id', $_GET['company_id']);
        }

        $this->db->from($this->db->table_products);

        $result = $this->db->count_all_results();
        return $result;
    }

    public function listofdealers($limit, $offset, $search = '')
    {
        $user_type = $this->session->userdata('user_type');
        $user_company_id = $this->session->userdata('user_company_id');
        $this->db->select('user_id,user_name,user_phone,user_type,user_status');

        if (strlen($search) > 0) {
            $this->db->or_like('user_name', $search, 'both');
            $this->db->or_like('user_phone', $search, 'both');
        }
        if (isset($_GET['user_type']) && (int)$_GET['user_type'] > 0) {
            $this->db->where('user_type', $_GET['user_type']);
        }
        $this->db->where('user_type != ', 0);

        $user_id = $this->session->userdata('user_id');
        if ($user_type != 0 && $user_type != 4) {
            $this->db->where('created_by', $user_id);
        }
        if ((string)$user_type != '0' && (string)$user_type != '4') {
            $this->db->where('user_company_id', $user_company_id);
            $this->db->where('user_type !=', $user_type);
        }

        $this->db->from($this->db->table_users);
        $this->db->limit($limit, $offset);
        $this->db->order_by("user_id", "desc");
        $this->db->where('user_status', 1);
        $result = $this->db->get();
        $result = $result->result_array();
        // echo "<pre>";print_r($this->db->last_query());exit;
        return $result;
    }

    public function totalNoOfDealers()
    {
        $user_type = $this->session->userdata('user_type');
        $user_company_id = $this->session->userdata('user_company_id');
        // $this->db->select('*');
        $this->db->select('count(*)');
        if (isset($_GET['search']) && strlen($_GET['search']) > 0) {
            $this->db->or_like('user_name', $_GET['search'], 'both');
            $this->db->or_like('user_phone', $_GET['search'], 'both');
        }

        // echo "<pre>";print_r((int)$_GET['user_type']);exit;
        if (isset($_GET['user_type']) && (int)$_GET['user_type'] > 0) {
            $this->db->where('user_type', $_GET['user_type']);
        }
        $this->db->where('user_type != ', 0);

        // $user_type = $this->session->userdata('user_type');
        $user_id = $this->session->userdata('user_id');
        if ($user_type == 2) {
            $this->db->where('user_distributor_id', $user_id);
        }
        if ($user_type == 1) {
            $this->db->where('user_type', 6);
            $this->db->where('created_by', $user_id);
        }
        if ((string)$user_type != '0' && (string)$user_type != '4') {
            $this->db->where('user_company_id', $user_company_id);
            $this->db->where('user_type !=', $user_type);
        }
        $this->db->where('user_status', 1);
        $this->db->from($this->db->table_users);

        $result = $this->db->count_all_results();
        // echo "<pre>";print_r($this->db->last_query());exit;
        // echo "<pre>";print_r($result);exit();
        return $result;
    }

    public function listofUnassignedSerialNumbers($limit, $offset, $search = '', $company_id = '')
    {
        $user_type = $this->session->userdata('user_type');
        $user_company_id = $this->session->userdata('user_company_id');
        // $this->db->select('*');
        $this->db->select('ser.s_serial_id,ser.s_serial_number,ser.s_imei,ser.s_mobile,com.c_company_name,di.user_name as distributor_name,de.user_name as dealer_name,ser.s_distributor_id,ser.s_dealer_id,ser.s_used');
        if (strlen($search) > 0) {
            $limit = 25;
            $offset = 0;
            $this->db->or_like('s_serial_number', $search, 'both');
            $this->db->or_like('s_imei', $search, 'both');
        }
        if ((string)$user_type != '0') {
            $this->db->where('com.c_company_id', $user_company_id);
        }
        $user_type = $this->session->userdata('user_type');

        if ($user_type == '0') {
            $this->db->where('ser.s_distributor_id', '0');
        } else if ($user_type == '2') {
            $this->db->where('ser.s_dealer_id', '0');
        }

        $this->db->where('ser.s_used', 0);
        $this->db->where('ser.s_distributor_id', 0);

        // if (strlen($search) > 0) {
        //     $this->db->like('ser.s_serial_number', $search, 'both');
        // }
        if (isset($_GET['company_id']) && strlen($_GET['company_id']) > 0) {
            $this->db->where('ser.s_company_id', $_GET['company_id'], 'both');
        }
        if (isset($_GET['s_product_id']) && strlen($_GET['s_product_id']) > 0) {
            $this->db->where('ser.s_product_id', $_GET['s_product_id']);
        }


        // $this->db->where('ser.inScan', '0');
        $this->db->from($this->db->table_serial_no . ' as ser');
        $this->db->join($this->db->table_company . ' as com', 'com.c_company_id = ser.s_company_id', 'left');
        $this->db->join($this->db->table_users . ' as di', 'di.user_id = ser.s_distributor_id', 'left');
        $this->db->join($this->db->table_users . ' as de', 'de.user_id = ser.s_dealer_id', 'left');
        $this->db->limit($limit, $offset);
        $this->db->order_by("ser.s_serial_number", "desc");
        $result = $this->db->get();
        $result = $result->result_array();
        //print_r("<br><br><br><br><br><br>");
        //print_r($this->db->last_query()); 
        return $result;
    }


    public function listofUnassignedSerialNumbersData($limit, $offset, $search = '', $company_id = '')
    {
        $user_type = $this->session->userdata('user_type');
        $user_company_id = $this->session->userdata('user_company_id');
        // $this->db->select('*');
        $this->db->select('ser.s_serial_id,ser.s_serial_number,ser.s_imei,ser.s_mobile,ser.s_mobile_2,com.c_company_name,di.user_name as distributor_name,de.user_name as dealer_name,ser.s_distributor_id,ser.s_dealer_id,ser.s_used');
        // if (strlen($search) > 0) {
        //     $limit = 25;
        //     $offset = 0;
        //     $this->db->or_like('s_serial_number', $search, 'both');
        //     $this->db->or_like('s_imei', $search, 'both');
        // }
        if (strlen($search) > 0) {
            $this->db->group_start();
            $this->db->or_like('ser.s_serial_number', $search, 'both');
            $this->db->or_like('ser.s_imei', $search, 'both');
            $this->db->group_end();
        }
        if ((string)$user_type != '0') {
            $this->db->where('com.c_company_id', $user_company_id);
        }
        $user_type = $this->session->userdata('user_type');

        if ($user_type == '0' || $user_type == '4') {
            $this->db->where('ser.s_distributor_id', '0');
        } else if ($user_type == '2') {
            $this->db->where('ser.s_dealer_id', '0');
        }

        $this->db->where('ser.s_used', 0);
        //$this->db->where('ser.s_distributor_id', 0);

        // if (strlen($search) > 0) {
        //     $this->db->like('ser.s_serial_number', $search, 'both');
        // }
        if (isset($_GET['company_id']) && strlen($_GET['company_id']) > 0) {
            $this->db->where('ser.s_company_id', $_GET['company_id'], 'both');
        }
        if (isset($_GET['s_country_id']) && strlen($_GET['s_country_id']) > 0) {
            $this->db->where('ser.s_country_id', $_GET['s_country_id'], 'both');
        }
        if (isset($_GET['s_state_id']) && strlen($_GET['s_state_id']) > 0) {
            $this->db->where('ser.s_state_id', $_GET['s_state_id'], 'both');
        }
        if (isset($_GET['s_product_id']) && strlen($_GET['s_product_id']) > 0) {
            $this->db->where('ser.s_product_id', $_GET['s_product_id']);
        }


        $this->db->where('ser.inScan', '0');
        $this->db->from($this->db->table_serial_no . ' as ser');
        $this->db->join($this->db->table_company . ' as com', 'com.c_company_id = ser.s_company_id', 'left');
        $this->db->join($this->db->table_users . ' as di', 'di.user_id = ser.s_distributor_id', 'left');
        $this->db->join($this->db->table_users . ' as de', 'de.user_id = ser.s_dealer_id', 'left');
        $this->db->limit($limit, $offset);
        $this->db->order_by("ser.s_serial_id", "desc");
        $result = $this->db->get();
        $result = $result->result_array();
        //print_r("<br><br><br><br><br><br>");
        // print_r($this->db->last_query()); exit;
        return $result;
    }


    public function listofSerialNumbers($limit, $offset, $search = '', $company_id = '')
    {
        $user_type = $this->session->userdata('user_type');
        $user_company_id = $this->session->userdata('user_company_id');
        $this->db->select('ser.s_serial_id,ser.s_serial_number,ser.s_imei,ser.s_mobile,com.c_company_name,di.user_name as distributor_name,de.user_name as dealer_name,ser.s_distributor_id,ser.s_dealer_id,ser.s_used');
        if (strlen($search) > 0) {
            $this->db->group_start();
            $this->db->like('ser.s_imei', $search, 'both');
            $this->db->or_like('ser.s_serial_number', $search, 'both');
            $this->db->group_end();
        }
        if ((string)$user_type != '0' && (string)$user_type != '4') {
            $this->db->where('ser.s_company_id', $user_company_id);
        }
        $user_type = $this->session->userdata('user_type');
        $user_id = $this->session->userdata('user_id');

        if ($user_type == '0' || $user_type == '4') {
            $this->db->where('ser.s_distributor_id >', '0');
        } else if ($user_type == '2') {
            $this->db->where('ser.s_distributor_id', $user_id);
            $this->db->where('s_dealer_id', 0);
        } else if ($user_type == '1') {
            $this->db->where('ser.s_dealer_id', $user_id);
        }

        $this->db->where('ser.s_used', 0);


        if (isset($_GET['company_id']) && strlen($_GET['company_id']) > 0) {
            $this->db->where('ser.s_company_id', $_GET['company_id'], 'both');
        }
        if (isset($_GET['s_product_id']) && strlen($_GET['s_product_id']) > 0) {
            $this->db->where('ser.s_product_id', $_GET['s_product_id']);
        }
        if (isset($_GET['used_status']) && strlen($_GET['used_status']) > 0) {
            $this->db->where('ser.s_used', $_GET['used_status']);
        }
        if (isset($_GET['s_country_id']) && strlen($_GET['s_country_id']) > 0) {
            $this->db->where('ser.s_country_id', $_GET['s_country_id'], 'both');
        }
        if (isset($_GET['s_state_id']) && strlen($_GET['s_state_id']) > 0) {
            $this->db->where('ser.s_state_id', $_GET['s_state_id'], 'both');
        }
        //$this->db->where('s_user_id >', 0);
        $this->db->from($this->db->table_serial_no . ' as ser');
        $this->db->join($this->db->table_company . ' as com', 'com.c_company_id = ser.s_company_id', 'left');
        $this->db->join($this->db->table_users . ' as di', 'di.user_id = ser.s_distributor_id', 'left');
        $this->db->join($this->db->table_users . ' as de', 'de.user_id = ser.s_dealer_id', 'left');
        $this->db->limit($limit, $offset);
        $this->db->order_by("ser.s_serial_id", "desc");
        $result = $this->db->get();
        $result = $result->result_array();
        // echo "<pre>";print_r($this->db->last_query());exit();
        return $result;
    }

    // public function listofAssignedSerialNumbers($limit, $offset, $search = '', $company_id = '')
    // {
    //     $user_type = $this->session->userdata('user_type');
    //     // print_r($search);exit();
    //     $user_company_id = $this->session->userdata('user_company_id');
    //     // $this->db->select('*');
    //     $this->db->select('ser.s_serial_id,ser.s_serial_number,ser.s_imei,ser.s_iccid,ser.s_mobile,com.c_company_name,,di.user_name as distributor_name,de.user_name as dealer_name,ser.s_distributor_id,ser.s_dealer_id,ser.s_used');
    //     if (isset($_GET['search']) && strlen($_GET['search']) > 0) {
    //         $this->db->group_start();
    //         $this->db->or_like('ser.s_serial_number', $search, 'both');
    //         $this->db->or_like('ser.s_imei', $search, 'both');         
    //         $this->db->group_end();  
    //     }
    //     $this->db->select('ser.s_serial_id,ser.s_serial_number,ser.s_imei,ser.s_mobile,com.c_company_name,,di.user_name as distributor_name,de.user_name as dealer_name,ser.s_distributor_id,ser.s_dealer_id,ser.s_used');
    //     if ((string)$user_type != '0') {
    //         $this->db->where('ser.s_company_id', $user_company_id);
    //     }
    //     $user_type = $this->session->userdata('user_type');
    //     $user_id = $this->session->userdata('user_id');

    //     if ($user_type == '0') {
    //         $this->db->where('ser.s_distributor_id >', '0');
    //     } else if ($user_type == '2') {
    //         $this->db->where('ser.s_distributor_id', $user_id);
    //     } else if ($user_type == '1') {
    //         $this->db->where('ser.s_dealer_id', $user_id);
    //     }

    //     // $this->db->where('ser.s_used', '0');
    //     //$this->db->where('ser.s_distributor_id>', 0);
    //     //$this->db->where('ser.s_dealer_id>', 0);

    //     // if (strlen($search) > 0) {
    //     //     $limit = 25;
    //     //     $offset = 0;
    //     //     $this->db->like('ser.s_serial_number', $search, 'both');
    //     // }
    //     if (isset($_GET['company_id']) && strlen($_GET['company_id']) > 0) {
    //         $this->db->where('ser.s_company_id', $_GET['company_id'], 'both');
    //     }
    //     if (isset($_GET['s_product_id']) && strlen($_GET['s_product_id']) > 0) {
    //         $this->db->where('ser.s_product_id', $_GET['s_product_id']);
    //     }
    //     // $this->db->where('s_user_id', 0);
    //     $this->db->from($this->db->table_serial_no . ' as ser');
    //     $this->db->join($this->db->table_company . ' as com', 'com.c_company_id = ser.s_company_id', 'left');
    //     $this->db->join($this->db->table_users . ' as di', 'di.user_id = ser.s_distributor_id', 'left');
    //     $this->db->join($this->db->table_users . ' as de', 'de.user_id = ser.s_dealer_id', 'left');
    //     $this->db->limit($limit, $offset);
    //     $this->db->order_by("ser.s_serial_id", "desc");
    //     $result = $this->db->get();
    //     $result = $result->result_array();
    //     // echo $this->db->last_query();exit();
    //     // echo "<pre>";print_r($search);exit();    
    //     return $result;
    // }

    public function listofAssignedSerialNumbers($limit, $offset, $search = '', $company_id = '')
    {
        $user_type = $this->session->userdata('user_type');
        // print_r($search);exit();
        $user_company_id = $this->session->userdata('user_company_id');
        // $this->db->select('*');
        $this->db->select('ser.s_serial_id,ser.s_serial_number,ser.s_imei,ser.s_iccid,ser.s_mobile,com.c_company_name,,di.user_name as distributor_name,de.user_name as dealer_name,ser.s_distributor_id,ser.s_dealer_id,ser.s_used');
        if (isset($_GET['search']) && strlen($_GET['search']) > 0) {
            $this->db->group_start();
            $this->db->or_like('ser.s_serial_number', $search, 'both');
            $this->db->or_like('ser.s_imei', $search, 'both');
            $this->db->group_end();
        }
        $this->db->select('ser.s_serial_id,ser.s_serial_number,ser.s_imei,ser.s_mobile,com.c_company_name,,di.user_name as distributor_name,de.user_name as dealer_name,ser.s_distributor_id,ser.s_dealer_id,ser.s_used');
        if ((string)$user_type != '0') {
            $this->db->where('ser.s_company_id', $user_company_id);
        }
        $user_type = $this->session->userdata('user_type');
        $user_id = $this->session->userdata('user_id');

        if ($user_type == '0' || $user_type == '4') {
            $this->db->where('ser.s_distributor_id >', '0');
        } else if ($user_type == '2') {
            $this->db->where('ser.s_distributor_id', $user_id);
        } else if ($user_type == '1') {
            $this->db->where('ser.s_dealer_id', $user_id);
        }

        // $this->db->where('ser.s_used', '0');
        //$this->db->where('ser.s_distributor_id>', 0);
        //$this->db->where('ser.s_dealer_id>', 0);

        // if (strlen($search) > 0) {
        //     $limit = 25;
        //     $offset = 0;
        //     $this->db->like('ser.s_serial_number', $search, 'both');
        // }
        if (isset($_GET['company_id']) && strlen($_GET['company_id']) > 0) {
            $this->db->where('ser.s_company_id', $_GET['company_id'], 'both');
        }
        if (isset($_GET['s_country_id']) && strlen($_GET['s_country_id']) > 0) {
            $this->db->where('ser.s_country_id', $_GET['s_country_id'], 'both');
        }
        if (isset($_GET['s_state_id']) && strlen($_GET['s_state_id']) > 0) {
            $this->db->where('ser.s_state_id', $_GET['s_state_id'], 'both');
        }
        if (isset($_GET['s_product_id']) && strlen($_GET['s_product_id']) > 0) {
            $this->db->where('ser.s_product_id', $_GET['s_product_id']);
        }
        if (isset($_GET['used_status']) && strlen($_GET['used_status']) > 0) {
            if ($_GET['used_status'] == 2) {
                $this->db->where('ser.s_used', '0');
            } else if ($_GET['used_status'] == 1) {
                $this->db->where('ser.s_used', $_GET['used_status']);
            }
        }
        // $this->db->where('s_user_id', 0);
        $this->db->from($this->db->table_serial_no . ' as ser');
        $this->db->join($this->db->table_company . ' as com', 'com.c_company_id = ser.s_company_id', 'left');
        $this->db->join($this->db->table_users . ' as di', 'di.user_id = ser.s_distributor_id', 'left');
        $this->db->join($this->db->table_users . ' as de', 'de.user_id = ser.s_dealer_id', 'left');
        $this->db->limit($limit, $offset);
        $this->db->order_by("ser.s_serial_id", "desc");
        $result = $this->db->get();
        $result = $result->result_array();
        // echo $this->db->last_query();exit();
        // echo "<pre>";print_r($search);exit();    
        return $result;
    }

    public function listofVehicleModels($limit, $offset, $search = '')
    {
        $this->db->select('mo.ve_model_id,mo.ve_make_id,mo.ve_model_name,mk.v_make_name');
        if (isset($_GET['make_id']) && strlen($_GET['make_id']) > 0) {
            $this->db->where('ve_make_id', $_GET['make_id'], 'both');
        }
        if (strlen($search) > 0) {
            $this->db->or_like('ve_model_name', $search, 'both');
        }

        $this->db->from($this->db->table_model . ' as mo');
        $this->db->join($this->db->table_make . ' as mk', 'mk.v_make_id = mo.ve_make_id', 'left');
        //	$this->db->limit($limit, $offset);
        $this->db->order_by("ve_model_id", "desc");
        $result = $this->db->get();
        $result = $result->result_array();
        //echo $this->db->last_query();exit();
        return $result;
    }

    public function totalNoOfvehicleModel()
    {
        $this->db->select('*');
        if (isset($_GET['make_id']) && strlen($_GET['make_id']) > 0) {
            $this->db->where('ve_make_id', $_GET['make_id'], 'both');
        }
        if (isset($_GET['search']) && strlen($_GET['search']) > 0) {
            $this->db->or_like('ve_model_name', $_GET['search'], 'both');
        }

        $this->db->from($this->db->table_model);
        $result = $this->db->count_all_results();
        return $result;
    }

    public function getModelInfo($modelID)
    {
        // $this->db->select('*');
        $this->db->select('ve_model_id,ve_make_id,ve_model_name');
        $this->db->where('ve_model_id = ', $modelID);
        $this->db->from($this->db->table_model);
        $result = $this->db->get();
        $result = $result->row_array();
        return $result;
    }

    public function getRtoInfo($RTONo)
    {
        //$this->db->select('*');
        $this->db->select('rto_no, rto_place, rto_pwd, rto_number, state_name, state_id');
        $this->db->where('rto_no = ', $RTONo);
        $this->db->from($this->db->table_rto);
        $result = $this->db->get();
        $result = $result->row_array();
        return $result;
    }

    public function getSerialNumberInfo($serial_number)
    {
        $this->db->select('ser.*,di.user_name as distributor_name, de.user_name as dealer_name');
        $this->db->where('ser.s_serial_id = ', $serial_number);
        $this->db->from($this->db->table_serial_no . ' as ser');
        $this->db->join($this->db->table_users . ' as di', 'di.user_id = ser.s_distributor_id', 'left');
        $this->db->join($this->db->table_users . ' as de', 'de.user_id = ser.s_dealer_id', 'left');
        $result = $this->db->get();
        $result = $result->row_array();
        return $result;
    }

    public function getImeiInfo($imei)
    {

        $this->db->select('ser.*');
        $this->db->where('ser.s_imei = ', $imei);
        $this->db->from($this->db->table_serial_no . ' as ser');
        $result = $this->db->get();
        $result = $result->row_array();
        // echo "<pre>";print_r($result);exit();
        return $result;
    }


    public function getSerialNumber($serial_number)
    {
        $this->db->select('ser.*');
        $this->db->where('ser.s_serial_number = ', $serial_number);
        $this->db->from($this->db->table_serial_no . ' as ser');
        $result = $this->db->get();
        $result = $result->row_array();
        return $result;
    }
    public function totalNoOfvehicleMake()
    {
        // $this->db->select('*');
        $this->db->select('v_make_id,v_make_name');
        if (isset($_GET['search']) && strlen($_GET['search']) > 0) {
            $this->db->or_like('v_make_name', $_GET['search'], 'both');
        }
        $this->db->from($this->db->table_make);
        $result = $this->db->count_all_results();
        return $result;
    }

    public function totalNoOfRTO_copy()
    {
        // $this->db->select('*');
        $this->db->select('rto_no, rto_place, rto_pwd, rto_number, state_name, state_id');
        if (isset($_GET['search']) && strlen($_GET['search']) > 0) {
            $this->db->or_like('rto_place', $_GET['search'], 'both');
            $this->db->or_like('rto_number', $_GET['search'], 'both');
        }
        $this->db->from($this->db->table_rto);
        $result = $this->db->count_all_results();
        return $result;
    }

    public function totalNoOfRTO($search = '', $state)
    {
        $this->db->select('rto_no,rto_place, rto_pwd, rto_number, state_name, state_id');
        if (isset($search) && strlen($search) > 0) {
            $this->db->group_start();
            $this->db->or_like('rto_place', $search, 'both');
            $this->db->or_like('rto_number', $search, 'both');
            $this->db->group_end();
        }
        if ($state != '') {
            $this->db->where('state_id', $state);
        }
        $this->db->from($this->db->table_rto);
        $result = $this->db->count_all_results();
        return $result;
    }


    public function listofRtoList_copy($limit, $offset, $search = '')
    {
        $this->db->select('rto_no,rto_place, rto_pwd, rto_number, state_name, state_id');
        if (isset($_GET['search']) && strlen($_GET['search']) > 0) {
            $this->db->or_like('rto_place', $_GET['search'], 'both');
            $this->db->or_like('rto_number', $_GET['search'], 'both');
            $this->db->or_like('state_name', $_GET['search'], 'both');
        }

        $this->db->from($this->db->table_rto);
        $this->db->limit($limit, $offset);
        $this->db->order_by("rto_no", "desc");
        $result = $this->db->get();
        $result = $result->result_array();
        //echo $this->db->last_query();exit();
        return $result;
    }

    public function listofRtoList($limit, $offset, $search = '', $state)
    {
        $this->db->select('rto_no,rto_place, rto_pwd, rto_number, state_name, state_id');
        if (isset($_GET['search']) && strlen($_GET['search']) > 0) {
            $this->db->group_start();
            $this->db->or_like('rto_place', $_GET['search'], 'both');
            $this->db->or_like('rto_number', $_GET['search'], 'both');
            $this->db->group_end();
        }
        if ($state != '') {
            $this->db->where('state_id', $state);
        }

        $this->db->from($this->db->table_rto);
        $this->db->limit($limit, $offset);
        $this->db->order_by("rto_no", "desc");
        $result = $this->db->get();
        $result = $result->result_array();
        return $result;
    }

    public function totalNoOfCustomers()
    {
        // $this->db->select('*');
        $this->db->select('count(*)');
        if (isset($_GET['search']) && strlen($_GET['search']) > 0) {
            $this->db->group_start();
            $this->db->like('c_customer_name', $_GET['search'], 'both');
            $this->db->or_like('c_phone', $_GET['search'], 'both');
            $this->db->group_end();
        }
        $this->db->from($this->db->table_customers);
        $result = $this->db->count_all_results();
        // echo "<pre>"; print_r($result); exit;
        return $result;
    }

    public function totalNoOfCustomersDealer($dealer_id)
    {
        $this->db->select('count(*)');
        $this->db->where('c_created_by', $dealer_id);
        if (isset($_GET['search']) && strlen($_GET['search']) > 0) {
            $this->db->group_start();
            $this->db->like('c_customer_name', $_GET['search'], 'both');
            $this->db->or_like('c_phone', $_GET['search'], 'both');
            $this->db->group_end();
        }
        $this->db->from($this->db->table_customers);
        $result = $this->db->count_all_results();
        // echo "<pre>"; print_r($_GET['search']); exit;
        return $result;
    }
    public function totalNoOfCustomersDistributor_old($distributor_id)
    {
        $this->db->select('user_id');
        $this->db->where('created_by', $distributor_id);
        $this->db->from($this->db->table_users);
        $result = $this->db->get();
        $result = $result->result_array();
        // echo "<pre>"; print_r($result); exit;
        foreach ($result as  $value) {
            $dealerids[] = $value[user_id];
        }
        foreach ($dealerids as $key => $value) {
            // $this->db->select('*');
            $this->db->select('count(*)');
            $this->db->where('c_created_by', $value);
            $this->db->from($this->db->table_customers);
            $result[$key] = $this->db->count_all_results();
            //echo $value;
        } //exit;

        // echo "<pre>"; print_r($result); exit;
        $result = array_sum($result);
        // echo $result; exit;
        //   echo "<pre>";print_r($this->db->last_query());exit;
        return $result;
    }

    public function getTotalDealerID($distributor_id)
    {
        $this->db->select('user_id');
        $this->db->where('created_by', $distributor_id);
        $this->db->from($this->db->table_users);
        $result = $this->db->get();
        $result = $result->result_array();
        return $result;
    }

    // public function totalNoOfCustomersDistributor($distributor_id,$search,$dealer_id)
    // {  
    //     //new code
    //     $this->db->select('count(*)');
    //     $this->db->where('one.created_by', $distributor_id);
    //     $this->db->where('one.user_status', 1);
    //     $this->db->where('cus.c_customer_name is NOT NULL', NULL, FALSE);
    //     $this->db->where('cus.c_status ','ACTIVE');

    //     if (isset($search) && strlen($search) > 0) {
    //         $this->db->group_start();
    //         $this->db->like('cus.c_customer_name', $search, 'both');
    //         $this->db->or_like('cus.c_phone', $search, 'both');
    //         $this->db->group_end();
    //     }

    //     $this->db->from($this->db->table_users . ' as one');
    //     $this->db->limit($limit, $offset);
    //     $this->db->join($this->db->table_users . ' as two', 'one.user_id = two.created_by', 'left');
    //     $this->db->join($this->db->table_customers . ' as cus', 'one.user_id = cus.c_created_by', 'left');
    //     $result = $this->db->count_all_results(); 
    //     // echo "<pre>";print_r($this->db->last_query());exit;
    //     return $result;
    // }

    public function totalNoOfCustomersForDistributor($dealer_id)
    {
        // echo "<pre>";print_r($dealer_id);exit;
        $outputArray = array();
        foreach ($dealer_id as $item) {
            $outputArray[] = $item['user_id'];
        }
        // echo "<pre>";print_r($search);exit;

        $this->db->select('cus.c_customer_id, cus.c_customer_name');
        $this->db->from($this->db->table_customers . ' as cus');
        $this->db->join($this->db->table_users . ' as user', 'user.user_id = cus.c_created_by', 'left');
        $this->db->where_in('cus.c_created_by', $outputArray);
        $this->db->where('cus.c_status', 'ACTIVE');

        $query = $this->db->get();
        $result = $query->result_array();

        // echo "<pre>";print_r($this->db->last_query());exit;
        // echo "<pre>";print_r(count($result));exit;
        return count($result);
    }

    public function totalNoOfCustomersDistributor($distributor_id, $search, $dealer_id)
    {
        // echo "<pre>";print_r($dealer_id);exit;
        $outputArray = array();
        foreach ($dealer_id as $item) {
            $outputArray[] = $item['user_id'];
        }
        // echo "<pre>";print_r($search);exit;

        $this->db->select('cus.c_customer_id, cus.c_customer_name');
        $this->db->from($this->db->table_customers . ' as cus');
        $this->db->join($this->db->table_users . ' as user', 'user.user_id = cus.c_created_by', 'left');
        $this->db->where_in('cus.c_created_by', $outputArray);
        $this->db->where('cus.c_status', 'ACTIVE');
        // $this->db->group_start();
        if (isset($search) && strlen($search) > 0) {
            $this->db->like('cus.c_customer_name', $search);
            $this->db->or_like('cus.c_phone', $search);
        }
        // $this->db->group_end();

        $query = $this->db->get();
        $result = $query->result_array();

        // echo "<pre>";print_r($this->db->last_query());exit;
        // echo "<pre>";print_r(count($result));exit;
        return count($result);
    }

    public function listofCustomersList($limit, $offset, $search = '')
    {
        // $this->db->select('*');
        $this->db->select('use.*,cus.c_customer_id,cus.c_phone,cus.c_status,cus.c_address,cus.c_email,cus.c_customer_name,use.user_name as dealer_name,two.user_name as distributor_name');
        if (isset($_GET['search']) && strlen($_GET['search']) > 0) {
            $this->db->group_start();
            $this->db->like('c_customer_name', $_GET['search'], 'both');
            $this->db->or_like('c_phone', $_GET['search'], 'both');
            $this->db->group_end();
        }

        //     $this->db->select('one.*,cus.c_phone,cus.c_status,cus.c_address,cus.c_email,cus.c_customer_name,three.user_name as distributor_name,four.user_name as dealer_name');
        //    // $this->db->or_where('one.user_type',); or_where
        //     $this->db->limit($limit, $offset);
        //     $this->db->where('cus.c_customer_name is NOT NULL', NULL, FALSE);
        //     $this->db->from($this->db->table_users . ' as one');
        //     $this->db->join($this->db->table_users . ' as two', 'one.user_id = two.created_by', 'left');
        //     $this->db->join($this->db->table_users . ' as three', 'two.user_id = three.created_by', 'left');
        //     $this->db->join($this->db->table_users . ' as four', 'three.user_id = four.created_by', 'left');
        //     $this->db->join($this->db->table_customers . ' as cus', 'one.user_id = cus.c_created_by', 'left');
        //     $result = $this->db->get();
        //     $result = $result->result_array();

        $this->db->select('use.*,cus.c_phone,cus.c_status,cus.c_address,cus.c_email,cus.c_customer_name,use.user_name as dealer_name,two.user_name as distributor_name');
        $this->db->from($this->db->table_customers . ' as cus');
        $this->db->limit($limit, $offset);
        // $this->db->from($this->db->table_users . ' as one');
        //
        $this->db->join($this->db->table_users . ' as use', 'use.user_id = cus.c_created_by', 'left');
        $this->db->join($this->db->table_users . ' as two', 'two.user_id = use.created_by', 'left');
        $result = $this->db->get();
        $result = $result->result_array();


        //echo "<pre>"; print_r($result); exit;

        // $this->db->from($this->db->table_customers);
        // $this->db->limit($limit, $offset);
        // $this->db->order_by("c_customer_name");
        // $result = $this->db->get();
        // $result = $result->result_array();
        // echo $this->db->last_query();exit();
        return $result;
    }

    public function listofCustomersListDealer($limit, $offset, $search = '', $dealer_id)
    {
        // $this->db->select('*');
        $this->db->select('c_phone,c_status,c_address,c_email,c_customer_name');
        if (isset($_GET['search']) && strlen($_GET['search']) > 0) {
            $this->db->group_start();
            $this->db->like('c_customer_name', $_GET['search'], 'both');
            $this->db->or_like('c_phone', $_GET['search'], 'both');
            $this->db->group_end();
        }
        $this->db->where('c_created_by', $dealer_id);
        $this->db->from($this->db->table_customers);
        $this->db->limit($limit, $offset);
        $this->db->order_by("c_customer_name");
        $result = $this->db->get();
        $result = $result->result_array();
        //echo $this->db->last_query();exit();
        return $result;
    }

    public function listofCustomersListDistributor($limit, $offset, $search, $distributor_id, $dealer_id)
    {
        $outputArray = array();
        foreach ($dealer_id as $item) {
            $outputArray[] = $item['user_id'];
        }
        // echo "<pre>";print_r($search);exit;

        // $this->db->select('one.user_name,cus.c_phone,cus.c_status,cus.c_customer_id,cus.c_address,cus.c_email,cus.c_customer_name,two.user_name as dealer_name');


        $this->db->select('user.user_name,cus.c_phone,cus.c_status,cus.c_customer_id,cus.c_address,cus.c_email,cus.c_customer_name,user.user_name');
        $this->db->from($this->db->table_customers . ' as cus');
        $this->db->join($this->db->table_users . ' as user', 'user.user_id = cus.c_created_by', 'left');
        $this->db->where_in('cus.c_created_by', $outputArray);
        $this->db->where('cus.c_status', 'ACTIVE');
        $this->db->limit($limit, $offset);
        // $this->db->group_start();
        $this->db->like('cus.c_customer_name', $search);
        $this->db->or_like('cus.c_phone', $search);
        // $this->db->group_end();

        $query = $this->db->get();
        $result = $query->result_array();

        // echo "<pre>";print_r($this->db->last_query());exit;
        // echo "<pre>";print_r(count($result));exit;
        return $result;

        // $this->db->select('one.user_name,cus.c_phone,cus.c_status,cus.c_customer_id,cus.c_address,cus.c_email,cus.c_customer_name,two.user_name as dealer_name');
        // $this->db->where('one.created_by', $distributor_id);
        // $this->db->where('one.user_status', 1);
        // $this->db->where('cus.c_customer_name is NOT NULL', NULL, FALSE);
        // $this->db->where('cus.c_status ','ACTIVE');
        // if (isset($search) && strlen($search) > 0) {
        //     $this->db->like('cus.c_customer_name', $_GET['search'], 'both');
        //     $this->db->or_like('cus.c_phone', $_GET['search'], 'both');
        // }
        // $this->db->from($this->db->table_users . ' as one');
        // $this->db->limit($limit, $offset);
        // $this->db->join($this->db->table_users . ' as two', 'one.user_id = two.created_by', 'left');
        // $this->db->join($this->db->table_customers . ' as cus', 'one.user_id = cus.c_created_by', 'left');
        // $result = $this->db->get();
        // $result = $result->result_array();
        // // echo "<pre>";print_r($this->db->last_query());exit();
        // return $result;
    }

    public function listofCustomersListDistributor_old($limit, $offset, $search = '', $distributor_id)
    {
        //echo "hai";exit;
        if (isset($_GET['search']) && strlen($_GET['search']) > 0) {
            $this->db->or_like('c_customer_name', $_GET['search'], 'both');
            $this->db->or_like('c_phone', $_GET['search'], 'both');
        }
        //     $this->db->select('user_id,user_name');
        //     $this->db->where('created_by', $distributor_id);
        //     $this->db->from($this->db->table_users);
        //     $result = $this->db->get();
        //     $result = $result->result_array();
        //    // echo "oooeee"."<pre>"; print_r($result); exit;
        //     foreach($result as  $value)
        //     {
        //        $dealerids[]=$value[user_id];
        //        //$dealernames[]=$value[user_name];
        //     }
        $this->db->select('one.user_name,cus.c_phone,cus.c_status,cus.c_customer_id,cus.c_address,cus.c_email,cus.c_customer_name,two.user_name as dealer_name');
        $this->db->where('one.created_by', $distributor_id);
        $this->db->where('cus.c_customer_name is NOT NULL', NULL, FALSE);
        $this->db->from($this->db->table_users . ' as one');
        $this->db->limit($limit, $offset);
        $this->db->join($this->db->table_users . ' as two', 'one.user_id = two.created_by', 'left');
        $this->db->join($this->db->table_customers . ' as cus', 'one.user_id = cus.c_created_by', 'left');
        $result = $this->db->get();
        $result = $result->result_array();



        // echo "<pre>"; print_r($result); exit;


        //echo "<pre>"; print_r($dealerids);
        //echo "<pre>"; print_r($dealernames); exit;
        //         $result1=array();
        //         $dealer=array();
        //         $i=0;
        //         foreach($dealerids as $key => $value)
        //         {
        //             $this->db->select('*');
        //             $this->db->where('c_created_by',$value);
        //             $this->db->from($this->db->table_customers);
        //             $this->db->limit($limit, $offset);
        //             $this->db->order_by("c_customer_name");
        //             $result = $this->db->get();
        //             $result = $result->result_array();
        //             $result['dealer_name']=$dealernames[$key];
        //             array_push($result1,$result);
        //         } 

        // echo "<pre>"; print_r($result1); exit;
        //         foreach($result1 as $key => $value)
        //         {
        //            foreach($result1[$key] as $val)
        //            {
        //                $results[]=$val;
        //            }
        //         }
        //         echo "<pre>"; print_r($results); exit;
        return $result;
    }

    public function distributor_vehicle_list($distributor_id)
    {
        //echo "hai";exit;
        $otherdb = $this->load->database('tracking', TRUE);
        $this->db->select('one.user_name,cus.c_phone,cus.c_status,cus.c_customer_id,cus.c_address,cus.c_email,cus.c_customer_name,two.user_name as dealer_name');
        $this->db->where('one.created_by', $distributor_id);
        $this->db->where('cus.c_customer_name is NOT NULL', NULL, FALSE);
        $this->db->from($this->db->table_users . ' as one');
        $this->db->join($this->db->table_users . ' as two', 'one.user_id = two.created_by', 'left');
        $this->db->join($this->db->table_customers . ' as cus', 'one.user_id = cus.c_created_by', 'left');
        $result = $this->db->get();
        $result = $result->result_array();

        $otherdb = $this->load->database('tracking', TRUE);
        $result2 = array();
        $customerIDs = array_column($result, 'c_customer_id');
        // echo "<pre>"; print_r($result); exit;

        $otherdb->select('vehicleRegNumber,latitude,longitude,vtrackingId,imei,distance');
        $otherdb->where_in('customerID', $customerIDs);
        $otherdb->from($otherdb->table_trackings);
        $query = $otherdb->get();
        $distributorRecords = $query->result_array();

        // echo "<pre>"; print_r($distributorRecords); exit;
        $invalidCount = 0;
        $validCount = 0;
        $data = array();
        foreach ($distributorRecords as $key => $value) {
            $dataObj = array();
            if ($this->validateLatitude($value['latitude']) && $this->validateLongitude($value['longitude'])) {
                $validCount++;

                $dataObj['vehicleRegNumber'] = $value['vehicleRegNumber'];
                $dataObj['vtrackingId'] = $value['vtrackingId'];
                $dataObj['imei'] = $value['imei'];
                $dataObj['latitude'] = $value['latitude'];
                $dataObj['longitude'] = $value['longitude'];
                $dataObj['distance'] = $value['distance'];
            } else {
                $invalidCount++;
                $dataObj['vehicleRegNumber'] = $value['vehicleRegNumber'];
                $dataObj['vtrackingId'] = $value['vtrackingId'];
                $dataObj['imei'] = $value['imei'];
                $dataObj['latitude'] = "12.345";
                $dataObj['longitude'] = "12.345";
                $dataObj['distance'] = $value['distance'];
                //  echo "<pre>"; print_r($value['imei']);
            }
            array_push($data, $dataObj);
        }
        // echo "<pre>"; print_r("valid ".$validCount);
        // echo "<pre>"; print_r("invalid ".$invalidCount);exit;
        return $data;
        //---------old code 

        // echo "<pre>";
        // print_r($result); exit;
        // $customer_ids=array();
        // $result2=array();
        // $result1=array();
        // for($i=0;$i<count($result);$i++)
        // {
        //     $customer_ids[$i]=$result[$i]['c_customer_id'];
        //     $otherdb->select('latitude,longitude,vtrackingId,imei');
        //     $otherdb->from($otherdb->table_trackings);
        //     $otherdb->where('customerID',$customer_ids[$i]);
        //   //  echo "idTable==>".$customer_ids[$i]."<br>";
        //     $result1 = $otherdb->get();
        //     array_push($result2,$result1->result_array());
        // }
        // //exit;
        // // echo "ohooii"."<pre>"; print_r($customer_ids); exit;
        // return $result2;
    }

    // public function dealer_vehicle_list($dealer_id)
    // {
    //     //old flow
    //     $this->db->select('c_customer_id');
    //     $this->db->where('c_created_by', $dealer_id);
    //     $this->db->from($this->db->table_customers);
    //     $result = $this->db->get();
    //     $result = $result->result_array();
    //     // echo "<pre>";print_r($result);exit;
    //     //echo $this->db->last_query();exit();
    //     $customer_ids=array();
    //     $otherdb = $this->load->database('tracking', TRUE);
    //     $result2=array();
    //     $result1=array();
    //     for($i=0;$i<count($result);$i++)
    //     {
    //         $customer_ids[$i]=$result[$i]['c_customer_id'];
    //         //echo $customer_ids[$i];
    //         $otherdb->select('vehicleRegNumber,vtrackingId,imei,latitude,longitude,distance');
    //         $otherdb->from($otherdb->table_trackings);
    //         $otherdb->where('customerID',$customer_ids[$i]);
    //         $result1 = $otherdb->get();
    //         // $result1 = $results->result_array();
    //         array_push($result2,$result1->result_array());
    //     }
    //     // // echo "<pre>";
    //     // // print_r($result2);
    //     // // exit;


    //     //  $data= array();
    //     // foreach ($result as $key => $value) {
    //     //     $dataObj= array();
    //     //     if ($this->validateLatitude($value['latitude']) && $this->validateLongitude($value['longitude'])) {
    //     //         $dataObj['vehicleRegNumber'] = $value['vehicleRegNumber'];
    //     //         $dataObj['vtrackingId'] = $value['vtrackingId'];
    //     //         $dataObj['imei'] = $value['imei'];
    //     //         $dataObj['latitude'] = $value['latitude'];
    //     //         $dataObj['longitude'] = $value['longitude'];
    //     //         $dataObj['distance'] = $value['distance'];

    //     //     } else {
    //     //         $dataObj['vehicleRegNumber'] = $value['vehicleRegNumber'];
    //     //         $dataObj['vtrackingId'] = $value['vtrackingId'];
    //     //         $dataObj['imei'] = $value['imei'];
    //     //         $dataObj['latitude'] = "00.0000";
    //     //         $dataObj['longitude'] = "00.0000";
    //     //         $dataObj['distance'] = $value['distance'];
    //     //     }
    //     //     array_push($data, $dataObj);
    //     // }
    //     // echo "<pre>";
    //     // print_r($data);
    //     // exit;
    //     return $data;
    // }

    public function dealer_vehicle_list($dealer_id)
    {
        $this->db->select('c_customer_id');
        $this->db->where('c_created_by', $dealer_id);
        $this->db->from($this->db->table_customers);
        $result = $this->db->get();
        $result = $result->result_array();
        //echo $this->db->last_query();exit();
        $customer_ids = array();
        $otherdb = $this->load->database('tracking', TRUE);
        $result2 = array();

        $otherdb->select('vehicleRegNumber,latitude,longitude,vtrackingId,imei,distance');
        $otherdb->where_in('customerID', $customerIDs);
        $otherdb->from($otherdb->table_trackings);
        $query = $otherdb->get();
        $dealerRecords = $query->result_array();
        // echo "<pre>";print_r($dealerRecords);exit;

        $data = array();
        foreach ($dealerRecords as $key => $value) {
            $dataObj = array();
            // echo "<pre>";print_r($dataObj);exit;
            if ($this->validateLatitude($value['latitude']) && $this->validateLongitude($value['longitude'])) {
                $dataObj['vehicleRegNumber'] = $value['vehicleRegNumber'];
                $dataObj['vtrackingId'] = $value['vtrackingId'];
                $dataObj['imei'] = $value['imei'];
                $dataObj['latitude'] = $value['latitude'];
                $dataObj['longitude'] = $value['longitude'];
                $dataObj['distance'] = $value['distance'];
            } else {
                $dataObj['vehicleRegNumber'] = $value['vehicleRegNumber'];
                $dataObj['vtrackingId'] = $value['vtrackingId'];
                $dataObj['imei'] = $value['imei'];
                $dataObj['latitude'] = "12.345";
                $dataObj['longitude'] = "12.345";
                $dataObj['distance'] = $value['distance'];
                // echo "<pre>";print_r($value['imei']);
            }
            array_push($data, $dataObj);
        }
        // echo "<pre>";print_r($data);exit;


        return $data;
    }



    public function totalNoOfCompanys()
    {
        $this->db->select('*');
        if (isset($_GET['search']) && strlen($_GET['search']) > 0) {
            $this->db->or_like('c_company_name', $_GET['search'], 'both');
            $this->db->or_where("FIND_IN_SET('$search',c_tac_no) !=", 0);
        }
        $this->db->from($this->db->table_company);
        $result = $this->db->count_all_results();
        return $result;
    }

    public function totalNoOfUnassignedSerialNumbers()
    {
        $user_type = $this->session->userdata('user_type');
        $user_company_id = $this->session->userdata('user_company_id');
        // $this->db->select('*');
        $this->db->select('count(*)');
        if ((string)$user_type != '0') {
            $this->db->where('s_company_id	', $user_company_id);
        }
        $user_type = $this->session->userdata('user_type');

        if ($user_type == '0') {
            $this->db->where('s_distributor_id', '0');
        } else if ($user_type == '2') {
            $this->db->where('s_dealer_id', '0');
        }

        if (isset($_GET['search']) && strlen($_GET['search']) > 0) {
            $this->db->like('s_serial_number', $_GET['search'], 'both');
        }

        if (isset($_GET['company_id']) && strlen($_GET['company_id']) > 0) {
            $this->db->where('s_company_id', $_GET['company_id'], 'both');
        }
        if (isset($_GET['s_country_id']) && strlen($_GET['s_country_id']) > 0) {
            $this->db->where('s_country_id', $_GET['s_country_id'], 'both');
        }
        if (isset($_GET['s_state_id']) && strlen($_GET['s_state_id']) > 0) {
            $this->db->where('s_state_id', $_GET['s_state_id'], 'both');
        }
        if (isset($_GET['s_product_id']) && strlen($_GET['s_product_id']) > 0) {
            $this->db->where('s_product_id', $_GET['s_product_id']);
        }
        $this->db->where('s_used', '0');
        $this->db->where('inScan', '0');
        //$this->db->where('s_user_id', 0);

        $this->db->from($this->db->table_serial_no);
        $result = $this->db->count_all_results();
        // echo "<pre>";print_r($this->db->last_query());exit();
        return $result;
    }

    public function totalNoOfSerialNumbers()
    {
        $user_type = $this->session->userdata('user_type');
        $user_company_id = $this->session->userdata('user_company_id');
        // $this->db->select('*');
        $this->db->select('count(*)');
        if ((string)$user_type != '0') {
            $this->db->where('s_company_id', $user_company_id);
        }
        $user_type = $this->session->userdata('user_type');
        $user_id = $this->session->userdata('user_id');

        if ($user_type == '0' || $user_type == '4') {
            $this->db->where('s_distributor_id >', '0');
        } else if ($user_type == '2') {
            $this->db->where('s_distributor_id', $user_id);
            $this->db->where('s_dealer_id', 0);
        } else if ($user_type == '1') {
            $this->db->where('s_dealer_id', $user_id);
        }

        if (isset($_GET['search']) && strlen($_GET['search']) > 0) {
            $this->db->group_start();
            $this->db->like('s_imei', $_GET['search'], 'both');
            $this->db->or_like('s_serial_number', $_GET['search'], 'both');
            $this->db->group_end();
        }

        if (isset($_GET['company_id']) && strlen($_GET['company_id']) > 0) {
            $this->db->where('s_company_id', $_GET['company_id'], 'both');
        }

        if (isset($_GET['s_product_id']) && strlen($_GET['s_product_id']) > 0) {
            $this->db->where('s_product_id', $_GET['s_product_id']);
        }

        if (isset($_GET['used_status']) && strlen($_GET['used_status']) > 0) {
            $this->db->where('s_used', $_GET['used_status']);
        }
        if (isset($_GET['s_country_id']) && strlen($_GET['s_country_id']) > 0) {
            $this->db->where('s_country_id', $_GET['s_country_id'], 'both');
        }
        if (isset($_GET['s_state_id']) && strlen($_GET['s_state_id']) > 0) {
            $this->db->where('s_state_id', $_GET['s_state_id'], 'both');
        }
        $this->db->where('s_used', '0');
        //$this->db->where('s_user_id >', 0);
        $this->db->from($this->db->table_serial_no);
        $result = $this->db->count_all_results();
        // echo "<pre>";print_r($this->db->last_query());exit();
        return $result;
    }

    public function totalNoOfAssignedSerialNumbers()
    {
        $user_type = $this->session->userdata('user_type');
        $user_company_id = $this->session->userdata('user_company_id');
        // $this->db->select('*');
        $this->db->select('count(*)');
        if ((string)$user_type != '0') {
            $this->db->where('s_company_id', $user_company_id);
        }
        $user_type = $this->session->userdata('user_type');
        $user_id = $this->session->userdata('user_id');

        if ($user_type == '0' || $user_type == '4') {
            $this->db->where('s_distributor_id >', '0');
        } else if ($user_type == '2') {
            $this->db->where('s_distributor_id', $user_id);
        } else if ($user_type == '1') {
            $this->db->where('s_dealer_id', $user_id);
        }

        // $this->db->where('s_used', '0');

        // if (isset($_GET['search']) && strlen($_GET['search']) > 0) {
        //     $this->db->like('s_serial_number', $_GET['search'], 'both');
        // }
        if (isset($_GET['search']) && strlen($_GET['search']) > 0) {
            $this->db->group_start();
            $this->db->or_like('s_serial_number', $_GET['search'], 'both');
            $this->db->or_like('s_imei', $_GET['search'], 'both');
            $this->db->group_end();
        }
        if (isset($_GET['used_status']) && strlen($_GET['used_status']) > 0) {
            if ($_GET['used_status'] == 2) {
                $this->db->where('s_used', '0');
            } else if ($_GET['used_status'] == 1) {
                $this->db->where('s_used', $_GET['used_status']);
            }
        }
        if (isset($_GET['s_country_id']) && strlen($_GET['s_country_id']) > 0) {
            $this->db->where('s_country_id', $_GET['s_country_id'], 'both');
        }
        if (isset($_GET['s_state_id']) && strlen($_GET['s_state_id']) > 0) {
            $this->db->where('s_state_id', $_GET['s_state_id'], 'both');
        }
        if (isset($_GET['company_id']) && strlen($_GET['company_id']) > 0) {
            $this->db->where('s_company_id', $_GET['company_id'], 'both');
        }
        if (isset($_GET['s_product_id']) && strlen($_GET['s_product_id']) > 0) {
            $this->db->where('s_product_id', $_GET['s_product_id']);
        }

        //$this->db->where('s_user_id >', 0);
        $this->db->from($this->db->table_serial_no);
        $result = $this->db->count_all_results();
        //echo $this->db->last_query();exit();
        return $result;
    }


    public function updateUserInfo($params)
    {
        $insertRecords = array();
        $insertRecords['user_password'] = isset($params['user_password']) ? $params['user_password'] : "";
        $this->db->where('user_id', $params['user_id']);
        $this->db->update($this->db->table_users, $insertRecords);
        return 1;
    }

    public function getUserInfo($params)
    {
        // $this->db->select('*');
        $this->db->select('user_id,user_name,user_email');
        $this->db->where('user_email = ', $params['email']);
        $this->db->from($this->db->table_users);
        $result = $this->db->get();
        $result = $result->row_array();
        return $result;
    }

    public function getUserInfobyid($id)
    {
        $this->db->select('*');
        $this->db->where('user_id = ', $id);
        $this->db->from($this->db->table_users);
        $result = $this->db->get();
        $result = $result->row_array();
        return $result;
    }


    // public function verify_exits_rto_number($rto_number, $id)
    public function verify_exits_rto_number($rto_number)
    {
        // $this->db->select('*');
        $this->db->select('rto_no, rto_place, rto_pwd, rto_number, state_name, state_id');
        $this->db->where('rto_number', $rto_number);
        /* if (strlen($id) > 0) {
            $this->db->where('rto_no !=', $id);
        } */

        $this->db->from($this->db->table_rto);
        $result = $this->db->get();
        $result = $result->result_array();

        if (empty($result)) {
            return true;
        }
        return false;
    }


    // Verify Exits Company Name
    public function verify_exits_model_make_records($model_name, $make_id, $id)
    {
        // $this->db->select('*');
        $this->db->select('ve_model_id,ve_make_id,ve_model_name');
        $this->db->where('ve_model_name', $model_name);
        $this->db->where('ve_make_id', $make_id);
        if (strlen($id) > 0) {
            $this->db->where('ve_model_id !=', $id);
        }
        $this->db->from($this->db->table_model);
        $result = $this->db->get();
        $result = $result->result_array();
        if (empty($result)) {
            return true;
        }
        return false;
    }


    public function verify_exits_model_make($model_name, $make_id)
    {
        // $this->db->select('*');
        $this->db->select('ve_model_id,ve_make_id,ve_model_name');
        $this->db->where('ve_model_name', $model_name);
        $this->db->where('ve_make_id', $make_id);
        $this->db->from($this->db->table_model);
        $result = $this->db->get();
        $result = $result->result_array();
        if (empty($result)) {
            return true;
        }
        return false;
    }


    // Verify Exits Company Name
    public function verify_exits_company_name($c_company_name, $id = "")
    {
        $this->db->select('*');
        $this->db->where('c_company_name', $c_company_name);
        if (strlen($id) > 0) {
            $this->db->where('c_company_id !=', $id);
        }
        $this->db->from($this->db->table_company);
        $result = $this->db->get();
        $result = $result->result_array();
        if (empty($result)) {
            return true;
        }
        return false;
    }

    public function verify_exits_product_name($p_product_name, $id = "")
    {
        // $this->db->select('*');
        $this->db->select('p_product_id, p_company_id, p_product_name');
        $this->db->where('p_product_name', $p_product_name);
        if (strlen($id) > 0) {
            $this->db->where('p_product_id !=', $id);
        }
        $this->db->from($this->db->table_products);
        $result = $this->db->get();
        $result = $result->result_array();
        if (empty($result)) {
            return true;
        }
        return false;
    }

    // Verify Exits Make Name
    public function verify_exits_make_name($make_name, $id = "")
    {
        // $this->db->select('*');
        $this->db->select('v_make_id,v_make_name');
        $this->db->where('v_make_name', $make_name);
        if (strlen($id) > 0) {
            $this->db->where('v_make_id !=', $id);
        }
        $this->db->from($this->db->table_make);
        $result = $this->db->get();
        $result = $result->result_array();
        if (empty($result)) {
            return true;
        }
        return false;
    }


    // Verify Exits Serial Number
    public function verify_exits_serial_number($serial_numbers, $id = "", $s_imei = '')
    {
        $this->db->select('s_serial_number,s_imei,s_mobile');
        if (is_string($serial_numbers) && strlen($serial_numbers) > 0) {
            $this->db->or_like('s_serial_number', $serial_numbers, 'none');
        }
        $params = array();
        $serial_numbers = explode(',', $serial_numbers);
        $params['serial_numbers'] = array_values(array_filter(array_unique($serial_numbers)));

        if (strlen($s_imei) > 0) {
            $this->db->or_like('s_imei', $s_imei, 'none');
        }

        foreach ($params['serial_numbers'] as $key => $value) {
            list($serial_number, $imei, $mobile) = explode('-', trim($value));
            //$this->db->where('s_serial_number',$value);
            // $this->db->or_where("FIND_IN_SET('$serial_number', s_serial_number) !=", 0);
            // $this->db->or_where("FIND_IN_SET('$imei', s_imei) !=", 0);
            // $this->db->or_where("FIND_IN_SET('$mobile', s_mobile) !=", 0);
            if (strlen($serial_number) > 0) {
                $this->db->or_like('s_serial_number', $serial_number, 'none');
            }
            if (strlen($imei) > 0) {
                $this->db->or_like('s_imei', $imei, 'none');
            }

            //$this->db->or_like('s_mobile',$mobile, 'none');

            //$this->db->or_where("FIND_IN_SET('$imei',s_imei) !=", 0);
            //$this->db->or_where("FIND_IN_SET('$mobile',s_mobile) !=", 0);
            if (strlen($id) > 0) {
                $this->db->where('s_serial_id !=', $id);
            }
        }

        $this->db->from($this->db->table_serial_no);
        $result = $this->db->get();
        $result = $result->result_array();
        //echo $this->db->last_query();exit();
        return $result;
    }



    // Bug Fixes Version 1.0 Developer: Lalith Kumar

    // Verify Exits Serial Number
    public function verify_exits_IMEI_numbers($serial_numbers, $id = "", $s_imei = '', $s_mobile, $s_mobile_2, $s_iccid, $s_state_id)
    {

        $this->db->select('s_serial_number,s_imei,s_mobile,s_mobile_2,s_iccid, s_state_id');
        $this->db->group_start();

        // Serial Number
        if (is_string($serial_numbers) && strlen($serial_numbers) > 0) {
            // OR-1
            $this->db->or_where('s_serial_number', $serial_numbers, 'none');
        }

        $params = array();
        $serial_numbers = explode(',', $serial_numbers);
        $params['serial_numbers'] = array_values(array_filter(array_unique($serial_numbers)));

        // IMEI Number
        if (strlen($s_imei) > 0) {
            // OR-2
            $this->db->or_where('s_imei', $s_imei, 'none');
        }

        if (strlen($s_mobile) > 0) {
            // OR-3
            $this->db->or_where('s_mobile', $s_mobile, 'none');
        }
        if (strlen($s_state_id) > 0) {
            // OR-2
            $this->db->or_where('s_state_id', $s_state_id, 'none');
        }
        if (strlen($s_mobile_2) > 0) {
            // OR-4
            $this->db->or_where('s_mobile_2', $s_mobile_2, 'none');
        }

        if (strlen($s_iccid) > 0) {
            // OR-5
            $this->db->or_where('s_iccid', $s_iccid, 'none');
        }

        $this->db->group_end();

        if (strlen($id) > 0) {
            $this->db->where('s_serial_id !=', $id);
        }

        $this->db->from($this->db->table_serial_no);
        $result = $this->db->get();
        $result = $result->result_array();
        //print_r($this->db->last_query()); exit();
        return $result;
    }


    public function verify_exits_IMEI_number($serial_numbers, $id, $s_imei, $s_iccid)
    {

        $this->db->select('s_serial_number,s_imei,s_mobile,s_mobile_2,s_iccid');
        $this->db->group_start();

        // Serial Number
        if (is_string($serial_numbers) && strlen($serial_numbers) > 0) {
            // OR-1
            $this->db->or_where('s_serial_number', $serial_numbers, 'none');
        }

        $params = array();
        $serial_numbers = explode(',', $id);
        $params['serial_numbers'] = array_values(array_filter(array_unique($serial_numbers)));

        // IMEI Number
        if (strlen($s_imei) > 0) {
            // OR-2
            $this->db->or_where('s_imei', $s_imei, 'none');
        }

        // if (strlen($s_mobile) > 0) {
        //     // OR-3
        //     $this->db->or_where('s_mobile', $s_mobile, 'none');
        // }
        // if (strlen($s_mobile_2) > 0) {
        //     // OR-4
        //     $this->db->or_where('s_mobile_2', $s_mobile_2, 'none');
        // }

        if (strlen($s_iccid) > 0) {
            // OR-5
            $this->db->or_where('s_iccid', $s_iccid, 'none');
        }

        $this->db->group_end();

        if (strlen($id) > 0) {
            $this->db->where('s_serial_id !=', $id);
        }

        $this->db->from($this->db->table_serial_no);
        $result = $this->db->get();
        $result = $result->result_array();
        // echo "<pre>";print_r($this->db->last_query()); exit();
        return $result;
    }

    // Verify Exits Serial Number In Renewal - Starts
    public function verify_renewal_serial_number_exists($serial_number, $vehicle_RC)
    {


        $this->db->select('veh_serial_no');
        $this->db->where('veh_serial_no', $serial_number);
        //$this->db->where('veh_rc_no !=', $vehicle_RC);
        $this->db->from($this->db->table_renewal);
        $result = $this->db->get();
        $result = $result->result_array();
        return $result;

        //echo $this->db->last_query();exit();
        /*if(empty($result))
			{
				return true;
			}
			return false;*/
    }
    // Verify Exits Serial Number In Renewal - Ends


    // Verify Exits Company Name
    public function verify_exits_product_tac_number($tac_number_list, $id = "")
    {
        $params['p_tac_no'] = array_values(array_filter(array_unique($tac_number_list)));

        $this->db->select('p_tac_no');
        foreach ($params['p_tac_no'] as $key => $value) {
            $this->db->or_where("FIND_IN_SET('$value',p_tac_no) !=", 0);
            if (strlen($id) > 0) {
                $this->db->where('p_product_id !=', $id);
            }
        }

        $this->db->from($this->db->table_products);
        $result = $this->db->get();
        $result = $result->result_array();
        return $result;
    }

    public function verify_exits_dealer_phone_number($phone_number, $id = "")
    {
        // $this->db->select('*');
        $this->db->select('user_id,user_name,user_phone,user_photo,user_email,user_own_company,user_info,user_company_id,user_type,user_distributor_id,users_rtono,user_status,state_id');
        $this->db->where('user_phone', $phone_number);
        if (strlen($id) > 0) {
            $this->db->where('user_id !=', $id);
        }
        $this->db->from($this->db->table_users);
        $result = $this->db->get();
        $result = $result->result_array();
        if (empty($result)) {
            return true;
        }
        return false;
    }

    public function verify_exits_dealer_email($user_email, $id = "")
    {
        // $this->db->select('*');
        $this->db->select('user_id,user_name,user_phone,user_photo,user_email,user_own_company,user_info,user_company_id,user_type,user_distributor_id,users_rtono,user_status,state_id');
        $this->db->where('user_email', $user_email);
        if (strlen($id) > 0) {
            $this->db->where('user_id !=', $id);
        }
        $this->db->from($this->db->table_users);
        $result = $this->db->get();
        $result = $result->result_array();
        if (empty($result)) {
            return true;
        }
        return false;
    }

    public function verify_exits_customer_phone_number($phone_number, $id = "")
    {
        // $this->db->select('*');
        $this->db->select('c_customer_id,c_customer_name,c_address,c_phone,c_photo,c_email,c_status,c_user_status');
        $this->db->where('c_phone', $phone_number);
        if (strlen($id) > 0) {
            $this->db->where('c_customer_id !=', $id);
        }
        $this->db->from($this->db->table_customers);
        $result = $this->db->get();
        $result = $result->result_array();
        if (empty($result)) {
            return true;
        }
        return false;
    }

    public function verify_exits_customer_email($user_email, $id = "")
    {
        // $this->db->select('*');
        $this->db->select('c_customer_id,c_customer_name,c_address,c_phone,c_photo,c_email,c_status,c_user_status');
        $this->db->where('c_email', $user_email);
        if (strlen($id) > 0) {
            $this->db->where('c_customer_id !=', $id);
        }
        $this->db->from($this->db->table_customers);
        $result = $this->db->get();
        $result = $result->result_array();
        if (empty($result)) {
            return true;
        }
        return false;
    }


    public function getCompanyInfo($id)
    {
        $this->db->select('*');
        $this->db->where('c_company_id', $id);
        $this->db->from($this->db->table_company);
        $result = $this->db->get();
        $result = $result->row_array();
        return $result;
    }

    public function getProductInfo($id)
    {
        $this->db->select('*');
        $this->db->where('p_product_id', $id);
        $this->db->from($this->db->table_products);
        $result = $this->db->get();
        $result = $result->row_array();
        return $result;
    }

    public function getMakeInfo($id)
    {
        $this->db->select('*');
        $this->db->where('v_make_id', $id);
        $this->db->from($this->db->table_make);
        $result = $this->db->get();
        $result = $result->row_array();
        return $result;
    }


    public function getdealerName($dealer_id)
    {
        $this->db->select('user_name');
        $this->db->from($this->db->table_users);
        $this->db->where('user_id', $dealer_id);

        $result = $this->db->get();
        //  echo $this->db->last_query();exit();
        $result = $result->row_array();

        return $result;
    }


    public function getDealerInfo($id)
    {


        $this->db->select('one.*,two.user_id as dis_id,two.user_name as dis_name');
        $this->db->where('one.user_id', $id);
        $this->db->from($this->db->table_users . ' as one');
        $this->db->join($this->db->table_users . ' as two', 'one.user_distributor_id = two.user_id', 'left');
        $result = $this->db->get();
        $result = $result->row_array();
        return $result;
    }

    //------------Add On Starts ------------

    public function Check_Certificate_Validity($id)
    {
        $this->db->select('*');
        $this->db->where('created_to', $id);
        $this->db->from($this->db->table_certificate);
        $result = $this->db->get();
        $result = $result->row_array();
        return $result;
    }

    public function Reduce_Certificate($id)
    {
        $insertRecords = array();
        $this->db->set('used', 'used+1', FALSE);
        $this->db->where('created_to', $id);
        $this->db->update($this->db->table_certificate);
        return 1;
    }

    //------------AddOn Ends ---------------


    public function verify_exits_vehicle_records($value, $field, $id = 0)
    {
        // $this->db->select('*');
        $this->db->select('veh_id,veh_rc_no,veh_chassis_no,veh_engine_no,veh_make_no,veh_model_no,veh_owner_id,veh_serial_no,veh_rto_no');
        $this->db->where($field, $value);
        if ((int)$id > 0) {
            $this->db->where('veh_id !=', $id);
        }
        $this->db->from($this->db->table_vehicle);
        $result = $this->db->get();
        $result = $result->result_array();
        if (empty($result)) {
            return true;
        }
        return false;
    }

    public function listofvehicle_old($limit, $offset, $search = '', $user_id = 0)
    {
        $user_type = $this->session->userdata('user_type');
        $user_company_id = $this->session->userdata('user_company_id');
        $this->db->select('veh.*,ser.s_serial_number');
        if ((string)$user_type != '0') {
            $this->db->where('ser.s_company_id', $user_company_id);
        }
        if (strlen($search) > 0) {
            $limit = 25;
            $offset = 0;
            $this->db->group_start();
            $this->db->like('veh.veh_rc_no', $search, 'both');

            // $this->db->or_like('veh.veh_rc_no', $search, 'both');
            $this->db->or_like('veh.veh_chassis_no', $search, 'both');
            $this->db->or_like('ser.s_serial_number', $search, 'both');
            $this->db->or_like('ser.s_imei', $search, 'both');
            $this->db->or_like('ser.s_iccid', $search, 'both');
            $this->db->or_like('veh.veh_invoice_no', $search, 'both');
            $this->db->or_like('veh.veh_owner_name', $search, 'both');
            $this->db->or_like('veh.veh_owner_phone', $search, 'both');
            $this->db->group_end();
        }
        if ((int)$user_id != 1) {
            $this->db->where('veh.veh_created_user_id', $user_id);
        }
        if ($_GET['start_date'] != 0 && $_GET['end_date'] != 0) {
            $from = $_GET['start_date'];
            $to = $_GET['end_date'];
            if (strtotime($_GET['end_date']) < strtotime($_GET['start_date'])) {
                $from = $_GET['end_date'];
                $to = $_GET['start_date'];
            }
            $this->db->where('veh.veh_create_date >=', $from);
            $this->db->where('veh.veh_create_date <=', $to);
        } elseif ($_GET['start_date'] != 0) {
            $this->db->where('veh.veh_create_date >=', $_GET['start_date']);
            $this->db->where('veh.veh_create_date <=', date('Y-m-d'));
        } elseif ($_GET['end_date'] != 0) {
            $this->db->where('veh.veh_create_date >=', date('Y-m-d'));
            $this->db->where('veh.veh_create_date <=', $_GET['end_date']);
        }

        if (isset($_GET['customer_id']) && strlen($_GET['customer_id']) > 0) {
            $this->db->where('veh.veh_owner_id', $_GET['customer_id']);
        }

        $this->db->from($this->db->table_vehicle . ' as veh');
        $this->db->join($this->db->table_serial_no . ' as ser', 'ser.s_serial_id = veh.veh_serial_no', 'left');

        $this->db->limit($limit, $offset);
        $this->db->order_by("veh.veh_id", "desc");
        $result = $this->db->get();
        $result = $result->result_array();
        // echo $this->db->last_query();exit();
        return $result;
    }


    public function listofvehicle($limit, $offset, $search = '', $user_id = 0, $dealer_id, $distributor_id, $startDate, $endDate, $state, $veh_rto_no)
    {
        $user_type = $this->session->userdata('user_type');
        $user_company_id = $this->session->userdata('user_company_id');
        $this->db->select('veh.*,ser.s_serial_number,ser.s_dealer_id,ser.s_imei,ser.s_distributor_id,ser.s_used');
        // echo "<pre>";print_r($this->db->last_query());exit();
        // echo "<pre>";print_r($distributor_id);exit;
        if ((string)$user_type != '0') {
            $this->db->where('ser.s_company_id', $user_company_id);
        }

        if ($startDate != 0 && $endDate != 0) {
            if (strtotime($_GET['end_date']) < strtotime($_GET['start_date'])) {
                $from = $_GET['end_date'];
                $to = $_GET['start_date'];
            }
            $this->db->where('veh.veh_create_date >=', $startDate);
            $this->db->where('veh.veh_create_date <=', $endDate);
        }

        if ((int)$dealer_id != 0) {
            $this->db->where('ser.s_dealer_id', $dealer_id);
        }
        if ((int)$distributor_id != 0) {
            $this->db->where('ser.s_distributor_id', $distributor_id);
        }
        if ((int)$state != 0) {
            $this->db->where('veh.veh_state_id', $state);
        }
        if ((int)$veh_rto_no != 0) {
            $this->db->where('veh.veh_rto_no', $veh_rto_no);
        }

        if (strlen($search) > 0) {
            $this->db->group_start();
            // $this->db->like('veh.veh_rc_no', $search, 'both');
            $this->db->like('veh.veh_chassis_no', $search, 'both');
            $this->db->or_like('veh.veh_rc_no', $search, 'both');
            $this->db->or_like('ser.s_serial_number', $search, 'both');
            $this->db->or_like('ser.s_imei', $search, 'both');
            $this->db->or_like('ser.s_iccid', $search, 'both');
            $this->db->or_like('veh.veh_invoice_no', $search, 'both');
            $this->db->or_like('veh.veh_owner_name', $search, 'both');
            $this->db->or_like('veh.veh_owner_phone', $search, 'both');
            $this->db->group_end();
        }

        if (isset($_GET['customer_id']) && strlen($_GET['customer_id']) > 0) {
            $this->db->where('veh.veh_owner_id', $_GET['customer_id']);
        }

        // old
        // $this->db->from($this->db->table_vehicle . ' as veh');
        // $this->db->join($this->db->table_serial_no . ' as ser', 'ser.s_serial_id = veh.veh_serial_no', 'left');

        $this->db->from($this->db->table_serial_no . ' as ser');
        $this->db->join($this->db->table_vehicle . ' as veh', 'ser.s_serial_id = veh.veh_serial_no', 'left');

        $this->db->where('ser.s_used', 1);
        $this->db->where('ser.s_status', 0);
        $this->db->limit($limit, $offset);
        $this->db->order_by("veh.veh_id", "desc");
        $result = $this->db->get();
        $result = $result->result_array();
        //   echo "<pre>";print_r($this->db->last_query());exit();
        // echo "<pre>";print_r($result);exit();
        // echo "<pre>";print_r($this->db->last_query());exit();
        return $result;
    }

    public function listofvehicledailyrun($limit, $offset, $search = '', $user_id = 0, $dealer_id, $distributor_id, $startDate, $endDate, $state, $veh_rto_no)
    {
        $user_type = $this->session->userdata('user_type');
        $user_company_id = $this->session->userdata('user_company_id');
        $this->db->select('veh.*,ser.s_serial_number,ser.s_dealer_id,ser.s_imei,ser.s_distributor_id,ser.s_used');
        // echo "<pre>";print_r($this->db->last_query());exit();
        // echo "<pre>";print_r($distributor_id);exit;
        if ((string)$user_type != '0') {
            $this->db->where('ser.s_company_id', $user_company_id);
        }

        if ($startDate != 0 && $endDate != 0) {
            if (strtotime($_GET['end_date']) < strtotime($_GET['start_date'])) {
                $from = $_GET['end_date'];
                $to = $_GET['start_date'];
            }
            $this->db->where('veh.veh_create_date >=', $startDate);
            $this->db->where('veh.veh_create_date <=', $endDate);
        }

        if ((int)$dealer_id != 0) {
            $this->db->where('ser.s_dealer_id', $dealer_id);
        }
        if ((int)$distributor_id != 0) {
            $this->db->where('ser.s_distributor_id', $distributor_id);
        }
        if ((int)$state != 0) {
            $this->db->where('veh.veh_state_id', $state);
        }
        if ((int)$veh_rto_no != 0) {
            $this->db->where('veh.veh_rto_no', $veh_rto_no);
        }

        if (strlen($search) > 0) {
            $this->db->group_start();
            // $this->db->like('veh.veh_rc_no', $search, 'both');
            $this->db->like('veh.veh_chassis_no', $search, 'both');
            $this->db->or_like('veh.veh_rc_no', $search, 'both');
            $this->db->or_like('ser.s_serial_number', $search, 'both');
            $this->db->or_like('ser.s_imei', $search, 'both');
            $this->db->or_like('ser.s_iccid', $search, 'both');
            $this->db->or_like('veh.veh_invoice_no', $search, 'both');
            $this->db->or_like('veh.veh_owner_name', $search, 'both');
            $this->db->or_like('veh.veh_owner_phone', $search, 'both');
            $this->db->group_end();
        }

        if (isset($_GET['customer_id']) && strlen($_GET['customer_id']) > 0) {
            $this->db->where('veh.veh_owner_id', $_GET['customer_id']);
        }

        $this->db->from($this->db->table_serial_no . ' as ser');
        $this->db->join($this->db->table_vehicle . ' as veh', 'ser.s_serial_id = veh.veh_serial_no', 'left');

        $this->db->where('ser.s_used', 1);
        $this->db->where('ser.s_status', 0);
        $this->db->limit($limit, $offset);
        $this->db->order_by("veh.veh_id", "desc");
        $result = $this->db->get();
        $result = $result->result_array();
        // echo "<pre>"; print_r($result); exit;
        if ($startDate != 0 && $endDate != 0) {
            if (strtotime($_GET['end_date']) < strtotime($_GET['start_date'])) {
                $from = $_GET['end_date'];
                $to = $_GET['start_date'];
                $startDate = date('m/d/Y', strtotime($_GET['start_date'])); // Output: 2024-09-03

            }
        }else{
            $startDate = date('m/d/Y', strtotime('now')); // Output: 2024-09-03
            // $to = date('m/d/Y', strtotime('now')); // Output: 2024-09-03

        }
        $vehicleArr = array();
        foreach ($result as $key => $value) {
            // echo "<pre>"; print_r($startDate); exit;
            $data = array();
            $data = $this->getIgnitionDatasByImei($value['s_imei'],$startDate,$sessdata = "");
            if(!empty($data)){
                $result[$key]['ignition_data'] = $data[0];
                array_push($vehicleArr,$result[$key]);
                $vehicleArr[0]['count'] = count($vehicleArr);
            }else{
                $result[$key]['ignition_data'] = array();
            }

        }
        //   echo "<pre>";print_r($this->db->last_query());exit();
        // echo "<pre>";print_r($vehicleArr);exit();
        // echo "<pre>";print_r($this->db->last_query());exit();
        return $vehicleArr;
    }

    function sumHrsMinsSectoTotalSecs($ignon){
		$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $ignon);

		sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);

		$time_seconds = $hours * 3600 + $minutes * 60 + $seconds;
		return $time_seconds;
    }

    function totalSecondsToGetDayHrsMins_new($totalSeconds){
		$final = "";
		if($totalSeconds!=0){
			$time = $totalSeconds; // time duration in seconds
		
			$days = floor($time / (60 * 60 * 24));
			$time -= $days * (60 * 60 * 24);
			
			$hours = floor($time / (60 * 60));
			$time -= $hours * (60 * 60);
			
			$minutes = floor($time / 60);
			$time -= $minutes * 60;
			
			$seconds = floor($time);
			$time -= $seconds;
	
			$final = "{$hours}h {$minutes}m {$seconds}s";
		}
		return $final;
    }

    function sumoftime($params,$paramtype){
	
        $finalstr  = "";
        
        if(sizeof($params)!=0){
            
            $params_arr = array();
            
            $sumofhrs	= 0;
            $sumofmins  = 0;
            $sumofsecs  = 0;	
            
            
            for($i=0;$i<sizeof($params);$i++){
                
                $params_arr = explode(":",$params[$i][$paramtype]);
                
                $sumofhrs	= $sumofhrs+$params_arr[0];
                $sumofmins  = $sumofmins+$params_arr[1];
                $sumofsecs  = $sumofsecs+$params_arr[2];
                
            }	
            
            $finalstr  = $sumofhrs.":".$sumofmins.":".$sumofsecs;
            
            
        }
    
        return $finalstr;	
        
        }
    
    public function getIgnitionDatasByImei($imei, $txtfromdate,$sessdata = "")
    {
	    $subquery100		= '';
        $finaldatastr = array();
        $start_end_ignition_details = array();
	   // echo "<pre>";print_r($txtfromdate);exit;
		if($sessdata!=""){ $subquery100 = " where t2.customer_id = ( select t1.c_customer_id from ci_customers t1 where t1.c_phone = '".$sessdata."' ) "; }
		$vechicledatas = $this->db->query("select t2.s_imei from ci_serial_numbers t2 WHERE t2.s_imei=".$imei)->result();
        // echo "<pre>"; print_r($vechicledatas); exit;
		
		$vehiclenos = '';
		foreach($vechicledatas as $row) {
			$vehiclenos .= "'".$row->s_imei."',";
		}
		
		if($vehiclenos!=""){
			$vehiclenos = substr($vehiclenos,0,strlen($vehiclenos)-1);
		}	
		
		$subquery101    = '';
		
		if($vehiclenos!=""){
			$subquery101    = " where t1.imei in (".$vehiclenos.") ";
		}
		
		// $DB2 	= $this->load->database('postgre_db', TRUE);
		$mainDB = $this->load->database('default', TRUE);
        $result = $mainDB
        ->select('s_imei, s_state_id, s_country_id, state.s_key') 
        ->from($mainDB->table_serial_no)
        ->where('s_imei', $imei)
        ->join($mainDB->table_state, 'state.id = ' . $mainDB->table_serial_no . '.s_state_id', 'left') 
        ->get()
        ->row_array();
        $databaseName = 'PSDN_' . $result['s_key'];
		
        $trimmedStr = trim($databaseName);
        
        $DB2 = $this->load->database($trimmedStr, TRUE);
        // echo "<pre>"; print_r($DB2); exit;

		if (!$DB2->initialize()) {
			$data = [];
			log_message('error', 'DB2 connection initialization failed');
			return $data;
		}
		
		
		$parts = explode("/", $txtfromdate);
        $month = $parts[0];
        $date  = $parts[1];
        $year  = $parts[2];
        $table_name_check = "tbl_trackinghistory_" . $month.$year;
        // echo "<pre>"; print_r($table_name_check); exit;

        // Get the current month and year
        $current_month = date('m');
        $current_year = date('Y');
        
        
        //new date format
        $reqDate = $year.'-'.$month.'-'.$date;
        
        // Check if the table exists
        $table_exists = $DB2->table_exists($table_name_check);
        
        if ($table_exists) {
            $table_name = "tbl_trackinghistory_" . $month.$year;
        } 
        else if($month == $current_month && $year == $current_year){
            $table_name = "tbl_trackingalldatas";
        }
        else{
             $table_name = "";
             $finaldatastr ="";
             return $finaldatastr;
        }
        
         $reqDate = $year.'-'.$month.'-'.$date;
        // echo "<pre>";print_r($table_name);exit;
        
		$subquery 		= " where 1=1 ";
		$subquery1 		= " where 1=1 ";
		
		$txtfromdate 	= $this->dateconversion($txtfromdate); 
		// $txttodate 		= $this->dateconversion($txttodate);
        // echo "<pre>";print_r($txtfromdate);exit;
		
		$txtfromdate_final 	= $reqDate." 00:00:00";
		$txttodate_final	= $reqDate." 23:59:59";	
		
		$subquery  .= " AND t2.gpsdatetime_comp between '".$txtfromdate_final."' and '".$txttodate_final."' ";
		$subquery1 .= " AND t20.gpsdatetime_comp between '".$txtfromdate_final."' and '".$txttodate_final."'  ";
		
		if($vehiclenos!=""){
		    $datas 	= $DB2->query("select t2.imei from ( SELECT t1.*, concat(t1.gps_date,' ',t1.gps_time) as gpsdatetime_comp  FROM public.".$table_name." t1 ".$subquery101." ) as t2 ".$subquery." GROUP BY t2.imei ")->result();

            // echo "<pre>";print_r("select t2.imei from ( SELECT t1.*, concat(t1.gps_date,' ',t1.gps_time) as gpsdatetime_comp  FROM public.tbl_trackingalldatas t1 ".$subquery101." ) as t2 ".$subquery." GROUP BY t2.imei ");exit;
            
            $finaldatastr = array();
		
		    //foreach($datas as $row) {
			
			$vehicle_reg_no_inner = $imei;
			$subquery101    = " where t1.imei = '".$imei."' ";
			
			$datas_inner 	= $DB2->query("select temp.imei, temp.vehicle_reg_no, temp.gps_date, temp.gps_time, temp.latitude, temp.longitude, temp.vehicle_speed, temp.id, temp.gpsdatetime_comp, temp.ignition from ( select t2.* from ( SELECT t1.*, concat(t1.gps_date,' ',t1.gps_time) as gpsdatetime_comp  FROM public.".$table_name." t1 ".$subquery101." ) as t2 ".$subquery." AND t2.imei = '".$vehicle_reg_no_inner."' ) as temp order by temp.gpsdatetime_comp asc")->result();
            // echo "select temp.imei, temp.vehicle_reg_no, temp.gps_date, temp.gps_time, temp.latitude, temp.longitude, temp.vehicle_speed, temp.id, temp.gpsdatetime_comp, temp.ignition from ( select t2.* from ( SELECT t1.*, concat(t1.gps_date,' ',t1.gps_time) as gpsdatetime_comp  FROM public.".$table_name." t1 ".$subquery101." ) as t2 ".$subquery." AND t2.imei = '".$vehicle_reg_no_inner."' ) as temp order by temp.gpsdatetime_comp asc";exit;
                // 			echo "<pre>";print_r("select temp.imei, temp.vehicle_reg_no, temp.gps_date, temp.gps_time, temp.latitude, temp.longitude, temp.vehicle_speed, temp.id, temp.gpsdatetime_comp, temp.ignition from ( select t2.* from ( SELECT t1.*, concat(t1.gps_date,' ',t1.gps_time) as gpsdatetime_comp  FROM public.".$table_name." t1 ".$subquery101." ) as t2 ".$subquery." AND t2.imei = '".$vehicle_reg_no_inner."' ) as temp order by temp.gpsdatetime_comp asc");exit;
			//$datas_inner 	= $DB2->query(" select temp.* from ( select t2.* from ( SELECT t1.*, concat(t1.gps_date,' ',t1.gps_time) as gpsdatetime_comp  FROM public.tbl_trackingalldatas t1 ".$subquery101." ) as t2 ".$subquery." AND t2.imei = '".$vehicle_reg_no_inner."' ) as temp order by temp.gpsdatetime_comp asc ")->result();
			
			$previous_igninition_on 		= "";
			$previous_igninition_on_date 	= "";

			$previous_igninition_on_latitude = "";
			$previous_igninition_on_longitude = "";
			$previous_igninition_off_latitude = "";
			$previous_igninition_off_longitude = "";


			$previous_igninition_off 		= "";
			$previous_igninition_off_date 	= "";

			$ignition_on_datas				= array();
			$ignition_off_datas				= array();
			$igninition_on_date_details	= array();
			
			$prev_ign_on_datas				= "";
			$prev_ign_off_datas				= "";

			$startdate_ign_onoff			= "";
			
			$iicount 						= 1;
			// echo "<pre>";print_r($datas_inner);exit;
            foreach ($datas_inner as $row_inner) {
				$imei = $row_inner->imei;
				if (!isset($grouped_data[$imei])) {
					$grouped_data[$imei] = array();
				}
				$grouped_data[$imei][] = $row_inner;

			}
		}
			// echo "<pre>";print_r($grouped_data);exit;

        ///NEW CODE - 03-09-2024
        foreach ($grouped_data as $imei => $group) {
            // echo "<pre>";print_r($group);exit;
		    
            //code end		    
           $previous_igninition_on 		= "";
           $previous_igninition_on_date 	= "";

           $previous_igninition_off 		= "";
           $previous_igninition_off_date 	= "";

           $ignition_on_datas				= array();
           $ignition_off_datas				= array();

           $prev_ign_on_datas				= "";
           $prev_ign_off_datas				= "";

           $startdate_ign_onoff			= "";
         
           $previous_igninition_on_latitude = "";
           $previous_igninition_on_longitude = "";
           $previous_igninition_off_latitude = "";
           $previous_igninition_off_longitude = "";


           $igninition_on_date_details	= array();
           $iicount 						= 1;
            // echo "<pre>"; print_r($group);exit;
           foreach($group as $row_inner) {
               
               $ignition_status		= $row_inner->ignition;
               $last_updatedate		= $row_inner->gpsdatetime_comp;	
    
               $last_latitude	= $row_inner->latitude;
               $last_longitude	= $row_inner->longitude;
               // echo $ignition_status." -> ".$last_updatedate."<br>";
            //    echo "<pre>";print_r($row_inner);exit;

               if($iicount=="1"){
                   $startdate_ign_onoff	= $last_updatedate;
               }

               if($previous_igninition_on=="" and $previous_igninition_off==""){
       
                    if($ignition_status=="1"){

                        $previous_igninition_on 		= "1";
                        $previous_igninition_on_date 	= $last_updatedate;			
                        $prev_ign_on_datas				= $last_updatedate;
                        if($last_latitude!="0"){
                            $previous_igninition_on_latitude 	= $last_latitude;			
                            $previous_igninition_on_longitude 	= $last_longitude;
                        }
                    }
                    else if($ignition_status=="0"){
                        
                        $previous_igninition_off 		= "0";
                        $previous_igninition_off_date 	= $last_updatedate;
                        $prev_ign_off_datas				= $last_updatedate;

                        if($last_latitude!="0"){
                            $previous_igninition_off_latitude 	= $last_latitude;			
                            $previous_igninition_off_longitude 	= $last_longitude;
                        }
                    }
               } else if($previous_igninition_on=="1" and $previous_igninition_off=="" and $ignition_status=="0"){
 
                   $date1 = new DateTime($previous_igninition_on_date);
                   $date2 = $date1->diff(new DateTime($last_updatedate));
               	// echo "<pre>";print_r($date1);
               	// echo "<pre>";print_r($date2);exit;
                   $igninition_on_report["imei"] = $imei;
					
                   $dateformat = explode("-",$previous_igninition_on_date);
                   $dateVal = explode(" ",$dateformat[2]);
                   $finaldate_previous_igninition_on_date = $dateVal[0].'/'.$dateformat[1].'/'.$dateformat[0] .' '.$dateVal[1];
                       
                   $igninition_on_report["start_date"] = $finaldate_previous_igninition_on_date;

                   $igninition_on_report["start_location"] = $previous_igninition_on_latitude.' - '.$previous_igninition_on_longitude;
                   $igninition_on_report["status"] = 1;

                   $ignition_on_datas_str 			= $date2->h.":".$date2->i.":".$date2->s;		
                   $ignition_on_datas[]			= array("ignitionon" => $ignition_on_datas_str);
                   
                   $previous_igninition_on 		= "";
                   $previous_igninition_on_date 	= "";
                   $previous_igninition_on_latitude = "";
                   $previous_igninition_on_longitude = "";
                   $previous_igninition_off 		= "0";
                   $previous_igninition_off_date 	= $last_updatedate;
                   
                   $prev_ign_on_datas				= $last_updatedate;

                   $dateformat = explode("-",$prev_ign_on_datas);
                   $dateVal = explode(" ",$dateformat[2]);
                   $finaldate_prev_ign_on_datas = $dateVal[0].'/'.$dateformat[1].'/'.$dateformat[0] .' '.$dateVal[1];
                   $igninition_on_report["end_date"] = $finaldate_prev_ign_on_datas;

                   $igninition_on_report["end_location"] = $last_latitude .' - '.$last_longitude;
                   // $igninition_on_report["duration"] = $date2->h.":".$date2->i.":".$date2->s;
                   $ignonSecs  = $this->sumHrsMinsSectoTotalSecs($date2->h.":".$date2->i.":".$date2->s);
                   $finalIgnon  = $this->totalSecondsToGetDayHrsMins_new($ignonSecs);
                   $igninition_on_report["duration"] = $finalIgnon;
                   $igninition_on_date_details[] = $igninition_on_report;
                   $ignition_on_datas[]			= array("ignitionon" => $ignition_on_datas_str);

                   
               }	
               else if($previous_igninition_on=="" and $previous_igninition_off=="0" and $ignition_status=="1"){
                   
                   $date1 = new DateTime($previous_igninition_off_date);
                   $date2 = $date1->diff(new DateTime($last_updatedate));

                   $igninition_off_report["imei"] = $imei;

                   $dateformat = explode("-",$previous_igninition_off_date);
                   $dateVal = explode(" ",$dateformat[2]);
                   $finaldate_previous_igninition_off_date = $dateVal[0].'/'.$dateformat[1].'/'.$dateformat[0] .' '.$dateVal[1];

                   $igninition_off_report["start_date"] = $finaldate_previous_igninition_off_date;
                   $igninition_off_report["start_location"] = $previous_igninition_off_latitude.' - '.$previous_igninition_off_longitude;
                   $igninition_off_report["status"] = 0;

                   $ignition_off_datas_str 		= $date2->h.":".$date2->i.":".$date2->s;		
                   $previous_igninition_on_latitude = $last_latitude;
                   $previous_igninition_on_longitude = $last_longitude;
                   $previous_igninition_on 		= "1";
                   $previous_igninition_on_date 	= $last_updatedate;
                
                    
                    $previous_igninition_off 		= "";
                    $previous_igninition_off_date 	= "";
                    $previous_igninition_off_latitude 	= "";
                    $previous_igninition_off_longitude 	= "";
                    $prev_ign_off_datas				= $last_updatedate;
                    $ignonSecs  = $this->sumHrsMinsSectoTotalSecs($date2->h.":".$date2->i.":".$date2->s);
                    $finalIgnon  = $this->totalSecondsToGetDayHrsMins_new($ignonSecs);

                    $igninition_off_report["duration"] = $finalIgnon;
                    $igninition_off_report["end_location"] = $last_latitude .' - '.$last_longitude;
                    // $igninition_off_report["end_date"] = $last_updatedate;
                    $dateformat = explode("-",$last_updatedate);
                    $dateVal = explode(" ",$dateformat[2]);
                    $finaldate_last_updatedate = $dateVal[0].'/'.$dateformat[1].'/'.$dateformat[0] .' '.$dateVal[1];
                    $igninition_off_report["end_date"] = $finaldate_last_updatedate;

                    // $prev_ign_off_datas				= $last_updatedate;
                    
                    $igninition_on_date_details[] = $igninition_off_report;
                   $ignition_off_datas[]			= array("ignitionoff" => $ignition_off_datas_str);

               }
               
               
               $iicount++;
               $start_end_ignition_details= $igninition_on_date_details;
               
           }
			// echo "<pre>";print_r($start_end_ignition_details);exit;
       
        //   echo "<pre>"; print_r($ignition_off_datas);exit;
            if(sizeof($ignition_off_datas)=="0" and sizeof($ignition_on_datas)=="0"){	

                if($ignition_status=="1"){
                    $date1 = new DateTime($startdate_ign_onoff);
                    $date2 = $date1->diff(new DateTime($last_updatedate));
                    
                    $ignition_off_datas_str 		= $date2->h.":".$date2->i.":".$date2->s;		
                    $ignition_on_datas[]			= array("ignitionoff" => $ignition_off_datas_str);
                }
                else if($ignition_status=="0"){
                    $date1 = new DateTime($startdate_ign_onoff);
                    $date2 = $date1->diff(new DateTime($last_updatedate));
                    
                    $ignition_on_datas_str 		= $date2->h.":".$date2->i.":".$date2->s;		
                    $ignition_off_datas[]		= array("ignitionoff" => $ignition_on_datas_str);
                }	
                
            }else{	

                if($ignition_status=="1"){	
            
                    $date1 = new DateTime($prev_ign_off_datas);
                    $date2 = $date1->diff(new DateTime($last_updatedate));
                    
                    $ignition_off_datas_str 		= $date2->h.":".$date2->i.":".$date2->s;		
                    $ignition_on_datas[]			= array("ignitionoff" => $ignition_off_datas_str);
                    
                }
                else if($ignition_status=="0"){

                    $date1 = new DateTime($prev_ign_on_datas);
                    $date2 = $date1->diff(new DateTime($last_updatedate));
                    
                    $ignition_on_datas_str 		= $date2->h.":".$date2->i.":".$date2->s;		
                    $ignition_off_datas[]		= array("ignitionoff" => $ignition_on_datas_str);
               
                }
            }	
            
      
            if(sizeof($ignition_on_datas)=="0"){
                $ignition_on_datas[]			= array("ignitionoff" => "00:00:00");
            }	
            
            if(sizeof($ignition_off_datas)=="0"){
                $ignition_off_datas[]		= array("ignitionoff" => "00:00:00");	
            }	
            
            $ignon  = $this->sumoftime($ignition_on_datas,'ignitionon');
            $ignoff = $this->sumoftime($ignition_off_datas,'ignitionoff');
            			// echo "<pre>";print_r($ignition_on_datas);print_r($ignition_off_datas);exit;
            			// echo "<pre>";print_r($ignition_off_datas);exit;
            
            $ignon ="";
            $ignoff = "";
            $ignon  = $this->sumoftime($ignition_on_datas,'ignitionon');
            $ignoff = $this->sumoftime($ignition_off_datas,'ignitionoff');
            // echo "<pre>";print_r($ignition_on_datas); echo " "; print_r($ignition_off_datas);exit;
            
            $ignonSecs = "";
            $ignoffSecs = "";
            $ignonSecs  = $this->sumHrsMinsSectoTotalSecs($ignon);
            $ignoffSecs  = $this->sumHrsMinsSectoTotalSecs($ignoff);

            $finalIgnon = "";
            $finalIgnoff = "";
            $finalIgnon  = $this->totalSecondsToGetDayHrsMins_new($ignonSecs);
            $finalIgnoff  = $this->totalSecondsToGetDayHrsMins_new($ignoffSecs);
            if(!empty($start_end_ignition_details)){
                $count = sizeof($start_end_ignition_details);
                $count = $count-1;
                $ignition_data['first_ignition_on'] = $start_end_ignition_details[0]['start_date'];
                $ignition_data['start_location'] = $start_end_ignition_details[0]['start_location'];
                $ignition_data['last_ignition_off'] = $start_end_ignition_details[$count]['end_date'];
                $ignition_data['end_location'] = $start_end_ignition_details[$count]['end_location'];
            }else{
                $ignition_data['first_ignition_on'] = "";
                $ignition_data['start_location'] = "";
                $ignition_data['last_ignition_off'] = "";
                $ignition_data['end_location'] = "";
            }
            
            if($finalIgnon==""){
                $finalIgnon = "0d 0h 0m 0s";
            }else{

            }
            if($finalIgnoff==""){
                $finalIgnoff = "0d 0h 0m 0s";
            }
            
               // echo "<pre>"; print_r($ignition_data);exit;
            
            $finaldatastr[] = array(
                "fleetno" => $imei,
                "ignon" => $finalIgnon,
                "ignoff" => $finalIgnoff,
                "first_ignition_on" =>$ignition_data['first_ignition_on'],
                "start_location" =>$ignition_data['start_location'],
                "last_ignition_off" =>$ignition_data['last_ignition_off'],
                "end_location" =>$ignition_data['end_location']
            );
            
            // $finaldatastr[] = (object) array("fleetno" => $vehicle_reg_no_inner, "ignon" => $ignon, "ignoff" => $ignoff, "dooropen" => "0:0:0", "doorclose" => "0:0:0", "acon" => "0:0:0", "acoff" => "0:0:0");
        }
        // $finaldatastr[] = (object) array("fleetno" => $vehicle_reg_no_inner, "ignon" => $ignon, "ignoff" => $ignoff, "dooropen" => "0:0:0", "doorclose" => "0:0:0", "acon" => "0:0:0", "acoff" => "0:0:0");
        
    	
		// echo "<pre>";print_r($finaldatastr); exit;
		// print_r($finaldatastr1);
		// print_r($ignition_on_datas);
		// print_r($ignition_on_datas);
		return $finaldatastr;
    }
    public function dateconversion($param){
	 
		$param 		= str_replace("/","-",$param);
		$param_arr 	= explode("-",$param);
		
		$param_str  = $param_arr[2]."-".$param_arr[1]."-".$param_arr[0];
		
		return $param_str;
		
	 
   }

    //     public function listofvehicle($limit, $offset, $search = '', $user_id = 0 , $dealer_id, $distributor_id, $startDate , $endDate , $scales , $state)
    //     {
    //         $user_type = $this->session->userdata('user_type');
    //         $user_company_id = $this->session->userdata('user_company_id');
    //         $this->db->select('veh.*,ser.s_serial_number,ser.s_dealer_id,ser.s_imei,ser.s_distributor_id,ser.s_used');
    //         // echo "<pre>";print_r($distributor_id);exit;
    //         if ((string)$user_type != '0') {
    //             $this->db->where('ser.s_company_id', $user_company_id);
    //         }

    //         if($scales!="off"){
    //             $from = $_GET['start_date'];
    //             $to = $_GET['end_date'];
    //             if (strtotime($_GET['end_date']) < strtotime($_GET['start_date'])) {
    //                 $from = $_GET['end_date'];
    //                 $to = $_GET['start_date'];
    //             }
    //             $this->db->where('veh.veh_create_date >=', $from);
    //             $this->db->where('veh.veh_create_date <=', $to); 

    //         }
    //         if((int)$dealer_id != 0)
    // 		{
    // 			$this->db->where('ser.s_dealer_id', $dealer_id);
    // 		} 
    //         if((int)$distributor_id != 0)
    // 		{
    // 			$this->db->where('ser.s_distributor_id', $distributor_id);
    // 		}
    //         if((int)$state != 0)
    // 		{
    // 			$this->db->where('veh_state_id', $state);
    // 		}

    //         if (strlen($search) > 0) {
    //             $this->db->group_start();
    //             $this->db->like('veh.veh_rc_no', $search, 'both');
    //             $this->db->or_like('veh.veh_chassis_no', $search, 'both');
    //             $this->db->or_like('ser.s_serial_number', $search, 'both');
    //             $this->db->or_like('ser.s_imei', $search, 'both');
    //             $this->db->or_like('ser.s_iccid', $search, 'both');
    //             $this->db->or_like('veh.veh_invoice_no', $search, 'both');
    //             $this->db->or_like('veh.veh_owner_name', $search, 'both');
    //             $this->db->or_like('veh.veh_owner_phone', $search, 'both');
    //             $this->db->group_end();
    //         }        

    //         // $user_type = $this->session->userdata('user_type');
    //         // if($user_type != 0){
    //         //     if ((int)$user_id != 1) {
    //         //         $this->db->where('veh_created_user_id', $user_id);
    //         //     }  
    //         // }

    //         if (isset($_GET['customer_id']) && strlen($_GET['customer_id']) > 0) {
    //             $this->db->where('veh.veh_owner_id', $_GET['customer_id']);
    //         }

    //         $this->db->from($this->db->table_vehicle . ' as veh');
    //         $this->db->join($this->db->table_serial_no . ' as ser', 'ser.s_serial_id = veh.veh_serial_no', 'left');
    //         $this->db->where('ser.s_used', 1);
    //         $this->db->limit($limit, $offset);
    //         $this->db->order_by("veh.veh_id", "desc");
    //         $result = $this->db->get();
    //         $result = $result->result_array();
    //         //   echo "<pre>";print_r($this->db->last_query());exit();
    //         // echo "<pre>";print_r($result);exit();
    //         // echo "<pre>";print_r($this   ->db->last_query());exit();
    //         return $result;
    //     }

    public function listofInvoices($limit, $offset, $search = '', $user_id = 0)
    {
        $user_type = $this->session->userdata('user_type');

        $user_company_id = $this->session->userdata('user_company_id');
        //print_r($user_type);exit();

        $subquery = " where 1=1 ";


        if ($_GET['user_type']) {
            $subquery .= " AND t1.i_user_type = '" . $_GET['user_type'] . "' ";
        }

        if ((int)$user_id != 1) {

            //$subquery			.= " AND ( t1.i_user_id = '".$user_id."' OR t1.i_created_by = '".$user_id."' ) and t1.i_user_id != '0' ";

            $subquery .= " AND ( t1.i_user_id = '" . $user_id . "' )  ";
        } else {

            $subquery .= " AND t1.i_user_id != '0' ";
        }

        if (isset($_GET['search']) && strlen($_GET['search']) > 0) {
            $subquery .= " AND t1.invoice_number like '%" . $_GET['search'] . "%' ";
        }

        if ($_GET['start_date'] != 0 && $_GET['end_date'] != 0) {
            $from = $_GET['start_date'];
            $to = $_GET['end_date'];
            if (strtotime($_GET['end_date']) < strtotime($_GET['start_date'])) {
                $from = $_GET['end_date'];
                $to = $_GET['start_date'];
            }

            $subquery .= " AND ( t1.i_created_date >='" . $from . "' AND t1.i_created_date <= '" . $to . "' ) ";
        } elseif ($_GET['start_date'] != 0) {
            $subquery .= " AND ( t1.i_created_date >='" . $_GET['start_date'] . "' AND t1.i_created_date <= '" . date('Y-m-d') . "' ) ";
        } elseif ($_GET['end_date'] != 0) {

            $subquery .= " AND ( t1.i_created_date >='" . date('Y-m-d') . "' AND t1.i_created_date <= '" . $_GET['end_date'] . "' ) ";
        }


        $query = " select t1.*, t2.*, t3.*, (SELECT t4.user_name FROM ci_users t4 WHERE t4.user_id = t1.i_created_by) as user_name2 from ci_invoices t1 left join ci_products t2 on t1.i_product_id = t2.p_product_id left join ci_users t3 on t3.user_id = t1.i_user_id " . $subquery . " order by t1.i_invoice_id desc limit " . $offset . "," . $limit;

        $results = $this->db->query($query)->result_array();

        return $results;
    }


    // Add on - addition Starts &&&&&&&&&&&

    public function listofvehicleRenewals($limit, $offset, $search = '', $user_id = 0)
    {
        $this->db->select('*');

        if (strlen($search) > 0) {
            $this->db->or_like('veh_rc_no', $search, 'both');
            $this->db->or_like('veh_chassis_no', $search, 'both');
            $this->db->or_like('veh_serial_no', $search, 'both');
            $this->db->or_like('veh_invoice_no', $search, 'both');
        }
        if ((int)$user_id != 1) {
            $this->db->where('veh_created_user_id', $user_id);
        }
        if ($_GET['start_date'] != 0 && $_GET['end_date'] != 0) {
            $from = $_GET['start_date'];
            $to = $_GET['end_date'];
            if (strtotime($_GET['end_date']) < strtotime($_GET['start_date'])) {
                $from = $_GET['end_date'];
                $to = $_GET['start_date'];
            }
            $this->db->where('veh_create_date >=', $from);
            $this->db->where('veh_create_date <=', $to);
        } elseif ($_GET['start_date'] != 0) {
            $this->db->where('veh_create_date >=', $_GET['start_date']);
            $this->db->where('veh_create_date <=', date('Y-m-d'));
        } elseif ($_GET['end_date'] != 0) {
            $this->db->where('veh_create_date >=', date('Y-m-d'));
            $this->db->where('veh_create_date <=', $_GET['end_date']);
        }
        $this->db->from($this->db->table_renewal);
        //$this->db->join($this->db->table_serial_no.' as ser', 'ser.s_serial_id = veh.veh_serial_no', 'left');

        $this->db->limit($limit, $offset);
        $this->db->order_by("veh_id", "desc");
        $result = $this->db->get();
        $result = $result->result_array();
        //echo $this->db->last_query();exit();
        return $result;
    }

    public function totalNoOfVehicleRenewals($user_id = 0)
    {
        // $this->db->select('*');
        $this->db->select('count(*)');
        if ((int)$user_id != 1) {
            $this->db->where('veh_created_user_id', $user_id);
        }
        if (isset($_GET['search']) && strlen($_GET['search']) > 0) {

            $this->db->or_like('veh_rc_no', $_GET['search'], 'both');
            $this->db->or_like('veh_chassis_no', $_GET['search'], 'both');
            $this->db->or_like('veh_serial_no', $_GET['search'], 'both');
            $this->db->or_like('veh_invoice_no', $_GET['search'], 'both');
        }
        if ($_GET['start_date'] != 0 && $_GET['end_date'] != 0) {
            $from = $_GET['start_date'];
            $to = $_GET['end_date'];
            if (strtotime($_GET['end_date']) < strtotime($_GET['start_date'])) {
                $from = $_GET['end_date'];
                $to = $_GET['start_date'];
            }
            $this->db->where('veh_create_date >=', $from);
            $this->db->where('veh_create_date <=', $to);
        } elseif ($_GET['start_date'] != 0) {
            $this->db->where('veh_create_date >=', $_GET['start_date']);
            $this->db->where('veh_create_date <=', date('Y-m-d'));
        } elseif ($_GET['end_date'] != 0) {
            $this->db->where('veh_create_date >=', date('Y-m-d'));
            $this->db->where('veh_create_date <=', $_GET['end_date']);
        }
        $this->db->from($this->db->table_renewal);
        $result = $this->db->count_all_results();
        return $result;
    }

    // Add on - addition Ends &&&&&&&&&&&
    public function totalNoOfVehicle_old($user_id = 0)
    {
        $user_type = $this->session->userdata('user_type');
        $user_company_id = $this->session->userdata('user_company_id');

        $this->db->select('*');
        if ((string)$user_type != '0') {
            $this->db->where('veh_company_id', $user_company_id);
        }
        if ((int)$user_id != 1) {
            $this->db->where('veh_created_user_id', $user_id);
        }
        if (isset($_GET['search']) && strlen($_GET['search']) > 0) {

            // $this->db->or_like('veh_rc_no', $_GET['search'], 'both');
            // $this->db->or_like('veh_chassis_no', $_GET['search'], 'both');
            // $this->db->or_like('veh_serial_no', $_GET['search'], 'both');
            // $this->db->or_like('veh_invoice_no', $_GET['search'], 'both');
            $search = $_GET['search'];
            $this->db->group_start();
            $this->db->like('veh_rc_no', $search, 'both');

            // $this->db->or_like('veh.veh_rc_no', $search, 'both');
            $this->db->or_like('veh_chassis_no', $search, 'both');
            $this->db->or_like('veh_owner_name', $search, 'both');
            $this->db->or_like('veh_owner_phone', $search, 'both');
            $this->db->group_end();
        }
        if ($_GET['start_date'] != 0 && $_GET['end_date'] != 0) {
            $from = $_GET['start_date'];
            $to = $_GET['end_date'];
            if (strtotime($_GET['end_date']) < strtotime($_GET['start_date'])) {
                $from = $_GET['end_date'];
                $to = $_GET['start_date'];
            }
            $this->db->where('veh_create_date >=', $from);
            $this->db->where('veh_create_date <=', $to);
        } elseif ($_GET['start_date'] != 0) {
            $this->db->where('veh_create_date >=', $_GET['start_date']);
            $this->db->where('veh_create_date <=', date('Y-m-d'));
        } elseif ($_GET['end_date'] != 0) {
            $this->db->where('veh_create_date >=', date('Y-m-d'));
            $this->db->where('veh_create_date <=', $_GET['end_date']);
        }
        if (isset($_GET['customer_id'])) {
            $this->db->where('veh_owner_id', $_GET['customer_id']);
        }
        $this->db->from($this->db->table_vehicle);
        $result = $this->db->count_all_results();
        return $result;
    }

    public function totalNoOfVehicle($search, $user_id = 0, $dealer_id, $distributor_id, $startDate, $endDate, $state, $veh_rto_no)
    {

        $user_type = $this->session->userdata('user_type');
        $user_company_id = $this->session->userdata('user_company_id');
        $this->db->select('*');
        if ((string)$user_type != '0') {
            $this->db->where('ser.s_company_id', $user_company_id);
        }

        if ($startDate != 0 && $endDate != 0) {
            // echo "<pre>";print_r(strtotime($_GET['end_date']));
            // echo "<pre>";print_r(strtotime($_GET['start_date']));exit;
            if (strtotime($_GET['end_date']) < strtotime($_GET['start_date'])) {
                $from = $_GET['end_date'];
                $to = $_GET['start_date'];
            }
            $this->db->where('veh.veh_create_date >=', $startDate);
            $this->db->where('veh.veh_create_date <=', $endDate);
        }


        if ((int)$dealer_id != 0) {
            $this->db->where('ser.s_dealer_id', $dealer_id);
        }
        if ((int)$distributor_id != 0) {
            $this->db->where('ser.s_distributor_id', $distributor_id);
        }
        if ((int)$state != 0) {
            $this->db->where('veh.veh_state_id', $state);
        }
        if ((int)$veh_rto_no != 0) {
            $this->db->where('veh.veh_rto_no', $veh_rto_no);
        }

        if (strlen($search) > 0) {
            $this->db->group_start();
            $this->db->like('veh.veh_rc_no', $search, 'both');
            $this->db->or_like('veh.veh_chassis_no', $search, 'both');
            $this->db->or_like('ser.s_serial_number', $search, 'both');
            $this->db->or_like('ser.s_imei', $search, 'both');
            $this->db->or_like('ser.s_iccid', $search, 'both');
            $this->db->or_like('veh.veh_invoice_no', $search, 'both');
            $this->db->or_like('veh.veh_owner_name', $search, 'both');
            $this->db->or_like('veh.veh_owner_phone', $search, 'both');
            $this->db->group_end();
        }

        if (isset($_GET['customer_id']) && strlen($_GET['customer_id']) > 0) {
            $this->db->where('veh.veh_owner_id', $_GET['customer_id']);
        }

        $this->db->where('ser.s_used', 1);
        $this->db->where('ser.s_status', 0);
        $this->db->from($this->db->table_serial_no . ' as ser');
        $this->db->join($this->db->table_vehicle . ' as veh', 'ser.s_serial_id = veh.veh_serial_no', 'left');

        // $this->db->from($this->db->table_vehicle . ' as veh');
        // $this->db->join($this->db->table_serial_no . ' as ser', 'ser.s_serial_id = veh.veh_serial_no', 'left');

        $result = $this->db->count_all_results();
        // echo $this->db->last_query();exit();
        // echo "<pre>";print_r($result);exit;
        return $result;
    }

    //     public function totalNoOfVehicle( $search, $user_id = 0, $dealer_id, $distributor_id, $scales , $state)
    //     {
    //         $user_type = $this->session->userdata('user_type');
    //         $user_company_id = $this->session->userdata('user_company_id');
    //         $this->db->select('*');       
    //         if ((string)$user_type != '0') {
    //             $this->db->where('ser.s_company_id', $user_company_id);
    //         }

    //         if($scales!="off"){
    //             $from = $_GET['start_date'];
    //             $to = $_GET['end_date'];
    //             if (strtotime($_GET['end_date']) < strtotime($_GET['start_date'])) {
    //                 $from = $_GET['end_date'];
    //                 $to = $_GET['start_date'];
    //             }
    //             $this->db->where('veh.veh_create_date >=', $from);
    //             $this->db->where('veh.veh_create_date <=', $to); 

    //         }
    //         if((int)$dealer_id != 0)
    // 		{
    // 			$this->db->where('ser.s_dealer_id', $dealer_id);
    // 		} 
    //         if((int)$distributor_id != 0)
    // 		{
    // 			$this->db->where('ser.s_distributor_id', $distributor_id);
    // 		}
    //         if((int)$state != 0)
    // 		{
    // 			$this->db->where('veh_state_id', $state);
    // 		}

    //         if (strlen($search) > 0) {
    //             $this->db->group_start();
    //             $this->db->like('veh.veh_rc_no', $search, 'both');
    //             $this->db->or_like('veh.veh_chassis_no', $search, 'both');
    //             $this->db->or_like('ser.s_serial_number', $search, 'both');
    //             $this->db->or_like('ser.s_imei', $search, 'both');
    //             $this->db->or_like('ser.s_iccid', $search, 'both');
    //             $this->db->or_like('veh.veh_invoice_no', $search, 'both');
    //             $this->db->or_like('veh.veh_owner_name', $search, 'both');
    //             $this->db->or_like('veh.veh_owner_phone', $search, 'both');
    //             $this->db->group_end();
    //         }

    //         if (isset($_GET['customer_id']) && strlen($_GET['customer_id']) > 0) {
    //             $this->db->where('veh.veh_owner_id', $_GET['customer_id']);
    //         }

    //         $this->db->where('ser.s_used', 1);
    //         $this->db->from($this->db->table_vehicle . ' as veh');
    //         $this->db->join($this->db->table_serial_no . ' as ser', 'ser.s_serial_id = veh.veh_serial_no', 'left');
    //         // echo $this->db->last_query();exit();
    //         $result = $this->db->count_all_results();
    //         // echo "<pre>";print_r($result);exit;
    //         return $result;
    //     }

    public function totalNoOfInvoices($user_id = 0)
    {
        $user_type = $this->session->userdata('user_type');
        $user_company_id = $this->session->userdata('user_company_id');


        $subquery = " where 1=1 ";

        if ($_GET['user_type']) {
            $subquery .= " AND t1.i_user_type = '" . $_GET['user_type'] . "' ";
        }

        if ((int)$user_id != 1) {

            $subquery .= " AND ( t1.i_user_id = '" . $user_id . "' OR t1.i_created_by = '" . $user_id . "' ) and t1.i_user_id != '0' ";
        } else {
            $subquery .= " AND t1.i_user_id != '0' ";
        }

        if (isset($_GET['search']) && strlen($_GET['search']) > 0) {
            $subquery .= " AND t1.invoice_number like '%" . $_GET['search'] . "%' ";
        }

        if ($_GET['start_date'] != 0 && $_GET['end_date'] != 0) {
            $from = $_GET['start_date'];
            $to = $_GET['end_date'];
            if (strtotime($_GET['end_date']) < strtotime($_GET['start_date'])) {
                $from = $_GET['end_date'];
                $to = $_GET['start_date'];
            }

            $subquery .= " AND ( t1.i_created_date >='" . $from . "' AND t1.i_created_date <= '" . $to . "' ) ";
        } elseif ($_GET['start_date'] != 0) {
            $subquery .= " AND ( t1.i_created_date >='" . $_GET['start_date'] . "' AND t1.i_created_date <= '" . date('Y-m-d') . "' ) ";
        } elseif ($_GET['end_date'] != 0) {

            $subquery .= " AND ( t1.i_created_date >='" . date('Y-m-d') . "' AND t1.i_created_date <= '" . $_GET['end_date'] . "' ) ";
        }

        $query = " select t1.* from ci_invoices t1 " . $subquery;

        $results = $this->db->query($query)->result_array();

        // return $query;
        return count($results);
    }


    public function getVehicleInfo($id, $user = 0)
    {
        // $this->db->select('veh.*,cus.c_email as veh_owner_email,mod.ve_model_name,ser.s_serial_number');
        $this->db->select('veh.*,cus.c_email as veh_owner_email,mod.ve_model_name,ser.s_serial_number,ser.s_imei,rto.rto_number, state.s_name as stateName');
        $this->db->where('veh.veh_id', $id);
        $this->db->from($this->db->table_vehicle . ' as veh');
        $this->db->join($this->db->table_model . ' as mod', 'veh.veh_model_no = mod.ve_model_id', 'left');
        $this->db->join($this->db->table_rto . ' as rto', 'veh.veh_rto_no = rto.rto_no', 'left');
        $this->db->join($this->db->table_serial_no . ' as ser', 'ser.s_serial_id = veh.veh_serial_no', 'left');
        $this->db->join($this->db->table_customers . ' as cus', 'cus.c_phone = veh.veh_owner_phone', 'left');
        $this->db->join($this->db->table_state . ' as state', 'state.id = veh.veh_state_id', 'left');
        $result = $this->db->get();
        $result = $result->row_array();
        // echo $this->db->last_query(); exit;

        // echo "<pre>";print_r($result);exit;
        return $result;
    }

    public function getVehicleInfoData($id, $user = 0)
    {
        // $this->db->select('veh.*,cus.c_email as veh_owner_email,mod.ve_model_name,ser.s_serial_number');
        // $this->db->select('veh.*,cus.c_email as veh_owner_email,mod.ve_model_name,ser.s_serial_number,rto.rto_number');
        $this->db->select('veh.veh_rc_no,veh.veh_owner_name,veh.veh_owner_phone,cus.c_email as veh_owner_email,mod.ve_model_name,ser.s_serial_number,ser.s_iccid,ser.s_state_id,ser.s_country_id,ser.s_imei,ser.s_serial_id,rto.rto_number, user.user_name as dealer_name, user.user_phone as dealer_phone, user.user_id as dealer_id');
        $this->db->where('veh_id', $id);
        $this->db->from($this->db->table_vehicle . ' as veh');
        $this->db->join($this->db->table_model . ' as mod', 'veh.veh_model_no = mod.ve_model_id', 'left');
        $this->db->join($this->db->table_rto . ' as rto', 'veh.veh_rto_no = rto.rto_no', 'left');
        $this->db->join($this->db->table_serial_no . ' as ser', 'ser.s_serial_id = veh.veh_serial_no', 'left');
        $this->db->join($this->db->table_customers . ' as cus', 'cus.c_phone = veh.veh_owner_phone', 'left');
        $this->db->join($this->db->table_users . ' as user', 'user.user_id = ser.s_dealer_id', 'left');
        $result = $this->db->get();
        $result = $result->row_array();

        return $result;
    }

    public function getPdfRenewalVehicleInfo($id)
    {
        $this->db->select('veh.*,ue.user_info,rto.rto_number,rto.rto_place,com.c_company_name,mke.v_make_name,model.ve_model_name');
        $this->db->where('veh.veh_id', $id);
        $this->db->from($this->db->table_renewal . ' veh');
        $this->db->join($this->db->table_users . ' ue', 'veh.veh_created_user_id = ue.user_id', 'left');
        $this->db->join($this->db->table_rto . ' rto', 'rto.rto_no = veh.veh_rto_no', 'left');
        $this->db->join($this->db->table_company . ' com', 'com.c_company_id = veh.veh_company_id', 'left');
        $this->db->join($this->db->table_make . ' mke', 'mke.v_make_id = veh.veh_make_no', 'left');
        //$this->db->join($this->db->table_serial_no.' ser', 'ser.s_serial_id = veh.veh_serial_no', 'left');
        $this->db->join($this->db->table_model . ' model', 'model.ve_model_id = veh.veh_model_no', 'left');
        $result = $this->db->get();
        //  echo $this->db->last_query();exit();
        $result = $result->row_array();

        return $result;
    }

    public function getPdfVehicleInfo($id)
    {
        $this->db->select('veh.*,ue.user_info,rto.rto_number,rto.rto_place,com.c_company_name,mke.v_make_name,ser.s_serial_number,ser.s_dealer_id,ser.s_imei,ser.s_iccid,ser.s_mobile,ser.s_mobile_2,model.ve_model_name,veh_customer.c_email, table_invoices_customer.invoice_number');
        $this->db->where('veh.veh_id', $id);
        $this->db->from($this->db->table_vehicle . ' veh');
        $this->db->join($this->db->table_users . ' ue', 'veh.veh_created_user_id = ue.user_id', 'left');
        $this->db->join($this->db->table_rto . ' rto', 'rto.rto_no = veh.veh_rto_no', 'left');
        $this->db->join($this->db->table_company . ' com', 'com.c_company_id = veh.veh_company_id', 'left');
        $this->db->join($this->db->table_make . ' mke', 'mke.v_make_id = veh.veh_make_no', 'left');
        $this->db->join($this->db->table_serial_no . ' ser', 'ser.s_serial_id = veh.veh_serial_no', 'left');
        $this->db->join($this->db->table_model . ' model', 'model.ve_model_id = veh.veh_model_no', 'left');
        $this->db->join($this->db->table_customers . ' veh_customer', 'veh_customer.c_customer_id = veh.veh_owner_id', 'left');
        $this->db->join($this->db->table_invoices_customer . ' table_invoices_customer', 'table_invoices_customer.i_to_customer_id = veh.veh_owner_id', 'left');
        $result = $this->db->get();
        //echo $this->db->last_query();exit();
        $result = $result->row_array();
        // echo "<pre>";print_r($id);exit;
        return $result;
    }

    public function getPdfInvoiceInfo($id)
    {
        $this->db->select('inv.*,prod.*,brand.*,usr.user_name,usr.user_email, usr.user_photo as companylogo, usr.user_own_company as FromCompany, usr.gstin,usr.user_info,usr2.user_name as user_name2,usr2.user_email as user_email2, usr2.user_own_company as ToCompany, usr2.gstin as gstin2,usr2.user_info as user_info2');
        $this->db->where('inv.i_invoice_id', $id);

        $this->db->from($this->db->table_invoices . ' as inv');
        $this->db->join($this->db->table_products . ' as prod', 'prod.p_product_id = inv.i_product_id', 'left');
        $this->db->join($this->db->table_company . ' as brand', 'prod.p_company_id = brand.c_company_id', 'left');
        $this->db->join($this->db->table_users . ' as usr', 'usr.user_id = inv.i_created_by', 'left');
        $this->db->join($this->db->table_users . ' as usr2', 'usr2.user_id = inv.i_user_id', 'left');
        $result = $this->db->get();
        //  echo $this->db->last_query();exit();
        $result = $result->row_array();

        return $result;
    }

    public function getPdfInvoiceSerialsInfo($serial_ids)
    {
        // $this->db->select('*');
        $this->db->select('s_serial_id,s_serial_number,s_imei,s_mobile,s_user_type,admin_price,distributor_price,dealer_price,s_created_date,assign_to_distributer_on,p_product_id,p_company_id,p_product_name,p_unit_price,p_product_description,p_created_date');
        $this->db->from($this->db->table_serial_no);
        $this->db->join($this->db->table_products, 's_product_id = p_product_id', 'left');
        $this->db->where_in('s_serial_id', $serial_ids);
        $this->db->order_by("s_serial_id");
        $result = $this->db->get();
        $result = $result->result_array();
        //echo $this->db->last_query();exit();
        return $result;
    }

    public function getUserIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function userTracking()
    {
        // echo "return data found for item===>"; exit; 
        $this->db->insert($this->db->table_user_histroy, array(
            'user_id' => $this->session->userdata('user_id'),
            'user_type' => $this->session->userdata('user_type'),
            'action_name' => "Login Successfully",
            'ip_address' => $this->getUserIpAddr()
        ));
    }

    public function listofInvoices_customers($limit, $offset, $search = '', $user_id = 0)
    {
        $user_type = $this->session->userdata('user_type');
        $user_company_id = $this->session->userdata('user_company_id');

        $subquery = " where 1=1 ";
        if ($user_type == 2) {

            $subquery1 = "t1.i_created_by = '" . $user_id . "' ";

            $query = " select distinct  t1.*, (SELECT  t4.user_name FROM ci_users t4 WHERE t4.user_id = t1.i_user_id) as user_name3 ,(SELECT  t4.user_name FROM ci_users t4 WHERE t4.user_id = t1.i_created_by) as user_name2 from ci_invoices t1   where " . $subquery1 . "  ";

            $results = $this->db->query($query)->result_array();
            //echo"<pre>";print_r($results);exit();

            return $results;
            exit();
        } else if ($user_type == 0) {


            $subquery1 = "t1.i_created_by = '" . $user_id . "' ";

            $query = " select distinct  t1.*, (SELECT  t4.user_name FROM ci_users t4 WHERE t4.user_id = t1.i_user_id) as user_name3 ,(SELECT  t4.user_name FROM ci_users t4 WHERE t4.user_id = t1.i_created_by) as user_name2 from ci_invoices t1   where " . $subquery1 . "  ";

            $results = $this->db->query($query)->result_array();
            //echo"<pre>";print_r($results);exit();

            return $results;
            exit();
        }

        if ($_GET['user_type']) {
            $subquery .= " AND t1.i_user_type = '" . $_GET['user_type'] . "' ";
        }

        if ((int)$user_id != 1) {

            $subquery .= " AND ( t1.i_user_id = '" . $user_id . "' OR t1.i_created_by = '" . $user_id . "' ) and t1.i_user_id != '0' ";
        } else {
            $subquery .= " AND t1.i_user_id != '0' ";
        }

        if (isset($_GET['search']) && strlen($_GET['search']) > 0) {
            $subquery .= " AND t1.invoice_number like '%" . $_GET['search'] . "%' ";
        }

        if ($_GET['start_date'] != 0 && $_GET['end_date'] != 0) {
            $from = $_GET['start_date'];
            $to = $_GET['end_date'];
            if (strtotime($_GET['end_date']) < strtotime($_GET['start_date'])) {
                $from = $_GET['end_date'];
                $to = $_GET['start_date'];
            }

            $subquery .= " AND ( t1.i_created_date >='" . $from . "' AND t1.i_created_date <= '" . $to . "' ) ";
        } elseif ($_GET['start_date'] != 0) {
            $subquery .= " AND ( t1.i_created_date >='" . $_GET['start_date'] . "' AND t1.i_created_date <= '" . date('Y-m-d') . "' ) ";
        } elseif ($_GET['end_date'] != 0) {

            $subquery .= " AND ( t1.i_created_date >='" . date('Y-m-d') . "' AND t1.i_created_date <= '" . $_GET['end_date'] . "' ) ";
        }

        $query = " select t1.*, t2.*, t3.*, (SELECT t4.user_name FROM ci_users t4 WHERE t4.user_id = t1.i_user_id) as user_name2, (SELECT t4.c_customer_name FROM ci_customers t4 WHERE t4.c_customer_id = t1.i_to_customer_id) as user_name3 from ci_invoices_customer t1 left join ci_products t2 on t1.i_product_id = t2.p_product_id left join ci_users t3 on t3.user_id = t1.i_to_customer_id " . $subquery . " order by t1.i_invoice_id desc limit " . $offset . "," . $limit;
        $results = $this->db->query($query)->result_array();
        //echo"<pre>";print_r($results);exit();

        return $results;
    }

    public function totalNoOfInvoices_customers($user_id = 0)
    {
        $user_type = $this->session->userdata('user_type');

        $user_company_id = $this->session->userdata('user_company_id');


        //echo "id".$user_company_id; exit;

        $subquery = " where 1=1 ";

        if ($_GET['user_type']) {
            $subquery .= " AND t1.i_user_type = '" . $_GET['user_type'] . "' ";
        }

        if ((int)$user_id != 1) {

            $subquery .= " AND ( t1.i_user_id = '" . $user_id . "' OR t1.i_created_by = '" . $user_id . "' ) and t1.i_user_id != '0' ";
        } else {
            $subquery .= " AND t1.i_user_id != '0' ";
        }

        if (isset($_GET['search']) && strlen($_GET['search']) > 0) {
            $subquery .= " AND t1.invoice_number like '%" . $_GET['search'] . "%' ";
        }

        if ($_GET['start_date'] != 0 && $_GET['end_date'] != 0) {
            $from = $_GET['start_date'];
            $to = $_GET['end_date'];
            if (strtotime($_GET['end_date']) < strtotime($_GET['start_date'])) {
                $from = $_GET['end_date'];
                $to = $_GET['start_date'];
            }

            $subquery .= " AND ( t1.i_created_date >='" . $from . "' AND t1.i_created_date <= '" . $to . "' ) ";
        } elseif ($_GET['start_date'] != 0) {
            $subquery .= " AND ( t1.i_created_date >='" . $_GET['start_date'] . "' AND t1.i_created_date <= '" . date('Y-m-d') . "' ) ";
        } elseif ($_GET['end_date'] != 0) {

            $subquery .= " AND ( t1.i_created_date >='" . date('Y-m-d') . "' AND t1.i_created_date <= '" . $_GET['end_date'] . "' ) ";
        }

        $query = " select t1.* from ci_invoices_customer t1 " . $subquery;
        $results = $this->db->query($query)->result_array();
        $datas = count($results);
        return $datas;
    }

    public function getPdfInvoiceInfo_customers($id)
    {
        $this->db->select('inv.*, prod.*, brand.*, usr.user_name, usr.user_email, usr.user_photo as companylogo, usr.user_own_company as FromCompany, usr.gstin, usr.acc_no, usr.acc_name,usr.acc_branch,usr.acc_ifsc_code,usr.user_info, customers.c_customer_name, customers.c_address, customers.c_email, customers.c_phone');
        $this->db->where('inv.i_invoice_id', $id);

        $this->db->from('ci_invoices_customer' . ' as inv');
        $this->db->join($this->db->table_products . ' as prod', 'prod.p_product_id = inv.i_product_id', 'left');
        $this->db->join($this->db->table_company . ' as brand', 'prod.p_company_id = brand.c_company_id', 'left');
        $this->db->join($this->db->table_users . ' as usr', 'usr.user_id = inv.i_user_id', 'left');
        $this->db->join($this->db->table_customers . ' as customers', 'customers.c_customer_id = inv.i_to_customer_id', 'left');
        $result = $this->db->get();
        //echo $this->db->last_query();exit();
        $result = $result->row_array();

        return $result;
    }

    function validateLatitude($latitude)
    {
        // Check if the latitude is a valid number within the range -90 to 90
        if (preg_match('/^[-]?((\d|[1-8]\d)(\.\d+)?|90(\.0+)?)$/', $latitude)) {
            return true;
        }
        return false;
    }

    function validateLongitude($longitude)
    {
        // Check if the longitude is a valid number within the range -180 to 180
        if (preg_match('/^[-]?((\d|\d\d|1[0-7]\d)(\.\d+)?|180(\.0+)?)$/', $longitude)) {
            return true;
        }
        return false;
    }

    public function veh_lat_long_check()
    {
        $DB2 = $this->load->database('postgre_db', TRUE);
        $DB2->select('imei,latitude,longitude');
        $DB2->from($DB2->tbl_registered_device_data);
        $results = $DB2->get();
        $results = $results->result_array();
        // echo "<pre>";print_r($DB2->last_query());exit;
        $data = array();
        // echo "<pre>";print_r($results);exit;
        $validCount = 0;
        $invalidCount = 0;
        $validLatLangArray = array();
        $invalidLatLangArray = array();

        foreach ($results as $key => $value) {
            $dataObj = array();
            if ($this->validateLatitude($value['latitude']) && $this->validateLongitude($value['longitude'])) {
                $validCount++;
                $validLatLangArray[] = array(
                    'imei' => $value['imei']
                );
            } else {
                $invalidCount++;
                $validLatLangArray[] = array(
                    'imei' => $value['imei']
                );
            }
        }
        // echo "<pre>";print_r($validLatLangArray);exit;
        return array(
            'validCount' => $validCount,
            'invalidCount' => $invalidCount
        );
    }

    // public function veh_lat_long()
    // {
    //     $otherdb = $this->load->database('tracking', TRUE);
    //     $otherdb->select('vehicleRegNumber,vtrackingId,imei,latitude,longitude,distance');
    //     $otherdb->from($otherdb->table_trackings);
    //     $results = $otherdb->get();
    //     $results = $results->result_array();
    //     $data= array();
    //     //  echo "<pre>";print_r($results);exit;
    //     foreach ($results as $key => $value) {
    //         $dataObj= array();
    //         if ($this->validateLatitude($value['latitude']) && $this->validateLongitude($value['longitude'])) {
    //             $dataObj['vehicleRegNumber'] = $value['vehicleRegNumber'];
    //             $dataObj['vtrackingId'] = $value['vtrackingId'];
    //             $dataObj['imei'] = $value['imei'];
    //             $dataObj['latitude'] = $value['latitude'];
    //             $dataObj['longitude'] = $value['longitude'];
    //             $dataObj['distance'] = $value['distance'];

    //         }
    //         else {
    //             $dataObj['vehicleRegNumber'] = $value['vehicleRegNumber'];
    //             $dataObj['vtrackingId'] = $value['vtrackingId'];
    //             $dataObj['imei'] = $value['imei'];
    //             $dataObj['latitude'] = "00.0000";
    //             $dataObj['longitude'] = "00.0000";
    //             $dataObj['distance'] = $value['distance'];
    //         }
    //         array_push($data, $dataObj);
    //         // else {
    //         //     $invalidIMEIs[] = $value['imei'];
    //         // }
    //         // if (!empty($dataObj)) {
    //         //     array_push($data, $dataObj);
    //         // }
    //     }
    //     // $invalidIMEIsString =  "'" .implode("','", $invalidIMEIs) . "'";
    //     // // echo "<pre>";print_r($invalidIMEIs);exit;

    //     // $DB2 = $this->load->database('postgre_db', TRUE);
    //     // $query = "select DISTINCT ON (imei) imei,latitude,longitude from public.tbl_trackingalldatas where imei in (".$invalidIMEIsString.") AND latitude BETWEEN -90 AND 90 AND longitude BETWEEN -180 AND 180 And latitude != 0 And longitude != 0  ORDER BY imei,server_reached DESC";
    //     // echo $query;exit();
    //     // $invalidData  = $DB2->query($query)->result();

    //     // echo "<pre>";print_r($data);exit;
    //     // foreach ($invalidData as $key => $value) {

    //     //     $dataObj['vehicleRegNumber'] = '';
    //     //     $dataObj['vtrackingId'] = '';
    //     //     $dataObj['imei'] = $value->imei;
    //     //     $dataObj['latitude'] = $value->latitude;
    //     //     $dataObj['longitude'] = $value->longitude;
    //     //     $dataObj['distance'] = '';

    //     //     array_push($data, $dataObj);
    //     // }
    //     return $data;
    //     // return $results;
    // }

    public function veh_lat_long()
    {
        $otherdb = $this->load->database('tracking', TRUE);
        $otherdb->select('vehicleRegNumber,vtrackingId,imei,latitude,longitude,distance');
        $otherdb->from($otherdb->table_trackings);
        $results = $otherdb->get();
        $results = $results->result_array();
        $data = array();
        $invalidIMEIs = array();
        //  echo "<pre>";print_r($results);exit;
        foreach ($results as $key => $value) {
            $dataObj = array();
            if ($this->validateLatitude($value['latitude']) && $this->validateLongitude($value['longitude'])) {
                $dataObj['vehicleRegNumber'] = $value['vehicleRegNumber'];
                $dataObj['vtrackingId'] = $value['vtrackingId'];
                $dataObj['imei'] = $value['imei'];
                $dataObj['latitude'] = $value['latitude'];
                $dataObj['longitude'] = $value['longitude'];
                $dataObj['distance'] = $value['distance'];
            } else {
                $dataObj['vehicleRegNumber'] = $value['vehicleRegNumber'];
                $dataObj['vtrackingId'] = $value['vtrackingId'];
                $dataObj['imei'] = $value['imei'];
                $dataObj['latitude'] = "12.345";
                $dataObj['longitude'] = "12.345";
                $dataObj['distance'] = $value['distance'];
            }
            array_push($data, $dataObj);
        }

        // echo "<pre>";print_r($data);exit;

        return $data;
    }

    public function getValitLatLngByImie($imei, $table_name)
    {
        $DB2 = $this->load->database('postgre_db', TRUE);
        $query = "select DISTINCT ON (imei) imei,latitude,longitude from public." . $table_name . " where imei = '" . $imei . "' AND latitude BETWEEN -90 AND 90 AND longitude BETWEEN -180 AND 180 And latitude != 0 And longitude != 0  ORDER BY imei,server_reached DESC";
        // echo "<pre>";print_r($query);exit;
        $data  = $DB2->query($query)->result();
        return $data;
    }

    // public function search_vehicle($imei_no)
    // {
    //     $otherdb = $this->load->database('tracking', TRUE);
    //     $otherdb->select('latitude,longitude');
    //     $otherdb->where('imei', $imei_no);
    //     $otherdb->from($otherdb->table_trackings);
    //     $result = $otherdb->get();
    //     $results = $result->result_array();
    //     return $results;
    // } 

    public function getIMEISerialInfo($imei)
    {
        $this->db->select('ser.*');
        $this->db->where('ser.s_imei = ', $imei);
        $this->db->from($this->db->table_serial_no . ' as ser');
        $result = $this->db->get();
        $result = $result->row_array();
        return $result;
    }

    public function updateOTAForIMEI($params)
    {
        $update_data = [
            'IMEI'          => $params['imei'],
            'RespHandling'  => $params['selectedVal'],
            'CreateTime'    => date('Y-m-d H:i:s'),
            'LastUpdatedBy' => $this->session->userdata('user_id')
        ];

        $DB2 = $this->load->database('postgre_db', TRUE);

        $query = 'INSERT INTO public.tbl_change_otadata ("IMEI", "RespHandling", "LastUpdatedBy") VALUES (\'' . $update_data["IMEI"] . '\', \'' . $update_data["RespHandling"] . '\', \'' . $update_data["LastUpdatedBy"] . '\')';
        // echo $query; exit;
        return $DB2->query($query);
    }

    public function getUnregisteredDeviceDataCopy($params)
    {
        $otherdb1 = $this->load->database('postgre_db', TRUE);
        $days_ago = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - 3, date("Y")));
        $date = date('Y-m-d') . ' 00:00:00';
        $dayValue = date('Y-m-d H:i:s');
        $otherdb1->select('count(*)');
        $otherdb1->from($otherdb1->tbl_unregistered_device_data);
        $otherdb1->where('created_time <', $days_ago);
        $results = $otherdb1->get();
        $results = $results->result_array();

        $insertRecords = array();
        $insertRecords['date'] = $days_ago;
        $insertRecords['count'] = $results[0]['count'];
        $insertRecords['created_at'] = $dayValue;
        $otherdb1->insert($otherdb1->tbl_cron_unregistered_records, $insertRecords);

        if ($results[0]['count'] != 0) {
            $otherdb1->where('created_time <', $days_ago);
            $otherdb1->delete($otherdb1->tbl_unregistered_device_data);
        }
        return 1;
    }


    public function getUnregisteredDeviceData($params)
    {
        $otherdb1 = $this->load->database('postgre_db', TRUE);

        $days_ago = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - 3, date("Y")));
        $date = date('Y-m-d') . ' 00:00:00';
        $dayValue = date('Y-m-d H:i:s');

        // Count query
        $countQuery = "SELECT COUNT(*) AS count FROM " . $otherdb1->tbl_unregistered_device_data . " WHERE created_time < '" . $days_ago . "'";
        $countResult = $otherdb1->query($countQuery)->result_array();

        // Insert query
        $insertQuery = "INSERT INTO " . $otherdb1->tbl_cron_unregistered_records . " (date, count, created_at) VALUES ('" . $days_ago . "', " . $countResult[0]['count'] . ", '" . $dayValue . "')";
        $insertResult = $otherdb1->query($insertQuery);

        // Delete query if count is not zero
        if ($countResult[0]['count'] != 0) {
            $deleteQuery = "DELETE FROM " . $otherdb1->tbl_unregistered_device_data . " WHERE created_time < '" . $days_ago . "'";
            $deleteResult = $otherdb1->query($deleteQuery);
        }

        return 1;
    }


    public function getRegisteredDeviceDataCopy($params)
    {
        $otherdb1 = $this->load->database('postgre_db', TRUE);
        $days_ago = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - 3, date("Y")));
        $date = date('Y-m-d') . ' 00:00:00';
        $dayValue = date('Y-m-d H:i:s');
        $otherdb1->select('count(*)');
        $otherdb1->from($otherdb1->tbl_registered_device_data);
        $otherdb1->where('created_time <', $days_ago);
        $results = $otherdb1->get();
        $results = $results->result_array();

        $insertRecords = array();
        $insertRecords['date'] = $days_ago;
        $insertRecords['count'] = $results[0]['count'];
        $insertRecords['created_at'] = $dayValue;
        $otherdb1->insert($otherdb1->tbl_cron_registered_records, $insertRecords);

        if ($results[0]['count'] != 0) {
            $otherdb1->where('created_time <', $days_ago);
            $otherdb1->delete($otherdb1->tbl_registered_device_data);
        }
        return 1;
    }

    public function getRegisteredDeviceData($params)
    {
        $otherdb1 = $this->load->database('postgre_db', TRUE);

        $days_ago = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - 3, date("Y")));
        $date = date('Y-m-d') . ' 00:00:00';
        $dayValue = date('Y-m-d H:i:s');

        // Count query
        $countQuery = "SELECT COUNT(*) AS count FROM " . $otherdb1->tbl_registered_device_data . " WHERE created_time < '" . $days_ago . "'";
        $countResult = $otherdb1->query($countQuery)->result_array();

        // Insert query
        $insertQuery = "INSERT INTO " . $otherdb1->tbl_cron_registered_records . " (date, count, created_at) VALUES ('" . $days_ago . "', " . $countResult[0]['count'] . ", '" . $dayValue . "')";
        $otherdb1->query($insertQuery);

        // Delete query if count is not zero
        if ($countResult[0]['count'] != 0) {
            $deleteQuery = "DELETE FROM " . $otherdb1->tbl_registered_device_data . " WHERE created_time < '" . $days_ago . "'";
            $otherdb1->query($deleteQuery);
        }

        return 1;
    }

    public function getStateInfo($stateId)
    {
        // $this->db->select('*');
        $this->db->select('id, s_name, s_key');
        $this->db->where('id = ', $stateId);
        $this->db->from($this->db->table_state);
        $result = $this->db->get();
        $result = $result->row_array();
        return $result;
    }

    //public function allStateList()
    // {
    //     $this->db->select('id, s_name');
    //     $this->db->from($this->db->table_state);
    //     $result = $this->db->get();
    //     $result = $result->result_array();
    //     return $result;
    // }

    public function getRtoInfoByStateId($stateNo = 0)
    {
        $this->db->select('rto_no, rto_place, rto_pwd, rto_number, state_name, state_id');
        if ((string)$stateNo != '0') {
            $this->db->where('state_id ', $stateNo);
        }
        $this->db->from($this->db->table_rto);
        $this->db->order_by("rto_place", "asc");
        $result = $this->db->get();
        $result = $result->result_array();
        return $result;
    }

    public function getStateInfoByCountryId($countryNo = 0)
    {
        $this->db->select('id, s_key, s_name, country_id');
        if ((string)$countryNo != '0') {
            $this->db->where('country_id ', $countryNo);
        }
        $this->db->from($this->db->table_state);
        $this->db->where('launch_state', 1);
        $this->db->order_by("s_name", "asc");
        $result = $this->db->get();
        $result = $result->result_array();
        return $result;
    }


    public function getLaunchStateInfoByCountryId($countryNo = 0)
    {
        $this->db->select('id, s_key, s_name, country_id');
        if ((string)$countryNo != '0') {
            $this->db->where('country_id ', $countryNo);
            $this->db->where('launch_state ', 1);
        }
        $this->db->from($this->db->table_state);
        $this->db->order_by("s_name", "asc");
        $result = $this->db->get();
        $result = $result->result_array();
        return $result;
    }

    public function listofvehicleExcel($search, $dealer, $txtfromdate, $txttodate)
    {
        $this->db->select('veh.*,ser.s_serial_number,ser.s_dealer_id,ser.s_imei');
        $this->db->where('veh.veh_create_date >=', $txtfromdate);
        $this->db->where('veh.veh_create_date <=', $txttodate);
        // echo "<pre>";print_r($search);exit();
        if (strlen($search) > 0) {
            // echo "<pre>";print_r($search);exit();
            $this->db->group_start();
            $this->db->like('veh.veh_rc_no', $search, 'both');
            $this->db->or_like('veh.veh_chassis_no', $search, 'both');
            $this->db->or_like('ser.s_serial_number', $search, 'both');
            $this->db->or_like('ser.s_imei', $search, 'both');
            $this->db->or_like('ser.s_iccid', $search, 'both');
            $this->db->or_like('veh.veh_invoice_no', $search, 'both');
            $this->db->or_like('veh.veh_owner_name', $search, 'both');
            $this->db->or_like('veh.veh_owner_phone', $search, 'both');
            $this->db->group_end();
        }

        if ($dealer != 0) {
            $this->db->where('ser.s_dealer_id', $dealer);
        }



        $this->db->from($this->db->table_vehicle . ' as veh');
        $this->db->join($this->db->table_serial_no . ' as ser', 'ser.s_serial_id = veh.veh_serial_no', 'left');

        // $this->db->limit($limit, $offset);
        $this->db->order_by("veh.veh_id", "desc");
        $result = $this->db->get();
        // $result = $result->result_array();
        $result = $result->result_array();
        // echo "<pre>";print_r($this->db->last_query());exit();
        // echo "<pre>";print_r($result);exit();
        return $result;
        // $this->db->select('veh.*');
        // $this->db->from($this->db->table_vehicle . ' as veh');
        // $this->db->limit(10);
        // $query = $this->db->get();
        // return $query->result_array();
    }


    public function totalExpiresForAdmin($user_id,$days ="")
    {
        $this->db->select('count(*)');
        $this->db->from($this->db->table_vehicle . ' as veh');
        $this->db->join($this->db->table_serial_no . ' as ser', 'ser.s_serial_id = veh.veh_serial_no', 'left');
        // $this->db->select('veh.*,ser.s_serial_number,ser.s_distributor_id ,dealuser.user_name as dealer_name ,disuser.user_name as distributor_name');
        $this->db->join($this->db->table_users . ' as disuser', 'ser.s_distributor_id = disuser.user_id', 'left');
        $this->db->join($this->db->table_users . ' as dealuser', 'ser.s_dealer_id = dealuser.user_id', 'left');
        $this->db->where('ser.s_used ', 1);
        // $currentDate  = date('Y-m-d H:i:s');
        $currentDate  = date('Y-m-d H:i:s');
        // if($days !==0){
        // print_r($days);exit;
        if($days !==0){
            if($days=="7"){
                $startDate = date('Y-m-d H:i:s', strtotime('-7 days'));

            }else if($days=="15"){
                $startDate = date('Y-m-d H:i:s', strtotime('-15 days'));

            }else if($days=="30"){
                $startDate = date('Y-m-d H:i:s', strtotime('-30 days'));

            }else if($days=="45"){
                $startDate = date('Y-m-d H:i:s', strtotime('-45 days'));
            }else{
                // $startDate = date('Y-m-d H:i:s', strtotime('-365 days'));
            }
            $this->db->where('veh.validity_to >=', $startDate);
            $this->db->where('veh.validity_to <=', $currentDate);


        }else if($days =="0"){
            $this->db->where('veh.validity_to >=', $currentDate);

        }else{
            $this->db->where('veh.validity_to <=', $currentDate);

        }
        // $startDate = date('Y-m-d H:i:s', strtotime('-20 days'));

        // $this->db->where('veh.validity_to <=', $currentDate);
        // $this->db->where('veh.validity_to >=', $currentDate);
        // echo "<pre>";print_r( "hai".($dealer_id));exit;

        if (strlen($_GET['search']) > 0) {
            $this->db->group_start();
            $this->db->like('ser.s_serial_number', $_GET['search'], 'both');
            $this->db->or_like('veh.veh_owner_phone', $_GET['search'], 'both');
            $this->db->group_end();
        }
        if ($_GET['dealer_id'] == "" && $_GET['distributor_id'] != "") {
            // $this->db->select('veh.*,ser.s_serial_number,ser.s_distributor_id ,disuser.user_name as distributor_name');
            // $this->db->join($this->db->table_users . ' as disuser', 'ser.s_distributor_id = disuser.user_id', 'left');
            $this->db->where('ser.s_distributor_id ', $_GET['distributor_id']);
        }
        if (isset($_GET['s_country_id']) && strlen($_GET['s_country_id']) > 0) {
            $this->db->where('ser.s_country_id', $_GET['s_country_id'], 'both');
        }
        if (isset($_GET['s_state_id']) && strlen($_GET['s_state_id']) > 0) {
            $this->db->where('ser.s_state_id', $_GET['s_state_id'], 'both');
        }
        if ($_GET['dealer_id'] != "" && $_GET['distributor_id'] != "") {
            // $this->db->select('veh.*,ser.s_serial_number,ser.s_distributor_id ,dealuser.user_name as dealer_name ,disuser.user_name as distributor_name');
            // $this->db->join($this->db->table_users . ' as disuser', 'ser.s_distributor_id = disuser.user_id', 'left');
            // $this->db->join($this->db->table_users . ' as dealuser', 'ser.s_dealer_id = dealuser.user_id', 'left');
            $this->db->where('ser.s_distributor_id ', $_GET['distributor_id']);
            $this->db->where('ser.s_dealer_id ', $_GET['dealer_id']);
        }
        // $this->db->select('veh.*,ser.s_serial_number,ser.s_distributor_id ');

        $result = $this->db->count_all_results();
        // echo "<pre>";print_r($result);exit();
        // echo "<pre>";print_r($this->db->last_query());exit();
        return $result;
    }
    //expiring veh for admin end -

    //expiring veh for dealer startc +
    public function listofExpires($limit, $offset, $search = '', $user_id)
    {
        $user_type = $this->session->userdata('user_type');
        $user_company_id = $this->session->userdata('user_company_id');
        $this->db->select('veh.*,ser.s_serial_number,dealuser.user_name as dealer_name, disUser.user_name as distributor_name');
        $this->db->where('ser.s_used ', 1);
        $this->db->from($this->db->table_vehicle . ' as veh');
        $this->db->join($this->db->table_serial_no . ' as ser', 'ser.s_serial_id = veh.veh_serial_no', 'left');
        $this->db->join($this->db->table_users . ' as dealuser', 'ser.s_dealer_id = dealuser.user_id', 'left');
        $this->db->join($this->db->table_users . ' as disUser', 'ser.s_distributor_id = disUser.user_id', 'left');
        $this->db->where('ser.s_dealer_id ', $user_id);
        $currentDate  = date('Y-m-d H:i:s');
        $this->db->where('veh.validity_to >=', $currentDate);
        if ((string)$user_type != '0') {
            $this->db->where('ser.s_company_id', $user_company_id);
        }

        if (strlen($search) > 0) {
            $this->db->group_start();
            $this->db->like('ser.s_serial_number', $search, 'both');
            $this->db->or_like('veh.veh_owner_phone', $search, 'both');
            $this->db->group_end();
        }
        if (isset($_GET['s_country_id']) && strlen($_GET['s_country_id']) > 0) {
            $this->db->where('ser.s_country_id', $_GET['s_country_id'], 'both');
        }
        if (isset($_GET['s_state_id']) && strlen($_GET['s_state_id']) > 0) {
            $this->db->where('ser.s_state_id', $_GET['s_state_id'], 'both');
        }
        $this->db->limit($limit, $offset);
        $this->db->order_by('veh.validity_to', "asc");
        $result = $this->db->get();
        $result = $result->result_array();
        // echo "<pre>";print_r($limit);exit;
        // echo "<pre>";print_r($this->db->last_query());exit();
        return $result;
    }

    public function totalExpires($user_id)
    {
        $this->db->select('count(*)');
        $this->db->from($this->db->table_vehicle . ' as veh');
        $this->db->join($this->db->table_serial_no . ' as ser', 'ser.s_serial_id = veh.veh_serial_no', 'left');
        $this->db->join($this->db->table_users . ' as dealuser', 'ser.s_dealer_id = dealuser.user_id', 'left');
        $this->db->where('ser.s_used ', 1);
        $this->db->where('ser.s_dealer_id ', $user_id);
        $currentDate  = date('Y-m-d H:i:s');
        $this->db->where('veh.validity_to >=', $currentDate);


        if (strlen($_GET['search']) > 0) {
            $this->db->group_start();
            $this->db->like('ser.s_serial_number', $_GET['search'], 'both');
            $this->db->or_like('veh.veh_owner_phone', $_GET['search'], 'both');
            $this->db->group_end();
        }
        $result = $this->db->count_all_results();
        // echo "<pre>";print_r($result);exit();
        // echo "<pre>";print_r($this->db->last_query());exit();
        return $result;
    }
    //expiring veh for dealer end ----------------------


    //expired veh for admin pannel start +
    public function listofvehicleExpiredForAdmin($limit, $offset, $search = '', $dealer_id, $distributor_id)
    {
        $user_type = $this->session->userdata('user_type');
        $user_company_id = $this->session->userdata('user_company_id');
        $this->db->select('veh.*,ser.s_serial_number,ser.s_distributor_id,dealuser.user_name as dealer_name ,disuser.user_name as distributor_name');
        $this->db->where('ser.s_used ', 1);
        $this->db->from($this->db->table_vehicle . ' as veh');
        $this->db->join($this->db->table_serial_no . ' as ser', 'ser.s_serial_id = veh.veh_serial_no', 'left');
        $this->db->join($this->db->table_users . ' as disuser', 'ser.s_distributor_id = disuser.user_id', 'left');
        $this->db->join($this->db->table_users . ' as dealuser', 'ser.s_dealer_id = dealuser.user_id', 'left');
        $currentDate  = date('Y-m-d H:i:s');
        $this->db->where('veh.validity_to <=', $currentDate);

        if ((string)$user_type != '0' && (string)$user_type != '4') {
            $this->db->where('ser.s_company_id', $user_company_id);
        }

        if (strlen($search) > 0) {
            $this->db->group_start();
            $this->db->like('ser.s_serial_number', $search, 'both');
            $this->db->or_like('veh.veh_owner_phone', $search, 'both');
            $this->db->group_end();
        }
        if ($dealer_id == "" && $distributor_id != "") {
            $this->db->where('ser.s_distributor_id ', $distributor_id);
        }
        if ($dealer_id != "" && $distributor_id != "") {
            $this->db->where('ser.s_distributor_id ', $distributor_id);
            $this->db->where('ser.s_dealer_id ', $dealer_id);
        }
        if (isset($_GET['s_country_id']) && strlen($_GET['s_country_id']) > 0) {
            $this->db->where('ser.s_country_id', $_GET['s_country_id'], 'both');
        }
        if (isset($_GET['s_state_id']) && strlen($_GET['s_state_id']) > 0) {
            $this->db->where('ser.s_state_id', $_GET['s_state_id'], 'both');
        }
        $date2 = new DateTime(date('Y-m-d H:i:s'));
        $this->db->limit($limit, $offset);
        $this->db->order_by('veh.validity_to', "desc");
        $result = $this->db->get();
        $result = $result->result_array();
        // echo "<pre>";print_r($limit);exit;
        // echo "<pre>";print_r($currentDate);exit();
        // echo "<pre>";print_r($this->db->last_query());exit();
        return $result;
    }

    public function totalExpiredForAdmin($user_id)
    {
        $this->db->select('count(*)');
        $this->db->from($this->db->table_vehicle . ' as veh');
        $this->db->join($this->db->table_serial_no . ' as ser', 'ser.s_serial_id = veh.veh_serial_no', 'left');
        // $this->db->select('veh.*,ser.s_serial_number,ser.s_distributor_id ,dealuser.user_name as dealer_name ,disuser.user_name as distributor_name');
        $this->db->join($this->db->table_users . ' as disuser', 'ser.s_distributor_id = disuser.user_id', 'left');
        $this->db->join($this->db->table_users . ' as dealuser', 'ser.s_dealer_id = dealuser.user_id', 'left');
        $this->db->where('ser.s_used ', 1);
        $currentDate  = date('Y-m-d H:i:s');
        $this->db->where('veh.validity_to <=', $currentDate);
        // echo "<pre>";print_r( "hai".($dealer_id));exit;

        if (strlen($_GET['search']) > 0) {
            $this->db->group_start();
            $this->db->like('ser.s_serial_number', $_GET['search'], 'both');
            $this->db->or_like('veh.veh_owner_phone', $_GET['search'], 'both');
            $this->db->group_end();
        }
        if ($_GET['dealer_id'] == "" && $_GET['distributor_id'] != "") {
            // $this->db->select('veh.*,ser.s_serial_number,ser.s_distributor_id ,disuser.user_name as distributor_name');
            // $this->db->join($this->db->table_users . ' as disuser', 'ser.s_distributor_id = disuser.user_id', 'left');
            $this->db->where('ser.s_distributor_id ', $_GET['distributor_id']);
        }
        if ($_GET['dealer_id'] != "" && $_GET['distributor_id'] != "") {
            // $this->db->select('veh.*,ser.s_serial_number,ser.s_distributor_id ,dealuser.user_name as dealer_name ,disuser.user_name as distributor_name');
            // $this->db->join($this->db->table_users . ' as disuser', 'ser.s_distributor_id = disuser.user_id', 'left');
            // $this->db->join($this->db->table_users . ' as dealuser', 'ser.s_dealer_id = dealuser.user_id', 'left');
            $this->db->where('ser.s_distributor_id ', $_GET['distributor_id']);
            $this->db->where('ser.s_dealer_id ', $_GET['dealer_id']);
        }
        if (isset($_GET['s_country_id']) && strlen($_GET['s_country_id']) > 0) {
            $this->db->where('ser.s_country_id', $_GET['s_country_id'], 'both');
        }
        if (isset($_GET['s_state_id']) && strlen($_GET['s_state_id']) > 0) {
            $this->db->where('ser.s_state_id', $_GET['s_state_id'], 'both');
        }
        $result = $this->db->count_all_results();
        // echo "<pre>";print_r($result);exit();
        // echo "<pre>";print_r($this->db->last_query());exit();
        return $result;
    }
    //expired veh for admin pannel end -


    //expired veh for dealer pannel start +
    public function listofExpiredVehicle($limit, $offset, $search = '', $user_id)
    {
        $user_type = $this->session->userdata('user_type');
        $user_company_id = $this->session->userdata('user_company_id');
        $this->db->select('veh.*,ser.s_serial_number,dealuser.user_name as dealer_name,disuser.user_name as distributor_name');
        $this->db->where('ser.s_used ', 1);
        $this->db->from($this->db->table_vehicle . ' as veh');
        $this->db->join($this->db->table_serial_no . ' as ser', 'ser.s_serial_id = veh.veh_serial_no', 'left');
        $this->db->join($this->db->table_users . ' as dealuser', 'ser.s_dealer_id = dealuser.user_id', 'left');
        $this->db->join($this->db->table_users . ' as disuser', 'ser.s_distributor_id = disuser.user_id', 'left');
        $this->db->where('ser.s_dealer_id ', $user_id);
        $currentDate  = date('Y-m-d H:i:s');
        $this->db->where('veh.validity_to <=', $currentDate);
        if ((string)$user_type != '0') {
            $this->db->where('ser.s_company_id', $user_company_id);
        }
        if (isset($_GET['s_country_id']) && strlen($_GET['s_country_id']) > 0) {
            $this->db->where('ser.s_country_id', $_GET['s_country_id'], 'both');
        }
        if (isset($_GET['s_state_id']) && strlen($_GET['s_state_id']) > 0) {
            $this->db->where('ser.s_state_id', $_GET['s_state_id'], 'both');
        }

        if (strlen($search) > 0) {
            $this->db->group_start();
            $this->db->like('ser.s_serial_number', $search, 'both');
            $this->db->or_like('veh.veh_owner_phone', $search, 'both');
            $this->db->group_end();
        }
        $this->db->limit($limit, $offset);
        $this->db->order_by('veh.validity_to', "desc");
        $result = $this->db->get();
        $result = $result->result_array();
        // echo "<pre>";print_r($limit);exit;
        // echo "<pre>";print_r($this->db->last_query());exit();
        return $result;
    }

    public function totalExpiredVehicle($user_id)
    {
        $this->db->select('count(*)');
        $this->db->from($this->db->table_vehicle . ' as veh');
        $this->db->join($this->db->table_serial_no . ' as ser', 'ser.s_serial_id = veh.veh_serial_no', 'left');
        $this->db->join($this->db->table_users . ' as dealuser', 'ser.s_dealer_id = dealuser.user_id', 'left');
        $this->db->where('ser.s_used ', 1);
        $this->db->where('ser.s_dealer_id ', $user_id);
        $currentDate  = date('Y-m-d H:i:s');
        $this->db->where('veh.validity_to <=', $currentDate);
        if (isset($_GET['s_country_id']) && strlen($_GET['s_country_id']) > 0) {
            $this->db->where('ser.s_country_id', $_GET['s_country_id'], 'both');
        }
        if (isset($_GET['s_state_id']) && strlen($_GET['s_state_id']) > 0) {
            $this->db->where('ser.s_state_id', $_GET['s_state_id'], 'both');
        }

        if (strlen($_GET['search']) > 0) {
            $this->db->group_start();
            $this->db->like('ser.s_serial_number', $_GET['search'], 'both');
            $this->db->or_like('veh.veh_owner_phone', $_GET['search'], 'both');
            $this->db->group_end();
        }
        $result = $this->db->count_all_results();
        // echo "<pre>";print_r($result);exit();
        // echo "<pre>";print_r($this->db->last_query());exit();
        return $result;
    }
    //expired veh for dealer pannel end------------------

    public function listofvehicleExpiresForAdmin($limit, $offset, $search = '', $dealer_id, $distributor_id, $days ='')
    {
        $user_type = $this->session->userdata('user_type');
        $user_company_id = $this->session->userdata('user_company_id');
        $this->db->select('veh.*,ser.s_serial_number,ser.s_distributor_id,dealuser.user_name as dealer_name ,disuser.user_name as distributor_name ');
        $this->db->where('ser.s_used ', 1);
        $this->db->from($this->db->table_vehicle . ' as veh');
        $this->db->join($this->db->table_serial_no . ' as ser', 'ser.s_serial_id = veh.veh_serial_no', 'left');
        $this->db->join($this->db->table_users . ' as disuser', 'ser.s_distributor_id = disuser.user_id', 'left');
        $this->db->join($this->db->table_users . ' as dealuser', 'ser.s_dealer_id = dealuser.user_id', 'left');
        $currentDate  = date('Y-m-d H:i:s');
        if($days !==0){
            if($days=="7"){
                $startDate = date('Y-m-d H:i:s', strtotime('-7 days'));

            }else if($days=="15"){
                $startDate = date('Y-m-d H:i:s', strtotime('-15 days'));

            }else if($days=="30"){
                $startDate = date('Y-m-d H:i:s', strtotime('-30 days'));

            }else if($days=="45"){
                $startDate = date('Y-m-d H:i:s', strtotime('-45 days'));
            }else{
                // $startDate = date('Y-m-d H:i:s', strtotime('-365 days'));
            }
            // print_r($days);exit;
            $this->db->where('veh.validity_to >=', $startDate);
            $this->db->where('veh.validity_to <=', $currentDate);

        }else if($days =="0"){
            $this->db->where('veh.validity_to >=', $currentDate);

        }else{
            $this->db->where('veh.validity_to <=', $currentDate);

        }
        // $startDate = date('Y-m-d H:i:s', strtotime('-20 days'));

        // $this->db->where('veh.validity_to >=', $currentDate);

        if ((string)$user_type != '0' && (string)$user_type != '4') {
            $this->db->where('ser.s_company_id', $user_company_id);
        }

        if (strlen($search) > 0) {
            $this->db->group_start();
            $this->db->like('ser.s_serial_number', $search, 'both');
            $this->db->or_like('veh.veh_owner_phone', $search, 'both');
            $this->db->group_end();
        }
        if ($dealer_id == "" && $distributor_id != "") {
            $this->db->where('ser.s_distributor_id ', $distributor_id);
        }
        if ($dealer_id != "" && $distributor_id != "") {
            $this->db->where('ser.s_distributor_id ', $distributor_id);
            $this->db->where('ser.s_dealer_id ', $dealer_id);
        }
        if (isset($_GET['s_country_id']) && strlen($_GET['s_country_id']) > 0) {
            $this->db->where('ser.s_country_id', $_GET['s_country_id'], 'both');
        }
        if (isset($_GET['s_state_id']) && strlen($_GET['s_state_id']) > 0) {
            $this->db->where('ser.s_state_id', $_GET['s_state_id'], 'both');
        }
        $date2 = new DateTime(date('Y-m-d H:i:s'));
        $this->db->limit($limit, $offset);
        if($days =="0"){
        $this->db->order_by('veh.validity_to', "asc");

        }else{
            // $this->db->order_by('expiry_in', "asc");
            $this->db->order_by('veh.validity_to', "asc");

        }
        // $this->db->order_by('veh.validity_to', "asc");
        $result = $this->db->get();
        $result = $result->result_array();

        // echo "<pre>";print_r($this->db->last_query());exit();
        // print_r($result);exit;
        $date2 = new DateTime(date('Y-m-d H:i:s'));
        $result1 = array();
        foreach ($result as $key => $value) {
            $date1 = new DateTime($value['validity_to']);
            $leftDays = 0;
            if ($date1 < $date2) {
                $ansDays = $date1->diff($date2)->days;
                $leftDays = $ansDays;
            } else {
                $leftDays = $date1->diff($date2)->days;
            }
            if($days >=$leftDays && $days !== 0){
                // echo "greater then 30  ".$key;
                array_push($result1,$value);
            }else if($days ==0){
                array_push($result1,$value);
            }

        }
        // exit;
        // echo "<pre>";print_r($days);exit;
        // echo "<pre>";print_r($currentDate);exit();
        // echo "<pre>";print_r($this->db->last_query());exit();
        return $result1;
    }

    public function fetch_dealers($params, $distributor_id)
    {
        $this->db->select('user_id,user_name');

        // $this->db->where('user_id',$dealer);
        $this->db->where('created_by ', $distributor_id);
        $this->db->where('user_type ', 1);
        $this->db->where('user_status ', 1);
        $this->db->from($this->db->table_users);
        $result = $this->db->get();
        $result = $result->result_array();
        // return $result;
        // $this->db->from($this->db->table_users);
        // $result = $this->db->get();
        // $result = $result->result_array();
        // echo $this->db->last_query();exit();
        // echo "<pre>";print_r($result);exit();
        return $result;
    }

    public function fetch_list_of_distributors($params, $needAdmin, $avoid = 0)
    {
        $this->db->select('user_id,user_name');
        $this->db->where('user_status', 1);

        // $this->db->where('user_id',$dealer);
        $this->db->where('user_type ', 2);
        $this->db->from($this->db->table_users);
        $result = $this->db->get();
        $result = $result->result_array();
        // return $result;
        // $this->db->from($this->db->table_users);
        // $result = $this->db->get();
        // $result = $result->result_array();
        // echo $this->db->last_query();exit();
        // echo "<pre>";print_r($result);exit();
        return $result;
    }

    public function alldealers($distributor_id = 0)
    {
        $this->db->select('user_id,user_name');
        if ((string)$distributor_id != '0') {
            $this->db->where('created_by ', $distributor_id);
            $this->db->where('user_type ', 1);
            $this->db->where('user_status ', 1);
        }
        $this->db->from($this->db->table_users);
        $result = $this->db->get();
        $result = $result->result_array();
        // echo $this->db->last_query();exit();
        return $result;
    }

    public function addMail($mail)
    {
        // echo "<pre>";print_r("pdf_qr_code");exit;
        $currentDate  = date('Y-m-d H:i:s');
        $insertRecords = array();
        $insertRecords['email_address'] = $mail;
        $insertRecords['created_at'] = $currentDate;
        $this->db->insert($this->db->table_service_mail, $insertRecords);
        return true;
    }

    public function delete_service_mail($mail_id)
    {
        $this->db->where('id', $mail_id);
        $this->db->delete($this->db->table_service_mail);
        return true;
    }

    public function getNoOfServiceMailList()
    {
        $this->db->select('count(*)');
        $this->db->from($this->db->table_service_mail);
        $result = $this->db->count_all_results();
        return $result;
    }

    public function getserviceMailList($limit, $offset)
    {
        $this->db->select('*');
        $this->db->from($this->db->table_service_mail);
        $this->db->limit($limit, $offset);
        $result = $this->db->get();
        $result = $result->result_array();
        // echo "<pre>";print_r($this->db->last_query());exit;

        return $result;
    }

    public function check_imei_data($imie, $user_id, $user_type)
    {
        // echo "<pre>";print_r($imie);
        // echo "<pre>";print_r($user_id);
        // echo "<pre>";print_r($user_type);exit;
        $this->db->select('count(*)');
        $this->db->from($this->db->table_serial_no);
        if ($user_type == 2) {
            $this->db->where('s_distributor_id', $user_id);
        }
        if ($user_type == 1) {
            $this->db->where('s_dealer_id', $user_id);
        }
        $this->db->where('s_imei', $imie);
        $result = $this->db->count_all_results();
        // echo "<pre>";print_r($this->db->last_query());exit;
        return $result;
    }

    public function get_device_logs($serialId)
    {
        $this->db->select('dev_logs.*,ser.s_serial_number,usr.user_name, user.user_name as dealer_name, disuser.user_name as distributor_name, cus.c_customer_name as customer_name');
        $this->db->where('dev_logs.serial_id', $serialId);
        $this->db->from($this->db->table_device_logs . ' as dev_logs');
        $this->db->join($this->db->table_serial_no . ' as ser', 'ser.s_serial_id = dev_logs.serial_id', 'left');
        $this->db->join($this->db->table_users . ' as usr', 'usr.user_id = dev_logs.changed_by', 'left');
        $this->db->join($this->db->table_users . ' as user', 'user.user_id = dev_logs.dealer_id', 'left');
        $this->db->join($this->db->table_users . ' as disuser', 'disuser.user_id = dev_logs.distributor_id', 'left');
        $this->db->join($this->db->table_customers . ' as cus', 'cus.c_customer_id = dev_logs.customer_id', 'left');
        $this->db->join($this->db->table_state . ' as state', 'state.id = ser.s_state_id', 'full');
        $result = $this->db->get();
        $result = $result->result();
        // echo $this->db->last_query();exit();
        return $result;
    }

    public function check_rto($rto_id)
    {

        $this->db->select('count(*)');
        $this->db->where('veh_rto_no', $rto_id);
        $this->db->from($this->db->table_vehicle);
        $result = $this->db->count_all_results();
        // echo $this->db->last_query();exit();	
        return $result;
    }

    public function delete_rto($rto_id)
    {
        $this->db->where('rto_no', $rto_id);
        $this->db->delete($this->db->table_rto);
        return true;
    }

    public function check_make($make_id)
    {

        $this->db->select('count(*)');
        $this->db->where('veh_make_no', $make_id);
        $this->db->from($this->db->table_vehicle);
        $result = $this->db->count_all_results();
        // echo $this->db->last_query();exit();	
        return $result;
    }

    public function delete_make($make_id)
    {
        $this->db->where('v_make_id', $make_id);
        $this->db->delete($this->db->table_make);
        return true;
    }

    public function check_model($model_id)
    {
        $this->db->select('count(*)');
        $this->db->where('veh_model_no', $model_id);
        $this->db->from($this->db->table_vehicle);
        $result = $this->db->count_all_results();
        // echo $this->db->last_query();exit();	
        return $result;
    }

    public function delete_model($model_id)
    {
        $this->db->where('ve_model_id', $model_id);
        $this->db->delete($this->db->table_model);
        return true;
    }

    public function getRegisteredData($current_time, $imei)
    {

        // $DB2 = $this->load->database('postgre_db', TRUE);
        // $DB2 = $this->load->database('PSDN_WB', TRUE);
        $mainDB = $this->load->database('default', TRUE);
        $result = $mainDB
            ->select('s_imei, s_state_id, s_country_id, state.s_key') // Add columns from the state table as needed
            ->from($mainDB->table_serial_no)
            ->where('s_imei', $imei)
            ->join($mainDB->table_state, 'state.id = ' . $mainDB->table_serial_no . '.s_state_id', 'left') // Adjust the join condition accordingly
            ->get()
            ->row_array();
        $databaseName = 'PSDN_' . $result['s_key'];
        $trimmedStr = trim($databaseName);
        $DB2 = $this->load->database($trimmedStr, TRUE);
        // $DB2->select('imei, data, created_time');
        // $DB2->where('created_time >', $current_time);
        // $DB2->where('imei', $imei);
        // $DB2->order_by('created_time', 'DESC');
        // $query = $DB2->get('tbl_registered_device_data');
        // $results = $query->result_array();

        $query = "select imei, data, created_time from public.tbl_registered_device_data where imei = '" . $imei . "' AND created_time > '" . $current_time . "' ORDER BY created_time DESC";
        // echo "<pre>";print_r($query);exit;
        $data  = $DB2->query($query)->result_array();
        // echo "<pre>";print_r($data);exit;
        // echo $DB2->last_query();exit();
        return $data;
    }

    public function getRegisteredFirstData($imei)
    {
        $mainDB = $this->load->database('default', TRUE);
        // echo $imei;exit;
        $result = $mainDB
            ->select('s_imei, s_state_id, s_country_id, state.s_key') // Add columns from the state table as needed
            ->from($mainDB->table_serial_no)
            ->where('s_imei', $imei)
            ->join($mainDB->table_state, 'state.id = ' . $mainDB->table_serial_no . '.s_state_id', 'left') // Adjust the join condition accordingly
            ->get()
            ->row_array();
            // echo $this->db->last_query();exit;
        $databaseName = 'PSDN_' . $result['s_key'];

        $trimmedStr = trim($databaseName);
        $DB2 = $this->load->database($trimmedStr, TRUE);
        // echo $trimmedStr;exit;
        // print_r($DB2);
        $table_name = "tbl_registered_device_data_".date('Y_m');
        $query = "select imei, data, created_time from public.".$table_name." where imei = '" . $imei . "' limit 1";
        // echo "<pre>";print_r($query);exit;
        
        $data  = $DB2->query($query)->result_array();
        return $data;
    }

    public function getValidImei($imei)
    {
        // echo "<pre>";print_r($imei);exit;
        $this->db->select('count(*)');
        $this->db->where('s_imei', $imei);
        $this->db->from($this->db->table_serial_no);
        $result = $this->db->count_all_results();
        return $result;
    }
    public function getValidUser($user_type, $imei, $user_id)
    {
        // echo "<pre>";print_r($imei);exit;
        $this->db->select('count(*)');
        $this->db->where('s_imei', $imei);
        if ($user_type == 1) {
            $this->db->where('s_dealer_id', $user_id);
        }
        if ($user_type == 2) {
            $this->db->where('s_distributor_id', $user_id);
        }
        $this->db->from($this->db->table_serial_no);
        $result = $this->db->count_all_results();
        // echo $this->db->last_query();exit();
        return $result;
    }

    public function awsImageUpload($imagePath, $imageName, $path)
    {
        try {
            // echo "<pre>";print_r("imagePath =>".$imagePath." imageName =>".$imageName." path =>".$path);exit;
            // "imagePath =>public/temp_upload/1697088606.png imageName =>1697088606.png path =>public/upload/vehicle"
            // s3 Bucket connect
            $credentials = [
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
                    'MaxKeys' => 1,
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
                    'Key'         => $folderName . "/" . $imageName,
                    'SourceFile'  => $imagePath,                   // public/temp_upload/1686202313.jpg
                    'ContentType' => $contentType,                   // jpg or png (File Type)
                    'StorageClass' => 'REDUCED_REDUNDANCY'
                ]);
                // echo "<pre>";print_r($result['ObjectURL']);exit;

                $url = $result['ObjectURL'];
                return $url;
            }
            $filePath = $folderName . "/" . $imageName;
            $info = $s3Client->doesObjectExist($bucket, $filePath);
            if (!$info) {
                // echo "<pre>";print_r('File does not exists');exit;
                // Upload the image to the folder  
                try {
                    $result1 = $s3Client->putObject([
                        'Bucket'      => $bucket,
                        'Key'         => $folderName . "/" . $imageName,   // sample/45614565.jpg
                        'SourceFile'  => $imagePath,                   // public/temp_upload/1686202313.jpg
                        'ContentType' => $contentType,                   // jpg or png (File Type)
                        'StorageClass' => 'REDUCED_REDUNDANCY'
                    ]);

                    $urlOutput = $result1['ObjectURL'];
                    return $urlOutput;
                } catch (S3Exception $e) {
                    die('Error:' . $e->getMessage());
                } catch (Exception $e) {
                    die('Error:' . $e->getMessage());
                }
            }
        } catch (S3Exception $e) {
            echo "An error occurred: " . $e->getMessage();
            exit;
        }
    }



    public function getGPSLiveTrackingData()
    {
        // Load the tracking database
        $trackingDB = $this->load->database('tracking', TRUE);

        // Select vtrackingId and imei from gps_livetracking_data
        $trackingData = $trackingDB->select('vtrackingId, imei')
            ->get('gps_livetracking_data')
            ->result();

        foreach ($trackingData as $key => $value) {
            // Load the main database
            $mainDB = $this->load->database('default', TRUE);

            // Select s_imei, s_state_id, s_country_id from the main database
            $result = $mainDB->select('s_imei, s_state_id, s_country_id')
                ->where('s_imei', $value->imei)
                ->get($mainDB->table_serial_no)
                ->row_array();

            if ($result) {
                // Update the tracking database with the stateId
                $trackingDB->set('stateId', $result['s_state_id'])
                    ->where('vtrackingId', $value->vtrackingId)
                    ->update('gps_livetracking_data');
            }
        }

        return 1;
    }
}