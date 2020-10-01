<?php

namespace Dorna;

class Upload {    
    
    const SIZE_B = 1;
    const SIZE_KB = 2;
    const SIZE_MB = 3;


    private $var_name = 'uploadfile';
    
    public $instance;
    public $user;
    
    function __construct() {
        $this->instance = get_instance();
        $this->user = get_instance()->user;
    }
    
    function get_file() {
//        move_uploaded_file($filename, $destination);
        
        echo $this->instance->user->real_patch_upload();
        
    }
    
    function commit() {
        move_uploaded_file($this->get_temp_name(), $this->user->real_patch_upload() . $this->get_name());
    }
    
    
    function get_ext() {
        $path = $_FILES[$this->var_name]['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        return $ext;
    }
    
    function get_name() {
        return isset($_FILES[$this->var_name]['name']) ? $_FILES[$this->var_name]['name'] : ''; 
    }
    
    function get_size($size = 2) {
        if (!isset($_FILES[$this->var_name]['size'])) {
            return 0;
        }
        
        $file_size = $_FILES[$this->var_name]['size'];
        
        switch ($size) {
            case Upload::SIZE_KB:
                $file_size /= 1000;
                break;
            case Upload::SIZE_MB:
                $file_size /= 1000000;
                break;
        }
        
        
        return $file_size;
        
    }
    
    function get_temp_name () {
        return isset($_FILES[$this->var_name]['tmp_name']) ? $_FILES[$this->var_name]['tmp_name'] : ''; 
    }
    
    
}
