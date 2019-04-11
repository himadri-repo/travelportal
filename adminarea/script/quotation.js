$(document).ready(function ()
{   
    $("#menu_quotation").addClass("active");
	get_all(0);	
    $("#btn_search").click(function()
	{ 
	   get_all(0);
	});	
	$('#txt_search').bind('keyup', function(e) 
	{
		get_all(0);
	});
	
	
});     


function get_all(start)
{
	
	var ctr=0,page_ctr=0;
	var msg='';
	ctr=(start*100)+1;
	from=(start*100)+1;
	$("#grid").html('');
	$("#grid").html('<tr ><td colspan="11" align="center"><img   src="'+baseurl+'/adminassets/images/loading.gif" alt="Uploading...."/></td></tr>');
	$("#div_pagination").html('');
    $.ajax({
				url: ''+baseurl+'/adminarea/ajax/quotation_sql.php',   	
				type: "POST",      	
				data: "tag=search"+"&start="+start+"&field="+$("#field").val()+"&value="+$("#user_id").val()+"&dt_from="+$("#dt_date_from").val()+"&dt_to="+$("#dt_date_to").val(),	
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
                                    $('#grid_row'+k+"").append("<td>"+ctr+"</td>");
                                    $('#grid_row'+k+"").append("<td>"+data[k]['request_date']+"</td>");									
									$('#grid_row'+k+"").append("<td>"+data[k]['trip_type']+"</td>");																		                                   																								                                    							
                                    $('#grid_row'+k+"").append("<td>"+data[k]['source_city']+"</td>");
									$('#grid_row'+k+"").append("<td>"+data[k]['destination_city']+"</td>");	
									$('#grid_row'+k+"").append("<td>"+data[k]['departure_date_time']+"</td>");	
									$('#grid_row'+k+"").append("<td>"+data[k]['departure_date_time1']+"</td>");
									$('#grid_row'+k+"").append("<td>"+data[k]['no_of_person']+"</td>");																
									$('#grid_row'+k+"").append("<td>"+data[k]['name']+"<br> ("+data[k]['user_id']+")<br>("+data[k]['customer_mobile']+")</td>");
									$('#grid_row'+k+"").append("<td>"+data[k]['seller']+"<br> ("+data[k]['seller_id']+")<br>("+data[k]['supplier_mobile']+")</td>");
									if(data[k]['status']=="PENDING")
									{
									$('#grid_row'+k+"").append("<td>"+									
										"<input type='radio' name='status"+k+"' id='status"+k+"1' onclick='update("+data[k]['id']+","+k+"1)' checked color='PENDING'>PENDING<br>"+
										"<input type='radio' name='status"+k+"'  id='status"+k+"2' onclick='update("+data[k]['id']+","+k+"2)' color='QUOTATION SENT'>QUOTATION SENT"+
									 "</td>");	
									} 
									else	
									{
									  $('#grid_row'+k+"").append("<td>"+	
									  "<input type='radio' name='status"+k+"' id='status"+k+"1' onclick='update("+data[k]['id']+","+k+"1)' color='PENDING'>PENDING<br>"+
									  "<input type='radio' name='status"+k+"'  id='status"+k+"2' onclick='update("+data[k]['id']+","+k+"1)' checked color='QUOTATION SENT'>QUOTATION SENT"+
									  "</td>");	
									}
									
														
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

function update(id,selecter_id)
{
	if(selecter_id==1)
	{
		selecter_id="01";
	}
	stat=$("#status"+selecter_id+"").attr("color");
	
	$.ajax({
				url: ''+baseurl+'/adminarea/ajax/quotation_sql.php',   	
				type: "POST",      	
				data: "tag=update"+"&status="+stat+"&id="+id,
				cache: false,							
				success: function(data)  		
				{				
					var data	=	$.parseJSON(data);										
					$.each(data, function(k,v) 
					{
						if(k=='success')  
						{
							alert(v)
						}							
					});
				}
	      });	
}
