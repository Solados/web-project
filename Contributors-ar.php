<?php
// Start session before ANY output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>هوية | المساهمون</title>
  <meta name="description" content="تعرف على المساهمين في بناء موقع هوية وما قام به كل شخص.">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@300;400;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="assets/styles.css">
</head>

<body>
<a class="skip-link" href="#main">تخطي إلى المحتوى</a>

<header class="site-header">
  <nav class="navbar" aria-label="التنقل الرئيسي">
    <a class="brand" href="index-ar.php" aria-label="الصفحة الرئيسية">
      <img src="image/Hawiyah.png" alt="الشعار" class="site-logo">
    </a>

    <button class="menu-toggle" aria-expanded="false" aria-controls="nav-links" aria-label="فتح القائمة">☰</button>

    <ul id="nav-links" class="nav-links">
      <?php include 'sign/check_login_status.php'; ?>

      <li class="nav-search">
        <button class="search-toggle" type="button" aria-label="بحث" aria-expanded="false">🔍</button>
        <form class="nav-search-form" action="Search.php" method="get" role="search">
          <input type="search" name="q" placeholder="ابحث عن كلمة..." autocomplete="off">
          <button type="submit">بحث</button>
        </form>
      </li>

      <?php if ($LOGGED_IN): ?>
        <li class="dropdown">
          <a class="dropbtn">الملف الشخصي</a>
          <ul class="dropdown-content">
            <li><a href="dashboard.php">الملف الشخصي</a></li>
            <li><a href="Favorite.php">المفضلة</a></li>
            <li><a href="sign/check_session.php?logout=true" onclick="return confirm('هل أنت متأكد من تسجيل الخروج؟')">تسجيل الخروج</a></li>
          </ul>
        </li>
      <?php else: ?>
        <li><a href="/sign/SignUp_LogIn_Form.html">تسجيل الدخول</a></li>
      <?php endif; ?>

      <li><a href="quiz/QUIZ-ar.php">الاختبارات</a></li>

      <li class="dropdown">
        <a class="dropbtn">الأسئلة</a>
        <ul class="dropdown-content">
          <li><a href="General-ar.php">أسئلة عامة</a></li>
          <li><a href="North-ar.php">أسئلة المنطقة الشمالية</a></li>
          <li><a href="South-ar.php">أسئلة المنطقة الجنوبية</a></li>
          <li><a href="West-ar.php">أسئلة المنطقة الغربية</a></li>
          <li><a href="East-ar.php">أسئلة المنطقة الشرقية</a></li>
          <li><a href="Central-ar.php">أسئلة المنطقة الوسطى</a></li>
        </ul>
      </li>

      <li><a href="index-ar.php">الرئيسية</a></li>
      <li><a href="Contributors.php" style="font-weight:700">English</a></li>
    </ul>
  </nav>
</header>

<main id="main" class="page-content">

  <section class="section section-intro">
    <div class="container">
      <div class="team-intro">
        <h1>المساهمون في بناء الموقع</h1>
        <p>
          هذا القسم يعرض أعضاء الفريق وأبرز ما قام به كل شخص أثناء تطوير موقع «هوية».
        </p>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">

      <div class="team-grid" aria-label="أعضاء الفريق">

        <article class="member-card">
          <div class="member-avatar-wrap">
            <img class="member-avatar" src="image/Hisham.jpg" alt="صورة العضو">
          </div>

          <h2 class="member-name">هشام آل مفرح</h2>
          <p class="member-role">Backend Developer</p>

          <ul class="member-tasks">
            <li>تنفيذ واجهات الصفحات وتنسيقها</li>
            <li>تحسين الاستجابة لمختلف الشاشات</li>
            <li>تحسين تجربة المستخدم في عرض الأسئلة</li>
          </ul>

          <div class="member-links">
            <a class="member-link" href="#" target="_blank" rel="noopener">GitHub</a>
            <a class="member-link" href="#" target="_blank" rel="noopener">LinkedIn</a>
          </div>
        </article>

        <article class="member-card">
          <div class="member-avatar-wrap">
            <img class="member-avatar" src="image/Muhammed.jpeg" alt="صورة العضو">
          </div>

          <h2 class="member-name">محمد المسعودي</h2>
          <p class="member-role">Frontend Developer</p>

          <ul class="member-tasks">
            <li>تطوير منطق البحث/الفلترة</li>
            <li>إدارة الجلسات وتسجيل الدخول</li>
            <li>تنظيم ملفات البيانات وربطها</li>
          </ul>

          <div class="member-links">
            <a class="member-link" href="#" target="_blank" rel="noopener">GitHub</a>
          </div>
        </article>

        <article class="member-card">
          <div class="member-avatar-wrap">
            <img class="member-avatar" src="image/team/member3.jpg" alt="صورة العضو">
          </div>

          <h2 class="member-name">الاسم هنا</h2>
          <p class="member-role">UI/UX + Content</p>

          <ul class="member-tasks">
            <li>تحديد الهوية البصرية (ألوان/مسافات/خطوط)</li>
            <li>تجهيز محتوى المناطق والأسئلة</li>
            <li>مراجعة الاتساق بين العربية والإنجليزية</li>
          </ul>

          <div class="member-links">
            <a class="member-link" href="#" target="_blank" rel="noopener">LinkedIn</a>
          </div>
        </article>

      </div>
    </div>
  </section>

</main>

<script src="assets/script.js"></script>
</body>
</html>
