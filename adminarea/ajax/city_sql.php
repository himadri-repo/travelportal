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
					$sql="SELECT * FROM city_tbl WHERE city LIKE '%$s%'";
					$result=mysql_query($sql);
					if(mysql_num_rows($result)>0)
					{
						$ctr=ceil(mysql_num_rows($result)/$limit);
						$total=mysql_num_rows($result);
						$sql="SELECT * FROM city_tbl WHERE  city LIKE '%$s%'  order by id desc LIMIT $start,$limit";			
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
		           
		            $city=mysql_real_escape_string(trim($_POST['city']." ".$_POST['code']));
					$code=mysql_real_escape_string(trim($_POST['code']));
					
					$sql_check="SELECT * FROM city_tbl WHERE city='$city'";
					$result_check=mysql_query($sql_check);
					if(mysql_num_rows($result_check)==0)
					{
					          
					          $sql="INSERT INTO city_tbl 
							  (id,city,code)
							   VALUES
							  ('','$city','$code')";
							  $result=mysql_query($sql);
							  
							
							  if($result)
							  {
								$response["success"]="City Added Successfully";
							  }
							  else
							  {
								   $response["error"]=mysql_error();
							  }
							  
					}
                    else
					{
						$response["error"]="This City Already Exist";
					}						
											  
					
		            
		break;	
					
		
         case "edit": 
					$city_id=$_POST['city_id'];
					$sql="SELECT * FROM city_tbl WHERE id='$city_id'";
					$result=mysql_query($sql);
					while($row=mysql_fetch_array($result,MYSQL_ASSOC))
						array_push($response,$row);
		break;

        case "update":
		           
		           	$city_id=$_POST['city_id'];			
					$city=mysql_real_escape_string(trim($_POST['city']." ".$_POST['code']));
					$code=mysql_real_escape_string(trim($_POST['code']));
					$sql_check="SELECT * FROM city_tbl WHERE city='$city' AND id!='$city_id'";
					$result_check=mysql_query($sql_check);
					if(mysql_num_rows($result_check)==0)
					{
						$sql="UPDATE city_tbl SET 
						city='$city',
						code='$code'
						
						WHERE id=$city_id";
						$result=mysql_query($sql);	
						if($result)
						{
							$response["success"]="City Updated Successfully";	
							
						}	
						else
							$response["error"]=mysql_error();	
					}	
					else
					{
						$response["error"]="This City Already Exist";
					}
		break;

        case "delete": 
					$city_id=$_POST['city_id'];
					$sql="DELETE FROM city_tbl WHERE id=$city_id";
					$result=mysql_query($sql);
					if($result)
					{
						$response["success"]="City Deleted Successfully";	
						
					}	
					else
                       	$response["error"]=mysql_error();	
					
		break;		
	}
	
}
echo json_encode($response);

?>