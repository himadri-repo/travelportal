<?php
include_once('../../config.php');
$response=array();
if(isset($_FILES["file_image"]["type"]))  
{	
     
	    $temporary = explode(".", $_FILES["file_image"]["name"]);		
		$file_extension = end($temporary);
		$filename ="page_".time() . '.' . $file_extension;				
		$sourcePath = $_FILES['file_image']['tmp_name'];   
		$targetPath = "../../upload/".$filename ;  
		if(move_uploaded_file($sourcePath,$targetPath))
		{			
			$response["success"]=$filename;
			$folderName = "../../upload/";	
			$filepath = $folderName . $filename;
			require_once('php_image_magician.php');
			$magicianObj = new imageLib($filepath);
			$magicianObj->resizeImage(350,350,2);		
			$success=$magicianObj->saveImage("../../upload/thumb/".$filename,100);									
					
		}
								
echo json_encode($response);		
}
 	
?>
