<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from "@inertiajs/vue3";
import { type BreadcrumbItem } from '@/types';
import { onMounted, ref } from 'vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import InputError from '@/components/InputError.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Textarea from '@/components/ui/textarea/Textarea.vue';


type Marker = {
    id: number;
    name: string;
    latitude: string;
    longitude: string;
    description: string | null;
    added: string;
    edited: string;
}

const props = defineProps<{
    markers: Marker[];
}>();

const form = useForm({
    name: '',
    latitude: '',
    longitude: '',
});

const submit = () => {
    form.post('/markers');
}

const mapElement = ref<HTMLElement | null>(null);

onMounted(() => {
    if (!mapElement.value) {
        return;
    }

    const map = L.map(mapElement.value).setView([58.2529, 22.4896], 7);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreerMap contributors',
    }).addTo(map)

    props.markers.forEach((marker) => {
        const latitude = Number(marker.latitude);
        const longitude = Number(marker.longitude);

        if (Number.isNaN(latitude) || Number.isNaN(longitude)) {
            return;
        }

        L.marker([latitude, longitude]).addTo(map).bindPopup(`<strong>${marker.name}</strong><br>
        ${marker.description ?? 'No description'}`);
    });
})

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Markers',
        href: '/markers',
    },
]
</script>

<template>

    <Head title="Markers" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                <h2 class="text-lg font-medium">Add marker</h2>

                <form class="mt-4 grid gap-4" @submit.prevent="submit">
                    <div>
                        <Input v-model="form.name" placeholder="Marker name" />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <Input v-model="form.latitude" placeholder="Latitude" />
                            <InputError :message="form.errors.latitude" />
                        </div>

                        <div>
                            <Input v-model="form.longitude" placeholder="Longitude" />
                            <InputError :message="form.errors.longitude" />
                        </div>
                    </div>

                    <div>
                        <Textarea v-model="form.description" placeholder="Description" />
                        <InputError :message="form.errors.description" />
                    </div>

                    <div>
                        <Button type="submit" :disabled="form.processing">
                            Save marker
                        </Button>
                    </div>
                </form>
            </div>

            <div class="rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                <h1 class="text-2xl font-semibold">Map Markers</h1>
                <p class="mt-2 text-sm text-muted-foreground">
                    Total markers: {{ markers.length }}
                </p>
            </div>

            <div class="rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                <h2 class="text-lg font-medium">Current marker data</h2>
                <div
                    class="mt-4 h-96 overflow-hidden rounded-lg border border-sidebar-border/70 dark:border-sidebar-border">
                    <div ref="mapElement" class="h-full w-full" />
                </div>


                <div v-if="markers.length === 0" class="mt-3 text-sm text-muted-foreground">
                    No markers yet.
                </div>

                <ul v-else class="mt-3 space-y-3">
                    <li v-for="marker in markers" :key="marker.id"
                        class="rounded-lg border border-sidebar-border/70 p-3 dark:border-sidebar-border">
                        <p class="font-medium">{{ marker.name }}</p>
                        <p class="text-sm text-muted-foreground">
                            {{ marker.latitude }}, {{ marker.longitude }}
                        </p>
                        <p v-if="marker.description" class="mt-2 text-sm">
                            {{ marker.description }}
                        </p>
                    </li>
                </ul>
            </div>
        </div>
    </AppLayout>
</template>