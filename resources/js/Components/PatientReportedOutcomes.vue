<script setup>
import {computed, onBeforeUnmount, onMounted, reactive, ref} from "vue";
import dayjs from "dayjs";
import IconMeasurement from "@/Icons/Measurements/IconMeasurement.vue";
import {formatString} from "../util.js";

const props = defineProps({
    period: Object,
    items: Array,
});

const graphWrapper = ref();
const settings = reactive({
    width: null,
    height: null,
});

let resizeTimer = null;

function onResize() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(updateSize, 300);
}

function updateSize() {
    settings.width = graphWrapper.value?.clientWidth;
}

onMounted(() => {
    updateSize();
    window.addEventListener('resize', onResize);
});
onBeforeUnmount(() => {
    window.addEventListener('resize', onResize);
});

const numSections = computed(() => dayjs(props.period.end).diff(dayjs(props.period.start), props.period.accuracy));
const sectionSize = computed(() => settings.width / numSections.value);
const labelModulo = computed(() => sectionSize.value > 60 ? 1 : (sectionSize.value > 40 ? 2 : 3));

const labels = computed(() => {
    let date = dayjs(props.period.start);
    const end = dayjs(props.period.end);

    const result = [];

    while (date < end) {
        switch (props.period.accuracy) {
            case 'month':
                result.push(date.format('MMM YYYY'));
                date = date.add(1, 'month');
                break;
            case 'week':
                result.push('v' + date.isoWeek());
                date = date.add(1, 'week');
                break;
            default:
                result.push(date.format('D/M'));
                date = date.add(1, 'day');
                break;
        }
    }

    return result;
});

const dataPoints = computed(() => {
    const result = [];

    for (let i = 0; i <= numSections.value; i++) {
        result[i] = [];
    }

    props.items.forEach(item => {
        const dateTime = dayjs(item.dateTime).startOf(props.period.accuracy);
        const section = dateTime.diff(dayjs(props.period.start), props.period.accuracy);

        result[section].push(item);
    });

    return result;
});

const maxItemsPerDay = computed(() => {
    let max = 0;
    dataPoints.value.forEach(i => {
        if (i.length > max) max = i.length;
    });
    return max;
})

const hoveredXAxis = ref(null);
const tooltip = ref(null);

function onMoveXAxis(e) {
    const t = Math.max(0, Math.min(1, e.offsetX / settings.width));

    hoveredXAxis.value = Math.max(0, Math.min(numSections.value, Math.round(t * numSections.value)));

    tooltip.value = null;

    if (!dataPoints.value[hoveredXAxis.value]?.length) return;

    const values = [];

    dataPoints.value[hoveredXAxis.value].forEach(item => {
        values.push(item);
    });

    tooltip.value = values;
}

function onLeaveXAxis() {
    hoveredXAxis.value = null;
    tooltip.value = null;
}
</script>

<template>
    <div class="bg-white border border-neutral-80 shadow p-3 rounded-lg text-sm relative">
        <div class="font-bold">Patient reported outcomes (PROMS)</div>

        <div class="px-8 pt-5" @mouseleave="onLeaveXAxis"
             @mousemove="onMoveXAxis">
            <div ref="graphWrapper" class="*:pointer-events-none relative">
                <div :style="{width: settings.width + 'px', height: Math.max(2.5, maxItemsPerDay * 2.5) + 'rem'}"
                     class="w-full relative">

                    <div v-if="hoveredXAxis !== null" :style="{left: (hoveredXAxis / numSections) * 100 + '%'}"
                         class="w-0.5 bg-green absolute inset-y-0 -ml-px"></div>

                    <template v-for="(_,i) in dataPoints" :key="i">
                        <div v-for="(item,j) in dataPoints[i]" :key="j"
                             :class="{
                                'z-10': i === hoveredXAxis,
                                'bg-culture-99 text-culture border-culture': item.referenceRange && item.value >= item.referenceRange,
                             }"
                             :style="{bottom: (j*2) + 'rem', left: (i / numSections) * 100 + '%'}"
                             class="bg-white rounded border border-neutral-80 absolute -translate-x-1/2 h-[1.7rem] mb-[0.4rem] flex items-center gap-1 px-1.5 text-2xs"
                        >
                            <IconMeasurement class="size-4"/>
                            {{ item.label }}
                        </div>
                    </template>
                </div>

                <div v-if="hoveredXAxis !== null && tooltip"
                     :style="{left: (hoveredXAxis / numSections) * 100 + '%'}"
                     class="absolute bottom-full mb-2 z-10 -translate-x-1/2 px-3 py-2 bg-white rounded-md border border-neutral-70 shadow-lg flex flex-col gap-2"
                >
                    <div v-for="(item,k) in tooltip" :key="k" class="flex items-center gap-2">
                        <IconMeasurement class="size-5"/>
                        <div class="text-2xs text-neutral-60 font-bold whitespace-nowrap">{{ item.label }}</div>
                        <div class="text-sm whitespace-nowrap">{{ formatString(item.format, item.value) }}</div>
                    </div>
                </div>
            </div>

            <!-- X Axis -->
            <div class="border-t border-neutral-80 relative *:pointer-events-none h-6 w-full"
                 @mouseleave="onLeaveXAxis"
                 @mousemove="onMoveXAxis">
                <span v-for="(_,i) in numSections + 1" :key="i"
                      :style="{left: (i * (sectionSize / settings.width) * 100) + '%'}"
                      class="w-px h-1.5 bg-neutral-80 absolute top-0 -ml-px -mt-px"
                ></span>

                <template v-for="(label,i) in labels" :key="i">
                        <span v-if="i % labelModulo === 0 || hoveredXAxis === i"
                              :class="{'font-bold bg-white z-10 px-4': hoveredXAxis === i}"
                              :style="{left: (i * (sectionSize / settings.width) * 100) + '%'}"
                              class="absolute top-1.5 text-2xs -translate-x-1/2 whitespace-nowrap"
                        >
                            {{ label }}
                        </span>
                </template>
            </div>
        </div>
    </div>
</template>
