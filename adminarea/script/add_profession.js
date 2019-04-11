var price=0;
$(document).ready(function ()
{   
    	 
    $("#menu_profession").addClass('active');	
	$("#txt_title").focus();
	$("#btn_remove").hide();
	
	
	$("#txt_title").bind("keyup",function(e)
	{
	  if(($(this).val().length)>0)
	  {
	   $('#txt_title').parent().find("#div_msg").remove();
	   $("#txt_title").parent().removeClass('form-group has-error');
	  }
	  else
	  {
	    $('#txt_title').parent().find("#div_msg").remove();
		$("#txt_title").parent().addClass('form-group has-error');
		$("#txt_title").parent().append('<div id="div_msg" style="color:#F87279" class="alert alert-block alert-danger col-xs-12 col-sm-12 ">Profession can not be left blank !!!</div>');
	  }
	});
	
	$("#btn_save").click(function()
	{
	   add();
	});
	
           		
});

function add()
{
    
  if($("#txt_title").val()=='')
  {
    $('#txt_title').parent().find("#div_msg").remove();
	$("#txt_title").parent().addClass('form-group has-error');
    $("#txt_title").parent().append('<div id="div_msg" style="color:#F87279" class="alert alert-block alert-danger col-xs-12 col-sm-12 ">Profession can not be left blank !!!</div>');
    $("#txt_title").focus();            
  }
  else
  {
     if($("#chk_price").is(":checked"))	  
	   price=1;
	 else
        price=0;	 
     $("#header").find("#div_msg").remove();	
     $("#header").append('<img src="'+baseurl+'/adminassets/images/loading.gif" alt="Uploading...." id="loading">');
     $.ajax
			({
				url: ''+baseurl+'/adminarea/ajax/profession_sql.php',      	
				type: "POST",      	
				data: "name="+$("#txt_title").val()+"&tag=insert",      		
				cache: false,					  			
				success: function(data)  		
				{
				   
					var data	=	$.parseJSON(data);	
						$.each(data, function(k,v) 
						{
                              if(k=='success')  
							  {	
                                $("#header").find("#loading").remove();							  
								$("#txt_title").focus();
                                $("#header").find("#div_msg").remove();								
								$("#header").append('<div id="div_msg" style="color:#69aa46" class="alert alert-block alert-success col-xs-12 col-sm-12 ">'+v+'</div>');
								clear1();
							  }
							  else
							  {
							    $("#header").find("#loading").remove();
							    $('#txt_title').parent().find("#div_msg").remove();
		                        $("#txt_title").parent().addClass('form-group has-error');
		                        $("#txt_title").parent().append('<div id="div_msg" style="color:#F87279" class="alert alert-block alert-danger col-xs-12 col-sm-12 ">'+v+'</div>');
							  }
                              	
						});
				}
            });		
  }
}
function remove_image()
{
  
  $("#preview").html('');
  $("#hid_image").val('');
  $("#btn_remove").hide();
}

function clear()
{
  
  $("#txt_title").val('');
  $("#preview").html('');
  $("#txt_title").focus();
  $("#div_msg").remove();
  $("#btn_remove").hide();
  $('input:checkbox[id=chk_price]').attr('checked', false);
  $("#txt_title").parent().removeClass('form-group has-error');
  window.location.href=''+baseurl+'/adminaarea/profession.php';
}
function clear1()
{
  
  $("#txt_title").val('');
  $("#preview").html('');
  $("#txt_title").focus();
  $("#btn_remove").hide();
  $("#txt_title").parent().removeClass('form-group has-error');
  
}