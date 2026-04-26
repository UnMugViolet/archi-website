<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

type ProjectListItem = {
    id: number;
    title: string;
    slug: string;
    cover_image: string | null;
};

const props = defineProps<{
    projects: ProjectListItem[];
}>();

console.log(props.projects);
</script>

<template>
    <Head title="Projets" />

    <div class="min-h-screen text-stone-900">
        <main class="mx-auto w-full px-50 px-6 py-12">
            <header class="mb-10">
                <h1 class="mt-2 text-4xl font-semibold tracking-tight">
                    Archi website
                </h1>
            </header>

            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <article
                    v-for="project in projects"
                    :key="project.id"
                    class="overflow-hidden border border-stone-200 bg-white"
                >
                    <Link :href="`/${project.slug}`" class="block">
                        <img
                            v-if="project.cover_image"
                            :src="project.cover_image"
                            :alt="project.title"
                            class="h-60 w-full object-cover"
                        />
                        <div
                            v-else
                            class="flex h-60 items-center justify-center bg-stone-200 text-stone-500"
                        >
                            Sans image
                        </div>

                        <div class="space-y-3 p-5">
                            <h2 class="text-xl font-medium">
                                {{ project.title }}
                            </h2>
                            <span class="inline-block text-sm font-medium text-stone-900">
                                Voir le projet
                            </span>
                        </div>
                    </Link>
                </article>
            </div>
        </main>
    </div>
</template>
