<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import Header from '@/Components/Header.vue'

const { product } = defineProps({
  product: Object,
})

const form = useForm({
  name: product.name,
  price: product.price,
  description: product.description,
  stock_quantity: product.stock_quantity,
  alert_qty: product.alert_qty,
  image: null,
  status: product.status ? 1 : 0,
})

const preview = ref(product.image ? `/storage/${product.image}` : null)

const handleImage = (e) => {
  form.image = e.target.files[0]
  preview.value = URL.createObjectURL(form.image)
}

const submit = () => {
  form.post(route('admin.products.update', product.id), {
    forceFormData: true,
    _method: 'put',
  })
}
</script>


<template>
    <Header />
  <div class="p-6 max-w-md">
    <h1 class="text-2xl font-bold mb-4">Edit Product</h1>

    <form @submit.prevent="submit">
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
        <button class="mt-6 bg-green-600 text-white px-4 py-2 rounded">
      Update
    </button>

      <Link :href="route('admin.products')" class="ml-4">
        Cancel
      </Link>
    </form>
  </div>
</template>
