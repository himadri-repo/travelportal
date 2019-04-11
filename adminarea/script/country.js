$(document).ready(function ()
{   
    localStorage.removeItem("country_id");    
    $("#country").addClass('active');
	$("#master").addClass('active');
	 $("#txt_search").focus();
	 $("#txt_search").bind("keyup",function(e)
	 {
		   get_all(0);
		   if($(this).val().length>0)
			 $("#result").html('showing results for "'+$(this).val()+'"');
		   else
			$("#result").html('');
	 });	 
	get_all(0);				
});     



function get_all(start)
{
	var ctr=0,page_ctr=0;
	var msg='';
	ctr=(start*100)+1;
	from=(start*100)+1;
	$("#grid").html('');
	$("#grid").html('<tr ><td colspan="4" align="center"><img   src="'+baseurl+'/adminassets/images/loading.gif" alt="Uploading...."/></td></tr>');
	$("#div_pagination").html('');
    $.ajax({
				url: ''+baseurl+'/adminarea/ajax/country_sql.php',   	
				type: "POST",      	
				data: "tag=search"+"&start="+start+"&s="+$("#txt_search").val(),	
				cache: false,							
				success: function(data)  		
				{					
					var data	=	$.parseJSON(data);	
					$("#grid").html('');
					$("#div_pagination").html('');						
					$.each(data, function(k,v) 
					{
					   if(k=='no_records')
							   {
                                    $("#info").html(''); 							   
									$("#grid").append("<tr></tr>"); 
						            $("#grid").append("<td colspan='4' align='center' style='color:red' > No Record To Display </td>");									
							   }

							   else if(k=='records')
							   {							    
							    if(v>1)
								{								    
							        if(v>3)
									{
									   var current_page=start;
									    current_page--;
										if(current_page<0)
									       $("#div_pagination").append("<li class='pagination_button previous disabled'><a style='cursor:pointer' >Previous</a></li>");
										else   
										   $("#div_pagination").append("<li class='pagination_button previous'><a style='cursor:pointer' onclick='get_all("+current_page+")'>Previous</a></li>");

									}
									var i=parseInt(start/3);
									if(i<=0)
									  i=1;
									else 
                                       i=(i*3)+1;									
									for(i;i<=v;i++)
									{
										if(page_ctr<3)
										{
										  if(start==parseInt(i-1))

												$("#div_pagination").append("<li class='pagination_button active'><a style='cursor:pointer' onclick='get_all("+parseInt(i-1)+")'>"+i+"</a></li>");
										  else
										     $("#div_pagination").append("<li class='pagination_button'><a style='cursor:pointer' onclick='get_all("+parseInt(i-1)+")'>"+i+"</a></li>");

										}
										page_ctr++;
									}    

									if(v>3)									 
									{
										var current_page=start;
										current_page++;
										if(current_page==v)
										 $("#div_pagination").append("<li class='pagination_button next disabled'><a style='cursor:pointer' >Next</a></li>");
										else										
										$("#div_pagination").append("<li class='pagination_button next'><a style='cursor:pointer' onclick='get_all("+current_page+")'>Next</a></li>");
									}								
								}		   
							   }                               							   
							   else if(k!='no_records' && k!='records' && k!='total')
							   {    							      
									$("#grid").append("<tr  id='grid_row"+k+"' ></tr>");
                                    $('#grid_row'+k+"").append("<td>"+ctr+"</td>");																										
									$('#grid_row'+k+"").append("<td>"+data[k]['country_name']+"</td>");																																		
									$('#grid_row'+k+"").append("<td>"+

															 "<div class='hidden-sm hidden-xs action-buttons'>"+														
																"<a class='green' style='cursor:pointer' href='country_form.php?id="+data[k]['id']+"'>"+
																	"<i class='ace-icon fa fa-pencil bigger-130'></i>"+
																"</a>"+
																"<a class='red' style='cursor:pointer' onclick='del("+data[k]['id']+")'>"+
																	"<i class='ace-icon fa fa-trash-o bigger-130'></i>"+
																"</a>"+
															"</div>"+
															"</td>");

									  ctr++;								
							   }
							   else
							   {	
							        ctr--;									   			
									   $("#info").html('showing '+from+' to '+ctr+' of '+v+'  Records');																								 
							   }

					   

					});

				}

            });	

}

function del(x)
{
 localStorage.setItem("country_id",x);
 var box=bootbox.dialog(
		{
			message:'Do You Want to Delete This Country ???',
			title: "Confirm",
			buttons:
			{				
				main: 
				{
					label: "OK",
					className: "btn-primary",
					callback: function() 
					{
					  del_record();
					}
				},
				cancel: 
				{
					label: "Cancel",
					className: "btn-primary",
					callback: function() 
					{
					}
				}	
			}
		});
}

function del_record()
{
  $.ajax
			({
				url: ''+baseurl+'/adminarea/ajax/country_sql.php',   	
				type: "POST",      	
				data: "tag=delete"+"&id="+localStorage.getItem("country_id"),      		
				cache: false,					  			
				success: function(data)  		
				{
					var data	=	$.parseJSON(data);	
						$.each(data, function(k,v) 
						{
						    if(k=='success')
							{
							    $("#header").find("#div_msg").remove();									
								$("#header").append('<div id="div_msg" style="color:#69aa46" class="alert alert-block alert-success col-xs-12 col-sm-12 ">'+v+'</div>'); 
								get_all(0);
								$("#txt_search").focus();
							}
                            else
                            {							   

                            }									
						});
				}
            });

}
