var err=0;
$(document).ready(function ()
{   		 
  
    $('#frm_setting input').each(function()
	 {  
	   if($(this).attr('msg')!='')
	   {
         $(this).bind("keyup",function(e)
	     {
			if(($(this).val().length)>0 )
	       {
			    err=0;
				$(this).parent().find("#div_msg").remove(); 			    
		   }
		   else
		   {
			  err=1;
			  $(this).parent().find("#div_msg").remove(); 
			  $(this).parent().append("<div id='div_msg' style='color:red' class='col-sm-12'>"+$(this).attr('msg')+"</div>");		
		   }
		});
	   }
		 
     });

	$("#setting").addClass('active');

	

	$("#txt_site_title").focus();

	$("#btn_banner_remove").hide();	

	$("#btn_logo_remove").hide();	

	get_all();

	

	

	$("#btn_save").click(function()

	{

	   add();

	});

	$("#btn_cancel").click(function()

	{

	    window.location.href=''+baseurl+'/adminarea/dashboard.php';

	})

		

	$("#file_logo").on("change", function()

    {

	   logo_upload();

	});

	

	

	

	$("#btn_logo_remove").on("click", function()

    {

	   remove_logo();

	});

        

           		

});

function logo_upload()

{

        $("#logo_preview").html('');

        var ext = $("#file_logo").val().split('.').pop();

		if(ext=='png' || ext=='jpg' || ext=='jpeg' || ext=='bmp' || ext=='gif')

		{

		   var file_size=($("#file_logo")[0].files[0].size/ 1048576);

		   

		   if(file_size<2)

		   {

		      $("#logo_preview").html('<img src="'+baseurl+'/adminassets/images/loading.gif" alt="Uploading....">');

		      $.ajax

				({

					url: ''+baseurl+'/adminarea/ajax/upload_logo.php',    	

					type: "POST",      	

					data: new FormData(),	

					data:  new FormData(document.getElementById("frm_setting")),

					contentType: false,       		

					cache: false,					

					processData:false,  			

					success: function(data)  		

					{

						var data	=	$.parseJSON(data);	

							$.each(data, function(k,v) 

							{

								if(k=='success')

								{

								  $("#hid_logo").val(v);

								  $("#btn_logo_remove").show();

								  $("#logo_preview").html('<img src="'+baseurl+'/upload/thumb/'+v+'" alt="Uploading....">');

								}

							});

                    }

                });					

		    }           

		   else		   

		    $("#logo_preview").html('<span style="color:red">Your file is too large please use up to 2MB file</span>');

		}

		else

		 $("#logo_preview").html('<span style="color:red">unsupported file type !!!</span>');

}





function add()

{

    
   $('#frm_setting input').each(function()
	 {  
         if($(this).attr('msg')!='' && $(this).val()=='')
		 {
			err=1;
			$(this).parent().find("#div_msg").remove(); 
			$(this).parent().append("<div id='div_msg' style='color:red' class='col-sm-12'>"+$(this).attr('msg')+"</div>");			
		 }
     });
  
  if(err==0)
  {

     $("#header").find("#div_msg").remove();	

     $("#header").append('<img src="'+baseurl+'assets/images/loading.gif" alt="Uploading...." id="loading">'); 

     $.ajax

			({

				url: ''+baseurl+'/adminarea/ajax/setting_sql.php',      	

				type: "POST",      	

				data:$("#frm_setting").serialize()+"&tag=save",	

				cache: false,					  			

				success: function(data)  		

				{

				   

					var data	=	$.parseJSON(data);	

						$.each(data, function(k,v) 

						{

                              if(k=='success')  

							  {	

                                $("#header").find("#loading").remove(); 							  

								$("#txt_site_title").focus();

                                $("#header").find("#div_msg").remove();								

								$("#header").append('<div id="div_msg" style="color:#69aa46" class="alert alert-block alert-success col-xs-12 col-sm-12 ">'+v+'</div>');

								

							  }

							  else

							  {

							    $("#header").find("#loading").remove(); 

							    $("#txt_site_title").focus();

                                $("#header").find("#div_msg").remove();								

								$("#header").append('<div id="div_msg" style="color:#F87279" class="alert alert-block alert-danger col-xs-12 col-sm-12 ">'+v+'</div>');

							  }

                              	

						});

				}

            });		

  
  }
}



function get_all()
{
	

   $("#preview").html('<img src="'+baseurl+'assets/images/loading.gif" alt="Uploading....">');

   $.ajax

			({

				url: ''+baseurl+'/adminarea/ajax/setting_sql.php',   	

				type: "POST",      	

				data: "tag=get_all",   		

				cache: false,					  			

				success: function(data)  		

				{

					var data	=	$.parseJSON(data);	

						$.each(data, function(k,v) 

						{			

                                							

								$("#txt_site_title").val(v['site_title']);
								$("#txt_phone_no").val(v['phone_no']);
								$("#txt_fax").val(v['fax']);
								$("#txt_email").val(v['email']);
								$("#txt_address").val(v['address']);
								 $("#map").val(v['map']);
								$("#txt_facebook_link").val(v['facebook_link']);
								$("#txt_twitter_link").val(v['twitter_link']);
								$("#txt_google_link").val(v['google_link']);
								$("#txt_pin_link").val(v['pinterest_link']);
								$("#txt_insta_link").val(v['instagram_link']);
								$("#txt_linkedin_link").val(v['linkedin_link']);
								$("#txt_service_charge").val(v['service_charge']);
                                $("#txt_sgst").val(v['sgst']);
                                $("#txt_cgst").val(v['cgst']);
                                $("#txt_igst").val(v['igst']);
								
								$("#txt_bank_name").val(v['bank_name']);
								$("#txt_branch").val(v['branch']);
								$("#txt_acc_name").val(v['acc_no']);
								$("#txt_acc_no").val(v['acc_name']);
								$("#txt_ifsc").val(v['ifsc']);

								
								

									

								if(v['logo']!='')

								{

					              $("#logo_preview").html('<img src="'+baseurl+'/upload/thumb/'+v['logo']+'" alt="Uploading....">');

								  $("#btn_logo_remove").show();

								  $("#hid_logo").val(v['logo']);

								}

                                else

								{

								  $("#logo_preview").html('<img src="'+baseurl+'/adminassets/images/no_image.jpg" alt="Uploading....">');

								}



                                							

								

								

								

								

								

						});

						return false;

				}

            });	

}

function remove_logo()

{

  $("#file_logo").val('');

  $("#logo_preview").html('');

  $("#hid_logo").val('');

  $("#btn_logo_remove").hide();

}





function isNumber(evt) 
{
        var iKeyCode = (evt.which) ? evt.which : evt.keyCode
        if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
            return false;

        return true;
}