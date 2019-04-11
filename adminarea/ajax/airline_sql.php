<?php
include_once('../../config.php');
$dt=new DateTime();

if(isset($_POST["tag"]))
{
	$tag=$_POST["tag"];
	$response=array();
	switch($tag)
	{
        case "search":
					$start=$_POST['start'];
					$s=trim($_POST['s']);
					$start=$start*20;
					$limit=20;
					$total=0;
					$sql="SELECT * FROM airline_tbl WHERE airline LIKE '%$s%'";
					$result=mysql_query($sql);
					if(mysql_num_rows($result)>0)
					{
						$ctr=ceil(mysql_num_rows($result)/$limit);
						$total=mysql_num_rows($result);
						$sql="SELECT * FROM airline_tbl WHERE  airline LIKE '%$s%'  order by id desc LIMIT $start,$limit";			
						$result=mysql_query($sql);
						$i=0;
						while($row=mysql_fetch_array($result,MYSQL_ASSOC))
						{
						 foreach($row as $k=>$v)
						  {						     							 
								$response[$i][$k]=$v;                              								
						  } 
							$i++;
						 
						}
						  $response['records']=$ctr;
						  $response['total']=$total;						  
					}
					else
					{
					  $response['no_records']="true";
					}
		break;

        

        case "insert":
		            $airline=mysql_real_escape_string(trim($_POST['airline']));
					$aircode=mysql_real_escape_string(trim($_POST['aircode']));
					$image=mysql_real_escape_string(trim($_POST['image']));
					
					$sql_check="SELECT * FROM airline_tbl WHERE airline='$airline'";
					$result_check=mysql_query($sql_check);
					if(mysql_num_rows($result_check)==0)
					{          
					          $sql="INSERT INTO airline_tbl 
							  (id,airline,aircode,image)
							   VALUES
							  ('','$airline','$aircode','$image')";
							  $result=mysql_query($sql);
							  
							
							  if($result)
							  {
								$response["success"]="Airline Added Successfully";
							  }
							  else
							  {
								   $response["error"]=mysql_error();
							  }
							  
					}
                    else
					{
						$response["error"]="This Airline Already Exist";
					}						
											  
					
		            
		break;	
					
		
         case "edit": 
					$airline_id=$_POST['airline_id'];
					$sql="SELECT * FROM airline_tbl WHERE id='$airline_id'";
					$result=mysql_query($sql);
					while($row=mysql_fetch_array($result,MYSQL_ASSOC))
						array_push($response,$row);
		break;

        case "update":
		           
		           	$airline_id=$_POST['airline_id'];			
					$airline=mysql_real_escape_string(trim($_POST['airline']));
					$aircode=mysql_real_escape_string(trim($_POST['aircode']));
					$image=mysql_real_escape_string(trim($_POST['image']));
					
					$sql_check="SELECT * FROM airline_tbl WHERE airline='$airline' AND id!='$airline_id'";
					$result_check=mysql_query($sql_check);
					if(mysql_num_rows($result_check)==0)
					{
						$sql="UPDATE airline_tbl SET 
						airline='$airline',
						aircode='$aircode',
						image='$image'
						WHERE id=$airline_id";
						$result=mysql_query($sql);	
						if($result)
						{
							$response["success"]="Airline Updated Successfully";	
							
						}	
						else
							$response["error"]=mysql_error();	
					}
					else
					{
						$response["error"]="This Airline Already Exist";
					}	
		break;

        case "delete": 
					$airline_id=$_POST['airline_id'];
					$sql="DELETE FROM airline_tbl WHERE id=$airline_id";
					$result=mysql_query($sql);
					if($result)
					{
						$response["success"]="Airline Deleted Successfully";	
						
					}	
					else
                       	$response["error"]=mysql_error();	
					
		break;		
	}
	
}
echo json_encode($response);

?>