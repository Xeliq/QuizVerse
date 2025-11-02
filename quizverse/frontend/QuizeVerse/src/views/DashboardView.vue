<template>
  <div>
    <h1>Witaj, {{ user.name }}</h1>
    <p>Email: {{ user.email }}</p>
    <button @click="handleLogout">Wyloguj</button>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api, { logout } from '../axios'

const user = ref({})

onMounted(async () => {
  const token = localStorage.getItem('token')
  const response = await api.get('/user', {
    headers: {
      Authorization: `Bearer ${token}`
    }
  })
  user.value = response.data
})

const handleLogout = async () => {
  try {
    await logout()
  } catch (error) {
    console.error('Logout error:', error)
  } finally {
    localStorage.removeItem('token')
    window.location.href = '/login'
  }
}
</script>

