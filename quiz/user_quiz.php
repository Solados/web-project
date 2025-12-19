<div class="add-question-form">
  <h3>أضف سؤالاً جديداً</h3>
  <form id="addQuestionForm">
    <div class="form-group">
      <label>نوع السؤال:</label>
      <select id="questionType" class="quiz-gold-select" required>
        <option value="Location_Recognition_question">تعرّف على الموقع</option>
        <option value="Cultural_Interpretation_question">التفسير الثقافي</option>
        <option value="Contextual_Usage_question">الاستخدام السياقي</option>
        <option value="Fill_in_Blank_question">املأ الفراغ</option>
        <option value="True_False_question">صح أم خطأ</option>
        <option value="Meaning_question">معنى الكلمة</option>
      </select>
    </div>
    
    <div class="form-group">
      <label>اللهجة:</label>
      <select id="questionDialect" class="quiz-gold-select" required>
        <option value="northern">شمالي</option>
        <option value="southern">جنوبي</option>
        <option value="eastern">شرقي</option>
        <option value="western">غربي</option>
        <option value="central">وسطى</option>
        <option value="general">عام</option>
      </select>
    </div>
    
    <div class="form-group">
      <label>نص السؤال:</label>
      <textarea id="questionText" class="quiz-gold-textarea" placeholder="اكتب السؤال هنا..." required></textarea>
    </div>
    
    <div class="form-group">
      <label>الإجابة الصحيحة:</label>
      <input type="text" id="correctAnswer" class="quiz-gold-input" placeholder="الإجابة الصحيحة" required>
    </div>
    
    <div class="form-group">
      <label>الخيارات (اختياري - للمتعدد):</label>
      <textarea id="questionChoices" class="quiz-gold-textarea" placeholder="أ) الخيار الأول&#10;ب) الخيار الثاني&#10;ج) الخيار الثالث"></textarea>
      <small>اكتب كل خيار في سطر جديد مع حرفه العربي (أ، ب، ج، د)</small>
    </div>
    
    <button type="submit" class="quiz-gold-btn">إضافة السؤال</button>
    <div id="formMessage" style="margin-top: 10px;"></div>
  </form>
</div>
<style>
.add-question-form {
  background: #f9f5eb;
  border: 2px solid #e6d5a8;
  border-radius: 12px;
  padding: 25px;
  margin: 30px 0;
}

.form-group {
  margin-bottom: 20px;
}

.quiz-gold-input, .quiz-gold-textarea {
  width: 100%;
  padding: 12px 14px;
  border-radius: 12px;
  border: 1.8px solid rgba(215,181,109,0.55);
  background: linear-gradient(180deg, #fff9ef, #f7e9cd);
  color: #4b2d1b;
  font-size: 15px;
  font-family: 'Noto Kufi Arabic', sans-serif;
}

.quiz-gold-textarea {
  min-height: 100px;
  resize: vertical;
}

small {
  color: #8a6d3b;
  font-size: 13px;
}
</style>
<script>
    document.getElementById('addQuestionForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  console.log('Form submission started');
  
  // Get form values
  const formData = new URLSearchParams();
  formData.append('type', document.getElementById('questionType').value);
  formData.append('dialect', document.getElementById('questionDialect').value);
  formData.append('question', document.getElementById('questionText').value);
  formData.append('answer', document.getElementById('correctAnswer').value);
  formData.append('choices', document.getElementById('questionChoices').value);
  
  console.log('Form data:', formData.toString());
  
  const messageDiv = document.getElementById('formMessage');
  messageDiv.innerHTML = 'جارٍ إضافة السؤال...';
  messageDiv.style.cssText = 'padding: 10px; margin: 10px 0; border-radius: 5px; background: #fff9ef; color: #4b2d1b;';
  
  try {
    // Send to PHP
    const response = await fetch('save_question.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
      },
      body: formData.toString()
    });
    
    console.log('Response status:', response.status);
    
    const result = await response.json();
    console.log('Response data:', result);
    
    if (result.success) {
      messageDiv.innerHTML = '✅ ' + result.message;
      messageDiv.style.background = '#e8f5e8';
      messageDiv.style.color = 'green';
      
      // Reset form
      document.getElementById('addQuestionForm').reset();
      
      // Clear message after 5 seconds
      setTimeout(() => {
        messageDiv.innerHTML = '';
        messageDiv.style.cssText = '';
      }, 5000);
    } else {
      messageDiv.innerHTML = '❌ ' + result.message;
      messageDiv.style.background = '#ffebee';
      messageDiv.style.color = 'red';
      
      // Show debug info if available
      if (result.debug) {
        console.error('Debug info:', result.debug);
      }
    }
  } catch (error) {
    console.error('Fetch error:', error);
    messageDiv.innerHTML = '❌ حدث خطأ في الاتصال بالخادم: ' + error.message;
    messageDiv.style.background = '#ffebee';
    messageDiv.style.color = 'red';
  }
});
</script>

