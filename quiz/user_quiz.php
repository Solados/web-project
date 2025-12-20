<div class="add-question-form">
  <h3>Add New Question</h3>
  <form id="addQuestionForm">
    <div class="form-group">
      <label>Question Type:</label>
      <select id="questionType" class="quiz-gold-select" required>
        <option value="Open-ended">Open-ended</option>
        <option value="MCQ (one correct)">Multiple Choice (one correct)</option>
        <option value="MCQ (multiple correct)">Multiple Choice (multiple correct)</option>
        <option value="True/False">True/False</option>
        <option value="Fill in Blank">Fill in the Blank</option>
      </select>
    </div>
    
    <div class="form-group">
      <label>Domain:</label>
      <select id="domain" class="quiz-gold-select" required>
        <option value="Common">Common</option>
        <option value="Specialized">Specialized</option>
      </select>
    </div>
    
    <div class="form-group">
      <label>Category:</label>
      <select id="category" class="quiz-gold-select" required>
        <option value="Food">Food</option>
        <option value="Clothes">Clothes</option>
        <option value="Celebration">Celebration</option>
        <option value="Entertainment">Entertainment</option>
        <option value="Crafts and Work">Crafts and Work</option>
        <option value="Dating">Dating</option>
        <option value="Languages and Communication">Languages and Communication</option>
      </select>
    </div>
    
    <div class="form-group">
      <label>Question Text:</label>
      <textarea id="question" class="quiz-gold-textarea" placeholder="Enter the question here..." required></textarea>
    </div>
    
    <div class="form-group">
      <label>Correct Answer:</label>
      <textarea id="answer" class="quiz-gold-textarea" placeholder="Correct answer (use comma for multiple answers)" required></textarea>
      <small>For multiple answers, separate with commas (e.g., "Rice, Bread, Meat")</small>
    </div>
    
    <div class="form-group">
      <label>Choices (for multiple choice questions):</label>
      <textarea id="choices" class="quiz-gold-textarea" placeholder="A) Choice 1&#10;B) Choice 2&#10;C) Choice 3&#10;D) Choice 4"></textarea>
      <small>Write each choice on a new line starting with letter (A., B., C., D.)<br>
      For open-ended questions, leave empty or enter "–"</small>
    </div>
    
    <button type="submit" class="quiz-gold-btn">Add Question</button>
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
  display: block;
  margin-top: 5px;
}
</style>

<script>
document.getElementById('addQuestionForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  console.log('Form submission started');
  
  // Get form values
  const formData = new URLSearchParams();
  formData.append('question', document.getElementById('question').value);
  formData.append('answer', document.getElementById('answer').value);
  formData.append('choices', document.getElementById('choices').value);
  formData.append('question_type', document.getElementById('questionType').value);
  formData.append('domain', document.getElementById('domain').value);
  formData.append('category', document.getElementById('category').value);
  
  console.log('Form data:', formData.toString());
  
  const messageDiv = document.getElementById('formMessage');
  messageDiv.innerHTML = 'Adding question...';
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
    messageDiv.innerHTML = '❌ Connection error: ' + error.message;
    messageDiv.style.background = '#ffebee';
    messageDiv.style.color = 'red';
  }
});
</script>