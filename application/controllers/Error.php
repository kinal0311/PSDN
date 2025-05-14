<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error extends CI_Controller {
	
	// Load Constructur
	public function __construct() {
        parent::__construct();
		$this->load->model('adminmodel');
		$this->load->model('commonmodel');
		$this->load->library("pagination");
		
    }
	
	public function index()
	{
		echo "";
	}
}
