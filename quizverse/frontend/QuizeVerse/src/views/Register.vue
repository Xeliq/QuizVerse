<template>
  <div class="login-page">
    <div class="login-box">
      <h1 class="login-title">
        Register for <span class="quizverse-text">QuizVerse</span>
      </h1>

      <form class="login-form" @submit.prevent="register">
        <label for="username">Username</label>
        <input type="text" id="username" v-model="username" placeholder="Enter your username" />

        <label for="email">Email</label>
        <input type="email" id="email" v-model="email" placeholder="Enter your email" />

        <label for="password">Password</label>
        <input type="password" id="password" v-model="password" placeholder="Enter your password" />

        <label for="password_confirmation">Confirm Password</label>
        <input type="password" id="password_confirmation" v-model="passwordConfirmation" placeholder="Confirm your password" />


        <label class="remember-me">
            <input type="checkbox" v-model="agreeTerms" />
            <span>I agree to the <router-link class="inline-link"to="/privacy">Terms</router-link></span>
        </label>

        <button type="submit" class="login-button">Register</button>

        <p v-if="error" class="error-message">{{ error }}</p>

        <div>
          <a href="/login" class="forgot-password">Already have an account?</a>
        </div>
        
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../axios'
import '../assets/login.css'

const username = ref('')
const email = ref('')
const password = ref('')
const passwordConfirmation = ref('')
const agreeTerms = ref(false)
const error = ref(null)

const register = async () => {
  error.value = null

  if (!agreeTerms.value) {
    error.value = 'You must agree to the terms.'
    return
  }

  try {
    const response = await api.post('/register', {
      name: username.value,
      email: email.value,
      password: password.value,
      password_confirmation: passwordConfirmation.value
    })

    const token = response.data.access_token
    localStorage.setItem('token', token)

    window.location.href = '/'
  } catch (e) {
    error.value = e.response?.data?.message || 'Registration failed.'
  }
}
</script>
