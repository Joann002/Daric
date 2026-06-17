<script setup>
import { computed } from 'vue';
import VueApexCharts from 'vue3-apexcharts';
import { useDarkMode } from '@/composables/useDarkMode';
import { formatCurrency } from '@/composables/useFormat';

const props = defineProps({
    data: Array,
});

const { isDark } = useDarkMode();

const series = computed(() => [
    { name: 'Revenus', data: props.data.map((d) => d.income) },
    { name: 'Dépenses', data: props.data.map((d) => d.expense) },
]);

const options = computed(() => ({
    chart: {
        type: 'area',
        fontFamily: 'Figtree, sans-serif',
        foreColor: isDark.value ? '#94a3b8' : '#64748b',
        toolbar: { show: false },
        zoom: { enabled: false },
    },
    colors: ['#10b981', '#f43f5e'],
    dataLabels: { enabled: false },
    stroke: { curve: 'smooth', width: 2.5 },
    fill: {
        type: 'gradient',
        gradient: { shadeIntensity: 1, opacityFrom: 0.3, opacityTo: 0.02 },
    },
    grid: {
        borderColor: isDark.value
            ? 'rgba(148,163,184,0.12)'
            : 'rgba(100,116,139,0.12)',
        strokeDashArray: 4,
    },
    legend: { position: 'bottom' },
    xaxis: {
        categories: props.data.map((d) => d.month),
        axisBorder: { show: false },
        axisTicks: { show: false },
    },
    yaxis: {
        labels: {
            formatter: (v) =>
                new Intl.NumberFormat('fr-FR', {
                    notation: 'compact',
                    compactDisplay: 'short',
                }).format(v),
        },
    },
    tooltip: { y: { formatter: (v) => formatCurrency(v) } },
}));
</script>

<template>
    <VueApexCharts
        type="area"
        height="280"
        :options="options"
        :series="series"
    />
</template>
