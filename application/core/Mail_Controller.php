<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');
class Mail_Controller  extends CI_Controller 
{
	var $DEFAULT_EMAIL_SETTINGS = array(
		"protocol" => "",
		"host" => "",
		"port" => "",
		"user" => "",
		"password" => "",
	);

	public function __construct()
	{
		parent::__construct();		
		$this->load->library('form_validation');
		//$this->load->library('encrypt');
		$this->load->library('encryption');
		$this->load->library('email');
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->helper('form');           			
	}

	public function send_sms($function, $subject, $company, $data) {
		$company = $this->get_companyinfo();
		$configuration = $company["configuration"];
		$email_settings = array_merge($this->DEFAULT_EMAIL_SETTINGS, $configuration["email_settings"]);
		$notification_templates = $configuration["notification_templates"];
		$template_info = $this->get_templatebycompany($company, $notification_templates, $function);

		$current_user = $company["current_user"];

		$to = $data['to'];
		$name = $data['company_name']; // $company["display_name"];
		$flag  = true;
		if(!SEND_EMAIL)
			return true;

		$this->load->library('parser');
        	
		$data = array_merge($template_info['default_data_structure'], $data);

		// $template = $this->parser->parse('templates/email/option1/booking_confirmation', $data, TRUE);
		$template = $this->parser->parse($template_info['file_path'], $data, TRUE);
		$template = $this->parser->conditionals($template, $data, TRUE);

		if(!SEND_SMS)
			return true;
			
		$msg=urlencode($template);
		$no="91".$to;
		$curl = curl_init();

		curl_setopt_array($curl, array(
			// CURLOPT_URL => "https://www.smsgateway.center/SMSApi/rest/send?userId=oxytra&password=Sneha@12356&senderId=OXYTRA&sendMethod=simpleMsg&msgType=text&mobile=".$no."&msg=".$msg."",
			// CURLOPT_URL => "https://www.smsgateway.center/SMSApi/rest/send?userId=oxytra&password=Sneha@12356&senderId=OXYTRA&sendMethod=simpleMsg&msgType=text&mobile=".$no."&msg=".$msg."",
			CURLOPT_URL => "https://www.smsgateway.center/SMSApi/rest/send?userId=travelmergers&password=Sumit@12356&senderId=TRAMER&sendMethod=simpleMsg&msgType=text&mobile=".$no."&msg=".$msg."",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
			"cache-control: no-cache"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) 
		{
			log_message("info", "ERROR : SMS sent to $to | $err");
		  	return $err;
		} 
		else 
		{
			log_message("info", "SMS sent to $to | $response");
		  	return $response;
		}
	}
	
	public function send_email($function, $subject, $company, $data) {
		$company = $this->get_companyinfo();
		$configuration = $company["configuration"];
		$email_settings = array_merge($this->DEFAULT_EMAIL_SETTINGS, $configuration["email_settings"]);
		$notification_templates = $configuration["notification_templates"];
		$template_info = $this->get_templatebycompany($company, $notification_templates, $function);

		$current_user = $company["current_user"];

		// $to = "majumdar.himadri@gmail.com"; // $data['to']; //"majumdar.himadri@gmail.com"; //$current_user["email"];
		// $cc = "majumdar.himadri@gmail.com"; //$current_user['email']; //"majumdar.himadri@gmail.com"; //$current_user["email"];
		$to = $data['to'];
		$cc = $data['cc'];
		$name = $data['company_name']; // $company["display_name"];
		$flag  = true;
		if(!SEND_EMAIL)
			return true;

		$this->load->library('parser');
        	
		$config['protocol'] = $email_settings["protocol"]; // "smtp";		
		$config['smtp_host'] = $email_settings["host"]; // "ssl://smtp.gmail.com";
		$config['smtp_port'] = $email_settings["port"]; // "465";
		$config['smtp_user'] = $email_settings["user"]; // "contact.merittree@gmail.com";
		$config['smtp_pass'] = $email_settings["password"]; // "hm280175";
		$config['charset'] = "utf-8";
		$config['mailtype'] = "html";     	
		$config['send_multipart'] = false;

		$this->load->library('email'); 
		$this->email->initialize($config);

		$this->email->set_header('MIME-Version', '1.0; charset=utf-8');
		$this->email->set_header('Content-type', 'text/html');
		$this->email->set_newline("\r\n");

		$data = array_merge($template_info['default_data_structure'], $data);

		// $template = $this->parser->parse('templates/email/option1/booking_confirmation', $data, TRUE);
		$template = $this->parser->parse($template_info['file_path'], $data, TRUE);
		$template = $this->parser->conditionals($template, $data, TRUE);

		// $this->email->from("contact.merittree@gmail.com",$name); 
		// $this->email->reply_to("contact.merittree@gmail.com",$name);
		$this->email->from($email_settings["user"],$name); 
		$this->email->reply_to($email_settings["user"],$name);
		$this->email->to($to);
		if($cc && $cc !== '') {
			$this->email->cc($cc);
		}
		$this->email->subject($subject);	
		$this->email->message($template);

		if ($this->email->send()) {
			log_message("info", "Email sent to $to");
			return true;
		}
		else {
			log_message("error", "ERROR => ".$this->email->print_debugger());
			return $this->email->print_debugger();
		}

		return $flag;
	}

	public function send($subject,$data)
	{	
		if(!SEND_EMAIL)
			return true;
        	
		 $config['protocol'] = "smtp";		
		 $config['smtp_host'] = "ssl://smtp.gmail.com";
		 $config['smtp_port'] = "465";		
		 $config['smtp_user'] = "noreply@oxytra.com";
		 $config['smtp_pass'] = "pwd@1712";
		 $config['charset']    = "utf-8";    
         $config['mailtype'] = "html";     	
		 $this->load->library('email',$config);
		 $this->email->set_header('MIME-Version', '1.0; charset=utf-8');
         $this->email->set_header('Content-type', 'text/html');
         $this->email->set_newline("\r\n");
		
		 $name =$data["name"];
		 $logo =$data["logo"];
		 $sender_email = $data["email"];		 
		 $msg = $data["msg"];	
		 $msg1 = $data["msg1"];
		 $msg2 = $data["msg2"];
		 
		$message='<html><body><table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" style="font-family:\'Open Sans\',Gill Sans,Arial,Helvetica,sans-serif;max-width:500px;color:#444444;text-align:left;background:#ffffff;border:1px solid #efefef"> 
					<tbody>
					<tr>
						<td bgcolor="#1a1a1a" background="https://ci4.googleusercontent.com/proxy/W2ALyHqtDi_l2MggkjvU3Kx_lXLpTmZgAIShkDbS_fywS0r1NS5timKZDvcG76_FjFp6pZXZ5xPFy7SFqaUZ8SiIYw=s0-d-e1-ft#http://teja1.Job4Artist.in/d1/images/mailerBG.gif" align="center" valign="top"> 
						<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center"> 
						<tbody> 
						<tr> 
							<td width="25"><img src="https://ci4.googleusercontent.com/proxy/ajI0iQ9AZlyADL8_9plu76lTu25FC1OpTY_tROnUtT6zLU8QdKG38VIPRyOdlIov1e6pgWZ2fFC565d84W9hl9M=s0-d-e1-ft#http://teja1.Job4Artist.in/d1/images/spacer.gif" width="8" height="1" vspace="0" align="left" class="CToWUd"></td>
							<td width="450" align="center"> 
							<table width="100%" border="0" cellspacing="0" cellpadding="0" style="text-align:center;font-size:20px;color:#444444;font-family:\'Open Sans\',Gill Sans,Arial,Helvetica,sans-serif"> 
							<tbody> <tr> <td height="20"></td>
						</tr>
						
						<tr> 
							<td align="center">
							<a href="#m_-4915400257058116905_"><img src="'.$logo.'" alt="" width="180" height="auto" border="0" style="margin:0;display:block;font-family:Arial,Helvetica,sans-serif;color:#007ebe;font-size:20px;text-align:center;font-weight:bold" class="CToWUd"></a> 
							</td>
						</tr>
						
						<tr> 
						<td height="20" style="border-bottom:1px solid #424649"></td>
						</tr>
						</tbody> 
					  </table> 
					  
					  <table border="0" cellspacing="0" cellpadding="0" style="font-family:\'Open Sans\',Gill Sans,Arial,Helvetica,sans-serif;color:#ffffff;text-align:center"> <tbody> <tr> <td height="20"></td></tr><tr> <td> <table width="100%" cellpadding="5" cellspacing="0" border="0" style="max-width:450px"> 
					  <tbody>
					  <tr> 
					  <td align="center" width="88" valign="top">
						<img src="https://ci3.googleusercontent.com/proxy/iGn4qvSkjqio9ZZtWraZHxDtcdGWXwp3dV4wsEKsxD2xvGybqW3-7oSijPd6lIrxxpjZm_bbgp-57yGbTl-oZijVqv6ulfSOQJ3ytTaAerc=s0-d-e1-ft#http://teja1.Job4Artist.in/d1/images/account-activation-ic.png" width="80" height="54" vspace="0" hspace="0" align="absmiddle" border="0" class="CToWUd">
					   </td>					
						<td valign="middle" style="font-size:18px;font-family:\'Open Sans\',Gill Sans,Arial,Helvetica,sans-serif;text-align:left"> '.$msg.' </td>
					  </tr>
					  </tbody>
					  </table> 
					  
					  </td>
					  </tr>
					  
					  <tr> 
					  <td height="20"></td>
					  </tr>
					  
					  <tr> 
					  <td align="center"> 
					  <table width="189" cellpadding="0" cellspacing="0" border="0" align="center">
					  <tbody>
					  <tr> 
					  <td align="center"> 
					  <a href="#" style="display:block;background:#ed3b12;border-radius:3px;color:#ffffff;text-decoration:none;font-size:16px;text-align:center;line-height:37px;font-family:\'Open Sans\',Gill Sans,Arial,Helvetica,sans-serif;padding:0 10px" target="_blank" data-saferedirecturl="https://www.google.com/url?hl=en&amp;q=http://www.Job4Artist.in/redirect.php?type%3DverifyEmail%26src%3Dregistration%26em%3D%252B4f8IzHnK%252F5DnDaMFXK7e%252BrLZTT0Y5Ra%26t%3D3rdZWYQH8PM8d8mxCUHEfw%253D%253D&amp;source=gmail&amp;ust=1527935657727000&amp;usg=AFQjCNFUmFtukAzsTBSNN17O8mmr-daNCw"></a>
					  </td>
					  </tr>
					  </tbody>
					  </table> 
					  
					  </td>
					  </tr>
					  
					  <tr> 
					  <td height="30"></td>
					  </tr>
					  </tbody> 
					  </table> 
					  </td>
					  
					  <td width="25">
					  <img src="https://ci4.googleusercontent.com/proxy/ajI0iQ9AZlyADL8_9plu76lTu25FC1OpTY_tROnUtT6zLU8QdKG38VIPRyOdlIov1e6pgWZ2fFC565d84W9hl9M=s0-d-e1-ft#http://teja1.Job4Artist.in/d1/images/spacer.gif" width="8" height="1" vspace="0" align="left" class="CToWUd"></td>
					  </tr>
					  </tbody> 
					  </table> 
					  </td>
					</tr>
					
					<tr>
					  <td>
						 <table width="100%" border="0" cellspacing="0" cellpadding="0"> 
						 <tbody> 
						 </tbody> 
						 </table>
					  </td>
					</tr>
																																																
				<tr> <td> <table width="100%" border="0" cellspacing="0" cellpadding="0"> <tbody>	
				<tr> 
				<td width="25">
				<img src="https://ci4.googleusercontent.com/proxy/ajI0iQ9AZlyADL8_9plu76lTu25FC1OpTY_tROnUtT6zLU8QdKG38VIPRyOdlIov1e6pgWZ2fFC565d84W9hl9M=s0-d-e1-ft#http://teja1.dazzlr.in/d1/images/spacer.gif" width="8" height="1" vspace="0" align="left" class="CToWUd">
				</td>
				<td width="450" valign="top"> 
				<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:\'Open Sans\',Gill Sans,Arial,Helvetica,sans-serif;max-width:500px;color:#666666;text-align:left;font-size:13px"> 
				<tbody> 
				<tr> 
				<td height="20"></td>
				</tr>


				
				<tr> 
				<td height="25">
				</td>
				</tr>
				<tr> 
				<td>'.$msg1.'</td></tr>

				<tr> <td height="15"></td></tr><tr> <td><strong style="color:#333333">'.$msg2.'</strong></td></tr>

				<tr> <td height="25"></td></tr><tr> <td>Best Regards, <br><strong style="color:#333333">The <span class="il">'.$name.'</span> Team</strong></td></tr></tbody> 
				</table> </td><td width="25">
				<img src="https://ci4.googleusercontent.com/proxy/ajI0iQ9AZlyADL8_9plu76lTu25FC1OpTY_tROnUtT6zLU8QdKG38VIPRyOdlIov1e6pgWZ2fFC565d84W9hl9M=s0-d-e1-ft#http://teja1.dazzlr.in/d1/images/spacer.gif" width="8" height="1" vspace="0" align="left" class="CToWUd">
				</td>			
				</tr>
				</tbody>
				</table>
				</td>
				</tr>
				
					</tbody>
					</table></body></html>';	
			 
		 $this->email->from("info@travelmergers.com",$name); 
		 $this->email->reply_to("info@travelmergers.com",$name);
		 $this->email->to($sender_email);		 
		 $this->email->subject($subject);	
		 $this->email->message($message);
		 
		 

		if ($this->email->send())
            return true;
        else 
           return $this->email->print_debugger();	   
	}

    public function adminsend($subject,$data)
	{     		
		if(!SEND_EMAIL)
			return true;

		 $config['protocol'] = "smtp";		
		 $config['smtp_host'] = "ssl://smtp.gmail.com";
		 $config['smtp_port'] = "465";		
		 $config['smtp_user'] = "noreply@oxytra.com";
		 $config['smtp_pass'] = "pwd@1712";
		 $config['charset']    = "utf-8";    
         $config['mailtype'] = "html";     			
         $this->email->set_newline("\r\n");
		
		 $name =$data["name"];
		 $sender_email = $data["email"];		 
		 $msg = $data["msg"];	
		 $msg1 = $data["msg1"];
		 $msg2 = $data["msg2"];
		 $msg=$msg."\n User ID : ".$data["user_id"]."\n Name : ".$name."\n Phone : ".$data["mobile"]."\n Email : ".$data["email"];
		  
		 $this->email->from('noreply@oxytra.com',$name); 
		 $this->email->reply_to($sender_email,$name);
		 $this->email->to("info@oxytra.com");		 
		 $this->email->subject($subject);	
		 $this->email->message($msg);
		          		 
		if ($this->email->send())
            return true;
        else 
           return $this->email->print_debugger();	   
	}

    public function send_message($no,$msg)
	{
		if(!SEND_SMS)
			return true;
			
		  $msg=urlencode($msg);
		  $no="91".$no;
		  $curl = curl_init();
		  curl_setopt_array($curl, array(
		//   CURLOPT_URL => "https://www.smsgateway.center/SMSApi/rest/send?userId=oxytra&password=Sumit@12356&senderId=OXYTRA&sendMethod=simpleMsg&msgType=text&mobile=".$no."&msg=".$msg."",
		  // CURLOPT_URL => "https://www.smsgateway.center/SMSApi/rest/send?userId=oxytra&password=Sneha@12356&senderId=OXYTRA&sendMethod=simpleMsg&msgType=text&mobile=".$no."&msg=".$msg."",
		  CURLOPT_URL => "https://www.smsgateway.center/SMSApi/rest/send?userId=travelmergers&password=Sumit@12356&senderId=TRAMER&sendMethod=simpleMsg&msgType=text&mobile=".$no."&msg=".$msg."",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
			"cache-control: no-cache"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) 
		{
		  return $err;
		} 
		else 
		{
		  return $response;
		}
   	}

   	public function getMyWallet() {
		$companyid = $this->session->userdata("current_user")["companyid"];
		$current_user = $this->session->userdata("current_user");
		$wallet = NULL;

		if(intval($this->session->userdata('user_id'))>0 && intval($current_user['is_admin'])!==1) {
			$mywallet = $this->Search_Model->getMyWallet($this->session->userdata('user_id'), -1);
			if($mywallet && count($mywallet)>0) {
				//$result["mywallet"] = $mywallet[0];
				$wallet = $mywallet[0];
			}
			else {
				//$result["mywallet"] = array('balance' => 0);
				$wallet = array('balance' => 0);
			}
		}
		else if(intval($companyid)>0) {
			$mywallet = $this->Search_Model->getMyWallet(-1, $companyid);
			
			if($mywallet && count($mywallet)>0) {
				$wallet = $mywallet[0];
			}
			else {
				$wallet = array('balance' => 0);
			}
		}
		else {
			$wallet = array('balance' => 0);
		}

		if(!$wallet) {
			//$result["mywallet"] = array('balance' => 0);
			$wallet = array('balance' => 0);
		}	

		return $wallet;
	}

	public function getHash($posted, $hashSequence, $salt) {
		$hashVarsSeq = explode('|', $hashSequence);
		$hash_string = '';	
		foreach($hashVarsSeq as $hash_var) {
		  $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
		  $hash_string .= '|';
		}
	
		$hash_string .= $salt;
	
	
		$hash = strtolower(hash('sha512', $hash_string));	

		return $hash;
	}

	public function get_templatebycompany($company, $companytemplates, $function) {
		$templates = $this->getTemplates();
		$templateid = -1;
		$template_info = array();
		log_message('info', "Finding configured template for function: $function | company name: ".$company["display_name"]." | templates: ".json_encode($companytemplates));
		try
		{
			for ($i=0; $i < count($companytemplates); $i++) { 
				$template = $companytemplates[$i];
				if($template && $template['name'] == $function) {
					$templateid = intval($template['id']);
					break;
				}
			}
			log_message('info', "Configured template id for $function is $templateid");

			for ($i=0; $i < count($templates); $i++) { 
				$template = $templates[$i];
				if($templateid>-1) {
					//company specific template is being set for this functional area
					if($template && intval($template['id']) == $templateid) {	
						$template_info = $template;
						break;
					}
				}
				else {
					if($template && $template['name'] == $function) {
						$template_info = $template;
						break;
					}
				}
			}

			log_message('info', "Identified template : ".json_encode($template_info));
		}
		catch(Exception $ex) {
			log_message('error', $ex);
		}

		return $template_info;
	}

	public function getTemplates() {
		
	}

	protected function get_companyinfo()
	{
		$company = $this->session->userdata('company');
		$companyinfo = &$company;
		$companyinfo['configuration'] = json_decode($company['setting']['configuration'], true);
		$companyinfo['account_settings'] = isset($companyinfo['configuration']['account_settings'])?$companyinfo['configuration']['account_settings']:array();
		$company_account_settings = $companyinfo['account_settings'];
		
		$ticket_account = null;
		if(count($company_account_settings)>0) {
			for($idx=0; $idx<count($company_account_settings); $idx++) {
				if($company_account_settings[$idx]['module']=='ticket_sale') {
					$ticket_account = $company_account_settings[$idx];
					break;
				}
			}
		}
		$companyinfo['ticket_sale_account'] = $ticket_account;
		$companyinfo['current_user'] = $this->session->userdata('current_user');

		log_message("info", "Company Info : ".json_encode($companyinfo));

		return $companyinfo;
	}

	public function getCurrentCompany() {
		$companies = $this->Admin_Model->get_companies();
		$company = null;
		$siteUrl = siteURL();

		for($idx=0; $idx<count($companies); $idx++) {
			if(strtolower($companies[$idx]["baseurl"]) === strtolower($siteUrl)) {
				$company = $companies[$idx];
				log_message('debug', "Get current company => ".json_encode($company, false));
				break;
			}
		}

		return $company;
	}

	public function getPG_payload_payu($input_post) {
		$payload = [];
		$selected_pg = $input_post['selected_pg'];
		$selected_convrates = $selected_pg['conv_rate'];
		$payment_type = $input_post['payment_type'];
		$amount = $input_post['amount'];
		$company = $input_post['company'];
		$current_user = $input_post['current_user'];
		$return_url = isset($input_post['return_url']) ? $input_post['return_url'] : base_url().'paymentgateway/response';

		$conv_rateobj = false;
		$conv_rate = 0.00;

		$final_amount = $amount;

		for ($i=0; $selected_convrates && $i<count($selected_convrates); $i++) {
			$conv_rateobj = $selected_convrates[$i];
			if($conv_rateobj['payment_mode']==$payment_type) {
				break;
			}
		}

		if($conv_rateobj) {
			if($conv_rateobj['rate_type']=='percentage') {
				$conv_rate = $amount * floatval($conv_rateobj['conv_rate']);
				$final_amount = round($final_amount + $conv_rate, 2);
			} else {
				$conv_rate = floatval($conv_rateobj['conv_rate']);
				$final_amount = round($final_amount + $conv_rate, 2);
			}
		}

		// Hash Sequence
		//sha512        (key|txnid|amount|      productinfo|firstname|email|udf1        |udf2|udf3|udf4|udf5|    |    |    |    |     |SALT)
		//				 key|txnid|amount      |productinfo|firstname|email|udf1        |udf2|udf3|udf4|udf5|    |    |    |    |     |SALT
		$hashSequence = "key|txnid|final_amount|productinfo|firstname|email|payment_mode|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";

		$salt = $selected_pg['configuration']['salt'];
		$name_array = explode(" ", $current_user['name']);

		$payload['txnid'] = uniqid();
		$payload['final_amount'] = $final_amount;
		$payload['key'] = $selected_pg['configuration']['marchent_key'];
		$payload['firstname'] = ($name_array && count($name_array)>0) ? $name_array[0] : '';
		$payload['email'] = $current_user['email'];
		$payload['phone'] = $current_user['mobile'];
		$payload['productinfo'] = 'Payment for B2B';
		$payload['surl'] = $return_url; // base_url().'paymentgateway/response';
		$payload['furl'] = $return_url; // base_url().'paymentgateway/response';
		$payload['lastname'] = ($name_array && count($name_array)>1) ? $name_array[1] : '';
		$payload['curl'] = $return_url; // base_url().'paymentgateway/response';
		$payload['address1'] = 'Kolkata';
		$payload['address2'] = 'Kolkata';
		$payload['city'] = 'Kolkata';
		$payload['state'] = 'West Bengal';
		$payload['country'] = 'IN';
		$payload['zipcode'] = '700084';
		$payload['payment_mode'] = $payment_type==5 ? 'CC' : ($payment_type==6 ? 'DC' : ($payment_type==6 ? 'NB' : ''));
		$payload['bankcode'] = '';
		$payload['enforce_paymethod'] = $payment_type==5 ? 'creditcard' : ($payment_type==6 ? 'debitcard' : ($payment_type==6 ? 'netbanking' : ''));
		$payload['hash'] = $this->getHash($payload, $hashSequence, $salt);

		return $payload;
	}

	public function getPG_payload_atom($input_post) {
		$payload = [];
		$selected_pg = $input_post['selected_pg'];
		$selected_convrates = $selected_pg['conv_rate'];
		$payment_type = $input_post['payment_type'];
		$amount = $input_post['amount'];
		$company = $input_post['company'];
		$current_user = $input_post['current_user'];

		$return_url = isset($input_post['return_url']) ? $input_post['return_url'] : base_url().'paymentgateway/response_atom';
		$conv_rateobj = false;
		$conv_rate = 0.00;

		$final_amount = $amount;

		for ($i=0; $payment_type>-1 && $selected_convrates && $i<count($selected_convrates); $i++) {
			$conv_rateobj = $selected_convrates[$i];
			if($conv_rateobj['payment_mode']==$payment_type) {
				break;
			}
		}

		if($conv_rateobj) {
			if($conv_rateobj['rate_type']=='percentage') {
				$conv_rate = $amount * floatval($conv_rateobj['conv_rate']);
				$final_amount = round($final_amount + $conv_rate, 2);
			} else {
				$conv_rate = floatval($conv_rateobj['conv_rate']);
				$final_amount = round($final_amount + $conv_rate, 2);
			}
		}

		$salt = $selected_pg['configuration']['salt'];
		$marchant_key = $selected_pg['configuration']['marchent_key'];
		$login = $selected_pg['configuration']['login'];
		$password = $selected_pg['configuration']['password'];

		$name_array = explode(" ", $current_user['name']);

		$payload['txnid'] = uniqid();
		//$payload['txndate'] = date("Y-m-d H:i:s");
		$payload['txndate'] = date("m/d/Y H:i:s");
		$payload['mode'] = 'test'; //This will be live at later stage
		$payload['login'] = $login;
		$payload['password'] = $password;
		//$payload['productinfo'] = 'Add amount to wallet';
		$payload['productinfo'] = 'NSE'; //This is a testing product. This would be changed to actual product at later stage
		$payload['amount'] = $final_amount;
		$payload['transaction_amount'] = $final_amount;
		$payload['final_amount'] = $final_amount;
		$payload['currency'] = 'INR';
		$payload['return_url'] = $return_url;  // base_url().'paymentgateway/response_atom';
		$payload['client_code'] = intval($current_user['id']);
		$payload['salt'] = $salt;
		$payload['key'] = $marchant_key;

		$payload['name'] = (($name_array && count($name_array)>0) ? $name_array[0] : '').' '.(($name_array && count($name_array)>1) ? $name_array[1] : '');
		$payload['email'] = $current_user['email'];
		$payload['mobile'] = $current_user['mobile'];

		$payload['billing_city'] = isset($current_user['address']) ? $current_user['address'] : 'Kolkata';
		$payload['customer_account'] = '639827';
		// $payload['surl'] = base_url().'paymentgateway/response';
		// $payload['furl'] = base_url().'paymentgateway/response';
		// $payload['lastname'] = ($name_array && count($name_array)>1) ? $name_array[1] : '';
		// $payload['rurl'] = base_url().'paymentgateway/response';
		// $payload['address1'] = 'Kolkata';
		// $payload['address2'] = 'Kolkata';
		// $payload['city'] = 'Kolkata';
		// $payload['state'] = 'West Bengal';
		// $payload['country'] = 'IN';
		// $payload['zipcode'] = '700084';
		// $payload['payment_mode'] = $payment_type==5 ? 'CC' : ($payment_type==6 ? 'DC' : ($payment_type==6 ? 'NB' : ''));
		// $payload['bankcode'] = '';
		// $payload['enforce_paymethod'] = $payment_type==5 ? 'creditcard' : ($payment_type==6 ? 'debitcard' : ($payment_type==6 ? 'netbanking' : ''));
		// $payload['hash'] = $this->getHash($payload, $hashSequence, $salt);

		return $payload;
	}

	public function getPGViewName($pgname = '') {
		if($pgname === 'PayU') {
			return 'payu_pw';
		}
		else if($pgname === 'Atom') {
			return 'pw_atom';
		}
		else {
			return false;
		}
	}

	public function process_pg_response($payload) {
		$pg_transid = $payload['mihpayid'];
		$mode = $payload['mode'];
		$status = $payload['status'];
		$txnid = $payload['txnid'];
		$amount = $payload['amount'];
		$card_category = $payload['card_category'];
		$trans_date = $payload['trans_date'];
		$error = $payload['error'];
		$message = $payload['message'];
		$response_json = $payload['response_json'];
		$billed_amount = 0;

		$pg_transaction = $this->User_Model->get_where('pg_transactions_tbl', array('trans_tracking_id' => $txnid));

		$company = $this->session->userdata('company');
		$user_id = $this->session->userdata('user_id');
		$current_user = $this->session->userdata('current_user');
		$companyid = $current_user["companyid"];
		$companyname = $company['display_name'];
		$result["company_setting"]=$this->Search_Model->company_setting($companyid);

		$walletid = $current_user['wallet_id'];
		$wallet_balance = $current_user['wallet_balance'];
		$sponsoring_companyid = $current_user['sponsoring_companyid'];

		$wallet = $this->User_Model->get_where('system_wallets_tbl', array('id' => $walletid));
		if($wallet && count($wallet)>0) {
			$wallet_balance = floatval($wallet[0]['balance']);

			if(isset($result['company_setting']) && isset($result['company_setting'][0]['payment_gateway'])) {
				$result['company_setting'][0]['payment_gateway'] = json_decode($result['company_setting'][0]['payment_gateway'], true);
			}
			if(isset($result['company_setting']) && isset($result['company_setting'][0]['bank_accounts'])) {
				$result['company_setting'][0]['bank_accounts'] = json_decode($result['company_setting'][0]['bank_accounts'], true);
			}
			if(isset($result['company_setting']) && isset($result['company_setting'][0]['configuration'])) {
				$result['company_setting'][0]['configuration'] = json_decode($result['company_setting'][0]['configuration'], true);
			}
			if(isset($result['company_setting']) && isset($result['company_setting'][0]['api_integration'])) {
				$result['company_setting'][0]['api_integration'] = json_decode($result['company_setting'][0]['api_integration'], true);
			}

			$payload['company'] = $company;
			$payload['current_user'] = $current_user;
			$payload['company_setting'] = $result["company_setting"];
			$payload['wallet'] = $wallet[0];
			$payload['wallet_balance'] = $wallet_balance;
		}
		if($pg_transaction && count($pg_transaction)>0) {
			$wallet_trans_id = -1;
			if($status === 'success') {
				$billed_amount = floatval($pg_transaction[0]['amount']);
				$wallet_trans_id = $this->User_Model->save("wallet_transaction_tbl", 
					array(
						'wallet_id' => $walletid, 
						'date' => date("Y-m-d H:i:s"),
						'trans_id' => $txnid,
						'companyid' => $companyid,
						'userid' => $user_id,
						'amount' => $billed_amount,
						'dr_cr_type' => 'CR',
						'status' => 1,
						'trans_type' => $pg_transaction[0]['payment_mode'],
						'trans_ref_id' => $pg_transid,
						'trans_ref_date' => $trans_date,
						'target_companyid' => $sponsoring_companyid,
						'trans_ref_type' => 'PAYMENT',
						'narration' => 'Deposited via online payment gateway for online booking (system generated message)',
						'sponsoring_companyid' => $sponsoring_companyid,
						'created_by' => $user_id
					)
				);

				$return = $this->User_Model->update_table_data('system_wallets_tbl', array('id' => $walletid), array('balance' => ($wallet_balance + $billed_amount)));

				$payload['transacting_amount'] = $billed_amount;
				$payload['wallet_balance'] = ($wallet_balance + $billed_amount);
				$payload['wallet_trans_id'] = $wallet_trans_id;
			}

			$return = $this->User_Model->update_table_data('pg_transactions_tbl', 
					array('id' => $pg_transaction[0]['id'], 'trans_tracking_id' => $txnid), 
					array('error' => $error, 'message' => $message, 'response_data' => $response_json, 'response_status' => $status, 
						'pg_transactionid' => $pg_transid, 'wallet_transactionid' => $wallet_trans_id, 'updated_by' => $user_id, 'updated_on' => date("Y-m-d H:i:s")));
			
			$payload['transaction_updated'] = $amount;
			$payload['pg_transaction_id'] = intval($pg_transaction[0]['id']);
			//redirect('/user');
		}
		else {
			$payload['wallet_trans_id'] = -1;
			$payload['pg_transaction_id'] = -1;
			$payload['transacting_amount'] = $billed_amount;
			$payload['wallet_balance'] = ($wallet_balance + $billed_amount);
			$payload['transaction_updated'] = false;
		}

		return $payload;
	}

	function isCreditAllowed($current_wallet_balance, $amount, $current_user) {
		
		$bflag = ($current_user && isset($current_user['credit_ac']) && boolval($current_user['credit_ac']) === true);

		$bflag &= ($current_wallet_balance > -1000000); //max 10 lacks limit

		return $bflag;
	}
}
?>