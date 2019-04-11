<?php
include_once('../../config.php');
$dt=new DateTime();
$dt=$dt->format('Y-m-d');
if(isset($_POST["tag"]))
{
	$tag=$_POST["tag"];
	$response=array();
	switch($tag)
	{
        case "search":
		            $arr=array();
					$start=$_POST['start'];
					$s=mysql_real_escape_string($_POST['s']);
					$start=$start*50;
					$limit=50;
					$total=0;
					
					$sql="SELECT * FROM profession_tbl WHERE name LIKE '%$s%'";
					$result=mysql_query($sql);
					if(mysql_num_rows($result)>0)
					{
						$ctr=ceil(mysql_num_rows($result)/$limit);
						$total=mysql_num_rows($result);
						$sql="SELECT * FROM profession_tbl WHERE  name LIKE '%$s%' order by name ASC LIMIT $start,$limit";			
						$result=mysql_query($sql);
						$i=0;
						while($row=mysql_fetch_array($result,MYSQL_ASSOC))
						{
						 foreach($row as $k=>$v)
						  {	
                             				 
								$response[$i][$k]=strip_tags($v);  
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
		            $name=mysql_real_escape_string(trim($_POST['name']));									
                    $sql="SELECT * FROM profession_tbl WHERE name='$name'"; 					
					$result=mysql_query($sql);
					if(mysql_num_rows($result)>0)
					{
					  $response["error"]="This Profession Already Exist !!!.";
					}
					else
					{
						$sql="INSERT INTO profession_tbl
						(id,name)
						VALUES
						('','$name')";
						$result=mysql_query($sql);
						if($result)
						{
							$response["success"]="Profession Added Successfully";
							
							
						}
						else
						$response["error"]=mysql_error();
					}
		break;
       	

        case "edit": 
					$id=$_POST['profession_id'];
					$sql="SELECT * FROM profession_tbl WHERE id='$id'";
					$result=mysql_query($sql);
					while($row=mysql_fetch_array($result,MYSQL_ASSOC))
						array_push($response,$row);
		break;

        case "update":
		            $id=$_POST['profession_id'];
		            $name=mysql_real_escape_string(trim($_POST['category_name']));					
					
                    $sql="SELECT * FROM profession_tbl WHERE name='$name' AND id!='$id'"; 					
					$result=mysql_query($sql);
					if(mysql_num_rows($result)>0)
					{
					  $response["error"]="This Profession Already Exist !!!.";
					}
					else
					{					
						$sql="UPDATE profession_tbl SET 
						name='$name'						
						
						
						WHERE id='$id'";
						$result=mysql_query($sql);	
						if($result)
						{
							$response["success"]="Profession Updated Successfully";	
							
						}	
						else
							$response["error"]=mysql_error();
                    }						
		break;

        case "delete": 
					$id=$_POST['profession_id'];
					$sql="DELETE FROM profession_tbl WHERE id=$id";
					$result=mysql_query($sql);
					if($result)
					{
						$response["success"]="Profession Deleted Successfully";	
					
					}	
					else
                       	$response["error"]=mysql_error();	
					
		break;		
	}
	
}
echo json_encode($response);


?>