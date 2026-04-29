<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-w-100 mx-auto p-6">
            <h1 class="text-2xl font-bold mb-6">Edit Post</h1>

            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label class="block mb-2">Title</label>
                    <input v-model="form.title" type="text" class="w-full border rounded px-3 py-2" required>
                    <div v-if="form.errors.title" class="text-red-500 text-sm">{{ form.errors.title }}</div>
                </div>

                <div class="mb-4">
                    <label class="block mb-2" for="author_id">Author</label>
                    <select id="author_id" v-model="form.author_id" class="w-full rounded border px-3 py-2" required>
                        <option disabled value="">Select an author</option>
                        <option v-for="author in props.authors" :key="author.id" :value="String(author.id)">
                            {{ author.name }}
                        </option>
                    </select>
                    <div v-if="form.errors.author_id" class="text-red-500 text-sm">{{ form.errors.author_id }}</div>
                </div>


                <div class="mb-4">
                    <label class="block mb-2">Content</label>
                    <textarea v-model="form.description" class="w-full border rounded px-3 py-2" rows="6"
                        required></textarea>
                    <div v-if="form.errors.description" class="text-red-500 text-sm">{{ form.errors.description }}</div>
                </div>

                <div class="mb-4">
                    <label class="flex items-center">
                        <input v-model="form.published" type="checkbox" class="mr-2">
                        published
                    </label>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded" :disabled="form.processing">
                        save
                    </button>
                    <Link :href="index().url" class="bg-gray-300 px-4 py-2 rounded">
                        cancel
                    </Link>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { BreadcrumbItem } from '@/types';
import { index } from '@/routes/posts';
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';

const props = defineProps<{
    post: {
        id: number;
        title: string;
        description: string | null;
        content: string | null;
        author_id: number;
        published: boolean;
    },
    authors: Array<{ id: number, name: string }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Posts',
        href: index().url,
    },
    {
        title: 'Edit post',
        href: '#',
    },
];

const form = useForm({
    title: props.post.title,
    description: props.post.description ?? props.post.content ?? '',
    author_id: String(props.post.author_id),
    published: props.post.published,
});

// Функция отправки формы
const submit = () => {
    form.put(`/posts/${props.post.id}`);
};
</script>