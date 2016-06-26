<?php

/*

 * 
 * 
 */

/**
 * Description of AbstractView
 * This is message view which shows any text gets as a parameter 
 * @author Ali Mohtasham Gilani <ali.mohtasham.g@gmail.com>
 */

namespace app\view;

use app\classes\View;
use app\classes\elements\Predefined_Forms;

class message_view extends View {

    /**
     *
     * @var string[]
     */
    protected $arguments;

    /**
     * Initializing View and Store a file in cache folder and include it
     * @param string[] $params title and messages
     */
    public function __construct($params = null) {
        $this->arguments = $params;
        $this->setHeader();
        $this->setBody();
        $cache_file = fopen(__SITE_PATH . "/src/app/cache/message_" . $params["title"] . "_view.html", "w") or die("Unable to open file!");
        fwrite($cache_file, $this->setHeader() . $this->setBody() );
        fclose($cache_file);
        include __SITE_PATH . "/src/app/cache/message_" . $params["title"] . "_view.html";
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
            <body>
                 <center>
                     <h1>" . $this->arguments["title"] . "</h1>
                     <h2>" . $this->arguments["message"] . "</h2>
                 </center>
           </body>
           </html>";
        return $body;
    }

}
