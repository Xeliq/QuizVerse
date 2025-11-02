<template>
  <div>
    <h1>Witaj, {{ user.name }}</h1>
    <p>Email: {{ user.email }}</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../axios'

const user = ref({})

onMounted(async () => {
  try {
    const response = await api.get('/user')
    user.value = response.data
  } catch (e) {
    console.error('Błąd przy pobieraniu użytkownika:', e)
  }
})
</script>

