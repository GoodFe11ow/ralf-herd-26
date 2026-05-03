<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';

type CartItem = {
    product_id: number;
    name: string;
    price: string;
    image: string | null;
    quantity: number;
};

defineProps<{
    cart: CartItem[];
}>();

const updateQuantity = (productId: number, quantity: number) => {
    if (quantity < 1) {
        return
    }

    router.patch(`/cart/${productId}`, { quantity }, {
        preserveScroll: true
    });
};

const removeItem = (productId: number) => {
    if (confirm('Remove this product from cart?')) {
        router.delete(`/cart/${productId}`, {
            preserveScroll: true,
        });
    }
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Shop',
        href: '/shop',
    },
    {
        title: 'Cart',
        href: '/cart',
    },
];
</script>

<template>

    <Head title="Cart" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-semibold">Cart</h1>
                    <p class="mt-2 text-sm text-muted-foreground">
                        Review selected products before checkout.
                    </p>
                </div>

                <Link href="/shop" class="rounded-md border px-4 py-2 text-sm">
                    Back to shop
                </Link>
            </div>

            <div v-if="cart.length === 0"
                class="rounded-xl border border-sidebar-border/70 p-6 text-sm text-muted-foreground dark:border-sidebar-border">
                Your cart is empty.
            </div>

            <div v-else class="grid gap-4">
                <div v-for="item in cart" :key="item.product_id"
                    class="flex items-center justify-between gap-4 rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                    <div class="flex items-center gap-4">
                        <img v-if="item.image" :src="item.image" :alt="item.name"
                            class="h-20 w-20 rounded-lg object-cover" />

                        <div>
                            <h2 class="text-lg font-medium">{{ item.name }}</h2>

                            <div class="mt-2 flex items-center gap-3">
                                <button type="button" class="rounded border px-3 py-1 text-sm"
                                    :disabled="item.quantity <= 1"
                                    @click="updateQuantity(item.product_id, item.quantity - 1)">
                                    -
                                </button>

                                <span class="min-w-8 text-center text-sm">
                                    {{ item.quantity }}
                                </span>

                                <button type="button" class="rounded border px-3 py-1 text-sm"
                                    @click="updateQuantity(item.product_id, item.quantity + 1)">
                                    +
                                </button>
                            </div>
                        </div>

                        <div class="text-right">
                            <p class="text-sm text-muted-foreground">
                                ${{ item.price }} each
                            </p>
                            <p class="mt-1 text-sm font-medium">
                                ${{ (Number(item.price) * item.quantity).toFixed(2) }}
                            </p>

                            <button type="button"
                                class="mt-3 rounded border border-red-300 px-3 py-1 text-sm text-red-600"
                                @click="removeItem(item.product_id)">
                                Remove
                            </button>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <Link href="/checkout" class="rounded-md bg-black px-4 py-2 text-sm text-white">
                        Proceed to checkout
                    </Link>
                </div>

            </div>
        </div>
    </AppLayout>
</template>
