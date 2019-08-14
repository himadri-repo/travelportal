<!--============ NEWSLETTER-2 =============-->
        <section id="newsletter-2" class="newsletter"> 
            <div class="container">
                <div class="row">
                	<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
                        <h2>Subscribe Our Newsletter</h2>	
                    </div>
                    
                    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                        <form>
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="email" class="form-control input-lg" placeholder="Enter your email address" required/>
                                    <span class="input-group-btn"><button class="btn btn-lg"><i class="fa fa-envelope"></i></button></span>
                                </div>
                            </div>
                        </form>
                    </div><!-- end columns -->
                </div><!-- end row -->
            </div><!-- end container -->
        </section><!-- end newsletter-2 -->
        
        
        <!--======================= FOOTER =======================-->
        <section id="footer" class="ftr-heading-w ftr-heading-mgn-2">
        
            <div id="footer-top" class="banner-padding ftr-top-grey">
                <div class="container">
                    <div class="row">

                        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 footer-widget ftr-about ftr-our-company">
                           <h3 class="footer-heading"><?php echo $footer[0]["title"]; ?></h3>
                            <p><?php echo $footer[0]["description"]; ?>.</p>
                            <ul class="social-links list-inline list-unstyled">
                            	<li><a href="<?php echo $setting[0]["facebook_link"]; ?>" target="_blank"><span><i class="fa fa-facebook"></i></span></a></li>
                            	<li><a href="<?php echo $setting[0]["twitter_link"]; ?>" target="_blank"><span><i class="fa fa-twitter"></i></span></a></li>
                                <li><a href="<?php echo $setting[0]["google_link"]; ?>" target="_blank"><span><i class="fa fa-google-plus"></i></span></a></li>
                            </ul>
                        </div><!-- end columns -->
                        
                        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 footer-widget ftr-in-touch">
                            <h3 class="footer-heading">Get in Touch</h3>
                            
                                <div class="row">
                                    <div class="col-sm-6 col-md-5 col-lg-5 slide-right-vis">
                                    
                                        <div class="form-group">
                                             <input type="text" class="form-control" placeholder="Name"  id="txt_name" name="txt_name" />
                                        </div>
                                      
                                        <div class="form-group">
                                             <input type="text" class="form-control" placeholder="Email"  id="txt_email" name="txt_email" />
                                        </div>
                                          <div class="form-group">
                                             <input type="text" class="form-control" placeholder="Phone No."  id="txt_phone" name="txt_phone"/>
                                        </div>
                                        
                                        
                                        
                                    </div><!-- end columns -->
        
                                    <div class="col-sm-6 col-md-7 col-lg-7 slide-left-vis">
                                        <div class="form-group">
                                            <textarea class="form-control" placeholder="Your Message" id="txt_msg" name="txt_msg"></textarea>
                                        </div>
                                        
                                        <div class="col-sm-12 col-md-12" id="mail-msg-box"></div>
                                    </div><!-- end columns -->
                                    
                                    <div class="col-sm-12 text-center">
                                        <button class="btn btn-orange" id="btn_send_mail">Send</button>
                                    </div><!-- end butn -->
                                </div><!-- end row -->
                            
                        </div><!-- end columns -->
                        
                    </div><!-- end row -->
                </div><!-- end container -->
            </div><!-- end footer-top -->

            <div id="footer-bottom" class="ftr-bot-black">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" id="copyright">
                            <p>© 2018 <a href="#">OxyTra</a>. All rights reserved.</p>
                        </div><!-- end columns -->
                        
                        <!--<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" id="terms">
                            <ul class="list-unstyled list-inline">
                            	<li><a href="#">Terms & Condition</a></li>
                                <li><a href="#">Privacy Policy</a></li>
                            </ul>
                        </div> -->
                    </div><!-- end row -->
                </div><!-- end container -->
            </div><!-- end footer-bottom -->            
        </section><!-- end footer -->
        
        <script src="<?php echo base_url(); ?>js/jquery.colorpanel.js"></script>
        <script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>js/jquery.flexslider.js"></script>
        <script src="<?php echo base_url(); ?>js/bootstrap-datepicker.js"></script>
        <script src="<?php echo base_url(); ?>js/custom-navigation.js"></script>
        <script src="<?php echo base_url(); ?>js/custom-flex.js"></script>
        <script src="<?php echo base_url(); ?>js/custom-date-picker.js"></script>
		
        <script src="<?php echo base_url(); ?>js/jquery.colorpanel.js"></script>
        <script src="<?php echo base_url(); ?>js/jquery.magnific-popup.min.js"></script>        
        <script src="<?php echo base_url(); ?>js/owl.carousel.min.js"></script>
        <script src="<?php echo base_url(); ?>js/custom-owl.js"></script>
        <script src="<?php echo base_url(); ?>js/custom-video.js"></script>
        <!-- DHTMLX inports-->
        <script src="<?php echo base_url(); ?>js/dhtmlx/dhtmlx.js"></script>

        <script type="text/javascript" src="<?php echo base_url(); ?>js/html2canvas.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>js/jspdf.min.js"></script>        
		<script src="<?php echo base_url(); ?>script/search_flight.js"></script>
        <script src="<?php echo base_url(); ?>script/contact.js"></script>
        <script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/components/core.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/components/md5.js"></script>
		<script>
            $(document).ready(function()
            {
                try
                {
                    $('html, body').animate({
                        scrollTop: $('#top_div').offset().top-200
                    }, 'slow');
                }
                catch(e) {
                    console.log(e);
                }
            });
		</script>
        
        <script>
            var tickets = [];
            function getTickets(mth, yr) {
                //var cellContents = {1: '20', 15: '60', 28: '$99.99'};

                // console.log('getTickets');
                // console.log(mth);
                // console.log(yr);

                if(mth===0) 
                    mth = new Date().getMonth()+1;
                else
                    mth++;

                if(yr===0)
                    yr = new Date().getFullYear();

                let obj= {};
                //console.log(tickets);
                if(tickets!==null && tickets!==undefined && tickets.length>0) {
                    for(var i=0; i<tickets.length; i++) {
                        try {
                            let depDate = tickets[i].departure_date_time;
                            let price = parseFloat(tickets[i].price);
                            let dateParts = depDate.split('-');
                            let tday = parseInt(dateParts[0]);
                            let tmth = parseInt(dateParts[1]);
                            let tyr = parseInt(dateParts[2]);
                            
                            //console.log(depDate.toString() + "-" + price.toString());
                            //console.log(mth.toString() + "-" + tmth.toString() + "-" + yr.toString() + "-" + tyr.toString() + "-" + price.toString());

                            if(mth===tmth && yr===tyr && (obj[tday]===undefined || obj[tday]===null)) {
                                obj[tday] = price.toFixed(0);
                            }
                        }
                        catch(e) {
                            console.log(e);
                        }
                    }
                }

                //console.log(obj);

                return obj;
            }

            function setTickets(searchedTickets) {
                //console.log(JSON.stringify(searchedTickets));
                tickets = searchedTickets;
            }

            $(function() {
                $("#destination").change(function() {
                    try
                    {
                        getAvailableTickets($("#source").val(), $("#destination").val());
                        //alert('hi');
                        setTimeout(function() {
                            try
                            {
                                $('.datepicker').datepicker("show");
                            }
                            catch(e) {
                                console.log(e);
                                //alert(e);
                            }
                        }, 0);
                    }
                    catch(e) {
                        console.log(e);
                        //alert(e);
                    }
                });

                getAvailableTickets($("#source").val(), $("#destination").val());

                $('.datepicker').datepicker({
                    changeMonth: true,
                    changeYear: true,
                    minDate: 0,
                    showAnim: "fold",
                    //The calendar is recreated OnSelect for inline calendar
                    onSelect: function (date, dp) {
                        updateDatePickerCells(dp);
                    },
                    onChangeMonthYear: function(month, year, dp) {
                        updateDatePickerCells(dp);
                    },
                    beforeShow: function(elem, dp) { //This is for non-inline datepicker
                        //alert(JSON.stringify(dp));
                        updateDatePickerCells(dp);
                    }
                });
                //updateDatePickerCells();

                function updateDatePickerCells(dp) {
                    /* Wait until current callstack is finished so the datepicker
                    is fully rendered before attempting to modify contents */
                    //getAvailableTickets($("#source").val(), $("#destination").val());

                    //console.log(JSON.stringify(dp));
                    let mth = dp.drawMonth; //dp.selectedMonth;
                    let yr = dp.drawYear; //dp.selectedMonth;
                    
                    // console.log('selected item');
                    // console.log($('#departure_date').val());
                    // console.log($('#departure_date').val()==='');
                    // console.log(dp.selectedMonth);
                    // console.log(dp.selectedYear);
                    if($('#departure_date').val() && $('#departure_date').val()!=='' && mth===0 && yr===0) {
                        datePart = $('#departure_date').val().split('/');
                        mth = parseInt(datePart[0])-1;
                        yr = parseInt(datePart[2]);
                    }

                    setTimeout(function (mth1, yr1) {
                        //Fill this with the data you want to insert (I use and AJAX request).  Key is day of month
                        //NOTE* watch out for CSS special characters in the value
                        var cellContents = getTickets(mth1, yr1);
                        //alert('hi');
                        //Select disabled days (span) for proper indexing but // apply the rule only to enabled days(a)
                        $('.ui-datepicker td > *').each(function (idx, elem) {
                            var value = cellContents[idx + 1] || 0;

                            // dynamically create a css rule to add the contents //with the :after                         
                            // selector so we don't break the datepicker //functionality 
                            var className = 'datepicker-content-' + CryptoJS.MD5(value).toString();

                            if(value == 0)
                                addCSSRule('.ui-datepicker td a.' + className + ':after {content: "\\a0";}'); //&nbsp;
                            else {
                                //console.log(value);
                                addCSSRule('.ui-datepicker td a.' + className + ':after {content: "' + "\\20b9 " + value + '";}');
                            }

                            $(this).addClass(className);
                        });
                    }, 0, mth, yr);
                }

                var dynamicCSSRules = [];
                function addCSSRule(rule) {
                    if ($.inArray(rule, dynamicCSSRules) == -1) {
                        $('head').append('<style>' + rule + '</style>');
                        dynamicCSSRules.push(rule);
                    }
                }
            });
        </script>
    </body>
</html>