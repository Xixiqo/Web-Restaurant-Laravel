<template>
  <div class="flex flex-col gap-0 pb-20">
    <Hero />
    <About />
    <MenuSection />
    <Reservation />
  </div>
</template>

<script setup>
import Hero from '@/components/landing/Hero.vue'
import About from '@/components/landing/About.vue'
import MenuSection from '@/components/landing/MenuSection.vue'
import Reservation from '@/components/landing/Reservation.vue'
</script>
