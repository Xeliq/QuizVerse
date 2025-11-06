<template>
  <div class="quiz-page" v-if="quiz">
    <div class="quiz-content">
      <div class="quiz-header">
        <h1 class="quiz-title">{{ quiz.title }}</h1>
        <p class="quiz-meta" v-if="quiz.questions">
          By User#{{ quiz.user_id }} • {{ quiz.questions.length }} Questions
        </p>
      </div>

      <div class="quiz-box">
        <div class="quiz-top">
          <h2>{{ quiz.title }}</h2>
        </div>

        <p class="question-text" v-if="currentQuestion">
          {{ currentIndex + 1 }}. {{ currentQuestion.text }}
        </p>

        <div class="answers" v-if="currentQuestion && currentQuestion.answers">
          <button
            v-for="(answer, index) in currentQuestion.answers"
            :key="answer.id"
            :class="['answer-btn', selectedAnswers[currentIndex] === index ? 'selected' : '']"
            @click="selectAnswer(index)"
          >
            {{ String.fromCharCode(65 + index) }}. {{ answer.text }}
          </button>
        </div>

        <div class="nav-buttons">
          <button class="nav-btn prev-btn" @click="prevQuestion" :disabled="currentIndex === 0">
            &larr; Previous
          </button>
          <button v-if="currentIndex !== quiz.questions.length - 1" class="nav-btn next-btn" @click="nextQuestion" :disabled="currentIndex === quiz.questions.length - 1">
            Next &rarr;
          </button>
          <button
            v-if="currentIndex === quiz.questions.length - 1"
            class="nav-btn submit-btn"
            @click="submitResult"
          >
            Zakończ i zapisz wynik
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import api from '../axios'
import { useRoute } from 'vue-router'

const route = useRoute()
const quizId = route.params.id
const quiz = ref(null)
const currentIndex = ref(0)
const selectedAnswers = ref({})

const currentQuestion = computed(() => {
  if (!quiz.value || !quiz.value.questions || quiz.value.questions.length === 0) return null
  return quiz.value.questions[currentIndex.value] || null
})

async function fetchQuiz() {
  try {
    const response = await api.get('/quizzes/' + quizId, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`
      }
    })
    quiz.value = response.data
  } catch (error) {
    console.error('Błąd pobierania quizu:', error)
  }
}

function nextQuestion() {
  if (currentIndex.value < quiz.value.questions.length - 1) {
    currentIndex.value++
  }
}

function prevQuestion() {
  if (currentIndex.value > 0) {
    currentIndex.value--
  }
}

function selectAnswer(index) {
  selectedAnswers.value[currentIndex.value] = index
}

onMounted(() => {
  fetchQuiz()
})

async function submitResult() {
  let score = 0

  quiz.value.questions.forEach((question, qIndex) => {
    const selectedIndex = selectedAnswers.value[qIndex]
    const selectedAnswer = question.answers[selectedIndex]

    if (selectedAnswer?.is_correct) {
      score += question.points
    }
  })

  try {
    const response = await api.post('/quizzes/save-result', {
      quiz_id: quiz.value.id,
      score: score.toString()
    }, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`
      }
    })

    alert('Wynik zapisany! Zdobyłeś ' + score + ' punktów.')
  } catch (error) {
    console.error('Błąd zapisu wyniku:', error)
    alert('Nie udało się zapisać wyniku.')
  }
}
</script>

<style src="@/assets/QuizView.css"></style>
