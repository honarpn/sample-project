<?php

namespace app\view;

use app\classes\View;
use app\classes\elements\Predefined_Forms;

class login_view extends View {
    /**
     *
     * @var string 
     */
    protected $arguments;

    /**
     * Initializing View and Store a file in cache folder and include it
     * @param string $params error messages
     */
    public function __construct($params = null) {
        $this->arguments = $params;
        $this->setHeader();
        $this->setBody();
        $cache_file = fopen(__SITE_PATH . "/src/app/cache/login_view.html", "w") or die("Unable to open file!");
        fwrite($cache_file, $this->setHeader() . $this->setBody() . $this->setFooter());
        fclose($cache_file);
        include __SITE_PATH . "/src/app/cache/login_view.html";
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
                       <img src='assets/images/login.png' style='margin:0px 0 20px 0;' />
                       ";
        if ($this->arguments != null) {
            $body.="<br /><p class='error'>" . $this->arguments . "<p/><br />";
        }
        $body.=$form->login_form();

        $body.="<br />"
                . "<a href='./resetrequest'>Forgot Your Password?</a></center></body></html>";
        return $body;
    }

}
