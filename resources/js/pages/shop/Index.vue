<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { type AppPageProps, type BreadcrumbItem } from '@/types';
import { computed, ref } from 'vue';
import { CheckCircle2, ShoppingCart } from 'lucide-vue-next';

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
const selectedQuantities = ref<Record<number, number>>({});
const page = usePage<AppPageProps>();
const successMessage = computed(() => page.props.flash.success);

const form = useForm({
    product_id: '',
    quantity: 1,
});

const getSelectedQuantity = (productId: number) => {
    return selectedQuantities.value[productId] ?? 1;
};

const setSelectedQuantity = (productId: number, quantity: number) => {
    selectedQuantities.value[productId] = Math.max(1, quantity);
};

const addToCart = (productId: number) => {
    activeProductId.value = productId;
    form.product_id = String(productId);
    form.quantity = getSelectedQuantity(productId);

    form.post('/cart', {
        preserveScroll: true,
        onSuccess: () => {
            addedProductId.value = productId;
            selectedQuantities.value[productId] = 1;

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
            <div
                v-if="successMessage"
                class="flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800"
            >
                <CheckCircle2 class="mt-0.5 size-5 shrink-0" />
                <div>
                    <p class="font-medium">Payment completed</p>
                    <p class="mt-1 text-sm">
                        {{ successMessage }}
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-semibold">Shop</h1>
                    <p class="mt-2 text-sm text-muted-foreground">
                        Browse available products and add them to your cart.
                    </p>
                </div>

                <Link
                    href="/cart"
                    class="inline-flex items-center gap-2 rounded-md border border-sidebar-border/70 px-4 py-2 text-sm font-medium transition hover:bg-muted/50 dark:border-sidebar-border"
                >
                    <ShoppingCart class="size-4" />
                    <span>Cart</span>
                </Link>
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

                    <div class="mt-4 flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-muted-foreground">
                                Quantity
                            </p>
                            <div class="mt-2 inline-flex items-center rounded-md border border-sidebar-border/70 dark:border-sidebar-border">
                                <button
                                    type="button"
                                    class="px-3 py-2 text-sm transition hover:bg-muted/50 disabled:cursor-not-allowed disabled:opacity-50"
                                    :disabled="getSelectedQuantity(product.id) <= 1"
                                    @click="setSelectedQuantity(product.id, getSelectedQuantity(product.id) - 1)"
                                >
                                    -
                                </button>
                                <span class="min-w-10 px-2 text-center text-sm font-medium">
                                    {{ getSelectedQuantity(product.id) }}
                                </span>
                                <button
                                    type="button"
                                    class="px-3 py-2 text-sm transition hover:bg-muted/50"
                                    @click="setSelectedQuantity(product.id, getSelectedQuantity(product.id) + 1)"
                                >
                                    +
                                </button>
                            </div>
                        </div>

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
