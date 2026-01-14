<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { create, index } from '@/routes/posts';
import { TableBody, Table, TableCaption, TableCell, TableHead, TableRow, TableHeader } from '@/components/ui/table';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import Button from '@/components/ui/button/Button.vue';
import { MoreVertical } from 'lucide-vue-next';
import {
    Pagination,
    PaginationContent,
    PaginationEllipsis,
    PaginationItem,
    PaginationNext,
    PaginationPrevious,
} from '@/components/ui/pagination';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Posts',
        href: create().url,
    },
];

 export type Post = {
    id: number,
    title: string,
    content: string,
    published: boolean,
    created_at: string,
    updated_at: string,
    created_at_formated: string,
    updated_at_formated: string,
    author: {
        id: number,
        name: string
    }
}

defineProps<{
    posts: PaginatedResponse;
}>()


interface PaginationLink {
    url: string | null;
    label: string;
    page?: number | null;
    active: boolean;
}

interface PaginatedResponse {
    current_page: number;
    data: Post[];
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: PaginationLink[];
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number;
    total: number;
};

const editPost = (postId: number) => {
    router.get(`/posts/${postId}/edit`);
};

const deletePost = (postId: number) => {
    if (confirm('Are you shure u want delet this post?')) {
        router.delete(`/posts/${postId}`);
    }
};

const viewPost = (postId: number) => {
    router.get(`/posts/${postId}`)
}

</script>

<template>

    <Head title="Posts" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <Table>
                <TableCaption>A list of your recent blog posts.</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead class="w-[100px]">
                            ID
                        </TableHead>
                        <TableHead>Title</TableHead>
                        <TableHead>Author</TableHead>
                        <TableHead class="text-right">Created at</TableHead>
                        <TableHead class="text-right">Updated at</TableHead>
                        <TableHead class="text-right">Published</TableHead>
                        <TableHead><span class="st-only">Action</span></TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="post in posts.data" :key="post.id">
                        <TableCell class="font-medium">
                            {{ post.id }}
                        </TableCell>
                        <TableCell>{{ post.title }}</TableCell>
                        <TableCell>{{ post.author.name }}</TableCell>
                        <TableCell class="text-right">{{ post.created_at_formated }}</TableCell>
                        <TableCell class="text-right">{{ post.updated_at_formated }}</TableCell>
                        <TableCell class="text-right">{{ post.published }}</TableCell>
                        <TableCell>
                            <div class="flex justify-end">
                                <DropdownMenu class="">
                                    <DropdownMenuTrigger as-child><Button size="icon" variant="ghost">
                                            <MoreVertical />
                                        </Button></DropdownMenuTrigger>
                                    <DropdownMenuContent class="">
                                        <DropdownMenuItem @click="viewPost(post.id)">View</DropdownMenuItem>
                                        <DropdownMenuItem @click="editPost(post.id)">Edit</DropdownMenuItem>
                                        <DropdownMenuSeparator />
                                        <DropdownMenuItem @click="deletePost(post.id)" class="text-destructive">Delete</DropdownMenuItem>
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </div>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
            <Pagination class="w-full" :page="posts.current_page" v-slot="{ page }" :items-per-page="posts.per_page"
                :total="posts.total" @update:page="(page) => router.get(index().url, {page: page})">
                <PaginationContent v-slot="{ items }">
                    <PaginationPrevious />

                    <template v-for="(item, index) in items" >
                        <PaginationItem v-if="item.type === 'page'" :key="index" :value="item.value" as-child>
                            <Button :variant="item.value===page? 'default': 'outline'">{{ item.value }}</Button>
                        </PaginationItem>
                    </template>

                    <PaginationEllipsis :index="4" />

                    <PaginationNext />
                </PaginationContent>
            </Pagination>
        </div>
    </AppLayout>
</template>
