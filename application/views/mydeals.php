<style>
    #mydeal-section {
        min-height: 700px;
    }

    #btn_search {
        margin: 27px 0px 1px 0px;
    }

    .content-data {
        margin: 20px;
        border-top: 1px solid #a1a1a1;
    }

    .innerpage-wrapper {
        background-color: #e5eef3;
    }
</style>
<!--==== INNERPAGE-WRAPPER =====-->
<section class="innerpage-wrapper">
    <div id="mydeal-section" class="innerpage-section-padding">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 content-side">
                    <h2><span style="font-weight: 200;">Flight <i class="fa fa-plane"></i></span> <span style="color: #faa61a; font-weight: 800;">Deals</span></h2>
                    <div class="search-area">
                    <form class="pg-search-form" id="frm_one_way" action="<?php echo base_url(); ?>search/mydeals/<?= $currentuser['uid']?>" method="post" onsubmit="return deal_validation()">
                        <input type="hidden" name="userid" value="<?= $currentuser['uid']?>"> 
                        <input type="hidden" name="companyid" value="<?= $currentuser['companyid']?>"> 
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-md-6 col-xs-offset-2">
                                <div class="form-group">
                                    <label><span><i class="fa fa-map-marker"></i></span>Departure - Arrival</label>
                                    <select class="form-control" name="circle" id="circle">
                                        <option value="-1" <?= (($circle=='-1') ? 'selected' : '')?>>Show Weekly View</option>
                                        <?php 
                                        if($circles && is_array($circles) && count($circles)>0) {
                                            foreach($circles as $circleitem)
                                            {
                                            ?>
                                                <option value="<?= $circleitem["source_id"].'~^~'.$circleitem["destination_id"];?>" <?= (($circle==($circleitem["source_id"].'~^~'.$circleitem["destination_id"])) ? 'selected' : '')?>><?= $circleitem["source_city"].' to '.$circleitem["destination_city"];?></option>
                                            <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-2 col-md-2">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-orange" id="btn_search">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div><!-- end row -->
            <hr/>
            <?php if($selected_circle != '-1' && $tickets) {?>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 content-data">
                    <h2><?= $selected_circle?></h2>
                    <div class="table-responsive-sm">
                        <table class="table table-sm table-hover table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Date</th>
                                    <th scope="col">Deal</th>
                                    <th scope="col">Airline</th>
                                    <th scope="col">Flight #</th>
                                    <th scope="col">Dept.Time</th>
                                    <th scope="col">Arrv.Time</th>
                                    <th scope="col">Avail.Qty</th>
                                    <th scope="col">Req.Qty</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($tickets) { 
                                    foreach($tickets as $ticket) {
                                        //http://example.com:90/search/flightdetails/25123
                                        $ticketid = intval($ticket['id']);
                                        $bookurl = base_url().'search/flightdetails/'.$ticketid.'?qty={qty}';
                                        if(!$isauthenticated) {
                                            $bookurl = base_url().'search/flightdetails/'.$ticketid.'&qty={qty}';
                                            $bookurl = base_url()."login?returnurl=$bookurl";
                                        }
                                    ?>
                                        <tr>
                                            <th scope="row"><?= date("jS M Y",strtotime($ticket['departure_date_time'])); ?></th>
                                            <td><?= number_format($ticket['ticket_price'],2)?></td>
                                            <td><?= $ticket['aircode']?></td>
                                            <td><?= $ticket['flight_no']?></td>
                                            <td><?= date("H:i",strtotime($ticket['departure_date_time'])); ?></td>
                                            <td><?= date("H:i",strtotime($ticket['arrival_date_time'])); ?></td>
                                            <td><?= $ticket['no_of_person']?></td>
                                            <td><input type="number" id="qty_<?= $ticketid ?>" name="qty" value="1" style="width: 50px;" min="1" max="9"></td>
                                            <td><a href="<?= $bookurl?>" class="btn btn-primary btn-sm" role="button" aria-pressed="true" onclick="return validate(this, <?= $ticketid ?>);">Book</a></td>
                                        </tr>
                                    <?php 
                                    }
                                } ?>
                            </tbody>                        
                        </table>
                    </div>
                </div>
            <?php }
            else { ?>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 content-data">
                    <h2><?= $selected_circle?></h2>
                    <div class="table-responsive-sm">
                        <table class="table table-sm table-hover table-striped">
                            <thead>
                                <?php if($ticket_calender) { ?>
                                <tr>
                                    <?php 
                                        $idx = 0;
                                        foreach($ticket_calender[0] as $key=>$value) {
                                            if($idx !== 1) {
                                    ?>
                                            <th scope="col" style="background-color:#de8e07; color: #ffffff; font-weight: 700;"><?= $key ?></th>
                                    <?php 
                                            }
                                            $idx++;
                                        } ?>
                                </tr>
                                <?php } ?>
                            </thead>
                            <tbody>
                                <?php if($ticket_calender) { 
                                    for ($i=0; $i < count($ticket_calender); $i++) { 
                                        $idx = 0; ?>
                                        <tr>
                                            <?php foreach($ticket_calender[$i] as $key=>$value) {
                                                if($idx === 0) {
                                            ?>
                                                    <th scope="row" style="color:#de8e07; font-weight: 600;"><?= $ticket_calender[$i][$key] ?></th>
                                            <?php 
                                                }
                                                else if($idx>1) { ?>
                                                    <td style="font-weight: 600;"><?= str_pad($ticket_calender[$i][$key], 10, ' ', STR_PAD_LEFT) ?></td>
                                                <?php }
                                                $idx++;
                                            } ?>
                                        </tr>
                                    <?php }
                                } ?>
                            </tbody>                        
                        </table>
                    </div>
                </div>
            <?php } ?>
        </div><!-- end container -->
    </div><!-- end thank-you --> 
</section><!-- end innerpage-wrapper -->

<script language="javascript">
    function deal_validation() {
	    if($("#source").val()=="")
		{	   
			$("#source").addClass('is-invalid');
			//$("#source").parent().find(".error").remove();
			$("#source").parent().append('<div class="error">Please Select Source !!!</div>');
			return false;
		}

        return true;
    }

    function validate(ctrl, ticketid) {
        var qty = $('#qty_'+ticketid).val();
        var flag = false;

        //alert(qty);
        //alert($(ctrl).attr('href'));
        if(qty>0) {
            try
            {
                var url = $(ctrl).attr('href');

                url = url.replace('{qty}', qty);

                $(ctrl).attr('href', url);
                url = $(ctrl).attr('href');

                // alert(url);
                flag = true;
            }
            catch(e) {
                alert(e);
            }
        }
        return flag;
    }
</script>
