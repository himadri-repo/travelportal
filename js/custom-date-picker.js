(function($) {
	
	"use strict";

	// Cache Selectors
	var date1		=$('.dpd1'),
		date2		=$('.dpd2'),
		date3		=$('.dpd3');
	
	
	//Date Picker//
	var nowTemp = new Date();
	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
	 
	var checkin = date1.datepicker({
		onRender: function(date) {
			return date.valueOf() < now.valueOf() ? 'disabled' : '';
		}
	}).on('changeDate', function(ev) {
		if (ev.date.valueOf() > checkout.date.valueOf()) {
			var newDate = new Date(ev.date)
			newDate.setDate(newDate.getDate() + 1);
			checkout.setValue(newDate);
		}
		
		checkin.hide();
		date2[0].focus();
		
	}).data('datepicker');
	
	var checkout = date2.datepicker({
		onRender: function(date) {
			return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
		}
		
	}).on('changeDate', function(ev) {
		checkout.hide();
	}).data('datepicker');
	 var date = new Date();
     var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
	 var date = new Date();
     date.setDate(date.getDate() - 365);
     var user_busy_days      = ['2019-03-07','2019-03-09','2019-03-10','2019-03-11'];
	date3.datepicker
	({
		format: 'dd-mm-yyyy',
		multidate: true,
		minDate: date,
    	beforeShowDay: function (date) 
    	{
                alert("no")
                calender_date = date.getFullYear()+'-'+(date.getMonth()+1)+'-'+('0'+date.getDate()).slice(-2);

                var search_index = $.inArray(calender_date, user_busy_days);

                if (search_index > -1) 
                {
                    
                    return {classes: 'active', tooltip: 'User available on this day.'};
                }else{
                   
                    return {classes: 'highlighted-cal-dates', tooltip: 'User not available on this day.'};
                }

        }
	});

	/*date3.datepicker('setDates', [new Date(2018, 10, 25), new Date(2018, 10, 26)]);
	
	$(".table-condensed .day").each(function()
	{
		if(parseInt($(this).html())<parseInt(date.getDate()))
		{
			$(this).addClass("old");
		}
		
	});
	
	
	
	date3.datepicker().on('changeDate', function(ev) 
	{
		
		$(".table-condensed .day").each(function()
		{
			if(parseInt($(this).html())<parseInt(date.getDate()))
			{
				$(this).addClass("old");
			}
		});
		var cd=$(this).val().split("-");	
        var current_date=new Date(cd[2],cd[1],cd[0]);		
		if(today>current_date)
		{
			$(this).val("");
		}			
	});	*/	
})(jQuery);