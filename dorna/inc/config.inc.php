<?php

//  root of your directory that you want to handle
define('ROOT', '../');

define('DORNA_FOLDER', 'dorna/');

// enter url without http:// or https://
// this url is your root of Dorna file manager
//define('BASE', '192.168.1.4/');
define('BASE', 'jiga1.gilaki.net/');

// your app name
define('APP_NAME', 'Gilaki.net | Dorna Simple FileManager');

define('TRASH_DIR', '../_trash/');

// for force in generate base_url in https
$force_https = FALSE;


$valid_ext = ['gif', 'png', 'jpeg', 'jpg', 'mp3', 'mp4', 'ogg', 'mogg', 'oga', 'm4p'];

date_default_timezone_set("Asia/Tehran");

function base_url($https = FALSE) {
    global $force_https;
    if ($force_https) {
        $https = TRUE;
    }
    return $https ? 'https://' . BASE . DORNA_FOLDER : 'http://' . BASE . DORNA_FOLDER;
}

function host_url($https = FALSE) {
    global $force_https;
    if ($force_https) {
        $https = TRUE;
    }
    return $https ? 'https://' . BASE : 'http://' . BASE;
}

function token_error($api = FALSE) {
    if (!$api) {
        ob_clean();
        include_once './inc/error.inc.php';
        exit;
    }
    
    header_remove();
    http_response_code(401);
    $response = [
        'status' => '401',
        'message' => 'Unauthorize request! check your token'
    ];
    echo json_encode($response);
    exit;
}

