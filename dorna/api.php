<?php

require_once './inc/auth.inc.php';
require_once './class/Upload.php';

$upload = new Dorna\Upload();

$input = get_instance()->input;

$level = '';

if ($input->get('level')) {
    $level = $input->get('level');
} else if ($input->post('level')) {
    $level = $input->post('level');    
} else {
    token_error(TRUE);
}

$level = strtolower($level);

switch ($level) {
    case 'write':
        $upload->commit();
        break;
    case 'mkdir':
        get_instance()->user->make_dir();
        break;
    case 'delete':
        get_instance()->user->delete_files();
        break;
    case 'read':
        echo get_instance()->user->read_dir();
        break;
    case 'rename':
        get_instance()->user->rename();
        break;
}

