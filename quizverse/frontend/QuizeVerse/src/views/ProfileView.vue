<template>
  <div class="profile-container">
    <section class="profile-header">
      <img src="@/assets/logo.png" alt="User Avatar" class="profile-avatar" />

      <div class="profile-stats">
        <div class="stat-box">
          <strong>Rank:</strong> {{ rank }} (Top {{ percentile }}%)
        </div>
        <div class="stat-box">
          <strong>Completed Quizzes:</strong> {{ completedQuizzes.length }}
        </div>
        <div class="stat-box">
          <strong>Created Quizzes:</strong> {{ createdQuizzes.length }}
        </div>
        <div class="stat-box">
          <strong>Total Points:</strong> {{ points }}
        </div>
      </div>
    </section>

    <!-- Completed Quizzes -->
    <section class="quiz-section">
      <h2>Completed Quizzes</h2>
      <div class="quiz-list">
        <div v-for="quiz in completedQuizzes" :key="quiz.id" class="quiz-item">
          <p>{{ quiz.title }}</p>
        </div>
      </div>
    </section>

    <!-- Created Quizzes -->
    <section class="quiz-section">
      <h2>Created Quizzes</h2>
      <div class="quiz-list">
        <div v-for="quiz in createdQuizzes" :key="quiz.id" class="quiz-item">
          <p>{{ quiz.title }}</p>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../axios'
import '../assets/profile.css'

const rank = ref(0)
const percentile = ref(0)
const points = ref(0)
const completedQuizzes = ref([])
const createdQuizzes = ref([])

onMounted(async () => {
  try {
    const response = await api.get('/profile')
    const data = response.data

    rank.value = data.rank
    percentile.value = data.percentile
    points.value = data.points
    completedQuizzes.value = data.completedQuizzes ?? []
    createdQuizzes.value = data.createdQuizzes ?? []
  } catch (e) {
    console.error('Failed to load profile', e)
  }
})
</script>
