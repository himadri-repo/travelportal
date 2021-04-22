<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// require_once(APPPATH.'entities/ticket.php');
class SpecialTicket {
    private $id;
    private $created_date;
    private $source;
    private $destination;
    private $source1;
    private $destination1;
    private $trip_type;
    private $departure_date_time;
    private $arrival_date_time;
    private $flight_no;
    private $terminal;
    private $departure_date_time1;
    private $arrival_date_time1;
    private $flight_no1;
    private $terminal1;
    private $terminal2;
    private $terminal3;
    private $no_of_person;
    private $max_no_of_person;
    private $no_of_stop;
    private $stops_name;
    private $class1;
    private $airline;
    private $airline1;
    private $aircode;
    private $aircode1;
    private $pnr;
    private $ticket_no;
    private $price;
    private $baggage;
    private $meal;
    private $markup;
    private $admin_markup;
    private $discount;
    private $total;
    private $sale_type;
    private $refundable;
    private $availibility;
    private $user_id;
    private $remarks;
    private $approved;
    private $available;
    private $data_collected_from;
    private $last_sync_key;
    private $created_by;
    private $created_on;
    private $updated_by;
    private $updated_on;
    private $companyid;
    private $price_child;
    private $price_infant;
    private $cancel_rate;
    private $booking_freeze_by;
    private $tag;    

    private $ticket_type;
    private $sourcecode;
    private $destinationcode;
    private $airlinecode;
    private $airlinename;
    private $dept_airport_name;
    private $arrv_airport_name;
    private $airoplane;
    private $supplier;
    private $suppliercode;
    private $totalduration;

    public function __construct()
	{
        $this->created_date = date("Y-m-d H:i:s");
        $this->created_on = date("Y-m-d H:i:s");

		//parent::__construct();
	}

    public function __get($name) {
        return $this->$name;
    }

    public function __set($name, $value) {
        return $this->$name = $value;
    }    

    public function transform_online_ticket($ticket) {
        if(!$ticket) return null;

        try
        {
            // $poolingbaseid = explode('_', trim($ticket['pollId']));
            // $poolingbaseid = trim($poolingbaseid[0]);
            //$ticketid = isset($ticket['FID']) ? $ticket['FID'] : null;
            $ticketid = isset($ticket['Ref']) ? $ticket['Ref'] : null;
            // $fs = $ticket['OD'][0]['FS'][0];
            // $od = $ticket['OD'][0];
            $adultdata = isset($ticket['Seats']) ? intval($ticket['Seats']) : 0;
            // $childdata = $ticket['paxWise']['O']['CHD'];
            // $infantdata = $ticket['paxWise']['O']['INF'];

            if($ticketid) {
                $this->id = $ticketid; //isset($ticket['ID']) ? $ticket['ID'] : null;
                $this->trip_type = 'ONE';
                $this->supplier = isset($ticket['supplierType']) ? $ticket['supplierType'] : null;
                $this->suppliercode = isset($ticket['supplierCode']) ? $ticket['supplierCode'] : null;
                $this->departure_date_time = date("Y-m-d H:i",strtotime($ticket['Dep']));
                $this->arrival_date_time = date("Y-m-d H:i",strtotime($ticket['Arr']));
                $this->flight_no = $ticket['Fno'];
                $this->aircode = $ticket['plt'];
                $this->airlinecode = $ticket['plt'];
                $this->airlinename = isset($ticket['plt']) ? $ticket['plt'] : '';
                $this->dept_airport_name = isset($ticket['Org']) ? $ticket['Org'] : '';
                $this->arrv_airport_name = isset($ticket['Des']) ? $ticket['Des'] : '';
                $this->class = strtoupper($ticket['Cab']) == 'E' ? 'ECONOMY' : '' ;
                $this->totalduration = intval($ticket['Dur']);
                // $this->sourcecode = $fs['dac'];
                // $this->destinationcode = $fs['aac'];
                $this->terminal = 'T-';
                $this->terminal1 = 'T-';

                $this->no_of_person = intval($ticket['Seats'])>10 ? 10 : intval($ticket['Seats']);
                $this->availibility = $this->no_of_person;
                $this->max_no_of_person = $this->no_of_person;
                $this->available = $this->no_of_person > 0 ? 'YES' : 'NO';
                $this->no_of_stop = 0;
                $this->data_collected_from = 'rndtrip';
                $this->last_sync_key = $ticketid;
                $this->companyid = 1;
                $this->user_id = 104;
                $this->created_by = 104;
                $this->refundable = 'N';
                $this->approved = 1;
                $this->sale_type = 'request';
                $this->tag = $this->ticket_type;

                $reschedule = $ticket['seg'];
                $cancel = 0;
                $meal = 'NO';
                $bag = $ticket['Bagg'];
                $this->remarks = 'Baggage : '.$bag.' | Reschedule : '.$reschedule;
                $this->cancel_rate = $cancel;

                $this->ticket_no = $ticketid;
                
                $this->price = floatval($ticket['NETFare']);
                $this->total = $this->price;
                $this->baggage = 0.00;
                $this->meal = 0.00;
                $this->markup = 0.00;
                $this->discount = 0.00;
                //$this->price_infant = floatval($infantdata['fare']['TF']);
                $this->price_infant = $this->price_infant > 1500 ? $this->price_infant : 1500;
            }
        }
        catch(Exception $ex) {
            log_message('debug', $ex);
        }

        return $this;
    }

    public function fill_data($ticket) {
        if(!$ticket) return;

        try {
            $this->id = $ticket->id;
            $this->trip_type = $ticket->trip_type;
            $this->supplier = $ticket->supplier;
            $this->suppliercode = $ticket->suppliercode;
            $this->departure_date_time = $ticket->departure_date_time;
            $this->arrival_date_time = $ticket->arrival_date_time;
            $this->flight_no = $ticket->flight_no;
            $this->aircode = $ticket->aircode;
            $this->airlinecode = $ticket->airlinecode;
            $this->airlinename = $ticket->airlinename;
            $this->dept_airport_name = $ticket->dept_airport_name;
            $this->arrv_airport_name = $ticket->arrv_airport_name;
            $this->class = $ticket->class;
            $this->totalduration = $ticket->totalduration;
            $this->sourcecode = $ticket->sourcecode;
            $this->destinationcode = $ticket->destinationcode;
            $this->terminal = $ticket->terminal;
            $this->terminal1 = $ticket->terminal1;

            $this->no_of_person = $ticket->no_of_person;
            $this->availibility = $ticket->availibility;
            $this->max_no_of_person = $ticket->max_no_of_person;
            $this->available = $ticket->available;
            $this->no_of_stop = $ticket->no_of_stop;
            $this->data_collected_from = 'atrip';
            $this->last_sync_key = $ticket->last_sync_key;
            $this->companyid = $ticket->companyid;
            $this->user_id = $ticket->user_id;
            $this->created_by = $ticket->created_by;
            $this->refundable = $ticket->refundable;
            $this->approved = $ticket->approved;
            $this->sale_type = $ticket->sale_type;
            $this->tag = $ticket->tag;

            $this->remarks = $ticket->remarks;
            $this->cancel_rate = $ticket->cancel_rate;

            $this->ticket_no = $ticket->ticket_no;
            
            $this->price = $ticket->price;
            $this->total = $ticket->total;
            $this->baggage = $ticket->baggage;
            $this->meal = $ticket->meal;
            $this->markup = $ticket->markup;
            $this->discount = $ticket->discount;
            $this->price_infant = $ticket->price_infant;
        }
        catch(Exception $ex) {
            log_message('debug', $ex);
        }
    }
}
?>
