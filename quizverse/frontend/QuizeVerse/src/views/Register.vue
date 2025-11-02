<template>
  <div class="login-page">
    <div class="login-box">
      <h1 class="login-title">
        Register for <span class="quizverse-text">QuizVerse</span>
      </h1>

      <form class="login-form">
        <label for="username">Username</label>
        <input type="text" id="username" v-model="username" placeholder="Enter your username" />

        <label for="email">Email</label>
        <input type="email" id="email" v-model="email" placeholder="Enter your email" />

        <label for="password">Password</label>
        <input type="password" id="password" v-model="password" placeholder="Enter your password" />

        <button type="submit" class="login-button">Register</button>

        <div class="login-options">
          <label class="remember-me">
            <input type="checkbox" v-model="agreeTerms" />
            <span>I agree to the Terms</span>
          </label>

          <a href="/login" class="forgot-password">Already have an account?</a>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import '../assets/login.css'  // reuse login styles

const username = ref('')
const email = ref('')
const password = ref('')
const agreeTerms = ref(false)

onMounted(() => {
  const app = document.getElementById('app')
  if (app) {
    app.style.display = 'flex'
    app.style.justifyContent = 'center'
    app.style.alignItems = 'center'
    app.style.height = '100vh'
    app.style.width = '100vw'
    app.style.maxWidth = '100%'
    app.style.padding = '0'
    app.style.gridTemplateColumns = 'none'
  }
})
</script>
