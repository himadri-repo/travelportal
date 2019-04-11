<?php
include_once('../../config.php');
if(isset($_POST["tag"]))
{
	$tag=$_POST["tag"];
	$response=array();
	switch($tag)
	{
		case "login":
		            $login_id=mysql_real_escape_string(trim($_POST['login_id']));
					$password=mysql_real_escape_string(trim($_POST['password']));
					
		            $sql="SELECT * FROM admin_tbl WHERE user_name='$login_id'";
					$rs1=mysql_query($sql);
					if(mysql_num_rows($rs1)==0)
					{
						$response["error1"]="Wrong User Name";	
					}
					
					$sql="SELECT * FROM admin_tbl WHERE user_name='$login_id' AND  password='".$password."'";
					$rs2=mysql_query($sql);
					if(mysql_num_rows($rs2)==0)
					{
					    if(empty($response["error1"]))
							$response["error2"]="Wrong Password";	
					}
					if(mysql_num_rows($rs1)>0 && mysql_num_rows($rs2)>0)
					{
					    $row=mysql_fetch_array($rs1);						
						$_SESSION["oxytra_admin_id"]=$row['admin_id'];	
						$_SESSION["admin_name"]=$row['name'];
						$_SESSION["authority"]=$row['authority'];
						$_SESSION["admin_login"]="loggedin";
						$_SESSION["admin_loggedin_time"]= time(); 
                        $response["success"]="Login Successful";
					}		
		break;	
	}
	
}
echo json_encode($response);

?>