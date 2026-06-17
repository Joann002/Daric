<script setup>
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import frLocale from '@fullcalendar/core/locales/fr';
import { formatCurrency } from '@/composables/useFormat';

const props = defineProps({
    events: { type: Array, default: () => [] },
    initialDate: { type: String, default: null },
});

const calendarOptions = computed(() => ({
    plugins: [dayGridPlugin, interactionPlugin],
    initialView: 'dayGridMonth',
    initialDate: props.initialDate ? `${props.initialDate}-01` : undefined,
    locale: frLocale,
    height: 'auto',
    headerToolbar: { left: 'title', center: '', right: 'prev,next today' },
    events: props.events.map((e) => ({
        id: String(e.id),
        title: `${e.type === 'income' ? '+' : '−'}${formatCurrency(e.amount)} · ${e.title}`,
        start: e.start,
        backgroundColor: e.type === 'income' ? '#10b981' : '#f43f5e',
        borderColor: 'transparent',
        textColor: '#ffffff',
    })),
    eventClick: (info) => {
        router.visit(route('transactions.show', info.event.id));
    },
}));
</script>

<template>
    <FullCalendar class="daric-calendar" :options="calendarOptions" />
</template>
