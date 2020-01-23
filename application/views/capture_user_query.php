<!--================ PAGE-COVER =================-->
<!-- <section class="page-cover" id="cover-user-query">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="page-title">User Query</h1>
                <ul class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li class="active">Registration</li>
                </ul>
            </div> --><!-- end columns -->
        <!-- </div>--><!-- end row -->
    <!-- </div>--><!-- end container -->
<!-- </section>--><!-- end page-cover -->

<!--===== User Query Form ====-->
<section class="user_query">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="query-form" style="margin: 0px -15px; text-align: left; border: 1px solid #cdcdcd;">
                <div style="background-color: #f5ebda;padding: 5px; margin: 0px 0px 10px 0px;">
                    <h3>Didn't find your preferred itinerary?</br>No Problem! Please share your query, will get back to you.</h3>
                    <h4 style="text-decoration: underline;">In case of emergency please do call us at <?= isset($state['contact_number'])?$state['contact_number']:' [Contact Us]'?></h4>
                </div>
                
                <div class="tab-content" style="padding: 5px;">
                    <div id="query" class="tab-pane fade in active">
                        <form class="pg-search-form" id="frm_one_way" action="" onsubmit="return submit_queryform()" autocomplete="off">
                            <input type="hidden" name="companyid" value="<?= intval($company['id'])?>"> 
                            <input type="hidden" name="userid" value="<?= intval($currentuser['id'])?>"> 

                            <div class="row"> <!-- start of first row-->
                                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label><span><i class="fa fa-map-marker"></i></span>From</label>
                                        <select class="form-control" name="sector_source" id="sector_source">
                                            <option value="">Source</option>
                                            <?php
                                            foreach($state['sectors'] as $sector)
                                            { ?>
                                                <option value="<?php echo $sector['id'];?>" <?php if($post[0]["source"]==$sector["id"]) echo "selected"; ?>><?php echo $sector["city"];?></option>
                                            <?php
                                            } ?>
                                        </select>
                                    </div>
                                </div><!-- end columns -->
                                
                                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label><span><i class="fa fa-map-marker"></i></span>To</label>
                                        <select class="form-control" name="sector_destination" id="sector_destination">
                                            <option value="">Destination</option>
                                            <?php
                                            foreach($state['sectors'] as $sector)
                                            { ?>
                                                <option value="<?php echo $sector['id'];?>" <?php if($post[0]["destination"]==$sector["id"]) echo "selected"; ?>><?php echo $sector["city"];?></option>
                                            <?php
                                            } ?>
                                        </select>
                                    </div>
                                </div><!-- end columns -->
                                
                                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label><span><i class="fa fa-calendar"></i></span>Departure Date</label>
                                        <!--<input class="form-control dpd3" placeholder="Date" name="departure_date_time" id="departure_date_time" autocomplete="off"/>-->
                                        <input class="datepicker" placeholder="dd/mm/yyyy" name="qry_departure_date" id="qry_departure_date" readonly value="<?= $post[0]['departure_date']; ?>"/>
                                        <!-- <input class="form-control datepicker" name="departure_date" id="departure_date" readonly required/> -->
                                        <input type="checkbox" name="flexible" id="flexible" value="flexible"><label style="margin: 0px 5px;">Is Date Flexible ?</label>
                                        <!-- <select class="form-control" name="departure_date_time" id="departure_date_time" style="display:none">
                                        </select>   -->
                                    </div>
                                </div><!-- end columns -->
                                
                                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label><span><i class="fa fa-users"></i></span>Passengers</label>                                                        
                                        <select class="form-control" name="qry_no_of_person" id="qry_no_of_person">
                                            <option value="1"  <?php if($post[0]["no_of_person"]==1) echo "selected"; ?>>1</option>
                                            <option value="2"  <?php if($post[0]["no_of_person"]==2) echo "selected"; ?>>2</option>
                                            <option value="3"  <?php if($post[0]["no_of_person"]==3) echo "selected"; ?>>3</option>
                                            <option value="4"  <?php if($post[0]["no_of_person"]==4) echo "selected"; ?>>4</option>
                                            <option value="5"  <?php if($post[0]["no_of_person"]==5) echo "selected"; ?>>5</option>
                                            <option value="6"  <?php if($post[0]["no_of_person"]==6) echo "selected"; ?>>6</option>
                                            <option value="7"  <?php if($post[0]["no_of_person"]==7) echo "selected"; ?>>7</option>
                                            <option value="8"  <?php if($post[0]["no_of_person"]==8) echo "selected"; ?>>8</option>
                                            <option value="9"  <?php if($post[0]["no_of_person"]==9) echo "selected"; ?>>9</option>
                                        </select>
                                    </div>
                                </div>
                            </div><!-- end first row -->
                            <div class="row"> <!-- start of second row-->
                                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label><span><i class="fa fa-money" aria-hidden="true"></i></span>Price range (₹)</label>
                                        <div>
                                            <input type="number" name="start_price" id="start_price" value="0" min="0" max="999999" required><label style="margin: 0px 5px;">-</label>
                                            <input type="number" name="end_price" id="end_price" value="0" min="0" max="999999" required>
                                        </div>
                                    </div>
                                </div><!-- end columns -->
                                
                                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label><span><i class="fa fa-clock-o" aria-hidden="true"></i></span>Preferred Time</label>
                                        <select class="form-control" name="time_range" id="time_range">
                                            <option value="1">Morning   (04 hrs to 11 hrs)</option>
                                            <option value="2">Afternoon (11 hrs to 16 hrs)</option>
                                            <option value="3">Evening   (16 hrs to 21 hrs)</option>
                                            <option value="4">Night     (21 hrs to 04 hrs)</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label><span><i class="fa fa-mobile" aria-hidden="true"></i></span>Mobile</label>
                                        <input type="tel" name="mobile" id="mobile" required value="<?= $state['current_user']['mobile']?>">
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label><span><i class="fa fa-envelope-o" aria-hidden="true"></i></span>Email</label>
                                        <input type="email" name="email" id="email" required value="<?= $state['current_user']['email']?>">
                                    </div>
                                </div><!-- end columns -->
                            </div><!-- end second row -->
                            <hr/>
                            <div class="row"> <!-- start of third row-->
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <label><span><i class="fa fa-comment-o" aria-hidden="true"></i></span>Please describe your query</label>
                                    <!-- Textarea -->
                                    <textarea class='editor' name="remarks" id="remarks">
                                        <?php //if(isset($content)) { echo $content; } ?> 
                                    </textarea>                                
                                </div>
                            </div><!-- end third row -->
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 error" id="msg"></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 action-section">
                                    <button type="submit" class="btn btn-orange" id="btn_query" style="float: right;">Submit Query</button>
                                </div>
                            </div>
                        </form>
                    </div><!-- end tab-one-way -->
                </div><!-- end tab-content -->
            </div><!-- end page-search-form -->
            
            
            
        </div><!-- end columns -->
    </div><!-- end row -->
</section><!-- end innerpage-wrapper -->

<script language="javascript">
    function submit_queryform(ev) {

        $('#msg').text('');
        var source_sector = parseInt($('#sector_source').val(), 10);
        var destination_sector = parseInt($('#sector_destination').val(), 10);
        var departure_date = $('#qry_departure_date').val();
        var is_flexible = $('#flexible').is(':checked');
        var no_of_person = $('#qry_no_of_person').val();
        var start_price = parseFloat($('#start_price').val());
        var end_price = parseFloat($('#end_price').val());
        var time_range = $('#time_range').val();
        var mobile = $('#mobile').val();
        var email = $('#email').val();
        var remarks = escape(tinymce.get("remarks").getContent()); //escape($('#remarks').val());
        var userid = <?= intval($currentuser['id'])?>;
        var companyid = <?= intval($company['id'])?>;

        if(start_price<=0 || end_price<=0 || start_price>end_price) {
            $('#msg').removeClass('success');
            $('#msg').addClass('error');
            $('#msg').text('Invalid price range provided. Please correct the data and re-submit the form.');

            return false;
        }
        else if(source_sector == destination_sector) {
            $('#msg').removeClass('success');
            $('#msg').addClass('error');
            $('#msg').text('Departure and Arrival city can`t be same. Please correct the data and re-submit the form.');

            return false;
        }

        //alert(JSON.stringify(ticket));
        var origin = document.location.origin;
        //origin = origin.substr(0, origin.lastIndexOf('/'));
        var url = origin+'/api/admin/capture/user_query';
        var data = {mode: 'insert', payload: {source_sector: source_sector, destination_sector: destination_sector, departure_date: departure_date, no_of_person: no_of_person, start_price: start_price, end_price: end_price, time_range: time_range, mobile: mobile, email: email, remarks: remarks, userid: userid, companyid: companyid, is_flexible: is_flexible}};
        console.log(`posted data => ${JSON.stringify(data)}`);
        //url = url.substr(0, url.lastIndexOf('/'));
        $('#btn_query').hide();
        $.ajax(
            {
                url: `${url}`,
                type: 'POST',
                data: JSON.stringify(data),
                error: function(err) {
                    $('#msg').removeClass('success');
                    $('#msg').addClass('error');
                    $('#msg').text(`${err}`);
                    $('#btn_query').show();
                },
                success: function(data) {
                    if(data && parseInt(data['code'])===200) {
                        //document.location.reload();
                        $('#msg').removeClass('error');
                        $('#msg').addClass('success');
                        $('#msg').text(`${data['message']}`);
                    }
                    else {
                        $('#msg').removeClass('success');
                        $('#msg').addClass('error');
                        $('#msg').text(`${data['message']}`);
                        //alert(`${data.status} (${data.code})`);
                    }
                    $('#btn_query').show();
                }
            }
        );

        return false;
    }
</script>