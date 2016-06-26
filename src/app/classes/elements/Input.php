<?php

/*

 * 
 * 
 */

/**
 * Description of input :
 * This Class is Produced to make html input elements 
 * 
 * @author Ali Mohtasha Gilani <ali.mohtasham.g@gmail.com>
 */

namespace app\classes\elements;

use app\classes\elements\Group_Element;

class Input {

    /**
     * input_code gets string of attributes and text to produce input tag code
     * @param string $attr is html code of tag attributes (made by Elements)
     * @param string $type is optional for setting icon for input element
     * @param string $text is optional
     * @return string produce code
     */
    public function input_code($attr, $type = null, $text = null) {
        if ($type != null) {
            $group_element = new Group_Element();
            $group = $group_element->make_input_group("<input " . $attr . "/>" . $text . "</input>", $type);
        } else {
            return "<input " . $attr . "/><strong>" . $text . "</strong></input>";
        }
        return $group;
    }

}
