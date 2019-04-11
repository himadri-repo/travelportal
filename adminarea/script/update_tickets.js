$(document).ready(function ()
{   
    
    $("#menu_update_tickets").addClass("active");
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
	   if($(this).val()=="t.approved")
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
	
	$("#chk_all").click(function()
	{
	   if($(this).attr('checked')==true)
	   {
		   $('#grid tr input[type=checkbox]').each(function()
		   {
				  
			 $(this).attr('checked', true);
			 

		   });
		 
	   }
	   if($(this).attr('checked')==false)
	   {
		 
		   $('#grid tr input[type=checkbox]').each(function()
		   {

			 $(this).attr('checked', false);
			
		   });
		  
		  
	   }
	   $("#hid_ids").val(arr);
	   
	   
	});	
});     


function get_all(start)
{
	if($("#field").val()=="t.approved")
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
	$("#grid").html('<tr ><td colspan="9" align="center"><img   src="'+baseurl+'/adminassets/images/loading.gif" alt="Uploading...."/></td></tr>');
	$("#div_pagination").html('');
    $.ajax({
				url: ''+baseurl+'/adminarea/ajax/tickets_sql.php',   	
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
                                    
                                     $('#grid_row'+k+"").append("<td>"+data[k]['id']+"</td>");
                                    $('#grid_row'+k+"").append("<td>"+data[k]['pnr']+"</td>");
									$('#grid_row'+k+"").append("<td>"+data[k]['trip_type']+"</td>");
									$('#grid_row'+k+"").append("<td ><i class='fa fa-plane' style='font-size:14px;height:auto;width:auto;border-radius:0;background:none;padding-top:0;margin-right:10px'></i>"+data[k]['departure_date_time']+"<br/><i class='fa fa-plane' style='transform:rotate(83deg);font-size:14px;height:auto;width:auto;border-radius:0;background:none;padding-top:0;margin-right:10px'></i>"+data[k]['arrival_date_time']+"</td>");		
									if(data[k]['trip_type']=="RETURN TRIP")
									{
										 $('#grid_row'+k+"").append("<td><i class='fa fa-plane' style='font-size:14px;height:auto;width:auto;border-radius:0;background:none;padding-top:0;margin-right:10px'></i>"+data[k]['departure_date_time1']+"<br/><i class='fa fa-plane' style='transform:rotate(83deg);font-size:14px;height:auto;width:auto;border-radius:0;background:none;padding-top:0;margin-right:10px'></i>"+data[k]['arrival_date_time1']+"</td>");		
									}	
                                    else
									{
										 $('#grid_row'+k+"").append("<td></td>");		
									}										
                                   																								                                    									
                                    $('#grid_row'+k+"").append("<td>"+data[k]['source_city']+"<br/>"+data[k]['destination_city']+"</td>");									
									
									$('#grid_row'+k+"").append("<td><b>Supplier Rate : </b>"+data[k]['total']+"<br> <b>Portal Rate : </b>"+parseFloat(parseFloat(data[k]['total'])+parseFloat(data[k]['admin_markup']))+"</td>");									
									$('#grid_row'+k+"").append("<td>"+data[k]['no_of_person']+"</td>");
									
									$('#grid_row'+k+"").append("<td>"+data[k]['name']+" <br/>( "+data[k]['uid']+" ) "+"</td>");
										$('#grid_row'+k+"").append("<td>"+data[k]['availibility']+"</td>");
									$('#grid_row'+k+"").append("<td>"+data[k]['approved']+"</td>");
									$('#grid_row'+k+"").append("<td>"+
									
									 "<div class='hidden-sm hidden-xs action-buttons'>"+														
									
										"<a class='red' style='cursor:pointer'  href='update_ticket_details.php?id="+data[k]['id']+"'>"+
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

function validate_ids()
{
	var ctr=0;
	$('#grid tr input[type=checkbox]').each(function()
   {
		  
	 if($(this).attr('checked')==true)
	 {
		 ctr++;
	 }
	

   });
   if(ctr>0)
	   return true;
   else
   {
	  alert("Please Select Ticket Before Update");
	  return false;
   }
}


