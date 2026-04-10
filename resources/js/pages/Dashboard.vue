<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

type WeatherData = {
    city: string;
    country: string | null;
    temperature: number | null;
    description: string | null;
    humidity: number | null;
    wind: number | null;
    icon: string | null;
};

type WeatherProps = {
    data: WeatherData | null;
    error: string | null;
    search: {
        city: string;
        country: string;
    };
};

const props = defineProps<{
    weather: WeatherProps;
}>();

const form = useForm({
    city: props.weather.search.city,
    country: props.weather.search.country,
});

const submit = () => {
    router.get(
        dashboard().url,
        {
            city: form.city,
            country: form.country || undefined,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
};

const locationLabel = () => {
    if (!props.weather.data) {
        return 'Weather search';
    }

    return props.weather.data.country
        ? `${props.weather.data.city}, ${props.weather.data.country}`
        : props.weather.data.city;
};
</script>

<template>
    <Head title="Dashboard" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <div class="flex h-full flex-col gap-4 p-4">
                        <form class="grid gap-3 sm:grid-cols-[1fr_120px_auto]" @submit.prevent="submit">
                            <Input v-model="form.city" placeholder="City" />
                            <Input v-model="form.country" placeholder="Country" maxlength="2" />
                            <Button type="submit">Search</Button>
                        </form>

                        <InputError :message="weather.error" />

                        <div v-if="weather.data" class="flex h-full justify-between gap-4">
                            <div>
                                <p class="text-sm text-muted-foreground">{{ locationLabel() }}</p>
                                <h2 class="mt-2 text-5xl font-semibold">{{ weather.data.temperature }}°C</h2>
                                <ul class="mt-4 grid list-inside list-disc gap-1 text-muted-foreground">
                                    <li>{{ weather.data.description }}</li>
                                    <li>Humidity: {{ weather.data.humidity }}%</li>
                                    <li>Wind: {{ weather.data.wind }} m/s</li>
                                </ul>
                            </div>
                            <img
                                v-if="weather.data.icon"
                                class="size-20"
                                :src="`https://openweathermap.org/img/wn/${weather.data.icon}@4x.png`"
                                :alt="weather.data.description ?? 'Weather icon'"
                            />
                        </div>

                        <div
                            v-else
                            class="flex min-h-40 items-center justify-center rounded-lg bg-muted/40 px-4 text-center text-sm text-muted-foreground"
                        >
                            Search for a city to see the current weather.
                        </div>
                    </div>
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
            </div>
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <PlaceholderPattern />
            </div>
        </div>
    </AppLayout>
</template>
