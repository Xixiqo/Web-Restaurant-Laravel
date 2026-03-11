<template>
  <div class="min-h-screen bg-[var(--color-bg-darkest)] pt-28 pb-20">
    <!-- Hero Section -->
    <section class="px-4 sm:px-6 lg:px-12 mb-20">
      <div class="max-w-7xl mx-auto">
        <div class="relative rounded-3xl overflow-hidden shadow-2xl">
          <img
            src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=1200&auto=format&fit=crop"
            alt="Restaurant Interior"
            class="w-full h-[400px] object-cover"
          />
          <div
            class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-transparent flex items-center"
          >
            <div class="px-8 md:px-16 max-w-xl">
              <p
                class="text-xs uppercase tracking-[0.2em] text-[var(--color-accent-orange)] mb-3 font-medium"
              >
                Our Story
              </p>
              <h1 class="text-4xl md:text-5xl font-serif text-white mb-4 leading-tight">
                About Sunuy House
              </h1>
              <p class="text-gray-300 leading-relaxed">
                Where tradition meets innovation, and every meal becomes a cherished memory.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Our Story -->
    <section class="px-4 sm:px-6 lg:px-12 mb-20">
      <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div>
          <p
            class="text-xs uppercase tracking-[0.2em] text-[var(--color-accent-orange)] mb-3 font-medium"
          >
            Est. 2010
          </p>
          <h2 class="text-3xl md:text-4xl font-serif text-[var(--color-text-light)] mb-6">
            A Passion for Perfection
          </h2>
          <div class="space-y-4 text-[var(--color-text-muted)] leading-relaxed">
            <p>
              Sunuy House was born from a simple dream: to bring authentic, handcrafted cuisine to
              the heart of the city. What started as a small family kitchen has grown into a beloved
              dining destination.
            </p>
            <p>
              Our chefs combine time-honored recipes with modern techniques, using only the freshest
              locally-sourced ingredients. Every dish is a testament to our commitment to quality
              and flavor.
            </p>
            <p>
              From our wood-fired ovens to our carefully curated wine selection, every detail at
              Sunuy House is designed to create an unforgettable dining experience.
            </p>
          </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div class="aspect-[3/4] rounded-2xl overflow-hidden shadow-xl">
            <img
              src="https://images.unsplash.com/photo-1590947132387-155cc02f3212?q=80&w=600&auto=format&fit=crop"
              alt="Pizza"
              class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
            />
          </div>
          <div class="aspect-[3/4] rounded-2xl overflow-hidden shadow-xl mt-8">
            <img
              src="https://images.unsplash.com/photo-1604382354936-07c5d9983bd3?q=80&w=600&auto=format&fit=crop"
              alt="Wood fired pizza"
              class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
            />
          </div>
        </div>
      </div>
    </section>

    <!-- Our Mission -->
    <section class="px-4 sm:px-6 lg:px-12 mb-20">
      <div class="max-w-7xl mx-auto">
        <div
          class="bg-[var(--color-primary-dark)] rounded-2xl p-8 md:p-16 border border-white/5 text-center"
        >
          <p
            class="text-xs uppercase tracking-[0.2em] text-[var(--color-accent-orange)] mb-3 font-medium"
          >
            Our Mission
          </p>
          <h2 class="text-3xl md:text-4xl font-serif text-[var(--color-text-light)] mb-6">
            Crafting Moments, Not Just Meals
          </h2>
          <p class="text-[var(--color-text-muted)] max-w-2xl mx-auto leading-relaxed text-lg">
            We believe dining is more than just eating — it's about connection, celebration, and
            creating memories. Our mission is to provide an exceptional culinary experience that
            brings people together.
          </p>
        </div>
      </div>
    </section>

    <!-- Stats -->
    <section class="px-4 sm:px-6 lg:px-12 mb-20">
      <div class="max-w-7xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-6">
        <div
          v-for="stat in stats"
          :key="stat.label"
          class="bg-[var(--color-primary-dark)] rounded-2xl p-6 text-center border border-white/5"
        >
          <p class="text-3xl md:text-4xl font-bold text-[var(--color-accent-orange)] mb-2">
            {{ stat.value }}
          </p>
          <p class="text-sm text-[var(--color-text-muted)]">{{ stat.label }}</p>
        </div>
      </div>
    </section>

    <!-- Team Section -->
    <section class="px-4 sm:px-6 lg:px-12">
      <div class="max-w-7xl mx-auto text-center">
        <p
          class="text-xs uppercase tracking-[0.2em] text-[var(--color-accent-orange)] mb-3 font-medium"
        >
          Our Team
        </p>
        <h2 class="text-3xl md:text-4xl font-serif text-[var(--color-text-light)] mb-12">
          Meet the Artisans
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
          <div v-for="member in team" :key="member.name" class="group">
            <div
              class="aspect-square rounded-2xl overflow-hidden shadow-xl mb-4 bg-[var(--color-primary-dark)]"
            >
              <img
                :src="member.image"
                :alt="member.name"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              />
            </div>
            <h3 class="text-lg font-serif text-[var(--color-text-light)] mb-1">
              {{ member.name }}
            </h3>
            <p class="text-sm text-[var(--color-accent-orange)]">{{ member.role }}</p>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
const stats = [
  { value: '15+', label: 'Years of Experience' },
  { value: '50+', label: 'Menu Varieties' },
  { value: '10K+', label: 'Happy Customers' },
  { value: '4.8', label: 'Average Rating' },
]

const team = [
  {
    name: 'Marco Rossi',
    role: 'Head Chef',
    image:
      'https://images.unsplash.com/photo-1577219491135-ce391730fb2c?q=80&w=500&auto=format&fit=crop',
  },
  {
    name: 'Sofia Laurent',
    role: 'Pastry Chef',
    image:
      'https://images.unsplash.com/photo-1583394838336-acd977736f90?q=80&w=500&auto=format&fit=crop',
  },
  {
    name: 'James Chen',
    role: 'Sous Chef',
    image:
      'https://images.unsplash.com/photo-1581299894007-aaa50297cf16?q=80&w=500&auto=format&fit=crop',
  },
]
</script>
