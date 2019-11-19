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

    private $enabled = true;

	public function __construct($config)
	{
        parent::__construct();
        
        if(is_array($config)) {
            $this->config = array_merge(array("companyid" => 0, "host" => "", "userid" => "", "userpassword" => "", "url" => ''), $config);

            $this->host = $this->config['host'];
            $this->userid = $this->config['userid'];
            $this->password = $this->config['userpassword'];
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
        // $data = json_encode($data, JSON_FORCE_OBJECT);
        // $curl = curl_init();
        
        // $ch = curl_init($this->apibaseurl."Rest.svc/v2/Authorization");
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        // curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        //     'Content-Type: application/json',
        //     'Content-Length: '.strlen($data)
        // ));

        // $opt = array(
        //     CURLOPT_URL => $this->apibaseurl."Rest.svc/v2/Authorization",
        //     //CURLOPT_URL => 'http://api.tektravels.com/SharedServices/SharedData.svc/rest/Authenticate',
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLINFO_HEADER_OUT => true,
		// 	//CURLOPT_ENCODING => "",
		// 	//CURLOPT_MAXREDIRS => 10,
		// 	CURLOPT_TIMEOUT => 30,
		// 	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => "POST",
        //     CURLOPT_POST => true,
        //     //CURLOPT_POSTFIELDS => json_encode(array('ClientId' => 'ApiIntegrationNew', 'UserName' => 'RadhaRani', 'Password' => 'radha@1234', 'EndUserIp' => '192.168.11.120'), JSON_FORCE_OBJECT), //$data,
        //     CURLOPT_POSTFIELDS => $data,
		// 	CURLOPT_HTTPHEADER => array(
        //         "Content-Type: application/json",
        //         //"Content-Length: ".strlen($data)
		// 	),
		// );

		// curl_setopt_array($curl, $opt);

		// $response = curl_exec($curl);
		// $err = curl_error($curl);
        // $info = curl_getinfo($curl);

        // log_message('info', 'Info Collected from REST Call '.json_encode($info));
        // log_message('info', 'Response Collected from REST Call '.json_encode($response));
        // log_message('info', 'Error Collected from REST Call '.json_encode($err));

        // curl_close($curl);
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
        $source_city_code = $payload['source_city_code'];
        $destination_city_code = $payload['destination_city_code'];
        $direct = intval($payload['direct'])==1;
        $stopflight = intval($payload['one_stop'])==1;
        
        $company = $payload['company'];
        $current_user = $payload['current_user'];
        $default_rateplan_id = intval($payload['default_rateplan_id']);
        $airlines = $payload['airlines'];
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
                            $ticket = array(
                                'id' => (100100000 + intval($tmzticket['ResultIndex'])),
                                'source_code' => $tmzticket['Segments'][0][0]['Origin']['Airport']['AirportCode'],
                                'destination_code' => $tmzticket['Segments'][0][0]['Destination']['Airport']['AirportCode'],
                                'source_city' => trim($tmzticket['Segments'][0][0]['Origin']['Airport']['AirportName']),
                                'destination_city' => trim($tmzticket['Segments'][0][0]['Destination']['Airport']['AirportName']),
                                'departure_date_time' => date('Y-m-d H:i:s', strtotime($tmzticket['Segments'][0][0]['Origin']['DepTime'])),
                                'arrival_date_time' => date('Y-m-d H:i:s', strtotime($tmzticket['Segments'][0][0]['Destination']['ArrTime'])),
                                'flight_no' => trim($tmzticket['Segments'][0][0]['Airline']['AirlineCode']).' '.trim($tmzticket['Segments'][0][0]['Airline']['FlightNumber']),
                                'terminal' => $tmzticket['Segments'][0][0]['Destination']['Airport']['Terminal'],
                                'departure_terminal' => $tmzticket['Segments'][0][0]['Origin']['Airport']['Terminal'],
                                'arrival_terminal' => $tmzticket['Segments'][0][0]['Destination']['Airport']['Terminal'],
                                'no_of_person' => $no_of_seats>0?$no_of_seats:$pax,
                                'seatsavailable' => $no_of_seats>0?$no_of_seats:$pax,
                                'tag' => $tmzticket['FareRules'][0]['FareRuleDetail'],
                                'data_collected_from' => 'tmz_api',
                                'sale_type' => 'api', /*This will be live only but for now making it API //live */
                                'refundable' => $tmzticket['IsRefundable']?'Y':'N',
                                'total' => floatval($tmzticket['Fare']['OfferedFare']),
                                'airline' => $tmzticket['Segments'][0][0]['Airline']['AirlineName'],
                                'aircode' => $tmzticket['Segments'][0][0]['Airline']['AirlineCode'],
                                'ticket_no' => 'TMZ-TKT-'.intval($tmzticket['ResultIndex']),
                                'price' => floatval($tmzticket['Fare']['OfferedFare']),
                                'cost_price' => floatval($tmzticket['Fare']['OfferedFare']),
                                'approved' => 1,
                                'class' => 'ECONOMY',
                                'no_of_stops' => intval($tmzticket['Segments'][0][0]['SegmentIndicator']),
                                'companyid' => $company['id'],
                                'companyname' => $company['display_name'],
                                'user_id' => -1,
                                'admin_markup' => 0,
                                'rate_plan_id' => 0,
                                'supplierid' => 0,
                                'sellerid' => $company['id'],
                                'seller_rateplan_id' => $default_rateplan_id,
                                'adult_total' => 0.00,
                                'image' => $image
                            );

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
}

?>