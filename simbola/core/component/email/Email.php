<?php

namespace simbola\core\component\email;

include_once 'phpmailer/class.phpmailer.php';

/**
 * Email component definitions
 *
 * @author Faraj
 */
class Email extends \simbola\core\component\system\lib\Component {

    /**
     * PHPMailer object
     * @var \PHPMailer
     */
    private $mailer;

    /**
     * Setup deafult data
     */
    public function setupDefault() {
        $this->setParamDefault('SMTP', null);
        $this->setParamDefault('GENERAL', array());
        $this->setParamDefault('DEBUG', false);        
    }
    
    /**
     * Initialize the component
     */
    public function init() {        
        $this->mailer = new \PHPMailer($this->params['DEBUG']);
        if (isset($this->params['SMTP'])) {
            $this->mailer->IsSMTP();
            $this->mailer->Host = parent::GetValue($this->params['SMTP'], 'Host', 'localhost');
            $this->mailer->SMTPDebug = parent::GetValue($this->params['SMTP'], 'SMTPDebug', 2);                     // enables SMTP debug information (for testing)
            $this->mailer->SMTPAuth = parent::GetValue($this->params['SMTP'], 'SMTPAuth', true);
            $this->mailer->Port = parent::GetValue($this->params['SMTP'], 'Port', 26);
            $this->mailer->Username = parent::GetValue($this->params['SMTP'], 'Username', '');
            $this->mailer->Password = parent::GetValue($this->params['SMTP'], 'Password', '');
        } else {
            //use normal PHP mails
        }        
        $this->mailer->From = parent::GetValue($this->params['GENERAL'], 'From', '');
        $this->mailer->FromName = parent::GetValue($this->params['GENERAL'], 'FromName', '');
    }

    /**
     * Send email
     * 
     * @param array $email Email definitions
     *                     data  ( Address => email / array of emails
     *                             From => email / array of emails
     *                             ReplyTo => email
     *                             Subject => email subject
     *                             AltBody => alt body information
     *                             Msg => message
     *                             Attachment => attachment / array of attachment );
     */
    public function send($email) {
        $address = is_array($email['Address']) ? $email['Address'] : array($email['Address']);        
        $this->mailer->ClearAllRecipients();
        foreach ($address as $name => $address) {
            $this->mailer->AddAddress($address, isset($name) ? $name : "");
        }
        if (isset($email['From'])) {
            $from = is_array($email['From']) ? $email['From'] : array($email['From']);
            foreach ($from as $name => $address) {
                $this->mailer->SetFrom($address, isset($name) ? $name : "");
                break;
            }
        }
        if (!isset($email['ReplyTo']) && isset($email['From'])) {
            $email['ReplyTo'] = $email['From'];
        }
        if (isset($email['ReplyTo'])) {
            $reply_to = is_array($email['ReplyTo']) ? $email['ReplyTo'] : array($email['ReplyTo']);
            foreach ($reply_to as $name => $address) {
                $this->mailer->AddReplyTo($address, isset($name) ? $name : "");
            }
        }
        $this->mailer->Subject = isset($email['Subject']) ? $email['Subject'] : "";
        $this->mailer->AltBody = isset($email['AltBody']) ? $email['AltBody'] : 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
        $this->mailer->MsgHTML(isset($email['Msg']) ? $email['Msg'] : "");
        if (isset($email['Attachment']) && is_array($email['Attachment'])) {
            foreach ($email['Attachment'] as $filename) {
                $this->mailer->AddAttachment($filename);
            }
        }
        $this->mailer->Send();
    }

}

?>
