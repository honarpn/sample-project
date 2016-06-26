<?php

/*

 * 
 * 
 */

/**
 * Description of Pagination
 * This class makes the html code of Pagination panel
 * 
 * @author Ali Mohtasham Gilani <ali.mohtasham.g@gmail.com>
 */

namespace app\classes\elements;

class Pagination {

    /**
     * 
     * @param integer $page_count shows the number of pages
     * @param integer $current_page
     * @param string $link_prefix is used to make URL for page links 
     * @return string returns HTML code
     */
    public function set_page_bar($page_count, $current_page, $link_prefix) {
        $page_count=(integer)$page_count;
        $pre_page = 1;
		if($page_count==0){$next_page=1;}
        else{$next_page = $page_count;}
        if ($current_page - 1 >= 1) {
            $pre_page = $current_page - 1;
        }
        if ($current_page + 1 <= $page_count) {
            $next_page = $current_page + 1;
        }
        $bar = "<center><nav>
                    <ul class='pagination'><li";
        if ($current_page == 1) {
            $bar.=" class='disabled'> <a  aria-label='Previous'>";
        } else {
            $bar.="> <a href='" . $link_prefix . "-" . $pre_page . "' aria-label='Previous'>";
        }


        $bar.="<span aria-hidden='true'>&laquo;</span>
                        </a>
                       </li>";
        for ($i = 1; $i <= $page_count; $i++) {
            $bar.= "<li";
            if ($i == $current_page) {
                $bar.=" class='active'><a>" . $i . "</a></li>";
            } else {
                $bar.="><a href='" . $link_prefix . "-" . $i . "'>" . $i . "</a></li>";
            }
        }
        $bar.="<li";
        if ($current_page == $page_count) {
            $bar.=" class='disabled'> <a aria-label='Next'>";
        } else {
            $bar.=" > <a href='" . $link_prefix . "-" . $next_page . "' aria-label='Next'>";
        }

        $bar.=" <span aria-hidden='true'>&raquo;</span>
                         </a>
                       </li>
                     </ul>
                   </nav></center>";
        return $bar;
    }

}
