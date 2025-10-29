<template>
  <div class="login-page">
    <div class="login-box">
      <h1 class="login-title">
        Login to <span class="quizverse-text">QuizVerse</span>
      </h1>

      <form class="login-form">
        <label for="email">Email</label>
        <input type="email" id="email" v-model="email" placeholder="Enter your email" />

        <label for="password">Password</label>
        <input type="password" id="password" v-model="password" placeholder="Enter your password" />

        <button type="submit" class="login-button">Login</button>

        <div class="login-options">
<label class="remember-me">
  <input type="checkbox" v-model="rememberMe" />
  <span>Remember me?</span>
</label>


          <a href="#" class="forgot-password">Forgot password?</a>
        </div>
      </form>
    </div>
  </div>
</template>


<script setup>
import '../assets/login.css'   /* <-- import the external CSS */
import { ref, onMounted } from 'vue'

const email = ref('')
const password = ref('')
const rememberMe = ref(false)

// Force the app root to full height and reset layout when this page mounts
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

