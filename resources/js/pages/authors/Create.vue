<template>
    <Head title="Create Author" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="max-w-2xl mx-auto p-6">
            <h1 class="text-2xl font-bold mb-6">Create author</h1>
            
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
                        type="email" 
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
                    ></textarea>
                    <div v-if="form.errors.bio" class="text-red-500 text-sm">{{ form.errors.bio }}</div>
                </div>

                <div class="flex gap-4">
                    <button 
                        type="submit" 
                        class="bg-blue-500 text-white px-4 py-2 rounded"
                        :disabled="form.processing"
                    >
                        Create
                    </button>
                    <a 
                        href="/authors" 
                        class="bg-gray-300 px-4 py-2 rounded inline-block"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm, Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Authors',
        href: '/authors',
    },
    {
        title: 'Create',
        href: '#',
    },
];

// Создаем пустую форму
const form = useForm({
    name: '',
    email: '',
    bio: '',
});

// Функция отправки формы
const submit = () => {
    form.post('/authors');
};
</script>