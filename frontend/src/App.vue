<template>
  <div class="min-h-screen flex flex-col font-sans">
    <!-- Show Navbar & Footer only on non-admin routes -->
    <Navbar v-if="!isAdminRoute" />
    <main class="flex-grow">
      <RouterView />
    </main>
    <Footer v-if="!isAdminRoute" />
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { RouterView, useRoute } from 'vue-router'
import Navbar from './components/Navbar.vue'
import Footer from './components/Footer.vue'

const route = useRoute()
const isAdminRoute = computed(() => {
  return route.path.startsWith('/admin') && route.path !== '/admin/login'
})
</script>
