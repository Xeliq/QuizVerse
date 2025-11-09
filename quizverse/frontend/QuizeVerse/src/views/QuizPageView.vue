<template>
  <div class="quiz-page" v-if="quiz">
    <div class="quiz-info">
      <div class="quiz-info-header">
        <h1 class="quiz-info-title">{{ quiz.title }}</h1>
        <p class="quiz-info-description">
          {{ quiz.description }}
        </p>
      </div>

      <div class="quiz-info-meta">
        <p><strong>Number of questions:</strong> {{ quiz.questions.length }}</p>
        <p><strong>Category:</strong> {{ quiz.category?.name }}</p>
      </div>

      <div class="quiz-image" v-if="quiz.image_url">
        <img :src="quiz.image_url" :alt="quiz.title" />
      </div>
      <div class="quiz-image" v-else>
        <img src="@/assets/logo.svg" alt="Default quiz image" />
      </div>

      <div class="quiz-author">
        <p>Quiz is created by <strong>{{ quiz.user?.name }}</strong></p>
      </div>

      <div class="start-quiz-button-container">
        <button class="start-quiz-button" @click="startQuiz" :disabled="isLoading">
          {{ isLoading ? 'Loading...' : 'Start Quiz' }}
        </button>
      </div>

      <div class="quiz-comments">
        <h2>Comments: 1</h2>
        <div class="comment">
          <div class="stars">★★★★★</div>
          <p class="comment-text">Very good test, I recommend it</p>
          <p class="comment-meta">User333 — 10/10/2010</p>
        </div>
      </div>

      <!-- Tutaj póżniej ogarniecie te komentarze -->
      <!-- <div class="quiz-comments" v-if="quiz.comments?.length">
        <h2>Comments: {{ quiz.comments.length }}</h2>
        <div class="comment" v-for="comment in quiz.comments" :key="comment.id">
          <div class="stars">{{ '★'.repeat(comment.rating) }}</div>
          <p class="comment-text">{{ comment.content }}</p>
          <p class="comment-meta">{{ comment.user?.name }} — {{ formatDate(comment.created_at) }}</p>
        </div>
      </div> -->
    </div>
  </div>
  <div v-else-if="error" class="error-message">
    {{ error }}
  </div>
  <div v-else class="loading">
    Loading quiz information...
  </div>
</template>

<script setup>
import '../assets/quiz-page.css'
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import api from '../axios'

const router = useRouter()
const route = useRoute()
const quiz = ref(null)
const error = ref(null)
const isLoading = ref(false)

const fetchQuizData = async () => {
  const quizId = route.params.id
  const token = localStorage.getItem('token')

  if (!token) {
    error.value = 'Please log in to view quiz details'
    return
  }

  try {
    isLoading.value = true
    const response = await api.get(`/quizzes/${quizId}`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
    quiz.value = response.data
  } catch (err) {
    console.error('Fetch quiz error:', err)
    error.value = err.response?.data?.message || 'Failed to load quiz information'
  } finally {
    isLoading.value = false
  }
}

const startQuiz = () => {
  const quizId = route.params.id
  router.push(`/quiz/${quizId}`)
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString()
}

onMounted(() => {
  fetchQuizData()
})
</script>
