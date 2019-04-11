<?php
include_once('../../config.php');
if(isset($_POST["tag"]))
{
	$tag=$_POST["tag"];
	$response=array();
	switch($tag)
	{
		case "get_city": 
						$id=$_POST['id'];					
						$sql="SELECT * FROM city_tbl  WHERE country='$id'";
						$result=mysql_query($sql);
						if(mysql_num_rows($result)>0)
						{
							while($row=mysql_fetch_array($result))
							{
								array_push($response,$row);
							}
						}
						else
						{
						  $response['no_records']=TRUE;
						}
						
		break;
		case "get_subcategory": 
						$id=$_POST['id'];					
						$sql="SELECT * FROM sub_category_tbl  WHERE category='$id'";
						$result=mysql_query($sql);
						if(mysql_num_rows($result)>0)
						{
							while($row=mysql_fetch_array($result))
							{
								array_push($response,$row);
							}
						}
						else
						{
						  $response['no_records']=TRUE;
						}
						
		break;
		case "check_uniqueness": 
						$mobile_no=$_POST['mobile_no'];	
                        $phone_no=$_POST['phone_no'];
                        $email=$_POST['email'];						
						$sql1="SELECT * FROM seller_tbl  WHERE mobile_no='$mobile_no'";
						$sql2="SELECT * FROM seller_tbl  WHERE phone_no='$phone_no'";
						$sql3="SELECT * FROM seller_tbl  WHERE email='$email'";
						
						$result1=mysql_query($sql1);
						$result2=mysql_query($sql2);
						$result3=mysql_query($sql3);
						
						if(mysql_num_rows($result3)>0)
						{
							 $response['email']="This Email already Exist";
						}
						else if(mysql_num_rows($result1)>0)
						{
							 $response['mobile']="This Mobile No. already Exist";
						}
						else if(mysql_num_rows($result2)>0)
						{
							 $response['phone']="This Phone No. already Exist";
						}						
						else
						{
						  $response['no_records']=TRUE;
						}
						
		break;
		
		case "check_uniqueness_buyer": 
						$mobile_no=$_POST['mobile_no'];	                      
                        $email=$_POST['email'];						
						$sql1="SELECT * FROM buyer_tbl  WHERE mobile_no='$mobile_no'";						
						$sql2="SELECT * FROM buyer_tbl  WHERE email='$email'";
						
						$result1=mysql_query($sql1);
						$result2=mysql_query($sql2);
					
						
						if(mysql_num_rows($result2)>0)
						{
							 $response['email']="This Email already Exist";
						}
						else if(mysql_num_rows($result1)>0)
						{
							 $response['mobile']="This Mobile No. already Exist";
						}
											
						else
						{
						  $response['no_records']=TRUE;
						}
						
		break;
		
		case "check_uniqueness1": 
						$mobile_no=$_POST['mobile_no'];	
                        $phone_no=$_POST['phone_no'];
                        $email=$_POST['email'];						
						$sql1="SELECT * FROM seller_tbl  WHERE mobile_no='$mobile_no' AND id!='".$_SESSION['flykets_seller_id']."'";
						$sql2="SELECT * FROM seller_tbl  WHERE phone_no='$phone_no' AND id!='".$_SESSION['flykets_seller_id']."'";
						$sql3="SELECT * FROM seller_tbl  WHERE email='$email' AND id!='".$_SESSION['flykets_seller_id']."'";
						
						$result1=mysql_query($sql1);
						$result2=mysql_query($sql2);
						$result3=mysql_query($sql3);
						
						if(mysql_num_rows($result3)>0)
						{
							 $response['email']="This Email already Exist";
						}
						else if(mysql_num_rows($result1)>0)
						{
							 $response['mobile']="This Mobile No. already Exist";
						}
						else if(mysql_num_rows($result2)>0)
						{
							 $response['phone']="This Phone No. already Exist";
						}						
						else
						{
						  $response['no_records']=TRUE;
						}
						
		break;
		
		case "check_uniqueness2": 
						$mobile_no=$_POST['mobile_no'];	                       
                        $email=$_POST['email'];						
						$sql1="SELECT * FROM buyer_tbl  WHERE mobile_no='$mobile_no' AND id!='".$_SESSION['flykets_buyer_id']."'";						
						$sql2="SELECT * FROM buyer_tbl  WHERE email='$email' AND id!='".$_SESSION['flykets_buyer_id']."'";
						
						$result1=mysql_query($sql1);
						$result2=mysql_query($sql2);	
						
						if(mysql_num_rows($result2)>0)
						{
							 $response['email']="This Email already Exist";
						}
						else if(mysql_num_rows($result1)>0)
						{
							 $response['mobile']="This Mobile No. already Exist";
						}
											
						else
						{
						  $response['no_records']=TRUE;
						}
						
		break;
		
		case "check_uniqueness_buyer1": 
						$mobile_no=$_POST['mobile_no'];	                      
                        $email=$_POST['email'];						
						$sql1="SELECT * FROM buyer_tbl  WHERE mobile_no='$mobile_no' AND id!='".$_SESSION['flykets_buyer_id']."'";					
						$sql2="SELECT * FROM buyer_tbl  WHERE email='$email' AND id!='".$_SESSION['flykets_buyer_id']."'";
						
						$result1=mysql_query($sql1);
						$result2=mysql_query($sql2);
						
						
						if(mysql_num_rows($result2)>0)
						{
							 $response['email']="This Email already Exist";
						}
						else if(mysql_num_rows($result1)>0)
						{
							 $response['mobile']="This Mobile No. already Exist";
						}
											
						else
						{
						  $response['no_records']=TRUE;
						}
						
		break;
		case "check_password": 
						$password=md5($_POST['password']);	                        				
						$sql="SELECT * FROM seller_tbl  WHERE password='$password' AND id='".$_SESSION['flykets_user_id']."'";						
						$result=mysql_query($sql);						
						if(mysql_num_rows($result)==0)
						{
							 $response['error']="You have entered wrong password";
						}
										
						else
						{
						  $response['success']=TRUE;
						}
						
		break;
		case "check_password1": 
						$password=md5($_POST['password']);	                        				
						$sql="SELECT * FROM buyer_tbl  WHERE password='$password' AND id='".$_SESSION['flykets_buyer_id']."'";						
						$result=mysql_query($sql);						
						if(mysql_num_rows($result)==0)
						{
							 $response['error']="You have entered wrong password";
						}
										
						else
						{
						  $response['success']=TRUE;
						}
						
		break;
		case "change": 						                        				
						$password=md5($_POST['password']);						
						$sql="UPDATE  seller_tbl SET  password='$password' WHERE id='".$_SESSION['flykets_seller_id']."'";																									
						if(mysql_query($sql))
						{
						  $response['success']=TRUE;
						}
						
		break;
		
		case "change1": 						                        				
						$password=md5($_POST['password']);						
						$sql="UPDATE  buyer_tbl SET  password='$password' WHERE id='".$_SESSION['flykets_buyer_id']."'";																									
						if(mysql_query($sql))
						{
						  $response['success']=TRUE;
						}
						
		break;
		
		
		case "register":
		        $company_name=$_POST['company_name'];
				$logo=$_POST['logo'];
				$type=$_POST['type'];
				$email=$_POST['email'];
				$website=$_POST['website'];
				$mobile_no=$_POST['mobile_no'];
				$phone_no=$_POST['phone_no'];	
				$country=$_POST['country'];
				$city=$_POST['city'];
				$pin=$_POST['pin'];
				$acc_no=$_POST['acc_no'];
				$bank_name=$_POST['bank_name'];
				$ifsc_code=$_POST['ifsc_code'];
				$gst_no=$_POST['gst_no'];
				$tin_no=$_POST['tin_no'];
				$address=$_POST['address'];
				$password=md5($_POST['password']);
				$dt=new DateTime();
				$doj=$dt->format('Y-m-d h:i:s');
				
				
					$sql="INSERT INTO seller_tbl
					(`id`,`name`, `logo`, `mobile_no`, `email`,`phone_no`,`website`, `type`, `country`, `city`, `pin`, `address`, `status`, `password`, `doj`,acc_no,bank_name,ifsc_code,gst_no,tin_no) VALUES 
					('', '$company_name', '$logo', '$mobile_no', '$email','$phone_no', '$website', '$type', '$country', '$city', '$pin', '$address', '0', '$password', '$doj','$acc_no','$bank_name','$ifsc_code','$gst_no','$tin_no')";
					if(mysql_query($sql))
					{
						$response['success']=true;
                        del_unsaved_file();						
					}
				
		break;
		
		case "buyer_register":
		        $name=$_POST['name'];			
				$email=$_POST['email'];			
				$mobile_no=$_POST['mobile_no'];				
				$country=$_POST['country'];
				$city=$_POST['city'];
				$pin=$_POST['pin'];			
				$address=$_POST['address'];
				$password=md5($_POST['password']);
				$dt=new DateTime();
				$doj=$dt->format('Y-m-d h:i:s');
				
				
					$sql="INSERT INTO buyer_tbl
					(`id`,`name`, `mobile_no`, `email`,`country`, `city`, `pin`, `address`, `status`, `password`, `doj`)VALUES
					('', '$name', '$mobile_no','$email','$country', '$city', '$pin', '$address', '0', '$password', '$doj')";
					if(mysql_query($sql))
					{
						$response['success']=true;
                       					
					}
				
		break;
		
		case "update":
		        $company_name=$_POST['company_name'];
				$logo=$_POST['logo'];
				$type=$_POST['type'];
				$email=$_POST['email'];
				$website=$_POST['website'];
				$mobile_no=$_POST['mobile_no'];
				$phone_no=$_POST['phone_no'];	
				$country=$_POST['country'];
				$city=$_POST['city'];
				$pin=$_POST['pin'];
				$acc_no=$_POST['acc_no'];
				$bank_name=$_POST['bank_name'];
				$ifsc_code=$_POST['ifsc_code'];
				$gst_no=$_POST['gst_no'];
				$tin_no=$_POST['tin_no'];
				$address=$_POST['address'];
				
				
				
				$sql="UPDATE  seller_tbl SET name='$company_name',logo='$logo',type='$type',email='$email',
				website='$website',mobile_no='$mobile_no',phone_no='$phone_no',country='$country',city='$city',address='$address',
				acc_no='$acc_no',bank_name='$bank_name',ifsc_code='$ifsc_code',gst_no='$gst_no',tin_no='$tin_no'
				WHERE id='".$_SESSION['flykets_seller_id']."'";
				if(mysql_query($sql))
				{
					$response['success']=true;
					del_unsaved_file();						
				}
				
		break;
		
		case "buyer_update":
		        $name=$_POST['name'];			
				$email=$_POST['email'];				
				$mobile_no=$_POST['mobile_no'];			
				$country=$_POST['country'];
				$city=$_POST['city'];
				$pin=$_POST['pin'];				
				$address=$_POST['address'];								
				
				$sql="UPDATE  buyer_tbl SET name='$name',email='$email',mobile_no='$mobile_no',country='$country',city='$city',address='$address'
				WHERE id='".$_SESSION['flykets_buyer_id']."'";
				if(mysql_query($sql))
				{
					$response['success']=true;
									
				}
				
		break;
		
		
	}
}
function del_unsaved_file()
{
		$sql="SELECT logo FROM seller_tbl";
		$result=mysql_query($sql);
		$image_arr=array();
		$pre=array();
		while($row=mysql_fetch_array($result))
		{
			$image_arr[]=$row['logo'];
		}
		$files=glob('../upload/*');	
		foreach($files as $file)
		{
			$del_file=explode("/",$file);
			$del=end($del_file);
			$pre=explode("_",$del);
			$prefix=$pre[0];
			if($prefix=='logo')
			{
				if(!in_array($del,$image_arr))
				{
					if(is_file('../upload/'.$del))
						unlink('../upload/'.$del);
					if(is_file('../upload/thumb/'.$del))
						unlink('../upload/thumb/'.$del);	
					
				}
			}
		}				
}
echo json_encode($response);	
?>