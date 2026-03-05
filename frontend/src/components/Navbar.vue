<template>
  <nav 
    :class="`fixed top-0 left-0 right-0 z-50 transition-all duration-300 ${isScrolled ? 'bg-[var(--color-primary-dark)] shadow-lg py-3' : 'bg-transparent py-6'}`"
  >
    <div class="max-w-7xl mx-auto px-6 lg:px-12 flex items-center justify-between">
      
      <!-- Logo Container -->
      <div class="flex items-center gap-3">
        <!-- Logo Icon Bubble -->
        <div class="w-10 h-10 rounded-full bg-[var(--color-text-light)]/20 backdrop-blur-sm flex items-center justify-center border border-white/10">
          <span class="text-[var(--color-text-light)] text-sm font-semibold font-serif">SH</span>
        </div>
        <a href="#" class="hidden sm:block text-2xl font-serif tracking-wide text-[var(--color-text-light)]">
          Sunuy House
        </a>
      </div>

      <!-- Desktop Links -->
      <div class="hidden md:flex items-center gap-10">
        <a v-for="link in links" :key="link.name" :href="link.href" 
           class="text-[15px] font-medium text-[var(--color-text-light)] hover:text-[var(--color-accent-orange)] transition-colors duration-300">
          {{ link.name }}
        </a>
      </div>

      <!-- CTA Button & Mobile Menu -->
      <div class="flex items-center gap-4">
        <button class="bg-[var(--color-primary-light)] border border-white/10 hover:border-white/30 text-[var(--color-text-light)] px-6 py-2 rounded-full font-medium transition-all duration-300 hover:scale-105 cursor-pointer flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
          </svg>
          <span class="hidden sm:inline">Log in</span>
        </button>

        <!-- Mobile Menu Button -->
        <button class="md:hidden text-[var(--color-text-light)] p-2">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
          </svg>
        </button>
      </div>

    </div>
  </nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const links = [
  { name: 'Home', href: '#' },
  { name: 'About', href: '#about' },
  { name: 'Menu', href: '#menu' },
  { name: 'Reservation', href: '#reservation' },
  { name: 'Contact', href: '#footer' },
]

const isScrolled = ref(false)

const handleScroll = () => {
  isScrolled.value = window.scrollY > 50
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll)
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
})
</script>
