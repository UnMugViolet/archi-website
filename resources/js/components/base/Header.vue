<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

</script>

<template>
	<header class="absolute top-0 left-0 w-full flex justify-between text-black pt-2 bg-transparent border-b px-4 py-3 z-2">
		<div class="mx-auto w-full">
			<Link href="/">
			<h1 class="text-2xl tracking-tight">
				Facta
			</h1>
		</Link>
		</div>

		<nav class="text-black">
			<ul class="flex space-x-4 lg:space-x-5 text-sm<">
				<Link href="/agence">
					<li class="text-lg hover:underline">
						Agence
					</li>
				</Link>
			</ul>
		</nav>
	</header>
</template>
