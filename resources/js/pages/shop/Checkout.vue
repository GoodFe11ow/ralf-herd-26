<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { type AppPageProps, type BreadcrumbItem } from '@/types';
import { computed } from 'vue';
import { AlertCircle, CreditCard } from 'lucide-vue-next';

type CartItem = {
    product_id: number;
    name: string;
    price: string;
    image: string | null;
    quantity: number;
};

defineProps<{
    cart: CartItem[];
    total: string;
}>();

const page = usePage<AppPageProps>();
const errorMessage = computed(() => page.props.flash.error);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Shop',
        href: '/shop',
    },
    {
        title: 'Cart',
        href: '/cart',
    },
    {
        title: 'Checkout',
        href: '/checkout',
    },
];

const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    payment_method: 'stripe',
});

const submit = () => {
    form.post('/checkout');
};

</script>

<template>
    <Head title="Checkout" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="grid gap-6 rounded-xl p-4">
            <div
                v-if="errorMessage"
                class="flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-900"
            >
                <AlertCircle class="mt-0.5 size-5 shrink-0" />
                <div>
                    <p class="font-medium">Payment was not completed</p>
                    <p class="mt-1 text-sm">
                        {{ errorMessage }}
                    </p>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
            <div class="rounded-xl border border-sidebar-border/70 p-6 dark:border-sidebar-border">
                <h1 class="text-3xl font-semibold">Checkout</h1>
                <p class="mt-2 text-sm text-muted-foreground">
                    Enter your details to continue to payment.
                </p>

                <form class="mt-6 grid gap-4" @submit.prevent="submit">
                    <div>
                        <label class="text-sm font-medium">First name</label>
                        <input
                            v-model="form.first_name"
                            type="text"
                            class="mt-2 w-full rounded-md border px-3 py-2"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium">Last name</label>
                        <input
                            v-model="form.last_name"
                            type="text"
                            class="mt-2 w-full rounded-md border px-3 py-2"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium">Email</label>
                        <input
                            v-model="form.email"
                            type="email"
                            class="mt-2 w-full rounded-md border px-3 py-2"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium">Phone</label>
                        <input
                            v-model="form.phone"
                            type="text"
                            class="mt-2 w-full rounded-md border px-3 py-2"
                        />
                    </div>

                    <div class="rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                        <p class="text-sm font-medium">Payment method</p>
                        <label
                            class="mt-3 flex cursor-pointer items-start gap-3 rounded-lg border border-indigo-200 bg-indigo-50 p-4 text-indigo-950"
                        >
                            <input
                                v-model="form.payment_method"
                                type="radio"
                                name="payment_method"
                                value="stripe"
                                class="mt-1"
                            />
                            <CreditCard class="mt-0.5 size-5 shrink-0" />
                            <div>
                                <p class="font-medium">Stripe Checkout</p>
                                <p class="mt-1 text-sm text-indigo-900/80">
                                    Secure hosted checkout page for card payments.
                                </p>
                            </div>
                        </label>
                    </div>

                    <button
                        type="submit"
                        class="mt-4 rounded-md bg-black px-4 py-2 text-sm text-white"
                        :disabled="form.processing"
                    >
                        Continue to payment
                    </button>
                </form>
            </div>

            <div class="rounded-xl border border-sidebar-border/70 p-6 dark:border-sidebar-border">
                <h2 class="text-xl font-semibold">Order summary</h2>

                <div class="mt-4 space-y-4">
                    <div
                        v-for="item in cart"
                        :key="item.product_id"
                        class="flex items-center justify-between gap-4"
                    >
                        <div>
                            <p class="font-medium">{{ item.name }}</p>
                            <p class="text-sm text-muted-foreground">
                                Quantity: {{ item.quantity }}
                            </p>
                        </div>

                        <p class="text-sm font-medium">
                            ${{ (Number(item.price) * item.quantity).toFixed(2) }}
                        </p>
                    </div>
                </div>

                <div class="mt-6 border-t pt-4">
                    <div class="flex items-center justify-between text-lg font-semibold">
                        <span>Total</span>
                        <span>${{ total }}</span>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </AppLayout>
</template>
