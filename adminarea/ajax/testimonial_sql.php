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
					$sql="SELECT t.*,u.user_id,u.name FROM testimonials_tbl t INNER JOIN user_tbl u ON t.user_id=u.id WHERE u.name LIKE '%$s%'";
					$result=mysql_query($sql);
					if(mysql_num_rows($result)>0)
					{
						$ctr=ceil(mysql_num_rows($result)/$limit);
						$total=mysql_num_rows($result);
						$sql="SELECT t.*,u.user_id,u.name FROM testimonials_tbl t INNER JOIN user_tbl u ON t.user_id=u.id WHERE u.name LIKE '%$s%'  order by t.id desc LIMIT $start,$limit";			
						$result=mysql_query($sql);
						$i=0;
						while($row=mysql_fetch_array($result,MYSQL_ASSOC))
						{
						 foreach($row as $k=>$v)
						  {
						   if($k=='description')
						      $response[$i][$k]=substr(strip_tags($v),0,50)."...";
						   else if($k=='approved')
						   {
							   if($v==0)
								   $response[$i][$k]="PENDING";
							   else
								   $response[$i][$k]="AAPROVED";
						   }
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
					$testimonial_id=$_POST['testimonial_id'];
					$sql="SELECT * FROM testimonials_tbl WHERE id='$testimonial_id'";
					$result=mysql_query($sql);
					while($row=mysql_fetch_array($result,MYSQL_ASSOC))
						array_push($response,$row);
		break;

        case "update":
		            $testimonial_id=$_POST['testimonial_id'];
		            $approved=trim($_POST['approved']);					
					$description=trim($_POST['description']);		
					$sql="UPDATE testimonials_tbl SET description='$description',approved='$approved' WHERE id='$testimonial_id'";
					$result=mysql_query($sql);	
					if($result)
					{
						$response["success"]="Record Updated Successfully";	
						
					}	
					else
                       	$response["error"]=mysql_error();		
		break;

        case "delete": 
					$testimonial_id=$_POST['testimonial_id'];
					$sql="DELETE FROM testimonials_tbl WHERE id=$testimonial_id";
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