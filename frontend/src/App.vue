<template>
  <div class="min-h-screen flex flex-col font-sans">
    <Navbar />
    <main class="flex-grow">
      <RouterView />
    </main>
    <Footer />
  </div>
</template>

<script setup>
import { RouterView } from 'vue-router'
import Navbar from './components/Navbar.vue'
import Footer from './components/Footer.vue'
</script>
  
<style>
/* Using Tailwind for all styling */
</style>
