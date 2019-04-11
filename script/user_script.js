$( document ).ready(function() 
{
   $("#destination1").val($("#source").val());	
   $("#source1").val($("#destination1").val());	
   $('.form_datetime').datetimepicker
   ({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
        showMeridian: 1
    });
	
	$('input[name="sale_type"]').click(function() 
	{
		if($(this).val()=="quote")
		{
			$("#div_request_import").show();
			
		}
		else
		{
			$("#div_request_import").hide();
			
		}
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
       $("#btn_register").attr("disabled", "disabled"); 
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
						//$("#status").html("<div class='alert alert-success'>"+value+"</div>");
						window.location.href=""+baseurl+"verify";
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
						//window.location.href=""+baseurl+"user";
						window.location.href=""+baseurl+"search";
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
	
	
	$('#btn_verify').on('click', function (e) 
	{
        e.preventDefault();
        $("#status").html("<center><img src='"+baseurl+"/images/loader-orange.gif'></center>");
        $.ajax({
            type: "POST",
            url: ""+baseurl+"User_Controller/confirm_otp", 
            data: $("#form_verify").serialize(),
            dataType: "json",  
            success: function(data)
			{
				
                $.each(data, function(key, value) 
				{  
                    $("#status").html("");				
                    if(key=="success")
					{						
						$('#form_verify input').each(function()
						{ 
							$(this).val("");								
						});
						$("#status").html("<div class='alert alert-success'>"+value+"</div>");
						
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
	
	$('#btn_otp').on('click', function (e) 
	{
        e.preventDefault();
        $("#status").html("<center><img src='"+baseurl+"/images/loader-orange.gif'></center>");
        $.ajax({
            type: "POST",
            url: ""+baseurl+"User_Controller/confirm_login_otp", 
            data: $("#form_login_otp").serialize(),
            dataType: "json",  
            success: function(data)
			{
				
                $.each(data, function(key, value) 
				{  
                    $("#status").html("");				
                    if(key=="success")
					{						
						$('#form_login_otp input').each(function()
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
		
		var arr = ["First", "Second", "Third", "Fourth", "Fifth"];
		if($("#no_of_stops").val()>0)
		{
			var add_row=$("#no_of_stops").val();
			$("#div_stop").show();			
			var remove=0;
			var present=0;
			
			$('#div_stop #stoprow').each(function()
			{
				 
				 if($(this).find('input').val()=="")
				 {
					 $(this).remove();
					 remove++;
				 }
				 else
				 {
					 present++;
				 }
			});
			var div="";		
			no=parseInt(add_row-present);	
            $("#div_stop").show();				
			for(var i=0;i<no;i++)
			{
				
				$("#div_stop").append('<div id="stoprow">'+
							'<div class="col-xs-6 col-sm-6">'+
							'<label>Enter '+arr[present]+' Stop Name</label>'+
							'</div>'+
							'<div class="col-xs-6 col-sm-6">'+
								'<div class="form-group">'+												
									'<input type="text" class="form-control" name="stop_name[]"  placeholder="Stop Name" />'+
								'</div>'+
							'</div>'+	
					'</div>');
					present++;
			}	
			
		}
		if($("#no_of_stops").val()==0)
		{
			$("#div_stop").html("");
			$("#div_stop").hide();	
		}
		
	});
	
	
	$("#no_of_stops1").change(function()
	{
		var arr = ["First", "Second", "Third", "Fourth", "Fifth"];
		if($("#no_of_stops1").val()>0)
		{
			var add_row=$("#no_of_stops1").val();
			$("#div_stop1").show();			
			var remove=0;
			var present=0;
			$('#div_stop1 #stoprow').each(function()
			{
				 if($(this).find('input').val()=="")
				 {
					 $(this).remove();
					 remove++;
				 }
				 else
				 {
					 present++;
				 }
			});
			var div="";		
			no=parseInt(add_row-present);			
			for(var i=0;i<no;i++)
			{
				
				$("#div_stop1").append('<div id="stoprow">'+
							'<div class="col-xs-6 col-sm-6">'+
							'<label>Enter '+arr[present]+' Stop Name</label>'+
							'</div>'+
							'<div class="col-xs-6 col-sm-6">'+
								'<div class="form-group">'+												
									'<input type="text" class="form-control" name="stop_name1[]"  placeholder="Stop Name" />'+
								'</div>'+
							'</div>'+	
					'</div>');
					present++;
			}	
			
		}
		if($("#no_of_stops1").val()==0)
		{
			$("#div_stop1").html("");
			$("#div_stop1").hide();	
		}
		
	});
	
	
	$("#btn_update_pnr").click(function()
	{
		
		$("#hid_refrence_booking_id").val($(this).attr("color"));
	});
	
	$("#set_pnr").click(function()
	{
		
			var d=$("#dt_from").val();
			var arr=d.split("-");
			d1=arr[1]+"/"+arr[0]+"/"+arr[2];

			var dd=$("#dt_to").val();
			var arr=dd.split("-");
			d2=arr[1]+"/"+arr[0]+"/"+arr[2];
			
			var date1 = new Date(d1);
			var date2 = new Date(d2);
			var timeDiff = Math.abs(date2.getTime() - date1.getTime());
			var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
			
			var div="";
			for(var i=0;i<=diffDays;i++)
			{
				var date = new Date(d1);
                var newdate = new Date(date);
				newdate.setDate(newdate.getDate() + i);
				var dd = newdate.getDate();
				var mm = newdate.getMonth() + 1;
				var y = newdate.getFullYear();
				var someFormattedDate = dd + '-' + mm + '-' + y;
				
				div+=''+
				'<div class="col-xs-1 col-sm-1">'+
						'<div class="form-group">'+												    
							'<input type="text" class="form-control" value='+someFormattedDate+' readonly  style="padding:0;font-size:12px">'+
						'</div>'+
					'</div>'+
					
					'<div class="col-xs-2 col-sm-2">'+
						'<div class="form-group">	'+											    
							'<input type="text" class="form-control" placeholder="Enter PNR" name="pnr[]" />'+
						'</div>'+
					'</div>'+
					
					
					'<div class="col-xs-1 col-sm-1">'+
						'<div class="form-group">	'+											    
							'<input type="number" min=0 class="form-control" placeholder="Seats" id="no_of_person'+i+'" name="no_of_person[]" />'+
						'</div>'+
					'</div>'+
					'<div class="col-xs-2 col-sm-2">'+
						'<div class="form-group">	'+											    
							'<input type="number" min=0 class="form-control" placeholder="Price"  id="price'+i+'"  name="price[]" autocomplete="off" onkeyup="return calculate1('+i+')" onblur="return calculate1('+i+')" onchange="return calculate1('+i+')"/>'+
						'</div>'+
					'</div>'+
					'<div class="col-xs-2 col-sm-2">'+
						'<div class="form-group">	'+											    
							'<input type="number" min=0 class="form-control" placeholder="Markup" id="markup'+i+'"  name="markup[]" autocomplete="off" onkeyup="return calculate1('+i+')" onblur="return calculate1('+i+')" onchange="return calculate1('+i+')" />'+
						'</div>'+
					'</div>'+
					'<div class="col-xs-2 col-sm-2">'+
						'<div class="form-group">	'+											    
							'<input type="number" min=0 class="form-control" placeholder="Discount" id="discount'+i+'" name="discount[]" autocomplete="off" onkeyup="return calculate1('+i+')" onblur="return calculate1('+i+')" onchange="return calculate1('+i+')"/>'+
						'</div>'+
					'</div>'+
					'<div class="col-xs-2 col-sm-2">'+
						'<div class="form-group">	'+											    
							'<input type="number" min=0 class="form-control" placeholder="Total" id="total'+i+'" name="total[]" autocomplete="off" onkeyup="return calculate1(event)" onblur="return calculate1(event)" onchange="return calculate1(event)"/>'+
						'</div>'+
					'</div>';
										
					
					
			}
			$("#set_pnr_div").html(div);
		
		
	});
	
	
	
	$('#btn_forgot_password').on('click', function (e) 
	{
        $("#btn_forgot_password").attr("disabled", "disabled"); 
        $("#forgot_status").html("<center><img src='"+baseurl+"/images/loader-orange.gif'></center>");
        $.ajax({
            type: "POST",
            url: ""+baseurl+"User_Controller/forgot_password", 
            data: {mobile:$("#forgot_mobile").val()},
			
            dataType: "json",  
            success: function(data)
			{
				
                $.each(data, function(key, value) 
				{  
                    $("#forgot_status").html("");				
                    if(key=="success")
					{												
						$("#forgot_status").html("<br/><br/><div class='alert alert-success'>"+value+"</div>");
						setTimeout(function()
						{
							window.location.href=""+baseurl+"login";
						},3000);
						
						
						
					}
					else
					{
						$('#forgot').modal({backdrop: 'static', keyboard: false}) 
						$("#forgot_status").html("");
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
	
	$('#btn_login_otp').on('click', function (e) 
	{
        $("#btn_login_otp").attr("disabled", "disabled"); 
        $("#login_otp_status").html("<center><img src='"+baseurl+"/images/loader-orange.gif'></center>");
        $.ajax({
            type: "POST",
            url: ""+baseurl+"User_Controller/send_login_otp", 
            data: {mobile:$("#otp_mobile").val()},
			
            dataType: "json",  
            success: function(data)
			{
				
                $.each(data, function(key, value) 
				{  
                    $("#login_otp_status").html("");				
                    if(key=="success")
					{												
						$("#login_otp_status").html("<br/><br/><div class='alert alert-success'>"+value+"</div>");
						setTimeout(function()
						{
							window.location.href=""+baseurl+"login-otp";
						},3000);
																		
					}
					else
					{
						$('#login_otp').modal({backdrop: 'static', keyboard: false}) 
						$("#login_otp_status").html("");
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
	
	function calculate1(x)
	{
				
		var price=$("#price"+x+"").val();
		var markup=$("#markup"+x+"").val();	
		var discount=$("#discount"+x+"").val();	
		
		if(price=="")
			price=0;
		if(markup=="")
			markup=0;
		if(discount=="")
			discount=0;

		total=parseFloat(price)+parseFloat(markup)-parseFloat(discount);
		total=total.toFixed(2);
		$("#total"+x+"").val(total);
	}
  function addoneway()
  {
	 var sale_type=$("input[name='sale_type']:checked").val();
	 if(sale_type=="request")
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
			/*else if($("#airline").val()=="")
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
			}*/
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

     if(sale_type=="quote")
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
			
			else if($("#source").val()==$("#destination").val())
			{
				$("#destination").addClass('is-invalid');
				$("#destination").parent().find(".error").remove();
				$("#destination").parent().append("<div class='error'>Source and Destination couldn't be same</div>");
				$("#destination").focus();
				$("html, body").animate({ scrollTop: 400 }, 600);
				return false;
			}
				
			else
			{
				return true;
			}
	}

    if(sale_type=="live")
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
			/*else if($("#airline").val()=="")
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
			}*/
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
	/*else if($("#availibility").val()=="")
	{
		$("#availibility").addClass('is-invalid');
		$("#availibility").parent().find(".error").remove();
		$("#availibility").parent().append("<div class='error'>Enter Availibility</div>");
		$("#availibility").focus();
		return false;
	}*/
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