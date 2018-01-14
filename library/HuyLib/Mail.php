<?php
class HuyLib_Mail extends Zend_Controller_Plugin_Abstract
{
    private $_mailSenderDefaultAddress = "dvhuy160795@gmail.com";
    private $_mailSenderDefaultNameAddress = "Van Huy";
    private $_mailSenderDefaultPass = "dvh160795";
    
    public function sendMail($fromAddress = [], $aryListToAddress = [], $bodyHtml = "", $subject = "" ) {
        $mail = new Zend_Mail();

        $userNameAddressFrom = isset($fromAddress['usernameaddress']) ? $fromAddress['usernameaddress'] : $this->_mailSenderDefaultAddress;
        $userPassFrom = isset($fromAddress['userpass']) ? $fromAddress['userpass'] : $this->_mailSenderDefaultPass;
        $userNameFrom = isset($fromAddress['username']) ? $fromAddress['username'] : $this->_mailSenderDefaultNameAddress;
  		  $config = array(
              'ssl'      => 'ssl',
              'auth'     => 'login',
              'username' => $userNameAddressFrom,
              'password' => $userPassFrom,
              'port'     => 465
          );

            $transport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);
            $sendNow   = 
            $mail ->setBodyHtml($bodyHtml)
            ->setFrom($this->_mailSenderDefaultAddress,$this->_mailSenderDefaultNameAddress)
            ->addTo($aryListToAddress)
            ->setSubject($subject)
            ->addHeader('X-MailGenerator', 'MyCoolApplication');
        return $mail->send($transport);
    }

    public function sendMailRegisterUser($aryListToAddress = [], $codeRandom) {
      $bodyHtml = "<h1>Enter the code in the 'Type code from your email' box and click register</h1>
                    <h2>".$codeRandom."</h2>";
      $subject = "[WatchDailyTV] Account registration required";
      return $this->sendMail([], $aryListToAddress, $bodyHtml, $subject);
    }

    public function sendMailForgotPasword($aryListToAddress = [], $codeRandom) {
      $bodyHtml = "<h1>Your password has been changed to</h1>
                    <h2>".$codeRandom."</h2>";
      $subject = "[WatchDailyTV] Alert";
      return $this->sendMail([], $aryListToAddress, $bodyHtml, $subject);
    }
}
