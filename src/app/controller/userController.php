<?php


/**
 * Description of userController
 * This class is User Controller and contain all actions related to the user
 * 
 * @author Ali Mohtasham Gilani <ali.mohtasham.g@gmail.com>
 */

namespace app\controller;

use app\classes\PDO_Database;
use app\classes\Token;
use app\classes\Controller;
use app\classes\Email;
use app\classes\Message;

class userController extends Controller {

    /**
     * Generates random string
     * @return string
     */
    protected function generateRandomString() {
        $length = 10;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    /**
     * 
     * @param string $email
     * @param string $password
     * @param string $confirm
     * @return string
     */
    public function signup($email, $password, $confirm) {

        $email = strtolower($email);
        $confirmation = md5($this->generateRandomString());
        $db = new PDO_Database();
        $result = $db->select_db("users", array('Email'), "Email='" . $email . "'");
        if ($result == false) {
            if ($password === $confirm) {
                if ($db->insert_db("users", array('Email', 'Password', 'Token', 'Confirmed'), array($email, $this->encryptPassword($password), hash_hmac('sha256', "'" . $email, $this->generateRandomString() . "'"), $confirmation))) {
                    $message = new Message();
                    $confirmation_url = $_SERVER['HTTP_HOST'] . __PREFIX . "user-verify-" . $confirmation;
                    $confirm_email = new Email();
                    $confirm_email->setMessage("Hi!", "Verify your email address", "
                                                                   <p>To finish setting up your account, we just need to make sure this email address is yours.</p>
                                                                    <a href='" . $confirmation_url . "' >Click hear to verify your email</a>

                                                                  <p>If you didn't make this request, Just ignore this email.</p>
                                                                  Thanks
                                                                  ", 'Member', $email);
                    $confirm_email->sendEmail();
                }
            } else {
                return "Password and Confirm fields were not the same value.";
            }
        } else {
            return "The Email Address already exists.";
        }
        return "done";
    }

    /**
     * 
     * @param string $email
     * @param string $password
     * @param string $remember
     * @return string
     */
    public function login($email, $password, $remember = null) {
        $email = strtolower($email);
        $db = new PDO_Database();
        $result = $db->select_db("users", array('Email', 'Password'), "Email='" . $email . "' and Confirmed=1");
        if ($result != false) {
            if ($this->verifyPassword($result[0]['Password'], $password)) {
                Token::setToken($remember, $email);
                header("location:./");
            } else {
                return "The password is wrong.";
            }
        } else {
            return "The Email address has been not registered / You have not confirmed your account yet";
        }
    }

    /**
     * logout operation
     */
    public function logout() {
        setcookie("query_key", "", time() - 4330000, '/');
        setcookie("rememberme", "", time() - 4330000, '/');
        header("location:./");
    }

    /**
     * 
     * @param string $old_password
     * @param string $new_password
     * @param string $confirm
     * @return string
     */
    public function change_password($old_password, $new_password, $confirm) {
        if ($this->user_email != null) {
            $db = new PDO_Database();
            $result = $db->select_db("users", array('Password'), "Email='" . $this->user_email . "'");
            if ($result != false) {
                if ($this->verifyPassword($result[0]['Password'], $old_password)) {
                    if ($new_password === $confirm) {

                        if ($db->update_db("users", array('Password'), array($this->encryptPassword($new_password)), "Email='" . $this->user_email . "'")) {
                            
                        }
                    } else {
                        return "Password and Confirm fields were not the same value.";
                    }
                } else {
                    return "currents Password is wrong.";
                }
            } else {
                return "The Email address has been not registered.";
            }
        } else {
            header("location:./");
        }
        return "done";
    }

    /**
     * 
     * @param string $verify_token
     * @return boolean
     */
    public function verify_email($verify_token) {
        $db = new PDO_Database();
        if ($db->update_db("users", array('Confirmed'), array("1"), "Confirmed='" . $verify_token . "'")) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 
     * @param string $password
     * @param string $confirm
     * @return string
     */
    public function reset_password($password, $confirm) {
        $data = filter_input_array(INPUT_COOKIE);
        $email = $data['reset_key'];
        if ($password === $confirm) {
            $db = new PDO_Database();
            if ($db->update_db("users", array('Password'), array($this->encryptPassword($password)), "Email='" . $email . "'")) {
                  setcookie("reset_key", "", time() - 4330000, '/');//makes reset link invalid
            }
        } else {
            return "Password and Confirm fields were not the same value.";
        }
        return "done";
    }

    /**
     * Sends user to password reset page
     * @param type $reset_token
     * @return string
     */
    public function new_password($reset_token) {

        if (Token::verifyResetToken($reset_token) != null) {
            $this->showView("new_password_view");
        } else {
            return "410";
        }
    }

    /**
     * Send an Email which contains a reset password link which would be valid for 10 minutes
     * @param string $email
     * @return string
     */
    public function reset_request($email) {
        $email = strtolower($email);
        $db = new PDO_Database();
        $result = $db->select_db("users", array('Password'), "Email='" . $email . "'");
        if ($result != false) {
            $db->delete_db("password_resets", "Email='" . $email . "'");
            setcookie(
                    'reset_key', $email, time() + 600, '/'
            );
            $reset_store_token = hash_hmac('sha256', $email, $this->generateRandomString());
            $db->insert_db("password_resets", array('Email', 'Token'), array($email, $reset_store_token));

            $reset_token = hash_hmac('sha256', $email, $reset_store_token);
            $reset_url = $_SERVER['HTTP_HOST'] . __PREFIX . "user-new-" . $reset_token;
            $reset_email = new Email();
            $reset_email->setMessage("Password reset", "Password reset", "
                                                                   <p>Please use following link to reset the password for your account in <company name>.</p>
                                                                    <a href='" . $reset_url . "' >Click hear to reset your password</a>

                                                                  <p>If you didn't make this request, Just ignore this email.</p>
                                                                  Thanks
                                                                  ", 'Member', $email);

            $reset_email->sendEmail();
        } else {
            return "The Email is not registered";
        }
        return "done";
    }

    /**
     * 
     * @param string $plain_password
     * @return string
     */
    protected function encryptPassword($plain_password) {
        $password = password_hash(
                base64_encode(
                        hash('sha256', $plain_password, true)
                ), PASSWORD_DEFAULT
        );
        return $password;
    }

    /**
     * 
     * @param string $encryptedPassword
     * @param string $plain_password
     * @return boolean
     */
    protected function verifyPassword($encryptedPassword, $plain_password) {
        if (password_verify(
                        base64_encode(
                                hash('sha256', $plain_password, true)
                        ), $encryptedPassword
                )) {
            return true;
        } else {
            return false;
        }
    }

}
