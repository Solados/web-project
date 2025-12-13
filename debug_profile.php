<?php
/**
 * Debug Profile System
 * Use this file to check if profile system is working correctly
 * Access: debug_profile.php in your browser
 */

session_start();

echo "<style>
  body { font-family: Arial; margin: 20px; background: #f5f5f5; }
  .section { background: white; padding: 20px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #115e42; }
  h2 { color: #115e42; margin-top: 0; }
  pre { background: #f0f0f0; padding: 10px; overflow-x: auto; }
  .success { color: green; font-weight: bold; }
  .error { color: red; font-weight: bold; }
  .warning { color: orange; font-weight: bold; }
  table { width: 100%; border-collapse: collapse; }
  td { border: 1px solid #ddd; padding: 8px; }
  th { background: #115e42; color: white; padding: 8px; }
</style>";

echo "<h1>🔍 Profile System Debug</h1>";

// 1. Session Check
echo "<div class='section'>";
echo "<h2>1. Session Status</h2>";
echo "<p>Session ID: <code>" . session_id() . "</code></p>";
echo "<p>Session Status: <span class='success'>Active</span></p>";
echo "<table>";
echo "<tr><th>Key</th><th>Value</th></tr>";
foreach ($_SESSION as $key => $value) {
    if (is_array($value)) {
        $value = json_encode($value);
    }
    echo "<tr><td>$key</td><td><code>" . htmlspecialchars((string)$value) . "</code></td></tr>";
}
echo "</table>";
if (empty($_SESSION)) {
    echo "<p class='warning'>⚠️ No session data found. User is not logged in.</p>";
} else {
    echo "<p class='success'>✅ Session data found. User logged in.</p>";
}
echo "</div>";

// 2. Cookie Check
echo "<div class='section'>";
echo "<h2>2. Cookie Status</h2>";
echo "<table>";
echo "<tr><th>Name</th><th>Value</th><th>Status</th></tr>";

$cookies_to_check = ['user_id', 'user_email', 'user_name', 'logged_in', 'PHPSESSID'];
foreach ($cookies_to_check as $cookie_name) {
    if (isset($_COOKIE[$cookie_name])) {
        $value = $_COOKIE[$cookie_name];
        if (strlen($value) > 40) {
            $value = substr($value, 0, 40) . "...";
        }
        echo "<tr><td>$cookie_name</td><td><code>$value</code></td><td><span class='success'>✅ Set</span></td></tr>";
    } else {
        echo "<tr><td>$cookie_name</td><td>-</td><td><span class='error'>❌ Not Set</span></td></tr>";
    }
}
echo "</table>";
echo "</div>";

// 3. Data File Check
echo "<div class='section'>";
echo "<h2>3. Data File Status</h2>";
$data_file = dirname(__DIR__) . '/data/user_data.csv';
if (file_exists($data_file)) {
    echo "<p><span class='success'>✅ user_data.csv found</span></p>";
    echo "<p>Path: <code>$data_file</code></p>";
    echo "<p>Size: " . filesize($data_file) . " bytes</p>";
    echo "<p>Last Modified: " . date('Y-m-d H:i:s', filemtime($data_file)) . "</p>";
    
    // Count users
    if (($handle = fopen($data_file, "r")) !== FALSE) {
        $count = -1; // Exclude header
        while (fgetcsv($handle) !== FALSE) {
            $count++;
        }
        fclose($handle);
        echo "<p>Total Users: $count</p>";
    }
} else {
    echo "<p><span class='error'>❌ user_data.csv not found</span></p>";
    echo "<p>Expected path: <code>$data_file</code></p>";
}
echo "</div>";

// 4. Authentication Check
echo "<div class='section'>";
echo "<h2>4. Authentication Status</h2>";
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    echo "<p><span class='success'>✅ User is LOGGED IN</span></p>";
    echo "<table>";
    echo "<tr><th>Property</th><th>Value</th></tr>";
    echo "<tr><td>Name</td><td>" . htmlspecialchars($_SESSION['user_name'] ?? 'Unknown') . "</td></tr>";
    echo "<tr><td>Email</td><td>" . htmlspecialchars($_SESSION['user_email'] ?? 'Unknown') . "</td></tr>";
    echo "<tr><td>User ID</td><td>" . htmlspecialchars($_SESSION['user_id'] ?? 'Unknown') . "</td></tr>";
    
    if (isset($_SESSION['login_time'])) {
        $login_date = date('Y-m-d H:i:s', $_SESSION['login_time']);
        echo "<tr><td>Login Time</td><td>$login_date</td></tr>";
    }
    
    if (isset($_SESSION['last_activity'])) {
        $last_activity = date('Y-m-d H:i:s', $_SESSION['last_activity']);
        echo "<tr><td>Last Activity</td><td>$last_activity</td></tr>";
    }
    
    if (isset($_SESSION['ip_address'])) {
        echo "<tr><td>IP Address</td><td>" . htmlspecialchars($_SESSION['ip_address']) . "</td></tr>";
    }
    
    echo "</table>";
} else {
    echo "<p><span class='error'>❌ User is NOT logged in</span></p>";
    echo "<p><a href='sign/Signup_Login_Form.html'>Go to Login Page</a></p>";
}
echo "</div>";

// 5. API Test
echo "<div class='section'>";
echo "<h2>5. API Endpoint Test</h2>";
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    echo "<p>Test the profile API endpoints:</p>";
    echo "<ul>";
    echo "<li><a href='api/user_profile.php?action=get_profile' target='_blank'>/api/user_profile.php?action=get_profile</a></li>";
    echo "<li><a href='api/user_profile.php?action=get_stats' target='_blank'>/api/user_profile.php?action=get_stats</a></li>";
    echo "</ul>";
} else {
    echo "<p class='warning'>⚠️ Login first to test API endpoints</p>";
}
echo "</div>";

// 6. Logout Test
echo "<div class='section'>";
echo "<h2>6. Logout Test</h2>";
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    echo "<p><a href='sign/check_session.php?logout=true' style='padding: 10px 20px; background: #d73a49; color: white; border-radius: 5px; text-decoration: none;'>Test Logout</a></p>";
} else {
    echo "<p class='warning'>⚠️ Not logged in. Cannot test logout.</p>";
}
echo "</div>";

// 7. System Information
echo "<div class='section'>";
echo "<h2>7. System Information</h2>";
echo "<table>";
echo "<tr><th>Item</th><th>Value</th></tr>";
echo "<tr><td>PHP Version</td><td>" . phpversion() . "</td></tr>";
echo "<tr><td>Server</td><td>" . htmlspecialchars($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</td></tr>";
echo "<tr><td>Your IP</td><td>" . htmlspecialchars($_SERVER['REMOTE_ADDR'] ?? 'Unknown') . "</td></tr>";
echo "<tr><td>Current File</td><td>" . htmlspecialchars(__FILE__) . "</td></tr>";
echo "<tr><td>Current URL</td><td>" . htmlspecialchars($_SERVER['REQUEST_URI'] ?? 'Unknown') . "</td></tr>";
echo "</table>";
echo "</div>";

echo "<hr>";
echo "<p style='text-align: center; color: #666; margin-top: 30px;'>Debug Profile System | Last Updated: " . date('Y-m-d H:i:s') . "</p>";
?>
