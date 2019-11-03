<!Doctype html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link href="https://fonts.googleapis.com/css?family=Lato:300,300i,400,400i,700,700i,900,900i%7CMerriweather:300,300i,400,400i,700,700i,900,900i" rel="stylesheet">               
        
        <style>
            .container {
                width: 75%;
                margin-right: auto;
                margin-left: auto;
                /* padding-left: 10px;
                padding-right: 10px; */
                /* background-color: #97d8ef; */
                background-image: url('http://yourlifeyourjourney.in/images/white_bg.jpg');
                background-repeat: repeat repeat;
                background-color: #ffffff;
                background-position: center;
            }

            .header {
                width: 100%;
            }

            .brand-image {
                background-image: url('http://yourlifeyourjourney.in/images/email_template_header.jpg');
                background-color: #ffffff;
                background-position: center;
                background-repeat: no-repeat;
                background-size: 100% 500px;
                height: 400px;
            }
            .company {
                /*font-size: x-large;*/
                font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
                color: #ffffff;
                margin: 5px 0px;
                /*padding: 5px 0px;*/
            }
            .profile {
                background-color: #de8913;
                /* position: fixed; */
                top: 0px;
                left: 0px;
                height: 70px;
                text-align: center;
            }
            .field_value {
                margin: 0px 10px;
                color: #ffffff;
            }
            .call2action {
                color: #ffffff;
            }
            .body {
                width: 80%;
                margin-left: auto;
                margin-right: auto;
                margin-top: -55px;
                height: 800px;
                background-color: #ffffffd9;
                padding: 60px 20px;
            }
            .title {
                font-size: 25px;
                font-weight: 700;
                margin-bottom: 10px;
                word-spacing: .3em;
            }
            .highlight {
                font-size: 25px;
                font-weight: 400;
                word-spacing: .2em;
                display: block;
                line-height: 1.7em;
            }
            .booking_details {
                margin: 20px 10px;
                font-size: 20px;
            }
            .booking_details .key {
                display: inline-block;
                font-weight: 600;
                word-spacing: .2em;
                width: 35%;
                margin: 0px 50px;
            }
            .booking_details .value {
                /* float: right; */
                font-weight: 400;
                /* word-spacing: .2em; */
                /* width: 50%; */
                padding: 3px 6px;
                display: inline-block;
            }
            .footer {
                background-color: #de8913;
                /*position: fixed;*/
                height: 70px;
                text-align: center;
                margin: -5px 0px 0px 0px;
            }
            .inwords {
                font-size: 15px;
                font-weight: 300;
            }
            .regards {
                margin: 15px 0px;
                font-size: 20px;
                font-weight: 600;
                word-spacing: .3em;
            }
        </style>
    </head>
    <body>
        <!-- Container -->
        <div class="container">
            <!--============ Header =============-->
            <div class="header">
                <div class="brand-image">
                    <div class="profile">
                        <h1 class="company">{company_name} Travel Agency</h1>
                        <div class="call2action">
                            <a href='' title='Call us' class="field_value">{phone_number}</a>
                            <a href="{action_url}" title='Search flight' class="action field_value">Search Flight</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="body">
                <div class="title">Dear Traveler,</div>

                <span class="highlight">Thank you for booking flights with us. We want you to have a more enriching travel experience. Please find your booking details as below. </span>
                <div class="booking_details">
                    <div class="key">Booking #</div>
                    <div class="value">{booking_number}</div>
                </div>
                <div class="booking_details">
                    <div class="key">Journey details</div>
                    <div class="value">{departure_city} to {arrival_city}</div>
                </div>
                <div class="booking_details">
                    <div class="key">Airlines & Flight #</div>
                    <div class="value">{airline} ({flight_number})</div>
                </div>
                <div class="booking_details">
                    <div class="key">Planned Departure Date</div>
                    <div class="value">{departure_date}</div>
                </div>
                <div class="booking_details">
                    <div class="key">Planned Arrival Date</div>
                    <div class="value">{arrival_date}</div>
                </div>
                <div class="booking_details">
                    <div class="key">No of PAX</div>
                    <div class="value">{no_of_pax}</div>
                </div>
                <!-- <div class="booking_details">
                    <div class="key">Invoice Amount</div>
                    <div class="value">₹ {invoice_amount}</div>
                </div> -->
                <!-- <div class="booking_details">
                    <div class="key">PNR</div>
                    <div class="value">{booking_status}</div>
                </div> -->
                
                <div class="booking_details">
                    <div class="key">PNR</div>
                    {if booking_status == 'PENDING'}
                        <div class="value">{booking_status}</div>
                    {else}
                        <div class="value">{pnr}</div>
                    {/if}
                </div>
                <span class="highlight">Please do share your valuable feedback with us. Please feel free to contact us for any of your travel need. We can assure you our best services </span>
                <div class="regards">Thank you once again,<br/>OxyTra Operation Team</div>
            </div>
            <div class="footer">
                <h1 class="company">{company_name} Travel Agency</h1>
                <div class="call2action">
                    <a href='{action_url}' title='Contact Us' class="field_value">Your Ultimate Travel Partner</a>
                </div>
            </div>
        </div>
    </body>
</html>