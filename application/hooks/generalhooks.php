<?php

class GeneralHooks {

    function post_controller() {
        $CI =& get_instance();
        $check = $CI->db->get_where('company_tbl', array('active' => 1));
        $result=$check->result_array();

        $allowed_hosts = array();

        for ($i=0; $i <count($result) ; $i++) { 
            $url = parse_url($result[$i]['baseurl']);
            
            $hostname = strtolower($url['host']);
            $hostname = str_replace('www.', '', $hostname);
            $allowed_hosts[] = $hostname;
            $allowed_hosts[] = 'www.'.$hostname;
        }

        $protocol = is_https() ? "https://" : "http://";
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : "";
        $host = str_replace('www.', '', $host);
        $servername = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : "";
        $servername = str_replace('www.', '', $servername);

        $base_url = '';
        if(is_cli()) {
           //$config['base_url'] = '';
           $CI->config->set_item('base_url', '');
        }
        else if(stristr($servername, "localhost") !== FALSE || (stristr($servername, '192.168.') !== FALSE) || (stristr($servername, '127.0.0') !== FALSE)) {
           //$config['base_url'] = $protocol.$host;
           $CI->config->set_item('base_url', $protocol.$host);
        }
        else {
            //$allowed_hosts = ['oxytra.pankh.com', 'airiq.pankh.com','wholesaler.example.com', 'supplier.example.com', 'example.com', 'www.example.com', 'www.oxytra.com', 'wholesaler.oxytra.com', 'supplier.oxytra.com', 'oxytra.com', 'www.oxytra.in', 'oxytra.in'];
            //$config['base_url'] = in_array($servername, $allowed_hosts) ? $protocol.$host."/" : "we-do-not-recognise-this-host.com";
            $CI->config->set_item('base_url', in_array($servername, $allowed_hosts) ? $protocol.$host."/" : "we-do-not-recognise-this-host.com");
        }
    }
}

?>