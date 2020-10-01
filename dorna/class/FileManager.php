<?php

namespace Dorna;

abstract class FileManager extends UserActions {

    private $action;
    public $user;
    public $access;
    public $ip;
    public $token;
    public $user_info = array();
    private $route;

    function __construct($user_info) {
        parent::__construct($user_info);
        $this->user_info = $user_info;
        $this->user = $user_info['user'];
        $this->token = $user_info['token'];
        $this->ip = $user_info['ip'];
        $this->access = $user_info['access'];
    }

    function get_list($route = ROOT) {
        $this->action = 'read';
        $this->route = ROOT . get_instance()->input->get_route();
        if (!$route | $route == '/') {
            $this->route = ROOT;
        }
        return $this->get_files(scandir($this->route));
    }

    function get_files($files) {

        $result = array();

        if (!count($files)) {
            return NULL;
        }

        
        foreach ($files as $file) {
            $continue = TRUE;
//            if ($this->check_dir()) {
//                
//            }
            


            if ($file == '..' && $this->route != ROOT) {

                $segments = explode('/', get_instance()->input->get_route());

                $new_route = implode('/', array_slice($segments, 0, count($segments) - 2));
                
                if (substr($new_route, 0, 1) == '/') {
                    $new_route = preg_replace('/\//', '', $new_route, 1);
                }
                
                $route_address = base_url() . 'index.php?route=' . $new_route . '&token=' . $this->token;
                
                $file_link = host_url() . $new_route;
                
                $result[] = ['file_name' => $file, 'route' => $route_address, 'file_link' => $file_link];
                
            } else if ($file != '.') {
                
            if ($this->user != 'root') {
                foreach ($this->access as $pattern => $actions) {
                    if (preg_match('/' . $pattern . '/', get_instance()->input->get_route() . $file)) {
                        $continue = FALSE;
                    }
                }

                if ($continue) {
                    continue;
                }
            }
                
                $route_address = base_url() . 'index.php?route=' . get_instance()->input->get_route() . $file . '&token=' . $this->token;
                
                $r = substr(get_instance()->input->get_route(), 0, 1) 
                        ? preg_replace('/\//', '', get_instance()->input->get_route(), 0)
                        : get_instance()->input->get_route();
                
                $file_link = host_url() . $r . ''. $file;
                
                $link = host_url() . $r;
                
                $result[] = ['file_name' => $file, 'route' => $route_address, 'file_link' => $file_link, 'link' => $link];
                
            }
        }

        return $result;
    }
    
    
    function real_patch_upload($without = TRUE) {
        return ROOT . get_instance()->input->get_route($without);
    }

    function make_dir() {
        $input = get_instance()->input;
        $dir_name = '';
        
        if ($input->get('folder_name')) {
            $dir_name = $input->get('folder_name');
        } else if ($input->post('folder_name')) {
            $dir_name = $input->post('folder_name');
        } else {
            token_error(TRUE);
        }
        
        while (substr($dir_name, 0, 1) == '/') {
            $dir_name = preg_replace('/^\//', '', $dir_name, 1);
        }
        
        $path = $this->real_patch_upload();
        
        $dirs = explode('/', $dir_name);
        
        
        foreach ($dirs as $dir) {
            if ($dir) {
                $path .= '/' . $dir;
                if (!is_dir($path)) {
                    mkdir($path);
                }
            }
        }
        echo "Folder added";
        exit;
        
    }
    
    function delete_files() {
        $input = get_instance()->input;
        $delete_files = '';
        
        if ($input->get('delete_files')) {
            $delete_files = $input->get('delete_files');
        } else if ($input->post('delete_files')) {
            $delete_files = $input->post('delete_files');
        } else {
            token_error(TRUE);
        }
        
        
        $files = explode(',', $delete_files);
        $path = $this->real_patch_upload(FALSE);
        
        foreach ($files as $file) {
            if (is_file($path . $file)) {
                unlink($path . $file);
            }
        }
        
        echo "Your seleted file deleted"; 
        exit;
        
    }
    
    function rename() {
        $input = get_instance()->input;
        
        // 1 is directory
        // 2 is file
        $type = 1;
        
        $rename_file = '';
        $rename_to = '';
        
        if ($input->get('rename_file')) {
            $rename_file = $input->get('rename_file');
        } else if ($input->post('rename_file')) {
            $rename_file = $input->post('rename_file');
        } else {
            token_error(TRUE);
        }
        
        if ($input->get('rename_to')) {
            $rename_to = $input->get('rename_to');
        } else if ($input->post('rename_to')) {
            $rename_to = $input->post('rename_to');
        } else {
            token_error(TRUE);
        }
        
        $path = $this->real_patch_upload(FALSE);
        
        if (is_file($path . $rename_file)) {
            $type = 2;
        } else if (is_dir($path . $rename_file)) {
            $type = 1;
        } else {
            echo 'Referesh page and try again!';
            exit;
        }
        if ($type == 1) {
            
            if (is_dir($path . $rename_to)) {
                echo 'Directory: ' . $rename_to . ' exist!';
                exit;
            }
            $message = rename($path . $rename_file, $path . $rename_to) ? "Rename Directory $rename_file to $rename_to" : "Renaming directory failed!";
            
        } else if ($type == 2) {
            if (is_file($path . $rename_to)) {
                echo 'File: ' . $rename_to . ' exist!';
                exit;
            }            
            $message = rename($path . $rename_file, $path . $rename_to) ? "Rename File $rename_file to $rename_to" : "Renaming file failed!";
            
        }
        
        echo $message;
        exit;
        
        
    }
    
    
    function read_dir() {
        $user = get_instance()->user;
        include_once './inc/files_list.inc.php';
    }
    
    
}
