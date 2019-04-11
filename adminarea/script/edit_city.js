$(document).ready(function ()
{   	
    $("#menu_city").addClass('active');	
	$("#city").focus();	
	
	
    if(localStorage.getItem("city_id")==null)
    {
	  window.location.href=''+baseurl+'/adminarea/airline.php';
	}
	
		
	
	  get_record();	
	 $("#city").bind("keyup",function(e)
	{
	  if(($(this).val().length)>0)
	  {
	   $('#city').parent().find("#div_msg").remove();
	   $("#city").parent().removeClass('form-group has-error');
	  }
	  else
	  {
	    $('#city').parent().find("#div_msg").remove();
		$("#city").parent().addClass('form-group has-error');
		$("#city").parent().append('<div id="div_msg" style="color:#F87279" class="alert alert-block alert-danger col-xs-12 col-sm-12 ">City can not be left blank</div>');
		$("#city").parent().append('<div id="div_msg" style="color:#F87279" class="alert alert-block alert-danger col-xs-12 col-sm-12 ">Airline can not be left blank</div>');
		$("#city").focus();
	  }
	});
	
	$("#city").change(function(e)
	{
	  if(($(this).val().length)>0)
	  {
	   $('#city').parent().find("#div_msg").remove();
	   $("#city").parent().removeClass('form-group has-error');
	  }
	  else
	  {
	    $('#city').parent().find("#div_msg").remove();
		$("#city").parent().addClass('form-group has-error');
		$("#city").parent().append('<div id="div_msg" style="color:#F87279" class="alert alert-block alert-danger col-xs-12 col-sm-12 ">City can not be left blank</div>');
		$("#city").focus();
	  }
	});
	
	$("#code").bind("keyup",function(e)
	{
	  if(($(this).val().length)>0)
	  {
	   $('#code').parent().find("#div_msg").remove();
	   $("#code").parent().removeClass('form-group has-error');
	  }
	  else
	  {
	    $('#code').parent().find("#div_msg").remove();
		$("#code").parent().addClass('form-group has-error');
		$("#code").parent().append('<div id="div_msg" style="color:#F87279" class="alert alert-block alert-danger col-xs-12 col-sm-12 ">Code can not be left blank</div>');
		$("#code").focus();
	  }
	});
	
	$("#code").change(function(e)
	{
	  if(($(this).val().length)>0)
	  {
	   $('#code').parent().find("#div_msg").remove();
	   $("#code").parent().removeClass('form-group has-error');
	  }
	  else
	  {
	    $('#code').parent().find("#div_msg").remove();
		$("#code").parent().addClass('form-group has-error');
		$("#code").parent().append('<div id="div_msg" style="color:#F87279" class="alert alert-block alert-danger col-xs-12 col-sm-12 ">Code can not be left blank</div>');
		$("#code").focus();
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


function update()
{
   
        if($("#city").val()=='')
		{
			   
				$('#city').parent().find("#div_msg").remove();   
                $("#city").parent().addClass('form-group has-error');   				
				$('#city').parent().append('<div id="div_msg" style="color:#F87279" class="alert alert-block alert-danger col-xs-12 col-sm-12 ">Airline can not be left blank</div>');					
				$('#city').focus();
		}
		
	  else if($("#code").val()=='')
	  {
	    $('#code').parent().find("#div_msg").remove();
		$("#code").parent().addClass('form-group has-error');
		$("#code").parent().append('<div id="div_msg" style="color:#F87279" class="alert alert-block alert-danger col-xs-12 col-sm-12 ">Code can not be left blank</div>');
		$("#code").focus();
	  }
  else
  {
    
     $("#header").find("#div_msg").remove();	
     $("#header").append('<img src="'+baseurl+'/assets/images/loading.gif" alt="Uploading...." id="loading">');
     $.ajax
			({
				url: ''+baseurl+'/adminarea/ajax/city_sql.php',      	
				type: "POST",      	
				data: $("#frm_customer").serialize()+"&tag=update"+"&city_id="+localStorage.getItem("city_id"),  
				cache: false,					  			
				success: function(data)  		
				{
				   
					var data	=	$.parseJSON(data);	
						$.each(data, function(k,v) 
						{
                             if(k=='success')  
							  {	
                                $("#header").find("#loading").remove();							  
								$("#city").focus();
                                $("#header").find("#div_msg").remove();								
								$("#header").append('<div id="div_msg" style="color:#69aa46" class="alert alert-block alert-success col-xs-12 col-sm-12 ">'+v+'</div>');
								clear1();
							  }
							  if(k=='error')
							  {
							      $("#header").find("#loading").remove();
								  $("#header").find("#div_msg").remove();
								  $("#city").focus();
								  $("#header").append('<div id="div_msg" style="color:#69aa46" class="alert alert-block alert-success col-xs-12 col-sm-12 ">'+v+'</div>');
								  
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
				url: ''+baseurl+'/adminarea/ajax/city_sql.php',   	
				type: "POST",      	
				data: "tag=edit"+"&city_id="+localStorage.getItem("city_id"),          		
				cache: false,					  			
				success: function(data)  		
				{
					var data	=	$.parseJSON(data);	
						$.each(data, function(k,v) 
						{			
                                				
								  $("#city").val(v['city']);
								  $("#code").val(v['code']);
								  $("#hid_image").val(v['image']);
                               								
								
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
