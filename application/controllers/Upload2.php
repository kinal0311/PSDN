<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends CI_Controller {
	
	// Load Constructur
	public function __construct() {
        parent::__construct();		
    }
	
	// Upload Dealer Profile photo
	public function dealer_profile_photo() {	

		$path = "public/upload/";
		if (!is_dir($path)) {
			mkdir($path, 0777, TRUE);
		}
		$path = "public/upload/users";
		if (!is_dir($path)) {
			mkdir($path, 0777, TRUE);
		}
		
		$path = "public/upload/vehicle";
		if (!is_dir($path)) {
			mkdir($path, 0777, TRUE);
		}
	
		$path = "public/temp_upload/";
		if (!is_dir($path)) {
			mkdir($path, 0777, TRUE);
		}	
		$returnResponse=array();
		$valid_formats = array("jpg", "png", "gif", "bmp","jpeg");
	try {
        if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
            if($_FILES) {
                $name = $_FILES['upload_profile_photo']['name'];
                $size = $_FILES['upload_profile_photo']['size'];
                if(strlen($name)) {
                    list($txt, $ext) = explode(".", $name);
                    if(in_array(strtolower($ext),$valid_formats)) {
                        if($size<(5242880)) {
                            $actual_image_name = time().".".$ext;
                            $tmp = $_FILES['upload_profile_photo']['tmp_name'];
                            if(move_uploaded_file($tmp, $path.$actual_image_name))
                            {
                               $returnResponse['success']=true; 
							   $returnResponse['path']=$path.$actual_image_name; 


							     $config['image_library'] = 'gd2';  
			                     $config['source_image'] =  $path.$actual_image_name;  
			                     $config['create_thumb'] = FALSE;  
			                     $config['maintain_ratio'] = TRUE;  
			                     $config['quality'] = '60%';  
			                     $config['width'] = 200;  
			                     $config['height'] = 200;  
			                     $config['new_image'] =  $path.$actual_image_name;  
			                     $this->load->library('image_lib', $config);  
			                     $this->image_lib->resize();  




                            } else {
							   $returnResponse['fail']=true; 
							   $returnResponse['error']='fiald to upload image';                                
                            }
                        } else {
							$returnResponse['fail']=true; 
							$returnResponse['error']='Image file size max 5 MB';                            
                        }
                    } else {                        
						$returnResponse['fail']=true; 
						$returnResponse['error']='Invalid file format..';
                    }
                } else {                 
				 $returnResponse['fail']=true; 
				 $returnResponse['error']='Please select image..!';
                }
            } else {
				$returnResponse['fail']=true; 
				$returnResponse['error']='Please select image..!';             
            }
        } 
    } catch(Exception $e) {
		$returnResponse['fail']=true; 
	    $returnResponse['error']=$e->getMessage();       
    }	 
	echo json_encode($returnResponse);

	}
	
	
	// Upload upload_gst_certificate
	public function upload_gst_certificate() {	
	
		$path = "public/upload/upload_gst_certificate/";
		if (!is_dir($path)) {
			mkdir($path, 0777, TRUE);
		}	
		$returnResponse=array();
		$valid_formats = array("jpg", "png", "gif", "bmp","jpeg");

	try {
        if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
            if($_FILES) {
                $name = $_FILES['upload_gst_certificate']['name'];
                $size = $_FILES['upload_gst_certificate']['size'];
                if(strlen($name)) {
                    list($txt, $ext) = explode(".", $name);
                    if(in_array(strtolower($ext),$valid_formats)) {
                        if($size<(5242880)) {
                            $actual_image_name = $txt."_".time().".".$ext;
                            $tmp = $_FILES['upload_gst_certificate']['tmp_name'];
                            if(move_uploaded_file($tmp, $path.$actual_image_name))
                            {
                               $returnResponse['success']=true; 
							   $returnResponse['path']=$path.$actual_image_name; 


							     $config['image_library'] = 'gd2';  
			                     $config['source_image'] =  $path.$actual_image_name;  
			                     $config['create_thumb'] = FALSE;  
			                     $config['maintain_ratio'] = TRUE;  
			                     $config['quality'] = '60%';  
			                     $config['width'] = 200;  
			                     $config['height'] = 200;  
			                     $config['new_image'] =  $path.$actual_image_name;  
			                     $this->load->library('image_lib', $config);  
			                     $this->image_lib->resize();  




                            } else {
							   $returnResponse['fail']=true; 
							   $returnResponse['error']='fiald to upload image';                                
                            }
                        } else {
							$returnResponse['fail']=true; 
							$returnResponse['error']='Image file size max 5 MB';                            
                        }
                    } else {                        
						$returnResponse['fail']=true; 
						$returnResponse['error']='Invalid file format..';
                    }
                } else {                 
				 $returnResponse['fail']=true; 
				 $returnResponse['error']='Please select image..!';
                }
            } else {
				$returnResponse['fail']=true; 
				$returnResponse['error']='Please select image..!';             
            }
        } 
    } catch(Exception $e) {
		$returnResponse['fail']=true; 
	    $returnResponse['error']=$e->getMessage();       
    }	 
	echo json_encode($returnResponse);

	}
	
	// Upload Dealer Profile photo
	public function upload_id_proof() {	


		$path = "public/upload/upload_id_proof/";
		if (!is_dir($path)) {
			mkdir($path, 0777, TRUE);
		}	
		$returnResponse=array();
		$valid_formats = array("jpg", "png", "gif", "bmp","jpeg");
	try {
        if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
            if($_FILES) {
                $name = $_FILES['upload_id_proof']['name'];
                $size = $_FILES['upload_id_proof']['size'];
                if(strlen($name)) {
                    list($txt, $ext) = explode(".", $name);
                    if(in_array(strtolower($ext),$valid_formats)) {
                        if($size<(5242880)) {
                            $actual_image_name = $txt."_".time().".".$ext;
                            $tmp = $_FILES['upload_id_proof']['tmp_name'];
                            if(move_uploaded_file($tmp, $path.$actual_image_name))
                            {
                               $returnResponse['success']=true; 
							   $returnResponse['path']=$path.$actual_image_name; 


							     $config['image_library'] = 'gd2';  
			                     $config['source_image'] =  $path.$actual_image_name;  
			                     $config['create_thumb'] = FALSE;  
			                     $config['maintain_ratio'] = TRUE;  
			                     $config['quality'] = '60%';  
			                     $config['width'] = 200;  
			                     $config['height'] = 200;  
			                     $config['new_image'] =  $path.$actual_image_name;  
			                     $this->load->library('image_lib', $config);  
			                     $this->image_lib->resize();  




                            } else {
							   $returnResponse['fail']=true; 
							   $returnResponse['error']='fiald to upload image';                                
                            }
                        } else {
							$returnResponse['fail']=true; 
							$returnResponse['error']='Image file size max 5 MB';                            
                        }
                    } else {                        
						$returnResponse['fail']=true; 
						$returnResponse['error']='Invalid file format..';
                    }
                } else {                 
				 $returnResponse['fail']=true; 
				 $returnResponse['error']='Please select image..!';
                }
            } else {
				$returnResponse['fail']=true; 
				$returnResponse['error']='Please select image..!';             
            }
        } 
    } catch(Exception $e) {
		$returnResponse['fail']=true; 
	    $returnResponse['error']=$e->getMessage();       
    }	 
	echo json_encode($returnResponse);

	}
	
	
	// Upload Dealer Profile photo
	public function upload_photo_personal() {	

		$path = "public/upload/upload_photo_personal/";
		if (!is_dir($path)) {
			mkdir($path, 0777, TRUE);
		}	
		$returnResponse=array();
		$valid_formats = array("jpg", "png", "gif", "bmp","jpeg");
	try {
        if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
            if($_FILES) {
                $name = $_FILES['upload_photo_personal']['name'];
                $size = $_FILES['upload_photo_personal']['size'];
                if(strlen($name)) {
                    list($txt, $ext) = explode(".", $name);
                    if(in_array(strtolower($ext),$valid_formats)) {
                        if($size<(5242880)) {
                            $actual_image_name = $txt."_".time().".".$ext;
                            $tmp = $_FILES['upload_photo_personal']['tmp_name'];
                            if(move_uploaded_file($tmp, $path.$actual_image_name))
                            {
                               $returnResponse['success']=true; 
							   $returnResponse['path']=$path.$actual_image_name; 


							     $config['image_library'] = 'gd2';  
			                     $config['source_image'] =  $path.$actual_image_name;  
			                     $config['create_thumb'] = FALSE;  
			                     $config['maintain_ratio'] = TRUE;  
			                     $config['quality'] = '60%';  
			                     $config['width'] = 200;  
			                     $config['height'] = 200;  
			                     $config['new_image'] =  $path.$actual_image_name;  
			                     $this->load->library('image_lib', $config);  
			                     $this->image_lib->resize();  




                            } else {
							   $returnResponse['fail']=true; 
							   $returnResponse['error']='fiald to upload image';                                
                            }
                        } else {
							$returnResponse['fail']=true; 
							$returnResponse['error']='Image file size max 5 MB';                            
                        }
                    } else {                        
						$returnResponse['fail']=true; 
						$returnResponse['error']='Invalid file format..';
                    }
                } else {                 
				 $returnResponse['fail']=true; 
				 $returnResponse['error']='Please select image..!';
                }
            } else {
				$returnResponse['fail']=true; 
				$returnResponse['error']='Please select image..!';             
            }
        } 
    } catch(Exception $e) {
		$returnResponse['fail']=true; 
	    $returnResponse['error']=$e->getMessage();       
    }	 
	echo json_encode($returnResponse);

	}
	
	// Upload Dealer Profile photo
	public function upload_pan_card() {	

		$path = "public/upload/upload_pan_card/";
		if (!is_dir($path)) {
			mkdir($path, 0777, TRUE);
		}	
		$returnResponse=array();
		$valid_formats = array("jpg", "png", "gif", "bmp","jpeg");
	try {
        if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
            if($_FILES) {
                $name = $_FILES['upload_pan_card']['name'];
                $size = $_FILES['upload_pan_card']['size'];
                if(strlen($name)) {
                    list($txt, $ext) = explode(".", $name);
                    if(in_array(strtolower($ext),$valid_formats)) {
                        if($size<(5242880)) {
                            $actual_image_name = $txt."_".time().".".$ext;
                            $tmp = $_FILES['upload_pan_card']['tmp_name'];
                            if(move_uploaded_file($tmp, $path.$actual_image_name))
                            {
                               $returnResponse['success']=true; 
							   $returnResponse['path']=$path.$actual_image_name; 


							     $config['image_library'] = 'gd2';  
			                     $config['source_image'] =  $path.$actual_image_name;  
			                     $config['create_thumb'] = FALSE;  
			                     $config['maintain_ratio'] = TRUE;  
			                     $config['quality'] = '60%';  
			                     $config['width'] = 200;  
			                     $config['height'] = 200;  
			                     $config['new_image'] =  $path.$actual_image_name;  
			                     $this->load->library('image_lib', $config);  
			                     $this->image_lib->resize();  




                            } else {
							   $returnResponse['fail']=true; 
							   $returnResponse['error']='fiald to upload image';                                
                            }
                        } else {
							$returnResponse['fail']=true; 
							$returnResponse['error']='Image file size max 5 MB';                            
                        }
                    } else {                        
						$returnResponse['fail']=true; 
						$returnResponse['error']='Invalid file format..';
                    }
                } else {                 
				 $returnResponse['fail']=true; 
				 $returnResponse['error']='Please select image..!';
                }
            } else {
				$returnResponse['fail']=true; 
				$returnResponse['error']='Please select image..!';             
            }
        } 
    } catch(Exception $e) {
		$returnResponse['fail']=true; 
	    $returnResponse['error']=$e->getMessage();       
    }	 
	echo json_encode($returnResponse);

	}
	
	// Upload Dealer Profile photo
	public function upload_cancelled_cheque_leaf() {	

		$path = "public/upload/upload_cancelled_cheque_leaf/";
		if (!is_dir($path)) {
			mkdir($path, 0777, TRUE);
		}	
		$returnResponse=array();
		$valid_formats = array("jpg", "png", "gif", "bmp","jpeg");
	try {
        if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
            if($_FILES) {
                $name = $_FILES['upload_cancelled_cheque_leaf']['name'];
                $size = $_FILES['upload_cancelled_cheque_leaf']['size'];
                if(strlen($name)) {
                    list($txt, $ext) = explode(".", $name);
                    if(in_array(strtolower($ext),$valid_formats)) {
                        if($size<(5242880)) {
                            $actual_image_name = $txt."_".time().".".$ext;
                            $tmp = $_FILES['upload_cancelled_cheque_leaf']['tmp_name'];
                            if(move_uploaded_file($tmp, $path.$actual_image_name))
                            {
                               $returnResponse['success']=true; 
							   $returnResponse['path']=$path.$actual_image_name; 


							     $config['image_library'] = 'gd2';  
			                     $config['source_image'] =  $path.$actual_image_name;  
			                     $config['create_thumb'] = FALSE;  
			                     $config['maintain_ratio'] = TRUE;  
			                     $config['quality'] = '60%';  
			                     $config['width'] = 200;  
			                     $config['height'] = 200;  
			                     $config['new_image'] =  $path.$actual_image_name;  
			                     $this->load->library('image_lib', $config);  
			                     $this->image_lib->resize();  




                            } else {
							   $returnResponse['fail']=true; 
							   $returnResponse['error']='fiald to upload image';                                
                            }
                        } else {
							$returnResponse['fail']=true; 
							$returnResponse['error']='Image file size max 5 MB';                            
                        }
                    } else {                        
						$returnResponse['fail']=true; 
						$returnResponse['error']='Invalid file format..';
                    }
                } else {                 
				 $returnResponse['fail']=true; 
				 $returnResponse['error']='Please select image..!';
                }
            } else {
				$returnResponse['fail']=true; 
				$returnResponse['error']='Please select image..!';             
            }
        } 
    } catch(Exception $e) {
		$returnResponse['fail']=true; 
	    $returnResponse['error']=$e->getMessage();       
    }	 
	echo json_encode($returnResponse);

	}
	
	
	
}
