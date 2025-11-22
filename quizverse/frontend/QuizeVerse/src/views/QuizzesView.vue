<template>
  <div class="quizzes-container">
    <h1 class="quizzes-title">Available Quizzes</h1>
    <div class="quizzes-list">
      <div v-if="loading" class="quiz-card">Loading quizzes…</div>
      <div v-else-if="error" class="quiz-card">Failed to load quizzes.</div>
      <template v-else>
        <div
          v-for="quiz in quizzes"
          :key="quiz.id"
          class="quiz-card"
          @click="goTo(`/quiz-page/${quiz.id}`)"
        >
          <h3>{{ quiz.title }}</h3>
          <p>{{ quiz.description }}</p>
          <p class="quiz-meta" v-if="quiz.category">Category: {{ quiz.category.name }}</p>
        </div>
      </template>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../axios'
import '../assets/quizzes-view.css'

const router = useRouter()
const quizzes = ref([])
const loading = ref(false)
const error = ref(null)

const goTo = (path) => {
  router.push(path)
}

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
