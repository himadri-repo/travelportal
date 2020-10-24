<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Ticket {
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

    public function __construct()
	{
		// parent::__construct();
	}

    public function __get($name) {
        return $this->$name;
    }

    public function __set($name, $value) {
        return $this->$name = $value;
    }
}
?>
