# OAuth2 SMTP Setup for Microsoft 365

## Quick Start

### 1. Install Dependencies

Open PowerShell in the `public_html` directory and run:

```powershell
composer require phpmailer/phpmailer league/oauth2-client
```

If you don't have Composer installed, download it from: https://getcomposer.org/

### 2. Access the Validator

Once dependencies are installed, navigate to:
```
http://localhost:3000/validateOAuth2.php
```

### 3. Follow the On-Page Instructions

The page includes complete step-by-step instructions for:
- Creating an Azure AD App Registration
- Configuring API permissions
- Getting your Tenant ID, Client ID, and Client Secret
- Testing the OAuth2 SMTP connection

## Why OAuth2?

- ✅ **Future-proof**: Basic authentication (username/password) is being deprecated by Microsoft
- ✅ **More secure**: Uses temporary tokens instead of storing passwords
- ✅ **External applications**: Perfect for sending emails from your website using your domain
- ✅ **No passwords**: Your email password never touches your application code

## Files Created

- `validateOAuth2.php` - OAuth2 SMTP testing and validation tool
- `vendor/` - Composer dependencies (created after running composer install)
- `composer.json` - Dependency configuration (created automatically)

## Need Help?

All detailed instructions are on the validateOAuth2.php page itself, including:
- Azure AD configuration screenshots and steps
- DNS record requirements
- Complete code examples for integration
- Troubleshooting tips
