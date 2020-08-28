<link rel="stylesheet" href="<?php echo base_url(); ?>css/flight_booking.css">

<!--================== PAGE-COVER ================-->
<section class="innerpage-wrapper">
    <div class="container-base">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-lg-12 col-md-12 breadcrum">
                <ol id="breadcrum">
                    <li class="selected">1. Review</li>
                    <li class="seperator"></li>
                    <li>2. Travellers</li>
                    <li class="seperator"></li>
                    <li>3. Payment</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-lg-12 col-md-12">
                <?php 
                $adult = intval($state['adult']);
                $child = intval($state['child']);
                $infant = intval($state['infant']);
                
                if($flight && is_array($flight) && count($flight) > 0) {
                    $flight = $flight[0];
                }

                $title = $flight['source_city'].' - '.$flight['destination_city'];
                $dep_date_time = date('D - d-M-Y', strtotime($flight['departure_date_time']));
                $dep_time = date('H:i', strtotime($flight['departure_date_time']));
                $arv_date_time = date('D - d-M-Y', strtotime($flight['arrival_date_time']));
                $arv_time = date('H:i', strtotime($flight['arrival_date_time']));

                $dateDiff = intval((strtotime($flight["arrival_date_time"])-strtotime($flight["departure_date_time"]))/60);

                $airline_name = $flight['airline_name'];
                $flight_no = $flight['flight_no'];
                $refundable = ($flight['refundable'] === 'Y') ? 'Refundable' : 'Not Refundable';
                ?>
                <form method="POST" id="bookticket" action="<?php echo base_url(); ?>search/review_booking/<?php echo $flight["id"];?>">
                    <div id="dvflightdetails" class="flight-section left">
                        <div class="section-head flight">
                            <span>Flight Detail</span>
                            <?php if(boolval($fare_quote['isprice_changed'])) { ?>
                            <div class="tv_right">
                                <div class="bg_si_d alert_msg blinking">Flight rate has been changed, due to heavy demand</div>
                            </div>
                            <?php } ?>

                            <!-- show error -->
                            <?php if($error && $error!==null && $error!=='') { ?>
                            <div class="tv_right">
                                <div class="bg_si_d alert_msg blinking"><?= $error ?></div>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="itinerary">
                            <div class="departure">
                                <div class="tag">Depart</div>
                                <div class="flight-item">
                                    <!-- Itinerary details -->
                                    <div class="travel-itinerary">
                                        <span class="travel-title"><?= $title ?></span>
                                        <span class="travel-title">| <?= $dep_date_time ?></span>
                                        <!-- <span class="travel-title">Free Meals</span> -->
                                    </div>
                                    <!-- Flight details -->
                                    <?php if($fare_quote && $fare_quote['ob_flight_segments'] && is_array($fare_quote['ob_flight_segments']) && count($fare_quote['ob_flight_segments']) > 0) {
                                        $index = 0;
                                        foreach ($fare_quote['ob_flight_segments'] as $quote) { 
                                            $index++;
                                            $qt_dep_time = date('H:i', strtotime($quote['DepTime']));
                                            $qt_arv_time = date('H:i', strtotime($quote['ArrTime']));
                                            $qt_dep_date_time = date('D - d-M-Y', strtotime($quote['DepTime']));
                                            $qt_arv_date_time = date('D - d-M-Y', strtotime($quote['ArrTime']));
                                            $qt_dateDiff = intval((strtotime($quote["ArrTime"])-strtotime($quote["DepTime"]))/60);
                                            $flight_no = $quote['AirlineCode'].' '.$quote['FlightNumber'];
                                            $airline_name = $quote['AirlineName'];
                                            ?>
                                            <div class="flgith-details">
                                                <div class="fli1">
                                                    <div class="fli1-m">
                                                        <div class="fli1-m-l"><img alt="Flight" width="50" height="35" src="/upload/<?= $flight['image']?>"></div>
                                                        <div class="fli1-m-r"><span ><?= $airline_name?></span><span><?= $flight_no ?></span></div>
                                                    </div>
                                                </div>
                                                <div class="fli-d-r">
                                                    <div class="fli2">
                                                        <div class="fli-cm"><span><?= $quote['Dep_CityCode'] ?></span> <span> <strong><?= $qt_dep_time ?></strong> </span></div>
                                                        <div class="lin1"></div>
                                                        <div class="air-dt">
                                                            <span><?= $qt_dep_date_time ?></span>
                                                            <span>Terminal - <?= $quote['Dep_Terminal'] ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="fli3">
                                                        <div class="stp"><span><?= intval($qt_dateDiff/60)."h ".($qt_dateDiff%60)."m"; ?></span></div>
                                                        <div class="lin2"><div class="fli-i"></div></div>
                                                        <div class="clr"></div>
                                                        <div class="ref" id="spnRefundable"><span><?= $refundable ?></span></div>
                                                    </div>
                                                    <div class="fli4">
                                                        <div class="fli-cm1"><span><?= $quote['Arr_CityCode'] ?></span> <span> <strong><?= $qt_arv_time ?></strong> </span></div>
                                                        <div class="lin3"></div>
                                                        <div class="air-dt1">
                                                            <span><?= $qt_arv_date_time ?></span>
                                                            <span>Terminal - <?= $quote['Arr_Terminal'] ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="FltAmeUK835">
                                                    <div class="fli-amenities"> 
                                                        <div class="make_flex space_between">         
                                                            <span>             
                                                                <span class="ftn12" style="font-weight:bold;">A<?= $quote['Craft']?></span>
                                                                <span class="malt5 mart5">|</span>			
                                                                <span style="display:none;" class="mart5 ficon-1 opct35"></span>             
                                                                <span class="mart5 ficon-2"></span>             
                                                                <span class="mart5 ficon-3"></span>             
                                                                <span class="mart5 ficon-4"></span>		   
                                                                <span class="mart5 ficon-5"></span>             
                                                                <span class="mart5 ficon-6"></span>         
                                                            </span>         
                                                            <a class="showmore" id="<?= ($quote['AirlineCode'].$quote['FlightNumber']) ?>" onclick="openTabFltAmeneties(this.id)">SHOW MORE</a>
                                                        </div>     
                                                        <div class="moreAmenities" id="divFltAme<?= ($quote['AirlineCode'].$quote['FlightNumber']) ?>">
                                                            <div class="cmlft">   
                                                                <span class="amntl">Comfort</span>         
                                                                <ul>          
                                                                    <li><span class="mart5 ficon-7"></span>30" seat pitch</li>             
                                                                    <li><span class="mart5 ficon-6"></span>3-3 seat layout </li>         
                                                                </ul>
                                                            </div>
                                                            <div class="amlft">
                                                                <span class="amntl">Amenities</span>         
                                                                <ul>		
                                                                    <li><span class="mart5 ficon-2"></span> Fresh meal available.</li>        
                                                                    <li><span class="mart5 ficon-1 opct35"></span>No Wi-fi</li>             
                                                                    <li><span class="mart5 ficon-3"></span> Power Outlets</li>             
                                                                    <li><span class="mart5 ficon-4"></span> On demand entertainment</li>         
                                                                </ul>
                                                            </div>     
                                                        </div> 
                                                    </div>
                                                </div>
                                                <?php //if($index === count($fare_quote['ob_flight_segments'])) { ?>
                                                    <div class="mel1-d111" style="display:block;" id="divFareRules">
                                                        <div class="tab_trvlr2">
                                                            <a class="tabHightLight" id="tabF00" onclick="tabHightLight(this.id)">Fare Rules</a>
                                                            <a class="tabHightLight" id="tabBaggage<?= $quote['SegmentIndicator']?>" onclick="tabBaggage(this.id)">Baggage</a>
                                                            <span style="float: right;color: green;font-size: 14px;margin-bottom: -1px;"></span>
                                                        </div>
                                                    </div>
                                                    <div class="new-b2b-bagge-main VisaBaggFar" id="sec_tabBaggage<?= $quote['SegmentIndicator']?>">
                                                        <div class="bageg-tab-b2b">
                                                            <div class="bagg-b2b-air">AIRLINE</div>
                                                            <div class="check-b2b-air">Check-in Baggage</div>
                                                            <div class="cabin-b2b-air">Cabin Baggage</div>
                                                        </div>
                                                        <div class="bageg-tab-b2b-new">
                                                            <div class="bagg-b2b-air">
                                                                <div class="fli-lo-new-bagge">
                                                                    <span class="logo-xs ai-lg"><img width="28" height="28" src="/upload/<?= $flight['image']?>"></span>
                                                                </div>
                                                                <div class="fli-lo-aiame-bagge">
                                                                    <span class="airline-name"><?= $airline_name ?></span>
                                                                    <span class="airline-name-code"><?= $flight_no ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="check-b2b-air">
                                                                <strong>
                                                                    <span></span>
                                                                </strong>
                                                                <span><?= $quote['Baggage'] ?></span>
                                                            </div>
                                                            <div class="cabin-b2b-air">
                                                                <strong>
                                                                    <span></span>
                                                                </strong>
                                                                <span><?= $quote['CabinBaggage'] ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php //} ?>
                                            </div>
                                        <?php } 
                                    }?>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="btnContinue_3" name="btnContinue_3" class="action-button" onclick="return proceed();">Continue</button>
                    </div>
                    <div id="dvcommercial" class="commercial-section right stickyitem">
                        <div class="section-head money">
                            <span style="float: left; width: 40%;">Price Summary</span>
                            <div class="traveller adult" id="divNoAdult"><?= $adult ?></div>
                            <div class="traveller child" id="divNoChild"><?= $child ?></div>
                            <div class="traveller infant" id="divNoInfant"><?= $infant ?></div>
                        </div>
                        <div>
                            <?php if($fare_quote && $fare_quote['passengers_fare'] && is_array($fare_quote['passengers_fare']) && count($fare_quote['passengers_fare']) > 0) { 
                                $isFD = boolval($state['isfixeddeparture']);
                                $passengers_fare = $fare_quote['passengers_fare'];
                                $tds = floatval($fare_quote['fare']['total_tds']);
                                $commision = floatval($fare_quote['fare']['total_commission']);
                                $discount = floatval($fare_quote['fare']['discount']);
                                $total_spl_margin = floatval($fare_quote['fare']['total_spl_margin']);
                                $total_whl_margin = floatval($fare_quote['fare']['total_whl_margin']);
                                //$tax = round(floatval($fare_quote['fare']['tax']) + floatval($fare_quote['fare']['othercharges']) + $tds + $total_spl_margin + $total_whl_margin - $commision - $discount, 0);
                                $finalvalue = $fare_quote['fare']['offeredfare'] + $tds + (($total_spl_margin + $total_whl_margin) * ($adult + $child));
                                $total = 0;
                                foreach ($passengers_fare as $passenger_fare) { 
                                    $passengertype = intval($passenger_fare['PassengerType']);
                                    $passengercount = intval($passenger_fare['PassengerCount']);
                                    $admin_markup = floatval($passenger_fare['admin_markup']);
                                    $passtype = 'Adult';
                                    //$value = floatval($passenger_fare['BaseFare']) * $passengercount;
                                    $value = floatval($passenger_fare['BaseFare']) * $passengercount;
                                    $finalvalue += $admin_markup * $passengercount;
                                    //$tax += floatval($passenger_fare['Tax']);
                                    if($passengertype === 1) {
                                        $passtype = 'Adult';
                                        // if($isFD) {
                                        //    $value += (($total_spl_margin + $total_whl_margin) * $passengercount);
                                        // }
                                        //$value = (floatval($passenger_fare['BaseFare']) + ($total_spl_margin + $total_whl_margin)) * $passengercount;
                                    } else if($passengertype === 2) {
                                        $passtype = 'Child';
                                        // if($isFD) {
                                        //     $value += (($total_spl_margin + $total_whl_margin) * $passengercount);
                                        // }
                                        //$value = (floatval($passenger_fare['BaseFare']) + ($total_spl_margin + $total_whl_margin)) * $passengercount;
                                    } else if($passengertype === 3) {
                                        $passtype = 'Infant';
                                    }
                                    $total += $value;
                                    ?>
                                    <div class="tr-summary">
                                        <div class="tr-count"><?= $passtype ?> x <?= $passengercount ?></div>
                                        <div class="tr-value"><span><?= $value ?></span></div>
                                    </div>
                                <?php    
                                }

                                $total = round($total, 0);
                                ?>
                            <?php } ?>
                            <!-- <div class="tr-summary">
                                <div class="tr-count">Child x 1</div>
                                <div class="tr-value"><span>5000</span></div>
                            </div>
                            <div class="tr-summary">
                                <div class="tr-count">Infant x 1</div>
                                <div class="tr-value"><span>1250</span></div>
                            </div> -->
                            <div class="tr-summary">
                                <div class="tr-count">Total Taxes & Charges</div>
                                <div class="tr-value"><span><?= ($finalvalue - $total) ?></span></div>
                            </div>
                            <div class="tr-summary highlight grand-total">
                                <div class="tr-count">Grand Total</div>
                                <div class="tr-value"><span><?= ($finalvalue) ?></span></div>
                            </div>
                        </div>
                        <button id="btnContinue_2" name="btnContinue_2" class="action-button" onclick="return proceed();">Continue</button>
                    </div>
                    <div id="dvpassengers" class="flight-section left hidden">
                        <div class="section-head people">
                            <span>Travellers Details</span>
                            <div class="tv_right">
                                <!-- ngIf: isDomestic -->
                                <div class="bg_si_d"><img src="<?php echo base_url(); ?>images/g-id-icon.png" width="21" height="16" alt="Name" class="spcer">Name should be same as in Goverment ID proof</div>
                                <!-- ngIf: isDomestic==false -->
                            </div>                        
                        </div>
                        <div class="itinerary">
                            <div class="tr-cen-trv">
                                <?php if($fare_quote && is_array($fare_quote) && $fare_quote['passengers_fare'] && is_array($fare_quote['passengers_fare']) && count($fare_quote['passengers_fare'])>0) { 
                                    $i = 0 ?>
                                    <?php for ($j=0; $j < count($fare_quote['passengers_fare']); $j++) { 
                                        $passenger = $fare_quote['passengers_fare'][$j];
                                        $passenger_type_code = intval($passenger['PassengerType']);
                                        $passenger_type = $passenger_type_code;
                                        switch ($passenger_type) {
                                            case 1:
                                                $passenger_type = 'Adult';
                                                break;
                                            case 2:
                                                $passenger_type = 'Child';
                                                break;
                                            case 3:
                                                $passenger_type = 'Infant';
                                                break;
                                            default:
                                                $passenger_type = 'Adult';
                                                break;
                                        }
                                        $pass_count = intval($passenger['PassengerCount']);
                                        $pi = 0;
                                        while ($pi++ < $pass_count) {
                                    ?>
                                            <div class="fd-ll" style="width:100%;">
                                                <input type="hidden" id="passenger_type_<?=$i?>" name="passenger_type_<?=$i?>" value="<?= $passenger_type ?>">
                                                <div class="adttl"><?=$passenger_type ?></div>
                                                <div class="errorPax" id="errAdult" style="display:none;color:red;"></div>
                                                <div id="divAdultPax">
                                                    <div class="shd_pnl">
                                                        <div class="clr"></div>
                                                        <div class="pdn12">
                                                            <div class="str_1">
                                                                <label class="ctr_cbox" id="mycheckbox">
                                                                    <span id="spnAdult<?=$i ?>"><?= $passenger_type ?> <?= ($i+1) ?></span>
                                                                    <input type="checkbox" name="chkPassenger_<?=$i ?>" checked="true" id="chkPassenger_<?=$i ?>" onclick="CloseTravCheckPass('Adult<?=$i?>')">
                                                                    <span class="cmark_cbox"></span>
                                                                </label>
                                                                <div class="arw_rit">
                                                                    <div class="dwn-ar-trv" id="dwnPassenger_<?=$i ?>" onclick="CloseTraveler('Adult<?=$i?>')" style="display: block;"></div>
                                                                </div>
                                                            </div>
                                                            <div class="trvr_sec" id="divTrvAdult<?=$i?>">
                                                                <div class="str_2">
                                                                    <label class="label_ti">Title</label>
                                                                    <select id="slPassenger_<?=$i?>" name="slPassenger_<?=$i?>" class="select_trvl" paxtype="<?=$passenger_type?>" idno="<?=$i?>" dynid="<?=$i?>" onchange="CheckSameTraveler(this.id);CookieSave('Adult');SetNameOnLabel('Adult<?=$i?>');" required="">
                                                                        <option value="">Title</option>
                                                                        <option value="Mr">MR</option>
                                                                        <option value="Ms">MS</option>
                                                                        <option value="Miss">Miss</option>
                                                                        <option value="Mstr">Master</option>
                                                                        <option value="Mrs">Mrs</option>
                                                                    </select>
                                                                </div>
                                                                <div class="str_3 mgl15">
                                                                    <label class="label_ti">(First Name &amp; (Middle name, if any)</label>
                                                                    <input type="text" name="txtPassenger_FN_<?=$i ?>" autocomplete="none" id="txtPassenger_FN_<?=$i ?>" paxtype="<?= $passenger_type ?>" idno="<?=$i ?>" dynid="<?=$i ?>" class="input_trvl" placeholder="Enter First Name" onblur="PreventSpecialCharacter(this);CheckSameTraveler(this.id);CookieSave('<?= $passenger_type ?>');SetNameOnLabel('<?= $passenger_type ?><?=$i ?>');" required="">
                                                                </div>
                                                                <div class="str_3 mgl15">
                                                                    <label class="label_ti">Last Name</label>
                                                                    <input type="text" name="txtPassenger_LN_<?=$i ?>" autocomplete="none" id="txtPassenger_LN_<?=$i ?>" paxtype="<?= $passenger_type ?>" idno="<?=$i ?>" dynid="<?=$i ?>" class="input_trvl" placeholder="Enter Last Name" onblur="PreventSpecialCharacter(this);CheckSameTraveler(this.id);CookieSave('<?= $passenger_type ?>');SetNameOnLabel('<?= $passenger_type ?><?=$i ?>');" required="">
                                                                </div>
                                                                
                                                                <?php if($passenger_type === 'Adult' && $pi === 1) { ?>
                                                                    <div class="str_3">
                                                                        <label class="label_ti">Email</label>
                                                                        <input type="text" name="txtPassenger_EML_<?=$i ?>" autocomplete="none" id="txtPassenger_EML_<?=$i ?>" paxtype="<?= $passenger_type ?>" idno="<?=$i ?>" dynid="<?=$i ?>" class="input_trvl" placeholder="Enter Email Address" onblur="PreventSpecialCharacter(this);CheckSameTraveler(this.id);CookieSave('<?= $passenger_type ?>');SetNameOnLabel('<?= $passenger_type ?><?=$i ?>');" required="">
                                                                    </div>
                                                                    <div class="str_3 mgl15">
                                                                        <label class="label_ti">Mobile</label>
                                                                        <input type="text" name="txtPassenger_MBL_<?=$i ?>" autocomplete="none" id="txtPassenger_MBL_<?=$i ?>" paxtype="<?= $passenger_type ?>" idno="<?=$i ?>" dynid="<?=$i ?>" class="input_trvl" placeholder="Enter Your Mobile Number" onblur="PreventSpecialCharacter(this);CheckSameTraveler(this.id);CookieSave('<?= $passenger_type ?>');SetNameOnLabel('<?= $passenger_type ?><?=$i ?>');" required="">
                                                                    </div>
                                                                <?php } ?>
                                                                <?php if($passenger_type === 'Infant') { ?>
                                                                <div class="inf" id="divDOBInfant<?=$i?>">
                                                                    <div class="inf1">Date of Birth </div>
                                                                    <div class="inf2">
                                                                        <select class="sel1" name="slDOBDay_Passenger_<?=$i ?>" id="slDOBDay_Passenger_<?=$i ?>" onchange="CookieSave('<?= $passenger_type ?>')">
                                                                            <option selected="selected" value="0">Day</option>
                                                                            <option value="01">1</option>
                                                                            <option value="02">2</option>
                                                                            <option value="03">3</option>
                                                                            <option value="04">4</option>
                                                                            <option value="05">5</option>
                                                                            <option value="06">6</option>
                                                                            <option value="07">7</option>
                                                                            <option value="08">8</option>
                                                                            <option value="09">9</option>
                                                                            <option value="10">10</option>
                                                                            <option value="11">11</option>
                                                                            <option value="12">12</option>
                                                                            <option value="13">13</option>
                                                                            <option value="14">14</option>
                                                                            <option value="15">15</option>
                                                                            <option value="16">16</option>
                                                                            <option value="17">17</option>
                                                                            <option value="18">18</option>
                                                                            <option value="19">19</option>
                                                                            <option value="20">20</option>
                                                                            <option value="21">21</option>
                                                                            <option value="22">22</option>
                                                                            <option value="23">23</option>
                                                                            <option value="24">24</option>
                                                                            <option value="25">25</option>
                                                                            <option value="26">26</option>
                                                                            <option value="27">27</option>
                                                                            <option value="28">28</option>
                                                                            <option value="29">29</option>
                                                                            <option value="30">30</option>
                                                                            <option value="31">31</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="inf3">
                                                                        <select class="sel1" name="slDOBMonth_Passenger_<?=$i ?>" id="slDOBMonth_Passenger_<?=$i ?>" onchange="CookieSave('<?= $passenger_type ?>')">
                                                                            <option selected="selected" value="0">Month</option>
                                                                            <option value="01">Jan</option>
                                                                            <option value="02">Feb</option>
                                                                            <option value="03">Mar</option>
                                                                            <option value="04">Apr</option>
                                                                            <option value="05">May</option>
                                                                            <option value="06">Jun</option>
                                                                            <option value="07">Jul</option>
                                                                            <option value="08">Aug</option>
                                                                            <option value="09">Sep</option>
                                                                            <option value="10">Oct</option>
                                                                            <option value="11">Nov</option>
                                                                            <option value="12">Dec</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="inf4">
                                                                        <select class="sel1 autoFillDOBYearInfant1" name="slDOBYear_Passenger_<?=$i ?>" id="slDOBYear_Passenger_<?=$i ?>" onchange="CookieSave('<?= $passenger_type ?>')">
                                                                            <option selected="selected" value="0">Year</option>
                                                                            <option value="2020">2020</option>
                                                                            <option value="2019">2019</option>
                                                                            <option value="2018">2018</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="dt-ma">*Date of birth required for Infant</div>
                                                                </div>
                                                                <?php } ?>
                                                                <div class="fl-m-m">
                                                                    <div class="fl-m">
                                                                        <!-- ngIf: !isDomestic && (IsShowDOB || IsShowPassportNumber || IsShowPassportExpirey) -->
                                                                        <div class="fl-mr">
                                                                            <!-- <div class="fl-mm" id="divFlyerMinusAdult0" ng-click="SlideUPFFlyer('divFlyerAdult0','divFlyerMinusAdult0','divFlyerPlusAdult0')">(-) Frequent flyer number and Meal preference (optional)</div>
                                                                            <div class="fl-ma" id="divFlyerPlusAdult0" ng-click="SlideDownFFlyer('divFlyerAdult0','divFlyerMinusAdult0','divFlyerPlusAdult0')">(+) Frequent flyer number and Meal preference (optional)</div> -->
                                                                        </div>
                                                                    </div>
                                                                    <!-- ngIf: !isDomestic && (IsShowDOB || IsShowPassportNumber || IsShowPassportExpirey) -->
                                                                    <div class="fl-d" id="divFlyerAdult<?=$i?>">
                                                                        <div class="fl-d1">
                                                                            <label class="lbl">Frequent flyer no.</label>
                                                                            <input type="text" value="" id="txtFFAdult<?=$i?>" name="" placeholder="Frequent Flyer Number" class="in">
                                                                        </div>
                                                                        <div class="fl-d2">
                                                                            <label class="lbl">AIRLINE</label>
                                                                            <input type="text" value="" placeholder="Enter Airline Name" class="in" id="txtFFAirAdult<?=$i?>" name="">
                                                                        </div>
                                                                        <div class="fl-d3">
                                                                            <label class="lbl">Meal Preference </label>
                                                                            <select class="sel" id="txtFFMealAdult<?=$i?>">
                                                                                <option value="Select Meal Preference">Select Meal Preference</option>
                                                                                <option value="Vegetarian Hindu Meal">Vegetarian Hindu Meal</option>
                                                                                <option value="Baby Meal">Baby Meal</option>
                                                                                <option value="Hindu ( Non Vegetarian) Meal">Hindu ( Non Vegetarian) Meal </option>
                                                                                <option value="Kosher Meal">Kosher Meal</option>
                                                                                <option value="Moslem Meal">Moslem Meal</option>
                                                                                <option value="Vegetarian Jain Meal">Vegetarian Jain Meal</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="clr"></div>
                                                    </div>
                                                </div>
                                                <!-- <a class="add_adlt" ng-click="CreatePaxPanel('Adult')">+ Add Adult</a> -->
                                                <div class="clr"></div>
                                            </div>
                                        <?php 
                                            $i++;
                                            } ?>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                        <button type="button" id="btnContinue_1" name="btnContinue_1" class="action-button" onclick="return proceed();">Continue</button>
                    </div>
                    <!-- Payment Section -->
                    <?php 
                    $wallet_balance = floatval($mywallet['balance']);
                    $setting = $setting[0];
                    // $payment_gw = json_decode($setting['payment_gateway'], true);
                    $payment_gw = $setting['payment_gateway'];
                    ?>
                    <div id="dvpayment" class="flight-section left hidden">
                        <input type="hidden" id="payment-gw" name="payment-gw" value="Wallet">
                        <div class="section-head people">
                            <span>Payment Details</span>
                            <div class="tv_right">
                                <div class="bg_si_d"><img src="<?php echo base_url(); ?>images/g-id-icon.png" width="21" height="16" alt="Name" class="spcer">Please use your own payment instruments</div>
                            </div>                        
                        </div>
                        <div class="pmtitinerary">
                            <div class="pymnt-bx-lft">
                                <div id="wlt" class="pg debt-dtl blk-cls" onclick="selectPaymentMethod(this);" target-content="DivDebitCardPanel" code="Wallet">
                                    <a href="JavaScript:Void(0);">
                                        <div class="debt-dtl-im debt-dtl-im2"></div>
                                        <span class="pg-item ebt-dtl-txt blu">My Wallet</span>
                                    </a>
                                </div>
                                <?php 
                                if($payment_gw && is_array($payment_gw) && count($payment_gw) > 0) { 
                                    for ($i=0; $i < count($payment_gw); $i++) { 
                                        $pg = $payment_gw[$i];
                                        $pg_class = ($pg['icon'] !== '') ? $pg['icon'] : "net-b-im";
                                ?>
                                    <div class="pg net-b-dtl" id="pg_<?= $pg['id'] ?>" name="pg_<?= $pg['id'] ?>" onclick="selectPaymentMethod(this);" target-content="pg-content-<?= $i ?>" code="<?= $pg['pw_name'] ?>">
                                        <a href="JavaScript:void(0);">
                                            <div class="<?= $pg_class ?>"></div>
                                            <span class="pg-item net-b-dtl-txt"><?= $pg['pw_name'] ?></span>
                                        </a>
                                    </div>
                                <?php
                                    }
                                } ?>
                            </div>
                            <div class="pg-content pymnt-bx-rgt" id="DivDebitCardPanel">
                                <div class="clr"></div>
                                <div>
                                    <span class="title">Wallet Balance : </span>
                                    <span class="title"><?= number_format($wallet_balance,2,".",",") ?></span>
                                </div>
                                <?php 
                                if($currentuser && isset($currentuser['credit_ac']) && boolval($currentuser['credit_ac'])) {
                                ?>
                                <div>
                                    <span class="bg_si_d" style="color: #0044cc;">(You have been allowed credit to process this order. But final discretion is with the supplier of this inventory.)</span>
                                </div>
                                <?php 
                                }
                                ?>
                                <div class="clr"></div>
                            </div>
                            <?php 
                                if($payment_gw && is_array($payment_gw) && count($payment_gw) > 0) { 
                                    for ($i=0; $i < count($payment_gw); $i++) { 
                                        $pg = $payment_gw[$i];
                                        $terms_condition = $pg['terms_condition'];
                                ?>
                                <div class="pg-content pymnt-bx-rgt hidden" id="pg-content-<?= $i ?>">
                                    <div class="clr"></div>
                                    <span><?= $terms_condition ?></span>
                                    <div class="clr"></div>
                                </div>
                            <?php
                                }
                            } ?>
                        </div>
                        <button type="button" id="btnContinue_3" name="btnContinue_3" class="action-button" onclick="return proceed();">Continue</button>
                    </div>
                    <!-- End of Payment Section -->
                </form>
            </div>
        </div>
    </div>
</section>

<script language="javascript">
    $(document).ready(function()
    {
        try
        {
            // $('#bookticket').submit(function() {
            //     var checked = $('#check01').is(':checked');

            //     if(!checked) {
            //         alert('Please accept terms and condition.');
            //     }
            //     return checked;
            // });

            $('ol#breadcrum li').click((ev) => {
                var link_text = $(ev.target)[0].innerHTML;
                if(link_text === '1. Review') {
                    screen_index = 0;
                }
                else if(link_text === '2. Travellers') {
                    screen_index = 1;
                }
                else if(link_text === '3. Payment') {
                    screen_index = 2;
                }

                proceed();
            });
        }
        catch(e) {
            console.log(e);
        }
    });

    function openTabFltAmeneties(ctrlid) {
        $('#divFltAme'+ctrlid).toggleClass('visible');
    }

    function tabBaggage(ctrlid) {
        $('#sec_'+ctrlid).toggleClass('visible');
    }

    var screen_index = 1; //1 means Review Screen
    var last_screen_index = 3;

    function proceed() {

        if(screen_index>=1 && screen_index<last_screen_index) {
            screen_index++;
        }
        else {
            if(screen_index === last_screen_index) {
                if(isvalidform()) {
                    try
                    {
                        $(document).ready(function()
                        {
                            $('#bookticket').submit();
                        });
                    }
                    catch(e) {
                        alert(e);
                    }
                    // $('#bookticket').submit((ev) => {
                    //     alert(JSON.stringify(ev));
                    // });
                }
                else if(msg !== '') {
                    screen_index=2;

                    alert(msg);
                    msg = '';
                }
            }
            else {
                screen_index = 1;
            }
        }

        $("ol#breadcrum li").each((idx, el) => {
            $(el).removeClass('selected');
        });

        //$("ul li:nth-child(2)").append( "<span> - 2nd!</span>" );

        if(screen_index === 1) {
            $("ol#breadcrum li:nth-child(1)").addClass('selected');

            if($("#dvflightdetails").hasClass("hidden")) {
                $('#dvflightdetails').removeClass('hidden');
            }
            
            if(!$("#dvpassengers").hasClass("hidden")) {
                $('#dvpassengers').addClass('hidden');
            }

            if(!$("#dvpayment").hasClass("hidden")) {
                $('#dvpayment').addClass('hidden');
            }
        }
        if(screen_index === 2) {
            $("ol#breadcrum li:nth-child(3)").addClass('selected');

            if(!$("#dvflightdetails").hasClass("hidden")) {
                $('#dvflightdetails').addClass('hidden');
            }
            
            if($("#dvpassengers").hasClass("hidden")) {
                $('#dvpassengers').removeClass('hidden');
            }

            if(!$("#dvpayment").hasClass("hidden")) {
                $('#dvpayment').addClass('hidden');
            }
        }
        else if(screen_index === 3) { //Payment section
            $("ol#breadcrum li:nth-child(5)").addClass('selected');

            if(!$("#dvflightdetails").hasClass("hidden")) {
                $('#dvflightdetails').addClass('hidden');
            }
            
            if(!$("#dvpassengers").hasClass("hidden")) {
                $('#dvpassengers').addClass('hidden');
            }

            if($("#dvpayment").hasClass("hidden")) {
                $('#dvpayment').removeClass('hidden');
            }            
        }
    }

    var msg = '';

    function isvalidform() {
        msg = '';
        var flag = false;

        var length = $('.select_trvl').length;

        for (let index = 0; index < length; index++) {
            const element = $($('.select_trvl')[index]);
            
            if(element.val() === '') {
                //not yet filled. Lets get field placeholder and paxtype
                msg += `Title of ${element.attr('paxtype')} is missing\n`;
            }
        }

        var length = $('.input_trvl').length;

        for (let index = 0; index < length; index++) {
            const element = $($('.input_trvl')[index]);
            
            if(element.val() === '') {
                //not yet filled. Lets get field placeholder and paxtype
                msg += `${element.attr('placeholder')} of ${element.attr('paxtype')}\n`;
            }
        }

        console.log(msg);

        flag = (msg === '');
        return flag;
    }

    function selectPaymentMethod(ctrl) {
        if(!ctrl) return;

        var selectedIndex = -1;
        var target_contentid = $(ctrl).attr('target-content');

        $('#payment-gw').val($(ctrl).attr('code'));
        for (let index = 0; index < $('.pg').length; index++) {
            const element = $('.pg')[index];
            // alert(`${$(ctrl).attr('id')} - ${$(element).attr('id')}`);
            if($(ctrl).attr('id') !== $(element).attr('id')) {
                $(element).removeClass('blk-cls');
                $(element).find('> a > span.pg-item').removeClass('blu');
                //$(element).find('> a > span.pg-item').removeClass('selected');
                $(element).find('> a > div.net-b-im2').removeClass('net-b-im2');
                $(element).find('> a > div.debt-dtl-im2').removeClass('debt-dtl-im2');
            }
            else {
                selectedIndex = index;
            }
        }

        for (let index = 0; index < $('.pg-content').length; index++) {
            const pg_content = $($('.pg-content')[index]);
            if(pg_content && target_contentid !== pg_content.attr('id')) {
                if(!$(pg_content).hasClass('hidden')) {
                    $(pg_content).addClass('hidden');
                }
            }
        }
        //alert(`Index of Item selected : ${target_contentid}`);

        $(`#${target_contentid}`).removeClass('hidden');
        $(ctrl).addClass('blk-cls');
        $(ctrl).find('> a > span.pg-item').addClass('blu');
        if(selectedIndex>0) {
            $(ctrl).find('> a > div').addClass('net-b-im2');
        }
        else if(selectedIndex<=0) {
            $(ctrl).find('> a > div').addClass('debt-dtl-im2');
        }
        //$(ctrl).find('> a > span.pg-item').addClass('selected');
    }

</script>