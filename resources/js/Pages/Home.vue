<template>
  <div>
    <Header />
    <main class="p-6">
      <h1 class="text-3xl font-bold mb-6">Products</h1>      
      <div  v-if="flashMessage"  class="flex justify-center mb-6">
          <div class="bg-green-600 text-white px-6 py-3 rounded shadow-lg transition">
            {{ flashMessage }}
          </div>
        </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <div
          v-for="product in products"
          :key="product.id"
          class="border rounded-lg shadow hover:shadow-lg flex flex-col"
        >
       
          <div class="w-full h-48 bg-gray-100 flex items-center justify-center overflow-hidden rounded-t-lg">
            <img
              v-if="product.image"
              :src="`/storage/${product.image}`"
              :alt="product.name"
              class="object-contain h-full w-full"
            />
            <span v-else class="text-gray-400">No Image</span>
          </div>

         
          <div class="flex-1 p-4 flex flex-col">
            <h2 class="text-lg font-semibold mb-1 truncate">{{ product.name }}</h2>
            <p class="text-gray-600 text-sm flex-1 overflow-hidden">{{ product.description }}</p>
            <p class="font-bold text-lg mt-2">₹{{ product.price }}</p>

           
            <button
            v-if="!isAdmin"
            @click="addToCart(product)"
            class="mt-4 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded w-full"
          >
            Add to Cart
          </button>

          <button
            v-else
            @click="editProduct(product)"
            class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded w-full"
          >
            Edit Product
          </button>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import Header from '@/Components/Header.vue'
import { cartCount } from '@/Stores/cart.js'
const flashMessage = ref(null)
const { props } = usePage()
const products = props.products || []

const isAdmin = computed(() => {
  console.log("ad="+props.auth?.user?.is_admin)
  return Boolean(props.auth?.user?.is_admin) 
})

const editProduct = (product) => {
  router.visit(`admin/products/${product.id}/edit`)
}

const addToCart = async (product) => {
  if (!props.auth.user) {
    router.visit('/login')
    return
  }

  try {
    const res = await axios.post('/cart/add', { product_id: product.id })
    cartCount.value = res.data.cartCount
    
    flashMessage.value = 'Item added to cart'
     
      setTimeout(() => {
        flashMessage.value = null
      }, 1000)

    } catch (error) {
    console.error(error)
    alert('Failed to add to cart')
    }
}
</script>
