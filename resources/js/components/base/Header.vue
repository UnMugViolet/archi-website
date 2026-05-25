<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

</script>

<template>
	<header class="flex justify-between text-black pt-2 font-strichpunkt border-b px-4 py-3">
		<div class="mx-auto w-full">
			<Link href="/">
			<h1 class="text-2xl font-semibold tracking-tight">
				Archi website
			</h1>
		</Link>
		</div>

		<nav class="text-black">
			<ul class="flex space-x-4 lg:space-x-10 text-sm font-medium">
				<Link href="/agence">
					<li class="text-lg hover:underline">
						Agence
					</li>
				</Link>
				<Link href="/contact">
					<li class="text-lg hover:underline">
						Contact
					</li>
				</Link>
			</ul>
		</nav>
	</header>
</template>
