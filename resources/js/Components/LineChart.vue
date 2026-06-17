<script setup>
import { computed } from 'vue';
import { Line } from 'vue-chartjs';
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    LineElement,
    PointElement,
    LinearScale,
    CategoryScale,
    Filler,
} from 'chart.js';
import { useDarkMode } from '@/composables/useDarkMode';
import { formatCurrency } from '@/composables/useFormat';

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    LineElement,
    PointElement,
    LinearScale,
    CategoryScale,
    Filler,
);

const props = defineProps({
    data: Array,
});

const { isDark } = useDarkMode();

const chartData = computed(() => ({
    labels: props.data.map((item) => item.month),
    datasets: [
        {
            label: 'Revenus',
            data: props.data.map((item) => item.income),
            borderColor: '#10b981',
            backgroundColor: 'rgba(16, 185, 129, 0.12)',
            tension: 0.4,
            fill: true,
            pointRadius: 3,
            pointBackgroundColor: '#10b981',
        },
        {
            label: 'Dépenses',
            data: props.data.map((item) => item.expense),
            borderColor: '#f43f5e',
            backgroundColor: 'rgba(244, 63, 94, 0.10)',
            tension: 0.4,
            fill: true,
            pointRadius: 3,
            pointBackgroundColor: '#f43f5e',
        },
    ],
}));

const chartOptions = computed(() => {
    const tick = isDark.value ? '#94a3b8' : '#64748b';
    const grid = isDark.value
        ? 'rgba(148, 163, 184, 0.12)'
        : 'rgba(100, 116, 139, 0.10)';

    return {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    color: tick,
                    padding: 16,
                    usePointStyle: true,
                    pointStyle: 'circle',
                    font: { size: 12, family: 'Figtree' },
                },
            },
            tooltip: {
                callbacks: {
                    label: (context) =>
                        `${context.dataset.label}: ${formatCurrency(context.parsed.y)}`,
                },
            },
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    color: tick,
                    callback: (value) =>
                        new Intl.NumberFormat('fr-FR', {
                            notation: 'compact',
                            compactDisplay: 'short',
                        }).format(value),
                },
                grid: { color: grid },
                border: { display: false },
            },
            x: {
                ticks: { color: tick },
                grid: { display: false },
                border: { display: false },
            },
        },
    };
});
</script>

<template>
    <div class="relative h-64">
        <Line :data="chartData" :options="chartOptions" />
    </div>
</template>
