<?php

/*

 * 
 * 
 */

/**
 * Description of AbstractView
 * This is post view to show posts table to members
 * @author Ali Mohtasham Gilani <ali.mohtasham.g@gmail.com>
 */

namespace app\view;

use app\classes\View;
use app\classes\elements\Table;
use app\classes\elements\Predefined_Forms;
use app\classes\elements\Pagination;

class posts_view extends View {

    /**
     *
     * @var Mixed[] $arguments
     * @var integer $post_count 
     * @var integer $items 
     * @var integer $page 
     */
    protected $arguments, $post_count, $items, $page;

    /**
     * Initializing View and Store a file in cache folder and include it
     * @param Mixed[] $params contains table information, number of items per page
     * number of pages and posts from database 
     */
    public function __construct($params) {

        $this->arguments["header"] = ["#", "Name", "Date", "email", "content"];
        $this->arguments["title"] = "Basic Forum";
        if (empty($params)) {
            $this->arguments["body"] = array();
        } else {
            $this->arguments["body"] = $params["database_result"];
            $this->items =$params["items"];
            $this->page = $params["page"];
        }
        $this->post_count = $params["post_count"];
        $this->setHeader();
        $this->setBody();
        $cache_file = fopen(__SITE_PATH . "/src/app/cache/post_view.html", "w") or die("Unable to open file!");
        fwrite($cache_file, $this->setHeader() . $this->setBody() . $this->setFooter());
        fclose($cache_file);
        include __SITE_PATH . "/src/app/cache/post_view.html";
    }

    /**
     * Makes body tag of HTML code
     * @return string
     */
    public function setBody() {
        $table = new Table();
        $form = new Predefined_Forms();
        $body = "<body>";
        $body.=$this->setMenu();
        $body.=$form->post_form();
        $body.=$table->create_table($this->arguments);
        $body.=$this->set_pagination($this->post_count, $this->items, $this->page);
        $body.="</body></html>";
        return $body;
    }

    /**
     * Makes pagination  HTML code
     * @return string
     */
    private function set_pagination($post_count, $items, $page) {
        $page_bar_code = '';
        $page_bar = new Pagination();
        if (($post_count % $items) == 0) {
            $page_count = $post_count / $items;
        } else {
            $page_count = $post_count / $items + 1;
        }
        $page_bar_code .= $page_bar->set_page_bar($page_count, $page, "./posts-show");
        return $page_bar_code;
    }

}
