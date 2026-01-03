<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import Header from '@/Components/Header.vue'
import { cartCount } from '@/Stores/cart.js'

const paymentMethod = ref('cod')

const submitOrder = () => {
  router.post('/checkout/place-order', {
    payment_method: paymentMethod.value,
  })
}
</script>

<template>
  <Header />

  <div class="max-w-4xl mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Checkout</h1>
  
    <div class="border rounded p-4 mb-6">
      <h2 class="text-xl font-semibold mb-4">Order Summary</h2>

      <div v-for="item in $page.props.products" :key="item.id" class="flex justify-between mb-2">
        <span>{{ item.name }} × {{ item.quantity }}</span>
        <span>₹{{ item.total }}</span>
      </div>

      <div class="border-t pt-3 font-bold text-lg text-right">
        Total: ₹{{ $page.props.totalPrice }}
      </div>
    </div>

   
    <div class="border rounded p-4 mb-6">
      <h2 class="text-xl font-semibold mb-4">Payment Method</h2>

      <label class="flex items-center gap-2 mb-2">
        <input type="radio" value="cod" v-model="paymentMethod" />
        Cash on Delivery
      </label>

      <label class="flex items-center gap-2 mb-2">
        <input type="radio" value="card" v-model="paymentMethod" />
        Credit / Debit Card
      </label>

      <label class="flex items-center gap-2">
        <input type="radio" value="upi" v-model="paymentMethod" />
        UPI
      </label>
    </div>

    <button
      @click="submitOrder"
      class="w-full bg-green-600 text-white py-3 rounded text-lg"
    >
      Place Order
    </button>
  </div>
</template>
