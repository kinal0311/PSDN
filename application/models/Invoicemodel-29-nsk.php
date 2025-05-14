<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoicemodel extends CI_Model {

   public function __construct()
   {
       parent::__construct();             
   }

   public function getcompanyalldetails_fromusertype($param){
			
			if($param=="0"){			
				$companydatas = $this->db->query("SELECT * from ci_company ")->result();
			}
			return $companydatas;
			
		}	
		
		public function fetch_txtdropdowndisdealdatas($txtpicompany,$user_type){
			
			if($txtpicompany=="0"){ $txtpicompany = ""; }
			
			$subquery = ' where 1=1 ';
			
			if($txtpicompany!=""){
				
				$subquery .= " AND user_company_id = '".$txtpicompany."' ";
				
			}	
			
			
			
			if($user_type == "0" or $user_type == "4"){ 
			
				$subquery .= " AND user_type = '2' ";
			
			}
			else if($user_type == "2"){
				$subquery .= " AND user_type = '1' ";
			}	
			
			if($user_type=="1"){
				
				$statedatas = $this->db->query("select * from ci_customers where c_user_status = 1")->result();
			}
			else{
				
				$statedatas = $this->db->query("select * from ci_users ".$subquery." ")->result();
			}	
			
			return $statedatas;
			
			
		}
		
		public function fetch_productdatas($param){
		
			$subquery   = ' where 1=1 ';
			
			if($param!=""){ $subquery .= ' AND t1.p_product_id = '.$param; }
			
			$query = $this->db->query(" select t1.* from ci_products t1 ".$subquery);
			$row = $query->row();
			
			return $row;
			
		
		}	
		
		public function fetch_companydropdowndatas($param){
			
			
			
			$query = $this->db->query(" select t1.* from ci_users t1 where t1.user_id = '".$param."'  ");
			$row = $query->row();
			
			return $row;
			
			
			
		}	
		
		public function getall_entries_products($param){
			
			$subquery = ' where 1=1 ';
			
			if($param!=""){
				$subquery .= " AND p_company_id = '".$param."' ";
			}

			$statedatas = $this->db->query("select * from ci_products ".$subquery." ")->result();
			
			return $statedatas;
		
		}

		public function get_competitors_ids($param){
		
			$param_arr = array();
			$final_str = "";
			
			if($param!=""){
				
				$param_arr = explode("txtaddqty_",$param);
				
				if($param_arr[1]!=""){
					$final_str = $param_arr[1];
				}	
				
				
			}

			return 	$final_str;

		}
		
		public function getall_entries_proforma_invoice($user_company_id,$user_type,$user_id){
			
			$subquery = " where 1=1 ";
			
			if($user_type=="4"){
				$subquery .= " AND t1.company_id = '".$user_company_id."' ";
			}
			else if($user_type=="1" or $user_type=="2"){
				$subquery .= " AND user_id = '".$user_id."' ";
			}	
			
			$statedatas = $this->db->query("select t1.*, t2.*, datediff(now(),t1.duedate) as statuscheck from ci_proformainvoice t1 left join ci_company t2 on t1.company_id = t2.c_company_id ".$subquery." ")->result();
			
			return $statedatas;
			
		}

		public function fetch_proformainvoicedatas($param){
			
			$subquery = " where 1=1 ";
			
			if($param!=""){ $subquery .= " AND t1.id = '".$param."' "; }
			
			$query = $this->db->query(" select t1.*,t2.* from ci_proformainvoice t1 left join ci_company t2 on t2.c_company_id = t1.company_id ".$subquery);
			$row = $query->row();
			
			return $row;
			
		}
		
		public function fetch_proformainvoice_products($param){
		
			$subquery = " where 1=1 ";
			
			if($param!=""){ $subquery .= " AND t1.ref_invoiceid = '".$param."' "; }
			
			$statedatas = $this->db->query("select t1.*,t2.* from ci_proformainvoice_products t1 left join ci_products t2 on t2.p_product_id = t1.product_id ".$subquery." ")->result();
			
			return $statedatas;
		
		}

		public function convert_number($number) {

			if (($number < 0) || ($number > 999999999)) {
			  throw new Exception("Number is out of range");
			}

			$Gn = floor($number / 1000000);

			/* Millions (giga) */

			$number -= $Gn * 1000000;
			$kn = floor($number / 1000);

			/* Thousands (kilo) */

			$number -= $kn * 1000;
			$Hn = floor($number / 100);

			/* Hundreds (hecto) */

			$number -= $Hn * 100;
			$Dn = floor($number / 10);

			/* Tens (deca) */

			$n = $number % 10;

			/* Ones */

			$res = "";

			if ($Gn) {

			  $res .= convert_number($Gn) .  "Million";
			}

			if ($kn) {

			  $res .= (empty($res) ? "" : " ") .$this->convert_number($kn) . " Thousand";

			}

			if ($Hn) {

			  $res .= (empty($res) ? "" : " ") .$this->convert_number($Hn) . " Hundred";

			}

			$ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");

			$tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", "Seventy", "Eigthy", "Ninety");

			if ($Dn || $n) {

			  if (!empty($res)) {

				$res .= " and ";

			  }

			  if ($Dn < 2) {

				$res .= $ones[$Dn * 10 + $n];

			  } else {

				$res .= $tens[$Dn];

				if ($n) {

				  $res .= "-" . $ones[$n];

				}

			  }

			}

			if (empty($res)) {

			  $res = "zero";

			}

			return $res;

		}     
}