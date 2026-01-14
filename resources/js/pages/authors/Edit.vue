<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-w-100 mx-auto p-6">
            <h1 class="text-2xl font-bold mb-6">Edit Author</h1>
            
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label class="block mb-2">Name</label>
                    <input 
                        v-model="form.name" 
                        type="text" 
                        class="w-full border rounded px-3 py-2"
                        required
                    >
                    <div v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</div>
                </div>

                <div class="mb-4">
                    <label class="block mb-2">Email</label>
                    <input 
                        v-model="form.email" 
                        type="text" 
                        class="w-full border rounded px-3 py-2"
                        required
                    >
                    <div v-if="form.errors.email" class="text-red-500 text-sm">{{ form.errors.email }}</div>
                </div>

                <div class="mb-4">
                    <label class="block mb-2">Bio</label>
                    <textarea 
                        v-model="form.bio" 
                        class="w-full border rounded px-3 py-2" 
                        rows="6"
                        required
                    ></textarea>
                    <div v-if="form.errors.bio" class="text-red-500 text-sm">{{ form.errors.bio }}</div>
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
import { index } from '@/routes/authors';
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';

// Получаем данные поста из контроллера
const props = defineProps<{
    author: {
        id: number;
        name: string,
        email: string,
        bio: string,
    }
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Authors',
        href: index().url,
    },
    {
        title: 'Edit author',
        href: '#',
    },
];

// Создаем форму с текущими данными поста
const form = useForm({
    name: props.author.name,
    email: props.author.email,
    bio: props.author.bio,
});

// Функция отправки формы
const submit = () => {
    form.put(`/authors/${props.author.id}`);
};
</script>