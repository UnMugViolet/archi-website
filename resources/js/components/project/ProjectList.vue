<script setup lang="ts">
import { ref } from 'vue';
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

</script>

<template>
	<HomepageContainer class="pt-20 px-4">
		<div class="flex w-full gap-12">
			<table class="w-7/12 border-separate border-spacing-y-3 text-sm uppercase text-black">
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
				</tbody>
			</table>
	
			 <!-- Image preview on hover, to be implemented later -->
			<div class="w-5/12 pt-10 max-h-[400px] ">
				<img
					v-if="hoveredProjectId !== null && props.projects.find((project) => project.id === hoveredProjectId)?.cover_image"
					:src="props.projects.find((project) => project.id === hoveredProjectId)?.cover_image"
					alt="Project cover"
					class=" w-full object-cover"
				>
			</div>
		</div>
	</HomepageContainer>
</template>
