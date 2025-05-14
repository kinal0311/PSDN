<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	
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
		$this->load->view('home');
	}
	
	// Initialize Function
	public function contact_us()
	{				
		$data['userinfo']=$this->commonmodel->getUserInfobyid(1);
		//print_r($data['userinfo']);exit();
		$this->load->view('contact_us',$data);
	}
	
	
}
