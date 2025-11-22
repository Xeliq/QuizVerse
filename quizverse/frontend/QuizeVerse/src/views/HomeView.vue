<template>
  <div class="home-container">

    <!-- Hero Section -->
    <section class="hero-section">
      <h1 class="hero-title">Welcome to QuizVerse</h1>
      <p class="hero-subtitle">Challenge yourself. Learn. Have fun.</p>

      <div class="hero-buttons">
        <button class="btn-primary" @click="goTo('/quizzes')">Play Quiz</button>
        <button class="btn-secondary" @click="goToCreateQuiz">Create Quiz</button>
      </div>
    </section>

    <!-- Trending Quizzes -->
    <section class="trending-section">
      <h2 class="section-title">Trending Quizzes</h2>

      <div class="trending-list">
        <div
          v-for="quiz in quizzes.slice(0, 5)"
          :key="quiz.id"
          class="quiz-card"
          @click="goTo(`/quiz-page/${quiz.id}`)"
        >
          <div class="quiz-card-header">
            <h3 class="quiz-title">{{ quiz.title }}</h3>
          </div>

          <div class="quiz-card-body">
            <p class="quiz-description">{{ quiz.description }}</p>
            <p class="quiz-meta" v-if="quiz.category">Category: {{ quiz.category.name }}</p>
          </div>

          <div class="quiz-card-footer">
            <p class="quiz-meta">Played {{ quiz.plays ?? 'N/A' }} times</p>
            <!-- trzeba będzie to potem ogarnąć z backendu -->
          </div>
        </div>
      </div>
    </section>

    <!-- Why Use Section -->
    <section class="why-section">
      <h2 class="section-title">Why use QuizVerse?</h2>

      <ul class="why-list">
        <li>✅ Create quizzes easily</li>
        <li>✅ Share with friends</li>
        <li>✅ Track results</li>
      </ul>
    </section>

    <!-- How it works Section -->
    <section class="how-section">
      <h2 class="section-title">How it works?</h2>
      <div class="how-steps">
        <div class="step">1️. Register or Login</div>
        <div class="step">2️. Choose or Create a Quiz</div>
        <div class="step">3️. Play and Compete</div>
      </div>
    </section>

    <!-- Top users Section -->
    <section class="users-section">
      <h2 class="section-title">Top users</h2>
      <canvas id="usersChart"></canvas>
    </section>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../axios'
import '../assets/home.css'

const router = useRouter()

const goTo = (path) => {
  router.push(path)
}

const goToCreateQuiz = () => {
  const token = localStorage.getItem('token')
  if (!token) {
    router.push('/login')
    return
  }
  router.push('/create-quiz')
}

const quizzes = ref([])
const loading = ref(false)
const error = ref(null)

onMounted(async () => {
  loading.value = true
  try {
    const response = await api.get('/all/quizzes')
    quizzes.value = response.data.map((q) => ({
      id: q.id,
      title: q.title,
      description: q.description,
      questionsCount: q.questions_count ?? (q.questions ? q.questions.length : undefined),
      plays: q.results_count ?? (q.results ? q.results.length : undefined),
      category: q.category ?? null,
      raw: q,
    }))
  } catch (e) {
    console.error('Error fetching quizzes', e)
    error.value = e
  } finally {
    loading.value = false
  }
})
</script>