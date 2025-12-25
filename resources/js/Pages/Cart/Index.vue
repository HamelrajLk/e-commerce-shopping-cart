
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { router } from '@inertiajs/vue3'

defineProps({
    cartItems: Array
})

const updateQuantity = (item, quantity) => {
    router.patch(`/cart/${item.id}`, { quantity })
}

const removeItem = (itemId) => {
    router.delete(`/cart/${itemId}`)
}
</script>

<template>
    <Head title="Cart" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Cart
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="max-w-4xl mx-auto p-6">
                        <h1 class="text-2xl font-bold mb-6">Your Cart</h1>

                        <div v-if="cartItems.length === 0">
                            Your cart is empty.
                        </div>

                        <div v-else>
                            <div
                                v-for="item in cartItems"
                                :key="item.id"
                                class="flex justify-between border-b py-4"
                            >
                                <div>
                                    <p class="font-semibold">{{ item.product.name }}</p>
                                    <p>$ {{ item.product.price }}</p>
                                </div>

                                <div class="flex items-center gap-4">
                                    <input
                                        type="number"
                                        min="1"
                                        :value="item.quantity"
                                        @change="updateQuantity(item, $event.target.value)"
                                        class="w-16 border rounded px-2"
                                    />

                                    <button
                                        class="text-red-600"
                                        @click="removeItem(item.id)"
                                    >
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </div>

                        <button
                            class="mt-6 bg-green-600 text-white px-4 py-2 rounded"
                            @click="router.post('/checkout')"
                        >
                            Checkout
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


