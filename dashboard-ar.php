<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// جلب حالة المستخدم من الجلسة
$LOGGED_IN = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$USER_NAME = $_SESSION['user_name'] ?? "";
$USER_EMAIL = $_SESSION['user_email'] ?? "";

// Include session check with authentication (kept for existing logic)
include_once 'sign/check_session.php';

// Get user data from session (already validated in check_session.php)
$username = $_SESSION['user_name'] ?? 'User';
$email = $_SESSION['user_email'] ?? 'user@example.com';
$user_id = $_SESSION['user_id'] ?? '';
$login_time = $_SESSION['login_time'] ?? 0;

// Format login date
$join_date = $login_time > 0 ? date('F j, Y', $login_time) : 'Unknown';
?>

<?php
$months = [
    1 => 'يناير', 2 => 'فبراير', 3 => 'مارس',
    4 => 'أبريل', 5 => 'مايو', 6 => 'يونيو',
    7 => 'يوليو', 8 => 'أغسطس', 9 => 'سبتمبر',
    10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر'
];

$time = strtotime($join_date);
$day = date('j', $time);
$month = $months[(int)date('n', $time)];
$year = date('Y', $time);

$join_date_ar = "$day $month $year";
?>

<!doctype html>
<html lang="ar" dir="rtl">
 <!-- Head -->
 <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ثقافة السعودية | ملفي</title>
  <meta name="description" content="إدارة ملفك ومتابعة تقدمك على موقع ثقافة السعودية.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/styles.css">
  <style>
    /* RTL overrides for Arabic layout */
    html, body { direction: rtl; text-align: right; }
    .navbar { flex-direction: row-reverse; }
    .nav-links { flex-direction: row-reverse; }
    .dropdown-content { right: 0; left: auto; text-align: right; }

    .profile-hero {
      background: linear-gradient(135deg, var(--green-700) 0%, var(--green-600) 100%);
      color: white;
      padding: 2rem 0;
      margin-bottom: 2rem;
    }
    
    .profile-header {
      display: grid;
      grid-template-columns: auto 1fr auto;
      gap: 2rem;
      align-items: center;
      padding: 2rem 1rem;
    }
    
    .profile-avatar {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      background: var(--gold-500);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 3rem;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .profile-info h1 {
      margin: 0 0 0.5rem;
      font-size: 2rem;
    }
    
    .profile-info p {
      margin: 0.25rem 0;
      opacity: 0.9;
      font-size: 1.05rem;
    }
    
    .profile-actions {
      display: flex;
      gap: 0.75rem;
      flex-wrap: wrap;
    }
    
    .profile-actions button {
      background: var(--gold-500);
      color: #1a1a1a;
      border: none;
      padding: 0.6rem 1rem;
      border-radius: 0.6rem;
      font-weight: 600;
      cursor: pointer;
      transition: filter 0.2s;
    }
    
    .profile-actions button:hover {
      filter: brightness(1.05);
    }
    
    .profile-actions button.logout {
      background: rgba(255,255,255,0.2);
      color: white;
      border: 1px solid rgba(255,255,255,0.3);
    }
    
    .profile-actions button.logout:hover {
      background: rgba(255,255,255,0.3);
    }
    
    .dashboard-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 1.5rem;
      margin-bottom: 3rem;
    }
    
    .stat-card {
      background: white;
      border: 1px solid var(--sand-200);
      border-radius: 1rem;
      padding: 1.5rem;
      box-shadow: var(--shadow-md);
      text-align: center;
    }
    
    .stat-card .icon {
      font-size: 2.5rem;
      margin-bottom: 0.75rem;
    }
    
    .stat-card .number {
      font-size: 2.2rem;
      font-weight: 700;
      color: var(--green-700);
      margin: 0.5rem 0;
    }
    
    .stat-card .label {
      color: var(--text-700);
      font-size: 0.95rem;
    }
    
    .section-title {
      font-size: 1.5rem;
      color: var(--green-700);
      margin: 2rem 0 1rem;
      padding-bottom: 0.75rem;
      border-bottom: 2px solid var(--gold-500);
    }
    
    .activity-list {
      background: white;
      border: 1px solid var(--sand-200);
      border-radius: 1rem;
      overflow: hidden;
      margin-bottom: 2rem;
    }
    
    .activity-item {
      padding: 1.25rem;
      border-bottom: 1px solid var(--sand-100);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    .activity-item:last-child {
      border-bottom: none;
    }
    
    .activity-content h4 {
      margin: 0 0 0.25rem;
      color: var(--green-700);
    }
    
    .activity-content p {
      margin: 0;
      color: var(--text-700);
      font-size: 0.9rem;
    }
    
    .activity-badge {
      background: var(--sand-100);
      color: var(--green-700);
      padding: 0.4rem 0.8rem;
      border-radius: 0.5rem;
      font-size: 0.85rem;
      font-weight: 600;
    }
    
    .profile-section {
      background: white;
      border: 1px solid var(--sand-200);
      border-radius: 1rem;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
      box-shadow: var(--shadow-md);
    }
    
    .form-group {
      margin-bottom: 1rem;
    }
    
    .form-group label {
      display: block;
      color: var(--text-700);
      font-weight: 600;
      margin-bottom: 0.5rem;
    }
    
    .form-group input {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid var(--sand-200);
      border-radius: 0.6rem;
      font-family: inherit;
      font-size: 1rem;
    }
    
    .form-group input:focus {
      outline: none;
      border-color: var(--green-700);
      box-shadow: 0 0 0 2px rgba(26, 123, 87, 0.1);
    }
    
    .two-col-form {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
    }
    
    @media (max-width: 768px) {
      .profile-header {
        grid-template-columns: 1fr;
        text-align: center;
      }
      
      .profile-actions {
        justify-content: center;
      }
      
      .two-col-form {
        grid-template-columns: 1fr;
      }
      
      .dashboard-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
 </head>
 <!-- Body -->
 <body>
  <header class="site-header">
    <!-- Header -->
    <!-- Navigation -->
    <nav class="navbar" aria-label="التنقل الرئيسي">
     <a class="brand" href="index-ar.php" aria-label="العودة للرئيسية">الثقافة السعودية</a>
    <button class="menu-toggle" aria-expanded="false" aria-controls="nav-links" aria-label="Toggle menu">☰</button>
      <!-- Nav list -->
      <ul id="nav-links" class="nav-links">
      <!-- Language switcher -->

     <?php include 'sign/check_login_status.php'; ?>

<?php if ($LOGGED_IN): ?>
    <li class="dropdown">
            <a class="dropbtn">ملفي</a>
            <!-- Profile dropdown list -->
            <ul class="dropdown-content">
              <li><a href="dashboard.php">ملفي</a></li>
              <li><a href="Favorites.php">المفضلة</a></li>
              <li><a href="My_quizzes.php">اختباراتي</a></li>
              <li><a href="sign/check_session.php?logout=true" onclick="return confirm('هل أنت متأكد أنك تريد تسجيل الخروج؟')">تسجيل الخروج</a></li>
            </ul>
          </li>
    
<?php else: ?>
    <li><a href="/sign/SignUp_LogIn_Form.html">تسجيل الدخول</a></li>
<?php endif; ?>

       <li class="dropdown">
                    <a class="dropbtn">الأسئلة</a>
                    <ul class="dropdown-content" role="menu">
                        <li><a href="../General-ar.php">أسئلة عامة</a></li>
                        <li><a href="../North-ar.php">أسئلة المنطقة الشمالية</a></li>
                        <li><a href="../South-ar.php">أسئلة المنطقة الجنوبية</a></li>
                        <li><a href="../West-ar.php">أسئلة المنطقة الغربية</a></li>
                        <li><a href="../East-ar.php">أسئلة المنطقة الشرقية</a></li>
                        <li><a href="../Central-ar.php">أسئلة المنطقة الوسطى</a></li>
                    </ul>
                </li>

        <li><a href="index-ar.php">الرئيسية</a></li>

        <!-- زر تبديل اللغة — يفتح الصفحة الإنجليزية المطابقة -->
        <li><a href="East.php" style="font-weight:700">English</a></li>
      </ul>
    </nav>
  </header>

  <!-- Profile Hero -->
  <div class="profile-hero">
    <div class="container">
      <div class="profile-header">
        <div class="profile-avatar">👤</div>
        <div class="profile-info">
              <h1><?php echo htmlspecialchars($username); ?></h1>
              <p><?php echo htmlspecialchars($email); ?></p>
              <p style="font-size: 0.95rem;">عضو منذ <?php echo $join_date_ar; ?></p>
            </div>
            <div class="profile-actions">
              <button onclick="document.getElementById('editModal').style.display='flex'">تعديل الملف الشخصي</button>
              <button class="logout" onclick="logout()">تسجيل الخروج</button>
            </div>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <main class="container">
    <!-- Statistics -->
    <h2 class="section-title">تقدمك</h2>
    <div class="dashboard-grid">
      <div class="stat-card">
        <div class="icon">📚</div>
        <div class="number">0</div>
        <div class="label">الإختبارات المكتملة</div>
      </div>
      <div class="stat-card">
        <div class="icon">⭐</div>
        <div class="number">0</div>
        <div class="label">الأجوبة الصحيحة</div>
      </div>
    </div>
    <!-- Profile Settings -->
    <h2 class="section-title">إعدادات الملف الشخصي</h2>
    <div class="profile-section">
      <h3 style="margin-top: 0; color: var(--green-700);">معلومات الحساب</h3>
      
      <div class="form-group">
        <label>اسم المستخدم</label>
        <input type="text" value="<?php echo htmlspecialchars($username); ?>" disabled style="background: var(--sand-100); cursor: not-allowed;">
      </div>
      <div class="form-group">
        <label>البريد الإلكتروني</label>
        <input type="email" value="<?php echo htmlspecialchars($email); ?>" disabled style="background: var(--sand-100); cursor: not-allowed;">
      </div>
      <p style="color: var(--text-700); font-size: 0.9rem; margin-top: 1rem;">اضغط "تعديل الملف الشخصي" لتحديث معلوماتك.</p>
      <div class="profile-actions">
          <button onclick="document.getElementById('editModal').style.display='flex'">تعديل الملف الشخصي</button>
          <button class="logout" onclick="logout()">تسجيل الخروج</button>
        </div>
    </div>
    <!-- Quick Links -->
    <h2 class="section-title">روابط سريعة</h2>
    <div class="dashboard-grid">
      <a href="quiz/QUIZ-en.php" style="text-decoration: none;">
        <div class="stat-card" style="cursor: pointer; transition: transform 0.2s;">
          <div class="icon">📝</div>
          <div class="label" style="font-size: 1rem; font-weight: 600;">ابدأ اختبارًا</div>
        </div>
      </a>
      <a href="General.php" style="text-decoration: none;">
        <div class="stat-card" style="cursor: pointer; transition: transform 0.2s;">
          <div class="icon">🗺️</div>
          <div class="label" style="font-size: 1rem; font-weight: 600;">استكشف المناطق</div>
        </div>
      </a>
      <a href="index.php" style="text-decoration: none;">
        <div class="stat-card" style="cursor: pointer; transition: transform 0.2s;">
          <div class="icon">🏠</div>
          <div class="label" style="font-size: 1rem; font-weight: 600;">العودة للرئيسية</div>
        </div>
      </a>
    </div>
  </main>

  <!-- Edit Profile Modal -->
  <div id="editModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center; padding: 1rem;">
    <div style="background: white; border-radius: 1rem; padding: 2rem; max-width: 500px; width: 100%; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
      <h2 style="margin-top: 0; color: var(--green-700);">تعديل الملف الشخصي</h2>
      <form style="display: flex; flex-direction: column; gap: 1rem;">
        <div class="form-group">
          <label>اسم المستخدم</label>
          <input type="text" value="<?php echo htmlspecialchars($username); ?>" placeholder="أدخل اسم المستخدم">
        </div>
        <div class="form-group">
          <label>البريد الإلكتروني</label>
          <input type="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="أدخل البريد الإلكتروني">
        </div>
        <div class="form-group">
          <label>كلمة المرور (اتركها فارغة إذا لم تتغير)</label>
          <input type="password" placeholder="أدخل كلمة مرور جديدة">
        </div>
        <div style="display: flex; gap: 0.75rem;">
          <button type="button" style="background: var(--gold-500); color: #1a1a1a; border: none; padding: 0.6rem 1rem; border-radius: 0.6rem; font-weight: 600; cursor: pointer; flex: 1;">حفظ التغييرات</button>
          <button type="button" onclick="document.getElementById('editModal').style.display='none'" style="background: var(--sand-200); color: var(--text-700); border: none; padding: 0.6rem 1rem; border-radius: 0.6rem; font-weight: 600; cursor: pointer; flex: 1;">إلغاء</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Footer -->
  <footer class="site-footer" aria-label="تذييل الصفحة">
   <div class="container footer-grid">
    <div>
     <strong>ثقافة السعودية</strong>
     <p>© 2025 جميع الحقوق محفوظة</p>
    </div>
    <ul class="footer-links">
     <li><a href="index-ar.php">العودة للرئيسية</a></li>
     <li><a href="dashboard.php">ملفي</a></li>
    </ul>
   </div>
  </footer>

  <script src="assets/scripts.js"></script>
  <script>
    // Load user stats via AJAX
    function loadUserStats() {
      fetch('../api/user_profile.php?action=get_stats')
        .then(response => response.json())
        .then(data => {
          if (data.success && data.stats) {
            // Update stat cards
            document.querySelectorAll('.stat-card .number')[0].textContent = data.stats.quizzes_completed;
            document.querySelectorAll('.stat-card .number')[1].textContent = data.stats.points_earned;
            document.querySelectorAll('.stat-card .number')[2].textContent = data.stats.achievements;
            document.querySelectorAll('.stat-card .number')[3].textContent = data.stats.regions_explored;
          }
        })
        .catch(error => console.error('Error loading stats:', error));
    }

    // Load stats on page load
    document.addEventListener('DOMContentLoaded', function() {
      loadUserStats();
    });

    // Close modal when clicking outside
    document.getElementById('editModal').addEventListener('click', function(e) {
      if (e.target === this) {
        this.style.display = 'none';
      }
    });

    function logout() {
      if (confirm('Are you sure you want to logout?')) {
        window.location.href = 'sign/check_session.php?logout=true';
      }
    }

    // Add hover effect to quick links
    document.querySelectorAll('.dashboard-grid a .stat-card').forEach(card => {
      card.closest('a').addEventListener('mouseenter', function() {
        card.style.transform = 'translateY(-3px)';
      });
      card.closest('a').addEventListener('mouseleave', function() {
        card.style.transform = 'translateY(0)';
      });
    });
  </script> 

