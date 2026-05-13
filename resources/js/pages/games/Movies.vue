<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';

type Movie = {
    id: number;
    title: string;
    image: string;
    description: string;
    director: string;
    release_year: number;
    genre: string;
    created_at?: string;
    updated_at?: string;
};

const props = defineProps<{
    movies: Movie[];
    error: string | null;
    sourceUrl: string;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Games',
        href: '/games',
    },
    {
        title: 'Movies API',
        href: '/games/movies',
    },
];
</script>

<template>
    <Head title="Movies API" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
            <div class="flex flex-wrap items-start justify-between gap-4 rounded-xl border border-sidebar-border/70 p-5 dark:border-sidebar-border">
                <div>
                    <h1 class="text-2xl font-semibold">Classmate Movies API</h1>
                    <p class="mt-2 text-sm text-muted-foreground">
                        Browse movie cards built from your classmate's API response.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a
                        :href="sourceUrl"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="rounded-md border px-4 py-2 text-sm"
                    >
                        Open source API
                    </a>

                    <Link
                        href="/games"
                        class="rounded-md bg-black px-4 py-2 text-sm text-white"
                    >
                        Back to games
                    </Link>
                </div>
            </div>

            <div
                v-if="error"
                class="rounded-xl border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-800 dark:border-amber-800 dark:bg-amber-950/40 dark:text-amber-200"
            >
                {{ error }}
            </div>

            <div
                v-if="!error && movies.length === 0"
                class="rounded-xl border border-sidebar-border/70 p-6 text-sm text-muted-foreground dark:border-sidebar-border"
            >
                No movies were returned by the external API.
            </div>

            <div v-else-if="!error" class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                <article
                    v-for="movie in movies"
                    :key="movie.id"
                    class="flex h-full flex-col rounded-xl border border-gray-300 p-4 dark:border-sidebar-border"
                >
                    <img
                        :src="movie.image"
                        :alt="movie.title"
                        class="h-52 w-full rounded-lg object-cover"
                    />

                    <div class="mt-4 flex flex-1 flex-col">
                        <div class="flex items-start justify-between gap-3">
                            <h2 class="text-lg font-semibold">{{ movie.title }}</h2>
                            <span class="rounded-full bg-muted px-2.5 py-1 text-xs text-muted-foreground">
                                #{{ movie.id }}
                            </span>
                        </div>

                        <p class="mt-2 text-sm leading-6 text-muted-foreground">
                            {{ movie.description }}
                        </p>

                        <div class="mt-4 grid gap-2 text-sm">
                            <div class="rounded-lg bg-muted px-3 py-2">
                                <p class="font-medium">Director</p>
                                <p class="mt-1 text-muted-foreground">{{ movie.director }}</p>
                            </div>

                            <div class="grid gap-2 sm:grid-cols-2">
                                <div class="rounded-lg bg-muted px-3 py-2">
                                    <p class="font-medium">Release year</p>
                                    <p class="mt-1 text-muted-foreground">{{ movie.release_year }}</p>
                                </div>

                                <div class="rounded-lg bg-muted px-3 py-2">
                                    <p class="font-medium">Genre</p>
                                    <p class="mt-1 text-muted-foreground">{{ movie.genre }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </AppLayout>
</template>
