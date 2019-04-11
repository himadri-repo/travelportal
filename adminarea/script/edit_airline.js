$(document).ready(function ()
{   	
    $("#menu_airline").addClass('active');	
	$("#airline").focus();	
	
	
    if(localStorage.getItem("airline_id")==null)
    {
	  window.location.href=''+baseurl+'/adminarea/airline.php';
	}
	
		
	
	  get_record();	
	 $("#airline").bind("keyup",function(e)
	{
	  if(($(this).val().length)>0)
	  {
	   $('#airline').parent().find("#div_msg").remove();
	   $("#airline").parent().removeClass('form-group has-error');
	  }
	  else
	  {
	    $('#airline').parent().find("#div_msg").remove();
		$("#airline").parent().addClass('form-group has-error');
		$("#airline").parent().append('<div id="div_msg" style="color:#F87279" class="alert alert-block alert-danger col-xs-12 col-sm-12 ">Airline can not be left blank</div>');
		$("#airline").focus();
	  }
	});
	
	$("#aircode").change(function(e)
	{
	  if(($(this).val().length)>0)
	  {
	   $('#aircode').parent().find("#div_msg").remove();
	   $("#aircode").parent().removeClass('form-group has-error');
	  }
	  else
	  {
	    $('#aircode').parent().find("#div_msg").remove();
		$("#aircode").parent().addClass('form-group has-error');
		$("#aircode").parent().append('<div id="div_msg" style="color:#F87279" class="alert alert-block alert-danger col-xs-12 col-sm-12 ">Aircode can not be left blank</div>');
		$("#aircode").focus();
	  }
	});
	
	$("#btn_update").click(function()
	{
	   update();
	});
	$("#file_image").on("change", function()
    {
	   upload();
	});
	$("#btn_cancel").click(function()
	{
	   clear();
	})
		
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
		      $("#preview").html('<img src="'+baseurl+'/assets/images/loading.gif" alt="Uploading....">');
		      $.ajax
				({
					url: ''+baseurl+'/adminarea/ajax/upload_faculty_image.php',    	
					type: "POST",      	
					data: new FormData(),	
					data:  new FormData(document.getElementById("frm_customer")),
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
   
        if($("#airline").val()=='')
		{
			   
				$('#airline').parent().find("#div_msg").remove();   
                $("#airline").parent().addClass('form-group has-error');   				
				$('#airline').parent().append('<div id="div_msg" style="color:#F87279" class="alert alert-block alert-danger col-xs-12 col-sm-12 ">Airline can not be left blank</div>');					
				$('#airline').focus();
		}
		
	  else if($("#aircode").val()=='')
	  {
	    $('#aircode').parent().find("#div_msg").remove();
		$("#aircode").parent().addClass('form-group has-error');
		$("#aircode").parent().append('<div id="div_msg" style="color:#F87279" class="alert alert-block alert-danger col-xs-12 col-sm-12 ">Aircode can not be left blank</div>');
		$("#aircode").focus();
	  }
  else
  {
    
     $("#header").find("#div_msg").remove();	
     $("#header").append('<img src="'+baseurl+'/assets/images/loading.gif" alt="Uploading...." id="loading">');
     $.ajax
			({
				url: ''+baseurl+'/adminarea/ajax/airline_sql.php',      	
				type: "POST",      	
				data: $("#frm_customer").serialize()+"&tag=update"+"&airline_id="+localStorage.getItem("airline_id")+"&image="+$("#hid_image").val(),  
				cache: false,					  			
				success: function(data)  		
				{
				   
					var data	=	$.parseJSON(data);	
						$.each(data, function(k,v) 
						{
                             if(k=='success')  
							  {	
                                $("#header").find("#loading").remove();							  
								$("#airline").focus();
                                $("#header").find("#div_msg").remove();								
								$("#header").append('<div id="div_msg" style="color:#69aa46" class="alert alert-block alert-success col-xs-12 col-sm-12 ">'+v+'</div>');
								clear1();
							  }
							  if(k=='error')
							  {
							      $("#header").find("#loading").remove();
								  $("#header").find("#div_msg").remove();
								  $("#airline").focus();
								  $("#header").append('<div id="div_msg" style="color:#69aa46" class="alert alert-block alert-success col-xs-12 col-sm-12 ">'+v+'</div>');
								  $("#airline").focus();
							  }
                              	
						});
				}
            });	
  }
}
function get_record()
{
   $("#preview").html('<img src="'+baseurl+'/assets/images/loading.gif" alt="Uploading....">');
   $.ajax
			({
				url: ''+baseurl+'/adminarea/ajax/airline_sql.php',   	
				type: "POST",      	
				data: "tag=edit"+"&airline_id="+localStorage.getItem("airline_id"),          		
				cache: false,					  			
				success: function(data)  		
				{
					var data	=	$.parseJSON(data);	
						$.each(data, function(k,v) 
						{			
                                				
								  $("#airline").val(v['airline']);
								  $("#aircode").val(v['aircode']);
								  $("#hid_image").val(v['image']);
                               								
								if(v['image']!='')
								{
					              $("#preview").html('<img src="'+baseurl+'/upload/thumb/'+v['image']+'" alt="Uploading....">');
								  $("#btn_remove").show();
								}
                                else
								{
								  $("#preview").html('<img src="'+baseurl+'/assets/images/no_image.jpg" alt="Uploading....">');
								}								
								  								  								 								 
								 
								
								
						});
						return false;
				}
            });	
}

function isNumber(evt) 
{
        var iKeyCode = (evt.which) ? evt.which : evt.keyCode
        if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
            return false;

        return true;
}
function remove_image()
{
  $("#file_image").val('');
  $("#preview").html('');
  $("#hid_image").val('');
  $("#btn_remove").hide();
}
