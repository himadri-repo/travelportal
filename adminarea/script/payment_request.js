$(document).ready(function ()
{   
    
	$("#txt_search").focus();
	
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
	$("#grid").html('<tr ><td colspan="9" align="center"><img   src="'+baseurl+'/adminassets/images/loading.gif" alt="Uploading...."/></td></tr>');
	$("#div_pagination").html('');
    $.ajax({
				url: ''+baseurl+'/adminarea/ajax/payment_request_sql.php',   	
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
						            $("#grid").append("<td colspan='9' align='center' style='color:red' > No Record To Display </td>");									
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
                                    $('#grid_row'+k+"").append("<td>"+data[k]['uid']+"</td>");	
									$('#grid_row'+k+"").append("<td>"+data[k]['name']+"</td>");	
									$('#grid_row'+k+"").append("<td>"+data[k]['request_date']+"</td>");		
                                    $('#grid_row'+k+"").append("<td>"+data[k]['payment_type']+"</td>");										
																                                    
									if(data[k]['payment_type']=="NEFT")
										$('#grid_row'+k+"").append("<td> REFRENCE ID "+data[k]['refrence_id']+"</td>");	
									if(data[k]['payment_type']=="CHEQUE")
										$('#grid_row'+k+"").append("<td> BANK  "+data[k]['bank']+", Cheuqe No. "+data[k]['cheque_no']+"</td>");	
									if(data[k]['payment_type']=="CASH")
										$('#grid_row'+k+"").append("<td> BANK  "+data[k]['bank']+", Acc No. "+data[k]['account_no']+"</td>");	

									$('#grid_row'+k+"").append("<td>"+data[k]['amount']+"</td>");	
									$('#grid_row'+k+"").append("<td>"+data[k]['status']+"</td>");
									
									if(data[k]['status']=='Pending')
									{
										$('#grid_row'+k+"").append("<td>"+										
										"<div class='hidden-sm hidden-xs action-buttons'>"+														
											"<a class='green' style='cursor:pointer' href='payment_request.php?user_id="+data[k]['user_id']+"&id="+data[k]['id']+"&amount="+data[k]['amount']+"'>"+
												"Pay"+
											"</a>"+										
										"</div>"+
									    "</td>");
									}
									else
									{
										$('#grid_row'+k+"").append("<td></td>");	
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
