<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
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
        title: 'Authors',
        href: '/authors',
    },
];

type Author = {
    id: number,
    name: string,
    email: string,
    bio: string,
    created_at: string,
    updated_at: string,
    created_at_formated: string,
    updated_at_formated: string,
}

defineProps<{
    authors: PaginatedResponse;
}>()

interface PaginationLink {
    url: string | null;
    label: string;
    page?: number | null;
    active: boolean;
}

interface PaginatedResponse {
    current_page: number;
    data: Author[];
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
}

// Функции для действий с авторами
const editAuthor = (authorId: number) => {
    router.get(`/authors/${authorId}/edit`);
};

const deleteAuthor = (authorId: number) => {
    if (confirm('Вы уверены, что хотите удалить этого автора?')) {
        router.delete(`/authors/${authorId}`);
    }
};

</script>

<template>
    <Head title="Authors" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <Table>
                <TableCaption>A list of all authors.</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead class="w-[100px]">ID</TableHead>
                        <TableHead>Name</TableHead>
                        <TableHead>Email</TableHead>
                        <TableHead>Bio</TableHead>
                        <TableHead class="text-right">Created at</TableHead>
                        <TableHead class="text-right">Updated at</TableHead>
                        <TableHead><span class="sr-only">Action</span></TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="author in authors.data" :key="author.id">
                        <TableCell class="font-medium">{{ author.id }}</TableCell>
                        <TableCell>{{ author.name }}</TableCell>
                        <TableCell>{{ author.email }}</TableCell>
                        <TableCell class="max-w-xs truncate">{{ author.bio }}</TableCell>
                        <TableCell class="text-right">{{ author.created_at_formated }}</TableCell>
                        <TableCell class="text-right">{{ author.updated_at_formated }}</TableCell>
                        <TableCell>
                            <div class="flex justify-end">
                                <DropdownMenu>
                                    <DropdownMenuTrigger as-child>
                                        <Button size="icon" variant="ghost">
                                            <MoreVertical />
                                        </Button>
                                    </DropdownMenuTrigger>
                                    <DropdownMenuContent>
                                        <DropdownMenuItem @click="editAuthor(author.id)">
                                            Edit
                                        </DropdownMenuItem>
                                        <DropdownMenuSeparator />
                                        <DropdownMenuItem 
                                            class="text-destructive" 
                                            @click="deleteAuthor(author.id)"
                                        >
                                            Delete
                                        </DropdownMenuItem>
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </div>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
            <Pagination 
                class="w-full" 
                :page="authors.current_page" 
                v-slot="{ page }" 
                :items-per-page="authors.per_page"
                :total="authors.total" 
                @update:page="(page) => router.get('/authors', {page: page})"
            >
                <PaginationContent v-slot="{ items }">
                    <PaginationPrevious />
                    <template v-for="(item, index) in items">
                        <PaginationItem v-if="item.type === 'page'" :key="index" :value="item.value" as-child>
                            <Button :variant="item.value===page? 'default': 'outline'">
                                {{ item.value }}
                            </Button>
                        </PaginationItem>
                    </template>
                    <PaginationEllipsis :index="4" />
                    <PaginationNext />
                </PaginationContent>
            </Pagination>
        </div>
    </AppLayout>
</template>