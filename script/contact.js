
$(document).ready(function()
{  

    var email_regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i;
	var phone_regex=/^[0-9-+]+$/;
	var message='';

	$("#btn_send_mail").click(function()
	{
		
		if($("#txt_name").val()=='')
		{
			
			
			$("#div_err").remove();
			$("#txt_name").parent().append('<div id="div_err" class="col-md-12" style="color:red">Please Enter Name</div>');
			$("#txt_name").focus();
			return false;
		}	
	   
	  
		else  if(!email_regex.test($("#txt_email").val()))
		{
			
			
			$("#div_err").remove();
			$("#txt_email").parent().append('<div id="div_err" class="col-md-12" style="color:red">Please Enter valid email</div>');
			$("#txt_email").focus();
			return false;
		}
		else  if(!phone_regex.test($("#txt_phone").val()))
		{
			$("#div_err").remove();
			$("#txt_phone").parent().append('<div id="div_err" class="col-md-12" style="color:red">Please Enter valid phone no</div>');
			$("#txt_phone").focus();
			return false;
				
		}
		else  if($("#txt_phone").val().length!=10)
		{
			$("#div_err").remove();
			$("#txt_phone").parent().append('<div id="div_err" class="col-md-12" style="color:red">Please Enter 10 digit phone no</div>');
			$("#txt_phone").focus();
			return false;
				
		}
		
		else
		{
			
			$("#div_err").remove();
			$("#mail-msg-box").html("<center><img style='height:50px;border:none' src="+baseurl+"adminassets/images/ajax.gif></center>");
			message=$("#txt_msg").val();
		    $.ajax
						  ({
							url:''+baseurl+'sendmail',
							type: "POST", 
							data:{name:$("#txt_name").val(),email:$("#txt_email").val(),phone:$("#txt_phone").val(),msg:message},
							
							cache: false,							
							success: function(data)  		
							{
								
								var data	=	$.parseJSON(data);	
								$.each(data, function(k,v) 
								{
								 
								   if(k=='success')
								   {
									 $("#mail-msg-box").html('');    
                                     $("#mail-msg-box").append('<span id="div_success" style="color:green">'+v+'</span>');									   
                                     $("#txt_name").val('');
									 $("#txt_email").val('');
									// $("#txt_phone").val('');
									 $("#txt_msg").val('');
									 
								   }
								    if(k=='error')
								   {
									 $("#mail-msg-box").html('');       
                                     $("#mail-msg-box").append('<span style="color:green">'+v+'</span>');	
									 $("#txt_name").val('');
									 $("#txt_email").val('');
									 //$("#txt_phone").val('');
									 $("#txt_msg").val('');
								   }
                                   									 
									  
									 
								});
							}
							
                             });
							 
		}
			
	});
	
	
	



});

