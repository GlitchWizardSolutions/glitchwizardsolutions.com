# OAuth2 Email Setup Guide for New Workspaces
## Microsoft Graph API with Client Credentials Flow

This guide will help you set up OAuth2 email sending using Microsoft Graph API in any new workspace. This replaces SMTP basic authentication with modern, secure OAuth2.

---

## 📋 What You're Setting Up

- **Microsoft Graph API** email sending (not SMTP)
- **OAuth2 Client Credentials Flow** (no user interaction needed)
- **Dual emails**: Admin notification + customer confirmation
- **Free** - included with Microsoft 365 Business Standard
- **No passwords in code** - uses secure tokens

---

## 🔧 Prerequisites

- Microsoft 365 Business Standard subscription
- Access to Azure Portal (portal.azure.com)
- cPanel or hosting access (for deployment)
- Git repository

---

## 📂 Step 1: Install Composer Locally

If you don't have SSH access to your server, install Composer **locally** in your development environment.

### Windows (PowerShell):

```powershell
cd your-project-folder/public_html
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

This creates `composer.phar` in your `public_html/` folder.

---

## 📦 Step 2: Install PHP Dependencies

Install the OAuth2 client library:

```powershell
cd public_html
php composer.phar require league/oauth2-client
```

**What this does:**
- Creates `composer.json` and `composer.lock`
- Creates `vendor/` folder with ~10 packages
- Installs League OAuth2 Client v2.8.1

**Files created:**
- `public_html/composer.json`
- `public_html/composer.lock`
- `public_html/vendor/` (entire directory)

---

## 🔐 Step 3: Register App in Microsoft Entra ID

### A. Navigate to Azure Portal

1. Go to **portal.azure.com**
2. Search for **"Microsoft Entra ID"** (formerly Azure Active Directory)
3. Click the blue **Microsoft Entra ID** icon (NOT AD B2C or Domain Services)

### B. Create App Registration

1. Left menu → **App registrations** → **+ New registration**
2. **Name:** `[Your Website Name] OAuth2 Email`
3. **Supported account types:** "Accounts in this organizational directory only (Single tenant)"
4. **Redirect URI:** Leave blank (not needed for client credentials)
5. Click **Register**

### C. Copy Your Credentials

From the **Overview** page, copy these:

- ✅ **Application (client) ID** - Format: `12345678-abcd-ef01-2345-6789abcdef01`
- ✅ **Directory (tenant) ID** - Format: `87654321-dcba-10fe-5432-10fedcba9876`

**Save these somewhere safe!**

---

## 🔑 Step 4: Create Client Secret

1. In your app → Left menu → **Certificates & secrets**
2. Click **+ New client secret**
3. **Description:** `OAuth2 Email Secret`
4. **Expires:** 24 months (or 6/12 months if you prefer)
5. Click **Add**
6. **IMMEDIATELY COPY THE VALUE** (long string like: `XyZ123~aBcDeF456gHiJkL789mNoPqR012sTuVwX345yZaBc6`)
   - ⚠️ You can only see this **once**! If you miss it, delete and create new one
   - ❌ Don't copy the "Secret ID" - you need the "Value"

**Save this secret securely!**

---

## 🔓 Step 5: Configure API Permissions

1. In your app → Left menu → **API permissions**
2. Click **+ Add a permission**
3. Select **Microsoft Graph**
4. Choose **Application permissions** (NOT Delegated)
5. Search for and check: **`Mail.Send`**
6. Click **Add permissions**
7. **CRITICAL:** Click **"Grant admin consent for [Your Organization]"**
8. Click **Yes** to confirm
9. **Verify:** You should see a green checkmark that says "Granted for [Your Organization]"

**Why Mail.Send?**
- Allows sending email from ANY mailbox in your tenant
- Works with all verified domains
- No per-user setup needed

---

## 📝 Step 6: Update Configuration File

### A. Create or Update `private/config.php`

Add these lines to your config file (usually in `private/config.php` or similar):

```php
<?php
// Mail server settings
define('support_email', 'youremail@yourdomain.com');  // Where contact forms send TO
define('smtp_from_email', 'youremail@yourdomain.com'); // What email sends FROM

// OAuth2 Settings for Microsoft Graph API (Modern Authentication)
// Get these from Azure Portal > Microsoft Entra ID > App registrations
define('oauth_tenant_id', 'PASTE_YOUR_TENANT_ID_HERE');           // Directory (tenant) ID
define('oauth_client_id', 'PASTE_YOUR_CLIENT_ID_HERE');           // Application (client) ID
define('oauth_client_secret', 'PASTE_YOUR_CLIENT_SECRET_HERE');   // Client secret VALUE (not Secret ID)
?>
```

**Replace:**
- `PASTE_YOUR_TENANT_ID_HERE` with your Directory (tenant) ID from Step 3
- `PASTE_YOUR_CLIENT_ID_HERE` with your Application (client) ID from Step 3
- `PASTE_YOUR_CLIENT_SECRET_HERE` with your Client Secret VALUE from Step 4

### B. Update Production Config

You'll need to manually update this on your production server later (see Step 9).

---

## 📄 Step 7: Copy Required Files

Copy these files from the original workspace to your new workspace:

### A. Create `validateOAuth2.php`

This is your testing page. Copy the entire file from:
- **Source:** `glitchwizardsolutions-com/public_html/validateOAuth2.php`
- **Destination:** `your-new-workspace/public_html/validateOAuth2.php`

**What it does:**
- Tests OAuth2 configuration
- Sends test email via Microsoft Graph API
- Shows detailed error messages
- Contains complete setup instructions

### B. Create or Update Contact Form Handler

Copy the contact form handler from:
- **Source:** `glitchwizardsolutions-com/public_html/forms/contact.php`
- **Destination:** `your-new-workspace/public_html/forms/contact.php`

**What it does:**
- Sends admin notification email
- Sends customer confirmation email
- Uses Microsoft Graph API with OAuth2
- Professional HTML email templates

### C. Update `.gitignore`

Add these lines to `public_html/.gitignore`:

```
# Composer
composer.phar
vendor/

# Private configuration
/private/config.php
```

**Note:** We'll temporarily comment out `vendor/` in Step 8 for initial deployment.

---

## 🚀 Step 8: Deploy to Production (Without SSH)

Since you don't have SSH access, you need to commit the `vendor/` folder once.

### A. Temporarily Allow vendor/ in Git

Edit `public_html/.gitignore` and comment out the vendor line:

```
# Composer
composer.phar
#vendor/  <-- COMMENTED OUT TEMPORARILY

# Private configuration
/private/config.php
```

### B. Add and Commit Everything

```powershell
cd public_html
git add .
git commit -m "Initial OAuth2 setup with vendor dependencies"
git push origin main
```

**What gets committed:**
- `composer.json`
- `composer.lock`
- `vendor/` (entire directory - ~77,000 lines)
- `validateOAuth2.php`
- `forms/contact.php`

### C. Important: Check for Git Submodules

Some Composer packages have `.git` folders that cause issues. Remove them:

```powershell
Get-ChildItem vendor -Recurse -Directory -Filter ".git" -Force | Remove-Item -Recurse -Force
```

Then commit again:

```powershell
git add vendor/
git commit -m "Remove embedded .git folders from vendor packages"
git push origin main
```

---

## 🔧 Step 9: Configure Production Server

### A. Pull Code to Production

In cPanel File Manager or via Git:
1. Navigate to your public_html folder
2. Pull the latest code from your repository

### B. Update Production Config

**CRITICAL:** Edit `/home/youruser/private/config.php` on production server and add your actual OAuth2 credentials from Steps 3 & 4.

**Security Note:** Never commit `private/config.php` to Git!

---

## ✅ Step 10: Test OAuth2 Configuration

### A. Access the Test Page

Navigate to: `https://yourdomain.com/validateOAuth2.php`

### B. Fill in the Test Form

- **Tenant ID:** (from Step 3)
- **Client ID:** (from Step 3)
- **Client Secret:** (from Step 4)
- **From Email:** Your Microsoft 365 email (e.g., `yourname@yourdomain.com`)
- **Test Email:** Where to send the test (can be same as From Email)

### C. Click "Test OAuth2 Configuration"

**Expected Result:**
- ✅ Green success message
- ✅ HTTP Status: 202 Accepted
- ✅ Email arrives in test inbox

**If you get errors:**
- `invalid_client` = Wrong Client ID or Client Secret
- `invalid_request` = Wrong Tenant ID
- `unauthorized_client` = Missing Mail.Send permission or admin consent
- Check the detailed troubleshooting in the error message

---

## 📧 Step 11: Test Contact Form

### A. Access Your Contact Form

Navigate to your contact form page (e.g., `https://yourdomain.com/message-me.php`)

### B. Submit a Test Message

Fill out the form and submit.

**Expected Results:**
1. ✅ Green success message on the website
2. ✅ **Admin email** arrives at `support_email` with:
   - Contact details (name, email, subject)
   - Message content
   - Reply-to set to customer's email
3. ✅ **Customer confirmation email** arrives with:
   - Professional thank you message
   - Copy of their submission
   - Reply-to set to your support email

**Check error_log if emails don't arrive:**
```
"Attempting to send admin email to: ..."
"Admin email sent successfully"
"Attempting to send confirmation email to: ..."
"Confirmation email sent successfully"
```

---

## 🧹 Step 12: Clean Up (Optional - After Everything Works)

Once OAuth2 is working perfectly on production, you can clean up the vendor folder from Git.

**Only do this if you have SSH access OR are comfortable leaving vendor/ in Git!**

### A. Uncomment vendor/ in .gitignore

```
# Composer
composer.phar
vendor/  <-- UNCOMMENTED NOW
```

### B. Remove vendor/ from Git (requires SSH on production)

```powershell
git rm -r --cached vendor/
git commit -m "Remove vendor from Git - use Composer install on server instead"
git push origin main
```

### C. On Production Server (SSH required)

```bash
cd /home/youruser/public_html
php composer.phar install
```

**If you don't have SSH:** Just leave vendor/ in Git. It's not ideal but works fine.

---

## 🔒 Security Checklist

- ✅ `private/config.php` is NOT in Git (check `.gitignore`)
- ✅ OAuth2 credentials are only in production `config.php`
- ✅ Admin consent granted for Mail.Send permission
- ✅ Client secret is kept secure (don't share publicly)
- ✅ Config file is outside of public_html if possible

---

## 📋 File Summary

### Files You Need to Copy:

1. **`public_html/validateOAuth2.php`** - Testing page (615 lines)
2. **`public_html/forms/contact.php`** - Contact form handler (213 lines)
3. **`public_html/composer.json`** - Composer dependencies
4. **`public_html/composer.lock`** - Locked dependency versions
5. **`public_html/vendor/`** - All Composer packages (~77,000 lines)

### Files You Need to Update:

1. **`private/config.php`** - Add OAuth2 credentials
2. **`public_html/.gitignore`** - Temporarily comment out vendor/

---

## 🐛 Common Issues & Solutions

### Issue: "invalid_client" error

**Solution:**
- Double-check Client ID and Client Secret
- Make sure you copied the Secret **Value**, not Secret ID
- Check for extra spaces when copy/pasting
- Create a new client secret and try again

### Issue: "invalid_request" error

**Solution:**
- Verify Tenant ID is correct (Directory tenant ID)
- Check that all three credentials match your Azure app

### Issue: "unauthorized_client" error

**Solution:**
- Go back to API permissions in Azure
- Make sure Mail.Send is listed
- Click "Grant admin consent" again
- Wait 5-10 minutes for permissions to propagate

### Issue: Admin email not arriving

**Solution:**
- Check error_log for "Admin email failed: ..." message
- Verify `support_email` is correct in config.php
- Make sure email address has an active mailbox in Microsoft 365

### Issue: Customer confirmation not arriving

**Solution:**
- Check customer's spam folder
- Verify customer email was valid
- Check error_log for specific error

### Issue: White screen on production after pulling

**Solution:**
- Vendor folder might have Git submodules
- Run: `Get-ChildItem vendor -Recurse -Directory -Filter ".git" -Force | Remove-Item -Recurse -Force`
- Re-commit vendor and push

---

## 📚 Technical Details

### Why Microsoft Graph API Instead of SMTP?

| Feature | SMTP OAuth2 | Graph API OAuth2 |
|---------|-------------|------------------|
| Client Credentials Flow | ❌ Not officially supported | ✅ Fully supported |
| User Interaction | ✅ Required (refresh tokens) | ✅ None needed |
| Setup Complexity | ⚠️ Complex | ✅ Simple |
| Microsoft Recommended | ❌ No | ✅ Yes |
| Future-Proof | ⚠️ Uncertain | ✅ Yes |

### What Gets Installed by Composer?

- `league/oauth2-client` (v2.8.1) - OAuth2 provider
- `guzzlehttp/guzzle` (v7.10.0) - HTTP client
- `guzzlehttp/promises` (v2.3.0) - Async support
- `guzzlehttp/psr7` (v2.8.0) - HTTP messages
- `psr/*` - PHP Standard Recommendations
- Dependencies of the above (~10 packages total)

### How OAuth2 Client Credentials Flow Works:

1. **App authenticates** with Client ID + Client Secret
2. **Microsoft returns** an access token (valid ~60 minutes)
3. **App uses token** to send email via Graph API
4. **No user login** needed - fully automated

---

## 🎯 Success Criteria

You've successfully set up OAuth2 email when:

- ✅ `validateOAuth2.php` shows green success message
- ✅ Test email arrives with "202 Accepted" status
- ✅ Contact form shows green success message
- ✅ Admin notification email arrives
- ✅ Customer confirmation email arrives
- ✅ Both emails have professional HTML formatting
- ✅ Reply buttons work correctly
- ✅ No errors in error_log

---

## 📞 Support

If you get stuck:
1. Check error_log for detailed error messages
2. Review the troubleshooting section in `validateOAuth2.php`
3. Verify all Azure permissions are granted
4. Make sure config.php has correct credentials
5. Test on `validateOAuth2.php` before testing contact form

---

## 🔄 Updating in the Future

### When Client Secret Expires:

1. Go to Azure Portal → Your App → Certificates & secrets
2. Delete old secret
3. Create new secret
4. Copy new Value
5. Update `private/config.php` on production with new secret
6. Test with `validateOAuth2.php`

### When Adding New Domains:

No action needed! Mail.Send permission works for **all verified domains** in your Microsoft 365 tenant.

### When Adding New Mailboxes:

No action needed! Mail.Send permission works for **all mailboxes** in your tenant.

---

**Created:** November 4, 2025  
**For:** Multi-workspace OAuth2 email deployment  
**Technology:** Microsoft Graph API, OAuth2 Client Credentials, PHP, Composer  

---

**End of Guide**
