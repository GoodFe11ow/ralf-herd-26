<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';

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
                            class="rounded-md bg-black px-4 py-2 text-sm text-white"
                        >
                            Add to cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
