<?php

namespace Dorna;

abstract class UserActions {
    
    public $user_info;
    
    function __construct($user_info) {
        $this->user_info = $user_info;
    }
    
    public function is_write() {
        
    }
    
    protected function is_read() {
        
    }
    
    protected function is_move() {}
    
    protected function is_delete() {}
    
    protected function is_trash() {}
    
    protected function is_root() {}
    
}
