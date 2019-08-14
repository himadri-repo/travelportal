<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');
class Mail_Controller  extends CI_Controller 
{
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
							<a href="#m_-4915400257058116905_"><img src="https://yourwebsite.co.in/oxytra/upload/logo.png" alt="OXTRA" width="180" height="auto" border="0" style="margin:0;display:block;font-family:Arial,Helvetica,sans-serif;color:#007ebe;font-size:20px;text-align:center;font-weight:bold" class="CToWUd"></a> 
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

				<tr> <td height="25"></td></tr><tr> <td>Best Regards, <br><strong style="color:#333333">The <span class="il">OXYTRA</span> Team</strong></td></tr></tbody> 
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
			 
		 $this->email->from("noreply@oxytra.com",$name); 
		 $this->email->reply_to("noreply@oxytra.com",$name);
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
		  CURLOPT_URL => "https://www.smsgateway.center/SMSApi/rest/send?userId=oxytra&password=Sumit@12356&senderId=OXYTRA&sendMethod=simpleMsg&msgType=text&mobile=".$no."&msg=".$msg."",
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

		if($this->session->userdata('user_id') && $current_user['is_admin']!=='1') {
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
		else if($companyid) {
			$mywallet = $this->Search_Model->getMyWallet(-1, $companyid);
			
			if($mywallet && count($mywallet)>0) {
				$mywallet=$this->Search_Model->getMyWallet(-1, $companyid);
				if($mywallet && count($mywallet)>0) {
					//$result["mywallet"] = $mywallet[0];
					$wallet = $mywallet[0];
				}
				else {
					//$result["mywallet"] = array('balance' => 0);
					$wallet = array('balance' => 0);
				}
			}
			else {
				//$result["mywallet"] = array('balance' => 0);
				$wallet = array('balance' => 0);
			}
		}
		else {
			//$result["mywallet"] = array('balance' => 0);
			$wallet = array('balance' => 0);
		}

		if(!$wallet) {
			//$result["mywallet"] = array('balance' => 0);
			$wallet = array('balance' => 0);
		}	

		return $wallet;
	}
}
?>