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
    // === SPAM PROTECTION LAYER 1: Honeypot Field ===
    // Bots typically fill out ALL fields, including hidden ones
    if (!empty($_POST['website'])) {
        error_log("SPAM BLOCKED: Honeypot field filled - IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
        // Return success to fool the bot (don't reveal we detected them)
        echo "OK";
        exit;
    }
    
    // === SPAM PROTECTION LAYER 2: Timestamp Check ===
    // Bots submit forms instantly, humans take at least a few seconds
    if (isset($_POST['form_timestamp'])) {
        $timestamp = intval($_POST['form_timestamp']);
        $currentTime = time() * 1000; // Convert to milliseconds
        $timeDiff = ($currentTime - $timestamp) / 1000; // Seconds elapsed
        
        // If submitted in less than 3 seconds, likely a bot
        if ($timeDiff < 3) {
            error_log("SPAM BLOCKED: Form submitted too quickly ({$timeDiff}s) - IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
            echo "OK";
            exit;
        }
    }
    
    // === SPAM PROTECTION LAYER 3: Rate Limiting ===
    // Check if same IP submitted recently
    session_start();
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $rateKey = "contact_form_" . md5($ip);
    $rateLimitFile = sys_get_temp_dir() . "/" . $rateKey;
    
    if (file_exists($rateLimitFile)) {
        $lastSubmit = file_get_contents($rateLimitFile);
        $timeSinceLastSubmit = time() - intval($lastSubmit);
        
        // Allow only 1 submission per 60 seconds from same IP
        if ($timeSinceLastSubmit < 60) {
            error_log("SPAM BLOCKED: Rate limit exceeded - IP: $ip - Time since last: {$timeSinceLastSubmit}s");
            throw new Exception('Please wait a moment before submitting another message.');
        }
    }
    
    // Record this submission
    file_put_contents($rateLimitFile, time());
    
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
    
    // === SPAM PROTECTION LAYER 4: Email Domain Validation ===
    // Check if email domain has valid MX records
    $emailDomain = substr(strrchr($email, "@"), 1);
    if (!checkdnsrr($emailDomain, 'MX') && !checkdnsrr($emailDomain, 'A')) {
        error_log("SPAM BLOCKED: Invalid email domain '$emailDomain' - IP: $ip");
        throw new Exception('Please provide a valid email address from an active domain.');
    }
    
    // === SPAM PROTECTION LAYER 5: Content Filtering ===
    // Detect common spam patterns
    $spamPatterns = [
        '/\b(viagra|cialis|pharmacy|pills|casino|poker|lottery|winner)\b/i',
        '/\b(bitcoin|crypto|investment|profit|earn money|make money fast)\b/i',
        '/\b(click here|buy now|limited time|act now|urgent)\b/i',
        '/(http[s]?:\/\/[^\s]+){4,}/i', // More than 3 URLs is suspicious
        '/[A-Z]{30,}/', // All caps blocks (likely spam)
    ];
    
    $combinedText = $name . ' ' . $subject . ' ' . $message;
    foreach ($spamPatterns as $pattern) {
        if (preg_match($pattern, $combinedText)) {
            error_log("SPAM BLOCKED: Suspicious content pattern detected - IP: $ip - Pattern: $pattern");
            // Return success to fool the spammer
            echo "OK";
            exit;
        }
    }
    
    // === SPAM PROTECTION LAYER 6: Name Validation ===
    // Check for suspicious name patterns
    if (strlen($name) < 2 || strlen($name) > 100) {
        error_log("SPAM BLOCKED: Suspicious name length (" . strlen($name) . ") - IP: $ip");
        throw new Exception('Please provide a valid name.');
    }
    
    // Check for excessive special characters in name
    if (preg_match('/[^\w\s\-\'.]/u', $name)) {
        error_log("SPAM BLOCKED: Invalid characters in name - IP: $ip - Name: $name");
        throw new Exception('Please provide a valid name using only letters.');
    }
    
    // Check for gibberish patterns in name (alternating caps like: aAbBcCdD)
    if (detectGibberish($name)) {
        error_log("SPAM BLOCKED: Gibberish detected in name - IP: $ip - Name: $name");
        echo "OK"; // Fool the bot
        exit;
    }
    
    // === SPAM PROTECTION LAYER 7: Message Length Validation ===
    if (strlen($message) < 10) {
        throw new Exception('Please provide a more detailed message (at least 10 characters).');
    }
    
    if (strlen($message) > 5000) {
        throw new Exception('Message is too long. Please keep it under 5000 characters.');
    }
    
    // === SPAM PROTECTION LAYER 8: Gibberish Detection ===
    // Detect long strings with no spaces (common in spam)
    if (preg_match('/[a-zA-Z]{50,}/', $message)) {
        error_log("SPAM BLOCKED: Suspicious long string without spaces (50+ chars) - IP: $ip");
        echo "OK"; // Fool the bot
        exit;
    }
    
    // Check if message is mostly gibberish
    if (detectGibberish($message)) {
        error_log("SPAM BLOCKED: Gibberish detected in message - IP: $ip");
        echo "OK"; // Fool the bot
        exit;
    }
    
    // === SPAM PROTECTION LAYER 9: Terms Acceptance ===
    // User must acknowledge security screening
    if (empty($_POST['security_acknowledgment'])) {
        error_log("SPAM BLOCKED: Security acknowledgment not checked - IP: $ip");
        throw new Exception('Please acknowledge the security notice to proceed.');
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
                            
                            <div style='background: white; padding: 12px; border: 1px solid #dee2e6; margin-bottom: 10px;'>
                                <div style='color: #666; font-size: 12px; font-weight: bold; margin-bottom: 5px;'>NAME</div>
                                <div style='color: #333; word-wrap: break-word; overflow-wrap: break-word;'>$name</div>
                            </div>
                            
                            <div style='background: white; padding: 12px; border: 1px solid #dee2e6; margin-bottom: 10px;'>
                                <div style='color: #666; font-size: 12px; font-weight: bold; margin-bottom: 5px;'>EMAIL</div>
                                <div style='color: #333; word-wrap: break-word; overflow-wrap: break-word;'><a href='mailto:$email' style='color: #673AB7; word-break: break-all;'>$email</a></div>
                            </div>
                            
                            <div style='background: white; padding: 12px; border: 1px solid #dee2e6; margin-bottom: 15px;'>
                                <div style='color: #666; font-size: 12px; font-weight: bold; margin-bottom: 5px;'>SUBJECT</div>
                                <div style='color: #333; word-wrap: break-word; overflow-wrap: break-word;'>$subject</div>
                            </div>
                            
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
                        <div style='background: #673AB7; padding: 20px; text-align: center;'>
                            <h2 style='color: white; margin: 0;'>Thank You, $name!</h2>
                        </div>
                        <div style='background: #f8f9fa; padding: 15px; border: 1px solid #e9ecef;'>
                            <p style='font-size: 16px; color: #333; line-height: 1.6; margin-top: 0;'>
                                We've successfully received your message and appreciate you taking the time to reach out to us.
                            </p>
                            <div style='background: white; padding: 15px; border-left: 4px solid #28a745; margin: 15px 0;'>
                                <p style='margin: 0; color: #28a745; font-weight: bold;'>✅ Your message has been received</p>
                                <p style='margin: 10px 0 0 0; font-size: 14px; color: #666;'>
                                    Our team will review your inquiry and respond within 1-2 business days.
                                </p>
                            </div>
                            
                            <h3 style='color: #673AB7; border-bottom: 2px solid #673AB7; padding-bottom: 10px;'>What You Sent</h3>
                            
                            <div style='background: white; padding: 12px; border: 1px solid #dee2e6; margin-bottom: 10px;'>
                                <div style='color: #666; font-size: 12px; font-weight: bold; margin-bottom: 5px;'>SUBJECT</div>
                                <div style='color: #333; word-wrap: break-word; overflow-wrap: break-word;'>$subject</div>
                            </div>
                            
                            <div style='background: white; padding: 12px; border: 1px solid #dee2e6; margin-bottom: 15px;'>
                                <div style='color: #666; font-size: 12px; font-weight: bold; margin-bottom: 5px;'>YOUR EMAIL</div>
                                <div style='color: #333; word-wrap: break-word; overflow-wrap: break-word;'>$email</div>
                            </div>
                            
                            <h3 style='color: #673AB7; border-bottom: 2px solid #673AB7; padding-bottom: 10px;'>Your Message</h3>
                            <div style='background: white; padding: 15px; border: 1px solid #dee2e6; white-space: pre-wrap; word-wrap: break-word; overflow-wrap: break-word; color: #555;'>$message</div>
                            
                            <div style='margin-top: 15px; padding: 15px; background: #fff3cd; border-left: 4px solid #ffc107;'>
                                <p style='margin: 0; color: #856404;'>
                                    <strong>📧 Need to add more information?</strong><br>
                                    Simply reply to this email and we'll get your updated message.
                                </p>
                            </div>
                        </div>
                        <div style='background: #343a40; color: white; padding: 15px; text-align: center; font-size: 12px;'>
                            <p style='margin: 0;'>GlitchWizard Solutions - Digital Foundations to Build Your Small Business</p>
                            <p style='margin: 5px 0 0 0;'>
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

/**
 * Detect gibberish text patterns
 * Looks for random character strings, alternating caps, lack of real words
 */
function detectGibberish($text) {
    // Remove spaces and check length
    $noSpaces = str_replace(' ', '', $text);
    
    // If text has very few spaces relative to length, suspicious
    $spaceCount = substr_count($text, ' ');
    $textLength = strlen($text);
    
    // Normal English has roughly 1 space per 5-6 characters
    // If less than 1 space per 15 characters, likely gibberish
    if ($textLength > 30 && $spaceCount < ($textLength / 15)) {
        return true;
    }
    
    // Check for alternating case pattern (aAbBcCdD)
    // Count transitions from lower to upper case
    $caseTransitions = 0;
    for ($i = 1; $i < strlen($text); $i++) {
        $prev = $text[$i-1];
        $curr = $text[$i];
        
        if (ctype_alpha($prev) && ctype_alpha($curr)) {
            if (ctype_lower($prev) && ctype_upper($curr)) {
                $caseTransitions++;
            }
        }
    }
    
    // If more than 20% of characters are case transitions, likely gibberish
    if ($textLength > 10 && ($caseTransitions / $textLength) > 0.20) {
        return true;
    }
    
    // Check vowel ratio - English text typically has 35-45% vowels
    $vowels = preg_match_all('/[aeiouAEIOU]/', $text);
    $letters = preg_match_all('/[a-zA-Z]/', $text);
    
    if ($letters > 20) {
        $vowelRatio = $vowels / $letters;
        // If less than 15% or more than 70% vowels, suspicious
        if ($vowelRatio < 0.15 || $vowelRatio > 0.70) {
            return true;
        }
    }
    
    // Check for excessive consonant clusters (4+ consonants in a row)
    if (preg_match('/[bcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ]{6,}/', $text)) {
        return true;
    }
    
    return false;
}
?>
