<script setup>
import {computed} from "vue";
import GraphReferenceLine from "@/Components/GraphReferenceLine.vue";
import GraphReferenceArea from "@/Components/GraphReferenceArea.vue";

const props = defineProps({
    data: Array,
    width: [Number, null],
    height: [Number, null],
    min: [Number, null],
    max: [Number, null],
    numSections: [Number, null],
    sectionSize: [Number, null],
    referenceValue: [Number, Object, null],
});

function getY(v) {
    return props.height - ((v - props.min) / (props.max - props.min)) * props.height;
}

const radius = computed(() => Math.min(4, props.sectionSize * 0.15));

function barPath(value, index) {
    const yMin = getY(value[0]) - radius.value;
    const yMax = getY(value[1]) + radius.value;
    const x = props.sectionSize * index + (props.sectionSize / 2);

    return `M${x},${yMin} L${x},${yMax}`;
}
</script>

<template>
    <svg v-if="width && height" :height="height" :viewBox="`0 0 ${width} ${height}`" :width="width"
         class="size-full" preserveAspectRatio="none">

        <template v-if="referenceValue">
            <GraphReferenceLine v-if="typeof(referenceValue) === 'number'" :width="width" :y="getY(referenceValue)"/>
            <GraphReferenceArea v-else-if="typeof(referenceValue) === 'object'" :high="getY(referenceValue.high)"
                                :low="getY(referenceValue.low)" :width="width"/>
        </template>

        <template v-for="(dp, i) in data" :key="i">
            <path v-if="dp" :d="barPath(dp, i)"
                  :style="{'--radius': radius * 2}" class="stroke-orange stroke-[length:--radius]"
                  stroke-linecap="round"
                  stroke-linejoin="round"/>
        </template>

        <slot/>
    </svg>
</template>
