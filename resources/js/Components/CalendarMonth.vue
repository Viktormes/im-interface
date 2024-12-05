<script setup>
import {computed, ref} from "vue";
import dayjs from "dayjs";

const props = defineProps({
    year: Number,
    month: Number,
    selectedSpan: {
        type: Object,
        default: null,
    },
    pick: String,
    weeks: Boolean,
});

const emit = defineEmits(['date-hovered', 'date-clicked'])

const monday = computed(() => dayjs().startOf('week'));
const isSameMonthAndYear = computed(() => dayjs().year() === props.year && dayjs().month() + 1 === props.month);
const today = computed(() => dayjs().date());

const startOfMonth = computed(() => dayjs(`${props.year}-${props.month}-1`));
const endOfMonth = computed(() => dayjs(startOfMonth.value).endOf('month'));
const daysInMonth = computed(() => startOfMonth.value.daysInMonth());
const daysInPrevMonth = computed(() => startOfMonth.value.subtract(1, 'month').daysInMonth());
const firstWeekDay = computed(() => startOfMonth.value.isoWeekday() - 1);
const prefixDays = computed(() => firstWeekDay.value + (firstWeekDay.value < 4 ? 7 : 0));
const totalDays = 42;

const selectedSpanOverlaps = computed(() => {
    if (!props.selectedSpan || !props.selectedSpan.from || !props.selectedSpan.to) return false;
    return props.selectedSpan.to >= startOfMonth.value && props.selectedSpan.from <= endOfMonth.value;
});

function classes(day, index) {
    const date = startOfMonth.value.add(day - 1, 'day');
    const isStart = selectedSpanOverlaps.value && date.format('YYYY-MM-DD') === props.selectedSpan.from.format('YYYY-MM-DD');
    const isEnd = selectedSpanOverlaps.value && date.format('YYYY-MM-DD') === props.selectedSpan.to.format('YYYY-MM-DD');
    const inSpan = selectedSpanOverlaps.value && date >= props.selectedSpan.from && date <= props.selectedSpan.to;

    return {
        'outline outline-1 outline-healthcare z-10': isSameMonthAndYear.value && day === today.value,
        'bg-healthcare-90': inSpan,
        'bg-healthcare-80': isStart || isEnd,
        '!bg-healthcare-70 !text-white': highlight.value !== null && (prefixDays.value + index) >= highlight.value.from && (prefixDays.value + index) <= highlight.value.to,
    };
}

const highlight = ref(null);

function onHoverDay(day, index) {
    emit('date-hovered', startOfMonth.value.add(day - 1, 'days'));

    let i = prefixDays.value + index;

    switch (props.pick) {
        case 'month':
            highlight.value = {
                from: 0,
                to: 41,
            };
            break;
        case 'week':
            highlight.value = {
                from: Math.floor(i / 7) * 7,
                to: Math.floor(i / 7) * 7 + 6,
            };
            break;
        default:
            highlight.value = {
                from: i,
                to: i,
            };
            break;
    }
}

function onLeaveDay() {
    highlight.value = null;
}
</script>

<template>
    <div
        :class="[weeks ? 'grid-cols-[1.5rem_1fr_1fr_1fr_1fr_1fr_1fr_1fr]' : 'grid-cols-7']"
        class="shrink-0 select-none bg-white grid text-xs p-1 w-64 h-48 border border-neutral-90 rounded overflow-hidden *:flex *:items-center *:justify-center"
    >
        <div v-if="weeks" class="text-neutral-70 border-r border-neutral-95">v</div>
        <div v-for="(_,i) in 7" :key="i"
             class="text-neutral-70">
            {{ monday.add(i, 'days').format('dd').substring(0, 1).toUpperCase() }}
        </div>

        <template v-for="(_,i) in totalDays" :key="i">
            <div v-if="weeks && (i % 7 === 0)" class="text-neutral-70 text-2xs border-r border-neutral-95">
                {{ startOfMonth.subtract(prefixDays, 'day').add(i, 'days').isoWeek() }}
            </div>

            <template v-if="i < prefixDays">
                <div class="text-neutral-70">
                    {{ daysInPrevMonth - prefixDays + i + 1 }}
                </div>
            </template>

            <template v-else-if="i < prefixDays + daysInMonth">
                <button :class="classes((i - prefixDays) + 1, i - prefixDays)"
                        class="cursor-pointer"
                        type="button"
                        @click="emit('date-clicked', startOfMonth.add((i - prefixDays), 'days'))"
                        @mouseenter="onHoverDay((i - prefixDays) + 1, i - prefixDays)" @mouseleave="onLeaveDay">
                    {{ (i - prefixDays) + 1 }}
                </button>
            </template>

            <template v-else>
                <div class="text-neutral-70">
                    {{ i - (daysInMonth + prefixDays) + 1 }}
                </div>
            </template>
        </template>
    </div>
</template>
