
<script setup>
import { ref } from 'vue'
import axios from 'axios'
import Header from '@/Components/Header.vue'
import { cartCount } from '@/Stores/cart.js'

const props = defineProps({
  products: Array,
  totalPrice: Number,
  cartCount: Number,
})

const items = ref(props.products || [])
const total = ref(props.totalPrice || 0)


const updateQty = async (product, qty) => {
  qty = Number(qty);
  console.log('pdt='+JSON.stringify(product))
console.log('qty='+qty);
console.log('stock='+product.stock_quantity)
  if (qty <= 0) {
    await removeItem(product.id)
    return
  }
  if (qty > product.stock_quantity) {
    alert(`Only ${product.stock_quantity} items in stock`)
    return
  }

  const res = await axios.post(`/cart/update/${product.id}`, {
    quantity: qty
  })

  items.value = res.data.products
  total.value = res.data.totalPrice
  cartCount.value = res.data.cartCount
}


const removeItem = async (id) => {
  const res = await axios.post(`/cart/remove/${id}`)

  items.value = res.data.products
  total.value = res.data.totalPrice
  cartCount.value = res.data.cartCount
}


const proceedToPayment = () => {
  if (items.value.length === 0) {
    alert('Cart is empty')
    return
  }
  
  window.location.href = '/checkout'
}
</script>

<template>
     <Header />

<div class="p-6 max-w-5xl mx-auto">
  <h1 class="text-3xl font-bold mb-6">Your Cart</h1>

  <div v-if="items.length">
    <table class="w-full border">
      <thead class="bg-gray-100">
        <tr>
          <th class="p-2 border">Product</th>
          <th class="p-2 border">Price</th>
          <th class="p-2 border">Quantity</th>
          <th class="p-2 border">Total</th>
          <th class="p-2 border">Action</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="item in items" :key="item.id">
          <td class="p-2 border">{{ item.name }}</td>
          <td class="p-2 border">₹{{ item.price }}</td>
         
          <td class="p-2 border">
            <div class="flex items-center gap-2">
              <button
                @click="updateQty(item, item.quantity - 1)"
                class="px-2 bg-gray-300 rounded"
              >−</button>

              <input
                type="number"
                min="0"
                class="w-16 text-center border rounded"
                :value="item.quantity"
                @change="updateQty(item, $event.target.value)"
              />

              <button
                @click="updateQty(item, item.quantity + 1)"
                class="px-2 bg-gray-300 rounded"
              >+</button>
            </div>
          </td>

          <td class="p-2 border font-bold">
            ₹{{ item.total }}
          </td>

          <td class="p-2 border">
            <button
              @click="removeItem(item.id)"
              class="text-red-600 hover:underline"
            >
              Remove
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <div class="text-right mt-4">
      <p class="text-xl font-bold">Grand Total: ₹{{ total }}</p>

      <button
  @click="proceedToPayment"
  class="mt-3 bg-green-600 text-white px-6 py-2 rounded"
>
  Proceed to Payment
</button>
    </div>
  </div>

  <div v-else class="text-gray-500">
    Your cart is empty
  </div>
</div>
</template>
