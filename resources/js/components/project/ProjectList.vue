<script setup lang="ts">
import HomepageContainer from '../container/HomepageContainer.vue';

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
	<HomepageContainer class="pt-20">
		<table class="text-xs text-black uppercase w-7/12">
			<thead class="text-xs uppercase">
				<tr>
					<th class="w-1/6">Années</th>
					<th>Projets</th>
					<th>Catégories</th>
					<th>Lieu</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="project in props.projects" :key="project.id">
					<td class="w-1/6">{{ project.date }}</td>
					<td class="w-1/2">{{ project.title }}</td>
					<td class="w-1/6">{{ project.categories }}</td>
					<td class="w-1/6">{{ project.location }}</td>
				</tr>
			</tbody>
		</table>

		 <!-- Image preview on hover, to be implemented later -->
		<div class="w-5/12"">

		</div>
	</HomepageContainer>
</template>
