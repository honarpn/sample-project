<?php

/*

 * 
 * 
 */

/**
 * Description of postController
 *
 * @author Ali Mohtasham Gilani <ali.mohtasham.g@gmail.com>
 */

namespace app\controller;

use app\classes\Controller;
use app\classes\PDO_Database;

class postController extends Controller {

    /**
     * Adds post to database
     * @param string $name
     * @param string $post
     * @return boolean
     */
    public function addPost($name, $post) {
        if ($this->user_email != null) {
            $db = new PDO_Database();
            if ($db->insert_db("posts", array('Email', 'Name', 'Content', 'Created'), array($this->user_email, $name, $post, date("y-m-d")))) {
                header("location:./");
            } else {
                return false;
            }
        }
    }

    /**
     * load post from database and send them to post_view to render
     * @param integer $page
     * @param integer $items is number of items per page
     * @return boolean
     */
    public function showPost($page = 1, $items = 10) {

        if ($this->user_email != null) {

            $db = new PDO_Database();

            $post_count = $db->table_row_count("posts");

            $result = $db->select_db("posts", '*', 'Order by Created desc LIMIT ' . $items . ' OFFSET ' . ($page - 1) * $items, false);
            $arguments = ["database_result" => $result, "post_count" => $post_count, "page" => $page,"items"=>$items];
            $this->showView("posts_view", $arguments);
            return true;
        } else {
            header("location:./");
        }
    }

}
