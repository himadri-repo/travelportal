$( document ).ready(function() 
{	     	                   
	$('#frm_one_way input').on('keyup', function () 
	{ 
        $(this).removeClass('is-invalid').addClass('is-valid');
        $(this).parent().find(".error").remove();
    });
	$('#frm_one_way select').on('change', function () 
	{ 
        $(this).removeClass('is-invalid').addClass('is-valid');
        $(this).parent().find(".error").remove();
    });
	$(".btn_send_quote_request").click(function()
	{
		$("#frm_sendquote").attr('action',''+baseurl+'search/sendquote/'+$(this).attr("color")+'');
	});
	
	$("#source").change(function()
	{
	   	 $.ajax({
            type: "POST",
            url: ""+baseurl+"search/filter_city", 
            data: {source:$("#source").val(),trip_type:"ONE"},
            dataType: "json",  
            success: function(data)
			{
				
                $.each(data, function(key, value) 
				{  
                    $("#destination").html("");				
					$("#destination").append("<option value=''>Destination</option>");
					
                    if(key=="success")
					{	
                        for(var i=0;i<50;i++)										  
					      $("#destination").append("<option value='"+value[i]["id"]+"'>"+value[i]["city"]+"</option>");
					}
					else
					{
						alert("Error")
					}
					
                });
            }
        });
	});
	
	$("#source1").change(function()
	{
	   	 $.ajax({
            type: "POST",
            url: ""+baseurl+"search/filter_city", 
            data: {source:$("#source1").val(),trip_type:"ROUND"},
            dataType: "json",  
            success: function(data)
			{
				
                $.each(data, function(key, value) 
				{  
                    $("#destination1").html("");				
					$("#destination1").append("<option value=''>Destination</option>");
					
                    if(key=="success")
					{	
                        for(var i=0;i<50;i++)										  
					      $("#destination1").append("<option value='"+value[i]["id"]+"'>"+value[i]["city"]+"</option>");
					}
					else
					{
						alert("Error")
					}
					
                });
            }
        });
	});
	

	$("#destination").change(function()
	{
		getAvailableTickets($("#source").val(), $("#destination").val());
	});
	
	
	$("#destination1").change(function()
	{
		
		
	   	 $.ajax({
            type: "POST",
            url: ""+baseurl+"search/search_available_date1", 
            data: {source:$("#source1").val(),destination:$("#destination1").val(),trip_type:"ROUND"},
            dataType: "json",  
            success: function(data)
			{		
			    $("#departure_date_time1").html("");
                $.each(data, function(key, value) 
				{                      					
                    if(key=="success")
					{	
                        for(var i=0;i<50;i++)
						{	
							 /*if(i==0)
							 {		
								 $("#departure_date_time").val(value[i]["departure_date_time"])									   					      
							 }
							 */                        
							$("#departure_date_time1").append("<option value='"+value[i]["departure_date_time1"]+"'>"+value[i]["departure_date_time1"]+"</option>");
							
						}
					}
					else
					{
						alert("Error")
					}
					
                });
            }
        });
	});
		
	
});

function validation()
{
	    if($("#trip_type").val()=="")
		{
			   
			$("#trip_type").addClass('is-invalid');
			$("#trip_type").parent().find(".error").remove();
			$("#trip_type").parent().append('<div class="error">Please Select Trip Type !!!</div>');
			return false;
		}
		else if($("#dt_from").val()=="")
		{
			$("#dt_from").addClass('is-invalid');
			$("#dt_from").parent().find(".error").remove();
			$("#dt_from").parent().append('<div class="error">Please Select Date From !!!</div>');
			return false;
		}
		else if($("#dt_to").val()=="")
		{
			$("#dt_to").addClass('is-invalid');
			$("#dt_to").parent().find(".error").remove();
			$("#dt_to").parent().append('<div class="error">Please Select Date To !!!</div>');
			return false;
		}
		
		else
		{
			return true;
		}
}
function validation1()
{
	    if($("#source1").val()=="")
		{
			   
			$("#source1").addClass('is-invalid');
			$("#source1").parent().find(".error").remove();
			$("#source1").parent().append('<div class="error">Please Select Source !!!</div>');
			return false;
		}
		else if($("#destination1").val()=="")
		{
			$("#destination1").addClass('is-invalid');
			$("#destination1").parent().find(".error").remove();
			$("#destination1").parent().append('<div class="error">Please Select Destination !!!</div>');
			return false;
		}
		else if($("#destination1").val()==$("#source1").val())
		{
			   $("#destination1").addClass('is-invalid');
			   $("#destination1").parent().find(".error").remove();			  
				$("#destination1").parent().append('<div class="error">Source and Destination can not be same !!!</div>');
				return false;
		}
		else if($("#departure_date_time1").val()=="")
		{
			   $("#departure_date_time1").addClass('is-invalid');
			   $("#departure_date_time1").parent().find(".error").remove();			  
			   $("#departure_date_time1").parent().append('<div class="error">Please Select Departing Date</div>');
				return false;
		}
		else if($("#departure_date_time1").val()=="")
		{
			   $("#departure_date_time1").addClass('is-invalid');
			   $("#departure_date_time1").parent().find(".error").remove();			  
			   $("#departure_date_time1").parent().append('<div class="error">Please Select Departing Date</div>');
				return false;
		}
		else if($("#passanger1").val()=="" || $("#passanger1").val()=="0")
		{
			   $("#passanger1").addClass('is-invalid');
			   $("#passanger1").parent().find(".error").remove();			  
			   $("#passanger1").parent().append('<div class="error">Please Enter No. of Passanger</div>');
				return false;
		}
		else
		{
			return true;
		}
}

function calculate()
{		
	var price=$("#calc_price").val()*$("#qty").val();
	var markup=$("#markup").val()*$("#qty").val();
	var service_charge=$("#service_charge").val();
	/*var sgst=$("#sgst").val();
	var cgst=$("#cgst").val();*/
	var igst=$("#igst").val();
	/*var meal=$("#meal").val();
	var bagggage=$("#bgg").val();*/	
		
	total=parseFloat(price)+parseFloat(markup)+parseFloat(service_charge)+parseFloat(igst);
	total=total.toFixed(2);
	$("#calc_total").val(total);	
	$("#total").val(parseFloat($("#price").val())+parseFloat($("#markup").val()));
}

$(window).on('load', function() 
{
	/*var doc = new jsPDF();
	var specialElementHandlers = {
		'#editor': function (element, renderer) {
			return true;
		}
};*/
$('#pdfview').click(function () 
{
    /*doc.fromHTML($('#thank-you').html(), 15, 15, {
        'width': 700,
            'elementHandlers': specialElementHandlers
    });
    doc.save('ticket.pdf');*/
	genPDF();
	
});
});

function genPDF()
  {
   html2canvas(document.body,{
   onrendered:function(canvas){

   var img=canvas.toDataURL("image/png");
   var doc = new jsPDF();
   doc.addImage(img,'JPEG',20,20);
   doc.save('ticket.pdf');
   }

   });
}

function getAvailableTickets(source, destination) {
	$.ajax({
		type: "POST",
		url: ""+baseurl+"search/search_available_date", 
		data: {source:source,destination:destination,trip_type:"ONE"},
		dataType: "json",  
		success: function(data)
		{	
			try
			{
				try
				{
					//need to work here
					//console.log(data.success);
					setTickets(data.success);
				}
				catch(e1) {
					console.log(e1);
				}

				$("#departure_date_time").html("");
				$.each(data, function(key, value) 
				{                      					
					if(key=="success")
					{	
						for(var i=0;i<50;i++)
						{	
							$("#departure_date_time").append("<option value='"+value[i]["departure_date_time"]+"'>"+value[i]["departure_date_time"]+"</option>");
						}
					}
					else
					{
						alert("Error")
					}
					
				});
			}
			catch(e) {
				console.log(e);
			}
		}
	});
}
