<script setup>
import { computed } from 'vue';
import VueApexCharts from 'vue3-apexcharts';
import { useDarkMode } from '@/composables/useDarkMode';
import { formatCurrency } from '@/composables/useFormat';

const props = defineProps({
    data: Array,
});

const { isDark } = useDarkMode();

const series = computed(() => props.data.map((d) => Number(d.total)));

const options = computed(() => ({
    chart: {
        type: 'donut',
        fontFamily: 'Figtree, sans-serif',
        foreColor: isDark.value ? '#94a3b8' : '#64748b',
    },
    labels: props.data.map((d) => d.name),
    colors: props.data.map((d) => d.color),
    legend: { position: 'bottom' },
    dataLabels: { enabled: false },
    stroke: { width: 2, colors: [isDark.value ? '#0f172a' : '#ffffff'] },
    plotOptions: { pie: { donut: { size: '65%' } } },
    tooltip: { y: { formatter: (v) => formatCurrency(v) } },
}));
</script>

<template>
    <VueApexCharts
        type="donut"
        height="280"
        :options="options"
        :series="series"
    />
</template>
