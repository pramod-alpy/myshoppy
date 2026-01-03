<script setup>
import { usePage, router, Link } from '@inertiajs/vue3'
import { ref, watchEffect } from 'vue'
import { cartCount } from '@/Stores/cart.js'

// reactive auth and cart
const { props } = usePage()

const user = ref(props.auth.user)

// watch props.auth.user so header updates on logout/login
watchEffect(() => {
  user.value = props.auth.user ?? null
  cartCount.value = props.cartCount ?? 0
})

// Logout function
const logout = () => {
  cartCount.value = 0 // reset cart badge
  user.value = null;
  router.post('/logout', {
    preserveState: false, // forces shared props to refresh
    onSuccess: () => {
      router.visit('/') // redirect to home after logout
    }
  })
}
</script>
<template>
  <header class="flex justify-between items-center p-4 bg-gray-100 shadow">
    <div>
      <h1 class="text-xl font-bold">
        <Link href="/" class="text-blue-600 hover:none">MyShoppy</Link>
      </h1>
    </div>

    <nav class="flex items-center gap-4">
      <!-- User is logged in -->
      <template v-if="user">
        <span class="font-semibold">Hello, {{ user.name }}</span>

        <Link v-if="user.is_admin" href="/admin/products" class="text-blue-600 hover:underline">
          Manage Products
        </Link>

        <template v-else>
          <Link href="/cart" class="text-blue-600 hover:underline">
            Cart
            <span
              v-if="cartCount"
              class="ml-1 bg-red-500 text-white px-2 rounded-full text-sm"
            >
              {{ cartCount }}
            </span>
          </Link>
        </template>

        <button @click="logout" class="text-red-600 hover:underline">Logout</button>
      </template>

      <!-- Guest -->
      <template v-else>
        <Link href="/login" class="text-blue-600 hover:underline">Login</Link>
        <Link href="/register" class="text-blue-600 hover:underline">Register</Link>
      </template>
    </nav>
  </header>
</template>