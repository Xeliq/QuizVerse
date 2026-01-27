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
        <img src="@/assets/logo.png" alt="Default quiz image" />
      </div>

      <div class="quiz-author">
        <p>Quiz is created by <strong>{{ quiz.user?.name }}</strong></p>
      </div>

      <div class="start-quiz-button-container">
        <button class="start-quiz-button" @click="startQuiz" :disabled="isLoading">
          {{ isLoading ? 'Loading...' : 'Start Quiz' }}
        </button>
      </div>

      <!-- Comments Section -->
      <div class="quiz-comments" v-if="comments.length > 0">
        <h2>Comments: {{ comments.length }}</h2>
        <div class="average-rating" v-if="averageRating">
          <div class="stars">{{ getStarDisplay(averageRating) }}</div>
          <p class="rating-text">{{ averageRating }}/5 ({{ totalComments }} {{ totalComments === 1 ? 'review' : 'reviews' }})</p>
        </div>
        <div class="comment" v-for="comment in comments" :key="comment.id">
          <div class="stars">{{ getStarDisplay(comment.rating) }}</div>
          <p class="comment-text">{{ comment.content }}</p>
          <p class="comment-meta">{{ comment.user_name }} — {{ formatDate(comment.created_at) }}</p>
        </div>
      </div>
      <div class="quiz-comments" v-else>
        <h2>Comments: 0</h2>
        <p class="no-comments">No comments yet. Be the first to leave feedback!</p>
      </div>
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
const comments = ref([])
const averageRating = ref(0)
const totalComments = ref(0)
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
    
    // Fetch comments after quiz data is loaded
    await fetchComments(quizId, token)
  } catch (err) {
    console.error('Fetch quiz error:', err)
    error.value = err.response?.data?.message || 'Failed to load quiz information'
  } finally {
    isLoading.value = false
  }
}

const fetchComments = async (quizId, token) => {
  try {
    // Fetch comments list
    const commentsResponse = await api.get(`/quizzes/${quizId}/comments`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
    comments.value = commentsResponse.data

    // Fetch average rating
    const ratingResponse = await api.get(`/quizzes/${quizId}/comments/rating`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
    averageRating.value = ratingResponse.data.average_rating
    totalComments.value = ratingResponse.data.total_comments
  } catch (err) {
    console.error('Fetch comments error:', err)
    // Don't show error for comments, just leave them empty
    comments.value = []
  }
}

const startQuiz = () => {
  const quizId = route.params.id
  router.push(`/quiz/${quizId}`)
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString()
}

const getStarDisplay = (rating) => {
  const fullStars = Math.floor(rating)
  const hasHalfStar = rating % 1 >= 0.5
  const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0)
  
  return '★'.repeat(fullStars) + 
         (hasHalfStar ? '½' : '') + 
         '☆'.repeat(emptyStars)
}

onMounted(() => {
  fetchQuizData()
})
</script>