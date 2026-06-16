<template>
    <div class="relative h-64">
        <Pie :data="chartData" :options="chartOptions" />
    </div>
</template>

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

ChartJS.register(Title, Tooltip, Legend, ArcElement, CategoryScale);

const props = defineProps({
    data: Array,
});

const chartData = computed(() => {
    return {
        labels: props.data.map(item => item.name),
        datasets: [
            {
                data: props.data.map(item => item.total),
                backgroundColor: props.data.map(item => item.color),
                borderWidth: 2,
                borderColor: '#ffffff',
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
                    const label = context.label || '';
                    const value = new Intl.NumberFormat('fr-FR', {
                        style: 'currency',
                        currency: 'XAF',
                        minimumFractionDigits: 0,
                    }).format(context.parsed);
                    return `${label}: ${value}`;
                },
            },
        },
    },
};
</script>
