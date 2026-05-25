<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Header from '@/components/base/Header.vue';
import HomepageContainer from '@/components/container/HomepageContainer.vue';
import ProjectGallery from '@/components/project/ProjectGallery.vue';

type ProjectListItem = {
    id: number;
    title: string;
    slug: string;
    cover_image: string | null;
};

const { projects } = defineProps<{
    projects: ProjectListItem[];
}>();
</script>

<template>
    <Head title="Archi website">
        <title>Archi website</title>
        <meta head-key="title" name="title" content="Archi website - Projets d'architecture à découvrir" />
        <meta head-key="description" name="description" content="Agence d'architecture basée à Paris, spécialisée dans la conception de projets innovants et durables." />
    </Head>

    <HomepageContainer>
        <div class="flex h-svh flex-col overflow-hidden">
            <Header />

            <div class="min-h-0 flex-1">
                <ProjectGallery :projects="projects" />
            </div>
        </div>
    </HomepageContainer>
</template>
