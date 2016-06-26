<?php


/**
 * Description of defaultController
 * This is default controller and render safe pages
 * @author Ali Mohtasham Gilani <ali.mohtasham.g@gmail.com>
 */

namespace app\controller;

use app\classes\Controller;

class defaultController extends Controller {

    /**
     * 
     * @param string|null $param
     * @param string|null $argument
     */
    public function go_to_page($param = null, $argument = null) {
        switch ($param) {
            case "signup":
                if ($this->user_email == null) {
                    $this->showView("signup_view", $argument);
                } else {
                    header("location:./");
                }
                break;
            case "login":
                if ($this->user_email == null) {
                    $this->showView("login_view", $argument);
                } else {
                    header("location:./");
                }
                break;
            case "change":
                if ($this->user_email != null) {
                    $this->showView("change_password_view", $argument);
                } else {
                    header("location:./");
                }
                break;
            case "resetrequest":
                if ($this->user_email == null) {
                    $this->showView("reset_request_view", $argument);
                } else {
                    header("location:./");
                }
				break;
            case "reset":
                if ($this->user_email == null) {
                    $this->showView("new_password_view", $argument);
                } else {
                    header("location:./");
                }
                break;
            case "message":
                $this->showView("message_view", $argument);
                break;
            default:
                //check if someone logged in or not
                if ($this->user_email != null) {
                    $this->forward("postController");
                } else {
                    $this->showView("login_view");
                }
                break;
        }
    }

}
