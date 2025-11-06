<template>
  <div class="create-quiz-page">
    <div class="create-quiz-box">
      <h1 class="create-quiz-title">
        Create a <span class="quizverse-text">Quiz</span>
      </h1>

      <form class="quiz-form">
        <div class="form-scroll">
          <!-- Quiz Title -->
          <label for="title">Title:</label>
          <input id="title" v-model="quiz.title" type="text" placeholder="Enter quiz title" />

          <!-- Description -->
          <label for="description">Description:</label>
          <textarea id="description" v-model="quiz.description" placeholder="Enter quiz description"></textarea>

          <!-- Category -->
          <label for="category">Category:</label>
          <select id="category" v-model="quiz.category_id">
            <option value="">Select Category</option>
            <option v-for="category in categories" :key="category.id" :value="category.id">
              {{ category.name }}
            </option>
          </select>

          <!-- Questions -->
          <div class="questions-section">
            <h2>Questions</h2>

            <div
              v-for="(question, qIndex) in quiz.questions"
              :key="qIndex"
              class="question-block"
            >
              <label>Question {{ qIndex + 1 }}:</label>
              <input
                type="text"
                v-model="question.text"
                placeholder="Type your question"
              />

              <!-- Points -->
              <label>Points:</label>
              <input
                type="number"
                v-model="question.points"
                min="1"
                placeholder="1"
              />

              <!-- Optional image -->
              <label>Image (optional):</label>
              <input type="file" @change="onFileChange($event, question)" />

              <!-- Answers -->
              <div class="answers-section">
                <h3>Answers</h3>
                <div
                  v-for="(answer, aIndex) in question.answers"
                  :key="aIndex"
                  class="answer-row"
                >
                  <input
                    type="text"
                    v-model="answer.text"
                    placeholder="Answer text"
                  />
                  <label>
                    <input
                      type="checkbox"
                      v-model="answer.is_correct"
                    />
                    Correct
                  </label>
                </div>

                <button type="button" @click="addAnswer(qIndex)">
                  + Add Answer
                </button>
              </div>

              <button type="button" @click="removeQuestion(qIndex)">
                🗑 Remove Question
              </button>
            </div>

            <button type="button" @click="addQuestion">
              + Add Question
            </button>
          </div>
        </div>

        <div class="button-row">
          <button type="submit">Save Quiz</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import '../assets/create-quiz.css'
import { ref, onMounted } from 'vue'

const quiz = ref({
  title: '',
  description: '',
  category_id: '',
  questions: []
})

// Temporary categories (since backend not connected yet)
const categories = ref([
  { id: 1, name: 'Science' },
  { id: 2, name: 'History' },
  { id: 3, name: 'Math' }
])

// Add/Remove Question
function addQuestion() {
  quiz.value.questions.push({
    text: '',
    points: 1,
    image_path: null,
    answers: []
  })
}

function removeQuestion(index) {
  quiz.value.questions.splice(index, 1)
}

// Add Answer to a Question
function addAnswer(questionIndex) {
  quiz.value.questions[questionIndex].answers.push({
    text: '',
    is_correct: false,
    image_path: null
  })
}

// Handle File Upload (frontend only)
function onFileChange(event, question) {
  const file = event.target.files[0]
  if (file) question.image_path = file.name
}

onMounted(() => {
  const app = document.getElementById('app')
  if (app) {
    app.style.display = 'flex'
    app.style.justifyContent = 'center'
    app.style.alignItems = 'center'
    app.style.height = '100vh'
    app.style.width = '100vw'
  }
})
</script>
