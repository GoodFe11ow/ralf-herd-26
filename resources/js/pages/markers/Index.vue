<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, router } from "@inertiajs/vue3";
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
    description: '',
});

const editingMarkerId = ref<number | null>(null);

const submit = () => {
    const options = {
        onSuccess: () => {
            editingMarkerId.value = null;
            form.reset();
        },
    };

    if (editingMarkerId.value !== null) {
        form.put(`/markers/${editingMarkerId.value}`, options);
        return;
    }

    form.post('/markers', options);
}

const startEdit = (marker: Marker) => {
    editingMarkerId.value = marker.id;
    form.name = marker.name;
    form.latitude = marker.latitude;
    form.longitude = marker.longitude;
    form.description = marker.description ?? '';

}

const cancelEdit = () => {
    editingMarkerId.value = null;
    form.reset();
}

const deleteMarker = (id: number) => {
    if (confirm('Delete this marker?')) {
        router.delete(`/markers/${id}`);
    }
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

    map.on('click', (event) => {
        form.latitude = event.latlng.lat.toFixed(7);
        form.longitude = event.latlng.lng.toFixed(7);
    });

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
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
            <div class="rounded-xl border border-foreground/30 p-5 dark:border-sidebar-border">
                <h1 class="text-2xl font-semibold">Map Markers</h1>
                <p class="mt-2 text-sm text-muted-foreground">
                    Manage your saved locations on the map. Total markers: {{ markers.length }}
                </p>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-xl border border-foreground/30 p-5 dark:border-sidebar-border">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-medium">
                                {{ editingMarkerId !== null ? 'Edit marker' : 'Add marker' }}
                            </h2>
                            <p class="mt-1 text-sm text-muted-foreground">
                                Add a location manually or click the map to fill coordinates automatically.
                            </p>
                        </div>
                    </div>

                    <form class="mt-6 grid gap-4" @submit.prevent="submit">
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
                        <p class="text-sm text-muted-foreground">
                            Tip: click on the map to fill latitude and longitude automatically.
                        </p>

                        <div>
                            <Textarea v-model="form.description" placeholder="Description" />
                            <InputError :message="form.errors.description" />
                        </div>

                        <div class="flex gap-3">
                            <Button type="submit" :disabled="form.processing">
                                {{ editingMarkerId !== null ? 'Update marker' : 'Save marker' }}
                            </Button>

                            <Button v-if="editingMarkerId !== null" type="button" variant="outline" @click="cancelEdit">
                                Cancel
                            </Button>
                        </div>
                    </form>
                </div>

                <div class="rounded-xl border border-foreground/30 p-5 dark:border-sidebar-border">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-medium">Interactive map</h2>
                            <p class="mt-1 text-sm text-muted-foreground">
                                Click anywhere on the map to select coordinates for a new marker.
                            </p>
                        </div>
                        <div class="rounded-full bg-muted px-3 py-1 text-sm text-muted-foreground">
                            {{ markers.length }} markers
                        </div>
                    </div>

                    <div class="mt-6 h-[28rem] overflow-hidden rounded-lg border border-sidebar-border/70 dark:border-sidebar-border lg:h-[34rem]">
                        <div ref="mapElement" class="h-full w-full" />
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-foreground/30 p-5 dark:border-sidebar-border">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-medium">Saved markers</h2>
                        <p class="mt-1 text-sm text-muted-foreground">
                            Browse, edit, or delete the locations you have already added.
                        </p>
                    </div>
                </div>

                <div v-if="markers.length === 0" class="mt-6 rounded-lg bg-muted/40 px-4 py-10 text-center text-sm text-muted-foreground">
                    No markers yet.
                </div>

                <div v-else class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    <article
                        v-for="marker in markers"
                        :key="marker.id"
                        class="flex h-full flex-col justify-between rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border"
                    >
                        <div>
                            <div class="flex items-start justify-between gap-3">
                                <p class="font-medium">{{ marker.name }}</p>
                                <span class="rounded-full bg-muted px-2.5 py-1 text-xs text-muted-foreground">
                                    #{{ marker.id }}
                                </span>
                            </div>
                            <p class="mt-2 text-sm text-muted-foreground">
                                {{ marker.latitude }}, {{ marker.longitude }}
                            </p>
                            <p v-if="marker.description" class="mt-3 text-sm leading-6">
                                {{ marker.description }}
                            </p>
                        </div>

                        <div class="mt-4 flex gap-3">
                            <Button type="button" variant="outline" @click="startEdit(marker)">
                                Edit
                            </Button>

                            <Button type="button" variant="destructive" @click="deleteMarker(marker.id)">
                                Delete
                            </Button>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
