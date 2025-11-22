<template>
  <div class="contact-container">
    <section class="contact-hero">
      <h1 class="contact-title">Contact Us</h1>
      <p class="contact-subtitle">
        Have questions, feedback, or ideas? We'd love to hear from you!
      </p>
    </section>

    <!-- Formularz kontaktowy -->
    <section class="contact-form-section">
      <h2 class="section-title">Send us a message</h2>
      <form class="contact-form" @submit.prevent="submitForm">
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" id="name" v-model="name" required />
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" v-model="email" required />
        </div>

        <div class="form-group">
          <label for="message">Message</label>
          <textarea id="message" v-model="message" rows="5" required></textarea>
        </div>

        <button type="submit" class="btn-submit">Send</button>
      </form>
    </section>

    <!-- Dane kontaktowe -->
    <section class="contact-info">
      <h2 class="section-title">Other ways to reach us</h2>
      <p>Email: <strong>support@quizverse.com</strong></p>
      <p>Phone: <strong>+48 123456789</strong></p>
    </section>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import '../assets/contact.css'

const name = ref('')
const email = ref('')
const message = ref('')

const submitForm = () => {
//do zrobienia później
  alert(`Message sent!\nName: ${name.value}\nEmail: ${email.value}\nMessage: ${message.value}`)
  name.value = ''
  email.value = ''
  message.value = ''
}
</script>
