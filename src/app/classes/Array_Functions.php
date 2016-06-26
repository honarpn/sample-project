<?php

/*

 * 
 * 
 */

/**
 * Description of Array_Functions
 * contains some functions related to array operations 
 * 
 * @author Ali Mohtasham Gilani <ali.mohtasham.g@gmail.com>
 */

namespace app\classes;

class Array_Functions {

    /**
     * Makes an array from indices of another array
     * @param mixed[] $array
     * @return mixed[] contains indices of $array
     */
    public static function grab_index($array) {

        $index_array = array();
        if ($array == null) {
            return $index_array;
        }
        foreach ($array as $key => $data) {
            $index_array[] = $key;
        }
        return $index_array;
    }

    /**
     * If an Index existence in an array returns its value
     * @param mixed[] $array
     * @param string|integer $index
     * @return mixed|null value of $index in $array
     */
    public static function check_Index_Existence($array, $index) {
        $indices = array();
        foreach ($array as $key => $content) {
            $indices[] = $key;
        }
        if (in_array($index, $indices)) {
            return $array[$index];
        } else {
            return null;
        }
    }

}
