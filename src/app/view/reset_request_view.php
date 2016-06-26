<?php

/*

 * 
 * 
 */

/**
 * Description of AbstractView
 * This is reset request view comes after clicking on "Forgot Your Password?"
 * @author Ali Mohtasham Gilani <ali.mohtasham.g@gmail.com>
 */

namespace app\view;

use app\classes\View;
use app\classes\elements\Predefined_Forms;

class reset_request_view extends View {

    /**
     *
     * @var string 
     */
    protected $arguments;

    /**
     * Initializing View and Store a file in cache folder and include it
     * @param string $params error messages
     */
    public function __construct($params) {
        $this->arguments = $params;
        $this->setHeader();
        $this->setBody();
        $cache_file = fopen(__SITE_PATH . "/src/app/cache/reset_request_view.html", "w") or die("Unable to open file!");
        fwrite($cache_file, $this->setHeader() . $this->setBody() . $this->setFooter());
        fclose($cache_file);
        include __SITE_PATH . "/src/app/cache/reset_request_view.html";
    }

    /**
     * Makes body tag of HTML code
     * @return string
     */
    public function setBody() {

        $form = new Predefined_Forms();
        $body = "<body>";
        $body.=$this->setMenu();
        $body.="
                       <center>
                       <img src='assets/images/login.png' style='margin:100px 0 20px 0;' />";
        if ($this->arguments != null) {
            $body.="<br /><p class='error'>" . $this->arguments . "<p/><br />";
        }
        $body.=$form->reset_password_form();

        $body.="</center></body></html>";
        return $body;
    }

}
