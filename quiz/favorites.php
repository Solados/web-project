<?php
// favorites.php
$file = 'user_data.csv';
$favorites = [];

if (file_exists($file)) {
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $favorites[] = $line;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Favorite Questions</title>
<link rel="stylesheet" href="../assets/styles.css">
<link rel="stylesheet" href="../assets/quiz-style.css">
<style>
/* Modal التأكيد */
#confirmModal {
    display: none;
    position: fixed;
    top:0; left:0; right:0; bottom:0;
    background: rgba(0,0,0,0.5);
    z-index: 9999;
    justify-content: center;
    align-items: center;
}
#confirmModal .modal-content {
    background: #fff;
    padding: 20px 25px;
    border-radius: 10px;
    max-width: 400px;
    width: 90%;
    text-align: center;
    box-shadow: 0 8px 28px rgba(0,0,0,0.25);
    font-family: inherit;
}
#confirmModal .modal-content p {
    margin-bottom: 20px;
    font-size: 1.1rem;
}
#confirmModal .modal-buttons {
    display: flex;
    justify-content: center;
    gap: 15px;
}
#confirmModal button {
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    font-size: 1rem;
    transition: 0.3s;
}
#confirmModal .deleteBtn {
    background: #ff4b5c;
    color: white;
}
#confirmModal .deleteBtn:hover {
    background: #e03d50;
}
#confirmModal .cancelBtn {
    background: #ccc;
}
#confirmModal .cancelBtn:hover {
    background: #999;
}
/* الرسالة الفارغة */
.emptyMsg {
    text-align: center;
    font-size: 1.1rem;
    color: #555;
    margin-top: 30px;
    opacity: 0;
    transition: opacity 0.5s ease;
}
.emptyMsg.show {
    opacity: 1;
}
</style>
</head>
<body>
<main>
<h1>Favorite Questions ⭐</h1>
<div id="favoritesContainer">
<?php
if (!empty($favorites)) {
    foreach ($favorites as $index => $fav) {
        echo '<div class="favorite-item" data-index="'.$index.'">';
        echo $fav;
        echo '<button class="deleteBtn" onclick="deleteFavorite('.$index.')">🗑️ Delete</button>';
        echo '</div>';
    }
}
?>
</div>

<!-- نافذة التأكيد -->
<div id="confirmModal">
  <div class="modal-content">
    <p>Are you sure you want to delete this favorite question?</p>
    <div class="modal-buttons">
      <button class="deleteBtn">Delete</button>
      <button class="cancelBtn">Cancel</button>
    </div>
  </div>
</div>

<script>
let currentDeleteIndex = null;

function deleteFavorite(index){
    currentDeleteIndex = index;
    document.getElementById('confirmModal').style.display = 'flex';
}

// زر إلغاء
document.querySelector('#confirmModal .cancelBtn').addEventListener('click', () => {
    document.getElementById('confirmModal').style.display = 'none';
    currentDeleteIndex = null;
});

// زر حذف
document.querySelector('#confirmModal .deleteBtn').addEventListener('click', () => {
    if(currentDeleteIndex === null) return;
    const index = currentDeleteIndex;
    currentDeleteIndex = null;
    document.getElementById('confirmModal').style.display = 'none';

    const container = document.getElementById('favoritesContainer');
    const item = document.querySelector('.favorite-item[data-index="'+index+'"]');
    if(!item) return;

    // تأثير fade + scale
    item.style.transition = "opacity 0.4s ease, transform 0.4s ease";
    item.style.opacity = 0;
    item.style.transform = "scale(0.95)";
    setTimeout(() => {
        item.remove();

        // إضافة الرسالة الفارغة إذا انتهت العناصر
        if(container.querySelectorAll('.favorite-item').length === 0){
            let emptyMsg = document.createElement('p');
            emptyMsg.className = 'emptyMsg';
            emptyMsg.innerHTML = '<span>⭐</span> You currently have no favorite questions.';
            container.appendChild(emptyMsg);
            setTimeout(() => emptyMsg.classList.add('show'), 50);
        }
    }, 400);

    // حذف من CSV
    fetch('delete_favorite.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'index=' + encodeURIComponent(index)
    })
    .then(res => res.text())
    .then(data => {
        showAlert(data, '#4CAF50');
    })
    .catch(err => {
        console.error('Error:', err);
        showAlert('Error deleting the question!', '#F44336');
    });
});

// Alert function
function showAlert(message, bgColor='#4CAF50'){
    const container = document.getElementById('favoritesContainer');
    const msg = document.createElement('div');
    msg.textContent = message;
    msg.style.background = bgColor;
    msg.style.color = 'white';
    msg.style.padding = '10px 15px';
    msg.style.margin = '10px 0';
    msg.style.borderRadius = '6px';
    msg.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
    msg.style.fontWeight = '600';
    msg.style.opacity = 0;
    msg.style.transition = "opacity 0.4s ease, transform 0.4s ease";
    msg.style.transform = "translateY(-10px)";
    container.prepend(msg);

    setTimeout(() => {
        msg.style.opacity = 1;
        msg.style.transform = "translateY(0)";
    }, 10);

    setTimeout(() => {
        msg.style.opacity = 0;
        msg.style.transform = "translateY(-10px)";
        setTimeout(()=> msg.remove(), 400);
    }, 3000);
}

// عند تحميل الصفحة، عرض رسالة فارغة إذا لا توجد عناصر
window.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('favoritesContainer');
    if(container.querySelectorAll('.favorite-item').length === 0){
        let emptyMsg = document.createElement('p');
        emptyMsg.className = 'emptyMsg';
        emptyMsg.innerHTML = '<span>⭐</span> You currently have no favorite questions.';
        container.appendChild(emptyMsg);
        setTimeout(() => emptyMsg.classList.add('show'), 50);
    }
});
</script>
</main>
</body>
</html>
