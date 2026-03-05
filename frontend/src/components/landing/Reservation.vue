<template>
  <section id="reservation" class="py-24 px-4 sm:px-6 lg:px-12 border-t border-white/5">
    <div class="max-w-7xl mx-auto">
      <div class="text-center mb-16">
        <h2 class="text-4xl md:text-5xl font-serif text-[var(--color-text-light)]">Make a Reservation</h2>
      </div>

      <!-- 2 Column Form -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12 bg-[var(--color-primary-light)] rounded-2xl p-6 sm:p-8 md:p-12 lg:p-16 shadow-2xl border border-white/5 items-start">

        <!-- Left Column -->
        <div class="space-y-6">
          <p class="text-[15px] text-[var(--color-text-muted)] font-light leading-relaxed">
            Reserve your table at Sunuy House and experience our delicious pizza in a warm, welcoming atmosphere. We look forward to serving you!
          </p>

          <div class="space-y-4">
            <div>
              <label class="block text-sm text-[var(--color-text-light)] mb-2">Full Name *</label>
              <input v-model="form.fullName" type="text" placeholder="Enter your full name" class="w-full bg-[var(--color-form-input)] text-white border-0 rounded-lg px-4 py-3 focus:ring-2 focus:ring-[var(--color-accent-orange)] focus:outline-none transition-shadow" />
            </div>

            <div>
              <label class="block text-sm text-[var(--color-text-light)] mb-2">Email Address *</label>
              <input v-model="form.email" type="email" placeholder="Enter your email address" class="w-full bg-[var(--color-form-input)] text-white border-0 rounded-lg px-4 py-3 focus:ring-2 focus:ring-[var(--color-accent-orange)] focus:outline-none transition-shadow" />
            </div>

            <div>
              <label class="block text-sm text-[var(--color-text-light)] mb-2">Phone Number *</label>
              <input v-model="form.phone" type="tel" placeholder="Enter your phone number" class="w-full bg-[var(--color-form-input)] text-white border-0 rounded-lg px-4 py-3 focus:ring-2 focus:ring-[var(--color-accent-orange)] focus:outline-none transition-shadow" />
            </div>

            <div>
              <label class="block text-sm text-[var(--color-text-light)] mb-2">Special Requests</label>
              <textarea v-model="form.requests" rows="4" placeholder="Any special requests or allergies?" class="w-full bg-[var(--color-form-input)] text-white border-0 rounded-lg px-4 py-3 focus:ring-2 focus:ring-[var(--color-accent-orange)] focus:outline-none transition-shadow resize-y"></textarea>
            </div>
          </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
          <div>
            <label class="block text-sm text-[var(--color-text-light)] mb-2">Select Date & Time *</label>
            <div class="grid grid-cols-2 gap-4">
              <input v-model="form.date" type="date" class="w-full bg-[var(--color-form-input)] text-gray-300 border-0 rounded-lg px-4 py-3 focus:ring-2 focus:ring-[var(--color-accent-orange)] focus:outline-none transition-shadow" />
              <input v-model="form.time" type="time" class="w-full bg-[var(--color-form-input)] text-gray-300 border-0 rounded-lg px-4 py-3 focus:ring-2 focus:ring-[var(--color-accent-orange)] focus:outline-none transition-shadow" />
            </div>
          </div>

          <div>
            <label class="block text-sm text-[var(--color-text-light)] mb-2">Party Size *</label>
            <div class="flex items-center gap-4 bg-[var(--color-form-input)] rounded-lg p-2 w-max">
              <button @click="decreaseParty" class="w-8 h-8 flex items-center justify-center rounded-md hover:bg-white/10 text-white font-bold transition-colors cursor-pointer">-</button>
              <span class="text-white w-4 text-center">{{ form.partySize }}</span>
              <button @click="increaseParty" class="w-8 h-8 flex items-center justify-center rounded-md hover:bg-white/10 text-white font-bold transition-colors cursor-pointer">+</button>
            </div>
          </div>

          <div class="bg-[var(--color-primary-dark)] rounded-lg p-6 border border-white/5">
            <h4 class="text-[var(--color-text-light)] font-medium mb-4">Reservation Summary</h4>
            <div class="space-y-2 text-sm text-[var(--color-text-muted)]">
              <div class="flex justify-between"><span>Date</span><span>{{ form.date }}</span></div>
              <div class="flex justify-between"><span>Time</span><span>{{ form.time }}</span></div>
              <div class="flex justify-between text-white font-medium pt-2 border-t border-white/10 mt-2"><span>Party Size</span><span>{{ form.partySize }} People</span></div>
            </div>
          </div>

          <div class="flex flex-col sm:flex-row gap-4">
            <button @click="submitForm" class="bg-[#c23b22] hover:bg-red-700 text-white px-8 py-3.5 rounded-full font-medium transition-all duration-300 flex-1 shadow-lg hover:shadow-xl hover:scale-[1.02] cursor-pointer">
              Make Reservation
            </button>
            <button @click="clearForm" class="bg-transparent border border-[var(--color-text-muted)] text-[var(--color-text-light)] hover:bg-white/5 px-8 py-3.5 rounded-full font-medium transition-all duration-300 cursor-pointer">
              Clear Form
            </button>
          </div>
        </div>

      </div>
    </div>
  </section>
</template>

<script setup>
import { reactive } from 'vue'

const form = reactive({
  fullName: '',
  email: '',
  phone: '',
  requests: '',
  date: '',
  time: '',
  partySize: 2
})

function increaseParty() {
  form.partySize++
}

function decreaseParty() {
  if (form.partySize > 2) {
    form.partySize--
  }
}

function submitForm() {
  console.log(form)
  clearForm()
}

function clearForm() {
  form.fullName = ''
  form.email = ''
  form.phone = ''
  form.requests = ''
  form.date = ''
  form.time = ''
  form.partySize = 2
}
</script>
