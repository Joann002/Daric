<script setup>
import sparkline from '@fnando/sparkline';
import { ref, onMounted, watch, nextTick } from 'vue';

const props = defineProps({
    data: { type: Array, default: () => [] },
    width: { type: Number, default: 120 },
    height: { type: Number, default: 36 },
    color: { type: String, default: '#10b981' },
});

const el = ref(null);

async function draw() {
    await nextTick();
    if (!el.value) return;
    const values = (props.data || [])
        .map(Number)
        .filter((n) => Number.isFinite(n));
    el.value.innerHTML = '';
    if (values.length === 0) return;

    const min = Math.min(...values);
    const max = Math.max(...values);

    // @fnando/sparkline divides by (max - min); a flat series would produce
    // NaN coordinates, so draw a centered flat line ourselves instead.
    if (values.length < 2 || min === max) {
        const y = props.height / 2;
        el.value.innerHTML = `<path class="sparkline--line" d="M2 ${y} L ${props.width - 2} ${y}" />`;
        return;
    }

    sparkline(el.value, values, { interactive: false });
}

onMounted(draw);
watch(() => props.data, draw, { deep: true });
</script>

<template>
    <svg
        ref="el"
        :width="width"
        :height="height"
        stroke-width="2"
        stroke="currentColor"
        fill="none"
        class="sparkline"
        :style="{ color }"
        preserveAspectRatio="none"
    />
</template>
