/*********************************PASSWORD METER START***************************************/	
var score=0;
var error=0;
var email_valid=/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i;
var strPassword;
var charPassword;
var complexity = $("#complexity");
var minPasswordLength = 8;
var baseScore = 0;

var num = {};
num.Excess = 0;
num.Upper = 0;
num.Numbers = 0;
num.Symbols = 0;

var bonus = {};
bonus.Excess = 3;
bonus.Upper = 4;
bonus.Numbers = 5;
bonus.Symbols = 5;
bonus.Combo = 0; 
bonus.FlatLower = 0;
bonus.FlatNumber = 0;

function checkVal()
{
    
	init();
	
	if (charPassword.length >= minPasswordLength)
	{
		baseScore = 50;	
		analyzeString();	
		calcComplexity();		
	}
	else
	{
		baseScore = 0;
	}
	
	outputResult();
}

function init()
{
	strPassword= $("#txt_password").val();
	charPassword = strPassword.split("");
		
	num.Excess = 0;
	num.Upper = 0;
	num.Numbers = 0;
	num.Symbols = 0;
	bonus.Combo = 0; 
	bonus.FlatLower = 0;
	bonus.FlatNumber = 0;
	baseScore = 0;
	score =0;
}

function analyzeString ()
{	
	for (i=0; i<charPassword.length;i++)
	{
		if (charPassword[i].match(/[A-Z]/g)) {num.Upper++;}
		if (charPassword[i].match(/[0-9]/g)) {num.Numbers++;}
		if (charPassword[i].match(/(.*[!,@,#,$,%,^,&,*,?,_,~])/)) {num.Symbols++;} 
	}
	
	num.Excess = charPassword.length - minPasswordLength;
	
	if (num.Upper && num.Numbers && num.Symbols)
	{
		bonus.Combo = 25; 
	}

	else if ((num.Upper && num.Numbers) || (num.Upper && num.Symbols) || (num.Numbers && num.Symbols))
	{
		bonus.Combo = 15; 
	}
	
	if (strPassword.match(/^[\sa-z]+$/))
	{ 
		bonus.FlatLower = -15;
	}
	
	if (strPassword.match(/^[\s0-9]+$/))
	{ 
		bonus.FlatNumber = -35;
	}
}
	
function calcComplexity()
{
	score = baseScore + (num.Excess*bonus.Excess) + (num.Upper*bonus.Upper) + (num.Numbers*bonus.Numbers) + (num.Symbols*bonus.Symbols) + bonus.Combo + bonus.FlatLower + bonus.FlatNumber;
	
}	
	
function outputResult()
{
	if ($("#txt_password").val()== "")
	{ 
		$("#complexity").html("Password strength").removeClass("weak strong stronger strongest").addClass("default");
	}
	else if (charPassword.length < minPasswordLength)
	{
		$("#complexity").html("At least " + minPasswordLength+ " characters please!").removeClass("strong stronger strongest").addClass("weak");
	}
	else if (score<50)
	{
		$("#complexity").html("Weak!").removeClass("strong stronger strongest").addClass("weak");
	}
	else if (score>=50 && score<75)
	{
		$("#complexity").html("Average!").removeClass("stronger strongest").addClass("strong");
	}
	else if (score>=75 && score<100)
	{
		$("#complexity").html("Strong!").removeClass("strongest").addClass("stronger");
	}
	else if (score>=100)
	{
		$("#complexity").html("Secure!").addClass("strongest");
	}
	
	
		
}
/*********************************PASSWORD METER END***************************************/
$(document).ready(function ()
{   		 
	
	$("#complexity").hide();
    outputResult();
    
	
	$("#btn_change").click(function()
	{
	    if($('#txt_old_password').val()=='')
		  {
		     $('#txt_old_password').parent().find("#div_msg").remove();
			 $("#txt_old_password").parent().addClass('form-group has-error');  
		     $('#txt_old_password').parent().append('<div id="div_msg" style="color:#F87279" class="alert alert-block alert-danger col-xs-12 col-sm-12 ">Enter Current password</div>');
			 return false;			
		  }
		  else
		  {
		     $('#txt_old_password').parent().find("#div_msg").remove(); 
			 $("#txt_old_password").parent().removeClass('form-group has-error'); 
		  }
	     if(score<=50 )
		  {
		     $('#txt_password').parent().find("#div_msg").remove();
			 $("#txt_password").parent().addClass('form-group has-error');  
		     $('#txt_password').parent().append('<div id="div_msg" style="color:#F87279" class="alert alert-block alert-danger col-xs-12 col-sm-12 ">Enter Strong password</div>');
			  return false;				
		  }
		  else
		  {
		     $('#txt_password').parent().find("#div_msg").remove(); 
			 $("#txt_password").parent().removeClass('form-group has-error'); 
		  }
		  if(score>50 && error==0)
		  {
		    change();
			 return false;				
		  }
	});
	
	$("#btn_cancel").click(function()
	{
	    window.location.href=''+baseurl+'/adminarea/dashboard.php';
	});
		
	$("#txt_old_password").focus();
	
	$("#txt_old_password").bind("keyup",function()
	 {
			
			if($(this).val().length>29)
			{
				
				$('#txt_old_password').parent().find("#div_msg").remove();
				$("#txt_old_password").parent().addClass('form-group has-error');  
				$("#txt_old_password").parent().append('<div id="div_msg" style="color:#F87279" class="alert alert-block alert-danger col-xs-12 col-sm-12 ">Enter up to 30 characters for Password !!!</div>');				
				error=1;
				
			}
			
			else
			{
			   error=0;
			   $('#txt_old_password').parent().find("#div_msg").remove();
			   $("#txt_old_password").parent().removeClass('form-group has-error');
			}	
         	   
			
	 });
	 
	 $("#txt_password").bind("keyup",function()
	 {
	    checkVal();
		if($(this).val().length>0)
	     $("#complexity").show();
	   else
	    $("#complexity").hide();
			if($(this).val().length>29)
			{
				
				$('#txt_password').parent().find("#div_msg").remove();
				$("#txt_password").parent().addClass('form-group has-error');  
				$("#txt_password").parent().append('<div id="div_msg" style="color:#F87279" class="alert alert-block alert-danger col-xs-12 col-sm-12 ">Enter up to 30 characters for Password !!!</div>');				
				error=1;
				
			}
			else
			{
			   error=0;
			   $('#txt_password').parent().find("#div_msg").remove();
			   $("#txt_password").parent().removeClass('form-group has-error');
			}
         	   
			
	 });
	 
	 $("#txt_password").change(function()
	 {
	  $("#complexity").show();
	  checkVal();
	 });
	 
	 $("#txt_confirm_password").bind("keyup",function()
	 {
			
			if($(this).val().length>29)
			{
				
				$('#txt_confirm_password').parent().find("#div_msg").remove();
				$("#txt_confirm_password").parent().addClass('form-group has-error');  
				$("#txt_confirm_password").parent().append('<div id="div_msg" style="color:#F87279" class="alert alert-block alert-danger col-xs-12 col-sm-12 ">Enter up to 30 characters for Password !!!</div>');				
				error=1;
				
			}
			else if($("#txt_confirm_password").val()!=$("#txt_password").val())
			{
				
				$('#txt_confirm_password').parent().find("#div_msg").remove();
				$("#txt_confirm_password").parent().addClass('form-group has-error');  
				$("#txt_confirm_password").parent().append('<div id="div_msg" style="color:#F87279" class="alert alert-block alert-danger col-xs-12 col-sm-12 "> Password not matched !!!</div>');				
				error=1;
				
			}
			else
			{
			   error=0;
			   $('#txt_confirm_password').parent().find("#div_msg").remove();
			   $("#txt_confirm_password").parent().removeClass('form-group has-error');
			}	
         	   
			
	 });
           		
});

function change()
{
        	   		
		
		 
		 $("#header").find("#div_msg").remove();	
		 $("#header").append('<img src="'+baseurl+'/adminassets/images/loading.gif" alt="Uploading...." id="loading">'); 
		 $.ajax
				({
					url: ''+baseurl+'/adminarea/ajax/change_password_sql.php',      	
					type: "POST",      	
					data:$("#frm_change_password").serialize()+"&tag=change",	
					cache: false,					  			
					success: function(data)  		
					{
					   
						var data	=	$.parseJSON(data);	
							$.each(data, function(k,v) 
							{
								  if(k=='success')  
								  {	
									window.location.href=""+baseurl+"/adminarea/index.php";
									localStorage.setItem("msg",v);
									return false;
								  }
								  else
								  {
									$("#header").find("#loading").remove(); 
									$("#header").find("#div_msg").remove(); 
									$("#header").append('<div id="div_msg" style="color:#F87279" class="alert alert-block alert-danger col-xs-12 col-sm-12 ">'+v+'</div>');
								  }
									
							});
					}
				});		
		
  
}

