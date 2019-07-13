$(document).ready(function ()
{   
    $("#menu_bookings").addClass("active");
	get_all(0);	
    $("#btn_search").click(function()
	{ 
	   get_all(0);
	});	
	$('#txt_search').bind('keyup', function(e) 
	{
		get_all(0);
	});
	
	$("#field").change(function()
	{
	   if($(this).val()=="b.status")
	   {
		   $("#div_status").show();
		   $("#div_user").hide();
	   }
       if($(this).val()=="u.id")
	   {
		   $("#div_status").hide();
		   $("#div_user").show();
	   }		   
	});
});     


function get_all(start)
{
	if($("#field").val()=="b.status")
	{
	  value=$("#status").val();	
	}
	else if($("#field").val()=="u.id")
	{
		value=$("#user_id").val();	
	}
	else
	{
		value="";
	}
	var ctr=0,page_ctr=0;
	var msg='';
	ctr=(start*100)+1;
	from=(start*100)+1;
	$("#grid").html('');
	$("#grid").html('<tr ><td colspan="11" align="center"><img   src="'+baseurl+'/adminassets/images/loading.gif" alt="Uploading...."/></td></tr>');
	$("#div_pagination").html('');
    $.ajax({
				url: ''+baseurl+'/adminarea/ajax/booking_sql.php',   	
				type: "POST",      	
				data: "tag=search"+"&start="+start+"&field="+$("#field").val()+"&value="+value+"&dt_from="+$("#dt_date_from").val()+"&dt_to="+$("#dt_date_to").val(),	
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
						            $("#grid").append("<td colspan='11' align='center' style='color:red' > No Record To Display </td>");									
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
							        var address='';
									$("#grid").append("<tr  id='grid_row"+k+"' ></tr>");
                                    $('#grid_row'+k+"").append("<td>"+data[k]['id']+"</td>");
                                    $('#grid_row'+k+"").append("<td>"+data[k]['date']+"</td>");
									/*$('#grid_row'+k+"").append("<td>"+data[k]['pnr']+"</td>");*/
									$('#grid_row'+k+"").append("<td>"+data[k]['trip_type']+"</td>");
																		
                                   																								                                    									
                                    $('#grid_row'+k+"").append("<td>"+data[k]['source_city']+"<br/>"+data[k]['destination_city']+"</td>");									
									$('#grid_row'+k+"").append("<td>"+data[k]['departure_date_time']+"</td>");		
									/*$('#grid_row'+k+"").append("<td>"+data[k]['rate']+"</td>");									
									$('#grid_row'+k+"").append("<td>"+data[k]['qty']+"</td>");
									$('#grid_row'+k+"").append("<td>"+data[k]['service_charge']+"</td>");
									$('#grid_row'+k+"").append("<td>"+data[k]['igst']+"</td>");	*/	
									$('#grid_row'+k+"").append("<td>"+data[k]['qty']+"</td>");
									$('#grid_row'+k+"").append("<td>"+data[k]['total']+"</td>");								
									$('#grid_row'+k+"").append("<td>"+data[k]['name']+"<br> ("+data[k]['user_id']+")</td>");
									$('#grid_row'+k+"").append("<td>"+data[k]['seller']+"<br> ("+data[k]['seller_id']+")</td>");
									$('#grid_row'+k+"").append("<td>"+data[k]['status']+"</td>");
									//if(data[k]['user_id']!=data[k]['seller_id'])
									{
										$('#grid_row'+k+"").append("<td>"+									
										 "<div class='hidden-sm hidden-xs action-buttons'>"+														
										
											"<a class='red' style='cursor:pointer'  href='edit_booking_details.php?id="+data[k]['id']+"'>"+
												"<i class='ace-icon fa fa-eye bigger-130'></i>"+
											"</a>"+
										"</div>"+
										
										"</td>");
									}
									// else
									// {
									// 	$('#grid_row'+k+"").append("<td></td>");
									// }
									
														
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

