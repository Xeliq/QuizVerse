<template>
  <NavigationBar />
  <div class="app-main">
    <router-view />
  </div>
  <Footer />
</template>

<script setup>
import NavigationBar from '/src/views/NavigationBar.vue'
import Footer from '/src/views/Footer.vue'
</script>

<style>
html, body {
  height: 100%;
  margin: 0;
  display: flex;
  flex-direction: column;
}

.main-content {
  flex: 1;
  padding-top: 70px;
}
</style>
