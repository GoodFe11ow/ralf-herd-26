<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
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

const props = defineProps<{
    games: Game[];
    filters: {
        search: string;
        genre: string;
        platform: string;
    };
}>();

const deleteGame = (gameId: number) => {
    if (confirm('Delete this game?')) {
        router.delete(`/games/${gameId}`)
    }
}

const filterForm = useForm({
    search: props.filters.search,
    genre: props.filters.genre,
    platform: props.filters.platform
});

const applyFilters = () => {
    router.get('/games', {
        search: filterForm.search || undefined,
        genre: filterForm.genre || undefined,
        platform: filterForm.platform || undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

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
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="text-3xl font-semibold">Favorite Games</h1>
                <p class="mt-2 text-sm text-muted-foreground">
                    Browse your custom games collection.
                </p>
            </div>

            <Link href="/games/create" class="rounded-md bg-black px-4 py-2 text-sm text-white">
                Add game
            </Link>
        </div>
        <form
            class="grid gap-4 rounded-xl border border-sidebar-border/70 p-4 md:grid-cols-4 dark:border-sidebar-border"
            @submit.prevent="applyFilters">
            <div>
                <label class="text-sm font-medium">Search</label>
                <input v-model="filterForm.search" type="text" placeholder="Search by title"
                    class="mt-2 w-full rounded-md border px-3 py-2" />
            </div>

            <div>
                <label class="text-sm font-medium">Genre</label>
                <input v-model="filterForm.genre" type="text" placeholder="RPG"
                    class="mt-2 w-full rounded-md border px-3 py-2" />
            </div>

            <div>
                <label class="text-sm font-medium">Platform</label>
                <input v-model="filterForm.platform" type="text" placeholder="PC"
                    class="mt-2 w-full rounded-md border px-3 py-2" />
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full rounded-md bg-black px-4 py-2 text-sm text-white">
                    Apply filters
                </button>
            </div>
        </form>



        <div v-if="games.length === 0"
            class="rounded-xl border border-sidebar-border/70 p-6 text-sm text-muted-foreground dark:border-sidebar-border">
            No games added yet.
        </div>

        <div v-else class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <div v-for="game in games" :key="game.id"
                class="rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                <img :src="game.image" :alt="game.title" class="h-48 w-full rounded-lg object-cover" />

                <h2 class="mt-4 text-lg font-semibold">{{ game.title }}</h2>
                <p class="mt-2 text-sm text-muted-foreground">{{ game.description }}</p>

                <div class="mt-4 space-y-1 text-sm">
                    <p><span class="font-medium">Platform:</span> {{ game.platform }}</p>
                    <p><span class="font-medium">Year:</span> {{ game.release_year }}</p>
                    <p><span class="font-medium">Genre:</span> {{ game.genre }}</p>
                </div>
                <div class="mt-4 flex justify-end gap-3">
                    <Link :href="`/games/${game.id}/edit`" class="rounded-md border px-4 py-2 text-sm">
                        Edit
                    </Link>

                    <button type="button" class="rounded-md border border-red-300 px-4 py-2 text-sm text-red-600"
                        @click="deleteGame(game.id)">
                        Delete
                    </button>
                </div>


            </div>
        </div>

    </AppLayout>
</template>
