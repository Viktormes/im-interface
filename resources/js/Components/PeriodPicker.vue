<script setup>
import IconCalendar from "@/Icons/IconCalendar.vue";
import IconChevronDown from "@/Icons/IconChevronDown.vue";
import {computed, reactive, ref} from "vue";
import CalendarMonth from "@/Components/CalendarMonth.vue";
import dayjs from "dayjs";
import Btn from "@/Components/Btn.vue";
import CalendarYear from "@/Components/CalendarYear.vue";
import Modal from "@/Components/Modal.vue";

const props = defineProps({
    start: Object,
    end: Object,
    accuracy: String,
});

const emit = defineEmits(['apply']);

const currentDate = dayjs().subtract(1, 'month');
const currentYear = ref(currentDate.year());
const currentMonth = ref(currentDate.month() + 1);
const prevMonth = computed(() => ((currentMonth.value - 1) + 11) % 12 + 1);
const prevYear = computed(() => currentMonth.value === 1 ? currentYear.value - 1 : currentYear.value);
const nextMonth = computed(() => ((currentMonth.value - 1) + 1) % 12 + 1);
const nextYear = computed(() => currentMonth.value === 12 ? currentYear.value + 1 : currentYear.value);

const from = ref(props.start || dayjs().startOf('day').subtract(6, 'days'));
const to = ref(props.end || dayjs().endOf('day'));
const newAccuracy = ref(props.accuracy || 'day');
const selectedSpan = reactive({
    from: dayjs(from.value),
    to: dayjs(to.value),
});

const showPeriodPicker = ref(false);
const spanText = computed(() => 'Dagligt genomsnitt' + ((v) => {
    switch (v) {
        case 'week':
            return ' / vecka';
        case 'month':
            return ' / månad';
        default:
            return '';
    }
})(props.accuracy));

const rangeText = function (from, to, acc) {
    switch (acc) {
        case 'month':
            return from.format('MMM YYYY') + ' \u00a0—\u00a0 ' + (to || from).format('MMM YYYY');
        case 'week':
            return 'v' + from.isoWeek() + ' ' + from.format('YYYY') + ' \u00a0—\u00a0 v' + (to || from).isoWeek() + ' ' + (to || from).format('YYYY');
        default:
            return from.format('D/M') + ' \u00a0—\u00a0 ' + (to || from).format('D/M');
    }
}

function clampSpan() {
    if (selectedSpan.from > selectedSpan.to) {
        [selectedSpan.from, selectedSpan.to] = [selectedSpan.to, selectedSpan.from];
    }

    switch (newAccuracy.value) {
        case 'month':
            selectedSpan.from = selectedSpan.from.startOf('month');
            selectedSpan.to = selectedSpan.to.endOf('month');
            break;
        case 'week':
            selectedSpan.from = selectedSpan.from.startOf('week');
            selectedSpan.to = selectedSpan.to.endOf('week');
            break;
    }
}

function clampFromTo() {
    if (to.value !== null && from.value > to.value) {
        [from.value, to.value] = [to.value, from.value];
    }

    switch (newAccuracy.value) {
        case 'month':
            from.value = from.value.startOf('month');
            if (to.value) to.value = to.value.endOf('month');
            break;
        case 'week':
            from.value = from.value.startOf('week');
            if (to.value) to.value = to.value.endOf('week');
            break;
    }

    [selectedSpan.from, selectedSpan.to] = [dayjs(from.value), dayjs(to.value || from.value)];
    clampSpan();
}

function decMonth() {
    currentMonth.value--;
    if (currentMonth.value < 1) {
        currentMonth.value = 12;
        currentYear.value--;
    }
}

function incMonth() {
    currentMonth.value++;
    if (currentMonth.value > 12) {
        currentMonth.value = 1;
        currentYear.value++;
    }
}

function onDateHovered(date) {
    if (to.value !== null) {
        return;
    }

    selectedSpan.from = dayjs(from.value);
    selectedSpan.to = dayjs(date);

    clampSpan();
}

function onDateClicked(date) {
    if (to.value === null) {
        to.value = dayjs(date);
    } else {
        from.value = dayjs(date);
        to.value = null;
    }

    clampFromTo();
}

function onLeaveCalendar() {
    if (to.value !== null)
        return;

    selectedSpan.to = dayjs(selectedSpan.from);

    clampSpan();
}

function selectLastWeek() {
    from.value = dayjs().startOf('day').subtract(6, 'day');
    to.value = dayjs().endOf('day');

    clampFromTo();
}

function selectLastMonths(amount) {
    switch (newAccuracy.value) {
        case 'day':
            from.value = dayjs().startOf('day').subtract(amount, 'month').add(1, 'day');
            to.value = dayjs().endOf('day');
            break;
        case 'week':
            from.value = dayjs().subtract(amount, 'month').startOf('week');
            to.value = dayjs().endOf('week');
            break;
        default:
            from.value = dayjs().startOf('month').subtract(amount - 1, 'month');
            to.value = dayjs().endOf('month');
            break;
    }

    clampFromTo();
}

function setAccuracy(acc) {
    newAccuracy.value = acc;

    clampFromTo();
}

function showPicker() {
    from.value = props.start || dayjs().startOf('day').subtract(6, 'days');
    to.value = props.end || dayjs().endOf('day');
    newAccuracy.value = props.accuracy || 'day';
    selectedSpan.from = dayjs(from.value);
    selectedSpan.to = dayjs(to.value);
    showPeriodPicker.value = true;

}

function hidePicker() {

    showPeriodPicker.value = false;
}

function apply() {
    emit('apply', {
        from: dayjs(from.value),
        to: dayjs(to.value),
        accuracy: newAccuracy.value,
    });

    showPeriodPicker.value = false;
}
</script>

<template>
    <div class="relative">
        <button
            class="bg-white hover:bg-neutral-99 rounded-lg border border-neutral-70 h-10 px-2 flex items-center gap-4 text-xs"
            type="button"
            @click="showPicker()"
        >
            <span class="ml-1">{{ spanText }}</span>
            <IconCalendar class="size-4 text-neutral-70 shrink-0"/>
            <span>{{ rangeText(start, end, accuracy) }}</span>
            <IconChevronDown class="size-6 shrink-0 -ml-1"/>
        </button>

        <Modal :show="showPeriodPicker" class="top-full left-0 -ml-4 -mt-1 flex flex-col gap-8" @close="hidePicker">
            <div class="shrink-0 flex items-start gap-8 w-max">
                <div class="shrink-0 flex flex-col gap-2 items-stretch">
                    <Btn :disabled="newAccuracy !== 'day'" @click="selectLastWeek">Senaste veckan</Btn>
                    <Btn @click="selectLastMonths(1)">Senaste månaden</Btn>
                    <Btn @click="selectLastMonths(3)">Senaste 3 månader</Btn>
                    <Btn :disabled="newAccuracy === 'day'" @click="selectLastMonths(6)">Senaste halvåret</Btn>
                    <Btn :disabled="newAccuracy === 'day'" @click="selectLastMonths(12)">Senaste året</Btn>
                </div>
                <div class="shrink-0 flex flex-col gap-4 w-max">
                    <div v-if="newAccuracy !== 'month'" class="flex items-start gap-2 relative">
                        <div class="flex flex-col gap-3">
                            <div class="flex items-center justify-center text-sm font-bold h-8">{{
                                    dayjs(`${prevYear}-${prevMonth}-1`).format('MMM YYYY')
                                }}
                            </div>
                            <CalendarMonth :month="prevMonth" :pick="newAccuracy" :selected-span="selectedSpan"
                                           :weeks="newAccuracy === 'week'" :year="prevYear"
                                           @mouseleave="onLeaveCalendar" @date-clicked="onDateClicked"
                                           @date-hovered="onDateHovered"/>
                        </div>
                        <div class="flex flex-col gap-3">
                            <div class="flex items-center justify-center text-sm font-bold h-8">{{
                                    dayjs(`${currentYear}-${currentMonth}-1`).format('MMM YYYY')
                                }}
                            </div>
                            <CalendarMonth :month="currentMonth" :pick="newAccuracy" :selected-span="selectedSpan"
                                           :weeks="newAccuracy === 'week'" :year="currentYear"
                                           @mouseleave="onLeaveCalendar" @date-clicked="onDateClicked"
                                           @date-hovered="onDateHovered"/>
                        </div>
                        <div class="flex flex-col gap-3">
                            <div class="flex items-center justify-center text-sm font-bold h-8">{{
                                    dayjs(`${nextYear}-${nextMonth}-1`).format('MMM YYYY')
                                }}
                            </div>
                            <CalendarMonth :month="nextMonth" :pick="newAccuracy" :selected-span="selectedSpan"
                                           :weeks="newAccuracy === 'week'" :year="nextYear"
                                           @mouseleave="onLeaveCalendar" @date-clicked="onDateClicked"
                                           @date-hovered="onDateHovered"/>
                        </div>
                        <Btn class="absolute left-0 top-0" @click="decMonth">
                            <IconChevronDown class="rotate-90 size-6 -my-0.5 -mx-1.5"/>
                        </Btn>
                        <Btn class="absolute right-0 top-0" @click="incMonth">
                            <IconChevronDown class="-rotate-90 size-6 -my-0.5 -mx-1.5"/>
                        </Btn>
                    </div>
                    <div v-else class="flex items-start gap-2 relative">
                        <div class="flex flex-col gap-3">
                            <div class="flex items-center justify-center text-sm font-bold h-8">
                                {{ nextYear - 2 }}
                            </div>
                            <CalendarYear :selected-span="selectedSpan" :year="nextYear - 2"
                                          @mouseleave="onLeaveCalendar" @date-clicked="onDateClicked"
                                          @date-hovered="onDateHovered"/>
                        </div>
                        <div class="flex flex-col gap-3">
                            <div class="flex items-center justify-center text-sm font-bold h-8">
                                {{ nextYear - 1 }}
                            </div>
                            <CalendarYear :selected-span="selectedSpan" :year="nextYear - 1"
                                          @mouseleave="onLeaveCalendar" @date-clicked="onDateClicked"
                                          @date-hovered="onDateHovered"/>
                        </div>
                        <div class="flex flex-col gap-3">
                            <div class="flex items-center justify-center text-sm font-bold h-8">
                                {{ nextYear }}
                            </div>
                            <CalendarYear :selected-span="selectedSpan" :year="nextYear"
                                          @mouseleave="onLeaveCalendar" @date-clicked="onDateClicked"
                                          @date-hovered="onDateHovered"/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="shrink-0 flex items-end gap-2">
                <Btn :primary="newAccuracy === 'day'" @click="setAccuracy('day')">Per dag</Btn>
                <Btn :primary="newAccuracy === 'week'" @click="setAccuracy('week')">Per vecka</Btn>
                <Btn :primary="newAccuracy === 'month'" @click="setAccuracy('month')">Per månad</Btn>

                <div class="flex-1"></div>

                <div
                    class="bg-white border border-neutral-80 rounded px-4 h-8 flex items-center justify-center text-sm select-none">
                    {{ rangeText(from, to, newAccuracy) }}
                </div>

                <div class="flex-1"></div>

                <Btn @click="hidePicker()">
                    Avbryt
                </Btn>
                <Btn :disabled="!to" primary @click="apply">
                    Tillämpa
                </Btn>
            </div>
        </Modal>
    </div>
</template>
