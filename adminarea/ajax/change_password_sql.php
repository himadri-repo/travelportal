<?php
include_once('../../config.php');
if(isset($_POST["tag"]))
{
	$tag=$_POST["tag"];
	$response=array();
	switch($tag)
	{
        case "change":
		            $old_password=$_POST['txt_old_password'];					
		            $password=$_POST['txt_password'];										
					$sql="SELECT * FROM admin_tbl  WHERE password='$old_password' AND admin_id='".$_SESSION['oxytra_admin_id']."'";
					$result=mysql_query($sql);	
					if(mysql_num_rows($result)>0)
					{						
                        $sql="UPDATE admin_tbl SET password='$password' WHERE admin_id='".$_SESSION['oxytra_admin_id']."'";
						$result=mysql_query($sql);	
						if($result)
						{ 
						    unset($_SESSION['admin_name']);
							unset($_SESSION['oxytra_admin_id']);
							unset($_SESSION['admin_login']);
							unset($_SESSION['admin_loggedin_time']);
							 unset($_SESSION['authority']);
							 unset($_SESSION['admin_name']);
							session_destroy();							
							$response["success"]="Password Changed Successfully";	
							
						}							
					}	
					else
                       	$response["error"]="Please Enter Correct Password";		
		break;				
	}
	
}
echo json_encode($response);
?>