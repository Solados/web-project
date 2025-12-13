# Profile Dashboard with Session & Cookie Integration

## Overview
The profile dashboard is now fully integrated with the login system, using secure sessions and persistent cookies to manage user data.

## Key Features Implemented

### 1. Enhanced Authentication System
- **30-day persistent cookies** - Users stay logged in for 30 days
- **Session management** - Secure server-side session handling
- **Cookie-based fallback** - Multiple authentication methods
- **Activity tracking** - Last activity timestamp for session validation
- **IP & User-Agent validation** - Security measures against session hijacking

### 2. Profile Dashboard Features
- **User Profile Display** - Shows username, email, and member since date
- **Statistics Cards** - Dynamic display of:
  - Quizzes Completed
  - Points Earned
  - Achievements
  - Regions Explored
- **Profile Settings** - View account information
- **Edit Profile Modal** - Update personal information
- **Quick Links** - Easy navigation to quizzes and regions
- **Logout Functionality** - Secure logout with cookie clearing

### 3. Secure Logout
- Clears all sessions
- Removes all cookies (user_id, user_email, user_name, logged_in, PHPSESSID)
- Destroys session data
- Redirects to login page

## Files Modified

### `/sign/login_check.php`
**Changes:**
- Extended cookie lifetime to 30 days (2,592,000 seconds)
- Extended session lifetime to match
- Added user_id generation (MD5 hash of email + timestamp)
- Set persistent cookies for user_id, user_email, user_name, logged_in
- Store IP address and user agent for security validation
- Updated session paths and domain settings

**Session Data Stored:**
```php
$_SESSION['user_id']         // Unique user identifier
$_SESSION['user_name']       // User's name
$_SESSION['user_email']      // User's email
$_SESSION['logged_in']       // Boolean true
$_SESSION['login_time']      // Unix timestamp
$_SESSION['last_activity']   // Activity tracking
$_SESSION['ip_address']      // IP validation
$_SESSION['user_agent']      // Browser validation
```

### `/sign/save_signup.php`
**Changes:**
- Extended cookie lifetime to 30 days
- Set all persistent cookies after signup
- Store all user data in session immediately after registration
- Generate unique user_id for new users

### `/sign/check_session.php`
**Changes:**
- Unified session configuration (30 days)
- Added logout handling via GET parameter (?logout=true)
- Proper cookie clearing on logout
- Session activity validation (30-day maximum)
- Automatic session expiration after inactivity
- Updated to redirect to login page instead of HTML

**User Data Array Created:**
```php
$user_data = [
    'user_id' => $_SESSION['user_id'],
    'user_name' => $_SESSION['user_name'],
    'user_email' => $_SESSION['user_email'],
    'login_time' => $_SESSION['login_time']
];
```

### `/dashboard.php`
**Changes:**
- Now includes check_session.php for authentication
- Retrieves all user data from sessions and cookies
- Displays member join date dynamically
- Properly escapes all user data with htmlspecialchars()
- Added dynamic stats loading via AJAX
- Enhanced logout functionality

**New Methods:**
- `loadUserStats()` - Fetches stats from API endpoint
- Dynamically updates stat cards on page load

### `/api/user_profile.php` (NEW FILE)
**Features:**
- GET endpoint for fetching user profile
- GET endpoint for fetching user statistics
- POST endpoint for updating profile information
- Session validation on all requests
- JSON responses for AJAX integration
- Data validation and sanitization

**API Endpoints:**
- `GET api/user_profile.php?action=get_profile` - Get profile data
- `GET api/user_profile.php?action=get_stats` - Get user statistics
- `POST api/user_profile.php?action=update_profile` - Update profile

## Security Features

### 1. Session Security
- HTTPOnly cookies (prevents XSS attacks)
- SameSite=Lax (CSRF protection)
- IP address validation
- User-Agent validation
- Activity timeout (30 days)

### 2. Data Protection
- Password hashing using PASSWORD_DEFAULT
- Email validation
- Input sanitization with htmlspecialchars()
- CSV file locking during writes

### 3. Logout Security
- Clears all session data
- Removes all cookies with past expiration
- Session destruction
- Redirect to login

## Cookie Details

### Cookies Set on Login/Signup
- **user_id** - MD5(email + timestamp)
- **user_email** - User's email address
- **user_name** - User's full name
- **logged_in** - '1' flag
- **PHPSESSID** - PHP session cookie

### Cookie Expiration
- Lifetime: 30 days (2,592,000 seconds)
- Path: / (entire domain)
- Domain: '' (current domain)
- Secure: false (set to true for HTTPS)
- HttpOnly: true
- SameSite: Lax

## Session Flow

### Login Process
1. User submits email and password
2. System verifies credentials against user_data.csv
3. Session is created with user data
4. Persistent cookies are set (30 days)
5. User redirected to dashboard or home

### Dashboard Access
1. check_session.php validates session
2. Verifies activity timeout
3. Updates last_activity timestamp
4. Retrieves user data from session
5. Dashboard displays profile information

### Logout Process
1. User clicks logout
2. Confirmation dialog shown
3. check_session.php?logout=true is called
4. All cookies are cleared
5. Session is destroyed
6. User redirected to login

## How to Use

### For Users
1. **Login** - Go to sign/Signup_Login_Form.html
2. **Dashboard** - Access dashboard.php after login
3. **Stay Logged In** - Browser will remember for 30 days
4. **Logout** - Click the logout button to clear all data

### For Developers
1. Check `check_session.php` at the top of protected pages
2. Use `$_SESSION` array to access user data
3. Call `/api/user_profile.php` for dynamic data
4. Always sanitize user data with htmlspecialchars()

## Testing

### Test Cases
1. **Login** - Verify session and cookies are created
2. **Dashboard** - Check user data displays correctly
3. **Page Refresh** - Verify persistence of login state
4. **Close Browser** - Reopen and check 30-day persistence
5. **Logout** - Verify all data is cleared
6. **Direct URL Access** - Non-logged-in users redirected to login

## Future Enhancements

1. Add profile picture upload
2. Implement change password functionality
3. Add email verification
4. Implement two-factor authentication
5. Add user activity log
6. Create profile completion percentage
7. Add quiz history tracking
8. Implement achievement system

## Notes

- Currently using CSV file storage; consider migrating to database
- Cookie lifetime set to 30 days; adjust as needed
- Activity timeout also set to 30 days; consider shorter for security
- All data is server-side validated; never trust client-side validation
