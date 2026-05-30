<script setup lang="ts">
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
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


const panX = ref(0);
const panY = ref(0);

const isDragging = ref(false);
const dragStartX = ref(0);
const dragStartY = ref(0);
const dragOriginX = ref(0);
const dragOriginY = ref(0);
const hasDragged = ref(false);

let rafId: number | null = null;

// Mobile detection
const isMobile = ref(false);
let mobileMediaQuery: MediaQueryList | null = null;

const syncIsMobile = (): void => {
	isMobile.value = mobileMediaQuery?.matches ?? false;
};

onMounted(() => {
	mobileMediaQuery = globalThis.matchMedia('(max-width: 639px)');
	syncIsMobile();
	if (mobileMediaQuery.addEventListener) {
		mobileMediaQuery.addEventListener('change', syncIsMobile);
	} else {
		mobileMediaQuery.addListener(syncIsMobile);
	}
});

onBeforeUnmount(() => {
	if (!mobileMediaQuery) return;
	if (mobileMediaQuery.removeEventListener) {
		mobileMediaQuery.removeEventListener('change', syncIsMobile);
	} else {
		mobileMediaQuery.removeListener(syncIsMobile);
	}
	if (rafId !== null) cancelAnimationFrame(rafId);
});

// Layout 
// Each slot: position as % of world width / rem from top, plus card width in rem.
// Used as a jitter-grid: projects placed once, no repetition.
const SLOTS = [
	{ top: 8,  left: 10, width: 18 },
	{ top: 18, left: 34, width: 19 },
	{ top: 10, left: 63, width: 24 },
	{ top: 26, left: 87, width: 26 },
	{ top: 40, left: 18, width: 20 },
	{ top: 48, left: 48, width: 18 },
	{ top: 62, left: 72, width: 19 },
	{ top: 74, left: 30, width: 17 },
];

// Seeded pseudo-random — same scatter on every render
function seeded(seed: number) {
	let s = seed;
	return () => { s = (s * 9301 + 49297) % 233280; return s / 233280; };
}

const floatingProjects = computed(() => {
	const rng = seeded(42);
	const cols = SLOTS.length;
	const horizontalSpreadFactor = isMobile.value ? 2.5 : 1;

	return props.projects.map((project, index) => {
		const slot = SLOTS[index % cols];
		const row = Math.floor(index / cols);

		// Jitter within the cell so cards don't stack in a rigid grid
		const jitterLeft = (rng() - 0.5) * 6;   // ±3% horizontal
		const jitterTop  = (rng() - 0.5) * 4;   // ±2rem vertical

		const rawLeft = slot.left + (row % 2 ? 6 : 0) + jitterLeft;
		const adjustedLeft = 50 + (rawLeft - 50) * horizontalSpreadFactor;
		const top = slot.top + row * 34 + jitterTop;

		return {
			...project,
			top:   `${top}rem`,
			left:  `${adjustedLeft}%`,
			width: `clamp(11rem, 24vw, ${slot.width}rem)`,
		};
	});
});

// World height grows with project count — no artificial cap
const worldHeight = computed(() => {
	const rows = Math.max(2, Math.ceil(props.projects.length / SLOTS.length));
	return `${rows * 42 + 24}rem`;
});

// Pointer handlers
const handlePointerDown = (event: PointerEvent): void => {
	const target = event.target as HTMLElement | null;
	if (target?.closest('a[href]')) return;

	if (rafId !== null) { cancelAnimationFrame(rafId); rafId = null; }

	isDragging.value = true;
	hasDragged.value = false;
	dragStartX.value = event.clientX;
	dragStartY.value = event.clientY;
	dragOriginX.value = panX.value;
	dragOriginY.value = panY.value;

	(event.currentTarget as HTMLElement | null)?.setPointerCapture(event.pointerId);
};

const handlePointerMove = (event: PointerEvent): void => {
	if (!isDragging.value) return;

	const deltaX = event.clientX - dragStartX.value;
	const deltaY = event.clientY - dragStartY.value;

	if (Math.abs(deltaX) > 4 || Math.abs(deltaY) > 4) {
		hasDragged.value = true;
	}

	panX.value = dragOriginX.value + deltaX;
	panY.value = dragOriginY.value + deltaY;
};

const handlePointerUp = (): void => {
	if (!isDragging.value) return;
	isDragging.value = false;
	// Release into momentum
	setTimeout(() => { hasDragged.value = false; }, 20);
};

// Wheel / trackpad
const handleWheel = (event: WheelEvent): void => {
	event.preventDefault();
	if (rafId !== null) { cancelAnimationFrame(rafId); rafId = null; }
	panX.value -= event.deltaX;
	panY.value -= event.deltaY;
};

// Touch (single-finger pan)
let touchStartX = 0;
let touchStartY = 0;
let touchOriginX = 0;
let touchOriginY = 0;
let isTouching = false;

const handleTouchStart = (event: TouchEvent): void => {
	const touch = event.touches[0];
	if (!touch) return;
	if (rafId !== null) { cancelAnimationFrame(rafId); rafId = null; }
	isTouching = true;
	touchStartX = touch.clientX;
	touchStartY = touch.clientY;
	touchOriginX = panX.value;
	touchOriginY = panY.value;
};

const handleTouchMove = (event: TouchEvent): void => {
	if (!isTouching) return;
	const touch = event.touches[0];
	if (!touch) return;
	event.preventDefault();

	panX.value = touchOriginX + (touch.clientX - touchStartX);
	panY.value = touchOriginY + (touch.clientY - touchStartY);
};

const handleTouchEnd = (): void => {
	isTouching = false;
};

// Click suppression after drag
const handleClickCapture = (event: MouseEvent): void => {
	if (!hasDragged.value) return;
	event.preventDefault();
	event.stopPropagation();
	hasDragged.value = false;
};
</script>

<template>
	<div
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
			class="relative w-full"
			:style="{
				minHeight: worldHeight,
				transform: `translate3d(${panX}px, ${panY}px, 0)`,
				transformOrigin: 'center top',
				willChange: 'transform',
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
					transform: 'translateX(-50%)',
					transformOrigin: 'center top',
				}"
			>
				<Thumbnail :project="project" />
			</div>
		</div>
	</div>
</template>
