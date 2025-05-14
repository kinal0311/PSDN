<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth
{
     private $iv = 'PSDN'; #Same as in JAVA
     private $key = '0123456789'; #Same as in JAVA

     function encrypt($str) {

                  //$key = $this->hex2bin($key);    
      $iv = $this->iv;

      $td = mcrypt_module_open('rijndael-128', '', 'cbc', $iv);

      mcrypt_generic_init($td, $this->key, $iv);
      $encrypted = mcrypt_generic($td, $str);

      mcrypt_generic_deinit($td);
      mcrypt_module_close($td);

      return bin2hex($encrypted);
    }

    function decrypt($code) {
                  //$key = $this->hex2bin($key);
      $code = $this->hex2bin($code);
      $iv = $this->iv;

      $td = mcrypt_module_open('rijndael-128', '', 'cbc', $iv);

      mcrypt_generic_init($td, $this->key, $iv);
      $decrypted = mdecrypt_generic($td, $code);

      mcrypt_generic_deinit($td);
      mcrypt_module_close($td);

      return utf8_encode(trim($decrypted));
    }

    protected function hex2bin($hexdata) {
      $bindata = '';

      for ($i = 0; $i < strlen($hexdata); $i += 2) {
        $bindata .= chr(hexdec(substr($hexdata, $i, 2)));
      }

      return $bindata;
    }
  }

  class Mobileapi102 extends CI_Controller 
  {

	// Load Constructur
   public function __construct() {
    parent::__construct();
    $this->countryCode='+91';

    if(!isset($_REQUEST['device_id']))
    {
      $response=array();
      $response['status']=1000;
      $response['message']="Invalid Access,Destory Build.1";
      echo json_encode($response);exit();
    }
    define('DEVICE_ID',(string)trim($_REQUEST['device_id']));
    define('ENCRYPTKEY','PSDNMOBILEAPI');
    define('BUILD_ENCRYPTION','PSDN');
    $this->load->model('Mobileapi102model');
    //$this->load->model('Vehiclehistorymodel');
    //echo "hello"; exit;
    $this->mobiledata = file_get_contents("php://input");

    if(isset($_GET['platform']) && (string)$_GET['platform']==='A')
    {
      $this->mobiledata=base64_encode($this->mobiledata);
    }
    if(strlen($this->mobiledata)>0)
    {
     $this->mobiledata=$this->decrypt($this->mobiledata);
     $this->mobiledata=json_decode($this->mobiledata,true);
   }
   $current_function=$this->router->fetch_method();
   if(!is_array($this->mobiledata) && $current_function !='image_upload')
   {
    $response=array();
    $response['status']=1000;
    $response['message']="Invalid Access,Destory Build.2";
    echo json_encode($response);exit();
  }
  $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  if (strpos($actual_link, 'authorization') !== false) {

  }else{
    $headers = apache_request_headers();
    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    if (strpos($actual_link, 'portal') !== false) {
    }else{
      if(!isset($headers['Authorization']))
      {
        $response=array();
        $response['status']=1000;
        $response['message']="Invalid Access,Destory Build.3";
        echo json_encode($response);exit();
      }
      $mcrypt = new Auth();
      $deviceID=$mcrypt->decrypt($headers['Authorization']);

      if (strpos($deviceID, DEVICE_ID) !== false) {

      }else{
        $response=array();
        $response['status']=1000;
        $response['message']="Invalid Access,Destory Build.4";
        echo json_encode($response);exit();
      }
    }

  }
}

public function encrypt_decrypt($string,$action,$encodeKey=ENCRYPTKEY) {
  if((string)$action==='encrypt')
  {
    return $this->encrypt($string);
  }else{
    return $this->decrypt($string);
  }
}       

public function encrypt($params)
{
  $jsonData=json_encode($params);
  return base64_encode($jsonData);exit();
    //   require 'Encryption.php';
    //   $mcrypt = new Encryption();
    //   $encodeKey=BUILD_ENCRYPTION.DEVICE_ID.ENCRYPTKEY;
    //   echo $mcrypt->encrypt(base64_encode(json_encode($this->mobiledata)),$encodeKey);
}
public function decrypt($params)
{
  return trim(base64_decode($params),'"');
  require 'Encryption.php';
    //   $mcrypt = new Encryption();
    //   $encodeKey=BUILD_ENCRYPTION.DEVICE_ID.ENCRYPTKEY;
    //   $decryptDate=$mcrypt->decrypt($this->mobiledata,$encodeKey);
    //   echo trim(base64_decode($decryptDate),'"');
}

public function error_report()
{
  echo 1;exit();
}
public function getcoreconfig()
{
  $params= $this->mobiledata;       
  $response=array();
  $response['appName']='PSDN';
  $response['appLogo']='https://randomuser.me/api/portraits/men/82.jpg';
  $response['loginLogo']='https://randomuser.me/api/portraits/men/82.jpg';
  $response['countryCode']="+91";
  $response['aboutUs']=base_url().'home/privacy';
  $response['terms']=base_url().'terms_and_condition.html';
  $response['androidKey']='KSDGKSKDDSKGKCVVFD';
  $response['noImageUrl']='https://randomuser.me/api/portraits/men/82.jpg';
  $response['errorReportCase']='error_report';
  $response['socketUrl']=SOCKET_URL;
  $res=array();
  $res['data']=$response;
  $res['status']=1;
  $res['message']='success';
  echo $this->encrypt($res);
}



public function user_logout()
{
 $params= $this->mobiledata;
 if(!isset($params['id']) || strlen($params['id'])===0)
 {
  $response=array();
  $response['status']=-1;
  $response['message']="Invalid Access,Please try again.";
  echo $this->encrypt($response);exit();
}
$params['c_phone']=$this->encrypt_decrypt($params['id'],'decrypt');
$result=$this->Mobileapi102model->verifyuser($params);
if(empty($result))
{
 $response=array();
 $response['status']=-1;
 $response['message']="Invalid Attempt.Please try again";
 echo $this->encrypt($response);exit();     
}
$params['user_logout']=1;
$params['c_phone']=$result['c_phone'];
$updateResult=$this->Mobileapi102model->updateInfo($params);
$response=array();
$response['status']=1;
$response['message']="User logged out successfully.";
echo $this->encrypt($response);exit();
}

public function update_new_password()
{
 $params= $this->mobiledata;
 if(!isset($params['userUnique']) || strlen($params['userUnique'])===0 || !isset($params['newPassword']) || strlen($params['newPassword'])===0 || !isset($params['retypePassword']) || strlen($params['retypePassword'])===0)
 {
  $response=array();
  $response['status']=-1;
  $response['message']="Invalid Access,Please try again.";
  echo $this->encrypt($response);exit();
}
$params['c_user_remember']=$params['userUnique'];
$result=$this->Mobileapi102model->verifyUsersData($params);
if(empty($result))
{
 $response=array();
 $response['status']=-1;
 $response['message']="Records not found,Please try again.";
 echo $this->encrypt($response);exit();     
}
$params['c_password']=md5($params['newPassword']);
$params['c_phone']=$result['c_phone'];
$updateResult=$this->Mobileapi102model->updateInfo($params);

$response=array();
$response['status']=1;
$response['message']="Password have been changes success,Please login again.";
echo $this->encrypt($response);exit();
}

public function otp_process()
{
 $params= $this->mobiledata;
 if(!isset($params['otpUnique']) || strlen($params['otpUnique'])===0 || !isset($params['otp']) || strlen($params['otp'])===0)
 {
  $response=array();
  $response['status']=-1;
  $response['message']="Invalid Access,Please try again.";
  echo $this->encrypt($response);exit();
}
$params['c_otp_reference']=base64_decode($this->encrypt_decrypt($params['otpUnique'],'decrypt'));
if((string)$params['c_otp_reference'] !=$params['otp'])
{
  $response=array();
  $response['status']=-1;
  $response['message']="OTP incorrect,Please try again.";
  echo $this->encrypt($response);exit(); 
}
$params['c_otp_reference']=$params['otpUnique'];
$result=$this->Mobileapi102model->verifyUsersData($params);

$userUnique=base64_encode(time()*200/rand());
$params['c_user_remember']=$userUnique;
$params['c_phone']=$result['c_phone'];
$updateResult=$this->Mobileapi102model->updateInfo($params);

$response=array();
$response['status']=1;
$response['userUnique']=$userUnique;

$response['message']="OTP has been verified success,Please change password.";
echo $this->encrypt($response);exit();
}

public function forgot_password()
{
 $params= $this->mobiledata;
 if(!isset($params['phoneNo']) || strlen($params['phoneNo'])===0)
 {
  $response=array();
  $response['status']=-1;
  $response['message']="Invalid Access,Please try again.";
  echo $this->encrypt($response);exit();
}
$params['c_phone']=$params['phoneNo'];
$result=$this->Mobileapi102model->verifyuser($params);
if(empty($result))
{
 $response=array();
 $response['status']=-1;
 $response['message']="Records not found,Please try again.";
 echo $this->encrypt($response);exit();     
}
$otpNumber=rand(1000,9999);
$otpUnique=$this->encrypt_decrypt(base64_encode($otpNumber),'encrypt');
$this->Mobileapi102model->send_sms($params['c_phone'],$otpNumber);
$params['c_otp_reference']=$otpUnique;
$updateResult=$this->Mobileapi102model->updateInfo($params);    	
$response=array();
$response['status']=1;
$response['otpUnique']=$otpUnique;
$response['otp_reference']=$otpNumber;
$response['message']="OTP has been sent,Kindly check it";
echo $this->encrypt($response);exit();
}

public function change_password()
{
 $params= $this->mobiledata;
 if(!isset($params['oldPassword']) || strlen($params['oldPassword'])===0 || !isset($params['newPassword']) || strlen($params['newPassword'])===0 || !isset($params['retypePassword']) || strlen($params['retypePassword'])===0)
 {
  $response=array();
  $response['status']=-1;
  $response['message']="Invalid Access,Please try again.";
  echo $this->encrypt($response);exit();
}

if((string)$params['newPassword']!=(string)$params['retypePassword'])
{
  $response=array();
  $response['status']=-1;
  $response['message']="Password mismatch,Please try again.";
  echo $this->encrypt($response);exit();	
}
$params['c_phone']=$this->encrypt_decrypt($params['id'],'decrypt');
$result=$this->Mobileapi102model->verifyuser($params);
if(empty($result))
{
 $response=array();
 $response['status']=-1;
 $response['message']="Invalid Attempt.Please try again";
 echo $this->encrypt($response);exit();     
}
if((string)$result['c_password']  !=md5($params['oldPassword']))
{
 $response=array();
 $response['status']=-1;
 $response['message']="Old password wrong,please try again";
 echo $this->encrypt($response);exit(); 
}
$params['c_password']=md5($params['new_password']);
$updateResult=$this->Mobileapi102model->updateInfo($params);
$response=array();
$response['status']=1; 
$response['message']='New Password have been changed successfully.';
echo $this->encrypt($response);exit();
}

public function update_profile()
{
 $params= $this->mobiledata;
 if(!isset($params['fname']) || strlen($params['fname'])===0 ||  !isset($params['id']) || strlen($params['id'])===0)
 {
  $response=array();
  $response['status']=-1;
  $response['message']="Invalid Access,Please try again.";
  echo $this->encrypt($response);exit();
}
$params['c_phone']=$this->encrypt_decrypt($params['id'],'decrypt');
$result=$this->Mobileapi102model->verifyuser($params);
if(empty($result))
{
 $response=array();
 $response['status']=-1;
 $response['message']="Invalid Attempt.Please try again";
 echo $this->encrypt($response);exit();     
}
$updateResult=$this->Mobileapi102model->updateInfo($params);
$result=$this->Mobileapi102model->verifyuser($params);
$response=array();
$response['status']=200; 
$response['message']='Your Profile has been updated successfully ! ';
$response['detail']=array(
  "userName"=>$result['c_customer_name'],
  "imageUrl"=>base_url().$result['c_photo'],
  "countryCode"=>$this->countryCode,
  "userEmail"=>$result['c_customer_name'],
  "phoneNum"=>$result['c_phone'],
  "id"=>$this->encrypt_decrypt($result['c_phone'],'encrypt'),
  "remember"=>''     
);               
echo $this->encrypt($response);exit();
}

public function image_upload()
{	
  $params= file_get_contents('php://input');
  $info=array();
  $info['params']=$params;
  $info['file']=$_FILES;
  $info['request']=$_REQUEST;

  log_message('error', json_encode($info));
  if(!isset($_REQUEST['device_id']) || strlen($_REQUEST['device_id'])===0 )
  {
    $response=array();
    $response['status']=-1;
    $response['message']="Invalid Access,Please try again.";
    echo $this->encrypt($response);exit();
  }
  $result=$this->Mobileapi102model->uploadCustomerPhotos();
  if($result['fail'])
  {
    $response=array();
    $response['status']=-1;
    $response['message']="Image not uploaded correctly,Please try again.";
    echo $this->encrypt($response);exit(); 
  }
  $response=array();
  $response['status']=1; 
  $response['path']=$result['path'];
  $response['message']='success';
  echo $this->encrypt($response);exit();
}  

public function view_tracking()
{
  
  $params= $this->mobiledata;
  // echo "hrllo";
  // print_r($params);
  // exit;
  
  if(!isset($params['vehId']) || strlen($params['vehId'])===0)
  {
    $response=array();
    $response['status']=-1;
    $response['message']="Invalid Access,Please try again.";
    echo $this->encrypt($response);
    exit();
  }
  //$params['vehId']=$this->encrypt_decrypt($params['vehId'],'decrypt');
  //$params['vehId']=21;
  //  echo "hai";
  // print_r($params);
  // exit;
  $result=$this->Mobileapi102model->view_tracking($params);
  // echo "<pre>";
  // print_r($result);
  // exit;
  $response=array();
  $response['status']=1;
  $response['message']="Successess";
  $response['data']=array();    
  if(count($result)>0)
  {
   $response['data']=$result;
  }
  else{
  $response['no_records_msg']='No Records Found.';
  }
  echo $this->encrypt($response);
  exit();
}

public function cerificate_list()
{
  //echo "hai"; exit;
 $params= $this->mobiledata;
//  echo '<pre>';
//  print_r($params);
//  exit;
 $params['id']=1;
 if(!isset($params['id']) || strlen($params['id'])===0)
 {
  $response=array();
  $response['status']=-1;
  $response['message']="Invalid Access,Please try again.";
  echo $this->encrypt($response);
  exit();
 }
//$params['c_phone']=$this->encrypt_decrypt($params['id'],'decrypt'); 
//9080201070 //IjkwODAyMDEwNzAi
 $params['c_phone']=9080201070;
 $result=$this->Mobileapi102model->verifyuser($params);
// hello<pre>HELLO?????===>Array
// (
//     [c_customer_id] => 652
//     [c_customer_name] => Prabhus
//     [c_address] => 6-22 KRISHNARAGPAREM,

// KONDURU MANDAJA,

// KONDURU,

// ANDHRA PRADESH.
//     [c_phone] => 9080201070
//     [c_photo] => public/upload/customer/1592457514.jpg
//     [c_email] => universalteleservices.in@gmail.com
//     [c_password] => e10adc3949ba59abbe56e057f20f883e
//     [c_device_id] => 
//     [c_status] => ACTIVE
//     [c_user_status] => 1
//     [c_otp_reference] => Ik16TTVOUT09Ig==
//     [c_user_remember] => MjAxLjI1MDQzMTcxNTY3
//     [c_remember] => 
//     [c_updated_date] => 2020-10-24 00:00:00
//     [c_created_by] => 0
//     [c_created_date] => 2019-03-23 14:47:22
// )

 if(empty($result))
 {
  $response=array();
  $response['status']=-1;
  $response['message']="Invalid Attempt.Please try again";
  echo $this->encrypt($response);
  exit();     
 }
 $params['limit']=!isset($params['limit'])?10:$params['limit'];
 $params['start']=!isset($params['start'])?0:$params['start'];
 $result=$this->Mobileapi102model->cerificate_list($params);
// echo "<pre>DATA===>";
// print_r($result);
// exit;
//hello<pre> ANSWERRRRArray
// (
//     [0] => Array
//         (
//             [s_mobile] => 89910473121803806799
//             [s_imei] => 869247047286874
//             [veh_id] => 21
//             [veh_cop_validity] => 2020-10-18 00:00:00
//             [veh_rc_no] => TN23BE4167
//             [c_customer_id] => 652
//             [s_serial_id] => 45
//             [c_phone] => 9080201070
//             [s_serial_number] => PSDN10A009911900000367
//         )

//     [1] => Array
//         (
//             [s_mobile] => 89910473121803806302
//             [s_imei] => 869247047286668
//             [veh_id] => 23
//             [veh_cop_validity] => 2020-10-22 18:19:34
//             [veh_rc_no] => TN88C9204
//             [c_customer_id] => 652
//             [s_serial_id] => 46
//             [c_phone] => 9080201070
//             [s_serial_number] => PSDN10A091900000010
//         )

//     [2] => Array
//         (
//             [s_mobile] => 9498561754
//             [s_imei] => 869247047317083
//             [veh_id] => 75
//             [veh_cop_validity] => 2021-01-26 13:22:13
//             [veh_rc_no] => TN05ZZ7845
//             [c_customer_id] => 652
//             [s_serial_id] => 370
//             [c_phone] => 9080201070
//             [s_serial_number] => PSDN10A091900000000
//         )

// )


    	  // $s_imei=array();
       //  foreach ($result as $key => $value) {
       //    $s_imei[]=$value['s_imei'];
       //  }
       //  $imei=$this->Mobileapi102model->get_imei_number($s_imei);
       //  $resultData=array();
       //  foreach ($result as $key => $value) {                   
       //     foreach ($imei as $key1 => $value1) {
       //        if((string)trim($value1['imei']) === (string)trim($value['s_imei']))
       //        {
       //           $value['uniqueId']=isset($value1['uniqueId'])?$value1['uniqueId']:0;
       //        }
       //     }
       //     $resultData[]=$value;
       //  }
 $response=array();
 $response['status']=1;
 $response['message']="Success";
 $response['data']=array();;
 foreach ($result as $key => $value) {
  $uniqueId=isset($value['s_imei'])?($value['s_imei']):0;
  $vehId=$this->encrypt_decrypt($value['veh_id'],'encrypt');
  $response['data'][]=array(
    "uniqueId"=>$uniqueId,
    "mobileNum"=>trim($value['c_phone']),
    "serialNum"=>trim($value['s_serial_number']),
    "s_imei"=>trim($value['s_imei']),
                   "trackId"=>'352093088118848',//trim($value['s_imei']),
                   "s_mobile"=>trim($value['s_mobile']),
                   "vehId"=>$vehId,
                   "validUpto"=>date('dMY',strtotime($value['veh_cop_validity'])),
                   "vehicleNum"=>trim($value['veh_rc_no']),
                   "viewTrackingInfo"=>base_url().'admin/tracking?vehId='.$vehId.'&Sid='.$uniqueId,
                   "viewUrl"=>TECH_URL.'admin/downloadwebpdf?id='.base64_encode(base64_encode(base64_encode($value['veh_id'])))
    );
  }
  $response['no_records_msg']='No Records Found.';
  $response['nextStart']=(int)$params['start']+10;
  echo $this->encrypt($response);
  exit();
}


//********** Func Starts SANTHOSH 26-08-2019 ************ // 

public function vehiclestatus_list()
{ 
 $params= $this->mobiledata;

 if(!isset($params['id']) || strlen($params['id'])===0)
 {
  $response=array();
  $response['status']=-1;
  $response['message']="Invalid Access,Please try again.";
  echo $this->encrypt($response);
  exit();
 }

$params['c_phone']=$this->encrypt_decrypt($params['id'],'decrypt');


$result=$this->Mobileapi102model->verifyuser($params);

            //print_r($result);
		    //exit();

if(empty($result))
{
 $response=array();
 $response['status']=-1;
 $response['message']="Invalid Attempt.Please try again";
 echo $this->encrypt($response);
 exit();     
}
$params['limit']=!isset($params['limit'])?10:$params['limit'];
$params['start']=!isset($params['start'])?0:$params['start'];



$result=$this->Mobileapi102model->vehiclestatus_list($params);
        //print_r("<pre>");
        //print_r($result);
		//print_r("</pre>");	
$response=array();
$response['status']=1;
$response['message']="Success";
$response['data']=array();



foreach ($result as $key => $value) {

  $uniqueId=isset($value['imei'])?($value['imei']):0;
            //  $vehId=$this->encrypt_decrypt($value['veh_id'],'encrypt');

			//---- Find Address Here ----

  $latitude = trim($value['latitude']);
  $longitude = trim($value['longitude']);

  if(!empty($latitude) && !empty($longitude)){
   $address = 'https://maps.google.com?q='.$value['latitude'].','.$value['longitude'];
 }else{            	
  $address = 'Not Updated';

}

		//---- Find Address Here -----
		//---- Find Ignition Starts ----

$ignition = number_format($value['ignition'], 1);

if(  $ignition == 1){			
  $ignition_pic = "ignition_on.png" ;
}else{
  $ignition_pic = "ignition_off.png" ;	
}
		//---- Find Ignition Ends ----

$response['data'][]=array(

              //    "uniqueId"=>$uniqueId,
                //  "mobileNum"=>trim($value['c_phone']),
                  //"serialNum"=>trim("TEST"),
  "vehicleNum"=>trim($value['vehicleRegnumber']),
  "imei"=>trim($value['imei']),
  "address"=> $address,
  "ignition"=>trim($ignition_pic),
  "speed"=>trim($value['speed']),
  "distance"=>trim($value['distance']),
  "status"=>trim($value['ignition']),				 
  "lastupdated"=>trim( gmdate("d-m-Y H:i:s", $value['lastupdatedTime'])),

              //     "trackId"=>'352093088118848',//trim($value['s_imei']),
               //   "s_mobile"=>trim($value['s_mobile']),
             //     "vehId"=>$vehId,
               //   "validUpto"=>date('dMY',strtotime($value['veh_cop_validity'])),

               //   "viewTrackingInfo"=>base_url().'admin/tracking?vehId='.$vehId.'&Sid='.$uniqueId,
  //  "viewUrl"=>TECH_URL.'admin/downloadwebpdf?id='.base64_encode(base64_encode(base64_encode($value['veh_id'])))
);


}	 

$response['no_records_msg']='No Records Found.';
$response['nextStart']=(int)$params['start']+10;

echo $this->encrypt($response);
exit();


}


//**********Func Ends SANTHOSH 26-08-2019 ************ // 



public function vehicle_list()
{
  $params= $this->mobiledata;
  if(!isset($params['id']) || strlen($params['id'])===0)
  {
    $response=array();
    $response['status']=-1;
    $response['message']="Invalid Access,Please try again.";
    echo $this->encrypt($response);
    exit();
  }

  $params['c_phone']=$this->encrypt_decrypt($params['id'],'decrypt');
  $result=$this->Mobileapi102model->verifyuser($params);

  if(empty($result))
  {
   $response=array();
   $response['status']=-1;
   $response['message']="Invalid Attempt.Please try again";
   echo $this->encrypt($response);
   exit();     
 }
 $params['limit']=!isset($params['limit'])?10:$params['limit'];
 $params['start']=!isset($params['start'])?0:$params['start'];

 $result=$this->Mobileapi102model->cerificate_list($params);

 $response=array();
 $response['status']=1;
 $response['message']="Success";
 $response['data']=array();    

 foreach ($result as $key => $value) {
   $vehId=$this->encrypt_decrypt($value['veh_id'],'encrypt');
   $response['data'][]=array(
    "mobileNum"=>$value['c_phone'],
    "serialNum"=>$value['s_serial_number'],
    "s_imei"=>$value['s_imei'],
    "s_mobile"=>$value['s_mobile'],
    "vehId"=>$vehId,
                    "trackId"=>'352093088118848',//trim($value['s_imei']),
                    "validUpto"=>date('dMY',strtotime($value['veh_cop_validity'])),
                    "vehicleNum"=>$value['veh_rc_no'],
                    "viewUrl"=>TECH_URL.'admin/downloadwebpdf?id='.base64_encode(base64_encode(base64_encode($value['veh_id'])))
                  );
 }       
 $response['no_records_msg']='No Records Found.';
 $response['nextStart']=(int)$params['start']+10;

 echo $this->encrypt($response);
 exit();


}

public function dashboardinfo()
{
 $params= $this->mobiledata;
 if(!isset($params['id']) || strlen($params['id'])===0)
 {
  $response=array();
  $response['status']=-1;
  $response['message']="Invalid Access,Please try again.";
  echo $this->encrypt($response);exit();
}
$params['c_phone']=$this->decrypt($params['id'],'decrypt');
$result=$this->Mobileapi102model->verifyuser($params);
if(empty($result))
{
 $response=array();
 $response['status']=-1;
 $response['message']="Invalid Attempt.Please try again";
 echo $this->encrypt($response);exit();     
}
$result=$this->Mobileapi102model->cerificate_list($params);
$response=array();
$response['activeCerificate']=count($result);
$response['activeVehicles']=count($result);
$response['status']=1;
$response['message']='success';
echo $this->encrypt($response);
exit();
}

public function quick_signin()
{
  $params= $this->mobiledata;
  if(!isset($params['remember']) || strlen($params['remember'])===0)
  {
    $response=array();
    $response['status']=-1;
    $response['message']="Invalid Access,Please try again.";
    echo $this->encrypt($response);exit();
  }        
  $result=$this->Mobileapi102model->quick_signin($params);
  if(empty($result))
  {
   $response=array();
   $response['status']=-2;
   $response['message']="Invalid Username/Password,Please try again.";
   echo $this->encrypt($response);exit();     
 }
 if(!empty($result) && (string)$result['c_user_status']!='1')
 {
   $response=array();
   $response['status']=-2;
   $response['message']="User has been blocked,Contact Admin team.";
   echo $this->encrypt($response);exit();     
 }
 if(((string)$result['c_device_id']!=(string)$params['deviceId']) && (string)$params['forceLogin'] ==='0')
 {
   $response=array();
   $response['status']=2;
   $response['message']="User already loggedin another device, Do you want to force login?";
   echo $this->encrypt($response);exit(); 
 }
 $response=array();
 $response['message']='Successfully loggedIn';
 $response['status']=200;
 $response['detail']=array(
  "userName"=>$result['c_customer_name'],
  "imageUrl"=>base_url().$result['c_photo'],

  "countryCode"=>$this->countryCode,
  "userEmail"=>$result['c_customer_name'],
  "phoneNum"=>$result['c_phone'],
  "id"=>$this->encrypt_decrypt($result['c_phone'],'encrypt'),
  "remember"=>''     
);    
 $updateRemember=array(); 
 $updateRemember['c_customer_id']=$result['c_customer_id'];       
 $response['remember']=$this->encrypt_decrypt(time().'@'.$result['c_customer_id'].'@'.time(),'encrypt');         
 $updateRemember['remember']=$response['remember'];
 $updateRemember['device_id']=$params['device_id'];        
 $updateRemember['c_updated_date']=date('Y-m-d');        
 $result=$this->Mobileapi102model->updateRememberKey($updateRemember);                  
 echo $this->encrypt($response);exit();
}

    // Get Profile starts

public function getCustomerProfileInfo()
{
 $params= $this->mobiledata;
 if(!isset($params['id']) || strlen($params['id'])===0)
 {
  $response=array();
  $response['status']=-1;
  $response['message']="Invalid Access,Please try again.";
  echo $this->encrypt($response);exit();
}

$this->session->set_userdata('profileidssess',$this->encrypt_decrypt($params['id'],'decrypt'));
$params['c_phone']=$this->encrypt_decrypt($params['id'],'decrypt');
$result=$this->Mobileapi102model->getCustomerProfileInfo($params);



if(empty($result))
{
 $response=array();
 $response['status']=-1;
 $response['message']="Invalid Attempt.Please try again";
 echo $this->encrypt($response);exit();     
}



$response=array();
$result['imageUrl']=base_url().$result['imageUrl'];
$response['data']=$result;
$response['id']=$params['id'];
$response['address']=$params['address'];

$response['status']=1;
$response['message']="Success,Valid Profile Information.";

echo $this->encrypt($response);
exit();
}


	// Get Profile Ends




	// Initialize Function0
public function signin()
{	

      $params= $this->mobiledata;
    //   echo "<pre>";print_r($params);exit;
      if(!isset($params['phone']) || !isset($params['password']) || strlen($params['phone'])===0 || strlen($params['password'])===0)
      {
       $response=array();
       $response['status']=-1;
       $response['message']="Username/Password required,Don't Blank";
       echo $this->encrypt($response);exit();
      }
      $params['password']=md5($params['password']);
      $result=$this->Mobileapi102model->signin($params);
      if(empty($result))
      {
       $response=array();
       $response['status']=-2;
       $response['message']="Invalid Username/Password,Please try again.";
       echo $this->encrypt($response);exit();     
      }
      if(!empty($result) && (string)$result['c_user_status']!='1')
      {
       $response=array();
       $response['status']=-2;
       $response['message']="User has been blocked,Contact Admin team.";
       echo $this->encrypt($response);exit();     
      }
      if(((string)$result['c_device_id']!=(string)$params['deviceId']) && (string)$params['forceLogin'] ==='0')
      {
       $response=array();
       $response['status']=2;
       $response['message']="User already loggedin another device, Do you want to force login?";
       echo $this->encrypt($response);exit(); 
      }
    
    $response=array();
    $response['message']='Successfully loggedIn';
    $response['status']=200;
    $response['detail']=array(
      "userName"=>$result['c_customer_name'],
      "imageUrl"=>base_url().$result['c_photo'],
      "countryCode"=>$this->countryCode,
      "userEmail"=>$result['c_customer_name'],
      "phoneNum"=>$result['c_phone'],
      "id"=>$this->encrypt_decrypt($result['c_phone'],'encrypt'),
      "remember"=>''     
    );
    $updateRemember=array(); 
    $updateRemember['c_customer_id']=$result['c_customer_id'];
    if((string)$params['rememberPassword']==='YES')
    {
     $response['remember']=$this->encrypt_decrypt(time().'@'.$result['c_customer_id'].'@'.time(),'encrypt');         
     $updateRemember['remember']=$response['remember'];        
    }						
    $updateRemember['device_id']=$params['device_id'];     
    $updateRemember['c_updated_date']=date('Y-m-d');   
    $result=$this->Mobileapi102model->updateRememberKey($updateRemember);
    echo $this->encrypt($response);exit();
}


  // Initialize Function0
public function soslist()
{ 
  $params= $this->mobiledata;
  if(!isset($params['id'])  || strlen($params['id'])===0 )
  {
    $response=array();
    $response['status']=-1;
    $response['message']="Don't Blank";
    echo $this->encrypt($response);exit();
  }
  $params['c_phone']=$this->encrypt_decrypt($params['id'],'decrypt');
  $result=$this->Mobileapi102model->sos_list($params);    
  $response=array();
  $response['message']='Success';
  $response['no_records']='No Records Found';
  $response['status']=200;
  $response['limit']=3;       
  $response['detail']=$result;       
  echo $this->encrypt($response);exit();
}

public function send_sos_otp()
{
 $params= $this->mobiledata;
 if(!isset($params['id'])  || strlen($params['id'])===0 || 
  !isset($params['phone'])  || strlen($params['phone'])===0  || 
  !isset($params['name'])  || strlen($params['name'])===0 )
 {
  $response=array();
  $response['status']=-1;
  $response['message']="Don't Blank";
  echo $this->encrypt($response);exit();
}
$params['c_phone']=$this->encrypt_decrypt($params['id'],'decrypt');
$count=$this->Mobileapi102model->check_exists_sos($params);    
if((int)$count > 0)
{
  $response=array();
  $response['status']=-1;
  $response['message']="The records already exists";
  echo $this->encrypt($response);exit();
}
$otpNumber=rand(1000,9999);
$otpMessage='OTP '.$otpNumber.' is to add SOS Contact, PSDN Tech.';
$this->Mobileapi102model->send_sms($params['phone'],$otpNumber,$otpMessage);
$response=array();
$response['message']='SOS added successfully,Please enter OTP';
$response['status']=200;
$response['otp_ref']=$otpNumber;
echo $this->encrypt($response);exit();
}
public function add_sos()
{ 
  $params= $this->mobiledata;
  if(!isset($params['id'])  || strlen($params['id'])===0 || 
    !isset($params['phone'])  || strlen($params['phone'])===0  || 
    !isset($params['name'])  || strlen($params['name'])===0 )
  {
    $response=array();
    $response['status']=-1;
    $response['message']="Don't Blank";
    echo $this->encrypt($response);exit();
  }
  $params['c_phone']=$this->encrypt_decrypt($params['id'],'decrypt');

  $count=$this->Mobileapi102model->check_exists_sos($params);    
  if((int)$count > 0)
  {
    $response=array();
    $response['status']=-1;
    $response['message']="The records already exists";
    echo $this->encrypt($response);exit();
  }
  $result=$this->Mobileapi102model->add_sos($params); 

  $response=array();
  $response['message']='SOS added successfully';
  $response['status']=200;
  echo $this->encrypt($response);exit();
}

public function remove_sos()
{
  $params= $this->mobiledata;
  if(!isset($params['id'])  || strlen($params['id'])===0 || 
    !isset($params['sos_id'])  || strlen($params['sos_id'])===0 )
  {
    $response=array();
    $response['status']=-1;
    $response['message']="Don't Blank";
    echo $this->encrypt($response);exit();
  }
  $params['c_phone']=$this->encrypt_decrypt($params['id'],'decrypt');

  $count=$this->Mobileapi102model->remove_sos($params); 
  $response=array();
  $response['message']='SOS Removed successfully';
  $response['status']=200;
  echo $this->encrypt($response);exit();
}

public function distance($lat1, $lon1, $lat2, $lon2, $unit) {
  if (($lat1 == $lat2) && ($lon1 == $lon2)) {
    return 0;
  }
  else {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);

    if ($unit == "K") {
      return ($miles * 1.609344);
    } else if ($unit == "N") {
      return ($miles * 0.8684);
    }else if ($unit == "m") {
      return ($miles * 1.609344)*1000;
    } else {
      return $miles;
    }
  }
}

// public function get_history_data(){
//   $params= $this->mobiledata;
//   $response=array();
//   $response['status']=1;
//   $response['message']="Success";
//         //getvehiclehistorydatas
//   $wholeLatLng=$this->Vehiclehistorymodel->getvehiclehistorydatasmob($params['imei'],$params['startTime'],$params['endTime']);

//         // $response['detail']
//   $filterLatLng = array();
// // echo count($wholeLatLng);
// $markerArray = array();
// $inStop = false;
//   for($a=0;$a<count($wholeLatLng);$a++){
//     if(count($filterLatLng)>0){
//   // echo "haii";
//       $lat1 =$filterLatLng[count($filterLatLng)-1]->latitude;
//       $lng1=$filterLatLng[count($filterLatLng)-1]->longitude;
//       $lat2=$wholeLatLng[$a]->latitude;
//       $lng2=$wholeLatLng[$a]->longitude;

//       $movement = $this->distance($lat1,
//         $lng1,
//         $lat2,
//         $lng2,"m");
//       if($movement>0.1){
//        array_push($filterLatLng, $wholeLatLng[$a]);
//   // echo $movement."-".$lat1.','.$lng1.','.$lat2.','.$lng2.'  </br>';
//      }else{
//  // echo $movement." -  ";
//      }
//    }else{
//     array_push($filterLatLng, $wholeLatLng[$a]);
//   }
// }
// $response['detail'] = $filterLatLng;

// for($i=0;$i<count($response['detail']);$i++){
//   $data = $response['detail'][$i];
//   if($data->ignition == "0"){
//     if(!$inStop){
//   // echo exit;
//       $key = count($markerArray);
//       $markerArray[$key]['latitude'] = $data->latitude;
//       $markerArray[$key]['longitude'] = $data->longitude;
//       $markerArray[$key]['info'] = $data->time;
//       $markerArray[$key]['type'] = "stop";
//       $inStop = true;
//     }
//   }else{
//     if($inStop == true && count($markerArray)!=0){
//       $key = count($markerArray)-1;
//       $markerArray[$key]['info'] = $markerArray[$key]['info'].' - '.$data->time;
//     }
//     $inStop = false;
//   }

// }

// $response['markerArray']= $markerArray;

// echo $this->encrypt($response);exit();
//         // echo json_encode($response);exit();
// }








public function get_history_data(){
  $params= $this->mobiledata;
  $response=array();
  $response['status']=1;
  $response['message']="Success";
  
        //getvehiclehistorydatas
  $wholeLatLng=$this->Vehiclehistorymodel->getvehiclehistorydatasmob($params['imei'],$params['startTime'],$params['endTime']);

  $filterLatLng = array();


  $markerArray = array();
  $idleMarkerArray = array();

  $inStop = false;
  $inIdle = false;
$totalDistance = 0;
  for($a=0;$a<count($wholeLatLng);$a++){
$totalDistance += $wholeLatLng[$a]->distance;
    if(count($filterLatLng)==0 || abs(floatval($wholeLatLng[$a]->speed) > 3.0)){

      if($inIdle == true && count($idleMarkerArray)!=0 && $data->ignition == "1"){
        $key = count($idleMarkerArray)-1;
        $idleMarkerArray[$key]['info'] = $idleMarkerArray[$key]['info'].' - '.$data->time."$ Total Duration: ".$this->timeDiff($idleMarkerArray[$key]['info'],$data->time);
      }
      $inIdle = false;
      array_push($filterLatLng, $wholeLatLng[$a]);
    }else{
      if(!$inIdle && $data->ignition == "1"){
        $key = count($idleMarkerArray);
        $idleMarkerArray[$key]['latitude'] = $data->latitude;
        $idleMarkerArray[$key]['longitude'] = $data->longitude;
        $idleMarkerArray[$key]['info'] = $data->time;
        $idleMarkerArray[$key]['type'] = "idle";
        $inIdle = true;
      }else if ($inIdle && count($idleMarkerArray)!=0 && $data->ignition == "0"){
        $key = count($idleMarkerArray)-1;
        $idleMarkerArray[$key]['info'] = $idleMarkerArray[$key]['info'].' - '.$data->time."$ Total Duration: ".$this->timeDiff($idleMarkerArray[$key]['info'],$data->time);
        $inIdle = false;
      }
    }


    $data = $wholeLatLng[$a];
    if($data->ignition == "0"){
      if(!$inStop){
        $key = count($markerArray);
        $markerArray[$key]['latitude'] = $data->latitude;
        $markerArray[$key]['longitude'] = $data->longitude;
        $markerArray[$key]['info'] = $data->time;
        $markerArray[$key]['type'] = "stop";
        array_push($filterLatLng, $wholeLatLng[$a]);
        $inStop = true;
      }
    }else{
      if($inStop == true && count($markerArray)!=0){
        $key = count($markerArray)-1;
        $markerArray[$key]['info'] = $markerArray[$key]['info'].' - '.$data->time."$ Total Duration: ".$this->timeDiff($markerArray[$key]['info'],$data->time);
        array_push($filterLatLng, $wholeLatLng[$a]);
      }
      $inStop = false;
    }



  }

  $response['detail']= $filterLatLng;

  $response['markerArray']= $markerArray;
  $response['idleMarkerArray']= $idleMarkerArray;
   $response['totalDistance']= $totalDistance;




    // $finalarr = array("rawdatas" => $filterLatLng,"markerArray" => $markerArray,"idleMarkerArray" => $idleMarkerArray );




  echo $this->encrypt($response);exit();
        // echo json_encode($response);exit();
}

  public function timeDiff($date1S,$date2S){



    $date1 = strtotime($date1S);  
    $date2 = strtotime($date2S);  

// Formulate the Difference between two dates 
    $diff = abs($date2 - $date1);  


// To get the year divide the resultant date into 
// total seconds in a year (365*60*60*24) 
    $years = floor($diff / (365*60*60*24));  


// To get the month, subtract it with years and 
// divide the resultant date into 
// total seconds in a month (30*60*60*24) 
    $months = floor(($diff - $years * 365*60*60*24) 
      / (30*60*60*24));  


// To get the day, subtract it with years and  
// months and divide the resultant date into 
// total seconds in a days (60*60*24) 
    $days = floor(($diff - $years * 365*60*60*24 -  
      $months*30*60*60*24)/ (60*60*24)); 


// To get the hour, subtract it with years,  
// months & seconds and divide the resultant 
// date into total seconds in a hours (60*60) 
    $hours = floor(($diff - $years * 365*60*60*24  
      - $months*30*60*60*24 - $days*60*60*24) 
    / (60*60));  


// To get the minutes, subtract it with years, 
// months, seconds and hours and divide the  
// resultant date into total seconds i.e. 60 
    $minutes = floor(($diff - $years * 365*60*60*24  
      - $months*30*60*60*24 - $days*60*60*24  
      - $hours*60*60)/ 60);  


// To get the minutes, subtract it with years, 
// months, seconds, hours and minutes  
    $seconds = floor(($diff - $years * 365*60*60*24  
      - $months*30*60*60*24 - $days*60*60*24 
      - $hours*60*60 - $minutes*60)); 


    $time = "";
    if($years >0 ){
      $time = $years."Year(s) ".$months."Month(s) ".$days."Day(s) ".$hours." Hr(s) ".$minutes."m ".$seconds."s";
    }else if($months >0 ){
      $time = $months."Month(s) ".$days."Day(s) ".$hours." Hr(s) ".$minutes."m ".$seconds."s";
    }else if($days >0 ){
      $time = $days."Day(s) ".$hours." Hr(s) ".$minutes."m ".$seconds."s";
    }else if($hours >0 ){
      $time = $hours." Hr(s) ".$minutes."m ".$seconds."s";
    }else if($minutes >0 ){
      $time = $minutes."min(s) ".$seconds."sec(s)";
    }else{
      $time = $seconds."sec(s)";
    }
    return $time; 

  }
  public function certificate_exp_date_message()
  {
      $this->db->select('veh.veh_owner_id as vehicleOwnerId,veh.validity_to as validityDate,veh.veh_owner_phone as ownerPhone,cus.c_email as customerEmail');	
      $this->db->where('veh.veh_status',1);		
			$this->db->from($this->db->table_vehicle.' as veh');
			$this->db->join($this->db->table_customers.' as cus', 'cus.c_customer_id = veh.veh_owner_id','left');
      $result = $this->db->get();
			$result = $result->result_array();
			$current_dates=date("Y-m-d h:i:s");
      $date1=date_create($current_dates);
      $Message="Your certificate going to expire within 15 days";
			for($i=0;$i<count($result);$i++)
			{
				$validity_dates[$i]=$result[$i]['validityDate'];
				$veh_owner_ids[$i]=$result[$i]['vehicleOwnerId'];
        $veh_owner_phone[$i]=$result[$i]['ownerPhone'];
        $customer_mail[$i]=$result[$i]['customerEmail'];
				$date2=date_create($validity_dates[$i]);   	
				$diff=date_diff($date1,$date2);
        $diff_val=$diff->format('%a');
        //echo $veh_owner_ids[$i];
				if($diff_val==15)
				{
          // echo  $customer_mail[$i]."   ";
          // echo  $validity_dates[$i]."   ";
          // echo  $veh_owner_phone[$i]."   ";
         // echo  $veh_owner_ids[$i]."   ";
          $this->Mobileapi102model->certificate_expirydate_sms($veh_owner_phone[$i],$Message);
          $this->Mobileapi102model->certificate_expirydate_mail($customer_mail[$i]);
        }
      }
      //exit;
      $response=array();
      $response['status']=1;
      $response['message']=array();    
      if(count($result)>0)
      {
        $response['status']=200;
        $response['message']='Your certificate going to expire within 15 days';
      }
      else
      {
       $response['message']='Error occur when message send!';
      }
    echo $this->encrypt($response);exit();
  }
} 