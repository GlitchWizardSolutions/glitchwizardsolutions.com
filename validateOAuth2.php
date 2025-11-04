<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="preconnect" href="//privacy-proxy.usercentrics.eu">
<link rel="preload" href="//privacy-proxy.usercentrics.eu/latest/uc-block.bundle.js" as="script">
<script type="application/javascript" src="https://privacy-proxy.usercentrics.eu/latest/uc-block.bundle.js"></script>
<script id="usercentrics-cmp" src="https://app.usercentrics.eu/browser-ui/latest/loader.js" data-settings-id="0lPnZOGfCQYkBt"  async></script>
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-4LP6TJ8YK9"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-4LP6TJ8YK9');
</script>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>OAuth2 SMTP Validator - Microsoft 365 - GlitchWizard Solutions</title>
  <meta content="OAuth2 SMTP email configuration testing tool for Microsoft 365 with modern authentication" name="description">
  <meta content="OAuth2, SMTP, Microsoft 365, modern authentication, email server validation" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- GlitchWizard Solutions Accessibility CSS -->
  <link href="https://glitchwizardsolutions.com/universal/accessibility.css" rel="stylesheet">
</head>

<body>
 <a id='skip-nav' class='screenreader-text' href='#primary-content'>Skip to Content</a>

  <!-- ======= Header ======= -->
  <header id="header" class="d-flex align-items-center ">
    <div class="container-fluid d-flex align-items-center justify-content-lg-between">

      <h1 class="logo me-auto me-lg-0"><a href="https://glitchwizardsolutions.com/index.html">GlitchWizard Solutions</a></h1>

      <nav id="navbar" class="navbar order-last order-lg-0">
        <ul>
          <li><a class="nav-link scrollto active" href="https://glitchwizardsolutions.com/#hero">Home</a></li>
          <li><a class="nav-link scrollto" href="https://glitchwizardsolutions.com/#services">Services</a></li>
          <li><a class="nav-link scrollto" href="https://outlook.office365.com/owa/calendar/GlitchWizardSolutionsLLC1@GlitchWizardSolutions.com/bookings/">Contact</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->

  <div id='primary-content'></div>
  <main id="main">
    <?php
    // Import PHPMailer classes (will only work if dependencies are installed)
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\OAuth;
    use League\OAuth2\Client\Provider\GenericProvider;
    
    // Start session for POST-Redirect-GET pattern
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Check if PHPMailer and required OAuth libraries are installed
    $dependencies_installed = false;
    $dependency_error = '';
    
    if (file_exists('vendor/autoload.php')) {
        require 'vendor/autoload.php';
        $dependencies_installed = true;
    } else {
        $dependency_error = 'Composer dependencies not installed. Please run: <code>composer require phpmailer/phpmailer league/oauth2-client</code>';
    }
    
    // PHP processing for OAuth2 SMTP test
    $test_result = '';
    $show_results = false;
    
    // Check if we have results from a redirect
    if (isset($_SESSION['oauth2_test_results'])) {
        $show_results = true;
        $test_result = $_SESSION['oauth2_test_results']['test_result'];
        $tenant_id = $_SESSION['oauth2_test_results']['tenant_id'];
        $client_id = $_SESSION['oauth2_test_results']['client_id'];
        $from_email = $_SESSION['oauth2_test_results']['from_email'];
        $test_email = $_SESSION['oauth2_test_results']['test_email'];
        if (isset($_SESSION['oauth2_test_results']['raw_error_details'])) {
            $raw_error_details = $_SESSION['oauth2_test_results']['raw_error_details'];
        }
        // Clear the session data after displaying
        unset($_SESSION['oauth2_test_results']);
    }
    
    if ($_POST && isset($_POST['test_oauth2']) && $dependencies_installed) {
        // Get form data
        $tenant_id = $_POST['tenant_id'] ?? '';
        $client_id = $_POST['client_id'] ?? '';
        $client_secret = $_POST['client_secret'] ?? '';
        $from_email = $_POST['from_email'] ?? '';
        $test_email = $_POST['test_email'] ?? '';
        
        try {
            // Create a new PHPMailer instance
            $mail = new PHPMailer(true);
            
            // Configure OAuth2 provider for Microsoft 365
            $provider = new GenericProvider([
                'clientId'                => $client_id,
                'clientSecret'            => $client_secret,
                'redirectUri'             => '',
                'urlAuthorize'            => "https://login.microsoftonline.com/$tenant_id/oauth2/v2.0/authorize",
                'urlAccessToken'          => "https://login.microsoftonline.com/$tenant_id/oauth2/v2.0/token",
                'urlResourceOwnerDetails' => '',
                'scopes'                  => 'https://outlook.office365.com/.default'
            ]);
            
            // Get OAuth2 access token using client credentials flow
            $accessToken = $provider->getAccessToken('client_credentials', [
                'scope' => 'https://outlook.office365.com/.default'
            ]);
            
            // Configure SMTP with OAuth2
            $mail->isSMTP();
            $mail->Host = 'smtp.office365.com';
            $mail->Port = 587;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->SMTPAuth = true;
            $mail->AuthType = 'XOAUTH2';
            
            // Set up OAuth2
            $mail->setOAuth(
                new OAuth([
                    'provider' => $provider,
                    'clientId' => $client_id,
                    'clientSecret' => $client_secret,
                    'refreshToken' => '',
                    'userName' => $from_email,
                    'accessToken' => $accessToken->getToken()
                ])
            );
            
            // Email content
            $mail->setFrom($from_email, 'OAuth2 SMTP Validator');
            $mail->addAddress($test_email);
            $mail->Subject = 'OAuth2 SMTP Test - ' . date('Y-m-d H:i:s');
            $mail->isHTML(true);
            $mail->Body = '<h3>OAuth2 SMTP Test Message</h3>
                          <p>This is a test message sent using OAuth2 authentication with Microsoft 365.</p>
                          <ul>
                            <li><strong>Tenant ID:</strong> ' . htmlspecialchars($tenant_id) . '</li>
                            <li><strong>Client ID:</strong> ' . htmlspecialchars($client_id) . '</li>
                            <li><strong>From Email:</strong> ' . htmlspecialchars($from_email) . '</li>
                            <li><strong>Test Time:</strong> ' . date('Y-m-d H:i:s T') . '</li>
                          </ul>';
            
            // Enable verbose debug output
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->Debugoutput = function($str, $level) {
                // Capture debug output
            };
            
            // Send the email
            $result = $mail->send();
            
            $raw_error_details = null;
            
            if ($result) {
                $test_result = '
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i> 
                    <strong>✅ SUCCESS!</strong><br>
                    <strong>Result:</strong> OAuth2 SMTP configuration is working correctly!<br>
                    <strong>Status:</strong> Test email sent successfully to <code>' . htmlspecialchars($test_email) . '</code><br>
                    <strong>From:</strong> <code>' . htmlspecialchars($from_email) . '</code><br>
                    <strong>Server:</strong> smtp.office365.com:587 (STARTTLS)<br>
                    <strong>Authentication:</strong> OAuth2 with Client Credentials Flow<br>
                    <strong>Tenant:</strong> <code>' . htmlspecialchars($tenant_id) . '</code><br>
                    <small class="text-muted">✉️ Check your inbox (and spam folder) for the test message.</small>
                </div>';
            }
            
        } catch (Exception $e) {
            $raw_error_details = $e->getMessage();
            $troubleshooting_tip = '';
            
            // Provide specific troubleshooting
            if (strpos($raw_error_details, 'invalid_client') !== false) {
                $troubleshooting_tip = '<br><strong>💡 Troubleshooting:</strong> Invalid Client ID or Client Secret. Verify your Azure AD App Registration credentials.';
            } elseif (strpos($raw_error_details, 'unauthorized_client') !== false) {
                $troubleshooting_tip = '<br><strong>💡 Troubleshooting:</strong> The application doesn\'t have permission to send email. Add "Mail.Send" API permission in Azure AD.';
            } elseif (strpos($raw_error_details, 'invalid_grant') !== false) {
                $troubleshooting_tip = '<br><strong>💡 Troubleshooting:</strong> Grant issue. Ensure admin consent is granted for the Mail.Send permission.';
            } elseif (strpos($raw_error_details, 'SMTP Error') !== false) {
                $troubleshooting_tip = '<br><strong>💡 Troubleshooting:</strong> SMTP connection issue. Verify the from email address has an active mailbox in Microsoft 365.';
            } else {
                $troubleshooting_tip = '<br><strong>💡 Troubleshooting:</strong> Check your Azure AD App Registration settings and API permissions.';
            }
            
            $test_result = '
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i> 
                <strong>❌ OAuth2 SMTP TEST FAILED</strong><br>
                <strong>Error Message:</strong> ' . htmlspecialchars($raw_error_details) . '<br>
                ' . $troubleshooting_tip . '
            </div>';
        }
        
        // Store results in session
        $_SESSION['oauth2_test_results'] = array(
            'test_result' => $test_result,
            'tenant_id' => $tenant_id,
            'client_id' => $client_id,
            'from_email' => $from_email,
            'test_email' => $test_email
        );
        
        if (isset($raw_error_details)) {
            $_SESSION['oauth2_test_results']['raw_error_details'] = $raw_error_details;
        }
        
        // Redirect to prevent form resubmission
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
    ?>

    <!-- ======= OAuth2 SMTP Configuration Validator Section ======= -->
    <section id="oauth2-validator" class="contact">
      <div class="container">

        <div class="section-title">
          <h2>OAuth2 SMTP Configuration Validator</h2>
          <p>Modern authentication for Microsoft 365 email sending. No passwords required - uses secure OAuth2 tokens.</p>
        </div>

        <?php if (!$dependencies_installed): ?>
        <div class="row justify-content-center mb-4">
          <div class="col-lg-8">
            <div class="alert alert-warning">
              <i class="bi bi-exclamation-triangle"></i> 
              <strong>Dependencies Required</strong><br>
              <?php echo $dependency_error; ?>
            </div>
          </div>
        </div>
        <?php endif; ?>

        <div class="row justify-content-center">
          <div class="col-lg-8">
            
            <form method="post" action="" class="oauth2-validator-form">
              <input type="hidden" name="test_oauth2" value="1">
              
              <div class="alert alert-info mb-4">
                <i class="bi bi-info-circle"></i> 
                <strong>What is OAuth2?</strong><br>
                OAuth2 is modern authentication that replaces passwords with secure tokens. 
                Microsoft is deprecating basic authentication (username/password) for SMTP. 
                This method will continue to work long-term.
              </div>
              
              <div class="form-group">
                <label for="tenant_id">Tenant ID (Directory ID)</label>
                <input type="text" class="form-control" name="tenant_id" id="tenant_id" 
                       placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx" 
                       value="" autocomplete="off" required>
                <small class="form-text text-muted">Find in Azure AD → Overview → Directory (tenant) ID</small>
              </div>
              
              <div class="form-group mt-3">
                <label for="client_id">Application (Client) ID</label>
                <input type="text" class="form-control" name="client_id" id="client_id" 
                       placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx" 
                       value="" autocomplete="off" required>
                <small class="form-text text-muted">Find in Azure AD → App Registrations → Your App → Application (client) ID</small>
              </div>
              
              <div class="form-group mt-3">
                <label for="client_secret">Client Secret Value</label>
                <input type="password" class="form-control" name="client_secret" id="client_secret" 
                       placeholder="Your client secret value (not the Secret ID)" 
                       value="" autocomplete="off" required>
                <small class="form-text text-muted">Find in Azure AD → App Registrations → Your App → Certificates & secrets</small>
              </div>
              
              <div class="form-group mt-3">
                <label for="from_email">From Email Address (Your Microsoft 365 Email)</label>
                <input type="email" class="form-control" name="from_email" id="from_email" 
                       placeholder="yourname@glitchwizardsolutions.com" 
                       value="" autocomplete="off" required>
                <small class="form-text text-muted">Must be a valid mailbox in your Microsoft 365 tenant</small>
              </div>
              
              <div class="form-group mt-3">
                <label for="test_email">Test Email Address (To)</label>
                <input type="email" class="form-control" name="test_email" id="test_email" 
                       placeholder="test@example.com" 
                       value="" autocomplete="off" required>
                <small class="form-text text-muted">Where to send the test email</small>
              </div>
              
              <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary" <?php echo !$dependencies_installed ? 'disabled' : ''; ?>>
                  <i class="bi bi-shield-check"></i> Test OAuth2 SMTP Configuration
                </button>
              </div>
              
              <?php if ($show_results && isset($test_result)): ?>
              <!-- Detailed Error/Success Message Area -->
              <div class="mt-4">
                <div class="card border-0 shadow-sm">
                  <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-code-square"></i> Test Results - Technical Details</h5>
                  </div>
                  <div class="card-body">
                    <?php echo $test_result; ?>
                    
                    <?php if (isset($raw_error_details)): ?>
                    <div class="mt-3 p-3" style="background: #f8f9fa; border-left: 4px solid #dc3545; border-radius: 4px;">
                      <h6 class="text-danger mb-2"><i class="bi bi-bug"></i> Raw Error Details (For Technical Support)</h6>
                      <pre style="white-space: pre-wrap; word-wrap: break-word; font-size: 12px; margin: 0; color: #212529;"><?php echo htmlspecialchars($raw_error_details); ?></pre>
                    </div>
                    <?php endif; ?>
                    
                    <div class="mt-3 p-3" style="background: #e7f3ff; border-left: 4px solid #0d6efd; border-radius: 4px;">
                      <h6 class="text-primary mb-2"><i class="bi bi-info-circle"></i> Configuration Summary</h6>
                      <table class="table table-sm table-borderless mb-0" style="font-size: 13px;">
                        <tr><td><strong>Authentication:</strong></td><td><code>OAuth2 Client Credentials</code></td></tr>
                        <tr><td><strong>SMTP Host:</strong></td><td><code>smtp.office365.com</code></td></tr>
                        <tr><td><strong>SMTP Port:</strong></td><td><code>587 (STARTTLS)</code></td></tr>
                        <tr><td><strong>Tenant ID:</strong></td><td><code><?php echo htmlspecialchars($tenant_id); ?></code></td></tr>
                        <tr><td><strong>Client ID:</strong></td><td><code><?php echo htmlspecialchars($client_id); ?></code></td></tr>
                        <tr><td><strong>From Address:</strong></td><td><code><?php echo htmlspecialchars($from_email); ?></code></td></tr>
                        <tr><td><strong>To Address:</strong></td><td><code><?php echo htmlspecialchars($test_email); ?></code></td></tr>
                        <tr><td><strong>Test Time:</strong></td><td><code><?php echo date('Y-m-d H:i:s T'); ?></code></td></tr>
                        <tr><td><strong>Server:</strong></td><td><code><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></code></td></tr>
                        <tr><td><strong>PHP Version:</strong></td><td><code><?php echo phpversion(); ?></code></td></tr>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <?php endif; ?>
            </form>
          </div>
        </div>

        <!-- ======= Setup Instructions ======= -->
        <div class="row mt-5 justify-content-center">
          <div class="col-lg-10">
            <div class="card shadow-sm">
              <div class="card-header bg-success text-white">
                <h4 class="mb-0"><i class="bi bi-book"></i> How to Set Up OAuth2 SMTP for Microsoft 365 Business Standard</h4>
              </div>
              <div class="card-body p-4">
                
                <div class="alert alert-primary mb-4">
                  <i class="bi bi-lightbulb"></i> 
                  <strong>Prerequisites:</strong> You need a Microsoft 365 Business Standard account with a custom domain already added and configured.
                </div>

                <h5 class="mb-3"><i class="bi bi-1-circle-fill text-primary"></i> Install Required PHP Libraries</h5>
                <p>First, install PHPMailer and OAuth2 libraries using Composer:</p>
                <div class="bg-dark text-white p-3 rounded mb-4">
                  <code style="color: #0f0;">composer require phpmailer/phpmailer league/oauth2-client</code>
                </div>

                <h5 class="mb-3"><i class="bi bi-2-circle-fill text-primary"></i> Register an Application in Azure Active Directory</h5>
                <ol class="mb-4">
                  <li><strong>Go to Azure Portal:</strong> <a href="https://portal.azure.com" target="_blank">https://portal.azure.com</a></li>
                  <li><strong>Navigate to:</strong> Azure Active Directory → App registrations → New registration</li>
                  <li><strong>Name your app:</strong> e.g., "My Website SMTP OAuth2"</li>
                  <li><strong>Supported account types:</strong> Select "Accounts in this organizational directory only"</li>
                  <li><strong>Redirect URI:</strong> Leave blank (not needed for client credentials flow)</li>
                  <li><strong>Click:</strong> Register</li>
                  <li><strong>Copy:</strong> Application (client) ID - you'll need this</li>
                  <li><strong>Copy:</strong> Directory (tenant) ID - you'll need this too</li>
                </ol>

                <h5 class="mb-3"><i class="bi bi-3-circle-fill text-primary"></i> Create a Client Secret</h5>
                <ol class="mb-4">
                  <li><strong>In your app:</strong> Go to "Certificates & secrets"</li>
                  <li><strong>Click:</strong> "New client secret"</li>
                  <li><strong>Description:</strong> "SMTP OAuth2 Secret"</li>
                  <li><strong>Expires:</strong> Choose 24 months (or longer if available)</li>
                  <li><strong>Click:</strong> Add</li>
                  <li><strong>IMPORTANT:</strong> Copy the <strong>Value</strong> immediately (not the Secret ID). You can't see it again!</li>
                </ol>

                <h5 class="mb-3"><i class="bi bi-4-circle-fill text-primary"></i> Configure API Permissions</h5>
                <ol class="mb-4">
                  <li><strong>In your app:</strong> Go to "API permissions"</li>
                  <li><strong>Click:</strong> "Add a permission"</li>
                  <li><strong>Select:</strong> "Microsoft Graph"</li>
                  <li><strong>Choose:</strong> "Application permissions" (NOT Delegated)</li>
                  <li><strong>Search for and select:</strong>
                    <ul>
                      <li><code>Mail.Send</code> - Allows sending mail from any mailbox</li>
                    </ul>
                  </li>
                  <li><strong>Click:</strong> "Add permissions"</li>
                  <li><strong>CRITICAL:</strong> Click "Grant admin consent for [Your Organization]" and confirm</li>
                  <li><strong>Verify:</strong> Status shows green checkmark "Granted for [Your Organization]"</li>
                </ol>

                <h5 class="mb-3"><i class="bi bi-5-circle-fill text-primary"></i> Enable SMTP AUTH in Exchange Online (Optional but Recommended)</h5>
                <ol class="mb-4">
                  <li><strong>Go to:</strong> <a href="https://admin.microsoft.com" target="_blank">Microsoft 365 Admin Center</a></li>
                  <li><strong>Navigate to:</strong> Users → Active users</li>
                  <li><strong>Select:</strong> The user whose email you'll send from (e.g., webdev@glitchwizardsolutions.com)</li>
                  <li><strong>Click:</strong> Mail tab</li>
                  <li><strong>Click:</strong> "Manage email apps"</li>
                  <li><strong>Ensure:</strong> "Authenticated SMTP" is checked</li>
                  <li><strong>Click:</strong> Save changes</li>
                </ol>

                <h5 class="mb-3"><i class="bi bi-6-circle-fill text-primary"></i> Configure Your DNS (If Using Azure)</h5>
                <div class="alert alert-warning mb-4">
                  <i class="bi bi-exclamation-triangle"></i> 
                  <strong>Note:</strong> You mentioned DNS is partially set up with Azure. Ensure these records exist:
                </div>
                <ul class="mb-4">
                  <li><strong>MX Record:</strong> Points to Microsoft 365 mail servers (e.g., glitchwizardsolutions-com.mail.protection.outlook.com)</li>
                  <li><strong>SPF Record (TXT):</strong> <code>v=spf1 include:spf.protection.outlook.com -all</code></li>
                  <li><strong>DKIM Records:</strong> Two CNAME records as specified in Microsoft 365 Admin Center</li>
                  <li><strong>DMARC Record (TXT):</strong> <code>v=DMARC1; p=quarantine; rua=mailto:dmarc@glitchwizardsolutions.com</code></li>
                </ul>
                <p class="mb-4">To configure these:</p>
                <ol class="mb-4">
                  <li><strong>Microsoft 365 Admin Center:</strong> Settings → Domains → glitchwizardsolutions.com</li>
                  <li><strong>Click:</strong> "DNS records"</li>
                  <li><strong>Follow:</strong> The specific values Microsoft provides for MX, SPF, DKIM, and DMARC</li>
                  <li><strong>Add these records:</strong> In your Azure DNS zone or domain registrar</li>
                </ol>

                <h5 class="mb-3"><i class="bi bi-7-circle-fill text-primary"></i> Test Your Configuration</h5>
                <ol class="mb-4">
                  <li><strong>Fill in the form above with:</strong>
                    <ul>
                      <li>Tenant ID (from step 2)</li>
                      <li>Client ID (from step 2)</li>
                      <li>Client Secret (from step 3)</li>
                      <li>Your Microsoft 365 email address</li>
                      <li>A test recipient email</li>
                    </ul>
                  </li>
                  <li><strong>Click:</strong> "Test OAuth2 SMTP Configuration"</li>
                  <li><strong>Check:</strong> The test email arrives successfully</li>
                </ol>

                <h5 class="mb-3"><i class="bi bi-8-circle-fill text-primary"></i> Use in Your Application</h5>
                <p>Once tested, integrate into your PHP application:</p>
                <div class="bg-dark text-white p-3 rounded mb-4" style="overflow-x: auto;">
                  <pre style="color: #0f0; margin: 0;"><code>&lt;?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\OAuth;
use League\OAuth2\Client\Provider\GenericProvider;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

// OAuth2 Provider Configuration
$provider = new GenericProvider([
    'clientId'        => 'YOUR_CLIENT_ID',
    'clientSecret'    => 'YOUR_CLIENT_SECRET',
    'redirectUri'     => '',
    'urlAuthorize'    => 'https://login.microsoftonline.com/YOUR_TENANT_ID/oauth2/v2.0/authorize',
    'urlAccessToken'  => 'https://login.microsoftonline.com/YOUR_TENANT_ID/oauth2/v2.0/token',
    'urlResourceOwnerDetails' => '',
    'scopes'          => 'https://outlook.office365.com/.default'
]);

// Get Access Token
$accessToken = $provider->getAccessToken('client_credentials', [
    'scope' => 'https://outlook.office365.com/.default'
]);

// Configure SMTP
$mail->isSMTP();
$mail->Host = 'smtp.office365.com';
$mail->Port = 587;
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->SMTPAuth = true;
$mail->AuthType = 'XOAUTH2';

// Set OAuth2
$mail->setOAuth(new OAuth([
    'provider' => $provider,
    'clientId' => 'YOUR_CLIENT_ID',
    'clientSecret' => 'YOUR_CLIENT_SECRET',
    'refreshToken' => '',
    'userName' => 'yourname@glitchwizardsolutions.com',
    'accessToken' => $accessToken->getToken()
]));

// Email Details
$mail->setFrom('yourname@glitchwizardsolutions.com', 'Your Name');
$mail->addAddress('recipient@example.com');
$mail->Subject = 'Test Email';
$mail->Body = 'This email was sent using OAuth2!';

// Send
$mail->send();
?&gt;</code></pre>
                </div>

                <div class="alert alert-success mb-0">
                  <i class="bi bi-check-circle"></i> 
                  <strong>Benefits of OAuth2:</strong>
                  <ul class="mb-0 mt-2">
                    <li>✅ No passwords stored in your code</li>
                    <li>✅ Future-proof (won't be deprecated like basic auth)</li>
                    <li>✅ More secure with token-based authentication</li>
                    <li>✅ Works with external web applications using your domain emails</li>
                    <li>✅ Granular permissions control</li>
                  </ul>
                </div>

              </div>
            </div>
          </div>
        </div>

      </div>
    </section><!-- End OAuth2 SMTP Validator Section -->

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong><span>GlitchWizard Solutions</span></strong>. All Rights Reserved
      </div>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>
</html>
