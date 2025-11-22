<template>
  <nav class="navbar">
    <div class="nav-left" @click="goTo('/')">
      <img src="@/assets/logo.png" class="logo" alt="QuizVerse Logo" />
      <!-- <span class="brand-name">QuizVerse</span> -->
    </div>

    <div class="nav-links">
      <router-link to="/ranking" class="nav-item">Ranking</router-link>
      <router-link to="/about" class="nav-item">About</router-link>
      <router-link to="/contact" class="nav-item">Contact</router-link>
    </div>

    <div class="nav-auth">
      <template v-if="!isLoggedIn">
        <router-link to="/login" class="auth-btn login-btn">Login</router-link>
        <router-link to="/register" class="auth-btn register-btn">Register</router-link>
      </template>

      <template v-else>
        <router-link to="/profile" class="auth-btn profile-btn">My Profile</router-link>
        <button class="auth-btn logout-btn" @click="logout">Logout</button>
      </template>
    </div>
  </nav>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import '../assets/navigation.css'

const router = useRouter()
const isLoggedIn = ref(false)

const checkAuth = () => {
  isLoggedIn.value = !!localStorage.getItem('token')
}

const logout = () => {
  localStorage.removeItem('token')
  isLoggedIn.value = false
  router.push('/login')
}

const goTo = (path) => {
  router.push(path)
}

onMounted(() => {
  checkAuth()
})
</script>
