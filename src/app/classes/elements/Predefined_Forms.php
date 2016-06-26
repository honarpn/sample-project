<?php

/*

 * 
 * 
 */

/**
 * Description of Predefined_Forms
 * This class contains some popular forms definition 
 * 
 * @author Ali Mohtasham Gilani <ali.mohtasham.g@gmail.com>
 */

namespace app\classes\elements;

use app\classes\elements\Form;
use app\classes\elements\Elements;

class Predefined_Forms {

    /**
     *
     * @var Elements makes HTML tag attribute codes 
     */
    private $form_elements;

    public function __construct() {
        $this->form_elements = new Elements();
    }

    /*
     * creates login form
     * @return string returns HTML code
     */

    public function login_form() {
        $form_elements = array();
        $form_elements[] = $this->form_elements->set_attributes("email");
        $form_elements[] = $this->form_elements->set_attributes("password");
        $form_elements[] = $this->form_elements->set_attributes("checkbox");
        $form_elements[] = $this->form_elements->set_attributes("submit");
        $form = new Form($form_elements, './user-login', "login-form", array("email", "password"));
        return $form->form_maker();
    }

    /*
     * creates signup form
     * @return string returns HTML code
     */

    public function signup_form() {
        $form_elements = array();
        $form_elements[] = $this->form_elements->set_attributes("email");
        $form_elements[] = $this->form_elements->set_attributes("password");
        $form_elements[] = $this->form_elements->set_attributes("password", ["name" => "confirm", "id" => "confirm", "placeholder" => "Please confirm Your password"]);
        $form_elements[] = $this->form_elements->set_attributes("submit");
        $form = new Form($form_elements, './user-signup', "signup-form", array("email", "password", "password"));
        return $form->form_maker();
    }

    /*
     * creates change password form
     * @return string returns HTML code
     */

    public function change_password_form() {
        $form_elements = array();
        $form_elements[] = $this->form_elements->set_attributes("password", ["name" => "oldpassword", "id" => "oldpassword", "placeholder" => "Please Enter current password"]);
        $form_elements[] = $this->form_elements->set_attributes("password");
        $form_elements[] = $this->form_elements->set_attributes("password", ["name" => "confirm", "id" => "confirm", "placeholder" => "Please confirm Your password"]);
        $form_elements[] = $this->form_elements->set_attributes("submit");
        $form = new Form($form_elements, './user-change', "changePassword-form", array("password", "password", "password"));
        return $form->form_maker();
    }

    /*
     * creates reset password form
     * @return string returns HTML code
     */

    public function new_password_form() {
        $form_elements = array();
        $form_elements[] = $this->form_elements->set_attributes("password");
        $form_elements[] = $this->form_elements->set_attributes("password", ["name" => "confirm", "id" => "confirm", "placeholder" => "Please confirm Your password"]);
        $form_elements[] = $this->form_elements->set_attributes("submit");
        $form = new Form($form_elements, './user-reset', "newPassword-form", array("password", "password"));
        return $form->form_maker();
    }

    /*
     * creates reset request password form
     * @return string returns HTML code
     */

    public function reset_password_form() {
        $form_elements = array();
        $form_elements[] = $this->form_elements->set_attributes("email");
        $form_elements[] = $this->form_elements->set_attributes("submit");
        $form = new Form($form_elements, './user-resetrequest', "resetRequestPassword-form", array("email"));
        return $form->form_maker();
    }

    /*
     * creates post text form
     * @return string returns HTML code
     */

    public function post_form() {
        $form_elements = array();
        $form_elements[] = $this->form_elements->set_attributes("text", ["name" => "name", "id" => "name", "placeholder" => "Please Enter Your Name"]);
        $form_elements[] = $this->form_elements->set_attributes("text", ["name" => "post", "id" => "post", "placeholder" => "Please Enter Your Text"]);
        $form_elements[] = $this->form_elements->set_attributes("submit");
        $form = new Form($form_elements, './posts-add', "post-form", array("user", "comment"));
        return $form->form_maker("100%");
    }

}
