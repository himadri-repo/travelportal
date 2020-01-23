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
                    </div>
                </div>
            </div>
            <div class="body">
                <div class="title">Dear {company_name} Travel Agency,</div>

                <span class="highlight">One of your user couldn't found preferred itenary in your inventory. Please find below of the user and his/her query. Please connect him/her and get his queries resolved.</span>
                <div class="booking_details">
                    <div class="key">Req.No #</div>
                    <div class="value">{reqid}</div>
                </div>
                <div class="booking_details">
                    <div class="key">Journey details</div>
                    <div class="value">{source_city} to {destination_city}</div>
                </div>
                <div class="booking_details">
                    <div class="key">Departure Date</div>
                    <div class="value">{departure_date} (
                    {if is_flexible == true}
                        <div class="value">Customer is flexible on date</div>
                    {else}
                        <div class="value">Customer is not flexible on date</div>
                    {/if}					
					)</div>
                </div>
                <div class="booking_details">
                    <div class="key">No of PAX</div>
                    <div class="value">{no_of_person}</div>
                </div>
                <div class="booking_details">
                    <div class="key">Expected price range</div>
                    <div class="value">{start_price} - {end_price}</div>
                </div>
                <div class="booking_details">
                    <div class="key">Preferred Time Slots</div>
                    <div class="value">{time_range}</div>
                </div>
                <div class="booking_details">
                    <div class="key">Mobile</div>
                    <div class="value">{mobile}</div>
                </div>
                <div class="booking_details">
                    <div class="key">Email</div>
                    <div class="value">{email}</div>
                </div>
                <div class="booking_details">
                    <div class="key">Special Instruction</div>
                    <div class="value">{remarks}</div>
                </div>
                <span class="highlight">In case you need any help in procuring this inventory may contact Travel Mergers support team. Your objective should be not to send customers with empty hand.</span>
                <div class="regards">Thank you once again,<br/>{company_name} Operation Team</div>
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