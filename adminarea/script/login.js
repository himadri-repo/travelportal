
$(document).ready(function ()
{    
    
    if(localStorage.getItem("msg")!=null)
    {	   
		var x=localStorage.getItem("msg");
		var box=bootbox.dialog(
		{
			message:''+x,
			title: "Message",
			buttons:
			{
				
				main: 
				{
					label: "OK",
					className: "btn-primary",
					callback: function() 
					{
					  
					}
				},
			
			}
		});
		localStorage.removeItem("msg");
		
    }
    if(localStorage.getItem("admin_user_name")==null)
		  $("#txt_login_id").val('');
	else
    	$("#txt_login_id").val(localStorage.getItem("admin_user_name"));
	if(localStorage.getItem("admin_password")==null) 
		$("#txt_password").val('');
	else
      	$("#txt_password").val(localStorage.getItem("admin_password"));
	
     $("#txt_login_id").focus();	
	 $("#txt_login_id").bind("keyup",function(e)
	 {
			 
			if(($(this).val().length)>49)
			{
				
				$('#txt_login_id').parent().find("#div_msg").remove();
				$("#txt_login_id").parent().append('<div id="div_msg" style="color:#F87279" class="alert alert-block alert-danger col-xs-12 col-sm-12 ">Maximam length Exceeded !</div>');
				return false;
			}
			else if(($(this).val().length)>0)
			{
			 $('#txt_login_id').parent().find("#div_msg").remove();
			}
			else
			{
			   
			   $('#txt_login_id').find("#div_msg").remove();
			}	
	 });
	 
	 $("#txt_login_id").bind("keyup",function(e)
	 {		
			if(e.keyCode==8)
			{
				if(($(this).val().length)<50)
				{
				    $('#txt_login_id').parent().find("#div_msg").remove();

				}	
			}
			if(e.keyCode==13)
				notblank();
	});
	


	$("#txt_password").bind("keyup",function(e)
	 {
			 
			if(($(this).val().length)>29)
			{
				$('#txt_password').parent().find("#div_msg").remove();
				$("#txt_password").parent().append('<div id="div_msg" style="color:#F87279" class="alert alert-block alert-danger col-xs-12 col-sm-12 ">Maximam length Exceeded !</div>');
				return false;
			}
			else if(($(this).val().length)>0)
			{
			 $('#txt_password').parent().find("#div_msg").remove();
			}
			else
			{			   
			  $('#txt_password').parent().find("#div_msg").remove();
			}	
	 });
	 
	$("#txt_password").bind("keyup",function(e)
	 {
		   
			if(e.keyCode==8)
			{
				if(($(this).val().length)<30)
				{
					 $('#txt_password').parent().find("#div_msg").remove();
				}	
			}
			if(e.keyCode==13)
				notblank();
	});
	
	
	
	$("#btn_login").click(function()
	{
		notblank();
	});
	
	
     $("#chk_remember").click(function()
	{
	    
		$("#chk_dont_remember").prop("checked", false);
	});
	
	$("#chk_dont_remember").click(function()
	{
		$("#chk_remember").prop("checked", false);
	});



});

function login()
	{  
		$.ajax({
					url: ''+baseurl+'/adminarea/ajax/login_sql.php',   	
					type: "POST",      	
					data:"tag=login"+"&password="+$("#txt_password").val()+"&login_id="+$("#txt_login_id").val(),	
					cache: false,							
					success: function(data)  		
					{
						var data	=	$.parseJSON(data);	
						$.each(data, function(k,v) 
						{
							if(k=='error1')
							{	
                                $("#txt_login_id").parent().find("#div_msg").remove();							
								$("#txt_login_id").parent().append('<div id="div_msg" style="color:#F87279" class="alert alert-block alert-danger col-xs-12 col-sm-12 ">'+v+'</div>');
								$("#txt_login_id").focus();
							}
							else
							{							
								$("#txt_login_id").parent().find("#div_msg").remove();
								
							}
							if(k=='error2')
							{
							    $("#txt_password").parent().find("#div_msg").remove();
								$("#txt_password").parent().append('<div id="div_msg" style="color:#F87279" class="alert alert-block alert-danger col-xs-12 col-sm-12 ">'+v+'</div>');
								$("#txt_password").focus();
							}
							else
							{
								$("#txt_password").parent().find("#div_msg").remove();
								
							}
							if(k=='success')
							{
							    if ($('#chk_remember').is(":checked"))
								{
								   localStorage.setItem("admin_user_name",$("#txt_login_id").val());
								   localStorage.setItem("admin_password",$("#txt_password").val());
								}
								if ($('#chk_dont_remember').is(":checked"))
								{								   
								    
								    localStorage.setItem("admin_password",'');
									localStorage.setItem("admin_user_name",'');
									
								}
								$("#txt_password").parent().find("#div_msg").remove();
								$("#txt_login_id").parent().find("#div_msg").remove();
								window.location.href=""+baseurl+"/adminarea/dashboard.php";
								
							}
						});	
					}
							
			  });

	}
	
	function notblank()
	{
		if($("#txt_login_id").val()=='')
		{
			$('#txt_login_id').parent().find("#div_msg").remove();
			$("#txt_login_id").parent().append('<div id="div_msg" style="color:#F87279" class="alert alert-block alert-danger col-xs-12 col-sm-12 ">Blank User Name can not be allowed !!!</div>');
			$("#txt_login_id").focus();
			return false;			
			
		}
		 else if($("#txt_password").val()=='')
		{
			$('#txt_password').parent().find("#div_msg").remove();
			$("#txt_password").parent().append('<div id="div_msg" style="color:#F87279" class="alert alert-block alert-danger col-xs-12 col-sm-12 ">Blank Password can not be allowed !!!</div>');
			$("#txt_password").focus();
			return false;	
		}
		else
		{
		    
			login();
		}
	}
