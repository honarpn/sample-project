<?php

/*

 * 
 * 
 */

/**
 * Description of JSON
 * Reads and Writes from/to JSON files
 * @author Ali Mohtasham Gilani <ali.mohtasham.g@gmail.com>
 */

namespace app\classes;

class JSON {

    /**
     * 
     * @param string $Data
     * @param string $file_path
     * @return boolean
     */
    public function writeFile($Data, $file_path) {
        try {
            $file = fopen($file_path, 'w') or die("can't open file");
            fwrite($file, str_replace("\\", "", $Data));
            fclose($file);
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * 
     * @param string $file_path
     * @return boolean|Mixed[]
     */
    public function readFile($file_path) {
        try {
            $jsonFile = str_replace("\\", "/", $file_path);
            $file = fopen($jsonFile, 'r') or die("can't open file");
            $obj = fread($file, filesize($jsonFile));
            fclose($file);
            return json_decode($obj, true);
        } catch (Exception $e) {
            return false;
        }
    }

}
