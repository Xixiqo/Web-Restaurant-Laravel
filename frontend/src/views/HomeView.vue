<template>
  <div class="flex flex-col gap-0 pb-20">
    <Hero />
    <About />
    <MenuSection />

    <!-- CTA Section -->
    <section class="py-24 px-4 sm:px-6 lg:px-12">
      <div class="max-w-7xl mx-auto">
        <div
          class="bg-gradient-to-r from-[var(--color-primary-dark)] to-[#333] rounded-3xl p-8 md:p-16 text-center border border-white/5 shadow-2xl relative overflow-hidden"
        >
          <!-- Background decorative -->
          <div class="absolute inset-0 opacity-5">
            <div
              class="absolute -top-20 -right-20 w-60 h-60 rounded-full bg-[var(--color-accent-orange)]"
            ></div>
            <div
              class="absolute -bottom-20 -left-20 w-40 h-40 rounded-full bg-[var(--color-accent-gold)]"
            ></div>
          </div>
          <div class="relative z-10">
            <h2 class="text-3xl md:text-4xl font-serif text-[var(--color-text-light)] mb-4">
              Ready for an Unforgettable Experience?
            </h2>
            <p class="text-[var(--color-text-muted)] max-w-lg mx-auto mb-8">
              Book your table today and let us take you on a culinary journey you won't forget.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
              <router-link
                to="/reservation"
                class="bg-[var(--color-accent-orange)] hover:bg-[#e07a3b] text-white px-8 py-3.5 rounded-full font-medium transition-all duration-300 hover:scale-105 shadow-lg"
              >
                Reserve a Table
              </router-link>
              <router-link
                to="/menu"
                class="border border-white/20 hover:border-white/40 text-[var(--color-text-light)] px-8 py-3.5 rounded-full font-medium transition-all duration-300 hover:bg-white/5"
              >
                View Full Menu
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import Hero from '@/components/landing/Hero.vue'
import About from '@/components/landing/About.vue'
import MenuSection from '@/components/landing/MenuSection.vue'
</script>
