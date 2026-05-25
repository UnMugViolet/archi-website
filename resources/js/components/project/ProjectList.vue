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
	<HomepageContainer>
		<table class="text-xs text-white uppercase py-10">
			<thead class="text-xs text-white uppercase">

			</thead>
			<tr>
				<th></th>
			</tr>
			<article v-for="project in props.projects" :key="project.id" class="w-full max-w-md">


				<div class="py-3">
					<h2 class="text-3xl font-medium uppercase tracking-wide font-strichpunkt">
						{{ project.title }}
					</h2>
				</div>
			</article>
		</table>
	</HomepageContainer>
</template>
