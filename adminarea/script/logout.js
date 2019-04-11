$(document).ready(function ()
{       
    $("#btn_logout").click(function()
	{	  
		$.ajax({
					url: ''+baseurl+'/adminarea/ajax/logout_sql.php',	
					type: "POST", 
					data:"tag=logout",
					cache: false,							
					success: function(data)  		
					{
						var data	=	$.parseJSON(data);	
						$.each(data, function(k,v) 
						{
							  if(k=='logout')
							  {
								window.location.href=""+baseurl+"/adminarea/index.php";
								localStorage.setItem("msg",v);
								return false;
								
							  }
							  
						});
					}
			});		
	});
});     