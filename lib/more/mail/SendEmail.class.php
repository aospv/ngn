<?php

class SendEmail {
  
  /**
   * @var PHPMailer
   */
  public $mailer;
  
  public $method;
  
  public $addHostToLinks = true;
  
  public function __construct() {
    $this->mailer = new PHPMailer();
    $this->mailer->SetLanguage('ru');
    $this->mailer->SetFrom(
      Config::getVarVar('mail', 'fromEmail'),
      Config::getVarVar('mail', 'fromName')
    );
    $this->mailer->CharSet  = CHARSET;
    $this->method = Config::getVarVar('mail', 'method');
    if ($this->method == 'smtp') {
      $smtp = Config::getVar('smtp');
      $this->mailer->Mailer = 'smtp';
      $this->mailer->Host = $smtp['server'];
      if (!empty($smtp['port'])) $this->mailer->Port = $smtp['port'];
      if (!empty($smtp['auth'])) {
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $smtp['user'];
        $this->mailer->Password = $smtp['pass'];
      }
    }
    $this->mailer->SMTPDebug = false;
  }

  /**
   * @param   mixed   Email/emails
   * @param   string  Тема письма
   * @param   string  Текст письма
   * @param   bool    HTML/Plain text
   * @return  bool
   */
  public function send($emails, $subject, $message, $html = true) {
    if (EMAIL_ALLOWED === false) {
      LogWriter::v('email', "$subject\n--------------\n$message");
      return true;
    }
    if ($html and $this->addHostToLinks) {
      $message = str_replace("<a href=", "\n<a href=", $message);
      $message = preg_replace('/(href=["\'])(?!http:\/\/)\.*\/*(.*)(["\'])/imu', '$1'.SITE_WWW.'/$2$3', $message);
      $message = preg_replace('/(src=["\'])(?!http:\/\/)\.*\/*(.*)(["\'])/imu', '$1'.SITE_WWW.'/$2$3', $message);
    }
    if (!is_array($emails)) {
      $recipients[0]['email'] = $emails;
      $recipients[0]['name'] = substr($emails, 0, strpos($emails, '@'));
    } else {
      $recipients = $emails;
    }
    if (!$recipients) throw new NgnException('$recipients not defined');
    ////////////////////// mail() ///////////////
    if ($this->method != 'smtp') {
      if ($html) {
        $headers  = 'Content-type: text/html; charset='.CHARSET."\r\n";
        $headers .= "From: ".
          Config::getVarVar('mail', 'fromName').
          " <".Config::getVarVar('mail', 'fromEmail').">\r\n"; 
      }
      foreach ($recipients as $v) {
        if (!@mail($v['email'], $subject, $message, $headers)) {
          LogWriter::v('email', 'failed');
          prr(array('send email', $v['email'], $subject, $message, $headers));
          return false;
        }
      }
      LogWriter::v('email', 'sent');
      return true;
    }
    ////////////////////// SMTP /////////////////
    $e = $this->mailer;
    $e->Subject  = $subject;
    $e->ClearAllRecipients();
    for ($i=0; $i<count($recipients); $i++) {
      $e->AddAddress($recipients[$i]['email'], $recipients[$i]['name']);
    }
    if (!$html) {
      $e->AltBody = $message;
    } else {
      $e->Body = $message;
      $message = str_replace("<br>", "\n", $message);
      $message = strip_tags($message);
      $e->AltBody = $message;
    }
    if (!$e->Send()) {
      throw new NgnException(
        "There has been a mail error sending to '".
        Tt::enumK($recipients, 'email')."': ".
        "{$e->ErrorInfo}");
    }
    return true;
  }

}
