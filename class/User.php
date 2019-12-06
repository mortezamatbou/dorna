<?php

namespace Dorna;

include './class/UserActions.php';
include './class/FileManager.php';

class User extends FileManager {    
    
    function __construct($user_info) {
        parent::__construct($user_info);
    }
    
    
}
