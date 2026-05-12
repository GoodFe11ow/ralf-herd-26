<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';

type Game = {
    id: number;
    title: string;
    image: string;
    description: string;
    platform: string;
    release_year: number;
    genre: string;
};

defineProps<{
    games: Game[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Games',
        href: '/games',
    },
];
</script>

<template>
    <Head title="Games" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
            <div>
                <h1 class="text-3xl font-semibold">Favorite Games</h1>
                <p class="mt-2 text-sm text-muted-foreground">
                    Browse your custom games collection.
                </p>
            </div>

            <div v-if="games.length === 0" class="rounded-xl border border-sidebar-border/70 p-6 text-sm text-muted-foreground dark:border-sidebar-border">
                No games added yet.
            </div>

            <div v-else class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                <div
                    v-for="game in games"
                    :key="game.id"
                    class="rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border"
                >
                    <img
                        :src="game.image"
                        :alt="game.title"
                        class="h-48 w-full rounded-lg object-cover"
                    />

                    <h2 class="mt-4 text-lg font-semibold">{{ game.title }}</h2>
                    <p class="mt-2 text-sm text-muted-foreground">{{ game.description }}</p>

                    <div class="mt-4 space-y-1 text-sm">
                        <p><span class="font-medium">Platform:</span> {{ game.platform }}</p>
                        <p><span class="font-medium">Year:</span> {{ game.release_year }}</p>
                        <p><span class="font-medium">Genre:</span> {{ game.genre }}</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
