<script setup>
import { computed, ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import frLocale from '@fullcalendar/core/locales/fr';
import Modal from '@/Components/Modal.vue';
import Icon from '@/Components/Icon.vue';
import EmptyState from '@/Components/ui/EmptyState.vue';
import { formatCurrency } from '@/composables/useFormat';

const props = defineProps({
    events: { type: Array, default: () => [] },
    initialDate: { type: String, default: null },
});

const showModal = ref(false);
const selectedDate = ref(null);

const dayEvents = computed(() =>
    selectedDate.value
        ? props.events.filter((e) => e.start === selectedDate.value)
        : [],
);

const dayTotal = computed(() =>
    dayEvents.value.reduce(
        (sum, e) => sum + (e.type === 'income' ? e.amount : -e.amount),
        0,
    ),
);

const selectedDateLabel = computed(() => {
    if (!selectedDate.value) return '';
    return new Intl.DateTimeFormat('fr-FR', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    }).format(new Date(`${selectedDate.value}T00:00:00`));
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
        info.jsEvent.preventDefault();
        openDay(info.event.startStr);
    },
    dateClick: (info) => openDay(info.dateStr),
}));

function openDay(dateStr) {
    selectedDate.value = dateStr;
    showModal.value = true;
}

function goToTransaction(id) {
    router.visit(route('transactions.show', id));
}
</script>

<template>
    <div>
        <FullCalendar class="daric-calendar" :options="calendarOptions" />

        <Modal :show="showModal" max-width="lg" @close="showModal = false">
            <div class="p-5 sm:p-6">
                <div class="mb-4 flex items-start justify-between gap-3">
                    <div>
                        <h3
                            class="text-base font-semibold capitalize text-slate-900 dark:text-white"
                        >
                            {{ selectedDateLabel }}
                        </h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            {{ dayEvents.length }} transaction(s)
                        </p>
                    </div>
                    <button
                        class="btn-ghost !p-2"
                        @click="showModal = false"
                        aria-label="Fermer"
                    >
                        <Icon name="close" class="h-5 w-5" />
                    </button>
                </div>

                <ul
                    v-if="dayEvents.length"
                    class="divide-y divide-slate-100 dark:divide-slate-800"
                >
                    <li
                        v-for="event in dayEvents"
                        :key="event.id"
                        class="flex cursor-pointer items-center justify-between gap-3 rounded-xl px-2 py-3 transition hover:bg-slate-50 dark:hover:bg-slate-800/50"
                        @click="goToTransaction(event.id)"
                    >
                        <div class="flex min-w-0 items-center gap-3">
                            <span
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full"
                                :class="
                                    event.type === 'income'
                                        ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400'
                                        : 'bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400'
                                "
                            >
                                <Icon
                                    :name="
                                        event.type === 'income'
                                            ? 'incoming'
                                            : 'outgoing'
                                    "
                                />
                            </span>
                            <span
                                class="truncate text-sm font-medium text-slate-900 dark:text-white"
                            >
                                {{ event.title }}
                            </span>
                        </div>
                        <span
                            class="tnum shrink-0 text-sm font-bold"
                            :class="
                                event.type === 'income'
                                    ? 'text-emerald-600 dark:text-emerald-400'
                                    : 'text-rose-600 dark:text-rose-400'
                            "
                        >
                            {{ event.type === 'income' ? '+' : '−'
                            }}{{ formatCurrency(event.amount) }}
                        </span>
                    </li>
                </ul>

                <EmptyState
                    v-else
                    icon="transactions"
                    title="Aucune transaction"
                    description="Aucune opération enregistrée ce jour."
                />

                <div
                    class="mt-4 flex items-center justify-between border-t border-slate-200/70 pt-4 dark:border-slate-800"
                >
                    <div v-if="dayEvents.length" class="text-sm">
                        <span class="text-slate-500 dark:text-slate-400">
                            Solde du jour :
                        </span>
                        <span
                            class="tnum font-bold"
                            :class="
                                dayTotal >= 0
                                    ? 'text-emerald-600 dark:text-emerald-400'
                                    : 'text-rose-600 dark:text-rose-400'
                            "
                        >
                            {{ dayTotal >= 0 ? '+' : '−'
                            }}{{ formatCurrency(Math.abs(dayTotal)) }}
                        </span>
                    </div>
                    <span v-else />

                    <Link
                        :href="route('transactions.create', { date: selectedDate })"
                        class="btn-primary !px-3 !py-1.5 text-xs"
                    >
                        <Icon name="plus" class="h-4 w-4" />
                        Ajouter ce jour
                    </Link>
                </div>
            </div>
        </Modal>
    </div>
</template>
