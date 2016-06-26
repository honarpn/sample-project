<?php

/*

 * 
 * 
 */

/**
 * Description of defaultController
 * This is message controller which renders pages which contain information 
 * @author Ali Mohtasham Gilani <ali.mohtasham.g@gmail.com>
 */

namespace app\controller;

use app\classes\Controller;

class messageController extends Controller {

    /**
     * Checks that there is there any produced html file with the same title before
     * and prefers to render cached file instead of producing another one
     * @param string|null $title
     * @param string|null $message
     */
    public function show_message($title = null, $message = null) {


        if (file_exists(__SITE_PATH . "\\src\\app\\cache\\message_" . $title . "_view.html")) {
            include __SITE_PATH . "\\src\\app\\cache\\message_" . $title . "_view.html";
        } else {
            $this->showView("message_view", array("title" => $title, "message" => $message));
        }
    }

}
