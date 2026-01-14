<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-w-100 mx-auto p-6">
            <h1 class="text-2xl font-bold mb-6">Edit Post</h1>
            
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label class="block mb-2">Title</label>
                    <input 
                        v-model="form.title" 
                        type="text" 
                        class="w-full border rounded px-3 py-2"
                        required
                    >
                    <div v-if="form.errors.title" class="text-red-500 text-sm">{{ form.errors.title }}</div>
                </div>

                <div class="mb-4">
                    <label class="block mb-2">Author</label>
                    <input 
                        v-model="form.author_id" 
                        type="text" 
                        class="w-full border rounded px-3 py-2"
                        required
                    >
                    <div v-if="form.errors.author_id" class="text-red-500 text-sm">{{ form.errors.author_id }}</div>
                </div>

                <div class="mb-4">
                    <label class="block mb-2">Content</label>
                    <textarea 
                        v-model="form.content" 
                        class="w-full border rounded px-3 py-2" 
                        rows="6"
                        required
                    ></textarea>
                    <div v-if="form.errors.content" class="text-red-500 text-sm">{{ form.errors.content }}</div>
                </div>

                <div class="mb-4">
                    <label class="flex items-center">
                        <input 
                            v-model="form.published" 
                            type="checkbox" 
                            class="mr-2"
                        >
                        published
                    </label>
                </div>

                <div class="flex gap-4">
                    <button 
                        type="submit" 
                        class="bg-blue-500 text-white px-4 py-2 rounded"
                        :disabled="form.processing"
                    >
                        save
                    </button>
                    <Link 
                        :href="index().url" 
                        class="bg-gray-300 px-4 py-2 rounded"
                    >
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

// Получаем данные поста из контроллера
const props = defineProps<{
    post: {
        id: number;
        title: string;
        content: string;
        author_id: { id: number }; // Изменено на объект
        published: boolean;
    },
    users: Array<{ id: number, name: string }>;
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

// Создаем форму с текущими данными поста
const form = useForm({
    title: props.post.title,
    content: props.post.content,
    author_id: props.post.author_id,
    published: props.post.published,
});

// Функция отправки формы
const submit = () => {
    form.put(`/posts/${props.post.id}`);
};
</script>