<?php

/*

 * 
 * 
 */

/**
 * Description of Group_Element:
 * This Class Makes form-group HTML code 
 * 
 *
 * @author Ali Mohtasham Gilani <ali.mohtasham.g@gmail.com>
 */

namespace app\classes\elements;

use app\classes\JSON;
use app\classes\Array_Functions;

class Group_Element {

    /**
     *
     * @var string[] contains loaded values from "config/icons.json" 
     */
    private $elements_icons;

    /**
     *
     * @var string[] contains those element types defined in "config/icons.json" 
     */
    private $elements_types;

    public function __construct() {
        $this->load_icon();
    }

    /**
     * Loads "config/icons.json" file
     */
    private function load_icon() {
        $json = new JSON();
        $this->elements_icons = $json->readFile(__SITE_PATH . "\config\icons.json");
        $this->elements_types = Array_Functions::grab_index($this->elements_icons);
    }

    /**
     * Make HTML code of form-group DIV
     * @param string $content HTML code of Input element
     * @param string $type for indicating suitable icon
     * @return string HTML code of form-group DIV
     */
    public function make_input_group($content, $type) {
        if (in_array($type, $this->elements_types)) {
            $div = "
                <div class=\"form-group\" >
                   <div class=\"input-group\" style='padding:0;' >
                       <span class='input-group-addon' >";

            $div.="<span class='glyphicon glyphicon-" . $this->elements_icons[$type] . "' ></span>";

            $div.="</span>";
            $div.=$content;
            $div .= "</div>
                 </div>";
        }
        return $div;
    }

}
