<?php

include_once('../../config.php');



if(isset($_POST["tag"]))

{

	$tag=$_POST["tag"];

	$response=array();

	switch($tag)

	{

      

       

        case "save":

		            $site_title=$_POST['txt_site_title'];
		            $phone_no=trim($_POST['txt_phone_no']);
					$fax=trim($_POST['txt_fax']);
					$email=trim($_POST['txt_email']);
					$address=mysql_real_escape_string(trim($_POST['txt_address']));
					$facebook_link=trim($_POST['txt_facebook_link']);
					$twitter_link=trim($_POST['txt_twitter_link']);
                    $pinterest_link=trim($_POST['txt_pin_link']);
					$linkedin_link=trim($_POST['txt_linkedin_link']);
					$google_link=trim($_POST['txt_google_link']);					 												
					$instagram_link=trim($_POST['txt_insta_link']);
					$logo=trim($_POST['hid_logo']);
					$service_charge=trim($_POST['txt_service_charge']);
					$cgst=trim($_POST['txt_cgst']);
					$sgst=trim($_POST['txt_sgst']);
					$igst=trim($_POST['txt_igst']);
					$map=trim($_POST['map']);
					$bank_name=trim($_POST['txt_bank_name']);
					$branch=trim($_POST['txt_branch']);
					$acc_name=trim($_POST['txt_acc_name']);
					$acc_no=trim($_POST['txt_acc_no']);
					$ifsc=trim($_POST['txt_ifsc']);

					

					

					$sql="UPDATE setting_tbl SET site_title='$site_title',
					phone_no='$phone_no',
					fax='$fax',
					email='$email',
					address='$address',
					facebook_link='$facebook_link',
					twitter_link='$twitter_link',
					pinterest_link='$pinterest_link',
                    linkedin_link='$linkedin_link',
					google_link='$google_link',
					instagram_link='$instagram_link',
					service_charge='$service_charge',
					cgst='$cgst',
					sgst='$sgst',
					igst='$igst',
					logo='$logo',
				    map='$map',
					bank_name='$bank_name',
					branch='$branch',
					acc_name='$acc_name',
					acc_no='$acc_no',
					ifsc='$ifsc'					
					WHERE setting_id=2";

					$result=mysql_query($sql);	

					if($result)

					{

						$response["success"]="Setting Updated Successfully";	

						//del_unsaved_file();

						//del_unsaved_file1();

					}	

					else

                       	$response["error"]=mysql_error();		

		break;

		

		 case "get_all": 					
					$sql="SELECT * FROM setting_tbl WHERE setting_id=2";
					$result=mysql_query($sql);
					while($row=mysql_fetch_array($result,MYSQL_ASSOC))
						array_push($response,$row);

		break;



        

	}

	

}

echo json_encode($response);

function del_unsaved_file()

{

		$sql="SELECT logo FROM setting_tbl";

		$result=mysql_query($sql);

		$image_arr=array();

		$pre=array();

		while($row=mysql_fetch_array($result))

		{

			$image_arr[]=$row['logo'];

		}

		$files=glob('../../upload/*');	

		foreach($files as $file)

		{

			$del_file=explode("/",$file);

			$del=end($del_file);

			$pre=explode("_",$del);

			$prefix=$pre[0];

			if($prefix=='logo')

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



function del_unsaved_file1()

{

		$sql="SELECT side_banner FROM setting_tbl";

		$result=mysql_query($sql);

		$image_arr=array();

		$pre=array();

		while($row=mysql_fetch_array($result))

		{

			$image_arr[]=$row['side_banner'];

		}

		$files=glob('../../upload/*');	

		foreach($files as $file)

		{

			$del_file=explode("/",$file);

			$del=end($del_file);

			$pre=explode("_",$del);

			$prefix=$pre[0];

			if($prefix=='banner')

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