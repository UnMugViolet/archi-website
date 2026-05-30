<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

type ProjectThumbnail = {
    id: number;
    title: string;
    slug: string;
    cover_image: string | null;
};

const { project } = defineProps<{
    project: ProjectThumbnail;
}>();
</script>

<template>
    <Link :href="`/${project.slug}`" class="group block">
        <article class="overflow-hidden">
            <div class="relative aspect-4/5 overflow-hidden bg-stone-100">
                <img
                    v-if="project.cover_image"
                    :src="project.cover_image"
                    :alt="project.title"
                    class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.02]"
                    loading="lazy"
                />
                <div
                    v-else
                    class="flex h-full items-center justify-center px-4 text-center text-sm uppercase tracking-[0.3em] text-stone-500"
                >
                    Sans image
                </div>
            </div>

            <div class="py-3">
                <h2 class="text-sm font-medium uppercase tracking-wide font-strichpunkt">
                    {{ project.title }}
                </h2>
            </div>
        </article>
    </Link>
</template>
