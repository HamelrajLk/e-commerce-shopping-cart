<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { router } from '@inertiajs/vue3'

defineProps({
    products: Array
})

const addToCart = (productId) => {
    router.post('/cart', {
        product_id: productId
    })
}
</script>

<template>
    <Head title="Products" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Products
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div
                        v-for="product in products"
                        :key="product.id"
                        class="border rounded-lg p-4"
                    >
                        <h2 class="font-semibold text-lg">{{ product.name }}</h2>
                        <p class="text-gray-600">$ {{ product.price }}</p>
                        <p class="text-sm text-gray-500">
                            Stock: {{ product.stock_quantity }}
                        </p>

                        <button
                            class="mt-4 bg-blue-600 text-white px-4 py-2 rounded disabled:opacity-50"
                            :disabled="product.stock_quantity === 0"
                            @click="addToCart(product.id)"
                        >
                            Add to Cart
                        </button>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

