<?php

/*

 * 
 * 
 */

/**
 * Description of Token
 * This class does Token operations related to remembering user, login ,reset password ...
 * 
 * @author Ali Mohtasham Gilani <ali.mohtasham.g@gmail.com>
 */

namespace app\classes;

use app\classes\PDO_Database;

class Token {

    /**
     * Find user's stored token
     * @param string $table
     * @param string $email
     * @return boolean|string
     */
    public static function getStoredToken($table, $email) {
        $db = new PDO_Database();
        $result = $db->select_db($table, array('Token'), "Email='" . $email . "'");
        if ($result != false) {
            return $result[0]['Token'];
        } else {
            return false;
        }
    }

    /**
     * Sets Cookies 
     * @param string $remember
     * @param string $email
     */
    public static function setToken($remember, $email) {
        if ($remember=="remember") {
            $login_time = time() + 4320000; //100 days
        } else {
            $login_time = time() + 43200; //1 day
        }
        $storedToken = self::getStoredToken("users", $email);
        setcookie(
                'query_key', $email, $login_time, '/'
        );
        setcookie(
                'rememberme', hash_hmac('sha256', $email, $storedToken), $login_time, '/'
        );
    }

    /**
     * Verifies that remember-me token is valid or not
     * @return string|null
     */
    public static function verifyToken() {
        if (isset($_COOKIE['query_key']) && isset($_COOKIE['rememberme'])) {
            $data = filter_input_array(INPUT_COOKIE);
            $email = $data['query_key'];
            $rememberme = $data['rememberme'];
            $storedToken = self::getStoredToken("users", $email);
            $valid = hash_equals(
                    $rememberme, hash_hmac('sha256', $email, $storedToken)
            );

            if ($valid == 1) {
                return $email;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    /**
     * Verifies that reset token is valid or not
     * @return string|boolean
     */
    public static function verifyResetToken($reset_token) {
        if (isset($_COOKIE['reset_key']) && $reset_token != null) {
            $data = filter_input_array(INPUT_COOKIE);
            $email = $data['reset_key'];
            $storedToken = self::getStoredToken("password_resets", $email);
            if ($storedToken != false) {
                $valid = hash_equals(
                        $reset_token, hash_hmac('sha256', $email, $storedToken)
                );
            }

            if ($valid == 1) {
                return $email;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

}
