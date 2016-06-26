<?php

/*

 * 
 * 
 */

/**
 * Description of Elements:
 * This class load attributes.json file which consists of main attributes for 
 * several type of html elements and produce attributes of a tag as a string
 * value 
 *
 * @author Ali Mohtasham Gilani <ali.mohtasham.g@gmail.com>
 */

namespace app\classes\elements;

use app\classes\JSON;
use \app\classes\Array_Functions;

class Elements {

    /**
     *
     * @var string[]|null $form_elements contains loaded elements 
     */
    private $form_elements = null;

    /**
     *
     * @var string[]|null $form_elements_types contains types of elements which 
     * loaded to $form_elements
     */
    private $form_elements_types = null;

    public function __construct() {
        $this->load_attributes();
    }

    /**
     * Loads attributes.json to $form_elements and collects elements type into the 
     * $form_elements_types array
     */
    private function load_attributes() {
        $json = new JSON();
        $this->form_elements = $json->readFile(__SITE_PATH . "\config\atrributes.json");
        $this->form_elements_types = Array_Functions::grab_index($this->form_elements);
    }

    /**
     * Produce attributes code for an element
     * 
     * @param string $type indicates element type (email,text,password,...) which
     *  should be initiated in attributes.json
     * 
     * @param string[]|null $override_amounts makes it possible to change predefined 
     * values of attributes in json file.
     * @return string[] returns attribute code of specified element(html tag) and text
     */
    public function set_attributes($type, $override_amounts = null) {
        $result = array();
        $set = '';
        $override_amount_fields = Array_Functions::grab_index($override_amounts);

        if (in_array($type, $this->form_elements_types)) {
            foreach ($this->form_elements[$type] as $key => $default_amount) {
                if ($type == 'checkbox' && $key == 'text') {
                    $result['text'] = $default_amount;
                } else {
                    if (in_array($key, $override_amount_fields)) {
                        $set.=" " . $key . "='" . $override_amounts[$key] . "'";
                    } else {
                        $set.=" " . $key . "='" . $default_amount . "'";
                    }
                }
            }
        }
        $result["attributes"] = $set;
        return $result;
    }

}
