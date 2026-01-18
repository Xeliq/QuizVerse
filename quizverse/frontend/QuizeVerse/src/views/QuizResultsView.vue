<template>
  <div class="results-page" v-if="result">
    <div class="results-box">
      <h1 class="results-title">
        {{ result.quiz.title }} Results
      </h1>

      <div class="results-info">
        <p>Your results: <strong>{{ score }}/{{ total }}</strong> ({{ percentage }}%)</p>
      </div>

      <div class="results-buttons">
        <button class="results-button yellow" @click="tryAgain">Try Again</button>
        <button class="results-button yellow" @click="nextQuiz">Next Quiz</button>
        <button class="results-button white" @click="leaveFeedback">Leave a Feedback</button>
      </div>
    </div>
  </div>

  <div v-else>
    <p>Loading result...</p>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../axios'
import '../assets/QuizResults.css'

const route = useRoute()
const router = useRouter()
const resultId = route.params.id

const result = ref(null)
const score = ref(0)
const total = ref(0)
const percentage = computed(() => total.value ? ((score.value / total.value) * 100).toFixed(0) : 0)

async function fetchResult() {
  try {
    const response = await api.get(`/quiz-results/${resultId}`, {
      headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
    })

    result.value = response.data
    score.value = result.value.score

    // bezpieczne sprawdzenie, czy quiz i questions istnieją
    total.value = result.value.quiz?.questions?.reduce((sum, q) => sum + q.points, 0) || 0
  } catch (error) {
    console.error('Error fetching result:', error)
    alert('Error fetching result.')
  }
}

function tryAgain() {
  router.push(`/quiz/${result.value.quiz.id}`)
}

function nextQuiz() {
  router.push('/quizzes')
}

function leaveFeedback() {
  router.push(`/feedback/${resultId}`)
}

onMounted(() => {
  fetchResult()
})
</script>
