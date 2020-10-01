<?php
namespace Dorna;

include_once './class/User.php';
include_once './class/Input.php';

class Auth {
    
    /**
     *
     * @var \Dorna\User
     */
    public $user;
    
    public $token = null;
    
    private static $instance;
    
    /**
     * 
     * @var \Dorna\Input
     */
    var $input;
    
    private $tokens = [
        'admin'   => '1',
        'uploader'  => '2',
    ];
    
    private $access = [
        'admin' => [
            '(uploads$)|(^\/uploads$)|(\/uploads\/[a-zA-Z0-9]*)|(uploads\/[a-zA-Z0-9]*)' => 'read|write|rename|delete|move|trash|mkdir'
        ],
        'uploader' => [
            '(uploads$)|(^\/uploads$)|(\/uploads\/[a-zA-Z0-9]*)|(uploads\/[a-zA-Z0-9]*)' => 'read|write|move|trash|mkdir|rename',
        ]
    ];
    
    private $block_sections = [
        DORNA_FOLDER
    ];
    
    
    function __construct() {
        session_start();
        self::$instance =& $this;
        $this->input = new Input();
    }

    /**
     * 
     * @param bool $api
     * @return \Dorna\User
     */
    function check_request($api = FALSE) {
        $this->token = "";
        
        if ($this->input->post('token')) {
            $this->token = $this->input->post('token');
        } else if ($this->input->get('token')) {
            $this->token = $this->input->get('token');
        } else {
            $this->token = $this->input->get_token();
        }
        
        if ($this->token) {
            $user_info = $this->get_token_info();
            
            if ($user_info) {
                $this->user = new \Dorna\User($user_info);
                return $this->user;
            }
        }
        
        token_error($api);
        
    }
    
    private function get_token_info() {
        foreach ($this->tokens as $level => $token) {
            if ($token == $this->token) {
                $user_info = [
                    'user' => $level,
                    'token' => $token,
                    'access' => $this->access[$level],
                    'ip' => $this->input->ip_address()
                ];
                return $user_info;
            }
        }
        return NULL;
    }
    
    public static function &get_instance() {
        return self::$instance;
    }

}