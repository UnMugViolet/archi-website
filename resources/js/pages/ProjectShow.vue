<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

type Category = {
    id: number;
    name: string;
    slug: string;
};

type ProjectImage = {
    id: number;
    path: string;
    alt_text: string | null;
    caption: string | null;
    is_thumbnail: boolean;
    display_order: number;
};

type ProjectSection = {
    id: number;
    title: string;
    section_type: string;
    content: string | null;
    display_order: number;
};

type ProjectMetric = {
    id: number;
    label: string;
    value: string;
    unit: string | null;
    display_order: number;
};

type AwardOrPublication = {
    id: number;
    entry_type: string;
    title: string;
    issuer_or_publisher: string | null;
    url: string | null;
    published_on: string | null;
    excerpt: string | null;
    display_order: number;
};

type Project = {
    id: number;
    title: string;
    slug: string;
    description: string | null;
    meta_title: string | null;
    meta_description: string | null;
    categories: Category[];
    images: ProjectImage[];
    sections: ProjectSection[];
    metrics: ProjectMetric[];
    awards_or_publication: AwardOrPublication[];
};

const { project } = defineProps<{
    project: Project;
}>();

const currentImageIndex = ref(0);

const carouselImages = computed(() => project.images ?? []);

const activeImage = computed(() => {
    return carouselImages.value[currentImageIndex.value] ?? null;
});

const showPreviousImage = (): void => {
    if (carouselImages.value.length === 0) {
        return;
    }

    currentImageIndex.value =
        (currentImageIndex.value - 1 + carouselImages.value.length) %
        carouselImages.value.length;
};

const showNextImage = (): void => {
    if (carouselImages.value.length === 0) {
        return;
    }

    currentImageIndex.value =
        (currentImageIndex.value + 1) % carouselImages.value.length;
};

const selectImage = (index: number): void => {
    currentImageIndex.value = index;
};
</script>

<template>
    <Head :title="project.meta_title || project.title" />

    <div class="min-h-screen bg-white text-stone-900">
        <main class="mx-auto w-full px-50 py-12">
            <Link href="/" class="mb-8 inline-block text-sm text-stone-600 hover:text-stone-900">
                Retour aux projets
            </Link>

            <section class="mb-12 grid gap-8  p-6 lg:grid-cols-2 lg:p-8">
                <div>
                    <h1 class="text-4xl font-semibold tracking-tight">{{ project.title }}</h1>
                    <p class="mt-4 max-w-2xl text-stone-600">{{ project.description }}</p>

                    <div v-if="project.categories.length" class="mt-5 flex flex-wrap gap-2">
                        <span
                            v-for="category in project.categories"
                            :key="category.id"
                            class="px-3 py-1 text-xs uppercase tracking-wide text-stone-600"
                        >
                            {{ category.name }}
                        </span>
                    </div>

                    <div v-if="project.metrics.length" class="mt-8 grid gap-3 sm:grid-cols-2">
                        <div
                            v-for="metric in project.metrics"
                            :key="metric.id"
                            class="border border-stone-200 p-4"
                        >
                            <p class="text-xs uppercase tracking-wide text-stone-500">{{ metric.label }}</p>
                            <p class="mt-2 text-lg font-semibold">
                                {{ metric.value }}
                                <span v-if="metric.unit" class="text-sm font-normal text-stone-500">{{ metric.unit }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div>
                    <figure class="overflow-hidden bg-stone-100">
                        <img
                            v-if="activeImage"
                            :src="activeImage.path"
                            :alt="activeImage.alt_text || project.title"
                            class="h-[28rem] w-full object-cover"
                        />
                        <div
                            v-else
                            class="flex h-[28rem] items-center justify-center text-sm text-stone-500"
                        >
                            Aucune image
                        </div>
                        <figcaption
                            v-if="activeImage && activeImage.caption"
                            class="border-t border-stone-200 bg-white p-3 text-sm text-stone-600"
                        >
                            {{ activeImage.caption }}
                        </figcaption>
                    </figure>

                    <div
                        v-if="carouselImages.length > 1"
                        class="mt-4 flex items-center justify-between gap-3"
                    >
                        <button
                            type="button"
                            class="px-3 py-2 text-sm text-stone-700 hover:bg-stone-100"
                            @click="showPreviousImage"
                        >
                            Precedent
                        </button>

                        <div class="flex items-center gap-2">
                            <button
                                v-for="(image, index) in carouselImages"
                                :key="image.id"
                                type="button"
                                class="rounded-full h-2.5 w-2.5"
                                :class="index === currentImageIndex ? 'bg-stone-900' : 'bg-stone-300'"
                                :aria-label="`Afficher image ${index + 1}`"
                                @click="selectImage(index)"
                            />
                        </div>

                        <button
                            type="button"
                            class="border border-stone-300 px-3 py-2 text-sm text-stone-700 hover:bg-stone-100"
                            @click="showNextImage"
                        >
                            Suivant
                        </button>
                    </div>
                </div>
            </section>

            <section v-if="project.sections.length" class="mb-10 space-y-8">
                <article v-for="section in project.sections" :key="section.id" class="bg-white p-6">
                    <h2 class="text-2xl font-medium">{{ section.title }}</h2>
                    <p class="mt-4 whitespace-pre-line leading-7 text-stone-700">{{ section.content }}</p>
                </article>
            </section>

            <section v-if="project.awards_or_publication.length" class="space-y-4">
                <h2 class="text-2xl font-medium">Prix et publications</h2>
                <article
                    v-for="entry in project.awards_or_publication"
                    :key="entry.id"
                    class="p-5"
                >
                    <p class="text-xs uppercase tracking-wide text-stone-500">{{ entry.entry_type }}</p>
                    <h3 class="mt-2 text-lg font-medium">{{ entry.title }}</h3>
                    <p v-if="entry.issuer_or_publisher" class="mt-1 text-sm text-stone-600">
                        {{ entry.issuer_or_publisher }}
                    </p>
                    <p v-if="entry.excerpt" class="mt-3 text-sm leading-6 text-stone-700">{{ entry.excerpt }}</p>
                    <a
                        v-if="entry.url"
                        :href="entry.url"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="mt-4 inline-block text-sm font-medium text-stone-900 underline"
                    >
                        Consulter
                    </a>
                </article>
            </section>
        </main>
    </div>
</template>
