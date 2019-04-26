<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH.'core/Mail_Controller.php');
class User_Controller extends Mail_Controller 
{

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Calcutta'); 		
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('security');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('excel');
		$this->load->database();
		$this->load->library('upload');
		$this->load->model('User_Model');
		$this->load->model('Search_Model');
		
		
	}
	
	
	public function index()
	{
		if ($this->session->userdata('user_id')) 
		{ 
	        $result['user_details']=$this->User_Model->user_details();
			$result['ticket_added']=$this->User_Model->ticket_added();
			$result['my_booking']=$this->User_Model->my_booking();	
			$result['ticket_sold']=$this->User_Model->ticket_sold();
			$result['count_testi']=$this->User_Model->count_testi();
			$result['cancels']=$this->User_Model->cancels();
			$result['wallet']=$this->User_Model->wallet();
			$result['city']=$this->User_Model->select("city_tbl");	
			$result['airline']=$this->User_Model->select("airline_tbl");
			$result["footer"]=$this->Search_Model->get_post(5);
			
			$result["setting"]=$this->Search_Model->setting();

			if(NEW_FLOW)
			{
				$current_user = $this->session->userdata("current_user");
				$companyid = $current_user["companyid"];
				$result["companyid"] = $companyid;
				$result["cname"] = $current_user["cname"];

				$result["company_setting"]=$this->Search_Model->company_setting($companyid);
			}

		    $this->load->view('header1',$result);			
			$this->load->view('user_dashboard',$result);
			$this->load->view('footer1');
		}
		else
		{
			redirect('/login');
		}
		
	}
	
	public function tickets()
	{
		$result['user_details']=$this->User_Model->user_details();
		if ($this->session->userdata('user_id') && $result['user_details'][0]["is_supplier"]==1) 
		{ 
	        $str="";
	        if($_SERVER['REQUEST_METHOD'] == 'POST') 		  
			{
				$arr=array(
				"DATE_FORMAT(departure_date_time, '%Y-%m-%d')<="=>date('Y-m-d', strtotime($this->input->post('dt_from'))),
				"DATE_FORMAT(departure_date_time, '%Y-%m-%d')>="=>date('Y-m-d', strtotime($this->input->post('dt_to'))),
				"source"=>$this->input->post('source'),
				"destination"=>$this->input->post('destination'),
				"pnr"=>$this->input->post('pnr')
				);
				
				
				$result['ticket']=$this->User_Model->search_ticket($arr);
				
				$result['dt_from']=$this->input->post('dt_from');
				$result['dt_to']=$this->input->post('dt_to');
				$result['source']=$this->input->post('source');
				$result['destination']=$this->input->post('destination');
				$result['pnr']=$this->input->post('pnr');
			}
			else
			{
				$result['ticket']=$this->User_Model->ticket();
				
				$result['dt_from']="";
				$result['dt_to']="";
				$result['source']="";
				$result['destination']="";
				$result['pnr']="";
			}
			
			$result['city']=$this->User_Model->select("city_tbl");
	        $result["setting"]=$this->Search_Model->setting();
	        $result["footer"]=$this->Search_Model->get_post(5);
		    $this->load->view('header1',$result);			
			$this->load->view('user_ticket',$result);
			$this->load->view('footer');
		}
		else
		{
			redirect('/user');
		}
		
	}
	
	public function testimonials()
	{
		$result['user_details']=$this->User_Model->user_details();
		if ($this->session->userdata('user_id') ) 
		{ 
	        			
			$result['testimonials']=$this->User_Model->testimonials();
	        $result["setting"]=$this->Search_Model->setting();
	        	$result["footer"]=$this->Search_Model->get_post(5);
		    $this->load->view('header1',$result);			
			$this->load->view('user_testimonials',$result);
			$this->load->view('footer');
		}
		else
		{
			redirect('/user');
		}
		
	}
	
	public function mybookings()
	{
		if ($this->session->userdata('user_id')) 
		{ 
	        $result['user_details']=$this->User_Model->user_details();
			$result['sale_order']=$this->User_Model->my_booking_order();
	        $result["setting"]=$this->Search_Model->setting();
	        $result["footer"]=$this->Search_Model->get_post(5);
		    $this->load->view('header1',$result);			
			$this->load->view('user_booking',$result);
			$this->load->view('footer');
		}
		else
		{
			redirect('/login');
		}	
	}
	
	
	public function booking_details($id)
	{
		if ($this->session->userdata('user_id')) 
		{ 
	        $result['user_details']=$this->User_Model->user_details();
			$result['sale_order']=$this->User_Model->booking_details($id);
	        $result["setting"]=$this->Search_Model->setting();
	        $result["footer"]=$this->Search_Model->get_post(5);
			
		    $this->load->view('header1',$result);			
			$this->load->view('user_booking_details',$result);
			$this->load->view('footer');
		}
		else
		{
			redirect('/login');
		}
		
	}
	
	public function bookingorders()
	{
		if ($this->session->userdata('user_id')) 
		{ 
	        $result['user_details']=$this->User_Model->user_details();
			$result['sale_order']=$this->User_Model->booking_orders();
	        $result["setting"]=$this->Search_Model->setting();
	        $result["footer"]=$this->Search_Model->get_post(5);
		    $this->load->view('header1',$result);			
			$this->load->view('user_booking_orders',$result);
			$this->load->view('footer');
		}
		else
		{
			redirect('/login');
		}
		
	}
	
	public function cancelrequest()
	{
		if ($this->session->userdata('user_id')) 
		{ 
	        $result['user_details']=$this->User_Model->user_details();
			$result['sale_order']=$this->User_Model->cancel_request();
	        $result["setting"]=$this->Search_Model->setting();
	        $result["footer"]=$this->Search_Model->get_post(5);
		    $this->load->view('header1',$result);			
			$this->load->view('user_cancel_request',$result);
			$this->load->view('footer');
		}
		else
		{
			redirect('/login');
		}	
	}
	
	public function edit_booking($id)
	{
		$result1["user"]=$this->User_Model->user_details();		
		//$result["flight"]=$this->Search_Model->get_booking_details($id); 
		$result["flight"]=$this->Search_Model->booking_details($id); 
		if ($this->session->userdata('user_id') && isset($id) && ($result["flight"][0]["customer_id"]==$this->session->userdata('user_id')) && $result1["user"][0]["is_customer"]==1 ) 
		{			          		   				  							  
				  $result["flight"][0]["price"]=$result["flight"][0]["rate"];	
				  $result["flight"][0]["service_charge"]=$result["flight"][0]["service_charge"];	
				  $result["flight"][0]["gst"]=($result["flight"][0]["igst"]+$result["flight"][0]["cgst"]+$result["flight"][0]["sgst"]);	
				  $result["flight"][0]["total"]=$result["flight"][0]["total"];
				  $result["flight"][0]["qty"]=$result["flight"][0]["qty"];
				  $result["flight"][0]["id"]=$id;								  			  			 
				  $result["setting"]=$this->Search_Model->setting();
				  	$result["footer"]=$this->Search_Model->get_post(5);
				  $this->load->view('header1',$result);;
				  $this->load->view('edit_booking',$result);						
				  $this->load->view('footer1');					 						  
		}
		else
		{
			redirect('/user/booking-orders');
		}
	}
	
	
	public function logout()
	{		
		$this->session->unset_userdata('user_id');
		$this->session->unset_userdata('name');
		$this->session->sess_destroy();
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		redirect('/login');				
	}
	
	public function register()
	{
		$result["setting"]=$this->Search_Model->setting();
		$result["footer"]=$this->Search_Model->get_post(5);
		$this->load->view('header1',$result);
		$this->load->view('register');
		$this->load->view('footer');
		
	}
	
	public function login()
	{
		$result["setting"]=$this->Search_Model->setting();
		$result["footer"]=$this->Search_Model->get_post(5);

		if(!NEW_FLOW) {
			$this->load->view('header1',$result);
			$this->load->view('login');
			$this->load->view('footer');
		}
		else {
			$this->load->view('header',$result);
			$this->load->view('login');
			$this->load->view('footer');
		}
	}
	
	
	public function verify()
	{
		$result["setting"]=$this->Search_Model->setting();
		$result["footer"]=$this->Search_Model->get_post(5);
		$this->load->view('header1',$result);
		$this->load->view('verify');
		$this->load->view('footer');
		
	}
	
	public function login_otp()
	{
		$result["setting"]=$this->Search_Model->setting();
		$result["footer"]=$this->Search_Model->get_post(5);
		$this->load->view('header1',$result);
		$this->load->view('login_otp');
		$this->load->view('footer');
		
	}
	
	public function do_register()
	{
		  
		 if(($_SERVER['REQUEST_METHOD'] == 'POST'))
		 {
			 $this->form_validation->set_rules('name', 'Name', 'required|trim|xss_clean');
			 $this->form_validation->set_rules('email','Email','required|trim|xss_clean|valid_email|callback_unique_email');
			 $this->form_validation->set_rules('mobile','Mobile No.','required|numeric|xss_clean|max_length[10]|min_length[10]|callback_unique_mobile');
			 $this->form_validation->set_rules('password','Password','required|xss_clean|min_length[6]');
			 $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
			 
			 if(!$this->form_validation->run()) 
			 {
				$json = array(
					'name' => form_error('name', '<div class="error">', '</div>'),
					'email' => form_error('email', '<div class="error">', '</div>'),                
					'mobile' => form_error('mobile', '<div class="error">', '</div>'),
					'password' => form_error('password', '<div class="error">', '</div>'),
					'confirm_password' => form_error('confirm_password', '<div class="error">', '</div>')								
				);
				
			 }
			 else
			 {
				   
				  $CI =   &get_instance();        
                  $query  =   $CI->db->get('user_tbl');
				  $num=$query->num_rows();
				  $num++;
				  $user_id="OXY".sprintf('%03d',$num);
				  $arr = array(
				             'user_id'=> $user_id,
				             'name' => $this->input->post('name'),
							 'email'=>$this->input->post('email'),
							 'mobile'=>$this->input->post('mobile'),
							 'password'=>$this->input->post('password'),
							 'doj'=>date("Y-m-d")
							 );
				 $otp=rand(111111,999999);
				 $this->session->set_userdata('otp',$otp);
				 $this->session->set_userdata('data',$arr);
				 $this->session->set_userdata('email',$this->input->post('email'));
				 $this->session->set_userdata('name',$this->input->post('name'));
				 $this->session->set_userdata('mobile',$this->input->post('mobile'));
				 $this->session->set_userdata('reg_user_id',$user_id);
				 $no=$this->input->post('mobile');
				 $msg='Dear '.$this->input->post('name').', OTP for mobile number verification is '.$otp.'. Thanks OXYTRA';
				 $this->send_message($no,$msg);
				 $json["success"]=true;
				 
			 }
			 echo json_encode($json);	
		 }		 
	}
	
	
	public function confirm_otp()
	{
		if(($_SERVER['REQUEST_METHOD'] == 'POST'))
		{
			$otp=$this->input->post('otp');
			if ($otp==$this->session->userdata('otp')) 
			{
				$data=$this->session->userdata('data');
				$result = $this->User_Model->save("user_tbl",$data);
				if($result==true)
				{
					   			 
						$data = array(				            
								 'name' => "OXYTRA",
								 'email'=>$this->session->userdata('email'),
								 'msg'=>"Your Registration Completed Successfully",
								 'msg1'=>'After Admin Approval of <span class="il">OXYTRA</span> you can login to this site',
								 'msg2'=>"Enjoy! You will be connected to no.1 Air Ticket booking site"
								 );
						$this->send("Registration",$data);
						
						$data = array(				            
								 'name' => $this->session->userdata('name'),
								 'email'=>$this->session->userdata('email'),
								 'mobile'=>$this->session->userdata('mobile'),
								 'msg'=>"A New User Registered",
								 'user_id'=> $this->session->userdata('reg_user_id'),
								 'msg1'=>'',
								 'msg2'=>""
								 
								 );
								 
						$this->adminsend("Registration",$data);
												
						$no="9800412356";
						$msg="A New User Register with OXYTRA. User ID : ".$this->session->userdata('reg_user_id')."";
						$this->send_message($no,$msg);
						$this->session->set_userdata('otp',"");
					    $this->session->set_userdata('data',"");
						$this->session->set_userdata('email',"");
						$this->session->set_userdata('name',"");
						$this->session->set_userdata('mobile',"");
						$this->session->set_userdata('reg_user_id',"");
						$json["success"]="Your Registration Completed Successfully";
			   }
			   else
			  {
				$json["error"]=false;
			  }
			}
			else
			{
				$json["error"]="You have entered wrong OTP";
			}
			echo json_encode($json);	
		}
	}
	public function confirm_login_otp()
	{
		if(($_SERVER['REQUEST_METHOD'] == 'POST'))
		{
			$login_otp=$this->input->post('login_otp');
			if ($login_otp==$this->session->userdata('login_otp')) 
			{
				if($login_otp=="")
				{
					$json["error"]="You have entered wrong OTP";
				}
				else
				{
					
					 
					 $arr1 = array(
								 'mobile'=>$this->session->userdata('login_mobile'),						 
								 'password'=>$this->session->userdata('login_password'),
								 'active'=>'1'	
								 );
								 
					
					 $result = $this->User_Model->login($arr1);
					 if($result==true)
					 {	
						$this->session->set_userdata('user_id',$result['user_id']); 			 
						$json["success"]="Login Successfully";
					 }
					 else
						 $json["error"]="Your Account is not Approved";
					 
				}
			   
			}
			else
			{
				$json["error"]="You have entered wrong OTP";
			}
			echo json_encode($json);	
		}
	}
	public function update()
	{
		 if(($_SERVER['REQUEST_METHOD'] == 'POST'))
		 {
			 $this->form_validation->set_rules('name', 'Name', 'required|trim|xss_clean');			 
			 $this->form_validation->set_rules('mobile','Mobile No.','required|numeric|xss_clean|max_length[10]|min_length[10]');						 
			 
			 if(!$this->form_validation->run()) 
			 {
				$json = array(
					'name' => form_error('name', '<div class="error">', '</div>'),					              
					'mobile' => form_error('mobile', '<div class="error">', '</div>')												
				);
				
			 }
			 else
			 {
				 $arr = array('name' => $this->input->post('name'),							 
							 'mobile'=>$this->input->post('mobile')							 							
							 );
				 $result = $this->User_Model->update($arr,$this->session->userdata('user_id'));
				 if($result==true)			 
					$json["success"]="Your Profile Updated Successfully";
				 else
					 $json["error"]=$this->db->last_query();
			 }
			 echo json_encode($json);	
		 }		 
	}
	
	
	public function addmarkup()
	{
		 if ($this->session->userdata('user_id')) 
		 {
		 		 $CI =   &get_instance();	
                 $ticket = $CI->db->get_where('tickets_tbl', array('id' => $_REQUEST['hid_markup_ticket_id']));				
				 $result1=$ticket->result_array();
				 
				 $price=$result1[0]["price"];
                 $markup=$result1[0]["markup"];								 
				 $total=$result1[0]["total"];	 
				 
				 $markup+=$_REQUEST['markup_value'];
				 $total+=$_REQUEST['markup_value'];	
				 
				 $where=array("id"=>$_REQUEST['hid_markup_ticket_id']);					 
				 $data=array("markup"=>$markup,"total"=>$total);			 
				 $result = $this->Search_Model->update("tickets_tbl",$data,$where);
				 if($result==true)			 
					$json["success"]="Markup Added Successfully";
				 else
					 $json["error"]=false;
			 
			 echo json_encode($json);	
		 }		 
	}
	
	public function makepayment()
	{
		 if ($this->session->userdata('user_id')) 
		 {
		 		 $payment_type=isset($_POST["payment_type"])?$_POST["payment_type"]:"";
		 		 $refrence_id=isset($_POST["refrence_id"])?$_POST["refrence_id"]:"";
		 		 $cheque_no=isset($_POST["cheque_no"])?$_POST["cheque_no"]:"";
		 		 $bank=isset($_POST["bank"])?$_POST["bank"]:"";
		 		 $amount=isset($_POST["amount"])?$_POST["amount"]:"";
				 
				 			 
				 $data=array(
				 "request_date"=>date("Y-m-d h:i:s"),
				 "user_id"=>$this->session->userdata('user_id'),
				 "payment_type"=>$payment_type,
				 "refrence_id"=>$refrence_id,
				 "cheque_no"=>$cheque_no,
				 "bank"=>$bank,
				 "amount"=>$amount
				 );	
				 
				 $result = $this->User_Model->save("payment_request_tbl",$data);
				 if($result==true)
				 {	
			        $user['user_details']=$this->User_Model->user_details();
					
                    $data = array(				            
				             'name' => "OXYTRA",
							 'email'=>$user['user_details'][0]["email"],
							 'msg'=>"Your Payment Request Sent Successfully",
							 'msg1'=>'After Admin Approval of <span class="il">OXYTRA</span> You will get amount on your wallet',
							 'msg2'=>"Enjoy! You will be connected to no.1 Air Ticket booking site"
							 );
			        $this->send("Payment Request",$data);
					
					
					$data1 = array(				            
				             'name' => $user['user_details'][0]["name"],
							 'email'=>$user['user_details'][0]["email"],
							 'mobile'=>$user['user_details'][0]["mobile"],
							 'msg'=>"A Payment Request is done for ".$amount."",
							 'user_id'=> $user['user_details'][0]["user_id"],
							 'msg1'=>'',
							 'msg2'=>""							 
							 );
							 
					$this->adminsend("Payment Request",$data1);	
					
					$no=$user['user_details'][0]["mobile"];
					$msg="Your Payment Request Sent Successfully. Thanks, OXYTRA";
					$this->send_message($no,$msg);
					
				
					$no="9800412356";
					$msg="A Payment Request Sent by User ID : ".$user['user_details'][0]["user_id"]."";
					$this->send_message($no,$msg);
				    $json["success"]="Payment Request Sent Successfully";
				 }
				 else
					 $json["error"]=false;
			 
			 echo json_encode($json);	
		 }		 
	}
	
	
	public function unique_email($email)
    {
        $CI =   &get_instance();        
        $check = $CI->db->get_where('user_tbl', array('email' => $email));
        
        if ($check->num_rows() > 0) 
		{
           
            $this->form_validation->set_message('unique_email', 'This Email already exists in our database');
            return false;
           
        }
        return TRUE;
    }
	
	public function unique_mobile($mobile)
    {
        $CI =   &get_instance();        
        $check = $CI->db->get_where('user_tbl', array('mobile' => $mobile));
        
        if ($check->num_rows() > 0) 
		{
           
            $this->form_validation->set_message('unique_mobile', 'This Mobile No. already exists in our database');
            return false;
           
        }

        return TRUE;
    }
	
	public function do_login()
	{
		 if(($_SERVER['REQUEST_METHOD'] == 'POST'))
		 {
			 $this->form_validation->set_rules('mobile','Mobile','required|numeric|xss_clean|max_length[10]|min_length[10]');
			 $this->form_validation->set_rules('password','Password','required|xss_clean');
			 
			 
			 if(!$this->form_validation->run()) 
			 {
				$json = array(                
					'mobile' => form_error('mobile', '<div class="error">', '</div>'),                               
					'password' => form_error('password', '<div class="error">', '</div>')											
				);
			 }
			 else
			 {
				if(!NEW_FLOW) {
					$json = $this->do_checkLogin();
				}
				else {
					$json = $this->do_checkNewLogin();
				}
			 }
			 echo json_encode($json);	 
		 }
	}

	private function do_checkNewLogin() {
		try
		{
			$arr = array(
				'mobile'=>$this->input->post('mobile'),						 
				'password'=>$this->input->post('password')						 
			);

			$result = $this->User_Model->newlogin($arr);
			if($result==true)
			{	
				$this->session->set_userdata('current_user',$result);
				$this->session->set_userdata('user_id',$result['user_id']);
				$this->session->set_userdata('name',$result['name']);
				$json["success"]="Login Successfully";
			}
			else {
				$json["error"]="Either wrong mobile number/email, password Or Your account is not yet approved";
			}
		}
		catch(Exception $ex) {
			var_dump($ex->getMessage());
			$json["error"] = $ex->getMessage();
		}

		return $json;
	}

	private function do_checkLogin() {
		try
		{
			$arr = array(
				'mobile'=>$this->input->post('mobile'),						 
				'password'=>$this->input->post('password')						 
				);
			//$result = $this->User_Model->login($arr);

			$arr1 = array(
						'mobile'=>$this->input->post('mobile'),						 
						'password'=>$this->input->post('password'),
						'active'=>'1'	
						);
						
			$result = $this->User_Model->login($arr);
			$result1 = $this->User_Model->login($arr1);
			if($result==true && $result1==true)
			{	
			$this->session->set_userdata('user_id',$result['user_id']);
			$this->session->set_userdata('name',$result['name']);
			$json["success"]="Login Successfully";
			}
			else if($result==true && $result1==false)
				$json["error"]="Your Account is not Approved";
			else
				$json["error"]="Wrong Mobile No. / Password";
		}
		catch(Exception $ex) {
			var_dump($ex->getMessage());
			$json["error"] = $ex->getMessage();
		}

		return $json;
	}
	
	public function uploadimg()
	{
		
		 if ($this->session->userdata('user_id')) 
		{
			$validextensions = array("png","jpeg","bmp","jpg","gif","JPG","PNG","JPEG","BMP","GIF");
			$temporary = explode(".", $_FILES["profile_image"]["name"]); 
			$file_extension = end($temporary);
			if ($_FILES["profile_image"]["size"] <2097152) 
			{

				if(in_array($file_extension,$validextensions))
				{
					$filename ='user_'.rand(10000, 990000) . '_' . time() . '.' . $file_extension;				
					$sourcePath = $_FILES['profile_image']['tmp_name'];   
					$targetPath = 'upload/'.$filename ;  
					$thumbPath = 'upload/thumb/'.$filename ; 
					if(move_uploaded_file($sourcePath,$targetPath))
					{
					  
					  
					  $this->load->library('image_lib');
					  $config['image_library'] = 'gd2';
					  $config['source_image'] = $targetPath;    
					  $config['maintain_ratio'] = true;
					  $config['width'] =250;
					  $config['height'] = 300;
					  $config['new_image'] = $thumbPath;               
					  $this->image_lib->initialize($config);
					  if(!$this->image_lib->resize())
					  { 
						echo $this->image_lib->display_errors();
					  }

					     $arr = array('profile_image' => $filename);							 							 					 														 
						 $result = $this->User_Model->update($arr,$this->session->userdata('user_id'));
						 if($result)	
						 {							 
							$data["success"]["msg"]="Image Uploaded Successfully";
					        $data["success"]["file_name"]=$filename;
						 }
						 else
							 $json["error"]=false;
					}  
					else 
					  $data["error"]="file not uploaded";	 
				} 
				else
				{
				  $data["error"]="Unsupported File Type !!!";
				}



			}
			else
			{
				$data["error"]="File is too large,upload up to 2MB file";
			}
				
			echo json_encode($data);
	    }		  
	    else
		{
			 redirect('admin');
		}
	  
	
	}
	
	
	/*public function addticket()
	{
		
		 if(($_SERVER['REQUEST_METHOD'] == 'POST') && $this->session->userdata('user_id'))
		 {
			 if(isset($_POST["departure_date_time1"]) && !empty($_POST["departure_date_time1"]) && isset($_POST["arrival_date_time1"]) && !empty($_POST["arrival_date_time1"]))
			 {
				 $this->form_validation->set_rules('ticket_no', 'Ticket No.', 'required|trim|xss_clean|callback_unique_ticket_no');			 
				 $this->form_validation->set_rules('pnr','PNR','required|trim|xss_clean|callback_unique_pnr');
				 $this->form_validation->set_rules('trip_type','Trip Type','required|trim|xss_clean');
				 $this->form_validation->set_rules('departure_date_time','Departure Date Time','required|trim|xss_clean');			 
				 $this->form_validation->set_rules('arrival_date_time', 'Arrival Date Time', 'required|trim|xss_clean');			 
				 $this->form_validation->set_rules('departure_date_time1','Departure Date Time','required|trim|xss_clean');			 
				 $this->form_validation->set_rules('arrival_date_time1', 'Arrival Date Time', 'required|trim|xss_clean');				 
				 $this->form_validation->set_rules('price','Price','required|trim|xss_clean');								
				 $this->form_validation->set_rules('total','Total','required|trim|xss_clean');
				 $this->form_validation->set_rules('no_of_person','No. of Person','required|trim|xss_clean');
				 $this->form_validation->set_rules('class','Class','required|trim|xss_clean');
			 }
			 else
			 {
				 $this->form_validation->set_rules('ticket_no', 'Ticket No.', 'required|trim|xss_clean|callback_unique_ticket_no');			 
				 $this->form_validation->set_rules('pnr','PNR','required|trim|xss_clean|callback_unique_pnr');
				 $this->form_validation->set_rules('trip_type','Trip Type','required|trim|xss_clean');
				 $this->form_validation->set_rules('departure_date_time','Departure Date Time','required|trim|xss_clean');			 
				 $this->form_validation->set_rules('arrival_date_time', 'Arrival Date Time', 'required|trim|xss_clean');			 						 
				 $this->form_validation->set_rules('price','Price','required|trim|xss_clean');
				
				 $this->form_validation->set_rules('total','Total','required|trim|xss_clean');
				 $this->form_validation->set_rules('no_of_person','No. of Person','required|trim|xss_clean');
				 $this->form_validation->set_rules('class','Class','required|trim|xss_clean');
			 }
			 if(!$this->form_validation->run()) 
			 {
				    if(isset($_POST["departure_date_time1"]) && !empty($_POST["departure_date_time1"]) && isset($_POST["arrival_date_time1"]) && !empty($_POST["arrival_date_time1"]))
			        {
						$json = array(
							'ticket_no' => form_error('ticket_no', '<div class="error">', '</div>'),					              
							'pnr' => form_error('pnr', '<div class="error">', '</div>'),
							'trip_type' => form_error('trip_type', '<div class="error">', '</div>'),
							'departure_date_time' => form_error('departure_date_time', '<div class="error">', '</div>'),					              
							'arrival_date_time' => form_error('arrival_date_time', '<div class="error">', '</div>'),
							'departure_date_time1' => form_error('departure_date_time1', '<div class="error">', '</div>'),					              
							'arrival_date_time1' => form_error('arrival_date_time1', '<div class="error">', '</div>'),
							'price' => form_error('price', '<div class="error">', '</div>'),					              						
							'total' => form_error('total', '<div class="error">', '</div>'),
							'no_of_person' => form_error('no_of_person', '<div class="error">', '</div>'),
							'class' => form_error('class', '<div class="error">', '</div>')
						);
				    }
					else
					{
						$json = array(
							'ticket_no' => form_error('ticket_no', '<div class="error">', '</div>'),					              
							'pnr' => form_error('pnr', '<div class="error">', '</div>'),
							'trip_type' => form_error('trip_type', '<div class="error">', '</div>'),
							'departure_date_time' => form_error('departure_date_time', '<div class="error">', '</div>'),					              
							'arrival_date_time' => form_error('arrival_date_time', '<div class="error">', '</div>'),							
							'price' => form_error('price', '<div class="error">', '</div>'),					              							
							'total' => form_error('total', '<div class="error">', '</div>'),
							'no_of_person' => form_error('no_of_person', '<div class="error">', '</div>'),
							'class' => form_error('class', '<div class="error">', '</div>')
						);
					}
			 }
			 else
			 {
				  if(isset($_POST["refundable"]))
                    $refundable="Y";
                  else
                   	$refundable="N";
				  if(isset($_POST["departure_date_time1"]) && !empty($_POST["departure_date_time1"]) && isset($_POST["arrival_date_time1"]) && !empty($_POST["arrival_date_time1"]))
			      {
				        $arr = array('ticket_no' => $this->input->post('ticket_no'),							 
							 'pnr'=>$this->input->post('pnr'),
							 'source'=>$this->input->post('source'),
							 'destination'=>$this->input->post('destination'),
							 'source1'=>$this->input->post('source1'),
							 'destination1'=>$this->input->post('destination1'),
							 'trip_type'=>$this->input->post('trip_type'),							
							 'sale_type'=>$this->input->post('sale_type'),							 						
							 'airline' => $this->input->post('airline'),
							 'departure_date_time' => $this->input->post('departure_date_time'),	
							 'arrival_date_time'=>$this->input->post('arrival_date_time'),
							 'flight_no'=>$this->input->post('flight_no'),
							 'terminal'=>$this->input->post('terminal'),
							 'departure_date_time1' => $this->input->post('departure_date_time1'),
							 'arrival_date_time1'=>$this->input->post('arrival_date_time1'),
							 'flight_no1'=>$this->input->post('flight_no1'),
							 'terminal1'=>$this->input->post('terminal1'),
							 'price' => $this->input->post('price'),
							
							 'markup'=>$this->input->post('markup'),
							 'total' => $this->input->post('total'),
							 'user_id'=>$this->session->userdata('user_id'),
							 'refundable'=>$refundable,
							 'no_of_person'=>$this->input->post('no_of_person'),
							 'max_no_of_person'=>$this->input->post('no_of_person'),
							 'class'=>$this->input->post('class')
							 );
				  }
                  else
				  {
					  $arr = array('ticket_no' => $this->input->post('ticket_no'),							 
							 'pnr'=>$this->input->post('pnr'),
							 'source'=>$this->input->post('source'),
							 'destination'=>$this->input->post('destination'),							 
							 'trip_type'=>$this->input->post('trip_type'),							
							 'sale_type'=>$this->input->post('sale_type'),							 							
							 'airline' => $this->input->post('airline'),
							 'arrival_date_time'=>$this->input->post('arrival_date_time'),
							 'departure_date_time' => $this->input->post('departure_date_time'),
							 'flight_no'=>$this->input->post('flight_no'),
							 'terminal'=>$this->input->post('terminal'),
							 'price' => $this->input->post('price'),	
							
							 'markup'=>$this->input->post('markup'),
							 'total' => $this->input->post('total'),
							 'user_id'=>$this->session->userdata('user_id'),
							 'refundable'=>$refundable,
							 'no_of_person'=>$this->input->post('no_of_person'),
							 'max_no_of_person'=>$this->input->post('no_of_person'),
							 'class'=>$this->input->post('class')
							 );
				  }					  
						 
				 $result = $this->User_Model->save("tickets_tbl",$arr);
				 if($result==true)			 
					$json["success"]="Ticket Added Successfully";
				 else
					 $json["error"]=false;
			 }
			 echo json_encode($json);	
		}
		 		 
	}*/
	
	
	public function submit_testimonial()
	{
	    if(($_SERVER['REQUEST_METHOD'] == 'POST') && $this->session->userdata('user_id'))
		 {
		         $arr = array(						 
    						 'date'=>date("Y-m-d h:i:s"),
    						 'user_id'=>$this->session->userdata('user_id'),
    						 'description'=>$this->input->post('description')					
    						 );	
    						 $result = $this->User_Model->save("testimonials_tbl",$arr);
    						 if($result)
    						 {
    						    $this->session->set_flashdata('msg', 'Testimonial Added Successfully');							
							    redirect("/user");							
							    $this->session->flashdata('msg');
    						 }
    						 else
    						 {
    							 echo $this->db->_error_message();die();
    						 }
		 }
	}
	
	
	public function submit_ticket()
	{
		
		if(($_SERVER['REQUEST_METHOD'] == 'POST') && $this->session->userdata('user_id'))
		 {
			if(isset($_POST["refundable"]))
				$refundable="Y";
			else
				$refundable="N";
			
			
			$dateDiff = strtotime($this->input->post('dt_to'))-strtotime($this->input->post('dt_from'));
			$years = floor($dateDiff / (365*60*60*24));
			$months = floor(($dateDiff - $years * 365*60*60*24) / (30*60*60*24));
			$days = floor(($dateDiff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
			
		    $dtfrom=date('d', strtotime($this->input->post('dt_from')));
		    $dtto=date('d', strtotime($this->input->post('dt_to')));
			$sale_type=$this->input->post('sale_type');
			$diff=$dtto-$dtfrom;
			$diff++;
			if($this->input->post('trip_type')=="ONE" )
			{
				        $stops_name=""; 
				        if($this->input->post('no_of_stops')>0)
						{
							foreach($this->input->post('stop_name') as $key=>$value)
							{
								if(empty($stops_name))
									$stops_name=$value;
								else
									$stops_name.=",".$value;
							}
						}
						else
						{
							$stops_name="";
						}
						
				        $j=0;
						for($i=$dtfrom;$i<=$dtto;$i++,$j++)
						{
    						 $dt=date('Y-m-d', strtotime($this->input->post('dt_from').' +'.$j.' day'));
    						 $departure_date_time=$dt." ".$this->input->post('hh').":".$this->input->post('mm').":00";
    						 $arrival_date_time=$dt." ".$this->input->post('hh1').":".$this->input->post('mm1').":00";
    						 
							 $class=isset($_POST["class"])?$_POST["class"]:"";
							 //$availibility=isset($_POST["availibility"])?$_POST["availibility"]:"";
    					     
							 $arr = array(						 
    						 'pnr'=>$_POST["pnr"][$j],
    						 'source'=>$this->input->post('source'),
    						 'destination'=>$this->input->post('destination'),					
    						 'trip_type'=>$this->input->post('trip_type'),												 					 					
    						 'airline' => $this->input->post('airline'),
    						 'aircode' => $this->input->post('aircode'),
    						 'class'=>$class,
    						 'departure_date_time' => $departure_date_time,
    						 'arrival_date_time'=>$arrival_date_time,
    						 'flight_no'=>$this->input->post('flight_no'),
    						 'terminal'=>$this->input->post('terminal'),					 					 
    						 'terminal1'=>$this->input->post('terminal1'),
    						 'price' => $_POST["price"][$j],
							 'discount' => 0,
    						 'markup'=>0,
    						 'total' => $_POST["total"][$j],
    						 'user_id'=>$this->session->userdata('user_id'),
    						 'refundable'=>$refundable,
    						 'no_of_person'=>$_POST["no_of_person"][$j],
    						 'max_no_of_person'=>$_POST["no_of_person"][$j],					 
    						 'remarks'=>$this->input->post('remarks'),
    						 'no_of_stops'=>$this->input->post('no_of_stops'),
							 'stops_name'=>$stops_name,
							  'sale_type'=>$sale_type
							 //'availibility'=>$availibility
    						 );	
    						 $ticket_id = $this->User_Model->save("tickets_tbl",$arr);
							 
						  
						}
						 if($ticket_id)
						 {
							    $user['user_details']=$this->User_Model->user_details();
					            $result["flight"]=$this->Search_Model->flight_details($ticket_id);  
								$data = array(				            
										 'name' => "OXYTRA",
										 'email'=>$user['user_details'][0]["email"],
										 'msg'=>"You have added ".$diff." (ONE WAY) ticket for  ".$result["flight"][0]["source_city"]." to ".$result["flight"][0]["destination_city"]." of ".$this->input->post('total')." INR  Ticket No.(".$ticket_id.")",
										 'msg1'=>'After Admin Approval of <span class="il">OXYTRA</span> Your Tickets will available for sale',
										 'msg2'=>"Enjoy! You are connected to no.1 Air Ticket booking site"
										 );
								$this->send("Ticket Added",$data);
								
								
								$data1 = array(				            
										 'name' => $user['user_details'][0]["name"],
										 'email'=>$user['user_details'][0]["email"],
										 'mobile'=>$user['user_details'][0]["mobile"],
										 'msg'=>$diff." New (ONE WAY) ticket for  ".$result["flight"][0]["source_city"]." to ".$result["flight"][0]["destination_city"]." of ".$this->input->post('total')." INR Ticket No.(".$ticket_id.")",
										 'user_id'=> $user['user_details'][0]["user_id"],
										 'msg1'=>'',
										 'msg2'=>""							 
										 );
										 
							 $this->adminsend("Ticket Added",$data1);	
							 
							$no=$user['user_details'][0]["mobile"];
							$msg=$diff." New (ONE WAY) Ticket Added";
							$this->send_message($no,$msg);


							$no="9800412356";
							$msg=$diff." New (ONE WAY) ticket Added by User ID : ".$user['user_details'][0]["user_id"]."";
							$this->send_message($no,$msg);
							
							
							 $this->session->set_flashdata('msg', 'Ticket Added Successfully');							
							 redirect("/user");							
							 $this->session->flashdata('msg');
						 }
						 else
						 {
							 echo $this->db->_error_message();die();
						 }
											
													
			}
			else
			{
			           
				$stops_name1=""; 
				if($this->input->post('no_of_stops1')>0)
				{
					foreach($this->input->post('stop_name1') as $key=>$value)
					{
						if(empty($stops_name1))
							$stops_name1=$value;
						else
							$stops_name1.=",".$value;
					}
				}
				else
				{
					$stops_name1="";
				}
				
				$stops_name=""; 
				if($this->input->post('no_of_stops')>0)
				{
					foreach($this->input->post('stop_name') as $key=>$value)
					{
						if(empty($stops_name))
							$stops_name=$value;
						else
							$stops_name.=",".$value;
					}
				}
				else
				{
					$stops_name="";
				}
				 $class=isset($_POST["class"])?$_POST["class"]:"";
				 $class1=isset($_POST["class1"])?$_POST["class1"]:"";
				 //$availibility=isset($_POST["availibility"])?$_POST["availibility"]:"";
							 
				 $arr = array(						 
				 'pnr'=>$this->input->post('pnr'),
				 'source'=>$this->input->post('source'),
				 'destination'=>$this->input->post('destination'),
				 'source1'=>$this->input->post('destination'),
				 'destination1'=>$this->input->post('source'),		
				 'trip_type'=>$this->input->post('trip_type'),												 					 					
				 'airline' => $this->input->post('airline'),
				 'airline1' => $this->input->post('airline1'),
				 'aircode' => $this->input->post('aircode'),
				 'aircode1' => $this->input->post('aircode1'),
				 'class'=>$class,
				 'class1'=>$class1,
				 'departure_date_time' => $this->input->post('departure_date_time'),
				 'arrival_date_time'=>$this->input->post('arrival_date_time'),
				 'departure_date_time1' => $this->input->post('departure_date_time1'),
				 'arrival_date_time1'=>$this->input->post('arrival_date_time1'),
				 'flight_no'=>$this->input->post('flight_no'),
				 'flight_no1'=>$this->input->post('flight_no1'),
				 'terminal'=>$this->input->post('terminal'),					 					 
				 'terminal1'=>$this->input->post('terminal1'),
				 'terminal2'=>$this->input->post('terminal2'),					 					 
				 'terminal3'=>$this->input->post('terminal3'),
				 'price' => $this->input->post('price'),					 
				 'markup'=>0,
				 'discount' => 0,
				 'total' => $this->input->post('total'),
				 'user_id'=>$this->session->userdata('user_id'),
				 'refundable'=>$refundable,
				 'no_of_person'=>$this->input->post('no_of_person'),
				 'max_no_of_person'=>$this->input->post('no_of_person'),					 
				 'remarks'=>$this->input->post('remarks'),
				 'no_of_stops'=>$this->input->post('no_of_stops'),
				 'no_of_stops1'=>$this->input->post('no_of_stops1'),
				 'stops_name'=>$stops_name,
				 'stops_name1'=>$stops_name1,
				 'sale_type'=>$sale_type
				 //'availibility'=>$availibility
				 );	
				 
				 $ticket_id = $this->User_Model->save("tickets_tbl",$arr);
				 if($ticket_id)
				 {
					            $user['user_details']=$this->User_Model->user_details();
					            $result["flight"]=$this->Search_Model->flight_details($ticket_id);  
								$data = array(				            
										 'name' => "OXYTRA",
										 'email'=>$user['user_details'][0]["email"],
										 'msg'=>"You have added ".$diff." (RETURN) ticket for  ".$result["flight"][0]["source_city"]." to ".$result["flight"][0]["destination_city"]." of ".$this->input->post('total')." INR for ".$this->input->post('no_of_person')." person Ticket No.(".$ticket_id.")",
										 'msg1'=>'After Admin Approval of <span class="il">OXYTRA</span> Your Tickets will available for sale',
										 'msg2'=>"Enjoy! You are connected to no.1 Air Ticket booking site"
										 );
								$this->send("Ticket Added",$data);
								
								
								$data1 = array(				            
										 'name' => $user['user_details'][0]["name"],
										 'email'=>$user['user_details'][0]["email"],
										 'mobile'=>$user['user_details'][0]["mobile"],
										 'msg'=>$diff." New (RETURN) ticket added for  ".$result["flight"][0]["source_city"]." to ".$result["flight"][0]["destination_city"]." of ".$this->input->post('total')." INR for ".$this->input->post('no_of_person')." person Ticket No.(".$ticket_id.")",
										 'user_id'=> $user['user_details'][0]["user_id"],
										 'msg1'=>'',
										 'msg2'=>""							 
										 );
										 
					 $this->adminsend("Ticket Added",$data1); 
					 
					$no=$user['user_details'][0]["mobile"];
					$msg=$diff." New (RETURN) Ticket Added";
					$this->send_message($no,$msg);


					$no="9800412356";
					$msg=$diff." New (RETURN) ticket Added by User ID : ".$user['user_details'][0]["user_id"]."";
					$this->send_message($no,$msg);
					 $this->session->set_flashdata('msg', 'Ticket Added Successfully');							
					 redirect("/user");							
					 $this->session->flashdata('msg');
				 }
				 else
				 {
					 echo $this->db->_error_message();die();
				 }
    						 
						  
						
			}
		
		}
        else
		{
			redirect('/user');
		}			
	}
	public function import_request_ticket()
	{
		
		if(($_SERVER['REQUEST_METHOD'] == 'POST') && $this->session->userdata('user_id'))
		{									
			$sale_type="quote";			
			if($this->input->post('request_trip_type')=="ONE" )
			{
				        
								if(isset($_FILES["request_file"]["name"]))
								{
									$path = $_FILES["request_file"]["tmp_name"];
									$object = PHPExcel_IOFactory::load($path);
									$diff=0;
									foreach($object->getWorksheetIterator() as $worksheet)
									{
										
										$highestRow = $worksheet->getHighestRow();
										$highestColumn = $worksheet->getHighestColumn();
										for($row=2; $row<=$highestRow; $row++)
										{
											$source = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
											$destination = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
											$date = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
											$available = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
											
											$CI =   &get_instance();	
											$query = $CI->db->get_where('city_tbl', array('code' => $source));				
											$source_arr=$query->result_array();
											$query = $CI->db->get_where('city_tbl', array('code' => $destination));				
											$destination_arr=$query->result_array();
											if(!empty($source) && !empty($destination) && !empty($date) && !empty($available))
											{
												$data = 
												array
												(
													'source'		=>	$source_arr[0]["id"],
													'destination'	=>  $destination_arr[0]["id"],
													'departure_date_time'			=>	date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($date)),
													'available'		=>	$available,
													'no_of_person'=>  "10000",
													'max_no_of_person'=> "10000",
													'trip_type'=>$this->input->post("request_trip_type"),
													'sale_type'=>$sale_type,
													'user_id'=>$this->session->userdata('user_id'),
													'approved'=>0
												);
												
												$departure_date_time=date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($date));
												$departure_date_time=$departure_date_time." 00:00:00";
												$CI =   &get_instance();        
												$query  =   $CI->db->get_where('tickets_tbl',array('source =' =>$source_arr[0]["id"],'destination'=>$destination_arr[0]["id"],'departure_date_time'=>$departure_date_time,"trip_type"=>"ONE",'user_id'=>$this->session->userdata('user_id')));
												$num=$query->num_rows();
												if($num>0)
												{
													$ticket_arr=$query->result_array();
													$id=$ticket_arr[0]["id"];																							
													$this->User_Model->update_table("tickets_tbl",$data,"id",$id);
												}
												else
												  $this->User_Model->save("tickets_tbl",$data);
												$diff++;
											}
										}
										
									}
									
									$user['user_details']=$this->User_Model->user_details();
									$result["flight"]=$this->Search_Model->flight_details($ticket_id);  
									$data = array(				            
											 'name' => "OXYTRA",
											 'email'=>$user['user_details'][0]["email"],
											 'msg'=>"You have added ".$diff." Request Mode (ONE WAY) ticket for  ".$source_arr[0]["city"]." to ".$destination_arr[0]["city"]."",
											 'msg1'=>'After Admin Approval of <span class="il">OXYTRA</span> Your Tickets will available for sale',
											 'msg2'=>"Enjoy! You are connected to no.1 Air Ticket booking site"
											 );
									$this->send("Ticket Added",$data);


									$data1 = array(				            
											 'name' => $user['user_details'][0]["name"],
											 'email'=>$user['user_details'][0]["email"],
											 'mobile'=>$user['user_details'][0]["mobile"],
											 'msg'=>$diff." New Request Mode (ONE WAY) ticket for  Added ".$source_arr[0]["city"]." to ".$destination_arr[0]["city"]." ",
											 'user_id'=> $user['user_details'][0]["user_id"],
											 'msg1'=>'',
											 'msg2'=>""							 
											 );
											 
									$this->adminsend("Ticket Added",$data1);	

									$no=$user['user_details'][0]["mobile"];
									$msg=$diff." New (ONE WAY) Ticket Added";
									$this->send_message($no,$msg);


									$no="9800412356";
									$msg=$diff." Request (ONE WAY) ticket Added by:".$user['user_details'][0]["user_id"]." for ".$source_arr[0]["city"]." to ".$destination_arr[0]["city"]."";
									$this->send_message($no,$msg);


									$this->session->set_flashdata('msg', 'Ticket Added Successfully');							
									redirect("/user");							
									$this->session->flashdata('msg');
									
									
								}
						
								
						 						 																					
			}
			else
			{
				if(isset($_FILES["request_file"]["name"]))
				{
									$path = $_FILES["request_file"]["tmp_name"];
									$object = PHPExcel_IOFactory::load($path);
									$diff=0;
									foreach($object->getWorksheetIterator() as $worksheet)
									{
										
										$highestRow = $worksheet->getHighestRow();
										$highestColumn = $worksheet->getHighestColumn();
										for($row=2; $row<=$highestRow; $row++)
										{
											$source = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
											$destination = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
											$date = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
											$date1 = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
											$available = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
											
											$CI =   &get_instance();	
											$query = $CI->db->get_where('city_tbl', array('code' => $source));				
											$source_arr=$query->result_array();
											$query = $CI->db->get_where('city_tbl', array('code' => $destination));				
											$destination_arr=$query->result_array();
											if(!empty($source) && !empty($destination) && !empty($date) && !empty($available))
											{
												$data = 
												array
												(
													'source'		=>	$source_arr[0]["id"],
													'destination'	=>  $destination_arr[0]["id"],
													'departure_date_time'			=>	date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($date)),
													'departure_date_time1'			=>	date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($date1)),
													'available'		=>	$available,
													'no_of_person'=>    "10000",
													'max_no_of_person'=> "10000",
													'trip_type'=>$this->input->post("request_trip_type"),
													'sale_type'=>$sale_type,
													'user_id'=>$this->session->userdata('user_id'),
													'approved'=>0
												);
												
												$departure_date_time=date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($date));
												$departure_date_time=$departure_date_time." 00:00:00";
												
												$departure_date_time1=date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($date1));
												$departure_date_time1=$departure_date_time1." 00:00:00";
												$CI =   &get_instance();        
												$query  =   $CI->db->get_where('tickets_tbl',array('source =' =>$source_arr[0]["id"],'destination'=>$destination_arr[0]["id"],'departure_date_time'=>$departure_date_time,'departure_date_time1'=>$departure_date_time1,"trip_type"=>"ROUND",'user_id'=>$this->session->userdata('user_id')));
												$num=$query->num_rows();
												if($num>0)
												{
													$ticket_arr=$query->result_array();													
													$id=$ticket_arr[0]["id"];																							
													$this->User_Model->update_table("tickets_tbl",$data,"id",$id);
												}
												else
												  $this->User_Model->save("tickets_tbl",$data);
												$diff++;
											}
										}
										
									}
									
									
									$user['user_details']=$this->User_Model->user_details();
									$result["flight"]=$this->Search_Model->flight_details($ticket_id);  
									$data = array(				            
											 'name' => "OXYTRA",
											 'email'=>$user['user_details'][0]["email"],
											 'msg'=>"You have added ".$diff." Request Mode (RETURN) ticket for  ".$source_arr[0]["city"]." to ".$destination_arr[0]["city"]." ",
											 'msg1'=>'After Admin Approval of <span class="il">OXYTRA</span> Your Tickets will available for sale',
											 'msg2'=>"Enjoy! You are connected to no.1 Air Ticket booking site"
											 );
									$this->send("Ticket Added",$data);


									$data1 = array(				            
											 'name' => $user['user_details'][0]["name"],
											 'email'=>$user['user_details'][0]["email"],
											 'mobile'=>$user['user_details'][0]["mobile"],
											 'msg'=>$diff." New Request Mode (RETURN) ticket for  Added ".$source_arr[0]["city"]." to ".$destination_arr[0]["city"]."",
											 'user_id'=> $user['user_details'][0]["user_id"],
											 'msg1'=>'',
											 'msg2'=>""							 
											 );
											 
									$this->adminsend("Ticket Added",$data1);	

									$no=$user['user_details'][0]["mobile"];
									$msg=$diff." New (RETURN) Ticket Added";
									$this->send_message($no,$msg);


									$no="9800412356";
									$msg=$diff." Request (RETURN) ticket Added by:".$user['user_details'][0]["user_id"]." for ".$source_arr[0]["city"]." to ".$destination_arr[0]["city"]."";
									$this->send_message($no,$msg);


									$this->session->set_flashdata('msg', 'Ticket Added Successfully');							
									redirect("/user");							
									$this->session->flashdata('msg');		
									
									
				}
						
								
			}
			
		
		}
        else
		{
			redirect('/user');
		}			
	}
	
	public function update_ticket()
	{
		if(($_SERVER['REQUEST_METHOD'] == 'POST') && $this->session->userdata('user_id'))
		{
			
			
			if(isset($_POST["refundable"]))
				$refundable="Y";
			else
				$refundable="N";	
			if($this->input->post('trip_type')=="ONE" )
			{
				            $stops_name=""; 
							if($this->input->post('no_of_stops')>0)
							{
								foreach($this->input->post('stop_name') as $key=>$value)
								{
									if(empty($stops_name))
										$stops_name=$value;
									else
										$stops_name.=",".$value;
								}
							}
							else
							{
								$stops_name="";
							}
    						 $departure_date_time=date("Y-m-d",strtotime($this->input->post('dt')))." ".$this->input->post('hh').":".$this->input->post('mm').":00";
    						 $arrival_date_time=date("Y-m-d",strtotime($this->input->post('dt')))." ".$this->input->post('hh1').":".$this->input->post('mm1').":00";
    						
    					     $arr = array(						 
    						 'pnr'=>$this->input->post('pnr'),
    						 'source'=>$this->input->post('source'),
    						 'destination'=>$this->input->post('destination'),					
    						 'trip_type'=>$this->input->post('trip_type'),												 					 					
    						 'airline' => $this->input->post('airline'),
    						 'aircode' => $this->input->post('aircode'),
    						 'class'=>$this->input->post('class'),
    						 'departure_date_time' => $departure_date_time,
    						 'arrival_date_time'=>$arrival_date_time,
    						 'flight_no'=>$this->input->post('flight_no'),
    						 'terminal'=>$this->input->post('terminal'),					 					 
    						 'terminal1'=>$this->input->post('terminal1'),
    						 'price' => $this->input->post('price'),					 
    						 'markup'=>0,
    						 'total' => $this->input->post('total'),
    						 'user_id'=>$this->session->userdata('user_id'),
    						 'refundable'=>$refundable,
    						 'no_of_person'=>$this->input->post('no_of_person'),
    						 'max_no_of_person'=>$this->input->post('no_of_person'),					 
    						 'remarks'=>$this->input->post('remarks'),
    						 'no_of_stops'=>$this->input->post('no_of_stops'),
							 "stops_name"=>$stops_name,
							 'availibility'=>$this->input->post('availibility'),
							 'available'=>$this->input->post('available')
    						 );	
    						 $result = $this->User_Model->update_table("tickets_tbl",$arr,"id",$this->input->post('ticket_id'));
    						 if($result)
							 {
								 
								
								 $this->session->set_flashdata('msg', 'Ticket Updated Successfully');							
								 redirect("/user");							
								 $this->session->flashdata('msg');
							 }
							 else
							 {
								 echo $this->db->_error_message();die();
							 }	
            }
            else
			{
				            $stops_name=""; 
							if($this->input->post('no_of_stops')>0)
							{
								foreach($this->input->post('stop_name') as $key=>$value)
								{
									if(empty($stops_name))
										$stops_name=$value;
									else
										$stops_name.=",".$value;
								}
							}
							else
							{
								$stops_name="";
							}
							
							$stops_name1=""; 
							if($this->input->post('no_of_stops1')>0)
							{
								foreach($this->input->post('stop_name1') as $key=>$value)
								{
									if(empty($stops_name1))
										$stops_name1=$value;
									else
										$stops_name1.=",".$value;
								}
							}
							else
							{
								$stops_name1="";
							}
				$arr = array(						 
				 'pnr'=>$this->input->post('pnr'),
				 'source'=>$this->input->post('source'),
				 'destination'=>$this->input->post('destination'),
				 'source1'=>$this->input->post('destination'),
				 'destination1'=>$this->input->post('source'),		
				 'trip_type'=>$this->input->post('trip_type'),												 					 					
				 'airline' => $this->input->post('airline'),
				 'airline1' => $this->input->post('airline1'),
				 'aircode' => $this->input->post('aircode'),
				 'aircode1' => $this->input->post('aircode1'),
				 'class'=>$this->input->post('class'),
				 'class1'=>$this->input->post('class1'),
				 'departure_date_time' => $this->input->post('departure_date_time'),
				 'arrival_date_time'=>$this->input->post('arrival_date_time'),
				 'departure_date_time1' => $this->input->post('departure_date_time1'),
				 'arrival_date_time1'=>$this->input->post('arrival_date_time1'),
				 'flight_no'=>$this->input->post('flight_no'),
				 'flight_no1'=>$this->input->post('flight_no1'),
				 'terminal'=>$this->input->post('terminal'),					 					 
				 'terminal1'=>$this->input->post('terminal1'),
				 'terminal2'=>$this->input->post('terminal2'),					 					 
				 'terminal3'=>$this->input->post('terminal3'),
				 'price' => $this->input->post('price'),					 
				 'markup'=>0,
				 'total' => $this->input->post('total'),
				 'user_id'=>$this->session->userdata('user_id'),
				 'refundable'=>$refundable,
				 'no_of_person'=>$this->input->post('no_of_person'),
				 'max_no_of_person'=>$this->input->post('no_of_person'),					 
				 'remarks'=>$this->input->post('remarks'),
				 'no_of_stops'=>$this->input->post('no_of_stops'),
				 'no_of_stops1'=>$this->input->post('no_of_stops1'),
				 'stops_name'=>$stops_name,
				 'stops_name1'=>$stops_name1,
				 'availibility'=>$this->input->post('availibility'),
				  'available'=>$this->input->post('available')
				 );	
				 $result = $this->User_Model->update_table("tickets_tbl",$arr,"id",$this->input->post('ticket_id'));
				 if($result)
				 {
					 $this->session->set_flashdata('msg', 'Ticket Updated Successfully');							
					 redirect("/user");							
					 $this->session->flashdata('msg');
				 }
				 else
				 {
					 echo $this->db->_error_message();die();
				 }	
				 
			}				
		}
        else
		{
			redirect('/user');
		}			
	}
	
	
	public function add_one_way()
	{
		if ($this->session->userdata('user_id')) 
		{
			$result["setting"]=$this->Search_Model->setting();
				$result["footer"]=$this->Search_Model->get_post(5);
			$result['city']=$this->User_Model->select("city_tbl");	
			$result['airline']=$this->User_Model->select("airline_tbl");
		    $this->load->view('header1',$result);
			$this->load->view('ticket-form');
			$this->load->view('footer');
		}
		else
		{
			redirect('/login');
			
		}
	}
	
	
	public function add_testimonial()
	{
		if ($this->session->userdata('user_id')) 
		{
			$result["setting"]=$this->Search_Model->setting();
				$result["footer"]=$this->Search_Model->get_post(5);
		    $this->load->view('header1',$result);
			$this->load->view('testimonial-form');
			$this->load->view('footer');
		}
		else
		{
			redirect('/login');
			
		}
	}
	
	
	public function add_return_ticket()
	{
		if ($this->session->userdata('user_id')) 
		{
			$result["setting"]=$this->Search_Model->setting();
				$result["footer"]=$this->Search_Model->get_post(5);
			$result['city']=$this->User_Model->select("city_tbl");	
			$result['airline']=$this->User_Model->select("airline_tbl");
		    $this->load->view('header1',$result);
			$this->load->view('return-ticket-form');
			$this->load->view('footer');
		}
		else
		{
			redirect('/login');
			
		}
	}
	
	
	public function edit_ticket($id)
	{
		if ($this->session->userdata('user_id')) 
		{
			$CI =   &get_instance();        
			$check = $CI->db->get_where('tickets_tbl', array('id' => $id));			
			if ($check->num_rows() > 0) 
			{
			   
				$result["setting"]=$this->Search_Model->setting();
				$result["footer"]=$this->Search_Model->get_post(5);
				$result['city']=$this->User_Model->select("city_tbl");	
				$result['airline']=$this->User_Model->select("airline_tbl");
				$result["flight"]=$this->Search_Model->flight_details($id); 
				if($result["flight"][0]["trip_type"]=="ONE")
				{
					$this->load->view('header1',$result);
					$this->load->view('edit-ticket-form');
					$this->load->view('footer');
				}
				else
				{
					$this->load->view('header1',$result);
					$this->load->view('edit-return-ticket-form');
					$this->load->view('footer');
				}
			   
			}
			else
				redirect('/user');
		}
		else
		{
			redirect('/login');
			
		}
	}
	
	
	public function edit_testimonial($id)
	{
		if ($this->session->userdata('user_id')) 
		{
			$CI =   &get_instance();        
			$check = $CI->db->get_where('testimonials_tbl', array('id' => $id));			
			if ($check->num_rows() > 0) 
			{
			   
				   $result["setting"]=$this->Search_Model->setting();
					$this->load->view('header1',$result);
					$this->load->view('ticket-form');
					$this->load->view('footer');
				
			   
			}
			else
				redirect('/user');
		}
		else
		{
			redirect('/login');
			
		}
	}
	
	
	public function unique_ticket_no($ticket_no)
    {
        $CI =   &get_instance();        
        $check = $CI->db->get_where('tickets_tbl', array('ticket_no' => $ticket_no));
        
        if ($check->num_rows() > 0) 
		{
           
            $this->form_validation->set_message('unique_ticket_no', 'This Ticket No. already exists in our database');
            return false;
           
        }

        return TRUE;
    }
	
	public function unique_pnr($pnr)
    {
        $CI =   &get_instance();        
        $check = $CI->db->get_where('tickets_tbl', array('pnr' => $pnr));        
        if ($check->num_rows() > 0) 
		{           
            $this->form_validation->set_message('unique_pnr', 'This PNR already exists in our database');
            return false;           
        }
        return TRUE;
    }
	
	
	public function transaction()
	{
		if ($this->session->userdata('user_id')) 
		{ 
	        $result['user_details']=$this->User_Model->user_details();
			$result['wallet_transaction']=$this->User_Model->wallet_transaction();
	        $result["setting"]=$this->Search_Model->setting();
	        $result["footer"]=$this->Search_Model->get_post(5);
		    $this->load->view('header1',$result);
			$this->load->view('user_transaction',$result);
			$this->load->view('footer');
		}
		else
		{
			redirect('/login');
		}
		
	}
	
	public function updatepnr()
	{
		if ($this->session->userdata('user_id')) 
		{ 
			$id=$this->input->post('hid_refrence_booking_id');
			$pnr=$this->input->post('pnr');
			$arr = array('pnr' => $pnr);					 							
            $where=array("refrence_id"=>$id);
			$result = $this->Search_Model->update("customer_information_tbl",$arr,$where);
			
            $where=array("id"=>$id);
			$result = $this->Search_Model->update("refrence_booking_tbl",$arr,$where);
		    //echo $this->db->last_query();die();
			if($result==true)			 
			{
				$this->session->set_flashdata('msg', 'PNR Updated Successfully');
				redirect("user/booking-orders");
				$this->session->flashdata('msg');
			}
		}
		else
		{
			redirect('/login');
		}
		
	}
	
	public function forgot_password()
	{
		 if(($_SERVER['REQUEST_METHOD'] == 'POST'))
		 {
			 $this->form_validation->set_rules('mobile','Mobile No.','required|numeric|xss_clean|max_length[10]|min_length[10]');				 			 
			 if(!$this->form_validation->run()) 
			 {
				$json = array('forgot_mobile' => form_error('mobile', '<div class="error">', '</div>') );       				        																			
			 }
			 else
			 {
				$CI =   &get_instance();        
				$check = $CI->db->get_where('user_tbl', array('mobile' => $this->input->post('mobile')));				
				if ($check->num_rows() == 0) 
				{
				     
					$json = array('forgot_mobile' =>  '<div class="error">This Mobile No. does not exist in our database</div>');       
				   
				}
				else
				{
					$result=$check->result();
		            $password=$result[0]->password;
					$name=$result[0]->name;
					$json["success"] =  "Your Password is sent to your Mobile No.";
					
					$no=$this->input->post('mobile');
				    $msg='Dear '.$name.' '.$password.' is your password. Thanks, OXYTRA';
				    $this->send_message($no,$msg);
				}					
				
			 }
			 echo json_encode($json);	
			
		 }
		 
		 
		
	}
	
	public function send_login_otp()
	{
		 if(($_SERVER['REQUEST_METHOD'] == 'POST'))
		 {
			 $this->form_validation->set_rules('mobile','Mobile No.','required|numeric|xss_clean|max_length[10]|min_length[10]');				 			 
			 if(!$this->form_validation->run()) 
			 {
				$json = array('otp_mobile' => form_error('mobile', '<div class="error">', '</div>') );       				        																			
			 }
			 else
			 {
				$CI =   &get_instance();        
				$check = $CI->db->get_where('user_tbl', array('mobile' => $this->input->post('mobile')));				
				if ($check->num_rows() == 0) 
				{
				     
					$json = array('otp_mobile' =>  '<div class="error">This Mobile No. does not exist in our database</div>');       
				   
				}
				else
				{
					$result=$check->result();
					$login_mobile=$result[0]->mobile;
		            $login_password=$result[0]->password;
					$name=$result[0]->name;
					
					$json["success"] =  "Your Login OTP is sent to your Mobile No.";
					
					$no=$this->input->post('mobile');
					$login_otp=rand(111111,999999);
					
				    $this->session->set_userdata('login_otp',$login_otp);
					$this->session->set_userdata('login_mobile',$login_mobile);
					$this->session->set_userdata('login_password',$login_password);
					
				    $msg='Dear '.$name.' '.$login_otp.' is your Login OTP. Thanks, OXYTRA';
				    $this->send_message($no,$msg);
				}					
				
			 }
			 echo json_encode($json);	
			
		 }		 		 		
	}
}
