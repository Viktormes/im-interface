<script setup>
import {computed, ref} from "vue";
import dayjs from "dayjs";

const props = defineProps({
    year: Number,
    selectedSpan: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(['date-hovered', 'date-clicked']);

const startOfYear = computed(() => dayjs(`${props.year}-1-1`).startOf('year'));
const endOfYear = computed(() => dayjs(startOfYear.value).endOf('year'));

const isSameYear = computed(() => dayjs().year() === props.year);
const today = computed(() => dayjs().month() + 1);

const startOfMonth = computed(() => dayjs(`${props.year}-${props.month}-1`));
const endOfMonth = computed(() => dayjs(startOfMonth.value).endOf('month'));
const daysInMonth = computed(() => startOfMonth.value.daysInMonth());
const daysInPrevMonth = computed(() => startOfMonth.value.subtract(1, 'month').daysInMonth());
const firstWeekDay = computed(() => startOfMonth.value.isoWeekday() - 1);
const prefixDays = computed(() => firstWeekDay.value + (firstWeekDay.value < 4 ? 7 : 0));
const totalDays = 42;

const selectedSpanOverlaps = computed(() => {
    if (!props.selectedSpan || !props.selectedSpan.from || !props.selectedSpan.to) return false;
    return props.selectedSpan.to >= startOfYear.value && props.selectedSpan.from <= endOfYear.value;
});

function classes(month, index) {
    const date = startOfYear.value.add(month - 1, 'months');
    const isStart = selectedSpanOverlaps.value && date.format('YYYY-MM') === props.selectedSpan.from.format('YYYY-MM');
    const isEnd = selectedSpanOverlaps.value && date.format('YYYY-MM') === props.selectedSpan.to.format('YYYY-MM');
    const inSpan = selectedSpanOverlaps.value && date >= props.selectedSpan.from && date <= props.selectedSpan.to;

    return {
        'outline outline-1 outline-healthcare z-10': isSameYear.value && month === today.value,
        'bg-healthcare-90': inSpan,
        'bg-healthcare-80': isStart || isEnd,
        '!bg-healthcare-70 !text-white': highlight.value !== null && index === highlight.value,
    };
}

const highlight = ref(null);

function onHoverMonth(month, index) {
    emit('date-hovered', startOfYear.value.add(month - 1, 'months'));

    highlight.value = index;
}

function onLeaveMonth() {
    highlight.value = null;
}
</script>

<template>
    <div
        class="shrink-0 select-none bg-white grid grid-cols-4 grid-rows-3 text-xs p-1 w-64 h-48 border border-neutral-90 rounded overflow-hidden *:flex *:items-center *:justify-center"
    >
        <button v-for="(month, i) in 12"
                :class="classes(month, i)"
                class="cursor-pointer"
                type="button"
                @click="emit('date-clicked', startOfYear.add(i, 'months'))"
                @mouseenter="onHoverMonth(month, i)" @mouseleave="onLeaveMonth"
        >
            {{ startOfYear.add(i, 'month').format('MMM') }}
        </button>
    </div>
</template>
