<style>
    ::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
        color: #aaaaaa;
        opacity: 1; /* Firefox */
    }

    :-ms-input-placeholder { /* Internet Explorer 10-11 */
        color: #aaaaaa;
    }

    ::-ms-input-placeholder { /* Microsoft Edge */
        color: #aaaaaa;
    }

    .container_bg input {
        border: 0px;
        /*width: 100%;*/
        padding: 2px;
        outline: none;
    }
    .container_bg input:active, .container_bg input:focus, .container_bg input:hover, .container_bg input:visited {
        border: 0px;
        outline: none;
    }
    .container_bg {
        width: 100%;
        /*min-height: 230px;*/
        /*position: relative;*/
        background-color: #4263c1;
        background-image: linear-gradient(0deg, #6b8ef2 0, #4263c1 100%);        
    }
    @media only screen and (max-width: 768px) {
        .container_bg {
            display: flex;
            position: relative;
            left: 5px;     
        }   
    }
    .middle_part {
        /*width: 1200px;*/
        margin: 0 auto;
        padding: 18px 15px;
        position: relative;
    }
    .middle_part h1, .middle_part_sb h1 {
        font-size: 26px;
        text-align: center;
        margin: 0 0 6px;
        color: #fff;
        font-weight: 400;
    }
    .clr {
        clear: both;
    }
    .one-rou, .wid_rit {
        width: 50%;
        float: left;
        margin-bottom: 12px;
        margin-right: 10px;
    }
    ul {
        list-style-type: none;
        padding-inline-start: 0px;
    }
    .one-rou ul li {
        color: #cad5f5;
        cursor: pointer;
        float: left;
        font-size: 12px;
        list-style: outside none;
        margin: 0;
        padding: 2px 0;
        text-align: center;
        width: 86px;
        font-weight: 600;
        border-radius: 20px;
        margin-right: 1px;
    }

    .border-lft {
        border-bottom-left-radius: 4px;
        border-top-left-radius: 4px;
    }
    .bg-color {
        background-color: #fff!important;
        color: #1853a2!important;
    }

    .search_bg {
        width: 100%;
        background: #fff;
        border-radius: 3px;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        padding: 0;
        margin: 0 auto;
        box-shadow: 4px 4px 4px rgba(71,101,187,.75);
        -moz-box-shadow: 4px 4px 4px rgba(71,101,187,.75);
        -webkit-box-shadow: 4px 4px 4px rgba(71,101,187,.75);        
    }
    .mgr20 {
        padding-left: 0;
    }
    .s_col_1 {
        width: 250px;
        float: left;
        position: relative;
        border-right: 1px solid #d6d3d3;
        padding: 0;
        height: 65px;
    }    
    .s_col_2, .s_col_r {
        position: relative;
    }
    .mgr10 {
        padding-left: 0;
    }
    .s_col_2 {
        width: 136px;
        float: left;
        border-right: 1px solid #d6d3d3;
        padding: 0;
    }    
    .op {
        opacity: .4;
        position: relative;
        z-index: 9;
    }
    .s_col_2, .s_col_r {
        position: relative;
    }
    .mgr10 {
        padding-left: 0;
    }
    .s_col_2 {
        width: 136px;
        float: left;
        border-right: 1px solid #d6d3d3;
        padding: 0;
    }
    .ccp {
        cursor: pointer;
    }    
    .s_col_7 {
        width: 127px;
        float: left;
        border-right: 1px solid #d6d3d3;
        padding: 0;
    }    
    .s_col_8 {
        width: 118px;
        float: left;
        padding: 0;
    }    
    #search {
        position: relative;
    }
    .ripplen {
        position: relative;
        overflow: hidden;
        transform: translate3d(0, 0, 0);
    }
    .s_col_v4 {
        width: 152px;
        float: right;
    }    
    @media only screen and (min-width: 768px) {
        .h-100 {
            min-height: 60px
        }
    }
    .group-item {
        padding: 0px;
    }
    .auto-height {
        display: flex;
        /*padding: 18px 2px;*/
        font-size: 14pt;
        font-weight: 600;
        color: #aaaaaa;
    }
    .sc-input {
        height: 60px;
    }
    #sc_btnsearch {
        border: solid 0px #cdcdcd;
        background-color: #faa61a;
        width: 104%;
        /*height: 100%;
        display: flex; */
        padding: 17px 22px;
        margin: 0px;
        justify-content: center;
        font-size: 14pt;
        font-weight: 500;
        color: #ffffff;
        border-top-right-radius: 3px;
        border-bottom-right-radius: 3px;
    }
    .datepicker {
        border-radius: 0px;
        box-shadow: none;
        padding: 2px;
        margin: 0px;
        /* height: 30px; */
    }
    .list-item {
        width: 100%;
        outline: none;
        padding: 3px 7px;
        display: inline-block;
        color: #999999;
    }
    .ui-state-active {
        border: 0px !important;
        background: #d07712 !important;
        background-image: linear-gradient(#d07712, #e8b783, #d07712) !important;
        color: #ffffff !important;
    }
    .list-item:hover, .list-item:focus, .ui-state-active:hover, .ui-state-active:focus, .ui-state-active:active {
        background-image: linear-gradient(#d07712, #e8b783, #d07712) !important;
        color: #ffffff !important;
    }
    .ui-autocomplete-input, .datepicker {
        color: #0b3dca;
    }

    @media only screen and (max-width: 991px) {
        .auto-height {
            padding: 5px;
            margin-left: -15px;
            margin-right: -15px;
        }
        .sc-input {
            padding: 5px !important;
        }
        #sc_btnsearch {
            width: 100%;
        }
    }

    @media only screen and (min-width: 1200px) {
        .sc-col-12 {
            width: 12%;
        }
        .sc-col-9 {
            width: 9%;
        }
        .seperator {
            border-right: solid 1px #cdcdcd;
        }
    }    
    @media only screen and (min-width:768) and (max-width: 1200px) {
        .sc-col-12 {
            width: 12%;
        }
        .sc-col-9 {
            width: 9%;
        }
        .seperator {
            border-right: solid 1px #cdcdcd;
        }
    }    
    .one-rou ul {
        display: inline-flex;
    }
    .dropdown_n, .dropbtn_n9, .dropbtn_n10 {
        position: relative;
        display: inline-block;
    }

    .dropbtn_n9 {
        -moz-appearance: none;
        border: 0;
        float: left;
        font-weight: 600;
        font-size: 13px;
        outline: medium none;
        padding: 22px 10px 18px;
        /*width: 105px;*/
        width: 100%;
        cursor: pointer;
        text-align: left;
        background: #fff url(https://www.easemytrip.com/img/arro-hp-new.png) no-repeat 97% 55%;
        /* font-size: 10pt; */
        /* font-weight: 600; */
        color: #797979;
    }    

    /* Model section | Travellers & Trip Type*/
    .dropdown-content-n {
        display: none;
        position: absolute;
        background-color: #fff;
        width: 180px;
        padding: 10px;
        box-shadow: 0 0 20px 0 rgba(0,0,0,0.45);
        z-index: 1;
        top: 65px;
        box-sizing: content-box;
        -webkit-box-sizing: content-box;
    }    
    .innr_pnl {
        width: 180px;
        position: relative;
    }    
    .innr_pnl::before {
        content: '';
        position: absolute;
        left: 2%;
        top: -15px;
        width: 0;
        height: 0;
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-bottom: 5px solid #fff;
        clear: both;
    }    
    .main_dv {
        width: 100%;
        float: left;
        margin-bottom: 13px;
    }    
    .dropdown-content-n a {
        text-decoration: none;
        display: block;
    }

    .dn_btn {
        cursor: pointer;
        background: #ef6614;
        /*float: right;*/
        text-align: center;
        padding: 4px 12px;
        display: block;
        color: #fff;
        font-size: 11px;
        border-radius: 3px;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
    }    
    .ttl_col {
        width: 35%;
        float: left;
    }    
    .count_col {
        width: 65%;
        float: left;
    }        
    .ttl_col p {
        font-size: 13px;
        color: #000;
        display: block;
        margin: 0px;
    }    
    .ttl_col span {
        font-size: 10px;
        color: #a3a2a2;
        display: block;
    }    
    .count_col {
        width: 65%;
        float: left;
    }    
    .plu-mis-s {
        width: 85px;
        float: right;
    }    
    .minus_boxADt, .minus_box1 {
        background-color: #2196f3;
        border: none;
        clear: both;
        color: #fff;
        cursor: pointer;
        display: block;
        float: left;
        font-size: 19px;
        margin-left: 0;
        outline: none;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        line-height: 0;
    }    
    .numbe_box2 {
        background-color: transparent;
        border: none;
        color: #000;
        display: block;
        float: left;
        font-size: 15px;
        outline: none;
        text-align: center;
        width: 32px;
        line-height: 27px;
    }    
    .plus_box1, .plus_boxChd, .plus_box1Inf {
        background-color: #2196f3;
        border: none;
        color: #fff;
        cursor: pointer;
        display: block;
        float: right;
        font-size: 21px;
        margin-right: 0;
        outline: none;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        line-height: 0;
        padding: 0;
    }    
    .ctrl_dv {
        background-color: #2196f3;
        padding: 3px;
    }

    /* Trip type controls */
    .cont_flt {
        display: inline-block;
        position: relative;
        padding-left: 28px;
        margin: 6px 25px 6px 0;
        cursor: pointer;
        font-size: 13px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        line-height: 19px;
    }    
    .cont_flt input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }    
    .cont_flt input:checked ~ .chk_flt {
        background-color: #0b3dca;
    }
    .chk_flt {
        position: absolute;
        top: 0;
        left: 0;
        height: 20px;
        width: 20px;
        background-color: #fff;
        border-radius: 50%;
        border: 1px solid #2196f3;
    }    

    a:link, a:hover, a:visited {
        text-decoration: none;
    }

    .transit-point {
        text-transform: uppercase; 
        font-size: 15px; 
        width: 100%;
    }

    .selected ~ .chk_flt {
        background-color: #0b3dca;
    }
</style>
<div class="container_bg">
    <div class="middle_part">
        <h1 class="">
            <i class=""></i>
            <span>Search Lowest Airfare</span>
        </h1>
        <div class="one-rou">
            <input name="Trip" id="Trip" class="hide-round one_rou_click" value="One" checked="checked" type="radio" style="display: none;">
            <input name="Trip" id="radio1" class="hide-round2 rund" value="Two" type="radio" style="display: none;">
            <!-- <input name="Trip" id="rdoMul" class="hide-round2 rund" value="Mul" type="radio" style="display: none;"> -->
            <ul>
                <li class="border-lft flig-show click-one bg-color" onclick="setType('O');">One Way </li>
                <li class="click-round flig-show" onclick="setType('R');">Round Trip</li>
            </ul>
        </div>
        <div class="clr"></div>
        <div class="search_bg">
            <!-- <div class="h-100"> -->
                <form class="h-100" id="frm_one_way" action="<?php echo base_url(); ?>search/search_one_way" method="post" onsubmit="return validation_ticket_search('oneway')">
                    <input type="hidden" id="trip_type" name="trip_type" value="<?= $trip_type ?>"> 
                    <input type="hidden" id="source" name="source" value="<?= intval($source) ?>"> 
                    <input type="hidden" id="destination" name="destination" value="<?= intval($destination) ?>"> 
                    <!-- <input type="hidden" name="departure_date" value="03/05/2020">  -->
                    <input type="hidden" id="no_of_person" name="no_of_person" value="<?= (intval($no_of_person)>0 ? intval($no_of_person) : 1) ?>"> 
                    <input type="hidden" id="adult" name="adult" value="<?= (intval($adult)>0 ? intval($adult) : 1) ?>"> 
                    <input type="hidden" id="child" name="child" value="<?= (intval($child)>0 ? intval($child) : 0) ?>"> 
                    <input type="hidden" id="infant" name="infant" value="<?= (intval($infant)>0 ? intval($infant) : 0) ?>"> 
                    <input type="hidden" id="class_type" name="class_type" value="<?= $class ?>"> 
                    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 seperator">
                        <!-- <span>From</span> -->
                        <div class="group-item auto-height">
                            <input type="text" id="sc_source" name="sc_source" title="Departing airport" placeholder="From" class="sc-input transit-point" autocomplete="off" field="source" style="text-transform: uppercase; font-size: 15px; width: 100%;" value="<?= $source_city_name ?>">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 seperator">
                        <!-- <span>To</span> -->
                        <div class="group-item auto-height">
                            <input type="text" id="sc_destination" name="sc_destination" title="Arriving airport" placeholder="To" class="sc-input transit-point" autocomplete="off" field="destination" value="<?= $destination_city_name ?>">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 seperator">
                        <!-- <span>Dept Date</span> -->
                        <div class="group-item auto-height">
                            <input id="departure_date" name="departure_date" type="text" class="datepicker sc-input" placeholder="Journey Date" autocomplete="off" value="<?= $departure_date ?>">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 seperator">
                        <!-- <span>Rtn Date</span> -->
                        <div class="group-item auto-height">
                            <input id="return_date" name="return_date" type="text" class="datepicker sc-input" placeholder="Return Date" autocomplete="off" disabled value="<?= $return_date ?>">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 seperator sc-col-12">
                        <div class="group-item auto-height">
                            <!-- <span class="sc-input">Travellers</span> -->
                            <a onclick="myFunction4($('#myDropdown_n'))" class="dropbtn_n9">
                                <span id="travellers_count" class="drpNoTrv"><?= (intval($no_of_person)>0 ? intval($no_of_person) : 1) ?> Traveller(s)</span>
                            </a>
                            <div id="myDropdown_n" class="dropdown-content-n">
                                <div class="innr_pnl">
                                    <div class="main_dv">
                                        <div class="ttl_col"><p>Adult</p><span>(12+ yrs)</span></div>
                                        <div class="count_col">    
                                            <div class="plu-mis-s">
                                                <div class="m1">
                                                    <input type="button" value="-" class="minus_boxADt" field="quantity">
                                                </div>
                                                <div class="tx">
                                                    <input type="text" name="quantity" value="<?= (intval($adult)>0 ? intval($adult) : 1) ?>" class="numbe_box2" id="optAdult" readonly="">
                                                </div>
                                                <div class="pl">
                                                    <input type="button" value="+" class="plus_box1" field="quantity">
                                                </div>
                                                <div class="clr"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="main_dv">
                                        <div class="ttl_col"><p>Children</p><span>(2+ 12 yrs)</span></div>
                                        <div class="count_col">    
                                            <div class="plu-mis-s">
                                                <div class="m1">
                                                    <input type="button" value="-" class="minus_box1" field="quantity1">
                                                </div>
                                                <div class="tx">
                                                    <input type="text" name="quantity1" value="<?= (intval($child)>0 ? intval($child) : 0) ?>" class="numbe_box2" id="optChild" readonly="">
                                                </div>
                                                <div class="pl">
                                                    <input type="button" value="+" class="plus_boxChd" field="quantity1">
                                                </div>
                                                <div class="clr"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="main_dv">
                                        <div class="ttl_col"><p>Infant(s)</p><span>(below 2 yrs)</span></div>
                                        <div class="count_col">    
                                            <div class="plu-mis-s">
                                                <div class="m1">
                                                    <input type="button" value="-" class="minus_box1" field="quantity2">
                                                </div>
                                                <div class="tx">
                                                    <input type="text" name="quantity2" value="<?= (intval($infant)>0 ? intval($infant) : 0) ?>" class="numbe_box2" id="optInfant" readonly="">
                                                </div>
                                                <div class="pl">
                                                    <input type="button" value="+" class="plus_box1Inf" field="quantity2">
                                                </div>
                                                <div class="clr"></div>
                                            </div>
                                        </div>
                                    </div>    
                                    <div class="clr"></div>   
                                    <div class="ctrl_dv">
                                        <a id="traveLer" class="dn_btn">Done</a>
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 sc-col-12">
                        <div class="group-item auto-height">
                            <!-- <span class="sc-input">Economy</span> -->
                            <a onclick="myFunction4($('#myDropdown_n9'))" class="dropbtn_n9">
                                <span id="travel_class" class="drpNoTrv">Economy</span>
                            </a>                        
                            <div id="myDropdown_n9" class="dropdown-content-n">
                                <div class="innr_pnl">
                                    <label class="cont_flt">Economy
                                        <input type="radio" value="0" name="optClass" class="tr_class" checked="checked" onclick="fillOptClassName('Economy', this)">
                                        <span class="chk_flt"></span>
                                    </label>
                                    <label class="cont_flt">
                                        Prem.Economy
                                        <input type="radio" value="4" name="optClass" class="tr_class" onclick="fillOptClassName('Prem.Economy', this)">
                                        <span class="chk_flt"></span>
                                    </label>
                                    <label class="cont_flt">
                                        Business
                                        <input type="radio" value="2" name="optClass" class="tr_class" onclick="fillOptClassName('Business', this)">
                                        <span class="chk_flt"></span>
                                    </label>
                                    <label class="cont_flt optFrst" style="display: none;">
                                        First
                                        <input type="radio" value="1" name="optClass" class="tr_class" onclick="fillOptClassName('First', this)">
                                        <span class="chk_flt"></span>
                                    </label>
                                    <div class="clr"></div>     
                                    <div class="ctrl_dv">
                                        <a id="triptype" class="dn_btn">Done</a>
                                    </div>  
                                </div>
                            </div>                        
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 sc-col-9" style="padding: 0px;">
                        <div class="group-item">
                            <button type="submit" id="sc_btnsearch" name="sc_btnsearch" title=" Search flight">Search</button>
                            <!-- <button type="submit" id="srch" name="srch" title=" Search flight">Search</button> -->
                        </div>
                    </div>
                </form>
            <!-- </div> -->
        </div>        
    </div>
</div>

<script lang="javascript">
    var availableTags = [
        "ActionScript",
        "AppleScript",
        "Asp",
        "BASIC",
        "C",
        "C++",
        "Clojure",
        "COBOL",
        "ColdFusion",
        "Erlang",
        "Fortran",
        "Groovy",
        "Haskell",
        "Java",
        "JavaScript",
        "Lisp",
        "Perl",
        "PHP",
        "Python",
        "Ruby",
        "Scala",
        "Scheme"
    ];

    var cities = JSON.parse('<?= json_encode($sources) ?>');

    $(function() {
        // $( ".datepicker" ).datepicker( "option", "showAnim", "slideDown" );
        $( "#sc_source, #sc_destination" ).autocomplete({
            source: getCity,
            minLength: 2,
            select: function( event, ui ) {
                console.log( "Selected: " + ui.item.value + " aka " + ui.item.label );
                if(event.target && event.target.id) {
                    var target_field = $(event.target).attr('field');
                    if(target_field) {
                        //$(`#${target_field}`).val(parseInt(ui.item.label, 10));
                        $(`#${target_field}`).attr('value', parseInt(ui.item.label, 10));
                    }
                }
            },
            create: function() {
                $(this).data('ui-autocomplete')._renderItem = function( ul, item ) {
                    console.log(item);
                    return $( "<li>" )
                        .attr( "data-value", item.label )
                        //.append( item.label )
                        .append( '<span class="list-item">' + item.value    + '</span>')
                        .appendTo( ul );
                };
            },
        });

        $(".sc-input, body").click(function() {
            $(".dropdown-content-n").hide();
        });

        $(".plus_box1, .plus_boxChd, .plus_box1Inf").click(ev => {
            //This is addition
            //optAdult, optChild, optInfant
            var field_name = $(ev.target).attr('field');
            var value = parseInt($(`input[name='${field_name}']`).val(), 10);

            if(value<9) {
                value++;
                $(`input[name='${field_name}']`).val(value);
                console.log(`Value added. New value => ${value}`);

                var adult_qty = parseInt($('#optAdult').val(), 10);
                var infant_qty = parseInt($('#optInfant').val(), 10);

                if(infant_qty>adult_qty) {
                    value--;
                    $(`input[name='${field_name}']`).val(value);
                    alert('Number of infant can`t be more than adult');
                }

                updateTravellersCount();
            }
            else {
                alert('Travellers can`t be more than 9');
            }

            event.stopPropagation();
        });

        $(".minus_boxADt, .minus_box1").click(ev => {
            //This is addition
            //optAdult, optChild, optInfant
            var field_name = $(ev.target).attr('field');
            var value = parseInt($(`input[name='${field_name}']`).val(), 10);

            if(value>0) {
                value--;
                $(`input[name='${field_name}']`).val(value);
                console.log(`Value added. New value => ${value}`);

                var adult_qty = parseInt($('#optAdult').val(), 10);
                var infant_qty = parseInt($('#optInfant').val(), 10);

                if(infant_qty>adult_qty) {
                    value++;
                    $(`input[name='${field_name}']`).val(value);
                    alert('Number of infant can`t be more than adult');
                }

                updateTravellersCount();
            }
            else {
                alert('Travellers can`t be less than 0');
            }

            event.stopPropagation();
        });
    });


    function updateTravellersCount() {
        var travellers_count = 0;

        var adult_qty = parseInt($('#optAdult').val(), 10);
        var child_qty = parseInt($('#optChild').val(), 10);
        var infant_qty = parseInt($('#optInfant').val(), 10);

        travellers_count = adult_qty+child_qty+infant_qty;
        $('input[name="no_of_person"]').val(adult_qty+child_qty+infant_qty); //Yes Infant count also should be plus. But rate of infant will be different.
        $('input[name="adult"]').val(adult_qty);
        $('input[name="child"]').val(child_qty);
        $('input[name="infant"]').val(infant_qty);

        $('#travellers_count').html(`${travellers_count} traveller(s)`);
    }
    var prev_ctrl = null;
    function myFunction4(elContainer) {
        if(elContainer) {
            if(prev_ctrl) {
                //prev_ctrl.hide();
                //prev_ctrl = null;
                $(".dropdown-content-n").hide();
                prev_ctrl = null;
            }

            if(prev_ctrl && elContainer && elContainer.selector !== prev_ctrl.selector) {
                elContainer.toggle();
            } else if(prev_ctrl === null && elContainer) {
                elContainer.toggle();
            }

            if(elContainer.is(':visible')) {
                elContainer.focus();
            }

            prev_ctrl = elContainer;
            event.stopPropagation();
        }
    }

    function getCity(request, response) {
        console.log(request);
        //response(availableTags);
        var city_list = [];
        for (let index = 0; index < cities.length; index++) {
            const city = cities[index];
            if(city && city.sector.toLowerCase().indexOf(request.term)>-1) {
                city_list.push({'label': city.id, 'value': city.sector });
            }
        }
        response(city_list);
    }

    function fillOptClassName(className, ctrl) {
        $('#travel_class').html(className);
        $('#class').val(className);

        // for (let index = 0; index < $('.tr_class').length; index++) {
        //     const element = $('.tr_class')[index];
        //     if($(element).hasClass('selected'))
        //         $(element).removeClass('selected');
        // }

        // $(ctrl).addClass('selected');
    }
</script>