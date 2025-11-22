<template>
  <footer class="footer">
    <div class="footer-content">
      <div class="footer-left" @click="goTo('/')">
        <img src="@/assets/logo.png" class="footer-logo" alt="QuizVerse Logo" />
      </div>

      <div class="footer-right">
        <p class="footer-copy">© 2025 QuizVerse. All rights reserved.</p>
        <router-link to="/privacy" class="footer-link">Privacy Policy</router-link>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { useRouter } from 'vue-router'
import '../assets/footer.css'
const router = useRouter()

const goTo = (path) => {
  router.push(path)
}
</script>