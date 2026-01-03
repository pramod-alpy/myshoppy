<template>
    <Header />
    <div class="p-6">
      <h1 class="text-3xl font-bold mb-4">Add New Product</h1>
  
      <form @submit.prevent="submit" class="space-y-4 max-w-md">
        <div>
          <label class="block mb-1 font-semibold">Name</label>
          <input v-model="form.name" type="text" class="w-full border rounded p-2" required />
        </div>
  
        <div>
          <label class="block mb-1 font-semibold">Description</label>
          <textarea v-model="form.description" class="w-full border rounded p-2"></textarea>
        </div>
  
        <div>
          <label class="block mb-1 font-semibold">Price</label>
          <input v-model="form.price" type="number" min="0" class="w-full border rounded p-2" required />
        </div>
        <div>
          <label class="block mb-1 font-semibold">Image</label>
          <input type="file" accept="image/*" @change="handleImage" />
      <!-- Image Preview -->
      <img v-if="preview" :src="preview" class="h-32 mt-2 rounded border" />

        </div>
        <div>
        <label class="block mb-1 font-semibold">Stock Quantity</label>
        <input
            v-model="form.stock_quantity"
            type="number"
            min="1"
            class="w-full border rounded p-2"
            required
        />
        </div>
        <div>
        <label class="block mb-1 font-semibold">Alert Qty</label>
        <input
            v-model="form.alert_qty"
            type="number"
            min="1"
            class="w-full border rounded p-2"
            required
        />
        </div>
        <div>
          <label class="block mb-1 font-semibold">Status</label>
        <select
          v-model="form.status"
          class="w-full border rounded p-2"
          required
        >
          <option :value="1">Active</option>
          <option :value="0">Inactive</option>
        </select>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save Product</button>
        <Link :href="route('admin.products')" class="ml-4">
        Cancel
      </Link>
      </form>
    </div>
  </template>
  
  <script setup>
  import { useForm, Link } from '@inertiajs/vue3'
  import { ref } from 'vue'
  import Header from '@/Components/Header.vue'
  const form = useForm({
  name: '',
  description: '',
  price: '',
  status: 1, 
  stock_quantity: 1,
  alert_qty: 1,
  image: null,
})
  const preview = ref(null)
  
  const handleImage = (e) => {
  form.image = e.target.files[0]
  preview.value = URL.createObjectURL(form.image)
}

const submit = () => {
  form.post(route('admin.products.store'), {
    forceFormData: true,
  })
}
  </script>
  