<?php
include_once('../../config.php');
if(isset($_POST["tag"]))
{
	$tag=$_POST["tag"];
	$response=array();
	switch($tag)
	{
		case "logout":
		           
					unset($_SESSION['flykets_admin_id']);	
					unset($_SESSION['admin_loggedin_time']);
					unset($_SESSION['admin_loggedin_time']);
					session_destroy();
					$response["logout"]="Logout Successfully";
		break;	
		
		

	}
	
}
echo json_encode($response);

?>