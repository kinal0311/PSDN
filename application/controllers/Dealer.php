<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dealer extends CI_Controller {
	
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
		$user_type=$this->session->userdata('user_type');
		if(isset($user_type) && (string)$user_type==='0')
		{
			 redirect(base_url().'admin/dashboard', 'refresh');
			 exit();
		}
		$this->load->view('disdealer/DealerLogin');
	}
	public function forgot_password()
	{		
		$this->load->view('dealer/forgot_password');
	}
	
}
