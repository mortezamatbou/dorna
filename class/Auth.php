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
        'root'   => '111',
        'admin'  => '222',
        'viewer' => '333',
        'test'   => '444'
    ];
    
    private $access = [
        'root' => [
            '' => 'read|write|rename|delete|move|trash|mkdir'
        ],
        'admin' => [
            '(music$)|(^\/music$)|(\/music\/[a-zA-Z0-9]*)|(music\/[a-zA-Z0-9]*)' => 'read|write|move|trash|mkdir|rename',
        ],
        'viewer' => [
            '(music)|(^\/music$)|(^\/music\/[a-zA-Z0-9]*)' => 'read'
        ],
        'test' => [
            '(music)|(^\/music$)|(^\/music\/[a-zA-Z0-9]*)' => 'read|write'
        ]
    ];
    
    private $block_sections = [
        DORNA_FOLDER
    ];
    
    
    function __construct() {
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