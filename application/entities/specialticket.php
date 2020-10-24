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
            $poolingbaseid = explode('_', trim($ticket['pollId']));
            $poolingbaseid = trim($poolingbaseid[0]);
            //$ticketid = isset($ticket['FID']) ? $ticket['FID'] : null;
            $ticketid = isset($ticket['ID']) ? $ticket['ID'] : null;
            $fs = $ticket['OD'][0]['FS'][0];
            $od = $ticket['OD'][0];
            $adultdata = $ticket['paxWise']['O']['ADT'];
            $childdata = $ticket['paxWise']['O']['CHD'];
            $infantdata = $ticket['paxWise']['O']['INF'];

            if($fs && $od) {
                $this->id = isset($ticket['ID']) ? $ticket['ID'] : null;
                $this->trip_type = 'ONE';
                $this->supplier = isset($ticket['supplierType']) ? $ticket['supplierType'] : null;
                $this->suppliercode = isset($ticket['supplierCode']) ? $ticket['supplierCode'] : null;
                $this->departure_date_time = date("Y-m-d H:i",strtotime($fs['ddt'].' '.$fs['dd']));
                $this->arrival_date_time = date("Y-m-d H:i",strtotime($fs['adt'].' '.$fs['ad']));
                $this->flight_no = $fs['ac'].'-'.$fs['fl'];
                $this->aircode = $fs['ac'];
                $this->airlinecode = $fs['ac'];
                $this->airlinename = isset($fs['aname']) ? $fs['aname'] : '';
                $this->dept_airport_name = isset($fs['dan']) ? $fs['dan'] : '';
                $this->arrv_airport_name = isset($fs['aan']) ? $fs['aan'] : '';
                $this->class = strtoupper($fs['cabin']);
                $this->totalduration = $fs['du'];
                $this->sourcecode = $fs['dac'];
                $this->destinationcode = $fs['aac'];
                $this->terminal = 'T-'.$fs['dt'];
                $this->terminal1 = 'T-'.$fs['at'];

                $this->no_of_person = intval($fs['seat'])>10 ? 10 : intval($fs['seat']);
                $this->availibility = intval($fs['seat'])>10 ? 10 : intval($fs['seat']);
                $this->max_no_of_person = intval($fs['seat']);
                $this->available = $this->no_of_person > 0 ? 'YES' : 'NO';
                $this->no_of_stop = 0;
                $this->data_collected_from = 'atrip';
                $this->last_sync_key = $poolingbaseid;
                $this->companyid = 1;
                $this->user_id = 104;
                $this->created_by = 104;
                $this->refundable = 'N';
                $this->approved = 1;
                $this->sale_type = 'request';
                $this->tag = $this->ticket_type;

                $reschedule = isset($adultdata['farerule']) ? (floatval($adultdata['farerule']['change'])+200) : 0;
                $cancel = isset($adultdata['farerule']) ? (floatval($adultdata['farerule']['cancel'])+200) : 0;
                $meal = (isset($adultdata['meal']) && boolval($adultdata['meal'])) ? 'YES' : 'NO';
                $bag = (isset($adultdata['bag']) && isset($adultdata['bag']['value'])) ? $adultdata['bag']['value'].' '.$adultdata['bag']['unit'] : '';
                $this->remarks = 'Meal : '.$meal.' | baggage : '.$bag.' | Reschedule : '.$reschedule.' | Cancel : '.$cancel;
                $this->cancel_rate = $cancel;

                $this->ticket_no = $ticketid;
                
                $this->price = floatval($adultdata['fare']['TF']);
                $this->total = $this->price;
                $this->baggage = 0.00;
                $this->meal = 0.00;
                $this->markup = 0.00;
                $this->discount = 0.00;
                $this->price_infant = floatval($infantdata['fare']['TF']);
                $this->price_infant = $this->price_infant > 1500 ? $this->price_infant : 1500;
            }
        }
        catch(Exception $ex) {
            log_message('debug', $ex);
        }

        return $this;
    }
}
?>
