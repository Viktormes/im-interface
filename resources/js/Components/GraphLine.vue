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

function linePath() {
    const points = [];

    const offset = props.sectionSize / 2.5;

    let prevWasData = false;
    for (let i = 0; i < props.data.length; i++) {
        let dp = props.data[i];

        if (dp !== null) {
            const y = getY(dp);
            const x = i * props.sectionSize;

            if (!prevWasData) {
                points.push(`M ${x} ${y}`);
            } else {
                const x1 = (i - 1) * props.sectionSize + offset;
                const x2 = x - offset;

                const y1 = getY(props.data[i - 1]);
                const y2 = y;

                points.push(`C ${x1} ${y1}, ${x2} ${y2}, ${x} ${y}`);
            }
        }

        prevWasData = dp !== null;
    }

    return points.join(' ');
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

        <path :d="linePath()"
              class="stroke-2 stroke-healthcare fill-none"
              stroke-linecap="round"
              stroke-linejoin="round"/>

        <template v-for="(dp, i) in data" :key="i">
            <circle v-if="dp !== null" :cx="i * sectionSize" :cy="getY(dp)" r="3"/>
        </template>

        <slot/>
    </svg>
</template>
