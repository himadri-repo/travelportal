$(document).ready(function ()
{   $("#faq").addClass('active');
	$("#post_menu").addClass('active');
		
	$("#txt_title").focus();
	
	
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
		$("#txt_title").parent().append('<div id="div_msg" style="color:#F87279" class="alert alert-block alert-danger col-xs-12 col-sm-12 ">Question can not be left blank !!!</div>');
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
    $("#txt_title").parent().append('<div id="div_msg" style="color:#F87279" class="alert alert-block alert-danger col-xs-12 col-sm-12 ">Question can not be left blank !!!</div>');
    $("#txt_title").focus();            
  }
  else
  {
     $("#header").find("#div_msg").remove();	
     $("#header").append('<img src="'+baseurl+'/adminassets/images/loading.gif" alt="Uploading...." id="loading">');
     $.ajax
			({
				url: ''+baseurl+'/adminarea/ajax/faq_sql.php',      	
				type: "POST",      	
				data: "question="+$("#txt_title").val()+"&description="+CKEDITOR.instances.editor.getData()+"&tag=insert",      		
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
                              	
						});
				}
            });		
  }
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
  $("#btn_remove").hide();
  $("#txt_title").parent().removeClass('form-group has-error');
  window.location.href=''+baseurl+'/adminarea/faq.php';
}
function clear1()
{
  
  $("#txt_title").val('');
  $("#txt_sub_title").val('');  
  $("#file_image").val('');
  CKEDITOR.instances.editor.setData('');
  $("#preview").html('');
  $("#txt_title").focus();
  $("#btn_remove").hide();
  $("#txt_title").parent().removeClass('form-group has-error');
  
}