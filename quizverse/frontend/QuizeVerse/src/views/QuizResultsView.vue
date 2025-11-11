<template>
  <div class="results-page">
    <div class="results-box">
      <h1 class="results-title">
        Football Quiz Results
      </h1>

      <div class="results-info">
        <p>Your results: <strong>{{ score }}/{{ total }}</strong> ({{ percentage }}%)</p>
        <p>Time: <strong>{{ time }}</strong></p>
      </div>

      <div class="results-buttons">
        <button class="results-button yellow" @click="tryAgain">Try Again</button>
        <button class="results-button yellow" @click="nextQuiz">Next Quiz</button>
        <button class="results-button white" @click="leaveFeedback">Leave a Feedback</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import '../assets/QuizResults.css'
const score = ref(25)
const total = ref(50)
const percentage = computed(() => ((score.value / total.value) * 100).toFixed(0))
const time = ref('00:50')

function tryAgain() { alert('Restarting quiz...') }
function nextQuiz() { alert('Loading next quiz...') }
function leaveFeedback() { alert('Redirecting to feedback form...') }
function showAnswers() { alert('Showing answers...') }

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

