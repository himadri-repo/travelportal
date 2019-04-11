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
					$start=$_POST['start'];
					$s=trim($_POST['s']);
					$start=$start*10;
					$limit=10;
					$total=0;
					$sql="SELECT * FROM content_tbl WHERE title LIKE '%$s%'";
					$result=mysql_query($sql);
					if(mysql_num_rows($result)>0)
					{
						$ctr=ceil(mysql_num_rows($result)/$limit);
						$total=mysql_num_rows($result);
						$sql="SELECT * FROM content_tbl WHERE  title LIKE '%$s%'  order by id ASC LIMIT $start,$limit";			
						$result=mysql_query($sql);
						$i=0;
						while($row=mysql_fetch_array($result,MYSQL_ASSOC))
						{
						 foreach($row as $k=>$v)
						  {
						    if($k=='description')
						      $response[$i][$k]=substr(strip_tags($v),0,50)."...";
						   else  
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
		            $page_title=mysql_real_escape_string(trim($_POST['page_title']));
					$sql="SELECT * FROM page_tbl WHERE page_title='$page_title'";
					$result=mysql_query($sql);
					if(mysql_num_rows($result)==0)
					{
						$page_sub_title=mysql_real_escape_string(trim($_POST['page_sub_title']));
						$page_description=mysql_real_escape_string(trim($_POST['page_description']));
						$page_image_name=trim($_POST['page_image_name']);								
						$sql="INSERT INTO page_tbl(page_id,page_title,page_sub_title,page_description,page_image_name)VALUES('','$page_title','$page_sub_title','$page_description','$page_image_name')";
						$result=mysql_query($sql);
						if($result)
						{
							$response["success"]="Page Added Successfully";
							del_unsaved_file();
							
						}
						
					}
					else
					{ 
					   $response["error"]="This page is already existing. Please try with another Page title";
					}
		break;
       	

         case "edit": 
					$content_id=$_POST['content_id'];
					$sql="SELECT * FROM content_tbl WHERE id='$content_id'";
					$result=mysql_query($sql);
					while($row=mysql_fetch_array($result,MYSQL_ASSOC))
						array_push($response,$row);
		break;

        case "update":
		            $content_id=$_POST['content_id'];
					$image=$_POST['image'];
					$description=$_POST['description'];
		            $description=mysql_real_escape_string(trim($_POST['description']));
					
						
						$description=mysql_real_escape_string(trim($_POST['description']));
											
						$sql="UPDATE content_tbl SET description='$description',image='$image' WHERE id='$content_id'";
						$result=mysql_query($sql);	
						if($result)
						{
							$response["success"]="Content Updated Successfully";	
							//del_unsaved_file();
						}	
									   
		break;

        case "delete": 
					$page_id=$_POST['page_id'];
					$sql="DELETE FROM page_tbl WHERE page_id=$page_id";
					$result=mysql_query($sql);
					if($result)
					{
						$response["success"]="Page Deleted Successfully";	
						del_unsaved_file();
					}	
					else
                       	$response["error"]=mysql_error();	
					
		break;		
	}
	
}
echo json_encode($response);

?>