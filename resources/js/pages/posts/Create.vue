<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { create, store } from '@/routes/posts';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import Switch from '@/components/ui/switch/Switch.vue';
import Button from '@/components/ui/button/Button.vue';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import InputError from '@/components/InputError.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Post create',
        href: create().url,
    },
];

const form = useForm({
    title: '',
    content: '',
    author: '',
    publish: false
})

const submit = () => {
    form.post(store().url)
}
</script>

<template>
    <Head title="Posts" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="max-w-2xl h-full mx-auto w-full p-4">
                <h3 class="text-lg font-medium">Post create</h3>
                <form @submit.prevent="submit">
                    <div class="grid gap-4 mt-6">
                        <div>
                            <Label for="title">Title</Label>
                            <Input class="mt-2" id="title" v-model="form.title" />
                            <InputError :message="form.errors.title" />
                        </div>
                        <div>
                            <Label for="content">Content</Label>
                            <Textarea class="mt-2" id="content" v-model="form.content" />
                        </div>
                        <div>
                            <Label for="author">Author</Label>
                            <Input class="mt-2" id="author" v-model="form.author" />
                        </div>
                        <div class="flex items-center space-x-2">
                            <Switch id="published" />
                            <Label for="published">Published</Label>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <Button type="submit">Save</Button>
                    </div>
                </form>
                <pre>{{ form }}</pre>
            </div>
        </div>
    </AppLayout>
</template>
