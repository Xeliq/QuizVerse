<template>
  <div class="feedback-page">
    <div class="feedback-box" v-if="!submitted">
      <h1 class="feedback-title">Leave Your Feedback</h1>
      
      <div class="quiz-info" v-if="quizTitle">
        <p>Quiz: <strong>{{ quizTitle }}</strong></p>
      </div>

      <form @submit.prevent="submitFeedback" class="feedback-form">
        <div class="rating-section">
          <label>Rating:</label>
          <div class="stars-input">
            <span 
              v-for="star in 5" 
              :key="star"
              @click="rating = star"
              class="star"
              :class="{ active: star <= rating }"
            >
              ★
            </span>
          </div>
          <p class="rating-text">{{ rating }}/5</p>
        </div>

        <div class="comment-section">
          <label for="comment">Your Comment:</label>
          <textarea
            id="comment"
            v-model="content"
            rows="6"
            placeholder="Share your thoughts about this quiz... (minimum 3 characters)"
            required
            minlength="3"
            maxlength="1000"
          ></textarea>
          <p class="char-count">{{ content.length }}/1000 characters</p>
        </div>

        <div class="feedback-buttons">
          <button type="submit" class="submit-button" :disabled="isSubmitting || !isValid">
            {{ isSubmitting ? 'Submitting...' : 'Submit Feedback' }}
          </button>
          <button type="button" class="cancel-button" @click="goBack">
            Cancel
          </button>
        </div>
      </form>

      <p v-if="error" class="error-message">{{ error }}</p>
    </div>

    <div class="feedback-box success" v-else>
      <div class="success-icon">✓</div>
      <h1 class="feedback-title">Thank You!</h1>
      <p class="success-message">Your feedback has been submitted successfully.</p>
      <div class="feedback-buttons">
        <button class="submit-button" @click="goToQuizPage">View Quiz</button>
        <button class="cancel-button" @click="goToQuizzes">Browse Quizzes</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../axios'
import '../assets/Feedback.css'

const route = useRoute()
const router = useRouter()
const resultId = route.params.id

const rating = ref(5)
const content = ref('')
const quizId = ref(null)
const quizTitle = ref('')
const isSubmitting = ref(false)
const submitted = ref(false)
const error = ref(null)

// Validation: content must be at least 3 characters
const isValid = computed(() => {
  return content.value.trim().length >= 3 && content.value.length <= 1000
})

async function fetchQuizResult() {
  try {
    const response = await api.get(`/quiz-results/${resultId}`, {
      headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
    })
    
    quizId.value = response.data.quiz.id
    quizTitle.value = response.data.quiz.title
  } catch (err) {
    console.error('Error fetching quiz result:', err)
    error.value = 'Failed to load quiz information.'
  }
}

async function submitFeedback() {
  if (!isValid.value) {
    error.value = 'Please enter at least 3 characters for your comment.'
    return
  }

  if (!quizId.value) {
    error.value = 'Quiz information not found.'
    return
  }

  isSubmitting.value = true
  error.value = null

  try {
    const token = localStorage.getItem('token')
    
    // POST /api/quizzes/{quizId}/comments
    await api.post(`/quizzes/${quizId.value}/comments`, {
      content: content.value.trim(),
      rating: rating.value
    }, {
      headers: { Authorization: `Bearer ${token}` }
    })

    submitted.value = true
  } catch (err) {
    console.error('Error submitting feedback:', err)
    
    // Handle validation errors from backend
    if (err.response?.status === 422) {
      const errors = err.response.data.errors
      if (errors) {
        const errorMessages = Object.values(errors).flat()
        error.value = errorMessages.join(', ')
      } else {
        error.value = 'Validation failed. Please check your input.'
      }
    } else {
      error.value = err.response?.data?.message || 'Failed to submit feedback. Please try again.'
    }
  } finally {
    isSubmitting.value = false
  }
}

function goBack() {
  router.push(`/quiz-result/${resultId}`)
}

function goToQuizPage() {
  router.push(`/quiz-page/${quizId.value}`)
}

function goToQuizzes() {
  router.push('/quizzes')
}

onMounted(() => {
  fetchQuizResult()
})
</script>