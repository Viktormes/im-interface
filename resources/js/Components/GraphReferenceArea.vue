<script setup>
defineProps({
    high: Number,
    low: Number,
    width: Number,
});
</script>

<template>
    <path :d="`M0,${low} L${width},${low} L${width},${high} L0,${high} Z`" class="fill-healthcare/5"/>
    <path :d="`M0,${low} L${width},${low}`" class="stroke stroke-neutral-80" stroke-dasharray="5"/>
    <path :d="`M0,${high} L${width},${high}`" class="stroke stroke-neutral-80" stroke-dasharray="5"/>
</template>
