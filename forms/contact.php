<?php
  /**
  * Requires the "PHP Email Form" library
  * The "PHP Email Form" library is available only in the pro version of the template
  * The library should be uploaded to: vendor/php-email-form/php-email-form.php
  * For more info and help: https://bootstrapmade.com/php-email-form/
  */

  // Include configuration file for SMTP settings
  require_once('../../private/config.php');

  // Use support_email from config.php
  $receiving_email_address = support_email;

  if( file_exists($php_email_form = '../assets/vendor/php-email-form/php-email-form.php' )) {
    include( $php_email_form );
  } else {
    die( 'Unable to load the "PHP Email Form" Library!');
  }

  $contact = new PHP_Email_Form;
  $contact->ajax = true;
  
  $contact->to = $receiving_email_address;
  $contact->from_name = $_POST['name'];
  // Use Azure-verified sender email instead of customer email
  $contact->from_email = smtp_from_email;
  $contact->subject = $_POST['subject'];
  
  // Override the default mailer property that gets set to forms@localhost
  $contact->mailer = smtp_from_email;

  // Set Reply-To header to ensure replies go to the customer's email
  $contact->options = array(
    'reply_to' => $_POST['email']
  );

  // SMTP configuration using constants from config.php
  $contact->smtp = array(
    'host' => smtp_host,
    'username' => smtp_username,
    'password' => smtp_password,
    'port' => smtp_port,
    'encryption' => 'tls'
  );
  
  // Set Reply-To header to ensure replies go to the customer's email
  $contact->options = array(
    'reply_to' => $_POST['email']
  );
  error_log("=== SMTP DEBUG INFO ===");
  error_log("SMTP Host from config: " . smtp_host);
  error_log("SMTP Port from config: " . smtp_port);
  error_log("SMTP User from config: " . smtp_username);
  error_log("SERVER_NAME: " . ($_SERVER['SERVER_NAME'] ?? 'Not set'));
  error_log("HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'Not set'));
  error_log("Config file path: " . realpath('../../private/config.php'));
  error_log("=== END DEBUG INFO ===");
   
  $contact->add_message( $_POST['name'], 'From');
  $contact->add_message( $_POST['email'], 'Email');
  $contact->add_message( $_POST['message'], 'Message', 10);

  // Send with error handling
  $result = $contact->send();
  
  // Log the result for debugging
  error_log("Email send result: " . $result);
  
  echo $result;
?>
