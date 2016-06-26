<?php

/*

 * 
 * 
 */

/**
 * Description of Table
 * This class produces a HTML Table 
 * 
 * @author Ali Mohtasham Gilani <ali.mohtasham.g@gmail.com>
 */

namespace app\classes\elements;

class Table {

    /**
     * 
     * @param string[] $data contains three array with Title,Header and body index
     * @return string HTML code of table
     */
    public function create_table($data) {

        $table = "
            <div class='container' style='margin-top:30px;'>
            <div class=\"panel panel-default\">
                        <div class=\"panel-heading\">" . $data["title"] . "</div>
                        <div class=\"panel-body\">
                    
                        </div>
                        <table class='table table-striped'>";
        $table.=$this->set_header($data["header"]);
        $table.=$this->set_body($data["body"]);
        $table.="</table></div>";
        return $table;
    }

    /**
     * 
     * @param string[] $header array of header values
     * @return string HTML code of table header
     */
    private function set_header($header) {
        $header_code = ' <thead><tr>';
        foreach ($header as $column_head) {
            $header_code .= "<th>" . $column_head . "</th>";
        }
        $header_code.='</tr></thead>';
        return $header_code;
    }

    /**
     * 
     * @param string $column_content
     * @return string HTML code of table column
     */
    private function add_column($column_content) {
        $column_code = '<td>' . $column_content . '</td>';
        return $column_code;
    }

    /**
     * 
     * @param string[] $row_contents array of row values
     * @return string HTML code of table row
     */
    private function add_row($row_contents) {
        $row_code = '<tr>';
        foreach ($row_contents as $column_content) {
            $row_code .= $this->add_column($column_content);
        }
        $row_code.='</tr>';
        return $row_code;
    }

    /**
     * 
     * @param string[] $body_contents array of all row values
     * @return string HTML code of table body
     */
    private function set_body($body_contents) {
        $body = "<tbody>";
        if ($body_contents != null) {
            foreach ($body_contents as $row_contents) {
                $body .= $this->add_row($row_contents);
            }
        }
        $body.='</tbody>'
                . '</div>';
        return $body;
    }

}
