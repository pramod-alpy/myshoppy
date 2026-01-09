<script setup>
import Header from '@/Components/Header.vue'
defineProps({
  order: Object
})
</script>

<template>
  <Header />
<div class="max-w-4xl mx-auto p-8">
    <div class="text-center mb-8">
      <div class="text-green-600 text-5xl mb-2">✓</div>
      <h1 class="text-3xl font-bold">Order Placed Successfully</h1>
      <p class="text-gray-600">
        Thank you for your purchase!
      </p>
    </div>

    <!-- Order Info -->
    <div class="border rounded p-4 mb-6">     
      <p>
        <strong>Status:</strong>
        <span
          :class="order.payment_status === 'paid'
            ? 'text-green-600'
            : 'text-yellow-600'"
        >
          {{ order.payment_status }}
        </span>
      </p>
      <p><strong>Payment:</strong> {{ order.payment_method }}</p>
    </div>

    <!-- Purchased Items -->
    <div class="border rounded p-4 mb-6">
      <h2 class="text-xl font-semibold mb-4">Items Purchased</h2>

      <div
        v-for="item in order.items"
        :key="item.id"
        class="flex justify-between border-b py-2"
      >
        <div>
          <p class="font-medium">{{ item.name }}</p>
          <p class="text-sm text-gray-500">
            ₹{{ item.price }} × {{ item.quantity }}
          </p>
        </div>

        <p class="font-semibold">₹{{ item.total }}</p>
      </div>

      <div class="text-right font-bold text-lg pt-4">
        Total: ₹{{ order.total_amount }}
      </div>
    </div>

    <div class="text-center">
      <a href="/" class="bg-green-600 text-white px-6 py-3 rounded">
        Continue Shopping
      </a>
    </div>
  </div>
</template>
