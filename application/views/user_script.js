$( document ).ready(function() 
{
   $("#destination1").val($("#source").val());	
   $("#source1").val($("#destination1").val());	
   $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
        showMeridian: 1
    });
	
	$("#trip_type").change(function()
	{
		
		if($(this).val()=="ONE")
		{
			$("#div_returns").hide();
		}
		if($(this).val()=="ROUND")
		{
			$("#div_returns").show();
		}
	});
	
	$("#airline").change(function()
	{
		
		$("#aircode").val($("#airline option:selected" ).attr("color"));
	});
	$("#airline1").change(function()
	{
		
		$("#aircode1").val($("#airline option:selected" ).attr("color"));
	});
	
    $('#btn_register').on('click', function (e) 
	{
        
        $("#status").html("<center><img src='"+baseurl+"/images/loader-orange.gif'></center>");
        $.ajax({
            type: "POST",
            url: ""+baseurl+"User_Controller/do_register", 
            data: $("#form_registration").serialize(),
            dataType: "json",  
            success: function(data)
			{
				
                $.each(data, function(key, value) 
				{  
                    $("#status").html("");				
                    if(key=="success")
					{						
						$('#form_registration input').each(function()
						{ 
							$(this).val("");								
						});
						$("#status").html("<div class='alert alert-success'>"+value+"</div>");
					}
					else
					{
						$("#status").html("");
						if(value!="")
						{
							
							$('#'+key+"").addClass('is-invalid');
							$('#'+key+"").parent().find(".error").remove();
							$('#'+key+"").parent().append(value);
							
							
						}
					}
                });
            }
        });
    });
	
	$('#form_registration input').on('keyup', function () 
	{ 
        $(this).removeClass('is-invalid').addClass('is-valid');
        $(this).parent().find(".error").remove();
    });
	
	$('#btn_login').on('click', function (e) 
	{
        e.preventDefault();
        $("#status").html("<center><img src='"+baseurl+"/images/loader-orange.gif'></center>");
        $.ajax({
            type: "POST",
            url: ""+baseurl+"User_Controller/do_login", 
            data: $("#form_login").serialize(),
            dataType: "json",  
            success: function(data)
			{
				
                $.each(data, function(key, value) 
				{  
                    $("#status").html("");				
                    if(key=="success")
					{						
						$('#form_login input').each(function()
						{ 
							$(this).val("");								
						});
						$("#status").html("<div class='alert alert-success'>"+value+"</div>");
						window.location.href=""+baseurl+"user";
					}
					else if(key=="error")
					{
						$("#status").html("<div class='alert alert-danger'>"+value+"</div>");
					}
					else
					{
						$("#status").html("");
						if(value!="")
						{
							
							$('#'+key+"").addClass('is-invalid');
							$('#'+key+"").parent().find(".error").remove();
							$('#'+key+"").parent().append(value);
							
							
						}
					}
                });
            }
        });
    });
	
	
	$('#btn_update').on('click', function (e) 
	{
        
        $("#status").html("<center><img src='"+baseurl+"/images/loader-orange.gif'></center>");
        $.ajax({
            type: "POST",
            url: ""+baseurl+"User_Controller/update", 
            data: {name:$("#name").val(),
			mobile:$("#mobile").val()},
            dataType: "json",  
            success: function(data)
			{
				
                $.each(data, function(key, value) 
				{  
                    $("#status").html("");				
                    if(key=="success")
					{												
						$("#status").html("<div class='alert alert-success'>"+value+"</div>");
						setTimeout(function()
						{
							window.location.href=""+baseurl+"user";
						},1000);
						
						
						
					}
					else
					{
						$('#edit-profile').modal({backdrop: 'static', keyboard: false}) 
						$("#status").html("");
						if(value!="")
						{
							
							$('#'+key+"").addClass('is-invalid');
							$('#'+key+"").parent().find(".error").remove();
							$('#'+key+"").parent().append(value);
							
							
						}
					}
                });
            }
        });
    });
	$('#edit-profile input').on('keyup', function () 
	{ 
        $(this).removeClass('is-invalid').addClass('is-valid');
        $(this).parent().find(".error").remove();
    });
	
	
	$("#profile_image").change(function()
	{
			
			 
			 
			if($("#profile_image").val().length==0)
			{
				$("#profile_image").parent().find("#div_err").remove();
				$("#profile_image").parent().append('<div id="div_err" class="alert alert-danger" role="alert">Please Select Image !!!</div>');
				return false;
			}
			else
			{
				   	$("#user-img").css('width','50px');				   
				    $("#user-img").attr('src',baseurl+'images/loader-orange.gif');					   
					$("#profile_image").parent().find("#div_err").remove();
					$.ajax
					({
						url:''+baseurl+'User_Controller/uploadimg',  	
						type: "POST", 
						data: new FormData(),	
						data:  new FormData(document.getElementById("frm_profile_image")),
						contentType: false,       		
						cache: false,					
						processData:false,  							
						success: function(data)  		
						{
							var data	=	$.parseJSON(data);	
							$.each(data, function(k,v) 
							{
								  if(k=='error')
								  {
									$("#profile_image").parent().find("#div_err").remove();
									$("#profile_image").parent().removeClass("form-group has-error");
									$("#profile_image").parent().append('<div id="div_err" class="alert alert-danger" role="alert">'+v+'</div>');
								  }	
								 else 
								 {
									
									for(i in v)
									{
										if(i=='file_name')
										{
											$("#user-img").css('width','250px');									
											
											$("#user-img").attr('src',baseurl+'upload/thumb/'+v[i]);
											$("#profile_image").val('');
											
										}	
										else
										{										
											$("#profile_image").parent().find("#div_err").remove();
											$("#profile_image").parent().removeClass("form-group has-error");
											$("#profile_image").parent().addClass("form-group has-success");
											$("#profile_image").parent().append('<div id="div_err" class="alert alert-success" role="alert">'+v[i]+'</div>');
											$("#profile_image").parent().find("#div_err").fadeOut(2000);
											
										}
									}
								  }	
								  
								  
							});
							
						},
						error: function(xhr, status, error){
							var errorMessage = xhr.status + ': ' + xhr.statusText;
							//alert('Error - ' + errorMessage);
							console.log(errorMessage);
						}						
					});
					return false;
				
			}

	});
	
	
	  

    $('#frm_ticket input').on('keyup', function () 
	{ 
        $(this).removeClass('is-invalid').addClass('is-valid');
        $(this).parent().find(".error").remove();
    });
	$('#frm_ticket select').on('change', function () 
	{ 
        $(this).removeClass('is-invalid').addClass('is-valid');
        $(this).parent().find(".error").remove();
    });
	$('#frm_ticket input').on('blur', function () 
	{ 
        $(this).removeClass('is-invalid').addClass('is-valid');
        $(this).parent().find(".error").remove();
    });
	
	$(".btn_book_bckend").click(function()
	{
		$("#frm_book_ticket").attr('action',''+baseurl+'search/flightdetails/'+$(this).attr("color")+'');
	});
	$(".btn_booking_cancel").click(function()
	{
		$("#frm_booking_cancel").attr('action',''+baseurl+'search/customer_cancel_request/'+$(this).attr("color")+'');
	});
	$(".btn_approve_canel").click(function()
	{
		$("#frm_approve_cancel").attr('action',''+baseurl+'search/approve_cancel/'+$(this).attr("color")+'');
	});
	$("#btn_markup_bckend").click(function()
	{
		
		$("#hid_markup_ticket_id").val($(this).attr("color"));
	});
	
	$('#btn_add_markup').on('click', function (e) 
	{
	   if($("#markup_value").val()=="" )
		{
			$("#markup_value").addClass('is-invalid');
			$("#markup_value").parent().find(".error").remove();
			$("#markup_value").parent().append("<div class='error'>Please Enter Markup Value</div>");
		}
		else
		{
			$("#markup_status").html("<center><img src='"+baseurl+"/images/loader-orange.gif'></center>");
			$.ajax({
				type: "POST",
				url: ""+baseurl+"User_Controller/addmarkup",           
				data:$("#frm_markup").serialize(),
				dataType: "json",  
				success: function(data)
				{
					
					$.each(data, function(key, value) 
					{  
						$("#markup_status").html("");				
						if(key=="success")
						{												
							$("#markup_status").html("<div class='alert alert-success'>"+value+"</div>");
							$('#frm_markup input').each(function () 
							{ 
								$(this).val("");								
							});
							
							
						}
						else
						{
							alert(value)
							e.preventDefault();
							$('#add-markup').modal({backdrop: 'static', keyboard: false}) 
							$("#markup_status").html("<div class='alert alert-danger'>"+value+"</div>");
							
						}
					});
				}
			});
		}
	});	
	
	$('#btn_make_payment').on('click', function (e) 
	{
	    if($("#amount").val()=="" )
		{
			$("#amount").addClass('is-invalid');
			$("#amount").parent().find(".error").remove();
			$("#amount").parent().append("<div class='error'>Please Enter Amount</div>");
		}		
		else
		{
			$("#make_payment_status").html("<center><img src='"+baseurl+"/images/loader-orange.gif'></center>");
			$.ajax({
				type: "POST",
				url: ""+baseurl+"User_Controller/makepayment",           
				data:$("#frm_make_payment").serialize(),
				dataType: "json",  
				success: function(data)
				{
					
					$.each(data, function(key, value) 
					{  
						$("#make_payment_status").html("");				
						if(key=="success")
						{												
							$("#make_payment_status").html("<div class='alert alert-success'>"+value+"</div>");
							$('#frm_make_payment input').each(function () 
							{ 
								$(this).val("");								
							});
							
							
						}
						else
						{
							alert(value)
							e.preventDefault();
							$('#make-payment').modal({backdrop: 'static', keyboard: false}) 
							$("#make_payment_status").html("<div class='alert alert-danger'>"+value+"</div>");
							
						}
					});
				}
			});
		}
	});	
	
	$("#payment_type").change(function()
	{
		if($(this).val()=="CHEQUE")
		{
			$("#div_bank").show();
			$("#div_cheque_no").show();
			$("#div_account_no").hide();
			$("#div_refrence_id").hide();
		}
		
		if($(this).val()=="NEFT")
		{
			$("#div_bank").hide();
			$("#div_cheque_no").hide();
			$("#div_account_no").hide();
			$("#div_refrence_id").show();
		}
		
		if($(this).val()=="BANK")
		{
			$("#div_bank").show();
			$("#div_cheque_no").hide();
			$("#div_account_no").show();
			$("#div_refrence_id").hide();
		}
	});
	
	$("#source").change(function()
	{
		$("#destination1").val($("#source").val());
	});
	
	$("#destination").change(function()
	{
		$("#source1").val($("#destination").val());
	});
	
	$("#source1").change(function()
	{
		$("#destination").val($("#source1").val());
	});
	
	$("#destination1").change(function()
	{
		$("#source").val($("#destination1").val());
	});
	
	$("#no_of_stops").change(function()
	{
		if($("#no_of_stops").val()>0)
		{
			$("#div_stop").show();
			$("#div_stop").html("");
			var div='<div class="col-xs-6 col-sm-6">'+
						'<label>Enter Stop Name</label>'+
						'</div>'+
						'<div class="col-xs-6 col-sm-6">'+
							'<div class="form-group">'+												
								'<input type="text" class="form-control" name="stop_name[]"  placeholder="Stop Name" />'+
							'</div>'+
						'</div>';
						
			for(var i=0;i<$("#no_of_stops").val();i++)
			{
				$("#div_stop").append(div);
			}				
		}
		else
		{
			$("#div_stop").hide();
		}
	});
	
		
});

function calculate(evt)
{
	
		
	var price=$("#price").val();
	var markup=$("#markup").val();	
	var discount=$("#discount").val();	
	
	if(price=="")
		price=0;
	if(markup=="")
		markup=0;
	if(discount=="")
		discount=0;

	total=parseFloat(price)+parseFloat(markup)-parseFloat(discount);
	total=total.toFixed(2);
	$("#total").val(total);
}
  function addoneway()
  {
	 if($("#dt_from").val()=="")
	{
		$("#dt_from").addClass('is-invalid');
		$("#dt_from").parent().find(".error").remove();
		$("#dt_from").parent().append("<div class='error'>Select Date</div>");
		$("#dt_from").focus();
		$("html, body").animate({ scrollTop: 300 }, 600);
		return false;
	}
	else if($("#dt_to").val()=="")
	{
		$("#dt_to").addClass('is-invalid');
		$("#dt_to").parent().find(".error").remove();
		$("#dt_to").parent().append("<div class='error'>Select Date</div>");
		$("#dt_to").focus();
		$("html, body").animate({ scrollTop: 300 }, 600);
		return false;
	}
	else if($("#departure_date_time").val()=="")
	{
		$("#departure_date_time").addClass('is-invalid');
		$("#departure_date_time").parent().find(".error").remove();
		$("#departure_date_time").parent().append("<div class='error'>Select Date</div>");
		$("#departure_date_time").focus();
		$("html, body").animate({ scrollTop: 400 }, 600);
		return false;
	}
	else if($("#arrival_date_time").val()=="")
	{
		$("#arrival_date_time").addClass('is-invalid');
		$("#arrival_date_time").parent().find(".error").remove();
		$("#arrival_date_time").parent().append("<div class='error'>Select Date</div>");
		$("#arrival_date_time").focus();
		$("html, body").animate({ scrollTop: 400 }, 600);
		return false;
	}
	else if($("#source").val()==$("#destination").val())
	{
		$("#destination").addClass('is-invalid');
		$("#destination").parent().find(".error").remove();
		$("#destination").parent().append("<div class='error'>Source and Destination couldn't be same</div>");
		$("#destination").focus();
		$("html, body").animate({ scrollTop: 400 }, 600);
		return false;
	}
	else if($("#airline").val()=="")
	{
		$("#airline").addClass('is-invalid');
		$("#airline").parent().find(".error").remove();
		$("#airline").parent().append("<div class='error'>Select Airline</div>");
		$("#airline").focus();
		$("html, body").animate({ scrollTop: 400 }, 600);
		return false;
	}
	else if($("#flight_no").val()=="")
	{
		$("#flight_no").addClass('is-invalid');
		$("#flight_no").parent().find(".error").remove();
		$("#flight_no").parent().append("<div class='error'>Enter Flight No.</div>");
		$("#flight_no").focus();
		$("html, body").animate({ scrollTop: 400 }, 600);
		return false;
	}
	
	else if($("#no_of_person").val()=="")
	{
		$("#no_of_person").addClass('is-invalid');
		$("#no_of_person").parent().find(".error").remove();
		$("#no_of_person").parent().append("<div class='error'>Enter No. of seat</div>");
		$("#no_of_person").focus();
		return false;
	}
	else if($("#availibility").val()=="")
	{
		$("#availibility").addClass('is-invalid');
		$("#availibility").parent().find(".error").remove();
		$("#availibility").parent().append("<div class='error'>Enter Availibility</div>");
		$("#availibility").focus();
		return false;
	}
	else if($("#price").val()=="")
	{
		$("#price").addClass('is-invalid');
		$("#price").parent().find(".error").remove();
		$("#price").parent().append("<div class='error'>Enter Ticket Fare</div>");
		$("#price").focus();
		return false;
	}		
	else
	{
		return true;
	}
}
 function addreturn()
  {
	if($("#source").val()=="")
	{
		$("#source").addClass('is-invalid');
		$("#source").parent().find(".error").remove();
		$("#source").parent().append("<div class='error'>Please Select Source City</div>");
		$("#source").focus();
		$("html, body").animate({ scrollTop: 400 }, 600);
		return false;
	}
    else if($("#destination").val()=="")
	{
		$("#destination").addClass('is-invalid');
		$("#destination").parent().find(".error").remove();
		$("#destination").parent().append("<div class='error'>Please Select Destination City</div>");
		$("#destination").focus();
		$("html, body").animate({ scrollTop: 400 }, 600);
		return false;
	}
  	
	else if($("#source").val()==$("#destination").val())
	{
		$("#destination").addClass('is-invalid');
		$("#destination").parent().find(".error").remove();
		$("#destination").parent().append("<div class='error'>Source and Destination couldn't be same</div>");
		$("#destination").focus();
		$("html, body").animate({ scrollTop: 400 }, 600);
		return false;
	} 
	else if($("#airline").val()=="")
	{
		$("#airline").addClass('is-invalid');
		$("#airline").parent().find(".error").remove();
		$("#airline").parent().append("<div class='error'>Select Airline</div>");
		$("#airline").focus();
		$("html, body").animate({ scrollTop: 400 }, 600);
		return false;
	}
	else if($("#departure_date_time").val()=="")
	{
		$("#departure_date_time").addClass('is-invalid');
		$("#departure_date_time").parent().find(".error").remove();
		$("#departure_date_time").parent().append("<div class='error'>Select Date</div>");
		$("#departure_date_time").focus();
		$("html, body").animate({ scrollTop: 400 }, 600);
		return false;
	}
	else if($("#arrival_date_time").val()=="")
	{
		$("#arrival_date_time").addClass('is-invalid');
		$("#arrival_date_time").parent().find(".error").remove();
		$("#arrival_date_time").parent().append("<div class='error'>Select Date</div>");
		$("#arrival_date_time").focus();
		$("html, body").animate({ scrollTop: 400 }, 600);
		return false;
	}
	
	
	else if($("#flight_no").val()=="")
	{
		$("#flight_no").addClass('is-invalid');
		$("#flight_no").parent().find(".error").remove();
		$("#flight_no").parent().append("<div class='error'>Enter Flight No.</div>");
		$("#flight_no").focus();
		$("html, body").animate({ scrollTop: 400 }, 600);
		return false;
	}
	else if($("#airline1").val()=="")
	{
		$("#airline1").addClass('is-invalid');
		$("#airline1").parent().find(".error").remove();
		$("#airline1").parent().append("<div class='error'>Select Airline</div>");
		$("#airline1").focus();
		$("html, body").animate({ scrollTop: 800 }, 600);
		return false;
	}
	else if($("#departure_date_time1").val()=="" )
	{
		$("#departure_date_time1").addClass('is-invalid');
		$("#departure_date_time1").parent().find(".error").remove();
		$("#departure_date_time1").parent().append("<div class='error'>Select Date</div>");
		$("#departure_date_time1").focus();
	    $("html, body").animate({ scrollTop: 800 }, 600);
		return false;
	}
	else if($("#arrival_date_time1").val()=="")
	{
		$("#arrival_date_time1").addClass('is-invalid');
		$("#arrival_date_time1").parent().find(".error").remove();
		$("#arrival_date_time1").parent().append("<div class='error'>Select Date</div>");
		$("#arrival_date_time1").focus();
		$("html, body").animate({ scrollTop: 800 }, 600);
		return false;
	}
	
	else if($("#flight_no1").val()=="" )
	{
		$("#flight_no1").addClass('is-invalid');
		$("#flight_no1").parent().find(".error").remove();
		$("#flight_no1").parent().append("<div class='error'>Enter Flight No.</div>");
		$("#flight_no1").focus();
     	$("html, body").animate({ scrollTop: 800 }, 600);
		return false;
	}
	else if($("#no_of_person").val()=="")
	{
		$("#no_of_person").addClass('is-invalid');
		$("#no_of_person").parent().find(".error").remove();
		$("#no_of_person").parent().append("<div class='error'>Enter No. of seat</div>");
		$("#no_of_person").focus();
		return false;
	}
	else if($("#availibility").val()=="")
	{
		$("#availibility").addClass('is-invalid');
		$("#availibility").parent().find(".error").remove();
		$("#availibility").parent().append("<div class='error'>Enter Availibility</div>");
		$("#availibility").focus();
		return false;
	}
	else if($("#price").val()=="")
	{
		$("#price").addClass('is-invalid');
		$("#price").parent().find(".error").remove();
		$("#price").parent().append("<div class='error'>Enter Ticket Fare</div>");
		$("#price").focus();
		return false;
	}		
	else
	{
		return true;
	}
}