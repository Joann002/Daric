<template>
    <div class="relative h-64">
        <Line :data="chartData" :options="chartOptions" />
    </div>
</template>

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
} from 'chart.js';

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    LineElement,
    PointElement,
    LinearScale,
    CategoryScale
);

const props = defineProps({
    data: Array,
});

const chartData = computed(() => {
    return {
        labels: props.data.map(item => item.month),
        datasets: [
            {
                label: 'Revenus',
                data: props.data.map(item => item.income),
                borderColor: '#10B981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
            },
            {
                label: 'Dépenses',
                data: props.data.map(item => item.expense),
                borderColor: '#EF4444',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                tension: 0.4,
            },
        ],
    };
});

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'bottom',
            labels: {
                color: '#9CA3AF',
                padding: 15,
                font: {
                    size: 12,
                },
            },
        },
        tooltip: {
            callbacks: {
                label: function(context) {
                    const label = context.dataset.label || '';
                    const value = new Intl.NumberFormat('fr-FR', {
                        style: 'currency',
                        currency: 'XAF',
                        minimumFractionDigits: 0,
                    }).format(context.parsed.y);
                    return `${label}: ${value}`;
                },
            },
        },
    },
    scales: {
        y: {
            beginAtZero: true,
            ticks: {
                color: '#9CA3AF',
                callback: function(value) {
                    return new Intl.NumberFormat('fr-FR', {
                        notation: 'compact',
                        compactDisplay: 'short',
                    }).format(value);
                },
            },
            grid: {
                color: 'rgba(156, 163, 175, 0.1)',
            },
        },
        x: {
            ticks: {
                color: '#9CA3AF',
            },
            grid: {
                color: 'rgba(156, 163, 175, 0.1)',
            },
        },
    },
};
</script>
