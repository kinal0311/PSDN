<?php

defined('BASEPATH') OR exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, OPTIONS");


class StockInwardScan extends CI_Controller
{
    // Load Constructur
    public function __construct()
    {
        parent::__construct();
        $this->load->model('adminmodel');
        $this->load->model('commonmodel');
        $this->load->model('stockinwardscanmodel');
        $this->load->library("pagination");
        $this->load->library('session');
    }

    // Initialize Function
    public function index()
    {
        $user_type = $this->session->userdata('user_type');
       if (isset($user_type) && (string)$user_type !== '4') {
            redirect(base_url() . 'admin/dashboard', 'refresh');
            exit();
        }

        $this->load->view('stockinwardscan/login');
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

        if ($response['user_type'] !== '4') {
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
        $returnResponse['redirect'] = 'admin/dashboard';
        echo json_encode($returnResponse);
        exit();
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect(base_url() . 'admin/', 'refresh');
        exit();
    }

    public function scan()
    {
        //Permission
        $user_type = $this->session->userdata('user_type');
        if (!check_permission($user_type, 'menu_inventry_create')) {
            redirect(base_url(), 'refresh');
            exit();
        }

        $this->load->view('stockinwardscan/scan');
    }

    public function verifyscanner()
    {
        $params = $this->input->post();
        $returnResponse = array();
        $returnResponse['validation'] = array();
        $returnResponse['error'] = "";
        $returnResponse['success'] = "";
        // Validation
        $this->form_validation->set_rules('serial_number', 'Code already exist', 'required');

        // Validation verify
        if ($this->form_validation->run() == FALSE) {
            $returnResponse['validation'] = $this->form_validation->error_array();
            echo json_encode($returnResponse);
            exit();
        }

        $code_arr = explode(';', $this->input->post('serial_number'));

        if (count($code_arr) != 3 || strlen($code_arr[0]) != 15 || strlen($code_arr[1]) != 20 || strlen($code_arr[2]) != 17) {
            $returnResponse['error'] = "Please enter valid code.";
            echo json_encode($returnResponse);
            exit();
        }

        $code_arr[1] = str_split($code_arr[1],10);

        //Pass params to Model
        $response = $this->stockinwardscanmodel->checkCodeExists($code_arr);
        if (!empty($response)) {
            $returnResponse['error'] = "Serial number already exist. Please try with new code.";
            echo json_encode($returnResponse);
            exit();
        } else {
            $inserted = $this->stockinwardscanmodel->saveSerialNumber($code_arr);
            if ($inserted) {
                $returnResponse['success'] = true;
                $returnResponse['redirect'] = 'admin/dashboard';
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

