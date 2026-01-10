<template>
  <div class="login-page">
    <div class="login-box">
      <h1 class="login-title">
        Login to <span class="quizverse-text">QuizVerse</span>
      </h1>

      <form class="login-form" @submit.prevent="login">
        <label for="email">Email</label>
        <input type="email" id="email" v-model="email" placeholder="Enter your email" />

        <label for="password">Password</label>
        <input type="password" id="password" v-model="password" placeholder="Enter your password" />

        <button type="submit" class="login-button">Login</button>
        <p v-if="error" class="error-message">{{ error }}</p>
      </form>
    </div>
  </div>
</template>


<script setup>
import '../assets/login.css'
import { ref, onMounted } from 'vue'
import api from '../axios'

const email = ref('')
const password = ref('')
const rememberMe = ref(false)
const error = ref(null)

const login = async () => {
  try {
    const response = await api.post('/login', {
    email: email.value,
    password: password.value
  })

    const token = response.data.access_token
    localStorage.setItem('token', token)

    // przekierowanie po zalogowaniu
    window.location.href = '/'
  } catch (e) {
    console.error('Login error:', e.response)
    if (e.response?.status === 401) {
      error.value = e.response.data.message || 'Invalid login data'
    } else {
      error.value = 'An unexpected error occurred'
    }
  }
}
</script>


