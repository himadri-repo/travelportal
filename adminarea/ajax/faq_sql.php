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
					$sql="SELECT * FROM faq_tbl WHERE question LIKE '%$s%'";
					$result=mysql_query($sql);
					if(mysql_num_rows($result)>0)
					{
						$ctr=ceil(mysql_num_rows($result)/$limit);
						$total=mysql_num_rows($result);
						$sql="SELECT * FROM faq_tbl WHERE  question LIKE '%$s%'  order by id desc LIMIT $start,$limit";			
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
		            $question=trim($_POST['question']);					
					$description=trim($_POST['description']);											
		            $sql="INSERT INTO faq_tbl(id,question,description)VALUES('','$question','$description')";
					$result=mysql_query($sql);
					if($result)
					{
						$response["success"]="FAQ Added Successfully";
						
						
					}
					else
					$response["error"]=mysql_error();
		break;
       	

         case "edit": 
					$faq_id=$_POST['faq_id'];
					$sql="SELECT * FROM faq_tbl WHERE id='$faq_id'";
					$result=mysql_query($sql);
					while($row=mysql_fetch_array($result,MYSQL_ASSOC))
						array_push($response,$row);
		break;

        case "update":
		            $faq_id=$_POST['faq_id'];
		            $question=trim($_POST['question']);					
					$description=trim($_POST['description']);		
					$sql="UPDATE faq_tbl SET question='$question',description='$description' WHERE id='$faq_id'";
					$result=mysql_query($sql);	
					if($result)
					{
						$response["success"]="FAQ Updated Successfully";	
						
					}	
					else
                       	$response["error"]=mysql_error();		
		break;

        case "delete": 
					$faq_id=$_POST['faq_id'];
					$sql="DELETE FROM faq_tbl WHERE id=$faq_id";
					$result=mysql_query($sql);
					if($result)
					{
						$response["success"]="FAQ Deleted Successfully";	
						
					}	
					else
                       	$response["error"]=mysql_error();	
					
		break;		
	}
	
}
echo json_encode($response);

?>