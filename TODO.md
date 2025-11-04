# TODO List - GlitchWizard Solutions Website

## 🔧 Technical Debt & Improvements

### High Priority

#### [ ] Clean up vendor/ from Git when SSH access is available
**When:** Once you have SSH/Terminal access to production server  
**Why:** Following best practices - dependencies shouldn't be in version control  
**Estimated Time:** 10 minutes

**Steps:**
1. **On Production Server (via SSH):**
   ```bash
   cd public_html
   curl -sS https://getcomposer.org/installer | php
   php composer.phar install --no-dev --optimize-autoloader
   ```

2. **On Local Machine:**
   ```powershell
   # Edit .gitignore and uncomment line 2
   # Change: # vendor/ (temporarily committed...)
   # To: vendor/
   
   # Remove vendor from Git tracking
   git rm -r --cached vendor/
   
   # Commit the change
   git commit -m "Remove vendor/ from Git tracking - now using Composer on production"
   
   # Push to remote
   git push
   ```

3. **Verify on production:**
   - Test that validateOAuth2.php still works
   - Check that vendor/ folder exists and is populated

**Benefits:**
- ✅ Smaller Git repository
- ✅ Cleaner Git diffs
- ✅ Easier to update dependencies
- ✅ Industry best practice

---

### Medium Priority

#### [ ] Enable PHP ZIP Extension for faster Composer installs
**File:** `C:\xampp\php\php.ini`  
**Action:** Find `;extension=zip` and change to `extension=zip`  
**Benefit:** Composer will download packages as ZIP files instead of cloning Git repos (much faster)

#### [ ] Set up DMARC monitoring email
**Current DMARC:** `rua=mailto:dmarc@glitchwizardsolutions.com`  
**Action:** Create dmarc@glitchwizardsolutions.com mailbox or update to existing email  
**Benefit:** Receive reports about email authentication failures

#### [ ] Test OAuth2 SMTP in production
**File:** validateOAuth2.php  
**Action:** Complete Azure AD App Registration and test sending email  
**Documentation:** Follow steps in validateOAuth2.php or OAUTH2-SETUP.md

#### [ ] Update contact form to use OAuth2 instead of basic auth
**File:** forms/contact.php  
**When:** After OAuth2 is tested and working  
**Benefit:** Future-proof before Microsoft deprecates basic auth

---

### Low Priority

#### [ ] Fix SOA record showing old provider
**Current:** Primary nameserver shows ns1.digitalbackups.net  
**Expected:** Should show Microsoft nameserver  
**Impact:** Cosmetic only - doesn't affect functionality  
**Note:** May resolve automatically over time or contact Microsoft support

#### [ ] Consider Azure Communication Services for email
**Alternative to:** Microsoft 365 SMTP OAuth2  
**Pros:** Designed for applications, simpler auth  
**Cons:** Additional service cost  
**Status:** Already tested - had DNS resolution issues

---

## 📝 Documentation Needed

- [ ] Document OAuth2 setup process after first successful configuration
- [ ] Create backup/restore procedures for site
- [ ] Document DNS record purposes and requirements

---

## 🐛 Known Issues

### SMTP Authentication
**Issue:** Microsoft 365 basic auth (username/password) failing  
**Workarounds Tested:**
- Gmail SMTP (working but not ideal)
- Azure Communication Services (DNS issues)
- Direct IP with SSL bypass (working but not secure)

**Solution in Progress:** OAuth2 implementation (validateOAuth2.php)

### DNS Records
**Issue:** Old digitalbackups.net records still appearing in some queries  
**Impact:** Minimal - site and email working correctly  
**Action Required:** None - monitoring for automatic resolution

---

## 💡 Future Enhancements

- [ ] Add rate limiting to contact form
- [ ] Implement honeypot spam protection
- [ ] Add email sending queue for better reliability
- [ ] Set up automated backups
- [ ] Configure CDN for faster asset delivery
- [ ] Add monitoring/alerting for site uptime

---

**Last Updated:** November 4, 2025  
**Maintained By:** Barbara K. Moore (webdev@glitchwizardsolutions.com)
