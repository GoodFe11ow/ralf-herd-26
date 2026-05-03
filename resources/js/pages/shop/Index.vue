<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { ref } from 'vue';

type Product = {
    id: number;
    name: string;
    image: string | null;
    price: string;
    description: string;
};

defineProps<{
    products: Product[];
}>();

const activeProductId = ref<number | null>(null);
const addedProductId = ref<number | null>(null);

const form = useForm({
    product_id: '',
    quantity: 1,
});

const addToCart = (productId: number) => {
    activeProductId.value = productId;
    form.product_id = String(productId);
    form.quantity = 1;

    form.post('/cart', {
        preserveScroll: true,
        onSuccess: () => {
            addedProductId.value = productId;

            window.setTimeout(() => {
                if (addedProductId.value === productId) {
                    addedProductId.value = null;
                }
            }, 1400);
        },
        onFinish: () => {
            activeProductId.value = null;
        },
    })
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Shop',
        href: '/shop',
    },
];
</script>

<template>
    <Head title="Shop" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
            <div>
                <h1 class="text-3xl font-semibold">Shop</h1>
                <p class="mt-2 text-sm text-muted-foreground">
                    Browse available products and add them to your cart.
                </p>
            </div>

            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                <div
                    v-for="product in products"
                    :key="product.id"
                    class="rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border"
                >
                    <img
                        v-if="product.image"
                        :src="product.image"
                        :alt="product.name"
                        class="h-48 w-full rounded-lg object-cover"
                    />

                    <div class="mt-4 flex items-start justify-between gap-4">
                        <h2 class="text-lg font-semibold">{{ product.name }}</h2>
                        <span class="text-sm font-medium">${{ product.price }}</span>
                    </div>

                    <p class="mt-3 text-sm text-muted-foreground">
                        {{ product.description }}
                    </p>

                    <div class="mt-4">
                        <button
                            type="button"
                            class="rounded-md px-4 py-2 text-sm font-medium text-white transition disabled:cursor-not-allowed disabled:opacity-70"
                            :class="
                                addedProductId === product.id
                                    ? 'bg-emerald-600 hover:bg-emerald-600'
                                    : 'bg-black hover:bg-neutral-800 active:scale-[0.98]'
                            "
                            :disabled="activeProductId === product.id && form.processing"
                            @click="addToCart(product.id)"
                        >
                            <span v-if="activeProductId === product.id && form.processing">Adding...</span>
                            <span v-else-if="addedProductId === product.id">Added to cart</span>
                            <span v-else>Add to cart</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
