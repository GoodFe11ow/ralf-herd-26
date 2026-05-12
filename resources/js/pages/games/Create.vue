<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import InputError from '@/components/InputError.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Games',
        href: '/games',
    },
    {
        title: 'Create',
        href: '/games/create',
    },
];

const form = useForm({
    title: '',
    image: '',
    description: '',
    platform: '',
    release_year: '',
    genre: '',
});

const submit = () => {
    form.post('/games')
}
</script>

<template>
    <Head title="Create Game" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto flex h-full w-full max-w-2xl flex-1 flex-col gap-6 rounded-xl p-4">
            <div>
                <h1 class="text-3xl font-semibold">Add Game</h1>
                <p class="mt-2 text-sm text-muted-foreground">
                    Fill in the game details below.
                </p>
            </div>

            <form class="grid gap-4" @submit.prevent="submit">
                <div>
                    <label class="text-sm font-medium">Title</label>
                    <input v-model="form.title" type="text" class="mt-2 w-full rounded-md border px-3 py-2" />
                    <InputError :message="form.errors.title" />
                </div>

                <div>
                    <label class="text-sm font-medium">Image URL</label>
                    <input v-model="form.image" type="text" class="mt-2 w-full rounded-md border px-3 py-2" />
                    <InputError :message="form.errors.image" />
                </div>

                <div>
                    <label class="text-sm font-medium">Description</label>
                    <textarea v-model="form.description" class="mt-2 w-full rounded-md border px-3 py-2" rows="4"></textarea>
                    <InputError :message="form.errors.description" />
                </div>

                <div>
                    <label class="text-sm font-medium">Platform</label>
                    <input v-model="form.platform" type="text" class="mt-2 w-full rounded-md border px-3 py-2" />
                    <InputError :message="form.errors.platform" />
                </div>

                <div>
                    <label class="text-sm font-medium">Release year</label>
                    <input v-model="form.release_year" type="number" class="mt-2 w-full rounded-md border px-3 py-2" />
                    <InputError :message="form.errors.release_year" />
                </div>

                <div>
                    <label class="text-sm font-medium">Genre</label>
                    <input v-model="form.genre" type="text" class="mt-2 w-full rounded-md border px-3 py-2" />
                    <InputError :message="form.errors.genre" />
                </div>

                <div class="mt-4 flex justify-end gap-3">
                    <Link href="/games" class="rounded-md border px-4 py-2 text-sm">
                        Cancel
                    </Link>

                    <button type="submit" class="rounded-md bg-black px-4 py-2 text-sm text-white" :disabled="form.processing">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
