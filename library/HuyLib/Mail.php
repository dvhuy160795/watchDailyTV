<?php
class HuyLib_Mail extends Zend_Controller_Plugin_Abstract
{
    private $_mailSenderDefaultAddress = "dvhuy160795@gmail.com";
    private $_mailSenderDefaultNameAddress = "Van Huy";
    private $_mailSenderDefaultPass = "dvh160795";
    
    public function send() {
        $mail = new Zend_Mail();
    	$sendOk = true;
  		$config = array(
              'ssl'      => 'ssl',
              'auth'     => 'login',
              'username' => $this->_mailSenderDefaultAddress,
              'password' => $this->_mailSenderDefaultPass,
              'port'     => 587
          );

  		$transport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);
  		$sendNow   = 
  		$mail->setBodyHtml("sadfsadf")
            ->setFrom($this->_mailSenderDefaultAddress,$this->_mailSenderDefaultNameAddress)
            ->addTo("asdfsad")
            //->addCc(["huydv@vnext.com.vn","channelno2160795@gmail.com"])
            ->setSubject("Asdfsd")
            ->addHeader('X-MailGenerator', 'MyCoolApplication');
    var_dump($transport);die;
        return $mail->send($transport);
    }
}
