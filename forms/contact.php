<?php
/**
 * Contact Form Handler with Microsoft Graph API OAuth2
 * Sends email to administrator and confirmation email to user
 */

// Include configuration file for OAuth2 settings
require_once('../../private/config.php');
require_once('../vendor/autoload.php');

use League\OAuth2\Client\Provider\GenericProvider;

// Validate form inputs
try {
    // Validate form inputs
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['message'])) {
        throw new Exception('Please fill in all required fields.');
    }
    
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $subject = htmlspecialchars(trim($_POST['subject'] ?? 'Contact Form Submission'));
    $message = htmlspecialchars(trim($_POST['message']));
    
    if (!$email) {
        throw new Exception('Please provide a valid email address.');
    }
    
    // Configure OAuth2 provider for Microsoft 365
    $provider = new GenericProvider([
        'clientId'                => oauth_client_id,
        'clientSecret'            => oauth_client_secret,
        'redirectUri'             => '',
        'urlAuthorize'            => 'https://login.microsoftonline.com/' . oauth_tenant_id . '/oauth2/v2.0/authorize',
        'urlAccessToken'          => 'https://login.microsoftonline.com/' . oauth_tenant_id . '/oauth2/v2.0/token',
        'urlResourceOwnerDetails' => '',
        'scopes'                  => 'https://graph.microsoft.com/.default'
    ]);
    
    // Get OAuth2 access token using client credentials flow
    $accessToken = $provider->getAccessToken('client_credentials', [
        'scope' => 'https://graph.microsoft.com/.default'
    ]);
    
    // --- EMAIL 1: Send to Administrator ---
    $adminEmailMessage = [
        'message' => [
            'subject' => "New Contact Form: $subject",
            'body' => [
                'contentType' => 'HTML',
                'content' => "
                    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                        <div style='background: #673AB7; padding: 20px; text-align: center;'>
                            <h2 style='color: white; margin: 0;'>New Contact Form Submission</h2>
                        </div>
                        <div style='background: #f8f9fa; padding: 15px; border: 1px solid #e9ecef;'>
                            <h3 style='color: #673AB7; border-bottom: 2px solid #673AB7; padding-bottom: 10px; margin-top: 0;'>Contact Details</h3>
                            <table style='width: 100%; margin: 15px 0; table-layout: fixed; word-wrap: break-word;'>
                                <tr>
                                    <td style='padding: 8px; background: white; border: 1px solid #dee2e6; width: 80px; vertical-align: top;'><strong>Name:</strong></td>
                                    <td style='padding: 8px; background: white; border: 1px solid #dee2e6; word-wrap: break-word; overflow-wrap: break-word;'>$name</td>
                                </tr>
                                <tr>
                                    <td style='padding: 8px; background: white; border: 1px solid #dee2e6; width: 80px; vertical-align: top;'><strong>Email:</strong></td>
                                    <td style='padding: 8px; background: white; border: 1px solid #dee2e6; word-wrap: break-word; overflow-wrap: break-word;'><a href='mailto:$email' style='word-break: break-all;'>$email</a></td>
                                </tr>
                                <tr>
                                    <td style='padding: 8px; background: white; border: 1px solid #dee2e6; width: 80px; vertical-align: top;'><strong>Subject:</strong></td>
                                    <td style='padding: 8px; background: white; border: 1px solid #dee2e6; word-wrap: break-word; overflow-wrap: break-word;'>$subject</td>
                                </tr>
                            </table>
                            <h3 style='color: #673AB7; border-bottom: 2px solid #673AB7; padding-bottom: 10px;'>Message</h3>
                            <div style='background: white; padding: 15px; border: 1px solid #dee2e6; white-space: pre-wrap; word-wrap: break-word; overflow-wrap: break-word;'>$message</div>
                            <div style='margin-top: 15px; padding: 15px; background: #e7f3ff; border-left: 4px solid #2196F3;'>
                                <strong>💡 Quick Actions:</strong><br>
                                <a href='mailto:$email?subject=Re: $subject' style='color: #673AB7; text-decoration: none; word-break: break-all;'>
                                    ✉️ Reply to $name
                                </a>
                            </div>
                        </div>
                        <div style='background: #343a40; color: white; padding: 15px; text-align: center; font-size: 12px;'>
                            <p style='margin: 0;'>GlitchWizard Solutions - Contact Form</p>
                            <p style='margin: 5px 0 0 0;'>Received: " . date('F j, Y \a\t g:i A T') . "</p>
                        </div>
                    </div>
                "
            ],
            'toRecipients' => [
                ['emailAddress' => ['address' => support_email]]
            ],
            'replyTo' => [
                ['emailAddress' => ['address' => $email, 'name' => $name]]
            ]
        ],
        'saveToSentItems' => 'true'
    ];
    
    // Send admin email
    error_log("Attempting to send admin email to: " . support_email);
    try {
        $result1 = sendGraphEmail($adminEmailMessage, $accessToken->getToken(), smtp_from_email);
        error_log("Admin email sent successfully");
    } catch (Exception $e) {
        error_log("Admin email failed: " . $e->getMessage());
        throw $e; // Re-throw to catch in main try block
    }
    
    // --- EMAIL 2: Send Confirmation to User ---
    $userEmailMessage = [
        'message' => [
            'subject' => "We've Received Your Message - GlitchWizard Solutions",
            'body' => [
                'contentType' => 'HTML',
                'content' => "
                    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                        <div style='background: linear-gradient(135deg, #673AB7, #9C27B0); padding: 30px; text-align: center;'>
                            <h1 style='color: white; margin: 0; font-size: 28px;'>Thank You, $name!</h1>
                        </div>
                        <div style='background: #f8f9fa; padding: 30px; border: 1px solid #e9ecef;'>
                            <p style='font-size: 16px; color: #333; line-height: 1.6;'>
                                We've successfully received your message and appreciate you taking the time to reach out to us.
                            </p>
                            <div style='background: white; padding: 20px; border-left: 4px solid #28a745; margin: 20px 0;'>
                                <p style='margin: 0; color: #28a745; font-weight: bold;'>✅ Your message has been received</p>
                                <p style='margin: 10px 0 0 0; font-size: 14px; color: #666;'>
                                    Our team will review your inquiry and respond within 1-2 business days.
                                </p>
                            </div>
                            <h3 style='color: #673AB7; margin-top: 30px;'>What You Sent:</h3>
                            <table style='width: 100%; margin: 15px 0; background: white; border: 1px solid #dee2e6;'>
                                <tr><td style='padding: 12px; border-bottom: 1px solid #dee2e6;'><strong>Subject:</strong></td>
                                    <td style='padding: 12px; border-bottom: 1px solid #dee2e6;'>$subject</td></tr>
                                <tr><td style='padding: 12px;'><strong>Your Email:</strong></td>
                                    <td style='padding: 12px;'>$email</td></tr>
                            </table>
                            <div style='background: white; padding: 15px; border: 1px solid #dee2e6; margin-top: 15px;'>
                                <strong>Your Message:</strong>
                                <p style='margin: 10px 0 0 0; white-space: pre-wrap; color: #555;'>$message</p>
                            </div>
                            <div style='margin-top: 30px; padding: 20px; background: #fff3cd; border-left: 4px solid #ffc107;'>
                                <p style='margin: 0; color: #856404;'>
                                    <strong>📧 Need to add more information?</strong><br>
                                    Simply reply to this email and we'll get your updated message.
                                </p>
                            </div>
                        </div>
                        <div style='background: #343a40; color: white; padding: 20px; text-align: center;'>
                            <p style='margin: 0 0 10px 0; font-size: 16px; font-weight: bold;'>GlitchWizard Solutions</p>
                            <p style='margin: 0; font-size: 14px;'>Digital Foundations to Build Your Small Business</p>
                            <p style='margin: 15px 0 0 0; font-size: 12px; opacity: 0.8;'>
                                <a href='https://glitchwizardsolutions.com' style='color: #fff; text-decoration: none;'>glitchwizardsolutions.com</a>
                            </p>
                        </div>
                    </div>
                "
            ],
            'toRecipients' => [
                ['emailAddress' => ['address' => $email, 'name' => $name]]
            ],
            'replyTo' => [
                ['emailAddress' => ['address' => support_email, 'name' => 'GlitchWizard Solutions']]
            ]
        ],
        'saveToSentItems' => 'true'
    ];
    
    // Send user confirmation email
    error_log("Attempting to send confirmation email to: " . $email);
    try {
        $result2 = sendGraphEmail($userEmailMessage, $accessToken->getToken(), smtp_from_email);
        error_log("Confirmation email sent successfully");
    } catch (Exception $e) {
        error_log("Confirmation email failed: " . $e->getMessage());
        // Don't throw - admin email already sent, just log the error
    }
    
    // Return success response in format expected by validate.js
    echo "OK";
    
} catch (Exception $e) {
    error_log("Contact Form Error: " . $e->getMessage());
    echo "Error: " . $e->getMessage();
}

/**
 * Send email via Microsoft Graph API
 */
function sendGraphEmail($emailMessage, $accessToken, $fromEmail) {
    $graphUrl = "https://graph.microsoft.com/v1.0/users/$fromEmail/sendMail";
    
    $ch = curl_init($graphUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($emailMessage));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode !== 202) {
        $error = json_decode($response, true);
        $errorMsg = $error['error']['message'] ?? 'Unknown error';
        throw new Exception("Graph API Error: $errorMsg");
    }
    
    return true;
}
?>
