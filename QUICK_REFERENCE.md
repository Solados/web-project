# Quick Reference: Profile Dashboard Integration

## What Changed

### ✅ Session & Cookie System (30 days)
- Login stores session data on server
- Cookies store user info on client browser
- Users stay logged in for 30 days
- Session expires after 30 days of inactivity

### ✅ Files Updated
1. **sign/login_check.php** - Enhanced login with persistent cookies
2. **sign/save_signup.php** - Auto-login after signup with cookies
3. **sign/check_session.php** - Session validation & logout handling
4. **dashboard.php** - Now uses real session data + cookies
5. **api/user_profile.php** - NEW API for profile operations

### ✅ New Features
- User stays logged in across browser restarts
- Dashboard displays actual user data from session
- Dynamic statistics loading
- Secure logout clears all data
- Session activity tracking

## User Experience Flow

```
1. User Login/Signup
   ↓
2. Session Created (Server)
3. Cookies Set (Client - 30 days)
   ↓
4. Dashboard Accessible
   ↓
5. Can Stay Logged In for 30 Days
   ↓
6. Click Logout
   ↓
7. All Cookies + Session Cleared
8. Redirect to Login
```

## Technical Architecture

```
Session Storage (Server):
├── user_id         (MD5 hash)
├── user_name       (from CSV)
├── user_email      (from CSV)
├── logged_in       (boolean)
├── login_time      (unix timestamp)
├── last_activity   (unix timestamp)
├── ip_address      (for validation)
└── user_agent      (for validation)

Cookie Storage (Client - 30 days):
├── user_id
├── user_email
├── user_name
└── logged_in
```

## How It Works

### Login Process
```
1. User submits credentials
2. login_check.php verifies against user_data.csv
3. Session is created with user data
4. Cookies are set on client (30 days)
5. JSON response redirects to dashboard
```

### Dashboard Access
```
1. dashboard.php includes check_session.php
2. check_session.php validates session
3. If expired/missing → Redirects to login
4. If valid → User data available in $_SESSION
5. Dashboard displays info + loads stats via AJAX
```

### Logout Process
```
1. User clicks logout button
2. Redirects to: check_session.php?logout=true
3. All cookies cleared with past expiration
4. Session destroyed
5. Redirects back to login
```

## Protected Pages

Any page that includes `check_session.php` is automatically protected:

```php
<?php
include_once 'sign/check_session.php';
// If user not logged in → auto redirects to login
// If logged in → $_SESSION data available
?>
```

## Important URLs

- **Login Form:** `sign/Signup_Login_Form.html`
- **Dashboard:** `dashboard.php`
- **API Profile:** `api/user_profile.php?action=get_stats`
- **Logout:** `sign/check_session.php?logout=true`

## Security Measures

✅ HTTPOnly Cookies (XSS protection)
✅ SameSite=Lax (CSRF protection)
✅ IP Address Validation
✅ User-Agent Validation
✅ 30-day Activity Timeout
✅ Password Hashing (PASSWORD_DEFAULT)
✅ Input Sanitization
✅ CSV File Locking

## Testing Checklist

- [ ] Login works → session created
- [ ] Cookies visible in browser
- [ ] Dashboard displays user info
- [ ] Page refresh → user still logged in
- [ ] Close browser → reopen → still logged in
- [ ] Logout clears cookies
- [ ] Direct dashboard access → redirects to login
- [ ] Edit profile modal works
- [ ] Stats load dynamically
- [ ] Different browser/computer → login again

## Customization

### Change Cookie Lifetime
Edit these files and change `2592000` (30 days):
- `sign/login_check.php` (line 2)
- `sign/save_signup.php` (line ~95)
- `sign/check_session.php` (line 2)

Value in seconds:
- 86400 = 1 day
- 604800 = 7 days
- 2592000 = 30 days

### Enable HTTPS Cookies
In session_set_cookie_params(), change:
```php
'secure' => false,  // Change to true for HTTPS
```

### Add More User Fields
1. Add columns to user_data.csv header
2. Update save_signup.php to save new fields
3. Update login_check.php to retrieve new fields
4. Update check_session.php to store in session
5. Access in dashboard via $_SESSION

## Common Issues

**Issue: Not staying logged in after restart**
→ Check cookie settings in check_session.php

**Issue: User data not showing**
→ Verify session data stored in login_check.php

**Issue: Logout not working**
→ Check check_session.php?logout=true URL

**Issue: Dashboard not accessible**
→ Verify check_session.php is included

## API Usage

### Get User Stats
```javascript
fetch('/api/user_profile.php?action=get_stats')
  .then(r => r.json())
  .then(data => console.log(data.stats))
```

### Get Profile
```javascript
fetch('/api/user_profile.php?action=get_profile')
  .then(r => r.json())
  .then(data => console.log(data.data))
```

### Update Profile
```javascript
fetch('/api/user_profile.php', {
  method: 'POST',
  body: new FormData(form)
})
```

---

**Last Updated:** December 10, 2025
**Version:** 1.0
