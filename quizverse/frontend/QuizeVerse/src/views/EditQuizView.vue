<template>
  <div class="create-quiz-page">
    <div class="create-quiz-box">
      <h1 class="create-quiz-title">
        Edit the <span>Quiz</span>
      </h1>

      <form class="quiz-form" @submit.prevent="submitQuiz" >
        <div class="form-scroll">
          <!-- Quiz Title -->
          <label for="title">Title:</label>
          <input id="title" v-model="quiz.title" name="title" type="text" placeholder="Enter quiz title" />

          <!-- Description -->
          <label for="description">Description:</label>
          <textarea id="description" v-model="quiz.description" name="description" placeholder="Enter quiz description"></textarea>

          <!-- Category -->
          <label for="category">Category:</label>
          <select id="category" name="category_id" v-model="quiz.category_id">
            <option value="">Select Category</option>
            <option v-for="category in categories" :key="category.id" :value="category.id">
              {{ category.name }}
            </option>
          </select>

          <!-- Quiz Image -->
          <label for="quiz-image">Quiz Image (optional):</label>
          <input id="quiz-image" type="file" accept="image/*" ref="quizImageInput" @change="onQuizImageChange" />
          <div v-if="quiz.imagePreview" class="image-preview">
            <img :src="quiz.imagePreview" alt="Quiz image preview" />
          </div>

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
              <input type="file" accept="image/*" @change="onFileChange($event, qIndex)" />

              <div v-if="question.imagePreview" class="image-preview">
                <img :src="question.imagePreview" alt="Question image preview" />
              </div>

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
import api from '../axios'

import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()
const quizId = route.params.id

const quizImageInput = ref(null)


const token = localStorage.getItem('token')

const quiz = ref({
  title: '',
  description: '',
  category_id: '',
  image: null,
  imagePreview: null,
  questions: []
})

const categories = ref([])

async function fetchCategories() {
  try {
    const response = await api.get('/categories/select', {
      headers: { Authorization: `Bearer ${token}` }
    })
    const data = response.data

    if (data.status === 'success') {
      categories.value = Object.entries(data.categories).map(([id, name]) => ({
        id: parseInt(id),
        name
      }))
    } else {
      console.error('Error fetching categories:', data)
    }
  } catch (error) {
    console.error('Network error while fetching categories:', error)
  }
}


async function fetchQuizForEdit() {
  try {
    const response = await api.get(`/quizzes/${quizId}`, {
      headers: { Authorization: `Bearer ${token}` }
    })

    const data = response.data

    quiz.value = {
      title: data.title,
      description: data.description,
      category_id: data.category_id,
      image: null,


      imagePreview: data.image_url ?? null,

      questions: data.questions.map(q => ({
        text: q.text,
        points: q.points,
        image: null,

        imagePreview: q.image_url ?? null,

        answers: q.answers.map(a => ({
          text: a.text,
          is_correct: a.is_correct,
          image: null,

          
          imagePreview: a.image_url ?? null
        }))
      }))
    }

    if (quizImageInput.value) {
      quizImageInput.value.value = null
    }
  } catch (error) {
    console.error(error)
    alert('Failed to load quiz')
  }
}



function onQuizImageChange(event) {
  const file = event.target.files[0]
  if (file) {
    quiz.value.image = file
    quiz.value.imagePreview = URL.createObjectURL(file)
  }
}

function onFileChange(event, qIndex) {
  const file = event.target.files[0]
  if (file) {
    quiz.value.questions[qIndex].image = file
    quiz.value.questions[qIndex].imagePreview = URL.createObjectURL(file)
  }
}





onMounted(() => {
  fetchCategories()
  fetchQuizForEdit()

  const app = document.getElementById('app')
  if (app) {
    // app.style.display = 'flex'
    // app.style.justifyContent = 'center'
    // app.style.alignItems = 'center'
    // app.style.height = '100vh'
    // app.style.width = '100vw'
  }
})

function addQuestion() {
  quiz.value.questions.push({
    text: '',
    points: 1,
    image: null,
    answers: []
  })
}

function removeQuestion(index) {
  quiz.value.questions.splice(index, 1)
}

function addAnswer(questionIndex) {
  quiz.value.questions[questionIndex].answers.push({
    text: '',
    is_correct: false,
    image: null
  })
}



async function submitQuiz() {
  if (!quiz.value.title || !quiz.value.category_id || quiz.value.questions.length === 0) {
    alert('Fill up title, category and at least one question.')
    return
  }

  const formData = new FormData()

  formData.append('title', quiz.value.title)
  formData.append('description', quiz.value.description)
  formData.append('category_id', quiz.value.category_id)

  if (quiz.value.image instanceof File) {
    formData.append('image', quiz.value.image)
  }

  quiz.value.questions.forEach((question, qIndex) => {
    formData.append(`questions[${qIndex}][text]`, question.text)
    formData.append(`questions[${qIndex}][points]`, question.points)

    if (question.image instanceof File) {
      formData.append(`questions[${qIndex}][image]`, question.image)
    }

    question.answers.forEach((answer, aIndex) => {
      formData.append(`questions[${qIndex}][answers][${aIndex}][text]`, answer.text)
      formData.append(
        `questions[${qIndex}][answers][${aIndex}][is_correct]`,
        answer.is_correct ? 'true' : 'false'
      )

      if (answer.image instanceof File) {
        formData.append(
          `questions[${qIndex}][answers][${aIndex}][image]`,
          answer.image
        )
      }
    })
  })

  try {
    formData.append('_method', 'PUT')

    await api.post(`/quizzes/${quizId}`, formData, {
      headers: { 'Content-Type': undefined, Authorization: `Bearer ${token}` }
    })

    alert('Quiz updated successfully')
    router.push('/profile')
  } catch (error) {
  console.error('FULL ERROR:', error)

  if (error.response) {
    console.error('STATUS:', error.response.status)
    console.error('DATA:', error.response.data)
    alert(JSON.stringify(error.response.data, null, 2))
  } else {
    alert('Network / CORS error')
  }
}

}




</script>


