<?php
include_once('../../config.php');
if(isset($_POST["tag"]))
{
	$tag=$_POST["tag"];
	$response=array();
	switch($tag)
	{
        case "search":
					$start=$_POST['start'];
					$s=trim($_POST['s']);
					$start=$start*10;
					$limit=10;
					$total=0;
					$sql="SELECT * FROM term_tbl WHERE title LIKE '%$s%'";
					$result=mysql_query($sql);
					if(mysql_num_rows($result)>0)
					{
						$ctr=ceil(mysql_num_rows($result)/$limit);
						$total=mysql_num_rows($result);
						$sql="SELECT * FROM term_tbl WHERE  title LIKE '%$s%'  order by id desc LIMIT $start,$limit";			
						$result=mysql_query($sql);
						$i=0;
						while($row=mysql_fetch_array($result,MYSQL_ASSOC))
						{
						 foreach($row as $k=>$v)
						  {
						   if($k=='description')
						      $response[$i][$k]=substr(strip_tags($v),0,50)."...";
						   else  
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
		            $title=trim($_POST['title']);					
					$description=trim($_POST['description']);											
		            $sql="INSERT INTO term_tbl(id,title,description)VALUES('','$title','$description')";
					$result=mysql_query($sql);
					if($result)
					{
						$response["success"]="Record Added Successfully";
						
						
					}
					else
					$response["error"]=mysql_error();
		break;
       	

         case "edit": 
					$term_id=$_POST['term_id'];
					$sql="SELECT * FROM term_tbl WHERE id='$term_id'";
					$result=mysql_query($sql);
					while($row=mysql_fetch_array($result,MYSQL_ASSOC))
						array_push($response,$row);
		break;

        case "update":
		            $term_id=$_POST['term_id'];
		            $title=trim($_POST['title']);					
					$description=trim($_POST['description']);		
					$sql="UPDATE term_tbl SET title='$title',description='$description' WHERE id='$term_id'";
					$result=mysql_query($sql);	
					if($result)
					{
						$response["success"]="Record Updated Successfully";	
						
					}	
					else
                       	$response["error"]=mysql_error();		
		break;

        case "delete": 
					$term_id=$_POST['term_id'];
					$sql="DELETE FROM term_tbl WHERE id=$term_id";
					$result=mysql_query($sql);
					if($result)
					{
						$response["success"]="Record Deleted Successfully";	
						
					}	
					else
                       	$response["error"]=mysql_error();	
					
		break;		
	}
	
}
echo json_encode($response);

?>