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
					$sql="SELECT * FROM slider_tbl WHERE slider_title LIKE '%$s%'";
					$result=mysql_query($sql);
					if(mysql_num_rows($result)>0)
					{
						$ctr=ceil(mysql_num_rows($result)/$limit);
						$total=mysql_num_rows($result);
						$sql="SELECT * FROM slider_tbl WHERE  slider_title LIKE '%$s%'  order by slider_id desc LIMIT $start,$limit";			
						$result=mysql_query($sql);
						$i=0;
						while($row=mysql_fetch_array($result,MYSQL_ASSOC))
						{
						 foreach($row as $k=>$v)
						  {
						   if($k=='slider_description')
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
		            $slider_title=trim($_POST['slider_title']);
					$slider_sub_title=trim($_POST['slider_sub_title']);
					$slider_description=trim($_POST['slider_description']);
					$slider_image_name=trim($_POST['slider_image_name']);							
		            $sql="INSERT INTO slider_tbl(slider_id,slider_title,slider_sub_title,slider_description,slider_image_name)VALUES('','$slider_title','$slider_sub_title','$slider_description','$slider_image_name')";
					$result=mysql_query($sql);
					if($result)
					{
						$response["success"]="Slider Added Successfully";
						del_unsaved_file();
						
					}
					else
					$response["error"]=mysql_error();
		break;
       	

         case "edit": 
					$slider_id=$_POST['slider_id'];
					$sql="SELECT * FROM slider_tbl WHERE slider_id='$slider_id'";
					$result=mysql_query($sql);
					while($row=mysql_fetch_array($result,MYSQL_ASSOC))
						array_push($response,$row);
		break;

        case "update":
		            $slider_id=$_POST['slider_id'];
		            $slider_title=trim($_POST['slider_title']);
					$slider_sub_title=trim($_POST['slider_sub_title']);
					$slider_description=trim($_POST['slider_description']);
					$slider_image_name=trim($_POST['slider_image_name']);	
					$sql="UPDATE slider_tbl SET slider_title='$slider_title',slider_sub_title='$slider_sub_title',slider_description='$slider_description',slider_image_name='$slider_image_name' WHERE slider_id='$slider_id'";
					$result=mysql_query($sql);	
					if($result)
					{
						$response["success"]="Slider Updated Successfully";	
						del_unsaved_file();
					}	
					else
                       	$response["error"]=mysql_error();		
		break;

        case "delete": 
					$slider_id=$_POST['slider_id'];
					$sql="DELETE FROM slider_tbl WHERE slider_id=$slider_id";
					$result=mysql_query($sql);
					if($result)
					{
						$response["success"]="Slider Deleted Successfully";	
						del_unsaved_file();
					}	
					else
                       	$response["error"]=mysql_error();	
					
		break;		
	}
	
}
echo json_encode($response);
function del_unsaved_file()
{
		$sql="SELECT slider_image_name FROM slider_tbl";
		$result=mysql_query($sql);
		$image_arr=array();
		$pre=array();
		while($row=mysql_fetch_array($result))
		{
			$image_arr[]=$row['slider_image_name'];
		}
		$files=glob('../../upload/*');	
		foreach($files as $file)
		{
			$del_file=explode("/",$file);
			$del=end($del_file);
			$pre=explode("_",$del);
			$prefix=$pre[0];
			if($prefix=='slider')
			{
				if(!in_array($del,$image_arr))
				{
					if(is_file('../../upload/'.$del))
						unlink('../../upload/'.$del);
					if(is_file('../../upload/thumb/'.$del))
						unlink('../../upload/thumb/'.$del);	
					
				}
			}
		}				
}

?>