<style>
.search_overlay {
	position: fixed; /* Sit on top of the page content */
	display: none; /* Hidden by default */
	width: 100%; /* Full width (cover the whole page) */
	height: 100%; /* Full height (cover the whole page) */
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	background-color: rgba(0,0,0,0.5); /* Black background with opacity */
	z-index: 999; /* Specify a stack order in case you're using a different order for other elements */
	cursor: pointer; /* Add a pointer on hover */
}
.search_overlay img {
    opacity: 0.3;
}
</style>         
        <div id="progressbar" class="search_overlay" style="display:none">
            <img src="<?php echo base_url(); ?>images/progress.gif" style="width: 100%; height: 100%;" title="Search is progressing ...">
        </div>
        
        <!--
        <section class="page-cover back-size" id="cover-flight-search">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                    	<h1 class="page-title">Flight Search Result</h1>
                        <ul class="breadcrumb">
                            <li><a href="#">Home</a></li>
                            <li class="active">Flight Search Result</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>-->
        
        
        <!--===== INNERPAGE-WRAPPER ====-->
        <section class="innerpage-wrapper">
        	<div id="search-result-page" class="innerpage-section-padding">
        		<div class="container">
        			<div class="row">
                    	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 content-side">
                        	<div class="page-search-form">
                            	<h2>Search the <span>Flight <i class="fa fa-plane"></i></span></h2>
                                
                                <ul class="nav nav-tabs">
								    <li class="active"><a href="#tab-one-way" data-toggle="tab">One Way</a></li>
                                	<li ><a href="#tab-round-trip" data-toggle="tab">Round Trip</a></li>
                                	
                                </ul>
                                
                                <div class="tab-content">
                                	<div id="tab-round-trip" class="tab-pane fade in ">
                                        <form class="pg-search-form" id="frm_round_way" action="<?php echo base_url(); ?>search/search_round_trip" method="post" onsubmit="return validation1()" autocomplete="off">
										   <input type="hidden" name="trip_type" value="ROUND"> 
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                                    <div class="form-group">
                                                        <label><span><i class="fa fa-map-marker"></i></span>From</label>
                                                        <select class="form-control" name="source1" id="source1">
													      <option value="">Source</option>
														  <?php
                                                          if($city1) {
                                                            foreach($city1 as $key=>$value)
                                                            {
                                                            ?>
                                                                <option value="<?php echo $city1[$key]["id"];?>"><?php echo $city1[$key]["city"];?></option>
                                                            <?php
                                                            }
                                                           }
														   ?>										  
														</select>
                                                    </div>
                                                </div><!-- end columns -->
                                                
                                                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                                    <div class="form-group">
                                                        <label><span><i class="fa fa-map-marker"></i></span>To</label>
                                                         <select class="form-control" name="destination1" id="destination1">
													      <option value="">Destination</option>
														 									  
														</select>
                                                    </div>
                                                </div><!-- end columns -->
                                                
                                                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                                    <div class="form-group">
                                                        <label><span><i class="fa fa-calendar"></i></span>Available Date</label>
                                                        <!--<input class="form-control dpd3" placeholder="Date" name="departure_date_time1" id="departure_date_time1" autocomplete="off"/>-->
														<input class="datepicker" placeholder="dd/mm/yyyy" name="departure_date1" id="departure_date1" readonly required/>
                                                        <select class="form-control" name="departure_date_time1" id="departure_date_time1" style="display:none">
                                                        </select>    
                                                    </div>

                                                </div><!-- end columns -->
                                                
                                                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                                    <div class="form-group">
                                                        <label><span><i class="fa fa-users"></i></span>Passengers</label>                                                        
														<select class="form-control" name="no_of_person1" id="no_of_person1">														 
														  <option value="1">1</option>
														  <option value="2">2</option>
														  <option value="3">3</option>
														  <option value="4">4</option>
														  <option value="5">5</option>
														  <option value="6">6</option>
														  <option value="7">7</option>
														  <option value="8">8</option>
														  <option value="9">9</option>
														</select>
													</div>
                                                </div>
                                            </div><!-- end row -->
                                            
                                            <button type="submit" class="btn btn-orange" id="btn_round_way">Search</button>
                                        </form>
                                    </div>
                                    
                                    <div id="tab-one-way" class="tab-pane fade in active">
                                        <form class="pg-search-form" id="frm_one_way" action="<?php echo base_url(); ?>search/search_one_way" method="post" onsubmit="return validate_searchform()" autocomplete="off">
										   <input type="hidden" name="trip_type" value="ONE"> 
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                                    <div class="form-group">
                                                        <label><span><i class="fa fa-map-marker"></i></span>From</label>
                                                        <select class="form-control" name="source" id="source">
													      <option value="">Source</option>
														  <?php
														  foreach($sources as $sector)
														  {
														  ?>
															<option value="<?php echo $sector['id'];?>"><?php echo $sector["sector"];?></option>
														  <?php
														  }
														   ?>										  
														  <?php
														//   foreach($city as $key=>$value)
														//   {
														  ?>
															<!-- <option value="<?php //echo $city[$key]["id"];?>"><?php //echo $city[$key]["city"];?></option> -->
														  <?php
//														  }
														   ?>										  
														</select>
                                                    </div>
                                                </div><!-- end columns -->
                                                
                                                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                                    <div class="form-group">
                                                        <label><span><i class="fa fa-map-marker"></i></span>To</label>
                                                         <select class="form-control" name="destination" id="destination">
													      <option value="">Destination</option>
														 									  
														</select>
                                                    </div>
                                                </div><!-- end columns -->
                                                
                                                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                                    <div class="form-group">
                                                        <label><span><i class="fa fa-calendar"></i></span>Available Date</label>
                                                       <!--<input class="form-control dpd3" placeholder="Date" name="departure_date_time" id="departure_date_time" autocomplete="off"/>-->
                                                        <input class="datepicker" placeholder="dd/mm/yyyy" name="departure_date" id="departure_date" readonly/>
                                                        <!-- <input class="form-control datepicker" name="departure_date" id="departure_date" readonly required/> -->
                                                        <select class="form-control" name="departure_date_time" id="departure_date_time" style="display:none">
                                                        </select>  
                                                    </div>

                                                </div><!-- end columns -->
                                                
                                                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                                    <div class="form-group">
                                                        <label><span><i class="fa fa-users"></i></span>Passengers</label>                                                        
														<select class="form-control" name="no_of_person" id="no_of_person">
														  <option value="1">1</option>
														  <option value="2">2</option>
														  <option value="3">3</option>
														  <option value="4">4</option>
														  <option value="5">5</option>
														  <option value="6">6</option>
														  <option value="7">7</option>
														  <option value="8">8</option>
														  <option value="9">9</option>
														  
														</select>
													</div>
                                                </div>
                                            </div><!-- end row -->
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 error"></div>
                                            </div>
                                            <button type="submit" class="btn btn-orange" id="btn_one_way">Search</button>
                                        </form>
                                    </div><!-- end tab-one-way -->
                                </div><!-- end tab-content -->
                            </div><!-- end page-search-form -->
                            
                            
                    		
                        </div><!-- end columns -->
                    </div><!-- end row -->
            	</div><!-- end container -->
            </div><!-- end search-result-page -->
        </section><!-- end innerpage-wrapper -->
        
        <script language="javascript">
            function validate_searchform(mode='oneway')
            {
                $("#progressbar").hide();
                if($("#source").val()=="")
                {
                    // $("#source1").addClass('is-invalid');
                    // $("#source1").parent().find(".error").remove();
                    // $("#source1").parent().append('<div class="error">Please Select Source !!!</div>');
                    $('.error').html('<div class="error">*** Please Select Source !!!</div>');
                    return false;
                }
                else if($("#destination").val()=="")
                {
                    // $("#destination1").addClass('is-invalid');
                    // $("#destination1").parent().find(".error").remove();
                    // $("#destination1").parent().append('<div class="error">Please Select Destination !!!</div>');
                    $('.error').html('<div class="error">*** Please Select Destination !!!</div>');
                    return false;
                }
                else if($("#departure_date").val()=="")
                {
                    // $("#departure_date1").addClass('is-invalid');
                    // $("#departure_date1").parent().find(".error").remove();			  
                    // $("#departure_date1").parent().append('<div class="error">Please Select Departing Date</div>');
                    $('.error').html('<div class="error">*** Please Select Departing Date</div>');
                    return false;
                }
                else if($("#no_of_person").val()=="" || $("#no_of_person").val()=="0")
                {
                    // $("#passanger1").addClass('is-invalid');
                    // $("#passanger1").parent().find(".error").remove();			  
                    // $("#passanger1").parent().append('<div class="error">Please Enter No. of Passanger</div>');
                    $('.error').html('<div class="error">*** Please Enter No. of Passanger</div>');
                    return false;
                }
                else
                {
                    $("#btn_one_way").hide();
                    $("#progressbar").show();
                    return true;
                }
            }
        </script>
        