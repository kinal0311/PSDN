<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, OPTIONS");

class Customer extends CI_Controller {
	
	// Load Constructur
	public function __construct() {
        parent::__construct();
		$this->load->model('adminmodel');
		$this->load->model('commonmodel');
		$this->load->library("pagination");
		
    }
	
	// Initialize Function
	public function index()
	{
        $this->load->view('masters/customer_registration');
	}

	
    public function create_customer_front()
    {
        $params = $this->input->post();
        $returnResponse = array();

        $returnResponse['validation'] = array();

        $returnResponse['error'] = "";

        $returnResponse['success'] = "";

        // Validation

        $this->form_validation->set_rules('name', 'Name', 'trim|required');

        $this->form_validation->set_rules('phone', 'Phone', 'trim|required');

        $this->form_validation->set_rules('email', 'Email', 'trim|required');

        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        $this->form_validation->set_rules('address', 'Address', 'trim|required');

        
        $this->form_validation->set_rules(

            'phone', 'Phone',

            array(

                'required',

                array(

                    'phone_no_already_exits',

                    function ($str) {

                        return $this->commonmodel->verify_exits_customer_phone_number($str);

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

                            return $this->commonmodel->verify_exits_customer_email($str);

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
        $response = $this->adminmodel->create_new_customer($params);

        $returnResponse['success'] = true;

        $returnResponse['redirect'] = 'home.html';

        $returnResponse['message'] = 'Registration completed successfully.We will contact you soon!';

        echo json_encode($returnResponse);
        exit();
    }
}
