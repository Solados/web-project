# Hawiyya Cultural Regions & Quizzes

## English

Hawiyya is a bilingual (Arabic / English) informational website focused on Saudi regional culture and interactive quizzes. It combines static region pages with a lightweight PHP profile/dashboard system for user sign-up, authentication, and basic progress tracking. This README presents both English and Arabic content in the same file.

### Key Features

- Clean, responsive design with support for RTL (Arabic) and LTR (English) layouts.
- Region pages (North, South, East, West, Central) with imagery and descriptive content.
- Interactive quizzes with client-side parsing and result tracking.
- Lightweight user profile and dashboard backed by CSV storage (suitable for demos and prototypes).

### Project structure (select files)

- `index.php` Home page
- `index-ar.php` Home page (Arabic)
- `dashboard.php` User profile dashboard (requires login)
- `sign/` Authentication handlers and forms (signup, login, session checks)
- `api/` Minimal API endpoints (profile and quiz-related actions)
- `assets/` CSS and JavaScript (`styles.css`, `script.js`, `quiz-parser.js`)
- `data/` CSV data files (includes `user_data.csv` for the prototype store)
- `image/` Image assets used across the site

### Usage notes

- This repository is intended as a prototype/demo. The included PHP handlers and CSV-backed storage are convenient for local testing and demos but are not production-grade.
- To evaluate PHP-backed features (signup, login, dashboard), run a PHP-capable web server and point your browser to the site root. (Server instructions are intentionally omitted from this document.)

### Authentication & profile system

- Session-based authentication with persistent cookies for convenience during demos.
- Passwords are stored using PHP's `password_hash()`.
- The dashboard and protected pages include session validation via the scripts under `sign/`.

### Developer notes

- Styling and theme variables are in `assets/styles.css`.
- Quiz parsing and client logic live in `assets/quiz-parser.js` and `assets/script.js`.
- `api/user_profile.php` exposes minimal JSON endpoints used by the dashboard.
- Helper files: `debug_profile.php` and `test_profile_system.html` aid local debugging and testing.

### Attribution

Images and some fonts were obtained from public sources (for example, Unsplash and Google Fonts). Replace or re-license assets before deploying to production.

---

## العربية

حوية هو موقع ثنائي اللغة (العربية / الإنجليزية) يقدم معلومات عن المناطق السعودية مع اختبارات تفاعلية. يجمع المشروع صفحات ثابتة لكل منطقة مع نظام ملف شخصي/لوحة تحكم بسيط مبني على PHP لتسجيل المستخدمين والمصادقة وتتبع نتائج الاختبارات الأساسية.

### الميزات الرئيسية

- تصميم مستجيب ونظيف يدعم اتجاهات الكتابة RTL (العربية) وLTR (الإنجليزية).
- صفحات المناطق (الشمال الجنوب الشرق الغرب الوسط) مع صور ومحتوى وصفي.
- اختبارات تفاعلية مع معالجة على جهة العميل وتتبع النتائج.
- نظام ملف شخصي ولوحة تحكم خفيف يعتمد على ملفات CSV (مناسب للعروض والاختبارات التجريبية).

### بنية المشروع (ملفات مختارة)

- `index.php` الصفحة الرئيسية
- `index-ar.php` الصفحة الرئيسية (بالعربية)
- `dashboard.php` لوحة الملف الشخصي (تتطلب تسجيل دخول)
- `sign/` معالجات ونماذج المصادقة (تسجيل دخول فحص الجلسة)
- `api/` نقاط نهاية API بسيطة (عمليات الملف الشخصي والاختبارات)
- `assets/` ملفات CSS وJavaScript (`styles.css`, `script.js`, `quiz-parser.js`)
- `data/` ملفات CSV (بما في ذلك `user_data.csv` لمخزن الاختبارات)
- `image/` ملفات الصور المستخدمة في الموقع

### ملاحظات الاستخدام

- هذا المستودع مخصص كنموذج تجريبي/عرض توضيحي. معالجات PHP ومخزن CSV المضمن ملائمة للاختبار المحلي والعروض وليس للإنتاج.
- لتجربة ميزات PHP (تسجيل دخول لوحة التحكم) شغل خادما يدعم PHP ووجه المتصفح إلى جذر المشروع. (تم حذف تعليمات تشغيل الخادم من هذا المستند عمدا.)

### نظام المصادقة والملف الشخصي

- مصادقة قائمة على الجلسات مع ملفات تعريف ارتباط دائمة لتسهيل الاختبارات.
- كلمات المرور مخزنة باستخدام `password_hash()` في PHP.
- تشمل صفحات لوحة التحكم وحمايتها على فحص الجلسة عبر سكربتات داخل `sign/`.

### ملاحظات للمطورين

- أنماط التصميم والمتغيرات في `assets/styles.css`.
- منطق الاختبارات وجافاسكربت في `assets/quiz-parser.js` و`assets/script.js`.
- يوفر `api/user_profile.php` نقاط نهاية JSON بسيطة للوحة التحكم.
- ملفات المساعدة: `debug_profile.php` و`test_profile_system.html` لتسهيل التصحيح والاختبار المحلي.

### حقوق المصدر

الصور وبعض الخطوط مأخوذة من مصادر عامة (مثل Unsplash وGoogle Fonts). استبدل أو أعد ترخيص العناصر قبل النشر في بيئة إنتاج.

---
