<template>
  <AppLayout :breadcrumbs="breadcrumbs">
      <div class="max-w-100 mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">{{ post!.title }}</h1>
        <p class="text-gray-600 mb-2"><span class="font-bold text-black">Author:</span><br>{{ post!.author.name }}</p>
        <div class="mt-4 whitespace-pre-line"><span class="font-bold">Content:</span><br>
          {{ post!.content }}
        </div>
        <button @click="goBack" class="border-black border-2 mt-10 p-2 rounded-xl">back</button>
      </div>
  </AppLayout>   
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { index } from '@/routes/posts';
import { router } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Posts',
        href: index().url,
    },
    {
        title: 'View post',
        href: '#',
    },
];

const goBack = () => {
    router.visit('/posts')
}

defineProps({
  post: Object
});
</script>
