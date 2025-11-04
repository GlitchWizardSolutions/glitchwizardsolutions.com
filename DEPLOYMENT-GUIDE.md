# Deployment Guide for OAuth2 SMTP (Without SSH Access)

Since you don't have SSH/Terminal access to your production server, here are your options for deploying the OAuth2 functionality:

## ✅ Option 1: Commit vendor/ Folder (Easiest - Recommended for You)

Since you can't run Composer on production, temporarily commit the vendor folder:

### Steps:

1. **Remove vendor/ from .gitignore temporarily:**
   ```powershell
   # Edit .gitignore and comment out the vendor line:
   # vendor/
   ```

2. **Commit everything:**
   ```powershell
   git add .
   git commit -m "Add OAuth2 SMTP support with dependencies"
   git push
   ```

3. **Deploy to production** (via FTP, cPanel File Manager, or Git pull)

4. **Test validateOAuth2.php** on your live site

5. **Later:** You can uncomment `vendor/` in .gitignore if you gain SSH access

### Pros:
- ✅ Works immediately without server access
- ✅ No configuration needed on production
- ✅ Guaranteed to work

### Cons:
- ❌ Large Git repository (~5-10 MB more)
- ❌ Messy Git diffs when updating packages

---

## 🔧 Option 2: Use cPanel Terminal (If Available)

Some hosting providers include Terminal in cPanel:

### Check if you have access:
1. Log into **cPanel**
2. Look for **"Terminal"** or **"SSH Access"** in the Advanced section
3. If available, you can run:
   ```bash
   cd public_html
   curl -sS https://getcomposer.org/installer | php
   php composer.phar install --no-dev
   ```

---

## 📁 Option 3: Manual Upload via FTP/cPanel File Manager

If Git isn't working or you prefer manual control:

### Steps:
1. **Zip the vendor folder** on your local machine:
   ```powershell
   Compress-Archive -Path vendor -DestinationPath vendor.zip
   ```

2. **Upload vendor.zip** to your server via FTP or cPanel File Manager

3. **Extract on the server** using cPanel File Manager:
   - Navigate to `public_html`
   - Right-click `vendor.zip`
   - Select "Extract"

4. **Delete vendor.zip** after extraction

5. **Also upload:**
   - `composer.json`
   - `composer.lock`
   - `validateOAuth2.php`
   - `OAUTH2-SETUP.md`

---

## 🎯 My Recommendation for Your Situation

**Use Option 1** - Commit the vendor folder to Git.

Here's why:
- ✅ No server access required
- ✅ Works with your current FTP/Git deployment
- ✅ Less prone to errors
- ✅ You can always clean it up later if you get SSH access

The "vendor/ in .gitignore" rule is a **best practice**, but it's not mandatory. Many projects commit dependencies when deployment automation isn't available.

---

## 📝 Current Status

- ✅ Composer installed locally
- ✅ Dependencies installed (PHPMailer, OAuth2 Client)
- ✅ .gitignore created (currently ignoring vendor/)
- ✅ validateOAuth2.php ready to use
- ⏳ Waiting for deployment decision

---

## 🚀 Quick Deploy Commands (Option 1)

```powershell
# 1. Edit .gitignore and comment out line 2:
# Change: vendor/
# To: # vendor/

# 2. Add and commit everything
git add .
git commit -m "Add OAuth2 SMTP validator with vendor dependencies"

# 3. Push to your remote repository
git push origin main

# 4. Deploy to production (your normal process)
```

After deployment, test at:
```
https://glitchwizardsolutions.com/validateOAuth2.php
```
