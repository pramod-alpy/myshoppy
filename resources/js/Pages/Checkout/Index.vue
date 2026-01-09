<script setup>
import { ref, watch, onMounted} from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { loadStripe } from '@stripe/stripe-js'
import axios from 'axios'
import Header from '@/Components/Header.vue'
import { cartCount } from '@/Stores/cart.js'

const page = usePage()
const paymentMethod = ref('cod')
const stripe = ref(null)
const elements = ref(null)
const cardElement = ref(null)
const clientSecret = ref(null)

onMounted(async () => {
  stripe.value = await loadStripe(import.meta.env.VITE_STRIPE_KEY)
  elements.value = stripe.value.elements()
  cardElement.value = elements.value.create('card')
  cardElement.value.mount('#card-element')

  // Create PaymentIntent immediately
  const res = await axios.post('/checkout/stripe-intent')
  clientSecret.value = res.data.clientSecret
})



const submitOrder = async () => {
  if (paymentMethod.value === 'cod') {
    router.post('/checkout/place-order', { payment_method: 'cod' })
    return
  }

  const { paymentIntent, error } =
    await stripe.value.confirmCardPayment(clientSecret.value, {
      payment_method: { card: cardElement.value },
    })

  if (error) {
    alert(error.message)
    return
  }

  router.post('/checkout/place-order', {
    payment_method: 'stripe',
    payment_intent_id: paymentIntent.id,
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

      <label class="flex items-center gap-2 opacity-50 cursor-not-allowed">
        <input type="radio" disabled />
        UPI (Coming soon)
      </label>
    </div>
    
<div v-show="paymentMethod === 'card'" class="border rounded p-4 mb-6">
  <label class="block mb-2 font-semibold">Card Details</label>
  <div id="card-element" class="p-3 border rounded"></div>
</div>

    <button
      @click="submitOrder"
      class="w-full bg-green-600 text-white py-3 rounded text-lg"
    >
      Place Order
    </button>
  </div>
</template>
