<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('Api.php');
require_once(APPPATH.'core/Common.php');

class Api_tripmaza extends Api {
    private $config = array();

    private $apibaseurl = '';
    private $host = '';
    private $userid = '';
    private $password = '';

    private $clientid = '';
    private $agencytype = '';
    private $tokenid = '';
    private $token_generated_on = NULL;

    private $cash_balance = 0.00;
    private $ticketing_balance = 0.00;

    private $enabled = true;

	public function __construct($config)
	{
        parent::__construct();
        
        if(is_array($config)) {
            $this->config = array_merge(array("companyid" => 0, "host" => "", "userid" => "", "password" => "", "url" => ''), $config);

            $this->host = $this->config['host'];
            $this->userid = $this->config['userid'];
            $this->password = $this->config['password'];
            $this->apibaseurl = $this->config['url'];

            log_message('info', "Config passed => ".json_encode($config));
        }
        else {
            log_message('error', "Library - api_tripmaza - Wrong config passed - $config");
        }
    }
    
    public function post($urlpart, $data, $content_type="application/json") {

        log_message('info', "Posted Data : ".json_encode($data, JSON_UNESCAPED_SLASHES));
        //log_message('info', "Posted Data : ".json_encode($data, JSON_FORCE_OBJECT | JSON_UNESCAPED_SLASHES));
        //log_message('info', "Posted Data : ".array2json($data, JSON_UNESCAPED_SLASHES));

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
			CURLOPT_TIMEOUT => 30,
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

		curl_setopt_array($curl, $opt);

		$response = curl_exec($curl);
		$err = curl_error($curl);
        $info = curl_getinfo($curl);

        log_message('info', 'Info Collected from REST Call '.json_encode($info));
        log_message('info', 'Response Collected from REST Call '.json_encode($response));
        log_message('info', 'Error Collected from REST Call '.json_encode($err));

        curl_close($curl);

        return array('response' => $response, 'info' => $info, 'err' => $err);
    }

    public function reauthenticate()
    {
        $data = array('EndUserIp' => '121.101.14.10', 'Host' => $this->host, 'UserID' => $this->userid, 'UserPassword' => $this->password);
        $err = '';
        $response = '';
        $info = '';
        $urlpart = "Rest.svc/v2/Authorization";
        $result = $this->post($urlpart, $data, "application/json");
        if($result && is_array($result)) {
            $response = $result['response'];
            $info = $result['info'];
            $err = $result['err'];
        }

		if ($err) 
		{
			log_message("info", "ERROR : $err");
		  	return false;
		} 
		else 
		{
            $this->token_generated_on = date('Y-m-d H:i:s');

            log_message("info", "Response | $response");
            if($response !== '') {
                $response = json_decode($response, TRUE);
            }
    
            if($response && is_array($response) && $response['Error'] && intval($response['Error']['ErrorCode']) === 0) {
                $this->agencytype = $response['Result']['Agencytype'];
                $this->clientid = $response['Result']['ClientID'];
                $this->tokenid = $response['Result']['TokenID'];

                log_message('info', "Token generated -> ".$this->tokenid." | Client Id -> ".$this->clientid." | Agency Type -> ".$this->agencytype);
            }
            else {
                log_message('error', "Some error -> ".json_encode($response));
            }
		  	return $response;
		}
    }

    public function search_inventory($payload) {
        if(!$payload || !is_array($payload)) return NULL;
        $pax = intval($payload['no_of_person']);
        $departure_date = date('m/d/Y', strtotime($payload['departure_date']));
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
        
        $tickets = [];

        $data = array('AuthoTokenId' => $this->tokenid, 'IsOnlineUser' => 'True', 'IsOnlyGroup' => 'True', 'ClientID' => $this->clientid, 'TokenId' => $this->tokenid, 'AdultCount' => $pax, 'ChildCount' => 0, 'InfantCount' => 0,
                'IsDomestic' => true, 'DirectFlight' => $direct, 'OneStopFlight' => $stopflight, 'JourneyType' => "1", 'PreferredAirlines' => NULL);

        $data['Segments'] = [array(
            'Origin' => $source_city_code,
            'Destination' => $destination_city_code,
            'FlightCabinClass' => '1',
            'PreferredDepartureTime' => $departure_date.' 00:00:00',
            'PreferredArrivalTime' => $departure_date.' 00:00:00',
            'TMResultIndex' => '0'
        )];
        $data['Sources'] = NULL;

        $urlpart = "Rest.svc/V2/Searchflight";
        $result = $this->post($urlpart, $data, "application/json");

        if($result && is_array($result)) {
            $response = $result['response'];
            $info = $result['info'];
            $err = $result['err'];
        }

		if ($err) 
		{
			log_message("info", "ERROR : $err");
		  	return false;
		} 
		else 
		{
            if($response !== '') {
                $response = json_decode($response, TRUE);
            }
            if($response && is_array($response) && count($response)>0) {
                $result = $response['Response']['Results'][0];
                $lastairline = null;
                for ($i=0; $i < count($result); $i++) { 
                    $tmzticket = $result[$i];
                    if($tmzticket) {
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

                            $no_of_seats = intval($tmzticket['Segments'][0][0]['NoOfSeatAvailable']);
                            $no_of_seats = $no_of_seats>0?$no_of_seats:$pax;
                            $price = round(floatval($tmzticket['Fare']['OfferedFare'])/$no_of_seats, 0);
                            $ticket = array(
                                'id' => (100100000 + intval($tmzticket['ResultIndex'])),
                                'uid' => intval($company['primary_user_id']),
                                'remarks' => 'Live inventory from API',
                                'source' => $source_city_id,
                                'destination' => $destination_city_id,
                                'source_code' => $tmzticket['Segments'][0][0]['Origin']['Airport']['AirportCode'],
                                'destination_code' => $tmzticket['Segments'][0][0]['Destination']['Airport']['AirportCode'],
                                'source_city' => $source_city, //trim($tmzticket['Segments'][0][0]['Origin']['Airport']['AirportName']),
                                'destination_city' => $destination_city, //trim($tmzticket['Segments'][0][0]['Destination']['Airport']['AirportName']),
                                'departure_date_time' => date('Y-m-d H:i:s', strtotime($tmzticket['Segments'][0][0]['Origin']['DepTime'])),
                                'arrival_date_time' => date('Y-m-d H:i:s', strtotime($tmzticket['Segments'][0][0]['Destination']['ArrTime'])),
                                'flight_no' => trim($tmzticket['Segments'][0][0]['Airline']['AirlineCode']).' '.trim($tmzticket['Segments'][0][0]['Airline']['FlightNumber']),
                                'terminal' => $tmzticket['Segments'][0][0]['Destination']['Airport']['Terminal'],
                                'departure_terminal' => $tmzticket['Segments'][0][0]['Origin']['Airport']['Terminal'],
                                'arrival_terminal' => $tmzticket['Segments'][0][0]['Destination']['Airport']['Terminal'],
                                'no_of_person' => $no_of_seats,
                                'seatsavailable' => $no_of_seats,
                                'tag' => $tmzticket['FareRules'][0]['FareRuleDetail'],
                                'data_collected_from' => 'tmz_api',
                                'sale_type' => 'api', /*This will be live only but for now making it API //live */
                                'refundable' => $tmzticket['IsRefundable']?'Y':'N',
                                'total' => $price, //floatval($tmzticket['Fare']['OfferedFare']),
                                'airline' => $lastairline['id'],
                                'aircode' => $tmzticket['Segments'][0][0]['Airline']['AirlineCode'],
                                'ticket_no' => 'TMZ-TKT-'.intval($tmzticket['ResultIndex']),
                                'price' => $price, //floatval($tmzticket['Fare']['OfferedFare']),
                                'cost_price' => $price, //floatval($tmzticket['Fare']['OfferedFare']),
                                'approved' => 1,
                                'class' => 'ECONOMY',
                                'no_of_stops' => intval($tmzticket['Segments'][0][0]['SegmentIndicator']),
                                'companyid' => intval($supl_company['id']), //$company['id'], This has to be supplier company id. Means who own the ticket
                                'companyname' => $supl_company['display_name'],
                                'user_id' => intval($supl_company['primary_user_id']), // -1,
                                'admin_markup' => 0,
                                'rate_plan_id' => $supl_rateplan_id,
                                'supplierid' => $supl_companyid,
                                'sellerid' => $company['id'],
                                'seller_rateplan_id' => $default_rateplan_id,
                                'adult_total' => $price, //0.00,
                                'image' => $image,
                                'ResultIndex' => intval($tmzticket['ResultIndex']),
                                'SearchIndex' => intval($tmzticket['SearchIndex']),
                                'SuppSource' => intval($tmzticket['SuppSource']),
                                'SuppTokenId' => $tmzticket['SuppTokenID'],
                                'SuppTraceId' => $tmzticket['SuppTraceId'],
                                'tokenid' => $tmzticket['AuthoTraceId'],
                                'clientid' => $this->clientid,
                                'agencytype' => $this->agencytype
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
            
            log_message("info", "Response (Ticket Search) : ".json_encode($response, JSON_UNESCAPED_SLASHES));
            log_message("info", "Searched Tickets : ".json_encode($tickets, JSON_UNESCAPED_SLASHES));

            return $tickets;
        }
    }

    public function check_balance() {
        $data = array('ClientID' => $this->clientid, 'TokenID' => $this->tokenid);
        $err = '';
        $response = '';
        $info = '';
        $urlpart = "Rest.svc/v2/checkbalance";
        $result = $this->post($urlpart, $data, "application/json");
        if($result && is_array($result)) {
            $response = json_decode($result['response'], TRUE);
            $info = $result['info'];
            $err = $result['err'];
        }
        
		if ($err) 
		{
			log_message("info", "ERROR : $err");
		  	return false;
		} 
		else 
		{
            if($response) {
                if(isset($response['Error']) && intval($response['Error']['ErrorCode'])>0) {
                    log_message('error', "API returned Error => ".$response['Error']['ErrorMessage']);
                }
                else {
                    $this->cash_balance = isset($response['CashBalance']) ? floatval($response['CashBalance']) : 0.00;
                    $this->ticketing_balance = isset($response['TicketingBalance']) ? floatval($response['TicketingBalance']) : 0.00;

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

        if($bookingid && intval(BookingId)<=0) return null;

        $data = array('TokenID' => $this->tokenid, 'BookingId' => $bookingid, 'TMagId' => $this->clientid);

        $err = '';
        $response = '';
        $info = '';
        $urlpart = "Rest.svc/v2/GetFlightBooking";
        $result = $this->post($urlpart, $data, "application/json");
        if($result && is_array($result)) {
            $response = json_decode($result['response'], TRUE);
            $info = $result['info'];
            $err = $result['err'];
        }
        
		if ($err) 
		{
			log_message("info", "ERROR : $err");
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
        $resultindex = intval($ticket['ResultIndex']);
        $suppsource = intval($ticket['SuppSource']);
        $supptraceid = $ticket['SuppTraceId'];
        $supptokenid = $ticket['SuppTokenId'];
        $searchindex = intval($ticket['SearchIndex']);
        $tokenid = $ticket['tokenid'];

        $data = array('EndUserIp' => '121.101.14.10', 'TokenId' => $tokenid, 'ResultIndex' => $resultindex, 'SuppSource' => $suppsource, 'SuppTraceId' => $supptraceid, 'SuppTokenId' => $supptokenid, 'SearchIndex' => $searchindex);

        log_message('info', 'Farequote => '.json_encode($data, JSON_UNESCAPED_SLASHES));
        $err = '';
        $response = '';
        $info = '';
        $urlpart = "Rest.svc/v2/Farequote";
        $result = $this->post($urlpart, $data, "application/json");
        if($result && is_array($result)) {
            $response = json_decode($result['response'], TRUE);
            $info = $result['info'];
            $err = $result['err'];
        }
        
		if ($err) 
		{
			log_message("info", "ERROR : $err");
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
                    $isprice_changed = boolval($response['IsPriceChanged']) ? boolval($response['IsPriceChanged']) : false;
                    $traceid = isset($response['TraceId']) ? $response['TraceId'] : '';
                    $fqindex = isset($response['FQIndex']) ? intval($response['FQIndex']) : 0;

                    return array('isprice_changed' => $isprice_changed, 'offeredfare' => floatval($response['Results']['Fare']['OfferedFare']), 'traceid' => $traceid, 'fqindex' => $fqindex);
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

        $fare_quote = $this->fare_quote($ticket);

        //We need to check balance also
        if($balance && $fare_quote && $adminuser) {
            if(!boolval($fare_quote['isprice_changed'])) {
                $traceid = $fare_quote['traceid'];
                $fqindex = $fare_quote['fqindex'];
                $passengers = [];

                for ($i=0; $i < count($customers); $i++) { 
                    $customer = $customers[$i];
                    $passender = array(
                        'PaxId' => "$i",
                        'Title' => $customer['prefix'],
                        'FirstName' => $customer['first_name'],
                        'LastName' => $customer['last_name'],
                        'PaxType' => 1,
                        'DateOfBirth' => (intval($customer['age'])>0 ? date("Y-m-d", strtotime("-".intval($customer['age'])." years")) : date("Y-m-d", strtotime("-20 years"))),
                        'Gender' => ($customer['prefix']==='Mr.'?'1':'2'),
                        'PassportNo' => null,
                        'PassportExpiry' => null,
                        'AddressLine1' => '-',
                        'AddressLine2' => '-',
                        'City' => '',
                        'CountryCode' => 'IN',
                        'CountryName' => 'India',
                        'Email' => $adminuser['email'],
                        'ContactNo' => $adminuser['mobile'],
                        'IsLeadPax' => true,
                        'FFAirline' => null,
                        'FFNumber' => null,
                        'Fare' => array('BaseFare' => 0, 'Tax' => 0, 'TransactionFee' => 0, 'YQTax' => 0, 'AdditionalTxnFeeOfrd' => 0, 'AdditionalTxnFeePub' => 0, 'AirTransFee' => 0),
                        'Baggage' => null,
                        'MealDynamic' => null,
                        'Ticket' => null
                    );

                    array_push($passengers, $passender);
                }

                $data = array('PageID' => $traceid, 'AuthoToken' => $this->tokenid, 'Ticketing' => array(array('Passengers' => $passengers,'GSTDetails' => null, 'FareQuoteIndexs' => $fqindex)), 'LastBalance' => 0.00, 'PmtType' => 'F-Credit');

                log_message('info', 'Ticketing API POST => '.json_encode($data, JSON_UNESCAPED_SLASHES));
                
                $err = '';
                $response = '';
                $info = '';
                $urlpart = "Rest.svc/V2/ticketing";
                $result = $this->post($urlpart, $data, "application/json");
                if($result && is_array($result)) {
                    $response = json_decode($result['response'], TRUE);
                    $info = $result['info'];
                    $err = $result['err'];
                }
                
                if ($err) 
                {
                    log_message("info", "ERROR : $err");
                      return false;
                } 
                else 
                {
                    if($response) {
                        if(isset($response['Error']) && intval($response['Error']['ErrorCode'])>0) {
                            log_message('error', "API returned Error => ".$response['Error']['ErrorMessage']);
                        }
                        else {
                            $bookingid = intval($response['BookingIndex']);
                            $pnrlist = $response['PNRList'];
                            $pageid = $response['PageID'];
        
                            return array('bookingid' => $bookingid, 'pnrlist' => $pnrlist, 'pageid' => $pageid);
                        }
                    }
                    else {
                        log_message('error', "NO API Response");
                    }
                }
            }
            else {
                log_message('error', 'Ticket price changed at final stage. I think its already more than 20 mins waiting.');
            }
        }
    }

    public function get_booking_details($payload) {
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

        log_message('info', 'Get Booking Details API POST => '.json_encode($data, JSON_UNESCAPED_SLASHES));
        
        $err = '';
        $response = '';
        $info = '';
        $urlpart = "Rest.svc/V2/GetFlightBooking";
        $result = $this->post($urlpart, $data, "application/json");
        if($result && is_array($result)) {
            $response = json_decode($result['response'], TRUE);
            $info = $result['info'];
            $err = $result['err'];
        }
        
        if ($err) 
        {
            log_message("info", "ERROR : $err");
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