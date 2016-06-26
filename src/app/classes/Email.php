<?php

/*

 * 
 * 
 */

/**
 * Description of email
 * This class is used to send email
 * 
 * @author Ali Mohtasham Gilani <ali.mohtasham.g@gmail.com>
 */

namespace app\classes;

use Swift_Message;
use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_TransportException;
use app\classes\JSON;

class Email {

    /**
     *
     * @var string
     */
    private $email_smtp_server;

    /**
     *
     * @var string
     */
    private $email_username;

    /**
     *
     * @var string
     */
    private $email_name;

    /**
     *
     * @var string
     */
    private $email_password;

    /**
     *
     * @var integer
     */
    private $email_server_port;

    /**
     *
     * @var string  
     */
    private $security;

    /**
     *
     * @var Swift_Message
     */
    protected $email_message;

    /**
     * Loads email specification form "config/config.json"
     */
    private function loadConfig() {
        $json = new JSON();
        $init_email = $json->readFile(__SITE_PATH . "\config\config.json");
        $this->email_name = $init_email["name"];
        $this->email_server_name = $init_email["email_server_name"];
        $this->email_server_port = $init_email["email_server_port"];
        $this->email_username = $init_email["email_username"];
        $this->email_password = $init_email["email_password"];
        $this->security = $init_email["security"];
    }

    /**
     * Initializes essential variables
     */
    public function __construct() {
        $this->loadConfig();
    }

    /**
     * Sends Email
     * @return boolean
     */
    public function sendEmail() {
        try {// Create the Transport
            $transport = Swift_SmtpTransport::newInstance($this->email_server_name, $this->email_server_port, $this->security)
                    ->setUsername($this->email_username)
                    ->setPassword($this->email_password)
            ;
            $mailer = Swift_Mailer::newInstance($transport);
            $result = $mailer->send($this->email_message);
            return $result;
        } catch (Swift_TransportException $e) {
            echo $e->getMessage();
            return false;
        }
        return true;
    }

    /**
     * Initializes essential variables of message
     * @param string $message_subject
     * @param string $message_header
     * @param string $message_content
     * @param string $from_email_address
     * @param string $from_name
     * @param string $to_name
     * @param string $to_email_address
     * @return void
     */
    public function setMessage($message_subject, $message_header, $message_content, $to_name, $to_email_address) {
        $this->email_message = Swift_Message::newInstance($message_subject)
                ->setFrom(array($this->email_username => $this->email_name))
                ->setTo(array($to_email_address => $to_name))
                ->setBody($this->setBody($message_header, $message_content), 'text/html');
    }

    /**
     * Makes HTML code of Email
     * @param string $header
     * @param string $message
     * @return string
     */
    private function setBody($header, $message) {
        $body = "        
            <html>
            <body>
            <div  style=' font-family: Verdana, Geneva, sans-serif; color: #333;'>
            <table width='100%' border='0' cellspacing='0' cellpadding='0' bgcolor='#F7F7F7' color='#666'>
                <tr >
                    <td height='80' colspan='3' style='border-bottom:2px solid #0066FF;'>
                       <h1>" . $header . "</h1>
                    </td>
                </tr>
                <tr>
                    <td height='47' colspan='3' align='left' valign='middle'>
                    <p>" .
                $message
                . "</p>
                    </td>
                </tr>
                <tr>
                    <td height='26' colspan='3' align='center' valign='middle'>&nbsp;</td>
                </tr>
                <tr>
                    <td height='23' colspan='3' align='center' valign='middle'>&nbsp;</td>
                </tr>
                <tr>
                    <td height='70' colspan='3' bgcolor='#E7E7E7' style='border-top:2px solid #0066FF;'>
                    <ul style='position: relative;float:left;padding-top:10px;'>
                        <li style='display:inline-table;list-style-type: none;margin:0; padding: 0;'> <span class='copyright'>Copyright &copy; </span>
                        </li>
                        <li style='display:inline-table;list-style-type: none;margin:0; padding: 0;'><a href='https://www.linkedin.com/in/ali-mohtasham-a75a2886' >Designed by Ali Mohtasham Gilani 2016</a>
                        </li>
                    </ul>
                    </td>
                </tr>
            </table>
            </div>
            </body>
            </html>
        ";
        return $body;
    }

}
