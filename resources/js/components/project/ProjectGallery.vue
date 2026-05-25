<script setup lang="ts">
import { computed, ref } from 'vue';
import Thumbnail from '@/components/project/Thumbnail.vue';

type ProjectListItem = {
	id: number;
	title: string;
	slug: string;
	cover_image: string | null;
};

const props = defineProps<{
	projects: ProjectListItem[];
}>();

const viewport = ref<HTMLElement | null>(null);
const panX = ref(0);
const panY = ref(0);
const spread = ref(1);
const spreadMin = 1;
const spreadMax = 1.5;
const scale = ref(1);
const scaleMin = 0.8;
const scaleMax = 1.25;
const isDragging = ref(false);
const dragStartX = ref(0);
const dragStartY = ref(0);
const dragOriginX = ref(0);
const dragOriginY = ref(0);
const hasDragged = ref(false);
const lastTouchX = ref(0);
const lastTouchY = ref(0);
const isTouching = ref(false);

const cardPositions = [
	{ top: 8, left: 10, width: 18 },
	{ top: 18, left: 34, width: 19 },
	{ top: 10, left: 63, width: 18 },
	{ top: 26, left: 82, width: 17 },
	{ top: 40, left: 18, width: 20 },
	{ top: 48, left: 48, width: 18 },
	{ top: 62, left: 72, width: 19 },
	{ top: 74, left: 30, width: 17 },
];

const floatingProjects = computed(() => {
	return props.projects.map((project, index) => {
		const position = cardPositions[index % cardPositions.length];
		const row = Math.floor(index / cardPositions.length);
		const spreadFactor = spread.value;
		const horizontalSpread = 1 + (spreadFactor - 1) * 0.2;

		return {
			...project,
			top: `${(position.top + row * 34) * spreadFactor}rem`,
			left: `${50 + ((position.left + (row % 2 ? 6 : 0)) - 50) * horizontalSpread}%`,
			width: `clamp(11rem, 24vw, ${position.width}rem)`,
		};
	});
});

const sceneHeight = computed(() => {
	const rows = Math.max(2, Math.ceil(props.projects.length / cardPositions.length));

	return `${(rows * 42 + 24) * spread.value}rem`;
});


const handleWheel = (event: WheelEvent): void => {
	event.preventDefault();
};

const handleTouchStart = (event: TouchEvent): void => {
	const touch = event.touches[0];

	if (!touch) {
		return;
	}

	isTouching.value = true;
	lastTouchX.value = touch.clientX;
	lastTouchY.value = touch.clientY;
};

const handleTouchMove = (event: TouchEvent): void => {
	if (!isTouching.value) {
		return;
	}

	const container = viewport.value;
	const touch = event.touches[0];

	if (!container || !touch) {
		return;
	}

	event.preventDefault();
	lastTouchX.value = touch.clientX;
	lastTouchY.value = touch.clientY;
};

const handleTouchEnd = (): void => {
	isTouching.value = false;
};

const handlePointerDown = (event: PointerEvent): void => {
	const target = event.target as HTMLElement | null;

	if (target?.closest('a[href]')) {
		return;
	}

	isDragging.value = true;
	hasDragged.value = false;
	dragStartX.value = event.clientX;
	dragStartY.value = event.clientY;
	dragOriginX.value = panX.value;
	dragOriginY.value = panY.value;

	(event.currentTarget as HTMLElement | null)?.setPointerCapture(event.pointerId);
};

const handlePointerMove = (event: PointerEvent): void => {
	if (!isDragging.value) {
		return;
	}

	const deltaX = event.clientX - dragStartX.value;
	const deltaY = event.clientY - dragStartY.value;

	if (Math.abs(deltaX) > 4 || Math.abs(deltaY) > 4) {
		hasDragged.value = true;
	}

	panX.value = dragOriginX.value + deltaX;
	panY.value = dragOriginY.value + deltaY;
};

const handlePointerUp = (event: PointerEvent): void => {
	isDragging.value = false;
};

const handleClickCapture = (event: MouseEvent): void => {
	if (!hasDragged.value) {
		return;
	}

	event.preventDefault();
	event.stopPropagation();
	hasDragged.value = false;
};
</script>

<template>
	<div
		ref="viewport"
		class="relative h-full overflow-hidden bg-stone-50 text-stone-900 overscroll-contain select-none cursor-grab active:cursor-grabbing"
		@wheel.prevent="handleWheel"
		@touchstart.passive="handleTouchStart"
		@touchmove="handleTouchMove"
		@touchend="handleTouchEnd"
		@touchcancel="handleTouchEnd"
		@pointerdown="handlePointerDown"
		@pointermove="handlePointerMove"
		@pointerup="handlePointerUp"
		@pointercancel="handlePointerUp"
		@click.capture="handleClickCapture"
	>
		<div
			class="relative mx-auto w-full px-4 py-6 sm:px-6 lg:px-8"
			:style="{
				minHeight: sceneHeight,
				minWidth: '100%',
				transform: `translate3d(${panX}px, ${panY}px, 0)`,
				transformOrigin: 'center top',
			}"
		>
			<div
				v-for="project in floatingProjects"
				:key="project.id"
				class="group absolute block"
				:style="{
					top: project.top,
					left: project.left,
					width: project.width,
					transform: `translateX(-50%) scale(${scale})`,
					transformOrigin: 'center top',
				}"
			>
				<Thumbnail :project="project" />
			</div>
		</div>
	</div>
</template>
