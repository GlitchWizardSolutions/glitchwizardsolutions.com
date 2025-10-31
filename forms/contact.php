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
  $contact->from_email = $_POST['email'];
  $contact->subject = $_POST['subject'];

  // SMTP configuration using constants from config.php
  $contact->smtp = array(
    'host' => smtp_host,
    'username' => smtp_username,
    'password' => smtp_password,
    'port' => smtp_port,
    'encryption' => 'tls'
  );

  // Add debugging information
  error_log("SMTP Config: Host=" . smtp_host . ", Port=" . smtp_port . ", User=" . smtp_username);
   
  $contact->add_message( $_POST['name'], 'From');
  $contact->add_message( $_POST['email'], 'Email');
  $contact->add_message( $_POST['message'], 'Message', 10);

  // Send with error handling
  $result = $contact->send();
  
  // Log the result for debugging
  error_log("Email send result: " . $result);
  
  echo $result;
?>
