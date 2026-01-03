<script setup>
import { Link, router, usePage } from '@inertiajs/vue3'
import Header from '@/Components/Header.vue'
const { props } = usePage()
const products = props.products

const deleteProduct = (id) => {
  if (confirm('Are you sure to delete product?')) {
    router.delete(route('admin.products.destroy', id))
  }
}
</script>

<template>
    <Header />
  <div class="p-6">
    <div class="flex justify-between mb-4">
      <h1 class="text-3xl font-bold">Manage Products</h1>

      <Link
        :href="route('admin.products.create')"
        class="bg-blue-600 text-white px-4 py-2 rounded"
      >
        Add Product
      </Link>
    </div>

    <table class="w-full border border-collapse">
  <thead>
    <tr class="bg-gray-200">
      <th class="border p-2 text-center">ID</th>
      <th class="border p-2 text-left">Name</th>
      <th class="border p-2 text-right">Price</th>
      <th class="border p-2 text-center">Status</th>
      <th class="border p-2 text-center">Actions</th>
    </tr>
  </thead>

  <tbody>
    <tr
      v-for="(product, index) in products"
      :key="product.id"
      class="hover:bg-gray-50"
    >
      <td class="border p-2 text-center">{{ index + 1 }}</td>
      <td class="border p-2 text-left">{{ product.name }}</td>
      <td class="border p-2 text-right">₹{{ product.price }}</td>
      <td class="border p-2 text-center">
        <span
          v-if="product.status"
          class="text-green-600 text-lg font-bold"
        >
          ✅
        </span>
        <span
          v-else
          class="text-red-600 text-lg font-bold"
        >
          ❌
        </span>
      </td>
      <td class="border p-2 text-center">
        <div class="flex justify-center gap-3">
          <Link
            :href="route('admin.products.edit', product.id)"
            class="text-blue-600 hover:underline"
          >
            Edit
          </Link>

          <button
            @click="deleteProduct(product.id)"
            class="text-red-600 hover:underline"
          >
            Delete
          </button>
        </div>
      </td>
    </tr>
  </tbody>
</table>
  </div>
</template>
