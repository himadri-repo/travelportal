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
					$start=$start*100;
					$limit=100;
					$total=0;
					$s=trim($_POST['s']);
					$sql="SELECT * FROM country WHERE country_name LIKE '%$s%'";
					$result=mysql_query($sql);
					if(mysql_num_rows($result)>0)
					{
						$ctr=ceil(mysql_num_rows($result)/$limit);
						$total=mysql_num_rows($result);
						$sql="SELECT * FROM country WHERE country_name LIKE '%$s%' order by trim(country_name) asc LIMIT $start,$limit";			
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
        

		
        case "delete": 
					$id=$_POST['id'];
					$sql="DELETE FROM country WHERE id=$id";
					$result=mysql_query($sql);
					if($result)
					{
						$response["success"]="Country Deleted Successfully";	
					
					}	
					else
                       	$response["error"]=mysql_error();						
		break;	
	}
}
echo json_encode($response);
?>