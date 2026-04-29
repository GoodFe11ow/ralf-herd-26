<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class=" border border-gray-400 p-6 m-[10%] rounded-xl">
      <h1 class="text-2xl font-bold mb-4">{{ post!.title }}</h1>
      <p class="text-gray-600 mb-2"><span class="font-bold text-black">Author:</span><br>{{ post!.author.name }}</p>
      <div class="mt-4 whitespace-pre-line"><span class="font-bold">Content:</span><br>
        {{ post!.content }}
      </div>
      <div class="mt-8">
        <h2 class="text-xl font-semibold">Comments</h2>
        <form class="mt-4" @submit.prevent="submitComment(post.id)">
          <textarea v-model="form.content" class="w-full rounded-lg border px-3 py-2" rows="4"
            placeholder="Write a comment..."></textarea>
          <div v-if="form.errors.content" class="mt-1 text-sm text-red-500">
            {{ form.errors.content }}
          </div>

          <button type="submit" class="mt-3 rounded bg-blue-500 px-4 py-2 text-white" :disabled="form.processing">
            Add comment
          </button>
        </form>


        <div v-if="post.comments.length === 0" class="mt-3 text-sm text-gray-500">
          No comments yet.
        </div>


        <div v-else class="mt-4 space-y-4">
          <div v-for="comment in post.comments" :key="comment.id" class="rounded-lg border p-4">
            <div class="flex items-start justify-between gap-4">
              <p class="font-medium">{{ comment.author_name }}</p>

              <button v-if="isAdmin" type="button" class="rounded bg-red-500 px-3 py-1 text-sm text-white"
                @click="deleteComment(comment.id)">
                Delete
              </button>
            </div>

            <p class="mt-2 whitespace-pre-line text-sm text-gray-700">
              {{ comment.content }}
            </p>
          </div>

        </div>
      </div>

      <button @click="goBack" class="border border-gray-400 bg-gray-100 border-1 mt-10 p-2 rounded-xl">back</button>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { index } from '@/routes/posts';
import { router, useForm, usePage } from '@inertiajs/vue3';


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

const form = useForm({
  content: '',
});

const submitComment = (postId: number) => {
  form.post(`/posts/${postId}/comments`, {
    onSuccess: () => form.reset(),
  })
}

const deleteComment = (commentId: number) => {
  if (confirm('Delete this comment?')) {
    router.delete(`/comments/${commentId}`);
  }
};

const page = usePage();

const isAdmin = Boolean(page.props.auth.user?.is_admin);

defineProps<{
  post: {
    id: number;
    title: string;
    content: string;
    author: {
      name: string;
    };
    comments: Array<{
      id: number;
      author_name: string;
      content: string;
      created_at: string;
    }>;
  };
}>();
</script>
