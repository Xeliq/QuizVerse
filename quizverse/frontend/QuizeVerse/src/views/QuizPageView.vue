<template>
  <div class="quiz-page">
    <div class="quiz-info">
      <div class="quiz-info-header">
        <h1 class="quiz-info-title">Football Players Quiz</h1>
        <p class="quiz-info-description">
          This quiz is testing your knowledge about new generation players and recent events in football
        </p>
      </div>

      <div class="quiz-info-meta">
        <p><strong>Number of questions:</strong> 20</p>
      </div>

      <div class="quiz-image">
        <img src="@/assets/logo.svg" alt="Football on grass" />
      </div>

      <div class="quiz-author">
        <p>Quiz is created by <strong>User1</strong></p>
      </div>

      <div class="start-quiz-button-container">
        <button class="start-quiz-button" @click="startQuiz">Start Quiz</button>
      </div>

      <div class="quiz-comments">
        <h2>Comments: 1</h2>
        <div class="comment">
          <div class="stars">★★★★★</div>
          <p class="comment-text">Very good test, I recommend it</p>
          <p class="comment-meta">User333 — 10/10/2010</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import '../assets/quiz-page.css'
import { useRouter } from 'vue-router'

const router = useRouter()

const startQuiz = () => {
  router.push('/quiz/football-players/start')
  // router.push('/quiz/:id')
  //do zmiany ścieżka, będzie musiało być po id quizu
}

// tutaj bedzie API


</script>
