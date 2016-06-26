<?php

/**
 * Description of router
 * This class is responsible for routing requests
 * 
 * @author Ali Mohtasham Gilani <ali.mohtasham.g@gmail.com>
 */

namespace app;

use app\classes\JSON;
use app\controller\messageController;

class Router {

    /**
     *
     * @var string the action of controller 
     */
    private $action;

    /**
     *
     * @var Mixed[] arguments are going to current controller 
     */
    private $arguments= array();

    /**
     *
     * @var string is current controller 
     */
    private $controller;

    /**
     *
     * @var string[] contains name of controllers 
     */
    private $controllers = array();

    /**
     *
     * @var string[] contains loaded routes 
     */
    private $routsConfig;

    /**
     *
     * @var type 
     */
    private $message;

    /**
     * Initializing
     */
    public function __construct() {
        $this->loadConfig();
        $this->message = new messageController();
    }

    /**
     * Load information from "config/routes.json"
     */
    private function loadConfig() {
        $json = new JSON();
        $this->routsConfig = $json->readFile(__SITE_PATH . "\\config\\routes.json");
        foreach ($this->routsConfig['controllers'] as $key => $controller_name) {
            $this->controllers[] = $key;
        }
    }

    /**
     * Search path and set current controller, action and arguments
     * @param Mixed[] $params
     * @return boolean
     */
    protected function searchPath($params) {
        foreach ($this->routsConfig['controllers'] as $key_1 => $controller_name) {
            if ($params[0] == $key_1) {
                $this->controller = $params[0];
                foreach ($this->routsConfig['controllers'][$key_1] as $key_2 => $action_name) {
                    if ($params[1] == $key_2) {
                        $this->action = $params[1];

                        foreach ($params as $key_3 => $param) {
                            if ($key_3 != '0' && $key_3 != '1') {//First and Second parameters are controller and action respectively
                                $this->arguments[] = $param;
                            }
                        }
                        return true;
                    }
                }
            }
        }
        if ($this->controller == null || ($this->action == null && 1 < count($params))) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * This function has Routing responsibility
     * @param string[] $params none post and get parameters
     * @param Mixed[] $request post parameters
     */
    public function routing($params, $request = null) {

        #keeps users from requesting any file they want
        $safe_pages = array_merge($this->controllers, array("signup", "login", "change", "resetrequest", null));
        if ($request != null) {
            $path = array_merge($params, $request);
        } else {
            $path = $params;
        }
        //if URL contains one parameter 
        if (in_array($params[0], $safe_pages)) {
            if (count($params) == 1) {
                switch ($params[0]) {
                    case "signup":
                        $home = new controller\defaultController;
                        $home->go_to_page("signup");
                        break;
                    case "login":
                        $home = new controller\defaultController;
                        $home->go_to_page("login");
                        break;
                    case "change":
                        $home = new controller\defaultController;
                        $home->go_to_page("change");
                        break;
                    case "resetrequest":
                        $home = new controller\defaultController;
                        $home->go_to_page("resetrequest");
                        break;
                    case "posts":
                        $post = new controller\postController;
                        if ($post->showPost(1) == false) {
                            $this->message->show_message("404", "we are sorry but the page you are looking for cannot be found");
                        }
                        break;
                    default :
                        $default = new controller\defaultController;
                        $default->go_to_page();
                        break;
                }
            } else if (count($params) > 3) {//Users could enter URL with 3 parameter max, unless they send parameters by form 
                $this->message->show_message("404", "we are sorry but the page you are looking for cannot be found");
            } else if ($this->searchPath($path)) {//If number of parameters does not match to routes.json information searchPath function returns false
                switch ($this->controller) {
                    case "user":
                        $user = new controller\userController;
                        $home = new controller\defaultController;
                        switch ($this->action) {
                            case "logout":
                                $user->logout();
                                break;
                            case "signup":
                                $result = $user->signup($this->arguments[0], $this->arguments[1], $this->arguments[2]);
                                if ($result == "done") {
                                    $this->message->show_message("Successful Sign-Up", "Please check your email and complete your sign-up process.");
                                } else {
                                    $home->go_to_page("signup", $result);
                                }
                                break;
                            case "login":
                                if (count($this->arguments) == 3) {
                                    $result = $user->login($this->arguments[0], $this->arguments[1], $this->arguments[2]);
                                } else {
                                    $result = $user->login($this->arguments[0], $this->arguments[1]);
                                }
                                $home->go_to_page("login", $result);
                                break;
                            case "resetrequest":
						     	$result=$user->reset_request($this->arguments[0]);
                                if ( $result== "done") {
									 $this->message->show_message("Password Reset", "Please check your Email to reset your password");                  
                                } else {                                   
									$home->go_to_page("resetrequest", $result);
                                }
								
                                break;
                            case "new":
                                if ($user->new_password($this->arguments[0]) == "410") {
                                    $this->message->show_message("410", "Unfortunately the link is expired. ");
                                }
                                break;
                            case "reset":
                                $result = $user->reset_password($this->arguments[0], $this->arguments[1]);
                                if ($result == "done") {
                                    $this->message->show_message("Successful Password Reset", "Now, you can login to the website.");
                                } else {
                                    $home->go_to_page("reset", $result);
                                }
                                break;
                            case "change":
                                $result = $user->change_password($this->arguments[0], $this->arguments[1], $this->arguments[2]);
                                if ($result == "done") {
                                    $this->message->show_message("Password is Changed Successfully", "Now, you can login to the website with your new Password.");
                                } else {
                                    $home->go_to_page("change", $result);
                                }
                                break;
                            case "verify":
                                if ($user->verify_email($this->arguments[0]) == false) {
                                    $this->message->show_message("404", "we are sorry but the page you are looking for cannot be found");
                                } else {
                                    $this->message->show_message("Verification", "Now, you can login to the website.");
                                }
                                break;
                        }
                        break;
                    case "posts":
                        $post = new controller\postController;
                        switch ($this->action) {
                            case "add":
                                if ($post->addPost($this->arguments[0], $this->arguments[1]) == false) {
                                    $this->message->show_message("404", "we are sorry but the page you are looking for cannot be found");
                                }
                                break;
                            case "show":
                                if ($post->showPost($this->arguments[0]) == false) {
                                    $this->message->show_message("404", "we are sorry but the page you are looking for cannot be found");
                                }
                                break;
                        }
                        break;
                }
            } else {
                $this->message->show_message("404", "we are sorry but the page you are looking for cannot be found");
            }
        } else {
            $this->message->show_message("404", "we are sorry but the page you are looking for cannot be found");
        }
    }

}
