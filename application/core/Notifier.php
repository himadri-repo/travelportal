<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Notifier {
    var $DEFAULT_EMAIL_SETTINGS = array(
        "protocol" => "",
        "host" => "",
        "port" => "",
        "user" => "",
        "password" => "",
    );    

    function send_sms($function, $subject, $company, $data) {
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
    
    function send_email($function, $subject, $company, $data) {
        //$company = $this->get_companyinfo();
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
}

?>