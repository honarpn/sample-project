<?php



/**
 * Description of AbstractView
 * This is Parent of all Views in our Model View Controller(MVC) Design Pattern 
 * @author Ali Mohtasham Gilani <ali.mohtasham.g@gmail.com>
 */

namespace app\classes;

use app\classes\JSON;
use app\classes\Token;

abstract class View {

    /**
     *
     * @var string[] 
     */
    protected $css_files;

    /**
     *
     * @var string[] 
     */
    protected $js_files;

    /**
     *
     * @var string[] 
     */
    protected $meta_info ;

    /**
     *
     * @var string
     */
    protected $icon = "<link rel='icon' href='assets/images/favicon.ico' type='image/x-icon'> \n";

    /**
     *
     * @var string
     */
    protected $header ;

    /**
     *
     * @var string 
     */
    protected $body;

    /**
     * Makes head tag content
     * @param string $header_extra for add something more than default config
     * @return string
     */
    protected function setHeader($header_extra = '') {
        $this->header = "<!DOCTYPE html> \n <html> \n <head> \n <meta charset='utf-8'> \n <title>Log-in Page Project</title>";
        $this->loadConfig();
        $this->addMeta();
        $this->addJS();
        $this->addCSS();
        $this->header.=$header_extra;
        $this->header.="</head>";
        return $this->header;
    }

    /**
     * Make body tag content
     * @return string HTML code
     */
    abstract public function setBody();

    /**
     * Makes HTML code of Main footer
     * @return string
     */
    protected function setFooter() {
        $footer = "<div class='footer'>
                   <center>
                    <a href='https://www.linkedin.com/in/ali-mohtasham-a75a2886' >Designed by <strong> Ali Mohtasham Gilani </strong>  All rights reserved.</a>
                   </center>
                </div>
                ";
        return $footer;
    }

    /**
     * Makes HTML code of Main Menu
     * @return string
     */
    public function setMenu() {
        $loged_in = false;
        if (isset($_COOKIE['query_key']) && isset($_COOKIE['rememberme'])) {
            $loged_in = explode("@", Token::verifyToken());
        }
        $menu = "
 <nav class='navbar navbar-inverse navbar-fixed-top' role='navigation'>
    <div class='container'>
        <div class='navbar-header'>
            <button type='button' class='navbar-toggle' data-toggle='collapse' data-target='#menu_bar' >
                <span class='sr-only'>Toggle navigation</span>
                <span class='icon-bar' style='background-color: black;'></span>
                <span class='icon-bar' style='background-color: black;'></span>
                <span class='icon-bar' style='background-color: black;'></span>
            </button>
            <a class='navbar-brand' ><img src='assets/images/logo.png' height='30px'></a>
        </div>

        <div class='collapse navbar-collapse' id='menu_bar' >
            <ul class='nav navbar-nav navbar-right' >";
        if ($loged_in == false) {
            $menu .=" <li>
                    <a href='./login' >Login</a>
                </li>
                <li>
                    <a href='./signup' >Signup</a>
                </li>";
        } else {
            $menu .= "  
                <li>
                 <a>Welcome " . $loged_in[0] . "</a>" .
                    "</li>
                <li>
                    <a href='./posts' >Posts</a>
                </li>
                <li>
                    <a href='./change' >Change Password</a>
                </li>
                <li>
                    <a  href='./user-logout' >logout</a>
                </li>";
        }

        $menu .="</ul>
        </div>
    </div>
</nav>
<div  style='height:100px;width:100%;position:relative;'></div>
";
        return $menu;
    }

    /**
     * Load information from "config/view.json"
     */
    protected function loadConfig() {
        $json = new JSON();
        $viewConfig = $json->readFile(__SITE_PATH . "\\config\\view.json");
        $this->meta_info = $viewConfig['meta'];
        $this->js_files = $viewConfig['javascript'];
        $this->css_files = $viewConfig['css'];
    }

    /**
     * Add CSS files from view.json
     */
    protected function addCSS() {
        foreach ($this->css_files as $css_file) {
            $this->header.="<link rel='stylesheet' href='" . __ASSETS_PATH . "/" . $css_file . "'/> \n";
        }
    }

    /**
     * Add javascript files from view.json
     */
    protected function addJS() {
        foreach ($this->js_files as $js_file) {
            $this->header.="<script type='text/javascript' src='" . __ASSETS_PATH . "/" . $js_file . "'></script> \n";
        }
    }

    /**
     * Add Metadata from view.json
     */
    protected function addMeta() {
        foreach ($this->meta_info as $key => $meta) {
            $this->header.="<meta name='" . $key . "'  content='" . $meta . "' /> \n";
        }
    }

}
