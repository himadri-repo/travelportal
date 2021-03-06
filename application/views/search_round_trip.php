<style>
.msg {
	background: #000; 
	opacity: 0.77; 
	min-width: 150px; 
	position: absolute; 
	bottom: 100%; 
	color: #fff; 
	padding: 5px; 
	border: 1px solid #cdcecf; 
	-moz-border-radius: 4px; 
	-webkit-border-radius: 4px; 
	border-radius: 4px; 
	-moz-box-shadow: 0 0 5px 1px #cdcecf;
	-webkit-box-shadow: 0 0 5px 1px #cdcecf; 
	box-shadow: 0 0 5px 1px #cdcecf; 
	left: 50%; 
	transform: translate(-50%, -1em); 
	z-index: 2;
}

.msg::before {
    content: "";
    position: absolute;
    border: 7px solid #000;
	top: 99%;
	transform: translateX(-50%);

	border-left-color: transparent;
    border-right-color: transparent;
    border-bottom-color: transparent;
	/*border-top: 10px solid rgba(146, 22, 22, 0.77);	*/
	border-top: 10px solid rgb(25, 23, 23);
	left: 50%;
}

.msg:after {
	transform: translateX(-50%);
	border-left-color: transparent;
    border-right-color: transparent;
    border-bottom-color: transparent;
    border-top: 10px solid rgba(146, 22, 22, 0.77);	
}

.title {
	font-size: 17px;
	font-weight: 600;
	color: #696565;
}

.summary_title {
	font-size: 17px;
    font-weight: 600;
    color: #ffffff;
}

.title1 {
	color: #ff0000;
}

.stopstyle {
    display: -webkit-flex; 
    display: -moz-flex; 
    display: -ms-flexbox; 
    display: -o-flex; 
    display: flex; 
    justify-content: center; 
    z-index: 1; 
    transform: translateX(-50%); 
    bottom: -13px; 
    position: relative;
}

.stopper-icon {
    width: 10px; 
    height: 10px; 
    display: inline-block; 
    border: 3px solid #ff0d0d; 
    border-radius: 50%; 
    background: #fff; 
    margin: 0 4px; 
    cursor: pointer; 
    position: relative;
}

.stopper-icon-gr {
    border: 3px solid #0fda1a; 
}

.stopper-icon .msg {
    display: none;
}

.stopper-icon:hover .msg {
    display: block;
}

.airline-logo {
	max-width: 50px;
    flex: 1 0 20%;
    max-height: 50px;
    margin: 5px 7px;
    /* border-radius: 50%; */
    width: 25px;
    height: 25px;
}

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
    opacity: 0.7;
}
.date_diff {
    font-size: 12px;
    color: rgba(17, 50, 210, 0.77);
    font-weight: 600;
}
.blink_me {
	animation: blinker 3s linear infinite;
    color: #ff0000;
    font-size: 0.7em;
    display: inline-block;
    min-height: 35px;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}

.no-left-padding {
	padding-left: 0px;
}
.no-right-padding {
	padding-right: 0px;
}

.outbound, .inbound {
	cursor: pointer;
}

.selected_element {
	background-color: #ffffff;
	border: 2px solid #252cbb;
}

@media(min-width:769px) {
	.selected_element {
		background-color: #ffffff;
		border: 2px solid #252cbb;
		position: -webkit-sticky;
		position: sticky;
		top: 60px;
		bottom: 65px;
		z-index: 9;
	}
}

.fixed-footer {
    position: fixed;
    left: 0px;
    bottom: 0px;
    height: 65px;
    width: 100%;
    /* background: #100e77d9; */
    color: #fff;
    z-index: 9;
}

.footer-content {
    background-color: #0b1071eb;
    min-height: 65px;
    z-index: 99;	
}

</style>         
<div id="progressbar" class="search_overlay" style="display:none">
	<img src="<?php echo base_url(); ?>images/progress.gif" style="width: 100%; height: 100%;" title="Search is progressing ...">
</div>

<section class="innerpage-wrapper" style="top:0;margin-top:20px">
	<div id="flight-listings" class="innerpage-section-padding" style="padding-top:0;">
		<div class="container-base">
			<div class="row">        	
					<div class="col-xs-12 col-sm-12 col-lg-12 col-md-12 right-side-bar">
								
					<div class="page-search-form" style="padding: 20px 10px;">
						<?php 
						$companyid = intval($this->session->userdata("current_user")["companyid"]);
						$triptype = trim($state['triptype']);
						if($companyid>0) { ?> <!--  === 1 || $companyid === 7 -->
							<?= $this->load->view('search_panel',$state, TRUE);?>
						<?php } ?>
					</div><!-- end page-search-form -->
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-lg-12 col-md-12 content-side" id="top_div">
					<?php 
						$dept_date = $state['departure_date'];
						$rtn_date = $state['return_date'];

                        $currentuserstyle = ((($currentuser['is_admin']=='1' || $currentuser['type']=='B2B') && !empty($flight) && count($flight)>0) ? 'block': 'none');
						$direction = '→';
						if($triptype === 'round') {
							$direction = '⇄';
						}
                        // if($flight && is_array($flight) && count($flight)>0 && $flight[0]['trip_type'] === 'ROUND') {
                        //     $direction = '⇄';
                        // }
					?>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="background-color: #faa61a; color: #ffffff; font-size: 15pt; text-align: right; display: <?php echo $currentuserstyle;?> ">
                        <?php if(!empty($flight) && is_array($flight) && count($flight)>0) { ?>
    						<span class="title" style="float:left; color: #ffffff;"> Departure : <?= date('d-M-Y', strtotime($dept_date)).' | '.$flight[0]['source_city']." $direction ".$flight[0]['destination_city'] ?></span>
							<?php if($triptype === 'round') { ?>
								<span class="title" style="float:left; color: #ffffff;">&nbsp;|&nbsp;Return : <?= date('d-M-Y', strtotime($rtn_date)) ?></span>
							<?php } ?>
                            <!-- ⇄ -->
                        <?php } ?>
						<input type="checkbox" id="showcostprice" name="showcostprice" checked style="display: inline-block; width: 12pt; height: 12pt; cursor: pointer;"/>
						<span>Show cost price</span>
					</div>

					<?php
						if(!empty($flight))
						{ ?>
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 no-left-padding">
						<?php
						 	foreach($flight as $key=>$value)
							{
								$flightitem = $flight[$key];
								if($flightitem['direction'] === 'IB') continue;

								$segments = isset($flightitem['segments'])?$flightitem['segments']:false;

								$stops_details = [];
								if($segments) {
									$firstairline = $flightitem['flight_no'];
									for ($si=0; $si < count($segments)-1; $si++) { 
										$stop_name = $segments[$si]['arrival_city'];
										$arrival_datetime = $segments[$si]['arrival_datetime'];

										$next_departure_datetime = $segments[$si+1]['departure_datetime'];
										$next_aircode = $segments[$si+1]['aircode']; //it was aircide changing it to aircode
										$next_flight_number = $segments[$si+1]['flight_number'];
										$next_dept_terminal = "T-".$segments[$si]['departure_terminal'];

										$layover = intval((strtotime($next_departure_datetime)-strtotime($arrival_datetime))/60);
										$layover = intval($layover/60).'h '.intval($layover%60).'m';

										$sameairline = ($firstairline === "$next_aircode $next_flight_number");

										$stops_details[] = array('stop_name' => $stop_name, 'layover' => $layover, 'sameairline' => $sameairline, 'next_airline' => "$next_aircode $next_flight_number", 'terminal' => $next_dept_terminal);

										$firstairline = "$next_aircode $next_flight_number";
									}
								}

								$class = "";
                                //echo $flight[$key]["user_id"] . " - " . $this->session->userdata('user_id');
                                log_message('debug', 'Flight Data => '.json_encode($value));

								if($flight[$key]["user_id"]==$this->session->userdata('user_id')) {
									//echo "supplier_ticket";
									$class = "supplier_ticket";
								} 
								else if($flight[$key]["user_id"]==-1) {
									//API ticket
									$class = "api_ticket";
									//echo "<br/>NOT supplier_ticket";
								}
								else {
									$class = "";
                                }
                                
                                //if($flight[$key]["adult_total"]>0) {
								$splcode = 'special fare';
								$rmkrs = '';
                                if(isset($flight[$key]["sale_type"]) && $flight[$key]["sale_type"] !== 'api') {
                                    $source_city = $flightitem['source_city_code'];
                                    $destination_city = $flightitem['destination_city_code'];
                                    $airline_name = $flight[$key]["airline"];
                                    $dept_date = strtotime($flight[$key]["departure_date_time"]);
                                    $arrv_date = strtotime($flight[$key]["arrival_date_time"]);
                                    $dateDiff = intval((strtotime($flight[$key]["arrival_date_time"])-strtotime($flight[$key]["departure_date_time"]))/60);
									$dept_terminal = $flightitem['departure_terminal'];
									$arrv_terminal = $flightitem['arrival_terminal'];
                                }
                                else {
                                    $splcode = 'system/live fare';
                                    $source_city = $flightitem['source_code'];
                                    $destination_city = $flightitem['destination_code'];
                                    $airline_name = $flight[$key]["airline_name"];
                                    $dept_date = strtotime($flight[$key]["departure_date_time"]);
                                    $arrv_date = strtotime($flight[$key]["arrival_date_time"]);
									$dateDiff = intval((strtotime($flight[$key]["arrival_date_time"])-strtotime($flight[$key]["departure_date_time"]))/60);
									
									$dept_terminal = "T-".$flightitem['departure_terminal'];
									$arrv_terminal = "T-".$flightitem['arrival_terminal'];
                                }
                                
								//$flightitem
                                if((intval($flight[$key]["companyid"])===intval($currentuser['companyid'])) && intval($currentuser['is_admin'])===1) {
									$splcode = $flight[$key]["data_collected_from"];
									$rmkrs = $flight[$key]["remarks"];
								}
								else if((intval($flight[$key]["companyid"])!==intval($currentuser['companyid'])) && intval($currentuser['is_admin'])===1) {
									$splcode = $flight[$key]["companyname"];
									$rmkrs = $flight[$key]["remarks"];
								}
								
								$sale_type = $flight[$key]["sale_type"];

                                $auto_corrected = $flight[$key]["live_corrected"];
                                $stops = intval($flight[$key]["no_of_stops"]);

                                if($stops === 0) {
                                    $stop_text = "Non Stop";
                                }
                                else {
                                    $stop_text = "$stops Stop(s)";
								}
								
								$flight_data['image'] = $flightitem['image'];
								$flight_data['airline'] = $airline_name;
								$flight_data['flight_no'] = $flightitem['flight_no'];
								$flight_data['splcode'] = $splcode;
								$flight_data['dept_date'] = $dept_date;
								$flight_data['formated_dept_date'] = date('H:i', $dept_date);
								$flight_data['arrv_date'] = $arrv_date;
								$flight_data['formated_arrv_date'] = date('H:i', $arrv_date);
								$flight_data['source_city'] = $source_city;
								$flight_data['dept_terminal'] = $dept_terminal;
								$flight_data['stop_details'] = $stops_details;
								$flight_data['destination_city'] = $destination_city;
								$flight_data['arrv_terminal'] = $arrv_terminal;
								$flight_data['no_of_person'] = $flightitem['no_of_person'];
								$flight_data['sale_type'] = $sale_type;
								$flight_data['id'] = $flightitem["id"];
								$flight_data['companyid'] = intval($flightitem["companyid"]);

								$infant_price = isset($flight[$key]["infant_price"]) ? floatval($flight[$key]["infant_price"]) : 0;

								$adult = isset($state['adult']) ? intval($state['adult']) : 0;
								$child = isset($state['child']) ? intval($state['child']) : 0;
								$infant = isset($state['infant']) ? intval($state['infant']) : 0;
				
								$final_total = ($flight[$key]["price"] * ($adult + $child)) + ($infant_price * $infant);
								if($currentuser['is_admin']!=='1' && $currentuser['type']=='B2B') {
									$final_total += $flight[$key]["admin_markup"];
								} 
								if($currentuser['is_admin']=='1') {
									$costprice = (floatval($flight[$key]['cost_price']) * ($adult + $child)) + ($infant_price * $infant);
								}
								else if($currentuser['is_admin']!=='1' && $currentuser['type']=='B2B') {
									// $costprice = $flight[$key]["price"] + ($infant_price * $infant);
									$costprice = ($flight[$key]["price"] * ($adult + $child)) + ($infant_price * $infant);
								}
								else {
									$costprice = 0;
								}
								
								$flight_data['costprice'] = $costprice;
								$flight_data['final_total'] = $final_total;								
								?>

                                <div class="<?php echo $class?> list-block main-block f-list-block outbound" data="<?= htmlspecialchars(json_encode($flight_data), ENT_QUOTES, 'UTF-8') ?>">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-lg-3 col-md-3" style="padding: 10px;">
                                            <div style="display: flex; border-right: 1px solid #e0dcdc;">
                                                <?php if(isset($flightitem['image']) && $flightitem['image']!=='') { ?>
                                                    <img src="<?php echo base_url(); ?>upload/thumb/<?= $flightitem['image'] ?>" class="img-responsive airline-logo" alt="flight-img" />
                                                <?php } else { ?>
                                                    <img src="<?php echo base_url(); ?>upload/thumb/flight.png" class="img-responsive airline-logo" alt="flight-img" />
                                                <?php } ?>
                                                <div>
                                                    <div class="title"><?= $airline_name?></div>
                                                    <div style="color: #aba3a3; font-size: 0.85em;"><?= $flightitem['flight_no'] ?> (<?= $splcode ?>)</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-lg-5 col-md-5" style="padding: 10px;">
											<div style="display: flex; border-right: 1px solid #e0dcdc;">
												<?php 
													$dt1 = date_create(date('Y-m-d 00:00:00', $dept_date));
													$dt2 = date_create(date('Y-m-d 23:59:59', $arrv_date));
													$date_diff = date_diff($dt1,$dt2);
													$days = intval(date_diff($dt1,$dt2)->d);
												?>
                                                <div style="flex: 1 0 10%; margin: 2px 7px;">
                                                    <div style="text-align: center;">
                                                        <span class="title"><?= date('H:i', $dept_date) ?></span>
                                                        <div style="color: #aba3a3; font-size: 0.85em;"><?= $source_city ?>(<?= $dept_terminal ?>)</div>
                                                    </div>
                                                </div>
                                                <div style="flex: 1 0 25%; margin: auto 0px;">
                                                    <div style="margin: 0 11%; padding-bottom: 0.55em; border-bottom: 2px solid #cacaca; display: flex;">
                                                        <?php 
                                                        if($stops>0) {
                                                            $wth = 100/$stops;
                                                        }
                                                        else {
                                                            $wth = 100;
                                                        }
                                                        for ($i=0; $i < $stops ; $i++) { 
															$stop_detail = $stops_details[$i];
															$sameairline = boolval($stop_detail['sameairline']);
															$sameair_class = "stopper-icon";
															if($sameairline) {
																$sameair_class = $sameair_class." stopper-icon-gr";
															}
															?>
                                                            <span class="stopstyle" <?= "style = 'width: ".$wth."%; left: ".($wth/2)."%; '"?>>
                                                                <i class="<?= $sameair_class ?>">
                                                                    <div style="transform-origin: 0% 100%;" class="msg">
																		<table style="width: 200px;">
																			<tr style="opacity: 0.75;">
																				<td style="padding-right: 5px; text-align: left;">Stop</td>
																				<td style="padding-right: 5px; text-align: left;">Airline</td>
																				<td style="text-align: right;">Layover Time</td>
																			</tr>
																			<tr>
																				<td style="padding-right: 5px; text-align: left;"><?= $stop_detail['stop_name'] ?> <?= (isset($stop_detail['terminal']) && $stop_detail['terminal']!=='') ? (' ('.$stop_detail['terminal'].')') : '' ?></td>
																				<td style="padding-right: 5px; text-align: left;"><?= $stop_detail['next_airline'] ?></td>
																				<td style="text-align: right;"><?= $stop_detail['layover'] ?></td>
																			</tr>
																		</table>
                                                                    </div>
                                                                </i>
                                                            </span>
                                                        <?php } ?>
                                                        <!-- <span style="display: -webkit-flex; display: -moz-flex; display: -ms-flexbox; display: -o-flex; display: flex; justify-content: center; left: 20%; z-index: 1; transform: translateX(-50%); bottom: -13px; position: relative; width: 33%;">
                                                            <i style="width: 9px; height: 9px; display: inline-block; border: 2px solid #cacaca; border-radius: 50%; background: #fff; margin: 0 4px; cursor: pointer; position: relative;">
                                                                <div style="display: none; transform-origin: 0% 100%;" class="msg">
                                                                    This is testing
                                                                </div>
                                                            </i>
                                                        </span>
                                                        <span style="display: -webkit-flex; display: -moz-flex; display: -ms-flexbox; display: -o-flex; display: flex; justify-content: center; left: 20%; z-index: 1; transform: translateX(-50%); bottom: -13px; position: relative; width: 33%;">
                                                            <i style="width: 9px; height: 9px; display: inline-block; border: 2px solid #cacaca; border-radius: 50%; background: #fff; margin: 0 4px; cursor: pointer; position: relative;">
                                                                <div style="display: none; transform-origin: 0% 100%;" class="msg">
                                                                    This is testing
                                                                </div>
                                                            </i>
                                                        </span>
                                                        <span style="display: -webkit-flex; display: -moz-flex; display: -ms-flexbox; display: -o-flex; display: flex; justify-content: center; left: 20%; z-index: 1; transform: translateX(-50%); bottom: -13px; position: relative; width: 33%;">
                                                            <i style="width: 9px; height: 9px; display: inline-block; border: 2px solid #cacaca; border-radius: 50%; background: #fff; margin: 0 4px; cursor: pointer; position: relative;">
                                                                <div style="display: none; transform-origin: 0% 100%;" class="msg">
                                                                    This is testing
                                                                </div>
                                                            </i>
                                                        </span> -->
                                                    </div>
                                                    <div style="margin: 3px 0px 1px 5px; color: #aba3a3; font-size: 0.80em; text-align: center;"><span class="title1"><?= intval($dateDiff/60)."h ".($dateDiff%60)."m"; ?></span> | <?= $stop_text ?></div>
                                                </div>
                                                <div style="flex: 1 0 10%;">
                                                    <div style="text-align: center;">
														<span class="title"><?= date('H:i', $arrv_date) ?></span>
														<?php if($days>0) { ?>
															<span class="date_diff"><?= $date_diff->format("%R%a ").($days>1 ? 'days' : 'day') ?></span>
														<?php } ?>
                                                        <div style="color: #aba3a3; font-size: 0.85em;"><?= $destination_city ?>(<?= $arrv_terminal ?>)</div>
                                                    </div>										
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-lg-2 col-md-2" style="padding: 10px;">
                                            <div style="border-right: 1px solid #e0dcdc; padding: 5px 0px;">
                                                <div style="text-align: center;">
                                                    <div class="title"><?= $flightitem['no_of_person'] ?> seats(s) left</div>
                                                    <div style="color: #aba3a3; font-size: 0.75em;"><?= $flightitem['class'] ?> | <?= $flightitem['refundable']==='Y' ? 'Refundable' : 'Non Refundable' ?></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-lg-2 col-md-2" style="padding: 10px;">
                                            <div style="border-right: 0px solid #e0dcdc; padding: 5px 5px;">
                                                <div style="text-align: center;">
                                                    <div class="title">
														<?php 
														$infant_price = isset($flight[$key]["infant_price"]) ? floatval($flight[$key]["infant_price"]) : 0;
														// $adult = isset($flight[$key]["adult"]) ? floatval($flight[$key]["adult"]) : 0;
														// $child = isset($flight[$key]["child"]) ? floatval($flight[$key]["child"]) : 0;
														// $infant = isset($flight[$key]["infant"]) ? floatval($flight[$key]["infant"]) : 0;
														$adult = isset($state['adult']) ? intval($state['adult']) : 0;
														$child = isset($state['child']) ? intval($state['child']) : 0;
														$infant = isset($state['infant']) ? intval($state['infant']) : 0;
										
                                                        $final_total = ($flight[$key]["price"] * ($adult + $child)) + ($infant_price * $infant);
														if($currentuser['is_admin']!=='1' && $currentuser['type']=='B2B') {
															$final_total += $flight[$key]["admin_markup"];
                                                        } 
                                                        if($currentuser['is_admin']=='1') {
                                                            $costprice = (floatval($flight[$key]['cost_price']) * ($adult + $child)) + ($infant_price * $infant);
                                                        }
                                                        else if($currentuser['is_admin']!=='1' && $currentuser['type']=='B2B') {
															// $costprice = $flight[$key]["price"] + ($infant_price * $infant);
															$costprice = ($flight[$key]["price"] * ($adult + $child)) + ($infant_price * $infant);
                                                        }
                                                        else {
                                                            $costprice = 0;
                                                        }
                                                        ?>
                                                        <?php if($final_total>0 && $controller->show_price(intval($flightitem['user_id']))) { ?>
                                                            <i class='fa fa-inr'></i> <?= number_format($final_total,2,".",","); ?>
                                                        <?php } else { ?>
                                                            <span class="total">Available</span>
                                                        <?php } ?>
                                                    </div>
                                                    <?php if($costprice>0 && $controller->show_price(intval($flightitem['user_id']))) { ?>
                                                        <div class="costprice" style="color: #aba3a3; font-size: 0.75em;">Cost : <i class='fa fa-inr'></i><?= number_format($costprice,2,".",","); ?></div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="col-xs-12 col-sm-12 col-lg-2 col-md-2" style="padding: 10px;">
                                            <div style="padding: 5px 0px;">
												<?php if($sale_type === 'request') { ?>
													<a href="<?php echo base_url(); ?>search/flightdetails/<?php echo $flight[$key]["id"];?>" class="btn btn-orange" style="float: right; margin: 0px 10px;">REQUEST NOW</a>
												<?php } else if($sale_type==="live" || $sale_type==="api") { ?>
													<a href="<?php echo base_url(); ?>search/flightdetails/<?php echo $flight[$key]["id"];?>" class="btn btn-orange" style="float: right; margin: 0px 10px;">BOOK NOW</a>
												<?php } else { ?>
													<a href="<?php echo base_url(); ?>search/flightdetails/<?php echo $flight[$key]["id"];?>" class="btn btn-orange" style="float: right; margin: 0px 10px;">REQUEST NOW</a>
												<?php } ?>
                                            </div>
                                        </div> -->
                                    </div>
                                    <div class="row">
                                        <div style="border-top: 2px solid #b1b1b17d; margin: 1px 20px;">
                                            <div class="col-xs-12 col-sm-12 col-lg-4 col-md-4">
                                                <?php if($flightitem["live_fare"]>0 && $flightitem["sale_type"]!=='api') {?>
                                                    <!-- <div style="color: #1433a5; font-size: 0.80em; display: inline-block;">Current System fare : <i class='fa fa-inr'></i> <?= number_format($flightitem["live_fare"],2,".",","); ?> | <?= ($flightitem["seatsavailable"]>10?'10+':$flightitem["seatsavailable"]) ?> seat(s) left</div> -->
													<div style="color: #1433a5; font-size: 0.80em; display: inline-block;"><?= $rmkrs ?></div>
                                                <?php } ?>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-lg-8 col-md-8" style="text-align: right;">
                                                <?php if(isset($flightitem["sale_type"]) && $flightitem["sale_type"]!=='api' && $sale_type=='request') {?>
                                                    <div class="blink_me">Seats & rates are subject to availability. Confirm before booking.</div>
												<?php } 
												else if(isset($flightitem["sale_type"]) && $flightitem["sale_type"]==='api') { ?>
													<div class="blink_me"><?= $flightitem['remarks'] ?></div>
												<?php } ?>
                                            </div>
                                        </div>
                                    </div>

									<div class="list-content">
										<div class="<?php echo $class?> list-info f-list-info" style="padding:5px 5px 0 5px;">
											<?php if($currentuser['is_admin']=='1' && $flight[$key]["sale_type"]!=="api") { ?>
												<div class="action_icons">
													<ul>
														<?php if( intval($flight[$key]["companyid"]) === intval($currentuser['companyid']) ) { ?>
															<li title='Edit ticket' alt='Edit ticket'>
																<button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#ticket_form" data-mode='update' data-object="<?= htmlspecialchars(json_encode($flight_attributes[$key], JSON_HEX_APOS));?>">
																	<span>
																		<i class="fa fa-pencil" aria-hidden="true"></i>
																	</span>
																</button>
															</li>
														<?php } ?>
														<?php if( intval($flight[$key]["companyid"]) !== intval($currentuser['companyid']) ) { ?>
															<li title='Clone ticket' alt='Clone ticket'>
																<button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#ticket_form" data-mode='clone' data-object="<?= htmlspecialchars(json_encode($flight_attributes[$key], JSON_HEX_APOS));?>">
																	<span>
																		<i class="fa fa-files-o" aria-hidden="true"></i>
																	</span>
																</button>
															</li>
														<?php } ?>
													</ul>
												</div>
											<?php } ?>
										</div><!-- end f-list-info -->
									</div><!-- end list-content -->
								</div><!-- end f-list-block -->
								<!-- Button trigger modal -->
								<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
									Launch demo modal
								</button> -->
								<?php
							} 
						?>
							</div>
							<!-- Round trip flights -->							
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 no-right-padding">
						<?php
						 	foreach($flight as $key=>$value)
							{
								$flight_data = [];
								$flightitem = $flight[$key];
								if($flightitem['direction'] === 'OB' || $flightitem['direction'] === 'OI') continue;
								
								$segments = isset($flightitem['segments'])?$flightitem['segments']:false;

								$stops_details = [];
								if($segments) {
									$firstairline = $flightitem['flight_no'];
									for ($si=0; $si < count($segments)-1; $si++) { 
										$stop_name = $segments[$si]['arrival_city'];
										$arrival_datetime = $segments[$si]['arrival_datetime'];

										$next_departure_datetime = $segments[$si+1]['departure_datetime'];
										$next_aircode = $segments[$si+1]['aircode']; //it was aircide changing it to aircode
										$next_flight_number = $segments[$si+1]['flight_number'];
										$next_dept_terminal = "T-".$segments[$si]['departure_terminal'];

										$layover = intval((strtotime($next_departure_datetime)-strtotime($arrival_datetime))/60);
										$layover = intval($layover/60).'h '.intval($layover%60).'m';

										$sameairline = ($firstairline === "$next_aircode $next_flight_number");

										$stops_details[] = array('stop_name' => $stop_name, 'layover' => $layover, 'sameairline' => $sameairline, 'next_airline' => "$next_aircode $next_flight_number", 'terminal' => $next_dept_terminal);

										$firstairline = "$next_aircode $next_flight_number";
									}
								}

								$class = "";
                                //echo $flight[$key]["user_id"] . " - " . $this->session->userdata('user_id');
                                log_message('debug', 'Flight Data => '.json_encode($value));

								if($flight[$key]["user_id"]==$this->session->userdata('user_id')) {
									//echo "supplier_ticket";
									$class = "supplier_ticket";
								} 
								else if($flight[$key]["user_id"]==-1) {
									//API ticket
									$class = "api_ticket";
									//echo "<br/>NOT supplier_ticket";
								}
								else {
									$class = "";
                                }
                                
                                //if($flight[$key]["adult_total"]>0) {
								$splcode = 'special fare';
								$rmkrs = '';
                                if(isset($flight[$key]["sale_type"]) && $flight[$key]["sale_type"] !== 'api') {
                                    $source_city = $flightitem['source_city_code'];
                                    $destination_city = $flightitem['destination_city_code'];
                                    $airline_name = $flight[$key]["airline"];
                                    $dept_date = strtotime($flight[$key]["departure_date_time"]);
                                    $arrv_date = strtotime($flight[$key]["arrival_date_time"]);
                                    $dateDiff = intval((strtotime($flight[$key]["arrival_date_time"])-strtotime($flight[$key]["departure_date_time"]))/60);
									$dept_terminal = $flightitem['departure_terminal'];
									$arrv_terminal = $flightitem['arrival_terminal'];
                                }
                                else {
                                    $splcode = 'system/live fare';
                                    $source_city = $flightitem['source_code'];
                                    $destination_city = $flightitem['destination_code'];
                                    $airline_name = $flight[$key]["airline_name"];
                                    $dept_date = strtotime($flight[$key]["departure_date_time"]);
                                    $arrv_date = strtotime($flight[$key]["arrival_date_time"]);
									$dateDiff = intval((strtotime($flight[$key]["arrival_date_time"])-strtotime($flight[$key]["departure_date_time"]))/60);
									
									$dept_terminal = "T-".$flightitem['departure_terminal'];
									$arrv_terminal = "T-".$flightitem['arrival_terminal'];
                                }
                                
								//$flightitem
                                if((intval($flight[$key]["companyid"])===intval($currentuser['companyid'])) && intval($currentuser['is_admin'])===1) {
									$splcode = $flight[$key]["data_collected_from"];
									$rmkrs = $flight[$key]["remarks"];
								}
								else if((intval($flight[$key]["companyid"])!==intval($currentuser['companyid'])) && intval($currentuser['is_admin'])===1) {
									$splcode = $flight[$key]["companyname"];
									$rmkrs = $flight[$key]["remarks"];
								}
								
								$sale_type = $flight[$key]["sale_type"];

                                $auto_corrected = $flight[$key]["live_corrected"];
                                $stops = intval($flight[$key]["no_of_stops"]);

                                if($stops === 0) {
                                    $stop_text = "Non Stop";
                                }
                                else {
                                    $stop_text = "$stops Stop(s)";
								}
								
								$flight_data['image'] = $flightitem['image'];
								$flight_data['airline'] = $airline_name;
								$flight_data['flight_no'] = $flightitem['flight_no'];
								$flight_data['splcode'] = $splcode;
								$flight_data['dept_date'] = $dept_date;
								$flight_data['formated_dept_date'] = date('H:i', $dept_date);
								$flight_data['arrv_date'] = $arrv_date;
								$flight_data['formated_arrv_date'] = date('H:i', $arrv_date);
								$flight_data['source_city'] = $source_city;
								$flight_data['dept_terminal'] = $dept_terminal;
								$flight_data['stop_details'] = $stops_details;
								$flight_data['destination_city'] = $destination_city;
								$flight_data['arrv_terminal'] = $arrv_terminal;
								$flight_data['no_of_person'] = $flightitem['no_of_person'];
								$flight_data['sale_type'] = $sale_type;
								$flight_data['id'] = $flightitem["id"];
								$flight_data['companyid'] = intval($flightitem["companyid"]);

								$infant_price = isset($flight[$key]["infant_price"]) ? floatval($flight[$key]["infant_price"]) : 0;

								$adult = isset($state['adult']) ? intval($state['adult']) : 0;
								$child = isset($state['child']) ? intval($state['child']) : 0;
								$infant = isset($state['infant']) ? intval($state['infant']) : 0;
				
								$final_total = ($flight[$key]["price"] * ($adult + $child)) + ($infant_price * $infant);
								if($currentuser['is_admin']!=='1' && $currentuser['type']=='B2B') {
									$final_total += $flight[$key]["admin_markup"];
								} 
								if($currentuser['is_admin']=='1') {
									$costprice = (floatval($flight[$key]['cost_price']) * ($adult + $child)) + ($infant_price * $infant);
								}
								else if($currentuser['is_admin']!=='1' && $currentuser['type']=='B2B') {
									// $costprice = $flight[$key]["price"] + ($infant_price * $infant);
									$costprice = ($flight[$key]["price"] * ($adult + $child)) + ($infant_price * $infant);
								}
								else {
									$costprice = 0;
								}
								
								$flight_data['costprice'] = $costprice;
								$flight_data['final_total'] = $final_total;

								?>

                                <div class="<?php echo $class?> list-block main-block f-list-block inbound" data="<?= htmlspecialchars(json_encode($flight_data), ENT_QUOTES, 'UTF-8') ?>">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-lg-3 col-md-3" style="padding: 10px;">
                                            <div style="display: flex; border-right: 1px solid #e0dcdc;">
                                                <?php if(isset($flightitem['image']) && $flightitem['image']!=='') { ?>
                                                    <img src="<?php echo base_url(); ?>upload/thumb/<?= $flightitem['image'] ?>" class="img-responsive airline-logo" alt="flight-img" />
                                                <?php } else { ?>
                                                    <img src="<?php echo base_url(); ?>upload/thumb/flight.png" class="img-responsive airline-logo" alt="flight-img" />
                                                <?php } ?>
                                                <div>
                                                    <div class="title"><?= $airline_name?></div>
                                                    <div style="color: #aba3a3; font-size: 0.85em;"><?= $flightitem['flight_no'] ?> (<?= $splcode ?>)</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-lg-5 col-md-5" style="padding: 10px;">
											<div style="display: flex; border-right: 1px solid #e0dcdc;">
												<?php 
													$dt1 = date_create(date('Y-m-d 00:00:00', $dept_date));
													$dt2 = date_create(date('Y-m-d 23:59:59', $arrv_date));
													$date_diff = date_diff($dt1,$dt2);
													$days = intval(date_diff($dt1,$dt2)->d);
												?>
                                                <div style="flex: 1 0 10%; margin: 2px 7px;">
                                                    <div style="text-align: center;">
                                                        <span class="title"><?= date('H:i', $dept_date) ?></span>
                                                        <div style="color: #aba3a3; font-size: 0.85em;"><?= $source_city ?>(<?= $dept_terminal ?>)</div>
                                                    </div>
                                                </div>
                                                <div style="flex: 1 0 25%; margin: auto 0px;">
                                                    <div style="margin: 0 11%; padding-bottom: 0.55em; border-bottom: 2px solid #cacaca; display: flex;">
                                                        <?php 
                                                        if($stops>0) {
                                                            $wth = 100/$stops;
                                                        }
                                                        else {
                                                            $wth = 100;
                                                        }
                                                        for ($i=0; $i < $stops ; $i++) { 
															$stop_detail = $stops_details[$i];
															$sameairline = boolval($stop_detail['sameairline']);
															$sameair_class = "stopper-icon";
															if($sameairline) {
																$sameair_class = $sameair_class." stopper-icon-gr";
															}
															?>
                                                            <span class="stopstyle" <?= "style = 'width: ".$wth."%; left: ".($wth/2)."%; '"?>>
                                                                <i class="<?= $sameair_class ?>">
                                                                    <div style="transform-origin: 0% 100%;" class="msg">
																		<table style="width: 200px;">
																			<tr style="opacity: 0.75;">
																				<td style="padding-right: 5px; text-align: left;">Stop</td>
																				<td style="padding-right: 5px; text-align: left;">Airline</td>
																				<td style="text-align: right;">Layover Time</td>
																			</tr>
																			<tr>
																				<td style="padding-right: 5px; text-align: left;"><?= $stop_detail['stop_name'] ?> <?= (isset($stop_detail['terminal']) && $stop_detail['terminal']!=='') ? (' ('.$stop_detail['terminal'].')') : '' ?></td>
																				<td style="padding-right: 5px; text-align: left;"><?= $stop_detail['next_airline'] ?></td>
																				<td style="text-align: right;"><?= $stop_detail['layover'] ?></td>
																			</tr>
																		</table>
                                                                    </div>
                                                                </i>
                                                            </span>
                                                        <?php } ?>
                                                    </div>
                                                    <div style="margin: 3px 0px 1px 5px; color: #aba3a3; font-size: 0.80em; text-align: center;"><span class="title1"><?= intval($dateDiff/60)."h ".($dateDiff%60)."m"; ?></span> | <?= $stop_text ?></div>
                                                </div>
                                                <div style="flex: 1 0 10%;">
                                                    <div style="text-align: center;">
														<span class="title"><?= date('H:i', $arrv_date) ?></span>
														<?php if($days>0) { ?>
															<span class="date_diff"><?= $date_diff->format("%R%a ").($days>1 ? 'days' : 'day') ?></span>
														<?php } ?>
                                                        <div style="color: #aba3a3; font-size: 0.85em;"><?= $destination_city ?>(<?= $arrv_terminal ?>)</div>
                                                    </div>										
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-lg-2 col-md-2" style="padding: 10px;">
                                            <div style="border-right: 1px solid #e0dcdc; padding: 5px 0px;">
                                                <div style="text-align: center;">
                                                    <div class="title"><?= $flightitem['no_of_person'] ?> seats(s) left</div>
                                                    <div style="color: #aba3a3; font-size: 0.75em;"><?= $flightitem['class'] ?> | <?= $flightitem['refundable']==='Y' ? 'Refundable' : 'Non Refundable' ?></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-lg-2 col-md-2" style="padding: 10px;">
                                            <div style="border-right: 0px solid #e0dcdc; padding: 5px 5px;">
                                                <div style="text-align: center;">
                                                    <div class="title">
														<?php 
														// $infant_price = isset($flight[$key]["infant_price"]) ? floatval($flight[$key]["infant_price"]) : 0;

														// $adult = isset($state['adult']) ? intval($state['adult']) : 0;
														// $child = isset($state['child']) ? intval($state['child']) : 0;
														// $infant = isset($state['infant']) ? intval($state['infant']) : 0;
										
                                                        // $final_total = ($flight[$key]["price"] * ($adult + $child)) + ($infant_price * $infant);
														// if($currentuser['is_admin']!=='1' && $currentuser['type']=='B2B') {
														// 	$final_total += $flight[$key]["admin_markup"];
                                                        // } 
                                                        // if($currentuser['is_admin']=='1') {
                                                        //     $costprice = (floatval($flight[$key]['cost_price']) * ($adult + $child)) + ($infant_price * $infant);
                                                        // }
                                                        // else if($currentuser['is_admin']!=='1' && $currentuser['type']=='B2B') {
														// 	// $costprice = $flight[$key]["price"] + ($infant_price * $infant);
														// 	$costprice = ($flight[$key]["price"] * ($adult + $child)) + ($infant_price * $infant);
                                                        // }
                                                        // else {
                                                        //     $costprice = 0;
														// }
														
														// $flight_data['costprice'] = $costprice;
														// $flight_data['final_total'] = $final_total;

                                                        ?>
                                                        <?php if($final_total>0 && $controller->show_price(intval($flightitem['user_id']))) { ?>
                                                            <i class='fa fa-inr'></i> <?= number_format($final_total,2,".",","); ?>
                                                        <?php } else { ?>
                                                            <span class="total">Available</span>
                                                        <?php } ?>
                                                    </div>
                                                    <?php if($costprice>0 && $controller->show_price(intval($flightitem['user_id']))) { ?>
                                                        <div class="costprice" style="color: #aba3a3; font-size: 0.75em;">Cost : <i class='fa fa-inr'></i><?= number_format($costprice,2,".",","); ?></div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="col-xs-12 col-sm-12 col-lg-2 col-md-2" style="padding: 10px;">
                                            <div style="padding: 5px 0px;">
												<?php if($sale_type === 'request') { ?>
													<a href="<?php echo base_url(); ?>search/flightdetails/<?php echo $flight[$key]["id"];?>" class="btn btn-orange" style="float: right; margin: 0px 10px;">REQUEST NOW</a>
												<?php } else if($sale_type==="live" || $sale_type==="api") { ?>
													<a href="<?php echo base_url(); ?>search/flightdetails/<?php echo $flight[$key]["id"];?>" class="btn btn-orange" style="float: right; margin: 0px 10px;">BOOK NOW</a>												
												<?php } else { ?>
													<a href="<?php echo base_url(); ?>search/flightdetails/<?php echo $flight[$key]["id"];?>" class="btn btn-orange" style="float: right; margin: 0px 10px;">REQUEST NOW</a>
												<?php } ?>
                                            </div>
                                        </div> -->
                                    </div>
                                    <div class="row">
                                        <div style="border-top: 2px solid #b1b1b17d; margin: 1px 20px;">
                                            <div class="col-xs-12 col-sm-12 col-lg-4 col-md-4">
                                                <?php if($flightitem["live_fare"]>0 && $flightitem["sale_type"]!=='api') {?>
													<div style="color: #1433a5; font-size: 0.80em; display: inline-block;"><?= $rmkrs ?></div>
                                                <?php } ?>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-lg-8 col-md-8" style="text-align: right;">
                                                <?php if(isset($flightitem["sale_type"]) && $flightitem["sale_type"]!=='api' && $sale_type=='request') {?>
                                                    <div class="blink_me">Seats & rates are subject to availability. Confirm before booking.</div>
												<?php } 
												else if(isset($flightitem["sale_type"]) && $flightitem["sale_type"]==='api') { ?>
													<div class="blink_me"><?= $flightitem['remarks'] ?></div>
												<?php } ?>
                                            </div>
                                        </div>
                                    </div>

									<div class="list-content">
										<div class="<?php echo $class?> list-info f-list-info" style="padding:5px 5px 0 5px;">
											<?php if($currentuser['is_admin']=='1' && $flight[$key]["sale_type"]!=="api") { ?>
												<div class="action_icons">
													<ul>
														<?php if( intval($flight[$key]["companyid"]) === intval($currentuser['companyid']) ) { ?>
															<li title='Edit ticket' alt='Edit ticket'>
																<button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#ticket_form" data-mode='update' data-object="<?= htmlspecialchars(json_encode($flight_attributes[$key], JSON_HEX_APOS));?>">
																	<span>
																		<i class="fa fa-pencil" aria-hidden="true"></i>
																	</span>
																</button>
															</li>
														<?php } ?>
														<?php if( intval($flight[$key]["companyid"]) !== intval($currentuser['companyid']) ) { ?>
															<li title='Clone ticket' alt='Clone ticket'>
																<button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#ticket_form" data-mode='clone' data-object="<?= htmlspecialchars(json_encode($flight_attributes[$key], JSON_HEX_APOS));?>">
																	<span>
																		<i class="fa fa-files-o" aria-hidden="true"></i>
																	</span>
																</button>
															</li>
														<?php } ?>
													</ul>
												</div>
											<?php } ?>
										</div><!-- end f-list-info -->
									</div><!-- end list-content -->
								</div><!-- end f-list-block -->
								<?php
							} 
						?>
							</div>							
						<?php 
						}
						else
						{
							?>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<div class="page-heading mg-bot-55">
									<?php 
									if($currentuser['is_admin']=='1') {
									?>
										<h2>No Result Found !!!</h2>
										<hr class="heading-line">
									<?php 
									}
									else {
									?>
										<?= $this->load->view('capture_user_query',$state, TRUE);?>
									<?php } ?>
								</div>
								</div>	
							<?php
						}									
					?>

				</div><!-- end columns -->
				<!-- Footer -->
				<?php 
				if(!empty($flight)) {
				?>
				<div class="fixed-footer" id="selection_summary">
					<div class="container-base footer-content">
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<div class="row">
									<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5" style="border-right: 1px solid #cdcdcd;">
										<div class="row">
											<div class="col-xs-12 col-sm-12 col-lg-5 col-md-5 offset-md-1" style="padding: 10px; margin-left: 20px;">
												<div style="display: flex; border-right: 1px solid #e0dcdc;">
													<img id="ob_image" src="<?php echo base_url(); ?>upload/thumb/flight.png" class="img-responsive airline-logo" alt="flight-img" />
													<div>
														<div class="summary_title" id="ob_airline">Airline name</div>
														<div style="color: #aba3a3; font-size: 0.85em;" id="ob_flight">Flight Code</div>
													</div>
												</div>
											</div>
											<div class="col-xs-12 col-sm-12 col-lg-5 col-md-5" style="padding: 10px;">
												<div style="display: flex; border-right: 0px solid #e0dcdc;">
													<div style="flex: 1 0 10%; margin: 2px 7px;">
														<div style="text-align: center;">
															<span class="summary_title" id="ob_depttime">Dept.Time</span>
															<div style="color: #aba3a3; font-size: 0.85em;" id="ob_deptcity">City/Terminal</div>
														</div>
													</div>
													<div style="flex: 1 0 25%; margin: auto 0px;">
														<div style="margin: 0 11%; padding-bottom: 0.55em; border-bottom: 2px solid #cacaca; display: flex;"></div>
													</div>
													<div style="flex: 1 0 10%;">
														<div style="text-align: center;">
															<span class="summary_title" id="ob_arrvtime">Arrv.Time</span>
															<div style="color: #aba3a3; font-size: 0.85em;" id="ob_arrvcity">City/Terminal</div>
														</div>										
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5" style="border-right: 1px solid #cdcdcd;">
										<div class="row">
											<div class="col-xs-12 col-sm-12 col-lg-5 col-md-5 offset-md-1" style="padding: 10px; margin-left: 20px;">
												<div style="display: flex; border-right: 1px solid #e0dcdc;">
													<img id="ib_image" src="<?php echo base_url(); ?>upload/thumb/flight.png" class="img-responsive airline-logo" alt="flight-img" />
													<div>
														<div class="summary_title" id="ib_airline">Airline name</div>
														<div style="color: #aba3a3; font-size: 0.85em;" id="ib_flight">Flight Code</div>
													</div>
												</div>
											</div>
											<div class="col-xs-12 col-sm-12 col-lg-5 col-md-5" style="padding: 10px;">
												<div style="display: flex; border-right: 0px solid #e0dcdc;">
													<div style="flex: 1 0 10%; margin: 2px 7px;">
														<div style="text-align: center;">
															<span class="summary_title" id="ib_depttime">Dept.Time</span>
															<div style="color: #aba3a3; font-size: 0.85em;" id="ib_deptcity">City/Terminal</div>
														</div>
													</div>
													<div style="flex: 1 0 25%; margin: auto 0px;">
														<div style="margin: 0 11%; padding-bottom: 0.55em; border-bottom: 2px solid #cacaca; display: flex;"></div>
													</div>
													<div style="flex: 1 0 10%;">
														<div style="text-align: center;">
															<span class="summary_title" id="ib_arrvtime">Arrv.Time</span>
															<div style="color: #aba3a3; font-size: 0.85em;" id="ib_arrvcity">City/Terminal</div>
														</div>										
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-1 col-lg-1" style="min-height: 9vh; padding: 25px;">
										<i class='fa fa-inr'></i><div id="total" style="padding: 0px 5px; display: inline; font-size: 1.2em;"></div>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-1 col-lg-1">
										<form class="pg-search-form" id="book_flights" action="<?php echo base_url(); ?>search/flightdetails_round" method="post" onsubmit="return validate_selection()">
											<input type="hidden" id="trip_type" name="trip_type" value="ROUND"> 
											<input type="hidden" id="ticket_type" name="ticket_type" value="OB"> 
											<input type="hidden" id="ob_selected_ticket" name="ob_selected_ticket" value="">
											<input type="hidden" id="ib_selected_ticket" name="ib_selected_ticket" value="">
											<button type="submit" class="btn btn-orange" id="btn_one_way" style="float: right;padding: 25px;margin-top: 0px;">Go Next</button>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php 
				}
				?>
			</div><!-- end row -->
		</div><!-- end container -->
	</div><!-- end flight-listings -->
</section><!-- end innerpage-wrapper -->

<!-- ticket modify / ticket clone dialog -->
<div class="modal fade" id="ticket_form" tabindex="-1" role="dialog" aria-labelledby="ticket_form_label" aria-hidden="true" style="z-index: 10000;">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="ticket_form_label" style="display: initial;">Modal title</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 edit-item" id="edit-item">
						<div class="row">
							<div class="col-sm-6 col-md-2 col-lg-2">
								<!-- Selected airline -->
								<label>Airline</label>
							</div>
							<div class="col-sm-6 col-md-4 col-lg-4">
								<select class="form-control" name="airline" id="airline">
									<option value="-1" selected>Airline</option>
									<?php
									if($airlines && count($airlines)>0) {
										foreach($airlines as $key=>$value)
										{ ?>
											<option value="<?php echo $airlines[$key]["id"];?>"><?php echo $airlines[$key]["display_name"];?></option>
										<?php
										}
									}
									?>										  
								</select>
							</div>
							<div class="col-sm-6 col-md-2 col-lg-2">
								<!-- Flight Number, PAX & base rate -->
								<label></label>
							</div>
							<div class="col-sm-6 col-md-4 col-lg-4">
								<!--<input class="form-control datepicker" style="width: 60%; display: inline-block; padding: 0px 2px;" placeholder="mm/dd/yyyy" name="dept_date" id="dept_date" pattern="[0-9]{2}/[0-9]{2}/[0-9]{4}" value="" readonly/>
								<input class="form-control" placeholder="HH:mm" style="width: 35%; display: inline-block; padding: 0px 2px;" name="dept_date_time" id="dept_date_time" pattern="[0-9]{2}:[0-9]{2}" value=""/> -->
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6 col-md-2 col-lg-2">
								<!-- Flight Number, PAX & base rate -->
								<label>Flight #</label>
							</div>
							<div class="col-sm-6 col-md-4 col-lg-4">
								<input type="text" id="flight_number" class="form-control" placeholder="Please enter [Flight Number]" autocomplete="off" value="" required/>
							</div>
							<div class="col-sm-6 col-md-2 col-lg-2">
								<!-- Flight Number, PAX & base rate -->
								<label>Dept. Date</label>
							</div>
							<div class="col-sm-6 col-md-4 col-lg-4">
								<input class="form-control datepicker" style="width: 60%; display: inline-block; padding: 0px 2px;" placeholder="mm/dd/yyyy" name="dept_date" id="dept_date" pattern="[0-9]{2}/[0-9]{2}/[0-9]{4}" value="" readonly/>
								<input class="form-control" placeholder="HH:mm" style="width: 35%; display: inline-block; padding: 0px 2px;" name="dept_date_time" id="dept_date_time" pattern="[0-9]{2}:[0-9]{2}" value=""/>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6 col-md-2 col-lg-2">
								<!-- Flight Number, PAX & base rate -->
								<label>No Of PAX</label>
							</div>
							<div class="col-sm-6 col-md-4 col-lg-4">
								<input type="text" id="no_of_pax" class="form-control" placeholder="Please enter [PAX]" autocomplete="off" value="" required/>
							</div>
							<div class="col-sm-6 col-md-2 col-lg-2">
								<!-- Flight Number, PAX & base rate -->
								<label>Arrival Date</label>
							</div>
							<div class="col-sm-6 col-md-4 col-lg-4">
								<input class="form-control datepicker" style="width: 60%; display: inline-block; padding: 0px 2px;" placeholder="mm/dd/yyyy" name="arrv_date" id="arrv_date" pattern="[0-9]{2}/[0-9]{2}/[0-9]{4}" value="" readonly/>
								<input class="form-control" placeholder="HH:mm" style="width: 35%; display: inline-block; padding: 0px 2px;" name="arrv_date_time" id="arrv_date_time" pattern="[0-9]{2}:[0-9]{2}" value=""/>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6 col-md-2 col-lg-2">
								<!-- Flight Number, PAX & base rate -->
								<label>Price</label>
							</div>
							<div class="col-sm-6 col-md-4 col-lg-4">
								<input type="text" id="price" class="form-control" placeholder="Please enter [Ticket Base Price]" autocomplete="off" value="" required/>  
							</div>
							<div class="col-sm-6 col-md-2 col-lg-2">
								<!-- Flight Number, PAX & base rate -->
								<label>TAG</label>
							</div>
							<div class="col-sm-6 col-md-4 col-lg-4">
								<input type="text" id="tag" class="form-control" placeholder="Please enter [TAG]" autocomplete="off" value="" required/>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button id="btncloseticketform" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			<button id="btnsaveticket" type="button" class="btn btn-primary" data-ticket="" onclick="javascript:save_ticket(this);">Save changes</button>
		</div>
		</div>
	</div>
</div>		

<div id="getquote" class="modal custom-modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h3 class="modal-title">Enter No. of Passengers</h3>
			</div><!-- end modal-header -->
			
			<div class="modal-body">
					<form id="frm_sendquote" action="" method="POST">
					<div class="form-group">                        		
						<input type="number" class="form-control" placeholder="Enter No. of Passengers" name="no_of_person" id="no_of_person" maxlength="10" />
					</div><!-- end form-group -->
						<button type="submit" class="btn btn-orange">Submit</button>
						<div class="form-group" id="forgot_status">
						</div>
					</form>
				
			</div><!-- end modal-bpdy -->
		</div><!-- end modal-content -->
	</div><!-- end modal-dialog -->
</div><!-- end edit-profile -->

<script language="javascript">
	var show_price = <?= $controller->show_price(intval($flightitem['user_id'])) ?>;

	var outbound_selected_element = null;
	var outbound_selected_ticket = null;

	var inbound_selected_element = null;
	var inbound_selected_ticket = null;

	$( document ).ready(function() {
		fetch_destination(<?= intval($post[0]["destination"])?>);

		$("#showcostprice").change(function() {
			if(this.checked) {
				$('.costprice').show();
			} else {
				$('.costprice').hide();
			}
		});

		//Outbound flight events
		$('.outbound').on('click', function(event) {
			var element = $(event.currentTarget); // Button that triggered the modal
			if(outbound_selected_element) {
				$(outbound_selected_element).removeClass('selected_element');
			}

			if($(element).attr('data')) {
				outbound_selected_ticket = JSON.parse($(element).attr('data'));
			}

			$(element).addClass('selected_element');
			outbound_selected_element = element;

			showSelectedSection('ob', outbound_selected_ticket);

			// if(outbound_selected_ticket) {
			// 	alert('Outbound : ' + JSON.stringify(outbound_selected_ticket));
			// }
		});

		//Inbound flight events
		$('.inbound').on('click', function(event) {
			var element = $(event.currentTarget); // Button that triggered the modal
			if(inbound_selected_element) {
				$(inbound_selected_element).removeClass('selected_element');
			}

			if($(element).attr('data')) {
				inbound_selected_ticket = JSON.parse($(element).attr('data'));
			}

			$(element).addClass('selected_element');
			inbound_selected_element = element;

			showSelectedSection('ib', inbound_selected_ticket);

			// if(inbound_selected_ticket) {
			// 	alert('Inbound : ' + JSON.stringify(inbound_selected_ticket));
			// }
		});

		$('#ticket_form').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget); // Button that triggered the modal
			var flight = button.data('object'); // Extract info from data-* attributes
			var mode = button.data('mode'); // Extract info from data-* attributes

			// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
			// Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
			var modal = $(this);

			$(this).draggable({
				handle: ".modal-header"
			});					
			modal.find('.modal-title').text(`${mode} ticket ` + flight['source_city'] + ' to ' + flight['destination_city']);
			modal.find('.modal-body #flight_number').val(flight['flight_no']);
			//modal.find('.modal-body #dept_date').val(formatDate(flight['departure_date']));
			modal.find('.modal-body #dept_date').val(moment(flight['departure_date'], 'DD/MM/YYYY').format('MM/DD/YYYY'));
			modal.find('.modal-body #dept_date_time').val(flight['departure_time']);
			//modal.find('.modal-body #arrv_date').val(formatDate(flight['arrival_date']));
			modal.find('.modal-body #airline').val(flight['airlineid']).change();
			modal.find('.modal-body #arrv_date').val(moment(flight['arrival_date'], 'DD/MM/YYYY').format('MM/DD/YYYY'));
			modal.find('.modal-body #arrv_date_time').val(flight['arrival_time']);
			modal.find('.modal-body #no_of_pax').val(flight['no_of_person']);
			modal.find('.modal-body #price').val(flight['price']);
			modal.find('.modal-body #tag').val(flight['tag']);

			modal.find('.modal-footer #btnsaveticket').data('ticket', flight);
			modal.find('.modal-footer #btnsaveticket').data('mode', mode);
			
		});
	});

	function validate_selection() {
		let flag = false;
		if(outbound_selected_ticket && outbound_selected_ticket.final_total) {
			flag = true;
		}

		if(inbound_selected_ticket && inbound_selected_ticket.final_total) {
			flag = flag && true;
		}

		if(!flag) {
			alert("Please select both directional tickets, to proceed further");
		}

		return flag;
	}

	function showSelectedSection(type, selected_ticket) {
		var base_url = "<?php echo base_url(); ?>upload/thumb/";
		base_url += (selected_ticket.image === undefined || selected_ticket.image==='') ? 'flight.png' : selected_ticket.image;
		
		//ob_flight, ob_depttime, ob_deptcity, ob_arrvtime, ob_arrvcity, 
		
		$('#'+type+'_image').attr('src', base_url);
		$('#'+type+'_airline').text(selected_ticket.airline);
		$('#'+type+'_flight').text(selected_ticket.flight_no + `(${selected_ticket.splcode})`);
		$('#'+type+'_depttime').text(selected_ticket.formated_dept_date);
		$('#'+type+'_deptcity').text(selected_ticket.source_city);
		$('#'+type+'_arrvtime').text(selected_ticket.formated_arrv_date);
		$('#'+type+'_arrvcity').text(selected_ticket.destination_city);
		
		// alert(outbound_selected_ticket.final_total);
		// alert(inbound_selected_ticket.final_total);

		// set value for posting
		$('#ticket_type').val('round');
		$('#ob_selected_ticket').val(JSON.stringify(outbound_selected_ticket));
		$('#ib_selected_ticket').val(JSON.stringify(inbound_selected_ticket));

		if(outbound_selected_ticket && inbound_selected_ticket) {
			$('#book_flights').attr('action', "<?php echo base_url(); ?>search/flightdetails_round/"+outbound_selected_ticket.id+'/'+inbound_selected_ticket.id);
			$('#total').text(outbound_selected_ticket.final_total + inbound_selected_ticket.final_total);
		}
	}

	function save_ticket(ev) {
		var modelButton = $(ev);
		var ticket = modelButton.data('ticket');
		var mode = modelButton.data('mode');
		var modal = $('#ticket_form');

		var dept_date_time = moment(modal.find('.modal-body #dept_date').val()+' '+modal.find('.modal-body #dept_date_time').val(), 'MM-DD-YYYY HH:mm').format('YYYY-MM-DD HH:mm');
		var arrv_date_time = moment(modal.find('.modal-body #arrv_date').val()+' '+modal.find('.modal-body #arrv_date_time').val(), 'MM-DD-YYYY HH:mm').format('YYYY-MM-DD HH:mm');

		console.log(dept_date_time + ' <-> ' + arrv_date_time);

		if(arrv_date_time <= dept_date_time) {
			alert('Arrival date must be greater than departure date');
			return;
		}

		ticket.airlineid = modal.find('.modal-body #airline').val();
		ticket.flight_no = modal.find('.modal-body #flight_number').val();
		ticket.departure_date = moment(modal.find('.modal-body #dept_date').val(), 'MM/DD/YYYY').format('YYYY/MM/DD');
		ticket.departure_time = modal.find('.modal-body #dept_date_time').val();
		ticket.arrival_date = moment(modal.find('.modal-body #arrv_date').val(), 'MM/DD/YYYY').format('YYYY/MM/DD');
		ticket.arrival_time = modal.find('.modal-body #arrv_date_time').val();
		ticket.no_of_person = modal.find('.modal-body #no_of_pax').val();
		ticket.price = modal.find('.modal-body #price').val();
		ticket.tag = modal.find('.modal-body #tag').val();

		//alert(JSON.stringify(ticket));
		var url = document.location.href;
		url = url.substr(0, url.lastIndexOf('/'));

		post_update(
			{
				url: `${url}/save_ticket_post`,
				data: {mode: mode, payload: {source_ticket: ticket}},
				error: function() {
					alert('ERROR');
				},
				success: function(data) {
					if(data && parseInt(data['code'])===200) {
						document.location.reload();
					}
					else {
						alert(`${data.status} (${data.code})`);
					}
				}
			}
		);
	}

	function post_update(payload) {
		$.ajax({
			url: payload.url, //'/ajax-requestPost',
			type: 'POST',
			data: payload.data,
			error: function() {
				//alert('Something is wrong');
				payload.error();
			},
			success: function(data) {
				payload.success(JSON.parse(data));
					// $("tbody").append("<tr><td>"+title+"</td><td>"+description+"</td></tr>");
					// alert("Record added successfully");  
			}
		});
	}

	function formatDate(date) {
		var d = new Date(date),
			month = '' + (d.getMonth() + 1),
			day = '' + d.getDate(),
			year = d.getFullYear();

		if (month.length < 2) 
			month = '0' + month;
		if (day.length < 2) 
			day = '0' + day;

		return [year, month, day].join('-');
	}

	function edit_ticket(id) {
		$('#ticket_form').modal('show');
	}
		
	function clone_ticket(id) {
		alert(`Clone : Ticket Id ${id}`);
	}

	function validation_ticket_search()
	{
		//alert(triptype);
		$("#progressbar").hide();
		if($("#sc_source").val()=="")
		{
			// $("#trip_type").addClass('is-invalid');
			// $("#trip_type").parent().find(".error").remove();
			// $("#trip_type").parent().append('<div class="error">Please Select Trip Type !!!</div>');
			alert("Departing city is mandatory");
			return false;
		}
		else if($("#sc_destination").val()=="")
		{
			// $("#trip_type").addClass('is-invalid');
			// $("#trip_type").parent().find(".error").remove();
			// $("#trip_type").parent().append('<div class="error">Please Select Trip Type !!!</div>');
			alert("Arriving city is mandatory");
			return false;
		}
		else if($("#departure_date").val()=="")
		{
			// $("#dt_from").addClass('is-invalid');
			// $("#dt_from").parent().find(".error").remove();
			// $("#dt_from").parent().append('<div class="error">Please Select Date From !!!</div>');
			alert("Departure date can't be empty or invalid");
			return false;
		}
		else if(triptype==='round' && ($("#return_date").val()=="" || new Date($("#return_date").val())<new Date($("#departure_date").val()) ))
		{
			// $("#dt_to").addClass('is-invalid');
			// $("#dt_to").parent().find(".error").remove();
			// $("#dt_to").parent().append('<div class="error">Please Select Date To !!!</div>');
			alert("In case of round trip booking, return date is mandatory and must be same or greater than departuere date");
			return false;
		}
		else
		{
			$("#btn_one_way").hide();
			$("#progressbar").show();
			return true;
		}
	}	

	function displayTime(ticksInSecs) {
		var ticks = ticksInSecs;
		var hh = Math.floor(ticks / 3600);
		var mm = Math.floor((ticks % 3600) / 60);
		var ss = ticks % 60;

		//alert( pad(hh, 2) + ":" + pad(mm, 2) + ":" + pad(ss, 2) );
		return (pad(hh, 2) + ":" + pad(mm, 2)); // + ":" + pad(ss, 2));
	}

	function pad(n, width) {
		var n = n + '';
		return n.length >= width ? n : new Array(width - n.length + 1).join('0') + n;
	}	
</script>
