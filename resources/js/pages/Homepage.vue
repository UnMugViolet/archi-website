<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { onMounted, ref, watch } from 'vue';
import Header from '@/components/base/Header.vue';
import HomepageContainer from '@/components/container/HomepageContainer.vue';
import ProjectGallery from '@/components/project/ProjectGallery.vue';
import ProjectList from '@/components/project/ProjectList.vue';

type ProjectListItem = {
    id: number;
    title: string;
    slug: string;
    date: string | null;
    category: string | null;
    location: string | null;
    cover_image: string | null;
};

const { projects } = defineProps<{
    projects: ProjectListItem[];
}>();

const currentView = ref<'gallery' | 'list'>('gallery');

const handleViewChange = (view: 'gallery' | 'list'): void => {
	currentView.value = view;
};

onMounted(() => {
    const queryView = new URLSearchParams(globalThis.location.search).get('view');

    if (queryView === 'list') {
        currentView.value = 'list';
    }
});

watch(
	() => currentView.value,
	(newView) => {
        const url = new URL(globalThis.location.href);

        if (newView === 'list') {
            url.searchParams.set('view', 'list');
        } else {
            url.searchParams.delete('view');
        }

        const nextUrl = `${url.pathname}${url.search}${url.hash}`;
        globalThis.history.replaceState(null, '', nextUrl);
	}
);
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
                <ProjectGallery v-if="currentView === 'gallery'" :projects="projects" />
                <ProjectList v-else :projects="projects" />
				<div class="absolute left-[50%] top-12 transform -translate-x-1/2  flex space-x-4 z-10">
                    <p @click="handleViewChange('gallery')" :class="currentView === 'gallery' ? 'underline' : 'cursor-pointer'">Gallerie</p>
                    <p @click="handleViewChange('list')" :class="currentView === 'list' ? 'underline ' : 'cursor-pointer'">Liste</p>
				</div>
            </div>
        </div>
    </HomepageContainer>
</template>
