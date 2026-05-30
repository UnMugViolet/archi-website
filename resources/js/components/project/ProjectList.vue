<script setup lang="ts">
import { computed, ref } from 'vue';
import HomepageContainer from '../container/HomepageContainer.vue';

type ProjectListItem = {
	id: number;
	title: string;
	slug: string;
	date: string | null;
	category: string | null;
	location: string | null;
	cover_image: string | null;
};

const props = defineProps<{
	projects: ProjectListItem[];
}>();

const hoveredProjectId = ref<number | null>(null);

const handleMouseEnter = (projectId: number): void => {
	hoveredProjectId.value = projectId;
};

const hoveredProject = computed<ProjectListItem | null>(() => {
	if (hoveredProjectId.value === null) {
		return null;
	}

	return props.projects.find((project) => project.id === hoveredProjectId.value) ?? null;
});

const hoveredCoverImage = computed<string | null>(() => hoveredProject.value?.cover_image ?? null);

</script>

<template>
	<HomepageContainer class="pt-20 px-4 h-[100svh] flex flex-col">
		<div class="flex w-full gap-12 mt-10 flex-1 min-h-0">
			<div class="w-full md:w-7/12 max-h-[100svh] overflow-y-auto overflow-x-hidden min-h-0">
					<table class="w-full border-separate border-spacing-y-3 text-sm uppercase text-black overflow-y-scroll">
					<thead class="text-sm uppercase">
						<tr>
							<th class="text-left">Année</th>
							<th class="text-left">Projets</th>
							<th class="text-left">Catégorie</th>
							<th class="text-left">Lieu</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="project in props.projects" :key="project.id"
							@mouseenter="handleMouseEnter(project.id)">
							<td class="w-1/6 py-2 align-top">{{ project.date ?? '-' }}</td>
							<td class="w-1/2 py-2 align-top">{{ project.title }}</td>
							<td class="w-1/6 py-2 align-top">{{ project.category ?? '-' }}</td>
							<td class="w-1/6 py-2 align-top">{{ project.location ?? '-' }}</td>
						</tr>
												<tr v-for="project in props.projects" :key="project.id"
							@mouseenter="handleMouseEnter(project.id)">
							<td class="w-1/6 py-2 align-top">{{ project.date ?? '-' }}</td>
							<td class="w-1/2 py-2 align-top">{{ project.title }}</td>
							<td class="w-1/6 py-2 align-top">{{ project.category ?? '-' }}</td>
							<td class="w-1/6 py-2 align-top">{{ project.location ?? '-' }}</td>
						</tr>
												<tr v-for="project in props.projects" :key="project.id"
							@mouseenter="handleMouseEnter(project.id)">
							<td class="w-1/6 py-2 align-top">{{ project.date ?? '-' }}</td>
							<td class="w-1/2 py-2 align-top">{{ project.title }}</td>
							<td class="w-1/6 py-2 align-top">{{ project.category ?? '-' }}</td>
							<td class="w-1/6 py-2 align-top">{{ project.location ?? '-' }}</td>
						</tr>
												<tr v-for="project in props.projects" :key="project.id"
							@mouseenter="handleMouseEnter(project.id)">
							<td class="w-1/6 py-2 align-top">{{ project.date ?? '-' }}</td>
							<td class="w-1/2 py-2 align-top">{{ project.title }}</td>
							<td class="w-1/6 py-2 align-top">{{ project.category ?? '-' }}</td>
							<td class="w-1/6 py-2 align-top">{{ project.location ?? '-' }}</td>
						</tr>
					</tbody>
				</table>
			</div>
	

			<!-- Image preview on hover, to be implemented later -->
			<div class="hidden md:flex w-5/12 h-[100svh]	 items-center justify-center overflow-hidden">
				<img
					v-if="hoveredCoverImage"
					:src="hoveredCoverImage"
					alt="Project cover"
					class="mx-auto max-h-[400px] w-auto max-w-full object-contain"
				>
			</div>
		</div>
	</HomepageContainer>
</template>
