<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { Head } from '@inertiajs/vue3'

const props = defineProps({
    products: Array
})

const form = useForm({
    name: '',
    description: '',
    price: '',
    image: ''
})

const submit = () => {
    form.post(route('admin.products.store'), {
        onSuccess: () => form.reset()
    })
}
</script>

<template>
  <Head title="Admin Products" />

  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Add Product</h1>

    <form @submit.prevent="submit" class="mb-6 space-y-4">
      <input v-model="form.name" type="text" placeholder="Product Name" class="border p-2 rounded w-full" required />
      <textarea v-model="form.description" placeholder="Description" class="border p-2 rounded w-full"></textarea>
      <input v-model="form.price" type="number" placeholder="Price" class="border p-2 rounded w-full" required />
      <input v-model="form.image" type="text" placeholder="Image URL" class="border p-2 rounded w-full" />
      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Add Product</button>
    </form>

    <h2 class="text-xl font-bold mb-2">Existing Products</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div v-for="product in props.products" :key="product.id" class="border p-4 rounded shadow">
        <h3 class="font-semibold">{{ product.name }}</h3>
        <p class="text-gray-600">{{ product.description }}</p>
        <p class="font-bold">₹{{ product.price }}</p>
        <img v-if="product.image" :src="product.image" alt="" class="mt-2 w-full h-32 object-cover rounded" />
      </div>
    </div>
  </div>
</template>
