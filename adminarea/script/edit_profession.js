var price=0;
$(document).ready(function ()
{   		 
    if(localStorage.getItem("profession_id")==null)
    {
	  window.location.href=''+baseurl+'/adminarea/profession.php';
	}
	$("#menu_profession").addClass('active');	
	$("#txt_title").focus();
	$("#btn_remove").hide();
	
	//load_category();
	get_record();
	var category='';
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
	
	$("#btn_update").click(function()
	{
	   update();
	});
	
	
           		
});


function update()
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
      
     $("#header").find("#div_msg").remove();	
     $("#header").append('<img src="'+baseurl+'/adminassets/images/loading.gif" alt="Uploading...." id="loading">');
     $.ajax
			({
				url: ''+baseurl+'/adminarea/ajax/profession_sql.php',      	
				type: "POST",      	
				data: "category_name="+$("#txt_title").val()+"&profession_id="+localStorage.getItem("profession_id")+"&tag=update",      		
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
							  }
							  if(k=='error')  
							  {	
                                $("#header").find("#loading").remove();								  
								$("#txt_title").focus();
                                $("#header").find("#div_msg").remove();								
								$("#header").append('<div id="div_msg" style="color:#F87279" class="alert alert-block alert-danger col-xs-12 col-sm-12 ">'+v+'</div>');
							  }
                              	
						});
				}
            });	
  }
}
function get_record()
{
  
   $("#preview").html('<img src="'+baseurl+'/adminassets/images/loading.gif" alt="Uploading....">');
   $.ajax
			({
				url: ''+baseurl+'/adminarea/ajax/profession_sql.php',   	
				type: "POST",      	
				data: "tag=edit"+"&profession_id="+localStorage.getItem("profession_id"),      		
				cache: false,					  			
				success: function(data)  		
				{
					var data	=	$.parseJSON(data);	
						$.each(data, function(k,v) 
						{			
                                
								$("#txt_title").val(v['name']);								
								
								
						});
						return false;
				}
            });	
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
  $("#txt_title").parent().removeClass('form-group has-error');
  window.location.href=''+baseurl+'/adminarea/profession.php';
}