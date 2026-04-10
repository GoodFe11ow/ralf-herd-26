<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from "@inertiajs/vue3";
import { type BreadcrumbItem } from '@/types';

type Marker = {
    id: number;
    name: string;
    latitude: string;
    longitude: string;
    description: string | null;
    added: string;
    edited: string;
}

defineProps<{
    markers: Marker[];
}>();

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
                <h1 class="text-2xl font-semibold">Map Markers</h1>
                <p class="mt-2 text-sm text-muted-foreground">
                    Total markers: {{ markers.length }}
                </p>
            </div>

            <div class="rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                <h2 class="text-lg font-medium">Current marker data</h2>

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