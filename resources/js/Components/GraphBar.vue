<script setup>
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

function barPath(value, index) {
    const yMin = getY(0);
    const yMax = getY(value);
    const halfSize = props.sectionSize / 2;
    const halfBarWidth = Math.min(10, halfSize * 0.4);
    const xMin = (props.sectionSize * index + halfSize) - halfBarWidth;
    const xMax = xMin + halfBarWidth * 2;

    return `M${xMin},${yMin} L${xMin},${yMax} L${xMax},${yMax} L${xMax},${yMin} Z`;
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
                  class="fill-healthcare"
                  stroke-linecap="round"
                  stroke-linejoin="round"/>
        </template>

        <slot/>
    </svg>
</template>
