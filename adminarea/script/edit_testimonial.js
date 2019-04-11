$(document).ready(function ()
{   		 
    if(localStorage.getItem("testimonial_id")==null)
    {
	  window.location.href=''+baseurl+'/adminarea/testimonial.php';
	}
	$("#testimonial").addClass('active');
	$("#post_menu").addClass('active');
	
	$("#txt_title").focus();
	$("#btn_remove").hide();

	
	get_record();
	
	
	
	$("#btn_update").click(function()
	{
	   update();
	});
	
           		
});

function update()
{  
     $("#header").find("#div_msg").remove();	
     $("#header").append('<img src="'+baseurl+'/adminassets/images/loading.gif" alt="Uploading...." id="loading">');
     $.ajax
			({
				url: ''+baseurl+'/adminarea/ajax/testimonial_sql.php',      	
				type: "POST",      	
				data: "approved="+$("#approved").val()+"&description="+CKEDITOR.instances.editor.getData()+"&tag=update&testimonial_id="+localStorage.getItem("testimonial_id"),      		
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


function get_record()
{
    
    
   $("#preview").html('<img src="'+baseurl+'/adminassets/images/loading.gif" alt="Uploading....">');
       $.ajax
			({
				url: ''+baseurl+'/adminarea/ajax/testimonial_sql.php',   	
				type: "POST",      	
				data: "tag=edit&testimonial_id="+localStorage.getItem("testimonial_id"),      		
				cache: false,					  			
				success: function(data)  		
				{
					var data	=	$.parseJSON(data);	
						$.each(data, function(k,v) 
						{
						       
								$("#approved").val(v['approved']);				
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
  window.location.href=''+baseurl+'/adminarea/testimonial.php';
}