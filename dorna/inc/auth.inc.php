<?php

require './inc/config.inc.php';
require './class/Auth.php';

$auth = new \Dorna\Auth();

/**
 * 
 * @return \Dorna\Auth
 */
function &get_instance() {
    return \Dorna\Auth::get_instance();
}

/**
 * @var \Dorna\Auth return an object of \Dorna\User class if token be correct
 */
$user = $auth->check_request();
