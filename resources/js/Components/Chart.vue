<script setup>
import {computed, onBeforeUnmount, onMounted, reactive, ref, watch} from "vue";
import dayjs from "dayjs";
import GraphBar from "@/Components/GraphBar.vue";
import GraphLine from "@/Components/GraphLine.vue";
import GraphHighLow from "@/Components/GraphHighLow.vue";
import {formatString} from "../util.js";
import IconHeartRate from "@/Icons/Measurements/IconHeartRate.vue";
import IconSleep from "@/Icons/Measurements/IconSleep.vue";
import IconSteps from "@/Icons/Measurements/IconSteps.vue";
import IconHeartRateVariability from "@/Icons/Measurements/IconHeartRateVariability.vue";
import IconBreathingRate from "@/Icons/Measurements/IconBreathingRate.vue";
import IconDistance from "@/Icons/Measurements/IconDistance.vue";
import IconElevation from "@/Icons/Measurements/IconElevation.vue";
import IconCalories from "@/Icons/Measurements/IconCalories.vue";
import IconExercise from "@/Icons/Measurements/IconExercise.vue";
import IconSpO2 from "@/Icons/Measurements/IconSpO2.vue";
import IconVo2Max from "@/Icons/Measurements/IconVo2Max.vue";
import IconMeasurement from "@/Icons/Measurements/IconMeasurement.vue";

const props = defineProps({
    period: Object,
    chart: Object,
});

const graphWrapper = ref();
const settings = reactive({
    min: null,
    max: null,
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
    settings.height = graphWrapper.value?.clientHeight;
}

function updateSettings() {
    let min = props.chart.min, max = props.chart.max;

    dataPoints.value.forEach(dp => {
        const dpValues = Array.isArray(dp) ? dp : [dp];

        for (const v of dpValues) {
            if (v === null) continue;

            if (min === null) min = v;
            else min = Math.min(min, v);

            if (max === null) max = v;
            else max = Math.max(max, v);
        }
    });

    if (min < props.chart.min) {
        let base = Math.round(Math.log10(min));
        base = Math.min(1, Math.pow(10, base - 1));
        min = (Math.ceil(min / base) - 1) * base;
    }

    if (max > props.chart.max) {
        let base = Math.round(Math.log10(max));
        base = Math.max(1, Math.pow(10, base - 1));
        max = (Math.ceil(max / base) + 1) * base;
    }

    settings.min = min;
    settings.max = max;
}

const dataInSections = computed(() => props.chart.type !== 'line');
const numSections = computed(() => dayjs(props.period.end).diff(dayjs(props.period.start), props.period.accuracy) + (dataInSections.value ? 1 : 0));
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
    const valuePerDay = {};

    Object.keys(props.chart.data).forEach(k => {
        const date = dayjs(k).format('YYYY-MM-DD');

        const values = props.chart.data[k].map(dp => dp.value);

        if (props.chart.cumulative) {
            valuePerDay[date] = values.reduce((a, c) => a + c, 0);
        } else {
            let r = [];
            if (Array.isArray(values[0])) {
                values.forEach(v => {
                    v.forEach((v2, i) => {
                        if (r[i] === undefined) r[i] = 0;
                        r[i] += v[i];
                    });
                });
                r = r.map(v => v /= values.length);
            } else {
                r = values.reduce((a, c) => a + c, 0) / values.length;
            }

            if (Array.isArray(r) && props.chart.type !== 'high-low') {
                r = r.reduce((a, c) => a + c, 0) / r.length;
            }

            valuePerDay[date] = r;
        }

    });

    const result = [];
    const valuesPerSection = {};
    let sectionDate = dayjs(props.period.start);
    const endSection = dayjs(props.period.end);

    while (sectionDate < endSection) {
        const df = sectionDate.format('YYYY-MM-DD');
        let date = dayjs(sectionDate);
        const endDate = date.endOf(props.period.accuracy);

        valuesPerSection[df] = [];

        while (date < endDate) {
            const k = date.format('YYYY-MM-DD');

            if (valuePerDay[k]) {
                valuesPerSection[df].push(valuePerDay[k]);
            }

            date = date.add(1, 'day');
        }

        if (valuesPerSection[df].length) {
            valuesPerSection[df] = [...valuesPerSection[df], ...valuesPerSection[df]];

            const length = valuesPerSection[df].length;
            valuesPerSection[df] = valuesPerSection[df].reduce((a, c) => {
                if (a === null) {
                    return Array.isArray(c) ? [...c] : c;
                }

                if (Array.isArray(c)) {
                    c.forEach((v, i) => a[i] += v);
                    return a;
                }

                return a + c;
            }, null);

            if (Array.isArray(valuesPerSection[df])) {
                valuesPerSection[df] = valuesPerSection[df].map(v => v / length);
            } else {
                valuesPerSection[df] /= length;
            }

        } else {
            valuesPerSection[df] = null;
        }

        sectionDate = sectionDate.add(1, props.period.accuracy);
    }

    return Object.values(valuesPerSection);
});

function resolveGraph(type) {
    switch (type) {
        case 'bar':
            return GraphBar;
        case 'line':
            return GraphLine;
        case 'high-low':
            return GraphHighLow;
        default:
            return null;
    }
}

watch(() => dataPoints.value, updateSettings);
onMounted(() => {
    updateSize();
    updateSettings();
    window.addEventListener('resize', onResize);
});
onBeforeUnmount(() => {
    window.addEventListener('resize', onResize);
});

const hoveredYAxis = ref(null);

function onMoveYAxis(e) {
    hoveredYAxis.value = Math.max(0, Math.min(1, e.offsetY / settings.height));
}

function onLeaveYAxis() {
    hoveredYAxis.value = null;
}

const hoveredXAxis = ref(null);
const tooltip = ref(null);

function onMoveXAxis(e) {
    const t = Math.max(0, Math.min(1, e.offsetX / settings.width));

    if (dataInSections.value) {
        hoveredXAxis.value = Math.max(0, Math.min(numSections.value - 1, Math.floor(t * numSections.value)));
    } else {
        hoveredXAxis.value = Math.max(0, Math.min(numSections.value, Math.round(t * numSections.value)));
    }

    tooltip.value = null;

    if (dataPoints.value[hoveredXAxis.value] === null) return;

    const value = Array.isArray(dataPoints.value[hoveredXAxis.value]) ?
        dataPoints.value[hoveredXAxis.value].map(v => formatString(props.chart.format, v)).join(' - ') :
        formatString(props.chart.format, dataPoints.value[hoveredXAxis.value]);

    tooltip.value = {
        label: labels.value[hoveredXAxis.value],
        icon: props.chart.icon,
        value,
    };
}

function onLeaveXAxis() {
    hoveredXAxis.value = null;
    tooltip.value = null;
}

function getIcon(icon) {
    switch (icon) {
        case 'heart-rate':
            return IconHeartRate;
        case 'heart-rate-variability':
            return IconHeartRateVariability;
        case 'sleep':
            return IconSleep;
        case 'steps':
            return IconSteps;
        case 'distance':
            return IconDistance;
        case 'breathing-rate':
            return IconBreathingRate;
        case 'elevation':
            return IconElevation;
        case 'calories':
            return IconCalories;
        case 'exercise':
            return IconExercise;
        case 'spo2':
            return IconSpO2;
        case 'vo2max':
            return IconVo2Max;
        default:
            return IconMeasurement;
    }
}
</script>

<template>
    <div class="bg-white border border-neutral-80 shadow p-3 rounded-lg text-sm relative">
        <div class="font-bold">{{ chart.label }}</div>
        <div class="h-36 grid grid-cols-[3rem_1fr] grid-rows-[1fr_1.5rem] pr-8 pt-3">

            <!-- Y Axis -->
            <div class="border-r border-neutral-80 relative *:pointer-events-none" @mouseleave="onLeaveYAxis"
                 @mousemove="onMoveYAxis">
                <div class="absolute top-0 right-2 -translate-y-1/2 text-2xs whitespace-nowrap">
                    {{ formatString(chart.format, settings.max) }}
                </div>
                <div class="absolute bottom-0 right-2 translate-y-1/2 text-2xs whitespace-nowrap">
                    {{ formatString(chart.format, settings.min) }}
                </div>
                <div v-if="hoveredYAxis !== null" :style="{top: (hoveredYAxis * 100) + '%'}"
                     class="absolute right-1 -translate-y-full -mt-1 bg-white rounded border border-neutral-70 px-1 py-0.5 text-xs whitespace-nowrap">
                    {{
                        formatString(chart.format, settings.min + ((settings.max - settings.min) * (1 - hoveredYAxis)))
                    }}
                </div>
            </div>

            <!-- Graph -->
            <div ref="graphWrapper" class="overflow-hidden *:pointer-events-none" @mouseleave="onLeaveXAxis"
                 @mousemove="onMoveXAxis">
                <component :is="resolveGraph(chart.type)"
                           :data="dataPoints"
                           :num-sections="numSections"
                           :reference-value="chart.referenceValue"
                           :section-size="sectionSize"
                           v-bind="settings">

                    <path v-if="hoveredYAxis !== null"
                          :d="`M0,${settings.height * hoveredYAxis} L${settings.width},${settings.height * hoveredYAxis}`"
                          class="stroke stroke-green"/>

                    <path v-if="hoveredXAxis !== null"
                          :d="`M${hoveredXAxis * sectionSize + (dataInSections ? sectionSize * 0.5 : 0) - 0.5},0 l0,${settings.height}`"
                          class="stroke-2 stroke-green"/>

                </component>
            </div>

            <!-- Corner -->
            <div/>

            <!-- X Axis -->
            <div class="border-t border-neutral-80 relative *:pointer-events-none" @mouseleave="onLeaveXAxis"
                 @mousemove="onMoveXAxis">
                <span v-for="(_,i) in numSections + 1" :key="i"
                      :style="{left: (i * (sectionSize / settings.width) * 100) + '%'}"
                      class="w-px h-1.5 bg-neutral-80 absolute top-0 -ml-px -mt-px"
                ></span>

                <template v-for="(label,i) in labels" :key="i">
                    <span v-if="i % labelModulo === 0 || hoveredXAxis === i"
                          :class="{'font-bold bg-white z-10 px-4': hoveredXAxis === i}"
                          :style="{left: ((i + (dataInSections ? 0.5 : 0)) * (sectionSize / settings.width) * 100) + '%'}"
                          class="absolute top-1.5 text-2xs -translate-x-1/2 whitespace-nowrap"
                    >
                        {{ label }}
                    </span>
                </template>
            </div>

            <div v-if="hoveredXAxis !== null && tooltip"
                 :class="{
                    '!-translate-x-1/4': !dataInSections && hoveredXAxis === 0,
                    '!-translate-x-3/4': !dataInSections && hoveredXAxis === numSections,
                 }"
                 :style="{left: (hoveredXAxis * sectionSize) + (dataInSections ? (sectionSize / 2) : 0) + 'px'}"
                 class="absolute bottom-40 mb-2 z-10 ml-[3.75rem] -translate-x-1/2 px-3 py-2 bg-white rounded-md border border-neutral-70 shadow-lg"
            >
                <div class="text-2xs text-neutral-60 font-bold whitespace-nowrap">{{ tooltip.label }}</div>
                <div class="flex items-center gap-2 mt-2">
                    <component :is="getIcon(tooltip.icon)" v-if="tooltip.icon" class="size-6"/>
                    <div class="text-sm whitespace-nowrap">{{ tooltip.value }}</div>
                </div>
            </div>
        </div>
    </div>
</template>
