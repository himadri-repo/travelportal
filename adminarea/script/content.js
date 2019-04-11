$(document).ready(function ()
{   
    localStorage.removeItem("content_id");    
    $("#content").addClass('active');
	$("#all_page_menu").addClass('active');
	
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
	ctr=(start*10)+1;
	from=(start*10)+1;
	$("#grid").html('');
	$("#grid").html('<tr ><td colspan="4" align="center"><img   src="'+baseurl+'assets/images/loading.gif" alt="Uploading...."/></td></tr>');
	$("#div_pagination").html('');
    $.ajax({
				url: ''+baseurl+'/adminarea/ajax/content_sql.php',   	
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
									
									$('#grid_row'+k+"").append("<td>"+data[k]['title']+"</td>");									
									$('#grid_row'+k+"").append("<td>"+data[k]['description']+"</td>");								
									$('#grid_row'+k+"").append("<td>"+
															 "<div class='hidden-sm hidden-xs action-buttons'>"+														
																"<a class='green' style='cursor:pointer' onclick='edit("+data[k]['id']+")'>"+
																	"<i class='ace-icon fa fa-pencil bigger-130'></i>"+
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

function edit(x)
{
    
    localStorage.setItem("content_id",x);
	window.location.href=''+baseurl+'/adminarea/edit_content.php';
    	
}