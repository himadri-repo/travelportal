<?php

class GeneralHooks {
    function post_controller_constructor() {
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

    function post_controller() {
        $CI =& get_instance();

        try
        {
            $company = $CI->session->userdata('company');
            $current_user = $CI->session->userdata('current_user');

            $protocol = is_https() ? "https://" : "http://";
            $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : "";
            $host = str_replace('www.', '', $host);
            $servername = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : "";
            $servername = str_replace('www.', '', $servername);

            log_message('info', "Hooks:Post_Controller - Current_User => ".json_encode($current_user));
            log_message('info', "Hooks:Post_Controller - Company => ".json_encode($company));

            log_message('info', "Hooks:Post_Controller - SERVER => ".json_encode($_SERVER));
            log_message('info', "Hooks:Post_Controller - POST => ".json_encode($_POST));
            if($current_user!=null && intval($current_user['id'])>0) {
                log_message('info', "Hooks:Post_Controller - USER => ".json_encode($current_user));
                
                $this->save_user_activity($_SERVER, $_POST, $current_user, $company);
            }
        }
        catch(Exception $ex) {
            log_message('error', "Hooks:Post_Controller - ".$ex);
        }
    }

    protected function save_user_activity($serverdata, $posteddata, $currentuser, $company) {
        $CI =& get_instance();

        $proceed_to_save = true;
        $uri = isset($serverdata['REQUEST_URI']) ? $serverdata['REQUEST_URI'] : '';
        $controller = '';
        $method = '';
        if($uri!=='') {
            $uri_parts = explode('/', $uri);
            if(count($uri_parts)>=2) {
                $controller = trim($uri_parts[1]);
            }
            if(count($uri_parts)>=3) {
                $method = trim($uri_parts[2]);
            }
        }

        $data = array(
            'userid' => $currentuser['id'],
            'remote_ip' => isset($serverdata['REMOTE_ADDR']) ? $serverdata['REMOTE_ADDR'] : '',
            'remote_port' => isset($serverdata['REMOTE_PORT']) ? $serverdata['REMOTE_PORT'] : '',
            'request_method' => isset($serverdata['REQUEST_METHOD']) ? $serverdata['REQUEST_METHOD'] : '',
            'user_agent' => isset($serverdata['HTTP_USER_AGENT']) ? $serverdata['HTTP_USER_AGENT'] : '',
            'is_ajax' => isset($serverdata['HTTP_X_REQUESTED_WITH']) ? (strpos($serverdata['HTTP_X_REQUESTED_WITH'], 'XMLHttpRequest')>-1?1:0) : 0,
            'http_cookie' => isset($serverdata['HTTP_COOKIE']) ? $serverdata['HTTP_COOKIE'] : '',
            'http_cookie' => isset($serverdata['HTTP_COOKIE']) ? $serverdata['HTTP_COOKIE'] : '',
            'uri' => $uri,
            'controller' => $controller,
            'method' => $method,
            'posted_data' => json_encode($posteddata),
            'server_data' => json_encode($serverdata)
        );

        log_message('info', "Hooks:Post_Controller - Logging User Activity => ".json_encode($data));
        
        if($proceed_to_save) {
            $result = $CI->db->insert('user_activities_tbl', $data);

            log_message('info', "Hooks:Post_Controller - User Activity Logged => Result = $result");
        }
    }
}

?>