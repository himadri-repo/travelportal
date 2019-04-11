$(document).ready(function ()
{   
      $("#post_menu").addClass('active');
	$("#all_posts_menu").addClass('active');	
    if(localStorage.getItem("slider_id")==null)
    {
	  window.location.href=''+baseurl+'/adminarea/slider.php';
	}

	
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
	$("#btn_cancel").click(function()
	{
	   clear();
	})
		
	$("#file_image").on("change", function()
    {
	   upload();
	});
	
	$("#btn_remove").on("click", function()
    {
	   remove_image();
	});
           		
});
function upload()
{
        $("#preview").html('');
        var ext = $("#file_image").val().split('.').pop();
		if(ext=='png' || ext=='jpg' || ext=='jpeg' || ext=='bmp' || ext=='gif')
		{
		   var file_size=($("#file_image")[0].files[0].size/ 1048576);
		   
		   if(file_size<2)
		   {
		      $("#preview").html('<img src="'+baseurl+'/adminassets/images/loading.gif" alt="Uploading....">');
		      $.ajax
				({
					url: ''+baseurl+'/adminarea/ajax/upload_slider_image.php',    	
					type: "POST",      	
					data: new FormData(),	
					data:  new FormData(document.getElementById("frm_slider")),
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
								  $("#hid_image").val(v);
								  $("#btn_remove").show();
								  $("#preview").html('<img src="'+baseurl+'/upload/thumb/'+v+'" alt="Uploading....">');
								}
							});
                    }
                });					
		    }           
		   else		   
		    $("#preview").html('<span style="color:red">Your file is too large please use up to 2MB file</span>');
		}
		else
		 $("#preview").html('<span style="color:red">unsupported file type !!!</span>');
}

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
				url: ''+baseurl+'/adminarea/ajax/slider_sql.php',      	
				type: "POST",      	
				data: "slider_title="+$("#txt_title").val()+"&slider_sub_title="+$("#txt_sub_title").val()+"&slider_description="+CKEDITOR.instances.editor.getData()+"&slider_image_name="+$("#hid_image").val()+"&slider_id="+localStorage.getItem("slider_id")+"&tag=update",      		
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
				url: ''+baseurl+'/adminarea/ajax/slider_sql.php',   	
				type: "POST",      	
				data: "tag=edit"+"&slider_id="+localStorage.getItem("slider_id"),      		
				cache: false,					  			
				success: function(data)  		
				{
					var data	=	$.parseJSON(data);	
						$.each(data, function(k,v) 
						{						        
								$("#txt_title").val(v['slider_title']);
								$("#txt_sub_title").val(v['slider_sub_title']);
								CKEDITOR.instances.editor.setData(v['slider_description'])
								$("#hid_image").val(v['slider_image_name']);	
								if(v['slider_image_name']!='')
								{
					              $("#preview").html('<img src="'+baseurl+'/upload/thumb/'+v['slider_image_name']+'" alt="Uploading....">');
								  $("#btn_remove").show();
								}
                                else
								{
								  $("#preview").html('<img src="'+baseurl+'/adminassets/images/no_image.jpg" alt="Uploading....">');
								}								
								
								
								
								
								
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
  window.location.href=''+baseurl+'/adminarea/slider.php';
}