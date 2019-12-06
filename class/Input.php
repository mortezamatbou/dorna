<?php

namespace Dorna;


class Input {
    
    
    public function get($variable_name) {
        return filter_input(INPUT_GET, $variable_name, FILTER_SANITIZE_STRIPPED);
    }
    
    
    public function post($variable_name) {
        return filter_input(INPUT_POST, $variable_name, FILTER_SANITIZE_STRIPPED);
        
    }
    
    public function get_route($whitout = FALSE) {
        $route = '/';
        if ($this->get('route')) {
            $route = $this->get('route');
        } else if ($this->post('route')) {
            $route = $this->post('route');
        }
        if (substr($route, strlen($route)-1) != '/') {
            $route .= '/';
        }
        
        if ($whitout) {
            while (substr($route, 0, 1) == '/') {
                $route = preg_replace('/^\//', '', $route, 1);
            }
        }
        
        return $route;
    }
    
    
    public function get_token() {
        $token = '';
        if ($this->get('token')) {
            $token = $this->get('token');
        } else if ($this->post('token')) {
            $token = $this->post('token');
        } else {
            $headers = apache_request_headers();
            foreach ($headers as $header => $value) {
                if ($header === 'token') {
                    $token = htmlspecialchars(strip_tags($value));
                    break;
                }
            }
        }
        return $token;
    }
    
    
    public function ip_address() {
        $ip = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
    }
    
    
}