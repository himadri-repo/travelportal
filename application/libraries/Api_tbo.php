<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('Api.php');
require_once(APPPATH.'core/Common.php');

class Api_tbo extends Api {
    private $config = array();

    private $timeout = 240;

    private $apibaseurl = '';
    private $host = '';
    private $userid = '';
    private $password = '';

    private $clientid = '';
    private $agencytype = '';
    private $tokenid = '';
    private $token_generated_on = NULL;

    private $sponsoring_config = [];
    private $tenent_config = [];

    private $member = [];

    private $cash_balance = 0.00;
    private $ticketing_balance = 0.00;

    private $fare = NULL;
    private $fare_breakdown = NULL;
    private $segments = NULL;
    private $fare_rules = NULL;
    private $book_result = NULL;

    private $enabled = true;

	public function __construct($config)
	{
        parent::__construct();
        
        if(is_array($config)) {
            $this->config = array_merge(array("companyid" => 0, "clientid" => "", "userid" => "", "password" => "", "url" => '', 'sponsoring_config' => false, 'tenant_config' => false), $config);

            //$this->host = $this->config['clientid'];
            $this->userid = $this->config['userid'];
            $this->password = $this->config['password'];
            $this->apibaseurl = $this->config['url'];
            $this->sponsoring_config = $this->config['sponsoring_config'];
            $this->tenent_config = $this->config['tenant_config'];
            $this->host = $this->sponsoring_config ? $this->sponsoring_config['ClientId'] : $this->config['clientid'];


            log_message('debug', "Config passed => ".json_encode($config));
        }
        else {
            log_message('error', "Library - api_tripmaza - Wrong config passed - $config");
        }
    }
    
    public function post($urlpart, $data, $content_type="application/json") {

        log_message('debug', "Posted Data : ".json_encode($data, JSON_UNESCAPED_SLASHES));
        //log_message('debug', "Posted Data : ".json_encode($data, JSON_FORCE_OBJECT | JSON_UNESCAPED_SLASHES));
        //log_message('debug', "Posted Data : ".array2json($data, JSON_UNESCAPED_SLASHES));

        $data = json_encode($data, JSON_UNESCAPED_SLASHES);
        //$data = json_encode($data, JSON_FORCE_OBJECT | JSON_UNESCAPED_SLASHES);
        //$data = array2json($data, JSON_FORCE_OBJECT | JSON_UNESCAPED_SLASHES);
        $curl = curl_init();
        $opt = array(
            CURLOPT_URL => $this->apibaseurl.$urlpart,
            //CURLOPT_URL => 'http://api.tektravels.com/SharedServices/SharedData.svc/rest/Authenticate',
            CURLOPT_RETURNTRANSFER => true,
            CURLINFO_HEADER_OUT => true,
			//CURLOPT_ENCODING => "",
			//CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => $this->timeout,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POST => true,
            //CURLOPT_POSTFIELDS => json_encode(array('ClientId' => 'ApiIntegrationNew', 'UserName' => 'RadhaRani', 'Password' => 'radha@1234', 'EndUserIp' => '192.168.11.120'), JSON_FORCE_OBJECT), //$data,
            CURLOPT_POSTFIELDS => $data,
			CURLOPT_HTTPHEADER => array(
                "Content-Type: $content_type",
                //"Content-Length: ".strlen($data)
			),
		);

        $max = ini_get('max_execution_time');
        ini_set('max_execution_time', $this->timeout);
		curl_setopt_array($curl, $opt);

		$response = curl_exec($curl);
		$err = curl_error($curl);
        $info = curl_getinfo($curl);

        log_message('debug', 'Info Collected from REST Call '.json_encode($info));
        log_message('debug', 'Response Collected from REST Call '.json_encode($response));
        log_message('debug', 'Error Collected from REST Call '.json_encode($err));

        curl_close($curl);
        ini_set('max_execution_time', $max);

        return array('response' => $response, 'debug' => $info, 'err' => $err);
    }

    public function reauthenticate()
    {
        $data = array('EndUserIp' => '121.101.14.10', 'ClientId' => $this->host, 'UserName' => $this->userid, 'Password' => $this->password);
        $err = '';
        $response = '';
        $info = '';
        $urlpart = "SharedServices/SharedData.svc/rest/Authenticate";
        $result = $this->post($urlpart, $data, "application/json");
        if($result && is_array($result)) {
            $response = $result['response'];
            $info = isset($result['debug']) ? $result['debug'] : '';
            $err = $result['err'];
        }

		if ($err) 
		{
			log_message("debug", "ERROR : $err");
		  	return false;
		} 
		else 
		{
            $this->token_generated_on = date('Y-m-d H:i:s');

            log_message("debug", "Response | $response");
            if($response !== '') {
                $response = json_decode($response, TRUE);
            }
    
            if($response && is_array($response) && $response['Error'] && intval($response['Error']['ErrorCode']) === 0) {
                $this->member = $response['Member'];
                $this->tokenid = $response['TokenId'];

                log_message('debug', "Token generated -> ".$this->tokenid." | Member Details -> ".json_encode($this->member));
            }
            else {
                log_message('error', "Some error -> ".json_encode($response));
            }
		  	return $response;
		}
    }

    public function search_inventory($payload) {
        if(!$payload || !is_array($payload)) return NULL;
        $trip_type = trim($payload['trip_type']);

        $pax = intval($payload['no_of_person']);
        $adult = intval($payload['adult']);
        $child = intval($payload['child']);
        $infant = intval($payload['infant']);
        $departure_date = date('m/d/Y', strtotime($payload['departure_date']));

        if(isset($payload['return_date']) && boolval($payload['return_date']))
            $return_date = date('m/d/Y', strtotime($payload['return_date']));
        else {
            $return_date = false;
        }
        $source_city_id = intval($payload['source_city']['id']);
        $destination_city_id = intval($payload['destination_city']['id']);
        $source_city = isset($payload['source_city'])?$payload['source_city']['city']:$payload['source_city_code'];
        $destination_city = isset($payload['destination_city'])?$payload['destination_city']['city']:$payload['destination_city_code'];
        $source_city_code = $payload['source_city_code'];
        $destination_city_code = $payload['destination_city_code'];
        $direct = intval($payload['direct'])==1;
        $stopflight = intval($payload['one_stop'])==1;
        $ticket_format = isset($payload['ticket_format'])?$payload['ticket_format']:array();
        
        $company = $payload['company'];
        $current_user = $payload['current_user'];
        $default_rateplan_id = intval($payload['default_rateplan_id']);
        $airlines = $payload['airlines'];
        $supl_companyid = intval($payload['supl_companyid']);
        $supl_rateplan_id = intval($payload['supl_rateplan_id']);
        $supl_company = $payload['supl_company'];
        $journeytype = "1";

        if($trip_type === 'round') {
            $journeytype = "2";
        }

        $tickets = [];

        $data = array('EndUserIp' => '192.168.10.10', 'TokenId' => $this->tokenid, 'AdultCount' => $adult, 'ChildCount' => $child, 'InfantCount' => $infant,
                'DirectFlight' => $direct, 'OneStopFlight' => $stopflight, 'JourneyType' => $journeytype, 'PreferredAirlines' => NULL);

        $data['Segments'][0] = array(
            'Origin' => $source_city_code,
            'Destination' => $destination_city_code,
            'FlightCabinClass' => '1', /* 1 means All class | 2 means Economy */
            'PreferredDepartureTime' => date('Y-m-d H:i:s', strtotime($departure_date.' 00:00:00')),
            'PreferredArrivalTime' => date('Y-m-d H:i:s', strtotime($departure_date.' 00:00:00'))
        );

        if($trip_type ==='round' && $return_date) {
            $data['Segments'][1] = array(
                'Origin' => $destination_city_code,
                'Destination' => $source_city_code,
                'FlightCabinClass' => '1', /* 1 means All class | 2 means Economy */
                'PreferredDepartureTime' => date('Y-m-d H:i:s', strtotime($return_date.' 00:00:00')),
                'PreferredArrivalTime' => date('Y-m-d H:i:s', strtotime($return_date.' 00:00:00'))
            );
        }

        // $data['Segments'] = [array(
        //     'Origin' => $source_city_code,
        //     'Destination' => $destination_city_code,
        //     'FlightCabinClass' => '1', /* 1 means All class | 2 means Economy */
        //     'PreferredDepartureTime' => date('Y-m-d H:i:s', strtotime($departure_date.' 00:00:00')),
        //     'PreferredArrivalTime' => date('Y-m-d H:i:s', strtotime($departure_date.' 00:00:00'))
        // )];

        //$data['Sources'] = NULL;

        $urlpart = "BookingEngineService_Air/AirService.svc/rest/Search/";
        $result = $this->post($urlpart, $data, "application/json");

        if($result && is_array($result)) {
            $response = $result['response'];
            $info = isset($result['debug']) ? $result['debug'] : '';
            $err = $result['err'];
        }

		if ($err) 
		{
			log_message("debug", "ERROR : $err");
		  	return false;
		} 
		else 
		{
            if($response !== '') {
                $response = json_decode($response, TRUE);
            }
            if($response && is_array($response) && count($response)>0) {
                $response_status = intval($response['Response']['ResponseStatus']);
                $traceid = $response['Response']['TraceId'];
                if($response_status===1) {
                    //$result = $response['Response']['Results'][0];
                    $results = $response['Response']['Results'];
                }
                else {
                    //$result = [];
                    $results = [];
                }
                $lastairline = null;
                $legcode = "OB";
                for($leg=0; $leg<count($results); $leg++) {
                    if(($leg % 2) === 0) {
                        $legcode = "OB";
                    }
                    else {
                        $legcode = "IB";
                    }
                    $result = $results[$leg];
                    if($result && is_array($result) && count($result)>0) {
                        for ($i=0; $i < count($result); $i++) { 
                            $tmzticket = $result[$i];
                            if($tmzticket) {
                                log_message('debug', "$i => ".json_encode($tmzticket));
                                try {
                                    $image = 'flight.png';
                                    if($lastairline && isset($lastairline['aircode']) && $lastairline['aircode'] === trim($tmzticket['Segments'][0][0]['Airline']['AirlineCode'])) {
                                        $image = $lastairline['image'];
                                    }
                                    else {
                                        for($air=0; $air<count($airlines); $air++) {
                                            if($airlines[$air]['aircode']===trim($tmzticket['Segments'][0][0]['Airline']['AirlineCode'])) {
                                                $image=$airlines[$air]['image'];
                                                $lastairline = $airlines[$air];
                                                break;
                                            }
                                        }
                                    }

                                    $price_infant = 0;
                                    if($infant > 0) {
                                        $fare_breakdown = $tmzticket['FareBreakdown'];
                                        for ($inf=0; $inf < count($fare_breakdown) ; $inf++) { 
                                            $passenger_fare = $fare_breakdown[$inf];
                                            if($passenger_fare['PassengerType'] === 3) {
                                                //infant
                                                // $price_infant = floatval($passenger_fare['BaseFare']);
                                                $price_infant = floatval($passenger_fare['BaseFare']) + floatval($passenger_fare['Tax']);
                                                break;
                                            }
                                        }
                                    }

                                    $seats_count = $adult + $child;
                                    $pax = $seats_count;
                                    $no_of_seats = isset($tmzticket['Segments'][0][0]['NoOfSeatAvailable'])?intval($tmzticket['Segments'][0][0]['NoOfSeatAvailable']):0;
                                    $no_of_seats = $no_of_seats>0?$no_of_seats:$pax;
                                    $commision = round(floatval($tmzticket['Fare']['CommissionEarned']) + floatval($tmzticket['Fare']['IncentiveEarned']) + floatval($tmzticket['Fare']['PLBEarned']) , 0);
                                    $tds = round(floatval($tmzticket['Fare']['TdsOnCommission']) + floatval($tmzticket['Fare']['TdsOnIncentive']) + floatval($tmzticket['Fare']['TdsOnPLB']) , 0);
                                    $price = round((floatval($tmzticket['Fare']['OfferedFare']) + $tds - ($price_infant * $infant))/$pax, 0);
                                    $iid = intval(str_replace('IB', '', str_replace('OB', '', $tmzticket['ResultIndex'])));

                                    $segments = [];
                                    $lastsegmentidx = 0;
                                    for ($ii=0; $ii<count($tmzticket['Segments'][0]); $ii++) { 
                                        $seg = $tmzticket['Segments'][0][$ii];
                                        $segments[] = array(
                                            'segmentindicator' => $seg['SegmentIndicator'], 
                                            'aircode' => $seg['Airline']['AirlineCode'],
                                            'airname' => $seg['Airline']['AirlineName'],
                                            'flight_number' => $seg['Airline']['FlightNumber'],
                                            'fare_class' => $seg['Airline']['FareClass'],
                                            'operating_carrier' => $seg['Airline']['OperatingCarrier'],
                                            'duration' => intval($seg['Duration']),
                                            'departure_city' => $seg['Origin']['Airport']['AirportCode'],
                                            'departure_terminal' => $seg['Origin']['Airport']['Terminal'],
                                            'departure_datetime' => $seg['Origin']['DepTime'],
                                            'arrival_city' => $seg['Destination']['Airport']['AirportCode'],
                                            'arrival_terminal' => $seg['Destination']['Airport']['Terminal'],
                                            'arrival_datetime' => $seg['Destination']['ArrTime'],
                                            'aircraft' => $seg['Craft'],
                                            'flight_status' => $seg['FlightStatus'],
                                            'iseticketeligible' => boolval($seg['IsETicketEligible'])
                                        );

                                        $lastsegmentidx = $ii;
                                    }

                                    $llc = isset($tmzticket['IsLCC'])?boolval($tmzticket['IsLCC']):false;
                                    $gstallowed = isset($tmzticket['GSTAllowed'])?boolval($tmzticket['GSTAllowed']):false;
                                    $isholdallowedwithssr = isset($tmzticket['IsHoldAllowedWithSSR'])?boolval($tmzticket['IsHoldAllowedWithSSR']):false;
                                    $ispanrequiredatticket = isset($tmzticket['IsPanRequiredAtTicket'])?boolval($tmzticket['IsPanRequiredAtTicket']):false;
                                    $ispassportrequiredatbook = isset($tmzticket['IsPassportRequiredAtBook'])?boolval($tmzticket['IsPassportRequiredAtBook']):false;
                                    $ispassportrequiredatticket = isset($tmzticket['IsPassportRequiredAtTicket'])?boolval($tmzticket['IsPassportRequiredAtTicket']):false;
                                    $iscouponappilcable = isset($tmzticket['IsCouponAppilcable'])?boolval($tmzticket['IsCouponAppilcable']):false;
                                    $isgstmandatory = isset($tmzticket['IsGSTMandatory'])?boolval($tmzticket['IsGSTMandatory']):false;
                                    $remarks = (isset($tmzticket['AirlineRemark']) && $tmzticket['AirlineRemark'] !== '') ? $tmzticket['AirlineRemark'] : 'Live inventory from API';
                                    $remarks = $remarks." ".($ispanrequiredatticket?'| PAN Mandatory ':'');
                                    $remarks = $remarks." ".($ispassportrequiredatbook?'| Passport Mandatory ':'');
                                    $remarks = $remarks." ".($isgstmandatory?'| GST# Mandatory ':'');

                                    $ticket = array(
                                        'direction' => $legcode,
                                        'id' => (200100000 + $iid),
                                        'uid' => intval($company['primary_user_id']),
                                        'remarks' => $remarks,
                                        'source' => $source_city_id,
                                        'destination' => $destination_city_id,
                                        'source_code' => $tmzticket['Segments'][0][0]['Origin']['Airport']['AirportCode'],
                                        'destination_code' => $tmzticket['Segments'][0][$lastsegmentidx]['Destination']['Airport']['AirportCode'],
                                        'source_city' => $source_city, //trim($tmzticket['Segments'][0][0]['Origin']['Airport']['AirportName']),
                                        'destination_city' => $destination_city, //trim($tmzticket['Segments'][0][0]['Destination']['Airport']['AirportName']),
                                        'departure_date_time' => date('Y-m-d H:i:s', strtotime($tmzticket['Segments'][0][0]['Origin']['DepTime'])),
                                        'arrival_date_time' => date('Y-m-d H:i:s', strtotime($tmzticket['Segments'][0][$lastsegmentidx]['Destination']['ArrTime'])),
                                        'flight_no' => trim($tmzticket['Segments'][0][0]['Airline']['AirlineCode']).' '.trim($tmzticket['Segments'][0][0]['Airline']['FlightNumber']),
                                        'terminal' => $tmzticket['Segments'][0][0]['Origin']['Airport']['Terminal'],
                                        'departure_terminal' => $tmzticket['Segments'][0][0]['Origin']['Airport']['Terminal'],
                                        'arrival_terminal' => $tmzticket['Segments'][0][$lastsegmentidx]['Destination']['Airport']['Terminal'],
                                        'no_of_person' => $no_of_seats,
                                        'adult' => $adult,
                                        'child' => $child,
                                        'infant' => $infant,
                                        'seatsavailable' => $no_of_seats,
                                        'tag' => $tmzticket['FareRules'][0]['FareRuleDetail'],
                                        'data_collected_from' => 'tbo_api',
                                        'sale_type' => 'api', /*This will be live only but for now making it API //live */
                                        'library_name' => 'api_tbo',
                                        'config' => $this->config,
                                        'refundable' => $tmzticket['IsRefundable']?'Y':'N',
                                        'total' => $price, //floatval($tmzticket['Fare']['OfferedFare']),
                                        'airline_name' => $lastairline['display_name'],
                                        'airline' => $lastairline['id'],
                                        'aircode' => $tmzticket['Segments'][0][0]['Airline']['AirlineCode'],
                                        'ticket_no' => 'TBO-TKT-'.$iid,
                                        'price' => $price, //floatval($tmzticket['Fare']['OfferedFare']),
                                        'cost_price' => $price, //floatval($tmzticket['Fare']['OfferedFare']),
                                        'infant_price' => $price_infant, //floatval($tmzticket['Fare']['OfferedFare']),
                                        'approved' => 1,
                                        'class' => 'ECONOMY',
                                        'no_of_stops' => $lastsegmentidx,
                                        'companyid' => intval($supl_company['id']), //$company['id'], This has to be supplier company id. Means who own the ticket
                                        'companyname' => $supl_company['display_name'],
                                        'user_id' => intval($supl_company['primary_user_id']), // -1,
                                        'admin_markup' => 0,
                                        'rate_plan_id' => $supl_rateplan_id,
                                        'supplierid' => $supl_companyid,
                                        'sellerid' => $company['id'],
                                        'seller_rateplan_id' => $default_rateplan_id,
                                        'adult_total' => 0.00, //if we make it non-zero then search showing wrong date.
                                        'image' => $image,
                                        'ResultIndex' => $tmzticket['ResultIndex'],
                                        // 'SearchIndex' => intval($tmzticket['SearchIndex']),
                                        // 'SuppSource' => intval($tmzticket['SuppSource']),
                                        'IsLCC' => isset($tmzticket['IsLCC'])?boolval($tmzticket['IsLCC']):false,
                                        'GSTAllowed' => isset($tmzticket['GSTAllowed'])?boolval($tmzticket['GSTAllowed']):false,
                                        'IsHoldAllowedWithSSR' => isset($tmzticket['IsHoldAllowedWithSSR'])?boolval($tmzticket['IsHoldAllowedWithSSR']):false,
                                        'IsPanRequiredAtTicket' => isset($tmzticket['IsPanRequiredAtTicket'])?boolval($tmzticket['IsPanRequiredAtTicket']):false,
                                        'IsPassportRequiredAtBook' => isset($tmzticket['IsPassportRequiredAtBook'])?boolval($tmzticket['IsPassportRequiredAtBook']):false,
                                        'IsPassportRequiredAtTicket' => isset($tmzticket['IsPassportRequiredAtTicket'])?boolval($tmzticket['IsPassportRequiredAtTicket']):false,
                                        'IsCouponAppilcable' => isset($tmzticket['IsCouponAppilcable'])?boolval($tmzticket['IsCouponAppilcable']):false,
                                        'IsGSTMandatory' => isset($tmzticket['IsGSTMandatory'])?boolval($tmzticket['IsGSTMandatory']):false,
                                        'SuppTokenId' => $this->tokenid,
                                        'SuppTraceId' => $traceid,
                                        'tokenid' => $this->tokenid,
                                        'clientid' => $this->member['MemberId'],
                                        'agencytype' => $this->member['AgencyId'],
                                        'segments' => $segments
                                    );
                                    $this->tokenid = $ticket['tokenid'];
                                    $this->clientid = $ticket['clientid'];
                                    $this->agencytype = $ticket['agencytype'];
                            

                                    $ticket = array_merge($ticket_format, $ticket);

                                    array_push($tickets, $ticket);
                                }
                                catch(Exception $ex) {
                                    log_message('error', $ex);
                                }
                            }
                        }
                    }
                }
            }
            
            log_message("debug", "Response (Ticket Search) : ".json_encode($response, JSON_UNESCAPED_SLASHES));
            log_message("debug", "Searched Tickets : ".json_encode($tickets, JSON_UNESCAPED_SLASHES));

            return $tickets;
        }
    }

    public function check_balance() {
        $data = array('EndUserIp' => '192.168.1.1', 'ClientId' => 'ApiIntegrationNew', 'TokenAgencyId' => "$this->agencytype", 'TokenMemberId' => "$this->clientid", 'TokenId' => $this->tokenid);
        $err = '';
        $response = '';
        $info = '';
        $urlpart = "SharedServices/SharedData.svc/rest/GetAgencyBalance";
        $result = $this->post($urlpart, $data, "application/json");

        if($result && is_array($result)) {
            $response = json_decode($result['response'], TRUE);
            $info = isset($result['debug']) ? $result['debug'] : '';
            $err = $result['err'];
        }
        
		if ($err) 
		{
			log_message("debug", "ERROR : $err");
		  	return false;
		} 
		else 
		{
            log_message("debug", "Response | ".json_encode($response));
            // if($response !== '') {
            //     $response = json_decode($response, TRUE);
            // }

            if($response) {
                if(isset($response['Error']) && intval($response['Error']['ErrorCode'])>0) {
                    log_message('error', "API returned Error => ".$response['Error']['ErrorMessage']);
                }
                else {
                    //CashBalance | CreditBalance

                    $this->cash_balance = isset($response['CashBalance']) ? floatval($response['CashBalance']) : 0.00;
                    $this->ticketing_balance = isset($response['CreditBalance']) ? floatval($response['CreditBalance']) : 0.00;

                    return array('cash_balance' => $this->cash_balance, 'ticketing_balance' => $this->ticketing_balance);
                }
            }
            else {
                log_message('error', "NO API Response");
            }
        }

        return false;
    }

    public function getBooking($bookingid) {

        if($bookingid && intval($bookingid)<=0) return null;

        $data = array('TokenID' => $this->tokenid, 'BookingId' => $bookingid, 'TMagId' => $this->clientid);

        $err = '';
        $response = '';
        $info = '';
        $urlpart = "Rest.svc/v2/GetFlightBooking";
        $result = $this->post($urlpart, $data, "application/json");
        if($result && is_array($result)) {
            $response = json_decode($result['response'], TRUE);
            $info = isset($result['info']) ? $result['info'] : '';
            $err = $result['err'];
        }
        
		if ($err) 
		{
			log_message("debug", "ERROR : $err");
		  	return false;
		} 
		else 
		{
            if($response && isset($response['Error']) && intval($response['Error']['ErrorCode'])>0) {
                log_message("error", "ERROR : GetBookingDetails : ".$response['Error']['ErrorMessage']);
                return false;
            }

            if($response && isset($response['Response'])) {
                $response = $response['Response'];
                if(isset($response['Error']) && intval($response['Error']['ErrorCode'])>0) {
                    log_message('error', "API returned Error => ".$response['Error']['ErrorMessage']);
                }
                else {
                    $flight_itenary = isset($response['FlightItinerary']) ? $response['FlightItinerary'] : [];
                    $pageid = isset($response['PageID']) ? $response['PageID'] : '';

                    return array('flight_itinery' => $flight_itenary, 'pageid' => $pageid);
                }
            }
            else {
                log_message('error', "NO API Response");
            }
        }

        return false;
    }

    public function fare_quote($ticket) {
        $resultindex = $ticket['ResultIndex'];
        $suppsource = 0;
        $supptraceid = $ticket['SuppTraceId'];
        $supptokenid = $ticket['SuppTokenId'];
        $searchindex = $ticket['ResultIndex'];
        $tokenid = $ticket['tokenid'];

        $pax = (intval($ticket['adult']) + intval($ticket['child']));
        $infant_pax = intval($ticket['infant']);
        $infant_price = isset($ticket['infant_price']) ? floatval($ticket['infant_price']) : 0;

        $whl_charges = (floatval($ticket['whl_markup']) + floatval($ticket['whl_srvchg']) + floatval($ticket['whl_igst'])) * $pax;
        $spl_charges = (floatval($ticket['spl_markup']) + floatval($ticket['spl_srvchg']) + floatval($ticket['spl_igst'])) * $pax;

        //$data = array('EndUserIp' => '121.101.14.10', 'TokenId' => $tokenid, 'ResultIndex' => $resultindex, 'SuppSource' => $suppsource, 'SuppTraceId' => $supptraceid, 'SuppTokenId' => $supptokenid, 'SearchIndex' => $searchindex);
        $data = array('EndUserIp' => '192.168.1.1', 'TokenId' => $tokenid, 'ResultIndex' => $resultindex, 'TraceId' => $supptraceid);

        log_message('debug', 'Farequote => '.json_encode($data, JSON_UNESCAPED_SLASHES));
        $err = '';
        $response = '';
        $info = '';
        $urlpart = "BookingEngineService_Air/AirService.svc/rest/FareQuote/";
        $result = $this->post($urlpart, $data, "application/json");
        if($result && is_array($result)) {
            $response = json_decode($result['response'], TRUE);
            $info = isset($result['debug']) ? $result['debug'] : '';
            $err = $result['err'];
        }
        
		if ($err) 
		{
			log_message("debug", "ERROR : $err");
		  	return false;
		} 
		else 
		{
            if($response && isset($response['Response'])) {
                $response = $response['Response'];
                if(isset($response['Error']) && intval($response['Error']['ErrorCode'])>0) {
                    log_message('error', "API returned Error => ".$response['Error']['ErrorMessage']);
                }
                else {
                    // Published Fare : BaseFare + Tax + OtherCharges + ServiceFee + AdditionalTxnFeepub + AirlineTransFee (i.e. : 7054 = 3596+2966+492+0+0+0)
                    // Offered Fare : (Published Fare-CommissionEarned-PLBEarned) (i.e. : 6822.12 = 7054 - 85 -88)
                    // Net Payable (Invoice Amount) : Published Fare – (CommissionEarned+ IncentiveEarned+ PLBEarned + AdditionalTxnFee) + (TdsOnCommission + TdsOnIncentive + TdsOnPLB) + GST(IGSTAmount+CGSTAmount+SGSTAmount+CessAmount)
                    // (6,914.02 = 7054 - (85+59.7+88.03+0) + (33.66 +23.88+35.21) + ( GST not applicable in this case))

                    $result = $response['Results'];

                    log_message('debug', 'Fare Quote Result ->'.json_encode($result));

                    $isprice_changed = boolval($response['IsPriceChanged']) ? boolval($response['IsPriceChanged']) : false;
                    $traceid = isset($response['TraceId']) ? $response['TraceId'] : '';
                    $result_index = isset($result['ResultIndex']) ? $result['ResultIndex'] : '';
                    //$fqindex = isset($response['FQIndex']) ? intval($response['FQIndex']) : 0;
                    $islcc = isset($result['IsLCC']) ? boolval($result['IsLCC']) : false;
                    $isrefundable = isset($result['IsRefundable']) ? boolval($result['IsRefundable']) : false;
                    $gstallowed = isset($result['GSTAllowed']) ? boolval($result['GSTAllowed']) : false;
                    $airlineremark = (isset($result['AirlineRemark']) && $result['AirlineRemark'] !== '') ? $result['AirlineRemark'] : 'Live inventory from API'; // isset($result['AirlineRemark']) ? $result['AirlineRemark'] : '';
                    $offeredfare = isset($result['Fare']['OfferedFare']) ? floatval($result['Fare']['OfferedFare']) : 0;
                    $this->fare = isset($result['Fare']) ? $result['Fare'] : NULL;
                    $this->fare_breakdown = isset($result['FareBreakdown']) ? $result['FareBreakdown'] : NULL;
                    $this->segments = isset($result['Segments']) ? $result['Segments'] : NULL;
                    $this->fare_rules = isset($result['FareRules']) ? $result['FareRules'] : NULL;

                    $fare = [];
                    $passengers_fare = [];
                    $ob_flight_segments = [];
                    if($this->fare) {
                        $tds_commision = isset($this->fare['TdsOnCommission']) ? floatval($this->fare['TdsOnCommission']) : 0;
                        $tds_plb = isset($this->fare['TdsOnPLB']) ? floatval($this->fare['TdsOnPLB']) : 0;
                        $tds_incentive = isset($this->fare['TdsOnIncentive']) ? floatval($this->fare['TdsOnIncentive']) : 0;

                        $tds = round($tds_commision + $tds_plb + $tds_incentive, 0);
                        $offeredfare = round($offeredfare, 0);

                        $total = round(($offeredfare + $tds - ($infant_price * $infant_pax)) / $pax, 0);
                        $price = round(($offeredfare + $tds + $whl_charges + $spl_charges - ($infant_price * $infant_pax)) / $pax, 0);

                        $ticket['price'] = $price;
                        $ticket['total'] = $total;

                        $fare = array(
                            'currency' => isset($this->fare['Currency']) ? $this->fare['Currency'] : '',
                            'basefare' => isset($this->fare['BaseFare']) ? floatval($this->fare['BaseFare']) : 0,
                            'tax' => isset($this->fare['Tax']) ? floatval($this->fare['Tax']) : 0,
                            'yqtax' => isset($this->fare['YQTax']) ? floatval($this->fare['YQTax']) : 0,
                            'othercharges' => isset($this->fare['OtherCharges']) ? floatval($this->fare['OtherCharges']) : 0,
                            'additionaltxnfeepub' => isset($this->fare['AdditionalTxnFeePub']) ? floatval($this->fare['AdditionalTxnFeePub']) : 0,
                            'additionaltxnfeeofrd' => isset($this->fare['AdditionalTxnFeeOfrd']) ? floatval($this->fare['AdditionalTxnFeeOfrd']) : 0,
                            'discount' => isset($this->fare['Discount']) ? floatval($this->fare['Discount']) : 0,
                            'publishedfare' => isset($this->fare['PublishedFare']) ? floatval($this->fare['PublishedFare']) : 0,
                            'offeredfare' => $offeredfare,
                            'commissionearned' => isset($this->fare['CommissionEarned']) ? floatval($this->fare['CommissionEarned']) : 0,
                            'plbearned' => isset($this->fare['PLBEarned']) ? floatval($this->fare['PLBEarned']) : 0,
                            'incentiveearned' => isset($this->fare['IncentiveEarned']) ? floatval($this->fare['IncentiveEarned']) : 0,
                            'total_commission' => 0,
                            'tdsoncommission' => isset($this->fare['TdsOnCommission']) ? floatval($this->fare['TdsOnCommission']) : 0,
                            'tdsonplb' => isset($this->fare['TdsOnPLB']) ? floatval($this->fare['TdsOnPLB']) : 0,
                            'tdsonincentive' => isset($this->fare['TdsOnIncentive']) ? floatval($this->fare['TdsOnIncentive']) : 0,
                            'total_tds' => $tds,
                            'total_spl_margin' => $spl_charges,
                            'total_whl_margin' => $whl_charges,
                            'servicefee' => isset($this->fare['ServiceFee']) ? floatval($this->fare['ServiceFee']) : 0,
                            'totalbaggagecharges' => isset($this->fare['TotalBaggageCharges']) ? floatval($this->fare['TotalBaggageCharges']) : 0,
                            'totalmealcharges' => isset($this->fare['TotalMealCharges']) ? floatval($this->fare['TotalMealCharges']) : 0,
                            'totalseatcharges' => isset($this->fare['TotalSeatCharges']) ? floatval($this->fare['TotalSeatCharges']) : 0,
                            'totalspecialservicecharges' => isset($this->fare['TotalSpecialServiceCharges']) ? floatval($this->fare['TotalSpecialServiceCharges']) : 0,
                            'airlineremark' => $airlineremark,
                            'gstallowed' => $gstallowed,
                            'isrefundable' => $isrefundable,
                            'islcc' => $islcc,
                            'gstallowed' => isset($result['gstallowed'])?boolval($result['gstallowed']):false,
                            'isholdallowedwithssr' => isset($result['isholdallowedwithssr'])?boolval($result['isholdallowedwithssr']):false,
                            'ispanrequiredatticket' => isset($result['ispanrequiredatticket'])?boolval($result['ispanrequiredatticket']):false,
                            'ispassportrequiredatbook' => isset($result['ispassportrequiredatbook'])?boolval($result['ispassportrequiredatbook']):false,
                            'ispassportrequiredatticket' => isset($result['ispassportrequiredatticket'])?boolval($result['ispassportrequiredatticket']):false,
                            'iscouponappilcable' => isset($result['iscouponappilcable'])?boolval($result['iscouponappilcable']):false,
                            'isgstmandatory' => isset($result['isgstmandatory'])?boolval($result['isgstmandatory']):false,
                            'isprice_changed' => $isprice_changed,
                            'traceid' => $traceid,
                        );
                        $fare['total_spl_margin'] = floatval($ticket['spl_markup']) + floatval($ticket['spl_srvchg']) + floatval($ticket['spl_igst']) - floatval($ticket['spl_disc']);
                        $fare['total_whl_margin'] = floatval($ticket['whl_markup']) + floatval($ticket['whl_srvchg']) + floatval($ticket['whl_igst']) - floatval($ticket['whl_disc']);
                        $fare['total_tds'] = $tds;
                        $fare['total_commission'] = $fare['commissionearned'] + $fare['plbearned'] + $fare['incentiveearned'];
                        $fare['infant_price'] = 0;
                    }

                    if($this->fare_breakdown && is_array($this->fare_breakdown) && count($this->fare_breakdown) > 0) {    
                        $infant_price = 0;
                        foreach ($this->fare_breakdown as $passenger_fare) {
                            $passengers_fare[] = array(
                                'Currency' => $passenger_fare['Currency'],
                                'PassengerType' => $passenger_fare['PassengerType'], /* 1 = Adult | 2 = Child | 3 = Infant */
                                'PassengerCount' => intval($passenger_fare['PassengerCount']),
                                'BaseFare' => floatval($passenger_fare['BaseFare']),
                                'Tax' => floatval($passenger_fare['Tax']),
                                'YQTax' => floatval($passenger_fare['YQTax']),
                                'AdditionalTxnFeeOfrd' => floatval($passenger_fare['AdditionalTxnFeeOfrd']),
                                'AdditionalTxnFeePub' => floatval($passenger_fare['AdditionalTxnFeePub']),
                                'PGCharge' => floatval($passenger_fare['PGCharge'])
                            );

                            if($passenger_fare['PassengerType'] === 3) {
                                //infant
                                // $infant_price = floatval($passenger_fare['BaseFare']);
                                $infant_price = floatval($passenger_fare['BaseFare']) + floatval($passenger_fare['Tax']);
                            }
                        }
                        $fare['infant_price'] = $infant_price;
                    }

                    if($this->segments && is_array($this->segments) && count($this->segments) > 0 && $this->segments[0] && is_array($this->segments[0]) && count($this->segments[0]) > 0) {    
                        $this->segments = $this->segments[0];
                        foreach ($this->segments as $segment) {
                            $ob_flight_segments[] = array(
                                'Baggage' => $segment['Baggage'],
                                'CabinBaggage' => $segment['CabinBaggage'],
                                'CabinClass' => $segment['CabinClass'],
                                'SegmentIndicator' => $segment['SegmentIndicator'],
                                'Craft' => $segment['Craft'],
                                'Duration' => $segment['Duration'],
                                'IsETicketEligible' => $segment['IsETicketEligible'],
                                'FlightStatus' => $segment['FlightStatus'],
                                'AirlineCode' => $segment['Airline']['AirlineCode'],
                                'AirlineName' => $segment['Airline']['AirlineName'],
                                'FareClass' => $segment['Airline']['FareClass'],
                                'FlightNumber' => $segment['Airline']['FlightNumber'],
                                'OperatingCarrier' => $segment['Airline']['OperatingCarrier'],
                                'DepTime' => $segment['Origin']['DepTime'],
                                'ArrTime' => $segment['Destination']['ArrTime'],
                                'Dep_AirportCode' => $segment['Origin']['Airport']['AirportCode'],
                                'Dep_AirportName' => $segment['Origin']['Airport']['AirportName'],
                                'Dep_Terminal' => $segment['Origin']['Airport']['Terminal'],
                                'Dep_CityCode' => $segment['Origin']['Airport']['CityCode'],
                                'Dep_CityName' => $segment['Origin']['Airport']['CityName'],
                                'Dep_CountryCode' => $segment['Origin']['Airport']['CountryCode'],
                                'Dep_CountryName' => $segment['Origin']['Airport']['CountryName'],
                                'Arr_AirportCode' => $segment['Destination']['Airport']['AirportCode'],
                                'Arr_AirportName' => $segment['Destination']['Airport']['AirportName'],
                                'Arr_Terminal' => $segment['Destination']['Airport']['Terminal'],
                                'Arr_CityCode' => $segment['Destination']['Airport']['CityCode'],
                                'Arr_CityName' => $segment['Destination']['Airport']['CityName'],
                                'Arr_CountryCode' => $segment['Destination']['Airport']['CountryCode'],
                                'Arr_CountryName' => $segment['Destination']['Airport']['CountryName'],
                            );
                        }
                    }

                    $fare_quote = array('isprice_changed' => $isprice_changed, 'offeredfare' => $offeredfare, 'traceid' => $traceid, 'fqindex' => $result_index, 'fare' => $fare, 'passengers_fare' => $passengers_fare, 'ob_flight_segments' => $ob_flight_segments, 'price' => $price, 'base_price' => $total);

                    log_message('debug', 'Fare Quote : '.json_encode($fare_quote));
                    return $fare_quote;
                }
            }
            else {
                log_message('error', "NO API Response");
            }
        }

        return false;
    }

    public function book($payload) {
        if(!$payload || !is_array($payload)) return NULL;
        $company = $payload['company'];
        $current_user = $payload['current_user'];
        $ticket = $payload['ticket'];
        $customers = $payload['customers'];
        $posteddata = $payload['posteddata'];
        $adminuser = $payload['admin_user'];

        $this->tokenid = $ticket['tokenid'];
        $this->clientid = $ticket['clientid'];
        $this->agencytype = $ticket['agencytype'];

        $balance = $this->check_balance();
        log_message('debug', 'Balance Check : '.json_encode($balance));

        $fare_quote = $this->fare_quote($ticket);
        log_message('debug', 'Fare Quote : '.json_encode($fare_quote));
        
        log_message('debug', "Posted value => ".json_encode($payload));
        //We need to check balance also
        if($balance && $fare_quote && $adminuser) {
            //if(!boolval($fare_quote['isprice_changed'])) {
            if(true) {
                $traceid = $fare_quote['traceid'];
                $fqindex = $fare_quote['fqindex'];
                $passengers = [];

                for ($i=0; $i < count($customers); $i++) { 
                    $customer = $customers[$i];
                    $paxtype_code = $customer['passenger_type'];

                    $paxtype = ($paxtype_code === 'Adult' ? 1 : ($paxtype_code === 'Child' ? 2 : ($paxtype_code === 'Infant' ? 3 : -1)));
                    // ((($customer['passenger_type'] === 'Adult') ? 1 : (($customer['passenger_type'] === 'Child') ? 2 : ($customer['passenger_type'] === 'Infant') ? 3 : -1 )));

                    $dob = (intval($customer['passenger_age'])>0 ? date("Y-m-d", strtotime("-".intval($customer['passenger_age'])." years")) : date("Y-m-d", strtotime("-20 years")));
                    if($paxtype === 2) {
                        $dob = (intval($customer['passenger_age'])>0 ? date("Y-m-d", strtotime("-".intval($customer['passenger_age'])." years")) : date("Y-m-d", strtotime("-11 years")));
                    }
                    // (intval($customer['passenger_age'])>0 ? date("Y-m-d", strtotime("-".intval($customer['passenger_age'])." years")) : date("Y-m-d", strtotime("-20 years")))
                    //Please enter valid title for passenger 1. Title Can be from the following values : Mr ,Mstr ,Mrs ,Ms ,Miss ,Master ,DR ,CHD ,MST ,PROF ,Inf
                    $passender = array(
                        'PaxId' => "$i",
                        //'Title' => $this->get_customer_title($i, $customer['prefix']),
                        'Title' => $this->get_customer_title($i, $customer['passenger_title']),
                        'FirstName' => $customer['passenger_first_name'],
                        'LastName' => $customer['passenger_last_name'],
                        'PaxType' => $paxtype,
                        'DateOfBirth' => $dob,
                        'Gender' => ($customer['passenger_title']==='Mr.'?'1':'2'),
                        'PassportNo' => null,
                        'PassportExpiry' => null,
                        'AddressLine1' => '-',
                        'AddressLine2' => '-',
                        'City' => '',
                        'CountryCode' => 'IN',
                        'CountryName' => 'India',
                        'Nationality' => 'IN',
                        'Email' => $adminuser['email'],
                        'ContactNo' => $adminuser['mobile'],
                        'IsLeadPax' => true,
                        'FFAirlineCode' => null,
                        'FFNumber' => '',
                        'Fare' => array('BaseFare' => 0, 'Tax' => 0, 'TransactionFee' => 0, 'YQTax' => 0, 'AdditionalTxnFeeOfrd' => 0, 'AdditionalTxnFeePub' => 0, 'ServiceFee' => 0),
                        'Baggage' => [],
                        'MealDynamic' => [],
                        'SeatDynamic' => [],
                        'GSTCompanyAddress' => '',
                        'GSTCompanyContactNumber' => '',
                        'GSTCompanyName' => '',
                        'GSTNumber' => '',
                        'GSTCompanyEmail' => ''
                    );

                    array_push($passengers, $passender);
                }

                $data = array('EndUserIp' => '192.168.11.58', 'TraceId' => $traceid, 'ResultIndex' => $fqindex, 'TokenId' => $this->tokenid, 'Passengers' => $passengers, 'AgentReferenceNo' => 'customer_tracking_no', 'PreferredCurrency' => null);
                log_message('debug', 'Ticketing API POST => '.json_encode($data, JSON_UNESCAPED_SLASHES));

                $lcc = isset($fare_quote['fare']['islcc']) ? boolval($fare_quote['fare']['islcc']) : false;
                if(isset($fare_quote['fare']) && isset($fare_quote['fare']['islcc']) && boolval($fare_quote['fare']['islcc'])) {
                    $booking_response = $this->book_lcc($data);
                }
                else {
                    $booking_response = $this->book_nonlcc($data);
                    $data['BookingId'] = $booking_response['Result']['bookingid'];
                    $data['PNR'] = $booking_response['Result']['pnrlist'];
                    log_message('debug', "LCC => ".($lcc?'true':'false')." | Book NON LCC Ticket : ".json_encode($booking_response));
                    if(isset($data['BookingId']) && intval($data['BookingId'])>0) {
                        log_message('debug', "LCC => ".($lcc?'true':'false')." | Calling final booking for NON LCC");
                        $booking_response = $this->book_lcc($data);
                    }
                }

                log_message('debug', "LCC => ".($lcc?'true':'false')." | Final Booking : ".json_encode($booking_response));

                return $booking_response;
            }
            else {
                log_message('error', 'Ticket price changed at final stage. I think its already more than 20 mins waiting.');
            }
        }

        return false;
    }

    private function get_customer_title($idx=0 , $customer_prefix='Mr.') {
        $title = 'Mr';
        switch ($customer_prefix) {
            case 'Mr.':
                $title = 'Mr';
                break;
            case 'Mrs.':
                $title = 'Mrs';
                break;
            case 'Miss':
                $title = 'Miss';
                break;
            case 'Mr.':
                $title = 'Mr';
                break;
            default:
                $title = 'Mr';
                break;
        }

        return $title;
    }

    private function book_lcc($data) {
        $err = NULL;
        $err_code = 0;
        $book_result = [];
        $response = '';
        $info = '';
        $urlpart = "BookingEngineService_Air/AirService.svc/rest/Ticket/";
        $result = $this->post($urlpart, $data, "application/json");
        if($result && is_array($result)) {
            $response = json_decode($result['response'], TRUE);
            $response = $response['Response'];
            $info = isset($result['debug']) ? $result['debug'] : '';
            if(isset($result['err']) && $result['err']!=='') {
                $err = array('ErrorCode' => 999, 'ErrorMessage' => $result['err']);
            }

            log_message('debug', 'Ticketing API POST RESPONSE => '.json_encode($result, JSON_UNESCAPED_SLASHES));
        }
        
        if ($err) 
        {
            log_message("debug", "ERROR : ".json_encode($err));
            $book_result=array('Error' => $err, 'Status' => false);
        } 
        else 
        {
            if($response) {
                if(isset($response['Error']) && intval($response['Error']['ErrorCode'])>0) {
                    log_message('error', "API returned Error => ".$response['Error']['ErrorMessage']);
                    $err = $response['Error']['ErrorMessage'];
                    $err = array('ErrorCode' => intval($response['Error']['ErrorCode']), 'ErrorMessage' => $err);

                    $book_result=array('Error' => $err, 'Status' => false);
                }
                else {
                    $response = $response['Response'];
                    $bookingid = intval($response['BookingId']);
                    $pnrlist = $response['PNR'];
                    $ssrdenied = isset($response['SSRDenied']) ? boolval($response['SSRDenied']) : false;
                    $ssrmessage = isset($response['SSRMessage']) ? boolval($response['SSRMessage']) : false;
                    $status = (isset($response['Status']) && intval($response['Status']) === 1);
                    $ispricechanged = isset($response['IsPriceChanged']) ? boolval($response['IsPriceChanged']) : false;
                    $istimechanged = isset($response['IsTimeChanged']) ? boolval($response['IsTimeChanged']) : false;
                    $ticletstatus = (isset($response['TicketStatus']) && intval($response['TicketStatus']) === 1);
                    $flight_itinerary = isset($response['FlightItinerary']) ? $response['FlightItinerary'] : [];
                    if($flight_itinerary && is_array($flight_itinerary) && count($flight_itinerary)>0) {
                        $flight_itinerary['pnr'] = $flight_itinerary['PNR'];
                    }                    

                    $result = array('bookingid' => $bookingid, 'pnrlist' => $pnrlist, 'pageid' => '', 'itinerary' => $flight_itinerary, 'ssrdenied' => $ssrdenied, 'ssrmessage' => $ssrmessage, 
                                    'status' => $status, 'ispricechanged' => $ispricechanged, 'istimechanged' => $istimechanged, 'ticletstatus' => $ticletstatus);
                    $book_result=array('Error' => array('Error' => 0, 'ErrorMessage' => ''), 'Status' => true, 'Result' => $result);
                    $this->book_result = $result;
                }
            }
            else {
                log_message('error', "NO API Response");
                $book_result=array('Error' => array('ErrorCode' => 999, 'ErrorMessage' => 'NO API Response'), 'Status' => false);
            }
        }

        return $book_result;
    }

    private function book_nonlcc($data) {
        $err = '';
        $err_code = 0;
        $book_result = [];
        $response = '';
        $info = '';
        $urlpart = "BookingEngineService_Air/AirService.svc/rest/Book/";
        $result = $this->post($urlpart, $data, "application/json");
        if($result && is_array($result)) {
            $response = json_decode($result['response'], TRUE);
            $response = $response['Response'];
            $info = isset($result['debug']) ? $result['debug'] : '';
            if(isset($result['err']) && $result['err']!=='') {
                $err = array('ErrorCode' => 999, 'ErrorMessage' => $result['err']);
            }

            log_message('debug', 'Ticketing API POST RESPONSE => '.json_encode($result, JSON_UNESCAPED_SLASHES));
        }
        
        if ($err) 
        {
            log_message("debug", "ERROR : ".json_encode($err));
            $book_result=array('Error' => $err, 'Status' => false);
        } 
        else 
        {
            if($response) {
                if(isset($response['Error']) && intval($response['Error']['ErrorCode'])>0) {
                    log_message('error', "API returned Error => ".$response['Error']['ErrorMessage']);
                    $err = $response['Error']['ErrorMessage'];
                    $err = array('ErrorCode' => intval($response['Error']['ErrorCode']), 'ErrorMessage' => $err);

                    $book_result=array('Error' => $err, 'Status' => false);
                }
                else {
                    $response = $response['Response'];
                    $bookingid = intval($response['BookingId']);
                    $pnrlist = $response['PNR'];
                    $ssrdenied = isset($response['SSRDenied']) ? boolval($response['SSRDenied']) : false;
                    $ssrmessage = isset($response['SSRMessage']) ? boolval($response['SSRMessage']) : false;
                    $status = (isset($response['Status']) && intval($response['Status']) === 1);
                    $ispricechanged = isset($response['IsPriceChanged']) ? boolval($response['IsPriceChanged']) : false;
                    $istimechanged = isset($response['IsTimeChanged']) ? boolval($response['IsTimeChanged']) : false;
                    $ticletstatus = (isset($response['TicketStatus']) && intval($response['TicketStatus']) === 1);
                    $flight_itinerary = isset($response['FlightItinerary']) ? $response['FlightItinerary'] : [];
                    
                    if($flight_itinerary && is_array($flight_itinerary) && count($flight_itinerary)>0) {
                        $flight_itinerary['pnr'] = $flight_itinerary['PNR'];
                    }

                    $result = array('bookingid' => $bookingid, 'pnrlist' => $pnrlist, 'pageid' => '', 'itinerary' => $flight_itinerary, 'ssrdenied' => $ssrdenied, 'ssrmessage' => $ssrmessage, 
                                    'status' => $status, 'ispricechanged' => $ispricechanged, 'istimechanged' => $istimechanged, 'ticletstatus' => $ticletstatus);
                    $book_result=array('Error' => array('Error' => 0, 'ErrorMessage' => ''), 'Status' => true, 'Result' => $result);
                    $this->book_result = $result;
                }
            }
            else {
                log_message('error', "NO API Response");
                $book_result=array('Error' => array('ErrorCode' => 999, 'ErrorMessage' => 'NO API Response'), 'Status' => false);
            }
        }

        return $book_result;
    }

    public function get_booking_details($payload) {
        $booking_details = $payload['booking_details'];

        $booking_details = (isset($booking_details['Result']) && isset($booking_details['Result']['itinerary'])) ? $booking_details['Result']['itinerary'] : NULL;

        return $booking_details;
    }

    public function get_booking_details_old($payload) {
        if(!$payload || !is_array($payload)) return NULL;

        $company = $payload['company'];
        $current_user = $payload['current_user'];
        $ticket = $payload['ticket'];
        $customers = $payload['customers'];
        $posteddata = $payload['posteddata'];
        $adminuser = $payload['admin_user'];
        $bookingdetails = $payload['booking_details'];

        $this->tokenid = $ticket['tokenid'];
        $this->clientid = $ticket['clientid'];
        $this->agencytype = $ticket['agencytype'];
        $bookingid = 0;
        if ($payload && isset($payload['booking_details']) && is_array($payload['booking_details'])) {
            $bookingid = isset($payload['booking_details']['bookingid'])? intval($payload['booking_details']['bookingid']) : 0;
        }

        $data = array('BookingId' => $bookingid, 'TokenId' => $this->tokenid, 'TMagId' => $this->clientid);

        log_message('debug', 'Get Booking Details API POST => '.json_encode($data, JSON_UNESCAPED_SLASHES));
        
        $err = '';
        $response = '';
        $info = '';
        $urlpart = "Rest.svc/V2/GetFlightBooking";
        $result = $this->post($urlpart, $data, "application/json");
        if($result && is_array($result)) {
            $response = json_decode($result['response'], TRUE);
            $info = isset($result['info']) ? $result['info'] : '';
            $err = $result['err'];
        }
        
        if ($err) 
        {
            log_message("debug", "ERROR : $err");
              return false;
        } 
        else 
        {
            if($response) {
                if(isset($response['Error']) && intval($response['Error']['ErrorCode'])>0) {
                    log_message('error', "API returned Error => ".$response['Error']['ErrorMessage']);
                }
                else {
                    $response = isset($response['Response'])?$response['Response'][0]:false;
                    if($response && isset($response['FlightItinerary']) && is_array($response['FlightItinerary']) && count($response['FlightItinerary'])>0) {
                        $itinerary = $response['FlightItinerary'];
                        $pnr = isset($response['FlightItinerary']['PNR'])?$response['FlightItinerary']['PNR']:'';
    
                        return array('bookingid' => $bookingid, 'pnr' => $pnr, 'itinerary' => $itinerary);
                    }
                }
            }
            else {
                log_message('error', "NO API Response");
            }
        }
    }
}

?>