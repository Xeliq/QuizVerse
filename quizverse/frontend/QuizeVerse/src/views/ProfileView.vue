<template>
  <div class="profile-container">
    <h1 class="about-title">Profile</h1>
    <section class="profile-header">
      <img src="@/assets/tung_tung.jpg" alt="User Avatar" class="profile-avatar" />

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
          <br>
            <button class="icon-button edit" @click="editQuiz(quiz.id)" title="Edit quiz">
              ✏️
            </button>&nbsp;

            <button class="icon-button delete" @click="deleteQuiz(quiz.id)" title="Delete quiz">
              🗑️
            </button>
          </div>
        </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../axios'
import '../assets/profile.css'

const rank = ref(0)
const percentile = ref(0)
const points = ref(0)
const completedQuizzes = ref([])
const createdQuizzes = ref([])


const route = useRoute()
const router = useRouter()
const token = localStorage.getItem('token')

const quizId = route.params.id
const isEditMode = !!quizId

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


const editQuiz = (quizId) => {
  router.push(`/quizzes/${quizId}/edit`)
}





const deleteQuiz = async (quizId) => {
  const confirmed = confirm('Do you want to delete this quiz?')
  if (!confirmed) return

  try {
    await api.delete(`/quizzes/${quizId}`)


    createdQuizzes.value = createdQuizzes.value.filter(
      quiz => quiz.id !== quizId
    )
  } catch (error) {
    console.error('Error deleting quiz', error)
    alert('Failed to delete quiz')
  }
}







</script>
