<?php

/*

 * 
 * 
 */

/**
 * Description of Form
 * Produces HTML Form code 
 *
 * @author Ali Mohtasham Gilani <ali.mohtasham.g@gmail.com>
 */

namespace app\classes\elements;

use app\classes\elements\Input;
use app\classes\Array_Functions;

class Form {

    /**
     *
     * @var string[] contains attribute codes produced by Elements object
     */
    protected $element_attributes;

    /**
     *
     * @var string indicates form action attribute 
     */
    protected $action;

    /**
     *
     * @var  string indicates form name attribute 
     */
    protected $name;

    /**
     *
     * @var  string indicates form class attribute 
     */
    protected $class;

    /**
     *
     * @var  string indicates form id attribute 
     */
    protected $id;

    /**
     *
     * @var  string[]|null is an array with the same order of form elements to 
     * indicate suitable icon for the element (icon for an element is defined in
     * "config/icons.json" file)
     */
    protected $types;

    public function __construct($element_attributes, $action, $name, $types = null, $id = null, $class = null) {
        $this->element_attributes = $element_attributes;
        $this->action = $action;
        $this->name = $name;
        $this->class = $class;
        $this->types = $types;
        if ($id == null) {
            $this->id = $name;
        }
        if ($id == null) {
            $this->class = $name;
        }
    }

    /**
     * Makes HTML Form 
     * @param string $width sets width of form 
     * @return string returns html code of form element
     */
    public function form_maker($width = "300px") {
        /*
         * @var Input makes html input elements
         */
        $input_element = new Input();
        $form = "<div class='container'><form id='" . $this->id . "' name='" . $this->name . "' action='" . $this->action . "' enctype='application/x-www-form-urlencoded' method='POST' style=\"valign:middle;margin-right:10px;width:" . $width . ";\">";

        foreach ($this->element_attributes as $key => $element_attribute) {
            if ($this->types != null && Array_Functions::check_Index_Existence($this->types, $key)) {
                $form.=$input_element->input_code($element_attribute["attributes"], $this->types[$key]);
            } else {
                if (Array_Functions::check_Index_Existence($element_attribute, "text")) {
                    $form.=$input_element->input_code($element_attribute["attributes"], null, $element_attribute["text"]);
                } else {
                    $form.=$input_element->input_code($element_attribute["attributes"]);
                }
            }
        }

        $form .="</form></div>";
        return $form;
    }

}
