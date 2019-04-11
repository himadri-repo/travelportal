<?php
include_once('../../config.php');
$response=array();
if(isset($_FILES["file_logo"]["type"]))  
{	
     
	    $temporary = explode(".", $_FILES["file_logo"]["name"]);		
		$file_extension = end($temporary);
		$filename ="logo_".time() . '.' . $file_extension;				
		$sourcePath = $_FILES['file_logo']['tmp_name'];   
		$targetPath = "../../upload/".$filename ;  
		if(move_uploaded_file($sourcePath,$targetPath))
		{			
			$response["success"]=$filename;
			$folderName = "../../upload/";	
			$filepath = $folderName . $filename;
			require_once('php_image_magician.php');
			$magicianObj = new imageLib($filepath);
			$magicianObj->resizeImage(50,50,2);		
			$success=$magicianObj->saveImage("../../upload/thumb/".$filename,100);									
					
		}
								
echo json_encode($response);		
}
 	
?>
