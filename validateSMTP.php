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

  <title>SMTP Configuration Validator - GlitchWizard Solutions</title>
  <meta content="Professional SMTP email configuration testing tool for validating email server settings" name="description">
  <meta content="SMTP test, email configuration, email server validation, SMTP validator" name="keywords">

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

  <!-- =======================================================
  * Updated: October 30 2025 with Bootstrap v5.3.0
  ======================================================== -->
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
    // Start session for POST-Redirect-GET pattern
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // PHP processing for SMTP test
    $test_result = '';
    $show_results = false;
    
    // Check if we have results from a redirect
    if (isset($_SESSION['smtp_test_results'])) {
        $show_results = true;
        $test_result = $_SESSION['smtp_test_results']['test_result'];
        $smtp_host = $_SESSION['smtp_test_results']['smtp_host'];
        $smtp_port = $_SESSION['smtp_test_results']['smtp_port'];
        $smtp_username = $_SESSION['smtp_test_results']['smtp_username'];
        $smtp_encryption = $_SESSION['smtp_test_results']['smtp_encryption'];
        $test_email = $_SESSION['smtp_test_results']['test_email'];
        $from_email = $_SESSION['smtp_test_results']['from_email'];
        if (isset($_SESSION['smtp_test_results']['raw_error_details'])) {
            $raw_error_details = $_SESSION['smtp_test_results']['raw_error_details'];
        }
        // Clear the session data after displaying
        unset($_SESSION['smtp_test_results']);
    }
    
    if ($_POST && isset($_POST['test_smtp'])) {
        // Get form data
        $smtp_host = $_POST['smtp_host'] ?? '';
        $smtp_port = $_POST['smtp_port'] ?? '';
        $smtp_username = $_POST['smtp_username'] ?? '';
        $smtp_password = $_POST['smtp_password'] ?? '';
        $smtp_encryption = $_POST['smtp_encryption'] ?? '';
        $test_email = $_POST['test_email'] ?? '';
        $from_email = $_POST['from_email'] ?? '';
        
        // Include the PHP Email Form library
        if (file_exists('assets/vendor/php-email-form/php-email-form.php')) {
            include('assets/vendor/php-email-form/php-email-form.php');
            
            try {
                $contact = new PHP_Email_Form;
                $contact->ajax = false;
                
                $contact->to = $test_email;
                $contact->from_name = 'SMTP Validator';
                $contact->from_email = $from_email;
                $contact->subject = 'SMTP Test - ' . date('Y-m-d H:i:s');
                
                // Add Reply-To header (important for Gmail SMTP)
                $contact->options = array(
                    'reply_to' => $from_email
                );
                
                // SMTP configuration
                $contact->smtp = array(
                    'host' => $smtp_host,
                    'username' => $smtp_username,
                    'password' => $smtp_password,
                    'port' => (int)$smtp_port,
                    'encryption' => $smtp_encryption
                );
                
                $contact->add_message('This is a test message sent from the SMTP Configuration Validator.', 'Test Message');
                $contact->add_message('Host: ' . $smtp_host, 'SMTP Host');
                $contact->add_message('Port: ' . $smtp_port, 'SMTP Port');
                $contact->add_message('Encryption: ' . $smtp_encryption, 'Encryption');
                $contact->add_message('Username: ' . $smtp_username, 'SMTP Username');
                $contact->add_message('From Email: ' . $from_email, 'From Email');
                $contact->add_message('Timestamp: ' . date('Y-m-d H:i:s'), 'Test Time');
                
                $result = $contact->send();
                
                // Initialize raw error details variable
                $raw_error_details = null;
                
                if ($result === 'OK') {
                    $gmail_note = '';
                    if (strpos($smtp_host, 'gmail') !== false) {
                        $gmail_note = '<br><br><strong>📧 Gmail SMTP Note:</strong><br>
                        <small class="text-info">Gmail may show the email as coming from your authenticated Gmail account (' . htmlspecialchars($smtp_username) . ') 
                        but will set the Reply-To header to your specified From email (' . htmlspecialchars($from_email) . '). 
                        This means replies will go to your business email, which is the desired behavior.</small>';
                    }
                    
                    $test_result = '
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle"></i> 
                        <strong>✅ SUCCESS!</strong><br>
                        <strong>Result:</strong> SMTP configuration is working correctly!<br>
                        <strong>Status:</strong> Test email sent successfully to <code>' . htmlspecialchars($test_email) . '</code><br>
                        <strong>From:</strong> <code>' . htmlspecialchars($from_email) . '</code> (set as Reply-To if using Gmail)<br>
                        <strong>Server:</strong> ' . htmlspecialchars($smtp_host) . ':' . htmlspecialchars($smtp_port) . ' (' . strtoupper($smtp_encryption) . ')<br>
                        <strong>Authentication:</strong> Successful with user <code>' . htmlspecialchars($smtp_username) . '</code><br>
                        <small class="text-muted">✉️ Check your inbox (and spam folder) for the test message.</small>' . $gmail_note . '
                    </div>';
                  
                } else {
                    // Parse the error message for more detailed information
                    $error_details = htmlspecialchars($result);
                    $raw_error_details = $result; // Store raw error for technical display
                    $troubleshooting_tip = '';
                    
                    // Provide specific troubleshooting based on error type
                    if (strpos($result, 'Could not authenticate') !== false) {
                        $troubleshooting_tip = '<br><strong>💡 Troubleshooting:</strong> Authentication failed. Try creating a new App Password in your email provider\'s security settings.';
                    } elseif (strpos($result, 'Could not connect') !== false) {
                        $troubleshooting_tip = '<br><strong>💡 Troubleshooting:</strong> Connection failed. Check if the host and port are correct, or try port 465 with SSL encryption.';
                    } elseif (strpos($result, 'Connection timed out') !== false) {
                        $troubleshooting_tip = '<br><strong>💡 Troubleshooting:</strong> Connection timeout. The server may be blocking the connection or the host/port is incorrect.';
                    } elseif (strpos($result, 'certificate') !== false) {
                        $troubleshooting_tip = '<br><strong>💡 Troubleshooting:</strong> SSL/TLS certificate issue. Try switching between TLS and SSL encryption methods.';
                    } elseif (strpos($result, 'Relay access denied') !== false) {
                        $troubleshooting_tip = '<br><strong>💡 Troubleshooting:</strong> Server rejected the email. Authentication may be required or the username/password is incorrect.';
                    } else {
                        $troubleshooting_tip = '<br><strong>💡 Troubleshooting:</strong> Check your username, password, and server settings. For Microsoft 365 and Gmail, ensure you\'re using an App Password.';
                    }
                    
                    $test_result = '
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i> 
                        <strong>❌ SMTP TEST FAILED</strong><br>
                        <strong>Configuration Tested:</strong><br>
                        • Host: <code>' . htmlspecialchars($smtp_host) . '</code><br>
                        • Port: <code>' . htmlspecialchars($smtp_port) . '</code><br>
                        • Encryption: <code>' . strtoupper($smtp_encryption) . '</code><br>
                        • Username: <code>' . htmlspecialchars($smtp_username) . '</code><br>
                        • From Email: <code>' . htmlspecialchars($from_email) . '</code><br>
                        • Test Email: <code>' . htmlspecialchars($test_email) . '</code><br><br>
                        <strong>🚫 Error Details:</strong><br>
                        <code>' . $error_details . '</code>
                        ' . $troubleshooting_tip . '
                    </div>';
                }
                
            } catch (Exception $e) {
                $exception_message = htmlspecialchars($e->getMessage());
                $raw_error_details = "Exception Type: " . get_class($e) . "\n" .
                                    "Message: " . $e->getMessage() . "\n" .
                                    "File: " . $e->getFile() . "\n" .
                                    "Line: " . $e->getLine() . "\n" .
                                    "Stack Trace:\n" . $e->getTraceAsString();
                $exception_tip = '';
                
                // Provide specific tips based on exception type
                if (strpos($exception_message, 'SMTP') !== false) {
                    $exception_tip = '<br><strong>💡 Troubleshooting:</strong> SMTP-related exception. Check if your email provider supports the configured settings.';
                } elseif (strpos($exception_message, 'Connection') !== false) {
                    $exception_tip = '<br><strong>💡 Troubleshooting:</strong> Network connection issue. Verify your internet connection and server settings.';
                } else {
                    $exception_tip = '<br><strong>💡 Troubleshooting:</strong> General PHP exception. Check your server configuration and email provider requirements.';
                }
                
                $test_result = '
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> 
                    <strong>💥 EXCEPTION OCCURRED</strong><br>
                    <strong>Configuration Attempted:</strong><br>
                    • Host: <code>' . htmlspecialchars($smtp_host) . '</code><br>
                    • Port: <code>' . htmlspecialchars($smtp_port) . '</code><br>
                    • Encryption: <code>' . strtoupper($smtp_encryption) . '</code><br>
                    • Username: <code>' . htmlspecialchars($smtp_username) . '</code><br><br>
                    <strong>⚠️ Exception Details:</strong><br>
                    <code>' . $exception_message . '</code>
                    ' . $exception_tip . '
                </div>';
            }
        } else {
            $test_result = '<div class="alert alert-warning"><i class="bi bi-exclamation-triangle"></i> <strong>ERROR:</strong> PHP Email Form library not found.</div>';
        }
        
        // Store results in session and redirect (POST-Redirect-GET pattern)
        $_SESSION['smtp_test_results'] = array(
            'test_result' => $test_result,
            'smtp_host' => $smtp_host,
            'smtp_port' => $smtp_port,
            'smtp_username' => $smtp_username,
            'smtp_encryption' => $smtp_encryption,
            'test_email' => $test_email,
            'from_email' => $from_email
        );
        
        // Include raw error details if they exist
        if (isset($raw_error_details)) {
            $_SESSION['smtp_test_results']['raw_error_details'] = $raw_error_details;
        }
        
        // Redirect to prevent form resubmission
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
    ?>

    <!-- ======= SMTP Configuration Validator Section ======= -->
    <section id="smtp-validator" class="contact">
      <div class="container">

        <div class="section-title">
          <h2>SMTP Configuration Validator</h2>
          <p>Professional email server configuration testing tool. Test your SMTP settings before deploying to production.</p>
        </div>

        <div class="row justify-content-center">
          <div class="col-lg-8">
            
            <form method="post" action="" class="smtp-validator-form">
              <input type="hidden" name="test_smtp" value="1">
              
              <!-- Loading and message elements -->
              <div class="loading" style="display: none; padding: 15px; text-align: center; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; margin-bottom: 20px;">
                Testing SMTP configuration, please wait...
              </div>
              
              <div class="row">
                <div class="col-md-6 form-group">
                  <label for="smtp_host">SMTP Host</label>
                  <select name="smtp_host" class="form-control" id="smtp_host" required>
                    <option value="smtp.azurecomm.net" <?php echo (isset($_POST['smtp_host']) && $_POST['smtp_host'] == 'smtp.azurecomm.net') ? 'selected' : ''; ?>>smtp.azurecomm.net (Azure)</option>
                    <option value="smtp.office365.com" <?php echo (isset($_POST['smtp_host']) && $_POST['smtp_host'] == 'smtp.office365.com') ? 'selected' : ''; ?>>smtp.office365.com (Microsoft 365)</option>
                    <option value="smtp.gmail.com" <?php echo (isset($_POST['smtp_host']) && $_POST['smtp_host'] == 'smtp.gmail.com') ? 'selected' : ''; ?>>smtp.gmail.com (Gmail)</option>
                    <option value="mail.glitchwizardsolutions.com" <?php echo (isset($_POST['smtp_host']) && $_POST['smtp_host'] == 'mail.glitchwizardsolutions.com') ? 'selected' : ''; ?>>mail.glitchwizardsolutions.com (Custom)</option>
                  </select>
                </div>
                <div class="col-md-6 form-group mt-3 mt-md-0">
                  <label for="smtp_port">SMTP Port</label>
                  <select name="smtp_port" class="form-control" id="smtp_port" required>
                    <option value="587" <?php echo (isset($_POST['smtp_port']) && $_POST['smtp_port'] == '587') ? 'selected' : ''; ?>>587 (TLS)</option>
                    <option value="465" <?php echo (isset($_POST['smtp_port']) && $_POST['smtp_port'] == '465') ? 'selected' : ''; ?>>465 (SSL)</option>
                    <option value="25" <?php echo (isset($_POST['smtp_port']) && $_POST['smtp_port'] == '25') ? 'selected' : ''; ?>>25 (Plain/STARTTLS)</option>
                  </select>
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-6 form-group mt-3">
                  <label for="smtp_encryption">Encryption</label>
                  <select name="smtp_encryption" class="form-control" id="smtp_encryption" required>
                    <option value="tls" <?php echo (isset($_POST['smtp_encryption']) && $_POST['smtp_encryption'] == 'tls') ? 'selected' : ''; ?>>TLS</option>
                    <option value="ssl" <?php echo (isset($_POST['smtp_encryption']) && $_POST['smtp_encryption'] == 'ssl') ? 'selected' : ''; ?>>SSL</option>
                  </select>
                </div>
                <div class="col-md-6 form-group mt-3">
                  <label for="test_email">Test Email Address (To)</label>
                  <select name="test_email" class="form-control" id="test_email" required>
                    <option value="sidewaysy@gmail.com" <?php echo (isset($_POST['test_email']) && $_POST['test_email'] == 'sidewaysy@gmail.com') ? 'selected' : ''; ?>>sidewaysy@gmail.com</option>
                    <option value="webdev@glitchwizardsolutions.com" <?php echo (isset($_POST['test_email']) && $_POST['test_email'] == 'webdev@glitchwizardsolutions.com') ? 'selected' : ''; ?>>webdev@glitchwizardsolutions.com</option>
                    <option value="admin@glitchwizard.website" <?php echo (isset($_POST['test_email']) && $_POST['test_email'] == 'admin@glitchwizard.website') ? 'selected' : ''; ?>>admin@glitchwizard.website</option>
                  </select>
                </div>
              </div>
              
              <div class="form-group mt-3">
                <label for="from_email">From Email Address</label>
                <select name="from_email" class="form-control" id="from_email" required>
                  <option value="webdev@glitchwizardsolutions.com" <?php echo (isset($_POST['from_email']) && $_POST['from_email'] == 'webdev@glitchwizardsolutions.com') ? 'selected' : ''; ?>>webdev@glitchwizardsolutions.com</option>
                  <option value="DoNotReply@glitchwizardsolutions.com" <?php echo (isset($_POST['from_email']) && $_POST['from_email'] == 'DoNotReply@glitchwizardsolutions.com') ? 'selected' : ''; ?>>DoNotReply@glitchwizardsolutions.com</option>
                  <option value="admin@glitchwizard.website" <?php echo (isset($_POST['from_email']) && $_POST['from_email'] == 'admin@glitchwizard.website') ? 'selected' : ''; ?>>admin@glitchwizard.website</option>
                </select>
                <small class="form-text text-muted">This should typically match your SMTP username or be from the same domain</small>
              </div>
              
              <div class="form-group mt-3">
                <label for="smtp_username">SMTP Username</label>
                <input type="text" class="form-control" name="smtp_username" id="smtp_username" 
                       placeholder="your@domain.com or endpoint ID" 
                       value="" autocomplete="off" required>
                <small class="form-text text-muted">Can be an email address, endpoint ID, or username depending on your SMTP provider</small>
              </div>
              
              <div class="form-group mt-3">
                <label for="smtp_password">SMTP Password</label>
                <input type="password" class="form-control" name="smtp_password" id="smtp_password" 
                       placeholder="Your password or app password" value="" autocomplete="off" required>
                <small class="form-text text-muted">For Microsoft 365, use an App Password. For Gmail, enable 2FA and create an App Password.</small>
              </div>
              
              <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-envelope-check"></i> Test SMTP Configuration
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
                        <tr><td><strong>SMTP Host:</strong></td><td><code><?php echo htmlspecialchars($smtp_host); ?></code></td></tr>
                        <tr><td><strong>SMTP Port:</strong></td><td><code><?php echo htmlspecialchars($smtp_port); ?></code></td></tr>
                        <tr><td><strong>Encryption:</strong></td><td><code><?php echo strtoupper($smtp_encryption); ?></code></td></tr>
                        <tr><td><strong>Username:</strong></td><td><code><?php echo htmlspecialchars($smtp_username); ?></code></td></tr>
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
            </form>          </div>
        </div>

        <!-- ======= DNS Records Section ======= -->
        <div class="row mt-5 justify-content-center">
          <div class="col-lg-10">
            <div class="card shadow-sm">
              <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-dns"></i> DNS Records for glitchwizardsolutions.com</h4>
              </div>
              <div class="card-body p-0">
                <?php
                $domain = 'glitchwizardsolutions.com';
                
                // Function to get DNS records
                function getDNSRecords($domain, $type) {
                    $records = @dns_get_record($domain, $type);
                    return $records ? $records : array();
                }
                
                // Get all types of DNS records
                $mx_records = getDNSRecords($domain, DNS_MX);
                $txt_records = getDNSRecords($domain, DNS_TXT);
                $a_records = getDNSRecords($domain, DNS_A);
                $aaaa_records = getDNSRecords($domain, DNS_AAAA);
                $cname_records = getDNSRecords($domain, DNS_CNAME);
                $ns_records = getDNSRecords($domain, DNS_NS);
                $soa_records = getDNSRecords($domain, DNS_SOA);
                
                // Get ALL records at once for any we might have missed
                $all_records = getDNSRecords($domain, DNS_ALL);
                ?>
                
                <div class="table-responsive">
                  <table class="table table-sm table-hover mb-0" style="font-size: 14px;">
                    <thead class="table-light">
                      <tr>
                        <th style="width: 100px;">Type</th>
                        <th>Record Details</th>
                        <th style="width: 100px;">Priority/TTL</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- Nameservers (NS) -->
                      <?php if (!empty($ns_records)): ?>
                        <?php foreach ($ns_records as $record): ?>
                        <tr>
                          <td><span class="badge bg-primary">NS</span></td>
                          <td><code><?php echo htmlspecialchars($record['target']); ?></code></td>
                          <td><small><?php echo $record['ttl']; ?>s</small></td>
                        </tr>
                        <?php endforeach; ?>
                      <?php endif; ?>
                      
                      <!-- MX Records -->
                      <?php if (!empty($mx_records)): ?>
                        <?php foreach ($mx_records as $record): ?>
                        <tr>
                          <td><span class="badge bg-success">MX</span></td>
                          <td><code><?php echo htmlspecialchars($record['target']); ?></code></td>
                          <td>Pri: <?php echo $record['pri']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                      <?php endif; ?>
                      
                      <!-- A Records (IPv4) -->
                      <?php if (!empty($a_records)): ?>
                        <?php foreach ($a_records as $record): ?>
                        <tr>
                          <td><span class="badge bg-info">A</span></td>
                          <td><code><?php echo htmlspecialchars($record['ip']); ?></code></td>
                          <td><small><?php echo $record['ttl']; ?>s</small></td>
                        </tr>
                        <?php endforeach; ?>
                      <?php endif; ?>
                      
                      <!-- AAAA Records (IPv6) -->
                      <?php if (!empty($aaaa_records)): ?>
                        <?php foreach ($aaaa_records as $record): ?>
                        <tr>
                          <td><span class="badge bg-info">AAAA</span></td>
                          <td><code style="font-size: 11px;"><?php echo htmlspecialchars($record['ipv6']); ?></code></td>
                          <td><small><?php echo $record['ttl']; ?>s</small></td>
                        </tr>
                        <?php endforeach; ?>
                      <?php endif; ?>
                      
                      <!-- CNAME Records -->
                      <?php if (!empty($cname_records)): ?>
                        <?php foreach ($cname_records as $record): ?>
                        <tr>
                          <td><span class="badge bg-secondary">CNAME</span></td>
                          <td><code><?php echo htmlspecialchars($record['target']); ?></code></td>
                          <td><small><?php echo $record['ttl']; ?>s</small></td>
                        </tr>
                        <?php endforeach; ?>
                      <?php endif; ?>
                      
                      <!-- TXT Records (SPF, DKIM, DMARC) -->
                      <?php if (!empty($txt_records)): ?>
                        <?php foreach ($txt_records as $record): ?>
                        <tr>
                          <td><span class="badge bg-warning text-dark">TXT</span></td>
                          <td>
                            <code style="word-break: break-all; font-size: 11px;">
                              <?php 
                              $txt = $record['txt'];
                              // Highlight important email-related records
                              if (strpos($txt, 'v=spf') !== false) {
                                  echo '<strong class="text-primary">SPF:</strong> ';
                              } elseif (strpos($txt, 'v=DKIM') !== false) {
                                  echo '<strong class="text-success">DKIM:</strong> ';
                              } elseif (strpos($txt, 'v=DMARC') !== false) {
                                  echo '<strong class="text-danger">DMARC:</strong> ';
                              }
                              echo htmlspecialchars(strlen($txt) > 80 ? substr($txt, 0, 80) . '...' : $txt); 
                              ?>
                            </code>
                          </td>
                          <td><small><?php echo $record['ttl']; ?>s</small></td>
                        </tr>
                        <?php endforeach; ?>
                      <?php endif; ?>
                      
                      <!-- SOA Record -->
                      <?php if (!empty($soa_records)): ?>
                        <?php foreach ($soa_records as $record): ?>
                        <tr>
                          <td><span class="badge bg-dark">SOA</span></td>
                          <td><code style="font-size: 11px;"><?php echo htmlspecialchars($record['mname']); ?> (Serial: <?php echo $record['serial']; ?>)</code></td>
                          <td><small><?php echo $record['ttl']; ?>s</small></td>
                        </tr>
                        <?php endforeach; ?>
                      <?php endif; ?>
                      
                      <?php if (empty($all_records)): ?>
                        <tr>
                          <td colspan="3" class="text-center text-muted">No DNS records found</td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
                
                <div class="p-3 bg-light border-top">
                  <small class="text-muted">
                    <i class="bi bi-info-circle"></i> Showing <?php echo count($all_records); ?> total DNS records. Cache may delay recent changes up to 48 hours.
                  </small>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- ======= Common SMTP Configurations ======= -->
        <div class="row mt-5 justify-content-center">
          <div class="col-lg-10">
            <div class="card shadow-sm">
              <div class="card-header bg-secondary text-white">
                <h4 class="mb-0"><i class="bi bi-server"></i> Common SMTP Configurations</h4>
              </div>
              <div class="card-body p-4">
                <div class="row g-4">
                  <div class="col-lg-3 col-md-6">
                    <div class="p-3 border rounded h-100 bg-light">
                      <div class="text-center mb-3">
                        <i class="bi bi-microsoft" style="font-size: 2rem; color: #0078d4;"></i>
                      </div>
                      <h5 class="text-center mb-3">Microsoft 365</h5>
                      <p class="mb-2"><strong>Host:</strong> <code>smtp.office365.com</code></p>
                      <p class="mb-2"><strong>Port:</strong> <code>587</code></p>
                      <p class="mb-2"><strong>Encryption:</strong> <code>TLS</code></p>
                      <p class="mb-0"><strong>Auth:</strong> <span class="badge bg-warning text-dark">App Password</span></p>
                    </div>
                  </div>

                  <div class="col-lg-3 col-md-6">
                    <div class="p-3 border rounded h-100 bg-light">
                      <div class="text-center mb-3">
                        <i class="bi bi-cloud" style="font-size: 2rem; color: #0078d4;"></i>
                      </div>
                      <h5 class="text-center mb-3">Azure Comm Services</h5>
                      <p class="mb-2"><strong>Host:</strong> <code>smtp.azurecomm.net</code></p>
                      <p class="mb-2"><strong>Port:</strong> <code>587</code></p>
                      <p class="mb-2"><strong>Encryption:</strong> <code>TLS</code></p>
                      <p class="mb-0"><strong>Auth:</strong> <span class="badge bg-info">Endpoint & Key</span></p>
                    </div>
                  </div>

                  <div class="col-lg-3 col-md-6">
                    <div class="p-3 border rounded h-100 bg-light">
                      <div class="text-center mb-3">
                        <i class="bi bi-google" style="font-size: 2rem; color: #db4437;"></i>
                      </div>
                      <h5 class="text-center mb-3">Gmail</h5>
                      <p class="mb-2"><strong>Host:</strong> <code>smtp.gmail.com</code></p>
                      <p class="mb-2"><strong>Port:</strong> <code>587</code></p>
                      <p class="mb-2"><strong>Encryption:</strong> <code>TLS</code></p>
                      <p class="mb-0"><strong>Auth:</strong> <span class="badge bg-warning text-dark">App Password</span></p>
                    </div>
                  </div>

                  <div class="col-lg-3 col-md-6">
                    <div class="p-3 border rounded h-100 bg-light">
                      <div class="text-center mb-3">
                        <i class="bi bi-envelope-at" style="font-size: 2rem; color: #6c757d;"></i>
                      </div>
                      <h5 class="text-center mb-3">Custom/cPanel</h5>
                      <p class="mb-2"><strong>Host:</strong> <code>mail.yourdomain.com</code></p>
                      <p class="mb-2"><strong>Port:</strong> <code>587</code> or <code>465</code></p>
                      <p class="mb-2"><strong>Encryption:</strong> <code>TLS</code> or <code>SSL</code></p>
                      <p class="mb-0"><strong>Auth:</strong> <span class="badge bg-secondary">Regular Password</span></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- ======= Troubleshooting Tips ======= -->
        <div class="row mt-5 justify-content-center">
          <div class="col-lg-10">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title"><i class="bi bi-lightbulb"></i> Troubleshooting Tips</h5>
                <ul class="list-unstyled">
                  <li><i class="bi bi-check-circle text-success"></i> <strong>Microsoft 365:</strong> Enable MFA and create App Password in Security settings</li>
                  <li><i class="bi bi-check-circle text-success"></i> <strong>Azure Communication Services:</strong> Use ACS Endpoint as username and Access Key as password from Azure Portal</li>
                  <li><i class="bi bi-check-circle text-success"></i> <strong>Gmail:</strong> Enable 2FA and generate App Password (not regular password)</li>
                  <li><i class="bi bi-check-circle text-success"></i> <strong>Port 587:</strong> Usually works better than 465 for most providers</li>
                  <li><i class="bi bi-check-circle text-success"></i> <strong>App Passwords:</strong> Use without spaces (e.g., abcdexfghijklmnop)</li>
                  <li><i class="bi bi-check-circle text-success"></i> <strong>Check Spam:</strong> Test emails often go to spam/junk folders</li>
                </ul>
              </div>
            </div>
          </div>
        </div>

      </div>
    </section><!-- End SMTP Validator Section -->
  </main>
   <!-- ======= Footer ======= -->
  <footer id="footer">

    <div class="footer-top">

      <div class="container">

        <div class="row  justify-content-center">
          <div class="col-lg-6">
          
          </div>
        </div>


      </div>
    </div>

    <div class='container footer-bottom clearfix'>
      <div class='copyright'>
  
        <a href='terms.php'>Terms of Service</a> &nbsp; 
        <a href='accessibility.php'>Accessibility Policy</a>  &nbsp; 
        <a href='privacy.php'>Privacy Policy</a>  &nbsp; 
        <a href='javascript:UC_UI.showSecondLayer();' id='usercentrics-psl'>Privacy Settings</a>
      </div>
      <div class='credits'>
        &copy; 2022-<script type='text/JavaScript'>document.write(new Date().getFullYear());</script>&nbsp; GlitchWizard Solutions LLC. All Rights Reserved
      </div>
    </div>
  </footer><!-- End Footer -->

  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  
  <!-- Custom script for SMTP form -->
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    // Find our SMTP validator form
    const smtpForm = document.querySelector('.smtp-validator-form');
    
    if (smtpForm) {
      smtpForm.addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        const loadingDiv = this.querySelector('.loading');
        
        // Show loading state on button
        if (submitBtn) {
          submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Testing...';
          submitBtn.disabled = true;
        }
        
        // Show loading message
        if (loadingDiv) {
          loadingDiv.style.display = 'block';
        }
        
        // Allow normal form submission
        return true;
      });
    }
  });
  </script>

</body>

</html>