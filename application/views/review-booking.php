<link rel="stylesheet" href="<?php echo base_url(); ?>css/flight_booking.css">

<!--================== PAGE-COVER ================-->
<section class="innerpage-wrapper">
    <div class="container-base">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-lg-12 col-md-12 breadcrum">
                <div class="title" style="font-size: xx-large; margin: 5px 15px;">Review your booking</div>
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

                <!-- Start : Flight Itinerary details -->
                <div id="dvflightdetails" class="flight-section left">
                    <div class="section-head flight">
                        <span>Flight Detail</span>
                        <?php if(boolval($fare_quote['isprice_changed'])) { ?>
                        <div class="tv_right">
                            <div class="bg_si_d alert_msg blinking">Flight rate has been changed, due to heavy demand</div>
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
                </div>
                <!-- End : Flight Itinerary details -->

                <!-- Start : Commercial part starts -->
                <div id="dvcommercial" class="commercial-section right stickyitem">
                    <div class="section-head money">
                        <span style="float: left; width: 40%;">Price Summary</span>
                        <div class="traveller adult" id="divNoAdult"><?= $adult ?></div>
                        <div class="traveller child" id="divNoChild"><?= $child ?></div>
                        <div class="traveller infant" id="divNoInfant"><?= $infant ?></div>
                    </div>
                    <div>
                        <?php if($fare_quote && $fare_quote['passengers_fare'] && is_array($fare_quote['passengers_fare']) && count($fare_quote['passengers_fare']) > 0) { 
                            $passengers_fare = $fare_quote['passengers_fare'];
                            $tds = floatval($fare_quote['fare']['total_tds']);
                            $commision = floatval($fare_quote['fare']['total_commission']);
                            $discount = floatval($fare_quote['fare']['discount']);
                            $total_spl_margin = floatval($fare_quote['fare']['total_spl_margin']);
                            $total_whl_margin = floatval($fare_quote['fare']['total_whl_margin']);
                            $tax = round(floatval($fare_quote['fare']['tax']) + floatval($fare_quote['fare']['othercharges']) + $tds + (($total_spl_margin + $total_whl_margin) * ($adult + $child)) - $commision - $discount, 0);
                            $total = 0;
                            foreach ($passengers_fare as $passenger_fare) { 
                                $passengertype = intval($passenger_fare['PassengerType']);
                                $passengercount = intval($passenger_fare['PassengerCount']);
                                $passtype = 'Adult';
                                // $value = floatval($passenger_fare['BaseFare']);
                                $value = floatval($passenger_fare['BaseFare']) * $passengercount;
                                //$tax += floatval($passenger_fare['Tax']);
                                if($passengertype === 1) {
                                    $passtype = 'Adult';
                                } else if($passengertype === 2) {
                                    $passtype = 'Child';
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
                        <div class="tr-summary">
                            <div class="tr-count">Total Taxes & Charges</div>
                            <div class="tr-value"><span><?= $tax ?></span></div>
                        </div>
                        <div class="tr-summary highlight grand-total">
                            <div class="tr-count">Grand Total</div>
                            <div class="tr-value"><span><?= ($total + $tax) ?></span></div>
                        </div>
                    </div>
                </div>
                <!-- End : Commercial part end -->

                <!-- Start : Passengers list -->
                <div id="dvpassengers" class="flight-section left">
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
                            <?php if($state && is_array($state) && $state['passengers'] && is_array($state['passengers']) && count($state['passengers'])>0) {
                                $passengers = $state['passengers'];
                                $email_visible = false;
                                for ($i=0; $i < count($passengers); $i++) { 
                                    $passenger = $passengers[$i];
                                    $passenger_type = $passenger['passenger_type'];
                                    if(!boolval($passenger['enable'])) continue;

                                    $name = "{$passenger['passenger_title']} {$passenger['passenger_first_name']} {$passenger['passenger_last_name']}"
                                ?>
                                <div class="fd-ll" style="width:100%;">
                                    <div class="adttl"><?=$passenger_type ?></div>
                                    <div class="errorPax" id="errAdult" style="display:none;color:red;"></div>
                                    <div id="divAdultPax">
                                        <div class="shd_pnl">
                                            <div class="clr"></div>
                                            <div class="pdn12">
                                                <div class="str_1">
                                                    <label class="ctr_cbox" id="mycheckbox">
                                                        <span id="spnAdult<?=$i ?>"><?= $name ?></span>
                                                    </label>
                                                    <!-- <div class="arw_rit">
                                                        <div class="dwn-ar-trv" id="dwnPassenger_<?=$i ?>" onclick="CloseTraveler('Adult<?=$i?>')" style="display: block;"></div>
                                                    </div> -->
                                                </div>
                                                <div class="trvr_sec" id="divTrvAdult<?=$i?>">
                                                    <div class="str_2">
                                                        <label class="label_ti">Title</label> <span class="input_trvl"><?= $passenger['passenger_title'] ?></span>
                                                    </div>
                                                    <div class="str_3 mgl15">
                                                        <label class="label_ti">(First Name &amp; (Middle name, if any)</label>
                                                        <span class="input_trvl"><?= $passenger['passenger_first_name'] ?></span>
                                                    </div>
                                                    <div class="str_3 mgl15">
                                                        <label class="label_ti">Last Name</label>
                                                        <span class="input_trvl"><?= $passenger['passenger_last_name'] ?></span>
                                                    </div>
                                                    <?php if($passenger_type === 'Adult' && !$email_visible) { 
                                                        $email_visible = true;
                                                        ?>
                                                        <div class="str_3">
                                                            <label class="label_ti">Email</label>
                                                            <span class="input_trvl"><?= $passenger['passenger_email'] ?></span>
                                                        </div>
                                                        <div class="str_3 mgl15">
                                                            <label class="label_ti">Mobile</label>
                                                            <span class="input_trvl"><?= $passenger['passenger_mobile'] ?></span>
                                                        </div>
                                                    <?php } ?>
                                                    <?php if($passenger_type === 'Infant') { 
                                                        $dob = mktime(0,0,0,intval($passenger['passenger_dob_month']), intval($passenger['passenger_dob_day']), intval($passenger['passenger_dob_year']));
                                                    ?>
                                                        <div class="inf" id="divDOBInfant<?=$i?>">
                                                            <div class="inf1">Date of Birth </div>
                                                            <span class="inf1"><?= date('Y-M-d', $dob) ?></span>
                                                            <div class="dt-ma">*Date of birth required for Infant</div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="clr"></div>
                                        </div>
                                    </div>
                                    <!-- <a class="add_adlt" ng-click="CreatePaxPanel('Adult')">+ Add Adult</a> -->
                                    <div class="clr"></div>
                                </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <!-- End : Passengers list -->

                <!-- Start : Payment section -->
                <div id="dvpayment" class="flight-section left">
                    <input type="hidden" id="payment-gw" name="payment-gw" value="Wallet">
                    <div class="section-head people">
                        <span>Payment Details</span>
                        <div class="tv_right">
                            <div class="bg_si_d"><img src="<?php echo base_url(); ?>images/g-id-icon.png" width="21" height="16" alt="Name" class="spcer">Please use your own payment instruments</div>
                        </div>                        
                    </div>
                    <div class="pmtitinerary">
                        <?php 
                            $payment_mode = 'Wallet';
                            if($state && is_array($state) && isset($state['payment_gateway'])) {
                                $payment_mode = $state['payment_gateway'];
                            }
                            $wallet_balance = floatval($mywallet['balance']);
                            $setting = $setting[0];
                        ?>
                        <?php 
                        if($payment_mode === 'Wallet') { ?>
                            <div class="pymnt-bx-lft" style="height: 30vh;">
                                <div id="wlt" class="pg debt-dtl blk-cls" code="<?= $payment_mode ?>">
                                    <a href="JavaScript:Void(0);">
                                        <div class="debt-dtl-im debt-dtl-im2"></div>
                                        <span class="pg-item ebt-dtl-txt blu">My Wallet</span>
                                    </a>
                                </div>
                            </div>

                            <div class="pg-content pymnt-bx-rgt">
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
                        }
                        else { ?>
                            <div class="pymnt-bx-lft" style="height: 30vh;">
                                <div class="pg net-b-dtl" id="pg_1" name="pg_1" code="<?= $payment_mode ?>">
                                    <a href="JavaScript:void(0);">
                                        <div class="net-b-im"></div>
                                        <span class="pg-item net-b-dtl-txt blu"><?= $payment_mode ?></span>
                                    </a>
                                </div>
                            </div>
                            <div class="pg-content pymnt-bx-rgt" id="pg-content-1">
                                <div class="clr"></div>
                                <span>Third party payment gateway will be used to accept your payment. You will be redirected to third party payment portal. After successful completion of the payment, you will be redirected back to our site with payment reference id.</span>
                                <span>On any kind of issues, please call our support or write to us within 24 hours of your transaction.</span>
                                <div class="clr"></div>
                            </div>                            
                        <?php }
                        ?>
                    </div>
                    <form method="POST" id="bookticket" action="<?php echo base_url(); ?>search/pre_booking/<?= $flight["id"] ?>">
                        <button type="submit" id="btnContinue_3" name="btnContinue_3" class="action-button">Continue</button>
                    </form>
                </div>                
                <!-- End : Payment section -->
            </div>
        </div>
    </div>
</section>

<script language="javascript">
    $(document).ready(function()
    {
        try
        {

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
</script>