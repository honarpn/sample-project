<?php

/*

 * 
 * 
 */

/**
 * Description of Controller
 * This is Parent of all controllers in our Model View Controller(MVC) Design Pattern 
 * 
 * @author Ali Mohtasham Gilani <ali.mohtasham.g@gmail.com>
 */

namespace app\classes;

use app\classes\Token;

class Controller {

    /**
     *
     * @var string|null shows if someone logged into their account
     */
    protected $user_email = null;

    /**
     * Checks if someone logged into their account or not
     */
    public function __construct() {
        if (isset($_COOKIE['query_key']) && isset($_COOKIE['rememberme'])) {
            $this->user_email = Token::verifyToken();
        }
    }

    /**
     * Render a View class an Pass parameters to View
     * @param string $name is name of view class
     * @param Mixed[]|null $params depends on related view class
     */
    protected function showView($name, $params = array()) {
        $class = "app\\view\\" . $name;
        new $class($params);
    }

    /**
     * Forwards one controller to another 
     * @param string $controller 
     */
    protected function forward($controller) {
        $class = "app\\controller\\" . $controller;
        $object = new $class();
        $object->showPost(1);
    }

}
