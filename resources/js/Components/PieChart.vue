<script setup>
import { computed } from 'vue';
import { Pie } from 'vue-chartjs';
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    ArcElement,
    CategoryScale,
} from 'chart.js';
import { useDarkMode } from '@/composables/useDarkMode';
import { formatCurrency } from '@/composables/useFormat';

ChartJS.register(Title, Tooltip, Legend, ArcElement, CategoryScale);

const props = defineProps({
    data: Array,
});

const { isDark } = useDarkMode();

const chartData = computed(() => ({
    labels: props.data.map((item) => item.name),
    datasets: [
        {
            data: props.data.map((item) => item.total),
            backgroundColor: props.data.map((item) => item.color),
            borderWidth: 3,
            borderColor: isDark.value ? '#0f172a' : '#ffffff',
            hoverOffset: 6,
        },
    ],
}));

const chartOptions = computed(() => ({
    responsive: true,
    maintainAspectRatio: false,
    cutout: '60%',
    plugins: {
        legend: {
            position: 'bottom',
            labels: {
                color: isDark.value ? '#94a3b8' : '#64748b',
                padding: 16,
                usePointStyle: true,
                pointStyle: 'circle',
                font: { size: 12, family: 'Figtree' },
            },
        },
        tooltip: {
            callbacks: {
                label: (context) =>
                    `${context.label}: ${formatCurrency(context.parsed)}`,
            },
        },
    },
}));
</script>

<template>
    <div class="relative h-64">
        <Pie :data="chartData" :options="chartOptions" />
    </div>
</template>
