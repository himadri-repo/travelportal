$(document).ready(function ()
{   		 
    if(localStorage.getItem("post_id")==null)
    {
	  window.location.href=''+baseurl+'/adminarea/post.php';
	}
	 $("#content").addClass('active');
	$("#post_menu").addClass('active');
	
	$("#txt_title").focus();
	$("#btn_remove").hide();
	
	
	get_record();
	
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
		$("#txt_title").parent().append('<div id="div_msg" style="color:#F87279" class="alert alert-block alert-danger col-xs-12 col-sm-12 ">Title can not be left blank !!!</div>');
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
    $("#txt_title").parent().append('<div id="div_msg" style="color:#F87279" class="alert alert-block alert-danger col-xs-12 col-sm-12 ">Title can not be left blank !!!</div>');
    $("#txt_title").focus();            
  }
  else
  {
     $("#header").find("#div_msg").remove();	
     $("#header").append('<img src="'+baseurl+'/adminassets/images/loading.gif" alt="Uploading...." id="loading">');
     $.ajax
			({
				url: ''+baseurl+'/adminarea/ajax/post_sql.php',      	
				type: "POST",      	
				data: "title="+$("#txt_title").val()+"&description="+CKEDITOR.instances.editor.getData()+"&tag=update&post_id="+localStorage.getItem("post_id"),      		
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
                              else
							  {							    
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
				url: ''+baseurl+'/adminarea/ajax/post_sql.php',   	
				type: "POST",      	
				data: "tag=edit"+"&post_id="+localStorage.getItem("post_id"),      		
				cache: false,					  			
				success: function(data)  		
				{
					var data	=	$.parseJSON(data);	
						$.each(data, function(k,v) 
						{						        
								$("#txt_title").val(v['title']);				
								CKEDITOR.instances.editor.setData(v['description'])
														
								
								
								
								
								
						});
						return false;
				}
            });	
}
function remove_image()
{
  $("#file_image").val('');
  $("#preview").html('');
  $("#hid_image").val('');
  $("#btn_remove").hide();
}

function clear()
{
  
  $("#txt_title").val('');
  $("#txt_sub_title").val('');
  $("#file_image").val('');
  CKEDITOR.instances.editor.setData('');
  $("#preview").html('');
  $("#txt_title").focus();
  $("#div_msg").remove();
  $("#txt_title").parent().removeClass('form-group has-error');
  window.location.href=''+baseurl+'/adminarea/post.php';
}